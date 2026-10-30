<?php
/**
 * VIEW: GESTÃO COMPLETA DE CATEGORIAS, PRODUTOS & VARIANTES BLING
 * DIVINIUM ADMIN SUITE - QUIET LUXURY EXECUTION
 */
?>

<!-- CABEÇALHO DA ABA CATEGORIAS -->
<div class="catalog-header-bar">
    <div class="catalog-header-text">
        <h2>Categorias & Coleções Autorais</h2>
        <p>Crie, edite e organize os agrupamentos de joias, ordem no menu da loja e descrições das coleções.</p>
    </div>
    <div class="catalog-actions-bar">
        <button class="btn-admin-gold" onclick="openNewCategoryModal(event)">
            <span>+ Nova Categoria</span>
        </button>
    </div>
</div>

<!-- BARRA DE PESQUISA EM TEMPO REAL -->
<div class="admin-filter-bar">
    <div class="search-box-wrap">
        <svg class="search-box-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input type="text" id="categorySearchInput" class="search-box-input" placeholder="Buscar por nome da coleção, slug ou descrição..." onkeyup="filterCategories()">
    </div>

    <div class="filter-count-badge">
        <span id="catVisibleCount"><?= count($categories ?? []) ?></span> coleções cadastradas
    </div>
</div>

<!-- TABELA DE CATEGORIAS -->
<div class="admin-card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Nome da Categoria</th>
                    <th>Slug URL</th>
                    <th>Descrição</th>
                    <th>Ordem</th>
                    <th>Produtos & Variantes</th>
                    <th>Status</th>
                    <th style="text-align: right; padding-right: 20px;">Ações</th>
                </tr>
            </thead>
            <tbody id="categoryTableBody">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $c): ?>
                        <tr id="cat-row-<?= $c['id'] ?>"
                            data-name="<?= strtolower(\App\Core\View::e($c['name'])) ?>"
                            data-slug="<?= strtolower(\App\Core\View::e($c['slug'])) ?>"
                            data-desc="<?= strtolower(\App\Core\View::e($c['description'] ?? '')) ?>">
                            <td style="color: #8e8880; font-family: monospace;"><?= $c['id'] ?></td>
                            <td>
                                <strong style="color: #ffffff; font-size: 0.95rem; display: block;"><?= \App\Core\View::e($c['name']) ?></strong>
                            </td>
                            <td>
                                <span style="color: #E5E7EB; font-family: monospace; font-size: 0.8rem; background: rgba(229,231,235,0.08); padding: 2px 8px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.06);">
                                    /<?= \App\Core\View::e($c['slug']) ?>
                                </span>
                            </td>
                            <td style="color: #b5ada3; max-width: 280px; font-size: 0.82rem;">
                                <?= \App\Core\View::e($c['description'] ?? '—') ?>
                            </td>
                            <td>
                                <span style="font-size: 0.8rem; color: #a8a096;"><?= (int) ($c['sort_order'] ?? 0) ?></span>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 3px;">
                                    <span style="font-weight: 600; color: #ede7df; font-size: 0.88rem; display: flex; align-items: center; gap: 6px;">
                                        <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #A7ADB4;"></span>
                                        <?= (int) ($c['main_products_count'] ?? 0) ?> produtos principais
                                    </span>
                                    <span style="font-size: 0.78rem; color: #8e8880; padding-left: 12px;">
                                        <?= (int) ($c['variants_count'] ?? 0) ?> variantes Bling
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-status <?= !empty($c['is_active']) ? 'badge-active' : 'badge-inactive' ?>">
                                    <?= !empty($c['is_active']) ? 'Ativa' : 'Inativa' ?>
                                </span>
                            </td>
                            <td style="text-align: right; padding-right: 20px;">
                                <div class="action-buttons" style="justify-content: flex-end;">
                                    <button class="btn-table-action" onclick='editCategory(<?= json_encode($c, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'>
                                        Editar
                                    </button>
                                    <button class="btn-table-action btn-delete" onclick="deleteCategory(<?= $c['id'] ?>)">
                                        Excluir
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: #8e8880;">
                            Nenhuma categoria cadastrada. Clique no botão acima para criar sua primeira coleção.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL CATEGORIA AMPLIADO COM GESTÃO DE PRODUTOS & VARIANTES BLING -->
