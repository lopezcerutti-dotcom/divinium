<?php
/**
 * VIEW: CATÁLOGO DE JOIAS, ESTOQUE & CANAIS DE VENDA
 * DIVINIUM HIGH-JEWELRY CENTRALIZED MANAGEMENT HUB
 * 
 * Unificação definitiva das seções:
 * 1. Catálogo de Joias & Modelos Autorais
 * 2. Variações Minerais & Controle de Estoque Físico
 * 3. Central de Informações e Exibição Pública dos Produtos
 * 4. Canais de Venda & Integração Bling ERP (API v3)
 */

$activeTab = $activeTab ?? (isset($_GET['tab']) ? trim($_GET['tab']) : 'catalogo');
if (!in_array($activeTab, ['catalogo', 'exibicao', 'bling'], true)) {
    $activeTab = 'catalogo';
}

$groups = $groups ?? [];
$products = $products ?? [];
$categories = $categories ?? [];
$blingConfig = $blingConfig ?? [];
$settings = $settings ?? [];
?>

<!-- ============================================================== -->
<!-- ESTILOS EXECUTIVOS DA CENTRAL UNIFICADA DIVINIUM               -->
<!-- ============================================================== -->
<style>
/* Abas Principais da Central Unificada */
.unified-hub-nav {
    display: flex;
    gap: 8px;
    background: rgba(18, 18, 18, 0.7);
    padding: 6px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    margin-bottom: 24px;
    backdrop-filter: blur(10px);
}
.unified-hub-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 22px;
    background: transparent;
    color: #9e978e;
    border: 1px solid transparent;
    border-radius: 9px;
    font-size: 0.88rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
    text-decoration: none;
}
.unified-hub-tab-btn:hover {
    color: #FFFFFF;
    background: rgba(255, 255, 255, 0.04);
}
.unified-hub-tab-btn.active {
    background: rgba(212, 186, 136, 0.12);
    color: #FFFFFF;
    border-color: rgba(212, 186, 136, 0.35);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.35);
}
.unified-hub-tab-btn .tab-badge {
    background: rgba(255, 255, 255, 0.08);
    color: #CBD0D6;
    padding: 2px 7px;
    border-radius: 10px;
    font-size: 0.72rem;
    font-weight: 700;
}
.unified-hub-tab-btn.active .tab-badge {
    background: rgba(212, 186, 136, 0.25);
    color: #FFFFFF;
}

/* Painéis de Conteúdo das Abas */
.unified-hub-pane {
    display: none;
    animation: fadeIn 0.25s ease forwards;
}
.unified-hub-pane.active {
    display: block;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Linha Principal de Modelo Autoral / Grupo */
.group-row {
    background: rgba(26, 26, 26, 0.75);
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    transition: background 0.2s ease;
}
.group-row:hover {
    background: rgba(35, 35, 35, 0.95);
}
.group-row.is-expanded {
    background: rgba(32, 32, 32, 0.98);
    border-bottom-color: rgba(212, 186, 136, 0.25);
}

/* Botão de Expandir Gaveta de Variações */
.btn-drawer-toggle {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #CBD0D6;
    border-radius: 6px;
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.75rem;
}
.btn-drawer-toggle:hover {
    background: rgba(212, 186, 136, 0.18);
    border-color: rgba(212, 186, 136, 0.4);
    color: #FFFFFF;
}
.group-row.is-expanded .btn-drawer-toggle {
    transform: rotate(90deg);
    background: rgba(212, 186, 136, 0.25);
    border-color: rgba(212, 186, 136, 0.5);
    color: #FFFFFF;
}

/* Gaveta de Variações */
.variations-drawer-row {
    display: none;
    background: rgba(18, 18, 18, 0.95);
    border-bottom: 2px solid rgba(212, 186, 136, 0.3);
}
.variations-drawer-row.active {
    display: table-row;
}
.variations-drawer-container {
    padding: 16px 20px 20px 60px;
    background: linear-gradient(180deg, rgba(212, 186, 136, 0.04) 0%, rgba(0, 0, 0, 0.2) 100%);
    border-left: 3px solid rgba(212, 186, 136, 0.5);
}
.drawer-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    flex-wrap: wrap;
    gap: 10px;
}
.drawer-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: #FFFFFF;
    display: flex;
    align-items: center;
    gap: 8px;
}
.drawer-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.82rem;
    background: rgba(25, 25, 25, 0.85);
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.08);
}
.drawer-table th {
    background: rgba(0, 0, 0, 0.4);
    color: #9e978e;
    font-size: 0.74rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 8px 12px;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}
.drawer-table td {
    padding: 8px 12px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    color: #ded7cf;
    vertical-align: middle;
}
.drawer-table tr:last-child td {
    border-bottom: none;
}
.drawer-table tr:hover {
    background: rgba(255, 255, 255, 0.02);
}

/* Inputs de Edição Rápida da Variação */
.inline-input {
    background: rgba(0, 0, 0, 0.35);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #FFFFFF;
    padding: 5px 8px;
    border-radius: 5px;
    font-size: 0.8rem;
    transition: all 0.2s ease;
}
.inline-input:focus {
    border-color: #CBD0D6;
    outline: none;
    background: rgba(0, 0, 0, 0.6);
}
.inline-input-stock {
    width: 65px;
    text-align: center;
}
.inline-input-price {
    width: 90px;
}
.inline-input-text {
    width: 100%;
    min-width: 140px;
}

/* Badges e Identificadores */
.badge-group-shared {
    background: rgba(212, 186, 136, 0.12);
    color: #ded7cf;
    border: 1px solid rgba(212, 186, 136, 0.25);
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}
.badge-variant-specific {
    background: rgba(167, 173, 180, 0.12);
    color: #CBD0D6;
    border: 1px solid rgba(167, 173, 180, 0.25);
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}
.badge-stock-consolidated {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 3px 9px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.78rem;
}

