<?php
/**
 * VIEW: DETALHES DA JOIA DIVINIUM (/produto/{slug} ou /loja/{slug})
 * QUIET LUXURY HIGH-JEWELRY PRODUCT DETAIL PAGE
 * 
 * - Navegação interna sem redirecionamento imediato para marketplaces
 * - Galeria de alta resolução e especificações técnicas completas
 * - Botões de compra padronizados e imparciais para todos os canais de venda
 * - Preços específicos por plataforma de venda
 * - Totalmente personalizável via Painel Administrativo (/admin/boutique e /admin/produtos)
 */

use App\Models\Setting;
use App\Core\View;

if (!function_exists('getPlatformIconSvgShow')) {
    function getPlatformIconSvgShow(string $platform): string {
        $plat = strtolower(trim($platform));
        if (str_contains($plat, 'shopee')) {
            return '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" class="channel-svg"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h2v2c0 .55.45 1 1 1s1-.45 1-1V8h6v2c0 .55.45 1 1 1s1-.45 1-1V8h2v12z"/></svg>';
        }
        if (str_contains($plat, 'mercado')) {
            return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="channel-svg"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>';
        }
        if (str_contains($plat, 'amazon')) {
            return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="channel-svg"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>';
        }
        if (str_contains($plat, 'tiktok')) {
            return '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" class="channel-svg"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298-.002.595.042.88.13V9.4a6.33 6.33 0 0 0-1-.08A6.34 6.34 0 0 0 3 15.66a6.34 6.34 0 0 0 10.82 4.49 6.27 6.27 0 0 0 1.87-4.49V8.62a8.28 8.28 0 0 0 3.9 1.07V6.69z"/></svg>';
        }
        if (str_contains($plat, 'whatsapp') || str_contains($plat, 'wa.me')) {
            return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="channel-svg"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>';
        }
        if (str_contains($plat, 'pix') || str_contains($plat, 'asaas')) {
            return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="channel-svg"><path d="M12 2L3 12l9 10 9-10L12 2zm0 3.8L18.2 12 12 18.2 5.8 12 12 5.8z"/></svg>';
        }
        if (str_contains($plat, 'instagram')) {
            return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="channel-svg"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>';
        }
        if (str_contains($plat, 'enjoei') || str_contains($plat, 'elo7')) {
            return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="channel-svg"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>';
        }
        if (str_contains($plat, 'oficial') || str_contains($plat, 'divinium') || str_contains($plat, 'diret') || str_contains($plat, 'site') || str_contains($plat, 'checkout')) {
            return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="channel-svg"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
        }
        return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="channel-svg"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>';
    }
}

if (!function_exists('getStoneColorDot')) {
    function getStoneColorDot(string $stone): string {
        $st = mb_strtolower(trim($stone));
        if (str_contains($st, 'amazonita')) return '#30c7b5';
        if (str_contains($st, 'ametista')) return '#9b59b6';
        if (str_contains($st, 'coral')) return '#e74c3c';
        if (str_contains($st, 'cornalina')) return '#d35400';
        if (str_contains($st, 'estrela')) return '#1a237e';
        if (str_contains($st, 'howlita')) return '#00bcd4';
        if (str_contains($st, 'madrepérola') || str_contains($st, 'madreperola')) {
            if (str_contains($st, 'preta')) return '#212121';
            if (str_contains($st, 'verde')) return '#27ae60';
            if (str_contains($st, 'roxa')) return '#8e44ad';
            if (str_contains($st, 'vermelha')) return '#c0392b';
            if (str_contains($st, 'celeste')) return '#29b6f6';
            return '#f5f5f0'; // Perolada nobre
        }
        if (str_contains($st, 'olho de tigre')) return '#b7791f';
        if (str_contains($st, 'ônix') || str_contains($st, 'onix')) return '#181818';
        if (str_contains($st, 'sol')) return '#f39c12';
        if (str_contains($st, 'verde') || str_contains($st, 'quartzo verde')) return '#2e7d32';
        if (str_contains($st, 'sodalita')) return '#283593';
        if (str_contains($st, 'azul')) return '#1e88e5';
        if (str_contains($st, 'rosa')) return '#f48fb1';
        if (str_contains($st, 'vermelho') || str_contains($st, 'jaspe')) return '#b71c1c';
        return '#CBD0D6';
    }
}

// Extrair propriedades do produto com fallbacks elegantes
$prodId = (int) ($product['id'] ?? 0);
$prodName = (string) ($product['name'] ?? 'Joia Autoral DIVINIUM');
$prodSlug = (string) ($product['slug'] ?? 'joia');
$prodCategory = !empty($product['category_name']) ? $product['category_name'] : 'Coleção Autoral';
$prodMineral = trim((string) ($product['stone_mineral'] ?? ''));
$rawMaterials = trim((string) ($product['materials'] ?? 'Banho de Prata'));
$prodMaterials = str_ireplace(['Prata de Lei 925', 'prata de lei 925', 'Prata de Lei', 'prata de lei'], 'Banho de Prata', $rawMaterials);
$prodDimensions = trim((string) ($product['dimensions'] ?? ''));
$prodColors = trim((string) ($product['colors'] ?? ''));
$prodSku = trim((string) ($product['sku'] ?? 'DIV-' . str_pad((string)$prodId, 4, '0', STR_PAD_LEFT)));
$prodStockStatus = (string) ($product['stock_status'] ?? 'in_stock');
$prodImageUrl = !empty($product['image_url']) ? $product['image_url'] : '/assets/images/hero/featured-green-shell.jpg';

// Preço e Condições
$basePriceCents = (int) ($product['price_cents'] ?? 38000);
$basePriceFormatted = number_format($basePriceCents / 100, 2, ',', '.');
$promotionalPriceCents = !empty($product['promotional_price_cents']) ? (int) $product['promotional_price_cents'] : null;
$promotionalPriceFormatted = $promotionalPriceCents ? number_format($promotionalPriceCents / 100, 2, ',', '.') : null;
$pixPriceFormatted = number_format(($basePriceCents * 0.95) / 100, 2, ',', '.');
$installmentVal = number_format(($basePriceCents / 6) / 100, 2, ',', '.');

// Identificar variação inicial se houver variações para este modelo
$initialVariation = null;
if (!empty($variations) && is_array($variations)) {
    $targetSku = $selectedSku ?? ($product['sku'] ?? '');
    foreach ($variations as $var) {
        if (!empty($targetSku) && ($var['sku'] ?? '') === $targetSku) {
            $initialVariation = $var;
            break;
        }
    }
    if (!$initialVariation && !empty($variations[0])) {
        $initialVariation = $variations[0];
    }
}