<div class="modal-overlay" id="categoryModal">
    <div class="modal-content" style="max-width: 960px; width: 95%; max-height: 90vh; display: flex; flex-direction: column; padding: 0; overflow: hidden;">
        <!-- CABEÇALHO DO MODAL -->
        <div class="modal-header" style="padding: 18px 24px; border-bottom: 1px solid rgba(255,255,255,0.08); flex-shrink: 0;">
            <div>
                <h3 class="modal-title" id="catModalTitle" style="margin: 0;">Nova Categoria</h3>
                <p id="catModalSubtitle" style="margin: 3px 0 0; font-size: 0.78rem; color: #8e8880;">
                    Configure as informações da coleção, modelos vinculados e variantes minerais.
                </p>
            </div>
            <button class="close-modal-btn" onclick="closeCategoryModal()">&times;</button>
        </div>

        <!-- CORPO DO MODAL SCROLLÁVEL -->
        <div style="flex: 1; overflow-y: auto; padding: 22px 24px;">
            <form id="categoryForm" onsubmit="handleCategorySubmit(event)">
                <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">
                <input type="hidden" name="id" id="catId" value="">

                <!-- FORMULÁRIO BÁSICO DE CATEGORIA -->
                <div class="form-group">
                    <label for="catName">Nome da Categoria / Coleção *</label>
                    <input type="text" id="catName" name="name" class="form-input" required placeholder="Ex: Braceletes & Arm Candy">
                </div>

                <div class="form-group">
                    <label for="catDesc">Descrição da Coleção</label>
                    <textarea id="catDesc" name="description" class="form-textarea" rows="3" placeholder="Peças esculpidas com banho de prata e acabamento acetinado..."></textarea>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="catOrder">Ordem de Exibição no Menu</label>
                        <input type="number" id="catOrder" name="sort_order" class="form-input" value="1" min="0">
                    </div>

                    <div class="form-group">
                        <label>Visibilidade Pública</label>
                        <div class="form-checkbox-row" style="margin-top: 10px;">
                            <input type="checkbox" id="catActive" name="is_active" value="1" checked style="width: 18px; height: 18px; accent-color: #A7ADB4;">
                            <label for="catActive" style="margin: 0; cursor: pointer; color: #EDE7DF;">Categoria Ativa na Loja</label>
                        </div>
                    </div>
                </div>

                <!-- SEÇÃO DINÂMICA: PRODUTOS DESTA CATEGORIA -->
                <div id="catProductsSection" style="margin-top: 26px; padding-top: 22px; border-top: 1px solid rgba(255,255,255,0.08);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                        <div>
                            <h4 style="margin: 0; color: #ffffff; font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
                                <span>💎</span> Produtos desta Categoria
                            </h4>
                            <p style="margin: 3px 0 0; font-size: 0.8rem; color: #8e8880;">
                                Cada produto principal possui exatamente 1 card na Boutique. Suas variantes aparecem exclusivamente na página do produto.
                            </p>
                        </div>
                        <div id="catProductsCountBadge" style="background: rgba(167, 173, 180, 0.12); border: 1px solid rgba(167, 173, 180, 0.25); border-radius: 20px; padding: 4px 14px; font-size: 0.78rem; color: #E5E7EB; font-weight: 500;">
                            <span id="catCountMain">0</span> produtos principais • <span id="catCountVars">0</span> variantes
                        </div>
                    </div>

                    <!-- CARD DE ASSOCIAÇÃO DE PRODUTOS EXISTENTES -->
                    <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 14px 16px; margin-bottom: 20px;">
                        <label style="color: #E5E7EB; font-size: 0.82rem; font-weight: 600; margin-bottom: 8px; display: block;">
                            + Associar Produto Existente a esta Categoria (Sem Duplicar)
                        </label>
                        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                            <select id="catAvailableSelect" class="form-select" style="flex: 1; min-width: 260px;">
                                <option value="">Carregando produtos disponíveis...</option>
                            </select>
                            <button type="button" class="btn-admin-gold" onclick="associateProductToCurrentCategory()" style="padding: 8px 16px; font-size: 0.82rem; white-space: nowrap;">
                                <span>+ Vincular Produto</span>
                            </button>
                        </div>
                        <span class="field-hint" style="margin-top: 6px; display: block; font-size: 0.74rem; color: #8e8880;">
                            O produto principal e todas as suas variantes cadastradas no Bling serão vinculados a esta coleção de forma unificada.
                        </span>
                    </div>

                    <!-- LISTA DINÂMICA DE PRODUTOS VINCULADOS -->
                    <div id="catProductsListContainer">
                        <div style="text-align: center; padding: 24px; color: #8e8880; font-size: 0.85rem;">
                            Carregando produtos vinculados...
                        </div>
                    </div>
                </div>

                <!-- AVISO PARA NOVA CATEGORIA -->
                <div id="catNewNotice" style="display: none; margin-top: 20px; padding: 14px 16px; background: rgba(167, 173, 180, 0.06); border: 1px dashed rgba(167, 173, 180, 0.25); border-radius: 8px; text-align: center; color: #b5ada3; font-size: 0.82rem;">
                    💡 <strong>Dica:</strong> Salve a nova categoria primeiro para poder vincular produtos e administrar suas variantes do Bling.
                </div>
            </form>
        </div>

        <!-- RODAPÉ DO MODAL -->
        <div class="modal-footer" style="padding: 16px 24px; border-top: 1px solid rgba(255,255,255,0.08); flex-shrink: 0; background: rgba(0,0,0,0.25);">
            <button type="button" class="btn-admin-secondary" onclick="closeCategoryModal()">Cancelar</button>
            <button type="button" class="btn-admin-gold" id="saveCatBtn" onclick="document.getElementById('categoryForm').requestSubmit()">Salvar Categoria</button>
        </div>
    </div>
</div>

<style>
/* ESTILOS EXCLUSIVOS DA GESTÃO DE PRODUTOS & VARIANTES NA CATEGORIA */
.cat-prod-card {
    background: #17191d;
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 8px;
    margin-bottom: 12px;
    overflow: hidden;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.cat-prod-card:hover {
    border-color: rgba(167, 173, 180, 0.3);
}
.cat-prod-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 14px;
    cursor: pointer;
    background: rgba(255, 255, 255, 0.015);
    user-select: none;
    gap: 12px;
}
.cat-prod-header:hover {
    background: rgba(255, 255, 255, 0.035);
}
.cat-prod-body {
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    background: rgba(0, 0, 0, 0.25);
    padding: 16px 18px;
}
.cat-prod-subtabs {
    display: flex;
    gap: 8px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    margin-bottom: 14px;
    padding-bottom: 4px;
}
.cat-subtab-btn {
    background: none;
    border: none;
    color: #8e8880;
    font-size: 0.8rem;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
}
.cat-subtab-btn.active {
    background: rgba(167, 173, 180, 0.15);
    color: #ffffff;
}
.variant-card-item {
    background: rgba(255, 255, 255, 0.025);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 6px;
    padding: 12px 14px;
    margin-bottom: 10px;
    transition: border-color 0.2s;
}
.variant-card-item:hover {
    border-color: rgba(167, 173, 180, 0.2);
}
.channel-row-item {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 6px;
    padding: 12px 14px;
    margin-bottom: 10px;
}
</style>

<script>
let currentEditingCategoryId = null;

function filterCategories() {
    const query = (document.getElementById('categorySearchInput')?.value || '').toLowerCase().trim();
    const rows = document.querySelectorAll('#categoryTableBody tr[data-name]');
    let count = 0;

    rows.forEach(r => {
        const name = r.getAttribute('data-name') || '';
        const slug = r.getAttribute('data-slug') || '';
        const desc = r.getAttribute('data-desc') || '';

        if (!query || name.includes(query) || slug.includes(query) || desc.includes(query)) {
            r.style.display = '';
            count++;
        } else {
            r.style.display = 'none';
        }
    });

    const countElem = document.getElementById('catVisibleCount');
    if (countElem) countElem.innerText = count;
}

function openNewCategoryModal(e) {
    if (e) e.preventDefault();
    currentEditingCategoryId = null;
    document.getElementById('categoryForm').reset();
    document.getElementById('catId').value = '';
    document.getElementById('catModalTitle').innerText = 'Nova Categoria';
    document.getElementById('catModalSubtitle').innerText = 'Crie uma nova coleção autoral para agrupar joias na Boutique.';
    
    // Ocultar produtos e exibir aviso de salvamento primeiro
    document.getElementById('catProductsSection').style.display = 'none';
    document.getElementById('catNewNotice').style.display = 'block';

    document.getElementById('categoryModal').classList.add('active');
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.remove('active');
    currentEditingCategoryId = null;
}

