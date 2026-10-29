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

    /**
     * Retorna o logotipo oficial em SVG da plataforma de venda correspondente
     */
    public static function getPlatformLogoSvg(string $platform, int $size = 14): string
    {
        $raw = strtolower(trim($platform));
        $s = (int) $size;

        if (str_contains($raw, 'shopee')) {
            return '<svg class="channel-logo-svg" width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><path d="M19 7h-2.2C16.4 4.1 14.5 2 12 2S7.6 4.1 7.2 7H5c-1.1 0-2 .9-2 2l1.3 11.7c.1.9.8 1.6 1.7 1.6h12c.9 0 1.6-.7 1.7-1.6L21 9c0-1.1-.9-2-2-2z" fill="#EE4D2D"/><path d="M8.8 7c.4-2 1.6-3.4 3.2-3.4s2.8 1.4 3.2 3.4H8.8z" stroke="#FFFFFF" stroke-width="1.2" stroke-linecap="round"/><path d="M14.2 12.3c-.2-.7-.7-1.1-1.6-1.3l-.8-.2c-.5-.1-.7-.3-.7-.6s.3-.5.8-.5c.6 0 1.1.2 1.4.5l.6-.7c-.5-.4-1.2-.6-2-.6-1.1 0-1.9.6-1.9 1.5 0 .7.5 1.2 1.4 1.4l.8.2c.6.1.9.3.9.7s-.4.6-1 .6c-.7 0-1.3-.3-1.7-.7l-.6.7c.6.6 1.4.8 2.3.8 1.3 0 2.1-.6 2.1-1.6z" fill="#FFFFFF"/></svg>';
        }

        if (str_contains($raw, 'mercado') || str_contains($raw, 'ml') || str_contains($raw, 'livre')) {
            return '<svg class="channel-logo-svg" width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#FFE600"/><path d="M16.5 11.5c-.5-.5-1.3-.7-2-.7-.4 0-.8.1-1.1.3l-.7-.7c.3-.5.4-1.1.2-1.7-.3-.8-1-1.3-1.8-1.4-.8-.1-1.6.3-2.1.9l-2.2 2.6c-.3.4-.3 1 0 1.4l1.2 1.2c.3.3.7.4 1.1.4.3 0 .7-.1.9-.3l.7-.7c.3.4.8.7 1.4.8.6.1 1.2-.1 1.6-.5l.8-.8c.4.3.9.4 1.4.3.6-.1 1.1-.5 1.3-1.1.1-.3.1-.6 0-.8h.1c.3 0 .6-.2.8-.5.3-.4.2-1-.3-1.3zm-6.2.8l-1.1-1.1 2.1-2.4c.3-.3.7-.5 1.1-.4.4.1.7.3.8.7.1.3 0 .7-.2.9l-2.7 2.3zm2.5 1.1l-.8-.8 1.8-1.5c.2-.2.5-.2.7-.2.2 0 .5.1.7.3.2.2.3.5.2.7 0 .3-.2.5-.4.7l-2.2.8zm2.2-.4l-.8-.8 1.4-1.2c.2-.2.5-.2.7-.1.2.1.4.3.4.5 0 .2 0 .4-.2.6l-1.5 1z" fill="#2D3277"/></svg>';
        }

        if (str_contains($raw, 'tiktok')) {
            return '<svg class="channel-logo-svg" width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#000000"/><path d="M17.5 7.2a4.4 4.4 0 0 1-3.2-3.2h-2.5v12.2a2.4 2.4 0 1 1-2.4-2.4c.3 0 .6.1.9.2V11.5a4.9 4.9 0 0 0-.9-.1 4.9 4.9 0 1 0 4.9 4.9V9.8c1.3.9 2.8 1.4 4.4 1.4V8.7c-.4 0-.8-.1-1.2-.3z" fill="#FFFFFF"/><path d="M16.5 8.2a4.4 4.4 0 0 1-3.2-3.2h-.8v1.3a4.4 4.4 0 0 0 3.2 3.2v-1.3z" fill="#00F2FE"/><path d="M12.1 16.2a2.4 2.4 0 0 1-2.4 2.4c-1.3 0-2.4-1.1-2.4-2.4 0-.4.1-.8.3-1.1-.5.7-.8 1.6-.8 2.5a3.8 3.8 0 0 0 3.8 3.8 3.8 3.8 0 0 0 3.8-3.8V11.5h-1.5v4.7h-.8z" fill="#FE2C55"/></svg>';
        }

        if (str_contains($raw, 'amazon')) {
            return '<svg class="channel-logo-svg" width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#232F3E"/><path d="M6 14.5c2.5 1.8 6.5 2.5 9.8 1.2.5-.2 1.1.2.7.7-2.3 2.1-6.8 2.4-10.5.5-.4-.2-.4-.8 0-.9l.7-.4c.3-.2.7-.2 1-.1l-.7-.9z" fill="#FF9900"/><path d="M16.8 14.2c-.3-.4-1.8-.2-2.5-.1-.2 0-.3-.2-.1-.3 1.1-.9 2.9-.6 3.2-.2.3.4-.1 2.2-1.1 3-.2.1-.3 0-.3-.2.2-.7.8-1.8.8-2.2z" fill="#FF9900"/><path d="M13.2 8.5c0 1.2.6 1.8 1.5 1.8.7 0 1.2-.4 1.5-.9V8.5h1.4v4.4h-1.3v-.6c-.4.5-1.1.8-1.8.8-1.5 0-2.5-1.1-2.5-2.5 0-1.6 1.1-2.6 2.6-2.6.7 0 1.3.3 1.6.7v-.4c0-.9-.6-1.4-1.5-1.4-.7 0-1.3.3-1.6.7l-.8-.8c.6-.7 1.4-1.1 2.5-1.1 1.7 0 2.8.9 2.8 2.6v3.2h-1.4v-.6c-.4.5-1.1.8-1.8.8-1.5 0-2.5-1-2.5-2.5 0-1.6 1.1-2.6 2.6-2.6z" fill="#FFFFFF" opacity="0.9"/></svg>';
        }

        if (str_contains($raw, 'whatsapp') || str_contains($raw, 'wa.me') || str_contains($raw, 'consultor') || str_contains($raw, 'zap')) {
            return '<svg class="channel-logo-svg" width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#25D366"/><path d="M17.5 14.7c-.3-.1-1.6-.8-1.8-.9-.3-.1-.5-.1-.7.1-.2.3-.8.9-1 .1.1-.2.3-.4.3-.7.1-.3.1-1.5.6-2.9 1.8-1.1 1-.8 1.9-.3 2.3.4.4.9.4 1.3.4.3 0 .6 0 .8-.1.2-.1.7-.8.8-1.5.1-.7.1-1.3.1-1.5 0-.2-.2-.3-.5-.4zM12 4.4C7.8 4.4 4.4 7.8 4.4 12c0 1.4.4 2.8 1.1 4L4 20l4.2-1.1c1.2.6 2.5 1 3.8 1 4.2 0 7.6-3.4 7.6-7.6 0-4.2-3.4-7.9-7.6-7.9zm0 13.9c-1.2 0-2.4-.3-3.4-.9l-.2-.1-2.5.7.7-2.4-.2-.3c-.7-1-1.1-2.3-1.1-3.6 0-3.5 2.8-6.3 6.3-6.3 3.5 0 6.3 2.8 6.3 6.3 0 3.5-2.8 6.3-6.3 6.3z" fill="#FFFFFF"/></svg>';
        }

        if (str_contains($raw, 'pix') || str_contains($raw, 'asaas')) {
            return '<svg class="channel-logo-svg" width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#00BFA5"/><path d="M7.4 7.4l4.6 4.6-4.6 4.6-2.1-2.1a3.5 3.5 0 0 1 0-5l2.1-2.1zm9.2 0l2.1 2.1a3.5 3.5 0 0 1 0 5l-2.1 2.1-4.6-4.6 4.6-4.6zm-4.6 1.8l2.8 2.8-2.8 2.8-2.8-2.8 2.8-2.8z" fill="#FFFFFF"/></svg>';
        }

        if (str_contains($raw, 'instagram')) {
            return '<svg class="channel-logo-svg" width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#E1306C"/><rect x="5" y="5" width="14" height="14" rx="3.5" stroke="#FFFFFF" stroke-width="1.6"/><circle cx="12" cy="12" r="3.2" stroke="#FFFFFF" stroke-width="1.6"/><circle cx="16" cy="8" r="0.8" fill="#FFFFFF"/></svg>';
        }

        if (str_contains($raw, 'enjoei')) {
            return '<svg class="channel-logo-svg" width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#E83B5E"/><path d="M12 6c-3.3 0-6 2.7-6 6s2.7 6 6 6 6-2.7 6-6h-6v2.2h3.6c-.5 1.1-1.6 1.8-2.8 1.8-1.7 0-3-1.3-3-3s1.3-3 3-3c.8 0 1.5.3 2 .8l1.6-1.6C15.2 6.8 13.7 6 12 6z" fill="#FFFFFF"/></svg>';
        }

        if (str_contains($raw, 'elo7')) {
            return '<svg class="channel-logo-svg" width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#FF8800"/><path d="M6 7h12v2.2H9.2v2.8h6.6v2.2H9.2v2.8h8.8V19H6V7z" fill="#FFFFFF"/></svg>';
        }

        if (str_contains($raw, 'diret') || str_contains($raw, 'boutique') || str_contains($raw, 'site') || str_contains($raw, 'checkout') || str_contains($raw, 'divinium')) {
            return '<svg class="channel-logo-svg" width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#202020"/><path d="M6 3h12l3 5-9 13L3 8l3-5z" stroke="#ded7cf" stroke-width="1.6" fill="none" stroke-linejoin="round"/><path d="M3 8h18M12 21L8 8m4 13l4-13M6 3l2 5m10-5l-2 5" stroke="#ded7cf" stroke-width="1.2" stroke-linecap="round"/></svg>';
        }

        return '<svg class="channel-logo-svg" width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#50555E"/><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round"/></svg>';
    }
}
