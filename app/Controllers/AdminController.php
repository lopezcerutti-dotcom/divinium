<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\View;
use App\Integrations\Bling\BlingService;

class AdminController
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Dashboard Principal do Administrador
     */
    public function dashboard(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $totalProducts = (int) $this->pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
        $activeProducts = (int) $this->pdo->query('SELECT COUNT(*) FROM products WHERE is_active = 1')->fetchColumn();
        $totalCategories = (int) $this->pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn();
        $totalStock = (int) $this->pdo->query('SELECT COALESCE(SUM(stock_quantity), 0) FROM products')->fetchColumn();
        
        $productsStmt = $this->pdo->query('SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC LIMIT 10');
        $products = $productsStmt->fetchAll();

        $categoriesStmt = $this->pdo->query('SELECT * FROM categories ORDER BY sort_order ASC, name ASC');
        $categories = $categoriesStmt->fetchAll();

        $blingService = new BlingService($this->pdo);
        $blingConfig = $blingService->getConfig();
        $storeSettings = \App\Models\Setting::getAll();

        View::render('admin/dashboard', [
            'pageTitle' => 'Painel de Controle | Divinium',
            'adminUser' => $_SESSION['admin_user'] ?? ['name' => 'Administrador', 'email' => 'admin@divinium.com.br'],
            'totalProducts' => $totalProducts,
            'activeProducts' => $activeProducts,
            'totalCategories' => $totalCategories,
            'totalStock' => $totalStock,
            'products' => $products,
            'categories' => $categories,
            'blingConfig' => $blingConfig,
            'storeSettings' => $storeSettings,
        ], 'admin');
    }

    /**
     * Gestão de Produtos
     */
    /**
     * Gestão de Produtos & Links de Venda Externa
     */
    public function products(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $totalProducts = (int) $this->pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
        $activeProducts = (int) $this->pdo->query("SELECT COUNT(*) FROM products WHERE (status = 'published' OR (status IS NULL AND is_active = 1))")->fetchColumn();
        $totalCategories = (int) $this->pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn();
        $totalStock = (int) $this->pdo->query('SELECT COALESCE(SUM(stock_quantity), 0) FROM products')->fetchColumn();

        $productsStmt = $this->pdo->query('
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            ORDER BY p.sort_order ASC, p.id DESC
        ');
        $products = $productsStmt ? $productsStmt->fetchAll(\PDO::FETCH_ASSOC) : [];

        // Carregar links de plataformas para cada produto
        $linksStmt = $this->pdo->query('SELECT * FROM product_links ORDER BY sort_order ASC, id ASC');
        $allLinks = $linksStmt ? $linksStmt->fetchAll(\PDO::FETCH_ASSOC) : [];
        $linksByProduct = [];
        foreach ($allLinks as $l) {
            $linksByProduct[(int) $l['product_id']][] = $l;
        }

        foreach ($products as &$p) {
            $p['links'] = $linksByProduct[(int) $p['id']] ?? [];
            if (empty($p['links']) && !empty($p['external_url'])) {
                $p['links'] = [[
                    'id' => 0,
                    'product_id' => $p['id'],
                    'platform_name' => 'Shopee',
                    'url' => $p['external_url'],
                    'price_cents' => $p['price_cents'] ?? 38000,
                    'button_text' => 'Comprar na Shopee Oficial',
                    'button_color' => 'default',
                    'button_size' => 'md',
                    'is_active' => 1,
                    'sort_order' => 0
                ]];
            }
        }
        unset($p);

        $categories = $this->pdo->query('SELECT * FROM categories ORDER BY sort_order ASC, name ASC')->fetchAll(\PDO::FETCH_ASSOC);

        $blingService = new BlingService($this->pdo);
        $blingConfig = $blingService->getConfig();

        View::render('admin/products', [
            'pageTitle' => 'Catálogo de Joias & Canais de Venda',
            'adminUser' => $_SESSION['admin_user'] ?? ['name' => 'Administrador'],
            'totalProducts' => $totalProducts,
            'activeProducts' => $activeProducts,
            'totalCategories' => $totalCategories,
            'totalStock' => $totalStock,
            'products' => $products,
            'categories' => $categories,
            'blingConfig' => $blingConfig,
        ], 'admin');
    }

    /**
     * Salvar Produto (Novo ou Edição) com Campos Independentes e Multi-Links
     */
    public function saveProduct(): void
    {
        $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
        $name = trim((string) ($_POST['name'] ?? ''));
        $categoryId = !empty($_POST['category_id']) ? (int) $_POST['category_id'] : null;
        $stoneMineral = trim((string) ($_POST['stone_mineral'] ?? ''));
        $materials = trim((string) ($_POST['materials'] ?? ''));
        $dimensions = trim((string) ($_POST['dimensions'] ?? ''));
        $colors = trim((string) ($_POST['colors'] ?? ''));
        $variantsJson = trim((string) ($_POST['variants_json'] ?? ''));

        $price = (float) str_replace(['R$', ' ', '.', ','], ['', '', '', '.'], (string) ($_POST['price'] ?? '0'));
        $priceCents = (int) round($price * 100);
        if ($priceCents <= 0 && !empty($_POST['price_cents'])) {
            $priceCents = (int) $_POST['price_cents'];
        }

        $promotionalPrice = !empty($_POST['promotional_price']) 
            ? (float) str_replace(['R$', ' ', '.', ','], ['', '', '', '.'], (string) $_POST['promotional_price']) 
            : 0;
        $promotionalPriceCents = $promotionalPrice > 0 ? (int) round($promotionalPrice * 100) : (int) round($priceCents * 0.95);

        $stock = isset($_POST['stock_quantity']) ? (int) $_POST['stock_quantity'] : 10;
        $stockStatus = trim((string) ($_POST['stock_status'] ?? 'in_stock'));
        $shortDescription = trim((string) ($_POST['short_description'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $imageUrl = trim((string) ($_POST['image_url'] ?? ''));
        $sortOrder = isset($_POST['sort_order']) ? (int) $_POST['sort_order'] : 0;
        $isFeatured = !empty($_POST['is_featured']) ? 1 : 0;

        $status = trim((string) ($_POST['status'] ?? 'published'));
        if (!in_array($status, ['published', 'draft', 'hidden'], true)) {
            $status = (!empty($_POST['is_active']) && $_POST['is_active'] == '1') ? 'published' : 'hidden';
        }
        $isActive = ($status === 'published') ? 1 : 0;

        if ($name === '') {
            $this->respond(['success' => false, 'error' => 'O nome do produto é obrigatório.'], 200);
            return;
        }

        $rawSlug = trim((string) ($_POST['slug'] ?? ''));
        if ($rawSlug !== '') {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $rawSlug), '-'));
        } else {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        }
        if ($slug === '') {
            $slug = 'joia-' . ($id ?? time());
        }

        $sku = !empty($_POST['sku']) ? trim($_POST['sku']) : 'DIV-' . strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $slug), 0, 8)) . '-' . rand(10, 99);

        // Upload de imagem caso enviado arquivo
        if (!empty($_FILES['image_file']['tmp_name']) && is_uploaded_file($_FILES['image_file']['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'avif'], true)) {
                $uploadDir = BASE_PATH . '/assets/images/products';
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0755, true);
                }
                $filename = 'prod-' . time() . '-' . rand(100, 999) . '.' . $ext;
                $dest = $uploadDir . '/' . $filename;
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $dest)) {
                    $imageUrl = '/assets/images/products/' . $filename;
                }
            }
        }

        if ($imageUrl === '') {
            $imageUrl = '/assets/images/hero/featured-green-shell.jpg';
        }

        $showBadge = isset($_POST['show_badge']) ? ((int) $_POST['show_badge'] ? 1 : 0) : 1;
        $showSku = isset($_POST['show_sku']) ? ((int) $_POST['show_sku'] ? 1 : 0) : 1;
        $showSpecs = isset($_POST['show_specs']) ? ((int) $_POST['show_specs'] ? 1 : 0) : 1;
        $showDescription = isset($_POST['show_description']) ? ((int) $_POST['show_description'] ? 1 : 0) : 1;

        $cardClickBehavior = trim((string) ($_POST['card_click_behavior'] ?? 'default'));
        if (!in_array($cardClickBehavior, ['default', 'detail', 'external', 'custom'], true)) {
            $cardClickBehavior = 'default';
        }
        $customTargetUrl = trim((string) ($_POST['custom_target_url'] ?? ''));
        $detailLayout = trim((string) ($_POST['detail_layout'] ?? 'default'));
        if (!in_array($detailLayout, ['default', 'list', 'grid'], true)) {
            $detailLayout = 'default';
        }
        $showChannels = isset($_POST['show_channels']) ? ((int) $_POST['show_channels'] ? 1 : 0) : 1;

        // Auto-verificação da coluna show_sku na tabela products
        try {
            $driver = $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
            if ($driver === 'sqlite') {
                $cols = $this->pdo->query("PRAGMA table_info(products)")->fetchAll(\PDO::FETCH_ASSOC);
                $hasShowSku = false;
                foreach ($cols as $c) {
                    if (($c['name'] ?? '') === 'show_sku') { $hasShowSku = true; break; }
                }
                if (!$hasShowSku) {
                    $this->pdo->exec("ALTER TABLE products ADD COLUMN show_sku INTEGER NOT NULL DEFAULT 1");
                }
            } else {
                $colCheck = $this->pdo->query("SHOW COLUMNS FROM products LIKE 'show_sku'");
                if ($colCheck && $colCheck->rowCount() === 0) {
                    $this->pdo->exec("ALTER TABLE products ADD COLUMN show_sku TINYINT(1) NOT NULL DEFAULT 1");
                }
            }
        } catch (\Throwable $e) {
            // Ignorar
        }

        try {
            if ($id !== null && $id > 0) {
                $stmt = $this->pdo->prepare('
                    UPDATE products SET 
                        name = ?, slug = ?, category_id = ?, stone_mineral = ?, materials = ?, dimensions = ?, 
                        colors = ?, variants_json = ?, price_cents = ?, promotional_price_cents = ?, 
                        stock_quantity = ?, stock_status = ?, status = ?, sort_order = ?, 
                        short_description = ?, description = ?, image_url = ?, show_badge = ?, 
                        show_sku = ?, show_specs = ?, show_description = ?, card_click_behavior = ?, custom_target_url = ?,
                        detail_layout = ?, show_channels = ?, is_active = ?, is_featured = ?
                    WHERE id = ?
                ');
                $stmt->execute([
                    $name, $slug, $categoryId, $stoneMineral, $materials, $dimensions, 
                    $colors, $variantsJson, $priceCents, $promotionalPriceCents, 
                    $stock, $stockStatus, $status, $sortOrder, 
                    $shortDescription, $description, $imageUrl, $showBadge, 
                    $showSku, $showSpecs, $showDescription, $cardClickBehavior, $customTargetUrl,
                    $detailLayout, $showChannels, $isActive, $isFeatured, $id
                ]);
                $productId = $id;
            } else {
                $uuid = 'prod-' . uniqid();
                $stmt = $this->pdo->prepare('
                    INSERT INTO products (
                        uuid, category_id, name, slug, sku, stone_mineral, materials, dimensions, 
                        colors, variants_json, price_cents, promotional_price_cents, stock_quantity, 
                        stock_status, status, sort_order, short_description, description, image_url, 
                        show_badge, show_sku, show_specs, show_description, card_click_behavior, custom_target_url,
                        detail_layout, show_channels, is_active, is_featured
                    ) VALUES (
                        ?, ?, ?, ?, ?, ?, ?, ?, 
                        ?, ?, ?, ?, ?, 
                        ?, ?, ?, ?, ?, ?, 
                        ?, ?, ?, ?, ?, ?,
                        ?, ?, ?, ?
                    )
                ');
                $stmt->execute([
                    $uuid, $categoryId, $name, $slug, $sku, $stoneMineral, $materials, $dimensions, 
                    $colors, $variantsJson, $priceCents, $promotionalPriceCents, $stock, 
                    $stockStatus, $status, $sortOrder, $shortDescription, $description, $imageUrl, 
                    $showBadge, $showSku, $showSpecs, $showDescription, $cardClickBehavior, $customTargetUrl,
                    $detailLayout, $showChannels, $isActive, $isFeatured
                ]);
                $productId = (int) $this->pdo->lastInsertId();
            }

            // Processar links externos de plataformas (sem limite fixo)
            $rawLinks = $_POST['links'] ?? [];
            if (is_string($rawLinks)) {
                $rawLinks = json_decode($rawLinks, true) ?: [];
            }

            $validLinks = [];
            $primaryExternalUrl = '';

            if (is_array($rawLinks)) {
                foreach ($rawLinks as $idx => $linkItem) {
                    $u = trim((string) ($linkItem['url'] ?? ''));
                    if ($u === '') {
                        continue;
                    }
                    if (!preg_match('#^https?://#i', $u)) {
                        $u = 'https://' . $u;
                    }
                    // Validar formato de URL
                    if (!filter_var($u, FILTER_VALIDATE_URL)) {
                        continue;
                    }

                    $plat = trim((string) ($linkItem['platform_name'] ?? 'Shopee'));
                    if ($plat === '') {
                        $plat = 'Shopee';
                    }

                    $lPrice = 0;
                    if (isset($linkItem['price']) && $linkItem['price'] !== '') {
                        $lPrice = (float) str_replace(['R$', ' ', '.', ','], ['', '', '', '.'], (string) $linkItem['price']);
                    }
                    $lPriceCents = $lPrice > 0 ? (int) round($lPrice * 100) : $priceCents;

                    $btnText = trim((string) ($linkItem['button_text'] ?? ''));
                    if ($btnText === '') {
                        $btnText = $plat === 'Shopee' ? 'Comprar na Shopee Oficial' : ('Comprar no ' . $plat);
                    }

                    $btnColor = trim((string) ($linkItem['button_color'] ?? 'default'));
                    if ($btnColor === '') $btnColor = 'default';

                    $btnSize = trim((string) ($linkItem['button_size'] ?? 'md'));
                    if (!in_array($btnSize, ['sm', 'md', 'lg', 'full'], true)) {
                        $btnSize = 'md';
                    }

                    $lTarget = trim((string) ($linkItem['target'] ?? '_blank'));
                    if (!in_array($lTarget, ['_blank', '_self'], true)) {
                        $lTarget = '_blank';
                    }

                    $lActive = isset($linkItem['is_active']) ? ((int) $linkItem['is_active'] ? 1 : 0) : 1;
                    $lSort = isset($linkItem['sort_order']) ? (int) $linkItem['sort_order'] : (int) $idx;

                    $validLinks[] = [
                        'platform_name' => $plat,
                        'url' => $u,
                        'price_cents' => $lPriceCents,
                        'button_text' => $btnText,
                        'button_color' => $btnColor,
                        'button_size' => $btnSize,
                        'target' => $lTarget,
                        'is_active' => $lActive,
                        'sort_order' => $lSort,
                    ];

                    if ($primaryExternalUrl === '' && $lActive === 1) {
                        $primaryExternalUrl = $u;
                    }
                }
            }

            // Excluir links anteriores e re-inserir links atualizados com segurança
            $this->pdo->prepare('DELETE FROM product_links WHERE product_id = ?')->execute([$productId]);

            if (!empty($validLinks)) {
                $insLink = $this->pdo->prepare('
                    INSERT INTO product_links (product_id, platform_name, url, price_cents, button_text, button_color, button_size, target, is_active, sort_order) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ');
                foreach ($validLinks as $vl) {
                    $insLink->execute([
                        $productId,
                        $vl['platform_name'],
                        $vl['url'],
                        $vl['price_cents'],
                        $vl['button_text'],
                        $vl['button_color'],
                        $vl['button_size'],
                        $vl['target'],
                        $vl['is_active'],
                        $vl['sort_order']
                    ]);
                }
            }

            // Atualizar external_url legado na tabela products para compatibilidade retroativa
            if ($primaryExternalUrl !== '') {
                $this->pdo->prepare('UPDATE products SET external_url = ? WHERE id = ?')->execute([$primaryExternalUrl, $productId]);
            }

            $this->respond([
                'success' => true, 
                'message' => 'Joia e canais de venda salvos com sucesso!',
                'product_id' => $productId
            ]);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao salvar produto: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Duplicar Produto (com todos os links associados e status Rascunho)
     */
    public function duplicateProduct(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->respond(['success' => false, 'error' => 'ID inválido.'], 200);
            return;
        }

        try {
            $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = ?');
            $stmt->execute([$id]);
            $prod = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$prod) {
                $this->respond(['success' => false, 'error' => 'Produto original não encontrado.'], 200);
                return;
            }

            $newName = $prod['name'] . ' (Cópia)';
            $newUuid = 'prod-' . uniqid();
            $slugBase = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $newName), '-'));
            $newSlug = $slugBase . '-' . rand(100, 999);
            $newSku = ($prod['sku'] ?? 'DIV-AUT') . '-COP' . rand(10, 99);
            $newStatus = 'draft';
            $newActive = 0;

            $insProd = $this->pdo->prepare('
                INSERT INTO products (
                    uuid, category_id, sku, name, slug, short_description, description, 
                    materials, stone_mineral, dimensions, colors, variants_json, 
                    price_cents, promotional_price_cents, stock_quantity, stock_status, 
                    status, sort_order, image_url, external_url, show_badge, show_specs, 
                    show_description, card_click_behavior, custom_target_url, detail_layout, 
                    show_channels, is_active, is_featured
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, 
                    ?, ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, 
                    ?, ?, ?
                )
            ');

            $insProd->execute([
                $newUuid,
                $prod['category_id'],
                $newSku,
                $newName,
                $newSlug,
                $prod['short_description'] ?? null,
                $prod['description'] ?? null,
                $prod['materials'] ?? null,
                $prod['stone_mineral'] ?? null,
                $prod['dimensions'] ?? null,
                $prod['colors'] ?? null,
                $prod['variants_json'] ?? null,
                $prod['price_cents'] ?? 38000,
                $prod['promotional_price_cents'] ?? null,
                $prod['stock_quantity'] ?? 10,
                $prod['stock_status'] ?? 'in_stock',
                $newStatus,
                ((int) ($prod['sort_order'] ?? 0)) + 1,
                $prod['image_url'] ?? null,
                $prod['external_url'] ?? null,
                $prod['show_badge'] ?? 1,
                $prod['show_specs'] ?? 1,
                $prod['show_description'] ?? 1,
                $prod['card_click_behavior'] ?? 'default',
                $prod['custom_target_url'] ?? null,
                $prod['detail_layout'] ?? 'default',
                $prod['show_channels'] ?? 1,
                $newActive,
                0
            ]);

            $newId = (int) $this->pdo->lastInsertId();

            // Duplicar todos os canais / links associados
            $linksStmt = $this->pdo->prepare('SELECT * FROM product_links WHERE product_id = ? ORDER BY sort_order ASC');
            $linksStmt->execute([$id]);
            $origLinks = $linksStmt->fetchAll(\PDO::FETCH_ASSOC);

            if (!empty($origLinks)) {
                $insLink = $this->pdo->prepare('
                    INSERT INTO product_links (product_id, platform_name, url, price_cents, button_text, button_color, button_size, target, is_active, sort_order) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ');
                foreach ($origLinks as $ol) {
                    $insLink->execute([
                        $newId,
                        $ol['platform_name'],
                        $ol['url'],
                        $ol['price_cents'],
                        $ol['button_text'],
                        $ol['button_color'] ?? 'default',
                        $ol['button_size'] ?? 'md',
                        $ol['target'] ?? '_blank',
                        $ol['is_active'],
                        $ol['sort_order']
                    ]);
                }
            }

            $this->respond([
                'success' => true, 
                'message' => 'Joia duplicada com sucesso em modo Rascunho!', 
                'new_id' => $newId
            ]);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao duplicar produto: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Alternar Visibilidade / Status do Produto (Publicado / Oculto)
     */
    public function toggleProduct(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->respond(['success' => false, 'error' => 'ID inválido.'], 200);
            return;
        }

        try {
            $prod = $this->pdo->query("SELECT is_active, status FROM products WHERE id = $id")->fetch(\PDO::FETCH_ASSOC);
            if (!$prod) {
                $this->respond(['success' => false, 'error' => 'Produto não encontrado.'], 200);
                return;
            }

            $currentlyPublished = (!empty($prod['is_active']) && ($prod['status'] ?? 'published') === 'published');
            $newActive = $currentlyPublished ? 0 : 1;
            $newStatus = $newActive === 1 ? 'published' : 'hidden';

            $stmt = $this->pdo->prepare('UPDATE products SET is_active = ?, status = ? WHERE id = ?');
            $stmt->execute([$newActive, $newStatus, $id]);

            $this->respond(['success' => true, 'is_active' => $newActive, 'status' => $newStatus]);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao alternar status: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Excluir Produto e seus Links Associados
     */
    public function deleteProduct(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->respond(['success' => false, 'error' => 'ID inválido.'], 200);
            return;
        }

        try {
            $this->pdo->prepare('DELETE FROM product_links WHERE product_id = ?')->execute([$id]);
            $this->pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);

            $this->respond(['success' => true, 'message' => 'Produto e seus canais foram removidos com sucesso.']);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao excluir produto: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Gestão de Categorias
     */
    public function categories(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Buscar categorias com contagens separadas de produtos principais e variantes
        $categories = $this->pdo->query('
            SELECT c.*, 
            (
                SELECT COUNT(*) 
                FROM products p 
                WHERE p.category_id = c.id 
                  AND (p.parent_sku IS NULL OR p.parent_sku = "" OR p.parent_sku = p.sku)
            ) AS main_products_count,
            (
                SELECT COUNT(*) 
                FROM products p 
                WHERE p.category_id = c.id 
                  AND p.parent_sku IS NOT NULL 
                  AND p.parent_sku != "" 
                  AND p.parent_sku != p.sku
            ) AS variants_count,
            (
                SELECT COUNT(*) 
                FROM products p 
                WHERE p.category_id = c.id
            ) AS product_count 
            FROM categories c 
            ORDER BY c.sort_order ASC, c.name ASC
        ')->fetchAll(\PDO::FETCH_ASSOC);

        // Fallback para seed_products.json caso as contagens no banco estejam zeradas
        $totalDbProds = (int) $this->pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
        if ($totalDbProds === 0) {
            $seedFile = dirname(__DIR__, 2) . '/database/seed_products.json';
            if (file_exists($seedFile)) {
                $seedContent = @file_get_contents($seedFile);
                $seedJson = json_decode((string)$seedContent, true);
                $items = isset($seedJson['value']) && is_array($seedJson['value']) ? $seedJson['value'] : (is_array($seedJson) ? $seedJson : []);
                
                $seedMainByCat = [];
                $seedVarByCat = [];
                foreach ($items as $sp) {
                    $catId = (int)($sp['category_id'] ?? 0);
                    $sku = trim((string)($sp['sku'] ?? ''));
                    $parent = trim((string)($sp['parent_sku'] ?? ''));
                    if ($parent === '' || $parent === $sku) {
                        $seedMainByCat[$catId] = ($seedMainByCat[$catId] ?? 0) + 1;
                    } else {
                        $seedVarByCat[$catId] = ($seedVarByCat[$catId] ?? 0) + 1;
                    }
                }

                foreach ($categories as &$c) {
                    $cId = (int)$c['id'];
                    $c['main_products_count'] = $seedMainByCat[$cId] ?? 0;
                    $c['variants_count'] = $seedVarByCat[$cId] ?? 0;
                    $c['product_count'] = ($c['main_products_count'] ?? 0) + ($c['variants_count'] ?? 0);
                }
                unset($c);
            }
        }

        View::render('admin/categories', [
            'pageTitle' => 'Gerenciar Categorias & Coleções | Catálogo Divinium',
            'adminUser' => $_SESSION['admin_user'] ?? ['name' => 'Administrador'],
            'categories' => $categories,
        ], 'admin');
    }

    /**
     * Salvar Categoria
     */
    public function saveCategory(): void
    {
        $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
        $name = trim((string) ($_POST['name'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $sortOrder = isset($_POST['sort_order']) ? (int) $_POST['sort_order'] : 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($name === '') {
            $this->respond(['success' => false, 'error' => 'O nome da categoria é obrigatório.'], 200);
            return;
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

        try {
            if ($id !== null && $id > 0) {
                $stmt = $this->pdo->prepare('UPDATE categories SET name = ?, slug = ?, description = ?, sort_order = ?, is_active = ? WHERE id = ?');
                $stmt->execute([$name, $slug, $description, $sortOrder, $isActive, $id]);
            } else {
                $uuid = 'cat-' . uniqid();
                $stmt = $this->pdo->prepare('INSERT INTO categories (uuid, name, slug, description, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$uuid, $name, $slug, $description, $sortOrder, $isActive]);
            }

            $this->respond(['success' => true, 'message' => 'Categoria gravada com sucesso!']);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao salvar categoria: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Excluir Categoria
     */
    public function deleteCategory(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->respond(['success' => false, 'error' => 'ID inválido.'], 200);
            return;
        }

        $stmt = $this->pdo->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->execute([$id]);

        $this->respond(['success' => true, 'message' => 'Categoria removida com sucesso.']);
    }

    /**
     * Retorna produtos principais vinculados à categoria, com suas variantes Bling e links de compra
     */
    public function categoryProducts(): void
    {
        $categoryId = (int) ($_GET['categoria_id'] ?? 0);
        if ($categoryId <= 0) {
            $this->respond(['success' => false, 'error' => 'Categoria inválida.'], 200);
            return;
        }

        // 1. Buscar produtos desta categoria no banco
        $stmt = $this->pdo->prepare("
            SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.category_id = ?
            ORDER BY p.sort_order ASC, p.id ASC
        ");
        $stmt->execute([$categoryId]);
        $catProducts = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];

        // Mesclar ou carregar do seed_products.json caso necessário
        $seedFile = dirname(__DIR__, 2) . '/database/seed_products.json';
        $seedItems = [];
        if (file_exists($seedFile)) {
            $seedContent = @file_get_contents($seedFile);
            $seedJson = json_decode((string)$seedContent, true);
            $seedItems = isset($seedJson['value']) && is_array($seedJson['value']) ? $seedJson['value'] : (is_array($seedJson) ? $seedJson : []);
        }

        if (empty($catProducts)) {
            foreach ($seedItems as $sp) {
                if ((int)($sp['category_id'] ?? 0) === $categoryId) {
                    $catProducts[] = $sp;
                }
            }
        }

        // Indexar variantes por parent_sku
        $variantsByParentSku = [];
        $existingVarSkus = [];
        $totalVariants = 0;

        foreach ($catProducts as $p) {
            $sku = trim((string)($p['sku'] ?? ''));
            $parentSku = trim((string)($p['parent_sku'] ?? ''));
            if ($parentSku !== '' && $parentSku !== $sku) {
                $variantsByParentSku[$parentSku][] = $p;
                $existingVarSkus[$sku] = true;
                $totalVariants++;
            }
        }

        // Também enriquecer com variantes do catálogo Bling verificado (seed) se faltarem
        foreach ($seedItems as $sp) {
            $spSku = trim((string)($sp['sku'] ?? ''));
            $spParent = trim((string)($sp['parent_sku'] ?? ''));
            if ($spParent !== '' && $spParent !== $spSku && !isset($existingVarSkus[$spSku])) {
                $parentInCat = false;
                foreach ($catProducts as $cp) {
                    if (trim((string)($cp['sku'] ?? '')) === $spParent) {
                        $parentInCat = true;
                        break;
                    }
                }
                if ($parentInCat || (int)($sp['category_id'] ?? 0) === $categoryId) {
                    $variantsByParentSku[$spParent][] = $sp;
                    $existingVarSkus[$spSku] = true;
                    $totalVariants++;
                }
            }
        }

        $mainProducts = [];
        foreach ($catProducts as $p) {
            $sku = trim((string)($p['sku'] ?? ''));
            $parentSku = trim((string)($p['parent_sku'] ?? ''));
            $isMain = ($parentSku === '' || $parentSku === $sku);

            if ($isMain) {
                $pId = (int)($p['id'] ?? 0);

                // Carregar canais de venda (product_links)
                $links = [];
                if ($pId > 0) {
                    try {
                        $linkStmt = $this->pdo->prepare("SELECT * FROM product_links WHERE product_id = ? ORDER BY sort_order ASC, id ASC");
                        $linkStmt->execute([$pId]);
                        $links = $linkStmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
                    } catch (\Throwable $e) {}
                }
                if (empty($links) && !empty($p['external_url'])) {
                    $links[] = [
                        'id' => 0,
                        'product_id' => $pId,
                        'platform_name' => 'Shopee',
                        'url' => $p['external_url'],
                        'price_cents' => $p['price_cents'] ?? 38000,
                        'button_text' => 'Comprar na Shopee Oficial',
                        'is_active' => 1,
                        'sort_order' => 0,
                    ];
                }

                // Variantes deste produto
                $vars = $variantsByParentSku[$sku] ?? [];
                if (empty($vars)) {
                    // Fallback para variantes com prefixo do SKU
                    foreach ($catProducts as $other) {
                        $otherSku = trim((string)($other['sku'] ?? ''));
                        if ($otherSku !== $sku && str_starts_with($otherSku, $sku . '-')) {
                            $vars[] = $other;
                        }
                    }
                }

                // Filtrar item base genérico se houver variantes minerais autênticas
                $cleanVars = [];
                foreach ($vars as $v) {
                    $vStone = trim((string)($v['stone_mineral'] ?? ''));
                    $vSku = trim((string)($v['sku'] ?? ''));
                    if ($vSku === $sku && ($vStone === '' || str_contains(mb_strtolower($vStone), 'tradicional')) && count($vars) > 1) {
                        continue;
                    }
                    if (empty($v['image_url']) && !empty($p['image_url'])) {
                        $v['image_url'] = $p['image_url'];
                    }
                    $v['price_formatted'] = 'R$ ' . number_format(((int)($v['price_cents'] ?? ($p['price_cents'] ?? 38000))) / 100, 2, ',', '.');
                    $cleanVars[] = $v;
                }
                if (empty($cleanVars)) {
                    $cleanVars = $vars;
                }

                $p['price_formatted'] = 'R$ ' . number_format(((int)($p['price_cents'] ?? 38000)) / 100, 2, ',', '.');
                $p['variants'] = array_values($cleanVars);
                $p['variants_count'] = count($p['variants']);
                $p['links'] = $links;

                $mainProducts[] = $p;
            }
        }

        // Buscar outros produtos principais disponíveis no catálogo para associar
        $availableProducts = [];
        try {
            $availStmt = $this->pdo->prepare("
                SELECT p.id, p.name, p.sku, p.category_id, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE (p.category_id != ? OR p.category_id IS NULL)
                  AND (p.parent_sku IS NULL OR p.parent_sku = '' OR p.parent_sku = p.sku)
                ORDER BY p.name ASC
            ");
            $availStmt->execute([$categoryId]);
            $availableProducts = $availStmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\Throwable $e) {}

        if (empty($availableProducts) && !empty($seedItems)) {
            $seenAvail = [];
            foreach ($seedItems as $sp) {
                $pSku = trim((string)($sp['sku'] ?? ''));
                $pParent = trim((string)($sp['parent_sku'] ?? ''));
                if (($pParent === '' || $pParent === $pSku) && (int)($sp['category_id'] ?? 0) !== $categoryId) {
                    if (!isset($seenAvail[$pSku])) {
                        $seenAvail[$pSku] = true;
                        $availableProducts[] = [
                            'id' => $sp['id'],
                            'name' => $sp['name'],
                            'sku' => $sp['sku'],
                            'category_id' => $sp['category_id'],
                            'category_name' => $sp['category_name'] ?? 'Outra Categoria',
                        ];
                    }
                }
            }
        }

        $this->respond([
            'success' => true,
            'main_products_count' => count($mainProducts),
            'variants_count' => $totalVariants,
            'products' => $mainProducts,
            'available_products' => $availableProducts,
        ]);
    }

    /**
     * Associa um produto existente e suas variantes a uma categoria, sem duplicar registros
     */
    public function associateCategoryProduct(): void
    {
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $productId = (int) ($_POST['product_id'] ?? 0);

        if ($categoryId <= 0 || $productId <= 0) {
            $this->respond(['success' => false, 'error' => 'Dados inválidos para associação.'], 200);
            return;
        }

        try {
            $pStmt = $this->pdo->prepare("SELECT id, sku FROM products WHERE id = ? LIMIT 1");
            $pStmt->execute([$productId]);
            $prod = $pStmt->fetch(\PDO::FETCH_ASSOC);

            if (!$prod) {
                // Se produto existe apenas no seed, sincronizá-lo para persistência
                $seedFile = dirname(__DIR__, 2) . '/database/seed_products.json';
                if (file_exists($seedFile)) {
                    $seedJson = json_decode((string)file_get_contents($seedFile), true);
                    $items = isset($seedJson['value']) && is_array($seedJson['value']) ? $seedJson['value'] : (is_array($seedJson) ? $seedJson : []);
                    foreach ($items as $sp) {
                        if ((int)($sp['id'] ?? 0) === $productId) {
                            $prod = $sp;
                            break;
                        }
                    }
                }
            }

            if (!$prod) {
                $this->respond(['success' => false, 'error' => 'Produto não encontrado no catálogo.'], 200);
                return;
            }

            $sku = trim((string)($prod['sku'] ?? ''));

            // Atualizar produto principal e todas as suas variantes para a nova categoria sem duplicar
            $updateStmt = $this->pdo->prepare("
                UPDATE products 
                SET category_id = ? 
                WHERE id = ? OR (parent_sku = ? AND parent_sku != '') OR (sku LIKE ? AND sku != ?)
            ");
            $updateStmt->execute([$categoryId, $productId, $sku, $sku . '-%', $sku]);

            $this->respond([
                'success' => true, 
                'message' => 'Produto principal e suas variantes vinculados à categoria com sucesso!'
            ]);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao associar produto: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Desassocia um produto principal e suas variantes da categoria (preserva o produto intacto no catálogo)
     */
    public function disassociateCategoryProduct(): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);

        if ($productId <= 0) {
            $this->respond(['success' => false, 'error' => 'Produto inválido.'], 200);
            return;
        }

        try {
            $pStmt = $this->pdo->prepare("SELECT id, sku FROM products WHERE id = ? LIMIT 1");
            $pStmt->execute([$productId]);
            $prod = $pStmt->fetch(\PDO::FETCH_ASSOC);

            if ($prod) {
                $sku = trim((string)($prod['sku'] ?? ''));
                $stmt = $this->pdo->prepare("
                    UPDATE products 
                    SET category_id = NULL 
                    WHERE id = ? OR (parent_sku = ? AND parent_sku != '') OR (sku LIKE ? AND sku != ?)
                ");
                $stmt->execute([$productId, $sku, $sku . '-%', $sku]);
            }

            $this->respond([
                'success' => true,
                'message' => 'Produto desassociado da categoria com sucesso (o cadastro permanece preservado no catálogo).'
            ]);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao desassociar produto: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Salva alterações em uma variante (nome/gema mineral, SKU, preço, estoque e imagem)
     */
    public function saveVariant(): void
    {
        $variantId = !empty($_POST['variant_id']) ? (int) $_POST['variant_id'] : 0;
        $sku = trim((string) ($_POST['sku'] ?? ''));
        $name = trim((string) ($_POST['name'] ?? ''));
        $stoneMineral = trim((string) ($_POST['stone_mineral'] ?? ''));
        $price = (float) str_replace(['R$', ' ', '.', ','], ['', '', '', '.'], (string) ($_POST['price'] ?? '0'));
        $priceCents = (int) round($price * 100);
        $stock = isset($_POST['stock_quantity']) ? max(0, (int) $_POST['stock_quantity']) : 0;
        $imageUrl = trim((string) ($_POST['image_url'] ?? ''));

        if ($sku === '') {
            $this->respond(['success' => false, 'error' => 'SKU da variante é obrigatório.'], 200);
            return;
        }

        try {
            // Upload de imagem da variante se arquivo foi enviado
            if (!empty($_FILES['image_file']['tmp_name']) && is_uploaded_file($_FILES['image_file']['tmp_name'])) {
                $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'avif'], true)) {
                    $uploadDir = BASE_PATH . '/assets/images/products';
                    if (!is_dir($uploadDir)) {
                        @mkdir($uploadDir, 0755, true);
                    }
                    $filename = 'var-' . time() . '-' . rand(100, 999) . '.' . $ext;
                    if (move_uploaded_file($_FILES['image_file']['tmp_name'], $uploadDir . '/' . $filename)) {
                        $imageUrl = '/assets/images/products/' . $filename;
                    }
                }
            }

            if ($variantId > 0) {
                $stmt = $this->pdo->prepare("
                    UPDATE products 
                    SET name = COALESCE(NULLIF(?, ''), name),
                        stone_mineral = ?,
                        price_cents = CASE WHEN ? > 0 THEN ? ELSE price_cents END,
                        stock_quantity = ?,
                        image_url = CASE WHEN ? != '' THEN ? ELSE image_url END
                    WHERE id = ?
                ");
                $stmt->execute([$name, $stoneMineral, $priceCents, $priceCents, $stock, $imageUrl, $imageUrl, $variantId]);
            } else {
                $stmt = $this->pdo->prepare("
                    UPDATE products 
                    SET name = COALESCE(NULLIF(?, ''), name),
                        stone_mineral = ?,
                        price_cents = CASE WHEN ? > 0 THEN ? ELSE price_cents END,
                        stock_quantity = ?,
                        image_url = CASE WHEN ? != '' THEN ? ELSE image_url END
                    WHERE sku = ?
                ");
                $stmt->execute([$name, $stoneMineral, $priceCents, $priceCents, $stock, $imageUrl, $imageUrl, $sku]);
            }

            $this->respond([
                'success' => true,
                'message' => 'Variante atualizada com sucesso!',
                'image_url' => $imageUrl,
            ]);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao salvar variante: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Salva links e canais de venda configurados para um produto principal (compartilhados por todas as variantes)
     */
    public function saveProductChannels(): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);
        if ($productId <= 0) {
            $this->respond(['success' => false, 'error' => 'Produto principal inválido.'], 200);
            return;
        }

        $rawLinks = $_POST['links'] ?? [];
        if (is_string($rawLinks)) {
            $rawLinks = json_decode($rawLinks, true) ?: [];
        }

        try {
            $this->pdo->prepare("DELETE FROM product_links WHERE product_id = ?")->execute([$productId]);

            $insStmt = $this->pdo->prepare("
                INSERT INTO product_links 
                (product_id, platform_name, url, price_cents, button_text, button_color, button_size, target, is_active, sort_order)
                VALUES (?, ?, ?, ?, ?, 'default', 'md', '_blank', ?, ?)
            ");

            $primaryExternalUrl = '';

            if (is_array($rawLinks)) {
                foreach ($rawLinks as $idx => $link) {
                    $url = trim((string)($link['url'] ?? ''));
                    if ($url === '') continue;
                    if (!preg_match('#^https?://#i', $url)) {
                        $url = 'https://' . $url;
                    }
                    if (!filter_var($url, FILTER_VALIDATE_URL)) continue;

                    $plat = trim((string)($link['platform_name'] ?? 'Shopee'));
                    if ($plat === '') $plat = 'Shopee';

                    $priceFloat = 0;
                    if (isset($link['price']) && $link['price'] !== '') {
                        $priceFloat = (float) str_replace(['R$', ' ', '.', ','], ['', '', '', '.'], (string) $link['price']);
                    }
                    $priceCents = $priceFloat > 0 ? (int) round($priceFloat * 100) : null;

                    $btnText = trim((string)($link['button_text'] ?? ''));
                    if ($btnText === '') {
                        $btnText = $plat === 'Shopee' ? 'Comprar na Shopee Oficial' : ('Comprar no ' . $plat);
                    }

                    $isActive = isset($link['is_active']) ? ((int)$link['is_active'] ? 1 : 0) : 1;
                    $sort = isset($link['sort_order']) ? (int)$link['sort_order'] : $idx;

                    $insStmt->execute([$productId, $plat, $url, $priceCents, $btnText, $isActive, $sort]);

                    if ($primaryExternalUrl === '' && $isActive) {
                        $primaryExternalUrl = $url;
                    }
                }
            }

            if ($primaryExternalUrl !== '') {
                $this->pdo->prepare("UPDATE products SET external_url = ? WHERE id = ?")->execute([$primaryExternalUrl, $productId]);
            }

            $this->respond([
                'success' => true,
                'message' => 'Canais de venda e links compartilhados salvos com sucesso!'
            ]);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao salvar canais: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Editor Visual da Landing Page
     */
    public function landingPage(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $settings = \App\Models\Setting::getAll();

        View::render('admin/landing_page', [
            'pageTitle' => 'Editor da Landing Page | Divinium',
            'adminUser' => $_SESSION['admin_user'] ?? ['name' => 'Administrador', 'email' => 'admin@divinium.com.br'],
            'settings' => $settings,
        ], 'admin');
    }

    /**
     * Salvar alterações da Landing Page
     */
    public function saveLandingPage(): void
    {
        try {
            \App\Models\Setting::setMany($_POST);

            $this->respond([
                'success' => true,
                'message' => 'Conteúdo da Landing Page atualizado com sucesso!'
            ]);
        } catch (\Throwable $e) {
            error_log('Erro ao salvar Landing Page: ' . $e->getMessage());
            $this->respond([
                'success' => false,
                'error' => 'Erro ao salvar alterações: ' . $e->getMessage()
            ], 200);
        }
    }

    /**
     * Editor Visual de Elementos da Boutique (/loja)
     */
    public function boutique(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $settings = \App\Models\Setting::getAll();

        View::render('admin/boutique', [
            'pageTitle' => 'Personalizar Boutique DIVINIUM',
            'adminUser' => $_SESSION['admin_user'] ?? ['name' => 'Administrador', 'email' => 'admin@divinium.com.br'],
            'settings' => $settings,
        ], 'admin');
    }

    /**
     * Salvar alterações dos Elementos da Boutique
     */
    public function saveBoutique(): void
    {
        try {
            \App\Models\Setting::setMany($_POST);

            $this->respond([
                'success' => true,
                'message' => 'Elementos da Boutique atualizados com sucesso!'
            ]);
        } catch (\Throwable $e) {
            error_log('Erro ao salvar Boutique: ' . $e->getMessage());
            $this->respond([
                'success' => false,
                'error' => 'Erro ao salvar elementos da Boutique: ' . $e->getMessage()
            ], 200);
        }
    }

    /**
     * Seção Centralizada: Informações e Exibição dos Produtos
     * Reúne todas as informações públicas dos produtos, SKU, selos, botões,
     * especificações, preços e enquadramento de imagens.
     */
    public function productDisplay(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $settings = \App\Models\Setting::getAll();

        View::render('admin/product_display', [
            'pageTitle' => 'Informações e Exibição dos Produtos | DIVINIUM',
            'adminUser' => $_SESSION['admin_user'] ?? ['name' => 'Administrador', 'email' => 'admin@divinium.com.br'],
            'settings' => $settings,
        ], 'admin');
    }

    /**
     * Salvar configurações da Central de Informações e Exibição dos Produtos
     */
    public function saveProductDisplay(): void
    {
        try {
            // Sincroniza JSON de linhas da ficha técnica com as chaves individuais correspondentes
            if (!empty($_POST['store_specs_items_json'])) {
                $rawSpecs = is_string($_POST['store_specs_items_json']) 
                    ? json_decode($_POST['store_specs_items_json'], true) 
                    : $_POST['store_specs_items_json'];

                if (is_array($rawSpecs)) {
                    foreach ($rawSpecs as $sp) {
                        if (!is_array($sp)) continue;
                        $id = $sp['id'] ?? '';
                        $vis = !empty($sp['visible']) ? '1' : '0';
                        $title = trim((string)($sp['title'] ?? ''));
                        $desc = trim((string)($sp['desc'] ?? ''));

                        if ($id === 'material') {
                            $_POST['store_spec_mineral_visible'] = $vis;
                            $_POST['store_spec_mineral_label'] = $title;
                            $_POST['store_spec_mineral_override'] = $desc;
                        } elseif ($id === 'acabamento') {
                            $_POST['store_spec_materials_visible'] = $vis;
                            $_POST['store_spec_materials_label'] = $title;
                            $_POST['store_spec_materials_override'] = $desc;
                        } elseif ($id === 'design') {
                            $_POST['store_spec_design_visible'] = $vis;
                            $_POST['store_spec_design_label'] = $title;
                            $_POST['store_spec_design_text'] = $desc;
                        } elseif ($id === 'dimensoes') {
                            $_POST['store_spec_dimensions_visible'] = $vis;
                            $_POST['store_spec_dimensions_label'] = $title;
                            $_POST['store_spec_dimensions_override'] = $desc;
                        } elseif ($id === 'cores') {
                            $_POST['store_spec_colors_visible'] = $vis;
                            $_POST['store_spec_colors_label'] = $title;
                            $_POST['store_spec_colors_override'] = $desc;
                        } elseif ($id === 'autenticidade') {
                            $_POST['store_spec_authenticity_visible'] = $vis;
                            $_POST['store_spec_authenticity_label'] = $title;
                            $_POST['store_spec_authenticity_text'] = $desc;
                        } elseif ($id === 'sku') {
                            $_POST['store_spec_sku_visible'] = $vis;
                            $_POST['store_spec_sku_label'] = $title;
                        } elseif ($id === 'disponibilidade') {
                            $_POST['store_spec_availability_visible'] = $vis;
                            $_POST['store_spec_availability_label'] = $title;
                            $_POST['store_spec_availability_override'] = $desc;
                        }
                    }
                }
            }

            \App\Models\Setting::setMany($_POST);

            $this->respond([
                'success' => true,
                'message' => 'Informações e exibição dos produtos atualizadas com sucesso!'
            ]);
        } catch (\Throwable $e) {
            error_log('Erro ao salvar exibição de produtos: ' . $e->getMessage());
            $this->respond([
                'success' => false,
                'error' => 'Erro ao salvar alterações: ' . $e->getMessage()
            ], 200);
        }
    }

    /**
     * Gestão Centralizada de Coleções & Lançamentos da DIVINIUM
     * Organizada em categorias separadas (Braceletes, Colares, Anéis, etc.)
     */
    public function lancamentos(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $categories = \App\Models\Collection::getCategories();
        $collections = \App\Models\Collection::getAll();
        $grouped = \App\Models\Collection::getGroupedByCategory();

        // Buscar todos os produtos cadastrados no catálogo para vincular às coleções
        $products = [];
        try {
            $stmt = $this->pdo->query("
                SELECT p.id, p.name, p.slug, p.sku, p.price_cents, p.image_url, p.stone_mineral, p.category_id, p.is_active,
                       c.name as category_name, c.slug as category_slug
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.sort_order ASC, p.id DESC
            ");
            if ($stmt) {
                $products = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
            }
        } catch (\Throwable $e) {
            error_log('Erro ao carregar produtos para lançamentos: ' . $e->getMessage());
        }

        if (empty($products)) {
            $products = \App\Controllers\StoreController::getDefaultProducts();
        }

        View::render('admin/lancamentos', [
            'pageTitle' => 'Lançamentos & Gestão de Coleções',
            'adminUser' => $_SESSION['admin_user'] ?? ['name' => 'Administrador', 'email' => 'admin@divinium.com.br'],
            'categories' => $categories,
            'collections' => $collections,
            'grouped' => $grouped,
            'products' => $products,
        ], 'admin');
    }

    /**
     * Salva ou atualiza uma coleção com todos os seus atributos
     */
    public function saveColecao(): void
    {
        try {
            $data = $_POST;
            if (!empty($data['products_json']) && is_string($data['products_json'])) {
                $decodedProducts = json_decode($data['products_json'], true);
                if (is_array($decodedProducts)) {
                    $data['products'] = $decodedProducts;
                }
            }

            $saved = \App\Models\Collection::save($data);

            $this->respond([
                'success' => true,
                'message' => 'Coleção "' . ($saved['name'] ?? '') . '" salva com sucesso!',
                'collection' => $saved
            ]);
        } catch (\Throwable $e) {
            error_log('Erro ao salvar coleção: ' . $e->getMessage());
            $this->respond([
                'success' => false,
                'error' => 'Erro ao salvar coleção: ' . $e->getMessage()
            ], 200);
        }
    }

    /**
     * Exclui uma coleção (preservando todos os produtos intactos no catálogo)
     */
    public function deleteColecao(): void
    {
        try {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id <= 0) {
                $this->respond(['success' => false, 'error' => 'ID de coleção inválido.'], 200);
                return;
            }

            \App\Models\Collection::delete($id);

            $this->respond([
                'success' => true,
                'message' => 'Coleção excluída com sucesso. Todos os produtos vinculados foram mantidos intactos no catálogo.'
            ]);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao excluir coleção: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Reordena as coleções da loja pública
     */
    public function reorderColecoes(): void
    {
        try {
            $orderedIds = $_POST['ordered_ids'] ?? [];
            if (is_string($orderedIds)) {
                $orderedIds = json_decode($orderedIds, true) ?: [];
            }

            \App\Models\Collection::reorder($orderedIds);

            $this->respond([
                'success' => true,
                'message' => 'Ordem das coleções atualizada com sucesso!'
            ]);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao reordenar coleções: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Alterna a visibilidade (mostrar/ocultar) de uma coleção sem apagar seu conteúdo
     */
    public function toggleColecaoVisibility(): void
    {
        try {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id <= 0) {
                $this->respond(['success' => false, 'error' => 'ID inválido.'], 200);
                return;
            }

            $newState = \App\Models\Collection::toggleVisibility($id);

            $this->respond([
                'success' => true,
                'is_active' => $newState ? 1 : 0,
                'message' => $newState ? 'Coleção ativada na loja pública.' : 'Coleção ocultada da loja pública (conteúdo preservado).'
            ]);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao alternar visibilidade: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Alterna a visibilidade de um produto individual dentro de uma coleção
     */
    public function toggleColecaoProduto(): void
    {
        try {
            $collectionId = (int) ($_POST['collection_id'] ?? 0);
            $productId = (int) ($_POST['product_id'] ?? 0);

            if ($collectionId <= 0 || $productId <= 0) {
                $this->respond(['success' => false, 'error' => 'Parâmetros inválidos.'], 200);
                return;
            }

            $newState = \App\Models\Collection::toggleProductVisibility($collectionId, $productId);

            $this->respond([
                'success' => true,
                'is_active' => $newState ? 1 : 0,
                'message' => $newState ? 'Produto exibido nesta coleção.' : 'Produto ocultado nesta coleção.'
            ]);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao alternar visibilidade do produto: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Cria uma nova categoria dinamicamente para coleções
     */
    public function saveLancamentoCategoria(): void
    {
        try {
            $name = trim((string) ($_POST['name'] ?? ''));
            $desc = trim((string) ($_POST['description'] ?? ''));

            if ($name === '') {
                $this->respond(['success' => false, 'error' => 'O nome da categoria é obrigatório.'], 200);
                return;
            }

            $category = \App\Models\Collection::createCategory($name, $desc);

            $this->respond([
                'success' => true,
                'message' => 'Categoria "' . $name . '" criada com sucesso! Uma nova área independente já está disponível.',
                'category' => $category
            ]);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao criar categoria: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Dashboard de Acompanhamento (Analytics, Cliques Shopee e Métricas da Loja)
     */
    public function metrics(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $totalClicks = 0;
        $totalViews = 100;
        $ctr = 0.0;
        $potentialSalesCents = 0;
        $chartDays = [];
        $topProducts = [];
        $categoryClicks = [];
        $recentClicks = [];

        try {
            $driver = $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);

            // 1. Total de Cliques na Shopee
            try {
                $totalClicks = (int) $this->pdo->query('SELECT COUNT(*) FROM product_clicks')->fetchColumn();
            } catch (\Throwable $e) {
                $totalClicks = 0;
            }

            // 2. Total de Visualizações de Página
            try {
                $totalViews = (int) $this->pdo->query('SELECT COUNT(*) FROM page_views')->fetchColumn();
            } catch (\Throwable $e) {
                $totalViews = 0;
            }
            if ($totalViews === 0) {
                $totalViews = max(100, $totalClicks * 3);
            }

            // 3. Taxa de Intenção de Compra (CTR)
            $ctr = $totalViews > 0 ? round(($totalClicks / $totalViews) * 100, 1) : 0;

            // 4. Valor Potencial Estimado (soma dos preços das joias clicadas)
            try {
                $potentialSalesStmt = $this->pdo->query('
                    SELECT COALESCE(SUM(p.price_cents), 0) as total_cents 
                    FROM product_clicks c 
                    JOIN products p ON c.product_id = p.id
                ');
                $potentialSalesCents = (int) $potentialSalesStmt->fetchColumn();
            } catch (\Throwable $e) {
                $potentialSalesCents = 0;
            }

            // 5. Cliques dos últimos 7 dias (compatível com MySQL e SQLite)
            $dateCondition = ($driver === 'sqlite')
                ? "created_at >= DATE('now', '-7 days')"
                : "created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";

            $dailyClicks = [];
            try {
                $dailyClicksStmt = $this->pdo->query("
                    SELECT DATE(created_at) as click_date, COUNT(*) as clicks
                    FROM product_clicks
                    WHERE {$dateCondition}
                    GROUP BY DATE(created_at)
                    ORDER BY click_date ASC
                ");
                $dailyClicks = $dailyClicksStmt ? $dailyClicksStmt->fetchAll(\PDO::FETCH_ASSOC) : [];
            } catch (\Throwable $e) {
                $dailyClicks = [];
            }

            // Preencher 7 dias completos com label amigável
            for ($i = 6; $i >= 0; $i--) {
                $d = date('Y-m-d', strtotime("-$i days"));
                $dayLabel = date('d/m', strtotime("-$i days"));
                $clicks = 0;
                foreach ($dailyClicks as $row) {
                    if (($row['click_date'] ?? '') === $d) {
                        $clicks = (int) $row['clicks'];
                        break;
                    }
                }
                $chartDays[] = ['date' => $d, 'label' => $dayLabel, 'clicks' => $clicks];
            }

            // 6. Ranking de Produtos mais Clicados para a Shopee
            try {
                $topProductsStmt = $this->pdo->query('
                    SELECT p.id, p.name, p.image_url, p.price_cents, c.name as category_name, COUNT(pc.id) as clicks_count
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    LEFT JOIN product_clicks pc ON pc.product_id = p.id
                    GROUP BY p.id, p.name, p.image_url, p.price_cents, c.name
                    ORDER BY clicks_count DESC, p.id ASC
                    LIMIT 10
                ');
                $topProducts = $topProductsStmt ? $topProductsStmt->fetchAll(\PDO::FETCH_ASSOC) : [];
            } catch (\Throwable $e) {
                $topProducts = [];
            }

            // 7. Cliques por Categoria
            try {
                $categoryClicksStmt = $this->pdo->query('
                    SELECT c.name as category_name, COUNT(pc.id) as clicks_count
                    FROM categories c
                    LEFT JOIN products p ON p.category_id = c.id
                    LEFT JOIN product_clicks pc ON pc.product_id = p.id
                    GROUP BY c.id, c.name
                    ORDER BY clicks_count DESC
                ');
                $categoryClicks = $categoryClicksStmt ? $categoryClicksStmt->fetchAll(\PDO::FETCH_ASSOC) : [];
            } catch (\Throwable $e) {
                $categoryClicks = [];
            }

            // 8. Feed Recente em Tempo Real (Últimos 15 Cliques)
            try {
                $recentClicksStmt = $this->pdo->query('
                    SELECT pc.*, p.price_cents, p.image_url
                    FROM product_clicks pc
                    LEFT JOIN products p ON pc.product_id = p.id
                    ORDER BY pc.id DESC
                    LIMIT 15
                ');
                $recentClicks = $recentClicksStmt ? $recentClicksStmt->fetchAll(\PDO::FETCH_ASSOC) : [];
            } catch (\Throwable $e) {
                $recentClicks = [];
            }

        } catch (\Throwable $mainMetricsEx) {
            error_log('Erro geral ao carregar métricas: ' . $mainMetricsEx->getMessage());
        }

        $metricsConfig = \App\Models\Setting::getAll();

        View::render('admin/metrics', [
            'pageTitle' => 'Dashboard de Acompanhamento | Vendas Shopee & Métricas',
            'adminUser' => $_SESSION['admin_user'] ?? ['name' => 'Administrador', 'email' => 'admin@divinium.com.br'],
            'totalClicks' => $totalClicks,
            'totalViews' => $totalViews,
            'ctr' => $ctr,
            'potentialSalesCents' => $potentialSalesCents,
            'chartDays' => $chartDays,
            'topProducts' => $topProducts,
            'categoryClicks' => $categoryClicks,
            'recentClicks' => $recentClicks,
            'metricsConfig' => $metricsConfig,
        ], 'admin');
    }

    /**
     * Gestão de Temas & Identidade Visual
     */
    public function themes(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $defaultTheme = \App\Models\Setting::get('default_theme', 'theme-silver-noir');
        $customThemeSettings = \App\Models\Setting::getAll();

        $themesList = [
            [
                'id' => 'theme-silver-noir',
                'name' => 'Prata Nobre & Noir',
                'subtitle' => 'Clássico Minimalista',
                'description' => 'Elegância minimalista e sofisticada com branco perolado, preto suave, prata fosca e realces grafite. Uma joalheria de luxo discreto.',
                'primary_color' => '#A7ADB4',
                'accent_color' => '#737B85',
                'bg_color' => '#F8F8F7',
                'surface_color' => '#FFFFFF',
                'text_color' => '#202020',
                'text_muted' => '#686868',
                'badge' => 'Clássico Minimalista',
                'gradient' => 'linear-gradient(135deg, #FFFFFF 0%, #A7ADB4 50%, #737B85 100%)',
                'is_default' => in_array($defaultTheme, ['theme-silver-noir', 'theme-gold'], true)
            ],
            [
                'id' => 'theme-pearl-platinum',
                'name' => 'Branco Perolado & Platina',
                'subtitle' => 'Quiet Luxury',
                'description' => 'Minimalismo luminoso com superfícies brancas, detalhes em prata fosca e tipografia refinada em preto suave.',
                'primary_color' => '#A7ADB4',
                'accent_color' => '#737B85',
                'bg_color' => '#FFFFFF',
                'surface_color' => '#FFFFFF',
                'text_color' => '#202020',
                'text_muted' => '#686868',
                'badge' => 'Quiet Luxury',
                'gradient' => 'linear-gradient(135deg, #FFFFFF 0%, #CBD0D6 50%, #737B85 100%)',
                'is_default' => in_array($defaultTheme, ['theme-pearl-platinum', 'theme-silver'], true)
            ],
            [
                'id' => 'theme-nude-pearl',
                'name' => 'Pérola Pura & Prata',
                'subtitle' => 'Monocromático Nobre',
                'description' => 'Harmonia impecável de branco perolado, prata escovada e contraste preciso para destacar as joias com absoluta pureza.',
                'primary_color' => '#A7ADB4',
                'accent_color' => '#737B85',
                'bg_color' => '#F8F8F7',
                'surface_color' => '#FFFFFF',
                'text_color' => '#202020',
                'text_muted' => '#686868',
                'badge' => 'Monocromático Nobre',
                'gradient' => 'linear-gradient(135deg, #FFFFFF 0%, #A7ADB4 50%, #202020 100%)',
                'is_default' => in_array($defaultTheme, ['theme-nude-pearl', 'theme-emerald'], true)
            ],
            [
                'id' => 'theme-contemporary-graphite',
                'name' => 'Prata & Grafite Contemporâneo',
                'subtitle' => 'Contemporâneo',
                'description' => 'Estética moderna e arquitetônica com fundo cinza muito claro, superfícies brancas e acabamento em prata fosca e grafite.',
                'primary_color' => '#A7ADB4',
                'accent_color' => '#737B85',
                'bg_color' => '#F0F0EF',
                'surface_color' => '#FFFFFF',
                'text_color' => '#202020',
                'text_muted' => '#686868',
                'badge' => 'Contemporâneo',
                'gradient' => 'linear-gradient(135deg, #FFFFFF 0%, #A7ADB4 50%, #252525 100%)',
                'is_default' => in_array($defaultTheme, ['theme-contemporary-graphite', 'theme-rose'], true)
            ]
        ];

        View::render('admin/themes', [
            'pageTitle' => 'Gestão de Temas & Identidade Visual | Divinium',
            'adminUser' => $_SESSION['admin_user'] ?? ['name' => 'Administrador', 'email' => 'admin@divinium.com.br'],
            'defaultTheme' => $defaultTheme,
            'themes' => $themesList,
            'customSettings' => $customThemeSettings,
        ], 'admin');
    }

    /**
     * Salvar Tema Padrão do Site
     */
    public function saveDefaultTheme(): void
    {
        $theme = trim((string) ($_POST['theme_id'] ?? 'theme-silver-noir'));
        $allowed = [
            'theme-silver-noir', 'theme-pearl-platinum', 'theme-nude-pearl', 'theme-contemporary-graphite',
            'theme-gold', 'theme-silver', 'theme-emerald', 'theme-rose', 'theme-custom'
        ];
        if (!in_array($theme, $allowed, true)) {
            $this->respond(['success' => false, 'error' => 'Tema inválido.'], 200);
            return;
        }

        \App\Models\Setting::set('default_theme', $theme);
        $this->respond([
            'success' => true,
            'message' => 'Tema padrão do site atualizado com sucesso!'
        ]);
    }

    /**
     * Salvar Personalização da Paleta de Cores Customizada
     */
    public function saveCustomTheme(): void
    {
        try {
            \App\Models\Setting::setMany($_POST);
            $this->respond([
                'success' => true,
                'message' => 'Paleta personalizada e identidade visual gravadas com sucesso!'
            ]);
        } catch (\Throwable $e) {
            $this->respond([
                'success' => false,
                'error' => 'Erro ao salvar tema personalizado: ' . $e->getMessage()
            ], 200);
        }
    }

    /**
     * Upload de Imagem de Fundo (Site, Hero ou Admin)
     */
    public function uploadBackgroundImage(): void
    {
        try {
            if (empty($_FILES['bg_file']['tmp_name']) || !is_uploaded_file($_FILES['bg_file']['tmp_name'])) {
                $this->respond(['success' => false, 'error' => 'Nenhum arquivo de imagem foi enviado.'], 200);
                return;
            }

            $ext = strtolower(pathinfo($_FILES['bg_file']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
            if (!in_array($ext, $allowed, true)) {
                $this->respond(['success' => false, 'error' => 'Formato de arquivo inválido. Permitidos: JPG, PNG, WEBP, SVG.'], 200);
                return;
            }

            $targetKey = trim((string) ($_POST['target_key'] ?? 'site_bg_image'));
            $allowedKeys = [
                'site_bg_image',
                'home_bg_image',
                'hero_bg_image',
                'store_bg_image',
                'product_bg_image',
                'checkout_bg_image',
                'institutional_bg_image',
                'header_bg_image',
                'footer_bg_image',
                'top_banner_bg_image',
                'admin_bg_image'
            ];
            if (!in_array($targetKey, $allowedKeys, true)) {
                $targetKey = 'site_bg_image';
            }

            $uploadDir = BASE_PATH . '/assets/images/backgrounds';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }

            $filename = 'bg-' . time() . '-' . rand(100, 999) . '.' . $ext;
            $dest = $uploadDir . '/' . $filename;

            if (!move_uploaded_file($_FILES['bg_file']['tmp_name'], $dest)) {
                $this->respond(['success' => false, 'error' => 'Não foi possível salvar o arquivo no servidor.'], 200);
                return;
            }

            $url = '/assets/images/backgrounds/' . $filename;
            \App\Models\Setting::set($targetKey, $url);

            $this->respond([
                'success' => true,
                'message' => 'Imagem de fundo enviada e aplicada com sucesso!',
                'url' => $url,
                'target_key' => $targetKey
            ]);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao processar upload: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Remover Imagem de Fundo (Restaura o Padrão do Tema)
     */
    public function removeBackgroundImage(): void
    {
        try {
            $targetKey = trim((string) ($_POST['target_key'] ?? 'site_bg_image'));
            $allowedKeys = [
                'site_bg_image',
                'home_bg_image',
                'hero_bg_image',
                'store_bg_image',
                'product_bg_image',
                'checkout_bg_image',
                'institutional_bg_image',
                'header_bg_image',
                'footer_bg_image',
                'top_banner_bg_image',
                'admin_bg_image'
            ];
            if (!in_array($targetKey, $allowedKeys, true)) {
                $targetKey = 'site_bg_image';
            }

            \App\Models\Setting::set($targetKey, '');

            $this->respond([
                'success' => true,
                'message' => 'Imagem de fundo removida. O padrão do tema foi restaurado!',
                'target_key' => $targetKey
            ]);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao remover imagem: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Upload Genérico de Mídia para Identidade, Banners, Favicon e Seções
     */
    public function uploadMedia(): void
    {
        try {
            if (empty($_FILES['media_file']) || $_FILES['media_file']['error'] !== UPLOAD_ERR_OK) {
                $this->respond(['success' => false, 'error' => 'Nenhum arquivo enviado ou erro no upload.'], 200);
                return;
            }

            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'image/x-icon', 'image/vnd.microsoft.icon'];
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES['media_file']['tmp_name']);

            if (!in_array($mime, $allowedMimes, true)) {
                $this->respond(['success' => false, 'error' => 'Formato não suportado. Utilize JPG, PNG, WebP, SVG ou ICO.'], 200);
                return;
            }

            $ext = strtolower(pathinfo($_FILES['media_file']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg', 'ico'], true)) {
                $ext = 'jpg';
            }

            $category = trim((string) ($_POST['media_type'] ?? 'general'));
            $safeCategory = in_array($category, ['logos', 'favicons', 'banners', 'editorial', 'slides'], true) ? $category : 'general';

            $uploadDir = BASE_PATH . '/assets/images/' . $safeCategory;
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }

            $filename = 'media-' . time() . '-' . rand(100, 999) . '.' . $ext;
            $dest = $uploadDir . '/' . $filename;

            if (!move_uploaded_file($_FILES['media_file']['tmp_name'], $dest)) {
                $this->respond(['success' => false, 'error' => 'Falha ao salvar mídia no servidor.'], 200);
                return;
            }

            $url = '/assets/images/' . $safeCategory . '/' . $filename;

            $targetSettingKey = trim((string) ($_POST['target_setting_key'] ?? ''));
            if ($targetSettingKey !== '' && !str_starts_with($targetSettingKey, '_')) {
                \App\Models\Setting::set($targetSettingKey, $url);
            }

            $this->respond([
                'success' => true,
                'message' => 'Arquivo enviado e atualizado com sucesso!',
                'url' => $url,
                'target_setting_key' => $targetSettingKey
            ]);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao processar mídia: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Salvar Configurações Gerais da Loja (Dashboard)
     */
    public function saveStoreSettings(): void
    {
        try {
            \App\Models\Setting::setMany($_POST);
            $this->respond([
                'success' => true,
                'message' => 'Configurações gerais da loja atualizadas com sucesso!'
            ]);
        } catch (\Throwable $e) {
            $this->respond([
                'success' => false,
                'error' => 'Erro ao salvar configurações da loja: ' . $e->getMessage()
            ], 200);
        }
    }

    /**
     * Salvar Parâmetros de Rastreamento e Metas de Marketing (Métricas)
     */
    public function saveMetricsConfig(): void
    {
        try {
            \App\Models\Setting::setMany($_POST);
            $this->respond([
                'success' => true,
                'message' => 'Metas e tags de rastreamento salvas com sucesso!'
            ]);
        } catch (\Throwable $e) {
            $this->respond([
                'success' => false,
                'error' => 'Erro ao salvar parâmetros de métricas: ' . $e->getMessage()
            ], 200);
        }
    }

    /**
     * Salva as credenciais do aplicativo Bling ERP
     */
    public function blingSaveConfig(): void
    {
        $clientId = trim((string) ($_POST['client_id'] ?? ''));
        $clientSecret = trim((string) ($_POST['client_secret'] ?? ''));
        $redirectUri = trim((string) ($_POST['redirect_uri'] ?? ''));

        if ($clientId === '' || $clientSecret === '') {
            $this->respond(['success' => false, 'error' => 'Client ID e Client Secret são obrigatórios.'], 200);
            return;
        }

        $service = new BlingService($this->pdo);
        $service->saveCredentials($clientId, $clientSecret, $redirectUri);

        $this->respond([
            'success' => true,
            'message' => 'Credenciais do Bling salvas com sucesso! Agora você pode conectar ou sincronizar.'
        ]);
    }

    /**
     * Redireciona para o Bling para conceder autorização OAuth 2.0
     */
    public function blingAuthorize(): void
    {
        try {
            $service = new BlingService($this->pdo);
            $authUrl = $service->getAuthorizationUrl();
            header('Location: ' . $authUrl);
            exit;
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao iniciar autorização Bling: ' . $e->getMessage()], 200);
        }
    }

    /**
     * Callback OAuth 2.0 recebido do Bling
     */
    public function blingCallback(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $code = trim((string) ($_GET['code'] ?? ''));
        $error = trim((string) ($_GET['error'] ?? ''));

        if (!empty($error)) {
            $_SESSION['flash_error'] = 'Autorização negada pelo Bling: ' . $error;
            header('Location: /admin/produtos');
            exit;
        }

        if (empty($code)) {
            $_SESSION['flash_error'] = 'Código de autorização não recebido do Bling.';
            header('Location: /admin/produtos');
            exit;
        }

        try {
            $service = new BlingService($this->pdo);
            $service->exchangeCodeForTokens($code);
            $_SESSION['flash_success'] = 'Conta Bling conectada com sucesso! Você já pode sincronizar o estoque via API.';
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Falha ao autenticar com Bling: ' . $e->getMessage();
        }

        header('Location: /admin/produtos');
        exit;
    }

    /**
     * Dispara a sincronização de produtos e estoques com o Bling
     */
    public function blingSync(): void
    {
        $mode = trim((string) ($_POST['mode'] ?? 'auto')); // 'auto', 'api', 'catalog'

        try {
            $service = new BlingService($this->pdo);

            if ($mode === 'catalog') {
                $result = $service->syncFromLocalDataset();
            } elseif ($mode === 'api') {
                $result = $service->syncFromApi();
            } else {
                $result = $service->syncNow();
            }

            $this->respond([
                'success' => true,
                'message' => $result['message'] ?? sprintf('%d produtos sincronizados com o estoque do Bling!', $result['total'] ?? 0),
                'data'    => $result,
            ]);
        } catch (\Throwable $e) {
            $this->respond([
                'success' => false,
                'error'   => 'Erro na sincronização com Bling: ' . $e->getMessage(),
            ], 200);
        }
    }

    /**
     * Helper de resposta JSON ou redirecionamento
     */
    private function respond(array $data, int $status = 200): void
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $isJson = (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))
            || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')
            || (isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json'))
            || !empty($_SERVER['HTTP_X_CSRF_TOKEN'])
            || str_contains($uri, '/salvar')
            || str_contains($uri, '/toggle')
            || str_contains($uri, '/excluir')
            || str_contains($uri, '/duplicar')
            || str_contains($uri, '/upload-bg')
            || str_contains($uri, '/remover-bg')
            || str_contains($uri, '/sync')
            || str_contains($uri, '/config');

        if ($isJson) {
            while (ob_get_level() > 0) {
                @ob_end_clean();
            }
            header('Content-Type: application/json; charset=UTF-8');
            // Hostinger LiteSpeed Web Server intercepta status >= 400 e exibe
            // "Configuração do servidor incompleta. Por favor, contate o administrador."
            // Para respostas JSON/AJAX, retornamos sempre HTTP 200 com payload {success: false, error: ...}
            http_response_code(200);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($data['error'])) {
            $_SESSION['flash_error'] = $data['error'];
        } elseif (!empty($data['message'])) {
            $_SESSION['flash_success'] = $data['message'];
        }

        http_response_code(200);
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin'));
        exit;
    }
}
