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

// 5. SELETOR INTERATIVO DE GEMAS (DESATIVADO - A HOME AGORA UTILIZA COMPOSIÇÃO EDITORIAL PURA)
function setupHeroMineralSwitcher() {
    // As informações e variações de minerais residem exclusivamente na Boutique (/loja).
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

// 7. CONTROLE DO SELETOR DE TEMAS (SUPORTE À ÁREA ADMINISTRATIVA)
function setupThemeToggle() {
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

        localStorage.setItem('divinium_theme', found.id);
    }

    // Mantém a função disponível globalmente para pré-visualização no painel administrativo (/admin/temas)
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

// ==============================================================================
// 10. CARROSSEL EDITORIAL DA HOME (QUIET LUXURY HERO CAROUSEL)
// ==============================================================================
function initHeroCarousel() {
    const container = document.getElementById('heroCarousel');
    if (!container) return;

    const slides = Array.from(container.querySelectorAll('.hero-carousel-slide'));
    const dots = Array.from(container.querySelectorAll('.hero-carousel-dot'));
    const prevBtn = document.getElementById('heroCarouselPrev');
    const nextBtn = document.getElementById('heroCarouselNext');

    if (slides.length <= 1) {
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
        const dotsWrap = document.getElementById('heroCarouselDots');
        if (dotsWrap) dotsWrap.style.display = 'none';
        return;
    }

    let currentIndex = 0;
    let autoplayTimer = null;
    let isPaused = false;

    const autoplayEnabled = container.getAttribute('data-autoplay') === '1';
    const intervalMs = parseInt(container.getAttribute('data-interval'), 10) || 5000;

    function goToSlide(index) {
        if (index < 0) {
            index = slides.length - 1;
        } else if (index >= slides.length) {
            index = 0;
        }

        slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.add('active');
            } else {
                slide.classList.remove('active');
            }
        });

        dots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });

        currentIndex = index;
    }

    function nextSlide() {
        goToSlide(currentIndex + 1);
    }

    function prevSlide() {
        goToSlide(currentIndex - 1);
    }

    function startAutoplay() {
        if (!autoplayEnabled || autoplayTimer !== null) return;
        autoplayTimer = setInterval(() => {
            if (!isPaused) {
                nextSlide();
            }
        }, intervalMs);
    }

    function stopAutoplay() {
        if (autoplayTimer !== null) {
            clearInterval(autoplayTimer);
            autoplayTimer = null;
        }
    }

    function resetAutoplay() {
        stopAutoplay();
        startAutoplay();
    }

    // Setas manuais
    if (nextBtn) {
        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            nextSlide();
            resetAutoplay();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            prevSlide();
            resetAutoplay();
        });
    }

    // Indicadores minimalistas
    dots.forEach((dot, i) => {
        dot.addEventListener('click', (e) => {
            e.preventDefault();
            goToSlide(i);
            resetAutoplay();
        });
    });

    // Pausar reprodução automática ao passar o cursor ou focar
    container.addEventListener('mouseenter', () => {
        isPaused = true;
    });

    container.addEventListener('mouseleave', () => {
        isPaused = false;
    });

    container.addEventListener('focusin', () => {
        isPaused = true;
    });

    container.addEventListener('focusout', () => {
        isPaused = false;
    });

    // Suporte a swipe por toque (Mobile)
    let touchStartX = 0;
    let touchEndX = 0;

    container.addEventListener('touchstart', (e) => {
        if (e.changedTouches && e.changedTouches[0]) {
            touchStartX = e.changedTouches[0].screenX;
            isPaused = true;
        }
    }, { passive: true });

    container.addEventListener('touchend', (e) => {
        if (e.changedTouches && e.changedTouches[0]) {
            touchEndX = e.changedTouches[0].screenX;
            const diffX = touchStartX - touchEndX;
            if (Math.abs(diffX) > 40) {
                if (diffX > 0) {
                    nextSlide(); // swipe esquerda -> próxima joia
                } else {
                    prevSlide(); // swipe direita -> joia anterior
                }
                resetAutoplay();
            }
        }
        setTimeout(() => {
            isPaused = false;
        }, 1200);
    }, { passive: true });

    // Suporte a teclas direcionais quando focado
    container.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') {
            nextSlide();
            resetAutoplay();
        } else if (e.key === 'ArrowLeft') {
            prevSlide();
            resetAutoplay();
        }
    });

    // Iniciar autoplay
    startAutoplay();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHeroCarousel);
} else {
    initHeroCarousel();
}

