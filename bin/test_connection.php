<?php

declare(strict_types=1);

/**
 * Script utilitário para testar a conexão com o banco de dados configurado no .env.
 * Uso: php bin/test_connection.php
 */

require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\Database;
use App\Core\Env;

echo "=== TESTE DE CONEXÃO COM BANCO DE DADOS ===" . PHP_EOL;
echo "Host:     " . Env::get('DB_HOST') . PHP_EOL;
echo "Database: " . Env::get('DB_DATABASE') . PHP_EOL;
echo "User:     " . Env::get('DB_USERNAME') . PHP_EOL;
echo "-------------------------------------------" . PHP_EOL;

try {
    $pdo = Database::getConnection();
    $version = $pdo->query('SELECT VERSION()')->fetchColumn();
    
    echo " STATUS: Conexão estabelecida com sucesso!" . PHP_EOL;
    echo " Versão do Servidor: " . $version . PHP_EOL;

    // Verificar se as tabelas já foram importadas
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    $hasUsers = $stmt->fetch();

    if ($hasUsers) {
        $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        echo " Tabela 'users' encontrada! Registros: {$count}" . PHP_EOL;
    } else {
        echo "⚠ As tabelas do sistema ainda não foram importadas." . PHP_EOL;
        echo "  Para importar, execute o schema.sql no phpMyAdmin do hPanel." . PHP_EOL;
    }
} catch (Throwable $e) {
    echo " ERRO AO CONECTAR: " . $e->getMessage() . PHP_EOL;
    echo PHP_EOL;
    echo "Dica:" . PHP_EOL;
    echo "- Se você estiver rodando no seu computador (localmente) e o banco for da Hostinger," . PHP_EOL;
    echo "  o MySQL da Hostinger bloqueia conexões externas por padrão." . PHP_EOL;
    echo "  Para liberar, acesse o hPanel > Bancos de Dados > MySQL Remoto e adicione seu IP." . PHP_EOL;
    echo "- Quando os arquivos forem enviados para a Hostinger, o host '127.0.0.1' ou 'localhost' funcionará direto." . PHP_EOL;
}
