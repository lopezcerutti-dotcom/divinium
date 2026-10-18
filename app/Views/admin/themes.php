<?php
/**
 * VIEW: GESTÃO DE TEMAS & IDENTIDADE VISUAL - ADMIN DIVINIUM
 */
?>

<!-- BANNER DE APRESENTAÇÃO DE TEMAS -->
<div class="admin-card" style="background: linear-gradient(135deg, rgba(202, 161, 92, 0.12) 0%, rgba(13, 15, 18, 0.95) 100%); border: 1px solid rgba(202, 161, 92, 0.35); margin-bottom: 28px; padding: 26px 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                <span style="color: #caa15c; font-size: 1.3rem;">✦</span>
                <h2 style="font-family: var(--font-serif); font-size: 1.6rem; color: #ffffff; margin: 0; letter-spacing: 0.5px;">Gestão de Temas & Paletas Quiet Luxury</h2>
                <span style="background: rgba(202, 161, 92, 0.2); color: #caa15c; font-size: 0.7rem; font-weight: 700; padding: 3px 10px; border-radius: 12px; text-transform: uppercase;">4 Paletas Nobres</span>
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
        <div class="theme-card <?= $isActive ? 'active-theme' : '' ?>" id="card-<?= $theme['id'] ?>" style="background: #14161a; border: 1px solid <?= $isActive ? $theme['primary_color'] : 'rgba(255, 255, 255, 0.1)' ?>; border-radius: 14px; padding: 26px; display: flex; flex-direction: column; justify-content: space-between; position: relative; transition: all 0.3s ease; box-shadow: <?= $isActive ? '0 12px 30px rgba(0,0,0,0.5), 0 0 25px ' . $theme['primary_color'] . '33' : '0 8px 20px rgba(0,0,0,0.3)' ?>;">
            
            <!-- BADGE SUPERIOR -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <span style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; padding: 4px 10px; border-radius: 8px; background: rgba(255,255,255,0.06); color: <?= $theme['primary_color'] ?>; border: 1px solid <?= $theme['primary_color'] ?>44;">
                    <?= \App\Core\View::e($theme['badge']) ?>
                </span>
                
                <?php if ($isActive): ?>
                    <span class="active-badge" id="badge-<?= $theme['id'] ?>" style="display: inline-flex; align-items: center; gap: 5px; font-size: 0.72rem; font-weight: 700; color: #4ade80; background: rgba(74, 222, 128, 0.12); padding: 4px 10px; border-radius: 8px; border: 1px solid rgba(74, 222, 128, 0.3);">
                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #4ade80; box-shadow: 0 0 8px #4ade80;"></span>
                        Tema Padrão do Site
                    </span>
                <?php else: ?>
                    <span class="active-badge" id="badge-<?= $theme['id'] ?>" style="display: none; align-items: center; gap: 5px; font-size: 0.72rem; font-weight: 700; color: #4ade80; background: rgba(74, 222, 128, 0.12); padding: 4px 10px; border-radius: 8px; border: 1px solid rgba(74, 222, 128, 0.3);">
                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #4ade80; box-shadow: 0 0 8px #4ade80;"></span>
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
                <div style="display: flex; gap: 10px; align-items: center;">
                    <div title="Cor Primária: <?= $theme['primary_color'] ?>" style="width: 32px; height: 32px; border-radius: 50%; background: <?= $theme['primary_color'] ?>; border: 2px solid rgba(255,255,255,0.2); box-shadow: 0 0 10px <?= $theme['primary_color'] ?>55;"></div>
                    <div title="Cor de Destaque: <?= $theme['accent_color'] ?>" style="width: 32px; height: 32px; border-radius: 50%; background: <?= $theme['accent_color'] ?>; border: 2px solid rgba(255,255,255,0.2);"></div>
                    <div title="Fundo Base: <?= $theme['bg_color'] ?>" style="width: 32px; height: 32px; border-radius: 50%; background: <?= $theme['bg_color'] ?>; border: 2px solid rgba(255,255,255,0.2);"></div>
                    <div title="Texto Nobre: <?= $theme['text_color'] ?>" style="width: 32px; height: 32px; border-radius: 50%; background: <?= $theme['text_color'] ?>; border: 2px solid rgba(255,255,255,0.2);"></div>
                    <div style="margin-left: auto; font-size: 0.72rem; color: #bbb; font-family: monospace;">
                        <?= $theme['primary_color'] ?>
                    </div>
                </div>
            </div>

            <!-- MINI PREVIEW VISUAL -->
            <div style="background: <?= $theme['bg_color'] ?>; border: 1px solid <?= $theme['primary_color'] ?>44; border-radius: 10px; padding: 14px; margin-bottom: 22px; position: relative; overflow: hidden;">
                <div style="font-size: 0.68rem; text-transform: uppercase; color: <?= $theme['primary_color'] ?>; font-weight: 700; margin-bottom: 4px;">Exemplo de Botão & Tipografia:</div>
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <span style="font-family: var(--font-serif); font-size: 1.05rem; background: <?= $theme['gradient'] ?>; -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;">
                        Divinium Joias
                    </span>
                    <span style="font-size: 0.72rem; font-weight: 700; padding: 6px 12px; border-radius: 20px; background: <?= $theme['primary_color'] ?>; color: #000; box-shadow: 0 2px 8px <?= $theme['primary_color'] ?>66;">
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

