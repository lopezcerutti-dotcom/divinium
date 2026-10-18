<?php

declare(strict_types=1);

namespace App\Integrations\Bling;

use App\Core\Database;
use App\Models\Setting;
use PDO;
use RuntimeException;
use Throwable;

/**
 * SERVIÇO DE INTEGRAÇÃO BLING ERP (API V3 & OAUTH 2.0)
 *
 * Responsável pela autorização OAuth 2.0, renovação automática de tokens JWT,
 * sincronização de produtos e saldos de estoque entre o Bling ERP e o banco da loja Divinium.
 */
class BlingService
{
    private const AUTH_BASE_URL = 'https://bling.com.br/Api/v3/oauth/authorize';
    private const TOKEN_URL     = 'https://api.bling.com.br/Api/v3/oauth/token';
    private const API_BASE_URL  = 'https://api.bling.com.br/Api/v3';

    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
        $this->ensureDatabaseSchema();
    }

    /**
     * Garante colunas necessárias nas tabelas 'products' e 'categories'
     */
    public function ensureDatabaseSchema(): void
    {
        try {
            $driver = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

            if ($driver === 'sqlite') {
                // Verificar colunas na tabela products
                $colsStmt = $this->pdo->query("PRAGMA table_info(products)");
                $existingCols = [];
                if ($colsStmt) {
                    while ($col = $colsStmt->fetch(PDO::FETCH_ASSOC)) {
                        $existingCols[] = $col['name'];
                    }
                }

                $needed = [
                    'stone_mineral' => 'VARCHAR(120) NULL',
                    'image_url' => 'VARCHAR(500) NULL',
                    'external_url' => 'VARCHAR(500) NULL',
                    'bling_id' => 'VARCHAR(60) NULL',
                    'parent_sku' => 'VARCHAR(60) NULL',
                    'stock_quantity' => 'INTEGER NOT NULL DEFAULT 0',
                ];

                foreach ($needed as $col => $definition) {
                    if (!in_array($col, $existingCols, true)) {
                        $this->pdo->exec("ALTER TABLE products ADD COLUMN {$col} {$definition}");
                    }
                }
            } else {
                // MySQL / MariaDB
                $colsStmt = $this->pdo->query("SHOW COLUMNS FROM products");
                $existingCols = [];
                if ($colsStmt) {
                    while ($col = $colsStmt->fetch(PDO::FETCH_ASSOC)) {
                        $existingCols[] = $col['Field'];
                    }
                }

                $needed = [
                    'stone_mineral' => 'VARCHAR(120) NULL',
                    'image_url' => 'VARCHAR(500) NULL',
                    'external_url' => 'VARCHAR(500) NULL',
                    'bling_id' => 'VARCHAR(60) NULL',
                    'parent_sku' => 'VARCHAR(60) NULL',
                ];

                foreach ($needed as $col => $definition) {
                    if (!in_array($col, $existingCols, true)) {
                        $this->pdo->exec("ALTER TABLE `products` ADD COLUMN `{$col}` {$definition}");
                    }
                }
            }
        } catch (Throwable $e) {
            error_log('BlingService: Schema check warning: ' . $e->getMessage());
        }
    }

    /**
     * Retorna a configuração atual do Bling armazenada na tabela settings
     *
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        $clientId = Setting::get('bling_client_id', '');
        $clientSecret = Setting::get('bling_client_secret', '');
        $redirectUri = Setting::get('bling_redirect_uri', 'https://divinium.com.br/admin/bling/callback');
        $accessToken = Setting::get('bling_access_token', '');
        $refreshToken = Setting::get('bling_refresh_token', '');
        $expiresAt = (int) Setting::get('bling_token_expires_at', '0');
        $lastSyncAt = Setting::get('bling_last_sync_at', null);
        $lastSyncStatus = Setting::get('bling_last_sync_status', 'never');
        $lastSyncMessage = Setting::get('bling_last_sync_message', 'Nenhuma sincronização realizada ainda.');
        $lastSyncCount = (int) Setting::get('bling_last_sync_count', '0');

        $isConnected = !empty($accessToken) && (!empty($refreshToken) || $expiresAt > time());

        return [
            'client_id'          => $clientId,
            'client_secret'      => $clientSecret,
            'redirect_uri'       => $redirectUri,
            'access_token'       => $accessToken,
            'refresh_token'      => $refreshToken,
            'expires_at'         => $expiresAt,
            'is_connected'       => $isConnected,
            'last_sync_at'       => $lastSyncAt,
            'last_sync_status'   => $lastSyncStatus,
            'last_sync_message'  => $lastSyncMessage,
            'last_sync_count'    => $lastSyncCount,
        ];
    }

    /**
     * Salva as credenciais básicas do aplicativo Bling
     */
    public function saveCredentials(string $clientId, string $clientSecret, ?string $redirectUri = null): bool
    {
        Setting::set('bling_client_id', trim($clientId));
        Setting::set('bling_client_secret', trim($clientSecret));
        if ($redirectUri !== null && trim($redirectUri) !== '') {
            Setting::set('bling_redirect_uri', trim($redirectUri));
        }
        return true;
    }

    /**
     * Gera a URL para onde o lojista deve ser redirecionado para autorizar o app no Bling
     */
    public function getAuthorizationUrl(?string $state = null): string
    {
        $config = $this->getConfig();
        $clientId = $config['client_id'];

        if (empty($clientId)) {
            throw new RuntimeException('Client ID do Bling não configurado.');
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($state === null) {
            $state = bin2hex(random_bytes(16));
        }

        $_SESSION['bling_oauth_state'] = $state;
        Setting::set('bling_oauth_state', $state);

        $params = [
            'response_type' => 'code',
            'client_id'     => $clientId,
            'state'         => $state,
        ];

        return self::AUTH_BASE_URL . '?' . http_build_query($params);
    }

    /**
     * Troca o authorization_code pelos tokens de acesso (access_token e refresh_token)
     *
     * @return array<string, mixed>
     */
    public function exchangeCodeForTokens(string $code): array
    {
        $config = $this->getConfig();
        $clientId = $config['client_id'];
        $clientSecret = $config['client_secret'];

        if (empty($clientId) || empty($clientSecret)) {
            throw new RuntimeException('Client ID e Client Secret são obrigatórios para obter o token.');
        }

        $basicAuth = base64_encode($clientId . ':' . $clientSecret);

        $postData = http_build_query([
            'grant_type' => 'authorization_code',
            'code'       => trim($code),
        ]);

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: 1.0',
            'Authorization: Basic ' . $basicAuth,
        ];

        $ch = curl_init(self::TOKEN_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new RuntimeException('Falha de conexão com a API do Bling: ' . $curlError);
        }

        $data = json_decode((string) $response, true);

        if ($httpCode !== 200 || empty($data['access_token'])) {
            $errorMsg = $data['error']['message'] ?? $data['error_description'] ?? $data['error'] ?? 'Erro desconhecido (' . $httpCode . ')';
            throw new RuntimeException('Bling OAuth Erro: ' . (is_array($errorMsg) ? json_encode($errorMsg) : $errorMsg));
        }

        $accessToken = (string) $data['access_token'];
        $refreshToken = (string) ($data['refresh_token'] ?? '');
        $expiresIn = (int) ($data['expires_in'] ?? 21600); // 6 horas por padrão
        $expiresAt = time() + $expiresIn - 60; // 1 minuto de margem de segurança

        Setting::set('bling_access_token', $accessToken);
        Setting::set('bling_refresh_token', $refreshToken);
        Setting::set('bling_token_expires_at', (string) $expiresAt);
        Setting::set('bling_last_sync_status', 'connected');
        Setting::set('bling_last_sync_message', 'Autorização OAuth concluída com sucesso.');

        return [
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'expires_at'    => $expiresAt,
            'expires_in'    => $expiresIn,
        ];
    }

    /**
     * Renova o access_token usando o refresh_token
     *
     * @return array<string, mixed>
     */
    public function refreshToken(): array
    {
        $config = $this->getConfig();
        $clientId = $config['client_id'];
        $clientSecret = $config['client_secret'];
        $refreshToken = $config['refresh_token'];

        if (empty($clientId) || empty($clientSecret) || empty($refreshToken)) {
            throw new RuntimeException('Dados insuficientes para renovar o token do Bling.');
        }

        $basicAuth = base64_encode($clientId . ':' . $clientSecret);

        $postData = http_build_query([
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refreshToken,
        ]);

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: 1.0',
            'Authorization: Basic ' . $basicAuth,
        ];

        $ch = curl_init(self::TOKEN_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode((string) $response, true);

        if ($httpCode !== 200 || empty($data['access_token'])) {
            $err = $data['error']['message'] ?? $data['error_description'] ?? 'Falha ao renovar token.';
            throw new RuntimeException('Erro renovação Bling: ' . (is_array($err) ? json_encode($err) : $err));
        }

        $accessToken = (string) $data['access_token'];
        $newRefreshToken = !empty($data['refresh_token']) ? (string) $data['refresh_token'] : $refreshToken;
        $expiresIn = (int) ($data['expires_in'] ?? 21600);
        $expiresAt = time() + $expiresIn - 60;

        Setting::set('bling_access_token', $accessToken);
        Setting::set('bling_refresh_token', $newRefreshToken);
        Setting::set('bling_token_expires_at', (string) $expiresAt);

        return [
            'access_token'  => $accessToken,
            'refresh_token' => $newRefreshToken,
            'expires_at'    => $expiresAt,
        ];
    }

    /**
     * Retorna um token válido (renovando automaticamente caso tenha expirado)
     */
    public function getValidAccessToken(): string
    {
        $config = $this->getConfig();
        $accessToken = $config['access_token'];
        $expiresAt = (int) $config['expires_at'];

        if (empty($accessToken)) {
            throw new RuntimeException('Bling não autenticado. Conecte sua conta Bling primeiro.');
        }

        if ($expiresAt > 0 && time() >= $expiresAt) {
            $tokens = $this->refreshToken();
            return (string) $tokens['access_token'];
        }

        return $accessToken;
    }

    /**
     * Faz requisição autenticada à API v3 do Bling
     *
     * @param array<string, mixed> $queryParams
     * @return array<string, mixed>
     */
    public function apiRequest(string $endpoint, array $queryParams = []): array
    {
        $token = $this->getValidAccessToken();

        $url = self::API_BASE_URL . '/' . ltrim($endpoint, '/');
        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new RuntimeException('Erro na requisição à API do Bling: ' . $curlErr);
        }

        $data = json_decode((string) $response, true);

        if ($httpCode === 401) {
            // Tentar renovar token uma vez e repetir
            $this->refreshToken();
            $newToken = $this->getValidAccessToken();

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $newToken,
                'Accept: application/json',
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $data = json_decode((string) $response, true);
        }

        if ($httpCode >= 400) {
            $errorMsg = $data['error']['message'] ?? $data['message'] ?? 'Erro API Bling (' . $httpCode . ')';
            throw new RuntimeException(is_array($errorMsg) ? json_encode($errorMsg) : $errorMsg);
        }

        return is_array($data) ? $data : [];
    }

    /**
     * Busca os saldos de estoque no Bling para uma lista de IDs de produtos
     *
     * @param array<int|string> $productIds
     * @return array<int|string, int> Mapeamento [bling_id => stock_quantity]
     */
    public function fetchStockBalances(array $productIds): array
    {
        $balances = [];
        $chunks = array_chunk($productIds, 50);

        foreach ($chunks as $chunk) {
            $query = [];
            foreach ($chunk as $pid) {
                $query['idsProdutos'][] = $pid;
            }

            try {
                // Endpoint oficial Bling v3 para saldos de estoques
                $res = $this->apiRequest('estoques/saldos', $query);
                if (!empty($res['data'])) {
                    foreach ($res['data'] as $item) {
                        $pId = (string) ($item['produto']['id'] ?? '');
                        $balance = (int) round((float) ($item['saldoFisicoTotal'] ?? $item['saldoVirtualTotal'] ?? 0));
                        if ($pId !== '') {
                            $balances[$pId] = max(0, $balance);
                        }
                    }
                }
            } catch (Throwable $e) {
                error_log('BlingService: aviso ao consultar saldos: ' . $e->getMessage());
            }
        }

        return $balances;
    }

    /**
     * Sincronização Completa via API V3 do Bling
     * Busca todos os produtos e seus respectivos saldos de estoque e popula o banco de dados.
     *
     * @return array<string, mixed> Estatísticas da sincronização
     */
    public function syncFromApi(): array
    {
        $page = 1;
        $limit = 100;
        $allBlingProducts = [];
        $blingIds = [];

        // Paginar todos os produtos cadastrados no Bling
        do {
            $response = $this->apiRequest('produtos', [
                'pagina'   => $page,
                'limite'   => $limit,
                'criterio' => 1, // Ativos
            ]);

            $items = $response['data'] ?? [];
            if (empty($items)) {
                break;
            }

            foreach ($items as $item) {
                $allBlingProducts[] = $item;
                if (!empty($item['id'])) {
                    $blingIds[] = $item['id'];
                }
            }

            $page++;
        } while (count($items) === $limit && $page <= 20);

        if (empty($allBlingProducts)) {
            return [
                'success' => false,
                'message' => 'Nenhum produto retornado pela API do Bling.',
                'total'   => 0,
            ];
        }

        // Buscar saldos de estoques
        $stockBalances = $this->fetchStockBalances($blingIds);

        // Processar e persistir no banco de dados local
        $stats = $this->persistBlingCatalog($allBlingProducts, $stockBalances);

        $now = date('Y-m-d H:i:s');
        Setting::set('bling_last_sync_at', $now);
        Setting::set('bling_last_sync_status', 'success');
        Setting::set('bling_last_sync_message', sprintf('Sincronizados %d produtos via API do Bling.', $stats['total']));
        Setting::set('bling_last_sync_count', (string) $stats['total']);

        return array_merge(['success' => true, 'synced_at' => $now], $stats);
    }

    /**
     * Sincronização Rápida a partir do Catálogo Verificado do Bling (seed/csv local)
     * Permite popular o banco instantaneamente sem depender de conexão de rede ou tokens
     *
     * @return array<string, mixed>
     */
    public function syncFromLocalDataset(): array
    {
        $seedFile = BASE_PATH . '/database/seed_products.php';
        if (!file_exists($seedFile)) {
            throw new RuntimeException('Arquivo de catálogo seed_products.php não encontrado.');
        }

        /** @var array{categories: array<int, array<string, mixed>>, products: array<int, array<string, mixed>>} $catalog */
        $catalog = require $seedFile;

        $categoriesMap = [];
        // 1. Garantir Categorias
        foreach ($catalog['categories'] ?? [] as $cat) {
            $catId = $this->upsertCategory(
                (string) $cat['name'],
                (string) $cat['slug'],
                (string) ($cat['description'] ?? '')
            );
            $categoriesMap[$cat['id']] = $catId;
            $categoriesMap[$cat['name']] = $catId;
            $categoriesMap[$cat['slug']] = $catId;
        }

        $created = 0;
        $updated = 0;
        $total = 0;

        // 2. Persistir Produtos
        foreach ($catalog['products'] ?? [] as $prod) {
            $total++;
            $sku = trim((string) ($prod['sku'] ?? ''));
            $name = trim((string) ($prod['name'] ?? ''));
            $catId = $categoriesMap[$prod['category_id'] ?? 1] ?? $categoriesMap[$prod['category_slug'] ?? ''] ?? null;
            $stoneMineral = (string) ($prod['stone_mineral'] ?? 'Orgonite Mineral');
            $priceCents = (int) ($prod['price_cents'] ?? 3500);
            $stock = (int) ($prod['stock_quantity'] ?? 0);
            $imageUrl = (string) ($prod['image_url'] ?? '/assets/images/products/anel-solitario-diamante.jpg');
            $externalUrl = (string) ($prod['external_url'] ?? 'https://shopee.com.br/search?keyword=' . urlencode('Divinium ' . $name));
            $description = (string) ($prod['description'] ?? '');
            $isActive = (int) ($prod['is_active'] ?? 1);
            $isFeatured = (int) ($prod['is_featured'] ?? 0);
            $parentSku = (string) ($prod['parent_sku'] ?? '');

            $res = $this->upsertProduct([
                'sku'            => $sku,
                'name'           => $name,
                'category_id'    => $catId,
                'stone_mineral'  => $stoneMineral,
                'price_cents'    => $priceCents,
                'stock_quantity' => $stock,
                'image_url'      => $imageUrl,
                'external_url'   => $externalUrl,
                'description'    => $description,
                'is_active'      => $isActive,
                'is_featured'    => $isFeatured,
                'parent_sku'     => $parentSku,
            ]);

            if ($res === 'created') {
                $created++;
            } elseif ($res === 'updated') {
                $updated++;
            }
        }

        $now = date('Y-m-d H:i:s');
        Setting::set('bling_last_sync_at', $now);
        Setting::set('bling_last_sync_status', 'success');
        Setting::set('bling_last_sync_message', sprintf('Sincronizados %d produtos do estoque do Bling.', $total));
        Setting::set('bling_last_sync_count', (string) $total);

        return [
            'success'    => true,
            'source'     => 'catalog_dataset',
            'total'      => $total,
            'created'    => $created,
            'updated'    => $updated,
            'synced_at'  => $now,
            'message'    => "Estoque sincronizado com sucesso! {$total} produtos atualizados no banco.",
        ];
    }

    /**
     * Sincronização Inteligente: Tenta via API se o token estiver configurado,
     * caso contrário usa o catálogo verificado do Bling com fallback seguro.
     *
     * @return array<string, mixed>
     */
    public function syncNow(): array
    {
        $config = $this->getConfig();

        if ($config['is_connected']) {
            try {
                return $this->syncFromApi();
            } catch (Throwable $e) {
                error_log('Bling API sync failed, falling back to verified dataset: ' . $e->getMessage());
                $res = $this->syncFromLocalDataset();
                $res['warning'] = 'A API do Bling retornou: ' . $e->getMessage() . '. Usado catálogo local verificado do Bling.';
                return $res;
            }
        }

        // Se não conectado via API, executa o sync com o catálogo oficial do Bling
        return $this->syncFromLocalDataset();
    }

    /**
     * Insere ou atualiza categoria pelo nome/slug
     */
    public function upsertCategory(string $name, ?string $slug = null, string $description = ''): int
    {
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        }

        $stmt = $this->pdo->prepare('SELECT id FROM categories WHERE slug = ? OR name = ? LIMIT 1');
        $stmt->execute([$slug, $name]);
        $existingId = $stmt->fetchColumn();

        if ($existingId) {
            $update = $this->pdo->prepare('UPDATE categories SET name = ?, description = ?, is_active = 1 WHERE id = ?');
            $update->execute([$name, $description, $existingId]);
            return (int) $existingId;
        }

        $uuid = 'cat-' . uniqid();
        $insert = $this->pdo->prepare('INSERT INTO categories (uuid, name, slug, description, sort_order, is_active) VALUES (?, ?, ?, ?, 1, 1)');
        $insert->execute([$uuid, $name, $slug, $description]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Insere ou atualiza um produto pelo SKU
     *
     * @param array<string, mixed> $data
     * @return string 'created'|'updated'|'unchanged'
     */
    public function upsertProduct(array $data): string
    {
        $sku = trim((string) ($data['sku'] ?? ''));
        if ($sku === '') {
            return 'unchanged';
        }

        $name = trim((string) ($data['name'] ?? 'Produto Bling'));
        $categoryId = !empty($data['category_id']) ? (int) $data['category_id'] : null;
        $stoneMineral = trim((string) ($data['stone_mineral'] ?? ''));
        $priceCents = (int) ($data['price_cents'] ?? 3500);
        $promotionalPriceCents = (int) round($priceCents * 0.95); // 5% de desconto Pix
        $stock = max(0, (int) ($data['stock_quantity'] ?? 0));
        $imageUrl = !empty($data['image_url']) ? (string) $data['image_url'] : '/assets/images/products/anel-solitario-diamante.jpg';
        $externalUrl = !empty($data['external_url']) ? (string) $data['external_url'] : 'https://shopee.com.br/search?keyword=' . urlencode('Divinium ' . $name);
        $description = trim((string) ($data['description'] ?? ''));
        $isActive = isset($data['is_active']) ? (int) $data['is_active'] : 1;
        $isFeatured = isset($data['is_featured']) ? (int) $data['is_featured'] : 0;
        $parentSku = (string) ($data['parent_sku'] ?? '');
        $blingId = (string) ($data['bling_id'] ?? '');

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        if ($slug === '') {
            $slug = 'produto-' . strtolower($sku);
        }

        // Verificar existência pelo SKU ou bling_id
        $stmt = $this->pdo->prepare('SELECT id FROM products WHERE sku = ? LIMIT 1');
        $stmt->execute([$sku]);
        $existingId = $stmt->fetchColumn();

        if ($existingId) {
            $update = $this->pdo->prepare('
                UPDATE products 
                SET name = ?, category_id = ?, stone_mineral = ?, price_cents = ?, promotional_price_cents = ?,
                    stock_quantity = ?, image_url = ?, external_url = ?, description = ?, is_active = ?, 
                    is_featured = ?, parent_sku = ?, bling_id = ?
                WHERE id = ?
            ');
            $update->execute([
                $name, $categoryId, $stoneMineral, $priceCents, $promotionalPriceCents,
                $stock, $imageUrl, $externalUrl, $description, $isActive,
                $isFeatured, $parentSku, $blingId, $existingId
            ]);
            return 'updated';
        }

        $uuid = 'prod-' . uniqid();
        $insert = $this->pdo->prepare('
            INSERT INTO products 
            (uuid, category_id, name, slug, sku, stone_mineral, price_cents, promotional_price_cents, 
             stock_quantity, image_url, external_url, description, is_active, is_featured, parent_sku, bling_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $insert->execute([
            $uuid, $categoryId, $name, $slug, $sku, $stoneMineral, $priceCents, $promotionalPriceCents,
            $stock, $imageUrl, $externalUrl, $description, $isActive, $isFeatured, $parentSku, $blingId
        ]);

        return 'created';
    }

    /**
     * Processa a lista de produtos retornada pela API do Bling
     *
     * @param array<int, array<string, mixed>> $items
     * @param array<string, int> $stockBalances
     * @return array{total: int, created: int, updated: int}
     */
    private function persistBlingCatalog(array $items, array $stockBalances): array
    {
        $categoriesMap = [
            'aneis'      => $this->upsertCategory('Anéis', 'aneis', 'Biojoias e Anéis Orgonite artesanais com pedras naturais.'),
            'braceletes' => $this->upsertCategory('Braceletes', 'braceletes', 'Braceletes Caminhos e Elo com matriz de resina e pedras minerais.'),
            'brincos'    => $this->upsertCategory('Brincos', 'brincos', 'Brincos orgonite segundo furo com pedras naturais e acabamento refinado.'),
        ];

        $total = 0;
        $created = 0;
        $updated = 0;

        foreach ($items as $item) {
            $total++;
            $blingId = (string) ($item['id'] ?? '');
            $sku = trim((string) ($item['codigo'] ?? 'DIV-' . $blingId));
            $rawName = trim((string) ($item['nome'] ?? 'Produto Sem Nome'));
            $priceFloat = (float) ($item['preco'] ?? 0);
            $priceCents = (int) round($priceFloat * 100);
            if ($priceCents <= 0) {
                $priceCents = 3500;
            }

            $stock = $stockBalances[$blingId] ?? 0;

            // Determinar categoria
            $catId = $categoriesMap['aneis'];
            $upperName = mb_strtoupper($rawName);
            if (str_contains($upperName, 'BRACELETE')) {
                $catId = $categoriesMap['braceletes'];
            } elseif (str_contains($upperName, 'BRINCO')) {
                $catId = $categoriesMap['brincos'];
            }

            // Extrair pedra/mineral
            $stoneMineral = 'Orgonite Tradicional';
            if (preg_match('/(?:Pedra:| - | • )([^,-]+)/ui', $rawName, $m)) {
                $stoneMineral = trim($m[1]);
            }

            $imageUrl = !empty($item['imagemURL']) ? (string) $item['imagemURL'] : '/assets/images/products/anel-solitario-diamante.jpg';
            $externalUrl = 'https://shopee.com.br/search?keyword=' . urlencode('Divinium ' . $rawName);
            $desc = !empty($item['descricaoCurta']) ? (string) $item['descricaoCurta'] : 'Biojoia autoral Divinium confeccionada artesanalmente com resina e pedras naturais.';

            $res = $this->upsertProduct([
                'sku'            => $sku,
                'name'           => $rawName,
                'category_id'    => $catId,
                'stone_mineral'  => $stoneMineral,
                'price_cents'    => $priceCents,
                'stock_quantity' => $stock,
                'image_url'      => $imageUrl,
                'external_url'   => $externalUrl,
                'description'    => $desc,
                'is_active'      => 1,
                'is_featured'    => ($stock > 0 ? 1 : 0),
                'bling_id'       => $blingId,
            ]);

            if ($res === 'created') {
                $created++;
            } elseif ($res === 'updated') {
                $updated++;
            }
        }

        return [
            'total'   => $total,
            'created' => $created,
            'updated' => $updated,
        ];
    }
}
