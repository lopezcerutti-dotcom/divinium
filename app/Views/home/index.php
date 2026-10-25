<?php
/**
 * VIEW: PÁGINA INICIAL DIVINIUM - COLEÇÃO CAMINHOS (QUIET LUXURY)
 * Estruturada com as imagens e mensagens dinâmicas da marca.
 */
use App\Models\Setting;
use App\Core\View;
use App\Models\Collection;

$renderSection = [];
ob_start();
?>

<!-- ======================================================================= -->
<!-- SEÇÃO 1: HERO PRINCIPAL (HAUTE JOAILLERIE & QUIET LUXURY EXPERIENCE) -->
<!-- ======================================================================= -->
<section class="hero-section" id="hero">
    <!-- Efeitos Atmosféricos: Halo Dourado de Iluminação e Ruído Nobre -->
    <div class="hero-ambient-glow"></div>
    <div class="hero-spotlight"></div>
    <div class="hero-overlay"></div>

    <div class="container hero-container">
        
        <!-- Coluna Esquerda: Storytelling Editorial & Seletor de Minerais -->
        <div class="hero-content">
            <div class="hero-editorial-badge">
                <span class="badge-sparkle">✦</span>
                <span><?= \App\Core\View::e(Setting::get('hero_badge_text', 'HAUTE JOAILLERIE • EDIÇÃO AUTORAL BRASILEIRA')) ?></span>
            </div>
            
            <h1 class="hero-title">
                <?= \App\Core\View::e(Setting::get('hero_title_line1', 'Joias que Revelam o Luxo Silencioso')) ?>
            </h1>
            
            <p class="hero-subtitle">
                <?= \App\Core\View::e(Setting::get('hero_subtitle', 'A nobreza da prata de lei 925 entrelaçada à vibração autêntica de gemas minerais raras. Braceletes CAMINHOS: um mesmo design, diferentes formas de expressar a sua presença.')) ?>
            </p>

            <!-- Grupo de Ações Principais (Botões de Alta Conversão) -->
            <div class="hero-cta-group">
                <?php if (Setting::get('hero_primary_btn_visible', '1') === '1'): ?>
                <a href="<?= \App\Core\View::e(Setting::get('hero_primary_btn_url', Setting::get('hero_cta_primary_link', '/loja'))) ?>" class="btn-hero-gold hero-btn-primary">
                    <span><?= \App\Core\View::e(Setting::get('hero_primary_btn_text', Setting::get('hero_cta_primary_text', 'Explorar Coleção Completa'))) ?></span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
                <?php endif; ?>

                <?php if (Setting::get('hero_secondary_btn_visible', '1') === '1'): 
                    $secBtnUrl = Setting::get('hero_secondary_btn_url');
                    if (empty($secBtnUrl)) {
                        $waNum = preg_replace('/[^0-9]/', '', Setting::get('hero_whatsapp_number', '5511999999999'));
                        $secBtnUrl = "https://wa.me/{$waNum}?text=" . urlencode(Setting::get('whatsapp_floating_message', 'Olá! Gostaria de um atendimento personalizado sobre as joias autorais Divinium.'));
                    }
                ?>
                <a href="<?= \App\Core\View::e($secBtnUrl) ?>" target="_blank" rel="noopener" class="btn-hero-ghost hero-btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    <span><?= \App\Core\View::e(Setting::get('hero_secondary_btn_text', Setting::get('hero_cta_whatsapp_text', 'Concierge WhatsApp'))) ?></span>
                </a>
                <?php endif; ?>
            </div>

            <!-- Selos de Confiança e Exclusividade -->
            <div class="hero-trust-strip">
                <div class="trust-badge">
                    <span class="trust-dot">✦</span>
                    <span><?= \App\Core\View::e(Setting::get('hero_trust_badge_1', 'Prata de Lei 925')) ?></span>
                </div>
                <div class="trust-badge">
                    <span class="trust-dot">✦</span>
                    <span><?= \App\Core\View::e(Setting::get('hero_trust_badge_2', 'Gemas 100% Naturais')) ?></span>
                </div>
                <div class="trust-badge">
                    <span class="trust-dot">✦</span>
                    <span><?= \App\Core\View::e(Setting::get('hero_trust_badge_3', 'Certificado de Garantia')) ?></span>
                </div>
            </div>
        </div>

        <!-- Coluna Direita: CARROSSEL EDITORIAL DE IMAGENS PRINCIPAIS (ALTA JOALHERIA) -->
        <?php
            $carouselAutoplay = Setting::get('hero_carousel_autoplay', '1');
            $carouselInterval = (int) Setting::get('hero_carousel_interval', '5');
            if ($carouselInterval < 2) $carouselInterval = 5;

            $slidesJson = Setting::get('hero_carousel_slides');
            $carouselSlides = [];
            if (!empty($slidesJson)) {
                $decoded = json_decode($slidesJson, true);
                if (is_array($decoded)) {
                    $carouselSlides = $decoded;
                }
            }

            if (empty($carouselSlides)) {
                $mainImg = Setting::get('hero_editorial_main_img', '/assets/images/hero/featured-green-shell.jpg');
                $secImg = Setting::get('hero_editorial_sec_img', '/assets/images/testimonials/lifestyle-wrist.jpeg');
                $carouselSlides = [
                    [
                        'id' => 1,
                        'image_url' => $mainImg,
                        'alt_text' => 'Bracelete Caminhos em Prata 925 com Concha Verde Esmeralda',
                        'link_url' => '/loja',
                        'sort_order' => 1,
                        'is_active' => 1
                    ],
                    [
                        'id' => 2,
                        'image_url' => $secImg,
                        'alt_text' => 'Joia Autoral DIVINIUM no Pulso — Coleção Caminhos',
                        'link_url' => '/loja',
                        'sort_order' => 2,
                        'is_active' => 1
                    ],
                    [
                        'id' => 3,
                        'image_url' => '/assets/images/about/model-wrist-sunglasses.jpg',
                        'alt_text' => 'Alta Joalheria Autoral Brasileira DIVINIUM',
                        'link_url' => '/loja',
                        'sort_order' => 3,
                        'is_active' => 1
                    ]
                ];
            }

            $activeSlides = array_values(array_filter($carouselSlides, fn($s) => ($s['is_active'] ?? 1) == 1));
            usort($activeSlides, fn($a, $b) => ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0));
            if (empty($activeSlides) && !empty($carouselSlides)) {
                $activeSlides = array_values($carouselSlides);
            }
        ?>
        <div class="hero-stage">
            <div class="hero-carousel-container" id="heroCarousel" 
                 data-autoplay="<?= $carouselAutoplay ?>" 
                 data-interval="<?= $carouselInterval * 1000 ?>"
                 tabindex="0"
                 role="region"
                 aria-roledescription="carousel"
                 aria-label="Carrossel de Joias DIVINIUM">
                
                <!-- Efeito Atmosférico de Iluminação Suave -->
                <div class="editorial-ambient-glow"></div>

                <!-- Moldura de Alta Joalheria do Carrossel -->
                <div class="hero-carousel-frame">
                    <!-- Cantos Ornamentais de Alta Joalheria -->
                    <div class="stage-corner corner-tl"></div>
                    <div class="stage-corner corner-tr"></div>
                    <div class="stage-corner corner-bl"></div>
                    <div class="stage-corner corner-br"></div>

                    <!-- Trilho com os Slides -->
                    <div class="hero-carousel-track" id="heroCarouselTrack">
                        <?php foreach ($activeSlides as $idx => $slide): ?>
                            <?php 
                                $hasLink = !empty($slide['link_url']);
                                $imgSrc = $slide['image_url'] ?? '/assets/images/hero/featured-green-shell.jpg';
                                $imgAlt = $slide['alt_text'] ?? 'Joia Autoral DIVINIUM';
                            ?>
                            <div class="hero-carousel-slide <?= $idx === 0 ? 'active' : '' ?>" data-index="<?= $idx ?>">
                                <?php if ($hasLink): ?>
                                    <a href="<?= \App\Core\View::e($slide['link_url']) ?>" class="hero-carousel-link" title="<?= \App\Core\View::e($imgAlt) ?>">
                                        <img src="<?= \App\Core\View::e($imgSrc) ?>" 
                                             alt="<?= \App\Core\View::e($imgAlt) ?>" 
                                             class="hero-carousel-img"
                                             loading="<?= $idx === 0 ? 'eager' : 'lazy' ?>"
                                             onerror="this.onerror=null; this.src='/assets/images/hero/featured-green-shell.jpg';">
                                    </a>
                                <?php else: ?>
                                    <div class="hero-carousel-media-wrap">
                                        <img src="<?= \App\Core\View::e($imgSrc) ?>" 
                                             alt="<?= \App\Core\View::e($imgAlt) ?>" 
                                             class="hero-carousel-img"
                                             loading="<?= $idx === 0 ? 'eager' : 'lazy' ?>"
                                             onerror="this.onerror=null; this.src='/assets/images/hero/featured-green-shell.jpg';">
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Setas Discretas de Navegação Manual -->
                    <?php if (count($activeSlides) > 1): ?>
                    <button type="button" class="hero-carousel-arrow hero-carousel-prev" id="heroCarouselPrev" aria-label="Imagem anterior" title="Imagem anterior">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    <button type="button" class="hero-carousel-arrow hero-carousel-next" id="heroCarouselNext" aria-label="Próxima imagem" title="Próxima imagem">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>

                    <!-- Indicadores de Posição Minimalistas -->
                    <div class="hero-carousel-dots" id="heroCarouselDots">
                        <?php foreach ($activeSlides as $idx => $slide): ?>
                            <button type="button" 
                                    class="hero-carousel-dot <?= $idx === 0 ? 'active' : '' ?>" 
                                    data-index="<?= $idx ?>" 
                                    aria-label="Ir para imagem <?= $idx + 1 ?>"></button>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

