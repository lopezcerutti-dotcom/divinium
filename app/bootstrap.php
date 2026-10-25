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

    // Limpar buffers incompletos para garantir resposta íntegra ao navegador
    while (ob_get_level() > 0) {
        @ob_end_clean();
    }

    // NUNCA emitir HTTP 500 no LiteSpeed / Hostinger para evitar que ele
    // intercepte a saída e substitua por "Configuração do servidor incompleta. Por favor, contate o administrador."
    if (!headers_sent()) {
        http_response_code(200);
    }

    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $isJson = (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))
        || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')
        || (isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json'))
        || !empty($_SERVER['HTTP_X_CSRF_TOKEN'])
        || str_contains($uri, '/salvar')
        || str_contains($uri, '/api/');

    if ($isJson) {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=UTF-8');
        }
        echo json_encode([
            'success' => false,
            'error' => $isProduction ? 'Ocorreu um erro interno momentâneo. Por favor, recarregue a página e tente novamente.' : $e->getMessage(),
            'details' => $isProduction ? null : [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]
        ], JSON_UNESCAPED_UNICODE);
    } else {
        if (!headers_sent()) {
            header('Content-Type: text/html; charset=UTF-8');
        }
        if ($isProduction) {
            echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Divinium Joalheria</title><style>body{background:#F8F8F7;color:#202020;font-family:sans-serif;text-align:center;padding:80px 20px;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;box-sizing:border-box;}.box{background:#FFFFFF;border:1px solid #737B85;border-radius:16px;padding:48px 32px;max-width:500px;box-shadow:0 10px 30px rgba(0,0,0,0.05);}h2{letter-spacing:4px;font-weight:600;margin:0 0 12px;color:#202020;}p{color:#686868;line-height:1.6;margin:0 0 24px;}a{color:#202020;background:linear-gradient(135deg,#CBD0D6 0%,#A7ADB4 100%);border:1px solid #737B85;padding:12px 28px;border-radius:24px;text-decoration:none;display:inline-block;font-weight:700;}</style></head><body><div class="box"><h2>DIVINIUM</h2><p>Nosso servidor está finalizando uma atualização automática momentânea.</p><a href="/">Recarregar Página</a></div></body></html>';
        } else {
            echo "<pre style='background:#f8d7da;color:#721c24;padding:20px;border-radius:5px;'>";
            echo "<strong>Erro de Desenvolvimento:</strong> " . htmlspecialchars($e->getMessage()) . "\n";
            echo "<strong>Arquivo:</strong> " . htmlspecialchars($e->getFile()) . " (" . $e->getLine() . ")\n\n";
            echo htmlspecialchars($e->getTraceAsString());
            echo "</pre>";
        }
    }
    exit(0);
});

// Handler de Encerramento (Captura Erros Fatais do PHP antes que o LiteSpeed Hostinger intercepte)
register_shutdown_function(function () use ($isProduction): void {
    $error = error_get_last();
    $fatalTypes = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_RECOVERABLE_ERROR, E_USER_ERROR];
    if ($error !== null && in_array($error['type'], $fatalTypes, true)) {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = sprintf(
            "[%s] FATAL ERROR: %s em %s:%d\n",
            $timestamp,
            $error['message'],
            $error['file'],
            $error['line']
        );
        @file_put_contents(LOGS_PATH . '/app/exception.log', $logMessage, FILE_APPEND | LOCK_EX);
        @file_put_contents(LOGS_PATH . '/app/fatal_last.log', $logMessage, LOCK_EX);

        if (php_sapi_name() === 'cli') {
            exit(1);
        }

        while (ob_get_level() > 0) {
            @ob_end_clean();
        }

        // Não emitir HTTP 500 no LiteSpeed / Hostinger para evitar "Configuração do servidor incompleta"
        if (!headers_sent()) {
            http_response_code(200);
            header('Content-Type: text/html; charset=UTF-8');
        }

        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $isJson = (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))
            || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')
            || (isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json'))
            || !empty($_SERVER['HTTP_X_CSRF_TOKEN'])
            || str_contains($uri, '/salvar')
            || str_contains($uri, '/api/');

        if ($isJson) {
            if (!headers_sent()) {
                header('Content-Type: application/json; charset=UTF-8');
            }
            echo json_encode([
                'success' => false,
                'error' => 'Ocorreu uma instabilidade temporária no servidor. Por favor, tente novamente.'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Divinium Joalheria</title><style>body{background:#F8F8F7;color:#202020;font-family:sans-serif;text-align:center;padding:80px 20px;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;box-sizing:border-box;}.box{background:#FFFFFF;border:1px solid #737B85;border-radius:16px;padding:48px 32px;max-width:500px;box-shadow:0 10px 30px rgba(0,0,0,0.05);}h2{letter-spacing:4px;font-weight:600;margin:0 0 12px;color:#202020;}p{color:#686868;line-height:1.6;margin:0 0 24px;}a{color:#202020;background:linear-gradient(135deg,#CBD0D6 0%,#A7ADB4 100%);border:1px solid #737B85;padding:12px 28px;border-radius:24px;text-decoration:none;display:inline-block;font-weight:700;}</style></head><body><div class="box"><h2>DIVINIUM</h2><p>A boutique está inicializando os serviços com segurança.</p><a href="/">Atualizar Página</a></div></body></html>';
        }
    }
});

// 6. BLINDAGEM DE SESSÃO PHP (Apenas para requisições Web)
if (php_sapi_name() !== 'cli' && session_status() === PHP_SESSION_NONE && !headers_sent()) {
    try {
        @ini_set('session.use_strict_mode', '1');
        @ini_set('session.use_only_cookies', '1');
        @ini_set('session.cookie_httponly', '1');
        
        // Armazenamento isolado na pasta segura de storage do próprio site (imune a expurgo do SO/cron)
        $sessionSavePath = STORAGE_PATH . '/sessions';
        if (!is_dir($sessionSavePath)) {
            @mkdir($sessionSavePath, 0755, true);
        }
        if (is_dir($sessionSavePath) && is_writable($sessionSavePath)) {
            @session_save_path($sessionSavePath);
        }

        // Cookie seguro somente se estiver sob HTTPS
        $isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
                   (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
                   (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') ||
                   (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443);
        
        if ($isHttps) {
            @ini_set('session.cookie_secure', '1');
        }

        @session_set_cookie_params([
            'lifetime' => (int) Env::get('SESSION_LIFETIME', 86400),
            'path' => '/',
            'domain' => '',
            'secure' => $isHttps,
            'httponly' => true,
            'samesite' => (string) Env::get('SESSION_SAME_SITE', 'Lax')
        ]);

        @session_start();
    } catch (\Throwable $sessionEx) {
        // Ignora silenciosamente para não interromper inicialização
    }
}

// 7. RETORNO DO AMBIENTE INICIALIZADO
return [
    'env' => Env::get('APP_ENV'),
    'debug' => Env::get('APP_DEBUG'),
    'url' => Env::get('APP_URL')
];
