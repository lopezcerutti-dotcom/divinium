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
            drawerBody.innerHTML = cart.map((item, idx) => `
                <div class="cart-item-row" style="display:flex;gap:14px;padding:14px 0;border-bottom:1px solid rgba(255,255,255,0.08);align-items:center;">
                    ${item.image ? `<img src="${item.image}" alt="${item.name}" style="width:52px;height:52px;object-fit:cover;border-radius:6px;border:1px solid #737B85;">` : ''}
                    <div style="flex-grow:1;">
                        <h4 style="font-family:var(--font-serif, 'Cormorant Garamond', serif);font-size:1.05rem;color:#ffffff;margin:0 0 2px;">${item.name}</h4>
                        ${item.stone ? `<span style="font-size:0.75rem;color:#CBD0D6;display:block;margin-bottom:4px;">Pedra: ${item.stone}</span>` : ''}
                        <span style="font-size:0.82rem;color:#ded7cf;">${(item.priceCents / 100).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'})} x ${item.qty}</span>
                    </div>
                    <button type="button" onclick="removeFromCart('${item.itemKey || item.id}')" style="background:none;border:none;color:#8e8880;cursor:pointer;font-size:1.2rem;padding:4px;" title="Remover item">&times;</button>
                </div>
            `).join('');
        }
    }

    localStorage.setItem('divinium_cart', JSON.stringify(cart));
}

function addToCart(idOrObj, name, priceCents) {
    let id, itemName, price, img, stone;
    if (typeof idOrObj === 'object' && idOrObj !== null) {
        id = idOrObj.id;
        itemName = idOrObj.name;
        price = idOrObj.price || idOrObj.priceCents || 0;
        img = idOrObj.image || '';
        stone = idOrObj.stone || '';
    } else {
        id = idOrObj;
        itemName = name;
        price = priceCents || 0;
        img = '';
        stone = '';
    }

    const itemKey = String(id) + (stone ? ('-' + stone) : '');
    const existing = cart.find(i => (i.itemKey || i.id) === itemKey);
    if (existing) {
        existing.qty++;
    } else {
        cart.push({
            id: id,
            itemKey: itemKey,
            name: itemName,
            priceCents: price,
            image: img,
            stone: stone,
            qty: 1
        });
    }
    updateCartUI();
    openDrawer();
}

