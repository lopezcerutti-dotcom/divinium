<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\View;

class StoreController
{
    /**
     * Página da Loja / Vitrine de Joias com links diretos para a Shopee
     * Exibe EXATAMENTE um card para cada modelo de joia, reunindo variações no detalhe.
     */
    public function index(): void
    {
        $categories = [];
        $products = [];

        try {
            $pdo = Database::getConnection();

            // Buscar categorias ativas
            $catStmt = $pdo->query("SELECT id, name, slug, description FROM categories WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
            if ($catStmt) {
                $categories = $catStmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
            }

            // Buscar modelos principais únicos (produtos base com parent_sku nulo, vazio ou igual a sku)
            $productsStmt = $pdo->query("
                SELECT p.*, c.name as category_name, c.slug as category_slug,
                       (SELECT COUNT(*) FROM products v WHERE (v.parent_sku = p.sku OR v.sku = p.sku) AND (v.status = 'published' OR (v.status IS NULL AND v.is_active = 1))) as variations_count,
                       (SELECT MIN(v.price_cents) FROM products v WHERE (v.parent_sku = p.sku OR v.sku = p.sku) AND (v.status = 'published' OR (v.status IS NULL AND v.is_active = 1))) as min_price_cents
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE (p.status = 'published' OR (p.status IS NULL AND p.is_active = 1)) 
                  AND (p.parent_sku IS NULL OR p.parent_sku = '' OR p.parent_sku = p.sku)
                ORDER BY p.category_id ASC, p.sort_order ASC, p.id ASC
            ");
            if ($productsStmt) {
                $products = $productsStmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
            }

            // Fallback de agrupamento caso parent_sku ainda não esteja totalmente indexado
            if (count($products) < 8) {
                $allRawStmt = $pdo->query("
                    SELECT p.*, c.name as category_name, c.slug as category_slug 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    WHERE (p.status = 'published' OR (p.status IS NULL AND p.is_active = 1)) 
                    ORDER BY p.category_id ASC, p.sort_order ASC, p.id ASC
                ");
                $allRaw = $allRawStmt ? ($allRawStmt->fetchAll(\PDO::FETCH_ASSOC) ?: []) : [];
                $grouped = [];
                foreach ($allRaw as $item) {
                    $sku = trim((string)($item['sku'] ?? ''));
                    $parent = trim((string)($item['parent_sku'] ?? ''));
                    if ($parent !== '' && $parent !== $sku) {
                        $baseSku = $parent;
                    } else {
                        $parts = explode('-', $sku);
                        $baseSku = count($parts) >= 3 ? implode('-', array_slice($parts, 0, 3)) : $sku;
                    }
                    if (!isset($grouped[$baseSku])) {
                        $item['variations_count'] = 1;
                        $item['min_price_cents'] = $item['price_cents'] ?? 3500;
                        $grouped[$baseSku] = $item;
                    } else {
                        $grouped[$baseSku]['variations_count']++;
                        if (!empty($item['price_cents']) && $item['price_cents'] < $grouped[$baseSku]['min_price_cents']) {
                            $grouped[$baseSku]['min_price_cents'] = $item['price_cents'];
                        }
                    }
                }
                if (!empty($grouped)) {
                    $products = array_values($grouped);
                }
            }

            // Buscar todos os links de produtos ativos
            if (!empty($products)) {
                $linksStmt = $pdo->query('SELECT * FROM product_links WHERE is_active = 1 ORDER BY sort_order ASC, id ASC');
                $allLinks = $linksStmt ? $linksStmt->fetchAll(\PDO::FETCH_ASSOC) : [];
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

        // AUTO-CURA: Se o banco estiver vazio, carrega os 8 modelos autorais com suas imagens reais
        if (empty($products)) {
            $products = self::getDefaultProducts();
        }

        if (empty($categories)) {
            $categories = [
                ['id' => 1, 'name' => 'Anéis', 'slug' => 'aneis'],
                ['id' => 2, 'name' => 'Braceletes', 'slug' => 'braceletes'],
                ['id' => 3, 'name' => 'Brincos', 'slug' => 'brincos'],
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

                // Identificar o SKU base do modelo
                $rawModelSku = !empty($product['parent_sku']) ? $product['parent_sku'] : $product['sku'];
                $parts = explode('-', (string)$rawModelSku);
                $baseModelSku = count($parts) >= 3 ? implode('-', array_slice($parts, 0, 3)) : $rawModelSku;

                // Buscar todas as variações deste modelo
                $variations = [];
                try {
                    $varStmt = $pdo->prepare("
                        SELECT p.*, c.name as category_name 
                        FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.id 
                        WHERE (p.sku = :base_sku OR p.parent_sku = :base_sku OR p.sku LIKE :prefix)
                          AND (p.status = 'published' OR (p.status IS NULL AND p.is_active = 1))
                        ORDER BY CASE WHEN p.sku = :base_sku THEN 0 ELSE 1 END, p.sort_order ASC, p.id ASC
                    ");
                    $varStmt->execute([
                        ':base_sku' => $baseModelSku,
                        ':prefix'   => $baseModelSku . '-%'
                    ]);
                    $variations = $varStmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
                } catch (\Throwable $varEx) {
                    // Silencioso
                }

                // Fallback para arquivo seed caso a tabela de produtos tenha apenas o produto individual
                if (empty($variations) || count($variations) <= 1) {
                    $seedFile = dirname(__DIR__, 2) . '/database/seed_products.json';
                    if (file_exists($seedFile)) {
                        $seedJson = json_decode((string)@file_get_contents($seedFile), true);
                        if (is_array($seedJson)) {
                            $variations = [];
                            foreach ($seedJson as $sp) {
                                $spSku = (string)($sp['sku'] ?? '');
                                $spParent = (string)($sp['parent_sku'] ?? '');
                                if ($spSku === $baseModelSku || $spParent === $baseModelSku || str_starts_with($spSku, $baseModelSku . '-')) {
                                    $variations[] = $sp;
                                }
                            }
                        }
                    }
                }

                // Garantir links e dados de cada variação
                foreach ($variations as &$v) {
                    if (empty($v['image_url'])) {
                        $v['image_url'] = '/assets/images/products/an-rd-20-capa.jpg';
                    }
                    if (empty($v['external_url'])) {
                        $v['external_url'] = 'https://shopee.com.br/search?keyword=' . urlencode('Divinium ' . ($v['name'] ?? ''));
                    }
                    $v['price_formatted'] = 'R$ ' . number_format(((int)($v['price_cents'] ?? 3500)) / 100, 2, ',', '.');
                }
                unset($v);

                // Buscar joias relacionadas (outros modelos da mesma categoria)
                $catId = (int) ($product['category_id'] ?? 0);
                $relSql = "
                    SELECT p.*, c.name as category_name 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    WHERE (p.sku != :cur_sku AND (p.parent_sku IS NULL OR p.parent_sku = '' OR p.parent_sku = p.sku))
                      AND (p.status = 'published' OR (p.status IS NULL AND p.is_active = 1))
                      " . ($catId > 0 ? "AND p.category_id = " . $catId : "") . "
                    ORDER BY p.sort_order ASC, p.id ASC 
                    LIMIT 4
                ";
                $relStmt = $pdo->prepare($relSql);
                $relStmt->execute([':cur_sku' => $baseModelSku]);
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
                if ($defSlug === $rawSlug || (string)$def['id'] === $rawSlug || ($def['sku'] ?? '') === $rawSlug) {
                    $product = $def;
                    $links = $def['links'] ?? [];
                    break;
                }
            }

        // Garantir que as variações sejam carregadas do seed_products.json se ainda não estiverem preenchidas
        if ($product && (empty($variations) || count($variations) <= 1)) {
            $rawModelSku = !empty($product['parent_sku']) ? $product['parent_sku'] : ($product['sku'] ?? '');
            $parts = explode('-', (string)$rawModelSku);
            $baseModelSku = count($parts) >= 3 ? implode('-', array_slice($parts, 0, 3)) : $rawModelSku;
            $seedFile = dirname(__DIR__, 2) . '/database/seed_products.json';
            if (file_exists($seedFile)) {
                $seedJson = json_decode((string)@file_get_contents($seedFile), true);
                if (is_array($seedJson)) {
                    $variations = [];
                    foreach ($seedJson as $sp) {
                        $spSku = (string)($sp['sku'] ?? '');
                        $spParent = (string)($sp['parent_sku'] ?? '');
                        if ($spSku === $baseModelSku || $spParent === $baseModelSku || str_starts_with($spSku, $baseModelSku . '-')) {
                            if (empty($sp['image_url'])) {
                                $sp['image_url'] = $product['image_url'] ?? '/assets/images/products/an-rd-20-capa.jpg';
                            }
                            if (empty($sp['external_url'])) {
                                $sp['external_url'] = 'https://shopee.com.br/search?keyword=' . urlencode('Divinium ' . ($sp['name'] ?? ''));
                            }
                            $sp['price_formatted'] = 'R$ ' . number_format(((int)($sp['price_cents'] ?? ($product['price_cents'] ?? 3500))) / 100, 2, ',', '.');
                            $variations[] = $sp;
                        }
                    }
                }
            }
        }

        // Se nenhum link estiver cadastrado, cria o canal oficial Shopee
        if (empty($links)) {
            $links = [
                [
                    'id' => 0,
                    'product_id' => $product['id'] ?? 0,
                    'platform_name' => 'Shopee',
                    'url' => !empty($product['external_url']) ? $product['external_url'] : ('https://shopee.com.br/search?keyword=' . urlencode('Divinium ' . ($product['name'] ?? ''))),
                    'price_cents' => $product['price_cents'] ?? 38000,
                    'button_text' => 'Comprar na Shopee Oficial',
                    'button_color' => 'default',
                    'button_size' => 'md',
                    'target' => '_blank',
                    'is_active' => 1,
                    'sort_order' => 0
                ]
            ];
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
            'variations' => $variations ?? [],
            'selectedSku' => $product['sku'] ?? '',
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
     * Catálogo dos 8 Modelos Oficiais DIVINIUM (Organização sem duplicatas)
     *
     * @return array<int, array<string, mixed>>
     */
    private static function getDefaultProducts(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Anel Redondo Orgonite 2,5cm',
                'slug' => 'an-rd-25-anel-redondo-orgonite-2-5cm',
                'description' => 'Biojoia orgonite artesanal Divinium em formato circular de 2,5 cm. Confeccionada com resina nobre de alta pureza e aro regulável em metal com acabamento prateado brilhante.',
                'stone_mineral' => 'Orgonite & Gemas Minerais',
                'sku' => 'AN-RD-25',
                'materials' => 'Resina Nobre & Metal Prateado',
                'dimensions' => '2,5 cm de diâmetro (aro ajustável)',
                'colors' => '7 Opções de Pedras Minerais',
                'stock_status' => 'in_stock',
                'price_cents' => 4000,
                'compare_at_price_cents' => 5000,
                'image_url' => '/assets/images/products/an-rd-25-capa.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Anel Redondo Orgonite 2.5cm'),
                'category_id' => 1,
                'category_name' => 'Anéis',
                'variations_count' => 7,
                'min_price_cents' => 4000,
                'is_featured' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Anel Redondo Orgonite 2cm',
                'slug' => 'an-rd-20-anel-redondo-orgonite-2cm',
                'description' => 'Elegância e equilíbrio com cabochão circular de 2 cm em matriz cristalina de resina límpida e aro ajustável. Disponível em 17 pedras e acabamentos naturais autênticos.',
                'stone_mineral' => 'Orgonite & Gemas Minerais',
                'sku' => 'AN-RD-20',
                'materials' => 'Resina Nobre & Metal Prateado',
                'dimensions' => '2,0 cm de diâmetro (aro ajustável)',
                'colors' => '17 Opções de Pedras Minerais',
                'stock_status' => 'in_stock',
                'price_cents' => 3500,
                'compare_at_price_cents' => 4500,
                'image_url' => '/assets/images/products/an-rd-20-capa.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Anel Redondo Orgonite 2cm'),
                'category_id' => 1,
                'category_name' => 'Anéis',
                'variations_count' => 17,
                'min_price_cents' => 3500,
                'is_featured' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Anel Oval Orgonite 3cm x 4cm',
                'slug' => 'an-ov-40-anel-oval-orgonote-3cm-x-4cm',
                'description' => 'Peça de presença marcante com cabochão oval de 3cm por 4cm. Envolve cristais naturais e elementos energéticos em resina cristalina límpida com aro metálico ajustável.',
                'stone_mineral' => 'Orgonite & Gemas Minerais',
                'sku' => 'AN-OV-40',
                'materials' => 'Resina Nobre & Metal Prateado',
                'dimensions' => '3 cm x 4 cm (aro ajustável)',
                'colors' => '17 Opções de Pedras Minerais',
                'stock_status' => 'in_stock',
                'price_cents' => 4800,
                'compare_at_price_cents' => 6000,
                'image_url' => '/assets/images/products/an-ov-40-capa.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Anel Oval Orgonite 3x4cm'),
                'category_id' => 1,
                'category_name' => 'Anéis',
                'variations_count' => 17,
                'min_price_cents' => 4800,
                'is_featured' => 0,
            ],
            [
                'id' => 4,
                'name' => 'Brincos Segundo Furo 6mm (0,6cm)',
                'slug' => 'br-sf-06-brincos-segundo-furo-6-mm-0-6-cm',
                'description' => 'Delicado par de brincos ponto de luz com elemento circular de 6 mm em resina límpida com gema mineral natural e tarraxa segura em metal prateado.',
                'stone_mineral' => 'Orgonite & Gemas Minerais',
                'sku' => 'BR-SF-06',
                'materials' => 'Metal Prateado & Resina',
                'dimensions' => '6 mm de diâmetro (0,6 cm)',
                'colors' => '17 Opções de Pedras Minerais',
                'stock_status' => 'in_stock',
                'price_cents' => 3500,
                'compare_at_price_cents' => 4500,
                'image_url' => '/assets/images/products/br-sf-06-capa.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Brincos Segundo Furo 6mm'),
                'category_id' => 3,
                'category_name' => 'Brincos',
                'variations_count' => 17,
                'min_price_cents' => 3500,
                'is_featured' => 1,
            ],
            [
                'id' => 5,
                'name' => 'Bracelete Caminhos Redondo 2cm',
                'slug' => 'brac-cam-20-bracelete-caminhos-redondo-2-cm',
                'description' => 'Bracelete de aro aberto rígido em metal prateado com chatão circular de 2 cm. A união perfeita entre o desenho contemporâneo e o poder da lapidação mineral artesanal.',
                'stone_mineral' => 'Orgonite & Gemas Minerais',
                'sku' => 'BRAC-CAM-20',
                'materials' => 'Metal Prateado & Resina',
                'dimensions' => 'Aro ajustável, chatão de 2,0 cm',
                'colors' => '16 Opções de Pedras Minerais',
                'stock_status' => 'in_stock',
                'price_cents' => 4800,
                'compare_at_price_cents' => 5800,
                'image_url' => '/assets/images/products/brac-cam-20-capa.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Caminhos Redondo 2cm'),
                'category_id' => 2,
                'category_name' => 'Braceletes',
                'variations_count' => 16,
                'min_price_cents' => 4800,
                'is_featured' => 1,
            ],
            [
                'id' => 6,
                'name' => 'Bracelete Caminhos Redondo 2,5cm',
                'slug' => 'brac-cam-25-bracelete-caminhos-redondo-2-5-cm',
                'description' => 'Presença e sofisticação com chatão redondo generoso de 2,5 cm em matriz mineral autoral e aro duplo com esferas nas extremidades.',
                'stone_mineral' => 'Orgonite & Gemas Minerais',
                'sku' => 'BRAC-CAM-25',
                'materials' => 'Metal Prateado & Resina',
                'dimensions' => 'Aro ajustável, chatão de 2,5 cm',
                'colors' => '15 Opções de Pedras Minerais',
                'stock_status' => 'in_stock',
                'price_cents' => 5200,
                'compare_at_price_cents' => 6500,
                'image_url' => '/assets/images/products/brac-cam-25-capa.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Caminhos Redondo 2.5cm'),
                'category_id' => 2,
                'category_name' => 'Braceletes',
                'variations_count' => 15,
                'min_price_cents' => 5200,
                'is_featured' => 1,
            ],
            [
                'id' => 7,
                'name' => 'Bracelete Elo Redondo 1,5cm',
                'slug' => 'brac-elo-15-bracelete-elo-redondo-1-5-cm',
                'description' => 'Design fluido com corrente de elos entrelaçados em metal prateado e medalha central de 1,5 cm com mineral genuíno.',
                'stone_mineral' => 'Orgonite & Gemas Minerais',
                'sku' => 'BRAC-ELO-15',
                'materials' => 'Corrente de Elos & Resina',
                'dimensions' => '1,5 cm medalha (corrente 18-20cm)',
                'colors' => '10 Opções de Pedras Minerais',
                'stock_status' => 'in_stock',
                'price_cents' => 4500,
                'compare_at_price_cents' => 5500,
                'image_url' => '/assets/images/products/brac-elo-15-capa.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Elo Redondo 1.5cm'),
                'category_id' => 2,
                'category_name' => 'Braceletes',
                'variations_count' => 10,
                'min_price_cents' => 4500,
                'is_featured' => 0,
            ],
            [
                'id' => 8,
                'name' => 'Bracelete Conexão Redondo 2cm',
                'slug' => 'brac-con-20-bracelete-conexao-redondo-2-cm',
                'description' => 'Design conceitual Conexão com aro ergonômico e mandala circular central de 2 cm em orgonite tradicional de alta concentração mineral.',
                'stone_mineral' => 'Orgonite Tradicional',
                'sku' => 'BRAC-CON-20',
                'materials' => 'Metal Prateado & Orgonite',
                'dimensions' => 'Aro ajustável, medalha de 2,0 cm',
                'colors' => 'Orgonite Tradicional',
                'stock_status' => 'in_stock',
                'price_cents' => 4800,
                'compare_at_price_cents' => 5800,
                'image_url' => '/assets/images/products/brac-con-20-capa.jpg',
                'external_url' => 'https://shopee.com.br/search?keyword=' . urlencode('Divinium Bracelete Conexao 2cm'),
                'category_id' => 2,
                'category_name' => 'Braceletes',
                'variations_count' => 1,
                'min_price_cents' => 4800,
                'is_featured' => 0,
            ],
        ];
    }
}
