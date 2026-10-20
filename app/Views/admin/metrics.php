<?php
/**
 * VIEW: DASHBOARD DE ACOMPANHAMENTO & ANALYTICS - ADMIN DIVINIUM
 */
$maxClicksInChart = 1;
foreach ($chartDays as $d) {
    if ($d['clicks'] > $maxClicksInChart) {
        $maxClicksInChart = $d['clicks'];
    }
}
?>

<!-- BANNER DE APRESENTAÇÃO DO DASHBOARD -->
<div class="admin-card" style="background: linear-gradient(135deg, rgba(202, 161, 92, 0.12) 0%, rgba(13, 15, 18, 0.95) 100%); border: 1px solid rgba(202, 161, 92, 0.35); margin-bottom: 24px; padding: 24px 28px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                <span style="color: #caa15c; font-size: 1.2rem;">✦</span>
                <h2 style="font-family: var(--font-serif); font-size: 1.5rem; color: #ffffff; margin: 0;">Dashboard de Acompanhamento & Conversão</h2>
                <span style="background: rgba(238, 77, 45, 0.2); color: #ff6b4a; font-size: 0.68rem; font-weight: 700; padding: 3px 8px; border-radius: 12px; text-transform: uppercase;">Shopee Analytics</span>
            </div>
            <p style="font-size: 0.84rem; color: #b8b1a7; margin: 0;">
                Monitore em tempo real as intenções de compra, cliques na Shopee Oficial, joias mais desejadas e o volume financeiro potencial gerado pelo seu catálogo.
            </p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="/loja" target="_blank" class="btn-admin-gold" style="text-decoration: none; font-size: 0.8rem; padding: 9px 16px;">
                <span>Ver Vitrine Ao Vivo ↗</span>
            </a>
        </div>
    </div>
</div>

<!-- 1. GRID DE KPIS PRINCIPAIS -->
<div class="metrics-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 18px; margin-bottom: 24px;">
    
    <!-- KPI 1: TOTAL DE CLIQUES NA SHOPEE -->
    <div class="metric-card" style="background: #14161a; border: 1px solid rgba(238, 77, 45, 0.3); border-radius: 12px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
            <div class="metric-icon-wrap" style="background: rgba(238, 77, 45, 0.15); color: #ee4d2d; width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h2v2c0 .55.45 1 1 1s1-.45 1-1V8h6v2c0 .55.45 1 1 1s1-.45 1-1V8h2v12z"/></svg>
            </div>
            <?php 
                $clicksTarget = (int) ($metricsConfig['monthly_clicks_target'] ?? 1000);
                $clicksPct = min(100, round(($totalClicks / max(1, $clicksTarget)) * 100));
            ?>
            <span style="font-size: 0.72rem; color: #4ade80; background: rgba(74, 222, 128, 0.1); padding: 2px 8px; border-radius: 12px; font-weight: 600;">
                <?= $clicksPct ?>% da meta mensal
            </span>
        </div>
        <div class="metric-data">
            <span class="metric-value" style="font-size: 1.9rem; font-weight: 700; color: #ffffff; display: block; line-height: 1.1; margin-bottom: 4px;"><?= number_format($totalClicks, 0, ',', '.') ?></span>
            <span class="metric-label" style="font-size: 0.78rem; color: #9e978e; text-transform: uppercase; letter-spacing: 0.5px;">Intenções de Compra Shopee</span>
        </div>
    </div>

    <!-- KPI 2: VOLUME FINANCEIRO POTENCIAL -->
    <div class="metric-card" style="background: #14161a; border: 1px solid rgba(202, 161, 92, 0.3); border-radius: 12px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
            <div class="metric-icon-wrap" style="background: rgba(202, 161, 92, 0.15); color: #caa15c; width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </div>
            <?php 
                $revTarget = (int) ($metricsConfig['monthly_revenue_target'] ?? 50000);
                $revPct = min(100, round((($potentialSalesCents / 100) / max(1, $revTarget)) * 100));
            ?>
            <span style="font-size: 0.72rem; color: #caa15c; background: rgba(202, 161, 92, 0.1); padding: 2px 8px; border-radius: 12px; font-weight: 600;">
                Meta: R$ <?= number_format($revTarget, 0, ',', '.') ?>
            </span>
        </div>
        <div class="metric-data">
            <span class="metric-value" style="font-size: 1.7rem; font-weight: 700; color: #caa15c; display: block; line-height: 1.1; margin-bottom: 4px;">R$ <?= number_format($potentialSalesCents / 100, 2, ',', '.') ?></span>
            <span class="metric-label" style="font-size: 0.78rem; color: #9e978e; text-transform: uppercase; letter-spacing: 0.5px;">Volume Potencial de Venda</span>
        </div>
    </div>

    <!-- KPI 3: TAXA DE CONVERSÃO DE CLIQUES (CTR) -->
    <div class="metric-card" style="background: #14161a; border: 1px solid rgba(74, 222, 128, 0.25); border-radius: 12px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
            <div class="metric-icon-wrap" style="background: rgba(74, 222, 128, 0.12); color: #4ade80; width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline></svg>
            </div>
            <span style="font-size: 0.72rem; color: #4ade80; background: rgba(74, 222, 128, 0.1); padding: 2px 8px; border-radius: 12px; font-weight: 600;">Alta Retenção</span>
        </div>
        <div class="metric-data">
            <span class="metric-value" style="font-size: 1.9rem; font-weight: 700; color: #4ade80; display: block; line-height: 1.1; margin-bottom: 4px;"><?= $ctr ?>%</span>
            <span class="metric-label" style="font-size: 0.78rem; color: #9e978e; text-transform: uppercase; letter-spacing: 0.5px;">Taxa de Clique (CTR)</span>
        </div>
    </div>

    <!-- KPI 4: VISUALIZAÇÕES DE PÁGINA -->
    <div class="metric-card" style="background: #14161a; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
            <div class="metric-icon-wrap" style="background: rgba(255, 255, 255, 0.08); color: #ffffff; width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            </div>
            <span style="font-size: 0.72rem; color: #bbb; background: rgba(255, 255, 255, 0.06); padding: 2px 8px; border-radius: 12px; font-weight: 600;">Loja & Home</span>
        </div>
        <div class="metric-data">
            <span class="metric-value" style="font-size: 1.9rem; font-weight: 700; color: #ffffff; display: block; line-height: 1.1; margin-bottom: 4px;"><?= number_format($totalViews, 0, ',', '.') ?></span>
            <span class="metric-label" style="font-size: 0.78rem; color: #9e978e; text-transform: uppercase; letter-spacing: 0.5px;">Visualizações Totais</span>
        </div>
    </div>

