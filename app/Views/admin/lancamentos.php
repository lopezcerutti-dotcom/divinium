<?php
/**
 * VIEW: GESTÃO CENTRALIZADA DE COLEÇÕES & LANÇAMENTOS DA DIVINIUM
 * Permite gerenciar coleções organizadas por categorias independentes (Braceletes, Colares, Anéis, etc.)
 * com total controle de títulos, descrições, imagens, botões, visibilidade e ordenação.
 */
use App\Core\View;
use App\Middlewares\CsrfMiddleware;

$categories = $categories ?? [];
$collections = $collections ?? [];
$grouped = $grouped ?? [];
$products = $products ?? [];

$activeCategorySlug = $_GET['cat'] ?? (!empty($categories[0]['slug']) ? $categories[0]['slug'] : 'braceletes');
?>

<div class="lancamentos-dashboard">

    <!-- CABEÇALHO DO MÓDULO LANÇAMENTOS -->
    <div class="catalog-header-bar">
        <div class="catalog-header-text">
            <h2>Lançamentos & Coleções Autorais</h2>
            <p>Gerencie coleções, títulos, banners, botões de ação e produtos divididos por categorias independentes.</p>
        </div>
        <div class="catalog-actions-bar">
            <button type="button" class="btn-admin-secondary" onclick="openNewCategoryModal(event)">
                <span>+ Nova Categoria</span>
            </button>
            <button type="button" class="btn-admin-gold" onclick="openNewCollectionModal(event, '<?= View::e($activeCategorySlug) ?>')">
                <span>+ Nova Coleção</span>
            </button>
            <a href="/#collections" target="_blank" class="btn-admin-secondary" style="text-decoration: none;" title="Ver na Home">
                <span>Ver na Home ↗</span>
            </a>
        </div>
    </div>

    <!-- TABS DE NAVEGAÇÃO DE CATEGORIAS INDEPENDENTES -->
    <div class="lancamentos-category-tabs" id="categoryTabsBar">
        <?php foreach ($categories as $cat): ?>
            <?php 
                $cSlug = $cat['slug'] ?? '';
                $cCollections = $grouped[$cSlug] ?? [];
                $isActiveTab = ($cSlug === $activeCategorySlug);
                
                $icon = '💎';
                if ($cSlug === 'colares') $icon = '📿';
                elseif ($cSlug === 'aneis') $icon = '💍';
                elseif (str_contains($cSlug, 'brinco')) $icon = '✨';
                elseif (str_contains($cSlug, 'pingente')) $icon = '🌟';
            ?>
            <button type="button" 
                    class="lancamento-cat-tab <?= $isActiveTab ? 'active' : '' ?>"
                    data-cat-slug="<?= View::e($cSlug) ?>"
                    onclick="switchCategoryTab('<?= View::e($cSlug) ?>', this)">
                <span class="cat-tab-icon"><?= $icon ?></span>
                <span class="cat-tab-title"><?= View::e($cat['name']) ?></span>
                <span class="cat-tab-badge" id="badge-count-<?= View::e($cSlug) ?>"><?= count($cCollections) ?></span>
            </button>
        <?php endforeach; ?>

        <button type="button" class="lancamento-cat-tab add-cat-tab-btn" onclick="openNewCategoryModal(event)" title="Adicionar nova categoria">
            <span>+ Nova Categoria</span>
        </button>
    </div>

    <!-- ÁREAS INDEPENDENTES DE CADA CATEGORIA -->
    <?php foreach ($categories as $cat): ?>
        <?php 
            $cSlug = $cat['slug'] ?? '';
            $cCollections = $grouped[$cSlug] ?? [];
            $isActiveTab = ($cSlug === $activeCategorySlug);
        ?>
        <div class="category-independent-area <?= $isActiveTab ? 'active' : '' ?>" id="area-cat-<?= View::e($cSlug) ?>">
            
            <!-- Resumo da Categoria Atual -->
            <div class="category-summary-card">
                <div class="cat-summary-info">
                    <div class="cat-summary-tag">CATEGORIA ATIVA</div>
                    <h3 class="cat-summary-title"><?= View::e($cat['name']) ?></h3>
                    <p class="cat-summary-desc"><?= View::e($cat['description'] ?? 'Coleções de joias assinadas pela Divinium.') ?></p>
                </div>
                <div class="cat-summary-stats">
                    <div class="stat-pill">
                        <span class="stat-num" id="total-col-<?= View::e($cSlug) ?>"><?= count($cCollections) ?></span>
                        <span class="stat-lbl">Coleções</span>
                    </div>
                    <button type="button" class="btn-admin-gold" onclick="openNewCollectionModal(event, '<?= View::e($cSlug) ?>')">
                        <span>+ Nova Coleção em <?= View::e($cat['name']) ?></span>
                    </button>
                </div>
            </div>

            <!-- LISTAGEM DE COLEÇÕES DA CATEGORIA -->
            <div class="collections-cards-list" id="list-collections-<?= View::e($cSlug) ?>">
                <?php if (!empty($cCollections)): ?>
                    <?php foreach ($cCollections as $index => $col): ?>
                        <div class="collection-item-card" 
                             id="col-card-<?= (int)$col['id'] ?>" 
                             data-id="<?= (int)$col['id'] ?>"
                             data-order="<?= (int)($col['sort_order'] ?? ($index + 1)) ?>">
                            
                            <!-- Header do Card da Coleção -->
                            <div class="col-card-header">
                                <div class="col-drag-and-title">
                                    <span class="col-order-pill">#<?= (int)($col['sort_order'] ?? ($index + 1)) ?></span>
                                    <?php if (!empty($col['badge'])): ?>
                                        <span class="col-badge-tag"><?= View::e($col['badge']) ?></span>
                                    <?php endif; ?>
                                    <h4 class="col-name"><?= View::e($col['name']) ?></h4>
                                    <span class="col-title-sep">—</span>
                                    <span class="col-display-title"><?= View::e($col['title'] ?? '') ?></span>
                                </div>

                                <div class="col-card-actions">
                                    <!-- Toggle de Visibilidade (Ativo/Oculto) -->
                                    <label class="switch-toggle-wrap" title="Mostrar ou ocultar esta coleção na loja">
                                        <input type="checkbox" 
                                               <?= !empty($col['is_active']) ? 'checked' : '' ?> 
                                               onchange="toggleCollectionActive(<?= (int)$col['id'] ?>, this)">
                                        <span class="switch-slider"></span>
                                        <span class="switch-label-text"><?= !empty($col['is_active']) ? 'Visível' : 'Oculto' ?></span>
                                    </label>

                                    <!-- Reordenação Rápida -->
                                    <div class="order-buttons-wrap">
                                        <button type="button" class="btn-order-arrow" onclick="moveCollectionUp(<?= (int)$col['id'] ?>, '<?= View::e($cSlug) ?>')" title="Mover para cima">▲</button>
                                        <button type="button" class="btn-order-arrow" onclick="moveCollectionDown(<?= (int)$col['id'] ?>, '<?= View::e($cSlug) ?>')" title="Mover para baixo">▼</button>
                                    </div>

                                    <!-- Botões de Ação -->
                                    <button type="button" class="btn-card-action btn-preview" onclick='previewCollectionModal(<?= json_encode($col, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT) ?>)' title="Pré-visualizar como fica no site">
                                        <span>👁️ Preview</span>
                                    </button>
                                    <button type="button" class="btn-card-action btn-edit" onclick='editCollectionModal(<?= json_encode($col, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'>
                                        <span>✏️ Editar</span>
                                    </button>
                                    <button type="button" class="btn-card-action btn-delete" onclick="deleteCollection(<?= (int)$col['id'] ?>, '<?= View::e(addslashes($col['name'])) ?>')">
                                        <span>🗑️ Excluir</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Corpo do Card: Informações, Mídia e Botão -->
                            <div class="col-card-body">
                                <!-- Coluna de Mídia e Imagem -->
                                <div class="col-media-preview">
                                    <div class="col-img-box">
                                        <img src="<?= View::e(!empty($col['image_url']) ? $col['image_url'] : '/assets/images/products/colecao-completa-espelho.jpg') ?>" 
                                             alt="<?= View::e($col['image_alt'] ?? $col['name']) ?>"
                                             loading="lazy"
                                             onerror="this.onerror=null; this.src='/assets/images/products/colecao-completa-espelho.jpg';">
                                    </div>
                                    <div class="col-btn-preview-box">
                                        <a href="<?= View::e(!empty($col['button_url']) ? $col['button_url'] : '/loja') ?>" 
                                           target="<?= View::e($col['button_target'] ?? '_self') ?>"
                                           class="btn-col-preview"
                                           onclick="return false;">
                                            <span><?= View::e(!empty($col['button_text']) ? $col['button_text'] : 'Explorar Coleção') ?></span>
                                            <span style="font-size: 0.8rem;">↗</span>
                                        </a>
                                        <?php if (!empty($col['button_secondary_text'])): ?>
                                            <span class="secondary-link-tag">Link 2: <?= View::e($col['button_secondary_text']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Coluna de Textos & Descrições -->
                                <div class="col-info-details">
                                    <?php if (!empty($col['subtitle'])): ?>
                                        <div class="col-subtitle-text"><?= View::e($col['subtitle']) ?></div>
                                    <?php endif; ?>

                                    <?php if (!empty($col['description'])): ?>
                                        <div class="col-desc-text"><?= View::e($col['description']) ?></div>
                                    <?php endif; ?>

                                    <?php if (!empty($col['promo_text'])): ?>
                                        <div class="col-promo-banner">
                                            <span class="promo-sparkle">✦</span>
                                            <span><?= View::e($col['promo_text']) ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Grade de Produtos Vinculados a esta Coleção -->
                                    <div class="col-products-container">
                                        <div class="col-products-header">
                                            <span class="lbl-products-title">Joias Vinculadas a esta Coleção (<?= count($col['products'] ?? []) ?>)</span>
                                            <button type="button" class="btn-link-gold" onclick='editCollectionModal(<?= json_encode($col, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT) ?>, "products")'>
                                                + Vincular / Reordenar Joias
                                            </button>
                                        </div>

                                        <?php if (!empty($col['products'])): ?>
                                            <div class="col-products-grid">
                                                <?php foreach ($col['products'] as $p): ?>
                                                    <div class="col-prod-chip <?= empty($p['is_active']) ? 'chip-hidden' : '' ?>" id="prod-chip-<?= (int)$col['id'] ?>-<?= (int)$p['id'] ?>">
                                                        <div class="chip-thumb">
                                                            <img src="<?= View::e(!empty($p['image_url']) ? $p['image_url'] : '/assets/images/products/bracelete-verde.jpg') ?>" 
                                                                 alt="<?= View::e($p['name'] ?? '') ?>"
                                                                 onerror="this.onerror=null; this.src='/assets/images/products/bracelete-verde.jpg';">
                                                        </div>
                                                        <div class="chip-data">
                                                            <span class="chip-name"><?= View::e($p['name'] ?? 'Joia') ?></span>
                                                            <span class="chip-price">R$ <?= number_format(((int)($p['price_cents'] ?? 38000)) / 100, 2, ',', '.') ?></span>
                                                        </div>
                                                        <button type="button" 
                                                                class="chip-visibility-btn" 
                                                                onclick="toggleCollectionProduct(<?= (int)$col['id'] ?>, <?= (int)$p['id'] ?>, this)"
                                                                title="<?= !empty($p['is_active']) ? 'Produto visível nesta coleção (clique para ocultar)' : 'Produto oculto nesta coleção (clique para exibir)' ?>">
                                                            <?= !empty($p['is_active']) ? '👁️' : '🚫' ?>
                                                        </button>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="empty-products-hint">
                                                <span>Nenhuma joia vinculada especificamente. A coleção exibe as peças da categoria <?= View::e($cat['name']) ?> na vitrine.</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Estado Vazio para Categoria sem Coleção -->
                    <div class="empty-collections-state">
                        <div class="empty-icon">💎</div>
                        <h3>Nenhuma coleção criada em <?= View::e($cat['name']) ?></h3>
                        <p>Você pode criar uma coleção exclusiva para exibir peças e destaques de <?= View::e($cat['name']) ?> na loja.</p>
                        <button type="button" class="btn-admin-gold" onclick="openNewCollectionModal(event, '<?= View::e($cSlug) ?>')">
                            <span>+ Criar Primeira Coleção em <?= View::e($cat['name']) ?></span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    <?php endforeach; ?>

</div>

<!-- ======================================================================= -->
<!-- MODAL DE CRIAÇÃO / EDIÇÃO DE COLEÇÃO (FORMULÁRIO COMPLETO) -->
<!-- ======================================================================= -->
<div class="modal-overlay" id="collectionModal">
    <div class="modal-content modal-content-large">
        <div class="modal-header">
            <div>
                <h3 class="modal-title" id="colModalTitle">Nova Coleção</h3>
                <span class="modal-subtitle" id="colModalSubtitle">Configure os títulos, imagens, botões e joias da coleção.</span>
            </div>
            <button type="button" class="close-modal-btn" onclick="closeCollectionModal()">&times;</button>
        </div>

        <form id="collectionForm" onsubmit="handleCollectionSubmit(event)">
            <input type="hidden" name="_csrf_token" value="<?= CsrfMiddleware::getToken() ?>">
            <input type="hidden" name="id" id="fieldColId" value="">
            <input type="hidden" name="products_json" id="fieldColProductsJson" value="[]">

            <div class="modal-tabs-header">
                <button type="button" class="modal-tab-btn active" onclick="switchModalTab('tab-col-general', this)">
                    <span>✦ Geral & Textos</span>
                </button>
                <button type="button" class="modal-tab-btn" onclick="switchModalTab('tab-col-media-cta', this)">
                    <span>🖼️ Imagem & Botão</span>
                </button>
                <button type="button" class="modal-tab-btn" onclick="switchModalTab('tab-col-products', this)">
                    <span>💎 Joias da Coleção</span>
                </button>
            </div>

            <div class="modal-tab-content active" id="tab-col-general">
                <div class="form-row-2">
                    <div class="form-group">
                        <label for="fieldColCategory">Categoria Pertencente *</label>
                        <select id="fieldColCategory" name="category_slug" class="form-select" onchange="handleCategorySelectChange(this)" required>
                            <?php foreach ($categories as $catOption): ?>
                                <option value="<?= View::e($catOption['slug']) ?>" data-id="<?= (int)$catOption['id'] ?>" data-name="<?= View::e($catOption['name']) ?>">
                                    <?= View::e($catOption['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="category_id" id="fieldColCategoryId" value="1">
                        <input type="hidden" name="category_name" id="fieldColCategoryName" value="Braceletes">
                    </div>

                    <div class="form-group">
                        <label for="fieldColName">Nome Interno da Coleção *</label>
                        <input type="text" id="fieldColName" name="name" class="form-input" required placeholder="Ex: Coleção Caminhos">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="fieldColBadge">Tag / Badge em Destaque</label>
                        <input type="text" id="fieldColBadge" name="badge" class="form-input" placeholder="Ex: LANÇAMENTO EXCLUSIVO, EDIÇÃO LIMITADA, ALTA JOALHERIA">
                    </div>

                    <div class="form-group">
                        <label for="fieldColTitle">Título Público de Exibição *</label>
                        <input type="text" id="fieldColTitle" name="title" class="form-input" required placeholder="Ex: Braceletes CAMINHOS • Harmonia Mineral">
                    </div>
                </div>

                <div class="form-group">
                    <label for="fieldColSubtitle">Subtítulo / Frase de Impacto</label>
                    <input type="text" id="fieldColSubtitle" name="subtitle" class="form-input" placeholder="Ex: Seis caminhos, seis vibrações esculpidas em prata de lei 925">
                </div>

                <div class="form-group">
                    <label for="fieldColDescription">Descrição Completa da Coleção</label>
                    <textarea id="fieldColDescription" name="description" class="form-textarea" rows="3" placeholder="Apresente os diferenciais estéticos, acabamento e inspiração da coleção..."></textarea>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="fieldColPromoText">Texto Promocional / Diferencial</label>
                        <input type="text" id="fieldColPromoText" name="promo_text" class="form-input" placeholder="Ex: Edição Autoral Limitada • Frete Cortesia para todo o Brasil">
                    </div>

                    <div class="form-group">
                        <label for="fieldColOrder">Ordem de Exibição</label>
                        <input type="number" id="fieldColOrder" name="sort_order" class="form-input" value="1" min="1">
                    </div>
                </div>

                <div class="form-checkbox-row" style="margin-top: 15px;">
                    <input type="checkbox" id="fieldColActive" name="is_active" value="1" checked style="width: 18px; height: 18px; accent-color: #737B85;">
                    <label for="fieldColActive" style="margin: 0; cursor: pointer; color: #ede7df;">
                        <strong>Coleção Ativa e Visível na Loja</strong>
                        <span style="display: block; font-size: 0.8rem; color: #a8a096;">Ao desmarcar, a coleção é ocultada do público sem apagar nenhuma informação.</span>
                    </label>
                </div>
            </div>

            <!-- ABA 2: IMAGEM & BOTÃO -->
            <div class="modal-tab-content" id="tab-col-media-cta">
                <div class="form-row-2">
                    <div class="form-group">
                        <label for="fieldColImageUrl">URL da Imagem Principal / Editorial *</label>
                        <div style="display: flex; gap: 8px;">
                            <input type="text" id="fieldColImageUrl" name="image_url" class="form-input" required 
                                   placeholder="/assets/images/products/colecao-completa-espelho.jpg" 
                                   onchange="updateImagePreview(this.value)">
                            <label class="btn-admin-secondary" style="margin: 0; cursor: pointer; white-space: nowrap;">
                                <span>Upload</span>
                                <input type="file" accept="image/*" style="display: none;" onchange="uploadCollectionImage(this)">
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fieldColImageAlt">Texto Alternativo da Imagem (SEO / Acessibilidade)</label>
                        <input type="text" id="fieldColImageAlt" name="image_alt" class="form-input" placeholder="Ex: Coleção Braceletes Caminhos Divinium no Espelho">
                    </div>
                </div>

                <!-- Preview da Imagem -->
                <div class="modal-image-preview-box">
                    <img id="colImagePreviewElem" src="/assets/images/products/colecao-completa-espelho.jpg" alt="Preview da Coleção">
                </div>

                <div class="form-divider" style="margin: 20px 0; border-top: 1px solid rgba(255,255,255,0.08);"></div>

                <h4 style="color: #CBD0D6; font-size: 0.95rem; margin-bottom: 15px;">✦ Botão de Ação Principal (CTA)</h4>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="fieldColButtonText">Texto do Botão *</label>
                        <input type="text" id="fieldColButtonText" name="button_text" class="form-input" required placeholder="Ex: Explorar Coleção, Comprar na Loja, Ver Joias">
                    </div>

                    <div class="form-group">
                        <label for="fieldColButtonUrl">Link / URL de Destino *</label>
                        <input type="text" id="fieldColButtonUrl" name="button_url" class="form-input" required placeholder="Ex: /loja, /produto/bracelete-caminhos, https://...">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="fieldColButtonTarget">Destino do Link</label>
                        <select id="fieldColButtonTarget" name="button_target" class="form-select">
                            <option value="_self">Abrir na Mesma Aba (_self)</option>
                            <option value="_blank">Abrir em Nova Aba (_blank)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fieldColButtonStyle">Estilo Visual do Botão</label>
                        <select id="fieldColButtonStyle" name="button_style" class="form-select">
                            <option value="gold">Dourado Divinium Nobre</option>
                            <option value="dark">Preto Minimalista com Borda Ouro</option>
                            <option value="ghost">Transparente Sofisticado</option>
                        </select>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label for="fieldColButtonSecText">Botão / Link Secundário (Opcional)</label>
                        <input type="text" id="fieldColButtonSecText" name="button_secondary_text" class="form-input" placeholder="Ex: Falar com Concierge, Atendimento">
                    </div>

                    <div class="form-group">
                        <label for="fieldColButtonSecUrl">URL do Link Secundário</label>
                        <input type="text" id="fieldColButtonSecUrl" name="button_secondary_url" class="form-input" placeholder="Ex: https://wa.me/5521965683841">
                    </div>
                </div>
            </div>

            <!-- ABA 3: JOIAS DA COLEÇÃO -->
            <div class="modal-tab-content" id="tab-col-products">
                <div style="margin-bottom: 15px;">
                    <strong style="color: #ede7df; display: block; margin-bottom: 4px;">Selecione e organize as joias que compõem esta coleção</strong>
                    <span style="color: #a8a096; font-size: 0.85rem;">Você pode marcar os produtos que devem aparecer nesta coleção, definir sua ordem e alternar a visibilidade individual.</span>
                </div>

                <div class="modal-products-selector-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th style="width: 40px;">Vincular</th>
                                <th style="width: 60px;">Foto</th>
                                <th>Nome da Joia</th>
                                <th>Preço Ref.</th>
                                <th>Categoria</th>
                                <th style="width: 90px;">Ordem</th>
                                <th style="width: 90px; text-align: center;">Visível</th>
                            </tr>
                        </thead>
                        <tbody id="modalProductsSelectorBody">
                            <!-- Preenchido dinamicamente via JS com base no catálogo -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-admin-secondary" onclick="closeCollectionModal()">Cancelar</button>
                <button type="button" class="btn-admin-secondary" onclick="previewCurrentFormCollection()">👁️ Pré-visualizar</button>
                <button type="submit" class="btn-admin-gold" id="btnSaveCollection">Salvar Coleção</button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================================= -->
<!-- MODAL DE NOVA CATEGORIA DINÂMICA -->
<!-- ======================================================================= -->
<div class="modal-overlay" id="categoryModal">
    <div class="modal-content">
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Nova Categoria de Joias</h3>
                <span class="modal-subtitle">Cria uma área independente no painel e na vitrine da loja.</span>
            </div>
            <button type="button" class="close-modal-btn" onclick="closeCategoryModal()">&times;</button>
        </div>

        <form id="categoryCreateForm" onsubmit="handleCategoryCreateSubmit(event)">
            <input type="hidden" name="_csrf_token" value="<?= CsrfMiddleware::getToken() ?>">

            <div class="form-group">
                <label for="newCatName">Nome da Categoria *</label>
                <input type="text" id="newCatName" name="name" class="form-input" required placeholder="Ex: Brincos, Pingentes, Alianças, Conjuntos">
            </div>

            <div class="form-group">
                <label for="newCatDesc">Descrição da Categoria</label>
                <textarea id="newCatDesc" name="description" class="form-textarea" rows="3" placeholder="Descreva os tipos de peças e características desta categoria..."></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-admin-secondary" onclick="closeCategoryModal()">Cancelar</button>
                <button type="submit" class="btn-admin-gold" id="btnSaveCategory">Criar Categoria</button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================================= -->
<!-- MODAL DE PRÉ-VISUALIZAÇÃO AO VIVO DA COLEÇÃO -->
<!-- ======================================================================= -->
<div class="modal-overlay" id="previewModal">
    <div class="modal-content modal-content-large" style="background: #202020; border: 1px solid rgba(167,173,180,0.3); padding: 0; max-height: 90vh;">
        <div class="modal-header" style="padding: 18px 24px; border-bottom: 1px solid rgba(255,255,255,0.08); background: rgba(20,22,26,0.95);">
            <div>
                <h3 class="modal-title" style="color: #CBD0D6; font-size: 1.15rem;">✦ Pré-visualização ao Vivo na Loja Pública</h3>
                <span class="modal-subtitle">Assim é como o cliente visualizará esta coleção no site DIVINIUM.</span>
            </div>
            <button type="button" class="close-modal-btn" onclick="closePreviewModal()">&times;</button>
        </div>

        <div class="preview-scroll-body" style="padding: 30px; overflow-y: auto; max-height: calc(90vh - 80px);">
            <div id="livePreviewContainer">
                <!-- Renderizado via JS -->
            </div>
        </div>
    </div>
</div>

<!-- ESTILOS EXCLUSIVOS DO MÓDULO LANÇAMENTOS -->
<style>
/* Abas de Categorias */
.lancamentos-category-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
    overflow-x: auto;
    padding-bottom: 6px;
    scrollbar-width: thin;
}
.lancamento-cat-tab {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 22px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    color: #a8a096;
    font-size: 0.92rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    white-space: nowrap;
}
.lancamento-cat-tab:hover {
    background: rgba(212, 186, 136, 0.08);
    border-color: rgba(212, 186, 136, 0.3);
    color: #ede7df;
}
.lancamento-cat-tab.active {
    background: linear-gradient(135deg, rgba(212, 186, 136, 0.18), rgba(202, 161, 92, 0.08));
    border-color: #737B85;
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(212, 186, 136, 0.12);
}
.cat-tab-badge {
    background: rgba(255, 255, 255, 0.1);
    padding: 2px 7px;
    border-radius: 12px;
    font-size: 0.72rem;
    font-weight: 600;
    color: #CBD0D6;
}
.lancamento-cat-tab.active .cat-tab-badge {
    background: #CBD0D6;
    color: #202020;
    font-weight: 700;
}
.add-cat-tab-btn {
    border-style: dashed;
    border-color: rgba(212, 186, 136, 0.3);
    color: #CBD0D6;
}

/* Áreas Independentes */
.category-independent-area {
    display: none;
    animation: fadeInArea 0.3s ease;
}
.category-independent-area.active {
    display: block;
}
@keyframes fadeInArea {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Summary Card */
.category-summary-card {
    background: rgba(20, 22, 26, 0.65);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 22px 26px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    gap: 20px;
}
.cat-summary-tag {
    font-size: 0.68rem;
    letter-spacing: 2px;
    color: #CBD0D6;
    font-weight: 700;
    margin-bottom: 4px;
}
.cat-summary-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.8rem;
    color: #ffffff;
    margin: 0 0 4px;
}
.cat-summary-desc {
    color: #a8a096;
    font-size: 0.88rem;
    margin: 0;
}
.cat-summary-stats {
    display: flex;
    align-items: center;
    gap: 15px;
}
.stat-pill {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    padding: 8px 16px;
    border-radius: 8px;
    text-align: center;
}
.stat-num {
    display: block;
    font-size: 1.25rem;
    font-weight: 700;
    color: #CBD0D6;
}
.stat-lbl {
    font-size: 0.7rem;
    color: #8e8880;
    text-transform: uppercase;
}

/* Card da Coleção */
.collections-cards-list {
    display: flex;
    flex-direction: column;
    gap: 22px;
}
.collection-item-card {
    background: rgba(20, 22, 26, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.09);
    border-radius: 12px;
    overflow: hidden;
    transition: border-color 0.25s ease, box-shadow 0.25s ease;
}
.collection-item-card:hover {
    border-color: rgba(212, 186, 136, 0.35);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}
.col-card-header {
    background: rgba(255, 255, 255, 0.02);
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    padding: 16px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}
.col-drag-and-title {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.col-order-pill {
    background: rgba(212, 186, 136, 0.12);
    color: #CBD0D6;
    border: 1px solid rgba(212, 186, 136, 0.25);
    padding: 3px 9px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 700;
    font-family: monospace;
}
.col-badge-tag {
    background: rgba(255, 255, 255, 0.06);
    color: #ede7df;
    border: 1px solid rgba(255, 255, 255, 0.12);
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.68rem;
    letter-spacing: 1.5px;
    font-weight: 600;
}
.col-name {
    font-size: 1.05rem;
    color: #ffffff;
    margin: 0;
    font-weight: 600;
}
.col-title-sep {
    color: #555;
}
.col-display-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.15rem;
    color: #CBD0D6;
}
.col-card-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

/* Switch Toggle */
.switch-toggle-wrap {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 0.8rem;
    color: #b5ada3;
    user-select: none;
    margin-right: 6px;
}
.switch-toggle-wrap input {
    display: none;
}
.switch-slider {
    width: 38px;
    height: 20px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 20px;
    position: relative;
    transition: background 0.25s ease;
}
.switch-slider::before {
    content: '';
    position: absolute;
    width: 14px;
    height: 14px;
    background: #ffffff;
    border-radius: 50%;
    top: 3px;
    left: 3px;
    transition: transform 0.25s ease;
}
.switch-toggle-wrap input:checked + .switch-slider {
    background: #A7ADB4;
}
.switch-toggle-wrap input:checked + .switch-slider::before {
    transform: translateX(18px);
}

.order-buttons-wrap {
    display: flex;
    gap: 3px;
}
.btn-order-arrow {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #ede7df;
    padding: 5px 8px;
    border-radius: 4px;
    font-size: 0.7rem;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-order-arrow:hover {
    background: rgba(212, 186, 136, 0.2);
    border-color: #737B85;
    color: #CBD0D6;
}

.btn-card-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 6px;
    font-size: 0.82rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: 1px solid transparent;
}
.btn-card-action.btn-preview {
    background: rgba(212, 186, 136, 0.1);
    border-color: rgba(212, 186, 136, 0.25);
    color: #CBD0D6;
}
.btn-card-action.btn-preview:hover {
    background: rgba(212, 186, 136, 0.2);
    color: #ffffff;
}
.btn-card-action.btn-edit {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(255, 255, 255, 0.12);
    color: #ede7df;
}
.btn-card-action.btn-edit:hover {
    background: rgba(255, 255, 255, 0.12);
    color: #ffffff;
}
.btn-card-action.btn-delete {
    background: rgba(239, 68, 68, 0.08);
    border-color: rgba(239, 68, 68, 0.25);
    color: #f87171;
}
.btn-card-action.btn-delete:hover {
    background: rgba(239, 68, 68, 0.2);
    color: #ffffff;
}

/* Corpo do Card */
.col-card-body {
    padding: 24px;
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 26px;
}
@media (max-width: 900px) {
    .col-card-body {
        grid-template-columns: 1fr;
    }
}
.col-media-preview {
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.col-img-box {
    width: 100%;
    aspect-ratio: 4 / 3;
    border-radius: 8px;
    overflow: hidden;
    background: #121110;
    border: 1px solid rgba(255, 255, 255, 0.08);
}
.col-img-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.col-img-box:hover img {
    transform: scale(1.04);
}
.btn-col-preview {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: linear-gradient(135deg, #CBD0D6, #A7ADB4);
    color: #202020;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(212, 186, 136, 0.2);
}
.secondary-link-tag {
    font-size: 0.74rem;
    color: #a8a096;
    text-align: center;
    display: block;
}

/* Informações */
.col-info-details {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.col-subtitle-text {
    font-size: 0.95rem;
    color: #CBD0D6;
    font-weight: 500;
}
.col-desc-text {
    font-size: 0.88rem;
    color: #b5ada3;
    line-height: 1.5;
}
.col-promo-banner {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(212, 186, 136, 0.08);
    border: 1px solid rgba(212, 186, 136, 0.2);
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 0.8rem;
    color: #CBD0D6;
    align-self: flex-start;
}
.promo-sparkle {
    color: #CBD0D6;
}

/* Chips de Produtos */
.col-products-container {
    margin-top: 10px;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    padding-top: 15px;
}
.col-products-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.lbl-products-title {
    font-size: 0.78rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #a8a096;
    font-weight: 600;
}
.btn-link-gold {
    background: none;
    border: none;
    color: #CBD0D6;
    font-size: 0.8rem;
    cursor: pointer;
    text-decoration: underline;
    padding: 0;
}
.col-products-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.col-prod-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 6px;
    padding: 5px 10px;
    transition: all 0.2s ease;
}
.col-prod-chip.chip-hidden {
    opacity: 0.5;
    background: rgba(239, 68, 68, 0.04);
    border-color: rgba(239, 68, 68, 0.2);
}
.chip-thumb {
    width: 26px;
    height: 26px;
    border-radius: 4px;
    overflow: hidden;
    background: #000;
}
.chip-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.chip-data {
    display: flex;
    flex-direction: column;
}
.chip-name {
    font-size: 0.78rem;
    color: #ede7df;
    font-weight: 500;
    max-width: 160px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.chip-price {
    font-size: 0.7rem;
    color: #CBD0D6;
}
.chip-visibility-btn {
    background: none;
    border: none;
    font-size: 0.8rem;
    cursor: pointer;
    padding: 2px 4px;
    border-radius: 3px;
    transition: background 0.2s;
}
.chip-visibility-btn:hover {
    background: rgba(255, 255, 255, 0.1);
}
.empty-products-hint {
    color: #8e8880;
    font-size: 0.82rem;
    font-style: italic;
}

/* Modal Tabs */
.modal-content-large {
    max-width: 860px;
    width: 95%;
}
.modal-tabs-header {
    display: flex;
    gap: 8px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    margin-bottom: 20px;
    padding-bottom: 2px;
}
.modal-tab-btn {
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    padding: 8px 16px;
    color: #a8a096;
    font-size: 0.88rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}
.modal-tab-btn:hover {
    color: #ede7df;
}
.modal-tab-btn.active {
    color: #CBD0D6;
    border-bottom-color: #CBD0D6;
}
.modal-tab-content {
    display: none;
}
.modal-tab-content.active {
    display: block;
}
.modal-image-preview-box {
    margin-top: 15px;
    width: 100%;
    max-height: 200px;
    border-radius: 8px;
    overflow: hidden;
    background: #000;
    border: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    justify-content: center;
    align-items: center;
}
.modal-image-preview-box img {
    max-height: 200px;
    max-width: 100%;
    object-fit: contain;
}
.modal-products-selector-table-wrap {
    max-height: 320px;
    overflow-y: auto;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 6px;
}

/* Empty State */
.empty-collections-state {
    text-align: center;
    padding: 60px 20px;
    background: rgba(20, 22, 26, 0.5);
    border: 1px dashed rgba(255, 255, 255, 0.12);
    border-radius: 12px;
}
.empty-icon {
    font-size: 2.5rem;
    margin-bottom: 12px;
}
.empty-collections-state h3 {
    color: #ffffff;
    font-size: 1.25rem;
    margin: 0 0 6px;
}
.empty-collections-state p {
    color: #8e8880;
    font-size: 0.9rem;
    margin: 0 0 20px;
}
</style>

<!-- SCRIPTS DE COMPORTAMENTO E PERSISTÊNCIA -->
<script>
// Catálogo completo de produtos em memória para a seleção
const catalogProducts = <?= json_encode($products, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT) ?>;
const categoriesList = <?= json_encode($categories, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT) ?>;

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
}

function switchCategoryTab(catSlug, btn) {
    document.querySelectorAll('.lancamento-cat-tab').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    document.querySelectorAll('.category-independent-area').forEach(a => a.classList.remove('active'));
    const targetArea = document.getElementById('area-cat-' + catSlug);
    if (targetArea) {
        targetArea.classList.add('active');
    }

    // Atualiza URL sem recarregar
    const url = new URL(window.location);
    url.searchParams.set('cat', catSlug);
    window.history.replaceState({}, '', url);
}

function switchModalTab(tabId, btn) {
    document.querySelectorAll('.modal-tab-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    document.querySelectorAll('.modal-tab-content').forEach(c => c.classList.remove('active'));
    const target = document.getElementById(tabId);
    if (target) target.classList.add('active');
}

function handleCategorySelectChange(selectElem) {
    const opt = selectElem.options[selectElem.selectedIndex];
    if (opt) {
        document.getElementById('fieldColCategoryId').value = opt.getAttribute('data-id') || '1';
        document.getElementById('fieldColCategoryName').value = opt.getAttribute('data-name') || selectElem.value;
    }
}

function updateImagePreview(url) {
    const img = document.getElementById('colImagePreviewElem');
    if (img && url) {
        img.src = url;
    }
}

// Upload de imagem da Coleção
async function uploadCollectionImage(fileInput) {
    if (!fileInput.files || !fileInput.files[0]) return;
    const file = fileInput.files[0];
    const formData = new FormData();
    formData.append('bg_file', file);
    formData.append('target_key', 'collection_image');
    
    const csrfToken = getCsrfToken();
    if (csrfToken) formData.append('_csrf_token', csrfToken);

    try {
        const res = await fetch('/admin/temas/upload-bg', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const rawText = await res.text();
        const data = JSON.parse(rawText);
        if (data.success && data.file_path) {
            document.getElementById('fieldColImageUrl').value = data.file_path;
            updateImagePreview(data.file_path);
            showToast('✓ Imagem enviada com sucesso!');
        } else {
            alert(data.error || 'Erro ao enviar imagem.');
        }
    } catch (err) {
        alert('Erro no upload da imagem: ' + err.message);
    }
}

// Abrir Modal para Nova Coleção
function openNewCollectionModal(e, defaultCatSlug) {
    if (e) e.preventDefault();
    document.getElementById('collectionForm').reset();
    document.getElementById('fieldColId').value = '';
    document.getElementById('colModalTitle').innerText = 'Nova Coleção';
    document.getElementById('colModalSubtitle').innerText = 'Cadastre uma nova coleção autoral e vincule suas joias.';

    if (defaultCatSlug) {
        const catSelect = document.getElementById('fieldColCategory');
        if (catSelect) {
            catSelect.value = defaultCatSlug;
            handleCategorySelectChange(catSelect);
        }
    }

    renderModalProductSelectors([]);
    switchModalTab('tab-col-general', document.querySelector('.modal-tab-btn'));
    updateImagePreview('/assets/images/products/colecao-completa-espelho.jpg');
    document.getElementById('collectionModal').classList.add('active');
}

// Abrir Modal para Editar Coleção
function editCollectionModal(col, targetTab = 'general') {
    document.getElementById('fieldColId').value = col.id;
    document.getElementById('fieldColName').value = col.name || '';
    document.getElementById('fieldColTitle').value = col.title || col.name || '';
    document.getElementById('fieldColSubtitle').value = col.subtitle || '';
    document.getElementById('fieldColDescription').value = col.description || '';
    document.getElementById('fieldColPromoText').value = col.promo_text || '';
    document.getElementById('fieldColBadge').value = col.badge || '';
    document.getElementById('fieldColImageUrl').value = col.image_url || '';
    document.getElementById('fieldColImageAlt').value = col.image_alt || '';
    document.getElementById('fieldColButtonText').value = col.button_text || 'Explorar Coleção';
    document.getElementById('fieldColButtonUrl').value = col.button_url || '/loja';
    document.getElementById('fieldColButtonTarget').value = col.button_target || '_self';
    document.getElementById('fieldColButtonStyle').value = col.button_style || 'gold';
    document.getElementById('fieldColButtonSecText').value = col.button_secondary_text || '';
    document.getElementById('fieldColButtonSecUrl').value = col.button_secondary_url || '';
    document.getElementById('fieldColOrder').value = col.sort_order || 1;
    document.getElementById('fieldColActive').checked = (col.is_active == 1);

    const catSelect = document.getElementById('fieldColCategory');
    if (catSelect && col.category_slug) {
        catSelect.value = col.category_slug;
        handleCategorySelectChange(catSelect);
    }

    updateImagePreview(col.image_url || '/assets/images/products/colecao-completa-espelho.jpg');

    renderModalProductSelectors(col.products || []);

    document.getElementById('colModalTitle').innerText = 'Editar Coleção: ' + col.name;
    document.getElementById('colModalSubtitle').innerText = 'Altere qualquer título, texto, imagem, botão ou joias desta coleção.';
    
    if (targetTab === 'products') {
        const prodTabBtn = document.querySelectorAll('.modal-tab-btn')[2];
        switchModalTab('tab-col-products', prodTabBtn);
    } else {
        switchModalTab('tab-col-general', document.querySelector('.modal-tab-btn'));
    }

    document.getElementById('collectionModal').classList.add('active');
}

function closeCollectionModal() {
    document.getElementById('collectionModal').classList.remove('active');
}

// Renderiza a tabela de seleção de produtos no modal
function renderModalProductSelectors(assignedProducts = []) {
    const tbody = document.getElementById('modalProductsSelectorBody');
    if (!tbody) return;
    tbody.innerHTML = '';

    const assignedMap = {};
    (assignedProducts || []).forEach((p, idx) => {
        assignedMap[p.id] = {
            order: p.sort_order ?? (idx + 1),
            is_active: p.is_active !== undefined ? p.is_active : 1
        };
    });

    catalogProducts.forEach((prod, i) => {
        const isLinked = !!assignedMap[prod.id];
        const prodOrder = isLinked ? assignedMap[prod.id].order : (i + 1);
        const prodActive = isLinked ? assignedMap[prod.id].is_active : 1;
        const priceFmt = (prod.price_cents ? (prod.price_cents / 100).toFixed(2).replace('.', ',') : '380,00');

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="text-align: center;">
                <input type="checkbox" class="prod-selector-chk" value="${prod.id}" ${isLinked ? 'checked' : ''} style="width: 16px; height: 16px; accent-color: #737B85;">
            </td>
            <td>
                <img src="${prod.image_url || '/assets/images/products/bracelete-verde.jpg'}" style="width: 34px; height: 34px; border-radius: 4px; object-fit: cover;">
            </td>
            <td>
                <strong style="color: #ffffff; font-size: 0.85rem; display: block;">${escapeHtml(prod.name)}</strong>
                <span style="color: #8e8880; font-size: 0.75rem;">SKU: ${escapeHtml(prod.sku || 'DIV-CAM')}</span>
            </td>
            <td style="color: #CBD0D6; font-size: 0.82rem;">R$ ${priceFmt}</td>
            <td style="color: #a8a096; font-size: 0.8rem;">${escapeHtml(prod.category_name || 'Braceletes')}</td>
            <td>
                <input type="number" class="form-input prod-order-input" data-id="${prod.id}" value="${prodOrder}" min="1" style="padding: 4px 8px; font-size: 0.8rem; width: 65px;">
            </td>
            <td style="text-align: center;">
                <input type="checkbox" class="prod-active-chk" data-id="${prod.id}" value="1" ${prodActive == 1 ? 'checked' : ''} style="width: 16px; height: 16px; accent-color: #737B85;">
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// Salvar Coleção
async function handleCollectionSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSaveCollection');
    btn.disabled = true;
    btn.innerText = 'Salvando no Servidor...';

    // Coleta produtos marcados
    const selectedProducts = [];
    document.querySelectorAll('.prod-selector-chk:checked').forEach(chk => {
        const pId = parseInt(chk.value);
        const prodData = catalogProducts.find(p => p.id == pId);
        if (prodData) {
            const orderInput = document.querySelector(`.prod-order-input[data-id="${pId}"]`);
            const activeInput = document.querySelector(`.prod-active-chk[data-id="${pId}"]`);
            selectedProducts.push({
                id: prodData.id,
                name: prodData.name,
                price_cents: prodData.price_cents,
                image_url: prodData.image_url,
                badge: prodData.stone_mineral || '',
                sort_order: orderInput ? parseInt(orderInput.value) || 1 : 1,
                is_active: activeInput && activeInput.checked ? 1 : 0,
                url: '/loja'
            });
        }
    });

    selectedProducts.sort((a, b) => a.sort_order - b.sort_order);
    document.getElementById('fieldColProductsJson').value = JSON.stringify(selectedProducts);

    const form = document.getElementById('collectionForm');
    const formData = new FormData(form);
    const csrfToken = getCsrfToken();
    if (csrfToken && !formData.has('_csrf_token')) {
        formData.append('_csrf_token', csrfToken);
    }

    try {
        const res = await fetch('/admin/lancamentos/salvar-colecao', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        const rawText = await res.text();
        let data;
        try {
            data = JSON.parse(rawText);
        } catch (je) {
            console.error('Resposta não-JSON:', rawText);
            throw new Error('O servidor respondeu com formato inesperado. Por favor, tente novamente.');
        }

        if (data.success) {
            showToast('✓ Coleção salva com sucesso!');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            alert(data.error || 'Erro ao salvar coleção.');
            btn.disabled = false;
            btn.innerText = 'Salvar Coleção';
        }
    } catch (err) {
        alert('Erro ao processar: ' + err.message);
        btn.disabled = false;
        btn.innerText = 'Salvar Coleção';
    }
}

// Alternar visibilidade da coleção
async function toggleCollectionActive(colId, chk) {
    const csrfToken = getCsrfToken();
    const formData = new FormData();
    formData.append('id', colId);
    if (csrfToken) formData.append('_csrf_token', csrfToken);

    try {
        const res = await fetch('/admin/lancamentos/toggle-visibilidade', {
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
            const lbl = chk.parentElement.querySelector('.switch-label-text');
            if (lbl) {
                lbl.innerText = data.is_active ? 'Visível' : 'Oculto';
            }
            showToast(data.is_active ? '✓ Coleção ativada na loja pública' : '✓ Coleção ocultada da loja');
        } else {
            alert(data.error || 'Erro ao alternar visibilidade.');
            chk.checked = !chk.checked;
        }
    } catch (err) {
        alert('Erro de conexão: ' + err.message);
        chk.checked = !chk.checked;
    }
}

// Alternar visibilidade de um produto individual dentro da coleção
async function toggleCollectionProduct(colId, prodId, btn) {
    const csrfToken = getCsrfToken();
    const formData = new FormData();
    formData.append('collection_id', colId);
    formData.append('product_id', prodId);
    if (csrfToken) formData.append('_csrf_token', csrfToken);

    try {
        const res = await fetch('/admin/lancamentos/toggle-produto', {
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
            const chip = document.getElementById(`prod-chip-${colId}-${prodId}`);
            if (chip) {
                if (data.is_active) {
                    chip.classList.remove('chip-hidden');
                    btn.innerText = '👁️';
                } else {
                    chip.classList.add('chip-hidden');
                    btn.innerText = '🚫';
                }
            }
            showToast(data.is_active ? '✓ Produto visível nesta coleção' : '✓ Produto ocultado desta coleção');
        } else {
            alert(data.error || 'Erro ao alternar visibilidade do produto.');
        }
    } catch (err) {
        alert('Erro ao conectar: ' + err.message);
    }
}

// Mover coleção para cima
function moveCollectionUp(colId, catSlug) {
    const list = document.getElementById('list-collections-' + catSlug) || document.querySelector(`#area-cat-${catSlug} .collections-cards-list`);
    const card = document.getElementById('col-card-' + colId);
    if (!card || !list) return;

    const prev = card.previousElementSibling;
    if (prev && prev.classList.contains('collection-item-card')) {
        list.insertBefore(card, prev);
        saveCollectionsOrder(catSlug);
    }
}

// Mover coleção para baixo
function moveCollectionDown(colId, catSlug) {
    const list = document.getElementById('list-collections-' + catSlug) || document.querySelector(`#area-cat-${catSlug} .collections-cards-list`);
    const card = document.getElementById('col-card-' + colId);
    if (!card || !list) return;

    const next = card.nextElementSibling;
    if (next && next.classList.contains('collection-item-card')) {
        list.insertBefore(next, card);
        saveCollectionsOrder(catSlug);
    }
}

// Salvar a nova ordem das coleções
async function saveCollectionsOrder(catSlug) {
    const list = document.getElementById('list-collections-' + catSlug) || document.querySelector(`#area-cat-${catSlug} .collections-cards-list`);
    const allCards = list ? list.querySelectorAll('.collection-item-card') : document.querySelectorAll('.collection-item-card');
    const orderedIds = [];
    allCards.forEach(c => {
        const id = parseInt(c.getAttribute('data-id'));
        if (id > 0) orderedIds.push(id);
    });

    const csrfToken = getCsrfToken();
    const formData = new FormData();
    formData.append('ordered_ids', JSON.stringify(orderedIds));
    if (csrfToken) formData.append('_csrf_token', csrfToken);

    try {
        const res = await fetch('/admin/lancamentos/reordenar-colecoes', {
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
            showToast('✓ Ordem das coleções atualizada!');
            // Atualiza os pills visuais de ordem (#1, #2...)
            allCards.forEach((c, idx) => {
                const pill = c.querySelector('.col-order-pill');
                if (pill) pill.innerText = '#' + (idx + 1);
            });
        }
    } catch (err) {
        console.error('Erro ao reordenar:', err);
    }
}

// Excluir Coleção
async function deleteCollection(colId, colName) {
    if (!confirm(`Deseja realmente excluir a coleção "${colName}"?\n\nOs produtos associados NÃO serão apagados e continuarão intactos no catálogo.`)) {
        return;
    }

    const csrfToken = getCsrfToken();
    const formData = new FormData();
    formData.append('id', colId);
    if (csrfToken) formData.append('_csrf_token', csrfToken);

    try {
        const res = await fetch('/admin/lancamentos/excluir-colecao', {
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
            const card = document.getElementById('col-card-' + colId);
            if (card) {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                setTimeout(() => card.remove(), 250);
            }
            showToast('✓ Coleção excluída com sucesso.');
        } else {
            alert(data.error || 'Erro ao excluir coleção.');
        }
    } catch (err) {
        alert('Erro ao excluir: ' + err.message);
    }
}

// Modal Nova Categoria
function openNewCategoryModal(e) {
    if (e) e.preventDefault();
    document.getElementById('categoryCreateForm').reset();
    document.getElementById('categoryModal').classList.add('active');
}
function closeCategoryModal() {
    document.getElementById('categoryModal').classList.remove('active');
}

// Salvar Nova Categoria
async function handleCategoryCreateSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSaveCategory');
    btn.disabled = true;
    btn.innerText = 'Criando Categoria...';

    const formData = new FormData(document.getElementById('categoryCreateForm'));
    const csrfToken = getCsrfToken();
    if (csrfToken && !formData.has('_csrf_token')) {
        formData.append('_csrf_token', csrfToken);
    }

    try {
        const res = await fetch('/admin/lancamentos/salvar-categoria', {
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
            showToast('✓ Nova categoria criada com sucesso!');
            setTimeout(() => {
                window.location.reload();
            }, 800);
        } else {
            alert(data.error || 'Erro ao criar categoria.');
            btn.disabled = false;
            btn.innerText = 'Criar Categoria';
        }
    } catch (err) {
        alert('Erro ao processar: ' + err.message);
        btn.disabled = false;
        btn.innerText = 'Criar Categoria';
    }
}

// Modal de Pré-visualização da Coleção
function previewCollectionModal(col) {
    const container = document.getElementById('livePreviewContainer');
    if (!container) return;

    const productsHtml = (col.products || []).filter(p => p.is_active != 0).map(p => `
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column;">
            <div style="width: 100%; aspect-ratio: 1/1; background: #000; overflow: hidden;">
                <img src="${p.image_url || '/assets/images/products/bracelete-verde.jpg'}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div style="padding: 14px; display: flex; flex-direction: column; gap: 6px; flex: 1;">
                <span style="font-size: 0.68rem; letter-spacing: 1px; color: #CBD0D6; text-transform: uppercase;">${escapeHtml(p.badge || 'ALTA JOALHERIA')}</span>
                <strong style="color: #ffffff; font-size: 0.95rem;">${escapeHtml(p.name)}</strong>
                <span style="color: #CBD0D6; font-size: 1rem; font-weight: 600; margin-top: auto;">R$ ${(p.price_cents ? (p.price_cents/100).toFixed(2).replace('.', ',') : '380,00')}</span>
            </div>
        </div>
    `).join('');

    container.innerHTML = `
        <div style="text-align: center; max-width: 650px; margin: 0 auto 30px;">
            <span style="display: inline-block; font-size: 0.75rem; letter-spacing: 2px; color: #CBD0D6; font-weight: 700; margin-bottom: 8px;">${escapeHtml(col.badge || 'LANÇAMENTO EXCLUSIVO')}</span>
            <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 2.2rem; color: #ffffff; margin: 0 0 10px;">${escapeHtml(col.title || col.name)}</h2>
            ${col.subtitle ? `<p style="font-size: 1rem; color: #CBD0D6; margin: 0 0 10px;">${escapeHtml(col.subtitle)}</p>` : ''}
            ${col.description ? `<p style="font-size: 0.9rem; color: #a8a096; line-height: 1.6; margin: 0 0 20px;">${escapeHtml(col.description)}</p>` : ''}
            <div style="display: flex; gap: 12px; justify-content: center; align-items: center; flex-wrap: wrap;">
                <a href="${escapeHtml(col.button_url || '/loja')}" target="${escapeHtml(col.button_target || '_self')}" 
                   style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 26px; background: linear-gradient(135deg, #CBD0D6, #A7ADB4); color: #202020; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 0.95rem; box-shadow: 0 4px 15px rgba(167,173,180,0.3);">
                    <span>${escapeHtml(col.button_text || 'Explorar Coleção')}</span>
                    <span>↗</span>
                </a>
                ${col.button_secondary_text ? `
                    <a href="${escapeHtml(col.button_secondary_url || '#')}" target="_blank" style="padding: 11px 22px; border: 1px solid rgba(167,173,180,0.4); color: #CBD0D6; border-radius: 6px; text-decoration: none; font-size: 0.9rem;">
                        ${escapeHtml(col.button_secondary_text)}
                    </a>
                ` : ''}
            </div>
        </div>

        <div style="margin: 30px 0; border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1); max-height: 420px; background: #000;">
            <img src="${escapeHtml(col.image_url || '/assets/images/products/colecao-completa-espelho.jpg')}" alt="${escapeHtml(col.image_alt || col.name)}" style="width: 100%; height: 100%; object-fit: cover;">
        </div>

        ${productsHtml ? `
            <div style="margin-top: 35px;">
                <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; color: #ede7df; margin: 0 0 16px; text-align: center;">Peças da Coleção</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 18px;">
                    ${productsHtml}
                </div>
            </div>
        ` : ''}
    `;

    document.getElementById('previewModal').classList.add('active');
}

function previewCurrentFormCollection() {
    const colData = {
        name: document.getElementById('fieldColName').value || 'Nova Coleção',
        title: document.getElementById('fieldColTitle').value || 'Título da Coleção',
        subtitle: document.getElementById('fieldColSubtitle').value || '',
        description: document.getElementById('fieldColDescription').value || '',
        badge: document.getElementById('fieldColBadge').value || 'LANÇAMENTO',
        image_url: document.getElementById('fieldColImageUrl').value || '/assets/images/products/colecao-completa-espelho.jpg',
        image_alt: document.getElementById('fieldColImageAlt').value || '',
        button_text: document.getElementById('fieldColButtonText').value || 'Explorar Coleção',
        button_url: document.getElementById('fieldColButtonUrl').value || '/loja',
        button_target: document.getElementById('fieldColButtonTarget').value || '_self',
        button_secondary_text: document.getElementById('fieldColButtonSecText').value || '',
        button_secondary_url: document.getElementById('fieldColButtonSecUrl').value || '',
        products: []
    };

    document.querySelectorAll('.prod-selector-chk:checked').forEach(chk => {
        const pId = parseInt(chk.value);
        const pData = catalogProducts.find(p => p.id == pId);
        if (pData) {
            colData.products.push(pData);
        }
    });

    previewCollectionModal(colData);
}

function closePreviewModal() {
    document.getElementById('previewModal').classList.remove('active');
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

function showToast(msg) {
    const existing = document.getElementById('adminToast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'adminToast';
    toast.innerText = msg;
    toast.style.cssText = `
        position: fixed;
        bottom: 25px;
        right: 25px;
        background: #252525;
        border: 1px solid rgba(167, 173, 180, 0.4);
        color: #ffffff;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.4);
        z-index: 999999;
        animation: fadeInToast 0.3s ease;
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.4s ease';
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}
</script>
