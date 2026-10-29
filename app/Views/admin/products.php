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

/* Miniatura da Foto Principal na Tabela de Joias */
.table-img-thumb {
    width: 42px;
    height: 42px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid rgba(255, 255, 255, 0.12);
    background: #141414;
    display: inline-block;
    vertical-align: middle;
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.35);
}
.table-img-thumb:hover {
    transform: scale(1.1);
    border-color: rgba(212, 186, 136, 0.5);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
}

/* Barra de Rolagem Horizontal Superior Sincronizada */
.table-scroll-top-wrapper {
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    height: 14px;
    background: rgba(255, 255, 255, 0.03);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}
.table-scroll-top-track {
    height: 1px;
}
.table-scroll-top-wrapper::-webkit-scrollbar,
.table-scroll-container::-webkit-scrollbar {
    height: 10px;
    width: 8px;
}
.table-scroll-top-wrapper::-webkit-scrollbar-track,
.table-scroll-container::-webkit-scrollbar-track {
    background: #141312;
}
.table-scroll-top-wrapper::-webkit-scrollbar-thumb,
.table-scroll-container::-webkit-scrollbar-thumb {
    background: rgba(167, 173, 180, 0.45);
    border-radius: 6px;
    border: 2px solid #141312;
}
.table-scroll-top-wrapper::-webkit-scrollbar-thumb:hover,
.table-scroll-container::-webkit-scrollbar-thumb:hover {
    background: rgba(212, 186, 136, 0.85);
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
        <span>💎 Catálogo & Grupos de Joias</span>
        <span class="tab-badge"><?= count($groups) ?> Modelos</span>
    </button>
    <button type="button" class="unified-hub-tab-btn <?= $activeTab === 'exibicao' ? 'active' : '' ?>" id="mainTabBtnExibicao" onclick="switchMainTab('exibicao')">
        <span>📋 Informações Públicas, Ficha Técnica & Canais</span>
        <span class="tab-badge" style="background: rgba(212, 186, 136, 0.25); color: #FFFFFF;">PÚBLICO</span>
    </button>
    <button type="button" class="unified-hub-tab-btn <?= $activeTab === 'bling' ? 'active' : '' ?>" id="mainTabBtnBling" onclick="switchMainTab('bling')">
        <span>📦 Estoque & Integração Bling ERP</span>
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

        <!-- BARRA DE ROLAGEM HORIZONTAL SUPERIOR SINCRONIZADA -->
        <div class="table-scroll-top-wrapper" id="catalogScrollTopWrapper">
            <div class="table-scroll-top-track" id="catalogScrollTopTrack"></div>
        </div>

        <!-- CONTAINER DA TABELA -->
        <div class="table-scroll-container" id="catalogTableScrollContainer">
            <table class="admin-table" id="productsTable">
                <thead>
                    <tr>
                        <th style="width: 44px; text-align: center;" title="Expandir variações vinculadas">Var.</th>
                        <th style="width: 54px; text-align: center;">Foto</th>
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
                                <td style="text-align: center; width: 54px;">
                                    <img src="<?= \App\Core\View::e($grp['image_url'] ?: '/assets/images/hero/featured-green-shell.jpg') ?>" 
                                         alt="<?= \App\Core\View::e($grp['name']) ?>" 
                                         class="table-img-thumb"
                                         onclick="toggleVariationsDrawer('<?= \App\Core\View::e($grp['base_sku']) ?>')"
                                         style="width: 42px; height: 42px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(255,255,255,0.12); cursor: pointer;"
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

                                <!-- CANAIS DE VENDA DO MODELO -->
                                <td>
                                    <?php 
                                        $activeLinks = [];
                                        if (!empty($grp['links']) && is_array($grp['links'])) {
                                            foreach ($grp['links'] as $lnk) {
                                                $u = trim((string)($lnk['url'] ?? ''));
                                                $isAct = isset($lnk['is_active']) ? ((int)$lnk['is_active'] === 1) : true;
                                                if ($u !== '' && $isAct) {
                                                    $activeLinks[] = $lnk;
                                                }
                                            }
                                        }
                                    ?>
                                    <div class="model-channels-cell" id="model-channels-cell-<?= $grp['id'] ?>">
                                        <div class="platform-links-list" id="model-links-tags-<?= $grp['id'] ?>">
                                            <?php if (!empty($activeLinks)): ?>
                                                <?php foreach ($activeLinks as $lnk): ?>
                                                    <?php 
                                                        $platName = trim((string)($lnk['platform_name'] ?? 'Canal'));
                                                        $rawSlug = strtolower(preg_replace('/[^a-z0-9]/', '', $platName));
                                                        $platSlug = 'outro';
                                                        if (str_contains($rawSlug, 'shopee')) $platSlug = 'shopee';
                                                        elseif (str_contains($rawSlug, 'mercado') || str_contains($rawSlug, 'ml')) $platSlug = 'mercadolivre';
                                                        elseif (str_contains($rawSlug, 'amazon')) $platSlug = 'amazon';
                                                        elseif (str_contains($rawSlug, 'tiktok')) $platSlug = 'tiktok';
                                                        elseif (str_contains($rawSlug, 'whatsapp') || str_contains($rawSlug, 'wa')) $platSlug = 'whatsapp';
                                                        elseif (str_contains($rawSlug, 'diret') || str_contains($rawSlug, 'site') || str_contains($rawSlug, 'checkout')) $platSlug = 'compradireta';
                                                        elseif (str_contains($rawSlug, 'pix') || str_contains($rawSlug, 'asaas')) $platSlug = 'pix';
                                                        elseif (str_contains($rawSlug, 'instagram')) $platSlug = 'instagram';
                                                        elseif (str_contains($rawSlug, 'enjoei')) $platSlug = 'enjoei';
                                                        elseif (str_contains($rawSlug, 'elo7')) $platSlug = 'elo7';

                                                        $linkPriceFormatted = !empty($lnk['price_cents']) ? number_format($lnk['price_cents'] / 100, 2, ',', '.') : '';
                                                        $rawLinkUrl = trim((string)($lnk['url'] ?? ''));
                                                        $linkUrl = ($rawLinkUrl !== '' && !str_starts_with($rawLinkUrl, '/') && !preg_match('#^https?://#i', $rawLinkUrl)) ? ('https://' . $rawLinkUrl) : $rawLinkUrl;
                                                        $targetAttr = !empty($lnk['target']) && $lnk['target'] === '_self' ? '_self' : '_blank';
                                                        $linkBadge = !empty($lnk['badge']) ? ' [' . $lnk['badge'] . ']' : '';
                                                    ?>
                                                    <a href="<?= \App\Core\View::e($linkUrl) ?>" target="<?= $targetAttr ?>" rel="noopener noreferrer" 
                                                       class="platform-mini-tag tag-<?= $platSlug ?>" 
                                                       title="<?= \App\Core\View::e(($lnk['button_text'] ?? $platName) . $linkBadge) ?><?= $linkPriceFormatted ? ' — R$ ' . $linkPriceFormatted : '' ?>">
                                                        <span class="channel-logo-icon"><?= \App\Core\View::getPlatformLogoSvg($platName, 15) ?></span>
                                                        <span class="channel-tag-name"><?= \App\Core\View::e($platName) ?></span>
                                                        <span class="channel-tag-arrow">↗</span>
                                                    </a>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span style="color: #6b655c; font-size: 0.74rem;">Nenhum canal ativo</span>
                                            <?php endif; ?>
                                        </div>

                                        <button type="button" class="btn-channel-quick-edit" 
                                                data-base-sku="<?= \App\Core\View::e($grp['base_sku']) ?>" 
                                                data-group-id="<?= (int)$grp['id'] ?>"
                                                onclick="openModelChannelsBySku(this.getAttribute('data-base-sku'), this.getAttribute('data-group-id'))" 
                                                title="Configurar canais de venda para <?= \App\Core\View::e($grp['name']) ?>">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                                            <span>Editar Canais</span>
                                        </button>
                                    </div>
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
                                        <button class="btn-table-action" onclick='editGroupProduct(<?= $groupJson ?>)' title="Editar modelo, dados do grupo e variações">
                                            Editar Joia
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
                                                        <td style="text-align: center; width: 44px;">
                                                            <img src="<?= \App\Core\View::e($v['image_url'] ?: $grp['image_url']) ?>" 
                                                                 alt="<?= \App\Core\View::e($v['stone_mineral'] ?? '') ?>" 
                                                                 style="width: 28px; height: 28px; object-fit: cover; border-radius: 4px; border: 1px solid rgba(255,255,255,0.12); display: inline-block; vertical-align: middle;"
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
<!-- ABA 2: INFORMAÇÕES PÚBLICAS, FICHA TÉCNICA & CANAIS            -->
<!-- ============================================================== -->
<div class="unified-hub-pane <?= $activeTab === 'exibicao' ? 'active' : '' ?>" id="mainTabExibicao">
    <?php include __DIR__ . '/product_display.php'; ?>
</div>

<!-- ============================================================== -->
<!-- ABA 3: ESTOQUE & INTEGRAÇÃO BLING ERP (API v3)                 -->
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
<div class="modal-overlay" id="productModal">
    <div class="modal-content" style="max-width: 880px; max-height: 92vh; display: flex; flex-direction: column;">
        <div class="modal-header">
            <div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <h3 class="modal-title" id="productModalTitle" style="margin: 0;">Cadastrar Nova Joia</h3>
                    <span class="badge-group-shared" id="modalGroupBadge">GRUPO DO PRODUTO</span>
                </div>
                <p style="font-size: 0.78rem; color: #a8a096; margin: 3px 0 0;">Gestão autoral de características do modelo, variações minerais e canais de venda integrados</p>
            </div>
            <button class="close-modal-btn" onclick="closeProductModal()">&times;</button>
        </div>

        <!-- ABAS DO MODAL -->
        <div class="modal-tabs">
            <button type="button" class="modal-tab-btn active" id="tabBtnGeneral" onclick="switchProductTab('general')">
                <span>✦ Dados do Grupo</span>
            </button>
            <button type="button" class="modal-tab-btn" id="tabBtnSpecs" onclick="switchProductTab('specs')">
                <span>📋 Ficha Técnica</span>
            </button>
            <button type="button" class="modal-tab-btn" id="tabBtnVariants" onclick="switchProductTab('variants')">
                <span>💎 Variações & Estoque</span>
                <span id="modalVariantsCountBadge" class="tab-badge" style="background: rgba(212, 186, 136, 0.2); color: #FFFFFF; font-size: 0.7rem; padding: 1px 6px; border-radius: 10px; margin-left: 4px;">1</span>
            </button>
            <button type="button" class="modal-tab-btn" id="tabBtnLinks" onclick="switchProductTab('links')">
                <span>🛒 Canais de Compra</span>
                <span id="linksCountBadge" style="background: rgba(212, 186, 136, 0.2); color: #CBD0D6; font-size: 0.7rem; padding: 1px 6px; border-radius: 10px; margin-left: 4px;">0</span>
            </button>
            <button type="button" class="modal-tab-btn" id="tabBtnDisplay" onclick="switchProductTab('display')">
                <span>👁️ Exibição Pública</span>
            </button>
        </div>

        <form id="productForm" enctype="multipart/form-data" onsubmit="handleProductSubmit(event)" style="overflow-y: auto; padding-right: 6px; flex: 1;">
            <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">
            <input type="hidden" name="id" id="prodId" value="">
            <input type="hidden" name="parent_sku" id="prodParentSku" value="">
            <input type="hidden" name="links" id="prodLinksJson" value="[]">
            <input type="hidden" name="variants_data" id="prodVariantsJsonInput" value="[]">
            <input type="hidden" name="deleted_variant_ids" id="prodDeletedVariantIds" value="">

            <!-- SUB-ABA 1: DADOS DO GRUPO (COMPARTILHADOS) -->
            <div id="productTabGeneral" class="modal-tab-pane active">
                <div style="background: rgba(212,186,136,0.06); border: 1px solid rgba(212,186,136,0.2); border-radius: 8px; padding: 10px 14px; margin-bottom: 14px; font-size: 0.8rem; color: #ded7cf;">
                    <strong>✦ Informações Compartilhadas do Grupo:</strong> O nome do modelo, categoria, descrição e URLs são herdadas por todas as variações minerais desta joia.
                </div>

                <div class="form-group">
                    <label for="prodName">Nome do Modelo da Joia *</label>
                    <input type="text" id="prodName" name="name" class="form-input" required placeholder="Ex: Bracelete Caminhos" oninput="handleNameInput(this)">
                </div>

                <div class="form-group">
                    <label for="prodSlug">URL Própria da Joia (Slug / Link Amigável) *</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="color: #9e978e; font-size: 0.8rem; background: rgba(255,255,255,0.04); padding: 8px 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.08); white-space: nowrap;">
                            divinium.com.br/produto/
                        </span>
                        <input type="text" id="prodSlug" name="slug" class="form-input" placeholder="bracelete-caminhos" oninput="handleSlugInput(this)">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodSku">SKU Base do Modelo *</label>
                        <input type="text" id="prodSku" name="sku" class="form-input" placeholder="Ex: DIV-BRAC-CAM (auto gerado se vazio)">
                    </div>

                    <div class="form-group">
                        <label for="prodCategory">Categoria / Coleção</label>
                        <select id="prodCategory" name="category_id" class="form-select">
                            <option value="">Selecione uma categoria...</option>
                            <?php foreach ($categories ?? [] as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= \App\Core\View::e($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- PREÇOS & ESTOQUE DO MODELO -->
                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodPrice">Preço Base de Venda Oficial (R$) *</label>
                        <input type="text" id="prodPrice" name="price" class="form-input" required placeholder="Ex: 40,00" onkeyup="formatCurrencyLive(this)">
                    </div>

                    <div class="form-group">
                        <label for="prodPromotionalPrice">Preço Promocional / Comparativo (R$)</label>
                        <input type="text" id="prodPromotionalPrice" name="promotional_price" class="form-input" placeholder="Opcional (Ex: 35,00)" onkeyup="formatCurrencyLive(this)">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodMineral">Gema Mineral Base / Principal</label>
                        <input type="text" id="prodMineral" name="stone_mineral" class="form-input" placeholder="Ex: Concha Verde Esmeralda">
                    </div>

                    <div class="form-group">
                        <label for="prodStockQuantity">Estoque Físico do Modelo</label>
                        <input type="number" id="prodStockQuantity" name="stock_quantity" class="form-input" min="0" value="10">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodStockStatus">Disponibilidade do Estoque</label>
                        <select id="prodStockStatus" name="stock_status" class="form-select">
                            <option value="in_stock">Pronta Entrega</option>
                            <option value="made_to_order">Sob Encomenda</option>
                            <option value="out_of_stock">Esgotado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="prodSortOrder">Ordem de Exibição na Loja</label>
                        <input type="number" id="prodSortOrder" name="sort_order" class="form-input" value="0" placeholder="0">
                    </div>
                </div>

                <!-- FOTO PRINCIPAL (CAPA DO MODELO) -->
                <div class="form-group" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.07); padding: 14px 16px; border-radius: 8px;">
                    <label style="color: #ded7cf; font-weight: 600; margin-bottom: 8px; display: block; font-size: 0.84rem;">
                        Foto Principal da Joia (Capa do Modelo)
                    </label>
                    <div style="display: flex; gap: 14px; align-items: flex-start;">
                        <img id="imagePreviewBox" src="/assets/images/hero/featured-green-shell.jpg" alt="Preview" 
                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(212,186,136,0.3); background: #121212; flex-shrink: 0;"
                             onerror="this.src='/assets/images/hero/featured-green-shell.jpg'">
                        <div style="flex: 1; display: flex; flex-direction: column; gap: 8px;">
                            <input type="text" id="prodImageUrl" name="image_url" class="form-input" placeholder="/assets/images/products/... ou URL externa" oninput="updateImagePreview(this.value)">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <label class="btn-admin-secondary" style="margin: 0; padding: 6px 14px; font-size: 0.78rem; cursor: pointer;">
                                    <span>📁 Fazer Upload de Imagem</span>
                                    <input type="file" id="prodImageFile" name="image_file" accept="image/*" style="display: none;" onchange="handleImageFileUpload(this)">
                                </label>
                                <span id="imageFileName" style="font-size: 0.75rem; color: #8e8880;">Nenhum arquivo selecionado</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Status na Boutique</label>
                    <div class="status-radio-group">
                        <label class="status-radio-label">
                            <input type="radio" name="status" id="statusPublished" value="published" checked>
                            <span style="color: #FFFFFF;">● Publicado</span>
                        </label>
                        <label class="status-radio-label">
                            <input type="radio" name="status" id="statusDraft" value="draft">
                            <span style="color: #facc15;">● Rascunho</span>
                        </label>
                        <label class="status-radio-label">
                            <input type="radio" name="status" id="statusHidden" value="hidden">
                            <span style="color: #9ca3af;">● Oculto</span>
                        </label>
                    </div>
                </div>

                <div class="form-checkbox-row" style="margin-bottom: 14px;">
                    <input type="checkbox" id="prodFeatured" name="is_featured" value="1" style="width: 18px; height: 18px; accent-color: #737B85;">
                    <label for="prodFeatured" style="margin: 0; cursor: pointer; color: #ded7cf;">Destacar este modelo no topo da Vitrine Principal</label>
                </div>

                <div class="form-group">
                    <label for="prodShortDesc">Breve Resumo (1 linha)</label>
                    <input type="text" id="prodShortDesc" name="short_description" class="form-input" placeholder="Ex: Joia autoral contemporânea com acabamento artesanal e pedras minerais nobres.">
                </div>

                <div class="form-group">
                    <label for="prodDesc">Descrição Completa da Peça</label>
                    <textarea id="prodDesc" name="description" class="form-textarea" rows="4" placeholder="Descreva os detalhes da manufatura, inspiração autoral, autenticidade e cuidados..."></textarea>
                </div>
            </div>

            <!-- SUB-ABA 2: FICHA TÉCNICA DO MODELO (COMPARTILHADA) -->
            <div id="productTabSpecs" class="modal-tab-pane">
                <div style="background: rgba(212,186,136,0.06); border: 1px solid rgba(212,186,136,0.2); border-radius: 8px; padding: 10px 14px; margin-bottom: 14px; font-size: 0.8rem; color: #ded7cf;">
                    <strong>📋 Especificações do Grupo:</strong> Estes dados alimentam a tabela "Especificações & Manufatura" na página do produto.
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodMaterials">Acabamento Nobre</label>
                        <input type="text" id="prodMaterials" name="materials" class="form-input" placeholder="Ex: Banho de Prata 950 com Verniz Protetor Antialérgico">
                    </div>

                    <div class="form-group">
                        <label for="prodColors">Nuances & Cores</label>
                        <input type="text" id="prodColors" name="colors" class="form-input" placeholder="Ex: Prata Nobre Espelhada, Tons Minerais">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodDimensions">Medidas & Dimensões</label>
                        <input type="text" id="prodDimensions" name="dimensions" class="form-input" placeholder="Ex: Diâmetro 6,5 cm • Aro anatômico ajustável">
                    </div>

                    <div class="form-group">
                        <label for="prodVariants">Tamanhos / Variantes de Medida</label>
                        <input type="text" id="prodVariants" name="variants_json" class="form-input" placeholder="Ex: Tamanho Único Regulável, 16 ao 22">
                    </div>
                </div>
            </div>

            <!-- SUB-ABA 3: GESTOR DE VARIAÇÕES MINERAIS & ESTOQUE [ESPECÍFICO DE CADA VARIAÇÃO] -->
            <div id="productTabVariants" class="modal-tab-pane">
                <div style="background: rgba(167, 173, 180, 0.08); border: 1px solid rgba(167, 173, 180, 0.25); border-radius: 8px; padding: 12px 16px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <div>
                        <strong style="color: #FFFFFF; font-size: 0.88rem;">💎 Variações Minerais & Estoques Individuais</strong>
                        <p style="margin: 3px 0 0; font-size: 0.78rem; color: #b5ada3;">
                            Cada variação possui sua gema mineral, foto exclusiva, SKU próprio, estoque físico e link de compra independente.
                        </p>
                    </div>
                    <button type="button" class="btn-admin-gold" onclick="addModalVariantRow()" style="padding: 6px 14px; font-size: 0.8rem; white-space: nowrap;">
                        <span>+ Adicionar Variação</span>
                    </button>
                </div>

                <!-- CONTAINER DINÂMICO DE VARIAÇÕES -->
                <div id="modalVariantsContainer" style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 14px;"></div>

                <button type="button" class="btn-admin-secondary" onclick="addModalVariantRow()" style="width: 100%; padding: 10px; justify-content: center; font-size: 0.82rem; border-style: dashed;">
                    <span>+ Adicionar Outra Variação Mineral a este Modelo</span>
                </button>
            </div>

            <!-- SUB-ABA 4: CANAIS DE COMPRA POR PRODUTO PRINCIPAL -->
            <div id="productTabLinks" class="modal-tab-pane">
                <div style="background: rgba(212, 186, 136, 0.06); border: 1px solid rgba(212, 186, 136, 0.25); border-radius: 8px; padding: 12px 16px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <div>
                        <strong style="color: #ffffff; font-size: 0.9rem;">🛒 Canais de Compra Exclusivos do Produto Principal (Modelo Pai)</strong>
                        <p style="margin: 3px 0 0; font-size: 0.78rem; color: #b5ada3;">
                            Configure os links e a ativação dos canais de compra para este modelo. A configuração pertence exclusivamente ao modelo pai e não polui as variações.
                        </p>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <button type="button" class="btn-admin-gold" id="btnSaveChannelsDirect" onclick="saveCurrentModelChannels()" style="padding: 7px 14px; font-size: 0.8rem; white-space: nowrap;">
                            <span>💾 Salvar Canais Deste Modelo</span>
                        </button>
                    </div>
                </div>

                <!-- CONTAINER DOS CANAIS (ESTRUTURADOS + PERSONALIZADOS) -->
                <div id="productLinksContainer"></div>

                <div style="margin-top: 14px; display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <button type="button" class="btn-add-platform-link" onclick="addNewPlatformLink()" style="margin: 0;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>+ Adicionar Outro Canal Personalizado</span>
                    </button>
                </div>
            </div>

            <!-- SUB-ABA 5: EXIBIÇÃO & VISIBILIDADE PÚBLICA -->
            <div id="productTabDisplay" class="modal-tab-pane">
                <div class="form-group" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.07); padding: 14px 16px; border-radius: 8px;">
                    <label style="color: #CBD0D6; font-weight: 600; margin-bottom: 10px; display: block; font-size: 0.84rem;">
                        👁️ Controles de Visibilidade Pública do Card & Detalhe
                    </label>
                    
                    <div class="form-row-2" style="margin-bottom: 12px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="prodCardBehavior" style="font-size: 0.78rem;">Ao Clicar no Card desta Joia</label>
                            <select id="prodCardBehavior" name="card_click_behavior" class="form-select" onchange="toggleCustomUrlGroup(this.value)">
                                <option value="default">Padrão da Loja (Herança Geral da Boutique)</option>
                                <option value="detail">💎 Abrir Página de Detalhes Interna (/produto/{slug})</option>
                                <option value="external">↗ Abrir 1º Canal Externo Direto</option>
                                <option value="custom">🔗 URL Personalizada Específica</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="prodDetailLayout" style="font-size: 0.78rem;">Disposição dos Botões de Compra</label>
                            <select id="prodDetailLayout" name="detail_layout" class="form-select">
                                <option value="default">Padrão da Boutique</option>
                                <option value="list">Lista Vertical Padronizada</option>
                                <option value="grid">Grade Lado a Lado (2 Colunas)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="customTargetUrlGroup" style="display: none; margin-bottom: 12px;">
                        <label for="prodCustomTargetUrl" style="font-size: 0.78rem;">URL de Destino Personalizada</label>
                        <input type="url" id="prodCustomTargetUrl" name="custom_target_url" class="form-input" placeholder="https://...">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 10px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 10px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.82rem; color: #ede7df;">
                            <input type="hidden" name="show_badge" value="0">
                            <input type="checkbox" id="prodShowBadge" name="show_badge" value="1" checked style="width: 16px; height: 16px; accent-color: #737B85;">
                            <span>Exibir selos flutuantes na foto (Banho de Prata / Sob Encomenda / Canais)</span>
                        </label>

                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.82rem; color: #ede7df;">
                            <input type="hidden" name="show_sku" value="0">
                            <input type="checkbox" id="prodShowSku" name="show_sku" value="1" checked style="width: 16px; height: 16px; accent-color: #737B85;">
                            <span>Exibir código SKU / Referência publicamente no site</span>
                        </label>

                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.82rem; color: #ede7df;">
                            <input type="hidden" name="show_specs" value="0">
                            <input type="checkbox" id="prodShowSpecs" name="show_specs" value="1" checked style="width: 16px; height: 16px; accent-color: #737B85;">
                            <span>Exibir especificações técnicas (Materiais, Medidas e Acabamento)</span>
                        </label>

                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.82rem; color: #ede7df;">
                            <input type="hidden" name="show_description" value="0">
                            <input type="checkbox" id="prodShowDescription" name="show_description" value="1" checked style="width: 16px; height: 16px; accent-color: #737B85;">
                            <span>Exibir descrição resumida da peça no card</span>
                        </label>

                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.82rem; color: #ede7df;">
                            <input type="hidden" name="show_channels" value="0">
                            <input type="checkbox" id="prodShowChannels" name="show_channels" value="1" checked style="width: 16px; height: 16px; accent-color: #737B85;">
                            <span>Exibir painel de canais de compra na página de detalhes</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- BOTÕES DO RODAPÉ DO MODAL -->
            <div class="modal-footer" style="margin-top: 18px; padding-top: 14px; border-top: 1px solid rgba(255,255,255,0.08);">
                <button type="button" class="btn-admin-secondary" onclick="closeProductModal()">Cancelar</button>
                <button type="submit" class="btn-admin-gold" id="saveProdBtn">Salvar Joia & Variações</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL RÁPIDO: GESTÃO DE CANAIS DE VENDA POR MODELO AUTORAL     -->
<!-- ============================================================== -->
<div class="modal-overlay" id="modelChannelsQuickModal" style="display: none; z-index: 10005;">
    <div class="modal-content" style="max-width: 860px; max-height: 90vh; display: flex; flex-direction: column;">
        <div class="modal-header" style="flex-shrink: 0; padding: 18px 24px; border-bottom: 1px solid rgba(255,255,255,0.08);">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(212,186,136,0.15); border: 1px solid rgba(212,186,136,0.3); display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                    🛒
                </div>
                <div>
                    <h3 class="modal-title" id="quickModalModelTitle" style="margin: 0; font-size: 1.15rem; color: #FFFFFF;">Canais de Venda do Modelo</h3>
                    <p style="margin: 4px 0 0; font-size: 0.78rem; color: #a8a096;" id="quickModalModelSubtitle">
                        Configure opções editáveis de canais de venda externos e botões de compra para este modelo autoral.
                    </p>
                </div>
            </div>
            <button type="button" class="close-modal-btn" onclick="closeModelChannelsQuickModal()" style="font-size: 1.4rem; background: transparent; border: none; color: #CBD0D6; cursor: pointer;">&times;</button>
        </div>

        <div class="modal-body" style="overflow-y: auto; padding: 20px 24px; flex: 1;">
            <input type="hidden" id="quickModalProdId" value="0">
            <input type="hidden" id="quickModalBaseSku" value="">

            <div style="background: rgba(212, 186, 136, 0.06); border: 1px solid rgba(212, 186, 136, 0.25); border-radius: 10px; padding: 14px 18px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                <div>
                    <strong style="color: #ffffff; font-size: 0.88rem;">💎 Canais de Venda e Botões de Compra Independentes</strong>
                    <p style="margin: 3px 0 0; font-size: 0.76rem; color: #b5ada3;">
                        Adicione, reordene (↑ / ↓), defina preços por canal ou ative/desative canais para este modelo. As opções são compartilhadas por todas as variações desta joia.
                    </p>
                </div>
                <button type="button" class="btn-admin-gold" onclick="addNewPlatformLink('quickModalLinksContainer')" style="padding: 7px 16px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 6px;">
                    <span>+ Adicionar Canal de Venda</span>
                </button>
            </div>

            <!-- CONTAINER DOS CANAIS -->
            <div id="quickModalLinksContainer"></div>
        </div>

        <div class="modal-footer" style="flex-shrink: 0; padding: 14px 24px; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center;">
            <button type="button" class="btn-admin-secondary" onclick="closeModelChannelsQuickModal()">Fechar</button>
            <button type="button" class="btn-admin-gold" id="btnQuickSaveChannels" onclick="saveQuickModelChannels()">
                <span>💾 Salvar Canais Deste Modelo</span>
            </button>
        </div>
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
// Função utilitária global para sanitização e escape HTML
function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// Catálogo de Modelos Autorais para acesso dinâmico de canais protegido contra quebras de tag script
window.allCatalogGroups = <?= json_encode(array_values($groups), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>;

function openModelChannelsBySku(baseSku, prodId = null) {
    let group = null;
    const allGroups = window.allCatalogGroups || [];
    if (baseSku) {
        const cleanSku = String(baseSku).trim().toUpperCase();
        group = allGroups.find(g => 
            (g.base_sku && String(g.base_sku).trim().toUpperCase() === cleanSku) ||
            (g.sku && String(g.sku).trim().toUpperCase() === cleanSku)
        );
    }
    if (!group && prodId) {
        group = allGroups.find(g => String(g.id) === String(prodId));
    }
    if (group) {
        openModelChannelsQuickModal(group);
    } else {
        alert('Modelo autoral não encontrado (' + (baseSku || prodId) + ').');
    }
}

// Estado Global de Variações e Links do Modal
let currentProductLinks = [];
let currentProductVariants = [];
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

    if (tab === 'catalogo') {
        setTimeout(updateScrollbarSync, 60);
    }

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
    setTimeout(updateScrollbarSync, 60);
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

    if (tab === 'links') {
        renderPlatformLinks('productLinksContainer');
    }
}

function updateImagePreview(url) {
    const box = document.getElementById('imagePreviewBox');
    if (box) {
        box.src = url || '/assets/images/hero/featured-green-shell.jpg';
    }
}

function handleImageFileUpload(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const label = document.getElementById('imageFileName');
        if (label) label.innerText = file.name;
        const reader = new FileReader();
        reader.onload = function(e) {
            updateImagePreview(e.target.result);
        };
        reader.readAsDataURL(file);
    }
}

