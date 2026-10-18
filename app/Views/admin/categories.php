<?php
/**
 * VIEW: GESTÃO COMPLETA DE CATEGORIAS / COLEÇÕES AUTORAIS
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
                    <th>Total de Joias</th>
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
                                <span style="color: #d4ba88; font-family: monospace; font-size: 0.8rem; background: rgba(212,186,136,0.1); padding: 2px 8px; border-radius: 4px;">
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
                                <span style="font-weight: 600; color: #ede7df;"><?= (int) ($c['product_count'] ?? 0) ?> joias</span>
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

<!-- MODAL CATEGORIA -->
<div class="modal-overlay" id="categoryModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="catModalTitle">Nova Categoria</h3>
            <button class="close-modal-btn" onclick="closeCategoryModal()">&times;</button>
        </div>

        <form id="categoryForm" onsubmit="handleCategorySubmit(event)">
            <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">
            <input type="hidden" name="id" id="catId" value="">

            <div class="form-group">
                <label for="catName">Nome da Categoria / Coleção *</label>
                <input type="text" id="catName" name="name" class="form-input" required placeholder="Ex: Braceletes & Arm Candy">
            </div>

            <div class="form-group">
                <label for="catDesc">Descrição da Coleção</label>
                <textarea id="catDesc" name="description" class="form-textarea" rows="3" placeholder="Peças esculpidas em prata de lei 925 com acabamento acetinado..."></textarea>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="catOrder">Ordem de Exibição no Menu</label>
                    <input type="number" id="catOrder" name="sort_order" class="form-input" value="1" min="0">
                </div>

                <div class="form-group">
                    <label>Visibilidade Pública</label>
                    <div class="form-checkbox-row" style="margin-top: 10px;">
                        <input type="checkbox" id="catActive" name="is_active" value="1" checked style="width: 18px; height: 18px; accent-color: #d4ba88;">
                        <label for="catActive" style="margin: 0; cursor: pointer;">Categoria Ativa na Loja</label>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-admin-secondary" onclick="closeCategoryModal()">Cancelar</button>
                <button type="submit" class="btn-admin-gold" id="saveCatBtn">Salvar Categoria</button>
            </div>
        </form>
    </div>
</div>

<script>
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
    document.getElementById('categoryForm').reset();
    document.getElementById('catId').value = '';
    document.getElementById('catModalTitle').innerText = 'Nova Categoria';
    document.getElementById('categoryModal').classList.add('active');
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.remove('active');
}

function editCategory(cat) {
    document.getElementById('catId').value = cat.id;
    document.getElementById('catName').value = cat.name || '';
    document.getElementById('catDesc').value = cat.description || '';
    document.getElementById('catOrder').value = cat.sort_order || 0;
    document.getElementById('catActive').checked = cat.is_active == 1;
    document.getElementById('catModalTitle').innerText = 'Editar: ' + cat.name;
    document.getElementById('categoryModal').classList.add('active');
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
}

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
        const data = await response.json();
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
