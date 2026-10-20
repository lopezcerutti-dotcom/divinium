<?php
/**
 * VIEW: LOJA & BOUTIQUE OFICIAL DIVINIUM
 * Totalmente personalizável via Painel Administrativo (/admin/boutique e /admin/produtos).
 * Suporte a múltiplos canais de venda por produto com preços e botões independentes,
 * e controle individual de visibilidade para todos os elementos.
 */
use App\Models\Setting;
use App\Core\View;

if (!function_exists('getPlatformIconSvg')) {
    function getPlatformIconSvg(string $platform): string {
        $plat = strtolower(trim($platform));
        if (str_contains($plat, 'shopee')) {
            return '<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" class="shopee-icon-svg"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h2v2c0 .55.45 1 1 1s1-.45 1-1V8h6v2c0 .55.45 1 1 1s1-.45 1-1V8h2v12z"/></svg>';
        }
        if (str_contains($plat, 'mercado')) {
            return '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>';
        }
        if (str_contains($plat, 'amazon')) {
            return '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>';
        }
        if (str_contains($plat, 'tiktok')) {
            return '<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298-.002.595.042.88.13V9.4a6.33 6.33 0 0 0-1-.08A6.34 6.34 0 0 0 3 15.66a6.34 6.34 0 0 0 10.82 4.49 6.27 6.27 0 0 0 1.87-4.49V8.62a8.28 8.28 0 0 0 3.9 1.07V6.69z"/></svg>';
        }
        if (str_contains($plat, 'whatsapp')) {
            return '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>';
        }
        return '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
    }
}

if (!function_exists('getPlatformEmoji')) {
    function getPlatformEmoji(string $platform): string {
        $plat = strtolower(trim($platform));
        if (str_contains($plat, 'shopee')) return '🛍️';
        if (str_contains($plat, 'mercado')) return '📦';
        if (str_contains($plat, 'amazon')) return '🚚';
        if (str_contains($plat, 'tiktok')) return '🎬';
        if (str_contains($plat, 'whatsapp')) return '💬';
        return '✦';
    }
}

if (!function_exists('getButtonColorAndSizeAttrs')) {
    function getButtonColorAndSizeAttrs(?string $color, ?string $size): array {
        $color = trim((string)$color);
        $size = trim((string)$size);
        if ($size === '' || !in_array($size, ['sm', 'md', 'lg', 'full'])) {
            $size = Setting::get('store_btn_default_size', 'md');
        }
        if ($color === '' || $color === 'default') {
            $color = Setting::get('store_btn_default_color', 'default');
        }

        $classes = ['btn-size-' . $size];
        $inlineStyle = '';

        if ($color !== 'default' && $color !== '') {
            if (str_starts_with($color, '#')) {
                // Cor personalizada Hexadecimal
                $hex = ltrim($color, '#');
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
                $luminance = ($r * 0.299 + $g * 0.587 + $b * 0.114);
                $textColor = $luminance > 160 ? '#121110' : '#ffffff';
                $inlineStyle = "background: {$color} !important; border-color: {$color} !important; color: {$textColor} !important;";
            } else {
                $classes[] = 'btn-color-' . $color;
            }
        }

        return [
            'class' => implode(' ', $classes),
            'style' => $inlineStyle
        ];
    }
}
?>