<!-- ESTÚDIO DE PERSONALIZAÇÃO DE CORES & IDENTIDADE AUTORAL -->
<div class="admin-card" style="border: 1px solid rgba(202, 161, 92, 0.35); padding: 26px; margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 16px; flex-wrap: wrap; gap: 14px;">
        <div>
            <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; font-size: 1.35rem; margin: 0;">
                <span style="color: #caa15c;">🎨</span>
                Estúdio de Cores & Personalização Autoral
            </h3>
            <p style="font-size: 0.82rem; color: #9e978e; margin: 4px 0 0;">
                Personalize as cores primárias, superfícies e fontes para criar uma paleta exclusiva para sua joalheria.
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <button type="button" class="btn-admin-secondary" onclick="resetToThemeDefaults()">
                <span>Restaurar Padrões</span>
            </button>
            <button type="button" class="btn-admin-gold" onclick="document.getElementById('customThemeForm').requestSubmit()">
                <span>Salvar Identidade Personalizada</span>
            </button>
        </div>
    </div>

    <form id="customThemeForm" onsubmit="handleCustomThemeSubmit(event)">
        <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">

        <!-- GRID DE CORES -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 22px;">
            <div class="form-group" style="margin: 0;">
                <label for="custom_color_primary">Cor Primária (Ouro / Botões)</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_primary" 
                           value="<?= \App\Core\View::e($customSettings['custom_color_primary'] ?? '#CAA15C') ?>" 
                           onchange="document.getElementById('custom_color_primary').value = this.value; updateCustomPreview();"
                           style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_primary" name="custom_color_primary" class="form-input" 
                           value="<?= \App\Core\View::e($customSettings['custom_color_primary'] ?? '#CAA15C') ?>" 
                           oninput="document.getElementById('picker_primary').value = this.value; updateCustomPreview();">
                </div>
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="custom_color_accent">Cor de Realce (Acentos & Hover)</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_accent" 
                           value="<?= \App\Core\View::e($customSettings['custom_color_accent'] ?? '#E0C080') ?>" 
                           onchange="document.getElementById('custom_color_accent').value = this.value; updateCustomPreview();"
                           style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_accent" name="custom_color_accent" class="form-input" 
                           value="<?= \App\Core\View::e($customSettings['custom_color_accent'] ?? '#E0C080') ?>" 
                           oninput="document.getElementById('picker_accent').value = this.value; updateCustomPreview();">
                </div>
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="custom_color_bg">Fundo da Página (Dark Noir)</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_bg" 
                           value="<?= \App\Core\View::e($customSettings['custom_color_bg'] ?? '#0D0C0B') ?>" 
                           onchange="document.getElementById('custom_color_bg').value = this.value; updateCustomPreview();"
                           style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_bg" name="custom_color_bg" class="form-input" 
                           value="<?= \App\Core\View::e($customSettings['custom_color_bg'] ?? '#0D0C0B') ?>" 
                           oninput="document.getElementById('picker_bg').value = this.value; updateCustomPreview();">
                </div>
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="custom_color_surface">Superfície dos Cards & Módulos</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_surface" 
                           value="<?= \App\Core\View::e($customSettings['custom_color_surface'] ?? '#141312') ?>" 
                           onchange="document.getElementById('custom_color_surface').value = this.value; updateCustomPreview();"
                           style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_surface" name="custom_color_surface" class="form-input" 
                           value="<?= \App\Core\View::e($customSettings['custom_color_surface'] ?? '#141312') ?>" 
                           oninput="document.getElementById('picker_surface').value = this.value; updateCustomPreview();">
                </div>
            </div>
        </div>

        <!-- GRID DE TIPOGRAFIA E FORMAS -->
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

        <!-- PRÉ-VISUALIZADOR DA SUA PALETA AO VIVO -->
        <div id="customPalettePreviewBox" style="margin-top: 20px; background: #0d0c0b; border: 1px solid #caa15c; border-radius: 12px; padding: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
            <div>
                <span id="previewSerifTitle" style="font-family: var(--font-serif); font-size: 1.4rem; color: #caa15c; display: block; font-weight: 700;">
                    Divinium Joias Contemporâneas
                </span>
                <span style="font-size: 0.8rem; color: #ded7cf;">
                    Prévia em tempo real das cores e tipografia selecionadas acima.
                </span>
            </div>
            <button type="button" id="previewBtnSample" class="btn-admin-gold" style="background: #caa15c; color: #000; border-radius: 8px;">
                <span>Exemplo de Botão</span>
            </button>
        </div>
    </form>
</div>

