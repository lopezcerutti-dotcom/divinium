<?php

declare(strict_types=1);

namespace App\Middlewares;

use RuntimeException;

/**
 * Middleware de proteção contra Cross-Site Request Forgery (CSRF).
 * - Gera tokens criptograficamente seguros vinculados à sessão ativa
 * - Valida formulários POST/PUT/DELETE com comparação em tempo constante
 */
class CsrfMiddleware
{
    private const SESSION_KEY = '_csrf_token';

    /**
     * Retorna o token CSRF atual ou gera um novo se inexistente.
     */
    public static function getToken(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    /**
     * Valida a requisição contra ataques CSRF.
     * Ignora métodos seguros (GET, HEAD, OPTIONS).
     */
    public static function validate(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if (in_array($method, ['GET', 'HEAD', 'OPTIONS'], true)) {
            return;
        }

        // Webhooks e tracking APIs utilizam transporte seguro assíncrono, dispensando CSRF
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        if (str_starts_with($uri, '/api/webhooks/') || str_starts_with($uri, '/api/track/')) {
            return;
        }

        $sessionToken = $_SESSION[self::SESSION_KEY] ?? '';
        $submittedToken = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

        if (empty($sessionToken) || empty($submittedToken) || !hash_equals($sessionToken, $submittedToken)) {
            self::logViolation();
            http_response_code(419); // 419 Page Expired / CSRF Token Mismatch
            
            if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode([
                    'success' => false,
                    'error' => 'Sessão expirada ou token de segurança inválido. Por favor, atualize a página e tente novamente.'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><title>Sessão Expirada</title></head><body style="font-family:sans-serif;text-align:center;padding:50px;"><h2>Sua sessão expirou por segurança.</h2><p>Por favor, volte à página anterior, recarregue e envie o formulário novamente.</p><a href="javascript:history.back()">Voltar</a></body></html>';
            }
            exit;
        }
    }

    private static function logViolation(): void
    {
        $logFile = BASE_PATH . '/logs/security/csrf_violations.log';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $entry = sprintf("[%s] VIOLAÇÃO CSRF detectada | IP: %s | URI: %s\n", date('Y-m-d H:i:s'), $ip, $uri);
        @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
    }
}
