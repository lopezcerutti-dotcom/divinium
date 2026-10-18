<?php
/**
 * VIEW: EDITOR DA LANDING PAGE E TEXTOS DO SITE (PAINEL ADMINISTRATIVO)
 * Permite editar qualquer elemento textual, títulos, preços e mídias da loja e da Home.
 */
use App\Models\Setting;
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
        <button type="button" class="tab-btn active" onclick="switchSection('sec-banner', this)">
            <span>📢 Top Banner</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-header-cart', this)">
            <span>🧭 Topo & Sacola</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-hero', this)">
            <span>👑 Hero & Títulos</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-stage', this)">
            <span>💎 Palco em Destaque</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-about', this)">
            <span>📜 Manifesto & Sobre</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-collections', this)">
            <span>✦ Vitrine & Filtros</span>
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
        <button type="button" class="tab-btn" onclick="switchSection('sec-store', this)">
            <span>🛍️ Textos da Loja</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-footer', this)">
            <span>📞 Rodapé & LGPD</span>
        </button>
    </div>

    <!-- FORMULÁRIO PRINCIPAL DO CMS -->
    <form id="landingPageForm" action="/admin/landing-page/salvar" method="POST" onsubmit="handleEditorSubmit(event)">
        <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">

        <!-- ============================================================== -->
        <!-- SEÇÃO 1: BARRA DE ANÚNCIOS SUPERIOR (TOP BANNER) -->
        <!-- ============================================================== -->
        <div class="editor-section-card active" id="sec-banner">
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

                <!-- Links da Navegação Principal -->
                <div class="form-group">
                    <label for="nav_link_home">Menu: Início</label>
                    <input type="text" id="nav_link_home" name="nav_link_home" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_home', 'Início')) ?>"
                           placeholder="Início">
                </div>

                <div class="form-group">
                    <label for="nav_link_store">Menu: Loja</label>
                    <input type="text" id="nav_link_store" name="nav_link_store" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_store', 'Loja')) ?>"
                           placeholder="Loja">
                </div>

                <div class="form-group">
                    <label for="nav_link_about">Menu: Sobre</label>
                    <input type="text" id="nav_link_about" name="nav_link_about" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_about', 'Sobre')) ?>"
                           placeholder="Sobre">
                </div>

                <div class="form-group">
                    <label for="nav_link_collections">Menu: Coleções (Dropdown)</label>
                    <input type="text" id="nav_link_collections" name="nav_link_collections" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_collections', 'Coleções')) ?>"
                           placeholder="Coleções">
                </div>

                <div class="form-group">
                    <label for="nav_link_services">Menu: Garantia / Serviços</label>
                    <input type="text" id="nav_link_services" name="nav_link_services" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_link_services', 'Garantia')) ?>"
                           placeholder="Garantia">
                </div>

                <!-- Sub-itens do Menu Coleções -->
                <div class="form-group">
                    <label for="nav_sub_all">Dropdown: Ver Todas as Peças</label>
                    <input type="text" id="nav_sub_all" name="nav_sub_all" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_all', 'Ver Todas as Peças')) ?>"
                           placeholder="Ver Todas as Peças">
                </div>

                <div class="form-group">
                    <label for="nav_sub_bracelets">Dropdown: Braceletes</label>
                    <input type="text" id="nav_sub_bracelets" name="nav_sub_bracelets" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_bracelets', 'Braceletes Exclusivos')) ?>"
                           placeholder="Braceletes Exclusivos">
                </div>

                <div class="form-group">
                    <label for="nav_sub_necklaces">Dropdown: Colares</label>
                    <input type="text" id="nav_sub_necklaces" name="nav_sub_necklaces" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_necklaces', 'Colares & Pingentes')) ?>"
                           placeholder="Colares & Pingentes">
                </div>

                <div class="form-group">
                    <label for="nav_sub_rings">Dropdown: Anéis</label>
                    <input type="text" id="nav_sub_rings" name="nav_sub_rings" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('nav_sub_rings', 'Anéis & Alianças')) ?>"
                           placeholder="Anéis & Alianças">
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

                <!-- Seletor Interativo de Gemas Minerais -->
                <div class="form-group full-width" style="margin-top: 15px; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 18px;">
                    <label for="hero_mineral_box_label" style="color: #caa15c; font-weight: 700;">Rótulo do Box Seletor de Minerais</label>
                    <input type="text" id="hero_mineral_box_label" name="hero_mineral_box_label" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('hero_mineral_box_label', 'Escolha sua sintonia mineral:')) ?>"
                           placeholder="Escolha sua sintonia mineral:">
                </div>

                <!-- Mineral 1: Quartzo Verde -->
                <div class="form-group">
                    <label for="stone_verde_name">Mineral 1 - Nome da Pílula</label>
                    <input type="text" id="stone_verde_name" name="stone_verde_name" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stone_verde_name', 'Quartzo Verde')) ?>">
                </div>
                <div class="form-group">
                    <label for="stone_verde_title">Mineral 1 - Título no Card</label>
                    <input type="text" id="stone_verde_title" name="stone_verde_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stone_verde_title', 'Bracelete Caminhos • Quartzo Verde')) ?>">
                </div>
                <div class="form-group full-width">
                    <label for="stone_verde_desc">Mineral 1 - Descrição Tátil</label>
                    <input type="text" id="stone_verde_desc" name="stone_verde_desc" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stone_verde_desc', 'Prata nobre e quartzo verde natural em concha mineral.')) ?>">
                </div>

                <!-- Mineral 2: Madrepérola -->
                <div class="form-group">
                    <label for="stone_madre_name">Mineral 2 - Nome da Pílula</label>
                    <input type="text" id="stone_madre_name" name="stone_madre_name" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stone_madre_name', 'Madrepérola')) ?>">
                </div>
                <div class="form-group">
                    <label for="stone_madre_title">Mineral 2 - Título no Card</label>
                    <input type="text" id="stone_madre_title" name="stone_madre_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stone_madre_title', 'Bracelete Caminhos • Madrepérola')) ?>">
                </div>
                <div class="form-group full-width">
                    <label for="stone_madre_desc">Mineral 2 - Descrição Tátil</label>
                    <input type="text" id="stone_madre_desc" name="stone_madre_desc" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stone_madre_desc', 'Prata nobre e nácar natural de madrepérola com brilho perolado.')) ?>">
                </div>

                <!-- Mineral 3: Jaspe Vermelho -->
                <div class="form-group">
                    <label for="stone_verm_name">Mineral 3 - Nome da Pílula</label>
                    <input type="text" id="stone_verm_name" name="stone_verm_name" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stone_verm_name', 'Jaspe Vermelho')) ?>">
                </div>
                <div class="form-group">
                    <label for="stone_verm_title">Mineral 3 - Título no Card</label>
                    <input type="text" id="stone_verm_title" name="stone_verm_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stone_verm_title', 'Bracelete Caminhos • Jaspe Vermelho')) ?>">
                </div>
                <div class="form-group full-width">
                    <label for="stone_verm_desc">Mineral 3 - Descrição Tátil</label>
                    <input type="text" id="stone_verm_desc" name="stone_verm_desc" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stone_verm_desc', 'Prata nobre e jaspe vermelho mineral de presença intensa e terrosa.')) ?>">
                </div>

                <!-- Mineral 4: Ônix Negro -->
                <div class="form-group">
                    <label for="stone_onix_name">Mineral 4 - Nome da Pílula</label>
                    <input type="text" id="stone_onix_name" name="stone_onix_name" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stone_onix_name', 'Ônix Negro')) ?>">
                </div>
                <div class="form-group">
                    <label for="stone_onix_title">Mineral 4 - Título no Card</label>
                    <input type="text" id="stone_onix_title" name="stone_onix_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stone_onix_title', 'Bracelete Caminhos • Ônix Negro')) ?>">
                </div>
                <div class="form-group full-width">
                    <label for="stone_onix_desc">Mineral 4 - Descrição Tátil</label>
                    <input type="text" id="stone_onix_desc" name="stone_onix_desc" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stone_onix_desc', 'Prata nobre e ônix negro de acabamento mineral espelhado.')) ?>">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 4: PALCO DA PEÇA EM DESTAQUE (HERO STAGE) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-stage">
            <div class="section-card-header">
                <div class="section-icon">💎</div>
                <div>
                    <h3>Palco da Peça em Destaque (Lado Direito do Hero)</h3>
                    <p>Controle as informações do card flutuante com a foto da joia na abertura do site.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="stage_top_tag">Etiqueta Superior Flutuante</label>
                    <input type="text" id="stage_top_tag" name="stage_top_tag" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stage_top_tag', 'PEÇA EM DESTAQUE')) ?>"
                           placeholder="PEÇA EM DESTAQUE">
                </div>

                <div class="form-group">
                    <label for="stage_card_eyebrow">Etiqueta do Card</label>
                    <input type="text" id="stage_card_eyebrow" name="stage_card_eyebrow" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stage_card_eyebrow', 'EDIÇÃO AUTORAL DIVINIUM')) ?>"
                           placeholder="EDIÇÃO AUTORAL DIVINIUM">
                </div>

                <div class="form-group">
                    <label for="stage_product_title">Título da Peça Padrão</label>
                    <input type="text" id="stage_product_title" name="stage_product_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stage_product_title', 'Bracelete Caminhos • Verde')) ?>"
                           placeholder="Bracelete Caminhos • Verde">
                </div>

                <div class="form-group">
                    <label for="stage_product_price">Preço Principal (R$)</label>
                    <input type="text" id="stage_product_price" name="stage_product_price" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stage_product_price', 'R$ 380,00')) ?>"
                           placeholder="R$ 380,00">
                </div>

                <div class="form-group">
                    <label for="stage_product_pix">Texto Condição Pix</label>
                    <input type="text" id="stage_product_pix" name="stage_product_pix" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stage_product_pix', 'ou R$ 361,00 no Pix (5% OFF)')) ?>"
                           placeholder="ou R$ 361,00 no Pix (5% OFF)">
                </div>

                <div class="form-group">
                    <label for="stage_product_image">Caminho da Imagem Padrão do Palco</label>
                    <input type="text" id="stage_product_image" name="stage_product_image" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stage_product_image', '/assets/images/hero/featured-green-shell.jpg')) ?>"
                           placeholder="/assets/images/hero/featured-green-shell.jpg">
                </div>

                <div class="form-group full-width">
                    <label for="stage_product_desc">Descrição Curta da Peça</label>
                    <input type="text" id="stage_product_desc" name="stage_product_desc" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('stage_product_desc', 'Prata nobre e quartzo verde natural em concha mineral.')) ?>"
                           placeholder="Prata nobre e quartzo verde natural em concha mineral.">
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
                <div class="form-group">
                    <label for="about_tag">Tag da Seção</label>
                    <input type="text" id="about_tag" name="about_tag" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_tag', 'MANIFESTO DIVINIUM')) ?>"
                           placeholder="MANIFESTO DIVINIUM">
                </div>

                <div class="form-group">
                    <label for="about_title">Título Principal do Manifesto</label>
                    <input type="text" id="about_title" name="about_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_title', 'A Beleza Não Precisa Gritar Para Ser Notada')) ?>"
                           placeholder="A Beleza Não Precisa Gritar Para Ser Notada">
                </div>

                <div class="form-group full-width">
                    <label for="about_p1">Primeiro Parágrafo do Manifesto</label>
                    <textarea id="about_p1" name="about_p1" class="form-textarea" rows="3"><?= \App\Core\View::e(Setting::get('about_p1', 'A DIVINIUM nasceu da convicção de que o verdadeiro luxo é autoral, sutil e profundamente conectado à sua identidade. Em um mercado de réplicas e produções em massa, escolhemos a rota da nobreza mineral.')) ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="about_p2">Segundo Parágrafo do Manifesto</label>
                    <textarea id="about_p2" name="about_p2" class="form-textarea" rows="3"><?= \App\Core\View::e(Setting::get('about_p2', 'Cada gema é única em suas veias, coloração e densidade. Encastoadas à mão sobre o aro duplo em prata, as pedras ganham vida e presença táctil sem jamais pesar no pulso.')) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="about_quote">Frase / Pergunta Poética de Destaque</label>
                    <input type="text" id="about_quote" name="about_quote" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_quote', 'Qual delas conversa com você hoje?')) ?>"
                           placeholder="Qual delas conversa com você hoje?">
                </div>

                <div class="form-group">
                    <label for="about_cta_text">Texto do Botão de Ação</label>
                    <input type="text" id="about_cta_text" name="about_cta_text" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_cta_text', 'Conheça Nossa História')) ?>"
                           placeholder="Conheça Nossa História">
                </div>

                <!-- Foto e Macro do Sobre -->
                <div class="form-group">
                    <label for="about_micro_title">Título da Miniatura Macro</label>
                    <input type="text" id="about_micro_title" name="about_micro_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_micro_title', 'Textura Mineral Autêntica')) ?>">
                </div>

                <div class="form-group">
                    <label for="about_micro_desc">Descrição da Miniatura Macro</label>
                    <input type="text" id="about_micro_desc" name="about_micro_desc" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_micro_desc', 'Fragmentos minerais nobres preservados com resina de alta transparência.')) ?>">
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
                           value="<?= \App\Core\View::e(Setting::get('about_editorial_caption', 'COLEÇÃO CAMINHOS • UM MESMO DESIGN, DIFERENTES FORMAS DE SENTIR')) ?>">
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
                    <p>Textos da seção que apresenta o catálogo de braceletes na página principal.</p>
                </div>
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

        const data = await response.json();

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
            alert(data.error || 'Erro ao salvar configurações.');
            btn.disabled = false;
            btn.innerHTML = '<span>Salvar Alterações da Landing Page</span>';
        }
    } catch (err) {
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
        const data = await res.json();
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
</script>