<!-- INFORMAÇÕES DE IMPLEMENTAÇÃO E CUSTOMIZAÇÃO -->
<div class="admin-card" style="border: 1px solid rgba(255, 255, 255, 0.08); padding: 24px;">
    <h3 class="card-title" style="margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
        Como funciona a Gestão de Temas na Divinium?
    </h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; font-size: 0.84rem; color: #b8b1a7; line-height: 1.5;">
        <div>
            <strong style="color: #fff; display: block; margin-bottom: 4px;">1. Tema Padrão Global:</strong>
            Ao clicar em <em>"Definir Padrão"</em>, a preferência é gravada no banco de dados. Qualquer cliente ou visitante que acessar a página inicial ou a loja receberá essa paleta de cores imediatamente no primeiro carregamento.
        </div>
        <div>
            <strong style="color: #fff; display: block; margin-bottom: 4px;">2. Alternador para o Visitante:</strong>
            O visitante também pode alternar entre os temas usando o botão de gema no cabeçalho superior. A escolha individual dele fica salva na memória do navegador do cliente sem afetar os outros usuários.
        </div>
        <div>
            <strong style="color: #fff; display: block; margin-bottom: 4px;">3. Arquitetura CSS Zero Reflow:</strong>
            Todos os 4 temas utilizam classes CSS nativas pré-compiladas (`.theme-gold`, `.theme-silver`, `.theme-emerald`, `.theme-rose`), garantindo renderização instantânea em 60fps sem lentidão.
        </div>
    </div>
</div>

<script>
// Testar tema instantaneamente no navegador
function previewThemeOnPage(themeId) {
    if (window.diviniumApplyTheme) {
        window.diviniumApplyTheme(themeId);
    } else {
        const themes = ['theme-gold', 'theme-silver', 'theme-emerald', 'theme-rose'];
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
    toast.style.border = '1px solid #caa15c';
    toast.style.color = '#fff';
    toast.style.padding = '12px 20px';
    toast.style.borderRadius = '8px';
    toast.style.boxShadow = '0 8px 25px rgba(0,0,0,0.5)';
    toast.style.fontSize = '0.85rem';
    toast.style.zIndex = '99999';
    toast.textContent = '✦ Tema aplicado no seu navegador para pré-visualização!';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2500);
}

// Salvar no banco como tema padrão do site
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
                'X-CSRF-TOKEN': csrfToken
            }
        });

        const data = await response.json();

        if (data.success) {
            // Atualizar classes visuais dos cards
            document.querySelectorAll('.theme-card').forEach(c => {
                c.classList.remove('active-theme');
                c.style.borderColor = 'rgba(255, 255, 255, 0.1)';
            });
            document.querySelectorAll('.active-badge').forEach(b => b.style.display = 'none');

            const card = document.getElementById('card-' + themeId);
            if (card) {
                card.classList.add('active-theme');
            }
            const badge = document.getElementById('badge-' + themeId);
            if (badge) {
                badge.style.display = 'inline-flex';
            }

            // Aplicar também no navegador
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
        alert('Falha na comunicação com o servidor.');
        btnElement.disabled = false;
        btnElement.innerHTML = originalText;
    }
}

function updateCustomPreview() {
    const primary = document.getElementById('custom_color_primary')?.value || '#CAA15C';
    const accent = document.getElementById('custom_color_accent')?.value || '#E0C080';
    const bg = document.getElementById('custom_color_bg')?.value || '#0D0C0B';
    const surface = document.getElementById('custom_color_surface')?.value || '#141312';
    const fontSerif = document.getElementById('custom_font_serif')?.value || "'Cormorant Garamond', Georgia, serif";
    const radius = document.getElementById('custom_border_radius')?.value || '8px';

    const box = document.getElementById('customPalettePreviewBox');
    if (box) {
        box.style.backgroundColor = bg;
        box.style.borderColor = primary;
    }
    const title = document.getElementById('previewSerifTitle');
    if (title) {
        title.style.color = primary;
        title.style.fontFamily = fontSerif;
    }
    const btn = document.getElementById('previewBtnSample');
    if (btn) {
        btn.style.backgroundColor = primary;
        btn.style.borderRadius = radius;
    }
}

function resetToThemeDefaults() {
    document.getElementById('custom_color_primary').value = '#CAA15C';
    document.getElementById('picker_primary').value = '#CAA15C';
    document.getElementById('custom_color_accent').value = '#E0C080';
    document.getElementById('picker_accent').value = '#E0C080';
    document.getElementById('custom_color_bg').value = '#0D0C0B';
    document.getElementById('picker_bg').value = '#0D0C0B';
    document.getElementById('custom_color_surface').value = '#141312';
    document.getElementById('picker_surface').value = '#141312';
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
    if (csrfToken && !formData.has('_csrf_token')) {
        formData.append('_csrf_token', csrfToken);
    }

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
            alert(data.message || 'Identidade visual e paleta personalizada salvas!');
            window.location.reload();
        } else {
            alert(data.error || 'Erro ao salvar tema personalizado.');
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
    }
}

document.addEventListener('DOMContentLoaded', updateCustomPreview);
</script>
