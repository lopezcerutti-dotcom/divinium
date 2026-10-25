<?php
/**
 * VIEW: GESTÃO DE TEMAS & IDENTIDADE VISUAL - ADMIN DIVINIUM
 */
?>

<!-- BANNER DE APRESENTAÇÃO DE TEMAS -->
<div class="admin-card" style="background: linear-gradient(135deg, rgba(167, 173, 180, 0.14) 0%, rgba(20, 22, 26, 0.95) 100%); border: 1px solid rgba(167, 173, 180, 0.35); margin-bottom: 28px; padding: 26px 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                <span style="color: #cbd0d6; font-size: 1.3rem;">✦</span>
                <h2 style="font-family: var(--font-serif); font-size: 1.6rem; color: #ffffff; margin: 0; letter-spacing: 0.5px;">Gestão de Temas & Identidade Visual Nobre</h2>
                <span style="background: rgba(167, 173, 180, 0.15); color: #cbd0d6; font-size: 0.7rem; font-weight: 700; padding: 3px 10px; border-radius: 12px; text-transform: uppercase;">Branco, Preto, Prata & Grafite</span>
            </div>
            <p style="font-size: 0.88rem; color: #b8b1a7; margin: 0; max-width: 720px; line-height: 1.5;">
                Defina o tema visual padrão que será carregado automaticamente para todos os visitantes da loja e landing page. Você também pode testar cada tema no seu navegador em tempo real.
            </p>
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <a href="/" target="_blank" class="btn-admin-gold" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <span>Ver Loja Ao Vivo ↗</span>
            </a>
        </div>
    </div>
</div>

<!-- GRID DOS 4 TEMAS LUXUOSOS -->
<div class="themes-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; margin-bottom: 35px;">
    <?php foreach ($themes as $theme): ?>
        <?php $isActive = $theme['is_default']; ?>
        <div class="theme-card <?= $isActive ? 'active-theme' : '' ?>" id="card-<?= $theme['id'] ?>" style="background: #1e1e1e; border: 1px solid <?= $isActive ? $theme['primary_color'] : 'rgba(255, 255, 255, 0.1)' ?>; border-radius: 14px; padding: 26px; display: flex; flex-direction: column; justify-content: space-between; position: relative; transition: all 0.3s ease; box-shadow: <?= $isActive ? '0 12px 30px rgba(0,0,0,0.5), 0 0 25px ' . $theme['primary_color'] . '33' : '0 8px 20px rgba(0,0,0,0.3)' ?>;">
            
            <!-- BADGE SUPERIOR -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <span style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; padding: 4px 10px; border-radius: 8px; background: rgba(255,255,255,0.06); color: <?= $theme['primary_color'] ?>; border: 1px solid <?= $theme['primary_color'] ?>44;">
                    <?= \App\Core\View::e($theme['badge']) ?>
                </span>
                
                <?php if ($isActive): ?>
                    <span class="active-badge" id="badge-<?= $theme['id'] ?>" style="display: inline-flex; align-items: center; gap: 5px; font-size: 0.72rem; font-weight: 700; color: #FFFFFF; background: rgba(167, 173, 180, 0.2); padding: 4px 10px; border-radius: 8px; border: 1px solid #A7ADB4;">
                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #A7ADB4; box-shadow: 0 0 8px #A7ADB4;"></span>
                        Tema Padrão do Site
                    </span>
                <?php else: ?>
                    <span class="active-badge" id="badge-<?= $theme['id'] ?>" style="display: none; align-items: center; gap: 5px; font-size: 0.72rem; font-weight: 700; color: #FFFFFF; background: rgba(167, 173, 180, 0.2); padding: 4px 10px; border-radius: 8px; border: 1px solid #A7ADB4;">
                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #A7ADB4; box-shadow: 0 0 8px #A7ADB4;"></span>
                        Tema Padrão do Site
                    </span>
                <?php endif; ?>
            </div>

            <!-- TÍTULO E SUBTÍTULO -->
            <div>
                <h3 style="font-family: var(--font-serif); font-size: 1.4rem; color: #ffffff; margin: 0 0 4px; display: flex; align-items: center; gap: 8px;">
                    <?= \App\Core\View::e($theme['name']) ?>
                </h3>
                <span style="font-size: 0.78rem; color: <?= $theme['primary_color'] ?>; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 12px;">
                    <?= \App\Core\View::e($theme['subtitle']) ?>
                </span>
                <p style="font-size: 0.82rem; color: #9e978e; line-height: 1.45; margin: 0 0 20px;">
                    <?= \App\Core\View::e($theme['description']) ?>
                </p>
            </div>

            <!-- AMOSTRA DE PALETA DE CORES -->
            <div style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 14px; margin-bottom: 20px;">
                <div style="font-size: 0.7rem; color: #888; text-transform: uppercase; margin-bottom: 8px; font-weight: 600;">Paleta Harmônica:</div>
                <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                    <div title="Paleta Principal: <?= $theme['primary_color'] ?>" style="width: 28px; height: 28px; border-radius: 50%; background: <?= $theme['primary_color'] ?>; border: 2px solid rgba(255,255,255,0.2); box-shadow: 0 0 10px <?= $theme['primary_color'] ?>55;"></div>
                    <div title="Realces / Acentos: <?= $theme['accent_color'] ?>" style="width: 28px; height: 28px; border-radius: 50%; background: <?= $theme['accent_color'] ?>; border: 2px solid rgba(255,255,255,0.2);"></div>
                    <div title="Fundo do Tema: <?= $theme['bg_color'] ?>" style="width: 28px; height: 28px; border-radius: 50%; background: <?= $theme['bg_color'] ?>; border: 2px solid rgba(255,255,255,0.2);"></div>
                    <div title="Superfícies & Cards: <?= $theme['surface_color'] ?? '#FFFFFF' ?>" style="width: 28px; height: 28px; border-radius: 50%; background: <?= $theme['surface_color'] ?? '#FFFFFF' ?>; border: 2px solid rgba(255,255,255,0.2);"></div>
                    <div title="Texto: <?= $theme['text_color'] ?>" style="width: 28px; height: 28px; border-radius: 50%; background: <?= $theme['text_color'] ?>; border: 2px solid rgba(255,255,255,0.2);"></div>
                    <div style="margin-left: auto; font-size: 0.72rem; color: #cbd5e1; font-family: monospace; font-weight: 600;">
                        <?= $theme['primary_color'] ?>
                    </div>
                </div>
            </div>

            <!-- MINI PREVIEW VISUAL -->
            <div style="background: <?= $theme['bg_color'] ?>; border: 1px solid <?= $theme['accent_color'] ?>55; border-radius: 10px; padding: 14px; margin-bottom: 22px; position: relative; overflow: hidden; box-shadow: inset 0 1px 4px rgba(0,0,0,0.06);">
                <div style="font-size: 0.68rem; text-transform: uppercase; color: <?= $theme['accent_color'] ?>; font-weight: 700; margin-bottom: 6px; letter-spacing: 0.5px;">Exemplo de Botão & Tipografia:</div>
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <span style="font-family: var(--font-serif); font-size: 1.15rem; color: <?= $theme['text_color'] ?>; font-weight: 700; letter-spacing: 0.5px;">
                        Divinium Joias
                    </span>
                    <span style="font-size: 0.72rem; font-weight: 700; padding: 6px 14px; border-radius: 20px; background: <?= $theme['primary_color'] ?>; color: <?= $theme['text_color'] ?>; border: 1px solid <?= $theme['accent_color'] ?>; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                        Comprar Joia
                    </span>
                </div>
            </div>

            <!-- BOTÕES DE AÇÃO -->
            <div style="display: flex; gap: 10px; align-items: center;">
                <button type="button" 
                        class="btn-admin-gold" 
                        style="flex: 1; padding: 10px 14px; font-size: 0.8rem; text-align: center; justify-content: center;"
                        onclick="setDefaultTheme('<?= $theme['id'] ?>', this)">
                    <span>Definir Padrão</span>
                </button>
                <button type="button" 
                        class="btn-admin-cancel" 
                        style="padding: 10px 14px; font-size: 0.8rem; border-color: rgba(255,255,255,0.18);"
                        onclick="previewThemeOnPage('<?= $theme['id'] ?>')">
                    <span>Testar Aqui 👁</span>
                </button>
            </div>

        </div>
    <?php endforeach; ?>