function removeFromCart(keyOrId) {
    cart = cart.filter(i => (i.itemKey || i.id) !== keyOrId);
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
    const collectionBlocks = document.querySelectorAll('.collection-block');

    pills.forEach(pill => {
        pill.addEventListener('click', () => {
            pills.forEach(p => p.classList.remove('active'));
            pill.classList.add('active');

            const filter = pill.getAttribute('data-filter');

            if (collectionBlocks.length > 0) {
                collectionBlocks.forEach(block => {
                    const category = block.getAttribute('data-category');
                    if (filter === 'all' || category === filter) {
                        block.style.display = 'block';
                    } else {
                        block.style.display = 'none';
                    }
                });
            } else if (cards.length > 0) {
                cards.forEach(card => {
                    const category = card.getAttribute('data-category');
                    if (filter === 'all' || category === filter) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
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
    setupMobileMenu();
    setupHeaderSearch();

    const cartTrigger = document.getElementById('cartTrigger');
    const closeBtn = document.getElementById('closeCartDrawer');
    const overlay = document.getElementById('cartDrawerOverlay');

    if (cartTrigger) cartTrigger.addEventListener('click', openDrawer);
    if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
    if (overlay) overlay.addEventListener('click', closeDrawer);
});

// 5.1 CONTROLE DO MENU RESPONSIVO MOBILE (DRAWER & ACORDEÃO)
function setupMobileMenu() {
    const toggleBtn = document.getElementById('mobileMenuToggle');
    const drawer = document.getElementById('mobileNavDrawer');
    const overlay = document.getElementById('mobileNavOverlay');
    const closeBtn = document.getElementById('closeMobileNavBtn');
    const loginBtn = document.getElementById('mobileLoginBtn');
    const accordionToggle = document.getElementById('mobileCollectionsToggle');
    const accordion = document.getElementById('mobileCollectionsAccordion');

    if (!toggleBtn || !drawer || !overlay) return;

    function openMobileMenu() {
        drawer.classList.add('active');
        overlay.classList.add('active');
        toggleBtn.classList.add('active');
        toggleBtn.setAttribute('aria-expanded', 'true');
        drawer.setAttribute('aria-hidden', 'false');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        drawer.classList.remove('active');
        overlay.classList.remove('active');
        toggleBtn.classList.remove('active');
        toggleBtn.setAttribute('aria-expanded', 'false');
        drawer.setAttribute('aria-hidden', 'true');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    toggleBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (drawer.classList.contains('active')) {
            closeMobileMenu();
        } else {
            openMobileMenu();
        }
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            closeMobileMenu();
        });
    }

    overlay.addEventListener('click', (e) => {
        e.preventDefault();
        closeMobileMenu();
    });

    // Fechar ao teclar Esc
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && drawer.classList.contains('active')) {
            closeMobileMenu();
        }
    });

    // Acordeão de Coleções
    if (accordionToggle && accordion) {
        accordionToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const isOpened = accordion.classList.toggle('active');
            accordionToggle.setAttribute('aria-expanded', isOpened ? 'true' : 'false');
        });
    }

    // Fechar automaticamente ao clicar em qualquer link interno
    const navLinks = drawer.querySelectorAll('a');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            closeMobileMenu();
        });
    });

    // Botão de login administrativo pelo menu mobile
    if (loginBtn) {
        loginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            closeMobileMenu();
            const loginTrigger = document.getElementById('loginModalTrigger');
            if (loginTrigger) {
                loginTrigger.click();
            } else {
                const modalOverlay = document.getElementById('loginModalOverlay');
                if (modalOverlay) {
                    modalOverlay.classList.add('active');
                    modalOverlay.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                }
            }
        });
    }

    // Suporte a swipe para fechar (deslizar para a esquerda)
    let touchStartX = 0;
    let touchStartY = 0;

    drawer.addEventListener('touchstart', (e) => {
        if (e.changedTouches && e.changedTouches[0]) {
            touchStartX = e.changedTouches[0].screenX;
            touchStartY = e.changedTouches[0].screenY;
        }
    }, { passive: true });

    drawer.addEventListener('touchend', (e) => {
        if (e.changedTouches && e.changedTouches[0]) {
            const touchEndX = e.changedTouches[0].screenX;
            const touchEndY = e.changedTouches[0].screenY;
            const diffX = touchStartX - touchEndX;
            const diffY = Math.abs(touchStartY - touchEndY);
            // Se deslizou mais de 45px para a esquerda e menos na vertical
            if (diffX > 45 && diffY < 60) {
                closeMobileMenu();
            }
        }
    }, { passive: true });

    window.closeMobileMenu = closeMobileMenu;
    window.openMobileMenu = openMobileMenu;
}

function setupHeaderSearch() {
    const searchBtns = document.querySelectorAll('.search-trigger');
    searchBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            if (window.location.pathname.startsWith('/loja')) {
                const searchInput = document.getElementById('storeSearchInput');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                window.location.href = '/loja#storeSearchInput';
            }
        });
    });
}

