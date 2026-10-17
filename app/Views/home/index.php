<?php
/**
 * VIEW: PÁGINA INICIAL DIVINIUM - COLEÇÃO CAMINHOS (QUIET LUXURY)
 * Estruturada com as imagens e mensagens dinâmicas da marca.
 */
use App\Models\Setting;
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
                <?= \App\Core\View::e(Setting::get('hero_title_line1', 'Joias que Revelam')) ?><br>
                <span class="gold-gradient-glow"><?= \App\Core\View::e(Setting::get('hero_title_highlight', 'o Luxo Silencioso')) ?></span>
            </h1>
            
            <p class="hero-subtitle">
                <?= \App\Core\View::e(Setting::get('hero_subtitle', 'A nobreza da prata de lei 925 entrelaçada à vibração autêntica de gemas minerais raras. Braceletes CAMINHOS: um mesmo design, diferentes formas de expressar a sua presença.')) ?>
            </p>

            <!-- Seletor Interativo de Gemas (Paleta Mineral Sexy) -->
            <div class="hero-mineral-box">
                <span class="mineral-box-label">Escolha sua sintonia mineral:</span>
                <div class="mineral-pills-row" role="radiogroup" aria-label="Selecione o mineral">
                    <button class="mineral-pill active" 
                            data-stone="verde" 
                            data-title="Bracelete Caminhos • Quartzo Verde" 
                            data-desc="Prata nobre e quartzo verde natural em concha mineral." 
                            data-price="R$ 380,00" 
                            data-pix="ou R$ 361,00 no Pix (5% OFF)" 
                            data-img="/assets/images/hero/featured-green-shell.jpg" 
                            data-id="cam-verde"
                            data-cents="38000">
                        <span class="mineral-dot" style="background: radial-gradient(circle, #3d865b, #153f28);"></span>
                        <span class="mineral-name">Quartzo Verde</span>
                    </button>
                    
                    <button class="mineral-pill" 
                            data-stone="madreperola" 
                            data-title="Bracelete Caminhos • Madrepérola" 
                            data-desc="Prata nobre e nácar natural de madrepérola com brilho perolado." 
                            data-price="R$ 380,00" 
                            data-pix="ou R$ 361,00 no Pix (5% OFF)" 
                            data-img="/assets/images/products/bracelete-madreperola.jpg" 
                            data-id="cam-madre"
                            data-cents="38000">
                        <span class="mineral-dot" style="background: radial-gradient(circle, #fcf8ee, #dfcca2);"></span>
                        <span class="mineral-name">Madrepérola</span>
                    </button>
                    
                    <button class="mineral-pill" 
                            data-stone="vermelho" 
                            data-title="Bracelete Caminhos • Jaspe Vermelho" 
                            data-desc="Prata nobre e jaspe vermelho mineral de presença intensa e terrosa." 
                            data-price="R$ 380,00" 
                            data-pix="ou R$ 361,00 no Pix (5% OFF)" 
                            data-img="/assets/images/products/bracelete-vermelho.jpg" 
                            data-id="cam-verm"
                            data-cents="38000">
                        <span class="mineral-dot" style="background: radial-gradient(circle, #c53030, #631212);"></span>
                        <span class="mineral-name">Jaspe Vermelho</span>
                    </button>
                    
                    <button class="mineral-pill" 
                            data-stone="onix" 
                            data-title="Bracelete Caminhos • Ônix Negro" 
                            data-desc="Prata nobre e ônix negro de acabamento mineral espelhado." 
                            data-price="R$ 380,00" 
                            data-pix="ou R$ 361,00 no Pix (5% OFF)" 
                            data-img="/assets/images/products/bracelete-onix.jpg" 
                            data-id="cam-onix"
                            data-cents="38000">
                        <span class="mineral-dot" style="background: radial-gradient(circle, #2b2b2b, #0c0c0c);"></span>
                        <span class="mineral-name">Ônix Negro</span>
                    </button>
                </div>
            </div>

            <!-- Grupo de Ações Principais (Botões de Alta Conversão) -->
            <div class="hero-cta-group">
                <a href="<?= \App\Core\View::e(Setting::get('hero_cta_primary_link', '/loja')) ?>" class="btn-hero-gold">
                    <span><?= \App\Core\View::e(Setting::get('hero_cta_primary_text', 'Explorar Coleção Completa')) ?></span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
                <a href="https://wa.me/<?= \App\Core\View::e(Setting::get('hero_whatsapp_number', '5511999999999')) ?>?text=Ol%C3%A1,%20gostaria%20de%20um%20atendimento%20personalizado%20sobre%20as%20joias%20Divinium" target="_blank" rel="noopener" class="btn-hero-ghost">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    <span><?= \App\Core\View::e(Setting::get('hero_cta_whatsapp_text', 'Concierge WhatsApp')) ?></span>
                </a>
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

        <!-- Coluna Direita: O PALCO DE DESTAQUE DO PRODUTO (HERO SHOWCASE STAGE) -->
        <div class="hero-stage">
            <div class="stage-frame">
                <!-- Efeito Iluminação Radial de Fundo -->
                <div class="stage-halo"></div>

                <!-- Detalhes de Canto Ornamentais (Alta Joalheria) -->
                <div class="stage-corner corner-tl"></div>
                <div class="stage-corner corner-tr"></div>
                <div class="stage-corner corner-bl"></div>
                <div class="stage-corner corner-br"></div>

                <!-- Tag Superior Flutuante -->
                <div class="stage-top-pill">
                    <span class="pulse-indicator"></span>
                    <span><?= \App\Core\View::e(Setting::get('stage_top_tag', 'PEÇA EM DESTAQUE')) ?></span>
                </div>

                <!-- Imagem do Produto em Destaque Absoluto (Grande e Nítida) -->
                <div class="stage-media-wrap">
                    <img id="heroSpotlightImg" 
                         src="<?= \App\Core\View::e(Setting::get('stage_product_image', '/assets/images/hero/featured-green-shell.jpg')) ?>" 
                         alt="Bracelete Caminhos Quartzo Verde"
                         class="stage-main-img"
                         loading="eager"
                         onerror="this.onerror=null; this.src='/assets/images/hero/featured-green-shell.jpg';">
                </div>

                <!-- Card Flutuante de Compra Imediata (Glassmorphism Sexy) -->
                <div class="stage-glass-card">
                    <div class="glass-info">
                        <span class="glass-eyebrow" id="heroSpotlightTag"><?= \App\Core\View::e(Setting::get('stage_card_eyebrow', 'EDIÇÃO AUTORAL DIVINIUM')) ?></span>
                        <h3 class="glass-title" id="heroSpotlightTitle"><?= \App\Core\View::e(Setting::get('stage_product_title', 'Bracelete Caminhos • Verde')) ?></h3>
                        <p class="glass-desc" id="heroSpotlightDesc"><?= \App\Core\View::e(Setting::get('stage_product_desc', 'Prata nobre e quartzo verde natural em concha mineral.')) ?></p>
                        
                        <div class="glass-pricing">
                            <span class="price-main" id="heroSpotlightPrice"><?= \App\Core\View::e(Setting::get('stage_product_price', 'R$ 380,00')) ?></span>
                            <span class="price-pix" id="heroSpotlightPix"><?= \App\Core\View::e(Setting::get('stage_product_pix', 'ou R$ 361,00 no Pix (5% OFF)')) ?></span>
                        </div>
                    </div>

                    <button class="stage-buy-btn" id="heroQuickBuyBtn" onclick="addToCart('cam-verde', 'Bracelete Caminhos Quartzo Verde', 38000)" aria-label="Adicionar à sacola">
                        <span>Garantir</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                </div>

            </div>
        </div>

    </div>
