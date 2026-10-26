<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

/**
 * Model para gerenciamento de configurações e conteúdos dinâmicos do site.
 * Armazena e recupera textos, títulos, valores e parâmetros da landing page.
 */
class Setting
{
    /** @var array<string, string|null>|null Cache em memória durante o ciclo da requisição */
    private static ?array $cache = null;

    /**
     * Carrega todas as configurações do banco para a memória em uma única consulta.
     */
    public static function loadAll(): void
    {
        if (self::$cache !== null) {
            return;
        }

        self::$cache = [];

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query('SELECT key_name, value FROM settings');
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                self::$cache[$row['key_name']] = $row['value'];
            }
        } catch (\Throwable $e) {
            // Em caso de falha de conexão ou tabela ausente, fallback seguro
            error_log('Erro ao carregar configurações: ' . $e->getMessage());
        }
    }

    /**
     * Normaliza e atualiza configurações legadas para a nova identidade visual
     * DIVINIUM (Branco perolado, Preto suave, Prata fosca, Cinza-prata e Grafite).
     */
    private static function sanitizeLegacySetting(string $key, ?string $val): ?string
    {
        if ($val === null) {
            return null;
        }

        $trimmed = trim($val);
        $upper = strtoupper($trimmed);

        if ($key === 'default_theme') {
            if (in_array($trimmed, ['theme-gold', 'theme-silver', 'theme-emerald', 'theme-rose'], true)) {
                return 'theme-silver-noir';
            }
            return $trimmed;
        }

        // Se for cor legada em tons dourados, amarelos, champagnes, bronzes, rose gold ou verdes
        $legacyGoldsAndGreens = [
            '#CAA15C', '#D4BA88', '#EBD5A0', '#C5A880', '#D4AF37', '#B89758',
            '#C2A36E', '#DFB76C', '#E5C07B', '#F5E8C7', '#FFF8EC',
            '#38A169', '#68D391', '#4ADE80', '#22C55E', '#15803D',
            '#D48B60', '#C8B9AE', '#B3A49A', '#9C7A58'
        ];

        if (in_array($upper, $legacyGoldsAndGreens, true)) {
            if (str_contains($key, 'accent') || str_contains($key, 'hover')) {
                return '#737B85';
            }
            return '#A7ADB4';
        }

        // Fundos globais ou de páginas que estavam em dark noir antigo ou marfim anterior
        if (in_array($key, ['custom_color_bg', 'site_bg_color', 'home_bg_color', 'store_bg_color', 'product_bg_color', 'checkout_bg_color', 'institutional_bg_color'], true)) {
            if (in_array($upper, ['#0D0C0B', '#120D0A', '#06130D', '#141312', '#F7F7F5', '#FAF8F5'], true)) {
                return '#F8F8F7';
            }
        }

        // Fundo secundário / seções alternadas
        if (in_array($key, ['custom_color_bg_alt', 'surface_alt'], true)) {
            if (in_array($upper, ['#FAFAF8', '#FAF8F5', '#1E1E1D'], true)) {
                return '#F0F0EF';
            }
        }

        // Textos que estavam com cores antigas
        if ($key === 'custom_color_text' && in_array($upper, ['#25272A', '#302C29', '#FFFFFF', '#EDE7DF'], true)) {
            return '#202020';
        }

        if ($key === 'custom_color_text_muted' && in_array($upper, ['#737B85', '#81766F', '#8E8880', '#9E978E', '#A8A198'], true)) {
            return '#686868';
        }

        // Fundo de rodapé e top banner para o novo preto grafite
        if (in_array($key, ['footer_bg_color', 'top_banner_bg_color'], true)) {
            if (in_array($upper, ['#1E2023', '#1A1C1E', '#0D0C0B', '#1C1E21', '#24201D'], true)) {
                return '#252525';
            }
        }

        // Texto de rodapé e top banner sobre o fundo escuro
        if (in_array($key, ['footer_text_color', 'top_banner_text_color'], true)) {
            if (in_array($upper, ['#CBD0D6', '#D5CBC3', '#EDE7DF'], true)) {
                return '#FFFFFF';
            }
        }

        // Normalização das informações públicas dos produtos: Substituir "Prata de Lei 925", "Prata de Lei" e "Prata 925" por "Banho de Prata"
        if ($val !== null && (
            stripos($val, 'prata de lei 925') !== false || 
            stripos($val, 'prata de lei') !== false || 
            stripos($val, 'prata 925') !== false ||
            stripos($val, 'prata legítima 925') !== false ||
            stripos($val, 'prata nobre 925') !== false
        )) {
            $val = str_ireplace(
                ['Prata de Lei 925', 'prata de lei 925', 'Prata de Lei', 'prata de lei', 'Prata legítima 925', 'prata legítima 925', 'Prata nobre 925', 'prata nobre 925', 'Prata 925', 'prata 925'],
                ['Banho de Prata', 'banho de prata', 'Banho de Prata', 'banho de prata', 'Banho de Prata', 'banho de prata', 'Banho de Prata', 'banho de prata', 'Banho de Prata', 'banho de prata'],
                $val
            );
        }

        return $val;
    }

    /**
     * Retorna o valor de uma configuração pelo seu identificador (ou valor padrão).
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        if (self::$cache === null) {
            self::loadAll();
        }

        $val = self::$cache[$key] ?? null;
        if ($val === null || trim($val) === '') {
            return self::sanitizeLegacySetting($key, $default);
        }

        return self::sanitizeLegacySetting($key, $val);
    }

    /**
     * Define ou atualiza uma configuração no banco de dados.
     */
    public static function set(string $key, ?string $value): bool
    {
        try {
            $pdo = Database::getConnection();

            $check = $pdo->prepare('SELECT COUNT(*) FROM settings WHERE key_name = :key_name');
            $check->execute(['key_name' => $key]);
            $exists = ((int) $check->fetchColumn()) > 0;

            if ($exists) {
                $stmt = $pdo->prepare('UPDATE settings SET value = :value WHERE key_name = :key_name');
                $stmt->execute(['value' => $value, 'key_name' => $key]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO settings (key_name, value) VALUES (:key_name, :value)');
                $stmt->execute(['key_name' => $key, 'value' => $value]);
            }

            if (self::$cache === null) {
                self::$cache = [];
            }
            self::$cache[$key] = $value;

            return true;
        } catch (\Throwable $e) {
            error_log("Erro ao salvar configuração '{$key}': " . $e->getMessage());
            return false;
        }
    }

    /**
     * Salva múltiplas configurações em lote de forma atômica e rápida.
     *
     * @param array<string, mixed> $data
     */
    public static function setMany(array $data): bool
    {
        try {
            $pdo = Database::getConnection();
            $inTx = false;
            try {
                $inTx = $pdo->beginTransaction();
            } catch (\Throwable $txErr) {
                // Algum driver pode não suportar ou já estar em transação
            }

            $check = $pdo->prepare('SELECT COUNT(*) FROM settings WHERE key_name = :key_name');
            $update = $pdo->prepare('UPDATE settings SET value = :value WHERE key_name = :key_name');
            $insert = $pdo->prepare('INSERT INTO settings (key_name, value) VALUES (:key_name, :value)');

            if (self::$cache === null) {
                self::$cache = [];
            }

            foreach ($data as $key => $value) {
                if (is_string($key) && !str_starts_with($key, '_')) {
                    $val = is_scalar($value) ? (string) $value : json_encode($value, JSON_UNESCAPED_UNICODE);

                    $check->execute(['key_name' => $key]);
                    $exists = ((int) $check->fetchColumn()) > 0;

                    if ($exists) {
                        $update->execute(['value' => $val, 'key_name' => $key]);
                    } else {
                        $insert->execute(['key_name' => $key, 'value' => $val]);
                    }

                    self::$cache[$key] = $val;
                }
            }

            if ($inTx && $pdo->inTransaction()) {
                $pdo->commit();
            }

            return true;
        } catch (\Throwable $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                try {
                    $pdo->rollBack();
                } catch (\Throwable $rbErr) {}
            }
            error_log('Erro ao salvar lote de configurações: ' . $e->getMessage());

            // Fallback seguro chave a chave
            $fallbackSuccess = true;
            foreach ($data as $k => $v) {
                if (is_string($k) && !str_starts_with($k, '_')) {
                    $val = is_scalar($v) ? (string) $v : json_encode($v, JSON_UNESCAPED_UNICODE);
                    if (!self::set($k, $val)) {
                        $fallbackSuccess = false;
                    }
                }
            }
            return $fallbackSuccess;
        }
    }

    /**
     * Retorna todas as configurações salvas.
     *
     * @return array<string, string|null>
     */
    public static function getAll(): array
    {
        if (self::$cache === null) {
            self::loadAll();
        }
        $sanitized = [];
        foreach (self::$cache ?? [] as $k => $v) {
            $sanitized[$k] = self::sanitizeLegacySetting((string)$k, $v);
        }
        return $sanitized;
    }

    /**
     * Retorna a lista completa e ordenada de linhas da Ficha Técnica dos produtos.
     * Suporta reordenação, renomeação de títulos, descrições customizadas, ocultação e novas linhas livres.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getProductSpecsList(): array
    {
        $json = self::get('store_specs_items_json');
        if (!empty($json)) {
            $decoded = json_decode($json, true);
            if (is_array($decoded) && !empty($decoded)) {
                $sanitized = [];
                $hasDesign = false;
                foreach ($decoded as $item) {
                    if (!is_array($item)) continue;
                    $id = trim((string)($item['id'] ?? ''));
                    if ($id === '') continue;

                    $title = trim((string)($item['title'] ?? ''));
                    // Renomeações automáticas requeridas se o item ainda tiver títulos legados
                    if ($id === 'material' && ($title === 'Gema / Mineral' || $title === '')) {
                        $title = 'Material';
                    } elseif ($id === 'acabamento' && ($title === 'Metal Nobre' || $title === '')) {
                        $title = 'Acabamento';
                    }

                    if ($id === 'design') {
                        $hasDesign = true;
                    }

                    $sanitized[] = [
                        'id' => $id,
                        'type' => (string)($item['type'] ?? 'custom'),
                        'title' => $title,
                        'desc' => trim((string)($item['desc'] ?? '')),
                        'visible' => !empty($item['visible']) ? 1 : 0,
                        'is_custom' => !empty($item['is_custom'])
                    ];
                }

                // Garante que o campo autoral Design exista mesmo em JSONs anteriores
                if (!$hasDesign) {
                    $insertIdx = 2; // logo após acabamento
                    $designRow = [
                        'id' => 'design',
                        'type' => 'fixed',
                        'title' => 'Design',
                        'desc' => self::get('store_spec_design_text', 'Design Autoral Exclusivo DIVINIUM • Linhas Anatômicas Contemporâneas'),
                        'visible' => (int) self::get('store_spec_design_visible', '1'),
                        'is_custom' => false
                    ];
                    array_splice($sanitized, $insertIdx, 0, [$designRow]);
                }

                if (!empty($sanitized)) {
                    return $sanitized;
                }
            }
        }

        // Padrão estrutural conforme solicitação
        return [
            [
                'id' => 'material',
                'type' => 'mineral',
                'title' => self::get('store_spec_mineral_label', 'Material'),
                'desc' => self::get('store_spec_mineral_override', ''),
                'visible' => (int) self::get('store_spec_mineral_visible', '1'),
                'is_custom' => false
            ],
            [
                'id' => 'acabamento',
                'type' => 'materials',
                'title' => self::get('store_spec_materials_label', 'Acabamento'),
                'desc' => self::get('store_spec_materials_override', ''),
                'visible' => (int) self::get('store_spec_materials_visible', '1'),
                'is_custom' => false
            ],
            [
                'id' => 'design',
                'type' => 'fixed',
                'title' => self::get('store_spec_design_label', 'Design'),
                'desc' => self::get('store_spec_design_text', 'Design Autoral Exclusivo DIVINIUM • Linhas Anatômicas Contemporâneas'),
                'visible' => (int) self::get('store_spec_design_visible', '1'),
                'is_custom' => false
            ],
            [
                'id' => 'dimensoes',
                'type' => 'dimensions',
                'title' => self::get('store_spec_dimensions_label', 'Dimensões & Ajuste'),
                'desc' => self::get('store_spec_dimensions_override', ''),
                'visible' => (int) self::get('store_spec_dimensions_visible', '1'),
                'is_custom' => false
            ],
            [
                'id' => 'cores',
                'type' => 'colors',
                'title' => self::get('store_spec_colors_label', 'Lapidação & Tonalidade'),
                'desc' => self::get('store_spec_colors_override', ''),
                'visible' => (int) self::get('store_spec_colors_visible', '1'),
                'is_custom' => false
            ],
            [
                'id' => 'autenticidade',
                'type' => 'fixed',
                'title' => self::get('store_spec_authenticity_label', 'Autenticidade'),
                'desc' => self::get('store_spec_authenticity_text', 'Certificado de Banho de Prata com Garantia Vitalícia'),
                'visible' => (int) self::get('store_spec_authenticity_visible', '1'),
                'is_custom' => false
            ],
            [
                'id' => 'sku',
                'type' => 'sku',
                'title' => self::get('store_spec_sku_label', 'Referência / SKU'),
                'desc' => '',
                'visible' => (int) self::get('store_spec_sku_visible', '1'),
                'is_custom' => false
            ],
            [
                'id' => 'disponibilidade',
                'type' => 'availability',
                'title' => self::get('store_spec_availability_label', 'Disponibilidade'),
                'desc' => self::get('store_spec_availability_override', ''),
                'visible' => (int) self::get('store_spec_availability_visible', '1'),
                'is_custom' => false
            ],
        ];
    }

    /**
     * Retorna a lista completa e ordenada de Canais de Venda e Marketplaces dos produtos.
     * Suporta reordenação, personalização de títulos, descrições, preços, badges, links, ícones,
     * ocultação e adição de novos canais de venda.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getProductChannelsList(): array
    {
        $json = self::get('store_channels_items_json');
        if (!empty($json)) {
            $decoded = json_decode($json, true);
            if (is_array($decoded) && !empty($decoded)) {
                $sanitized = [];
                foreach ($decoded as $item) {
                    if (!is_array($item)) continue;
                    $id = trim((string)($item['id'] ?? ''));
                    if ($id === '') continue;

                    $sanitized[] = [
                        'id' => $id,
                        'type' => (string)($item['type'] ?? 'external'),
                        'platform' => trim((string)($item['platform'] ?? 'Loja Oficial')),
                        'title' => trim((string)($item['title'] ?? '')),
                        'official_label' => trim((string)($item['official_label'] ?? 'Canal Homologado DIVINIUM')),
                        'desc' => trim((string)($item['desc'] ?? '')),
                        'price_override' => trim((string)($item['price_override'] ?? '')),
                        'badge' => trim((string)($item['badge'] ?? '')),
                        'icon' => trim((string)($item['icon'] ?? 'oficial')),
                        'url' => trim((string)($item['url'] ?? '')),
                        'target' => trim((string)($item['target'] ?? '_blank')),
                        'visible' => !empty($item['visible']) ? 1 : 0,
                        'is_custom' => !empty($item['is_custom']),
                        'is_deletable' => !isset($item['is_deletable']) || !empty($item['is_deletable']),
                    ];
                }
                if (!empty($sanitized)) {
                    return $sanitized;
                }
            }
        }

        // Padrão estrutural oficial DIVINIUM
        return [
            [
                'id' => 'shopee',
                'type' => 'external',
                'platform' => 'Shopee',
                'title' => self::get('store_channel_shopee_title', 'Comprar na Shopee Oficial'),
                'official_label' => self::get('store_channel_shopee_official', 'Canal Homologado DIVINIUM'),
                'desc' => self::get('store_channel_shopee_desc', 'Loja Oficial DIVINIUM na Shopee Brasil'),
                'price_override' => self::get('store_channel_shopee_price', ''),
                'badge' => self::get('store_channel_shopee_badge', 'Oficial'),
                'icon' => 'shopee',
                'url' => self::get('store_channel_shopee_url', 'https://shopee.com.br/search?keyword=Divinium'),
                'target' => '_blank',
                'visible' => (int) self::get('store_channel_shopee_visible', '1'),
                'is_custom' => false,
                'is_deletable' => true,
            ],
            [
                'id' => 'mercadolivre',
                'type' => 'external',
                'platform' => 'Mercado Livre',
                'title' => self::get('store_channel_ml_title', 'Comprar no Mercado Livre'),
                'official_label' => self::get('store_channel_ml_official', 'Canal Homologado DIVINIUM'),
                'desc' => self::get('store_channel_ml_desc', 'Loja Oficial Mercado Líder Platinum'),
                'price_override' => self::get('store_channel_ml_price', ''),
                'badge' => self::get('store_channel_ml_badge', 'Envio Full'),
                'icon' => 'mercadolivre',
                'url' => self::get('store_channel_ml_url', 'https://lista.mercadolivre.com.br/divinium'),
                'target' => '_blank',
                'visible' => (int) self::get('store_channel_ml_visible', '1'),
                'is_custom' => false,
                'is_deletable' => true,
            ],
            [
                'id' => 'whatsapp',
                'type' => 'external',
                'platform' => 'WhatsApp VIP',
                'title' => self::get('store_channel_wa_title', 'Consultar Consultor VIP no WhatsApp'),
                'official_label' => self::get('store_channel_wa_official', 'Atendimento Direto DIVINIUM'),
                'desc' => self::get('store_channel_wa_desc', 'Atendimento personalizado com especialista em alta joalheria'),
                'price_override' => self::get('store_channel_wa_price', ''),
                'badge' => self::get('store_channel_wa_badge', 'Atendimento VIP'),
                'icon' => 'whatsapp',
                'url' => self::get('store_channel_wa_url', self::get('store_whatsapp_url', 'https://wa.me/5511999999999')),
                'target' => '_blank',
                'visible' => (int) self::get('store_channel_wa_visible', '1'),
                'is_custom' => false,
                'is_deletable' => true,
            ],
        ];
    }
}

