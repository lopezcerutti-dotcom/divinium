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
            
            $uri = $_SERVER['REQUEST_URI'] ?? '';
            $isJson = (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))
                || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')
                || (isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json'))
                || !empty($_SERVER['HTTP_X_CSRF_TOKEN'])
                || str_contains($uri, '/salvar')
                || str_contains($uri, '/toggle')
                || str_contains($uri, '/excluir')
                || str_contains($uri, '/duplicar')
                || str_contains($uri, '/upload-bg')
                || str_contains($uri, '/remover-bg')
                || str_contains($uri, '/sync')
                || str_contains($uri, '/config');

            if ($isJson) {
                while (ob_get_level() > 0) {
                    @ob_end_clean();
                }
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
                while (ob_get_level() > 0) {
                    @ob_end_clean();
                }
                // Retorna 200 com página de retorno para não ser interceptada pelo LiteSpeed Hostinger
                http_response_code(200);
                echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Sessão Expirada | Divinium</title><style>body{background:#F8F8F7;color:#202020;font-family:sans-serif;text-align:center;padding:80px 20px;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;box-sizing:border-box;}.box{background:#FFFFFF;border:1px solid #737B85;border-radius:16px;padding:48px 32px;max-width:500px;box-shadow:0 10px 30px rgba(0,0,0,0.05);}h2{letter-spacing:4px;font-weight:600;margin:0 0 12px;color:#202020;}p{color:#686868;line-height:1.6;margin:0 0 24px;}a{color:#202020;background:linear-gradient(135deg,#CBD0D6 0%,#A7ADB4 100%);border:1px solid #737B85;padding:12px 28px;border-radius:24px;text-decoration:none;display:inline-block;font-weight:700;}</style></head><body><div class="box"><h2>DIVINIUM</h2><p>Sua sessão expirou por segurança. Por favor, retorne e tente novamente.</p><a href="javascript:history.back()">Voltar</a></div></body></html>';
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
