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
        if (str_contains($plat, 'oficial') || str_contains($plat, 'divinium')) {
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
        return '#d4ba88';
    }
}

// Extrair propriedades do produto com fallbacks elegantes
$prodId = (int) ($product['id'] ?? 0);
$prodName = (string) ($product['name'] ?? 'Joia Autoral DIVINIUM');
$prodSlug = (string) ($product['slug'] ?? 'joia');
$prodCategory = !empty($product['category_name']) ? $product['category_name'] : 'Coleção Autoral';
$prodMineral = trim((string) ($product['stone_mineral'] ?? ''));
$prodMaterials = trim((string) ($product['materials'] ?? 'Prata de Lei 925'));
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

// Configurações globais do painel (/admin/boutique)
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
    
    <!-- 1. BREADCRUMB LUXURY DE NAVEGAÇÃO -->
    <nav class="product-breadcrumb-nav" aria-label="Navegação estrutural">
        <div class="container">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="/">Início</a></li>
                <li class="breadcrumb-separator">/</li>
                <li class="breadcrumb-item"><a href="/loja"><?= View::e(Setting::get('nav_link_store', 'Boutique')) ?></a></li>
                <?php if ($prodCategory): ?>
                    <li class="breadcrumb-separator">/</li>
                    <li class="breadcrumb-item"><a href="/loja#catalogo"><?= View::e($prodCategory) ?></a></li>
                <?php endif; ?>
                <li class="breadcrumb-separator">/</li>
                <li class="breadcrumb-item active" aria-current="page"><?= View::e($prodName) ?></li>
            </ol>
        </div>
    </nav>

    <!-- 2. SEÇÃO PRINCIPAL DO PRODUTO (LAYOUT 2 COLUNAS) -->
    <section class="product-main-section">
        <div class="container">
            <div class="product-detail-layout">
                
                <!-- COLUNA ESQUERDA: GALERIA DE FOTOS & MÍDIA -->
                <div class="product-media-column">
                    <div class="product-gallery-card">
                        
                        <!-- Badges Flutuantes na Foto -->
                        <?php if ($showProdBadge): ?>
                        <div class="product-media-badges">
                            <?php if ($prodStockStatus === 'preorder'): ?>
                                <span class="media-badge badge-preorder">Sob Encomenda</span>
                            <?php elseif ($prodStockStatus === 'out_of_stock'): ?>
                                <span class="media-badge badge-out">Indisponível</span>
                            <?php else: ?>
                                <span class="media-badge badge-metal">Prata de Lei 925</span>
                            <?php endif; ?>

                            <?php if ($prodMineral): ?>
                                <span class="media-badge badge-mineral">✦ <?= View::e($prodMineral) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Imagem Principal com Zoom Suave -->
                        <div class="product-main-image-wrap" id="mainImageWrapper">
                            <img src="<?= View::e($prodImageUrl) ?>" 
                                 alt="<?= View::e($prodName) ?>" 
                                 id="productMainImage"
                                 class="product-main-image"
                                 loading="eager"
                                 onerror="this.onerror=null; this.src='/assets/images/hero/featured-green-shell.jpg';">
                        </div>

                        <!-- Barra de Ações & Compartilhamento -->
                        <?php if ($globalShowShare): ?>
                        <div class="product-media-footer">
                            <span class="media-sku-text">Código da Joia: <strong>#<?= View::e($prodSku) ?></strong></span>
                            
                            <div class="media-share-actions">
                                <button type="button" class="btn-share-icon" onclick="copyProductLink('<?= View::e($currentUrl) ?>')" title="Copiar link próprio desta joia" id="btnCopyLink">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                    <span id="copyLinkText">Compartilhar Joia</span>
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
                    <!-- PAINEL DE COMPRA PADRONIZADO E IMPARCIAL (TODOS OS CANAIS EQUIVALENTES) -->
                    <!-- ============================================================== -->
                    <?php if ($showProdChannels): ?>
                    <div class="product-purchase-channels-box" id="canais-de-compra">
                        
                        <div class="channels-box-header">
                            <div class="channels-header-text">
                                <h3 class="channels-box-title">
                                    <?= View::e(Setting::get('store_detail_channels_title', 'Escolha onde deseja adquirir sua joia:')) ?>
                                </h3>
                                <p class="channels-box-subtitle">
                                    <?= View::e(Setting::get('store_detail_channels_subtitle', 'Selecione seu canal oficial de preferência. Todos contam com garantia de autenticidade, embalagem exclusiva e compra 100% protegida.')) ?>
                                </p>
                            </div>
                        </div>

                        <!-- Opção Direta de Compra na Boutique Divinium (com Pix 5% OFF) -->
                        <div class="channel-option-wrapper" style="margin-bottom: 14px;">
                            <a href="/checkout" 
                               id="btnDirectCheckoutVip"
                               onclick="buySelectedVariation(event, true);" 
                               class="btn-channel-impartial platform-direct-vip"
                               style="background: linear-gradient(135deg, rgba(212,186,136,0.18) 0%, rgba(20,18,16,0.98) 100%); border: 1.5px solid #d4ba88; box-shadow: 0 4px 15px rgba(212,186,136,0.15);">
                                
                                <div class="channel-btn-left">
                                    <span class="channel-icon-pill" style="background: rgba(212,186,136,0.2); color: #d4ba88;">
                                        💎
                                    </span>
                                    <div class="channel-meta-text">
                                        <span class="channel-action-name" style="color: #ffffff; font-weight: 700;">Comprar Direto na DIVINIUM</span>
                                        <span class="channel-protection-sub" style="color: #d4ba88;">5% OFF no Pix • Envio com Seguro Blindado</span>
                                    </div>
                                </div>

                                <div class="channel-btn-right">
                                    <div class="channel-price-wrap">
                                        <span class="channel-price-label" style="color: #4ade80;">Pix (-5%)</span>
                                        <span class="channel-price-val vip-pix-val" style="color: #d4ba88; font-size: 1.05rem;">R$ <?= $pixPriceFormatted ?></span>
                                    </div>
                                    <span class="channel-arrow-circle" style="background: #d4ba88; color: #000;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                                    </span>
                                </div>
                            </a>
                        </div>

                        <!-- Lista ou Grade Imparcial de Opções de Compra -->
                        <?php if (!empty($validLinks)): ?>
                            <div class="channels-action-list <?= $buttonLayout === 'grid' ? 'channels-layout-grid' : 'channels-layout-list' ?>">
                                <?php foreach ($validLinks as $index => $link): 
                                    $platform = !empty($link['platform_name']) ? trim($link['platform_name']) : 'Loja Oficial';
                                    $platSlug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $platform));
                                    $linkUrl = trim($link['url']);
                                    $linkPriceCents = !empty($link['price_cents']) ? (int)$link['price_cents'] : $basePriceCents;
                                    $linkPriceFormatted = number_format($linkPriceCents / 100, 2, ',', '.');
                                    
                                    // Texto do botão: Se não configurado especificamente, usa o padrão imparcial: "Comprar na {Plataforma}"
                                    $btnText = !empty($link['button_text']) 
                                        ? $link['button_text'] 
                                        : (stripos($platform, 'whatsapp') !== false ? 'Consultar via WhatsApp VIP' : ('Comprar na ' . $platform));
                                    
                                    $targetAttr = !empty($link['target']) && $link['target'] === '_self' ? '_self' : '_blank';
                                    
                                    // Customização visual de cor/tamanho (se imparcialidade não for forçada ou se for estilo nobre)
                                    $btnColor = $link['button_color'] ?? 'default';
                                    $btnSize = $link['button_size'] ?? 'md';
                                ?>
                                    <div class="channel-option-wrapper">
                                        <a href="<?= View::e($linkUrl) ?>" 
                                           target="<?= $targetAttr ?>" 
                                           rel="noopener noreferrer"
                                           class="btn-channel-impartial btn-channel-external platform-<?= $platSlug ?>"
                                           data-track-product-id="<?= $prodId ?>"
                                           data-track-product-name="<?= View::e($prodName) ?>"
                                           data-track-source="detail_<?= $platSlug ?>"
                                           title="<?= View::e($btnText) ?> - R$ <?= $linkPriceFormatted ?>">
                                            
                                            <!-- Lado Esquerdo: Ícone da Plataforma + Nome de Ação -->
                                            <div class="channel-btn-left">
                                                <span class="channel-icon-pill">
                                                    <?= getPlatformIconSvgShow($platform) ?>
                                                </span>
                                                <div class="channel-meta-text">
                                                    <span class="channel-action-name"><?= View::e($btnText) ?></span>
                                                    <span class="channel-protection-sub">Canal Homologado DIVINIUM</span>
                                                </div>
                                            </div>

                                            <!-- Lado Direito: Preço Específico e Seta Elegante -->
                                            <div class="channel-btn-right">
                                                <div class="channel-price-wrap">
                                                    <span class="channel-price-label">Preço</span>
                                                    <span class="channel-price-val">R$ <?= $linkPriceFormatted ?></span>
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
                            <!-- Fallback Caso Nenhum Link Esteja Cadastrado: Atendimento Direto WhatsApp -->
                            <div class="channel-option-wrapper">
                                <a href="<?= View::e(Setting::get('store_whatsapp_url', 'https://wa.me/5511999999999')) ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="btn-channel-impartial btn-channel-external platform-whatsapp"
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

                        <!-- Nota de Segurança e Imparcialidade -->
                        <div class="channels-security-footnote">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                            <span>Todos os canais listados possuem certificação de autenticidade, nota fiscal e garantia de fábrica DIVINIUM.</span>
                        </div>

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
                                <p>Joia autoral manufaturada em prata de lei 925 com lapidação mineral precisa e acabamento artesanal espelhado. Uma expressão sublime do luxo silencioso, criada para transcender tendências e permanecer na sua história.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- FICHA TÉCNICA / CARACTERÍSTICAS NOBRES -->
                    <?php if ($showProdSpecs && $globalShowSpecs): ?>
                    <div class="product-specs-card">
                        <h3 class="section-subheading">Especificações & Manufatura</h3>
                        
                        <dl class="specs-grid-table">
                            <?php if ($prodMineral): ?>
                            <div class="spec-row">
                                <dt class="spec-term">Gema / Mineral</dt>
                                <dd class="spec-value highlight-gold" id="specMineralValue">✦ <?= View::e($prodMineral) ?></dd>
                            </div>
                            <?php endif; ?>

                            <?php if ($prodMaterials): ?>
                            <div class="spec-row">
                                <dt class="spec-term">Metal Nobre</dt>
                                <dd class="spec-value"><?= View::e($prodMaterials) ?></dd>
                            </div>
                            <?php endif; ?>

                            <?php if ($prodDimensions): ?>
                            <div class="spec-row">
                                <dt class="spec-term">Dimensões & Ajuste</dt>
                                <dd class="spec-value"><?= View::e($prodDimensions) ?></dd>
                            </div>
                            <?php endif; ?>

                            <?php if ($prodColors): ?>
                            <div class="spec-row">
                                <dt class="spec-term">Acabamento</dt>
                                <dd class="spec-value"><?= View::e($prodColors) ?></dd>
                            </div>
                            <?php endif; ?>

                            <div class="spec-row">
                                <dt class="spec-term">Autenticidade</dt>
                                <dd class="spec-value">Certificado de Prata de Lei 925 com Garantia Vitalícia</dd>
                            </div>

                            <div class="spec-row">
                                <dt class="spec-term">Referência / SKU</dt>
                                <dd class="spec-value" id="specSkuValue">#<?= View::e($prodSku) ?></dd>
                            </div>

                            <div class="spec-row">
                                <dt class="spec-term">Disponibilidade</dt>
                                <dd class="spec-value" id="specStockValue">
                                    <?php if ($prodStockStatus === 'preorder'): ?>
                                        <span style="color: #fbbf24;">● Manufatura Sob Encomenda (Envio prioritário)</span>
                                    <?php elseif ($prodStockStatus === 'out_of_stock'): ?>
                                        <span style="color: #f87171;">● Temporariamente Esgotado</span>
                                    <?php else: ?>
                                        <span style="color: #4ade80;">● Pronta Entrega para Envio Imediato</span>
                                    <?php endif; ?>
                                </dd>
                            </div>
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
                                <p class="guarantee-desc"><?= View::e(Setting::get('store_detail_guarantee_text', 'Cada joia é acompanhada de certificado numerado de prata de lei 925, estojo de presente aveludado e garantia vitalícia contra defeitos no metal.')) ?></p>
                            </div>
                        </div>

                        <div class="guarantee-perks-grid">
                            <div class="perk-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                <span>Envio Assegurado p/ todo Brasil</span>
                            </div>
                            <div class="perk-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                <span>Estojo Rígido de Presente</span>
                            </div>
                            <div class="perk-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                <span>Garantia Vitalícia do Metal 925</span>
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
        const pixVal = (priceCents * 0.95) / 100;
        vipPixVal.textContent = 'R$ ' + pixVal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
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
            specStock.innerHTML = '<span style="color: #fbbf24;">● Manufatura Sob Encomenda (Envio prioritário)</span>';
        } else {
            specStock.innerHTML = '<span style="color: #4ade80;">● Pronta Entrega para Envio Imediato</span>';
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
            badgeStock.textContent = 'Prata de Lei 925';
        }
    }
    
    // 9. Atualizar botões de canais externos (Shopee e outros) sem alterar o botão de checkout VIP interno
    if (extUrl) {
        document.querySelectorAll('.btn-channel-external').forEach(cBtn => {
            cBtn.href = extUrl;
            const pVal = cBtn.querySelector('.channel-price-val');
            if (pVal && priceFormatted) {
                pVal.textContent = priceFormatted;
            }
        });
    }

    // 10. Atualizar URL com o parâmetro da variante selecionada sem recarregar a página
    if (sku && window.history && window.history.replaceState) {
        const curUrl = new URL(window.location);
        curUrl.searchParams.set('variante', sku);
        window.history.replaceState({}, '', curUrl.toString());
    }
}

function buySelectedVariation(event, isDirectCheckout) {
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
        window.location.href = '/checkout';
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
    btn.style.borderColor = '#d4ba88';
    btn.style.color = '#d4ba88';
    
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
