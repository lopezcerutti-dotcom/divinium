<?php
/**
 * VIEW: GESTÃO DEDICADA DE PRODUTOS & ESTOQUE BLING ERP
 * QUIET LUXURY EXECUTIVE CATALOG MANAGEMENT
 */
?>

<!-- CABEÇALHO DO CATÁLOGO & BOTÕES DE AÇÃO -->
<div class="catalog-header-bar">
    <div class="catalog-header-text">
        <h2>Catálogo de Joias & Estoque</h2>
        <p>Gerencie peças autorais, gemas minerais, fotos, links da Shopee e sincronização em tempo real com Bling ERP.</p>
    </div>
    <div class="catalog-actions-bar">
        <!-- BOTÃO SINCRONIZAR COM BLING -->
        <button class="btn-admin-bling" id="syncBlingBtnTable" onclick="triggerBlingSync(event)" title="Sincronizar estoque e produtos com Bling ERP">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="sync-icon">
                <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/>
            </svg>
            <span class="sync-text">Sincronizar com Bling</span>
        </button>

        <!-- BOTÃO CONFIGURAR CREDENCIAIS BLING -->
        <button class="btn-admin-secondary" onclick="openBlingModal(event)" title="Configurações da API Bling v3">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0 2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
            <span>Config Bling</span>
        </button>

        <!-- BOTÃO ADICIONAR NOVA JOIA -->
        <button class="btn-admin-gold" onclick="openNewProductModal(event)">
            <span>+ Nova Joia</span>
        </button>
    </div>
</div>

<!-- CARDS COMPACTOS DE RESUMO DO CATÁLOGO -->
<div class="metrics-grid" style="margin-bottom: 22px;">
    <div class="metric-card">
        <div class="metric-icon-wrap">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
        </div>
        <div class="metric-data">
            <span class="metric-value"><?= (int) ($totalProducts ?? count($products ?? [])) ?></span>
            <span class="metric-label">Total no Catálogo</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon-wrap" style="background: rgba(74, 222, 128, 0.12); color: #4ade80;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
        <div class="metric-data">
            <span class="metric-value"><?= (int) ($activeProducts ?? 0) ?></span>
            <span class="metric-label">Joias Ativas na Loja</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon-wrap" style="background: rgba(202, 161, 92, 0.12); color: #d4ba88;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
        </div>
        <div class="metric-data">
            <span class="metric-value"><?= (int) ($totalStock ?? 0) ?> un.</span>
            <span class="metric-label">Estoque Físico Total</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon-wrap" style="background: rgba(16, 185, 129, 0.12); color: #34d399;">
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
        <input type="text" id="catalogSearchInput" class="search-box-input" placeholder="Buscar por nome ou SKU da joia..." onkeyup="filterCatalog()" oninput="filterCatalog()">
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
        <span id="visibleItemsCount"><?= count($products ?? []) ?></span> de <?= count($products ?? []) ?> joias
    </div>
</div>

