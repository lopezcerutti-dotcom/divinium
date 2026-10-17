<?php
/**
 * VIEW: LOJA OFICIAL DIVINIUM (VITRINE DE BIOJOIAS & ORGONITES)
 * Catálogo completo com 100 produtos cadastrados e checkout garantido Shopee.
 */
?>

<!-- 1. HERO HEADER DA BOUTIQUE -->
<section class="store-hero-section">
    <div class="store-hero-glow"></div>
    <div class="container store-hero-container">
        
        <div class="store-hero-badge">
            <span class="badge-sparkle">✦</span>
            <span>CATÁLOGO OFICIAL • BIOJOIAS & ORGONITES AUTORAIS</span>
        </div>

        <h1 class="store-hero-title">
            Biojoias, Orgonites & <span class="gold-gradient-glow">Sintonia Mineral</span>
        </h1>

        <p class="store-hero-subtitle">
            Cada peça Divinium une resina nobre, metais condutores e pedras naturais selecionadas. 
            Explore nosso acervo completo com <strong>100 biojoias exclusivas</strong> agrupadas em Anéis, Braceletes e Brincos. 
            Finalize sua compra com segurança, cupons e rastreamento na nossa <strong>Loja Oficial na Shopee</strong>.
        </p>

        <!-- Faixa de Garantia & Confiança Shopee -->
        <div class="store-trust-badges">
            <div class="trust-badge-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                <span>Garantia Shopee • Compra 100% Protegida</span>
            </div>
            <div class="trust-badge-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                <span>Biojoias e Pedras 100% Naturais</span>
            </div>
            <div class="trust-badge-item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                <span>Envio Rápido e Seguro para todo o Brasil</span>
            </div>
        </div>

    </div>
</section>