<!-- 1. HERO HEADER DA BOUTIQUE -->
<?php if (Setting::get('store_hero_visible', '1') === '1'): ?>
<section class="store-hero-section">
    <div class="store-hero-glow"></div>
    <div class="container store-hero-container">
        
        <?php if (Setting::get('store_hero_badge_visible', '1') === '1'): ?>
        <div class="store-hero-badge">
            <span class="badge-sparkle">✦</span>
            <span><?= View::e(Setting::get('store_hero_badge', 'VITRINE OFICIAL DIVINIUM • CANAIS HOMOLOGADOS')) ?></span>
        </div>
        <?php endif; ?>

        <?php if (Setting::get('store_hero_title_visible', '1') === '1'): ?>
        <h1 class="store-hero-title">
            <?= View::e(Setting::get('store_hero_title', 'Joias Autorais &')) ?> <span class="gold-gradient-glow"><?= View::e(Setting::get('store_hero_highlight', 'Sintonia Mineral')) ?></span>
        </h1>
        <?php endif; ?>

        <?php if (Setting::get('store_hero_subtitle_visible', '1') === '1'): ?>
        <p class="store-hero-subtitle">
            <?= View::e(Setting::get('store_hero_subtitle', 'Cada joia Divinium une a precisão da prata de lei 925 à energia de gemas minerais raras e conchas naturais. Escolha sua peça e finalize sua compra com toda a segurança, cupons e rastreamento nos nossos canais oficiais homologados.')) ?>
        </p>
        <?php endif; ?>

        <!-- Faixa de Garantia & Confiança da Boutique -->
        <?php if (Setting::get('store_trust_visible', '1') === '1'): ?>
        <div class="store-trust-badges">
            <?php if (Setting::get('store_badge_1_visible', '1') === '1'): ?>
            <div class="trust-badge-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                <span><?= View::e(Setting::get('store_badge_1', 'Compra 100% Protegida • Garantia Oficial')) ?></span>
            </div>
            <?php endif; ?>

            <?php if (Setting::get('store_badge_2_visible', '1') === '1'): ?>
            <div class="trust-badge-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                <span><?= View::e(Setting::get('store_badge_2', 'Certificado de Prata de Lei 925')) ?></span>
            </div>
            <?php endif; ?>

            <?php if (Setting::get('store_badge_3_visible', '1') === '1'): ?>
            <div class="trust-badge-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                <span><?= View::e(Setting::get('store_badge_3', 'Envio Rápido e Seguro para todo o Brasil')) ?></span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</section>
<?php endif; ?>

