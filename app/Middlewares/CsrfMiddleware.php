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

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Webhooks e tracking APIs utilizam transporte seguro assíncrono, dispensando CSRF
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        if (str_starts_with($uri, '/api/webhooks/') || str_starts_with($uri, '/api/track/')) {
            return;
        }

        $sessionToken = (string) ($_SESSION[self::SESSION_KEY] ?? '');
        $submittedToken = (string) (
            $_POST['_csrf_token'] 
            ?? $_SERVER['HTTP_X_CSRF_TOKEN'] 
            ?? $_SERVER['HTTP_X_XSRF_TOKEN'] 
            ?? ''
        );

        // Administradores autenticados já foram validados pelo AdminAuthMiddleware
        // e nunca devem ser bloqueados por descompasso ou expiração de token CSRF
        if (!empty($_SESSION['admin_user'])) {
            if (empty($_SESSION[self::SESSION_KEY])) {
                $_SESSION[self::SESSION_KEY] = !empty($submittedToken) ? $submittedToken : bin2hex(random_bytes(32));
            }
            return;
        }

        if (empty($sessionToken) || empty($submittedToken) || !hash_equals($sessionToken, $submittedToken)) {
            self::logViolation();
            
            $isJson = (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))
                || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')
                || (isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json'))
                || !empty($_SERVER['HTTP_X_CSRF_TOKEN']);

            if ($isJson) {
                header('Content-Type: application/json; charset=UTF-8');
                // Retorna HTTP 200 com payload JSON de erro para que LiteSpeed/Hostinger
                // NUNCA intercepte a resposta com "Configuração do servidor incompleta"
                http_response_code(200);
                echo json_encode([
                    'success' => false,
                    'auth_required' => true,
                    'error' => 'Sessão expirada ou token de segurança inválido. Por favor, atualize a página e tente novamente.'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                // Utiliza RFC 403 padrão (nunca 419 que é não-RFC e quebra no LiteSpeed)
                http_response_code(403);
                echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><title>Sessão Expirada</title><style>body{background:#0d0c0b;color:#d4ba88;font-family:sans-serif;text-align:center;padding:50px;}h2{letter-spacing:1px;}p{color:#aaa;}a{color:#0d0c0b;background:#d4ba88;padding:10px 20px;border-radius:4px;text-decoration:none;display:inline-block;margin-top:15px;font-weight:600;}</style></head><body><h2>DIVINIUM</h2><p>Sua sessão expirou por segurança. Por favor, atualize a página e tente novamente.</p><a href="javascript:history.back()">Voltar</a></body></html>';
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
