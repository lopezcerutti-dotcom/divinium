<?php
/**
 * VIEW: EDITOR DA LANDING PAGE E TEXTOS DO SITE (PAINEL ADMINISTRATIVO)
 * Permite editar qualquer elemento textual, títulos, preços e mídias da loja e da Home.
 */
use App\Models\Setting;
use App\Core\View;

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
?>

<div class="landing-editor-container">

    <!-- BARRA SUPERIOR DE AÇÕES RÁPIDAS -->
    <div class="editor-header-bar">
        <div class="editor-header-info">
            <h2>Editor de Textos & Conteúdo do Site</h2>
            <p>Edite 100% dos textos, títulos, chamadas, links, fotos e selos de qualquer seção do site com efeito imediato.</p>
        </div>
        <div class="editor-header-actions">
            <a href="/" target="_blank" class="btn-admin-secondary">
                <span>↗ Ver Home Ao Vivo</span>
            </a>
            <a href="/loja" target="_blank" class="btn-admin-secondary">
                <span>🛍️ Ver Loja Ao Vivo</span>
            </a>
            <button type="button" class="btn-admin-gold" onclick="document.getElementById('landingPageForm').requestSubmit()">
                <span>Salvar Todas as Alterações</span>
            </button>
        </div>
    </div>

    <!-- NAVEGAÇÃO ENTRE SEÇÕES (TABS VISUAIS) -->
    <div class="editor-nav-tabs">
        <button type="button" class="tab-btn active" onclick="switchSection('sec-sections-order', this)">
            <span>🔀 Ordem da Home</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-theme-colors', this)" style="border: 1px solid rgba(202,161,92,0.4); background: rgba(202,161,92,0.08);">
            <span>🎨 Cores & Fundo (Todas as Páginas)</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-identity', this)">
            <span>💎 Identidade & Logo</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-banner', this)">
            <span>📢 Top Banner</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-header-cart', this)">
            <span>🧭 Topo & Menu</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-mobile-menu', this)">
            <span>📱 Menu Mobile</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-hero', this)">
            <span>👑 Hero & Títulos</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-stage', this)">
            <span>🎠 Carrossel Principal</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-about', this)">
            <span>📜 Manifesto & Sobre</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-collections', this)">
            <span>✨ Vitrine & Coleções</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-services', this)">
            <span>🛡️ Serviços & Garantia</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-heritage', this)">
            <span>👑 Peça Única</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-testimonials', this)">
            <span>💬 Depoimentos</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-buttons', this)">
            <span>🔘 Botões & Estilos</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-store', this)">
            <span>🛍️ Textos da Loja</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-institutional', this)">
            <span>🏛️ Institucional & CDC</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-seo', this)">
            <span>🌐 SEO & Metatags</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-footer', this)">
            <span>📞 Rodapé & LGPD</span>
        </button>
    </div>

    <!-- FORMULÁRIO PRINCIPAL DO CMS -->
    <form id="landingPageForm" action="/admin/landing-page/salvar" method="POST" onsubmit="handleEditorSubmit(event)">
        <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">

        <!-- ============================================================== -->
        <!-- SEÇÃO: ORDEM E VISIBILIDADE DAS SEÇÕES DA HOME (PILAR 6) -->
        <!-- ============================================================== -->
        <div class="editor-section-card active" id="sec-sections-order">
            <div class="section-card-header">
                <div class="section-icon">🔀</div>
                <div>
                    <h3>Ordem & Visibilidade das Seções da Página Inicial</h3>
                    <p>Defina a sequência hierárquica e ative ou oculte individualmente qualquer uma das 6 seções da Home.</p>
                </div>
            </div>

            <?php
                $rawOrder = Setting::get('home_sections_order', 'hero,about,collections,services,heritage,testimonials');
                // Migração transparente de legado 'stage' para 'collections'
                $rawOrder = str_replace('stage', 'collections', $rawOrder);
                $sectionsList = [
                    'hero' => ['name' => 'Abertura / Hero Principal & Carrossel', 'desc' => 'Dobra inicial com chamada nobre, fotos editoriais e botões de ação', 'tab' => 'sec-hero', 'icon' => '👑'],
                    'about' => ['name' => 'Manifesto & Sobre a Marca', 'desc' => 'Narrativa conceitual de design, resina nobre, ouro e foto editorial', 'tab' => 'sec-about', 'icon' => '📜'],
                    'collections' => ['name' => 'Vitrine de Coleções & Lançamentos', 'desc' => 'Grade de joias da Home, filtros de categoria e card lifestyle', 'tab' => 'sec-collections', 'icon' => '✨'],
                    'services' => ['name' => 'Serviços, Garantia & Autoria', 'desc' => 'Grade com 3 serviços exclusivos de sintonia, cuidado e garantia', 'tab' => 'sec-services', 'icon' => '🛡️'],
                    'heritage' => ['name' => 'Peça Única & Tradição', 'desc' => 'Destaque especial da joia autoral com selo de exclusividade', 'tab' => 'sec-heritage', 'icon' => '💎'],
                    'testimonials' => ['name' => 'Depoimentos & Experiência VIP', 'desc' => 'Avaliações de clientes verificadas, foto e miniaturas', 'tab' => 'sec-testimonials', 'icon' => '💬']
                ];
                $orderKeys = array_filter(array_map('trim', explode(',', $rawOrder)));
                foreach (array_keys($sectionsList) as $k) {
                    if (!in_array($k, $orderKeys)) $orderKeys[] = $k;
                }
                $currentOrderStr = implode(',', $orderKeys);
            ?>

            <input type="hidden" id="home_sections_order" name="home_sections_order" value="<?= View::e($currentOrderStr) ?>">

            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(212,186,136,0.2); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                    <span style="font-size: 0.85rem; color: #CBD0D6; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        ✦ Sequência Atual da Página Inicial:
                    </span>
                    <button type="button" class="btn-admin-secondary" style="font-size: 0.76rem; padding: 6px 12px;" onclick="resetSectionsOrder()">
                        Restaurar Ordem Padrão
                    </button>
                </div>

                <div id="sectionsOrderContainer" style="display: flex; flex-direction: column; gap: 12px;">
                    <?php foreach ($orderKeys as $idx => $secKey): 
                        if (!isset($sectionsList[$secKey])) continue;
                        $secInfo = $sectionsList[$secKey];
                        $visKey = 'home_' . $secKey . '_visible';
                        $isSecVisible = Setting::get($visKey, '1') === '1';
                    ?>
                    <div class="section-order-row" data-section-key="<?= $secKey ?>" style="display: flex; align-items: center; justify-content: space-between; background: #161513; border: 1px solid <?= $isSecVisible ? 'rgba(212,186,136,0.3)' : 'rgba(255,255,255,0.06)' ?>; border-radius: 10px; padding: 14px 18px; gap: 14px; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <span class="order-badge" style="width: 28px; height: 28px; border-radius: 50%; background: rgba(212,186,136,0.15); color: #CBD0D6; border: 1px solid #CBD0D6; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem;">
                                <?= $idx + 1 ?>
                            </span>
                            <span style="font-size: 1.3rem;"><?= $secInfo['icon'] ?></span>
                            <div>
                                <strong style="color: #ffffff; font-size: 0.95rem; display: block;"><?= $secInfo['name'] ?></strong>
                                <span style="color: #8e8880; font-size: 0.78rem;"><?= $secInfo['desc'] ?></span>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 12px;">
                            <button type="button" class="btn-slide-order" onclick="moveSectionOrder('<?= $secKey ?>', -1)" title="Mover seção para cima">▲</button>
                            <button type="button" class="btn-slide-order" onclick="moveSectionOrder('<?= $secKey ?>', 1)" title="Mover seção para baixo">▼</button>

                            <button type="button" class="btn-admin-secondary" style="font-size: 0.75rem; padding: 6px 10px;" onclick="switchSection('<?= $secInfo['tab'] ?>', document.querySelector('.editor-nav-tabs button[onclick*=\'<?= $secInfo['tab'] ?>\']'))">
                                ✎ Editar Textos
                            </button>

                            <div style="margin-left: 8px;">
                                <?= $renderToggle($visKey) ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO: CORES & FUNDO DE TODAS AS PÁGINAS (NOVO ESTÚDIO CENTRAL) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-theme-colors">
            <div class="section-card-header">
                <div class="section-icon">🎨</div>
                <div>
                    <h3>Cores & Fundo de Todas as Páginas do Site</h3>
                    <p>Controle global sobre o fundo (dark noir ou marfim), superfícies dos cards, ouro nobre e imagens/texturas de fundo aplicadas em todo o site ou por página.</p>
                </div>
            </div>

            <!-- PALETAS RÁPIDAS DE ALTA JOALHERIA -->
            <div class="form-group full-width" style="background: rgba(202,161,92,0.06); border: 1px solid rgba(202,161,92,0.25); border-radius: 12px; padding: 18px 22px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 12px;">
                    <div>
                        <strong style="color: #CBD0D6; font-size: 0.92rem; text-transform: uppercase; letter-spacing: 0.05em; display: block;">
                            ✦ Aplicar Paleta Quiet Luxury Pronta (1 Clique):
                        </strong>
                        <span style="font-size: 0.78rem; color: #b8b1a7;">Clique para preencher automaticamente as tonalidades harmônicas em todos os campos.</span>
                    </div>
                    <a href="/admin/temas" class="btn-admin-secondary" style="font-size: 0.78rem; padding: 6px 12px; text-decoration: none;">
                        <span>Ver Painel Completo de Temas ↗</span>
                    </a>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                    <button type="button" class="btn-admin-cancel" style="background: #141312; border: 1px solid rgba(202,161,92,0.4); text-align: left; padding: 10px 14px;" onclick="applyThemePalette('gold')">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <span style="width: 14px; height: 14px; border-radius: 50%; background: #CBD0D6; border: 1px solid #fff;"></span>
                            <strong style="color: #CBD0D6; font-size: 0.84rem;">Noir Ateliê Ouro</strong>
                        </div>
                        <span style="font-size: 0.72rem; color: #8e8880;">Fundo #252525 • Ouro #A7ADB4</span>
                    </button>

                    <button type="button" class="btn-admin-cancel" style="background: #1e1e1d; border: 1px solid rgba(255,255,255,0.2); text-align: left; padding: 10px 14px;" onclick="applyThemePalette('silver')">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <span style="width: 14px; height: 14px; border-radius: 50%; background: #ffffff; border: 1px solid #CBD0D6;"></span>
                            <strong style="color: #ffffff; font-size: 0.84rem;">Marfim & Prata de Lei</strong>
                        </div>
                        <span style="font-size: 0.72rem; color: #8e8880;">Fundo #FBF9F5 • Prata #FFFFFF</span>
                    </button>

                    <button type="button" class="btn-admin-cancel" style="background: #18120e; border: 1px solid rgba(212,139,96,0.4); text-align: left; padding: 10px 14px;" onclick="applyThemePalette('rose')">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <span style="width: 14px; height: 14px; border-radius: 50%; background: #d48b60; border: 1px solid #fff;"></span>
                            <strong style="color: #d48b60; font-size: 0.84rem;">Terracota & Aço</strong>
                        </div>
                        <span style="font-size: 0.72rem; color: #8e8880;">Fundo #120D0A • Terracota #D48B60</span>
                    </button>

                    <button type="button" class="btn-admin-cancel" style="background: #091710; border: 1px solid rgba(56,161,105,0.4); text-align: left; padding: 10px 14px;" onclick="applyThemePalette('emerald')">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <span style="width: 14px; height: 14px; border-radius: 50%; background: #38a169; border: 1px solid #fff;"></span>
                            <strong style="color: #38a169; font-size: 0.84rem;">Esmeralda Imperial</strong>
                        </div>
                        <span style="font-size: 0.72rem; color: #8e8880;">Fundo #06130D • Esmeralda #38A169</span>
                    </button>
                </div>
            </div>

            <!-- 1. CORES GLOBAIS DA MARCA -->
            <div class="form-group full-width" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 8px; margin-bottom: 16px;">
                <h4 style="color: #CBD0D6; margin: 0; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    ✦ 1. Cores Globais da Marca (Aplicam-se em Todas as Páginas do Site)
                </h4>
            </div>

            <div class="form-grid" style="margin-bottom: 24px;">
                <!-- Fundo Base Global -->
                <div class="form-group">
                    <label for="lp_custom_color_bg">Fundo Base Global (Dark Noir)</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="picker_lp_color_bg" 
                               value="<?= View::e(Setting::get('custom_color_bg', '#252525')) ?>" 
                               oninput="document.getElementById('lp_custom_color_bg').value = this.value; updateThemeColorsLivePreview();"
                               style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                        <input type="text" id="lp_custom_color_bg" name="custom_color_bg" class="form-input" 
                               value="<?= View::e(Setting::get('custom_color_bg', '#252525')) ?>" 
                               oninput="if(this.value.startsWith('#') && this.value.length === 7) document.getElementById('picker_lp_color_bg').value = this.value; updateThemeColorsLivePreview();">
                    </div>
                    <span class="field-hint">Aplica-se ao fundo de todas as páginas da loja (Home, Boutique, Produto, Checkout, etc.)</span>
                </div>

                <!-- Superfície dos Cards -->
                <div class="form-group">
                    <label for="lp_custom_color_surface">Superfície dos Cards & Caixas</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="picker_lp_color_surface" 
                               value="<?= View::e(Setting::get('custom_color_surface', '#141312')) ?>" 
                               oninput="document.getElementById('lp_custom_color_surface').value = this.value; updateThemeColorsLivePreview();"
                               style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                        <input type="text" id="lp_custom_color_surface" name="custom_color_surface" class="form-input" 
                               value="<?= View::e(Setting::get('custom_color_surface', '#141312')) ?>" 
                               oninput="if(this.value.startsWith('#') && this.value.length === 7) document.getElementById('picker_lp_color_surface').value = this.value; updateThemeColorsLivePreview();">
                    </div>
                    <span class="field-hint">Aplica-se às caixas de produtos, formulários e fichas técnicas.</span>
                </div>

                <!-- Cor Primária Ouro -->
                <div class="form-group">
                    <label for="lp_custom_color_primary">Cor Primária (Ouro / Botões Principais)</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="picker_lp_color_primary" 
                               value="<?= View::e(Setting::get('custom_color_primary', '#A7ADB4')) ?>" 
                               oninput="document.getElementById('lp_custom_color_primary').value = this.value; updateThemeColorsLivePreview();"
                               style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                        <input type="text" id="lp_custom_color_primary" name="custom_color_primary" class="form-input" 
                               value="<?= View::e(Setting::get('custom_color_primary', '#A7ADB4')) ?>" 
                               oninput="if(this.value.startsWith('#') && this.value.length === 7) document.getElementById('picker_lp_color_primary').value = this.value; updateThemeColorsLivePreview();">
                    </div>
                    <span class="field-hint">Define o tom dos botões de compra, títulos e detalhes nobres.</span>
                </div>

                <!-- Cor de Realce -->
                <div class="form-group">
                    <label for="lp_custom_color_accent">Cor de Realce (Hover & Acentos)</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="picker_lp_color_accent" 
                               value="<?= View::e(Setting::get('custom_color_accent', '#E0C080')) ?>" 
                               oninput="document.getElementById('lp_custom_color_accent').value = this.value; updateThemeColorsLivePreview();"
                               style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                        <input type="text" id="lp_custom_color_accent" name="custom_color_accent" class="form-input" 
                               value="<?= View::e(Setting::get('custom_color_accent', '#E0C080')) ?>" 
                               oninput="if(this.value.startsWith('#') && this.value.length === 7) document.getElementById('picker_lp_color_accent').value = this.value; updateThemeColorsLivePreview();">
                    </div>
                    <span class="field-hint">Brilho dourado ao passar o cursor do mouse sobre links e botões.</span>
                </div>

                <!-- Texto Principal -->
                <div class="form-group">
                    <label for="lp_custom_color_text">Texto Principal (Leitura & Títulos)</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="picker_lp_color_text" 
                               value="<?= View::e(Setting::get('custom_color_text', '#F4EFEA')) ?>" 
                               oninput="document.getElementById('lp_custom_color_text').value = this.value; updateThemeColorsLivePreview();"
                               style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                        <input type="text" id="lp_custom_color_text" name="custom_color_text" class="form-input" 
                               value="<?= View::e(Setting::get('custom_color_text', '#F4EFEA')) ?>" 
                               oninput="if(this.value.startsWith('#') && this.value.length === 7) document.getElementById('picker_lp_color_text').value = this.value; updateThemeColorsLivePreview();">
                    </div>
                    <span class="field-hint">Cor dos títulos e corpo de texto principal.</span>
                </div>

                <!-- Texto Suave -->
                <div class="form-group">
                    <label for="lp_custom_color_text_muted">Texto Suave (Secundário / Legendas)</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="picker_lp_color_text_muted" 
                               value="<?= View::e(Setting::get('custom_color_text_muted', '#9E978E')) ?>" 
                               oninput="document.getElementById('lp_custom_color_text_muted').value = this.value; updateThemeColorsLivePreview();"
                               style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                        <input type="text" id="lp_custom_color_text_muted" name="custom_color_text_muted" class="form-input" 
                               value="<?= View::e(Setting::get('custom_color_text_muted', '#9E978E')) ?>" 
                               oninput="if(this.value.startsWith('#') && this.value.length === 7) document.getElementById('picker_lp_color_text_muted').value = this.value; updateThemeColorsLivePreview();">
                    </div>
                    <span class="field-hint">Legendas, especificações técnicas e notas de rodapé.</span>
                </div>
            </div>

            <!-- 2. CORES ESPECÍFICAS POR PÁGINA (OPCIONAL) -->
            <div style="background: rgba(0,0,0,0.25); border: 1px solid rgba(202,161,92,0.2); border-radius: 12px; padding: 18px 20px; margin-bottom: 24px;">
                <div style="margin-bottom: 14px;">
                    <strong style="color: #ded7cf; font-size: 0.88rem; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                        <span>✦ 2. Personalização de Cores Específicas por Página</span>
                        <span style="font-size: 0.68rem; color: #CBD0D6; background: rgba(202,161,92,0.18); padding: 2px 8px; border-radius: 6px;">Opcional</span>
                    </strong>
                    <p style="font-size: 0.78rem; color: #8e8880; margin: 4px 0 0;">
                        Deixe em branco para que cada página herde automaticamente as cores globais configuradas acima.
                    </p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                    <!-- Boutique -->
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 12px 14px;">
                        <span style="font-size: 0.76rem; font-weight: 700; color: #CBD0D6; text-transform: uppercase; display: block; margin-bottom: 8px;">🛍️ Boutique (/loja)</span>
                        <div class="form-group" style="margin-bottom: 8px;">
                            <label style="font-size: 0.74rem;">Fundo da Loja:</label>
                            <input type="text" name="store_bg_color" class="form-input" 
                                   value="<?= View::e(Setting::get('store_bg_color', '')) ?>" 
                                   placeholder="Vazio = herda cor global">
                        </div>
                        <div class="form-group" style="margin: 0;">
                            <label style="font-size: 0.74rem;">Cards de Joias:</label>
                            <input type="text" name="store_card_bg" class="form-input" 
                                   value="<?= View::e(Setting::get('store_card_bg', '')) ?>" 
                                   placeholder="Vazio = herda superfície global">
                        </div>
                    </div>

                    <!-- Detalhes do Produto -->
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 12px 14px;">
                        <span style="font-size: 0.76rem; font-weight: 700; color: #CBD0D6; text-transform: uppercase; display: block; margin-bottom: 8px;">💎 Detalhes da Joia (/produto)</span>
                        <div class="form-group" style="margin-bottom: 8px;">
                            <label style="font-size: 0.74rem;">Fundo da Página:</label>
                            <input type="text" name="product_bg_color" class="form-input" 
                                   value="<?= View::e(Setting::get('product_bg_color', '')) ?>" 
                                   placeholder="Vazio = herda cor global">
                        </div>
                        <div class="form-group" style="margin: 0;">
                            <label style="font-size: 0.74rem;">Card da Galeria & Compra:</label>
                            <input type="text" name="product_card_bg" class="form-input" 
                                   value="<?= View::e(Setting::get('product_card_bg', '')) ?>" 
                                   placeholder="Vazio = herda superfície global">
                        </div>
                    </div>

                    <!-- Checkout Seguro -->
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 12px 14px;">
                        <span style="font-size: 0.76rem; font-weight: 700; color: #CBD0D6; text-transform: uppercase; display: block; margin-bottom: 8px;">🔒 Checkout Seguro (/checkout)</span>
                        <div class="form-group" style="margin-bottom: 8px;">
                            <label style="font-size: 0.74rem;">Fundo do Checkout:</label>
                            <input type="text" name="checkout_bg_color" class="form-input" 
                                   value="<?= View::e(Setting::get('checkout_bg_color', '')) ?>" 
                                   placeholder="Vazio = herda cor global">
                        </div>
                        <div class="form-group" style="margin: 0;">
                            <label style="font-size: 0.74rem;">Caixas de Pagamento:</label>
                            <input type="text" name="checkout_card_bg" class="form-input" 
                                   value="<?= View::e(Setting::get('checkout_card_bg', '')) ?>" 
                                   placeholder="Vazio = herda superfície global">
                        </div>
                    </div>

                    <!-- Institucional -->
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 12px 14px;">
                        <span style="font-size: 0.76rem; font-weight: 700; color: #CBD0D6; text-transform: uppercase; display: block; margin-bottom: 8px;">📜 Páginas Institucionais</span>
                        <div class="form-group" style="margin-bottom: 8px;">
                            <label style="font-size: 0.74rem;">Fundo das Políticas:</label>
                            <input type="text" name="institutional_bg_color" class="form-input" 
                                   value="<?= View::e(Setting::get('institutional_bg_color', '')) ?>" 
                                   placeholder="Vazio = herda cor global">
                        </div>
                        <div class="form-group" style="margin: 0;">
                            <label style="font-size: 0.74rem;">Card do Texto:</label>
                            <input type="text" name="institutional_card_bg" class="form-input" 
                                   value="<?= View::e(Setting::get('institutional_card_bg', '')) ?>" 
                                   placeholder="Vazio = herda superfície global">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. IMAGEM OU TEXTURA DE FUNDO -->
            <div class="form-group full-width" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 8px; margin-bottom: 16px;">
                <h4 style="color: #CBD0D6; margin: 0; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    ✦ 3. Imagem ou Textura de Fundo do Site & Páginas
                </h4>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start; margin-bottom: 24px;">
                <div>
                    <!-- 1. COR DE FUNDO BASE (SÓLIDA) -->
                    <div class="form-group full-width" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 14px 16px; margin-bottom: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <label for="lp_site_bg_color" style="font-size: 0.8rem; font-weight: 700; color: #CBD0D6; margin: 0; text-transform: uppercase; letter-spacing: 0.04em;">
                                🎨 Cor de Fundo Base do Site:
                            </label>
                            <button type="button" class="btn-admin-cancel" style="padding: 2px 8px; font-size: 0.7rem;" onclick="document.getElementById('lp_site_bg_color').value = ''; document.getElementById('picker_site_bg_color').value = '#252525'; updateThemeColorsLivePreview();">
                                ✕ Limpar / Herdar Padrão
                            </button>
                        </div>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input type="color" id="picker_site_bg_color" 
                                   value="<?= View::e(Setting::get('site_bg_color', Setting::get('custom_color_bg', '#252525'))) ?>" 
                                   oninput="document.getElementById('lp_site_bg_color').value = this.value; updateThemeColorsLivePreview();"
                                   style="width: 48px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                            <input type="text" id="lp_site_bg_color" name="site_bg_color" class="form-input" 
                                   value="<?= View::e(Setting::get('site_bg_color', Setting::get('custom_color_bg', '#252525'))) ?>" 
                                   placeholder="Ex: #F8F8F7 ou #252525"
                                   oninput="if(this.value.startsWith('#') && (this.value.length === 4 || this.value.length === 7)) document.getElementById('picker_site_bg_color').value = this.value; updateThemeColorsLivePreview();">
                        </div>
                        <div style="display: flex; gap: 8px; align-items: center; margin-top: 8px; flex-wrap: wrap;">
                            <span style="font-size: 0.7rem; color: #888;">Paleta Rápida:</span>
                            <button type="button" onclick="document.getElementById('lp_site_bg_color').value = '#F8F8F7'; document.getElementById('picker_site_bg_color').value = '#F8F8F7'; updateThemeColorsLivePreview();" title="Branco Perolado" style="padding: 3px 8px; font-size: 0.7rem; border-radius: 4px; background: #F8F8F7; color: #202020; border: 1px solid #ccc; cursor: pointer;">Pérola #F8F8F7</button>
                            <button type="button" onclick="document.getElementById('lp_site_bg_color').value = '#FFFFFF'; document.getElementById('picker_site_bg_color').value = '#FFFFFF'; updateThemeColorsLivePreview();" title="Branco Puro" style="padding: 3px 8px; font-size: 0.7rem; border-radius: 4px; background: #FFFFFF; color: #202020; border: 1px solid #ccc; cursor: pointer;">Branco #FFFFFF</button>
                            <button type="button" onclick="document.getElementById('lp_site_bg_color').value = '#F0F0EF'; document.getElementById('picker_site_bg_color').value = '#F0F0EF'; updateThemeColorsLivePreview();" title="Cinza Platina" style="padding: 3px 8px; font-size: 0.7rem; border-radius: 4px; background: #F0F0EF; color: #202020; border: 1px solid #aaa; cursor: pointer;">Platina #F0F0EF</button>
                            <button type="button" onclick="document.getElementById('lp_site_bg_color').value = '#252525'; document.getElementById('picker_site_bg_color').value = '#252525'; updateThemeColorsLivePreview();" title="Preto Grafite" style="padding: 3px 8px; font-size: 0.7rem; border-radius: 4px; background: #252525; color: #FFFFFF; border: 1px solid #555; cursor: pointer;">Grafite #252525</button>
                            <button type="button" onclick="document.getElementById('lp_site_bg_color').value = '#14161a'; document.getElementById('picker_site_bg_color').value = '#14161a'; updateThemeColorsLivePreview();" title="Dark Noir" style="padding: 3px 8px; font-size: 0.7rem; border-radius: 4px; background: #14161a; color: #FFFFFF; border: 1px solid #333; cursor: pointer;">Noir #14161a</button>
                        </div>
                        <span class="field-hint" style="margin-top: 6px;">Pode ser usada como cor sólida exclusiva ou combinada como base sob imagem/textura translúcida.</span>
                    </div>

                    <!-- 2. UPLOAD DIRETO OU URL DA IMAGEM GLOBAL -->
                    <div class="form-group full-width">
                        <label for="lp_site_bg_image">Imagem de Fundo Global (Aplica-se em Todo o Site):</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="text" id="lp_site_bg_image" name="site_bg_image" class="form-input" 
                                   value="<?= View::e(Setting::get('site_bg_image', '')) ?>" 
                                   placeholder="Ex: /assets/images/backgrounds/marble-noir.svg ou link externo"
                                   oninput="updateThemeColorsLivePreview()">
                            <label class="btn-admin-secondary" style="cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 9px 14px; white-space: nowrap; font-size: 0.8rem;">
                                <span>📁 Enviar Foto</span>
                                <input type="file" accept="image/*" style="display: none;" onchange="uploadSiteBgFromLanding(this)">
                            </label>
                            <button type="button" class="btn-admin-cancel" onclick="document.getElementById('lp_site_bg_image').value = ''; updateThemeColorsLivePreview();">
                                <span>Limpar</span>
                            </button>
                        </div>
                        <span class="field-hint">A foto ou textura se estende suavemente por todas as páginas com os containers translúcidos.</span>
                    </div>

                    <!-- Presets Rápidos de Texturas Nobres -->
                    <div class="form-group full-width">
                        <label style="font-size: 0.78rem; text-transform: uppercase; color: #CBD0D6; font-weight: 700; display: block; margin-bottom: 8px;">
                            ✦ Escolher Textura de Alta Joalheria Pronta:
                        </label>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 10px;">
                            <button type="button" class="btn-admin-cancel" style="padding: 8px 10px; text-align: left;" onclick="applyBgPresetLanding('/assets/images/backgrounds/linen-pearl.svg', 'repeat')">
                                <span style="font-size: 0.78rem; color: #fff; font-weight: 600; display: block;">Linho Nobre Claro</span>
                                <span style="font-size: 0.68rem; color: #8e8880;">Trama suave iluminada</span>
                            </button>
                            <button type="button" class="btn-admin-cancel" style="padding: 8px 10px; text-align: left;" onclick="applyBgPresetLanding('/assets/images/backgrounds/marble-noir.svg', 'repeat')">
                                <span style="font-size: 0.78rem; color: #fff; font-weight: 600; display: block;">Mármore Noir & Prata</span>
                                <span style="font-size: 0.68rem; color: #8e8880;">Veios nobres sutis</span>
                            </button>
                            <button type="button" class="btn-admin-cancel" style="padding: 8px 10px; text-align: left;" onclick="applyBgPresetLanding('/assets/images/backgrounds/carbon-noir.svg', 'repeat')">
                                <span style="font-size: 0.78rem; color: #fff; font-weight: 600; display: block;">Carbono Quiet Luxury</span>
                                <span style="font-size: 0.68rem; color: #8e8880;">Trama geométrica</span>
                            </button>
                            <button type="button" class="btn-admin-cancel" style="padding: 8px 10px; text-align: left;" onclick="applyBgPresetLanding('/assets/images/backgrounds/stardust-noir.svg', 'repeat')">
                                <span style="font-size: 0.78rem; color: #fff; font-weight: 600; display: block;">Poeira Prateada ✦</span>
                                <span style="font-size: 0.68rem; color: #8e8880;">Microbrilho discreto</span>
                            </button>
                            <button type="button" class="btn-admin-cancel" style="padding: 8px 10px; text-align: left;" onclick="applyBgPresetLanding('/assets/images/backgrounds/granite-silver.svg', 'repeat')">
                                <span style="font-size: 0.78rem; color: #fff; font-weight: 600; display: block;">Granito Mineral Fino</span>
                                <span style="font-size: 0.68rem; color: #8e8880;">Textura mineral natural</span>
                            </button>
                        </div>
                    </div>

                    <!-- Contraste & Modo de Exibição -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                        <div class="form-group" style="margin: 0;">
                            <label for="lp_site_bg_overlay">
                                Escurecimento / Sobreposição: <strong id="valOverlayLanding"><?= round(((float)Setting::get('site_bg_overlay', '0.75')) * 100) ?>%</strong>
                            </label>
                            <input type="range" id="lp_site_bg_overlay" name="site_bg_overlay" min="0.00" max="0.95" step="0.05" 
                                   value="<?= View::e(Setting::get('site_bg_overlay', '0.75')) ?>" 
                                   style="width: 100%; accent-color: #CBD0D6;"
                                   oninput="document.getElementById('valOverlayLanding').innerText = Math.round(this.value * 100) + '%'; updateThemeColorsLivePreview();">
                            <span class="field-hint">Garante legibilidade dos textos e intensidade da foto.</span>
                        </div>

                        <div class="form-group" style="margin: 0;">
                            <label for="lp_site_bg_repeat">Modo de Enquadramento</label>
                            <select id="lp_site_bg_repeat" name="site_bg_repeat" class="form-select" onchange="updateThemeColorsLivePreview()">
                                <option value="cover" <?= Setting::get('site_bg_repeat', 'cover') === 'cover' ? 'selected' : '' ?>>Cobrir Inteira (Cover fixo)</option>
                                <option value="contain" <?= Setting::get('site_bg_repeat', '') === 'contain' ? 'selected' : '' ?>>Conter Proporção (Contain)</option>
                                <option value="repeat" <?= Setting::get('site_bg_repeat', '') === 'repeat' ? 'selected' : '' ?>>Repetir Textura (Seamless)</option>
                            </select>
                        </div>
                    </div>

                    <!-- 3. CONTROLE DE SOMBRAS (ATIVAR, DESATIVAR, COR, INTENSIDADE & ALCANCE) -->
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(167, 173, 180, 0.25); border-radius: 10px; padding: 16px 18px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
                            <span style="font-size: 0.8rem; font-weight: 700; color: #FFFFFF; text-transform: uppercase; letter-spacing: 0.04em;">
                                ✦ Sombras de Superfície & Cards:
                            </span>
                            <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.78rem; color: #CBD0D6;">
                                <input type="hidden" name="site_shadows_enabled" value="0">
                                <input type="checkbox" id="site_shadows_enabled" name="site_shadows_enabled" value="1" 
                                       <?= Setting::get('site_shadows_enabled', '1') === '1' ? 'checked' : '' ?> 
                                       onchange="updateThemeColorsLivePreview()"
                                       style="width: 16px; height: 16px; accent-color: #A7ADB4; cursor: pointer;">
                                <strong id="lbl_shadows_status"><?= Setting::get('site_shadows_enabled', '1') === '1' ? 'Sombras Ativadas' : 'Sombras Desativadas' ?></strong>
                            </label>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px;">
                            <!-- Cor da Sombra -->
                            <div class="form-group" style="margin: 0;">
                                <label for="site_shadow_color" style="font-size: 0.74rem;">Cor da Sombra:</label>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <input type="color" id="picker_site_shadow_color" 
                                           value="<?= View::e(Setting::get('site_shadow_color', '#000000')) ?>" 
                                           oninput="document.getElementById('site_shadow_color').value = this.value; updateThemeColorsLivePreview();"
                                           style="width: 44px; height: 36px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                                    <input type="text" id="site_shadow_color" name="site_shadow_color" class="form-input" 
                                           value="<?= View::e(Setting::get('site_shadow_color', '#000000')) ?>" 
                                           oninput="if(this.value.startsWith('#') && (this.value.length === 4 || this.value.length === 7)) document.getElementById('picker_site_shadow_color').value = this.value; updateThemeColorsLivePreview();">
                                </div>
                            </div>

                            <!-- Intensidade da Sombra -->
                            <div class="form-group" style="margin: 0;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.74rem; margin-bottom: 4px;">
                                    <span>Intensidade:</span>
                                    <strong id="val_shadow_intensity"><?= round(((float)Setting::get('site_shadow_intensity', '0.25')) * 100) ?>%</strong>
                                </div>
                                <input type="range" id="site_shadow_intensity" name="site_shadow_intensity" min="0.05" max="0.80" step="0.05" 
                                       value="<?= View::e(Setting::get('site_shadow_intensity', '0.25')) ?>" 
                                       style="width: 100%; accent-color: #A7ADB4;"
                                       oninput="document.getElementById('val_shadow_intensity').innerText = Math.round(this.value * 100) + '%'; updateThemeColorsLivePreview();">
                                <span class="field-hint">Suave (15%) a Marcada (60%).</span>
                            </div>

                            <!-- Alcance / Blur da Sombra -->
                            <div class="form-group" style="margin: 0;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.74rem; margin-bottom: 4px;">
                                    <span>Desfoque (Blur):</span>
                                    <strong id="val_shadow_blur"><?= (int)Setting::get('site_shadow_blur', '24') ?>px</strong>
                                </div>
                                <input type="range" id="site_shadow_blur" name="site_shadow_blur" min="4" max="50" step="2" 
                                       value="<?= View::e(Setting::get('site_shadow_blur', '24')) ?>" 
                                       style="width: 100%; accent-color: #A7ADB4;"
                                       oninput="document.getElementById('val_shadow_blur').innerText = this.value + 'px'; updateThemeColorsLivePreview();">
                                <span class="field-hint">Espalhamento nas bordas dos cards.</span>
                            </div>
                        </div>
                    </div>

                    <!-- 4. EFEITOS VISUAIS DE FUNDO (GLASSMORPHISM, VINHETA & AMBIENT GLOW) -->
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(167, 173, 180, 0.25); border-radius: 10px; padding: 16px 18px; margin-bottom: 16px;">
                        <span style="font-size: 0.8rem; font-weight: 700; color: #FFFFFF; text-transform: uppercase; letter-spacing: 0.04em; display: block; margin-bottom: 12px;">
                            ✦ Efeitos Visuais de Fundo:
                        </span>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px;">
                            <!-- Efeito 1: Glassmorphism / Vidro Translúcido -->
                            <div style="background: rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 12px 14px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <strong style="font-size: 0.76rem; color: #CBD0D6;">Efeito Vidro (Glass)</strong>
                                    <label style="display: inline-flex; align-items: center; gap: 4px; cursor: pointer; font-size: 0.7rem; color: #aaa;">
                                        <input type="hidden" name="site_fx_glass_enabled" value="0">
                                        <input type="checkbox" id="site_fx_glass_enabled" name="site_fx_glass_enabled" value="1" 
                                               <?= Setting::get('site_fx_glass_enabled', '1') === '1' ? 'checked' : '' ?> 
                                               onchange="updateThemeColorsLivePreview()"
                                               style="width: 14px; height: 14px; accent-color: #A7ADB4; cursor: pointer;">
                                        <span>Ativar</span>
                                    </label>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 0.7rem; margin-bottom: 4px;">
                                    <span>Desfoque de Vidro:</span>
                                    <strong id="val_fx_glass_blur"><?= (int)Setting::get('site_fx_glass_blur', '12') ?>px</strong>
                                </div>
                                <input type="range" id="site_fx_glass_blur" name="site_fx_glass_blur" min="2" max="25" step="1" 
                                       value="<?= View::e(Setting::get('site_fx_glass_blur', '12')) ?>" 
                                       style="width: 100%; accent-color: #A7ADB4;"
                                       oninput="document.getElementById('val_fx_glass_blur').innerText = this.value + 'px'; updateThemeColorsLivePreview();">
                                <span class="field-hint" style="font-size: 0.65rem;">Desfoque elegante sob cabeçalho e cards.</span>
                            </div>

                            <!-- Efeito 2: Vinheta Periférica Suave -->
                            <div style="background: rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 12px 14px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <strong style="font-size: 0.76rem; color: #CBD0D6;">Vinheta Periférica</strong>
                                    <label style="display: inline-flex; align-items: center; gap: 4px; cursor: pointer; font-size: 0.7rem; color: #aaa;">
                                        <input type="hidden" name="site_fx_vignette_enabled" value="0">
                                        <input type="checkbox" id="site_fx_vignette_enabled" name="site_fx_vignette_enabled" value="1" 
                                               <?= Setting::get('site_fx_vignette_enabled', '0') === '1' ? 'checked' : '' ?> 
                                               onchange="updateThemeColorsLivePreview()"
                                               style="width: 14px; height: 14px; accent-color: #A7ADB4; cursor: pointer;">
                                        <span>Ativar</span>
                                    </label>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 0.7rem; margin-bottom: 4px;">
                                    <span>Intensidade:</span>
                                    <strong id="val_fx_vignette"><?= round(((float)Setting::get('site_fx_vignette_intensity', '0.40')) * 100) ?>%</strong>
                                </div>
                                <input type="range" id="site_fx_vignette_intensity" name="site_fx_vignette_intensity" min="0.10" max="0.80" step="0.05" 
                                       value="<?= View::e(Setting::get('site_fx_vignette_intensity', '0.40')) ?>" 
                                       style="width: 100%; accent-color: #A7ADB4;"
                                       oninput="document.getElementById('val_fx_vignette').innerText = Math.round(this.value * 100) + '%'; updateThemeColorsLivePreview();">
                                <span class="field-hint" style="font-size: 0.65rem;">Escurecimento suave nas bordas da tela.</span>
                            </div>

                            <!-- Efeito 3: Brilho Ambiente Nobre (Spotlight) -->
                            <div style="background: rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 12px 14px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <strong style="font-size: 0.76rem; color: #CBD0D6;">Brilho Ambiente (Glow)</strong>
                                    <label style="display: inline-flex; align-items: center; gap: 4px; cursor: pointer; font-size: 0.7rem; color: #aaa;">
                                        <input type="hidden" name="site_fx_glow_enabled" value="0">
                                        <input type="checkbox" id="site_fx_glow_enabled" name="site_fx_glow_enabled" value="1" 
                                               <?= Setting::get('site_fx_glow_enabled', '1') === '1' ? 'checked' : '' ?> 
                                               onchange="updateThemeColorsLivePreview()"
                                               style="width: 14px; height: 14px; accent-color: #A7ADB4; cursor: pointer;">
                                        <span>Ativar</span>
                                    </label>
                                </div>
                                <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 6px;">
                                    <input type="color" id="picker_fx_glow_color" 
                                           value="<?= View::e(Setting::get('site_fx_glow_color', '#A7ADB4')) ?>" 
                                           oninput="document.getElementById('site_fx_glow_color').value = this.value; updateThemeColorsLivePreview();"
                                           style="width: 32px; height: 26px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                                    <input type="text" id="site_fx_glow_color" name="site_fx_glow_color" class="form-input" 
                                           value="<?= View::e(Setting::get('site_fx_glow_color', '#A7ADB4')) ?>" 
                                           style="padding: 4px 8px; font-size: 0.72rem; height: 26px;"
                                           oninput="if(this.value.startsWith('#') && (this.value.length === 4 || this.value.length === 7)) document.getElementById('picker_fx_glow_color').value = this.value; updateThemeColorsLivePreview();">
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 0.7rem; margin-bottom: 4px;">
                                    <span>Intensidade:</span>
                                    <strong id="val_fx_glow"><?= round(((float)Setting::get('site_fx_glow_intensity', '0.14')) * 100) ?>%</strong>
                                </div>
                                <input type="range" id="site_fx_glow_intensity" name="site_fx_glow_intensity" min="0.05" max="0.40" step="0.01" 
                                       value="<?= View::e(Setting::get('site_fx_glow_intensity', '0.14')) ?>" 
                                       style="width: 100%; accent-color: #A7ADB4;"
                                       oninput="document.getElementById('val_fx_glow').innerText = Math.round(this.value * 100) + '%'; updateThemeColorsLivePreview();">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Lateral de Pré-Visualização Ao Vivo -->
                <div style="position: sticky; top: 20px;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #ded7cf; margin-bottom: 8px;">Prévia da Loja em Tempo Real:</label>
                    <div id="lpColorsLivePreviewCard" style="height: 380px; border-radius: 12px; border: 1px solid #CBD0D6; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; padding: 18px; box-shadow: 0 10px 25px rgba(0,0,0,0.6); background-color: #252525; transition: all 0.3s ease;">
                        <div id="lpColorsLivePreviewOverlay" style="position: absolute; inset: 0; background: rgba(13,12,11,0.75); z-index: 1; pointer-events: none; transition: background 0.3s ease;"></div>
                        <div style="position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center;">
                            <span id="lpPreviewLogo" style="font-family: var(--font-serif); font-size: 1.05rem; color: #fff; font-weight: 700; letter-spacing: 1px;">DIVINIUM</span>
                            <span style="font-size: 0.65rem; background: rgba(167,173,180,0.2); color: #CBD0D6; border: 1px solid #CBD0D6; padding: 2px 8px; border-radius: 10px; font-weight: 600;">PRÉVIA AO VIVO</span>
                        </div>
                        <div style="position: relative; z-index: 2; text-align: center; margin: auto 0;">
                            <h4 id="lpPreviewTitle" style="font-family: var(--font-serif); font-size: 1.25rem; color: #fff; margin: 0 0 4px;">Joias que Revelam</h4>
                            <span id="lpPreviewHighlight" style="font-family: var(--font-serif); font-size: 1.1rem; color: #CBD0D6; font-weight: 700; display: block; margin-bottom: 10px;">o Luxo Silencioso</span>
                            <div id="lpPreviewCardBox" style="display: inline-block; background: #141312; border: 1px solid rgba(255,255,255,0.12); border-radius: 8px; padding: 10px 16px; transition: all 0.3s ease;">
                                <span id="lpPreviewCardText" style="font-size: 0.72rem; color: #ded7cf;">✦ Alta Joalheria em Prata de Lei</span>
                            </div>
                        </div>
                        <div style="position: relative; z-index: 2; text-align: center;">
                            <button type="button" id="lpPreviewBtn" style="background: #CBD0D6; color: #000; border: none; font-size: 0.72rem; font-weight: 700; padding: 6px 14px; border-radius: 20px; cursor: default;">
                                Ver Coleção ↗
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO: IDENTIDADE VISUAL & LOGOTIPO (PILAR 1) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-identity">
            <div class="section-card-header">
                <div class="section-icon">💎</div>
                <div>
                    <h3>Identidade Visual, Logotipo & Favicon</h3>
                    <p>Controle completo sobre o logotipo (em tipografia de alta joalheria ou imagem gráfica transparente), favicon e monograma autoral.</p>
                </div>
            </div>

            <div class="form-grid">
                <!-- ATALHO PARA CORES & FUNDO -->
                <div class="form-group full-width" style="background: linear-gradient(135deg, rgba(202, 161, 92, 0.12) 0%, rgba(13, 15, 18, 0.95) 100%); border: 1px solid rgba(202, 161, 92, 0.35); border-radius: 10px; padding: 16px 20px; margin-bottom: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <div>
                            <strong style="color: #CBD0D6; font-size: 0.92rem; display: block; margin-bottom: 4px;">🎨 Personalizar Cores & Fundo de Todas as Páginas</strong>
                            <p style="font-size: 0.82rem; color: #b8b1a7; margin: 0; line-height: 1.4;">
                                Edite as cores da marca (fundo dark noir, superfícies de cards, ouro nobre, textos) ou aplique imagens e texturas de fundo em todas as páginas do site.
                            </p>
                        </div>
                        <a href="/admin/temas" class="btn-admin-gold" style="font-size: 0.8rem; padding: 8px 14px; text-decoration: none;">
                            <span>Abrir Cores, Fundo & Temas ↗</span>
                        </a>
                    </div>
                </div>

                <!-- Modo do Logotipo -->
                <div class="form-group full-width" style="background: rgba(255,255,255,0.02); padding: 16px; border-radius: 10px; border: 1px solid rgba(212,186,136,0.2);">
                    <label style="font-size: 0.9rem; color: #CBD0D6; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 8px;">
                        Formato do Logotipo no Cabeçalho
                    </label>
                    <div style="display: flex; gap: 24px; align-items: center; flex-wrap: wrap;">
                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; color: #ffffff; font-size: 0.88rem;">
                            <input type="radio" name="site_logo_type" value="text" <?= Setting::get('site_logo_type', 'text') === 'text' ? 'checked' : '' ?> onchange="toggleLogoFields()" style="accent-color: #CBD0D6; width: 18px; height: 18px;">
                            <span>Tipografia Editorial Nobre (Texto Puro estilizado)</span>
                        </label>
                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; color: #ffffff; font-size: 0.88rem;">
                            <input type="radio" name="site_logo_type" value="image" <?= Setting::get('site_logo_type') === 'image' ? 'checked' : '' ?> onchange="toggleLogoFields()" style="accent-color: #CBD0D6; width: 18px; height: 18px;">
                            <span>Imagem / Arquivo Gráfico (PNG transparente, SVG ou WebP)</span>
                        </label>
                    </div>
                </div>

                <!-- Campos de Texto do Logo -->
                <div class="form-group" id="logoTextGroup1">
                    <label for="id_header_logo_text">Nome Principal da Marca (Texto)</label>
                    <input type="text" id="id_header_logo_text" name="header_logo_text" class="form-input"
                           value="<?= View::e(Setting::get('header_logo_text', 'DIVINIUM')) ?>"
                           placeholder="DIVINIUM">
                    <span class="field-hint">Exibido com tracking expandido e fonte serifada imperial.</span>
                </div>

                <div class="form-group" id="logoTextGroup2">
                    <label for="id_header_logo_sub">Subtítulo / Selo da Marca</label>
                    <input type="text" id="id_header_logo_sub" name="header_logo_sub" class="form-input"
                           value="<?= View::e(Setting::get('header_logo_sub', 'HAUTE JOAILLERIE')) ?>"
                           placeholder="HAUTE JOAILLERIE">
                    <span class="field-hint">Ex: HAUTE JOAILLERIE, JOALHERIA AUTORAL ou JOIAS DE LUXO.</span>
                </div>

                <!-- Campos de Imagem do Logo -->
                <div class="form-group full-width" id="logoImageGroup" style="<?= Setting::get('site_logo_type', 'text') === 'text' ? 'display: none;' : '' ?>">
                    <div style="background: rgba(0,0,0,0.3); border: 1px dashed rgba(212,186,136,0.3); border-radius: 10px; padding: 20px;">
                        <h4 style="color: #fff; margin: 0 0 10px; font-size: 0.95rem;">Arquivo do Logotipo Gráfico</h4>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
                            <div>
                                <label style="font-size: 0.8rem; color: #ded7cf;">Logotipo para Fundo Escuro (Dourado / Branco):</label>
                                <div style="display: flex; gap: 8px; margin-top: 6px;">
                                    <input type="text" id="site_logo_image_dark" name="site_logo_image_dark" class="form-input"
                                           value="<?= View::e(Setting::get('site_logo_image_dark', '')) ?>"
                                           placeholder="/assets/images/logos/logo-gold.svg">
                                    <label class="btn-admin-gold" style="padding: 0 14px; cursor: pointer; display: flex; align-items: center; white-space: nowrap; font-size: 0.78rem;">
                                        <span>📁 Upload</span>
                                        <input type="file" accept="image/*,.svg" style="display: none;" onchange="uploadMediaFile(this, 'site_logo_image_dark', 'logos')">
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label style="font-size: 0.8rem; color: #ded7cf;">Logotipo Alternativo (Fundo Claro / Faturas):</label>
                                <div style="display: flex; gap: 8px; margin-top: 6px;">
                                    <input type="text" id="site_logo_image_light" name="site_logo_image_light" class="form-input"
                                           value="<?= View::e(Setting::get('site_logo_image_light', '')) ?>"
                                           placeholder="/assets/images/logos/logo-dark.svg">
                                    <label class="btn-admin-secondary" style="padding: 0 14px; cursor: pointer; display: flex; align-items: center; white-space: nowrap; font-size: 0.78rem;">
                                        <span>📁 Upload</span>
                                        <input type="file" accept="image/*,.svg" style="display: none;" onchange="uploadMediaFile(this, 'site_logo_image_light', 'logos')">
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-top: 14px;">
                            <div>
                                <label for="site_logo_height" style="font-size: 0.8rem; color: #ded7cf;">Altura da Imagem do Logo:</label>
                                <select id="site_logo_height" name="site_logo_height" class="form-input" style="margin-top: 6px;">
                                    <option value="32px" <?= Setting::get('site_logo_height') === '32px' ? 'selected' : '' ?>>32px — Minimalista Discreto</option>
                                    <option value="38px" <?= Setting::get('site_logo_height', '38px') === '38px' ? 'selected' : '' ?>>38px — Padrão Equilibrado</option>
                                    <option value="46px" <?= Setting::get('site_logo_height') === '46px' ? 'selected' : '' ?>>46px — Destaque Nobre</option>
                                    <option value="54px" <?= Setting::get('site_logo_height') === '54px' ? 'selected' : '' ?>>54px — Grande Impacto</option>
                                </select>
                            </div>

                            <div>
                                <label for="site_logo_alignment" style="font-size: 0.8rem; color: #ded7cf;">Alinhamento no Cabeçalho Desktop:</label>
                                <select id="site_logo_alignment" name="site_logo_alignment" class="form-input" style="margin-top: 6px;">
                                    <option value="center" <?= Setting::get('site_logo_alignment', 'center') === 'center' ? 'selected' : '' ?>>Centralizado (Design Simétrico de Alta Joalheria)</option>
                                    <option value="left" <?= Setting::get('site_logo_alignment') === 'left' ? 'selected' : '' ?>>À Esquerda (Layout E-Commerce Convencional)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Favicon & Monograma -->
                <div class="form-group">
                    <label for="site_favicon_url">Ícone de Navegação (Favicon da Aba do Navegador)</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="site_favicon_url" name="site_favicon_url" class="form-input"
                               value="<?= View::e(Setting::get('site_favicon_url', '/assets/images/favicon.ico')) ?>"
                               placeholder="/assets/images/favicon.ico">
                        <label class="btn-admin-gold" style="padding: 0 14px; cursor: pointer; display: flex; align-items: center; white-space: nowrap; font-size: 0.78rem;">
                            <span>📁 Upload</span>
                            <input type="file" accept="image/*,.ico" style="display: none;" onchange="uploadMediaFile(this, 'site_favicon_url', 'favicons')">
                        </label>
                    </div>
                    <span class="field-hint">Exibido na aba do navegador e nos favoritos (.ico, .png 32x32 ou .svg).</span>
                </div>

                <div class="form-group">
                    <label for="site_monogram_url">Monograma / Selo Diamantino da Marca</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="site_monogram_url" name="site_monogram_url" class="form-input"
                               value="<?= View::e(Setting::get('site_monogram_url', '/assets/images/brand/monogram.svg')) ?>"
                               placeholder="/assets/images/brand/monogram.svg">
                        <label class="btn-admin-gold" style="padding: 0 14px; cursor: pointer; display: flex; align-items: center; white-space: nowrap; font-size: 0.78rem;">
                            <span>📁 Upload</span>
                            <input type="file" accept="image/*,.svg" style="display: none;" onchange="uploadMediaFile(this, 'site_monogram_url', 'logos')">
                        </label>
                    </div>
                    <span class="field-hint">Símbolo gráfico utilizado em certificados de garantia e fundos de embalagens.</span>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 1: BARRA DE ANÚNCIOS SUPERIOR (TOP BANNER) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-banner">
            <div class="section-card-header">
                <div class="section-icon">📢</div>
                <div>
                    <h3>Barra Superior de Aviso (Top Announcement)</h3>
                    <p>A faixa de boas-vindas exibida no topo absoluto do site com benefícios e ofertas.</p>
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="top_banner_text">Texto do Anúncio Superior</label>
                    <input type="text" id="top_banner_text" name="top_banner_text" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('top_banner_text', '✨ Frete Cortesia com Seguro para todo o Brasil • 5% de Desconto no Pix • Até 10x sem juros')) ?>"
                           placeholder="✨ Frete Cortesia com Seguro para todo o Brasil • 5% de Desconto no Pix • Até 10x sem juros">
                    <span class="field-hint">Dica: Use emojis e símbolos como ✨ ou • para enriquecer a mensagem.</span>
                </div>

                <div class="form-checkbox-row full-width">
                    <input type="checkbox" id="top_banner_active" name="top_banner_active" value="1" 
                           <?= Setting::get('top_banner_active', '1') === '1' ? 'checked' : '' ?>
                           style="width: 18px; height: 18px; accent-color: #CBD0D6;">
                    <label for="top_banner_active">Exibir esta barra no topo do site</label>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 2: CABEÇALHO, MENU, SACOLA & COOKIES -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-header-cart">
            <div class="section-card-header">
                <div class="section-icon">🧭</div>
                <div>
                    <h3>Cabeçalho, Menu, Gaveta da Sacola & Aviso de Cookies</h3>
                    <p>Altere os textos do logotipo, menus de navegação, carrinho lateral e consentimento de dados.</p>
                </div>
            </div>

            <div class="form-grid">
                <!-- Logotipo Superior -->
                <div class="form-group">
                    <label for="header_logo_text">Texto Principal do Logo</label>
                    <input type="text" id="header_logo_text" name="header_logo_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('header_logo_text', 'DIVINIUM')) ?>"
                           placeholder="DIVINIUM">
                </div>

                <div class="form-group">
                    <label for="header_logo_sub">Subtítulo do Logo</label>
                    <input type="text" id="header_logo_sub" name="header_logo_sub" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('header_logo_sub', 'HAUTE JOAILLERIE')) ?>"
                           placeholder="HAUTE JOAILLERIE">
                </div>

                <!-- Links da Navegação Principal Desktop -->
                <div class="form-group full-width" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 6px; margin-top: 10px;">
                    <h4 style="color: #CBD0D6; margin: 0; font-size: 0.88rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        🧭 Itens do Menu Desktop (Topo Esquerda)
                    </h4>
                </div>

                <!-- Link Início -->
                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_link_home">Menu 1: Rótulo</label>
                        <?= $renderToggle('nav_link_home_visible') ?>
                    </div>
                    <input type="text" id="nav_link_home" name="nav_link_home" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_home', 'Início')) ?>"
                           placeholder="Início">
                </div>
                <div class="form-group">
                    <label for="nav_link_home_url">Menu 1: Destino (URL)</label>
                    <input type="text" id="nav_link_home_url" name="nav_link_home_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_home_url', '/')) ?>"
                           placeholder="/">
                </div>

                <!-- Link Loja -->
                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_link_store">Menu 2: Rótulo</label>
                        <?= $renderToggle('nav_link_store_visible') ?>
                    </div>
                    <input type="text" id="nav_link_store" name="nav_link_store" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_store', 'Loja')) ?>"
                           placeholder="Loja">
                </div>
                <div class="form-group">
                    <label for="nav_link_store_url">Menu 2: Destino (URL)</label>
                    <input type="text" id="nav_link_store_url" name="nav_link_store_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_store_url', '/loja')) ?>"
                           placeholder="/loja">
                </div>

                <!-- Link Sobre -->
                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_link_about">Menu 3: Rótulo</label>
                        <?= $renderToggle('nav_link_about_visible') ?>
                    </div>
                    <input type="text" id="nav_link_about" name="nav_link_about" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_about', 'Sobre')) ?>"
                           placeholder="Sobre">
                </div>
                <div class="form-group">
                    <label for="nav_link_about_url">Menu 3: Destino (URL)</label>
                    <input type="text" id="nav_link_about_url" name="nav_link_about_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_about_url', '/#about')) ?>"
                           placeholder="/#about">
                </div>

                <!-- Link Coleções -->
                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_link_collections">Menu 4: Dropdown Coleções</label>
                        <?= $renderToggle('nav_link_collections_visible') ?>
                    </div>
                    <input type="text" id="nav_link_collections" name="nav_link_collections" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_collections', 'Coleções')) ?>"
                           placeholder="Coleções">
                </div>
                <div class="form-group">
                    <label for="nav_link_collections_url">Menu 4: Destino (URL)</label>
                    <input type="text" id="nav_link_collections_url" name="nav_link_collections_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_collections_url', '/loja')) ?>"
                           placeholder="/loja">
                </div>

                <!-- Link Garantia / Serviços -->
                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_link_services">Menu 5: Rótulo</label>
                        <?= $renderToggle('nav_link_services_visible') ?>
                    </div>
                    <input type="text" id="nav_link_services" name="nav_link_services" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_services', 'Garantia')) ?>"
                           placeholder="Garantia">
                </div>
                <div class="form-group">
                    <label for="nav_link_services_url">Menu 5: Destino (URL)</label>
                    <input type="text" id="nav_link_services_url" name="nav_link_services_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_services_url', '/#services')) ?>"
                           placeholder="/#services">
                </div>

                <!-- Link Customizado Adicional -->
                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_custom_link1_text">Menu 6 (Opcional): Rótulo</label>
                        <?= $renderToggle('nav_custom_link1_visible', '0') ?>
                    </div>
                    <input type="text" id="nav_custom_link1_text" name="nav_custom_link1_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_custom_link1_text', 'Certificado')) ?>"
                           placeholder="Ex: Certificado">
                </div>
                <div class="form-group">
                    <label for="nav_custom_link1_url">Menu 6: Destino (URL)</label>
                    <input type="text" id="nav_custom_link1_url" name="nav_custom_link1_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_custom_link1_url', '/garantia-e-cuidados')) ?>"
                           placeholder="/garantia-e-cuidados">
                </div>

                <!-- Sub-itens do Menu Coleções -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 10px; margin-top: 10px;">
                    <h4 style="color: #CBD0D6; margin: 0; font-size: 0.88rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        💎 Sub-itens da Gaveta / Dropdown de Coleções
                    </h4>
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_sub_all">Dropdown 1: Rótulo</label>
                        <?= $renderToggle('nav_sub_all_visible') ?>
                    </div>
                    <input type="text" id="nav_sub_all" name="nav_sub_all" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_all', 'Ver Todas as Peças')) ?>"
                           placeholder="Ver Todas as Peças">
                </div>
                <div class="form-group">
                    <label for="nav_sub_all_url">Dropdown 1: Destino (URL)</label>
                    <input type="text" id="nav_sub_all_url" name="nav_sub_all_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_all_url', '/loja')) ?>"
                           placeholder="/loja">
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_sub_bracelets">Dropdown 2: Braceletes</label>
                        <?= $renderToggle('nav_sub_bracelets_visible') ?>
                    </div>
                    <input type="text" id="nav_sub_bracelets" name="nav_sub_bracelets" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_bracelets', 'Braceletes Exclusivos')) ?>"
                           placeholder="Braceletes Exclusivos">
                </div>
                <div class="form-group">
                    <label for="nav_sub_bracelets_url">Dropdown 2: Destino (URL)</label>
                    <input type="text" id="nav_sub_bracelets_url" name="nav_sub_bracelets_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_bracelets_url', '/loja#braceletes')) ?>"
                           placeholder="/loja#braceletes">
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_sub_necklaces">Dropdown 3: Colares</label>
                        <?= $renderToggle('nav_sub_necklaces_visible') ?>
                    </div>
                    <input type="text" id="nav_sub_necklaces" name="nav_sub_necklaces" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_necklaces', 'Colares & Pingentes')) ?>"
                           placeholder="Colares & Pingentes">
                </div>
                <div class="form-group">
                    <label for="nav_sub_necklaces_url">Dropdown 3: Destino (URL)</label>
                    <input type="text" id="nav_sub_necklaces_url" name="nav_sub_necklaces_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_necklaces_url', '/loja#colares')) ?>"
                           placeholder="/loja#colares">
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_sub_rings">Dropdown 4: Anéis</label>
                        <?= $renderToggle('nav_sub_rings_visible') ?>
                    </div>
                    <input type="text" id="nav_sub_rings" name="nav_sub_rings" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_rings', 'Anéis & Alianças')) ?>"
                           placeholder="Anéis & Alianças">
                </div>
                <div class="form-group">
                    <label for="nav_sub_rings_url">Dropdown 4: Destino (URL)</label>
                    <input type="text" id="nav_sub_rings_url" name="nav_sub_rings_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_rings_url', '/loja#aneis')) ?>"
                           placeholder="/loja#aneis">
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_sub_earrings">Dropdown 5: Brincos</label>
                        <?= $renderToggle('nav_sub_earrings_visible') ?>
                    </div>
                    <input type="text" id="nav_sub_earrings" name="nav_sub_earrings" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_earrings', 'Brincos & Segundo Furo')) ?>"
                           placeholder="Brincos & Segundo Furo">
                </div>
                <div class="form-group">
                    <label for="nav_sub_earrings_url">Dropdown 5: Destino (URL)</label>
                    <input type="text" id="nav_sub_earrings_url" name="nav_sub_earrings_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_earrings_url', '/loja#brincos')) ?>"
                           placeholder="/loja#brincos">
                </div>

                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 10px; margin-top: 10px;">
                    <h4 style="color: #CBD0D6; margin: 0; font-size: 0.88rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        🛍️ Gaveta Lateral da Sacola de Compras
                    </h4>
                </div>

                <!-- Gaveta Lateral da Sacola -->
                <div class="form-group">
                    <label for="cart_drawer_title">Título da Gaveta da Sacola</label>
                    <input type="text" id="cart_drawer_title" name="cart_drawer_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('cart_drawer_title', 'Sua Sacola de Escolhas')) ?>"
                           placeholder="Sua Sacola de Escolhas">
                </div>

                <div class="form-group">
                    <label for="cart_drawer_empty">Mensagem de Sacola Vazia</label>
                    <input type="text" id="cart_drawer_empty" name="cart_drawer_empty" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('cart_drawer_empty', 'Sua sacola está vazia no momento.')) ?>"
                           placeholder="Sua sacola está vazia no momento.">
                </div>

                <div class="form-group">
                    <label for="cart_drawer_discover">Botão Descobrir Coleção (Sacola Vazia)</label>
                    <input type="text" id="cart_drawer_discover" name="cart_drawer_discover" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('cart_drawer_discover', 'Descobrir Coleção')) ?>"
                           placeholder="Descobrir Coleção">
                </div>

                <div class="form-group">
                    <label for="cart_security_badge">Selo de Segurança da Sacola</label>
                    <input type="text" id="cart_security_badge" name="cart_security_badge" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('cart_security_badge', '🔒 Pagamento 100% Seguro via ASAAS • Pix com QR Code Instantâneo')) ?>"
                           placeholder="🔒 Pagamento 100% Seguro via ASAAS • Pix com QR Code Instantâneo">
                </div>

                <div class="form-group">
                    <label for="cart_checkout_btn">Botão Finalizar Compra</label>
                    <input type="text" id="cart_checkout_btn" name="cart_checkout_btn" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('cart_checkout_btn', 'Finalizar Compra')) ?>"
                           placeholder="Finalizar Compra">
                </div>

                <!-- Banner de Cookies LGPD -->
                <div class="form-group full-width">
                    <label for="cookie_banner_text">Texto do Banner de Cookies (LGPD)</label>
                    <textarea id="cookie_banner_text" name="cookie_banner_text" class="form-textarea" rows="2"><?= \App\Core\View::e(Setting::get('cookie_banner_text', 'Utilizamos cookies essenciais para garantir a melhor experiência de navegação e compra segura em conformidade com a LGPD. Ao continuar, você concorda com nossos Termos de Uso e Política de Privacidade.')) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="cookie_banner_btn">Botão Aceitar Cookies</label>
                    <input type="text" id="cookie_banner_btn" name="cookie_banner_btn" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('cookie_banner_btn', 'Prosseguir')) ?>"
                           placeholder="Prosseguir">
                </div>

                <!-- Modal de Acesso Administrativo -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 10px; margin-top: 10px;">
                    <h4 style="color: #CBD0D6; margin: 0; font-size: 0.88rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        🔐 Botão & Modal de Acesso aos Bastidores (Entrar)
                    </h4>
                </div>

                <div class="form-group">
                    <label for="header_login_label">Texto do Botão no Topo</label>
                    <input type="text" id="header_login_label" name="header_login_label" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('header_login_label', 'Entrar')) ?>"
                           placeholder="Entrar">
                </div>

                <div class="form-group">
                    <label for="login_modal_title">Título da Janela de Login</label>
                    <input type="text" id="login_modal_title" name="login_modal_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('login_modal_title', 'Acesso aos Bastidores')) ?>"
                           placeholder="Acesso aos Bastidores">
                </div>

                <div class="form-group">
                    <label for="login_modal_subtitle">Subtítulo Explicativo</label>
                    <input type="text" id="login_modal_subtitle" name="login_modal_subtitle" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('login_modal_subtitle', 'Controle de produtos, categorias, minerais e gestão do site.')) ?>">
                </div>

                <div class="form-group">
                    <label for="login_modal_btn">Texto do Botão de Envio</label>
                    <input type="text" id="login_modal_btn" name="login_modal_btn" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('login_modal_btn', 'Acessar Painel de Controle')) ?>"
                           placeholder="Acessar Painel de Controle">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO: MENU RESPONSIVO MOBILE (DRAWER & CELULAR) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-mobile-menu">
            <div class="section-card-header">
                <div class="section-icon">📱</div>
                <div>
                    <h3>Menu Responsivo do Celular (Gaveta & Navegação Mobile)</h3>
                    <p>Personalize cada detalhe da experiência móvel: links, acordeão de coleções, botões de ação, selos, frases de rodapé e cores.</p>
                </div>
            </div>

            <!-- Painel Informativo com Atalho -->
            <div style="margin-bottom: 24px; padding: 14px 18px; background: rgba(212, 186, 136, 0.06); border-radius: 10px; border: 1px solid rgba(212, 186, 136, 0.2); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <div style="font-size: 0.85rem; color: #ded7cf; line-height: 1.4;">
                    <strong style="color: #CBD0D6;">✦ Controle Total no Celular:</strong> 
                    Cada link, botão e selo possui um interruptor individual <em>Exibindo / Oculto</em>. Você pode ocultar itens sem perder o que escreveu.
                </div>
                <a href="/#mobileNavDrawer" target="_blank" class="btn-admin-secondary" style="font-size: 0.8rem; padding: 6px 14px;">
                    <span>↗ Abrir Loja no Celular</span>
                </a>
            </div>

            <div class="form-grid">

                <!-- 1. COMPORTAMENTO GERAL -->
                <div class="form-group full-width" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 8px;">
                    <h4 style="color: #CBD0D6; margin: 0; font-size: 0.92rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        ⚙️ 1. Comportamento & Abertura da Gaveta
                    </h4>
                </div>

                <div class="form-group full-width" style="background: rgba(212, 186, 136, 0.05); padding: 12px 16px; border-radius: 8px; border: 1px solid rgba(212, 186, 136, 0.15);">
                    <div class="field-label-row">
                        <label style="color: #ffffff; font-weight: 600; font-size: 0.9rem;">Ativar Menu Hambúrguer & Gaveta no Celular</label>
                        <?= $renderToggle('mobile_menu_enabled') ?>
                    </div>
                    <span class="field-hint" style="margin-top: 2px;">Quando desativado, o botão hambúrguer de duas linhas e a gaveta lateral ficam totalmente ocultos.</span>
                </div>

                <div class="form-group">
                    <label for="mobile_nav_position">Posição de Abertura da Gaveta</label>
                    <select id="mobile_nav_position" name="mobile_nav_position" class="form-input" onchange="updateMobileMenuLivePreview()">
                        <option value="left" <?= Setting::get('mobile_nav_position', 'left') === 'left' ? 'selected' : '' ?>>Deslizar da Esquerda (Padrão Sofisticado)</option>
                        <option value="right" <?= Setting::get('mobile_nav_position') === 'right' ? 'selected' : '' ?>>Deslizar da Direita</option>
                    </select>
                    <span class="field-hint">Lado da tela de onde a gaveta de navegação surge suavemente.</span>
                </div>

                <div class="form-group">
                    <label for="mobile_nav_drawer_width">Largura Máxima no Celular</label>
                    <select id="mobile_nav_drawer_width" name="mobile_nav_drawer_width" class="form-input" onchange="updateMobileMenuLivePreview()">
                        <option value="320px" <?= Setting::get('mobile_nav_drawer_width') === '320px' ? 'selected' : '' ?>>Compacta (320px)</option>
                        <option value="360px" <?= Setting::get('mobile_nav_drawer_width', '360px') === '360px' ? 'selected' : '' ?>>Equilibrada - Padrão Divinium (360px)</option>
                        <option value="400px" <?= Setting::get('mobile_nav_drawer_width') === '400px' ? 'selected' : '' ?>>Ampla (400px)</option>
                        <option value="100%" <?= Setting::get('mobile_nav_drawer_width') === '100%' ? 'selected' : '' ?>>Tela Cheia (100% de largura)</option>
                    </select>
                    <span class="field-hint">Dimensão da gaveta quando expandida no smartphone.</span>
                </div>

                <div class="form-group full-width">
                    <label for="mobile_nav_backdrop_blur">Intensidade do Desfoque de Fundo (Backdrop Blur)</label>
                    <select id="mobile_nav_backdrop_blur" name="mobile_nav_backdrop_blur" class="form-input">
                        <option value="0px" <?= Setting::get('mobile_nav_backdrop_blur') === '0px' ? 'selected' : '' ?>>Sem desfoque (Fundo apenas escuro)</option>
                        <option value="4px" <?= Setting::get('mobile_nav_backdrop_blur') === '4px' ? 'selected' : '' ?>>Sutil (4px)</option>
                        <option value="8px" <?= Setting::get('mobile_nav_backdrop_blur', '8px') === '8px' ? 'selected' : '' ?>>Luxo Suave (8px - Padrão)</option>
                        <option value="16px" <?= Setting::get('mobile_nav_backdrop_blur') === '16px' ? 'selected' : '' ?>>Profundo / Efeito Vidro Fosco (16px)</option>
                    </select>
                    <span class="field-hint">Efeito de glassmorphism aplicado ao restante da loja enquanto o menu estiver aberto.</span>
                </div>

                <!-- 2. CORES E ESTÉTICA VISUAL -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px; margin-top: 8px;">
                    <h4 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.92rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        🎨 2. Paleta de Cores do Menu Mobile
                    </h4>
                    <span class="field-hint">Deixe o menu personalizado com tonalidades exclusivas da joalheria.</span>
                </div>

                <div class="form-group">
                    <label for="mobile_nav_bg_color">Cor de Fundo da Gaveta</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="mobile_nav_bg_color_picker" 
                               value="<?= Setting::get('mobile_nav_bg_color', '#252525') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('mobile_nav_bg_color').value = this.value; updateMobileMenuLivePreview();">
                        <input type="text" id="mobile_nav_bg_color" name="mobile_nav_bg_color" class="form-input" 
                               value="<?= View::e(Setting::get('mobile_nav_bg_color', '#252525')) ?>" 
                               placeholder="#252525"
                               oninput="if(this.value.length===7){document.getElementById('mobile_nav_bg_color_picker').value=this.value;} updateMobileMenuLivePreview();">
                    </div>
                    <span class="field-hint">Sugestões: #252525 (Ônix Nobre), #141210 (Café Diamante), #0f172a (Safira Noite).</span>
                </div>

                <div class="form-group">
                    <label for="mobile_nav_text_color">Cor dos Textos e Ícones</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="mobile_nav_text_color_picker" 
                               value="<?= Setting::get('mobile_nav_text_color', '#ded7cf') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('mobile_nav_text_color').value = this.value; updateMobileMenuLivePreview();">
                        <input type="text" id="mobile_nav_text_color" name="mobile_nav_text_color" class="form-input" 
                               value="<?= View::e(Setting::get('mobile_nav_text_color', '#ded7cf')) ?>" 
                               placeholder="#ded7cf"
                               oninput="if(this.value.length===7){document.getElementById('mobile_nav_text_color_picker').value=this.value;} updateMobileMenuLivePreview();">
                    </div>
                    <span class="field-hint">Cor dos títulos dos links e chevrons na gaveta.</span>
                </div>

                <div class="form-group full-width">
                    <label for="mobile_nav_accent_color">Cor de Destaque / Dourado (Bordas Ativas & Selos)</label>
                    <div style="display: flex; gap: 8px; align-items: center; max-width: 400px;">
                        <input type="color" id="mobile_nav_accent_color_picker" 
                               value="<?= Setting::get('mobile_nav_accent_color', '#CBD0D6') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('mobile_nav_accent_color').value = this.value; updateMobileMenuLivePreview();">
                        <input type="text" id="mobile_nav_accent_color" name="mobile_nav_accent_color" class="form-input" 
                               value="<?= View::e(Setting::get('mobile_nav_accent_color', '#CBD0D6')) ?>" 
                               placeholder="#CBD0D6"
                               oninput="if(this.value.length===7){document.getElementById('mobile_nav_accent_color_picker').value=this.value;} updateMobileMenuLivePreview();">
                    </div>
                    <span class="field-hint">Cor da borda lateral do link selecionado, selo 'Exclusivo' e contorno dourado.</span>
                </div>

                <!-- 3. CABEÇALHO DO MENU -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px; margin-top: 8px;">
                    <h4 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.92rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        👑 3. Cabeçalho do Menu Mobile (Marca & Selo)
                    </h4>
                </div>

                <div class="form-group full-width">
                    <div class="field-label-row">
                        <label>Exibir Marca / Logotipo no Topo do Menu</label>
                        <?= $renderToggle('mobile_nav_show_brand') ?>
                    </div>
                </div>

                <div class="form-group <?= Setting::get('mobile_nav_show_brand', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="mobile_nav_logo_text">Texto Principal da Marca no Menu</label>
                    <input type="text" id="mobile_nav_logo_text" name="mobile_nav_logo_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_logo_text', Setting::get('header_logo_text', 'DIVINIUM'))) ?>"
                           placeholder="DIVINIUM"
                           oninput="updateMobileMenuLivePreview()">
                </div>

                <div class="form-group <?= Setting::get('mobile_nav_show_brand', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="mobile_nav_logo_sub">Subtítulo da Marca no Menu</label>
                    <input type="text" id="mobile_nav_logo_sub" name="mobile_nav_logo_sub" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_logo_sub', Setting::get('header_logo_sub', 'HAUTE JOAILLERIE'))) ?>"
                           placeholder="HAUTE JOAILLERIE"
                           oninput="updateMobileMenuLivePreview()">
                </div>

                <div class="form-group full-width <?= Setting::get('mobile_nav_show_header_badge', '0') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_header_badge">Selo Superior Flutuante (Opcional, acima da marca)</label>
                        <?= $renderToggle('mobile_nav_show_header_badge', '0') ?>
                    </div>
                    <input type="text" id="mobile_nav_header_badge" name="mobile_nav_header_badge" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_header_badge', 'JOALHERIA AUTORAL')) ?>"
                           placeholder="JOALHERIA AUTORAL"
                           oninput="updateMobileMenuLivePreview()">
                    <span class="field-hint">Ex: 'JOALHERIA AUTORAL', 'EDIÇÃO LIMITADA' ou 'ALTA JOALHERIA'.</span>
                </div>

                <!-- 4. LINKS DE NAVEGAÇÃO -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px; margin-top: 8px;">
                    <h4 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.92rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        🧭 4. Links de Navegação Mobile (Texto, URL & Exibição)
                    </h4>
                    <span class="field-hint">Altere o texto, destino e a visibilidade de cada link individualmente.</span>
                </div>

                <!-- Link 1: Início -->
                <div class="form-group <?= Setting::get('mobile_nav_home_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_home_text">Link 1: Texto</label>
                        <?= $renderToggle('mobile_nav_home_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_home_text" name="mobile_nav_home_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_home_text', Setting::get('nav_link_home', 'Início'))) ?>"
                           placeholder="Início"
                           oninput="updateMobileMenuLivePreview()">
                </div>
                <div class="form-group <?= Setting::get('mobile_nav_home_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="mobile_nav_home_url">Link 1: URL de Destino</label>
                    <input type="text" id="mobile_nav_home_url" name="mobile_nav_home_url" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_home_url', '/')) ?>"
                           placeholder="/">
                </div>

                <!-- Link 2: Loja / Boutique -->
                <div class="form-group <?= Setting::get('mobile_nav_store_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_store_text">Link 2: Texto (Loja / Boutique)</label>
                        <?= $renderToggle('mobile_nav_store_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_store_text" name="mobile_nav_store_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_store_text', Setting::get('nav_link_store', 'Boutique / Loja'))) ?>"
                           placeholder="Boutique / Loja"
                           oninput="updateMobileMenuLivePreview()">
                </div>
                <div class="form-group <?= Setting::get('mobile_nav_store_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="mobile_nav_store_url">Link 2: URL de Destino</label>
                    <input type="text" id="mobile_nav_store_url" name="mobile_nav_store_url" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_store_url', '/loja')) ?>"
                           placeholder="/loja">
                </div>

                <!-- Selo do Link da Loja -->
                <div class="form-group full-width <?= Setting::get('mobile_nav_store_badge_visible', '1') !== '1' ? 'is-dimmed' : '' ?>" style="background: rgba(255,255,255,0.02); padding: 12px 14px; border-radius: 8px; border: 1px dashed rgba(255,255,255,0.08);">
                    <div class="field-label-row">
                        <label for="mobile_nav_store_badge_text">Etiqueta Flutuante do Link da Loja (Badge Dourado)</label>
                        <?= $renderToggle('mobile_nav_store_badge_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_store_badge_text" name="mobile_nav_store_badge_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_store_badge_text', 'Exclusivo')) ?>"
                           placeholder="Exclusivo"
                           oninput="updateMobileMenuLivePreview()">
                    <span class="field-hint">Exibido ao lado do nome 'Boutique / Loja'. Ex: 'Exclusivo', 'Novo', 'Catálogo'.</span>
                </div>

                <!-- Link 3: Acordeão de Coleções -->
                <div class="form-group full-width <?= Setting::get('mobile_nav_collections_visible', '1') !== '1' ? 'is-dimmed' : '' ?>" style="background: rgba(212, 186, 136, 0.04); padding: 14px; border-radius: 8px; border: 1px solid rgba(212, 186, 136, 0.15);">
                    <div class="field-label-row">
                        <label for="mobile_nav_collections_text" style="color: #ffffff; font-weight: 600;">Link 3: Acordeão Expansível de Coleções</label>
                        <?= $renderToggle('mobile_nav_collections_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_collections_text" name="mobile_nav_collections_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_collections_text', Setting::get('nav_link_collections', 'Coleções'))) ?>"
                           placeholder="Coleções"
                           oninput="updateMobileMenuLivePreview()">
                    <span class="field-hint">Menu com seta que expande as subcategorias abaixo no celular.</span>

                    <div style="margin-top: 14px; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.08); display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <!-- Sub-item 1 -->
                        <div class="form-group <?= Setting::get('mobile_nav_sub_all_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                            <div class="field-label-row">
                                <label for="mobile_nav_sub_all_text" style="font-size: 0.78rem;">Sub-item 1: Todas as Peças</label>
                                <?= $renderToggle('mobile_nav_sub_all_visible') ?>
                            </div>
                            <input type="text" id="mobile_nav_sub_all_text" name="mobile_nav_sub_all_text" class="form-input" 
                                   value="<?= View::e(Setting::get('mobile_nav_sub_all_text', Setting::get('nav_sub_all', 'Ver Todas as Peças'))) ?>"
                                   oninput="updateMobileMenuLivePreview()">
                            <input type="text" name="mobile_nav_sub_all_url" class="form-input" style="margin-top: 4px; font-size: 0.78rem;"
                                   value="<?= View::e(Setting::get('mobile_nav_sub_all_url', '/loja')) ?>">
                        </div>

                        <!-- Sub-item 2 -->
                        <div class="form-group <?= Setting::get('mobile_nav_sub_bracelets_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                            <div class="field-label-row">
                                <label for="mobile_nav_sub_bracelets_text" style="font-size: 0.78rem;">Sub-item 2: Braceletes</label>
                                <?= $renderToggle('mobile_nav_sub_bracelets_visible') ?>
                            </div>
                            <input type="text" id="mobile_nav_sub_bracelets_text" name="mobile_nav_sub_bracelets_text" class="form-input" 
                                   value="<?= View::e(Setting::get('mobile_nav_sub_bracelets_text', Setting::get('nav_sub_bracelets', 'Braceletes Exclusivos'))) ?>"
                                   oninput="updateMobileMenuLivePreview()">
                            <input type="text" name="mobile_nav_sub_bracelets_url" class="form-input" style="margin-top: 4px; font-size: 0.78rem;"
                                   value="<?= View::e(Setting::get('mobile_nav_sub_bracelets_url', '/loja#braceletes')) ?>">
                        </div>

                        <!-- Sub-item 3 -->
                        <div class="form-group <?= Setting::get('mobile_nav_sub_necklaces_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                            <div class="field-label-row">
                                <label for="mobile_nav_sub_necklaces_text" style="font-size: 0.78rem;">Sub-item 3: Colares</label>
                                <?= $renderToggle('mobile_nav_sub_necklaces_visible') ?>
                            </div>
                            <input type="text" id="mobile_nav_sub_necklaces_text" name="mobile_nav_sub_necklaces_text" class="form-input" 
                                   value="<?= View::e(Setting::get('mobile_nav_sub_necklaces_text', Setting::get('nav_sub_necklaces', 'Colares & Pingentes'))) ?>"
                                   oninput="updateMobileMenuLivePreview()">
                            <input type="text" name="mobile_nav_sub_necklaces_url" class="form-input" style="margin-top: 4px; font-size: 0.78rem;"
                                   value="<?= View::e(Setting::get('mobile_nav_sub_necklaces_url', '/loja#colares')) ?>">
                        </div>

                        <!-- Sub-item 4 -->
                        <div class="form-group <?= Setting::get('mobile_nav_sub_rings_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                            <div class="field-label-row">
                                <label for="mobile_nav_sub_rings_text" style="font-size: 0.78rem;">Sub-item 4: Anéis</label>
                                <?= $renderToggle('mobile_nav_sub_rings_visible') ?>
                            </div>
                            <input type="text" id="mobile_nav_sub_rings_text" name="mobile_nav_sub_rings_text" class="form-input" 
                                   value="<?= View::e(Setting::get('mobile_nav_sub_rings_text', Setting::get('nav_sub_rings', 'Anéis & Alianças'))) ?>"
                                   oninput="updateMobileMenuLivePreview()">
                            <input type="text" name="mobile_nav_sub_rings_url" class="form-input" style="margin-top: 4px; font-size: 0.78rem;"
                                   value="<?= View::e(Setting::get('mobile_nav_sub_rings_url', '/loja#aneis')) ?>">
                        </div>
                    </div>
                </div>

                <!-- Link 4: Sobre -->
                <div class="form-group <?= Setting::get('mobile_nav_about_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_about_text">Link 4: Texto (Sobre a Divinium)</label>
                        <?= $renderToggle('mobile_nav_about_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_about_text" name="mobile_nav_about_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_about_text', Setting::get('nav_link_about', 'Sobre'))) ?>"
                           placeholder="Sobre"
                           oninput="updateMobileMenuLivePreview()">
                </div>
                <div class="form-group <?= Setting::get('mobile_nav_about_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="mobile_nav_about_url">Link 4: URL de Destino</label>
                    <input type="text" id="mobile_nav_about_url" name="mobile_nav_about_url" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_about_url', '/#about')) ?>"
                           placeholder="/#about">
                </div>

                <!-- Link 5: Garantia & Serviços -->
                <div class="form-group <?= Setting::get('mobile_nav_services_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_services_text">Link 5: Texto (Garantia & Serviços)</label>
                        <?= $renderToggle('mobile_nav_services_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_services_text" name="mobile_nav_services_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_services_text', Setting::get('nav_link_services', 'Garantia & Serviços'))) ?>"
                           placeholder="Garantia & Serviços"
                           oninput="updateMobileMenuLivePreview()">
                </div>
                <div class="form-group <?= Setting::get('mobile_nav_services_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="mobile_nav_services_url">Link 5: URL de Destino</label>
                    <input type="text" id="mobile_nav_services_url" name="mobile_nav_services_url" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_services_url', '/#services')) ?>"
                           placeholder="/#services">
                </div>

                <!-- Link 6: Lista de Desejos / Favoritos -->
                <div class="form-group <?= Setting::get('mobile_nav_wishlist_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_wishlist_text">Link 6: Texto (Lista de Desejos)</label>
                        <?= $renderToggle('mobile_nav_wishlist_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_wishlist_text" name="mobile_nav_wishlist_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_wishlist_text', 'Lista de Desejos')) ?>"
                           placeholder="Lista de Desejos"
                           oninput="updateMobileMenuLivePreview()">
                </div>
                <div class="form-group <?= Setting::get('mobile_nav_wishlist_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="mobile_nav_wishlist_url">Link 6: URL de Destino</label>
                    <input type="text" id="mobile_nav_wishlist_url" name="mobile_nav_wishlist_url" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_wishlist_url', '/minha-conta/favoritos')) ?>"
                           placeholder="/minha-conta/favoritos">
                </div>

                <!-- Link 7: Personalizado Adicional 1 -->
                <div class="form-group <?= Setting::get('mobile_nav_custom1_visible', '0') !== '1' ? 'is-dimmed' : '' ?>" style="background: rgba(255,255,255,0.02); padding: 10px; border-radius: 6px;">
                    <div class="field-label-row">
                        <label for="mobile_nav_custom1_text">Link Extra 1 (Ex: Rastreamento)</label>
                        <?= $renderToggle('mobile_nav_custom1_visible', '0') ?>
                    </div>
                    <input type="text" id="mobile_nav_custom1_text" name="mobile_nav_custom1_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_custom1_text', 'Rastrear Meu Pedido')) ?>"
                           placeholder="Rastrear Meu Pedido"
                           oninput="updateMobileMenuLivePreview()">
                    <div style="display: flex; gap: 6px; margin-top: 6px;">
                        <input type="text" name="mobile_nav_custom1_url" class="form-input" style="flex: 2; font-size: 0.78rem;"
                               value="<?= View::e(Setting::get('mobile_nav_custom1_url', '/loja')) ?>" placeholder="/loja">
                        <input type="text" name="mobile_nav_custom1_badge" class="form-input" style="flex: 1; font-size: 0.78rem;"
                               value="<?= View::e(Setting::get('mobile_nav_custom1_badge', 'VIP')) ?>" placeholder="Badge (VIP)">
                    </div>
                </div>

                <!-- Link 8: Personalizado Adicional 2 -->
                <div class="form-group <?= Setting::get('mobile_nav_custom2_visible', '0') !== '1' ? 'is-dimmed' : '' ?>" style="background: rgba(255,255,255,0.02); padding: 10px; border-radius: 6px;">
                    <div class="field-label-row">
                        <label for="mobile_nav_custom2_text">Link Extra 2 (Ex: Contato / FAQ)</label>
                        <?= $renderToggle('mobile_nav_custom2_visible', '0') ?>
                    </div>
                    <input type="text" id="mobile_nav_custom2_text" name="mobile_nav_custom2_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_custom2_text', 'Atendimento & FAQ')) ?>"
                           placeholder="Atendimento & FAQ"
                           oninput="updateMobileMenuLivePreview()">
                    <div style="display: flex; gap: 6px; margin-top: 6px;">
                        <input type="text" name="mobile_nav_custom2_url" class="form-input" style="flex: 2; font-size: 0.78rem;"
                               value="<?= View::e(Setting::get('mobile_nav_custom2_url', '/#services')) ?>" placeholder="/#services">
                        <input type="text" name="mobile_nav_custom2_badge" class="form-input" style="flex: 1; font-size: 0.78rem;"
                               value="<?= View::e(Setting::get('mobile_nav_custom2_badge', '')) ?>" placeholder="Badge">
                    </div>
                </div>

                <!-- 5. RODAPÉ E AÇÕES -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px; margin-top: 8px;">
                    <h4 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.92rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        📞 5. Rodapé & Ações da Gaveta (WhatsApp & Acesso)
                    </h4>
                </div>

                <!-- Botão WhatsApp -->
                <div class="form-group <?= Setting::get('mobile_nav_whatsapp_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_whatsapp_text">Botão WhatsApp: Texto</label>
                        <?= $renderToggle('mobile_nav_whatsapp_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_whatsapp_text" name="mobile_nav_whatsapp_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_whatsapp_text', 'Concierge VIP via WhatsApp')) ?>"
                           placeholder="Concierge VIP via WhatsApp"
                           oninput="updateMobileMenuLivePreview()">
                </div>
                <div class="form-group <?= Setting::get('mobile_nav_whatsapp_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="mobile_nav_whatsapp_url">Botão WhatsApp: URL / Número</label>
                    <input type="text" id="mobile_nav_whatsapp_url" name="mobile_nav_whatsapp_url" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_whatsapp_url', Setting::get('hero_whatsapp_number', '5511999999999'))) ?>"
                           placeholder="5511999999999 ou link completo">
                </div>

                <!-- Botão Entrar -->
                <div class="form-group full-width <?= Setting::get('mobile_nav_login_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_login_text">Botão de Acesso / Login</label>
                        <?= $renderToggle('mobile_nav_login_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_login_text" name="mobile_nav_login_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_login_text', 'Entrar no Painel Administrativo')) ?>"
                           placeholder="Entrar no Painel Administrativo"
                           oninput="updateMobileMenuLivePreview()">
                    <span class="field-hint">Abre a janela nobre de autenticação administrativa e credenciais.</span>
                </div>

                <!-- Mensagem Perk -->
                <div class="form-group full-width <?= Setting::get('mobile_nav_perk_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_perk_text">Selo Institucional / Frase de Rodapé</label>
                        <?= $renderToggle('mobile_nav_perk_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_perk_text" name="mobile_nav_perk_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_perk_text', '✨ Joalheria Autoral Brasileira • Prata 925 & Gemas Raras')) ?>"
                           placeholder="✨ Joalheria Autoral Brasileira • Prata 925 & Gemas Raras"
                           oninput="updateMobileMenuLivePreview()">
                    <span class="field-hint">Frase final de prestígio e assinatura da marca no pé do menu.</span>
                </div>

                <!-- 6. PRÉ-VISUALIZAÇÃO EM TEMPO REAL -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 20px; margin-top: 12px;">
                    <h4 style="color: #CBD0D6; margin: 0 0 10px; font-size: 0.92rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; justify-content: space-between;">
                        <span>📱 Pré-visualização Interativa do Menu no Smartphone</span>
                        <span style="font-size: 0.72rem; color: #8e8880; font-weight: normal; text-transform: none;">Atualiza em tempo real enquanto você digita</span>
                    </h4>
                    
                    <div style="background: rgba(0,0,0,0.4); padding: 20px; border-radius: 12px; border: 1px dashed rgba(255,255,255,0.1); display: flex; justify-content: center;">
                        <!-- Mockup de Smartphone -->
                        <div style="width: 330px; border-radius: 28px; border: 3px solid rgba(212, 186, 136, 0.4); box-shadow: 0 16px 40px rgba(0,0,0,0.8); overflow: hidden; background: #000; display: flex; flex-direction: column;">
                            <!-- Barra de Status do Celular -->
                            <div style="background: #000; padding: 6px 16px; display: flex; justify-content: space-between; align-items: center; font-size: 0.65rem; color: #888;">
                                <span>9:41</span>
                                <div style="display: flex; gap: 4px; align-items: center;">
                                    <span>●●●●</span>
                                    <span>5G</span>
                                    <span>100%</span>
                                </div>
                            </div>
                            
                            <!-- Conteúdo do Menu dentro do Mockup -->
                            <div id="mobileMenuLivePreviewDrawer" style="background: <?= Setting::get('mobile_nav_bg_color', '#252525') ?>; color: <?= Setting::get('mobile_nav_text_color', '#ded7cf') ?>; padding: 16px 18px; display: flex; flex-direction: column; min-height: 480px; justify-content: space-between;">
                                
                                <div>
                                    <!-- Header do Mockup -->
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 14px; border-bottom: 1px solid rgba(255,255,255,0.08);">
                                        <div id="previewBrandWrap">
                                            <div id="previewHeaderBadge" style="display: <?= Setting::get('mobile_nav_show_header_badge', '0') === '1' ? 'inline-block' : 'none' ?>; font-size: 0.52rem; font-weight: 700; color: #CBD0D6; border: 1px solid rgba(212,186,136,0.3); padding: 1px 6px; border-radius: 10px; margin-bottom: 3px;"><?= View::e(Setting::get('mobile_nav_header_badge', 'JOALHERIA AUTORAL')) ?></div>
                                            <div id="previewLogoText" style="font-family: 'Cinzel', serif; font-size: 1.2rem; font-weight: 700; letter-spacing: 0.15em; color: #fff;"><?= View::e(Setting::get('mobile_nav_logo_text', 'DIVINIUM')) ?></div>
                                            <div id="previewLogoSub" style="font-size: 0.45rem; letter-spacing: 0.25em; color: #8e8880;"><?= View::e(Setting::get('mobile_nav_logo_sub', 'HAUTE JOAILLERIE')) ?></div>
                                        </div>
                                        <div style="width: 26px; height: 26px; border-radius: 50%; background: rgba(255,255,255,0.06); display: flex; align-items: center; justify-content: center; font-size: 1rem; color: #aaa;">&times;</div>
                                    </div>

                                    <!-- Links do Mockup -->
                                    <div style="display: flex; flex-direction: column; gap: 4px; margin-top: 12px;">
                                        <div id="previewLinkHome" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; border-radius: 6px; background: rgba(212, 186, 136, 0.08); border-left: 2px solid #CBD0D6; color: #fff; font-size: 0.88rem;">
                                            <span id="previewTextHome"><?= View::e(Setting::get('mobile_nav_home_text', 'Início')) ?></span>
                                            <span style="font-size: 0.7rem; color: #CBD0D6;">›</span>
                                        </div>

                                        <div id="previewLinkStore" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; border-radius: 6px; font-size: 0.88rem; color: #ded7cf;">
                                            <span id="previewTextStore"><?= View::e(Setting::get('mobile_nav_store_text', 'Boutique / Loja')) ?></span>
                                            <span id="previewBadgeStore" style="font-size: 0.58rem; background: rgba(212, 186, 136, 0.15); color: #CBD0D6; padding: 2px 6px; border-radius: 8px; border: 1px solid rgba(212, 186, 136, 0.3);"><?= View::e(Setting::get('mobile_nav_store_badge_text', 'Exclusivo')) ?></span>
                                        </div>

                                        <div id="previewLinkCollections" style="display: flex; flex-direction: column; padding: 8px 10px; border-radius: 6px; font-size: 0.88rem; color: #ded7cf;">
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <span id="previewTextCollections"><?= View::e(Setting::get('mobile_nav_collections_text', 'Coleções')) ?></span>
                                                <span style="font-size: 0.7rem; color: #888;">⌵</span>
                                            </div>
                                            <div style="padding-left: 12px; margin-top: 4px; display: flex; flex-direction: column; gap: 4px; font-size: 0.75rem; color: #999;">
                                                <span>✦ <span id="previewTextSubAll"><?= View::e(Setting::get('mobile_nav_sub_all_text', 'Ver Todas as Peças')) ?></span></span>
                                                <span>✦ <span id="previewTextSubBracelets"><?= View::e(Setting::get('mobile_nav_sub_bracelets_text', 'Braceletes Exclusivos')) ?></span></span>
                                            </div>
                                        </div>

                                        <div id="previewLinkAbout" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; border-radius: 6px; font-size: 0.88rem; color: #ded7cf;">
                                            <span id="previewTextAbout"><?= View::e(Setting::get('mobile_nav_about_text', 'Sobre')) ?></span>
                                            <span style="font-size: 0.7rem; color: #888;">›</span>
                                        </div>

                                        <div id="previewLinkServices" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; border-radius: 6px; font-size: 0.88rem; color: #ded7cf;">
                                            <span id="previewTextServices"><?= View::e(Setting::get('mobile_nav_services_text', 'Garantia & Serviços')) ?></span>
                                            <span style="font-size: 0.7rem; color: #888;">›</span>
                                        </div>

                                        <div id="previewLinkWishlist" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; border-radius: 6px; font-size: 0.88rem; color: #ded7cf;">
                                            <span>♡ <span id="previewTextWishlist"><?= View::e(Setting::get('mobile_nav_wishlist_text', 'Lista de Desejos')) ?></span></span>
                                            <span style="font-size: 0.7rem; color: #888;">›</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rodapé do Mockup -->
                                <div style="display: flex; flex-direction: column; gap: 8px; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.08); margin-top: 12px;">
                                    <div id="previewBtnWhatsapp" style="display: flex; align-items: center; justify-content: center; gap: 6px; background: rgba(37, 211, 102, 0.12); border: 1px solid rgba(37, 211, 102, 0.3); color: #25d366; padding: 8px; border-radius: 8px; font-size: 0.78rem; font-weight: 600;">
                                        <span>💬</span>
                                        <span id="previewTextWhatsapp"><?= View::e(Setting::get('mobile_nav_whatsapp_text', 'Concierge VIP via WhatsApp')) ?></span>
                                    </div>

                                    <div id="previewBtnLogin" style="display: flex; align-items: center; justify-content: center; gap: 6px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.12); color: #ded7cf; padding: 7px; border-radius: 8px; font-size: 0.74rem;">
                                        <span>🔒</span>
                                        <span id="previewTextLogin"><?= View::e(Setting::get('mobile_nav_login_text', 'Entrar no Painel Administrativo')) ?></span>
                                    </div>

                                    <div id="previewPerkWrap" style="text-align: center; color: #8e8880; font-size: 0.62rem; margin-top: 2px;">
                                        <span id="previewTextPerk"><?= View::e(Setting::get('mobile_nav_perk_text', '✨ Joalheria Autoral Brasileira • Prata 925 & Gemas Raras')) ?></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 3: HERO PRINCIPAL, HEADLINE & SELETOR DE MINERAIS -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-hero">
            <div class="section-card-header">
                <div class="section-icon">👑</div>
                <div>
                    <h3>Hero Principal & Seletor de Minerais</h3>
                    <p>Títulos editoriais, subtítulos, botões de ação, selos de autoridade e nomes dos minerais interativos.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="hero_badge_text">Tag Editorial Superior</label>
                    <input type="text" id="hero_badge_text" name="hero_badge_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_badge_text', 'HAUTE JOAILLERIE • EDIÇÃO AUTORAL BRASILEIRA')) ?>"
                           placeholder="HAUTE JOAILLERIE • EDIÇÃO AUTORAL BRASILEIRA">
                </div>

                <div class="form-group">
                    <label for="hero_title_line1">Título Principal (Linha 1)</label>
                    <input type="text" id="hero_title_line1" name="hero_title_line1" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_title_line1', 'Joias que Revelam')) ?>"
                           placeholder="Joias que Revelam">
                </div>

                <div class="form-group">
                    <label for="hero_title_highlight">Título Principal (Destaque Metálico)</label>
                    <input type="text" id="hero_title_highlight" name="hero_title_highlight" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_title_highlight', 'o Luxo Silencioso')) ?>"
                           placeholder="o Luxo Silencioso">
                    <span class="field-hint">Esta parte ganha o gradiente nobre dourado ou prateado.</span>
                </div>

                <div class="form-group full-width">
                    <label for="hero_subtitle">Subtítulo / Descrição Storytelling</label>
                    <textarea id="hero_subtitle" name="hero_subtitle" class="form-textarea" rows="3"
                              placeholder="A nobreza da prata de lei 925 entrelaçada à vibração autêntica de gemas minerais raras..."><?= \App\Core\View::e(Setting::get('hero_subtitle', 'A nobreza da prata de lei 925 entrelaçada à vibração autêntica de gemas minerais raras. Braceletes CAMINHOS: um mesmo design, diferentes formas de expressar a sua presença.')) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="hero_cta_primary_text">Botão Principal - Texto</label>
                    <input type="text" id="hero_cta_primary_text" name="hero_cta_primary_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_cta_primary_text', 'Explorar Coleção Completa')) ?>"
                           placeholder="Explorar Coleção Completa">
                </div>

                <div class="form-group">
                    <label for="hero_cta_primary_link">Botão Principal - Link de Destino</label>
                    <input type="text" id="hero_cta_primary_link" name="hero_cta_primary_link" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_cta_primary_link', '/loja')) ?>"
                           placeholder="/loja ou #collections">
                </div>

                <div class="form-group">
                    <label for="hero_cta_whatsapp_text">Botão Secundário (WhatsApp Concierge) - Texto</label>
                    <input type="text" id="hero_cta_whatsapp_text" name="hero_cta_whatsapp_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_cta_whatsapp_text', 'Concierge WhatsApp')) ?>"
                           placeholder="Concierge WhatsApp">
                </div>

                <div class="form-group">
                    <label for="hero_whatsapp_number">Número WhatsApp (com DDI e DDD)</label>
                    <input type="text" id="hero_whatsapp_number" name="hero_whatsapp_number" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_whatsapp_number', '5511999999999')) ?>"
                           placeholder="5511999999999">
                </div>

                <div class="form-group">
                    <label for="hero_trust_badge_1">Selo de Confiança 1</label>
                    <input type="text" id="hero_trust_badge_1" name="hero_trust_badge_1" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_trust_badge_1', 'Prata de Lei 925')) ?>"
                           placeholder="Prata de Lei 925">
                </div>

                <div class="form-group">
                    <label for="hero_trust_badge_2">Selo de Confiança 2</label>
                    <input type="text" id="hero_trust_badge_2" name="hero_trust_badge_2" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_trust_badge_2', 'Gemas 100% Naturais')) ?>"
                           placeholder="Gemas 100% Naturais">
                </div>

                <div class="form-group">
                    <label for="hero_trust_badge_3">Selo de Confiança 3</label>
                    <input type="text" id="hero_trust_badge_3" name="hero_trust_badge_3" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_trust_badge_3', 'Certificado de Garantia')) ?>"
                           placeholder="Certificado de Garantia">
                </div>

                <div class="form-group full-width" style="background: rgba(202, 161, 92, 0.08); border: 1px solid rgba(202, 161, 92, 0.25); border-radius: 10px; padding: 18px; margin-top: 10px;">
                    <label for="hero_bg_image" style="display: flex; justify-content: space-between; align-items: center; font-weight: 700; color: #CBD0D6;">
                        <span>🖼️ Imagem de Fundo do Hero (Banner Principal)</span>
                        <a href="/admin/temas" target="_blank" style="color: #fff; font-size: 0.74rem; text-decoration: none; background: rgba(202, 161, 92, 0.2); padding: 2px 8px; border-radius: 6px;">Gerenciar Texturas ↗</a>
                    </label>
                    <div style="display: flex; gap: 10px; margin-top: 8px;">
                        <input type="text" id="hero_bg_image" name="hero_bg_image" class="form-input" 
                               value="<?= \App\Core\View::e(Setting::get('hero_bg_image', '')) ?>" 
                               placeholder="Ex: /assets/images/hero/hero-caminhos.jpg ou link externo">
                        <label class="btn-admin-gold" style="padding: 0 16px; cursor: pointer; display: flex; align-items: center; white-space: nowrap; font-size: 0.8rem;">
                            <span>📁 Fazer Upload</span>
                            <input type="file" accept="image/*" style="display: none;" onchange="uploadHeroEditorBg(this)">
                        </label>
                    </div>
                    <span class="field-hint">Se preenchido, substitui o gradiente padrão da abertura da Home por esta imagem. Deixe vazio para usar o padrão do tema.</span>
                </div>

            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 4: CARROSSEL DE IMAGENS PRINCIPAIS DA HOME -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-stage">
            <div class="section-card-header">
                <div class="section-icon">🎠</div>
                <div>
                    <h3>Carrossel de Imagens Principais da Home (Alta Joalheria)</h3>
                    <p>Gerencie as fotografias, links, textos e a reprodução automática do carrossel exibido na abertura da Home.</p>
                </div>
            </div>

            <div class="form-grid">
                <!-- Configurações Gerais do Carrossel -->
                <div class="form-group" style="background: rgba(212, 186, 136, 0.05); padding: 14px 16px; border-radius: 8px; border: 1px solid rgba(212, 186, 136, 0.15);">
                    <div class="field-label-row">
                        <label style="color: #ffffff; font-weight: 600; font-size: 0.88rem;">Reprodução Automática (Autoplay)</label>
                        <?= $renderToggle('hero_carousel_autoplay') ?>
                    </div>
                    <span class="field-hint" style="margin-top: 2px;">Alterna as imagens automaticamente com transição suave. Pausa ao passar o cursor ou tocar no carrossel.</span>
                </div>

                <div class="form-group" style="background: rgba(255, 255, 255, 0.02); padding: 14px 16px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.06);">
                    <label for="hero_carousel_interval" style="color: #ffffff; font-weight: 600; font-size: 0.88rem;">Tempo de Exibição por Slide</label>
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 6px;">
                        <input type="number" id="hero_carousel_interval" name="hero_carousel_interval" class="form-input" min="2" max="30" step="1"
                               value="<?= View::e(Setting::get('hero_carousel_interval', '5')) ?>" style="width: 100px;">
                        <span style="font-size: 0.82rem; color: #b5ada3;">segundos por imagem</span>
                    </div>
                    <span class="field-hint">Intervalo entre trocas de slide (recomendado: 4 a 7 segundos).</span>
                </div>

                <!-- Input Hidden com os Slides Serializados em JSON -->
                <input type="hidden" name="hero_carousel_slides" id="heroCarouselSlidesJson" value="<?= View::e(Setting::get('hero_carousel_slides', '')) ?>">
                <input type="hidden" name="hero_editorial_main_img" id="hero_editorial_main_img_sync" value="<?= View::e(Setting::get('hero_editorial_main_img', '')) ?>">
                <input type="hidden" name="hero_editorial_sec_img" id="hero_editorial_sec_img_sync" value="<?= View::e(Setting::get('hero_editorial_sec_img', '')) ?>">

                <!-- Gerenciador Visual de Slides -->
                <div class="form-group full-width" style="margin-top: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 6px;">
                        <div>
                            <h4 style="color: #CBD0D6; font-size: 0.95rem; font-weight: 600; margin: 0;">Imagens do Carrossel (<span id="carouselSlidesCount">0</span>)</h4>
                            <span class="field-hint" style="margin: 2px 0 0;">Adicione, reordene, configure links e textos alternativos para cada imagem.</span>
                        </div>
                        <button type="button" class="btn-admin-gold" onclick="addNewCarouselSlide()" style="font-size: 0.8rem; padding: 8px 16px;">
                            <span>+ Adicionar Nova Imagem</span>
                        </button>
                    </div>

                    <div id="carouselSlidesContainer" class="carousel-slides-manager">
                        <!-- Renderizado via JavaScript -->
                    </div>

                    <button type="button" class="btn-add-carousel-slide" onclick="addNewCarouselSlide()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>+ Adicionar Outra Imagem ao Carrossel da Home</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 5: MANIFESTO & SOBRE -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-about">
            <div class="section-card-header">
                <div class="section-icon">📜</div>
                <div>
                    <h3>Manifesto & Sobre a Marca</h3>
                    <p>A história editorial, pilares conceituais, fotos e a essência do quiet luxury.</p>
                </div>
            </div>

            <div class="form-grid">
                <!-- ============================================================== -->
                <!-- PALETA DE CORES & ESTÉTICA VISUAL DO MANIFESTO -->
                <!-- ============================================================== -->
                <div class="form-group full-width" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 16px; margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <div>
                            <h4 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.98rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                                <span>🎨</span> 1. Cores & Estética Visual do Manifesto
                            </h4>
                            <span class="field-hint">Personalize as cores de fundo, tipografia, destaque dourado, botões e cards com visualização ao vivo antes de salvar.</span>
                        </div>

                        <!-- Presets Rápidos de Cores -->
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <button type="button" class="btn-admin-secondary" style="padding: 6px 11px; font-size: 0.76rem;" onclick="applyAboutColorPreset('default')">
                                <span>🖤 Ônix & Ouro (Padrão)</span>
                            </button>
                            <button type="button" class="btn-admin-secondary" style="padding: 6px 11px; font-size: 0.76rem;" onclick="applyAboutColorPreset('light')">
                                <span>🤍 Editorial Claro</span>
                            </button>
                            <button type="button" class="btn-admin-secondary" style="padding: 6px 11px; font-size: 0.76rem;" onclick="applyAboutColorPreset('emerald')">
                                <span>💚 Esmeralda & Ouro</span>
                            </button>
                            <button type="button" class="btn-admin-secondary" style="padding: 6px 11px; font-size: 0.76rem;" onclick="applyAboutColorPreset('sapphire')">
                                <span>💎 Safira Noturna</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="about_bg_color">Cor de Fundo da Seção</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="about_bg_color_picker" 
                               value="<?= Setting::get('about_bg_color', '#252525') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('about_bg_color').value = this.value; updateAboutLivePreview();">
                        <input type="text" id="about_bg_color" name="about_bg_color" class="form-input" 
                               value="<?= View::e(Setting::get('about_bg_color', '#252525')) ?>" 
                               placeholder="#252525"
                               oninput="if(this.value.length===7){document.getElementById('about_bg_color_picker').value=this.value;} updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Fundo principal (sugestões: #252525, #141210, #fbf9f5).</span>
                </div>

                <div class="form-group">
                    <label for="about_title_color">Cor do Título Principal</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="about_title_color_picker" 
                               value="<?= Setting::get('about_title_color', '#ffffff') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('about_title_color').value = this.value; updateAboutLivePreview();">
                        <input type="text" id="about_title_color" name="about_title_color" class="form-input" 
                               value="<?= View::e(Setting::get('about_title_color', '#ffffff')) ?>" 
                               placeholder="#ffffff"
                               oninput="if(this.value.length===7){document.getElementById('about_title_color_picker').value=this.value;} updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Cor da tipografia serifada de destaque.</span>
                </div>

                <div class="form-group">
                    <label for="about_text_color">Cor dos Parágrafos / Textos</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="about_text_color_picker" 
                               value="<?= Setting::get('about_text_color', '#ded7cf') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('about_text_color').value = this.value; updateAboutLivePreview();">
                        <input type="text" id="about_text_color" name="about_text_color" class="form-input" 
                               value="<?= View::e(Setting::get('about_text_color', '#ded7cf')) ?>" 
                               placeholder="#ded7cf"
                               oninput="if(this.value.length===7){document.getElementById('about_text_color_picker').value=this.value;} updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Cor dos parágrafos descritivos da história.</span>
                </div>

                <div class="form-group">
                    <label for="about_accent_color">Cor de Destaque / Ouro (Citação & Detalhes)</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="about_accent_color_picker" 
                               value="<?= Setting::get('about_accent_color', '#CBD0D6') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('about_accent_color').value = this.value; updateAboutLivePreview();">
                        <input type="text" id="about_accent_color" name="about_accent_color" class="form-input" 
                               value="<?= View::e(Setting::get('about_accent_color', '#CBD0D6')) ?>" 
                               placeholder="#CBD0D6"
                               oninput="if(this.value.length===7){document.getElementById('about_accent_color_picker').value=this.value;} updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Cor da citação poética em itálico e toques nobres.</span>
                </div>

                <div class="form-group">
                    <label for="about_tag_color">Cor da Tag / Selo Superior</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="about_tag_color_picker" 
                               value="<?= Setting::get('about_tag_color', '#CBD0D6') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('about_tag_color').value = this.value; updateAboutLivePreview();">
                        <input type="text" id="about_tag_color" name="about_tag_color" class="form-input" 
                               value="<?= View::e(Setting::get('about_tag_color', '#CBD0D6')) ?>" 
                               placeholder="#CBD0D6"
                               oninput="if(this.value.length===7){document.getElementById('about_tag_color_picker').value=this.value;} updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Cor do selo 'MANIFESTO DIVINIUM' acima do título.</span>
                </div>

                <div class="form-group">
                    <label for="about_btn_color">Cor do Botão de Ação (Borda & Texto)</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="about_btn_color_picker" 
                               value="<?= Setting::get('about_btn_color', '#CBD0D6') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('about_btn_color').value = this.value; updateAboutLivePreview();">
                        <input type="text" id="about_btn_color" name="about_btn_color" class="form-input" 
                               value="<?= View::e(Setting::get('about_btn_color', '#CBD0D6')) ?>" 
                               placeholder="#CBD0D6"
                               oninput="if(this.value.length===7){document.getElementById('about_btn_color_picker').value=this.value;} updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Cor do botão 'Conheça Nossa História'.</span>
                </div>

                <div class="form-group">
                    <label for="about_card_bg">Fundo do Card da Miniatura Macro</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="about_card_bg_picker" 
                               value="<?= Setting::get('about_card_bg', '#161513') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('about_card_bg').value = this.value; updateAboutLivePreview();">
                        <input type="text" id="about_card_bg" name="about_card_bg" class="form-input" 
                               value="<?= View::e(Setting::get('about_card_bg', '#161513')) ?>" 
                               placeholder="#161513"
                               oninput="if(this.value.length===7){document.getElementById('about_card_bg_picker').value=this.value;} updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Cor de fundo do card de textura mineral.</span>
                </div>

                <div class="form-group">
                    <label for="about_card_border">Borda do Card da Miniatura Macro</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="text" id="about_card_border" name="about_card_border" class="form-input" 
                               value="<?= View::e(Setting::get('about_card_border', 'rgba(212, 186, 136, 0.25)')) ?>" 
                               placeholder="rgba(212, 186, 136, 0.25)"
                               oninput="updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Ex: rgba(212, 186, 136, 0.25) ou #332d24.</span>
                </div>

                <div class="form-group full-width">
                    <label for="about_caption_bg">Fundo da Legenda Editorial da Foto</label>
                    <div style="display: flex; gap: 8px; align-items: center; max-width: 450px;">
                        <input type="text" id="about_caption_bg" name="about_caption_bg" class="form-input" 
                               value="<?= View::e(Setting::get('about_caption_bg', 'rgba(18, 17, 16, 0.85)')) ?>" 
                               placeholder="rgba(18, 17, 16, 0.85)"
                               oninput="updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Fundo translúcido da barra inferior da foto editorial lateral.</span>
                </div>

                <!-- PREVIEW AO VIVO DO MANIFESTO NO PAINEL -->
                <div class="form-group full-width" style="margin: 10px 0 20px;">
                    <label style="color: #CBD0D6; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 6px;">
                        <span>👁️</span> Pré-Visualização em Tempo Real do Manifesto
                    </label>
                    <div id="aboutLivePreviewContainer" style="background: <?= View::e(Setting::get('about_bg_color', '#252525')) ?>; border: 1px solid rgba(212,186,136,0.3); border-radius: 12px; padding: 26px; transition: background 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 24px; align-items: center;">
                            <!-- Coluna de Textos -->
                            <div>
                                <span id="aboutPreviewTag" style="display: inline-block; font-size: 0.72rem; letter-spacing: 0.15em; font-weight: 600; text-transform: uppercase; color: <?= View::e(Setting::get('about_tag_color', '#CBD0D6')) ?>; margin-bottom: 8px;">
                                    <?= View::e(Setting::get('about_tag', 'MANIFESTO DIVINIUM')) ?>
                                </span>
                                <h3 id="aboutPreviewTitle" style="font-family: serif; font-size: 1.45rem; line-height: 1.25; margin: 0 0 12px; font-weight: normal; color: <?= View::e(Setting::get('about_title_color', '#ffffff')) ?>;">
                                    <?= View::e(Setting::get('about_title', 'A Beleza Não Precisa Gritar Para Ser Notada')) ?>
                                </h3>
                                <p id="aboutPreviewP1" style="font-size: 0.84rem; line-height: 1.6; margin: 0 0 10px; color: <?= View::e(Setting::get('about_text_color', '#ded7cf')) ?>;">
                                    <?= View::e(Setting::get('about_p1', 'A DIVINIUM nasceu da convicção de que o verdadeiro luxo é autoral, sutil e profundamente conectado à sua identidade...')) ?>
                                </p>
                                <p id="aboutPreviewQuote" style="font-family: serif; font-style: italic; font-size: 1.05rem; margin: 0 0 16px; color: <?= View::e(Setting::get('about_accent_color', '#CBD0D6')) ?>; <?= Setting::get('about_quote_visible', '1') !== '1' ? 'display: none;' : '' ?>">
                                    "<?= View::e(Setting::get('about_quote', 'Qual delas conversa com você hoje?')) ?>"
                                </p>
                                <div id="aboutPreviewBtnWrap" style="margin-bottom: 16px; <?= Setting::get('about_cta_visible', '1') !== '1' ? 'display: none;' : '' ?>">
                                    <span id="aboutPreviewBtn" style="display: inline-block; padding: 7px 20px; font-size: 0.76rem; border: 1px solid <?= View::e(Setting::get('about_btn_color', '#CBD0D6')) ?>; color: <?= View::e(Setting::get('about_btn_color', '#CBD0D6')) ?>; border-radius: 20px; letter-spacing: 0.05em; font-weight: 500;">
                                        <?= View::e(Setting::get('about_cta_text', 'Conheça Nossa História')) ?>
                                    </span>
                                </div>
                                <!-- Mini Card -->
                                <div id="aboutPreviewCard" style="display: flex; gap: 12px; align-items: center; padding: 12px; border-radius: 8px; background: <?= View::e(Setting::get('about_card_bg', '#161513')) ?>; border: 1px solid <?= View::e(Setting::get('about_card_border', 'rgba(212, 186, 136, 0.25)')) ?>; max-width: 320px;">
                                    <div style="width: 44px; height: 44px; border-radius: 6px; overflow: hidden; flex-shrink: 0; background: #222;">
                                        <img src="<?= View::e(Setting::get('about_micro_img', '/assets/images/about/macro-texture.jpg')) ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='/assets/images/about/macro-texture.png'">
                                    </div>
                                    <div>
                                        <strong id="aboutPreviewCardTitle" style="display: block; font-size: 0.8rem; color: <?= View::e(Setting::get('about_title_color', '#ffffff')) ?>;">
                                            <?= View::e(Setting::get('about_micro_title', 'Textura Mineral Autêntica')) ?>
                                        </strong>
                                        <span id="aboutPreviewCardDesc" style="font-size: 0.7rem; color: <?= View::e(Setting::get('about_text_color', '#ded7cf')) ?>;">
                                            <?= View::e(Setting::get('about_micro_desc', 'Fragmentos minerais nobres preservados com resina.')) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Coluna Imagem Editorial -->
                            <div style="position: relative; border-radius: 8px; overflow: hidden; height: 280px; box-shadow: 0 4px 15px rgba(0,0,0,0.4);">
                                <img src="<?= View::e(Setting::get('about_banner_img', '/assets/images/about/banner-caminhos.jpg')) ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='/assets/images/about/banner-caminhos.png'">
                                <div id="aboutPreviewCaption" style="position: absolute; bottom: 8px; left: 8px; right: 8px; padding: 6px 10px; border-radius: 4px; font-size: 0.65rem; text-align: center; letter-spacing: 0.1em; font-weight: 600; background: <?= View::e(Setting::get('about_caption_bg', 'rgba(18, 17, 16, 0.85)')) ?>; color: <?= View::e(Setting::get('about_accent_color', '#CBD0D6')) ?>; <?= Setting::get('about_editorial_caption_visible', '1') !== '1' ? 'display: none;' : '' ?>">
                                    <?= View::e(Setting::get('about_editorial_caption', 'COLEÇÃO CAMINHOS • UM MESMO DESIGN, DIFERENTES FORMAS DE SENTIR')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- 2. TEXTOS, CHAMADAS E FOTOS DO MANIFESTO -->
                <!-- ============================================================== -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px; margin-top: 8px;">
                    <h4 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.98rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                        <span>✍️</span> 2. Textos, Chamadas & Imagens do Manifesto
                    </h4>
                    <span class="field-hint">Altere o conteúdo editorial do Manifesto Divinium.</span>
                </div>

                <div class="form-group">
                    <label for="about_tag">Tag da Seção</label>
                    <input type="text" id="about_tag" name="about_tag" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_tag', 'MANIFESTO DIVINIUM')) ?>"
                           placeholder="MANIFESTO DIVINIUM"
                           oninput="updateAboutLivePreview()">
                </div>

                <div class="form-group">
                    <label for="about_title">Título Principal do Manifesto</label>
                    <input type="text" id="about_title" name="about_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_title', 'A Beleza Não Precisa Gritar Para Ser Notada')) ?>"
                           placeholder="A Beleza Não Precisa Gritar Para Ser Notada"
                           oninput="updateAboutLivePreview()">
                </div>

                <div class="form-group full-width">
                    <label for="about_p1">Primeiro Parágrafo do Manifesto</label>
                    <textarea id="about_p1" name="about_p1" class="form-textarea" rows="3" oninput="updateAboutLivePreview()"><?= \App\Core\View::e(Setting::get('about_p1', 'A DIVINIUM nasceu da convicção de que o verdadeiro luxo é autoral, sutil e profundamente conectado à sua identidade. Em um mercado de réplicas e produções em massa, escolhemos a rota da nobreza mineral.')) ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="about_p2">Segundo Parágrafo do Manifesto</label>
                    <textarea id="about_p2" name="about_p2" class="form-textarea" rows="3"><?= \App\Core\View::e(Setting::get('about_p2', 'Cada gema é única em suas veias, coloração e densidade. Encastoadas à mão sobre o aro duplo em prata, as pedras ganham vida e presença táctil sem jamais pesar no pulso.')) ?></textarea>
                </div>

                <div class="form-group <?= Setting::get('about_quote_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="about_quote">Frase / Pergunta Poética de Destaque</label>
                        <?= $renderToggle('about_quote_visible') ?>
                    </div>
                    <input type="text" id="about_quote" name="about_quote" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_quote', 'Qual delas conversa com você hoje?')) ?>"
                           placeholder="Qual delas conversa com você hoje?"
                           oninput="updateAboutLivePreview()">
                    <span class="field-hint">Frase poética reflexiva exibida em itálico abaixo dos parágrafos do manifesto.</span>
                </div>

                <div class="form-group <?= Setting::get('about_cta_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="about_cta_text">Texto do Botão de Ação</label>
                        <?= $renderToggle('about_cta_visible') ?>
                    </div>
                    <input type="text" id="about_cta_text" name="about_cta_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_cta_text', 'Conheça Nossa História')) ?>"
                           placeholder="Conheça Nossa História"
                           oninput="updateAboutLivePreview()">
                    <span class="field-hint">Rótulo do botão de chamada para ação do Manifesto.</span>
                </div>

                <div class="form-group <?= Setting::get('about_cta_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="about_cta_url">Destino / Link do Botão (URL ou Âncora)</label>
                    <input type="text" id="about_cta_url" name="about_cta_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_cta_url', '#services')) ?>"
                           placeholder="#services ou /loja">
                    <span class="field-hint">Endereço de destino ao clicar no botão.</span>
                </div>

                <!-- Foto e Macro do Sobre -->
                <div class="form-group">
                    <label for="about_micro_title">Título da Miniatura Macro</label>
                    <input type="text" id="about_micro_title" name="about_micro_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_micro_title', 'Textura Mineral Autêntica')) ?>"
                           oninput="updateAboutLivePreview()">
                </div>

                <div class="form-group">
                    <label for="about_micro_desc">Descrição da Miniatura Macro</label>
                    <input type="text" id="about_micro_desc" name="about_micro_desc" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_micro_desc', 'Fragmentos minerais nobres preservados com resina de alta transparência.')) ?>"
                           oninput="updateAboutLivePreview()">
                </div>

                <div class="form-group">
                    <label for="about_micro_img">Imagem Macro da Gema</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="about_micro_img" name="about_micro_img" class="form-input"
                               value="<?= \App\Core\View::e(Setting::get('about_micro_img', '/assets/images/about/macro-texture.jpg')) ?>">
                        <label class="btn-admin-secondary" style="cursor: pointer; white-space: nowrap; padding: 8px 12px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 4px;">
                            <span>📁 Enviar</span>
                            <input type="file" accept="image/*" style="display: none;" onchange="uploadMediaFile(this, 'about_micro_img', 'editorials')">
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="about_banner_img">Imagem Editorial Lateral (Banner Caminhos)</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="about_banner_img" name="about_banner_img" class="form-input"
                               value="<?= \App\Core\View::e(Setting::get('about_banner_img', '/assets/images/about/banner-caminhos.jpg')) ?>">
                        <label class="btn-admin-secondary" style="cursor: pointer; white-space: nowrap; padding: 8px 12px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 4px;">
                            <span>📁 Enviar</span>
                            <input type="file" accept="image/*" style="display: none;" onchange="uploadMediaFile(this, 'about_banner_img', 'editorials')">
                        </label>
                    </div>
                </div>

                <div class="form-group full-width <?= Setting::get('about_editorial_caption_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="about_editorial_caption">Legenda da Foto Editorial</label>
                        <?= $renderToggle('about_editorial_caption_visible') ?>
                    </div>
                    <input type="text" id="about_editorial_caption" name="about_editorial_caption" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_editorial_caption', 'COLEÇÃO CAMINHOS • UM MESMO DESIGN, DIFERENTES FORMAS DE SENTIR')) ?>"
                           oninput="updateAboutLivePreview()">
                    <span class="field-hint">Legenda translúcida com destaque mineral exibida sobre a foto editorial.</span>
                </div>

                <!-- Pilares Conceituais -->
                <div class="form-group">
                    <label for="about_pillar1_title">Pilar 1 - Título</label>
                    <input type="text" id="about_pillar1_title" name="about_pillar1_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_pillar1_title', 'Design Autoral & Exclusivo')) ?>">
                </div>

                <div class="form-group">
                    <label for="about_pillar1_desc">Pilar 1 - Descrição</label>
                    <input type="text" id="about_pillar1_desc" name="about_pillar1_desc" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_pillar1_desc', 'Projetado no Brasil por mestres ourives, com tiragens limitadas e acabamento impecável.')) ?>">
                </div>

                <div class="form-group">
                    <label for="about_pillar2_title">Pilar 2 - Título</label>
                    <input type="text" id="about_pillar2_title" name="about_pillar2_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_pillar2_title', 'Metais Nobres & Gemas')) ?>">
                </div>

                <div class="form-group">
                    <label for="about_pillar2_desc">Pilar 2 - Descrição</label>
                    <input type="text" id="about_pillar2_desc" name="about_pillar2_desc" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_pillar2_desc', 'Prata de Lei 925 legítima aliada a pedras naturais selecionadas uma a uma.')) ?>">
                </div>

                <div class="form-group">
                    <label for="about_pillar3_title">Pilar 3 - Título</label>
                    <input type="text" id="about_pillar3_title" name="about_pillar3_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_pillar3_title', 'Garantia & Autenticidade')) ?>">
                </div>

                <div class="form-group">
                    <label for="about_pillar3_desc">Pilar 3 - Descrição</label>
                    <input type="text" id="about_pillar3_desc" name="about_pillar3_desc" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_pillar3_desc', 'Cada peça é acompanhada do certificado oficial de autenticidade e pureza dos materiais.')) ?>">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 6: VITRINE DE PRODUTOS & FILTROS NA HOME -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-collections">
            <div class="section-card-header">
                <div class="section-icon">✦</div>
                <div>
                    <h3>Vitrine de Produtos, Filtros & Card Lifestyle na Home</h3>
                    <p>Configurações legadas de texto da vitrine. Para gerenciar coleções por categoria, utilize a nova aba Lançamentos.</p>
                </div>
            </div>

            <div style="background: rgba(212,186,136,0.1); border: 1px solid rgba(212,186,136,0.25); border-radius: 8px; padding: 14px 18px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 15px;">
                <div>
                    <strong style="color: #CBD0D6; display: block; margin-bottom: 4px;">🚀 Gerenciamento de Coleções centralizado na aba Lançamentos</strong>
                    <span style="color: #a8a096; font-size: 0.85rem;">Você pode criar, editar, reordenar coleções e gerenciar produtos separados por categoria (Braceletes, Colares, Anéis) na aba Lançamentos.</span>
                </div>
                <a href="/admin/lancamentos" class="btn-admin-gold" style="white-space: nowrap; font-size: 0.82rem; padding: 8px 16px; text-decoration: none;">
                    Ir para Lançamentos ↗
                </a>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="collections_tag">Tag da Seção</label>
                    <input type="text" id="collections_tag" name="collections_tag" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('collections_tag', 'SELEÇÃO EXCLUSIVA')) ?>"
                           placeholder="SELEÇÃO EXCLUSIVA">
                </div>

                <div class="form-group">
                    <label for="collections_title">Título da Vitrine</label>
                    <input type="text" id="collections_title" name="collections_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('collections_title', 'Braceletes CAMINHOS')) ?>"
                           placeholder="Braceletes CAMINHOS">
                </div>

                <!-- Filtros em Pílulas da Vitrine -->
                <div class="form-group">
                    <label for="filter_pill_all">Pílula: Todas as Peças</label>
                    <input type="text" id="filter_pill_all" name="filter_pill_all" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('filter_pill_all', 'Todas as Peças')) ?>">
                </div>

                <div class="form-group">
                    <label for="filter_pill_verde">Pílula: Quartzo Verde</label>
                    <input type="text" id="filter_pill_verde" name="filter_pill_verde" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('filter_pill_verde', 'Quartzo Verde')) ?>">
                </div>

                <div class="form-group">
                    <label for="filter_pill_madre">Pílula: Madrepérola</label>
                    <input type="text" id="filter_pill_madre" name="filter_pill_madre" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('filter_pill_madre', 'Madrepérola')) ?>">
                </div>

                <div class="form-group">
                    <label for="filter_pill_verm">Pílula: Jaspe Vermelho</label>
                    <input type="text" id="filter_pill_verm" name="filter_pill_verm" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('filter_pill_verm', 'Jaspe Vermelho')) ?>">
                </div>

                <div class="form-group">
                    <label for="filter_pill_onix">Pílula: Ônix Negro</label>
                    <input type="text" id="filter_pill_onix" name="filter_pill_onix" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('filter_pill_onix', 'Ônix Negro')) ?>">
                </div>

                <!-- Card Editorial Lateral (Coleção no Espelho) -->
                <div class="form-group">
                    <label for="showcase_lifestyle_title">Card Espelho - Título</label>
                    <input type="text" id="showcase_lifestyle_title" name="showcase_lifestyle_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('showcase_lifestyle_title', 'Harmonia Mineral no Espelho')) ?>">
                </div>

                <div class="form-group">
                    <label for="showcase_lifestyle_img">Card Espelho - Imagem</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="showcase_lifestyle_img" name="showcase_lifestyle_img" class="form-input"
                               value="<?= \App\Core\View::e(Setting::get('showcase_lifestyle_img', '/assets/images/products/colecao-completa-espelho.jpg')) ?>">
                        <label class="btn-admin-secondary" style="cursor: pointer; white-space: nowrap; padding: 8px 12px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 4px;">
                            <span>📁 Enviar</span>
                            <input type="file" accept="image/*" style="display: none;" onchange="uploadMediaFile(this, 'showcase_lifestyle_img', 'editorials')">
                        </label>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="showcase_lifestyle_text">Card Espelho - Descrição</label>
                    <textarea id="showcase_lifestyle_text" name="showcase_lifestyle_text" class="form-textarea" rows="2"><?= \App\Core\View::e(Setting::get('showcase_lifestyle_text', 'Seis caminhos, seis vibrações. Combine mais de uma cor para expressar as diferentes nuances da sua presença.')) ?></textarea>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 7: SERVIÇOS & GARANTIA -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-services">
            <div class="section-card-header">
                <div class="section-icon">🛡️</div>
                <div>
                    <h3>Experiência de Serviços Exclusivos</h3>
                    <p>Destaques sobre atendimento sob medida, pós-venda e garantia.</p>
                </div>
            </div>

            <div class="form-grid">
                <!-- ============================================================== -->
                <!-- 1. PERSONALIZAÇÃO DE CORES & ESTÉTICA DA SEÇÃO -->
                <!-- ============================================================== -->
                <div class="form-group full-width" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 16px; margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <div>
                            <h4 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.98rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                                <span>🎨</span> 1. Cores & Estética Visual dos Serviços Exclusivos
                            </h4>
                            <span class="field-hint">Personalize as cores de fundo, cartões, títulos, textos, números/ícones, bordas e botões da seção.</span>
                        </div>

                        <!-- Presets Rápidos de Cores -->
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <button type="button" class="btn-admin-secondary" style="padding: 6px 11px; font-size: 0.76rem;" onclick="applyServicesColorPreset('default')">
                                <span>🖤 Quiet Luxury (Padrão)</span>
                            </button>
                            <button type="button" class="btn-admin-secondary" style="padding: 6px 11px; font-size: 0.76rem;" onclick="applyServicesColorPreset('light')">
                                <span>🤍 Pérola & Platina</span>
                            </button>
                            <button type="button" class="btn-admin-secondary" style="padding: 6px 11px; font-size: 0.76rem;" onclick="applyServicesColorPreset('noir')">
                                <span>🌑 Noir Grafite</span>
                            </button>
                            <button type="button" class="btn-admin-cancel" style="padding: 6px 11px; font-size: 0.76rem;" onclick="resetServicesColors()">
                                <span>↺ Restaurar Padrões</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 1. FUNDO DA SEÇÃO -->
                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label for="services_bg_color" style="margin: 0; font-weight: 600;">Cor de Fundo da Seção</label>
                        <button type="button" class="btn-admin-cancel" style="padding: 2px 7px; font-size: 0.7rem;" onclick="clearFieldServices('services_bg_color', 'services_bg_color_picker', '#252525')">
                            ✕ Limpar
                        </button>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="services_bg_color_picker" 
                               value="<?= Setting::get('services_bg_color') ? Setting::get('services_bg_color') : '#252525' ?>" 
                               style="width: 44px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('services_bg_color').value = this.value; updateServicesLivePreview();">
                        <input type="text" id="services_bg_color" name="services_bg_color" class="form-input" 
                               value="<?= View::e(Setting::get('services_bg_color', '')) ?>" 
                               placeholder="Ex: #252525 ou deixe vazio para padrão"
                               oninput="if(this.value.length===7 || this.value.length===4){document.getElementById('services_bg_color_picker').value=this.value;} updateServicesLivePreview();">
                    </div>
                    <div style="display: flex; gap: 6px; align-items: center; margin-top: 6px; flex-wrap: wrap;">
                        <span style="font-size: 0.7rem; color: #888;">Rápido:</span>
                        <button type="button" onclick="setQuickColorServices('services_bg_color', 'services_bg_color_picker', '#252525')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #252525; color: #fff; border: 1px solid #555; cursor: pointer;">#252525</button>
                        <button type="button" onclick="setQuickColorServices('services_bg_color', 'services_bg_color_picker', '#14161a')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #14161a; color: #fff; border: 1px solid #333; cursor: pointer;">#14161a</button>
                        <button type="button" onclick="setQuickColorServices('services_bg_color', 'services_bg_color_picker', '#0d0f12')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #0d0f12; color: #fff; border: 1px solid #333; cursor: pointer;">#0d0f12</button>
                        <button type="button" onclick="setQuickColorServices('services_bg_color', 'services_bg_color_picker', '#F8F8F7')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #F8F8F7; color: #222; border: 1px solid #ccc; cursor: pointer;">#F8F8F7</button>
                        <button type="button" onclick="setQuickColorServices('services_bg_color', 'services_bg_color_picker', '#FFFFFF')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #FFFFFF; color: #222; border: 1px solid #ccc; cursor: pointer;">#FFFFFF</button>
                    </div>
                    <span class="field-hint">Fundo geral da seção no site.</span>
                </div>

                <!-- 2. FUNDO DOS CARDS DE SERVIÇO -->
                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label for="services_card_bg" style="margin: 0; font-weight: 600;">Cor de Fundo dos Cards / Blocos</label>
                        <button type="button" class="btn-admin-cancel" style="padding: 2px 7px; font-size: 0.7rem;" onclick="clearFieldServices('services_card_bg', 'services_card_bg_picker', '#1a1d22')">
                            ✕ Limpar
                        </button>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="services_card_bg_picker" 
                               value="<?= Setting::get('services_card_bg') ? Setting::get('services_card_bg') : '#1a1d22' ?>" 
                               style="width: 44px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('services_card_bg').value = this.value; updateServicesLivePreview();">
                        <input type="text" id="services_card_bg" name="services_card_bg" class="form-input" 
                               value="<?= View::e(Setting::get('services_card_bg', '')) ?>" 
                               placeholder="Ex: rgba(255,255,255,0.03) ou #1a1d22"
                               oninput="if(this.value.length===7 || this.value.length===4){document.getElementById('services_card_bg_picker').value=this.value;} updateServicesLivePreview();">
                    </div>
                    <div style="display: flex; gap: 6px; align-items: center; margin-top: 6px; flex-wrap: wrap;">
                        <span style="font-size: 0.7rem; color: #888;">Rápido:</span>
                        <button type="button" onclick="setQuickColorServices('services_card_bg', 'services_card_bg_picker', 'rgba(255,255,255,0.03)')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #2a2a2a; color: #fff; border: 1px solid #444; cursor: pointer;">Translúcido</button>
                        <button type="button" onclick="setQuickColorServices('services_card_bg', 'services_card_bg_picker', '#14161a')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #14161a; color: #fff; border: 1px solid #333; cursor: pointer;">#14161a</button>
                        <button type="button" onclick="setQuickColorServices('services_card_bg', 'services_card_bg_picker', '#1f2228')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #1f2228; color: #fff; border: 1px solid #444; cursor: pointer;">#1f2228</button>
                        <button type="button" onclick="setQuickColorServices('services_card_bg', 'services_card_bg_picker', '#FFFFFF')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #FFFFFF; color: #222; border: 1px solid #ccc; cursor: pointer;">#FFFFFF</button>
                        <button type="button" onclick="setQuickColorServices('services_card_bg', 'services_card_bg_picker', '#F0F0EF')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #F0F0EF; color: #222; border: 1px solid #aaa; cursor: pointer;">#F0F0EF</button>
                    </div>
                    <span class="field-hint">Fundo individual de cada um dos 3 itens de serviço.</span>
                </div>

                <!-- 3. TÍTULOS -->
                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label for="services_title_color" style="margin: 0; font-weight: 600;">Cor dos Títulos (Principal e Serviços)</label>
                        <button type="button" class="btn-admin-cancel" style="padding: 2px 7px; font-size: 0.7rem;" onclick="clearFieldServices('services_title_color', 'services_title_color_picker', '#ffffff')">
                            ✕ Limpar
                        </button>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="services_title_color_picker" 
                               value="<?= Setting::get('services_title_color') ? Setting::get('services_title_color') : '#ffffff' ?>" 
                               style="width: 44px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('services_title_color').value = this.value; updateServicesLivePreview();">
                        <input type="text" id="services_title_color" name="services_title_color" class="form-input" 
                               value="<?= View::e(Setting::get('services_title_color', '')) ?>" 
                               placeholder="Ex: #FFFFFF ou #14161a"
                               oninput="if(this.value.length===7 || this.value.length===4){document.getElementById('services_title_color_picker').value=this.value;} updateServicesLivePreview();">
                    </div>
                    <div style="display: flex; gap: 6px; align-items: center; margin-top: 6px; flex-wrap: wrap;">
                        <span style="font-size: 0.7rem; color: #888;">Rápido:</span>
                        <button type="button" onclick="setQuickColorServices('services_title_color', 'services_title_color_picker', '#FFFFFF')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #FFFFFF; color: #111; border: 1px solid #ccc; cursor: pointer;">Branco #FFF</button>
                        <button type="button" onclick="setQuickColorServices('services_title_color', 'services_title_color_picker', '#CBD0D6')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #CBD0D6; color: #111; border: 1px solid #aaa; cursor: pointer;">Prata #CBD0D6</button>
                        <button type="button" onclick="setQuickColorServices('services_title_color', 'services_title_color_picker', '#14161a')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #14161a; color: #fff; border: 1px solid #333; cursor: pointer;">Grafite #14161a</button>
                    </div>
                    <span class="field-hint">Aplica-se ao título principal da seção e aos nomes de cada serviço.</span>
                </div>

                <!-- 4. SUBTÍTULO -->
                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label for="services_subtitle_color" style="margin: 0; font-weight: 600;">Cor do Subtítulo Explicativo</label>
                        <button type="button" class="btn-admin-cancel" style="padding: 2px 7px; font-size: 0.7rem;" onclick="clearFieldServices('services_subtitle_color', 'services_subtitle_color_picker', '#CBD0D6')">
                            ✕ Limpar
                        </button>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="services_subtitle_color_picker" 
                               value="<?= Setting::get('services_subtitle_color') ? Setting::get('services_subtitle_color') : '#CBD0D6' ?>" 
                               style="width: 44px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('services_subtitle_color').value = this.value; updateServicesLivePreview();">
                        <input type="text" id="services_subtitle_color" name="services_subtitle_color" class="form-input" 
                               value="<?= View::e(Setting::get('services_subtitle_color', '')) ?>" 
                               placeholder="Ex: #CBD0D6 ou #555c66"
                               oninput="if(this.value.length===7 || this.value.length===4){document.getElementById('services_subtitle_color_picker').value=this.value;} updateServicesLivePreview();">
                    </div>
                    <div style="display: flex; gap: 6px; align-items: center; margin-top: 6px; flex-wrap: wrap;">
                        <span style="font-size: 0.7rem; color: #888;">Rápido:</span>
                        <button type="button" onclick="setQuickColorServices('services_subtitle_color', 'services_subtitle_color_picker', '#CBD0D6')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #CBD0D6; color: #111; border: 1px solid #aaa; cursor: pointer;">Prata #CBD0D6</button>
                        <button type="button" onclick="setQuickColorServices('services_subtitle_color', 'services_subtitle_color_picker', '#E5E8EC')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #E5E8EC; color: #111; border: 1px solid #ccc; cursor: pointer;">Gelo #E5E8EC</button>
                        <button type="button" onclick="setQuickColorServices('services_subtitle_color', 'services_subtitle_color_picker', '#4a525d')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #4a525d; color: #fff; border: 1px solid #333; cursor: pointer;">Cinza Médio #4a525d</button>
                    </div>
                    <span class="field-hint">Texto descritivo posicionado logo abaixo do título principal.</span>
                </div>

                <!-- 5. TEXTOS DOS ITENS / DETALHES -->
                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label for="services_text_color" style="margin: 0; font-weight: 600;">Cor dos Textos / Lista de Detalhes</label>
                        <button type="button" class="btn-admin-cancel" style="padding: 2px 7px; font-size: 0.7rem;" onclick="clearFieldServices('services_text_color', 'services_text_color_picker', '#a3a8b0')">
                            ✕ Limpar
                        </button>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="services_text_color_picker" 
                               value="<?= Setting::get('services_text_color') ? Setting::get('services_text_color') : '#a3a8b0' ?>" 
                               style="width: 44px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('services_text_color').value = this.value; updateServicesLivePreview();">
                        <input type="text" id="services_text_color" name="services_text_color" class="form-input" 
                               value="<?= View::e(Setting::get('services_text_color', '')) ?>" 
                               placeholder="Ex: #a3a8b0 ou #606975"
                               oninput="if(this.value.length===7 || this.value.length===4){document.getElementById('services_text_color_picker').value=this.value;} updateServicesLivePreview();">
                    </div>
                    <div style="display: flex; gap: 6px; align-items: center; margin-top: 6px; flex-wrap: wrap;">
                        <span style="font-size: 0.7rem; color: #888;">Rápido:</span>
                        <button type="button" onclick="setQuickColorServices('services_text_color', 'services_text_color_picker', '#a3a8b0')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #a3a8b0; color: #111; border: 1px solid #888; cursor: pointer;">Suave #a3a8b0</button>
                        <button type="button" onclick="setQuickColorServices('services_text_color', 'services_text_color_picker', '#D0D4DA')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #D0D4DA; color: #111; border: 1px solid #aaa; cursor: pointer;">Claro #D0D4DA</button>
                        <button type="button" onclick="setQuickColorServices('services_text_color', 'services_text_color_picker', '#606975')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #606975; color: #fff; border: 1px solid #444; cursor: pointer;">Grafite #606975</button>
                    </div>
                    <span class="field-hint">Cor dos 3 tópicos com marcadores de cada serviço.</span>
                </div>

                <!-- 6. ÍCONES E NÚMEROS -->
                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label for="services_icon_color" style="margin: 0; font-weight: 600;">Cor dos Números (01, 02, 03) & Ícones/Marcadores</label>
                        <button type="button" class="btn-admin-cancel" style="padding: 2px 7px; font-size: 0.7rem;" onclick="clearFieldServices('services_icon_color', 'services_icon_color_picker', '#CBD0D6')">
                            ✕ Limpar
                        </button>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="services_icon_color_picker" 
                               value="<?= Setting::get('services_icon_color') ? Setting::get('services_icon_color') : '#CBD0D6' ?>" 
                               style="width: 44px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('services_icon_color').value = this.value; updateServicesLivePreview();">
                        <input type="text" id="services_icon_color" name="services_icon_color" class="form-input" 
                               value="<?= View::e(Setting::get('services_icon_color', '')) ?>" 
                               placeholder="Ex: #CBD0D6 ou #FFFFFF"
                               oninput="if(this.value.length===7 || this.value.length===4){document.getElementById('services_icon_color_picker').value=this.value;} updateServicesLivePreview();">
                    </div>
                    <div style="display: flex; gap: 6px; align-items: center; margin-top: 6px; flex-wrap: wrap;">
                        <span style="font-size: 0.7rem; color: #888;">Rápido:</span>
                        <button type="button" onclick="setQuickColorServices('services_icon_color', 'services_icon_color_picker', '#CBD0D6')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #CBD0D6; color: #111; border: 1px solid #aaa; cursor: pointer;">Prata #CBD0D6</button>
                        <button type="button" onclick="setQuickColorServices('services_icon_color', 'services_icon_color_picker', '#FFFFFF')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #FFFFFF; color: #111; border: 1px solid #ccc; cursor: pointer;">Branco #FFF</button>
                        <button type="button" onclick="setQuickColorServices('services_icon_color', 'services_icon_color_picker', '#14161a')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #14161a; color: #fff; border: 1px solid #333; cursor: pointer;">Grafite #14161a</button>
                    </div>
                    <span class="field-hint">Cor dos numerais '01, 02, 03' e dos marcadores da lista.</span>
                </div>

                <!-- 7. BORDAS E DIVISÓRIAS -->
                <div class="form-group full-width">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label for="services_border_color" style="margin: 0; font-weight: 600;">Cor das Bordas & Linhas Divisórias</label>
                        <button type="button" class="btn-admin-cancel" style="padding: 2px 7px; font-size: 0.7rem;" onclick="clearFieldServices('services_border_color', 'services_border_color_picker', 'rgba(255, 255, 255, 0.08)')">
                            ✕ Limpar
                        </button>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center; max-width: 480px;">
                        <input type="color" id="services_border_color_picker" 
                               value="<?= (strpos(Setting::get('services_border_color', ''), '#') === 0 && strlen(Setting::get('services_border_color')) === 7) ? Setting::get('services_border_color') : '#444444' ?>" 
                               style="width: 44px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('services_border_color').value = this.value; updateServicesLivePreview();">
                        <input type="text" id="services_border_color" name="services_border_color" class="form-input" 
                               value="<?= View::e(Setting::get('services_border_color', '')) ?>" 
                               placeholder="Ex: rgba(255, 255, 255, 0.08) ou #333333"
                               oninput="if(this.value.length===7 || this.value.length===4){document.getElementById('services_border_color_picker').value=this.value;} updateServicesLivePreview();">
                    </div>
                    <div style="display: flex; gap: 6px; align-items: center; margin-top: 6px; flex-wrap: wrap;">
                        <span style="font-size: 0.7rem; color: #888;">Rápido:</span>
                        <button type="button" onclick="setQuickColorServices('services_border_color', 'services_border_color_picker', 'rgba(255, 255, 255, 0.08)')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #252525; color: #ccc; border: 1px solid #444; cursor: pointer;">Sutil Branco (0.08)</button>
                        <button type="button" onclick="setQuickColorServices('services_border_color', 'services_border_color_picker', 'rgba(203, 208, 214, 0.25)')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #333; color: #CBD0D6; border: 1px solid #666; cursor: pointer;">Prata Suave (0.25)</button>
                        <button type="button" onclick="setQuickColorServices('services_border_color', 'services_border_color_picker', 'rgba(0, 0, 0, 0.10)')" style="padding: 2px 6px; font-size: 0.68rem; border-radius: 4px; background: #e0e0e0; color: #222; border: 1px solid #bbb; cursor: pointer;">Sutil Escuro (0.10)</button>
                    </div>
                    <span class="field-hint">Linhas divisórias e contornos entre cada serviço.</span>
                </div>

                <!-- ============================================================== -->
                <!-- 8. PERSONALIZAÇÃO DO BOTÃO DE AÇÃO / CTA -->
                <!-- ============================================================== -->
                <div class="form-group full-width" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 16px; margin: 12px 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
                        <label style="font-size: 0.88rem; font-weight: 700; color: #CBD0D6; margin: 0; text-transform: uppercase; letter-spacing: 0.04em; display: flex; align-items: center; gap: 8px;">
                            <span>🔘</span> Personalização do Botão de Ação (CTA)
                        </label>
                        <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.8rem; color: #fff;">
                            <input type="checkbox" id="services_cta_visible" name="services_cta_visible" value="1" 
                                   <?= Setting::get('services_cta_visible', '1') === '1' ? 'checked' : '' ?>
                                   onchange="updateServicesLivePreview()">
                            <span>Exibir Botão nesta Seção</span>
                        </label>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 14px; margin-bottom: 14px;">
                        <div>
                            <label for="services_cta_text" style="font-size: 0.8rem;">Texto do Botão</label>
                            <input type="text" id="services_cta_text" name="services_cta_text" class="form-input"
                                   value="<?= View::e(Setting::get('services_cta_text', 'Falar com o Concierge no WhatsApp ↗')) ?>"
                                   placeholder="Falar com o Concierge no WhatsApp ↗"
                                   oninput="updateServicesLivePreview()">
                        </div>
                        <div>
                            <label for="services_cta_url" style="font-size: 0.8rem;">Link / URL de Destino</label>
                            <input type="text" id="services_cta_url" name="services_cta_url" class="form-input"
                                   value="<?= View::e(Setting::get('services_cta_url', 'https://wa.me/5511999999999')) ?>"
                                   placeholder="https://wa.me/5511999999999 ou #contato"
                                   oninput="updateServicesLivePreview()">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px;">
                        <!-- Cor de Fundo do Botão -->
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                <label for="services_btn_bg" style="font-size: 0.78rem; margin: 0;">Fundo do Botão</label>
                                <button type="button" class="btn-admin-cancel" style="padding: 1px 6px; font-size: 0.65rem;" onclick="clearFieldServices('services_btn_bg', 'services_btn_bg_picker', '#CBD0D6')">✕</button>
                            </div>
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <input type="color" id="services_btn_bg_picker" 
                                       value="<?= Setting::get('services_btn_bg') ? Setting::get('services_btn_bg') : '#CBD0D6' ?>" 
                                       style="width: 40px; height: 34px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                                       oninput="document.getElementById('services_btn_bg').value = this.value; updateServicesLivePreview();">
                                <input type="text" id="services_btn_bg" name="services_btn_bg" class="form-input" 
                                       value="<?= View::e(Setting::get('services_btn_bg', '')) ?>" 
                                       placeholder="#CBD0D6"
                                       oninput="if(this.value.length===7 || this.value.length===4){document.getElementById('services_btn_bg_picker').value=this.value;} updateServicesLivePreview();">
                            </div>
                        </div>

                        <!-- Cor do Texto do Botão -->
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                <label for="services_btn_text" style="font-size: 0.78rem; margin: 0;">Texto do Botão</label>
                                <button type="button" class="btn-admin-cancel" style="padding: 1px 6px; font-size: 0.65rem;" onclick="clearFieldServices('services_btn_text', 'services_btn_text_picker', '#121212')">✕</button>
                            </div>
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <input type="color" id="services_btn_text_picker" 
                                       value="<?= Setting::get('services_btn_text') ? Setting::get('services_btn_text') : '#121212' ?>" 
                                       style="width: 40px; height: 34px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                                       oninput="document.getElementById('services_btn_text').value = this.value; updateServicesLivePreview();">
                                <input type="text" id="services_btn_text" name="services_btn_text" class="form-input" 
                                       value="<?= View::e(Setting::get('services_btn_text', '')) ?>" 
                                       placeholder="#121212"
                                       oninput="if(this.value.length===7 || this.value.length===4){document.getElementById('services_btn_text_picker').value=this.value;} updateServicesLivePreview();">
                            </div>
                        </div>

                        <!-- Cor da Borda do Botão -->
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                <label for="services_btn_border" style="font-size: 0.78rem; margin: 0;">Borda do Botão</label>
                                <button type="button" class="btn-admin-cancel" style="padding: 1px 6px; font-size: 0.65rem;" onclick="clearFieldServices('services_btn_border', 'services_btn_border_picker', '#CBD0D6')">✕</button>
                            </div>
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <input type="color" id="services_btn_border_picker" 
                                       value="<?= Setting::get('services_btn_border') ? Setting::get('services_btn_border') : '#CBD0D6' ?>" 
                                       style="width: 40px; height: 34px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                                       oninput="document.getElementById('services_btn_border').value = this.value; updateServicesLivePreview();">
                                <input type="text" id="services_btn_border" name="services_btn_border" class="form-input" 
                                       value="<?= View::e(Setting::get('services_btn_border', '')) ?>" 
                                       placeholder="#CBD0D6"
                                       oninput="if(this.value.length===7 || this.value.length===4){document.getElementById('services_btn_border_picker').value=this.value;} updateServicesLivePreview();">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- PRÉ-VISUALIZAÇÃO EM TEMPO REAL DA SEÇÃO DE SERVIÇOS -->
                <!-- ============================================================== -->
                <div class="form-group full-width" style="margin: 10px 0 24px;">
                    <label style="color: #CBD0D6; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 6px;">
                        <span>👁️</span> Pré-Visualização em Tempo Real: Serviços Exclusivos
                    </label>
                    
                    <div id="servicesLivePreviewContainer" style="background: <?= View::e(Setting::get('services_bg_color') ?: '#252525') ?>; border: 1px solid rgba(255,255,255,0.12); border-radius: 14px; padding: 32px 24px; transition: all 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                        
                        <!-- Header Preview -->
                        <div style="text-align: center; max-width: 680px; margin: 0 auto 28px;">
                            <h3 id="servicesPreviewMainTitle" style="font-family: serif; font-size: 1.55rem; line-height: 1.25; margin: 0 0 10px; font-weight: normal; color: <?= View::e(Setting::get('services_title_color') ?: '#ffffff') ?>; letter-spacing: 0.03em;">
                                <?= View::e(Setting::get('services_title', 'Experiência de Serviços Exclusivos')) ?>
                            </h3>
                            <p id="servicesPreviewSubtitle" style="font-size: 0.84rem; line-height: 1.6; margin: 0; color: <?= View::e(Setting::get('services_subtitle_color') ?: '#CBD0D6') ?>;">
                                <?= View::e(Setting::get('services_subtitle', 'Mais do que uma joia — um vínculo de bem-estar e sintonia. Cada bracelete Divinium é acompanhado de cuidados minuciosos e suporte sob medida.')) ?>
                            </p>
                        </div>

                        <!-- 3 Cards Grid Preview -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 28px;">
                            
                            <!-- Card 01 -->
                            <div class="servicesPreviewItemCard" style="border: 1px solid <?= View::e(Setting::get('services_border_color') ?: 'rgba(255,255,255,0.08)') ?>; background-color: <?= View::e(Setting::get('services_card_bg') ?: 'transparent') ?>; border-radius: 8px; padding: 18px 16px; transition: all 0.3s ease;">
                                <div class="servicesPreviewNum" style="font-family: serif; font-size: 1.4rem; font-weight: 700; margin-bottom: 6px; color: <?= View::e(Setting::get('services_icon_color') ?: '#CBD0D6') ?>;">01</div>
                                <div class="servicesPreviewItemTitle" style="font-weight: 700; font-size: 0.92rem; margin-bottom: 10px; color: <?= View::e(Setting::get('services_title_color') ?: '#ffffff') ?>;">
                                    <?= View::e(Setting::get('service_1_title', 'Consultoria de Sintonia Mineral')) ?>
                                </div>
                                <ul class="servicesPreviewList" style="list-style: none; padding: 0; margin: 0; font-size: 0.76rem; line-height: 1.5; color: <?= View::e(Setting::get('services_text_color') ?: '#a3a8b0') ?>;">
                                    <li style="margin-bottom: 6px; display: flex; align-items: flex-start; gap: 6px;">
                                        <span class="servicesPreviewBullet" style="color: <?= View::e(Setting::get('services_icon_color') ?: '#CBD0D6') ?>; font-size: 0.65rem; line-height: 1.5;">✦</span>
                                        <span>Ajuda personalizada para descobrir a pedra que melhor conversa com você</span>
                                    </li>
                                    <li style="display: flex; align-items: flex-start; gap: 6px;">
                                        <span class="servicesPreviewBullet" style="color: <?= View::e(Setting::get('services_icon_color') ?: '#CBD0D6') ?>; font-size: 0.65rem; line-height: 1.5;">✦</span>
                                        <span>Atendimento direto pelo nosso Concierge no WhatsApp</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Card 02 -->
                            <div class="servicesPreviewItemCard" style="border: 1px solid <?= View::e(Setting::get('services_border_color') ?: 'rgba(255,255,255,0.08)') ?>; background-color: <?= View::e(Setting::get('services_card_bg') ?: 'transparent') ?>; border-radius: 8px; padding: 18px 16px; transition: all 0.3s ease;">
                                <div class="servicesPreviewNum" style="font-family: serif; font-size: 1.4rem; font-weight: 700; margin-bottom: 6px; color: <?= View::e(Setting::get('services_icon_color') ?: '#CBD0D6') ?>;">02</div>
                                <div class="servicesPreviewItemTitle" style="font-weight: 700; font-size: 0.92rem; margin-bottom: 10px; color: <?= View::e(Setting::get('services_title_color') ?: '#ffffff') ?>;">
                                    <?= View::e(Setting::get('service_2_title', 'Cuidado Artesanal & Manutenção')) ?>
                                </div>
                                <ul class="servicesPreviewList" style="list-style: none; padding: 0; margin: 0; font-size: 0.76rem; line-height: 1.5; color: <?= View::e(Setting::get('services_text_color') ?: '#a3a8b0') ?>;">
                                    <li style="margin-bottom: 6px; display: flex; align-items: flex-start; gap: 6px;">
                                        <span class="servicesPreviewBullet" style="color: <?= View::e(Setting::get('services_icon_color') ?: '#CBD0D6') ?>; font-size: 0.65rem; line-height: 1.5;">✦</span>
                                        <span>Orientações de conservação natural da prata de lei</span>
                                    </li>
                                    <li style="display: flex; align-items: flex-start; gap: 6px;">
                                        <span class="servicesPreviewBullet" style="color: <?= View::e(Setting::get('services_icon_color') ?: '#CBD0D6') ?>; font-size: 0.65rem; line-height: 1.5;">✦</span>
                                        <span>Polimento artesanal para renovação periódica do brilho</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Card 03 -->
                            <div class="servicesPreviewItemCard" style="border: 1px solid <?= View::e(Setting::get('services_border_color') ?: 'rgba(255,255,255,0.08)') ?>; background-color: <?= View::e(Setting::get('services_card_bg') ?: 'transparent') ?>; border-radius: 8px; padding: 18px 16px; transition: all 0.3s ease;">
                                <div class="servicesPreviewNum" style="font-family: serif; font-size: 1.4rem; font-weight: 700; margin-bottom: 6px; color: <?= View::e(Setting::get('services_icon_color') ?: '#CBD0D6') ?>;">03</div>
                                <div class="servicesPreviewItemTitle" style="font-weight: 700; font-size: 0.92rem; margin-bottom: 10px; color: <?= View::e(Setting::get('services_title_color') ?: '#ffffff') ?>;">
                                    <?= View::e(Setting::get('service_3_title', 'Ajuste de Conforto & Garantia')) ?>
                                </div>
                                <ul class="servicesPreviewList" style="list-style: none; padding: 0; margin: 0; font-size: 0.76rem; line-height: 1.5; color: <?= View::e(Setting::get('services_text_color') ?: '#a3a8b0') ?>;">
                                    <li style="margin-bottom: 6px; display: flex; align-items: flex-start; gap: 6px;">
                                        <span class="servicesPreviewBullet" style="color: <?= View::e(Setting::get('services_icon_color') ?: '#CBD0D6') ?>; font-size: 0.65rem; line-height: 1.5;">✦</span>
                                        <span>Aro anatômico maleável com caimento refinado</span>
                                    </li>
                                    <li style="display: flex; align-items: flex-start; gap: 6px;">
                                        <span class="servicesPreviewBullet" style="color: <?= View::e(Setting::get('services_icon_color') ?: '#CBD0D6') ?>; font-size: 0.65rem; line-height: 1.5;">✦</span>
                                        <span>Certificado de autenticidade dos minerais naturais</span>
                                    </li>
                                </ul>
                            </div>

                        </div>

                        <!-- CTA Button Preview -->
                        <div id="servicesPreviewBtnWrap" style="text-align: center; <?= Setting::get('services_cta_visible', '1') !== '1' ? 'display: none;' : '' ?>">
                            <span id="servicesPreviewBtn" style="display: inline-flex; align-items: center; justify-content: center; padding: 11px 26px; border-radius: 30px; font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; background: <?= View::e(Setting::get('services_btn_bg') ?: '#CBD0D6') ?>; color: <?= View::e(Setting::get('services_btn_text') ?: '#121212') ?>; border: 1px solid <?= View::e(Setting::get('services_btn_border') ?: '#CBD0D6') ?>; box-shadow: 0 4px 12px rgba(0,0,0,0.25);">
                                <?= View::e(Setting::get('services_cta_text', 'Falar com o Concierge no WhatsApp ↗')) ?>
                            </span>
                        </div>

                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- 2. TEXTOS E CONTEÚDO DOS SERVIÇOS -->
                <!-- ============================================================== -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px; margin-top: 8px;">
                    <h4 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.98rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                        <span>✍️</span> 2. Textos & Conteúdo dos Serviços
                    </h4>
                    <span class="field-hint">Altere os títulos, subtítulos e itens explicativos da seção de serviços.</span>
                </div>

                <div class="form-group">
                    <label for="services_title">Título Principal dos Serviços</label>
                    <input type="text" id="services_title" name="services_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('services_title', 'Experiência de Serviços Exclusivos')) ?>"
                           placeholder="Experiência de Serviços Exclusivos"
                           oninput="updateServicesLivePreview()">
                </div>

                <div class="form-group">
                    <label for="services_subtitle">Subtítulo Explicativo</label>
                    <input type="text" id="services_subtitle" name="services_subtitle" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('services_subtitle', 'Mais do que uma joia — um vínculo de bem-estar e sintonia. Cada bracelete Divinium é acompanhado de cuidados minuciosos e suporte sob medida.')) ?>"
                           oninput="updateServicesLivePreview()">
                </div>

                <!-- Serviço 01 -->
                <div class="form-group full-width" style="margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 15px;">
                    <label for="service_1_title" style="color: #CBD0D6; font-weight: 700;">Serviço 01 - Título</label>
                    <input type="text" id="service_1_title" name="service_1_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_1_title', 'Consultoria de Sintonia Mineral')) ?>"
                           oninput="updateServicesLivePreview()">
                </div>
                <div class="form-group">
                    <label for="service_1_item_1">Serviço 01 - Item 1</label>
                    <input type="text" id="service_1_item_1" name="service_1_item_1" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_1_item_1', 'Ajuda personalizada para descobrir a pedra que melhor conversa com seu momento')) ?>">
                </div>
                <div class="form-group">
                    <label for="service_1_item_2">Serviço 01 - Item 2</label>
                    <input type="text" id="service_1_item_2" name="service_1_item_2" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_1_item_2', 'Harmonização de presentes inesquecíveis para pessoas queridas')) ?>">
                </div>
                <div class="form-group full-width">
                    <label for="service_1_item_3">Serviço 01 - Item 3</label>
                    <input type="text" id="service_1_item_3" name="service_1_item_3" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_1_item_3', 'Atendimento direto pelo nosso Concierge no WhatsApp')) ?>">
                </div>

                <!-- Serviço 02 -->
                <div class="form-group full-width" style="margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 15px;">
                    <label for="service_2_title" style="color: #CBD0D6; font-weight: 700;">Serviço 02 - Título</label>
                    <input type="text" id="service_2_title" name="service_2_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_2_title', 'Cuidado Artesanal & Manutenção da Prata')) ?>"
                           oninput="updateServicesLivePreview()">
                </div>
                <div class="form-group">
                    <label for="service_2_item_1">Serviço 02 - Item 1</label>
                    <input type="text" id="service_2_item_1" name="service_2_item_1" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_2_item_1', 'Orientações de limpeza e conservação natural da prata de lei')) ?>">
                </div>
                <div class="form-group">
                    <label for="service_2_item_2">Serviço 02 - Item 2</label>
                    <input type="text" id="service_2_item_2" name="service_2_item_2" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_2_item_2', 'Polimento artesanal para renovação periódica do brilho original')) ?>">
                </div>
                <div class="form-group full-width">
                    <label for="service_2_item_3">Serviço 02 - Item 3</label>
                    <input type="text" id="service_2_item_3" name="service_2_item_3" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_2_item_3', 'Proteção e impermeabilização da resina mineral')) ?>">
                </div>

                <!-- Serviço 03 -->
                <div class="form-group full-width" style="margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 15px;">
                    <label for="service_3_title" style="color: #CBD0D6; font-weight: 700;">Serviço 03 - Título</label>
                    <input type="text" id="service_3_title" name="service_3_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_3_title', 'Ajuste de Conforto & Garantia')) ?>"
                           oninput="updateServicesLivePreview()">
                </div>
                <div class="form-group">
                    <label for="service_3_item_1">Serviço 03 - Item 1</label>
                    <input type="text" id="service_3_item_1" name="service_3_item_1" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_3_item_1', 'Aro anatômico maleável que se molda gentilmente ao formato do seu pulso')) ?>">
                </div>
                <div class="form-group">
                    <label for="service_3_item_2">Serviço 03 - Item 2</label>
                    <input type="text" id="service_3_item_2" name="service_3_item_2" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_3_item_2', 'Garantia contra defeitos de fabricação e desprendimento de gemas')) ?>">
                </div>
                <div class="form-group full-width">
                    <label for="service_3_item_3">Serviço 03 - Item 3</label>
                    <input type="text" id="service_3_item_3" name="service_3_item_3" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_3_item_3', 'Certificado de autenticidade dos minerais naturais')) ?>">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 8: CADA PEÇA É ÚNICA & HERANÇA -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-heritage">
            <div class="section-card-header">
                <div class="section-icon">👑</div>
                <div>
                    <h3>Cada Peça é Única & Herança Autoral</h3>
                    <p>Controle os textos, chamadas e imagem editorial da seção "Desde 2026, Cada Peça é Única".</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="heritage_tag">Tag da Seção</label>
                    <input type="text" id="heritage_tag" name="heritage_tag" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('heritage_tag', 'SOFISTICAÇÃO & NATUREZA')) ?>"
                           placeholder="SOFISTICAÇÃO & NATUREZA">
                </div>

                <div class="form-group">
                    <label for="heritage_title">Título da Seção</label>
                    <input type="text" id="heritage_title" name="heritage_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('heritage_title', 'Cada Peça é Única')) ?>"
                           placeholder="Cada Peça é Única">
                </div>

                <div class="form-group full-width">
                    <label for="heritage_p1">Primeiro Parágrafo</label>
                    <textarea id="heritage_p1" name="heritage_p1" class="form-textarea" rows="3"><?= \App\Core\View::e(Setting::get('heritage_p1', 'A natureza não se repete, e nós respeitamos esse princípio. Quando você adquire um bracelete Divinium, tem a certeza de que nenhuma outra pessoa no mundo possui um exemplar idêntico ao seu.')) ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="heritage_p2">Segundo Parágrafo</label>
                    <textarea id="heritage_p2" name="heritage_p2" class="form-textarea" rows="3"><?= \App\Core\View::e(Setting::get('heritage_p2', 'O aro duplo oferece estabilidade elegante e leveza incomparável no antebraço, transitando com naturalidade do visual casual diário a eventos de gala.')) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="heritage_cta_text">Texto do Botão de Ação</label>
                    <input type="text" id="heritage_cta_text" name="heritage_cta_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('heritage_cta_text', 'Escolher Meu Bracelete')) ?>"
                           placeholder="Escolher Meu Bracelete">
                </div>

                <div class="form-group">
                    <label for="heritage_cta_url">Destino / Link do Botão (URL ou Âncora)</label>
                    <input type="text" id="heritage_cta_url" name="heritage_cta_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('heritage_cta_url', '#collections')) ?>"
                           placeholder="#collections ou /loja">
                </div>

                <div class="form-group full-width">
                    <label for="heritage_banner_img">Imagem Editorial da Seção Peça Única</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="heritage_banner_img" name="heritage_banner_img" class="form-input"
                               value="<?= \App\Core\View::e(Setting::get('heritage_banner_img', '/assets/images/about/banner-unica.jpg')) ?>">
                        <label class="btn-admin-secondary" style="cursor: pointer; white-space: nowrap; padding: 8px 12px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 4px;">
                            <span>📁 Enviar</span>
                            <input type="file" accept="image/*" style="display: none;" onchange="uploadMediaFile(this, 'heritage_banner_img', 'editorials')">
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 9: DEPOIMENTOS & AVALIAÇÕES (PROVA SOCIAL) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-testimonials">
            <div class="section-card-header">
                <div class="section-icon">💬</div>
                <div>
                    <h3>Depoimentos & Avaliações de Clientes</h3>
                    <p>Edite os textos de aprovação, estrelas, depoimento destacado e fotos da galeria de clientes na Home.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="testimonials_title">Título da Seção de Depoimentos</label>
                    <input type="text" id="testimonials_title" name="testimonials_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('testimonials_title', 'Amado por Quem Valoriza a Beleza Atemporal')) ?>"
                           placeholder="Amado por Quem Valoriza a Beleza Atemporal">
                </div>

                <div class="form-group">
                    <label for="testimonial_stars">Avaliação (Estrelas / Símbolos)</label>
                    <input type="text" id="testimonial_stars" name="testimonial_stars" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('testimonial_stars', '★★★★★')) ?>"
                           placeholder="★★★★★">
                </div>

                <div class="form-group">
                    <label for="testimonial_author">Autor do Depoimento</label>
                    <input type="text" id="testimonial_author" name="testimonial_author" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('testimonial_author', '[ Marcela R. • São Paulo/SP • Cliente Verificada ]')) ?>"
                           placeholder="[ Marcela R. • São Paulo/SP • Cliente Verificada ]">
                </div>

                <div class="form-group full-width">
                    <label for="testimonial_quote">Citação / Depoimento Completo</label>
                    <textarea id="testimonial_quote" name="testimonial_quote" class="form-textarea" rows="3"><?= \App\Core\View::e(Setting::get('testimonial_quote', 'Quando coloquei o bracelete no pulso, senti imediatamente a presença e a qualidade do acabamento. A pedra tem uma profundidade de cor inacreditável. Todo mundo elogia e pergunta de onde é!')) ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="testimonial_backdrop_img">Imagem de Fundo do Depoimento (Foto da Modelo)</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="testimonial_backdrop_img" name="testimonial_backdrop_img" class="form-input"
                               value="<?= \App\Core\View::e(Setting::get('testimonial_backdrop_img', '/assets/images/about/model-wrist-sunglasses.jpg')) ?>">
                        <label class="btn-admin-secondary" style="cursor: pointer; white-space: nowrap; padding: 8px 12px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 4px;">
                            <span>📁 Enviar</span>
                            <input type="file" accept="image/*" style="display: none;" onchange="uploadMediaFile(this, 'testimonial_backdrop_img', 'editorials')">
                        </label>
                    </div>
                </div>

                <!-- Miniaturas da Faixa de Clientes -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 14px; margin-top: 8px;">
                    <h4 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        📸 Faixa de Miniaturas da Galeria de Clientes (5 Fotos)
                    </h4>
                    <span class="field-hint">Fotos quadradas exibidas logo abaixo da citação na Home.</span>
                </div>

                <?php 
                    $defaultThumbs = [
                        1 => '/assets/images/products/bracelete-verde.jpg',
                        2 => '/assets/images/products/bracelete-madreperola.jpg',
                        3 => '/assets/images/products/bracelete-vermelho.jpg',
                        4 => '/assets/images/products/bracelete-onix.jpg',
                        5 => '/assets/images/about/macro-texture.jpg',
                    ];
                    for ($t = 1; $t <= 5; $t++): 
                        $thumbKey = 'testimonial_thumb_' . $t;
                        $thumbVal = Setting::get($thumbKey, $defaultThumbs[$t]);
                ?>
                <div class="form-group">
                    <label for="<?= $thumbKey ?>">Miniatura 0<?= $t ?></label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="<?= $thumbKey ?>" name="<?= $thumbKey ?>" class="form-input"
                               value="<?= View::e($thumbVal) ?>">
                        <label class="btn-admin-secondary" style="cursor: pointer; white-space: nowrap; padding: 8px 10px; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 4px;">
                            <span>📁 Enviar</span>
                            <input type="file" accept="image/*" style="display: none;" onchange="uploadMediaFile(this, '<?= $thumbKey ?>', 'editorials')">
                        </label>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO: BOTÕES, ESTILOS & CHAMADAS PARA AÇÃO (PILAR 8) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-buttons">
            <div class="section-card-header">
                <div class="section-icon">🔘</div>
                <div>
                    <h3>Botões, Chamadas para Ação & Estilos de Interação</h3>
                    <p>Configure individualmente o texto, destino, estilo e visibilidade de cada botão da Home, Boutique e Concierge.</p>
                </div>
            </div>

            <div class="form-grid">
                <!-- Botões do Hero Principal -->
                <div class="form-group full-width" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 8px; margin-bottom: 14px;">
                    <h4 style="color: #CBD0D6; margin: 0; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        👑 Botões da Dobra Principal (Hero)
                    </h4>
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="hero_primary_btn_text">Botão Primário: Texto</label>
                        <?= $renderToggle('hero_primary_btn_visible') ?>
                    </div>
                    <input type="text" id="hero_primary_btn_text" name="hero_primary_btn_text" class="form-input"
                           value="<?= View::e(Setting::get('hero_primary_btn_text', 'Conhecer a Coleção')) ?>">
                </div>

                <div class="form-group">
                    <label for="hero_primary_btn_url">Botão Primário: Link de Destino</label>
                    <input type="text" id="hero_primary_btn_url" name="hero_primary_btn_url" class="form-input"
                           value="<?= View::e(Setting::get('hero_primary_btn_url', '/loja')) ?>"
                           placeholder="/loja">
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="hero_secondary_btn_text">Botão Secundário: Texto</label>
                        <?= $renderToggle('hero_secondary_btn_visible') ?>
                    </div>
                    <input type="text" id="hero_secondary_btn_text" name="hero_secondary_btn_text" class="form-input"
                           value="<?= View::e(Setting::get('hero_secondary_btn_text', 'Falar com Concierge VIP')) ?>">
                </div>

                <div class="form-group">
                    <label for="hero_secondary_btn_url">Botão Secundário: Link de Destino</label>
                    <input type="text" id="hero_secondary_btn_url" name="hero_secondary_btn_url" class="form-input"
                           value="<?= View::e(Setting::get('hero_secondary_btn_url', 'https://wa.me/5511999999999')) ?>"
                           placeholder="https://wa.me/...">
                </div>

                <!-- Botão Flutuante de WhatsApp Concierge VIP -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); border-bottom: 1px solid rgba(255,255,255,0.08); padding: 14px 0; margin: 14px 0;">
                    <div class="field-label-row">
                        <h4 style="color: #25D366; margin: 0; font-size: 0.95rem; display: flex; align-items: center; gap: 8px;">
                            <span>💬</span> Botão Flutuante de WhatsApp VIP (Fixo em Todas as Páginas)
                        </h4>
                        <?= $renderToggle('whatsapp_floating_enabled') ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="hero_whatsapp_number">Número do WhatsApp (com DDI e DDD)</label>
                    <input type="text" id="hero_whatsapp_number" name="hero_whatsapp_number" class="form-input"
                           value="<?= View::e(Setting::get('hero_whatsapp_number', '5511999999999')) ?>"
                           placeholder="Ex: 5511999999999">
                    <span class="field-hint">Apenas números. O link será gerado automaticamente.</span>
                </div>

                <div class="form-group">
                    <label for="whatsapp_floating_label">Rótulo Exibido no Botão Flutuante</label>
                    <input type="text" id="whatsapp_floating_label" name="whatsapp_floating_label" class="form-input"
                           value="<?= View::e(Setting::get('whatsapp_floating_label', 'Concierge VIP')) ?>"
                           placeholder="Concierge VIP">
                </div>

                <div class="form-group full-width">
                    <label for="whatsapp_floating_message">Mensagem Pré-preenchida ao Abrir o WhatsApp</label>
                    <input type="text" id="whatsapp_floating_message" name="whatsapp_floating_message" class="form-input"
                           value="<?= View::e(Setting::get('whatsapp_floating_message', 'Olá! Gostaria de um atendimento personalizado sobre as joias autorais Divinium.')) ?>">
                </div>

                <div class="form-group">
                    <label for="whatsapp_floating_position">Posicionamento na Tela</label>
                    <select id="whatsapp_floating_position" name="whatsapp_floating_position" class="form-input">
                        <option value="right" <?= Setting::get('whatsapp_floating_position', 'right') === 'right' ? 'selected' : '' ?>>Canto Inferior Direito (Padrão)</option>
                        <option value="left" <?= Setting::get('whatsapp_floating_position') === 'left' ? 'selected' : '' ?>>Canto Inferior Esquerdo</option>
                    </select>
                </div>

                <!-- Botões da Boutique e Estilos Globais -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 14px; margin-top: 14px;">
                    <h4 style="color: #CBD0D6; margin: 0 0 12px; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        🛍️ Botões dos Cards da Vitrine & Formas Globais
                    </h4>
                </div>

                <div class="form-group">
                    <label for="store_card_btn_text">Texto do Botão nos Cards da Loja</label>
                    <input type="text" id="store_card_btn_text" name="store_card_btn_text" class="form-input"
                           value="<?= View::e(Setting::get('store_card_btn_text', 'Ver Detalhes')) ?>"
                           placeholder="Ver Detalhes">
                </div>

                <div class="form-group">
                    <label for="store_card_btn_action">Ação Primária ao Clicar no Card</label>
                    <select id="store_card_btn_action" name="store_card_btn_action" class="form-input">
                        <option value="product_page" <?= Setting::get('store_card_btn_action', 'product_page') === 'product_page' ? 'selected' : '' ?>>Abrir Página Individual da Joia (com variações)</option>
                        <option value="direct_checkout" <?= Setting::get('store_card_btn_action') === 'direct_checkout' ? 'selected' : '' ?>>Ir Direto para o Checkout Seguro</option>
                        <option value="whatsapp" <?= Setting::get('store_card_btn_action') === 'whatsapp' ? 'selected' : '' ?>>Iniciar Compra com Concierge no WhatsApp</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="theme_btn_border_radius">Arredondamento das Bordas dos Botões</label>
                    <select id="theme_btn_border_radius" name="theme_btn_border_radius" class="form-input">
                        <option value="0px" <?= Setting::get('theme_btn_border_radius') === '0px' ? 'selected' : '' ?>>0px — Reto Minimalista (Architectural Luxe)</option>
                        <option value="4px" <?= Setting::get('theme_btn_border_radius', '4px') === '4px' ? 'selected' : '' ?>>4px — Sutilmente Arredondado (Padrão Joalheria)</option>
                        <option value="8px" <?= Setting::get('theme_btn_border_radius') === '8px' ? 'selected' : '' ?>>8px — Moderado Contemporâneo</option>
                        <option value="50px" <?= Setting::get('theme_btn_border_radius') === '50px' ? 'selected' : '' ?>>50px — Pílula Clássica Oval</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="theme_btn_text_transform">Estilo do Texto dos Botões</label>
                    <select id="theme_btn_text_transform" name="theme_btn_text_transform" class="form-input">
                        <option value="uppercase" <?= Setting::get('theme_btn_text_transform', 'uppercase') === 'uppercase' ? 'selected' : '' ?>>MAIÚSCULO COM RESPIRO (Quiet Luxury)</option>
                        <option value="none" <?= Setting::get('theme_btn_text_transform') === 'none' ? 'selected' : '' ?>>Normal conforme digitado</option>
                        <option value="capitalize" <?= Setting::get('theme_btn_text_transform') === 'capitalize' ? 'selected' : '' ?>>Primeira Letra De Cada Palavra</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO: PÁGINAS INSTITUCIONAIS, CDC & POLÍTICAS (PILAR 9) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-institutional">
            <div class="section-card-header">
                <div class="section-icon">🏛️</div>
                <div>
                    <h3>Páginas Institucionais, Políticas & Informações Comerciais</h3>
                    <p>Edite os textos legais em conformidade com o CDC e LGPD, dados empresariais e o conteúdo completo de cada página institucional.</p>
                </div>
            </div>

            <div class="form-grid">
                <!-- Informações Oficiais da Empresa -->
                <div class="form-group full-width" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 8px;">
                    <h4 style="color: #CBD0D6; margin: 0; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        📋 Dados Jurídicos & Comerciais (Exibidos no Rodapé e Faturas)
                    </h4>
                </div>

                <div class="form-group full-width">
                    <label for="footer_legal_info">Razão Social & CNPJ</label>
                    <input type="text" id="footer_legal_info" name="footer_legal_info" class="form-input"
                           value="<?= View::e(Setting::get('footer_legal_info', 'DIVINIUM JOIAS LTDA. • CNPJ: 00.000.000/0001-00 • São Paulo/SP - Brasil')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="footer_consumer_rights">Texto de Conformidade com o CDC (Decreto 7.962/2013)</label>
                    <textarea id="footer_consumer_rights" name="footer_consumer_rights" class="form-textarea" rows="2"><?= View::e(Setting::get('footer_consumer_rights', 'Conforme o Decreto nº 7.962/2013 e Lei 8.078/1990 (Código de Defesa do Consumidor). Direito de arrependimento em até 7 dias corridos após o recebimento.')) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="footer_email">E-mail Oficial de Atendimento</label>
                    <input type="email" id="footer_email" name="footer_email" class="form-input"
                           value="<?= View::e(Setting::get('footer_email', 'atendimento@divinium.com.br')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_operating_hours">Horário de Funcionamento</label>
                    <input type="text" id="footer_operating_hours" name="footer_operating_hours" class="form-input"
                           value="<?= View::e(Setting::get('footer_operating_hours', 'Segunda a Sexta: 09h às 18h')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="footer_instagram">Link Oficial do Instagram</label>
                    <input type="text" id="footer_instagram" name="footer_instagram" class="form-input"
                           value="<?= View::e(Setting::get('footer_instagram', 'https://instagram.com/divinium')) ?>">
                </div>

                <!-- Selos de Meios de Pagamento -->
                <div class="form-group">
                    <label for="footer_pay_badge1">Selo de Pagamento 1</label>
                    <input type="text" id="footer_pay_badge1" name="footer_pay_badge1" class="form-input"
                           value="<?= View::e(Setting::get('footer_pay_badge1', 'PIX (Aprovação Imediata)')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_pay_badge2">Selo de Pagamento 2</label>
                    <input type="text" id="footer_pay_badge2" name="footer_pay_badge2" class="form-input"
                           value="<?= View::e(Setting::get('footer_pay_badge2', 'Cartão de Crédito em até 10x')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_pay_badge3">Selo de Pagamento 3</label>
                    <input type="text" id="footer_pay_badge3" name="footer_pay_badge3" class="form-input"
                           value="<?= View::e(Setting::get('footer_pay_badge3', 'Boleto Bancário')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_pay_badge4">Selo de Pagamento 4</label>
                    <input type="text" id="footer_pay_badge4" name="footer_pay_badge4" class="form-input"
                           value="<?= View::e(Setting::get('footer_pay_badge4', 'Gateway Homologado ASAAS')) ?>">
                </div>

                <!-- ============================================================== -->
                <!-- BLOCO: CHECKOUT DIRETO, PAGAMENTO PIX & SACOLA DE COMPRAS -->
                <!-- ============================================================== -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 14px; margin-top: 14px;">
                    <div class="field-label-row" style="background: rgba(212,186,136,0.05); padding: 12px 16px; border-radius: 8px; border: 1px solid rgba(212,186,136,0.2);">
                        <div>
                            <h4 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                                <span>⚡</span> Checkout Direto, Pagamento Pix & Sacola de Compras (/checkout)
                            </h4>
                            <span class="field-hint" style="margin: 0;">Ative ou desative toda a sacola de compras, gaveta lateral, checkout direto e pagamentos via Pix.</span>
                        </div>
                        <?= $renderToggle('checkout_direct_cart_enabled') ?>
                    </div>
                </div>

                <div id="checkoutConfigFields" class="sub-section-fields-container <?= Setting::get('checkout_direct_cart_enabled', '1') !== '1' ? 'is-dimmed' : '' ?>" style="transition: all 0.3s ease; <?= Setting::get('checkout_direct_cart_enabled', '1') !== '1' ? 'opacity: 0.35; pointer-events: none;' : '' ?>">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="payment_pix_key">Chave Pix Oficial (E-mail, CNPJ, Telefone ou Aleatória)</label>
                            <input type="text" id="payment_pix_key" name="payment_pix_key" class="form-input"
                                   value="<?= View::e(Setting::get('payment_pix_key', 'contato@divinium.com.br')) ?>"
                                   placeholder="ex: contato@divinium.com.br ou CNPJ">
                        </div>

                        <div class="form-group">
                            <label for="payment_pix_name">Nome do Titular / Beneficiário do Pix</label>
                            <input type="text" id="payment_pix_name" name="payment_pix_name" class="form-input"
                                   value="<?= View::e(Setting::get('payment_pix_name', 'DIVINIUM JOIAS AUTORAIS')) ?>"
                                   placeholder="DIVINIUM JOIAS AUTORAIS">
                        </div>

                        <div class="form-group">
                            <label for="cart_drawer_title">Título da Gaveta da Sacola</label>
                            <input type="text" id="cart_drawer_title" name="cart_drawer_title" class="form-input"
                                   value="<?= View::e(Setting::get('cart_drawer_title', 'Sua Sacola de Escolhas')) ?>"
                                   placeholder="Sua Sacola de Escolhas">
                        </div>

                        <div class="form-group">
                            <label for="cart_checkout_btn">Texto do Botão de Finalizar Compra</label>
                            <input type="text" id="cart_checkout_btn" name="cart_checkout_btn" class="form-input"
                                   value="<?= View::e(Setting::get('cart_checkout_btn', 'Finalizar Compra')) ?>"
                                   placeholder="Finalizar Compra">
                        </div>

                        <div class="form-group">
                            <label for="cart_drawer_empty">Mensagem de Sacola Vazia</label>
                            <input type="text" id="cart_drawer_empty" name="cart_drawer_empty" class="form-input"
                                   value="<?= View::e(Setting::get('cart_drawer_empty', 'Sua sacola está vazia no momento.')) ?>">
                        </div>

                        <div class="form-group">
                            <label for="cart_drawer_discover">Texto do Botão 'Descobrir Coleção' (Sacola Vazia)</label>
                            <input type="text" id="cart_drawer_discover" name="cart_drawer_discover" class="form-input"
                                   value="<?= View::e(Setting::get('cart_drawer_discover', 'Descobrir Coleção')) ?>">
                        </div>

                        <div class="form-group full-width">
                            <label for="cart_security_badge">Texto de Segurança do Rodapé da Sacola</label>
                            <input type="text" id="cart_security_badge" name="cart_security_badge" class="form-input"
                                   value="<?= View::e(Setting::get('cart_security_badge', '🔒 Pagamento 100% Seguro via ASAAS • Pix com QR Code Instantâneo')) ?>">
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- BLOCO: CONTEÚDO DAS PÁGINAS INSTITUCIONAIS OFICIAIS -->
                <!-- ============================================================== -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 14px; margin-top: 14px;">
                    <div class="field-label-row" style="background: rgba(212,186,136,0.05); padding: 12px 16px; border-radius: 8px; border: 1px solid rgba(212,186,136,0.2);">
                        <div>
                            <h4 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                                <span>📄</span> Conteúdo das Páginas Institucionais Oficiais
                            </h4>
                            <span class="field-hint" style="margin: 0;">Ative ou desative o conteúdo editorial e páginas oficiais (Trocas, Garantia, LGPD e Termos de Uso).</span>
                        </div>
                        <?= $renderToggle('institutional_pages_content_enabled') ?>
                    </div>
                </div>

                <div id="institutionalPagesFields" class="sub-section-fields-container <?= Setting::get('institutional_pages_content_enabled', '1') !== '1' ? 'is-dimmed' : '' ?>" style="transition: all 0.3s ease; <?= Setting::get('institutional_pages_content_enabled', '1') !== '1' ? 'opacity: 0.35; pointer-events: none;' : '' ?>">
                    <!-- Página 1: Trocas e Devoluções -->
                    <div class="form-group full-width" style="background: rgba(255,255,255,0.02); padding: 16px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); margin-bottom: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <strong style="color: #ffffff;">1. Trocas e Devoluções (CDC 7 Dias)</strong>
                            <a href="/trocas-e-devolucoes" target="_blank" class="btn-admin-secondary" style="font-size: 0.74rem; padding: 4px 10px;">Ver Página ↗</a>
                        </div>
                        <input type="text" name="page_trocas_title" class="form-input" style="margin-bottom: 8px;"
                               value="<?= View::e(Setting::get('page_trocas_title', 'Política de Trocas e Devoluções')) ?>" placeholder="Título da Página">
                        <textarea name="page_trocas_content" class="form-textarea" rows="4" placeholder="Conteúdo da página de trocas e devoluções..."><?= View::e(Setting::get('page_trocas_content', 'Na DIVINIUM, respeitamos integralmente o Código de Defesa do Consumidor (Lei nº 8.078/1990). Você possui até 7 (sete) dias corridos a partir da data de entrega para solicitar a troca ou devolução da sua joia sem qualquer custo adicional.')) ?></textarea>
                    </div>

                    <!-- Página 2: Garantia e Cuidados -->
                    <div class="form-group full-width" style="background: rgba(255,255,255,0.02); padding: 16px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); margin-bottom: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <strong style="color: #ffffff;">2. Garantia e Cuidados com a Joia</strong>
                            <a href="/garantia-e-cuidados" target="_blank" class="btn-admin-secondary" style="font-size: 0.74rem; padding: 4px 10px;">Ver Página ↗</a>
                        </div>
                        <input type="text" name="page_garantia_title" class="form-input" style="margin-bottom: 8px;"
                               value="<?= View::e(Setting::get('page_garantia_title', 'Certificado de Garantia & Cuidados')) ?>" placeholder="Título da Página">
                        <textarea name="page_garantia_content" class="form-textarea" rows="4" placeholder="Conteúdo sobre garantia e cuidados..."><?= View::e(Setting::get('page_garantia_content', 'Todas as joias DIVINIUM acompanham Certificado de Autenticidade e Garantia Vitalícia quanto ao teor da Prata de Lei 925 e autenticidade das pedras e cristais nobres.')) ?></textarea>
                    </div>

                    <!-- Página 3: LGPD & Privacidade -->
                    <div class="form-group full-width" style="background: rgba(255,255,255,0.02); padding: 16px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); margin-bottom: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <strong style="color: #ffffff;">3. Política de Privacidade (LGPD)</strong>
                            <a href="/politica-de-privacidade" target="_blank" class="btn-admin-secondary" style="font-size: 0.74rem; padding: 4px 10px;">Ver Página ↗</a>
                        </div>
                        <input type="text" name="page_privacidade_title" class="form-input" style="margin-bottom: 8px;"
                               value="<?= View::e(Setting::get('page_privacidade_title', 'Política de Privacidade e Proteção de Dados')) ?>" placeholder="Título da Página">
                        <textarea name="page_privacidade_content" class="form-textarea" rows="4" placeholder="Conteúdo da política de privacidade..."><?= View::e(Setting::get('page_privacidade_content', 'A sua privacidade é sagrada para a DIVINIUM. Em conformidade com a LGPD (Lei nº 13.709/2018), coletamos apenas os dados necessários para o processamento seguro de seus pedidos e comunicações VIP autorizadas.')) ?></textarea>
                    </div>

                    <!-- Página 4: Termos de Uso -->
                    <div class="form-group full-width" style="background: rgba(255,255,255,0.02); padding: 16px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <strong style="color: #ffffff;">4. Termos de Uso do Site</strong>
                            <a href="/termos-de-uso" target="_blank" class="btn-admin-secondary" style="font-size: 0.74rem; padding: 4px 10px;">Ver Página ↗</a>
                        </div>
                        <input type="text" name="page_termos_title" class="form-input" style="margin-bottom: 8px;"
                               value="<?= View::e(Setting::get('page_termos_title', 'Termos de Uso')) ?>" placeholder="Título da Página">
                        <textarea name="page_termos_content" class="form-textarea" rows="4" placeholder="Conteúdo dos termos de uso..."><?= View::e(Setting::get('page_termos_content', 'Ao navegar e efetuar compras no ambiente digital DIVINIUM, você concorda com nossos termos operacionais de compra, prazos de confecção e modalidades de entrega.')) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO: SEO, METADADOS & SCRIPTS DE RASTREAMENTO (PILAR 10) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-seo">
            <div class="section-card-header">
                <div class="section-icon">🌐</div>
                <div>
                    <h3>Metadados de SEO, Títulos de Páginas & Rastreamento</h3>
                    <p>Otimize sua joalheria para o Google, configure tags Open Graph para WhatsApp e insira códigos do Pixel e Analytics.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="seo_site_title_format">Padrão do Título da Página (&lt;title&gt;)</label>
                    <input type="text" id="seo_site_title_format" name="seo_site_title_format" class="form-input"
                           value="<?= View::e(Setting::get('seo_site_title_format', '{title} | Divinium')) ?>"
                           placeholder="{title} | Divinium">
                    <span class="field-hint">Use <code>{title}</code> para o título da página atual e <code>{brand}</code> para o nome da marca.</span>
                </div>

                <div class="form-group full-width">
                    <label for="seo_meta_description">Meta Descrição Global (Snippet do Google)</label>
                    <textarea id="seo_meta_description" name="seo_meta_description" class="form-textarea" rows="3"><?= View::e(Setting::get('seo_meta_description', 'Divinium — Joias autorais criadas com metais nobres e pedras preciosas. Design atemporal, acabamento artesanal e a essência do quiet luxury.')) ?></textarea>
                    <span class="field-hint">Recomendado entre 140 e 160 caracteres para visualização perfeita nos resultados de busca do Google.</span>
                </div>

                <div class="form-group full-width">
                    <label for="seo_meta_keywords">Palavras-chave (Keywords)</label>
                    <input type="text" id="seo_meta_keywords" name="seo_meta_keywords" class="form-input"
                           value="<?= View::e(Setting::get('seo_meta_keywords', 'joias autorais, prata 925, pedras naturais, quiet luxury, alta joalheria brasileira, orgonite nobre')) ?>"
                           placeholder="joias autorais, prata 925, ...">
                    <span class="field-hint">Termos separados por vírgula que descrevem o catálogo da marca.</span>
                </div>

                <div class="form-group full-width">
                    <label for="seo_og_image">Imagem de Compartilhamento Open Graph (WhatsApp / Redes Sociais)</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="seo_og_image" name="seo_og_image" class="form-input"
                               value="<?= View::e(Setting::get('seo_og_image', '/assets/images/hero/hero-main.webp')) ?>"
                               placeholder="/assets/images/hero/hero-main.webp">
                        <label class="btn-admin-gold" style="padding: 0 14px; cursor: pointer; display: flex; align-items: center; white-space: nowrap; font-size: 0.78rem;">
                            <span>📁 Upload</span>
                            <input type="file" accept="image/*" style="display: none;" onchange="uploadMediaFile(this, 'seo_og_image', 'banners')">
                        </label>
                    </div>
                    <span class="field-hint">A imagem que aparece automaticamente ao enviar o link do seu site pelo WhatsApp (tamanho ideal: 1200x630 px).</span>
                </div>

                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 14px; margin-top: 8px;">
                    <h4 style="color: #CBD0D6; margin: 0 0 4px; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        📊 Scripts de Rastreamento & Marketing Digital
                    </h4>
                    <span class="field-hint">Códigos fornecidos pelo Google, Meta, TikTok ou ferramentas de atendimento para mensuração de tráfego.</span>
                </div>

                <div class="form-group full-width">
                    <label for="custom_head_scripts">Códigos no &lt;head&gt; (Google Analytics, Meta Pixel, Tag Manager)</label>
                    <textarea id="custom_head_scripts" name="custom_head_scripts" class="form-textarea" rows="4" style="font-family: monospace; font-size: 0.8rem;" placeholder="<!-- Cole aqui seus scripts do <head> -->"><?= View::e(Setting::get('custom_head_scripts', '')) ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="custom_body_scripts">Códigos no final do &lt;body&gt; (Chatbots, Hotjar, Tags extras)</label>
                    <textarea id="custom_body_scripts" name="custom_body_scripts" class="form-textarea" rows="4" style="font-family: monospace; font-size: 0.8rem;" placeholder="<!-- Cole aqui scripts do <body> -->"><?= View::e(Setting::get('custom_body_scripts', '')) ?></textarea>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 11: CANAIS DE CONTATO, RODAPÉ & LGPD -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-footer">
            <div class="section-card-header">
                <div class="section-icon">📞</div>
                <div>
                    <h3>Canais de Contato, Rodapé & Informações Jurídicas</h3>
                    <p>Edite os dados de atendimento, links institucionais, métodos de pagamento e dados legais.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="footer_tagline">Slogan Institucional da Marca no Rodapé</label>
                    <input type="text" id="footer_tagline" name="footer_tagline" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_tagline', 'Joalheria fina concebida para quem aprecia a nobreza atemporal, o luxo silencioso e o design autoral inconfundível.')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_col1_title">Título da Coluna 1 do Rodapé</label>
                    <input type="text" id="footer_col1_title" name="footer_col1_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_col1_title', 'Coleções')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_col2_title">Título da Coluna 2 do Rodapé</label>
                    <input type="text" id="footer_col2_title" name="footer_col2_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_col2_title', 'Atendimento & Concierge')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_col3_title">Título da Coluna 3 do Rodapé</label>
                    <input type="text" id="footer_col3_title" name="footer_col3_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_col3_title', 'Segurança & Privacidade')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_editions_text">Texto: Edições Numeradas</label>
                    <input type="text" id="footer_editions_text" name="footer_editions_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_editions_text', 'Edições Numeradas')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_whatsapp_label">Texto do Link WhatsApp VIP</label>
                    <input type="text" id="footer_whatsapp_label" name="footer_whatsapp_label" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_whatsapp_label', 'Atendimento via WhatsApp')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_email">E-mail de Atendimento Oficial</label>
                    <input type="email" id="footer_email" name="footer_email" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_email', 'atendimento@divinium.com.br')) ?>"
                           placeholder="atendimento@divinium.com.br">
                </div>

                <div class="form-group">
                    <label for="footer_instagram">Instagram da Marca</label>
                    <input type="text" id="footer_instagram" name="footer_instagram" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_instagram', 'https://instagram.com/diviniumjoias')) ?>"
                           placeholder="https://instagram.com/diviniumjoias">
                </div>

                <div class="form-group">
                    <label for="footer_operating_hours">Horário de Funcionamento do Concierge</label>
                    <input type="text" id="footer_operating_hours" name="footer_operating_hours" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_operating_hours', 'Segunda a Sexta: 09h às 18h')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_returns_text">Link: Trocas e Devoluções</label>
                    <input type="text" id="footer_returns_text" name="footer_returns_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_returns_text', 'Trocas e Devoluções (CDC 7 Dias)')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_warranty_text">Link: Certificado & Cuidados</label>
                    <input type="text" id="footer_warranty_text" name="footer_warranty_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_warranty_text', 'Certificado & Cuidados')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_size_guide_text">Link: Guia de Medidas</label>
                    <input type="text" id="footer_size_guide_text" name="footer_size_guide_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_size_guide_text', 'Guia de Medidas de Anéis')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_privacy_text">Link: Política de Privacidade</label>
                    <input type="text" id="footer_privacy_text" name="footer_privacy_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_privacy_text', 'Política de Privacidade (LGPD)')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_terms_text">Link: Termos de Uso</label>
                    <input type="text" id="footer_terms_text" name="footer_terms_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_terms_text', 'Termos de Uso')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_dpo_text">Link: Encarregado de Dados (DPO)</label>
                    <input type="text" id="footer_dpo_text" name="footer_dpo_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_dpo_text', 'Encarregado de Dados (DPO)')) ?>">
                </div>

                <!-- Selos de Meios de Pagamento -->
                <div class="form-group">
                    <label for="footer_pay_badge1">Selo Pagamento 1</label>
                    <input type="text" id="footer_pay_badge1" name="footer_pay_badge1" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_pay_badge1', 'PIX (Aprovação Imediata)')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_pay_badge2">Selo Pagamento 2</label>
                    <input type="text" id="footer_pay_badge2" name="footer_pay_badge2" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_pay_badge2', 'Cartão de Crédito em até 10x')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_pay_badge3">Selo Pagamento 3</label>
                    <input type="text" id="footer_pay_badge3" name="footer_pay_badge3" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_pay_badge3', 'Boleto Bancário')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_pay_badge4">Selo Pagamento 4</label>
                    <input type="text" id="footer_pay_badge4" name="footer_pay_badge4" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_pay_badge4', 'Gateway Homologado ASAAS')) ?>">
                </div>

                <!-- Jurídico e Copyright -->
                <div class="form-group full-width">
                    <label for="footer_legal_info">Razão Social & Dados Jurídicos (CDC 7.962/2013)</label>
                    <input type="text" id="footer_legal_info" name="footer_legal_info" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_legal_info', 'DIVINIUM JOIAS LTDA. • CNPJ: 00.000.000/0001-00 • São Paulo/SP - Brasil')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="footer_consumer_rights">Texto de Conformidade Legal e CDC</label>
                    <textarea id="footer_consumer_rights" name="footer_consumer_rights" class="form-textarea" rows="2"><?= \App\Core\View::e(Setting::get('footer_consumer_rights', 'Conforme o Decreto nº 7.962/2013 e Lei 8.078/1990 (Código de Defesa do Consumidor). Direito de arrependimento em até 7 dias corridos após o recebimento.')) ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="footer_copyright_brand">Texto de Direitos Autorais / Copyright</label>
                    <input type="text" id="footer_copyright_brand" name="footer_copyright_brand" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_copyright_brand', 'Divinium. Todos os direitos reservados.')) ?>">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO: TEXTOS & ELEMENTOS DA BOUTIQUE (/loja) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-store">
            <div class="section-card-header">
                <div class="section-icon">🛍️</div>
                <div>
                    <h3>Textos & Chamadas da Boutique (/loja)</h3>
                    <p>Configure os títulos e elementos da vitrine oficial. Você também pode acessar o módulo completo em <a href="/admin/boutique" style="color: #CBD0D6; text-decoration: underline;">Personalizar Boutique</a>.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width" style="background: rgba(212, 186, 136, 0.08); border: 1px solid rgba(212, 186, 136, 0.25); border-radius: 8px; padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <strong style="color: #f7f4ee; display: block; margin-bottom: 2px;">💎 Central Executiva da Boutique & Catálogo</strong>
                        <span style="color: #a8a096; font-size: 0.82rem;">Gerencie selos de garantia, seção editorial, botões de múltiplos canais e produtos com links ilimitados.</span>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <a href="/admin/boutique" class="btn-admin-gold" style="font-size: 0.8rem; padding: 8px 14px;">Personalizar Boutique Completa</a>
                        <a href="/admin/produtos" class="btn-admin-secondary" style="font-size: 0.8rem; padding: 8px 14px;">Gerenciar Produtos & Links</a>
                    </div>
                </div>

                <div class="form-group full-width" style="background: rgba(212, 186, 136, 0.05); padding: 12px 16px; border-radius: 8px; border: 1px solid rgba(212, 186, 136, 0.15);">
                    <div class="field-label-row">
                        <label style="color: #ffffff; font-weight: 600; font-size: 0.9rem;">Exibir Hero no topo da Boutique (/loja)</label>
                        <?= $renderToggle('store_hero_visible') ?>
                    </div>
                </div>

                <div class="form-group full-width <?= Setting::get('store_hero_badge_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_hero_badge_lp">Selo Superior da Boutique (Badge)</label>
                        <?= $renderToggle('store_hero_badge_visible') ?>
                    </div>
                    <input type="text" id="store_hero_badge_lp" name="store_hero_badge" class="form-input" 
                           value="<?= View::e(Setting::get('store_hero_badge', 'VITRINE OFICIAL DIVINIUM • CANAIS HOMOLOGADOS')) ?>">
                </div>

                <div class="form-group <?= Setting::get('store_hero_title_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_hero_title_lp">Título da Loja (Primeira Parte)</label>
                        <?= $renderToggle('store_hero_title_visible') ?>
                    </div>
                    <input type="text" id="store_hero_title_lp" name="store_hero_title" class="form-input" 
                           value="<?= View::e(Setting::get('store_hero_title', 'Joias Autorais &')) ?>">
                </div>

                <div class="form-group <?= Setting::get('store_hero_title_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="store_hero_highlight_lp">Destaque em Ouro</label>
                    <input type="text" id="store_hero_highlight_lp" name="store_hero_highlight" class="form-input" 
                           value="<?= View::e(Setting::get('store_hero_highlight', 'Sintonia Mineral')) ?>">
                </div>

                <div class="form-group full-width <?= Setting::get('store_hero_subtitle_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_hero_subtitle_lp">Subtítulo / Descrição Conceitual da Boutique</label>
                        <?= $renderToggle('store_hero_subtitle_visible') ?>
                    </div>
                    <textarea id="store_hero_subtitle_lp" name="store_hero_subtitle" class="form-textarea" rows="2"><?= View::e(Setting::get('store_hero_subtitle', 'Cada joia Divinium une a precisão da prata de lei 925 à energia de gemas minerais raras e conchas naturais. Escolha sua peça e finalize sua compra com toda a segurança, cupons e rastreamento nos nossos canais oficiais homologados.')) ?></textarea>
                </div>

                <div class="form-group <?= Setting::get('store_filter_all_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_filter_all_lp">Rótulo Filtro 'Todas'</label>
                        <?= $renderToggle('store_filter_all_visible') ?>
                    </div>
                    <input type="text" id="store_filter_all_lp" name="store_filter_all" class="form-input" 
                           value="<?= View::e(Setting::get('store_filter_all', 'Todas as Peças')) ?>">
                </div>

                <div class="form-group <?= Setting::get('store_card_badge_metal_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_card_badge_metal_lp">Selo de Metal nos Cards</label>
                        <?= $renderToggle('store_card_badge_metal_visible') ?>
                    </div>
                    <input type="text" id="store_card_badge_metal_lp" name="store_card_badge_metal" class="form-input" 
                           value="<?= View::e(Setting::get('store_card_badge_metal', 'Prata 925')) ?>">
                </div>

                <div class="form-group <?= Setting::get('store_pix_discount_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_pix_discount_label_lp">Rótulo Pix nos Cards</label>
                        <?= $renderToggle('store_pix_discount_visible') ?>
                    </div>
                    <input type="text" id="store_pix_discount_label_lp" name="store_pix_discount_label" class="form-input" 
                           value="<?= View::e(Setting::get('store_pix_discount_label', 'no Pix (-5%)')) ?>">
                </div>

                <div class="form-group <?= Setting::get('store_multi_channel_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_multi_channel_label_lp">Rótulo 'Disponível também em:'</label>
                        <?= $renderToggle('store_multi_channel_visible') ?>
                    </div>
                    <input type="text" id="store_multi_channel_label_lp" name="store_multi_channel_label" class="form-input" 
                           value="<?= View::e(Setting::get('store_multi_channel_label', 'Disponível também em:')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="store_whatsapp_url_lp">Link do Atendimento WhatsApp na Boutique</label>
                    <input type="text" id="store_whatsapp_url_lp" name="store_whatsapp_url" class="form-input" 
                           value="<?= View::e(Setting::get('store_whatsapp_url', 'https://wa.me/5511999999999?text=Ol%C3%A1,%20estou%20na%20loja%20da%20Divinium%20e%20gostaria%20de%20tirar%20d%C3%BAvidas')) ?>">
                </div>
            </div>
        </div>

        <!-- BARRA FIXA DE SALVAMENTO NO RODAPÉ -->
        <div class="editor-save-dock">
            <div class="save-dock-inner">
                <span class="save-dock-status" id="saveDockStatus">✦ Altere qualquer campo acima e clique em Salvar para atualizar o site ao vivo.</span>
                <button type="submit" class="btn-admin-gold" id="saveEditorSubmitBtn">
                    <span>Salvar Alterações da Landing Page</span>
                </button>
            </div>
        </div>

    </form>
