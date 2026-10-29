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

function renderChannelRowHtml(prodId, idx, link = {}) {
    const plat = link.platform_name || 'Shopee';
    const isCustomPlat = !['Shopee', 'Mercado Livre', 'Amazon', 'WhatsApp', 'Compra Direta', 'Asaas Pix', 'TikTok Shop', 'Enjoei', 'Elo7', 'Instagram Shop'].includes(plat);
    const selectedPlat = isCustomPlat ? 'Outro' : plat;
    const url = link.url || '';
    const btnText = link.button_text || (plat === 'Shopee' ? 'Comprar na Shopee Oficial' : `Comprar no ${plat}`);
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
            btnTextInput.value = (val === 'Shopee' || val === 'Amazon') ? ('Comprar na ' + val) : ('Comprar no ' + val);
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
