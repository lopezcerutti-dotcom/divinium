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
            <span>🔘 Escolha Onde Adquirir & Canais</span>
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
        <!-- SEÇÃO 6: ESCOLHA ONDE ADQUIRIR SUA JOIA & CANAIS DE COMPRA -->
        <!-- ============================================================== -->
        <div class="editor-section-card active" id="sec-buttons" style="margin-bottom: 24px;">
            <div class="section-card-header">
                <div class="section-icon">🔘</div>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                        <h3 style="margin: 0;">6. Escolha Onde Deseja Adquirir & Canais Oficiais</h3>
                        <div style="display: flex; gap: 6px; align-items: center;">
                            <span style="background: rgba(212,186,136,0.15); color: #ded7cf; font-size: 0.72rem; padding: 3px 8px; border-radius: 4px; border: 1px solid rgba(212,186,136,0.3); font-weight: 600;">
                                ✦ GRUPO DO PRODUTO (COMPARTILHADO)
                            </span>
                        </div>
                    </div>
                    <p>Centralize e edite todas as informações do bloco “Escolha onde deseja adquirir sua joia”. As configurações aqui definidas pertencem ao grupo do produto e são compartilhadas automaticamente por todas as suas variações, preservando valores individuais quando houver.</p>
                </div>
            </div>

            <!-- AVISO DE COMPARTILHAMENTO INTELIGENTE POR GRUPO -->
            <div style="background: rgba(212,186,136,0.06); border: 1px solid rgba(212,186,136,0.2); border-radius: 8px; padding: 12px 16px; margin: 0 0 20px 0; display: flex; align-items: center; gap: 12px;">
                <span style="font-size: 1.2rem;">✨</span>
                <span style="font-size: 0.8rem; color: #ded7cf; line-height: 1.45;">
                    <strong>Herança Automática:</strong> Título, introdução, benefícios, selos, informações de confiança e a ordem dos canais aplicam-se a todas as variações da joia. Quando preço, estoque ou link de marketplace dependerem de uma variação específica, o site utiliza com total precisão o dado da variação selecionada.
                </span>
            </div>

            <!-- ========================================================== -->
            <!-- CATEGORIA 1: TÍTULO PRINCIPAL & TEXTO INTRODUTÓRIO -->
            <!-- ========================================================== -->
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 18px 20px; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.06);">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 1.1rem;">💎</span>
                        <div>
                            <h4 style="margin: 0; font-size: 0.95rem; color: #ffffff;">Categoria 1: Título Principal & Texto Introdutório</h4>
                            <span style="font-size: 0.73rem; color: #8e8880;">Chamada e mensagem de proteção exibidas no topo do bloco de compra (/produto/{slug})</span>
                        </div>
                    </div>
                    <?= $renderToggle('store_detail_show_channels', '1') ?>
                </div>

                <div class="form-grid">
                    <div class="form-group full-width <?= Setting::get('store_detail_channels_title_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                        <div class="field-label-row">
                            <label for="store_detail_channels_title">Título Principal do Bloco</label>
                            <?= $renderToggle('store_detail_channels_title_visible', '1') ?>
                        </div>
                        <input type="text" id="store_detail_channels_title" name="store_detail_channels_title" class="form-input" 
                               value="<?= View::e(Setting::get('store_detail_channels_title', 'Escolha onde deseja adquirir sua joia:')) ?>" 
                               placeholder="Escolha onde deseja adquirir sua joia:">
                        <span class="field-hint">Título principal com estética Quiet Luxury exibido no topo do bloco de canais.</span>
                    </div>

                    <div class="form-group full-width <?= Setting::get('store_detail_channels_subtitle_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                        <div class="field-label-row">
                            <label for="store_detail_channels_subtitle">Texto Introdutório de Proteção & Segurança</label>
                            <?= $renderToggle('store_detail_channels_subtitle_visible', '1') ?>
                        </div>
                        <textarea id="store_detail_channels_subtitle" name="store_detail_channels_subtitle" class="form-textarea" rows="2"
                                  placeholder="Selecione seu canal oficial de preferência. Compra 100% protegida."><?= View::e(Setting::get('store_detail_channels_subtitle', 'Selecione seu canal oficial de preferência. Compra 100% protegida com garantia de autenticidade e embalagem exclusiva.')) ?></textarea>
                        <span class="field-hint">Texto informativo e elegante que assegura a procedência e proteção ao cliente.</span>
                    </div>
                </div>
            </div>

            <!-- ========================================================== -->
            <!-- CATEGORIA 2: CANAL DE COMPRA DIRETA DIVINIUM -->
            <!-- ========================================================== -->
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 18px 20px; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.06);">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 1.1rem;">🏛️</span>
                        <div>
                            <h4 style="margin: 0; font-size: 0.95rem; color: #ffffff;">Categoria 2: Canal de Compra Direta DIVINIUM</h4>
                            <span style="font-size: 0.73rem; color: #8e8880;">Opção prioritária com benefício exclusivo no Pix e envio direto da boutique</span>
                        </div>
                    </div>
                    <?= $renderToggle('store_direct_channel_visible', '1') ?>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="store_direct_channel_title">Nome do Botão / Título de Ação</label>
                        <input type="text" id="store_direct_channel_title" name="store_direct_channel_title" class="form-input" 
                               value="<?= View::e(Setting::get('store_direct_channel_title', 'Comprar Direto na DIVINIUM')) ?>" placeholder="Comprar Direto na DIVINIUM">
                        <span class="field-hint">Texto de destaque no botão oficial próprio da joalheria.</span>
                    </div>

                    <div class="form-group">
                        <label for="store_direct_channel_subtitle">Descrição / Subtítulo Promocional</label>
                        <input type="text" id="store_direct_channel_subtitle" name="store_direct_channel_subtitle" class="form-input" 
                               value="<?= View::e(Setting::get('store_direct_channel_subtitle', '5% OFF no Pix • Envio com Seguro Blindado')) ?>" placeholder="5% OFF no Pix • Envio com Seguro Blindado">
                        <span class="field-hint">Frase de benefício exibida logo abaixo do título do botão.</span>
                    </div>

                    <div class="form-group">
                        <label for="store_direct_pix_discount_pct">Desconto no Pix (%)</label>
                        <input type="number" step="0.5" min="0" max="50" id="store_direct_pix_discount_pct" name="store_direct_pix_discount_pct" class="form-input" 
                               value="<?= View::e(Setting::get('store_direct_pix_discount_pct', '5')) ?>" placeholder="5">
                        <span class="field-hint">Percentual deduzido dinamicamente sobre o valor da variação selecionada.</span>
                    </div>

                    <div class="form-group">
                        <label for="store_direct_pix_label">Rótulo da Condição no Pix</label>
                        <input type="text" id="store_direct_pix_label" name="store_direct_pix_label" class="form-input" 
                               value="<?= View::e(Setting::get('store_direct_pix_label', 'Pix (-5%)')) ?>" placeholder="Pix (-5%)">
                        <span class="field-hint">Texto da etiqueta que acompanha o valor com desconto.</span>
                    </div>

                    <div class="form-group">
                        <label for="store_direct_shipping_info">Informações de Envio & Segurança</label>
                        <input type="text" id="store_direct_shipping_info" name="store_direct_shipping_info" class="form-input" 
                               value="<?= View::e(Setting::get('store_direct_shipping_info', 'Envio com Seguro Blindado')) ?>" placeholder="Envio com Seguro Blindado">
                        <span class="field-hint">Garantia logística informada na finalização do pedido.</span>
                    </div>

                    <div class="form-group">
                        <label for="store_direct_price_override">Preço Fixo Exclusivo (Opcional R$)</label>
                        <input type="text" id="store_direct_price_override" name="store_direct_price_override" class="form-input" 
                               value="<?= View::e(Setting::get('store_direct_price_override', '')) ?>" placeholder="Deixe vazio para usar preço dinâmico">
                        <span class="field-hint">Se preenchido, fixa o valor. Se vazio, calcula automaticamente pela variação.</span>
                    </div>

                    <div class="form-group">
                        <label for="store_direct_url">Link de Compra / Checkout</label>
                        <input type="text" id="store_direct_url" name="store_direct_url" class="form-input" 
                               value="<?= View::e(Setting::get('store_direct_url', '/checkout')) ?>" placeholder="/checkout">
                        <span class="field-hint">Destino da ação de compra direta (/checkout ou link personalizado).</span>
                    </div>

                    <div class="form-group">
                        <label for="store_direct_badge">Etiqueta / Badge Visual</label>
                        <input type="text" id="store_direct_badge" name="store_direct_badge" class="form-input" 
                               value="<?= View::e(Setting::get('store_direct_badge', 'Exclusivo')) ?>" placeholder="Ex: Exclusivo, ✦ Oficial">
                        <span class="field-hint">Selo decorativo ao lado do nome do botão.</span>
                    </div>

                    <div class="form-group">
                        <label for="store_direct_icon">Ícone do Botão Direto</label>
                        <?php $curDirectIcon = Setting::get('store_direct_icon', 'diamond'); ?>
                        <select id="store_direct_icon" name="store_direct_icon" class="form-select">
                            <option value="diamond" <?= ($curDirectIcon === 'diamond' || $curDirectIcon === '') ? 'selected' : '' ?>>💎 Diamante / Joia Preciosa</option>
                            <option value="oficial" <?= $curDirectIcon === 'oficial' ? 'selected' : '' ?>>✦ Selo Oficial DIVINIUM</option>
                            <option value="sacola" <?= $curDirectIcon === 'sacola' ? 'selected' : '' ?>>🛍️ Sacola de Joias</option>
                            <option value="coroa" <?= $curDirectIcon === 'coroa' ? 'selected' : '' ?>>👑 Coroa Real</option>
                            <option value="escudo" <?= $curDirectIcon === 'escudo' ? 'selected' : '' ?>>🛡️ Escudo de Segurança</option>
                        </select>
                        <span class="field-hint">Símbolo gráfico exibido na pílula do botão.</span>
                    </div>
                </div>
            </div>

            <!-- ========================================================== -->
            <!-- CATEGORIA 3: CANAIS EXTERNOS (MARKETPLACES & ATENDIMENTO) -->
            <!-- ========================================================== -->
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 18px 20px; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.06);">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 1.1rem;">🌐</span>
                        <div>
                            <h4 style="margin: 0; font-size: 0.95rem; color: #ffffff;">Categoria 3: Canais Externos, Marketplaces & Consultor VIP</h4>
                            <span style="font-size: 0.73rem; color: #8e8880;">Shopee, Mercado Livre, WhatsApp VIP e outros canais homologados. Adicione, reordene (↑/↓), oculte ou exclua livremente.</span>
                        </div>
                    </div>
                    <button type="button" class="btn-admin-secondary" onclick="addCustomChannelRow()" style="font-size: 0.78rem; padding: 6px 14px; display: inline-flex; align-items: center; gap: 6px; border-color: rgba(212, 186, 136, 0.4); color: #ded7cf;">
                        <span>➕ Adicionar Novo Canal de Venda</span>
                    </button>
                </div>

                <!-- Container dinâmico e reordenável de canais -->
                <div id="channelsRowsContainer">
                    <?php 
                    $channelsList = Setting::getProductChannelsList();
                    foreach ($channelsList as $cIdx => $cItem):
                        $cId = (string)($cItem['id'] ?? 'channel_' . $cIdx);
                        $cPlat = (string)($cItem['platform'] ?? 'Loja Oficial');
                        $cTitle = (string)($cItem['title'] ?? '');
                        $cOfficial = (string)($cItem['official_label'] ?? 'Canal Homologado DIVINIUM');
                        $cDesc = (string)($cItem['desc'] ?? '');
                        $cPrice = (string)($cItem['price_override'] ?? '');
                        $cBadge = (string)($cItem['badge'] ?? '');
                        $cIcon = (string)($cItem['icon'] ?? 'oficial');
                        $cUrl = (string)($cItem['url'] ?? '');
                        $cTarget = (string)($cItem['target'] ?? '_blank');
                        $cBgColor = (string)($cItem['button_bg_color'] ?? '');
                        $cTextColor = (string)($cItem['button_text_color'] ?? '');
                        $cVis = !empty($cItem['visible']);
                        $cIsCustom = !empty($cItem['is_custom']);
                        $cIsDeletable = !isset($cItem['is_deletable']) || !empty($cItem['is_deletable']);
                    ?>
                    <div class="channel-row-item <?= !$cVis ? 'is-dimmed' : '' ?>"
                         data-id="<?= View::e($cId) ?>"
                         data-type="<?= View::e((string)($cItem['type'] ?? 'external')) ?>"
                         data-custom="<?= $cIsCustom ? '1' : '0' ?>"
                         style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.07); border-radius: 10px; padding: 14px 16px; margin-bottom: 12px; transition: all 0.25s ease;">
                        
                        <!-- Barra Superior do Card do Canal -->
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span class="channel-order-badge" style="background: rgba(212,186,136,0.15); color: #ded7cf; border: 1px solid rgba(212,186,136,0.3); font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 6px;">
                                    Canal #<?= $cIdx + 1 ?>
                                </span>
                                <span class="channel-platform-preview" style="font-size: 0.75rem; color: #ffffff; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                    <span class="channel-logo-icon" style="display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; flex-shrink: 0; border-radius: 4px; overflow: hidden; background: #ffffff; padding: 2px; box-shadow: 0 1px 3px rgba(0,0,0,0.15);">
                                        <?= View::getPlatformLogoSvg($cPlat ?: $cIcon, 16) ?>
                                    </span>
                                    <span><?= View::e($cPlat) ?></span>
                                </span>
                                <?php if (!empty($cBadge)): ?>
                                    <span style="background: rgba(255,255,255,0.06); color: #CBD0D6; font-size: 0.68rem; padding: 1px 6px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.1);">
                                        <?= View::e($cBadge) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div style="display: flex; align-items: center; gap: 8px;">
                                <!-- Botões Reordenar -->
                                <button type="button" class="btn-order-move btn-channel-move-up" onclick="moveChannelRow(this, -1)" title="Mover Canal para Cima" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); color: #CBD0D6; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: bold;">
                                    ↑
                                </button>
                                <button type="button" class="btn-order-move btn-channel-move-down" onclick="moveChannelRow(this, 1)" title="Mover Canal para Baixo" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); color: #CBD0D6; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: bold;">
                                    ↓
                                </button>

                                <!-- Switch Visibilidade -->
                                <label class="element-toggle-switch <?= $cVis ? 'is-visible' : 'is-hidden' ?>" style="margin: 0; padding: 3px 8px; font-size: 0.72rem;">
                                    <input type="checkbox" class="channel-vis-checkbox" <?= $cVis ? 'checked' : '' ?> onchange="toggleChannelRowVis(this)">
                                    <span class="toggle-slider"></span>
                                    <span class="toggle-text"><?= $cVis ? 'Visível' : 'Oculto' ?></span>
                                </label>

                                <!-- Botão Excluir -->
                                <?php if ($cIsDeletable): ?>
                                <button type="button" onclick="deleteChannelRow(this)" title="Excluir este Canal de Venda" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.35); color: #f87171; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; margin-left: 2px;">
                                    ✕
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Linha 1 de Campos: Plataforma, Botão de Ação, Identificação Homologada -->
                        <div style="display: grid; grid-template-columns: 1fr 1.5fr 1fr; gap: 12px; margin-bottom: 10px;">
                            <div>
                                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Plataforma / Marketplace</label>
                                <input type="text" class="form-input channel-row-platform-input" value="<?= View::e($cPlat) ?>" placeholder="Ex: Shopee, Mercado Livre, Amazon..." oninput="updatePlatformPreview(this)">
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Nome do Botão / Ação</label>
                                <input type="text" class="form-input channel-row-title-input" value="<?= View::e($cTitle) ?>" placeholder="Ex: Comprar na Shopee Oficial">
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Identificação Oficial</label>
                                <input type="text" class="form-input channel-row-official-input" value="<?= View::e($cOfficial) ?>" placeholder="Ex: Canal Homologado DIVINIUM">
                            </div>
                        </div>

                        <!-- Linha 2 de Campos: Descrição, Preço Específico, Etiqueta, Ícone -->
                        <div style="display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 12px; margin-bottom: 10px;">
                            <div>
                                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Descrição / Vantagem</label>
                                <input type="text" class="form-input channel-row-desc-input" value="<?= View::e($cDesc) ?>" placeholder="Ex: Parcelamento sem juros e cupons do app">
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Preço Específico (R$)</label>
                                <input type="text" class="form-input channel-row-price-input" value="<?= View::e($cPrice) ?>" placeholder="Dinâmico da variação">
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Etiqueta / Badge</label>
                                <input type="text" class="form-input channel-row-badge-input" value="<?= View::e($cBadge) ?>" placeholder="Ex: Oficial, Envio Full">
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Ícone Visual</label>
                                <select class="form-select channel-row-icon-select" onchange="updateIconSelectPreview(this)" style="font-size: 0.8rem; height: 38px;">
                                    <option value="oficial" <?= ($cIcon === 'oficial' || $cIcon === 'divinium' || str_contains(strtolower($cPlat), 'oficial') || str_contains(strtolower($cPlat), 'divinium')) ? 'selected' : '' ?>>✦ Oficial DIVINIUM</option>
                                    <option value="shopee" <?= str_contains(strtolower($cIcon), 'shopee') ? 'selected' : '' ?>>Shopee</option>
                                    <option value="mercadolivre" <?= str_contains(strtolower($cIcon), 'mercado') ? 'selected' : '' ?>>Mercado Livre</option>
                                    <option value="whatsapp" <?= str_contains(strtolower($cIcon), 'whatsapp') ? 'selected' : '' ?>>WhatsApp VIP</option>
                                    <option value="amazon" <?= str_contains(strtolower($cIcon), 'amazon') ? 'selected' : '' ?>>Amazon</option>
                                    <option value="tiktok" <?= str_contains(strtolower($cIcon), 'tiktok') ? 'selected' : '' ?>>TikTok Shop</option>
                                    <option value="sacola" <?= $cIcon === 'sacola' ? 'selected' : '' ?>>Sacola de Compras</option>
                                </select>
                            </div>
                        </div>

                        <!-- Linha 3 de Campos: URL e Destino -->
                        <div style="display: grid; grid-template-columns: 3fr 1fr; gap: 12px;">
                            <div>
                                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">
                                    Link de Compra / URL Base do Canal
                                </label>
                                <input type="url" class="form-input channel-row-url-input" value="<?= View::e($cUrl) ?>" placeholder="https://...">
                                <span class="field-hint" style="font-size: 0.68rem; color: #8e8880; margin-top: 2px; display: block;">
                                    URL padrão do canal. Se uma variação tiver link externo próprio cadastrado, este será aplicado dinamicamente.
                                </span>
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Destino</label>
                                <select class="form-select channel-row-target-select" style="font-size: 0.8rem; height: 38px;">
                                    <option value="_blank" <?= $cTarget === '_blank' ? 'selected' : '' ?>>Nova Aba (_blank)</option>
                                    <option value="_self" <?= $cTarget === '_self' ? 'selected' : '' ?>>Mesma Aba (_self)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Linha 4 de Campos: Cores do Botão (Fundo e Letra) -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 10px; padding-top: 10px; border-top: 1px dashed rgba(255,255,255,0.08);">
                            <div>
                                <label style="display: block; font-size: 0.74rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">
                                    Cor de Fundo do Botão (Opcional)
                                </label>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <input type="color" value="<?= !empty($cBgColor) && str_starts_with($cBgColor, '#') && strlen($cBgColor) === 7 ? View::e($cBgColor) : '#202020' ?>" 
                                           onchange="this.nextElementSibling.value = this.value; serializeChannelsRows();" 
                                           title="Escolher cor de fundo"
                                           style="width: 34px; height: 32px; border-radius: 5px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer; padding: 1px;">
                                    <input type="text" class="form-input channel-row-bg-color-input" value="<?= View::e($cBgColor) ?>" placeholder="Padrão (Ex: #ded7cf ou #202020)" 
                                           oninput="if(this.value.startsWith('#') && this.value.length === 7) this.previousElementSibling.value = this.value; serializeChannelsRows();"
                                           style="font-size: 0.78rem; padding: 6px 8px; font-family: monospace; flex: 1;">
                                </div>
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.74rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">
                                    Cor da Letra / Texto do Botão (Opcional)
                                </label>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <input type="color" value="<?= !empty($cTextColor) && str_starts_with($cTextColor, '#') && strlen($cTextColor) === 7 ? View::e($cTextColor) : '#FFFFFF' ?>" 
                                           onchange="this.nextElementSibling.value = this.value; serializeChannelsRows();" 
                                           title="Escolher cor da letra"
                                           style="width: 34px; height: 32px; border-radius: 5px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer; padding: 1px;">
                                    <input type="text" class="form-input channel-row-text-color-input" value="<?= View::e($cTextColor) ?>" placeholder="Padrão (Ex: #FFFFFF ou #202020)" 
                                           oninput="if(this.value.startsWith('#') && this.value.length === 7) this.previousElementSibling.value = this.value; serializeChannelsRows();"
                                           style="font-size: 0.78rem; padding: 6px 8px; font-family: monospace; flex: 1;">
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Hidden com JSON serializado dos canais -->
                <input type="hidden" id="store_channels_items_json" name="store_channels_items_json" value="<?= View::e(json_encode($channelsList, JSON_UNESCAPED_UNICODE)) ?>">

                <!-- Contador e Rodapé dos Canais -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 14px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.06);">
                    <span id="channelsRowsCounter" style="font-size: 0.75rem; color: #8e8880;"></span>
                    <button type="button" class="btn-admin-secondary" onclick="addCustomChannelRow()" style="font-size: 0.78rem; padding: 6px 14px; display: inline-flex; align-items: center; gap: 6px; border-color: rgba(212, 186, 136, 0.4); color: #ded7cf;">
                        <span>➕ Adicionar Novo Canal de Venda</span>
                    </button>
                </div>
            </div>

            <!-- ========================================================== -->
            <!-- CATEGORIA 4: INFORMAÇÕES DE CONFIANÇA & SEGURANÇA DA COMPRA -->
            <!-- ========================================================== -->
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 18px 20px; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.06);">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 1.1rem;">🛡️</span>
                        <div>
                            <h4 style="margin: 0; font-size: 0.95rem; color: #ffffff;">Categoria 4: Informações de Confiança, Nota Fiscal & Garantia</h4>
                            <span style="font-size: 0.73rem; color: #8e8880;">Certificados de autenticidade, nota fiscal eletrônica, estojo exclusivo e garantia de fábrica</span>
                        </div>
                    </div>
                    <?= $renderToggle('store_detail_trust_visible', '1') ?>
                </div>

                <div class="form-grid">
                    <div class="form-group full-width <?= Setting::get('store_detail_trust_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                        <label for="store_detail_trust_text">Texto de Autenticidade & Nota Fiscal (Rodapé dos Canais)</label>
                        <input type="text" id="store_detail_trust_text" name="store_detail_trust_text" class="form-input" 
                               value="<?= View::e(Setting::get('store_detail_trust_text', 'Todos os canais listados possuem certificação de autenticidade, nota fiscal e garantia de fábrica DIVINIUM.')) ?>"
                               placeholder="Todos os canais listados possuem certificação de autenticidade, nota fiscal e garantia de fábrica DIVINIUM.">
                        <span class="field-hint">Exibido acompanhado do ícone de escudo de segurança logo abaixo da lista de canais de compra.</span>
                    </div>

                    <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.06); padding-top: 14px; margin-top: 8px;">
                        <div class="field-label-row">
                            <label style="color: #CBD0D6; font-size: 0.88rem; font-weight: 600;">Selo Inferior de Garantia & Excelência DIVINIUM</label>
                            <?= $renderToggle('store_detail_show_guarantee', '1') ?>
                        </div>
                        <span class="field-hint">Card de rodapé com estojo de veludo, certificado numerado de banho de prata e garantia.</span>
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

            <!-- ========================================================== -->
            <!-- CATEGORIA 5: ELEMENTOS VISUAIS & DISPOSIÇÃO DOS BOTÕES -->
            <!-- ========================================================== -->
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 18px 20px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.06);">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 1.1rem;">🎨</span>
                        <div>
                            <h4 style="margin: 0; font-size: 0.95rem; color: #ffffff;">Categoria 5: Elementos Visuais, Disposição & Botões Complementares</h4>
                            <span style="font-size: 0.73rem; color: #8e8880;">Formato visual dos botões, imparcialidade e botões de compartilhamento</span>
                        </div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="store_detail_button_layout">Disposição Visual dos Canais de Compra</label>
                        <?php $curLayout = Setting::get('store_detail_button_layout', 'list'); ?>
                        <select id="store_detail_button_layout" name="store_detail_button_layout" class="form-select">
                            <option value="list" <?= $curLayout === 'list' ? 'selected' : '' ?>>Lista Vertical Elegante (Recomendado)</option>
                            <option value="grid" <?= $curLayout === 'grid' ? 'selected' : '' ?>>Grade em 2 Colunas</option>
                        </select>
                        <span class="field-hint">Alterne entre layout vertical linear ou grade lado a lado para telas largas.</span>
                    </div>

                    <div class="form-group">
                        <div class="field-label-row">
                            <label for="store_detail_impartial_buttons">Imparcialidade dos Canais</label>
                            <?= $renderToggle('store_detail_impartial_buttons', '1') ?>
                        </div>
                        <input type="text" class="form-input" value="Padronização Quiet Luxury ativa" readonly style="opacity: 0.7; cursor: default;">
                        <span class="field-hint">Todos os canais recebem tratamento estético nobre, tipografia refinada e mesmo peso visual.</span>
                    </div>

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
                        <span class="field-hint">Texto do botão que copia o link próprio da joia com feedback visual.</span>
                    </div>
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
.spec-row-item.is-dimmed,
.channel-row-item.is-dimmed {
    opacity: 0.55;
    background: rgba(0, 0, 0, 0.25) !important;
    border-color: rgba(255, 255, 255, 0.04) !important;
}
.spec-row-item.is-dimmed:hover,
.channel-row-item.is-dimmed:hover {
    opacity: 0.85;
}
.spec-row-item:hover,
.channel-row-item:hover {
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
    .channel-row-item > div:nth-child(2),
    .channel-row-item > div:nth-child(3),
    .channel-row-item > div:nth-child(4) {
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

/* ==============================================================
   GERENCIADOR DINÂMICO DE CANAIS DE COMPRA & MARKETPLACES
   ============================================================== */
function toggleChannelRowVis(checkbox) {
    const row = checkbox.closest('.channel-row-item');
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

    serializeChannelsRows();
    updateChannelsOrderBadges();
}

function moveChannelRow(btn, dir) {
    const row = btn.closest('.channel-row-item');
    if (!row) return;

    if (dir === -1) {
        const prev = row.previousElementSibling;
        if (prev && prev.classList.contains('channel-row-item')) {
            row.parentNode.insertBefore(row, prev);
            highlightRow(row);
        }
    } else if (dir === 1) {
        const next = row.nextElementSibling;
        if (next && next.classList.contains('channel-row-item')) {
            row.parentNode.insertBefore(next, row);
            highlightRow(row);
        }
    }

    updateChannelsOrderBadges();
    serializeChannelsRows();
}

function deleteChannelRow(btn) {
    const row = btn.closest('.channel-row-item');
    if (!row) return;

    const plat = row.querySelector('.channel-row-platform-input')?.value.trim() || 'este canal';
    if (confirm(`Tem certeza que deseja remover o canal "${plat}" da lista?`)) {
        row.style.opacity = '0';
        row.style.transform = 'scale(0.95)';
        setTimeout(() => {
            row.remove();
            updateChannelsOrderBadges();
            serializeChannelsRows();
            if (typeof showAdminToast === 'function') {
                showAdminToast('Canal removido da lista.', 'info');
            }
        }, 200);
    }
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
        return `<svg class="channel-logo-svg" width="${s}" height="${s}" viewBox="0 0 260 292" fill="none" style="flex-shrink:0; vertical-align:middle;"><path fill="#00F2EA" d="M243.838 77.189V66.999c-12.529.019-24.812-3.488-35.442-10.12 9.41 10.297 21.801 17.397 35.442 20.31zM177.817 11.655c-.319-1.822-.564-3.656-.734-5.497V0h-48.182v191.228c-.077 22.29-18.177 40.341-40.501 40.341-6.554 0-12.742-1.555-18.222-4.318 7.401 9.707 19.087 15.973 32.241 15.973 22.32 0 40.424-18.049 40.502-40.342V11.655h34.896zM100.694 114.408v-10.848c-4.026-.55-8.085-.826-12.149-.824C39.642 102.735 0 142.356 0 191.228c0 30.64 15.58 57.643 39.255 73.527-15.615-15.953-25.236-37.789-25.236-61.874 0-48.249 38.634-87.481 86.675-88.473z"/><path fill="#FF004F" d="M191.102 105.182c18.814 13.442 41.862 21.351 66.755 21.351V78.656c-4.711.001-9.41-.49-14.019-1.466v37.686c-24.891 0-47.936-7.909-66.755-21.35v97.703c0 48.876-39.642 88.495-88.54 88.495-18.245 0-35.203-5.513-49.29-14.968 16.078 16.431 38.5 26.624 63.306 26.624 48.901 0 88.545-39.619 88.545-88.497v-97.701H191.102zM208.396 56.88c-9.615-10.499-15.928-24.067-17.294-39.067v-6.158h-13.285c3.344 19.065 14.75 35.353 30.579 45.225zM70.181 227.25c-5.372-7.04-8.275-15.652-8.262-24.507c0-22.354 18.132-40.479 40.502-40.479 4.169-.001 8.313.637 12.286 1.897v-48.947c-4.643-.636-9.329-.906-14.013-.807v38.098c-3.976-1.26-8.122-1.9-12.292-1.896-22.37 0-40.501 18.123-40.501 40.48 0 15.806 9.063 29.492 22.28 36.159z"/><path fill="#000000" d="M177.083 93.525c18.819 13.441 41.864 21.35 66.755 21.35V77.189c-13.894-2.958-26.194-10.215-35.442-20.309-15.83-9.873-27.235-26.161-30.579-45.225h-34.896v191.226c-.079 22.293-18.18 40.344-40.502 40.344-13.154 0-24.84-6.267-32.241-15.975-13.216-6.667-22.279-20.354-22.279-36.16 0-22.355 18.131-40.48 40.501-40.48 4.286 0 8.417.667 12.292 1.896v-38.098c-48.039.992-86.674 40.224-86.674 88.474 0 24.086 9.621 45.921 25.236 61.875 14.087 9.454 31.045 14.968 49.29 14.968 48.899 0 88.54-39.621 88.54-88.496V93.525h-15.25z"/></svg>`;
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
        return `<img src="/assets/images/brand/divinium-canal-oficial.png" alt="DIVINIUM" class="channel-logo-img" width="${s}" height="${s}" style="width:${s}px; height:${s}px; object-fit:contain; border-radius:3px; display:inline-block; vertical-align:middle; flex-shrink:0;">`;
    }
    return `<svg class="channel-logo-svg" width="${s}" height="${s}" viewBox="0 0 24 24" fill="none" style="flex-shrink:0; vertical-align:middle;"><rect width="24" height="24" rx="5" fill="#50555E"/><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round"/></svg>`;
}

function addCustomChannelRow() {
    const container = document.getElementById('channelsRowsContainer');
    if (!container) return;

    const rowId = 'channel_' + Date.now();
    const newRow = document.createElement('div');
    newRow.className = 'channel-row-item';
    newRow.setAttribute('data-id', rowId);
    newRow.setAttribute('data-type', 'external');
    newRow.setAttribute('data-custom', '1');
    newRow.style.cssText = 'background: rgba(255,255,255,0.02); border: 1px solid rgba(212,186,136,0.3); border-radius: 10px; padding: 14px 16px; margin-bottom: 12px; transition: all 0.25s ease; animation: fadeInRow 0.3s ease;';

    newRow.innerHTML = `
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid rgba(255,255,255,0.05);">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="channel-order-badge" style="background: rgba(212,186,136,0.15); color: #ded7cf; border: 1px solid rgba(212,186,136,0.3); font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 6px;">
                    Canal #
                </span>
                <span class="channel-platform-preview" style="font-size: 0.75rem; color: #ffffff; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                    <span class="channel-logo-icon" style="display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; flex-shrink: 0; border-radius: 4px; overflow: hidden; background: #ffffff; padding: 2px; box-shadow: 0 1px 3px rgba(0,0,0,0.15);">
                        ${getPlatformLogoSvg('Outro', 16)}
                    </span>
                    <span>✦ Novo Canal de Venda</span>
                </span>
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
                <button type="button" class="btn-order-move btn-channel-move-up" onclick="moveChannelRow(this, -1)" title="Mover Canal para Cima" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); color: #CBD0D6; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: bold;">
                    ↑
                </button>
                <button type="button" class="btn-order-move btn-channel-move-down" onclick="moveChannelRow(this, 1)" title="Mover Canal para Baixo" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); color: #CBD0D6; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: bold;">
                    ↓
                </button>

                <label class="element-toggle-switch is-visible" style="margin: 0; padding: 3px 8px; font-size: 0.72rem;">
                    <input type="checkbox" class="channel-vis-checkbox" checked onchange="toggleChannelRowVis(this)">
                    <span class="toggle-slider"></span>
                    <span class="toggle-text">Visível</span>
                </label>

                <button type="button" onclick="deleteChannelRow(this)" title="Excluir este Canal" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.35); color: #f87171; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; margin-left: 2px;">
                    ✕
                </button>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1.5fr 1fr; gap: 12px; margin-bottom: 10px;">
            <div>
                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Plataforma / Marketplace</label>
                <input type="text" class="form-input channel-row-platform-input" value="" placeholder="Ex: Amazon, TikTok, Elo7..." oninput="updatePlatformPreview(this)">
            </div>
            <div>
                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Nome do Botão / Ação</label>
                <input type="text" class="form-input channel-row-title-input" value="" placeholder="Ex: Comprar na Amazon Oficial">
            </div>
            <div>
                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Identificação Oficial</label>
                <input type="text" class="form-input channel-row-official-input" value="Canal Homologado DIVINIUM" placeholder="Ex: Canal Homologado DIVINIUM">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 12px; margin-bottom: 10px;">
            <div>
                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Descrição / Vantagem</label>
                <input type="text" class="form-input channel-row-desc-input" value="" placeholder="Ex: Entrega Prime e parcelamento">
            </div>
            <div>
                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Preço Específico (R$)</label>
                <input type="text" class="form-input channel-row-price-input" value="" placeholder="Dinâmico da variação">
            </div>
            <div>
                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Etiqueta / Badge</label>
                <input type="text" class="form-input channel-row-badge-input" value="" placeholder="Ex: Prime, Oficial">
            </div>
            <div>
                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Ícone Visual</label>
                <select class="form-select channel-row-icon-select" onchange="updateIconSelectPreview(this)" style="font-size: 0.8rem; height: 38px;">
                    <option value="oficial">✦ Oficial DIVINIUM</option>
                    <option value="shopee">Shopee</option>
                    <option value="mercadolivre">Mercado Livre</option>
                    <option value="whatsapp">WhatsApp VIP</option>
                    <option value="amazon">Amazon</option>
                    <option value="tiktok">TikTok Shop</option>
                    <option value="sacola">Sacola de Compras</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 3fr 1fr; gap: 12px; margin-bottom: 10px;">
            <div>
                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Link de Compra / URL do Canal</label>
                <input type="url" class="form-input channel-row-url-input" value="" placeholder="https://...">
            </div>
            <div>
                <label style="display: block; font-size: 0.76rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Destino</label>
                <select class="form-select channel-row-target-select" style="font-size: 0.8rem; height: 38px;">
                    <option value="_blank">Nova Aba (_blank)</option>
                    <option value="_self">Mesma Aba (_self)</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding-top: 10px; border-top: 1px dashed rgba(255,255,255,0.08);">
            <div>
                <label style="display: block; font-size: 0.74rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Fundo do Botão (Opcional)</label>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <input type="color" value="#202020" onchange="this.nextElementSibling.value = this.value; serializeChannelsRows();" style="width: 34px; height: 32px; border-radius: 5px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer; padding: 1px;">
                    <input type="text" class="form-input channel-row-bg-color-input" value="" placeholder="Padrão (Ex: #ded7cf ou #202020)" oninput="if(this.value.startsWith('#') && this.value.length === 7) this.previousElementSibling.value = this.value; serializeChannelsRows();" style="font-size: 0.78rem; padding: 6px 8px; font-family: monospace; flex: 1;">
                </div>
            </div>
            <div>
                <label style="display: block; font-size: 0.74rem; color: #CBD0D6; margin-bottom: 3px; font-weight: 600;">Cor da Letra / Texto (Opcional)</label>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <input type="color" value="#FFFFFF" onchange="this.nextElementSibling.value = this.value; serializeChannelsRows();" style="width: 34px; height: 32px; border-radius: 5px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer; padding: 1px;">
                    <input type="text" class="form-input channel-row-text-color-input" value="" placeholder="Padrão (Ex: #FFFFFF ou #202020)" oninput="if(this.value.startsWith('#') && this.value.length === 7) this.previousElementSibling.value = this.value; serializeChannelsRows();" style="font-size: 0.78rem; padding: 6px 8px; font-family: monospace; flex: 1;">
                </div>
            </div>
        </div>
    `;

    container.appendChild(newRow);

    updateChannelsOrderBadges();
    serializeChannelsRows();

    const platInput = newRow.querySelector('.channel-row-platform-input');
    if (platInput) platInput.focus();
}

function updatePlatformPreview(input) {
    const row = input.closest('.channel-row-item');
    if (!row) return;
    const preview = row.querySelector('.channel-platform-preview');
    const iconSelect = row.querySelector('.channel-row-icon-select');
    if (preview) {
        const val = input.value.trim() || (iconSelect ? iconSelect.value : 'Novo Canal');
        preview.innerHTML = `<span class="channel-logo-icon" style="display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; flex-shrink: 0; border-radius: 4px; overflow: hidden; background: #ffffff; padding: 2px; box-shadow: 0 1px 3px rgba(0,0,0,0.15);">${getPlatformLogoSvg(val, 16)}</span><span>${escapeHtml(input.value.trim() || 'Novo Canal')}</span>`;
    }
    serializeChannelsRows();
}

function updateIconSelectPreview(select) {
    const row = select.closest('.channel-row-item');
    if (!row) return;
    const preview = row.querySelector('.channel-platform-preview');
    const platInput = row.querySelector('.channel-row-platform-input');
    const platVal = platInput ? platInput.value.trim() : '';
    const iconVal = select.value;
    const targetVal = platVal || iconVal;
    if (preview) {
        preview.innerHTML = `<span class="channel-logo-icon" style="display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; flex-shrink: 0; border-radius: 4px; overflow: hidden; background: #ffffff; padding: 2px; box-shadow: 0 1px 3px rgba(0,0,0,0.15);">${getPlatformLogoSvg(targetVal, 16)}</span><span>${escapeHtml(platVal || (iconVal === 'oficial' ? 'Loja Oficial DIVINIUM' : iconVal))}</span>`;
    }
    serializeChannelsRows();
}

function updateChannelsOrderBadges() {
    const container = document.getElementById('channelsRowsContainer');
    if (!container) return;

    const rows = container.querySelectorAll('.channel-row-item');
    let visibleCount = 0;
    let hiddenCount = 0;

    rows.forEach((row, idx) => {
        const badge = row.querySelector('.channel-order-badge');
        if (badge) {
            badge.innerText = `Canal #${idx + 1}`;
        }

        const btnUp = row.querySelector('.btn-channel-move-up');
        const btnDown = row.querySelector('.btn-channel-move-down');
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

        const chk = row.querySelector('.channel-vis-checkbox');
        if (chk && chk.checked) {
            visibleCount++;
            row.classList.remove('is-dimmed');
        } else {
            hiddenCount++;
            row.classList.add('is-dimmed');
        }
    });

    const counter = document.getElementById('channelsRowsCounter');
    if (counter) {
        counter.innerHTML = `<strong>${rows.length}</strong> canais de venda configurados (<span style="color: #4ADE80;">${visibleCount} visíveis</span>, <span style="color: #f87171;">${hiddenCount} ocultos</span>)`;
    }
}

function serializeChannelsRows() {
    const container = document.getElementById('channelsRowsContainer');
    const hiddenInput = document.getElementById('store_channels_items_json');
    if (!container || !hiddenInput) return;

    const rows = container.querySelectorAll('.channel-row-item');
    const items = [];

    rows.forEach(row => {
        const id = row.getAttribute('data-id') || '';
        const type = row.getAttribute('data-type') || 'external';
        const isCustom = row.getAttribute('data-custom') === '1';
        
        const platformInput = row.querySelector('.channel-row-platform-input');
        const titleInput = row.querySelector('.channel-row-title-input');
        const officialInput = row.querySelector('.channel-row-official-input');
        const descInput = row.querySelector('.channel-row-desc-input');
        const priceInput = row.querySelector('.channel-row-price-input');
        const badgeInput = row.querySelector('.channel-row-badge-input');
        const iconSelect = row.querySelector('.channel-row-icon-select');
        const urlInput = row.querySelector('.channel-row-url-input');
        const targetSelect = row.querySelector('.channel-row-target-select');
        const visCheckbox = row.querySelector('.channel-vis-checkbox');
        const bgColorInput = row.querySelector('.channel-row-bg-color-input');
        const textColorInput = row.querySelector('.channel-row-text-color-input');

        const plat = platformInput ? platformInput.value.trim() : 'Loja Oficial';
        const title = titleInput ? titleInput.value.trim() : ('Comprar na ' + plat);

        items.push({
            id: id,
            type: type,
            platform: plat,
            title: title,
            official_label: officialInput ? officialInput.value.trim() : 'Canal Homologado DIVINIUM',
            desc: descInput ? descInput.value.trim() : '',
            price_override: priceInput ? priceInput.value.trim() : '',
            badge: badgeInput ? badgeInput.value.trim() : '',
            icon: iconSelect ? iconSelect.value : 'oficial',
            url: urlInput ? urlInput.value.trim() : '',
            target: targetSelect ? targetSelect.value : '_blank',
            button_bg_color: bgColorInput ? bgColorInput.value.trim() : '',
            button_text_color: textColorInput ? textColorInput.value.trim() : '',
            visible: (visCheckbox && visCheckbox.checked) ? 1 : 0,
            is_custom: isCustom ? 1 : 0,
            is_deletable: 1
        });
    });

    hiddenInput.value = JSON.stringify(items);
}

document.addEventListener('DOMContentLoaded', function() {
    updateSpecsOrderBadges();
    serializeSpecsRows();

    updateChannelsOrderBadges();
    serializeChannelsRows();

    const specsContainer = document.getElementById('specsRowsContainer');
    if (specsContainer) {
        specsContainer.addEventListener('input', function(e) {
            if (e.target.classList.contains('spec-row-title-input') || e.target.classList.contains('spec-row-desc-input')) {
                serializeSpecsRows();
            }
        });
    }

    const channelsContainer = document.getElementById('channelsRowsContainer');
    if (channelsContainer) {
        channelsContainer.addEventListener('input', function(e) {
            serializeChannelsRows();
        });
        channelsContainer.addEventListener('change', function(e) {
            serializeChannelsRows();
        });
    }
});

async function handleDisplaySubmit(e) {
    e.preventDefault();
    serializeSpecsRows();    // Garante a serialização mais atualizada da lista de especificações
    serializeChannelsRows(); // Garante a serialização mais atualizada da lista de canais de compra

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
                showAdminToast(data.message || 'Informações e canais de compra salvos com sucesso!', 'success');
            } else {
                alert(data.message || 'Informações e canais de compra salvos com sucesso!');
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