<!-- TABELA DE GESTÃO DO CATÁLOGO COM ROLAGEM DUPLA, CABEÇALHO FIXO & PAGINAÇÃO -->
<div class="admin-card" style="padding: 0; overflow: hidden;">
    
    <!-- CONTROLES DE PAGINAÇÃO SUPERIOR -->
    <div class="pagination-bar pagination-bar-top" id="paginationBarTop">
        <div class="pagination-info" id="paginationInfoTop">
            Carregando catálogo...
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
            <div class="pagination-nav" id="paginationNavTop">
                <!-- Botões gerados via JavaScript -->
            </div>
        </div>
    </div>

    <!-- BARRA DE ROLAGEM HORIZONTAL SUPERIOR SINCRONIZADA -->
    <div class="table-scroll-top-wrapper" id="catalogScrollTopWrapper">
        <div class="table-scroll-top-track" id="catalogScrollTopTrack"></div>
    </div>

    <!-- CONTAINER DA TABELA COM ROLAGEM VERTICAL E HORIZONTAL (CABEÇALHO FIXO) -->
    <div class="table-scroll-container" id="catalogTableScrollContainer">
        <table class="admin-table" id="productsTable">
            <thead>
                <tr>
                    <th style="width: 58px; text-align: center;">Foto</th>
                    <th>Joia & SKU</th>
                    <th>Gema / Materiais</th>
                    <th>Categoria</th>
                    <th>Preço Base (R$)</th>
                    <th>Canais de Venda & Links</th>
                    <th>Estoque</th>
                    <th style="text-align: center; width: 60px;">Ordem</th>
                    <th>Status</th>
                    <th style="text-align: right; padding-right: 20px;">Ações</th>
                </tr>
            </thead>
            <tbody id="catalogTableBody">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $p): ?>
                        <?php 
                            $stock = (int) ($p['stock_quantity'] ?? 0);
                            $stockStatus = $p['stock_status'] ?? 'in_stock';
                            if ($stockStatus === 'out_of_stock' || $stock <= 0) {
                                $stockClass = 'stock-out';
                                $stockText = 'Esgotado';
                            } elseif ($stockStatus === 'made_to_order') {
                                $stockClass = 'stock-low';
                                $stockText = 'Sob Encomenda';
                            } else {
                                $stockClass = $stock > 3 ? 'stock-in' : 'stock-low';
                                $stockText = $stock . ' un.';
                            }

                            $categoryName = $p['category_name'] ?? 'Coleção Autoral';
                            $status = $p['status'] ?? (!empty($p['is_active']) ? 'published' : 'hidden');
                            $statusClass = 'status-' . $status;
                            $statusLabel = $status === 'published' ? 'Publicado' : ($status === 'draft' ? 'Rascunho' : 'Oculto');
                        ?>
                        <tr id="product-row-<?= $p['id'] ?>" 
                            data-name="<?= strtolower(\App\Core\View::e($p['name'] ?? '')) ?>" 
                            data-sku="<?= strtolower(\App\Core\View::e($p['sku'] ?? '')) ?>" 
                            data-stone="<?= strtolower(\App\Core\View::e($p['stone_mineral'] ?? '')) ?>" 
                            data-category="<?= \App\Core\View::e($categoryName) ?>" 
                            data-status="<?= $status === 'published' ? 'ativo' : 'oculto' ?>" 
                            data-stock="<?= $stock ?>">
                            
                            <td style="text-align: center; padding-left: 18px;">
                                <div class="table-thumb-wrap">
                                    <img src="<?= \App\Core\View::e($p['image_url'] ?? '/assets/images/hero/featured-green-shell.jpg') ?>" 
                                         alt="<?= \App\Core\View::e($p['name']) ?>" 
                                         class="table-thumb"
                                         loading="lazy"
                                         onerror="this.src='/assets/images/hero/featured-green-shell.jpg';">
                                </div>
                            </td>

                            <td>
                                <strong style="color: #ffffff; font-size: 0.92rem; display: block; margin-bottom: 2px;">
                                    <?= \App\Core\View::e($p['name']) ?>
                                    <?php if (!empty($p['is_featured'])): ?>
                                        <span style="color: #d4ba88; font-size: 0.72rem; margin-left: 4px;" title="Destaque na vitrine">✦</span>
                                    <?php endif; ?>
                                </strong>
                                <span style="font-family: monospace; font-size: 0.72rem; color: #a8a096; background: rgba(255,255,255,0.05); padding: 1px 6px; border-radius: 4px;">
                                    <?= \App\Core\View::e($p['sku'] ?? 'DIV-AUT') ?>
                                </span>
                            </td>

                            <td>
                                <span class="stone-pill" style="margin-bottom: 4px; display: inline-flex;">
                                    <span class="stone-dot"></span>
                                    <?= \App\Core\View::e($p['stone_mineral'] ?: 'Mineral Puro') ?>
                                </span>
                                <?php if (!empty($p['materials'])): ?>
                                    <div style="font-size: 0.72rem; color: #8e8880; max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <?= \App\Core\View::e($p['materials']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td>
                                <span style="font-size: 0.78rem; color: #b5ada3;">
                                    <?= \App\Core\View::e($categoryName) ?>
                                </span>
                            </td>

                            <td>
                                <strong style="color: #ede7df;">
                                    R$ <?= number_format(($p['price_cents'] ?? 0) / 100, 2, ',', '.') ?>
                                </strong>
                                <?php if (!empty($p['promotional_price_cents'])): ?>
                                    <div style="font-size: 0.72rem; color: #34d399;">
                                        De: R$ <?= number_format($p['promotional_price_cents'] / 100, 2, ',', '.') ?>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if (!empty($p['links'])): ?>
                                    <div class="platform-links-list">
                                        <?php foreach ($p['links'] as $lnk): ?>
                                            <?php 
                                                $platSlug = strtolower(preg_replace('/[^a-z0-9]/', '', $lnk['platform_name'] ?? 'shopee'));
                                                $linkPriceFormatted = number_format(($lnk['price_cents'] ?? ($p['price_cents'] ?? 0)) / 100, 2, ',', '.');
                                            ?>
                                            <a href="<?= \App\Core\View::e($lnk['url']) ?>" target="_blank" rel="noopener noreferrer" 
                                               class="platform-mini-tag tag-<?= $platSlug ?>" 
                                               title="<?= \App\Core\View::e($lnk['button_text'] ?? $lnk['platform_name']) ?> — R$ <?= $linkPriceFormatted ?>">
                                                <span><?= \App\Core\View::e($lnk['platform_name']) ?>: R$ <?= $linkPriceFormatted ?></span>
                                                <span>↗</span>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php elseif (!empty($p['external_url'])): ?>
                                    <a href="<?= \App\Core\View::e($p['external_url']) ?>" target="_blank" rel="noopener noreferrer" class="platform-mini-tag tag-shopee">
                                        <span>Shopee: R$ <?= number_format(($p['price_cents'] ?? 0) / 100, 2, ',', '.') ?></span>
                                        <span>↗</span>
                                    </a>
                                <?php else: ?>
                                    <span style="color: #6b655c; font-size: 0.74rem;">—</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <span class="stock-badge <?= $stockClass ?>">
                                    <?= $stockText ?>
                                </span>
                            </td>

                            <td style="text-align: center; color: #a8a096; font-size: 0.82rem;">
                                <?= (int) ($p['sort_order'] ?? 0) ?>
                            </td>

                            <td>
                                <span class="badge-status <?= $statusClass ?>" id="status-badge-<?= $p['id'] ?>">
                                    <?= $statusLabel ?>
                                </span>
                            </td>

                            <td style="text-align: right; padding-right: 20px;">
                                <div class="action-buttons" style="justify-content: flex-end;">
                                    <button class="btn-table-action" onclick="toggleProductStatus(<?= $p['id'] ?>)" title="Alternar status na loja">
                                        <?= $status === 'published' ? 'Ocultar' : 'Publicar' ?>
                                    </button>
                                    <button class="btn-table-action" onclick='editProduct(<?= json_encode($p, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE) ?>)' title="Editar detalhes e canais">
                                        Editar
                                    </button>
                                    <button class="btn-table-action btn-table-duplicate" onclick="duplicateProduct(<?= $p['id'] ?>)" title="Duplicar joia e canais de venda">
                                        Duplicar
                                    </button>
                                    <button class="btn-table-action btn-delete" onclick="deleteProduct(<?= $p['id'] ?>)" title="Excluir produto">
                                        Excluir
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="emptyCatalogRow">
                        <td colspan="10" style="text-align: center; padding: 48px 20px; color: #8e8880;">
                            <div style="font-size: 2.2rem; margin-bottom: 12px;">✦</div>
                            <h3 style="font-family: var(--font-serif); font-size: 1.25rem; color: #ffffff; margin: 0 0 6px;">Nenhuma joia cadastrada ainda</h3>
                            <p style="font-size: 0.85rem; margin: 0 0 16px;">Sincronize o estoque diretamente do Bling ERP ou adicione sua primeira joia manualmente.</p>
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
                        Nenhuma joia corresponde aos filtros selecionados. Limpe a busca para ver todas.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- CONTROLES DE PAGINAÇÃO INFERIOR -->
    <div class="pagination-bar pagination-bar-bottom" id="paginationBarBottom">
        <div class="pagination-info" id="paginationInfoBottom">
            Carregando catálogo...
        </div>
        <div class="pagination-controls-wrap">
            <div class="pagination-per-page">
                <label for="perPageSelectBottom">Itens por página:</label>
                <select id="perPageSelectBottom" class="per-page-select" onchange="changeItemsPerPage(this.value)">
                    <option value="25" selected>25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="pagination-nav" id="paginationNavBottom">
                <!-- Botões gerados via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- MODAL COMPLETO DE CADASTRO / EDIÇÃO DE PRODUTO & MULTI-CANAIS -->
