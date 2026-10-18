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
    <!-- ATALHO INTEGRAÇÃO BLING ERP -->
    <div class="admin-card" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(20, 19, 18, 0.85) 100%); border: 1px solid rgba(16, 185, 129, 0.4); padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; gap: 14px; margin: 0;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                <span style="color: #34d399; font-size: 1.1rem;">📦</span>
                <h3 style="font-family: var(--font-serif); font-size: 1.25rem; color: #ffffff; margin: 0;">Estoque Bling ERP</h3>
                <?php if (!empty($blingConfig['is_connected'])): ?>
                    <span class="bling-status-badge bling-status-connected"><span class="bling-status-dot"></span> Conectado</span>
                <?php elseif (!empty($blingConfig['client_id'])): ?>
                    <span class="bling-status-badge bling-status-waiting"><span class="bling-status-dot"></span> Aguardando Autorização</span>
                <?php else: ?>
                    <span class="bling-status-badge bling-status-disconnected"><span class="bling-status-dot"></span> Não Vinculado</span>
                <?php endif; ?>
            </div>
            <p style="font-size: 0.82rem; color: #b8b1a7; margin: 0;">
                <?php if (!empty($blingConfig['last_sync_at'])): ?>
                    Último sync: <strong><?= date('d/m/Y H:i', strtotime($blingConfig['last_sync_at'])) ?></strong> (<?= (int)$blingConfig['last_sync_count'] ?> itens)
                <?php else: ?>
                    Sincronize produtos, SKUs e saldo de estoque físico em tempo real.
                <?php endif; ?>
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn-admin-bling" onclick="triggerBlingSync(event)" style="padding: 8px 14px; font-size: 0.8rem;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/>
                </svg>
                <span>Sincronizar</span>
            </button>
            <button class="btn-admin-secondary" onclick="openBlingModal(event)" style="padding: 8px 12px; font-size: 0.8rem;">
                <span>Config</span>
            </button>
        </div>
    </div>
</div>

