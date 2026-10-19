-- ==============================================================================
-- SISTEMA DE E-COMMERCE COM ASAAS & LGPD (DIVINIUM)
-- SCRIPT DDL E SEEDS - COMPATÍVEL COM MYSQL 8.0+ E MARIADB 10.6+
-- Charset: utf8mb4 | Collation: utf8mb4_unicode_ci | Engine: InnoDB
-- ==============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------------------------
-- 1. TABELA DE MIGRAÇÕES / CONTROLE DE VERSÃO
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `migrations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `migration` VARCHAR(255) NOT NULL,
    `batch` INT UNSIGNED NOT NULL,
    `executed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 2. TABELA DE CONFIGURAÇÕES GERAIS DO SISTEMA
-- Armazena configurações dinâmicas que não exigem re-deploy
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key_name` VARCHAR(100) NOT NULL UNIQUE,
    `value` TEXT NULL,
    `is_encrypted` TINYINT(1) NOT NULL DEFAULT 0,
    `description` VARCHAR(255) NULL,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 3. CONTROLE DE ACESSO (RBAC) - ROLES E PERMISSIONS
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roles` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `display_name` VARCHAR(100) NOT NULL,
    `description` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `permissions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(80) NOT NULL UNIQUE,
    `display_name` VARCHAR(120) NOT NULL,
    `module` VARCHAR(50) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `role_permissions` (
    `role_id` INT UNSIGNED NOT NULL,
    `permission_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`role_id`, `permission_id`),
    CONSTRAINT `fk_role_permissions_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_role_permissions_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 4. USUÁRIOS
-- UUID público para evitar enumeração sequencial de IDs na interface pública
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `uuid` CHAR(36) NOT NULL UNIQUE,
    `role_id` INT UNSIGNED NOT NULL DEFAULT 2, -- Default: Cliente
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(191) NOT NULL UNIQUE,
    `cpf_cnpj_hash` CHAR(64) NULL, -- SHA-256 para busca indexada rápida
    `cpf_cnpj_encrypted` TEXT NULL, -- Dado pessoal criptografado via AES-256-GCM
    `phone` VARCHAR(20) NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `two_factor_secret` VARCHAR(255) NULL,
    `two_factor_enabled` TINYINT(1) NOT NULL DEFAULT 0,
    `status` ENUM('active', 'inactive', 'blocked', 'anonymized') NOT NULL DEFAULT 'active',
    `email_verified_at` TIMESTAMP NULL,
    `last_login_at` TIMESTAMP NULL,
    `last_login_ip` VARCHAR(45) NULL,
    `failed_login_attempts` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `locked_until` TIMESTAMP NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL, -- Soft delete
    CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT,
    INDEX `idx_users_email` (`email`),
    INDEX `idx_users_cpf_hash` (`cpf_cnpj_hash`),
    INDEX `idx_users_uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 5. SESSÕES, LOGIN ATTEMPTS E RECUPERAÇÃO DE SENHA
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sessions` (
    `id` VARCHAR(128) PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(255) NOT NULL,
    `payload` MEDIUMTEXT NOT NULL,
    `last_activity` INT UNSIGNED NOT NULL,
    CONSTRAINT `fk_sessions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    INDEX `idx_sessions_last_activity` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `login_attempts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email_or_identifier` VARCHAR(191) NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(255) NULL,
    `success` TINYINT(1) NOT NULL,
    `attempted_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_login_attempts_ip_time` (`ip_address`, `attempted_at`),
    INDEX `idx_login_attempts_ident_time` (`email_or_identifier`, `attempted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_resets` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(191) NOT NULL,
    `token_hash` CHAR(64) NOT NULL, -- SHA-256 do token gerado
    `expires_at` TIMESTAMP NOT NULL,
    `used_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_pwd_resets_token` (`token_hash`),
    INDEX `idx_pwd_resets_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 6. ENDEREÇOS (PADRÃO BRASIL)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `addresses` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `uuid` CHAR(36) NOT NULL UNIQUE,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `recipient_name` VARCHAR(150) NOT NULL,
    `zipcode` CHAR(8) NOT NULL, -- Apenas dígitos numéricos (ex: 01001000)
    `street` VARCHAR(150) NOT NULL,
    `number` VARCHAR(20) NOT NULL,
    `complement` VARCHAR(100) NULL,
    `district` VARCHAR(100) NOT NULL,
    `city` VARCHAR(100) NOT NULL,
    `state` CHAR(2) NOT NULL, -- UF: SP, RJ, etc.
    `is_default` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    CONSTRAINT `fk_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    INDEX `idx_addresses_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 7. VÍNCULO CLIENTE COM GATEWAY ASAAS
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customers_asaas` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL UNIQUE,
    `asaas_customer_id` VARCHAR(50) NOT NULL UNIQUE,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_asaas_customer_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
    INDEX `idx_asaas_customer_id` (`asaas_customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 8. CATÁLOGO: CATEGORIAS E PRODUTOS
-- Preços em centavos (INT UNSIGNED): elimina imprecisão binária de floats.
-- Ex: R$ 149,90 = 14990 centavos.
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `uuid` CHAR(36) NOT NULL UNIQUE,
    `parent_id` INT UNSIGNED NULL,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(120) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `products` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `uuid` CHAR(36) NOT NULL UNIQUE,
    `category_id` INT UNSIGNED NULL,
    `name` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(220) NOT NULL UNIQUE,
    `sku` VARCHAR(60) NOT NULL UNIQUE,
    `short_description` VARCHAR(255) NULL,
    `description` MEDIUMTEXT NOT NULL,
    `price_cents` INT UNSIGNED NOT NULL, -- Valor normal de venda
    `promotional_price_cents` INT UNSIGNED NULL, -- Preço promocional (se ativo)
    `cost_price_cents` INT UNSIGNED NULL,
    `stock_quantity` INT NOT NULL DEFAULT 0,
    `min_stock_alert` INT NOT NULL DEFAULT 5,
    `weight_grams` INT UNSIGNED NOT NULL DEFAULT 0,
    `height_cm` INT UNSIGNED NOT NULL DEFAULT 0,
    `width_cm` INT UNSIGNED NOT NULL DEFAULT 0,
    `length_cm` INT UNSIGNED NOT NULL DEFAULT 0,
    `materials` VARCHAR(255) NULL,
    `dimensions` VARCHAR(150) NULL,
    `colors` VARCHAR(255) NULL,
    `variants_json` TEXT NULL,
    `stone_mineral` VARCHAR(150) NULL,
    `image_url` VARCHAR(500) NULL,
    `stock_status` VARCHAR(30) NOT NULL DEFAULT 'in_stock',
    `status` VARCHAR(20) NOT NULL DEFAULT 'published',
    `sort_order` INT NOT NULL DEFAULT 0,
    `show_badge` TINYINT(1) NOT NULL DEFAULT 1,
    `show_specs` TINYINT(1) NOT NULL DEFAULT 1,
    `show_description` TINYINT(1) NOT NULL DEFAULT 1,
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0, -- Destaque na Home de Branding
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `external_url` VARCHAR(500) NULL, -- Link de venda externa principal (legado/fallback)
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
    INDEX `idx_products_slug` (`slug`),
    INDEX `idx_products_active_featured` (`is_active`, `is_featured`),
    INDEX `idx_products_status_sort` (`status`, `sort_order`),
    INDEX `idx_products_sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `product_links` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `platform_name` VARCHAR(100) NOT NULL,
    `url` VARCHAR(500) NOT NULL,
    `price_cents` INT UNSIGNED NULL,
    `button_text` VARCHAR(100) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_product_links_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
    INDEX `idx_product_links_sort` (`product_id`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `product_images` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `file_path` VARCHAR(255) NOT NULL, -- Caminho público relativo em /uploads/products/
    `alt_text` VARCHAR(150) NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_cover` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
    INDEX `idx_product_images_sort` (`product_id`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 9. CUPONS DE DESCONTO
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `coupons` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `type` ENUM('percentage', 'fixed_amount') NOT NULL,
    `value` INT UNSIGNED NOT NULL, -- Porcentagem (1 a 100) ou valor em centavos
    `min_order_amount_cents` INT UNSIGNED NOT NULL DEFAULT 0,
    `usage_limit` INT UNSIGNED NULL, -- Limite geral de usos
    `usage_per_user_limit` INT UNSIGNED NOT NULL DEFAULT 1, -- Limite por CPF/usuário
    `used_times` INT UNSIGNED NOT NULL DEFAULT 0,
    `starts_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `expires_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 10. CARRINHOS DE COMPRAS (SESSÃO OU USUÁRIO)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `carts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `uuid` CHAR(36) NOT NULL UNIQUE,
    `user_id` BIGINT UNSIGNED NULL,
    `session_token` VARCHAR(64) NOT NULL,
    `coupon_id` INT UNSIGNED NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_carts_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
    INDEX `idx_carts_session` (`session_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cart_items` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `cart_id` BIGINT UNSIGNED NOT NULL,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
    `unit_price_cents` INT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_cart_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
    UNIQUE KEY `uk_cart_product` (`cart_id`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 11. PEDIDOS E ITENS (MÁQUINA DE ESTADOS DO PEDIDO)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `uuid` CHAR(36) NOT NULL UNIQUE,
    `order_number` VARCHAR(32) NOT NULL UNIQUE, -- Ex: ORD-20261012-0001
    `user_id` BIGINT UNSIGNED NOT NULL,
    `status` ENUM(
        'pending_payment', -- Aguardando pagamento
        'payment_processing', -- Pagamento em análise de risco
        'paid', -- Pago / confirmado
        'preparing', -- Em separação / empacotamento
        'shipped', -- Despachado / em transporte
        'delivered', -- Entregue
        'cancelled', -- Cancelado (falha de pagamento ou manual)
        'refunded', -- Reembolsado / estornado
        'disputed' -- Chargeback / contestação
    ) NOT NULL DEFAULT 'pending_payment',
    `subtotal_cents` INT UNSIGNED NOT NULL,
    `discount_cents` INT UNSIGNED NOT NULL DEFAULT 0,
    `shipping_cost_cents` INT UNSIGNED NOT NULL DEFAULT 0,
    `total_cents` INT UNSIGNED NOT NULL, -- SEMPRE calculado no backend
    `coupon_id` INT UNSIGNED NULL,
    `shipping_carrier` VARCHAR(80) NULL, -- Ex: Melhor Envio / Correios Sedex
    `shipping_service_code` VARCHAR(50) NULL,
    `tracking_code` VARCHAR(100) NULL,
    `shipping_address_snapshot` JSON NOT NULL, -- Endereço imutável no momento da compra
    `customer_snapshot` JSON NOT NULL, -- Dados do cliente no momento da compra
    `customer_notes` TEXT NULL,
    `admin_notes` TEXT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_orders_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
    INDEX `idx_orders_status` (`status`),
    INDEX `idx_orders_user` (`user_id`),
    INDEX `idx_orders_number` (`order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `order_items` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` BIGINT UNSIGNED NOT NULL,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `product_name` VARCHAR(200) NOT NULL,
    `sku` VARCHAR(60) NOT NULL,
    `quantity` INT UNSIGNED NOT NULL,
    `unit_price_cents` INT UNSIGNED NOT NULL,
    `total_price_cents` INT UNSIGNED NOT NULL,
    CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 12. PAGAMENTOS (ASAAS) E DADOS TOKENIZADOS
-- Zero armazenamento de PAN (número do cartão) ou CVV. Escopo PCI SAQ A.
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `payments` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `uuid` CHAR(36) NOT NULL UNIQUE,
    `order_id` BIGINT UNSIGNED NOT NULL,
    `asaas_payment_id` VARCHAR(50) NOT NULL UNIQUE, -- ID da cobrança retornado pela API do ASAAS
    `billing_type` ENUM('PIX', 'CREDIT_CARD', 'BOLETO') NOT NULL,
    `status` ENUM(
        'PENDING',
        'RECEIVED',
        'CONFIRMED',
        'OVERDUE',
        'REFUNDED',
        'RECEIVED_IN_CASH',
        'REFUND_REQUESTED',
        'CHARGEBACK_REQUESTED',
        'CHARGEBACK_DISPUTE',
        'AWAITING_CHARGEBACK_REVERSAL',
        'DUNNING_REQUESTED',
        'DUNNING_RECEIVED',
        'AWAITING_RISK_ANALYSIS'
    ) NOT NULL DEFAULT 'PENDING',
    `amount_cents` INT UNSIGNED NOT NULL,
    `net_amount_cents` INT UNSIGNED NULL, -- Valor líquido creditado após taxas ASAAS
    `fee_cents` INT UNSIGNED NULL, -- Taxa cobrada pelo ASAAS
    `due_date` DATE NOT NULL,
    `payment_date` TIMESTAMP NULL,
    `client_payment_date` TIMESTAMP NULL,
    `installment_count` TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `pix_qr_code_payload` TEXT NULL, -- Copia e cola do Pix
    `pix_qr_code_image_url` VARCHAR(255) NULL, -- URL do QR code oficial do ASAAS
    `bank_slip_url` VARCHAR(255) NULL, -- URL da 2ª via do boleto
    `bank_slip_bar_code` VARCHAR(80) NULL, -- Linha digitável
    `card_last4` CHAR(4) NULL, -- Apenas últimos 4 dígitos informados pelo ASAAS
    `card_brand` VARCHAR(30) NULL, -- Visa, Mastercard, Elo
    `raw_response` JSON NULL, -- Resposta da criação da cobrança para auditoria
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE RESTRICT,
    INDEX `idx_payments_asaas_id` (`asaas_payment_id`),
    INDEX `idx_payments_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 13. WEBHOOKS / EVENTOS DE PAGAMENTO (ASAAS) COM IDEMPOTÊNCIA
-- UNIQUE KEY no event_id do ASAAS impede processamento duplicado
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `payment_events` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `asaas_event_id` VARCHAR(100) NOT NULL UNIQUE, -- ID único do evento enviado pelo webhook do ASAAS
    `payment_id` BIGINT UNSIGNED NULL,
    `event_name` VARCHAR(80) NOT NULL, -- Ex: PAYMENT_RECEIVED, PAYMENT_OVERDUE
    `status` ENUM('queued', 'processed', 'ignored', 'failed') NOT NULL DEFAULT 'queued',
    `payload` JSON NOT NULL, -- Payload JSON íntegro recebido
    `ip_address` VARCHAR(45) NOT NULL,
    `processing_attempts` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `error_message` TEXT NULL,
    `processed_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_payment_events_payment` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
    INDEX `idx_payment_events_status` (`status`),
    INDEX `idx_payment_events_asaas_event` (`asaas_event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 14. TRILHA DE AUDITORIA IMUTÁVEL (APPEND-ONLY)
-- Apenas INSERT é permitido. UPDATE e DELETE são bloqueados via TRIGGER.
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(255) NULL,
    `action` VARCHAR(100) NOT NULL, -- Ex: order.created, payment.webhook_received, user.login
    `entity_type` VARCHAR(80) NULL, -- Ex: orders, payments, users
    `entity_id` BIGINT UNSIGNED NULL,
    `old_values` JSON NULL,
    `new_values` JSON NULL,
    `context` JSON NULL, -- Correlações, IDs externos
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_audit_entity` (`entity_type`, `entity_id`),
    INDEX `idx_audit_action` (`action`),
    INDEX `idx_audit_user` (`user_id`),
    INDEX `idx_audit_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TRIGGERS DE SEGURANÇA: Impedem UPDATE ou DELETE em audit_logs
DROP TRIGGER IF EXISTS `trg_prevent_audit_logs_update`;
DELIMITER $$
CREATE TRIGGER `trg_prevent_audit_logs_update`
BEFORE UPDATE ON `audit_logs`
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Segurança: Atualizações são estritamente proibidas em audit_logs (append-only).';
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `trg_prevent_audit_logs_delete`;
DELIMITER $$
CREATE TRIGGER `trg_prevent_audit_logs_delete`
BEFORE DELETE ON `audit_logs`
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Segurança: Exclusões são estritamente proibidas em audit_logs (append-only).';
END$$
DELIMITER ;

-- ------------------------------------------------------------------------------
-- 15. LOGS DE SEGURANÇA (SECURITY LOGS)
-- Trilha de ataques detectados (brute-force, CSRF fail, SQLi tentativa, etc.)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `security_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `level` ENUM('INFO', 'WARNING', 'CRITICAL', 'EMERGENCY') NOT NULL,
    `event_type` VARCHAR(100) NOT NULL, -- Ex: csrf_token_invalid, brute_force_ip_blocked
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(255) NULL,
    `request_uri` VARCHAR(255) NULL,
    `request_method` VARCHAR(10) NULL,
    `details` JSON NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_sec_logs_level` (`level`),
    INDEX `idx_sec_logs_ip` (`ip_address`),
    INDEX `idx_sec_logs_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 16. LGPD - TERMOS DE CONSENTIMENTO E PRIVACIDADE
-- Guarda versão exata, data/hora e IP no momento do opt-in
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `consents` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NULL,
    `consent_type` ENUM('terms_of_use', 'privacy_policy', 'marketing_email', 'marketing_whatsapp') NOT NULL,
    `policy_version` VARCHAR(20) NOT NULL, -- Ex: '1.0.0'
    `granted` TINYINT(1) NOT NULL DEFAULT 1,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(255) NULL,
    `revoked_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_consents_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    INDEX `idx_consents_user_type` (`user_id`, `consent_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 17. NEWSLETTER / CAPTURA DE LEADS
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `newsletter` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(191) NOT NULL UNIQUE,
    `name` VARCHAR(150) NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `is_subscribed` TINYINT(1) NOT NULL DEFAULT 1,
    `unsubscribed_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ==============================================================================
-- SEEDS INICIAIS (DADOS ESSENCIAIS)
-- ==============================================================================

-- Papéis do Sistema
INSERT INTO `roles` (`id`, `name`, `display_name`, `description`) VALUES
(1, 'super_admin', 'Super Administrador', 'Acesso total irrestrito ao painel e configurações'),
(2, 'customer', 'Cliente', 'Acesso à área do cliente e compras'),
(3, 'manager', 'Gerente de E-commerce', 'Gestão de catálogo, pedidos e estoque'),
(4, 'support', 'Atendimento', 'Consulta de pedidos e suporte ao cliente')
ON DUPLICATE KEY UPDATE `display_name` = VALUES(`display_name`);

-- Permissões Essenciais
INSERT INTO `permissions` (`id`, `name`, `display_name`, `module`) VALUES
(1, 'products.view', 'Visualizar Produtos', 'catalog'),
(2, 'products.manage', 'Criar/Editar/Excluir Produtos', 'catalog'),
(3, 'orders.view', 'Visualizar Pedidos', 'orders'),
(4, 'orders.manage', 'Alterar Status de Pedidos', 'orders'),
(5, 'customers.view', 'Visualizar Clientes', 'customers'),
(6, 'audit.view', 'Visualizar Trilha de Auditoria', 'audit'),
(7, 'settings.manage', 'Gerenciar Configurações', 'settings')
ON DUPLICATE KEY UPDATE `display_name` = VALUES(`display_name`);

-- Vínculo Papel Administrador x Permissões
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 1, `id` FROM `permissions`;

-- Usuário Administrador Placeholder
-- ATENÇÃO: Senha padrão temporária: 'Admin@Secure2026!#' (Argon2id)
-- OBRIGATÓRIO trocar no primeiro acesso!
INSERT INTO `users` (
    `id`, `uuid`, `role_id`, `name`, `email`, `password_hash`, `status`, `created_at`
) VALUES (
    1,
    'a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11',
    1,
    'Administrador Inicial',
    'admin@seudominio.com.br',
    '$argon2id$v=19$m=65536,t=4,p=1$cTBoN3dEaFpuSEoxUllXdw$a0P1/v68jD03l7d45fE6g7h8i9j0k1l2m3n4o5p6q7r',
    'active',
    NOW()
) ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- Categorias Iniciais de Exemplo para o E-commerce
INSERT INTO `categories` (`id`, `uuid`, `parent_id`, `name`, `slug`, `description`, `sort_order`, `is_active`) VALUES
(1, 'b1eebc99-9c0b-4ef8-bb6d-6bb9bd380a12', NULL, 'Destaques Exclusivos', 'destaques-exclusivos', 'Linha principal de produtos assinados pela marca.', 1, 1),
(2, 'c2eebc99-9c0b-4ef8-bb6d-6bb9bd380a13', NULL, 'Coleção Premium', 'colecao-premium', 'Itens limitados com curadoria especial.', 2, 1)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Configurações Globais Padrão
INSERT INTO `settings` (`key_name`, `value`, `is_encrypted`, `description`) VALUES
('store_name', 'Divinium Brand', 0, 'Nome comercial da loja exibido nos e-mails e cabeçalho'),
('store_document_cnpj', '00.000.000/0001-00', 0, 'CNPJ oficial exibido no rodapé (obrigatório pelo Decreto 7.962/2013)'),
('store_support_email', 'atendimento@seudominio.com.br', 0, 'E-mail de contato e SAC oficial'),
('store_support_whatsapp', '5511999999999', 0, 'Número do WhatsApp para suporte ao cliente'),
('store_dpo_contact', 'dpo@seudominio.com.br', 0, 'Contato do Encarregado de Proteção de Dados (LGPD)'),
('asaas_environment', 'sandbox', 0, 'Ambiente do gateway: sandbox ou production'),
('policy_version_current', '1.0.0', 0, 'Versão vigente dos Termos e Políticas de Privacidade'),
('default_theme', 'theme-gold', 0, 'Tema visual padrão da loja (theme-gold, theme-silver, theme-emerald, theme-rose)')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);

-- ------------------------------------------------------------------------------
-- TABELAS DE ANALYTICS & RASTREAMENTO DE INTENÇÕES DE COMPRA SHOPEE
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_clicks` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `source` VARCHAR(50) NOT NULL DEFAULT 'store',
    `ip_address` VARCHAR(45) NOT NULL DEFAULT '127.0.0.1',
    `user_agent` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_product_clicks_product` (`product_id`),
    INDEX `idx_product_clicks_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `page_views` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `page_name` VARCHAR(100) NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL DEFAULT '127.0.0.1',
    `user_agent` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_page_views_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================================
-- MODELO DE PRIVILÉGIOS MÍNIMOS (LEAST PRIVILEGE) PARA APLICAÇÃO
-- Executar como ROOT / ADMIN do MySQL:
-- ==============================================================================
/*
-- 1. Usuário da Aplicação Web (execução diária - SEM permissões DDL):
CREATE USER IF NOT EXISTS 'u_divinium_app'@'localhost' IDENTIFIED BY 'SUA_SENHA_FORTE_APP_AQUI';
GRANT SELECT, INSERT, UPDATE, DELETE ON `divinium_db`.* TO 'u_divinium_app'@'localhost';

-- 2. Usuário de Deploy/Migrations (usado somente pelo CI/CD ou via terminal SSH):
CREATE USER IF NOT EXISTS 'u_divinium_deploy'@'localhost' IDENTIFIED BY 'SUA_SENHA_FORTE_DEPLOY_AQUI';
GRANT ALL PRIVILEGES ON `divinium_db`.* TO 'u_divinium_deploy'@'localhost';

FLUSH PRIVILEGES;
*/