<!-- 2. CATÁLOGO DE PRODUTOS -->
<section class="store-catalog-section" id="catalogo">
    <div class="container">
        
        <!-- Campo de Busca em Tempo Real -->
        <div class="store-search-container">
            <svg class="store-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" 
                   id="storeSearchInput" 
                   class="store-search-input" 
                   placeholder="Buscar por gema (ex: Amazonita, Ametista, Ônix, Madrepérola, Coral), SKU ou modelo..." 
                   oninput="handleStoreSearch(this.value)">
        </div>

        <!-- Barra de Filtros de Categorias -->
        <div class="store-filter-bar">
            <div class="filter-pills-wrap">
                <button class="store-filter-pill active" data-filter="all" onclick="filterStore('all', this)">
                    <span>Todas as Peças</span>
                    <span class="count-pill"><?= count($products ?? []) ?></span>
                </button>
                
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <?php 
                            $catCount = isset($cat['product_count']) 
                                ? (int)$cat['product_count'] 
                                : count(array_filter($products ?? [], fn($p) => (int)($p['category_id'] ?? 0) === (int)$cat['id']));
                        ?>
                        <button class="store-filter-pill" data-filter="cat-<?= $cat['id'] ?>" onclick="filterStore('cat-<?= $cat['id'] ?>', this)">
                            <span><?= \App\Core\View::e($cat['name']) ?></span>
                            <span class="count-pill"><?= $catCount ?></span>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="store-info-pill">
                <span class="shopee-dot"></span>
                <span>100 Peças Cadastradas • Clique para comprar na Shopee</span>
            </div>
        </div>

        <!-- Grade de Produtos -->
        <?php if (!empty($products)): ?>
            <div class="store-products-grid" id="storeGrid">
                <?php foreach ($products as $prod): ?>
                    <?php 
                        $targetUrl = !empty($prod['external_url']) 
                            ? $prod['external_url'] 
                            : 'https://shopee.com.br/search?keyword=' . urlencode('Divinium ' . ($prod['name'] ?? 'biojoia'));
                        $priceCents = (int) ($prod['price_cents'] ?? 38000);
                        $priceFormatted = number_format($priceCents / 100, 2, ',', '.');
                        $pixPriceFormatted = number_format(($priceCents * 0.95) / 100, 2, ',', '.');
                        $filterClass = 'cat-' . ($prod['category_id'] ?? 'none');
                        $mineralName = !empty($prod['stone_mineral']) ? $prod['stone_mineral'] : 'Orgonite Autoral';
                        $stockQty = (int) ($prod['stock_quantity'] ?? 0);
                        $hasStock = $stockQty > 0;
                        $skuCode = $prod['sku'] ?? 'DIV';
                        $catName = $prod['category_name'] ?? 'Biojoia';
                    ?>
                    
                    <article class="store-product-card" 
                             data-category="<?= $filterClass ?>" 
                             data-search="<?= strtolower(\App\Core\View::e($prod['name'] . ' ' . $skuCode . ' ' . $mineralName . ' ' . $catName)) ?>"
                             data-track-product-id="<?= $prod['id'] ?>" 
                             data-track-product-name="<?= \App\Core\View::e($prod['name']) ?>">
                        
                        <!-- Imagem do Produto com Link Direto para a Shopee -->
                        <a href="<?= \App\Core\View::e($targetUrl) ?>" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="store-card-media"
                           data-track-product-id="<?= $prod['id'] ?>"
                           data-track-product-name="<?= \App\Core\View::e($prod['name']) ?>"
                           data-track-source="store_media"
                           title="Ver <?= \App\Core\View::e($prod['name']) ?> na Shopee">
                            
                            <img src="<?= \App\Core\View::e($prod['image_url'] ?: '/assets/images/hero/featured-green-shell.jpg') ?>" 
                                 alt="<?= \App\Core\View::e($prod['name']) ?>"
                                 loading="lazy"
                                 class="store-card-img"
                                 onerror="this.onerror=null; this.src='/assets/images/hero/featured-green-shell.jpg';">
                            
                            <!-- Badges Flutuantes -->
                            <div class="card-badges-top">
                                <span class="badge-sku"><?= \App\Core\View::e($skuCode) ?></span>
                                <span class="badge-shopee-tag">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h2v2c0 .55.45 1 1 1s1-.45 1-1V8h6v2c0 .55.45 1 1 1s1-.45 1-1V8h2v12z"/></svg>
                                    Shopee Oficial ↗
                                </span>
                            </div>

                            <div class="card-hover-overlay">
                                <span class="overlay-cta-text">
                                    <span>Comprar na Shopee</span>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                </span>
                            </div>
                        </a>

                        <!-- Informações do Produto -->
                        <div class="store-card-body">
                            
                            <!-- Categoria e Status de Estoque -->
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <span class="badge-metal"><?= \App\Core\View::e($catName) ?></span>
                                <?php if ($hasStock): ?>
                                    <span class="badge-stock in-stock" title="Disponível para envio imediato">✓ Em estoque (<?= $stockQty ?> un)</span>
                                <?php else: ?>
                                    <span class="badge-stock out-of-stock" title="Produção artesanal sob demanda">○ Sob encomenda</span>
                                <?php endif; ?>
                            </div>

                            <!-- Mineral -->
                            <div class="store-card-mineral">
                                <span class="mineral-indicator-dot"></span>
                                <span class="mineral-text"><?= \App\Core\View::e($mineralName) ?></span>
                            </div>

                            <!-- Nome do Produto -->
                            <h3 class="store-card-title">
                                <a href="<?= \App\Core\View::e($targetUrl) ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   data-track-product-id="<?= $prod['id'] ?>"
                                   data-track-product-name="<?= \App\Core\View::e($prod['name']) ?>"
                                   data-track-source="store_title">
                                    <?= \App\Core\View::e($prod['name']) ?>
                                </a>
                            </h3>

                            <p class="store-card-desc">
                                <?= \App\Core\View::e($prod['description'] ?: 'Biojoia orgonite autoral confeccionada artesanalmente em resina nobre e minerais autênticos.') ?>
                            </p>

                            <!-- Preços e Condições -->
                            <div class="store-card-pricing">
                                <div class="price-main-row">
                                    <span class="price-value">R$ <?= $priceFormatted ?></span>
                                    <span class="price-pix">R$ <?= $pixPriceFormatted ?> no Pix</span>
                                </div>
                                <div class="price-installments">
                                    <span>Até 6x sem juros de R$ <?= number_format(($priceCents / 6) / 100, 2, ',', '.') ?> na Shopee</span>
                                </div>
                            </div>

                            <!-- Botão de Ação Direto Shopee -->
                            <a href="<?= \App\Core\View::e($targetUrl) ?>" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="btn-shopee-action"
                               data-track-product-id="<?= $prod['id'] ?>"
                               data-track-product-name="<?= \App\Core\View::e($prod['name']) ?>"
                               data-track-source="store_btn"
                               title="Comprar <?= \App\Core\View::e($prod['name']) ?> na Shopee Oficial">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="shopee-icon-svg"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h2v2c0 .55.45 1 1 1s1-.45 1-1V8h6v2c0 .55.45 1 1 1s1-.45 1-1V8h2v12z"/></svg>
                                <span><?= $hasStock ? 'Comprar na Shopee' : 'Encomendar na Shopee' ?></span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="arrow-ext-svg"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            
            <!-- Estado Vazio ao Filtrar Sem Resultados -->
            <div class="store-empty-state" id="storeNoResults" style="display: none;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <h3>Nenhuma Peça Encontrada</h3>
                <p>Nenhuma biojoia corresponde aos filtros ou busca selecionados. Tente outro mineral ou limpe a busca.</p>
                <button type="button" class="btn-store-back" onclick="resetStoreFilters()">Ver Todas as 100 Peças</button>
            </div>
        <?php else: ?>
            <div class="store-empty-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <h3>Nenhuma Joia Disponível no Catálogo</h3>
                <p>Nossas coleções estão sendo atualizadas nos bastidores. Volte em instantes.</p>
                <a href="/" class="btn-store-back">Voltar à Página Inicial</a>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- 3. SEÇÃO EDITORIAL: POR QUE COMPRAR VIA SHOPEE OFICIAL -->