/* Notificação Toast */
.quick-toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    background: #181818;
    color: #FFFFFF;
    border: 1px solid rgba(212, 186, 136, 0.4);
    padding: 12px 20px;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    z-index: 10000;
    display: none;
    align-items: center;
    gap: 10px;
    font-size: 0.85rem;
    animation: slideUp 0.25s ease;
}
@keyframes slideUp {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<!-- CABEÇALHO DO CATÁLOGO & BOTÕES DE AÇÃO -->
<div class="catalog-header-bar">
    <div class="catalog-header-text">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
            <span style="font-size: 1.6rem;">💎</span>
            <h2 style="margin: 0;">Catálogo de Joias, Estoque & Canais de Venda</h2>
            <span class="badge-group-shared">CENTRAL INTEGRADA</span>
        </div>
        <p>Gerenciamento centralizado de modelos autorais, variações minerais, controle de estoque físico, sincronização com Bling ERP e exibição pública.</p>
    </div>
    <div class="catalog-actions-bar">
        <!-- BOTÃO VER VITRINE AO VIVO -->
        <a href="/loja" target="_blank" class="btn-admin-secondary" title="Abrir a Boutique em nova aba">
            <span>🛍️ Vitrine ao Vivo ↗</span>
        </a>

        <!-- BOTÃO SINCRONIZAR COM BLING -->
        <button class="btn-admin-bling" id="syncBlingBtnTable" onclick="triggerBlingSync(event)" title="Sincronizar estoque e produtos com Bling ERP">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="sync-icon">
                <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/>
            </svg>
            <span class="sync-text">Sincronizar com Bling</span>
        </button>

        <!-- BOTÃO ADICIONAR NOVA JOIA -->
        <button class="btn-admin-gold" onclick="openNewProductModal(event)">
            <span>+ Nova Joia</span>
        </button>
    </div>
</div>

<!-- ============================================================== -->
<!-- ABAS PRINCIPAIS DA CENTRAL UNIFICADA                          -->
<!-- ============================================================== -->
<div class="unified-hub-nav">
    <button type="button" class="unified-hub-tab-btn <?= $activeTab === 'catalogo' ? 'active' : '' ?>" id="mainTabBtnCatalogo" onclick="switchMainTab('catalogo')">
        <span>💎 Catálogo de Joias, Estoque & Canais de Venda</span>
        <span class="tab-badge"><?= count($groups) ?> Modelos</span>
    </button>
    <button type="button" class="unified-hub-tab-btn <?= $activeTab === 'bling' ? 'active' : '' ?>" id="mainTabBtnBling" onclick="switchMainTab('bling')">
        <span>📦 Estoque & Integração Bling ERP (API v3)</span>
        <?php if (!empty($blingConfig['is_connected'])): ?>
            <span class="tab-badge" style="background: rgba(34, 197, 94, 0.2); color: #4ade80;">ATIVO</span>
        <?php else: ?>
            <span class="tab-badge" style="background: rgba(234, 179, 8, 0.2); color: #facc15;">API v3</span>
        <?php endif; ?>
    </button>
</div>

<!-- ============================================================== -->
<!-- ABA 1: CATÁLOGO & GRUPOS DE JOIAS COM GAVETAS DE VARIAÇÕES    -->
<!-- ============================================================== -->
<div class="unified-hub-pane <?= $activeTab === 'catalogo' ? 'active' : '' ?>" id="mainTabCatalogo">

    <!-- CARDS DE RESUMO DO CATÁLOGO UNIFICADO -->
    <div class="metrics-grid" style="margin-bottom: 22px;">
        <div class="metric-card">
            <div class="metric-icon-wrap">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
            </div>
            <div class="metric-data">
                <span class="metric-value"><?= count($groups) ?> modelos</span>
                <span class="metric-label"><?= (int) ($totalProducts ?? count($products)) ?> variações cadastradas</span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon-wrap" style="background: rgba(167, 173, 180, 0.12); color: #CBD0D6;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <div class="metric-data">
                <span class="metric-value"><?= (int) ($activeProducts ?? 0) ?> joias</span>
                <span class="metric-label">Exibidas Atualmente na Loja</span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon-wrap" style="background: rgba(202, 161, 92, 0.12); color: #CBD0D6;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
            </div>
            <div class="metric-data">
                <span class="metric-value"><?= (int) ($totalStock ?? 0) ?> un.</span>
                <span class="metric-label">Estoque Físico Consolidado</span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon-wrap" style="background: rgba(167, 173, 180, 0.12); color: #CBD0D6;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/></svg>
            </div>
            <div class="metric-data">
                <span class="metric-value" style="font-size: 1.15rem; display: flex; align-items: center; gap: 8px;">
                    <?php if (!empty($blingConfig['is_connected'])): ?>
                        <span class="bling-status-badge bling-status-connected" style="font-size: 0.72rem;"><span class="bling-status-dot"></span> Bling Ativo</span>
                    <?php elseif (!empty($blingConfig['client_id'])): ?>
                        <span class="bling-status-badge bling-status-waiting" style="font-size: 0.72rem;"><span class="bling-status-dot"></span> Bling Pendente</span>
                    <?php else: ?>
                        <span class="bling-status-badge bling-status-disconnected" style="font-size: 0.72rem;"><span class="bling-status-dot"></span> Bling Desconectado</span>
                    <?php endif; ?>
                </span>
                <span class="metric-label">
                    <?= !empty($blingConfig['last_sync_at']) ? 'Sync: ' . date('d/m H:i', strtotime($blingConfig['last_sync_at'])) : 'Pronto p/ sincronizar' ?>
                </span>
            </div>
        </div>
    </div>

    <!-- BARRA DE BUSCA EM TEMPO REAL & FILTROS -->
    <div class="admin-filter-bar">
        <div class="search-box-wrap">
            <svg class="search-box-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" id="catalogSearchInput" class="search-box-input" placeholder="Buscar por modelo, gema mineral ou SKU..." onkeyup="filterCatalog()" oninput="filterCatalog()">
        </div>

        <!-- FILTRO DE CATEGORIA -->
        <select id="catalogCategoryFilter" class="filter-select" onchange="filterCatalog()">
            <option value="">Todas as Categorias</option>
            <?php foreach ($categories ?? [] as $cat): ?>
                <option value="<?= \App\Core\View::e($cat['name']) ?>"><?= \App\Core\View::e($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- FILTRO DE STATUS -->
        <select id="catalogStatusFilter" class="filter-select" onchange="filterCatalog()">
            <option value="">Todos os Status</option>
            <option value="ativo">Exibidos na Loja</option>
            <option value="oculto">Ocultos</option>
        </select>

        <!-- FILTRO DE ESTOQUE -->
        <select id="catalogStockFilter" class="filter-select" onchange="filterCatalog()">
            <option value="">Todos os Estoques</option>
            <option value="in_stock">Em Estoque (>0)</option>
            <option value="low_stock">Estoque Baixo (1 a 3)</option>
            <option value="out_of_stock">Esgotado (0)</option>
        </select>

        <div class="filter-count-badge">
            <span id="visibleItemsCount"><?= count($groups) ?></span> de <?= count($groups) ?> modelos
        </div>
    </div>

    <!-- TABELA HIERÁRQUICA DE MODELOS AUTORAIS COM GAVETAS DE VARIAÇÕES -->
    <div class="admin-card" style="padding: 0; overflow: hidden;">
        
        <!-- CONTROLES DE PAGINAÇÃO SUPERIOR -->
        <div class="pagination-bar pagination-bar-top" id="paginationBarTop">
            <div class="pagination-info" id="paginationInfoTop">
                Carregando catálogo unificado...
            </div>
            <div class="pagination-controls-wrap">
                <div class="pagination-per-page">
                    <label for="perPageSelectTop">Itens por página:</label>
                    <select id="perPageSelectTop" class="per-page-select" onchange="changeItemsPerPage(this.value)">
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="pagination-nav" id="paginationNavTop"></div>
            </div>
        </div>

        <!-- CONTAINER DA TABELA -->
        <div class="table-scroll-container" id="catalogTableScrollContainer">
            <table class="admin-table" id="productsTable">
                <thead>
                    <tr>
                        <th style="width: 44px; text-align: center;" title="Expandir variações vinculadas">Var.</th>
                        <th style="width: 58px; text-align: center;">Foto</th>
                        <th>Modelo Autoral & SKU Base</th>
                        <th>Categoria</th>
                        <th>Preço Base (R$)</th>
                        <th>Canais de Venda</th>
                        <th>Estoque Consolidado</th>
                        <th style="text-align: center; width: 60px;">Ordem</th>
                        <th>Status</th>
                        <th style="text-align: right; padding-right: 20px;">Ações</th>
                    </tr>
                </thead>
                <tbody id="catalogTableBody">
                    <?php if (!empty($groups)): ?>
                        <?php foreach ($groups as $grp): ?>
                            <?php 
                                $groupStock = (int) ($grp['total_stock'] ?? 0);
                                $varCount = count($grp['variants'] ?? []);
                                if ($groupStock <= 0) {
                                    $stockBadgeClass = 'stock-out';
                                    $stockBadgeText = 'Esgotado';
                                } elseif ($groupStock <= 3) {
                                    $stockBadgeClass = 'stock-low';
                                    $stockBadgeText = $groupStock . ' un.';
                                } else {
                                    $stockBadgeClass = 'stock-in';
                                    $stockBadgeText = $groupStock . ' un.';
                                }

                                $status = $grp['status'] ?? 'published';
                                $statusClass = 'status-' . $status;
                                $statusLabel = $status === 'published' ? 'Publicado' : ($status === 'draft' ? 'Rascunho' : 'Oculto');

                                $minPrice = ($grp['min_price_cents'] ?? 0) / 100;
                                $maxPrice = ($grp['max_price_cents'] ?? 0) / 100;
                                $priceDisplay = ($minPrice > 0 && $maxPrice > $minPrice) 
                                    ? ('R$ ' . number_format($minPrice, 2, ',', '.') . ' – ' . number_format($maxPrice, 2, ',', '.'))
                                    : ('R$ ' . number_format($minPrice > 0 ? $minPrice : (($grp['parent_product']['price_cents'] ?? 0) / 100), 2, ',', '.'));

                                $stonesSummary = implode(' • ', array_filter(array_unique(array_map(function($v) {
                                    return $v['stone_mineral'] ?? '';
                                }, $grp['variants']))));

                                $groupJson = json_encode($grp, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE);
                            ?>
                            <!-- LINHA DO MODELO / GRUPO PRINCIPAL -->
                            <tr id="group-row-<?= \App\Core\View::e($grp['base_sku']) ?>" 
                                class="group-row"
                                data-name="<?= strtolower(\App\Core\View::e($grp['name'] ?? '')) ?>" 
                                data-sku="<?= strtolower(\App\Core\View::e($grp['base_sku'] ?? '')) ?>" 
                                data-stone="<?= strtolower(\App\Core\View::e($stonesSummary)) ?>" 
                                data-category="<?= \App\Core\View::e($grp['category_name'] ?? '') ?>" 
                                data-status="<?= ($status === 'published' ? 'ativo' : 'oculto') ?>" 
                                data-stock="<?= ($groupStock > 3 ? 'in_stock' : ($groupStock > 0 ? 'low_stock' : 'out_of_stock')) ?>">
                                
                                <!-- BOTÃO EXPANDIR GAVETA DE VARIAÇÕES -->
                                <td style="text-align: center;">
                                    <button type="button" class="btn-drawer-toggle" onclick="toggleVariationsDrawer('<?= \App\Core\View::e($grp['base_sku']) ?>')" title="Expandir/recolher variações minerais">
                                        ▶
                                    </button>
                                </td>

                                <!-- FOTO PRINCIPAL DO MODELO -->
                                <td style="text-align: center;">
                                    <img src="<?= \App\Core\View::e($grp['image_url'] ?: '/assets/images/hero/featured-green-shell.jpg') ?>" 
                                         alt="<?= \App\Core\View::e($grp['name']) ?>" 
                                         class="table-img-thumb"
                                         onclick="toggleVariationsDrawer('<?= \App\Core\View::e($grp['base_sku']) ?>')"
                                         style="cursor: pointer;"
                                         onerror="this.src='/assets/images/hero/featured-green-shell.jpg'">
                                </td>

                                <!-- NOME DO MODELO & SKU BASE -->
                                <td>
                                    <div style="font-weight: 600; color: #ffffff; font-size: 0.9rem; cursor: pointer;" onclick="toggleVariationsDrawer('<?= \App\Core\View::e($grp['base_sku']) ?>')">
                                        <?= \App\Core\View::e($grp['name']) ?>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 3px;">
                                        <span class="badge-group-shared">SKU BASE: <?= \App\Core\View::e($grp['base_sku']) ?></span>
                                        <span style="font-size: 0.72rem; color: #8e8880;">
                                            <?= $varCount ?> <?= $varCount === 1 ? 'variação' : 'variações' ?>
                                        </span>
                                    </div>
                                </td>

                                <!-- CATEGORIA -->
                                <td>
                                    <span class="category-pill">
                                        <?= \App\Core\View::e($grp['category_name'] ?? 'Coleção Autoral') ?>
                                    </span>
                                </td>

                                <!-- PREÇO BASE OU FAIXA -->
                                <td>
                                    <div style="font-weight: 600; color: #ede7df; font-size: 0.86rem;">
                                        <?= $priceDisplay ?>
                                    </div>
                                </td>

                                <!-- CANAIS DE VENDA DO GRUPO -->
                                <td>
                                    <?php if (!empty($grp['links'])): ?>
                                        <div class="platform-links-list">
                                            <?php foreach ($grp['links'] as $lnk): ?>
                                                <?php 
                                                    $platSlug = strtolower(preg_replace('/[^a-z0-9]/', '', $lnk['platform_name'] ?? 'shopee'));
                                                    $linkPriceFormatted = number_format(($lnk['price_cents'] ?? 0) / 100, 2, ',', '.');
                                                ?>
                                                <a href="<?= \App\Core\View::e($lnk['url']) ?>" target="_blank" rel="noopener noreferrer" 
                                                   class="platform-mini-tag tag-<?= $platSlug ?>" 
                                                   title="<?= \App\Core\View::e($lnk['button_text'] ?? $lnk['platform_name']) ?> — R$ <?= $linkPriceFormatted ?>">
                                                    <span><?= \App\Core\View::e($lnk['platform_name']) ?></span>
                                                    <span>↗</span>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #6b655c; font-size: 0.74rem;">—</span>
                                    <?php endif; ?>
                                </td>

                                <!-- ESTOQUE CONSOLIDADO DO GRUPO -->
                                <td>
                                    <span class="stock-badge <?= $stockBadgeClass ?>">
                                        <?= $stockBadgeText ?>
                                    </span>
                                    <div style="font-size: 0.7rem; color: #8e8880; margin-top: 2px;">
                                        <?= $varCount ?> variações somadas
                                    </div>
                                </td>

                                <!-- ORDEM -->
                                <td style="text-align: center; color: #a8a096; font-size: 0.82rem;">
                                    <?= (int) ($grp['sort_order'] ?? 0) ?>
                                </td>

                                <!-- STATUS -->
                                <td>
                                    <span class="badge-status <?= $statusClass ?>" id="status-badge-group-<?= $grp['id'] ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>

                                <!-- AÇÕES DO MODELO / GRUPO -->
                                <td style="text-align: right; padding-right: 20px;">
                                    <div class="action-buttons" style="justify-content: flex-end;">
                                        <?php $pSlug = !empty($grp['slug']) ? $grp['slug'] : \App\Controllers\StoreController::slugify($grp['name'] ?? ('joia-' . $grp['id'])); ?>
                                        <a href="/produto/<?= urlencode($pSlug) ?>" target="_blank" class="btn-table-action" title="Ver vitrine pública da joia" style="text-decoration: none; color: #CBD0D6; border-color: rgba(212,186,136,0.3);">
                                            Ver ↗
                                        </a>
                                        <button class="btn-table-action" onclick="toggleProductStatus(<?= $grp['id'] ?>)" title="Alternar status na loja">
                                            <?= $status === 'published' ? 'Ocultar' : 'Publicar' ?>
                                        </button>
                                        <button class="btn-table-action" onclick='editGroupProduct(<?= $groupJson ?>)' title="Abrir edição completa desta joia (informações, preços, fotos, variações, estoque, ficha técnica e canais)" style="font-weight: 600; color: #FFFFFF; background: rgba(212,186,136,0.18); border-color: rgba(212,186,136,0.45);">
                                            ✏️ Editar Joia Completa
                                        </button>
                                        <button class="btn-table-action btn-table-duplicate" onclick="duplicateProduct(<?= $grp['id'] ?>)" title="Duplicar joia e canais de venda">
                                            Duplicar
                                        </button>
                                        <button class="btn-table-action btn-delete" onclick="deleteProduct(<?= $grp['id'] ?>)" title="Excluir produto e variações">
                                            Excluir
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- GAVETA EXPANSÍVEL DE VARIAÇÕES MINERAIS VINCULADAS -->
                            <tr id="drawer-group-<?= \App\Core\View::e($grp['base_sku']) ?>" class="variations-drawer-row">
                                <td colspan="10" style="padding: 0;">
                                    <div class="variations-drawer-container">
                                        <div class="drawer-header-bar">
                                            <div class="drawer-title">
                                                <span>💎 Variações Minerais do Modelo:</span>
                                                <strong style="color: #ffffff;"><?= \App\Core\View::e($grp['name']) ?></strong>
                                                <span class="badge-group-shared">SKU BASE: <?= \App\Core\View::e($grp['base_sku']) ?></span>
                                            </div>
                                            <div style="display: flex; gap: 8px;">
                                                <button type="button" class="btn-admin-gold" style="padding: 5px 12px; font-size: 0.78rem;" onclick='addNewVariantToGroup(<?= $groupJson ?>)'>
                                                    <span>+ Nova Variação Mineral</span>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- TABELA COMPACTA DE VARIAÇÕES -->
                                        <table class="drawer-table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 44px; text-align: center;">Foto</th>
                                                    <th>Gema / Mineral</th>
                                                    <th>SKU da Variação</th>
                                                    <th>Preço (R$)</th>
                                                    <th>Estoque Físico</th>
                                                    <th>Disponibilidade</th>
                                                    <th>Link Específico (Shopee / Externo)</th>
                                                    <th style="text-align: right; width: 140px;">Ações Rápidas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($grp['variants'] as $v): ?>
                                                    <?php 
                                                        $vStock = (int)($v['stock_quantity'] ?? 0);
                                                        $vPriceFormatted = number_format(($v['price_cents'] ?? 0) / 100, 2, ',', '.');
                                                    ?>
                                                    <tr id="variant-row-<?= $v['id'] ?>">
                                                        <!-- FOTO DA VARIAÇÃO -->
                                                        <td style="text-align: center;">
                                                            <img src="<?= \App\Core\View::e($v['image_url'] ?: $grp['image_url']) ?>" 
                                                                 alt="<?= \App\Core\View::e($v['stone_mineral'] ?? '') ?>" 
                                                                 style="width: 34px; height: 34px; object-fit: cover; border-radius: 4px; border: 1px solid rgba(255,255,255,0.12);"
                                                                 onerror="this.src='/assets/images/hero/featured-green-shell.jpg'">
                                                        </td>

                                                        <!-- GEMA / MINERAL -->
                                                        <td>
                                                            <input type="text" class="inline-input inline-input-text" id="v_stone_<?= $v['id'] ?>" value="<?= \App\Core\View::e($v['stone_mineral'] ?? '') ?>" placeholder="Ex: Concha Verde Esmeralda">
                                                        </td>

                                                        <!-- SKU INDIVIDUAL -->
                                                        <td>
                                                            <input type="text" class="inline-input" id="v_sku_<?= $v['id'] ?>" value="<?= \App\Core\View::e($v['sku'] ?? '') ?>" style="width: 120px; font-family: monospace; font-size: 0.78rem;">
                                                        </td>

                                                        <!-- PREÇO (R$) -->
                                                        <td>
                                                            <input type="text" class="inline-input inline-input-price" id="v_price_<?= $v['id'] ?>" value="<?= $vPriceFormatted ?>" onkeyup="formatCurrencyLive(this)">
                                                        </td>

                                                        <!-- ESTOQUE FÍSICO -->
                                                        <td>
                                                            <input type="number" min="0" class="inline-input inline-input-stock" id="v_stock_<?= $v['id'] ?>" value="<?= $vStock ?>">
                                                        </td>

                                                        <!-- STATUS DE ESTOQUE -->
                                                        <td>
                                                            <select class="inline-input" id="v_status_<?= $v['id'] ?>" style="font-size: 0.78rem;">
                                                                <option value="in_stock" <?= ($v['stock_status'] ?? '') === 'in_stock' ? 'selected' : '' ?>>Pronta Entrega</option>
                                                                <option value="made_to_order" <?= ($v['stock_status'] ?? '') === 'made_to_order' ? 'selected' : '' ?>>Sob Encomenda</option>
                                                                <option value="out_of_stock" <?= ($v['stock_status'] ?? '') === 'out_of_stock' ? 'selected' : '' ?>>Esgotado</option>
                                                            </select>
                                                        </td>

                                                        <!-- LINK ESPECÍFICO -->
                                                        <td>
                                                            <input type="url" class="inline-input inline-input-text" id="v_ext_<?= $v['id'] ?>" value="<?= \App\Core\View::e($v['external_url'] ?? '') ?>" placeholder="https://shopee.com.br/...">
                                                            <input type="hidden" id="v_img_<?= $v['id'] ?>" value="<?= \App\Core\View::e($v['image_url'] ?? '') ?>">
                                                        </td>

                                                        <!-- AÇÕES DA VARIAÇÃO -->
                                                        <td style="text-align: right; white-space: nowrap;">
                                                            <button type="button" class="btn-table-action" onclick="saveQuickVariant(<?= $v['id'] ?>)" title="Salvar alterações desta variação instantaneamente" style="color: #FFFFFF; border-color: rgba(212, 186, 136, 0.4);">
                                                                💾 Salvar
                                                            </button>
                                                            <?php if ($v['id'] !== $grp['id']): ?>
                                                                <button type="button" class="btn-table-action btn-delete" onclick="deleteProduct(<?= $v['id'] ?>)" title="Excluir apenas esta variação">
                                                                    ✕
                                                                </button>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="emptyCatalogRow">
                            <td colspan="10" style="text-align: center; padding: 48px 20px; color: #8e8880;">
                                <div style="font-size: 2.2rem; margin-bottom: 12px;">✦</div>
                                <h3 style="font-family: var(--font-serif); font-size: 1.25rem; color: #ffffff; margin: 0 0 6px;">Nenhum produto cadastrado no catálogo</h3>
                                <p style="font-size: 0.85rem; margin: 0 0 16px;">Sincronize o catálogo diretamente do Bling ERP ou cadastre seu primeiro modelo autoral.</p>
                                <div style="display: flex; gap: 10px; justify-content: center;">
                                    <button class="btn-admin-bling" onclick="triggerBlingSync(event)">
                                        <span>Sincronizar com Bling</span>
                                    </button>
                                    <button class="btn-admin-gold" onclick="openNewProductModal(event)">
                                        <span>+ Nova Joia</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr id="noResultsFilterRow" style="display: none;">
                        <td colspan="10" style="text-align: center; padding: 36px 20px; color: #8e8880;">
                            Nenhuma joia corresponde aos filtros selecionados. Limpe os campos de busca para exibir todas.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- CONTROLES DE PAGINAÇÃO INFERIOR -->
        <div class="pagination-bar pagination-bar-bottom" id="paginationBarBottom">
            <div class="pagination-info" id="paginationInfoBottom"></div>
            <div class="pagination-controls-wrap">
                <div class="pagination-per-page">
                    <label for="perPageSelectBottom">Itens por página:</label>
                    <select id="perPageSelectBottom" class="per-page-select" onchange="changeItemsPerPage(this.value)">
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="pagination-nav" id="paginationNavBottom"></div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- ABA 2: ESTOQUE & INTEGRAÇÃO BLING ERP (API v3)                 -->
<!-- ============================================================== -->
<div class="unified-hub-pane <?= $activeTab === 'bling' ? 'active' : '' ?>" id="mainTabBling">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
        <!-- CARD DE STATUS DA CONEXÃO BLING -->
        <div class="admin-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 12px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 1.4rem;">📦</span>
                    <div>
                        <h3 style="margin: 0; font-size: 1.05rem;">Status da Conexão Bling ERP (API v3)</h3>
                        <span style="font-size: 0.75rem; color: #8e8880;">OAuth 2.0 com renovação automática de Refresh Token</span>
                    </div>
                </div>
                <?php if (!empty($blingConfig['is_connected'])): ?>
                    <span class="bling-status-badge bling-status-connected"><span class="bling-status-dot"></span> Conectado</span>
                <?php elseif (!empty($blingConfig['client_id'])): ?>
                    <span class="bling-status-badge bling-status-waiting"><span class="bling-status-dot"></span> Pendente</span>
                <?php else: ?>
                    <span class="bling-status-badge bling-status-disconnected"><span class="bling-status-dot"></span> Desconectado</span>
                <?php endif; ?>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 18px; font-size: 0.82rem;">
                <div style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                    <span style="color: #8e8880; display: block; font-size: 0.74rem;">ÚLTIMA SINCRONIZAÇÃO:</span>
                    <strong style="color: #FFFFFF; font-size: 0.9rem; margin-top: 2px; display: block;">
                        <?= !empty($blingConfig['last_sync_at']) ? date('d/m/Y H:i:s', strtotime($blingConfig['last_sync_at'])) : 'Nunca realizada' ?>
                    </strong>
                </div>
                <div style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                    <span style="color: #8e8880; display: block; font-size: 0.74rem;">ITENS NO CATÁLOGO:</span>
                    <strong style="color: #FFFFFF; font-size: 0.9rem; margin-top: 2px; display: block;">
                        <?= (int) ($blingConfig['last_sync_count'] ?? count($products)) ?> itens ativos
                    </strong>
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="button" class="btn-admin-bling" onclick="triggerBlingSync(event)" style="flex: 1; justify-content: center; padding: 12px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/>
                    </svg>
                    <span>Sincronizar Estoque Agora</span>
                </button>
                <button type="button" class="btn-admin-secondary" onclick="openBlingModal(event)">
                    <span>⚙️ Configurar Credenciais</span>
                </button>
            </div>
        </div>

        <!-- CARD DE MONITOR DE ESTOQUE CRÍTICO -->
        <div class="admin-card">
            <h3 style="margin: 0 0 8px; font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
                <span>⚠️</span>
                <span>Alerta de Estoque Crítico & Reposição</span>
            </h3>
            <p style="font-size: 0.8rem; color: #8e8880; margin: 0 0 14px;">Peças com estoque zerado ou abaixo de 3 unidades no estoque físico:</p>

            <div style="max-height: 220px; overflow-y: auto;">
                <?php 
                    $lowStockItems = array_filter($products, function($p) {
                        return ((int)($p['stock_quantity'] ?? 0)) <= 3;
                    });
                ?>
                <?php if (!empty($lowStockItems)): ?>
                    <table style="width: 100%; font-size: 0.78rem; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.08); color: #8e8880; text-align: left;">
                                <th style="padding: 6px 8px;">Joia</th>
                                <th style="padding: 6px 8px;">SKU</th>
                                <th style="padding: 6px 8px;">Gema</th>
                                <th style="padding: 6px 8px; text-align: center;">Estoque</th>
                                <th style="padding: 6px 8px; text-align: right;">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($lowStockItems, 0, 8) as $lsp): ?>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                    <td style="padding: 6px 8px; color: #ffffff;"><?= \App\Core\View::e($lsp['name']) ?></td>
                                    <td style="padding: 6px 8px; font-family: monospace; color: #CBD0D6;"><?= \App\Core\View::e($lsp['sku']) ?></td>
                                    <td style="padding: 6px 8px; color: #ded7cf;"><?= \App\Core\View::e($lsp['stone_mineral'] ?? '—') ?></td>
                                    <td style="padding: 6px 8px; text-align: center;">
                                        <span class="stock-badge <?= ((int)($lsp['stock_quantity'] ?? 0)) <= 0 ? 'stock-out' : 'stock-low' ?>" style="font-size: 0.7rem; padding: 2px 6px;">
                                            <?= (int)($lsp['stock_quantity'] ?? 0) ?> un.
                                        </span>
                                    </td>
                                    <td style="padding: 6px 8px; text-align: right;">
                                        <button type="button" class="btn-table-action" onclick='editProduct(<?= json_encode($lsp, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE) ?>)' style="padding: 3px 8px; font-size: 0.72rem;">
                                            Ajustar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="text-align: center; padding: 24px 10px; color: #4ade80; font-size: 0.85rem;">
                        ✓ Todos os produtos estão com níveis saudáveis de estoque (> 3 unidades).
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL DE CADASTRO E EDIÇÃO COMPLETA DA JOIA                    -->
<!-- Diferencia dados do grupo (compartilhados) de variações        -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- CENTRAL DE EDIÇÃO COMPLETA DA JOIA (TELA ÚNICA DE EDIÇÃO)       -->
<!-- Reúne todas as 8 áreas do produto num só local sem duplicações -->
<!-- ============================================================== -->
<div class="modal-overlay" id="productModal">
    <div class="modal-content" style="max-width: 1060px; width: 95vw; max-height: 94vh; display: flex; flex-direction: column;">
        
        <!-- CABEÇALHO DA CENTRAL DE EDIÇÃO -->
        <div class="modal-header" style="background: rgba(14, 14, 14, 0.95); padding: 14px 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
            <div style="flex: 1; min-width: 0;">
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <span style="font-size: 1.25rem;">💎</span>
                    <h3 class="modal-title" id="productModalTitle" style="margin: 0; font-size: 1.15rem; font-weight: 700;">Editar Joia Completa</h3>
                    <span class="badge-group-shared" id="modalGroupBadge">MODELO AUTORAL</span>
                    <span class="badge-variant-specific" id="modalSkuBadge" style="font-family: monospace;">SKU: —</span>
                    <span class="badge-status badge-status-published" id="modalStatusBadge">● Publicado</span>
                </div>
                <p style="font-size: 0.76rem; color: #a8a096; margin: 3px 0 0;">
                    Central Única DIVINIUM: altere informações gerais, preços, imagens, variações, estoque, ficha técnica, visibilidade e canais.
                </p>
            </div>
            
            <div style="display: flex; align-items: center; gap: 10px;">
                <button type="button" class="btn-admin-gold" onclick="document.getElementById('productForm').requestSubmit()" style="padding: 8px 16px; font-size: 0.84rem; white-space: nowrap;">
                    <span>💾 Salvar Joia Completa</span>
                </button>
                <button class="close-modal-btn" onclick="closeProductModal()" style="font-size: 1.5rem; line-height: 1; padding: 0 4px;">&times;</button>
            </div>
        </div>

        <!-- AS 8 ABAS ORGANIZADAS DA CENTRAL DE EDIÇÃO -->
        <div class="modal-tabs" style="background: rgba(20, 20, 20, 0.95); overflow-x: auto; padding: 6px 14px; gap: 4px; border-bottom: 1px solid rgba(255, 255, 255, 0.06);">
            <button type="button" class="modal-tab-btn active" id="tabBtnGeneral" onclick="switchProductTab('general')">
                <span>✦ Informações Gerais</span>
            </button>
            <button type="button" class="modal-tab-btn" id="tabBtnPrices" onclick="switchProductTab('prices')">
                <span>💰 Preços & Pagamentos</span>
            </button>
            <button type="button" class="modal-tab-btn" id="tabBtnImages" onclick="switchProductTab('images')">
                <span>🖼️ Imagens & Galeria</span>
            </button>
            <button type="button" class="modal-tab-btn" id="tabBtnVariants" onclick="switchProductTab('variants')">
                <span>💎 Variações & Pedras</span>
                <span id="modalVariantsCountBadge" class="tab-badge" style="background: rgba(212, 186, 136, 0.2); color: #FFFFFF; font-size: 0.7rem; padding: 1px 6px; border-radius: 10px; margin-left: 4px;">1</span>
            </button>
            <button type="button" class="modal-tab-btn" id="tabBtnStock" onclick="switchProductTab('stock')">
                <span>📦 Estoque & Identificação</span>
            </button>
            <button type="button" class="modal-tab-btn" id="tabBtnSpecs" onclick="switchProductTab('specs')">
                <span>📋 Ficha Técnica</span>
            </button>
            <button type="button" class="modal-tab-btn" id="tabBtnDisplay" onclick="switchProductTab('display')">
                <span>👁️ Exibição Pública</span>
            </button>
            <button type="button" class="modal-tab-btn" id="tabBtnLinks" onclick="switchProductTab('links')">
                <span>🔗 Canais de Venda</span>
                <span id="linksCountBadge" style="background: rgba(212, 186, 136, 0.2); color: #CBD0D6; font-size: 0.7rem; padding: 1px 6px; border-radius: 10px; margin-left: 4px;">0</span>
            </button>
        </div>

        <form id="productForm" onsubmit="handleProductSubmit(event)" style="overflow-y: auto; padding: 18px 20px; flex: 1;" enctype="multipart/form-data">
            <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">
            <input type="hidden" name="id" id="prodId" value="">
            <input type="hidden" name="parent_sku" id="prodParentSku" value="">
            <input type="hidden" name="links" id="prodLinksJson" value="[]">
            <input type="hidden" name="variants_data" id="prodVariantsJsonInput" value="[]">
            <input type="hidden" name="deleted_variant_ids" id="prodDeletedVariantIds" value="">
            <input type="hidden" name="gallery_json" id="prodGalleryJsonInput" value="[]">
            <input type="hidden" name="specs_json" id="prodSpecsJsonInput" value="{}">

            <!-- ============================================================== -->
            <!-- SEÇÃO 1: INFORMAÇÕES GERAIS                                    -->
            <!-- ============================================================== -->
            <div id="productTabGeneral" class="modal-tab-pane active">
                <div style="background: rgba(212,186,136,0.06); border: 1px solid rgba(212,186,136,0.2); border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; font-size: 0.8rem; color: #ded7cf;">
                    <strong>✦ Informações Gerais do Modelo:</strong> O nome do modelo, categoria, link próprio e storytelling são herdados por todas as variações minerais desta joia.
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodName">Nome do Modelo da Joia *</label>
                        <input type="text" id="prodName" name="name" class="form-input" required placeholder="Ex: Bracelete Caminhos" oninput="handleNameInput(this)">
                    </div>

                    <div class="form-group">
                        <label for="prodCategory">Categoria / Coleção *</label>
                        <select id="prodCategory" name="category_id" class="form-select" required>
                            <option value="">Selecione uma categoria...</option>
                            <?php foreach ($categories ?? [] as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= \App\Core\View::e($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodSlug">URL Própria da Joia (Slug / Link Amigável) *</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="color: #9e978e; font-size: 0.8rem; background: rgba(255,255,255,0.04); padding: 8px 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.08); white-space: nowrap;">
                                divinium.com.br/produto/
                            </span>
                            <input type="text" id="prodSlug" name="slug" class="form-input" placeholder="bracelete-caminhos" oninput="handleSlugInput(this)">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="prodSku">SKU Base do Modelo *</label>
                        <input type="text" id="prodSku" name="sku" class="form-input" placeholder="Ex: DIV-BRAC-CAM (gerado automaticamente se vazio)">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label>Status na Boutique</label>
                        <div class="status-radio-group">
                            <label class="status-radio-label">
                                <input type="radio" name="status" id="statusPublished" value="published" checked onchange="updateModalStatusBadge('published')">
                                <span style="color: #4ade80;">● Publicado</span>
                            </label>
                            <label class="status-radio-label">
                                <input type="radio" name="status" id="statusDraft" value="draft" onchange="updateModalStatusBadge('draft')">
                                <span style="color: #facc15;">● Rascunho</span>
                            </label>
                            <label class="status-radio-label">
                                <input type="radio" name="status" id="statusHidden" value="hidden" onchange="updateModalStatusBadge('hidden')">
                                <span style="color: #9ca3af;">● Oculto</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="prodSortOrder">Ordem de Exibição na Loja</label>
                        <input type="number" id="prodSortOrder" name="sort_order" class="form-input" value="0" placeholder="0">
                    </div>
                </div>

                <div class="form-checkbox-row" style="margin-bottom: 14px; background: rgba(255,255,255,0.02); padding: 10px 14px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.06);">
                    <input type="checkbox" id="prodFeatured" name="is_featured" value="1" style="width: 18px; height: 18px; accent-color: #737B85;">
                    <label for="prodFeatured" style="margin: 0; cursor: pointer; color: #ede7df;">✦ Destacar este modelo autoral no topo da Vitrine Principal (Destaque VIP)</label>
                </div>

                <div class="form-group">
                    <label for="prodShortDesc">Breve Resumo (1 linha para cards e SEO)</label>
                    <input type="text" id="prodShortDesc" name="short_description" class="form-input" placeholder="Ex: Joia autoral contemporânea com banho nobre e pedras minerais autênticas lapidadas à mão.">
                </div>

                <div class="form-group">
                    <label for="prodDesc">Descrição Completa & Storytelling da Peça</label>
                    <textarea id="prodDesc" name="description" class="form-textarea" rows="4" placeholder="Descreva a manufatura, essência do design, simbolismo das pedras, autenticidade e cuidados..."></textarea>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- SEÇÃO 2: PREÇOS & PAGAMENTOS                                   -->
            <!-- ============================================================== -->
            <div id="productTabPrices" class="modal-tab-pane">
                <div style="background: rgba(212,186,136,0.06); border: 1px solid rgba(212,186,136,0.2); border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; font-size: 0.8rem; color: #ded7cf;">
                    <strong>💰 Gestão de Preços e Condições Comerciais:</strong> Configure o valor oficial de venda, preço promocional, porcentagem de desconto no Pix e parcelamento com cálculo automático.
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodPrice">Preço Oficial de Venda (R$) *</label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9e978e; font-size: 0.85rem;">R$</span>
                            <input type="text" id="prodPrice" name="price" class="form-input" required placeholder="380,00" style="padding-left: 38px; font-weight: 600; font-size: 0.95rem;" onkeyup="formatCurrencyLive(this); updatePixLiveCalc();" oninput="updatePixLiveCalc();">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="prodPromotionalPrice">Preço Promocional "De R$..." (Opcional)</label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9e978e; font-size: 0.85rem;">R$</span>
                            <input type="text" id="prodPromotionalPrice" name="promotional_price" class="form-input" placeholder="Ex: 450,00" style="padding-left: 38px;" onkeyup="formatCurrencyLive(this); updatePixLiveCalc();" oninput="updatePixLiveCalc();">
                        </div>
                        <span style="font-size: 0.72rem; color: #8e8880; margin-top: 3px; display: block;">Se preenchido, exibe "De R$ 450,00 Por R$ 380,00" na vitrine.</span>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodPixDiscount">Desconto no Pix (%)</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="number" id="prodPixDiscount" name="pix_discount_pct" class="form-input" value="5" min="0" max="99" style="width: 100px; text-align: center; font-weight: 600;" oninput="updatePixLiveCalc()">
                            <span style="color: #ded7cf; font-size: 0.85rem;">% de desconto à vista</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <label for="prodInstallmentsText" style="margin: 0;">Condições de Parcelamento</label>
                            <button type="button" onclick="calculateInstallmentsSuggestion()" style="background: none; border: none; color: #CBD0D6; font-size: 0.72rem; cursor: pointer; text-decoration: underline;">
                                ⚡ Sugerir 6x sem juros
                            </button>
                        </div>
                        <input type="text" id="prodInstallmentsText" name="installments_text" class="form-input" placeholder="Ex: Em até 6x sem juros de R$ 63,33 no cartão">
                    </div>
                </div>

                <!-- PREVIEW AO VIVO DO BLOCO DE PREÇOS -->
                <div style="background: rgba(0, 0, 0, 0.4); border: 1px solid rgba(212, 186, 136, 0.25); border-radius: 10px; padding: 16px 20px; margin-top: 14px;">
                    <span style="color: #9e978e; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; display: block; margin-bottom: 8px;">✦ Pré-visualização na Loja:</span>
                    <div style="display: flex; align-items: baseline; gap: 12px; flex-wrap: wrap;">
                        <span id="previewComparePrice" style="color: #737373; text-decoration: line-through; font-size: 0.95rem; display: none;">De R$ 0,00</span>
                        <span id="previewCurrentPrice" style="font-family: var(--font-serif, serif); font-size: 1.5rem; font-weight: 600; color: #FFFFFF;">R$ 380,00</span>
                        <span id="previewPixBadge" style="background: rgba(34, 197, 94, 0.12); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.25); border-radius: 6px; padding: 3px 8px; font-size: 0.78rem; font-weight: 600;">
                            ⚡ R$ 361,00 no Pix (-5%)
                        </span>
                    </div>
                    <div id="previewInstallments" style="margin-top: 6px; font-size: 0.8rem; color: #a8a096; display: flex; align-items: center; gap: 6px;">
                        <span>💳 Em até 6x de R$ 63,33 sem juros nos cartões</span>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- SEÇÃO 3: IMAGENS & GALERIA                                     -->
            <!-- ============================================================== -->
            <div id="productTabImages" class="modal-tab-pane">
                <div style="background: rgba(212,186,136,0.06); border: 1px solid rgba(212,186,136,0.2); border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; font-size: 0.8rem; color: #ded7cf;">
                    <strong>🖼️ Imagem Principal & Galeria de Fotos:</strong> A foto principal representa o modelo autoral. A galeria permite fotos adicionais de ângulos, uso e detalhes. Fotos de gemas específicas são cadastradas na aba Variações.
                </div>

                <div style="display: grid; grid-template-columns: 160px 1fr; gap: 20px; margin-bottom: 20px; align-items: start;">
                    <div>
                        <label style="font-size: 0.8rem; color: #9e978e; display: block; margin-bottom: 6px; font-weight: 600;">Foto Principal</label>
                        <div style="width: 160px; height: 160px; border-radius: 10px; overflow: hidden; border: 2px solid rgba(212, 186, 136, 0.35); background: #000000; position: relative;">
                            <img id="mainImgPreview" src="/assets/images/hero/featured-green-shell.jpg" alt="Preview da Joia" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='/assets/images/hero/featured-green-shell.jpg'">
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <label for="prodImageUrl">URL da Imagem Principal</label>
                            <input type="text" id="prodImageUrl" name="image_url" class="form-input" placeholder="/assets/images/products/... ou https://..." oninput="updateMainImagePreview(this.value)">
                        </div>

                        <div class="form-group">
                            <label for="prodImageFile">Ou Enviar Arquivo do Computador (Upload Direto)</label>
                            <input type="file" id="prodImageFile" name="image_file" class="form-input" accept="image/jpeg,image/png,image/webp,image/avif" onchange="previewUploadedImage(this)" style="padding: 6px;">
                            <span style="font-size: 0.72rem; color: #8e8880; margin-top: 3px; display: block;">Formatos aceitos: JPG, PNG, WebP, AVIF. Otimização automática para Quiet Luxury.</span>
                        </div>
                    </div>
                </div>

                <div style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
                        <div>
                            <strong style="color: #ffffff; font-size: 0.88rem;">📸 Galeria de Fotos Adicionais (Ângulos e Manufatura)</strong>
                            <span style="font-size: 0.75rem; color: #8e8880; display: block;">Fotos extras exibidas como miniaturas navegáveis na página do produto</span>
                        </div>
                    </div>

                    <div id="galleryContainer" style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 12px;"></div>

                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="newGalleryUrlInput" class="form-input" placeholder="Cole a URL de uma foto adicional (/assets/images/... ou https://...)" style="flex: 1;">
                        <button type="button" class="btn-admin-gold" onclick="addGalleryItem()" style="white-space: nowrap; padding: 8px 16px; font-size: 0.8rem;">
                            <span>+ Adicionar Foto</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- SEÇÃO 4: VARIAÇÕES & PEDRAS                                    -->
            <!-- ============================================================== -->
            <div id="productTabVariants" class="modal-tab-pane">
                <div style="background: rgba(167, 173, 180, 0.08); border: 1px solid rgba(167, 173, 180, 0.25); border-radius: 8px; padding: 12px 16px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <div>
                        <strong style="color: #FFFFFF; font-size: 0.88rem;">💎 Variações Minerais & Estoques Individuais</strong>
                        <p style="margin: 3px 0 0; font-size: 0.78rem; color: #b5ada3;">
                            Cada variação possui sua gema mineral, foto exclusiva, SKU próprio, estoque físico individual e link específico de compra.
                        </p>
                    </div>
                    <button type="button" class="btn-admin-gold" onclick="addModalVariantRow()" style="padding: 6px 14px; font-size: 0.8rem; white-space: nowrap;">
                        <span>+ Nova Variação Mineral</span>
                    </button>
                </div>

                <!-- CONTAINER DINÂMICO DE VARIAÇÕES -->
                <div id="modalVariantsContainer" style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 14px;"></div>

                <button type="button" class="btn-admin-secondary" onclick="addModalVariantRow()" style="width: 100%; padding: 12px; justify-content: center; font-size: 0.84rem; border-style: dashed; border-color: rgba(212,186,136,0.35);">
                    <span>+ Adicionar Outra Variação Mineral a este Modelo</span>
                </button>
            </div>

            <!-- ============================================================== -->
            <!-- SEÇÃO 5: ESTOQUE & IDENTIFICAÇÃO                               -->
            <!-- ============================================================== -->
            <div id="productTabStock" class="modal-tab-pane">
                <div style="background: rgba(212,186,136,0.06); border: 1px solid rgba(212,186,136,0.2); border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; font-size: 0.8rem; color: #ded7cf;">
                    <strong>📦 Estoque Consolidado & Identificação do Modelo:</strong> O estoque total é consolidado em tempo real somando todas as variações minerais cadastradas.
                </div>

                <div class="metrics-grid" style="margin-bottom: 18px;">
                    <div class="metric-card" style="background: rgba(255,255,255,0.02);">
                        <div class="metric-data">
                            <span class="metric-value" id="stockTabTotalUnits" style="color: #4ade80;">0 un.</span>
                            <span class="metric-label">Estoque Consolidado Total</span>
                        </div>
                    </div>
                    <div class="metric-card" style="background: rgba(255,255,255,0.02);">
                        <div class="metric-data">
                            <span class="metric-value" id="stockTabTotalVariants" style="color: #FFFFFF;">0</span>
                            <span class="metric-label">Variações Minerais Vinculadas</span>
                        </div>
                    </div>
                    <div class="metric-card" style="background: rgba(255,255,255,0.02);">
                        <div class="metric-data">
                            <span class="metric-value" id="stockTabHealthStatus" style="font-size: 1.05rem; color: #CBD0D6;">Pronta Entrega</span>
                            <span class="metric-label">Situação de Disponibilidade</span>
                        </div>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodStockQuantity">Estoque Base do Modelo (Unidades)</label>
                        <input type="number" id="prodStockQuantity" name="stock_quantity" class="form-input" min="0" value="10" placeholder="10" oninput="updateConsolidatedStockUI()">
                        <span style="font-size: 0.72rem; color: #8e8880; margin-top: 3px; display: block;">Se este modelo não tiver variações, este estoque será o considerado.</span>
                    </div>

                    <div class="form-group">
                        <label for="prodStockStatus">Status de Disponibilidade do Modelo</label>
                        <select id="prodStockStatus" name="stock_status" class="form-select">
                            <option value="in_stock">Pronta Entrega para Envio Imediato</option>
                            <option value="made_to_order">Manufatura Sob Encomenda</option>
                            <option value="out_of_stock">Temporariamente Esgotado</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- SEÇÃO 6: FICHA TÉCNICA                                         -->
            <!-- ============================================================== -->
            <div id="productTabSpecs" class="modal-tab-pane">
                <div style="background: rgba(212,186,136,0.06); border: 1px solid rgba(212,186,136,0.2); border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; font-size: 0.8rem; color: #ded7cf;">
                    <strong>📋 Especificações & Ficha Técnica de Manufatura:</strong> Estes dados são exibidos na aba "Especificações & Manufatura" na página do produto.
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="specAcabamento">Acabamento Nobre</label>
                        <input type="text" id="specAcabamento" name="spec_acabamento" class="form-input" placeholder="Ex: Polimento espelhado de alta joalheria artesanal com verniz antialérgico">
                    </div>

                    <div class="form-group">
                        <label for="prodMaterials">Material da Estrutura</label>
                        <input type="text" id="prodMaterials" name="materials" class="form-input" placeholder="Ex: Banho de Prata 950 com Camada Protetora Nobre">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="specDesign">Design Autoral DIVINIUM</label>
                        <input type="text" id="specDesign" name="spec_design" class="form-input" placeholder="Ex: Design Autoral Exclusivo DIVINIUM — Linha Atemporal">
                    </div>

                    <div class="form-group">
                        <label for="specAutenticidade">Autenticidade & Garantia</label>
                        <input type="text" id="specAutenticidade" name="spec_autenticidade" class="form-input" placeholder="Ex: Certificado de Garantia de Fábrica e Nota Fiscal Eletrônica Inclusos">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="specDisponibilidade">Disponibilidade & Envio</label>
                        <input type="text" id="specDisponibilidade" name="spec_disponibilidade" class="form-input" placeholder="Ex: Pronta Entrega com Envio Imediato e Seguro Blindado">
                    </div>

                    <div class="form-group">
                        <label for="prodDimensions">Medidas & Dimensões</label>
                        <input type="text" id="prodDimensions" name="dimensions" class="form-input" placeholder="Ex: Diâmetro 6,5 cm • Aro anatômico ajustável • Peso: 22g">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodColors">Nuances & Cores</label>
                        <input type="text" id="prodColors" name="colors" class="form-input" placeholder="Ex: Prata Nobre Espelhada, Tons Minerais Translúcidos">
                    </div>

                    <div class="form-group">
                        <label for="prodVariants">Tamanhos / Variantes de Medida</label>
                        <input type="text" id="prodVariants" name="variants_json" class="form-input" placeholder="Ex: Tamanho Único Regulável, 16 ao 22">
                    </div>
                </div>

                <!-- LINHAS PERSONALIZADAS DA FICHA TÉCNICA -->
                <div style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 14px; margin-top: 10px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <span style="font-size: 0.82rem; font-weight: 600; color: #FFFFFF;">Linhas Personalizadas Extras</span>
                        <button type="button" class="btn-admin-gold" onclick="addCustomSpecRow()" style="padding: 4px 12px; font-size: 0.74rem;">
                            <span>+ Adicionar Linha Extra</span>
                        </button>
                    </div>
                    <div id="customSpecsContainer" style="display: flex; flex-direction: column; gap: 8px;"></div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- SEÇÃO 7: EXIBIÇÃO PÚBLICA & VISIBILIDADE                       -->
            <!-- ============================================================== -->
            <div id="productTabDisplay" class="modal-tab-pane">
                <div style="background: rgba(212,186,136,0.06); border: 1px solid rgba(212,186,136,0.2); border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; font-size: 0.8rem; color: #ded7cf;">
                    <strong>👁️ Controles de Visibilidade Pública do Card & Detalhe:</strong> Escolha exatamente quais elementos aparecem para os clientes. Ocultar não apaga dados nem afeta integrações fiscais ou de estoque.
                </div>

                <div class="form-row-2" style="margin-bottom: 16px;">
                    <div class="form-group">
                        <label for="prodCardBehavior">Ao Clicar no Card desta Joia</label>
                        <select id="prodCardBehavior" name="card_click_behavior" class="form-select" onchange="toggleCustomUrlGroup(this.value)">
                            <option value="default">Padrão da Boutique (Herança Geral)</option>
                            <option value="detail">💎 Abrir Página de Detalhes Interna (/produto/{slug})</option>
                            <option value="external">↗ Abrir 1º Canal Externo Direto</option>
                            <option value="custom">🔗 URL Personalizada Específica</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="prodDetailLayout">Disposição dos Botões de Compra</label>
                        <select id="prodDetailLayout" name="detail_layout" class="form-select">
                            <option value="default">Padrão da Boutique</option>
                            <option value="list">Lista Vertical Padronizada</option>
                            <option value="grid">Grade Lado a Lado (2 Colunas)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" id="customTargetUrlGroup" style="display: none; margin-bottom: 16px;">
                    <label for="prodCustomTargetUrl">URL de Destino Personalizada</label>
                    <input type="url" id="prodCustomTargetUrl" name="custom_target_url" class="form-input" placeholder="https://...">
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.07); padding: 16px; border-radius: 8px;">
                    <strong style="color: #CBD0D6; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.06em;">Opções de Exibir / Ocultar Informações:</strong>
                    
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 0.84rem; color: #ede7df;">
                        <input type="hidden" name="show_sku" value="0">
                        <input type="checkbox" id="prodShowSku" name="show_sku" value="1" checked style="width: 18px; height: 18px; accent-color: #737B85;">
                        <span>Exibir Código SKU / Referência publicamente na página e cards</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 0.84rem; color: #ede7df;">
                        <input type="hidden" name="show_badge" value="0">
                        <input type="checkbox" id="prodShowBadge" name="show_badge" value="1" checked style="width: 18px; height: 18px; accent-color: #737B85;">
                        <span>Exibir Selos flutuantes na foto (Banho de Prata / Sob Encomenda / Canais)</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 0.84rem; color: #ede7df;">
                        <input type="hidden" name="show_specs" value="0">
                        <input type="checkbox" id="prodShowSpecs" name="show_specs" value="1" checked style="width: 18px; height: 18px; accent-color: #737B85;">
                        <span>Exibir Ficha Técnica & Especificações de Manufatura</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 0.84rem; color: #ede7df;">
                        <input type="hidden" name="show_description" value="0">
                        <input type="checkbox" id="prodShowDescription" name="show_description" value="1" checked style="width: 18px; height: 18px; accent-color: #737B85;">
                        <span>Exibir Descrição Resumida da peça no card</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 0.84rem; color: #ede7df;">
                        <input type="hidden" name="show_channels" value="0">
                        <input type="checkbox" id="prodShowChannels" name="show_channels" value="1" checked style="width: 18px; height: 18px; accent-color: #737B85;">
                        <span>Exibir Bloco "Escolha onde deseja adquirir sua joia" (Canais de compra)</span>
                    </label>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- SEÇÃO 8: CANAIS DE VENDA                                       -->
            <!-- ============================================================== -->
            <div id="productTabLinks" class="modal-tab-pane">
                <div style="background: rgba(212, 186, 136, 0.05); border: 1px solid rgba(212, 186, 136, 0.2); border-radius: 8px; padding: 14px 18px; margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                        <div>
                            <strong style="color: #ffffff; font-size: 0.9rem;">Canais de Venda Oficiais Compartilhados</strong>
                            <p style="margin: 3px 0 0; font-size: 0.78rem; color: #b5ada3;">
                                Estes canais de compra (Shopee, Mercado Livre, Amazon, WhatsApp Concierge, Compra Direta DIVINIUM) aplicam-se a todas as variações do modelo autoral.
                            </p>
                        </div>
                        <button type="button" class="btn-admin-gold" onclick="addNewPlatformLink()" style="padding: 6px 14px; font-size: 0.8rem; white-space: nowrap;">
                            <span>+ Adicionar Canal</span>
                        </button>
                    </div>
                </div>

                <div id="productLinksContainer" style="display: flex; flex-direction: column; gap: 10px;"></div>

                <button type="button" class="btn-add-platform-link" onclick="addNewPlatformLink()" style="margin-top: 12px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    <span>+ Adicionar Canal de Venda (Shopee, Mercado Livre, Amazon, WhatsApp...)</span>
                </button>
            </div>

            <!-- BOTÕES DO RODAPÉ DO MODAL -->
            <div class="modal-footer" style="margin-top: 22px; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center;">
                <button type="button" class="btn-admin-secondary" onclick="closeProductModal()">Cancelar</button>
                <button type="submit" class="btn-admin-gold" id="saveProdBtn" style="padding: 10px 24px; font-size: 0.9rem;">
                    <span>💾 Salvar Todas as Alterações da Joia</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL DE CONFIGURAÇÃO BLING ERP (API v3)                       -->
<!-- ============================================================== -->
<div class="modal-overlay" id="blingModal">
    <div class="modal-content" style="max-width: 680px;">
        <div class="modal-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 1.4rem;">📦</span>
                <div>
                    <h3 class="modal-title" style="margin: 0;">Integração Bling ERP (API v3)</h3>
                    <p style="font-size: 0.78rem; color: #9e978e; margin: 2px 0 0;">Sincronização de catálogo, produtos, variações e estoque</p>
                </div>
            </div>
            <button class="close-modal-btn" onclick="closeBlingModal()">&times;</button>
        </div>

        <div class="bling-tabs">
            <button class="bling-tab-btn active" id="tabBtnSync" onclick="switchBlingTab('sync')">
                <span>🔄 Sincronização</span>
            </button>
            <button class="bling-tab-btn" id="tabBtnConfig" onclick="switchBlingTab('config')">
                <span>⚙️ Credenciais</span>
            </button>
            <button class="bling-tab-btn" id="tabBtnHelp" onclick="switchBlingTab('help')">
                <span>📖 Passo a Passo</span>
            </button>
        </div>

        <!-- ABA 1: SINCRONIZAÇÃO -->
        <div id="blingTabSync" class="bling-tab-content">
            <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 10px; padding: 16px 20px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="font-size: 0.85rem; color: #b5ada3;">Status da Conexão API:</span>
                    <?php if (!empty($blingConfig['is_connected'])): ?>
                        <span class="bling-status-badge bling-status-connected"><span class="bling-status-dot"></span> Conectado via OAuth 2.0</span>
                    <?php elseif (!empty($blingConfig['client_id'])): ?>
                        <span class="bling-status-badge bling-status-waiting"><span class="bling-status-dot"></span> Credenciais Salvas (Pendente)</span>
                    <?php else: ?>
                        <span class="bling-status-badge bling-status-disconnected"><span class="bling-status-dot"></span> Não Vinculado</span>
                    <?php endif; ?>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 0.8rem; border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 12px;">
                    <div>
                        <span style="color: #7a746c;">Última Sincronização:</span>
                        <div style="color: #ede7df; font-weight: 600; margin-top: 2px;">
                            <?= !empty($blingConfig['last_sync_at']) ? date('d/m/Y H:i:s', strtotime($blingConfig['last_sync_at'])) : 'Nunca realizada' ?>
                        </div>
                    </div>
                    <div>
                        <span style="color: #7a746c;">Itens Sincronizados:</span>
                        <div style="color: #ede7df; font-weight: 600; margin-top: 2px;">
                            <?= (int) ($blingConfig['last_sync_count'] ?? 0) ?> produtos no catálogo
                        </div>
                    </div>
                </div>

                <?php if (!empty($blingConfig['last_sync_message'])): ?>
                    <div style="margin-top: 10px; font-size: 0.75rem; color: #9e978e; background: rgba(0,0,0,0.25); padding: 8px 12px; border-radius: 6px;">
                        <strong>Log:</strong> <?= \App\Core\View::e($blingConfig['last_sync_message']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 10px;">
                <button class="btn-admin-bling" id="modalSyncNowBtn" onclick="executeBlingSync('auto')" style="justify-content: center; padding: 14px; font-size: 0.95rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="sync-icon">
                        <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/>
                    </svg>
                    <span class="sync-text">Sincronizar Estoque e Produtos Agora</span>
                </button>
            </div>

            <div id="blingSyncResult" style="display: none; margin-top: 16px; padding: 14px 18px; border-radius: 8px; font-size: 0.85rem;"></div>
        </div>

        <!-- ABA 2: CONFIGURAÇÃO DE CREDENCIAIS -->
        <div id="blingTabConfig" class="bling-tab-content" style="display: none;">
            <form id="blingConfigForm" onsubmit="handleBlingConfigSubmit(event)">
                <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">

                <div class="form-group">
                    <label for="blingClientId">Client ID do Bling (Aplicativo) *</label>
                    <input type="text" id="blingClientId" name="client_id" class="form-input" 
                           value="<?= \App\Core\View::e($blingConfig['client_id'] ?? '') ?>" 
                           placeholder="Cole o Client ID gerado no Bling" required>
                </div>

                <div class="form-group">
                    <label for="blingClientSecret">Client Secret *</label>
                    <div style="position: relative;">
                        <input type="password" id="blingClientSecret" name="client_secret" class="form-input" 
                               value="<?= \App\Core\View::e($blingConfig['client_secret'] ?? '') ?>" 
                               placeholder="Cole o Client Secret gerado no Bling" required style="padding-right: 40px;">
                        <button type="button" onclick="toggleSecretVisibility()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #8e8880; cursor: pointer;">
                            👁
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label>URL de Redirecionamento (Callback)</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="blingRedirectUri" class="form-input" 
                               value="<?= \App\Core\View::e($blingConfig['redirect_uri'] ?? 'https://divinium.com.br/admin/bling/callback') ?>" readonly>
                        <button type="button" class="btn-admin-secondary" onclick="copyRedirectUri()">Copiar</button>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn-admin-secondary" onclick="closeBlingModal()">Cancelar</button>
                    <button type="submit" class="btn-admin-gold" id="saveBlingConfigBtn">Salvar Credenciais</button>
                </div>
            </form>
        </div>

        <!-- ABA 3: PASSO A PASSO -->
        <div id="blingTabHelp" class="bling-tab-content" style="display: none;">
            <ol style="color: #ded7cf; font-size: 0.85rem; padding-left: 20px; line-height: 1.7;">
                <li>Acesse sua conta no Bling: <strong>Preferências › Sistema › Aplicativos</strong>.</li>
                <li>Clique em <strong>Novo Aplicativo</strong>.</li>
                <li>Em <em>URL de Redirecionamento</em>, cole exatamente o link de callback.</li>
                <li>Habilite as permissões de <strong>Produtos</strong> e <strong>Estoque</strong>.</li>
                <li>Copie o <strong>Client ID</strong> e <strong>Client Secret</strong> gerados e salve na aba Credenciais.</li>
            </ol>
        </div>
    </div>
</div>

<!-- TOAST DE NOTIFICAÇÃO RÁPIDA -->
<div class="quick-toast" id="quickToast">
    <span id="quickToastIcon">✓</span>
    <span id="quickToastMsg">Atualização realizada com sucesso!</span>
</div>

<!-- ============================================================== -->
<!-- SCRIPTS DA CENTRAL UNIFICADA                                   -->
<!-- ============================================================== -->
<script>
// Estado Global da Central Única de Edição
let currentProductLinks = [];
let currentProductVariants = [];
let currentProductGallery = [];
let currentCustomSpecs = [];
let deletedVariantIds = [];
let currentPage = 1;
let itemsPerPage = 25;

function showToast(msg, isError = false) {
    const toast = document.getElementById('quickToast');
    const msgEl = document.getElementById('quickToastMsg');
    const iconEl = document.getElementById('quickToastIcon');
    if (!toast || !msgEl) return;

    msgEl.innerText = msg;
    iconEl.innerText = isError ? '⚠️' : '✓';
    toast.style.borderColor = isError ? '#f87171' : 'rgba(212, 186, 136, 0.5)';
    toast.style.display = 'flex';
    setTimeout(() => {
        toast.style.display = 'none';
    }, 3500);
}

window.showAdminToast = function(msg, type = 'success') {
    showToast(msg, type === 'error');
};

function switchMainTab(tab) {
    document.querySelectorAll('.unified-hub-tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.unified-hub-pane').forEach(p => p.classList.remove('active'));

    const targetBtn = document.getElementById('mainTabBtn' + tab.charAt(0).toUpperCase() + tab.slice(1));
    const targetPane = document.getElementById('mainTab' + tab.charAt(0).toUpperCase() + tab.slice(1));

    if (targetBtn) targetBtn.classList.add('active');
    if (targetPane) targetPane.classList.add('active');

    // Atualiza a URL sem recarregar
    const url = new URL(window.location.href);
    url.searchParams.set('tab', tab);
    window.history.replaceState({}, '', url.toString());
}

function toggleVariationsDrawer(groupSku) {
    const row = document.getElementById('group-row-' + groupSku);
    const drawer = document.getElementById('drawer-group-' + groupSku);
    if (!row || !drawer) return;

    const isExpanded = row.classList.contains('is-expanded');
    if (isExpanded) {
        row.classList.remove('is-expanded');
        drawer.classList.remove('active');
    } else {
        row.classList.add('is-expanded');
        drawer.classList.add('active');
    }
}

async function saveQuickVariant(variantId) {
    const sku = document.getElementById('v_sku_' + variantId)?.value || '';
    const stone = document.getElementById('v_stone_' + variantId)?.value || '';
    const price = document.getElementById('v_price_' + variantId)?.value || '0';
    const stock = document.getElementById('v_stock_' + variantId)?.value || '0';
    const status = document.getElementById('v_status_' + variantId)?.value || 'in_stock';
    const ext = document.getElementById('v_ext_' + variantId)?.value || '';
    const img = document.getElementById('v_img_' + variantId)?.value || '';

    const formData = new FormData();
    formData.append('id', variantId);
    formData.append('sku', sku);
    formData.append('stone_mineral', stone);
    formData.append('price', price);
    formData.append('stock_quantity', stock);
    formData.append('stock_status', status);
    formData.append('external_url', ext);
    formData.append('image_url', img);

    const csrf = getCsrfToken();
    if (csrf) formData.append('_csrf_token', csrf);

    try {
        const res = await fetch('/admin/produtos/salvar-variante', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            }
        });
        const data = await res.json();
        if (data.success) {
            showToast(data.message || 'Variação ' + sku + ' atualizada!');
        } else {
            alert(data.error || 'Erro ao salvar variação.');
        }
    } catch (e) {
        alert('Erro ao conectar ao servidor: ' + e.message);
    }
}

function switchProductTab(tab) {
    document.querySelectorAll('.modal-tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.modal-tab-pane').forEach(p => p.classList.remove('active'));

    const btn = document.getElementById('tabBtn' + tab.charAt(0).toUpperCase() + tab.slice(1));
    const pane = document.getElementById('productTab' + tab.charAt(0).toUpperCase() + tab.slice(1));

    if (btn) btn.classList.add('active');
    if (pane) pane.classList.add('active');
}

function updateModalStatusBadge(status) {
    const badge = document.getElementById('modalStatusBadge');
    if (!badge) return;
    badge.className = 'badge-status badge-status-' + status;
    if (status === 'published') badge.innerText = '● Publicado';
    else if (status === 'draft') badge.innerText = '● Rascunho';
    else if (status === 'hidden') badge.innerText = '● Oculto';
}

function updateMainImagePreview(url) {
    const img = document.getElementById('mainImgPreview');
    if (img) {
        img.src = (url && url.trim()) ? url.trim() : '/assets/images/hero/featured-green-shell.jpg';
    }
}

function previewUploadedImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('mainImgPreview');
            if (img) img.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

/* ============================================================== */
/* PREÇOS, PIX & PARCELAMENTO AO VIVO                            */
/* ============================================================== */
function parseMoneyValue(val) {
    if (!val) return 0;
    val = (val + '').replace(/\s/g, '').replace('R$', '').replace(/\./g, '').replace(',', '.');
    return parseFloat(val) || 0;
}

function formatMoney(num) {
    return num.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function updatePixLiveCalc() {
    const priceVal = parseMoneyValue(document.getElementById('prodPrice')?.value || '380,00');
    const promoVal = parseMoneyValue(document.getElementById('prodPromotionalPrice')?.value || '');
    const pixPct = parseInt(document.getElementById('prodPixDiscount')?.value || '5', 10) || 0;

    const previewCurrent = document.getElementById('previewCurrentPrice');
    const previewCompare = document.getElementById('previewComparePrice');
    const previewPix = document.getElementById('previewPixBadge');
    const previewInstallments = document.getElementById('previewInstallments');

    if (previewCurrent) previewCurrent.innerText = 'R$ ' + formatMoney(priceVal);

    if (previewCompare) {
        if (promoVal > priceVal) {
            previewCompare.innerText = 'De R$ ' + formatMoney(promoVal);
            previewCompare.style.display = 'inline';
        } else {
            previewCompare.style.display = 'none';
        }
    }

    const pixPrice = priceVal * (1 - (pixPct / 100));
    if (previewPix) {
        previewPix.innerText = `⚡ R$ ${formatMoney(pixPrice)} no Pix (-${pixPct}%)`;
    }

    const instInput = document.getElementById('prodInstallmentsText');
    if (previewInstallments) {
        if (instInput && instInput.value.trim()) {
            previewInstallments.innerHTML = `<span>💳 ${instInput.value.trim()}</span>`;
        } else {
            const parcel6 = priceVal > 0 ? (priceVal / 6) : 0;
            previewInstallments.innerHTML = `<span>💳 Em até 6x de R$ ${formatMoney(parcel6)} sem juros nos cartões</span>`;
        }
    }
}

function calculateInstallmentsSuggestion() {
    const priceVal = parseMoneyValue(document.getElementById('prodPrice')?.value || '380,00');
    const parcel6 = priceVal > 0 ? (priceVal / 6) : 0;
    const instInput = document.getElementById('prodInstallmentsText');
    if (instInput) {
        instInput.value = `Em até 6x sem juros de R$ ${formatMoney(parcel6)} no cartão`;
    }
    updatePixLiveCalc();
}

/* ============================================================== */
/* GALERIA DE FOTOS ADICIONAIS                                    */
/* ============================================================== */
function renderGalleryItems() {
    const container = document.getElementById('galleryContainer');
    if (!container) return;
    container.innerHTML = '';

    if (!currentProductGallery || currentProductGallery.length === 0) {
        container.innerHTML = `
            <div style="font-size: 0.78rem; color: #8e8880; font-style: italic; padding: 6px 0;">
                Nenhuma imagem adicional na galeria. Cole uma URL abaixo para adicionar.
            </div>
        `;
        return;
    }

    currentProductGallery.forEach((url, idx) => {
        const card = document.createElement('div');
        card.style.position = 'relative';
        card.style.width = '78px';
        card.style.height = '78px';
        card.style.borderRadius = '8px';
        card.style.overflow = 'hidden';
        card.style.border = '1px solid rgba(212, 186, 136, 0.4)';
        card.style.background = '#0a0a0a';

        card.innerHTML = `
            <img src="${url}" alt="Galeria ${idx + 1}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='/assets/images/hero/featured-green-shell.jpg'">
            <button type="button" onclick="removeGalleryItem(${idx})" title="Remover foto" style="position: absolute; top: 3px; right: 3px; background: rgba(0,0,0,0.7); border: none; color: #f87171; border-radius: 50%; width: 20px; height: 20px; font-size: 11px; line-height: 1; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                ✕
            </button>
        `;
        container.appendChild(card);
    });
}

function addGalleryItem() {
    const input = document.getElementById('newGalleryUrlInput');
    const url = (input?.value || '').trim();
    if (!url) {
        alert('Por favor, informe a URL da foto adicional.');
        return;
    }
    currentProductGallery.push(url);
    if (input) input.value = '';
    renderGalleryItems();
}

function removeGalleryItem(idx) {
    currentProductGallery.splice(idx, 1);
    renderGalleryItems();
}

/* ============================================================== */
/* FICHA TÉCNICA PERSONALIZADA (LINHAS EXTRAS)                    */
/* ============================================================== */
function renderCustomSpecs() {
    const container = document.getElementById('customSpecsContainer');
    if (!container) return;
    container.innerHTML = '';

    if (!currentCustomSpecs || currentCustomSpecs.length === 0) {
        container.innerHTML = `
            <div style="font-size: 0.78rem; color: #8e8880; font-style: italic; padding: 4px 0;">
                Nenhuma linha adicional personalizada. Clique em "+ Adicionar Linha Extra" se desejar adicionar propriedades exclusivas (ex: "Lapidação", "Origem", "Peso da Gema").
            </div>
        `;
        return;
    }

    currentCustomSpecs.forEach((spec, idx) => {
        const row = document.createElement('div');
        row.style.display = 'grid';
        row.style.gridTemplateColumns = '180px 1fr 36px';
        row.style.gap = '8px';
        row.style.alignItems = 'center';

        row.innerHTML = `
            <input type="text" class="form-input" placeholder="Propriedade (ex: Lapidação)" value="${spec.label || ''}" oninput="updateCustomSpecField(${idx}, 'label', this.value)" style="padding: 6px 10px; font-size: 0.8rem;">
            <input type="text" class="form-input" placeholder="Descrição / Valor (ex: Oval cabochão facetado artesanalmente)" value="${spec.value || ''}" oninput="updateCustomSpecField(${idx}, 'value', this.value)" style="padding: 6px 10px; font-size: 0.8rem;">
            <button type="button" onclick="removeCustomSpecRow(${idx})" style="background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #f87171; border-radius: 4px; height: 32px; cursor: pointer;" title="Remover linha">
                ✕
            </button>
        `;
        container.appendChild(row);
    });
}

function addCustomSpecRow(label = '', value = '') {
    currentCustomSpecs.push({ label, value });
    renderCustomSpecs();
}

function removeCustomSpecRow(idx) {
    currentCustomSpecs.splice(idx, 1);
    renderCustomSpecs();
}

function updateCustomSpecField(idx, field, val) {
    if (currentCustomSpecs[idx]) {
        currentCustomSpecs[idx][field] = val;
    }
}

function buildSpecsJson() {
    return {
        acabamento: document.getElementById('specAcabamento')?.value || '',
        design: document.getElementById('specDesign')?.value || '',
        autenticidade: document.getElementById('specAutenticidade')?.value || '',
        disponibilidade: document.getElementById('specDisponibilidade')?.value || '',
        custom_specs: currentCustomSpecs.filter(s => (s.label && s.label.trim()) || (s.value && s.value.trim()))
    };
}

/* ============================================================== */
/* ESTOQUE CONSOLIDADO DO MODELO                                  */
/* ============================================================== */
function updateConsolidatedStockUI() {
    let totalUnits = 0;
    if (currentProductVariants && currentProductVariants.length > 0) {
        currentProductVariants.forEach(v => {
            totalUnits += parseInt(v.stock_quantity || 0, 10);
        });
    } else {
        totalUnits = parseInt(document.getElementById('prodStockQuantity')?.value || 0, 10);
    }

    const totalUnitsEl = document.getElementById('stockTabTotalUnits');
    const totalVariantsEl = document.getElementById('stockTabTotalVariants');
    const healthEl = document.getElementById('stockTabHealthStatus');

    if (totalUnitsEl) totalUnitsEl.innerText = totalUnits + ' un.';
    if (totalVariantsEl) totalVariantsEl.innerText = currentProductVariants.length;

    if (healthEl) {
        if (totalUnits > 3) {
            healthEl.innerText = 'Pronta Entrega (Nível Saudável)';
            healthEl.style.color = '#4ade80';
        } else if (totalUnits > 0) {
            healthEl.innerText = 'Estoque Baixo';
            healthEl.style.color = '#facc15';
        } else {
            healthEl.innerText = 'Esgotado / Sob Encomenda';
            healthEl.style.color = '#f87171';
        }
    }
}

/* ============================================================== */
/* ABERTURA & CARREGAMENTO NA TELA ÚNICA DE EDIÇÃO                */
/* ============================================================== */
function openNewProductModal(e) {
    if (e) e.preventDefault();
    document.getElementById('productForm').reset();
    document.getElementById('prodId').value = '';
    document.getElementById('prodParentSku').value = '';
    document.getElementById('prodDeletedVariantIds').value = '';
    deletedVariantIds = [];

    // 1. Informações Gerais
    document.getElementById('statusPublished').checked = true;
    updateModalStatusBadge('published');
    document.getElementById('modalGroupBadge').innerText = 'NOVA JOIA AUTORAL';
    document.getElementById('modalSkuBadge').innerText = 'SKU: (novo)';

    document.getElementById('prodFeatured').checked = false;
    document.getElementById('prodShowBadge').checked = true;
    document.getElementById('prodShowSku').checked = true;
    document.getElementById('prodShowSpecs').checked = true;
    document.getElementById('prodShowDescription').checked = true;
    document.getElementById('prodShowChannels').checked = true;

    document.getElementById('prodCardBehavior').value = 'default';
    toggleCustomUrlGroup('default');
    document.getElementById('prodDetailLayout').value = 'default';

    // 2. Preços & Pagamentos
    document.getElementById('prodPrice').value = '380,00';
    document.getElementById('prodPromotionalPrice').value = '';
    document.getElementById('prodPixDiscount').value = '5';
    document.getElementById('prodInstallmentsText').value = 'Em até 6x sem juros de R$ 63,33 no cartão';

    // 3. Imagem Principal & Galeria
    document.getElementById('prodImageUrl').value = '';
    updateMainImagePreview('');
    currentProductGallery = [];
    renderGalleryItems();

    // 4. Ficha Técnica Padrão
    document.getElementById('specAcabamento').value = 'Polimento espelhado de alta joalheria artesanal com verniz antialérgico';
    document.getElementById('prodMaterials').value = 'Banho de Prata 950 Nobre com Camada Protetora';
    document.getElementById('specDesign').value = 'Design Autoral Exclusivo DIVINIUM — Linha Atemporal';
    document.getElementById('specAutenticidade').value = 'Certificado de Garantia de Fábrica e Nota Fiscal Eletrônica Inclusos';
    document.getElementById('specDisponibilidade').value = 'Pronta Entrega com Envio Imediato e Seguro Blindado';
    document.getElementById('prodDimensions').value = 'Diâmetro ajustável • Aro anatômico';
    document.getElementById('prodColors').value = 'Prata Nobre Espelhada, Nuances Minerais';
    document.getElementById('prodVariants').value = 'Tamanho Único Regulável';
    currentCustomSpecs = [];
    renderCustomSpecs();

    // 5. Estoque
    document.getElementById('prodStockQuantity').value = '10';
    document.getElementById('prodStockStatus').value = 'in_stock';

    // 6. Canais
    currentProductLinks = [{
        platform_name: 'Shopee',
        url: '',
        price: '380,00',
        button_text: 'Comprar na Shopee Oficial',
        button_color: 'default',
        button_size: 'md',
        target: '_blank',
        is_active: 1,
        sort_order: 0
    }];

    // 7. Variações
    currentProductVariants = [{
        id: 0,
        sku: '',
        stone_mineral: 'Gema Mineral Autoral',
        price: '380,00',
        promotional_price: '',
        stock_quantity: 10,
        stock_status: 'in_stock',
        image_url: '/assets/images/hero/featured-green-shell.jpg',
        external_url: ''
    }];

    renderPlatformLinks();
    renderModalVariants();
    updatePixLiveCalc();
    updateConsolidatedStockUI();
    switchProductTab('general');
    document.getElementById('productModalTitle').innerText = 'Cadastrar Novo Modelo de Joia';
    document.getElementById('productModal').classList.add('active');
}

function editGroupProduct(group) {
    document.getElementById('productForm').reset();
    document.getElementById('prodId').value = group.id || '';
    document.getElementById('prodParentSku').value = group.base_sku || '';
    deletedVariantIds = [];
    document.getElementById('prodDeletedVariantIds').value = '';

    // 1. Informações Gerais
    document.getElementById('prodName').value = group.name || '';
    document.getElementById('prodSlug').value = group.slug || '';
    document.getElementById('prodSku').value = group.base_sku || '';
    document.getElementById('prodCategory').value = group.category_id || '';
    document.getElementById('prodSortOrder').value = group.sort_order || 0;
    document.getElementById('prodShortDesc').value = group.short_description || '';
    document.getElementById('prodDesc').value = group.description || '';

    const status = group.status || 'published';
    if (status === 'draft') document.getElementById('statusDraft').checked = true;
    else if (status === 'hidden') document.getElementById('statusHidden').checked = true;
    else document.getElementById('statusPublished').checked = true;
    updateModalStatusBadge(status);

    document.getElementById('modalGroupBadge').innerText = group.name ? group.name.toUpperCase() : 'MODELO AUTORAL';
    document.getElementById('modalSkuBadge').innerText = 'SKU: ' + (group.base_sku || '—');

    document.getElementById('prodFeatured').checked = group.is_featured == 1;

    // 2. Preços & Pagamentos
    let priceStr = '380,00';
    if (group.price_cents && group.price_cents > 0) {
        priceStr = (group.price_cents / 100).toFixed(2).replace('.', ',');
    } else if (group.min_price_cents && group.min_price_cents > 0) {
        priceStr = (group.min_price_cents / 100).toFixed(2).replace('.', ',');
    }
    document.getElementById('prodPrice').value = priceStr;

    let promoStr = '';
    if (group.promotional_price_cents && group.promotional_price_cents > 0) {
        promoStr = (group.promotional_price_cents / 100).toFixed(2).replace('.', ',');
    }
    document.getElementById('prodPromotionalPrice').value = promoStr;

    document.getElementById('prodPixDiscount').value = (group.pix_discount_pct !== undefined && group.pix_discount_pct !== null) ? group.pix_discount_pct : 5;
    document.getElementById('prodInstallmentsText').value = group.installments_text || '';

    updatePixLiveCalc();

    // 3. Imagens & Galeria
    document.getElementById('prodImageUrl').value = group.image_url || '';
    updateMainImagePreview(group.image_url || '');

    currentProductGallery = [];
    if (group.gallery_json) {
        try {
            currentProductGallery = typeof group.gallery_json === 'string' ? JSON.parse(group.gallery_json) : group.gallery_json;
            if (!Array.isArray(currentProductGallery)) currentProductGallery = [];
        } catch(e) {
            currentProductGallery = [];
        }
    }
    renderGalleryItems();

    // 4. Ficha Técnica
    let parsedSpecs = {};
    if (group.specs_json) {
        try {
            parsedSpecs = typeof group.specs_json === 'string' ? JSON.parse(group.specs_json) : group.specs_json;
            if (typeof parsedSpecs !== 'object' || parsedSpecs === null) parsedSpecs = {};
        } catch(e) {
            parsedSpecs = {};
        }
    }
    document.getElementById('specAcabamento').value = parsedSpecs.acabamento || 'Polimento espelhado de alta joalheria artesanal com verniz antialérgico';
    document.getElementById('prodMaterials').value = group.materials || 'Banho de Prata 950 Nobre com Camada Protetora';
    document.getElementById('specDesign').value = parsedSpecs.design || 'Design Autoral Exclusivo DIVINIUM — Linha Atemporal';
    document.getElementById('specAutenticidade').value = parsedSpecs.autenticidade || 'Certificado de Garantia de Fábrica e Nota Fiscal Eletrônica Inclusos';
    document.getElementById('specDisponibilidade').value = parsedSpecs.disponibilidade || 'Pronta Entrega com Envio Imediato e Seguro Blindado';
    document.getElementById('prodDimensions').value = group.dimensions || 'Diâmetro regulável • Aro anatômico';
    document.getElementById('prodColors').value = group.colors || '';
    document.getElementById('prodVariants').value = group.variants_json || '';

    currentCustomSpecs = Array.isArray(parsedSpecs.custom_specs) ? parsedSpecs.custom_specs : [];
    renderCustomSpecs();

    // 5. Exibição Pública
    document.getElementById('prodCardBehavior').value = group.card_click_behavior || 'default';
    toggleCustomUrlGroup(group.card_click_behavior || 'default');
    document.getElementById('prodCustomTargetUrl').value = group.custom_target_url || '';
    document.getElementById('prodDetailLayout').value = group.detail_layout || 'default';

    document.getElementById('prodShowBadge').checked = group.show_badge != 0;
    document.getElementById('prodShowSku').checked = group.show_sku != 0;
    document.getElementById('prodShowSpecs').checked = group.show_specs != 0;
    document.getElementById('prodShowDescription').checked = group.show_description != 0;
    document.getElementById('prodShowChannels').checked = group.show_channels != 0;

    // 6. Canais de Venda
    currentProductLinks = (group.links || []).map(l => ({
        platform_name: l.platform_name || 'Shopee',
        url: l.url || '',
        price: l.price_cents ? ((l.price_cents) / 100).toFixed(2).replace('.', ',') : priceStr,
        button_text: l.button_text || '',
        button_color: l.button_color || 'default',
        button_size: l.button_size || 'md',
        target: l.target || '_blank',
        is_active: l.is_active !== undefined ? l.is_active : 1,
        sort_order: l.sort_order || 0
    }));
    renderPlatformLinks();

    // 7. Variações & Pedras
    currentProductVariants = (group.variants || []).map(v => ({
        id: v.id || 0,
        sku: v.sku || '',
        stone_mineral: v.stone_mineral || '',
        price: v.price_cents ? ((v.price_cents) / 100).toFixed(2).replace('.', ',') : priceStr,
        promotional_price: v.promotional_price_cents ? ((v.promotional_price_cents) / 100).toFixed(2).replace('.', ',') : '',
        stock_quantity: v.stock_quantity !== undefined ? v.stock_quantity : 10,
        stock_status: v.stock_status || 'in_stock',
        image_url: v.image_url || group.image_url || '/assets/images/hero/featured-green-shell.jpg',
        external_url: v.external_url || ''
    }));

    if (currentProductVariants.length === 0) {
        currentProductVariants = [{
            id: group.id || 0,
            sku: group.base_sku || '',
            stone_mineral: group.stone_mineral || 'Gema Mineral Base',
            price: priceStr,
            promotional_price: promoStr,
            stock_quantity: group.stock_quantity !== undefined ? group.stock_quantity : (group.total_stock || 10),
            stock_status: group.stock_status || 'in_stock',
            image_url: group.image_url || '/assets/images/hero/featured-green-shell.jpg',
            external_url: ''
        }];
    }
    renderModalVariants();

    // 8. Estoque & Identificação
    document.getElementById('prodStockQuantity').value = group.stock_quantity !== undefined ? group.stock_quantity : (group.total_stock || 10);
    document.getElementById('prodStockStatus').value = group.stock_status || 'in_stock';
    updateConsolidatedStockUI();

    switchProductTab('general');
    document.getElementById('productModalTitle').innerText = 'Editar: ' + group.name;
    document.getElementById('productModal').classList.add('active');
}

function addNewVariantToGroup(group) {
    editGroupProduct(group);
    switchProductTab('variants');
    addModalVariantRow();
}

function closeProductModal() {
    document.getElementById('productModal').classList.remove('active');
}

/* ============================================================== */
/* GESTÃO DINÂMICA DE VARIAÇÕES NO MODAL                         */
/* ============================================================== */
function renderModalVariants() {
    const container = document.getElementById('modalVariantsContainer');
    const badge = document.getElementById('modalVariantsCountBadge');
    if (!container) return;

    if (badge) badge.innerText = currentProductVariants.length;
    container.innerHTML = '';

    currentProductVariants.forEach((v, idx) => {
        const row = document.createElement('div');
        row.style.background = 'rgba(255,255,255,0.03)';
        row.style.border = '1px solid rgba(255,255,255,0.08)';
        row.style.borderRadius = '8px';
        row.style.padding = '12px 14px';

        row.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 6px;">
                <span style="font-size: 0.8rem; font-weight: 600; color: #FFFFFF;">
                    💎 Variação #${idx + 1} ${v.sku ? '— ' + v.sku : ''}
                </span>
                ${currentProductVariants.length > 1 ? `
                    <button type="button" onclick="removeModalVariantRow(${idx})" style="background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #f87171; border-radius: 4px; padding: 2px 8px; font-size: 0.72rem; cursor: pointer;">
                        Remover
                    </button>
                ` : ''}
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                <div>
                    <label style="font-size: 0.75rem; color: #9e978e; display: block; margin-bottom: 2px;">Gema / Mineral *</label>
                    <input type="text" class="form-input" value="${v.stone_mineral || ''}" placeholder="Ex: Concha Verde Esmeralda" oninput="updateModalVariantField(${idx}, 'stone_mineral', this.value)" style="padding: 6px 10px; font-size: 0.8rem;">
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: #9e978e; display: block; margin-bottom: 2px;">SKU da Variação</label>
                    <input type="text" class="form-input" value="${v.sku || ''}" placeholder="Ex: AN-RD-25-ESM" oninput="updateModalVariantField(${idx}, 'sku', this.value)" style="padding: 6px 10px; font-size: 0.8rem; font-family: monospace;">
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                <div>
                    <label style="font-size: 0.75rem; color: #9e978e; display: block; margin-bottom: 2px;">Preço (R$)</label>
                    <input type="text" class="form-input" value="${v.price || '380,00'}" onkeyup="formatCurrencyLive(this)" oninput="updateModalVariantField(${idx}, 'price', this.value)" style="padding: 6px 10px; font-size: 0.8rem;">
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: #9e978e; display: block; margin-bottom: 2px;">Preço Promocional</label>
                    <input type="text" class="form-input" value="${v.promotional_price || ''}" onkeyup="formatCurrencyLive(this)" oninput="updateModalVariantField(${idx}, 'promotional_price', this.value)" placeholder="Opcional" style="padding: 6px 10px; font-size: 0.8rem;">
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: #9e978e; display: block; margin-bottom: 2px;">Estoque Físico</label>
                    <input type="number" min="0" class="form-input" value="${v.stock_quantity !== undefined ? v.stock_quantity : 10}" oninput="updateModalVariantField(${idx}, 'stock_quantity', this.value)" style="padding: 6px 10px; font-size: 0.8rem;">
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: #9e978e; display: block; margin-bottom: 2px;">Status</label>
                    <select class="form-select" onchange="updateModalVariantField(${idx}, 'stock_status', this.value)" style="padding: 6px 10px; font-size: 0.8rem;">
                        <option value="in_stock" ${v.stock_status === 'in_stock' ? 'selected' : ''}>Pronta Entrega</option>
                        <option value="made_to_order" ${v.stock_status === 'made_to_order' ? 'selected' : ''}>Sob Encomenda</option>
                        <option value="out_of_stock" ${v.stock_status === 'out_of_stock' ? 'selected' : ''}>Esgotado</option>
                    </select>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div>
                    <label style="font-size: 0.75rem; color: #9e978e; display: block; margin-bottom: 2px;">URL da Foto Específica</label>
                    <input type="text" class="form-input" value="${v.image_url || ''}" placeholder="/assets/images/..." oninput="updateModalVariantField(${idx}, 'image_url', this.value)" style="padding: 6px 10px; font-size: 0.8rem;">
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: #9e978e; display: block; margin-bottom: 2px;">Link Shopee Específico</label>
                    <input type="url" class="form-input" value="${v.external_url || ''}" placeholder="https://shopee.com.br/..." oninput="updateModalVariantField(${idx}, 'external_url', this.value)" style="padding: 6px 10px; font-size: 0.8rem;">
                </div>
            </div>
        `;
        container.appendChild(row);
    });
}

function addModalVariantRow() {
    const baseSku = document.getElementById('prodSku')?.value || 'DIV-NOVA';
    const nextNum = currentProductVariants.length + 1;
    currentProductVariants.push({
        id: 0,
        sku: baseSku + '-VAR' + nextNum,
        stone_mineral: '',
        price: document.getElementById('prodPrice')?.value || '380,00',
        promotional_price: '',
        stock_quantity: 10,
        stock_status: 'in_stock',
        image_url: document.getElementById('prodImageUrl')?.value || '/assets/images/hero/featured-green-shell.jpg',
        external_url: ''
    });
    renderModalVariants();
    updateConsolidatedStockUI();
}

function removeModalVariantRow(idx) {
    if (currentProductVariants.length <= 1) return;
    const item = currentProductVariants[idx];
    if (item && item.id && item.id > 0) {
        deletedVariantIds.push(item.id);
        document.getElementById('prodDeletedVariantIds').value = deletedVariantIds.join(',');
    }
    currentProductVariants.splice(idx, 1);
    renderModalVariants();
    updateConsolidatedStockUI();
}

function updateModalVariantField(idx, field, val) {
    if (currentProductVariants[idx]) {
        currentProductVariants[idx][field] = val;
        if (field === 'stock_quantity') {
            updateConsolidatedStockUI();
        }
    }
}

/* ============================================================== */
/* SUBMISSÃO ATÔMICA DA JOIA & TODAS AS 8 SEÇÕES                 */
/* ============================================================== */
async function handleProductSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('saveProdBtn');
    const origHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span>⏳ Salvando Joia Completa...</span>';

    // 1. Serializar todas as estruturas dinâmicas nos campos ocultos
    document.getElementById('prodLinksJson').value = JSON.stringify(currentProductLinks);
    document.getElementById('prodVariantsJsonInput').value = JSON.stringify(currentProductVariants);
    document.getElementById('prodDeletedVariantIds').value = deletedVariantIds.join(',');
    document.getElementById('prodGalleryJsonInput').value = JSON.stringify(currentProductGallery);
    document.getElementById('prodSpecsJsonInput').value = JSON.stringify(buildSpecsJson());

    const formData = new FormData(document.getElementById('productForm'));
    const csrfToken = getCsrfToken();
    if (csrfToken && !formData.has('_csrf_token')) {
        formData.append('_csrf_token', csrfToken);
    }

    try {
        const response = await fetch('/admin/produtos/salvar', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        if (data.success) {
            showToast(data.message || 'Todas as alterações da joia foram salvas!');
            setTimeout(() => {
                window.location.reload();
            }, 600);
        } else {
            alert(data.error || 'Erro ao gravar produto.');
            btn.disabled = false;
            btn.innerHTML = origHtml;
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
        btn.disabled = false;
        btn.innerHTML = origHtml;
    }
}

/* ============================================================== */
/* CANAIS DE VENDA DINÂMICOS                                      */
/* ============================================================== */
function renderPlatformLinks() {
    const container = document.getElementById('productLinksContainer');
    const badge = document.getElementById('linksCountBadge');
    if (!container) return;

    if (badge) badge.innerText = currentProductLinks.length;
    container.innerHTML = '';

    currentProductLinks.forEach((link, idx) => {
        const item = document.createElement('div');
        item.style.background = 'rgba(255,255,255,0.02)';
        item.style.border = '1px solid rgba(255,255,255,0.06)';
        item.style.borderRadius = '8px';
        item.style.padding = '12px 14px';
        item.style.marginBottom = '10px';

        item.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <strong style="color: #FFFFFF; font-size: 0.82rem;">🔗 Canal #${idx + 1}</strong>
                <button type="button" onclick="removePlatformLink(${idx})" style="background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #f87171; border-radius: 4px; padding: 2px 8px; font-size: 0.72rem; cursor: pointer;">
                    Remover
                </button>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1.5fr 1fr; gap: 10px;">
                <div>
                    <label style="font-size: 0.72rem; color: #8e8880; display: block;">Plataforma</label>
                    <select class="form-select" onchange="currentProductLinks[${idx}].platform_name = this.value; currentProductLinks[${idx}].button_text = 'Comprar no ' + this.value;" style="padding: 5px 8px; font-size: 0.78rem;">
                        <option value="Shopee" ${link.platform_name === 'Shopee' ? 'selected' : ''}>Shopee</option>
                        <option value="Mercado Livre" ${link.platform_name === 'Mercado Livre' ? 'selected' : ''}>Mercado Livre</option>
                        <option value="Amazon" ${link.platform_name === 'Amazon' ? 'selected' : ''}>Amazon</option>
                        <option value="WhatsApp" ${link.platform_name === 'WhatsApp' ? 'selected' : ''}>WhatsApp Concierge</option>
                        <option value="Compra Direta DIVINIUM" ${link.platform_name === 'Compra Direta DIVINIUM' ? 'selected' : ''}>Compra Direta DIVINIUM</option>
                        <option value="Outro" ${link.platform_name === 'Outro' ? 'selected' : ''}>Outro Canal</option>
                    </select>
                </div>
                <div>
                    <label style="font-size: 0.72rem; color: #8e8880; display: block;">Link Oficial (URL)</label>
                    <input type="url" class="form-input" value="${link.url || ''}" placeholder="https://..." oninput="currentProductLinks[${idx}].url = this.value" style="padding: 5px 8px; font-size: 0.78rem;">
                </div>
                <div>
                    <label style="font-size: 0.72rem; color: #8e8880; display: block;">Preço no Canal (R$)</label>
                    <input type="text" class="form-input" value="${link.price || '380,00'}" onkeyup="formatCurrencyLive(this)" oninput="currentProductLinks[${idx}].price = this.value" style="padding: 5px 8px; font-size: 0.78rem;">
                </div>
            </div>
        `;
        container.appendChild(item);
    });
}

function addNewPlatformLink() {
    const currentPrice = document.getElementById('prodPrice')?.value || '380,00';
    currentProductLinks.push({
        platform_name: 'Shopee',
        url: '',
        price: currentPrice,
        button_text: 'Comprar na Shopee Oficial',
        button_color: 'default',
        button_size: 'md',
        target: '_blank',
        is_active: 1,
        sort_order: currentProductLinks.length
    });
    renderPlatformLinks();
}

function removePlatformLink(idx) {
    currentProductLinks.splice(idx, 1);
    renderPlatformLinks();
}

/* ============================================================== */
/* UTILITÁRIOS, BLING & FILTRAGEM                                 */
/* ============================================================== */
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
}

function formatCurrencyLive(input) {
    let v = input.value.replace(/\D/g, '');
    if (!v) { input.value = ''; return; }
    v = (parseInt(v, 10) / 100).toFixed(2) + '';
    v = v.replace('.', ',');
    v = v.replace(/(\d)(\d{3})(\d{3}),/g, '$1.$2.$3,');
    v = v.replace(/(\d)(\d{3}),/g, '$1.$2,');
    input.value = v;
}

function handleNameInput(el) {
    const slugInput = document.getElementById('prodSlug');
    const skuInput = document.getElementById('prodSku');
    if (slugInput && !slugInput.dataset.manual) {
        slugInput.value = el.value.toLowerCase().trim()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }
    if (skuInput && !skuInput.dataset.manual && !skuInput.value) {
        const clean = el.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        skuInput.value = 'DIV-' + (clean.slice(0, 8) || 'JOIA');
    }
}

function handleSlugInput(el) {
    el.dataset.manual = '1';
}

function toggleCustomUrlGroup(val) {
    const group = document.getElementById('customTargetUrlGroup');
    if (group) group.style.display = (val === 'custom') ? 'block' : 'none';
}

function filterCatalog(resetPage = true) {
    if (resetPage) currentPage = 1;
    const term = (document.getElementById('catalogSearchInput')?.value || '').toLowerCase().trim();
    const cat = (document.getElementById('catalogCategoryFilter')?.value || '').trim();
    const status = (document.getElementById('catalogStatusFilter')?.value || '').trim();
    const stock = (document.getElementById('catalogStockFilter')?.value || '').trim();

    const rows = Array.from(document.querySelectorAll('#catalogTableBody tr.group-row'));
    let matched = 0;

    rows.forEach(r => {
        const rName = r.getAttribute('data-name') || '';
        const rSku = r.getAttribute('data-sku') || '';
        const rStone = r.getAttribute('data-stone') || '';
        const rCat = r.getAttribute('data-category') || '';
        const rStatus = r.getAttribute('data-status') || '';
        const rStock = r.getAttribute('data-stock') || '';

        const matchTerm = !term || rName.includes(term) || rSku.includes(term) || rStone.includes(term);
        const matchCat = !cat || rCat === cat;
        const matchStatus = !status || rStatus === status;
        const matchStock = !stock || (stock === 'in_stock' ? (rStock === 'in_stock' || rStock === 'low_stock') : rStock === stock);

        if (matchTerm && matchCat && matchStatus && matchStock) {
            r.dataset.matched = '1';
            matched++;
        } else {
            r.dataset.matched = '0';
        }
    });

    const countEl = document.getElementById('visibleItemsCount');
    if (countEl) countEl.innerText = matched;

    renderPagination(matched);
}

function renderPagination(totalMatched) {
    const matchedRows = Array.from(document.querySelectorAll('#catalogTableBody tr.group-row')).filter(r => r.dataset.matched === '1');
    const totalPages = Math.ceil(totalMatched / itemsPerPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;

    const startIdx = (currentPage - 1) * itemsPerPage;
    const endIdx = startIdx + itemsPerPage;

    Array.from(document.querySelectorAll('#catalogTableBody tr.group-row')).forEach(r => {
        r.style.display = 'none';
        const sku = r.getAttribute('data-sku');
        const drawer = document.getElementById('drawer-group-' + sku);
        if (drawer) drawer.classList.remove('active');
        r.classList.remove('is-expanded');
    });

    matchedRows.slice(startIdx, endIdx).forEach(r => {
        r.style.display = '';
    });

    const infoText = totalMatched > 0 
        ? `Exibindo ${startIdx + 1} a ${Math.min(endIdx, totalMatched)} de ${totalMatched} modelos`
        : 'Nenhum modelo encontrado';

    document.querySelectorAll('.pagination-info').forEach(el => el.innerText = infoText);
}

function changeItemsPerPage(val) {
    itemsPerPage = parseInt(val, 10) || 25;
    filterCatalog();
}

async function duplicateProduct(id) {
    if (!confirm('Deseja criar uma cópia desta joia com todas as suas variações e canais configurados?')) return;
    const formData = new FormData();
    formData.append('id', id);
    const csrf = getCsrfToken();
    if (csrf) formData.append('_csrf_token', csrf);

    try {
        const res = await fetch('/admin/produtos/duplicar', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf }
        });
        const data = await res.json();
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.error || 'Erro ao duplicar.');
        }
    } catch (e) {
        alert('Erro ao comunicar com o servidor: ' + e.message);
    }
}

