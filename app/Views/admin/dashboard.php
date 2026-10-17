<?php
/**
 * VIEW: DASHBOARD ADMINISTRATIVO DIVINIUM
 */
?>

<!-- CARDS DE MÉTRICAS -->
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-icon-wrap">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
        </div>
        <div class="metric-data">
            <span class="metric-value"><?= (int) ($totalProducts ?? 0) ?></span>
            <span class="metric-label">Total de Produtos</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon-wrap" style="background: rgba(74, 222, 128, 0.12); color: #4ade80;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
        <div class="metric-data">
            <span class="metric-value"><?= (int) ($activeProducts ?? 0) ?></span>
            <span class="metric-label">Produtos Exibidos na Loja</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon-wrap">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
        </div>
        <div class="metric-data">
            <span class="metric-value"><?= (int) ($totalCategories ?? 0) ?></span>
            <span class="metric-label">Categorias & Coleções</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon-wrap" style="background: rgba(212, 186, 136, 0.18);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div class="metric-data">
            <span class="metric-value">Pix & Cartão</span>
            <span class="metric-label">Gateway Asaas Ativo</span>
        </div>
    </div>
</div>

<!-- BANNERS DE ATALHO RÁPIDO: MÉTRICAS, TEMAS E EDITOR -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <!-- ATALHO DASHBOARD DE ACOMPANHAMENTO -->
    <div class="admin-card" style="background: linear-gradient(135deg, rgba(238, 77, 45, 0.12) 0%, rgba(20, 19, 18, 0.8) 100%); border: 1px solid rgba(238, 77, 45, 0.35); padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; gap: 14px; margin: 0;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                <span style="color: #ff6b4a; font-size: 1.1rem;">📈</span>
                <h3 style="font-family: var(--font-serif); font-size: 1.25rem; color: #ffffff; margin: 0;">Dashboard de Acompanhamento</h3>
                <span style="background: rgba(238, 77, 45, 0.2); color: #ff6b4a; font-size: 0.65rem; font-weight: 700; padding: 2px 8px; border-radius: 12px; text-transform: uppercase;">Shopee</span>
            </div>
            <p style="font-size: 0.82rem; color: #b8b1a7; margin: 0;">Acompanhe cliques, taxa de conversão e volume financeiro.</p>
        </div>
        <a href="/admin/metricas" class="btn-admin-gold" style="text-decoration: none; padding: 8px 14px; font-size: 0.8rem; white-space: nowrap;">
            <span>Ver Métricas ↗</span>
        </a>
    </div>

    <!-- ATALHO GESTÃO DE TEMAS -->
    <div class="admin-card" style="background: linear-gradient(135deg, rgba(202, 161, 92, 0.12) 0%, rgba(20, 19, 18, 0.8) 100%); border: 1px solid rgba(202, 161, 92, 0.35); padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; gap: 14px; margin: 0;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                <span style="color: #caa15c; font-size: 1.1rem;">✦</span>
                <h3 style="font-family: var(--font-serif); font-size: 1.25rem; color: #ffffff; margin: 0;">Gestão de Temas</h3>
                <span style="background: rgba(202, 161, 92, 0.2); color: #caa15c; font-size: 0.65rem; font-weight: 700; padding: 2px 8px; border-radius: 12px; text-transform: uppercase;">4 Paletas</span>
            </div>
            <p style="font-size: 0.82rem; color: #b8b1a7; margin: 0;">Alterne entre Ouro, Prata, Esmeralda e Rose Gold.</p>
        </div>
        <a href="/admin/temas" class="btn-admin-gold" style="text-decoration: none; padding: 8px 14px; font-size: 0.8rem; white-space: nowrap;">
            <span>Gerenciar ↗</span>
        </a>
    </div>

    <!-- ATALHO EDITOR DA LANDING PAGE -->
    <div class="admin-card" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(20, 19, 18, 0.8) 100%); border: 1px solid rgba(52, 211, 153, 0.3); padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; gap: 14px; margin: 0;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                <span style="color: #34d399; font-size: 1.1rem;">✎</span>
                <h3 style="font-family: var(--font-serif); font-size: 1.25rem; color: #ffffff; margin: 0;">Editor da Landing Page</h3>
                <span style="background: rgba(52, 211, 153, 0.2); color: #34d399; font-size: 0.65rem; font-weight: 700; padding: 2px 8px; border-radius: 12px; text-transform: uppercase;">Ao Vivo</span>
            </div>
            <p style="font-size: 0.82rem; color: #b8b1a7; margin: 0;">Edite textos, fotos e valores da Home facilmente.</p>
        </div>
        <a href="/admin/landing-page" class="btn-admin-gold" style="text-decoration: none; padding: 8px 14px; font-size: 0.8rem; white-space: nowrap;">
            <span>Editar Home ↗</span>
        </a>
    </div>
</div>

