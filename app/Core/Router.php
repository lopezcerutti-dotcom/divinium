<?php

declare(strict_types=1);

namespace App\Core;

use App\Middlewares\CsrfMiddleware;

/**
 * Roteador minimalista, veloz e seguro com suporte a middlewares e parâmetros de URL.
 */
class Router
{
    /** @var array<string, array<string, array{handler: callable|array, middlewares: array}>> */
    private array $routes = [];

    public function __construct()
    {
        // Registrar rotas padrão da aplicação
        $this->registerDefaultRoutes();
    }

    public function get(string $path, callable|array $handler, array $middlewares = []): void
    {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, callable|array $handler, array $middlewares = []): void
    {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }

    private function addRoute(string $method, string $path, callable|array $handler, array $middlewares): void
    {
        $this->routes[$method][$path] = [
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    /**
     * Despacha a requisição HTTP para o manipulador correspondente.
     */
    public function dispatch(string $method, string $uri): void
    {
        $parsedUrl = parse_url($uri, PHP_URL_PATH);
        $path = rtrim($parsedUrl ?: '/', '/');
        if ($path === '' || $path === '/index.php' || $path === '/index.html' || $path === '/index') {
            $path = '/';
        }

        // Executar validação global de CSRF em métodos de escrita (exceto webhooks)
        CsrfMiddleware::validate();

        // Interceptar callback OAuth do Bling caso retorne na raiz ou em /admin/bling/callback
        if (isset($_GET['code']) && ($path === '/' || str_ends_with($path, '/bling/callback'))) {
            $controller = new \App\Controllers\AdminController();
            $controller->blingCallback();
            return;
        }

        // 1. Busca exata de rota
        if (isset($this->routes[$method][$path])) {
            $route = $this->routes[$method][$path];
            $this->executeRoute($route['handler'], $route['middlewares'], []);
            return;
        }

        // 2. Busca por padrão de rota com parâmetros dinâmicos (ex: /produto/{slug})
        foreach ($this->routes[$method] ?? [] as $routePath => $route) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $routePath);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->executeRoute($route['handler'], $route['middlewares'], $params);
                return;
            }
        }

