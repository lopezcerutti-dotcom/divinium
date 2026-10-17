<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\View;

class StoreController
{
    /**
     * Página da Loja / Vitrine de Joias com links diretos para a Shopee
     * Carrega e gerencia os 100 produtos das categorias: Anéis, Braceletes e Brincos.
     */
    public function index(): void
    {
        $seedCatalog = self::getSeedCatalog();
        $categories = array_values($seedCatalog['categories'] ?? []);
        $products = $seedCatalog['products'] ?? [];

        try {
            $pdo = Database::getConnection();

            // Garantir que as tabelas de categorias e produtos existam
            self::ensureCatalogTables($pdo);

            // Verificar se o banco já possui os produtos; se tiver menos de 100, sincroniza
            $countStmt = $pdo->query('SELECT COUNT(*) FROM products');
            $totalInDb = $countStmt ? (int) $countStmt->fetchColumn() : 0;

            if ($totalInDb < 100 && !empty($products)) {
                self::syncSeedToDatabase($pdo, $seedCatalog);
            }

            // Buscar categorias ativas com contagem de produtos
            $categoriesStmt = $pdo->query('
                SELECT c.*, COUNT(p.id) as product_count
                FROM categories c
                LEFT JOIN products p ON p.category_id = c.id AND p.is_active = 1
                WHERE c.is_active = 1
                GROUP BY c.id
                ORDER BY c.sort_order ASC, c.name ASC
            ');
            if ($categoriesStmt) {
                $dbCategories = $categoriesStmt->fetchAll();
                if (!empty($dbCategories)) {
                    $categories = $dbCategories;
                }
            }

            // Buscar produtos ativos do banco
            $productsStmt = $pdo->query('
                SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.is_active = 1 
                ORDER BY p.is_featured DESC, p.stock_quantity DESC, p.id ASC
            ');
            if ($productsStmt) {
                $dbProducts = $productsStmt->fetchAll();
                if (!empty($dbProducts)) {
                    $products = $dbProducts;
                }
            }
        } catch (\Throwable $e) {
            error_log('Aviso ao sincronizar catálogo do banco: ' . $e->getMessage());
        }

        View::render('store/index', [
            'pageTitle' => 'Loja Oficial | Catálogo Completo Biojoias & Orgonites',
            'pageDescription' => 'Catálogo oficial Divinium com 100 biojoias e orgonites artesanais. Escolha seu anel, bracelete ou brinco com gemas e pedras naturais.',
            'categories' => $categories,
            'products' => $products
        ]);
    }

    /**
     * Retorna o catálogo oficial com os 100 produtos cadastrados a partir do CSV
     *
     * @return array{categories: array<int, array<string, mixed>>, products: array<int, array<string, mixed>>}
     */
    public static function getSeedCatalog(): array
    {
        $seedFile = defined('BASE_PATH') ? BASE_PATH . '/database/seed_products.php' : dirname(__DIR__, 2) . '/database/seed_products.php';
        if (file_exists($seedFile)) {
            $data = require $seedFile;
            if (is_array($data) && !empty($data['products'])) {
                return $data;
            }
        }

        // Fallback via JSON
        $jsonFile = defined('BASE_PATH') ? BASE_PATH . '/database/seed_products.json' : dirname(__DIR__, 2) . '/database/seed_products.json';
        if (file_exists($jsonFile)) {
            $json = json_decode((string) file_get_contents($jsonFile), true);
            if (is_array($json) && !empty($json)) {
                return [
                    'categories' => [
                        1 => ['id' => 1, 'name' => 'Anéis', 'slug' => 'aneis', 'description' => 'Biojoias e Anéis Orgonite artesanais com pedras naturais.'],
                        2 => ['id' => 2, 'name' => 'Braceletes', 'slug' => 'braceletes', 'description' => 'Braceletes Caminhos e Elo com matriz de resina e pedras minerais.'],
                        3 => ['id' => 3, 'name' => 'Brincos', 'slug' => 'brincos', 'description' => 'Brincos orgonite segundo furo com pedras naturais e acabamento refinado.'],
                    ],
                    'products' => $json
                ];
            }
        }

        return ['categories' => [], 'products' => []];
    }

    /**
     * Garante a criação resiliente das tabelas caso o banco esteja zerado
     */
    private static function ensureCatalogTables(\PDO $pdo): void
    {
        try {
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS categories (
                    id INTEGER PRIMARY KEY,
                    uuid VARCHAR(36) NOT NULL,
                    parent_id INTEGER NULL,
                    name VARCHAR(150) NOT NULL,
                    slug VARCHAR(160) NOT NULL,
                    description TEXT NULL,
                    sort_order INTEGER DEFAULT 0,
                    is_active INTEGER DEFAULT 1
                );
                CREATE TABLE IF NOT EXISTS products (
                    id INTEGER PRIMARY KEY,
                    uuid VARCHAR(36) NOT NULL,
                    category_id INTEGER NULL,
                    sku VARCHAR(64) NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    slug VARCHAR(255) NOT NULL,
                    description TEXT NULL,
                    stone_mineral VARCHAR(100) NULL,
                    price_cents INTEGER NOT NULL DEFAULT 38000,
                    stock_quantity INTEGER NOT NULL DEFAULT 0,
                    image_url VARCHAR(500) NULL,
                    external_url VARCHAR(500) NULL,
                    is_active INTEGER DEFAULT 1,
                    is_featured INTEGER DEFAULT 0
                );
            ");
        } catch (\Throwable $e) {
            // Silencioso se tabela já existir com schema avançado MySQL
        }
    }

    /**
     * Sincroniza os 100 produtos e 3 categorias no banco de dados de forma idempotente
     */
    private static function syncSeedToDatabase(\PDO $pdo, array $seedCatalog): void
    {
        try {
            // Sincronizar categorias
            foreach ($seedCatalog['categories'] as $cat) {
                $check = $pdo->prepare('SELECT id FROM categories WHERE id = ?');
                $check->execute([$cat['id']]);
                if (!$check->fetch()) {
                    $ins = $pdo->prepare('INSERT INTO categories (id, uuid, name, slug, description, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, 1)');
                    $ins->execute([$cat['id'], 'cat-' . $cat['slug'], $cat['name'], $cat['slug'], $cat['description'] ?? '', $cat['id']]);
                }
            }

            // Sincronizar produtos
            foreach ($seedCatalog['products'] as $prod) {
                $check = $pdo->prepare('SELECT id FROM products WHERE sku = ?');
                $check->execute([$prod['sku']]);
                if (!$check->fetch()) {
                    $ins = $pdo->prepare('
                        INSERT INTO products (
                            id, uuid, category_id, sku, name, slug, description, stone_mineral,
                            price_cents, stock_quantity, image_url, external_url, is_active, is_featured
                        ) VALUES (
                            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?
                        )
                    ');
                    $ins->execute([
                        $prod['id'],
                        $prod['uuid'],
                        $prod['category_id'],
                        $prod['sku'],
                        $prod['name'],
                        $prod['slug'],
                        $prod['description'],
                        $prod['stone_mineral'],
                        $prod['price_cents'],
                        $prod['stock_quantity'],
                        $prod['image_url'],
                        $prod['external_url'],
                        $prod['is_featured'] ?? 0
                    ]);
                }
            }
        } catch (\Throwable $e) {
            error_log('Falha ao sincronizar dados do seed: ' . $e->getMessage());
        }
    }
}
