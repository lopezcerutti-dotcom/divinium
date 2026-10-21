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
                Estúdio de Cores & Fundo de Todas as Páginas
            </h3>
            <p style="font-size: 0.82rem; color: #9e978e; margin: 4px 0 0;">
                Personalize as cores de fundo, superfícies, ouro e textos para todas as páginas da loja (Home, Boutique, Produto, Checkout e Institucionais).
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

        <!-- 1. GRID DE CORES GLOBAIS (APLICADAS EM TODAS AS PÁGINAS) -->
        <div style="margin-bottom: 12px;">
            <span style="font-size: 0.78rem; text-transform: uppercase; color: #caa15c; font-weight: 700; letter-spacing: 0.5px;">✦ 1. Cores Globais da Marca (Aplicam-se em Todas as Páginas do Site):</span>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 24px;">
            <div class="form-group" style="margin: 0;">
                <label for="custom_color_bg">Fundo Base (Dark Noir)</label>
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
                <label for="custom_color_surface">Superfície dos Cards & Caixas</label>
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
                <label for="custom_color_accent">Cor de Realce (Hover & Acentos)</label>
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
                <label for="custom_color_text">Texto Principal (Leitura)</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_text" 
                           value="<?= \App\Core\View::e($customSettings['custom_color_text'] ?? '#F4EFEA') ?>" 
                           onchange="document.getElementById('custom_color_text').value = this.value; updateCustomPreview();"
                           style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_text" name="custom_color_text" class="form-input" 
                           value="<?= \App\Core\View::e($customSettings['custom_color_text'] ?? '#F4EFEA') ?>" 
                           oninput="document.getElementById('picker_text').value = this.value; updateCustomPreview();">
                </div>
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="custom_color_text_muted">Texto Suave (Secundário)</label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="color" id="picker_text_muted" 
                           value="<?= \App\Core\View::e($customSettings['custom_color_text_muted'] ?? '#9E978E') ?>" 
                           onchange="document.getElementById('custom_color_text_muted').value = this.value; updateCustomPreview();"
                           style="width: 44px; height: 38px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: transparent; cursor: pointer;">
                    <input type="text" id="custom_color_text_muted" name="custom_color_text_muted" class="form-input" 
                           value="<?= \App\Core\View::e($customSettings['custom_color_text_muted'] ?? '#9E978E') ?>" 
                           oninput="document.getElementById('picker_text_muted').value = this.value; updateCustomPreview();">
                </div>
            </div>
        </div>

        <!-- 2. CORES ESPECÍFICAS POR PÁGINA (OPCIONAL) -->
        <div style="background: rgba(0,0,0,0.3); border: 1px solid rgba(202,161,92,0.25); border-radius: 12px; padding: 18px 20px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;" onclick="toggleSpecificColorsSection()">
                <div>
                    <span style="font-size: 0.82rem; font-weight: 700; color: #ded7cf; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px;">
                        <span>✦ 2. Personalização de Cores Específicas por Página</span>
                        <span style="font-size: 0.7rem; color: #caa15c; background: rgba(202,161,92,0.18); padding: 2px 8px; border-radius: 6px; font-weight: 600;">Opcional</span>
                    </span>
                    <p style="font-size: 0.76rem; color: #8e8880; margin: 4px 0 0;">
                        Deseja personalizar individualmente o tom de fundo ou dos cards da Boutique, do Produto, do Checkout ou das Políticas? Configure abaixo. Se deixar vazio, cada página herda as cores globais.
                    </p>
                </div>
                <span id="arrowSpecificColors" style="color: #caa15c; font-size: 1.1rem; transition: transform 0.2s;">▼</span>
            </div>

            <div id="contentSpecificColors" style="margin-top: 18px; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 16px; display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px;">
                <!-- Boutique & Catálogo (/loja) -->
                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 14px;">
                    <span style="font-size: 0.75rem; font-weight: 700; color: #caa15c; text-transform: uppercase; display: block; margin-bottom: 10px;">🛍️ Boutique & Catálogo (/loja)</span>
                    <div class="form-group" style="margin-bottom: 10px;">
                        <label style="font-size: 0.75rem;">Fundo da Boutique:</label>
                        <input type="text" name="store_bg_color" class="form-input" 
                               value="<?= \App\Core\View::e($customSettings['store_bg_color'] ?? '') ?>" 
                               placeholder="Padrão: herdado do global (#0D0C0B)">
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label style="font-size: 0.75rem;">Cards de Joias da Boutique:</label>
                        <input type="text" name="store_card_bg" class="form-input" 
                               value="<?= \App\Core\View::e($customSettings['store_card_bg'] ?? '') ?>" 
                               placeholder="Padrão: herdado do global (#141312)">
                    </div>
                </div>

                <!-- Detalhe da Joia (/produto/{slug}) -->
                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 14px;">
                    <span style="font-size: 0.75rem; font-weight: 700; color: #caa15c; text-transform: uppercase; display: block; margin-bottom: 10px;">💎 Página de Detalhe da Joia</span>
                    <div class="form-group" style="margin-bottom: 10px;">
                        <label style="font-size: 0.75rem;">Fundo da Página do Produto:</label>
                        <input type="text" name="product_bg_color" class="form-input" 
                               value="<?= \App\Core\View::e($customSettings['product_bg_color'] ?? '') ?>" 
                               placeholder="Padrão: herdado do global (#0D0C0B)">
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label style="font-size: 0.75rem;">Card da Galeria & Compra:</label>
                        <input type="text" name="product_card_bg" class="form-input" 
                               value="<?= \App\Core\View::e($customSettings['product_card_bg'] ?? '') ?>" 
                               placeholder="Padrão: herdado do global (#141312)">
                    </div>
                </div>

                <!-- Checkout Seguro (/checkout) -->
                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 14px;">
                    <span style="font-size: 0.75rem; font-weight: 700; color: #caa15c; text-transform: uppercase; display: block; margin-bottom: 10px;">🔒 Checkout Seguro (/checkout)</span>
                    <div class="form-group" style="margin-bottom: 10px;">
                        <label style="font-size: 0.75rem;">Fundo do Checkout:</label>
                        <input type="text" name="checkout_bg_color" class="form-input" 
                               value="<?= \App\Core\View::e($customSettings['checkout_bg_color'] ?? '') ?>" 
                               placeholder="Padrão: herdado do global (#0D0C0B)">
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label style="font-size: 0.75rem;">Caixas de Pagamento & Resumo:</label>
                        <input type="text" name="checkout_card_bg" class="form-input" 
                               value="<?= \App\Core\View::e($customSettings['checkout_card_bg'] ?? '') ?>" 
                               placeholder="Padrão: herdado do global (#141312)">
                    </div>
                </div>

                <!-- Páginas Institucionais & Políticas -->
                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 14px;">
                    <span style="font-size: 0.75rem; font-weight: 700; color: #caa15c; text-transform: uppercase; display: block; margin-bottom: 10px;">📜 Páginas Institucionais & Políticas</span>
                    <div class="form-group" style="margin-bottom: 10px;">
                        <label style="font-size: 0.75rem;">Fundo das Páginas de Políticas:</label>
                        <input type="text" name="institutional_bg_color" class="form-input" 
                               value="<?= \App\Core\View::e($customSettings['institutional_bg_color'] ?? '') ?>" 
                               placeholder="Padrão: herdado do global (#0D0C0B)">
                    </div>
                    <div class="form-group" style="margin: 0;">
                        <label style="font-size: 0.75rem;">Card do Conteúdo Editorial:</label>
                        <input type="text" name="institutional_card_bg" class="form-input" 
                               value="<?= \App\Core\View::e($customSettings['institutional_card_bg'] ?? '') ?>" 
                               placeholder="Padrão: herdado do global (#141312)">
                    </div>
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

        <!-- ESCALA TIPOGRÁFICA, RESPIRO & ALINHAMENTO (PILAR 5) -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; border-top: 1px solid rgba(255, 255, 255, 0.06); padding-top: 18px; margin-top: 18px;">
            <div class="form-group" style="margin: 0;">
                <label for="hero_title_size">Tamanho do Título Principal (Hero)</label>
                <select id="hero_title_size" name="hero_title_size" class="form-select">
                    <option value="clamp(2.3rem, 3.8vw, 3.4rem)" <?= ($customSettings['hero_title_size'] ?? '') === 'clamp(2.3rem, 3.8vw, 3.4rem)' ? 'selected' : '' ?>>Editorial Padrão (Harmônico)</option>
                    <option value="clamp(2.6rem, 4.5vw, 4.0rem)" <?= ($customSettings['hero_title_size'] ?? '') === 'clamp(2.6rem, 4.5vw, 4.0rem)' ? 'selected' : '' ?>>Grande & Imponente (Impacto)</option>
                    <option value="clamp(2.0rem, 3.0vw, 2.8rem)" <?= ($customSettings['hero_title_size'] ?? '') === 'clamp(2.0rem, 3.0vw, 2.8rem)' ? 'selected' : '' ?>>Minimalista Discreto (Quiet Luxe)</option>
                </select>
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="body_font_size">Tamanho do Texto de Leitura</label>
                <select id="body_font_size" name="body_font_size" class="form-select">
                    <option value="1rem" <?= ($customSettings['body_font_size'] ?? '1rem') === '1rem' ? 'selected' : '' ?>>16px — Padrão Equilibrado</option>
                    <option value="1.05rem" <?= ($customSettings['body_font_size'] ?? '') === '1.05rem' ? 'selected' : '' ?>>17px — Leitura Confortável</option>
                    <option value="0.95rem" <?= ($customSettings['body_font_size'] ?? '') === '0.95rem' ? 'selected' : '' ?>>15px — Compacto & Refinado</option>
                </select>
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="section_spacing_y">Espaçamento Vertical entre Seções</label>
                <select id="section_spacing_y" name="section_spacing_y" class="form-select">
                    <option value="120px" <?= ($customSettings['section_spacing_y'] ?? '120px') === '120px' ? 'selected' : '' ?>>120px — Respiro Amplo Quiet Luxury</option>
                    <option value="80px" <?= ($customSettings['section_spacing_y'] ?? '') === '80px' ? 'selected' : '' ?>>80px — Padrão Moderado</option>
                    <option value="160px" <?= ($customSettings['section_spacing_y'] ?? '') === '160px' ? 'selected' : '' ?>>160px — Alta Costura / Super Espaçado</option>
                </select>
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="editorial_text_alignment">Alinhamento Textual Editorial</label>
                <select id="editorial_text_alignment" name="editorial_text_alignment" class="form-select">
                    <option value="center" <?= ($customSettings['editorial_text_alignment'] ?? 'center') === 'center' ? 'selected' : '' ?>>Centralizado (Elegância Simétrica)</option>
                    <option value="left" <?= ($customSettings['editorial_text_alignment'] ?? '') === 'left' ? 'selected' : '' ?>>À Esquerda (Moderno Editorial)</option>
                </select>
            </div>
        </div>

        <!-- PRÉ-VISUALIZADOR DA SUA PALETA AO VIVO -->
        <div id="customPalettePreviewBox" style="margin-top: 20px; background: #0d0c0b; border: 1px solid #caa15c; border-radius: 12px; padding: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
            <div>
                <span id="previewSerifTitle" style="font-family: var(--font-serif); font-size: 1.4rem; color: #caa15c; display: block; font-weight: 700;">
                    Divinium Joias Contemporâneas
                </span>
                <span id="previewSampleText" style="font-size: 0.85rem; color: #f4efea; display: block; margin-top: 2px;">
                    Joias autorais em prata de lei 925 e gemas minerais raras.
                </span>
                <span id="previewSampleMuted" style="font-size: 0.76rem; color: #9e978e;">
                    Prévia em tempo real das cores e tipografia selecionadas acima.
                </span>
            </div>
            <button type="button" id="previewBtnSample" class="btn-admin-gold" style="background: #caa15c; color: #000; border-radius: 8px;">
                <span>Exemplo de Botão</span>
            </button>
        </div>
    </form>
