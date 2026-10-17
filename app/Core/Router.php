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
        if ($path === '') {
            $path = '/';
        }

        // Executar validação global de CSRF em métodos de escrita (exceto webhooks)
        CsrfMiddleware::validate();

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
            echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><title>Página Não Encontrada - 404</title></head><body style="font-family:sans-serif;text-align:center;padding:50px;"><h1>404</h1><p>A página que você está procurando não existe.</p><a href="/">Voltar à Página Inicial</a></body></html>';
        }
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

        // Rota Raiz: Home focada em Branding & Conversão
        $this->get('/', function (): void {
            \App\Core\View::render('home/index', [
                'pageTitle' => 'Joias que Revelam o Luxo Silencioso',
                'pageDescription' => 'Divinium — Joias exclusivas em ouro 18k e pedras preciosas com a essência do quiet luxury.'
            ]);
        });

        // Rota da Loja Oficial com Vitrine e Redirecionamento Shopee
        $this->get('/loja', [\App\Controllers\StoreController::class, 'index']);

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

        // Rotas de Autenticação Administrativa
        $this->post('/admin/login', [\App\Controllers\AuthController::class, 'login']);
        $this->get('/admin/logout', [\App\Controllers\AuthController::class, 'logout']);
        $this->post('/admin/logout', [\App\Controllers\AuthController::class, 'logout']);

        // Painel Administrativo Protegido por Sessão
        $adminAuth = [\App\Middlewares\AdminAuthMiddleware::class];

        $this->get('/admin', [\App\Controllers\AdminController::class, 'dashboard'], $adminAuth);
        $this->get('/admin/metricas', [\App\Controllers\AdminController::class, 'metrics'], $adminAuth);
        $this->get('/admin/produtos', [\App\Controllers\AdminController::class, 'products'], $adminAuth);
        $this->post('/admin/produtos/salvar', [\App\Controllers\AdminController::class, 'saveProduct'], $adminAuth);
        $this->post('/admin/produtos/toggle', [\App\Controllers\AdminController::class, 'toggleProduct'], $adminAuth);
        $this->post('/admin/produtos/excluir', [\App\Controllers\AdminController::class, 'deleteProduct'], $adminAuth);
        $this->get('/admin/categorias', [\App\Controllers\AdminController::class, 'categories'], $adminAuth);
        $this->post('/admin/categorias/salvar', [\App\Controllers\AdminController::class, 'saveCategory'], $adminAuth);
        $this->post('/admin/categorias/excluir', [\App\Controllers\AdminController::class, 'deleteCategory'], $adminAuth);
        $this->get('/admin/landing-page', [\App\Controllers\AdminController::class, 'landingPage'], $adminAuth);
        $this->post('/admin/landing-page/salvar', [\App\Controllers\AdminController::class, 'saveLandingPage'], $adminAuth);
        $this->get('/admin/temas', [\App\Controllers\AdminController::class, 'themes'], $adminAuth);
        $this->post('/admin/temas/salvar', [\App\Controllers\AdminController::class, 'saveDefaultTheme'], $adminAuth);
    }
}