<!-- TABELA DE GESTÃO DE PRODUTOS -->
<div class="admin-card">
    <div class="card-header-row">
        <div>
            <h2 class="card-title">Joias Cadastradas no Catálogo</h2>
            <p style="font-size: 0.8rem; color: #9e978e; margin: 4px 0 0;">Gerencie os braceletes, minerais e valores exibidos na loja.</p>
        </div>
        <button class="btn-admin-gold" onclick="openNewProductModal(event)">
            <span>+ Novo Produto</span>
        </button>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 60px;">Foto</th>
                    <th>Nome da Joia</th>
                    <th>Gema / Mineral</th>
                    <th>Preço (R$)</th>
                    <th>Preço Pix (-5%)</th>
                    <th>Estoque</th>
                    <th>Link Shopee</th>
                    <th>Status</th>
                    <th style="text-align: right;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $p): ?>
                        <tr id="product-row-<?= $p['id'] ?>">
                            <td>
                                <img src="<?= \App\Core\View::e($p['image_url'] ?? '/assets/images/hero/featured-green-shell.jpg') ?>" 
                                     alt="<?= \App\Core\View::e($p['name']) ?>" 
                                     class="table-thumb"
                                     onerror="this.src='/assets/images/hero/featured-green-shell.jpg';">
                            </td>
                            <td>
                                <strong><?= \App\Core\View::e($p['name']) ?></strong>
                                <div style="font-size: 0.72rem; color: #8e8880;">SKU: <?= \App\Core\View::e($p['sku'] ?? 'DIV-001') ?></div>
                            </td>
                            <td>
                                <span style="display: inline-flex; align-items: center; gap: 6px;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #d4ba88;"></span>
                                    <?= \App\Core\View::e($p['stone_mineral'] ?? 'Mineral Puro') ?>
                                </span>
                            </td>
                            <td>
                                <strong>R$ <?= number_format(($p['price_cents'] ?? 0) / 100, 2, ',', '.') ?></strong>
                            </td>
                            <td style="color: #4ade80;">
                                R$ <?= number_format((($p['promotional_price_cents'] ?? $p['price_cents'] * 0.95) / 100), 2, ',', '.') ?>
                            </td>
                            <td>
                                <span><?= (int) ($p['stock_quantity'] ?? 0) ?> un.</span>
                            </td>
                            <td>
                                <?php if (!empty($p['external_url'])): ?>
                                    <a href="<?= \App\Core\View::e($p['external_url']) ?>" target="_blank" rel="noopener noreferrer" style="color: #ee4d2d; font-size: 0.76rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; background: rgba(238, 77, 45, 0.12); padding: 4px 8px; border-radius: 6px; border: 1px solid rgba(238, 77, 45, 0.3);">
                                        <span>↗ Shopee</span>
                                    </a>
                                <?php else: ?>
                                    <span style="color: #777; font-size: 0.72rem;">Não definido</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge-status <?= !empty($p['is_active']) ? 'badge-active' : 'badge-inactive' ?>" id="status-badge-<?= $p['id'] ?>">
                                    <?= !empty($p['is_active']) ? 'Exibido' : 'Oculto' ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div class="action-buttons" style="justify-content: flex-end;">
                                    <button class="btn-table-action" onclick="toggleProductStatus(<?= $p['id'] ?>)">
                                        <?= !empty($p['is_active']) ? 'Ocultar' : 'Exibir' ?>
                                    </button>
                                    <button class="btn-table-action" onclick='editProduct(<?= json_encode($p, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'>
                                        Editar
                                    </button>
                                    <button class="btn-table-action btn-delete" onclick="deleteProduct(<?= $p['id'] ?>)">
                                        Excluir
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 30px; color: #8e8880;">
                            Nenhum produto cadastrado no momento. Clique no botão acima para adicionar sua primeira joia.
                        </td>
                    </tr>
                <?php endif; ?>
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
            <input type="hidden" name="id" id="prodId" value="">

            <div class="form-group">
                <label for="prodName">Nome do Produto *</label>
                <input type="text" id="prodName" name="name" class="form-input" required placeholder="Ex: Bracelete Caminhos • Quartzo Verde">
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="prodMineral">Mineral / Gema</label>
                    <input type="text" id="prodMineral" name="stone_mineral" class="form-input" placeholder="Ex: Quartzo Verde, Madrepérola...">
                </div>

                <div class="form-group">
                    <label for="prodCategory">Categoria</label>
                    <select id="prodCategory" name="category_id" class="form-select">
                        <option value="">Selecione uma categoria...</option>
                        <?php foreach ($categories as $cat): ?>
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
                    <label for="prodStock">Estoque Disponível</label>
                    <input type="number" id="prodStock" name="stock_quantity" class="form-input" value="10" min="0">
                </div>
            </div>

            <div class="form-group">
                <label for="prodShopee">Link do Produto na Shopee / Loja Externa *</label>
                <input type="url" id="prodShopee" name="external_url" class="form-input" placeholder="https://shopee.com.br/produto-exemplo-divinium...">
                <span style="font-size: 0.72rem; color: #d4ba88;">Quando o cliente clicar no produto na loja, será redirecionado para este link para finalizar a compra na Shopee.</span>
            </div>

            <div class="form-group">
                <label for="prodImage">Caminho da Imagem ou URL</label>
                <input type="text" id="prodImage" name="image_url" class="form-input" placeholder="/assets/images/hero/featured-green-shell.jpg">
                <span style="font-size: 0.72rem; color: #8e8880;">Use imagens em /assets/images/products/ ou deixe o padrão.</span>
            </div>

            <div class="form-group">
                <label for="prodDesc">Descrição Curta</label>
                <textarea id="prodDesc" name="description" class="form-textarea" rows="2" placeholder="Prata nobre 925 e gema mineral pura com concha natural."></textarea>
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

<script>
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

async function handleProductSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('saveProdBtn');
    btn.disabled = true;
    btn.innerText = 'Salvando...';

    const formData = new FormData(document.getElementById('productForm'));

    try {
        const response = await fetch('/admin/produtos/salvar', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
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

    try {
        const response = await fetch('/admin/produtos/toggle', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
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

    try {
        const response = await fetch('/admin/produtos/excluir', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        if (data.success) {
            const row = document.getElementById('product-row-' + id);
            if (row) row.remove();
        } else {
            alert(data.error || 'Erro ao excluir.');
        }
    } catch (err) {
        alert('Erro ao excluir produto.');
    }
}
</script>