function openNewProductModal(e) {
    if (e) e.preventDefault();
    document.getElementById('productForm').reset();
    document.getElementById('prodId').value = '';
    document.getElementById('prodParentSku').value = '';
    document.getElementById('prodDeletedVariantIds').value = '';
    deletedVariantIds = [];

    document.getElementById('prodPrice').value = '40,00';
    document.getElementById('prodPromotionalPrice').value = '';
    document.getElementById('prodMineral').value = '';
    document.getElementById('prodStockQuantity').value = '10';
    document.getElementById('prodStockStatus').value = 'in_stock';
    document.getElementById('prodImageUrl').value = '';
    updateImagePreview('/assets/images/hero/featured-green-shell.jpg');
    const fileLabel = document.getElementById('imageFileName');
    if (fileLabel) fileLabel.innerText = 'Nenhum arquivo selecionado';

    document.getElementById('statusPublished').checked = true;
    document.getElementById('prodFeatured').checked = false;
    document.getElementById('prodShowBadge').checked = true;
    document.getElementById('prodShowSku').checked = true;
    document.getElementById('prodShowSpecs').checked = true;
    document.getElementById('prodShowDescription').checked = true;
    document.getElementById('prodShowChannels').checked = true;

    if (document.getElementById('prodCardBehavior')) {
        document.getElementById('prodCardBehavior').value = 'default';
        toggleCustomUrlGroup('default');
    }
    if (document.getElementById('prodCustomTargetUrl')) {
        document.getElementById('prodCustomTargetUrl').value = '';
    }
    if (document.getElementById('prodDetailLayout')) {
        document.getElementById('prodDetailLayout').value = 'default';
    }

    currentProductLinks = [{
        platform_name: 'Shopee',
        url: '',
        price: '40,00',
        button_text: 'Comprar na Shopee Oficial',
        button_color: 'default',
        button_bg_color: '',
        button_text_color: '',
        button_size: 'md',
        target: '_blank',
        is_active: 1,
        sort_order: 0
    }];
    currentProductVariants = [{
        id: 0,
        sku: '',
        stone_mineral: 'Gema Mineral Autoral',
        price: '40,00',
        promotional_price: '',
        stock_quantity: 10,
        stock_status: 'in_stock',
        image_url: '/assets/images/hero/featured-green-shell.jpg',
        external_url: ''
    }];

    renderPlatformLinks();
    renderModalVariants();
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

    document.getElementById('prodName').value = group.name || '';
    document.getElementById('prodSlug').value = group.slug || '';
    document.getElementById('prodSku').value = group.base_sku || '';
    document.getElementById('prodCategory').value = group.category_id || '';

    // Preços & Estoque
    const basePriceFormatted = group.price_cents ? (group.price_cents / 100).toFixed(2).replace('.', ',') : (group.min_price_cents ? (group.min_price_cents / 100).toFixed(2).replace('.', ',') : '40,00');
    document.getElementById('prodPrice').value = basePriceFormatted;
    document.getElementById('prodPromotionalPrice').value = group.promotional_price_cents ? (group.promotional_price_cents / 100).toFixed(2).replace('.', ',') : '';
    document.getElementById('prodMineral').value = group.stone_mineral || '';
    document.getElementById('prodStockQuantity').value = group.stock_quantity !== undefined ? group.stock_quantity : (group.total_stock !== undefined ? group.total_stock : 10);
    document.getElementById('prodStockStatus').value = group.stock_status || 'in_stock';

    // Imagem da Capa
    const imgUrl = group.image_url || '/assets/images/hero/featured-green-shell.jpg';
    document.getElementById('prodImageUrl').value = group.image_url || '';
    updateImagePreview(imgUrl);
    const fileLabel = document.getElementById('imageFileName');
    if (fileLabel) fileLabel.innerText = 'Nenhum arquivo selecionado';

    // Ficha Técnica & Descrição
    document.getElementById('prodMaterials').value = group.materials || '';
    document.getElementById('prodDimensions').value = group.dimensions || '';
    document.getElementById('prodColors').value = group.colors || '';
    document.getElementById('prodVariants').value = group.variants_json || '';
    document.getElementById('prodSortOrder').value = group.sort_order || 0;
    document.getElementById('prodShortDesc').value = group.short_description || '';
    document.getElementById('prodDesc').value = group.description || '';

    const status = group.status || 'published';
    if (status === 'draft') document.getElementById('statusDraft').checked = true;
    else if (status === 'hidden') document.getElementById('statusHidden').checked = true;
    else document.getElementById('statusPublished').checked = true;

    document.getElementById('prodFeatured').checked = group.is_featured == 1;
    document.getElementById('prodShowBadge').checked = group.show_badge != 0;
    document.getElementById('prodShowSku').checked = group.show_sku != 0;
    document.getElementById('prodShowSpecs').checked = group.show_specs != 0;
    document.getElementById('prodShowDescription').checked = group.show_description != 0;
    document.getElementById('prodShowChannels').checked = group.show_channels != 0;

    // Exibição Pública
    if (document.getElementById('prodCardBehavior')) {
        document.getElementById('prodCardBehavior').value = group.card_click_behavior || 'default';
        toggleCustomUrlGroup(group.card_click_behavior || 'default');
    }
    if (document.getElementById('prodCustomTargetUrl')) {
        document.getElementById('prodCustomTargetUrl').value = group.custom_target_url || '';
    }
    if (document.getElementById('prodDetailLayout')) {
        document.getElementById('prodDetailLayout').value = group.detail_layout || 'default';
    }

    // Canais de Compra Exclusivos do Produto Principal (Modelo Pai)
    const fallbackExtUrl = group.parent_product?.external_url || group.external_url || '';
    currentProductLinks = normalizeModelLinks(group.links, basePriceFormatted, fallbackExtUrl);

    // Variações do Grupo
    currentProductVariants = (group.variants || []).map(v => ({
        id: v.id || 0,
        sku: v.sku || '',
        stone_mineral: v.stone_mineral || '',
        price: v.price_cents ? ((v.price_cents) / 100).toFixed(2).replace('.', ',') : basePriceFormatted,
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
            price: basePriceFormatted,
            promotional_price: '',
            stock_quantity: group.total_stock || 10,
            stock_status: 'in_stock',
            image_url: group.image_url || '/assets/images/hero/featured-green-shell.jpg',
            external_url: ''
        }];
    }

    renderPlatformLinks();
    renderModalVariants();
    switchProductTab('general');
    document.getElementById('productModalTitle').innerText = 'Editar: ' + group.name;
    document.getElementById('productModal').classList.add('active');
}