</div>

<!-- SCRIPTS DO EDITOR DA LANDING PAGE -->
<script>
function handleToggleSwitch(chk) {
    const parentLabel = chk.closest('.element-toggle-switch');
    const textSpan = parentLabel ? parentLabel.querySelector('.toggle-text') : null;
    const formGroup = chk.closest('.form-group');

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

    if (chk.name === 'checkout_direct_cart_enabled') {
        const container = document.getElementById('checkoutConfigFields');
        if (container) {
            if (chk.checked) {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
                container.classList.remove('is-dimmed');
            } else {
                container.style.opacity = '0.35';
                container.style.pointerEvents = 'none';
                container.classList.add('is-dimmed');
            }
        }
    }

    if (chk.name === 'institutional_pages_content_enabled') {
        const container = document.getElementById('institutionalPagesFields');
        if (container) {
            if (chk.checked) {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
                container.classList.remove('is-dimmed');
            } else {
                container.style.opacity = '0.35';
                container.style.pointerEvents = 'none';
                container.classList.add('is-dimmed');
            }
        }
    }

    if (chk.name && chk.name.startsWith('about_')) {
        if (typeof updateAboutLivePreview === 'function') {
            updateAboutLivePreview();
        }
        if (chk.name === 'about_cta_visible') {
            const urlGroup = document.getElementById('about_cta_url')?.closest('.form-group');
            if (urlGroup) {
                if (chk.checked) urlGroup.classList.remove('is-dimmed');
                else urlGroup.classList.add('is-dimmed');
            }
        }
    }
}

