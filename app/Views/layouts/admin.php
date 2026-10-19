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
            background-color: #0d0c0b !important;
            background-image: linear-gradient(rgba(13, 12, 11, <?= (float)$adminBgOverlay ?>), rgba(13, 12, 11, <?= min(0.98, (float)$adminBgOverlay + 0.10) ?>)), url('<?= \App\Core\View::e($adminBgImage) ?>') !important;
            background-size: cover !important;
            background-attachment: fixed !important;
            background-position: center center !important;
        }
        .admin-main, .admin-card {
            background-color: rgba(20, 22, 26, 0.75) !important;
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

                <a href="/admin/produtos" class="admin-nav-item <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/produtos') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    <span>Produtos & Joias</span>
                </a>

                <a href="/admin/categorias" class="admin-nav-item <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/categorias') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                    <span>Categorias</span>
                </a>

                <a href="/admin/boutique" class="admin-nav-item <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/boutique') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span>Personalizar Boutique</span>
                </a>

                <a href="/admin/produtos" onclick="if(typeof openBlingModal==='function'){openBlingModal(event);}" class="admin-nav-item <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '#bling') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    <span>Estoque & Bling ERP</span>
                </a>

                <div class="nav-section-label">MÉTRICAS & CONVERSÃO</div>
                <a href="/admin/metricas" class="admin-nav-item <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/metricas') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                    <span>Dashboard de Acompanhamento</span>
                </a>

                <div class="nav-section-label">DESIGN & IDENTIDADE</div>
                <a href="/admin/temas" class="admin-nav-item <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/temas') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a10 10 0 0 1 10 10c0 5.52-4.48 10-10 10"></path></svg>
                    <span>Gestão de Temas</span>
                </a>

                <a href="/admin/landing-page" class="admin-nav-item <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/landing-page') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    <span>Textos & Landing Page</span>
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

</body>
</html>