</section>

<!-- ======================================================================= -->
<!-- SEÇÃO 2: MANIFESTO & HISTÓRIA (CRAFTED WITH PURPOSE) -->
<!-- ======================================================================= -->
<section class="about-manifesto-section" id="about">
    <div class="container about-grid">
        
        <!-- Coluna de Texto e Manifesto -->
        <div class="about-text-column">
            <span class="sub-label"><?= \App\Core\View::e(Setting::get('about_tag', 'MANIFESTO DIVINIUM')) ?></span>
            <h2 class="section-serif-title">
                <?= \App\Core\View::e(Setting::get('about_title', 'A Beleza Não Precisa Gritar Para Ser Notada')) ?>
            </h2>
            <div class="about-story-text">
                <p>
                    <?= \App\Core\View::e(Setting::get('about_p1', 'A DIVINIUM nasceu da convicção de que o verdadeiro luxo é autoral, sutil e profundamente conectado à sua identidade. Em um mercado de réplicas e produções em massa, escolhemos a rota da nobreza mineral.')) ?>
                </p>
                <p>
                    <?= \App\Core\View::e(Setting::get('about_p2', 'Cada gema é única em suas veias, coloração e densidade. Encastoadas à mão sobre o aro duplo em prata, as pedras ganham vida e presença táctil sem jamais pesar no pulso.')) ?>
                </p>
                <p style="font-family: var(--font-serif); font-size: 1.35rem; color: var(--text-secondary, #6B665F); font-style: italic;">
                    "Qual delas conversa com você hoje?"
                </p>
            </div>
            
            <div class="about-cta">
                <a href="#services" class="btn-outline-dark">Conheça Nossa História</a>
            </div>

            <!-- Miniatura com macro da gema -->
            <div class="about-micro-feature">
                <div class="micro-thumb">
                    <img src="/assets/images/about/macro-texture.jpg" 
                         alt="Textura Mineral Orgânica" 
                         loading="lazy"
                         onerror="this.onerror=null; this.src='/assets/images/about/macro-texture.png';">
                </div>
                <div class="micro-info">
                    <strong>Textura Mineral Autêntica</strong>
                    <span>Fragmentos minerais nobres preservados com resina de alta transparência.</span>
                </div>
            </div>
        </div>

        <!-- Coluna de Imagem Editorial em Destaque -->
        <div class="about-image-column">
            <div class="editorial-frame">
                <img src="/assets/images/about/banner-caminhos.jpg" 
                     alt="Coleção Braceletes Caminhos Divinium" 
                     class="editorial-img" 
                     loading="lazy"
                     onerror="this.onerror=null; this.src='/assets/images/about/banner-caminhos.png';">
                <div class="editorial-caption">
                    <span>COLEÇÃO CAMINHOS • UM MESMO DESIGN, DIFERENTES FORMAS DE SENTIR</span>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ======================================================================= -->
