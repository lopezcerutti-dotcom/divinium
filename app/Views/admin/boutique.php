<?php
/**
 * VIEW: PERSONALIZAR BOUTIQUE DIVINIUM (/loja)
 * QUIET LUXURY BOUTIQUE VISUAL CUSTOMIZER
 * Com controles individuais de Exibição / Ocultação para cada elemento.
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
    <label class="element-toggle-switch ' . $class . '" title="Exibir ou ocultar este elemento na loja pública">
        <input type="hidden" name="' . $key . '" value="0">
        <input type="checkbox" name="' . $key . '" value="1" ' . $checked . ' onchange="handleToggleSwitch(this)">
        <span class="toggle-text">' . $label . '</span>
    </label>';
};

/**
 * Helper reutilizável para renderizar seletor de cores sincronizado (Color Picker + Campo de Texto Hex)
 */
$renderColorField = function(string $key, string $default, string $label, string $hint = ''): string {
    $val = Setting::get($key, $default);
    $pickerVal = (is_string($val) && str_starts_with($val, '#') && strlen($val) === 7) ? $val : ((is_string($default) && str_starts_with($default, '#') && strlen($default) === 7) ? $default : '#202020');
    $html = '<div class="form-group">';
    $html .= '<label for="' . $key . '" style="color: #CBD0D6; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px; display: block;">' . View::e($label) . '</label>';
    $html .= '<div style="display: flex; gap: 8px; align-items: center;">';
    $html .= '<input type="color" id="picker_' . $key . '" value="' . View::e($pickerVal) . '" oninput="syncColorInput(this, \'' . $key . '\'); if (typeof updateProductElementPreview === \'function\') updateProductElementPreview();" style="width: 36px; height: 36px; border: 1px solid rgba(255,255,255,0.2); border-radius: 6px; padding: 1px; cursor: pointer; background: transparent;">';
    $html .= '<input type="text" name="' . $key . '" id="' . $key . '" class="form-input" value="' . View::e($val) . '" placeholder="' . View::e($default) . '" style="font-size: 0.82rem; padding: 7px 10px; flex: 1;" oninput="syncPickerInput(this, \'picker_' . $key . '\'); if (typeof updateProductElementPreview === \'function\') updateProductElementPreview();">';
    $html .= '</div>';
    if (!empty($hint)) {
        $html .= '<span class="field-hint" style="font-size: 0.72rem; color: #a7adb4; margin-top: 2px;">' . View::e($hint) . '</span>';
    }
    $html .= '</div>';
    return $html;
};
?>

