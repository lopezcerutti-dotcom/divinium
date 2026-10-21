<?php
/**
 * VIEW: CHECKOUT NOBRE DIVINIUM (/checkout)
 * Quiet luxury checkout experience com integração Pix, Asaas e Concierge VIP
 */
use App\Models\Setting;
use App\Core\View;

$pixKey = Setting::get('payment_pix_key', 'contato@divinium.com.br');
$pixBeneficiary = Setting::get('payment_pix_name', 'DIVINIUM JOIAS AUTORAIS');
$waNumber = Setting::get('hero_whatsapp_number', '5511999999999');
?>

<div class="checkout-page-container">
    <div class="container checkout-inner">
        
        <!-- HEADER DE SEGURANÇA DO CHECKOUT -->
        <div class="checkout-header">
            <div class="checkout-brand">
                <span class="sparkle">✦</span>
                <span class="brand-name">DIVINIUM</span>
                <span class="checkout-badge">CHECKOUT SEGURO</span>
            </div>
            <div class="checkout-security-status">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                <span>Criptografia SSL de 256 bits • Ambiente Protegido</span>
            </div>
        </div>

        <div class="checkout-grid">
            <!-- COLUNA ESQUERDA: DADOS DO CLIENTE & FORMA DE PAGAMENTO -->
            <div class="checkout-col-main">
                <div class="checkout-card">
                    <h2 class="card-step-title">
                        <span class="step-num">1</span>
                        <span>Identificação & Entrega</span>
                    </h2>

                    <form id="checkoutForm" onsubmit="handleCheckoutSubmit(event)">
                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="clientName">Nome Completo *</label>
                                <input type="text" id="clientName" name="name" class="form-input" placeholder="Ex: Maria Clara Silveira" required>
                            </div>
                            <div class="form-group">
                                <label for="clientEmail">E-mail para Confirmação *</label>
                                <input type="email" id="clientEmail" name="email" class="form-input" placeholder="seu@email.com" required>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="clientPhone">WhatsApp / Telefone *</label>
                                <input type="tel" id="clientPhone" name="phone" class="form-input" placeholder="(11) 99999-9999" required>
                            </div>
                            <div class="form-group">
                                <label for="clientCep">CEP de Entrega *</label>
                                <div style="display: flex; gap: 8px;">
                                    <input type="text" id="clientCep" name="cep" class="form-input" placeholder="00000-000" maxlength="9" required>
                                    <button type="button" class="btn-calc-cep" onclick="consultarCep()">Buscar</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="clientAddress">Endereço Completo & Bairro</label>
                            <input type="text" id="clientAddress" name="address" class="form-input" placeholder="Rua, Número, Complemento, Bairro">
                        </div>

                        <!-- FORMAS DE PAGAMENTO NOBRES -->
                        <h2 class="card-step-title" style="margin-top: 36px;">
                            <span class="step-num">2</span>
                            <span>Forma de Pagamento Preferencial</span>
                        </h2>

                        <div class="payment-methods-grid">
                            <!-- Opção 1: Pix com 5% de Desconto -->
                            <label class="payment-option active" id="optPix">
                                <input type="radio" name="payment_method" value="pix" checked onchange="switchPaymentMethod('pix')">
                                <div class="payment-option-inner">
                                    <div class="payment-opt-top">
                                        <span class="opt-name">⚡ Pix Instantâneo</span>
                                        <span class="opt-discount-tag">5% DE DESCONTO</span>
                                    </div>
                                    <p class="opt-desc">Aprovação imediata. QR Code gerado instantaneamente na tela.</p>
                                </div>
                            </label>

                            <!-- Opção 2: Concierge VIP WhatsApp -->
                            <label class="payment-option" id="optWa">
                                <input type="radio" name="payment_method" value="whatsapp" onchange="switchPaymentMethod('whatsapp')">
                                <div class="payment-option-inner">
                                    <div class="payment-opt-top">
                                        <span class="opt-name">💬 Concierge VIP (WhatsApp)</span>
                                        <span class="opt-tag-nob">ATENDIMENTO HUMANO</span>
                                    </div>
                                    <p class="opt-desc">Conclua o pedido diretamente com um especialista e tire todas as dúvidas.</p>
                                </div>
                            </label>

                            <!-- Opção 3: Cartão de Crédito em até 10x -->
                            <label class="payment-option" id="optCard">
                                <input type="radio" name="payment_method" value="card" onchange="switchPaymentMethod('card')">
                                <div class="payment-option-inner">
                                    <div class="payment-opt-top">
                                        <span class="opt-name">💳 Cartão de Crédito</span>
                                        <span class="opt-tag-sub">ATÉ 10X SEM JUROS</span>
                                    </div>
                                    <p class="opt-desc">Pagamento processado em ambiente 100% criptografado homologado ASAAS.</p>
                                </div>
                            </label>
                        </div>

                        <!-- Painel Dinâmico de Instruções de Pagamento -->
                        <div id="paymentInstructionsBox" class="payment-instructions-card">
                            <div id="pixInstructions">
                                <h4 style="color: #caa15c; margin: 0 0 8px; font-size: 1rem;">Pagamento Imediato via Pix com 5% OFF</h4>
                                <p style="font-size: 0.85rem; color: #b8b1a7; margin: 0 0 14px;">
                                    Após clicar em <strong>"Confirmar Pedido & Gerar Pix"</strong>, você receberá a chave e o QR Code oficial para pagamento instantâneo com envio prioritário.
                                </p>
                                <div class="pix-key-preview">
                                    <span>Chave Oficial Divinium:</span>
                                    <code><?= View::e($pixKey) ?></code>
                                    <small>(<?= View::e($pixBeneficiary) ?>)</small>
                                </div>
                            </div>

                            <div id="waInstructions" style="display: none;">
                                <h4 style="color: #4ade80; margin: 0 0 8px; font-size: 1rem;">Finalização com Concierge Exclusivo</h4>
                                <p style="font-size: 0.85rem; color: #b8b1a7; margin: 0;">
                                    Ao avançar, nossa equipe já receberá a lista exata das joias selecionadas e o endereço para gerar seu link de pagamento com seguro cortesia.
                                </p>
                            </div>

                            <div id="cardInstructions" style="display: none;">
                                <h4 style="color: #ffffff; margin: 0 0 8px; font-size: 1rem;">Cartão de Crédito com Parcelamento Sem Juros</h4>
                                <p style="font-size: 0.85rem; color: #b8b1a7; margin: 0;">
                                    Você será direcionado para o gateway seguro homologado da Divinium para inserção protegida dos dados do cartão em até 10x.
                                </p>
                            </div>
                        </div>

                        <button type="submit" class="btn-checkout-submit" id="btnSubmitCheckout">
                            <span>Confirmar Pedido & Continuar</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- COLUNA DIREITA: RESUMO DO PEDIDO & SACOLA -->
            <div class="checkout-col-summary">
                <div class="checkout-card summary-card">
                    <h3 class="summary-title">Resumo da Sua Sacola</h3>
                    
                    <div id="checkoutItemsList" class="checkout-items-list">
                        <!-- Carregado via JS -->
                        <div class="loading-items">Carregando itens da sacola...</div>
                    </div>

                    <div class="summary-totals-block">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span id="summarySubtotal">R$ 0,00</span>
                        </div>
                        <div class="summary-row" id="summaryPixRow">
                            <span style="color: #caa15c;">Desconto Pix (5%):</span>
                            <span id="summaryPixDiscount" style="color: #caa15c;">- R$ 0,00</span>
                        </div>
                        <div class="summary-row">
                            <span>Frete:</span>
                            <span style="color: #4ade80; font-weight: 600;">CORTESIA COM SEGURO</span>
                        </div>
                        <div class="summary-row total-row">
                            <span>Total Estimado:</span>
                            <span id="summaryGrandTotal" class="total-val">R$ 0,00</span>
                        </div>
                    </div>

                    <div class="checkout-perks-list">
                        <div class="perk-item">
                            <span class="perk-dot">✦</span>
                            <span>Prata de Lei 925 com Certificado Vitalício</span>
                        </div>
                        <div class="perk-item">
                            <span class="perk-dot">✦</span>
                            <span>Direito de Arrependimento de 7 Dias (CDC)</span>
                        </div>
                        <div class="perk-item">
                            <span class="perk-dot">✦</span>
                            <span>Embalagem Especial para Presente em Veludo</span>
                        </div>
                    </div>

                    <a href="/loja" class="btn-return-store">← Escolher mais joias na boutique</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.checkout-page-container {
    background: #0d0c0b;
    min-height: 85vh;
    padding: 40px 0 80px;
    color: #ded7cf;
}
.checkout-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
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
    color: #caa15c;
    font-size: 1.2rem;
}
.checkout-brand .brand-name {
    font-family: var(--font-serif, 'Cormorant Garamond', Georgia, serif);
    font-size: 1.5rem;
    color: #ffffff;
    letter-spacing: 2px;
}
.checkout-badge {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 1px;
    background: rgba(202, 161, 92, 0.15);
    color: #caa15c;
    padding: 3px 8px;
    border-radius: 4px;
    border: 1px solid rgba(202, 161, 92, 0.3);
}
.checkout-security-status {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.78rem;
    color: #9e978e;
}
.checkout-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 30px;
    align-items: start;
}
.checkout-card {
    background: #141312;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 14px;
    padding: 32px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}
