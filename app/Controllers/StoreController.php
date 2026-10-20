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

            // Buscar produtos publicados com sua categoria ordenados por sort_order
            $productsStmt = $pdo->query("
                SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE (p.status = 'published' OR (p.status IS NULL AND p.is_active = 1)) 
                ORDER BY p.sort_order ASC, p.is_featured DESC, p.id DESC
            ");
            if ($productsStmt) {
                $products = $productsStmt->fetchAll() ?: [];
            }

            // Buscar todos os links de produtos ativos
            if (!empty($products)) {
                $linksStmt = $pdo->query('SELECT * FROM product_links WHERE is_active = 1 ORDER BY sort_order ASC, id ASC');
                $allLinks = $linksStmt ? $linksStmt->fetchAll() : [];
                $linksByProduct = [];
                foreach ($allLinks as $l) {
                    $linksByProduct[(int) $l['product_id']][] = $l;
                }

                foreach ($products as &$prod) {
                    if (empty($prod['slug'])) {
                        $prod['slug'] = self::slugify((string)($prod['name'] ?? ('joia-' . $prod['id'])));
                    }
                    $prod['links'] = $linksByProduct[(int) $prod['id']] ?? [];
                    if (empty($prod['links']) && !empty($prod['external_url'])) {
                        $prod['links'] = [
                            [
                                'id' => 0,
                                'product_id' => $prod['id'],
                                'platform_name' => 'Shopee',
                                'url' => $prod['external_url'],
                                'price_cents' => $prod['price_cents'] ?? 38000,
                                'button_text' => 'Comprar na Shopee Oficial',
                                'button_color' => 'default',
                                'button_size' => 'md',
                                'target' => '_blank',
                                'is_active' => 1,
                                'sort_order' => 0
                            ]
                        ];
                    }
                }
                unset($prod);
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
     * Página de Detalhes da Joia (/produto/{slug} ou /loja/{slug})
     * Experiência Quiet Luxury com visualização completa e canais de venda imparciais
     *
     * @param array<string, string> $params
     */
    public function show(array $params = []): void
    {
        $rawSlug = trim((string) ($params['slug'] ?? ''));
        if ($rawSlug === '') {
            header('Location: /loja');
            exit;
        }

        $product = null;
        $links = [];
        $relatedProducts = [];

        try {
            $pdo = Database::getConnection();

            $isNumeric = is_numeric($rawSlug);
            $sql = "
                SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE (p.slug = :slug " . ($isNumeric ? "OR p.id = :num_id" : "") . ")
                  AND (p.status = 'published' OR (p.status IS NULL AND p.is_active = 1))
                LIMIT 1
            ";
            $stmt = $pdo->prepare($sql);
            $bindings = ['slug' => $rawSlug];
            if ($isNumeric) {
                $bindings['num_id'] = (int) $rawSlug;
            }
            $stmt->execute($bindings);
            $product = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;

            if ($product) {
                if (empty($product['slug'])) {
                    $product['slug'] = self::slugify((string)$product['name']);
                }

                // Buscar canais de venda ativos e ordenados
                $linksStmt = $pdo->prepare('
                    SELECT * FROM product_links 
                    WHERE product_id = ? AND is_active = 1 
                    ORDER BY sort_order ASC, id ASC
                ');
                $linksStmt->execute([(int)$product['id']]);
                $links = $linksStmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];

                // Se não houver canais em product_links mas houver external_url cadastrada
                if (empty($links) && !empty($product['external_url'])) {
                    $platName = 'Shopee';
                    $u = (string)$product['external_url'];
                    if (stripos($u, 'mercadolivre') !== false || stripos($u, 'mercado_livre') !== false) {
                        $platName = 'Mercado Livre';
                    } elseif (stripos($u, 'amazon') !== false) {
                        $platName = 'Amazon';
                    } elseif (stripos($u, 'tiktok') !== false) {
                        $platName = 'TikTok Shop';
                    } elseif (stripos($u, 'wa.me') !== false || stripos($u, 'whatsapp') !== false) {
                        $platName = 'WhatsApp';
                    }
                    $links = [
                        [
                            'id' => 0,
                            'product_id' => $product['id'],
                            'platform_name' => $platName,
                            'url' => $product['external_url'],
                            'price_cents' => $product['price_cents'] ?? 38000,
                            'button_text' => 'Comprar na ' . $platName,
                            'button_color' => 'default',
                            'button_size' => 'md',
                            'target' => '_blank',
                            'is_active' => 1,
                            'sort_order' => 0
                        ]
                    ];
                }

                // Buscar joias relacionadas (mesma categoria)
                $catId = (int) ($product['category_id'] ?? 0);
                $relSql = "
                    SELECT p.*, c.name as category_name 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    WHERE p.id != ? 
                      AND (p.status = 'published' OR (p.status IS NULL AND p.is_active = 1))
                      " . ($catId > 0 ? "AND p.category_id = " . $catId : "") . "
                    ORDER BY p.is_featured DESC, p.sort_order ASC, p.id DESC 
                    LIMIT 4
                ";
                $relStmt = $pdo->prepare($relSql);
                $relStmt->execute([(int)$product['id']]);
                $relatedProducts = $relStmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
                foreach ($relatedProducts as &$rp) {
                    if (empty($rp['slug'])) {
                        $rp['slug'] = self::slugify((string)($rp['name'] ?? 'joia-' . $rp['id']));
                    }
                }
                unset($rp);
            }
        } catch (\Throwable $e) {
            error_log('Erro ao buscar produto: ' . $e->getMessage());
        }

        // Fallback resiliente: Catálogo de demonstração caso ainda não haja banco configurado
        if (!$product) {
            $defaults = self::getDefaultProducts();
            foreach ($defaults as $def) {
                $defSlug = $def['slug'] ?? self::slugify($def['name']);
                if ($defSlug === $rawSlug || (string)$def['id'] === $rawSlug) {
                    $product = $def;
                    $links = $def['links'] ?? [];
                    break;
                }
            }

            if ($product) {
                foreach ($defaults as $def) {
                    if ($def['id'] !== $product['id']) {
                        $relatedProducts[] = $def;
                    }
                }
            }
        }

        // Se ainda assim o produto não existe, redireciona suavemente para a vitrine
        if (!$product) {
            header('Location: /loja');
            exit;
        }

        View::render('store/show', [
            'pageTitle' => ($product['name'] ?? 'Joia Autoral') . ' | Boutique DIVINIUM',
            'pageDescription' => !empty($product['short_description']) 
                ? $product['short_description'] 
                : (!empty($product['description']) ? substr(strip_tags((string)$product['description']), 0, 160) : 'Conheça todos os detalhes desta joia exclusiva DIVINIUM.'),
            'product' => $product,
            'links' => $links,
            'relatedProducts' => $relatedProducts
        ]);
    }

    /**
     * Helper para gerar slugs amigáveis e limpos
     */
    public static function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', mb_strtolower($text));
        $text = trim(preg_replace('~[^-\w]+~', '', $text), '-');
        return !empty($text) ? $text : 'joia';
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
                'slug' => 'bracelete-caminhos-concha-verde',
                'description' => 'Prata de Lei 925 com incrustação artesanal de concha verde esmeralda e acabamento espelhado.',
                'stone_mineral' => 'Concha Verde Natural',
                'sku' => 'DIV-CAM-01',
                'materials' => 'Prata 925 & Concha Natural',
                'dimensions' => '17cm a 20cm ajustável',
                'colors' => 'Verde Esmeralda',
                'stock_status' => 'in_stock',
                'price_cents' => 38000,
                'compare_at_price_cents' => 45000,
                'image_url' => '/assets/images/hero/featured-green-shell.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Concha Verde'),
                'category_id' => 1,
                'category_name' => 'Coleção Caminhos',
                'is_featured' => 1,
                'links' => [
                    [
                        'id' => 1,
                        'product_id' => 1,
                        'platform_name' => 'Shopee',
                        'url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Concha Verde'),
                        'price_cents' => 38000,
                        'button_text' => 'Comprar na Shopee Oficial',
                        'is_active' => 1,
                        'sort_order' => 1
                    ],
                    [
                        'id' => 2,
                        'product_id' => 1,
                        'platform_name' => 'Mercado Livre',
                        'url' => 'https://lista.mercadolivre.com.br/divinium-concha-verde',
                        'price_cents' => 39900,
                        'button_text' => 'Comprar no Mercado Livre',
                        'is_active' => 1,
                        'sort_order' => 2
                    ]
                ]
            ],
            [
                'id' => 2,
                'name' => 'Bracelete Caminhos — Madrepérola Branca',
                'slug' => 'bracelete-caminhos-madreperola',
                'description' => 'A pureza atemporal da madrepérola natural harmonizada com o brilho da prata 925.',
                'stone_mineral' => 'Madrepérola Pura',
                'sku' => 'DIV-CAM-02',
                'materials' => 'Prata 925 & Madrepérola',
                'dimensions' => '17cm a 20cm ajustável',
                'colors' => 'Branco Iridescente',
                'stock_status' => 'in_stock',
                'price_cents' => 38000,
                'compare_at_price_cents' => 45000,
                'image_url' => '/assets/images/products/bracelete-madreperola.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Madreperola'),
                'category_id' => 1,
                'category_name' => 'Coleção Caminhos',
                'is_featured' => 1,
                'links' => [
                    [
                        'id' => 3,
                        'product_id' => 2,
                        'platform_name' => 'Shopee',
                        'url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Madreperola'),
                        'price_cents' => 38000,
                        'button_text' => 'Comprar na Shopee Oficial',
                        'is_active' => 1,
                        'sort_order' => 1
                    ],
                    [
                        'id' => 4,
                        'product_id' => 2,
                        'platform_name' => 'Mercado Livre',
                        'url' => 'https://lista.mercadolivre.com.br/divinium-madreperola',
                        'price_cents' => 39900,
                        'button_text' => 'Comprar no Mercado Livre',
                        'is_active' => 1,
                        'sort_order' => 2
                    ]
                ]
            ],
            [
                'id' => 3,
                'name' => 'Bracelete Caminhos — Ônix Preto Absoluto',
                'slug' => 'bracelete-caminhos-onix',
                'description' => 'Contraste poderoso e elegância sóbria com gema natural de ônix preto e prata de lei.',
                'stone_mineral' => 'Ônix Preto Natural',
                'sku' => 'DIV-CAM-03',
                'materials' => 'Prata 925 & Ônix Preto',
                'dimensions' => '17cm a 20cm ajustável',
                'colors' => 'Preto Absoluto',
                'stock_status' => 'in_stock',
                'price_cents' => 38000,
                'compare_at_price_cents' => 45000,
                'image_url' => '/assets/images/products/bracelete-onix.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Onix'),
                'category_id' => 2,
                'category_name' => 'Pedras Raras',
                'is_featured' => 1,
                'links' => [
                    [
                        'id' => 5,
                        'product_id' => 3,
                        'platform_name' => 'Shopee',
                        'url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Onix'),
                        'price_cents' => 38000,
                        'button_text' => 'Comprar na Shopee Oficial',
                        'is_active' => 1,
                        'sort_order' => 1
                    ],
                    [
                        'id' => 6,
                        'product_id' => 3,
                        'platform_name' => 'Amazon',
                        'url' => 'https://amazon.com.br/dp/divinium-onix',
                        'price_cents' => 41000,
                        'button_text' => 'Comprar na Amazon Brasil',
                        'is_active' => 1,
                        'sort_order' => 2
                    ]
                ]
            ],
            [
                'id' => 4,
                'name' => 'Bracelete Caminhos — Coral Vermelho Imperial',
                'slug' => 'bracelete-caminhos-coral-vermelho',
                'description' => 'Vibratilidade e distinção mineral em tom carmim profundo esculpido à mão.',
                'stone_mineral' => 'Coral Vermelho',
                'sku' => 'DIV-CAM-04',
                'materials' => 'Prata 925 & Coral Mineral',
                'dimensions' => '17cm a 20cm ajustável',
                'colors' => 'Vermelho Imperial',
                'stock_status' => 'in_stock',
                'price_cents' => 38000,
                'compare_at_price_cents' => 45000,
                'image_url' => '/assets/images/products/bracelete-vermelho.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Coral Vermelho'),
                'category_id' => 2,
                'category_name' => 'Pedras Raras',
                'is_featured' => 0,
                'links' => [
                    [
                        'id' => 7,
                        'product_id' => 4,
                        'platform_name' => 'Shopee',
                        'url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Coral Vermelho'),
                        'price_cents' => 38000,
                        'button_text' => 'Comprar na Shopee Oficial',
                        'is_active' => 1,
                        'sort_order' => 1
                    ]
                ]
            ],
            [
                'id' => 5,
                'name' => 'Bracelete Caminhos — Rose Gold & Quartzo',
                'slug' => 'bracelete-caminhos-rose-gold',
                'description' => 'Banhado em ouro rosé nobre com acabamento acetinado de alta joalheria.',
                'stone_mineral' => 'Ouro Rosé & Cristal',
                'sku' => 'DIV-CAM-05',
                'materials' => 'Banho Ouro Rosé & Quartzo Cristal',
                'dimensions' => '17cm a 20cm ajustável',
                'colors' => 'Rosé Nobre',
                'stock_status' => 'preorder',
                'price_cents' => 42000,
                'compare_at_price_cents' => 49000,
                'image_url' => '/assets/images/products/bracelete-rose-gold.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Rose Gold'),
                'category_id' => 3,
                'category_name' => 'Edição Limitada',
                'is_featured' => 0,
                'links' => [
                    [
                        'id' => 8,
                        'product_id' => 5,
                        'platform_name' => 'Shopee',
                        'url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Rose Gold'),
                        'price_cents' => 42000,
                        'button_text' => 'Comprar na Shopee Oficial',
                        'is_active' => 1,
                        'sort_order' => 1
                    ],
                    [
                        'id' => 9,
                        'product_id' => 5,
                        'platform_name' => 'WhatsApp VIP',
                        'url' => 'https://wa.me/5511999999999?text=Gostaria%20de%20encomendar%20o%20Bracelete%20Rose%20Gold',
                        'price_cents' => 42000,
                        'button_text' => 'Encomendar via WhatsApp VIP',
                        'is_active' => 1,
                        'sort_order' => 2
                    ]
                ]
            ],
            [
                'id' => 6,
                'name' => 'Coleção Completa — Caixa Exclusiva de Espelho',
                'slug' => 'colecao-completa-espelho',
                'description' => 'Estojo com a tríade autoral da Coleção Caminhos em estojo nobre aveludado.',
                'stone_mineral' => 'Coleção Tríade Mineral',
                'sku' => 'DIV-SET-01',
                'materials' => 'Prata 925 & Tríade de Gemas',
                'dimensions' => 'Estojo de Luxo Nobre',
                'colors' => 'Coleção Tríade',
                'stock_status' => 'in_stock',
                'price_cents' => 99000,
                'compare_at_price_cents' => 114000,
                'image_url' => '/assets/images/products/colecao-completa-espelho.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Colecao Caminhos'),
                'category_id' => 3,
                'category_name' => 'Edição Limitada',
                'is_featured' => 1,
                'links' => [
                    [
                        'id' => 10,
                        'product_id' => 6,
                        'platform_name' => 'Shopee',
                        'url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Colecao Caminhos'),
                        'price_cents' => 99000,
                        'button_text' => 'Comprar na Shopee Oficial',
                        'is_active' => 1,
                        'sort_order' => 1
                    ],
                    [
                        'id' => 11,
                        'product_id' => 6,
                        'platform_name' => 'Mercado Livre',
                        'url' => 'https://lista.mercadolivre.com.br/divinium-colecao-completa',
                        'price_cents' => 105000,
                        'button_text' => 'Comprar no Mercado Livre',
                        'is_active' => 1,
                        'sort_order' => 2
                    ]
                ]
            ],
        ];
    }
}
