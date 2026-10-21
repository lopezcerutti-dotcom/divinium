<?php
/**
 * VIEW: EDITOR DA LANDING PAGE E TEXTOS DO SITE (PAINEL ADMINISTRATIVO)
 * Permite editar qualquer elemento textual, títulos, preços e mídias da loja e da Home.
 */
use App\Models\Setting;
use App\Core\View;

$renderToggle = function(string $key, string $default = '1'): string {
    $val = Setting::get($key, $default);
    $isVisible = ($val === '1' || $val === 1 || $val === true);
    $class = $isVisible ? 'is-visible' : 'is-hidden';
    $label = $isVisible ? 'Exibindo' : 'Oculto';
    $checked = $isVisible ? 'checked' : '';
    return '
    <label class="element-toggle-switch ' . $class . '" title="Exibir ou ocultar este elemento na loja pública">
        <input type="hidden" name="' . $key . '" value="0">
        <input type="checkbox" name="' . $key . '" value="1" ' . $checked . ' onchange="handleToggleSwitch(this)">
        <span class="toggle-text">' . $label . '</span>
    </label>';
};
?>

<div class="landing-editor-container">

    <!-- BARRA SUPERIOR DE AÇÕES RÁPIDAS -->
    <div class="editor-header-bar">
        <div class="editor-header-info">
            <h2>Editor de Textos & Conteúdo do Site</h2>
            <p>Edite 100% dos textos, títulos, chamadas, links, fotos e selos de qualquer seção do site com efeito imediato.</p>
        </div>
        <div class="editor-header-actions">
            <a href="/" target="_blank" class="btn-admin-secondary">
                <span>↗ Ver Home Ao Vivo</span>
            </a>
            <a href="/loja" target="_blank" class="btn-admin-secondary">
                <span>🛍️ Ver Loja Ao Vivo</span>
            </a>
            <button type="button" class="btn-admin-gold" onclick="document.getElementById('landingPageForm').requestSubmit()">
                <span>Salvar Todas as Alterações</span>
            </button>
        </div>
    </div>

    <!-- NAVEGAÇÃO ENTRE SEÇÕES (TABS VISUAIS) -->
    <div class="editor-nav-tabs">
        <button type="button" class="tab-btn active" onclick="switchSection('sec-sections-order', this)">
            <span>🔀 Ordem da Home</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-identity', this)">
            <span>💎 Identidade & Logo</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-banner', this)">
            <span>📢 Top Banner</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-header-cart', this)">
            <span>🧭 Topo & Menu</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-mobile-menu', this)">
            <span>📱 Menu Mobile</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-hero', this)">
            <span>👑 Hero & Títulos</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-stage', this)">
            <span>🎠 Carrossel Principal</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-about', this)">
            <span>📜 Manifesto & Sobre</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-services', this)">
            <span>🛡️ Serviços & Garantia</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-heritage', this)">
            <span>👑 Peça Única</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-testimonials', this)">
            <span>💬 Depoimentos</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-buttons', this)">
            <span>🔘 Botões & Estilos</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-store', this)">
            <span>🛍️ Textos da Loja</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-institutional', this)">
            <span>🏛️ Institucional & CDC</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-seo', this)">
            <span>🌐 SEO & Metatags</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-footer', this)">
            <span>📞 Rodapé & LGPD</span>
        </button>
    </div>

    <!-- FORMULÁRIO PRINCIPAL DO CMS -->
    <form id="landingPageForm" action="/admin/landing-page/salvar" method="POST" onsubmit="handleEditorSubmit(event)">
        <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">

        <!-- ============================================================== -->
        <!-- SEÇÃO: ORDEM E VISIBILIDADE DAS SEÇÕES DA HOME (PILAR 6) -->
        <!-- ============================================================== -->
        <div class="editor-section-card active" id="sec-sections-order">
            <div class="section-card-header">
                <div class="section-icon">🔀</div>
                <div>
                    <h3>Ordem & Visibilidade das Seções da Página Inicial</h3>
                    <p>Defina a sequência hierárquica e ative ou oculte individualmente qualquer uma das 6 seções da Home.</p>
                </div>
            </div>

            <?php
                $currentOrderStr = Setting::get('home_sections_order', 'hero,stage,about,services,heritage,testimonials');
                $sectionsList = [
                    'hero' => ['name' => 'Abertura / Hero Principal', 'desc' => 'Dobra inicial com chamada nobre, foto editorial e botões de ação', 'tab' => 'sec-hero', 'icon' => '👑'],
                    'stage' => ['name' => 'Carrossel de Destaques', 'desc' => 'Vitrine de fotos deslizantes da joalheria autoral em alta resolução', 'tab' => 'sec-stage', 'icon' => '🎠'],
                    'about' => ['name' => 'Manifesto & Sobre a Marca', 'desc' => 'Narrativa conceitual de design, resina nobre e ouro', 'tab' => 'sec-about', 'icon' => '📜'],
                    'services' => ['name' => 'Serviços, Garantia & Autoria', 'desc' => 'Grade com 3 selos de garantia vitalícia, ouro nobre e entrega blindada', 'tab' => 'sec-services', 'icon' => '🛡️'],
                    'heritage' => ['name' => 'Peça Única & Tradição', 'desc' => 'Destaque especial da joia autoral com selo de exclusividade', 'tab' => 'sec-heritage', 'icon' => '💎'],
                    'testimonials' => ['name' => 'Depoimentos & Experiência VIP', 'desc' => 'Avaliações de colecionadores e prova social de clientes', 'tab' => 'sec-testimonials', 'icon' => '💬']
                ];
                $orderKeys = array_filter(array_map('trim', explode(',', $currentOrderStr)));
                foreach (array_keys($sectionsList) as $k) {
                    if (!in_array($k, $orderKeys)) $orderKeys[] = $k;
                }
            ?>

            <input type="hidden" id="home_sections_order" name="home_sections_order" value="<?= View::e($currentOrderStr) ?>">

            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(212,186,136,0.2); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                    <span style="font-size: 0.85rem; color: #d4ba88; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        ✦ Sequência Atual da Página Inicial:
                    </span>
                    <button type="button" class="btn-admin-secondary" style="font-size: 0.76rem; padding: 6px 12px;" onclick="resetSectionsOrder()">
                        Restaurar Ordem Padrão
                    </button>
                </div>

                <div id="sectionsOrderContainer" style="display: flex; flex-direction: column; gap: 12px;">
                    <?php foreach ($orderKeys as $idx => $secKey): 
                        if (!isset($sectionsList[$secKey])) continue;
                        $secInfo = $sectionsList[$secKey];
                        $visKey = 'home_' . $secKey . '_visible';
                        $isSecVisible = Setting::get($visKey, '1') === '1';
                    ?>
                    <div class="section-order-row" data-section-key="<?= $secKey ?>" style="display: flex; align-items: center; justify-content: space-between; background: #161513; border: 1px solid <?= $isSecVisible ? 'rgba(212,186,136,0.3)' : 'rgba(255,255,255,0.06)' ?>; border-radius: 10px; padding: 14px 18px; gap: 14px; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <span class="order-badge" style="width: 28px; height: 28px; border-radius: 50%; background: rgba(212,186,136,0.15); color: #d4ba88; border: 1px solid #d4ba88; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem;">
                                <?= $idx + 1 ?>
                            </span>
                            <span style="font-size: 1.3rem;"><?= $secInfo['icon'] ?></span>
                            <div>
                                <strong style="color: #ffffff; font-size: 0.95rem; display: block;"><?= $secInfo['name'] ?></strong>
                                <span style="color: #8e8880; font-size: 0.78rem;"><?= $secInfo['desc'] ?></span>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 12px;">
                            <button type="button" class="btn-slide-order" onclick="moveSectionOrder('<?= $secKey ?>', -1)" title="Mover seção para cima">▲</button>
                            <button type="button" class="btn-slide-order" onclick="moveSectionOrder('<?= $secKey ?>', 1)" title="Mover seção para baixo">▼</button>

                            <button type="button" class="btn-admin-secondary" style="font-size: 0.75rem; padding: 6px 10px;" onclick="switchSection('<?= $secInfo['tab'] ?>', document.querySelector('.editor-nav-tabs button[onclick*=\'<?= $secInfo['tab'] ?>\']'))">
                                ✎ Editar Textos
                            </button>

                            <div style="margin-left: 8px;">
                                <?= $renderToggle($visKey) ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO: IDENTIDADE VISUAL & LOGOTIPO (PILAR 1) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-identity">
            <div class="section-card-header">
                <div class="section-icon">💎</div>
                <div>
                    <h3>Identidade Visual, Logotipo & Favicon</h3>
                    <p>Controle completo sobre o logotipo (em tipografia de alta joalheria ou imagem gráfica transparente), favicon e monograma autoral.</p>
                </div>
            </div>

            <div class="form-grid">
                <!-- Modo do Logotipo -->
                <div class="form-group full-width" style="background: rgba(255,255,255,0.02); padding: 16px; border-radius: 10px; border: 1px solid rgba(212,186,136,0.2);">
                    <label style="font-size: 0.9rem; color: #d4ba88; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 8px;">
                        Formato do Logotipo no Cabeçalho
                    </label>
                    <div style="display: flex; gap: 24px; align-items: center; flex-wrap: wrap;">
                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; color: #ffffff; font-size: 0.88rem;">
                            <input type="radio" name="site_logo_type" value="text" <?= Setting::get('site_logo_type', 'text') === 'text' ? 'checked' : '' ?> onchange="toggleLogoFields()" style="accent-color: #d4ba88; width: 18px; height: 18px;">
                            <span>Tipografia Editorial Nobre (Texto Puro estilizado)</span>
                        </label>
                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; color: #ffffff; font-size: 0.88rem;">
                            <input type="radio" name="site_logo_type" value="image" <?= Setting::get('site_logo_type') === 'image' ? 'checked' : '' ?> onchange="toggleLogoFields()" style="accent-color: #d4ba88; width: 18px; height: 18px;">
                            <span>Imagem / Arquivo Gráfico (PNG transparente, SVG ou WebP)</span>
                        </label>
                    </div>
                </div>

                <!-- Campos de Texto do Logo -->
                <div class="form-group" id="logoTextGroup1">
                    <label for="id_header_logo_text">Nome Principal da Marca (Texto)</label>
                    <input type="text" id="id_header_logo_text" name="header_logo_text" class="form-input"
                           value="<?= View::e(Setting::get('header_logo_text', 'DIVINIUM')) ?>"
                           placeholder="DIVINIUM">
                    <span class="field-hint">Exibido com tracking expandido e fonte serifada imperial.</span>
                </div>

                <div class="form-group" id="logoTextGroup2">
                    <label for="id_header_logo_sub">Subtítulo / Selo da Marca</label>
                    <input type="text" id="id_header_logo_sub" name="header_logo_sub" class="form-input"
                           value="<?= View::e(Setting::get('header_logo_sub', 'HAUTE JOAILLERIE')) ?>"
                           placeholder="HAUTE JOAILLERIE">
                    <span class="field-hint">Ex: HAUTE JOAILLERIE, JOALHERIA AUTORAL ou JOIAS DE LUXO.</span>
                </div>

                <!-- Campos de Imagem do Logo -->
                <div class="form-group full-width" id="logoImageGroup" style="<?= Setting::get('site_logo_type', 'text') === 'text' ? 'display: none;' : '' ?>">
                    <div style="background: rgba(0,0,0,0.3); border: 1px dashed rgba(212,186,136,0.3); border-radius: 10px; padding: 20px;">
                        <h4 style="color: #fff; margin: 0 0 10px; font-size: 0.95rem;">Arquivo do Logotipo Gráfico</h4>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
                            <div>
                                <label style="font-size: 0.8rem; color: #ded7cf;">Logotipo para Fundo Escuro (Dourado / Branco):</label>
                                <div style="display: flex; gap: 8px; margin-top: 6px;">
                                    <input type="text" id="site_logo_image_dark" name="site_logo_image_dark" class="form-input"
                                           value="<?= View::e(Setting::get('site_logo_image_dark', '')) ?>"
                                           placeholder="/assets/images/logos/logo-gold.svg">
                                    <label class="btn-admin-gold" style="padding: 0 14px; cursor: pointer; display: flex; align-items: center; white-space: nowrap; font-size: 0.78rem;">
                                        <span>📁 Upload</span>
                                        <input type="file" accept="image/*,.svg" style="display: none;" onchange="uploadMediaFile(this, 'site_logo_image_dark', 'logos')">
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label style="font-size: 0.8rem; color: #ded7cf;">Logotipo Alternativo (Fundo Claro / Faturas):</label>
                                <div style="display: flex; gap: 8px; margin-top: 6px;">
                                    <input type="text" id="site_logo_image_light" name="site_logo_image_light" class="form-input"
                                           value="<?= View::e(Setting::get('site_logo_image_light', '')) ?>"
                                           placeholder="/assets/images/logos/logo-dark.svg">
                                    <label class="btn-admin-secondary" style="padding: 0 14px; cursor: pointer; display: flex; align-items: center; white-space: nowrap; font-size: 0.78rem;">
                                        <span>📁 Upload</span>
                                        <input type="file" accept="image/*,.svg" style="display: none;" onchange="uploadMediaFile(this, 'site_logo_image_light', 'logos')">
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-top: 14px;">
                            <div>
                                <label for="site_logo_height" style="font-size: 0.8rem; color: #ded7cf;">Altura da Imagem do Logo:</label>
                                <select id="site_logo_height" name="site_logo_height" class="form-input" style="margin-top: 6px;">
                                    <option value="32px" <?= Setting::get('site_logo_height') === '32px' ? 'selected' : '' ?>>32px — Minimalista Discreto</option>
                                    <option value="38px" <?= Setting::get('site_logo_height', '38px') === '38px' ? 'selected' : '' ?>>38px — Padrão Equilibrado</option>
                                    <option value="46px" <?= Setting::get('site_logo_height') === '46px' ? 'selected' : '' ?>>46px — Destaque Nobre</option>
                                    <option value="54px" <?= Setting::get('site_logo_height') === '54px' ? 'selected' : '' ?>>54px — Grande Impacto</option>
                                </select>
                            </div>

                            <div>
                                <label for="site_logo_alignment" style="font-size: 0.8rem; color: #ded7cf;">Alinhamento no Cabeçalho Desktop:</label>
                                <select id="site_logo_alignment" name="site_logo_alignment" class="form-input" style="margin-top: 6px;">
                                    <option value="center" <?= Setting::get('site_logo_alignment', 'center') === 'center' ? 'selected' : '' ?>>Centralizado (Design Simétrico de Alta Joalheria)</option>
                                    <option value="left" <?= Setting::get('site_logo_alignment') === 'left' ? 'selected' : '' ?>>À Esquerda (Layout E-Commerce Convencional)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Favicon & Monograma -->
                <div class="form-group">
                    <label for="site_favicon_url">Ícone de Navegação (Favicon da Aba do Navegador)</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="site_favicon_url" name="site_favicon_url" class="form-input"
                               value="<?= View::e(Setting::get('site_favicon_url', '/assets/images/favicon.ico')) ?>"
                               placeholder="/assets/images/favicon.ico">
                        <label class="btn-admin-gold" style="padding: 0 14px; cursor: pointer; display: flex; align-items: center; white-space: nowrap; font-size: 0.78rem;">
                            <span>📁 Upload</span>
                            <input type="file" accept="image/*,.ico" style="display: none;" onchange="uploadMediaFile(this, 'site_favicon_url', 'favicons')">
                        </label>
                    </div>
                    <span class="field-hint">Exibido na aba do navegador e nos favoritos (.ico, .png 32x32 ou .svg).</span>
                </div>

                <div class="form-group">
                    <label for="site_monogram_url">Monograma / Selo Diamantino da Marca</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="site_monogram_url" name="site_monogram_url" class="form-input"
                               value="<?= View::e(Setting::get('site_monogram_url', '/assets/images/brand/monogram.svg')) ?>"
                               placeholder="/assets/images/brand/monogram.svg">
                        <label class="btn-admin-gold" style="padding: 0 14px; cursor: pointer; display: flex; align-items: center; white-space: nowrap; font-size: 0.78rem;">
                            <span>📁 Upload</span>
                            <input type="file" accept="image/*,.svg" style="display: none;" onchange="uploadMediaFile(this, 'site_monogram_url', 'logos')">
                        </label>
                    </div>
                    <span class="field-hint">Símbolo gráfico utilizado em certificados de garantia e fundos de embalagens.</span>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 1: BARRA DE ANÚNCIOS SUPERIOR (TOP BANNER) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-banner">
            <div class="section-card-header">
                <div class="section-icon">📢</div>
                <div>
                    <h3>Barra Superior de Aviso (Top Announcement)</h3>
                    <p>A faixa de boas-vindas exibida no topo absoluto do site com benefícios e ofertas.</p>
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="top_banner_text">Texto do Anúncio Superior</label>
                    <input type="text" id="top_banner_text" name="top_banner_text" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('top_banner_text', '✨ Frete Cortesia com Seguro para todo o Brasil • 5% de Desconto no Pix • Até 10x sem juros')) ?>"
                           placeholder="✨ Frete Cortesia com Seguro para todo o Brasil • 5% de Desconto no Pix • Até 10x sem juros">
                    <span class="field-hint">Dica: Use emojis e símbolos como ✨ ou • para enriquecer a mensagem.</span>
                </div>

                <div class="form-checkbox-row full-width">
                    <input type="checkbox" id="top_banner_active" name="top_banner_active" value="1" 
                           <?= Setting::get('top_banner_active', '1') === '1' ? 'checked' : '' ?>
                           style="width: 18px; height: 18px; accent-color: #d4ba88;">
                    <label for="top_banner_active">Exibir esta barra no topo do site</label>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 2: CABEÇALHO, MENU, SACOLA & COOKIES -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-header-cart">
            <div class="section-card-header">
                <div class="section-icon">🧭</div>
                <div>
                    <h3>Cabeçalho, Menu, Gaveta da Sacola & Aviso de Cookies</h3>
                    <p>Altere os textos do logotipo, menus de navegação, carrinho lateral e consentimento de dados.</p>
                </div>
            </div>

            <div class="form-grid">
                <!-- Logotipo Superior -->
                <div class="form-group">
                    <label for="header_logo_text">Texto Principal do Logo</label>
                    <input type="text" id="header_logo_text" name="header_logo_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('header_logo_text', 'DIVINIUM')) ?>"
                           placeholder="DIVINIUM">
                </div>

                <div class="form-group">
                    <label for="header_logo_sub">Subtítulo do Logo</label>
                    <input type="text" id="header_logo_sub" name="header_logo_sub" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('header_logo_sub', 'HAUTE JOAILLERIE')) ?>"
                           placeholder="HAUTE JOAILLERIE">
                </div>

                <!-- Links da Navegação Principal Desktop -->
                <div class="form-group full-width" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 6px; margin-top: 10px;">
                    <h4 style="color: #d4ba88; margin: 0; font-size: 0.88rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        🧭 Itens do Menu Desktop (Topo Esquerda)
                    </h4>
                </div>

                <!-- Link Início -->
                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_link_home">Menu 1: Rótulo</label>
                        <?= $renderToggle('nav_link_home_visible') ?>
                    </div>
                    <input type="text" id="nav_link_home" name="nav_link_home" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_home', 'Início')) ?>"
                           placeholder="Início">
                </div>
                <div class="form-group">
                    <label for="nav_link_home_url">Menu 1: Destino (URL)</label>
                    <input type="text" id="nav_link_home_url" name="nav_link_home_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_home_url', '/')) ?>"
                           placeholder="/">
                </div>

                <!-- Link Loja -->
                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_link_store">Menu 2: Rótulo</label>
                        <?= $renderToggle('nav_link_store_visible') ?>
                    </div>
                    <input type="text" id="nav_link_store" name="nav_link_store" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_store', 'Loja')) ?>"
                           placeholder="Loja">
                </div>
                <div class="form-group">
                    <label for="nav_link_store_url">Menu 2: Destino (URL)</label>
                    <input type="text" id="nav_link_store_url" name="nav_link_store_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_store_url', '/loja')) ?>"
                           placeholder="/loja">
                </div>

                <!-- Link Sobre -->
                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_link_about">Menu 3: Rótulo</label>
                        <?= $renderToggle('nav_link_about_visible') ?>
                    </div>
                    <input type="text" id="nav_link_about" name="nav_link_about" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_about', 'Sobre')) ?>"
                           placeholder="Sobre">
                </div>
                <div class="form-group">
                    <label for="nav_link_about_url">Menu 3: Destino (URL)</label>
                    <input type="text" id="nav_link_about_url" name="nav_link_about_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_about_url', '/#about')) ?>"
                           placeholder="/#about">
                </div>

                <!-- Link Coleções -->
                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_link_collections">Menu 4: Dropdown Coleções</label>
                        <?= $renderToggle('nav_link_collections_visible') ?>
                    </div>
                    <input type="text" id="nav_link_collections" name="nav_link_collections" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_collections', 'Coleções')) ?>"
                           placeholder="Coleções">
                </div>
                <div class="form-group">
                    <label for="nav_link_collections_url">Menu 4: Destino (URL)</label>
                    <input type="text" id="nav_link_collections_url" name="nav_link_collections_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_collections_url', '/loja')) ?>"
                           placeholder="/loja">
                </div>

                <!-- Link Garantia / Serviços -->
                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_link_services">Menu 5: Rótulo</label>
                        <?= $renderToggle('nav_link_services_visible') ?>
                    </div>
                    <input type="text" id="nav_link_services" name="nav_link_services" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_services', 'Garantia')) ?>"
                           placeholder="Garantia">
                </div>
                <div class="form-group">
                    <label for="nav_link_services_url">Menu 5: Destino (URL)</label>
                    <input type="text" id="nav_link_services_url" name="nav_link_services_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_services_url', '/#services')) ?>"
                           placeholder="/#services">
                </div>

                <!-- Link Customizado Adicional -->
                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_custom_link1_text">Menu 6 (Opcional): Rótulo</label>
                        <?= $renderToggle('nav_custom_link1_visible', '0') ?>
                    </div>
                    <input type="text" id="nav_custom_link1_text" name="nav_custom_link1_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_custom_link1_text', 'Certificado')) ?>"
                           placeholder="Ex: Certificado">
                </div>
                <div class="form-group">
                    <label for="nav_custom_link1_url">Menu 6: Destino (URL)</label>
                    <input type="text" id="nav_custom_link1_url" name="nav_custom_link1_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_custom_link1_url', '/garantia-e-cuidados')) ?>"
                           placeholder="/garantia-e-cuidados">
                </div>

                <!-- Sub-itens do Menu Coleções -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 10px; margin-top: 10px;">
                    <h4 style="color: #d4ba88; margin: 0; font-size: 0.88rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        💎 Sub-itens da Gaveta / Dropdown de Coleções
                    </h4>
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_sub_all">Dropdown 1: Rótulo</label>
                        <?= $renderToggle('nav_sub_all_visible') ?>
                    </div>
                    <input type="text" id="nav_sub_all" name="nav_sub_all" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_all', 'Ver Todas as Peças')) ?>"
                           placeholder="Ver Todas as Peças">
                </div>
                <div class="form-group">
                    <label for="nav_sub_all_url">Dropdown 1: Destino (URL)</label>
                    <input type="text" id="nav_sub_all_url" name="nav_sub_all_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_all_url', '/loja')) ?>"
                           placeholder="/loja">
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_sub_bracelets">Dropdown 2: Braceletes</label>
                        <?= $renderToggle('nav_sub_bracelets_visible') ?>
                    </div>
                    <input type="text" id="nav_sub_bracelets" name="nav_sub_bracelets" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_bracelets', 'Braceletes Exclusivos')) ?>"
                           placeholder="Braceletes Exclusivos">
                </div>
                <div class="form-group">
                    <label for="nav_sub_bracelets_url">Dropdown 2: Destino (URL)</label>
                    <input type="text" id="nav_sub_bracelets_url" name="nav_sub_bracelets_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_bracelets_url', '/loja#braceletes')) ?>"
                           placeholder="/loja#braceletes">
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_sub_necklaces">Dropdown 3: Colares</label>
                        <?= $renderToggle('nav_sub_necklaces_visible') ?>
                    </div>
                    <input type="text" id="nav_sub_necklaces" name="nav_sub_necklaces" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_necklaces', 'Colares & Pingentes')) ?>"
                           placeholder="Colares & Pingentes">
                </div>
                <div class="form-group">
                    <label for="nav_sub_necklaces_url">Dropdown 3: Destino (URL)</label>
                    <input type="text" id="nav_sub_necklaces_url" name="nav_sub_necklaces_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_necklaces_url', '/loja#colares')) ?>"
                           placeholder="/loja#colares">
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_sub_rings">Dropdown 4: Anéis</label>
                        <?= $renderToggle('nav_sub_rings_visible') ?>
                    </div>
                    <input type="text" id="nav_sub_rings" name="nav_sub_rings" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_rings', 'Anéis & Alianças')) ?>"
                           placeholder="Anéis & Alianças">
                </div>
                <div class="form-group">
                    <label for="nav_sub_rings_url">Dropdown 4: Destino (URL)</label>
                    <input type="text" id="nav_sub_rings_url" name="nav_sub_rings_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_rings_url', '/loja#aneis')) ?>"
                           placeholder="/loja#aneis">
                </div>

                <div class="form-group">
                    <div class="field-label-row">
                        <label for="nav_sub_earrings">Dropdown 5: Brincos</label>
                        <?= $renderToggle('nav_sub_earrings_visible') ?>
                    </div>
                    <input type="text" id="nav_sub_earrings" name="nav_sub_earrings" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_earrings', 'Brincos & Segundo Furo')) ?>"
                           placeholder="Brincos & Segundo Furo">
                </div>
                <div class="form-group">
                    <label for="nav_sub_earrings_url">Dropdown 5: Destino (URL)</label>
                    <input type="text" id="nav_sub_earrings_url" name="nav_sub_earrings_url" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_earrings_url', '/loja#brincos')) ?>"
                           placeholder="/loja#brincos">
                </div>

                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 10px; margin-top: 10px;">
                    <h4 style="color: #d4ba88; margin: 0; font-size: 0.88rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        🛍️ Gaveta Lateral da Sacola de Compras
                    </h4>
                </div>

                <!-- Gaveta Lateral da Sacola -->
                <div class="form-group">
                    <label for="cart_drawer_title">Título da Gaveta da Sacola</label>
                    <input type="text" id="cart_drawer_title" name="cart_drawer_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('cart_drawer_title', 'Sua Sacola de Escolhas')) ?>"
                           placeholder="Sua Sacola de Escolhas">
                </div>

                <div class="form-group">
                    <label for="cart_drawer_empty">Mensagem de Sacola Vazia</label>
                    <input type="text" id="cart_drawer_empty" name="cart_drawer_empty" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('cart_drawer_empty', 'Sua sacola está vazia no momento.')) ?>"
                           placeholder="Sua sacola está vazia no momento.">
                </div>

                <div class="form-group">
                    <label for="cart_drawer_discover">Botão Descobrir Coleção (Sacola Vazia)</label>
                    <input type="text" id="cart_drawer_discover" name="cart_drawer_discover" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('cart_drawer_discover', 'Descobrir Coleção')) ?>"
                           placeholder="Descobrir Coleção">
                </div>

                <div class="form-group">
                    <label for="cart_security_badge">Selo de Segurança da Sacola</label>
                    <input type="text" id="cart_security_badge" name="cart_security_badge" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('cart_security_badge', '🔒 Pagamento 100% Seguro via ASAAS • Pix com QR Code Instantâneo')) ?>"
                           placeholder="🔒 Pagamento 100% Seguro via ASAAS • Pix com QR Code Instantâneo">
                </div>

                <div class="form-group">
                    <label for="cart_checkout_btn">Botão Finalizar Compra</label>
                    <input type="text" id="cart_checkout_btn" name="cart_checkout_btn" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('cart_checkout_btn', 'Finalizar Compra')) ?>"
                           placeholder="Finalizar Compra">
                </div>

                <!-- Banner de Cookies LGPD -->
                <div class="form-group full-width">
                    <label for="cookie_banner_text">Texto do Banner de Cookies (LGPD)</label>
                    <textarea id="cookie_banner_text" name="cookie_banner_text" class="form-textarea" rows="2"><?= \App\Core\View::e(Setting::get('cookie_banner_text', 'Utilizamos cookies essenciais para garantir a melhor experiência de navegação e compra segura em conformidade com a LGPD. Ao continuar, você concorda com nossos Termos de Uso e Política de Privacidade.')) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="cookie_banner_btn">Botão Aceitar Cookies</label>
                    <input type="text" id="cookie_banner_btn" name="cookie_banner_btn" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('cookie_banner_btn', 'Prosseguir')) ?>"
                           placeholder="Prosseguir">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO: MENU RESPONSIVO MOBILE (DRAWER & CELULAR) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-mobile-menu">
            <div class="section-card-header">
                <div class="section-icon">📱</div>
                <div>
                    <h3>Menu Responsivo do Celular (Gaveta & Navegação Mobile)</h3>
                    <p>Personalize cada detalhe da experiência móvel: links, acordeão de coleções, botões de ação, selos, frases de rodapé e cores.</p>
                </div>
            </div>

            <!-- Painel Informativo com Atalho -->
            <div style="margin-bottom: 24px; padding: 14px 18px; background: rgba(212, 186, 136, 0.06); border-radius: 10px; border: 1px solid rgba(212, 186, 136, 0.2); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <div style="font-size: 0.85rem; color: #ded7cf; line-height: 1.4;">
                    <strong style="color: #d4ba88;">✦ Controle Total no Celular:</strong> 
                    Cada link, botão e selo possui um interruptor individual <em>Exibindo / Oculto</em>. Você pode ocultar itens sem perder o que escreveu.
                </div>
                <a href="/#mobileNavDrawer" target="_blank" class="btn-admin-secondary" style="font-size: 0.8rem; padding: 6px 14px;">
                    <span>↗ Abrir Loja no Celular</span>
                </a>
            </div>

            <div class="form-grid">

                <!-- 1. COMPORTAMENTO GERAL -->
                <div class="form-group full-width" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 8px;">
                    <h4 style="color: #d4ba88; margin: 0; font-size: 0.92rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        ⚙️ 1. Comportamento & Abertura da Gaveta
                    </h4>
                </div>

                <div class="form-group full-width" style="background: rgba(212, 186, 136, 0.05); padding: 12px 16px; border-radius: 8px; border: 1px solid rgba(212, 186, 136, 0.15);">
                    <div class="field-label-row">
                        <label style="color: #ffffff; font-weight: 600; font-size: 0.9rem;">Ativar Menu Hambúrguer & Gaveta no Celular</label>
                        <?= $renderToggle('mobile_menu_enabled') ?>
                    </div>
                    <span class="field-hint" style="margin-top: 2px;">Quando desativado, o botão hambúrguer de duas linhas e a gaveta lateral ficam totalmente ocultos.</span>
                </div>

                <div class="form-group">
                    <label for="mobile_nav_position">Posição de Abertura da Gaveta</label>
                    <select id="mobile_nav_position" name="mobile_nav_position" class="form-input" onchange="updateMobileMenuLivePreview()">
                        <option value="left" <?= Setting::get('mobile_nav_position', 'left') === 'left' ? 'selected' : '' ?>>Deslizar da Esquerda (Padrão Sofisticado)</option>
                        <option value="right" <?= Setting::get('mobile_nav_position') === 'right' ? 'selected' : '' ?>>Deslizar da Direita</option>
                    </select>
                    <span class="field-hint">Lado da tela de onde a gaveta de navegação surge suavemente.</span>
                </div>

                <div class="form-group">
                    <label for="mobile_nav_drawer_width">Largura Máxima no Celular</label>
                    <select id="mobile_nav_drawer_width" name="mobile_nav_drawer_width" class="form-input" onchange="updateMobileMenuLivePreview()">
                        <option value="320px" <?= Setting::get('mobile_nav_drawer_width') === '320px' ? 'selected' : '' ?>>Compacta (320px)</option>
                        <option value="360px" <?= Setting::get('mobile_nav_drawer_width', '360px') === '360px' ? 'selected' : '' ?>>Equilibrada - Padrão Divinium (360px)</option>
                        <option value="400px" <?= Setting::get('mobile_nav_drawer_width') === '400px' ? 'selected' : '' ?>>Ampla (400px)</option>
                        <option value="100%" <?= Setting::get('mobile_nav_drawer_width') === '100%' ? 'selected' : '' ?>>Tela Cheia (100% de largura)</option>
                    </select>
                    <span class="field-hint">Dimensão da gaveta quando expandida no smartphone.</span>
                </div>

                <div class="form-group full-width">
                    <label for="mobile_nav_backdrop_blur">Intensidade do Desfoque de Fundo (Backdrop Blur)</label>
                    <select id="mobile_nav_backdrop_blur" name="mobile_nav_backdrop_blur" class="form-input">
                        <option value="0px" <?= Setting::get('mobile_nav_backdrop_blur') === '0px' ? 'selected' : '' ?>>Sem desfoque (Fundo apenas escuro)</option>
                        <option value="4px" <?= Setting::get('mobile_nav_backdrop_blur') === '4px' ? 'selected' : '' ?>>Sutil (4px)</option>
                        <option value="8px" <?= Setting::get('mobile_nav_backdrop_blur', '8px') === '8px' ? 'selected' : '' ?>>Luxo Suave (8px - Padrão)</option>
                        <option value="16px" <?= Setting::get('mobile_nav_backdrop_blur') === '16px' ? 'selected' : '' ?>>Profundo / Efeito Vidro Fosco (16px)</option>
                    </select>
                    <span class="field-hint">Efeito de glassmorphism aplicado ao restante da loja enquanto o menu estiver aberto.</span>
                </div>

                <!-- 2. CORES E ESTÉTICA VISUAL -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px; margin-top: 8px;">
                    <h4 style="color: #d4ba88; margin: 0 0 4px; font-size: 0.92rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        🎨 2. Paleta de Cores do Menu Mobile
                    </h4>
                    <span class="field-hint">Deixe o menu personalizado com tonalidades exclusivas da joalheria.</span>
                </div>

                <div class="form-group">
                    <label for="mobile_nav_bg_color">Cor de Fundo da Gaveta</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="mobile_nav_bg_color_picker" 
                               value="<?= Setting::get('mobile_nav_bg_color', '#0d0c0b') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('mobile_nav_bg_color').value = this.value; updateMobileMenuLivePreview();">
                        <input type="text" id="mobile_nav_bg_color" name="mobile_nav_bg_color" class="form-input" 
                               value="<?= View::e(Setting::get('mobile_nav_bg_color', '#0d0c0b')) ?>" 
                               placeholder="#0d0c0b"
                               oninput="if(this.value.length===7){document.getElementById('mobile_nav_bg_color_picker').value=this.value;} updateMobileMenuLivePreview();">
                    </div>
                    <span class="field-hint">Sugestões: #0d0c0b (Ônix Nobre), #141210 (Café Diamante), #0f172a (Safira Noite).</span>
                </div>

                <div class="form-group">
                    <label for="mobile_nav_text_color">Cor dos Textos e Ícones</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="mobile_nav_text_color_picker" 
                               value="<?= Setting::get('mobile_nav_text_color', '#ded7cf') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('mobile_nav_text_color').value = this.value; updateMobileMenuLivePreview();">
                        <input type="text" id="mobile_nav_text_color" name="mobile_nav_text_color" class="form-input" 
                               value="<?= View::e(Setting::get('mobile_nav_text_color', '#ded7cf')) ?>" 
                               placeholder="#ded7cf"
                               oninput="if(this.value.length===7){document.getElementById('mobile_nav_text_color_picker').value=this.value;} updateMobileMenuLivePreview();">
                    </div>
                    <span class="field-hint">Cor dos títulos dos links e chevrons na gaveta.</span>
                </div>

                <div class="form-group full-width">
                    <label for="mobile_nav_accent_color">Cor de Destaque / Dourado (Bordas Ativas & Selos)</label>
                    <div style="display: flex; gap: 8px; align-items: center; max-width: 400px;">
                        <input type="color" id="mobile_nav_accent_color_picker" 
                               value="<?= Setting::get('mobile_nav_accent_color', '#d4ba88') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('mobile_nav_accent_color').value = this.value; updateMobileMenuLivePreview();">
                        <input type="text" id="mobile_nav_accent_color" name="mobile_nav_accent_color" class="form-input" 
                               value="<?= View::e(Setting::get('mobile_nav_accent_color', '#d4ba88')) ?>" 
                               placeholder="#d4ba88"
                               oninput="if(this.value.length===7){document.getElementById('mobile_nav_accent_color_picker').value=this.value;} updateMobileMenuLivePreview();">
                    </div>
                    <span class="field-hint">Cor da borda lateral do link selecionado, selo 'Exclusivo' e contorno dourado.</span>
                </div>

                <!-- 3. CABEÇALHO DO MENU -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px; margin-top: 8px;">
                    <h4 style="color: #d4ba88; margin: 0 0 4px; font-size: 0.92rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        👑 3. Cabeçalho do Menu Mobile (Marca & Selo)
                    </h4>
                </div>

                <div class="form-group full-width">
                    <div class="field-label-row">
                        <label>Exibir Marca / Logotipo no Topo do Menu</label>
                        <?= $renderToggle('mobile_nav_show_brand') ?>
                    </div>
                </div>

                <div class="form-group <?= Setting::get('mobile_nav_show_brand', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="mobile_nav_logo_text">Texto Principal da Marca no Menu</label>
                    <input type="text" id="mobile_nav_logo_text" name="mobile_nav_logo_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_logo_text', Setting::get('header_logo_text', 'DIVINIUM'))) ?>"
                           placeholder="DIVINIUM"
                           oninput="updateMobileMenuLivePreview()">
                </div>

                <div class="form-group <?= Setting::get('mobile_nav_show_brand', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="mobile_nav_logo_sub">Subtítulo da Marca no Menu</label>
                    <input type="text" id="mobile_nav_logo_sub" name="mobile_nav_logo_sub" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_logo_sub', Setting::get('header_logo_sub', 'HAUTE JOAILLERIE'))) ?>"
                           placeholder="HAUTE JOAILLERIE"
                           oninput="updateMobileMenuLivePreview()">
                </div>

                <div class="form-group full-width <?= Setting::get('mobile_nav_show_header_badge', '0') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_header_badge">Selo Superior Flutuante (Opcional, acima da marca)</label>
                        <?= $renderToggle('mobile_nav_show_header_badge', '0') ?>
                    </div>
                    <input type="text" id="mobile_nav_header_badge" name="mobile_nav_header_badge" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_header_badge', 'JOALHERIA AUTORAL')) ?>"
                           placeholder="JOALHERIA AUTORAL"
                           oninput="updateMobileMenuLivePreview()">
                    <span class="field-hint">Ex: 'JOALHERIA AUTORAL', 'EDIÇÃO LIMITADA' ou 'ALTA JOALHERIA'.</span>
                </div>

                <!-- 4. LINKS DE NAVEGAÇÃO -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px; margin-top: 8px;">
                    <h4 style="color: #d4ba88; margin: 0 0 4px; font-size: 0.92rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        🧭 4. Links de Navegação Mobile (Texto, URL & Exibição)
                    </h4>
                    <span class="field-hint">Altere o texto, destino e a visibilidade de cada link individualmente.</span>
                </div>

                <!-- Link 1: Início -->
                <div class="form-group <?= Setting::get('mobile_nav_home_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_home_text">Link 1: Texto</label>
                        <?= $renderToggle('mobile_nav_home_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_home_text" name="mobile_nav_home_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_home_text', Setting::get('nav_link_home', 'Início'))) ?>"
                           placeholder="Início"
                           oninput="updateMobileMenuLivePreview()">
                </div>
                <div class="form-group <?= Setting::get('mobile_nav_home_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="mobile_nav_home_url">Link 1: URL de Destino</label>
                    <input type="text" id="mobile_nav_home_url" name="mobile_nav_home_url" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_home_url', '/')) ?>"
                           placeholder="/">
                </div>

                <!-- Link 2: Loja / Boutique -->
                <div class="form-group <?= Setting::get('mobile_nav_store_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_store_text">Link 2: Texto (Loja / Boutique)</label>
                        <?= $renderToggle('mobile_nav_store_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_store_text" name="mobile_nav_store_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_store_text', Setting::get('nav_link_store', 'Boutique / Loja'))) ?>"
                           placeholder="Boutique / Loja"
                           oninput="updateMobileMenuLivePreview()">
                </div>
                <div class="form-group <?= Setting::get('mobile_nav_store_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="mobile_nav_store_url">Link 2: URL de Destino</label>
                    <input type="text" id="mobile_nav_store_url" name="mobile_nav_store_url" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_store_url', '/loja')) ?>"
                           placeholder="/loja">
                </div>

                <!-- Selo do Link da Loja -->
                <div class="form-group full-width <?= Setting::get('mobile_nav_store_badge_visible', '1') !== '1' ? 'is-dimmed' : '' ?>" style="background: rgba(255,255,255,0.02); padding: 12px 14px; border-radius: 8px; border: 1px dashed rgba(255,255,255,0.08);">
                    <div class="field-label-row">
                        <label for="mobile_nav_store_badge_text">Etiqueta Flutuante do Link da Loja (Badge Dourado)</label>
                        <?= $renderToggle('mobile_nav_store_badge_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_store_badge_text" name="mobile_nav_store_badge_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_store_badge_text', 'Exclusivo')) ?>"
                           placeholder="Exclusivo"
                           oninput="updateMobileMenuLivePreview()">
                    <span class="field-hint">Exibido ao lado do nome 'Boutique / Loja'. Ex: 'Exclusivo', 'Novo', 'Catálogo'.</span>
                </div>

                <!-- Link 3: Acordeão de Coleções -->
                <div class="form-group full-width <?= Setting::get('mobile_nav_collections_visible', '1') !== '1' ? 'is-dimmed' : '' ?>" style="background: rgba(212, 186, 136, 0.04); padding: 14px; border-radius: 8px; border: 1px solid rgba(212, 186, 136, 0.15);">
                    <div class="field-label-row">
                        <label for="mobile_nav_collections_text" style="color: #ffffff; font-weight: 600;">Link 3: Acordeão Expansível de Coleções</label>
                        <?= $renderToggle('mobile_nav_collections_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_collections_text" name="mobile_nav_collections_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_collections_text', Setting::get('nav_link_collections', 'Coleções'))) ?>"
                           placeholder="Coleções"
                           oninput="updateMobileMenuLivePreview()">
                    <span class="field-hint">Menu com seta que expande as subcategorias abaixo no celular.</span>

                    <div style="margin-top: 14px; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.08); display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <!-- Sub-item 1 -->
                        <div class="form-group <?= Setting::get('mobile_nav_sub_all_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                            <div class="field-label-row">
                                <label for="mobile_nav_sub_all_text" style="font-size: 0.78rem;">Sub-item 1: Todas as Peças</label>
                                <?= $renderToggle('mobile_nav_sub_all_visible') ?>
                            </div>
                            <input type="text" id="mobile_nav_sub_all_text" name="mobile_nav_sub_all_text" class="form-input" 
                                   value="<?= View::e(Setting::get('mobile_nav_sub_all_text', Setting::get('nav_sub_all', 'Ver Todas as Peças'))) ?>"
                                   oninput="updateMobileMenuLivePreview()">
                            <input type="text" name="mobile_nav_sub_all_url" class="form-input" style="margin-top: 4px; font-size: 0.78rem;"
                                   value="<?= View::e(Setting::get('mobile_nav_sub_all_url', '/loja')) ?>">
                        </div>

                        <!-- Sub-item 2 -->
                        <div class="form-group <?= Setting::get('mobile_nav_sub_bracelets_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                            <div class="field-label-row">
                                <label for="mobile_nav_sub_bracelets_text" style="font-size: 0.78rem;">Sub-item 2: Braceletes</label>
                                <?= $renderToggle('mobile_nav_sub_bracelets_visible') ?>
                            </div>
                            <input type="text" id="mobile_nav_sub_bracelets_text" name="mobile_nav_sub_bracelets_text" class="form-input" 
                                   value="<?= View::e(Setting::get('mobile_nav_sub_bracelets_text', Setting::get('nav_sub_bracelets', 'Braceletes Exclusivos'))) ?>"
                                   oninput="updateMobileMenuLivePreview()">
                            <input type="text" name="mobile_nav_sub_bracelets_url" class="form-input" style="margin-top: 4px; font-size: 0.78rem;"
                                   value="<?= View::e(Setting::get('mobile_nav_sub_bracelets_url', '/loja#braceletes')) ?>">
                        </div>

                        <!-- Sub-item 3 -->
                        <div class="form-group <?= Setting::get('mobile_nav_sub_necklaces_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                            <div class="field-label-row">
                                <label for="mobile_nav_sub_necklaces_text" style="font-size: 0.78rem;">Sub-item 3: Colares</label>
                                <?= $renderToggle('mobile_nav_sub_necklaces_visible') ?>
                            </div>
                            <input type="text" id="mobile_nav_sub_necklaces_text" name="mobile_nav_sub_necklaces_text" class="form-input" 
                                   value="<?= View::e(Setting::get('mobile_nav_sub_necklaces_text', Setting::get('nav_sub_necklaces', 'Colares & Pingentes'))) ?>"
                                   oninput="updateMobileMenuLivePreview()">
                            <input type="text" name="mobile_nav_sub_necklaces_url" class="form-input" style="margin-top: 4px; font-size: 0.78rem;"
                                   value="<?= View::e(Setting::get('mobile_nav_sub_necklaces_url', '/loja#colares')) ?>">
                        </div>

                        <!-- Sub-item 4 -->
                        <div class="form-group <?= Setting::get('mobile_nav_sub_rings_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                            <div class="field-label-row">
                                <label for="mobile_nav_sub_rings_text" style="font-size: 0.78rem;">Sub-item 4: Anéis</label>
                                <?= $renderToggle('mobile_nav_sub_rings_visible') ?>
                            </div>
                            <input type="text" id="mobile_nav_sub_rings_text" name="mobile_nav_sub_rings_text" class="form-input" 
                                   value="<?= View::e(Setting::get('mobile_nav_sub_rings_text', Setting::get('nav_sub_rings', 'Anéis & Alianças'))) ?>"
                                   oninput="updateMobileMenuLivePreview()">
                            <input type="text" name="mobile_nav_sub_rings_url" class="form-input" style="margin-top: 4px; font-size: 0.78rem;"
                                   value="<?= View::e(Setting::get('mobile_nav_sub_rings_url', '/loja#aneis')) ?>">
                        </div>
                    </div>
                </div>

                <!-- Link 4: Sobre -->
                <div class="form-group <?= Setting::get('mobile_nav_about_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_about_text">Link 4: Texto (Sobre a Divinium)</label>
                        <?= $renderToggle('mobile_nav_about_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_about_text" name="mobile_nav_about_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_about_text', Setting::get('nav_link_about', 'Sobre'))) ?>"
                           placeholder="Sobre"
                           oninput="updateMobileMenuLivePreview()">
                </div>
                <div class="form-group <?= Setting::get('mobile_nav_about_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="mobile_nav_about_url">Link 4: URL de Destino</label>
                    <input type="text" id="mobile_nav_about_url" name="mobile_nav_about_url" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_about_url', '/#about')) ?>"
                           placeholder="/#about">
                </div>

                <!-- Link 5: Garantia & Serviços -->
                <div class="form-group <?= Setting::get('mobile_nav_services_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_services_text">Link 5: Texto (Garantia & Serviços)</label>
                        <?= $renderToggle('mobile_nav_services_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_services_text" name="mobile_nav_services_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_services_text', Setting::get('nav_link_services', 'Garantia & Serviços'))) ?>"
                           placeholder="Garantia & Serviços"
                           oninput="updateMobileMenuLivePreview()">
                </div>
                <div class="form-group <?= Setting::get('mobile_nav_services_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="mobile_nav_services_url">Link 5: URL de Destino</label>
                    <input type="text" id="mobile_nav_services_url" name="mobile_nav_services_url" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_services_url', '/#services')) ?>"
                           placeholder="/#services">
                </div>

                <!-- Link 6: Lista de Desejos / Favoritos -->
                <div class="form-group <?= Setting::get('mobile_nav_wishlist_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_wishlist_text">Link 6: Texto (Lista de Desejos)</label>
                        <?= $renderToggle('mobile_nav_wishlist_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_wishlist_text" name="mobile_nav_wishlist_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_wishlist_text', 'Lista de Desejos')) ?>"
                           placeholder="Lista de Desejos"
                           oninput="updateMobileMenuLivePreview()">
                </div>
                <div class="form-group <?= Setting::get('mobile_nav_wishlist_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="mobile_nav_wishlist_url">Link 6: URL de Destino</label>
                    <input type="text" id="mobile_nav_wishlist_url" name="mobile_nav_wishlist_url" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_wishlist_url', '/minha-conta/favoritos')) ?>"
                           placeholder="/minha-conta/favoritos">
                </div>

                <!-- Link 7: Personalizado Adicional 1 -->
                <div class="form-group <?= Setting::get('mobile_nav_custom1_visible', '0') !== '1' ? 'is-dimmed' : '' ?>" style="background: rgba(255,255,255,0.02); padding: 10px; border-radius: 6px;">
                    <div class="field-label-row">
                        <label for="mobile_nav_custom1_text">Link Extra 1 (Ex: Rastreamento)</label>
                        <?= $renderToggle('mobile_nav_custom1_visible', '0') ?>
                    </div>
                    <input type="text" id="mobile_nav_custom1_text" name="mobile_nav_custom1_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_custom1_text', 'Rastrear Meu Pedido')) ?>"
                           placeholder="Rastrear Meu Pedido"
                           oninput="updateMobileMenuLivePreview()">
                    <div style="display: flex; gap: 6px; margin-top: 6px;">
                        <input type="text" name="mobile_nav_custom1_url" class="form-input" style="flex: 2; font-size: 0.78rem;"
                               value="<?= View::e(Setting::get('mobile_nav_custom1_url', '/loja')) ?>" placeholder="/loja">
                        <input type="text" name="mobile_nav_custom1_badge" class="form-input" style="flex: 1; font-size: 0.78rem;"
                               value="<?= View::e(Setting::get('mobile_nav_custom1_badge', 'VIP')) ?>" placeholder="Badge (VIP)">
                    </div>
                </div>

                <!-- Link 8: Personalizado Adicional 2 -->
                <div class="form-group <?= Setting::get('mobile_nav_custom2_visible', '0') !== '1' ? 'is-dimmed' : '' ?>" style="background: rgba(255,255,255,0.02); padding: 10px; border-radius: 6px;">
                    <div class="field-label-row">
                        <label for="mobile_nav_custom2_text">Link Extra 2 (Ex: Contato / FAQ)</label>
                        <?= $renderToggle('mobile_nav_custom2_visible', '0') ?>
                    </div>
                    <input type="text" id="mobile_nav_custom2_text" name="mobile_nav_custom2_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_custom2_text', 'Atendimento & FAQ')) ?>"
                           placeholder="Atendimento & FAQ"
                           oninput="updateMobileMenuLivePreview()">
                    <div style="display: flex; gap: 6px; margin-top: 6px;">
                        <input type="text" name="mobile_nav_custom2_url" class="form-input" style="flex: 2; font-size: 0.78rem;"
                               value="<?= View::e(Setting::get('mobile_nav_custom2_url', '/#services')) ?>" placeholder="/#services">
                        <input type="text" name="mobile_nav_custom2_badge" class="form-input" style="flex: 1; font-size: 0.78rem;"
                               value="<?= View::e(Setting::get('mobile_nav_custom2_badge', '')) ?>" placeholder="Badge">
                    </div>
                </div>

                <!-- 5. RODAPÉ E AÇÕES -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px; margin-top: 8px;">
                    <h4 style="color: #d4ba88; margin: 0 0 4px; font-size: 0.92rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        📞 5. Rodapé & Ações da Gaveta (WhatsApp & Acesso)
                    </h4>
                </div>

                <!-- Botão WhatsApp -->
                <div class="form-group <?= Setting::get('mobile_nav_whatsapp_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_whatsapp_text">Botão WhatsApp: Texto</label>
                        <?= $renderToggle('mobile_nav_whatsapp_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_whatsapp_text" name="mobile_nav_whatsapp_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_whatsapp_text', 'Concierge VIP via WhatsApp')) ?>"
                           placeholder="Concierge VIP via WhatsApp"
                           oninput="updateMobileMenuLivePreview()">
                </div>
                <div class="form-group <?= Setting::get('mobile_nav_whatsapp_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="mobile_nav_whatsapp_url">Botão WhatsApp: URL / Número</label>
                    <input type="text" id="mobile_nav_whatsapp_url" name="mobile_nav_whatsapp_url" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_whatsapp_url', Setting::get('hero_whatsapp_number', '5511999999999'))) ?>"
                           placeholder="5511999999999 ou link completo">
                </div>

                <!-- Botão Entrar -->
                <div class="form-group full-width <?= Setting::get('mobile_nav_login_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_login_text">Botão de Acesso / Login</label>
                        <?= $renderToggle('mobile_nav_login_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_login_text" name="mobile_nav_login_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_login_text', 'Entrar no Painel Administrativo')) ?>"
                           placeholder="Entrar no Painel Administrativo"
                           oninput="updateMobileMenuLivePreview()">
                    <span class="field-hint">Abre a janela nobre de autenticação administrativa e credenciais.</span>
                </div>

                <!-- Mensagem Perk -->
                <div class="form-group full-width <?= Setting::get('mobile_nav_perk_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="mobile_nav_perk_text">Selo Institucional / Frase de Rodapé</label>
                        <?= $renderToggle('mobile_nav_perk_visible') ?>
                    </div>
                    <input type="text" id="mobile_nav_perk_text" name="mobile_nav_perk_text" class="form-input" 
                           value="<?= View::e(Setting::get('mobile_nav_perk_text', '✨ Joalheria Autoral Brasileira • Prata 925 & Gemas Raras')) ?>"
                           placeholder="✨ Joalheria Autoral Brasileira • Prata 925 & Gemas Raras"
                           oninput="updateMobileMenuLivePreview()">
                    <span class="field-hint">Frase final de prestígio e assinatura da marca no pé do menu.</span>
                </div>

                <!-- 6. PRÉ-VISUALIZAÇÃO EM TEMPO REAL -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 20px; margin-top: 12px;">
                    <h4 style="color: #d4ba88; margin: 0 0 10px; font-size: 0.92rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; justify-content: space-between;">
                        <span>📱 Pré-visualização Interativa do Menu no Smartphone</span>
                        <span style="font-size: 0.72rem; color: #8e8880; font-weight: normal; text-transform: none;">Atualiza em tempo real enquanto você digita</span>
                    </h4>
                    
                    <div style="background: rgba(0,0,0,0.4); padding: 20px; border-radius: 12px; border: 1px dashed rgba(255,255,255,0.1); display: flex; justify-content: center;">
                        <!-- Mockup de Smartphone -->
                        <div style="width: 330px; border-radius: 28px; border: 3px solid rgba(212, 186, 136, 0.4); box-shadow: 0 16px 40px rgba(0,0,0,0.8); overflow: hidden; background: #000; display: flex; flex-direction: column;">
                            <!-- Barra de Status do Celular -->
                            <div style="background: #000; padding: 6px 16px; display: flex; justify-content: space-between; align-items: center; font-size: 0.65rem; color: #888;">
                                <span>9:41</span>
                                <div style="display: flex; gap: 4px; align-items: center;">
                                    <span>●●●●</span>
                                    <span>5G</span>
                                    <span>100%</span>
                                </div>
                            </div>
                            
                            <!-- Conteúdo do Menu dentro do Mockup -->
                            <div id="mobileMenuLivePreviewDrawer" style="background: <?= Setting::get('mobile_nav_bg_color', '#0d0c0b') ?>; color: <?= Setting::get('mobile_nav_text_color', '#ded7cf') ?>; padding: 16px 18px; display: flex; flex-direction: column; min-height: 480px; justify-content: space-between;">
                                
                                <div>
                                    <!-- Header do Mockup -->
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 14px; border-bottom: 1px solid rgba(255,255,255,0.08);">
                                        <div id="previewBrandWrap">
                                            <div id="previewHeaderBadge" style="display: <?= Setting::get('mobile_nav_show_header_badge', '0') === '1' ? 'inline-block' : 'none' ?>; font-size: 0.52rem; font-weight: 700; color: #d4ba88; border: 1px solid rgba(212,186,136,0.3); padding: 1px 6px; border-radius: 10px; margin-bottom: 3px;"><?= View::e(Setting::get('mobile_nav_header_badge', 'JOALHERIA AUTORAL')) ?></div>
                                            <div id="previewLogoText" style="font-family: 'Cinzel', serif; font-size: 1.2rem; font-weight: 700; letter-spacing: 0.15em; color: #fff;"><?= View::e(Setting::get('mobile_nav_logo_text', 'DIVINIUM')) ?></div>
                                            <div id="previewLogoSub" style="font-size: 0.45rem; letter-spacing: 0.25em; color: #8e8880;"><?= View::e(Setting::get('mobile_nav_logo_sub', 'HAUTE JOAILLERIE')) ?></div>
                                        </div>
                                        <div style="width: 26px; height: 26px; border-radius: 50%; background: rgba(255,255,255,0.06); display: flex; align-items: center; justify-content: center; font-size: 1rem; color: #aaa;">&times;</div>
                                    </div>

                                    <!-- Links do Mockup -->
                                    <div style="display: flex; flex-direction: column; gap: 4px; margin-top: 12px;">
                                        <div id="previewLinkHome" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; border-radius: 6px; background: rgba(212, 186, 136, 0.08); border-left: 2px solid #d4ba88; color: #fff; font-size: 0.88rem;">
                                            <span id="previewTextHome"><?= View::e(Setting::get('mobile_nav_home_text', 'Início')) ?></span>
                                            <span style="font-size: 0.7rem; color: #d4ba88;">›</span>
                                        </div>

                                        <div id="previewLinkStore" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; border-radius: 6px; font-size: 0.88rem; color: #ded7cf;">
                                            <span id="previewTextStore"><?= View::e(Setting::get('mobile_nav_store_text', 'Boutique / Loja')) ?></span>
                                            <span id="previewBadgeStore" style="font-size: 0.58rem; background: rgba(212, 186, 136, 0.15); color: #d4ba88; padding: 2px 6px; border-radius: 8px; border: 1px solid rgba(212, 186, 136, 0.3);"><?= View::e(Setting::get('mobile_nav_store_badge_text', 'Exclusivo')) ?></span>
                                        </div>

                                        <div id="previewLinkCollections" style="display: flex; flex-direction: column; padding: 8px 10px; border-radius: 6px; font-size: 0.88rem; color: #ded7cf;">
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <span id="previewTextCollections"><?= View::e(Setting::get('mobile_nav_collections_text', 'Coleções')) ?></span>
                                                <span style="font-size: 0.7rem; color: #888;">⌵</span>
                                            </div>
                                            <div style="padding-left: 12px; margin-top: 4px; display: flex; flex-direction: column; gap: 4px; font-size: 0.75rem; color: #999;">
                                                <span>✦ <span id="previewTextSubAll"><?= View::e(Setting::get('mobile_nav_sub_all_text', 'Ver Todas as Peças')) ?></span></span>
                                                <span>✦ <span id="previewTextSubBracelets"><?= View::e(Setting::get('mobile_nav_sub_bracelets_text', 'Braceletes Exclusivos')) ?></span></span>
                                            </div>
                                        </div>

                                        <div id="previewLinkAbout" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; border-radius: 6px; font-size: 0.88rem; color: #ded7cf;">
                                            <span id="previewTextAbout"><?= View::e(Setting::get('mobile_nav_about_text', 'Sobre')) ?></span>
                                            <span style="font-size: 0.7rem; color: #888;">›</span>
                                        </div>

                                        <div id="previewLinkServices" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; border-radius: 6px; font-size: 0.88rem; color: #ded7cf;">
                                            <span id="previewTextServices"><?= View::e(Setting::get('mobile_nav_services_text', 'Garantia & Serviços')) ?></span>
                                            <span style="font-size: 0.7rem; color: #888;">›</span>
                                        </div>

                                        <div id="previewLinkWishlist" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; border-radius: 6px; font-size: 0.88rem; color: #ded7cf;">
                                            <span>♡ <span id="previewTextWishlist"><?= View::e(Setting::get('mobile_nav_wishlist_text', 'Lista de Desejos')) ?></span></span>
                                            <span style="font-size: 0.7rem; color: #888;">›</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rodapé do Mockup -->
                                <div style="display: flex; flex-direction: column; gap: 8px; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.08); margin-top: 12px;">
                                    <div id="previewBtnWhatsapp" style="display: flex; align-items: center; justify-content: center; gap: 6px; background: rgba(37, 211, 102, 0.12); border: 1px solid rgba(37, 211, 102, 0.3); color: #25d366; padding: 8px; border-radius: 8px; font-size: 0.78rem; font-weight: 600;">
                                        <span>💬</span>
                                        <span id="previewTextWhatsapp"><?= View::e(Setting::get('mobile_nav_whatsapp_text', 'Concierge VIP via WhatsApp')) ?></span>
                                    </div>

                                    <div id="previewBtnLogin" style="display: flex; align-items: center; justify-content: center; gap: 6px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.12); color: #ded7cf; padding: 7px; border-radius: 8px; font-size: 0.74rem;">
                                        <span>🔒</span>
                                        <span id="previewTextLogin"><?= View::e(Setting::get('mobile_nav_login_text', 'Entrar no Painel Administrativo')) ?></span>
                                    </div>

                                    <div id="previewPerkWrap" style="text-align: center; color: #8e8880; font-size: 0.62rem; margin-top: 2px;">
                                        <span id="previewTextPerk"><?= View::e(Setting::get('mobile_nav_perk_text', '✨ Joalheria Autoral Brasileira • Prata 925 & Gemas Raras')) ?></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 3: HERO PRINCIPAL, HEADLINE & SELETOR DE MINERAIS -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-hero">
            <div class="section-card-header">
                <div class="section-icon">👑</div>
                <div>
                    <h3>Hero Principal & Seletor de Minerais</h3>
                    <p>Títulos editoriais, subtítulos, botões de ação, selos de autoridade e nomes dos minerais interativos.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="hero_badge_text">Tag Editorial Superior</label>
                    <input type="text" id="hero_badge_text" name="hero_badge_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_badge_text', 'HAUTE JOAILLERIE • EDIÇÃO AUTORAL BRASILEIRA')) ?>"
                           placeholder="HAUTE JOAILLERIE • EDIÇÃO AUTORAL BRASILEIRA">
                </div>

                <div class="form-group">
                    <label for="hero_title_line1">Título Principal (Linha 1)</label>
                    <input type="text" id="hero_title_line1" name="hero_title_line1" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_title_line1', 'Joias que Revelam')) ?>"
                           placeholder="Joias que Revelam">
                </div>

                <div class="form-group">
                    <label for="hero_title_highlight">Título Principal (Destaque Metálico)</label>
                    <input type="text" id="hero_title_highlight" name="hero_title_highlight" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_title_highlight', 'o Luxo Silencioso')) ?>"
                           placeholder="o Luxo Silencioso">
                    <span class="field-hint">Esta parte ganha o gradiente nobre dourado ou prateado.</span>
                </div>

                <div class="form-group full-width">
                    <label for="hero_subtitle">Subtítulo / Descrição Storytelling</label>
                    <textarea id="hero_subtitle" name="hero_subtitle" class="form-textarea" rows="3"
                              placeholder="A nobreza da prata de lei 925 entrelaçada à vibração autêntica de gemas minerais raras..."><?= \App\Core\View::e(Setting::get('hero_subtitle', 'A nobreza da prata de lei 925 entrelaçada à vibração autêntica de gemas minerais raras. Braceletes CAMINHOS: um mesmo design, diferentes formas de expressar a sua presença.')) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="hero_cta_primary_text">Botão Principal - Texto</label>
                    <input type="text" id="hero_cta_primary_text" name="hero_cta_primary_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_cta_primary_text', 'Explorar Coleção Completa')) ?>"
                           placeholder="Explorar Coleção Completa">
                </div>

                <div class="form-group">
                    <label for="hero_cta_primary_link">Botão Principal - Link de Destino</label>
                    <input type="text" id="hero_cta_primary_link" name="hero_cta_primary_link" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_cta_primary_link', '/loja')) ?>"
                           placeholder="/loja ou #collections">
                </div>

                <div class="form-group">
                    <label for="hero_cta_whatsapp_text">Botão Secundário (WhatsApp Concierge) - Texto</label>
                    <input type="text" id="hero_cta_whatsapp_text" name="hero_cta_whatsapp_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_cta_whatsapp_text', 'Concierge WhatsApp')) ?>"
                           placeholder="Concierge WhatsApp">
                </div>

                <div class="form-group">
                    <label for="hero_whatsapp_number">Número WhatsApp (com DDI e DDD)</label>
                    <input type="text" id="hero_whatsapp_number" name="hero_whatsapp_number" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_whatsapp_number', '5511999999999')) ?>"
                           placeholder="5511999999999">
                </div>

                <div class="form-group">
                    <label for="hero_trust_badge_1">Selo de Confiança 1</label>
                    <input type="text" id="hero_trust_badge_1" name="hero_trust_badge_1" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_trust_badge_1', 'Prata de Lei 925')) ?>"
                           placeholder="Prata de Lei 925">
                </div>

                <div class="form-group">
                    <label for="hero_trust_badge_2">Selo de Confiança 2</label>
                    <input type="text" id="hero_trust_badge_2" name="hero_trust_badge_2" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_trust_badge_2', 'Gemas 100% Naturais')) ?>"
                           placeholder="Gemas 100% Naturais">
                </div>

                <div class="form-group">
                    <label for="hero_trust_badge_3">Selo de Confiança 3</label>
                    <input type="text" id="hero_trust_badge_3" name="hero_trust_badge_3" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_trust_badge_3', 'Certificado de Garantia')) ?>"
                           placeholder="Certificado de Garantia">
                </div>

                <div class="form-group full-width" style="background: rgba(202, 161, 92, 0.08); border: 1px solid rgba(202, 161, 92, 0.25); border-radius: 10px; padding: 18px; margin-top: 10px;">
                    <label for="hero_bg_image" style="display: flex; justify-content: space-between; align-items: center; font-weight: 700; color: #caa15c;">
                        <span>🖼️ Imagem de Fundo do Hero (Banner Principal)</span>
                        <a href="/admin/temas" target="_blank" style="color: #fff; font-size: 0.74rem; text-decoration: none; background: rgba(202, 161, 92, 0.2); padding: 2px 8px; border-radius: 6px;">Gerenciar Texturas ↗</a>
                    </label>
                    <div style="display: flex; gap: 10px; margin-top: 8px;">
                        <input type="text" id="hero_bg_image" name="hero_bg_image" class="form-input" 
                               value="<?= \App\Core\View::e(Setting::get('hero_bg_image', '')) ?>" 
                               placeholder="Ex: /assets/images/hero/hero-caminhos.jpg ou link externo">
                        <label class="btn-admin-gold" style="padding: 0 16px; cursor: pointer; display: flex; align-items: center; white-space: nowrap; font-size: 0.8rem;">
                            <span>📁 Fazer Upload</span>
                            <input type="file" accept="image/*" style="display: none;" onchange="uploadHeroEditorBg(this)">
                        </label>
                    </div>
                    <span class="field-hint">Se preenchido, substitui o gradiente padrão da abertura da Home por esta imagem. Deixe vazio para usar o padrão do tema.</span>
                </div>

            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 4: CARROSSEL DE IMAGENS PRINCIPAIS DA HOME -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-stage">
            <div class="section-card-header">
                <div class="section-icon">🎠</div>
                <div>
                    <h3>Carrossel de Imagens Principais da Home (Alta Joalheria)</h3>
                    <p>Gerencie as fotografias, links, textos e a reprodução automática do carrossel exibido na abertura da Home.</p>
                </div>
            </div>

            <div class="form-grid">
                <!-- Configurações Gerais do Carrossel -->
                <div class="form-group" style="background: rgba(212, 186, 136, 0.05); padding: 14px 16px; border-radius: 8px; border: 1px solid rgba(212, 186, 136, 0.15);">
                    <div class="field-label-row">
                        <label style="color: #ffffff; font-weight: 600; font-size: 0.88rem;">Reprodução Automática (Autoplay)</label>
                        <?= $renderToggle('hero_carousel_autoplay') ?>
                    </div>
                    <span class="field-hint" style="margin-top: 2px;">Alterna as imagens automaticamente com transição suave. Pausa ao passar o cursor ou tocar no carrossel.</span>
                </div>

                <div class="form-group" style="background: rgba(255, 255, 255, 0.02); padding: 14px 16px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.06);">
                    <label for="hero_carousel_interval" style="color: #ffffff; font-weight: 600; font-size: 0.88rem;">Tempo de Exibição por Slide</label>
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 6px;">
                        <input type="number" id="hero_carousel_interval" name="hero_carousel_interval" class="form-input" min="2" max="30" step="1"
                               value="<?= View::e(Setting::get('hero_carousel_interval', '5')) ?>" style="width: 100px;">
                        <span style="font-size: 0.82rem; color: #b5ada3;">segundos por imagem</span>
                    </div>
                    <span class="field-hint">Intervalo entre trocas de slide (recomendado: 4 a 7 segundos).</span>
                </div>

                <!-- Input Hidden com os Slides Serializados em JSON -->
                <input type="hidden" name="hero_carousel_slides" id="heroCarouselSlidesJson" value="<?= View::e(Setting::get('hero_carousel_slides', '')) ?>">
                <input type="hidden" name="hero_editorial_main_img" id="hero_editorial_main_img_sync" value="<?= View::e(Setting::get('hero_editorial_main_img', '')) ?>">
                <input type="hidden" name="hero_editorial_sec_img" id="hero_editorial_sec_img_sync" value="<?= View::e(Setting::get('hero_editorial_sec_img', '')) ?>">

                <!-- Gerenciador Visual de Slides -->
                <div class="form-group full-width" style="margin-top: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 6px;">
                        <div>
                            <h4 style="color: #d4ba88; font-size: 0.95rem; font-weight: 600; margin: 0;">Imagens do Carrossel (<span id="carouselSlidesCount">0</span>)</h4>
                            <span class="field-hint" style="margin: 2px 0 0;">Adicione, reordene, configure links e textos alternativos para cada imagem.</span>
                        </div>
                        <button type="button" class="btn-admin-gold" onclick="addNewCarouselSlide()" style="font-size: 0.8rem; padding: 8px 16px;">
                            <span>+ Adicionar Nova Imagem</span>
                        </button>
                    </div>

                    <div id="carouselSlidesContainer" class="carousel-slides-manager">
                        <!-- Renderizado via JavaScript -->
                    </div>

                    <button type="button" class="btn-add-carousel-slide" onclick="addNewCarouselSlide()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>+ Adicionar Outra Imagem ao Carrossel da Home</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 5: MANIFESTO & SOBRE -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-about">
            <div class="section-card-header">
                <div class="section-icon">📜</div>
                <div>
                    <h3>Manifesto & Sobre a Marca</h3>
                    <p>A história editorial, pilares conceituais, fotos e a essência do quiet luxury.</p>
                </div>
            </div>

            <div class="form-grid">
                <!-- ============================================================== -->
                <!-- PALETA DE CORES & ESTÉTICA VISUAL DO MANIFESTO -->
                <!-- ============================================================== -->
                <div class="form-group full-width" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 16px; margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <div>
                            <h4 style="color: #d4ba88; margin: 0 0 4px; font-size: 0.98rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                                <span>🎨</span> 1. Cores & Estética Visual do Manifesto
                            </h4>
                            <span class="field-hint">Personalize as cores de fundo, tipografia, destaque dourado, botões e cards com visualização ao vivo antes de salvar.</span>
                        </div>

                        <!-- Presets Rápidos de Cores -->
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <button type="button" class="btn-admin-secondary" style="padding: 6px 11px; font-size: 0.76rem;" onclick="applyAboutColorPreset('default')">
                                <span>🖤 Ônix & Ouro (Padrão)</span>
                            </button>
                            <button type="button" class="btn-admin-secondary" style="padding: 6px 11px; font-size: 0.76rem;" onclick="applyAboutColorPreset('light')">
                                <span>🤍 Editorial Claro</span>
                            </button>
                            <button type="button" class="btn-admin-secondary" style="padding: 6px 11px; font-size: 0.76rem;" onclick="applyAboutColorPreset('emerald')">
                                <span>💚 Esmeralda & Ouro</span>
                            </button>
                            <button type="button" class="btn-admin-secondary" style="padding: 6px 11px; font-size: 0.76rem;" onclick="applyAboutColorPreset('sapphire')">
                                <span>💎 Safira Noturna</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="about_bg_color">Cor de Fundo da Seção</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="about_bg_color_picker" 
                               value="<?= Setting::get('about_bg_color', '#0d0c0b') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('about_bg_color').value = this.value; updateAboutLivePreview();">
                        <input type="text" id="about_bg_color" name="about_bg_color" class="form-input" 
                               value="<?= View::e(Setting::get('about_bg_color', '#0d0c0b')) ?>" 
                               placeholder="#0d0c0b"
                               oninput="if(this.value.length===7){document.getElementById('about_bg_color_picker').value=this.value;} updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Fundo principal (sugestões: #0d0c0b, #141210, #fbf9f5).</span>
                </div>

                <div class="form-group">
                    <label for="about_title_color">Cor do Título Principal</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="about_title_color_picker" 
                               value="<?= Setting::get('about_title_color', '#ffffff') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('about_title_color').value = this.value; updateAboutLivePreview();">
                        <input type="text" id="about_title_color" name="about_title_color" class="form-input" 
                               value="<?= View::e(Setting::get('about_title_color', '#ffffff')) ?>" 
                               placeholder="#ffffff"
                               oninput="if(this.value.length===7){document.getElementById('about_title_color_picker').value=this.value;} updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Cor da tipografia serifada de destaque.</span>
                </div>

                <div class="form-group">
                    <label for="about_text_color">Cor dos Parágrafos / Textos</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="about_text_color_picker" 
                               value="<?= Setting::get('about_text_color', '#ded7cf') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('about_text_color').value = this.value; updateAboutLivePreview();">
                        <input type="text" id="about_text_color" name="about_text_color" class="form-input" 
                               value="<?= View::e(Setting::get('about_text_color', '#ded7cf')) ?>" 
                               placeholder="#ded7cf"
                               oninput="if(this.value.length===7){document.getElementById('about_text_color_picker').value=this.value;} updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Cor dos parágrafos descritivos da história.</span>
                </div>

                <div class="form-group">
                    <label for="about_accent_color">Cor de Destaque / Ouro (Citação & Detalhes)</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="about_accent_color_picker" 
                               value="<?= Setting::get('about_accent_color', '#d4ba88') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('about_accent_color').value = this.value; updateAboutLivePreview();">
                        <input type="text" id="about_accent_color" name="about_accent_color" class="form-input" 
                               value="<?= View::e(Setting::get('about_accent_color', '#d4ba88')) ?>" 
                               placeholder="#d4ba88"
                               oninput="if(this.value.length===7){document.getElementById('about_accent_color_picker').value=this.value;} updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Cor da citação poética em itálico e toques nobres.</span>
                </div>

                <div class="form-group">
                    <label for="about_tag_color">Cor da Tag / Selo Superior</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="about_tag_color_picker" 
                               value="<?= Setting::get('about_tag_color', '#d4ba88') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('about_tag_color').value = this.value; updateAboutLivePreview();">
                        <input type="text" id="about_tag_color" name="about_tag_color" class="form-input" 
                               value="<?= View::e(Setting::get('about_tag_color', '#d4ba88')) ?>" 
                               placeholder="#d4ba88"
                               oninput="if(this.value.length===7){document.getElementById('about_tag_color_picker').value=this.value;} updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Cor do selo 'MANIFESTO DIVINIUM' acima do título.</span>
                </div>

                <div class="form-group">
                    <label for="about_btn_color">Cor do Botão de Ação (Borda & Texto)</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="about_btn_color_picker" 
                               value="<?= Setting::get('about_btn_color', '#d4ba88') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('about_btn_color').value = this.value; updateAboutLivePreview();">
                        <input type="text" id="about_btn_color" name="about_btn_color" class="form-input" 
                               value="<?= View::e(Setting::get('about_btn_color', '#d4ba88')) ?>" 
                               placeholder="#d4ba88"
                               oninput="if(this.value.length===7){document.getElementById('about_btn_color_picker').value=this.value;} updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Cor do botão 'Conheça Nossa História'.</span>
                </div>

                <div class="form-group">
                    <label for="about_card_bg">Fundo do Card da Miniatura Macro</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="color" id="about_card_bg_picker" 
                               value="<?= Setting::get('about_card_bg', '#161513') ?>" 
                               style="width: 42px; height: 38px; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; cursor: pointer; background: transparent; padding: 2px;"
                               oninput="document.getElementById('about_card_bg').value = this.value; updateAboutLivePreview();">
                        <input type="text" id="about_card_bg" name="about_card_bg" class="form-input" 
                               value="<?= View::e(Setting::get('about_card_bg', '#161513')) ?>" 
                               placeholder="#161513"
                               oninput="if(this.value.length===7){document.getElementById('about_card_bg_picker').value=this.value;} updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Cor de fundo do card de textura mineral.</span>
                </div>

                <div class="form-group">
                    <label for="about_card_border">Borda do Card da Miniatura Macro</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="text" id="about_card_border" name="about_card_border" class="form-input" 
                               value="<?= View::e(Setting::get('about_card_border', 'rgba(212, 186, 136, 0.25)')) ?>" 
                               placeholder="rgba(212, 186, 136, 0.25)"
                               oninput="updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Ex: rgba(212, 186, 136, 0.25) ou #332d24.</span>
                </div>

                <div class="form-group full-width">
                    <label for="about_caption_bg">Fundo da Legenda Editorial da Foto</label>
                    <div style="display: flex; gap: 8px; align-items: center; max-width: 450px;">
                        <input type="text" id="about_caption_bg" name="about_caption_bg" class="form-input" 
                               value="<?= View::e(Setting::get('about_caption_bg', 'rgba(18, 17, 16, 0.85)')) ?>" 
                               placeholder="rgba(18, 17, 16, 0.85)"
                               oninput="updateAboutLivePreview();">
                    </div>
                    <span class="field-hint">Fundo translúcido da barra inferior da foto editorial lateral.</span>
                </div>

                <!-- PREVIEW AO VIVO DO MANIFESTO NO PAINEL -->
                <div class="form-group full-width" style="margin: 10px 0 20px;">
                    <label style="color: #d4ba88; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 6px;">
                        <span>👁️</span> Pré-Visualização em Tempo Real do Manifesto
                    </label>
                    <div id="aboutLivePreviewContainer" style="background: <?= View::e(Setting::get('about_bg_color', '#0d0c0b')) ?>; border: 1px solid rgba(212,186,136,0.3); border-radius: 12px; padding: 26px; transition: background 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 24px; align-items: center;">
                            <!-- Coluna de Textos -->
                            <div>
                                <span id="aboutPreviewTag" style="display: inline-block; font-size: 0.72rem; letter-spacing: 0.15em; font-weight: 600; text-transform: uppercase; color: <?= View::e(Setting::get('about_tag_color', '#d4ba88')) ?>; margin-bottom: 8px;">
                                    <?= View::e(Setting::get('about_tag', 'MANIFESTO DIVINIUM')) ?>
                                </span>
                                <h3 id="aboutPreviewTitle" style="font-family: serif; font-size: 1.45rem; line-height: 1.25; margin: 0 0 12px; font-weight: normal; color: <?= View::e(Setting::get('about_title_color', '#ffffff')) ?>;">
                                    <?= View::e(Setting::get('about_title', 'A Beleza Não Precisa Gritar Para Ser Notada')) ?>
                                </h3>
                                <p id="aboutPreviewP1" style="font-size: 0.84rem; line-height: 1.6; margin: 0 0 10px; color: <?= View::e(Setting::get('about_text_color', '#ded7cf')) ?>;">
                                    <?= View::e(Setting::get('about_p1', 'A DIVINIUM nasceu da convicção de que o verdadeiro luxo é autoral, sutil e profundamente conectado à sua identidade...')) ?>
                                </p>
                                <p id="aboutPreviewQuote" style="font-family: serif; font-style: italic; font-size: 1.05rem; margin: 0 0 16px; color: <?= View::e(Setting::get('about_accent_color', '#d4ba88')) ?>;">
                                    "<?= View::e(Setting::get('about_quote', 'Qual delas conversa com você hoje?')) ?>"
                                </p>
                                <div style="margin-bottom: 16px;">
                                    <span id="aboutPreviewBtn" style="display: inline-block; padding: 7px 20px; font-size: 0.76rem; border: 1px solid <?= View::e(Setting::get('about_btn_color', '#d4ba88')) ?>; color: <?= View::e(Setting::get('about_btn_color', '#d4ba88')) ?>; border-radius: 20px; letter-spacing: 0.05em; font-weight: 500;">
                                        <?= View::e(Setting::get('about_cta_text', 'Conheça Nossa História')) ?>
                                    </span>
                                </div>
                                <!-- Mini Card -->
                                <div id="aboutPreviewCard" style="display: flex; gap: 12px; align-items: center; padding: 12px; border-radius: 8px; background: <?= View::e(Setting::get('about_card_bg', '#161513')) ?>; border: 1px solid <?= View::e(Setting::get('about_card_border', 'rgba(212, 186, 136, 0.25)')) ?>; max-width: 320px;">
                                    <div style="width: 44px; height: 44px; border-radius: 6px; overflow: hidden; flex-shrink: 0; background: #222;">
                                        <img src="<?= View::e(Setting::get('about_micro_img', '/assets/images/about/macro-texture.jpg')) ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='/assets/images/about/macro-texture.png'">
                                    </div>
                                    <div>
                                        <strong id="aboutPreviewCardTitle" style="display: block; font-size: 0.8rem; color: <?= View::e(Setting::get('about_title_color', '#ffffff')) ?>;">
                                            <?= View::e(Setting::get('about_micro_title', 'Textura Mineral Autêntica')) ?>
                                        </strong>
                                        <span id="aboutPreviewCardDesc" style="font-size: 0.7rem; color: <?= View::e(Setting::get('about_text_color', '#ded7cf')) ?>;">
                                            <?= View::e(Setting::get('about_micro_desc', 'Fragmentos minerais nobres preservados com resina.')) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Coluna Imagem Editorial -->
                            <div style="position: relative; border-radius: 8px; overflow: hidden; height: 280px; box-shadow: 0 4px 15px rgba(0,0,0,0.4);">
                                <img src="<?= View::e(Setting::get('about_banner_img', '/assets/images/about/banner-caminhos.jpg')) ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='/assets/images/about/banner-caminhos.png'">
                                <div id="aboutPreviewCaption" style="position: absolute; bottom: 8px; left: 8px; right: 8px; padding: 6px 10px; border-radius: 4px; font-size: 0.65rem; text-align: center; letter-spacing: 0.1em; font-weight: 600; background: <?= View::e(Setting::get('about_caption_bg', 'rgba(18, 17, 16, 0.85)')) ?>; color: <?= View::e(Setting::get('about_accent_color', '#d4ba88')) ?>;">
                                    <?= View::e(Setting::get('about_editorial_caption', 'COLEÇÃO CAMINHOS • UM MESMO DESIGN, DIFERENTES FORMAS DE SENTIR')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- 2. TEXTOS, CHAMADAS E FOTOS DO MANIFESTO -->
                <!-- ============================================================== -->
                <div class="form-group full-width" style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px; margin-top: 8px;">
                    <h4 style="color: #d4ba88; margin: 0 0 4px; font-size: 0.98rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                        <span>✍️</span> 2. Textos, Chamadas & Imagens do Manifesto
                    </h4>
                    <span class="field-hint">Altere o conteúdo editorial do Manifesto Divinium.</span>
                </div>

                <div class="form-group">
                    <label for="about_tag">Tag da Seção</label>
                    <input type="text" id="about_tag" name="about_tag" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_tag', 'MANIFESTO DIVINIUM')) ?>"
                           placeholder="MANIFESTO DIVINIUM"
                           oninput="updateAboutLivePreview()">
                </div>

                <div class="form-group">
                    <label for="about_title">Título Principal do Manifesto</label>
                    <input type="text" id="about_title" name="about_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_title', 'A Beleza Não Precisa Gritar Para Ser Notada')) ?>"
                           placeholder="A Beleza Não Precisa Gritar Para Ser Notada"
                           oninput="updateAboutLivePreview()">
                </div>

                <div class="form-group full-width">
                    <label for="about_p1">Primeiro Parágrafo do Manifesto</label>
                    <textarea id="about_p1" name="about_p1" class="form-textarea" rows="3" oninput="updateAboutLivePreview()"><?= \App\Core\View::e(Setting::get('about_p1', 'A DIVINIUM nasceu da convicção de que o verdadeiro luxo é autoral, sutil e profundamente conectado à sua identidade. Em um mercado de réplicas e produções em massa, escolhemos a rota da nobreza mineral.')) ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="about_p2">Segundo Parágrafo do Manifesto</label>
                    <textarea id="about_p2" name="about_p2" class="form-textarea" rows="3"><?= \App\Core\View::e(Setting::get('about_p2', 'Cada gema é única em suas veias, coloração e densidade. Encastoadas à mão sobre o aro duplo em prata, as pedras ganham vida e presença táctil sem jamais pesar no pulso.')) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="about_quote">Frase / Pergunta Poética de Destaque</label>
                    <input type="text" id="about_quote" name="about_quote" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_quote', 'Qual delas conversa com você hoje?')) ?>"
                           placeholder="Qual delas conversa com você hoje?"
                           oninput="updateAboutLivePreview()">
                </div>

                <div class="form-group">
                    <label for="about_cta_text">Texto do Botão de Ação</label>
                    <input type="text" id="about_cta_text" name="about_cta_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_cta_text', 'Conheça Nossa História')) ?>"
                           placeholder="Conheça Nossa História"
                           oninput="updateAboutLivePreview()">
                </div>

                <!-- Foto e Macro do Sobre -->
                <div class="form-group">
                    <label for="about_micro_title">Título da Miniatura Macro</label>
                    <input type="text" id="about_micro_title" name="about_micro_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_micro_title', 'Textura Mineral Autêntica')) ?>"
                           oninput="updateAboutLivePreview()">
                </div>

                <div class="form-group">
                    <label for="about_micro_desc">Descrição da Miniatura Macro</label>
                    <input type="text" id="about_micro_desc" name="about_micro_desc" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_micro_desc', 'Fragmentos minerais nobres preservados com resina de alta transparência.')) ?>"
                           oninput="updateAboutLivePreview()">
                </div>

                <div class="form-group">
                    <label for="about_micro_img">Caminho da Imagem Macro</label>
                    <input type="text" id="about_micro_img" name="about_micro_img" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_micro_img', '/assets/images/about/macro-texture.jpg')) ?>">
                </div>

                <div class="form-group">
                    <label for="about_banner_img">Caminho da Imagem Editorial Lateral</label>
                    <input type="text" id="about_banner_img" name="about_banner_img" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_banner_img', '/assets/images/about/banner-caminhos.jpg')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="about_editorial_caption">Legenda da Foto Editorial</label>
                    <input type="text" id="about_editorial_caption" name="about_editorial_caption" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_editorial_caption', 'COLEÇÃO CAMINHOS • UM MESMO DESIGN, DIFERENTES FORMAS DE SENTIR')) ?>"
                           oninput="updateAboutLivePreview()">
                </div>

                <!-- Pilares Conceituais -->
                <div class="form-group">
                    <label for="about_pillar1_title">Pilar 1 - Título</label>
                    <input type="text" id="about_pillar1_title" name="about_pillar1_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_pillar1_title', 'Design Autoral & Exclusivo')) ?>">
                </div>

                <div class="form-group">
                    <label for="about_pillar1_desc">Pilar 1 - Descrição</label>
                    <input type="text" id="about_pillar1_desc" name="about_pillar1_desc" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_pillar1_desc', 'Projetado no Brasil por mestres ourives, com tiragens limitadas e acabamento impecável.')) ?>">
                </div>

                <div class="form-group">
                    <label for="about_pillar2_title">Pilar 2 - Título</label>
                    <input type="text" id="about_pillar2_title" name="about_pillar2_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_pillar2_title', 'Metais Nobres & Gemas')) ?>">
                </div>

                <div class="form-group">
                    <label for="about_pillar2_desc">Pilar 2 - Descrição</label>
                    <input type="text" id="about_pillar2_desc" name="about_pillar2_desc" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_pillar2_desc', 'Prata de Lei 925 legítima aliada a pedras naturais selecionadas uma a uma.')) ?>">
                </div>

                <div class="form-group">
                    <label for="about_pillar3_title">Pilar 3 - Título</label>
                    <input type="text" id="about_pillar3_title" name="about_pillar3_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_pillar3_title', 'Garantia & Autenticidade')) ?>">
                </div>

                <div class="form-group">
                    <label for="about_pillar3_desc">Pilar 3 - Descrição</label>
                    <input type="text" id="about_pillar3_desc" name="about_pillar3_desc" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_pillar3_desc', 'Cada peça é acompanhada do certificado oficial de autenticidade e pureza dos materiais.')) ?>">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 6: VITRINE DE PRODUTOS & FILTROS NA HOME -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-collections">
            <div class="section-card-header">
                <div class="section-icon">✦</div>
                <div>
                    <h3>Vitrine de Produtos, Filtros & Card Lifestyle na Home</h3>
                    <p>Configurações legadas de texto da vitrine. Para gerenciar coleções por categoria, utilize a nova aba Lançamentos.</p>
                </div>
            </div>

            <div style="background: rgba(212,186,136,0.1); border: 1px solid rgba(212,186,136,0.25); border-radius: 8px; padding: 14px 18px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 15px;">
                <div>
                    <strong style="color: #d4ba88; display: block; margin-bottom: 4px;">🚀 Gerenciamento de Coleções centralizado na aba Lançamentos</strong>
                    <span style="color: #a8a096; font-size: 0.85rem;">Você pode criar, editar, reordenar coleções e gerenciar produtos separados por categoria (Braceletes, Colares, Anéis) na aba Lançamentos.</span>
                </div>
                <a href="/admin/lancamentos" class="btn-admin-gold" style="white-space: nowrap; font-size: 0.82rem; padding: 8px 16px; text-decoration: none;">
                    Ir para Lançamentos ↗
                </a>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="collections_tag">Tag da Seção</label>
                    <input type="text" id="collections_tag" name="collections_tag" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('collections_tag', 'SELEÇÃO EXCLUSIVA')) ?>"
                           placeholder="SELEÇÃO EXCLUSIVA">
                </div>

                <div class="form-group">
                    <label for="collections_title">Título da Vitrine</label>
                    <input type="text" id="collections_title" name="collections_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('collections_title', 'Braceletes CAMINHOS')) ?>"
                           placeholder="Braceletes CAMINHOS">
                </div>

                <!-- Filtros em Pílulas da Vitrine -->
                <div class="form-group">
                    <label for="filter_pill_all">Pílula: Todas as Peças</label>
                    <input type="text" id="filter_pill_all" name="filter_pill_all" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('filter_pill_all', 'Todas as Peças')) ?>">
                </div>

                <div class="form-group">
                    <label for="filter_pill_verde">Pílula: Quartzo Verde</label>
                    <input type="text" id="filter_pill_verde" name="filter_pill_verde" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('filter_pill_verde', 'Quartzo Verde')) ?>">
                </div>

                <div class="form-group">
                    <label for="filter_pill_madre">Pílula: Madrepérola</label>
                    <input type="text" id="filter_pill_madre" name="filter_pill_madre" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('filter_pill_madre', 'Madrepérola')) ?>">
                </div>

                <div class="form-group">
                    <label for="filter_pill_verm">Pílula: Jaspe Vermelho</label>
                    <input type="text" id="filter_pill_verm" name="filter_pill_verm" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('filter_pill_verm', 'Jaspe Vermelho')) ?>">
                </div>

                <div class="form-group">
                    <label for="filter_pill_onix">Pílula: Ônix Negro</label>
                    <input type="text" id="filter_pill_onix" name="filter_pill_onix" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('filter_pill_onix', 'Ônix Negro')) ?>">
                </div>

                <!-- Card Editorial Lateral (Coleção no Espelho) -->
                <div class="form-group">
                    <label for="showcase_lifestyle_title">Card Espelho - Título</label>
                    <input type="text" id="showcase_lifestyle_title" name="showcase_lifestyle_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('showcase_lifestyle_title', 'Harmonia Mineral no Espelho')) ?>">
                </div>

                <div class="form-group">
                    <label for="showcase_lifestyle_img">Card Espelho - Imagem</label>
                    <input type="text" id="showcase_lifestyle_img" name="showcase_lifestyle_img" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('showcase_lifestyle_img', '/assets/images/products/colecao-completa-espelho.jpg')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="showcase_lifestyle_text">Card Espelho - Descrição</label>
                    <textarea id="showcase_lifestyle_text" name="showcase_lifestyle_text" class="form-textarea" rows="2"><?= \App\Core\View::e(Setting::get('showcase_lifestyle_text', 'Seis caminhos, seis vibrações. Combine mais de uma cor para expressar as diferentes nuances da sua presença.')) ?></textarea>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 7: SERVIÇOS & GARANTIA -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-services">
            <div class="section-card-header">
                <div class="section-icon">🛡️</div>
                <div>
                    <h3>Experiência de Serviços Exclusivos</h3>
                    <p>Destaques sobre atendimento sob medida, pós-venda e garantia.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="services_title">Título Principal dos Serviços</label>
                    <input type="text" id="services_title" name="services_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('services_title', 'Experiência de Serviços Exclusivos')) ?>"
                           placeholder="Experiência de Serviços Exclusivos">
                </div>

                <div class="form-group">
                    <label for="services_subtitle">Subtítulo Explicativo</label>
                    <input type="text" id="services_subtitle" name="services_subtitle" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('services_subtitle', 'Mais do que uma joia — um vínculo de bem-estar e sintonia. Cada bracelete Divinium é acompanhado de cuidados minuciosos e suporte sob medida.')) ?>">
                </div>

                <!-- Serviço 01 -->
                <div class="form-group full-width" style="margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 15px;">
                    <label for="service_1_title" style="color: #caa15c; font-weight: 700;">Serviço 01 - Título</label>
                    <input type="text" id="service_1_title" name="service_1_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_1_title', 'Consultoria de Sintonia Mineral')) ?>">
                </div>
                <div class="form-group">
                    <label for="service_1_item_1">Serviço 01 - Item 1</label>
                    <input type="text" id="service_1_item_1" name="service_1_item_1" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_1_item_1', 'Ajuda personalizada para descobrir a pedra que melhor conversa com seu momento')) ?>">
                </div>
                <div class="form-group">
                    <label for="service_1_item_2">Serviço 01 - Item 2</label>
                    <input type="text" id="service_1_item_2" name="service_1_item_2" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_1_item_2', 'Harmonização de presentes inesquecíveis para pessoas queridas')) ?>">
                </div>
                <div class="form-group full-width">
                    <label for="service_1_item_3">Serviço 01 - Item 3</label>
                    <input type="text" id="service_1_item_3" name="service_1_item_3" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_1_item_3', 'Atendimento direto pelo nosso Concierge no WhatsApp')) ?>">
                </div>

                <!-- Serviço 02 -->
                <div class="form-group full-width" style="margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 15px;">
                    <label for="service_2_title" style="color: #caa15c; font-weight: 700;">Serviço 02 - Título</label>
                    <input type="text" id="service_2_title" name="service_2_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_2_title', 'Cuidado Artesanal & Manutenção da Prata')) ?>">
                </div>
                <div class="form-group">
                    <label for="service_2_item_1">Serviço 02 - Item 1</label>
                    <input type="text" id="service_2_item_1" name="service_2_item_1" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_2_item_1', 'Orientações de limpeza e conservação natural da prata de lei')) ?>">
                </div>
                <div class="form-group">
                    <label for="service_2_item_2">Serviço 02 - Item 2</label>
                    <input type="text" id="service_2_item_2" name="service_2_item_2" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_2_item_2', 'Polimento artesanal para renovação periódica do brilho original')) ?>">
                </div>
                <div class="form-group full-width">
                    <label for="service_2_item_3">Serviço 02 - Item 3</label>
                    <input type="text" id="service_2_item_3" name="service_2_item_3" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_2_item_3', 'Proteção e impermeabilização da resina mineral')) ?>">
                </div>

                <!-- Serviço 03 -->
                <div class="form-group full-width" style="margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 15px;">
                    <label for="service_3_title" style="color: #caa15c; font-weight: 700;">Serviço 03 - Título</label>
                    <input type="text" id="service_3_title" name="service_3_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_3_title', 'Ajuste de Conforto & Garantia')) ?>">
                </div>
                <div class="form-group">
                    <label for="service_3_item_1">Serviço 03 - Item 1</label>
                    <input type="text" id="service_3_item_1" name="service_3_item_1" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_3_item_1', 'Aro anatômico maleável que se molda gentilmente ao formato do seu pulso')) ?>">
                </div>
                <div class="form-group">
                    <label for="service_3_item_2">Serviço 03 - Item 2</label>
                    <input type="text" id="service_3_item_2" name="service_3_item_2" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_3_item_2', 'Garantia contra defeitos de fabricação e desprendimento de gemas')) ?>">
                </div>
                <div class="form-group full-width">
                    <label for="service_3_item_3">Serviço 03 - Item 3</label>
                    <input type="text" id="service_3_item_3" name="service_3_item_3" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('service_3_item_3', 'Certificado de autenticidade dos minerais naturais')) ?>">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 8: CADA PEÇA É ÚNICA & HERANÇA -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-heritage">
            <div class="section-card-header">
                <div class="section-icon">👑</div>
                <div>
                    <h3>Cada Peça é Única & Herança Autoral</h3>
                    <p>Controle os textos, chamadas e imagem editorial da seção "Desde 2026, Cada Peça é Única".</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="heritage_tag">Tag da Seção</label>
                    <input type="text" id="heritage_tag" name="heritage_tag" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('heritage_tag', 'SOFISTICAÇÃO & NATUREZA')) ?>"
                           placeholder="SOFISTICAÇÃO & NATUREZA">
                </div>

                <div class="form-group">
                    <label for="heritage_title">Título da Seção</label>
                    <input type="text" id="heritage_title" name="heritage_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('heritage_title', 'Cada Peça é Única')) ?>"
                           placeholder="Cada Peça é Única">
                </div>

                <div class="form-group full-width">
                    <label for="heritage_p1">Primeiro Parágrafo</label>
                    <textarea id="heritage_p1" name="heritage_p1" class="form-textarea" rows="3"><?= \App\Core\View::e(Setting::get('heritage_p1', 'A natureza não se repete, e nós respeitamos esse princípio. Quando você adquire um bracelete Divinium, tem a certeza de que nenhuma outra pessoa no mundo possui um exemplar idêntico ao seu.')) ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="heritage_p2">Segundo Parágrafo</label>
                    <textarea id="heritage_p2" name="heritage_p2" class="form-textarea" rows="3"><?= \App\Core\View::e(Setting::get('heritage_p2', 'O aro duplo oferece estabilidade elegante e leveza incomparável no antebraço, transitando com naturalidade do visual casual diário a eventos de gala.')) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="heritage_cta_text">Texto do Botão de Ação</label>
                    <input type="text" id="heritage_cta_text" name="heritage_cta_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('heritage_cta_text', 'Escolher Meu Bracelete')) ?>"
                           placeholder="Escolher Meu Bracelete">
                </div>

                <div class="form-group">
                    <label for="heritage_banner_img">Caminho da Imagem Editorial</label>
                    <input type="text" id="heritage_banner_img" name="heritage_banner_img" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('heritage_banner_img', '/assets/images/about/banner-unica.jpg')) ?>"
                           placeholder="/assets/images/about/banner-unica.jpg">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 9: DEPOIMENTOS & AVALIAÇÕES (PROVA SOCIAL) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-testimonials">
            <div class="section-card-header">
                <div class="section-icon">💬</div>
                <div>
                    <h3>Depoimentos & Avaliações de Clientes</h3>
                    <p>Edite os textos de aprovação, estrelas e depoimento destacado na Home.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="testimonials_title">Título da Seção de Depoimentos</label>
                    <input type="text" id="testimonials_title" name="testimonials_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('testimonials_title', 'Amado por Quem Valoriza a Beleza Atemporal')) ?>"
                           placeholder="Amado por Quem Valoriza a Beleza Atemporal">
                </div>

                <div class="form-group">
                    <label for="testimonial_stars">Avaliação (Estrelas / Símbolos)</label>
                    <input type="text" id="testimonial_stars" name="testimonial_stars" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('testimonial_stars', '★★★★★')) ?>"
                           placeholder="★★★★★">
                </div>

                <div class="form-group">
                    <label for="testimonial_author">Autor do Depoimento</label>
                    <input type="text" id="testimonial_author" name="testimonial_author" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('testimonial_author', '[ Marcela R. • São Paulo/SP • Cliente Verificada ]')) ?>"
                           placeholder="[ Marcela R. • São Paulo/SP • Cliente Verificada ]">
                </div>

                <div class="form-group full-width">
                    <label for="testimonial_quote">Citação / Depoimento Completo</label>
                    <textarea id="testimonial_quote" name="testimonial_quote" class="form-textarea" rows="3"><?= \App\Core\View::e(Setting::get('testimonial_quote', 'Quando coloquei o bracelete no pulso, senti imediatamente a presença e a qualidade do acabamento. A pedra tem uma profundidade de cor inacreditável. Todo mundo elogia e pergunta de onde é!')) ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="testimonial_backdrop_img">Caminho da Imagem de Fundo (Foto da Modelo)</label>
                    <input type="text" id="testimonial_backdrop_img" name="testimonial_backdrop_img" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('testimonial_backdrop_img', '/assets/images/about/model-wrist-sunglasses.jpg')) ?>"
                           placeholder="/assets/images/about/model-wrist-sunglasses.jpg">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 10: TEXTOS DA LOJA / VITRINE GERAL -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-store">
            <div class="section-card-header">
                <div class="section-icon">🛍️</div>
                <div>
                    <h3>Textos da Loja / Vitrine Oficial (/loja)</h3>
                    <p>Altere a headline da boutique, subtítulo, selos de garantia Shopee e pílulas de orientação.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="store_hero_badge">Tag Superior da Loja</label>
                    <input type="text" id="store_hero_badge" name="store_hero_badge" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('store_hero_badge', 'VITRINE OFICIAL DIVINIUM • CHECKOUT GARANTIDO SHOPEE')) ?>"
                           placeholder="VITRINE OFICIAL DIVINIUM • CHECKOUT GARANTIDO SHOPEE">
                </div>

                <div class="form-group">
                    <label for="store_hero_title">Título Principal (Parte 1)</label>
                    <input type="text" id="store_hero_title" name="store_hero_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('store_hero_title', 'Joias Autorais &')) ?>"
                           placeholder="Joias Autorais &">
                </div>

                <div class="form-group">
                    <label for="store_hero_highlight">Título Principal (Destaque Dourado)</label>
                    <input type="text" id="store_hero_highlight" name="store_hero_highlight" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('store_hero_highlight', 'Sintonia Mineral')) ?>"
                           placeholder="Sintonia Mineral">
                </div>

                <div class="form-group full-width">
                    <label for="store_hero_subtitle">Subtítulo da Loja</label>
                    <textarea id="store_hero_subtitle" name="store_hero_subtitle" class="form-textarea" rows="3"><?= \App\Core\View::e(Setting::get('store_hero_subtitle', 'Cada joia Divinium une a precisão da prata de lei 925 à energia de gemas minerais raras e conchas naturais. Escolha sua peça e finalize sua compra com toda a segurança, cupons e rastreamento da nossa Loja Oficial na Shopee.')) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="store_badge_1">Selo de Confiança 1 (Garantia)</label>
                    <input type="text" id="store_badge_1" name="store_badge_1" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('store_badge_1', 'Garantia Shopee • Compra 100% Protegida')) ?>">
                </div>

                <div class="form-group">
                    <label for="store_badge_2">Selo de Confiança 2 (Certificado)</label>
                    <input type="text" id="store_badge_2" name="store_badge_2" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('store_badge_2', 'Certificado de Prata de Lei 925')) ?>">
                </div>

                <div class="form-group">
                    <label for="store_badge_3">Selo de Confiança 3 (Envio)</label>
                    <input type="text" id="store_badge_3" name="store_badge_3" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('store_badge_3', 'Envio Rápido e Seguro para todo o Brasil')) ?>">
                </div>

                <div class="form-group">
                    <label for="store_filter_all">Texto do Filtro "Todas as Peças"</label>
                    <input type="text" id="store_filter_all" name="store_filter_all" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('store_filter_all', 'Todas as Peças')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="store_info_pill_text">Texto de Orientação da Vitrine</label>
                    <input type="text" id="store_info_pill_text" name="store_info_pill_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('store_info_pill_text', 'Clique na peça para comprar na Shopee')) ?>">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 11: CANAIS DE CONTATO, RODAPÉ & LGPD -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-footer">
            <div class="section-card-header">
                <div class="section-icon">📞</div>
                <div>
                    <h3>Canais de Contato, Rodapé & Informações Jurídicas</h3>
                    <p>Edite os dados de atendimento, links institucionais, métodos de pagamento e dados legais.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="footer_tagline">Slogan Institucional da Marca no Rodapé</label>
                    <input type="text" id="footer_tagline" name="footer_tagline" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_tagline', 'Joalheria fina concebida para quem aprecia a nobreza atemporal, o luxo silencioso e o design autoral inconfundível.')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_col1_title">Título da Coluna 1 do Rodapé</label>
                    <input type="text" id="footer_col1_title" name="footer_col1_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_col1_title', 'Coleções')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_col2_title">Título da Coluna 2 do Rodapé</label>
                    <input type="text" id="footer_col2_title" name="footer_col2_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_col2_title', 'Atendimento & Concierge')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_col3_title">Título da Coluna 3 do Rodapé</label>
                    <input type="text" id="footer_col3_title" name="footer_col3_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_col3_title', 'Segurança & Privacidade')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_editions_text">Texto: Edições Numeradas</label>
                    <input type="text" id="footer_editions_text" name="footer_editions_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_editions_text', 'Edições Numeradas')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_whatsapp_label">Texto do Link WhatsApp VIP</label>
                    <input type="text" id="footer_whatsapp_label" name="footer_whatsapp_label" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_whatsapp_label', 'Atendimento via WhatsApp')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_email">E-mail de Atendimento Oficial</label>
                    <input type="email" id="footer_email" name="footer_email" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_email', 'atendimento@divinium.com.br')) ?>"
                           placeholder="atendimento@divinium.com.br">
                </div>

                <div class="form-group">
                    <label for="footer_instagram">Instagram da Marca</label>
                    <input type="text" id="footer_instagram" name="footer_instagram" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_instagram', 'https://instagram.com/diviniumjoias')) ?>"
                           placeholder="https://instagram.com/diviniumjoias">
                </div>

                <div class="form-group">
                    <label for="footer_operating_hours">Horário de Funcionamento do Concierge</label>
                    <input type="text" id="footer_operating_hours" name="footer_operating_hours" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_operating_hours', 'Segunda a Sexta: 09h às 18h')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_returns_text">Link: Trocas e Devoluções</label>
                    <input type="text" id="footer_returns_text" name="footer_returns_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_returns_text', 'Trocas e Devoluções (CDC 7 Dias)')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_warranty_text">Link: Certificado & Cuidados</label>
                    <input type="text" id="footer_warranty_text" name="footer_warranty_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_warranty_text', 'Certificado & Cuidados')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_size_guide_text">Link: Guia de Medidas</label>
                    <input type="text" id="footer_size_guide_text" name="footer_size_guide_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_size_guide_text', 'Guia de Medidas de Anéis')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_privacy_text">Link: Política de Privacidade</label>
                    <input type="text" id="footer_privacy_text" name="footer_privacy_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_privacy_text', 'Política de Privacidade (LGPD)')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_terms_text">Link: Termos de Uso</label>
                    <input type="text" id="footer_terms_text" name="footer_terms_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_terms_text', 'Termos de Uso')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_dpo_text">Link: Encarregado de Dados (DPO)</label>
                    <input type="text" id="footer_dpo_text" name="footer_dpo_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_dpo_text', 'Encarregado de Dados (DPO)')) ?>">
                </div>

                <!-- Selos de Meios de Pagamento -->
                <div class="form-group">
                    <label for="footer_pay_badge1">Selo Pagamento 1</label>
                    <input type="text" id="footer_pay_badge1" name="footer_pay_badge1" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_pay_badge1', 'PIX (Aprovação Imediata)')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_pay_badge2">Selo Pagamento 2</label>
                    <input type="text" id="footer_pay_badge2" name="footer_pay_badge2" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_pay_badge2', 'Cartão de Crédito em até 10x')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_pay_badge3">Selo Pagamento 3</label>
                    <input type="text" id="footer_pay_badge3" name="footer_pay_badge3" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_pay_badge3', 'Boleto Bancário')) ?>">
                </div>

                <div class="form-group">
                    <label for="footer_pay_badge4">Selo Pagamento 4</label>
                    <input type="text" id="footer_pay_badge4" name="footer_pay_badge4" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_pay_badge4', 'Gateway Homologado ASAAS')) ?>">
                </div>

                <!-- Jurídico e Copyright -->
                <div class="form-group full-width">
                    <label for="footer_legal_info">Razão Social & Dados Jurídicos (CDC 7.962/2013)</label>
                    <input type="text" id="footer_legal_info" name="footer_legal_info" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_legal_info', 'DIVINIUM JOIAS LTDA. • CNPJ: 00.000.000/0001-00 • São Paulo/SP - Brasil')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="footer_consumer_rights">Texto de Conformidade Legal e CDC</label>
                    <textarea id="footer_consumer_rights" name="footer_consumer_rights" class="form-textarea" rows="2"><?= \App\Core\View::e(Setting::get('footer_consumer_rights', 'Conforme o Decreto nº 7.962/2013 e Lei 8.078/1990 (Código de Defesa do Consumidor). Direito de arrependimento em até 7 dias corridos após o recebimento.')) ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="footer_copyright_brand">Texto de Direitos Autorais / Copyright</label>
                    <input type="text" id="footer_copyright_brand" name="footer_copyright_brand" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_copyright_brand', 'Divinium. Todos os direitos reservados.')) ?>">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO: TEXTOS & ELEMENTOS DA BOUTIQUE (/loja) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-store">
            <div class="section-card-header">
                <div class="section-icon">🛍️</div>
                <div>
                    <h3>Textos & Chamadas da Boutique (/loja)</h3>
                    <p>Configure os títulos e elementos da vitrine oficial. Você também pode acessar o módulo completo em <a href="/admin/boutique" style="color: #d4ba88; text-decoration: underline;">Personalizar Boutique</a>.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width" style="background: rgba(212, 186, 136, 0.08); border: 1px solid rgba(212, 186, 136, 0.25); border-radius: 8px; padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <strong style="color: #f7f4ee; display: block; margin-bottom: 2px;">💎 Central Executiva da Boutique & Catálogo</strong>
                        <span style="color: #a8a096; font-size: 0.82rem;">Gerencie selos de garantia, seção editorial, botões de múltiplos canais e produtos com links ilimitados.</span>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <a href="/admin/boutique" class="btn-admin-gold" style="font-size: 0.8rem; padding: 8px 14px;">Personalizar Boutique Completa</a>
                        <a href="/admin/produtos" class="btn-admin-secondary" style="font-size: 0.8rem; padding: 8px 14px;">Gerenciar Produtos & Links</a>
                    </div>
                </div>

                <div class="form-group full-width" style="background: rgba(212, 186, 136, 0.05); padding: 12px 16px; border-radius: 8px; border: 1px solid rgba(212, 186, 136, 0.15);">
                    <div class="field-label-row">
                        <label style="color: #ffffff; font-weight: 600; font-size: 0.9rem;">Exibir Hero no topo da Boutique (/loja)</label>
                        <?= $renderToggle('store_hero_visible') ?>
                    </div>
                </div>

                <div class="form-group full-width <?= Setting::get('store_hero_badge_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_hero_badge_lp">Selo Superior da Boutique (Badge)</label>
                        <?= $renderToggle('store_hero_badge_visible') ?>
                    </div>
                    <input type="text" id="store_hero_badge_lp" name="store_hero_badge" class="form-input" 
                           value="<?= View::e(Setting::get('store_hero_badge', 'VITRINE OFICIAL DIVINIUM • CANAIS HOMOLOGADOS')) ?>">
                </div>

                <div class="form-group <?= Setting::get('store_hero_title_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_hero_title_lp">Título da Loja (Primeira Parte)</label>
                        <?= $renderToggle('store_hero_title_visible') ?>
                    </div>
                    <input type="text" id="store_hero_title_lp" name="store_hero_title" class="form-input" 
                           value="<?= View::e(Setting::get('store_hero_title', 'Joias Autorais &')) ?>">
                </div>

                <div class="form-group <?= Setting::get('store_hero_title_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <label for="store_hero_highlight_lp">Destaque em Ouro</label>
                    <input type="text" id="store_hero_highlight_lp" name="store_hero_highlight" class="form-input" 
                           value="<?= View::e(Setting::get('store_hero_highlight', 'Sintonia Mineral')) ?>">
                </div>

                <div class="form-group full-width <?= Setting::get('store_hero_subtitle_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_hero_subtitle_lp">Subtítulo / Descrição Conceitual da Boutique</label>
                        <?= $renderToggle('store_hero_subtitle_visible') ?>
                    </div>
                    <textarea id="store_hero_subtitle_lp" name="store_hero_subtitle" class="form-textarea" rows="2"><?= View::e(Setting::get('store_hero_subtitle', 'Cada joia Divinium une a precisão da prata de lei 925 à energia de gemas minerais raras e conchas naturais. Escolha sua peça e finalize sua compra com toda a segurança, cupons e rastreamento nos nossos canais oficiais homologados.')) ?></textarea>
                </div>

                <div class="form-group <?= Setting::get('store_filter_all_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_filter_all_lp">Rótulo Filtro 'Todas'</label>
                        <?= $renderToggle('store_filter_all_visible') ?>
                    </div>
                    <input type="text" id="store_filter_all_lp" name="store_filter_all" class="form-input" 
                           value="<?= View::e(Setting::get('store_filter_all', 'Todas as Peças')) ?>">
                </div>

                <div class="form-group <?= Setting::get('store_card_badge_metal_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_card_badge_metal_lp">Selo de Metal nos Cards</label>
                        <?= $renderToggle('store_card_badge_metal_visible') ?>
                    </div>
                    <input type="text" id="store_card_badge_metal_lp" name="store_card_badge_metal" class="form-input" 
                           value="<?= View::e(Setting::get('store_card_badge_metal', 'Prata 925')) ?>">
                </div>

                <div class="form-group <?= Setting::get('store_pix_discount_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_pix_discount_label_lp">Rótulo Pix nos Cards</label>
                        <?= $renderToggle('store_pix_discount_visible') ?>
                    </div>
                    <input type="text" id="store_pix_discount_label_lp" name="store_pix_discount_label" class="form-input" 
                           value="<?= View::e(Setting::get('store_pix_discount_label', 'no Pix (-5%)')) ?>">
                </div>

                <div class="form-group <?= Setting::get('store_multi_channel_visible', '1') !== '1' ? 'is-dimmed' : '' ?>">
                    <div class="field-label-row">
                        <label for="store_multi_channel_label_lp">Rótulo 'Disponível também em:'</label>
                        <?= $renderToggle('store_multi_channel_visible') ?>
                    </div>
                    <input type="text" id="store_multi_channel_label_lp" name="store_multi_channel_label" class="form-input" 
                           value="<?= View::e(Setting::get('store_multi_channel_label', 'Disponível também em:')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="store_whatsapp_url_lp">Link do Atendimento WhatsApp na Boutique</label>
                    <input type="text" id="store_whatsapp_url_lp" name="store_whatsapp_url" class="form-input" 
                           value="<?= View::e(Setting::get('store_whatsapp_url', 'https://wa.me/5511999999999?text=Ol%C3%A1,%20estou%20na%20loja%20da%20Divinium%20e%20gostaria%20de%20tirar%20d%C3%BAvidas')) ?>">
                </div>
            </div>
        </div>

        <!-- BARRA FIXA DE SALVAMENTO NO RODAPÉ -->
        <div class="editor-save-dock">
            <div class="save-dock-inner">
                <span class="save-dock-status" id="saveDockStatus">✦ Altere qualquer campo acima e clique em Salvar para atualizar o site ao vivo.</span>
                <button type="submit" class="btn-admin-gold" id="saveEditorSubmitBtn">
                    <span>Salvar Alterações da Landing Page</span>
                </button>
            </div>
        </div>

    </form>
