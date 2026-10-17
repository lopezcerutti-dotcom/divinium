<?php
require_once __DIR__ . '/../app/bootstrap.php';
try {
    $pdo = \App\Core\Database::getConnection();
    
    // Verificar se coluna external_url já existe
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    $hasColumn = false;
    
    if ($driver === 'sqlite') {
        $cols = $pdo->query("PRAGMA table_info(products)")->fetchAll();
        foreach ($cols as $col) {
            if ($col['name'] === 'external_url') {
                $hasColumn = true;
                break;
            }
        }
    } else {
        $stmt = $pdo->query("SHOW COLUMNS FROM products LIKE 'external_url'");
        $hasColumn = (bool) $stmt->fetch();
    }
    
    if (!$hasColumn) {
        $pdo->exec("ALTER TABLE products ADD COLUMN external_url TEXT NULL");
        echo "COLUNA external_url ADICIONADA COM SUCESSO!\n";
    } else {
        echo "COLUNA external_url JA EXISTE.\n";
    }

    // Configurar links iniciais de exemplo da Shopee nos produtos existentes
    $pdo->exec("
        UPDATE products SET external_url = 'https://shopee.com.br/search?keyword=bracelete%20caminhos' WHERE external_url IS NULL OR external_url = ''
    ");
    echo "LINKS SHOPEE ATUALIZADOS!\n";

} catch (\Throwable $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
}
