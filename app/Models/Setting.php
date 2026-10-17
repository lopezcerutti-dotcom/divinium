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
     * Retorna o valor de uma configuração pelo seu identificador (ou valor padrão).
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        if (self::$cache === null) {
            self::loadAll();
        }

        $val = self::$cache[$key] ?? null;
        if ($val === null || trim($val) === '') {
            return $default;
        }

        return $val;
    }

    /**
     * Define ou atualiza uma configuração no banco de dados.
     */
    public static function set(string $key, ?string $value): bool
    {
        try {
            $pdo = Database::getConnection();

            $check = $pdo->prepare('SELECT id FROM settings WHERE key_name = :key_name LIMIT 1');
            $check->execute(['key_name' => $key]);
            $existingId = $check->fetchColumn();

            if ($existingId) {
                $stmt = $pdo->prepare('UPDATE settings SET value = :value, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
                $stmt->execute(['value' => $value, 'id' => $existingId]);
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
     * Salva múltiplas configurações em lote.
     *
     * @param array<string, mixed> $data
     */
    public static function setMany(array $data): bool
    {
        $success = true;
        foreach ($data as $key => $value) {
            if (is_string($key) && !str_starts_with($key, '_')) {
                $val = is_scalar($value) ? (string) $value : json_encode($value, JSON_UNESCAPED_UNICODE);
                if (!self::set($key, $val)) {
                    $success = false;
                }
            }
        }
        return $success;
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
        return self::$cache ?? [];
    }
}
