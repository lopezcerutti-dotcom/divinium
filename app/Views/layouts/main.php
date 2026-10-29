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
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/assets/images/favicon-48x48.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
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
        <?php if (!empty(\App\Models\Setting::get('store_card_bg'))): ?>
        body.luxury-theme.page-store .store-product-card,
        body.luxury-theme.page-store .reason-card {
            background-color: <?= \App\Core\View::e(\App\Models\Setting::get('store_card_bg')) ?> !important;
        }
        <?php endif; ?>
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

                <button type="button" class="login-pill-btn" id="loginModalTrigger" aria-label="Acessar Painel Administrativo"><?= \App\Core\View::e(\App\Models\Setting::get('header_login_label', 'Entrar')) ?></button>
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

                <?php if (\App\Models\Setting::get('mobile_nav_login_visible', '1') === '1'): ?>
                    <button type="button" class="mobile-nav-login-btn" id="mobileLoginBtn">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span><?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_login_text', 'Entrar no Painel Administrativo')) ?></span>
                    </button>
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
                <a href="#collections" class="btn-discover" onclick="closeDrawer()"><?= \App\Core\View::e(\App\Models\Setting::get('cart_drawer_discover', 'Descobrir Coleção')) ?></a>
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
            <a href="/checkout" class="btn-checkout-primary"><?= \App\Core\View::e(\App\Models\Setting::get('cart_checkout_btn', 'Finalizar Compra')) ?></a>
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
                <p class="brand-tagline"><?= \App\Core\View::e(\App\Models\Setting::get('footer_tagline', 'Joalheria fina concebida para quem aprecia a nobreza atemporal, o luxo silencioso e o design autoral inconfundível.')) ?></p>
                <div class="footer-socials">
                    <a href="<?= \App\Core\View::e(\App\Models\Setting::get('footer_instagram', 'https://instagram.com')) ?>" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a>
                    <a href="https://wa.me/<?= \App\Core\View::e(\App\Models\Setting::get('hero_whatsapp_number', '5511999999999')) ?>" target="_blank" rel="noopener" aria-label="WhatsApp">WhatsApp VIP</a>
                </div>
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
                    <li><a href="https://wa.me/<?= \App\Core\View::e(\App\Models\Setting::get('hero_whatsapp_number', '5511999999999')) ?>" target="_blank"><?= \App\Core\View::e(\App\Models\Setting::get('footer_whatsapp_label', 'Atendimento via WhatsApp')) ?></a></li>
                    <li><a href="mailto:<?= \App\Core\View::e(\App\Models\Setting::get('footer_email', 'atendimento@divinium.com.br')) ?>"><?= \App\Core\View::e(\App\Models\Setting::get('footer_email', 'atendimento@divinium.com.br')) ?></a></li>
                    <li><a href="/trocas-e-devolucoes"><?= \App\Core\View::e(\App\Models\Setting::get('footer_returns_text', 'Trocas e Devoluções (CDC 7 Dias)')) ?></a></li>
                    <li><a href="/garantia-e-cuidados"><?= \App\Core\View::e(\App\Models\Setting::get('footer_warranty_text', 'Certificado & Cuidados')) ?></a></li>
                    <li><a href="/guia-de-medidas"><?= \App\Core\View::e(\App\Models\Setting::get('footer_size_guide_text', 'Guia de Medidas de Anéis')) ?></a></li>
                </ul>
            </div>

            <?php if (\App\Models\Setting::get('institutional_pages_content_enabled', '1') === '1'): ?>
            <div class="footer-col">
                <h4><?= \App\Core\View::e(\App\Models\Setting::get('footer_col3_title', 'Segurança & Privacidade')) ?></h4>
                <ul>
                    <li><a href="/politica-de-privacidade"><?= \App\Core\View::e(\App\Models\Setting::get('footer_privacy_text', 'Política de Privacidade (LGPD)')) ?></a></li>
                    <li><a href="/termos-de-uso"><?= \App\Core\View::e(\App\Models\Setting::get('footer_terms_text', 'Termos de Uso')) ?></a></li>
                    <li><a href="/dpo"><?= \App\Core\View::e(\App\Models\Setting::get('footer_dpo_text', 'Encarregado de Dados (DPO)')) ?></a></li>
                    <li><span><?= \App\Core\View::e(\App\Models\Setting::get('footer_operating_hours', 'Segunda a Sexta: 09h às 18h')) ?></span></li>
                </ul>
            </div>
            <?php endif; ?>
        </div>

        <div class="container footer-middle">
            <div class="payment-methods-row">
                <span class="payment-badge"><?= \App\Core\View::e(\App\Models\Setting::get('footer_pay_badge1', 'PIX (Aprovação Imediata)')) ?></span>
                <span class="payment-badge"><?= \App\Core\View::e(\App\Models\Setting::get('footer_pay_badge2', 'Cartão de Crédito em até 10x')) ?></span>
                <span class="payment-badge"><?= \App\Core\View::e(\App\Models\Setting::get('footer_pay_badge3', 'Boleto Bancário')) ?></span>
                <span class="payment-badge"><?= \App\Core\View::e(\App\Models\Setting::get('footer_pay_badge4', 'Gateway Homologado ASAAS')) ?></span>
            </div>
        </div>

        <div class="container footer-bottom">
            <div class="legal-info">
                <p><?= \App\Core\View::e(\App\Models\Setting::get('footer_legal_info', 'DIVINIUM JOIAS LTDA. • CNPJ: 00.000.000/0001-00 • São Paulo/SP - Brasil')) ?></p>
                <p><?= \App\Core\View::e(\App\Models\Setting::get('footer_consumer_rights', 'Conforme o Decreto nº 7.962/2013 e Lei 8.078/1990 (Código de Defesa do Consumidor). Direito de arrependimento em até 7 dias corridos após o recebimento.')) ?></p>
            </div>
            <div class="copyright">
                <p>&copy; <?= date('Y') ?> <?= \App\Core\View::e(\App\Models\Setting::get('footer_copyright_brand', 'Divinium. Todos os direitos reservados.')) ?></p>
            </div>
        </div>
    </footer>

    <!-- 6. BANNER DE COOKIES LGPD -->
    <div class="cookie-banner" id="cookieBanner" role="dialog" aria-live="polite">
        <div class="cookie-content">
            <p><?= \App\Core\View::e(\App\Models\Setting::get('cookie_banner_text', 'Utilizamos cookies essenciais para garantir a melhor experiência de navegação e compra segura em conformidade com a LGPD. Ao continuar, você concorda com nossos Termos de Uso e Política de Privacidade.')) ?></p>
            <div class="cookie-actions">
                <button class="cookie-btn-accept" id="acceptCookiesBtn"><?= \App\Core\View::e(\App\Models\Setting::get('cookie_banner_btn', 'Prosseguir')) ?></button>
            </div>
        </div>
    </div>

    <!-- 7. MODAL DE ACESSO AO PAINEL ADMINISTRATIVO -->
    <div class="login-modal-overlay" id="loginModalOverlay" aria-hidden="true">
        <div class="login-modal-box" role="dialog" aria-labelledby="loginModalTitle" aria-modal="true">
            <button class="login-modal-close" id="closeLoginModal" aria-label="Fechar">&times;</button>
            
            <div class="login-modal-brand">
                <span class="brand-sparkle">✦</span>
                <span class="brand-title">DIVINIUM</span>
                <span class="brand-sub">PAINEL DO ADMINISTRADOR</span>
            </div>

            <div class="login-modal-header">
                <h3 id="loginModalTitle"><?= \App\Core\View::e(\App\Models\Setting::get('login_modal_title', 'Acesso aos Bastidores')) ?></h3>
                <p><?= \App\Core\View::e(\App\Models\Setting::get('login_modal_subtitle', 'Controle de produtos, categorias, minerais e gestão do site.')) ?></p>
            </div>

            <!-- Alerta de Erro Dinâmico -->
            <div class="login-error-alert" id="loginErrorAlert" style="display: none;"></div>

            <form id="adminLoginForm" action="/admin/login" method="POST" onsubmit="handleAdminLogin(event)">
                <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">

                <div class="modal-form-group">
                    <label for="adminEmail">E-mail de Acesso</label>
                    <div class="input-icon-wrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <input type="email" id="adminEmail" name="email" class="modal-input" placeholder="seu@email.com" required autocomplete="username">
                    </div>
                </div>

                <div class="modal-form-group">
                    <label for="adminPassword">Senha de Segurança</label>
                    <div class="input-icon-wrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <input type="password" id="adminPassword" name="password" class="modal-input" placeholder="Sua senha de acesso" required autocomplete="current-password">
                    </div>
                </div>

                <button type="submit" class="btn-modal-submit" id="adminLoginSubmitBtn">
                    <span><?= \App\Core\View::e(\App\Models\Setting::get('login_modal_btn', 'Acessar Painel de Controle')) ?></span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>
            </form>
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