<!-- SEÇÃO 3: VITRINE DE PRODUTOS & SLIDER (SIGNATURE SHOWCASE) -->
<!-- ======================================================================= -->
<section class="showcase-section" id="collections">
    <div class="container">
        
        <!-- Cabeçalho da Vitrine com Filtros em Pílula -->
        <div class="showcase-header">
            <div class="showcase-titles">
                <span class="sub-label">SELEÇÃO EXCLUSIVA</span>
                <h2 class="section-serif-title">Braceletes CAMINHOS</h2>
            </div>
            
            <div class="showcase-filter-pills" role="tablist">
                <button class="filter-pill active" data-filter="all">Todas as Peças</button>
                <button class="filter-pill" data-filter="verde">Quartzo Verde</button>
                <button class="filter-pill" data-filter="madreperola">Madrepérola</button>
                <button class="filter-pill" data-filter="vermelho">Jaspe Vermelho</button>
                <button class="filter-pill" data-filter="onix">Ônix Negro</button>
            </div>
        </div>

        <!-- Grid de Produtos e Foto Editorial de Apoio -->
        <div class="showcase-grid">
            
            <!-- Produto 1: Quartzo Verde -->
            <div class="product-card" data-category="verde">
                <div class="product-thumb">
                    <img src="/assets/images/products/bracelete-verde.jpg" 
                         alt="Bracelete Caminhos Quartzo Verde" 
                         loading="lazy"
                         onerror="this.onerror=null; this.src='/assets/images/products/bracelete-verde.png';">
                    <span class="badge-tag">PRATA & QUARTZO VERDE</span>
                </div>
                <div class="product-info">
                    <h3 class="product-name">Bracelete Caminhos • Verde</h3>
                    <p class="product-variant">Esperança, Equilíbrio & Cura • Aro Duplo em Prata</p>
                    <div class="product-price-box">
                        <span class="price">R$ 380,00</span>
                        <span class="installment">6x de R$ 63,33 sem juros | R$ 361,00 no Pix</span>
                    </div>
                    <button class="btn-add-cart" onclick="addToCart('cam-verde', 'Bracelete Caminhos Quartzo Verde', 38000)">
                        Adicionar à Sacola
                    </button>
                </div>
            </div>

            <!-- Produto 2: Madrepérola & Nácar -->
            <div class="product-card" data-category="madreperola">
                <div class="product-thumb">
                    <img src="/assets/images/products/bracelete-madreperola.jpg" 
                         alt="Bracelete Caminhos Madrepérola" 
                         loading="lazy"
                         onerror="this.onerror=null; this.src='/assets/images/products/bracelete-madreperola.jpeg';">
                    <span class="badge-tag">PRATA & MADREPÉROLA</span>
                </div>
                <div class="product-info">
                    <h3 class="product-name">Bracelete Caminhos • Madrepérola</h3>
                    <p class="product-variant">Serenidade, Pureza & Luz • Brilho Nacarado Natural</p>
                    <div class="product-price-box">
                        <span class="price">R$ 380,00</span>
                        <span class="installment">6x de R$ 63,33 sem juros | R$ 361,00 no Pix</span>
                    </div>
                    <button class="btn-add-cart" onclick="addToCart('cam-madre', 'Bracelete Caminhos Madrepérola', 38000)">
                        Adicionar à Sacola
                    </button>
                </div>
            </div>

            <!-- Produto 3: Jaspe Vermelho -->
            <div class="product-card" data-category="vermelho">
                <div class="product-thumb">
                    <img src="/assets/images/products/bracelete-vermelho.jpg" 
                         alt="Bracelete Caminhos Jaspe Vermelho" 
                         loading="lazy"
                         onerror="this.onerror=null; this.src='/assets/images/products/bracelete-vermelho.jpg';">
                    <span class="badge-tag">PRATA & JASPE VERMELHO</span>
                </div>
                <div class="product-info">
                    <h3 class="product-name">Bracelete Caminhos • Vermelho</h3>
                    <p class="product-variant">Força, Vitalidade & Paixão • Acabamento em Couro Nobre</p>
                    <div class="product-price-box">
                        <span class="price">R$ 380,00</span>
                        <span class="installment">6x de R$ 63,33 sem juros | R$ 361,00 no Pix</span>
                    </div>
                    <button class="btn-add-cart" onclick="addToCart('cam-verm', 'Bracelete Caminhos Jaspe Vermelho', 38000)">
                        Adicionar à Sacola
                    </button>
                </div>
            </div>

            <!-- Card Editorial Lateral: Coleção Completa no Espelho -->
            <div class="showcase-lifestyle-card">
                <div class="lifestyle-media">
                    <img src="/assets/images/products/colecao-completa-espelho.jpg" 
                         alt="Composição Completa Braceletes Caminhos" 
                         loading="lazy"
                         onerror="this.onerror=null; this.src='/assets/images/products/colecao-completa-espelho.png';">
                </div>
                <div class="lifestyle-caption">
                    <h4>Harmonia Mineral no Espelho</h4>
                    <p>Seis caminhos, seis vibrações. Combine mais de uma cor para expressar as diferentes nuances da sua presença.</p>
                </div>
            </div>

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

    </div>
