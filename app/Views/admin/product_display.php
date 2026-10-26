<?php
/**
 * VIEW: CENTRAL DE INFORMAÇÕES E EXIBIÇÃO DOS PRODUTOS
 * DIVINIUM HIGH-JEWELRY PUBLIC DISPLAY CONTROLLER
 * 
 * Reúne em um único local centralizado todos os campos, textos, selos,
 * botões, preços e controles de visibilidade das joias exibidas publicamente.
 * 
 * Preserva 100% do banco de dados interno, integrações com Bling ERP,
 * estoque, pedidos e variações.
 */

use App\Models\Setting;
use App\Core\View;

/**
 * Helper reutilizável para renderizar o interruptor Exibir / Ocultar
 */
$renderToggle = function(string $key, string $default = '1'): string {
    $val = Setting::get($key, $default);
    $isVisible = ($val === '1' || $val === 1 || $val === true);
    $class = $isVisible ? 'is-visible' : 'is-hidden';
    $label = $isVisible ? 'Exibindo' : 'Oculto';
    $checked = $isVisible ? 'checked' : '';
    return '
    <label class="element-toggle-switch ' . $class . '" title="Exibir ou ocultar esta informação publicamente">
        <input type="hidden" name="' . $key . '" value="0">
        <input type="checkbox" name="' . $key . '" value="1" ' . $checked . ' onchange="handleToggleSwitch(this)">
        <span class="toggle-text">' . $label . '</span>
    </label>';
};
?>