<div class="modal-overlay" id="productModal">
    <div class="modal-content" style="max-width: 820px; max-height: 90vh; display: flex; flex-direction: column;">
        <div class="modal-header">
            <div>
                <h3 class="modal-title" id="productModalTitle">Cadastrar Nova Joia</h3>
                <p style="font-size: 0.78rem; color: #a8a096; margin: 2px 0 0;">Gestão autoral de características, estoque e canais de venda independentes</p>
            </div>
            <button class="close-modal-btn" onclick="closeProductModal()">&times;</button>
        </div>

        <!-- ABAS DO MODAL -->
        <div class="modal-tabs">
            <button type="button" class="modal-tab-btn active" id="tabBtnGeneral" onclick="switchProductTab('general')">
                <span>✦ Informações da Joia</span>
            </button>
            <button type="button" class="modal-tab-btn" id="tabBtnPricing" onclick="switchProductTab('pricing')">
                <span>🏷️ Preço & Estoque</span>
            </button>
            <button type="button" class="modal-tab-btn" id="tabBtnLinks" onclick="switchProductTab('links')">
                <span>🔗 Canais de Venda Externos</span>
                <span id="linksCountBadge" style="background: rgba(212, 186, 136, 0.2); color: #d4ba88; font-size: 0.7rem; padding: 1px 6px; border-radius: 10px; margin-left: 4px;">0</span>
            </button>
            <button type="button" class="modal-tab-btn" id="tabBtnMedia" onclick="switchProductTab('media')">
                <span>🖼️ Fotos & Detalhes</span>
            </button>
        </div>

        <form id="productForm" onsubmit="handleProductSubmit(event)" style="overflow-y: auto; padding-right: 6px; flex: 1;">
            <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">
            <input type="hidden" name="id" id="prodId" value="">
            <input type="hidden" name="links" id="prodLinksJson" value="[]">

            <!-- ABA 1: INFORMAÇÕES DA JOIA -->
            <div id="productTabGeneral" class="modal-tab-pane active">
                <div class="form-group">
                    <label for="prodName">Nome da Joia *</label>
                    <input type="text" id="prodName" name="name" class="form-input" required placeholder="Ex: Bracelete Caminhos — Concha Verde Esmeralda">
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodSku">SKU / Código Interno</label>
                        <input type="text" id="prodSku" name="sku" class="form-input" placeholder="Ex: DIV-BRAC-ESM (auto gerado se vazio)">
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

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodMineral">Gema Mineral / Pedra</label>
                        <input type="text" id="prodMineral" name="stone_mineral" class="form-input" placeholder="Ex: Concha Verde Esmeralda, Ônix, Madrepérola...">
                    </div>

                    <div class="form-group">
                        <label for="prodMaterials">Materiais Nobres</label>
                        <input type="text" id="prodMaterials" name="materials" class="form-input" placeholder="Ex: Prata de Lei 925, Resina Nobre condutora">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodDimensions">Medidas & Dimensões</label>
                        <input type="text" id="prodDimensions" name="dimensions" class="form-input" placeholder="Ex: Diâmetro 2,5 cm • Aro ajustável 14 ao 22">
                    </div>

                    <div class="form-group">
                        <label for="prodColors">Cores & Acabamentos</label>
                        <input type="text" id="prodColors" name="colors" class="form-input" placeholder="Ex: Prata Espelhada, Verde Esmeralda, Ônix Preto">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodVariants">Variantes / Tamanhos</label>
                        <input type="text" id="prodVariants" name="variants_json" class="form-input" placeholder="Ex: Tamanho Único Regulável, ou 16, 18, 20">
                    </div>

                    <div class="form-group">
                        <label for="prodSortOrder">Ordem de Exibição na Loja</label>
                        <input type="number" id="prodSortOrder" name="sort_order" class="form-input" value="0" placeholder="0 (menor aparece primeiro)">
                    </div>
                </div>

                <div class="form-group">
                    <label>Status de Exibição na Boutique</label>
                    <div class="status-radio-group">
                        <label class="status-radio-label">
                            <input type="radio" name="status" id="statusPublished" value="published" checked>
                            <span style="color: #4ade80;">● Publicado (Visível na Boutique)</span>
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

                <div class="form-checkbox-row">
                    <input type="checkbox" id="prodFeatured" name="is_featured" value="1" style="width: 18px; height: 18px; accent-color: #d4ba88;">
                    <label for="prodFeatured" style="margin: 0; cursor: pointer; color: #ded7cf;">Destacar esta joia no topo da Vitrine Principal</label>
                </div>

                <div class="form-group" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.07); padding: 14px 16px; border-radius: 8px; margin-top: 14px;">
                    <label style="color: #d4ba88; font-weight: 600; margin-bottom: 10px; display: block; font-size: 0.84rem;">
                        👁️ Opções Individuais de Exibição no Card (/loja)
                    </label>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.82rem; color: #ede7df;">
                            <input type="hidden" name="show_badge" value="0">
                            <input type="checkbox" id="prodShowBadge" name="show_badge" value="1" checked style="width: 16px; height: 16px; accent-color: #d4ba88;">
                            <span>Exibir selos flutuantes na foto (Prata 925 / Sob Encomenda / Canais)</span>
                        </label>
                        <span class="field-hint" style="margin: -6px 0 0 24px; font-size: 0.72rem;">Desmarque para ocultar selos que cubram detalhes ou ocupem espaço excessivo na fotografia desta peça.</span>

                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.82rem; color: #ede7df;">
                            <input type="hidden" name="show_specs" value="0">
                            <input type="checkbox" id="prodShowSpecs" name="show_specs" value="1" checked style="width: 16px; height: 16px; accent-color: #d4ba88;">
                            <span>Exibir tags de especificações (Materiais, Medidas e SKU)</span>
                        </label>

                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.82rem; color: #ede7df;">
                            <input type="hidden" name="show_description" value="0">
                            <input type="checkbox" id="prodShowDescription" name="show_description" value="1" checked style="width: 16px; height: 16px; accent-color: #d4ba88;">
                            <span>Exibir descrição resumida da peça no card</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- ABA 2: PREÇO & ESTOQUE -->
            <div id="productTabPricing" class="modal-tab-pane">
                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodPrice">Preço de Referência Oficial (R$) *</label>
                        <input type="text" id="prodPrice" name="price" class="form-input" required placeholder="380,00" onkeyup="formatCurrencyLive(this)">
                        <span class="field-hint">Preço base da joia. Cada canal externo pode ter seu próprio valor.</span>
                    </div>

                    <div class="form-group">
                        <label for="prodPromotionalPrice">Preço Promocional / Comparativo (R$)</label>
                        <input type="text" id="prodPromotionalPrice" name="promotional_price" class="form-input" placeholder="450,00" onkeyup="formatCurrencyLive(this)">
                        <span class="field-hint">Valor riscado "De: R$ ..." para destacar promoção.</span>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="prodStock">Estoque Físico Total</label>
                        <input type="number" id="prodStock" name="stock_quantity" class="form-input" value="10" min="0">
                    </div>

                    <div class="form-group">
                        <label for="prodStockStatus">Disponibilidade da Peça</label>
                        <select id="prodStockStatus" name="stock_status" class="form-select">
                            <option value="in_stock">Pronta Entrega (Envio Imediato)</option>
                            <option value="made_to_order">Sob Encomenda Artesanal</option>
                            <option value="out_of_stock">Esgotado no Momento</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- ABA 3: CANAIS DE VENDA EXTERNOS (MULTI-LINKS) -->
            <div id="productTabLinks" class="modal-tab-pane">
                <div style="background: rgba(212, 186, 136, 0.05); border: 1px solid rgba(212, 186, 136, 0.2); border-radius: 8px; padding: 14px 18px; margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                        <div>
                            <strong style="color: #ffffff; font-size: 0.9rem;">Canais de Venda com Preços Independentes</strong>
                            <p style="margin: 3px 0 0; font-size: 0.78rem; color: #b5ada3;">
                                Adicione quantos canais quiser (Shopee, Mercado Livre, Amazon, TikTok Shop, WhatsApp...). Cada botão de compra no site exibirá seu preço específico e direcionará para o link configurado!
                            </p>
                        </div>
                        <button type="button" class="btn-admin-gold" onclick="addNewPlatformLink()" style="padding: 6px 14px; font-size: 0.8rem; white-space: nowrap;">
                            <span>+ Adicionar Canal</span>
                        </button>
                    </div>
                </div>

                <!-- Container dinâmico dos cards de links -->
                <div id="productLinksContainer">
                    <!-- Renderizado dinamicamente via JS -->
                </div>

                <button type="button" class="btn-add-platform-link" onclick="addNewPlatformLink()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    <span>+ Adicionar Outro Canal de Venda (Shopee, Mercado Livre, Amazon...)</span>
                </button>
            </div>

            <!-- ABA 4: FOTOS & DETALHES -->
            <div id="productTabMedia" class="modal-tab-pane">
                <div class="form-group">
                    <label for="prodImage">URL da Imagem Principal</label>
                    <input type="text" id="prodImage" name="image_url" class="form-input" placeholder="/assets/images/hero/featured-green-shell.jpg" oninput="updateImagePreview(this.value)">
                    <span class="field-hint">Use imagens em /assets/images/ ou URL externa HTTPS de alta resolução.</span>
                </div>

                <div class="form-row-2" style="align-items: center;">
                    <div class="form-group">
                        <label for="prodImageFile">Ou Enviar Nova Foto do Computador</label>
                        <input type="file" id="prodImageFile" name="image_file" class="form-input" accept="image/*" onchange="previewUploadedImage(this)">
                    </div>
                    <div style="text-align: center;">
                        <img id="imagePreviewBox" src="/assets/images/hero/featured-green-shell.jpg" alt="Preview" style="width: 72px; height: 72px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(255,255,255,0.15);">
                    </div>
                </div>

                <div class="form-group">
                    <label for="prodShortDesc">Breve Resumo (1 linha)</label>
                    <input type="text" id="prodShortDesc" name="short_description" class="form-input" placeholder="Ex: Joia autoral em prata legítima 925 com lapidação mineral e acabamento artesanal.">
                </div>

                <div class="form-group">
                    <label for="prodDesc">Descrição Completa e Detalhes da Peça</label>
                    <textarea id="prodDesc" name="description" class="form-textarea" rows="4" placeholder="Descreva os detalhes da lapidação, autenticidade, cuidados e a exclusividade artesanal da peça..."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-admin-secondary" onclick="closeProductModal()">Cancelar</button>
                <button type="submit" class="btn-admin-gold" id="saveProdBtn">Salvar Joia & Canais</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DE INTEGRAÇÃO & CONFIGURAÇÃO BLING ERP -->
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
                <span style="font-size: 0.75rem; color: #8e8880; text-align: center;">
                    Atualiza saldos de estoque, preços, SKUs e variações direto para o banco de dados da loja.
                </span>

                <?php if (!empty($blingConfig['is_connected'])): ?>
                    <button class="btn-admin-secondary" onclick="executeBlingSync('api')" style="padding: 10px; font-size: 0.85rem; justify-content: center;">
                        <span>Forçar Consulta Completa via API Bling v3</span>
                    </button>
                <?php else: ?>
                    <div style="background: rgba(202, 161, 92, 0.1); border: 1px solid rgba(202, 161, 92, 0.25); border-radius: 8px; padding: 12px 16px; margin-top: 6px; font-size: 0.8rem; color: #d4ba88; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                        <span>Conecte suas credenciais para sincronizar via API ao vivo.</span>
                        <button type="button" class="btn-admin-gold" onclick="switchBlingTab('config')" style="padding: 6px 12px; font-size: 0.75rem; white-space: nowrap;">
                            Conectar API ↗
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <div id="blingSyncResult" style="display: none; margin-top: 16px; padding: 14px 18px; border-radius: 8px; font-size: 0.85rem;"></div>
        </div>

        <!-- ABA 2: CONFIGURAÇÃO DE CREDENCIAIS -->
        <div id="blingTabConfig" class="bling-tab-content" style="display: none;">
            <form id="blingConfigForm" onsubmit="handleBlingConfigSubmit(event)">
                <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">
                <div class="form-group">
                    <label for="blingClientId">Client ID do Aplicativo Bling *</label>
                    <input type="text" id="blingClientId" name="client_id" class="form-input" required 
                           value="<?= \App\Core\View::e($blingConfig['client_id'] ?? '') ?>" 
                           placeholder="Ex: 7dbf42c119eea8b65d2c1a1a9ad92b1577594">
                    <span style="font-size: 0.72rem; color: #8e8880;">Encontrado na aba "Informações do app" do seu aplicativo no Bling.</span>
                </div>

                <div class="form-group">
                    <label for="blingClientSecret">Client Secret (Segredo do Cliente) *</label>
                    <div style="position: relative;">
                        <input type="password" id="blingClientSecret" name="client_secret" class="form-input" required 
                               value="<?= \App\Core\View::e($blingConfig['client_secret'] ?? '') ?>" 
                               placeholder="Ex: 68eb85dcad967396d05fecdd00802117d7515264b520c362e7f46619aa09"
                               style="padding-right: 44px;">
                        <button type="button" onclick="toggleSecretVisibility()" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: transparent; border: none; color: #8e8880; cursor: pointer; font-size: 1rem;">
                            👁
                        </button>
                    </div>
                    <span style="font-size: 0.72rem; color: #8e8880;">Clique no olho no Bling para copiar o segredo completo.</span>
                </div>

                <div class="form-group">
                    <label for="blingRedirectUri">Link de Redirecionamento (Callback)</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="blingRedirectUri" name="redirect_uri" class="form-input" readonly
                               value="<?= \App\Core\View::e($blingConfig['redirect_uri'] ?? 'https://divinium.com.br/admin/bling/callback') ?>">
                        <button type="button" class="btn-admin-secondary" onclick="copyRedirectUri()" style="padding: 0 16px; white-space: nowrap;">
                            Copiar
                        </button>
                    </div>
                    <span style="font-size: 0.72rem; color: #8e8880;">Cole este mesmo link no campo Link de redirecionamento do seu app no Bling.</span>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 20px; flex-wrap: wrap;">
                    <button type="submit" class="btn-admin-gold" id="saveBlingConfigBtn">
                        Salvar Credenciais
                    </button>

                    <?php if (!empty($blingConfig['client_id'])): ?>
                        <a href="/admin/bling/authorize" class="btn-admin-bling" style="text-decoration: none;">
                            <span>🔗 Conectar com Bling (Autorizar App)</span>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- ABA 3: PASSO A PASSO -->
        <div id="blingTabHelp" class="bling-tab-content" style="display: none; font-size: 0.85rem; color: #ded7cf; line-height: 1.6;">
            <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 18px; margin-bottom: 14px;">
                <h4 style="color: #34d399; margin: 0 0 10px; font-size: 0.95rem;">Como conectar com seu Bling:</h4>
                <ol style="margin: 0; padding-left: 20px; display: flex; flex-direction: column; gap: 8px;">
                    <li>No Bling, vá em <strong>Preferências &gt; Central de Extensões</strong> ou <strong>Área do Integrador</strong>.</li>
                    <li>Abra seu aplicativo (<strong>Gest Estoques PHP</strong>).</li>
                    <li>Copie o <strong>Client Id</strong> e o <strong>Client Secret</strong> e salve na aba "Credenciais" acima.</li>
                    <li>Garanta que o link de redirecionamento esteja registrado no Bling.</li>
                    <li>Clique em <strong>"Conectar com Bling"</strong> para autorizar o acesso.</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<script>
