<?php

declare(strict_types=1);

namespace App\Integrations\Asaas;

use App\Core\Env;
use RuntimeException;

/**
 * Cliente HTTP para a API v3 do ASAAS.
 * Executa requisições via cURL com TLS rigoroso, timeouts e política de retry.
 */
class AsaasClient
{
    private string $apiKey;
    private string $baseUrl;
    private int $timeout;

    public function __construct()
    {
        $this->apiKey = (string) Env::get('ASAAS_API_KEY');
        $env = (string) Env::get('ASAAS_ENVIRONMENT', 'sandbox');
        
        $this->baseUrl = ($env === 'production')
            ? 'https://api.asaas.com/v3'
            : 'https://sandbox.asaas.com/api/v3';

        $this->timeout = 25; // 25 segundos máximo por requisição
    }

    /**
     * Envia uma requisição HTTP para o ASAAS.
     *
     * @param string $method GET, POST, PUT, DELETE
     * @param string $endpoint Ex: '/customers', '/payments'
     * @param array<string, mixed> $data
     * @param int $retries Número de tentativas em caso de erro temporário de rede
     * @return array<string, mixed> Resposta decodificada em JSON
     */
    public function request(string $method, string $endpoint, array $data = [], int $retries = 2): array
    {
        $url = $this->baseUrl . $endpoint;
        $attempt = 0;

        do {
            $attempt++;
            $ch = curl_init();

            $headers = [
                'Content-Type: application/json',
                'access_token: ' . $this->apiKey,
                'User-Agent: DiviniumStore/1.0 (Hostinger-Integration)'
            ];

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            
            // Verificação SSL estrita (obrigatório para produção)
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

            if ($method === 'POST') {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
            } elseif ($method === 'PUT') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
            } elseif ($method === 'DELETE') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            } elseif ($method === 'GET' && !empty($data)) {
                $urlWithQuery = $url . '?' . http_build_query($data);
                curl_setopt($ch, CURLOPT_URL, $urlWithQuery);
            }

            $responseBody = curl_exec($ch);
            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            $curlErrno = curl_errno($ch);
            curl_close($ch);

            // Se for erro de conexão transitório (timeout ou rede), tenta novamente
            if ($curlErrno && $attempt <= $retries) {
                usleep(500000 * $attempt); // Backoff: 0.5s, 1.0s
                continue;
            }

            if ($curlErrno) {
                $this->logError("Erro cURL [{$curlErrno}]: {$curlError} ao acessar {$endpoint}");
                throw new RuntimeException("Falha de comunicação com o gateway de pagamento: {$curlError}");
            }

            $decoded = json_decode((string) $responseBody, true);

            // Sucesso (2xx)
            if ($httpCode >= 200 && $httpCode < 300) {
                return is_array($decoded) ? $decoded : [];
            }

            // Tratamento de erros de negócio da API do ASAAS (4xx, 5xx)
            $errorMessage = $this->extractErrorMessage($decoded, $httpCode);
            $this->logError("Erro ASAAS [HTTP {$httpCode}] em {$endpoint}: {$errorMessage}");

            throw new RuntimeException("Erro ASAAS: {$errorMessage}", $httpCode);

        } while ($attempt <= $retries);

        throw new RuntimeException("Falha ao comunicar com ASAAS após {$retries} tentativas.");
    }

    private function extractErrorMessage(?array $response, int $httpCode): string
    {
        if (isset($response['errors']) && is_array($response['errors'])) {
            $descriptions = array_column($response['errors'], 'description');
            return implode(', ', $descriptions);
        }

        if (isset($response['message'])) {
            return (string) $response['message'];
        }

        return "Código de resposta HTTP inesperado: {$httpCode}";
    }

    private function logError(string $message): void
    {
        $logFile = BASE_PATH . '/logs/payments/asaas_errors.log';
        $entry = sprintf("[%s] %s\n", date('Y-m-d H:i:s'), $message);
        @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
    }
}
