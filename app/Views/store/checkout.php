<?php
/**
 * VIEW: CHECKOUT NOBRE DIVINIUM (/checkout)
 * Quiet luxury checkout experience com integração Pix, Asaas e Concierge VIP
 * Totalmente integrado aos controles centralizados do Painel Administrativo.
 */
use App\Models\Setting;
use App\Core\View;

// Configurações de Pagamento & Pix
$pixKey = Setting::get('payment_pix_key', 'contato@divinium.com.br');
$pixBeneficiary = Setting::get('payment_pix_name', 'DIVINIUM JOIAS AUTORAIS');
$pixDiscountPercent = (float)Setting::get('payment_pix_discount_percent', '5');
$pixDiscountVisible = Setting::get('payment_pix_discount_visible', '1') === '1';

// Contatos & Suporte
$waNumber = Setting::get('hero_whatsapp_number', '5511999999999');

// Selo de Segurança & Cabeçalho
$sslBadgeVisible = Setting::get('checkout_ssl_badge_visible', '1') === '1';
$sslBadgeText = Setting::get('checkout_ssl_badge_text', 'Criptografia SSL de 256 bits • Ambiente Protegido');

// Linha de Frete & Rótulo Padrão
$shippingRowVisible = Setting::get('checkout_shipping_row_visible', '1') === '1';
$shippingDefaultLabel = Setting::get('checkout_shipping_default_label', 'A calcular ou Cortesia');

// Perks / Pilares do Checkout
$perk1Visible = Setting::get('checkout_perk1_visible', '1') === '1';
$perk1Text = Setting::get('checkout_perk1_text', 'Banho Nobre de Prata com Certificado e Garantia');
$perk2Visible = Setting::get('checkout_perk2_visible', '1') === '1';
$perk2Text = Setting::get('checkout_perk2_text', 'Direito de Arrependimento de 7 Dias (CDC)');
$perk3Visible = Setting::get('checkout_perk3_visible', '1') === '1';
$perk3Text = Setting::get('checkout_perk3_text', 'Embalagem Especial para Presente em Veludo');

// Cabeçalho e Títulos do Checkout
$brandName = Setting::get('checkout_brand_name', 'DIVINIUM');
$brandBadgeVisible = Setting::get('checkout_brand_badge_visible', '1') === '1';
$brandBadgeText = Setting::get('checkout_brand_badge_text', 'CHECKOUT SEGURO');

// Passos & Seções
$step1Title = Setting::get('checkout_step1_title', 'Identificação & Entrega');
$step2Title = Setting::get('checkout_step2_title', 'Forma de Pagamento Preferencial');
$shippingTitle = Setting::get('checkout_shipping_title', 'Opções de Frete & Entrega');
$summaryTitle = Setting::get('checkout_summary_title', 'Resumo da Sua Sacola');

// Rótulos de Formulário
$lblClientName = Setting::get('checkout_lbl_name', 'Nome Completo *');
$lblClientEmail = Setting::get('checkout_lbl_email', 'E-mail para Confirmação *');
$lblClientPhone = Setting::get('checkout_lbl_phone', 'WhatsApp / Telefone *');
$lblClientCep = Setting::get('checkout_lbl_cep', 'CEP de Entrega *');
$lblClientAddress = Setting::get('checkout_lbl_address', 'Endereço Completo & Bairro');
$btnSearchCep = Setting::get('checkout_btn_search_cep', 'Buscar');

// Opções de Pagamento (Ativação, Nomes, Tags & Descrições)
$optPixEnabled = Setting::get('checkout_opt_pix_enabled', '1') === '1';
$optPixTitle = Setting::get('checkout_opt_pix_title', '⚡ Pix Instantâneo');
$optPixBadge = Setting::get('checkout_opt_pix_badge', 'DE DESCONTO');
$optPixDesc = Setting::get('checkout_opt_pix_desc', 'Aprovação imediata. QR Code gerado instantaneamente na tela.');
$btnCopyPix = Setting::get('checkout_btn_copy_pix', '📋 Copiar Chave');

$optWaEnabled = Setting::get('checkout_opt_wa_enabled', '1') === '1';
$optWaTitle = Setting::get('checkout_opt_wa_title', '💬 Concierge VIP (WhatsApp)');
$optWaBadge = Setting::get('checkout_opt_wa_badge', 'ATENDIMENTO HUMANO');
$optWaDesc = Setting::get('checkout_opt_wa_desc', 'Conclua o pedido diretamente com um especialista e tire todas as dúvidas.');

$optCardEnabled = Setting::get('checkout_opt_card_enabled', '1') === '1';
$optCardTitle = Setting::get('checkout_opt_card_title', '💳 Cartão de Crédito');
$optCardBadge = Setting::get('checkout_opt_card_badge', 'ATÉ 10X SEM JUROS');
$optCardDesc = Setting::get('checkout_opt_card_desc', 'Pagamento processado em ambiente 100% criptografado homologado ASAAS.');

// Métodos Habilitados & Método Padrão Inicial
$enabledPaymentMethods = [];
if ($optPixEnabled) $enabledPaymentMethods[] = 'pix';
if ($optWaEnabled) $enabledPaymentMethods[] = 'whatsapp';
if ($optCardEnabled) $enabledPaymentMethods[] = 'card';
$defaultPaymentMethod = !empty($enabledPaymentMethods) ? $enabledPaymentMethods[0] : '';

// Botão de Envio
$btnSubmitText = Setting::get('checkout_btn_submit_text', 'Confirmar Pedido & Continuar');

// Rótulos do Resumo
$lblSubtotal = Setting::get('checkout_lbl_subtotal', 'Subtotal:');
$lblPixDiscount = Setting::get('checkout_lbl_pix_discount', 'Desconto Pix');
$lblShipping = Setting::get('checkout_lbl_shipping', 'Frete:');
$lblTotal = Setting::get('checkout_lbl_total', 'Total Estimado:');
$returnStoreVisible = Setting::get('checkout_return_store_visible', '1') === '1';
$returnStoreText = Setting::get('checkout_return_store_text', '← Escolher mais joias na boutique');