.card-step-title {
    font-family: var(--font-serif, 'Cormorant Garamond', Georgia, serif);
    font-size: 1.35rem;
    color: #ffffff;
    margin: 0 0 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.step-num {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #caa15c;
    color: #0d0c0b;
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
    color: #ded7cf;
    margin-bottom: 6px;
}
.form-input {
    width: 100%;
    padding: 12px 14px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 8px;
    color: #ffffff;
    font-size: 0.88rem;
    transition: border-color 0.2s;
    box-sizing: border-box;
}
.form-input:focus {
    outline: none;
    border-color: #caa15c;
    background: rgba(202, 161, 92, 0.04);
}
.btn-calc-cep {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #ded7cf;
    border-radius: 8px;
    padding: 0 16px;
    font-size: 0.82rem;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s;
}
.btn-calc-cep:hover {
    background: #caa15c;
    color: #0d0c0b;
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
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    padding: 16px;
    cursor: pointer;
    transition: all 0.2s;
}
.payment-option.active {
    border-color: #caa15c;
    background: rgba(202, 161, 92, 0.06);
}
.payment-option input {
    margin-top: 3px;
    accent-color: #caa15c;
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
    color: #ffffff;
    font-size: 0.92rem;
}
.opt-discount-tag {
    font-size: 0.68rem;
    font-weight: 700;
    color: #caa15c;
    background: rgba(202, 161, 92, 0.18);
    padding: 2px 8px;
    border-radius: 4px;
}
.opt-tag-nob {
    font-size: 0.68rem;
    font-weight: 700;
    color: #4ade80;
    background: rgba(74, 222, 128, 0.15);
    padding: 2px 8px;
    border-radius: 4px;
}
.opt-tag-sub {
    font-size: 0.68rem;
    color: #888;
}
.opt-desc {
    margin: 0;
    font-size: 0.78rem;
    color: #9e978e;
}
.payment-instructions-card {
    background: rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(202, 161, 92, 0.2);
    border-radius: 10px;
    padding: 16px 20px;
    margin-bottom: 24px;
}
.pix-key-preview {
    background: rgba(255, 255, 255, 0.04);
    padding: 10px 14px;
    border-radius: 6px;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.pix-key-preview code {
    color: #caa15c;
    font-weight: 700;
}
.btn-checkout-submit {
    width: 100%;
    padding: 16px 24px;
    background: #caa15c;
    color: #0d0c0b;
    border: none;
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
.btn-checkout-submit:hover {
    background: #e0c080;
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(202, 161, 92, 0.3);
}
.summary-title {
    font-family: var(--font-serif, 'Cormorant Garamond', Georgia, serif);
    font-size: 1.35rem;
    color: #ffffff;
    margin: 0 0 18px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}
.checkout-items-list {
    margin-bottom: 20px;
    max-height: 320px;
    overflow-y: auto;
}
.checkout-item-row {
    display: flex;
    gap: 14px;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}
.checkout-item-img {
    width: 54px;
    height: 54px;
    border-radius: 8px;
    object-fit: cover;
    background: #1e1d1b;
}
.checkout-item-info {
    flex: 1;
}
.checkout-item-title {
    font-size: 0.85rem;
    color: #ffffff;
    font-weight: 600;
    margin: 0 0 2px;
}
.checkout-item-meta {
    font-size: 0.74rem;
    color: #caa15c;
    margin: 0;
}
.checkout-item-price {
    font-size: 0.88rem;
    font-weight: 700;
    color: #ffffff;
}
.summary-totals-block {
    background: rgba(255, 255, 255, 0.02);
    border-radius: 8px;
    padding: 14px;
    margin-bottom: 20px;
}
.summary-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    color: #9e978e;
    margin-bottom: 8px;
}
.summary-row.total-row {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    font-size: 1.05rem;
    color: #ffffff;
    font-weight: 700;
}
.total-val {
    color: #caa15c;
}
.checkout-perks-list {
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    padding-top: 16px;
    margin-bottom: 18px;
}
.perk-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.76rem;
    color: #888;
    margin-bottom: 6px;
}
.perk-dot {
    color: #caa15c;
}
.btn-return-store {
    display: block;
    text-align: center;
    font-size: 0.8rem;
    color: #caa15c;
    text-decoration: none;
    transition: color 0.2s;
}
.btn-return-store:hover {
    color: #e0c080;
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
let currentPaymentMethod = 'pix';
let cartSubtotal = 0;

function switchPaymentMethod(method) {
    currentPaymentMethod = method;
    document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('active'));
    
    document.getElementById('pixInstructions').style.display = 'none';
    document.getElementById('waInstructions').style.display = 'none';
    document.getElementById('cardInstructions').style.display = 'none';
    
    const submitBtn = document.getElementById('btnSubmitCheckout');

    if (method === 'pix') {
        document.getElementById('optPix').classList.add('active');
        document.getElementById('pixInstructions').style.display = 'block';
        submitBtn.innerHTML = '<span>Confirmar Pedido & Gerar Pix (-5%)</span> <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
    } else if (method === 'whatsapp') {
        document.getElementById('optWa').classList.add('active');
        document.getElementById('waInstructions').style.display = 'block';
        submitBtn.innerHTML = '<span>Avançar para Atendimento WhatsApp</span> <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
    } else if (method === 'card') {
        document.getElementById('optCard').classList.add('active');
        document.getElementById('cardInstructions').style.display = 'block';
        submitBtn.innerHTML = '<span>Pagar no Cartão de Crédito</span> <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
    }
    updateTotals();
}

function updateTotals() {
    const subtotalEl = document.getElementById('summarySubtotal');
    const pixRow = document.getElementById('summaryPixRow');
    const pixDiscEl = document.getElementById('summaryPixDiscount');
    const grandTotalEl = document.getElementById('summaryGrandTotal');

    subtotalEl.textContent = 'R$ ' + cartSubtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    if (currentPaymentMethod === 'pix') {
        const discount = cartSubtotal * 0.05;
        const total = cartSubtotal * 0.95;
        pixRow.style.display = 'flex';
        pixDiscEl.textContent = '- R$ ' + discount.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        grandTotalEl.textContent = 'R$ ' + total.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    } else {
        pixRow.style.display = 'none';
        grandTotalEl.textContent = 'R$ ' + cartSubtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
}

function loadCartFromStorage() {
    const listContainer = document.getElementById('checkoutItemsList');
    let items = [];
    try {
        const saved = localStorage.getItem('divinium_cart');
        if (saved) items = JSON.parse(saved);
    } catch (e) {}

    if (!items || items.length === 0) {
        listContainer.innerHTML = '<div style="padding: 20px 0; color: #888; font-size: 0.85rem; text-align: center;">Sua sacola está vazia no momento.<br><a href="/loja" style="color:#caa15c; text-decoration:none; display:inline-block; margin-top:8px;">Descobrir Joias</a></div>';
        cartSubtotal = 0;
        updateTotals();
        return;
    }

    listContainer.innerHTML = '';
    cartSubtotal = 0;

    items.forEach(it => {
        const pCents = it.price_cents || (parseFloat(it.price || 0) * 100);
        const itemTotal = (pCents * (it.quantity || 1)) / 100;
        cartSubtotal += itemTotal;

        const row = document.createElement('div');
        row.className = 'checkout-item-row';
        row.innerHTML = `
            <img src="${it.image_url || '/assets/images/products/an-rd-20-capa.jpg'}" class="checkout-item-img" alt="${it.name}">
            <div class="checkout-item-info">
                <h4 class="checkout-item-title">${it.name}</h4>
                <p class="checkout-item-meta">${it.quantity || 1}x • ${it.stone_mineral || 'Gema Autoral'}</p>
            </div>
            <span class="checkout-item-price">R$ ${itemTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
        `;
        listContainer.appendChild(row);
    });

    updateTotals();
}

function consultarCep() {
    const cepInput = document.getElementById('clientCep');
    const cep = cepInput.value.replace(/\D/g, '');
    if (cep.length !== 8) {
        alert('Por favor, informe um CEP válido com 8 dígitos.');
        return;
    }
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(res => res.json())
        .then(data => {
            if (!data.erro) {
                const addr = document.getElementById('clientAddress');
                addr.value = `${data.logradouro}, , ${data.bairro} - ${data.localidade}/${data.uf}`;
                addr.focus();
            } else {
                alert('CEP não localizado.');
            }
        }).catch(() => {});
}

function handleCheckoutSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const name = form.name.value;
    const email = form.email.value;
    const phone = form.phone.value;
    const address = form.address.value;

    let items = [];
    try {
        items = JSON.parse(localStorage.getItem('divinium_cart') || '[]');
    } catch(e) {}

    if (currentPaymentMethod === 'whatsapp') {
        let msg = `Olá! Gostaria de concluir meu pedido na DIVINIUM:\n\n*Cliente:* ${name}\n*E-mail:* ${email}\n*Telefone:* ${phone}\n*Endereço:* ${address}\n\n*Itens Selecionados:*\n`;
        items.forEach(it => {
            msg += `• ${it.quantity || 1}x ${it.name} (${it.stone_mineral || ''})\n`;
        });
        msg += `\n*Total Estimado:* R$ ${cartSubtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}\n\nPor favor, confirmem a disponibilidade para envio!`;
        const waUrl = `https://wa.me/<?= View::e($waNumber) ?>?text=` + encodeURIComponent(msg);
        window.open(waUrl, '_blank');
        return;
    }

    if (currentPaymentMethod === 'pix') {
        const pixTotal = cartSubtotal * 0.95;
        alert(`Pedido registrado com sucesso!\n\nValor com 5% de Desconto Pix: R$ ${pixTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}\n\nChave Pix Oficial: <?= View::e($pixKey) ?>\nBeneficiário: <?= View::e($pixBeneficiary) ?>\n\nEnvie o comprovante para nosso Concierge VIP no WhatsApp.`);
        let msg = `Olá! Acabei de gerar o Pix do meu pedido (${name} - R$ ${pixTotal.toFixed(2)}). Segue comprovante para envio prioritário!`;
        window.open(`https://wa.me/<?= View::e($waNumber) ?>?text=` + encodeURIComponent(msg), '_blank');
        return;
    }

    alert('Direcionando para o gateway de pagamento seguro...');
}

document.addEventListener('DOMContentLoaded', loadCartFromStorage);
</script>