</div>


<!-- ESTÚDIO DE PERSONALIZAÇÃO DE CORES INDEPENDENTES & IDENTIDADE VISUAL NOBRE -->
<div class="admin-card" style="border: 1px solid rgba(167, 173, 180, 0.35); padding: 26px; margin-bottom: 30px; background: #16181b;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 16px; flex-wrap: wrap; gap: 14px;">
        <div>
            <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; font-size: 1.35rem; margin: 0; color: #FFFFFF;">
                <span style="color: #A7ADB4;">🎨</span>
                Estúdio de Cores & Elementos Independentes (DIVINIUM)
            </h3>
            <p style="font-size: 0.82rem; color: #9e978e; margin: 4px 0 0;">
                Cada controle afeta estritamente sua função específica. Mudar o fundo não altera textos nem ícones; mudar os botões não recolore títulos nem links.
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <button type="button" class="btn-admin-secondary" onclick="resetToThemeDefaults()">
                <span>Restaurar Padrões</span>
            </button>
            <button type="button" class="btn-admin-gold" onclick="document.getElementById('customThemeForm').requestSubmit()">
                <span>Salvar Cores & Estilos</span>
            </button>
        </div>
    </div>

    <form id="customThemeForm" onsubmit="handleCustomThemeSubmit(event)">
        <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">

        <!-- SEÇÃO 1: FUNDOS & SUPERFÍCIES PRINCIPAIS -->
        <div style="margin-bottom: 16px;">
            <span style="font-size: 0.78rem; text-transform: uppercase; color: #A7ADB4; font-weight: 700; letter-spacing: 0.5px;">✦ 1. Fundos Principais & Superfícies da Loja:</span>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 24px;">
            <!-- Fundo Base Principal -->
            <div class="form-group" style="margin: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <label for="custom_color_bg" style="font-size: 0.78rem; margin: 0;">Fundo Base Principal (--bg-main):</label>
                    <button type="button" onclick="clearColorField('custom_color_bg', 'picker_bg', '#F8F8F7')" title="Restaurar padrão" style="background: none; border: none; color: #888; cursor: pointer; font-size: 0.7rem; padding: 0 4px;">✕ Padrão</button>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_bg" value="<?= \App\Core\View::e($customSettings['custom_color_bg'] ?? '#F8F8F7') ?>" onchange="document.getElementById('custom_color_bg').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_bg" name="custom_color_bg" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_bg'] ?? '#F8F8F7') ?>" oninput="document.getElementById('picker_bg').value = this.value; updateCustomPreview();">
                </div>
                <div style="display: flex; gap: 5px; margin-top: 6px;">
                    <button type="button" onclick="setQuickColor('custom_color_bg', 'picker_bg', '#F8F8F7')" title="Branco Perolado (#F8F8F7)" style="width: 18px; height: 18px; border-radius: 50%; background: #F8F8F7; border: 1px solid #ccc; cursor: pointer;"></button>
                    <button type="button" onclick="setQuickColor('custom_color_bg', 'picker_bg', '#F0F0EF')" title="Cinza Muito Claro (#F0F0EF)" style="width: 18px; height: 18px; border-radius: 50%; background: #F0F0EF; border: 1px solid #ccc; cursor: pointer;"></button>
                    <button type="button" onclick="setQuickColor('custom_color_bg', 'picker_bg', '#FFFFFF')" title="Branco Puro (#FFFFFF)" style="width: 18px; height: 18px; border-radius: 50%; background: #FFFFFF; border: 1px solid #ccc; cursor: pointer;"></button>
                    <button type="button" onclick="setQuickColor('custom_color_bg', 'picker_bg', '#252525')" title="Preto Grafite (#252525)" style="width: 18px; height: 18px; border-radius: 50%; background: #252525; border: 1px solid #555; cursor: pointer;"></button>
                </div>
            </div>

            <!-- Fundo Secundário (Seções Alternadas) -->
            <div class="form-group" style="margin: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <label for="custom_color_bg_alt" style="font-size: 0.78rem; margin: 0;">Fundo Secundário (--bg-alt):</label>
                    <button type="button" onclick="clearColorField('custom_color_bg_alt', 'picker_bg_alt', '#F0F0EF')" title="Restaurar padrão" style="background: none; border: none; color: #888; cursor: pointer; font-size: 0.7rem; padding: 0 4px;">✕ Padrão</button>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_bg_alt" value="<?= \App\Core\View::e($customSettings['custom_color_bg_alt'] ?? '#F0F0EF') ?>" onchange="document.getElementById('custom_color_bg_alt').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_bg_alt" name="custom_color_bg_alt" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_bg_alt'] ?? '#F0F0EF') ?>" oninput="document.getElementById('picker_bg_alt').value = this.value; updateCustomPreview();">
                </div>
                <span style="font-size: 0.65rem; color: #888; display: block; margin-top: 4px;">Seções alternadas (Sobre, Depoimentos, etc).</span>
            </div>

            <!-- Superfície dos Cards -->
            <div class="form-group" style="margin: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <label for="custom_color_surface" style="font-size: 0.78rem; margin: 0;">Superfície dos Cards (--surface-card):</label>
                    <button type="button" onclick="clearColorField('custom_color_surface', 'picker_surface', '#FFFFFF')" title="Restaurar padrão" style="background: none; border: none; color: #888; cursor: pointer; font-size: 0.7rem; padding: 0 4px;">✕ Padrão</button>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_surface" value="<?= \App\Core\View::e($customSettings['custom_color_surface'] ?? '#FFFFFF') ?>" onchange="document.getElementById('custom_color_surface').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_surface" name="custom_color_surface" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_surface'] ?? '#FFFFFF') ?>" oninput="document.getElementById('picker_surface').value = this.value; updateCustomPreview();">
                </div>
                <div style="display: flex; gap: 5px; margin-top: 6px;">
                    <button type="button" onclick="setQuickColor('custom_color_surface', 'picker_surface', '#FFFFFF')" title="Branco Puro (#FFFFFF)" style="width: 18px; height: 18px; border-radius: 50%; background: #FFFFFF; border: 1px solid #ccc; cursor: pointer;"></button>
                    <button type="button" onclick="setQuickColor('custom_color_surface', 'picker_surface', '#F8F8F7')" title="Branco Perolado (#F8F8F7)" style="width: 18px; height: 18px; border-radius: 50%; background: #F8F8F7; border: 1px solid #ccc; cursor: pointer;"></button>
                    <button type="button" onclick="setQuickColor('custom_color_surface', 'picker_surface', '#F0F0EF')" title="Cinza Claro (#F0F0EF)" style="width: 18px; height: 18px; border-radius: 50%; background: #F0F0EF; border: 1px solid #aaa; cursor: pointer;"></button>
                    <button type="button" onclick="setQuickColor('custom_color_surface', 'picker_surface', '#252525')" title="Preto Grafite (#252525)" style="width: 18px; height: 18px; border-radius: 50%; background: #252525; border: 1px solid #555; cursor: pointer;"></button>
                </div>
            </div>

            <!-- Superfície de Caixas e Modais -->
            <div class="form-group" style="margin: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <label for="custom_color_surface_box" style="font-size: 0.78rem; margin: 0;">Caixas & Modais (--surface-box):</label>
                    <button type="button" onclick="clearColorField('custom_color_surface_box', 'picker_surface_box', '#FFFFFF')" title="Restaurar padrão" style="background: none; border: none; color: #888; cursor: pointer; font-size: 0.7rem; padding: 0 4px;">✕ Padrão</button>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_surface_box" value="<?= \App\Core\View::e($customSettings['custom_color_surface_box'] ?? '#FFFFFF') ?>" onchange="document.getElementById('custom_color_surface_box').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_surface_box" name="custom_color_surface_box" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_surface_box'] ?? '#FFFFFF') ?>" oninput="document.getElementById('picker_surface_box').value = this.value; updateCustomPreview();">
                </div>
                <span style="font-size: 0.65rem; color: #888; display: block; margin-top: 4px;">Fundo de gavetas laterais, modais e caixas flutuantes.</span>
            </div>
        </div>

        <!-- SEÇÃO 2: TIPOGRAFIA INDEPENDENTE -->
        <div style="margin-bottom: 16px; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 18px;">
            <span style="font-size: 0.78rem; text-transform: uppercase; color: #A7ADB4; font-weight: 700; letter-spacing: 0.5px;">✦ 2. Tipografia Independente (Títulos, Subtítulos e Textos):</span>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 24px;">
            <!-- Títulos Principais -->
            <div class="form-group" style="margin: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <label for="custom_color_title" style="font-size: 0.78rem; margin: 0;">Títulos Principais (--title-color):</label>
                    <button type="button" onclick="clearColorField('custom_color_title', 'picker_title', '#202020')" title="Restaurar padrão" style="background: none; border: none; color: #888; cursor: pointer; font-size: 0.7rem; padding: 0 4px;">✕ Padrão</button>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_title" value="<?= \App\Core\View::e($customSettings['custom_color_title'] ?? '#202020') ?>" onchange="document.getElementById('custom_color_title').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_title" name="custom_color_title" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_title'] ?? '#202020') ?>" oninput="document.getElementById('picker_title').value = this.value; updateCustomPreview();">
                </div>
                <span style="font-size: 0.65rem; color: #888; display: block; margin-top: 4px;">H1 a H6, títulos de seções e nomes das joias.</span>
            </div>

            <!-- Subtítulos -->
            <div class="form-group" style="margin: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <label for="custom_color_subtitle" style="font-size: 0.78rem; margin: 0;">Subtítulos (--subtitle-color):</label>
                    <button type="button" onclick="clearColorField('custom_color_subtitle', 'picker_subtitle', '#686868')" title="Restaurar padrão" style="background: none; border: none; color: #888; cursor: pointer; font-size: 0.7rem; padding: 0 4px;">✕ Padrão</button>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_subtitle" value="<?= \App\Core\View::e($customSettings['custom_color_subtitle'] ?? '#686868') ?>" onchange="document.getElementById('custom_color_subtitle').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_subtitle" name="custom_color_subtitle" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_subtitle'] ?? '#686868') ?>" oninput="document.getElementById('picker_subtitle').value = this.value; updateCustomPreview();">
                </div>
                <span style="font-size: 0.65rem; color: #888; display: block; margin-top: 4px;">Linhas finas editoriais e subtítulos de apoio.</span>
            </div>

            <!-- Texto Comum / Parágrafos -->
            <div class="form-group" style="margin: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <label for="custom_color_text" style="font-size: 0.78rem; margin: 0;">Texto Normal (--text-body):</label>
                    <button type="button" onclick="clearColorField('custom_color_text', 'picker_text', '#202020')" title="Restaurar padrão" style="background: none; border: none; color: #888; cursor: pointer; font-size: 0.7rem; padding: 0 4px;">✕ Padrão</button>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_text" value="<?= \App\Core\View::e($customSettings['custom_color_text'] ?? '#202020') ?>" onchange="document.getElementById('custom_color_text').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_text" name="custom_color_text" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_text'] ?? '#202020') ?>" oninput="document.getElementById('picker_text').value = this.value; updateCustomPreview();">
                </div>
                <span style="font-size: 0.65rem; color: #888; display: block; margin-top: 4px;">Parágrafos, formulários e leitura geral (Preto Suave #202020).</span>
            </div>

            <!-- Textos Secundários / Muted -->
            <div class="form-group" style="margin: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <label for="custom_color_text_muted" style="font-size: 0.78rem; margin: 0;">Texto Muted (--text-muted):</label>
                    <button type="button" onclick="clearColorField('custom_color_text_muted', 'picker_text_muted', '#686868')" title="Restaurar padrão" style="background: none; border: none; color: #888; cursor: pointer; font-size: 0.7rem; padding: 0 4px;">✕ Padrão</button>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_text_muted" value="<?= \App\Core\View::e($customSettings['custom_color_text_muted'] ?? '#686868') ?>" onchange="document.getElementById('custom_color_text_muted').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_text_muted" name="custom_color_text_muted" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_text_muted'] ?? '#686868') ?>" oninput="document.getElementById('picker_text_muted').value = this.value; updateCustomPreview();">
                </div>
                <span style="font-size: 0.65rem; color: #888; display: block; margin-top: 4px;">Detalhes técnicos, legendas e descrições sutis.</span>
            </div>
        </div>

        <!-- SEÇÃO 3: ÍCONES INDEPENDENTES & LINKS -->
        <div style="margin-bottom: 16px; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 18px;">
            <span style="font-size: 0.78rem; text-transform: uppercase; color: #A7ADB4; font-weight: 700; letter-spacing: 0.5px;">✦ 3. Ícones & Links Independentes (Não sofrem alteração com fundo):</span>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 24px;">
            <!-- Ícones Decorativos -->
            <div class="form-group" style="margin: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <label for="custom_color_icon_decor" style="font-size: 0.78rem; margin: 0;">Ícones Decorativos (--icon-decor-color):</label>
                    <button type="button" onclick="clearColorField('custom_color_icon_decor', 'picker_icon_decor', '#A7ADB4')" title="Restaurar padrão" style="background: none; border: none; color: #888; cursor: pointer; font-size: 0.7rem; padding: 0 4px;">✕ Padrão</button>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_icon_decor" value="<?= \App\Core\View::e($customSettings['custom_color_icon_decor'] ?? '#A7ADB4') ?>" onchange="document.getElementById('custom_color_icon_decor').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_icon_decor" name="custom_color_icon_decor" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_icon_decor'] ?? '#A7ADB4') ?>" oninput="document.getElementById('picker_icon_decor').value = this.value; updateCustomPreview();">
                </div>
                <span style="font-size: 0.65rem; color: #888; display: block; margin-top: 4px;">Estrelas, sparkles, selos de qualidade e detalhes metálicos.</span>
            </div>

            <!-- Ícones Funcionais & Navegação -->
            <div class="form-group" style="margin: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <label for="custom_color_icon_nav" style="font-size: 0.78rem; margin: 0;">Ícones Funcionais (--icon-nav-color):</label>
                    <button type="button" onclick="clearColorField('custom_color_icon_nav', 'picker_icon_nav', '#202020')" title="Restaurar padrão" style="background: none; border: none; color: #888; cursor: pointer; font-size: 0.7rem; padding: 0 4px;">✕ Padrão</button>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_icon_nav" value="<?= \App\Core\View::e($customSettings['custom_color_icon_nav'] ?? '#202020') ?>" onchange="document.getElementById('custom_color_icon_nav').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_icon_nav" name="custom_color_icon_nav" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_icon_nav'] ?? '#202020') ?>" oninput="document.getElementById('picker_icon_nav').value = this.value; updateCustomPreview();">
                </div>
                <span style="font-size: 0.65rem; color: #888; display: block; margin-top: 4px;">Sacola de compras, busca, setas, menu e fechar.</span>
            </div>

            <!-- Links Normais -->
            <div class="form-group" style="margin: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <label for="custom_color_link" style="font-size: 0.78rem; margin: 0;">Cor dos Links (--link-color):</label>
                    <button type="button" onclick="clearColorField('custom_color_link', 'picker_link', '#202020')" title="Restaurar padrão" style="background: none; border: none; color: #888; cursor: pointer; font-size: 0.7rem; padding: 0 4px;">✕ Padrão</button>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_link" value="<?= \App\Core\View::e($customSettings['custom_color_link'] ?? '#202020') ?>" onchange="document.getElementById('custom_color_link').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_link" name="custom_color_link" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_link'] ?? '#202020') ?>" oninput="document.getElementById('picker_link').value = this.value; updateCustomPreview();">
                </div>
                <span style="font-size: 0.65rem; color: #888; display: block; margin-top: 4px;">Links do menu e rodapé em estado normal.</span>
            </div>

            <!-- Hover dos Links -->
            <div class="form-group" style="margin: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <label for="custom_color_link_hover" style="font-size: 0.78rem; margin: 0;">Hover dos Links (--link-hover):</label>
                    <button type="button" onclick="clearColorField('custom_color_link_hover', 'picker_link_hover', '#737B85')" title="Restaurar padrão" style="background: none; border: none; color: #888; cursor: pointer; font-size: 0.7rem; padding: 0 4px;">✕ Padrão</button>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_link_hover" value="<?= \App\Core\View::e($customSettings['custom_color_link_hover'] ?? '#737B85') ?>" onchange="document.getElementById('custom_color_link_hover').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_link_hover" name="custom_color_link_hover" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_link_hover'] ?? '#737B85') ?>" oninput="document.getElementById('picker_link_hover').value = this.value; updateCustomPreview();">
                </div>
                <span style="font-size: 0.65rem; color: #888; display: block; margin-top: 4px;">Tom de realce suave ao passar o mouse.</span>
            </div>
        </div>

        <!-- SEÇÃO 4: BOTÕES INDEPENDENTES (PRIMÁRIOS E SECUNDÁRIOS) -->
        <div style="margin-bottom: 16px; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 18px;">
            <span style="font-size: 0.78rem; text-transform: uppercase; color: #A7ADB4; font-weight: 700; letter-spacing: 0.5px;">✦ 4. Botões de Ação Independentes:</span>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 24px;">
            <!-- Fundo Botão Primário -->
            <div class="form-group" style="margin: 0;">
                <label for="custom_color_btn_primary_bg" style="font-size: 0.78rem;">Fundo Botão Primário (--btn-primary-bg):</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_btn_primary_bg" value="<?= \App\Core\View::e($customSettings['custom_color_btn_primary_bg'] ?? $customSettings['custom_color_primary'] ?? '#A7ADB4') ?>" onchange="document.getElementById('custom_color_btn_primary_bg').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_btn_primary_bg" name="custom_color_btn_primary_bg" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_btn_primary_bg'] ?? $customSettings['custom_color_primary'] ?? '#A7ADB4') ?>" oninput="document.getElementById('picker_btn_primary_bg').value = this.value; updateCustomPreview();">
                </div>
                <div style="display: flex; gap: 5px; margin-top: 6px;">
                    <button type="button" onclick="setQuickColor('custom_color_btn_primary_bg', 'picker_btn_primary_bg', '#A7ADB4')" title="Prata Fosca (#A7ADB4)" style="width: 18px; height: 18px; border-radius: 50%; background: #A7ADB4; border: 1px solid #999; cursor: pointer;"></button>
                    <button type="button" onclick="setQuickColor('custom_color_btn_primary_bg', 'picker_btn_primary_bg', '#CBD0D6')" title="Platina (#CBD0D6)" style="width: 18px; height: 18px; border-radius: 50%; background: #CBD0D6; border: 1px solid #ccc; cursor: pointer;"></button>
                    <button type="button" onclick="setQuickColor('custom_color_btn_primary_bg', 'picker_btn_primary_bg', '#737B85')" title="Cinza Prata (#737B85)" style="width: 18px; height: 18px; border-radius: 50%; background: #737B85; border: 1px solid #555; cursor: pointer;"></button>
                    <button type="button" onclick="setQuickColor('custom_color_btn_primary_bg', 'picker_btn_primary_bg', '#202020')" title="Preto Suave (#202020)" style="width: 18px; height: 18px; border-radius: 50%; background: #202020; border: 1px solid #444; cursor: pointer;"></button>
                </div>
            </div>

            <!-- Texto Botão Primário -->
            <div class="form-group" style="margin: 0;">
                <label for="custom_color_btn_primary_text" style="font-size: 0.78rem;">Texto Botão Primário (--btn-primary-text):</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_btn_primary_text" value="<?= \App\Core\View::e($customSettings['custom_color_btn_primary_text'] ?? '#202020') ?>" onchange="document.getElementById('custom_color_btn_primary_text').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_btn_primary_text" name="custom_color_btn_primary_text" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_btn_primary_text'] ?? '#202020') ?>" oninput="document.getElementById('picker_btn_primary_text').value = this.value; updateCustomPreview();">
                </div>
                <span style="font-size: 0.65rem; color: #888; display: block; margin-top: 4px;">Padrão: Preto #202020 para contraste AA sobre prata.</span>
            </div>

            <!-- Borda Botão Primário -->
            <div class="form-group" style="margin: 0;">
                <label for="custom_color_btn_primary_border" style="font-size: 0.78rem;">Borda Botão Primário (--btn-primary-border):</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_btn_primary_border" value="<?= \App\Core\View::e($customSettings['custom_color_btn_primary_border'] ?? '#737B85') ?>" onchange="document.getElementById('custom_color_btn_primary_border').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_btn_primary_border" name="custom_color_btn_primary_border" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_btn_primary_border'] ?? '#737B85') ?>" oninput="document.getElementById('picker_btn_primary_border').value = this.value; updateCustomPreview();">
                </div>
                <span style="font-size: 0.65rem; color: #888; display: block; margin-top: 4px;">Padrão: Cinza-prata #737B85.</span>
            </div>

            <!-- Fundo Botão Secundário -->
            <div class="form-group" style="margin: 0;">
                <label for="custom_color_btn_secondary_bg" style="font-size: 0.78rem;">Fundo Botão Secundário (--btn-secondary-bg):</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_btn_secondary_bg" value="<?= \App\Core\View::e($customSettings['custom_color_btn_secondary_bg'] ?? '#F0F0EF') ?>" onchange="document.getElementById('custom_color_btn_secondary_bg').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_btn_secondary_bg" name="custom_color_btn_secondary_bg" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_btn_secondary_bg'] ?? '#F0F0EF') ?>" oninput="document.getElementById('picker_btn_secondary_bg').value = this.value; updateCustomPreview();">
                </div>
                <span style="font-size: 0.65rem; color: #888; display: block; margin-top: 4px;">Botões secundários e contornos.</span>
            </div>

            <!-- Texto Botão Secundário -->
            <div class="form-group" style="margin: 0;">
                <label for="custom_color_btn_secondary_text" style="font-size: 0.78rem;">Texto Botão Secundário (--btn-secondary-text):</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_btn_secondary_text" value="<?= \App\Core\View::e($customSettings['custom_color_btn_secondary_text'] ?? '#202020') ?>" onchange="document.getElementById('custom_color_btn_secondary_text').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_btn_secondary_text" name="custom_color_btn_secondary_text" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_btn_secondary_text'] ?? '#202020') ?>" oninput="document.getElementById('picker_btn_secondary_text').value = this.value; updateCustomPreview();">
                </div>
                <span style="font-size: 0.65rem; color: #888; display: block; margin-top: 4px;">Padrão: Preto #202020.</span>
            </div>
        </div>

        <!-- SEÇÃO 5: BORDAS, ESTADOS & MENSAGENS -->
        <div style="margin-bottom: 16px; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 18px;">
            <span style="font-size: 0.78rem; text-transform: uppercase; color: #A7ADB4; font-weight: 700; letter-spacing: 0.5px;">✦ 5. Bordas, Foco & Mensagens de Status (Feedback Funcional Preservado):</span>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 24px;">
            <!-- Borda Principal -->
            <div class="form-group" style="margin: 0;">
                <label for="custom_color_border" style="font-size: 0.78rem;">Bordas Principais (--border-color):</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_border" value="<?= \App\Core\View::e($customSettings['custom_color_border'] ?? '#737B85') ?>" onchange="document.getElementById('custom_color_border').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_border" name="custom_color_border" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_border'] ?? '#737B85') ?>" oninput="document.getElementById('picker_border').value = this.value; updateCustomPreview();">
                </div>
            </div>

            <!-- Anel de Foco / Acessibilidade -->
            <div class="form-group" style="margin: 0;">
                <label for="custom_color_focus_ring" style="font-size: 0.78rem;">Foco & Seleção (--focus-ring):</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_focus_ring" value="<?= \App\Core\View::e($customSettings['custom_color_focus_ring'] ?? '#737B85') ?>" onchange="document.getElementById('custom_color_focus_ring').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_focus_ring" name="custom_color_focus_ring" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_focus_ring'] ?? '#737B85') ?>" oninput="document.getElementById('picker_focus_ring').value = this.value; updateCustomPreview();">
                </div>
            </div>

            <!-- Sucesso -->
            <div class="form-group" style="margin: 0;">
                <label for="custom_color_success" style="font-size: 0.78rem;">Mensagem de Sucesso (--color-success):</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_success" value="<?= \App\Core\View::e($customSettings['custom_color_success'] ?? '#2E7D32') ?>" onchange="document.getElementById('custom_color_success').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_success" name="custom_color_success" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_success'] ?? '#2E7D32') ?>" oninput="document.getElementById('picker_success').value = this.value; updateCustomPreview();">
                </div>
            </div>

            <!-- Alerta -->
            <div class="form-group" style="margin: 0;">
                <label for="custom_color_warning" style="font-size: 0.78rem;">Mensagem de Alerta (--color-warning):</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_warning" value="<?= \App\Core\View::e($customSettings['custom_color_warning'] ?? '#D97706') ?>" onchange="document.getElementById('custom_color_warning').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_warning" name="custom_color_warning" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_warning'] ?? '#D97706') ?>" oninput="document.getElementById('picker_warning').value = this.value; updateCustomPreview();">
                </div>
            </div>

            <!-- Erro -->
            <div class="form-group" style="margin: 0;">
                <label for="custom_color_error" style="font-size: 0.78rem;">Mensagem de Erro (--color-error):</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_error" value="<?= \App\Core\View::e($customSettings['custom_color_error'] ?? '#DC2626') ?>" onchange="document.getElementById('custom_color_error').value = this.value; updateCustomPreview();" style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_error" name="custom_color_error" class="form-input" value="<?= \App\Core\View::e($customSettings['custom_color_error'] ?? '#DC2626') ?>" oninput="document.getElementById('picker_error').value = this.value; updateCustomPreview();">
                </div>
            </div>
        </div>

        <!-- SEÇÃO 6: GRID DE TIPOGRAFIA E FORMAS -->
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

        <!-- PRÉ-VISUALIZADOR DA SUA PALETA AO VIVO (DEMONSTRADOR DE CONTRASTE REAL) -->
        <div id="customPalettePreviewBox" style="margin-top: 24px; background: #FFFFFF; border: 1px solid rgba(167, 173, 180, 0.4); border-radius: 12px; padding: 22px; display: flex; flex-direction: column; gap: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 14px;">
                <div style="flex: 1; min-width: 260px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                        <span id="previewDecorIcon" style="font-size: 1.1rem; color: #A7ADB4;">✦</span>
                        <span id="previewSerifTitle" style="font-family: var(--font-serif); font-size: 1.35rem; color: #202020; font-weight: 700;">
                            Divinium Joias Contemporâneas
                        </span>
                    </div>
                    <span id="previewSubtitleText" style="font-size: 0.82rem; color: #686868; display: block; margin-bottom: 6px; font-weight: 600;">
                        Alta Joalheria Autoral em Prata 925 & Luxo Silencioso
                    </span>
                    <span id="previewSampleText" style="font-size: 0.85rem; color: #202020; display: block; line-height: 1.45;">
                        Cada peça é desenhada para harmonizar proporção anatômica, pureza mineral e acabamento acetinado de precisão.
                    </span>
                    <span id="previewSampleMuted" style="font-size: 0.76rem; color: #686868; display: block; margin-top: 4px;">
                        Garantia vitalícia do metal nobre com certificado oficial de autenticidade mineral.
                    </span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px; align-items: flex-end;">
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <a href="javascript:void(0)" id="previewSampleLink" style="font-size: 0.82rem; color: #202020; text-decoration: underline; font-weight: 600;">Ver Coleção</a>
                        <span id="previewNavIcon" style="font-size: 0.9rem; color: #202020;">🔍</span>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button type="button" id="previewBtnSampleSecondary" style="background: #F0F0EF; color: #202020; border: 1px solid #CBD0D6; padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 0.82rem; cursor: pointer;">
                            <span>Secundário</span>
                        </button>
                        <button type="button" id="previewBtnSample" style="background: #A7ADB4; color: #202020; border: 1px solid #737B85; padding: 8px 18px; border-radius: 8px; font-weight: 700; font-size: 0.82rem; cursor: pointer;">
                            <span>Botão Primário</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pílulas de Status Funcional -->
            <div style="display: flex; gap: 10px; border-top: 1px solid rgba(0,0,0,0.06); padding-top: 12px; flex-wrap: wrap;">
                <span id="previewSuccessBadge" style="font-size: 0.72rem; font-weight: 700; color: #2E7D32; background: rgba(46,125,50,0.1); padding: 3px 10px; border-radius: 12px;">✓ Pedido Aprovado (Sucesso)</span>
                <span id="previewWarningBadge" style="font-size: 0.72rem; font-weight: 700; color: #D97706; background: rgba(217,119,6,0.1); padding: 3px 10px; border-radius: 12px;">⚠ Poucas Peças em Estoque (Alerta)</span>
                <span id="previewErrorBadge" style="font-size: 0.72rem; font-weight: 700; color: #DC2626; background: rgba(220,38,38,0.1); padding: 3px 10px; border-radius: 12px;">✕ Campo Obrigatório (Erro)</span>
            </div>
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
        'preview_sub' => 'Prata de Lei 925 & Gemas Minerais Raras'
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
        'preview_sub' => 'Prata 925 • R$ 490,00'
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
// GESTÃO DE TEMAS NATIVOS & ALTERNADOR VISUAL
// ==============================================================================
function previewThemeOnPage(themeId) {
    if (window.diviniumApplyTheme) {
        window.diviniumApplyTheme(themeId);
    } else {
        const themes = [
            'theme-silver-noir', 'theme-pearl-platinum', 'theme-nude-pearl', 'theme-contemporary-graphite',
            'theme-gold', 'theme-silver', 'theme-emerald', 'theme-rose'
        ];
        themes.forEach(t => {
            document.documentElement.classList.remove(t);
            document.body.classList.remove(t);
        });
        document.documentElement.classList.add(themeId);
        document.body.classList.add(themeId);
        localStorage.setItem('divinium_theme', themeId);
    }

    const toast = document.createElement('div');
    toast.style.position = 'fixed';
    toast.style.bottom = '24px';
    toast.style.right = '24px';
    toast.style.background = '#1a1816';
    toast.style.border = '1px solid #cbd0d6';
    toast.style.color = '#fff';
    toast.style.padding = '12px 20px';
    toast.style.borderRadius = '8px';
    toast.style.boxShadow = '0 8px 25px rgba(0,0,0,0.5)';
    toast.style.fontSize = '0.85rem';
    toast.style.zIndex = '99999';
    toast.textContent = '✦ Tema aplicado no seu navegador para teste instantâneo!';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2500);
}

