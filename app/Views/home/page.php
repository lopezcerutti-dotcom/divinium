<?php
/**
 * VIEW: PÁGINAS INSTITUCIONAIS & POLÍTICAS (DIVINIUM QUIET LUXURY)
 */
use App\Models\Setting;
use App\Core\View;
?>

<div class="institutional-page-container">
    <!-- HERO HEADER INSTITUCIONAL -->
    <section class="institutional-hero">
        <div class="container">
            <div class="breadcrumb-trail">
                <a href="/">Início</a>
                <span class="sep">/</span>
                <a href="/loja">Boutique</a>
                <span class="sep">/</span>
                <span class="current"><?= View::e($pageHeading ?? 'Institucional') ?></span>
            </div>

            <div class="institutional-hero-badge">
                <span class="sparkle">✦</span>
                <span>DIVINIUM • ALTA JOALHERIA & CONFIANÇA</span>
            </div>

            <h1 class="institutional-title"><?= View::e($pageHeading ?? 'Institucional') ?></h1>
            <p class="institutional-subtitle"><?= View::e($pageDescription ?? 'Transparência, autenticidade e respeito ao cliente.') ?></p>
        </div>
    </section>

    <!-- CONTEÚDO EDITORIAL NOBRE -->
    <section class="institutional-content-section">
        <div class="container">
            <div class="institutional-card">
                <div class="editorial-body">
                    <?= $pageBodyHtml ?? '' ?>
                </div>

                <div class="institutional-footer-strip">
                    <div class="strip-left">
                        <span class="seal-sparkle">✦</span>
                        <span>DIVINIUM JOIAS • Prata de Lei 925 & Gemas Naturais Raras</span>
                    </div>
                    <div class="strip-right">
                        <a href="/loja" class="btn-back-store">
                            <span>← Explorar Vitrine da Boutique</span>
                        </a>
                        <a href="https://wa.me/<?= View::e(Setting::get('hero_whatsapp_number', '5511999999999')) ?>" target="_blank" rel="noopener" class="btn-concierge-help">
                            <span>Falar com o Concierge</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.institutional-page-container {
    background-color: var(--page-bg, var(--bg-dark));
    color: var(--text-primary);
    min-height: 80vh;
    padding-bottom: 80px;
}
.institutional-hero {
    padding: 70px 0 35px;
    background: radial-gradient(circle at 50% 20%, rgba(202, 161, 92, 0.08) 0%, rgba(13, 12, 11, 0) 70%);
    border-bottom: 1px solid var(--border-light);
    text-align: center;
}
.breadcrumb-trail {
    font-size: 0.8rem;
    color: var(--text-secondary);
    margin-bottom: 16px;
    letter-spacing: 0.5px;
}
.breadcrumb-trail a {
    color: var(--gold-accent);
    text-decoration: none;
    transition: color 0.2s;
}
.breadcrumb-trail a:hover {
    color: var(--gold-hover);
}
.breadcrumb-trail .sep {
    margin: 0 8px;
    color: var(--text-secondary);
    opacity: 0.6;
}
.institutional-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--gold-accent);
    background: rgba(202, 161, 92, 0.1);
    padding: 6px 14px;
    border-radius: 20px;
    border: 1px solid var(--border-light);
    margin-bottom: 14px;
}
.institutional-title {
    font-family: var(--font-serif, 'Cormorant Garamond', Georgia, serif);
    font-size: 2.5rem;
    color: var(--text-primary);
    margin: 0 0 10px;
    letter-spacing: 0.5px;
}
.institutional-subtitle {
    font-size: 1rem;
    color: var(--text-secondary);
    max-width: 680px;
    margin: 0 auto;
    line-height: 1.5;
}
.institutional-content-section {
    padding-top: 40px;
}
.institutional-card {
    background-color: var(--page-card-bg, var(--surface-dark));
    border: 1px solid var(--border-light);
    border-radius: 16px;
    padding: 45px 50px;
    max-width: 900px;
    margin: 0 auto;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
}
.editorial-body {
    font-family: var(--font-sans, 'Plus Jakarta Sans', sans-serif);
    font-size: 0.95rem;
    line-height: 1.8;
    color: var(--text-secondary);
}
.editorial-body h3 {
    font-family: var(--font-serif, 'Cormorant Garamond', Georgia, serif);
    font-size: 1.7rem;
    color: var(--gold-accent);
    margin: 0 0 16px;
    letter-spacing: 0.5px;
}
.editorial-body h4 {
    font-family: var(--font-serif, 'Cormorant Garamond', Georgia, serif);
    font-size: 1.3rem;
    color: var(--text-primary);
    margin: 28px 0 10px;
    border-bottom: 1px solid var(--border-light);
    padding-bottom: 6px;
}
.editorial-body p {
    margin: 0 0 16px;
}
.editorial-body ul, .editorial-body ol {
    margin: 0 0 20px;
    padding-left: 24px;
}
.editorial-body li {
    margin-bottom: 8px;
}
.institutional-footer-strip {
    margin-top: 40px;
    padding-top: 25px;
    border-top: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}
.strip-left {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.78rem;
    color: var(--text-secondary);
}
.seal-sparkle {
    color: var(--gold-accent);
}
.strip-right {
    display: flex;
    gap: 12px;
    align-items: center;
}
.btn-back-store, .btn-concierge-help {
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.25s ease;
}
.btn-back-store {
    background: transparent;
    color: var(--text-secondary);
    border: 1px solid var(--border-light);
}
.btn-back-store:hover {
    border-color: var(--gold-accent);
    color: var(--gold-accent);
}
.btn-concierge-help {
    background: var(--gold-accent);
    color: var(--bg-dark, #0d0c0b);
    border: 1px solid var(--gold-accent);
}
.btn-concierge-help:hover {
    background: var(--gold-hover);
    border-color: var(--gold-hover);
}
@media (max-width: 768px) {
    .institutional-card {
        padding: 26px 20px;
    }
    .institutional-title {
        font-size: 1.9rem;
    }
    .institutional-footer-strip {
        flex-direction: column;
        align-items: flex-start;
    }
    .strip-right {
        width: 100%;
        flex-direction: column;
    }
    .btn-back-store, .btn-concierge-help {
        width: 100%;
        text-align: center;
    }
}
</style>
