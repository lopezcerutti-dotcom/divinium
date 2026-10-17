<?php
/**
 * VIEW: GESTÃO DE CATEGORIAS / COLEÇÕES
 */
?>

<div class="admin-card">
    <div class="card-header-row">
        <div>
            <h2 class="card-title">Categorias & Coleções</h2>
            <p style="font-size: 0.8rem; color: #9e978e; margin: 4px 0 0;">Organize o catálogo de joias por tipo de peça e coleção autoral.</p>
        </div>
        <button class="btn-admin-gold" onclick="openNewCategoryModal(event)">
            <span>+ Nova Categoria</span>
        </button>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Nome da Categoria</th>
                    <th>Slug URL</th>
                    <th>Descrição</th>
                    <th>Total de Joias</th>
                    <th>Status</th>
                    <th style="text-align: right;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $c): ?>
                        <tr id="cat-row-<?= $c['id'] ?>">
                            <td><?= $c['id'] ?></td>
                            <td><strong><?= \App\Core\View::e($c['name']) ?></strong></td>
                            <td style="color: #d4ba88; font-family: monospace; font-size: 0.78rem;">/<?= \App\Core\View::e($c['slug']) ?></td>
                            <td style="color: #9e978e; max-width: 250px;"><?= \App\Core\View::e($c['description'] ?? '—') ?></td>
                            <td><?= (int) ($c['product_count'] ?? 0) ?> produtos</td>
                            <td>
                                <span class="badge-status <?= !empty($c['is_active']) ? 'badge-active' : 'badge-inactive' ?>">
                                    <?= !empty($c['is_active']) ? 'Ativa' : 'Inativa' ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div class="action-buttons" style="justify-content: flex-end;">
                                    <button class="btn-table-action" onclick='editCategory(<?= json_encode($c, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'>Editar</button>
                                    <button class="btn-table-action btn-delete" onclick="deleteCategory(<?= $c['id'] ?>)">Excluir</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px; color: #8e8880;">Nenhuma categoria cadastrada.</td>
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
            <input type="hidden" name="id" id="catId" value="">

            <div class="form-group">
                <label for="catName">Nome da Categoria *</label>
                <input type="text" id="catName" name="name" class="form-input" required placeholder="Ex: Anéis Autoriais">
            </div>

            <div class="form-group">
                <label for="catDesc">Descrição</label>
                <textarea id="catDesc" name="description" class="form-textarea" rows="2" placeholder="Descrição da coleção..."></textarea>
            </div>

            <div class="form-group">
                <label for="catOrder">Ordem de Exibição</label>
                <input type="number" id="catOrder" name="sort_order" class="form-input" value="1" min="0">
            </div>

            <div class="form-checkbox-row">
                <input type="checkbox" id="catActive" name="is_active" value="1" checked style="width: 18px; height: 18px; accent-color: #d4ba88;">
                <label for="catActive" style="margin: 0; cursor: pointer;">Categoria Ativa</label>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-admin-secondary" onclick="closeCategoryModal()">Cancelar</button>
                <button type="submit" class="btn-admin-gold" id="saveCatBtn">Salvar Categoria</button>
            </div>
        </form>
    </div>
</div>

<script>
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
    document.getElementById('catModalTitle').innerText = 'Editar Categoria';
    document.getElementById('categoryModal').classList.add('active');
}

async function handleCategorySubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('saveCatBtn');
    btn.disabled = true;

    const formData = new FormData(document.getElementById('categoryForm'));

    try {
        const response = await fetch('/admin/categorias/salvar', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const data = await response.json();
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.error || 'Erro ao salvar.');
            btn.disabled = false;
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor.');
        btn.disabled = false;
    }
}

async function deleteCategory(id) {
    if (!confirm('Deseja realmente excluir esta categoria?')) return;
    const formData = new FormData();
    formData.append('id', id);

    try {
        const response = await fetch('/admin/categorias/excluir', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const data = await response.json();
        if (data.success) {
            const row = document.getElementById('cat-row-' + id);
            if (row) row.remove();
        } else {
            alert(data.error || 'Erro ao excluir.');
        }
    } catch (err) {
        alert('Erro ao excluir categoria.');
    }
}
</script>