function switchSection(sectionId, btn) {
    document.querySelectorAll('.editor-nav-tabs .tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.editor-section-card').forEach(card => card.classList.remove('active'));

    btn.classList.add('active');
    const target = document.getElementById(sectionId);
    if (target) {
        target.classList.add('active');
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

async function handleEditorSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('saveEditorSubmitBtn');
    const status = document.getElementById('saveDockStatus');
    
    btn.disabled = true;
    btn.innerHTML = '<span>Salvando no banco de dados...</span>';
    status.innerText = 'Gravando configurações e atualizando o site...';
    status.style.color = '#CBD0D6';

    // Sincronizar dados do carrossel antes de coletar o FormData
    if (typeof syncCarouselSlidesToForm === 'function') {
        syncCarouselSlidesToForm();
    }

    const form = document.getElementById('landingPageForm');
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
    if (csrfToken && !formData.has('_csrf_token')) {
        formData.append('_csrf_token', csrfToken);
    }

    try {
        const response = await fetch('/admin/landing-page/salvar', {
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
            throw new Error('O servidor respondeu com um formato inesperado. Se você passou muito tempo inativo, recarregue a página.');
        }

        if (data.success) {
            status.innerText = '✓ Sucesso! Todos os textos e conteúdos foram atualizados ao vivo no site.';
            status.style.color = '#FFFFFF';
            btn.innerHTML = '<span>✓ Salvo com Sucesso!</span>';
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<span>Salvar Alterações da Landing Page</span>';
                status.innerText = '✦ Altere qualquer campo acima e clique em Salvar para atualizar o site ao vivo.';
                status.style.color = '#a8a096';
            }, 2500);
        } else {
            const errorMsg = data.error || 'Erro ao salvar configurações.';
            status.innerText = '✕ ' + errorMsg;
            status.style.color = '#f87171';
            if (data.auth_required) {
                alert('Sua sessão de administrador expirou. Por favor, faça login novamente para salvar as alterações.');
            } else {
                alert(errorMsg);
            }
            btn.disabled = false;
            btn.innerHTML = '<span>Salvar Alterações da Landing Page</span>';
        }
    } catch (err) {
        status.innerText = '✕ ' + err.message;
        status.style.color = '#f87171';
        alert('Erro ao processar a solicitação: ' + err.message);
        btn.disabled = false;
        btn.innerHTML = '<span>Salvar Alterações da Landing Page</span>';
    }
}