/* ==============================================================================
   FILTROS, PESQUISA POR NOME/SKU & PAGINAÇÃO MULTI-ITENS
   ============================================================================== */
let currentPage = 1;
let itemsPerPage = 25;
let filteredRows = [];

function normalizeSearchText(str) {
    return (str || '')
        .toString()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .trim();
}

function filterCatalog(resetPage = true) {
    if (resetPage) {
        currentPage = 1;
    }

    const searchVal = normalizeSearchText(document.getElementById('catalogSearchInput')?.value || '');
    const catVal = normalizeSearchText(document.getElementById('catalogCategoryFilter')?.value || '');
    const statusVal = normalizeSearchText(document.getElementById('catalogStatusFilter')?.value || '');
    const stockVal = (document.getElementById('catalogStockFilter')?.value || '').trim();

    const allRows = Array.from(document.querySelectorAll('#catalogTableBody tr[data-name]'));
    filteredRows = [];

    allRows.forEach(row => {
        const name = normalizeSearchText(row.getAttribute('data-name'));
        const sku = normalizeSearchText(row.getAttribute('data-sku'));
        const stone = normalizeSearchText(row.getAttribute('data-stone'));
        const category = normalizeSearchText(row.getAttribute('data-category'));
        const status = normalizeSearchText(row.getAttribute('data-status'));
        const stock = parseInt(row.getAttribute('data-stock') || '0', 10);

        // Pesquisa abrangente por Nome ou SKU (e gema mineral como bônus)
        const matchesSearch = !searchVal || name.includes(searchVal) || sku.includes(searchVal) || stone.includes(searchVal);
        const matchesCategory = !catVal || category === catVal;
        const matchesStatus = !statusVal || status === statusVal;

        let matchesStock = true;
        if (stockVal === 'in_stock') matchesStock = stock > 0;
        else if (stockVal === 'low_stock') matchesStock = stock >= 1 && stock <= 3;
        else if (stockVal === 'out_of_stock') matchesStock = stock === 0;

        if (matchesSearch && matchesCategory && matchesStatus && matchesStock) {
            filteredRows.push(row);
        }
    });

    renderPagination();
}