</div>

<!-- ESTÚDIO DE IMAGEM DE FUNDO & TEXTURAS NOBRES EM TODAS AS PÁGINAS -->
<div class="admin-card" style="border: 1px solid rgba(202, 161, 92, 0.4); padding: 28px; margin-bottom: 30px; background: #14161a; position: relative; overflow: hidden;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 18px; flex-wrap: wrap; gap: 14px;">
        <div>
            <h3 class="card-title" style="display: flex; align-items: center; gap: 10px; font-size: 1.35rem; margin: 0;">
                <span style="color: #caa15c;">🖼️</span>
                Personalização de Imagens de Fundo & Texturas de Todas as Páginas
            </h3>
            <p style="font-size: 0.82rem; color: #9e978e; margin: 4px 0 0;">
                Defina imagens ou texturas nobres para o fundo global do site, da boutique, dos produtos, do checkout, das políticas ou do painel executivo.
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <button type="button" class="btn-admin-cancel" onclick="resetActiveBackground()">
                <span>Remover Fundo Ativo</span>
            </button>
            <button type="button" class="btn-admin-gold" onclick="saveBackgroundSettings()">
                <span>Salvar Imagens de Fundo</span>
            </button>
        </div>
    </div>

    <!-- TABS PARA ESCOLHA DO DESTINO DO FUNDO -->
    <div style="display: flex; gap: 8px; margin-bottom: 22px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 12px; flex-wrap: wrap;">
        <button type="button" id="tabBtnSite" class="btn-admin-gold" style="padding: 8px 16px; font-size: 0.82rem;" onclick="switchBgTab('site')">
            <span>🌐 Fundo Global (Todas as Páginas)</span>
        </button>
        <button type="button" id="tabBtnHero" class="btn-admin-cancel" style="padding: 8px 16px; font-size: 0.82rem;" onclick="switchBgTab('hero')">
            <span>👑 Hero da Home</span>
        </button>
        <button type="button" id="tabBtnStore" class="btn-admin-cancel" style="padding: 8px 16px; font-size: 0.82rem;" onclick="switchBgTab('store')">
            <span>🛍️ Boutique & Catálogo</span>
        </button>
        <button type="button" id="tabBtnProduct" class="btn-admin-cancel" style="padding: 8px 16px; font-size: 0.82rem;" onclick="switchBgTab('product')">
            <span>💎 Detalhes do Produto</span>
        </button>
        <button type="button" id="tabBtnCheckout" class="btn-admin-cancel" style="padding: 8px 16px; font-size: 0.82rem;" onclick="switchBgTab('checkout')">
            <span>🔒 Checkout Seguro</span>
        </button>
        <button type="button" id="tabBtnInstitutional" class="btn-admin-cancel" style="padding: 8px 16px; font-size: 0.82rem;" onclick="switchBgTab('institutional')">
            <span>📜 Páginas Institucionais</span>
        </button>
        <button type="button" id="tabBtnAdmin" class="btn-admin-cancel" style="padding: 8px 16px; font-size: 0.82rem;" onclick="switchBgTab('admin')">
            <span>🛡️ Painel Administrativo</span>
        </button>
    </div>

    <!-- PAINEL 1: FUNDO GLOBAL DO SITE (TODAS AS PÁGINAS) -->
    <div id="panelBgSite" class="bg-settings-panel">
        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
            <div>
                <!-- OPÇÃO 1: UPLOAD DO COMPUTADOR -->
                <div style="background: rgba(255,255,255,0.02); border: 2px dashed rgba(202, 161, 92, 0.3); border-radius: 12px; padding: 22px; text-align: center; margin-bottom: 20px;">
                    <div style="font-size: 2rem; margin-bottom: 6px;">📁</div>
                    <h4 style="font-size: 0.95rem; color: #fff; margin: 0 0 4px;">Enviar Fundo Global do seu Computador</h4>
                    <p style="font-size: 0.78rem; color: #9e978e; margin: 0 0 14px;">A imagem se estende suavemente por todas as páginas da loja (Home, Boutique, Checkout, etc.)</p>
                    <label class="btn-admin-gold" style="cursor: pointer; display: inline-flex; align-items: center; gap: 8px; padding: 9px 18px; font-size: 0.84rem;">
                        <span>Escolher Arquivo no Meu Dispositivo</span>
                        <input type="file" id="fileUploadSite" accept="image/*" style="display: none;" onchange="uploadBgFile(this, 'site_bg_image')">
                    </label>
                    <div id="uploadStatusSite" style="font-size: 0.8rem; color: #caa15c; margin-top: 10px; display: none;"></div>
                </div>

                <!-- OPÇÃO 2: URL DIRETA OU CAMINHO -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="inputSiteBg">Ou insira a URL direta da imagem:</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" id="inputSiteBg" class="form-input" 
                               value="<?= \App\Core\View::e($customSettings['site_bg_image'] ?? '') ?>" 
                               placeholder="Ex: /assets/images/backgrounds/marble-noir.svg ou link HTTPS externo"
                               oninput="updateBgPreview('site')">
                        <button type="button" class="btn-admin-secondary" onclick="document.getElementById('inputSiteBg').value = ''; updateBgPreview('site');">
                            <span>Limpar</span>
                        </button>
                    </div>
                </div>

                <!-- OPÇÃO 3: GALERIA DE TEXTURAS NOBRES PRONTAS -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #caa15c; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">✦ Texturas de Alta Joalheria Prontas para Usar:</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 10px;">
                        <button type="button" class="bg-preset-btn" onclick="applyPresetToTarget('site', '/assets/images/backgrounds/marble-noir.svg', 'repeat')" style="background: #191715; border: 1px solid rgba(202,161,92,0.3); border-radius: 8px; padding: 10px; cursor: pointer; text-align: left;">
                            <div style="height: 40px; border-radius: 4px; background: url('/assets/images/backgrounds/marble-noir.svg') center/cover; margin-bottom: 6px; border: 1px solid rgba(255,255,255,0.1);"></div>
                            <span style="font-size: 0.72rem; color: #fff; display: block; font-weight: 600;">Mármore Noir & Ouro</span>
                        </button>
                        <button type="button" class="bg-preset-btn" onclick="applyPresetToTarget('site', '/assets/images/backgrounds/carbon-noir.svg', 'repeat')" style="background: #141312; border: 1px solid rgba(202,161,92,0.3); border-radius: 8px; padding: 10px; cursor: pointer; text-align: left;">
                            <div style="height: 40px; border-radius: 4px; background: url('/assets/images/backgrounds/carbon-noir.svg') center/auto repeat; margin-bottom: 6px; border: 1px solid rgba(255,255,255,0.1);"></div>
                            <span style="font-size: 0.72rem; color: #fff; display: block; font-weight: 600;">Carbono Quiet Luxury</span>
                        </button>
                        <button type="button" class="bg-preset-btn" onclick="applyPresetToTarget('site', '/assets/images/backgrounds/stardust-noir.svg', 'repeat')" style="background: #0f0e0d; border: 1px solid rgba(202,161,92,0.3); border-radius: 8px; padding: 10px; cursor: pointer; text-align: left;">
                            <div style="height: 40px; border-radius: 4px; background: url('/assets/images/backgrounds/stardust-noir.svg') center/cover; margin-bottom: 6px; border: 1px solid rgba(255,255,255,0.1);"></div>
                            <span style="font-size: 0.72rem; color: #fff; display: block; font-weight: 600;">Poeira Dourada ✦</span>
                        </button>
                        <button type="button" class="bg-preset-btn" onclick="applyPresetToTarget('site', '/assets/images/hero/hero-caminhos.jpg', 'cover')" style="background: #14161a; border: 1px solid rgba(202,161,92,0.3); border-radius: 8px; padding: 10px; cursor: pointer; text-align: left;">
                            <div style="height: 40px; border-radius: 4px; background: url('/assets/images/hero/hero-caminhos.jpg') center/cover; margin-bottom: 6px; border: 1px solid rgba(255,255,255,0.1);"></div>
                            <span style="font-size: 0.72rem; color: #fff; display: block; font-weight: 600;">Joia Divinium nobre</span>
                        </button>
                        <button type="button" class="bg-preset-btn" onclick="applyPresetToTarget('site', '/assets/images/brand/IMG_8356.jpg', 'cover')" style="background: #14161a; border: 1px solid rgba(202,161,92,0.3); border-radius: 8px; padding: 10px; cursor: pointer; text-align: left;">
                            <div style="height: 40px; border-radius: 4px; background: url('/assets/images/brand/IMG_8356.jpg') center/cover; margin-bottom: 6px; border: 1px solid rgba(255,255,255,0.1);"></div>
                            <span style="font-size: 0.72rem; color: #fff; display: block; font-weight: 600;">Ateliê de Ourives</span>
                        </button>
                    </div>
                </div>

                <!-- CONTROLES ADICIONAIS: OPACIDADE DO ESCURECIMENTO & REPETIÇÃO -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px;">
                    <div class="form-group" style="margin: 0;">
                        <label for="inputSiteOverlay">
                            Escurecimento para Contraste: <strong id="overlayValSite"><?= round(((float)($customSettings['site_bg_overlay'] ?? '0.75')) * 100) ?>%</strong>
                        </label>
                        <input type="range" id="inputSiteOverlay" min="0.10" max="0.95" step="0.05" 
                               value="<?= \App\Core\View::e($customSettings['site_bg_overlay'] ?? '0.75') ?>"
                               style="width: 100%; accent-color: #caa15c;"
                               oninput="document.getElementById('overlayValSite').innerText = Math.round(this.value * 100) + '%'; updateBgPreview('site');">
                    </div>

                    <div class="form-group" style="margin: 0;">
                        <label for="inputSiteRepeat">Modo de Enquadramento:</label>
                        <select id="inputSiteRepeat" class="form-select" onchange="updateBgPreview('site')">
                            <option value="cover" <?= ($customSettings['site_bg_repeat'] ?? 'cover') === 'cover' ? 'selected' : '' ?>>Cobrir Tela Inteira (Fixado ao Rolar)</option>
                            <option value="repeat" <?= ($customSettings['site_bg_repeat'] ?? '') === 'repeat' ? 'selected' : '' ?>>Repetir Padrão Texturizado (Seamless)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- COLUNA DIREITA: PRÉ-VISUALIZAÇÃO AO VIVO EM TEMPO REAL -->
            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #ded7cf; margin-bottom: 8px;">Prévia da Loja com este Fundo:</label>
                <div id="previewCardSite" style="height: 380px; border-radius: 14px; border: 1px solid #caa15c; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.6);">
                    <div id="previewOverlaySite" style="position: absolute; inset: 0; background: rgba(13,12,11,0.75); z-index: 1;"></div>
                    <div style="position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-family: var(--font-serif); font-size: 1.1rem; color: #fff; letter-spacing: 1px; font-weight: 700;">DIVINIUM</span>
                        <span style="font-size: 0.68rem; background: rgba(202,161,92,0.25); color: #caa15c; border: 1px solid #caa15c; padding: 2px 8px; border-radius: 12px; font-weight: 600;">VISÃO GLOBAL</span>
                    </div>
                    <div style="position: relative; z-index: 2; text-align: center; margin: auto 0;">
                        <h4 style="font-family: var(--font-serif); font-size: 1.3rem; color: #fff; margin: 0 0 6px;">Joias que Revelam</h4>
                        <span style="font-family: var(--font-serif); font-size: 1.15rem; color: #caa15c; font-weight: 700; display: block; margin-bottom: 10px;">o Luxo Silencioso</span>
                        <div style="display: inline-block; background: rgba(255,255,255,0.08); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15); border-radius: 10px; padding: 10px 16px;">
                            <span style="font-size: 0.72rem; color: #ded7cf; display: block;">Bracelete Caminhos • R$ 380,00</span>
                        </div>
                    </div>
                    <div style="position: relative; z-index: 2; text-align: center;">
                        <span style="font-size: 0.72rem; color: #9e978e;">Fundo aplicado instantaneamente em todas as páginas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PAINEL 2: FUNDO DO HERO (BANNER PRINCIPAL DA HOME) -->
    <div id="panelBgHero" class="bg-settings-panel" style="display: none;">
        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
            <div>
                <div style="background: rgba(255,255,255,0.02); border: 2px dashed rgba(202, 161, 92, 0.3); border-radius: 12px; padding: 22px; text-align: center; margin-bottom: 20px;">
                    <div style="font-size: 2rem; margin-bottom: 6px;">👑</div>
                    <h4 style="font-size: 0.95rem; color: #fff; margin: 0 0 4px;">Enviar Fundo Exclusivo do Hero (Banner de Abertura)</h4>
                    <p style="font-size: 0.78rem; color: #9e978e; margin: 0 0 14px;">Substitui o gradiente padrão da primeira dobra da Home por uma fotografia espetacular.</p>
                    <label class="btn-admin-gold" style="cursor: pointer; display: inline-flex; align-items: center; gap: 8px; padding: 9px 18px; font-size: 0.84rem;">
                        <span>Escolher Foto para o Hero</span>
                        <input type="file" id="fileUploadHero" accept="image/*" style="display: none;" onchange="uploadBgFile(this, 'hero_bg_image')">
                    </label>
                    <div id="uploadStatusHero" style="font-size: 0.8rem; color: #caa15c; margin-top: 10px; display: none;"></div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="inputHeroBg">URL direta da imagem do Hero:</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" id="inputHeroBg" class="form-input" 
                               value="<?= \App\Core\View::e($customSettings['hero_bg_image'] ?? '') ?>" 
                               placeholder="Ex: /assets/images/hero/hero-caminhos.jpg"
                               oninput="updateBgPreview('hero')">
                        <button type="button" class="btn-admin-secondary" onclick="document.getElementById('inputHeroBg').value = ''; updateBgPreview('hero');">
                            <span>Limpar</span>
                        </button>
                    </div>
                </div>

                <div class="form-group" style="margin: 0;">
                    <label for="inputHeroOverlay">
                        Intensidade do Escurecimento do Hero: <strong id="overlayValHero"><?= round(((float)($customSettings['hero_bg_overlay'] ?? '0.60')) * 100) ?>%</strong>
                    </label>
                    <input type="range" id="inputHeroOverlay" min="0.10" max="0.95" step="0.05" 
                           value="<?= \App\Core\View::e($customSettings['hero_bg_overlay'] ?? '0.60') ?>"
                           style="width: 100%; accent-color: #caa15c;"
                           oninput="document.getElementById('overlayValHero').innerText = Math.round(this.value * 100) + '%'; updateBgPreview('hero');">
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #ded7cf; margin-bottom: 8px;">Prévia do Hero Principal:</label>
                <div id="previewCardHero" style="height: 380px; border-radius: 14px; border: 1px solid #caa15c; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 24px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.6);">
                    <div id="previewOverlayHero" style="position: absolute; inset: 0; background: rgba(13,12,11,0.60); z-index: 1;"></div>
                    <div style="position: relative; z-index: 2;">
                        <span style="font-size: 0.72rem; color: #caa15c; font-weight: 700; letter-spacing: 1px; display: block; margin-bottom: 6px;">✦ HAUTE JOAILLERIE</span>
                        <h3 style="font-family: var(--font-serif); font-size: 1.5rem; color: #fff; margin: 0 0 12px;">Joias que Revelam o Luxo Silencioso</h3>
                        <a href="#" class="btn-admin-gold" style="padding: 8px 16px; font-size: 0.8rem; pointer-events: none;">Explorar Coleção</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PAINEL 3: FUNDO DA BOUTIQUE & CATÁLOGO (/loja) -->
    <div id="panelBgStore" class="bg-settings-panel" style="display: none;">
        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
            <div>
                <div style="background: rgba(255,255,255,0.02); border: 2px dashed rgba(202, 161, 92, 0.3); border-radius: 12px; padding: 22px; text-align: center; margin-bottom: 20px;">
                    <div style="font-size: 2rem; margin-bottom: 6px;">🛍️</div>
                    <h4 style="font-size: 0.95rem; color: #fff; margin: 0 0 4px;">Enviar Fundo Exclusivo da Boutique (/loja)</h4>
                    <p style="font-size: 0.78rem; color: #9e978e; margin: 0 0 14px;">Dê à sua vitrine oficial uma imagem ou textura personalizada para envolver os clientes.</p>
                    <label class="btn-admin-gold" style="cursor: pointer; display: inline-flex; align-items: center; gap: 8px; padding: 9px 18px; font-size: 0.84rem;">
                        <span>Escolher Imagem para a Boutique</span>
                        <input type="file" id="fileUploadStore" accept="image/*" style="display: none;" onchange="uploadBgFile(this, 'store_bg_image')">
                    </label>
                    <div id="uploadStatusStore" style="font-size: 0.8rem; color: #caa15c; margin-top: 10px; display: none;"></div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="inputStoreBg">URL direta da imagem da Boutique:</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" id="inputStoreBg" class="form-input" 
                               value="<?= \App\Core\View::e($customSettings['store_bg_image'] ?? '') ?>" 
                               placeholder="Ex: /assets/images/backgrounds/carbon-noir.svg"
                               oninput="updateBgPreview('store')">
                        <button type="button" class="btn-admin-secondary" onclick="document.getElementById('inputStoreBg').value = ''; updateBgPreview('store');">
                            <span>Limpar</span>
                        </button>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group" style="margin: 0;">
                        <label for="inputStoreOverlay">
                            Escurecimento da Boutique: <strong id="overlayValStore"><?= round(((float)($customSettings['store_bg_overlay'] ?? '0.75')) * 100) ?>%</strong>
                        </label>
                        <input type="range" id="inputStoreOverlay" min="0.10" max="0.95" step="0.05" 
                               value="<?= \App\Core\View::e($customSettings['store_bg_overlay'] ?? '0.75') ?>"
                               style="width: 100%; accent-color: #caa15c;"
                               oninput="document.getElementById('overlayValStore').innerText = Math.round(this.value * 100) + '%'; updateBgPreview('store');">
                    </div>

                    <div class="form-group" style="margin: 0;">
                        <label for="inputStoreRepeat">Modo de Enquadramento:</label>
                        <select id="inputStoreRepeat" class="form-select" onchange="updateBgPreview('store')">
                            <option value="cover" <?= ($customSettings['store_bg_repeat'] ?? 'cover') === 'cover' ? 'selected' : '' ?>>Cobrir Tela (Cover)</option>
                            <option value="repeat" <?= ($customSettings['store_bg_repeat'] ?? '') === 'repeat' ? 'selected' : '' ?>>Repetir Textura (Repeat)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #ded7cf; margin-bottom: 8px;">Prévia da Boutique (/loja):</label>
                <div id="previewCardStore" style="height: 380px; border-radius: 14px; border: 1px solid #caa15c; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.6);">
                    <div id="previewOverlayStore" style="position: absolute; inset: 0; background: rgba(13,12,11,0.75); z-index: 1;"></div>
                    <div style="position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-family: var(--font-serif); font-size: 1.1rem; color: #fff; font-weight: 700;">VITRINE OFICIAL</span>
                        <span style="font-size: 0.68rem; color: #4ade80;">● Catálogo Ativo</span>
                    </div>
                    <div style="position: relative; z-index: 2; background: rgba(20,19,18,0.85); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 14px;">
                        <span style="font-size: 0.72rem; color: #caa15c; font-weight: 600; display: block;">Joia Autoral</span>
                        <span style="font-size: 0.95rem; font-weight: 700; color: #fff;">Anel Gema Imperial</span>
                    </div>
                    <div style="position: relative; z-index: 2; text-align: center;">
                        <span style="font-size: 0.72rem; color: #9e978e;">Fundo exclusivo da página /loja</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PAINEL 4: FUNDO DA PÁGINA DO PRODUTO (/produto/{slug}) -->
    <div id="panelBgProduct" class="bg-settings-panel" style="display: none;">
        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
            <div>
                <div style="background: rgba(255,255,255,0.02); border: 2px dashed rgba(202, 161, 92, 0.3); border-radius: 12px; padding: 22px; text-align: center; margin-bottom: 20px;">
                    <div style="font-size: 2rem; margin-bottom: 6px;">💎</div>
                    <h4 style="font-size: 0.95rem; color: #fff; margin: 0 0 4px;">Enviar Fundo da Página de Detalhe da Joia</h4>
                    <p style="font-size: 0.78rem; color: #9e978e; margin: 0 0 14px;">Define a atmosfera da tela de apresentação individual de cada produto.</p>
                    <label class="btn-admin-gold" style="cursor: pointer; display: inline-flex; align-items: center; gap: 8px; padding: 9px 18px; font-size: 0.84rem;">
                        <span>Escolher Imagem para Detalhe da Joia</span>
                        <input type="file" id="fileUploadProduct" accept="image/*" style="display: none;" onchange="uploadBgFile(this, 'product_bg_image')">
                    </label>
                    <div id="uploadStatusProduct" style="font-size: 0.8rem; color: #caa15c; margin-top: 10px; display: none;"></div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="inputProductBg">URL direta da imagem do Produto:</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" id="inputProductBg" class="form-input" 
                               value="<?= \App\Core\View::e($customSettings['product_bg_image'] ?? '') ?>" 
                               placeholder="Ex: /assets/images/backgrounds/stardust-noir.svg"
                               oninput="updateBgPreview('product')">
                        <button type="button" class="btn-admin-secondary" onclick="document.getElementById('inputProductBg').value = ''; updateBgPreview('product');">
                            <span>Limpar</span>
                        </button>
                    </div>
                </div>

                <div class="form-group" style="margin: 0;">
                    <label for="inputProductOverlay">
                        Intensidade do Escurecimento: <strong id="overlayValProduct"><?= round(((float)($customSettings['product_bg_overlay'] ?? '0.75')) * 100) ?>%</strong>
                    </label>
                    <input type="range" id="inputProductOverlay" min="0.10" max="0.95" step="0.05" 
                           value="<?= \App\Core\View::e($customSettings['product_bg_overlay'] ?? '0.75') ?>"
                           style="width: 100%; accent-color: #caa15c;"
                           oninput="document.getElementById('overlayValProduct').innerText = Math.round(this.value * 100) + '%'; updateBgPreview('product');">
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #ded7cf; margin-bottom: 8px;">Prévia da Página da Joia:</label>
                <div id="previewCardProduct" style="height: 380px; border-radius: 14px; border: 1px solid #caa15c; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.6);">
                    <div id="previewOverlayProduct" style="position: absolute; inset: 0; background: rgba(13,12,11,0.75); z-index: 1;"></div>
                    <div style="position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.72rem; color: #caa15c; font-weight: 700;">Prata 925 • Gema Rara</span>
                        <span style="font-size: 0.68rem; color: #ded7cf;">#SKU-772</span>
                    </div>
                    <div style="position: relative; z-index: 2; text-align: center;">
                        <h4 style="font-family: var(--font-serif); font-size: 1.25rem; color: #fff; margin: 0 0 4px;">Colar Sinfonia Mineral</h4>
                        <span style="color: #caa15c; font-weight: 700; font-size: 1.1rem;">R$ 490,00</span>
                    </div>
                    <div style="position: relative; z-index: 2; text-align: center;">
                        <span style="font-size: 0.72rem; color: #9e978e;">Fundo da visualização de cada joia</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PAINEL 5: FUNDO DO CHECKOUT SEGURO (/checkout) -->
    <div id="panelBgCheckout" class="bg-settings-panel" style="display: none;">
        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
            <div>
                <div style="background: rgba(255,255,255,0.02); border: 2px dashed rgba(202, 161, 92, 0.3); border-radius: 12px; padding: 22px; text-align: center; margin-bottom: 20px;">
                    <div style="font-size: 2rem; margin-bottom: 6px;">🔒</div>
                    <h4 style="font-size: 0.95rem; color: #fff; margin: 0 0 4px;">Enviar Fundo do Checkout Seguro (/checkout)</h4>
                    <p style="font-size: 0.78rem; color: #9e978e; margin: 0 0 14px;">Ambiente de alta sofisticação para finalização das compras e pagamentos Pix.</p>
                    <label class="btn-admin-gold" style="cursor: pointer; display: inline-flex; align-items: center; gap: 8px; padding: 9px 18px; font-size: 0.84rem;">
                        <span>Escolher Imagem para o Checkout</span>
                        <input type="file" id="fileUploadCheckout" accept="image/*" style="display: none;" onchange="uploadBgFile(this, 'checkout_bg_image')">
                    </label>
                    <div id="uploadStatusCheckout" style="font-size: 0.8rem; color: #caa15c; margin-top: 10px; display: none;"></div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="inputCheckoutBg">URL direta da imagem do Checkout:</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" id="inputCheckoutBg" class="form-input" 
                               value="<?= \App\Core\View::e($customSettings['checkout_bg_image'] ?? '') ?>" 
                               placeholder="Ex: /assets/images/backgrounds/marble-noir.svg"
                               oninput="updateBgPreview('checkout')">
                        <button type="button" class="btn-admin-secondary" onclick="document.getElementById('inputCheckoutBg').value = ''; updateBgPreview('checkout');">
                            <span>Limpar</span>
                        </button>
                    </div>
                </div>

                <div class="form-group" style="margin: 0;">
                    <label for="inputCheckoutOverlay">
                        Intensidade do Escurecimento: <strong id="overlayValCheckout"><?= round(((float)($customSettings['checkout_bg_overlay'] ?? '0.75')) * 100) ?>%</strong>
                    </label>
                    <input type="range" id="inputCheckoutOverlay" min="0.10" max="0.95" step="0.05" 
                           value="<?= \App\Core\View::e($customSettings['checkout_bg_overlay'] ?? '0.75') ?>"
                           style="width: 100%; accent-color: #caa15c;"
                           oninput="document.getElementById('overlayValCheckout').innerText = Math.round(this.value * 100) + '%'; updateBgPreview('checkout');">
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #ded7cf; margin-bottom: 8px;">Prévia do Checkout Seguro:</label>
                <div id="previewCardCheckout" style="height: 380px; border-radius: 14px; border: 1px solid #caa15c; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.6);">
                    <div id="previewOverlayCheckout" style="position: absolute; inset: 0; background: rgba(13,12,11,0.75); z-index: 1;"></div>
                    <div style="position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 700; color: #fff; font-size: 0.9rem;">CHECKOUT SEGURO</span>
                        <span style="font-size: 0.7rem; color: #4ade80;">🔒 SSL 256 bits</span>
                    </div>
                    <div style="position: relative; z-index: 2; background: rgba(20,19,18,0.85); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 14px;">
                        <span style="font-size: 0.72rem; color: #caa15c; font-weight: 600; display: block;">Pagamento Pix Instantâneo</span>
                        <span style="font-size: 0.82rem; color: #fff;">Desconto Nobre de 5% Aplicado</span>
                    </div>
                    <div style="position: relative; z-index: 2; text-align: center;">
                        <span style="font-size: 0.72rem; color: #9e978e;">Fundo do ambiente de pagamento</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PAINEL 6: FUNDO DAS PÁGINAS INSTITUCIONAIS -->
    <div id="panelBgInstitutional" class="bg-settings-panel" style="display: none;">
        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
            <div>
                <div style="background: rgba(255,255,255,0.02); border: 2px dashed rgba(202, 161, 92, 0.3); border-radius: 12px; padding: 22px; text-align: center; margin-bottom: 20px;">
                    <div style="font-size: 2rem; margin-bottom: 6px;">📜</div>
                    <h4 style="font-size: 0.95rem; color: #fff; margin: 0 0 4px;">Enviar Fundo das Páginas Institucionais</h4>
                    <p style="font-size: 0.78rem; color: #9e978e; margin: 0 0 14px;">Aplica-se às páginas de Políticas de Privacidade, Termos de Uso, Garantia, Trocas e Devoluções.</p>
                    <label class="btn-admin-gold" style="cursor: pointer; display: inline-flex; align-items: center; gap: 8px; padding: 9px 18px; font-size: 0.84rem;">
                        <span>Escolher Imagem para Institucional</span>
                        <input type="file" id="fileUploadInstitutional" accept="image/*" style="display: none;" onchange="uploadBgFile(this, 'institutional_bg_image')">
                    </label>
                    <div id="uploadStatusInstitutional" style="font-size: 0.8rem; color: #caa15c; margin-top: 10px; display: none;"></div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="inputInstitutionalBg">URL direta da imagem Institucional:</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" id="inputInstitutionalBg" class="form-input" 
                               value="<?= \App\Core\View::e($customSettings['institutional_bg_image'] ?? '') ?>" 
                               placeholder="Ex: /assets/images/backgrounds/carbon-noir.svg"
                               oninput="updateBgPreview('institutional')">
                        <button type="button" class="btn-admin-secondary" onclick="document.getElementById('inputInstitutionalBg').value = ''; updateBgPreview('institutional');">
                            <span>Limpar</span>
                        </button>
                    </div>
                </div>

                <div class="form-group" style="margin: 0;">
                    <label for="inputInstitutionalOverlay">
                        Intensidade do Escurecimento: <strong id="overlayValInstitutional"><?= round(((float)($customSettings['institutional_bg_overlay'] ?? '0.75')) * 100) ?>%</strong>
                    </label>
                    <input type="range" id="inputInstitutionalOverlay" min="0.10" max="0.95" step="0.05" 
                           value="<?= \App\Core\View::e($customSettings['institutional_bg_overlay'] ?? '0.75') ?>"
                           style="width: 100%; accent-color: #caa15c;"
                           oninput="document.getElementById('overlayValInstitutional').innerText = Math.round(this.value * 100) + '%'; updateBgPreview('institutional');">
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #ded7cf; margin-bottom: 8px;">Prévia das Políticas & Termos:</label>
                <div id="previewCardInstitutional" style="height: 380px; border-radius: 14px; border: 1px solid #caa15c; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.6);">
                    <div id="previewOverlayInstitutional" style="position: absolute; inset: 0; background: rgba(13,12,11,0.75); z-index: 1;"></div>
                    <div style="position: relative; z-index: 2; text-align: center;">
                        <span style="font-size: 0.68rem; background: rgba(202,161,92,0.2); color: #caa15c; padding: 2px 8px; border-radius: 10px;">POLÍTICAS DE GARANTIA</span>
                        <h4 style="font-family: var(--font-serif); font-size: 1.2rem; color: #fff; margin: 8px 0 0;">Transparência & Confiança</h4>
                    </div>
                    <div style="position: relative; z-index: 2; background: rgba(20,19,18,0.85); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 12px; font-size: 0.72rem; color: #ded7cf;">
                        Certificado oficial de garantia vitalícia do metal nobre prata 925.
                    </div>
                    <div style="position: relative; z-index: 2; text-align: center;">
                        <span style="font-size: 0.72rem; color: #9e978e;">Fundo das páginas institucionais</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PAINEL 7: FUNDO DO PAINEL ADMINISTRATIVO -->
    <div id="panelBgAdmin" class="bg-settings-panel" style="display: none;">
        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
            <div>
                <div style="background: rgba(255,255,255,0.02); border: 2px dashed rgba(202, 161, 92, 0.3); border-radius: 12px; padding: 22px; text-align: center; margin-bottom: 20px;">
                    <div style="font-size: 2rem; margin-bottom: 6px;">🛡️</div>
                    <h4 style="font-size: 0.95rem; color: #fff; margin: 0 0 4px;">Enviar Fundo Personalizado para o Painel de Controle</h4>
                    <p style="font-size: 0.78rem; color: #9e978e; margin: 0 0 14px;">Dê a este painel administrativo a mesma identidade visual da sua boutique.</p>
                    <label class="btn-admin-gold" style="cursor: pointer; display: inline-flex; align-items: center; gap: 8px; padding: 9px 18px; font-size: 0.84rem;">
                        <span>Escolher Imagem para o Painel</span>
                        <input type="file" id="fileUploadAdmin" accept="image/*" style="display: none;" onchange="uploadBgFile(this, 'admin_bg_image')">
                    </label>
                    <div id="uploadStatusAdmin" style="font-size: 0.8rem; color: #caa15c; margin-top: 10px; display: none;"></div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="inputAdminBg">URL direta da imagem do Painel:</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" id="inputAdminBg" class="form-input" 
                               value="<?= \App\Core\View::e($customSettings['admin_bg_image'] ?? '') ?>" 
                               placeholder="Ex: /assets/images/backgrounds/marble-noir.svg"
                               oninput="updateBgPreview('admin')">
                        <button type="button" class="btn-admin-secondary" onclick="document.getElementById('inputAdminBg').value = ''; updateBgPreview('admin');">
                            <span>Limpar</span>
                        </button>
                    </div>
                </div>

                <div class="form-group" style="margin: 0;">
                    <label for="inputAdminOverlay">
                        Intensidade do Escurecimento do Painel: <strong id="overlayValAdmin"><?= round(((float)($customSettings['admin_bg_overlay'] ?? '0.85')) * 100) ?>%</strong>
                    </label>
                    <input type="range" id="inputAdminOverlay" min="0.50" max="0.95" step="0.05" 
                           value="<?= \App\Core\View::e($customSettings['admin_bg_overlay'] ?? '0.85') ?>"
                           style="width: 100%; accent-color: #caa15c;"
                           oninput="document.getElementById('overlayValAdmin').innerText = Math.round(this.value * 100) + '%'; updateBgPreview('admin');">
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.8rem; font-weight: 600; color: #ded7cf; margin-bottom: 8px;">Prévia do Painel Administrativo:</label>
                <div id="previewCardAdmin" style="height: 380px; border-radius: 14px; border: 1px solid #caa15c; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.6);">
                    <div id="previewOverlayAdmin" style="position: absolute; inset: 0; background: rgba(13,12,11,0.85); z-index: 1;"></div>
                    <div style="position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 700; color: #fff; font-size: 0.9rem;">DIVINIUM ADMIN</span>
                        <span style="font-size: 0.7rem; color: #4ade80;">● Online</span>
                    </div>
                    <div style="position: relative; z-index: 2; background: rgba(20,22,26,0.8); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 14px;">
                        <span style="font-size: 0.75rem; color: #caa15c; font-weight: 600; display: block;">Métricas de Vendas</span>
                        <span style="font-size: 1.2rem; font-weight: 700; color: #fff;">R$ 14.820,00</span>
                    </div>
                    <div style="position: relative; z-index: 2; text-align: center;">
                        <span style="font-size: 0.7rem; color: #9e978e;">Fundo do painel de controle</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            throw new Error('O servidor respondeu com formato inesperado. Se sua sessão expirou, recarregue a página.');
        }

        if (data.auth_required) {
            alert('Sua sessão administrativa expirou. Por favor, faça login novamente.');
            window.location.reload();
            return;
        }

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