<section class="store-reasons-section">
    <div class="container reasons-container">
        
        <div class="reasons-header">
            <span class="reasons-eyebrow">TRANSPARÊNCIA & CONVENIÊNCIA</span>
            <h2 class="reasons-title">Sua Experiência Segura na <span class="gold-gradient-glow">Shopee Oficial</span></h2>
            <p class="reasons-desc">
                Para oferecer os melhores cupons de frete, proteção estendida ao consumidor e máxima velocidade de entrega, 
                hospedamos a finalização das nossas vendas no ecossistema da Shopee Oficial.
            </p>
        </div>

        <div class="reasons-grid">
            <div class="reason-card">
                <div class="reason-icon">🛡️</div>
                <h3>Garantia Shopee Estendida</h3>
                <p>Seu pagamento só é liberado à Divinium após você receber o pacote em casa e conferir a perfeição da sua joia.</p>
            </div>

            <div class="reason-card">
                <div class="reason-icon">🎫</div>
                <h3>Cupons de Frete & Descontos</h3>
                <p>Aproveite os cupons de frete grátis e moedas da Shopee para obter vantagens exclusivas na sua compra.</p>
            </div>

            <div class="reason-card">
                <div class="reason-icon">💎</div>
                <h3>Autenticidade Mineral</h3>
                <p>Todas as peças enviadas acompanham o certificado de autenticidade Divinium e são feitas com pedras naturais legítimas.</p>
            </div>

            <div class="reason-card">
                <div class="reason-icon">💬</div>
                <h3>Atendimento & Concierge VIP</h3>
                <p>Dúvidas sobre o modelo ou tamanho? Nosso atendimento via WhatsApp ou chat da Shopee responde em minutos.</p>
            </div>
        </div>

        <div class="store-whatsapp-cta">
            <div class="whatsapp-cta-content">
                <h3>Precisa de ajuda para escolher sua biojoia?</h3>
                <p>Fale diretamente com nossa consultora de gemas minerais antes de concluir sua compra.</p>
            </div>
            <a href="https://wa.me/5511999999999?text=Ol%C3%A1,%20estou%20na%20loja%20da%20Divinium%20e%20gostaria%20de%20tirar%20d%C3%BAvidas%20sobre%20uma%20biojoia" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="btn-whatsapp-concierge">
                <span>Conversar no WhatsApp</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
            </a>
        </div>

    </div>
</section>

<!-- Script de Filtragem Rápida e Busca Dinâmica no Catálogo -->
<script>
let currentCategoryFilter = 'all';
let currentSearchTerm = '';

function filterStore(cat, btn) {
    document.querySelectorAll('.store-filter-pill').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
    currentCategoryFilter = cat;
    applyStoreFilters();
}

function handleStoreSearch(val) {
    currentSearchTerm = val.trim().toLowerCase();
    applyStoreFilters();
}

function applyStoreFilters() {
    const cards = document.querySelectorAll('.store-product-card');
    let visibleCount = 0;

    cards.forEach(card => {
        const matchesCategory = (currentCategoryFilter === 'all' || card.getAttribute('data-category') === currentCategoryFilter);
        const searchData = card.getAttribute('data-search') || '';
        const matchesSearch = (!currentSearchTerm || searchData.includes(currentSearchTerm));

        if (matchesCategory && matchesSearch) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    const noResults = document.getElementById('storeNoResults');
    if (noResults) {
        noResults.style.display = (visibleCount === 0) ? 'block' : 'none';
    }
}

function resetStoreFilters() {
    const searchInput = document.getElementById('storeSearchInput');
    if (searchInput) searchInput.value = '';
    currentSearchTerm = '';
    
    const allBtn = document.querySelector('.store-filter-pill[data-filter="all"]');
    filterStore('all', allBtn);
}
</script>