function editProduct(p) {
    if (!p) return;
    if (p.base_sku && p.variants) {
        editGroupProduct(p);
        return;
    }
    const pSku = p.sku || '';
    const parentSku = p.parent_sku || '';
    let targetBase = parentSku;
    if (!targetBase) {
        const parts = pSku.split('-');
        targetBase = parts.length >= 3 ? parts.slice(0, 3).join('-') : pSku;
    }
    const groupRow = document.getElementById('group-row-' + targetBase);
    if (groupRow) {
        const editBtn = groupRow.querySelector('button[title*="Editar"]');
        if (editBtn) {
            editBtn.click();
            return;
        }
    }
    editGroupProduct({
        id: p.id,
        base_sku: targetBase || p.sku,
        name: p.name,
        slug: p.slug,
        category_id: p.category_id,
        category_name: p.category_name,
        price_cents: p.price_cents,
        promotional_price_cents: p.promotional_price_cents,
        stock_quantity: p.stock_quantity,
        stock_status: p.stock_status,
        materials: p.materials,
        dimensions: p.dimensions,
        colors: p.colors,
        variants_json: p.variants_json,
        stone_mineral: p.stone_mineral,
        short_description: p.short_description,
        description: p.description,
        image_url: p.image_url,
        status: p.status,
        sort_order: p.sort_order,
        is_featured: p.is_featured,
        show_badge: p.show_badge,
        show_sku: p.show_sku,
        show_specs: p.show_specs,
        show_description: p.show_description,
        show_channels: p.show_channels,
        card_click_behavior: p.card_click_behavior,
        custom_target_url: p.custom_target_url,
        detail_layout: p.detail_layout,
        links: p.links || [],
        total_stock: p.stock_quantity || 0,
        variants: [p]
    });
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
    const defaultPrice = document.getElementById('prodPrice')?.value || '40,00';
    const defaultImg = document.getElementById('prodImageUrl')?.value || '/assets/images/hero/featured-green-shell.jpg';
    currentProductVariants.push({
        id: 0,
        sku: baseSku + '-VAR' + nextNum,
        stone_mineral: '',
        price: defaultPrice,
        promotional_price: '',
        stock_quantity: 10,
        stock_status: 'in_stock',
        image_url: defaultImg,
        external_url: ''
    });
    renderModalVariants();
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
}

