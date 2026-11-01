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

<?php
$slugMap = [
    'trocas-e-devolucoes' => 'trocas',
    'garantia-e-cuidados' => 'garantia',
    'politica-de-privacidade' => 'privacidade',
    'termos-de-uso' => 'termos',
    'politica-de-frete-e-entrega' => 'frete',
];
$pKey = $slugMap[$pageSlug ?? ''] ?? '';

// Botão de Ação Direta (CTA da Página)
$ctaVisible = $pKey ? (Setting::get('page_' . $pKey . '_cta_visible', '1') === '1') : false;
$ctaText = $pKey ? Setting::get('page_' . $pKey . '_cta_text', '') : '';
$ctaUrl = $pKey ? Setting::get('page_' . $pKey . '_cta_url', '') : '';
$ctaBg = $pKey ? Setting::get('page_' . $pKey . '_cta_bg', '#202020') : '#202020';
$ctaColor = $pKey ? Setting::get('page_' . $pKey . '_cta_color', '#FFFFFF') : '#FFFFFF';
$ctaBorder = $pKey ? Setting::get('page_' . $pKey . '_cta_border', '#CBD0D6') : '#CBD0D6';
$ctaIcon = $pKey ? Setting::get('page_' . $pKey . '_cta_icon', '') : '';
$ctaIconColor = $pKey ? Setting::get('page_' . $pKey . '_cta_icon_color', '#CBD0D6') : '#CBD0D6';
$ctaRadius = $pKey ? Setting::get('page_' . $pKey . '_cta_radius', '8px') : '8px';
$ctaShadow = $pKey ? Setting::get('page_' . $pKey . '_cta_shadow', 'none') : 'none';

if (!$ctaText && $pKey) {
    $defaultCtaTexts = [
        'trocas' => 'Solicitar Troca via WhatsApp',
        'garantia' => 'Solicitar Atendimento de Garantia',
        'privacidade' => 'Contatar Encarregado LGPD (DPO)',
        'termos' => 'Dúvidas sobre os Termos',
        'frete' => 'Consultar Frete & Rastreamento',
    ];
    $ctaText = $defaultCtaTexts[$pKey] ?? 'Falar com o Concierge';
}
if (!$ctaUrl && $pKey) {
    $ctaUrl = ($pKey === 'privacidade') ? 'mailto:dpo@divinium.com.br' : ('https://wa.me/' . Setting::get('hero_whatsapp_number', '5511999999999'));
}
if (!$ctaIcon && $pKey) {
    $ctaIcon = ($pKey === 'privacidade') ? '✉️' : (($pKey === 'frete') ? '🚚' : '💬');
}

// Botões de Rodapé: Verificar se usa customizado desta página ou global
$useCustomFooter = $pKey && (Setting::get('page_' . $pKey . '_custom_footer_btns', '0') === '1');
$backPrefix = $useCustomFooter ? ('page_' . $pKey . '_btn_back') : 'inst_btn_back_store';
$concPrefix = $useCustomFooter ? ('page_' . $pKey . '_btn_concierge') : 'inst_btn_concierge';

$backVisible = Setting::get($backPrefix . '_visible', '1') === '1';
$backText = Setting::get($backPrefix . '_text', '← Explorar Vitrine da Boutique');
$backUrl = Setting::get($backPrefix . '_url', '/loja');
$backBg = Setting::get($backPrefix . '_bg', 'transparent');
$backColor = Setting::get($backPrefix . '_color', '#CBD0D6');
$backBorder = Setting::get($backPrefix . '_border', 'rgba(255,255,255,0.15)');
$backIcon = Setting::get($backPrefix . '_icon', '←');
$backIconColor = Setting::get($backPrefix . '_icon_color', '#CBD0D6');
$backRadius = Setting::get($backPrefix . '_radius', '8px');
$backShadow = Setting::get($backPrefix . '_shadow', 'none');

$concVisible = Setting::get($concPrefix . '_visible', '1') === '1';
$concText = Setting::get($concPrefix . '_text', 'Falar com o Concierge');
$concUrl = Setting::get($concPrefix . '_url', 'https://wa.me/' . Setting::get('hero_whatsapp_number', '5511999999999'));
$concBg = Setting::get($concPrefix . '_bg', '#CBD0D6');
$concColor = Setting::get($concPrefix . '_color', '#202020');
$concBorder = Setting::get($concPrefix . '_border', '#737B85');
$concIcon = Setting::get($concPrefix . '_icon', '💬');
$concIconColor = Setting::get($concPrefix . '_icon_color', '#202020');
$concRadius = Setting::get($concPrefix . '_radius', '8px');
$concShadow = Setting::get($concPrefix . '_shadow', 'none');

$instShadowMap = [
    'none' => 'none',
    'subtle' => '0 2px 8px rgba(0,0,0,0.12)',
    'noble' => '0 4px 16px rgba(0,0,0,0.25)',
    'glow' => '0 0 18px rgba(203, 208, 214, 0.45)',
    'flat' => 'none',
];