        // 3. Rota não encontrada (404)
        http_response_code(404);
        if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode(['error' => 'Página ou recurso não encontrado.'], JSON_UNESCAPED_UNICODE);
        } else {
            header('Content-Type: text/html; charset=UTF-8');
            echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Página Não Encontrada | Divinium</title><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"><style>body{background:#F8F8F7;color:#202020;font-family:\'Inter\',sans-serif;margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;text-align:center;box-sizing:border-box;}.error-card{background:#FFFFFF;border:1px solid #737B85;border-radius:16px;padding:48px 32px;max-width:500px;box-shadow:0 10px 30px rgba(0,0,0,0.06);}h1{font-family:\'Cormorant Garamond\',Georgia,serif;letter-spacing:4px;font-weight:400;font-size:3.5rem;margin:0 0 10px;color:#202020;}p{color:#686868;font-size:1.05rem;line-height:1.6;margin:0 0 28px;}a{color:#202020;background:linear-gradient(135deg,#CBD0D6 0%,#A7ADB4 100%);border:1px solid #737B85;padding:12px 30px;border-radius:24px;text-decoration:none;display:inline-block;font-weight:700;letter-spacing:0.5px;transition:all 0.3s ease;}a:hover{filter:brightness(0.95);transform:translateY(-2px);}</style></head><body><div class="error-card"><h1>404</h1><p>A joia ou página que você procura não foi encontrada ou foi movida.</p><a href="/">Voltar à Boutique</a></div></body></html>';
        }
        exit;
    }

    private function executeRoute(callable|array $handler, array $middlewares, array $params): void
    {
        // Executar middlewares da rota
        foreach ($middlewares as $middleware) {
            if (is_callable($middleware)) {
                $middleware();
            } elseif (is_string($middleware) && class_exists($middleware)) {
                (new $middleware())->handle();
            }
        }

        // Executar manipulador
        if (is_array($handler) && count($handler) === 2) {
            [$class, $method] = $handler;
            $controller = new $class();
            $controller->$method($params);
        } elseif (is_callable($handler)) {
            $handler($params);
        }
    }

    private function registerDefaultRoutes(): void
    {
        // Endpoint dedicado de Webhook do ASAAS
        $this->post('/api/webhooks/asaas', function (): void {
            $pdo = Database::getConnection();
            $handler = new \App\Webhooks\AsaasWebhookHandler($pdo);
            $handler->handle();
        });

        // Endpoint de Diagnóstico / Verificação de Saúde
        $this->get('/health', function (): void {
            header('Content-Type: application/json; charset=UTF-8');
            $dbStatus = 'unknown';
            $tables = [];
            $settingsTest = 'not_tested';
            try {
                $pdo = Database::getConnection();
                $dbDriver = $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
                $dbStatus = 'connected (' . $dbDriver . ')';

                if ($dbDriver === 'sqlite') {
                    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
                    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                        $tables[] = $row['name'];
                    }
                } else {
                    $stmt = $pdo->query("SHOW TABLES");
                    while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
                        $tables[] = $row[0];
                    }
                }

                // Testar escrita e leitura em settings
                $setRes = \App\Models\Setting::set('__health_check_test', date('Y-m-d H:i:s'));
                $getVal = \App\Models\Setting::get('__health_check_test');
                $settingsTest = ($setRes && $getVal) ? 'ok' : 'write_or_read_failed';

            } catch (\Throwable $e) {
                $dbStatus = 'error: ' . $e->getMessage();
                $settingsTest = 'error: ' . $e->getMessage();
            }

            $readLast = function(string $file, int $lines = 25): array {
                if (!file_exists($file) || !is_readable($file)) return [];
                $content = @file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                return is_array($content) ? array_slice($content, -$lines) : [];
            };

            $logDir = defined('LOGS_PATH') ? LOGS_PATH : dirname(__DIR__, 2) . '/logs';

            echo json_encode([
                'status' => 'ok',
                'php_version' => PHP_VERSION,
                'db' => $dbStatus,
                'tables' => $tables,
                'settings_test' => $settingsTest,
                'recent_exceptions' => $readLast($logDir . '/app/exception.log'),
                'recent_errors' => $readLast($logDir . '/app/error.log'),
                'time' => date('Y-m-d H:i:s')
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit;
        });

        // Endpoint de Diagnóstico Seguro de Logs do Servidor
        $this->get('/debug-logs-84j2', function (): void {
            header('Content-Type: application/json; charset=UTF-8');
            $readLast = function(string $file, int $lines = 40): array {
                if (!file_exists($file) || !is_readable($file)) return [];
                $content = @file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                return is_array($content) ? array_slice($content, -$lines) : [];
            };

            $logDir = defined('LOGS_PATH') ? LOGS_PATH : dirname(__DIR__, 2) . '/logs';

            echo json_encode([
                'exception_log' => $readLast($logDir . '/app/exception.log'),
                'error_log' => $readLast($logDir . '/app/error.log'),
                'database_errors' => $readLast($logDir . '/app/database_errors.log'),
                'php_ini_error_log' => $readLast((string)ini_get('error_log')),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
                'env_app_env' => \App\Core\Env::get('APP_ENV'),
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit;
        });

        // Endpoint de Diagnóstico Direto dos Últimos Erros
        $this->get('/api/last-error', function (): void {
            header('Content-Type: text/plain; charset=UTF-8');
            $logDir = defined('LOGS_PATH') ? LOGS_PATH : dirname(__DIR__, 2) . '/logs';
            $files = [
                $logDir . '/app/fatal_last.log',
                $logDir . '/app/exception.log',
                $logDir . '/app/error.log'
            ];
            foreach ($files as $file) {
                if (file_exists($file) && is_readable($file)) {
                    $lines = @file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
                    if (!empty($lines)) {
                        echo "=== " . basename($file) . " ===\n";
                        echo implode("\n", array_slice($lines, -25)) . "\n\n";
                    }
                }
            }
            exit;
        });

        // Rota Raiz: Home focada em Branding & Conversão
        $homeHandler = function (): void {
            \App\Core\View::render('home/index', [
                'pageTitle' => 'Joias que Revelam o Luxo Silencioso',
                'pageDescription' => 'Divinium — Joias exclusivas em ouro 18k e pedras preciosas com a essência do quiet luxury.'
            ]);
        };

        $this->get('/', $homeHandler);
        $this->get('/index.php', $homeHandler);
        $this->get('/index', $homeHandler);

        // Rota da Loja Oficial com Vitrine e Páginas de Detalhes das Joias
        $this->get('/loja', [\App\Controllers\StoreController::class, 'index']);
        $this->get('/produto/{slug}', [\App\Controllers\StoreController::class, 'show']);
        $this->get('/loja/{slug}', [\App\Controllers\StoreController::class, 'show']);

        // Rota de Checkout Seguro
        $this->get('/checkout', [\App\Controllers\StoreController::class, 'checkout']);

        // Rotas das Páginas Institucionais & Políticas
        $this->get('/trocas-e-devolucoes', [\App\Controllers\StoreController::class, 'institutionalPage']);
        $this->get('/garantia-e-cuidados', [\App\Controllers\StoreController::class, 'institutionalPage']);
        $this->get('/politica-de-privacidade', [\App\Controllers\StoreController::class, 'institutionalPage']);
        $this->get('/termos-de-uso', [\App\Controllers\StoreController::class, 'institutionalPage']);
        $this->get('/guia-de-medidas', [\App\Controllers\StoreController::class, 'institutionalPage']);
        $this->get('/dpo', [\App\Controllers\StoreController::class, 'institutionalPage']);
        $this->get('/institucional/{slug}', [\App\Controllers\StoreController::class, 'institutionalPage']);

        // Rota de Rastreamento de Cliques da Shopee (Analytics)
        $this->post('/api/track/click', function (): void {
            $input = json_decode(file_get_contents('php://input'), true);
            if (empty($input) && !empty($_POST)) {
                $input = $_POST;
            }
            $productId = (int) ($input['product_id'] ?? 0);
            $productName = trim((string) ($input['product_name'] ?? 'Joia Shopee'));
            $source = trim((string) ($input['source'] ?? 'store'));
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido';

            if ($productId > 0) {
                try {
                    $pdo = Database::getConnection();
                    $stmt = $pdo->prepare('INSERT INTO product_clicks (product_id, product_name, source, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, ?)');
                    $stmt->execute([$productId, $productName, $source, $ip, $ua, date('Y-m-d H:i:s')]);
                } catch (\Throwable $e) {
                    // Silently ignore to not disrupt user experience
                }
            }

            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode(['success' => true]);
            exit;
        });

        // Rotas Administrativas e de Autenticação (Suporta /admin e ADMIN_PREFIX customizado)
        $adminAuth = [\App\Middlewares\AdminAuthMiddleware::class];
        $adminPrefixes = ['admin'];
        $customPrefix = trim((string) \App\Core\Env::get('ADMIN_PREFIX', ''));
        if ($customPrefix !== '' && $customPrefix !== 'admin') {
            $adminPrefixes[] = trim($customPrefix, '/');
        }
        $adminPrefixes = array_unique($adminPrefixes);

        foreach ($adminPrefixes as $pref) {
            $p = '/' . $pref;

            // Autenticação
            $this->post($p . '/login', [\App\Controllers\AuthController::class, 'login']);
            $this->get($p . '/logout', [\App\Controllers\AuthController::class, 'logout']);
            $this->post($p . '/logout', [\App\Controllers\AuthController::class, 'logout']);

            // Painel e Módulos Protegidos
            $this->get($p, [\App\Controllers\AdminController::class, 'dashboard'], $adminAuth);
            $this->get($p . '/metricas', [\App\Controllers\AdminController::class, 'metrics'], $adminAuth);
            $this->get($p . '/produtos', [\App\Controllers\AdminController::class, 'products'], $adminAuth);
            $this->post($p . '/produtos/salvar', [\App\Controllers\AdminController::class, 'saveProduct'], $adminAuth);
            $this->post($p . '/produtos/salvar-variante', [\App\Controllers\AdminController::class, 'saveProductVariant'], $adminAuth);
            $this->post($p . '/produtos/duplicar', [\App\Controllers\AdminController::class, 'duplicateProduct'], $adminAuth);
            $this->post($p . '/produtos/toggle', [\App\Controllers\AdminController::class, 'toggleProduct'], $adminAuth);
            $this->post($p . '/produtos/excluir', [\App\Controllers\AdminController::class, 'deleteProduct'], $adminAuth);
            $this->post($p . '/produtos/salvar-canais', [\App\Controllers\AdminController::class, 'saveProductChannels'], $adminAuth);
            $this->get($p . '/produtos-exibicao', [\App\Controllers\AdminController::class, 'productDisplay'], $adminAuth);
            $this->post($p . '/produtos-exibicao/salvar', [\App\Controllers\AdminController::class, 'saveProductDisplay'], $adminAuth);
            $this->get($p . '/categorias', [\App\Controllers\AdminController::class, 'categories'], $adminAuth);
            $this->post($p . '/categorias/salvar', [\App\Controllers\AdminController::class, 'saveCategory'], $adminAuth);
            $this->post($p . '/categorias/excluir', [\App\Controllers\AdminController::class, 'deleteCategory'], $adminAuth);
            $this->get($p . '/categorias/produtos', [\App\Controllers\AdminController::class, 'categoryProducts'], $adminAuth);
            $this->post($p . '/categorias/associar-produto', [\App\Controllers\AdminController::class, 'associateCategoryProduct'], $adminAuth);
            $this->post($p . '/categorias/desassociar-produto', [\App\Controllers\AdminController::class, 'disassociateCategoryProduct'], $adminAuth);
            $this->post($p . '/categorias/salvar-variante', [\App\Controllers\AdminController::class, 'saveVariant'], $adminAuth);
            $this->post($p . '/categorias/salvar-canais', [\App\Controllers\AdminController::class, 'saveProductChannels'], $adminAuth);
            $this->get($p . '/boutique', [\App\Controllers\AdminController::class, 'boutique'], $adminAuth);
            $this->post($p . '/boutique/salvar', [\App\Controllers\AdminController::class, 'saveBoutique'], $adminAuth);
            $this->get($p . '/lancamentos', [\App\Controllers\AdminController::class, 'lancamentos'], $adminAuth);
            $this->post($p . '/lancamentos/salvar-colecao', [\App\Controllers\AdminController::class, 'saveColecao'], $adminAuth);
            $this->post($p . '/lancamentos/excluir-colecao', [\App\Controllers\AdminController::class, 'deleteColecao'], $adminAuth);
            $this->post($p . '/lancamentos/reordenar-colecoes', [\App\Controllers\AdminController::class, 'reorderColecoes'], $adminAuth);
            $this->post($p . '/lancamentos/toggle-visibilidade', [\App\Controllers\AdminController::class, 'toggleColecaoVisibility'], $adminAuth);
            $this->post($p . '/lancamentos/toggle-produto', [\App\Controllers\AdminController::class, 'toggleColecaoProduto'], $adminAuth);
            $this->post($p . '/lancamentos/salvar-categoria', [\App\Controllers\AdminController::class, 'saveLancamentoCategoria'], $adminAuth);
            $this->get($p . '/landing-page', [\App\Controllers\AdminController::class, 'landingPage'], $adminAuth);
            $this->post($p . '/landing-page/salvar', [\App\Controllers\AdminController::class, 'saveLandingPage'], $adminAuth);
            $this->get($p . '/temas', [\App\Controllers\AdminController::class, 'themes'], $adminAuth);
            $this->post($p . '/temas/salvar', [\App\Controllers\AdminController::class, 'saveDefaultTheme'], $adminAuth);
            $this->post($p . '/temas/salvar-custom', [\App\Controllers\AdminController::class, 'saveCustomTheme'], $adminAuth);
            $this->post($p . '/temas/upload-bg', [\App\Controllers\AdminController::class, 'uploadBackgroundImage'], $adminAuth);
            $this->post($p . '/temas/remover-bg', [\App\Controllers\AdminController::class, 'removeBackgroundImage'], $adminAuth);
            $this->post($p . '/configuracoes/salvar', [\App\Controllers\AdminController::class, 'saveStoreSettings'], $adminAuth);
            $this->post($p . '/metricas/salvar', [\App\Controllers\AdminController::class, 'saveMetricsConfig'], $adminAuth);
            $this->post($p . '/media/upload', [\App\Controllers\AdminController::class, 'uploadMedia'], $adminAuth);

            // Integração Bling ERP
            $this->get($p . '/bling/authorize', [\App\Controllers\AdminController::class, 'blingAuthorize'], $adminAuth);
            $this->get($p . '/bling/callback', [\App\Controllers\AdminController::class, 'blingCallback']);
            $this->post($p . '/bling/config', [\App\Controllers\AdminController::class, 'blingSaveConfig'], $adminAuth);
            $this->post($p . '/bling/sync', [\App\Controllers\AdminController::class, 'blingSync'], $adminAuth);
        }
    }
}