async function uploadHeroEditorBg(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    const formData = new FormData();
    formData.append('bg_file', file);
    formData.append('target_key', 'hero_bg_image');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
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
        const raw = await res.text();
        let data;
        try {
            data = JSON.parse(raw);
        } catch (jsonErr) {
            console.error('Resposta não-JSON do upload:', raw);
            alert('Não foi possível processar o upload da imagem. Recarregue a página.');
            return;
        }

        if (data.auth_required) {
            alert('Sua sessão expirou. Por favor, faça login novamente para enviar imagens.');
            return;
        }

        if (data.success && data.url) {
            document.getElementById('hero_bg_image').value = data.url;
            alert(data.message || 'Imagem de fundo do Hero enviada com sucesso!');
        } else {
            alert(data.error || 'Erro ao enviar imagem.');
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
    }
}

async function uploadEditorialImage(input, targetId) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    const formData = new FormData();
    formData.append('bg_file', file);
    formData.append('target_key', targetId);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
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
        const raw = await res.text();
        let data;
        try {
            data = JSON.parse(raw);
        } catch (jsonErr) {
            console.error('Resposta não-JSON do upload:', raw);
            alert('Não foi possível processar o upload da imagem. Recarregue a página.');
            return;
        }

        if (data.auth_required) {
            alert('Sua sessão expirou. Por favor, faça login novamente para enviar imagens.');
            return;
        }

        if (data.success && data.url) {
            const targetEl = document.getElementById(targetId);
            if (targetEl) targetEl.value = data.url;
            alert(data.message || 'Imagem enviada com sucesso! Clique em Salvar para publicar.');
        } else {
            alert(data.error || 'Erro ao enviar imagem.');
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
    }
}

