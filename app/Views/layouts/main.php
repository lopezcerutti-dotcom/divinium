<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-P94C1TNV7L"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-P94C1TNV7L');
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <?php
        $defaultSiteTitle = \App\Models\Setting::get('seo_site_title', 'DIVINIUM | Joalheria Autoral e Biojoias Contemporâneas no Rio de Janeiro');
        $defaultMetaDesc = \App\Models\Setting::get('seo_meta_description', 'Descubra acessórios autorais de design atemporal e acabamento artesanal. Peças exclusivas em edições limitadas criadas no Rio de Janeiro.');
        $titleFormat = \App\Models\Setting::get('seo_site_title_format', '{title} | Divinium');
        $siteBrand = \App\Models\Setting::get('header_logo_text', 'Divinium');

        $reqUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $isHome = ($reqUri === '/' || $reqUri === '' || $reqUri === '/index.php' || $reqUri === '/index');

        if ($isHome || empty($pageTitle)) {
            $renderedTitle = $defaultSiteTitle;
        } else {
            $currentTitle = (string)$pageTitle;
            if (stripos($currentTitle, 'divinium') !== false || str_contains($currentTitle, '|')) {
                $renderedTitle = $currentTitle;
            } else {
                $renderedTitle = str_replace(['{title}', '{brand}'], [$currentTitle, $siteBrand], $titleFormat);
                if (!str_contains($titleFormat, '{title}')) {
                    $renderedTitle = $currentTitle . ' | ' . $siteBrand;
                }
            }
        }

        $metaDesc = (!empty($pageDescription) && !$isHome) ? $pageDescription : $defaultMetaDesc;
        $metaKeywords = \App\Models\Setting::get('seo_meta_keywords', 'joias autorais, biojoias contemporâneas, joalheria rio de janeiro, acessórios de luxo, edições limitadas, acabamento artesanal, design atemporal');
        $ogImage = \App\Models\Setting::get('seo_og_image', '/assets/images/hero/hero-main.webp');
        $faviconUrl = \App\Models\Setting::get('site_favicon_url', '/assets/images/favicon.ico');

        // Domínio oficial e resolução dinâmica do link canônico (rel="canonical")
        $rawCanonicalBase = \App\Models\Setting::get('site_canonical_url', \App\Core\Env::get('APP_URL', 'https://divinium.com.br'));
        $canonicalBase = rtrim(trim((string)$rawCanonicalBase), '/');
        if (empty($canonicalBase) || str_contains($canonicalBase, 'seudominio.com.br') || str_contains($canonicalBase, 'localhost')) {
            $canonicalBase = 'https://divinium.com.br';
        }
        if (!str_starts_with($canonicalBase, 'http://') && !str_starts_with($canonicalBase, 'https://')) {
            $canonicalBase = 'https://' . $canonicalBase;
        }

        if (!empty($canonicalUrl)) {
            $trimmedCanonical = trim((string)$canonicalUrl);
            if (str_starts_with($trimmedCanonical, 'http://') || str_starts_with($trimmedCanonical, 'https://')) {
                $renderedCanonical = str_replace(['http://seudominio.com.br', 'https://seudominio.com.br'], $canonicalBase, $trimmedCanonical);
            } else {
                $renderedCanonical = $canonicalBase . '/' . ltrim($trimmedCanonical, '/');
            }
        } else {
            $reqPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
            if (empty($reqPath) || $reqPath === '/' || $reqPath === '/index.php' || $reqPath === '/index') {
                $renderedCanonical = $canonicalBase . '/';
            } else {
                $renderedCanonical = $canonicalBase . '/' . ltrim($reqPath, '/');
            }
        }

        // Resolução de imagem para redes sociais com URL absoluta
        $renderedOgImage = $ogImage;
        if (!empty($ogImage) && !str_starts_with($ogImage, 'http://') && !str_starts_with($ogImage, 'https://')) {
            $renderedOgImage = $canonicalBase . '/' . ltrim($ogImage, '/');
        }
    ?>
    <title><?= \App\Core\View::e($renderedTitle) ?></title>
    
    <meta name="description" content="<?= \App\Core\View::e($metaDesc) ?>">
    <meta name="keywords" content="<?= \App\Core\View::e($metaKeywords) ?>">
    <!-- Google Search Console: Verificação de Propriedade do Domínio -->
    <meta name="google-site-verification" content="<?= \App\Core\View::e(\App\Models\Setting::get('google_site_verification', 'T5Ch24JLJl6L5hLahazzSiHA3mFyfCB0F05zyV0PVPA')) ?>" />
    <link rel="canonical" href="<?= \App\Core\View::e($renderedCanonical) ?>">
    <!-- Favicon & Identidade Visual Oficial DIVINIUM -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico?v=20261029b">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png?v=20261029b">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png?v=20261029b">
    <link rel="icon" type="image/png" sizes="48x48" href="/assets/images/favicon-48x48.png?v=20261029b">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png?v=20261029b">
    <link rel="manifest" href="/site.webmanifest?v=20261029b">
    <meta name="theme-color" content="#202020">
    <meta name="msapplication-TileColor" content="#202020">

    <!-- Open Graph / Redes Sociais -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= \App\Core\View::e($siteBrand) ?>">
    <meta property="og:title" content="<?= \App\Core\View::e($renderedTitle) ?>">
    <meta property="og:description" content="<?= \App\Core\View::e($metaDesc) ?>">
    <meta property="og:url" content="<?= \App\Core\View::e($renderedCanonical) ?>">
    <meta property="og:image" content="<?= \App\Core\View::e($renderedOgImage) ?>">
    <meta property="og:locale" content="pt_BR">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= \App\Core\View::e($renderedTitle) ?>">
    <meta name="twitter:description" content="<?= \App\Core\View::e($metaDesc) ?>">
    <meta name="twitter:image" content="<?= \App\Core\View::e($renderedOgImage) ?>">

    <!-- Tipografia Editorial: Cormorant Garamond (Serif de Luxo) + Plus Jakarta Sans (UI moderna) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Estilos Principais -->
    <link rel="stylesheet" href="/assets/css/app.css?v=<?= time() ?>">

    <!-- Token CSRF para requisições assíncronas -->
    <meta name="csrf-token" content="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">

    <!-- Aplicação do Tema Definido no Painel Administrativo -->
    <?php
        $rawServerTheme = \App\Models\Setting::get('default_theme', 'theme-silver-noir');
        $themeClassMap = [
            'theme-gold' => 'theme-silver-noir',
            'theme-silver' => 'theme-pearl-platinum',
            'theme-emerald' => 'theme-nude-pearl',
            'theme-rose' => 'theme-contemporary-graphite'
        ];
        $canonicalTheme = $themeClassMap[$rawServerTheme] ?? $rawServerTheme;

        // Identificação da Página Atual para Aplicação Estrita de Cores Específicas
        $reqUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $bodyPageClass = 'page-home';
        if ($reqUri === '/' || $reqUri === '') {
            $bodyPageClass = 'page-home';
        } elseif (str_starts_with($reqUri, '/loja')) {
            $bodyPageClass = 'page-store';
        } elseif (str_starts_with($reqUri, '/produto')) {
            $bodyPageClass = 'page-product';
        } elseif (str_starts_with($reqUri, '/checkout')) {
            $bodyPageClass = 'page-checkout';
        } elseif (str_starts_with($reqUri, '/pagina') || str_starts_with($reqUri, '/privacidade') || str_starts_with($reqUri, '/termos')) {
            $bodyPageClass = 'page-institutional';
        }
    ?>
    <script>
        (function() {
            var rawTheme = <?= json_encode($rawServerTheme) ?>;
            var canonicalTheme = <?= json_encode($canonicalTheme) ?>;
            document.documentElement.classList.add(canonicalTheme);
            if (rawTheme && rawTheme !== canonicalTheme) {
                document.documentElement.classList.add(rawTheme);
            }
        })();
    </script>

    <!-- Scripts de Rastreamento / Analytics (Head) -->
    <?= \App\Models\Setting::get('custom_head_scripts', '') ?>

    <!-- Personalização de Cores e Fundo Dinâmico em Todas as Páginas -->
    <?php
        // -------------------------------------------------------------------------
        // 1. TEMA BASE CANÔNICO & VALORES PADRÃO (QUIET LUXURY DA DIVINIUM)
        // -------------------------------------------------------------------------
        $themeDefaults = [
            'theme-silver-noir' => [
                'bg' => '#F8F8F7', 'bg_alt' => '#F0F0EF', 'surface' => '#FFFFFF', 'surface_box' => '#FFFFFF',
                'title' => '#202020', 'subtitle' => '#686868', 'text' => '#202020', 'text_muted' => '#686868',
                'icon_decor' => '#A7ADB4', 'icon_nav' => '#202020',
                'link' => '#202020', 'link_hover' => '#737B85',
                'btn_primary_bg' => '#A7ADB4', 'btn_primary_text' => '#202020', 'btn_primary_border' => '#737B85',
                'btn_secondary_bg' => '#F0F0EF', 'btn_secondary_text' => '#202020', 'btn_secondary_border' => '#CBD0D6',
                'border' => 'rgba(115, 123, 133, 0.28)', 'border_subtle' => 'rgba(115, 123, 133, 0.15)',
                'hover_bg' => 'rgba(167, 173, 180, 0.12)', 'focus_ring' => '#737B85', 'disabled' => '#A7ADB4',
                'footer_bg' => '#252525', 'footer_text' => '#FFFFFF',
                'header_bg' => '#FFFFFF', 'header_text' => '#202020'
            ],
            'theme-pearl-platinum' => [
                'bg' => '#FFFFFF', 'bg_alt' => '#F0F0EF', 'surface' => '#FFFFFF', 'surface_box' => '#FFFFFF',
                'title' => '#202020', 'subtitle' => '#686868', 'text' => '#202020', 'text_muted' => '#686868',
                'icon_decor' => '#A7ADB4', 'icon_nav' => '#202020',
                'link' => '#202020', 'link_hover' => '#737B85',
                'btn_primary_bg' => '#A7ADB4', 'btn_primary_text' => '#202020', 'btn_primary_border' => '#737B85',
                'btn_secondary_bg' => '#F0F0EF', 'btn_secondary_text' => '#202020', 'btn_secondary_border' => '#CBD0D6',
                'border' => 'rgba(115, 123, 133, 0.28)', 'border_subtle' => 'rgba(115, 123, 133, 0.15)',
                'hover_bg' => 'rgba(167, 173, 180, 0.12)', 'focus_ring' => '#737B85', 'disabled' => '#A7ADB4',
                'footer_bg' => '#252525', 'footer_text' => '#FFFFFF',
                'header_bg' => '#FFFFFF', 'header_text' => '#202020'
            ],
            'theme-nude-pearl' => [
                'bg' => '#F8F8F7', 'bg_alt' => '#F0F0EF', 'surface' => '#FFFFFF', 'surface_box' => '#FFFFFF',
                'title' => '#202020', 'subtitle' => '#686868', 'text' => '#202020', 'text_muted' => '#686868',
                'icon_decor' => '#A7ADB4', 'icon_nav' => '#202020',
                'link' => '#202020', 'link_hover' => '#737B85',
                'btn_primary_bg' => '#A7ADB4', 'btn_primary_text' => '#202020', 'btn_primary_border' => '#737B85',
                'btn_secondary_bg' => '#F0F0EF', 'btn_secondary_text' => '#202020', 'btn_secondary_border' => '#CBD0D6',
                'border' => 'rgba(115, 123, 133, 0.28)', 'border_subtle' => 'rgba(115, 123, 133, 0.15)',
                'hover_bg' => 'rgba(167, 173, 180, 0.12)', 'focus_ring' => '#737B85', 'disabled' => '#A7ADB4',
                'footer_bg' => '#252525', 'footer_text' => '#FFFFFF',
                'header_bg' => '#FFFFFF', 'header_text' => '#202020'
            ],
            'theme-contemporary-graphite' => [
                'bg' => '#F0F0EF', 'bg_alt' => '#F8F8F7', 'surface' => '#FFFFFF', 'surface_box' => '#FFFFFF',
                'title' => '#202020', 'subtitle' => '#686868', 'text' => '#202020', 'text_muted' => '#686868',
                'icon_decor' => '#A7ADB4', 'icon_nav' => '#202020',
                'link' => '#202020', 'link_hover' => '#737B85',
                'btn_primary_bg' => '#A7ADB4', 'btn_primary_text' => '#202020', 'btn_primary_border' => '#737B85',
                'btn_secondary_bg' => '#F8F8F7', 'btn_secondary_text' => '#202020', 'btn_secondary_border' => '#CBD0D6',
                'border' => 'rgba(115, 123, 133, 0.28)', 'border_subtle' => 'rgba(115, 123, 133, 0.15)',
                'hover_bg' => 'rgba(167, 173, 180, 0.12)', 'focus_ring' => '#737B85', 'disabled' => '#A7ADB4',
                'footer_bg' => '#252525', 'footer_text' => '#FFFFFF',
                'header_bg' => '#FFFFFF', 'header_text' => '#202020'
            ]
        ];
        $activeThemeDef = $themeDefaults[$canonicalTheme] ?? $themeDefaults['theme-silver-noir'];

        // Função de Contraste WCAG AA (evita texto claro sobre botões claros)
        $calcContrastColor = function($hexColor, $darkDefault = '#202020', $lightDefault = '#FFFFFF') {
            $h = ltrim((string)$hexColor, '#');
            if (strlen($h) === 3) {
                $h = $h[0].$h[0].$h[1].$h[1].$h[2].$h[2];
            }
            if (strlen($h) !== 6) return $lightDefault;
            $r = hexdec(substr($h, 0, 2));
            $g = hexdec(substr($h, 2, 2));
            $b = hexdec(substr($h, 4, 2));
            $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
            return ($yiq >= 140) ? $darkDefault : $lightDefault;
        };

        // -------------------------------------------------------------------------
        // 2. CORES INDEPENDENTES POR FUNÇÃO (NUNCA ACOPLADAS)
        // -------------------------------------------------------------------------
        // Fundos & Superfícies
        $rawBg = \App\Models\Setting::get('custom_color_bg');
        $rawSiteBg = \App\Models\Setting::get('site_bg_color');
        $rawBgAlt = \App\Models\Setting::get('custom_color_bg_alt');
        $rawSurface = \App\Models\Setting::get('custom_color_surface');
        $rawSurfaceBox = \App\Models\Setting::get('custom_color_surface_box');

        $effBgMain = !empty($rawSiteBg) ? $rawSiteBg : (!empty($rawBg) ? $rawBg : $activeThemeDef['bg']);
        $effBgAlt = !empty($rawBgAlt) ? $rawBgAlt : $activeThemeDef['bg_alt'];
        $effSurfaceCard = !empty($rawSurface) ? $rawSurface : $activeThemeDef['surface'];
        $effSurfaceBox = !empty($rawSurfaceBox) ? $rawSurfaceBox : $effSurfaceCard;

        // Helper para conversão HEX -> RGB
        $hexToRgb = function($hex, $default = [0, 0, 0]) {
            $h = ltrim((string)$hex, '#');
            if (strlen($h) === 3) $h = $h[0].$h[0].$h[1].$h[1].$h[2].$h[2];
            if (strlen($h) !== 6) return $default;
            return [hexdec(substr($h, 0, 2)), hexdec(substr($h, 2, 2)), hexdec(substr($h, 4, 2))];
        };

        // -------------------------------------------------------------------------
        // SOMBRAS & EFEITOS VISUAIS DE FUNDO
        // -------------------------------------------------------------------------
        $shadowsEnabled = \App\Models\Setting::get('site_shadows_enabled', '1');
        $shadowIntensity = (float)\App\Models\Setting::get('site_shadow_intensity', '0.25');
        $shadowColorHex = \App\Models\Setting::get('site_shadow_color', '#000000');
        $shadowBlur = (int)\App\Models\Setting::get('site_shadow_blur', '24');
        $sRgb = $hexToRgb($shadowColorHex);
        $shadowRgba = "rgba({$sRgb[0]}, {$sRgb[1]}, {$sRgb[2]}, {$shadowIntensity})";
        $shadowRgbaSubtle = "rgba({$sRgb[0]}, {$sRgb[1]}, {$sRgb[2]}, " . ($shadowIntensity * 0.5) . ")";
        $shadowRgbaDeep = "rgba({$sRgb[0]}, {$sRgb[1]}, {$sRgb[2]}, " . min(1.0, $shadowIntensity * 1.6) . ")";

        // Efeitos de Fundo: Glassmorphism, Vinheta e Ambient Glow
        $glassEnabled = \App\Models\Setting::get('site_fx_glass_enabled', '1');
        $glassBlur = (int)\App\Models\Setting::get('site_fx_glass_blur', '12');

        $vignetteEnabled = \App\Models\Setting::get('site_fx_vignette_enabled', '0');
        $vignetteIntensity = (float)\App\Models\Setting::get('site_fx_vignette_intensity', '0.40');

        $glowEnabled = \App\Models\Setting::get('site_fx_glow_enabled', '1');
        $glowColor = \App\Models\Setting::get('site_fx_glow_color', '#A7ADB4');
        $glowIntensity = (float)\App\Models\Setting::get('site_fx_glow_intensity', '0.14');
        $gRgb = $hexToRgb($glowColor, [167, 173, 180]);

        // Tipografia Independente
        $rawTitle = \App\Models\Setting::get('custom_color_title');
        $rawSubtitle = \App\Models\Setting::get('custom_color_subtitle');
        $rawText = \App\Models\Setting::get('custom_color_text');
        $rawTextMuted = \App\Models\Setting::get('custom_color_text_muted');

        $effTitleColor = !empty($rawTitle) ? $rawTitle : (!empty($rawText) ? $rawText : $activeThemeDef['title']);
        $effSubtitleColor = !empty($rawSubtitle) ? $rawSubtitle : (!empty($rawTextMuted) ? $rawTextMuted : $activeThemeDef['subtitle']);
        $effTextBody = !empty($rawText) ? $rawText : $activeThemeDef['text'];
        $effTextMuted = !empty($rawTextMuted) ? $rawTextMuted : $activeThemeDef['text_muted'];

        // Ícones Independentes
        $rawIconDecor = \App\Models\Setting::get('custom_color_icon_decor');
        $rawIconNav = \App\Models\Setting::get('custom_color_icon_nav');
        $rawLegacyPrimary = \App\Models\Setting::get('custom_color_primary');

        $effIconDecor = !empty($rawIconDecor) ? $rawIconDecor : (!empty($rawLegacyPrimary) ? $rawLegacyPrimary : $activeThemeDef['icon_decor']);
        $effIconNav = !empty($rawIconNav) ? $rawIconNav : $activeThemeDef['icon_nav'];

        // Links Independentes
        $rawLink = \App\Models\Setting::get('custom_color_link');
        $rawLinkHover = \App\Models\Setting::get('custom_color_link_hover');
        $rawLegacyAccent = \App\Models\Setting::get('custom_color_accent');

        $effLinkColor = !empty($rawLink) ? $rawLink : $activeThemeDef['link'];
        $effLinkHover = !empty($rawLinkHover) ? $rawLinkHover : (!empty($rawLegacyAccent) ? $rawLegacyAccent : $activeThemeDef['link_hover']);

        // Botões Independentes
        $rawBtnPrimaryBg = \App\Models\Setting::get('custom_color_btn_primary_bg');
        $rawBtnPrimaryText = \App\Models\Setting::get('custom_color_btn_primary_text');
        $rawBtnPrimaryBorder = \App\Models\Setting::get('custom_color_btn_primary_border');
        $rawBtnSecondaryBg = \App\Models\Setting::get('custom_color_btn_secondary_bg');
        $rawBtnSecondaryText = \App\Models\Setting::get('custom_color_btn_secondary_text');
        $rawBtnSecondaryBorder = \App\Models\Setting::get('custom_color_btn_secondary_border');

        $effBtnPrimaryBg = !empty($rawBtnPrimaryBg) ? $rawBtnPrimaryBg : (!empty($rawLegacyPrimary) ? $rawLegacyPrimary : $activeThemeDef['btn_primary_bg']);
        $effBtnPrimaryText = !empty($rawBtnPrimaryText) ? $rawBtnPrimaryText : $calcContrastColor($effBtnPrimaryBg);
        $effBtnPrimaryBorder = !empty($rawBtnPrimaryBorder) ? $rawBtnPrimaryBorder : (!empty($rawLegacyAccent) ? $rawLegacyAccent : $activeThemeDef['btn_primary_border']);

        $effBtnSecondaryBg = !empty($rawBtnSecondaryBg) ? $rawBtnSecondaryBg : $activeThemeDef['btn_secondary_bg'];
        $effBtnSecondaryText = !empty($rawBtnSecondaryText) ? $rawBtnSecondaryText : $activeThemeDef['btn_secondary_text'];
        $effBtnSecondaryBorder = !empty($rawBtnSecondaryBorder) ? $rawBtnSecondaryBorder : $activeThemeDef['btn_secondary_border'];

        // Bordas & Divisores
        $rawBorder = \App\Models\Setting::get('custom_color_border');
        $rawBorderSubtle = \App\Models\Setting::get('custom_color_border_subtle');
        $effBorderColor = !empty($rawBorder) ? $rawBorder : $activeThemeDef['border'];
        $effBorderSubtle = !empty($rawBorderSubtle) ? $rawBorderSubtle : $activeThemeDef['border_subtle'];

        // Estados Interativos
        $rawHoverBg = \App\Models\Setting::get('custom_color_hover_bg');
        $rawFocusRing = \App\Models\Setting::get('custom_color_focus_ring');
        $rawDisabled = \App\Models\Setting::get('custom_color_disabled');
        $effHoverBg = !empty($rawHoverBg) ? $rawHoverBg : $activeThemeDef['hover_bg'];
        $effFocusRing = !empty($rawFocusRing) ? $rawFocusRing : $activeThemeDef['focus_ring'];
        $effDisabledColor = !empty($rawDisabled) ? $rawDisabled : $activeThemeDef['disabled'];

        // Mensagens & Status
        $effColorSuccess = \App\Models\Setting::get('custom_color_success', '#2E7D32');
        $effColorWarning = \App\Models\Setting::get('custom_color_warning', '#D97706');
        $effColorError = \App\Models\Setting::get('custom_color_error', '#DC2626');

        // Destaque de Preços e Descontos
        $rawPrice = \App\Models\Setting::get('custom_color_price');
        $rawDiscount = \App\Models\Setting::get('custom_color_discount');
        $effPriceColor = !empty($rawPrice) ? $rawPrice : $activeThemeDef['title'];
        $effDiscountColor = !empty($rawDiscount) ? $rawDiscount : '#737B85';

        // Etiquetas e Badges
        $rawBadgeBg = \App\Models\Setting::get('custom_color_badge_bg');
        $rawBadgeText = \App\Models\Setting::get('custom_color_badge_text');
        $rawBadgeBorder = \App\Models\Setting::get('custom_color_badge_border');
        $effBadgeBg = !empty($rawBadgeBg) ? $rawBadgeBg : 'rgba(167, 173, 180, 0.18)';
        $effBadgeText = !empty($rawBadgeText) ? $rawBadgeText : $activeThemeDef['text'];
        $effBadgeBorder = !empty($rawBadgeBorder) ? $rawBadgeBorder : 'rgba(167, 173, 180, 0.35)';

        // Campos de Formulário & Inputs
        $rawInputBg = \App\Models\Setting::get('custom_color_input_bg');
        $rawInputBorder = \App\Models\Setting::get('custom_color_input_border');
        $rawInputText = \App\Models\Setting::get('custom_color_input_text');
        $effInputBg = !empty($rawInputBg) ? $rawInputBg : '#FFFFFF';
        $effInputBorder = !empty($rawInputBorder) ? $rawInputBorder : 'rgba(115, 123, 133, 0.28)';
        $effInputText = !empty($rawInputText) ? $rawInputText : $activeThemeDef['text'];

        // Header, Footer & Top Banner
        $rawHeaderBg = \App\Models\Setting::get('header_bg_color');
        $rawHeaderText = \App\Models\Setting::get('header_text_color');
        $effHeaderBg = !empty($rawHeaderBg) ? $rawHeaderBg : $activeThemeDef['header_bg'];
        $effHeaderText = !empty($rawHeaderText) ? $rawHeaderText : $activeThemeDef['header_text'];

        $rawFooterBg = \App\Models\Setting::get('footer_bg_color');
        $rawFooterText = \App\Models\Setting::get('footer_text_color');
        $effFooterBg = !empty($rawFooterBg) ? $rawFooterBg : $activeThemeDef['footer_bg'];
        $effFooterText = !empty($rawFooterText) ? $rawFooterText : $activeThemeDef['footer_text'];

        $rawTopBannerBg = \App\Models\Setting::get('top_banner_bg_color');
        $rawTopBannerText = \App\Models\Setting::get('top_banner_text_color');
        $effTopBannerBg = !empty($rawTopBannerBg) ? $rawTopBannerBg : '#252525';
        $effTopBannerText = !empty($rawTopBannerText) ? $rawTopBannerText : '#FFFFFF';

        // Tipografia e Formas
        $dynSerif = \App\Models\Setting::get('custom_font_serif');
        $dynSans = \App\Models\Setting::get('custom_font_sans');
        $dynBtnRadius = \App\Models\Setting::get('theme_btn_border_radius', \App\Models\Setting::get('custom_border_radius', ''));
        $dynBtnTransform = \App\Models\Setting::get('theme_btn_text_transform', '');
        $dynHeroSize = \App\Models\Setting::get('hero_title_size', '');
        $dynBodySize = \App\Models\Setting::get('body_font_size', '');
        $dynSpacingY = \App\Models\Setting::get('section_spacing_y', '');
        $dynAlign = \App\Models\Setting::get('editorial_text_alignment', '');

        // -------------------------------------------------------------------------
        // 3. GERADOR DE REGRAS DE FUNDO & TEXTURA POR ÁREA (MODOS MUTUAMENTE EXCLUSIVOS)
        // -------------------------------------------------------------------------
        $renderAreaBgCss = function($selector, $areaKey, $isGlobal = false) use ($effBgMain) {
            $mode = \App\Models\Setting::get("{$areaKey}_bg_mode");
            $color = \App\Models\Setting::get("{$areaKey}_bg_color");
            $image = \App\Models\Setting::get("{$areaKey}_bg_image");
            $overlay = (float)\App\Models\Setting::get("{$areaKey}_bg_overlay", ($areaKey === 'hero' ? '0.60' : '0.75'));
            $repeat = \App\Models\Setting::get("{$areaKey}_bg_repeat", 'cover');
            $position = \App\Models\Setting::get("{$areaKey}_bg_position", 'center center');
            $texture = \App\Models\Setting::get("{$areaKey}_bg_texture");
            $textureOpacity = (float)\App\Models\Setting::get("{$areaKey}_bg_texture_opacity", '0.85');

            // Inferência inteligente para preservar dados legados
            if ($areaKey === 'site' && empty($color) && !empty($rawSiteBg)) {
                $color = $rawSiteBg;
            }
            if (empty($mode)) {
                if (!empty($image)) $mode = 'image';
                elseif (!empty($texture)) $mode = 'texture';
                elseif (!empty($color)) $mode = 'color';
                else $mode = $isGlobal ? 'color' : 'inherit';
            }

            $rules = "";
            if ($mode === 'color') {
                $chosenCol = !empty($color) ? $color : ($isGlobal ? $effBgMain : '');
                if (!empty($chosenCol)) {
                    $rules .= "{$selector} { background-color: {$chosenCol} !important; background-image: none !important; }\n";
                }
            } elseif ($mode === 'image' && !empty($image)) {
                $safeImg = \App\Core\View::e($image);
                $chosenCol = !empty($color) ? $color : ($isGlobal ? $effBgMain : 'transparent');
                $size = ($repeat === 'repeat') ? 'auto' : ($repeat === 'contain' ? 'contain' : 'cover');
                $repVal = ($repeat === 'repeat') ? 'repeat' : 'no-repeat';
                $attach = ($areaKey === 'site' || $areaKey === 'product' || $areaKey === 'checkout') ? 'fixed' : 'scroll';
                $rules .= "{$selector} {
                    background-color: {$chosenCol} !important;
                    background-image: linear-gradient(rgba(30,30,30, {$overlay}), rgba(30,30,30, {$overlay})), url('{$safeImg}') !important;
                    background-size: {$size} !important;
                    background-repeat: {$repVal} !important;
                    background-position: {$position} !important;
                    background-attachment: {$attach} !important;
                }\n";
            } elseif ($mode === 'texture' && !empty($texture)) {
                $safeTex = \App\Core\View::e($texture);
                $baseBg = !empty($color) ? $color : ($isGlobal ? $effBgMain : 'transparent');
                $rules .= "{$selector} {
                    background-color: {$baseBg} !important;
                    background-image: linear-gradient(rgba(248,248,247, " . (1 - $textureOpacity) . "), rgba(248,248,247, " . (1 - $textureOpacity) . ")), url('{$safeTex}') !important;
                    background-repeat: repeat !important;
                    background-size: auto !important;
                    background-position: center center !important;
                }\n";
            } elseif ($mode === 'inherit' && !$isGlobal) {
                // Sem personalização específica: herda transparentemente o fundo global
            }
            return $rules;
        };
    ?>
    <style id="divinium-design-tokens">
        :root, html, body.luxury-theme {
            <?php if (!empty($dynSerif)): ?>--font-serif: <?= $dynSerif ?> !important;<?php endif; ?>
            <?php if (!empty($dynSans)): ?>--font-sans: <?= $dynSans ?> !important;<?php endif; ?>

            /* 1. FUNDOS & SUPERFÍCIES INDEPENDENTES */
            --bg-main: <?= $effBgMain ?> !important;
            --bg-alt: <?= $effBgAlt ?> !important;
            --surface-card: <?= $effSurfaceCard ?> !important;
            --surface-box: <?= $effSurfaceBox ?> !important;

            /* Mapeamentos legados para retrocompatibilidade */
            --bg-light: var(--bg-main) !important;
            --bg-white: var(--surface-card) !important;
            --card-bg: var(--surface-card) !important;
            --bg-dark-card: var(--surface-card) !important;
            --surface-dark: #252525 !important;
            --bg-dark: #252525 !important;
            --surface-alt: var(--bg-alt) !important;

            /* 2. TIPOGRAFIA INDEPENDENTE */
            --title-color: <?= $effTitleColor ?> !important;
            --subtitle-color: <?= $effSubtitleColor ?> !important;
            --text-body: <?= $effTextBody ?> !important;
            --text-muted: <?= $effTextMuted ?> !important;

            /* Mapeamentos legados de texto */
            --text-primary: var(--text-body) !important;
            --text-secondary: var(--text-muted) !important;
            --text-light: var(--text-body) !important;
            --text-light-muted: var(--text-muted) !important;

            /* 3. ÍCONES INDEPENDENTES */
            --icon-decor-color: <?= $effIconDecor ?> !important;
            --icon-nav-color: <?= $effIconNav ?> !important;

            /* 4. LINKS & INTERAÇÃO */
            --link-color: <?= $effLinkColor ?> !important;
            --link-hover: <?= $effLinkHover ?> !important;

            /* 5. BOTÕES INDEPENDENTES */
            --btn-primary-bg: <?= $effBtnPrimaryBg ?> !important;
            --btn-primary-text: <?= $effBtnPrimaryText ?> !important;
            --btn-primary-border: <?= $effBtnPrimaryBorder ?> !important;
            --btn-secondary-bg: <?= $effBtnSecondaryBg ?> !important;
            --btn-secondary-text: <?= $effBtnSecondaryText ?> !important;
            --btn-secondary-border: <?= $effBtnSecondaryBorder ?> !important;

            /* Mapeamentos metálicos legados */
            --gold-accent: var(--icon-decor-color) !important;
            --gold-hover: var(--link-hover) !important;
            --silver-accent: var(--icon-decor-color) !important;
            --silver-hover: var(--link-hover) !important;
            --primary-color: var(--btn-primary-bg) !important;
            --accent-color: var(--btn-primary-border) !important;

            /* 6. BORDAS & DIVISORES */
            --border-color: <?= $effBorderColor ?> !important;
            --border-subtle: <?= $effBorderSubtle ?> !important;
            --border-light: var(--border-subtle) !important;
            --border-medium: var(--border-color) !important;
            --border-gold: var(--btn-primary-border) !important;

            /* 7. ESTADOS INTERATIVOS */
            --hover-bg: <?= $effHoverBg ?> !important;
            --focus-ring: <?= $effFocusRing ?> !important;
            --disabled-color: <?= $effDisabledColor ?> !important;

            /* 8. MENSAGENS & STATUS */
            --color-success: <?= $effColorSuccess ?> !important;
            --color-warning: <?= $effColorWarning ?> !important;
            --color-error: <?= $effColorError ?> !important;

            /* 9. CABEÇALHO, RODAPÉ & TOP BANNER */
            --header-bg: <?= $effHeaderBg ?> !important;
            --header-text: <?= $effHeaderText ?> !important;
            --footer-bg: <?= $effFooterBg ?> !important;
            --footer-text: <?= $effFooterText ?> !important;
            --top-banner-bg: <?= $effTopBannerBg ?> !important;
            --top-banner-text: <?= $effTopBannerText ?> !important;

            /* 10. PREÇOS, DESCONTOS, BADGES & FORMULÁRIOS */
            --price-color: <?= $effPriceColor ?> !important;
            --discount-color: <?= $effDiscountColor ?> !important;
            --badge-bg: <?= $effBadgeBg ?> !important;
            --badge-text: <?= $effBadgeText ?> !important;
            --badge-border: <?= $effBadgeBorder ?> !important;
            --input-bg: <?= $effInputBg ?> !important;
            --input-border: <?= $effInputBorder ?> !important;
            --input-text: <?= $effInputText ?> !important;
        }

        /* -------------------------------------------------------------------------
           APLICAÇÃO DOS TOKENS INDEPENDENTES NAS INTERFACES
           ------------------------------------------------------------------------- */
        body.luxury-theme {
            background-color: var(--bg-main) !important;
            color: var(--text-body) !important;
        }

        /* TÍTULOS PRINCIPAIS (REGRA ESTRITA: AFETA APENAS TÍTULOS) */
        body.luxury-theme h1,
        body.luxury-theme h2,
        body.luxury-theme h3,
        body.luxury-theme h4,
        body.luxury-theme h5,
        body.luxury-theme h6,
        body.luxury-theme .hero-title,
        body.luxury-theme .showcase-title,
        body.luxury-theme .services-title,
        body.luxury-theme .testimonials-title,
        body.luxury-theme .store-hero-title,
        body.luxury-theme .product-title-large,
        body.luxury-theme .product-title-heading,
        body.luxury-theme .product-price-main,
        body.luxury-theme .price-current-large,
        body.luxury-theme .institutional-title {
            color: var(--title-color) !important;
        }

        /* SUBTÍTULOS INDEPENDENTES */
        body.luxury-theme .hero-subtitle,
        body.luxury-theme .store-hero-subtitle,
        body.luxury-theme .institutional-subtitle,
        body.luxury-theme .services-subtitle,
        body.luxury-theme .testimonials-subtitle,
        body.luxury-theme .product-subtitle,
        body.luxury-theme .page-subtitle {
            color: var(--subtitle-color) !important;
        }

        /* TEXTOS DE CORPO, DESCRIÇÕES & MUTED */
        body.luxury-theme p,
        body.luxury-theme .editorial-desc,
        body.luxury-theme .product-storytelling-text,
        body.luxury-theme .spec-term,
        body.luxury-theme .guarantee-desc {
            color: var(--text-muted) !important;
        }

        /* ÍCONES DECORATIVOS (SPARKLES, SELOS, ESTRELAS, BADGES) */
        body.luxury-theme .badge-sparkle,
        body.luxury-theme .seal-sparkle,
        body.luxury-theme .trust-dot,
        body.luxury-theme .shopee-dot,
        body.luxury-theme .service-card-icon,
        body.luxury-theme .badge-metal {
            color: var(--icon-decor-color) !important;
            fill: var(--icon-decor-color) !important;
        }

        /* ÍCONES FUNCIONAIS & DE NAVEGAÇÃO */
        body.luxury-theme .action-btn svg,
        body.luxury-theme .header-action-btn svg,
        body.luxury-theme .store-search-box svg,
        body.luxury-theme .input-icon-wrap svg,
        body.luxury-theme .modal-close-btn svg,
        body.luxury-theme .btn-card-discreet svg {
            color: var(--icon-nav-color) !important;
            stroke: var(--icon-nav-color) !important;
        }

        /* BARRAS DO ÍCONE DE MENU MOBILE (HAMBÚRGUER) */
        body.luxury-theme .mobile-menu-toggle span {
            background: var(--header-text, var(--icon-nav-color, #202020)) !important;
            background-color: var(--header-text, var(--icon-nav-color, #202020)) !important;
        }

        /* LINKS INDEPENDENTES */
        body.luxury-theme a.nav-link,
        body.luxury-theme .nav-link,
        body.luxury-theme .breadcrumb-link,
        body.luxury-theme .footer-col a {
            color: var(--link-color) !important;
        }
        body.luxury-theme a.nav-link:hover,
        body.luxury-theme .nav-link:hover,
        body.luxury-theme .breadcrumb-link:hover,
        body.luxury-theme .footer-col a:hover {
            color: var(--link-hover) !important;
        }

        /* BOTÕES PRINCIPAIS */
        body.luxury-theme .btn-hero-gold,
        body.luxury-theme .btn-gold,
        body.luxury-theme .stage-buy-btn,
        body.luxury-theme .btn-checkout-primary,
        body.luxury-theme .btn-checkout-submit,
        body.luxury-theme .btn-card-explore,
        body.luxury-theme .btn-store-back,
        body.luxury-theme .btn-concierge-help,
        body.luxury-theme .btn-col-cta-lifestyle,
        body.luxury-theme .btn-add-cart,
        body.luxury-theme .store-filter-pill.active {
            background: var(--btn-primary-bg) !important;
            background-color: var(--btn-primary-bg) !important;
            color: var(--btn-primary-text) !important;
            border-color: var(--btn-primary-border) !important;
            font-weight: 700 !important;
        }
        body.luxury-theme .btn-hero-gold:hover,
        body.luxury-theme .btn-gold:hover,
        body.luxury-theme .stage-buy-btn:hover,
        body.luxury-theme .btn-checkout-primary:hover,
        body.luxury-theme .btn-checkout-submit:hover,
        body.luxury-theme .btn-card-explore:hover,
        body.luxury-theme .btn-add-cart:hover {
            opacity: 0.92 !important;
            transform: translateY(-1px);
        }

        /* BOTÕES SECUNDÁRIOS & CANCELAR */
        body.luxury-theme .btn-secondary,
        body.luxury-theme .btn-admin-cancel,
        body.luxury-theme .btn-outline {
            background: var(--btn-secondary-bg) !important;
            background-color: var(--btn-secondary-bg) !important;
            color: var(--btn-secondary-text) !important;
            border-color: var(--btn-secondary-border) !important;
        }

        /* FOCO, HOVER, SELEÇÃO E DESATIVADOS */
        body.luxury-theme button:focus,
        body.luxury-theme a:focus,
        body.luxury-theme input:focus {
            outline: 2px solid var(--focus-ring) !important;
            outline-offset: 2px !important;
        }
        body.luxury-theme button:disabled,
        body.luxury-theme .disabled {
            color: var(--disabled-color) !important;
            opacity: 0.6 !important;
            cursor: not-allowed !important;
        }
        ::selection {
            background: var(--focus-ring) !important;
            color: #FFFFFF !important;
        }

        /* SUPERFÍCIES, CARDS & CAIXAS */
        body.luxury-theme .showcase-lifestyle-card,
        body.luxury-theme .service-card,
        body.luxury-theme .testimonial-card,
        body.luxury-theme .store-product-card,
        body.luxury-theme .reason-card,
        body.luxury-theme .related-product-card,
        body.luxury-theme .product-gallery-card,
        body.luxury-theme .product-info-panel,
        body.luxury-theme .checkout-card,
        body.luxury-theme .checkout-summary-card,
        body.luxury-theme .institutional-card {
            background-color: var(--surface-card) !important;
            border-color: var(--border-color) !important;
        }

        /* BADGES & ETIQUETAS */
        body.luxury-theme .badge-tag,
        body.luxury-theme .product-badge,
        body.luxury-theme .checkout-badge,
        body.luxury-theme .variations-count-badge {
            background: var(--badge-bg) !important;
            background-color: var(--badge-bg) !important;
            color: var(--badge-text) !important;
            border-color: var(--badge-border) !important;
        }

        /* CAMPOS DE FORMULÁRIO */
        body.luxury-theme .form-input,
        body.luxury-theme .form-textarea,
        body.luxury-theme .form-select {
            background-color: var(--input-bg) !important;
            border-color: var(--input-border) !important;
            color: var(--input-text) !important;
        }

        /* DESTAQUE DE PREÇO & DESCONTO */
        body.luxury-theme .product-price-box .price,
        body.luxury-theme .related-card-price span,
        body.luxury-theme .channel-price-val,
        body.luxury-theme #summaryGrandTotal {
            color: var(--price-color) !important;
        }
        body.luxury-theme .opt-discount-tag,
        body.luxury-theme #summaryPixRow span {
            color: var(--discount-color) !important;
        }

        /* CABEÇALHO, RODAPÉ & TOP BANNER */
        body.luxury-theme .main-header {
            background-color: var(--header-bg) !important;
            border-bottom: 1px solid var(--border-subtle) !important;
        }
        body.luxury-theme .main-header .brand-name,
        body.luxury-theme .main-header .logo-text,
        body.luxury-theme .main-header .nav-link,
        body.luxury-theme .main-header .action-btn {
            color: var(--header-text) !important;
        }
        body.luxury-theme .main-footer {
            background-color: var(--footer-bg) !important;
            border-top: 1px solid var(--border-subtle) !important;
        }
        body.luxury-theme .main-footer,
        body.luxury-theme .main-footer p,
        body.luxury-theme .main-footer a,
        body.luxury-theme .main-footer .footer-col-title,
        body.luxury-theme .main-footer .footer-bottom {
            color: var(--footer-text) !important;
        }
        body.luxury-theme .top-announcement-bar {
            background-color: var(--top-banner-bg) !important;
            color: var(--top-banner-text) !important;
            border-bottom: 1px solid var(--border-subtle) !important;
        }

        /* -------------------------------------------------------------------------
           REGRAS DINÂMICAS DE FUNDO & TEXTURA POR ÁREA (MODOS MUTUAMENTE EXCLUSIVOS)
           ------------------------------------------------------------------------- */
        <?= $renderAreaBgCss('body.luxury-theme', 'site', true) ?>
        <?= $renderAreaBgCss('body.luxury-theme.page-home', 'home') ?>
        <?= $renderAreaBgCss('.hero-section', 'hero') ?>
        <?= $renderAreaBgCss('body.luxury-theme.page-store, .store-hero-section', 'store') ?>
        <?= $renderAreaBgCss('body.luxury-theme.page-product, .product-detail-page', 'product') ?>
        <?= $renderAreaBgCss('body.luxury-theme.page-checkout, .checkout-page-container', 'checkout') ?>
        <?= $renderAreaBgCss('body.luxury-theme.page-institutional, .institutional-page-container', 'institutional') ?>
        <?= $renderAreaBgCss('body.luxury-theme .main-header', 'header') ?>
        <?= $renderAreaBgCss('body.luxury-theme .main-footer', 'footer') ?>
        <?= $renderAreaBgCss('body.luxury-theme .top-announcement-bar', 'top_banner') ?>

        /* Sobrescrita de Cards específicos por página (quando configurado) */
        <?php if (!empty(\App\Models\Setting::get('home_card_bg'))): ?>
        body.luxury-theme.page-home .showcase-lifestyle-card,
        body.luxury-theme.page-home .service-card,
        body.luxury-theme.page-home .testimonial-card {
            background-color: <?= \App\Core\View::e(\App\Models\Setting::get('home_card_bg')) ?> !important;
        }
        <?php endif; ?>
        /* =========================================================================
           CORES EXCLUSIVAS DOS CARDS DE PRODUTOS DA BOUTIQUE (/loja)
           ========================================================================= */
        <?php
            $stCardBg = \App\Models\Setting::get('store_card_bg', '');
            $stCardBorder = \App\Models\Setting::get('store_card_border_color', '');
            $stCardHoverBorder = \App\Models\Setting::get('store_card_hover_border_color', '');
            $stCardMediaBg = \App\Models\Setting::get('store_card_media_bg', '');
            $stCardTitleColor = \App\Models\Setting::get('store_card_title_color', '');
            $stCardTitleHover = \App\Models\Setting::get('store_card_title_hover_color', '');
            $stCardPriceColor = \App\Models\Setting::get('store_card_price_color', '');
            $stCardPricePrefix = \App\Models\Setting::get('store_card_price_prefix_color', '');
            $stCardBadgeBg = \App\Models\Setting::get('store_card_badge_bg', '');
            $stCardBadgeColor = \App\Models\Setting::get('store_card_badge_color', '');
            $stCardBadgeBorder = \App\Models\Setting::get('store_card_badge_border', '');
            $stCardBtnBg = \App\Models\Setting::get('store_card_btn_bg', '');
            $stCardBtnColor = \App\Models\Setting::get('store_card_btn_color', '');
            $stCardBtnBorder = \App\Models\Setting::get('store_card_btn_border', '');
            $stCardBtnHoverBg = \App\Models\Setting::get('store_card_btn_hover_bg', '');
            $stCardBtnHoverColor = \App\Models\Setting::get('store_card_btn_hover_color', '');
            $stCardBtnHoverBorder = \App\Models\Setting::get('store_card_btn_hover_border', '');
            $stCardOverlayBg = \App\Models\Setting::get('store_card_overlay_btn_bg', '');
            $stCardOverlayColor = \App\Models\Setting::get('store_card_overlay_btn_color', '');
        ?>
        <?php if (!empty($stCardBg)): ?>
        body.luxury-theme.page-store .store-product-card,
        body.luxury-theme.page-store .reason-card,
        .page-store .store-product-card {
            background: <?= \App\Core\View::e($stCardBg) ?> !important;
            background-color: <?= \App\Core\View::e($stCardBg) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($stCardBorder)): ?>
        body.luxury-theme.page-store .store-product-card,
        .page-store .store-product-card {
            border-color: <?= \App\Core\View::e($stCardBorder) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($stCardHoverBorder)): ?>
        body.luxury-theme.page-store .store-product-card:hover,
        .page-store .store-product-card:hover {
            border-color: <?= \App\Core\View::e($stCardHoverBorder) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($stCardMediaBg)): ?>
        body.luxury-theme.page-store .store-card-media,
        .page-store .store-card-media {
            background: <?= \App\Core\View::e($stCardMediaBg) ?> !important;
            background-color: <?= \App\Core\View::e($stCardMediaBg) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($stCardTitleColor)): ?>
        body.luxury-theme.page-store .store-card-title-clean a,
        .page-store .store-card-title-clean a {
            color: <?= \App\Core\View::e($stCardTitleColor) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($stCardTitleHover)): ?>
        body.luxury-theme.page-store .store-card-title-clean a:hover,
        .page-store .store-card-title-clean a:hover {
            color: <?= \App\Core\View::e($stCardTitleHover) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($stCardPriceColor)): ?>
        body.luxury-theme.page-store .store-card-price-clean,
        body.luxury-theme.page-store .store-card-price-clean span:not(.price-prefix),
        .page-store .store-card-price-clean,
        .page-store .store-card-price-clean span:not(.price-prefix) {
            color: <?= \App\Core\View::e($stCardPriceColor) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($stCardPricePrefix)): ?>
        body.luxury-theme.page-store .store-card-price-clean .price-prefix,
        .page-store .store-card-price-clean .price-prefix {
            color: <?= \App\Core\View::e($stCardPricePrefix) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($stCardBadgeBg) || !empty($stCardBadgeColor) || !empty($stCardBadgeBorder)): ?>
        body.luxury-theme.page-store .store-product-card .badge-metal,
        .page-store .store-product-card .badge-metal {
            <?php if (!empty($stCardBadgeBg)): ?>background: <?= \App\Core\View::e($stCardBadgeBg) ?> !important; background-color: <?= \App\Core\View::e($stCardBadgeBg) ?> !important;<?php endif; ?>
            <?php if (!empty($stCardBadgeColor)): ?>color: <?= \App\Core\View::e($stCardBadgeColor) ?> !important;<?php endif; ?>
            <?php if (!empty($stCardBadgeBorder)): ?>border-color: <?= \App\Core\View::e($stCardBadgeBorder) ?> !important;<?php endif; ?>
        }
        <?php endif; ?>
        <?php if (!empty($stCardBtnBg) || !empty($stCardBtnColor) || !empty($stCardBtnBorder)): ?>
        body.luxury-theme.page-store .store-product-card .btn-card-discreet,
        .page-store .store-product-card .btn-card-discreet {
            <?php if (!empty($stCardBtnBg)): ?>background: <?= \App\Core\View::e($stCardBtnBg) ?> !important; background-color: <?= \App\Core\View::e($stCardBtnBg) ?> !important;<?php endif; ?>
            <?php if (!empty($stCardBtnColor)): ?>color: <?= \App\Core\View::e($stCardBtnColor) ?> !important;<?php endif; ?>
            <?php if (!empty($stCardBtnBorder)): ?>border-color: <?= \App\Core\View::e($stCardBtnBorder) ?> !important;<?php endif; ?>
        }
        <?php endif; ?>
        <?php if (!empty($stCardBtnHoverBg) || !empty($stCardBtnHoverColor) || !empty($stCardBtnHoverBorder)): ?>
        body.luxury-theme.page-store .store-product-card .btn-card-discreet:hover,
        .page-store .store-product-card .btn-card-discreet:hover {
            <?php if (!empty($stCardBtnHoverBg)): ?>background: <?= \App\Core\View::e($stCardBtnHoverBg) ?> !important; background-color: <?= \App\Core\View::e($stCardBtnHoverBg) ?> !important;<?php endif; ?>
            <?php if (!empty($stCardBtnHoverColor)): ?>color: <?= \App\Core\View::e($stCardBtnHoverColor) ?> !important;<?php endif; ?>
            <?php if (!empty($stCardBtnHoverBorder)): ?>border-color: <?= \App\Core\View::e($stCardBtnHoverBorder) ?> !important;<?php endif; ?>
        }
        <?php endif; ?>
        <?php if (!empty($stCardOverlayBg) || !empty($stCardOverlayColor)): ?>
        body.luxury-theme.page-store .store-product-card .overlay-cta-text,
        .page-store .store-product-card .overlay-cta-text {
            <?php if (!empty($stCardOverlayBg)): ?>background: <?= \App\Core\View::e($stCardOverlayBg) ?> !important; background-color: <?= \App\Core\View::e($stCardOverlayBg) ?> !important;<?php endif; ?>
            <?php if (!empty($stCardOverlayColor)): ?>color: <?= \App\Core\View::e($stCardOverlayColor) ?> !important;<?php endif; ?>
        }
        <?php endif; ?>

        /* TÍTULO DA SEÇÃO DE SEGURANÇA & CANAIS DA BOUTIQUE (TODO EM PRETO) */
        .store-reasons-section .reasons-title,
        .store-reasons-section .reasons-title *,
        body.luxury-theme .store-reasons-section .reasons-title,
        body.luxury-theme .store-reasons-section .reasons-title *,
        body.luxury-theme .store-reasons-section .reasons-title .gold-gradient-glow,
        .page-store .store-reasons-section .reasons-title,
        .page-store .store-reasons-section .reasons-title * {
            color: #000000 !important;
            -webkit-text-fill-color: #000000 !important;
            background: none !important;
            background-image: none !important;
        }
        <?php if (!empty(\App\Models\Setting::get('product_card_bg'))): ?>
        body.luxury-theme.page-product .product-gallery-card,
        body.luxury-theme.page-product .product-info-panel,
        body.luxury-theme.page-product .product-description-card {
            background-color: <?= \App\Core\View::e(\App\Models\Setting::get('product_card_bg')) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty(\App\Models\Setting::get('checkout_card_bg'))): ?>
        body.luxury-theme.page-checkout .checkout-card,
        body.luxury-theme.page-checkout .checkout-summary-card {
            background-color: <?= \App\Core\View::e(\App\Models\Setting::get('checkout_card_bg')) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty(\App\Models\Setting::get('institutional_card_bg'))): ?>
        body.luxury-theme.page-institutional .institutional-card {
            background-color: <?= \App\Core\View::e(\App\Models\Setting::get('institutional_card_bg')) ?> !important;
        }
        <?php endif; ?>
        /* =========================================================================
           CORES EXCLUSIVAS DO HERO BANNER PRINCIPAL (PALETA PERSONALIZADA)
           ========================================================================= */
        <?php
            $heroBgCol = \App\Models\Setting::get('custom_color_hero_bg', '');
            $heroTitleCol = \App\Models\Setting::get('custom_color_hero_title', '');
            $heroSubtitleCol = \App\Models\Setting::get('custom_color_hero_subtitle', '');
            $heroBadgeBgCol = \App\Models\Setting::get('custom_color_hero_badge_bg', '');
            $heroBadgeTextCol = \App\Models\Setting::get('custom_color_hero_badge_text', '');
            $heroBadgeBorderCol = \App\Models\Setting::get('custom_color_hero_badge_border', '');
            $heroBtnBgCol = \App\Models\Setting::get('custom_color_hero_btn_bg', '');
            $heroBtnTextCol = \App\Models\Setting::get('custom_color_hero_btn_text', '');
            $heroBtnSecTextCol = \App\Models\Setting::get('custom_color_hero_btn_sec_text', '');
            $heroTrustTextCol = \App\Models\Setting::get('custom_color_hero_trust_text', '');
        ?>
        <?php if (!empty($heroBgCol)): ?>
        .hero-section,
        body.luxury-theme .hero-section {
            background-color: <?= \App\Core\View::e($heroBgCol) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($heroTitleCol)): ?>
        body.luxury-theme .hero-section .hero-title,
        .hero-section .hero-title {
            color: <?= \App\Core\View::e($heroTitleCol) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($heroSubtitleCol)): ?>
        body.luxury-theme .hero-section .hero-subtitle,
        body.luxury-theme .hero-section .hero-subtitle strong,
        .hero-section .hero-subtitle,
        .hero-section .hero-subtitle strong {
            color: <?= \App\Core\View::e($heroSubtitleCol) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($heroBadgeBgCol) || !empty($heroBadgeTextCol) || !empty($heroBadgeBorderCol)): ?>
        body.luxury-theme .hero-section .hero-editorial-badge,
        .hero-section .hero-editorial-badge {
            <?php if (!empty($heroBadgeBgCol)): ?>background-color: <?= \App\Core\View::e($heroBadgeBgCol) ?> !important; background: <?= \App\Core\View::e($heroBadgeBgCol) ?> !important;<?php endif; ?>
            <?php if (!empty($heroBadgeTextCol)): ?>color: <?= \App\Core\View::e($heroBadgeTextCol) ?> !important;<?php endif; ?>
            <?php if (!empty($heroBadgeBorderCol)): ?>border-color: <?= \App\Core\View::e($heroBadgeBorderCol) ?> !important;<?php endif; ?>
        }
        <?php if (!empty($heroBadgeTextCol)): ?>
        body.luxury-theme .hero-section .hero-editorial-badge span,
        body.luxury-theme .hero-section .hero-editorial-badge .badge-sparkle,
        .hero-section .hero-editorial-badge span,
        .hero-section .hero-editorial-badge .badge-sparkle {
            color: <?= \App\Core\View::e($heroBadgeTextCol) ?> !important;
        }
        <?php endif; ?>
        <?php endif; ?>
        <?php if (!empty($heroBtnBgCol) || !empty($heroBtnTextCol)): ?>
        body.luxury-theme .hero-section .btn-hero-gold,
        .hero-section .btn-hero-gold {
            <?php if (!empty($heroBtnBgCol)): ?>background: <?= \App\Core\View::e($heroBtnBgCol) ?> !important; background-color: <?= \App\Core\View::e($heroBtnBgCol) ?> !important; border-color: <?= \App\Core\View::e($heroBtnBgCol) ?> !important;<?php endif; ?>
            <?php if (!empty($heroBtnTextCol)): ?>color: <?= \App\Core\View::e($heroBtnTextCol) ?> !important;<?php endif; ?>
        }
        <?php if (!empty($heroBtnTextCol)): ?>
        body.luxury-theme .hero-section .btn-hero-gold span,
        body.luxury-theme .hero-section .btn-hero-gold svg,
        .hero-section .btn-hero-gold span,
        .hero-section .btn-hero-gold svg {
            color: <?= \App\Core\View::e($heroBtnTextCol) ?> !important;
            stroke: <?= \App\Core\View::e($heroBtnTextCol) ?> !important;
        }
        <?php endif; ?>
        <?php endif; ?>
        <?php if (!empty($heroBtnSecTextCol)): ?>
        body.luxury-theme .hero-section .btn-hero-ghost,
        .hero-section .btn-hero-ghost {
            color: <?= \App\Core\View::e($heroBtnSecTextCol) ?> !important;
            border-color: <?= \App\Core\View::e($heroBtnSecTextCol) ?> !important;
        }
        body.luxury-theme .hero-section .btn-hero-ghost span,
        body.luxury-theme .hero-section .btn-hero-ghost svg,
        .hero-section .btn-hero-ghost span,
        .hero-section .btn-hero-ghost svg {
            color: <?= \App\Core\View::e($heroBtnSecTextCol) ?> !important;
            stroke: <?= \App\Core\View::e($heroBtnSecTextCol) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($heroTrustTextCol)): ?>
        body.luxury-theme .hero-section .hero-trust-strip .trust-badge,
        body.luxury-theme .hero-section .hero-trust-strip .trust-badge *,
        .hero-section .hero-trust-strip .trust-badge,
        .hero-section .hero-trust-strip .trust-badge * {
            color: <?= \App\Core\View::e($heroTrustTextCol) ?> !important;
        }
        <?php endif; ?>

        /* =========================================================================
           CORES EXCLUSIVAS DA ABA DA SACOLA (CART DRAWER)
           ========================================================================= */
        <?php
            $cartBgCol = \App\Models\Setting::get('custom_color_cart_bg', '');
            $cartFooterBgCol = \App\Models\Setting::get('custom_color_cart_footer_bg', '');
            $cartTitleCol = \App\Models\Setting::get('custom_color_cart_title', '');
            $cartItemTitleCol = \App\Models\Setting::get('custom_color_cart_item_title', '');
            $cartTextCol = \App\Models\Setting::get('custom_color_cart_text', '');
            $cartPriceCol = \App\Models\Setting::get('custom_color_cart_price', '');
            $cartBorderCol = \App\Models\Setting::get('custom_color_cart_border', '');
            $cartBtnBgCol = \App\Models\Setting::get('custom_color_cart_btn_bg', '');
            $cartBtnTextCol = \App\Models\Setting::get('custom_color_cart_btn_text', '');
        ?>
        <?php if (!empty($cartBgCol)): ?>
        .cart-drawer,
        .cart-drawer-body,
        body.luxury-theme .cart-drawer,
        body.luxury-theme .cart-drawer-body {
            background: <?= \App\Core\View::e($cartBgCol) ?> !important;
            background-color: <?= \App\Core\View::e($cartBgCol) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($cartFooterBgCol)): ?>
        .cart-drawer-header,
        .cart-drawer-footer,
        body.luxury-theme .cart-drawer-header,
        body.luxury-theme .cart-drawer-footer {
            background: <?= \App\Core\View::e($cartFooterBgCol) ?> !important;
            background-color: <?= \App\Core\View::e($cartFooterBgCol) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($cartTitleCol)): ?>
        .cart-drawer-header h3,
        .cart-drawer-header .close-drawer-btn,
        body.luxury-theme .cart-drawer-header h3,
        body.luxury-theme .cart-drawer-header .close-drawer-btn {
            color: <?= \App\Core\View::e($cartTitleCol) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($cartItemTitleCol)): ?>
        .cart-drawer .cart-item-row h4,
        #cartDrawerBody .cart-item-row h4,
        .cart-drawer .cart-item-row button[onclick*="changeCartItemQty"] + span,
        body.luxury-theme .cart-drawer .cart-item-row h4,
        body.luxury-theme #cartDrawerBody .cart-item-row h4 {
            color: <?= \App\Core\View::e($cartItemTitleCol) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($cartTextCol)): ?>
        .cart-drawer .cart-perks,
        .cart-drawer .cart-perks small,
        .cart-drawer .empty-cart-state p,
        .cart-drawer .cart-subtotal-row > span:first-child,
        .cart-drawer .cart-item-row span:not(.cart-item-price-val),
        .cart-drawer .cart-item-row button[onclick*="changeCartItemQty"],
        .cart-drawer .cart-item-row button[onclick*="removeFromCart"],
        .cart-drawer .btn-discover,
        #cartDrawerBody .cart-item-row span:not(.cart-item-price-val),
        body.luxury-theme .cart-drawer .cart-perks,
        body.luxury-theme .cart-drawer .empty-cart-state p {
            color: <?= \App\Core\View::e($cartTextCol) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($cartPriceCol)): ?>
        .cart-subtotal-row,
        .cart-subtotal-row .subtotal-val,
        #cartSubtotalVal,
        .cart-drawer .cart-item-row .cart-item-price-val,
        #cartDrawerBody .cart-item-row div > span:last-child,
        body.luxury-theme .cart-subtotal-row,
        body.luxury-theme .cart-subtotal-row .subtotal-val,
        body.luxury-theme #cartSubtotalVal {
            color: <?= \App\Core\View::e($cartPriceCol) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($cartBorderCol)): ?>
        .cart-drawer-header,
        .cart-drawer-footer,
        .cart-drawer .cart-item-row,
        .cart-drawer .btn-discover,
        #cartDrawerBody .cart-item-row,
        body.luxury-theme .cart-drawer-header,
        body.luxury-theme .cart-drawer-footer {
            border-color: <?= \App\Core\View::e($cartBorderCol) ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($cartBtnBgCol) || !empty($cartBtnTextCol)): ?>
        .cart-drawer .btn-checkout-primary,
        body.luxury-theme .cart-drawer .btn-checkout-primary {
            <?php if (!empty($cartBtnBgCol)): ?>background: <?= \App\Core\View::e($cartBtnBgCol) ?> !important; background-color: <?= \App\Core\View::e($cartBtnBgCol) ?> !important; border-color: <?= \App\Core\View::e($cartBtnBgCol) ?> !important;<?php endif; ?>
            <?php if (!empty($cartBtnTextCol)): ?>color: <?= \App\Core\View::e($cartBtnTextCol) ?> !important;<?php endif; ?>
        }
        <?php if (!empty($cartBtnTextCol)): ?>
        .cart-drawer .btn-checkout-primary span,
        .cart-drawer .btn-checkout-primary svg,
        body.luxury-theme .cart-drawer .btn-checkout-primary span,
        body.luxury-theme .cart-drawer .btn-checkout-primary svg {
            color: <?= \App\Core\View::e($cartBtnTextCol) ?> !important;
            stroke: <?= \App\Core\View::e($cartBtnTextCol) ?> !important;
        }
        <?php endif; ?>
        <?php endif; ?>


        /* -------------------------------------------------------------------------
           CORES PERSONALIZADAS: SEÇÃO EXPERIÊNCIA DE SERVIÇOS EXCLUSIVOS
           ------------------------------------------------------------------------- */
        <?php
            $servicesBg = \App\Models\Setting::get('services_bg_color', '');
            $servicesCardBg = \App\Models\Setting::get('services_card_bg', '');
            $servicesTitleColor = \App\Models\Setting::get('services_title_color', '');
            $servicesSubtitleColor = \App\Models\Setting::get('services_subtitle_color', '');
            $servicesTextColor = \App\Models\Setting::get('services_text_color', '');
            $servicesIconColor = \App\Models\Setting::get('services_icon_color', '');
            $servicesBorderColor = \App\Models\Setting::get('services_border_color', '');
            $servicesBtnBg = \App\Models\Setting::get('services_btn_bg', '');
            $servicesBtnText = \App\Models\Setting::get('services_btn_text', '');
            $servicesBtnBorder = \App\Models\Setting::get('services_btn_border', '');
        ?>
        <?php if (!empty($servicesBg)): ?>
        body.luxury-theme .signature-services-section,
        body.luxury-theme #services {
            background: <?= $servicesBg ?> !important;
            background-color: <?= $servicesBg ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($servicesCardBg)): ?>
        body.luxury-theme .signature-services-section .service-item {
            background-color: <?= $servicesCardBg ?> !important;
            padding: 28px 24px !important;
            border-radius: 10px !important;
            margin-bottom: 12px !important;
        }
        <?php endif; ?>
        <?php if (!empty($servicesTitleColor)): ?>
        body.luxury-theme .signature-services-section .services-main-title,
        body.luxury-theme .signature-services-section .service-name {
            color: <?= $servicesTitleColor ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($servicesSubtitleColor)): ?>
        body.luxury-theme .signature-services-section .services-subtitle {
            color: <?= $servicesSubtitleColor ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($servicesTextColor)): ?>
        body.luxury-theme .signature-services-section .service-details,
        body.luxury-theme .signature-services-section .service-details li,
        body.luxury-theme .signature-services-section p {
            color: <?= $servicesTextColor ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($servicesIconColor)): ?>
        body.luxury-theme .signature-services-section .service-num,
        body.luxury-theme .signature-services-section .service-details li::before,
        body.luxury-theme .signature-services-section .service-icon {
            color: <?= $servicesIconColor ?> !important;
            fill: <?= $servicesIconColor ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($servicesBorderColor)): ?>
        body.luxury-theme .signature-services-section .service-item {
            border-color: <?= $servicesBorderColor ?> !important;
        }
        <?php endif; ?>
        .signature-services-section .btn-services-cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 32px;
            border-radius: 30px;
            font-size: 0.88rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-decoration: none;
            transition: all 0.3s ease;
            background: <?= !empty($servicesBtnBg) ? $servicesBtnBg : 'var(--btn-primary-bg)' ?> !important;
            color: <?= !empty($servicesBtnText) ? $servicesBtnText : 'var(--btn-primary-text)' ?> !important;
            border: 1px solid <?= !empty($servicesBtnBorder) ? $servicesBtnBorder : 'var(--btn-primary-border)' ?> !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.25);
        }
        .signature-services-section .btn-services-cta:hover {
            transform: translateY(-2px);
            opacity: 0.92;
            box-shadow: 0 8px 24px rgba(0,0,0,0.35);
        }

        /* Tipografia, Alinhamento & Respiro Dinâmico */
        <?php if (!empty($dynBtnRadius)): ?>
        .btn-gold, .btn-secondary, .btn-discover, .btn-checkout-primary, .btn-product-channel, .btn-card-action, .cta-button, .about-btn, .hero-btn-primary, .hero-btn-secondary, .btn-hero-gold, .btn-card-explore, .stage-buy-btn, .btn-checkout-submit, .btn-store-back, .btn-concierge-help {
            border-radius: <?= $dynBtnRadius ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($dynBtnTransform)): ?>
        .btn-gold, .btn-secondary, .btn-discover, .btn-checkout-primary, .btn-product-channel, .btn-card-action, .cta-button, .about-btn, .hero-btn-primary, .hero-btn-secondary, .btn-hero-gold, .btn-card-explore, .stage-buy-btn, .btn-checkout-submit, .btn-store-back, .btn-concierge-help {
            text-transform: <?= $dynBtnTransform ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($dynHeroSize)): ?>
        .hero-content h1, .hero-title-main, .hero-title {
            font-size: <?= $dynHeroSize ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($dynBodySize)): ?>
        body, p, .editorial-desc {
            font-size: <?= $dynBodySize ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($dynSpacingY)): ?>
        .about-manifesto-section, .services-section, .heritage-section, .testimonials-section {
            padding-top: <?= $dynSpacingY ?> !important;
            padding-bottom: <?= $dynSpacingY ?> !important;
        }
        <?php endif; ?>
        <?php if (!empty($dynAlign)): ?>
        .manifesto-header, .section-header-editorial, .editorial-text-wrap {
            text-align: <?= $dynAlign ?> !important;
        }
        <?php endif; ?>

        /* -------------------------------------------------------------------------
           SOMBRAS PERSONALIZADAS (CARDS, SUPERFÍCIES & CABEÇALHO)
           ------------------------------------------------------------------------- */
        <?php if ($shadowsEnabled === '0' || $shadowsEnabled === 0): ?>
        :root, html, body.luxury-theme {
            --site-shadow-color: transparent !important;
            --shadow-sm: none !important;
            --shadow-md: none !important;
            --shadow-lg: none !important;
            --shadow-card: none !important;
            --surface-shadow: none !important;
            --card-shadow: none !important;
            --elevated-shadow: none !important;
        }
        body.luxury-theme .showcase-lifestyle-card,
        body.luxury-theme .service-card,
        body.luxury-theme .testimonial-card,
        body.luxury-theme .store-product-card,
        body.luxury-theme .reason-card,
        body.luxury-theme .related-product-card,
        body.luxury-theme .product-gallery-card,
        body.luxury-theme .product-info-panel,
        body.luxury-theme .checkout-card,
        body.luxury-theme .checkout-summary-card,
        body.luxury-theme .institutional-card,
        body.luxury-theme .main-header {
            box-shadow: none !important;
        }
        <?php else: ?>
        :root, html, body.luxury-theme {
            --site-shadow-color: <?= $shadowRgba ?> !important;
            --shadow-sm: 0 2px 6px <?= $shadowRgbaSubtle ?> !important;
            --shadow-md: 0 8px <?= $shadowBlur ?>px <?= $shadowRgba ?> !important;
            --shadow-lg: 0 16px <?= round($shadowBlur * 1.5) ?>px <?= $shadowRgbaDeep ?> !important;
            --shadow-card: 0 10px <?= $shadowBlur ?>px <?= $shadowRgba ?> !important;
            --surface-shadow: 0 8px <?= $shadowBlur ?>px <?= $shadowRgba ?> !important;
            --card-shadow: 0 10px <?= $shadowBlur ?>px <?= $shadowRgba ?> !important;
            --elevated-shadow: 0 18px <?= round($shadowBlur * 1.8) ?>px <?= $shadowRgbaDeep ?> !important;
        }
        body.luxury-theme .showcase-lifestyle-card,
        body.luxury-theme .service-card,
        body.luxury-theme .testimonial-card,
        body.luxury-theme .store-product-card,
        body.luxury-theme .reason-card,
        body.luxury-theme .related-product-card,
        body.luxury-theme .product-gallery-card,
        body.luxury-theme .product-info-panel,
        body.luxury-theme .checkout-card,
        body.luxury-theme .checkout-summary-card,
        body.luxury-theme .institutional-card {
            box-shadow: var(--card-shadow) !important;
        }
        body.luxury-theme .main-header {
            box-shadow: var(--shadow-sm) !important;
        }
        <?php endif; ?>

        /* -------------------------------------------------------------------------
           EFEITOS VISUAIS DE FUNDO (GLASSMORPHISM, VINHETA & AMBIENT GLOW)
           ------------------------------------------------------------------------- */
        <?php if ($glassEnabled === '0' || $glassEnabled === 0): ?>
        body.luxury-theme .main-header,
        body.luxury-theme .showcase-lifestyle-card,
        body.luxury-theme .service-card,
        body.luxury-theme .testimonial-card,
        body.luxury-theme .store-product-card,
        body.luxury-theme .checkout-card,
        body.luxury-theme .product-gallery-card,
        body.luxury-theme .product-info-panel,
        body.luxury-theme .institutional-card {
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }
        <?php else: ?>
        body.luxury-theme .main-header {
            backdrop-filter: blur(<?= $glassBlur ?>px) !important;
            -webkit-backdrop-filter: blur(<?= $glassBlur ?>px) !important;
        }
        body.luxury-theme .showcase-lifestyle-card,
        body.luxury-theme .service-card,
        body.luxury-theme .testimonial-card,
        body.luxury-theme .store-product-card,
        body.luxury-theme .checkout-card,
        body.luxury-theme .product-gallery-card,
        body.luxury-theme .product-info-panel,
        body.luxury-theme .institutional-card {
            backdrop-filter: blur(<?= round($glassBlur * 0.7) ?>px) !important;
            -webkit-backdrop-filter: blur(<?= round($glassBlur * 0.7) ?>px) !important;
        }
        <?php endif; ?>

        <?php if ($vignetteEnabled === '1' || $vignetteEnabled === 1 || $vignetteEnabled === true): ?>
        body.luxury-theme::after {
            content: '' !important;
            position: fixed !important;
            inset: 0 !important;
            pointer-events: none !important;
            z-index: 1 !important;
            background: radial-gradient(ellipse at 50% 50%, transparent 40%, rgba(0, 0, 0, <?= $vignetteIntensity ?>) 100%) !important;
        }
        body.luxury-theme > * {
            position: relative;
            z-index: 2;
        }
        <?php else: ?>
        body.luxury-theme::after {
            display: none !important;
        }
        <?php endif; ?>

        <?php if ($glowEnabled === '1' || $glowEnabled === 1 || $glowEnabled === true): ?>
        body.luxury-theme::before {
            content: '' !important;
            position: fixed !important;
            top: 0 !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: 100vw !important;
            height: 100vh !important;
            pointer-events: none !important;
            z-index: 0 !important;
            background: radial-gradient(circle at 50% 18%, rgba(<?= $gRgb[0] ?>, <?= $gRgb[1] ?>, <?= $gRgb[2] ?>, <?= $glowIntensity ?>) 0%, rgba(<?= $gRgb[0] ?>, <?= $gRgb[1] ?>, <?= $gRgb[2] ?>, <?= round($glowIntensity * 0.2, 3) ?>) 45%, transparent 75%) !important;
        }
        <?php else: ?>
        body.luxury-theme::before {
            display: none !important;
        }
        <?php endif; ?>
    </style>