</section>

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
                <div class="service-name">Consultoria de Sintonia Mineral</div>
                <div class="service-details">
                    <ul>
                        <li>Ajuda personalizada para descobrir a pedra que melhor conversa com seu momento</li>
                        <li>Harmonização de presentes inesquecíveis para pessoas queridas</li>
                        <li>Atendimento direto pelo nosso Concierge no WhatsApp</li>
                    </ul>
                </div>
            </div>

            <!-- Serviço 02 -->
            <div class="service-item">
                <div class="service-num">02</div>
                <div class="service-name">Cuidado Artesanal & Manutenção da Prata</div>
                <div class="service-details">
                    <ul>
                        <li>Orientações de limpeza e conservação natural da prata de lei</li>
                        <li>Polimento artesanal para renovação periódica do brilho original</li>
                        <li>Proteção e impermeabilização da resina mineral</li>
                    </ul>
                </div>
            </div>

            <!-- Serviço 03 -->
            <div class="service-item">
                <div class="service-num">03</div>
                <div class="service-name">Ajuste de Conforto & Garantia</div>
                <div class="service-details">
                    <ul>
                        <li>Aro anatômico maleável que se molda gentilmente ao formato do seu pulso</li>
                        <li>Garantia contra defeitos de fabricação e desprendimento de gemas</li>
                        <li>Certificado de autenticidade dos minerais naturais</li>
                    </ul>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ======================================================================= -->
