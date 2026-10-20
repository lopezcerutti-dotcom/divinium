<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use Throwable;

/**
 * Model de Coleções & Lançamentos da DIVINIUM
 * Gerencia coleções organizadas por categorias (Braceletes, Colares, Anéis, etc.),
 * com suporte a títulos, descrições, imagens, botões, visibilidade e ordenação.
 */
class Collection
{
    private const SETTING_KEY = 'lancamentos_collections_data';

    /**
     * Retorna todas as coleções cadastradas, ordenadas por sort_order.
     * Se ainda não existirem coleções salvas, inicializa com o acervo autoral padrão
     * aproveitando os produtos e dados existentes sem criar duplicatas.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getAll(): array
    {
        $raw = Setting::get(self::SETTING_KEY);
        if (!empty($raw)) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded) && !empty($decoded)) {
                // Ordenar por sort_order
                usort($decoded, function ($a, $b) {
                    return ((int) ($a['sort_order'] ?? 0)) <=> ((int) ($b['sort_order'] ?? 0));
                });
                return $decoded;
            }
        }

        // Tenta buscar da tabela collections caso exista
        $fromDb = self::loadFromDatabaseTable();
        if (!empty($fromDb)) {
            self::saveAll($fromDb);
            return $fromDb;
        }

        // Se vazio, cria a estrutura inicial padrão preservando os produtos existentes
        $defaults = self::getDefaultCollections();
        self::saveAll($defaults);
        return $defaults;
    }

    /**
     * Retorna apenas as coleções ativas (is_active = 1)
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getActive(): array
    {
        $all = self::getAll();
        return array_values(array_filter($all, function ($col) {
            return !empty($col['is_active']);
        }));
    }

    /**
     * Retorna as coleções agrupadas por slug da categoria
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    public static function getGroupedByCategory(): array
    {
        $all = self::getAll();
        $grouped = [];
        foreach ($all as $col) {
            $catSlug = !empty($col['category_slug']) ? (string)$col['category_slug'] : 'outras';
            $grouped[$catSlug][] = $col;
        }
        return $grouped;
    }

    /**
     * Busca uma coleção pelo seu ID
     *
     * @param int $id
     * @return array<string, mixed>|null
     */
    public static function find(int $id): ?array
    {
        $all = self::getAll();
        foreach ($all as $col) {
            if ((int) ($col['id'] ?? 0) === $id) {
                return $col;
            }
        }
        return null;
    }