</section>
<?php
$renderSection['hero'] = ob_get_clean();
ob_start();
?>

<!-- ======================================================================= -->
<!-- SEÇÃO 2: MANIFESTO & HISTÓRIA (CRAFTED WITH PURPOSE) -->
<!-- ======================================================================= -->
<?php
// Configurações dinâmicas de cores da Seção Manifesto & Sobre a Marca
$aboutBg = Setting::get('about_bg_color', '#252525');
$aboutTitleColor = Setting::get('about_title_color', '#ffffff');
$aboutTextColor = Setting::get('about_text_color', '#E2E4E7');
$aboutAccentColor = Setting::get('about_accent_color', '#A7ADB4');
$aboutTagColor = Setting::get('about_tag_color', '#A7ADB4');
$aboutCardBg = Setting::get('about_card_bg', '#202020');
$aboutCardBorder = Setting::get('about_card_border', 'rgba(167, 173, 180, 0.25)');
$aboutBtnColor = Setting::get('about_btn_color', '#A7ADB4');
$aboutCaptionBg = Setting::get('about_caption_bg', 'rgba(37, 37, 37, 0.85)');
?>
<section class="about-manifesto-section" id="about" style="
    --about-bg: <?= \App\Core\View::e($aboutBg) ?>;
    --about-title: <?= \App\Core\View::e($aboutTitleColor) ?>;
    --about-text: <?= \App\Core\View::e($aboutTextColor) ?>;
    --about-accent: <?= \App\Core\View::e($aboutAccentColor) ?>;
    --about-tag: <?= \App\Core\View::e($aboutTagColor) ?>;
    --about-card-bg: <?= \App\Core\View::e($aboutCardBg) ?>;
    --about-card-border: <?= \App\Core\View::e($aboutCardBorder) ?>;
    --about-btn: <?= \App\Core\View::e($aboutBtnColor) ?>;
    --about-caption-bg: <?= \App\Core\View::e($aboutCaptionBg) ?>;
    background-color: var(--about-bg);