<!-- TABELA DE GESTÃO DE PRODUTOS -->
<div class="admin-card">
    <div class="card-header-row">
        <div>
            <h2 class="card-title">Joias e Produtos Cadastrados</h2>
            <p style="font-size: 0.8rem; color: #9e978e; margin: 4px 0 0;">
                Estoque integrado com Bling ERP. Gerencie as peças, variações de pedras e valores da loja.
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <!-- BOTÃO PRINCIPAL DE SINCRONIZAÇÃO COM O BLING -->
            <button class="btn-admin-bling" id="syncBlingBtnTable" onclick="triggerBlingSync(event)" title="Sincronizar produtos e saldo de estoque com o Bling">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="sync-icon">
                    <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/>
                </svg>
                <span class="sync-text">Sincronizar com Bling</span>
            </button>

            <!-- BOTÃO CONFIGURAR CREDENCIAIS BLING -->
            <button class="btn-admin-secondary" onclick="openBlingModal(event)" style="display: inline-flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0 2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                <span>Config Bling</span>
            </button>

            <button class="btn-admin-gold" onclick="openNewProductModal(event)">
                <span>+ Novo Produto</span>
            </button>
        </div>
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

    const formData = new FormData(document.getElementById('blingConfigForm'));

    try {
        const res = await fetch('/admin/bling/config', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
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

        const res = await fetch('/admin/bling/sync', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
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
</script>

<!-- MODAL DE INTEGRAÇÃO & SINCRONIZAÇÃO BLING ERP -->
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

        <!-- TABS DO MODAL BLING -->
        <div class="bling-tabs">
            <button class="bling-tab-btn active" id="tabBtnSync" onclick="switchBlingTab('sync')">
                <span>🔄 Sincronização de Estoque</span>
            </button>
            <button class="bling-tab-btn" id="tabBtnConfig" onclick="switchBlingTab('config')">
                <span>⚙️ Credenciais da API</span>
            </button>
            <button class="bling-tab-btn" id="tabBtnHelp" onclick="switchBlingTab('help')">
                <span>📖 Passo a Passo</span>
            </button>
        </div>

        <!-- ABA 1: SINCRONIZAÇÃO -->
        <div id="blingTabSync" class="bling-tab-content">
            <!-- STATUS DA CONEXÃO -->
            <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 10px; padding: 16px 20px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="font-size: 0.85rem; color: #b5ada3;">Status da Conexão API:</span>
                    <?php if (!empty($blingConfig['is_connected'])): ?>
                        <span class="bling-status-badge bling-status-connected"><span class="bling-status-dot"></span> Conectado via OAuth 2.0 (Token Ativo)</span>
                    <?php elseif (!empty($blingConfig['client_id'])): ?>
                        <span class="bling-status-badge bling-status-waiting"><span class="bling-status-dot"></span> Credenciais Salvas (Autorização Pendente)</span>
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

            <!-- BOTÕES DE AÇÃO DE SINCRONIZAÇÃO -->
            <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 10px;">
                <!-- OPÇÃO 1: SYNC AUTOMÁTICO INTELIGENTE -->
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
                        <span>Conecte o aplicativo para sincronizar em tempo real pela API do Bling.</span>
                        <button type="button" class="btn-admin-gold" onclick="switchBlingTab('config')" style="padding: 6px 12px; font-size: 0.75rem; white-space: nowrap;">
                            Conectar API ↗
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- RESULTADO AO VIVO DO SYNC -->
            <div id="blingSyncResult" style="display: none; margin-top: 16px; padding: 14px 18px; border-radius: 8px; font-size: 0.85rem;"></div>
        </div>

        <!-- ABA 2: CONFIGURAÇÃO DE CREDENCIAIS (OAUTH2 API V3) -->
        <div id="blingTabConfig" class="bling-tab-content" style="display: none;">
            <form id="blingConfigForm" onsubmit="handleBlingConfigSubmit(event)">
                <div class="form-group">
                    <label for="blingClientId">Client ID do Aplicativo Bling *</label>
                    <input type="text" id="blingClientId" name="client_id" class="form-input" required 
                           value="<?= \App\Core\View::e($blingConfig['client_id'] ?? '') ?>" 
                           placeholder="Ex: 7dbf42c119eea8b65d2c1a1a9ad92b1577594">
                    <span class="field-hint">Encontrado na aba "Informações do app" do seu aplicativo no Bling.</span>
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
                    <span class="field-hint">Clique no ícone de olho no Bling para copiar o segredo completo.</span>
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
                    <span class="field-hint">Certifique-se de que este mesmo link esteja preenchido no Bling (o sistema aceita https://divinium.com.br/ ou https://divinium.com.br/admin/bling/callback).</span>
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

        <!-- ABA 3: PASSO A PASSO ILUSTRADO -->
        <div id="blingTabHelp" class="bling-tab-content" style="display: none; font-size: 0.85rem; color: #ded7cf; line-height: 1.6;">
            <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 18px; margin-bottom: 14px;">
                <h4 style="color: #34d399; margin: 0 0 10px; font-size: 0.95rem;">Como pegar o Client ID e Secret no seu Bling:</h4>
                <ol style="margin: 0; padding-left: 20px; display: flex; flex-direction: column; gap: 8px;">
                    <li>No Bling, vá no menu de engrenagem <strong>Preferências &gt; Central de Extensões</strong> ou clique em <strong>Área do Integrador</strong>.</li>
                    <li>Clique no aplicativo que você criou (<strong>Gest Estoques PHP</strong>).</li>
                    <li>Abra a aba <strong>"Informações do app"</strong>.</li>
                    <li>Copie o <strong>Client Id</strong> e cole no campo correspondente aqui.</li>
                    <li>Ao lado do <strong>Client Secret</strong>, clique no ícone do olho para revelar o código secreto, copie e cole aqui.</li>
                    <li>Clique no botão <strong>"Salvar Credenciais"</strong> e depois em <strong>"Conectar com Bling"</strong> para autorizar o acesso.</li>
                </ol>
            </div>
            <div style="color: #9e978e; font-size: 0.78rem;">
                💡 <em>Dica: Mesmo sem conectar a API de imediato, você já pode clicar em <strong>"Sincronizar Estoque Agora (1-Clique)"</strong> para popular o banco de dados da loja com os 100 produtos das coleções do Bling!</em>
            </div>
        </div>
    </div>
</div>
