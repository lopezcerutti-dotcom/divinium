<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Gerenciador Singleton de Conexão com o Banco de Dados via PDO.
 * Garante prepared statements nativos (ATTR_EMULATE_PREPARES = false) contra SQL Injection.
 */
class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $host = (string) Env::get('DB_HOST', '127.0.0.1');
            $port = (string) Env::get('DB_PORT', '3306');
            $db   = (string) Env::get('DB_DATABASE');
            $user = (string) Env::get('DB_USERNAME');
            $pass = (string) Env::get('DB_PASSWORD');
            $charset = (string) Env::get('DB_CHARSET', 'utf8mb4');

            $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";

            if (!extension_loaded('pdo_mysql')) {
                throw new RuntimeException(
                    'A extensão PHP "pdo_mysql" não está ativada no seu ambiente. ' .
                    'Ative-a no php.ini (local) ou no hPanel da Hostinger (Avançado > Configuração do PHP > Extensões).'
                );
            }

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // ESSENCIAL CONTRA SQL INJECTION: Desliga emulação de prepared statements
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            if (defined('PDO::MYSQL_ATTR_INIT_COMMAND')) {
                $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES {$charset} COLLATE utf8mb4_unicode_ci";
            }

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // FALLBACK LOCAL INTELIGENTE: Se a conexão MySQL falhar (ex: rodando localmente no notebook),
                // conectar ao banco local SQLite para que o site e o painel administrativo funcionem 100% imediatamente!
                $sqlitePath = BASE_PATH . '/database/divinium_local.sqlite';
                if (extension_loaded('pdo_sqlite')) {
                    if (!file_exists($sqlitePath)) {
                        @touch($sqlitePath);
                    }
                    self::$instance = new PDO('sqlite:' . $sqlitePath, null, null, [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]);
                    return self::$instance;
                }

                // Logar falha de conexão caso SQLite também não esteja disponível
                $logFile = BASE_PATH . '/logs/app/database_errors.log';
                $entry = sprintf("[%s] Falha de conexão DB: %s\n", date('Y-m-d H:i:s'), $e->getMessage());
                @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);

                throw new RuntimeException('Não foi possível estabelecer conexão segura com a base de dados.');
            }
        }

        return self::$instance;
    }
}