<!-- 2. CATÁLOGO DE PRODUTOS -->
<section class="store-catalog-section" id="catalogo">
    <div class="container">
        
        <!-- Barra de Filtros de Coleções / Minerais -->
        <?php if (Setting::get('store_filter_bar_visible', '1') === '1'): ?>
        <div class="store-filter-bar">
            <div class="filter-pills-wrap">
                <?php if (Setting::get('store_filter_all_visible', '1') === '1'): ?>
                <button class="store-filter-pill active" data-filter="all" onclick="filterStore('all', this)">
                    <span><?= View::e(Setting::get('store_filter_all', 'Todas as Peças')) ?></span>
                    <span class="count-pill"><?= count($products ?? []) ?></span>
                </button>
                <?php endif; ?>
                
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <button class="store-filter-pill" data-filter="cat-<?= $cat['id'] ?>" onclick="filterStore('cat-<?= $cat['id'] ?>', this)">
                            <span><?= View::e($cat['name']) ?></span>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?php if (Setting::get('store_info_pill_visible', '1') === '1'): ?>
            <div class="store-info-pill">
                <span class="shopee-dot"></span>
                <span><?= View::e(Setting::get('store_info_pill_text', 'Escolha seu canal de compra favorito com segurança')) ?></span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Grade de Produtos -->
        <?php if (!empty($products)): ?>
            <div class="store-products-grid" id="storeGrid">
                <?php foreach ($products as $prod): ?>
                    <?php 
                        // Base / Reference Price
                        $basePriceCents = (int) ($prod['price_cents'] ?? 38000);
                        $basePriceFormatted = number_format($basePriceCents / 100, 2, ',', '.');
                        $pixPriceFormatted = number_format(($basePriceCents * 0.95) / 100, 2, ',', '.');
                        
                        $filterClass = 'cat-' . ($prod['category_id'] ?? 'none');
                        $mineralName = !empty($prod['stone_mineral']) ? $prod['stone_mineral'] : (!empty($prod['category_name']) ? $prod['category_name'] : 'Mineral Natural');
                        
                        // Active Links & Independent Platform Pricing
                        $links = !empty($prod['links']) && is_array($prod['links']) ? $prod['links'] : [];
                        if (empty($links) && !empty($prod['external_url'])) {
                            $links = [
                                [
                                    'id' => 0,
                                    'platform_name' => 'Shopee',
                                    'url' => $prod['external_url'],
                                    'price_cents' => $basePriceCents,
                                    'button_text' => 'Comprar na Shopee Oficial',
                                    'button_color' => 'default',
                                    'button_size' => 'md',
                                    'is_active' => 1
                                ]
                            ];
                        }
                        
                        $primaryLink = !empty($links) ? $links[0] : null;
                        $primaryUrl = $primaryLink ? $primaryLink['url'] : 'https://shopee.com.br/search?keyword=' . urlencode('Divinium ' . ($prod['name'] ?? 'joia'));
                        $primaryPlatform = $primaryLink && !empty($primaryLink['platform_name']) ? $primaryLink['platform_name'] : 'Shopee';
                        $platformSlug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $primaryPlatform));
                        $primaryPriceCents = ($primaryLink && !empty($primaryLink['price_cents'])) ? (int)$primaryLink['price_cents'] : $basePriceCents;
                        $primaryPriceFormatted = number_format($primaryPriceCents / 100, 2, ',', '.');
                        $primaryBtnText = ($primaryLink && !empty($primaryLink['button_text'])) ? $primaryLink['button_text'] : ('Comprar na ' . $primaryPlatform);
                        
                        // Specs / Details
                        $materials = trim($prod['materials'] ?? '');
                        $dimensions = trim($prod['dimensions'] ?? '');
                        $colors = trim($prod['colors'] ?? '');
                        $sku = trim($prod['sku'] ?? '');
                        $stockStatus = $prod['stock_status'] ?? 'in_stock';

                        // Controles de visibilidade por produto e globais
                        $showProdBadge = (int) ($prod['show_badge'] ?? 1);
                        $showProdSpecs = (int) ($prod['show_specs'] ?? 1);
                        $showProdDesc = (int) ($prod['show_description'] ?? 1);

                        $showGlobalMetalBadge = Setting::get('store_card_badge_metal_visible', '1') === '1';
                        $showGlobalChannelBadge = Setting::get('store_card_channel_badge_visible', '1') === '1';
                        $showGlobalMineral = Setting::get('store_card_mineral_visible', '1') === '1';
                        $showGlobalDesc = Setting::get('store_card_desc_visible', '1') === '1';
                        $showGlobalSpecs = Setting::get('store_card_specs_visible', '1') === '1';
                        $showGlobalPix = Setting::get('store_pix_discount_visible', '1') === '1';
                        $showGlobalInstallments = Setting::get('store_installments_visible', '1') === '1';
                        $showGlobalMultiChannel = Setting::get('store_multi_channel_visible', '1') === '1';

                        // Decide se a barra de badges flutuante deve aparecer
                        $hasBadgesToRender = $showProdBadge === 1 && (
                            $showGlobalMetalBadge ||
                            $showGlobalChannelBadge ||
                            in_array($stockStatus, ['preorder', 'out_of_stock'])
                        );
                    ?>
                    
                    <article class="store-product-card" data-category="<?= $filterClass ?>" data-track-product-id="<?= $prod['id'] ?>" data-track-product-name="<?= View::e($prod['name']) ?>">
                        <!-- Imagem do Produto com Link Direto para o Canal Principal -->
                        <a href="<?= View::e($primaryUrl) ?>" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="store-card-media"
                           data-track-product-id="<?= $prod['id'] ?>"
                           data-track-product-name="<?= View::e($prod['name']) ?>"
                           data-track-source="store_media_<?= $platformSlug ?>"
                           title="Ver <?= View::e($prod['name']) ?> na <?= View::e($primaryPlatform) ?>">
                            
                            <img src="<?= View::e($prod['image_url'] ?: '/assets/images/hero/featured-green-shell.jpg') ?>" 
                                 alt="<?= View::e($prod['name']) ?>"
                                 loading="lazy"
                                 class="store-card-img"
                                 onerror="this.onerror=null; this.src='/assets/images/hero/featured-green-shell.jpg';">
                            
                            <!-- Badges Flutuantes (Respeita configuração individual do produto e global) -->
                            <?php if ($hasBadgesToRender): ?>
                            <div class="card-badges-top">
                                <?php if ($stockStatus === 'preorder'): ?>
                                    <span class="badge-metal badge-stock-preorder">Sob Encomenda</span>
                                <?php elseif ($stockStatus === 'out_of_stock'): ?>
                                    <span class="badge-metal badge-stock-out">Indisponível</span>
                                <?php elseif ($showGlobalMetalBadge): ?>
                                    <span class="badge-metal"><?= View::e(Setting::get('store_card_badge_metal', 'Prata 925')) ?></span>
                                <?php endif; ?>

                                <?php if ($showGlobalChannelBadge): ?>
                                    <?php if (count($links) > 1): ?>
                                        <span class="badge-shopee-tag" style="background: rgba(212,186,136,0.9); color: #0d0c0b; font-weight: 700;">
                                            ✦ <?= count($links) ?> Canais Oficiais
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-shopee-tag platform-tag-<?= $platformSlug ?>">
                                            <?= View::e($primaryPlatform) ?> ↗
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <div class="card-hover-overlay">
                                <span class="overlay-cta-text">
                                    <span><?= View::e($primaryBtnText) ?></span>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                </span>
                            </div>
                        </a>

                        <!-- Informações do Produto -->
                        <div class="store-card-body">
                            <?php if ($showGlobalMineral): ?>
                            <div class="store-card-mineral">
                                <span class="mineral-indicator-dot"></span>
                                <span class="mineral-text"><?= View::e($mineralName) ?></span>
                            </div>
                            <?php endif; ?>

                            <h3 class="store-card-title">
                                <a href="<?= View::e($primaryUrl) ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   data-track-product-id="<?= $prod['id'] ?>"
                                   data-track-product-name="<?= View::e($prod['name']) ?>"
                                   data-track-source="store_title_<?= $platformSlug ?>">
                                    <?= View::e($prod['name']) ?>
                                </a>
                            </h3>

                            <?php if ($showProdDesc === 1 && $showGlobalDesc): ?>
                            <p class="store-card-desc">
                                <?= View::e($prod['short_description'] ?: ($prod['description'] ?: 'Joia autoral em prata legítima 925 com lapidação mineral e acabamento artesanal contemporâneo.')) ?>
                            </p>
                            <?php endif; ?>

                            <!-- Especificações Técnicas (Materiais, Medidas, SKU) -->
                            <?php if ($showProdSpecs === 1 && $showGlobalSpecs && ($materials || $dimensions || $colors || $sku)): ?>
                                <div class="store-card-specs">
                                    <?php if ($materials): ?>
                                        <span class="spec-chip" title="Materiais">💎 <?= View::e($materials) ?></span>
                                    <?php endif; ?>
                                    <?php if ($dimensions): ?>
                                        <span class="spec-chip" title="Medidas / Dimensões">📐 <?= View::e($dimensions) ?></span>
                                    <?php endif; ?>
                                    <?php if ($colors): ?>
                                        <span class="spec-chip" title="Cor / Acabamento">🎨 <?= View::e($colors) ?></span>
                                    <?php endif; ?>
                                    <?php if ($sku): ?>
                                        <span class="spec-chip store-card-sku" title="SKU / Código Interno">#<?= View::e($sku) ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Preços e Condições -->
                            <div class="store-card-pricing">
                                <div class="price-main-row">
                                    <span class="price-value">R$ <?= $basePriceFormatted ?></span>
                                    <?php if ($showGlobalPix): ?>
                                    <span class="price-pix">R$ <?= $pixPriceFormatted ?> <?= View::e(Setting::get('store_pix_discount_label', 'no Pix (-5%)')) ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($showGlobalInstallments): ?>
                                <div class="price-installments">
                                    <span>Até 6x sem juros de R$ <?= number_format(($basePriceCents / 6) / 100, 2, ',', '.') ?> nos canais oficiais</span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Botão Principal de Compra (com Preço Específico da Plataforma) -->
                            <?php if ($primaryLink): 
                                $btnAttrs = getButtonColorAndSizeAttrs($primaryLink['button_color'] ?? null, $primaryLink['button_size'] ?? null);
                            ?>
                                <a href="<?= View::e($primaryUrl) ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="btn-platform-action platform-btn-<?= $platformSlug ?> <?= $btnAttrs['class'] ?>"
                                   style="<?= $btnAttrs['style'] ?>"
                                   data-track-product-id="<?= $prod['id'] ?>"
                                   data-track-product-name="<?= View::e($prod['name']) ?>"
                                   data-track-source="store_<?= $platformSlug ?>"
                                   title="<?= View::e($primaryBtnText) ?>">
                                    <div class="platform-btn-content">
                                        <?= getPlatformIconSvg($primaryPlatform) ?>
                                        <span class="platform-btn-text"><?= View::e($primaryBtnText) ?></span>
                                    </div>
                                    <div class="platform-btn-price-tag">
                                        <span>R$ <?= $primaryPriceFormatted ?></span>
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="arrow-ext-svg"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                    </div>
                                </a>
                            <?php else: 
                                $btnAttrs = getButtonColorAndSizeAttrs(null, null);
                            ?>
                                <a href="<?= View::e(Setting::get('store_whatsapp_url', 'https://wa.me/5511999999999')) ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="btn-platform-action platform-btn-whatsapp <?= $btnAttrs['class'] ?>"
                                   style="<?= $btnAttrs['style'] ?>"
                                   data-track-product-id="<?= $prod['id'] ?>"
                                   data-track-product-name="<?= View::e($prod['name']) ?>"
                                   data-track-source="store_whatsapp"
                                   title="Consultar disponibilidade via WhatsApp VIP">
                                    <div class="platform-btn-content">
                                        <?= getPlatformIconSvg('whatsapp') ?>
                                        <span class="platform-btn-text">Consultar no WhatsApp</span>
                                    </div>
                                    <div class="platform-btn-price-tag">
                                        <span>R$ <?= $basePriceFormatted ?></span>
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="arrow-ext-svg"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                    </div>
                                </a>
                            <?php endif; ?>

                            <!-- Canais de Venda Adicionais (Links Independentes com seus respectivos Preços) -->
                            <?php if ($showGlobalMultiChannel && count($links) > 1): ?>
                                <div class="store-card-channels">
                                    <span class="channels-title"><?= View::e(Setting::get('store_multi_channel_label', 'Disponível também em:')) ?></span>
                                    <div class="channels-grid">
                                        <?php for ($i = 1; $i < count($links); $i++): 
                                            $secLink = $links[$i];
                                            $secPriceCents = !empty($secLink['price_cents']) ? (int)$secLink['price_cents'] : $basePriceCents;
                                            $secPriceFormatted = number_format($secPriceCents / 100, 2, ',', '.');
                                            $secPlatform = !empty($secLink['platform_name']) ? $secLink['platform_name'] : 'Canal Parceiro';
                                            $secPlatformSlug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $secPlatform));
                                            $secBtnText = !empty($secLink['button_text']) ? $secLink['button_text'] : $secPlatform;
                                            $secAttrs = getButtonColorAndSizeAttrs($secLink['button_color'] ?? null, 'sm');
                                        ?>
                                            <a href="<?= View::e($secLink['url']) ?>" 
                                               target="_blank" 
                                               rel="noopener noreferrer" 
                                               class="store-channel-pill platform-pill-<?= $secPlatformSlug ?> <?= $secAttrs['class'] ?>"
                                               style="<?= $secAttrs['style'] ?>"
                                               data-track-product-id="<?= $prod['id'] ?>"
                                               data-track-product-name="<?= View::e($prod['name']) ?>"
                                               data-track-source="store_<?= $secPlatformSlug ?>"
                                               title="<?= View::e($secBtnText) ?>">
                                                <div class="channel-pill-left">
                                                    <span class="channel-pill-icon"><?= getPlatformEmoji($secPlatform) ?></span>
                                                    <span class="channel-pill-name"><?= View::e($secBtnText) ?></span>
                                                </div>
                                                <div class="channel-pill-price">
                                                    <span>R$ <?= $secPriceFormatted ?></span>
                                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                                </div>
                                            </a>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="store-empty-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <?php if (Setting::get('store_empty_title_visible', '1') === '1'): ?>
                <h3><?= View::e(Setting::get('store_empty_title', 'Nenhuma Joia Disponível no Catálogo')) ?></h3>
                <?php endif; ?>
                <?php if (Setting::get('store_empty_desc_visible', '1') === '1'): ?>
                <p><?= View::e(Setting::get('store_empty_desc', 'Nossas coleções estão sendo atualizadas nos bastidores. Experimente outro filtro ou volte em instantes.')) ?></p>
                <?php endif; ?>
                <?php if (Setting::get('store_empty_btn_visible', '1') === '1'): ?>
                <a href="<?= View::e(Setting::get('store_empty_url', '/')) ?>" class="btn-store-back">
                    <?= View::e(Setting::get('store_empty_btn', 'Voltar à Página Inicial')) ?>
                </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- 3. SEÇÃO EDITORIAL: TRANSPARÊNCIA & CANAIS HOMOLOGADOS -->