function changeItemsPerPage(newVal) {
    itemsPerPage = parseInt(newVal, 10) || 25;
    currentPage = 1;

    // Sincronizar os controles de página (topo e rodapé)
    const topSelect = document.getElementById('perPageSelectTop');
    const bottomSelect = document.getElementById('perPageSelectBottom');
    if (topSelect) topSelect.value = itemsPerPage;
    if (bottomSelect) bottomSelect.value = itemsPerPage;

    renderPagination();
}

function goToPage(page) {
    const totalPages = Math.ceil(filteredRows.length / itemsPerPage) || 1;
    if (page < 1) page = 1;
    if (page > totalPages) page = totalPages;
    currentPage = page;

    renderPagination();

    // Rola suavemente o container para o início da lista de produtos
    const tableContainer = document.getElementById('catalogTableScrollContainer');
    if (tableContainer) {
        tableContainer.scrollTop = 0;
    }
}

function renderPagination() {
    const totalItems = filteredRows.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    if (currentPage < 1) currentPage = 1;

    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = Math.min(startIndex + itemsPerPage, totalItems);

    // 1. Ocultar todas as linhas e exibir somente a página ativa
    const allRows = document.querySelectorAll('#catalogTableBody tr[data-name]');
    allRows.forEach(r => r.style.display = 'none');

    for (let i = startIndex; i < endIndex; i++) {
        if (filteredRows[i]) {
            filteredRows[i].style.display = '';
        }
    }

    // 2. Alerta de busca sem resultados
    const noResultsRow = document.getElementById('noResultsFilterRow');
    if (noResultsRow) {
        noResultsRow.style.display = (totalItems === 0 && allRows.length > 0) ? '' : 'none';
    }

    // 3. Atualizar resumo textual (Topo e Rodapé)
    const displayStart = totalItems === 0 ? 0 : startIndex + 1;
    const displayEnd = endIndex;
    const infoHtml = totalItems === 0 
        ? `Nenhuma joia encontrada` 
        : `Mostrando <strong>${displayStart}–${displayEnd}</strong> de <strong>${totalItems}</strong> joias`;

    const topInfo = document.getElementById('paginationInfoTop');
    const bottomInfo = document.getElementById('paginationInfoBottom');
    if (topInfo) topInfo.innerHTML = infoHtml;
    if (bottomInfo) bottomInfo.innerHTML = infoHtml;

    const countElem = document.getElementById('visibleItemsCount');
    if (countElem) countElem.innerText = totalItems;

    // 4. Renderizar botões de navegação sincronizados
    renderPaginationNavButtons('paginationNavTop', totalPages);
    renderPaginationNavButtons('paginationNavBottom', totalPages);

    // 5. Atualizar largura do scroll horizontal superior
    updateScrollbarSync();
}