if ($initialVariation) {
    if (!empty($initialVariation['sku'])) $prodSku = $initialVariation['sku'];
    if (!empty($initialVariation['stone_mineral'])) $prodMineral = $initialVariation['stone_mineral'];
    if (!empty($initialVariation['image_url'])) $prodImageUrl = $initialVariation['image_url'];
    if (!empty($initialVariation['price_cents'])) {
        $basePriceCents = (int)$initialVariation['price_cents'];
        $basePriceFormatted = number_format($basePriceCents / 100, 2, ',', '.');
        $pixPriceFormatted = number_format(($basePriceCents * 0.95) / 100, 2, ',', '.');
        $installmentVal = number_format(($basePriceCents / 6) / 100, 2, ',', '.');
    }
    if (isset($initialVariation['has_stock'])) {
        $prodStockStatus = $initialVariation['has_stock'] ? 'in_stock' : 'out_of_stock';
    }
}

// Controles de visibilidade (preferência do produto prevalece sobre configuração geral)
$showProdBadge = isset($product['show_badge']) ? ((int) $product['show_badge'] === 1) : true;
$showProdSpecs = isset($product['show_specs']) ? ((int) $product['show_specs'] === 1) : true;
$showProdDesc = isset($product['show_description']) ? ((int) $product['show_description'] === 1) : true;
$showProdChannels = isset($product['show_channels']) ? ((int) $product['show_channels'] === 1) : true;

// SKU: Visibilidade Global + Override por Produto (sem afetar estoque, Bling ou banco de dados)
$showGlobalSku = Setting::get('store_sku_visible', '1') === '1';
$showProductSku = !isset($product['show_sku']) || ((int)$product['show_sku'] === 1);
$canShowSku = $showGlobalSku && $showProductSku;

// Selos e Badges Globais editáveis e ocultáveis
$showMetalBadge = Setting::get('store_badge_metal_visible', Setting::get('store_card_badge_metal_visible', '1')) === '1';
$metalBadgeText = Setting::get('store_badge_metal_text', Setting::get('store_card_badge_metal', 'Banho de Prata'));
$showMineralBadge = Setting::get('store_badge_mineral_visible', Setting::get('store_card_mineral_visible', '1')) === '1';
$showStatusBadge = Setting::get('store_badge_status_visible', '1') === '1';
$preorderBadgeText = Setting::get('store_badge_preorder_text', 'Sob Encomenda');
$outOfStockBadgeText = Setting::get('store_badge_outofstock_text', 'Indisponível');

// Configurações globais do painel (/admin/boutique e /admin/produtos-exibicao)
$globalShowSpecs = Setting::get('store_detail_show_specs', '1') === '1';
$globalShowGuarantee = Setting::get('store_detail_show_guarantee', '1') === '1';
$globalShowRelated = Setting::get('store_detail_show_related', '1') === '1';
$globalShowPix = Setting::get('store_detail_show_pix_discount', Setting::get('store_pix_discount_visible', '1')) === '1';
$globalShowInstallments = Setting::get('store_detail_show_installments', Setting::get('store_installments_visible', '1')) === '1';
$globalShowShare = Setting::get('store_detail_show_share', '1') === '1';
$buttonLayout = Setting::get('store_detail_button_layout', 'list'); // 'list' ou 'grid'

// Imparcialidade de botões: Todos os canais recebem o mesmo destaque e tipografia padronizados
$impartialButtons = Setting::get('store_detail_impartial_buttons', '1') === '1';

// Filtrar apenas canais com URL válida
$validLinks = [];
if (!empty($links) && is_array($links)) {
    foreach ($links as $lnk) {
        $u = trim((string)($lnk['url'] ?? ''));
        $isActive = isset($lnk['is_active']) ? ((int)$lnk['is_active'] === 1) : true;
        if ($u !== '' && $isActive) {
            $validLinks[] = $lnk;
        }
    }
}

// URL completa para compartilhamento
$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/produto/" . rawurlencode($prodSlug);
?>