    /**
     * Salva ou atualiza uma coleção
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed> Coleção salva
     */
    public static function save(array $data): array
    {
        $all = self::getAll();
        $id = (int) ($data['id'] ?? 0);
        $isNew = $id <= 0;

        if ($isNew) {
            $maxId = 0;
            foreach ($all as $col) {
                if ((int) ($col['id'] ?? 0) > $maxId) {
                    $maxId = (int) $col['id'];
                }
            }
            $id = $maxId + 1;
        }

        $name = trim((string) ($data['name'] ?? 'Nova Coleção'));
        $slug = trim((string) ($data['slug'] ?? ''));
        if ($slug === '') {
            $slug = self::slugify($name);
        }

        $catName = trim((string) ($data['category_name'] ?? 'Braceletes'));
        $catSlug = trim((string) ($data['category_slug'] ?? self::slugify($catName)));

        $collection = [
            'id' => $id,
            'category_id' => (int) ($data['category_id'] ?? 1),
            'category_name' => $catName,
            'category_slug' => $catSlug,
            'name' => $name,
            'slug' => $slug,
            'title' => trim((string) ($data['title'] ?? $name)),
            'subtitle' => trim((string) ($data['subtitle'] ?? '')),
            'description' => trim((string) ($data['description'] ?? '')),
            'promo_text' => trim((string) ($data['promo_text'] ?? '')),
            'badge' => trim((string) ($data['badge'] ?? 'LANÇAMENTO EXCLUSIVO')),
            'image_url' => trim((string) ($data['image_url'] ?? '/assets/images/products/colecao-completa-espelho.jpg')),
            'image_alt' => trim((string) ($data['image_alt'] ?? $name)),
            'button_text' => trim((string) ($data['button_text'] ?? 'Explorar Coleção')),
            'button_url' => trim((string) ($data['button_url'] ?? '/loja')),
            'button_target' => in_array($data['button_target'] ?? '', ['_blank', '_self'], true) ? $data['button_target'] : '_self',
            'button_style' => trim((string) ($data['button_style'] ?? 'gold')),
            'button_secondary_text' => trim((string) ($data['button_secondary_text'] ?? '')),
            'button_secondary_url' => trim((string) ($data['button_secondary_url'] ?? '')),
            'sort_order' => (int) ($data['sort_order'] ?? count($all) + 1),
            'is_active' => isset($data['is_active']) ? (int) $data['is_active'] : 1,
            'products' => is_array($data['products'] ?? null) ? $data['products'] : [],
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Atualizar ou inserir
        $foundIndex = -1;
        foreach ($all as $idx => $c) {
            if ((int) ($c['id'] ?? 0) === $id) {
                $foundIndex = $idx;
                break;
            }
        }

        if ($foundIndex >= 0) {
            $all[$foundIndex] = array_merge($all[$foundIndex], $collection);
        } else {
            $all[] = $collection;
        }

        self::saveAll($all);
        self::syncToDatabaseTable($collection);

        return $collection;
    }

    /**
     * Exclui uma coleção pelo ID (sem apagar os produtos associados no catálogo)
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $all = self::getAll();
        $filtered = array_values(array_filter($all, function ($col) use ($id) {
            return (int) ($col['id'] ?? 0) !== $id;
        }));

        self::saveAll($filtered);

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare('DELETE FROM collections WHERE id = ?');
            $stmt->execute([$id]);
        } catch (Throwable $e) {
            // Ignora silenciosamente se tabela não existir
        }

        return true;
    }

    /**
     * Alterna a visibilidade (mostrar/ocultar) de uma coleção
     *
     * @param int $id
     * @return bool Novo estado de visibilidade (true = ativo / false = inativo)
     */
    public static function toggleVisibility(int $id): bool
    {
        $all = self::getAll();
        $newState = false;

        foreach ($all as &$col) {
            if ((int) ($col['id'] ?? 0) === $id) {
                $col['is_active'] = empty($col['is_active']) ? 1 : 0;
                $newState = (bool) $col['is_active'];
                break;
            }
        }
        unset($col);

        self::saveAll($all);
        return $newState;
    }

    /**
     * Alterna a visibilidade de um produto específico dentro de uma coleção
     */
    public static function toggleProductVisibility(int $collectionId, int $productId): bool
    {
        $all = self::getAll();
        $newState = false;

        foreach ($all as &$col) {
            if ((int) ($col['id'] ?? 0) === $collectionId && !empty($col['products'])) {
                foreach ($col['products'] as &$prod) {
                    if ((int) ($prod['id'] ?? 0) === $productId) {
                        $prod['is_active'] = empty($prod['is_active']) ? 1 : 0;
                        $newState = (bool) $prod['is_active'];
                        break;
                    }
                }
                unset($prod);
                break;
            }
        }
        unset($col);

        self::saveAll($all);
        return $newState;
    }

    /**
     * Reordena as coleções com base em um array ordenado de IDs
     *
     * @param array<int> $orderedIds
     * @return bool
     */
    public static function reorder(array $orderedIds): bool
    {
        $all = self::getAll();
        $map = [];
        foreach ($all as $c) {
            $map[(int) $c['id']] = $c;
        }

        $newOrder = [];
        $order = 1;
        foreach ($orderedIds as $id) {
            $intId = (int) $id;
            if (isset($map[$intId])) {
                $item = $map[$intId];
                $item['sort_order'] = $order++;
                $newOrder[] = $item;
                unset($map[$intId]);
            }
        }

        // Itens que não estavam no array recebido mantêm-se no final
        foreach ($map as $rem) {
            $rem['sort_order'] = $order++;
            $newOrder[] = $rem;
        }

        self::saveAll($newOrder);
        return true;
    }

    /**
     * Persiste a lista completa de coleções
     *
     * @param array<int, array<string, mixed>> $collections
     */
    public static function saveAll(array $collections): bool
    {
        return Setting::set(self::SETTING_KEY, json_encode($collections, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    /**
     * Retorna a lista de categorias disponíveis para coleções
     * (Braceletes, Colares, Anéis + qualquer categoria cadastrada na tabela categories)
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getCategories(): array
    {
        $categories = [];
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query('SELECT id, name, slug, description, sort_order, is_active FROM categories ORDER BY sort_order ASC, name ASC');
            if ($stmt) {
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            }
        } catch (Throwable $e) {
            // fallback
        }

        // Garante a existência das 3 categorias essenciais requeridas
        $required = [
            ['name' => 'Braceletes', 'slug' => 'braceletes', 'description' => 'Aros duplos esculturais e joias de pulso em prata 925.'],
            ['name' => 'Colares', 'slug' => 'colares', 'description' => 'Correntes e pingentes elegantes com gemas minerais raras.'],
            ['name' => 'Anéis', 'slug' => 'aneis', 'description' => 'Joias esculturais de design envolvente em prata de lei.']
        ];

        $existingSlugs = array_map(function ($c) {
            return $c['slug'] ?? '';
        }, $categories);

        $sortIndex = count($categories) + 1;
        foreach ($required as $req) {
            if (!in_array($req['slug'], $existingSlugs, true)) {
                // Tenta inserir no banco
                try {
                    $pdo = Database::getConnection();
                    $ins = $pdo->prepare('INSERT INTO categories (uuid, name, slug, description, sort_order, is_active) VALUES (?, ?, ?, ?, ?, 1)');
                    $ins->execute([
                        bin2hex(random_bytes(16)),
                        $req['name'],
                        $req['slug'],
                        $req['description'],
                        $sortIndex++
                    ]);
                    $newId = (int) $pdo->lastInsertId();
                    $categories[] = [
                        'id' => $newId > 0 ? $newId : count($categories) + 1,
                        'name' => $req['name'],
                        'slug' => $req['slug'],
                        'description' => $req['description'],
                        'sort_order' => $sortIndex,
                        'is_active' => 1
                    ];
                } catch (Throwable $e) {
                    $categories[] = [
                        'id' => count($categories) + 1,
                        'name' => $req['name'],
                        'slug' => $req['slug'],
                        'description' => $req['description'],
                        'sort_order' => $sortIndex,
                        'is_active' => 1
                    ];
                }
            }
        }

        return $categories;
    }

    /**
     * Cria ou adiciona uma nova categoria dinamicamente
     */
    public static function createCategory(string $name, ?string $description = null): array
    {
        $name = trim($name);
        $slug = self::slugify($name);
        $desc = trim((string)$description);

        $pdo = Database::getConnection();
        $uuid = bin2hex(random_bytes(16));

        $stmt = $pdo->prepare('INSERT INTO categories (uuid, name, slug, description, sort_order, is_active) VALUES (?, ?, ?, ?, 99, 1)');
        $stmt->execute([$uuid, $name, $slug, $desc]);
        $id = (int) $pdo->lastInsertId();

        return [
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
            'description' => $desc,
            'is_active' => 1
        ];
    }

    /**
     * Tenta carregar coleções da tabela SQL collections se existir
     *
     * @return array<int, array<string, mixed>>
     */
    private static function loadFromDatabaseTable(): array
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query('SELECT * FROM collections ORDER BY sort_order ASC, id ASC');
            if ($stmt) {
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($rows)) {
                    foreach ($rows as &$r) {
                        if (!empty($r['products_json'])) {
                            $r['products'] = json_decode($r['products_json'], true) ?: [];
                        } else {
                            $r['products'] = [];
                        }
                    }
                    unset($r);
                    return $rows;
                }
            }
        } catch (Throwable $e) {
            // Tabela pode não existir
        }
        return [];
    }

    /**
     * Sincroniza a coleção para a tabela SQL se ela existir
     */
    private static function syncToDatabaseTable(array $col): void
    {
        try {
            $pdo = Database::getConnection();
            // Garante existência da tabela
            $pdo->exec("CREATE TABLE IF NOT EXISTS `collections` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `category_id` INT UNSIGNED NULL,
                `category_name` VARCHAR(100) NULL,
                `category_slug` VARCHAR(120) NULL,
                `name` VARCHAR(150) NOT NULL,
                `slug` VARCHAR(160) NOT NULL,
                `title` VARCHAR(255) NULL,
                `subtitle` VARCHAR(255) NULL,
                `description` TEXT NULL,
                `promo_text` VARCHAR(255) NULL,
                `badge` VARCHAR(100) NULL,
                `image_url` VARCHAR(500) NULL,
                `image_alt` VARCHAR(255) NULL,
                `button_text` VARCHAR(100) NULL,
                `button_url` VARCHAR(500) NULL,
                `button_target` VARCHAR(20) NOT NULL DEFAULT '_self',
                `button_style` VARCHAR(50) NOT NULL DEFAULT 'gold',
                `button_secondary_text` VARCHAR(100) NULL,
                `button_secondary_url` VARCHAR(500) NULL,
                `products_json` TEXT NULL,
                `sort_order` INT NOT NULL DEFAULT 0,
                `is_active` TINYINT(1) NOT NULL DEFAULT 1,
                `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

            $sql = "INSERT INTO `collections` 
                (`id`, `category_id`, `category_name`, `category_slug`, `name`, `slug`, `title`, `subtitle`, `description`, `promo_text`, `badge`, `image_url`, `image_alt`, `button_text`, `button_url`, `button_target`, `button_style`, `button_secondary_text`, `button_secondary_url`, `products_json`, `sort_order`, `is_active`)
                VALUES (:id, :category_id, :category_name, :category_slug, :name, :slug, :title, :subtitle, :description, :promo_text, :badge, :image_url, :image_alt, :button_text, :button_url, :button_target, :button_style, :button_secondary_text, :button_secondary_url, :products_json, :sort_order, :is_active)
                ON DUPLICATE KEY UPDATE 
                `category_id` = VALUES(`category_id`),
                `category_name` = VALUES(`category_name`),
                `category_slug` = VALUES(`category_slug`),
                `name` = VALUES(`name`),
                `slug` = VALUES(`slug`),
                `title` = VALUES(`title`),
                `subtitle` = VALUES(`subtitle`),
                `description` = VALUES(`description`),
                `promo_text` = VALUES(`promo_text`),
                `badge` = VALUES(`badge`),
                `image_url` = VALUES(`image_url`),
                `image_alt` = VALUES(`image_alt`),
                `button_text` = VALUES(`button_text`),
                `button_url` = VALUES(`button_url`),
                `button_target` = VALUES(`button_target`),
                `button_style` = VALUES(`button_style`),
                `button_secondary_text` = VALUES(`button_secondary_text`),
                `button_secondary_url` = VALUES(`button_secondary_url`),
                `products_json` = VALUES(`products_json`),
                `sort_order` = VALUES(`sort_order`),
                `is_active` = VALUES(`is_active`)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id' => $col['id'],
                ':category_id' => $col['category_id'] ?? 1,
                ':category_name' => $col['category_name'] ?? 'Braceletes',
                ':category_slug' => $col['category_slug'] ?? 'braceletes',
                ':name' => $col['name'],
                ':slug' => $col['slug'],
                ':title' => $col['title'] ?? $col['name'],
                ':subtitle' => $col['subtitle'] ?? '',
                ':description' => $col['description'] ?? '',
                ':promo_text' => $col['promo_text'] ?? '',
                ':badge' => $col['badge'] ?? '',
                ':image_url' => $col['image_url'] ?? '',
                ':image_alt' => $col['image_alt'] ?? '',
                ':button_text' => $col['button_text'] ?? 'Explorar Coleção',
                ':button_url' => $col['button_url'] ?? '/loja',
                ':button_target' => $col['button_target'] ?? '_self',
                ':button_style' => $col['button_style'] ?? 'gold',
                ':button_secondary_text' => $col['button_secondary_text'] ?? '',
                ':button_secondary_url' => $col['button_secondary_url'] ?? '',
                ':products_json' => json_encode($col['products'] ?? [], JSON_UNESCAPED_UNICODE),
                ':sort_order' => $col['sort_order'] ?? 0,
                ':is_active' => $col['is_active'] ?? 1,
            ]);
        } catch (Throwable $e) {
            // Ignora silenciosamente, Setting já garantiu a persistência
        }
    }

    /**
     * Retorna a estrutura inicial de coleções padrão para o quiet luxury da Divinium,
     * reaproveitando os braceletes e fotos existentes.
     *
     * @return array<int, array<string, mixed>>
     */
    private static function getDefaultCollections(): array
    {
        return [
            [
                'id' => 1,
                'category_id' => 1,
                'category_name' => 'Braceletes',
                'category_slug' => 'braceletes',
                'name' => 'Coleção Caminhos',
                'slug' => 'colecao-caminhos',
                'title' => 'Braceletes CAMINHOS • Harmonia Mineral',
                'subtitle' => 'Seis caminhos, seis vibrações. Esculpidos em aro duplo de prata de lei 925 com gemas minerais nobres.',
                'description' => 'A Coleção Caminhos expressa a harmonia entre as formas geométricas contemporâneas e a pulsação viva da natureza. Peças assinadas com aro anatômico duplo e lapidação manual.',
                'promo_text' => 'Edição Autoral Limitada • Frete Cortesia para todo o Brasil',
                'badge' => 'LANÇAMENTO EXCLUSIVO',
                'image_url' => '/assets/images/products/colecao-completa-espelho.jpg',
                'image_alt' => 'Coleção Braceletes Caminhos Divinium no Espelho',
                'button_text' => 'Explorar Braceletes',
                'button_url' => '/loja',
                'button_target' => '_self',
                'button_style' => 'gold',
                'button_secondary_text' => 'Atendimento Concierge',
                'button_secondary_url' => 'https://wa.me/5521965683841',
                'sort_order' => 1,
                'is_active' => 1,
                'products' => [
                    [
                        'id' => 1,
                        'name' => 'Bracelete Caminhos • Quartzo Verde',
                        'price_cents' => 38000,
                        'image_url' => '/assets/images/products/bracelete-verde.jpg',
                        'badge' => 'PRATA & QUARTZO VERDE',
                        'is_active' => 1,
                        'sort_order' => 1,
                        'url' => '/loja'
                    ],
                    [
                        'id' => 2,
                        'name' => 'Bracelete Caminhos • Madrepérola',
                        'price_cents' => 38000,
                        'image_url' => '/assets/images/products/bracelete-madreperola.jpg',
                        'badge' => 'PRATA & MADREPÉROLA',
                        'is_active' => 1,
                        'sort_order' => 2,
                        'url' => '/loja'
                    ],
                    [
                        'id' => 3,
                        'name' => 'Bracelete Caminhos • Jaspe Vermelho',
                        'price_cents' => 38000,
                        'image_url' => '/assets/images/products/bracelete-vermelho.jpg',
                        'badge' => 'PRATA & JASPE VERMELHO',
                        'is_active' => 1,
                        'sort_order' => 3,
                        'url' => '/loja'
                    ],
                    [
                        'id' => 4,
                        'name' => 'Bracelete Caminhos • Ônix Preto',
                        'price_cents' => 38000,
                        'image_url' => '/assets/images/products/bracelete-onix.jpg',
                        'badge' => 'PRATA & ÔNIX PRETO',
                        'is_active' => 1,
                        'sort_order' => 4,
                        'url' => '/loja'
                    ]
                ]
            ],
            [
                'id' => 2,
                'category_id' => 2,
                'category_name' => 'Colares',
                'category_slug' => 'colares',
                'name' => 'Coleção Fios de Luz',
                'slug' => 'colecao-fios-de-luz',
                'title' => 'Colares FIOS DE LUZ • Fluidez & Brilho Mineral',
                'subtitle' => 'Correntes delicadas em prata de lei 925 com pingentes facetados em pedras preciosas brasileiras.',
                'description' => 'A leveza das correntes dialoga com a intensidade dos pingentes minerais em uma composição minimalista que valoriza o colo com sofisticação atemporal.',
                'promo_text' => 'Alta Joalheria Autoral • Certificado de Garantia Vitalícia da Prata',
                'badge' => 'NOVA COLEÇÃO',
                'image_url' => '/assets/images/products/bracelete-madreperola.jpeg',
                'image_alt' => 'Colares Coleção Fios de Luz Divinium',
                'button_text' => 'Ver Colares',
                'button_url' => '/loja',
                'button_target' => '_self',
                'button_style' => 'gold',
                'button_secondary_text' => '',
                'button_secondary_url' => '',
                'sort_order' => 2,
                'is_active' => 1,
                'products' => []
            ],
            [
                'id' => 3,
                'category_id' => 3,
                'category_name' => 'Anéis',
                'category_slug' => 'aneis',
                'name' => 'Coleção Aura Mineral',
                'slug' => 'colecao-aura-mineral',
                'title' => 'Anéis AURA • Escultura & Design Orgânico',
                'subtitle' => 'Anéis com presença marcante e conforto absoluto. Geometria fluida que abraça os dedos com nobreza.',
                'description' => 'Peças contemporâneas inspiradas na força telúrica das formações minerais. Acabamento polido espelhado e pedras selecionadas uma a uma.',
                'promo_text' => 'Design Exclusivo Patenteado • Joias Numeradas',
                'badge' => 'COLEÇÃO ASSINADA',
                'image_url' => '/assets/images/products/bracelete-onix.png',
                'image_alt' => 'Anéis Coleção Aura Mineral Divinium',
                'button_text' => 'Ver Anéis',
                'button_url' => '/loja',
                'button_target' => '_self',
                'button_style' => 'gold',
                'button_secondary_text' => '',
                'button_secondary_url' => '',
                'sort_order' => 3,
                'is_active' => 1,
                'products' => []
            ]
        ];
    }

    /**
     * Helper para gerar slugs amigáveis
     */
    private static function slugify(string $text): string
    {
        $text = mb_strtolower(trim($text), 'UTF-8');
        $text = (string) preg_replace('/[áàâãä]/u', 'a', $text);
        $text = (string) preg_replace('/[éèêë]/u', 'e', $text);
        $text = (string) preg_replace('/[íìîï]/u', 'i', $text);
        $text = (string) preg_replace('/[óòôõö]/u', 'o', $text);
        $text = (string) preg_replace('/[úùûü]/u', 'u', $text);
        $text = (string) preg_replace('/[ç]/u', 'c', $text);
        $text = (string) preg_replace('/[^a-z0-9\-]/u', '-', $text);
        $text = (string) preg_replace('/\-+/', '-', $text);
        return trim($text, '-');
    }
}
