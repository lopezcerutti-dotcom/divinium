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
            $this->respond(['success' => false, 'error' => 'O nome do produto é obrigatório.'], 422);
            return;
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
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
        $showSpecs = isset($_POST['show_specs']) ? ((int) $_POST['show_specs'] ? 1 : 0) : 1;
        $showDescription = isset($_POST['show_description']) ? ((int) $_POST['show_description'] ? 1 : 0) : 1;

        try {
            if ($id !== null && $id > 0) {
                $stmt = $this->pdo->prepare('
                    UPDATE products SET 
                        name = ?, category_id = ?, stone_mineral = ?, materials = ?, dimensions = ?, 
                        colors = ?, variants_json = ?, price_cents = ?, promotional_price_cents = ?, 
                        stock_quantity = ?, stock_status = ?, status = ?, sort_order = ?, 
                        short_description = ?, description = ?, image_url = ?, show_badge = ?, 
                        show_specs = ?, show_description = ?, is_active = ?, is_featured = ?
                    WHERE id = ?
                ');
                $stmt->execute([
                    $name, $categoryId, $stoneMineral, $materials, $dimensions, 
                    $colors, $variantsJson, $priceCents, $promotionalPriceCents, 
                    $stock, $stockStatus, $status, $sortOrder, 
                    $shortDescription, $description, $imageUrl, $showBadge, 
                    $showSpecs, $showDescription, $isActive, $isFeatured, $id
                ]);
                $productId = $id;
            } else {
                $uuid = 'prod-' . uniqid();
                $stmt = $this->pdo->prepare('
                    INSERT INTO products (
                        uuid, category_id, name, slug, sku, stone_mineral, materials, dimensions, 
                        colors, variants_json, price_cents, promotional_price_cents, stock_quantity, 
                        stock_status, status, sort_order, short_description, description, image_url, 
                        show_badge, show_specs, show_description, is_active, is_featured
                    ) VALUES (
                        ?, ?, ?, ?, ?, ?, ?, ?, 
                        ?, ?, ?, ?, ?, 
                        ?, ?, ?, ?, ?, ?, 
                        ?, ?, ?, ?, ?
                    )
                ');
                $stmt->execute([
                    $uuid, $categoryId, $name, $slug, $sku, $stoneMineral, $materials, $dimensions, 
                    $colors, $variantsJson, $priceCents, $promotionalPriceCents, $stock, 
                    $stockStatus, $status, $sortOrder, $shortDescription, $description, $imageUrl, 
                    $showBadge, $showSpecs, $showDescription, $isActive, $isFeatured
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

                    $lActive = isset($linkItem['is_active']) ? ((int) $linkItem['is_active'] ? 1 : 0) : 1;
                    $lSort = isset($linkItem['sort_order']) ? (int) $linkItem['sort_order'] : (int) $idx;

                    $validLinks[] = [
                        'platform_name' => $plat,
                        'url' => $u,
                        'price_cents' => $lPriceCents,
                        'button_text' => $btnText,
                        'button_color' => $btnColor,
                        'button_size' => $btnSize,
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
                    INSERT INTO product_links (product_id, platform_name, url, price_cents, button_text, button_color, button_size, is_active, sort_order) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
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
            $this->respond(['success' => false, 'error' => 'Erro ao salvar produto: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Duplicar Produto (com todos os links associados e status Rascunho)
     */
    public function duplicateProduct(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->respond(['success' => false, 'error' => 'ID inválido.'], 422);
            return;
        }

        try {
            $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = ?');
            $stmt->execute([$id]);
            $prod = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$prod) {
                $this->respond(['success' => false, 'error' => 'Produto original não encontrado.'], 404);
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
                    show_description, is_active, is_featured
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, 
                    ?, ?, ?, ?, ?, ?, 
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
                    INSERT INTO product_links (product_id, platform_name, url, price_cents, button_text, button_color, button_size, is_active, sort_order) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
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
            $this->respond(['success' => false, 'error' => 'Erro ao duplicar produto: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Alternar Visibilidade / Status do Produto (Publicado / Oculto)
     */
    public function toggleProduct(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->respond(['success' => false, 'error' => 'ID inválido.'], 422);
            return;
        }

        try {
            $prod = $this->pdo->query("SELECT is_active, status FROM products WHERE id = $id")->fetch(\PDO::FETCH_ASSOC);
            if (!$prod) {
                $this->respond(['success' => false, 'error' => 'Produto não encontrado.'], 404);
                return;
            }

            $currentlyPublished = (!empty($prod['is_active']) && ($prod['status'] ?? 'published') === 'published');
            $newActive = $currentlyPublished ? 0 : 1;
            $newStatus = $newActive === 1 ? 'published' : 'hidden';

            $stmt = $this->pdo->prepare('UPDATE products SET is_active = ?, status = ? WHERE id = ?');
            $stmt->execute([$newActive, $newStatus, $id]);

            $this->respond(['success' => true, 'is_active' => $newActive, 'status' => $newStatus]);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao alternar status: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Excluir Produto e seus Links Associados
     */
    public function deleteProduct(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->respond(['success' => false, 'error' => 'ID inválido.'], 422);
            return;
        }

        try {
            $this->pdo->prepare('DELETE FROM product_links WHERE product_id = ?')->execute([$id]);
            $this->pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);

            $this->respond(['success' => true, 'message' => 'Produto e seus canais foram removidos com sucesso.']);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao excluir produto: ' . $e->getMessage()], 500);
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

        $categories = $this->pdo->query('SELECT c.*, (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count FROM categories c ORDER BY c.sort_order ASC, c.name ASC')->fetchAll();

        View::render('admin/categories', [
            'pageTitle' => 'Gerenciar Categorias | Catálogo Divinium',
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
            $this->respond(['success' => false, 'error' => 'O nome da categoria é obrigatório.'], 422);
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
            $this->respond(['success' => false, 'error' => 'Erro ao salvar categoria: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Excluir Categoria
     */
    public function deleteCategory(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->respond(['success' => false, 'error' => 'ID inválido.'], 422);
            return;
        }

        $stmt = $this->pdo->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->execute([$id]);

        $this->respond(['success' => true, 'message' => 'Categoria removida com sucesso.']);
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
            $this->respond([
                'success' => false,
                'error' => 'Erro ao salvar alterações: ' . $e->getMessage()
            ], 500);
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
            $this->respond([
                'success' => false,
                'error' => 'Erro ao salvar elementos da Boutique: ' . $e->getMessage()
            ], 500);
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

        // 1. Total de Cliques na Shopee
        $totalClicks = (int) $this->pdo->query('SELECT COUNT(*) FROM product_clicks')->fetchColumn();

        // 2. Total de Visualizações de Página
        $totalViews = (int) $this->pdo->query('SELECT COUNT(*) FROM page_views')->fetchColumn();
        if ($totalViews === 0) {
            $totalViews = max(100, $totalClicks * 3);
        }

        // 3. Taxa de Intenção de Compra (CTR)
        $ctr = $totalViews > 0 ? round(($totalClicks / $totalViews) * 100, 1) : 0;

        // 4. Valor Potencial Estimado (soma dos preços das joias clicadas)
        $potentialSalesStmt = $this->pdo->query('
            SELECT COALESCE(SUM(p.price_cents), 0) as total_cents 
            FROM product_clicks c 
            JOIN products p ON c.product_id = p.id
        ');
        $potentialSalesCents = (int) $potentialSalesStmt->fetchColumn();

        // 5. Cliques dos últimos 7 dias (para o gráfico interativo SVG)
        $dailyClicksStmt = $this->pdo->query("
            SELECT DATE(created_at) as click_date, COUNT(*) as clicks
            FROM product_clicks
            WHERE created_at >= DATE('now', '-7 days')
            GROUP BY DATE(created_at)
            ORDER BY click_date ASC
        ");
        $dailyClicks = $dailyClicksStmt ? $dailyClicksStmt->fetchAll(\PDO::FETCH_ASSOC) : [];

        // Preencher 7 dias completos com label amigável
        $chartDays = [];
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
        $topProductsStmt = $this->pdo->query('
            SELECT p.id, p.name, p.image_url, p.price_cents, c.name as category_name, COUNT(pc.id) as clicks_count
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN product_clicks pc ON pc.product_id = p.id
            GROUP BY p.id
            ORDER BY clicks_count DESC, p.id ASC
            LIMIT 10
        ');
        $topProducts = $topProductsStmt ? $topProductsStmt->fetchAll(\PDO::FETCH_ASSOC) : [];

        // 7. Cliques por Categoria
        $categoryClicksStmt = $this->pdo->query('
            SELECT c.name as category_name, COUNT(pc.id) as clicks_count
            FROM categories c
            LEFT JOIN products p ON p.category_id = c.id
            LEFT JOIN product_clicks pc ON pc.product_id = p.id
            GROUP BY c.id
            ORDER BY clicks_count DESC
        ');
        $categoryClicks = $categoryClicksStmt ? $categoryClicksStmt->fetchAll(\PDO::FETCH_ASSOC) : [];

        // 8. Feed Recente em Tempo Real (Últimos 15 Cliques)
        $recentClicksStmt = $this->pdo->query('
            SELECT pc.*, p.price_cents, p.image_url
            FROM product_clicks pc
            LEFT JOIN products p ON pc.product_id = p.id
            ORDER BY pc.id DESC
            LIMIT 15
        ');
        $recentClicks = $recentClicksStmt ? $recentClicksStmt->fetchAll(\PDO::FETCH_ASSOC) : [];

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

        $defaultTheme = \App\Models\Setting::get('default_theme', 'theme-gold');
        $customThemeSettings = \App\Models\Setting::getAll();

        $themesList = [
            [
                'id' => 'theme-gold',
                'name' => 'Ouro Imperial & Noir',
                'subtitle' => 'Quiet Luxury Clássico',
                'description' => 'A essência atemporal da alta joalheria. Ouro 18k e dourado palha suave sobre fundo preto nobre ônix. Ideal para destacar diamantes e metais nobres com sobriedade régia.',
                'primary_color' => '#CAA15C',
                'accent_color' => '#E0C080',
                'bg_color' => '#0A0B0D',
                'text_color' => '#F4EFEA',
                'badge' => 'Clássico Original',
                'gradient' => 'linear-gradient(135deg, #F4EFEA 0%, #CAA15C 50%, #8A6D3B 100%)',
                'is_default' => ($defaultTheme === 'theme-gold')
            ],
            [
                'id' => 'theme-silver',
                'name' => 'Prata Nobre & Platina',
                'subtitle' => 'Luz Diamantina & Contemporâneo',
                'description' => 'Elegância minimalista e cristalina. Prata 925 polida, platina e brilho diamantino com glassmorphism translúcido sofisticado e tipografia de alto contraste.',
                'primary_color' => '#CBD5E1',
                'accent_color' => '#E2E8F0',
                'bg_color' => '#0F172A',
                'text_color' => '#FFFFFF',
                'badge' => 'Alta Legibilidade',
                'gradient' => 'linear-gradient(135deg, #FFFFFF 0%, #E2E8F0 50%, #94A3B8 100%)',
                'is_default' => ($defaultTheme === 'theme-silver')
            ],
            [
                'id' => 'theme-emerald',
                'name' => 'Esmeralda Real & Champagne Gold',
                'subtitle' => 'Gemas Preciosas & Tradição Joalheira',
                'description' => 'Inspirado nas grandes casas da Place Vendôme. Verde esmeralda profundo aveludado com detalhes em champagne gold. Cria uma aura aristocrática para gemas naturais.',
                'primary_color' => '#10B981',
                'accent_color' => '#34D399',
                'bg_color' => '#041E16',
                'text_color' => '#ECFDF5',
                'badge' => 'Exclusivo ✦',
                'gradient' => 'linear-gradient(135deg, #ECFDF5 0%, #34D399 45%, #059669 100%)',
                'is_default' => ($defaultTheme === 'theme-emerald')
            ],
            [
                'id' => 'theme-rose',
                'name' => 'Rose Gold & Veludo Noir',
                'subtitle' => 'Romantismo & Alta Sociedade',
                'description' => 'A delicadeza sensual do ouro rosa 18k combinada a um fundo grafite aveludado. Acento caloroso que valoriza o caimento das joias com toque sedutor e contemporâneo.',
                'primary_color' => '#FB7185',
                'accent_color' => '#FDA4AF',
                'bg_color' => '#1F0B17',
                'text_color' => '#FFF1F2',
                'badge' => 'Tendência 2026',
                'gradient' => 'linear-gradient(135deg, #FFF1F2 0%, #FDA4AF 45%, #E11D48 100%)',
                'is_default' => ($defaultTheme === 'theme-rose')
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
        $theme = trim((string) ($_POST['theme_id'] ?? 'theme-gold'));
        $allowed = ['theme-gold', 'theme-silver', 'theme-emerald', 'theme-rose', 'theme-custom'];
        if (!in_array($theme, $allowed, true)) {
            $this->respond(['success' => false, 'error' => 'Tema inválido.'], 422);
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
            ], 500);
        }
    }

    /**
     * Upload de Imagem de Fundo (Site, Hero ou Admin)
     */
    public function uploadBackgroundImage(): void
    {
        try {
            if (empty($_FILES['bg_file']['tmp_name']) || !is_uploaded_file($_FILES['bg_file']['tmp_name'])) {
                $this->respond(['success' => false, 'error' => 'Nenhum arquivo de imagem foi enviado.'], 400);
                return;
            }

            $ext = strtolower(pathinfo($_FILES['bg_file']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
            if (!in_array($ext, $allowed, true)) {
                $this->respond(['success' => false, 'error' => 'Formato de arquivo inválido. Permitidos: JPG, PNG, WEBP, SVG.'], 400);
                return;
            }

            $targetKey = trim((string) ($_POST['target_key'] ?? 'site_bg_image'));
            $allowedKeys = ['site_bg_image', 'hero_bg_image', 'admin_bg_image'];
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
                $this->respond(['success' => false, 'error' => 'Não foi possível salvar o arquivo no servidor.'], 500);
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
            $this->respond(['success' => false, 'error' => 'Erro ao processar upload: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remover Imagem de Fundo (Restaura o Padrão do Tema)
     */
    public function removeBackgroundImage(): void
    {
        try {
            $targetKey = trim((string) ($_POST['target_key'] ?? 'site_bg_image'));
            $allowedKeys = ['site_bg_image', 'hero_bg_image', 'admin_bg_image'];
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
            $this->respond(['success' => false, 'error' => 'Erro ao remover imagem: ' . $e->getMessage()], 500);
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
            ], 500);
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
            ], 500);
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
            $this->respond(['success' => false, 'error' => 'Client ID e Client Secret são obrigatórios.'], 422);
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
            $this->respond(['success' => false, 'error' => 'Erro ao iniciar autorização Bling: ' . $e->getMessage()], 400);
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
            ], 500);
        }
    }

    /**
     * Helper de resposta JSON ou redirecionamento
     */
    private function respond(array $data, int $status = 200): void
    {
        $isJson = isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')
            || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');

        if ($isJson) {
            header('Content-Type: application/json; charset=UTF-8');
            http_response_code($status);
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

        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin'));
        exit;
    }
}
