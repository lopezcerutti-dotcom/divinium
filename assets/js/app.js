/**
 * DIVINIUM - JAVASCRIPT PRINCIPAL
 * Interatividade da Home, Gaveta do Carrinho e Consentimento LGPD
 */

// 1. ESTADO DO CARRINHO EM LOCALSTORAGE (OFFLINE-FIRST / NORMALIZADO / REATIVO)
function getNormalizedCart() {
    try {
        const raw = localStorage.getItem('divinium_cart');
        if (!raw) return [];
        const parsed = JSON.parse(raw);
        if (!Array.isArray(parsed)) return [];
        return parsed.map((item, idx) => {
            const pCents = item.priceCents || item.price_cents || (parseFloat(item.price || 0) * 100) || 0;
            const q = parseInt(item.qty || item.quantity || 1, 10);
            const key = item.itemKey || item.sku || (String(item.id || idx) + '_' + (item.stone || ''));
            const img = item.image || item.image_url || '/assets/images/hero/hero-main.webp';
            const stn = item.stone || item.stone_mineral || '';
            return {
                id: item.id || idx,
                itemKey: key,
                sku: item.sku || '',
                name: item.name || 'Joia Divinium',
                stone: stn,
                stone_mineral: stn,
                priceCents: pCents,
                price_cents: pCents,
                price: pCents / 100,
                image: img,
                image_url: img,
                qty: q > 0 ? q : 1,
                quantity: q > 0 ? q : 1
            };
        });
    } catch (e) {
        return [];
    }
}

let cart = getNormalizedCart();

function saveCart(newCart) {
    cart = newCart;
    localStorage.setItem('divinium_cart', JSON.stringify(cart));
    updateCartUI();
    window.dispatchEvent(new CustomEvent('cart:updated', { detail: { cart } }));
}

function updateCartUI() {
    const counter = document.getElementById('cartCount');
    const drawerBody = document.getElementById('cartDrawerBody');
    const subtotalEl = document.getElementById('cartSubtotalVal');

    const totalItems = cart.reduce((acc, item) => acc + (item.qty || 1), 0);
    const subtotalCents = cart.reduce((acc, item) => acc + ((item.priceCents || 0) * (item.qty || 1)), 0);

    if (counter) {
        counter.textContent = totalItems;
        counter.style.display = totalItems > 0 ? 'inline-flex' : 'none';
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
                <div class="empty-cart-state" style="text-align: center; padding: 45px 16px; color: #8e8880;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" class="empty-icon" style="stroke: #CBD0D6; margin-bottom: 14px;"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    <p style="font-size: 0.95rem; margin-bottom: 18px; color: #ded7cf;">Sua sacola está vazia no momento.</p>
                    <a href="/loja" class="btn-discover" onclick="closeDrawer()" style="display: inline-block; padding: 10px 22px; border-radius: 20px; font-size: 0.82rem; font-weight: 600; text-decoration: none; border: 1px solid #CBD0D6; color: #CBD0D6;">Descobrir Coleção</a>
                </div>
            `;
        } else {
            drawerBody.innerHTML = cart.map((item) => `
                <div class="cart-item-row" style="display:flex;gap:12px;padding:14px 0;border-bottom:1px solid rgba(255,255,255,0.08);align-items:center;">
                    ${item.image ? `<img src="${item.image}" alt="${item.name}" style="width:56px;height:56px;object-fit:cover;border-radius:8px;border:1px solid rgba(255,255,255,0.12);background:#1a1918;">` : ''}
                    <div style="flex-grow:1;min-width:0;">
                        <h4 style="font-family:var(--font-serif, 'Cormorant Garamond', serif);font-size:1.02rem;color:#ffffff;margin:0 0 2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${item.name}</h4>
                        ${item.stone ? `<span style="font-size:0.75rem;color:#CBD0D6;display:block;margin-bottom:4px;">Pedra: ${item.stone}</span>` : ''}
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:6px;gap:8px;">
                            <div style="display:inline-flex;align-items:center;border:1px solid rgba(255,255,255,0.16);border-radius:6px;overflow:hidden;background:rgba(0,0,0,0.25);">
                                <button type="button" onclick="changeCartItemQty('${item.itemKey}', -1)" style="background:transparent;border:none;color:#ded7cf;padding:3px 9px;cursor:pointer;font-size:0.95rem;line-height:1;" title="Diminuir quantidade">−</button>
                                <span style="font-size:0.82rem;font-weight:700;color:#ffffff;padding:0 6px;min-width:20px;text-align:center;">${item.qty}</span>
                                <button type="button" onclick="changeCartItemQty('${item.itemKey}', 1)" style="background:transparent;border:none;color:#ded7cf;padding:3px 9px;cursor:pointer;font-size:0.95rem;line-height:1;" title="Aumentar quantidade">+</button>
                            </div>
                            <span style="font-size:0.85rem;color:#ded7cf;font-weight:600;">${((item.priceCents * item.qty) / 100).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'})}</span>
                        </div>
                    </div>
                    <button type="button" onclick="removeFromCart('${item.itemKey}')" style="background:none;border:none;color:#8e8880;cursor:pointer;font-size:1.15rem;padding:6px;border-radius:4px;transition:color 0.2s;" onmouseover="this.style.color='#f87171'" onmouseout="this.style.color='#8e8880'" title="Remover item">&times;</button>
                </div>
            `).join('');
        }
    }
}

function addToCart(idOrObj, name, priceCents, sku, stone, image, qty) {
    let item;
    if (typeof idOrObj === 'object' && idOrObj !== null) {
        item = idOrObj;
    } else {
        item = {
            id: idOrObj,
            name: name,
            priceCents: priceCents || 0,
            sku: sku || '',
            stone: stone || '',
            image: image || '',
            qty: qty || 1
        };
    }

    const pCents = item.priceCents || item.price_cents || (parseFloat(item.price || 0) * 100) || 0;
    const itemQty = parseInt(item.qty || item.quantity || 1, 10);
    const itemStone = item.stone || item.stone_mineral || '';
    const itemSku = item.sku || '';
    const itemKey = item.itemKey || (itemSku !== '' ? itemSku : (String(item.id) + (itemStone ? ('_' + itemStone) : '')));
    const itemImg = item.image || item.image_url || '';

    let current = getNormalizedCart();
    const existing = current.find(i => i.itemKey === itemKey);
    if (existing) {
        existing.qty += itemQty;
        existing.quantity = existing.qty;
    } else {
        current.push({
            id: item.id || Date.now(),
            itemKey: itemKey,
            sku: itemSku,
            name: item.name || 'Joia Divinium',
            stone: itemStone,
            stone_mineral: itemStone,
            priceCents: pCents,
            price_cents: pCents,
            price: pCents / 100,
            image: itemImg,
            image_url: itemImg,
            qty: itemQty > 0 ? itemQty : 1,
            quantity: itemQty > 0 ? itemQty : 1
        });
    }

    saveCart(current);
    if (typeof openDrawer === 'function') {
        openDrawer();
    }
}

function changeCartItemQty(key, delta) {
    let current = getNormalizedCart();
    const idx = current.findIndex(i => i.itemKey === key || String(i.id) === String(key));
    if (idx !== -1) {
        current[idx].qty += delta;
        current[idx].quantity = current[idx].qty;
        if (current[idx].qty <= 0) {
            current.splice(idx, 1);
        }
        saveCart(current);
    }
}

function removeFromCart(keyOrId) {
    let current = getNormalizedCart();
    current = current.filter(i => i.itemKey !== keyOrId && String(i.id) !== String(keyOrId));
    saveCart(current);
}

function clearCart() {
    saveCart([]);
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