</div>

<!-- 2. GRÁFICO INTERATIVO DE TENDÊNCIA E DISTRIBUIÇÃO POR CATEGORIA -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 28px;">
    
    <!-- GRÁFICO DE BARRAS DOS ÚLTIMOS 7 DIAS (SVG PURO) -->
    <div class="admin-card" style="background: #14161a; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h3 class="card-title" style="margin: 0; font-size: 1.15rem;">Intenções de Compra por Dia</h3>
                <p style="font-size: 0.78rem; color: #888; margin: 3px 0 0;">Volume diário de cliques para a Shopee nos últimos 7 dias</p>
            </div>
            <span style="font-size: 0.72rem; color: #caa15c; font-weight: 600; background: rgba(202, 161, 92, 0.1); padding: 4px 10px; border-radius: 20px;">Última Semana</span>
        </div>

        <!-- Renderizador do Gráfico de Barras SVG -->
        <div style="width: 100%; height: 210px; display: flex; align-items: flex-end; justify-content: space-between; gap: 12px; padding-top: 10px; border-bottom: 1px solid rgba(255,255,255,0.08);">
            <?php foreach ($chartDays as $bar): ?>
                <?php 
                    $pct = max(10, min(100, round(($bar['clicks'] / $maxClicksInChart) * 100)));
                    $heightPx = round(($pct / 100) * 160);
                ?>
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 8px;" title="<?= $bar['date'] ?>: <?= $bar['clicks'] ?> cliques">
                    <span style="font-size: 0.75rem; color: #caa15c; font-weight: 700;"><?= $bar['clicks'] ?></span>
                    <div style="width: 100%; max-width: 44px; height: <?= $heightPx ?>px; background: linear-gradient(180deg, #caa15c 0%, rgba(202, 161, 92, 0.25) 100%); border-radius: 6px 6px 0 0; border: 1px solid rgba(202, 161, 92, 0.5); transition: all 0.3s ease; cursor: pointer;"
                         onmouseover="this.style.background='linear-gradient(180deg, #f0cf8e 0%, #caa15c 100%)'; this.style.boxShadow='0 0 15px rgba(202,161,92,0.6)';"
                         onmouseout="this.style.background='linear-gradient(180deg, #caa15c 0%, rgba(202, 161, 92, 0.25) 100%)'; this.style.boxShadow='none';"></div>
                    <span style="font-size: 0.72rem; color: #999; margin-top: 4px;"><?= $bar['label'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- DISTRIBUIÇÃO POR CATEGORIA -->
    <div class="admin-card" style="background: #14161a; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 24px;">
        <h3 class="card-title" style="margin: 0 0 4px; font-size: 1.15rem;">Cliques por Categoria</h3>
        <p style="font-size: 0.78rem; color: #888; margin: 0 0 20px;">Participação de interesse no catálogo</p>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            <?php foreach ($categoryClicks as $cat): ?>
                <?php 
                    $catPct = $totalClicks > 0 ? round(($cat['clicks_count'] / $totalClicks) * 100, 1) : 0;
                ?>
                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.82rem; margin-bottom: 6px;">
                        <span style="color: #fff; font-weight: 500;"><?= \App\Core\View::e($cat['category_name']) ?></span>
                        <span style="color: #caa15c; font-weight: 700;"><?= $cat['clicks_count'] ?> (<?= $catPct ?>%)</span>
                    </div>
                    <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.06); border-radius: 6px; overflow: hidden;">
                        <div style="width: <?= max(4, $catPct) ?>%; height: 100%; background: linear-gradient(90deg, #caa15c, #e0c080); border-radius: 6px;"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<!-- 3. TABELA DE RANKING: JOIAS MAIS DESEJADAS & PERFORMANCE -->
