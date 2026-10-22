<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <?php
        $titleFormat = \App\Models\Setting::get('seo_site_title_format', '{title} | Divinium');
        $siteBrand = \App\Models\Setting::get('header_logo_text', 'Divinium');
        $currentTitle = isset($pageTitle) ? $pageTitle : 'Joias Autorais & Luxo Silencioso';
        $renderedTitle = str_replace(['{title}', '{brand}'], [$currentTitle, $siteBrand], $titleFormat);
        if (!str_contains($titleFormat, '{title}')) {
            $renderedTitle = $currentTitle . ' | ' . $siteBrand;
        }
        $metaDesc = isset($pageDescription) ? $pageDescription : \App\Models\Setting::get('seo_meta_description', 'Divinium — Joias autorais criadas com metais nobres e pedras preciosas. Design atemporal, acabamento artesanal e a essência do quiet luxury.');
        $metaKeywords = \App\Models\Setting::get('seo_meta_keywords', 'joias autorais, prata 925, pedras naturais, quiet luxury, alta joalheria brasileira, orgonite nobre');
        $ogImage = \App\Models\Setting::get('seo_og_image', '/assets/images/hero/hero-main.webp');
        $faviconUrl = \App\Models\Setting::get('site_favicon_url', '/assets/images/favicon.ico');
    ?>
    <title><?= \App\Core\View::e($renderedTitle) ?></title>
    
    <meta name="description" content="<?= \App\Core\View::e($metaDesc) ?>">
    <meta name="keywords" content="<?= \App\Core\View::e($metaKeywords) ?>">
    <link rel="canonical" href="https://seudominio.com.br<?= \App\Core\View::e($_SERVER['REQUEST_URI'] ?? '/') ?>">
    <link rel="icon" href="<?= \App\Core\View::e($faviconUrl) ?>">

    <!-- Open Graph / Redes Sociais -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= \App\Core\View::e($siteBrand) ?>">
    <meta property="og:title" content="<?= \App\Core\View::e($renderedTitle) ?>">
    <meta property="og:description" content="<?= \App\Core\View::e($metaDesc) ?>">
    <meta property="og:image" content="<?= \App\Core\View::e($ogImage) ?>">

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
        // Fundo Global do Site
        $siteBgImage = \App\Models\Setting::get('site_bg_image');
        $siteBgOverlay = \App\Models\Setting::get('site_bg_overlay', '0.75');
        $siteBgRepeat = \App\Models\Setting::get('site_bg_repeat', 'cover');

        // Fundo Específico da Home (/ ou Início)
        $homeBgColor = \App\Models\Setting::get('home_bg_color');
        $homeCardBg = \App\Models\Setting::get('home_card_bg');

        // Fundo Específico do Hero (Banner Inicial da Home)
        $heroBgImage = \App\Models\Setting::get('hero_bg_image');
        $heroBgOverlay = \App\Models\Setting::get('hero_bg_overlay', '0.60');

        // Fundo Específico da Boutique / Vitrine (/loja)
        $storeBgColor = \App\Models\Setting::get('store_bg_color');
        $storeBgImage = \App\Models\Setting::get('store_bg_image');
        $storeBgOverlay = \App\Models\Setting::get('store_bg_overlay', '0.75');
        $storeBgRepeat = \App\Models\Setting::get('store_bg_repeat', 'cover');
        $storeCardBg = \App\Models\Setting::get('store_card_bg');

        // Fundo Específico da Página de Detalhe da Joia (/produto/{slug})
        $productBgColor = \App\Models\Setting::get('product_bg_color');
        $productBgImage = \App\Models\Setting::get('product_bg_image');
        $productBgOverlay = \App\Models\Setting::get('product_bg_overlay', '0.75');
        $productCardBg = \App\Models\Setting::get('product_card_bg');

        // Fundo Específico do Checkout (/checkout)
        $checkoutBgColor = \App\Models\Setting::get('checkout_bg_color');
        $checkoutBgImage = \App\Models\Setting::get('checkout_bg_image');
        $checkoutBgOverlay = \App\Models\Setting::get('checkout_bg_overlay', '0.75');
        $checkoutCardBg = \App\Models\Setting::get('checkout_card_bg');

        // Fundo Específico das Páginas Institucionais (/politica-de-privacidade, /termos-de-uso, etc)
        $institutionalBgColor = \App\Models\Setting::get('institutional_bg_color');
        $institutionalBgImage = \App\Models\Setting::get('institutional_bg_image');
        $institutionalBgOverlay = \App\Models\Setting::get('institutional_bg_overlay', '0.75');
        $institutionalCardBg = \App\Models\Setting::get('institutional_card_bg');

        // Fundo do Cabeçalho, Rodapé e Top Banner
        $headerBgColor = \App\Models\Setting::get('header_bg_color');
        $headerTextColor = \App\Models\Setting::get('header_text_color');
        $footerBgColor = \App\Models\Setting::get('footer_bg_color');
        $footerTextColor = \App\Models\Setting::get('footer_text_color');
        $topBannerBgColor = \App\Models\Setting::get('top_banner_bg_color');
        $topBannerTextColor = \App\Models\Setting::get('top_banner_text_color');

        // 1. Cores Globais da Marca (Base Primária do Estúdio de Cores)
        $customColorBg = \App\Models\Setting::get('custom_color_bg');
        $customColorBgAlt = \App\Models\Setting::get('custom_color_bg_alt');
        $customColorSurface = \App\Models\Setting::get('custom_color_surface');
        $customColorPrimary = \App\Models\Setting::get('custom_color_primary');
        $customColorAccent = \App\Models\Setting::get('custom_color_accent');
        $customColorText = \App\Models\Setting::get('custom_color_text');
        $customColorTextMuted = \App\Models\Setting::get('custom_color_text_muted');

        // Padrões do tema canônico selecionado caso a cor global não esteja definida
        $themeDefaults = [
            'theme-silver-noir' => [
                'bg' => '#F7F7F5', 'bg_alt' => '#FAFAF8', 'surface' => '#FFFFFF',
                'primary' => '#A7ADB4', 'accent' => '#737B85',
                'text' => '#25272A', 'text_muted' => '#737B85',
                'footer_bg' => '#1E2023', 'footer_text' => '#CBD0D6'
            ],
            'theme-pearl-platinum' => [
                'bg' => '#FAFAF8', 'bg_alt' => '#FFFFFF', 'surface' => '#FFFFFF',
                'primary' => '#CBD0D6', 'accent' => '#858C95',
                'text' => '#242629', 'text_muted' => '#858C95',
                'footer_bg' => '#1A1C1E', 'footer_text' => '#CBD0D6'
            ],
            'theme-nude-pearl' => [
                'bg' => '#F8F5F2', 'bg_alt' => '#FAF8F5', 'surface' => '#FFFFFF',
                'primary' => '#C8B9AE', 'accent' => '#B3A49A',
                'text' => '#302C29', 'text_muted' => '#81766F',
                'footer_bg' => '#24201D', 'footer_text' => '#D5CBC3'
            ],
            'theme-contemporary-graphite' => [
                'bg' => '#F2F3F4', 'bg_alt' => '#FAFAF8', 'surface' => '#FFFFFF',
                'primary' => '#9DA5AE', 'accent' => '#626B75',
                'text' => '#25272A', 'text_muted' => '#626B75',
                'footer_bg' => '#1C1E21', 'footer_text' => '#CBD0D6'
            ]
        ];
        $activeThemeDef = $themeDefaults[$canonicalTheme] ?? $themeDefaults['theme-silver-noir'];

        $effGlobalBg = !empty($customColorBg) ? $customColorBg : $activeThemeDef['bg'];
        $effGlobalBgAlt = !empty($customColorBgAlt) ? $customColorBgAlt : $activeThemeDef['bg_alt'];
        $effGlobalSurface = !empty($customColorSurface) ? $customColorSurface : $activeThemeDef['surface'];
        $effGlobalPrimary = !empty($customColorPrimary) ? $customColorPrimary : $activeThemeDef['primary'];
        $effGlobalAccent = !empty($customColorAccent) ? $customColorAccent : $activeThemeDef['accent'];
        $effGlobalText = !empty($customColorText) ? $customColorText : $activeThemeDef['text'];
        $effGlobalTextMuted = !empty($customColorTextMuted) ? $customColorTextMuted : $activeThemeDef['text_muted'];

        // Regra de Herança Estrita: Se um campo específico estiver vazio, ele herda diretamente a cor global correspondente
        $effHomeBg = !empty($homeBgColor) ? $homeBgColor : $effGlobalBg;
        $effHomeCard = !empty($homeCardBg) ? $homeCardBg : $effGlobalSurface;

        $effStoreBg = !empty($storeBgColor) ? $storeBgColor : $effGlobalBg;
        $effStoreCard = !empty($storeCardBg) ? $storeCardBg : $effGlobalSurface;

        $effProductBg = !empty($productBgColor) ? $productBgColor : $effGlobalBg;
        $effProductCard = !empty($productCardBg) ? $productCardBg : $effGlobalSurface;

        $effCheckoutBg = !empty($checkoutBgColor) ? $checkoutBgColor : $effGlobalBg;
        $effCheckoutCard = !empty($checkoutCardBg) ? $checkoutCardBg : $effGlobalSurface;

        $effInstitutionalBg = !empty($institutionalBgColor) ? $institutionalBgColor : $effGlobalBg;
        $effInstitutionalCard = !empty($institutionalCardBg) ? $institutionalCardBg : $effGlobalSurface;

        $effHeaderBg = !empty($headerBgColor) ? $headerBgColor : $effGlobalSurface;
        $effHeaderText = !empty($headerTextColor) ? $headerTextColor : $effGlobalText;

        $effFooterBg = !empty($footerBgColor) ? $footerBgColor : $activeThemeDef['footer_bg'];
        $effFooterText = !empty($footerTextColor) ? $footerTextColor : $activeThemeDef['footer_text'];

        $effTopBannerBg = !empty($topBannerBgColor) ? $topBannerBgColor : '#1A1C1E';
        $effTopBannerText = !empty($topBannerTextColor) ? $topBannerTextColor : '#CBD0D6';

        // Cálculo dinâmico de contraste para textos de botões e estados interativos
        $calcContrastColor = function($hexColor) {
            $h = ltrim((string)$hexColor, '#');
            if (strlen($h) === 3) {
                $h = $h[0].$h[0].$h[1].$h[1].$h[2].$h[2];
            }
            if (strlen($h) !== 6) return '#FFFFFF';
            $r = hexdec(substr($h, 0, 2));
            $g = hexdec(substr($h, 2, 2));
            $b = hexdec(substr($h, 4, 2));
            $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
            return ($yiq >= 140) ? '#121110' : '#FFFFFF';
        };
        $effBtnText = $calcContrastColor($effGlobalPrimary);
        $effBtnHoverText = $calcContrastColor($effGlobalAccent);

        // Tipografia e Formas
        $dynSerif = \App\Models\Setting::get('custom_font_serif');
        $dynSans = \App\Models\Setting::get('custom_font_sans');
        $dynBtnRadius = \App\Models\Setting::get('theme_btn_border_radius', \App\Models\Setting::get('custom_border_radius', ''));
        $dynBtnTransform = \App\Models\Setting::get('theme_btn_text_transform', '');
        $dynHeroSize = \App\Models\Setting::get('hero_title_size', '');
        $dynBodySize = \App\Models\Setting::get('body_font_size', '');
        $dynSpacingY = \App\Models\Setting::get('section_spacing_y', '');
        $dynAlign = \App\Models\Setting::get('editorial_text_alignment', '');
    ?>
    <style id="divinium-design-tokens">
        :root, html, body.luxury-theme {
            <?php if (!empty($dynSerif)): ?>--font-serif: <?= $dynSerif ?> !important;<?php endif; ?>
            <?php if (!empty($dynSans)): ?>--font-sans: <?= $dynSans ?> !important;<?php endif; ?>

            /* Cores Globais da Marca Centralizadas */
            --bg-light: <?= $effGlobalBg ?> !important;
            --bg-white: <?= $effGlobalSurface ?> !important;
            --bg-dark: <?= $effGlobalBg ?> !important;
            --bg-alt: <?= $effGlobalBgAlt ?> !important;
            --surface-alt: <?= $effGlobalBgAlt ?> !important;
            --bg-dark-card: <?= $effGlobalSurface ?> !important;
            --card-bg: <?= $effGlobalSurface ?> !important;
            --surface-dark: <?= $effGlobalSurface ?> !important;

            --gold-accent: <?= $effGlobalPrimary ?> !important;
            --gold-hover: <?= $effGlobalAccent ?> !important;
            --silver-accent: <?= $effGlobalPrimary ?> !important;
            --silver-hover: <?= $effGlobalAccent ?> !important;

            --text-primary: <?= $effGlobalText ?> !important;
            --text-light: <?= $effGlobalText ?> !important;
            --text-secondary: <?= $effGlobalTextMuted ?> !important;
            --text-muted: <?= $effGlobalTextMuted ?> !important;
            --text-light-muted: <?= $effGlobalTextMuted ?> !important;

            /* Variáveis Específicas por Página com Herança Estrita */
            --home-bg: <?= $effHomeBg ?> !important;
            --home-card-bg: <?= $effHomeCard ?> !important;

            --store-bg: <?= $effStoreBg ?> !important;
            --store-card-bg: <?= $effStoreCard ?> !important;

            --product-bg: <?= $effProductBg ?> !important;
            --product-card-bg: <?= $effProductCard ?> !important;

            --checkout-bg: <?= $effCheckoutBg ?> !important;
            --checkout-card-bg: <?= $effCheckoutCard ?> !important;

            --page-bg: <?= $effInstitutionalBg ?> !important;
            --page-card-bg: <?= $effInstitutionalCard ?> !important;

            --header-bg: <?= $effHeaderBg ?> !important;
            --header-text: <?= $effHeaderText ?> !important;

            --footer-bg: <?= $effFooterBg ?> !important;
            --footer-text: <?= $effFooterText ?> !important;

            --top-banner-bg: <?= $effTopBannerBg ?> !important;
            --top-banner-text: <?= $effTopBannerText ?> !important;
        }

        /* 1. FUNDO BASE E TEXTO GLOBAL EM TODAS AS PÁGINAS */
        body.luxury-theme {
            background-color: <?= $effGlobalBg ?> !important;
            color: <?= $effGlobalText ?> !important;
        }

        /* Títulos Nobres Globais */
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
            color: <?= $effGlobalText ?> !important;
        }

        /* Textos Secundários / Muted */
        body.luxury-theme .hero-subtitle,
        body.luxury-theme .store-hero-subtitle,
        body.luxury-theme .institutional-subtitle,
        body.luxury-theme .product-storytelling-text,
        body.luxury-theme .editorial-desc,
        body.luxury-theme .spec-term,
        body.luxury-theme .guarantee-desc {
            color: <?= $effGlobalTextMuted ?> !important;
        }

        /* 2. PÁGINA INICIAL (HOME) */
        body.luxury-theme .hero-section,
        body.luxury-theme .about-manifesto-section,
        body.luxury-theme .showcase-section,
        body.luxury-theme .signature-services-section,
        body.luxury-theme .heritage-editorial-section,
        body.luxury-theme .testimonials-section {
            background-color: <?= $effHomeBg ?> !important;
        }
        body.luxury-theme .showcase-lifestyle-card,
        body.luxury-theme .service-card,
        body.luxury-theme .testimonial-card,
        body.luxury-theme .about-micro-feature {
            background-color: <?= $effHomeCard ?> !important;
            background: <?= $effHomeCard ?> !important;
        }

        /* 3. BOUTIQUE & CATÁLOGO (/loja) */
        body.luxury-theme .store-hero-section,
        body.luxury-theme .store-catalog-section,
        body.luxury-theme .store-reasons-section {
            background-color: <?= $effStoreBg ?> !important;
        }
        body.luxury-theme .store-product-card,
        body.luxury-theme .reason-card,
        body.luxury-theme .related-product-card {
            background-color: <?= $effStoreCard ?> !important;
            background: <?= $effStoreCard ?> !important;
        }

        /* 4. DETALHE DO PRODUTO (/produto/{slug}) */
        body.luxury-theme .product-detail-page,
        body.luxury-theme .product-breadcrumb-nav {
            background-color: <?= $effProductBg ?> !important;
        }
        body.luxury-theme .product-gallery-card,
        body.luxury-theme .product-info-panel,
        body.luxury-theme .product-price-box,
        body.luxury-theme .product-price-block,
        body.luxury-theme .product-purchase-channels-box,
        body.luxury-theme .product-variations-section,
        body.luxury-theme .product-description-card,
        body.luxury-theme .product-specs-card,
        body.luxury-theme .product-guarantee-card {
            background-color: <?= $effProductCard ?> !important;
            background: <?= $effProductCard ?> !important;
        }

        /* 5. CHECKOUT SEGURO (/checkout) */
        body.luxury-theme .checkout-page-container {
            background-color: <?= $effCheckoutBg ?> !important;
        }
        body.luxury-theme .checkout-card,
        body.luxury-theme .checkout-summary-card,
        body.luxury-theme .payment-instructions-card {
            background-color: <?= $effCheckoutCard ?> !important;
            background: <?= $effCheckoutCard ?> !important;
        }

        /* 6. PÁGINAS INSTITUCIONAIS & POLÍTICAS */
        body.luxury-theme .institutional-page-container,
        body.luxury-theme .institutional-hero {
            background-color: <?= $effInstitutionalBg ?> !important;
        }
        body.luxury-theme .institutional-card {
            background-color: <?= $effInstitutionalCard ?> !important;
            background: <?= $effInstitutionalCard ?> !important;
        }

        /* 7. CABEÇALHO / MENU SUPERIOR */
        body.luxury-theme .main-header {
            background-color: <?= $effHeaderBg ?> !important;
            background: <?= $effHeaderBg ?> !important;
        }
        body.luxury-theme .main-header .nav-link,
        body.luxury-theme .main-header .brand-name,
        body.luxury-theme .main-header .header-action-btn,
        body.luxury-theme .main-header .action-btn,
        body.luxury-theme .main-header .logo-text,
        body.luxury-theme .main-header .theme-toggle-btn {
            color: <?= $effHeaderText ?> !important;
        }

        /* 8. RODAPÉ OFICIAL */
        body.luxury-theme .main-footer {
            background-color: <?= $effFooterBg ?> !important;
            background: <?= $effFooterBg ?> !important;
        }
        body.luxury-theme .main-footer,
        body.luxury-theme .main-footer p,
        body.luxury-theme .main-footer a,
        body.luxury-theme .main-footer .footer-col-title,
        body.luxury-theme .main-footer .footer-col h4,
        body.luxury-theme .main-footer .footer-logo,
        body.luxury-theme .main-footer .brand-tagline,
        body.luxury-theme .main-footer .footer-bottom {
            color: <?= $effFooterText ?> !important;
        }

        /* 9. TOP BANNER DE ANÚNCIOS */
        body.luxury-theme .top-announcement-bar {
            background-color: <?= $effTopBannerBg ?> !important;
            background: <?= $effTopBannerBg ?> !important;
        }
        body.luxury-theme .top-announcement-bar,
        body.luxury-theme .top-announcement-bar span,
        body.luxury-theme .top-announcement-bar strong {
            color: <?= $effTopBannerText ?> !important;
        }

        /* 10. BOTÕES PRINCIPAIS, DESTAQUES E ESTADOS DE HOVER */
        body.luxury-theme .btn-hero-gold,
        body.luxury-theme .btn-gold,
        body.luxury-theme .stage-buy-btn,
        body.luxury-theme .btn-checkout-primary,
        body.luxury-theme .btn-checkout-submit,
        body.luxury-theme .btn-card-explore,
        body.luxury-theme .overlay-cta-text,
        body.luxury-theme .btn-store-back,
        body.luxury-theme .btn-concierge-help,
        body.luxury-theme .store-filter-pill.active {
            background: <?= $effGlobalPrimary ?> !important;
            background-color: <?= $effGlobalPrimary ?> !important;
            color: <?= $effGlobalText ?> !important;
            border-color: <?= $effGlobalAccent ?> !important;
        }

        body.luxury-theme .btn-hero-gold:hover,
        body.luxury-theme .btn-gold:hover,
        body.luxury-theme .stage-buy-btn:hover,
        body.luxury-theme .btn-checkout-primary:hover,
        body.luxury-theme .btn-checkout-submit:hover,
        body.luxury-theme .btn-card-explore:hover,
        body.luxury-theme .btn-concierge-help:hover {
            background: <?= $effGlobalAccent ?> !important;
            background-color: <?= $effGlobalAccent ?> !important;
            color: #ffffff !important;
            border-color: <?= $effGlobalAccent ?> !important;
        }

        /* 11. IMAGEM / TEXTURA DE FUNDO GLOBAL */
        <?php if (!empty($siteBgImage)): ?>
        body.luxury-theme {
            background-color: <?= $effGlobalBg ?> !important;
            background-image: linear-gradient(rgba(13, 12, 11, <?= (float)$siteBgOverlay ?>), rgba(13, 12, 11, <?= min(0.98, (float)$siteBgOverlay + 0.15) ?>)), url('<?= \App\Core\View::e($siteBgImage) ?>') !important;
            background-size: <?= $siteBgRepeat === 'repeat' ? 'auto' : 'cover' ?> !important;
            background-repeat: <?= $siteBgRepeat === 'repeat' ? 'repeat' : 'no-repeat' ?> !important;
            background-attachment: fixed !important;
            background-position: center center !important;
        }
        <?php endif; ?>

        /* 12. HERO DA PÁGINA INICIAL COM IMAGEM */
        <?php if (!empty($heroBgImage)): ?>
        .hero-section {
            background-image: linear-gradient(rgba(13, 12, 11, <?= (float)$heroBgOverlay ?>), rgba(13, 12, 11, <?= min(0.95, (float)$heroBgOverlay + 0.25) ?>)), url('<?= \App\Core\View::e($heroBgImage) ?>') !important;
            background-size: cover !important;
            background-position: center center !important;
            background-repeat: no-repeat !important;
        }
        <?php endif; ?>

        /* 13. BOUTIQUE COM IMAGEM */
        <?php if (!empty($storeBgImage)): ?>
        .store-hero-section {
            background-image: linear-gradient(rgba(13, 12, 11, <?= (float)$storeBgOverlay ?>), rgba(13, 12, 11, <?= min(0.98, (float)$storeBgOverlay + 0.15) ?>)), url('<?= \App\Core\View::e($storeBgImage) ?>') !important;
            background-size: <?= $storeBgRepeat === 'repeat' ? 'auto' : 'cover' ?> !important;
            background-repeat: <?= $storeBgRepeat === 'repeat' ? 'repeat' : 'no-repeat' ?> !important;
            background-position: center center !important;
        }
        <?php endif; ?>

        /* 14. DETALHE DO PRODUTO COM IMAGEM */
        <?php if (!empty($productBgImage)): ?>
        .product-detail-page {
            background-image: linear-gradient(rgba(13, 12, 11, <?= (float)$productBgOverlay ?>), rgba(13, 12, 11, <?= min(0.98, (float)$productBgOverlay + 0.15) ?>)), url('<?= \App\Core\View::e($productBgImage) ?>') !important;
            background-size: cover !important;
            background-attachment: fixed !important;
            background-position: center center !important;
        }
        <?php endif; ?>

        /* 15. CHECKOUT COM IMAGEM */
        <?php if (!empty($checkoutBgImage)): ?>
        .checkout-page-container {
            background-image: linear-gradient(rgba(13, 12, 11, <?= (float)$checkoutBgOverlay ?>), rgba(13, 12, 11, <?= min(0.98, (float)$checkoutBgOverlay + 0.15) ?>)), url('<?= \App\Core\View::e($checkoutBgImage) ?>') !important;
            background-size: cover !important;
            background-attachment: fixed !important;
            background-position: center center !important;
        }
        <?php endif; ?>

        /* 16. INSTITUCIONAIS COM IMAGEM */
        <?php if (!empty($institutionalBgImage)): ?>
        .institutional-page-container {
            background-image: linear-gradient(rgba(13, 12, 11, <?= (float)$institutionalBgOverlay ?>), rgba(13, 12, 11, <?= min(0.98, (float)$institutionalBgOverlay + 0.15) ?>)), url('<?= \App\Core\View::e($institutionalBgImage) ?>') !important;
            background-size: cover !important;
            background-attachment: fixed !important;
            background-position: center center !important;
        }
        <?php endif; ?>

        /* 17. TIPOGRAFIA & DIMENSÕES DINÂMICAS */
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
    </style>
</head>
<body class="luxury-theme <?= \App\Core\View::e($canonicalTheme) ?> <?= $rawServerTheme !== $canonicalTheme ? \App\Core\View::e($rawServerTheme) : '' ?>">
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
                        <small><?= \App\Core\View::e(\App\Models\Setting::get('mobile_nav_perk_text', '✨ Joalheria Autoral Brasileira • Prata 925 & Gemas Raras')) ?></small>
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
