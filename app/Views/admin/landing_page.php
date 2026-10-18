<?php
/**
 * VIEW: EDITOR DA LANDING PAGE (PAINEL ADMINISTRATIVO)
 * Permite editar qualquer elemento textual, títulos, preços e mídias da Home.
 */
use App\Models\Setting;
?>

<div class="landing-editor-container">

    <!-- BARRA SUPERIOR DE AÇÕES RÁPIDAS -->
    <div class="editor-header-bar">
        <div class="editor-header-info">
            <h2>Editor da Landing Page</h2>
            <p>Altere textos, chamadas, links, fotos e selos de qualquer seção da página inicial com efeito imediato.</p>
        </div>
        <div class="editor-header-actions">
            <a href="/" target="_blank" class="btn-admin-secondary">
                <span>↗ Ver Site Ao Vivo</span>
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
            <span>✦ Coleções na Home</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-services', this)">
            <span>🛡️ Serviços & Garantia</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchSection('sec-footer', this)">
            <span>📞 Contatos & Rodapé</span>
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
                    <p>A faixa de boas-vindas exibida no topo do site com benefícios e ofertas.</p>
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="top_banner_text">Texto do Anúncio Superior</label>
                    <input type="text" id="top_banner_text" name="top_banner_text" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('top_banner_text', '✨ Frete Cortesia com Seguro para todo o Brasil • 5% de Desconto no Pix • Até 10x sem juros')) ?>"
                           placeholder="✨ Frete Cortesia com Seguro para todo o Brasil • 5% de Desconto no Pix • Até 10x sem juros">
                    <span class="field-hint">Dica: Use emojis e símbolos como ✨ ou • para deixar a mensagem atraente.</span>
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
        <!-- SEÇÃO 2: HERO PRINCIPAL & HEADLINE -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-hero">
            <div class="section-card-header">
                <div class="section-icon">👑</div>
                <div>
                    <h3>Hero Principal (Abertura da Landing Page)</h3>
                    <p>Títulos editoriais, subtítulos, botões de ação e selos de autoridade.</p>
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
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 3: PALCO DA PEÇA EM DESTAQUE (HERO STAGE) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-stage">
            <div class="section-card-header">
                <div class="section-icon">💎</div>
                <div>
                    <h3>Palco da Peça em Destaque (Lado Direito do Hero)</h3>
                    <p>Controle as informações do card flutuante com a foto da joia.</p>
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
        <!-- SEÇÃO 4: MANIFESTO & SOBRE -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-about">
            <div class="section-card-header">
                <div class="section-icon">📜</div>
                <div>
                    <h3>Manifesto & Sobre a Marca</h3>
                    <p>A história editorial, pilares conceituais e essência do quiet luxury.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="about_tag">Tag da Seção</label>
                    <input type="text" id="about_tag" name="about_tag" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_tag', 'O PROPÓSITO DIVINIUM')) ?>"
                           placeholder="O PROPÓSITO DIVINIUM">
                </div>

                <div class="form-group">
                    <label for="about_title">Título Principal do Manifesto</label>
                    <input type="text" id="about_title" name="about_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('about_title', 'A Beleza Não Precisa Gritar Para Ser Notada')) ?>"
                           placeholder="A Beleza Não Precisa Gritar Para Ser Notada">
                </div>

                <div class="form-group full-width">
                    <label for="about_p1">Primeiro Parágrafo do Manifesto</label>
                    <textarea id="about_p1" name="about_p1" class="form-textarea" rows="3"><?= \App\Core\View::e(Setting::get('about_p1', 'Em um mundo dominado pela pressa e pelo excesso, a Divinium nasce como um convite à contemplação. Acreditamos no luxo que sussurra: aquele que se revela nos detalhes invisíveis, na precisão do acabamento manual e na harmonia pura das formas minerais.')) ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="about_p2">Segundo Parágrafo do Manifesto</label>
                    <textarea id="about_p2" name="about_p2" class="form-textarea" rows="3"><?= \App\Core\View::e(Setting::get('about_p2', 'Cada bracelete da Coleção Caminhos é esculpido artesanalmente para abraçar o pulso com conforto anatômico absoluto. Não são apenas acessórios — são amuletos de presença, elegância e conexão com a nobreza da terra.')) ?></textarea>
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
        <!-- SEÇÃO 5: COLEÇÕES NA HOME -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-collections">
            <div class="section-card-header">
                <div class="section-icon">✦</div>
                <div>
                    <h3>Seção Coleções & Vitrine na Home</h3>
                    <p>Textos da seção que apresenta o catálogo de braceletes na página principal.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="collections_tag">Tag da Seção</label>
                    <input type="text" id="collections_tag" name="collections_tag" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('collections_tag', 'COLEÇÃO CAMINHOS')) ?>"
                           placeholder="COLEÇÃO CAMINHOS">
                </div>

                <div class="form-group">
                    <label for="collections_title">Título da Vitrine</label>
                    <input type="text" id="collections_title" name="collections_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('collections_title', 'Seis Expressões de Uma Mesma Essência')) ?>"
                           placeholder="Seis Expressões de Uma Mesma Essência">
                </div>

                <div class="form-group full-width">
                    <label for="collections_subtitle">Subtítulo da Vitrine</label>
                    <textarea id="collections_subtitle" name="collections_subtitle" class="form-textarea" rows="2"><?= \App\Core\View::e(Setting::get('collections_subtitle', 'Cada bracelete é forjado com o mesmo aro nobre e recebe uma gema mineral com propriedades e energias singulares. Descubra qual mineral reverbera com a sua jornada.')) ?></textarea>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 6: SERVIÇOS & GARANTIA -->
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
                    <label for="services_title">Título da Seção de Serviços</label>
                    <input type="text" id="services_title" name="services_title" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('services_title', 'Experiência de Serviços Exclusivos')) ?>"
                           placeholder="Experiência de Serviços Exclusivos">
                </div>

                <div class="form-group">
                    <label for="services_subtitle">Subtítulo Explicativo</label>
                    <input type="text" id="services_subtitle" name="services_subtitle" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('services_subtitle', 'Mais do que uma joia — um vínculo de bem-estar e sintonia.')) ?>"
                           placeholder="Mais do que uma joia — um vínculo de bem-estar e sintonia.">
                </div>

                <div class="form-group full-width">
                    <label for="services_item1">Serviço 01: Sintonia Mineral</label>
                    <input type="text" id="services_item1" name="services_item1" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('services_item1', 'Consultoria personalizada para identificar o mineral alinhado ao seu momento de vida.')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="services_item2">Serviço 02: Ajuste e Ergonomia</label>
                    <input type="text" id="services_item2" name="services_item2" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('services_item2', 'Aro anatômico ajustável com orientação personalizada para o encaixe perfeito no pulso.')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="services_item3">Serviço 03: Certificação & Cuidados</label>
                    <input type="text" id="services_item3" name="services_item3" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('services_item3', 'Certificado vitalício de autenticidade da prata e guia exclusivo de conservação e limpeza.')) ?>">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 7: CONTATOS & RODAPÉ -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="sec-footer">
            <div class="section-card-header">
                <div class="section-icon">📞</div>
                <div>
                    <h3>Canais de Contato & Informações Legais</h3>
                    <p>Dados de atendimento, WhatsApp, e-mail e informações jurídicas do rodapé.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="footer_email">E-mail de Atendimento</label>
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

                <div class="form-group full-width">
                    <label for="footer_legal_info">Razão Social & Dados Jurídicos (CDC 7.962/2013)</label>
                    <input type="text" id="footer_legal_info" name="footer_legal_info" class="form-input"
                           value="<?= \App\Core\View::e(Setting::get('footer_legal_info', 'DIVINIUM JOIAS LTDA. • CNPJ: 00.000.000/0001-00 • São Paulo/SP - Brasil')) ?>">
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
            status.innerText = '✓ Sucesso! O conteúdo da Landing Page foi atualizado com sucesso.';
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
</script>
