<?php
/**
 * VIEW: IDENTIDADE VISUAL DIVINIUM — EDITOR DE PALETA ÚNICA PERSONALIZÁVEL
 */
$isSinglePaletteSaved = (($customSettings['divinium_single_palette_saved'] ?? '') === '1');

$paletteElements = [
    // ✦ PALETA GLOBAL & DESIGN SYSTEM
    [
        'key'     => 'custom_color_bg',
        'picker'  => 'picker_bg',
        'label'   => 'Fundo principal',
        'initial' => '#FFFFFF',
        'section' => '✦ Paleta Global & Design System',
    ],
    [
        'key'     => 'custom_color_bg_alt',
        'picker'  => 'picker_bg_alt',
        'label'   => 'Fundo secundário',
        'initial' => '#F7F7F7',
        'section' => '✦ Paleta Global & Design System',
    ],
    [
        'key'     => 'custom_color_surface',
        'picker'  => 'picker_surface',
        'label'   => 'Fundo dos cards',
        'initial' => '#FFFFFF',
        'section' => '✦ Paleta Global & Design System',
    ],
    [
        'key'     => 'custom_color_section_bg',
        'picker'  => 'picker_section_bg',
        'label'   => 'Fundo das seções',
        'initial' => '#F2F2F2',
        'section' => '✦ Paleta Global & Design System',
    ],
    [
        'key'     => 'custom_color_title',
        'picker'  => 'picker_title',
        'label'   => 'Títulos',
        'initial' => '#171717',
        'section' => '✦ Paleta Global & Design System',
    ],
    [
        'key'     => 'custom_color_text',
        'picker'  => 'picker_text',
        'label'   => 'Textos principais',
        'initial' => '#555555',
        'section' => '✦ Paleta Global & Design System',
    ],
    [
        'key'     => 'custom_color_text_muted',
        'picker'  => 'picker_text_muted',
        'label'   => 'Textos secundários',
        'initial' => '#777777',
        'section' => '✦ Paleta Global & Design System',
    ],
    [
        'key'     => 'custom_color_primary',
        'picker'  => 'picker_primary',
        'label'   => 'Cor principal da marca',
        'initial' => '#171717',
        'section' => '✦ Paleta Global & Design System',
    ],
    [
        'key'     => 'custom_color_accent',
        'picker'  => 'picker_accent',
        'label'   => 'Cor de destaque',
        'initial' => '#A7ADB4',
        'section' => '✦ Paleta Global & Design System',
    ],
    [
        'key'     => 'custom_color_btn_primary_bg',
        'picker'  => 'picker_btn_primary_bg',
        'label'   => 'Botões principais — fundo',
        'initial' => '#171717',
        'section' => '✦ Paleta Global & Design System',
    ],
    [
        'key'     => 'custom_color_btn_primary_text',
        'picker'  => 'picker_btn_primary_text',
        'label'   => 'Botões principais — texto',
        'initial' => '#FFFFFF',
        'section' => '✦ Paleta Global & Design System',
    ],
    [
        'key'     => 'custom_color_btn_secondary_bg',
        'picker'  => 'picker_btn_secondary_bg',
        'label'   => 'Botões secundários — fundo',
        'initial' => '#FFFFFF',
        'section' => '✦ Paleta Global & Design System',
    ],
    [
        'key'     => 'custom_color_btn_secondary_text',
        'picker'  => 'picker_btn_secondary_text',
        'label'   => 'Botões secundários — texto',
        'initial' => '#171717',
        'section' => '✦ Paleta Global & Design System',
    ],
    [
        'key'     => 'custom_color_border',
        'picker'  => 'picker_border',
        'label'   => 'Bordas e divisórias',
        'initial' => '#D9DDE1',
        'section' => '✦ Paleta Global & Design System',
    ],
    [
        'key'     => 'custom_color_icon_nav',
        'picker'  => 'picker_icon_nav',
        'label'   => 'Ícones',
        'initial' => '#777777',
        'section' => '✦ Paleta Global & Design System',
    ],
    [
        'key'     => 'custom_color_link',
        'picker'  => 'picker_link',
        'label'   => 'Links',
        'initial' => '#555555',
        'section' => '✦ Paleta Global & Design System',
    ],
    [
        'key'     => 'custom_color_btn_hover',
        'picker'  => 'picker_btn_hover',
        'label'   => 'Hover dos botões',
        'initial' => '#A7ADB4',
        'section' => '✦ Paleta Global & Design System',
    ],

    // 👑 HERO BANNER PRINCIPAL
    [
        'key'     => 'custom_color_hero_bg',
        'picker'  => 'picker_hero_bg',
        'label'   => 'Hero — Fundo',
        'initial' => '#252525',
        'section' => '👑 Hero Banner Principal',
    ],
    [
        'key'     => 'custom_color_hero_title',
        'picker'  => 'picker_hero_title',
        'label'   => 'Hero — Título Principal',
        'initial' => '#FFFFFF',
        'section' => '👑 Hero Banner Principal',
    ],
    [
        'key'     => 'custom_color_hero_subtitle',
        'picker'  => 'picker_hero_subtitle',
        'label'   => 'Hero — Subtítulo / Descrição',
        'initial' => '#CBD0D6',
        'section' => '👑 Hero Banner Principal',
    ],
    [
        'key'     => 'custom_color_hero_badge_bg',
        'picker'  => 'picker_hero_badge_bg',
        'label'   => 'Hero — Selo Editorial (Fundo)',
        'initial' => '#1E2024',
        'section' => '👑 Hero Banner Principal',
    ],
    [
        'key'     => 'custom_color_hero_badge_text',
        'picker'  => 'picker_hero_badge_text',
        'label'   => 'Hero — Selo Editorial (Texto/Ícone)',
        'initial' => '#CBD0D6',
        'section' => '👑 Hero Banner Principal',
    ],
    [
        'key'     => 'custom_color_hero_badge_border',
        'picker'  => 'picker_hero_badge_border',
        'label'   => 'Hero — Selo Editorial (Borda)',
        'initial' => '#4A5058',
        'section' => '👑 Hero Banner Principal',
    ],
    [
        'key'     => 'custom_color_hero_btn_bg',
        'picker'  => 'picker_hero_btn_bg',
        'label'   => 'Hero — Botão Principal (Fundo)',
        'initial' => '#CBD0D6',
        'section' => '👑 Hero Banner Principal',
    ],
    [
        'key'     => 'custom_color_hero_btn_text',
        'picker'  => 'picker_hero_btn_text',
        'label'   => 'Hero — Botão Principal (Texto)',
        'initial' => '#171717',
        'section' => '👑 Hero Banner Principal',
    ],
    [
        'key'     => 'custom_color_hero_btn_sec_text',
        'picker'  => 'picker_hero_btn_sec_text',
        'label'   => 'Hero — Botão Concierge (Texto/Borda)',
        'initial' => '#F3EFE6',
        'section' => '👑 Hero Banner Principal',
    ],
    [
        'key'     => 'custom_color_hero_trust_text',
        'picker'  => 'picker_hero_trust_text',
        'label'   => 'Hero — Selos de Confiança (Texto/Ícone)',
        'initial' => '#CBD0D6',
        'section' => '👑 Hero Banner Principal',
    ],

    // 🛍️ ABA DA SACOLA (CART DRAWER)
    [
        'key'     => 'custom_color_cart_bg',
        'picker'  => 'picker_cart_bg',
        'label'   => 'Sacola — Fundo Principal',
        'initial' => '#FFFFFF',
        'section' => '🛍️ Aba da Sacola (Gaveta Lateral)',
    ],
    [
        'key'     => 'custom_color_cart_footer_bg',
        'picker'  => 'picker_cart_footer_bg',
        'label'   => 'Sacola — Fundo do Rodapé & Header',
        'initial' => '#F7F7F7',
        'section' => '🛍️ Aba da Sacola (Gaveta Lateral)',
    ],
    [
        'key'     => 'custom_color_cart_title',
        'picker'  => 'picker_cart_title',
        'label'   => 'Sacola — Título ("Sua Sacola...")',
        'initial' => '#171717',
        'section' => '🛍️ Aba da Sacola (Gaveta Lateral)',
    ],
    [
        'key'     => 'custom_color_cart_item_title',
        'picker'  => 'picker_cart_item_title',
        'label'   => 'Sacola — Nome dos Produtos',
        'initial' => '#171717',
        'section' => '🛍️ Aba da Sacola (Gaveta Lateral)',
    ],
    [
        'key'     => 'custom_color_cart_text',
        'picker'  => 'picker_cart_text',
        'label'   => 'Sacola — Textos e Detalhes',
        'initial' => '#555555',
        'section' => '🛍️ Aba da Sacola (Gaveta Lateral)',
    ],
    [
        'key'     => 'custom_color_cart_price',
        'picker'  => 'picker_cart_price',
        'label'   => 'Sacola — Preço e Subtotal',
        'initial' => '#171717',
        'section' => '🛍️ Aba da Sacola (Gaveta Lateral)',
    ],
    [
        'key'     => 'custom_color_cart_border',
        'picker'  => 'picker_cart_border',
        'label'   => 'Sacola — Bordas e Divisórias',
        'initial' => '#D9DDE1',
        'section' => '🛍️ Aba da Sacola (Gaveta Lateral)',
    ],
    [
        'key'     => 'custom_color_cart_btn_bg',
        'picker'  => 'picker_cart_btn_bg',
        'label'   => 'Sacola — Botão Finalizar (Fundo)',
        'initial' => '#171717',
        'section' => '🛍️ Aba da Sacola (Gaveta Lateral)',
    ],
    [
        'key'     => 'custom_color_cart_btn_text',
        'picker'  => 'picker_cart_btn_text',
        'label'   => 'Sacola — Botão Finalizar (Texto)',
        'initial' => '#FFFFFF',
        'section' => '🛍️ Aba da Sacola (Gaveta Lateral)',
    ],
];

$resolvePaletteColor = static function (string $key, string $initialHex) use ($customSettings, $isSinglePaletteSaved): string {
    if ($isSinglePaletteSaved && !empty($customSettings[$key])) {
        $val = strtoupper(trim((string) $customSettings[$key]));
        if (preg_match('/^#[0-9A-F]{6}$/', $val)) {
            return $val;
        }
    }
    return strtoupper($initialHex);
};
?>

<!-- BANNER DE APRESENTAÇÃO DA PALETA ÚNICA -->
<div class="admin-card" style="background: linear-gradient(135deg, rgba(167, 173, 180, 0.14) 0%, rgba(20, 22, 26, 0.95) 100%); border: 1px solid rgba(167, 173, 180, 0.35); margin-bottom: 28px; padding: 26px 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                <span style="color: #cbd0d6; font-size: 1.3rem;">✦</span>
                <h2 style="font-family: var(--font-serif); font-size: 1.6rem; color: #ffffff; margin: 0; letter-spacing: 0.5px;">Identidade Visual DIVINIUM</h2>
                <span style="background: rgba(167, 173, 180, 0.15); color: #cbd0d6; font-size: 0.7rem; font-weight: 700; padding: 3px 10px; border-radius: 12px; text-transform: uppercase;">Paleta Única Editável</span>
            </div>
            <p style="font-size: 0.88rem; color: #b8b1a7; margin: 0; max-width: 720px; line-height: 1.5;">
                Personalize individualmente cada cor da paleta oficial da DIVINIUM por seletor visual ou código HEX, visualize as alterações antes de aplicar e restaure qualquer cor ao seu valor inicial quando desejar.
            </p>
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <a href="/" target="_blank" class="btn-admin-gold" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <span>Ver Loja Ao Vivo ↗</span>
            </a>
        </div>
    </div>