// ==============================================================================
// GESTÃO DE IDENTIDADE VISUAL & UPLOAD GENÉRICO DE MÍDIA
// ==============================================================================
function toggleLogoFields() {
    const isImage = document.querySelector('input[name="site_logo_type"]:checked')?.value === 'image';
    const imgGroup = document.getElementById('logoImageGroup');
    const textGroup1 = document.getElementById('logoTextGroup1');
    const textGroup2 = document.getElementById('logoTextGroup2');
    if (imgGroup) imgGroup.style.display = isImage ? 'block' : 'none';
    if (textGroup1) textGroup1.style.opacity = isImage ? '0.7' : '1';
    if (textGroup2) textGroup2.style.opacity = isImage ? '0.7' : '1';
}

async function uploadMediaFile(input, targetInputId, mediaType, previewImgId) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    const formData = new FormData();
    formData.append('media_file', file);
    formData.append('media_type', mediaType || 'general');
    formData.append('target_setting_key', targetInputId || '');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
    if (csrfToken) formData.append('_csrf_token', csrfToken);

    try {
        const res = await fetch('/admin/media/upload', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const raw = await res.text();
        let data;
        try {
            data = JSON.parse(raw);
        } catch (e) {
            alert('Resposta inesperada do servidor ao enviar mídia. Recarregue a página.');
            return;
        }

        if (data.success && data.url) {
            const inputEl = document.getElementById(targetInputId);
            if (inputEl) inputEl.value = data.url;
            if (previewImgId) {
                const imgEl = document.getElementById(previewImgId);
                if (imgEl) imgEl.src = data.url;
            }
            alert('Arquivo enviado e aplicado com sucesso! Clique em Salvar para publicar as alterações.');
        } else {
            alert(data.error || 'Erro no upload do arquivo.');
        }
    } catch (err) {
        alert('Erro ao enviar arquivo: ' + err.message);
    }
}

