<?php
require_once __DIR__ . '/../app/bootstrap.php';
try {
    $_SESSION['admin_user'] = ['id' => 1, 'name' => 'Admin', 'email' => 'admin@divinium.com.br'];
    $ctrl = new \App\Controllers\AdminController();
    $ctrl->dashboard();
    echo "\nADMIN_CONTROLLER_OK\n";
} catch (\Throwable $e) {
    echo "\nADMIN_ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
