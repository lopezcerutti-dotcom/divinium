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
            $isJson = isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json');
            if ($isJson) {
                header('Content-Type: application/json; charset=UTF-8');
                http_response_code(401);
                echo json_encode(['error' => 'Acesso não autorizado. Por favor faça login como administrador.']);
                exit;
            }

            $_SESSION['login_error'] = 'Você precisa estar logado para acessar o painel de controle.';
            header('Location: /?login=required');
            exit;
        }
    }
}
