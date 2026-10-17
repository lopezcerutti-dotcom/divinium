<?php
require_once __DIR__ . '/../app/bootstrap.php';
try {
    $pdo = \App\Core\Database::getConnection();
    echo "DB_CONNECTED: OK\n";
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    echo "DRIVER: $driver\n";
    $stmt = $pdo->query("SELECT id, name, email FROM users");
    $users = $stmt->fetchAll();
    echo "USERS: " . json_encode($users) . "\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