function editCategory(cat) {
    currentEditingCategoryId = cat.id;
    document.getElementById('catId').value = cat.id;
    document.getElementById('catName').value = cat.name || '';
    document.getElementById('catDesc').value = cat.description || '';
    document.getElementById('catOrder').value = cat.sort_order || 0;
    document.getElementById('catActive').checked = cat.is_active == 1;
    document.getElementById('catModalTitle').innerText = 'Editar: ' + cat.name;
    document.getElementById('catModalSubtitle').innerText = 'Gerencie os dados da coleção, modelos vinculados e suas variantes Bling.';

    // Exibir seção de produtos desta categoria
    document.getElementById('catProductsSection').style.display = 'block';
    document.getElementById('catNewNotice').style.display = 'none';

    document.getElementById('categoryModal').classList.add('active');

    // Carregar produtos e variantes da categoria via AJAX
    loadCategoryProducts(cat.id);
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

/**
 * Carrega a lista de produtos principais vinculados à categoria e suas variantes Bling
 */
async function loadCategoryProducts(catId) {
    const container = document.getElementById('catProductsListContainer');
    const selectAvailable = document.getElementById('catAvailableSelect');
    if (!container) return;

    container.innerHTML = `
        <div style="text-align: center; padding: 24px; color: #8e8880; font-size: 0.85rem;">
            <span style="display: inline-block; animation: spin 1s linear infinite; margin-right: 6px;">⟳</span>
            Carregando produtos e variantes cadastradas no Bling...
        </div>
    `;

    try {
        const response = await fetch(`/admin/categorias/produtos?categoria_id=${catId}`, {
            headers: { 'Accept': 'application/json' }
        });
        const data = await response.json();

        if (!data.success) {
            container.innerHTML = `<div style="padding: 16px; color: #f87171; font-size: 0.85rem;">${escapeHtml(data.error || 'Erro ao carregar produtos.')}</div>`;
            return;
        }

        // Atualizar contadores separados
        document.getElementById('catCountMain').innerText = data.main_products_count || 0;
        document.getElementById('catCountVars').innerText = data.variants_count || 0;

        // Preencher dropdown de produtos disponíveis para associar
        if (selectAvailable) {
            selectAvailable.innerHTML = '<option value="">-- Selecione uma joia existente para vincular --</option>';
            if (data.available_products && data.available_products.length > 0) {
                data.available_products.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.id;
                    const catInfo = p.category_name ? ` [Atual: ${p.category_name}]` : ' [Sem categoria]';
                    opt.textContent = `${p.name} (SKU: ${p.sku})${catInfo}`;
                    selectAvailable.appendChild(opt);
                });
            } else {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'Nenhum outro produto disponível para associar';
                opt.disabled = true;
                selectAvailable.appendChild(opt);
            }
        }

        // Renderizar lista de produtos principais vinculados
        renderCategoryProductsList(data.products || []);

    } catch (err) {
        container.innerHTML = `<div style="padding: 16px; color: #f87171; font-size: 0.85rem;">Falha na conexão: ${escapeHtml(err.message)}</div>`;
    }
}

/**
 * Renderiza os cards dos produtos principais com gaveta de variantes e canais de venda
 */