<div class="product-detail-page">

    <!-- SEÇÃO PRINCIPAL DO PRODUTO (LAYOUT 2 COLUNAS) -->
    <section class="product-main-section">
        <div class="container">
            <div class="product-detail-layout">
                
                <!-- COLUNA ESQUERDA: GALERIA DE FOTOS & MÍDIA -->
                <div class="product-media-column">
                    <div class="product-gallery-card">
                        
                        <!-- Badges Flutuantes na Foto -->
                        <?php if ($showProdBadge): ?>
                        <div class="product-media-badges">
                            <?php if ($showStatusBadge && $prodStockStatus === 'preorder'): ?>
                                <span class="media-badge badge-preorder"><?= View::e($preorderBadgeText) ?></span>
                            <?php elseif ($showStatusBadge && $prodStockStatus === 'out_of_stock'): ?>
                                <span class="media-badge badge-out"><?= View::e($outOfStockBadgeText) ?></span>
                            <?php elseif ($showMetalBadge): ?>
                                <span class="media-badge badge-metal"><?= View::e($metalBadgeText) ?></span>
                            <?php endif; ?>

                            <?php if ($showMineralBadge && $prodMineral): ?>
                                <span class="media-badge badge-mineral">✦ <?= View::e($prodMineral) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Imagem Principal com Zoom Suave (Sem cortes na joia) -->
                        <div class="product-main-image-wrap" id="mainImageWrapper">
                            <img src="<?= View::e($prodImageUrl) ?>" 
                                 alt="<?= View::e($prodName) ?>" 
                                 id="productMainImage"
                                 class="product-main-image"
                                 loading="eager"
                                 onerror="this.onerror=null; this.src='/assets/images/hero/featured-green-shell.jpg';">
                        </div>

                        <!-- Barra de Ações & Compartilhamento -->
                        <?php if ($globalShowShare || $canShowSku): ?>
                        <div class="product-media-footer">
                            <?php if ($canShowSku): ?>
                            <span class="media-sku-text"><?= View::e(Setting::get('store_sku_label', 'Código da Joia:')) ?> <strong>#<?= View::e($prodSku) ?></strong></span>
                            <?php else: ?>
                            <span></span>
                            <?php endif; ?>
                            
                            <?php if ($globalShowShare): ?>
                            <div class="media-share-actions">
                                <button type="button" class="btn-share-icon" onclick="copyProductLink('<?= View::e($currentUrl) ?>')" title="Copiar link próprio desta joia" id="btnCopyLink">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                    <span id="copyLinkText"><?= View::e(Setting::get('store_detail_share_btn_text', 'Compartilhar Joia')) ?></span>
                                </button>
                                
                                <a href="https://api.whatsapp.com/send?text=<?= urlencode('Conheça esta joia da DIVINIUM: ' . $prodName . ' — ' . $currentUrl) ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="btn-share-icon whatsapp" 
                                   title="Compartilhar no WhatsApp">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M19.05 4.91A9.816 9.816 0 0 0 12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01zm-7.01 15.24c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.196 8.196 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.83c.02 4.54-3.68 8.23-8.22 8.23z"/></svg>
                                    <span>WhatsApp</span>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- COLUNA DIREITA: INFORMAÇÕES, PREÇOS & CANAIS DE COMPRA -->
                <div class="product-info-column">
                    
                    <div class="product-info-header">
                        <div class="product-collection-tag">
                            <span class="gold-sparkle">✦</span>
                            <span><?= View::e($prodCategory) ?></span>
                        </div>
                        <h1 class="product-title-heading"><?= View::e($prodName) ?></h1>
                    </div>

                    <!-- Bloco de Preço Oficial e Condições -->
                    <div class="product-price-block">
                        <div class="price-figures-wrap">
                            <?php if ($promotionalPriceFormatted): ?>
                                <span class="price-compare-at">De R$ <?= $promotionalPriceFormatted ?></span>
                            <?php endif; ?>
                            <span class="price-current-large">R$ <?= $basePriceFormatted ?></span>
                            
                            <?php if ($globalShowPix): ?>
                                <span class="price-pix-badge">
                                    <span class="pix-icon">⚡</span>
                                    <span>R$ <?= $pixPriceFormatted ?> <?= View::e(Setting::get('store_pix_discount_label', 'no Pix (-5%)')) ?></span>
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if ($globalShowInstallments): ?>
                        <div class="price-installments-row">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                            <span><?= View::e(Setting::get('store_detail_installments_text', 'Em até 6x de R$ ' . $installmentVal . ' sem juros nos canais oficiais homologados')) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- ============================================================== -->
                    <!-- SELETOR DE PEDRAS MINERAIS / VARIAÇÕES DA JOIA (AUTORAL DIVINIUM) -->
                    <!-- ============================================================== -->
                    <?php if (!empty($variations) && count($variations) > 1): ?>
                    <div class="product-variations-section" id="seletor-variacoes">
                        <div class="variations-header">
                            <div class="variations-title">
                                <span>Pedra Mineral:</span>
                                <strong class="selected-stone-name" id="selectedStoneName"><?= View::e($prodMineral ?: 'Selecione a pedra') ?></strong>
                            </div>
                            <span class="variations-count-badge">✦ <?= count($variations) ?> Opções de Gemas</span>
                        </div>

                        <div class="variations-chips-grid" id="variationsGrid" role="radiogroup" aria-label="Selecione a pedra mineral">
                            <?php foreach ($variations as $var): 
                                $vSku = (string)($var['sku'] ?? '');
                                $vName = (string)($var['name'] ?? '');
                                $vStone = (string)($var['stone_mineral'] ?? $vName);
                                $vPriceCents = (int)($var['price_cents'] ?? $basePriceCents);
                                $vPriceFormatted = number_format($vPriceCents / 100, 2, ',', '.');
                                $vImg = !empty($var['image_url']) ? $var['image_url'] : $prodImageUrl;
                                $vStock = !empty($var['has_stock']) || (!isset($var['has_stock']) && ($var['stock_status'] ?? '') !== 'out_of_stock');
                                $vUrl = !empty($var['external_url']) ? $var['external_url'] : ('https://shopee.com.br/search?keyword=' . urlencode('Divinium ' . $vName));
                                $isActive = ($vSku === $prodSku);
                                $dotColor = getStoneColorDot($vStone);
                            ?>
                                <button type="button" 
                                        class="variation-chip <?= $isActive ? 'active' : '' ?> <?= !$vStock ? 'variation-out-stock' : '' ?>"
                                        role="radio"
                                        aria-checked="<?= $isActive ? 'true' : 'false' ?>"
                                        data-sku="<?= View::e($vSku) ?>"
                                        data-name="<?= View::e($vName) ?>"
                                        data-stone="<?= View::e($vStone) ?>"
                                        data-price-formatted="R$ <?= $vPriceFormatted ?>"
                                        data-price-cents="<?= $vPriceCents ?>"
                                        data-image="<?= View::e($vImg) ?>"
                                        data-stock="<?= $vStock ? 'in_stock' : 'out_of_stock' ?>"
                                        data-url="<?= View::e($vUrl) ?>"
                                        onclick="selectProductVariation(this)"
                                        title="<?= View::e($vStone) ?> - R$ <?= $vPriceFormatted ?><?= !$vStock ? ' (Sob Encomenda)' : '' ?>">
                                    <span class="stone-dot" style="background-color: <?= $dotColor ?>;"></span>
                                    <span class="stone-label"><?= View::e($vStone) ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- ============================================================== -->
                    <!-- PAINEL CENTRALIZADO DE COMPRA (COMPARTILHADO PELO GRUPO DO PRODUTO) -->
                    <!-- ============================================================== -->
                    <?php 
                    $showChannelsBlock = $showProdChannels && (Setting::get('store_detail_show_channels', '1') === '1');
                    if ($showChannelsBlock): 
                        $channelsTitleVisible = Setting::get('store_detail_channels_title_visible', '1') === '1';
                        $channelsTitle = Setting::get('store_detail_channels_title', 'Escolha onde deseja adquirir sua joia:');
                        $channelsSubtitleVisible = Setting::get('store_detail_channels_subtitle_visible', '1') === '1';
                        $channelsSubtitle = Setting::get('store_detail_channels_subtitle', 'Selecione seu canal oficial de preferência. Todos contam com garantia de autenticidade, embalagem exclusiva e compra 100% protegida.');

                        // Configurações do Canal Direto DIVINIUM
                        $showDirectChannel = Setting::get('store_direct_channel_visible', '1') === '1';
                        $directTitle = Setting::get('store_direct_channel_title', 'Comprar Direto na DIVINIUM');
                        $directSubtitle = Setting::get('store_direct_channel_subtitle', '5% OFF no Pix • Envio com Seguro Blindado');
                        $directPixDiscountPct = (float) Setting::get('store_direct_pix_discount_pct', '5');
                        $directPixLabel = Setting::get('store_direct_pix_label', 'Pix (-5%)');
                        $directPriceOverride = trim((string) Setting::get('store_direct_price_override', ''));
                        $directShippingInfo = Setting::get('store_direct_shipping_info', 'Envio com Seguro Blindado');
                        $directUrl = Setting::get('store_direct_url', '/checkout');
                        $directBadge = Setting::get('store_direct_badge', 'Exclusivo');
                        $directIcon = Setting::get('store_direct_icon', 'diamond');
                        $directPixCents = $basePriceCents * (1 - ($directPixDiscountPct / 100));
                        $directPixFormatted = $directPriceOverride !== '' ? $directPriceOverride : number_format($directPixCents / 100, 2, ',', '.');

                        // Configurações de Canais Externos
                        $centralChannels = Setting::getProductChannelsList();
                        
                        // Montar lista de canais de compra ativos do Produto Principal (Modelo Pai)
                        $displayChannels = [];
                        $hasDirectInLinks = false;
                        $directChannelConfigured = null;

                        if (!empty($validLinks)) {
                            foreach ($validLinks as $vl) {
                                $u = trim((string)($vl['url'] ?? ''));
                                if ($u === '') continue;

                                $plat = trim((string)($vl['platform_name'] ?? 'Loja Oficial'));
                                $platLower = strtolower($plat);
                                $linkPriceCents = !empty($vl['price_cents']) ? (int)$vl['price_cents'] : $basePriceCents;
                                $linkPriceFormatted = number_format($linkPriceCents / 100, 2, ',', '.');
                                $hasOverride = !empty($vl['price_cents']);

                                $isDirect = str_contains($platLower, 'compra direta') || str_contains($platLower, 'site') || (str_starts_with($u, '/') && !str_contains($platLower, 'shopee') && !str_contains($platLower, 'mercado') && !str_contains($platLower, 'tiktok'));

                                $targetAttr = !empty($vl['target']) ? $vl['target'] : ($isDirect && str_starts_with($u, '/') ? '_self' : '_blank');

                                $officialBadge = !empty($vl['badge']) ? trim((string)$vl['badge']) : '';
                                if ($officialBadge === '') {
                                    if (str_contains($platLower, 'shopee')) $officialBadge = 'Shopee Oficial';
                                    elseif (str_contains($platLower, 'mercado') || str_contains($platLower, 'ml')) $officialBadge = 'Mercado Livre';
                                    elseif (str_contains($platLower, 'amazon')) $officialBadge = 'Amazon Prime';
                                    elseif (str_contains($platLower, 'tiktok')) $officialBadge = 'TikTok Shop';
                                    elseif (str_contains($platLower, 'diret')) $officialBadge = 'Exclusivo';
                                    elseif (str_contains($platLower, 'whatsapp') || str_contains($platLower, 'wa')) $officialBadge = 'Atendimento VIP';
                                    elseif (str_contains($platLower, 'pix') || str_contains($platLower, 'asaas')) $officialBadge = 'Pix com Desconto';
                                    elseif (str_contains($platLower, 'enjoei')) $officialBadge = 'Enjoei';
                                    elseif (str_contains($platLower, 'elo7')) $officialBadge = 'Elo7';
                                    else $officialBadge = 'Oficial';
                                }

                                $btnTitle = !empty($vl['button_text']) ? $vl['button_text'] : ('Comprar no ' . $plat);

                                if ($isDirect) {
                                    $hasDirectInLinks = true;
                                    $directChannelConfigured = [
                                        'title' => $btnTitle,
                                        'url' => $u,
                                        'price_formatted' => $linkPriceFormatted,
                                        'badge' => $officialBadge,
                                        'target' => $targetAttr
                                    ];
                                } else {
                                    $displayChannels[] = [
                                        'id' => $vl['id'] ?? 0,
                                        'platform' => $plat,
                                        'title' => $btnTitle,
                                        'official_label' => 'Canal Homologado DIVINIUM',
                                        'desc' => '',
                                        'price_formatted' => $linkPriceFormatted,
                                        'has_override' => $hasOverride,
                                        'badge' => $officialBadge,
                                        'icon' => $platLower,
                                        'url' => $u,
                                        'target' => $targetAttr
                                    ];
                                }
                            }

                            if ($directChannelConfigured) {
                                $showDirectChannel = true;
                                $directUrl = $directChannelConfigured['url'];
                                $directTitle = $directChannelConfigured['title'];
                                $directPixFormatted = $directChannelConfigured['price_formatted'];
                                if (!empty($directChannelConfigured['badge'])) {
                                    $directBadge = $directChannelConfigured['badge'];
                                }
                            }
                        } elseif (!empty($centralChannels)) {
                            // Fallback caso o modelo ainda não tenha canais individuais configurados
                            foreach ($centralChannels as $cCh) {
                                if (empty($cCh['visible'])) continue;
                                $itemUrl = trim((string)($cCh['url'] ?? ''));
                                if ($itemUrl === '') continue;
                                $platKey = strtolower(trim((string)($cCh['platform'] ?? '')));
                                $itemPrice = trim((string)($cCh['price_override'] ?? ''));
                                $hasOverride = !empty($itemPrice);
                                if (empty($itemPrice)) $itemPrice = $basePriceFormatted;

                                $displayChannels[] = [
                                    'id' => $cCh['id'] ?? $platKey,
                                    'platform' => $cCh['platform'] ?? 'Loja Oficial',
                                    'title' => !empty($cCh['title']) ? $cCh['title'] : ('Comprar na ' . ($cCh['platform'] ?? 'Loja Oficial')),
                                    'official_label' => !empty($cCh['official_label']) ? $cCh['official_label'] : 'Canal Homologado DIVINIUM',
                                    'desc' => $cCh['desc'] ?? '',
                                    'price_formatted' => $itemPrice,
                                    'has_override' => $hasOverride,
                                    'badge' => $cCh['badge'] ?? 'Oficial',
                                    'icon' => $cCh['icon'] ?? $platKey,
                                    'url' => $itemUrl,
                                    'target' => $cCh['target'] ?? '_blank'
                                ];
                            }
                        }

                        // Informações de Confiança & Segurança
                        $showTrustFootnote = Setting::get('store_detail_trust_visible', '1') === '1';
                        $trustText = Setting::get('store_detail_trust_text', 'Todos os canais listados possuem certificação de autenticidade, nota fiscal e garantia de fábrica DIVINIUM.');
                    ?>
                    <div class="product-purchase-channels-box" id="canais-de-compra">
                        
                        <?php if ($channelsTitleVisible || $channelsSubtitleVisible): ?>
                        <div class="channels-box-header">
                            <div class="channels-header-text">
                                <?php if ($channelsTitleVisible && !empty($channelsTitle)): ?>
                                <h3 class="channels-box-title">
                                    <?= View::e($channelsTitle) ?>
                                </h3>
                                <?php endif; ?>
                                
                                <?php if ($channelsSubtitleVisible && !empty($channelsSubtitle)): ?>
                                <p class="channels-box-subtitle">
                                    <?= View::e($channelsSubtitle) ?>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- 1. Opção Direta de Compra na Boutique DIVINIUM -->
                        <?php if ($showDirectChannel): ?>
                        <div class="channel-option-wrapper" style="margin-bottom: 14px;">
                            <a href="<?= View::e($directUrl) ?>" 
                               id="btnDirectCheckoutVip"
                               onclick="buySelectedVariation(event, true, this.getAttribute('href'));" 
                               class="btn-channel-impartial platform-direct-vip"
                               style="background: var(--card-bg); border: 1.5px solid var(--gold-accent); box-shadow: var(--shadow-luxury, 0 4px 15px rgba(0,0,0,0.12));">
                                
                                <div class="channel-btn-left">
                                    <span class="channel-icon-pill" style="background: rgba(167,173,180,0.15); color: var(--gold-accent); border: 1px solid var(--border-light);">
                                        <?= ($directIcon === 'diamond' || $directIcon === 'diamante' || $directIcon === '') ? '💎' : getPlatformIconSvgShow($directIcon) ?>
                                    </span>
                                    <div class="channel-meta-text">
                                        <span class="channel-action-name" style="color: var(--text-primary); font-weight: 700;">
                                            <?= View::e($directTitle) ?>
                                            <?php if (!empty($directBadge)): ?>
                                                <span class="channel-mini-badge" style="background: rgba(212,186,136,0.25); color: #ded7cf; font-size: 0.65rem; padding: 1px 6px; border-radius: 4px; margin-left: 6px; font-weight: 700; border: 1px solid rgba(212,186,136,0.4); vertical-align: middle;">
                                                    <?= View::e($directBadge) ?>
                                                </span>
                                            <?php endif; ?>
                                        </span>
                                        <span class="channel-protection-sub" style="color: var(--gold-accent);"><?= View::e($directSubtitle) ?></span>
                                    </div>
                                </div>

                                <div class="channel-btn-right">
                                    <div class="channel-price-wrap">
                                        <span class="channel-price-label" style="color: var(--text-secondary);"><?= View::e($directPixLabel) ?></span>
                                        <span class="channel-price-val vip-pix-val" 
                                              style="color: var(--gold-accent); font-size: 1.05rem;"
                                              data-discount-pct="<?= $directPixDiscountPct ?>"
                                              data-override="<?= View::e($directPriceOverride) ?>">
                                            R$ <?= $directPixFormatted ?>
                                        </span>
                                    </div>
                                    <span class="channel-arrow-circle" style="background: var(--gold-accent); color: var(--bg-dark, #202020);">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                                    </span>
                                </div>
                            </a>
                        </div>
                        <?php endif; ?>

                        <!-- 2. Lista ou Grade de Canais de Venda Externos (Marketplaces & Consultor) -->
                        <?php if (!empty($displayChannels)): ?>
                            <div class="channels-action-list <?= $buttonLayout === 'grid' ? 'channels-layout-grid' : 'channels-layout-list' ?>">
                                <?php foreach ($displayChannels as $channel): 
                                    $platSlug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $channel['platform']));
                                    $targetAttr = !empty($channel['target']) && $channel['target'] === '_self' ? '_self' : '_blank';
                                ?>
                                    <div class="channel-option-wrapper">
                                        <a href="<?= View::e($channel['url']) ?>" 
                                           target="<?= $targetAttr ?>" 
                                           rel="noopener noreferrer"
                                           class="btn-channel-impartial btn-channel-external platform-<?= $platSlug ?>"
                                           data-platform="<?= View::e(strtolower($channel['platform'])) ?>"
                                           data-track-product-id="<?= $prodId ?>"
                                           data-track-product-name="<?= View::e($prodName) ?>"
                                           data-track-source="detail_<?= $platSlug ?>"
                                           data-has-override="<?= $channel['has_override'] ? '1' : '0' ?>"
                                           title="<?= View::e($channel['title']) ?> - R$ <?= $channel['price_formatted'] ?>">
                                            
                                            <!-- Lado Esquerdo: Ícone da Plataforma + Nome de Ação -->
                                            <div class="channel-btn-left">
                                                <span class="channel-icon-pill">
                                                    <?= getPlatformIconSvgShow($channel['icon']) ?>
                                                </span>
                                                <div class="channel-meta-text">
                                                    <span class="channel-action-name">
                                                        <?= View::e($channel['title']) ?>
                                                        <?php if (!empty($channel['badge'])): ?>
                                                            <span class="channel-mini-badge" style="background: rgba(212,186,136,0.18); color: #ded7cf; font-size: 0.65rem; padding: 1px 5px; border-radius: 4px; margin-left: 5px; font-weight: 600; border: 1px solid rgba(212,186,136,0.3); vertical-align: middle;">
                                                                <?= View::e($channel['badge']) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </span>
                                                    <span class="channel-protection-sub"><?= View::e($channel['official_label']) ?></span>
                                                </div>
                                            </div>

                                            <!-- Lado Direito: Preço Específico e Seta Elegante -->
                                            <div class="channel-btn-right">
                                                <div class="channel-price-wrap">
                                                    <span class="channel-price-label">Preço</span>
                                                    <span class="channel-price-val">R$ <?= $channel['price_formatted'] ?></span>
                                                </div>
                                                <span class="channel-arrow-circle" aria-hidden="true">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                                                </span>
                                            </div>

                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <!-- Fallback: Atendimento Direto WhatsApp -->
                            <div class="channel-option-wrapper">
                                <a href="<?= View::e(Setting::get('store_whatsapp_url', 'https://wa.me/5511999999999')) ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="btn-channel-impartial btn-channel-external platform-whatsapp"
                                   data-platform="whatsapp"
                                   data-track-product-id="<?= $prodId ?>"
                                   data-track-product-name="<?= View::e($prodName) ?>"
                                   data-track-source="detail_whatsapp_fallback">
                                    
                                    <div class="channel-btn-left">
                                        <span class="channel-icon-pill">
                                            <?= getPlatformIconSvgShow('whatsapp') ?>
                                        </span>
                                        <div class="channel-meta-text">
                                            <span class="channel-action-name">Consultar Disponibilidade com Consultor VIP</span>
                                            <span class="channel-protection-sub">Atendimento Direto DIVINIUM</span>
                                        </div>
                                    </div>

                                    <div class="channel-btn-right">
                                        <div class="channel-price-wrap">
                                            <span class="channel-price-label">Referência</span>
                                            <span class="channel-price-val">R$ <?= $basePriceFormatted ?></span>
                                        </div>
                                        <span class="channel-arrow-circle">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                                        </span>
                                    </div>

                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- 3. Informações de Confiança, Nota Fiscal & Garantia de Fábrica -->
                        <?php if ($showTrustFootnote && !empty($trustText)): ?>
                        <div class="channels-security-footnote">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                            <span><?= View::e($trustText) ?></span>
                        </div>
                        <?php endif; ?>

                    </div>
                    <?php endif; ?>

                    <!-- DESCRIÇÃO COMPLETA DA JOIA -->
                    <?php if ($showProdDesc): ?>
                    <div class="product-description-card">
                        <h3 class="section-subheading">A Essência da Peça</h3>
                        <div class="product-storytelling-text">
                            <?php if (!empty($product['description'])): ?>
                                <?= nl2br(View::e($product['description'])) ?>
                            <?php elseif (!empty($product['short_description'])): ?>
                                <?= nl2br(View::e($product['short_description'])) ?>
                            <?php else: ?>
                                <p>Joia autoral manufaturada com banho de prata com lapidação mineral precisa e acabamento artesanal espelhado. Uma expressão sublime do luxo silencioso, criada para transcender tendências e permanecer na sua história.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- FICHA TÉCNICA / ESPECIFICAÇÕES & MANUFATURA -->
                    <?php if ($showProdSpecs && $globalShowSpecs): ?>
                    <div class="product-specs-card">
                        <h3 class="section-subheading"><?= View::e(Setting::get('store_specs_card_title', 'Especificações & Manufatura')) ?></h3>
                        
                        <dl class="specs-grid-table">
                            <?php 
                            $specsList = Setting::getProductSpecsList();
                            foreach ($specsList as $item):
                                if (empty($item['visible'])) continue;
                                $itemId = $item['id'] ?? '';
                                $type = $item['type'] ?? 'custom';
                                $itemTitle = trim((string)($item['title'] ?? ''));
                                $itemDesc = trim((string)($item['desc'] ?? ''));
                            ?>
                                <?php if ($itemId === 'material' || $type === 'mineral'): ?>
                                    <?php 
                                    $val = $itemDesc !== '' ? $itemDesc : $prodMineral;
                                    if (!empty($val)):
                                    ?>
                                    <div class="spec-row">
                                        <dt class="spec-term"><?= View::e($itemTitle ?: 'Material') ?></dt>
                                        <dd class="spec-value highlight-gold" id="specMineralValue">✦ <?= View::e($val) ?></dd>
                                    </div>
                                    <?php endif; ?>

                                <?php elseif ($itemId === 'acabamento' || $type === 'materials'): ?>
                                    <?php 
                                    $val = $itemDesc !== '' ? $itemDesc : $prodMaterials;
                                    if (!empty($val)):
                                    ?>
                                    <div class="spec-row">
                                        <dt class="spec-term"><?= View::e($itemTitle ?: 'Acabamento') ?></dt>
                                        <dd class="spec-value"><?= View::e($val) ?></dd>
                                    </div>
                                    <?php endif; ?>

                                <?php elseif ($itemId === 'design'): ?>
                                    <?php 
                                    $val = $itemDesc !== '' ? $itemDesc : 'Design Autoral Exclusivo DIVINIUM • Linhas Anatômicas Contemporâneas';
                                    ?>
                                    <div class="spec-row">
                                        <dt class="spec-term"><?= View::e($itemTitle ?: 'Design') ?></dt>
                                        <dd class="spec-value highlight-gold">✦ <?= View::e($val) ?></dd>
                                    </div>

                                <?php elseif ($itemId === 'dimensoes' || $type === 'dimensions'): ?>
                                    <?php 
                                    $val = $itemDesc !== '' ? $itemDesc : $prodDimensions;
                                    if (!empty($val)):
                                    ?>
                                    <div class="spec-row">
                                        <dt class="spec-term"><?= View::e($itemTitle ?: 'Dimensões & Ajuste') ?></dt>
                                        <dd class="spec-value"><?= View::e($val) ?></dd>
                                    </div>
                                    <?php endif; ?>

                                <?php elseif ($itemId === 'cores' || $type === 'colors'): ?>
                                    <?php 
                                    $val = $itemDesc !== '' ? $itemDesc : $prodColors;
                                    if (!empty($val)):
                                    ?>
                                    <div class="spec-row">
                                        <dt class="spec-term"><?= View::e($itemTitle ?: 'Lapidação & Tonalidade') ?></dt>
                                        <dd class="spec-value"><?= View::e($val) ?></dd>
                                    </div>
                                    <?php endif; ?>

                                <?php elseif ($itemId === 'autenticidade'): ?>
                                    <?php 
                                    $val = $itemDesc !== '' ? $itemDesc : Setting::get('store_spec_authenticity_text', 'Certificado de Banho de Prata com Garantia Vitalícia');
                                    ?>
                                    <div class="spec-row">
                                        <dt class="spec-term"><?= View::e($itemTitle ?: 'Autenticidade') ?></dt>
                                        <dd class="spec-value"><?= View::e($val) ?></dd>
                                    </div>

                                <?php elseif ($itemId === 'sku' || $type === 'sku'): ?>
                                    <?php if ($canShowSku && Setting::get('store_spec_sku_visible', '1') === '1'): ?>
                                    <div class="spec-row">
                                        <dt class="spec-term"><?= View::e($itemTitle ?: 'Referência / SKU') ?></dt>
                                        <dd class="spec-value" id="specSkuValue">#<?= View::e($prodSku) ?></dd>
                                    </div>
                                    <?php endif; ?>

                                <?php elseif ($itemId === 'disponibilidade' || $type === 'availability'): ?>
                                    <div class="spec-row">
                                        <dt class="spec-term"><?= View::e($itemTitle ?: 'Disponibilidade') ?></dt>
                                        <dd class="spec-value" id="specStockValue">
                                            <?php if ($itemDesc !== ''): ?>
                                                <span style="color: var(--text-primary); font-weight: 600;">● <?= View::e($itemDesc) ?></span>
                                            <?php elseif ($prodStockStatus === 'preorder'): ?>
                                                <span style="color: var(--gold-accent, #737B85); font-weight: 600;">● <?= View::e(Setting::get('store_availability_preorder_text', 'Manufatura Sob Encomenda (Envio prioritário)')) ?></span>
                                            <?php elseif ($prodStockStatus === 'out_of_stock'): ?>
                                                <span style="color: #f87171;">● <?= View::e(Setting::get('store_availability_outofstock_text', 'Temporariamente Esgotado')) ?></span>
                                            <?php else: ?>
                                                <span style="color: var(--text-primary); font-weight: 600;">● <?= View::e(Setting::get('store_availability_instock_text', 'Pronta Entrega para Envio Imediato')) ?></span>
                                            <?php endif; ?>
                                        </dd>
                                    </div>

                                <?php else: ?>
                                    <?php if ($itemTitle !== '' || $itemDesc !== ''): ?>
                                    <div class="spec-row">
                                        <dt class="spec-term"><?= View::e($itemTitle) ?></dt>
                                        <dd class="spec-value"><?= View::e($itemDesc) ?></dd>
                                    </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </dl>
                    </div>
                    <?php endif; ?>

                    <!-- SELO DE GARANTIA & EXCELÊNCIA DIVINIUM -->
                    <?php if ($globalShowGuarantee): ?>
                    <div class="product-guarantee-card">
                        <div class="guarantee-header">
                            <span class="guarantee-icon">🛡️</span>
                            <div>
                                <h4 class="guarantee-title"><?= View::e(Setting::get('store_detail_guarantee_title', 'Autenticidade & Garantia DIVINIUM')) ?></h4>
                                <p class="guarantee-desc"><?= View::e(Setting::get('store_detail_guarantee_text', 'Cada joia é acompanhada de certificado numerado de banho de prata, estojo de presente aveludado e garantia vitalícia contra defeitos no acabamento.')) ?></p>
                            </div>
                        </div>

                        <div class="guarantee-perks-grid">
                            <div class="perk-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                <span>Envio Assegurado p/ todo Brasil</span>
                            </div>
                            <div class="perk-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                <span>Estojo Rígido de Presente</span>
                            </div>
                            <div class="perk-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                <span>Garantia de Qualidade Banho de Prata</span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </section>

    <!-- 3. SEÇÃO DE JOIAS RELACIONADAS DA MESMA COLEÇÃO -->
    <?php if ($globalShowRelated && !empty($relatedProducts)): ?>
    <section class="product-related-section">
        <div class="container">
            <div class="related-section-header">
                <span class="related-eyebrow">DESCUBRA MAIS</span>
                <h2 class="related-title">Outras Peças da Coleção</h2>
            </div>

            <div class="related-products-grid">
                <?php foreach ($relatedProducts as $relProd): 
                    $relPrice = number_format(($relProd['price_cents'] ?? 38000) / 100, 2, ',', '.');
                    $relSlug = $relProd['slug'] ?? 'joia-' . $relProd['id'];
                ?>
                    <article class="related-product-card">
                        <a href="/produto/<?= View::e($relSlug) ?>" class="related-card-media">
                            <img src="<?= View::e($relProd['image_url'] ?: '/assets/images/hero/featured-green-shell.jpg') ?>" 
                                 alt="<?= View::e($relProd['name']) ?>" 
                                 loading="lazy"
                                 class="related-card-img"
                                 onerror="this.onerror=null; this.src='/assets/images/hero/featured-green-shell.jpg';">
                        </a>
                        <div class="related-card-body">
                            <span class="related-card-mineral"><?= View::e($relProd['stone_mineral'] ?? 'Mineral Raro') ?></span>
                            <h4 class="related-card-title">
                                <a href="/produto/<?= View::e($relSlug) ?>"><?= View::e($relProd['name']) ?></a>
                            </h4>
                            <div class="related-card-price">
                                <span>R$ <?= $relPrice ?></span>
                            </div>
                            <a href="/produto/<?= View::e($relSlug) ?>" class="btn-related-view">
                                Conhecer Peça ↗
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

