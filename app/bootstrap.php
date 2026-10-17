<?php

declare(strict_types=1);

/**
 * ==============================================================================
 * BOOTSTRAP DA APLICAÇÃO (FORA DA WEB)
 * Inicializa constantes, variáveis de ambiente, tratamento de erros e segurança.
 * ==============================================================================
 */

// 1. DEFINIÇÃO DE DIRETÓRIOS BASE
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('STORAGE_PATH', BASE_PATH . '/storage');
define('LOGS_PATH', BASE_PATH . '/logs');
define('PUBLIC_PATH', BASE_PATH . '/public_html');

// 2. AUTOLOAD PSR-4 (COMPOSER OU FALLBACK NATIVO)
if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require_once BASE_PATH . '/vendor/autoload.php';
} else {
    // Autoloader fallback para execução antes do 'composer install'
    spl_autoload_register(function (string $class): void {
        $prefix = 'App\\';
        $baseDir = APP_PATH . '/';

        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }

        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require $file;
        }
    });
}

// 3. CARREGAMENTO DO ARQUIVO DE AMBIENTE (.env, env.php OU FALLBACK SEGURO)
if (file_exists(APP_PATH . '/Core/Env.php')) {
    require_once APP_PATH . '/Core/Env.php';
}
use App\Core\Env;

// Procurar o .env ou env.php na raiz do projeto ou pastas adjacentes
$possibleEnvPaths = [
    BASE_PATH . '/.env',
    BASE_PATH . '/env.php',
    BASE_PATH . '/config.env',
    APP_PATH . '/.env',
    APP_PATH . '/env.php',
    dirname(BASE_PATH) . '/.env',
    __DIR__ . '/../.env',
    PUBLIC_PATH . '/../.env',
    PUBLIC_PATH . '/.env',
    PUBLIC_PATH . '/env.php',
    ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/.env',
    ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/../.env',
];

$envFile = null;
foreach ($possibleEnvPaths as $candidate) {
    if (!empty($candidate) && file_exists($candidate) && is_readable($candidate)) {
        if (str_ends_with($candidate, '.php')) {
            $phpVars = require $candidate;
            if (is_array($phpVars)) {
                Env::setMany($phpVars);
                $envFile = $candidate;
                break;
            }
        } else {
            Env::load($candidate);
            $envFile = $candidate;
            break;
        }
    }
}

// VALORES PADRÃO SEGUROS (AUTO-CURA):
// Garante que, mesmo que o arquivo .env suma no upload por ser arquivo oculto,
// ou caso falte alguma chave, o site NUNCA trave com "Configuração do servidor incompleta".
$safeDefaults = [
    'APP_NAME' => 'Divinium Store',
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'false',
    'APP_URL' => 'https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'),
    'APP_KEY' => 'b9dfeaafff599d8a7873a7d7c5c6584f650e4ec43877015d5dbca94e71f23625',
    'APP_TIMEZONE' => 'America/Sao_Paulo',
    'APP_LOCALE' => 'pt_BR',
    'ADMIN_PREFIX' => 'gestao-privada-x84j2',
    'DB_CONNECTION' => 'mysql',
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'u247277668_divinium',
    'DB_USERNAME' => 'u247277668_aurikim',
    'DB_PASSWORD' => '202122AURIkim',
    'DB_CHARSET' => 'utf8mb4',
    'DB_COLLATION' => 'utf8mb4_unicode_ci',
    'ASAAS_ENVIRONMENT' => 'sandbox',
    'ASAAS_API_KEY' => '$aact_placeholder_configure_sua_chave_asaas_aqui',
    'ASAAS_WEBHOOK_SECRET' => 'whsec_placeholder_configure_seu_token_webhook_aqui',
    'SESSION_LIFETIME' => '7200',
    'SESSION_SECURE_COOKIE' => 'true',
    'SESSION_HTTP_ONLY' => 'true',
    'SESSION_SAME_SITE' => 'Lax'
];

foreach ($safeDefaults as $key => $val) {
    if (Env::get($key) === null || Env::get($key) === '') {
        Env::set($key, (string) $val);
    }
}

// Garantir existência de diretórios essenciais de logs e cache
$requiredDirs = [
    LOGS_PATH . '/app',
    LOGS_PATH . '/security',
    LOGS_PATH . '/payments',
    LOGS_PATH . '/webhooks',
    STORAGE_PATH . '/cache',
    STORAGE_PATH . '/sessions',
];
foreach ($requiredDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 4. CONFIGURAÇÃO DE FUSO HORÁRIO E ENCODING
date_default_timezone_set(Env::get('APP_TIMEZONE', 'America/Sao_Paulo'));
if (function_exists('mb_internal_encoding')) {
    mb_internal_encoding('UTF-8');
}

// 5. TRATAMENTO SEGURO DE ERROS E EXCEÇÕES (OWASP A05)
$isProduction = Env::get('APP_ENV') === 'production';

if ($isProduction) {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED);
} else {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

ini_set('log_errors', '1');
ini_set('error_log', LOGS_PATH . '/app/error.log');

// Handler de Exceções Não Capturadas
set_exception_handler(function (Throwable $e) use ($isProduction): void {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = sprintf(
        "[%s] %s: %s em %s:%d\nStack trace:\n%s\n",
        $timestamp,
        get_class($e),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTraceAsString()
    );

    // Gravar no log de aplicação segregado
    @file_put_contents(LOGS_PATH . '/app/exception.log', $logMessage, FILE_APPEND | LOCK_EX);

    if (php_sapi_name() === 'cli') {
        fwrite(STDERR, "\n[ERRO CRÍTICO] " . $e->getMessage() . "\nEm " . $e->getFile() . ":" . $e->getLine() . "\n");
        exit(1);
    }

    if (!headers_sent()) {
        http_response_code(500);
    }

    if ($isProduction) {
        // Resposta opaca para o usuário final
        if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                'success' => false,
                'message' => 'Ocorreu um erro interno ao processar sua requisição. Tente novamente mais tarde.'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><title>Erro no Servidor</title></head><body style="font-family:sans-serif;text-align:center;padding:50px;"><h2>Desculpe, ocorreu uma instabilidade momentânea.</h2><p>Nossa equipe já foi notificada. Por favor, recarregue a página em alguns instantes.</p></body></html>';
        }
    } else {
        echo "<pre style='background:#f8d7da;color:#721c24;padding:20px;border-radius:5px;'>";
        echo "<strong>Erro de Desenvolvimento:</strong> " . htmlspecialchars($e->getMessage()) . "\n";
        echo "<strong>Arquivo:</strong> " . htmlspecialchars($e->getFile()) . " (" . $e->getLine() . ")\n\n";
        echo htmlspecialchars($e->getTraceAsString());
        echo "</pre>";
    }
    exit(1);
});

// 6. BLINDAGEM DE SESSÃO PHP (Apenas para requisições Web)
if (php_sapi_name() !== 'cli' && session_status() === PHP_SESSION_NONE && !headers_sent()) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    
    // Cookie seguro somente se estiver sob HTTPS
    $isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
               (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    
    if ($isHttps) {
        ini_set('session.cookie_secure', '1');
    }

    session_set_cookie_params([
        'lifetime' => (int) Env::get('SESSION_LIFETIME', 7200),
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => (string) Env::get('SESSION_SAME_SITE', 'Lax')
    ]);

    session_start();
}

// 7. RETORNO DO AMBIENTE INICIALIZADO
return [
    'env' => Env::get('APP_ENV'),
    'debug' => Env::get('APP_DEBUG'),
    'url' => Env::get('APP_URL')
];