function renderPaginationNavButtons(containerId, totalPages) {
    const container = document.getElementById(containerId);
    if (!container) return;

    if (totalPages <= 1) {
        container.innerHTML = `
            <button type="button" class="pagination-btn" disabled title="Página anterior">‹</button>
            <button type="button" class="pagination-btn active">1</button>
            <button type="button" class="pagination-btn" disabled title="Próxima página">›</button>
        `;
        return;
    }

    let html = '';

    // Primeira página
    html += `<button type="button" class="pagination-btn" onclick="goToPage(1)" ${currentPage === 1 ? 'disabled' : ''} title="Primeira página">«</button>`;
    // Página anterior
    html += `<button type="button" class="pagination-btn" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} title="Página anterior">‹</button>`;

    // Janela de páginas (até 5 visíveis)
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, currentPage + 2);

    if (currentPage <= 3) {
        endPage = Math.min(5, totalPages);
    }
    if (currentPage >= totalPages - 2) {
        startPage = Math.max(1, totalPages - 4);
    }

    if (startPage > 1) {
        html += `<button type="button" class="pagination-btn" onclick="goToPage(1)">1</button>`;
        if (startPage > 2) {
            html += `<span style="color: #6b655c; padding: 0 4px;">...</span>`;
        }
    }

    for (let p = startPage; p <= endPage; p++) {
        const isActive = p === currentPage;
        html += `<button type="button" class="pagination-btn ${isActive ? 'active' : ''}" onclick="goToPage(${p})">${p}</button>`;
    }

    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            html += `<span style="color: #6b655c; padding: 0 4px;">...</span>`;
        }
        html += `<button type="button" class="pagination-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;
    }

    // Próxima página
    html += `<button type="button" class="pagination-btn" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} title="Próxima página">›</button>`;
    // Última página
    html += `<button type="button" class="pagination-btn" onclick="goToPage(${totalPages})" ${currentPage === totalPages ? 'disabled' : ''} title="Última página">»</button>`;

    container.innerHTML = html;
}

/* ==============================================================================
   SINCRONIZAÇÃO DE ROLAGEM HORIZONTAL SUPERIOR & INFERIOR
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

/* ==============================================================================
   CONTROLE DO MODAL DE PRODUTOS & MULTI-CANAIS
   ============================================================================== */
let currentProductLinks = [];

function switchProductTab(tabId) {
    document.querySelectorAll('.modal-tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.modal-tab-pane').forEach(pane => pane.classList.remove('active'));

    const tabBtn = document.getElementById('tabBtn' + tabId.charAt(0).toUpperCase() + tabId.slice(1));
    const tabPane = document.getElementById('productTab' + tabId.charAt(0).toUpperCase() + tabId.slice(1));

    if (tabBtn) tabBtn.classList.add('active');
    if (tabPane) tabPane.classList.add('active');
}

function updateLinksBadge() {
    const badge = document.getElementById('linksCountBadge');
    if (badge) {
        badge.innerText = currentProductLinks.length;
    }
}

function renderPlatformLinks() {
    const container = document.getElementById('productLinksContainer');
    if (!container) return;

    if (currentProductLinks.length === 0) {
        container.innerHTML = `
            <div style="text-align: center; padding: 24px 16px; background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1); border-radius: 8px; color: #8e8880; font-size: 0.82rem;">
                Nenhum canal de venda cadastrado ainda. Clique em "<strong>+ Adicionar Canal</strong>" para vincular à Shopee, Mercado Livre, Amazon, etc.
            </div>
        `;
        updateLinksBadge();
        return;
    }

    let html = '';
    currentProductLinks.forEach((link, idx) => {
        const plat = link.platform_name || 'Shopee';
        const url = link.url || '';
        const price = link.price !== undefined ? link.price : (link.price_cents ? (link.price_cents / 100).toFixed(2).replace('.', ',') : '');
        const btnText = link.button_text || (plat === 'Shopee' ? 'Comprar na Shopee Oficial' : ('Comprar no ' + plat));
        const isActive = link.is_active != 0;
        const sortOrder = link.sort_order !== undefined ? link.sort_order : idx;

        const isCustomPlat = !['Shopee', 'Mercado Livre', 'Amazon', 'TikTok Shop', 'WhatsApp', 'Loja Oficial'].includes(plat);

        html += `
            <div class="platform-link-card ${isActive ? '' : 'is-inactive'}" id="linkCard_${idx}">
                <div class="platform-link-header">
                    <div class="platform-link-title">
                        <span style="font-size: 1.1rem;">${getPlatformIcon(plat)}</span>
                        <span>Canal #${idx + 1}: <strong>${escapeHtml(plat)}</strong></span>
                        ${!isActive ? '<span style="font-size: 0.7rem; color: #9ca3af; background: rgba(255,255,255,0.06); padding: 1px 6px; border-radius: 4px;">Inativo</span>' : ''}
                    </div>
                    <div class="platform-link-actions">
                        ${url ? `<a href="${escapeHtml(url)}" target="_blank" rel="noopener noreferrer" class="btn-test-link" title="Testar link no navegador">↗ Testar</a>` : ''}
                        <button type="button" class="btn-remove-link" onclick="removePlatformLink(${idx})" title="Remover este canal">✕ Remover</button>
                    </div>
                </div>

                <div class="form-row-2" style="margin-bottom: 12px;">
                    <div class="form-group">
                        <label>Plataforma / Canal *</label>
                        <select class="form-select link-platform-select" onchange="handlePlatformChange(${idx}, this.value)">
                            <option value="Shopee" ${plat === 'Shopee' ? 'selected' : ''}>Shopee</option>
                            <option value="Mercado Livre" ${plat === 'Mercado Livre' ? 'selected' : ''}>Mercado Livre</option>
                            <option value="Amazon" ${plat === 'Amazon' ? 'selected' : ''}>Amazon</option>
                            <option value="TikTok Shop" ${plat === 'TikTok Shop' ? 'selected' : ''}>TikTok Shop</option>
                            <option value="WhatsApp" ${plat === 'WhatsApp' ? 'selected' : ''}>WhatsApp / Atendimento VIP</option>
                            <option value="Loja Oficial" ${plat === 'Loja Oficial' ? 'selected' : ''}>Loja Oficial</option>
                            <option value="Outro" ${isCustomPlat ? 'selected' : ''}>Outra Plataforma...</option>
                        </select>
                    </div>

                    <div class="form-group" id="customPlatGroup_${idx}" style="${isCustomPlat ? '' : 'display: none;'}">
                        <label>Nome da Plataforma Personalizada</label>
                        <input type="text" class="form-input link-custom-plat" value="${escapeHtml(plat)}" placeholder="Ex: Elo7, Enjoei, Instagram..." oninput="updateLinkData(${idx}, 'platform_name', this.value)">
                    </div>

                    <div class="form-group" style="${isCustomPlat ? 'grid-column: 1 / -1;' : ''}">
                        <label>URL de Destino do Produto *</label>
                        <input type="url" class="form-input link-url" value="${escapeHtml(url)}" placeholder="https://shopee.com.br/produto-exemplo..." required oninput="updateLinkData(${idx}, 'url', this.value)">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <label style="margin: 0;">Preço Específico neste Canal (R$)</label>
                            <button type="button" onclick="copyBasePriceToLink(${idx})" style="background: none; border: none; color: #d4ba88; font-size: 0.72rem; cursor: pointer; text-decoration: underline;">
                                Usar Preço Base
                            </button>
                        </div>
                        <input type="text" class="form-input link-price" value="${escapeHtml(price)}" placeholder="380,00" onkeyup="formatCurrencyLive(this)" oninput="updateLinkData(${idx}, 'price', this.value)">
                    </div>

                    <div class="form-group">
                        <label>Texto do Botão de Compra</label>
                        <input type="text" class="form-input link-btn-text" value="${escapeHtml(btnText)}" placeholder="Ex: Comprar na Shopee Oficial" oninput="updateLinkData(${idx}, 'button_text', this.value)">
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; padding-top: 8px; border-top: 1px solid rgba(255,255,255,0.04);">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem; color: #ded7cf; cursor: pointer; margin: 0;">
                        <input type="checkbox" class="link-is-active" ${isActive ? 'checked' : ''} onchange="updateLinkData(${idx}, 'is_active', this.checked ? 1 : 0); renderPlatformLinks();" style="accent-color: #d4ba88;">
                        <span>Canal ativo e visível na Boutique pública</span>
                    </label>

                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span style="font-size: 0.74rem; color: #8e8880;">Ordem:</span>
                        <input type="number" class="form-input" style="width: 60px; padding: 4px 8px; font-size: 0.8rem;" value="${sortOrder}" oninput="updateLinkData(${idx}, 'sort_order', parseInt(this.value, 10) || 0)">
                    </div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
    updateLinksBadge();
}

