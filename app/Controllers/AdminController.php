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
        
        $productsStmt = $this->pdo->query('SELECT * FROM products ORDER BY id DESC LIMIT 20');
        $products = $productsStmt->fetchAll();

        $categoriesStmt = $this->pdo->query('SELECT * FROM categories ORDER BY sort_order ASC, name ASC');
        $categories = $categoriesStmt->fetchAll();

        $blingService = new BlingService($this->pdo);
        $blingConfig = $blingService->getConfig();

        View::render('admin/dashboard', [
            'pageTitle' => 'Painel Administrativo | Bastidores Divinium',
            'adminUser' => $_SESSION['admin_user'] ?? ['name' => 'Administrador', 'email' => 'admin@divinium.com.br'],
            'totalProducts' => $totalProducts,
            'activeProducts' => $activeProducts,
            'totalCategories' => $totalCategories,
            'products' => $products,
            'categories' => $categories,
            'blingConfig' => $blingConfig,
        ], 'admin');
    }

    /**
     * Gestão de Produtos
     */
    public function products(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $productsStmt = $this->pdo->query('SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC');
        $products = $productsStmt->fetchAll();

        $categories = $this->pdo->query('SELECT * FROM categories ORDER BY name ASC')->fetchAll();

        $blingService = new BlingService($this->pdo);
        $blingConfig = $blingService->getConfig();

        View::render('admin/products', [
            'pageTitle' => 'Gerenciar Produtos | Catálogo Divinium',
            'adminUser' => $_SESSION['admin_user'] ?? ['name' => 'Administrador'],
            'products' => $products,
            'categories' => $categories,
            'blingConfig' => $blingConfig,
        ], 'admin');
    }

    /**
     * Salvar Produto (Novo ou Edição)
     */
    public function saveProduct(): void
    {
        $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
        $name = trim((string) ($_POST['name'] ?? ''));
        $categoryId = !empty($_POST['category_id']) ? (int) $_POST['category_id'] : null;
        $stoneMineral = trim((string) ($_POST['stone_mineral'] ?? ''));
        $price = (float) str_replace(['R$', ' ', '.', ','], ['', '', '', '.'], (string) ($_POST['price'] ?? '0'));
        $priceCents = (int) round($price * 100);
        if ($priceCents <= 0 && !empty($_POST['price_cents'])) {
            $priceCents = (int) $_POST['price_cents'];
        }
        $promotionalPriceCents = !empty($_POST['promotional_price']) ? (int) round((float) str_replace(['R$', ' ', '.', ','], ['', '', '', '.'], $_POST['promotional_price']) * 100) : (int) round($priceCents * 0.95);
        $stock = isset($_POST['stock_quantity']) ? (int) $_POST['stock_quantity'] : 10;
        $shortDescription = trim((string) ($_POST['short_description'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $imageUrl = trim((string) ($_POST['image_url'] ?? ''));
        $externalUrl = trim((string) ($_POST['external_url'] ?? ''));
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;

        if ($name === '') {
            $this->respond(['success' => false, 'error' => 'O nome do produto é obrigatório.'], 422);
            return;
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        $sku = !empty($_POST['sku']) ? trim($_POST['sku']) : 'DIV-' . strtoupper(substr($slug, 0, 8)) . '-' . rand(10, 99);

        // Upload de imagem caso enviado arquivo
        if (!empty($_FILES['image_file']['tmp_name']) && is_uploaded_file($_FILES['image_file']['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                $uploadDir = BASE_PATH . '/public_html/assets/images/products';
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

        try {
            if ($id !== null && $id > 0) {
                $stmt = $this->pdo->prepare('UPDATE products SET name = ?, category_id = ?, stone_mineral = ?, price_cents = ?, promotional_price_cents = ?, stock_quantity = ?, short_description = ?, description = ?, image_url = ?, external_url = ?, is_active = ?, is_featured = ? WHERE id = ?');
                $stmt->execute([$name, $categoryId, $stoneMineral, $priceCents, $promotionalPriceCents, $stock, $shortDescription, $description, $imageUrl, $externalUrl, $isActive, $isFeatured, $id]);
            } else {
                $uuid = 'prod-' . uniqid();
                $stmt = $this->pdo->prepare('INSERT INTO products (uuid, category_id, name, slug, sku, stone_mineral, price_cents, promotional_price_cents, stock_quantity, short_description, description, image_url, external_url, is_active, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([$uuid, $categoryId, $name, $slug, $sku, $stoneMineral, $priceCents, $promotionalPriceCents, $stock, $shortDescription, $description, $imageUrl, $externalUrl, $isActive, $isFeatured]);
            }

            $this->respond(['success' => true, 'message' => 'Produto gravado com sucesso!']);
        } catch (\Throwable $e) {
            $this->respond(['success' => false, 'error' => 'Erro ao salvar produto: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Alternar Ativo/Inativo de Produto
     */
    public function toggleProduct(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->respond(['success' => false, 'error' => 'ID inválido.'], 422);
            return;
        }

        $stmt = $this->pdo->prepare('UPDATE products SET is_active = CASE WHEN is_active = 1 THEN 0 ELSE 1 END WHERE id = ?');
        $stmt->execute([$id]);

        $newStatus = $this->pdo->query("SELECT is_active FROM products WHERE id = $id")->fetchColumn();
        $this->respond(['success' => true, 'is_active' => (int) $newStatus]);
    }

    /**
     * Excluir Produto
     */
    public function deleteProduct(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->respond(['success' => false, 'error' => 'ID inválido.'], 422);
            return;
        }

        $stmt = $this->pdo->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);

        $this->respond(['success' => true, 'message' => 'Produto removido com sucesso.']);
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
        ], 'admin');
    }

    /**
     * Salvar Tema Padrão do Site
     */
    public function saveDefaultTheme(): void
    {
        $theme = trim((string) ($_POST['theme_id'] ?? 'theme-gold'));
        $allowed = ['theme-gold', 'theme-silver', 'theme-emerald', 'theme-rose'];
        if (!in_array($theme, $allowed, true)) {
            $this->respond(['success' => false, 'error' => 'Tema inválido.'], 422);
            return;
        }

        \App\Models\Setting::set('default_theme', $theme, 'Tema Padrão da Loja');
        $this->respond([
            'success' => true,
            'message' => 'Tema padrão do site atualizado com sucesso!'
        ]);
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
