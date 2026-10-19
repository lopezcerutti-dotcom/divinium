<?php

declare(strict_types=1);

/**
 * ==============================================================================
 * FRONT CONTROLLER - DIVINIUM E-COMMERCE
 * Localização: public_html/index.php
 * Único arquivo PHP publicamente exposto à internet.
 * ==============================================================================
 */

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
    echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><title>Divinium Joalheria</title><style>body{background:#0d0c0b;color:#d4ba88;font-family:sans-serif;text-align:center;padding:80px 20px;}h2{letter-spacing:2px;font-weight:400;}</style></head><body><h2>DIVINIUM</h2><p style="color:#aaa;">Atualizando serviços com segurança. Por favor, recarregue a página em alguns instantes.</p></body></html>';
    exit(0);
}

$app = require_once $bootstrapFile;

// Dispara o Roteador Principal / Pipeline de Middlewares
use App\Core\Router;

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

    $isJson = (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))
        || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');

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
                'pageTitle' => 'Joias que Revelam o Luxo Silencioso',
                'pageDescription' => 'Divinium — Joias exclusivas em prata de lei 925 e gemas minerais raras com a essência do quiet luxury.'
            ]);
            exit(0);
        } catch (Throwable $renderErr) {
            if (!headers_sent()) {
                header('Content-Type: text/html; charset=UTF-8');
            }
            echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><title>Divinium Joalheria</title><style>body{background:#0d0c0b;color:#d4ba88;font-family:sans-serif;text-align:center;padding:80px 20px;}h2{letter-spacing:2px;font-weight:400;}p{color:#aaa;font-size:0.95rem;}a{color:#0d0c0b;background:#d4ba88;padding:10px 22px;border-radius:20px;text-decoration:none;display:inline-block;margin-top:15px;font-weight:600;}</style></head><body><h2>DIVINIUM</h2><p>A boutique está inicializando os serviços com segurança.</p><a href="/">Atualizar Página</a></body></html>';
            exit(0);
        }
    }

    exit(1);
}