function getPlatformIcon(plat) {
    plat = (plat || '').toLowerCase();
    if (plat.includes('shopee')) return '🟠';
    if (plat.includes('mercado')) return '🟡';
    if (plat.includes('amazon')) return '⚪';
    if (plat.includes('tiktok')) return '🎵';
    if (plat.includes('whatsapp') || plat.includes('wa.me')) return '🟢';
    return '✦';
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

function updateLinkData(idx, field, value) {
    if (currentProductLinks[idx]) {
        currentProductLinks[idx][field] = value;
    }
}

function handlePlatformChange(idx, newPlat) {
    if (!currentProductLinks[idx]) return;

    if (newPlat === 'Outro') {
        currentProductLinks[idx].platform_name = '';
        currentProductLinks[idx].button_text = 'Comprar Joia';
    } else {
        currentProductLinks[idx].platform_name = newPlat;
        currentProductLinks[idx].button_text = newPlat === 'Shopee' ? 'Comprar na Shopee Oficial' : ('Comprar no ' + newPlat);
    }
    renderPlatformLinks();
}

function copyBasePriceToLink(idx) {
    const basePrice = document.getElementById('prodPrice')?.value || '380,00';
    if (currentProductLinks[idx]) {
        currentProductLinks[idx].price = basePrice;
        renderPlatformLinks();
    }
}

function addNewPlatformLink(data = null) {
    const basePrice = document.getElementById('prodPrice')?.value || '380,00';
    const newLink = data || {
        platform_name: currentProductLinks.length === 0 ? 'Shopee' : (currentProductLinks.length === 1 ? 'Mercado Livre' : 'Amazon'),
        url: '',
        price: basePrice,
        button_text: '',
        is_active: 1,
        sort_order: currentProductLinks.length
    };
    if (!newLink.button_text) {
        newLink.button_text = newLink.platform_name === 'Shopee' ? 'Comprar na Shopee Oficial' : ('Comprar no ' + newLink.platform_name);
    }
    currentProductLinks.push(newLink);
    renderPlatformLinks();
}

function removePlatformLink(idx) {
    currentProductLinks.splice(idx, 1);
    renderPlatformLinks();
}

function formatCurrencyLive(input) {
    let value = input.value.replace(/\D/g, '');
    if (value === '') {
        input.value = '';
        return;
    }
    value = (parseInt(value, 10) / 100).toFixed(2) + '';
    value = value.replace('.', ',');
    value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    input.value = value;
}

function updateImagePreview(url) {
    const preview = document.getElementById('imagePreviewBox');
    if (preview && url) {
        preview.src = url;
    }
}

function previewUploadedImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreviewBox');
            if (preview) preview.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function openNewProductModal(e) {
    if (e) e.preventDefault();
    document.getElementById('productForm').reset();
    document.getElementById('prodId').value = '';
    document.getElementById('prodPrice').value = '380,00';
    document.getElementById('prodStock').value = '10';
    document.getElementById('statusPublished').checked = true;
    document.getElementById('prodShowBadge').checked = true;
    document.getElementById('prodShowSpecs').checked = true;
    document.getElementById('prodShowDescription').checked = true;
    document.getElementById('imagePreviewBox').src = '/assets/images/hero/featured-green-shell.jpg';
    document.getElementById('productModalTitle').innerText = 'Cadastrar Nova Joia';

    currentProductLinks = [
        {
            platform_name: 'Shopee',
            url: '',
            price: '380,00',
            button_text: 'Comprar na Shopee Oficial',
            is_active: 1,
            sort_order: 0
        }
    ];

    switchProductTab('general');
    renderPlatformLinks();
    document.getElementById('productModal').classList.add('active');
}

function closeProductModal() {
    document.getElementById('productModal').classList.remove('active');
}

function editProduct(product) {
    document.getElementById('productForm').reset();
    document.getElementById('prodId').value = product.id;
    document.getElementById('prodName').value = product.name || '';
    document.getElementById('prodSku').value = product.sku || '';
    document.getElementById('prodCategory').value = product.category_id || '';
    document.getElementById('prodMineral').value = product.stone_mineral || '';
    document.getElementById('prodMaterials').value = product.materials || '';
    document.getElementById('prodDimensions').value = product.dimensions || '';
    document.getElementById('prodColors').value = product.colors || '';
    document.getElementById('prodVariants').value = product.variants_json || '';
    document.getElementById('prodSortOrder').value = product.sort_order || 0;

    const priceVal = ((product.price_cents || 38000) / 100).toFixed(2).replace('.', ',');
    document.getElementById('prodPrice').value = priceVal;

    if (product.promotional_price_cents) {
        document.getElementById('prodPromotionalPrice').value = ((product.promotional_price_cents) / 100).toFixed(2).replace('.', ',');
    } else {
        document.getElementById('prodPromotionalPrice').value = '';
    }

    document.getElementById('prodStock').value = product.stock_quantity !== undefined ? product.stock_quantity : 10;
    document.getElementById('prodStockStatus').value = product.stock_status || 'in_stock';

    // Status Radio
    const prodStatus = product.status || (product.is_active == 1 ? 'published' : 'hidden');
    if (prodStatus === 'draft') {
        document.getElementById('statusDraft').checked = true;
    } else if (prodStatus === 'hidden') {
        document.getElementById('statusHidden').checked = true;
    } else {
        document.getElementById('statusPublished').checked = true;
    }

    document.getElementById('prodFeatured').checked = product.is_featured == 1;

    document.getElementById('prodShowBadge').checked = (product.show_badge === undefined || product.show_badge === null || product.show_badge == 1);
    document.getElementById('prodShowSpecs').checked = (product.show_specs === undefined || product.show_specs === null || product.show_specs == 1);
    document.getElementById('prodShowDescription').checked = (product.show_description === undefined || product.show_description === null || product.show_description == 1);

    document.getElementById('prodImage').value = product.image_url || '';
    updateImagePreview(product.image_url || '/assets/images/hero/featured-green-shell.jpg');

    document.getElementById('prodShortDesc').value = product.short_description || '';
    document.getElementById('prodDesc').value = product.description || '';

    // Links de Plataformas
    if (Array.isArray(product.links) && product.links.length > 0) {
        currentProductLinks = product.links.map(l => ({
            platform_name: l.platform_name || 'Shopee',
            url: l.url || '',
            price: l.price_cents ? ((l.price_cents) / 100).toFixed(2).replace('.', ',') : priceVal,
            button_text: l.button_text || '',
            is_active: l.is_active !== undefined ? l.is_active : 1,
            sort_order: l.sort_order || 0
        }));
    } else if (product.external_url) {
        currentProductLinks = [{
            platform_name: 'Shopee',
            url: product.external_url,
            price: priceVal,
            button_text: 'Comprar na Shopee Oficial',
            is_active: 1,
            sort_order: 0
        }];
    } else {
        currentProductLinks = [];
    }

    switchProductTab('general');
    renderPlatformLinks();
    document.getElementById('productModalTitle').innerText = 'Editar: ' + product.name;
    document.getElementById('productModal').classList.add('active');
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
}

async function handleProductSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('saveProdBtn');
    btn.disabled = true;
    btn.innerText = 'Salvando Joia & Canais...';

    // Serializar links para o input oculto
    document.getElementById('prodLinksJson').value = JSON.stringify(currentProductLinks);

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
            btn.innerText = 'Salvar Joia & Canais';
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
        btn.disabled = false;
        btn.innerText = 'Salvar Joia & Canais';
    }
}