</div>

<!-- SCRIPTS DO EDITOR DA LANDING PAGE -->
<script>
function handleToggleSwitch(chk) {
    const parentLabel = chk.closest('.element-toggle-switch');
    const textSpan = parentLabel ? parentLabel.querySelector('.toggle-text') : null;
    const formGroup = chk.closest('.form-group');

    if (chk.checked) {
        if (parentLabel) {
            parentLabel.classList.add('is-visible');
            parentLabel.classList.remove('is-hidden');
        }
        if (textSpan) textSpan.textContent = 'Exibindo';
        if (formGroup) formGroup.classList.remove('is-dimmed');
    } else {
        if (parentLabel) {
            parentLabel.classList.remove('is-visible');
            parentLabel.classList.add('is-hidden');
        }
        if (textSpan) textSpan.textContent = 'Oculto';
        if (formGroup) formGroup.classList.add('is-dimmed');
    }
}

function switchSection(sectionId, btn) {
    document.querySelectorAll('.editor-nav-tabs .tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.editor-section-card').forEach(card => card.classList.remove('active'));

    btn.classList.add('active');
    const target = document.getElementById(sectionId);
    if (target) {
        target.classList.add('active');
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

async function handleEditorSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('saveEditorSubmitBtn');
    const status = document.getElementById('saveDockStatus');
    
    btn.disabled = true;
    btn.innerHTML = '<span>Salvando no banco de dados...</span>';
    status.innerText = 'Gravando configurações e atualizando o site...';
    status.style.color = '#d4ba88';

    // Sincronizar dados do carrossel antes de coletar o FormData
    if (typeof syncCarouselSlidesToForm === 'function') {
        syncCarouselSlidesToForm();
    }

    const form = document.getElementById('landingPageForm');
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
    if (csrfToken && !formData.has('_csrf_token')) {
        formData.append('_csrf_token', csrfToken);
    }

    try {
        const response = await fetch('/admin/landing-page/salvar', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        const rawText = await response.text();
        let data;
        try {
            data = JSON.parse(rawText);
        } catch (jsonErr) {
            console.error('Resposta não-JSON do servidor:', rawText);
            throw new Error('O servidor respondeu com um formato inesperado. Se você passou muito tempo inativo, recarregue a página.');
        }

        if (data.success) {
            status.innerText = '✓ Sucesso! Todos os textos e conteúdos foram atualizados ao vivo no site.';
            status.style.color = '#4ade80';
            btn.innerHTML = '<span>✓ Salvo com Sucesso!</span>';
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<span>Salvar Alterações da Landing Page</span>';
                status.innerText = '✦ Altere qualquer campo acima e clique em Salvar para atualizar o site ao vivo.';
                status.style.color = '#a8a096';
            }, 2500);
        } else {
            const errorMsg = data.error || 'Erro ao salvar configurações.';
            status.innerText = '✕ ' + errorMsg;
            status.style.color = '#f87171';
            if (data.auth_required) {
                alert('Sua sessão de administrador expirou. Por favor, faça login novamente para salvar as alterações.');
            } else {
                alert(errorMsg);
            }
            btn.disabled = false;
            btn.innerHTML = '<span>Salvar Alterações da Landing Page</span>';
        }
    } catch (err) {
        status.innerText = '✕ ' + err.message;
        status.style.color = '#f87171';
        alert('Erro ao processar a solicitação: ' + err.message);
        btn.disabled = false;
        btn.innerHTML = '<span>Salvar Alterações da Landing Page</span>';
    }
}

