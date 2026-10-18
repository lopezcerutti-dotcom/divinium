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
        <input type="text" id="catalogSearchInput" class="search-box-input" placeholder="Buscar por joia, SKU, gema mineral..." onkeyup="filterCatalog()">
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

<!-- TABELA DE GESTÃO DO CATÁLOGO -->
<div class="admin-card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
        <table class="admin-table" id="productsTable">
            <thead>
                <tr>
                    <th style="width: 64px; text-align: center;">Foto</th>
                    <th>Joia & SKU</th>
                    <th>Gema / Mineral</th>
                    <th>Categoria</th>
                    <th>Preço (R$)</th>
                    <th>Pix (-5%)</th>
                    <th>Estoque</th>
                    <th>Link Shopee</th>
                    <th>Status</th>
                    <th style="text-align: right; padding-right: 20px;">Ações</th>
                </tr>
            </thead>
            <tbody id="catalogTableBody">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $p): ?>
                        <?php 
                            $stock = (int) ($p['stock_quantity'] ?? 0);
                            $stockClass = $stock > 3 ? 'stock-in' : ($stock > 0 ? 'stock-low' : 'stock-out');
                            $stockText = $stock > 3 ? ($stock . ' un.') : ($stock > 0 ? ($stock . ' un. • Baixo') : 'Esgotado');
                            $categoryName = $p['category_name'] ?? 'Coleção Autoral';
                        ?>
                        <tr id="product-row-<?= $p['id'] ?>" 
                            data-name="<?= strtolower(\App\Core\View::e($p['name'] ?? '')) ?>" 
                            data-sku="<?= strtolower(\App\Core\View::e($p['sku'] ?? '')) ?>" 
                            data-stone="<?= strtolower(\App\Core\View::e($p['stone_mineral'] ?? '')) ?>" 
                            data-category="<?= \App\Core\View::e($categoryName) ?>" 
                            data-status="<?= !empty($p['is_active']) ? 'ativo' : 'oculto' ?>" 
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
                                </strong>
                                <span style="font-family: monospace; font-size: 0.72rem; color: #a8a096; background: rgba(255,255,255,0.05); padding: 1px 6px; border-radius: 4px;">
                                    <?= \App\Core\View::e($p['sku'] ?? 'DIV-AUT') ?>
                                </span>
                            </td>

                            <td>
                                <span class="stone-pill">
                                    <span class="stone-dot"></span>
                                    <?= \App\Core\View::e($p['stone_mineral'] ?? 'Mineral Puro') ?>
                                </span>
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
                            </td>

                            <td>
                                <strong style="color: #34d399; font-size: 0.88rem;">
                                    R$ <?= number_format((($p['promotional_price_cents'] ?? ($p['price_cents'] ?? 0) * 0.95) / 100), 2, ',', '.') ?>
                                </strong>
                            </td>

                            <td>
                                <span class="stock-badge <?= $stockClass ?>">
                                    <?= $stockText ?>
                                </span>
                            </td>

                            <td>
                                <?php if (!empty($p['external_url'])): ?>
                                    <a href="<?= \App\Core\View::e($p['external_url']) ?>" target="_blank" rel="noopener noreferrer" class="shopee-badge">
                                        <span>↗ Shopee</span>
                                    </a>
                                <?php else: ?>
                                    <span style="color: #6b655c; font-size: 0.74rem;">—</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <span class="badge-status <?= !empty($p['is_active']) ? 'badge-active' : 'badge-inactive' ?>" id="status-badge-<?= $p['id'] ?>">
                                    <?= !empty($p['is_active']) ? 'Exibido' : 'Oculto' ?>
                                </span>
                            </td>

                            <td style="text-align: right; padding-right: 20px;">
                                <div class="action-buttons" style="justify-content: flex-end;">
                                    <button class="btn-table-action" onclick="toggleProductStatus(<?= $p['id'] ?>)" title="Alternar visibilidade na loja">
                                        <?= !empty($p['is_active']) ? 'Ocultar' : 'Exibir' ?>
                                    </button>
                                    <button class="btn-table-action" onclick='editProduct(<?= json_encode($p, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT) ?>)' title="Editar detalhes">
                                        Editar
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
</div>