$renderInstIcon = function($iconKey, $iconColor) {
    if (!$iconKey || $iconKey === 'none') return '';
    $style = 'color: ' . View::e($iconColor) . '; display: inline-flex; align-items: center; justify-content: center;';
    switch ($iconKey) {
        case 'arrow-right':
        case '→':
            return '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="' . View::e($iconColor) . '" stroke-width="2" style="margin-left: 5px;"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
        case 'arrow-left':
        case '←':
            return '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="' . View::e($iconColor) . '" stroke-width="2" style="margin-right: 5px;"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>';
        case 'sparkle':
        case '✦':
            return '<span style="' . $style . ' font-size: 0.95rem; margin-right: 5px;">✦</span>';
        case 'whatsapp':
        case '💬':
            return '<span style="' . $style . ' font-size: 1rem; margin-right: 5px;">💬</span>';
        case 'mail':
        case '✉️':
            return '<span style="' . $style . ' font-size: 1rem; margin-right: 5px;">✉️</span>';
        case 'truck':
        case '🚚':
            return '<span style="' . $style . ' font-size: 1rem; margin-right: 5px;">🚚</span>';
        case 'shield':
        case '🛡️':
            return '<span style="' . $style . ' font-size: 1rem; margin-right: 5px;">🛡️</span>';
        default:
            return '<span style="' . $style . ' margin-right: 5px;">' . View::e($iconKey) . '</span>';
    }
};
?>

    <!-- CONTEÚDO EDITORIAL NOBRE -->
    <section class="institutional-content-section">
        <div class="container">
            <div class="institutional-card">
                <div class="editorial-body">
                    <?= $pageBodyHtml ?? '' ?>
                </div>

                <?php if ($ctaVisible && $ctaText): ?>
                <div class="institutional-cta-box" style="margin: 32px 0 10px; padding: 20px 24px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <strong style="color: #ffffff; font-size: 1.05rem; display: block; font-family: var(--font-serif, Georgia, serif); letter-spacing: 0.3px;">Canal Oficial &amp; Atendimento VIP</strong>
                        <span style="font-size: 0.85rem; color: var(--text-secondary); display: block; margin-top: 4px;">Suporte direto e humanizado com nossos consultores de joalheria.</span>
                    </div>
                    <a href="<?= View::e($ctaUrl) ?>" target="_blank" rel="noopener" class="btn-inst-cta" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; font-size: 0.88rem; font-weight: 700; text-decoration: none; background: <?= View::e($ctaBg) ?> !important; color: <?= View::e($ctaColor) ?> !important; border: 1px solid <?= View::e($ctaBorder) ?> !important; border-radius: <?= View::e($ctaRadius) ?> !important; box-shadow: <?= View::e($instShadowMap[$ctaShadow] ?? 'none') ?> !important; transition: all 0.25s ease;">
                        <?= $renderInstIcon($ctaIcon, $ctaIconColor) ?>
                        <span><?= View::e($ctaText) ?></span>
                    </a>
                </div>
                <?php endif; ?>

                <div class="institutional-footer-strip">
                    <div class="strip-left">
                        <span class="seal-sparkle">✦</span>
                        <span>DIVINIUM JOIAS • Banho de Prata &amp; Gemas Naturais Raras</span>
                    </div>
                    <div class="strip-right">
                        <?php if ($backVisible): ?>
                        <a href="<?= View::e($backUrl) ?>" class="btn-back-store" style="background: <?= View::e($backBg) ?> !important; color: <?= View::e($backColor) ?> !important; border: 1px solid <?= View::e($backBorder) ?> !important; border-radius: <?= View::e($backRadius) ?> !important; box-shadow: <?= View::e($instShadowMap[$backShadow] ?? 'none') ?> !important; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease;">
                            <?= $renderInstIcon($backIcon, $backIconColor) ?>
                            <span><?= View::e($backText) ?></span>
                        </a>
                        <?php endif; ?>

                        <?php if ($concVisible): ?>
                        <a href="<?= View::e($concUrl) ?>" target="_blank" rel="noopener" class="btn-concierge-help" style="background: <?= View::e($concBg) ?> !important; color: <?= View::e($concColor) ?> !important; border: 1px solid <?= View::e($concBorder) ?> !important; border-radius: <?= View::e($concRadius) ?> !important; box-shadow: <?= View::e($instShadowMap[$concShadow] ?? 'none') ?> !important; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease;">
                            <?= $renderInstIcon($concIcon, $concIconColor) ?>
                            <span><?= View::e($concText) ?></span>
                        </a>
                        <?php endif; ?>
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
    background: var(--gold-accent, #CBD0D6);
    color: #202020;
    font-weight: 700;
    border: 1px solid var(--gold-hover, #737B85);
}
.btn-concierge-help:hover {
    background: var(--gold-hover, #737B85);
    color: #FFFFFF;
    border-color: var(--gold-hover, #737B85);
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
