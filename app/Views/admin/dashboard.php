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

<!-- MÓDULOS EXECUTIVOS DE GESTÃO (GRID 2x2 ELEGANTE) -->
<div class="exec-modules-grid">
    <!-- MÓDULO 1: MÉTRICAS SHOPEE -->
    <div class="exec-module-card" style="border-left: 3px solid #ff6b4a;">
        <div class="exec-card-top">
            <div class="exec-card-icon" style="background: rgba(238, 77, 45, 0.12); color: #ff6b4a;">
                📈
            </div>
            <span style="background: rgba(238, 77, 45, 0.15); color: #ff6b4a; font-size: 0.72rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; text-transform: uppercase;">
                Shopee Conversão
            </span>
        </div>
        <div class="exec-card-body">
            <h3>Dashboard de Acompanhamento</h3>
            <p>Monitore volume de cliques, taxa de conversão nas joias e receita gerada pelas campanhas externas.</p>
        </div>
        <div class="exec-card-footer">
            <span style="font-size: 0.78rem; color: #8e8880;">Relatórios em tempo real</span>
            <a href="/admin/metricas" class="btn-admin-gold" style="padding: 8px 16px; font-size: 0.82rem;">
                <span>Ver Métricas ↗</span>
            </a>
        </div>
    </div>

    <!-- MÓDULO 2: GESTÃO DE TEMAS -->
    <div class="exec-module-card" style="border-left: 3px solid #caa15c;">
        <div class="exec-card-top">
            <div class="exec-card-icon" style="background: rgba(202, 161, 92, 0.12); color: #caa15c;">
                ✦
            </div>
            <span style="background: rgba(202, 161, 92, 0.15); color: #caa15c; font-size: 0.72rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; text-transform: uppercase;">
                4 Paletas Autorais
            </span>
        </div>
        <div class="exec-card-body">
            <h3>Gestão de Temas & Identidade</h3>
            <p>Alterne a estética da boutique entre Ouro Nobre, Prata 925, Esmeralda e Rose Gold com um clique.</p>
        </div>
        <div class="exec-card-footer">
            <span style="font-size: 0.78rem; color: #8e8880;">Design System dinâmico</span>
            <a href="/admin/temas" class="btn-admin-gold" style="padding: 8px 16px; font-size: 0.82rem;">
                <span>Gerenciar Paletas ↗</span>
            </a>
        </div>
    </div>

    <!-- MÓDULO 3: EDITOR DA LANDING PAGE -->
    <div class="exec-module-card" style="border-left: 3px solid #34d399;">
        <div class="exec-card-top">
            <div class="exec-card-icon" style="background: rgba(52, 211, 153, 0.12); color: #34d399;">
                ✎
            </div>
            <span style="background: rgba(52, 211, 153, 0.15); color: #34d399; font-size: 0.72rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; text-transform: uppercase;">
                CMS Ao Vivo
            </span>
        </div>
        <div class="exec-card-body">
            <h3>Editor da Landing Page</h3>
            <p>Edite fotos do Hero, textos do manifesto, depoimentos de clientes e blocos institucionais da Home.</p>
        </div>
        <div class="exec-card-footer">
            <span style="font-size: 0.78rem; color: #8e8880;">Atualização instantânea</span>
            <a href="/admin/landing-page" class="btn-admin-gold" style="padding: 8px 16px; font-size: 0.82rem;">
                <span>Editar Home ↗</span>
            </a>
        </div>
    </div>

    <!-- MÓDULO 4: INTEGRAÇÃO BLING ERP -->
    <div class="exec-module-card" style="border-left: 3px solid #10b981;">
        <div class="exec-card-top">
            <div class="exec-card-icon" style="background: rgba(16, 185, 129, 0.12); color: #34d399;">
                📦
            </div>
            <?php if (!empty($blingConfig['is_connected'])): ?>
                <span class="bling-status-badge bling-status-connected"><span class="bling-status-dot"></span> Bling Conectado</span>
            <?php elseif (!empty($blingConfig['client_id'])): ?>
                <span class="bling-status-badge bling-status-waiting"><span class="bling-status-dot"></span> Autorização Pendente</span>
            <?php else: ?>
                <span class="bling-status-badge bling-status-disconnected"><span class="bling-status-dot"></span> Não Vinculado</span>
            <?php endif; ?>
        </div>
        <div class="exec-card-body">
            <h3>Estoque & Bling ERP (API v3)</h3>
            <p>
                <?php if (!empty($blingConfig['last_sync_at'])): ?>
                    Último sync em <strong><?= date('d/m/Y H:i', strtotime($blingConfig['last_sync_at'])) ?></strong> com <strong><?= (int)$blingConfig['last_sync_count'] ?> itens</strong> atualizados.
                <?php else: ?>
                    Sincronize produtos, SKUs, variações de pedras e estoque físico em tempo real.
                <?php endif; ?>
            </p>
        </div>
        <div class="exec-card-footer">
            <div style="display: flex; gap: 8px; width: 100%; justify-content: flex-end;">
                <button class="btn-admin-bling" onclick="triggerBlingSync(event)" style="padding: 8px 16px; font-size: 0.82rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/>
                    </svg>
                    <span class="sync-text">Sincronizar</span>
                </button>
                <button class="btn-admin-secondary" onclick="openBlingModal(event)" style="padding: 8px 14px; font-size: 0.82rem;">
                    <span>Config Bling</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- PAINEL DE CONFIGURAÇÕES GERAIS DA BOUTIQUE (TOTALMENTE EDITÁVEL) -->