function renderCategoryProductsList(products) {
    const container = document.getElementById('catProductsListContainer');
    if (!container) return;

    if (!products || products.length === 0) {
        container.innerHTML = `
            <div style="text-align: center; padding: 30px 20px; background: rgba(255,255,255,0.015); border: 1px dashed rgba(255,255,255,0.08); border-radius: 8px; color: #8e8880;">
                <p style="margin: 0; font-size: 0.88rem; color: #b5ada3;">Nenhum produto principal vinculado a esta categoria ainda.</p>
                <p style="margin: 4px 0 0; font-size: 0.78rem;">Selecione um produto existente no campo acima e clique em "+ Vincular Produto".</p>
            </div>
        `;
        return;
    }

    let html = '';
    products.forEach(prod => {
        const variants = prod.variants || [];
        const links = prod.links || [];
        const prodImg = prod.image_url || '/assets/images/hero/featured-green-shell.jpg';

        html += `
            <div class="cat-prod-card" id="cat-prod-card-${prod.id}">
                <!-- CABEÇALHO DO PRODUTO PRINCIPAL -->
                <div class="cat-prod-header" onclick="toggleProductVariantsAccordion(${prod.id})">
                    <div style="display: flex; align-items: center; gap: 12px; flex: 1; min-width: 0;">
                        <img src="${escapeHtml(prodImg)}" alt="" style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(255,255,255,0.12); flex-shrink: 0;" onerror="this.src='/assets/images/hero/featured-green-shell.jpg'">
                        <div style="min-width: 0;">
                            <strong style="color: #ffffff; font-size: 0.92rem; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                ${escapeHtml(prod.name)}
                            </strong>
                            <div style="display: flex; align-items: center; gap: 8px; margin-top: 3px; flex-wrap: wrap;">
                                <span style="font-family: monospace; font-size: 0.74rem; background: rgba(255,255,255,0.06); padding: 1px 6px; border-radius: 4px; color: #A7ADB4;">
                                    SKU: ${escapeHtml(prod.sku)}
                                </span>
                                <span style="font-size: 0.78rem; color: #E5E7EB; font-weight: 500;">
                                    ${escapeHtml(prod.price_formatted || 'R$ 380,00')}
                                </span>
                                <span style="font-size: 0.74rem; padding: 1px 6px; border-radius: 4px; background: rgba(167, 173, 180, 0.15); color: #FFFFFF;">
                                    ${prod.stock_quantity || 0} un. estoque
                                </span>
                                <span style="font-size: 0.74rem; padding: 1px 6px; border-radius: 4px; background: rgba(167, 173, 180, 0.15); color: #D1D5DB; font-weight: 600;">
                                    ${variants.length} variantes Bling
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;" onclick="event.stopPropagation()">
                        <button type="button" class="btn-table-action" onclick="toggleProductVariantsAccordion(${prod.id})" style="display: flex; align-items: center; gap: 6px;">
                            <span id="accordion-text-${prod.id}">Variantes & Canais</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" id="accordion-arrow-${prod.id}" style="transition: transform 0.2s;"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>
                        <button type="button" class="btn-table-action btn-delete" onclick="disassociateProductFromCategory(${prod.id}, '${escapeHtml(prod.name)}')" title="Desvincular produto desta categoria sem apagar do catálogo">
                            Desvincular
                        </button>
                    </div>
                </div>

                <!-- PAINEL EXPANSÍVEL: VARIANTES BLING & CANAIS DE VENDA COMPARTILHADOS -->
                <div class="cat-prod-body" id="cat-prod-body-${prod.id}" style="display: none;">
                    <div class="cat-prod-subtabs">
                        <button type="button" class="cat-subtab-btn active" id="subtab-btn-vars-${prod.id}" onclick="switchProdSubTab(${prod.id}, 'vars')">
                            💎 Variantes do Bling (${variants.length})
                        </button>
                        <button type="button" class="cat-subtab-btn" id="subtab-btn-channels-${prod.id}" onclick="switchProdSubTab(${prod.id}, 'channels')">
                            🔗 Canais de Venda & Links Compartilhados (${links.length})
                        </button>
                    </div>

                    <!-- SUB-ABA 1: VARIANTES DO PRODUTO (BLING) -->
                    <div id="subtab-pane-vars-${prod.id}">
                        <div style="margin-bottom: 12px; font-size: 0.78rem; color: #8e8880;">
                            Variantes registradas no Bling para este modelo. Você pode editar o nome da opção/gema, SKU, preço, estoque e imagem individual de cada uma.
                        </div>

                        ${variants.length > 0 ? variants.map(v => `
                            <div class="variant-card-item" id="var-item-${v.id || v.sku}">
                                <div style="display: flex; gap: 12px; align-items: flex-start; flex-wrap: wrap;">
                                    <div style="text-align: center; flex-shrink: 0;">
                                        <img src="${escapeHtml(v.image_url || prodImg)}" id="var-img-preview-${v.sku}" style="width: 52px; height: 52px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(255,255,255,0.12);" onerror="this.src='/assets/images/hero/featured-green-shell.jpg'">
                                    </div>
                                    <div style="flex: 1; min-width: 260px;">
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 8px;">
                                            <div>
                                                <label style="font-size: 0.72rem; color: #8e8880; display: block; margin-bottom: 2px;">Nome da Opção / Gema Mineral</label>
                                                <input type="text" class="form-input var-stone" value="${escapeHtml(v.stone_mineral || v.name)}" placeholder="Ex: Quartzo Verde, Madrepérola..." style="font-size: 0.8rem; padding: 6px 10px;">
                                            </div>
                                            <div>
                                                <label style="font-size: 0.72rem; color: #8e8880; display: block; margin-bottom: 2px;">SKU da Variante</label>
                                                <input type="text" class="form-input var-sku" value="${escapeHtml(v.sku)}" placeholder="SKU Variante" style="font-family: monospace; font-size: 0.78rem; padding: 6px 10px;">
                                            </div>
                                        </div>
                                        <div style="display: grid; grid-template-columns: 1fr 1fr 1.6fr; gap: 8px; margin-bottom: 8px;">
                                            <div>
                                                <label style="font-size: 0.72rem; color: #8e8880; display: block; margin-bottom: 2px;">Preço (R$)</label>
                                                <input type="text" class="form-input var-price" value="${escapeHtml((v.price_formatted || '').replace('R$ ', ''))}" placeholder="380,00" style="font-size: 0.8rem; padding: 6px 10px;">
                                            </div>
                                            <div>
                                                <label style="font-size: 0.72rem; color: #8e8880; display: block; margin-bottom: 2px;">Estoque Físico</label>
                                                <input type="number" class="form-input var-stock" value="${v.stock_quantity || 0}" min="0" placeholder="0" style="font-size: 0.8rem; padding: 6px 10px;">
                                            </div>
                                            <div>
                                                <label style="font-size: 0.72rem; color: #8e8880; display: block; margin-bottom: 2px;">URL da Imagem da Variante</label>
                                                <input type="text" class="form-input var-image" value="${escapeHtml(v.image_url || '')}" placeholder="/assets/images/products/..." style="font-size: 0.76rem; padding: 6px 10px;" oninput="document.getElementById('var-img-preview-${v.sku}').src = this.value || '${escapeHtml(prodImg)}'">
                                            </div>
                                        </div>
                                    </div>
                                    <div style="align-self: center; flex-shrink: 0;">
                                        <button type="button" class="btn-admin-gold" onclick="saveVariantData(${v.id || 0}, '${escapeHtml(v.sku)}', this)" style="padding: 6px 14px; font-size: 0.78rem; white-space: nowrap;">
                                            <span>Salvar Variante</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `).join('') : `
                            <div style="padding: 16px; background: rgba(255,255,255,0.02); border-radius: 6px; color: #8e8880; font-size: 0.8rem; text-align: center;">
                                Nenhuma variante mineral secundária identificada para este modelo. A peça base atua como opção única.
                            </div>
                        `}
                    </div>

                    <!-- SUB-ABA 2: CANAIS DE VENDA / COMPRA COMPARTILHADOS -->
                    <div id="subtab-pane-channels-${prod.id}" style="display: none;">
                        <div style="margin-bottom: 12px; font-size: 0.78rem; color: #8e8880;">
                            Configure os links de compra deste produto principal (Shopee, Mercado Livre, Amazon, WhatsApp, Asaas/Pix). <strong>Todas as variantes compartilham estes mesmos links</strong> de cada canal.
                        </div>

                        <div id="channels-container-${prod.id}">
                            ${links.map((link, lIdx) => renderChannelRowHtml(prod.id, lIdx, link)).join('')}
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 14px; flex-wrap: wrap; gap: 10px;">
                            <button type="button" class="btn-admin-secondary" onclick="addChannelRowToProduct(${prod.id})" style="padding: 6px 12px; font-size: 0.78rem;">
                                + Adicionar Canal de Venda
                            </button>
                            <button type="button" class="btn-admin-gold" onclick="saveProductChannelsData(${prod.id}, this)" style="padding: 6px 16px; font-size: 0.8rem;">
                                <span>Salvar Canais Deste Modelo</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

function getPlatformLogoSvg(platform, size = 15) {
    const raw = String(platform || '').trim().toLowerCase();
    const s = parseInt(size, 10) || 15;

    if (raw.includes('shopee')) {
        return `<svg class="channel-logo-svg" width="${s}" height="${s}" viewBox="0 0 250 260" fill="none" style="flex-shrink:0; vertical-align:middle;"><path fill-rule="evenodd" clip-rule="evenodd" d="M19.4 61.2h46.7C67.6 27.2 92.8 0 125 0s57.4 27.2 58.9 61.2h46.7c2.8 0 5 2.3 5 5.2l-8.6 174.5c-.9 12.3-10.9 22.1-23 22.4H39.5c-12-.5-20.7-10.1-21.9-22.3L9.7 66.8c0-2.8 2.2-5.1 5-5.2h4.7zm62.9 0h85.4C146.8 33.9 128 12.1 105 12.1s-41.8 21.8-42.7 49.1zm93.4 141.9c2.6-21-10.9-34.3-46.3-45.7-17.4-5.9-25.4-13.7-25.3-24.5.7-12 11.7-20.7 26.4-20.9 11.8.2 22.6 3.5 32.1 10 1.2.7 2.1.6 2.7-.4.3-.6.4-.7 2.1-3.4v0c1.2-2 1.9-3 2.1-3.6.7-1 .5-2-.7-2.8-1.9-1.5-7.7-4.6-10.9-6-9-3.8-18.4-5.7-27.7-5.7-21.1.1-37.8 13.5-39.2 31.4-.9 13 5.5 23.5 19.1 31.5 2.9 1.8 18.7 8 24.9 9.9 19.9 6.3 30.1 17.5 27.7 30.4-2.3 11.9-14.5 19.5-31.6 19.8-12.3-.5-24.5-5.1-34.9-13.3-.1-.2-.4-.3-.8-.6-.4-.3-.4-.3-.5-.5-.1-.1-.1-.1-.2-.2-1.1-.8-2.2-.7-2.9.3-.2.4-.5.8-2.6 3.9-1.4 2.1-2.1 3.1-2.4 3.7-.7 1-.5 1.4.4 2.4 4 3.3 9.2 6.7 12.6 8.6 9.8 4.9 20.2 7.7 31.4 8.1 7.2.5 16.3-1.1 23.1-4 12.1-5.1 19.9-15.5 21.4-28.4z" fill="#EE4D2D"/></svg>`;
    }
    if (raw.includes('mercado') || raw.includes('ml') || raw.includes('livre')) {
        return `<svg class="channel-logo-svg" width="${s}" height="${s}" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><ellipse cx="12" cy="12" rx="11" ry="8.5" fill="#FFE600"/><g fill="#2D3277" transform="translate(1.2, 2.2) scale(0.9)"><path d="M11.115 16.479a.93.927 0 0 1-.939-.886c-.002-.042-.006-.155-.103-.155-.04 0-.074.023-.113.059-.112.103-.254.206-.46.206a.816.814 0 0 1-.305-.066c-.535-.214-.542-.578-.521-.725.006-.038.007-.08-.02-.11l-.032-.03h-.034c-.027 0-.055.012-.093.039a.788.786 0 0 1-.454.16.7.699 0 0 1-.253-.05c-.708-.27-.65-.928-.617-1.126.005-.041-.005-.072-.03-.092l-.05-.04-.047.043a.728.726 0 0 1-.505.203.73.728 0 0 1-.732-.725c0-.4.328-.722.732-.722.364 0 .675.27.721.63l.026.195.11-.165c.01-.018.307-.46.852-.46.102 0 .21.016.316.05.434.13.508.52.519.68.008.094.075.1.09.1.037 0 .064-.024.083-.045a.746.744 0 0 1 .54-.225c.128 0 .263.03.402.09.69.293.379 1.158.374 1.167-.058.144-.061.207-.005.244l.027.013h.02c.03 0 .07-.014.134-.035.093-.032.235-.08.367-.08a.944.942 0 0 1 .94.93.936.934 0 0 1-.94.928zm7.302-4.171c-1.138-.98-3.768-3.24-4.481-3.77-.406-.302-.685-.462-.928-.533a1.559 1.554 0 0 0-.456-.07c-.182 0-.376.032-.58.095-.46.145-.918.505-1.362.854l-.023.018c-.414.324-.84.66-1.164.73a1.986 1.98 0 0 1-.43.049c-.362 0-.687-.104-.81-.258-.02-.025-.007-.066.04-.125l.008-.008 1-1.067c.783-.774 1.525-1.506 3.23-1.545h.085c1.062 0 2.12.469 2.24.524a7.03 7.03 0 0 0 3.056.724c1.076 0 2.188-.263 3.354-.795a9.135 9.11 0 0 0-.405-.317c-1.025.44-2.003.66-2.946.66-.962 0-1.925-.229-2.858-.68-.05-.022-1.22-.567-2.44-.57-.032 0-.065 0-.096.002-1.434.033-2.24.536-2.782.976-.528.013-.982.138-1.388.25-.361.1-.673.186-.979.185-.125 0-.35-.01-.37-.012-.35-.01-2.115-.437-3.518-.962-.143.1-.28.203-.415.31 1.466.593 3.25 1.053 3.812 1.089.157.01.323.027.491.027.372 0 .744-.103 1.104-.203.213-.059.446-.123.692-.17l-.196.194-1.017 1.087c-.08.08-.254.294-.14.557a.705.703 0 0 0 .268.292c.243.162.677.27 1.08.271.152 0 .297-.015.43-.044.427-.095.874-.448 1.349-.82.377-.296.913-.672 1.323-.782a1.494 1.49 0 0 1 .37-.05.611.61 0 0 1 .095.005c.27.034.533.125 1.003.472.835.62 4.531 3.815 4.566 3.846.002.002.238.203.22.537-.007.186-.11.352-.294.466a.902.9 0 0 1-.484.15.804.802 0 0 1-.428-.124c-.014-.01-1.28-1.157-1.746-1.543-.074-.06-.146-.115-.22-.115a.122.122 0 0 0-.096.045c-.073.09.01.212.105.294l1.48 1.47c.002 0 .184.17.204.395.012.244-.106.447-.35.606a.957.955 0 0 1-.526.171.766.764 0 0 1-.42-.127l-.214-.206a21.035 20.978 0 0 0-1.08-1.009c-.072-.058-.148-.112-.221-.112a.127.127 0 0 0-.094.038c-.033.037-.056.103.028.212a.698.696 0 0 0 .075.083l1.078 1.198c.01.01.222.26.024.511l-.038.048a1.18 1.178 0 0 1-.1.096c-.184.15-.43.164-.527.164a.8.798 0 0 1-.147-.012c-.106-.018-.178-.048-.212-.089l-.013-.013c-.06-.06-.602-.609-1.054-.98-.059-.05-.133-.11-.21-.11a.128.128 0 0 0-.096.042c-.09.096.044.24.1.293l.92 1.003a.204.204 0 0 1-.033.062c-.033.044-.144.155-.479.196a.91.907 0 0 1-.122.007c-.345 0-.712-.164-.902-.264a1.343 1.34 0 0 0 .13-.576 1.368 1.365 0 0 0-1.42-1.357c.024-.342-.025-.99-.697-1.274a1.455 1.452 0 0 0-.575-.125c-.146 0-.287.025-.42.075a1.153 1.15 0 0 0-.671-.564 1.52 1.515 0 0 0-.494-.085c-.28 0-.537.08-.767.242a1.168 1.165 0 0 0-.903-.43 1.173 1.17 0 0 0-.82.335c-.287-.217-1.425-.93-4.467-1.613a17.39 17.344 0 0 1-.692-.189 4.822 4.82 0 0 0-.077.494l.67.157c3.108.682 4.136 1.391 4.309 1.525a1.145 1.142 0 0 0-.09.442 1.16 1.158 0 0 0 1.378 1.132c.096.467.406.821.879 1.003a1.165 1.162 0 0 0 .415.08c.09 0 .179-.012.266-.034.086.22.282.493.722.668a1.233 1.23 0 0 0 .457.094c.122 0 .241-.022.355-.063a1.373 1.37 0 0 0 1.269.841c.37.002.726-.147.985-.41.221.121.688.341 1.163.341.06 0 .118-.002.175-.01.47-.059.689-.24.789-.382a.571.57 0 0 0 .048-.078c.11.032.234.058.373.058.255 0 .501-.086.75-.265.244-.174.418-.424.444-.637v-.01c.083.017.167.026.251.026.265 0 .527-.082.773-.242.48-.31.562-.715.554-.98a1.28 1.279 0 0 0 .978-.194 1.04 1.04 0 0 0 .502-.808 1.088 1.085 0 0 0-.16-.653c.804-.342 2.636-1.003 4.795-1.483a4.734 4.721 0 0 0-.067-.492 27.742 27.667 0 0 0-5.049 1.62z"/></g></svg>`;
    }
    if (raw.includes('tiktok')) {
        return `<svg class="channel-logo-svg" width="${s}" height="${s}" viewBox="0 0 260 292" fill="none" style="flex-shrink:0; vertical-align:middle;"><path fill="#00F2EA" d="M243.838 77.189V66.999c-12.529.019-24.812-3.488-35.442-10.12 9.41 10.297 21.801 17.397 35.442 20.31zM177.817 11.655c-.319-1.822-.564-3.656-.734-5.497V0h-48.182v191.228c-.077 22.29-18.177 40.341-40.501 40.341-6.554 0-12.742-1.555-18.222-4.318 7.401 9.707 19.087 15.973 32.241 15.973 22.32 0 40.424-18.049 40.502-40.342V11.655h34.896zM100.694 114.408v-10.848c-4.026-.55-8.085-.826-12.149-.824C39.642 102.735 0 142.356 0 191.228c0 30.64 15.58 57.643 39.255 73.527-15.615-15.953-25.236-37.789-25.236-61.874 0-48.249 38.634-87.481 86.675-88.473z"/><path fill="#FF004F" d="M191.102 105.182c18.814 13.442 41.862 21.351 66.755 21.351V78.656c-4.711.001-9.41-.49-14.019-1.466v37.686c-24.891 0-47.936-7.909-66.755-21.35v97.703c0 48.876-39.642 88.495-88.54 88.495-18.245 0-35.203-5.513-49.29-14.968 16.078 16.431 38.5 26.624 63.306 26.624 48.901 0 88.545-39.619 88.545-88.497v-97.701H191.102zM208.396 56.88c-9.615-10.499-15.928-24.067-17.294-39.067v-6.158h-13.285c3.344 19.065 14.75 35.353 30.579 45.225zM70.181 227.25c-5.372-7.04-8.275-15.652-8.262-24.507c0-22.354 18.132-40.479 40.502-40.479 c4.169-.001 8.313.637 12.286 1.897v-48.947c-4.643-.636-9.329-.906-14.013-.807v38.098c-3.976-1.26-8.122-1.9-12.292-1.896-22.37 0-40.501 18.123-40.501 40.48 0 15.806 9.063 29.492 22.28 36.159z"/><path fill="#000000" d="M177.083 93.525c18.819 13.441 41.864 21.35 66.755 21.35V77.189c-13.894-2.958-26.194-10.215-35.442-20.309-15.83-9.873-27.235-26.161-30.579-45.225h-34.896v191.226c-.079 22.293-18.18 40.344-40.502 40.344-13.154 0-24.84-6.267-32.241-15.975-13.216-6.667-22.279-20.354-22.279-36.16 0-22.355 18.131-40.48 40.501-40.48 4.286 0 8.417.667 12.292 1.896v-38.098c-48.039.992-86.674 40.224-86.674 88.474 0 24.086 9.621 45.921 25.236 61.875 14.087 9.454 31.045 14.968 49.29 14.968 48.899 0 88.54-39.621 88.54-88.496V93.525h-15.25z"/></svg>`;
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
    if (raw.includes('divinium') || raw.includes('loja oficial') || raw.includes('oficial') || raw.includes('diret') || raw.includes('boutique') || raw.includes('site') || raw.includes('checkout')) {
        return `<img src="/assets/images/brand/divinium-canal-oficial.png" alt="DIVINIUM" class="channel-logo-img" style="width:${s}px; height:${s}px; object-fit:contain; flex-shrink:0; vertical-align:middle; display:inline-block; border-radius:3px;">`;
    }
    return `<svg class="channel-logo-svg" width="${s}" height="${s}" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#50555E"/><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round"/></svg>`;
}

function renderChannelRowHtml(prodId, idx, link = {}) {
    const plat = link.platform_name || 'Shopee';
    const isCustomPlat = !['Loja Oficial DIVINIUM', 'Shopee', 'Mercado Livre', 'Amazon', 'WhatsApp', 'Compra Direta', 'Asaas Pix', 'TikTok Shop', 'Enjoei', 'Elo7', 'Instagram Shop'].includes(plat);
    const selectedPlat = isCustomPlat ? 'Outro' : plat;
    const url = link.url || '';
    const btnText = link.button_text || (plat === 'Shopee' ? 'Comprar na Shopee Oficial' : (plat === 'Loja Oficial DIVINIUM' ? 'Comprar na Loja Oficial DIVINIUM' : `Comprar no ${plat}`));
    const price = link.price_cents ? (link.price_cents / 100).toFixed(2).replace('.', ',') : (link.price || '');
    const badge = link.badge || '';
    const target = link.target || (url.startsWith('/') ? '_self' : '_blank');
    const isActive = link.is_active !== undefined ? (link.is_active == 1) : true;
    const bgColor = link.button_bg_color || '';
    const textColor = link.button_text_color || '';

    return `
        <div class="channel-row-item channel-item-${prodId}" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.07); border-radius: 8px; padding: 12px; margin-bottom: 10px;">
            <div style="display: grid; grid-template-columns: 190px 1fr 110px; gap: 10px; margin-bottom: 8px;">
                <div>
                    <label style="font-size: 0.7rem; color: #8e8880; display: block; margin-bottom: 2px;">Plataforma</label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span class="category-row-logo" style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; flex-shrink: 0; border-radius: 4px; overflow: hidden; background: #ffffff; padding: 2px; box-shadow: 0 1px 3px rgba(0,0,0,0.15);">
                            ${getPlatformLogoSvg(plat, 18)}
                        </span>
                        <select class="form-select channel-plat" style="font-size: 0.78rem; padding: 6px 8px; flex: 1;" onchange="onCategoryChannelPlatChange(this)">
                            <option value="Loja Oficial DIVINIUM" ${selectedPlat === 'Loja Oficial DIVINIUM' ? 'selected' : ''}>✦ Loja Oficial DIVINIUM</option>
                            <option value="Shopee" ${selectedPlat === 'Shopee' ? 'selected' : ''}>Shopee</option>
                            <option value="Mercado Livre" ${selectedPlat === 'Mercado Livre' ? 'selected' : ''}>Mercado Livre</option>
                            <option value="Amazon" ${selectedPlat === 'Amazon' ? 'selected' : ''}>Amazon</option>
                            <option value="WhatsApp" ${selectedPlat === 'WhatsApp' ? 'selected' : ''}>WhatsApp</option>
                            <option value="Compra Direta" ${selectedPlat === 'Compra Direta' ? 'selected' : ''}>Compra Direta</option>
                            <option value="Asaas Pix" ${selectedPlat === 'Asaas Pix' ? 'selected' : ''}>Asaas Pix</option>
                            <option value="TikTok Shop" ${selectedPlat === 'TikTok Shop' ? 'selected' : ''}>TikTok Shop</option>
                            <option value="Enjoei" ${selectedPlat === 'Enjoei' ? 'selected' : ''}>Enjoei</option>
                            <option value="Elo7" ${selectedPlat === 'Elo7' ? 'selected' : ''}>Elo7</option>
                            <option value="Instagram Shop" ${selectedPlat === 'Instagram Shop' ? 'selected' : ''}>Instagram</option>
                            <option value="Outro" ${selectedPlat === 'Outro' ? 'selected' : ''}>Outro (Personalizado)</option>
                        </select>
                    </div>
                    <input type="text" class="form-input channel-custom-plat" value="${isCustomPlat ? escapeHtml(plat) : ''}" placeholder="Nome do Canal" style="margin-top: 4px; font-size: 0.75rem; padding: 4px 6px; display: ${isCustomPlat ? 'block' : 'none'};" oninput="const logo=this.closest('div').querySelector('.category-row-logo'); if(logo) logo.innerHTML=getPlatformLogoSvg(this.value, 18);">
                </div>
                <div>
                    <label style="font-size: 0.7rem; color: #8e8880; display: block; margin-bottom: 2px;">URL de Compra</label>
                    <input type="text" class="form-input channel-url" value="${escapeHtml(url)}" placeholder="https://..." style="font-size: 0.78rem; padding: 6px 10px;">
                </div>
                <div>
                    <label style="font-size: 0.7rem; color: #8e8880; display: block; margin-bottom: 2px;">Preço (R$)</label>
                    <input type="text" class="form-input channel-price" value="${escapeHtml(price)}" placeholder="Padrão" style="font-size: 0.78rem; padding: 6px 8px;">
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1.5fr 1fr 120px auto; gap: 10px; align-items: flex-end;">
                <div>
                    <label style="font-size: 0.7rem; color: #8e8880; display: block; margin-bottom: 2px;">Texto do Botão</label>
                    <input type="text" class="form-input channel-btn-text" value="${escapeHtml(btnText)}" placeholder="Ex: Comprar agora" style="font-size: 0.78rem; padding: 6px 10px;">
                </div>
                <div>
                    <label style="font-size: 0.7rem; color: #8e8880; display: block; margin-bottom: 2px;">Selo / Destaque</label>
                    <input type="text" class="form-input channel-badge" value="${escapeHtml(badge)}" placeholder="Ex: Oficial, Frete Grátis..." style="font-size: 0.78rem; padding: 6px 10px;">
                </div>
                <div>
                    <label style="font-size: 0.7rem; color: #8e8880; display: block; margin-bottom: 2px;">Destino</label>
                    <select class="form-select channel-target" style="font-size: 0.76rem; padding: 6px 6px;">
                        <option value="_blank" ${target === '_blank' ? 'selected' : ''}>Nova Aba (↗)</option>
                        <option value="_self" ${target === '_self' ? 'selected' : ''}>Mesma Aba (↻)</option>
                    </select>
                </div>
                <div style="display: flex; align-items: center; gap: 8px; padding-bottom: 4px;">
                    <label style="display: flex; align-items: center; gap: 4px; font-size: 0.76rem; color: #EDE7DF; cursor: pointer; margin: 0;">
                        <input type="checkbox" class="channel-active" ${isActive ? 'checked' : ''} style="accent-color: #22c55e;"> Ativo
                    </label>
                    <button type="button" class="btn-table-action btn-delete" onclick="this.closest('.channel-row-item').remove()" title="Remover Canal" style="padding: 5px 8px; font-size: 0.74rem;">
                        ✕
                    </button>
                </div>
            </div>
            <!-- Personalização de Cores do Botão (Fundo e Letra) -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 8px; padding-top: 8px; border-top: 1px dashed rgba(255,255,255,0.08);">
                <div>
                    <label style="font-size: 0.7rem; color: #8e8880; display: block; margin-bottom: 2px;">Fundo do Botão</label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="color" value="${bgColor && bgColor.startsWith('#') && bgColor.length === 7 ? bgColor : '#202020'}" 
                               onchange="this.nextElementSibling.value = this.value" 
                               style="width: 30px; height: 28px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer; padding: 1px;">
                        <input type="text" class="form-input channel-bg-color" value="${escapeHtml(bgColor)}" placeholder="Padrão (Ex: #ded7cf ou #202020)" 
                               oninput="if(this.value.startsWith('#') && this.value.length === 7) this.previousElementSibling.value = this.value"
                               style="font-size: 0.76rem; padding: 5px 8px; font-family: monospace; flex: 1;">
                    </div>
                </div>
                <div>
                    <label style="font-size: 0.7rem; color: #8e8880; display: block; margin-bottom: 2px;">Cor da Letra (Texto)</label>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="color" value="${textColor && textColor.startsWith('#') && textColor.length === 7 ? textColor : '#FFFFFF'}" 
                               onchange="this.nextElementSibling.value = this.value" 
                               style="width: 30px; height: 28px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer; padding: 1px;">
                        <input type="text" class="form-input channel-text-color" value="${escapeHtml(textColor)}" placeholder="Padrão (Ex: #FFFFFF ou #202020)" 
                               oninput="if(this.value.startsWith('#') && this.value.length === 7) this.previousElementSibling.value = this.value"
                               style="font-size: 0.76rem; padding: 5px 8px; font-family: monospace; flex: 1;">
                    </div>
                </div>
            </div>
        </div>
    `;
}

function onCategoryChannelPlatChange(selectElem) {
    const row = selectElem.closest('.channel-row-item');
    if (!row) return;
    const customInput = row.querySelector('.channel-custom-plat');
    const logoSpan = row.querySelector('.category-row-logo');
    const val = selectElem.value;
    if (val === 'Outro') {
        if (customInput) customInput.style.display = 'block';
        if (logoSpan) logoSpan.innerHTML = getPlatformLogoSvg(customInput ? customInput.value : 'Outro', 18);
    } else {
        if (customInput) customInput.style.display = 'none';
        if (logoSpan) logoSpan.innerHTML = getPlatformLogoSvg(val, 18);
        const btnTextInput = row.querySelector('.channel-btn-text');
        if (btnTextInput && (!btnTextInput.value || btnTextInput.value.startsWith('Comprar'))) {
            if (val === 'Loja Oficial DIVINIUM') {
                btnTextInput.value = 'Comprar na Loja Oficial DIVINIUM';
            } else {
                btnTextInput.value = (val === 'Shopee' || val === 'Amazon') ? ('Comprar na ' + val) : ('Comprar no ' + val);
            }
        }
    }
}

function toggleProductVariantsAccordion(prodId) {
    const body = document.getElementById(`cat-prod-body-${prodId}`);
    const arrow = document.getElementById(`accordion-arrow-${prodId}`);
    if (!body) return;

    if (body.style.display === 'none' || body.style.display === '') {
        body.style.display = 'block';
        if (arrow) arrow.style.transform = 'rotate(180deg)';
    } else {
        body.style.display = 'none';
        if (arrow) arrow.style.transform = 'rotate(0deg)';
    }
}

function switchProdSubTab(prodId, tabName) {
    const paneVars = document.getElementById(`subtab-pane-vars-${prodId}`);
    const paneChannels = document.getElementById(`subtab-pane-channels-${prodId}`);
    const btnVars = document.getElementById(`subtab-btn-vars-${prodId}`);
    const btnChannels = document.getElementById(`subtab-btn-channels-${prodId}`);

    if (tabName === 'vars') {
        if (paneVars) paneVars.style.display = 'block';
        if (paneChannels) paneChannels.style.display = 'none';
        if (btnVars) btnVars.classList.add('active');
        if (btnChannels) btnChannels.classList.remove('active');
    } else {
        if (paneVars) paneVars.style.display = 'none';
        if (paneChannels) paneChannels.style.display = 'block';
        if (btnVars) btnVars.classList.remove('active');
        if (btnChannels) btnChannels.classList.add('active');
    }
}

function addChannelRowToProduct(prodId) {
    const container = document.getElementById(`channels-container-${prodId}`);
    if (!container) return;

    const div = document.createElement('div');
    div.innerHTML = renderChannelRowHtml(prodId, Date.now(), {
        platform_name: 'Shopee',
        url: '',
        button_text: 'Comprar na Shopee Oficial',
        is_active: 1
    });
    container.appendChild(div.firstElementChild);
}

/**
 * Associa produto existente à categoria sem duplicar registros
 */
async function associateProductToCurrentCategory() {
    if (!currentEditingCategoryId) {
        alert('Por favor, salve a categoria primeiro.');
        return;
    }

    const select = document.getElementById('catAvailableSelect');
    const productId = select?.value;

    if (!productId) {
        alert('Selecione uma joia existente para vincular.');
        return;
    }

    const formData = new FormData();
    formData.append('category_id', currentEditingCategoryId);
    formData.append('product_id', productId);
    const csrfToken = getCsrfToken();
    if (csrfToken) formData.append('_csrf_token', csrfToken);

    try {
        const response = await fetch('/admin/categorias/associar-produto', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const res = await response.json();
        if (res.success) {
            loadCategoryProducts(currentEditingCategoryId);
        } else {
            alert(res.error || 'Erro ao vincular produto.');
        }
    } catch (err) {
        alert('Falha ao conectar com o servidor: ' + err.message);
    }
}

/**
 * Desassocia produto principal e suas variantes desta categoria
 */
async function disassociateProductFromCategory(productId, productName) {
    if (!confirm(`Deseja desassociar "${productName}" e suas variantes desta categoria?\n\nO produto permanecerá intacto no catálogo da loja.`)) {
        return;
    }

    const formData = new FormData();
    formData.append('product_id', productId);
    const csrfToken = getCsrfToken();
    if (csrfToken) formData.append('_csrf_token', csrfToken);

    try {
        const response = await fetch('/admin/categorias/desassociar-produto', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const res = await response.json();
        if (res.success) {
            loadCategoryProducts(currentEditingCategoryId);
        } else {
            alert(res.error || 'Erro ao desassociar produto.');
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
    }
}

/**
 * Salva alterações em uma variante do Bling (pedra, SKU, preço, estoque, imagem)
 */
async function saveVariantData(variantId, defaultSku, btnElem) {
    const card = btnElem.closest('.variant-card-item');
    if (!card) return;

    const stone = card.querySelector('.var-stone')?.value || '';
    const sku = card.querySelector('.var-sku')?.value || defaultSku;
    const price = card.querySelector('.var-price')?.value || '';
    const stock = card.querySelector('.var-stock')?.value || '0';
    const image = card.querySelector('.var-image')?.value || '';

    const origText = btnElem.innerText;
    btnElem.disabled = true;
    btnElem.innerText = 'Salvando...';

    const formData = new FormData();
    if (variantId > 0) formData.append('variant_id', variantId);
    formData.append('sku', sku);
    formData.append('stone_mineral', stone);
    formData.append('price', price);
    formData.append('stock_quantity', stock);
    formData.append('image_url', image);

    const csrfToken = getCsrfToken();
    if (csrfToken) formData.append('_csrf_token', csrfToken);

    try {
        const response = await fetch('/admin/categorias/salvar-variante', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const res = await response.json();
        if (res.success) {
            btnElem.innerText = '✓ Salvo!';
            setTimeout(() => {
                btnElem.disabled = false;
                btnElem.innerText = origText;
            }, 1800);
        } else {
            alert(res.error || 'Erro ao salvar variante.');
            btnElem.disabled = false;
            btnElem.innerText = origText;
        }
    } catch (err) {
        alert('Erro de conexão: ' + err.message);
        btnElem.disabled = false;
        btnElem.innerText = origText;
    }
}

/**
 * Salva canais de venda configurados para um produto principal
 */
async function saveProductChannelsData(productId, btnElem) {
    const rows = document.querySelectorAll(`.channel-item-${productId}`);
    const links = [];

    rows.forEach((row, idx) => {
        let plat = row.querySelector('.channel-plat')?.value || 'Shopee';
        if (plat === 'Outro') {
            const customPlat = row.querySelector('.channel-custom-plat')?.value.trim();
            if (customPlat) plat = customPlat;
        }
        const url = row.querySelector('.channel-url')?.value || '';
        const price = row.querySelector('.channel-price')?.value || '';
        const btnText = row.querySelector('.channel-btn-text')?.value || '';
        const badge = row.querySelector('.channel-badge')?.value || '';
        const target = row.querySelector('.channel-target')?.value || '_blank';
        const isActive = row.querySelector('.channel-active')?.checked ? 1 : 0;
        const bgColor = row.querySelector('.channel-bg-color')?.value.trim() || '';
        const textColor = row.querySelector('.channel-text-color')?.value.trim() || '';

        if (url.trim() !== '') {
            links.push({
                platform_name: plat,
                url: url,
                price: price,
                button_text: btnText,
                badge: badge,
                button_bg_color: bgColor,
                button_text_color: textColor,
                target: target,
                is_active: isActive,
                sort_order: idx
            });
        }
    });

    const origText = btnElem.innerText;
    btnElem.disabled = true;
    btnElem.innerText = 'Salvando canais...';

    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('links', JSON.stringify(links));

    const csrfToken = getCsrfToken();
    if (csrfToken) formData.append('_csrf_token', csrfToken);

    try {
        const response = await fetch('/admin/categorias/salvar-canais', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const res = await response.json();
        if (res.success) {
            btnElem.innerText = '✓ Canais Salvos!';
            setTimeout(() => {
                btnElem.disabled = false;
                btnElem.innerText = origText;
            }, 1800);
        } else {
            alert(res.error || 'Erro ao salvar canais.');
            btnElem.disabled = false;
            btnElem.innerText = origText;
        }
    } catch (err) {
        alert('Erro de conexão: ' + err.message);
        btnElem.disabled = false;
        btnElem.innerText = origText;
    }
}

/**
 * Salva categoria e atualiza a página
 */
async function handleCategorySubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('saveCatBtn');
    btn.disabled = true;
    btn.innerText = 'Salvando...';

    const formData = new FormData(document.getElementById('categoryForm'));
    const csrfToken = getCsrfToken();
    if (csrfToken && !formData.has('_csrf_token')) {
        formData.append('_csrf_token', csrfToken);
    }

    try {
        const response = await fetch('/admin/categorias/salvar', {
            method: 'POST',
            body: formData,
            headers: { 
                'X-Requested-With': 'XMLHttpRequest', 
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json' 
            }
        });
        const rawText = await response.text();
        let data;
        try {
            data = JSON.parse(rawText);
        } catch (jsonErr) {
            console.error('Resposta não-JSON do servidor:', rawText);
            throw new Error('O servidor respondeu com formato inesperado. Se sua sessão expirou, recarregue a página.');
        }

        if (data.auth_required) {
            alert('Sua sessão administrativa expirou. Por favor, faça login novamente.');
            window.location.reload();
            return;
        }

        if (data.success) {
            window.location.reload();
        } else {
            alert(data.error || 'Erro ao salvar categoria.');
            btn.disabled = false;
            btn.innerText = 'Salvar Categoria';
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
        btn.disabled = false;
        btn.innerText = 'Salvar Categoria';
    }
}

async function deleteCategory(id) {
    if (!confirm('Deseja realmente excluir esta categoria? As joias vinculadas a ela não serão apagadas, mas perderão a categoria.')) {
        return;
    }

    const formData = new FormData();
    formData.append('id', id);
    const csrfToken = getCsrfToken();
    if (csrfToken) {
        formData.append('_csrf_token', csrfToken);
    }

    try {
        const response = await fetch('/admin/categorias/excluir', {
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
            const row = document.getElementById('cat-row-' + id);
            if (row) row.remove();
            filterCategories();
        } else {
            alert(data.error || 'Erro ao excluir.');
        }
    } catch (err) {
        alert('Erro ao excluir categoria.');
    }
}
</script>
