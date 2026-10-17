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
</script>