async function toggleProductStatus(id) {
    const formData = new FormData();
    formData.append('id', id);
    const csrf = getCsrfToken();
    if (csrf) formData.append('_csrf_token', csrf);

    try {
        const res = await fetch('/admin/produtos/toggle', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf }
        });
        const data = await res.json();
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.error || 'Erro ao alterar status.');
        }
    } catch (e) {
        alert('Erro ao alterar status: ' + e.message);
    }
}

async function deleteProduct(id) {
    if (!confirm('Tem certeza de que deseja excluir esta joia e todas as suas variações e canais vinculados?')) return;
    const formData = new FormData();
    formData.append('id', id);
    const csrf = getCsrfToken();
    if (csrf) formData.append('_csrf_token', csrf);

    try {
        const res = await fetch('/admin/produtos/excluir', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf }
        });
        const data = await res.json();
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.error || 'Erro ao excluir.');
        }
    } catch (e) {
        alert('Erro ao excluir: ' + e.message);
    }
}

function openBlingModal(e) {
    if (e) e.preventDefault();
    document.getElementById('blingModal').classList.add('active');
}
function closeBlingModal() {
    document.getElementById('blingModal').classList.remove('active');
}
function switchBlingTab(tab) {
    document.getElementById('blingTabSync').style.display = tab === 'sync' ? 'block' : 'none';
    document.getElementById('blingTabConfig').style.display = tab === 'config' ? 'block' : 'none';
    document.getElementById('blingTabHelp').style.display = tab === 'help' ? 'block' : 'none';
    document.getElementById('tabBtnSync').classList.toggle('active', tab === 'sync');
    document.getElementById('tabBtnConfig').classList.toggle('active', tab === 'config');
    document.getElementById('tabBtnHelp').classList.toggle('active', tab === 'help');
}
function toggleSecretVisibility() {
    const input = document.getElementById('blingClientSecret');
    input.type = input.type === 'password' ? 'text' : 'password';
}
function copyRedirectUri() {
    const input = document.getElementById('blingRedirectUri');
    navigator.clipboard.writeText(input.value).then(() => {
        showToast('Link de redirecionamento copiado!');
    });
}