function updateModalVariantField(idx, field, val) {
    if (currentProductVariants[idx]) {
        currentProductVariants[idx][field] = val;
    }
}

/* ============================================================== */
/* SUBMISSÃO ATÔMICA DO PRODUTO & VARIAÇÕES                      */
/* ============================================================== */
async function handleProductSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('saveProdBtn');
    btn.disabled = true;
    btn.innerText = 'Salvando Joia & Variações...';

    // Serializar canais e variações
    document.getElementById('prodLinksJson').value = JSON.stringify(currentProductLinks);
    document.getElementById('prodVariantsJsonInput').value = JSON.stringify(currentProductVariants);
    document.getElementById('prodDeletedVariantIds').value = deletedVariantIds.join(',');

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
            window.location.reload();
        } else {
            alert(data.error || 'Erro ao gravar produto.');
            btn.disabled = false;
            btn.innerText = 'Salvar Joia & Variações';
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
        btn.disabled = false;
        btn.innerText = 'Salvar Joia & Variações';
    }
}

/* ============================================================== */
/* LOGOTIPOS OFICIAIS DOS CANAIS DE VENDA (SVGs VETORIAIS)       */
/* ============================================================== */
function getPlatformLogoSvg(platform, size = 15) {
    const raw = String(platform || '').trim().toLowerCase();
    const s = parseInt(size, 10) || 15;

    if (raw.includes('shopee')) {
        return `<svg class="channel-logo-svg" width="${s}" height="${s}" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><path d="M19 7h-2.2C16.4 4.1 14.5 2 12 2S7.6 4.1 7.2 7H5c-1.1 0-2 .9-2 2l1.3 11.7c.1.9.8 1.6 1.7 1.6h12c.9 0 1.6-.7 1.7-1.6L21 9c0-1.1-.9-2-2-2z" fill="#EE4D2D"/><path d="M8.8 7c.4-2 1.6-3.4 3.2-3.4s2.8 1.4 3.2 3.4H8.8z" stroke="#FFFFFF" stroke-width="1.2" stroke-linecap="round"/><path d="M14.2 12.3c-.2-.7-.7-1.1-1.6-1.3l-.8-.2c-.5-.1-.7-.3-.7-.6s.3-.5.8-.5c.6 0 1.1.2 1.4.5l.6-.7c-.5-.4-1.2-.6-2-.6-1.1 0-1.9.6-1.9 1.5 0 .7.5 1.2 1.4 1.4l.8.2c.6.1.9.3.9.7s-.4.6-1 .6c-.7 0-1.3-.3-1.7-.7l-.6.7c.6.6 1.4.8 2.3.8 1.3 0 2.1-.6 2.1-1.6z" fill="#FFFFFF"/></svg>`;
    }
    if (raw.includes('mercado') || raw.includes('ml') || raw.includes('livre')) {
        return `<svg class="channel-logo-svg" width="${s}" height="${s}" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#FFE600"/><path d="M16.5 11.5c-.5-.5-1.3-.7-2-.7-.4 0-.8.1-1.1.3l-.7-.7c.3-.5.4-1.1.2-1.7-.3-.8-1-1.3-1.8-1.4-.8-.1-1.6.3-2.1.9l-2.2 2.6c-.3.4-.3 1 0 1.4l1.2 1.2c.3.3.7.4 1.1.4.3 0 .7-.1.9-.3l.7-.7c.3.4.8.7 1.4.8.6.1 1.2-.1 1.6-.5l.8-.8c.4.3.9.4 1.4.3.6-.1 1.1-.5 1.3-1.1.1-.3.1-.6 0-.8h.1c.3 0 .6-.2.8-.5.3-.4.2-1-.3-1.3zm-6.2.8l-1.1-1.1 2.1-2.4c.3-.3.7-.5 1.1-.4.4.1.7.3.8.7.1.3 0 .7-.2.9l-2.7 2.3zm2.5 1.1l-.8-.8 1.8-1.5c.2-.2.5-.2.7-.2.2 0 .5.1.7.3.2.2.3.5.2.7 0 .3-.2.5-.4.7l-2.2.8zm2.2-.4l-.8-.8 1.4-1.2c.2-.2.5-.2.7-.1.2.1.4.3.4.5 0 .2 0 .4-.2.6l-1.5 1z" fill="#2D3277"/></svg>`;
    }
    if (raw.includes('tiktok')) {
        return `<svg class="channel-logo-svg" width="${s}" height="${s}" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#000000"/><path d="M17.5 7.2a4.4 4.4 0 0 1-3.2-3.2h-2.5v12.2a2.4 2.4 0 1 1-2.4-2.4c.3 0 .6.1.9.2V11.5a4.9 4.9 0 0 0-.9-.1 4.9 4.9 0 1 0 4.9 4.9V9.8c1.3.9 2.8 1.4 4.4 1.4V8.7c-.4 0-.8-.1-1.2-.3z" fill="#FFFFFF"/><path d="M16.5 8.2a4.4 4.4 0 0 1-3.2-3.2h-.8v1.3a4.4 4.4 0 0 0 3.2 3.2v-1.3z" fill="#00F2FE"/><path d="M12.1 16.2a2.4 2.4 0 0 1-2.4 2.4c-1.3 0-2.4-1.1-2.4-2.4 0-.4.1-.8.3-1.1-.5.7-.8 1.6-.8 2.5a3.8 3.8 0 0 0 3.8 3.8 3.8 3.8 0 0 0 3.8-3.8V11.5h-1.5v4.7h-.8z" fill="#FE2C55"/></svg>`;
    }
    if (raw.includes('amazon')) {
        return `<svg class="channel-logo-svg" width="${s}" height="${s}" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#232F3E"/><path d="M6 14.5c2.5 1.8 6.5 2.5 9.8 1.2.5-.2 1.1.2.7.7-2.3 2.1-6.8 2.4-10.5.5-.4-.2-.4-.8 0-.9l.7-.4c.3-.2.7-.2 1-.1l-.7-.9z" fill="#FF9900"/><path d="M16.8 14.2c-.3-.4-1.8-.2-2.5-.1-.2 0-.3-.2-.1-.3 1.1-.9 2.9-.6 3.2-.2.3.4-.1 2.2-1.1 3-.2.1-.3 0-.3-.2.2-.7.8-1.8.8-2.2z" fill="#FF9900"/><path d="M13.2 8.5c0 1.2.6 1.8 1.5 1.8.7 0 1.2-.4 1.5-.9V8.5h1.4v4.4h-1.3v-.6c-.4.5-1.1.8-1.8.8-1.5 0-2.5-1.1-2.5-2.5 0-1.6 1.1-2.6 2.6-2.6.7 0 1.3.3 1.6.7v-.4c0-.9-.6-1.4-1.5-1.4-.7 0-1.3.3-1.6.7l-.8-.8c.6-.7 1.4-1.1 2.5-1.1 1.7 0 2.8.9 2.8 2.6v3.2h-1.4v-.6c-.4.5-1.1.8-1.8.8-1.5 0-2.5-1-2.5-2.5 0-1.6 1.1-2.6 2.6-2.6z" fill="#FFFFFF" opacity="0.9"/></svg>`;
    }
    if (raw.includes('whatsapp') || raw.includes('wa.me') || raw.includes('consultor') || raw.includes('zap')) {
        return `<svg class="channel-logo-svg" width="${s}" height="${s}" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#25D366"/><path d="M17.5 14.7c-.3-.1-1.6-.8-1.8-.9-.3-.1-.5-.1-.7.1-.2.3-.8.9-1 .1.1-.2.3-.4.3-.7.1-.3.1-1.5.6-2.9 1.8-1.1 1-.8 1.9-.3 2.3.4.4.9.4 1.3.4.3 0 .6 0 .8-.1.2-.1.7-.8.8-1.5.1-.7.1-1.3.1-1.5 0-.2-.2-.3-.5-.4zM12 4.4C7.8 4.4 4.4 7.8 4.4 12c0 1.4.4 2.8 1.1 4L4 20l4.2-1.1c1.2.6 2.5 1 3.8 1 4.2 0 7.6-3.4 7.6-7.6 0-4.2-3.4-7.9-7.6-7.9zm0 13.9c-1.2 0-2.4-.3-3.4-.9l-.2-.1-2.5.7.7-2.4-.2-.3c-.7-1-1.1-2.3-1.1-3.6 0-3.5 2.8-6.3 6.3-6.3 3.5 0 6.3 2.8 6.3 6.3 0 3.5-2.8 6.3-6.3 6.3z" fill="#FFFFFF"/></svg>`;
    }
    if (raw.includes('pix') || raw.includes('asaas')) {
        return `<svg class="channel-logo-svg" width="${s}" height="${s}" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#00BFA5"/><path d="M7.4 7.4l4.6 4.6-4.6 4.6-2.1-2.1a3.5 3.5 0 0 1 0-5l2.1-2.1zm9.2 0l2.1 2.1a3.5 3.5 0 0 1 0 5l-2.1 2.1-4.6-4.6 4.6-4.6zm-4.6 1.8l2.8 2.8-2.8 2.8-2.8-2.8 2.8-2.8z" fill="#FFFFFF"/></svg>`;
    }
    if (raw.includes('instagram')) {
        return `<svg class="channel-logo-svg" width="${s}" height="${s}" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#E1306C"/><rect x="5" y="5" width="14" height="14" rx="3.5" stroke="#FFFFFF" stroke-width="1.6"/><circle cx="12" cy="12" r="3.2" stroke="#FFFFFF" stroke-width="1.6"/><circle cx="16" cy="8" r="0.8" fill="#FFFFFF"/></svg>`;
    }
    if (raw.includes('enjoei')) {
        return `<svg class="channel-logo-svg" width="${s}" height="${s}" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#E83B5E"/><path d="M12 6c-3.3 0-6 2.7-6 6s2.7 6 6 6 6-2.7 6-6h-6v2.2h3.6c-.5 1.1-1.6 1.8-2.8 1.8-1.7 0-3-1.3-3-3s1.3-3 3-3c.8 0 1.5.3 2 .8l1.6-1.6C15.2 6.8 13.7 6 12 6z" fill="#FFFFFF"/></svg>`;
    }
    if (raw.includes('elo7')) {
        return `<svg class="channel-logo-svg" width="${s}" height="${s}" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#FF8800"/><path d="M6 7h12v2.2H9.2v2.8h6.6v2.2H9.2v2.8h8.8V19H6V7z" fill="#FFFFFF"/></svg>`;
    }
    if (raw.includes('diret') || raw.includes('boutique') || raw.includes('site') || raw.includes('checkout') || raw.includes('divinium')) {
        return `<svg class="channel-logo-svg" width="${s}" height="${s}" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#202020"/><path d="M6 3h12l3 5-9 13L3 8l3-5z" stroke="#ded7cf" stroke-width="1.6" fill="none" stroke-linejoin="round"/><path d="M3 8h18M12 21L8 8m4 13l4-13M6 3l2 5m10-5l-2 5" stroke="#ded7cf" stroke-width="1.2" stroke-linecap="round"/></svg>`;
    }
    return `<svg class="channel-logo-svg" width="${s}" height="${s}" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#50555E"/><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round"/></svg>`;
}