// Textos e Instruções Dinâmicas dos Meios de Pagamento
$pixTitle = Setting::get('checkout_pix_instructions_title', 'Pagamento Imediato via Pix com ' . $pixDiscountPercent . '% OFF');
$pixDesc = Setting::get('checkout_pix_instructions_text', 'Após clicar em "Confirmar Pedido & Gerar Pix", você receberá a chave e o QR Code oficial para pagamento instantâneo com envio prioritário.');

$waTitle = Setting::get('checkout_wa_instructions_title', 'Finalização com Concierge Exclusivo');
$waDesc = Setting::get('checkout_wa_instructions_text', 'Ao avançar, nossa equipe já receberá a lista exata das joias selecionadas e o endereço para gerar seu atendimento personalizado.');

$cardTitle = Setting::get('checkout_card_instructions_title', 'Cartão de Crédito com Parcelamento Sem Juros');
$cardDesc = Setting::get('checkout_card_instructions_text', 'Você será direcionado para o gateway seguro homologado da Divinium para inserção protegida dos dados do cartão em até 10x.');

// Controles de Textos e Visibilidade dos Botões do Checkout (Padrão 22a9aa6)
$btnCepVisible = Setting::get('checkout_btn_cep_visible', '1') === '1';
$btnCepText = Setting::get('checkout_btn_cep_text', Setting::get('checkout_btn_search_cep', 'Buscar'));

$btnPixVisible = Setting::get('checkout_btn_pix_visible', '1') === '1';
$btnPixText = Setting::get('checkout_btn_pix_text', Setting::get('checkout_btn_copy_pix', 'Copiar Chave'));

$btnSubmitVisible = Setting::get('checkout_btn_submit_visible', '1') === '1';
$btnSubmitText = Setting::get('checkout_btn_submit_text', 'Confirmar Pedido & Continuar');

$returnStoreVisible = Setting::get('checkout_return_store_visible', '1') === '1';
$returnStoreText = Setting::get('checkout_return_store_text', '← Escolher mais joias na boutique');
$returnStoreUrl = Setting::get('checkout_return_store_url', '/loja');
?>