// 5.3 BUSCA EM TEMPO REAL NO CATÁLOGO DA BOUTIQUE
function handleStoreLiveSearch(rawQuery) {
    const q = (rawQuery || '').toLowerCase().trim();
    const clearBtn = document.getElementById('storeSearchClear');
    if (clearBtn) {
        clearBtn.style.display = q ? 'inline-block' : 'none';
    }

    const grid = document.getElementById('storeGrid');
    const emptyBox = document.getElementById('storeEmptyState');
    if (!grid) return;

    const cards = grid.querySelectorAll('.store-product-card');
    let visibleCount = 0;

    cards.forEach(card => {
        const text = (card.innerText || '').toLowerCase();
        if (!q || text.includes(q)) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    if (emptyBox) {
        emptyBox.style.display = (visibleCount === 0) ? 'block' : 'none';
    }
}

function clearStoreSearch() {
    const input = document.getElementById('storeSearchInput');
    if (input) {
        input.value = '';
        handleStoreLiveSearch('');
        input.focus();
    }
}

window.handleStoreLiveSearch = handleStoreLiveSearch;
window.clearStoreSearch = clearStoreSearch;

// 7. CONTROLE DO SELETOR DE TEMAS (SUPORTE À ÁREA ADMINISTRATIVA)
function setupThemeToggle() {
    const availableThemes = [
        { id: 'theme-silver-noir', name: 'Prata Nobre & Noir', color: '#A7ADB4' },
        { id: 'theme-pearl-platinum', name: 'Branco Perolado & Platina', color: '#CBD0D6' },
        { id: 'theme-nude-pearl', name: 'Nude & Bege Pérola', color: '#C8B9AE' },
        { id: 'theme-contemporary-graphite', name: 'Prata & Grafite', color: '#9DA5AE' }
    ];

    const themeClassList = [
        'theme-silver-noir', 'theme-pearl-platinum', 'theme-nude-pearl', 'theme-contemporary-graphite',
        'theme-gold', 'theme-silver', 'theme-emerald', 'theme-rose'
    ];

    const legacyMap = {
        'theme-gold': 'theme-silver-noir',
        'theme-silver': 'theme-pearl-platinum',
        'theme-emerald': 'theme-nude-pearl',
        'theme-rose': 'theme-contemporary-graphite'
    };

    function applyTheme(themeId) {
        const resolvedId = legacyMap[themeId] || themeId;
        const found = availableThemes.find(t => t.id === resolvedId) || availableThemes[0];
        
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

// ==============================================================================
// 11. CARROSSEL DE DEPOIMENTOS DA HOME (QUIET LUXURY TESTIMONIALS CAROUSEL)
// ==============================================================================
function initTestimonialsCarousel() {
    const container = document.getElementById('testimonialsCarousel');
    if (!container) return;

    const slides = Array.from(container.querySelectorAll('.testimonial-slide'));
    const dots = Array.from(container.querySelectorAll('.testimonials-carousel-dot'));
    const prevBtn = document.getElementById('testimonialsCarouselPrev');
    const nextBtn = document.getElementById('testimonialsCarouselNext');

    if (slides.length <= 1) {
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
        const dotsWrap = document.getElementById('testimonialsCarouselDots');
        if (dotsWrap) dotsWrap.style.display = 'none';
        return;
    }

    let currentIndex = 0;
    let autoplayTimer = null;
    let isPaused = false;

    const autoplayEnabled = container.getAttribute('data-autoplay') !== '0';
    const intervalMs = parseInt(container.getAttribute('data-interval'), 10) || 6000;

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

    // Pausar ao passar o mouse ou focar
    container.addEventListener('mouseenter', () => { isPaused = true; });
    container.addEventListener('mouseleave', () => { isPaused = false; });
    container.addEventListener('focusin', () => { isPaused = true; });
    container.addEventListener('focusout', () => { isPaused = false; });

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
            if (Math.abs(diffX) > 35) {
                if (diffX > 0) {
                    nextSlide(); // swipe esquerda -> próximo depoimento
                } else {
                    prevSlide(); // swipe direita -> depoimento anterior
                }
                resetAutoplay();
            }
        }
        setTimeout(() => { isPaused = false; }, 1200);
    }, { passive: true });

    // Suporte a teclas direcionais
    container.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') {
            nextSlide();
            resetAutoplay();
        } else if (e.key === 'ArrowLeft') {
            prevSlide();
            resetAutoplay();
        }
    });

    startAutoplay();
}

function initAllCarousels() {
    initHeroCarousel();
    initTestimonialsCarousel();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAllCarousels);
} else {
    initAllCarousels();
}


