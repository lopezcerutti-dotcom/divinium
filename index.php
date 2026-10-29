<?php

declare(strict_types=1);

use App\Core\Router;

/**
 * ==============================================================================
 * FRONT CONTROLLER - DIVINIUM E-COMMERCE
 * Localização: public_html/index.php
 * Único arquivo PHP publicamente exposto à internet.
 * ==============================================================================
 */

// Blindagem precoce contra erros não capturados antes ou durante o bootstrap
// Impede que o LiteSpeed Web Server da Hostinger intercepte com tela de erro genérica
register_shutdown_function(function (): void {
    $error = error_get_last();
    $fatalTypes = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_RECOVERABLE_ERROR, E_USER_ERROR];
    if ($error !== null && in_array($error['type'], $fatalTypes, true)) {
        while (ob_get_level() > 0) {
            @ob_end_clean();
        }
        if (!headers_sent()) {
            http_response_code(200);
            header('Content-Type: text/html; charset=UTF-8');
        }
        $logDir = __DIR__ . '/logs/app';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        @file_put_contents($logDir . '/exception.log', sprintf("[%s] FATAL BOOT: %s em %s:%d\n", date('Y-m-d H:i:s'), $error['message'], $error['file'], $error['line']), FILE_APPEND | LOCK_EX);
        echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Divinium Joalheria</title><style>body{background:#F8F8F7;color:#202020;font-family:sans-serif;text-align:center;padding:80px 20px;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;box-sizing:border-box;}.box{background:#FFFFFF;border:1px solid #737B85;border-radius:16px;padding:48px 32px;max-width:500px;box-shadow:0 10px 30px rgba(0,0,0,0.05);}h2{letter-spacing:4px;font-weight:600;margin:0 0 12px;color:#202020;}p{color:#686868;line-height:1.6;margin:0 0 24px;}a{color:#202020;background:linear-gradient(135deg,#CBD0D6 0%,#A7ADB4 100%);border:1px solid #737B85;padding:12px 28px;border-radius:24px;text-decoration:none;display:inline-block;font-weight:700;}</style></head><body><div class="box"><h2>DIVINIUM</h2><p>O servidor está atualizando os serviços da boutique com segurança. Por favor, recarregue a página.</p><a href="/">Recarregar Página</a></div></body></html>';
        exit(0);
    }
});

// Ponto de entrada: Carrega o bootstrap (suporta app/ fora ou dentro de public_html)
$possibleBootstraps = [
    __DIR__ . '/app/bootstrap.php',
    dirname(__DIR__) . '/app/bootstrap.php',
    __DIR__ . '/../app/bootstrap.php',
    dirname(__FILE__, 2) . '/app/bootstrap.php',
];

$bootstrapFile = null;
foreach ($possibleBootstraps as $candidate) {
    if (file_exists($candidate)) {
        $bootstrapFile = $candidate;
        break;
    }
}

if ($bootstrapFile === null) {
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Divinium Joalheria</title><style>body{background:#F8F8F7;color:#202020;font-family:sans-serif;text-align:center;padding:80px 20px;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;box-sizing:border-box;}.box{background:#FFFFFF;border:1px solid #737B85;border-radius:16px;padding:48px 32px;max-width:500px;box-shadow:0 10px 30px rgba(0,0,0,0.05);}h2{letter-spacing:4px;font-weight:600;margin:0 0 12px;color:#202020;}p{color:#686868;line-height:1.6;margin:0;}</style></head><body><div class="box"><h2>DIVINIUM</h2><p>Atualizando serviços com segurança. Por favor, recarregue a página em alguns instantes.</p></div></body></html>';
    exit(0);
}

$app = require_once $bootstrapFile;

// Dispara o Roteador Principal / Pipeline de Middlewares
try {
    $router = new Router();
    $router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
} catch (Throwable $e) {
    // Registrar erro no log
    $logDir = defined('LOGS_PATH') ? LOGS_PATH . '/app' : __DIR__ . '/logs/app';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    @file_put_contents($logDir . '/exception.log', sprintf("[%s] %s em %s:%d\n", date('Y-m-d H:i:s'), $e->getMessage(), $e->getFile(), $e->getLine()), FILE_APPEND | LOCK_EX);

    while (ob_get_level() > 0) {
        @ob_end_clean();
    }

    if (!headers_sent()) {
        http_response_code(200);
    }

    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $isJson = (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))
        || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')
        || (isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json'))
        || !empty($_SERVER['HTTP_X_CSRF_TOKEN'])
        || str_contains($uri, '/salvar')
        || str_contains($uri, '/api/');

    if ($isJson) {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=UTF-8');
        }
        echo json_encode([
            'success' => false,
            'error' => 'Ocorreu uma instabilidade temporária no servidor. Por favor, tente novamente.'
        ], JSON_UNESCAPED_UNICODE);
        exit(0);
    }

    // AUTO-RECUPERAÇÃO: Na web, em requisições GET ou POST, renderiza página da Home ou tela amigável
    if (php_sapi_name() !== 'cli') {
        try {
            \App\Core\View::render('home/index', [
                'pageTitle' => 'DIVINIUM | Joalheria Autoral e Biojoias Contemporâneas no Rio de Janeiro',
                'pageDescription' => 'Descubra acessórios autorais de design atemporal e acabamento artesanal. Peças exclusivas em edições limitadas criadas no Rio de Janeiro.'
            ]);
            exit(0);
        } catch (Throwable $renderErr) {
            if (!headers_sent()) {
                header('Content-Type: text/html; charset=UTF-8');
            }
            echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Divinium Joalheria</title><style>body{background:#F8F8F7;color:#202020;font-family:sans-serif;text-align:center;padding:80px 20px;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;box-sizing:border-box;}.box{background:#FFFFFF;border:1px solid #737B85;border-radius:16px;padding:48px 32px;max-width:500px;box-shadow:0 10px 30px rgba(0,0,0,0.05);}h2{letter-spacing:4px;font-weight:600;margin:0 0 12px;color:#202020;}p{color:#686868;line-height:1.6;margin:0 0 24px;}a{color:#202020;background:linear-gradient(135deg,#CBD0D6 0%,#A7ADB4 100%);border:1px solid #737B85;padding:12px 28px;border-radius:24px;text-decoration:none;display:inline-block;font-weight:700;}</style></head><body><div class="box"><h2>DIVINIUM</h2><p>A boutique está inicializando os serviços com segurança.</p><a href="/">Atualizar Página</a></div></body></html>';
            exit(0);
        }
    }

    exit(1);
}
