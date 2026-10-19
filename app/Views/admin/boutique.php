<?php
/**
 * VIEW: PERSONALIZAR BOUTIQUE DIVINIUM (/loja)
 * QUIET LUXURY BOUTIQUE VISUAL CUSTOMIZER
 */
use App\Models\Setting;
?>

<div class="landing-editor-container">

    <!-- BARRA SUPERIOR DE AÇÕES -->
    <div class="editor-header-bar">
        <div class="editor-header-info">
            <h2>Personalizar Boutique & Vitrine Oficial</h2>
            <p>Edite 100% dos elementos visuais, chamadas, selos, filtros e textos da Boutique DIVINIUM (/loja).</p>
        </div>
        <div class="editor-header-actions">
            <a href="/loja" target="_blank" class="btn-admin-secondary">
                <span>🛍️ Ver Boutique Ao Vivo ↗</span>
            </a>
            <button type="button" class="btn-admin-gold" onclick="document.getElementById('boutiqueForm').requestSubmit()">
                <span>Salvar Todas as Alterações</span>
            </button>
        </div>
    </div>

    <!-- NAVEGAÇÃO ENTRE ABAS DA BOUTIQUE -->
    <div class="editor-nav-tabs">
        <button type="button" class="tab-btn active" onclick="switchBoutiqueSection('bsec-hero', this)">
            <span>👑 Hero & Títulos</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchBoutiqueSection('bsec-trust', this)">
            <span>🛡️ Selos de Garantia</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchBoutiqueSection('bsec-catalog', this)">
            <span>✦ Filtros & Cards</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchBoutiqueSection('bsec-reasons', this)">
            <span>💎 Seção Editorial</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchBoutiqueSection('bsec-whatsapp', this)">
            <span>💬 Atendimento VIP</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchBoutiqueSection('bsec-empty', this)">
            <span>🔍 Estado Vazio</span>
        </button>
    </div>

    <form id="boutiqueForm" action="/admin/boutique/salvar" method="POST" onsubmit="handleBoutiqueSubmit(event)">
        <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">

        <!-- ============================================================== -->
        <!-- SEÇÃO 1: HERO HEADER DA BOUTIQUE -->
        <!-- ============================================================== -->
        <div class="editor-section-card active" id="bsec-hero">
            <div class="section-card-header">
                <div class="section-icon">👑</div>
                <div>
                    <h3>Hero & Apresentação da Boutique</h3>
                    <p>O cabeçalho nobre de entrada da loja oficial com títulos e selo em degradê dourado.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-checkbox-row full-width">
                    <input type="checkbox" id="store_hero_visible" name="store_hero_visible" value="1" 
                           <?= Setting::get('store_hero_visible', '1') === '1' ? 'checked' : '' ?>
                           style="width: 18px; height: 18px; accent-color: #d4ba88;">
                    <label for="store_hero_visible" style="color: #ffffff; cursor: pointer; font-weight: 500;">Exibir o Hero Header no topo da Boutique</label>
                </div>

                <div class="form-group full-width">
                    <label for="store_hero_badge">Selo Superior da Boutique (Badge)</label>
                    <input type="text" id="store_hero_badge" name="store_hero_badge" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_hero_badge', 'VITRINE OFICIAL DIVINIUM • CANAIS HOMOLOGADOS')) ?>">
                    <span class="field-hint">Pequeno distintivo com brilho diamantino exibido acima do título principal.</span>
                </div>

                <div class="form-group">
                    <label for="store_hero_title">Título Principal (Primeira Parte)</label>
                    <input type="text" id="store_hero_title" name="store_hero_title" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_hero_title', 'Joias Autorais &')) ?>">
                </div>

                <div class="form-group">
                    <label for="store_hero_highlight">Destaque em Ouro (Segunda Parte)</label>
                    <input type="text" id="store_hero_highlight" name="store_hero_highlight" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_hero_highlight', 'Sintonia Mineral')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="store_hero_subtitle">Subtítulo / Descrição Conceitual</label>
                    <textarea id="store_hero_subtitle" name="store_hero_subtitle" class="form-textarea" rows="3"><?= \App\Core\View::e(Setting::get('store_hero_subtitle', 'Cada joia Divinium une a precisão da prata de lei 925 à energia de gemas minerais raras e conchas naturais. Escolha sua peça e finalize sua compra com toda a segurança, cupons e rastreamento nos nossos canais oficiais homologados.')) ?></textarea>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 2: SELOS DE CONFIANÇA & GARANTIA -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="bsec-trust">
            <div class="section-card-header">
                <div class="section-icon">🛡️</div>
                <div>
                    <h3>Selos de Confiança & Garantia</h3>
                    <p>Os 3 pilares de segurança e autenticidade exibidos abaixo da apresentação da Boutique.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-checkbox-row full-width">
                    <input type="checkbox" id="store_trust_visible" name="store_trust_visible" value="1" 
                           <?= Setting::get('store_trust_visible', '1') === '1' ? 'checked' : '' ?>
                           style="width: 18px; height: 18px; accent-color: #d4ba88;">
                    <label for="store_trust_visible" style="color: #ffffff; cursor: pointer; font-weight: 500;">Exibir faixa de selos de confiança</label>
                </div>

                <div class="form-group full-width">
                    <label for="store_badge_1">Selo 1: Proteção e Compra Garantida</label>
                    <input type="text" id="store_badge_1" name="store_badge_1" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_badge_1', 'Compra 100% Protegida • Garantia Oficial')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="store_badge_2">Selo 2: Autenticidade do Metal</label>
                    <input type="text" id="store_badge_2" name="store_badge_2" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_badge_2', 'Certificado de Prata de Lei 925')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="store_badge_3">Selo 3: Logística e Entrega</label>
                    <input type="text" id="store_badge_3" name="store_badge_3" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_badge_3', 'Envio Rápido e Seguro para todo o Brasil')) ?>">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 3: CATÁLOGO, FILTROS & LABELS DOS CARDS -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="bsec-catalog">
            <div class="section-card-header">
                <div class="section-icon">✦</div>
                <div>
                    <h3>Filtros do Catálogo & Textos dos Cards</h3>
                    <p>Controle os rótulos de navegação por coleções e as mensagens de preços nos cards das joias.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="store_filter_all">Rótulo da Pílula Principal (Todos)</label>
                    <input type="text" id="store_filter_all" name="store_filter_all" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_filter_all', 'Todas as Peças')) ?>">
                </div>

                <div class="form-group">
                    <label for="store_card_badge_metal">Texto do Selo de Metal no Card</label>
                    <input type="text" id="store_card_badge_metal" name="store_card_badge_metal" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_card_badge_metal', 'Prata 925')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="store_info_pill_text">Texto da Pílula Informativa (Ao lado dos filtros)</label>
                    <input type="text" id="store_info_pill_text" name="store_info_pill_text" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_info_pill_text', 'Escolha seu canal de compra favorito com segurança')) ?>">
                </div>

                <div class="form-checkbox-row full-width">
                    <input type="checkbox" id="store_info_pill_visible" name="store_info_pill_visible" value="1" 
                           <?= Setting::get('store_info_pill_visible', '1') === '1' ? 'checked' : '' ?>
                           style="width: 18px; height: 18px; accent-color: #d4ba88;">
                    <label for="store_info_pill_visible" style="color: #ded7cf; cursor: pointer;">Exibir pílula informativa na barra de filtros</label>
                </div>

                <div class="form-group">
                    <label for="store_pix_discount_label">Rótulo de Preço no Pix</label>
                    <input type="text" id="store_pix_discount_label" name="store_pix_discount_label" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_pix_discount_label', 'no Pix (-5%)')) ?>">
                </div>

                <div class="form-group">
                    <label for="store_multi_channel_label">Rótulo de Outros Canais de Venda</label>
                    <input type="text" id="store_multi_channel_label" name="store_multi_channel_label" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_multi_channel_label', 'Disponível também em:')) ?>">
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 4: SEÇÃO EDITORIAL (TRANSPARÊNCIA & VANTAGENS) -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="bsec-reasons">
            <div class="section-card-header">
                <div class="section-icon">💎</div>
                <div>
                    <h3>Seção Editorial: Transparência & Conveniência</h3>
                    <p>O bloco explicativo que detalha as vantagens dos canais oficiais (cupons, proteção, garantia).</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-checkbox-row full-width">
                    <input type="checkbox" id="store_reasons_visible" name="store_reasons_visible" value="1" 
                           <?= Setting::get('store_reasons_visible', '1') === '1' ? 'checked' : '' ?>
                           style="width: 18px; height: 18px; accent-color: #d4ba88;">
                    <label for="store_reasons_visible" style="color: #ffffff; cursor: pointer; font-weight: 500;">Exibir a Seção Editorial completa na Boutique</label>
                </div>

                <div class="form-group">
                    <label for="store_reasons_eyebrow">Sobretítulo (Eyebrow)</label>
                    <input type="text" id="store_reasons_eyebrow" name="store_reasons_eyebrow" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_reasons_eyebrow', 'TRANSPARÊNCIA & CONVENIÊNCIA')) ?>">
                </div>

                <div class="form-group">
                    <label for="store_reasons_title">Título da Seção</label>
                    <input type="text" id="store_reasons_title" name="store_reasons_title" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_reasons_title', 'Sua Experiência Segura em')) ?>">
                </div>

                <div class="form-group">
                    <label for="store_reasons_highlight">Destaque em Degradê Dourado</label>
                    <input type="text" id="store_reasons_highlight" name="store_reasons_highlight" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_reasons_highlight', 'Nossos Canais Oficiais')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="store_reasons_desc">Descrição Editorial</label>
                    <textarea id="store_reasons_desc" name="store_reasons_desc" class="form-textarea" rows="2"><?= \App\Core\View::e(Setting::get('store_reasons_desc', 'Para oferecer os melhores cupons de frete, proteção estendida ao consumidor e máxima velocidade de entrega, conectamos nossa boutique aos principais ecossistemas homologados.')) ?></textarea>
                </div>

                <!-- 4 Cards de Vantagens -->
                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 14px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                    <label>Card 1: Ícone & Título</label>
                    <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                        <input type="text" name="store_reason_1_icon" class="form-input" style="width: 60px;" value="<?= \App\Core\View::e(Setting::get('store_reason_1_icon', '🛡️')) ?>">
                        <input type="text" name="store_reason_1_title" class="form-input" value="<?= \App\Core\View::e(Setting::get('store_reason_1_title', 'Garantia de Entrega Estendida')) ?>">
                    </div>
                    <textarea name="store_reason_1_desc" class="form-textarea" rows="2"><?= \App\Core\View::e(Setting::get('store_reason_1_desc', 'Seu pagamento só é liberado à Divinium após você receber o pacote em casa e conferir a perfeição da sua joia.')) ?></textarea>
                </div>

                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 14px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                    <label>Card 2: Ícone & Título</label>
                    <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                        <input type="text" name="store_reason_2_icon" class="form-input" style="width: 60px;" value="<?= \App\Core\View::e(Setting::get('store_reason_2_icon', '🎫')) ?>">
                        <input type="text" name="store_reason_2_title" class="form-input" value="<?= \App\Core\View::e(Setting::get('store_reason_2_title', 'Cupons de Frete & Descontos')) ?>">
                    </div>
                    <textarea name="store_reason_2_desc" class="form-textarea" rows="2"><?= \App\Core\View::e(Setting::get('store_reason_2_desc', 'Aproveite cupons de frete grátis, cashback e vantagens exclusivas nos canais homologados.')) ?></textarea>
                </div>

                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 14px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                    <label>Card 3: Ícone & Título</label>
                    <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                        <input type="text" name="store_reason_3_icon" class="form-input" style="width: 60px;" value="<?= \App\Core\View::e(Setting::get('store_reason_3_icon', '💎')) ?>">
                        <input type="text" name="store_reason_3_title" class="form-input" value="<?= \App\Core\View::e(Setting::get('store_reason_3_title', 'Autenticidade & Prata 925')) ?>">
                    </div>
                    <textarea name="store_reason_3_desc" class="form-textarea" rows="2"><?= \App\Core\View::e(Setting::get('store_reason_3_desc', 'Todas as peças enviadas acompanham o certificado oficial de autenticidade da Divinium e estojo nobre.')) ?></textarea>
                </div>

                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 14px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                    <label>Card 4: Ícone & Título</label>
                    <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                        <input type="text" name="store_reason_4_icon" class="form-input" style="width: 60px;" value="<?= \App\Core\View::e(Setting::get('store_reason_4_icon', '💬')) ?>">
                        <input type="text" name="store_reason_4_title" class="form-input" value="<?= \App\Core\View::e(Setting::get('store_reason_4_title', 'Atendimento & Concierge VIP')) ?>">
                    </div>
                    <textarea name="store_reason_4_desc" class="form-textarea" rows="2"><?= \App\Core\View::e(Setting::get('store_reason_4_desc', 'Dúvidas sobre o modelo ou tamanho? Nosso atendimento via WhatsApp ou chat responde em minutos.')) ?></textarea>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 5: ATENDIMENTO VIP / WHATSAPP -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="bsec-whatsapp">
            <div class="section-card-header">
                <div class="section-icon">💬</div>
                <div>
                    <h3>Banner de Atendimento & Concierge VIP</h3>
                    <p>Chamada para falar com a consultora de gemas minerais antes da compra.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-checkbox-row full-width">
                    <input type="checkbox" id="store_whatsapp_visible" name="store_whatsapp_visible" value="1" 
                           <?= Setting::get('store_whatsapp_visible', '1') === '1' ? 'checked' : '' ?>
                           style="width: 18px; height: 18px; accent-color: #d4ba88;">
                    <label for="store_whatsapp_visible" style="color: #ffffff; cursor: pointer; font-weight: 500;">Exibir banner do Concierge WhatsApp na Boutique</label>
                </div>

                <div class="form-group">
                    <label for="store_whatsapp_title">Título da Chamada</label>
                    <input type="text" id="store_whatsapp_title" name="store_whatsapp_title" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_whatsapp_title', 'Precisa de ajuda para escolher sua joia?')) ?>">
                </div>

                <div class="form-group">
                    <label for="store_whatsapp_btn">Texto do Botão de Contato</label>
                    <input type="text" id="store_whatsapp_btn" name="store_whatsapp_btn" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_whatsapp_btn', 'Conversar no WhatsApp')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="store_whatsapp_desc">Descrição do Atendimento</label>
                    <input type="text" id="store_whatsapp_desc" name="store_whatsapp_desc" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_whatsapp_desc', 'Fale diretamente com nossa consultora de gemas minerais antes de concluir sua compra.')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="store_whatsapp_url">Link Completo ou Número do WhatsApp</label>
                    <input type="text" id="store_whatsapp_url" name="store_whatsapp_url" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_whatsapp_url', 'https://wa.me/5511999999999?text=Ol%C3%A1,%20estou%20na%20loja%20da%20Divinium%20e%20gostaria%20de%20tirar%20d%C3%BAvidas')) ?>">
                    <span class="field-hint">Pode ser URL completa com mensagem personalizada ou número internacional (ex: 5511999999999).</span>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SEÇÃO 6: ESTADO VAZIO -->
        <!-- ============================================================== -->
        <div class="editor-section-card" id="bsec-empty">
            <div class="section-card-header">
                <div class="section-icon">🔍</div>
                <div>
                    <h3>Mensagem de Estado Vazio</h3>
                    <p>Exibida quando um filtro não retorna peças ou o catálogo está sendo atualizado.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="store_empty_title">Título da Mensagem Vazia</label>
                    <input type="text" id="store_empty_title" name="store_empty_title" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_empty_title', 'Nenhuma Joia Disponível nesta Seleção')) ?>">
                </div>

                <div class="form-group full-width">
                    <label for="store_empty_desc">Texto Explicativo</label>
                    <input type="text" id="store_empty_desc" name="store_empty_desc" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_empty_desc', 'Nossas coleções estão sendo atualizadas nos bastidores. Experimente outro filtro ou volte em instantes.')) ?>">
                </div>

                <div class="form-group">
                    <label for="store_empty_btn">Texto do Botão de Retorno</label>
                    <input type="text" id="store_empty_btn" name="store_empty_btn" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_empty_btn', 'Voltar à Página Inicial')) ?>">
                </div>

                <div class="form-group">
                    <label for="store_empty_url">Destino do Botão</label>
                    <input type="text" id="store_empty_url" name="store_empty_url" class="form-input" 
                           value="<?= \App\Core\View::e(Setting::get('store_empty_url', '/')) ?>">
                </div>
            </div>
        </div>

        <!-- BARRA FLUTUANTE DE SALVAMENTO -->
        <div class="editor-save-dock">
            <div class="save-dock-inner">
                <div class="save-dock-status">
                    <span>💡 Todas as alterações salvas são refletidas instantaneamente na Boutique.</span>
                </div>
                <button type="submit" class="btn-admin-gold" id="saveBoutiqueBtn">
                    <span>Salvar Alterações da Boutique</span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function switchBoutiqueSection(sectionId, btn) {
    document.querySelectorAll('#boutiqueForm .editor-section-card').forEach(card => card.classList.remove('active'));
    document.querySelectorAll('.editor-nav-tabs .tab-btn').forEach(b => b.classList.remove('active'));

    const target = document.getElementById(sectionId);
    if (target) {
        target.classList.add('active');
    }
    if (btn) {
        btn.classList.add('active');
    }
}

async function handleBoutiqueSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('saveBoutiqueBtn');
    btn.disabled = true;
    btn.innerText = 'Salvando Elementos...';

    const form = document.getElementById('boutiqueForm');
    const formData = new FormData(form);

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
    if (csrfToken && !formData.has('_csrf_token')) {
        formData.append('_csrf_token', csrfToken);
    }

    try {
        const response = await fetch('/admin/boutique/salvar', {
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
            alert(data.message || 'Boutique atualizada com sucesso!');
            window.location.reload();
        } else {
            alert(data.error || 'Erro ao salvar elementos da Boutique.');
            btn.disabled = false;
            btn.innerText = 'Salvar Alterações da Boutique';
        }
    } catch (err) {
        alert('Erro ao conectar ao servidor: ' + err.message);
        btn.disabled = false;
        btn.innerText = 'Salvar Alterações da Boutique';
    }
}
</script>