<div class="admin-card" style="margin-bottom: 28px; border: 1px solid rgba(212, 186, 136, 0.25);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 14px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 class="card-title" style="display: flex; align-items: center; gap: 8px;">
                <span style="color: #caa15c;">✦</span>
                Configurações da Boutique & Vendas
            </h2>
            <p style="font-size: 0.82rem; color: #9e978e; margin: 4px 0 0;">
                Edite os parâmetros da marca, canais de contato, link da Shopee e anúncios exibidos na loja.
            </p>
        </div>
        <button type="button" class="btn-admin-gold" onclick="document.getElementById('storeSettingsForm').requestSubmit()">
            <span>Salvar Configurações da Loja</span>
        </button>
    </div>

    <form id="storeSettingsForm" onsubmit="handleStoreSettingsSubmit(event)">
        <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 18px;">
            <div class="form-group" style="margin: 0;">
                <label for="store_brand_name">Nome da Marca / Joalheria</label>
                <input type="text" id="store_brand_name" name="store_brand_name" class="form-input" 
                       value="<?= \App\Core\View::e($storeSettings['store_brand_name'] ?? 'Divinium Joias Contemporâneas') ?>" 
                       placeholder="Divinium Joias Contemporâneas">
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="store_whatsapp">WhatsApp Concierge / Vendas</label>
                <input type="text" id="store_whatsapp" name="store_whatsapp" class="form-input" 
                       value="<?= \App\Core\View::e($storeSettings['store_whatsapp'] ?? '+55 (11) 99999-9999') ?>" 
                       placeholder="+55 (11) 99999-9999">
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="store_contact_email">E-mail de Atendimento</label>
                <input type="email" id="store_contact_email" name="store_contact_email" class="form-input" 
                       value="<?= \App\Core\View::e($storeSettings['store_contact_email'] ?? 'contato@divinium.com.br') ?>" 
                       placeholder="contato@divinium.com.br">
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="shopee_store_url">Link da Loja Oficial Shopee</label>
                <input type="url" id="shopee_store_url" name="shopee_store_url" class="form-input" 
                       value="<?= \App\Core\View::e($storeSettings['shopee_store_url'] ?? 'https://shopee.com.br') ?>" 
                       placeholder="https://shopee.com.br/divinium">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 18px; align-items: flex-end;">
            <div class="form-group" style="margin: 0;">
                <label for="top_banner_text">Faixa de Anúncio Superior (Top Banner do Site)</label>
                <input type="text" id="top_banner_text" name="top_banner_text" class="form-input" 
                       value="<?= \App\Core\View::e($storeSettings['top_banner_text'] ?? '✨ Frete Cortesia com Seguro para todo o Brasil • 5% de Desconto no Pix • Até 10x sem juros') ?>" 
                       placeholder="✨ Frete Cortesia com Seguro para todo o Brasil...">
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="store_pix_discount">Desconto no Pix (%)</label>
                <input type="number" id="store_pix_discount" name="store_pix_discount" class="form-input" 
                       value="<?= (int) ($storeSettings['store_pix_discount'] ?? 5) ?>" min="0" max="50">
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="store_status_mode">Modo da Loja</label>
                <select id="store_status_mode" name="store_status_mode" class="form-select">
                    <option value="online" <?= ($storeSettings['store_status_mode'] ?? 'online') === 'online' ? 'selected' : '' ?>>🟢 Loja Aberta (Ao Vivo)</option>
                    <option value="maintenance" <?= ($storeSettings['store_status_mode'] ?? '') === 'maintenance' ? 'selected' : '' ?>>🟡 Em Manutenção</option>
                    <option value="vacation" <?= ($storeSettings['store_status_mode'] ?? '') === 'vacation' ? 'selected' : '' ?>>🔵 Pausa / Férias</option>
                </select>
            </div>
        </div>
    </form>