// ==============================================================================
// REORDENAÇÃO DINÂMICA DAS SEÇÕES DA HOME
// ==============================================================================
function moveSectionOrder(secKey, direction) {
    const container = document.getElementById('sectionsOrderContainer');
    if (!container) return;
    const rows = Array.from(container.querySelectorAll('.section-order-row'));
    const index = rows.findIndex(r => r.getAttribute('data-section-key') === secKey);
    if (index === -1) return;

    const newIndex = index + direction;
    if (newIndex < 0 || newIndex >= rows.length) return;

    if (direction < 0) {
        container.insertBefore(rows[index], rows[newIndex]);
    } else {
        container.insertBefore(rows[index], rows[newIndex].nextSibling);
    }

    rebuildSectionsOrder();
}

function rebuildSectionsOrder() {
    const container = document.getElementById('sectionsOrderContainer');
    if (!container) return;
    const rows = Array.from(container.querySelectorAll('.section-order-row'));
    const keys = [];

    rows.forEach((row, idx) => {
        const key = row.getAttribute('data-section-key');
        if (key) keys.push(key);
        const badge = row.querySelector('.order-badge');
        if (badge) badge.textContent = (idx + 1);
    });

    const hiddenInput = document.getElementById('home_sections_order');
    if (hiddenInput) {
        hiddenInput.value = keys.join(',');
    }
}