function toggleSpecificColorsSection() {
    const content = document.getElementById('contentSpecificColors');
    const arrow = document.getElementById('arrowSpecificColors');
    if (content) {
        if (content.style.display === 'none') {
            content.style.display = 'grid';
            if (arrow) arrow.style.transform = 'rotate(0deg)';
        } else {
            content.style.display = 'none';
            if (arrow) arrow.style.transform = 'rotate(-90deg)';
        }
    }
}

function updateCustomPreview() {
    const primary = document.getElementById('custom_color_primary')?.value || '#CAA15C';
    const accent = document.getElementById('custom_color_accent')?.value || '#E0C080';
    const bg = document.getElementById('custom_color_bg')?.value || '#0D0C0B';
    const surface = document.getElementById('custom_color_surface')?.value || '#141312';
    const text = document.getElementById('custom_color_text')?.value || '#F4EFEA';
    const textMuted = document.getElementById('custom_color_text_muted')?.value || '#9E978E';
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
    const sampleText = document.getElementById('previewSampleText');
    if (sampleText) {
        sampleText.style.color = text;
    }
    const sampleMuted = document.getElementById('previewSampleMuted');
    if (sampleMuted) {
        sampleMuted.style.color = textMuted;
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
    document.getElementById('custom_color_text').value = '#F4EFEA';
    document.getElementById('picker_text').value = '#F4EFEA';
    document.getElementById('custom_color_text_muted').value = '#9E978E';
    document.getElementById('picker_text_muted').value = '#9E978E';
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
        const rawText = await res.text();
        let data;
        try {
            data = JSON.parse(rawText);
        } catch (jsonErr) {
            console.error('Resposta não-JSON do servidor:', rawText);
            throw new Error('O servidor respondeu com formato inesperado. Se sua sessão expirou, recarregue a página.');
        }

        if (data.auth_required) {
            alert('Sua sessão administrativa expirou. Por favor, faça login novamente.');
            window.location.reload();
            return;
        }

        if (data.success) {
            alert(data.message || 'Cores e identidade visual salvas com sucesso em todas as páginas!');
            window.location.reload();
        } else {
            alert(data.error || 'Erro ao salvar tema personalizado.');
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
    }
}

// ==============================================================================
// GESTÃO DA IMAGEM DE FUNDO & TEXTURAS NOBRES (7 DESTINOS)
// ==============================================================================
let currentBgTab = 'site';
const allBgTabs = ['site', 'hero', 'store', 'product', 'checkout', 'institutional', 'admin'];

function switchBgTab(tab) {
    currentBgTab = tab;
    allBgTabs.forEach(t => {
        const btn = document.getElementById('tabBtn' + t.charAt(0).toUpperCase() + t.slice(1));
        const panel = document.getElementById('panelBg' + t.charAt(0).toUpperCase() + t.slice(1));
        if (btn) {
            btn.className = (t === tab) ? 'btn-admin-gold' : 'btn-admin-cancel';
        }
        if (panel) {
            panel.style.display = (t === tab) ? 'block' : 'none';
        }
    });
    updateBgPreview(tab);
}

function updateBgPreview(target) {
    const capitalize = s => s.charAt(0).toUpperCase() + s.slice(1);
    const targetCap = capitalize(target);

    const inputBg = document.getElementById('input' + targetCap + 'Bg');
    const inputOverlay = document.getElementById('input' + targetCap + 'Overlay');
    const inputRepeat = document.getElementById('input' + targetCap + 'Repeat');
    const card = document.getElementById('previewCard' + targetCap);
    const overlayEl = document.getElementById('previewOverlay' + targetCap);

    if (card && overlayEl) {
        const bg = inputBg?.value?.trim() || '';
        const overlay = inputOverlay?.value || 0.75;
        const repeat = inputRepeat?.value || 'cover';

        if (bg) {
            card.style.backgroundImage = `url('${bg}')`;
            card.style.backgroundSize = repeat === 'repeat' ? 'auto' : 'cover';
            card.style.backgroundRepeat = repeat === 'repeat' ? 'repeat' : 'no-repeat';
            card.style.backgroundPosition = 'center';
        } else {
            card.style.backgroundImage = 'none';
            card.style.backgroundColor = (target === 'hero') ? '#141312' : '#0d0c0b';
        }
        overlayEl.style.backgroundColor = `rgba(13, 12, 11, ${overlay})`;
    }
}

function applyPresetToTarget(target, url, repeat = 'cover') {
    const capitalize = s => s.charAt(0).toUpperCase() + s.slice(1);
    const targetCap = capitalize(target);

    const input = document.getElementById('input' + targetCap + 'Bg');
    const rep = document.getElementById('input' + targetCap + 'Repeat');
    if (input) input.value = url;
    if (rep) rep.value = repeat;
    updateBgPreview(target);
}

async function uploadBgFile(input, targetKey) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    
    const keyMap = {
        'site_bg_image': { target: 'site', status: 'uploadStatusSite' },
        'hero_bg_image': { target: 'hero', status: 'uploadStatusHero' },
        'store_bg_image': { target: 'store', status: 'uploadStatusStore' },
        'product_bg_image': { target: 'product', status: 'uploadStatusProduct' },
        'checkout_bg_image': { target: 'checkout', status: 'uploadStatusCheckout' },
        'institutional_bg_image': { target: 'institutional', status: 'uploadStatusInstitutional' },
        'admin_bg_image': { target: 'admin', status: 'uploadStatusAdmin' }
    };

    const config = keyMap[targetKey] || { target: 'site', status: 'uploadStatusSite' };
    const statusEl = document.getElementById(config.status);
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
            const capitalize = s => s.charAt(0).toUpperCase() + s.slice(1);
            const inputField = document.getElementById('input' + capitalize(config.target) + 'Bg');
            if (inputField) {
                inputField.value = data.url;
            }
            updateBgPreview(config.target);
            if (statusEl) {
                statusEl.innerHTML = '✓ Imagem enviada com sucesso! Salva em: <strong>' + data.url + '</strong>';
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

async function resetActiveBackground() {
    const keyMap = {
        'site': { key: 'site_bg_image', label: 'Fundo Global do Site' },
        'hero': { key: 'hero_bg_image', label: 'Fundo do Hero da Home' },
        'store': { key: 'store_bg_image', label: 'Fundo da Boutique / Loja' },
        'product': { key: 'product_bg_image', label: 'Fundo do Detalhe da Joia' },
        'checkout': { key: 'checkout_bg_image', label: 'Fundo do Checkout Seguro' },
        'institutional': { key: 'institutional_bg_image', label: 'Fundo das Páginas Institucionais' },
        'admin': { key: 'admin_bg_image', label: 'Fundo do Painel Administrativo' }
    };

    const current = keyMap[currentBgTab] || keyMap['site'];
    if (!confirm('Deseja realmente remover o ' + current.label + ' e restaurar o padrão do tema?')) {
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';

    const formData = new FormData();
    formData.append('target_key', current.key);
    formData.append('_csrf_token', csrfToken);

    try {
        const res = await fetch('/admin/temas/remover-bg', {
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
            const capitalize = s => s.charAt(0).toUpperCase() + s.slice(1);
            const inputField = document.getElementById('input' + capitalize(currentBgTab) + 'Bg');
            if (inputField) {
                inputField.value = '';
            }
            updateBgPreview(currentBgTab);
            alert(data.message || 'Fundo removido com sucesso!');
        } else {
            alert(data.error || 'Erro ao remover imagem de fundo.');
        }
    } catch (err) {
        alert('Erro de conexão: ' + err.message);
    }
}

async function saveBackgroundSettings() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';

    const formData = new FormData();
    formData.append('_csrf_token', csrfToken);

    // Global
    formData.append('site_bg_image', document.getElementById('inputSiteBg')?.value || '');
    formData.append('site_bg_overlay', document.getElementById('inputSiteOverlay')?.value || '0.75');
    formData.append('site_bg_repeat', document.getElementById('inputSiteRepeat')?.value || 'cover');

    // Hero
    formData.append('hero_bg_image', document.getElementById('inputHeroBg')?.value || '');
    formData.append('hero_bg_overlay', document.getElementById('inputHeroOverlay')?.value || '0.60');

    // Boutique
    formData.append('store_bg_image', document.getElementById('inputStoreBg')?.value || '');
    formData.append('store_bg_overlay', document.getElementById('inputStoreOverlay')?.value || '0.75');
    formData.append('store_bg_repeat', document.getElementById('inputStoreRepeat')?.value || 'cover');

    // Product
    formData.append('product_bg_image', document.getElementById('inputProductBg')?.value || '');
    formData.append('product_bg_overlay', document.getElementById('inputProductOverlay')?.value || '0.75');

    // Checkout
    formData.append('checkout_bg_image', document.getElementById('inputCheckoutBg')?.value || '');
    formData.append('checkout_bg_overlay', document.getElementById('inputCheckoutOverlay')?.value || '0.75');

    // Institutional
    formData.append('institutional_bg_image', document.getElementById('inputInstitutionalBg')?.value || '');
    formData.append('institutional_bg_overlay', document.getElementById('inputInstitutionalOverlay')?.value || '0.75');

    // Admin
    formData.append('admin_bg_image', document.getElementById('inputAdminBg')?.value || '');
    formData.append('admin_bg_overlay', document.getElementById('inputAdminOverlay')?.value || '0.85');

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
            alert(data.message || 'Configurações de imagem de fundo salvas com sucesso em todas as páginas!');
            window.location.reload();
        } else {
            alert(data.error || 'Erro ao salvar imagens de fundo.');
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    updateCustomPreview();
    allBgTabs.forEach(t => updateBgPreview(t));
});
</script>