</div>

<!-- Script de Interatividade & Feedback ao Copiar Link -->
<script>
function selectProductVariation(btn) {
    if (!btn) return;
    
    // Atualizar classe ativa
    document.querySelectorAll('.variation-chip').forEach(c => {
        c.classList.remove('active');
        c.setAttribute('aria-checked', 'false');
    });
    btn.classList.add('active');
    btn.setAttribute('aria-checked', 'true');
    
    const sku = btn.getAttribute('data-sku') || '';
    const name = btn.getAttribute('data-name') || '';
    const stone = btn.getAttribute('data-stone') || '';
    const priceFormatted = btn.getAttribute('data-price-formatted') || '';
    const priceCents = parseInt(btn.getAttribute('data-price-cents') || '0', 10);
    const imgUrl = btn.getAttribute('data-image') || '';
    const stock = btn.getAttribute('data-stock') || 'in_stock';
    const extUrl = btn.getAttribute('data-url') || '';
    
    // 1. Atualizar nome da pedra mineral selecionada
    const stoneDisplay = document.getElementById('selectedStoneName');
    if (stoneDisplay && stone) {
        stoneDisplay.textContent = stone;
    }
    
    // 2. Atualizar foto com transição suave
    const mainImg = document.getElementById('productMainImage');
    if (mainImg && imgUrl && mainImg.getAttribute('src') !== imgUrl) {
        mainImg.style.opacity = '0.25';
        mainImg.style.transition = 'opacity 0.2s ease';
        setTimeout(() => {
            mainImg.src = imgUrl;
            mainImg.onload = () => { mainImg.style.opacity = '1'; };
            setTimeout(() => { mainImg.style.opacity = '1'; }, 150);
        }, 100);
    }
    
    // 3. Atualizar preço principal
    const priceEl = document.querySelector('.price-current-large');
    if (priceEl && priceFormatted) {
        priceEl.textContent = priceFormatted;
    }
    
    // 4. Atualizar desconto no Pix e parcelamento no cartão
    const pixEl = document.querySelector('.price-pix-badge span:last-child');
    if (pixEl && priceCents > 0) {
        const pixVal = (priceCents * 0.95) / 100;
        pixEl.textContent = 'R$ ' + pixVal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' no Pix (-5%)';
    }
    const vipPixVal = document.querySelector('.vip-pix-val');
    if (vipPixVal && priceCents > 0) {
        const hasOverride = vipPixVal.getAttribute('data-override');
        if (!hasOverride || hasOverride.trim() === '') {
            const discountPct = parseFloat(vipPixVal.getAttribute('data-discount-pct') || '5');
            const pixVal = (priceCents * (1 - (discountPct / 100))) / 100;
            vipPixVal.textContent = 'R$ ' + pixVal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
    }
    const installmentsEl = document.querySelector('.price-installments-row span');
    if (installmentsEl && priceCents > 0) {
        const instVal = (priceCents / 600).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        installmentsEl.textContent = 'Em até 6x de R$ ' + instVal + ' sem juros nos canais oficiais homologados';
    }

    // 5. Atualizar ficha técnica no rodapé
    const specMin = document.getElementById('specMineralValue');
    if (specMin && stone) {
        specMin.textContent = '✦ ' + stone;
    }
    const specSku = document.getElementById('specSkuValue');
    if (specSku && sku) {
        specSku.textContent = '#' + sku;
    }
    const specStock = document.getElementById('specStockValue');
    if (specStock) {
        if (stock === 'out_of_stock') {
            specStock.innerHTML = '<span style="color: var(--gold-accent, #737B85); font-weight: 600;">● Manufatura Sob Encomenda (Envio prioritário)</span>';
        } else {
            specStock.innerHTML = '<span style="color: var(--text-primary, #202020); font-weight: 600;">● Pronta Entrega para Envio Imediato</span>';
        }
    }
    
    // 6. Atualizar SKU / Código da Joia no card da galeria
    const skuDisplay = document.querySelector('.media-sku-text strong');
    if (skuDisplay && sku) {
        skuDisplay.textContent = '#' + sku;
    }
    
    // 7. Atualizar Badge Mineral na foto
    const badgeMineral = document.querySelector('.badge-mineral');
    if (badgeMineral && stone) {
        badgeMineral.textContent = '✦ ' + stone;
    }
    
    // 8. Atualizar Badge de Disponibilidade na foto
    const badgeStock = document.querySelector('.product-media-badges .media-badge:first-child');
    if (badgeStock) {
        if (stock === 'out_of_stock') {
            badgeStock.className = 'media-badge badge-preorder';
            badgeStock.textContent = 'Sob Encomenda';
        } else {
            badgeStock.className = 'media-badge badge-metal';
            badgeStock.textContent = '<?= View::e($metalBadgeText) ?>';
        }
    }
    
    // 9. Atualizar exibição de preço nos canais externos caso não haja preço fixo de canal
    document.querySelectorAll('.btn-channel-external').forEach(cBtn => {
        const hasOverride = cBtn.getAttribute('data-has-override') === '1';
        const pVal = cBtn.querySelector('.channel-price-val');
        if (pVal && priceFormatted && !hasOverride) {
            pVal.textContent = priceFormatted;
        }
    });

    // 10. Atualizar URL com o parâmetro da variante selecionada sem recarregar a página
    if (sku && window.history && window.history.replaceState) {
        const curUrl = new URL(window.location);
        curUrl.searchParams.set('variante', sku);
        window.history.replaceState({}, '', curUrl.toString());
    }
}

function buySelectedVariation(event, isDirectCheckout, targetUrl) {
    if (event) {
        event.preventDefault();
    }
    const activeChip = document.querySelector('.variation-chip.active') || document.querySelector('.variation-chip');
    const sku = activeChip ? activeChip.getAttribute('data-sku') : '<?= View::e($prodSku) ?>';
    const stone = activeChip ? activeChip.getAttribute('data-stone') : (document.getElementById('selectedStoneName')?.textContent || '<?= View::e($prodMineral) ?>');
    const priceCents = activeChip ? parseInt(activeChip.getAttribute('data-price-cents') || '<?= $basePriceCents ?>', 10) : <?= $basePriceCents ?>;
    const imgUrl = activeChip ? activeChip.getAttribute('data-image') : (document.getElementById('productMainImage')?.getAttribute('src') || '<?= View::e($prodImageUrl) ?>');
    const modelName = <?= json_encode($prodName) ?>;
    const fullName = modelName + (stone ? ' - ' + stone : '');

    if (typeof addToCart === 'function') {
        addToCart({
            id: <?= $prodId ?>,
            sku: sku,
            name: fullName,
            price: priceCents,
            image: imgUrl,
            stone: stone
        });
    }

    if (isDirectCheckout) {
        const dest = (targetUrl && targetUrl.trim() !== '') ? targetUrl.trim() : '/checkout';
        if (dest.startsWith('http')) {
            window.open(dest, '_blank', 'noopener,noreferrer');
        } else {
            window.location.href = dest;
        }
    }
}

function copyProductLink(url) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(function() {
            showCopyFeedback();
        }).catch(function() {
            fallbackCopyText(url);
        });
    } else {
        fallbackCopyText(url);
    }
}

function fallbackCopyText(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        document.execCommand('copy');
        showCopyFeedback();
    } catch (err) {
        prompt('Copie o link da joia:', text);
    }
    document.body.removeChild(textArea);
}

function showCopyFeedback() {
    const btn = document.getElementById('btnCopyLink');
    const txt = document.getElementById('copyLinkText');
    if (!btn || !txt) return;
    
    const orig = txt.textContent;
    txt.textContent = 'Link Copiado!';
    btn.style.borderColor = 'var(--gold-accent)';
    btn.style.color = 'var(--gold-accent)';
    
    setTimeout(() => {
        txt.textContent = orig;
        btn.style.borderColor = '';
        btn.style.color = '';
    }, 2500);
}

// Rastreamento assíncrono de cliques em canais na página de detalhes
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-channel-impartial').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const prodId = this.getAttribute('data-track-product-id');
            const prodName = this.getAttribute('data-track-product-name');
            const source = this.getAttribute('data-track-source') || 'detail_page';
            
            if (prodId && window.fetch) {
                fetch('/api/track/click', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        product_id: parseInt(prodId, 10),
                        product_name: prodName,
                        source: source
                    })
                }).catch(function(){});
            }
        });
    });
});
</script>