/* ============================================================== */
/* CANAIS DE COMPRA POR PRODUTO PRINCIPAL (MODELO PAI)            */
/* ============================================================== */
const CHANNEL_PLATFORMS = {
    'Shopee': {
        name: 'Shopee',
        label: '🟠 Shopee',
        badge: 'Shopee Oficial',
        defaultBtn: 'Comprar na Shopee Oficial',
        placeholder: 'https://shopee.com.br/produto-...',
        accentColor: '#EE4D2D',
        target: '_blank'
    },
    'Mercado Livre': {
        name: 'Mercado Livre',
        label: '🟡 Mercado Livre',
        badge: 'Mercado Livre',
        defaultBtn: 'Comprar no Mercado Livre',
        placeholder: 'https://produto.mercadolivre.com.br/...',
        accentColor: '#FFE600',
        target: '_blank'
    },
    'Amazon': {
        name: 'Amazon',
        label: '📦 Amazon',
        badge: 'Amazon Prime',
        defaultBtn: 'Comprar na Amazon',
        placeholder: 'https://www.amazon.com.br/dp/...',
        accentColor: '#FF9900',
        target: '_blank'
    },
    'WhatsApp': {
        name: 'WhatsApp',
        label: '💬 WhatsApp (Consultor VIP)',
        badge: 'Atendimento VIP',
        defaultBtn: 'Comprar via Consultora no WhatsApp',
        placeholder: 'https://wa.me/5511999999999?text=Olá, quero atendimento...',
        accentColor: '#25D366',
        target: '_blank'
    },
    'Compra Direta': {
        name: 'Compra Direta',
        label: '💎 Compra Direta / Boutique (/checkout)',
        badge: 'Exclusivo',
        defaultBtn: 'Comprar Direto na Boutique',
        placeholder: '/checkout ou https://...',
        accentColor: '#CBD0D6',
        target: '_self'
    },
    'TikTok Shop': {
        name: 'TikTok Shop',
        label: '⚫ TikTok Shop',
        badge: 'TikTok Shop',
        defaultBtn: 'Comprar no TikTok Shop',
        placeholder: 'https://www.tiktok.com/@divinium/...',
        accentColor: '#FE2C55',
        target: '_blank'
    },
    'Asaas Pix': {
        name: 'Asaas Pix',
        label: '💠 Asaas / Pix Direto',
        badge: '5% OFF no Pix',
        defaultBtn: 'Pagar com Pix com Desconto',
        placeholder: 'https://asaas.com/c/...',
        accentColor: '#00d2c6',
        target: '_blank'
    },
    'Enjoei': {
        name: 'Enjoei',
        label: '🛍️ Enjoei',
        badge: 'Enjoei Pro',
        defaultBtn: 'Comprar no Enjoei',
        placeholder: 'https://www.enjoei.com.br/@...',
        accentColor: '#E83B5E',
        target: '_blank'
    },
    'Elo7': {
        name: 'Elo7',
        label: '🎀 Elo7',
        badge: 'Elo7 Oficial',
        defaultBtn: 'Comprar no Elo7',
        placeholder: 'https://www.elo7.com.br/...',
        accentColor: '#FF8800',
        target: '_blank'
    },
    'Instagram Shop': {
        name: 'Instagram Shop',
        label: '📸 Instagram Shop',
        badge: 'Instagram',
        defaultBtn: 'Ver e Comprar no Instagram',
        placeholder: 'https://www.instagram.com/...',
        accentColor: '#E1306C',
        target: '_blank'
    },
    'Outro': {
        name: 'Outro',
        label: '🔗 Outro / Canal Personalizado',
        badge: 'Canal Oficial',
        defaultBtn: 'Comprar no Canal Oficial',
        placeholder: 'https://...',
        accentColor: '#A7ADB4',
        target: '_blank'
    }
};

function getPlatformConfig(platName) {
    const raw = (platName || '').trim().toLowerCase();
    if (raw.includes('shopee')) return CHANNEL_PLATFORMS['Shopee'];
    if (raw.includes('mercado') || raw.includes('ml')) return CHANNEL_PLATFORMS['Mercado Livre'];
    if (raw.includes('amazon')) return CHANNEL_PLATFORMS['Amazon'];
    if (raw.includes('whatsapp') || raw.includes('wa')) return CHANNEL_PLATFORMS['WhatsApp'];
    if (raw.includes('diret') || raw.includes('site') || raw.includes('checkout') || raw.includes('boutique')) return CHANNEL_PLATFORMS['Compra Direta'];
    if (raw.includes('tiktok')) return CHANNEL_PLATFORMS['TikTok Shop'];
    if (raw.includes('pix') || raw.includes('asaas')) return CHANNEL_PLATFORMS['Asaas Pix'];
    if (raw.includes('enjoei')) return CHANNEL_PLATFORMS['Enjoei'];
    if (raw.includes('elo7')) return CHANNEL_PLATFORMS['Elo7'];
    if (raw.includes('instagram')) return CHANNEL_PLATFORMS['Instagram Shop'];
    return CHANNEL_PLATFORMS['Outro'];
}

