<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Gerenciador Singleton de Conexão com o Banco de Dados via PDO.
 * Resiliente e com auto-cura: tenta múltiplos hosts (localhost/127.0.0.1),
 * fallback para SQLite seguro em caso de indisponibilidade momentânea do MySQL.
 */
class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $configuredHost = (string) Env::get('DB_HOST', 'localhost');
        $port    = (string) Env::get('DB_PORT', '3306');
        $db      = (string) Env::get('DB_DATABASE', 'u247277668_divinium');
        $user    = (string) Env::get('DB_USERNAME', 'u247277668_aurikim');
        $pass    = (string) Env::get('DB_PASSWORD', '202122AURIkim');
        $charset = (string) Env::get('DB_CHARSET', 'utf8mb4');

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        if (defined('PDO::MYSQL_ATTR_INIT_COMMAND')) {
            $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES {$charset} COLLATE utf8mb4_unicode_ci";
        }

        // Tentar conectar via MySQL se a extensão estiver disponível
        if (extension_loaded('pdo_mysql') && !empty($db) && !empty($user)) {
            // Hostinger suporta 'localhost' (socket) ou '127.0.0.1' (TCP). Testamos ambos para garantir.
            $hostsToTry = array_unique([$configuredHost, 'localhost', '127.0.0.1']);

            foreach ($hostsToTry as $hostCandidate) {
                try {
                    $dsn = "mysql:host={$hostCandidate};port={$port};dbname={$db};charset={$charset}";
                    self::$instance = new PDO($dsn, $user, $pass, $options);
                    return self::$instance;
                } catch (PDOException $e) {
                    // Tentar o próximo host candidato
                    continue;
                }
            }
        }

        // FALLBACK RESILIENTE: SQLite para garantir que o site NUNCA saia do ar
        try {
            $sqliteDir = defined('BASE_PATH') ? BASE_PATH . '/database' : dirname(__DIR__, 2) . '/database';
            if (!is_dir($sqliteDir)) {
                @mkdir($sqliteDir, 0755, true);
            }

            $sqlitePath = $sqliteDir . '/divinium_local.sqlite';
            $isNew = !file_exists($sqlitePath) || filesize($sqlitePath) === 0;

            if (extension_loaded('pdo_sqlite')) {
                self::$instance = new PDO('sqlite:' . $sqlitePath, null, null, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);

                // Se acabou de ser criado, provisiona tabelas mínimas para navegação fluida
                if ($isNew) {
                    self::initFallbackTables(self::$instance);
                }

                return self::$instance;
            }
        } catch (\Throwable $e) {
            // Em caso extremo, tenta SQLite em memória
            if (extension_loaded('pdo_sqlite')) {
                self::$instance = new PDO('sqlite::memory:', null, null, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
                self::initFallbackTables(self::$instance);
                return self::$instance;
            }
        }

        // Se nenhuma alternativa funcionou, logar o erro
        $logFile = defined('BASE_PATH') ? BASE_PATH . '/logs/app/database_errors.log' : __DIR__ . '/database_errors.log';
        $entry = sprintf("[%s] Falha geral de conexão com base de dados.\n", date('Y-m-d H:i:s'));
        @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);

        throw new RuntimeException('Não foi possível estabelecer conexão segura com a base de dados.');
    }

    /**
     * Inicializa tabelas essenciais no banco fallback para o site nunca quebrar
     */
    private static function initFallbackTables(PDO $pdo): void
    {
        try {
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS settings (
                    key_name VARCHAR(100) PRIMARY KEY,
                    value TEXT NULL,
                    is_encrypted INTEGER DEFAULT 0,
                    description TEXT NULL
                );
                CREATE TABLE IF NOT EXISTS categories (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    uuid VARCHAR(36) NOT NULL,
                    parent_id INTEGER NULL,
                    name VARCHAR(150) NOT NULL,
                    slug VARCHAR(160) NOT NULL,
                    description TEXT NULL,
                    sort_order INTEGER DEFAULT 0,
                    is_active INTEGER DEFAULT 1
                );
                CREATE TABLE IF NOT EXISTS products (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    uuid VARCHAR(36) NOT NULL,
                    category_id INTEGER NULL,
                    sku VARCHAR(64) NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    slug VARCHAR(255) NOT NULL,
                    description TEXT NULL,
                    stone_mineral VARCHAR(100) NULL,
                    price_cents INTEGER NOT NULL DEFAULT 38000,
                    compare_at_price_cents INTEGER NULL,
                    image_url VARCHAR(500) NULL,
                    external_url VARCHAR(500) NULL,
                    is_active INTEGER DEFAULT 1,
                    is_featured INTEGER DEFAULT 0
                );
                CREATE TABLE IF NOT EXISTS product_clicks (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    product_id INTEGER NOT NULL,
                    product_name VARCHAR(255) NOT NULL,
                    source VARCHAR(50) DEFAULT 'store',
                    ip_address VARCHAR(45) DEFAULT '127.0.0.1',
                    user_agent VARCHAR(255) NULL,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                );
            ");
        } catch (\Throwable $e) {
            // Ignora silenciosamente para não interromper execução
        }
    }
}