</head>
<body class="luxury-theme <?= \App\Core\View::e($canonicalTheme) ?> <?= \App\Core\View::e($bodyPageClass) ?> <?= $rawServerTheme !== $canonicalTheme ? \App\Core\View::e($rawServerTheme) : '' ?>">
    <?= \App\Models\Setting::get('custom_body_scripts', '') ?>

    <!-- 1. BARRA SUPERIOR DE ANÚNCIO (TOP BANNER) -->
    <?php if (\App\Models\Setting::get('top_banner_active', '1') === '1'): ?>
    <div class="top-announcement-bar">
        <div class="container top-bar-content">
            <span><?= \App\Models\Setting::get('top_banner_text', '✨ Frete Cortesia com Seguro para todo o Brasil • 5% de Desconto no Pix • Até 10x sem juros') ?></span>
        </div>
    </div>
    <?php endif; ?>

    <!-- 2. CABEÇALHO PRINCIPAL (HEADER NAVEGAÇÃO) -->
    <header class="main-header" id="mainHeader">
        <div class="container header-inner">
            
            <!-- Menu Hambúrguer (Mobile) -->
            <?php if (\App\Models\Setting::get('mobile_menu_enabled', '1') === '1'): ?>
            <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Abrir Menu de Navegação" aria-expanded="false" aria-controls="mobileNavDrawer">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <?php endif; ?>

            <!-- Links de Navegação (Desktop Esquerda) -->
            <nav class="nav-links left-nav" aria-label="Navegação Principal">
                <?php if (\App\Models\Setting::get('nav_link_home_visible', '1') === '1'): ?>
                    <a href="<?= \App\Core\View::e(\App\Models\Setting::get('nav_link_home_url', '/')) ?>" class="nav-link <?= ($_SERVER['REQUEST_URI'] ?? '/') === '/' ? 'active' : '' ?>"><?= \App\Core\View::e(\App\Models\Setting::get('nav_link_home', 'Início')) ?></a>
                <?php endif; ?>

                <?php if (\App\Models\Setting::get('nav_link_store_visible', '1') === '1'): ?>
                    <a href="<?= \App\Core\View::e(\App\Models\Setting::get('nav_link_store_url', '/loja')) ?>" class="nav-link <?= str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/loja') ? 'active' : '' ?>"><?= \App\Core\View::e(\App\Models\Setting::get('nav_link_store', 'Loja')) ?></a>
                <?php endif; ?>

                <?php if (\App\Models\Setting::get('nav_link_about_visible', '1') === '1'): ?>
                    <a href="<?= \App\Core\View::e(\App\Models\Setting::get('nav_link_about_url', '/#about')) ?>" class="nav-link"><?= \App\Core\View::e(\App\Models\Setting::get('nav_link_about', 'Sobre')) ?></a>
                <?php endif; ?>

                <?php if (\App\Models\Setting::get('nav_link_collections_visible', '1') === '1'): ?>
                <div class="nav-item-dropdown">
                    <a href="<?= \App\Core\View::e(\App\Models\Setting::get('nav_link_collections_url', '/loja')) ?>" class="nav-link dropdown-toggle"><?= \App\Core\View::e(\App\Models\Setting::get('nav_link_collections', 'Coleções')) ?> <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg></a>
                    <div class="dropdown-menu">
                        <?php if (\App\Models\Setting::get('nav_sub_all_visible', '1') === '1'): ?>
                            <a href="<?= \App\Core\View::e(\App\Models\Setting::get('nav_sub_all_url', '/loja')) ?>"><?= \App\Core\View::e(\App\Models\Setting::get('nav_sub_all', 'Ver Todas as Peças')) ?></a>
                        <?php endif; ?>
                        <?php if (\App\Models\Setting::get('nav_sub_bracelets_visible', '1') === '1'): ?>
                            <a href="<?= \App\Core\View::e(\App\Models\Setting::get('nav_sub_bracelets_url', '/loja#braceletes')) ?>"><?= \App\Core\View::e(\App\Models\Setting::get('nav_sub_bracelets', 'Braceletes Exclusivos')) ?></a>
                        <?php endif; ?>
                        <?php if (\App\Models\Setting::get('nav_sub_necklaces_visible', '1') === '1'): ?>
                            <a href="<?= \App\Core\View::e(\App\Models\Setting::get('nav_sub_necklaces_url', '/loja#colares')) ?>"><?= \App\Core\View::e(\App\Models\Setting::get('nav_sub_necklaces', 'Colares & Pingentes')) ?></a>
                        <?php endif; ?>
                        <?php if (\App\Models\Setting::get('nav_sub_rings_visible', '1') === '1'): ?>
                            <a href="<?= \App\Core\View::e(\App\Models\Setting::get('nav_sub_rings_url', '/loja#aneis')) ?>"><?= \App\Core\View::e(\App\Models\Setting::get('nav_sub_rings', 'Anéis & Alianças')) ?></a>
                        <?php endif; ?>
                        <?php if (\App\Models\Setting::get('nav_sub_earrings_visible', '1') === '1'): ?>
                            <a href="<?= \App\Core\View::e(\App\Models\Setting::get('nav_sub_earrings_url', '/loja#brincos')) ?>"><?= \App\Core\View::e(\App\Models\Setting::get('nav_sub_earrings', 'Brincos & Segundo Furo')) ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (\App\Models\Setting::get('nav_link_services_visible', '1') === '1'): ?>
                    <a href="<?= \App\Core\View::e(\App\Models\Setting::get('nav_link_services_url', '/#services')) ?>" class="nav-link"><?= \App\Core\View::e(\App\Models\Setting::get('nav_link_services', 'Garantia')) ?></a>
                <?php endif; ?>

                <?php if (\App\Models\Setting::get('nav_custom_link1_visible', '0') === '1'): ?>
                    <a href="<?= \App\Core\View::e(\App\Models\Setting::get('nav_custom_link1_url', '/garantia-e-cuidados')) ?>" class="nav-link"><?= \App\Core\View::e(\App\Models\Setting::get('nav_custom_link1_text', 'Certificado')) ?></a>
                <?php endif; ?>
            </nav>

            <!-- Logo Central da Marca (Pilar 1: Texto ou Imagem) -->
            <div class="brand-logo-container" style="text-align: <?= \App\Models\Setting::get('site_logo_alignment', 'center') === 'left' ? 'left' : 'center' ?>;">
                <a href="/" class="brand-logo" aria-label="Divinium Home">
                    <?php if (\App\Models\Setting::get('site_logo_type', 'text') === 'image' && \App\Models\Setting::get('site_logo_image_dark')): ?>
                        <img src="<?= \App\Core\View::e(\App\Models\Setting::get('site_logo_image_dark')) ?>" 
                             alt="<?= \App\Core\View::e(\App\Models\Setting::get('header_logo_text', 'DIVINIUM')) ?>" 
                             style="height: <?= \App\Core\View::e(\App\Models\Setting::get('site_logo_height', '38px')) ?>; max-height: 56px; width: auto; object-fit: contain;">
                    <?php else: ?>
                        <span class="logo-text"><?= \App\Core\View::e(\App\Models\Setting::get('header_logo_text', 'DIVINIUM')) ?></span>
                        <span class="logo-sub"><?= \App\Core\View::e(\App\Models\Setting::get('header_logo_sub', 'HAUTE JOAILLERIE')) ?></span>
                    <?php endif; ?>
                </a>
            </div>

            <!-- Ações do Cabeçalho (Direita) -->
            <div class="header-actions">
                <button class="action-btn search-trigger" aria-label="Buscar produtos">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>

                <a href="/minha-conta/favoritos" class="action-btn wishlist-btn" aria-label="Lista de Desejos">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                </a>

                <?php if (\App\Models\Setting::get('checkout_direct_cart_enabled', '1') === '1'): ?>
                <button class="action-btn cart-trigger" id="cartTrigger" aria-label="Sacola de Compras">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    <span class="cart-counter" id="cartCount">0</span>
                </button>
                <?php endif; ?>

            </div>

        </div>
    </header>

    <!-- 2.1 GAVETA LATERAL DO MENU MOBILE (DRAWER RESPONSIVO) -->
    <?php if (\App\Models\Setting::get('mobile_menu_enabled', '1') === '1'): 
        $navBg = \App\Models\Setting::get('mobile_nav_bg_color', '');
        $navTextColor = \App\Models\Setting::get('mobile_nav_text_color', '');
        $navAccent = \App\Models\Setting::get('mobile_nav_accent_color', '');
        $navPos = \App\Models\Setting::get('mobile_nav_position', 'left');
        $navWidth = \App\Models\Setting::get('mobile_nav_drawer_width', '360px');
        $navBlur = \App\Models\Setting::get('mobile_nav_backdrop_blur', '8px');

        $drawerStyles = [];
        if ($navBg) $drawerStyles[] = "background: {$navBg} !important;";
        if ($navTextColor) $drawerStyles[] = "color: {$navTextColor} !important;";
        if ($navWidth && $navWidth !== '360px') $drawerStyles[] = "max-width: {$navWidth} !important;";
        if ($navAccent) {
            $drawerStyles[] = "--mobile-nav-accent: {$navAccent};";
        }
        $drawerStyleAttr = !empty($drawerStyles) ? 'style="' . implode(' ', $drawerStyles) . '"' : '';
        $overlayStyleAttr = ($navBlur && $navBlur !== '8px') ? 'style="backdrop-filter: blur(' . $navBlur . '); -webkit-backdrop-filter: blur(' . $navBlur . ');"' : '';
    ?>
    <div class="mobile-nav-overlay" id="mobileNavOverlay" aria-hidden="true" <?= $overlayStyleAttr ?>></div>
    <aside class="mobile-nav-drawer <?= $navPos === 'right' ? 'position-right' : '' ?>" id="mobileNavDrawer" aria-label="Menu de Navegação Principal" aria-hidden="true" <?= $drawerStyleAttr ?>>
        <div class="mobile-nav-header">
            <?php if (\App\Models\Setting::get('mobile_nav_show_brand', '1') === '1'): ?>
                <div class="mobile-nav-brand">
                    <?php if (\App\Models\Setting::get('mobile_nav_show_header_badge', '0') === '1'): ?>
                        <span class="mobile-nav-header-badge"><?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_header_badge', 'JOALHERIA AUTORAL')) ?></span>
                    <?php endif; ?>
                    <span class="logo-text"><?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_logo_text', \App\Models\Setting::get('header_logo_text', 'DIVINIUM'))) ?></span>
                    <span class="logo-sub"><?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_logo_sub', \App\Models\Setting::get('header_logo_sub', 'HAUTE JOAILLERIE'))) ?></span>
                </div>
            <?php else: ?>
                <div></div>
            <?php endif; ?>
            <button type="button" class="close-mobile-nav-btn" id="closeMobileNavBtn" aria-label="Fechar Menu">&times;</button>
        </div>

        <div class="mobile-nav-body">
            <nav class="mobile-nav-links" aria-label="Links de Navegação Mobile">
                <?php if (\App\Models\Setting::get('mobile_nav_home_visible', '1') === '1'): ?>
                    <a href="<?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_home_url', '/')) ?>" class="mobile-nav-link <?= ($_SERVER['REQUEST_URI'] ?? '/') === '/' ? 'active' : '' ?>">
                        <span class="mobile-nav-link-text"><?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_home_text', \App\Models\Setting::get('nav_link_home', 'Início'))) ?></span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                <?php endif; ?>

                <?php if (\App\Models\Setting::get('mobile_nav_store_visible', '1') === '1'): ?>
                    <a href="<?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_store_url', '/loja')) ?>" class="mobile-nav-link <?= str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/loja') ? 'active' : '' ?>">
                        <span class="mobile-nav-link-text"><?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_store_text', \App\Models\Setting::get('nav_link_store', 'Boutique / Loja'))) ?></span>
                        <?php if (\App\Models\Setting::get('mobile_nav_store_badge_visible', '1') === '1'): ?>
                            <span class="mobile-nav-badge"><?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_store_badge_text', 'Exclusivo')) ?></span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>

                <!-- Acordeão Coleções -->
                <?php if (\App\Models\Setting::get('mobile_nav_collections_visible', '1') === '1'): ?>
                    <div class="mobile-nav-accordion" id="mobileCollectionsAccordion">
                        <button type="button" class="mobile-accordion-toggle" id="mobileCollectionsToggle" aria-expanded="false">
                            <span class="mobile-nav-link-text"><?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_collections_text', \App\Models\Setting::get('nav_link_collections', 'Coleções'))) ?></span>
                            <svg class="accordion-chevron" width="12" height="8" viewBox="0 0 12 8" fill="none"><path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        </button>
                        <div class="mobile-accordion-content" id="mobileCollectionsContent">
                            <?php if (\App\Models\Setting::get('mobile_nav_sub_all_visible', '1') === '1'): ?>
                                <a href="<?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_sub_all_url', '/loja')) ?>" class="mobile-subnav-link">✦ <?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_sub_all_text', \App\Models\Setting::get('nav_sub_all', 'Ver Todas as Peças'))) ?></a>
                            <?php endif; ?>
                            <?php if (\App\Models\Setting::get('mobile_nav_sub_bracelets_visible', '1') === '1'): ?>
                                <a href="<?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_sub_bracelets_url', '/loja#braceletes')) ?>" class="mobile-subnav-link">✦ <?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_sub_bracelets_text', \App\Models\Setting::get('nav_sub_bracelets', 'Braceletes Exclusivos'))) ?></a>
                            <?php endif; ?>
                            <?php if (\App\Models\Setting::get('mobile_nav_sub_necklaces_visible', '1') === '1'): ?>
                                <a href="<?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_sub_necklaces_url', '/loja#colares')) ?>" class="mobile-subnav-link">✦ <?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_sub_necklaces_text', \App\Models\Setting::get('nav_sub_necklaces', 'Colares & Pingentes'))) ?></a>
                            <?php endif; ?>
                            <?php if (\App\Models\Setting::get('mobile_nav_sub_rings_visible', '1') === '1'): ?>
                                <a href="<?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_sub_rings_url', '/loja#aneis')) ?>" class="mobile-subnav-link">✦ <?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_sub_rings_text', \App\Models\Setting::get('nav_sub_rings', 'Anéis & Alianças'))) ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (\App\Models\Setting::get('mobile_nav_about_visible', '1') === '1'): ?>
                    <a href="<?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_about_url', '/#about')) ?>" class="mobile-nav-link">
                        <span class="mobile-nav-link-text"><?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_about_text', \App\Models\Setting::get('nav_link_about', 'Sobre'))) ?></span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                <?php endif; ?>

                <?php if (\App\Models\Setting::get('mobile_nav_services_visible', '1') === '1'): ?>
                    <a href="<?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_services_url', '/#services')) ?>" class="mobile-nav-link">
                        <span class="mobile-nav-link-text"><?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_services_text', \App\Models\Setting::get('nav_link_services', 'Garantia & Serviços'))) ?></span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                <?php endif; ?>

                <?php if (\App\Models\Setting::get('mobile_nav_wishlist_visible', '1') === '1'): ?>
                    <a href="<?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_wishlist_url', '/minha-conta/favoritos')) ?>" class="mobile-nav-link">
                        <span class="mobile-nav-link-text" style="display: flex; align-items: center; gap: 8px;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                            <?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_wishlist_text', 'Lista de Desejos')) ?>
                        </span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                <?php endif; ?>

                <?php if (\App\Models\Setting::get('mobile_nav_custom1_visible', '0') === '1'): ?>
                    <a href="<?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_custom1_url', '/loja')) ?>" class="mobile-nav-link">
                        <span class="mobile-nav-link-text"><?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_custom1_text', 'Rastrear Pedido')) ?></span>
                        <?php if (\App\Models\Setting::get('mobile_nav_custom1_badge')): ?>
                            <span class="mobile-nav-badge"><?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_custom1_badge')) ?></span>
                        <?php endif; ?>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                <?php endif; ?>

                <?php if (\App\Models\Setting::get('mobile_nav_custom2_visible', '0') === '1'): ?>
                    <a href="<?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_custom2_url', '/#services')) ?>" class="mobile-nav-link">
                        <span class="mobile-nav-link-text"><?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_custom2_text', 'Atendimento & FAQ')) ?></span>
                        <?php if (\App\Models\Setting::get('mobile_nav_custom2_badge')): ?>
                            <span class="mobile-nav-badge"><?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_custom2_badge')) ?></span>
                        <?php endif; ?>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                <?php endif; ?>
            </nav>

            <div class="mobile-nav-footer">
                <?php if (\App\Models\Setting::get('mobile_nav_whatsapp_visible', '1') === '1'): 
                    $waTarget = \App\Models\Setting::get('mobile_nav_whatsapp_url', '');
                    if (!$waTarget || !str_starts_with($waTarget, 'http')) {
                        $rawWa = \App\Models\Setting::get('hero_whatsapp_number', '5511999999999');
                        $waTarget = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $rawWa);
                    }
                ?>
                    <a href="<?= \App\Core\View::e($waTarget) ?>" target="_blank" rel="noopener" class="mobile-nav-whatsapp">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                        <span><?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_whatsapp_text', 'Concierge VIP via WhatsApp')) ?></span>
                    </a>
                <?php endif; ?>


                <?php if (\App\Models\Setting::get('mobile_nav_perk_visible', '1') === '1'): ?>
                    <div class="mobile-nav-perk">
                        <small><?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_perk_text', '✨ Joalheria Autoral Brasileira • Banho de Prata & Gemas Raras')) ?></small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </aside>
    <?php endif; ?>

    <!-- 3. GAVETA LATERAL DO CARRINHO (CART DRAWER) -->
    <?php
    // Controles Avançados de Botões: Sacola (Drawer) & Rodapé / Contatos
    $cartCheckoutVisible = \App\Models\Setting::get('cart_btn_checkout_visible', '1') === '1';
    $cartCheckoutText = \App\Models\Setting::get('cart_btn_checkout_text', \App\Models\Setting::get('cart_checkout_btn', 'Finalizar Compra'));
    $cartCheckoutUrl = \App\Models\Setting::get('cart_btn_checkout_url', '/checkout');
    $cartCheckoutBg = \App\Models\Setting::get('cart_btn_checkout_bg', '#202020');
    $cartCheckoutColor = \App\Models\Setting::get('cart_btn_checkout_color', '#FFFFFF');
    $cartCheckoutBorder = \App\Models\Setting::get('cart_btn_checkout_border', '#202020');
    $cartCheckoutIcon = \App\Models\Setting::get('cart_btn_checkout_icon', 'arrow-right');
    $cartCheckoutIconColor = \App\Models\Setting::get('cart_btn_checkout_icon_color', '#FFFFFF');
    $cartCheckoutRadius = \App\Models\Setting::get('cart_btn_checkout_radius', '6px');
    $cartCheckoutShadow = \App\Models\Setting::get('cart_btn_checkout_shadow', 'noble');

    $cartDiscoverVisible = \App\Models\Setting::get('cart_btn_discover_visible', '1') === '1';
    $cartDiscoverText = \App\Models\Setting::get('cart_btn_discover_text', \App\Models\Setting::get('cart_drawer_discover', 'Descobrir Coleção'));
    $cartDiscoverUrl = \App\Models\Setting::get('cart_btn_discover_url', '#collections');
    $cartDiscoverBg = \App\Models\Setting::get('cart_btn_discover_bg', 'transparent');
    $cartDiscoverColor = \App\Models\Setting::get('cart_btn_discover_color', '#202020');
    $cartDiscoverBorder = \App\Models\Setting::get('cart_btn_discover_border', '#202020');
    $cartDiscoverIcon = \App\Models\Setting::get('cart_btn_discover_icon', 'sparkle');
    $cartDiscoverIconColor = \App\Models\Setting::get('cart_btn_discover_icon_color', '#202020');
    $cartDiscoverRadius = \App\Models\Setting::get('cart_btn_discover_radius', '20px');
    $cartDiscoverShadow = \App\Models\Setting::get('cart_btn_discover_shadow', 'none');

    $footerWaVisible = (\App\Models\Setting::get('footer_btn_whatsapp_visible', '1') === '1') && (\App\Models\Setting::get('footer_whatsapp_visible', '1') === '1');
    $footerWaText = \App\Models\Setting::get('footer_btn_whatsapp_text', \App\Models\Setting::get('footer_whatsapp_label', 'Atendimento via WhatsApp'));
    $footerWaUrl = \App\Models\Setting::get('footer_btn_whatsapp_url', 'https://wa.me/' . \App\Models\Setting::get('hero_whatsapp_number', '5511999999999'));
    $footerWaBg = \App\Models\Setting::get('footer_btn_whatsapp_bg', 'transparent');
    $footerWaColor = \App\Models\Setting::get('footer_btn_whatsapp_color', '#CBD0D6');
    $footerWaBorder = \App\Models\Setting::get('footer_btn_whatsapp_border', 'rgba(255,255,255,0.15)');
    $footerWaIcon = \App\Models\Setting::get('footer_btn_whatsapp_icon', 'whatsapp');
    $footerWaIconColor = \App\Models\Setting::get('footer_btn_whatsapp_icon_color', '#25D366');
    $footerWaRadius = \App\Models\Setting::get('footer_btn_whatsapp_radius', '6px');
    $footerWaShadow = \App\Models\Setting::get('footer_btn_whatsapp_shadow', 'none');

    $footerInstaVisible = (\App\Models\Setting::get('footer_btn_instagram_visible', '1') === '1') && (\App\Models\Setting::get('footer_socials_visible', '1') === '1');
    $footerInstaText = \App\Models\Setting::get('footer_btn_instagram_text', 'Instagram');
    $footerInstaUrl = \App\Models\Setting::get('footer_btn_instagram_url', \App\Models\Setting::get('footer_instagram', 'https://instagram.com'));
    $footerInstaBg = \App\Models\Setting::get('footer_btn_instagram_bg', 'transparent');
    $footerInstaColor = \App\Models\Setting::get('footer_btn_instagram_color', '#CBD0D6');
    $footerInstaBorder = \App\Models\Setting::get('footer_btn_instagram_border', 'rgba(255,255,255,0.15)');
    $footerInstaIcon = \App\Models\Setting::get('footer_btn_instagram_icon', 'instagram');
    $footerInstaIconColor = \App\Models\Setting::get('footer_btn_instagram_icon_color', '#E4405F');
    $footerInstaRadius = \App\Models\Setting::get('footer_btn_instagram_radius', '6px');
    $footerInstaShadow = \App\Models\Setting::get('footer_btn_instagram_shadow', 'none');

    $footerEmailVisible = (\App\Models\Setting::get('footer_btn_email_visible', '1') === '1') && (\App\Models\Setting::get('footer_email_visible', '1') === '1');
    $footerEmailText = \App\Models\Setting::get('footer_btn_email_text', \App\Models\Setting::get('footer_email', 'atendimento@divinium.com.br'));
    $footerEmailVal = \App\Models\Setting::get('footer_email', 'atendimento@divinium.com.br');
    $footerEmailUrl = \App\Models\Setting::get('footer_btn_email_url', 'mailto:' . $footerEmailVal);
    $footerEmailBg = \App\Models\Setting::get('footer_btn_email_bg', 'transparent');
    $footerEmailColor = \App\Models\Setting::get('footer_btn_email_color', '#CBD0D6');
    $footerEmailBorder = \App\Models\Setting::get('footer_btn_email_border', 'rgba(255,255,255,0.15)');
    $footerEmailIcon = \App\Models\Setting::get('footer_btn_email_icon', 'mail');
    $footerEmailIconColor = \App\Models\Setting::get('footer_btn_email_icon_color', '#CBD0D6');
    $footerEmailRadius = \App\Models\Setting::get('footer_btn_email_radius', '6px');
    $footerEmailShadow = \App\Models\Setting::get('footer_btn_email_shadow', 'none');

    $layoutShadowMap = [
        'none' => 'none',
        'subtle' => '0 2px 8px rgba(0,0,0,0.06)',
        'noble' => '0 4px 16px rgba(0,0,0,0.12)',
        'glow' => '0 0 18px rgba(203, 208, 214, 0.45)',
        'flat' => 'none',
    ];

    $renderLayoutIcon = function($iconKey, $iconColor) {
        if (!$iconKey || $iconKey === 'none') return '';
        $style = 'color: ' . \App\Core\View::e($iconColor) . '; display: inline-flex; align-items: center; justify-content: center;';
        switch ($iconKey) {
            case 'arrow-right':
            case '→':
                return '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="' . \App\Core\View::e($iconColor) . '" stroke-width="2" style="margin-left: 5px;"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
            case 'arrow-left':
            case '←':
                return '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="' . \App\Core\View::e($iconColor) . '" stroke-width="2" style="margin-right: 5px;"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>';
            case 'sparkle':
            case '✦':
                return '<span style="' . $style . ' font-size: 0.9rem; margin-right: 5px;">✦</span>';
            case 'bag':
            case '🛍️':
                return '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="' . \App\Core\View::e($iconColor) . '" stroke-width="2" style="margin-right: 5px;"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>';
            case 'whatsapp':
            case '💬':
                return '<span style="' . $style . ' font-size: 0.95rem; margin-right: 5px;">💬</span>';
            case 'instagram':
            case '📷':
                return '<span style="' . $style . ' font-size: 0.95rem; margin-right: 5px;">📷</span>';
            case 'mail':
            case '✉️':
                return '<span style="' . $style . ' font-size: 0.95rem; margin-right: 5px;">✉️</span>';
            default:
                return '<span style="' . $style . ' margin-right: 5px;">' . \App\Core\View::e($iconKey) . '</span>';
        }
    };
    ?>
    <?php if (\App\Models\Setting::get('checkout_direct_cart_enabled', '1') === '1'): ?>
    <div class="cart-drawer-overlay" id="cartDrawerOverlay"></div>
    <aside class="cart-drawer" id="cartDrawer" aria-label="Sacola de compras">
        <div class="cart-drawer-header">
            <h3><?= \App\Core\View::e(\App\Models\Setting::get('cart_drawer_title', 'Sua Sacola de Escolhas')) ?></h3>
            <button class="close-drawer-btn" id="closeCartDrawer" aria-label="Fechar Sacola">&times;</button>
        </div>
        <div class="cart-drawer-body" id="cartDrawerBody">
            <div class="empty-cart-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="empty-icon"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                <p><?= \App\Core\View::e(\App\Models\Setting::get('cart_drawer_empty', 'Sua sacola está vazia no momento.')) ?></p>
                <?php if ($cartDiscoverVisible): ?>
                <a href="<?= \App\Core\View::e($cartDiscoverUrl) ?>" class="btn-discover" onclick="closeDrawer()" style="background: <?= \App\Core\View::e($cartDiscoverBg) ?> !important; color: <?= \App\Core\View::e($cartDiscoverColor) ?> !important; border: 1px solid <?= \App\Core\View::e($cartDiscoverBorder) ?> !important; border-radius: <?= \App\Core\View::e($cartDiscoverRadius) ?> !important; box-shadow: <?= \App\Core\View::e($layoutShadowMap[$cartDiscoverShadow] ?? 'none') ?> !important; display: inline-flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none; transition: all 0.2s ease;">
                    <?= $renderLayoutIcon($cartDiscoverIcon, $cartDiscoverIconColor) ?>
                    <span><?= \App\Core\View::e($cartDiscoverText) ?></span>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="cart-drawer-footer">
            <div class="cart-subtotal-row">
                <span>Subtotal:</span>
                <span class="subtotal-val" id="cartSubtotalVal">R$ 0,00</span>
            </div>
            <div class="cart-perks">
                <small><?= \App\Core\View::e(\App\Models\Setting::get('cart_security_badge', '🔒 Pagamento 100% Seguro via ASAAS • Pix com QR Code Instantâneo')) ?></small>
            </div>
            <?php if ($cartCheckoutVisible): ?>
            <a href="<?= \App\Core\View::e($cartCheckoutUrl) ?>" class="btn-checkout-primary" style="background: <?= \App\Core\View::e($cartCheckoutBg) ?> !important; color: <?= \App\Core\View::e($cartCheckoutColor) ?> !important; border: 1px solid <?= \App\Core\View::e($cartCheckoutBorder) ?> !important; border-radius: <?= \App\Core\View::e($cartCheckoutRadius) ?> !important; box-shadow: <?= \App\Core\View::e($layoutShadowMap[$cartCheckoutShadow] ?? 'none') ?> !important; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; transition: all 0.2s ease;">
                <?= $renderLayoutIcon($cartCheckoutIcon, $cartCheckoutIconColor) ?>
                <span><?= \App\Core\View::e($cartCheckoutText) ?></span>
            </a>
            <?php endif; ?>
        </div>
    </aside>
    <?php endif; ?>

    <!-- 4. CONTEÚDO PRINCIPAL DA VIEW -->
    <main class="page-content" id="mainContent">
        <?= $content ?>
    </main>

    <!-- 5. RODAPÉ DE ALTA CONVERSÃO & CONFORMIDADE JURÍDICA (DECRETO 7.962/2013 + LGPD) -->
    <footer class="main-footer">
        <div class="container footer-top">
            <div class="footer-col brand-col">
                <span class="footer-logo"><?= \App\Core\View::e(\App\Models\Setting::get('header_logo_text', 'DIVINIUM')) ?></span>
                <?php if (\App\Models\Setting::get('footer_tagline_visible', '1') === '1'): ?>
                <p class="brand-tagline"><?= \App\Core\View::e(\App\Models\Setting::get('footer_tagline', 'Joalheria fina concebida para quem aprecia a nobreza atemporal, o luxo silencioso e o design autoral inconfundível.')) ?></p>
                <?php endif; ?>
                <?php if (\App\Models\Setting::get('footer_socials_visible', '1') === '1'): ?>
                <div class="footer-socials" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                    <?php if ($footerInstaVisible): ?>
                    <a href="<?= \App\Core\View::e($footerInstaUrl) ?>" target="_blank" rel="noopener" aria-label="Instagram" class="footer-btn-insta" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: <?= \App\Core\View::e($footerInstaBg) ?> !important; color: <?= \App\Core\View::e($footerInstaColor) ?> !important; border: 1px solid <?= \App\Core\View::e($footerInstaBorder) ?> !important; border-radius: <?= \App\Core\View::e($footerInstaRadius) ?> !important; box-shadow: <?= \App\Core\View::e($layoutShadowMap[$footerInstaShadow] ?? 'none') ?> !important; text-decoration: none; font-size: 0.82rem; transition: all 0.2s ease;">
                        <?= $renderLayoutIcon($footerInstaIcon, $footerInstaIconColor) ?>
                        <span><?= \App\Core\View::e($footerInstaText) ?></span>
                    </a>
                    <?php endif; ?>
                    <?php if ($footerWaVisible): ?>
                    <a href="<?= \App\Core\View::e($footerWaUrl) ?>" target="_blank" rel="noopener" aria-label="WhatsApp" class="footer-btn-wa" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: <?= \App\Core\View::e($footerWaBg) ?> !important; color: <?= \App\Core\View::e($footerWaColor) ?> !important; border: 1px solid <?= \App\Core\View::e($footerWaBorder) ?> !important; border-radius: <?= \App\Core\View::e($footerWaRadius) ?> !important; box-shadow: <?= \App\Core\View::e($layoutShadowMap[$footerWaShadow] ?? 'none') ?> !important; text-decoration: none; font-size: 0.82rem; transition: all 0.2s ease;">
                        <?= $renderLayoutIcon($footerWaIcon, $footerWaIconColor) ?>
                        <span><?= \App\Core\View::e($footerWaText) ?></span>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="footer-col">
                <h4><?= \App\Core\View::e(\App\Models\Setting::get('footer_col1_title', 'Coleções')) ?></h4>
                <ul>
                    <li><a href="/loja"><?= \App\Core\View::e(\App\Models\Setting::get('nav_sub_all', 'Ver Catálogo Completo')) ?></a></li>
                    <li><a href="/loja#braceletes"><?= \App\Core\View::e(\App\Models\Setting::get('nav_sub_bracelets', 'Pulseiras & Braceletes')) ?></a></li>
                    <li><a href="/loja#colares"><?= \App\Core\View::e(\App\Models\Setting::get('nav_sub_necklaces', 'Colares & Pingentes')) ?></a></li>
                    <li><a href="/loja#aneis"><?= \App\Core\View::e(\App\Models\Setting::get('nav_sub_rings', 'Anéis Solitários & Alianças')) ?></a></li>
                    <li><a href="/loja"><?= \App\Core\View::e(\App\Models\Setting::get('footer_editions_text', 'Edições Numeradas')) ?></a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4><?= \App\Core\View::e(\App\Models\Setting::get('footer_col2_title', 'Atendimento & Concierge')) ?></h4>
                <ul>
                    <?php if ($footerWaVisible): ?>
                    <li>
                        <a href="<?= \App\Core\View::e($footerWaUrl) ?>" target="_blank" class="footer-link-wa" style="display: inline-flex; align-items: center; gap: 6px;">
                            <?= $renderLayoutIcon($footerWaIcon, $footerWaIconColor) ?>
                            <span><?= \App\Core\View::e($footerWaText) ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if ($footerEmailVisible): ?>
                    <li>
                        <a href="<?= \App\Core\View::e($footerEmailUrl) ?>" class="footer-link-email" style="display: inline-flex; align-items: center; gap: 6px;">
                            <?= $renderLayoutIcon($footerEmailIcon, $footerEmailIconColor) ?>
                            <span><?= \App\Core\View::e($footerEmailText) ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (\App\Models\Setting::get('page_trocas_enabled', '1') === '1'): ?>
                    <li><a href="/trocas-e-devolucoes"><?= \App\Core\View::e(\App\Models\Setting::get('footer_returns_text', \App\Models\Setting::get('page_trocas_title', 'Trocas e Devoluções (CDC 7 Dias)'))) ?></a></li>
                    <?php endif; ?>
                    <?php if (\App\Models\Setting::get('page_garantia_enabled', '1') === '1'): ?>
                    <li><a href="/garantia-e-cuidados"><?= \App\Core\View::e(\App\Models\Setting::get('footer_warranty_text', \App\Models\Setting::get('page_garantia_title', 'Certificado & Cuidados'))) ?></a></li>
                    <?php endif; ?>
                    <li><a href="/guia-de-medidas"><?= \App\Core\View::e(\App\Models\Setting::get('footer_size_guide_text', 'Guia de Medidas de Anéis')) ?></a></li>
                </ul>
            </div>

            <?php if (\App\Models\Setting::get('institutional_pages_content_enabled', '1') === '1'): ?>
            <div class="footer-col">
                <h4><?= \App\Core\View::e(\App\Models\Setting::get('footer_col3_title', 'Segurança & Privacidade')) ?></h4>
                <ul>
                    <?php if (\App\Models\Setting::get('page_privacidade_enabled', '1') === '1'): ?>
                    <li><a href="/politica-de-privacidade"><?= \App\Core\View::e(\App\Models\Setting::get('footer_privacy_text', \App\Models\Setting::get('page_privacidade_title', 'Política de Privacidade (LGPD)'))) ?></a></li>
                    <?php endif; ?>
                    <?php if (\App\Models\Setting::get('page_termos_enabled', '1') === '1'): ?>
                    <li><a href="/termos-de-uso"><?= \App\Core\View::e(\App\Models\Setting::get('footer_terms_text', \App\Models\Setting::get('page_termos_title', 'Termos de Uso'))) ?></a></li>
                    <?php endif; ?>
                    <?php if (\App\Models\Setting::get('page_frete_enabled', '1') === '1'): ?>
                    <li><a href="/politica-de-frete-e-entrega"><?= \App\Core\View::e(\App\Models\Setting::get('footer_shipping_text', \App\Models\Setting::get('page_frete_title', 'Política de Frete e Entrega'))) ?></a></li>
                    <?php endif; ?>
                    <li><a href="/dpo"><?= \App\Core\View::e(\App\Models\Setting::get('footer_dpo_text', 'Encarregado de Dados (DPO)')) ?></a></li>
                    <?php if (\App\Models\Setting::get('footer_operating_hours_visible', '1') === '1'): ?>
                    <li><span><?= \App\Core\View::e(\App\Models\Setting::get('footer_operating_hours', 'Segunda a Sexta: 09h às 18h')) ?></span></li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>

        <?php if (\App\Models\Setting::get('footer_pay_badges_visible', '1') === '1'): ?>
        <div class="container footer-middle">
            <div class="payment-methods-row">
                <span class="payment-badge"><?= \App\Core\View::e(\App\Models\Setting::get('footer_pay_badge1', 'PIX (Aprovação Imediata)')) ?></span>
                <span class="payment-badge"><?= \App\Core\View::e(\App\Models\Setting::get('footer_pay_badge2', 'Cartão de Crédito em até 10x')) ?></span>
                <span class="payment-badge"><?= \App\Core\View::e(\App\Models\Setting::get('footer_pay_badge3', 'Boleto Bancário')) ?></span>
                <span class="payment-badge"><?= \App\Core\View::e(\App\Models\Setting::get('footer_pay_badge4', 'Gateway Homologado ASAAS')) ?></span>
            </div>
        </div>
        <?php endif; ?>

        <div class="container footer-bottom">
            <div class="legal-info">
                <?php if (\App\Models\Setting::get('footer_legal_info_visible', '1') === '1'): ?>
                <p><?= \App\Core\View::e(\App\Models\Setting::get('footer_legal_info', 'DIVINIUM JOIAS LTDA. • CNPJ: 00.000.000/0001-00 • São Paulo/SP - Brasil')) ?></p>
                <?php endif; ?>
                <?php if (\App\Models\Setting::get('footer_consumer_rights_visible', '1') === '1'): ?>
                <p><?= \App\Core\View::e(\App\Models\Setting::get('footer_consumer_rights', 'Conforme o Decreto nº 7.962/2013 e Lei 8.078/1990 (Código de Defesa do Consumidor). Direito de arrependimento em até 7 dias corridos após o recebimento.')) ?></p>
                <?php endif; ?>
            </div>
            <div class="copyright">
                <p>&copy; <?= date('Y') ?> <?= \App\Core\View::e(\App\Models\Setting::get('footer_copyright_brand', 'Divinium. Todos os direitos reservados.')) ?></p>
            </div>
        </div>
    </footer>

    <style>
    .btn-checkout-primary:hover, .btn-discover:hover, .footer-btn-insta:hover, .footer-btn-wa:hover, .footer-link-wa:hover, .footer-link-email:hover, .btn-inst-cta:hover, .inst-block-btn:hover {
        filter: brightness(1.15);
        transform: translateY(-1px);
    }
    </style>

    <!-- 6. BANNER DE COOKIES LGPD -->
    <div class="cookie-banner" id="cookieBanner" role="dialog" aria-live="polite">
        <div class="cookie-content">
            <p><?= \App\Core\View::e(\App\Models\Setting::get('cookie_banner_text', 'Utilizamos cookies essenciais para garantir a melhor experiência de navegação e compra segura em conformidade com a LGPD. Ao continuar, você concorda com nossos Termos de Uso e Política de Privacidade.')) ?></p>
            <div class="cookie-actions">
                <button class="cookie-btn-accept" id="acceptCookiesBtn"><?= \App\Core\View::e(\App\Models\Setting::get('cookie_banner_btn', 'Prosseguir')) ?></button>
            </div>
        </div>
    </div>

    <!-- 8. BOTÃO FLUTUANTE DE WHATSAPP CONCIERGE VIP -->
    <?php if (\App\Models\Setting::get('whatsapp_floating_enabled', '1') === '1'): 
        $floatWaNum = preg_replace('/[^0-9]/', '', \App\Models\Setting::get('hero_whatsapp_number', '5511999999999'));
        $floatWaMsg = urlencode(\App\Models\Setting::get('whatsapp_floating_message', 'Olá! Gostaria de um atendimento personalizado sobre as joias autorais Divinium.'));
        $floatPos = \App\Models\Setting::get('whatsapp_floating_position', 'right');
    ?>
    <a href="https://wa.me/<?= $floatWaNum ?>?text=<?= $floatWaMsg ?>" 
       target="_blank" 
       rel="noopener noreferrer" 
       class="floating-whatsapp-vip <?= $floatPos === 'left' ? 'pos-left' : 'pos-right' ?>" 
       aria-label="Atendimento Concierge WhatsApp VIP" 
       title="Falar com o Concierge Divinium">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
        <span class="floating-wa-label"><?= \App\Core\View::e(\App\Models\Setting::get('whatsapp_floating_label', 'Concierge VIP')) ?></span>
    </a>
    <style>
    .floating-whatsapp-vip {
        position: fixed;
        bottom: 24px;
        z-index: 998;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px 10px 14px;
        background: #25D366;
        color: #ffffff;
        border-radius: 50px;
        text-decoration: none;
        box-shadow: 0 8px 24px rgba(37, 211, 102, 0.4), 0 2px 8px rgba(0, 0, 0, 0.3);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        font-family: var(--font-sans, sans-serif);
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .floating-whatsapp-vip.pos-right { right: 24px; }
    .floating-whatsapp-vip.pos-left { left: 24px; }
    .floating-whatsapp-vip:hover {
        background: #20bd5a;
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 12px 30px rgba(37, 211, 102, 0.5);
        color: #ffffff;
    }
    @media (max-width: 600px) {
        .floating-whatsapp-vip {
            bottom: 18px;
            padding: 12px;
            border-radius: 50%;
        }
        .floating-whatsapp-vip.pos-right { right: 18px; }
        .floating-whatsapp-vip.pos-left { left: 18px; }
        .floating-wa-label { display: none; }
    }
    </style>
    <?php endif; ?>

    <!-- Scripts da Loja -->
    <script src="/assets/js/app.js?v=<?= time() ?>"></script>
</body>
</html>