function normalizeModelLinks(rawLinks, basePriceFormatted, fallbackExternalUrl = '') {
    const links = Array.isArray(rawLinks) ? rawLinks : [];
    if (links.length > 0) {
        return links.map((l, idx) => {
            const plat = (l.platform_name || 'Shopee').trim();
            const pConfig = getPlatformConfig(plat);
            const isKnown = Object.keys(CHANNEL_PLATFORMS).some(k => k.toLowerCase() === plat.toLowerCase());
            const canonicalKey = isKnown ? plat : (pConfig.name === 'Outro' ? 'Outro' : pConfig.name);
            const price = l.price_cents ? ((l.price_cents) / 100).toFixed(2).replace('.', ',') : (l.price ? String(l.price) : basePriceFormatted);
            const target = l.target ? l.target : (l.url && l.url.startsWith('/') ? '_self' : '_blank');
            const isActive = l.is_active !== undefined ? (parseInt(l.is_active, 10) === 1 ? 1 : 0) : 1;

            return {
                platform_name: plat,
                canonical_key: canonicalKey,
                label: pConfig.label,
                placeholder: pConfig.placeholder,
                accent_color: pConfig.accentColor,
                url: l.url || '',
                price: price,
                button_text: l.button_text || pConfig.defaultBtn,
                badge: l.badge || '',
                button_color: l.button_color || 'default',
                button_bg_color: l.button_bg_color || '',
                button_text_color: l.button_text_color || '',
                button_size: l.button_size || 'md',
                target: target,
                is_active: isActive,
                sort_order: l.sort_order !== undefined ? parseInt(l.sort_order, 10) : idx
            };
        });
    }

    return [
        {
            platform_name: 'Shopee',
            canonical_key: 'Shopee',
            label: CHANNEL_PLATFORMS['Shopee'].label,
            placeholder: CHANNEL_PLATFORMS['Shopee'].placeholder,
            accent_color: CHANNEL_PLATFORMS['Shopee'].accentColor,
            url: fallbackExternalUrl || '',
            price: basePriceFormatted,
            button_text: CHANNEL_PLATFORMS['Shopee'].defaultBtn,
            badge: CHANNEL_PLATFORMS['Shopee'].badge,
            button_color: 'default',
            button_bg_color: '',
            button_text_color: '',
            button_size: 'md',
            target: '_blank',
            is_active: fallbackExternalUrl ? 1 : 0,
            sort_order: 0
        },
        {
            platform_name: 'Mercado Livre',
            canonical_key: 'Mercado Livre',
            label: CHANNEL_PLATFORMS['Mercado Livre'].label,
            placeholder: CHANNEL_PLATFORMS['Mercado Livre'].placeholder,
            accent_color: CHANNEL_PLATFORMS['Mercado Livre'].accentColor,
            url: '',
            price: basePriceFormatted,
            button_text: CHANNEL_PLATFORMS['Mercado Livre'].defaultBtn,
            badge: CHANNEL_PLATFORMS['Mercado Livre'].badge,
            button_color: 'default',
            button_bg_color: '',
            button_text_color: '',
            button_size: 'md',
            target: '_blank',
            is_active: 0,
            sort_order: 1
        },
        {
            platform_name: 'Compra Direta',
            canonical_key: 'Compra Direta',
            label: CHANNEL_PLATFORMS['Compra Direta'].label,
            placeholder: CHANNEL_PLATFORMS['Compra Direta'].placeholder,
            accent_color: CHANNEL_PLATFORMS['Compra Direta'].accentColor,
            url: '/checkout',
            price: basePriceFormatted,
            button_text: CHANNEL_PLATFORMS['Compra Direta'].defaultBtn,
            badge: CHANNEL_PLATFORMS['Compra Direta'].badge,
            button_color: 'default',
            button_bg_color: '',
            button_text_color: '',
            button_size: 'md',
            target: '_self',
            is_active: 1,
            sort_order: 2
        },
        {
            platform_name: 'WhatsApp',
            canonical_key: 'WhatsApp',
            label: CHANNEL_PLATFORMS['WhatsApp'].label,
            placeholder: CHANNEL_PLATFORMS['WhatsApp'].placeholder,
            accent_color: CHANNEL_PLATFORMS['WhatsApp'].accentColor,
            url: '',
            price: basePriceFormatted,
            button_text: CHANNEL_PLATFORMS['WhatsApp'].defaultBtn,
            badge: CHANNEL_PLATFORMS['WhatsApp'].badge,
            button_color: 'default',
            button_bg_color: '',
            button_text_color: '',
            button_size: 'md',
            target: '_blank',
            is_active: 0,
            sort_order: 3
        }
    ];
}

let quickModalCurrentGroup = null;

function openModelChannelsQuickModal(group) {
    if (!group) return;
    quickModalCurrentGroup = group;
    const modal = document.getElementById('modelChannelsQuickModal');
    if (!modal) return;

    const basePriceFormatted = group.price_cents ? ((group.price_cents) / 100).toFixed(2).replace('.', ',') : (group.min_price_cents ? ((group.min_price_cents) / 100).toFixed(2).replace('.', ',') : '380,00');
    const fallbackExtUrl = group.parent_product?.external_url || group.external_url || '';

    const bSku = group.base_sku || group.sku || '';
    document.getElementById('quickModalProdId').value = group.id || 0;
    document.getElementById('quickModalBaseSku').value = bSku;
    document.getElementById('quickModalModelTitle').innerText = 'Canais de Venda — ' + (group.name || 'Modelo Autoral');
    document.getElementById('quickModalModelSubtitle').innerText = 'SKU Base: ' + (bSku || 'N/A') + ' • Configure as opções de canais de venda externos e botões de compra para este modelo';

    currentProductLinks = normalizeModelLinks(group.links, basePriceFormatted, fallbackExtUrl);

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    renderPlatformLinks('quickModalLinksContainer');
}

function closeModelChannelsQuickModal() {
    const modal = document.getElementById('modelChannelsQuickModal');
    if (modal) modal.style.display = 'none';
    document.body.style.overflow = '';
    quickModalCurrentGroup = null;
}

function updateLinksCountBadge() {
    const badge = document.getElementById('linksCountBadge');
    if (!badge) return;
    const activeCount = currentProductLinks.filter(l => (parseInt(l.is_active, 10) === 1) && l.url && l.url.trim() !== '').length;
    badge.innerText = activeCount;
    badge.style.background = activeCount > 0 ? 'rgba(34, 197, 94, 0.25)' : 'rgba(212, 186, 136, 0.2)';
    badge.style.color = activeCount > 0 ? '#4ade80' : '#CBD0D6';
}

