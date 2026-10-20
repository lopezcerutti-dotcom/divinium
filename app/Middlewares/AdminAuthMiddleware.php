<?php

declare(strict_types=1);

namespace App\Middlewares;

class AdminAuthMiddleware
{
    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_user'])) {
            $isJson = (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))
                || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')
                || (isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json'))
                || !empty($_SERVER['HTTP_X_CSRF_TOKEN']);

            if ($isJson) {
                header('Content-Type: application/json; charset=UTF-8');
                // Retorna 200 com payload JSON seguro para impedir interceptação do LiteSpeed Hostinger
                http_response_code(200);
                echo json_encode([
                    'success' => false,
                    'auth_required' => true,
                    'error' => 'Sua sessão administrativa expirou. Por favor, faça login novamente para continuar.'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $_SESSION['login_error'] = 'Você precisa estar logado para acessar o painel de controle.';
            header('Location: /?login=required');
            exit;
        }
    }
}
