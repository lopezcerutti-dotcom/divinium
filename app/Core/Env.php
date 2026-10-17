<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Gerenciador de Variáveis de Ambiente e Configurações (.env / env.php).
 * Fornece fallback resiliente para garantir que o sistema nunca falhe por variáveis ausentes.
 */
class Env
{
    /** @var array<string, mixed> Variáveis carregadas em memória */
    private static array $variables = [];

    /**
     * Carrega variáveis a partir de um arquivo .env
     */
    public static function load(string $filePath): void
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            return;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#') || str_starts_with($line, ';')) {
                continue;
            }

            $pos = strpos($line, '=');
            if ($pos === false) {
                continue;
            }

            $key = trim(substr($line, 0, $pos));
            $value = trim(substr($line, $pos + 1));

            // Remove aspas simples ou duplas ao redor do valor
            $len = strlen($value);
            if ($len >= 2) {
                if (($value[0] === '"' && $value[$len - 1] === '"') ||
                    ($value[0] === "'" && $value[$len - 1] === "'")) {
                    $value = substr($value, 1, -1);
                }
            }

            self::set($key, $value);
        }
    }

    /**
     * Retorna o valor de uma variável de ambiente ou o valor padrão se não existir.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, self::$variables)) {
            $val = self::$variables[$key];
            if ($val !== null && $val !== '') {
                return $val;
            }
        }

        $envVal = getenv($key);
        if ($envVal !== false && $envVal !== '') {
            return $envVal;
        }

        if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
            return $_ENV[$key];
        }

        if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
            return $_SERVER[$key];
        }

        return $default;
    }

    /**
     * Define ou sobrescreve uma variável de ambiente.
     */
    public static function set(string $key, mixed $value): void
    {
        self::$variables[$key] = $value;
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
        if (is_scalar($value)) {
            @putenv("{$key}={$value}");
        }
    }

    /**
     * Define múltiplas variáveis de ambiente a partir de um array associativo.
     *
     * @param array<string, mixed> $vars
     */
    public static function setMany(array $vars): void
    {
        foreach ($vars as $key => $value) {
            self::set((string) $key, $value);
        }
    }

    /**
     * Retorna todas as variáveis carregadas atualmente.
     *
     * @return array<string, mixed>
     */
    public static function all(): array
    {
        return self::$variables;
    }
}