<div class="checkout-page-container">
    <div class="container checkout-inner">
        
        <!-- HEADER DE SEGURANÇA DO CHECKOUT -->
        <div class="checkout-header">
            <div class="checkout-brand">
                <span class="sparkle">✦</span>
                <span class="brand-name"><?= View::e($brandName) ?></span>
                <?php if ($brandBadgeVisible && !empty($brandBadgeText)): ?>
                <span class="checkout-badge"><?= View::e($brandBadgeText) ?></span>
                <?php endif; ?>
            </div>
            <?php if ($sslBadgeVisible): ?>
            <div class="checkout-security-status">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#737B85" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                <span><?= View::e($sslBadgeText) ?></span>
            </div>
            <?php endif; ?>
        </div>

        <div class="checkout-grid">
            <!-- COLUNA ESQUERDA: DADOS DO CLIENTE & FORMA DE PAGAMENTO -->
            <div class="checkout-col-main">
                <div class="checkout-card">
                    <h2 class="card-step-title">
                        <span class="step-num">1</span>
                        <span><?= View::e($step1Title) ?></span>
                    </h2>

                    <form id="checkoutForm" onsubmit="handleCheckoutSubmit(event)">
                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="clientName"><?= View::e($lblClientName) ?></label>
                                <input type="text" id="clientName" name="name" class="form-input" placeholder="Ex: Maria Clara Silveira" required>
                            </div>
                            <div class="form-group">
                                <label for="clientEmail"><?= View::e($lblClientEmail) ?></label>
                                <input type="email" id="clientEmail" name="email" class="form-input" placeholder="seu@email.com" required>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="clientPhone"><?= View::e($lblClientPhone) ?></label>
                                <input type="tel" id="clientPhone" name="phone" class="form-input" placeholder="(11) 99999-9999" required>
                            </div>
                            <div class="form-group">
                                <label for="clientCep"><?= View::e($lblClientCep) ?></label>
                                <div style="display: flex; gap: 8px;">
                                    <input type="text" id="clientCep" name="cep" class="form-input" placeholder="00000-000" maxlength="9" required>
                                    <?php if ($btnCepVisible): ?>
                                    <button type="button" class="btn-calc-cep" id="btnConsultarCep" onclick="consultarCep()">
                                        <span id="btnConsultarCepText"><?= View::e($btnCepText) ?></span>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="clientAddress"><?= View::e($lblClientAddress) ?></label>
                            <input type="text" id="clientAddress" name="address" class="form-input" placeholder="Rua, Número, Complemento, Bairro">
                        </div>

                        <!-- CONTAINER DE CÁLCULO E ESCOLHA DE FRETE REAL -->
                        <div id="shippingCalcContainer" class="shipping-calc-container" style="display: none;">
                            <div class="shipping-calc-header">
                                <span class="shipping-icon">🚚</span>
                                <h4 class="shipping-title"><?= View::e($shippingTitle) ?></h4>
                            </div>
                            <div id="shippingCalcStatus" class="shipping-calc-status"></div>
                            <div id="shippingOptionsList" class="shipping-options-list"></div>
                        </div>

                        <!-- FORMAS DE PAGAMENTO NOBRES -->
                        <h2 class="card-step-title" style="margin-top: 36px;">
                            <span class="step-num">2</span>
                            <span><?= View::e($step2Title) ?></span>
                        </h2>

                        <div class="payment-methods-grid">
                            <?php if ($optPixEnabled): ?>
                            <!-- Opção 1: Pix -->
                            <label class="payment-option <?= $defaultPaymentMethod === 'pix' ? 'active' : '' ?>" id="optPix">
                                <input type="radio" name="payment_method" value="pix" <?= $defaultPaymentMethod === 'pix' ? 'checked' : '' ?> onchange="switchPaymentMethod('pix')">
                                <div class="payment-option-inner">
                                    <div class="payment-opt-top">
                                        <span class="opt-name"><?= View::e($optPixTitle) ?></span>
                                        <?php if ($pixDiscountVisible && $pixDiscountPercent > 0): ?>
                                        <span class="opt-discount-tag"><?= $pixDiscountPercent ?>% <?= View::e($optPixBadge) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="opt-desc"><?= View::e($optPixDesc) ?></p>
                                </div>
                            </label>
                            <?php endif; ?>

                            <?php if ($optWaEnabled): ?>
                            <!-- Opção 2: Concierge VIP WhatsApp -->
                            <label class="payment-option <?= $defaultPaymentMethod === 'whatsapp' ? 'active' : '' ?>" id="optWa">
                                <input type="radio" name="payment_method" value="whatsapp" <?= $defaultPaymentMethod === 'whatsapp' ? 'checked' : '' ?> onchange="switchPaymentMethod('whatsapp')">
                                <div class="payment-option-inner">
                                    <div class="payment-opt-top">
                                        <span class="opt-name"><?= View::e($optWaTitle) ?></span>
                                        <span class="opt-tag-nob"><?= View::e($optWaBadge) ?></span>
                                    </div>
                                    <p class="opt-desc"><?= View::e($optWaDesc) ?></p>
                                </div>
                            </label>
                            <?php endif; ?>

                            <?php if ($optCardEnabled): ?>
                            <!-- Opção 3: Cartão de Crédito em até 10x -->
                            <label class="payment-option <?= $defaultPaymentMethod === 'card' ? 'active' : '' ?>" id="optCard">
                                <input type="radio" name="payment_method" value="card" <?= $defaultPaymentMethod === 'card' ? 'checked' : '' ?> onchange="switchPaymentMethod('card')">
                                <div class="payment-option-inner">
                                    <div class="payment-opt-top">
                                        <?php if (trim((string) $optCardTitle) !== ''): ?>
                                            <span class="opt-name"><?= View::e($optCardTitle) ?></span>
                                        <?php endif; ?>
                                        <?php if (trim((string) $optCardBadge) !== ''): ?>
                                            <span class="opt-tag-sub"><?= View::e($optCardBadge) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (trim((string) $optCardDesc) !== ''): ?>
                                        <p class="opt-desc"><?= View::e($optCardDesc) ?></p>
                                    <?php endif; ?>
                                </div>
                            </label>
                            <?php endif; ?>

                            <?php if (empty($enabledPaymentMethods)): ?>
                            <div style="grid-column: 1 / -1; background: rgba(0,0,0,0.03); padding: 18px 20px; border-radius: 8px; text-align: center; border: 1px dashed rgba(0,0,0,0.15);">
                                <span style="font-size: 1.2rem; display: block; margin-bottom: 6px;">🏛️</span>
                                <strong style="color: var(--text-primary); font-size: 0.95rem; display: block; margin-bottom: 4px;">Nenhuma forma de pagamento online disponível no momento.</strong>
                                <span style="font-size: 0.82rem; color: var(--text-secondary);">Por favor, entre em contato diretamente com o nosso Concierge VIP para concluir seu pedido.</span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Painel Dinâmico de Instruções de Pagamento -->
                        <div id="paymentInstructionsBox" class="payment-instructions-card" style="<?= empty($enabledPaymentMethods) ? 'display: none;' : '' ?>">
                            <?php if ($optPixEnabled): ?>
                            <div id="pixInstructions" style="<?= $defaultPaymentMethod === 'pix' ? 'display: block;' : 'display: none;' ?>">
                                <h4 style="color: var(--text-primary); margin: 0 0 8px; font-size: 1rem;"><?= View::e($pixTitle) ?></h4>
                                <p style="font-size: 0.85rem; color: var(--text-secondary); margin: 0 0 14px;">
                                    <?= View::e($pixDesc) ?>
                                </p>
                                <div class="pix-key-preview">
                                    <div>
                                        <span style="font-size: 0.76rem; color: var(--text-secondary); display: block;">Chave Oficial Divinium:</span>
                                        <code id="pixKeyValueDisplay"><?= View::e($pixKey) ?></code>
                                        <small style="display: block; color: var(--text-secondary); margin-top: 2px;">Titular: <?= View::e($pixBeneficiary) ?></small>
                                    </div>
                                    <?php if ($btnPixVisible): ?>
                                    <button type="button" class="btn-copy-pix" onclick="copyPixKey('<?= View::e($pixKey) ?>')">
                                        <span id="copyPixBtnText"><?= View::e($btnPixText) ?></span>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($optWaEnabled): ?>
                            <div id="waInstructions" style="<?= $defaultPaymentMethod === 'whatsapp' ? 'display: block;' : 'display: none;' ?>">
                                <h4 style="color: var(--text-primary, #202020); margin: 0 0 8px; font-size: 1rem;"><?= View::e($waTitle) ?></h4>
                                <p style="font-size: 0.85rem; color: var(--text-secondary); margin: 0;">
                                    <?= View::e($waDesc) ?>
                                </p>
                            </div>
                            <?php endif; ?>

                            <?php if ($optCardEnabled): ?>
                            <div id="cardInstructions" style="<?= $defaultPaymentMethod === 'card' ? 'display: block;' : 'display: none;' ?>">
                                <h4 style="color: var(--text-primary); margin: 0 0 8px; font-size: 1rem;"><?= View::e($cardTitle) ?></h4>
                                <p style="font-size: 0.85rem; color: var(--text-secondary); margin: 0;">
                                    <?= View::e($cardDesc) ?>
                                </p>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($btnSubmitVisible): ?>
                        <button type="submit" class="btn-checkout-submit" id="btnSubmitCheckout">
                            <span id="btnSubmitCheckoutText"><?= View::e($btnSubmitText) ?></span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- COLUNA DIREITA: RESUMO DO PEDIDO & SACOLA -->
            <div class="checkout-col-summary">
                <div class="checkout-card summary-card">
                    <h3 class="summary-title"><?= View::e($summaryTitle) ?></h3>
                    
                    <div id="checkoutItemsList" class="checkout-items-list">
                        <!-- Carregado via JS -->
                        <div class="loading-items">Carregando itens da sacola...</div>
                    </div>

                    <div class="summary-totals-block">
                        <div class="summary-row">
                            <span><?= View::e($lblSubtotal) ?></span>
                            <span id="summarySubtotal">R$ 0,00</span>
                        </div>
                        <?php if ($pixDiscountVisible && $pixDiscountPercent > 0): ?>
                        <div class="summary-row" id="summaryPixRow">
                            <span style="color: var(--gold-accent, #737B85);"><?= View::e($lblPixDiscount) ?> (<?= $pixDiscountPercent ?>%):</span>
                            <span id="summaryPixDiscount" style="color: var(--gold-accent, #737B85);">- R$ 0,00</span>
                        </div>
                        <?php endif; ?>
                        <?php if ($shippingRowVisible): ?>
                        <div class="summary-row" id="summaryShippingRow">
                            <span><?= View::e($lblShipping) ?></span>
                            <span id="summaryShippingVal" style="color: var(--text-primary); font-weight: 600;"><?= View::e($shippingDefaultLabel) ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="summary-row total-row">
                            <span><?= View::e($lblTotal) ?></span>
                            <span id="summaryGrandTotal" class="total-val">R$ 0,00</span>
                        </div>
                    </div>

                    <div class="checkout-perks-list">
                        <?php if ($perk1Visible && !empty($perk1Text)): ?>
                        <div class="perk-item">
                            <span class="perk-dot">✦</span>
                            <span><?= View::e($perk1Text) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($perk2Visible && !empty($perk2Text)): ?>
                        <div class="perk-item">
                            <span class="perk-dot">✦</span>
                            <span><?= View::e($perk2Text) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($perk3Visible && !empty($perk3Text)): ?>
                        <div class="perk-item">
                            <span class="perk-dot">✦</span>
                            <span><?= View::e($perk3Text) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($returnStoreVisible): ?>
                    <a href="<?= View::e($returnStoreUrl) ?>" class="btn-return-store"><?= View::e($returnStoreText) ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.checkout-page-container {
    background-color: var(--checkout-bg, var(--bg-light, #F7F7F5));
    min-height: 85vh;
    padding: 40px 0 80px;
    color: var(--text-primary, #25272A);
}
.checkout-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--border-light, rgba(0, 0, 0, 0.08));
    padding-bottom: 20px;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}
.checkout-brand {
    display: flex;
    align-items: center;
    gap: 10px;
}
.checkout-brand .sparkle {
    color: var(--gold-accent, #737B85);
    font-size: 1.2rem;
}
.checkout-brand .brand-name {
    font-family: var(--font-serif, 'Cormorant Garamond', Georgia, serif);
    font-size: 1.5rem;
    color: var(--text-primary, #25272A);
    letter-spacing: 2px;
}
.checkout-badge {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 1px;
    background: rgba(167, 173, 180, 0.18);
    color: var(--text-primary, #25272A);
    padding: 3px 8px;
    border-radius: 4px;
    border: 1px solid rgba(167, 173, 180, 0.35);
}
.checkout-security-status {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.78rem;
    color: var(--text-secondary, #737B85);
}
.checkout-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 30px;
    align-items: start;
}
.checkout-card {
    background-color: var(--checkout-card-bg, var(--surface-dark, #FFFFFF));
    border: 1px solid var(--border-light, rgba(0, 0, 0, 0.08));
    border-radius: 14px;
    padding: 32px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
}
.card-step-title {
    font-family: var(--font-serif, 'Cormorant Garamond', Georgia, serif);
    font-size: 1.35rem;
    color: var(--text-primary, #25272A);
    margin: 0 0 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.step-num {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: var(--gold-accent, #A7ADB4);
    color: #25272A;
    font-size: 0.85rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.form-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.form-group {
    margin-bottom: 18px;
}
.form-group label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-secondary, #737B85);
    margin-bottom: 6px;
}
.form-input {
    width: 100%;
    padding: 12px 14px;
    background: var(--bg-white, #FFFFFF);
    border: 1px solid var(--border-light, rgba(0, 0, 0, 0.14));
    border-radius: 8px;
    color: var(--text-primary, #25272A);
    font-size: 0.88rem;
    transition: border-color 0.2s;
    box-sizing: border-box;
}
.form-input:focus {
    outline: none;
    border-color: var(--gold-accent, #737B85);
    background: #FFFFFF;
    box-shadow: 0 0 0 3px rgba(167, 173, 180, 0.2);
}
.btn-calc-cep {
    background: rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-light, rgba(0, 0, 0, 0.15));
    color: var(--text-primary, #25272A);
    border-radius: 8px;
    padding: 0 16px;
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s;
}
.btn-calc-cep:hover {
    background: var(--gold-accent, #A7ADB4);
    color: #25272A;
}

/* Frete em tempo real */
.shipping-calc-container {
    background: rgba(167, 173, 180, 0.08);
    border: 1px solid rgba(167, 173, 180, 0.25);
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 22px;
}
.shipping-calc-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
}
.shipping-icon {
    font-size: 1.1rem;
}
.shipping-title {
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--text-primary, #25272A);
    margin: 0;
}
.shipping-calc-status {
    font-size: 0.8rem;
    color: var(--text-secondary, #737B85);
    margin-bottom: 8px;
}
.shipping-options-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.shipping-option-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #FFFFFF;
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 10px 14px;
    cursor: pointer;
    transition: all 0.2s;
}
.shipping-option-item:hover, .shipping-option-item.selected {
    border-color: var(--gold-accent, #737B85);
    background: rgba(167, 173, 180, 0.06);
}
.shipping-opt-left {
    display: flex;
    align-items: center;
    gap: 10px;
}
.shipping-opt-name {
    font-size: 0.86rem;
    font-weight: 600;
    color: var(--text-primary, #25272A);
}
.shipping-opt-time {
    font-size: 0.74rem;
    color: var(--text-secondary, #737B85);
    margin-left: 6px;
}
.shipping-opt-price {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--text-primary, #25272A);
}

.payment-methods-grid {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
}
.payment-option {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    background: rgba(0, 0, 0, 0.02);
    border: 1px solid var(--border-light, rgba(0, 0, 0, 0.08));
    border-radius: 10px;
    padding: 16px;
    cursor: pointer;
    transition: all 0.2s;
}
.payment-option.active {
    border-color: var(--gold-accent, #A7ADB4);
    background: rgba(167, 173, 180, 0.08);
}
.payment-option input {
    margin-top: 3px;
    accent-color: var(--gold-accent, #737B85);
}
.payment-option-inner {
    flex: 1;
}
.payment-opt-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}
.opt-name {
    font-weight: 600;
    color: var(--text-primary, #25272A);
    font-size: 0.92rem;
}
.opt-discount-tag {
    font-size: 0.68rem;
    font-weight: 700;
    color: #25272A;
    background: rgba(167, 173, 180, 0.25);
    padding: 2px 8px;
    border-radius: 4px;
    border: 1px solid rgba(167, 173, 180, 0.4);
}
.opt-tag-nob {
    font-size: 0.68rem;
    font-weight: 700;
    color: var(--text-primary, #202020);
    background: rgba(167, 173, 180, 0.2);
    border: 1px solid rgba(167, 173, 180, 0.4);
    padding: 2px 8px;
    border-radius: 4px;
}
.opt-tag-sub {
    font-size: 0.68rem;
    color: var(--text-secondary, #737B85);
}
.opt-desc {
    margin: 0;
    font-size: 0.78rem;
    color: var(--text-secondary, #737B85);
}
.payment-instructions-card {
    background: rgba(0, 0, 0, 0.02);
    border: 1px solid rgba(167, 173, 180, 0.28);
    border-radius: 10px;
    padding: 16px 20px;
    margin-bottom: 24px;
}
.pix-key-preview {
    background: #FFFFFF;
    border: 1px solid rgba(0, 0, 0, 0.08);
    padding: 12px 14px;
    border-radius: 8px;
    font-size: 0.8rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    color: var(--text-primary, #25272A);
}
.pix-key-preview code {
    color: var(--text-primary, #25272A);
    font-weight: 700;
    font-size: 0.9rem;
    background: rgba(0, 0, 0, 0.04);
    padding: 3px 6px;
    border-radius: 4px;
}
.btn-copy-pix {
    background: var(--gold-accent, #A7ADB4);
    color: #25272A;
    border: 1px solid var(--gold-hover, #737B85);
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 0.78rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}
.btn-copy-pix:hover {
    background: var(--gold-hover, #737B85);
    color: #FFFFFF;
}
.btn-checkout-submit {
    width: 100%;
    padding: 16px 24px;
    background: var(--gold-accent, #A7ADB4);
    color: #25272A;
    border: 1px solid var(--gold-hover, #737B85);
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: all 0.25s ease;
}
.btn-checkout-submit:hover:not(:disabled) {
    background: var(--gold-hover, #737B85);
    color: #FFFFFF;
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(115, 123, 133, 0.25);
}
.btn-checkout-submit:disabled, .btn-checkout-submit.disabled {
    background: #CBD0D6;
    border-color: #CBD0D6;
    color: #737B85;
    cursor: not-allowed;
    opacity: 0.7;
    transform: none;
    box-shadow: none;
}
.summary-title {
    font-family: var(--font-serif, 'Cormorant Garamond', Georgia, serif);
    font-size: 1.35rem;
    color: var(--text-primary, #25272A);
    margin: 0 0 18px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border-light, rgba(0, 0, 0, 0.08));
}
.checkout-items-list {
    margin-bottom: 20px;
    max-height: 380px;
    overflow-y: auto;
}
.checkout-item-row {
    display: flex;
    gap: 12px;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid var(--border-light, rgba(0, 0, 0, 0.06));
}
.checkout-item-img {
    width: 54px;
    height: 54px;
    border-radius: 8px;
    object-fit: cover;
    background: #f0f0ee;
    border: 1px solid rgba(0, 0, 0, 0.06);
    flex-shrink: 0;
}
.checkout-item-info {
    flex: 1;
    min-width: 0;
}
.checkout-item-title {
    font-size: 0.85rem;
    color: var(--text-primary, #25272A);
    font-weight: 600;
    margin: 0 0 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.checkout-item-meta {
    font-size: 0.74rem;
    color: var(--text-secondary, #737B85);
    margin: 0 0 4px;
}
.checkout-item-controls {
    display: flex;
    align-items: center;
    gap: 8px;
}
.checkout-item-stepper {
    display: inline-flex;
    align-items: center;
    border: 1px solid rgba(0, 0, 0, 0.12);
    border-radius: 4px;
    overflow: hidden;
    background: #FFFFFF;
}
.checkout-item-stepper button {
    border: none;
    background: none;
    width: 22px;
    height: 22px;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s;
}
.checkout-item-stepper button:hover {
    background: rgba(0, 0, 0, 0.08);
}
.checkout-item-stepper span {
    font-size: 0.8rem;
    font-weight: 600;
    min-width: 20px;
    text-align: center;
    padding: 0 2px;
}
.checkout-item-del-btn {
    border: none;
    background: none;
    color: #999;
    font-size: 1.1rem;
    cursor: pointer;
    line-height: 1;
    padding: 0 4px;
    transition: color 0.15s;
}
.checkout-item-del-btn:hover {
    color: #c93b3b;
}
.checkout-item-price {
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--text-primary, #25272A);
    white-space: nowrap;
    text-align: right;
}
.summary-totals-block {
    background: rgba(0, 0, 0, 0.02);
    border-radius: 8px;
    padding: 14px;
    margin-bottom: 20px;
    border: 1px solid var(--border-light, rgba(0, 0, 0, 0.05));
}
.summary-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    color: var(--text-secondary, #737B85);
    margin-bottom: 8px;
}
.summary-row.total-row {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid var(--border-light, rgba(0, 0, 0, 0.08));
    font-size: 1.05rem;
    color: var(--text-primary, #25272A);
    font-weight: 700;
}
.total-val {
    color: var(--text-primary, #25272A);
    font-weight: 800;
}
.checkout-perks-list {
    border-top: 1px solid var(--border-light, rgba(0, 0, 0, 0.08));
    padding-top: 16px;
    margin-bottom: 18px;
}
.perk-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.76rem;
    color: var(--text-secondary, #737B85);
    margin-bottom: 6px;
}
.perk-dot {
    color: var(--gold-accent, #737B85);
}
.btn-return-store {
    display: block;
    text-align: center;
    font-size: 0.8rem;
    color: var(--text-secondary, #737B85);
    text-decoration: none;
    transition: color 0.2s;
}
.btn-return-store:hover {
    color: var(--text-primary, #25272A);
    text-decoration: underline;
}
@media (max-width: 900px) {
    .checkout-grid {
        grid-template-columns: 1fr;
    }
    .form-row-2 {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
let currentPaymentMethod = <?= json_encode($defaultPaymentMethod) ?>;
const enabledPaymentMethods = <?= json_encode($enabledPaymentMethods) ?>;
let cartSubtotal = 0;
let selectedShipping = {
    price: 0,
    name: '<?= View::e($shippingDefaultLabel) ?>',
    selected: false
};
const pixDiscountPercent = <?= json_encode($pixDiscountPercent) ?>;
const pixDiscountVisible = <?= json_encode($pixDiscountVisible) ?>;

function switchPaymentMethod(method) {
    if (!enabledPaymentMethods.includes(method)) {
        return;
    }
    currentPaymentMethod = method;
    document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('active'));
    
    const pixInst = document.getElementById('pixInstructions');
    const waInst = document.getElementById('waInstructions');
    const cardInst = document.getElementById('cardInstructions');
    
    if (pixInst) pixInst.style.display = 'none';
    if (waInst) waInst.style.display = 'none';
    if (cardInst) cardInst.style.display = 'none';
    
    const submitBtn = document.getElementById('btnSubmitCheckout');
    const submitTextSpan = document.getElementById('btnSubmitCheckoutText');
    const customSubmitBase = <?= json_encode($btnSubmitText) ?>;

    if (method === 'pix') {
        const optEl = document.getElementById('optPix');
        if (optEl) optEl.classList.add('active');
        if (pixInst) pixInst.style.display = 'block';
        if (submitTextSpan) {
            submitTextSpan.textContent = `Confirmar Pedido & Gerar Pix ${pixDiscountVisible && pixDiscountPercent > 0 ? '(-' + pixDiscountPercent + '%)' : ''}`;
        }
    } else if (method === 'whatsapp') {
        const optEl = document.getElementById('optWa');
        if (optEl) optEl.classList.add('active');
        if (waInst) waInst.style.display = 'block';
        if (submitTextSpan) {
            submitTextSpan.textContent = 'Avançar para Atendimento WhatsApp';
        }
    } else if (method === 'card') {
        const optEl = document.getElementById('optCard');
        if (optEl) optEl.classList.add('active');
        if (cardInst) cardInst.style.display = 'block';
        if (submitTextSpan) {
            submitTextSpan.textContent = 'Pagar no Cartão de Crédito';
        }
    }
    updateTotals();
}

function updateTotals() {
    const subtotalEl = document.getElementById('summarySubtotal');
    const pixRow = document.getElementById('summaryPixRow');
    const pixDiscEl = document.getElementById('summaryPixDiscount');
    const shippingValEl = document.getElementById('summaryShippingVal');
    const grandTotalEl = document.getElementById('summaryGrandTotal');
    const submitBtn = document.getElementById('btnSubmitCheckout');

    subtotalEl.textContent = 'R$ ' + cartSubtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    let discount = 0;
    if (currentPaymentMethod === 'pix' && pixDiscountVisible && pixDiscountPercent > 0) {
        discount = cartSubtotal * (pixDiscountPercent / 100);
        if (pixRow) {
            pixRow.style.display = 'flex';
            if (pixDiscEl) pixDiscEl.textContent = '- R$ ' + discount.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
    } else {
        if (pixRow) pixRow.style.display = 'none';
    }

    if (shippingValEl) {
        if (selectedShipping.selected && selectedShipping.price > 0) {
            shippingValEl.textContent = selectedShipping.name + ': R$ ' + selectedShipping.price.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        } else if (selectedShipping.selected && selectedShipping.price === 0) {
            shippingValEl.textContent = selectedShipping.name + ' (Grátis)';
        } else {
            shippingValEl.textContent = selectedShipping.name;
        }
    }

    const grandTotal = Math.max(0, (cartSubtotal - discount) + (selectedShipping.price || 0));
    grandTotalEl.textContent = 'R$ ' + grandTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    if (cartSubtotal <= 0) {
        submitBtn.disabled = true;
        submitBtn.classList.add('disabled');
        submitBtn.title = 'Sua sacola está vazia. Adicione ao menos uma joia para prosseguir.';
    } else {
        submitBtn.disabled = false;
        submitBtn.classList.remove('disabled');
        submitBtn.title = '';
    }
}

function getStoredCartItems() {
    try {
        if (window.diviniumCart && typeof window.diviniumCart.getItems === 'function') {
            return window.diviniumCart.getItems();
        }
        const saved = localStorage.getItem('divinium_cart');
        return saved ? JSON.parse(saved) : [];
    } catch(e) {
        return [];
    }
}

function loadCartFromStorage() {
    const listContainer = document.getElementById('checkoutItemsList');
    const items = getStoredCartItems();

    if (!items || items.length === 0) {
        listContainer.innerHTML = '<div style="padding: 24px 0; color: #888; font-size: 0.85rem; text-align: center;">Sua sacola está vazia no momento.<br><a href="/loja" style="color:var(--gold-accent, #737B85); text-decoration:underline; font-weight:600; display:inline-block; margin-top:10px;">← Descobrir Joias na Boutique</a></div>';
        cartSubtotal = 0;
        updateTotals();
        return;
    }

    listContainer.innerHTML = '';
    cartSubtotal = 0;

    items.forEach((it, idx) => {
        const pCents = it.priceCents || it.price_cents || (parseFloat(it.price || 0) * 100);
        const qty = parseInt(it.qty || it.quantity || 1, 10);
        const itemTotal = (pCents * qty) / 100;
        cartSubtotal += itemTotal;

        const itemKey = it.itemKey || it.sku || it.id || ('idx_' + idx);
        const img = it.image || it.image_url || '/assets/images/products/an-rd-20-capa.jpg';
        const stone = it.stone || it.stone_mineral || '';

        const row = document.createElement('div');
        row.className = 'checkout-item-row';
        row.innerHTML = `
            <img src="${img}" class="checkout-item-img" alt="${it.name}">
            <div class="checkout-item-info">
                <h4 class="checkout-item-title" title="${it.name}">${it.name}</h4>
                <p class="checkout-item-meta">${stone ? stone : 'Gema Autoral'}</p>
                <div class="checkout-item-controls">
                    <div class="checkout-item-stepper">
                        <button type="button" onclick="changeCheckoutQty('${itemKey}', -1)" aria-label="Diminuir">-</button>
                        <span>${qty}</span>
                        <button type="button" onclick="changeCheckoutQty('${itemKey}', 1)" aria-label="Aumentar">+</button>
                    </div>
                    <button type="button" class="checkout-item-del-btn" onclick="removeCheckoutItem('${itemKey}')" title="Remover">&times;</button>
                </div>
            </div>
            <span class="checkout-item-price">R$ ${itemTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
        `;
        listContainer.appendChild(row);
    });

    updateTotals();
}

function changeCheckoutQty(itemKey, delta) {
    if (window.diviniumCart && typeof window.diviniumCart.changeQty === 'function') {
        window.diviniumCart.changeQty(itemKey, delta);
        loadCartFromStorage();
        return;
    }

    let items = getStoredCartItems();
    const idx = items.findIndex(it => (it.itemKey || it.sku || it.id) === itemKey);
    if (idx !== -1) {
        let q = (items[idx].qty || items[idx].quantity || 1) + delta;
        if (q <= 0) {
            items.splice(idx, 1);
        } else {
            items[idx].qty = q;
            items[idx].quantity = q;
        }
        localStorage.setItem('divinium_cart', JSON.stringify(items));
        loadCartFromStorage();
    }
}

function removeCheckoutItem(itemKey) {
    if (window.diviniumCart && typeof window.diviniumCart.remove === 'function') {
        window.diviniumCart.remove(itemKey);
        loadCartFromStorage();
        return;
    }

    let items = getStoredCartItems();
    items = items.filter(it => (it.itemKey || it.sku || it.id) !== itemKey);
    localStorage.setItem('divinium_cart', JSON.stringify(items));
    loadCartFromStorage();
}

function copyPixKey(key) {
    navigator.clipboard.writeText(key).then(() => {
        const btnText = document.getElementById('copyPixBtnText');
        if (btnText) {
            const orig = btnText.innerHTML;
            btnText.innerHTML = '✓ Chave Copiada!';
            setTimeout(() => { btnText.innerHTML = orig; }, 3000);
        }
    }).catch(() => {
        alert('Chave Pix: ' + key);
    });
}

function consultarCep() {
    const cepInput = document.getElementById('clientCep');
    const cep = cepInput.value.replace(/\D/g, '');
    if (cep.length !== 8) {
        alert('Por favor, informe um CEP válido com 8 dígitos.');
        return;
    }

    const btn = document.getElementById('btnConsultarCep');
    const textSpan = document.getElementById('btnConsultarCepText') || btn;
    const origBtnText = textSpan.textContent;
    textSpan.textContent = '...';
    btn.disabled = true;

    // 1. Auto-preencher endereço via ViaCEP
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(res => res.json())
        .then(data => {
            if (!data.erro) {
                const addr = document.getElementById('clientAddress');
                addr.value = `${data.logradouro}, , ${data.bairro} - ${data.localidade}/${data.uf}`;
                addr.focus();
            }
        }).catch(() => {});

    // 2. Consultar frete real no endpoint seguro DIVINIUM
    const shippingBox = document.getElementById('shippingCalcContainer');
    const shippingStatus = document.getElementById('shippingCalcStatus');
    const shippingList = document.getElementById('shippingOptionsList');

    shippingBox.style.display = 'block';
    shippingStatus.innerHTML = '<span style="color: var(--gold-accent, #737B85);">Buscando opções reais de frete para o CEP...</span>';
    shippingList.innerHTML = '';

    fetch('/api/frete/calcular', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cep: cep })
    })
    .then(res => res.json())
    .then(data => {
        textSpan.textContent = origBtnText;
        btn.disabled = false;

        if (data.ok && data.quotes && data.quotes.length > 0) {
            shippingStatus.innerHTML = '<strong>' + data.quotes.length + ' opções de frete real encontradas:</strong>';
            shippingList.innerHTML = '';

            data.quotes.forEach((q, idx) => {
                const optEl = document.createElement('div');
                optEl.className = 'shipping-option-item' + (idx === 0 ? ' selected' : '');
                optEl.innerHTML = `
                    <div class="shipping-opt-left">
                        <input type="radio" name="selected_shipping_radio" id="ship_opt_${idx}" value="${q.price}" ${idx === 0 ? 'checked' : ''}>
                        <label for="ship_opt_${idx}">
                            <span class="shipping-opt-name">${q.name}</span>
                            <span class="shipping-opt-time">${q.delivery_time ? '(' + q.delivery_time + ' dias úteis)' : ''}</span>
                        </label>
                    </div>
                    <span class="shipping-opt-price">R$ ${q.price.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                `;
                optEl.onclick = () => {
                    document.querySelectorAll('.shipping-option-item').forEach(el => el.classList.remove('selected'));
                    optEl.classList.add('selected');
                    optEl.querySelector('input').checked = true;
                    selectedShipping = {
                        price: parseFloat(q.price),
                        name: q.name,
                        selected: true
                    };
                    updateTotals();
                };
                shippingList.appendChild(optEl);
            });

            // Seleciona a primeira cotação por padrão
            selectedShipping = {
                price: parseFloat(data.quotes[0].price),
                name: data.quotes[0].name,
                selected: true
            };
            updateTotals();
        } else {
            // Nenhum serviço de cálculo externo ativo no momento: transparência absoluta (sem inventar cotação fictícia)
            shippingStatus.innerHTML = `
                <div style="background: rgba(0,0,0,0.03); padding: 10px 14px; border-radius: 6px; font-size: 0.82rem; color: var(--text-secondary);">
                    <span>${data.advice || '<?= View::e($shippingDefaultLabel) ?>'}</span>
                </div>
            `;
            selectedShipping = {
                price: 0,
                name: '<?= View::e($shippingDefaultLabel) ?>',
                selected: false
            };
            updateTotals();
        }
    })
    .catch(() => {
        textSpan.textContent = origBtnText;
        btn.disabled = false;
        shippingStatus.innerHTML = `<span style="font-size: 0.82rem; color: var(--text-secondary);"><?= View::e($shippingDefaultLabel) ?></span>`;
        updateTotals();
    });
}

function handleCheckoutSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const name = form.name.value;
    const email = form.email.value;
    const phone = form.phone.value;
    const cep = form.cep.value;
    const address = form.address.value;

    const items = getStoredCartItems();

    if (!items || items.length === 0 || cartSubtotal <= 0) {
        alert('Sua sacola está vazia no momento. Adicione ao menos uma joia para prosseguir com o pedido.');
        return;
    }

    let discount = 0;
    if (currentPaymentMethod === 'pix' && pixDiscountVisible && pixDiscountPercent > 0) {
        discount = cartSubtotal * (pixDiscountPercent / 100);
    }
    const finalTotal = Math.max(0, (cartSubtotal - discount) + (selectedShipping.price || 0));

    if (!currentPaymentMethod) {
        alert('Nenhuma modalidade de pagamento online ativa disponível no momento. Entre em contato com nosso atendimento para suporte personalizado.');
        return;
    }

    if (currentPaymentMethod === 'whatsapp') {
        let msg = `Olá! Gostaria de concluir meu pedido na DIVINIUM:\n\n*Cliente:* ${name}\n*E-mail:* ${email}\n*Telefone:* ${phone}\n*CEP:* ${cep}\n*Endereço:* ${address}\n\n*Itens Selecionados:*\n`;
        items.forEach(it => {
            const qty = it.qty || it.quantity || 1;
            const stone = it.stone || it.stone_mineral || '';
            msg += `• ${qty}x ${it.name} ${stone ? '(' + stone + ')' : ''}\n`;
        });
        if (selectedShipping.selected && selectedShipping.price > 0) {
            msg += `\n*Frete Escolhido:* ${selectedShipping.name} (R$ ${selectedShipping.price.toFixed(2)})`;
        }
        msg += `\n*Total Estimado:* R$ ${finalTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}\n\nPor favor, confirmem a disponibilidade para envio!`;
        const waUrl = `https://wa.me/<?= View::e($waNumber) ?>?text=` + encodeURIComponent(msg);
        window.open(waUrl, '_blank');
        return;
    }

    if (currentPaymentMethod === 'pix') {
        alert(`Pedido registrado com sucesso!\n\nValor Final: R$ ${finalTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}\n\nChave Pix Oficial: <?= View::e($pixKey) ?>\nBeneficiário: <?= View::e($pixBeneficiary) ?>\n\nEnvie o comprovante para nosso Concierge VIP no WhatsApp.`);
        let msg = `Olá! Acabei de gerar o Pix do meu pedido (${name} - R$ ${finalTotal.toFixed(2)}). Segue comprovante para envio prioritário!`;
        window.open(`https://wa.me/<?= View::e($waNumber) ?>?text=` + encodeURIComponent(msg), '_blank');
        return;
    }

    if (currentPaymentMethod === 'card') {
        if (!enabledPaymentMethods.includes('card')) {
            alert('A modalidade de pagamento por Cartão de Crédito está desativada no momento. Por favor, escolha outra opção para continuar.');
            return;
        }
        alert('Direcionando para o gateway de pagamento homologado ASAAS...');
        return;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadCartFromStorage();
    window.addEventListener('cart:updated', loadCartFromStorage);
    if (currentPaymentMethod) {
        switchPaymentMethod(currentPaymentMethod);
    } else {
        const submitBtn = document.getElementById('btnSubmitCheckout');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('disabled');
            submitBtn.title = 'Nenhuma forma de pagamento online disponível no momento.';
        }
    }
});
</script>

