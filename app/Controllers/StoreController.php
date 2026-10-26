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

            // Buscar todos os produtos ativos e agrupar estritamente por modelo autoral único
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

                $isParent = ($sku === $baseSku || $parent === '' || $parent === $sku);

                if (!isset($grouped[$baseSku])) {
                    $cleanName = preg_replace('/\s*-\s*[^-]+$/u', '', (string)$item['name']);
                    $item['clean_name'] = !empty($cleanName) ? $cleanName : $item['name'];
                    $item['variations_count'] = 1;
                    $item['min_price_cents'] = $item['price_cents'] ?? 3500;
                    $item['base_sku'] = $baseSku;
                    $grouped[$baseSku] = $item;
                } else {
                    $grouped[$baseSku]['variations_count']++;
                    if (!empty($item['price_cents']) && $item['price_cents'] < $grouped[$baseSku]['min_price_cents']) {
                        $grouped[$baseSku]['min_price_cents'] = $item['price_cents'];
                    }
                    if ($isParent) {
                        $cleanName = preg_replace('/\s*-\s*[^-]+$/u', '', (string)$item['name']);
                        $grouped[$baseSku]['id'] = $item['id'];
                        $grouped[$baseSku]['name'] = !empty($cleanName) ? $cleanName : $item['name'];
                        $grouped[$baseSku]['clean_name'] = $grouped[$baseSku]['name'];
                        if (!empty($item['slug'])) {
                            $grouped[$baseSku]['slug'] = $item['slug'];
                        }
                        if (!empty($item['image_url'])) {
                            $grouped[$baseSku]['image_url'] = $item['image_url'];
                        }
                    }
                }
            }

            if (!empty($grouped)) {
                $products = array_values($grouped);
                foreach ($products as &$pr) {
                    if (!empty($pr['clean_name'])) {
                        $pr['name'] = $pr['clean_name'];
                    }
                    if (!empty($pr['min_price_cents'])) {
                        $pr['price_cents'] = $pr['min_price_cents'];
                    }
                }
                unset($pr);
            }

            // Se houver menos de 8 modelos, mesclar modelos faltantes do catálogo autoral oficial
            if (count($products) < 8) {
                $defaults = self::getDefaultProducts();
                foreach ($defaults as $def) {
                    $defParts = explode('-', $def['sku']);
                    $defBase = count($defParts) >= 3 ? implode('-', array_slice($defParts, 0, 3)) : $def['sku'];
                    $found = false;
                    foreach ($products as $ep) {
                        $epSku = $ep['sku'] ?? '';
                        $epParts = explode('-', $epSku);
                        $epBase = count($epParts) >= 3 ? implode('-', array_slice($epParts, 0, 3)) : $epSku;
                        if ($epBase === $defBase) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $products[] = $def;
                    }
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

                // Identificar o SKU base do modelo autoral (grupo do produto)
                $rawModelSku = !empty($product['parent_sku']) ? $product['parent_sku'] : $product['sku'];
                $parts = explode('-', (string)$rawModelSku);
                $baseModelSku = count($parts) >= 3 ? implode('-', array_slice($parts, 0, 3)) : $rawModelSku;

                // Buscar canais de venda ativos e ordenados para o produto atual
                $linksStmt = $pdo->prepare('
                    SELECT * FROM product_links 
                    WHERE product_id = ? AND is_active = 1 
                    ORDER BY sort_order ASC, id ASC
                ');
                $linksStmt->execute([(int)$product['id']]);
                $links = $linksStmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];

                // Se a variação atual não tiver links próprios, herdar os canais do produto pai / modelo do grupo
                if (empty($links) && !empty($baseModelSku)) {
                    try {
                        $parentStmt = $pdo->prepare('
                            SELECT id FROM products 
                            WHERE (sku = :base_sku OR (parent_sku IS NULL OR parent_sku = "" OR parent_sku = sku)) 
                              AND (sku = :base_sku2 OR sku LIKE :prefix) 
                            ORDER BY id ASC LIMIT 1
                        ');
                        $parentStmt->execute([
                            ':base_sku' => $baseModelSku,
                            ':base_sku2' => $baseModelSku,
                            ':prefix' => $baseModelSku . '%'
                        ]);
                        $parentId = $parentStmt->fetchColumn();
                        if ($parentId && (int)$parentId !== (int)$product['id']) {
                            $parentLinksStmt = $pdo->prepare('
                                SELECT * FROM product_links 
                                WHERE product_id = ? AND is_active = 1 
                                ORDER BY sort_order ASC, id ASC
                            ');
                            $parentLinksStmt->execute([(int)$parentId]);
                            $links = $parentLinksStmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
                        }
                    } catch (\Throwable $pLinksEx) {}
                }

                // Se ainda não houver canais específicos, usar os canais centralizados da loja
                if (empty($links)) {
                    $configuredChannels = \App\Models\Setting::getProductChannelsList();
                    foreach ($configuredChannels as $idx => $cc) {
                        if (empty($cc['visible'])) continue;
                        $cUrl = trim($cc['url'] ?? '');
                        if (!empty($product['external_url']) && stripos($cc['platform'], 'shopee') !== false) {
                            $cUrl = $product['external_url'];
                        }
                        $links[] = [
                            'id' => $cc['id'] ?? $idx,
                            'product_id' => $product['id'],
                            'platform_name' => $cc['platform'],
                            'official_label' => $cc['official_label'] ?? 'Canal Homologado DIVINIUM',
                            'url' => $cUrl,
                            'price_cents' => !empty($cc['price_override']) ? (int)(str_replace([',', '.'], '', $cc['price_override'])) : ($product['price_cents'] ?? 38000),
                            'button_text' => !empty($cc['title']) ? $cc['title'] : ('Comprar na ' . $cc['platform']),
                            'button_color' => 'default',
                            'button_size' => 'md',
                            'badge' => $cc['badge'] ?? '',
                            'icon' => $cc['icon'] ?? 'oficial',
                            'desc' => $cc['desc'] ?? '',
                            'target' => $cc['target'] ?? '_blank',
                            'is_active' => 1,
                            'sort_order' => $idx
                        ];
                    }
                }

                // Se houver external_url cadastrada na joia mas nenhum canal Shopee nos links, garantir Shopee
                if (!empty($product['external_url'])) {
                    $hasShopee = false;
                    foreach ($links as &$lk) {
                        if (stripos($lk['platform_name'] ?? '', 'shopee') !== false) {
                            $hasShopee = true;
                            $lk['url'] = $product['external_url'];
                            break;
                        }
                    }
                    unset($lk);
                    if (!$hasShopee) {
                        $links[] = [
                            'id' => 0,
                            'product_id' => $product['id'],
                            'platform_name' => 'Shopee',
                            'official_label' => 'Canal Homologado DIVINIUM',
                            'url' => $product['external_url'],
                            'price_cents' => $product['price_cents'] ?? 38000,
                            'button_text' => 'Comprar na Shopee Oficial',
                            'button_color' => 'default',
                            'button_size' => 'md',
                            'badge' => 'Oficial',
                            'icon' => 'shopee',
                            'target' => '_blank',
                            'is_active' => 1,
                            'sort_order' => 0
                        ];
                    }
                }

                // 1. Carregar variações do banco de dados
                $dbVariations = [];
                try {
                    $varStmt = $pdo->prepare("
                        SELECT p.*, c.name as category_name 
                        FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.id 
                        WHERE (TRIM(p.sku) = :base_sku OR TRIM(p.parent_sku) = :base_sku OR TRIM(p.sku) LIKE :prefix)
                          AND (p.status = 'published' OR (p.status IS NULL AND p.is_active = 1))
                        ORDER BY p.sort_order ASC, p.id ASC
                    ");
                    $varStmt->execute([
                        ':base_sku' => $baseModelSku,
                        ':prefix'   => $baseModelSku . '-%'
                    ]);
                    $dbVariations = $varStmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
                } catch (\Throwable $varEx) {
                    // Silencioso
                }

                // 2. Carregar variações do catálogo oficial verificado (seed_products.json)
                $seedVariations = [];
                $seedFile = dirname(__DIR__, 2) . '/database/seed_products.json';
                if (file_exists($seedFile)) {
                    $seedContent = @file_get_contents($seedFile);
                    $seedJson = json_decode((string)$seedContent, true);
                    if (is_array($seedJson)) {
                        $items = isset($seedJson['value']) && is_array($seedJson['value']) ? $seedJson['value'] : $seedJson;
                        foreach ($items as $sp) {
                            $spSku = trim((string)($sp['sku'] ?? ''));
                            $spParent = trim((string)($sp['parent_sku'] ?? ''));
                            if ($spSku === $baseModelSku || $spParent === $baseModelSku || str_starts_with($spSku, $baseModelSku . '-')) {
                                $seedVariations[$spSku] = $sp;
                            }
                        }
                    }
                }

                // 3. Mesclar banco com o catálogo oficial, garantindo integridade das gemas e fotos
                $mergedVariations = [];
                foreach ($dbVariations as $dv) {
                    $s = trim((string)($dv['sku'] ?? ''));
                    if ($s !== '') {
                        $mergedVariations[$s] = $dv;
                    }
                }
                foreach ($seedVariations as $sSku => $sv) {
                    if (isset($mergedVariations[$sSku])) {
                        $currentImg = (string)($mergedVariations[$sSku]['image_url'] ?? '');
                        $seedImg = (string)($sv['image_url'] ?? '');
                        if ((empty($currentImg) || str_contains($currentImg, 'capa.jpg') || str_contains($currentImg, 'an-ov-40')) && !empty($seedImg) && !str_contains($seedImg, 'an-ov-40')) {
                            $mergedVariations[$sSku]['image_url'] = $seedImg;
                        }
                        if (empty($mergedVariations[$sSku]['stone_mineral']) && !empty($sv['stone_mineral'])) {
                            $mergedVariations[$sSku]['stone_mineral'] = $sv['stone_mineral'];
                        }
                        if (!isset($mergedVariations[$sSku]['has_stock']) && isset($sv['has_stock'])) {
                            $mergedVariations[$sSku]['has_stock'] = $sv['has_stock'];
                        }
                    } else {
                        $mergedVariations[$sSku] = $sv;
                    }
                }

                // 4. Filtrar o item base genérico se houver variações minerais reais
                $cleanVariations = [];
                foreach ($mergedVariations as $vSku => $vData) {
                    $vStone = trim((string)($vData['stone_mineral'] ?? ''));
                    $isGenericParent = ($vSku === $baseModelSku) && ($vStone === '' || $vStone === 'Orgonite Tradicional' || str_contains(mb_strtolower($vStone), 'tradicional'));
                    if ($isGenericParent && count($mergedVariations) > 1) {
                        continue;
                    }
                    $cleanVariations[] = $vData;
                }
                if (empty($cleanVariations)) {
                    $cleanVariations = array_values($mergedVariations);
                }

                // 5. Ordenação nobre das gemas autênticas do catálogo
                // Quartzo Verde, Madrepérola, Jaspe Vermelho, Ônix Negro e Ametista no topo
                $stoneOrderMap = [
                    'quartzo verde'   => 10,
                    'madrepérola'     => 20,
                    'madreperola'     => 20,
                    'jaspe vermelho'  => 30,
                    'coral'           => 30,
                    'ônix negro'      => 40,
                    'ônix'            => 40,
                    'onix'            => 40,
                    'ametista'        => 50,
                ];

                usort($cleanVariations, function($a, $b) use ($stoneOrderMap) {
                    $stoneA = mb_strtolower(trim((string)($a['stone_mineral'] ?? $a['name'] ?? '')));
                    $stoneB = mb_strtolower(trim((string)($b['stone_mineral'] ?? $b['name'] ?? '')));
                    
                    $rankA = 999;
                    foreach ($stoneOrderMap as $needle => $val) {
                        if (str_contains($stoneA, $needle)) {
                            $rankA = $val;
                            break;
                        }
                    }
                    $rankB = 999;
                    foreach ($stoneOrderMap as $needle => $val) {
                        if (str_contains($stoneB, $needle)) {
                            $rankB = $val;
                            break;
                        }
                    }
                    if ($rankA !== $rankB) {
                        return $rankA <=> $rankB;
                    }
                    return strcmp($stoneA, $stoneB);
                });

                // Formatar dados e preços de cada variação
                foreach ($cleanVariations as &$v) {
                    if (empty($v['image_url'])) {
                        $v['image_url'] = !empty($product['image_url']) ? $product['image_url'] : '/assets/images/products/brac-cam-20-capa.jpg';
                    }
                    if (empty($v['external_url'])) {
                        $v['external_url'] = 'https://shopee.com.br/search?keyword=' . urlencode('Divinium ' . ($v['name'] ?? ''));
                    }
                    $v['price_formatted'] = 'R$ ' . number_format(((int)($v['price_cents'] ?? ($product['price_cents'] ?? 3500))) / 100, 2, ',', '.');
                }
                unset($v);

                $variations = $cleanVariations;

                // 6. Resolução da Variante Inicial e Sincronização Perfeita com o Produto
                $targetVarSku = trim((string)($_GET['variante'] ?? ($_GET['sku'] ?? '')));
                $initialVariation = null;
                if ($targetVarSku !== '') {
                    foreach ($variations as $var) {
                        if (strcasecmp((string)$var['sku'], $targetVarSku) === 0) {
                            $initialVariation = $var;
                            break;
                        }
                    }
                }
                if (!$initialVariation) {
                    foreach ($variations as $var) {
                        if (!empty($var['slug']) && $var['slug'] === $rawSlug) {
                            $initialVariation = $var;
                            break;
                        }
                    }
                }
                if (!$initialVariation && !empty($variations[0])) {
                    $initialVariation = $variations[0];
                }

                if ($initialVariation) {
                    $selectedSku = $initialVariation['sku'];
                    $product['sku'] = $initialVariation['sku'];
                    if (!empty($initialVariation['stone_mineral'])) {
                        $product['stone_mineral'] = $initialVariation['stone_mineral'];
                    }
                    if (!empty($initialVariation['image_url'])) {
                        $product['image_url'] = $initialVariation['image_url'];
                    }
                    if (!empty($initialVariation['price_cents'])) {
                        $product['price_cents'] = $initialVariation['price_cents'];
                    }
                    if (isset($initialVariation['has_stock'])) {
                        $product['stock_status'] = $initialVariation['has_stock'] ? 'in_stock' : 'out_of_stock';
                    }
                    if (!empty($initialVariation['external_url'])) {
                        $product['external_url'] = $initialVariation['external_url'];
                    }
                }

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
                    LIMIT 8
                ";
                $relStmt = $pdo->prepare($relSql);
                $relStmt->execute([':cur_sku' => $baseModelSku]);
                $relRaw = $relStmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
                $relatedProducts = [];
                $seenRelBases = [];
                foreach ($relRaw as $rp) {
                    $rSku = (string)($rp['sku'] ?? '');
                    $rParts = explode('-', $rSku);
                    $rBase = count($rParts) >= 3 ? implode('-', array_slice($rParts, 0, 3)) : $rSku;
                    if ($rBase === $baseModelSku || isset($seenRelBases[$rBase])) {
                        continue;
                    }
                    $seenRelBases[$rBase] = true;
                    if (empty($rp['slug'])) {
                        $rp['slug'] = self::slugify((string)($rp['name'] ?? 'joia-' . $rp['id']));
                    }
                    $cleanRelName = preg_replace('/\s*-\s*[^-]+$/u', '', (string)$rp['name']);
                    $rp['name'] = !empty($cleanRelName) ? $cleanRelName : $rp['name'];
                    $relatedProducts[] = $rp;
                    if (count($relatedProducts) >= 4) {
                        break;
                    }
                }
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

            if ($product) {
                foreach ($defaults as $def) {
                    if ($def['id'] !== $product['id']) {
                        $relatedProducts[] = $def;
                    }
                }
            }
        }

        // Garantir que as variações sejam carregadas do seed_products.json se ainda não estiverem preenchidas
        if ($product && (empty($variations) || count($variations) <= 1)) {
            $rawModelSku = !empty($product['parent_sku']) ? $product['parent_sku'] : ($product['sku'] ?? '');
            $parts = explode('-', (string)$rawModelSku);
            $baseModelSku = count($parts) >= 3 ? implode('-', array_slice($parts, 0, 3)) : $rawModelSku;
            $seedFile = dirname(__DIR__, 2) . '/database/seed_products.json';
            if (file_exists($seedFile)) {
                $seedContent = @file_get_contents($seedFile);
                $seedJson = json_decode((string)$seedContent, true);
                if (is_array($seedJson)) {
                    $variations = [];
                    $items = isset($seedJson['value']) && is_array($seedJson['value']) ? $seedJson['value'] : $seedJson;
                    foreach ($items as $sp) {
                        $spSku = (string)($sp['sku'] ?? '');
                        $spParent = (string)($sp['parent_sku'] ?? '');
                        if ($spSku === $baseModelSku || $spParent === $baseModelSku || str_starts_with($spSku, $baseModelSku . '-')) {
                            $spStone = trim((string)($sp['stone_mineral'] ?? ''));
                            if ($spSku === $baseModelSku && ($spStone === '' || $spStone === 'Orgonite Tradicional')) {
                                continue;
                            }
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
                    if (!empty($variations)) {
                        $stoneOrderMap = [
                            'quartzo verde'   => 10,
                            'madrepérola'     => 20,
                            'madreperola'     => 20,
                            'jaspe vermelho'  => 30,
                            'coral'           => 30,
                            'ônix negro'      => 40,
                            'ônix'            => 40,
                            'onix'            => 40,
                            'ametista'        => 50,
                        ];
                        usort($variations, function($a, $b) use ($stoneOrderMap) {
                            $stoneA = mb_strtolower(trim((string)($a['stone_mineral'] ?? $a['name'] ?? '')));
                            $stoneB = mb_strtolower(trim((string)($b['stone_mineral'] ?? $b['name'] ?? '')));
                            $rankA = 999;
                            foreach ($stoneOrderMap as $needle => $val) {
                                if (str_contains($stoneA, $needle)) { $rankA = $val; break; }
                            }
                            $rankB = 999;
                            foreach ($stoneOrderMap as $needle => $val) {
                                if (str_contains($stoneB, $needle)) { $rankB = $val; break; }
                            }
                            if ($rankA !== $rankB) { return $rankA <=> $rankB; }
                            return strcmp($stoneA, $stoneB);
                        });
                        $initialVariation = $variations[0];
                        $selectedSku = $initialVariation['sku'];
                        $product['sku'] = $initialVariation['sku'];
                        $product['stone_mineral'] = $initialVariation['stone_mineral'];
                        $product['image_url'] = $initialVariation['image_url'];
                        $product['price_cents'] = $initialVariation['price_cents'];
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

    /**
     * Página de Finalização de Compra / Checkout Seguro
     */
    public function checkout(): void
    {
        if (\App\Models\Setting::get('checkout_direct_cart_enabled', '1') !== '1') {
            header('Location: /loja');
            exit;
        }

        View::render('store/checkout', [
            'pageTitle' => 'Finalizar Escolhas | Sacola Divinium',
            'pageDescription' => 'Conclua seu pedido com atendimento exclusivo, Pix instantâneo com 5% de desconto ou canais homologados.'
        ]);
    }

    /**
     * Páginas Institucionais & Políticas da Joalheria
     *
     * @param array<string, string> $params
     */
    public function institutionalPage(array $params = []): void
    {
        if (\App\Models\Setting::get('institutional_pages_content_enabled', '1') !== '1') {
            header('Location: /');
            exit;
        }

        $uri = trim((string)($_SERVER['REQUEST_URI'] ?? ''), '/');
        $parts = explode('?', $uri)[0];
        $slug = $params['slug'] ?? $parts;

        $pageMap = [
            'trocas-e-devolucoes' => [
                'title' => \App\Models\Setting::get('page_trocas_title', \App\Models\Setting::get('page_returns_title', 'Política de Trocas e Devoluções')),
                'desc' => 'Conforme o Código de Defesa do Consumidor (Art. 49), garantimos seu direito de arrependimento em até 7 dias corridos.',
                'content_key' => 'page_trocas_content',
                'default_content' => "<h3>Compromisso de Excelência e Confiança DIVINIUM</h3><p>Todas as peças DIVINIUM são concebidas sob rigorosos padrões de alta joalheria. Caso deseje realizar uma troca ou devolução, seguimos integralmente as disposições da Lei nº 8.078/1990 (Código de Defesa do Consumidor) e Decreto nº 7.962/2013.</p><h4>1. Direito de Arrependimento (CDC Art. 49)</h4><p>Você possui até <strong>7 (sete) dias corridos</strong> a contar da data de recebimento do pedido para solicitar a devolução com reembolso integral ou troca por outra gema mineral.</p><h4>2. Condições da Peça</h4><p>A joia deverá ser devolvida em sua embalagem de veludo original, acompanhada do Certificado de Garantia e sem sinais de uso indevido ou avarias mecânicas.</p><h4>3. Como Solicitar</h4><p>Entre em contato direto com o nosso <strong>Concierge VIP</strong> pelo WhatsApp oficial informado no rodapé ou pelo e-mail <strong>atendimento@divinium.com.br</strong> para receber o código postal de postagem reversa cortesia com seguro total de transporte.</p>"
            ],
            'garantia-e-cuidados' => [
                'title' => \App\Models\Setting::get('page_garantia_title', \App\Models\Setting::get('page_warranty_title', 'Certificado de Garantia & Cuidados')),
                'desc' => 'Conheça o compromisso de qualidade do banho de prata, gemas naturais e as orientações para conservação.',
                'content_key' => 'page_garantia_content',
                'default_content' => "<h3>Qualidade & Nobreza dos Materiais</h3><p>A DIVINIUM assegura a excelência de cada joia autoral. Nossas peças recebem acabamento nobre com Banho de Prata de alta durabilidade e as mandalas abrigam gemas minerais 100% autênticas envoltas em matriz de resina cristalina nobre.</p><h4>1. Garantia do Banho de Prata</h4><p>Garantimos a qualidade do banho de prata empregado em todas as nossas criações contra defeitos originários de manufatura.</p><h4>2. Cuidados com a sua Joia</h4><ul><li>Evite contato direto com produtos químicos abrasivos, alvejantes, cloro de piscina ou perfumes aplicados diretamente sobre as peças.</li><li>Para higienização, utilize apenas flanela mágica de polimento ou água morna com sabão neutro, secando com pano macio e suave.</li><li>Armazene suas joias individualmente na embalagem aveludada original DIVINIUM para prevenir atritos entre metais.</li></ul>"
            ],
            'politica-de-privacidade' => [
                'title' => \App\Models\Setting::get('page_privacidade_title', \App\Models\Setting::get('page_privacy_title', 'Política de Privacidade & Proteção de Dados (LGPD)')),
                'desc' => 'Nosso compromisso de total transparência e segurança no tratamento de seus dados pessoais (Lei nº 13.709/2018).',
                'content_key' => 'page_privacidade_content',
                'default_content' => "<h3>Privacidade e Respeito aos Seus Dados</h3><p>A DIVINIUM JOIAS valoriza a privacidade e a segurança das informações de nossos clientes. Esta Política está em total conformidade com a <strong>Lei Geral de Proteção de Dados Pessoais (Lei nº 13.709/2018 - LGPD)</strong>.</p><h4>1. Coleta de Informações</h4><p>Coletamos apenas dados estritamente necessários para viabilizar o processamento seguro de seus pedidos, cálculo de frete, emissão de nota fiscal e comunicação sobre suas joias.</p><h4>2. Segurança e Sigilo</h4><p>Não compartilhamos, comercializamos ou alugamos dados cadastrais a terceiros. Todo o tráfego é criptografado por protocolo SSL/TLS de 256 bits.</p><h4>3. Seus Direitos</h4><p>Você pode a qualquer momento consultar, retificar ou solicitar a eliminação dos seus dados entrando em contato com nosso Encarregado de Proteção de Dados (DPO) através de dpo@divinium.com.br.</p>"
            ],
            'termos-de-uso' => [
                'title' => \App\Models\Setting::get('page_termos_title', \App\Models\Setting::get('page_terms_title', 'Termos de Uso & Condições Gerais')),
                'desc' => 'Termos de navegação, propriedade intelectual e aquisição de joias autorais DIVINIUM.',
                'content_key' => 'page_termos_content',
                'default_content' => "<h3>Termos de Uso do Site DIVINIUM</h3><p>Bem-vindo à plataforma digital oficial da DIVINIUM. Ao navegar neste portal ou adquirir nossas peças, você expressa sua plena concordância com as condições aqui estabelecidas.</p><h4>1. Propriedade Intelectual</h4><p>Todo o design autoral das joias, fotografias, logotipos, textos, marcas e identidade visual são propriedade exclusiva da DIVINIUM JOIAS, protegidos pela Lei de Direitos Autorais (Lei nº 9.610/1998) e Lei de Propriedade Industrial (Lei nº 9.279/1996).</p><h4>2. Exatidão das Informações</h4><p>As fotografias reproduzem fielmente os modelos. Por se tratarem de gemas minerais autênticas extraídas da natureza, pequenas nuances de cor e grafismos naturais nas conchas e pedras comprovam a singularidade e exclusividade de cada joia.</p>"
            ],
            'guia-de-medidas' => [
                'title' => \App\Models\Setting::get('page_size_guide_title', 'Guia de Medidas & Tamanhos de Anéis'),
                'desc' => 'Aprenda a identificar a medida exata do seu aro para um ajuste perfeito e confortável.',
                'content_key' => 'policy_size_guide_content',
                'default_content' => "<h3>Como Descobrir o Tamanho do Seu Anel</h3><p>Para assegurar que seu anel DIVINIUM se encaixe perfeitamente com toda a elegância e conforto, utilize o método prático do diâmetro interno.</p><h4>Método do Diâmetro Interno</h4><ol><li>Selecione um anel ou aliança que sirva perfeitamente no dedo desejado.</li><li>Com uma régua milimetrada, meça a distância interna (linha reta de borda a borda interna), desconsiderando a espessura do aro.</li><li>Consulte a tabela de equivalências:<ul><li>15,5 mm = Aro 12</li><li>16,2 mm = Aro 14</li><li>16,8 mm = Aro 16</li><li>17,5 mm = Aro 18</li><li>18,2 mm = Aro 20</li><li>18,8 mm = Aro 22</li><li>19,5 mm = Aro 24</li></ul></li></ol><p>Em caso de dúvidas entre dois números, recomendamos a escolha do tamanho maior para máximo conforto durante todas as estações do ano.</p>"
            ],
            'dpo' => [
                'title' => \App\Models\Setting::get('page_dpo_title', 'Encarregado de Proteção de Dados (DPO)'),
                'desc' => 'Canal direto de atendimento para solicitações e direitos previstos pela LGPD.',
                'content_key' => 'policy_dpo_content',
                'default_content' => "<h3>Canal do Encarregado de Dados (DPO)</h3><p>Em atendimento ao Artigo 41 da Lei Federal nº 13.709/2018 (LGPD), a DIVINIUM disponibiliza este canal oficial de comunicação direto entre os titulares de dados, a empresa e a Autoridade Nacional de Proteção de Dados (ANPD).</p><h4>Encarregado Oficial:</h4><p><strong>Comitê de Privacidade & Governança de Dados</strong><br>E-mail: <strong>dpo@divinium.com.br</strong><br>Horário de Atendimento: Segunda a Sexta, das 09h às 18h (Horário de Brasília)</p><p>As solicitações de consulta, retificação ou exclusão de dados serão respondidas dentro dos prazos legais estipulados pela legislação vigente.</p>"
            ]
        ];

        $page = $pageMap[$slug] ?? null;
        if (!$page) {
            $customTitle = \App\Models\Setting::get('custom_page_' . $slug . '_title');
            if ($customTitle) {
                $page = [
                    'title' => $customTitle,
                    'desc' => \App\Models\Setting::get('custom_page_' . $slug . '_desc', 'Página institucional DIVINIUM'),
                    'content_key' => 'custom_page_' . $slug . '_content',
                    'default_content' => '<p>Conteúdo em atualização.</p>'
                ];
            } else {
                header('Location: /');
                exit;
            }
        }

        $bodyContent = \App\Models\Setting::get($page['content_key'], $page['default_content']);

        View::render('home/page', [
            'pageTitle' => $page['title'],
            'pageDescription' => $page['desc'],
            'pageHeading' => $page['title'],
            'pageBodyHtml' => $bodyContent,
            'pageSlug' => $slug
        ]);
    }
}