<?php if (Setting::get('store_reasons_visible', '1') === '1'): ?>
<section class="store-reasons-section">
    <div class="container reasons-container">
        
        <div class="reasons-header">
            <?php if (Setting::get('store_reasons_eyebrow_visible', '1') === '1'): ?>
            <span class="reasons-eyebrow"><?= View::e(Setting::get('store_reasons_eyebrow', 'TRANSPARÊNCIA & CONVENIÊNCIA')) ?></span>
            <?php endif; ?>

            <?php if (Setting::get('store_reasons_title_visible', '1') === '1'): ?>
            <h2 class="reasons-title">
                <?= View::e(Setting::get('store_reasons_title', 'Sua Experiência Segura em')) ?> 
                <span class="gold-gradient-glow"><?= View::e(Setting::get('store_reasons_highlight', 'Nossos Canais Oficiais')) ?></span>
            </h2>
            <?php endif; ?>

            <?php if (Setting::get('store_reasons_desc_visible', '1') === '1'): ?>
            <p class="reasons-desc">
                <?= View::e(Setting::get('store_reasons_desc', 'Para oferecer os melhores cupons de frete, proteção estendida ao consumidor e máxima velocidade de entrega, hospedamos a finalização das nossas vendas nos principais ecossistemas homologados do Brasil.')) ?>
            </p>
            <?php endif; ?>
        </div>

        <div class="reasons-grid">
            <?php if (Setting::get('store_reason_1_visible', '1') === '1'): ?>
            <div class="reason-card">
                <div class="reason-icon"><?= View::e(Setting::get('store_reason_1_icon', '🛡️')) ?></div>
                <h3><?= View::e(Setting::get('store_reason_1_title', 'Garantia de Entrega Estendida')) ?></h3>
                <p><?= View::e(Setting::get('store_reason_1_desc', 'Seu pagamento só é liberado à Divinium após você receber o pacote em casa e conferir a perfeição da sua joia.')) ?></p>
            </div>
            <?php endif; ?>

            <?php if (Setting::get('store_reason_2_visible', '1') === '1'): ?>
            <div class="reason-card">
                <div class="reason-icon"><?= View::e(Setting::get('store_reason_2_icon', '🎫')) ?></div>
                <h3><?= View::e(Setting::get('store_reason_2_title', 'Cupons de Frete & Descontos')) ?></h3>
                <p><?= View::e(Setting::get('store_reason_2_desc', 'Aproveite os cupons de frete grátis, moedas e cashback para obter vantagens exclusivas na sua compra.')) ?></p>
            </div>
            <?php endif; ?>

            <?php if (Setting::get('store_reason_3_visible', '1') === '1'): ?>
            <div class="reason-card">
                <div class="reason-icon"><?= View::e(Setting::get('store_reason_3_icon', '💎')) ?></div>
                <h3><?= View::e(Setting::get('store_reason_3_title', 'Autenticidade & Prata 925')) ?></h3>
                <p><?= View::e(Setting::get('store_reason_3_desc', 'Todas as peças enviadas acompanham o certificado oficial de autenticidade da Divinium e estojo nobre aveludado.')) ?></p>
            </div>
            <?php endif; ?>

            <?php if (Setting::get('store_reason_4_visible', '1') === '1'): ?>
            <div class="reason-card">
                <div class="reason-icon"><?= View::e(Setting::get('store_reason_4_icon', '💬')) ?></div>
                <h3><?= View::e(Setting::get('store_reason_4_title', 'Atendimento & Concierge VIP')) ?></h3>
                <p><?= View::e(Setting::get('store_reason_4_desc', 'Dúvidas sobre o modelo ou tamanho? Nosso atendimento via WhatsApp ou chat responde em poucos minutos.')) ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Concierge WhatsApp -->
        <?php if (Setting::get('store_whatsapp_visible', '1') === '1'): ?>
        <div class="store-whatsapp-cta">
            <div class="whatsapp-cta-content">
                <?php if (Setting::get('store_whatsapp_title_visible', '1') === '1'): ?>
                <h3><?= View::e(Setting::get('store_whatsapp_title', 'Precisa de ajuda para escolher sua joia?')) ?></h3>
                <?php endif; ?>
                <?php if (Setting::get('store_whatsapp_desc_visible', '1') === '1'): ?>
                <p><?= View::e(Setting::get('store_whatsapp_desc', 'Fale diretamente com nossa consultora de gemas minerais antes de concluir sua compra.')) ?></p>
                <?php endif; ?>
            </div>
            <?php if (Setting::get('store_whatsapp_btn_visible', '1') === '1'): ?>
            <a href="<?= View::e(Setting::get('store_whatsapp_url', 'https://wa.me/5511999999999?text=Ol%C3%A1,%20estou%20na%20loja%20da%20Divinium%20e%20gostaria%20de%20tirar%20d%C3%BAvidas%20sobre%20uma%20joia')) ?>" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="btn-whatsapp-concierge">
                <span><?= View::e(Setting::get('store_whatsapp_btn', 'Conversar no WhatsApp')) ?></span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</section>
<?php endif; ?>

<!-- Script de Filtragem Rápida no Catálogo -->
<script>
function filterStore(cat, btn) {
    document.querySelectorAll('.store-filter-pill').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const cards = document.querySelectorAll('.store-product-card');
    cards.forEach(card => {
        if (cat === 'all' || card.getAttribute('data-category') === cat) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
