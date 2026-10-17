<?php

declare(strict_types=1);

namespace App\Webhooks;

use App\Core\Env;
use PDO;
use Throwable;

/**
 * Receptor e processador de Webhooks do ASAAS.
 * - Validação em tempo constante do token (asaas-access-token)
 * - Garantia de idempotência via tabela payment_events
 * - Resposta imediata HTTP 200
 * - Transição segura de máquina de estados de pedidos
 */
class AsaasWebhookHandler
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Ponto de entrada do endpoint de webhook (ex: POST /api/webhooks/asaas).
     */
    public function handle(): void
    {
        $receivedToken = $_SERVER['HTTP_ASAAS_ACCESS_TOKEN'] ?? '';
        $expectedToken = (string) Env::get('ASAAS_WEBHOOK_SECRET');

        // 1. VALIDAÇÃO DE SEGURANÇA EM TEMPO CONSTANTE (EVITA TIMING ATTACK)
        if (empty($receivedToken) || !hash_equals($expectedToken, $receivedToken)) {
            $this->logSecurityViolation('Token de webhook inválido ou ausente', [
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'desconhecido',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);

            http_response_code(401);
            echo json_encode(['error' => 'Acesso não autorizado'], JSON_UNESCAPED_UNICODE);
            return;
        }

        // 2. LEITURA E VALIDAÇÃO DO PAYLOAD JSON
        $rawPayload = file_get_contents('php://input');
        if (empty($rawPayload)) {
            http_response_code(400);
            echo json_encode(['error' => 'Payload vazio'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $data = json_decode($rawPayload, true);
        if (!is_array($data) || empty($data['event']) || empty($data['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Formato de evento inválido'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $eventId = (string) $data['id'];
        $eventName = (string) $data['event'];
        $paymentData = $data['payment'] ?? [];
        $asaasPaymentId = $paymentData['id'] ?? null;
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        // 3. IDEMPOTÊNCIA: REGISTRO DO EVENTO COM UNIQUE CONSTRAINT
        // Se o evento já foi processado anteriormente, retorna 200 imediatamente
        try {
            $stmt = $this->db->prepare('
                INSERT INTO payment_events (asaas_event_id, event_name, status, payload, ip_address, created_at)
                VALUES (:event_id, :event_name, "queued", :payload, :ip_address, NOW())
            ');

            $stmt->execute([
                ':event_id' => $eventId,
                ':event_name' => $eventName,
                ':payload' => $rawPayload,
                ':ip_address' => $ipAddress
            ]);
            $internalEventId = (int) $this->db->lastInsertId();
        } catch (Throwable $e) {
            // Código 23000 = Violação de integridade / chave única (duplicidade)
            if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'Duplicate entry')) {
                // Evento já registrado: responder 200 e encerrar de forma idempotente
                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'Evento já recebido e deduplicado'], JSON_UNESCAPED_UNICODE);
                return;
            }

            $this->logError("Erro ao registrar evento de pagamento {$eventId}: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Erro interno ao persistir webhook'], JSON_UNESCAPED_UNICODE);
            return;
        }

        // 4. PROCESSAMENTO DO EVENTO E ATUALIZAÇÃO DA MÁQUINA DE ESTADOS
        try {
            $this->processEvent($eventName, $paymentData, $internalEventId);

            // Marca evento como processado
            $updateStmt = $this->db->prepare('
                UPDATE payment_events 
                SET status = "processed", processed_at = NOW() 
                WHERE id = :id
            ');
            $updateStmt->execute([':id' => $internalEventId]);

            // Resposta de sucesso 200 imediata ao ASAAS
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Webhook processado com sucesso'], JSON_UNESCAPED_UNICODE);

        } catch (Throwable $e) {
            // Registra falha para reprocessamento posterior via rotina cron
            $failStmt = $this->db->prepare('
                UPDATE payment_events 
                SET status = "failed", error_message = :err, processing_attempts = processing_attempts + 1 
                WHERE id = :id
            ');
            $failStmt->execute([
                ':id' => $internalEventId,
                ':err' => substr($e->getMessage(), 0, 1000)
            ]);

            $this->logError("Falha ao processar evento {$eventId} ({$eventName}): " . $e->getMessage());

            // Ainda retornamos 200 para que o ASAAS não fique em loop agressivo se for erro de regra de negócio,
            // ou 500 se desejamos retry automático do gateway. O padrão recomendado pelo ASAAS para erros de sistema é 500.
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao processar transição de estado'], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Executa as transições permitidas da máquina de estados.
     */
    private function processEvent(string $eventName, array $paymentData, int $internalEventId): void
    {
        $asaasPaymentId = $paymentData['id'] ?? null;
        if (!$asaasPaymentId) {
            return;
        }

        $this->db->beginTransaction();

        try {
            // Localizar registro de pagamento local
            $stmt = $this->db->prepare('
                SELECT p.id, p.order_id, p.status, p.amount_cents, o.status AS order_status
                FROM payments p
                INNER JOIN orders o ON o.id = p.order_id
                WHERE p.asaas_payment_id = :asaas_id
                FOR UPDATE
            ');
            $stmt->execute([':asaas_id' => $asaasPaymentId]);
            $payment = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$payment) {
                // Pagamento não encontrado localmente (pode ser de outro ambiente)
                $this->db->commit();
                return;
            }

            $orderId = (int) $payment['order_id'];
            $oldOrderStatus = (string) $payment['order_status'];

            switch ($eventName) {
                case 'PAYMENT_RECEIVED':
                case 'PAYMENT_CONFIRMED':
                    // Transição para PAGO
                    if (in_array($oldOrderStatus, ['pending_payment', 'payment_processing'], true)) {
                        $this->db->prepare('
                            UPDATE orders SET status = "paid", updated_at = NOW() WHERE id = :order_id
                        ')->execute([':order_id' => $orderId]);

                        $this->db->prepare('
                            UPDATE payments 
                            SET status = "CONFIRMED", payment_date = NOW(), updated_at = NOW() 
                            WHERE id = :payment_id
                        ')->execute([':payment_id' => $payment['id']]);

                        $this->recordAuditLog('order.paid', 'orders', $orderId, [
                            'status' => $oldOrderStatus
                        ], [
                            'status' => 'paid',
                            'asaas_event' => $eventName
                        ]);
                    }
                    break;

                case 'PAYMENT_OVERDUE':
                    if ($oldOrderStatus === 'pending_payment') {
                        $this->db->prepare('
                            UPDATE orders SET status = "cancelled", updated_at = NOW() WHERE id = :order_id
                        ')->execute([':order_id' => $orderId]);

                        $this->db->prepare('
                            UPDATE payments SET status = "OVERDUE", updated_at = NOW() WHERE id = :payment_id
                        ')->execute([':payment_id' => $payment['id']]);

                        $this->recordAuditLog('order.cancelled_overdue', 'orders', $orderId, [
                            'status' => $oldOrderStatus
                        ], [
                            'status' => 'cancelled'
                        ]);
                    }
                    break;

                case 'PAYMENT_REFUNDED':
                    $this->db->prepare('
                        UPDATE orders SET status = "refunded", updated_at = NOW() WHERE id = :order_id
                    ')->execute([':order_id' => $orderId]);

                    $this->db->prepare('
                        UPDATE payments SET status = "REFUNDED", updated_at = NOW() WHERE id = :payment_id
                    ')->execute([':payment_id' => $payment['id']]);

                    $this->recordAuditLog('order.refunded', 'orders', $orderId, [
                        'status' => $oldOrderStatus
                    ], [
                        'status' => 'refunded'
                    ]);
                    break;
            }

            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function recordAuditLog(string $action, string $entityType, int $entityId, array $old, array $new): void
    {
        $stmt = $this->db->prepare('
            INSERT INTO audit_logs (user_id, ip_address, user_agent, action, entity_type, entity_id, old_values, new_values, context, created_at)
            VALUES (NULL, :ip, "ASAAS-Webhook", :action, :type, :id, :old, :new, :context, NOW())
        ');
        $stmt->execute([
            ':ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            ':action' => $action,
            ':type' => $entityType,
            ':id' => $entityId,
            ':old' => json_encode($old),
            ':new' => json_encode($new),
            ':context' => json_encode(['source' => 'webhook'])
        ]);
    }

    private function logSecurityViolation(string $reason, array $meta): void
    {
        $stmt = $this->db->prepare('
            INSERT INTO security_logs (level, event_type, ip_address, user_agent, request_uri, request_method, details, created_at)
            VALUES ("CRITICAL", "webhook_token_invalid", :ip, :ua, "/api/webhooks/asaas", "POST", :details, NOW())
        ');
        $stmt->execute([
            ':ip' => $meta['ip'] ?? '0.0.0.0',
            ':ua' => $meta['user_agent'] ?? '',
            ':details' => json_encode(['reason' => $reason])
        ]);

        $logFile = BASE_PATH . '/logs/security/webhooks_unauthorized.log';
        $entry = sprintf("[%s] VIOLAÇÃO: %s | IP: %s\n", date('Y-m-d H:i:s'), $reason, $meta['ip'] ?? '');
        @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
    }

    private function logError(string $message): void
    {
        $logFile = BASE_PATH . '/logs/webhooks/webhook_errors.log';
        $entry = sprintf("[%s] %s\n", date('Y-m-d H:i:s'), $message);
        @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
    }
}