async function setDefaultTheme(themeId, btnElement) {
    const originalText = btnElement.innerHTML;
    btnElement.disabled = true;
    btnElement.innerHTML = '<span>Salvando...</span>';

    try {
        const formData = new FormData();
        formData.append('theme_id', themeId);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        formData.append('_csrf_token', csrfToken);

        const response = await fetch('/admin/temas/salvar', {
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
            throw new Error('O servidor respondeu com formato inesperado. Se sua sessão expirou, recarregue a página.');
        }

        if (data.auth_required) {
            alert('Sua sessão administrativa expirou. Por favor, faça login novamente.');
            window.location.reload();
            return;
        }

        if (data.success) {
            document.querySelectorAll('.theme-card').forEach(c => {
                c.classList.remove('active-theme');
                c.style.borderColor = 'rgba(255, 255, 255, 0.1)';
            });
            document.querySelectorAll('.active-badge').forEach(b => b.style.display = 'none');

            const card = document.getElementById('card-' + themeId);
            if (card) card.classList.add('active-theme');
            const badge = document.getElementById('badge-' + themeId);
            if (badge) badge.style.display = 'inline-flex';

            previewThemeOnPage(themeId);

            btnElement.innerHTML = '<span>✓ Definido!</span>';
            setTimeout(() => {
                btnElement.disabled = false;
                btnElement.innerHTML = originalText;
            }, 1800);
        } else {
            alert('Erro: ' + (data.error || 'Não foi possível salvar o tema.'));
            btnElement.disabled = false;
            btnElement.innerHTML = originalText;
        }
    } catch (err) {
        alert('Falha na comunicação: ' + err.message);
        btnElement.disabled = false;
        btnElement.innerHTML = originalText;
    }
}

// ==============================================================================
// GESTÃO DAS CORES INDEPENDENTES & PREVIEW DA PALETA
// ==============================================================================
function setQuickColor(inputId, pickerId, val) {
    const input = document.getElementById(inputId);
    const picker = document.getElementById(pickerId);
    if (input) input.value = val;
    if (picker) picker.value = val;
    updateCustomPreview();
}

function clearColorField(inputId, pickerId, defaultVal) {
    const input = document.getElementById(inputId);
    const picker = document.getElementById(pickerId);
    if (input) input.value = defaultVal;
    if (picker) picker.value = defaultVal;
    updateCustomPreview();
}

function getContrastColor(hex) {
    if (!hex || hex.length < 7) return '#202020';
    const r = parseInt(hex.substr(1, 2), 16);
    const g = parseInt(hex.substr(3, 2), 16);
    const b = parseInt(hex.substr(5, 2), 16);
    const yiq = (r * 299 + g * 587 + b * 114) / 1000;
    return yiq >= 140 ? '#202020' : '#FFFFFF';
}

function updateCustomPreview() {
    const bg = document.getElementById('custom_color_bg')?.value || '#F8F8F7';
    const surface = document.getElementById('custom_color_surface')?.value || '#FFFFFF';
    const title = document.getElementById('custom_color_title')?.value || '#202020';
    const subtitle = document.getElementById('custom_color_subtitle')?.value || '#686868';
    const text = document.getElementById('custom_color_text')?.value || '#202020';
    const textMuted = document.getElementById('custom_color_text_muted')?.value || '#686868';
    const iconDecor = document.getElementById('custom_color_icon_decor')?.value || '#A7ADB4';
    const iconNav = document.getElementById('custom_color_icon_nav')?.value || '#202020';
    const link = document.getElementById('custom_color_link')?.value || '#202020';
    const btnPrimaryBg = document.getElementById('custom_color_btn_primary_bg')?.value || '#A7ADB4';
    const btnPrimaryText = document.getElementById('custom_color_btn_primary_text')?.value || getContrastColor(btnPrimaryBg);
    const btnPrimaryBorder = document.getElementById('custom_color_btn_primary_border')?.value || '#737B85';
    const btnSecondaryBg = document.getElementById('custom_color_btn_secondary_bg')?.value || '#F0F0EF';
    const btnSecondaryText = document.getElementById('custom_color_btn_secondary_text')?.value || '#202020';
    const btnSecondaryBorder = document.getElementById('custom_color_btn_secondary_border')?.value || '#CBD0D6';
    const fontSerif = document.getElementById('custom_font_serif')?.value || "'Cormorant Garamond', Georgia, serif";
    const radius = document.getElementById('custom_border_radius')?.value || '8px';

    const box = document.getElementById('customPalettePreviewBox');
    if (box) {
        box.style.backgroundColor = surface;
        box.style.borderColor = btnPrimaryBorder;
    }
    const titleEl = document.getElementById('previewSerifTitle');
    if (titleEl) {
        titleEl.style.color = title;
        titleEl.style.fontFamily = fontSerif;
    }
    const subEl = document.getElementById('previewSubtitleText');
    if (subEl) subEl.style.color = subtitle;
    const sampleText = document.getElementById('previewSampleText');
    if (sampleText) sampleText.style.color = text;
    const sampleMuted = document.getElementById('previewSampleMuted');
    if (sampleMuted) sampleMuted.style.color = textMuted;

    const decorEl = document.getElementById('previewDecorIcon');
    if (decorEl) decorEl.style.color = iconDecor;
    const navEl = document.getElementById('previewNavIcon');
    if (navEl) navEl.style.color = iconNav;

    const linkEl = document.getElementById('previewSampleLink');
    if (linkEl) linkEl.style.color = link;

    const btn = document.getElementById('previewBtnSample');
    if (btn) {
        btn.style.backgroundColor = btnPrimaryBg;
        btn.style.color = btnPrimaryText;
        btn.style.borderColor = btnPrimaryBorder;
        btn.style.borderRadius = radius;
    }

    const btnSec = document.getElementById('previewBtnSampleSecondary');
    if (btnSec) {
        btnSec.style.backgroundColor = btnSecondaryBg;
        btnSec.style.color = btnSecondaryText;
        btnSec.style.borderColor = btnSecondaryBorder;
        btnSec.style.borderRadius = radius;
    }
}

function resetToThemeDefaults() {
    if (!confirm('Deseja realmente restaurar os padrões visuais originais da DIVINIUM (Prata Nobre & Noir)? Quaisquer alterações não salvas serão substituídas.')) {
        return;
    }
    setQuickColor('custom_color_bg', 'picker_bg', '#F8F8F7');
    setQuickColor('custom_color_bg_alt', 'picker_bg_alt', '#F0F0EF');
    setQuickColor('custom_color_surface', 'picker_surface', '#FFFFFF');
    setQuickColor('custom_color_surface_box', 'picker_surface_box', '#FFFFFF');
    setQuickColor('custom_color_title', 'picker_title', '#202020');
    setQuickColor('custom_color_subtitle', 'picker_subtitle', '#686868');
    setQuickColor('custom_color_text', 'picker_text', '#202020');
    setQuickColor('custom_color_text_muted', 'picker_text_muted', '#686868');
    setQuickColor('custom_color_icon_decor', 'picker_icon_decor', '#A7ADB4');
    setQuickColor('custom_color_icon_nav', 'picker_icon_nav', '#202020');
    setQuickColor('custom_color_link', 'picker_link', '#202020');
    setQuickColor('custom_color_link_hover', 'picker_link_hover', '#737B85');
    setQuickColor('custom_color_btn_primary_bg', 'picker_btn_primary_bg', '#A7ADB4');
    setQuickColor('custom_color_btn_primary_text', 'picker_btn_primary_text', '#202020');
    setQuickColor('custom_color_btn_primary_border', 'picker_btn_primary_border', '#737B85');
    setQuickColor('custom_color_btn_secondary_bg', 'picker_btn_secondary_bg', '#F0F0EF');
    setQuickColor('custom_color_btn_secondary_text', 'picker_btn_secondary_text', '#202020');
    setQuickColor('custom_color_btn_secondary_border', 'picker_btn_secondary_border', '#CBD0D6');
    setQuickColor('custom_color_border', 'picker_border', '#737B85');
    setQuickColor('custom_color_focus_ring', 'picker_focus_ring', '#737B85');
    setQuickColor('custom_color_success', 'picker_success', '#2E7D32');
    setQuickColor('custom_color_warning', 'picker_warning', '#D97706');
    setQuickColor('custom_color_error', 'picker_error', '#DC2626');

    document.getElementById('custom_font_serif').value = "'Cormorant Garamond', Georgia, serif";
    document.getElementById('custom_border_radius').value = '8px';

    updateCustomPreview();
}

async function handleCustomThemeSubmit(e) {
    e.preventDefault();
    const form = document.getElementById('customThemeForm');
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';

    try {
        const res = await fetch('/admin/temas/salvar-custom', {
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
            alert(data.message || 'Cores e identidade visual salvas com sucesso!');
            window.location.reload();
        } else {
            alert(data.error || 'Erro ao salvar tema personalizado.');
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
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

    try {
        const res = await fetch('/admin/temas/salvar-custom', {
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
