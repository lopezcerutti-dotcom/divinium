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
     * Salva múltiplas configurações em lote.
     *
     * @param array<string, mixed> $data
     */
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
        return self::$cache ?? [];
    }
}
