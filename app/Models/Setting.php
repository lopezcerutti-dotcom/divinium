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
}
