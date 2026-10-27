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
            <?= View::e(Setting::get('store_hero_subtitle', 'Cada joia Divinium une a nobreza e o brilho do banho de prata à energia de gemas minerais raras e conchas naturais. Escolha sua peça e finalize sua compra com toda a segurança, cupons e rastreamento nos nossos canais oficiais homologados.')) ?>
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
                <span><?= View::e(Setting::get('store_badge_2', 'Certificado de Banho de Prata')) ?></span>
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
                    <?php foreach ($categories as $cat): 
                        $slug = strtolower($cat['slug'] ?? '');
                        if (Setting::get('store_filter_' . $slug . '_visible', '1') !== '1') continue;
                    ?>
                        <button class="store-filter-pill" data-filter="cat-<?= $cat['id'] ?>" onclick="filterStore('cat-<?= $cat['id'] ?>', this)">
                            <span><?= View::e($cat['name']) ?></span>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Busca Instantânea na Boutique -->
            <div class="store-search-container" id="storeSearchContainer">
                <div class="store-search-box">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="storeSearchInput" class="store-search-input" 
                           placeholder="<?= View::e(Setting::get('store_search_placeholder', 'Buscar joia por nome, gema ou categoria...')) ?>"
                           oninput="handleStoreLiveSearch(this.value)"
                           aria-label="Buscar joia na boutique">
                    <button type="button" class="store-search-clear" id="storeSearchClear" onclick="clearStoreSearch()" style="display: none;" aria-label="Limpar busca">&times;</button>
                </div>
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
                        $rawLinks = !empty($prod['links']) && is_array($prod['links']) ? $prod['links'] : [];
                        $validLinks = [];
                        foreach ($rawLinks as $rl) {
                            $u = trim((string)($rl['url'] ?? ''));
                            $isActive = isset($rl['is_active']) ? ((int)$rl['is_active'] === 1) : true;
                            if ($u !== '' && $isActive) {
                                $validLinks[] = $rl;
                            }
                        }

                        if (empty($validLinks) && !empty($prod['external_url'])) {
                            $platAuto = 'Shopee';
                            $uAuto = (string)$prod['external_url'];
                            if (stripos($uAuto, 'mercadolivre') !== false || stripos($uAuto, 'mercado_livre') !== false) {
                                $platAuto = 'Mercado Livre';
                            } elseif (stripos($uAuto, 'amazon') !== false) {
                                $platAuto = 'Amazon';
                            } elseif (stripos($uAuto, 'tiktok') !== false) {
                                $platAuto = 'TikTok Shop';
                            } elseif (stripos($uAuto, 'wa.me') !== false || stripos($uAuto, 'whatsapp') !== false) {
                                $platAuto = 'WhatsApp';
                            }
                            $validLinks = [
                                [
                                    'id' => 0,
                                    'platform_name' => $platAuto,
                                    'url' => $prod['external_url'],
                                    'price_cents' => $basePriceCents,
                                    'button_text' => 'Comprar na ' . $platAuto,
                                    'button_color' => 'default',
                                    'button_size' => 'md',
                                    'target' => '_blank',
                                    'is_active' => 1
                                ]
                            ];
                        }
                        
                        // Resolução de URL Própria e Comportamento de Clique do Card
                        $prodSlug = !empty($prod['slug']) ? $prod['slug'] : ('joia-' . $prod['id']);
                        $detailUrl = '/produto/' . $prodSlug;
                        
                        $prodBehavior = $prod['card_click_behavior'] ?? 'default';
                        $globalBehavior = Setting::get('store_card_click_behavior', 'detail'); // 'detail' é o padrão
                        $cardBehavior = ($prodBehavior !== 'default' && !empty($prodBehavior)) ? $prodBehavior : $globalBehavior;

                        if ($cardBehavior === 'custom' && !empty($prod['custom_target_url'])) {
                            $cardUrl = $prod['custom_target_url'];
                            $cardTarget = '_blank';
                        } elseif ($cardBehavior === 'external' && !empty($validLinks)) {
                            $cardUrl = $validLinks[0]['url'];
                            $cardTarget = !empty($validLinks[0]['target']) ? $validLinks[0]['target'] : '_blank';
                        } else {
                            // Padrão DIVINIUM: Abre a página de detalhes da própria loja
                            $cardUrl = $detailUrl;
                            $cardTarget = '_self';
                        }
                        
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
                        $detailUrl = '/produto/' . (!empty($prod['slug']) ? $prod['slug'] : ('joia-' . $prod['id']));
                    ?>
                    
                    <article class="store-product-card" data-category="<?= $filterClass ?>" data-track-product-id="<?= $prod['id'] ?>" data-track-product-name="<?= View::e($prod['name']) ?>">
                        <!-- Imagem do Modelo com Link para a Página de Detalhes -->
                        <a href="<?= View::e($detailUrl) ?>" 
                           class="store-card-media"
                           data-track-product-id="<?= $prod['id'] ?>"
                           data-track-product-name="<?= View::e($prod['name']) ?>"
                           data-track-source="store_media_detail"
                           title="Conhecer <?= View::e($prod['name']) ?>">
                            
                            <img src="<?= View::e($prod['image_url'] ?: '/assets/images/hero/featured-green-shell.jpg') ?>" 
                                 alt="<?= View::e($prod['name']) ?>"
                                 loading="lazy"
                                 class="store-card-img"
                                 onerror="this.onerror=null; this.src='/assets/images/hero/featured-green-shell.jpg';">
                            
                            <?php 
                            $showVarBadge = Setting::get('store_card_variations_badge_visible', '1') === '1';
                            $varSuffix = Setting::get('store_card_variations_badge_suffix', 'Pedras Minerais');
                            $showCardMetalBadge = Setting::get('store_card_badge_metal_visible', '1') === '1';
                            $cardMetalText = Setting::get('store_card_badge_metal', 'Banho de Prata');
                            $cardBtnText = Setting::get('store_card_btn_text', 'Conhecer a Peça');
                            ?>
                            <?php if ($showProdBadge && ($showVarBadge || $showCardMetalBadge)): ?>
                            <div class="card-badges-top">
                                <?php if (!empty($prod['variations_count']) && (int)$prod['variations_count'] > 1 && $showVarBadge): ?>
                                    <span class="badge-metal">✦ <?= (int)$prod['variations_count'] ?> <?= View::e($varSuffix) ?></span>
                                <?php elseif ($showCardMetalBadge): ?>
                                    <span class="badge-metal"><?= View::e($cardMetalText) ?></span>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <div class="card-hover-overlay">
                                <span class="overlay-cta-text">
                                    <span><?= View::e($cardBtnText) ?></span>
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                                </span>
                            </div>
                        </a>

                        <!-- Informações do Card: Somente Imagem, Nome do Modelo, Preço e Botão Discreto -->
                        <div class="store-card-body-clean">
                            <?php if (Setting::get('store_product_title_visible', '1') === '1'): ?>
                            <h3 class="store-card-title-clean">
                                <a href="<?= View::e($detailUrl) ?>" 
                                   data-track-product-id="<?= $prod['id'] ?>"
                                   data-track-product-name="<?= View::e($prod['name']) ?>"
                                   data-track-source="store_title_detail"
                                   title="<?= View::e($prod['name']) ?>">
                                    <?= View::e($prod['name']) ?>
                                </a>
                            </h3>
                            <?php endif; ?>

                            <!-- Preço Correspondente -->
                            <?php if (Setting::get('store_price_visible', '1') === '1'): ?>
                            <div class="store-card-price-clean">
                                <?php if (!empty($prod['variations_count']) && (int)$prod['variations_count'] > 1): ?>
                                    <span class="price-prefix">A partir de</span>
                                <?php endif; ?>
                                <span>R$ <?= $basePriceFormatted ?></span>
                            </div>
                            <?php endif; ?>

                            <!-- Opções de Canais de Compra Ativos do Modelo -->
                            <?php if (!empty($validLinks)): ?>
                            <div class="store-card-channels">
                                <?php foreach ($validLinks as $lnk): 
                                    $platName = trim((string)($lnk['platform_name'] ?? 'Comprar'));
                                    $platLower = strtolower($platName);
                                    $platSlug = 'outro';
                                    if (str_contains($platLower, 'shopee')) $platSlug = 'shopee';
                                    elseif (str_contains($platLower, 'mercado') || str_contains($platLower, 'ml')) $platSlug = 'mercadolivre';
                                    elseif (str_contains($platLower, 'tiktok')) $platSlug = 'tiktok';
                                    elseif (str_contains($platLower, 'whatsapp') || str_contains($platLower, 'wa')) $platSlug = 'whatsapp';
                                    elseif (str_contains($platLower, 'diret') || str_contains($platLower, 'site')) $platSlug = 'compradireta';
                                    elseif (str_contains($platLower, 'amazon')) $platSlug = 'amazon';

                                    $uLnk = trim((string)($lnk['url'] ?? ''));
                                    $isDirect = str_starts_with($uLnk, '/');
                                    $targetLnk = !empty($lnk['target']) ? $lnk['target'] : ($isDirect ? '_self' : '_blank');
                                    $btnText = !empty($lnk['button_text']) ? $lnk['button_text'] : ('Comprar no ' . $platName);
                                ?>
                                    <a href="<?= View::e($uLnk) ?>" 
                                       target="<?= $targetLnk ?>" 
                                       <?= $targetLnk === '_blank' ? 'rel="noopener noreferrer"' : '' ?>
                                       onclick="event.stopPropagation();"
                                       class="btn-channel-chip platform-chip-<?= $platSlug ?>" 
                                       title="<?= View::e($btnText) ?>">
                                        <span><?= View::e($platName) ?></span>
                                        <span>↗</span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <!-- Botão Discreto para Conhecer a Peça -->
                            <a href="<?= View::e($detailUrl) ?>" 
                               class="btn-card-discreet"
                               data-track-product-id="<?= $prod['id'] ?>"
                               data-track-product-name="<?= View::e($prod['name']) ?>"
                               data-track-source="store_btn_explore"
                               title="Conhecer <?= View::e($prod['name']) ?> na DIVINIUM">
                                <span><?= View::e($cardBtnText) ?></span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <div class="store-empty-state" id="storeEmptyState" style="display: none; margin-top: 40px; text-align: center; padding: 40px 20px;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--gold-accent)" stroke-width="1" style="margin-bottom: 12px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <h3 style="color: var(--text-primary); font-family: var(--font-serif); font-size: 1.4rem; margin-bottom: 8px;"><?= View::e(Setting::get('store_empty_title', 'Em Breve Novos Lançamentos Desta Coleção')) ?></h3>
                <p style="color: var(--text-secondary); font-size: 0.9rem; max-width: 520px; margin: 0 auto 20px;"><?= View::e(Setting::get('store_empty_desc', 'Novas joias e gemas exclusivas estão sendo lapidadas no atelier. Selecione outra categoria ou explore todas as peças disponíveis.')) ?></p>
                <button type="button" class="btn-store-back" style="cursor: pointer;" onclick="filterStore('all', document.querySelector('.store-filter-pill'))">
                    Ver Todas as Joias Disponíveis
                </button>
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
                <h3><?= View::e(Setting::get('store_reason_3_title', 'Qualidade & Banho de Prata')) ?></h3>
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

<!-- Script de Filtragem Rápida no Catálogo com Suporte a Hash e Estado Vazio -->
<script>
function filterStore(cat, btn) {
    if (btn) {
        document.querySelectorAll('.store-filter-pill').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    const cards = document.querySelectorAll('.store-product-card');
    let visibleCount = 0;
    cards.forEach(card => {
        if (cat === 'all' || card.getAttribute('data-category') === cat) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    const emptyBox = document.getElementById('storeEmptyState');
    if (emptyBox) {
        emptyBox.style.display = visibleCount === 0 ? 'block' : 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const hash = window.location.hash.toLowerCase().replace('#', '');
    if (hash) {
        let matchedPill = null;
        document.querySelectorAll('.store-filter-pill').forEach(pill => {
            const txt = pill.textContent.toLowerCase();
            const filterAttr = pill.getAttribute('data-filter') || '';
            if (txt.includes(hash) || filterAttr.includes(hash)) {
                matchedPill = pill;
            }
        });

        if (matchedPill) {
            matchedPill.click();
            const catSec = document.getElementById('catalogo');
            if (catSec) {
                catSec.scrollIntoView({ behavior: 'smooth' });
            }
        }
    }
});
</script>