<div class="admin-card" style="background: #14161a; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 24px; margin-bottom: 28px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
        <div>
            <h3 class="card-title" style="margin: 0; font-size: 1.25rem;">Ranking de Joias Mais Clicadas</h3>
            <p style="font-size: 0.8rem; color: #888; margin: 3px 0 0;">Itens com maior atração de público e potencial de conversão na Shopee</p>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50px;">Posição</th>
                    <th style="width: 60px;">Foto</th>
                    <th>Joia / Mineral</th>
                    <th>Categoria</th>
                    <th>Preço de Venda</th>
                    <th>Cliques Shopee</th>
                    <th>Volume Potencial</th>
                    <th style="text-align: right;">Atratividade</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($topProducts)): ?>
                    <?php $pos = 1; foreach ($topProducts as $prod): ?>
                        <?php 
                            $volCents = $prod['clicks_count'] * (int) $prod['price_cents'];
                            $sharePct = $totalClicks > 0 ? round(($prod['clicks_count'] / $totalClicks) * 100, 1) : 0;
                        ?>
                        <tr>
                            <td>
                                <span style="font-weight: 700; color: <?= $pos <= 3 ? '#caa15c' : '#888' ?>; font-size: 0.95rem;">
                                    #<?= $pos++ ?>
                                </span>
                            </td>
                            <td>
                                <img src="<?= \App\Core\View::e($prod['image_url'] ?? '/assets/images/hero/featured-green-shell.jpg') ?>" 
                                     alt="<?= \App\Core\View::e($prod['name']) ?>" 
                                     class="table-thumb" 
                                     style="width: 44px; height: 44px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);"
                                     onerror="this.src='/assets/images/hero/featured-green-shell.jpg';">
                            </td>
                            <td>
                                <span style="font-weight: 600; color: #ffffff;"><?= \App\Core\View::e($prod['name']) ?></span>
                            </td>
                            <td>
                                <span style="font-size: 0.78rem; color: #b8b1a7;"><?= \App\Core\View::e($prod['category_name'] ?? 'Catálogo') ?></span>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: #caa15c;">R$ <?= number_format($prod['price_cents'] / 100, 2, ',', '.') ?></span>
                            </td>
                            <td>
                                <span style="font-size: 0.95rem; font-weight: 700; color: #ff6b4a; background: rgba(238, 77, 45, 0.12); padding: 4px 10px; border-radius: 8px; display: inline-flex; align-items: center; gap: 4px;">
                                    <?= $prod['clicks_count'] ?> cliques
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: #4ade80;">R$ <?= number_format($volCents / 100, 2, ',', '.') ?></span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; align-items: center; gap: 8px;">
                                    <div style="width: 60px; height: 6px; background: rgba(255,255,255,0.08); border-radius: 4px; overflow: hidden;">
                                        <div style="width: <?= min(100, $sharePct * 3) ?>%; height: 100%; background: #caa15c;"></div>
                                    </div>
                                    <span style="font-size: 0.75rem; color: #bbb;"><?= $sharePct ?>%</span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: #888; padding: 30px;">Nenhum dado de cliques registrado até o momento.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- 4. FEED RECENTE EM TEMPO REAL: ÚLTIMAS INTENÇÕES DE COMPRA -->
