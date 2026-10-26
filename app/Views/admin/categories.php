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

function renderChannelRowHtml(prodId, idx, link = {}) {
    const plat = link.platform_name || 'Shopee';
    const url = link.url || '';
    const btnText = link.button_text || (plat === 'Shopee' ? 'Comprar na Shopee Oficial' : `Comprar no ${plat}`);
    const price = link.price_cents ? (link.price_cents / 100).toFixed(2).replace('.', ',') : '';
    const isActive = link.is_active !== undefined ? link.is_active == 1 : true;

    return `
        <div class="channel-row-item channel-item-${prodId}">
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <div style="width: 140px; flex-shrink: 0;">
                    <label style="font-size: 0.7rem; color: #8e8880; display: block; margin-bottom: 2px;">Plataforma</label>
                    <select class="form-select channel-plat" style="font-size: 0.78rem; padding: 6px 8px;">
                        <option value="Shopee" ${plat === 'Shopee' ? 'selected' : ''}>Shopee</option>
                        <option value="Mercado Livre" ${plat === 'Mercado Livre' ? 'selected' : ''}>Mercado Livre</option>
                        <option value="Amazon" ${plat === 'Amazon' ? 'selected' : ''}>Amazon</option>
                        <option value="WhatsApp" ${plat === 'WhatsApp' ? 'selected' : ''}>WhatsApp</option>
                        <option value="Asaas Pix" ${plat === 'Asaas Pix' ? 'selected' : ''}>Asaas / Pix Direto</option>
                        <option value="TikTok Shop" ${plat === 'TikTok Shop' ? 'selected' : ''}>TikTok Shop</option>
                        <option value="Outro" ${!['Shopee','Mercado Livre','Amazon','WhatsApp','Asaas Pix','TikTok Shop'].includes(plat) ? 'selected' : ''}>Outro</option>
                    </select>
                </div>
                <div style="flex: 2; min-width: 200px;">
                    <label style="font-size: 0.7rem; color: #8e8880; display: block; margin-bottom: 2px;">URL de Compra</label>
                    <input type="url" class="form-input channel-url" value="${escapeHtml(url)}" placeholder="https://shopee.com.br/..." style="font-size: 0.78rem; padding: 6px 10px;">
                </div>
                <div style="width: 110px; flex-shrink: 0;">
                    <label style="font-size: 0.7rem; color: #8e8880; display: block; margin-bottom: 2px;">Preço Canal R$</label>
                    <input type="text" class="form-input channel-price" value="${escapeHtml(price)}" placeholder="380,00" style="font-size: 0.78rem; padding: 6px 8px;">
                </div>
                <div style="flex: 1.5; min-width: 150px;">
                    <label style="font-size: 0.7rem; color: #8e8880; display: block; margin-bottom: 2px;">Texto do Botão</label>
                    <input type="text" class="form-input channel-btn-text" value="${escapeHtml(btnText)}" placeholder="Comprar agora" style="font-size: 0.78rem; padding: 6px 10px;">
                </div>
                <div style="display: flex; align-items: center; gap: 6px; padding-top: 14px; flex-shrink: 0;">
                    <label style="display: flex; align-items: center; gap: 4px; font-size: 0.74rem; color: #EDE7DF; cursor: pointer;">
                        <input type="checkbox" class="channel-active" ${isActive ? 'checked' : ''} style="accent-color: #A7ADB4;"> Ativo
                    </label>
                    <button type="button" class="btn-table-action btn-delete" onclick="this.closest('.channel-row-item').remove()" style="padding: 4px 8px; font-size: 0.74rem;">
                        ✕
                    </button>
                </div>
            </div>
        </div>
    `;
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
        const plat = row.querySelector('.channel-plat')?.value || 'Shopee';
        const url = row.querySelector('.channel-url')?.value || '';
        const price = row.querySelector('.channel-price')?.value || '';
        const btnText = row.querySelector('.channel-btn-text')?.value || '';
        const isActive = row.querySelector('.channel-active')?.checked ? 1 : 0;

        if (url.trim() !== '') {
            links.push({
                platform_name: plat,
                url: url,
                price: price,
                button_text: btnText,
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