</div>

<!-- VISÃO RÁPIDA DO CATÁLOGO DE JOIAS -->
<div class="admin-card" style="padding: 0; overflow: hidden; margin-bottom: 28px;">
    <div style="padding: 20px 24px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
        <div>
            <h2 class="card-title" style="font-size: 1.35rem;">Visão Recente do Catálogo</h2>
            <p style="font-size: 0.82rem; color: #9e978e; margin: 4px 0 0;">
                Últimas joias cadastradas ou atualizadas pelo ERP. Gerencie todas no catálogo completo.
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <a href="/admin/produtos" class="btn-admin-secondary" style="gap: 6px;">
                <span>Ver Catálogo Completo (<?= (int) ($totalProducts ?? 0) ?>) &rarr;</span>
            </a>
            <button class="btn-admin-gold" onclick="openNewProductModal(event)">
                <span>+ Nova Joia</span>
            </button>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 64px; text-align: center;">Foto</th>
                    <th>Nome da Joia & SKU</th>
                    <th>Gema / Mineral</th>
                    <th>Preço (R$)</th>
                    <th>Preço Pix (-5%)</th>
                    <th>Estoque</th>
                    <th>Shopee</th>
                    <th>Status</th>
                    <th style="text-align: right; padding-right: 24px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $p): ?>
                        <?php 
                            $stock = (int) ($p['stock_quantity'] ?? 0);
                            $stockClass = $stock > 3 ? 'stock-in' : ($stock > 0 ? 'stock-low' : 'stock-out');
                            $stockText = $stock > 3 ? ($stock . ' un.') : ($stock > 0 ? ($stock . ' un. • Baixo') : 'Esgotado');
                        ?>
                        <tr id="product-row-<?= $p['id'] ?>">
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
                                <strong style="color: #ffffff; font-size: 0.9rem; display: block; margin-bottom: 2px;">
                                    <?= \App\Core\View::e($p['name']) ?>
                                </strong>
                                <span style="font-family: monospace; font-size: 0.72rem; color: #a8a096; background: rgba(255,255,255,0.05); padding: 1px 6px; border-radius: 4px;">
                                    <?= \App\Core\View::e($p['sku'] ?? 'DIV-001') ?>
                                </span>
                            </td>
                            <td>
                                <span class="stone-pill">
                                    <span class="stone-dot"></span>
                                    <?= \App\Core\View::e($p['stone_mineral'] ?? 'Mineral Puro') ?>
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
                            <td style="text-align: right; padding-right: 24px;">
                                <div class="action-buttons" style="justify-content: flex-end;">
                                    <button class="btn-table-action" onclick="toggleProductStatus(<?= $p['id'] ?>)">
                                        <?= !empty($p['is_active']) ? 'Ocultar' : 'Exibir' ?>
                                    </button>
                                    <button class="btn-table-action" onclick='editProduct(<?= json_encode($p, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'>
                                        Editar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 36px 20px; color: #8e8880;">
                            Nenhuma joia cadastrada ainda. Clique em "Sincronizar" no módulo Bling acima ou cadastre uma nova peça.
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
            <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">
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

async function handleStoreSettingsSubmit(e) {
    e.preventDefault();
    const form = document.getElementById('storeSettingsForm');
    const formData = new FormData(form);
    const csrfToken = getCsrfToken();
    if (csrfToken && !formData.has('_csrf_token')) {
        formData.append('_csrf_token', csrfToken);
    }

    try {
        const res = await fetch('/admin/configuracoes/salvar', {
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
            alert(data.message || 'Configurações da loja atualizadas com sucesso!');
        } else {
            alert(data.error || 'Erro ao salvar configurações.');
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
    }
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
                <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">
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
