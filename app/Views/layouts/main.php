<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?= isset($pageTitle) ? \App\Core\View::e($pageTitle) . ' | Divinium' : 'Divinium | Joalheria & Luxo Silencioso' ?></title>
    
    <meta name="description" content="<?= isset($pageDescription) ? \App\Core\View::e($pageDescription) : 'Divinium — Joias autorais criadas com metais nobres e pedras preciosas. Design atemporal, acabamento artesanal e a essência do quiet luxury.' ?>">
    <link rel="canonical" href="https://seudominio.com.br<?= \App\Core\View::e($_SERVER['REQUEST_URI'] ?? '/') ?>">

    <!-- Open Graph / Redes Sociais -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Divinium">
    <meta property="og:title" content="<?= isset($pageTitle) ? \App\Core\View::e($pageTitle) : 'Divinium | Joalheria & Luxo Silencioso' ?>">
    <meta property="og:description" content="Joias exclusivas que expressam a beleza atemporal do luxo silencioso.">
    <meta property="og:image" content="/assets/images/hero/hero-main.webp">

    <!-- Tipografia Editorial: Cormorant Garamond (Serif de Luxo) + Plus Jakarta Sans (UI moderna) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Estilos Principais -->
    <link rel="stylesheet" href="/assets/css/app.css?v=<?= time() ?>">

    <!-- Token CSRF para requisições assíncronas -->
    <meta name="csrf-token" content="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">

    <!-- Aplicação do Tema Definido no Painel Administrativo -->
    <script>
        (function() {
            var serverTheme = <?= json_encode(\App\Models\Setting::get('default_theme', 'theme-gold')) ?>;
            document.documentElement.classList.add(serverTheme);
        })();
    </script>

    <!-- Personalização de Fundo Dinâmico (Configurado no Painel Administrativo) -->
    <?php
        $siteBgImage = \App\Models\Setting::get('site_bg_image');
        $siteBgOverlay = \App\Models\Setting::get('site_bg_overlay', '0.75');
        $siteBgRepeat = \App\Models\Setting::get('site_bg_repeat', 'cover');
        $heroBgImage = \App\Models\Setting::get('hero_bg_image');
        $heroBgOverlay = \App\Models\Setting::get('hero_bg_overlay', '0.60');
    ?>
    <?php if (!empty($siteBgImage) || !empty($heroBgImage)): ?>
    <style id="divinium-dynamic-bg">
        <?php if (!empty($siteBgImage)): ?>
        body.luxury-theme {
            background-color: #0d0c0b !important;
            background-image: linear-gradient(rgba(13, 12, 11, <?= (float)$siteBgOverlay ?>), rgba(13, 12, 11, <?= min(0.98, (float)$siteBgOverlay + 0.15) ?>)), url('<?= \App\Core\View::e($siteBgImage) ?>') !important;
            background-size: <?= $siteBgRepeat === 'repeat' ? 'auto' : 'cover' ?> !important;
            background-repeat: <?= $siteBgRepeat === 'repeat' ? 'repeat' : 'no-repeat' ?> !important;
            background-attachment: fixed !important;
            background-position: center center !important;
        }
        <?php endif; ?>

        <?php if (!empty($heroBgImage)): ?>
        .hero-section {
            background-image: linear-gradient(rgba(13, 12, 11, <?= (float)$heroBgOverlay ?>), rgba(13, 12, 11, <?= min(0.95, (float)$heroBgOverlay + 0.25) ?>)), url('<?= \App\Core\View::e($heroBgImage) ?>') !important;
            background-size: cover !important;
            background-position: center center !important;
            background-repeat: no-repeat !important;
        }
        <?php elseif (!empty($siteBgImage)): ?>
        .hero-section {
            background-image: linear-gradient(rgba(13, 12, 11, 0.45), rgba(13, 12, 11, 0.85)), url('<?= \App\Core\View::e($siteBgImage) ?>') !important;
            background-size: cover !important;
            background-position: center center !important;
        }
        <?php endif; ?>
    </style>
    <?php endif; ?>
