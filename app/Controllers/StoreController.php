<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\View;

class StoreController
{
    /**
     * Página da Loja / Vitrine de Joias com links diretos para a Shopee
     */
    public function index(): void
    {
        $pdo = Database::getConnection();

        // Buscar categorias ativas
        $categoriesStmt = $pdo->query('SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order ASC, name ASC');
        $categories = $categoriesStmt->fetchAll();

        // Buscar produtos ativos com sua categoria
        $productsStmt = $pdo->query('
            SELECT p.*, c.name as category_name, c.slug as category_slug 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.is_active = 1 
            ORDER BY p.is_featured DESC, p.id DESC
        ');
        $products = $productsStmt->fetchAll();

        View::render('store/index', [
            'pageTitle' => 'Loja Oficial | Joias & Coleções Autorais',
            'pageDescription' => 'Conheça o catálogo completo de joias Divinium. Escolha sua gema mineral e finalize sua compra com segurança na nossa loja oficial na Shopee.',
            'categories' => $categories,
            'products' => $products
        ]);
    }
}
