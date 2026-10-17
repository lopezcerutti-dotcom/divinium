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
    // Delegar exceções não tratadas ao handler global configurado no bootstrap
    throw $e;
}
