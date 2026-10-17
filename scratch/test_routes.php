<?php

declare(strict_types=1);

require 'app/bootstrap.php';
session_start();
$_SESSION['admin_user'] = ['id' => 1, 'name' => 'Administrador Divinium', 'email' => 'admin@divinium.com.br'];

$urls = [
    '/',
    '/loja',
    '/admin',
    '/admin/temas',
    '/admin/metricas',
    '/admin/produtos',
    '/admin/categorias',
    '/admin/landing-page'
];

foreach ($urls as $url) {
    ob_start();
    try {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = $url;
        $router = new \App\Core\Router();
        $router->dispatch('GET', $url);
        $html = ob_get_clean();
        echo "[OK " . strlen($html) . " bytes] " . $url . PHP_EOL;
    } catch (\Throwable $e) {
        ob_end_clean();
        echo "[ERRO] " . $url . " -> " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
    }
}
