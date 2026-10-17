<?php
require_once 'app/bootstrap.php';
$pdo = \App\Core\Database::getConnection();
$tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
echo "TABLES:\n" . implode("\n", $tables) . "\n\n";

$products = $pdo->query("SELECT id, name, price_cents, is_active FROM products")->fetchAll(PDO::FETCH_ASSOC);
echo "PRODUCTS COUNT: " . count($products) . "\n";
print_r($products);