<!-- MODAL DE CADASTRO / EDIÇÃO DE PRODUTO -->
<div class="modal-overlay" id="productModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="productModalTitle">Cadastrar Nova Joia</h3>
            <button class="close-modal-btn" onclick="closeProductModal()">&times;</button>
        </div>

        <form id="productForm" onsubmit="handleProductSubmit(event)">
            <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">
            <input type="hidden" name="id" id="prodId" value="">

            <div class="form-group">
                <label for="prodName">Nome da Joia *</label>
                <input type="text" id="prodName" name="name" class="form-input" required placeholder="Ex: Bracelete Caminhos • Quartzo Verde">
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="prodMineral">Gema Mineral / Pedra</label>
                    <input type="text" id="prodMineral" name="stone_mineral" class="form-input" placeholder="Ex: Quartzo Verde, Madrepérola...">
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
                    <label for="prodPrice">Preço Normal (R$) *</label>
                    <input type="text" id="prodPrice" name="price" class="form-input" required placeholder="380,00">
                </div>

                <div class="form-group">
                    <label for="prodStock">Estoque Físico</label>
                    <input type="number" id="prodStock" name="stock_quantity" class="form-input" value="10" min="0">
                </div>
            </div>

            <div class="form-group">
                <label for="prodShopee">Link da Joia na Shopee / Loja Externa</label>
                <input type="url" id="prodShopee" name="external_url" class="form-input" placeholder="https://shopee.com.br/produto-exemplo-divinium...">
                <span style="font-size: 0.72rem; color: #d4ba88;">Clientes serão direcionados a este link para finalizar a compra com garantia Shopee.</span>
            </div>

            <div class="form-group">
                <label for="prodImage">URL da Imagem / Foto</label>
                <input type="text" id="prodImage" name="image_url" class="form-input" placeholder="/assets/images/hero/featured-green-shell.jpg">
                <span style="font-size: 0.72rem; color: #8e8880;">Use imagens em /assets/images/products/ ou link externo.</span>
            </div>

            <div class="form-group">
                <label for="prodDesc">Descrição da Peça</label>
                <textarea id="prodDesc" name="description" class="form-textarea" rows="2" placeholder="Prata nobre 925 e gema mineral pura lapidada manualmente."></textarea>
            </div>

            <div class="form-checkbox-row">
                <input type="checkbox" id="prodActive" name="is_active" value="1" checked style="width: 18px; height: 18px; accent-color: #d4ba88;">
                <label for="prodActive" style="margin: 0; cursor: pointer;">Exibir imediatamente na loja pública</label>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-admin-secondary" onclick="closeProductModal()">Cancelar</button>
                <button type="submit" class="btn-admin-gold" id="saveProdBtn">Salvar Joia</button>
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
   FILTROS EM TEMPO REAL NO CLIENT-SIDE
   ============================================================================== */
function filterCatalog() {
    const searchVal = (document.getElementById('catalogSearchInput')?.value || '').toLowerCase().trim();
    const catVal = (document.getElementById('catalogCategoryFilter')?.value || '').toLowerCase().trim();
    const statusVal = (document.getElementById('catalogStatusFilter')?.value || '').toLowerCase().trim();
    const stockVal = (document.getElementById('catalogStockFilter')?.value || '').toLowerCase().trim();

    const rows = document.querySelectorAll('#catalogTableBody tr[data-name]');
    let visibleCount = 0;

    rows.forEach(row => {
        const name = row.getAttribute('data-name') || '';
        const sku = row.getAttribute('data-sku') || '';
        const stone = row.getAttribute('data-stone') || '';
        const category = (row.getAttribute('data-category') || '').toLowerCase();
        const status = row.getAttribute('data-status') || '';
        const stock = parseInt(row.getAttribute('data-stock') || '0', 10);

        let matchesSearch = !searchVal || name.includes(searchVal) || sku.includes(searchVal) || stone.includes(searchVal);
        let matchesCategory = !catVal || category === catVal;
        let matchesStatus = !statusVal || status === statusVal;
        
        let matchesStock = true;
        if (stockVal === 'in_stock') matchesStock = stock > 0;
        else if (stockVal === 'low_stock') matchesStock = stock >= 1 && stock <= 3;
        else if (stockVal === 'out_of_stock') matchesStock = stock === 0;

        if (matchesSearch && matchesCategory && matchesStatus && matchesStock) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const countElem = document.getElementById('visibleItemsCount');
    if (countElem) countElem.innerText = visibleCount;

    const noResultsRow = document.getElementById('noResultsFilterRow');
    if (noResultsRow) {
        noResultsRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
    }
}

/* ==============================================================================
   CONTROLE DO MODAL DE PRODUTOS
   ============================================================================== */
function openNewProductModal(e) {
    if (e) e.preventDefault();
    document.getElementById('productForm').reset();
    document.getElementById('prodId').value = '';
    document.getElementById('prodShopee').value = '';
    document.getElementById('productModalTitle').innerText = 'Cadastrar Nova Joia';
    document.getElementById('productModal').classList.add('active');
}

function closeProductModal() {
    document.getElementById('productModal').classList.remove('active');
}

function editProduct(product) {
    document.getElementById('prodId').value = product.id;
    document.getElementById('prodName').value = product.name || '';
    document.getElementById('prodMineral').value = product.stone_mineral || '';
    document.getElementById('prodCategory').value = product.category_id || '';
    document.getElementById('prodPrice').value = ((product.price_cents || 38000) / 100).toFixed(2).replace('.', ',');
    document.getElementById('prodStock').value = product.stock_quantity || 10;
    document.getElementById('prodShopee').value = product.external_url || '';
    document.getElementById('prodImage').value = product.image_url || '';
    document.getElementById('prodDesc').value = product.description || '';
    document.getElementById('prodActive').checked = product.is_active == 1;

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
    btn.innerText = 'Salvando...';

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
            btn.innerText = 'Salvar Joia';
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
        btn.disabled = false;
        btn.innerText = 'Salvar Joia';
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
            window.location.reload();
        }
    } catch (err) {
        alert('Erro ao alterar status.');
    }
}

async function deleteProduct(id) {
    if (!confirm('Tem certeza de que deseja excluir este produto do catálogo?')) {
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
        alert('Erro ao excluir produto.');
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

// Auto open if hash is #novo or #bling
document.addEventListener('DOMContentLoaded', () => {
    if (window.location.hash === '#novo') {
        openNewProductModal();
    } else if (window.location.hash === '#bling') {
        openBlingModal();
    }
});
</script>