async function duplicateProduct(id) {
    if (!confirm('Deseja criar uma cópia desta joia com todos os seus canais de venda externos configurados?')) {
        return;
    }

    const formData = new FormData();
    formData.append('id', id);
    const csrfToken = getCsrfToken();
    if (csrfToken) {
        formData.append('_csrf_token', csrfToken);
    }

    try {
        const response = await fetch('/admin/produtos/duplicar', {
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
            alert(data.message || 'Produto duplicado com sucesso!');
            window.location.reload();
        } else {
            alert(data.error || 'Erro ao duplicar produto.');
        }
    } catch (err) {
        alert('Erro ao comunicar com o servidor: ' + err.message);
    }
}

async function toggleProductStatus(id) {
    const formData = new FormData();
    formData.append('id', id);
    const csrfToken = getCsrfToken();
    if (csrfToken) {
        formData.append('_csrf_token', csrfToken);
    }

    try {
        const response = await fetch('/admin/produtos/toggle', {
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
            const row = document.getElementById('product-row-' + id);
            const badge = document.getElementById('status-badge-' + id);
            const btn = row?.querySelector('.btn-table-action');
            if (row && badge) {
                const isPublished = (data.status === 'published' || data.is_active == 1);
                row.setAttribute('data-status', isPublished ? 'ativo' : 'oculto');
                badge.className = 'badge-status ' + (isPublished ? 'status-published' : 'status-hidden');
                badge.innerText = isPublished ? 'Publicado' : 'Oculto';
                if (btn) btn.innerText = isPublished ? 'Ocultar' : 'Publicar';
                filterCatalog(false);
            } else {
                window.location.reload();
            }
        } else {
            alert(data.error || 'Erro ao alterar status.');
        }
    } catch (err) {
        alert('Erro ao alterar status: ' + err.message);
    }
}

async function deleteProduct(id) {
    if (!confirm('Tem certeza de que deseja excluir esta joia e todos os seus canais de venda vinculados?')) {
        return;
    }

    const formData = new FormData();
    formData.append('id', id);
    const csrfToken = getCsrfToken();
    if (csrfToken) {
        formData.append('_csrf_token', csrfToken);
    }

    try {
        const response = await fetch('/admin/produtos/excluir', {
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
            const row = document.getElementById('product-row-' + id);
            if (row) row.remove();
            filterCatalog();
        } else {
            alert(data.error || 'Erro ao excluir.');
        }
    } catch (err) {
        alert('Erro ao excluir produto: ' + err.message);
    }
}

/* ==============================================================================
   CONTROLE DO MODAL E SINCRONIZAÇÃO BLING ERP
   ============================================================================== */
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
        alert('Link de redirecionamento copiado!');
    });
}

async function handleBlingConfigSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('saveBlingConfigBtn');
    btn.disabled = true;
    btn.innerText = 'Salvando...';

    const form = document.getElementById('blingConfigForm');
    const formData = new FormData(form);
    const csrfToken = getCsrfToken();
    if (csrfToken && !formData.has('_csrf_token')) {
        formData.append('_csrf_token', csrfToken);
    }

    try {
        const res = await fetch('/admin/bling/config', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const data = await res.json();
        if (data.success) {
            alert(data.message || 'Configurações salvas!');
            window.location.reload();
        } else {
            alert(data.error || 'Erro ao salvar credenciais.');
            btn.disabled = false;
            btn.innerText = 'Salvar Credenciais';
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
        btn.disabled = false;
        btn.innerText = 'Salvar Credenciais';
    }
}

async function triggerBlingSync(e, mode = 'auto') {
    if (e) e.preventDefault();

    const buttons = [
        document.getElementById('syncBlingBtnTable'),
        document.getElementById('modalSyncNowBtn')
    ];

    buttons.forEach(btn => {
        if (btn) {
            btn.classList.add('is-syncing');
            const txt = btn.querySelector('.sync-text');
            if (txt) txt.innerText = 'Sincronizando...';
        }
    });

    try {
        const formData = new FormData();
        formData.append('mode', mode);
        const csrfToken = getCsrfToken();
        if (csrfToken) {
            formData.append('_csrf_token', csrfToken);
        }

        const res = await fetch('/admin/bling/sync', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        const data = await res.json();

        if (data.success) {
            const count = data.data && data.data.total ? data.data.total : 0;
            const msg = data.message || (count + ' produtos e estoque sincronizados!');
            
            const resDiv = document.getElementById('blingSyncResult');
            if (resDiv) {
                resDiv.style.display = 'block';
                resDiv.style.background = 'rgba(16, 185, 129, 0.15)';
                resDiv.style.border = '1px solid rgba(16, 185, 129, 0.4)';
                resDiv.style.color = '#34d399';
                resDiv.innerHTML = '<strong>✓ ' + msg + '</strong><br><small style="color:#ded7cf;">Atualizando catálogo...</small>';
            }

            setTimeout(() => {
                window.location.reload();
            }, 800);
        } else {
            alert(data.error || 'Erro ao sincronizar com o Bling.');
            buttons.forEach(btn => {
                if (btn) {
                    btn.classList.remove('is-syncing');
                    const txt = btn.querySelector('.sync-text');
                    if (txt) txt.innerText = 'Sincronizar com Bling';
                }
            });
        }
    } catch (err) {
        alert('Erro de conexão durante a sincronização: ' + err.message);
        buttons.forEach(btn => {
            if (btn) {
                btn.classList.remove('is-syncing');
                const txt = btn.querySelector('.sync-text');
                if (txt) txt.innerText = 'Sincronizar com Bling';
            }
        });
    }
}

function executeBlingSync(mode) {
    triggerBlingSync(null, mode);
}

// Inicialização de Scroll Sincronizado, Paginação e Abertura por Hash
document.addEventListener('DOMContentLoaded', () => {
    initTableScrollSync();
    filterCatalog();

    if (window.location.hash === '#novo') {
        openNewProductModal();
    } else if (window.location.hash === '#bling') {
        openBlingModal();
    }
});
</script>