function resetSectionsOrder() {
    const defaultOrder = ['hero', 'about', 'collections', 'services', 'heritage', 'testimonials'];
    const container = document.getElementById('sectionsOrderContainer');
    if (!container) return;
    const rowsMap = {};
    container.querySelectorAll('.section-order-row').forEach(r => {
        rowsMap[r.getAttribute('data-section-key')] = r;
    });

    defaultOrder.forEach(k => {
        if (rowsMap[k]) container.appendChild(rowsMap[k]);
    });

    rebuildSectionsOrder();
}

// ==============================================================================
// GERENCIADOR VISUAL DE SLIDES DO CARROSSEL DA HOME
// ==============================================================================
function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

let currentCarouselSlides = [];

function initCarouselSlidesManager() {
    const jsonInput = document.getElementById('heroCarouselSlidesJson');
    let loaded = [];
    if (jsonInput && jsonInput.value) {
        try {
            const parsed = JSON.parse(jsonInput.value);
            if (Array.isArray(parsed)) loaded = parsed;
        } catch (e) {
            loaded = [];
        }
    }

    if (loaded.length === 0) {
        loaded = [
            {
                id: 1,
                image_url: '/assets/images/hero/featured-green-shell.jpg',
                alt_text: 'Bracelete Caminhos em Prata 925 com Concha Verde Esmeralda',
                link_url: '/loja',
                sort_order: 1,
                is_active: 1
            },
            {
                id: 2,
                image_url: '/assets/images/testimonials/lifestyle-wrist.jpeg',
                alt_text: 'Joia Autoral DIVINIUM no Pulso — Coleção Caminhos',
                link_url: '/loja',
                sort_order: 2,
                is_active: 1
            },
            {
                id: 3,
                image_url: '/assets/images/about/model-wrist-sunglasses.jpg',
                alt_text: 'Alta Joalheria Autoral Brasileira DIVINIUM',
                link_url: '/loja',
                sort_order: 3,
                is_active: 1
            }
        ];
    }

    currentCarouselSlides = loaded;
    renderCarouselSlides();
}