<div class="admin-card" style="background: #14161a; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div>
            <h3 class="card-title" style="margin: 0; font-size: 1.2rem; display: flex; align-items: center; gap: 8px;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #4ade80; box-shadow: 0 0 8px #4ade80; display: inline-block;"></span>
                Feed em Tempo Real de Cliques na Shopee
            </h3>
            <p style="font-size: 0.78rem; color: #888; margin: 3px 0 0;">Histórico dos últimos acessos que redirecionaram para a sua loja na Shopee</p>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 140px;">Data & Hora</th>
                    <th>Joia Clicada</th>
                    <th>Origem</th>
                    <th>Dispositivo Detectado</th>
                    <th>IP Anonimizado</th>
                    <th style="text-align: right;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recentClicks)): ?>
                    <?php foreach ($recentClicks as $click): ?>
                        <?php 
                            $isMobile = str_contains($click['user_agent'] ?? '', 'Mobile') || str_contains($click['user_agent'] ?? '', 'iPhone') || str_contains($click['user_agent'] ?? '', 'Android');
                            $deviceLabel = $isMobile ? '📱 Mobile' : '💻 Desktop';
                        ?>
                        <tr>
                            <td style="color: #9e978e; font-size: 0.78rem; font-family: monospace;">
                                <?= date('d/m/Y H:i', strtotime($click['created_at'])) ?>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: #ffffff;"><?= \App\Core\View::e($click['product_name']) ?></span>
                            </td>
                            <td>
                                <span style="font-size: 0.72rem; text-transform: uppercase; background: rgba(255,255,255,0.06); padding: 3px 8px; border-radius: 6px; color: #caa15c;">
                                    <?= \App\Core\View::e($click['source'] ?? 'loja') ?>
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.8rem; color: #b8b1a7;"><?= $deviceLabel ?></span>
                            </td>
                            <td style="color: #777; font-size: 0.78rem; font-family: monospace;">
                                <?= \App\Core\View::e($click['ip_address'] ?? '127.0.0.1') ?>
                            </td>
                            <td style="text-align: right;">
                                <span style="font-size: 0.72rem; color: #4ade80; background: rgba(74, 222, 128, 0.1); padding: 3px 8px; border-radius: 6px; font-weight: 600;">
                                    Redirecionado ↗
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #888; padding: 25px;">Nenhum clique recente.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- 5. CONFIGURAÇÕES EDITÁVEIS DE RASTREAMENTO & MARKETING -->
<div class="admin-card" style="border: 1px solid rgba(202, 161, 92, 0.3); padding: 24px; margin-top: 28px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 14px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h3 class="card-title" style="margin: 0; font-size: 1.25rem; display: flex; align-items: center; gap: 8px;">
                <span style="color: #caa15c;">⚙️</span>
                Configurações de Rastreamento, Pixels & Metas de Conversão
            </h3>
            <p style="font-size: 0.8rem; color: #888; margin: 3px 0 0;">
                Configure seus códigos de rastreamento do Facebook/Meta, Google Analytics, TikTok e estabeleça suas metas mensais.
            </p>
        </div>
        <button type="button" class="btn-admin-gold" onclick="document.getElementById('metricsConfigForm').requestSubmit()">
            <span>Salvar Configurações de Métricas</span>
        </button>
    </div>

    <form id="metricsConfigForm" onsubmit="handleMetricsConfigSubmit(event)">
        <input type="hidden" name="_csrf_token" value="<?= \App\Middlewares\CsrfMiddleware::getToken() ?>">

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-bottom: 18px;">
            <div class="form-group" style="margin: 0;">
                <label for="meta_pixel_id">Meta Pixel ID (Facebook & Instagram Ads)</label>
                <input type="text" id="meta_pixel_id" name="meta_pixel_id" class="form-input" 
                       value="<?= \App\Core\View::e($metricsConfig['meta_pixel_id'] ?? '') ?>" 
                       placeholder="Ex: 123456789012345">
                <span style="font-size: 0.72rem; color: #8e8880;">Identificador para otimização de anúncios de conversão.</span>
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="ga4_measurement_id">Google Analytics 4 (ID de Medição)</label>
                <input type="text" id="ga4_measurement_id" name="ga4_measurement_id" class="form-input" 
                       value="<?= \App\Core\View::e($metricsConfig['ga4_measurement_id'] ?? '') ?>" 
                       placeholder="Ex: G-XXXXXXXXXX">
                <span style="font-size: 0.72rem; color: #8e8880;">Rastreia fluxo e permanência de visitantes.</span>
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="gtm_container_id">Google Tag Manager (ID do Contêiner)</label>
                <input type="text" id="gtm_container_id" name="gtm_container_id" class="form-input" 
                       value="<?= \App\Core\View::e($metricsConfig['gtm_container_id'] ?? '') ?>" 
                       placeholder="Ex: GTM-XXXXXXX">
                <span style="font-size: 0.72rem; color: #8e8880;">Disparador central de tags de marketing.</span>
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="tiktok_pixel_id">TikTok Ads Pixel ID</label>
                <input type="text" id="tiktok_pixel_id" name="tiktok_pixel_id" class="form-input" 
                       value="<?= \App\Core\View::e($metricsConfig['tiktok_pixel_id'] ?? '') ?>" 
                       placeholder="Ex: CXXXXXXXXXXXXXX">
                <span style="font-size: 0.72rem; color: #8e8880;">Pixel para campanhas no TikTok.</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; border-top: 1px solid rgba(255, 255, 255, 0.06); padding-top: 18px;">
            <div class="form-group" style="margin: 0;">
                <label for="monthly_revenue_target">Meta de Demanda Mensal (R$)</label>
                <input type="number" id="monthly_revenue_target" name="monthly_revenue_target" class="form-input" 
                       value="<?= (int) ($metricsConfig['monthly_revenue_target'] ?? 50000) ?>" min="1000" step="500">
                <span style="font-size: 0.72rem; color: #8e8880;">Meta financeira de intenções de compra.</span>
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="monthly_clicks_target">Meta Mensal de Cliques Shopee</label>
                <input type="number" id="monthly_clicks_target" name="monthly_clicks_target" class="form-input" 
                       value="<?= (int) ($metricsConfig['monthly_clicks_target'] ?? 1000) ?>" min="50" step="50">
                <span style="font-size: 0.72rem; color: #8e8880;">Meta de redirecionamentos para a loja.</span>
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="default_utm_source">UTM Source Padrão</label>
                <input type="text" id="default_utm_source" name="default_utm_source" class="form-input" 
                       value="<?= \App\Core\View::e($metricsConfig['default_utm_source'] ?? 'divinium_site') ?>" 
                       placeholder="divinium_site">
            </div>

            <div class="form-group" style="margin: 0;">
                <label for="default_utm_campaign">UTM Campaign Padrão</label>
                <input type="text" id="default_utm_campaign" name="default_utm_campaign" class="form-input" 
                       value="<?= \App\Core\View::e($metricsConfig['default_utm_campaign'] ?? 'colecao_2026') ?>" 
                       placeholder="colecao_2026">
            </div>
        </div>
    </form>
</div>

<script>
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_csrf_token"]')?.value 
        || '';
}

async function handleMetricsConfigSubmit(e) {
    e.preventDefault();
    const form = document.getElementById('metricsConfigForm');
    const formData = new FormData(form);
    const csrfToken = getCsrfToken();
    if (csrfToken && !formData.has('_csrf_token')) {
        formData.append('_csrf_token', csrfToken);
    }

    try {
        const res = await fetch('/admin/metricas/salvar', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const rawText = await res.text();
        let data;
        try {
            data = JSON.parse(rawText);
        } catch (jsonErr) {
            console.error('Resposta não-JSON do servidor:', rawText);
            throw new Error('O servidor respondeu com formato inesperado. Se sua sessão expirou, recarregue a página.');
        }

        if (data.auth_required) {
            alert('Sua sessão administrativa expirou. Por favor, faça login novamente.');
            window.location.reload();
            return;
        }

        if (data.success) {
            alert(data.message || 'Configurações de métricas e rastreamento salvas com sucesso!');
            window.location.reload();
        } else {
            alert(data.error || 'Erro ao salvar configurações.');
        }
    } catch (err) {
        alert('Erro ao processar: ' + err.message);
    }
}
</script>