<div class="landing-editor-container">

    <!-- BARRA SUPERIOR DE AÇÕES -->
    <div class="editor-header-bar">
        <div class="editor-header-info">
            <h2>Personalizar Boutique & Vitrine Oficial</h2>
            <p>Edite 100% dos elementos visuais, chamadas, selos, filtros e textos da Boutique DIVINIUM (/loja), com opção individual de exibição ou ocultação.</p>
        </div>
        <div class="editor-header-actions">
            <a href="/loja" target="_blank" class="btn-admin-secondary">
                <span>🛍️ Ver Boutique Ao Vivo ↗</span>
            </a>
            <button type="button" class="btn-admin-gold" onclick="document.getElementById('boutiqueForm').requestSubmit()">
                <span>Salvar Todas as Alterações</span>
            </button>
        </div>
    </div>

    <!-- NAVEGAÇÃO ENTRE ABAS DA BOUTIQUE -->
    <div class="editor-nav-tabs">
        <button type="button" class="tab-btn active" onclick="switchBoutiqueSection('bsec-hero', this)">
            <span>👑 Hero & Títulos</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchBoutiqueSection('bsec-colors', this)">
            <span>🎨 Fundo & Cores</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchBoutiqueSection('bsec-trust', this)">
            <span>🛡️ Selos de Garantia</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchBoutiqueSection('bsec-catalog', this)">
            <span>✦ Filtros & Cards</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchBoutiqueSection('bsec-detail', this)">
            <span>💎 Detalhes da Joia & Canais</span>
        </button>
        <a href="/admin/produtos?tab=exibicao" class="tab-btn" style="background: rgba(202, 161, 92, 0.15); border-color: rgba(202, 161, 92, 0.4); text-decoration: none;" title="Abrir Central Unificada de Informações e Exibição dos Produtos">
            <span>💎 Informações & Exibição dos Produtos ↗</span>
        </a>
        <button type="button" class="tab-btn" onclick="switchBoutiqueSection('bsec-reasons', this)">
            <span>💎 Seção Editorial</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchBoutiqueSection('bsec-whatsapp', this)">
            <span>💬 Atendimento VIP</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchBoutiqueSection('bsec-empty', this)">
            <span>🔍 Estado Vazio</span>
        </button>
    </div>

    <form id="boutiqueForm" action="/admin/boutique/salvar" method="POST" onsubmit="handleBoutiqueSubmit(event)">
        <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">

        <!-- ============================================================== -->
        <!-- SEÇÃO 1: HERO HEADER DA BOUTIQUE -->
        <!-- ============================================================== -->
        <div class="editor-section-card active" id="bsec-hero">
            <div class="section-card-header">
                <div class="section-icon">👑</div>
                <div>
                    <h3>Hero & Apresentação da Boutique</h3>
                    <p>O cabeçalho nobre de entrada da loja oficial com títulos e selo em degradê dourado.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width" style="background: rgba(212, 186, 136, 0.05); padding: 12px 16px; border-radius: 8px; border: 1px solid rgba(212, 186, 136, 0.15);">
                    <div class="field-label-row">
                        <label style="color: #ffffff; font-weight: 600; font-size: 0.9rem;">Exibir Cabeçalho Hero da Boutique</label>
                        <?= $renderToggle('store_hero_visible') ?>
                    </div>
                    <span class="field-hint" style="margin-top: 2px;">Ao ocultar, a vitrine inicia diretamente pelo catálogo de joias.</span>
                </div>

                <div class="form-group full-width <?= Setting::get('store_hero_badge_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_hero_badge">Selo Superior da Boutique (Badge)</label>
                        <?= $renderToggle('store_hero_badge_visible') ?>
                    </div>
                    <input type="text" id="store_hero_badge" name="store_hero_badge" class="form-input" 
                           value="<?= View::e(Setting::get('store_hero_badge', 'VITRINE OFICIAL DIVINIUM • CANAIS HOMOLOGADOS')) ?>">
                    <span class="field-hint">Pequeno distintivo com brilho diamantino exibido acima do título principal.</span>
                </div>

                <div class="form-group <?= Setting::get('store_hero_title_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_hero_title">Título Principal (Primeira Parte)</label>
                        <?= $renderToggle('store_hero_title_visible') ?>
                    </div>
                    <input type="text" id="store_hero_title" name="store_hero_title" class="form-input" 
                           value="<?= View::e(Setting::get('store_hero_title', 'Joias Autorais &')) ?>">
                </div>

                <div class="form-group <?= Setting::get('store_hero_title_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="store_hero_highlight">Destaque em Ouro (Segunda Parte)</label>
                    <input type="text" id="store_hero_highlight" name="store_hero_highlight" class="form-input" 
                           value="<?= View::e(Setting::get('store_hero_highlight', 'Sintonia Mineral')) ?>">
                </div>

                <div class="form-group full-width <?= Setting::get('store_hero_subtitle_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_hero_subtitle">Subtítulo / Descrição Conceitual</label>
                        <?= $renderToggle('store_hero_subtitle_visible') ?>
                    </div>
                    <textarea id="store_hero_subtitle" name="store_hero_subtitle" class="form-textarea" rows="3"><?= View::e(Setting::get('store_hero_subtitle', 'Cada joia Divinium une a nobreza e o brilho do banho de prata à energia de gemas minerais raras e conchas naturais. Escolha sua peça e finalize sua compra com toda a segurança, cupons e rastreamento nos nossos canais oficiais homologados.')) ?></textarea>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 2: SELOS DE CONFIANÇA & GARANTIA -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="bsec-trust">
            <div class="section-card-header">
                <div class="section-icon">🛡️</div>
                <div>
                    <h3>Selos de Confiança & Garantia</h3>
                    <p>Os 3 pilares de segurança e autenticidade exibidos abaixo da apresentação da Boutique.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width" style="background: rgba(212, 186, 136, 0.05); padding: 12px 16px; border-radius: 8px; border: 1px solid rgba(212, 186, 136, 0.15);">
                    <div class="field-label-row">
                        <label style="color: #ffffff; font-weight: 600; font-size: 0.9rem;">Exibir Faixa Completa de Selos de Confiança</label>
                        <?= $renderToggle('store_trust_visible') ?>
                    </div>
                    <span class="field-hint" style="margin-top: 2px;">Ativa ou desativa todo o bloco de garantias abaixo do cabeçalho.</span>
                </div>

                <div class="form-group full-width <?= Setting::get('store_badge_1_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_badge_1">Selo 1: Proteção e Compra Garantida</label>
                        <?= $renderToggle('store_badge_1_visible') ?>
                    </div>
                    <input type="text" id="store_badge_1" name="store_badge_1" class="form-input" 
                           value="<?= View::e(Setting::get('store_badge_1', 'Compra 100% Protegida • Garantia Oficial')) ?>">
                </div>

                <div class="form-group full-width <?= Setting::get('store_badge_2_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_badge_2">Selo 2: Autenticidade do Metal</label>
                        <?= $renderToggle('store_badge_2_visible') ?>
                    </div>
                    <input type="text" id="store_badge_2" name="store_badge_2" class="form-input" 
                           value="<?= View::e(Setting::get('store_badge_2', 'Certificado de Banho de Prata')) ?>">
                </div>

                <div class="form-group full-width <?= Setting::get('store_badge_3_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_badge_3">Selo 3: Logística e Entrega</label>
                        <?= $renderToggle('store_badge_3_visible') ?>
                    </div>
                    <input type="text" id="store_badge_3" name="store_badge_3" class="form-input" 
                           value="<?= View::e(Setting::get('store_badge_3', 'Envio Rápido e Seguro para todo o Brasil')) ?>">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 3: CATÁLOGO, FILTROS & LABELS DOS CARDS -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="bsec-catalog">
            <div class="section-card-header">
                <div class="section-icon">✦</div>
                <div>
                    <h3>Filtros do Catálogo & Elementos dos Cards</h3>
                    <p>Controle os rótulos de navegação por coleções e a visibilidade de selos, descrições, pílulas e preços nos cards das joias.</p>
                </div>
            </div>

            <div class="form-grid">
                <!-- Filtros Principais -->
                <div class="form-group full-width" style="background: rgba(212, 186, 136, 0.05); padding: 12px 16px; border-radius: 8px; border: 1px solid rgba(212, 186, 136, 0.15);">
                    <div class="field-label-row">
                        <label style="color: #ffffff; font-weight: 600; font-size: 0.9rem;">Exibir Barra de Filtros de Coleções</label>
                        <?= $renderToggle('store_filter_bar_visible') ?>
                    </div>
                    <span class="field-hint" style="margin-top: 2px;">Permite aos visitantes filtrarem joias por categorias na vitrine.</span>
                </div>

                <div class="form-group <?= Setting::get('store_filter_all_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_filter_all">Pílula 'Todas as Peças'</label>
                        <?= $renderToggle('store_filter_all_visible') ?>
                    </div>
                    <input type="text" id="store_filter_all" name="store_filter_all" class="form-input" 
                           value="<?= View::e(Setting::get('store_filter_all', 'Todas as Peças')) ?>">
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="store_filter_braceletes_visible">Filtro: Braceletes</label>
                        <?= $renderToggle('store_filter_braceletes_visible') ?>
                    </div>
                    <input type="text" readonly class="form-input" style="opacity: 0.8;" value="Categoria: Braceletes (/loja#braceletes)">
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="store_filter_colares_visible">Filtro: Colares</label>
                        <?= $renderToggle('store_filter_colares_visible') ?>
                    </div>
                    <input type="text" readonly class="form-input" style="opacity: 0.8;" value="Categoria: Colares (/loja#colares)">
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="store_filter_aneis_visible">Filtro: Anéis</label>
                        <?= $renderToggle('store_filter_aneis_visible') ?>
                    </div>
                    <input type="text" readonly class="form-input" style="opacity: 0.8;" value="Categoria: Anéis (/loja#aneis)">
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="store_filter_brincos_visible">Filtro: Brincos</label>
                        <?= $renderToggle('store_filter_brincos_visible') ?>
                    </div>
                    <input type="text" readonly class="form-input" style="opacity: 0.8;" value="Categoria: Brincos (/loja#brincos)">
                </div>

                <div class="form-group">
                    <label for="store_default_channel">Canal Padrão Preferencial de Vendas</label>
                    <select id="store_default_channel" name="store_default_channel" class="form-input">
                        <option value="checkout" <?= Setting::get('store_default_channel', 'checkout') === 'checkout' ? 'selected' : '' ?>>🛒 Checkout Próprio Divinium (Pix 5% OFF + Cartão)</option>
                        <option value="whatsapp" <?= Setting::get('store_default_channel') === 'whatsapp' ? 'selected' : '' ?>>💬 Concierge VIP via WhatsApp</option>
                        <option value="shopee" <?= Setting::get('store_default_channel') === 'shopee' ? 'selected' : '' ?>>🟠 Shopee Oficial</option>
                        <option value="mercadolivre" <?= Setting::get('store_default_channel') === 'mercadolivre' ? 'selected' : '' ?>>🟡 Mercado Livre Oficial</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="store_search_placeholder">Texto Placeholder da Busca Rápida</label>
                    <input type="text" id="store_search_placeholder" name="store_search_placeholder" class="form-input"
                           value="<?= View::e(Setting::get('store_search_placeholder', 'Buscar joia por nome, gema ou categoria...')) ?>"
                           placeholder="Buscar joia por nome, gema ou categoria...">
                    <span class="field-hint">Exibido na caixa de busca ao vivo do catálogo na boutique.</span>
                </div>

                <div class="form-group full-width <?= Setting::get('store_info_pill_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_info_pill_text">Pílula Informativa de Segurança (Ao lado dos filtros)</label>
                        <?= $renderToggle('store_info_pill_visible') ?>
                    </div>
                    <input type="text" id="store_info_pill_text" name="store_info_pill_text" class="form-input" 
                           value="<?= View::e(Setting::get('store_info_pill_text', 'Escolha seu canal de compra favorito com segurança')) ?>">
                </div>

                <!-- Elementos dos Cards de Produtos -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); margin-top: 8px; padding-top: 16px;">
                    <h4 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.88rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        👁️ Elementos Visuais Globais dos Cards de Produtos
                    </h4>
                    <span class="field-hint">Defina a visibilidade geral dos elementos nos cards. Lembre-se que cada joia também possui controles individuais no cadastro de produtos.</span>
                </div>

                <div class="form-group <?= Setting::get('store_card_badge_metal_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_card_badge_metal">Selo de Metal Flutuante no Card</label>
                        <?= $renderToggle('store_card_badge_metal_visible') ?>
                    </div>
                    <input type="text" id="store_card_badge_metal" name="store_card_badge_metal" class="form-input" 
                           value="<?= View::e(Setting::get('store_card_badge_metal', 'Banho de Prata')) ?>">
                    <span class="field-hint">Selo sobreposto à foto do produto.</span>
                </div>

                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.05);">
                    <div class="field-label-row">
                        <label>Selo Flutuante do Canal (Shopee ↗ / Canais Oficiais)</label>
                        <?= $renderToggle('store_card_channel_badge_visible') ?>
                    </div>
                    <span class="field-hint">Pílula sobreposta à foto indicando o canal principal ou a contagem de canais homologados.</span>
                </div>

                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.05);">
                    <div class="field-label-row">
                        <label>Indicador Mineral da Joia (Ponto de Prata + Nome)</label>
                        <?= $renderToggle('store_card_mineral_visible') ?>
                    </div>
                    <span class="field-hint">Exibe a gema mineral lapidada logo acima do título do produto.</span>
                </div>

                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.05);">
                    <div class="field-label-row">
                        <label>Descrição Resumida no Card</label>
                        <?= $renderToggle('store_card_desc_visible') ?>
                    </div>
                    <span class="field-hint">Oculte se preferir um card mais limpo e minimalista, focado na foto e no preço.</span>
                </div>

                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.05);">
                    <div class="field-label-row">
                        <label>Tags de Especificações (Medidas, Materiais, SKU)</label>
                        <?= $renderToggle('store_card_specs_visible') ?>
                    </div>
                    <span class="field-hint">Chips informativos de materiais nobres, dimensões e código interno.</span>
                </div>

                <div class="form-group <?= Setting::get('store_pix_discount_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_pix_discount_label">Preço com Desconto no Pix</label>
                        <?= $renderToggle('store_pix_discount_visible') ?>
                    </div>
                    <input type="text" id="store_pix_discount_label" name="store_pix_discount_label" class="form-input" 
                           value="<?= View::e(Setting::get('store_pix_discount_label', 'no Pix (-5%)')) ?>">
                </div>

                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.05);">
                    <div class="field-label-row">
                        <label>Condição de Parcelamento ("Até 6x sem juros")</label>
                        <?= $renderToggle('store_installments_visible') ?>
                    </div>
                    <span class="field-hint">Mensagem informativa de parcelamento abaixo do preço principal.</span>
                </div>

                <div class="form-group full-width <?= Setting::get('store_multi_channel_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_multi_channel_label">Canais Secundários de Venda (Pílulas Independentes)</label>
                        <?= $renderToggle('store_multi_channel_visible') ?>
                    </div>
                    <input type="text" id="store_multi_channel_label" name="store_multi_channel_label" class="form-input" 
                           value="<?= View::e(Setting::get('store_multi_channel_label', 'Disponível também em:')) ?>">
                    <span class="field-hint">Exibe links secundários para Mercado Livre, Amazon, TikTok Shop ou WhatsApp.</span>
                </div>

                <!-- Estilo Padrão dos Botões de Compra -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); margin-top: 12px; padding-top: 16px;">
                    <h4 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.88rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        🎨 Estilo Padrão dos Botões de Compra da Vitrine
                    </h4>
                    <span class="field-hint">Defina a cor e o tamanho padrão para os botões de compra. Cada produto também pode ter botões com cores e tamanhos personalizados na aba de Produtos.</span>
                </div>

                <div class="form-group">
                    <label for="store_btn_default_color" style="color: #ded7cf; font-weight: 500; font-size: 0.85rem;">Cor Padrão dos Botões</label>
                    <select id="store_btn_default_color" name="store_btn_default_color" class="form-input">
                        <option value="default" <?= Setting::get('store_btn_default_color', 'default') === 'default' ? 'selected' : '' ?>>Automático por Plataforma (Shopee Laranja, ML Amarelo...)</option>
                        <option value="gold" <?= Setting::get('store_btn_default_color') === 'gold' ? 'selected' : '' ?>>🪙 Prata Nobre Divinium (#A7ADB4)</option>
                        <option value="dark" <?= Setting::get('store_btn_default_color') === 'dark' ? 'selected' : '' ?>>🖤 Ônix & Grafite (#252525)</option>
                        <option value="silver" <?= Setting::get('store_btn_default_color') === 'silver' ? 'selected' : '' ?>>🪙 Banho de Prata (#CBD0D6)</option>
                        <option value="platinum" <?= Setting::get('store_btn_default_color') === 'platinum' ? 'selected' : '' ?>>⚪ Platina Pura (#F0F0EF)</option>
                        <option value="titanium" <?= Setting::get('store_btn_default_color') === 'titanium' ? 'selected' : '' ?>>🔘 Titânio Grafite (#737B85)</option>
                        <option value="shopee" <?= Setting::get('store_btn_default_color') === 'shopee' ? 'selected' : '' ?>>🟠 Shopee Oficial (#EE4D2D)</option>
                        <option value="mercadolivre" <?= Setting::get('store_btn_default_color') === 'mercadolivre' ? 'selected' : '' ?>>🟡 Mercado Livre (#FFE600)</option>
                        <option value="amazon" <?= Setting::get('store_btn_default_color') === 'amazon' ? 'selected' : '' ?>>📦 Amazon (#FF9900)</option>
                        <option value="whatsapp" <?= Setting::get('store_btn_default_color') === 'whatsapp' ? 'selected' : '' ?>>🟢 WhatsApp VIP (#25D366)</option>
                    </select>
                    <span class="field-hint">Aparência aplicada quando o link estiver configurado como 'Cor Padrão do Canal'.</span>
                </div>

                <div class="form-group">
                    <label for="store_btn_default_size" style="color: #ded7cf; font-weight: 500; font-size: 0.85rem;">Tamanho Padrão dos Botões</label>
                    <select id="store_btn_default_size" name="store_btn_default_size" class="form-input">
                        <option value="sm" <?= Setting::get('store_btn_default_size') === 'sm' ? 'selected' : '' ?>>Compacto (Pequeno / Discreto)</option>
                        <option value="md" <?= Setting::get('store_btn_default_size', 'md') === 'md' ? 'selected' : '' ?>>Médio Padrão (Recomendado)</option>
                        <option value="lg" <?= Setting::get('store_btn_default_size') === 'lg' ? 'selected' : '' ?>>Grande (Destaque Nobre)</option>
                        <option value="full" <?= Setting::get('store_btn_default_size') === 'full' ? 'selected' : '' ?>>Largura Total (100% do Card)</option>
                    </select>
                    <span class="field-hint">Tamanho aplicado a links que utilizam a dimensão padrão da loja.</span>
                </div>

                <!-- Comportamento de Clique nos Cards -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); margin-top: 12px; padding-top: 16px;">
                    <h4 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.88rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        🎯 Comportamento de Clique nos Cards da Boutique
                    </h4>
                    <span class="field-hint">Defina para onde o cliente é direcionado ao clicar na foto, no título ou no card da joia. Cada produto também pode ter exceção individual cadastrada.</span>
                </div>

                <div class="form-group">
                    <label for="store_card_click_behavior" style="color: #ded7cf; font-weight: 500; font-size: 0.85rem;">Ao Clicar no Card da Joia</label>
                    <select id="store_card_click_behavior" name="store_card_click_behavior" class="form-input">
                        <option value="detail" <?= Setting::get('store_card_click_behavior', 'detail') === 'detail' ? 'selected' : '' ?>>💎 Abrir Página de Detalhes da Joia na Boutique DIVINIUM (Padrão Recomendado)</option>
                        <option value="external" <?= Setting::get('store_card_click_behavior') === 'external' ? 'selected' : '' ?>>↗ Redirecionar diretamente para o primeiro canal externo cadastrado</option>
                    </select>
                    <span class="field-hint">Na opção recomendada, o cliente primeiro conhece a história, especificações e fotos da joia, e só depois escolhe o canal de compra.</span>
                </div>

                <div class="form-group">
                    <label for="store_card_btn_text" style="color: #ded7cf; font-weight: 500; font-size: 0.85rem;">Texto do Botão Principal do Card</label>
                    <input type="text" id="store_card_btn_text" name="store_card_btn_text" class="form-input" 
                           value="<?= View::e(Setting::get('store_card_btn_text', 'Conhecer Joia & Opções')) ?>" placeholder="Conhecer Joia & Opções">
                    <span class="field-hint">Texto de convite exibido no botão nobre da vitrine.</span>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO: PÁGINA DE DETALHES DA JOIA & CANAIS DE COMPRA (/produto/{slug}) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="bsec-detail">
            <div class="section-card-header">
                <div class="section-icon">💎</div>
                <div>
                    <h3>Página de Detalhes da Joia & Canais de Compra</h3>
                    <p>Controle a disposição, títulos, seções informativas e imparcialidade dos botões na visualização individual do produto (/produto/{slug}).</p>
                </div>
            </div>

            <div class="form-grid">
                
                <!-- Card de Acesso à Central Unificada -->
                <div class="form-group full-width" style="background: rgba(202, 161, 92, 0.08); border: 1px solid rgba(202, 161, 92, 0.3); border-radius: 8px; padding: 14px 18px; display: flex; align-items: center; justify-content: space-between; gap: 14px;">
                    <div>
                        <strong style="color: #ffffff; font-size: 0.9rem; display: block; margin-bottom: 2px;">💎 Central Unificada de Informações e Exibição dos Produtos</strong>
                        <p style="margin: 0; font-size: 0.82rem; color: #ded7cf;">Para gerenciar SKU, selos de metal ("Banho de Prata"), gemas minerais, imagens sem cortes e especificações técnicas completas em um único lugar centralizado, utilize a seção dedicada.</p>
                    </div>
                    <a href="/admin/produtos?tab=exibicao" class="btn-admin-gold" style="white-space: nowrap; font-size: 0.8rem; padding: 8px 16px; text-decoration: none;">
                        Abrir Informações & Exibição ↗
                    </a>
                </div>

                <!-- Layout e Imparcialidade dos Botões -->
                <div class="form-group full-width" style="background: rgba(167, 173, 180, 0.06); padding: 14px 16px; border-radius: 8px; border: 1px solid rgba(167, 173, 180, 0.25);">
                    <h4 style="color: #CBD0D6; margin: 0 0 6px; font-size: 0.88rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        ⚖️ Imparcialidade & Disposição dos Canais de Venda
                    </h4>
                    <p style="margin: 0; font-size: 0.82rem; color: #b5ada3;">
                        Padroniza todos os canais cadastrados (Shopee, Mercado Livre, Amazon, TikTok Shop, WhatsApp, Loja Oficial) para que tenham a mesma nobreza, tipografia e destaque visual equivalente.
                    </p>
                </div>

                <div class="form-group">
                    <label for="store_detail_button_layout" style="color: #ded7cf; font-weight: 500; font-size: 0.85rem;">Disposição dos Botões de Compra</label>
                    <select id="store_detail_button_layout" name="store_detail_button_layout" class="form-input">
                        <option value="list" <?= Setting::get('store_detail_button_layout', 'list') === 'list' ? 'selected' : '' ?>>📋 Lista Vertical Padronizada (Padrão Quiet Luxury)</option>
                        <option value="grid" <?= Setting::get('store_detail_button_layout') === 'grid' ? 'selected' : '' ?>>🔲 Grade Lado a Lado (2 Colunas)</option>
                    </select>
                    <span class="field-hint">Organização visual das opções de compra na página do produto.</span>
                </div>

                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.05);">
                    <div class="field-label-row">
                        <label>Forçar Imparcialidade Visual dos Canais</label>
                        <?= $renderToggle('store_detail_impartial_buttons', '1') ?>
                    </div>
                    <span class="field-hint">Garante mesmo tamanho, tipografia, bordas e destaque estético para todos os canais de venda.</span>
                </div>

                <!-- Textos Informativos dos Canais -->
                <div class="form-group full-width <?= Setting::get('store_detail_channels_title_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_detail_channels_title">Título da Seção de Compra</label>
                        <?= $renderToggle('store_detail_channels_title_visible', '1') ?>
                    </div>
                    <input type="text" id="store_detail_channels_title" name="store_detail_channels_title" class="form-input" 
                           value="<?= View::e(Setting::get('store_detail_channels_title', 'Escolha onde deseja adquirir sua joia:')) ?>">
                    <span class="field-hint">Chamada principal exibida logo acima da lista de canais de venda.</span>
                </div>

                <div class="form-group full-width <?= Setting::get('store_detail_channels_subtitle_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_detail_channels_subtitle">Subtítulo Informativo de Confiança</label>
                        <?= $renderToggle('store_detail_channels_subtitle_visible', '1') ?>
                    </div>
                    <textarea id="store_detail_channels_subtitle" name="store_detail_channels_subtitle" class="form-textarea" rows="2"><?= View::e(Setting::get('store_detail_channels_subtitle', 'Selecione seu canal oficial de preferência. Todos contam com garantia de autenticidade, embalagem exclusiva e compra 100% protegida.')) ?></textarea>
                </div>

                <!-- Seções Informativas da Página de Detalhes -->
                <!-- ============================================================== -->
                <!-- CONTROLE COMPLETO DOS ELEMENTOS DA PÁGINA DO PRODUTO (/produto/{slug}) -->
                <!-- ============================================================== -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); margin-top: 14px; padding-top: 18px;">
                    <h4 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.92rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                        <span>🛍️</span> Controles dos Elementos de Compra & Pilares de Confiança
                    </h4>
                    <p style="margin: 0 0 16px; font-size: 0.82rem; color: #a7adb4;">
                        Ative, desative e personalize textos, rótulos, ícones e paletas de cores individuais para o seletor de quantidade, os botões de conversão e os 3 pilares de confiança da página da joia.
                    </p>
                </div>

                <!-- CARD 1: QUANTIDADE DE JOIAS -->
                <?php
                $qtyVisible = Setting::get('store_qty_selector_visible', '1');
                $isQtyDimmed = ($qtyVisible !== '1' && $qtyVisible !== 1) ? 'is-dimmed' : '';
                ?>
                <div class="form-group full-width <?= $isQtyDimmed ?>" id="card_ctrl_store_qty" style="background: rgba(255,255,255,0.025); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 18px 20px; margin-bottom: 14px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 10px;">
                        <div>
                            <strong style="color: #ffffff; font-size: 0.92rem; display: flex; align-items: center; gap: 8px;">
                                <span>🔢</span> 1. Quantidade de Joias (Seletor [- 1 +])
                            </strong>
                            <span style="font-size: 0.74rem; color: #a7adb4; display: block; margin-top: 2px;">Rótulo e seletor numérico de unidades que o cliente adiciona à sacola.</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 0.74rem; color: #a7adb4;">Exibição:</span>
                            <?= $renderToggle('store_qty_selector_visible', '1') ?>
                        </div>
                    </div>

                    <!-- Live Mini Preview -->
                    <div style="margin-bottom: 16px; background: rgba(0,0,0,0.3); border: 1px dashed rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                        <span style="font-size: 0.75rem; color: #8e8880; font-weight: 600; text-transform: uppercase;">Prévia em Tempo Real:</span>
                        <div id="prev_qty_wrap" style="display: inline-flex; align-items: center; gap: 8px;">
                            <span id="prev_qty_label" style="font-size: 0.78rem; font-weight: 700; text-transform: uppercase; color: <?= View::e(Setting::get('store_qty_text_color', '#A7ADB4')) ?>;"><?= View::e(Setting::get('store_qty_label', 'Qtd:')) ?></span>
                            <div id="prev_qty_box" style="display: inline-flex; align-items: center; background: <?= View::e(Setting::get('store_qty_bg_color', 'rgba(0, 0, 0, 0.35)')) ?>; border: 1px solid <?= View::e(Setting::get('store_qty_border_color', 'rgba(255, 255, 255, 0.18)')) ?>; border-radius: 8px; overflow: hidden;">
                                <span style="width: 32px; height: 34px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: <?= View::e(Setting::get('store_qty_text_color', '#ded7cf')) ?>;">−</span>
                                <span style="min-width: 28px; text-align: center; font-weight: 800; font-size: 0.9rem; color: <?= View::e(Setting::get('store_qty_text_color', '#ffffff')) ?>;">1</span>
                                <span style="width: 32px; height: 34px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: <?= View::e(Setting::get('store_qty_text_color', '#ded7cf')) ?>;">+</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                        <div class="form-group">
                            <label for="store_qty_label" style="color: #CBD0D6; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px; display: block;">Rótulo do Campo</label>
                            <input type="text" id="store_qty_label" name="store_qty_label" class="form-input" 
                                   value="<?= View::e(Setting::get('store_qty_label', 'Qtd:')) ?>" 
                                   placeholder="Qtd:" oninput="updateProductElementPreview()">
                            <span class="field-hint" style="font-size: 0.72rem; color: #a7adb4;">Texto ao lado do contador (ex: 'Qtd:', 'Unidades:').</span>
                        </div>
                        <?= $renderColorField('store_qty_text_color', '#ffffff', 'Cor do Texto / Rótulo', 'Cor dos números, símbolos e rótulo') ?>
                        <?= $renderColorField('store_qty_bg_color', '#14171a', 'Cor de Fundo do Seletor', 'Fundo da caixa de quantidade') ?>
                        <?= $renderColorField('store_qty_border_color', '#3d444d', 'Cor da Borda do Seletor', 'Borda externa da caixa') ?>
                    </div>
                </div>

                <!-- CARD 2: BOTÃO 'ADICIONAR À SACOLA' -->
                <?php
                $cartBtnVis = Setting::get('store_add_to_cart_btn_visible', Setting::get('store_add_to_cart_visible', '1'));
                $isCartDimmed = ($cartBtnVis !== '1' && $cartBtnVis !== 1) ? 'is-dimmed' : '';
                $cartBg = Setting::get('store_add_to_cart_bg_color', Setting::get('store_add_to_cart_bg', 'rgba(202, 161, 92, 0.08)'));
                $cartColor = Setting::get('store_add_to_cart_text_color', Setting::get('store_add_to_cart_color', '#ffffff'));
                $cartBorder = Setting::get('store_add_to_cart_border_color', Setting::get('store_add_to_cart_border', '#CBD0D6'));
                $cartIconColor = Setting::get('store_add_to_cart_icon_color', '#CBD0D6');
                $cartText = Setting::get('store_add_to_cart_btn_text', Setting::get('store_add_to_cart_text', 'Adicionar à Sacola'));
                ?>
                <div class="form-group full-width <?= $isCartDimmed ?>" id="card_ctrl_store_add_to_cart" style="background: rgba(255,255,255,0.025); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 18px 20px; margin-bottom: 14px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 10px;">
                        <div>
                            <strong style="color: #ffffff; font-size: 0.92rem; display: flex; align-items: center; gap: 8px;">
                                <span>🛍️</span> 2. Botão 'Adicionar à Sacola'
                            </strong>
                            <span style="font-size: 0.74rem; color: #a7adb4; display: block; margin-top: 2px;">Insere o produto e quantidade na gaveta da sacola sem sair da página.</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 0.74rem; color: #a7adb4;">Exibição:</span>
                            <?= $renderToggle('store_add_to_cart_btn_visible', '1') ?>
                        </div>
                    </div>

                    <!-- Live Mini Preview -->
                    <div style="margin-bottom: 16px; background: rgba(0,0,0,0.3); border: 1px dashed rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                        <span style="font-size: 0.75rem; color: #8e8880; font-weight: 600; text-transform: uppercase;">Prévia em Tempo Real:</span>
                        <div id="prev_add_to_cart_btn" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 8px; font-size: 0.86rem; font-weight: 700; background: <?= View::e($cartBg) ?>; color: <?= View::e($cartColor) ?>; border: 1.5px solid <?= View::e($cartBorder) ?>;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="<?= View::e($cartIconColor) ?>" stroke-width="2" id="prev_add_to_cart_svg">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                            <span id="prev_add_to_cart_text"><?= View::e($cartText) ?></span>
                        </div>
                    </div>

                    <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                        <div class="form-group">
                            <label for="store_add_to_cart_btn_text" style="color: #CBD0D6; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px; display: block;">Texto do Botão</label>
                            <input type="text" id="store_add_to_cart_btn_text" name="store_add_to_cart_btn_text" class="form-input" 
                                   value="<?= View::e($cartText) ?>" placeholder="Adicionar à Sacola" oninput="updateProductElementPreview()">
                        </div>
                        <?= $renderColorField('store_add_to_cart_bg_color', '#22262b', 'Cor de Fundo', 'Fundo do botão adicionar') ?>
                        <?= $renderColorField('store_add_to_cart_text_color', '#ffffff', 'Cor da Letra (Texto)', 'Cor do texto do botão') ?>
                        <?= $renderColorField('store_add_to_cart_icon_color', '#CBD0D6', 'Cor do Ícone', 'Cor do ícone da sacola') ?>
                        <?= $renderColorField('store_add_to_cart_border_color', '#CBD0D6', 'Cor da Borda', 'Borda do botão') ?>
                    </div>
                </div>

                <!-- CARD 3: BOTÃO 'COMPRAR AGORA' -->
                <?php
                $buyNowVis = Setting::get('store_buy_now_btn_visible', Setting::get('store_buy_now_visible', '1'));
                $isBuyNowDimmed = ($buyNowVis !== '1' && $buyNowVis !== 1) ? 'is-dimmed' : '';
                $buyNowBg = Setting::get('store_buy_now_bg_color', Setting::get('store_buy_now_bg', '#CBD0D6'));
                $buyNowColor = Setting::get('store_buy_now_text_color', Setting::get('store_buy_now_color', '#1a1918'));
                $buyNowBorder = Setting::get('store_buy_now_border_color', Setting::get('store_buy_now_border', '#737B85'));
                $buyNowIconColor = Setting::get('store_buy_now_icon_color', '#1a1918');
                $buyNowText = Setting::get('store_buy_now_btn_text', Setting::get('store_buy_now_text', 'Comprar Agora'));
                ?>
                <div class="form-group full-width <?= $isBuyNowDimmed ?>" id="card_ctrl_store_buy_now" style="background: rgba(255,255,255,0.025); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 18px 20px; margin-bottom: 14px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 10px;">
                        <div>
                            <strong style="color: #ffffff; font-size: 0.92rem; display: flex; align-items: center; gap: 8px;">
                                <span>⚡</span> 3. Botão 'Comprar Agora' (Compra Direta)
                            </strong>
                            <span style="font-size: 0.74rem; color: #a7adb4; display: block; margin-top: 2px;">Adiciona o item e direciona o cliente direto ao checkout seguro (/checkout).</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 0.74rem; color: #a7adb4;">Exibição:</span>
                            <?= $renderToggle('store_buy_now_btn_visible', '1') ?>
                        </div>
                    </div>

                    <!-- Live Mini Preview -->
                    <div style="margin-bottom: 16px; background: rgba(0,0,0,0.3); border: 1px dashed rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                        <span style="font-size: 0.75rem; color: #8e8880; font-weight: 600; text-transform: uppercase;">Prévia em Tempo Real:</span>
                        <div id="prev_buy_now_btn" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 8px; font-size: 0.86rem; font-weight: 800; background: <?= View::e($buyNowBg) ?>; color: <?= View::e($buyNowColor) ?>; border: 1.5px solid <?= View::e($buyNowBorder) ?>;">
                            <span id="prev_buy_now_text"><?= View::e($buyNowText) ?></span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="<?= View::e($buyNowIconColor) ?>" stroke-width="2.2" id="prev_buy_now_svg">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </div>
                    </div>

                    <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                        <div class="form-group">
                            <label for="store_buy_now_btn_text" style="color: #CBD0D6; font-size: 0.8rem; font-weight: 600; margin-bottom: 4px; display: block;">Texto do Botão</label>
                            <input type="text" id="store_buy_now_btn_text" name="store_buy_now_btn_text" class="form-input" 
                                   value="<?= View::e($buyNowText) ?>" placeholder="Comprar Agora" oninput="updateProductElementPreview()">
                        </div>
                        <?= $renderColorField('store_buy_now_bg_color', '#CBD0D6', 'Cor de Fundo', 'Fundo do botão comprar') ?>
                        <?= $renderColorField('store_buy_now_text_color', '#1a1918', 'Cor da Letra (Texto)', 'Cor do texto do botão') ?>
                        <?= $renderColorField('store_buy_now_icon_color', '#1a1918', 'Cor do Ícone', 'Cor da seta do botão') ?>
                        <?= $renderColorField('store_buy_now_border_color', '#737B85', 'Cor da Borda', 'Borda do botão') ?>
                    </div>
                </div>

                <!-- CARD 4: PILARES DE CONFIANÇA (3 PILARES INDEPENDENTES) -->
                <?php
                $trustRowVis = Setting::get('store_bag_trust_row_visible', '1');
                $isTrustRowDimmed = ($trustRowVis !== '1' && $trustRowVis !== 1) ? 'is-dimmed' : '';
                ?>
                <div class="form-group full-width <?= $isTrustRowDimmed ?>" id="card_ctrl_store_trust" style="background: rgba(255,255,255,0.025); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 18px 20px; margin-bottom: 14px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 10px;">
                        <div>
                            <strong style="color: #ffffff; font-size: 0.92rem; display: flex; align-items: center; gap: 8px;">
                                <span>🛡️</span> 4. Pilares de Confiança Abaixo dos Botões de Compra
                            </strong>
                            <span style="font-size: 0.74rem; color: #a7adb4; display: block; margin-top: 2px;">
                                Configurações independentes para cada um dos 3 pilares: texto, ícone, cores de texto, ícone, fundo e borda.
                            </span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 0.74rem; color: #a7adb4;">Linha Geral:</span>
                            <?= $renderToggle('store_bag_trust_row_visible', '1') ?>
                        </div>
                    </div>

                    <!-- Live Mini Preview da Linha de Pilares -->
                    <div style="margin-bottom: 16px; background: rgba(0,0,0,0.3); border: 1px dashed rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                        <span style="font-size: 0.75rem; color: #8e8880; font-weight: 600; text-transform: uppercase;">Prévia dos 3 Pilares:</span>
                        <div id="prev_trust_sub_row" style="display: flex; align-items: center; gap: 10px; font-size: 0.74rem; flex-wrap: wrap;">
                            <span id="prev_trust1_badge" style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 6px; border-radius: 4px;">
                                <span id="prev_trust1_icon"><?= View::e(Setting::get('store_bag_trust1_icon', '🛡️')) ?></span>
                                <span id="prev_trust1_text"><?= View::e(Setting::get('store_bag_trust1_text', 'Compra 100% Protegida')) ?></span>
                            </span>
                            <span class="dot" style="opacity: 0.4;">•</span>
                            <span id="prev_trust2_badge" style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 6px; border-radius: 4px;">
                                <span id="prev_trust2_icon"><?= View::e(Setting::get('store_bag_trust2_icon', '⚡')) ?></span>
                                <span id="prev_trust2_text"><?= View::e(Setting::get('store_bag_trust2_text', 'Pix com Desconto')) ?></span>
                            </span>
                            <span class="dot" style="opacity: 0.4;">•</span>
                            <span id="prev_trust3_badge" style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 6px; border-radius: 4px;">
                                <span id="prev_trust3_icon"><?= View::e(Setting::get('store_bag_trust3_icon', '💎')) ?></span>
                                <span id="prev_trust3_text"><?= View::e(Setting::get('store_bag_trust3_text', 'Joia Autoral com Certificado')) ?></span>
                            </span>
                        </div>
                    </div>

                    <!-- Sub-cards para cada um dos 3 pilares -->
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        
                        <!-- PILAR 1 -->
                        <?php
                        $t1Vis = Setting::get('store_bag_trust1_visible', '1');
                        $isT1Dim = ($t1Vis !== '1' && $t1Vis !== 1) ? 'is-dimmed' : '';
                        $t1Icon = Setting::get('store_bag_trust1_icon', '🛡️');
                        $t1Raw = Setting::get('store_bag_trust1_text', 'Compra 100% Protegida');
                        $t1Text = ($t1Raw === '🔒 Compra 100% Protegida') ? 'Compra 100% Protegida' : $t1Raw;
                        ?>
                        <div class="pilar-editor-subcard <?= $isT1Dim ?>" id="card_sub_trust1" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 14px 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.04); padding-bottom: 8px;">
                                <strong style="color: #CBD0D6; font-size: 0.85rem;">🛡️ Pilar 1: Compra Protegida</strong>
                                <?= $renderToggle('store_bag_trust1_visible', '1') ?>
                            </div>
                            <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px;">
                                <div class="form-group">
                                    <label style="color: #CBD0D6; font-size: 0.78rem; font-weight: 600; margin-bottom: 4px; display: block;">Ícone (Emoji / Símbolo)</label>
                                    <div style="display: flex; gap: 6px;">
                                        <input type="text" id="store_bag_trust1_icon" name="store_bag_trust1_icon" class="form-input" value="<?= View::e($t1Icon) ?>" placeholder="🛡️" style="width: 70px; text-align: center; font-size: 1rem;" oninput="updateProductElementPreview()">
                                        <div style="display: flex; gap: 4px; align-items: center;">
                                            <button type="button" class="btn-admin-cancel" style="padding: 4px 6px; font-size: 0.85rem;" onclick="setTrustIcon(1, '🛡️')">🛡️</button>
                                            <button type="button" class="btn-admin-cancel" style="padding: 4px 6px; font-size: 0.85rem;" onclick="setTrustIcon(1, '🔒')">🔒</button>
                                            <button type="button" class="btn-admin-cancel" style="padding: 4px 6px; font-size: 0.85rem;" onclick="setTrustIcon(1, '✓')">✓</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="grid-column: span 2;">
                                    <label for="store_bag_trust1_text" style="color: #CBD0D6; font-size: 0.78rem; font-weight: 600; margin-bottom: 4px; display: block;">Texto do Pilar 1</label>
                                    <input type="text" id="store_bag_trust1_text" name="store_bag_trust1_text" class="form-input" value="<?= View::e($t1Text) ?>" placeholder="Compra 100% Protegida" oninput="updateProductElementPreview()">
                                </div>
                                <?= $renderColorField('store_bag_trust1_text_color', '#a0988c', 'Cor do Texto', 'Cor da legenda') ?>
                                <?= $renderColorField('store_bag_trust1_icon_color', '#CBD0D6', 'Cor do Ícone', 'Cor do símbolo') ?>
                                <?= $renderColorField('store_bag_trust1_bg_color', 'transparent', 'Cor de Fundo', 'Fundo (ou transparent)') ?>
                                <?= $renderColorField('store_bag_trust1_border_color', 'transparent', 'Cor da Borda', 'Borda (ou transparent)') ?>
                            </div>
                        </div>

                        <!-- PILAR 2 -->
                        <?php
                        $t2Vis = Setting::get('store_bag_trust2_visible', '1');
                        $isT2Dim = ($t2Vis !== '1' && $t2Vis !== 1) ? 'is-dimmed' : '';
                        $t2Icon = Setting::get('store_bag_trust2_icon', '⚡');
                        $t2Raw = Setting::get('store_bag_trust2_text', 'Pix com Desconto');
                        $t2Text = ($t2Raw === '⚡ Pix com Desconto') ? 'Pix com Desconto' : $t2Raw;
                        ?>
                        <div class="pilar-editor-subcard <?= $isT2Dim ?>" id="card_sub_trust2" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 14px 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.04); padding-bottom: 8px;">
                                <strong style="color: #CBD0D6; font-size: 0.85rem;">⚡ Pilar 2: Pix com Desconto</strong>
                                <?= $renderToggle('store_bag_trust2_visible', '1') ?>
                            </div>
                            <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px;">
                                <div class="form-group">
                                    <label style="color: #CBD0D6; font-size: 0.78rem; font-weight: 600; margin-bottom: 4px; display: block;">Ícone (Emoji / Símbolo)</label>
                                    <div style="display: flex; gap: 6px;">
                                        <input type="text" id="store_bag_trust2_icon" name="store_bag_trust2_icon" class="form-input" value="<?= View::e($t2Icon) ?>" placeholder="⚡" style="width: 70px; text-align: center; font-size: 1rem;" oninput="updateProductElementPreview()">
                                        <div style="display: flex; gap: 4px; align-items: center;">
                                            <button type="button" class="btn-admin-cancel" style="padding: 4px 6px; font-size: 0.85rem;" onclick="setTrustIcon(2, '⚡')">⚡</button>
                                            <button type="button" class="btn-admin-cancel" style="padding: 4px 6px; font-size: 0.85rem;" onclick="setTrustIcon(2, '✦')">✦</button>
                                            <button type="button" class="btn-admin-cancel" style="padding: 4px 6px; font-size: 0.85rem;" onclick="setTrustIcon(2, '%')">%</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="grid-column: span 2;">
                                    <label for="store_bag_trust2_text" style="color: #CBD0D6; font-size: 0.78rem; font-weight: 600; margin-bottom: 4px; display: block;">Texto do Pilar 2</label>
                                    <input type="text" id="store_bag_trust2_text" name="store_bag_trust2_text" class="form-input" value="<?= View::e($t2Text) ?>" placeholder="Pix com Desconto" oninput="updateProductElementPreview()">
                                </div>
                                <?= $renderColorField('store_bag_trust2_text_color', '#a0988c', 'Cor do Texto', 'Cor da legenda') ?>
                                <?= $renderColorField('store_bag_trust2_icon_color', '#CBD0D6', 'Cor do Ícone', 'Cor do símbolo') ?>
                                <?= $renderColorField('store_bag_trust2_bg_color', 'transparent', 'Cor de Fundo', 'Fundo (ou transparent)') ?>
                                <?= $renderColorField('store_bag_trust2_border_color', 'transparent', 'Cor da Borda', 'Borda (ou transparent)') ?>
                            </div>
                        </div>

                        <!-- PILAR 3 -->
                        <?php
                        $t3Vis = Setting::get('store_bag_trust3_visible', '1');
                        $isT3Dim = ($t3Vis !== '1' && $t3Vis !== 1) ? 'is-dimmed' : '';
                        $t3Icon = Setting::get('store_bag_trust3_icon', '💎');
                        $t3Raw = Setting::get('store_bag_trust3_text', 'Joia Autoral com Certificado');
                        $t3Text = ($t3Raw === '✦ Joia Autoral com Certificado') ? 'Joia Autoral com Certificado' : $t3Raw;
                        ?>
                        <div class="pilar-editor-subcard <?= $isT3Dim ?>" id="card_sub_trust3" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 14px 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; border-bottom: 1px solid rgba(255,255,255,0.04); padding-bottom: 8px;">
                                <strong style="color: #CBD0D6; font-size: 0.85rem;">💎 Pilar 3: Autenticidade & Joia Autoral</strong>
                                <?= $renderToggle('store_bag_trust3_visible', '1') ?>
                            </div>
                            <div style="margin-bottom: 10px; background: rgba(202, 161, 92, 0.08); border-left: 3px solid #CBD0D6; padding: 6px 10px; border-radius: 4px;">
                                <span style="font-size: 0.72rem; color: #ded7cf; line-height: 1.3; display: block;">
                                    ⚖️ <strong>Transparência & Conformidade:</strong> O texto e o ícone deste pilar são totalmente editáveis e ocultáveis. Se sua joia não inclui certificado físico na entrega, personalize o texto ou desative o pilar.
                                </span>
                            </div>
                            <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px;">
                                <div class="form-group">
                                    <label style="color: #CBD0D6; font-size: 0.78rem; font-weight: 600; margin-bottom: 4px; display: block;">Ícone (Emoji / Símbolo)</label>
                                    <div style="display: flex; gap: 6px;">
                                        <input type="text" id="store_bag_trust3_icon" name="store_bag_trust3_icon" class="form-input" value="<?= View::e($t3Icon) ?>" placeholder="💎" style="width: 70px; text-align: center; font-size: 1rem;" oninput="updateProductElementPreview()">
                                        <div style="display: flex; gap: 4px; align-items: center;">
                                            <button type="button" class="btn-admin-cancel" style="padding: 4px 6px; font-size: 0.85rem;" onclick="setTrustIcon(3, '💎')">💎</button>
                                            <button type="button" class="btn-admin-cancel" style="padding: 4px 6px; font-size: 0.85rem;" onclick="setTrustIcon(3, '✦')">✦</button>
                                            <button type="button" class="btn-admin-cancel" style="padding: 4px 6px; font-size: 0.85rem;" onclick="setTrustIcon(3, '👑')">👑</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="grid-column: span 2;">
                                    <label for="store_bag_trust3_text" style="color: #CBD0D6; font-size: 0.78rem; font-weight: 600; margin-bottom: 4px; display: block;">Texto do Pilar 3</label>
                                    <input type="text" id="store_bag_trust3_text" name="store_bag_trust3_text" class="form-input" value="<?= View::e($t3Text) ?>" placeholder="Joia Autoral com Certificado" oninput="updateProductElementPreview()">
                                </div>
                                <?= $renderColorField('store_bag_trust3_text_color', '#a0988c', 'Cor do Texto', 'Cor da legenda') ?>
                                <?= $renderColorField('store_bag_trust3_icon_color', '#CBD0D6', 'Cor do Ícone', 'Cor do símbolo') ?>
                                <?= $renderColorField('store_bag_trust3_bg_color', 'transparent', 'Cor de Fundo', 'Fundo (ou transparent)') ?>
                                <?= $renderColorField('store_bag_trust3_border_color', 'transparent', 'Cor da Borda', 'Borda (ou transparent)') ?>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Seletor de Variações de Pedras Minerais -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); margin-top: 8px; padding-top: 14px;">
                    <h5 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.88rem; text-transform: uppercase;">
                        ✦ Seletor de Pedras Minerais & Variações
                    </h5>
                </div>

                <div class="form-group">
                    <label for="store_variations_label">Rótulo das Variações</label>
                    <input type="text" id="store_variations_label" name="store_variations_label" class="form-input" 
                           value="<?= View::e(Setting::get('store_variations_label', 'Pedra Mineral:')) ?>">
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="store_variations_badge_suffix">Sufixo do Distintivo de Opções</label>
                        <?= $renderToggle('store_variations_badge_visible', '1') ?>
                    </div>
                    <input type="text" id="store_variations_badge_suffix" name="store_variations_badge_suffix" class="form-input" 
                           value="<?= View::e(Setting::get('store_variations_badge_suffix', 'Opções de Gemas')) ?>">
                    <span class="field-hint">Ex: 'Opções de Gemas', 'Cores Disponíveis'.</span>
                </div>

                <!-- Descrição & Storytelling da Joia -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); margin-top: 8px; padding-top: 14px;">
                    <h5 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.88rem; text-transform: uppercase;">
                        📖 Descrição & Essência da Peça (Storytelling)
                    </h5>
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="store_desc_card_title">Título da Seção de Descrição</label>
                        <?= $renderToggle('store_desc_card_title_visible', '1') ?>
                    </div>
                    <input type="text" id="store_desc_card_title" name="store_desc_card_title" class="form-input" 
                           value="<?= View::e(Setting::get('store_desc_card_title', 'A Essência da Peça')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="store_desc_default_text">Texto Padrão de Descrição (Fallback)</label>
                    <textarea id="store_desc_default_text" name="store_desc_default_text" class="form-textarea" rows="2"><?= View::e(Setting::get('store_desc_default_text', 'Joia autoral manufaturada com banho de prata com lapidação mineral precisa e acabamento artesanal espelhado. Uma expressão sublime do luxo silencioso, criada para transcender tendências e permanecer na sua história.')) ?></textarea>
                    <span class="field-hint">Exibido caso o produto não possua descrição individual cadastrada.</span>
                </div>

                <!-- Fallback Atendimento WhatsApp VIP -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); margin-top: 8px; padding-top: 14px;">
                    <h5 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.88rem; text-transform: uppercase;">
                        💬 Botão de Atendimento Concierge VIP (Fallback de Compra)
                    </h5>
                </div>

                <div class="form-group">
                    <label for="store_wa_fallback_title">Texto Principal do Botão Concierge</label>
                    <input type="text" id="store_wa_fallback_title" name="store_wa_fallback_title" class="form-input" 
                           value="<?= View::e(Setting::get('store_wa_fallback_title', 'Consultar Disponibilidade com Consultor VIP')) ?>">
                </div>

                <div class="form-group">
                    <label for="store_wa_fallback_sub">Subtítulo de Confiança</label>
                    <input type="text" id="store_wa_fallback_sub" name="store_wa_fallback_sub" class="form-input" 
                           value="<?= View::e(Setting::get('store_wa_fallback_sub', 'Atendimento Direto DIVINIUM')) ?>">
                </div>

                <div class="form-group">
                    <label for="store_wa_fallback_price_label">Rótulo do Preço</label>
                    <input type="text" id="store_wa_fallback_price_label" name="store_wa_fallback_price_label" class="form-input" 
                           value="<?= View::e(Setting::get('store_wa_fallback_price_label', 'Referência')) ?>">
                </div>

                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); margin-top: 10px; padding-top: 16px;">
                    <h4 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.88rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        👁️ Visibilidade de Seções na Página de Detalhes
                    </h4>
                    <span class="field-hint">Ative ou desative seções complementares da página da joia.</span>
                </div>

                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.05);">
                    <div class="field-label-row">
                        <label>Ficha Técnica & Especificações da Joia</label>
                        <?= $renderToggle('store_detail_show_specs', '1') ?>
                    </div>
                    <span class="field-hint">Exibe tabela com materiais, medidas, gema mineral e código interno.</span>
                </div>

                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.05);">
                    <div class="field-label-row">
                        <label>Card de Garantia & Autenticidade DIVINIUM</label>
                        <?= $renderToggle('store_detail_show_guarantee', '1') ?>
                    </div>
                    <span class="field-hint">Exibe o selo de Banho de Prata com garantia e estojo de presente.</span>
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="store_guarantee_icon">Ícone do Selo de Garantia</label>
                        <?= $renderToggle('store_guarantee_icon_visible', '1') ?>
                    </div>
                    <input type="text" id="store_guarantee_icon" name="store_guarantee_icon" class="form-input" 
                           value="<?= View::e(Setting::get('store_guarantee_icon', '🛡️')) ?>" placeholder="🛡️">
                </div>

                <div class="form-group <?= Setting::get('store_detail_guarantee_title_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_detail_guarantee_title">Título da Garantia</label>
                        <?= $renderToggle('store_detail_guarantee_title_visible', '1') ?>
                    </div>
                    <input type="text" id="store_detail_guarantee_title" name="store_detail_guarantee_title" class="form-input" 
                           value="<?= View::e(Setting::get('store_detail_guarantee_title', 'Autenticidade & Garantia DIVINIUM')) ?>">
                </div>

                <div class="form-group full-width <?= Setting::get('store_detail_show_guarantee', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="store_detail_guarantee_text">Texto Explicativo da Garantia</label>
                    <input type="text" id="store_detail_guarantee_text" name="store_detail_guarantee_text" class="form-input" 
                           value="<?= View::e(Setting::get('store_detail_guarantee_text', 'Cada joia é acompanhada de certificado numerado de autenticidade, estojo de presente aveludado e garantia assegurada.')) ?>">
                </div>

                <!-- 3 Pilares do Card de Garantia na Página do Produto -->
                <div class="form-group full-width">
                    <div class="field-label-row">
                        <label for="store_guarantee_perk1_text">Pilar 1 da Joia (Ex: Banho & Certificado)</label>
                        <?= $renderToggle('store_guarantee_perk1_visible') ?>
                    </div>
                    <input type="text" id="store_guarantee_perk1_text" name="store_guarantee_perk1_text" class="form-input" 
                           value="<?= View::e(Setting::get('store_guarantee_perk1_text', 'Banho Nobre de Prata com Certificado e Garantia')) ?>">
                </div>

                <div class="form-group full-width">
                    <div class="field-label-row">
                        <label for="store_guarantee_perk2_text">Pilar 2 da Joia (Ex: Gemas Minerais)</label>
                        <?= $renderToggle('store_guarantee_perk2_visible') ?>
                    </div>
                    <input type="text" id="store_guarantee_perk2_text" name="store_guarantee_perk2_text" class="form-input" 
                           value="<?= View::e(Setting::get('store_guarantee_perk2_text', 'Gemas Minerais e Conchas 100% Autênticas')) ?>">
                </div>

                <div class="form-group full-width">
                    <div class="field-label-row">
                        <label for="store_guarantee_perk3_text">Pilar 3 da Joia (Ex: Estojo Aveludado)</label>
                        <?= $renderToggle('store_guarantee_perk3_visible') ?>
                    </div>
                    <input type="text" id="store_guarantee_perk3_text" name="store_guarantee_perk3_text" class="form-input" 
                           value="<?= View::e(Setting::get('store_guarantee_perk3_text', 'Acompanha Estojo Aveludado para Presente')) ?>">
                </div>

                <!-- Joias Relacionadas da Mesma Coleção -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); margin-top: 8px; padding-top: 14px;">
                    <div class="field-label-row">
                        <h5 style="color: #CBD0D6; margin: 0; font-size: 0.88rem; text-transform: uppercase;">
                            ✨ Joias Relacionadas da Mesma Coleção
                        </h5>
                        <?= $renderToggle('store_detail_show_related', '1') ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="store_related_eyebrow">Sobretítulo (Eyebrow)</label>
                        <?= $renderToggle('store_related_eyebrow_visible', '1') ?>
                    </div>
                    <input type="text" id="store_related_eyebrow" name="store_related_eyebrow" class="form-input" 
                           value="<?= View::e(Setting::get('store_related_eyebrow', 'DESCUBRA MAIS')) ?>">
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="store_related_title">Título da Seção de Relacionadas</label>
                        <?= $renderToggle('store_related_title_visible', '1') ?>
                    </div>
                    <input type="text" id="store_related_title" name="store_related_title" class="form-input" 
                           value="<?= View::e(Setting::get('store_related_title', 'Outras Peças da Coleção')) ?>">
                </div>

                <div class="form-group">
                    <label for="store_related_btn_text">Texto do Botão 'Conhecer Peça'</label>
                    <input type="text" id="store_related_btn_text" name="store_related_btn_text" class="form-input" 
                           value="<?= View::e(Setting::get('store_related_btn_text', 'Conhecer Peça ↗')) ?>">
                </div>

                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.05);">
                    <div class="field-label-row">
                        <label>Botão de Compartilhamento da URL Própria</label>
                        <?= $renderToggle('store_detail_show_share', '1') ?>
                    </div>
                    <span class="field-hint">Permite copiar o link direto da joia ou compartilhar no WhatsApp.</span>
                </div>

                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.05);">
                    <div class="field-label-row">
                        <label>Destaque de Desconto no Pix na Página de Detalhes</label>
                        <?= $renderToggle('store_detail_show_pix_discount', '1') ?>
                    </div>
                    <span class="field-hint">Exibe o selo de 5% de desconto no Pix junto ao valor principal.</span>
                </div>

                <div class="form-group full-width <?= Setting::get('store_detail_show_installments', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_detail_installments_text">Texto Informativo de Parcelamento</label>
                        <?= $renderToggle('store_detail_show_installments', '1') ?>
                    </div>
                    <input type="text" id="store_detail_installments_text" name="store_detail_installments_text" class="form-input" 
                           value="<?= View::e(Setting::get('store_detail_installments_text', 'Em até 6x sem juros nos canais oficiais homologados')) ?>">
                </div>

            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 4: SEÇÃO EDITORIAL (TRANSPARÊNCIA & VANTAGENS) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="bsec-reasons">
            <div class="section-card-header">
                <div class="section-icon">💎</div>
                <div>
                    <h3>Seção Editorial: Transparência & Conveniência</h3>
                    <p>O bloco explicativo que detalha as vantagens dos canais oficiais (cupons, proteção, garantia).</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width" style="background: rgba(212, 186, 136, 0.05); padding: 12px 16px; border-radius: 8px; border: 1px solid rgba(212, 186, 136, 0.15);">
                    <div class="field-label-row">
                        <label style="color: #ffffff; font-weight: 600; font-size: 0.9rem;">Exibir a Seção Editorial completa na Boutique</label>
                        <?= $renderToggle('store_reasons_visible') ?>
                    </div>
                    <span class="field-hint" style="margin-top: 2px;">Ativa ou desativa todo o bloco de transparência ao final da página.</span>
                </div>

                <div class="form-group <?= Setting::get('store_reasons_eyebrow_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_reasons_eyebrow">Sobretítulo (Eyebrow)</label>
                        <?= $renderToggle('store_reasons_eyebrow_visible') ?>
                    </div>
                    <input type="text" id="store_reasons_eyebrow" name="store_reasons_eyebrow" class="form-input" 
                           value="<?= View::e(Setting::get('store_reasons_eyebrow', 'TRANSPARÊNCIA & CONVENIÊNCIA')) ?>">
                </div>

                <div class="form-group <?= Setting::get('store_reasons_title_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_reasons_title">Título da Seção</label>
                        <?= $renderToggle('store_reasons_title_visible') ?>
                    </div>
                    <input type="text" id="store_reasons_title" name="store_reasons_title" class="form-input" 
                           value="<?= View::e(Setting::get('store_reasons_title', 'Sua Experiência Segura em')) ?>">
                </div>

                <div class="form-group <?= Setting::get('store_reasons_title_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="store_reasons_highlight">Destaque em Degradê Dourado</label>
                    <input type="text" id="store_reasons_highlight" name="store_reasons_highlight" class="form-input" 
                           value="<?= View::e(Setting::get('store_reasons_highlight', 'Nossos Canais Oficiais')) ?>">
                </div>

                <div class="form-group full-width <?= Setting::get('store_reasons_desc_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_reasons_desc">Descrição Editorial</label>
                        <?= $renderToggle('store_reasons_desc_visible') ?>
                    </div>
                    <textarea id="store_reasons_desc" name="store_reasons_desc" class="form-textarea" rows="2"><?= View::e(Setting::get('store_reasons_desc', 'Para oferecer os melhores cupons de frete, proteção estendida ao consumidor e máxima velocidade de entrega, conectamos nossa boutique aos principais ecossistemas homologados.')) ?></textarea>
                </div>

                <!-- 4 Cards de Vantagens Individuais -->
                <div class="form-group <?= Setting::get('store_reason_1_visible', '1') !== '1' ? 'is-dimmed' : '' ?>" style="background: rgba(255,255,255,0.02); padding: 14px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                    <div class="field-label-row">
                        <label>Card 1: Garantia Estendida</label>
                        <?= $renderToggle('store_reason_1_visible') ?>
                    </div>
                    <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                        <input type="text" name="store_reason_1_icon" class="form-input" style="width: 60px;" value="<?= View::e(Setting::get('store_reason_1_icon', '🛡️')) ?>">
                        <input type="text" name="store_reason_1_title" class="form-input" value="<?= View::e(Setting::get('store_reason_1_title', 'Garantia de Entrega Estendida')) ?>">
                    </div>
                    <textarea name="store_reason_1_desc" class="form-textarea" rows="2"><?= View::e(Setting::get('store_reason_1_desc', 'Seu pagamento só é liberado à Divinium após você receber o pacote em casa e conferir a perfeição da sua joia.')) ?></textarea>
                </div>

                <div class="form-group <?= Setting::get('store_reason_2_visible', '1') !== '1' ? 'is-dimmed' : '' ?>" style="background: rgba(255,255,255,0.02); padding: 14px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                    <div class="field-label-row">
                        <label>Card 2: Cupons & Cashback</label>
                        <?= $renderToggle('store_reason_2_visible') ?>
                    </div>
                    <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                        <input type="text" name="store_reason_2_icon" class="form-input" style="width: 60px;" value="<?= View::e(Setting::get('store_reason_2_icon', '🎫')) ?>">
                        <input type="text" name="store_reason_2_title" class="form-input" value="<?= View::e(Setting::get('store_reason_2_title', 'Cupons de Frete & Descontos')) ?>">
                    </div>
                    <textarea name="store_reason_2_desc" class="form-textarea" rows="2"><?= View::e(Setting::get('store_reason_2_desc', 'Aproveite cupons de frete grátis, cashback e vantagens exclusivas nos canais homologados.')) ?></textarea>
                </div>

                <div class="form-group <?= Setting::get('store_reason_3_visible', '1') !== '1' ? 'is-dimmed' : '' ?>" style="background: rgba(255,255,255,0.02); padding: 14px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                    <div class="field-label-row">
                        <label>Card 3: Qualidade & Banho de Prata</label>
                        <?= $renderToggle('store_reason_3_visible') ?>
                    </div>
                    <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                        <input type="text" name="store_reason_3_icon" class="form-input" style="width: 60px;" value="<?= View::e(Setting::get('store_reason_3_icon', '💎')) ?>">
                        <input type="text" name="store_reason_3_title" class="form-input" value="<?= View::e(Setting::get('store_reason_3_title', 'Qualidade & Banho de Prata')) ?>">
                    </div>
                    <textarea name="store_reason_3_desc" class="form-textarea" rows="2"><?= View::e(Setting::get('store_reason_3_desc', 'Todas as peças enviadas acompanham o certificado oficial de autenticidade da Divinium e estojo nobre.')) ?></textarea>
                </div>

                <div class="form-group <?= Setting::get('store_reason_4_visible', '1') !== '1' ? 'is-dimmed' : '' ?>" style="background: rgba(255,255,255,0.02); padding: 14px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                    <div class="field-label-row">
                        <label>Card 4: Atendimento VIP</label>
                        <?= $renderToggle('store_reason_4_visible') ?>
                    </div>
                    <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                        <input type="text" name="store_reason_4_icon" class="form-input" style="width: 60px;" value="<?= View::e(Setting::get('store_reason_4_icon', '💬')) ?>">
                        <input type="text" name="store_reason_4_title" class="form-input" value="<?= View::e(Setting::get('store_reason_4_title', 'Atendimento & Concierge VIP')) ?>">
                    </div>
                    <textarea name="store_reason_4_desc" class="form-textarea" rows="2"><?= View::e(Setting::get('store_reason_4_desc', 'Dúvidas sobre o modelo ou tamanho? Nosso atendimento via WhatsApp ou chat responde em minutos.')) ?></textarea>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 5: ATENDIMENTO VIP / WHATSAPP -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="bsec-whatsapp">
            <div class="section-card-header">
                <div class="section-icon">💬</div>
                <div>
                    <h3>Banner de Atendimento & Concierge VIP</h3>
                    <p>Chamada para falar com a consultora de gemas minerais antes da compra.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width" style="background: rgba(212, 186, 136, 0.05); padding: 12px 16px; border-radius: 8px; border: 1px solid rgba(212, 186, 136, 0.15);">
                    <div class="field-label-row">
                        <label style="color: #ffffff; font-weight: 600; font-size: 0.9rem;">Exibir Banner do Concierge WhatsApp na Boutique</label>
                        <?= $renderToggle('store_whatsapp_visible') ?>
                    </div>
                    <span class="field-hint" style="margin-top: 2px;">Ativa ou desativa a caixa de atendimento com botão direto para WhatsApp.</span>
                </div>

                <div class="form-group <?= Setting::get('store_whatsapp_title_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_whatsapp_title">Título da Chamada</label>
                        <?= $renderToggle('store_whatsapp_title_visible') ?>
                    </div>
                    <input type="text" id="store_whatsapp_title" name="store_whatsapp_title" class="form-input" 
                           value="<?= View::e(Setting::get('store_whatsapp_title', 'Precisa de ajuda para escolher sua joia?')) ?>">
                </div>

                <div class="form-group <?= Setting::get('store_whatsapp_btn_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_whatsapp_btn">Texto do Botão de Contato</label>
                        <?= $renderToggle('store_whatsapp_btn_visible') ?>
                    </div>
                    <input type="text" id="store_whatsapp_btn" name="store_whatsapp_btn" class="form-input" 
                           value="<?= View::e(Setting::get('store_whatsapp_btn', 'Conversar no WhatsApp')) ?>">
                </div>

                <div class="form-group full-width <?= Setting::get('store_whatsapp_desc_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_whatsapp_desc">Descrição do Atendimento</label>
                        <?= $renderToggle('store_whatsapp_desc_visible') ?>
                    </div>
                    <input type="text" id="store_whatsapp_desc" name="store_whatsapp_desc" class="form-input" 
                           value="<?= View::e(Setting::get('store_whatsapp_desc', 'Fale diretamente com nossa consultora de gemas minerais antes de concluir sua compra.')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="store_whatsapp_url">Link Completo ou Número do WhatsApp</label>
                    <input type="text" id="store_whatsapp_url" name="store_whatsapp_url" class="form-input" 
                           value="<?= View::e(Setting::get('store_whatsapp_url', 'https://wa.me/5511999999999?text=Ol%C3%A1,%20estou%20na%20loja%20da%20Divinium%20e%20gostaria%20de%20tirar%20d%C3%BAvidas')) ?>">
                    <span class="field-hint">Pode ser URL completa com mensagem personalizada ou número internacional (ex: 5511999999999).</span>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 6: ESTADO VAZIO -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="bsec-empty">
            <div class="section-card-header">
                <div class="section-icon">🔍</div>
                <div>
                    <h3>Mensagem de Estado Vazio</h3>
                    <p>Exibida quando um filtro não retorna peças ou o catálogo está sendo atualizado.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width <?= Setting::get('store_empty_title_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_empty_title">Título da Mensagem Vazia</label>
                        <?= $renderToggle('store_empty_title_visible') ?>
                    </div>
                    <input type="text" id="store_empty_title" name="store_empty_title" class="form-input" 
                           value="<?= View::e(Setting::get('store_empty_title', 'Nenhuma Joia Disponível nesta Seleção')) ?>">
                </div>

                <div class="form-group full-width <?= Setting::get('store_empty_desc_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_empty_desc">Texto Explicativo</label>
                        <?= $renderToggle('store_empty_desc_visible') ?>
                    </div>
                    <input type="text" id="store_empty_desc" name="store_empty_desc" class="form-input" 
                           value="<?= View::e(Setting::get('store_empty_desc', 'Nossas coleções estão sendo atualizadas nos bastidores. Experimente outro filtro ou volte em instantes.')) ?>">
                </div>

                <div class="form-group <?= Setting::get('store_empty_btn_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_empty_btn">Texto do Botão de Retorno</label>
                        <?= $renderToggle('store_empty_btn_visible') ?>
                    </div>
                    <input type="text" id="store_empty_btn" name="store_empty_btn" class="form-input" 
                           value="<?= View::e(Setting::get('store_empty_btn', 'Voltar à Página Inicial')) ?>">
                </div>

                <div class="form-group">
                    <label for="store_empty_url">Destino do Botão</label>
                    <input type="text" id="store_empty_url" name="store_empty_url" class="form-input" 
                           value="<?= View::e(Setting::get('store_empty_url', '/')) ?>">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO: FUNDO & CORES DA BOUTIQUE -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="bsec-colors">
            <div class="section-card-header">
                <div class="section-icon">🎨</div>
                <div>
                    <h3>Fundo, Superfícies & Cores da Boutique (/loja)</h3>
                    <p>Personalize as tonalidades exclusivas da página de catálogo (/loja), ou aplique texturas nobres.</p>
                </div>
            </div>

            <!-- CARD INFORMATIVO COM ATALHO PARA CORES GLOBAIS -->
            <div class="form-group full-width" style="background: linear-gradient(135deg, rgba(167, 173, 180, 0.12) 0%, rgba(37, 37, 37, 0.95) 100%); border: 1px solid rgba(167, 173, 180, 0.35); border-radius: 10px; padding: 16px 20px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <strong style="color: #CBD0D6; font-size: 0.92rem; display: block; margin-bottom: 4px;">✦ Estúdio Global de Cores & Fundo de Todas as Páginas</strong>
                        <p style="font-size: 0.82rem; color: #b8b1a7; margin: 0; line-height: 1.4;">
                            Deseja alterar as cores de fundo, superfícies e tons de <strong>todas as páginas do site simultaneamente</strong> (Home, Boutique, Checkout, Produto, Políticas)?
                        </p>
                    </div>
                    <a href="/admin/temas" class="btn-admin-gold" style="font-size: 0.8rem; padding: 8px 14px; text-decoration: none;">
                        <span>Abrir Estúdio de Temas & Cores Globais ↗</span>
                    </a>
                </div>
            </div>

            <div class="form-grid">
                <!-- COR DE FUNDO DA BOUTIQUE -->
                <div class="form-group">
                    <label for="store_bg_color">Cor de Fundo da Boutique</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="picker_store_bg" 
                               value="<?= View::e(Setting::get('store_bg_color', Setting::get('custom_color_bg', '#F8F8F7'))) ?>" 
                               onchange="document.getElementById('store_bg_color').value = this.value;"
                               style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                        <input type="text" id="store_bg_color" name="store_bg_color" class="form-input" 
                               value="<?= View::e(Setting::get('store_bg_color', '')) ?>" 
                               placeholder="Ex: #F8F8F7 (Vazio = herda cor global)"
                               oninput="if(this.value.startsWith('#') && this.value.length === 7) document.getElementById('picker_store_bg').value = this.value;">
                    </div>
                    <span class="field-hint">Se deixar em branco, herda a cor global definida em Temas.</span>
                </div>

                <!-- COR DOS CARDS DE JOIAS -->
                <div class="form-group">
                    <label for="store_card_bg">Superfície dos Cards de Joias</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="picker_store_card" 
                               value="<?= View::e(Setting::get('store_card_bg', Setting::get('custom_color_surface', '#141312'))) ?>" 
                               onchange="document.getElementById('store_card_bg').value = this.value;"
                               style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                        <input type="text" id="store_card_bg" name="store_card_bg" class="form-input" 
                               value="<?= View::e(Setting::get('store_card_bg', '')) ?>" 
                               placeholder="Ex: #141312 (Vazio = herda superfície global)"
                               oninput="if(this.value.startsWith('#') && this.value.length === 7) document.getElementById('picker_store_card').value = this.value;">
                    </div>
                    <span class="field-hint">Aplica-se ao fundo de cada vitrine de produto da loja.</span>
                </div>

                <!-- IMAGEM / TEXTURA DE FUNDO DA BOUTIQUE -->
                <div class="form-group full-width">
                    <label for="store_bg_image">Imagem de Fundo ou Textura da Boutique</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" id="store_bg_image" name="store_bg_image" class="form-input" 
                               value="<?= View::e(Setting::get('store_bg_image', '')) ?>" 
                               placeholder="Ex: /assets/images/backgrounds/marble-noir.svg ou link externo">
                        <button type="button" class="btn-admin-secondary" onclick="document.getElementById('store_bg_image').value = '';">
                            <span>Limpar</span>
                        </button>
                    </div>
                    <span class="field-hint">A imagem é exibida com transparência e elegância atrás do catálogo.</span>
                </div>

                <!-- PRESETS RÁPIDOS -->
                <div class="form-group full-width">
                    <label style="font-size: 0.78rem; text-transform: uppercase; color: #CBD0D6; font-weight: 700; display: block; margin-bottom: 8px;">✦ Texturas Rápidas de Alta Joalheria:</label>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <button type="button" class="btn-admin-cancel" style="font-size: 0.78rem; padding: 6px 12px;" onclick="document.getElementById('store_bg_image').value = '/assets/images/backgrounds/marble-noir.svg';">
                            Mármore Noir & Prata
                        </button>
                        <button type="button" class="btn-admin-cancel" style="font-size: 0.78rem; padding: 6px 12px;" onclick="document.getElementById('store_bg_image').value = '/assets/images/backgrounds/carbon-noir.svg';">
                            Carbono Texturizado
                        </button>
                        <button type="button" class="btn-admin-cancel" style="font-size: 0.78rem; padding: 6px 12px;" onclick="document.getElementById('store_bg_image').value = '/assets/images/backgrounds/stardust-noir.svg';">
                            Poeira de Estrelas ✦
                        </button>
                        <button type="button" class="btn-admin-cancel" style="font-size: 0.78rem; padding: 6px 12px;" onclick="document.getElementById('store_bg_image').value = '/assets/images/hero/hero-caminhos.jpg';">
                            Joia Divinium
                        </button>
                    </div>
                </div>

                <!-- CONTRASTE & ENQUADRAMENTO -->
                <div class="form-group">
                    <label for="store_bg_overlay">Escurecimento de Contraste</label>
                    <input type="range" id="store_bg_overlay" name="store_bg_overlay" min="0.10" max="0.95" step="0.05" 
                           value="<?= View::e(Setting::get('store_bg_overlay', '0.75')) ?>" 
                           style="width: 100%; accent-color: #737B85;">
                    <span class="field-hint">Garante legibilidade dos textos e cards sobre a imagem.</span>
                </div>

                <div class="form-group">
                    <label for="store_bg_repeat">Modo de Exibição</label>
                    <select id="store_bg_repeat" name="store_bg_repeat" class="form-select">
                        <option value="cover" <?= Setting::get('store_bg_repeat', 'cover') === 'cover' ? 'selected' : '' ?>>Cobrir Inteira (Cover)</option>
                        <option value="repeat" <?= Setting::get('store_bg_repeat', '') === 'repeat' ? 'selected' : '' ?>>Repetir Textura (Seamless Repeat)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- BARRA FLUTUANTE DE SALVAMENTO -->
        <div class="editor-save-dock">
            <div class="save-dock-inner">
                <div class="save-dock-status">
                    <span>💡 Todas as preferências de exibição e conteúdo salvas são refletidas instantaneamente na Boutique.</span>
                </div>
                <button type="submit" class="btn-admin-gold" id="saveBoutiqueBtn">
                    <span>Salvar Alterações da Boutique</span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function switchBoutiqueSection(sectionId, btn) {
    document.querySelectorAll('#boutiqueForm .editor-section-card').forEach(card => card.classList.remove('active'));
    document.querySelectorAll('.editor-nav-tabs .tab-btn').forEach(b => b.classList.remove('active'));

    const target = document.getElementById(sectionId);
    if (target) {
        target.classList.add('active');
    }
    if (btn) {
        btn.classList.add('active');
    }
}

function handleToggleSwitch(chk) {
    const parentLabel = chk.closest('.element-toggle-switch');
    const textSpan = parentLabel ? parentLabel.querySelector('.toggle-text') : null;
    const formGroup = chk.closest('.form-group') || chk.closest('.pilar-editor-subcard');

    if (chk.checked) {
        if (parentLabel) {
            parentLabel.classList.add('is-visible');
            parentLabel.classList.remove('is-hidden');
        }
        if (textSpan) textSpan.textContent = 'Exibindo';
        if (formGroup) formGroup.classList.remove('is-dimmed');
    } else {
        if (parentLabel) {
            parentLabel.classList.remove('is-visible');
            parentLabel.classList.add('is-hidden');
        }
        if (textSpan) textSpan.textContent = 'Oculto';
        if (formGroup) formGroup.classList.add('is-dimmed');
    }
}

function syncColorInput(picker, targetId) {
    const target = document.getElementById(targetId);
    if (target) {
        target.value = picker.value;
    }
}

function syncPickerInput(textInput, pickerId) {
    const picker = document.getElementById(pickerId);
    if (picker && /^#[0-9A-Fa-f]{6}$/.test(textInput.value.trim())) {
        picker.value = textInput.value.trim();
    }
}

function setTrustIcon(pilarNum, icon) {
    const el = document.getElementById('store_bag_trust' + pilarNum + '_icon');
    if (el) {
        el.value = icon;
        updateProductElementPreview();
    }
}

function updateProductElementPreview() {
    // 1. Qty
    const qLbl = document.getElementById('store_qty_label')?.value || 'Qtd:';
    const qColor = document.getElementById('store_qty_text_color')?.value || '#ffffff';
    const qBg = document.getElementById('store_qty_bg_color')?.value || 'rgba(0, 0, 0, 0.35)';
    const qBorder = document.getElementById('store_qty_border_color')?.value || 'rgba(255, 255, 255, 0.18)';
    
    const prevQLbl = document.getElementById('prev_qty_label');
    if (prevQLbl) {
        prevQLbl.textContent = qLbl;
        prevQLbl.style.color = qColor;
    }
    const prevQBox = document.getElementById('prev_qty_box');
    if (prevQBox) {
        prevQBox.style.background = qBg;
        prevQBox.style.borderColor = qBorder;
        prevQBox.querySelectorAll('span').forEach(s => s.style.color = qColor);
    }

    // 2. Add to Cart
    const cTxt = document.getElementById('store_add_to_cart_btn_text')?.value || 'Adicionar à Sacola';
    const cBg = document.getElementById('store_add_to_cart_bg_color')?.value || 'rgba(202, 161, 92, 0.08)';
    const cColor = document.getElementById('store_add_to_cart_text_color')?.value || '#ffffff';
    const cIcoColor = document.getElementById('store_add_to_cart_icon_color')?.value || '#CBD0D6';
    const cBorder = document.getElementById('store_add_to_cart_border_color')?.value || '#CBD0D6';

    const prevCartBtn = document.getElementById('prev_add_to_cart_btn');
    if (prevCartBtn) {
        prevCartBtn.style.background = cBg;
        prevCartBtn.style.color = cColor;
        prevCartBtn.style.borderColor = cBorder;
    }
    const prevCartTxt = document.getElementById('prev_add_to_cart_text');
    if (prevCartTxt) prevCartTxt.textContent = cTxt;
    const prevCartSvg = document.getElementById('prev_add_to_cart_svg');
    if (prevCartSvg) prevCartSvg.setAttribute('stroke', cIcoColor);

    // 3. Buy Now
    const bTxt = document.getElementById('store_buy_now_btn_text')?.value || 'Comprar Agora';
    const bBg = document.getElementById('store_buy_now_bg_color')?.value || '#CBD0D6';
    const bColor = document.getElementById('store_buy_now_text_color')?.value || '#1a1918';
    const bIcoColor = document.getElementById('store_buy_now_icon_color')?.value || '#1a1918';
    const bBorder = document.getElementById('store_buy_now_border_color')?.value || '#737B85';

    const prevBuyBtn = document.getElementById('prev_buy_now_btn');
    if (prevBuyBtn) {
        prevBuyBtn.style.background = bBg;
        prevBuyBtn.style.color = bColor;
        prevBuyBtn.style.borderColor = bBorder;
    }
    const prevBuyTxt = document.getElementById('prev_buy_now_text');
    if (prevBuyTxt) prevBuyTxt.textContent = bTxt;
    const prevBuySvg = document.getElementById('prev_buy_now_svg');
    if (prevBuySvg) prevBuySvg.setAttribute('stroke', bIcoColor);

    // 4. Trust Pillars
    for (let i = 1; i <= 3; i++) {
        const ico = document.getElementById('store_bag_trust' + i + '_icon')?.value || '';
        const txt = document.getElementById('store_bag_trust' + i + '_text')?.value || '';
        const tColor = document.getElementById('store_bag_trust' + i + '_text_color')?.value || '#a0988c';
        const iColor = document.getElementById('store_bag_trust' + i + '_icon_color')?.value || '#CBD0D6';
        const bg = document.getElementById('store_bag_trust' + i + '_bg_color')?.value || 'transparent';
        const border = document.getElementById('store_bag_trust' + i + '_border_color')?.value || 'transparent';

        const badge = document.getElementById('prev_trust' + i + '_badge');
        const icoEl = document.getElementById('prev_trust' + i + '_icon');
        const txtEl = document.getElementById('prev_trust' + i + '_text');

        if (badge) {
            badge.style.background = (bg !== 'transparent') ? bg : '';
            badge.style.border = (border !== 'transparent') ? '1px solid ' + border : '';
        }
        if (icoEl) {
            icoEl.textContent = ico;
            icoEl.style.color = iColor;
        }
        if (txtEl) {
            txtEl.textContent = txt;
            txtEl.style.color = tColor;
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateProductElementPreview();
});

async function handleBoutiqueSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('saveBoutiqueBtn');
    btn.disabled = true;
    btn.innerText = 'Salvando Elementos...';

    const form = document.getElementById('boutiqueForm');
    const formData = new FormData(form);

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
    if (csrfToken && !formData.has('_csrf_token')) {
        formData.append('_csrf_token', csrfToken);
    }

    const payload = {};
    formData.forEach((v, k) => { payload[k] = v; });

    try {
        const response = await fetch('/admin/boutique/salvar', {
            method: 'POST',
            body: JSON.stringify(payload),
            headers: {
                'Content-Type': 'application/json',
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
            throw new Error('O servidor respondeu com um formato inesperado. Se você passou muito tempo inativo, recarregue a página.');
        }

        if (data.success) {
            btn.innerText = '✓ Salvo com Sucesso!';
            btn.style.background = '#737B85';
            setTimeout(() => {
                btn.disabled = false;
                btn.innerText = 'Salvar Alterações da Boutique';
                btn.style.background = '';
            }, 2500);
        } else {
            if (data.auth_required) {
                alert('Sua sessão de administrador expirou. Por favor, faça login novamente para salvar as alterações.');
            } else {
                alert(data.error || 'Erro ao salvar elementos da Boutique.');
            }
            btn.disabled = false;
            btn.innerText = 'Salvar Alterações da Boutique';
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
        btn.disabled = false;
        btn.innerText = 'Salvar Alterações da Boutique';
    }
}
</script>