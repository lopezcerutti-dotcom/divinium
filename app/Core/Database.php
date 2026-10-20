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
                    self::ensureSchema(self::$instance);
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

                self::ensureSchema(self::$instance);
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
                self::ensureSchema(self::$instance);
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
                    short_description VARCHAR(255) NULL,
                    description TEXT NULL,
                    materials VARCHAR(255) NULL,
                    stone_mineral VARCHAR(100) NULL,
                    dimensions VARCHAR(150) NULL,
                    colors VARCHAR(255) NULL,
                    variants_json TEXT NULL,
                    price_cents INTEGER NOT NULL DEFAULT 38000,
                    promotional_price_cents INTEGER NULL,
                    compare_at_price_cents INTEGER NULL,
                    stock_quantity INTEGER NOT NULL DEFAULT 10,
                    stock_status VARCHAR(30) DEFAULT 'in_stock',
                    status VARCHAR(20) DEFAULT 'published',
                    sort_order INTEGER DEFAULT 0,
                    image_url VARCHAR(500) NULL,
                    external_url VARCHAR(500) NULL,
                    show_badge INTEGER DEFAULT 1,
                    show_specs INTEGER DEFAULT 1,
                    show_description INTEGER DEFAULT 1,
                    is_active INTEGER DEFAULT 1,
                    is_featured INTEGER DEFAULT 0
                );
                CREATE TABLE IF NOT EXISTS product_links (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    product_id INTEGER NOT NULL,
                    platform_name VARCHAR(100) NOT NULL,
                    url VARCHAR(500) NOT NULL,
                    price_cents INTEGER NULL,
                    button_text VARCHAR(100) NULL,
                    is_active INTEGER DEFAULT 1,
                    sort_order INTEGER DEFAULT 0,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
                );
                CREATE TABLE IF NOT EXISTS product_clicks (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    product_id INTEGER NOT NULL,
                    product_name VARCHAR(255) NOT NULL,
                    platform_name VARCHAR(100) DEFAULT 'Shopee',
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

    /**
     * Auto-cura e evolução contínua de schema (MySQL e SQLite)
     * Garante existência da tabela product_links e novas colunas em products sem perdas
     */
    public static function ensureSchema(PDO $pdo): void
    {
        static $ensured = false;
        if ($ensured) {
            return;
        }
        $ensured = true;

        // Flag de cache em disco para evitar execuções pesadas de DDL (ALTER/SHOW) em cada requisição web
        $flagFile = (defined('STORAGE_PATH') ? STORAGE_PATH : dirname(__DIR__, 2) . '/storage') . '/cache/schema_v3_ensured.flag';
        if (file_exists($flagFile) && (time() - filemtime($flagFile)) < 86400) {
            return;
        }

        try {
            $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

            // 1. Criar tabela product_links se não existir
            if ($driver === 'sqlite') {
                $pdo->exec("
                    CREATE TABLE IF NOT EXISTS product_links (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        product_id INTEGER NOT NULL,
                        platform_name VARCHAR(100) NOT NULL,
                        url VARCHAR(500) NOT NULL,
                        price_cents INTEGER NULL,
                        button_text VARCHAR(100) NULL,
                        is_active INTEGER DEFAULT 1,
                        sort_order INTEGER DEFAULT 0,
                        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
                    );
                ");
            } else {
                $pdo->exec("
                    CREATE TABLE IF NOT EXISTS `product_links` (
                        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        `product_id` BIGINT UNSIGNED NOT NULL,
                        `platform_name` VARCHAR(100) NOT NULL,
                        `url` VARCHAR(500) NOT NULL,
                        `price_cents` INT UNSIGNED NULL,
                        `button_text` VARCHAR(100) NULL,
                        `is_active` TINYINT(1) NOT NULL DEFAULT 1,
                        `sort_order` INT NOT NULL DEFAULT 0,
                        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        INDEX `idx_prod_links_prod` (`product_id`, `sort_order`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                ");
            }

            // 2. Garantir novas colunas em products
            $neededCols = [
                'materials' => 'VARCHAR(255) NULL',
                'dimensions' => 'VARCHAR(150) NULL',
                'colors' => 'VARCHAR(255) NULL',
                'variants_json' => 'TEXT NULL',
                'status' => "VARCHAR(20) NOT NULL DEFAULT 'published'",
                'sort_order' => 'INTEGER NOT NULL DEFAULT 0',
                'stock_status' => "VARCHAR(30) NOT NULL DEFAULT 'in_stock'",
                'short_description' => 'VARCHAR(255) NULL',
                'stone_mineral' => 'VARCHAR(150) NULL',
                'image_url' => 'VARCHAR(500) NULL',
                'external_url' => 'VARCHAR(500) NULL',
                'promotional_price_cents' => 'INTEGER NULL',
                'show_badge' => 'INTEGER NOT NULL DEFAULT 1',
                'show_specs' => 'INTEGER NOT NULL DEFAULT 1',
                'show_description' => 'INTEGER NOT NULL DEFAULT 1',
                'slug' => 'VARCHAR(255) NULL',
                'card_click_behavior' => "VARCHAR(50) NOT NULL DEFAULT 'default'",
                'custom_target_url' => 'VARCHAR(500) NULL',
                'detail_layout' => "VARCHAR(50) NOT NULL DEFAULT 'default'",
                'show_channels' => 'INTEGER NOT NULL DEFAULT 1',
            ];

            $existingCols = [];
            if ($driver === 'sqlite') {
                $stmt = $pdo->query("PRAGMA table_info(products)");
                if ($stmt) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $existingCols[] = strtolower($row['name']);
                    }
                }
            } else {
                $stmt = $pdo->query("SHOW COLUMNS FROM `products`");
                if ($stmt) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $existingCols[] = strtolower($row['Field']);
                    }
                }
            }

            foreach ($neededCols as $col => $definition) {
                if (!in_array(strtolower($col), $existingCols, true)) {
                    try {
                        if ($driver === 'sqlite') {
                            $pdo->exec("ALTER TABLE products ADD COLUMN {$col} {$definition}");
                        } else {
                            $pdo->exec("ALTER TABLE `products` ADD COLUMN `{$col}` {$definition}");
                        }
                    } catch (\Throwable $ex) {
                        // Coluna já existia ou adicionada concorrentemente
                    }
                }
            }

            // Auto-preenchimento de slugs ausentes nos produtos existentes
            try {
                $prodsWithoutSlug = $pdo->query("SELECT id, name, slug FROM products WHERE slug IS NULL OR slug = ''");
                if ($prodsWithoutSlug) {
                    $updateSlugStmt = $pdo->prepare("UPDATE products SET slug = ? WHERE id = ?");
                    while ($pRow = $prodsWithoutSlug->fetch(PDO::FETCH_ASSOC)) {
                        $baseSlug = preg_replace('~[^\pL\d]+~u', '-', mb_strtolower((string)$pRow['name']));
                        $baseSlug = trim(preg_replace('~[^-\w]+~', '', $baseSlug), '-');
                        if (empty($baseSlug)) {
                            $baseSlug = 'joia-' . $pRow['id'];
                        }
                        $updateSlugStmt->execute([$baseSlug, $pRow['id']]);
                    }
                }
            } catch (\Throwable $slugEx) {
                // Silencioso
            }

            // 2.1 Garantir novas colunas em product_links (cor, tamanho e target do botão)
            $neededLinkCols = [
                'button_color' => "VARCHAR(50) DEFAULT 'default'",
                'button_size' => "VARCHAR(20) DEFAULT 'md'",
                'target' => "VARCHAR(20) DEFAULT '_blank'",
            ];

            $existingLinkCols = [];
            if ($driver === 'sqlite') {
                $stmt = $pdo->query("PRAGMA table_info(product_links)");
                if ($stmt) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $existingLinkCols[] = strtolower($row['name']);
                    }
                }
            } else {
                $stmt = $pdo->query("SHOW COLUMNS FROM `product_links`");
                if ($stmt) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $existingLinkCols[] = strtolower($row['Field']);
                    }
                }
            }

            foreach ($neededLinkCols as $col => $definition) {
                if (!in_array(strtolower($col), $existingLinkCols, true)) {
                    try {
                        if ($driver === 'sqlite') {
                            $pdo->exec("ALTER TABLE product_links ADD COLUMN {$col} {$definition}");
                        } else {
                            $pdo->exec("ALTER TABLE `product_links` ADD COLUMN `{$col}` {$definition}");
                        }
                    } catch (\Throwable $ex) {
                        // Coluna já existia ou adicionada concorrentemente
                    }
                }
            }

            // 3. Migração automática e retrocompatibilidade para produtos existentes:
            // Se um produto tiver external_url e ainda não tiver registros em product_links,
            // adiciona o link na tabela product_links
            try {
                $prodsWithoutLinks = $pdo->query("
                    SELECT p.id, p.external_url, p.price_cents, p.name 
                    FROM products p 
                    WHERE p.external_url IS NOT NULL 
                      AND p.external_url != '' 
                      AND p.id NOT IN (SELECT DISTINCT product_id FROM product_links)
                ");
                if ($prodsWithoutLinks) {
                    $insertStmt = $pdo->prepare("
                        INSERT INTO product_links (product_id, platform_name, url, price_cents, button_text, is_active, sort_order) 
                        VALUES (?, ?, ?, ?, ?, 1, 0)
                    ");
                    while ($p = $prodsWithoutLinks->fetch(PDO::FETCH_ASSOC)) {
                        $url = trim((string) $p['external_url']);
                        $platName = 'Shopee';
                        if (stripos($url, 'mercadolivre') !== false || stripos($url, 'mercado_livre') !== false) {
                            $platName = 'Mercado Livre';
                        } elseif (stripos($url, 'amazon') !== false) {
                            $platName = 'Amazon';
                        } elseif (stripos($url, 'tiktok') !== false) {
                            $platName = 'TikTok Shop';
                        } elseif (stripos($url, 'wa.me') !== false || stripos($url, 'whatsapp') !== false) {
                            $platName = 'WhatsApp';
                        }
                        $btnText = $platName === 'Shopee' ? 'Comprar na Shopee Oficial' : ('Comprar no ' . $platName);
                        $insertStmt->execute([
                            $p['id'],
                            $platName,
                            $url,
                            $p['price_cents'] ?? 38000,
                            $btnText
                        ]);
                    }
                }
            } catch (\Throwable $migrationEx) {
                // Ignora silenciosamente
            }

            $ensured = true;
            $cacheDir = dirname($flagFile);
            if (!is_dir($cacheDir)) {
                @mkdir($cacheDir, 0755, true);
            }
            @touch($flagFile);
        } catch (\Throwable $e) {
            error_log('Database::ensureSchema warning: ' . $e->getMessage());
        }
    }
}
