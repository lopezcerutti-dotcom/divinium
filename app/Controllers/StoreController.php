<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\View;

class StoreController
{
    /**
     * Página da Loja / Vitrine de Joias com links diretos para a Shopee
     */
    public function index(): void
    {
        $categories = [];
        $products = [];

        try {
            $pdo = Database::getConnection();

            // Buscar categorias ativas
            $categoriesStmt = $pdo->query('SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order ASC, name ASC');
            if ($categoriesStmt) {
                $categories = $categoriesStmt->fetchAll() ?: [];
            }

            // Buscar produtos ativos com sua categoria
            $productsStmt = $pdo->query('
                SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.is_active = 1 
                ORDER BY p.is_featured DESC, p.id DESC
            ');
            if ($productsStmt) {
                $products = $productsStmt->fetchAll() ?: [];
            }
        } catch (\Throwable $e) {
            error_log('Aviso ao carregar produtos do banco: ' . $e->getMessage());
        }

        // AUTO-CURA: Se o banco estiver vazio ou tabelas ainda não importadas,
        // preenche com o catálogo autoral da Coleção Caminhos para a loja nunca ficar vazia!
        if (empty($products)) {
            $products = self::getDefaultProducts();
        }

        if (empty($categories)) {
            $categories = [
                ['id' => 1, 'name' => 'Coleção Caminhos', 'slug' => 'colecao-caminhos'],
                ['id' => 2, 'name' => 'Pedras Raras', 'slug' => 'pedras-raras'],
                ['id' => 3, 'name' => 'Edição Limitada', 'slug' => 'edicao-limitada'],
            ];
        }

        View::render('store/index', [
            'pageTitle' => 'Loja Oficial | Joias & Coleções Autorais',
            'pageDescription' => 'Conheça o catálogo completo de joias Divinium. Escolha sua gema mineral e finalize sua compra com segurança na nossa loja oficial na Shopee.',
            'categories' => $categories,
            'products' => $products
        ]);
    }

    /**
     * Catálogo fallback de alta definição com as joias Divinium
     *
     * @return array<int, array<string, mixed>>
     */
    private static function getDefaultProducts(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Bracelete Caminhos — Concha Verde Esmeralda',
                'description' => 'Prata de Lei 925 com incrustação artesanal de concha verde esmeralda e acabamento espelhado.',
                'stone_mineral' => 'Concha Verde Natural',
                'price_cents' => 38000,
                'compare_at_price_cents' => 45000,
                'image_url' => '/assets/images/hero/featured-green-shell.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Concha Verde'),
                'category_id' => 1,
                'category_name' => 'Coleção Caminhos',
                'is_featured' => 1
            ],
            [
                'id' => 2,
                'name' => 'Bracelete Caminhos — Madrepérola Branca',
                'description' => 'A pureza atemporal da madrepérola natural harmonizada com o brilho da prata 925.',
                'stone_mineral' => 'Madrepérola Pura',
                'price_cents' => 38000,
                'compare_at_price_cents' => 45000,
                'image_url' => '/assets/images/products/bracelete-madreperola.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Madreperola'),
                'category_id' => 1,
                'category_name' => 'Coleção Caminhos',
                'is_featured' => 1
            ],
            [
                'id' => 3,
                'name' => 'Bracelete Caminhos — Ônix Preto Absoluto',
                'description' => 'Contraste poderoso e elegância sóbria com gema natural de ônix preto e prata de lei.',
                'stone_mineral' => 'Ônix Preto Natural',
                'price_cents' => 38000,
                'compare_at_price_cents' => 45000,
                'image_url' => '/assets/images/products/bracelete-onix.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Onix'),
                'category_id' => 2,
                'category_name' => 'Pedras Raras',
                'is_featured' => 1
            ],
            [
                'id' => 4,
                'name' => 'Bracelete Caminhos — Coral Vermelho Imperial',
                'description' => 'Vibratilidade e distinção mineral em tom carmim profundo esculpido à mão.',
                'stone_mineral' => 'Coral Vermelho',
                'price_cents' => 38000,
                'compare_at_price_cents' => 45000,
                'image_url' => '/assets/images/products/bracelete-vermelho.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Coral Vermelho'),
                'category_id' => 2,
                'category_name' => 'Pedras Raras',
                'is_featured' => 0
            ],
            [
                'id' => 5,
                'name' => 'Bracelete Caminhos — Rose Gold & Quartzo',
                'description' => 'Banhado em ouro rosé nobre com acabamento acetinado de alta joalheria.',
                'stone_mineral' => 'Ouro Rosé & Cristal',
                'price_cents' => 42000,
                'compare_at_price_cents' => 49000,
                'image_url' => '/assets/images/products/bracelete-rose-gold.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Rose Gold'),
                'category_id' => 3,
                'category_name' => 'Edição Limitada',
                'is_featured' => 0
            ],
            [
                'id' => 6,
                'name' => 'Coleção Completa — Caixa Exclusiva de Espelho',
                'description' => 'Estojo com a tríade autoral da Coleção Caminhos em estojo nobre aveludado.',
                'stone_mineral' => 'Coleção Tríade Mineral',
                'price_cents' => 99000,
                'compare_at_price_cents' => 114000,
                'image_url' => '/assets/images/products/colecao-completa-espelho.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Colecao Caminhos'),
                'category_id' => 3,
                'category_name' => 'Edição Limitada',
                'is_featured' => 1
            ],
        ];
    }
}