</div>

<!-- PALETA ÚNICA EDITÁVEL: IDENTIDADE VISUAL DIVINIUM -->
<div class="admin-card" style="border: 1px solid rgba(167, 173, 180, 0.35); padding: 26px; margin-bottom: 30px; background: #16181b;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 16px; flex-wrap: wrap; gap: 14px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                <span style="color: #A7ADB4;">🎨</span>
                <h3 class="card-title" style="font-size: 1.35rem; margin: 0; color: #FFFFFF;">
                    Identidade Visual DIVINIUM
                </h3>
                <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 0.72rem; font-weight: 700; color: #FFFFFF; background: rgba(167, 173, 180, 0.2); padding: 4px 10px; border-radius: 8px; border: 1px solid #A7ADB4;">
                    <span style="width: 7px; height: 7px; border-radius: 50%; background: #A7ADB4; box-shadow: 0 0 8px #A7ADB4;"></span>
                    Paleta Oficial Ativa
                </span>
            </div>
            <p style="font-size: 0.82rem; color: #9e978e; margin: 4px 0 0;">
                Edite cada cor de forma independente. Fotografias, imagens das joias e personalizações específicas de componentes que já possuem controles próprios permanecem preservadas.
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <button type="button" class="btn-admin-secondary" onclick="resetToThemeDefaults()">
                <span>↺ Restaurar Todas as Cores Iniciais</span>
            </button>
            <button type="button" id="btnSavePaletteMain" class="btn-admin-gold" onclick="document.getElementById('customThemeForm').requestSubmit()">
                <span>Salvar e Aplicar Paleta</span>
            </button>
        </div>
    </div>

    <form id="customThemeForm" onsubmit="handleCustomThemeSubmit(event)">
        <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">
        <input type="hidden" name="divinium_single_palette_saved" value="1">

        <!-- GRADE DE CONTROLES INDIVIDUAIS DE CORES ORGANIZADOS POR SEÇÃO -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(255px, 1fr)); gap: 16px; margin-bottom: 26px;">
            <?php 
            $currentSection = null;
            foreach ($paletteElements as $item): 
                $sec = $item['section'] ?? '';
                if ($sec !== $currentSection):
                    $currentSection = $sec;
            ?>
                <div style="grid-column: 1 / -1; margin-top: <?= $currentSection === '✦ Paleta Global & Design System' ? '0' : '22px' ?>; margin-bottom: 4px; padding-bottom: 8px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); display: flex; align-items: center; justify-content: space-between;">
                    <span style="font-size: 0.95rem; font-weight: 700; color: #ffffff; letter-spacing: 0.5px;">
                        <?= \App\Core\View::e($currentSection) ?>
                    </span>
                    <span style="font-size: 0.72rem; color: #a7adb4; text-transform: uppercase; letter-spacing: 0.5px;">Cores Editáveis</span>
                </div>
            <?php endif; ?>
                <?php
                    $k = $item['key'];
                    $pId = $item['picker'];
                    $lbl = $item['label'];
                    $initHex = $item['initial'];
                    $currentVal = $resolvePaletteColor($k, $initHex);
                ?>
                <div class="form-group" style="margin: 0; background: rgba(255, 255, 255, 0.025); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 10px; padding: 12px 14px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; gap: 6px;">
                        <label for="<?= $k ?>" style="font-size: 0.8rem; font-weight: 600; color: #f1f5f9; margin: 0;">
                            <?= \App\Core\View::e($lbl) ?>
                        </label>
                        <button type="button"
                                onclick="clearColorField('<?= $k ?>', '<?= $pId ?>', '<?= $initHex ?>')"
                                title="Restaurar ao valor inicial (<?= $initHex ?>)"
                                style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); border-radius: 6px; color: #cbd5e1; cursor: pointer; font-size: 0.68rem; padding: 2px 7px; transition: all 0.2s;">
                            ↺ <?= $initHex ?>
                        </button>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color"
                                id="<?= $pId ?>"
                                value="<?= \App\Core\View::e($currentVal) ?>"
                                oninput="syncFromColorPicker('<?= $k ?>', this.value)"
                                onchange="syncFromColorPicker('<?= $k ?>', this.value)"
                                style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.22); background: transparent; cursor: pointer; flex-shrink: 0;">
                        <input type="text"
                                id="<?= $k ?>"
                                name="<?= $k ?>"
                                class="form-input"
                                value="<?= \App\Core\View::e($currentVal) ?>"
                                maxlength="7"
                                placeholder="<?= $initHex ?>"
                                oninput="syncFromHexField(this, '<?= $pId ?>')"
                                onblur="normalizeHexOnBlur(this, '<?= $pId ?>', '<?= $initHex ?>')"
                                style="font-family: monospace; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- CONTROLES DE TIPOGRAFIA E BORDAS PRESERVADOS -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; border-top: 1px solid rgba(255, 255, 255, 0.06); padding-top: 18px;">
            <div class="form-group" style="margin: 0;">
                <label for="custom_font_serif">Tipografia dos Títulos Principais</label>
                <select id="custom_font_serif" name="custom_font_serif" class="form-select" onchange="updateCustomPreview()">
                    <option value="'Cormorant Garamond', Georgia, serif" <?= ($customSettings['custom_font_serif'] ?? '') === "'Cormorant Garamond', Georgia, serif" ? 'selected' : '' ?>>Cormorant Garamond (Alta Joalheria)</option>
                    <option value="'Playfair Display', serif" <?= ($customSettings['custom_font_serif'] ?? '') === "'Playfair Display', serif" ? 'selected' : '' ?>>Playfair Display (Vogue Editorial)</option>
                    <option value="'Cinzel', serif" <?= ($customSettings['custom_font_serif'] ?? '') === "'Cinzel', serif" ? 'selected' : '' ?>>Cinzel (Imperial Romana)</option>
                    <option value="'Montserrat', sans-serif" <?= ($customSettings['custom_font_serif'] ?? '') === "'Montserrat', sans-serif" ? 'selected' : '' ?>>Montserrat (Moderna & Minimalista)</option>
                </select>
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="custom_font_sans">Tipografia do Texto / Leitura</label>
                <select id="custom_font_sans" name="custom_font_sans" class="form-select">
                    <option value="'Plus Jakarta Sans', sans-serif">Plus Jakarta Sans (Legibilidade 4K)</option>
                    <option value="'Inter', sans-serif" <?= ($customSettings['custom_font_sans'] ?? '') === "'Inter', sans-serif" ? 'selected' : '' ?>>Inter (Clean Contemporâneo)</option>
                    <option value="'Outfit', sans-serif" <?= ($customSettings['custom_font_sans'] ?? '') === "'Outfit', sans-serif" ? 'selected' : '' ?>>Outfit (Geométrica Suave)</option>
                </select>
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="custom_border_radius">Estilo de Bordas & Botões</label>
                <select id="custom_border_radius" name="custom_border_radius" class="form-select" onchange="updateCustomPreview()">
                    <option value="8px" <?= ($customSettings['custom_border_radius'] ?? '8px') === '8px' ? 'selected' : '' ?>>8px — Luxo Clássico (Equilibrado)</option>
                    <option value="16px" <?= ($customSettings['custom_border_radius'] ?? '') === '16px' ? 'selected' : '' ?>>16px — Contemporâneo Macio</option>
                    <option value="24px" <?= ($customSettings['custom_border_radius'] ?? '') === '24px' ? 'selected' : '' ?>>24px — Pílula / Soft Organic</option>
                    <option value="2px" <?= ($customSettings['custom_border_radius'] ?? '') === '2px' ? 'selected' : '' ?>>2px — Afiado / Alta Geometria</option>
                </select>
            </div>
        </div>

        <!-- PRÉ-VISUALIZADOR EM TEMPO REAL — IDENTIDADE VISUAL DIVINIUM -->
        <div style="margin-top: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
                <span style="font-size: 0.78rem; text-transform: uppercase; color: #cbd0d6; font-weight: 700; letter-spacing: 0.5px;">
                    ✦ Pré-visualização em Tempo Real — Identidade Visual DIVINIUM (Antes de Aplicar):
                </span>
                <span id="paletteSaveFeedback" style="font-size: 0.78rem; color: #9e978e;">
                    Altere qualquer cor acima para visualizar imediatamente o resultado.
                </span>
            </div>

            <!-- Fundo Principal (#FFFFFF) -->
            <div id="customPalettePreviewBox" style="background: #FFFFFF; border: 1px solid #D9DDE1; border-radius: 12px; padding: 20px; display: flex; flex-direction: column; gap: 16px; transition: all 0.2s ease;">

                <!-- Fundo Secundário (#F7F7F7) + Marca Principal (#171717) + Ícones (#777777) + Links (#555555) -->
                <div id="previewSecondaryBar" style="background: #F7F7F7; border: 1px solid #D9DDE1; border-radius: 8px; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span id="previewDecorIcon" style="font-size: 1.1rem; color: #A7ADB4;">✦</span>
                        <span id="previewBrandLogo" style="font-family: var(--font-serif); font-size: 1.2rem; font-weight: 700; letter-spacing: 2px; color: #171717;">
                            DIVINIUM
                        </span>
                        <span id="previewAccentBadge" style="font-size: 0.68rem; font-weight: 700; padding: 2px 8px; border-radius: 10px; background: #A7ADB4; color: #171717; text-transform: uppercase;">
                            Destaque
                        </span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <a href="javascript:void(0)" id="previewSampleLink" style="font-size: 0.82rem; color: #555555; text-decoration: underline; font-weight: 600;">Link da Boutique</a>
                        <span id="previewNavIcon" style="display: inline-flex; align-items: center; gap: 8px; color: #777777;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                        </span>
                    </div>
                </div>

                <!-- Fundo das Seções (#F2F2F2) -->
                <div id="previewSectionBox" style="background: #F2F2F2; border: 1px solid #D9DDE1; border-radius: 10px; padding: 18px;">
                    <!-- Fundo dos Cards (#FFFFFF) -->
                    <div id="previewCardSurface" style="background: #FFFFFF; border: 1px solid #D9DDE1; border-radius: 8px; padding: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                        <div style="flex: 1; min-width: 260px;">
                            <span id="previewSerifTitle" style="font-family: var(--font-serif); font-size: 1.35rem; color: #171717; font-weight: 700; display: block; margin-bottom: 6px;">
                                Joalheria Autoral & Biojoias Contemporâneas
                            </span>
                            <span id="previewSampleText" style="font-size: 0.86rem; color: #555555; display: block; line-height: 1.5; margin-bottom: 6px;">
                                Texto principal demonstrando leitura confortável, equilíbrio visual e fidelidade de contraste sobre o fundo dos cards.
                            </span>
                            <hr id="previewDividerLine" style="border: none; border-top: 1px solid #D9DDE1; margin: 10px 0;">
                            <span id="previewSampleMuted" style="font-size: 0.78rem; color: #777777; display: block;">
                                Texto secundário para legendas, especificações técnicas e observações sutis.
                            </span>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 10px; align-items: flex-end;">
                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                <button type="button"
                                        id="previewBtnSampleSecondary"
                                        onmouseenter="applyPreviewBtnHover(this, true)"
                                        onmouseleave="applyPreviewBtnHover(this, false)"
                                        style="background: #FFFFFF; color: #171717; border: 1px solid #D9DDE1; padding: 9px 16px; border-radius: 8px; font-weight: 600; font-size: 0.82rem; cursor: pointer; transition: all 0.2s ease;">
                                    <span>Botão Secundário</span>
                                </button>
                                <button type="button"
                                        id="previewBtnSample"
                                        onmouseenter="applyPreviewBtnHover(this, true)"
                                        onmouseleave="applyPreviewBtnHover(this, false)"
                                        style="background: #171717; color: #FFFFFF; border: 1px solid #171717; padding: 9px 18px; border-radius: 8px; font-weight: 700; font-size: 0.82rem; cursor: pointer; transition: all 0.2s ease;">
                                    <span>Botão Principal</span>
                                </button>
                                <button type="button"
                                        id="previewBtnHoverSample"
                                        style="background: #A7ADB4; color: #171717; border: 1px solid #A7ADB4; padding: 9px 14px; border-radius: 8px; font-weight: 600; font-size: 0.76rem; cursor: default;">
                                    <span>Hover dos Botões</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PRÉ-VISUALIZAÇÃO ESPECÍFICA: HERO BANNER & ABA DA SACOLA -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px; margin-top: 4px;">
                    <!-- PRÉ-VISUALIZAÇÃO: HERO BANNER PRINCIPAL -->
                    <div id="previewHeroBox" style="background: #252525; border: 1px solid #4A5058; border-radius: 10px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between; gap: 14px; transition: all 0.2s ease;">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <div id="previewHeroBadge" style="display: inline-flex; align-items: center; gap: 6px; background: #1E2024; border: 1px solid #4A5058; color: #CBD0D6; padding: 3px 10px; border-radius: 20px; font-size: 0.68rem; font-weight: 600; letter-spacing: 0.5px;">
                                    <span id="previewHeroBadgeSparkle" style="color: #CBD0D6;">✦</span>
                                    <span id="previewHeroBadgeText">HAUTE JOAILLERIE • EDIÇÃO AUTORAL</span>
                                </div>
                                <span style="font-size: 0.7rem; color: #888888; text-transform: uppercase; font-weight: 600;">Hero Banner</span>
                            </div>
                            <h3 id="previewHeroTitle" style="font-family: var(--font-serif); font-size: 1.35rem; color: #FFFFFF; line-height: 1.2; margin: 0 0 8px 0; font-weight: 400;">
                                Joias que Revelam o Luxo Silencioso
                            </h3>
                            <p id="previewHeroSubtitle" style="font-size: 0.8rem; color: #CBD0D6; line-height: 1.45; margin: 0 0 14px 0;">
                                A nobreza do banho de prata entrelaçada à vibração autêntica de gemas minerais raras.
                            </p>
                            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap; margin-bottom: 12px;">
                                <button type="button" id="previewHeroBtn" style="background: #CBD0D6; color: #171717; border: 1px solid #CBD0D6; padding: 7px 14px; border-radius: 20px; font-weight: 700; font-size: 0.78rem; cursor: pointer;">
                                    Explorar Coleção →
                                </button>
                                <button type="button" id="previewHeroSecBtn" style="background: rgba(255,255,255,0.04); color: #F3EFE6; border: 1px solid #F3EFE6; padding: 7px 12px; border-radius: 20px; font-weight: 500; font-size: 0.76rem; cursor: pointer;">
                                    Concierge WhatsApp
                                </button>
                            </div>
                        </div>
                        <div id="previewHeroTrustStrip" style="display: flex; gap: 10px; flex-wrap: wrap; font-size: 0.7rem; color: #CBD0D6; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 8px;">
                            <span>✦ Banho de Prata</span>
                            <span>✦ Gemas 100% Naturais</span>
                            <span>✦ Certificado de Garantia</span>
                        </div>
                    </div>

                    <!-- PRÉ-VISUALIZAÇÃO: ABA DA SACOLA (CART DRAWER) -->
                    <div id="previewCartBox" style="background: #FFFFFF; border: 1px solid #D9DDE1; border-radius: 10px; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s ease;">
                        <div>
                            <!-- Header da Sacola -->
                            <div id="previewCartHeader" style="background: #F7F7F7; border-bottom: 1px solid #D9DDE1; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center;">
                                <span id="previewCartTitle" style="font-family: var(--font-serif); font-size: 1.05rem; font-weight: 700; color: #171717;">
                                    Sua Sacola de Escolhas
                                </span>
                                <span id="previewCartCloseBtn" style="font-size: 1.2rem; color: #171717; cursor: pointer; line-height: 1;">&times;</span>
                            </div>
                            <!-- Corpo da Sacola com Item -->
                            <div id="previewCartBody" style="background: #FFFFFF; padding: 14px 16px;">
                                <div id="previewCartItemRow" style="display: flex; gap: 10px; align-items: center; border-bottom: 1px solid #D9DDE1; padding-bottom: 10px;">
                                    <div style="width: 40px; height: 40px; background: rgba(0,0,0,0.05); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">💎</div>
                                    <div style="flex: 1; min-width: 0;">
                                        <div id="previewCartItemName" style="font-family: var(--font-serif); font-size: 0.92rem; font-weight: 700; color: #171717; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            Bracelete Caminhos Prata
                                        </div>
                                        <div id="previewCartItemDetail" style="font-size: 0.72rem; color: #555555;">
                                            Gema: Quartzo Verde • Qtd: 1
                                        </div>
                                    </div>
                                    <div id="previewCartItemPrice" style="font-weight: 700; font-size: 0.88rem; color: #171717; flex-shrink: 0;">
                                        R$ 489,00
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Footer da Sacola -->
                        <div id="previewCartFooter" style="background: #F7F7F7; border-top: 1px solid #D9DDE1; padding: 12px 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <span id="previewCartSubtotalLabel" style="font-size: 0.82rem; font-weight: 600; color: #555555;">Subtotal:</span>
                                <span id="previewCartSubtotalVal" style="font-size: 0.98rem; font-weight: 700; color: #171717;">R$ 489,00</span>
                            </div>
                            <div id="previewCartPerks" style="font-size: 0.68rem; color: #555555; margin-bottom: 10px; line-height: 1.3;">
                                🔒 Pagamento Seguro via ASAAS • Pix Instantâneo
                            </div>
                            <button type="button" id="previewCartBtn" style="width: 100%; background: #171717; color: #FFFFFF; border: 1px solid #171717; padding: 9px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; cursor: pointer;">
                                Finalizar Compra Segura
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 18px;">
            <button type="button" class="btn-admin-secondary" onclick="resetToThemeDefaults()">
                <span>↺ Restaurar Todas as Cores Iniciais</span>
            </button>
            <button type="submit" id="btnSavePaletteBottom" class="btn-admin-gold">
                <span>Salvar e Aplicar Paleta</span>
            </button>
        </div>
    </form>
</div>
<!-- ==============================================================================
     ESTÚDIO DE PERSONALIZAÇÃO DE FUNDOS & TEXTURAS (11 DESTINOS COM MODOS MUTUAMENTE EXCLUSIVOS)
     ============================================================================== -->
<?php
$bgAreas = [
    'site' => [
        'name' => '🌐 Fundo Global (Todas as Páginas)',
        'short_name' => 'Fundo Global',
        'desc' => 'Fundo padrão que flui por todas as páginas da loja quando não houver personalização específica.',
        'has_cards' => false,
        'preview_badge' => 'VISÃO GLOBAL',
        'preview_title' => 'DIVINIUM HAUTE JOAILLERIE',
        'preview_sub' => 'Banho de Prata & Gemas Minerais Raras'
    ],
    'home' => [
        'name' => '🏠 Página Inicial / Home (/)',
        'short_name' => 'Home',
        'desc' => 'Fundo exclusivo da página inicial e seções de manifesto, vitrines e depoimentos.',
        'has_cards' => true,
        'card_key' => 'home_card_bg',
        'card_label' => 'Cards da Página Inicial',
        'preview_badge' => 'PÁGINA INICIAL',
        'preview_title' => 'Joias que Revelam o Luxo Silencioso',
        'preview_sub' => 'Coleção Permanente em Prata Nobre'
    ],
    'hero' => [
        'name' => '👑 Hero da Home (Banner de Abertura)',
        'short_name' => 'Hero Banner',
        'desc' => 'Primeira dobra visual de alto impacto na abertura da Home.',
        'has_cards' => false,
        'preview_badge' => 'HAUTE JOAILLERIE',
        'preview_title' => 'Joias que Revelam a Alma',
        'preview_sub' => 'Esculturas em Prata e Gemas Nobres'
    ],
    'store' => [
        'name' => '🛍️ Boutique & Catálogo (/loja)',
        'short_name' => 'Boutique',
        'desc' => 'Ambiente da vitrine oficial de joias, catálogo completo e filtros.',
        'has_cards' => true,
        'card_key' => 'store_card_bg',
        'card_label' => 'Cards de Joias da Boutique',
        'preview_badge' => 'VITRINE OFICIAL',
        'preview_title' => 'Catálogo de Joias Autorais',
        'preview_sub' => 'Anéis, Brincos, Colares e Braceletes'
    ],
    'product' => [
        'name' => '💎 Detalhes do Produto (/produto/*)',
        'short_name' => 'Detalhe da Joia',
        'desc' => 'Página de apresentação individual, fotos em alta resolução e botões de compra.',
        'has_cards' => true,
        'card_key' => 'product_card_bg',
        'card_label' => 'Painel da Galeria & Compra',
        'preview_badge' => 'DETALHE DA JOIA',
        'preview_title' => 'Colar Sinfonia Mineral',
        'preview_sub' => 'Banho de Prata • R$ 490,00'
    ],
    'checkout' => [
        'name' => '🔒 Checkout Seguro & Pix (/checkout)',
        'short_name' => 'Checkout Seguro',
        'desc' => 'Ambiente de pagamento, instruções Pix instantâneo e resumo do pedido.',
        'has_cards' => true,
        'card_key' => 'checkout_card_bg',
        'card_label' => 'Caixas de Pagamento & Resumo',
        'preview_badge' => 'PAGAMENTO SEGURO',
        'preview_title' => 'Finalização do Pedido',
        'preview_sub' => 'Pix Instantâneo com 5% OFF'
    ],
    'institutional' => [
        'name' => '📜 Páginas Institucionais & Políticas',
        'short_name' => 'Institucionais',
        'desc' => 'Páginas editoriais: Garantia, Políticas de Privacidade, Termos, Trocas e Devoluções.',
        'has_cards' => true,
        'card_key' => 'institutional_card_bg',
        'card_label' => 'Card do Conteúdo Editorial',
        'preview_badge' => 'INSTITUCIONAL',
        'preview_title' => 'Políticas & Garantia Vitalícia',
        'preview_sub' => 'Transparência e Autenticidade Divinium'
    ],
    'header' => [
        'name' => '✨ Cabeçalho & Menu de Navegação',
        'short_name' => 'Cabeçalho / Topo',
        'desc' => 'Barra superior fixa contendo logotipo, menus suspensos, carrinho e busca.',
        'has_cards' => false,
        'text_key' => 'header_text_color',
        'text_label' => 'Cor dos Textos & Links do Topo',
        'preview_badge' => 'CABEÇALHO',
        'preview_title' => 'DIVINIUM',
        'preview_sub' => 'Boutique • Coleções • Sobre • Carrinho'
    ],
    'footer' => [
        'name' => '🏛️ Rodapé Oficial da Loja',
        'short_name' => 'Rodapé',
        'desc' => 'Base de todas as páginas com links de navegação, certificado de garantia e CNPJ.',
        'has_cards' => false,
        'text_key' => 'footer_text_color',
        'text_label' => 'Cor dos Textos do Rodapé',
        'preview_badge' => 'RODAPÉ',
        'preview_title' => 'DIVINIUM HAUTE JOAILLERIE',
        'preview_sub' => '© 2026 Divinium. Joalheria Autoral.'
    ],
    'top_banner' => [
        'name' => '📢 Barra Superior de Anúncios',
        'short_name' => 'Barra de Anúncios',
        'desc' => 'Faixa promocional no topo máximo do site para frete grátis e avisos.',
        'has_cards' => false,
        'text_key' => 'top_banner_text_color',
        'text_label' => 'Cor do Texto do Anúncio',
        'preview_badge' => 'AVISO NOBRE',
        'preview_title' => 'Frete Cortesia em Compras acima de R$ 350',
        'preview_sub' => 'Embalagem Especial para Presente Incluída'
    ],
    'admin' => [
        'name' => '🛡️ Painel Administrativo',
        'short_name' => 'Painel Admin',
        'desc' => 'Fundo do ambiente de gestão privada e dashboards de controle.',
        'has_cards' => false,
        'preview_badge' => 'PAINEL EXECUTIVO',
        'preview_title' => 'DIVINIUM ADMIN',
        'preview_sub' => 'Métricas • Pedidos • Gestão Visual'
    ]
];

$texturePresets = [
    [
        'id' => 'linen',
        'name' => 'Linho Nobre Claro',
        'url' => '/assets/images/backgrounds/linen-pearl.svg',
        'desc' => 'Trama têxtil sutil e iluminada'
    ],
    [
        'id' => 'marble',
        'name' => 'Mármore Noir & Prata',
        'url' => '/assets/images/backgrounds/marble-noir.svg',
        'desc' => 'Grafite nobre com veios prateados'
    ],
    [
        'id' => 'carbon',
        'name' => 'Carbono Quiet Luxury',
        'url' => '/assets/images/backgrounds/carbon-noir.svg',
        'desc' => 'Geometria acetinada discreta'
    ],
    [
        'id' => 'stardust',
        'name' => 'Poeira Prateada & Diamante',
        'url' => '/assets/images/backgrounds/stardust-noir.svg',
        'desc' => 'Microbrilhos platinados sutis'
    ],
    [
        'id' => 'granite',
        'name' => 'Granito Mineral Fino',
        'url' => '/assets/images/backgrounds/granite-silver.svg',
        'desc' => 'Textura mineral natural clara'
    ]
];
?>

<div class="admin-card" style="border: 1px solid rgba(167, 173, 180, 0.4); padding: 28px; margin-bottom: 30px; background: #14161a; position: relative; overflow: hidden;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 18px; flex-wrap: wrap; gap: 14px;">
        <div>
            <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; font-size: 1.35rem; margin: 0; color: #FFFFFF;">
                <span style="color: #CBD0D6;">🖼️</span>
                Personalização de Imagens de Fundo & Texturas de Todas as Páginas
            </h3>
            <p style="font-size: 0.82rem; color: #9e978e; margin: 4px 0 0;">
                Defina para cada área se deseja Cor Sólida, Imagem de Fundo, Textura Nobre ou Herdar o fundo global. As opções são mutuamente exclusivas e garantem contraste impecável.
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <button type="button" class="btn-admin-gold" onclick="saveBackgroundSettings()">
                <span>Salvar Fundos & Texturas</span>
            </button>
        </div>
    </div>

    <!-- TABS PARA SELEÇÃO DA ÁREA CONFIGURÁVEL -->
    <div style="display: flex; gap: 8px; margin-bottom: 22px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 12px; flex-wrap: wrap;">
        <?php $firstArea = true; foreach ($bgAreas as $aKey => $aData): ?>
            <button type="button" 
                    id="tabBtn_<?= $aKey ?>" 
                    class="<?= $firstArea ? 'btn-admin-gold' : 'btn-admin-cancel' ?>" 
                    style="padding: 8px 14px; font-size: 0.8rem;" 
                    onclick="switchBgTab('<?= $aKey ?>')">
                <span><?= $aData['short_name'] ?></span>
            </button>
        <?php $firstArea = false; endforeach; ?>
    </div>

    <form id="backgroundStudioForm" onsubmit="event.preventDefault(); saveBackgroundSettings();">
        <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">

        <?php $firstArea = true; foreach ($bgAreas as $aKey => $aData): ?>
            <?php
                // Detectar modo atual (com inferência de fallback)
                $curMode = $customSettings["{$aKey}_bg_mode"] ?? '';
                if (empty($curMode)) {
                    if (!empty($customSettings["{$aKey}_bg_image"])) $curMode = 'image';
                    elseif (!empty($customSettings["{$aKey}_bg_texture"])) $curMode = 'texture';
                    elseif (!empty($customSettings["{$aKey}_bg_color"])) $curMode = 'color';
                    else $curMode = ($aKey === 'site') ? 'color' : 'inherit';
                }
                $curColor = $customSettings["{$aKey}_bg_color"] ?? ($aKey === 'site' ? '#F8F8F7' : '');
                $curImage = $customSettings["{$aKey}_bg_image"] ?? '';
                $curOverlay = $customSettings["{$aKey}_bg_overlay"] ?? ($aKey === 'hero' ? '0.60' : '0.75');
                $curRepeat = $customSettings["{$aKey}_bg_repeat"] ?? 'cover';
                $curPos = $customSettings["{$aKey}_bg_position"] ?? 'center center';
                $curTexture = $customSettings["{$aKey}_bg_texture"] ?? '';
                $curTextureOpacity = $customSettings["{$aKey}_bg_texture_opacity"] ?? '0.85';
            ?>

            <!-- PAINEL DA ÁREA: <?= $aKey ?> -->
            <div id="panel_<?= $aKey ?>" class="bg-area-panel" style="<?= $firstArea ? 'display: block;' : 'display: none;' ?>">
                <input type="hidden" name="<?= $aKey ?>_bg_mode" id="mode_<?= $aKey ?>" value="<?= \App\Core\View::e($curMode) ?>">
                <input type="hidden" name="<?= $aKey ?>_bg_texture" id="texture_val_<?= $aKey ?>" value="<?= \App\Core\View::e($curTexture) ?>">

                <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
                    <!-- COLUNA ESQUERDA: CONTROLES MUTUAMENTE EXCLUSIVOS -->
                    <div>
                        <!-- SELETOR DE MODO DO FUNDO (Pills mutuamente exclusivas) -->
                        <div style="background: rgba(0,0,0,0.3); border: 1px solid rgba(167, 173, 180, 0.25); border-radius: 10px; padding: 14px 18px; margin-bottom: 20px;">
                            <span style="font-size: 0.74rem; text-transform: uppercase; color: #CBD0D6; font-weight: 700; display: block; margin-bottom: 10px;">
                                Modo do Fundo para <?= $aData['short_name'] ?>:
                            </span>
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                <button type="button" 
                                        id="mode_btn_<?= $aKey ?>_inherit" 
                                        class="mode-pill-btn <?= $curMode === 'inherit' ? 'active-mode' : '' ?>" 
                                        onclick="setAreaBgMode('<?= $aKey ?>', 'inherit')">
                                    <span><?= ($aKey === 'site') ? '✦ Padrão do Tema' : '✦ Herdar Fundo Global' ?></span>
                                </button>
                                <button type="button" 
                                        id="mode_btn_<?= $aKey ?>_color" 
                                        class="mode-pill-btn <?= $curMode === 'color' ? 'active-mode' : '' ?>" 
                                        onclick="setAreaBgMode('<?= $aKey ?>', 'color')">
                                    <span>🎨 Cor Sólida</span>
                                </button>
                                <button type="button" 
                                        id="mode_btn_<?= $aKey ?>_image" 
                                        class="mode-pill-btn <?= $curMode === 'image' ? 'active-mode' : '' ?>" 
                                        onclick="setAreaBgMode('<?= $aKey ?>', 'image')">
                                    <span>🖼️ Imagem de Fundo</span>
                                </button>
                                <button type="button" 
                                        id="mode_btn_<?= $aKey ?>_texture" 
                                        class="mode-pill-btn <?= $curMode === 'texture' ? 'active-mode' : '' ?>" 
                                        onclick="setAreaBgMode('<?= $aKey ?>', 'texture')">
                                    <span>✨ Textura Nobre</span>
                                </button>
                            </div>
                        </div>

                        <!-- 1. MODO: HERDAR GLOBAL -->
                        <div id="subpanel_<?= $aKey ?>_inherit" style="<?= $curMode === 'inherit' ? 'display: block;' : 'display: none;' ?> background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 20px; margin-bottom: 20px;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                <span style="font-size: 1.2rem; color: #A7ADB4;">✦</span>
                                <h4 style="font-size: 0.95rem; color: #FFFFFF; margin: 0;">Herança Global Ativa</h4>
                            </div>
                            <p style="font-size: 0.8rem; color: #9e978e; margin: 0; line-height: 1.5;">
                                Esta área não possui sobrescrita exclusiva. Ela herda automaticamente o fundo definido no <strong>Fundo Global do Site</strong> (seja ele uma cor sólida, imagem com escurecimento ou textura).
                            </p>
                        </div>

                        <!-- 2. MODO: COR SÓLIDA -->
                        <div id="subpanel_<?= $aKey ?>_color" style="<?= $curMode === 'color' ? 'display: block;' : 'display: none;' ?> background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 20px; margin-bottom: 20px;">
                            <h4 style="font-size: 0.95rem; color: #FFFFFF; margin: 0 0 12px; display: flex; align-items: center; gap: 8px;">
                                <span>🎨</span> Cor Sólida de Fundo
                            </h4>
                            <div class="form-group" style="margin-bottom: 12px;">
                                <label for="input_<?= $aKey ?>_bg_color" style="font-size: 0.78rem;">Tom do Fundo:</label>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <input type="color" id="picker_<?= $aKey ?>_bg_color" 
                                           value="<?= !empty($curColor) ? $curColor : '#F8F8F7' ?>" 
                                           onchange="document.getElementById('input_<?= $aKey ?>_bg_color').value = this.value; updateAreaBgPreview('<?= $aKey ?>');"
                                           style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                                    <input type="text" id="input_<?= $aKey ?>_bg_color" name="<?= $aKey ?>_bg_color" class="form-input" 
                                           value="<?= \App\Core\View::e($curColor) ?>" 
                                           placeholder="Ex: #F8F8F7 ou #FFFFFF"
                                           oninput="if(this.value.length===7) { document.getElementById('picker_<?= $aKey ?>_bg_color').value = this.value; } updateAreaBgPreview('<?= $aKey ?>');">
                                    <button type="button" class="btn-admin-secondary" onclick="clearAreaCustomization('<?= $aKey ?>')">
                                        <span>Limpar</span>
                                    </button>
                                </div>
                                <div style="display: flex; gap: 5px; margin-top: 8px; align-items: center;">
                                    <span style="font-size: 0.65rem; color: #888; text-transform: uppercase;">Tons Rápidos:</span>
                                    <button type="button" onclick="setAreaColor('<?= $aKey ?>', '#F8F8F7')" title="Branco Perolado (#F8F8F7)" style="width: 18px; height: 18px; border-radius: 50%; background: #F8F8F7; border: 1px solid #ccc; cursor: pointer;"></button>
                                    <button type="button" onclick="setAreaColor('<?= $aKey ?>', '#F0F0EF')" title="Cinza Claro (#F0F0EF)" style="width: 18px; height: 18px; border-radius: 50%; background: #F0F0EF; border: 1px solid #ccc; cursor: pointer;"></button>
                                    <button type="button" onclick="setAreaColor('<?= $aKey ?>', '#FFFFFF')" title="Branco Puro (#FFFFFF)" style="width: 18px; height: 18px; border-radius: 50%; background: #FFFFFF; border: 1px solid #ccc; cursor: pointer;"></button>
                                    <button type="button" onclick="setAreaColor('<?= $aKey ?>', '#CBD0D6')" title="Platina (#CBD0D6)" style="width: 18px; height: 18px; border-radius: 50%; background: #CBD0D6; border: 1px solid #999; cursor: pointer;"></button>
                                    <button type="button" onclick="setAreaColor('<?= $aKey ?>', '#252525')" title="Preto Grafite (#252525)" style="width: 18px; height: 18px; border-radius: 50%; background: #252525; border: 1px solid #555; cursor: pointer;"></button>
                                </div>
                            </div>

                            <?php if (!empty($aData['has_cards'])): ?>
                                <div class="form-group" style="margin-top: 14px; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 12px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                        <label for="input_<?= $aData['card_key'] ?>" style="font-size: 0.78rem; margin: 0;"><?= $aData['card_label'] ?>:</label>
                                        <button type="button" onclick="document.getElementById('input_<?= $aData['card_key'] ?>').value = ''; updateAreaBgPreview('<?= $aKey ?>');" style="background: none; border: none; color: #888; font-size: 0.7rem; cursor: pointer;">✕ Limpar</button>
                                    </div>
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        <input type="color" id="picker_<?= $aData['card_key'] ?>" 
                                               value="<?= !empty($customSettings[$aData['card_key']]) ? $customSettings[$aData['card_key']] : '#FFFFFF' ?>" 
                                               onchange="document.getElementById('input_<?= $aData['card_key'] ?>').value = this.value; updateAreaBgPreview('<?= $aKey ?>');"
                                               style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                                        <input type="text" id="input_<?= $aData['card_key'] ?>" name="<?= $aData['card_key'] ?>" class="form-input" 
                                               value="<?= \App\Core\View::e($customSettings[$aData['card_key']] ?? '') ?>" 
                                               placeholder="Padrão: herdado do global (#FFFFFF)"
                                               oninput="if(this.value.length===7) { document.getElementById('picker_<?= $aData['card_key'] ?>').value = this.value; } updateAreaBgPreview('<?= $aKey ?>');">
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($aData['text_key'])): ?>
                                <div class="form-group" style="margin-top: 14px; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 12px;">
                                    <label for="input_<?= $aData['text_key'] ?>" style="font-size: 0.78rem;"><?= $aData['text_label'] ?>:</label>
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        <input type="color" id="picker_<?= $aData['text_key'] ?>" 
                                               value="<?= !empty($customSettings[$aData['text_key']]) ? $customSettings[$aData['text_key']] : '#FFFFFF' ?>" 
                                               onchange="document.getElementById('input_<?= $aData['text_key'] ?>').value = this.value; updateAreaBgPreview('<?= $aKey ?>');"
                                               style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                                        <input type="text" id="input_<?= $aData['text_key'] ?>" name="<?= $aData['text_key'] ?>" class="form-input" 
                                               value="<?= \App\Core\View::e($customSettings[$aData['text_key']] ?? '') ?>" 
                                               placeholder="Padrão da identidade"
                                               oninput="if(this.value.length===7) { document.getElementById('picker_<?= $aData['text_key'] ?>').value = this.value; } updateAreaBgPreview('<?= $aKey ?>');">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- 3. MODO: IMAGEM DE FUNDO -->
                        <div id="subpanel_<?= $aKey ?>_image" style="<?= $curMode === 'image' ? 'display: block;' : 'display: none;' ?> background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 20px; margin-bottom: 20px;">
                            <!-- Upload do Computador -->
                            <div style="border: 2px dashed rgba(167, 173, 180, 0.35); border-radius: 10px; padding: 18px; text-align: center; margin-bottom: 16px;">
                                <div style="font-size: 1.8rem; margin-bottom: 4px;">📁</div>
                                <h4 style="font-size: 0.9rem; color: #fff; margin: 0 0 2px;">Enviar Imagem do Dispositivo</h4>
                                <p style="font-size: 0.74rem; color: #9e978e; margin: 0 0 12px;">Formatos permitidos: JPG, PNG, WEBP, SVG</p>
                                <label class="btn-admin-gold" style="cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; font-size: 0.8rem;">
                                    <span>Escolher Arquivo</span>
                                    <input type="file" id="upload_file_<?= $aKey ?>" accept="image/*" style="display: none;" onchange="uploadBgFile(this, '<?= $aKey ?>_bg_image', '<?= $aKey ?>')">
                                </label>
                                <div id="upload_status_<?= $aKey ?>" style="font-size: 0.78rem; color: #CBD0D6; margin-top: 8px; display: none;"></div>
                            </div>

                            <!-- URL Direta da Imagem -->
                            <div class="form-group" style="margin-bottom: 14px;">
                                <label for="input_<?= $aKey ?>_bg_image" style="font-size: 0.78rem;">Ou URL / Caminho da Imagem:</label>
                                <div style="display: flex; gap: 8px;">
                                    <input type="text" id="input_<?= $aKey ?>_bg_image" name="<?= $aKey ?>_bg_image" class="form-input" 
                                           value="<?= \App\Core\View::e($curImage) ?>" 
                                           placeholder="Ex: /assets/images/backgrounds/marble-noir.svg" 
                                           oninput="updateAreaBgPreview('<?= $aKey ?>')">
                                    <button type="button" class="btn-admin-secondary" onclick="document.getElementById('input_<?= $aKey ?>_bg_image').value = ''; updateAreaBgPreview('<?= $aKey ?>');">
                                        <span>Limpar</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Controles de Exibição, Posição & Overlay -->
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 14px; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 14px;">
                                <div class="form-group" style="margin: 0;">
                                    <label for="select_<?= $aKey ?>_bg_repeat" style="font-size: 0.76rem;">Enquadramento:</label>
                                    <select id="select_<?= $aKey ?>_bg_repeat" name="<?= $aKey ?>_bg_repeat" class="form-select" onchange="updateAreaBgPreview('<?= $aKey ?>')">
                                        <option value="cover" <?= $curRepeat === 'cover' ? 'selected' : '' ?>>Cobrir Tela (Cover)</option>
                                        <option value="contain" <?= $curRepeat === 'contain' ? 'selected' : '' ?>>Conter Inteira (Contain)</option>
                                        <option value="repeat" <?= $curRepeat === 'repeat' ? 'selected' : '' ?>>Repetir Padrão (Repeat)</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin: 0;">
                                    <label for="select_<?= $aKey ?>_bg_position" style="font-size: 0.76rem;">Posição:</label>
                                    <select id="select_<?= $aKey ?>_bg_position" name="<?= $aKey ?>_bg_position" class="form-select" onchange="updateAreaBgPreview('<?= $aKey ?>')">
                                        <option value="center center" <?= $curPos === 'center center' ? 'selected' : '' ?>>Centro</option>
                                        <option value="top center" <?= $curPos === 'top center' ? 'selected' : '' ?>>Topo</option>
                                        <option value="bottom center" <?= $curPos === 'bottom center' ? 'selected' : '' ?>>Base</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin: 0;">
                                    <label for="range_<?= $aKey ?>_bg_overlay" style="font-size: 0.76rem;">
                                        Contraste: <strong id="overlay_label_<?= $aKey ?>"><?= round(((float)$curOverlay) * 100) ?>%</strong>
                                    </label>
                                    <input type="range" id="range_<?= $aKey ?>_bg_overlay" name="<?= $aKey ?>_bg_overlay" min="0.00" max="0.95" step="0.05" 
                                           value="<?= \App\Core\View::e($curOverlay) ?>" 
                                           style="width: 100%; accent-color: #737B85;" 
                                           oninput="document.getElementById('overlay_label_<?= $aKey ?>').innerText = Math.round(this.value * 100) + '%'; updateAreaBgPreview('<?= $aKey ?>');">
                                </div>
                            </div>
                        </div>

                        <!-- 4. MODO: TEXTURA NOBRE -->
                        <div id="subpanel_<?= $aKey ?>_texture" style="<?= $curMode === 'texture' ? 'display: block;' : 'display: none;' ?> background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 20px; margin-bottom: 20px;">
                            <h4 style="font-size: 0.95rem; color: #FFFFFF; margin: 0 0 12px; display: flex; align-items: center; gap: 8px;">
                                <span>✨</span> Galeria de Texturas Nobres Autênticas
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 10px; margin-bottom: 14px;">
                                <?php foreach ($texturePresets as $tex): ?>
                                    <button type="button" 
                                            class="bg-preset-btn" 
                                            onclick="applyTexturePreset('<?= $aKey ?>', '<?= $tex['url'] ?>')" 
                                            style="background: #1e1e1e; border: 1px solid <?= ($curTexture === $tex['url']) ? '#CBD0D6' : 'rgba(255,255,255,0.1)' ?>; border-radius: 8px; padding: 8px; cursor: pointer; text-align: left;">
                                        <div style="height: 36px; border-radius: 4px; background: url('<?= $tex['url'] ?>') center/cover; margin-bottom: 4px; border: 1px solid rgba(255,255,255,0.1);"></div>
                                        <span style="font-size: 0.72rem; color: #fff; font-weight: 600; display: block;"><?= $tex['name'] ?></span>
                                    </button>
                                <?php endforeach; ?>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 140px; gap: 12px; align-items: center;">
                                <div class="form-group" style="margin: 0;">
                                    <label for="input_text_tex_<?= $aKey ?>" style="font-size: 0.76rem;">URL da Textura Ativa:</label>
                                    <input type="text" id="input_text_tex_<?= $aKey ?>" class="form-input" 
                                           value="<?= \App\Core\View::e($curTexture) ?>" 
                                           placeholder="Ex: /assets/images/backgrounds/linen-pearl.svg" 
                                           oninput="document.getElementById('texture_val_<?= $aKey ?>').value = this.value; updateAreaBgPreview('<?= $aKey ?>');">
                                </div>
                                <div class="form-group" style="margin: 0;">
                                    <label for="range_tex_op_<?= $aKey ?>" style="font-size: 0.76rem;">
                                        Intensidade: <strong id="tex_op_label_<?= $aKey ?>"><?= round(((float)$curTextureOpacity) * 100) ?>%</strong>
                                    </label>
                                    <input type="range" id="range_tex_op_<?= $aKey ?>" name="<?= $aKey ?>_bg_texture_opacity" min="0.10" max="1.00" step="0.05" 
                                           value="<?= \App\Core\View::e($curTextureOpacity) ?>" 
                                           style="width: 100%; accent-color: #737B85;" 
                                           oninput="document.getElementById('tex_op_label_<?= $aKey ?>').innerText = Math.round(this.value * 100) + '%'; updateAreaBgPreview('<?= $aKey ?>');">
                                </div>
                            </div>
                        </div>

                        <!-- BOTÃO DE LIMPEZA E RESTAURAÇÃO DA ÁREA -->
                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 14px;">
                            <button type="button" class="btn-admin-cancel" onclick="clearAreaCustomization('<?= $aKey ?>')">
                                <span>✕ Limpar Personalização (Voltar a Herdar)</span>
                            </button>
                            <span style="font-size: 0.72rem; color: #888;">Configurações salvas apenas para <?= $aData['short_name'] ?></span>
                        </div>
                    </div>

                    <!-- COLUNA DIREITA: PRÉ-VISUALIZADOR AO VIVO DA ÁREA -->
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <label style="font-size: 0.8rem; font-weight: 700; color: #ded7cf; margin: 0;">Prévia em Tempo Real:</label>
                            <span id="preview_mode_badge_<?= $aKey ?>" style="font-size: 0.65rem; color: #CBD0D6; background: rgba(255,255,255,0.08); padding: 2px 6px; border-radius: 4px;">
                                Modo: <?= strtoupper($curMode) ?>
                            </span>
                        </div>

                        <div id="previewCard_<?= $aKey ?>" style="height: 380px; border-radius: 14px; border: 1px solid rgba(167, 173, 180, 0.4); position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; padding: 22px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                            <!-- Camada de Overlay de Escurecimento -->
                            <div id="previewOverlay_<?= $aKey ?>" style="position: absolute; inset: 0; background: rgba(26,26,26,<?= (float)$curOverlay ?>); z-index: 1;"></div>

                            <!-- Conteúdo de Primeiro Plano para Avaliação de Legibilidade -->
                            <div style="position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-family: var(--font-serif); font-size: 1.15rem; color: #fff; letter-spacing: 1px; font-weight: 700;">
                                    DIVINIUM
                                </span>
                                <span style="font-size: 0.68rem; background: rgba(167, 173, 180, 0.25); color: #CBD0D6; border: 1px solid rgba(167, 173, 180, 0.4); padding: 2px 8px; border-radius: 12px; font-weight: 600;">
                                    <?= $aData['preview_badge'] ?>
                                </span>
                            </div>

                            <div style="position: relative; z-index: 2; text-align: center; margin: auto 0;">
                                <h4 style="font-family: var(--font-serif); font-size: 1.25rem; color: #fff; margin: 0 0 6px;">
                                    <?= $aData['preview_title'] ?>
                                </h4>
                                <span style="font-size: 0.8rem; color: #CBD0D6; display: block; margin-bottom: 12px;">
                                    <?= $aData['preview_sub'] ?>
                                </span>
                                <div style="display: inline-block; background: rgba(255,255,255,0.08); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.18); border-radius: 8px; padding: 8px 16px;">
                                    <span style="font-size: 0.74rem; color: #ded7cf; font-weight: 600;">Contraste Acessível WCAG AA</span>
                                </div>
                            </div>

                            <div style="position: relative; z-index: 2; text-align: center;">
                                <span style="font-size: 0.7rem; color: #9e978e;">Visualização instantânea com opacidade e enquadramento</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php $firstArea = false; endforeach; ?>
    </form>
</div>

<!-- ==============================================================================
     INFORMAÇÕES DE IMPLEMENTAÇÃO E ARQUITETURA
     ============================================================================== -->
<div class="admin-card" style="border: 1px solid rgba(255, 255, 255, 0.08); padding: 24px; margin-bottom: 30px;">
    <h3 class="card-title" style="margin-bottom: 12px; display: flex; align-items: center; gap: 8px; color: #FFFFFF;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
        Como funciona a Hierarquia & Independência Visual na Divinium?
    </h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; font-size: 0.84rem; color: #b8b1a7; line-height: 1.5;">
        <div>
            <strong style="color: #fff; display: block; margin-bottom: 4px;">1. Desacoplamento Total de Elementos:</strong>
            Mudar o fundo do site ou de uma página altera exclusivamente o plano de fundo. Os títulos (--title-color), subtítulos (--subtitle-color) e ícones (--icon-nav-color e --icon-decor-color) mantêm suas próprias variáveis independentes, eliminando qualquer perda de contraste.
        </div>
        <div>
            <strong style="color: #fff; display: block; margin-bottom: 4px;">2. Modos Mutuamente Exclusivos:</strong>
            Para cada página, você escolhe se o fundo será uma cor sólida, uma fotografia ou uma textura nobre. Ao selecionar um modo, ele anula os outros para evitar conflitos de sobreposição ou regras concorrentes.
        </div>
        <div>
            <strong style="color: #fff; display: block; margin-bottom: 4px;">3. Herança Inteligente:</strong>
            Se você limpar a personalização de uma página específica, ela volta a herdar o fundo global instantaneamente. Suas fotos e joias do catálogo nunca são modificadas.
        </div>
    </div>
</div>

<script>
// ==============================================================================
// IDENTIDADE VISUAL DIVINIUM — GESTÃO DA PALETA ÚNICA EDITÁVEL
// ==============================================================================
const DIVINIUM_INITIAL_PALETTE = {
    custom_color_bg:                 { picker: 'picker_bg',                 initial: '#FFFFFF' },
    custom_color_bg_alt:             { picker: 'picker_bg_alt',             initial: '#F7F7F7' },
    custom_color_surface:            { picker: 'picker_surface',            initial: '#FFFFFF' },
    custom_color_section_bg:         { picker: 'picker_section_bg',         initial: '#F2F2F2' },
    custom_color_title:              { picker: 'picker_title',              initial: '#171717' },
    custom_color_text:               { picker: 'picker_text',               initial: '#555555' },
    custom_color_text_muted:         { picker: 'picker_text_muted',         initial: '#777777' },
    custom_color_primary:            { picker: 'picker_primary',            initial: '#171717' },
    custom_color_accent:             { picker: 'picker_accent',             initial: '#A7ADB4' },
    custom_color_btn_primary_bg:     { picker: 'picker_btn_primary_bg',     initial: '#171717' },
    custom_color_btn_primary_text:   { picker: 'picker_btn_primary_text',   initial: '#FFFFFF' },
    custom_color_btn_secondary_bg:   { picker: 'picker_btn_secondary_bg',   initial: '#FFFFFF' },
    custom_color_btn_secondary_text: { picker: 'picker_btn_secondary_text', initial: '#171717' },
    custom_color_border:             { picker: 'picker_border',             initial: '#D9DDE1' },
    custom_color_icon_nav:           { picker: 'picker_icon_nav',           initial: '#777777' },
    custom_color_link:               { picker: 'picker_link',               initial: '#555555' },
    custom_color_btn_hover:          { picker: 'picker_btn_hover',          initial: '#A7ADB4' },

    // Hero Banner Principal
    custom_color_hero_bg:            { picker: 'picker_hero_bg',            initial: '#252525' },
    custom_color_hero_title:         { picker: 'picker_hero_title',         initial: '#FFFFFF' },
    custom_color_hero_subtitle:      { picker: 'picker_hero_subtitle',      initial: '#CBD0D6' },
    custom_color_hero_badge_bg:      { picker: 'picker_hero_badge_bg',      initial: '#1E2024' },
    custom_color_hero_badge_text:    { picker: 'picker_hero_badge_text',    initial: '#CBD0D6' },
    custom_color_hero_badge_border:  { picker: 'picker_hero_badge_border',  initial: '#4A5058' },
    custom_color_hero_btn_bg:        { picker: 'picker_hero_btn_bg',        initial: '#CBD0D6' },
    custom_color_hero_btn_text:      { picker: 'picker_hero_btn_text',      initial: '#171717' },
    custom_color_hero_btn_sec_text:  { picker: 'picker_hero_btn_sec_text',  initial: '#F3EFE6' },
    custom_color_hero_trust_text:    { picker: 'picker_hero_trust_text',    initial: '#CBD0D6' },

    // Aba da Sacola (Cart Drawer)
    custom_color_cart_bg:            { picker: 'picker_cart_bg',            initial: '#FFFFFF' },
    custom_color_cart_footer_bg:     { picker: 'picker_cart_footer_bg',     initial: '#F7F7F7' },
    custom_color_cart_title:         { picker: 'picker_cart_title',         initial: '#171717' },
    custom_color_cart_item_title:    { picker: 'picker_cart_item_title',    initial: '#171717' },
    custom_color_cart_text:          { picker: 'picker_cart_text',          initial: '#555555' },
    custom_color_cart_price:         { picker: 'picker_cart_price',         initial: '#171717' },
    custom_color_cart_border:        { picker: 'picker_cart_border',        initial: '#D9DDE1' },
    custom_color_cart_btn_bg:        { picker: 'picker_cart_btn_bg',        initial: '#171717' },
    custom_color_cart_btn_text:      { picker: 'picker_cart_btn_text',      initial: '#FFFFFF' }
};

function syncFromColorPicker(inputId, hexVal) {
    const input = document.getElementById(inputId);
    if (input) {
        input.value = String(hexVal || '').toUpperCase();
    }
    updateCustomPreview();
}

function syncFromHexField(inputEl, pickerId) {
    if (!inputEl) return;
    let val = inputEl.value.trim().toUpperCase();
    if (val && !val.startsWith('#')) {
        val = '#' + val;
    }
    if (/^#[0-9A-F]{6}$/.test(val)) {
        const picker = document.getElementById(pickerId);
        if (picker) picker.value = val;
        updateCustomPreview();
    }
}

function normalizeHexOnBlur(inputEl, pickerId, fallbackHex) {
    if (!inputEl) return;
    let val = inputEl.value.trim().toUpperCase();
    if (val && !val.startsWith('#')) {
        val = '#' + val;
    }
    if (/^#[0-9A-F]{3}$/.test(val)) {
        val = '#' + val[1] + val[1] + val[2] + val[2] + val[3] + val[3];
    }
    if (!/^#[0-9A-F]{6}$/.test(val)) {
        val = fallbackHex.toUpperCase();
    }
    inputEl.value = val;
    const picker = document.getElementById(pickerId);
    if (picker) picker.value = val;
    updateCustomPreview();
}

function setQuickColor(inputId, pickerId, val) {
    const clean = String(val || '').toUpperCase();
    const input = document.getElementById(inputId);
    const picker = document.getElementById(pickerId);
    if (input) input.value = clean;
    if (picker) picker.value = clean;
    updateCustomPreview();
}

function clearColorField(inputId, pickerId, defaultVal) {
    setQuickColor(inputId, pickerId, defaultVal);
}

function getContrastColor(hex) {
    if (!hex || hex.length < 7) return '#171717';
    const r = parseInt(hex.substr(1, 2), 16);
    const g = parseInt(hex.substr(3, 2), 16);
    const b = parseInt(hex.substr(5, 2), 16);
    const yiq = (r * 299 + g * 587 + b * 114) / 1000;
    return yiq >= 140 ? '#171717' : '#FFFFFF';
}

function applyPreviewBtnHover(btnEl, isHover) {
    if (!btnEl) return;
    const hoverColor = document.getElementById('custom_color_btn_hover')?.value || '#A7ADB4';
    if (isHover) {
        btnEl.style.backgroundColor = hoverColor;
        btnEl.style.borderColor = hoverColor;
        btnEl.style.color = getContrastColor(hoverColor);
    } else {
        updateCustomPreview();
    }
}

function updateCustomPreview() {
    const bgMain          = document.getElementById('custom_color_bg')?.value || '#FFFFFF';
    const bgAlt           = document.getElementById('custom_color_bg_alt')?.value || '#F7F7F7';
    const surfaceCard     = document.getElementById('custom_color_surface')?.value || '#FFFFFF';
    const sectionBg       = document.getElementById('custom_color_section_bg')?.value || '#F2F2F2';
    const titleColor      = document.getElementById('custom_color_title')?.value || '#171717';
    const textMain        = document.getElementById('custom_color_text')?.value || '#555555';
    const textMuted       = document.getElementById('custom_color_text_muted')?.value || '#777777';
    const brandPrimary    = document.getElementById('custom_color_primary')?.value || '#171717';
    const accentColor     = document.getElementById('custom_color_accent')?.value || '#A7ADB4';
    const btnPrimaryBg    = document.getElementById('custom_color_btn_primary_bg')?.value || '#171717';
    const btnPrimaryText  = document.getElementById('custom_color_btn_primary_text')?.value || '#FFFFFF';
    const btnSecondaryBg  = document.getElementById('custom_color_btn_secondary_bg')?.value || '#FFFFFF';
    const btnSecondaryTxt = document.getElementById('custom_color_btn_secondary_text')?.value || '#171717';
    const borderColor     = document.getElementById('custom_color_border')?.value || '#D9DDE1';
    const iconColor       = document.getElementById('custom_color_icon_nav')?.value || '#777777';
    const linkColor       = document.getElementById('custom_color_link')?.value || '#555555';
    const btnHoverColor   = document.getElementById('custom_color_btn_hover')?.value || '#A7ADB4';

    const fontSerif = document.getElementById('custom_font_serif')?.value || "'Cormorant Garamond', Georgia, serif";
    const radius    = document.getElementById('custom_border_radius')?.value || '8px';

    const box = document.getElementById('customPalettePreviewBox');
    if (box) {
        box.style.backgroundColor = bgMain;
        box.style.borderColor = borderColor;
    }

    const secBar = document.getElementById('previewSecondaryBar');
    if (secBar) {
        secBar.style.backgroundColor = bgAlt;
        secBar.style.borderColor = borderColor;
    }

    const secBox = document.getElementById('previewSectionBox');
    if (secBox) {
        secBox.style.backgroundColor = sectionBg;
        secBox.style.borderColor = borderColor;
    }

    const cardSurface = document.getElementById('previewCardSurface');
    if (cardSurface) {
        cardSurface.style.backgroundColor = surfaceCard;
        cardSurface.style.borderColor = borderColor;
    }

    const divider = document.getElementById('previewDividerLine');
    if (divider) {
        divider.style.borderTopColor = borderColor;
    }

    const brandLogo = document.getElementById('previewBrandLogo');
    if (brandLogo) {
        brandLogo.style.color = brandPrimary;
        brandLogo.style.fontFamily = fontSerif;
    }

    const decorEl = document.getElementById('previewDecorIcon');
    if (decorEl) decorEl.style.color = accentColor;

    const accentBadge = document.getElementById('previewAccentBadge');
    if (accentBadge) {
        accentBadge.style.backgroundColor = accentColor;
        accentBadge.style.color = getContrastColor(accentColor);
    }

    const titleEl = document.getElementById('previewSerifTitle');
    if (titleEl) {
        titleEl.style.color = titleColor;
        titleEl.style.fontFamily = fontSerif;
    }

    const sampleText = document.getElementById('previewSampleText');
    if (sampleText) sampleText.style.color = textMain;

    const sampleMuted = document.getElementById('previewSampleMuted');
    if (sampleMuted) sampleMuted.style.color = textMuted;

    const navEl = document.getElementById('previewNavIcon');
    if (navEl) navEl.style.color = iconColor;

    const linkEl = document.getElementById('previewSampleLink');
    if (linkEl) linkEl.style.color = linkColor;

    const btn = document.getElementById('previewBtnSample');
    if (btn) {
        btn.style.backgroundColor = btnPrimaryBg;
        btn.style.color = btnPrimaryText;
        btn.style.borderColor = btnPrimaryBg;
        btn.style.borderRadius = radius;
    }

    const btnSec = document.getElementById('previewBtnSampleSecondary');
    if (btnSec) {
        btnSec.style.backgroundColor = btnSecondaryBg;
        btnSec.style.color = btnSecondaryTxt;
        btnSec.style.borderColor = borderColor;
        btnSec.style.borderRadius = radius;
    }

    const btnHover = document.getElementById('previewBtnHoverSample');
    if (btnHover) {
        btnHover.style.backgroundColor = btnHoverColor;
        btnHover.style.borderColor = btnHoverColor;
        btnHover.style.color = getContrastColor(btnHoverColor);
        btnHover.style.borderRadius = radius;
    }
}
    // Atualização em Tempo Real — Hero Banner
    const heroBg        = document.getElementById('custom_color_hero_bg')?.value || '#252525';
    const heroTitle     = document.getElementById('custom_color_hero_title')?.value || '#FFFFFF';
    const heroSub       = document.getElementById('custom_color_hero_subtitle')?.value || '#CBD0D6';
    const heroBadgeBg   = document.getElementById('custom_color_hero_badge_bg')?.value || '#1E2024';
    const heroBadgeTxt  = document.getElementById('custom_color_hero_badge_text')?.value || '#CBD0D6';
    const heroBadgeBdr  = document.getElementById('custom_color_hero_badge_border')?.value || '#4A5058';
    const heroBtnBg     = document.getElementById('custom_color_hero_btn_bg')?.value || '#CBD0D6';
    const heroBtnTxt    = document.getElementById('custom_color_hero_btn_text')?.value || '#171717';
    const heroBtnSecTxt = document.getElementById('custom_color_hero_btn_sec_text')?.value || '#F3EFE6';
    const heroTrustTxt  = document.getElementById('custom_color_hero_trust_text')?.value || '#CBD0D6';

    const pHeroBox = document.getElementById('previewHeroBox');
    if (pHeroBox) {
        pHeroBox.style.backgroundColor = heroBg;
        pHeroBox.style.borderColor = heroBadgeBdr;
    }
    const pHeroBadge = document.getElementById('previewHeroBadge');
    if (pHeroBadge) {
        pHeroBadge.style.backgroundColor = heroBadgeBg;
        pHeroBadge.style.borderColor = heroBadgeBdr;
        pHeroBadge.style.color = heroBadgeTxt;
    }
    const pHeroSparkle = document.getElementById('previewHeroBadgeSparkle');
    if (pHeroSparkle) pHeroSparkle.style.color = heroBadgeTxt;
    const pHeroTitle = document.getElementById('previewHeroTitle');
    if (pHeroTitle) {
        pHeroTitle.style.color = heroTitle;
        pHeroTitle.style.fontFamily = fontSerif;
    }
    const pHeroSub = document.getElementById('previewHeroSubtitle');
    if (pHeroSub) pHeroSub.style.color = heroSub;
    const pHeroBtn = document.getElementById('previewHeroBtn');
    if (pHeroBtn) {
        pHeroBtn.style.backgroundColor = heroBtnBg;
        pHeroBtn.style.borderColor = heroBtnBg;
        pHeroBtn.style.color = heroBtnTxt;
        pHeroBtn.style.borderRadius = radius;
    }
    const pHeroSecBtn = document.getElementById('previewHeroSecBtn');
    if (pHeroSecBtn) {
        pHeroSecBtn.style.borderColor = heroBtnSecTxt;
        pHeroSecBtn.style.color = heroBtnSecTxt;
        pHeroSecBtn.style.borderRadius = radius;
    }
    const pHeroTrust = document.getElementById('previewHeroTrustStrip');
    if (pHeroTrust) pHeroTrust.style.color = heroTrustTxt;

    // Atualização em Tempo Real — Aba da Sacola (Cart Drawer)
    const cartBg        = document.getElementById('custom_color_cart_bg')?.value || '#FFFFFF';
    const cartFootBg    = document.getElementById('custom_color_cart_footer_bg')?.value || '#F7F7F7';
    const cartTitle     = document.getElementById('custom_color_cart_title')?.value || '#171717';
    const cartItemTitle = document.getElementById('custom_color_cart_item_title')?.value || '#171717';
    const cartText      = document.getElementById('custom_color_cart_text')?.value || '#555555';
    const cartPrice     = document.getElementById('custom_color_cart_price')?.value || '#171717';
    const cartBorder    = document.getElementById('custom_color_cart_border')?.value || '#D9DDE1';
    const cartBtnBg     = document.getElementById('custom_color_cart_btn_bg')?.value || '#171717';
    const cartBtnTxt    = document.getElementById('custom_color_cart_btn_text')?.value || '#FFFFFF';

    const pCartBox = document.getElementById('previewCartBox');
    if (pCartBox) pCartBox.style.borderColor = cartBorder;
    const pCartHeader = document.getElementById('previewCartHeader');
    if (pCartHeader) {
        pCartHeader.style.backgroundColor = cartFootBg;
        pCartHeader.style.borderBottomColor = cartBorder;
    }
    const pCartTitle = document.getElementById('previewCartTitle');
    if (pCartTitle) {
        pCartTitle.style.color = cartTitle;
        pCartTitle.style.fontFamily = fontSerif;
    }
    const pCartClose = document.getElementById('previewCartCloseBtn');
    if (pCartClose) pCartClose.style.color = cartTitle;
    const pCartBody = document.getElementById('previewCartBody');
    if (pCartBody) pCartBody.style.backgroundColor = cartBg;
    const pCartRow = document.getElementById('previewCartItemRow');
    if (pCartRow) pCartRow.style.borderBottomColor = cartBorder;
    const pCartItemName = document.getElementById('previewCartItemName');
    if (pCartItemName) {
        pCartItemName.style.color = cartItemTitle;
        pCartItemName.style.fontFamily = fontSerif;
    }
    const pCartItemDet = document.getElementById('previewCartItemDetail');
    if (pCartItemDet) pCartItemDet.style.color = cartText;
    const pCartItemPr = document.getElementById('previewCartItemPrice');
    if (pCartItemPr) pCartItemPr.style.color = cartPrice;
    const pCartFooter = document.getElementById('previewCartFooter');
    if (pCartFooter) {
        pCartFooter.style.backgroundColor = cartFootBg;
        pCartFooter.style.borderTopColor = cartBorder;
    }
    const pCartSubLbl = document.getElementById('previewCartSubtotalLabel');
    if (pCartSubLbl) pCartSubLbl.style.color = cartText;
    const pCartSubVal = document.getElementById('previewCartSubtotalVal');
    if (pCartSubVal) pCartSubVal.style.color = cartPrice;
    const pCartPerks = document.getElementById('previewCartPerks');
    if (pCartPerks) pCartPerks.style.color = cartText;
    const pCartBtn = document.getElementById('previewCartBtn');
    if (pCartBtn) {
        pCartBtn.style.backgroundColor = cartBtnBg;
        pCartBtn.style.borderColor = cartBtnBg;
        pCartBtn.style.color = cartBtnTxt;
        pCartBtn.style.borderRadius = radius;
    }


function resetToThemeDefaults() {
    if (!confirm('Deseja restaurar todas as cores da Identidade Visual DIVINIUM aos valores iniciais?')) {
        return;
    }
    Object.keys(DIVINIUM_INITIAL_PALETTE).forEach(key => {
        const cfg = DIVINIUM_INITIAL_PALETTE[key];
        setQuickColor(key, cfg.picker, cfg.initial);
    });
    updateCustomPreview();
}

async function handleCustomThemeSubmit(e) {
    e.preventDefault();
    const form = document.getElementById('customThemeForm');
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';

    const payload = {};
    formData.forEach((v, k) => { payload[k] = v; });

    const btnTop = document.getElementById('btnSavePaletteMain');
    const btnBottom = document.getElementById('btnSavePaletteBottom');
    const feedback = document.getElementById('paletteSaveFeedback');
    if (btnTop) { btnTop.disabled = true; btnTop.innerHTML = '<span>Salvando...</span>'; }
    if (btnBottom) { btnBottom.disabled = true; btnBottom.innerHTML = '<span>Salvando...</span>'; }

    try {
        const res = await fetch('/admin/temas/salvar-custom', {
            method: 'POST',
            body: JSON.stringify(payload),
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const data = await res.json();
        if (data.success) {
            if (feedback) {
                feedback.textContent = '✓ ' + (data.message || 'Paleta Identidade Visual DIVINIUM salva e aplicada com sucesso!');
                feedback.style.color = '#4ade80';
            }
            alert(data.message || 'Paleta Identidade Visual DIVINIUM salva e aplicada com sucesso!');
            window.location.reload();
        } else {
            alert(data.error || 'Erro ao salvar a paleta.');
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
    } finally {
        if (btnTop) { btnTop.disabled = false; btnTop.innerHTML = '<span>Salvar e Aplicar Paleta</span>'; }
        if (btnBottom) { btnBottom.disabled = false; btnBottom.innerHTML = '<span>Salvar e Aplicar Paleta</span>'; }
    }
}
// ==============================================================================
// GESTÃO DE FUNDOS & TEXTURAS (11 ÁREAS COM MODOS MUTUAMENTE EXCLUSIVOS)
// ==============================================================================
let currentActiveArea = 'site';
const allAreaKeys = ['site', 'home', 'hero', 'store', 'product', 'checkout', 'institutional', 'header', 'footer', 'top_banner', 'admin'];

function switchBgTab(areaKey) {
    currentActiveArea = areaKey;
    allAreaKeys.forEach(k => {
        const btn = document.getElementById('tabBtn_' + k);
        const panel = document.getElementById('panel_' + k);
        if (btn) btn.className = (k === areaKey) ? 'btn-admin-gold' : 'btn-admin-cancel';
        if (panel) panel.style.display = (k === areaKey) ? 'block' : 'none';
    });
    updateAreaBgPreview(areaKey);
}

function setAreaBgMode(areaKey, mode) {
    const hiddenMode = document.getElementById('mode_' + areaKey);
    if (hiddenMode) hiddenMode.value = mode;

    // Atualizar classes das pílulas
    const modes = ['inherit', 'color', 'image', 'texture'];
    modes.forEach(m => {
        const btn = document.getElementById('mode_btn_' + areaKey + '_' + m);
        const sub = document.getElementById('subpanel_' + areaKey + '_' + m);
        if (btn) {
            if (m === mode) btn.classList.add('active-mode');
            else btn.classList.remove('active-mode');
        }
        if (sub) {
            sub.style.display = (m === mode) ? 'block' : 'none';
        }
    });

    const badge = document.getElementById('preview_mode_badge_' + areaKey);
    if (badge) badge.innerText = 'Modo: ' + mode.toUpperCase();

    updateAreaBgPreview(areaKey);
}

function setAreaColor(areaKey, hex) {
    const input = document.getElementById('input_' + areaKey + '_bg_color');
    const picker = document.getElementById('picker_' + areaKey + '_bg_color');
    if (input) input.value = hex;
    if (picker) picker.value = hex;
    setAreaBgMode(areaKey, 'color');
}

function applyTexturePreset(areaKey, url) {
    const hiddenTex = document.getElementById('texture_val_' + areaKey);
    const inputTex = document.getElementById('input_text_tex_' + areaKey);
    if (hiddenTex) hiddenTex.value = url;
    if (inputTex) inputTex.value = url;
    setAreaBgMode(areaKey, 'texture');
}

function clearAreaCustomization(areaKey) {
    const inputCol = document.getElementById('input_' + areaKey + '_bg_color');
    const inputImg = document.getElementById('input_' + areaKey + '_bg_image');
    const inputTex = document.getElementById('texture_val_' + areaKey);
    const inputTexText = document.getElementById('input_text_tex_' + areaKey);

    if (inputCol) inputCol.value = '';
    if (inputImg) inputImg.value = '';
    if (inputTex) inputTex.value = '';
    if (inputTexText) inputTexText.value = '';

    setAreaBgMode(areaKey, 'inherit');
}

function updateAreaBgPreview(areaKey) {
    const mode = document.getElementById('mode_' + areaKey)?.value || 'inherit';
    const card = document.getElementById('previewCard_' + areaKey);
    const overlayEl = document.getElementById('previewOverlay_' + areaKey);
    if (!card || !overlayEl) return;

    const overlay = document.getElementById('range_' + areaKey + '_bg_overlay')?.value || (areaKey === 'hero' ? 0.60 : 0.75);
    const repeat = document.getElementById('select_' + areaKey + '_bg_repeat')?.value || 'cover';
    const pos = document.getElementById('select_' + areaKey + '_bg_position')?.value || 'center center';
    const color = document.getElementById('input_' + areaKey + '_bg_color')?.value?.trim() || '';
    const image = document.getElementById('input_' + areaKey + '_bg_image')?.value?.trim() || '';
    const texture = document.getElementById('texture_val_' + areaKey)?.value?.trim() || '';
    const texOpacity = document.getElementById('range_tex_op_' + areaKey)?.value || 0.85;

    // Reset básico
    card.style.backgroundAttachment = 'scroll';
    card.style.backgroundPosition = pos;

    if (mode === 'color') {
        card.style.backgroundImage = 'none';
        card.style.backgroundColor = color || '#F8F8F7';
        overlayEl.style.backgroundColor = 'transparent';
    } else if (mode === 'image') {
        if (image) {
            card.style.backgroundImage = `url('${image}')`;
            card.style.backgroundSize = repeat === 'repeat' ? 'auto' : (repeat === 'contain' ? 'contain' : 'cover');
            card.style.backgroundRepeat = repeat === 'repeat' ? 'repeat' : 'no-repeat';
        } else {
            card.style.backgroundImage = 'none';
            card.style.backgroundColor = '#252525';
        }
        overlayEl.style.backgroundColor = `rgba(26, 26, 26, ${overlay})`;
    } else if (mode === 'texture') {
        if (texture) {
            card.style.backgroundImage = `url('${texture}')`;
            card.style.backgroundSize = 'auto';
            card.style.backgroundRepeat = 'repeat';
            card.style.backgroundColor = color || '#F8F8F7';
        } else {
            card.style.backgroundImage = 'none';
            card.style.backgroundColor = '#F8F8F7';
        }
        overlayEl.style.backgroundColor = `rgba(255, 255, 255, ${1 - texOpacity})`;
    } else {
        // Herdar
        card.style.backgroundImage = 'none';
        card.style.backgroundColor = '#1e1e1e';
        overlayEl.style.backgroundColor = 'rgba(0, 0, 0, 0.4)';
    }
}

async function uploadBgFile(input, targetKey, areaKey) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    const statusEl = document.getElementById('upload_status_' + areaKey);
    if (statusEl) {
        statusEl.style.display = 'block';
        statusEl.textContent = '⏳ Enviando imagem para o servidor...';
    }

    const formData = new FormData();
    formData.append('bg_file', file);
    formData.append('target_key', targetKey);

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
    formData.append('_csrf_token', csrfToken);

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
        const data = await res.json();
        if (data.success && data.url) {
            const inputField = document.getElementById('input_' + areaKey + '_bg_image');
            if (inputField) inputField.value = data.url;
            setAreaBgMode(areaKey, 'image');
            if (statusEl) {
                statusEl.innerHTML = '✓ Imagem enviada! Salva em: <strong>' + data.url + '</strong>';
            }
        } else {
            alert(data.error || 'Erro ao enviar imagem.');
            if (statusEl) statusEl.style.display = 'none';
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
        if (statusEl) statusEl.style.display = 'none';
    }
}

async function saveBackgroundSettings() {
    const form = document.getElementById('backgroundStudioForm');
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';

    const payload = {};
    formData.forEach((v, k) => { payload[k] = v; });

    try {
        const res = await fetch('/admin/temas/salvar-custom', {
            method: 'POST',
            body: JSON.stringify(payload),
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const data = await res.json();
        if (data.success) {
            alert(data.message || 'Configurações de fundo e texturas salvas com sucesso em todas as áreas!');
            window.location.reload();
        } else {
            alert(data.error || 'Erro ao salvar configurações de fundo.');
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    updateCustomPreview();
    allAreaKeys.forEach(k => updateAreaBgPreview(k));
});
</script>

<style>
/* ESTILOS ESPECÍFICOS DO PAINEL DE PERSONALIZAÇÃO */
.mode-pill-btn {
    padding: 7px 14px;
    font-size: 0.78rem;
    font-weight: 600;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #CBD0D6;
    cursor: pointer;
    transition: all 0.2s ease;
}
.mode-pill-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #FFFFFF;
}
.mode-pill-btn.active-mode {
    background: #A7ADB4 !important;
    color: #14161a !important;
    border-color: #CBD0D6 !important;
    font-weight: 700 !important;
    box-shadow: 0 2px 10px rgba(167, 173, 180, 0.3);
}
</style>