function syncCarouselSlidesToForm() {
    const jsonInput = document.getElementById('heroCarouselSlidesJson');
    if (jsonInput) {
        jsonInput.value = JSON.stringify(currentCarouselSlides);
    }
    const active = currentCarouselSlides.filter(s => s.is_active == 1);
    const mainSync = document.getElementById('hero_editorial_main_img_sync');
    const secSync = document.getElementById('hero_editorial_sec_img_sync');
    if (mainSync && active.length > 0) mainSync.value = active[0].image_url;
    if (secSync && active.length > 1) secSync.value = active[1].image_url;
}

function renderCarouselSlides() {
    const container = document.getElementById('carouselSlidesContainer');
    const counter = document.getElementById('carouselSlidesCount');
    if (!container) return;

    if (counter) counter.innerText = currentCarouselSlides.length;

    if (currentCarouselSlides.length === 0) {
        container.innerHTML = `
            <div style="text-align: center; padding: 28px; background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1); border-radius: 10px; color: #8e8880; font-size: 0.84rem;">
                Nenhuma imagem adicionada ao carrossel ainda. Clique em "<strong>+ Adicionar Nova Imagem</strong>" para compor a abertura da Home.
            </div>
        `;
        syncCarouselSlidesToForm();
        return;
    }

    let html = '';
    currentCarouselSlides.forEach((slide, idx) => {
        const img = slide.image_url || '/assets/images/hero/featured-green-shell.jpg';
        const alt = slide.alt_text || '';
        const link = slide.link_url || '';
        const order = slide.sort_order !== undefined ? slide.sort_order : (idx + 1);
        const isActive = slide.is_active != 0;

        html += `
            <div class="carousel-slide-item ${isActive ? '' : 'is-inactive'}" id="slideItem_${idx}">
                <div class="carousel-slide-thumb-wrap">
                    <img src="${escapeHtml(img)}" 
                         id="slideThumb_${idx}" 
                         class="carousel-slide-thumb" 
                         alt="Preview Slide ${idx + 1}"
                         onerror="this.onerror=null; this.src='/assets/images/hero/featured-green-shell.jpg';">
                </div>

                <div class="carousel-slide-fields">
                    <div class="carousel-slide-header">
                        <div class="carousel-slide-title">
                            <span>Imagem #${idx + 1}</span>
                            ${!isActive ? '<span style="font-size: 0.7rem; color: #f87171; background: rgba(248,113,113,0.1); padding: 1px 6px; border-radius: 4px;">Oculta</span>' : ''}
                        </div>
                        <div class="carousel-slide-actions">
                            <button type="button" class="btn-slide-order" onclick="moveCarouselSlide(${idx}, -1)" ${idx === 0 ? 'disabled' : ''} title="Mover para cima">▲</button>
                            <button type="button" class="btn-slide-order" onclick="moveCarouselSlide(${idx}, 1)" ${idx === currentCarouselSlides.length - 1 ? 'disabled' : ''} title="Mover para baixo">▼</button>
                            <button type="button" class="btn-slide-delete" onclick="removeCarouselSlide(${idx})" title="Excluir esta imagem">✕ Excluir</button>
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group" style="margin: 0;">
                            <label style="font-size: 0.76rem;">URL da Imagem *</label>
                            <div style="display: flex; gap: 8px;">
                                <input type="text" class="form-input" style="font-size: 0.8rem;" value="${escapeHtml(img)}" placeholder="/assets/images/hero/featured-green-shell.jpg" oninput="updateCarouselSlide(${idx}, 'image_url', this.value); updateSlideThumb(${idx}, this.value)">
                                <label class="btn-admin-gold" style="padding: 0 12px; cursor: pointer; display: flex; align-items: center; white-space: nowrap; font-size: 0.74rem;">
                                    <span>📁 Upload</span>
                                    <input type="file" accept="image/*" style="display: none;" onchange="uploadSlideImage(this, ${idx})">
                                </label>
                            </div>
                        </div>

                        <div class="form-group" style="margin: 0;">
                            <label style="font-size: 0.76rem;">Texto Alternativo (Alt / Acessibilidade / SEO)</label>
                            <input type="text" class="form-input" style="font-size: 0.8rem;" value="${escapeHtml(alt)}" placeholder="Ex: Bracelete Caminhos em Prata 925" oninput="updateCarouselSlide(${idx}, 'alt_text', this.value)">
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group" style="margin: 0;">
                            <label style="font-size: 0.76rem;">Link de Destino (Ao Clicar na Imagem - Opcional)</label>
                            <input type="text" class="form-input" style="font-size: 0.8rem;" value="${escapeHtml(link)}" placeholder="Ex: /loja ou https://wa.me/..." oninput="updateCarouselSlide(${idx}, 'link_url', this.value)">
                        </div>

                        <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 12px;">
                            <div class="form-group" style="margin: 0; width: 110px;">
                                <label style="font-size: 0.76rem;">Ordem</label>
                                <input type="number" class="form-input" style="font-size: 0.8rem;" value="${order}" oninput="updateCarouselSlide(${idx}, 'sort_order', parseInt(this.value, 10) || 0)">
                            </div>

                            <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.8rem; color: #ded7cf; cursor: pointer; margin-bottom: 8px; user-select: none;">
                                <input type="checkbox" ${isActive ? 'checked' : ''} onchange="updateCarouselSlide(${idx}, 'is_active', this.checked ? 1 : 0); renderCarouselSlides();" style="accent-color: #CBD0D6; width: 16px; height: 16px;">
                                <span>Exibir imagem</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
    syncCarouselSlidesToForm();
}

function updateSlideThumb(idx, url) {
    const thumb = document.getElementById('slideThumb_' + idx);
    if (thumb && url) {
        thumb.src = url;
    }
}

function updateCarouselSlide(idx, key, val) {
    if (currentCarouselSlides[idx]) {
        currentCarouselSlides[idx][key] = val;
        syncCarouselSlidesToForm();
    }
}

function addNewCarouselSlide() {
    const newOrder = currentCarouselSlides.length + 1;
    currentCarouselSlides.push({
        id: Date.now(),
        image_url: '/assets/images/hero/featured-green-shell.jpg',
        alt_text: 'Nova Joia DIVINIUM',
        link_url: '/loja',
        sort_order: newOrder,
        is_active: 1
    });
    renderCarouselSlides();
}

function removeCarouselSlide(idx) {
    if (!confirm('Tem certeza que deseja remover esta imagem do carrossel?')) return;
    currentCarouselSlides.splice(idx, 1);
    currentCarouselSlides.forEach((s, i) => s.sort_order = i + 1);
    renderCarouselSlides();
}

function moveCarouselSlide(idx, direction) {
    const targetIdx = idx + direction;
    if (targetIdx < 0 || targetIdx >= currentCarouselSlides.length) return;

    const temp = currentCarouselSlides[idx];
    currentCarouselSlides[idx] = currentCarouselSlides[targetIdx];
    currentCarouselSlides[targetIdx] = temp;

    currentCarouselSlides.forEach((s, i) => s.sort_order = i + 1);
    renderCarouselSlides();
}

async function uploadSlideImage(input, idx) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    const formData = new FormData();
    formData.append('bg_file', file);
    formData.append('target_key', 'slide_' + idx);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
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
        const raw = await res.text();
        let data;
        try {
            data = JSON.parse(raw);
        } catch (jsonErr) {
            console.error('Resposta não-JSON do upload:', raw);
            alert('Não foi possível processar o upload do slide. Recarregue a página.');
            return;
        }

        if (data.auth_required) {
            alert('Sua sessão expirou. Por favor, faça login novamente para enviar imagens.');
            return;
        }

        if (data.success && data.url) {
            updateCarouselSlide(idx, 'image_url', data.url);
            renderCarouselSlides();
            alert('Imagem enviada com sucesso! Clique em Salvar Alterações para publicar.');
        } else {
            alert(data.error || 'Erro ao enviar imagem.');
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCarouselSlidesManager);
} else {
    initCarouselSlidesManager();
}

function updateMobileMenuLivePreview() {
    const getVal = (id, def) => document.getElementById(id)?.value || def;

    const bg = getVal('mobile_nav_bg_color', '#252525');
    const txt = getVal('mobile_nav_text_color', '#ded7cf');
    const accent = getVal('mobile_nav_accent_color', '#CBD0D6');

    const drawer = document.getElementById('mobileMenuLivePreviewDrawer');
    if (drawer) {
        drawer.style.background = bg;
        drawer.style.color = txt;
    }

    const previewLogoText = document.getElementById('previewLogoText');
    if (previewLogoText) previewLogoText.textContent = getVal('mobile_nav_logo_text', 'DIVINIUM');

    const previewLogoSub = document.getElementById('previewLogoSub');
    if (previewLogoSub) previewLogoSub.textContent = getVal('mobile_nav_logo_sub', 'HAUTE JOAILLERIE');

    const previewHeaderBadge = document.getElementById('previewHeaderBadge');
    if (previewHeaderBadge) {
        previewHeaderBadge.textContent = getVal('mobile_nav_header_badge', 'JOALHERIA AUTORAL');
        previewHeaderBadge.style.color = accent;
        previewHeaderBadge.style.borderColor = accent;
    }

    const setPreview = (id, valId, def) => {
        const el = document.getElementById(id);
        if (el) el.textContent = getVal(valId, def);
    };

    setPreview('previewTextHome', 'mobile_nav_home_text', 'Início');
    setPreview('previewTextStore', 'mobile_nav_store_text', 'Boutique / Loja');
    setPreview('previewBadgeStore', 'mobile_nav_store_badge_text', 'Exclusivo');
    setPreview('previewTextCollections', 'mobile_nav_collections_text', 'Coleções');
    setPreview('previewTextSubAll', 'mobile_nav_sub_all_text', 'Ver Todas as Peças');
    setPreview('previewTextSubBracelets', 'mobile_nav_sub_bracelets_text', 'Braceletes Exclusivos');
    setPreview('previewTextAbout', 'mobile_nav_about_text', 'Sobre');
    setPreview('previewTextServices', 'mobile_nav_services_text', 'Garantia & Serviços');
    setPreview('previewTextWishlist', 'mobile_nav_wishlist_text', 'Lista de Desejos');
    setPreview('previewTextWhatsapp', 'mobile_nav_whatsapp_text', 'Concierge VIP via WhatsApp');
    setPreview('previewTextLogin', 'mobile_nav_login_text', 'Entrar no Painel Administrativo');
    setPreview('previewTextPerk', 'mobile_nav_perk_text', '✨ Joalheria Autoral Brasileira • Prata 925 & Gemas Raras');

    const previewLinkHome = document.getElementById('previewLinkHome');
    if (previewLinkHome) {
        previewLinkHome.style.borderLeftColor = accent;
    }
}

function updateAboutLivePreview() {
    const getVal = (id, def) => document.getElementById(id)?.value || def;

    const bg = getVal('about_bg_color', '#252525');
    const title = getVal('about_title_color', '#ffffff');
    const text = getVal('about_text_color', '#ded7cf');
    const accent = getVal('about_accent_color', '#CBD0D6');
    const tag = getVal('about_tag_color', '#CBD0D6');
    const cardBg = getVal('about_card_bg', '#161513');
    const cardBorder = getVal('about_card_border', 'rgba(212, 186, 136, 0.25)');
    const btn = getVal('about_btn_color', '#CBD0D6');
    const captionBg = getVal('about_caption_bg', 'rgba(18, 17, 16, 0.85)');

    const previewContainer = document.getElementById('aboutLivePreviewContainer');
    if (previewContainer) {
        previewContainer.style.background = bg;
    }

    const previewTag = document.getElementById('aboutPreviewTag');
    if (previewTag) {
        previewTag.textContent = getVal('about_tag', 'MANIFESTO DIVINIUM');
        previewTag.style.color = tag;
    }

    const previewTitle = document.getElementById('aboutPreviewTitle');
    if (previewTitle) {
        previewTitle.textContent = getVal('about_title', 'A Beleza Não Precisa Gritar Para Ser Notada');
        previewTitle.style.color = title;
    }

    const previewP1 = document.getElementById('aboutPreviewP1');
    if (previewP1) {
        previewP1.textContent = getVal('about_p1', 'A DIVINIUM nasceu da convicção de que o verdadeiro luxo é autoral, sutil e profundamente conectado à sua identidade...');
        previewP1.style.color = text;
    }

    const isQuoteActive = document.querySelector('input[name="about_quote_visible"][type="checkbox"]')?.checked ?? true;
    const isCtaActive = document.querySelector('input[name="about_cta_visible"][type="checkbox"]')?.checked ?? true;

    const previewQuote = document.getElementById('aboutPreviewQuote');
    if (previewQuote) {
        previewQuote.textContent = '"' + getVal('about_quote', 'Qual delas conversa com você hoje?') + '"';
        previewQuote.style.color = accent;
        previewQuote.style.display = isQuoteActive ? '' : 'none';
    }

    const previewBtn = document.getElementById('aboutPreviewBtn');
    const previewBtnWrap = document.getElementById('aboutPreviewBtnWrap');
    if (previewBtnWrap) {
        previewBtnWrap.style.display = isCtaActive ? '' : 'none';
    }
    if (previewBtn) {
        previewBtn.textContent = getVal('about_cta_text', 'Conheça Nossa História');
        previewBtn.style.color = btn;
        previewBtn.style.borderColor = btn;
    }

    const previewCard = document.getElementById('aboutPreviewCard');
    if (previewCard) {
        previewCard.style.backgroundColor = cardBg;
        previewCard.style.borderColor = cardBorder;
    }

    const previewCardTitle = document.getElementById('aboutPreviewCardTitle');
    if (previewCardTitle) {
        previewCardTitle.textContent = getVal('about_micro_title', 'Textura Mineral Autêntica');
        previewCardTitle.style.color = title;
    }

    const previewCardDesc = document.getElementById('aboutPreviewCardDesc');
    if (previewCardDesc) {
        previewCardDesc.textContent = getVal('about_micro_desc', 'Fragmentos minerais nobres preservados com resina.');
        previewCardDesc.style.color = text;
    }

    const isCaptionActive = document.querySelector('input[name="about_editorial_caption_visible"][type="checkbox"]')?.checked ?? true;

    const previewCaption = document.getElementById('aboutPreviewCaption');
    if (previewCaption) {
        previewCaption.textContent = getVal('about_editorial_caption', 'COLEÇÃO CAMINHOS • UM MESMO DESIGN, DIFERENTES FORMAS DE SENTIR');
        previewCaption.style.backgroundColor = captionBg;
        previewCaption.style.color = accent;
        previewCaption.style.display = isCaptionActive ? '' : 'none';
    }
}

function applyAboutColorPreset(preset) {
    const presets = {
        'default': {
            about_bg_color: '#252525',
            about_title_color: '#ffffff',
            about_text_color: '#ded7cf',
            about_accent_color: '#CBD0D6',
            about_tag_color: '#CBD0D6',
            about_card_bg: '#161513',
            about_card_border: 'rgba(212, 186, 136, 0.25)',
            about_btn_color: '#CBD0D6',
            about_caption_bg: 'rgba(18, 17, 16, 0.85)'
        },
        'light': {
            about_bg_color: '#fbf9f5',
            about_title_color: '#1a1918',
            about_text_color: '#55514c',
            about_accent_color: '#9c7a3c',
            about_tag_color: '#9c7a3c',
            about_card_bg: '#f0ede6',
            about_card_border: 'rgba(156, 122, 60, 0.3)',
            about_btn_color: '#1a1918',
            about_caption_bg: 'rgba(251, 249, 245, 0.9)'
        },
        'emerald': {
            about_bg_color: '#071510',
            about_title_color: '#ffffff',
            about_text_color: '#d2dfd8',
            about_accent_color: '#e5c98d',
            about_tag_color: '#CBD0D6',
            about_card_bg: '#0e221b',
            about_card_border: 'rgba(74, 222, 128, 0.25)',
            about_btn_color: '#e5c98d',
            about_caption_bg: 'rgba(7, 21, 16, 0.9)'
        },
        'sapphire': {
            about_bg_color: '#0a101d',
            about_title_color: '#ffffff',
            about_text_color: '#cbd5e1',
            about_accent_color: '#93c5fd',
            about_tag_color: '#60a5fa',
            about_card_bg: '#131d31',
            about_card_border: 'rgba(96, 165, 250, 0.3)',
            about_btn_color: '#93c5fd',
            about_caption_bg: 'rgba(10, 16, 29, 0.9)'
        }
    };

    const config = presets[preset] || presets['default'];
    for (const [key, val] of Object.entries(config)) {
        const textInput = document.getElementById(key);
        if (textInput) textInput.value = val;
        const pickerInput = document.getElementById(key + '_picker');
        if (pickerInput && val.startsWith('#') && val.length === 7) {
            pickerInput.value = val;
        }
    }
    updateAboutLivePreview();
}

// Suporte a abertura direta de abas por URL (ex: ?tab=sec-mobile-menu ou #sec-mobile-menu)
function checkInitialEditorTab() {
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab') || window.location.hash.replace('#', '');
    if (tabParam) {
        const btn = document.querySelector(`.editor-nav-tabs button[onclick*="'${tabParam}'"]`);
        if (btn) {
            switchSection(tabParam, btn);
        }
    }
    toggleLogoFields();
    updateMobileMenuLivePreview();
    updateAboutLivePreview();
    updateThemeColorsLivePreview();
}

// Funções de Paletas, Texturas e Prévia do Estúdio Central de Cores & Fundo
function applyThemePalette(themeName) {
    const palettes = {
        'gold': {
            bg: '#252525',
            surface: '#141312',
            primary: '#A7ADB4',
            accent: '#E0C080',
            text: '#F4EFEA',
            textMuted: '#9E978E'
        },
        'silver': {
            bg: '#FBF9F5',
            surface: '#FFFFFF',
            primary: '#1A1918',
            accent: '#8A8782',
            text: '#1A1918',
            textMuted: '#6B665F'
        },
        'rose': {
            bg: '#120D0A',
            surface: '#1C1510',
            primary: '#D48B60',
            accent: '#E5A882',
            text: '#F9F4F0',
            textMuted: '#A89990'
        },
        'emerald': {
            bg: '#06130D',
            surface: '#0D2018',
            primary: '#38A169',
            accent: '#68D391',
            text: '#EBF8F2',
            textMuted: '#8BA698'
        }
    };

    const p = palettes[themeName] || palettes['gold'];
    const fields = [
        { textId: 'lp_custom_color_bg', pickerId: 'picker_lp_color_bg', val: p.bg },
        { textId: 'lp_custom_color_surface', pickerId: 'picker_lp_color_surface', val: p.surface },
        { textId: 'lp_custom_color_primary', pickerId: 'picker_lp_color_primary', val: p.primary },
        { textId: 'lp_custom_color_accent', pickerId: 'picker_lp_color_accent', val: p.accent },
        { textId: 'lp_custom_color_text', pickerId: 'picker_lp_color_text', val: p.text },
        { textId: 'lp_custom_color_text_muted', pickerId: 'picker_lp_color_text_muted', val: p.textMuted }
    ];

    fields.forEach(f => {
        const txt = document.getElementById(f.textId);
        if (txt) txt.value = f.val;
        const pck = document.getElementById(f.pickerId);
        if (pck) pck.value = f.val;
    });

    updateThemeColorsLivePreview();
}

function updateThemeColorsLivePreview() {
    const bgVal = document.getElementById('lp_site_bg_color')?.value || document.getElementById('lp_custom_color_bg')?.value || '#252525';
    const surfaceVal = document.getElementById('lp_custom_color_surface')?.value || '#141312';
    const primaryVal = document.getElementById('lp_custom_color_primary')?.value || '#CBD0D6';
    const textVal = document.getElementById('lp_custom_color_text')?.value || '#ffffff';
    const imgVal = document.getElementById('lp_site_bg_image')?.value?.trim() || '';
    const overlayVal = parseFloat(document.getElementById('lp_site_bg_overlay')?.value || '0.75');
    const repeatVal = document.getElementById('lp_site_bg_repeat')?.value || 'cover';

    // Sombras
    const shadowsEnabled = document.getElementById('site_shadows_enabled')?.checked ?? true;
    const shadowColor = document.getElementById('site_shadow_color')?.value || '#000000';
    const shadowIntensity = parseFloat(document.getElementById('site_shadow_intensity')?.value || '0.25');
    const shadowBlur = parseInt(document.getElementById('site_shadow_blur')?.value || '24');
    const lblShadows = document.getElementById('lbl_shadows_status');
    if (lblShadows) lblShadows.innerText = shadowsEnabled ? 'Sombras Ativadas' : 'Sombras Desativadas';

    // Efeitos Visuais
    const glassEnabled = document.getElementById('site_fx_glass_enabled')?.checked ?? true;
    const glassBlur = parseInt(document.getElementById('site_fx_glass_blur')?.value || '12');
    const vignetteEnabled = document.getElementById('site_fx_vignette_enabled')?.checked ?? false;
    const vignetteIntensity = parseFloat(document.getElementById('site_fx_vignette_intensity')?.value || '0.40');
    const glowEnabled = document.getElementById('site_fx_glow_enabled')?.checked ?? true;
    const glowColor = document.getElementById('site_fx_glow_color')?.value || '#A7ADB4';
    const glowIntensity = parseFloat(document.getElementById('site_fx_glow_intensity')?.value || '0.14');

    const card = document.getElementById('lpColorsLivePreviewCard');
    const overlay = document.getElementById('lpColorsLivePreviewOverlay');
    const highlight = document.getElementById('lpPreviewHighlight');
    const cardBox = document.getElementById('lpPreviewCardBox');
    const btn = document.getElementById('lpPreviewBtn');
    const title = document.getElementById('lpPreviewTitle');

    // Helper Hex -> RGBA
    const toRgba = (hex, a) => {
        let c = (hex || '#000000').replace('#', '');
        if (c.length === 3) c = c[0]+c[0]+c[1]+c[1]+c[2]+c[2];
        const r = parseInt(c.substring(0, 2), 16) || 0;
        const g = parseInt(c.substring(2, 4), 16) || 0;
        const b = parseInt(c.substring(4, 6), 16) || 0;
        return `rgba(${r}, ${g}, ${b}, ${a})`;
    };

    if (card) {
        card.style.backgroundColor = bgVal;
        if (glowEnabled) {
            const glowRgba = toRgba(glowColor, glowIntensity);
            if (imgVal) {
                card.style.backgroundImage = `radial-gradient(circle at 50% 20%, ${glowRgba} 0%, transparent 70%), url('${imgVal}')`;
            } else {
                card.style.backgroundImage = `radial-gradient(circle at 50% 20%, ${glowRgba} 0%, transparent 70%)`;
            }
        } else {
            if (imgVal) {
                card.style.backgroundImage = `url('${imgVal}')`;
            } else {
                card.style.backgroundImage = 'none';
            }
        }
        card.style.backgroundSize = (repeatVal === 'repeat') ? 'auto' : 'cover';
        card.style.backgroundRepeat = (repeatVal === 'repeat') ? 'repeat' : 'no-repeat';
        card.style.backgroundPosition = 'center center';

        if (shadowsEnabled) {
            const sRgba = toRgba(shadowColor, shadowIntensity);
            card.style.boxShadow = `0 14px ${Math.round(shadowBlur * 1.4)}px ${sRgba}`;
        } else {
            card.style.boxShadow = 'none';
        }
    }

    if (overlay) {
        if (vignetteEnabled) {
            const vigRgba = `rgba(0, 0, 0, ${vignetteIntensity})`;
            overlay.style.background = `radial-gradient(ellipse at 50% 50%, rgba(13, 12, 11, ${overlayVal * 0.5}) 30%, ${vigRgba} 100%)`;
        } else {
            overlay.style.background = `rgba(13, 12, 11, ${overlayVal})`;
        }
    }

    if (cardBox) {
        cardBox.style.backgroundColor = surfaceVal;
        if (shadowsEnabled) {
            const sRgba = toRgba(shadowColor, shadowIntensity);
            cardBox.style.boxShadow = `0 8px ${shadowBlur}px ${sRgba}`;
        } else {
            cardBox.style.boxShadow = 'none';
        }
        if (glassEnabled) {
            cardBox.style.backdropFilter = `blur(${glassBlur}px)`;
            cardBox.style.webkitBackdropFilter = `blur(${glassBlur}px)`;
        } else {
            cardBox.style.backdropFilter = 'none';
            cardBox.style.webkitBackdropFilter = 'none';
        }
    }

    if (highlight) highlight.style.color = primaryVal;
    if (title) title.style.color = textVal;
    if (btn) {
        btn.style.backgroundColor = primaryVal;
        btn.style.color = (bgVal === '#FBF9F5' || bgVal === '#FFFFFF' || bgVal === '#F8F8F7' || primaryVal === '#1A1918') ? '#202020' : '#FFFFFF';
        if (shadowsEnabled) {
            btn.style.boxShadow = `0 4px 12px ${toRgba(shadowColor, shadowIntensity * 0.7)}`;
        } else {
            btn.style.boxShadow = 'none';
        }
    }
}

function applyBgPresetLanding(url, repeat) {
    const input = document.getElementById('lp_site_bg_image');
    if (input) input.value = url;
    const select = document.getElementById('lp_site_bg_repeat');
    if (select) select.value = repeat;
    updateThemeColorsLivePreview();
}

async function uploadSiteBgFromLanding(fileInput) {
    if (!fileInput.files || !fileInput.files[0]) return;
    const file = fileInput.files[0];
    const formData = new FormData();
    formData.append('bg_file', file);
    formData.append('target_key', 'site_bg_image');

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        || document.querySelector('input[name="_csrf_token"]')?.value || '';
    formData.append('_csrf_token', csrf);

    try {
        const res = await fetch('/admin/temas/upload-fundo', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            }
        });
        const data = await res.json();
        if (data.success && data.url) {
            const input = document.getElementById('lp_site_bg_image');
            if (input) input.value = data.url;
            updateThemeColorsLivePreview();
            alert('✓ Imagem de fundo enviada com sucesso!');
        } else {
            alert(data.error || 'Erro no upload do fundo.');
        }
    } catch (err) {
        alert('Erro ao enviar imagem: ' + err.message);
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', checkInitialEditorTab);
} else {
    checkInitialEditorTab();
}
</script>
