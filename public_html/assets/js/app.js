/**
 * DIVINIUM - JAVASCRIPT PRINCIPAL
 * Interatividade da Home, Gaveta do Carrinho e Consentimento LGPD
 */

// 1. ESTADO DO CARRINHO EM LOCALSTORAGE (OFFLINE-FIRST / RESPONSIVO)
let cart = JSON.parse(localStorage.getItem('divinium_cart')) || [];

function updateCartUI() {
    const counter = document.getElementById('cartCount');
    const drawerBody = document.getElementById('cartDrawerBody');
    const subtotalEl = document.getElementById('cartSubtotalVal');

    const totalItems = cart.reduce((acc, item) => acc + item.qty, 0);
    const subtotalCents = cart.reduce((acc, item) => acc + (item.priceCents * item.qty), 0);

    if (counter) {
        counter.textContent = totalItems;
        counter.style.display = totalItems > 0 ? 'flex' : 'none';
    }

    if (subtotalEl) {
        subtotalEl.textContent = (subtotalCents / 100).toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        });
    }

    if (drawerBody) {
        if (cart.length === 0) {
            drawerBody.innerHTML = `
                <div class="empty-cart-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="empty-icon"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    <p>Sua sacola está vazia no momento.</p>
                    <a href="#collections" class="btn-discover" onclick="closeDrawer()">Descobrir Coleção</a>
                </div>
            `;
        } else {
            drawerBody.innerHTML = cart.map(item => `
                <div class="cart-item-row" style="display:flex;gap:16px;padding:16px 0;border-bottom:1px solid #eee;align-items:center;">
                    <div style="flex-grow:1;">
                        <h4 style="font-family:'Cormorant Garamond', serif;font-size:1.15rem;margin-bottom:4px;">${item.name}</h4>
                        <span style="font-size:0.85rem;color:#666;">${(item.priceCents / 100).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'})} x ${item.qty}</span>
                    </div>
                    <button onclick="removeFromCart('${item.id}')" style="background:none;border:none;color:#999;cursor:pointer;font-size:1.2rem;">&times;</button>
                </div>
            `).join('');
        }
    }

    localStorage.setItem('divinium_cart', JSON.stringify(cart));
}

function addToCart(id, name, priceCents) {
    const existing = cart.find(i => i.id === id);
    if (existing) {
        existing.qty++;
    } else {
        cart.push({ id, name, priceCents, qty: 1 });
    }
    updateCartUI();
    openDrawer();
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    updateCartUI();
}