">
    <div class="container about-grid">
        
        <!-- Coluna de Texto e Manifesto -->
        <div class="about-text-column">
            <span class="sub-label" style="color: var(--about-tag);"><?= \App\Core\View::e(Setting::get('about_tag', 'MANIFESTO DIVINIUM')) ?></span>
            <h2 class="section-serif-title" style="color: var(--about-title);">
                <?= \App\Core\View::e(Setting::get('about_title', 'A Beleza Não Precisa Gritar Para Ser Notada')) ?>
            </h2>
            <div class="about-story-text">
                <p style="color: var(--about-text);">
                    <?= \App\Core\View::e(Setting::get('about_p1', 'A DIVINIUM nasceu da convicção de que o verdadeiro luxo é autoral, sutil e profundamente conectado à sua identidade. Em um mercado de réplicas e produções em massa, escolhemos a rota da nobreza mineral.')) ?>
                </p>
                <p style="color: var(--about-text);">
                    <?= \App\Core\View::e(Setting::get('about_p2', 'Cada gema é única em suas veias, coloração e densidade. Encastoadas à mão sobre o aro duplo em prata, as pedras ganham vida e presença táctil sem jamais pesar no pulso.')) ?>
                </p>
                <?php if (Setting::get('about_quote_visible', '1') === '1'): ?>
                <p class="about-quote-text" style="font-family: var(--font-serif); font-size: 1.35rem; color: var(--about-accent); font-style: italic;">
                    "<?= \App\Core\View::e(Setting::get('about_quote', 'Qual delas conversa com você hoje?')) ?>"
                </p>
                <?php endif; ?>
            </div>
            
            <?php if (Setting::get('about_cta_visible', '1') === '1'): ?>
            <div class="about-cta">
                <a href="<?= \App\Core\View::e(Setting::get('about_cta_url', '#services')) ?>" class="btn-outline-dark" style="border-color: var(--about-btn); color: var(--about-btn);"><?= \App\Core\View::e(Setting::get('about_cta_text', 'Conheça Nossa História')) ?></a>
            </div>
            <?php endif; ?>

            <!-- Miniatura com macro da gema -->
            <div class="about-micro-feature" style="background-color: var(--about-card-bg); border-color: var(--about-card-border);">
                <div class="micro-thumb">
                    <img src="<?= \App\Core\View::e(Setting::get('about_micro_img', '/assets/images/about/macro-texture.jpg')) ?>" 
                         alt="<?= \App\Core\View::e(Setting::get('about_micro_title', 'Textura Mineral Autêntica')) ?>" 
                         loading="lazy"
                         onerror="this.onerror=null; this.src='/assets/images/about/macro-texture.png';">
                </div>
                <div class="micro-info">
                    <strong style="color: var(--about-title);"><?= \App\Core\View::e(Setting::get('about_micro_title', 'Textura Mineral Autêntica')) ?></strong>
                    <span style="color: var(--about-text);"><?= \App\Core\View::e(Setting::get('about_micro_desc', 'Fragmentos minerais nobres preservados com resina de alta transparência.')) ?></span>
                </div>
            </div>
        </div>

        <!-- Coluna de Imagem Editorial em Destaque -->
        <div class="about-image-column">
            <div class="editorial-frame">
                <img src="<?= \App\Core\View::e(Setting::get('about_banner_img', '/assets/images/about/banner-caminhos.jpg')) ?>" 
                     alt="Coleção Braceletes Caminhos Divinium" 
                     class="editorial-img" 
                     loading="lazy"
                     onerror="this.onerror=null; this.src='/assets/images/about/banner-caminhos.png';">
                <?php if (Setting::get('about_editorial_caption_visible', '1') === '1'): ?>
                <div class="editorial-caption" style="background: var(--about-caption-bg); color: var(--about-accent);">
                    <span><?= \App\Core\View::e(Setting::get('about_editorial_caption', 'COLEÇÃO CAMINHOS • UM MESMO DESIGN, DIFERENTES FORMAS DE SENTIR')) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