function renderPlatformLinks(targetContainerId = 'productLinksContainer') {
    const containers = [];
    const mainContainer = document.getElementById('productLinksContainer');
    const quickContainer = document.getElementById('quickModalLinksContainer');

    if (targetContainerId === 'all') {
        if (mainContainer) containers.push(mainContainer);
        if (quickContainer) containers.push(quickContainer);
    } else {
        const c = document.getElementById(targetContainerId);
        if (c) containers.push(c);
    }

    if (containers.length === 0) return;

    updateLinksCountBadge();

    containers.forEach(container => {
        const isQuick = container.id === 'quickModalLinksContainer';
        container.innerHTML = '';

        if (currentProductLinks.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; padding: 32px 20px; background: rgba(255,255,255,0.015); border: 1px dashed rgba(255,255,255,0.1); border-radius: 10px; color: #a8a096;">
                    <p style="margin: 0 0 8px; font-size: 0.9rem; color: #ffffff;">Nenhum canal de venda cadastrado para este modelo.</p>
                    <p style="margin: 0 0 14px; font-size: 0.78rem;">Clique no botão abaixo para adicionar opções como Shopee, Mercado Livre, Amazon, WhatsApp ou Checkout próprio.</p>
                    <button type="button" class="btn-admin-gold" onclick="addNewPlatformLink('${container.id}')" style="padding: 6px 14px; font-size: 0.78rem;">
                        + Adicionar Primeiro Canal
                    </button>
                </div>
            `;
            return;
        }

        currentProductLinks.forEach((link, idx) => {
            const item = document.createElement('div');
            const isActive = parseInt(link.is_active, 10) === 1;
            const pConfig = getPlatformConfig(link.platform_name);
            const brandColor = pConfig.accentColor || '#CBD0D6';
            const isCustom = !Object.keys(CHANNEL_PLATFORMS).some(k => k.toLowerCase() === (link.platform_name || '').toLowerCase() && k !== 'Outro');
            const selectedSelectVal = isCustom ? 'Outro' : link.platform_name;

            item.className = 'channel-editor-card';
            item.style.background = isActive ? 'rgba(255,255,255,0.025)' : 'rgba(255,255,255,0.01)';
            item.style.border = isActive ? `1.5px solid ${brandColor}77` : '1px solid rgba(255,255,255,0.08)';
            item.style.borderRadius = '10px';
            item.style.padding = '14px 16px';
            item.style.marginBottom = '14px';
            item.style.transition = 'all 0.2s ease';
            item.style.opacity = isActive ? '1' : '0.65';

            const priceDisplay = link.price || '';
            const badgeDisplay = link.badge || '';
            const btnTextDisplay = link.button_text || pConfig.defaultBtn;
            const targetVal = link.target || '_blank';
            const bgColorVal = link.button_bg_color || '';
            const textColorVal = link.button_text_color || '';
            const previewBg = bgColorVal ? bgColorVal : 'rgba(0,0,0,0.35)';
            const previewColor = textColorVal ? textColorVal : '#FFFFFF';
            const previewBorder = bgColorVal ? '1px solid rgba(255,255,255,0.2)' : `1px solid ${brandColor}55`;

            item.innerHTML = `
                <!-- TOPO DO CARD: CANAL, ORDEM, STATUS E REMOVER -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 10px; flex-wrap: wrap; gap: 10px;">
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <span style="background: rgba(212,186,136,0.18); color: #ded7cf; font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 6px; border: 1px solid rgba(212,186,136,0.3);">
                            #${idx + 1}
                        </span>

                        <!-- LOGOTIPO OFICIAL DO CANAL E SELETOR -->
                        <span class="channel-card-logo" id="card-logo-${idx}" style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; flex-shrink: 0; border-radius: 4px; overflow: hidden; background: #ffffff; padding: 2px; box-shadow: 0 1px 3px rgba(0,0,0,0.15);">
                            ${getPlatformLogoSvg(link.platform_name, 18)}
                        </span>
                        <select class="form-select" style="font-size: 0.82rem; padding: 4px 10px; width: 210px; font-weight: 600;" onchange="changeChannelPlatform(${idx}, this.value, '${container.id}')">
                            <option value="Shopee" ${selectedSelectVal === 'Shopee' ? 'selected' : ''}>Shopee</option>
                            <option value="Mercado Livre" ${selectedSelectVal === 'Mercado Livre' ? 'selected' : ''}>Mercado Livre</option>
                            <option value="Amazon" ${selectedSelectVal === 'Amazon' ? 'selected' : ''}>Amazon</option>
                            <option value="WhatsApp" ${selectedSelectVal === 'WhatsApp' ? 'selected' : ''}>WhatsApp (Consultor VIP)</option>
                            <option value="Compra Direta" ${selectedSelectVal === 'Compra Direta' ? 'selected' : ''}>Compra Direta / Boutique</option>
                            <option value="TikTok Shop" ${selectedSelectVal === 'TikTok Shop' ? 'selected' : ''}>TikTok Shop</option>
                            <option value="Asaas Pix" ${selectedSelectVal === 'Asaas Pix' ? 'selected' : ''}>Asaas Pix</option>
                            <option value="Enjoei" ${selectedSelectVal === 'Enjoei' ? 'selected' : ''}>Enjoei</option>
                            <option value="Elo7" ${selectedSelectVal === 'Elo7' ? 'selected' : ''}>Elo7</option>
                            <option value="Instagram Shop" ${selectedSelectVal === 'Instagram Shop' ? 'selected' : ''}>Instagram Shop</option>
                            <option value="Outro" ${selectedSelectVal === 'Outro' ? 'selected' : ''}>Outro / Personalizado</option>
                        </select>

                        <!-- INPUT PARA NOME PERSONALIZADO SE SELECIONADO 'OUTRO' -->
                        <input type="text" class="form-input" value="${isCustom ? escapeHtml(link.platform_name || '') : ''}" 
                               placeholder="Nome do Canal (Ex: Shein)" 
                               style="font-size: 0.78rem; padding: 4px 8px; width: 140px; display: ${isCustom ? 'inline-block' : 'none'};"
                               oninput="currentProductLinks[${idx}].platform_name = this.value"
                               onchange="if(!currentProductLinks[${idx}].button_text || currentProductLinks[${idx}].button_text.startsWith('Comprar')) { currentProductLinks[${idx}].button_text = 'Comprar no ' + this.value; } renderPlatformLinks('${container.id}');">
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <!-- BOTÕES DE REORDENAÇÃO (SUBIR / DESCER) -->
                        <button type="button" class="btn-table-action" onclick="moveChannelLink(${idx}, -1, '${container.id}')" ${idx === 0 ? 'disabled style="opacity: 0.3; cursor: not-allowed;"' : ''} title="Mover canal para cima" style="padding: 2px 7px; font-size: 0.78rem;">
                            ↑
                        </button>
                        <button type="button" class="btn-table-action" onclick="moveChannelLink(${idx}, 1, '${container.id}')" ${idx === currentProductLinks.length - 1 ? 'disabled style="opacity: 0.3; cursor: not-allowed;"' : ''} title="Mover canal para baixo" style="padding: 2px 7px; font-size: 0.78rem;">
                            ↓
                        </button>

                        <!-- STATUS SWITCH ATIVO/INATIVO -->
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; user-select: none; margin: 0 4px;">
                            <input type="checkbox" ${isActive ? 'checked' : ''} 
                                   onchange="toggleChannelActive(${idx}, this.checked, '${container.id}')" 
                                   style="width: 16px; height: 16px; accent-color: #22c55e; cursor: pointer;">
                            <span style="font-size: 0.78rem; font-weight: 600; color: ${isActive ? '#4ade80' : '#9ca3af'};">
                                ${isActive ? '● Ativo' : '○ Inativo'}
                            </span>
                        </label>

                        <!-- REMOVER CANAL -->
                        <button type="button" onclick="removePlatformLink(${idx}, '${container.id}')" title="Excluir este canal deste modelo" style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3); color: #f87171; border-radius: 5px; padding: 3px 8px; font-size: 0.74rem; cursor: pointer;">
                            🗑️
                        </button>
                    </div>
                </div>

                <!-- LINHA 1 DE INPUTS: URL + PREÇO ESPECÍFICO -->
                <div style="display: grid; grid-template-columns: 2.2fr 1fr; gap: 12px; margin-bottom: 10px;">
                    <div>
                        <label style="font-size: 0.72rem; color: #b5ada3; display: block; margin-bottom: 3px; font-weight: 500;">
                            URL do Anúncio ou Ação de Compra *
                        </label>
                        <input type="text" class="form-input" value="${escapeHtml(link.url || '')}" 
                               placeholder="${pConfig.placeholder}" 
                               oninput="currentProductLinks[${idx}].url = this.value.trim(); updateLinksCountBadge();" 
                               style="padding: 7px 10px; font-size: 0.82rem;">
                    </div>
                    <div>
                        <label style="font-size: 0.72rem; color: #b5ada3; display: block; margin-bottom: 3px; font-weight: 500;">
                            Preço Específico (R$)
                        </label>
                        <input type="text" class="form-input" value="${escapeHtml(priceDisplay)}" 
                               placeholder="Padrão do modelo"
                               onkeyup="formatCurrencyLive(this)" 
                               oninput="currentProductLinks[${idx}].price = this.value" 
                               style="padding: 7px 10px; font-size: 0.82rem;">
                    </div>
                </div>

                <!-- LINHA 2 DE INPUTS: TEXTO DO BOTÃO + SELO/BADGE + DESTINO -->
                <div style="display: grid; grid-template-columns: 1.6fr 1.2fr 130px; gap: 12px; margin-bottom: 10px;">
                    <div>
                        <label style="font-size: 0.72rem; color: #b5ada3; display: block; margin-bottom: 3px; font-weight: 500;">
                            Texto do Botão
                        </label>
                        <input type="text" class="form-input" value="${escapeHtml(btnTextDisplay)}" 
                               placeholder="Ex: Comprar"
                               oninput="currentProductLinks[${idx}].button_text = this.value" 
                               style="padding: 7px 10px; font-size: 0.82rem;">
                    </div>
                    <div>
                        <label style="font-size: 0.72rem; color: #b5ada3; display: block; margin-bottom: 3px; font-weight: 500;">
                            Selo / Destaque do Canal (Opcional)
                        </label>
                        <input type="text" class="form-input" value="${escapeHtml(badgeDisplay)}" 
                               placeholder="Ex: Oficial, Frete Grátis..." 
                               oninput="currentProductLinks[${idx}].badge = this.value" 
                               style="padding: 7px 10px; font-size: 0.82rem;">
                    </div>
                    <div>
                        <label style="font-size: 0.72rem; color: #b5ada3; display: block; margin-bottom: 3px; font-weight: 500;">
                            Destino da Janela
                        </label>
                        <select class="form-select" style="padding: 7px 8px; font-size: 0.78rem;" onchange="currentProductLinks[${idx}].target = this.value">
                            <option value="_blank" ${targetVal === '_blank' ? 'selected' : ''}>Nova Aba (↗)</option>
                            <option value="_self" ${targetVal === '_self' ? 'selected' : ''}>Mesma Aba (↻)</option>
                        </select>
                    </div>
                </div>

                <!-- LINHA 3 DE INPUTS: PERSONALIZAÇÃO DE COR DE FUNDO E COR DA LETRA -->
                <div style="background: rgba(0,0,0,0.22); border: 1px solid rgba(212,186,136,0.15); border-radius: 8px; padding: 10px 12px; margin-bottom: 10px;">
                    <div style="font-size: 0.72rem; color: #ded7cf; font-weight: 600; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between;">
                        <span style="display: inline-flex; align-items: center; gap: 5px;">🎨 Cores do Botão deste Canal</span>
                        <span style="font-size: 0.68rem; color: #8e8880; font-weight: normal;">(Personalize a cor da letra e fundo na vitrine)</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <!-- COR DE FUNDO -->
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                <label style="font-size: 0.7rem; color: #b5ada3; font-weight: 500; margin: 0;">Fundo do Botão</label>
                                ${bgColorVal ? `<button type="button" onclick="resetChannelBtnColor(${idx}, 'bg', '${container.id}')" style="background:none; border:none; color:#f87171; font-size:0.65rem; cursor:pointer; padding:0;">↺ Padrão</button>` : ''}
                            </div>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <input type="color" value="${bgColorVal && bgColorVal.startsWith('#') && bgColorVal.length === 7 ? bgColorVal : '#202020'}" 
                                       onchange="updateChannelColor(${idx}, 'bg', this.value, '${container.id}')" 
                                       title="Escolher cor de fundo"
                                       style="width: 36px; height: 32px; border-radius: 5px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer; padding: 1px;">
                                <input type="text" class="form-input" value="${escapeHtml(bgColorVal)}" 
                                       placeholder="Padrão (Ex: #ded7cf ou #202020)" 
                                       oninput="updateChannelColor(${idx}, 'bg', this.value, '${container.id}', false)"
                                       style="padding: 5px 8px; font-size: 0.78rem; font-family: monospace; flex: 1;">
                            </div>
                            <div style="display: flex; gap: 4px; margin-top: 5px; align-items: center; flex-wrap: wrap;">
                                <span style="font-size: 0.62rem; color: #737B85; margin-right: 2px;">Paleta:</span>
                                <button type="button" onclick="updateChannelColor(${idx}, 'bg', '#ded7cf', '${container.id}')" title="Dourado DIVINIUM" style="width: 16px; height: 16px; border-radius: 3px; background: #ded7cf; border: 1px solid rgba(255,255,255,0.3); cursor: pointer;"></button>
                                <button type="button" onclick="updateChannelColor(${idx}, 'bg', '#202020', '${container.id}')" title="Preto Ébano" style="width: 16px; height: 16px; border-radius: 3px; background: #202020; border: 1px solid rgba(255,255,255,0.3); cursor: pointer;"></button>
                                <button type="button" onclick="updateChannelColor(${idx}, 'bg', '#EE4D2D', '${container.id}')" title="Shopee Oficial" style="width: 16px; height: 16px; border-radius: 3px; background: #EE4D2D; border: 1px solid rgba(255,255,255,0.3); cursor: pointer;"></button>
                                <button type="button" onclick="updateChannelColor(${idx}, 'bg', '#FFE600', '${container.id}')" title="Mercado Livre" style="width: 16px; height: 16px; border-radius: 3px; background: #FFE600; border: 1px solid rgba(255,255,255,0.3); cursor: pointer;"></button>
                                <button type="button" onclick="updateChannelColor(${idx}, 'bg', '#25D366', '${container.id}')" title="WhatsApp VIP" style="width: 16px; height: 16px; border-radius: 3px; background: #25D366; border: 1px solid rgba(255,255,255,0.3); cursor: pointer;"></button>
                                <button type="button" onclick="updateChannelColor(${idx}, 'bg', '#232F3E', '${container.id}')" title="Amazon Dark" style="width: 16px; height: 16px; border-radius: 3px; background: #232F3E; border: 1px solid rgba(255,255,255,0.3); cursor: pointer;"></button>
                            </div>
                        </div>

                        <!-- COR DA LETRA / TEXTO -->
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                <label style="font-size: 0.7rem; color: #b5ada3; font-weight: 500; margin: 0;">Cor da Letra (Texto)</label>
                                ${textColorVal ? `<button type="button" onclick="resetChannelBtnColor(${idx}, 'text', '${container.id}')" style="background:none; border:none; color:#f87171; font-size:0.65rem; cursor:pointer; padding:0;">↺ Padrão</button>` : ''}
                            </div>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <input type="color" value="${textColorVal && textColorVal.startsWith('#') && textColorVal.length === 7 ? textColorVal : '#FFFFFF'}" 
                                       onchange="updateChannelColor(${idx}, 'text', this.value, '${container.id}')" 
                                       title="Escolher cor da letra"
                                       style="width: 36px; height: 32px; border-radius: 5px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer; padding: 1px;">
                                <input type="text" class="form-input" value="${escapeHtml(textColorVal)}" 
                                       placeholder="Padrão (Ex: #FFFFFF ou #202020)" 
                                       oninput="updateChannelColor(${idx}, 'text', this.value, '${container.id}', false)"
                                       style="padding: 5px 8px; font-size: 0.78rem; font-family: monospace; flex: 1;">
                            </div>
                            <div style="display: flex; gap: 4px; margin-top: 5px; align-items: center; flex-wrap: wrap;">
                                <span style="font-size: 0.62rem; color: #737B85; margin-right: 2px;">Paleta:</span>
                                <button type="button" onclick="updateChannelColor(${idx}, 'text', '#FFFFFF', '${container.id}')" title="Branco Puro" style="width: 16px; height: 16px; border-radius: 3px; background: #FFFFFF; border: 1px solid rgba(0,0,0,0.3); cursor: pointer;"></button>
                                <button type="button" onclick="updateChannelColor(${idx}, 'text', '#202020', '${container.id}')" title="Preto Ébano" style="width: 16px; height: 16px; border-radius: 3px; background: #202020; border: 1px solid rgba(255,255,255,0.3); cursor: pointer;"></button>
                                <button type="button" onclick="updateChannelColor(${idx}, 'text', '#ded7cf', '${container.id}')" title="Dourado DIVINIUM" style="width: 16px; height: 16px; border-radius: 3px; background: #ded7cf; border: 1px solid rgba(0,0,0,0.3); cursor: pointer;"></button>
                                <button type="button" onclick="updateChannelColor(${idx}, 'text', '#111827', '${container.id}')" title="Grafite Escuro" style="width: 16px; height: 16px; border-radius: 3px; background: #111827; border: 1px solid rgba(255,255,255,0.3); cursor: pointer;"></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MINI PRÉVIA DO BOTÃO COMO APARECERÁ NA VITRINE -->
                <div class="channel-mini-preview-btn" style="background: ${previewBg}; color: ${previewColor}; border: ${previewBorder}; border-radius: 7px; padding: 8px 12px; display: flex; align-items: center; justify-content: space-between; font-size: 0.76rem; margin-top: 6px; transition: all 0.25s ease;">
                    <span style="font-size: 0.68rem; opacity: 0.75; text-transform: uppercase; letter-spacing: 0.05em;">Prévia na Boutique:</span>
                    <span style="color: inherit; font-weight: 600; display: inline-flex; align-items: center; gap: 7px;">
                        <span class="channel-logo-icon" style="display: inline-flex; align-items: center; justify-content: center; width: 16px; height: 16px; flex-shrink: 0; border-radius: 3px; overflow: hidden;">
                            ${getPlatformLogoSvg(link.platform_name, 16)}
                        </span>
                        <span style="color: inherit;">${escapeHtml(btnTextDisplay)}</span>
                        ${badgeDisplay ? `<span style="background: rgba(255,255,255,0.2); color: inherit; font-size: 0.65rem; padding: 1px 5px; border-radius: 3px; font-weight: 700;">${escapeHtml(badgeDisplay)}</span>` : ''}
                        <span style="color: inherit; opacity: 0.85; font-size: 0.72rem;">${priceDisplay ? '— R$ ' + priceDisplay : ''}</span>
                        <span style="color: inherit;">↗</span>
                    </span>
                </div>
            `;
            container.appendChild(item);
        });
    });
}

function changeChannelPlatform(idx, newPlat, containerId) {
    if (!currentProductLinks[idx]) return;
    const item = currentProductLinks[idx];
    const prevConfig = getPlatformConfig(item.platform_name);
    const newConfig = getPlatformConfig(newPlat);

    item.platform_name = newPlat;
    item.canonical_key = newPlat;
    item.accent_color = newConfig.accentColor;
    item.label = newConfig.label;
    item.placeholder = newConfig.placeholder;
    item.target = newConfig.target;

    // Se o texto do botão era o padrão do canal anterior, atualizar para o padrão do novo canal
    if (!item.button_text || item.button_text === prevConfig.defaultBtn || item.button_text.startsWith('Comprar no ') || item.button_text.startsWith('Comprar na ')) {
        item.button_text = newConfig.defaultBtn;
    }

    if (!item.badge || item.badge === prevConfig.badge) {
        item.badge = newConfig.badge;
    }

    if (newPlat === 'Compra Direta' && (!item.url || item.url === '')) {
        item.url = '/checkout';
    }

    renderPlatformLinks(containerId);
}

function moveChannelLink(idx, direction, containerId) {
    const targetIdx = idx + direction;
    if (targetIdx < 0 || targetIdx >= currentProductLinks.length) return;

    const temp = currentProductLinks[idx];
    currentProductLinks[idx] = currentProductLinks[targetIdx];
    currentProductLinks[targetIdx] = temp;

    currentProductLinks.forEach((l, i) => { l.sort_order = i; });
    renderPlatformLinks(containerId);
}

function toggleChannelActive(idx, isChecked, containerId = 'productLinksContainer') {
    if (currentProductLinks[idx]) {
        currentProductLinks[idx].is_active = isChecked ? 1 : 0;
        renderPlatformLinks(containerId);
    }
}

function addNewPlatformLink(containerId = 'productLinksContainer') {
    const defaultPrice = document.getElementById('prodPrice')?.value || '380,00';
    const newPlatKey = 'Shopee';
    const pConfig = CHANNEL_PLATFORMS[newPlatKey];

    currentProductLinks.push({
        platform_name: newPlatKey,
        canonical_key: newPlatKey,
        label: pConfig.label,
        placeholder: pConfig.placeholder,
        accent_color: pConfig.accentColor,
        url: '',
        price: defaultPrice,
        button_text: pConfig.defaultBtn,
        badge: pConfig.badge,
        button_color: 'default',
        button_bg_color: '',
        button_text_color: '',
        button_size: 'md',
        target: pConfig.target,
        is_active: 1,
        sort_order: currentProductLinks.length,
        is_canonical: false
    });

    renderPlatformLinks(containerId);
}

function updateChannelColor(idx, type, val, containerId, rerender = true) {
    if (!currentProductLinks[idx]) return;
    if (type === 'bg') {
        currentProductLinks[idx].button_bg_color = val;
    } else if (type === 'text') {
        currentProductLinks[idx].button_text_color = val;
    }
    if (rerender) {
        renderPlatformLinks(containerId);
    } else {
        const parentElem = document.getElementById(containerId);
        if (parentElem) {
            const cards = parentElem.querySelectorAll('.channel-editor-card');
            if (cards[idx]) {
                const prevBox = cards[idx].querySelector('.channel-mini-preview-btn');
                if (prevBox) {
                    if (type === 'bg') prevBox.style.background = val || 'rgba(0,0,0,0.35)';
                    if (type === 'text') prevBox.style.color = val || '#FFFFFF';
                }
            }
        }
    }
}

function resetChannelBtnColor(idx, type, containerId) {
    if (!currentProductLinks[idx]) return;
    if (type === 'bg') currentProductLinks[idx].button_bg_color = '';
    if (type === 'text') currentProductLinks[idx].button_text_color = '';
    renderPlatformLinks(containerId);
}

function removePlatformLink(idx, containerId = 'productLinksContainer') {
    currentProductLinks.splice(idx, 1);
    currentProductLinks.forEach((l, i) => { l.sort_order = i; });
    renderPlatformLinks(containerId);
}

function updateTableRowChannelTags(prodId, links) {
    const container = document.getElementById(`model-links-tags-${prodId}`);
    if (!container) return;
    const active = (links || []).filter(l => (parseInt(l.is_active, 10) === 1) && l.url && l.url.trim() !== '');
    if (active.length === 0) {
        container.innerHTML = '<span style="color: #6b655c; font-size: 0.74rem;">Nenhum canal ativo</span>';
        return;
    }
    container.innerHTML = active.map(lnk => {
        const plat = (lnk.platform_name || 'Canal').trim();
        const rawSlug = plat.toLowerCase().replace(/[^a-z0-9]/g, '');
        let slug = 'outro';
        if (rawSlug.includes('shopee')) slug = 'shopee';
        else if (rawSlug.includes('mercado') || rawSlug.includes('ml')) slug = 'mercadolivre';
        else if (rawSlug.includes('amazon')) slug = 'amazon';
        else if (rawSlug.includes('tiktok')) slug = 'tiktok';
        else if (rawSlug.includes('whatsapp') || rawSlug.includes('wa')) slug = 'whatsapp';
        else if (rawSlug.includes('diret') || rawSlug.includes('site') || rawSlug.includes('checkout')) slug = 'compradireta';
        else if (rawSlug.includes('pix') || rawSlug.includes('asaas')) slug = 'pix';
        else if (rawSlug.includes('instagram')) slug = 'instagram';
        else if (rawSlug.includes('enjoei')) slug = 'enjoei';
        else if (rawSlug.includes('elo7')) slug = 'elo7';

        const priceFmt = lnk.price_cents ? ' — R$ ' + (lnk.price_cents / 100).toFixed(2).replace('.', ',') : (lnk.price ? ' — R$ ' + lnk.price : '');
        const target = lnk.target === '_self' ? '_self' : '_blank';
        const badge = lnk.badge ? ` [${escapeHtml(lnk.badge)}]` : '';
        const title = escapeHtml((lnk.button_text || plat) + badge + priceFmt);

        return `<a href="${escapeHtml(lnk.url)}" target="${target}" rel="noopener noreferrer" class="platform-mini-tag tag-${slug}" title="${title}"><span class="channel-logo-icon">${getPlatformLogoSvg(plat, 15)}</span><span class="channel-tag-name">${escapeHtml(plat)}</span><span class="channel-tag-arrow">↗</span></a>`;
    }).join('');
}

async function saveQuickModelChannels() {
    const prodId = document.getElementById('quickModalProdId')?.value;
    if (!prodId || parseInt(prodId, 10) <= 0) {
        alert('Modelo inválido selecionado.');
        return;
    }

    const btn = document.getElementById('btnQuickSaveChannels');
    const originalText = btn ? btn.innerHTML : '';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span>Salvando Canais...</span>';
    }

    const formData = new FormData();
    formData.append('product_id', prodId);
    formData.append('base_sku', document.getElementById('quickModalBaseSku')?.value || '');
    formData.append('links', JSON.stringify(currentProductLinks));
    const csrf = getCsrfToken();
    if (csrf) formData.append('_csrf_token', csrf);

    try {
        const res = await fetch('/admin/produtos/salvar-canais', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf }
        });
        const data = await res.json();
        if (data.success) {
            showToast(data.message || 'Canais de venda salvos com sucesso!');
            if (quickModalCurrentGroup) {
                quickModalCurrentGroup.links = data.links || currentProductLinks;
            }
            updateTableRowChannelTags(prodId, data.links || currentProductLinks);
            closeModelChannelsQuickModal();
        } else {
            alert(data.error || 'Erro ao salvar canais.');
        }
    } catch (err) {
        alert('Erro ao conectar com o servidor: ' + err.message);
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }
}

async function saveCurrentModelChannels() {
    const prodId = document.getElementById('prodId')?.value;
    if (!prodId || parseInt(prodId, 10) <= 0) {
        alert('Por favor, salve ou selecione um modelo principal existente primeiro.');
        return;
    }

    const btn = document.getElementById('btnSaveChannelsDirect');
    const originalText = btn ? btn.innerHTML : '';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span>Salvando Canais...</span>';
    }

    const formData = new FormData();
    formData.append('product_id', prodId);
    formData.append('base_sku', document.getElementById('prodParentSku')?.value || document.getElementById('prodSku')?.value || '');
    formData.append('links', JSON.stringify(currentProductLinks));
    const csrf = getCsrfToken();
    if (csrf) formData.append('_csrf_token', csrf);

    try {
        const res = await fetch('/admin/produtos/salvar-canais', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf }
        });
        const data = await res.json();
        if (data.success) {
            showToast(data.message || 'Canais de compra salvos com sucesso!');
            updateLinksCountBadge();
            updateTableRowChannelTags(prodId, data.links || currentProductLinks);
        } else {
            alert(data.error || 'Erro ao salvar canais.');
        }
    } catch (err) {
        alert('Erro ao conectar com o servidor: ' + err.message);
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }
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
    updateScrollbarSync();
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

/* ==============================================================================
   SINCRONIZAÇÃO DE ROLAGEM HORIZONTAL SUPERIOR & INFERIOR (DUPLA BARRA)
   ============================================================================== */
function updateScrollbarSync() {
    const topTrack = document.getElementById('catalogScrollTopTrack');
    const table = document.getElementById('productsTable');
    if (topTrack && table) {
        topTrack.style.width = table.scrollWidth + 'px';
    }
}

function initTableScrollSync() {
    const topWrapper = document.getElementById('catalogScrollTopWrapper');
    const bottomWrapper = document.getElementById('catalogTableScrollContainer');
    const table = document.getElementById('productsTable');
    if (!topWrapper || !bottomWrapper || !table) return;

    updateScrollbarSync();

    let isSyncingTop = false;
    let isSyncingBottom = false;

    topWrapper.addEventListener('scroll', () => {
        if (!isSyncingTop) {
            isSyncingBottom = true;
            bottomWrapper.scrollLeft = topWrapper.scrollLeft;
        }
        isSyncingTop = false;
    });

    bottomWrapper.addEventListener('scroll', () => {
        if (!isSyncingBottom) {
            isSyncingTop = true;
            topWrapper.scrollLeft = bottomWrapper.scrollLeft;
        }
        isSyncingBottom = false;
    });

    window.addEventListener('resize', updateScrollbarSync);
}

// Inicialização com suporte a tabs e hash
document.addEventListener('DOMContentLoaded', () => {
    initTableScrollSync();
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