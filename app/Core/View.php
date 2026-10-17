<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

/**
 * Renderizador de Views e Layouts com escape automático para proteção contra XSS.
 */
class View
{
    /**
     * Renderiza uma view dentro de um layout.
     *
     * @param string $view Caminho relativo da view (ex: 'home/index')
     * @param array<string, mixed> $data Dados passados para a view
     * @param string $layout Layout que envelopa a view (default: 'main')
     */
    public static function render(string $view, array $data = [], string $layout = 'main'): void
    {
        $viewFile = APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            throw new RuntimeException("Arquivo de View não encontrado: {$viewFile}");
        }

        // Extrai variáveis com escopo isolado
        extract($data, EXTR_SKIP);

        // Captura o conteúdo da view em buffer
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Se layout for especificado, envelopa o conteúdo
        if ($layout !== '') {
            $layoutFile = APP_PATH . '/Views/layouts/' . $layout . '.php';
            if (!file_exists($layoutFile)) {
                throw new RuntimeException("Arquivo de Layout não encontrado: {$layoutFile}");
            }
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    /**
     * Função auxiliar de escape HTML contra Cross-Site Scripting (XSS).
     */
    public static function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