// 2. CONTROLE DA GAVETA LATERAL DO CARRINHO (DRAWER)
function openDrawer() {
    const drawer = document.getElementById('cartDrawer');
    const overlay = document.getElementById('cartDrawerOverlay');
    if (drawer && overlay) {
        drawer.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeDrawer() {
    const drawer = document.getElementById('cartDrawer');
    const overlay = document.getElementById('cartDrawerOverlay');
    if (drawer && overlay) {
        drawer.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// 3. FILTROS DA VITRINE (SHOWCASE FILTER PILLS)
function setupFilters() {
    const pills = document.querySelectorAll('.filter-pill');
    const cards = document.querySelectorAll('.product-card');

    pills.forEach(pill => {
        pill.addEventListener('click', () => {
            pills.forEach(p => p.classList.remove('active'));
            pill.classList.add('active');

            const filter = pill.getAttribute('data-filter');

            cards.forEach(card => {
                const category = card.getAttribute('data-category');
                if (filter === 'all' || category === filter) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
}

// 4. BANNER DE COOKIES LGPD
function setupCookieBanner() {
    const banner = document.getElementById('cookieBanner');
    const acceptBtn = document.getElementById('acceptCookiesBtn');

    if (!localStorage.getItem('divinium_cookies_accepted')) {
        if (banner) banner.style.display = 'flex';
    } else {
        if (banner) banner.style.display = 'none';
    }

    if (acceptBtn) {
        acceptBtn.addEventListener('click', () => {
            localStorage.setItem('divinium_cookies_accepted', 'true');
            if (banner) banner.style.display = 'none';
        });
    }
}

// 5. SELETOR INTERATIVO DE GEMAS NO HERO (HERO MINERAL SWITCHER)
function setupHeroMineralSwitcher() {
    const pills = document.querySelectorAll('.mineral-pill');
    const heroImg = document.getElementById('heroSpotlightImg');
    const heroTitle = document.getElementById('heroSpotlightTitle');
    const heroDesc = document.getElementById('heroSpotlightDesc');
    const heroPrice = document.getElementById('heroSpotlightPrice');
    const heroPix = document.getElementById('heroSpotlightPix');
    const quickBuyBtn = document.getElementById('heroQuickBuyBtn');

    if (!pills.length || !heroImg) return;

    pills.forEach(pill => {
        pill.addEventListener('click', () => {
            pills.forEach(p => p.classList.remove('active'));
            pill.classList.add('active');

            const title = pill.dataset.title;
            const desc = pill.dataset.desc;
            const price = pill.dataset.price;
            const pix = pill.dataset.pix;
            const img = pill.dataset.img;
            const id = pill.dataset.id;
            const priceCents = parseInt(pill.dataset.cents || '38000', 10);

            // Transição visual suave (crossfade & scale micro-animation)
            heroImg.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
            heroImg.style.opacity = '0.2';
            heroImg.style.transform = 'scale(0.96)';

            setTimeout(() => {
                heroImg.src = img;
                heroImg.alt = title;
                if (heroTitle) heroTitle.textContent = title;
                if (heroDesc) heroDesc.textContent = desc;
                if (heroPrice) heroPrice.textContent = price;
                if (heroPix) heroPix.textContent = pix;
                
                if (quickBuyBtn) {
                    quickBuyBtn.onclick = () => addToCart(id, title, priceCents);
                }

                heroImg.style.opacity = '1';
                heroImg.style.transform = 'scale(1)';
            }, 220);
        });
    });
}

// INICIALIZAÇÃO
document.addEventListener('DOMContentLoaded', () => {
    setupThemeToggle();
    setupShopeeClickTracking();
    updateCartUI();
    setupFilters();
    setupCookieBanner();
    setupHeroMineralSwitcher();
    setupAdminLoginModal();

    const cartTrigger = document.getElementById('cartTrigger');
    const closeBtn = document.getElementById('closeCartDrawer');
    const overlay = document.getElementById('cartDrawerOverlay');

    if (cartTrigger) cartTrigger.addEventListener('click', openDrawer);
    if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
    if (overlay) overlay.addEventListener('click', closeDrawer);
});

// 7. CONTROLE DO SELETOR DE TEMAS MULTI-PALETA (4 TEMAS LUXO)
function setupThemeToggle() {
    const btn = document.getElementById('themeToggleBtn');
    const label = document.getElementById('themeToggleLabel');

    const availableThemes = [
        { id: 'theme-gold', name: 'Ouro Imperial', color: '#caa15c' },
        { id: 'theme-silver', name: 'Prata Nobre', color: '#cbd5e1' },
        { id: 'theme-emerald', name: 'Esmeralda Real', color: '#10b981' },
        { id: 'theme-rose', name: 'Rose Gold', color: '#fb7185' }
    ];

    const themeClassList = availableThemes.map(t => t.id);

    function applyTheme(themeId) {
        const found = availableThemes.find(t => t.id === themeId) || availableThemes[0];
        
        // Limpar classes de temas anteriores
        themeClassList.forEach(cls => {
            document.documentElement.classList.remove(cls);
            document.body.classList.remove(cls);
        });

        // Adicionar nova classe
        document.documentElement.classList.add(found.id);
        document.body.classList.add(found.id);

        if (label) {
            label.textContent = found.name;
        }

        const gem = document.querySelector('.theme-toggle-gem');
        if (gem) {
            gem.style.boxShadow = `0 0 10px ${found.color}`;
        }

        localStorage.setItem('divinium_theme', found.id);
    }

    const savedTheme = localStorage.getItem('divinium_theme') || 'theme-gold';
    applyTheme(savedTheme);

    if (btn) {
        btn.addEventListener('click', () => {
            const current = localStorage.getItem('divinium_theme') || 'theme-gold';
            let nextIndex = availableThemes.findIndex(t => t.id === current) + 1;
            if (nextIndex >= availableThemes.length) {
                nextIndex = 0;
            }
            applyTheme(availableThemes[nextIndex].id);
        });
    }

    window.diviniumApplyTheme = applyTheme;
}

// 8. RASTREAMENTO INTELIGENTE DE CLIQUES NA SHOPEE (ANALYTICS)
function setupShopeeClickTracking() {
    document.addEventListener('click', (e) => {
        const target = e.target.closest('a[href*="shopee.com.br"], [data-track-product-id]');
        if (!target) return;

        const productId = target.getAttribute('data-track-product-id') || 
            target.closest('[data-track-product-id]')?.getAttribute('data-track-product-id') || 
            '1';
        const productName = target.getAttribute('data-track-product-name') || 
            target.closest('[data-track-product-name]')?.getAttribute('data-track-product-name') || 
            target.getAttribute('title') || 
            'Joia Divinium Shopee';
        const source = target.getAttribute('data-track-source') || 
            (window.location.pathname.includes('/loja') ? 'store' : 'landing_page');

        const payload = JSON.stringify({
            product_id: parseInt(productId, 10) || 1,
            product_name: productName,
            source: source
        });

        if (navigator.sendBeacon) {
            navigator.sendBeacon('/api/track/click', new Blob([payload], { type: 'application/json' }));
        } else {
            fetch('/api/track/click', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: payload,
                keepalive: true
            }).catch(() => {});
        }
    });
}

// 6. MODAL DE LOGIN ADMINISTRATIVO
function setupAdminLoginModal() {
    const trigger = document.getElementById('loginModalTrigger');
    const overlay = document.getElementById('loginModalOverlay');
    const closeBtn = document.getElementById('closeLoginModal');

    function openModal() {
        if (overlay) {
            overlay.classList.add('active');
            overlay.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            const emailInput = document.getElementById('adminEmail');
            if (emailInput) setTimeout(() => emailInput.focus(), 100);
        }
    }

    function closeModal() {
        if (overlay) {
            overlay.classList.remove('active');
            overlay.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }
    }

    if (trigger) {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            openModal();
        });
    }

    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    if (overlay) {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) closeModal();
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && overlay && overlay.classList.contains('active')) {
            closeModal();
        }
    });

    if (window.location.search.includes('login=')) {
        openModal();
    }
}

async function handleAdminLogin(e) {
    e.preventDefault();
    const btn = document.getElementById('adminLoginSubmitBtn');
    const errAlert = document.getElementById('loginErrorAlert');
    const form = document.getElementById('adminLoginForm');

    if (errAlert) errAlert.style.display = 'none';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span>Verificando credenciais...</span>';
    }

    const formData = new FormData(form);

    try {
        const response = await fetch('/admin/login', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            if (btn) btn.innerHTML = '<span>Acesso Autorizado! Redirecionando...</span>';
            window.location.href = data.redirect || '/admin';
        } else {
            if (errAlert) {
                errAlert.textContent = data.error || 'Credenciais inválidas.';
                errAlert.style.display = 'block';
            }
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<span>Acessar Painel de Controle</span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
            }
        }
    } catch (err) {
        if (errAlert) {
            errAlert.textContent = 'Erro ao processar o login. Tente novamente.';
            errAlert.style.display = 'block';
        }
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<span>Acessar Painel de Controle</span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
        }
    }
}

