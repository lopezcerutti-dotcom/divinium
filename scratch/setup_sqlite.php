<?php
require_once __DIR__ . '/../app/bootstrap.php';

$sqlitePath = BASE_PATH . '/database/divinium_local.sqlite';
$pdo = new PDO('sqlite:' . $sqlitePath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    uuid TEXT UNIQUE,
    role_id INTEGER DEFAULT 1,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    status TEXT DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    uuid TEXT UNIQUE,
    name TEXT NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    description TEXT,
    sort_order INTEGER DEFAULT 0,
    is_active INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    uuid TEXT UNIQUE,
    category_id INTEGER,
    name TEXT NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    sku TEXT UNIQUE NOT NULL,
    short_description TEXT,
    description TEXT,
    price_cents INTEGER NOT NULL,
    promotional_price_cents INTEGER,
    stock_quantity INTEGER DEFAULT 10,
    is_featured INTEGER DEFAULT 0,
    is_active INTEGER DEFAULT 1,
    image_url TEXT,
    stone_mineral TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    key_name TEXT UNIQUE NOT NULL,
    value TEXT,
    description TEXT
);
");

// Inserir Admin inicial padrão
$adminHash = password_hash('admin123', PASSWORD_BCRYPT);
$stmt = $pdo->prepare("INSERT OR IGNORE INTO users (id, uuid, role_id, name, email, password_hash, status) VALUES (1, 'admin-uuid-001', 1, 'Administrador Divinium', 'admin@divinium.com.br', ?, 'active')");
$stmt->execute([$adminHash]);

// Inserir categorias padrão
$pdo->exec("
INSERT OR IGNORE INTO categories (id, uuid, name, slug, description, sort_order, is_active) VALUES
(1, 'cat-001', 'Braceletes & Pulseiras', 'braceletes-pulseiras', 'Braceletes anatômicos forjados em prata de lei com pedras minerais', 1, 1),
(2, 'cat-002', 'Colares & Pingentes', 'colares-pingentes', 'Correntes nobres e relicários exclusivos', 2, 1),
(3, 'cat-003', 'Anéis Autoriais', 'aneis-autorais', 'Solitários e anéis de presença com gemas raras', 3, 1),
(4, 'cat-004', 'Brincos & Argolas', 'brincos-argolas', 'Design orgânico e elegância sutil', 4, 1);
");

// Inserir produtos padrão da Coleção Caminhos
$pdo->exec("
INSERT OR IGNORE INTO products (id, uuid, category_id, name, slug, sku, short_description, description, price_cents, promotional_price_cents, stock_quantity, is_featured, is_active, image_url, stone_mineral) VALUES
(1, 'prod-001', 1, 'Bracelete Caminhos • Quartzo Verde', 'bracelete-caminhos-quartzo-verde', 'CAM-VERDE-01', 'Prata nobre 925 e quartzo verde natural brasileiro em concha mineral.', 'Forjado artesanalmente com aro anatômico ajustável e gema mineral pura.', 38000, 36100, 15, 1, 1, '/assets/images/hero/featured-green-shell.jpg', 'Quartzo Verde'),
(2, 'prod-002', 1, 'Bracelete Caminhos • Madrepérola', 'bracelete-caminhos-madreperola', 'CAM-MADRE-02', 'Prata nobre 925 e nácar natural de madrepérola com brilho perolado.', 'Elegância minimalista e textura orgânica única em cada exemplar.', 38000, 36100, 12, 1, 1, '/assets/images/products/bracelete-madreperola.jpg', 'Madrepérola'),
(3, 'prod-003', 1, 'Bracelete Caminhos • Jaspe Vermelho', 'bracelete-caminhos-jaspe-vermelho', 'CAM-JASP-03', 'Prata nobre 925 e jaspe vermelho mineral de presença intensa e terrosa.', 'Vibração mineral forte com acabamento espelhado em prata de lei.', 38000, 36100, 8, 1, 1, '/assets/images/products/bracelete-vermelho.jpg', 'Jaspe Vermelho'),
(4, 'prod-004', 1, 'Bracelete Caminhos • Ônix Negro', 'bracelete-caminhos-onix-negro', 'CAM-ONIX-04', 'Prata nobre 925 e ônix negro de acabamento mineral espelhado profundo.', 'O ápice do Quiet Luxury: sobriedade, atemporalidade e nobreza.', 38000, 36100, 10, 1, 1, '/assets/images/products/bracelete-onix.jpg', 'Ônix Negro');
");

echo "SQLITE_SETUP_SUCCESS\n";