<div class="landing-editor-container">

    <!-- BARRA SUPERIOR DE AÇÕES & CABEÇALHO -->
    <div class="editor-header-bar">
        <div class="editor-header-info">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                <span style="font-size: 1.5rem;">💎</span>
                <h2 style="margin: 0;">Informações e Exibição dos Produtos</h2>
                <span class="brand-badge" style="background: rgba(202, 161, 92, 0.15); color: #CBD0D6; border: 1px solid rgba(202, 161, 92, 0.3); padding: 2px 8px; border-radius: 4px; font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 600;">CENTRALIZADO</span>
            </div>
            <p>Gerencie em um único local todas as informações públicas que aparecem nas páginas e cards dos produtos: SKU, selos, etiquetas, botões, preços, especificações e enquadramento de imagens sem cortes.</p>
        </div>
        <div class="editor-header-actions">
            <a href="/loja" target="_blank" class="btn-admin-secondary" title="Abrir a Boutique em nova aba">
                <span>🛍️ Ver Vitrine ao Vivo ↗</span>
            </a>
            <button type="button" class="btn-admin-gold" onclick="document.getElementById('productDisplayForm').requestSubmit()">
                <span>Salvar Todas as Alterações</span>
            </button>
        </div>
    </div>

    <!-- BANNER DE GARANTIA E SEGURANÇA DOS DADOS INTERNOS -->
    <div style="background: rgba(167, 173, 180, 0.08); border: 1px solid rgba(167, 173, 180, 0.25); border-radius: 10px; padding: 14px 18px; margin-bottom: 22px; display: flex; align-items: flex-start; gap: 14px;">
        <span style="font-size: 1.4rem; line-height: 1;">🛡️</span>
        <div style="font-size: 0.84rem; color: #ded7cf; line-height: 1.5;">
            <strong style="color: #ffffff; display: block; margin-bottom: 2px;">Preservação Total dos Cadastros e Integrações:</strong>
            Ocultar qualquer informação nesta central (como SKU, selos ou especificações) apenas desativa a exibição visual para os clientes no site. Os dados cadastrais internos, códigos SKU, saldo de estoque, variações minerais e a sincronização com o <strong>Bling ERP</strong> permanecem <strong>100% intactos e ativos</strong>.
        </div>
    </div>

    <!-- NAVEGAÇÃO RÁPIDA POR SEÇÕES -->
    <div class="editor-nav-tabs" style="margin-bottom: 20px;">
        <button type="button" class="tab-btn active" onclick="scrollToSection('sec-sku', this)">
            <span>🏷️ Código SKU & Identificação</span>
        </button>
        <button type="button" class="tab-btn" onclick="scrollToSection('sec-badges', this)">
            <span>✦ Selos, Etiquetas & Badges</span>
        </button>
        <button type="button" class="tab-btn" onclick="scrollToSection('sec-images', this)">
            <span>🖼️ Enquadramento de Imagens</span>
        </button>
        <button type="button" class="tab-btn" onclick="scrollToSection('sec-prices', this)">
            <span>💰 Preços & Pagamento</span>
        </button>
        <button type="button" class="tab-btn" onclick="scrollToSection('sec-specs', this)">
            <span>📋 Ficha Técnica & Materiais</span>
        </button>
        <button type="button" class="tab-btn" onclick="scrollToSection('sec-buttons', this)">
            <span>🔘 Botões & Canais de Compra</span>
        </button>
    </div>

    <form id="productDisplayForm" action="/admin/produtos-exibicao/salvar" method="POST" onsubmit="handleDisplaySubmit(event)">
        <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">

        <!-- ============================================================== -->
        <!-- SEÇÃO 1: CÓDIGO SKU & IDENTIFICAÇÃO PÚBLICA -->
        <!-- ============================================================== -->
        <div class="editor-section-card active" id="sec-sku" style="margin-bottom: 24px;">
            <div class="section-card-header">
                <div class="section-icon">🏷️</div>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                        <h3 style="margin: 0;">1. Código SKU & Referência Pública</h3>
                        <span style="background: rgba(255,255,255,0.06); color: #CBD0D6; font-size: 0.72rem; padding: 3px 8px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.1);">
                            📍 Rodapé da Imagem e Ficha Técnica (/produto/{slug})
                        </span>
                    </div>
                    <p>Controle se o código interno da joia (#DIV-...) deve ser exibido publicamente para os visitantes ou permanecer estritamente interno.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width" style="background: rgba(212, 186, 136, 0.05); padding: 14px 16px; border-radius: 8px; border: 1px solid rgba(212, 186, 136, 0.2);">
                    <div class="field-label-row">
                        <div>
                            <label style="color: #ffffff; font-weight: 600; font-size: 0.92rem; margin: 0;">
                                Exibir Código SKU Publicamente no Site
                            </label>
                            <span class="field-hint" style="margin-top: 3px;">
                                Quando <strong>Oculto</strong>, o código #DIV-... desaparece da página do produto e dos cards. O cadastro no banco, estoque e Bling continuam funcionando normalmente.
                            </span>
                        </div>
                        <?= $renderToggle('store_sku_visible', '1') ?>
                    </div>
                </div>

                <div class="form-group <?= Setting::get('store_sku_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="store_sku_label">Texto de Apresentação do SKU (Na Barra da Imagem)</label>
                    <input type="text" id="store_sku_label" name="store_sku_label" class="form-input" 
                           value="<?= View::e(Setting::get('store_sku_label', 'Código da Joia:')) ?>" placeholder="Ex: Código da Joia:">
                    <span class="field-hint">Rótulo exibido antes do número do SKU abaixo da foto principal da joia.</span>
                </div>

                <div class="form-group <?= Setting::get('store_sku_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_spec_sku_visible">Exibir Linha do SKU na Ficha Técnica</label>
                        <?= $renderToggle('store_spec_sku_visible', '1') ?>
                    </div>
                    <input type="text" id="store_spec_sku_label" name="store_spec_sku_label" class="form-input" 
                           value="<?= View::e(Setting::get('store_spec_sku_label', 'Referência / SKU')) ?>" placeholder="Ex: Referência / SKU">
                    <span class="field-hint">Rótulo utilizado na tabela de especificações técnicas da joia.</span>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 2: BOTÕES, SELOS E ETIQUETAS DOS PRODUTOS -->
        <!-- ============================================================== -->
        <div class="editor-section-card active" id="sec-badges" style="margin-bottom: 24px;">
            <div class="section-card-header">
                <div class="section-icon">✦</div>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                        <h3 style="margin: 0;">2. Botões, Selos e Etiquetas dos Produtos</h3>
                        <span style="background: rgba(255,255,255,0.06); color: #CBD0D6; font-size: 0.72rem; padding: 3px 8px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.1);">
                            📍 Sobrepostos à Foto (Vitrine /loja e Detalhe /produto)
                        </span>
                    </div>
                    <p>Personalize os textos dos selos e etiquetas ("Banho de Prata", "Sob Encomenda", gemas minerais) ou oculte-os individualmente.</p>
                </div>
            </div>

            <div class="form-grid">
                <!-- Selo de Metal Nobre na Página do Produto -->
                <div class="form-group <?= Setting::get('store_badge_metal_visible', '1') !== '1' ? 'is-dimmed' : '' ?>" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                    <div class="field-label-row">
                        <label for="store_badge_metal_text">Selo de Matéria-Prima na Página da Joia</label>
                        <?= $renderToggle('store_badge_metal_visible', '1') ?>
                    </div>
                    <input type="text" id="store_badge_metal_text" name="store_badge_metal_text" class="form-input" 
                           value="<?= View::e(Setting::get('store_badge_metal_text', Setting::get('store_card_badge_metal', 'Banho de Prata'))) ?>" placeholder="Ex: Banho de Prata">
                    <span class="field-hint">Selo sobreposto no canto superior da foto na página de detalhes (/produto/{slug}).</span>
                </div>

                <!-- Selo de Metal Nobre no Card da Vitrine -->
                <div class="form-group <?= Setting::get('store_card_badge_metal_visible', '1') !== '1' ? 'is-dimmed' : '' ?>" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                    <div class="field-label-row">
                        <label for="store_card_badge_metal">Selo de Matéria-Prima nos Cards da Vitrine</label>
                        <?= $renderToggle('store_card_badge_metal_visible', '1') ?>
                    </div>
                    <input type="text" id="store_card_badge_metal" name="store_card_badge_metal" class="form-input" 
                           value="<?= View::e(Setting::get('store_card_badge_metal', 'Banho de Prata')) ?>" placeholder="Ex: Banho de Prata">
                    <span class="field-hint">Selo compacto exibido sobre a foto dos produtos na listagem da vitrine (/loja).</span>
                </div>

                <!-- Selo de Gema Mineral Natural -->
                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                    <div class="field-label-row">
                        <label>Selo da Gema Mineral na Foto da Joia</label>
                        <?= $renderToggle('store_badge_mineral_visible', '1') ?>
                    </div>
                    <span class="field-hint">Exibe etiqueta com o nome da pedra mineral da peça (ex: "✦ Concha Verde", "✦ Ametista").</span>
                </div>

                <!-- Selo de Status: Sob Encomenda / Esgotado -->
                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                    <div class="field-label-row">
                        <label>Selo de Disponibilidade na Foto (Status)</label>
                        <?= $renderToggle('store_badge_status_visible', '1') ?>
                    </div>
                    <span class="field-hint">Exibe etiqueta especial quando a peça for sob encomenda ou estiver temporariamente esgotada.</span>
                </div>

                <div class="form-group <?= Setting::get('store_badge_status_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="store_badge_preorder_text">Texto para Peças Sob Encomenda</label>
                    <input type="text" id="store_badge_preorder_text" name="store_badge_preorder_text" class="form-input" 
                           value="<?= View::e(Setting::get('store_badge_preorder_text', 'Sob Encomenda')) ?>" placeholder="Sob Encomenda">
                    <span class="field-hint">Etiqueta exibida quando o estoque estiver configurado como 'preorder'.</span>
                </div>

                <div class="form-group <?= Setting::get('store_badge_status_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="store_badge_outofstock_text">Texto para Peças Indisponíveis</label>
                    <input type="text" id="store_badge_outofstock_text" name="store_badge_outofstock_text" class="form-input" 
                           value="<?= View::e(Setting::get('store_badge_outofstock_text', 'Indisponível')) ?>" placeholder="Indisponível">
                    <span class="field-hint">Etiqueta exibida quando o produto estiver com saldo de estoque zero.</span>
                </div>

                <div class="form-group <?= Setting::get('store_card_variations_badge_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_card_variations_badge_suffix">Selo de Variações Minerais no Card</label>
                        <?= $renderToggle('store_card_variations_badge_visible', '1') ?>
                    </div>
                    <input type="text" id="store_card_variations_badge_suffix" name="store_card_variations_badge_suffix" class="form-input" 
                           value="<?= View::e(Setting::get('store_card_variations_badge_suffix', 'Pedras Minerais')) ?>" placeholder="Pedras Minerais">
                    <span class="field-hint">Exemplo de exibição no card: "✦ 4 Pedras Minerais".</span>
                </div>

                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                    <div class="field-label-row">
                        <label>Selo do Canal Homologado no Card (Shopee ↗)</label>
                        <?= $renderToggle('store_card_channel_badge_visible', '1') ?>
                    </div>
                    <span class="field-hint">Mostra o canal parceiro no card da vitrine quando ativo.</span>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 3: ENQUADRAMENTO DE IMAGENS (SEM CORTES) -->
        <!-- ============================================================== -->
        <div class="editor-section-card active" id="sec-images" style="margin-bottom: 24px;">
            <div class="section-card-header">
                <div class="section-icon">🖼️</div>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                        <h3 style="margin: 0;">3. Enquadramento e Exibição de Imagens</h3>
                        <span style="background: rgba(255,255,255,0.06); color: #CBD0D6; font-size: 0.72rem; padding: 3px 8px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.1);">
                            📍 Todas as Fotos de Joias do Site
                        </span>
                    </div>
                    <p>Garanta que as joias apareçam com sua silhueta completa, sem cortes nas laterais, topo ou base da imagem.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width" style="background: rgba(202, 161, 92, 0.05); padding: 14px 16px; border-radius: 8px; border: 1px solid rgba(202, 161, 92, 0.2);">
                    <label for="store_image_fit" style="color: #ffffff; font-weight: 600; font-size: 0.92rem; margin-bottom: 6px; display: block;">
                        Modo de Enquadramento da Fotografia da Joia
                    </label>
                    <select id="store_image_fit" name="store_image_fit" class="form-input" style="max-width: 480px;">
                        <option value="contain" <?= Setting::get('store_image_fit', 'contain') === 'contain' ? 'selected' : '' ?>>
                            ✓ Ajustar Peça Inteira Sem Cortes (Recomendado - 100% Visível)
                        </option>
                        <option value="cover" <?= Setting::get('store_image_fit') === 'cover' ? 'selected' : '' ?>>
                            Preencher Container (Pode cortar partes da joia se a proporção for diferente)
                        </option>
                    </select>
                    <span class="field-hint" style="margin-top: 6px;">
                        No modo <strong>Ajustar Peça Inteira (Contain)</strong>, o sistema calcula as proporções exatas da imagem para que nenhum detalhe artesanal do anel, bracelete ou pingente seja cortado.
                    </span>
                </div>

                <div class="form-group">
                    <label for="store_image_bg">Cor de Fundo do Quadro da Fotografia</label>
                    <select id="store_image_bg" name="store_image_bg" class="form-input">
                        <option value="#000000" <?= Setting::get('store_image_bg', '#000000') === '#000000' ? 'selected' : '' ?>>🖤 Preto Profundo (#000000) — Padrão Nobre</option>
                        <option value="#0d0c0b" <?= Setting::get('store_image_bg') === '#0d0c0b' ? 'selected' : '' ?>>☕ Preto Suave / Ônix (#0d0c0b)</option>
                        <option value="#181818" <?= Setting::get('store_image_bg') === '#181818' ? 'selected' : '' ?>>🔘 Grafite Escuro (#181818)</option>
                        <option value="#FFFFFF" <?= Setting::get('store_image_bg') === '#FFFFFF' ? 'selected' : '' ?>>⚪ Branco Puro (#FFFFFF)</option>
                        <option value="#F8F8F7" <?= Setting::get('store_image_bg') === '#F8F8F7' ? 'selected' : '' ?>>🤍 Branco Perolado (#F8F8F7)</option>
                    </select>
                    <span class="field-hint">Fundo aplicado ao redor da foto quando a proporção da imagem for ligeiramente retangular.</span>
                </div>

                <div class="form-group">
                    <label for="store_image_padding">Margem Interna de Respiro (Padding)</label>
                    <select id="store_image_padding" name="store_image_padding" class="form-input">
                        <option value="8px" <?= Setting::get('store_image_padding', '8px') === '8px' ? 'selected' : '' ?>>Normal (8px no card / 12px na galeria)</option>
                        <option value="12px" <?= Setting::get('store_image_padding') === '12px' ? 'selected' : '' ?>>Ampla (12px no card / 16px na galeria)</option>
                        <option value="4px" <?= Setting::get('store_image_padding') === '4px' ? 'selected' : '' ?>>Compacta (4px / borda mínima)</option>
                    </select>
                    <span class="field-hint">Garante que a joia não encoste nas bordas do card ou da moldura.</span>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 4: PREÇOS, DESCONTOS & CONDIÇÕES DE PAGAMENTO -->
        <!-- ============================================================== -->
        <div class="editor-section-card active" id="sec-prices" style="margin-bottom: 24px;">
            <div class="section-card-header">
                <div class="section-icon">💰</div>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                        <h3 style="margin: 0;">4. Preços, Descontos & Condições de Pagamento</h3>
                        <span style="background: rgba(255,255,255,0.06); color: #CBD0D6; font-size: 0.72rem; padding: 3px 8px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.1);">
                            📍 Abaixo do Título do Produto
                        </span>
                    </div>
                    <p>Configure a exibição de preços oficiais, descontos no Pix (-5%) e parcelamento sem juros.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                    <div class="field-label-row">
                        <label>Exibir Preço de Venda Principal</label>
                        <?= $renderToggle('store_price_visible', '1') ?>
                    </div>
                    <span class="field-hint">Exibe o valor de referência oficial cadastrado no produto.</span>
                </div>

                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                    <div class="field-label-row">
                        <label>Exibir Preço Comparativo "De R$ ..."</label>
                        <?= $renderToggle('store_compare_price_visible', '1') ?>
                    </div>
                    <span class="field-hint">Mostra o preço original tachado quando houver valor promocional cadastrado.</span>
                </div>

                <div class="form-group <?= Setting::get('store_pix_discount_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_pix_discount_label">Preço com Desconto no Pix (-5%)</label>
                        <?= $renderToggle('store_pix_discount_visible', '1') ?>
                    </div>
                    <input type="text" id="store_pix_discount_label" name="store_pix_discount_label" class="form-input" 
                           value="<?= View::e(Setting::get('store_pix_discount_label', 'no Pix (-5%)')) ?>" placeholder="no Pix (-5%)">
                    <span class="field-hint">Rótulo exibido ao lado do valor calculado no Pix.</span>
                </div>

                <div class="form-group full-width <?= Setting::get('store_installments_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_detail_installments_text">Texto Informativo de Parcelamento</label>
                        <?= $renderToggle('store_installments_visible', '1') ?>
                    </div>
                    <input type="text" id="store_detail_installments_text" name="store_detail_installments_text" class="form-input" 
                           value="<?= View::e(Setting::get('store_detail_installments_text', 'Em até 6x sem juros nos canais oficiais homologados')) ?>" placeholder="Em até 6x sem juros nos canais oficiais homologados">
                    <span class="field-hint">Frase explicativa exibida diretamente abaixo do preço principal.</span>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 5: FICHA TÉCNICA, MATERIAIS & ESPECIFICAÇÕES -->
        <!-- ============================================================== -->
        <div class="editor-section-card active" id="sec-specs" style="margin-bottom: 24px;">
            <div class="section-card-header">
                <div class="section-icon">📋</div>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                        <h3 style="margin: 0;">5. Ficha Técnica, Materiais & Especificações</h3>
                        <span style="background: rgba(255,255,255,0.06); color: #CBD0D6; font-size: 0.72rem; padding: 3px 8px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.1);">
                            📍 Tabela "Especificações & Manufatura" (/produto/{slug})
                        </span>
                    </div>
                    <p>Controle completo das informações da joia: renomeie títulos, personalize descrições, oculte ou exiba linhas individualmente, adicione novos campos autorais (como Design) e reordene as linhas conforme preferir.</p>
                </div>
            </div>

            <div class="form-grid">
                <!-- Controle Geral do Bloco -->
                <div class="form-group full-width" style="background: rgba(212, 186, 136, 0.05); padding: 12px 16px; border-radius: 8px; border: 1px solid rgba(212, 186, 136, 0.15);">
                    <div class="field-label-row">
                        <label style="color: #ffffff; font-weight: 600; font-size: 0.9rem;">Exibir Bloco de Ficha Técnica Completo</label>
                        <?= $renderToggle('store_detail_show_specs', '1') ?>
                    </div>
                    <span class="field-hint">Habilita ou oculta toda a tabela técnica na página de detalhes da joia.</span>
                </div>

                <!-- Título do Bloco na Página do Produto -->
                <div class="form-group full-width" style="background: rgba(255,255,255,0.02); padding: 12px 16px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                    <label for="store_specs_card_title" style="color: #CBD0D6; font-weight: 600; font-size: 0.85rem; margin-bottom: 4px; display: block;">Título do Bloco da Ficha Técnica</label>
                    <input type="text" id="store_specs_card_title" name="store_specs_card_title" class="form-input" 
                           value="<?= View::e(Setting::get('store_specs_card_title', 'Especificações & Manufatura')) ?>" placeholder="Ex: Especificações & Manufatura">
                    <span class="field-hint">Título exibido acima da tabela de especificações na página pública do produto.</span>
                </div>

                <!-- Painel Informativo de Reordenação e Gestão -->
                <div class="form-group full-width" style="margin-bottom: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 12px;">
                        <div>
                            <strong style="color: #ffffff; font-size: 0.92rem; display: block;">Linhas de Informações & Especificações da Tabela</strong>
                            <span style="font-size: 0.78rem; color: #8e8880;">Use as setas ↑ e ↓ para reordenar a exibição. Edite títulos e valores livremente.</span>
                        </div>
                        <button type="button" class="btn-admin-secondary" onclick="addCustomSpecRow()" style="font-size: 0.78rem; padding: 7px 14px; display: inline-flex; align-items: center; gap: 6px; border-color: rgba(212, 186, 136, 0.4); color: #ded7cf;">
                            <span>➕ Adicionar Nova Linha Personalizada</span>
                        </button>
                    </div>

                    <!-- Container Onde Ficam as Linhas Interativas -->
                    <div id="specsRowsContainer" style="display: flex; flex-direction: column; gap: 10px;">
                        <?php 
                        $specsList = Setting::getProductSpecsList();
                        foreach ($specsList as $idx => $sp):
                            $id = $sp['id'] ?? '';
                            $type = $sp['type'] ?? 'custom';
                            $title = $sp['title'] ?? '';
                            $desc = $sp['desc'] ?? '';
                            $isVis = !empty($sp['visible']);
                            $isCustom = !empty($sp['is_custom']);

                            // Badge de identificação
                            $typeLabel = 'Campo Personalizado';
                            if ($id === 'material') $typeLabel = 'Gema / Material (Do Produto)';
                            elseif ($id === 'acabamento') $typeLabel = 'Acabamento Nobre (Do Produto)';
                            elseif ($id === 'design') $typeLabel = '✦ Identidade Autoral DIVINIUM';
                            elseif ($id === 'dimensoes') $typeLabel = 'Medidas & Dimensões (Do Produto)';
                            elseif ($id === 'cores') $typeLabel = 'Tonalidade & Nuances (Do Produto)';
                            elseif ($id === 'autenticidade') $typeLabel = 'Certificado & Garantia Legal';
                            elseif ($id === 'sku') $typeLabel = 'Código SKU & Referência';
                            elseif ($id === 'disponibilidade') $typeLabel = 'Status de Envio & Estoque';

                            // Placeholders e dicas
                            $placeholder = 'Texto descritivo exibido publicamente...';
                            $hint = 'Conteúdo exibido ao cliente nesta linha da ficha técnica.';
                            if ($id === 'material') {
                                $placeholder = 'Deixe vazio para usar a pedra cadastrada na joia (ex: Concha Verde)';
                                $hint = 'Se vazio, exibe automaticamente a gema/mineral da peça. Se preencher, usa este texto fixo.';
                            } elseif ($id === 'acabamento') {
                                $placeholder = 'Deixe vazio para usar o material cadastrado (ex: Banho de Prata)';
                                $hint = 'Se vazio, exibe o acabamento nobre cadastrado no produto. Se preencher, usa este texto fixo.';
                            } elseif ($id === 'design') {
                                $placeholder = 'Ex: Design Autoral Exclusivo DIVINIUM • Linhas Anatômicas Contemporâneas';
                                $hint = 'Destaque a manufatura e exclusividade autoral da DIVINIUM Joias.';
                            } elseif ($id === 'dimensoes') {
                                $placeholder = 'Deixe vazio para usar as medidas cadastradas na joia';
                                $hint = 'Se vazio, exibe as dimensões do catálogo (ex: Aro regulável 14 ao 22).';
                            } elseif ($id === 'cores') {
                                $placeholder = 'Deixe vazio para usar o acabamento/lapidação do catálogo';
                                $hint = 'Exibe detalhes de polimento artesanal e nuances do acabamento.';
                            } elseif ($id === 'autenticidade') {
                                $placeholder = 'Certificado de Banho de Prata com Garantia Vitalícia';
                                $hint = 'Certificação oficial que acompanha a joia e atesta sua nobreza.';
                            } elseif ($id === 'sku') {
                                $placeholder = 'Dinâmico: exibe #código do SKU do produto';
                                $hint = 'Exibe o código SKU cadastrado. Pode ser ocultado individualmente nesta tabela.';
                            } elseif ($id === 'disponibilidade') {
                                $placeholder = 'Deixe vazio para usar o status automático de pronta entrega / encomenda';
                                $hint = 'Mostra a disponibilidade imediata ou encomenda prioritária da joia.';
                            }
                        ?>
                        <div class="spec-row-item <?= !$isVis ? 'is-dimmed' : '' ?>"
                             data-id="<?= View::e($id) ?>"
                             data-type="<?= View::e($type) ?>"
                             data-custom="<?= $isCustom ? '1' : '0' ?>"
                             style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.07); border-radius: 10px; padding: 14px 16px; transition: all 0.25s ease;">
                             
                            <!-- Barra Superior do Card -->
                            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span class="spec-order-badge" style="background: rgba(212,186,136,0.15); color: #ded7cf; border: 1px solid rgba(212,186,136,0.3); font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 6px;">
                                        Linha #<?= $idx + 1 ?>
                                    </span>
                                    <span class="spec-type-badge" style="font-size: 0.72rem; color: #8e8880;">
                                        <?= View::e($typeLabel) ?>
                                    </span>
                                </div>

                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <!-- Botões Reordenar -->
                                    <button type="button" class="btn-order-move btn-move-up" onclick="moveSpecRow(this, -1)" title="Mover Linha para Cima" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); color: #CBD0D6; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: bold;">
                                        ↑
                                    </button>
                                    <button type="button" class="btn-order-move btn-move-down" onclick="moveSpecRow(this, 1)" title="Mover Linha para Baixo" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); color: #CBD0D6; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: bold;">
                                        ↓
                                    </button>

                                    <!-- Switch Visibilidade -->
                                    <label class="element-toggle-switch <?= $isVis ? 'is-visible' : 'is-hidden' ?>" style="margin: 0; padding: 3px 8px; font-size: 0.72rem;">
                                        <input type="checkbox" class="spec-vis-checkbox" <?= $isVis ? 'checked' : '' ?> onchange="toggleSpecRowVis(this)">
                                        <span class="toggle-slider"></span>
                                        <span class="toggle-text"><?= $isVis ? 'Visível' : 'Oculto' ?></span>
                                    </label>

                                    <!-- Excluir se for custom -->
                                    <?php if ($isCustom): ?>
                                    <button type="button" onclick="deleteSpecRow(this)" title="Excluir Linha Personalizada" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.35); color: #f87171; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; margin-left: 2px;">
                                        ✕
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Campos de Edição Livre -->
                            <div style="display: grid; grid-template-columns: 1fr 1.8fr; gap: 14px;">
                                <div>
                                    <label style="display: block; font-size: 0.78rem; color: #CBD0D6; margin-bottom: 4px; font-weight: 600;">
                                        Título da Linha (Rótulo Público)
                                    </label>
                                    <input type="text" class="form-input spec-row-title-input" value="<?= View::e($title) ?>" placeholder="Ex: Material, Acabamento, Design...">
                                </div>
                                <div>
                                    <label style="display: block; font-size: 0.78rem; color: #CBD0D6; margin-bottom: 4px; font-weight: 600;">
                                        Conteúdo / Descrição Exibida
                                    </label>
                                    <input type="text" class="form-input spec-row-desc-input" value="<?= View::e($desc) ?>" placeholder="<?= View::e($placeholder) ?>">
                                    <span class="field-hint" style="font-size: 0.7rem; color: #8e8880; margin-top: 3px; display: block;">
                                        <?= View::e($hint) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Hidden que guarda o JSON serializado -->
                    <input type="hidden" id="store_specs_items_json" name="store_specs_items_json" value="<?= View::e(json_encode($specsList, JSON_UNESCAPED_UNICODE)) ?>">

                    <!-- Contador e Status -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 14px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.06);">
                        <span id="specsRowsCounter" style="font-size: 0.75rem; color: #8e8880;"></span>
                        <button type="button" class="btn-admin-secondary" onclick="addCustomSpecRow()" style="font-size: 0.78rem; padding: 7px 14px; display: inline-flex; align-items: center; gap: 6px; border-color: rgba(212, 186, 136, 0.4); color: #ded7cf;">
                            <span>➕ Adicionar Nova Linha Personalizada</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 6: BOTÕES, AÇÕES & CANAIS DE COMPRA -->
        <!-- ============================================================== -->
        <div class="editor-section-card active" id="sec-buttons" style="margin-bottom: 24px;">
            <div class="section-card-header">
                <div class="section-icon">🔘</div>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                        <h3 style="margin: 0;">6. Botões, Ações & Canais de Compra</h3>
                        <span style="background: rgba(255,255,255,0.06); color: #CBD0D6; font-size: 0.72rem; padding: 3px 8px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.1);">
                            📍 Botões de CTA nos Cards e Página do Produto
                        </span>
                    </div>
                    <p>Controle os textos dos botões de compra, links para WhatsApp e botões de compartilhamento.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="store_card_btn_text">Texto do Botão no Card da Vitrine</label>
                    <input type="text" id="store_card_btn_text" name="store_card_btn_text" class="form-input" 
                           value="<?= View::e(Setting::get('store_card_btn_text', 'Conhecer a Peça')) ?>" placeholder="Conhecer a Peça">
                    <span class="field-hint">Texto do botão elegante exibido no card de cada joia na boutique.</span>
                </div>

                <div class="form-group <?= Setting::get('store_detail_show_share', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_detail_share_btn_text">Botão de Compartilhamento da Joia</label>
                        <?= $renderToggle('store_detail_show_share', '1') ?>
                    </div>
                    <input type="text" id="store_detail_share_btn_text" name="store_detail_share_btn_text" class="form-input" 
                           value="<?= View::e(Setting::get('store_detail_share_btn_text', 'Compartilhar Joia')) ?>" placeholder="Compartilhar Joia">
                    <span class="field-hint">Texto do botão que copia o link próprio da joia para a área de transferência.</span>
                </div>

                <div class="form-group full-width <?= Setting::get('store_detail_channels_title_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_detail_channels_title">Título da Seção de Compra (/produto/{slug})</label>
                        <?= $renderToggle('store_detail_channels_title_visible', '1') ?>
                    </div>
                    <input type="text" id="store_detail_channels_title" name="store_detail_channels_title" class="form-input" 
                           value="<?= View::e(Setting::get('store_detail_channels_title', 'Escolha onde deseja adquirir sua joia:')) ?>">
                    <span class="field-hint">Chamada principal exibida antes dos botões de compra dos canais oficiais.</span>
                </div>

                <div class="form-group full-width <?= Setting::get('store_detail_channels_subtitle_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_detail_channels_subtitle">Subtítulo Informativo de Confiança</label>
                        <?= $renderToggle('store_detail_channels_subtitle_visible', '1') ?>
                    </div>
                    <textarea id="store_detail_channels_subtitle" name="store_detail_channels_subtitle" class="form-textarea" rows="2"><?= View::e(Setting::get('store_detail_channels_subtitle', 'Selecione seu canal oficial de preferência. Todos contam com garantia de autenticidade, embalagem exclusiva e compra 100% protegida.')) ?></textarea>
                </div>

                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px;">
                    <div class="field-label-row">
                        <label style="color: #CBD0D6; font-size: 0.9rem; font-weight: 600;">Selo Inferior de Garantia & Excelência DIVINIUM</label>
                        <?= $renderToggle('store_detail_show_guarantee', '1') ?>
                    </div>
                    <span class="field-hint">Card de rodapé com escudo de autenticidade e estojo de veludo.</span>
                </div>

                <div class="form-group <?= Setting::get('store_detail_show_guarantee', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="store_detail_guarantee_title">Título da Garantia</label>
                    <input type="text" id="store_detail_guarantee_title" name="store_detail_guarantee_title" class="form-input" 
                           value="<?= View::e(Setting::get('store_detail_guarantee_title', 'Autenticidade & Garantia DIVINIUM')) ?>">
                </div>

                <div class="form-group <?= Setting::get('store_detail_show_guarantee', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="store_detail_guarantee_text">Texto Explicativo da Garantia</label>
                    <input type="text" id="store_detail_guarantee_text" name="store_detail_guarantee_text" class="form-input" 
                           value="<?= View::e(Setting::get('store_detail_guarantee_text', 'Cada joia é acompanhada de certificado numerado de banho de prata, estojo de presente aveludado e garantia vitalícia contra defeitos no acabamento.')) ?>">
                </div>
            </div>
        </div>

        <!-- BARRA FIXA / FLUTUANTE DE SALVAMENTO -->
        <div style="position: sticky; bottom: 20px; z-index: 100; background: rgba(18, 17, 16, 0.95); backdrop-filter: blur(12px); border: 1px solid rgba(212, 186, 136, 0.3); border-radius: 12px; padding: 14px 24px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            <div style="font-size: 0.85rem; color: #ded7cf; display: flex; align-items: center; gap: 8px;">
                <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #4ADE80;"></span>
                <span>Alterações salvas aqui se aplicam a todas as páginas e publicações dos produtos.</span>
            </div>
            <div style="display: flex; gap: 12px;">
                <a href="/loja" target="_blank" class="btn-admin-secondary" style="padding: 8px 16px;">
                    Ver Loja ↗
                </a>
                <button type="submit" class="btn-admin-gold" id="btnSaveBottom" style="padding: 10px 24px; font-weight: 700;">
                    Salvar Alterações
                </button>
            </div>
        </div>

    </form>
</div>

<!-- ESTILOS E SCRIPT DE MANIPULAÇÃO DINÂMICA DA CENTRAL DE EXIBIÇÃO -->
<style>
.spec-row-item.is-dimmed {
    opacity: 0.55;
    background: rgba(0, 0, 0, 0.25) !important;
    border-color: rgba(255, 255, 255, 0.04) !important;
}
.spec-row-item.is-dimmed:hover {
    opacity: 0.85;
}
.spec-row-item:hover {
    border-color: rgba(212, 186, 136, 0.35) !important;
}
.btn-order-move:hover:not(:disabled) {
    background: rgba(212, 186, 136, 0.2) !important;
    border-color: #d4ba88 !important;
    color: #ffffff !important;
}
@keyframes fadeInRow {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}
@media (max-width: 640px) {
    .spec-row-item > div:last-child {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
function handleToggleSwitch(checkbox) {
    const label = checkbox.closest('.element-toggle-switch');
    const textSpan = label ? label.querySelector('.toggle-text') : null;
    const formGroup = checkbox.closest('.form-group');

    if (checkbox.checked) {
        if (label) {
            label.classList.remove('is-hidden');
            label.classList.add('is-visible');
        }
        if (textSpan) textSpan.innerText = 'Exibindo';
        if (formGroup) formGroup.classList.remove('is-dimmed');
    } else {
        if (label) {
            label.classList.remove('is-visible');
            label.classList.add('is-hidden');
        }
        if (textSpan) textSpan.innerText = 'Oculto';
        if (formGroup && !formGroup.classList.contains('full-width')) {
            formGroup.classList.add('is-dimmed');
        }
    }
}

function scrollToSection(sectionId, btn) {
    document.querySelectorAll('.editor-nav-tabs .tab-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    const elem = document.getElementById(sectionId);
    if (elem) {
        elem.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

/* ==============================================================
   GERENCIADOR DINÂMICO DE ESPECIFICAÇÕES & MANUFATURA
   ============================================================== */
function toggleSpecRowVis(checkbox) {
    const row = checkbox.closest('.spec-row-item');
    const label = checkbox.closest('.element-toggle-switch');
    const textSpan = label ? label.querySelector('.toggle-text') : null;

    if (checkbox.checked) {
        if (label) {
            label.classList.remove('is-hidden');
            label.classList.add('is-visible');
        }
        if (textSpan) textSpan.innerText = 'Visível';
        if (row) row.classList.remove('is-dimmed');
    } else {
        if (label) {
            label.classList.remove('is-visible');
            label.classList.add('is-hidden');
        }
        if (textSpan) textSpan.innerText = 'Oculto';
        if (row) row.classList.add('is-dimmed');
    }

    serializeSpecsRows();
    updateSpecsOrderBadges();
}

function moveSpecRow(btn, dir) {
    const row = btn.closest('.spec-row-item');
    if (!row) return;

    if (dir === -1) {
        const prev = row.previousElementSibling;
        if (prev && prev.classList.contains('spec-row-item')) {
            row.parentNode.insertBefore(row, prev);
            highlightRow(row);
        }
    } else if (dir === 1) {
        const next = row.nextElementSibling;
        if (next && next.classList.contains('spec-row-item')) {
            row.parentNode.insertBefore(next, row);
            highlightRow(row);
        }
    }

    updateSpecsOrderBadges();
    serializeSpecsRows();
}

function highlightRow(row) {
    row.style.borderColor = 'rgba(212, 186, 136, 0.8)';
    row.style.boxShadow = '0 0 15px rgba(212, 186, 136, 0.25)';
    setTimeout(() => {
        row.style.borderColor = '';
        row.style.boxShadow = '';
    }, 600);
}

function deleteSpecRow(btn) {
    const row = btn.closest('.spec-row-item');
    if (!row) return;

    const title = row.querySelector('.spec-row-title-input')?.value.trim() || 'esta linha';
    if (confirm(`Tem certeza que deseja excluir a linha "${title}" da ficha técnica?`)) {
        row.style.opacity = '0';
        row.style.transform = 'scale(0.95)';
        setTimeout(() => {
            row.remove();
            updateSpecsOrderBadges();
            serializeSpecsRows();
            if (typeof showAdminToast === 'function') {
                showAdminToast('Linha personalizada removida.', 'info');
            }
        }, 200);
    }
}

function addCustomSpecRow() {
    const container = document.getElementById('specsRowsContainer');
    if (!container) return;

    const rowId = 'custom_' + Date.now();
    const newRow = document.createElement('div');
    newRow.className = 'spec-row-item';
    newRow.setAttribute('data-id', rowId);
    newRow.setAttribute('data-type', 'custom');
    newRow.setAttribute('data-custom', '1');
    newRow.style.cssText = 'background: rgba(255,255,255,0.02); border: 1px solid rgba(212,186,136,0.3); border-radius: 10px; padding: 14px 16px; transition: all 0.25s ease; animation: fadeInRow 0.3s ease;';

    newRow.innerHTML = `
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid rgba(255,255,255,0.05);">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="spec-order-badge" style="background: rgba(212,186,136,0.15); color: #ded7cf; border: 1px solid rgba(212,186,136,0.3); font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 6px;">
                    Linha #
                </span>
                <span class="spec-type-badge" style="font-size: 0.72rem; color: #ded7cf; background: rgba(212,186,136,0.1); padding: 2px 6px; border-radius: 4px;">
                    ✦ Nova Linha Personalizada
                </span>
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
                <button type="button" class="btn-order-move btn-move-up" onclick="moveSpecRow(this, -1)" title="Mover Linha para Cima" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); color: #CBD0D6; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: bold;">
                    ↑
                </button>
                <button type="button" class="btn-order-move btn-move-down" onclick="moveSpecRow(this, 1)" title="Mover Linha para Baixo" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); color: #CBD0D6; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: bold;">
                    ↓
                </button>

                <label class="element-toggle-switch is-visible" style="margin: 0; padding: 3px 8px; font-size: 0.72rem;">
                    <input type="checkbox" class="spec-vis-checkbox" checked onchange="toggleSpecRowVis(this)">
                    <span class="toggle-slider"></span>
                    <span class="toggle-text">Visível</span>
                </label>

                <button type="button" onclick="deleteSpecRow(this)" title="Excluir Linha Personalizada" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.35); color: #f87171; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; margin-left: 2px;">
                    ✕
                </button>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1.8fr; gap: 14px;">
            <div>
                <label style="display: block; font-size: 0.78rem; color: #CBD0D6; margin-bottom: 4px; font-weight: 600;">
                    Título da Linha (Rótulo Público)
                </label>
                <input type="text" class="form-input spec-row-title-input" value="" placeholder="Ex: Procedência, Cuidados, Coleção..." oninput="serializeSpecsRows()">
            </div>
            <div>
                <label style="display: block; font-size: 0.78rem; color: #CBD0D6; margin-bottom: 4px; font-weight: 600;">
                    Conteúdo / Descrição Exibida
                </label>
                <input type="text" class="form-input spec-row-desc-input" value="" placeholder="Digite a informação detalhada exibida na ficha técnica" oninput="serializeSpecsRows()">
                <span class="field-hint" style="font-size: 0.7rem; color: #8e8880; margin-top: 3px; display: block;">
                    Informação adicional exibida na tabela pública do produto.
                </span>
            </div>
        </div>
    `;

    container.appendChild(newRow);

    updateSpecsOrderBadges();
    serializeSpecsRows();

    const titleInput = newRow.querySelector('.spec-row-title-input');
    if (titleInput) {
        titleInput.focus();
    }
}

function updateSpecsOrderBadges() {
    const container = document.getElementById('specsRowsContainer');
    if (!container) return;

    const rows = container.querySelectorAll('.spec-row-item');
    let visibleCount = 0;
    let hiddenCount = 0;

    rows.forEach((row, idx) => {
        const badge = row.querySelector('.spec-order-badge');
        if (badge) {
            badge.innerText = `Linha #${idx + 1}`;
        }

        const btnUp = row.querySelector('.btn-move-up');
        const btnDown = row.querySelector('.btn-move-down');
        if (btnUp) {
            btnUp.disabled = (idx === 0);
            btnUp.style.opacity = (idx === 0) ? '0.3' : '1';
            btnUp.style.cursor = (idx === 0) ? 'not-allowed' : 'pointer';
        }
        if (btnDown) {
            btnDown.disabled = (idx === rows.length - 1);
            btnDown.style.opacity = (idx === rows.length - 1) ? '0.3' : '1';
            btnDown.style.cursor = (idx === rows.length - 1) ? 'not-allowed' : 'pointer';
        }

        const chk = row.querySelector('.spec-vis-checkbox');
        if (chk && chk.checked) {
            visibleCount++;
            row.classList.remove('is-dimmed');
        } else {
            hiddenCount++;
            row.classList.add('is-dimmed');
        }
    });

    const counter = document.getElementById('specsRowsCounter');
    if (counter) {
        counter.innerHTML = `<strong>${rows.length}</strong> campos configurados (<span style="color: #4ADE80;">${visibleCount} visíveis</span>, <span style="color: #f87171;">${hiddenCount} ocultos</span>)`;
    }
}

function serializeSpecsRows() {
    const container = document.getElementById('specsRowsContainer');
    const hiddenInput = document.getElementById('store_specs_items_json');
    if (!container || !hiddenInput) return;

    const rows = container.querySelectorAll('.spec-row-item');
    const items = [];

    rows.forEach(row => {
        const id = row.getAttribute('data-id') || '';
        const type = row.getAttribute('data-type') || 'custom';
        const isCustom = row.getAttribute('data-custom') === '1';
        const titleInput = row.querySelector('.spec-row-title-input');
        const descInput = row.querySelector('.spec-row-desc-input');
        const visCheckbox = row.querySelector('.spec-vis-checkbox');

        items.push({
            id: id,
            type: type,
            title: titleInput ? titleInput.value.trim() : '',
            desc: descInput ? descInput.value.trim() : '',
            visible: (visCheckbox && visCheckbox.checked) ? 1 : 0,
            is_custom: isCustom ? 1 : 0
        });
    });

    hiddenInput.value = JSON.stringify(items);
}

document.addEventListener('DOMContentLoaded', function() {
    updateSpecsOrderBadges();
    serializeSpecsRows();

    const container = document.getElementById('specsRowsContainer');
    if (container) {
        container.addEventListener('input', function(e) {
            if (e.target.classList.contains('spec-row-title-input') || e.target.classList.contains('spec-row-desc-input')) {
                serializeSpecsRows();
            }
        });
    }
});

async function handleDisplaySubmit(e) {
    e.preventDefault();
    serializeSpecsRows(); // Garante a serialização mais atualizada da lista de especificações

    const form = e.target;
    const btn = document.getElementById('btnSaveBottom');
    const originalText = btn ? btn.innerHTML : 'Salvar Alterações';

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span>Salvando...</span>';
    }

    try {
        const formData = new FormData(form);
        const resp = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await resp.json();

        if (data && data.success) {
            if (typeof showAdminToast === 'function') {
                showAdminToast(data.message || 'Informações e exibição dos produtos salvas com sucesso!', 'success');
            } else {
                alert(data.message || 'Informações e exibição dos produtos salvas com sucesso!');
            }
        } else {
            const err = (data && data.error) ? data.error : 'Erro ao salvar alterações.';
            if (typeof showAdminToast === 'function') {
                showAdminToast(err, 'error');
            } else {
                alert(err);
            }
        }
    } catch (err) {
        console.error('Erro ao salvar:', err);
        alert('Erro ao conectar com o servidor. Verifique a conexão.');
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }
}
</script>
