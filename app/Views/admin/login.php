<?php
declare(strict_types=1);

use App\Core\View;
use App\Models\Setting;
use App\Middlewares\CsrfMiddleware;

$loginTitle = Setting::get('login_modal_title', 'Acesso aos Bastidores');
$loginSubtitle = Setting::get('login_modal_subtitle', 'Controle de produtos, categorias, minerais e gestão do site.');
$loginBtnText = Setting::get('login_modal_btn', 'Acessar Painel de Controle');
$loginAction = $loginAction ?? '/admin/login';
$sessionError = $loginError ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Acesso Administrativo | DIVINIUM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
    <style>
        :root {
            --font-serif: 'Cormorant Garamond', Georgia, serif;
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --gold-accent: #A7ADB4;
            --gold-hover: #737B85;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body.admin-standalone-login {
            min-height: 100vh;
            background: radial-gradient(circle at 50% 25%, #1d1c1b 0%, #0d0c0c 100%);
            font-family: var(--font-sans);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: #ffffff;
        }
        .admin-standalone-login .login-modal-box {
            transform: none !important;
            opacity: 1 !important;
        }
    </style>
</head>
<body class="admin-standalone-login">
    <div class="login-modal-box" role="main" aria-labelledby="loginModalTitle">
        <div class="login-modal-brand">
            <span class="brand-sparkle">✦</span>
            <span class="brand-title">DIVINIUM</span>
            <span class="brand-sub">PAINEL DO ADMINISTRADOR</span>
        </div>

        <div class="login-modal-header">
            <h3 id="loginModalTitle"><?= View::e($loginTitle) ?></h3>
            <p><?= View::e($loginSubtitle) ?></p>
        </div>

        <div class="login-error-alert" id="loginErrorAlert" style="<?= $sessionError !== '' ? 'display: block;' : 'display: none;' ?>">
            <?= View::e($sessionError) ?>
        </div>

        <form id="adminLoginForm" action="<?= View::e($loginAction) ?>" method="POST" onsubmit="handleStandaloneAdminLogin(event)">
            <input type="hidden" name="_csrf_token" value="<?= CsrfMiddleware::getToken() ?>">

            <div class="modal-form-group">
                <label for="adminEmail">E-mail de Acesso</label>
                <div class="input-icon-wrap">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <input type="email" id="adminEmail" name="email" class="modal-input" placeholder="seu@email.com" required autocomplete="username" autofocus>
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
                <span><?= View::e($loginBtnText) ?></span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </button>
        </form>
    </div>

    <script>
    async function handleStandaloneAdminLogin(e) {
        e.preventDefault();
        const btn = document.getElementById('adminLoginSubmitBtn');
        const errAlert = document.getElementById('loginErrorAlert');
        const form = document.getElementById('adminLoginForm');
        const btnDefaultHtml = '<span><?= addslashes(View::e($loginBtnText)) ?></span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';

        if (errAlert) errAlert.style.display = 'none';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span>Verificando credenciais...</span>';
        }

        const formData = new FormData(form);

        try {
            const response = await fetch(form.getAttribute('action') || '/admin/login', {
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
                    btn.innerHTML = btnDefaultHtml;
                }
            }
        } catch (err) {
            if (errAlert) {
                errAlert.textContent = 'Erro ao processar o login. Tente novamente.';
                errAlert.style.display = 'block';
            }
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = btnDefaultHtml;
            }
        }
    }
    </script>
</body>
</html>

