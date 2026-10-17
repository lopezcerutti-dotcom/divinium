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
    http_response_code(500);
    exit('Erro de inicialização: Pasta app/ não encontrada no servidor. Certifique-se de que a pasta app/ foi enviada.');
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

    // AUTO-RECUPERAÇÃO: Na web, em requisições GET, nunca quebra a tela do usuário
    if (php_sapi_name() !== 'cli' && ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
        try {
            \App\Core\View::render('home/index', [
                'pageTitle' => 'Joias que Revelam o Luxo Silencioso',
                'pageDescription' => 'Divinium — Joias exclusivas em ouro 18k e pedras preciosas com a essência do quiet luxury.'
            ]);
            exit;
        } catch (Throwable $renderErr) {
            // Se até a View falhar, recarrega a página de forma fluida
            header('Content-Type: text/html; charset=UTF-8');
            echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><title>Divinium</title><meta http-equiv="refresh" content="2;url=/"></head><body style="background:#0d0f12;color:#caa15c;font-family:sans-serif;text-align:center;padding:100px 20px;"><h2>Divinium Joalheria</h2><p style="color:#aaa;">Carregando boutique...</p></body></html>';
            exit;
        }
    }

    throw $e;
}