async function uploadHeroEditorBg(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    const formData = new FormData();
    formData.append('bg_file', file);
    formData.append('target_key', 'hero_bg_image');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
    if (csrfToken) formData.append('_csrf_token', csrfToken);

    try {
        const res = await fetch('/admin/temas/upload-bg', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const raw = await res.text();
        let data;
        try {
            data = JSON.parse(raw);
        } catch (jsonErr) {
            console.error('Resposta não-JSON do upload:', raw);
            alert('Não foi possível processar o upload da imagem. Recarregue a página.');
            return;
        }

        if (data.auth_required) {
            alert('Sua sessão expirou. Por favor, faça login novamente para enviar imagens.');
            return;
        }

        if (data.success && data.url) {
            document.getElementById('hero_bg_image').value = data.url;
            alert(data.message || 'Imagem de fundo do Hero enviada com sucesso!');
        } else {
            alert(data.error || 'Erro ao enviar imagem.');
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
    }
}

async function uploadEditorialImage(input, targetId) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    const formData = new FormData();
    formData.append('bg_file', file);
    formData.append('target_key', targetId);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
    if (csrfToken) formData.append('_csrf_token', csrfToken);

    try {
        const res = await fetch('/admin/temas/upload-bg', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const raw = await res.text();
        let data;
        try {
            data = JSON.parse(raw);
        } catch (jsonErr) {
            console.error('Resposta não-JSON do upload:', raw);
            alert('Não foi possível processar o upload da imagem. Recarregue a página.');
            return;
        }

        if (data.auth_required) {
            alert('Sua sessão expirou. Por favor, faça login novamente para enviar imagens.');
            return;
        }

        if (data.success && data.url) {
            const targetEl = document.getElementById(targetId);
            if (targetEl) targetEl.value = data.url;
            alert(data.message || 'Imagem enviada com sucesso! Clique em Salvar para publicar.');
        } else {
            alert(data.error || 'Erro ao enviar imagem.');
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
    }
}

// ==============================================================================
// GERENCIADOR VISUAL DE SLIDES DO CARROSSEL DA HOME
// ==============================================================================
function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

let currentCarouselSlides = [];

function initCarouselSlidesManager() {
    const jsonInput = document.getElementById('heroCarouselSlidesJson');
    let loaded = [];
    if (jsonInput && jsonInput.value) {
        try {
            const parsed = JSON.parse(jsonInput.value);
            if (Array.isArray(parsed)) loaded = parsed;
        } catch (e) {
            loaded = [];
        }
    }

    if (loaded.length === 0) {
        loaded = [
            {
                id: 1,
                image_url: '/assets/images/hero/featured-green-shell.jpg',
                alt_text: 'Bracelete Caminhos em Prata 925 com Concha Verde Esmeralda',
                link_url: '/loja',
                sort_order: 1,
                is_active: 1
            },
            {
                id: 2,
                image_url: '/assets/images/testimonials/lifestyle-wrist.jpeg',
                alt_text: 'Joia Autoral DIVINIUM no Pulso — Coleção Caminhos',
                link_url: '/loja',
                sort_order: 2,
                is_active: 1
            },
            {
                id: 3,
                image_url: '/assets/images/about/model-wrist-sunglasses.jpg',
                alt_text: 'Alta Joalheria Autoral Brasileira DIVINIUM',
                link_url: '/loja',
                sort_order: 3,
                is_active: 1
            }
        ];
    }

    currentCarouselSlides = loaded;
    renderCarouselSlides();
}

function syncCarouselSlidesToForm() {
    const jsonInput = document.getElementById('heroCarouselSlidesJson');
    if (jsonInput) {
        jsonInput.value = JSON.stringify(currentCarouselSlides);
    }
    const active = currentCarouselSlides.filter(s => s.is_active == 1);
    const mainSync = document.getElementById('hero_editorial_main_img_sync');
    const secSync = document.getElementById('hero_editorial_sec_img_sync');
    if (mainSync && active.length > 0) mainSync.value = active[0].image_url;
    if (secSync && active.length > 1) secSync.value = active[1].image_url;
}

function renderCarouselSlides() {
    const container = document.getElementById('carouselSlidesContainer');
    const counter = document.getElementById('carouselSlidesCount');
    if (!container) return;

    if (counter) counter.innerText = currentCarouselSlides.length;

    if (currentCarouselSlides.length === 0) {
        container.innerHTML = `
            <div style="text-align: center; padding: 28px; background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1); border-radius: 10px; color: #8e8880; font-size: 0.84rem;">
                Nenhuma imagem adicionada ao carrossel ainda. Clique em "<strong>+ Adicionar Nova Imagem</strong>" para compor a abertura da Home.
            </div>
        `;
        syncCarouselSlidesToForm();
        return;
    }

    let html = '';
    currentCarouselSlides.forEach((slide, idx) => {
        const img = slide.image_url || '/assets/images/hero/featured-green-shell.jpg';
        const alt = slide.alt_text || '';
        const link = slide.link_url || '';
        const order = slide.sort_order !== undefined ? slide.sort_order : (idx + 1);
        const isActive = slide.is_active != 0;

        html += `
            <div class="carousel-slide-item ${isActive ? '' : 'is-inactive'}" id="slideItem_${idx}">
                <div class="carousel-slide-thumb-wrap">
                    <img src="${escapeHtml(img)}" 
                         id="slideThumb_${idx}" 
                         class="carousel-slide-thumb" 
                         alt="Preview Slide ${idx + 1}"
                         onerror="this.onerror=null; this.src='/assets/images/hero/featured-green-shell.jpg';">
                </div>

                <div class="carousel-slide-fields">
                    <div class="carousel-slide-header">
                        <div class="carousel-slide-title">
                            <span>Imagem #${idx + 1}</span>
                            ${!isActive ? '<span style="font-size: 0.7rem; color: #f87171; background: rgba(248,113,113,0.1); padding: 1px 6px; border-radius: 4px;">Oculta</span>' : ''}
                        </div>
                        <div class="carousel-slide-actions">
                            <button type="button" class="btn-slide-order" onclick="moveCarouselSlide(${idx}, -1)" ${idx === 0 ? 'disabled' : ''} title="Mover para cima">▲</button>
                            <button type="button" class="btn-slide-order" onclick="moveCarouselSlide(${idx}, 1)" ${idx === currentCarouselSlides.length - 1 ? 'disabled' : ''} title="Mover para baixo">▼</button>
                            <button type="button" class="btn-slide-delete" onclick="removeCarouselSlide(${idx})" title="Excluir esta imagem">✕ Excluir</button>
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group" style="margin: 0;">
                            <label style="font-size: 0.76rem;">URL da Imagem *</label>
                            <div style="display: flex; gap: 8px;">
                                <input type="text" class="form-input" style="font-size: 0.8rem;" value="${escapeHtml(img)}" placeholder="/assets/images/hero/featured-green-shell.jpg" oninput="updateCarouselSlide(${idx}, 'image_url', this.value); updateSlideThumb(${idx}, this.value)">
                                <label class="btn-admin-gold" style="padding: 0 12px; cursor: pointer; display: flex; align-items: center; white-space: nowrap; font-size: 0.74rem;">
                                    <span>📁 Upload</span>
                                    <input type="file" accept="image/*" style="display: none;" onchange="uploadSlideImage(this, ${idx})">
                                </label>
                            </div>
                        </div>

                        <div class="form-group" style="margin: 0;">
                            <label style="font-size: 0.76rem;">Texto Alternativo (Alt / Acessibilidade / SEO)</label>
                            <input type="text" class="form-input" style="font-size: 0.8rem;" value="${escapeHtml(alt)}" placeholder="Ex: Bracelete Caminhos em Prata 925" oninput="updateCarouselSlide(${idx}, 'alt_text', this.value)">
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group" style="margin: 0;">
                            <label style="font-size: 0.76rem;">Link de Destino (Ao Clicar na Imagem - Opcional)</label>
                            <input type="text" class="form-input" style="font-size: 0.8rem;" value="${escapeHtml(link)}" placeholder="Ex: /loja ou https://wa.me/..." oninput="updateCarouselSlide(${idx}, 'link_url', this.value)">
                        </div>

                        <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 12px;">
                            <div class="form-group" style="margin: 0; width: 110px;">
                                <label style="font-size: 0.76rem;">Ordem</label>
                                <input type="number" class="form-input" style="font-size: 0.8rem;" value="${order}" oninput="updateCarouselSlide(${idx}, 'sort_order', parseInt(this.value, 10) || 0)">
                            </div>

                            <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.8rem; color: #ded7cf; cursor: pointer; margin-bottom: 8px; user-select: none;">
                                <input type="checkbox" ${isActive ? 'checked' : ''} onchange="updateCarouselSlide(${idx}, 'is_active', this.checked ? 1 : 0); renderCarouselSlides();" style="accent-color: #d4ba88; width: 16px; height: 16px;">
                                <span>Exibir imagem</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
    syncCarouselSlidesToForm();
}

function updateSlideThumb(idx, url) {
    const thumb = document.getElementById('slideThumb_' + idx);
    if (thumb && url) {
        thumb.src = url;
    }
}

function updateCarouselSlide(idx, key, val) {
    if (currentCarouselSlides[idx]) {
        currentCarouselSlides[idx][key] = val;
        syncCarouselSlidesToForm();
    }
}

function addNewCarouselSlide() {
    const newOrder = currentCarouselSlides.length + 1;
    currentCarouselSlides.push({
        id: Date.now(),
        image_url: '/assets/images/hero/featured-green-shell.jpg',
        alt_text: 'Nova Joia DIVINIUM',
        link_url: '/loja',
        sort_order: newOrder,
        is_active: 1
    });
    renderCarouselSlides();
}

function removeCarouselSlide(idx) {
    if (!confirm('Tem certeza que deseja remover esta imagem do carrossel?')) return;
    currentCarouselSlides.splice(idx, 1);
    currentCarouselSlides.forEach((s, i) => s.sort_order = i + 1);
    renderCarouselSlides();
}

function moveCarouselSlide(idx, direction) {
    const targetIdx = idx + direction;
    if (targetIdx < 0 || targetIdx >= currentCarouselSlides.length) return;

    const temp = currentCarouselSlides[idx];
    currentCarouselSlides[idx] = currentCarouselSlides[targetIdx];
    currentCarouselSlides[targetIdx] = temp;

    currentCarouselSlides.forEach((s, i) => s.sort_order = i + 1);
    renderCarouselSlides();
}

async function uploadSlideImage(input, idx) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    const formData = new FormData();
    formData.append('bg_file', file);
    formData.append('target_key', 'slide_' + idx);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
    if (csrfToken) formData.append('_csrf_token', csrfToken);

    try {
        const res = await fetch('/admin/temas/upload-bg', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const raw = await res.text();
        let data;
        try {
            data = JSON.parse(raw);
        } catch (jsonErr) {
            console.error('Resposta não-JSON do upload:', raw);
            alert('Não foi possível processar o upload do slide. Recarregue a página.');
            return;
        }

        if (data.auth_required) {
            alert('Sua sessão expirou. Por favor, faça login novamente para enviar imagens.');
            return;
        }

        if (data.success && data.url) {
            updateCarouselSlide(idx, 'image_url', data.url);
            renderCarouselSlides();
            alert('Imagem enviada com sucesso! Clique em Salvar Alterações para publicar.');
        } else {
            alert(data.error || 'Erro ao enviar imagem.');
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCarouselSlidesManager);
} else {
    initCarouselSlidesManager();
}

function updateMobileMenuLivePreview() {
    const getVal = (id, def) => document.getElementById(id)?.value || def;

    const bg = getVal('mobile_nav_bg_color', '#0d0c0b');
    const txt = getVal('mobile_nav_text_color', '#ded7cf');
    const accent = getVal('mobile_nav_accent_color', '#d4ba88');

    const drawer = document.getElementById('mobileMenuLivePreviewDrawer');
    if (drawer) {
        drawer.style.background = bg;
        drawer.style.color = txt;
    }

    const previewLogoText = document.getElementById('previewLogoText');
    if (previewLogoText) previewLogoText.textContent = getVal('mobile_nav_logo_text', 'DIVINIUM');

    const previewLogoSub = document.getElementById('previewLogoSub');
    if (previewLogoSub) previewLogoSub.textContent = getVal('mobile_nav_logo_sub', 'HAUTE JOAILLERIE');

    const previewHeaderBadge = document.getElementById('previewHeaderBadge');
    if (previewHeaderBadge) {
        previewHeaderBadge.textContent = getVal('mobile_nav_header_badge', 'JOALHERIA AUTORAL');
        previewHeaderBadge.style.color = accent;
        previewHeaderBadge.style.borderColor = accent;
    }

    const setPreview = (id, valId, def) => {
        const el = document.getElementById(id);
        if (el) el.textContent = getVal(valId, def);
    };

    setPreview('previewTextHome', 'mobile_nav_home_text', 'Início');
    setPreview('previewTextStore', 'mobile_nav_store_text', 'Boutique / Loja');
    setPreview('previewBadgeStore', 'mobile_nav_store_badge_text', 'Exclusivo');
    setPreview('previewTextCollections', 'mobile_nav_collections_text', 'Coleções');
    setPreview('previewTextSubAll', 'mobile_nav_sub_all_text', 'Ver Todas as Peças');
    setPreview('previewTextSubBracelets', 'mobile_nav_sub_bracelets_text', 'Braceletes Exclusivos');
    setPreview('previewTextAbout', 'mobile_nav_about_text', 'Sobre');
    setPreview('previewTextServices', 'mobile_nav_services_text', 'Garantia & Serviços');
    setPreview('previewTextWishlist', 'mobile_nav_wishlist_text', 'Lista de Desejos');
    setPreview('previewTextWhatsapp', 'mobile_nav_whatsapp_text', 'Concierge VIP via WhatsApp');
    setPreview('previewTextLogin', 'mobile_nav_login_text', 'Entrar no Painel Administrativo');
    setPreview('previewTextPerk', 'mobile_nav_perk_text', '✨ Joalheria Autoral Brasileira • Prata 925 & Gemas Raras');

    const previewLinkHome = document.getElementById('previewLinkHome');
    if (previewLinkHome) {
        previewLinkHome.style.borderLeftColor = accent;
    }
}

function updateAboutLivePreview() {
    const getVal = (id, def) => document.getElementById(id)?.value || def;

    const bg = getVal('about_bg_color', '#0d0c0b');
    const title = getVal('about_title_color', '#ffffff');
    const text = getVal('about_text_color', '#ded7cf');
    const accent = getVal('about_accent_color', '#d4ba88');
    const tag = getVal('about_tag_color', '#d4ba88');
    const cardBg = getVal('about_card_bg', '#161513');
    const cardBorder = getVal('about_card_border', 'rgba(212, 186, 136, 0.25)');
    const btn = getVal('about_btn_color', '#d4ba88');
    const captionBg = getVal('about_caption_bg', 'rgba(18, 17, 16, 0.85)');

    const previewContainer = document.getElementById('aboutLivePreviewContainer');
    if (previewContainer) {
        previewContainer.style.background = bg;
    }

    const previewTag = document.getElementById('aboutPreviewTag');
    if (previewTag) {
        previewTag.textContent = getVal('about_tag', 'MANIFESTO DIVINIUM');
        previewTag.style.color = tag;
    }

    const previewTitle = document.getElementById('aboutPreviewTitle');
    if (previewTitle) {
        previewTitle.textContent = getVal('about_title', 'A Beleza Não Precisa Gritar Para Ser Notada');
        previewTitle.style.color = title;
    }

    const previewP1 = document.getElementById('aboutPreviewP1');
    if (previewP1) {
        previewP1.textContent = getVal('about_p1', 'A DIVINIUM nasceu da convicção de que o verdadeiro luxo é autoral, sutil e profundamente conectado à sua identidade...');
        previewP1.style.color = text;
    }

    const previewQuote = document.getElementById('aboutPreviewQuote');
    if (previewQuote) {
        previewQuote.textContent = '"' + getVal('about_quote', 'Qual delas conversa com você hoje?') + '"';
        previewQuote.style.color = accent;
    }

    const previewBtn = document.getElementById('aboutPreviewBtn');
    if (previewBtn) {
        previewBtn.textContent = getVal('about_cta_text', 'Conheça Nossa História');
        previewBtn.style.color = btn;
        previewBtn.style.borderColor = btn;
    }

    const previewCard = document.getElementById('aboutPreviewCard');
    if (previewCard) {
        previewCard.style.backgroundColor = cardBg;
        previewCard.style.borderColor = cardBorder;
    }

    const previewCardTitle = document.getElementById('aboutPreviewCardTitle');
    if (previewCardTitle) {
        previewCardTitle.textContent = getVal('about_micro_title', 'Textura Mineral Autêntica');
        previewCardTitle.style.color = title;
    }

    const previewCardDesc = document.getElementById('aboutPreviewCardDesc');
    if (previewCardDesc) {
        previewCardDesc.textContent = getVal('about_micro_desc', 'Fragmentos minerais nobres preservados com resina.');
        previewCardDesc.style.color = text;
    }

    const previewCaption = document.getElementById('aboutPreviewCaption');
    if (previewCaption) {
        previewCaption.textContent = getVal('about_editorial_caption', 'COLEÇÃO CAMINHOS • UM MESMO DESIGN, DIFERENTES FORMAS DE SENTIR');
        previewCaption.style.backgroundColor = captionBg;
        previewCaption.style.color = accent;
    }
}

function applyAboutColorPreset(preset) {
    const presets = {
        'default': {
            about_bg_color: '#0d0c0b',
            about_title_color: '#ffffff',
            about_text_color: '#ded7cf',
            about_accent_color: '#d4ba88',
            about_tag_color: '#d4ba88',
            about_card_bg: '#161513',
            about_card_border: 'rgba(212, 186, 136, 0.25)',
            about_btn_color: '#d4ba88',
            about_caption_bg: 'rgba(18, 17, 16, 0.85)'
        },
        'light': {
            about_bg_color: '#fbf9f5',
            about_title_color: '#1a1918',
            about_text_color: '#55514c',
            about_accent_color: '#9c7a3c',
            about_tag_color: '#9c7a3c',
            about_card_bg: '#f0ede6',
            about_card_border: 'rgba(156, 122, 60, 0.3)',
            about_btn_color: '#1a1918',
            about_caption_bg: 'rgba(251, 249, 245, 0.9)'
        },
        'emerald': {
            about_bg_color: '#071510',
            about_title_color: '#ffffff',
            about_text_color: '#d2dfd8',
            about_accent_color: '#e5c98d',
            about_tag_color: '#4ade80',
            about_card_bg: '#0e221b',
            about_card_border: 'rgba(74, 222, 128, 0.25)',
            about_btn_color: '#e5c98d',
            about_caption_bg: 'rgba(7, 21, 16, 0.9)'
        },
        'sapphire': {
            about_bg_color: '#0a101d',
            about_title_color: '#ffffff',
            about_text_color: '#cbd5e1',
            about_accent_color: '#93c5fd',
            about_tag_color: '#60a5fa',
            about_card_bg: '#131d31',
            about_card_border: 'rgba(96, 165, 250, 0.3)',
            about_btn_color: '#93c5fd',
            about_caption_bg: 'rgba(10, 16, 29, 0.9)'
        }
    };

    const config = presets[preset] || presets['default'];
    for (const [key, val] of Object.entries(config)) {
        const textInput = document.getElementById(key);
        if (textInput) textInput.value = val;
        const pickerInput = document.getElementById(key + '_picker');
        if (pickerInput && val.startsWith('#') && val.length === 7) {
            pickerInput.value = val;
        }
    }
    updateAboutLivePreview();
}

// Suporte a abertura direta de abas por URL (ex: ?tab=sec-mobile-menu ou #sec-mobile-menu)
function checkInitialEditorTab() {
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab') || window.location.hash.replace('#', '');
    if (tabParam) {
        const btn = document.querySelector(`.editor-nav-tabs button[onclick*="'${tabParam}'"]`);
        if (btn) {
            switchSection(tabParam, btn);
        }
    }
    updateMobileMenuLivePreview();
    updateAboutLivePreview();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', checkInitialEditorTab);
} else {
    checkInitialEditorTab();
}
</script>