</section>
<?php
$renderSection['about'] = ob_get_clean();
ob_start();
?>

<!-- ======================================================================= -->
<!-- SEÇÃO 3: VITRINE DE PRODUTOS & SLIDER (SIGNATURE SHOWCASE) -->
<!-- ======================================================================= -->
<?php
$activeCollections = \App\Models\Collection::getActive();
if (empty($activeCollections)) {
    $activeCollections = \App\Models\Collection::getAll();
}

// Extrair categorias das coleções ativas para os filtros
$availableCategories = [];
foreach ($activeCollections as $c) {
    $cSlug = !empty($c['category_slug']) ? $c['category_slug'] : 'braceletes';
    $cName = !empty($c['category_name']) ? $c['category_name'] : 'Braceletes';
    if (!isset($availableCategories[$cSlug])) {
        $availableCategories[$cSlug] = $cName;
    }
}
?>
<section class="showcase-section" id="collections">
    <div class="container">
        
        <!-- Cabeçalho da Vitrine com Filtros em Pílula -->
        <div class="showcase-header">
            <div class="showcase-titles">
                <span class="sub-label"><?= View::e(Setting::get('collections_tag', 'SELEÇÃO EXCLUSIVA')) ?></span>
                <h2 class="section-serif-title"><?= View::e(Setting::get('collections_title', 'Coleções & Lançamentos')) ?></h2>
            </div>
            
            <div class="showcase-filter-pills" role="tablist">
                <button class="filter-pill active" data-filter="all"><?= View::e(Setting::get('filter_pill_all', 'Todas as Peças')) ?></button>
                <?php foreach ($availableCategories as $cSlug => $cName): ?>
                    <button class="filter-pill" data-filter="cat-<?= View::e($cSlug) ?>"><?= View::e($cName) ?></button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Grade de Coleções Ativas -->
        <div class="collections-showcase-container">
            <?php foreach ($activeCollections as $cIdx => $col): ?>
                <?php
                    $colCatSlug = !empty($col['category_slug']) ? $col['category_slug'] : 'braceletes';
                    $colProducts = array_filter($col['products'] ?? [], function($p) {
                        return !isset($p['is_active']) || !empty($p['is_active']);
                    });
                ?>
                <div class="collection-block" data-category="cat-<?= View::e($colCatSlug) ?>" id="collection-block-<?= (int)$col['id'] ?>" style="margin-bottom: 55px;">
                    
                    <?php if (count($activeCollections) > 1 || !empty($col['subtitle']) || !empty($col['promo_text'])): ?>
                    <div class="collection-block-header" style="margin-bottom: 24px;">
                        <?php if (!empty($col['badge'])): ?>
                            <span class="badge-tag" style="position: static; display: inline-block; margin-bottom: 8px; background: rgba(167,173,180,0.15); color: var(--gold-accent); border: 1px solid var(--border-light);">
                                <?= View::e($col['badge']) ?>
                            </span>
                        <?php endif; ?>
                        <h3 style="font-family: var(--font-serif); font-size: 1.8rem; color: var(--text-primary); margin: 0 0 6px;">
                            <?= View::e(!empty($col['title']) ? $col['title'] : $col['name']) ?>
                        </h3>
                        <?php if (!empty($col['subtitle'])): ?>
                            <p style="font-size: 0.95rem; color: var(--text-secondary); margin: 0 0 10px; max-width: 820px; line-height: 1.6;">
                                <?= View::e($col['subtitle']) ?>
                            </p>
                        <?php endif; ?>
                        <?php if (!empty($col['promo_text'])): ?>
                            <div style="display: inline-flex; align-items: center; gap: 6px; background: var(--card-bg); border: 1px solid var(--border-light); color: var(--gold-accent); font-size: 0.8rem; font-weight: 600; padding: 4px 14px; border-radius: 20px;">
                                <span>✦</span>
                                <span><?= View::e($col['promo_text']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Grid de Produtos e Foto Editorial de Apoio -->
                    <div class="showcase-grid">
                        <?php if (!empty($colProducts)): ?>
                            <?php foreach ($colProducts as $prod): ?>
                                <?php
                                    $pPriceCents = (int) ($prod['price_cents'] ?? 38000);
                                    $pPriceFormatted = number_format($pPriceCents / 100, 2, ',', '.');
                                    $pInstallment = number_format(($pPriceCents / 6) / 100, 2, ',', '.');
                                    $pPix = number_format(($pPriceCents * 0.95) / 100, 2, ',', '.');
                                    $pImg = !empty($prod['image_url']) ? $prod['image_url'] : '/assets/images/products/bracelete-verde.jpg';
                                    $pBadge = !empty($prod['badge']) ? $prod['badge'] : 'ALTA JOALHERIA';
                                    $pUrl = !empty($prod['url']) ? $prod['url'] : '/loja';
                                ?>
                                <div class="product-card" data-category="cat-<?= View::e($colCatSlug) ?>">
                                    <div class="product-thumb">
                                        <img src="<?= View::e($pImg) ?>" 
                                             alt="<?= View::e($prod['name'] ?? 'Joia Divinium') ?>" 
                                             loading="lazy"
                                             onerror="this.onerror=null; this.src='/assets/images/products/bracelete-verde.jpg';">
                                        <span class="badge-tag"><?= View::e($pBadge) ?></span>
                                    </div>
                                    <div class="product-info">
                                        <h3 class="product-name"><?= View::e($prod['name'] ?? 'Joia Divinium') ?></h3>
                                        <p class="product-variant"><?= View::e($col['name']) ?> • Acabamento em Prata de Lei 925</p>
                                        <div class="product-price-box">
                                            <span class="price">R$ <?= $pPriceFormatted ?></span>
                                            <span class="installment">6x de R$ <?= $pInstallment ?> sem juros | R$ <?= $pPix ?> no Pix</span>
                                        </div>
                                        <div style="display: flex; gap: 8px; margin-top: auto;">
                                            <button type="button" class="btn-add-cart" onclick="addToCart('prod-<?= (int)($prod['id'] ?? 1) ?>', '<?= View::e(addslashes($prod['name'] ?? 'Joia')) ?>', <?= $pPriceCents ?>)">
                                                Adicionar à Sacola
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Card Editorial Lateral da Coleção -->
                        <div class="showcase-lifestyle-card">
                            <div class="lifestyle-media">
                                <img src="<?= View::e(!empty($col['image_url']) ? $col['image_url'] : '/assets/images/products/colecao-completa-espelho.jpg') ?>" 
                                     alt="<?= View::e(!empty($col['image_alt']) ? $col['image_alt'] : $col['name']) ?>" 
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='/assets/images/products/colecao-completa-espelho.jpg';">
                            </div>
                            <div class="lifestyle-caption">
                                <h4><?= View::e(!empty($col['title']) ? $col['title'] : $col['name']) ?></h4>
                                <p><?= View::e(!empty($col['description']) ? $col['description'] : (!empty($col['subtitle']) ? $col['subtitle'] : 'Seis caminhos, seis vibrações. Combine mais de uma cor para expressar as diferentes nuances da sua presença.')) ?></p>
                                
                                <div style="margin-top: 18px; display: flex; flex-direction: column; gap: 10px;">
                                    <a href="<?= View::e(!empty($col['button_url']) ? $col['button_url'] : '/loja') ?>" 
                                       target="<?= View::e($col['button_target'] ?? '_self') ?>"
                                       class="btn-col-cta-lifestyle"
                                       style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: var(--gold-accent, #A7ADB4); color: var(--text-primary, #25272A); border: 1px solid var(--gold-hover, #737B85); padding: 12px 24px; border-radius: 4px; font-weight: 700; font-size: 0.86rem; text-decoration: none; letter-spacing: 0.04em; transition: all 0.25s ease;">
                                        <span><?= View::e(!empty($col['button_text']) ? $col['button_text'] : 'Explorar Coleção') ?></span>
                                        <span style="font-size: 0.9rem;">↗</span>
                                    </a>

                                    <?php if (!empty($col['button_secondary_text']) && !empty($col['button_secondary_url'])): ?>
                                        <a href="<?= View::e($col['button_secondary_url']) ?>" 
                                           target="_blank" 
                                           style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; color: var(--text-secondary); font-size: 0.82rem; text-decoration: none; padding: 4px;">
                                            <span>💬 <?= View::e($col['button_secondary_text']) ?></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Barra de Progresso e Navegação do Slider -->
        <div class="showcase-slider-controls">
            <div class="slider-progress-bar">
                <div class="slider-progress-fill" style="width: 60%;"></div>
            </div>
            <div class="slider-nav-arrows">
                <button class="arrow-btn" aria-label="Anterior">&lsaquo;</button>
                <button class="arrow-btn" aria-label="Próximo">&rsaquo;</button>
            </div>
        </div>

</section>
<?php
$renderSection['collections'] = ob_get_clean();
ob_start();
?>

<!-- ======================================================================= -->
<!-- SEÇÃO 4: EXPERIÊNCIA E SERVIÇOS DE ASSINATURA (SIGNATURE SERVICES) -->
<!-- ======================================================================= -->
<section class="signature-services-section" id="services">
    <div class="container services-inner">
        
        <div class="services-header">
            <h2 class="services-main-title">
                <?= \App\Core\View::e(Setting::get('services_title', 'Experiência de Serviços Exclusivos')) ?>
            </h2>
            <p class="services-subtitle">
                <?= \App\Core\View::e(Setting::get('services_subtitle', 'Mais do que uma joia — um vínculo de bem-estar e sintonia. Cada bracelete Divinium é acompanhado de cuidados minuciosos e suporte sob medida.')) ?>
            </p>
        </div>

        <div class="services-accordion-list">
            
            <!-- Serviço 01 -->
            <div class="service-item">
                <div class="service-num">01</div>
                <div class="service-name"><?= \App\Core\View::e(Setting::get('service_1_title', 'Consultoria de Sintonia Mineral')) ?></div>
                <div class="service-details">
                    <ul>
                        <li><?= \App\Core\View::e(Setting::get('service_1_item_1', 'Ajuda personalizada para descobrir a pedra que melhor conversa com seu momento')) ?></li>
                        <li><?= \App\Core\View::e(Setting::get('service_1_item_2', 'Harmonização de presentes inesquecíveis para pessoas queridas')) ?></li>
                        <li><?= \App\Core\View::e(Setting::get('service_1_item_3', 'Atendimento direto pelo nosso Concierge no WhatsApp')) ?></li>
                    </ul>
                </div>
            </div>

            <!-- Serviço 02 -->
            <div class="service-item">
                <div class="service-num">02</div>
                <div class="service-name"><?= \App\Core\View::e(Setting::get('service_2_title', 'Cuidado Artesanal & Manutenção da Prata')) ?></div>
                <div class="service-details">
                    <ul>
                        <li><?= \App\Core\View::e(Setting::get('service_2_item_1', 'Orientações de limpeza e conservação natural da prata de lei')) ?></li>
                        <li><?= \App\Core\View::e(Setting::get('service_2_item_2', 'Polimento artesanal para renovação periódica do brilho original')) ?></li>
                        <li><?= \App\Core\View::e(Setting::get('service_2_item_3', 'Proteção e impermeabilização da resina mineral')) ?></li>
                    </ul>
                </div>
            </div>

            <!-- Serviço 03 -->
            <div class="service-item">
                <div class="service-num">03</div>
                <div class="service-name"><?= \App\Core\View::e(Setting::get('service_3_title', 'Ajuste de Conforto & Garantia')) ?></div>
                <div class="service-details">
                    <ul>
                        <li><?= \App\Core\View::e(Setting::get('service_3_item_1', 'Aro anatômico maleável que se molda gentilmente ao formato do seu pulso')) ?></li>
                        <li><?= \App\Core\View::e(Setting::get('service_3_item_2', 'Garantia contra defeitos de fabricação e desprendimento de gemas')) ?></li>
                        <li><?= \App\Core\View::e(Setting::get('service_3_item_3', 'Certificado de autenticidade dos minerais naturais')) ?></li>
                    </ul>
                </div>
            </div>

        </div>

        <?php 
            $servicesCtaVisible = Setting::get('services_cta_visible', '1');
            $servicesCtaText = Setting::get('services_cta_text', 'Falar com o Concierge no WhatsApp ↗');
            $servicesCtaUrl = Setting::get('services_cta_url', 'https://wa.me/5511999999999');
        ?>
        <?php if ($servicesCtaVisible === '1' && !empty($servicesCtaText)): ?>
        <div class="services-action" style="margin-top: 50px; text-align: center;">
            <a href="<?= \App\Core\View::e($servicesCtaUrl) ?>" class="btn-services-cta" target="_blank" rel="noopener noreferrer">
                <?= \App\Core\View::e($servicesCtaText) ?>
            </a>
        </div>
        <?php endif; ?>

    </div>
</section>
<?php
$renderSection['services'] = ob_get_clean();
ob_start();
?>

<!-- ======================================================================= -->
<!-- SEÇÃO 5: DESDE 2026, CADA PEÇA É ÚNICA -->
<!-- ======================================================================= -->
<section class="heritage-editorial-section">
    <div class="container heritage-grid">
        <div class="heritage-media">
            <div class="media-frame-shadow">
                <img src="<?= \App\Core\View::e(Setting::get('heritage_banner_img', '/assets/images/about/banner-unica.jpg')) ?>" 
                     alt="Cada Peça é Única Divinium" 
                     loading="lazy"
                     onerror="this.onerror=null; this.src='/assets/images/about/banner-unica.png';">
            </div>
        </div>
        <div class="heritage-text">
            <span class="sub-label"><?= \App\Core\View::e(Setting::get('heritage_tag', 'SOFISTICAÇÃO & NATUREZA')) ?></span>
            <h2 class="section-serif-title">
                <?= \App\Core\View::e(Setting::get('heritage_title', 'Cada Peça é Única')) ?>
            </h2>
            <p>
                <?= \App\Core\View::e(Setting::get('heritage_p1', 'A natureza não se repete, e nós respeitamos esse princípio. Quando você adquire um bracelete Divinium, tem a certeza de que nenhuma outra pessoa no mundo possui um exemplar idêntico ao seu.')) ?>
            </p>
            <p>
                <?= \App\Core\View::e(Setting::get('heritage_p2', 'O aro duplo oferece estabilidade elegante e leveza incomparável no antebraço, transitando com naturalidade do visual casual diário a eventos de gala.')) ?>
            </p>
            <div class="heritage-action">
                <a href="<?= \App\Core\View::e(Setting::get('heritage_cta_url', '#collections')) ?>" class="btn-outline-dark"><?= \App\Core\View::e(Setting::get('heritage_cta_text', 'Escolher Meu Bracelete')) ?></a>
            </div>
        </div>
    </div>
</section>
<?php
$renderSection['heritage'] = ob_get_clean();
ob_start();
?>

<!-- ======================================================================= -->
<!-- SEÇÃO 6: PROVA SOCIAL & DEPOIMENTOS (LOVED BY THOSE WHO APPRECIATE) -->
<!-- ======================================================================= -->
<section class="testimonials-section" id="testimonials">
    <div class="container">
        
        <div class="testimonials-header">
            <h2 class="section-serif-title">
                <?= \App\Core\View::e(Setting::get('testimonials_title', 'Amado por Quem Valoriza a Beleza Atemporal')) ?>
            </h2>
        </div>

        <?php
            $defaultTestimonials = [
                [
                    'id' => 1,
                    'stars' => Setting::get('testimonial_stars', '★★★★★'),
                    'quote' => Setting::get('testimonial_quote', 'Quando coloquei o bracelete no pulso, senti imediatamente a presença e a qualidade do acabamento. A pedra tem uma profundidade de cor inacreditável. Todo mundo elogia e pergunta de onde é!'),
                    'author' => Setting::get('testimonial_author', '[ Marcela R. • São Paulo/SP • Cliente Verificada ]'),
                    'backdrop' => Setting::get('testimonial_backdrop_img', '/assets/images/about/model-wrist-sunglasses.jpg'),
                    'is_active' => 1
                ],
                [
                    'id' => 2,
                    'stars' => Setting::get('testimonial_2_stars', '★★★★★'),
                    'quote' => Setting::get('testimonial_2_quote', 'O peso da prata 925 maciça e o encaixe anatômico do aro duplo são impecáveis. Uso diariamente há meses e a gema preserva o mesmo magnetismo mineral do primeiro dia. É uma verdadeira joia autoral.'),
                    'author' => Setting::get('testimonial_2_author', '[ Helena Castro • Rio de Janeiro/RJ • Colecionadora Divinium ]'),
                    'backdrop' => Setting::get('testimonial_2_backdrop_img', '/assets/images/about/banner-unica.jpg'),
                    'is_active' => 1
                ],
                [
                    'id' => 3,
                    'stars' => Setting::get('testimonial_3_stars', '★★★★★'),
                    'quote' => Setting::get('testimonial_3_quote', 'Presenteei minha esposa com o bracelete em Madrepérola e ela ficou maravilhada. O cuidado da embalagem com fita, o certificado mineral e a atenção do Concierge no WhatsApp elevaram a experiência a outro patamar.'),
                    'author' => Setting::get('testimonial_3_author', '[ Dr. Eduardo Valença • Belo Horizonte/MG • Compra Verificada ]'),
                    'backdrop' => Setting::get('testimonial_3_backdrop_img', '/assets/images/about/banner-caminhos.jpg'),
                    'is_active' => 1
                ],
                [
                    'id' => 4,
                    'stars' => Setting::get('testimonial_4_stars', '★★★★★'),
                    'quote' => Setting::get('testimonial_4_quote', 'O minimalismo escultural do aro duplo com a resina mineral verde esmeralda tem uma aura única. Transita do ambiente executivo a eventos de gala com elegância sem esforço. Simplesmente magnífico.'),
                    'author' => Setting::get('testimonial_4_author', '[ Beatriz Mendonça • Curitiba/PR • Arquiteta & Designer ]'),
                    'backdrop' => Setting::get('testimonial_4_backdrop_img', '/assets/images/about/macro-texture.jpg'),
                    'is_active' => 1
                ]
            ];

            $testimonialsSlidesJson = Setting::get('testimonials_slides');
            $testimonialsList = [];
            if (!empty($testimonialsSlidesJson)) {
                $decoded = json_decode($testimonialsSlidesJson, true);
                if (is_array($decoded) && !empty($decoded)) {
                    $testimonialsList = $decoded;
                }
            }
            if (empty($testimonialsList)) {
                $testimonialsList = $defaultTestimonials;
            }
            $activeTestimonials = array_values(array_filter($testimonialsList, fn($t) => ($t['is_active'] ?? 1) == 1));
            if (empty($activeTestimonials)) {
                $activeTestimonials = array_values($defaultTestimonials);
            }

            $testimonialsAutoplay = Setting::get('testimonials_carousel_autoplay', '1');
            $testimonialsInterval = (int) Setting::get('testimonials_carousel_interval', '6');
            if ($testimonialsInterval < 2) $testimonialsInterval = 6;
        ?>

        <!-- CARROSSEL DE DEPOIMENTOS -->
        <div class="testimonials-carousel-wrapper" id="testimonialsCarousel"
             data-autoplay="<?= $testimonialsAutoplay ?>"
             data-interval="<?= $testimonialsInterval * 1000 ?>"
             role="region"
             aria-roledescription="carousel"
             aria-label="Carrossel de Depoimentos dos Clientes">

            <div class="testimonials-carousel-viewport">
                <div class="testimonials-carousel-track" id="testimonialsCarouselTrack">
                    <?php foreach ($activeTestimonials as $idx => $item): 
                        $stars = $item['stars'] ?? '★★★★★';
                        $quote = $item['quote'] ?? '';
                        $author = $item['author'] ?? '';
                        $backdrop = $item['backdrop'] ?? '/assets/images/about/model-wrist-sunglasses.jpg';
                    ?>
                    <div class="testimonial-slide <?= $idx === 0 ? 'active' : '' ?>" data-index="<?= $idx ?>">
                        <div class="testimonial-showcase-box">
                            <div class="testimonial-backdrop">
                                <img src="<?= \App\Core\View::e($backdrop) ?>" 
                                     alt="Depoimento <?= $idx + 1 ?> Divinium"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='/assets/images/about/model-wrist-sunglasses.png';">
                            </div>
                            
                            <div class="testimonial-quote-card">
                                <div class="quote-stars"><?= \App\Core\View::e($stars) ?></div>
                                <blockquote class="quote-text">
                                    "<?= \App\Core\View::e($quote) ?>"
                                </blockquote>
                                <cite class="quote-author"><?= \App\Core\View::e($author) ?></cite>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Setas de Navegação -->
            <button type="button" class="testimonials-arrow testimonials-prev" id="testimonialsCarouselPrev" aria-label="Depoimento anterior" title="Depoimento anterior">
                <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            <button type="button" class="testimonials-arrow testimonials-next" id="testimonialsCarouselNext" aria-label="Próximo depoimento" title="Próximo depoimento">
                <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>

            <!-- Indicadores (Dots) -->
            <div class="testimonials-carousel-dots" id="testimonialsCarouselDots">
                <?php foreach ($activeTestimonials as $idx => $item): ?>
                <button type="button" 
                        class="testimonials-carousel-dot <?= $idx === 0 ? 'active' : '' ?>" 
                        data-index="<?= $idx ?>" 
                        aria-label="Ir para depoimento <?= $idx + 1 ?>"></button>
                <?php endforeach; ?>
            </div>

        </div>

        <!-- Miniaturas Reais das Peças e Detalhes -->
        <div class="client-gallery-strip">
            <div class="client-thumb">
                <img src="<?= \App\Core\View::e(Setting::get('testimonial_thumb_1', '/assets/images/products/bracelete-verde.jpg')) ?>" 
                     alt="Bracelete Verde"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='/assets/images/products/bracelete-verde.jpg';">
            </div>
            <div class="client-thumb">
                <img src="<?= \App\Core\View::e(Setting::get('testimonial_thumb_2', '/assets/images/products/bracelete-madreperola.jpg')) ?>" 
                     alt="Bracelete Madrepérola"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='/assets/images/products/bracelete-madreperola.jpg';">
            </div>
            <div class="client-thumb">
                <img src="<?= \App\Core\View::e(Setting::get('testimonial_thumb_3', '/assets/images/products/bracelete-vermelho.jpg')) ?>" 
                     alt="Bracelete Vermelho"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='/assets/images/products/bracelete-vermelho.jpg';">
            </div>
            <div class="client-thumb">
                <img src="<?= \App\Core\View::e(Setting::get('testimonial_thumb_4', '/assets/images/products/bracelete-onix.jpg')) ?>" 
                     alt="Bracelete Ônix"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='/assets/images/products/bracelete-onix.jpg';">
            </div>
            <div class="client-thumb">
                <img src="<?= \App\Core\View::e(Setting::get('testimonial_thumb_5', '/assets/images/about/macro-texture.jpg')) ?>" 
                     alt="Macro da Gema"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='/assets/images/about/macro-texture.jpg';">
            </div>
        </div>

    </div>
</section>
<?php
$renderSection['testimonials'] = ob_get_clean();

// Renderização dinâmica conforme Ordem e Visibilidade configuradas no Painel Administrativo
$defaultOrder = 'hero,about,collections,services,heritage,testimonials';
$orderRaw = Setting::get('home_sections_order', $defaultOrder);
$orderRaw = str_replace('stage', 'collections', $orderRaw ?: $defaultOrder);
$sections = array_filter(array_map('trim', explode(',', $orderRaw)));

foreach ($sections as $secKey) {
    if (isset($renderSection[$secKey]) && Setting::get('home_' . $secKey . '_visible', '1') === '1') {
        echo $renderSection[$secKey];
    }
}
?>