<!-- SEÇÃO 5: DESDE 2026, CADA PEÇA É ÚNICA -->
<!-- ======================================================================= -->
<section class="heritage-editorial-section">
    <div class="container heritage-grid">
        <div class="heritage-media">
            <div class="media-frame-shadow">
                <img src="/assets/images/about/banner-unica.jpg" 
                     alt="Cada Peça é Única Divinium" 
                     loading="lazy"
                     onerror="this.onerror=null; this.src='/assets/images/about/banner-unica.png';">
            </div>
        </div>
        <div class="heritage-text">
            <span class="sub-label">SOFISTICAÇÃO & NATUREZA</span>
            <h2 class="section-serif-title">
                Cada Peça é Única
            </h2>
            <p>
                A natureza não se repete, e nós respeitamos esse princípio. Quando você adquire um bracelete Divinium, tem a certeza de que nenhuma outra pessoa no mundo possui um exemplar idêntico ao seu.
            </p>
            <p>
                O aro duplo oferece estabilidade elegante e leveza incomparável no antebraço, transitando com naturalidade do visual casual diário a eventos de gala.
            </p>
            <div class="heritage-action">
                <a href="#collections" class="btn-outline-dark">Escolher Meu Bracelete</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- SEÇÃO 6: PROVA SOCIAL & DEPOIMENTOS (LOVED BY THOSE WHO APPRECIATE) -->
<!-- ======================================================================= -->
<section class="testimonials-section">
    <div class="container">
        
        <div class="testimonials-header">
            <h2 class="section-serif-title">
                Amado por Quem Valoriza a Beleza Atemporal
            </h2>
        </div>

        <div class="testimonial-showcase-box">
            <div class="testimonial-backdrop">
                <img src="/assets/images/about/model-wrist-sunglasses.jpg" 
                     alt="Modelo usando Bracelete Divinium"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='/assets/images/about/model-wrist-sunglasses.png';">
            </div>
            
            <div class="testimonial-quote-card">
                <div class="quote-stars">★★★★★</div>
                <blockquote class="quote-text">
                    "Quando coloquei o bracelete no pulso, senti imediatamente a presença e a qualidade do acabamento. A pedra tem uma profundidade de cor inacreditável. Todo mundo elogia e pergunta de onde é!"
                </blockquote>
                <cite class="quote-author">[ Marcela R. • São Paulo/SP • Cliente Verificada ]</cite>
            </div>
        </div>

        <!-- Miniaturas Reais das Peças e Detalhes -->
        <div class="client-gallery-strip">
            <div class="client-thumb">
                <img src="/assets/images/products/bracelete-verde.jpg" 
                     alt="Bracelete Verde"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='/assets/images/products/bracelete-verde.png';">
            </div>
            <div class="client-thumb">
                <img src="/assets/images/products/bracelete-madreperola.jpg" 
                     alt="Bracelete Madrepérola"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='/assets/images/products/bracelete-madreperola.jpeg';">
            </div>
            <div class="client-thumb">
                <img src="/assets/images/products/bracelete-vermelho.jpg" 
                     alt="Bracelete Vermelho"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='/assets/images/products/bracelete-vermelho.jpg';">
            </div>
            <div class="client-thumb">
                <img src="/assets/images/products/bracelete-onix.jpg" 
                     alt="Bracelete Ônix"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='/assets/images/products/bracelete-onix.jpeg';">
            </div>
            <div class="client-thumb">
                <img src="/assets/images/about/macro-texture.jpg" 
                     alt="Macro da Gema"
                     loading="lazy"
                     onerror="this.onerror=null; this.src='/assets/images/about/macro-texture.png';">
            </div>
        </div>

    </div>
</section>
