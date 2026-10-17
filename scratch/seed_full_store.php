<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$pdo = \App\Core\Database::getConnection();

echo "1. Criando tabelas de Analytics e Verificando Estrutura...\n";

// Criar tabelas de analytics caso não existam
$pdo->exec("
CREATE TABLE IF NOT EXISTS product_clicks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL,
    product_name TEXT NOT NULL,
    source TEXT DEFAULT 'store',
    ip_address TEXT DEFAULT '127.0.0.1',
    user_agent TEXT DEFAULT 'Mozilla/5.0',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS page_views (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    page_name TEXT NOT NULL,
    ip_address TEXT DEFAULT '127.0.0.1',
    user_agent TEXT DEFAULT 'Mozilla/5.0',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
");

// 2. Inserir ou atualizar Categorias
echo "2. Atualizando Categorias...\n";
$categories = [
    [
        'id' => 1,
        'uuid' => 'cat-braceletes-01',
        'name' => 'Pulseiras & Braceletes',
        'slug' => 'braceletes',
        'description' => 'Braceletes rígidos e pulseiras lapidadas à mão em Prata 925, Ouro 18k e pedras naturais com a elegância do quiet luxury.',
        'sort_order' => 1,
        'is_active' => 1
    ],
    [
        'id' => 2,
        'uuid' => 'cat-aneis-02',
        'name' => 'Anéis & Solitários',
        'slug' => 'aneis',
        'description' => 'Solitários de alta joalheria, alianças eternas e anéis de ouro 18k cravados com diamantes e gemas autênticas.',
        'sort_order' => 2,
        'is_active' => 1
    ],
    [
        'id' => 3,
        'uuid' => 'cat-colares-03',
        'name' => 'Colares & Chokers',
        'slug' => 'colares',
        'description' => 'Colares riviera de diamantes, chokers delicadas e correntes com pingentes de design atemporal.',
        'sort_order' => 3,
        'is_active' => 1
    ],
    [
        'id' => 4,
        'uuid' => 'cat-brincos-04',
        'name' => 'Brincos & Alta Joalheria',
        'slug' => 'brincos',
        'description' => 'Gotas preciosas em esmeralda, brincos ponto de luz e ear cuffs que iluminam a fisionomia com sutileza.',
        'sort_order' => 4,
        'is_active' => 1
    ]
];

$catStmt = $pdo->prepare("
    INSERT INTO categories (id, uuid, name, slug, description, sort_order, is_active)
    VALUES (?, ?, ?, ?, ?, ?, ?)
    ON CONFLICT(id) DO UPDATE SET
        name = excluded.name,
        slug = excluded.slug,
        description = excluded.description,
        sort_order = excluded.sort_order,
        is_active = excluded.is_active
");

foreach ($categories as $cat) {
    $catStmt->execute([
        $cat['id'],
        $cat['uuid'],
        $cat['name'],
        $cat['slug'],
        $cat['description'],
        $cat['sort_order'],
        $cat['is_active']
    ]);
}

// 3. Inserir Coleção Completa de Produtos
echo "3. Populando Catálogo de Produtos...\n";

$products = [
    [
        'id' => 1,
        'uuid' => 'prod-caminhos-verde',
        'category_id' => 1,
        'name' => 'Bracelete Caminhos • Quartzo Verde',
        'slug' => 'bracelete-caminhos-quartzo-verde',
        'sku' => 'DIV-BRAC-QVERDE',
        'stone_mineral' => 'Quartzo Verde Natural',
        'price_cents' => 38000,
        'promotional_price_cents' => 36100,
        'stock_quantity' => 14,
        'short_description' => 'Prata de Lei 925 maciça com Quartzo Verde lapidado em corte navete facetado. Simboliza vigor, vitalidade e equilíbrio.',
        'description' => 'O Bracelete Caminhos em Quartzo Verde une o magnetismo da Prata 925 com a pureza translúcida da gema verde. Desenvolvido sob os princípios do Quiet Luxury — sem logos aparentes, valorizando a nobreza da matéria-prima e o acabamento espelhado artesanal.',
        'image_url' => '/assets/images/products/bracelete-verde.jpg',
        'external_url' => 'https://shopee.com.br/search?keyword=bracelete%20caminhos%20quartzo%20verde',
        'is_active' => 1,
        'is_featured' => 1
    ],
    [
        'id' => 2,
        'uuid' => 'prod-caminhos-madreperola',
        'category_id' => 1,
        'name' => 'Bracelete Caminhos • Madrepérola',
        'slug' => 'bracelete-caminhos-madreperola',
        'sku' => 'DIV-BRAC-MADRE',
        'stone_mineral' => 'Madrepérola Australiana',
        'price_cents' => 38000,
        'promotional_price_cents' => 36100,
        'stock_quantity' => 11,
        'short_description' => 'Prata 925 com incrustação de madrepérola iridescente. Um brilho suave que reflete diferentes tons de marfim e champanhe.',
        'description' => 'A suavidade orgânica da madrepérola esculpida com precisão milimétrica. Uma joia versátil que transita com naturalidade entre uma reunião de negócios e um jantar sofisticado.',
        'image_url' => '/assets/images/products/bracelete-madreperola.jpg',
        'external_url' => 'https://shopee.com.br/search?keyword=bracelete%20caminhos%20madreperola',
        'is_active' => 1,
        'is_featured' => 1
    ],
    [
        'id' => 3,
        'uuid' => 'prod-caminhos-jaspe',
        'category_id' => 1,
        'name' => 'Bracelete Caminhos • Jaspe Vermelho',
        'slug' => 'bracelete-caminhos-jaspe-vermelho',
        'sku' => 'DIV-BRAC-JASPE',
        'stone_mineral' => 'Jaspe Vermelho Imperial',
        'price_cents' => 38000,
        'promotional_price_cents' => 36100,
        'stock_quantity' => 8,
        'short_description' => 'Prata 925 e Jaspe Vermelho terroso nobre. Um tom terracota intenso para mulheres que comandam seu espaço com presença marcante.',
        'description' => 'A força telúrica do Jaspe Vermelho lapidado à perfeição. Um toque de cor sóbrio e magnético que enriquece qualquer look com distinção autêntica.',
        'image_url' => '/assets/images/products/bracelete-vermelho.jpg',
        'external_url' => 'https://shopee.com.br/search?keyword=bracelete%20caminhos%20jaspe%20vermelho',
        'is_active' => 1,
        'is_featured' => 1
    ],
    [
        'id' => 4,
        'uuid' => 'prod-caminhos-onix',
        'category_id' => 1,
        'name' => 'Bracelete Caminhos • Ônix Negro',
        'slug' => 'bracelete-caminhos-onix-negro',
        'sku' => 'DIV-BRAC-ONIX',
        'stone_mineral' => 'Ônix Vulcânico Profundo',
        'price_cents' => 38000,
        'promotional_price_cents' => 36100,
        'stock_quantity' => 19,
        'short_description' => 'Prata 925 com Ônix negro absoluto espelhado. O clássico definitivo do contraste noir e prata polida.',
        'description' => 'Sóbrio, enigmático e imponente. O Ônix Negro polido à mão reflete a luz com elegância inconfundível. Perfeito para uso solo ou combinando em composições sofisticadas.',
        'image_url' => '/assets/images/products/bracelete-onix.jpg',
        'external_url' => 'https://shopee.com.br/search?keyword=bracelete%20caminhos%20onix%20negro',
        'is_active' => 1,
        'is_featured' => 1
    ],
    [
        'id' => 5,
        'uuid' => 'prod-anel-solitario-etoile',
        'category_id' => 2,
        'name' => 'Anel Solitário Étoile • Diamante 3.2ct Ouro 18k',
        'slug' => 'anel-solitario-etoile-diamante-ouro-18k',
        'sku' => 'DIV-ANEL-ETOILE',
        'stone_mineral' => 'Diamante Natural Lapidação Brilhante',
        'price_cents' => 1290000,
        'promotional_price_cents' => 1225500,
        'stock_quantity' => 5,
        'short_description' => 'Ouro Amarelo 18k com diamante central lapidação brilhante de 3.20 quilates. Cravação em 4 garras finas de segurança máxima.',
        'description' => 'O ícone supremo do luxo silencioso. Forjado em Ouro Maciço 18k com acabamento polido espelhado, o Anel Étoile eleva o clássico solitário ao seu ápice de pureza e brilho hipnotizante.',
        'image_url' => '/assets/images/products/anel-solitario-diamante.jpg',
        'external_url' => 'https://shopee.com.br/search?keyword=anel%20solitario%20ouro%2018k%20diamante',
        'is_active' => 1,
        'is_featured' => 1
    ],
    [
        'id' => 6,
        'uuid' => 'prod-colar-riviera-imperiale',
        'category_id' => 3,
        'name' => 'Colar Riviera Impériale • Diamantes e Ouro Branco',
        'slug' => 'colar-riviera-imperiale-diamantes-ouro-branco',
        'sku' => 'DIV-COLAR-RIVIERA',
        'stone_mineral' => 'Diamantes Pave Graduados',
        'price_cents' => 1850000,
        'promotional_price_cents' => 1757500,
        'stock_quantity' => 3,
        'short_description' => 'Colar Riviera completo em Ouro Branco 18k e Platina com diamantes calibrados de pureza VVS1 ao redor de toda a extensão.',
        'description' => 'Um rio contínuo de luz no colo. A fluidez da estrutura articulada garante caimento anatômico perfeito que não vira na pele, expressando requinte inigualável.',
        'image_url' => '/assets/images/products/colar-riviera-diamantes.jpg',
        'external_url' => 'https://shopee.com.br/search?keyword=colar%20riviera%20diamantes%20ouro%20branco',
        'is_active' => 1,
        'is_featured' => 1
    ],
    [
        'id' => 7,
        'uuid' => 'prod-brinco-esmeralda-majestade',
        'category_id' => 4,
        'name' => 'Brincos Gota Majestade • Esmeralda Colombiana Octogonal',
        'slug' => 'brincos-gota-esmeralda-colombiana-ouro-18k',
        'sku' => 'DIV-BRIN-ESMERALDA',
        'stone_mineral' => 'Esmeralda Colombiana & Diamantes',
        'price_cents' => 940000,
        'promotional_price_cents' => 893000,
        'stock_quantity' => 4,
        'short_description' => 'Esmeraldas Colombianas autênticas lapidação octogonal contornadas por halo de diamantes em Ouro Amarelo 18k.',
        'description' => 'O verde profundo mais cobiçado da joalheria mundial. Cada esmeralda é selecionada manualmente para garantir saturação homogênea e vivacidade verde-floresta impecável.',
        'image_url' => '/assets/images/products/brinco-esmeralda-colombiana.jpg',
        'external_url' => 'https://shopee.com.br/search?keyword=brincos%20esmeralda%20colombiana%20ouro%2018k',
        'is_active' => 1,
        'is_featured' => 1
    ],
    [
        'id' => 8,
        'uuid' => 'prod-bracelete-rosegold-eternite',
        'category_id' => 1,
        'name' => 'Bracelete Rígido Éternité • Rose Gold 18k & Diamantes',
        'slug' => 'bracelete-rigido-rosegold-diamantes',
        'sku' => 'DIV-BRAC-ROSE',
        'stone_mineral' => 'Diamantes Lapidação Francesa',
        'price_cents' => 489000,
        'promotional_price_cents' => 464550,
        'stock_quantity' => 7,
        'short_description' => 'Ouro Rosa Maciço 18k com fecho oculto de pressão e fileira de diamantes pavê. Toque aveludado e brilho caloroso.',
        'description' => 'A tonalidade rosé champanhe traz delicadeza contemporânea e contraste sedutor sobre a pele. Design tubular ergonômico feito para ser usado todos os dias sem restrições.',
        'image_url' => '/assets/images/products/bracelete-rose-gold.jpg',
        'external_url' => 'https://shopee.com.br/search?keyword=bracelete%20ouro%20rosa%2018k%20diamantes',
        'is_active' => 1,
        'is_featured' => 1
    ],
    [
        'id' => 9,
        'uuid' => 'prod-colecao-caminhos-box',
        'category_id' => 1,
        'name' => 'Coleção Caminhos Completa • 4 Minerais na Caixa de Luxo',
        'slug' => 'colecao-caminhos-completa-4-minerais',
        'sku' => 'DIV-BOX-CAMINHOS',
        'stone_mineral' => 'Quartzo Verde, Madrepérola, Jaspe e Ônix',
        'price_cents' => 139000,
        'promotional_price_cents' => 132050,
        'stock_quantity' => 10,
        'short_description' => 'Os quatro braceletes icônicos da Coleção Caminhos em estojo de veludo noir com certificado de autenticidade numerado.',
        'description' => 'O presente supremo de alta joalheria acessível. Adquira os 4 braceletes (Quartzo, Madrepérola, Jaspe e Ônix) com desconto especial de conjunto e embalagem comemorativa premium.',
        'image_url' => '/assets/images/products/colecao-completa-espelho.jpg',
        'external_url' => 'https://shopee.com.br/search?keyword=colecao%20caminhos%20divinium',
        'is_active' => 1,
        'is_featured' => 1
    ],
    [
        'id' => 10,
        'uuid' => 'prod-choker-aura-madreperola',
        'category_id' => 3,
        'name' => 'Choker Gargantilha Sintonia • Madrepérola Pura',
        'slug' => 'choker-gargantilha-sintonia-madreperola',
        'sku' => 'DIV-CHOK-SINTONIA',
        'stone_mineral' => 'Madrepérola Natural Lapidada',
        'price_cents' => 68000,
        'promotional_price_cents' => 64600,
        'stock_quantity' => 12,
        'short_description' => 'Fita rígida em Prata 925 com pingente oval de madrepérola natural. Extensão ajustável com fecho mosquetão reforçado.',
        'description' => 'Linhas puras que emolduram a clavícula com magnetismo minimalista. Desenvolvida para iluminar vestidos decotados ou compor visuais modernos sob camisas de seda.',
        'image_url' => '/assets/images/about/banner-sintonia.jpg',
        'external_url' => 'https://shopee.com.br/search?keyword=choker%20madreperola%20prata%20925',
        'is_active' => 1,
        'is_featured' => 0
    ],
    [
        'id' => 11,
        'uuid' => 'prod-anel-unica-geometrie',
        'category_id' => 2,
        'name' => 'Anel Contemporâneo Única • Prata 925 Geometria Pura',
        'slug' => 'anel-contemporaneo-unica-prata-925',
        'sku' => 'DIV-ANEL-UNICA',
        'stone_mineral' => 'Prata 925 Maciça Espelhada',
        'price_cents' => 45000,
        'promotional_price_cents' => 42750,
        'stock_quantity' => 15,
        'short_description' => 'Escultura moderna para as mãos. Acabamento polido à mão com anatomia interna arredondada para máximo conforto.',
        'description' => 'O peso substancial da prata nobre combinado a linhas arquitetônicas puras. Uma declaração de sofisticação autoconfiante sem recorrer a logomarcas.',
        'image_url' => '/assets/images/about/banner-unica.jpg',
        'external_url' => 'https://shopee.com.br/search?keyword=anel%20prata%20925%20macica%20divinium',
        'is_active' => 1,
        'is_featured' => 0
    ],
    [
        'id' => 12,
        'uuid' => 'prod-brinco-earcuff-infinito',
        'category_id' => 4,
        'name' => 'Ear Cuff Lumina • Diamantes em Ouro Branco',
        'slug' => 'ear-cuff-lumina-diamantes-ouro-branco',
        'sku' => 'DIV-EAR-LUMINA',
        'stone_mineral' => 'Zircônias Premium e Ouro Branco',
        'price_cents' => 59000,
        'promotional_price_cents' => 56050,
        'stock_quantity' => 17,
        'short_description' => 'Ear cuff de pressão anatômica que acompanha a curva da orelha sem necessidade de múltiplos furos.',
        'description' => 'Praticidade aliada à audácia refinada. Adere firmemente com pressão suave almofadada, oferecendo um efeito visual de múltiplos piercings em uma única joia.',
        'image_url' => '/assets/images/about/model-wrist-sunglasses.jpg',
        'external_url' => 'https://shopee.com.br/search?keyword=ear%20cuff%20diamantes%20divinium',
        'is_active' => 1,
        'is_featured' => 0
    ]
];

$prodStmt = $pdo->prepare("
    INSERT INTO products (
        id, uuid, category_id, name, slug, sku, stone_mineral,
        price_cents, promotional_price_cents, stock_quantity,
        short_description, description, image_url, external_url,
        is_active, is_featured
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ON CONFLICT(id) DO UPDATE SET
        category_id = excluded.category_id,
        name = excluded.name,
        slug = excluded.slug,
        sku = excluded.sku,
        stone_mineral = excluded.stone_mineral,
        price_cents = excluded.price_cents,
        promotional_price_cents = excluded.promotional_price_cents,
        stock_quantity = excluded.stock_quantity,
        short_description = excluded.short_description,
        description = excluded.description,
        image_url = excluded.image_url,
        external_url = excluded.external_url,
        is_active = excluded.is_active,
        is_featured = excluded.is_featured
");

foreach ($products as $p) {
    $prodStmt->execute([
        $p['id'],
        $p['uuid'],
        $p['category_id'],
        $p['name'],
        $p['slug'],
        $p['sku'],
        $p['stone_mineral'],
        $p['price_cents'],
        $p['promotional_price_cents'],
        $p['stock_quantity'],
        $p['short_description'],
        $p['description'],
        $p['image_url'],
        $p['external_url'],
        $p['is_active'],
        $p['is_featured']
    ]);
}

// 4. Inserir Dados Realistas de Cliques e Visitas para o Dashboard de Acompanhamento
echo "4. Gerando Dados de Analytics para o Dashboard de Acompanhamento...\n";

// Limpar cliques mock anteriores para ter dados frescos e coerentes
$pdo->exec("DELETE FROM product_clicks");
$pdo->exec("DELETE FROM page_views");

$clickStmt = $pdo->prepare("
    INSERT INTO product_clicks (product_id, product_name, source, ip_address, user_agent, created_at)
    VALUES (?, ?, ?, ?, ?, ?)
");

$viewStmt = $pdo->prepare("
    INSERT INTO page_views (page_name, ip_address, user_agent, created_at)
    VALUES (?, ?, ?, ?)
");

$sources = ['loja', 'landing_page', 'hero_modal', 'menu_destaque'];
$devices = [
    'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15 Mobile/15E148 Safari/604.1',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 Chrome/124.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/123.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Linux; Android 14; SM-S918B) AppleWebKit/537.36 Chrome/122.0.0.0 Mobile Safari/537.36'
];

$productClickWeights = [
    1 => 42, // Quartzo Verde
    5 => 38, // Anel Solitário
    6 => 31, // Colar Riviera
    2 => 27, // Madrepérola
    7 => 24, // Brinco Esmeralda
    8 => 19, // Rose Gold
    4 => 18, // Ônix
    9 => 16, // Caixa Coleção
    3 => 14, // Jaspe
    10 => 9,
    11 => 8,
    12 => 7,
];

// Gerar dados distribuídos nos últimos 7 dias
$baseTime = time();

foreach ($productClickWeights as $pId => $clickCount) {
    $pName = '';
    foreach ($products as $pr) {
        if ($pr['id'] === $pId) {
            $pName = $pr['name'];
            break;
        }
    }

    for ($i = 0; $i < $clickCount; $i++) {
        $daysAgo = rand(0, 6);
        $secondsAgo = ($daysAgo * 86400) + rand(120, 80000);
        $dateStr = date('Y-m-d H:i:s', $baseTime - $secondsAgo);
        $src = $sources[array_rand($sources)];
        $ip = '177.' . rand(10, 200) . '.' . rand(1, 250) . '.' . rand(1, 250);
        $ua = $devices[array_rand($devices)];

        $clickStmt->execute([$pId, $pName, $src, $ip, $ua, $dateStr]);
    }
}

// Gerar visualizações de página
for ($d = 6; $d >= 0; $d--) {
    $viewsDay = rand(45, 95);
    for ($v = 0; $v < $viewsDay; $v++) {
        $sec = ($d * 86400) + rand(60, 86000);
        $dateStr = date('Y-m-d H:i:s', $baseTime - $sec);
        $page = (rand(1, 10) > 4) ? 'loja' : 'home';
        $ip = '177.' . rand(10, 200) . '.' . rand(1, 250) . '.' . rand(1, 250);
        $ua = $devices[array_rand($devices)];

        $viewStmt->execute([$page, $ip, $ua, $dateStr]);
    }
}

// 5. Configurar Tema Padrão Inicial
echo "5. Configurando Tema Padrão...\n";
\App\Models\Setting::set('default_theme', 'theme-gold', 'Tema Visual Padrão da Loja');

echo "CARGA COMPLETA REALIZADA COM SUCESSO!\n";