</head>
<body class="luxury-theme">

    <!-- 1. BARRA SUPERIOR DE ANÚNCIO (TOP BANNER) -->
    <?php if (\App\Models\Setting::get('top_banner_active', '1') === '1'): ?>
    <div class="top-announcement-bar">
        <div class="container top-bar-content">
            <span><?= \App\Models\Setting::get('top_banner_text', '✨ Frete Cortesia com Seguro para todo o Brasil • 5% de Desconto no Pix • Até 10x sem juros') ?></span>
        </div>
    </div>
    <?php endif; ?>

    <!-- 2. CABEÇALHO PRINCIPAL (HEADER NAVEGAÇÃO) -->
    <header class="main-header" id="mainHeader">
        <div class="container header-inner">
            
            <!-- Menu Hambúrguer (Mobile) -->
            <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Abrir Menu de Navegação">
                <span></span>
                <span></span>
            </button>

            <!-- Links de Navegação (Desktop Esquerda) -->
            <nav class="nav-links left-nav" aria-label="Navegação Principal">
                <a href="/" class="nav-link <?= ($_SERVER['REQUEST_URI'] ?? '/') === '/' ? 'active' : '' ?>">Início</a>
                <a href="/loja" class="nav-link <?= str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/loja') ? 'active' : '' ?>">Loja</a>
                <a href="/#about" class="nav-link">Sobre</a>
                <div class="nav-item-dropdown">
                    <a href="/loja" class="nav-link dropdown-toggle">Coleções <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg></a>
                    <div class="dropdown-menu">
                        <a href="/loja">Ver Todas as Peças</a>
                        <a href="/loja#braceletes">Braceletes Exclusivos</a>
                        <a href="/loja#colares">Colares & Pingentes</a>
                        <a href="/loja#aneis">Anéis & Alianças</a>
                    </div>
                </div>
                <a href="/#services" class="nav-link">Garantia</a>
            </nav>

            <!-- Logo Central da Marca -->
            <div class="brand-logo-container">
                <a href="/" class="brand-logo" aria-label="Divinium Home">
                    <span class="logo-text">DIVINIUM</span>
                    <span class="logo-sub">HAUTE JOAILLERIE</span>
                </a>
            </div>

            <!-- Ações do Cabeçalho (Direita) -->
            <div class="header-actions">
                <button class="action-btn search-trigger" aria-label="Buscar produtos">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>

                <a href="/minha-conta/favoritos" class="action-btn wishlist-btn" aria-label="Lista de Desejos">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                </a>

                <button class="action-btn cart-trigger" id="cartTrigger" aria-label="Sacola de Compras">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    <span class="cart-counter" id="cartCount">0</span>
                </button>

                <button type="button" class="login-pill-btn" id="loginModalTrigger" aria-label="Acessar Painel Administrativo">Entrar</button>
            </div>

        </div>
    </header>

    <!-- 3. GAVETA LATERAL DO CARRINHO (CART DRAWER) -->
    <div class="cart-drawer-overlay" id="cartDrawerOverlay"></div>
    <aside class="cart-drawer" id="cartDrawer" aria-label="Sacola de compras">
        <div class="cart-drawer-header">
            <h3>Sua Sacola de Escolhas</h3>
            <button class="close-drawer-btn" id="closeCartDrawer" aria-label="Fechar Sacola">&times;</button>
        </div>
        <div class="cart-drawer-body" id="cartDrawerBody">
            <div class="empty-cart-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="empty-icon"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                <p>Sua sacola está vazia no momento.</p>
                <a href="#collections" class="btn-discover" onclick="closeDrawer()">Descobrir Coleção</a>
            </div>
        </div>
        <div class="cart-drawer-footer">
            <div class="cart-subtotal-row">
                <span>Subtotal:</span>
                <span class="subtotal-val" id="cartSubtotalVal">R$ 0,00</span>
            </div>
            <div class="cart-perks">
                <small>🔒 Pagamento 100% Seguro via ASAAS • Pix com QR Code Instantâneo</small>
            </div>
            <a href="/checkout" class="btn-checkout-primary">Finalizar Compra</a>
        </div>
    </aside>

    <!-- 4. CONTEÚDO PRINCIPAL DA VIEW -->
    <main class="page-content" id="mainContent">
        <?= $content ?>
    </main>

    <!-- 5. RODAPÉ DE ALTA CONVERSÃO & CONFORMIDADE JURÍDICA (DECRETO 7.962/2013 + LGPD) -->
    <footer class="main-footer">
        <div class="container footer-top">
            <div class="footer-col brand-col">
                <span class="footer-logo">DIVINIUM</span>
                <p class="brand-tagline">Joalheria fina concebida para quem aprecia a nobreza atemporal, o luxo silencioso e o design autoral inconfundível.</p>
                <div class="footer-socials">
                    <a href="<?= \App\Core\View::e(\App\Models\Setting::get('footer_instagram', 'https://instagram.com')) ?>" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a>
                    <a href="https://wa.me/<?= \App\Core\View::e(\App\Models\Setting::get('hero_whatsapp_number', '5511999999999')) ?>" target="_blank" rel="noopener" aria-label="WhatsApp">WhatsApp VIP</a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Coleções</h4>
                <ul>
                    <li><a href="/loja">Ver Catálogo Completo</a></li>
                    <li><a href="/loja#braceletes">Pulseiras & Braceletes</a></li>
                    <li><a href="/loja#colares">Colares & Pingentes</a></li>
                    <li><a href="/loja#aneis">Anéis Solitários & Alianças</a></li>
                    <li><a href="/loja">Edições Numeradas</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Atendimento & Concierge</h4>
                <ul>
                    <li><a href="https://wa.me/<?= \App\Core\View::e(\App\Models\Setting::get('hero_whatsapp_number', '5511999999999')) ?>" target="_blank">Atendimento via WhatsApp</a></li>
                    <li><a href="mailto:<?= \App\Core\View::e(\App\Models\Setting::get('footer_email', 'atendimento@divinium.com.br')) ?>"><?= \App\Core\View::e(\App\Models\Setting::get('footer_email', 'atendimento@divinium.com.br')) ?></a></li>
                    <li><a href="/trocas-e-devolucoes">Trocas e Devoluções (CDC 7 Dias)</a></li>
                    <li><a href="/garantia-e-cuidados">Certificado & Cuidados</a></li>
                    <li><a href="/guia-de-medidas">Guia de Medidas de Anéis</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Segurança & Privacidade</h4>
                <ul>
                    <li><a href="/politica-de-privacidade">Política de Privacidade (LGPD)</a></li>
                    <li><a href="/termos-de-uso">Termos de Uso</a></li>
                    <li><a href="/dpo">Encarregado de Dados (DPO)</a></li>
                    <li><span>Segunda a Sexta: 09h às 18h</span></li>
                </ul>
            </div>
        </div>

        <div class="container footer-middle">
            <div class="payment-methods-row">
                <span class="payment-badge">PIX (Aprovação Imediata)</span>
                <span class="payment-badge">Cartão de Crédito em até 10x</span>
                <span class="payment-badge">Boleto Bancário</span>
                <span class="payment-badge">Gateway Homologado ASAAS</span>
            </div>
        </div>

        <div class="container footer-bottom">
            <div class="legal-info">
                <p><?= \App\Core\View::e(\App\Models\Setting::get('footer_legal_info', 'DIVINIUM JOIAS LTDA. • CNPJ: 00.000.000/0001-00 • São Paulo/SP - Brasil')) ?></p>
                <p>Conforme o Decreto nº 7.962/2013 e Lei 8.078/1990 (Código de Defesa do Consumidor). Direito de arrependimento em até 7 dias corridos após o recebimento.</p>
            </div>
            <div class="copyright">
                <p>&copy; <?= date('Y') ?> Divinium. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- 6. BANNER DE COOKIES LGPD -->
    <div class="cookie-banner" id="cookieBanner" role="dialog" aria-live="polite">
        <div class="cookie-content">
            <p>Utilizamos cookies essenciais para garantir a melhor experiência de navegação e compra segura em conformidade com a LGPD. Ao continuar, você concorda com nossos <a href="/termos-de-uso">Termos de Uso</a> e <a href="/politica-de-privacidade">Política de Privacidade</a>.</p>
            <div class="cookie-actions">
                <button class="cookie-btn-accept" id="acceptCookiesBtn">Prosseguir</button>
            </div>
        </div>
    </div>

    <!-- 7. MODAL DE ACESSO AO PAINEL ADMINISTRATIVO -->
    <div class="login-modal-overlay" id="loginModalOverlay" aria-hidden="true">
        <div class="login-modal-box" role="dialog" aria-labelledby="loginModalTitle" aria-modal="true">
            <button class="login-modal-close" id="closeLoginModal" aria-label="Fechar">&times;</button>
            
            <div class="login-modal-brand">
                <span class="brand-sparkle">✦</span>
                <span class="brand-title">DIVINIUM</span>
                <span class="brand-sub">PAINEL DO ADMINISTRADOR</span>
            </div>

            <div class="login-modal-header">
                <h3 id="loginModalTitle">Acesso aos Bastidores</h3>
                <p>Controle de produtos, categorias, minerais e gestão do site.</p>
            </div>

            <!-- Alerta de Erro Dinâmico -->
            <div class="login-error-alert" id="loginErrorAlert" style="display: none;"></div>

            <form id="adminLoginForm" action="/admin/login" method="POST" onsubmit="handleAdminLogin(event)">
                <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">

                <div class="modal-form-group">
                    <label for="adminEmail">E-mail de Acesso</label>
                    <div class="input-icon-wrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <input type="email" id="adminEmail" name="email" class="modal-input" placeholder="seu@email.com" required autocomplete="username">
                    </div>
                </div>

                <div class="modal-form-group">
                    <label for="adminPassword">Senha de Segurança</label>
                    <div class="input-icon-wrap">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <input type="password" id="adminPassword" name="password" class="modal-input" placeholder="Sua senha de acesso" required autocomplete="current-password">
                    </div>
                </div>

                <button type="submit" class="btn-modal-submit" id="adminLoginSubmitBtn">
                    <span>Acessar Painel de Controle</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Scripts da Loja -->
    <script src="/assets/js/app.js?v=<?= time() ?>"></script>
</body>
</html>