async function triggerBlingSync(e, mode = 'auto') {
    if (e) e.preventDefault();
    const btn = document.getElementById('syncBlingBtnTable');
    if (btn) btn.classList.add('is-syncing');

    try {
        const formData = new FormData();
        formData.append('mode', mode);
        const csrf = getCsrfToken();
        if (csrf) formData.append('_csrf_token', csrf);

        const res = await fetch('/admin/bling/sync', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf }
        });
        const data = await res.json();
        if (data.success) {
            showToast(data.message || 'Catálogo e estoque sincronizados!');
            setTimeout(() => window.location.reload(), 900);
        } else {
            alert(data.error || 'Erro ao sincronizar com Bling.');
            if (btn) btn.classList.remove('is-syncing');
        }
    } catch (err) {
        alert('Erro de conexão: ' + err.message);
        if (btn) btn.classList.remove('is-syncing');
    }
}

async function handleBlingConfigSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('saveBlingConfigBtn');
    btn.disabled = true;
    btn.innerText = 'Salvando...';

    const formData = new FormData(document.getElementById('blingConfigForm'));
    const csrf = getCsrfToken();
    if (csrf) formData.append('_csrf_token', csrf);

    try {
        const res = await fetch('/admin/bling/config', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf }
        });
        const data = await res.json();
        if (data.success) {
            showToast('Credenciais Bling salvas com sucesso!');
            setTimeout(() => window.location.reload(), 900);
        } else {
            alert(data.error || 'Erro ao salvar credenciais.');
            btn.disabled = false;
            btn.innerText = 'Salvar Credenciais';
        }
    } catch (err) {
        alert('Erro ao conectar: ' + err.message);
        btn.disabled = false;
        btn.innerText = 'Salvar Credenciais';
    }
}

// Inicialização com suporte a tabs e hash
document.addEventListener('DOMContentLoaded', () => {
    filterCatalog();

    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    if (tabParam && ['catalogo', 'exibicao', 'bling'].includes(tabParam)) {
        switchMainTab(tabParam);
    } else if (window.location.hash === '#bling') {
        switchMainTab('bling');
    } else if (window.location.hash === '#exibicao') {
        switchMainTab('exibicao');
    } else if (window.location.hash === '#novo') {
        openNewProductModal();
    }
});
</script>