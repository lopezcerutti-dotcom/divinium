<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? \App\Core\View::e($pageTitle) : 'Painel de Controle | Divinium' ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/app.css?v=<?= time() ?>">
    <link rel="stylesheet" href="/assets/css/admin.css?v=<?= time() ?>">

    <meta name="csrf-token" content="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">
    <?php
        $adminBgImage = \App\Models\Setting::get('admin_bg_image');
        $adminBgOverlay = \App\Models\Setting::get('admin_bg_overlay', '0.85');
    ?>
    <?php if (!empty($adminBgImage)): ?>
    <style id="admin-dynamic-bg">
        body.admin-theme {
            background-color: #252525 !important;
            background-image: linear-gradient(rgba(37, 37, 37, <?= (float)$adminBgOverlay ?>), rgba(37, 37, 37, <?= min(0.98, (float)$adminBgOverlay + 0.10) ?>)), url('<?= \App\Core\View::e($adminBgImage) ?>') !important;
            background-size: cover !important;
            background-attachment: fixed !important;
            background-position: center center !important;
        }
        .admin-main, .admin-card {
            background-color: rgba(32, 32, 32, 0.85) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
        }
    </style>
    <?php endif; ?>
</head>
<body class="admin-theme">

    <div class="admin-wrapper">
        
        <!-- SIDEBAR DE NAVEGAÇÃO EXECUTIVA -->
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <a href="/admin" class="admin-logo">
                    <span class="brand-title">DIVINIUM</span>
                    <span class="brand-badge">PAINEL DE CONTROLE</span>
                </a>
            </div>

            <nav class="admin-nav">
                <div class="nav-section-label">GESTÃO DO CATÁLOGO</div>
                <a href="/admin" class="admin-nav-item <?= ($_SERVER['REQUEST_URI'] ?? '') === '/admin' ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span>Visão Geral</span>
                </a>

                <a href="/admin/produtos" class="admin-nav-item <?= (str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/produtos') || str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/produtos-exibicao')) ? 'active' : '' ?>" title="Gestão Centralizada: Catálogo de Joias, Estoque & Canais de Venda">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    <span>Catálogo, Estoque & Canais</span>
                </a>

                <a href="/admin/categorias" class="admin-nav-item <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/categorias') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                    <span>Categorias</span>
                </a>

                <a href="/admin/lancamentos" class="admin-nav-item <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/lancamentos') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    <span>Lançamentos</span>
                </a>

                <a href="/admin/boutique" class="admin-nav-item <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/boutique') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span>Personalizar Boutique</span>
                </a>

                <div class="nav-section-label">MÉTRICAS & CONVERSÃO</div>
                <a href="/admin/metricas" class="admin-nav-item <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/metricas') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                    <span>Dashboard de Acompanhamento</span>
                </a>

                <div class="nav-section-label">DESIGN & IDENTIDADE</div>
                <a href="/admin/temas" class="admin-nav-item <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/temas') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a10 10 0 0 1 10 10c0 5.52-4.48 10-10 10"></path></svg>
                    <span>Cores, Fundo & Temas</span>
                </a>

                <a href="/admin/landing-page" class="admin-nav-item <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/landing-page') && !str_contains($_SERVER['REQUEST_URI'] ?? '', 'sec-mobile-menu') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    <span>Textos & Landing Page</span>
                </a>

                <a href="/admin/landing-page?tab=sec-mobile-menu" class="admin-nav-item <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'sec-mobile-menu') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                    <span>Menu Mobile (Celular)</span>
                </a>

                <div class="nav-section-label">ATALHOS & ACESSO</div>
                <a href="/" target="_blank" class="admin-nav-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    <span>Ver Loja Ao Vivo</span>
                </a>

                <a href="/admin/logout" class="admin-nav-item logout-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span>Sair do Painel</span>
                </a>
            </nav>

            <div class="admin-user-pill">
                <div class="user-avatar">AD</div>
                <div class="user-info">
                    <span class="user-name"><?= \App\Core\View::e($adminUser['name'] ?? 'Administrador') ?></span>
                    <span class="user-role">Super Admin</span>
                </div>
            </div>
        </aside>

        <!-- ÁREA DE CONTEÚDO PRINCIPAL -->
        <main class="admin-main">
            <header class="admin-topbar">
                <div class="topbar-left">
                    <div class="topbar-breadcrumb">Painel Administrativo &rsaquo; Divinium</div>
                    <h1 class="admin-page-title"><?= \App\Core\View::e($pageTitle ?? 'Painel de Controle') ?></h1>
                </div>
                <div class="topbar-right">
                    <div class="store-status-pill">
                        <span class="pulse-dot"></span>
                        <span>Loja Online</span>
                    </div>
                    <a href="/" target="_blank" class="btn-admin-secondary" style="gap: 6px;">
                        <span>Ver Loja</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </a>
                </div>
            </header>

            <?php if (!empty($_SESSION['flash_success'])): ?>
                <div class="admin-alert alert-success">
                    <span><?= \App\Core\View::e($_SESSION['flash_success']) ?></span>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="admin-alert alert-error">
                    <span><?= \App\Core\View::e($_SESSION['flash_error']) ?></span>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <div class="admin-content-area">
                <?= $content ?>
            </div>
        </main>

    </div>

    <!-- SCRIPT PROTETOR GLOBAL DE REQUISIÇÕES ADMINISTRATIVAS -->
    <script>
    window.escapeHtml = function(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    };

    (function() {
        // Blindagem do Response.prototype.json para nunca quebrar com HTML inesperado
        const originalJson = Response.prototype.json;
        Response.prototype.json = async function() {
            try {
                const text = await this.text();
                try {
                    const data = JSON.parse(text);
                    if (data && data.auth_required) {
                        alert('Sua sessão de administrador expirou. Por favor, recarregue a página para efetuar login novamente.');
                        window.location.reload();
                    }
                    return data;
                } catch (parseErr) {
                    console.error('Resposta não-JSON interceptada:', text);
                    return {
                        success: false,
                        error: 'O servidor retornou uma resposta inesperada. Por favor, recarregue a página.'
                    };
                }
            } catch (err) {
                return originalJson.call(this);
            }
        };

        // Blindagem do fetch para sempre injetar headers AJAX e CSRF no painel
        const originalFetch = window.fetch;
        window.fetch = async function(resource, init) {
            init = init || {};
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!init.headers) {
                init.headers = {};
            }

            if (init.headers instanceof Headers) {
                if (!init.headers.has('X-Requested-With')) init.headers.set('X-Requested-With', 'XMLHttpRequest');
                if (csrfToken && !init.headers.has('X-CSRF-TOKEN')) init.headers.set('X-CSRF-TOKEN', csrfToken);
            } else if (!Array.isArray(init.headers) && typeof init.headers === 'object') {
                if (!init.headers['X-Requested-With']) init.headers['X-Requested-With'] = 'XMLHttpRequest';
                if (csrfToken && !init.headers['X-CSRF-TOKEN']) init.headers['X-CSRF-TOKEN'] = csrfToken;
            }

            return originalFetch.call(this, resource, init);
        };
    })();
    </script>
</body>
</html>
