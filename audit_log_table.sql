-- ============================================================
-- Audit Log Table for tracking all lead and opportunity activities
-- ============================================================
-- Fixed: Removed problematic FOREIGN KEY constraint (errno 150)
-- The constraint commented out because your database structure may vary
-- You can uncomment the alternative version below if needed
-- ============================================================
CREATE TABLE IF NOT EXISTS `tbl_audit_log` (
    `log_id` BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'Unique log ID',
    `lead_id` INT NOT NULL COMMENT 'Lead/Opportunity ID from orders/tbl_mst_orders table',
    `user_id` INT NOT NULL COMMENT 'User who performed the action',
    `user_name` VARCHAR(100) NOT NULL COMMENT 'Username for quick reference',
    `user_type` VARCHAR(50) COMMENT 'User type (ADMIN, SUPERADMIN, PARTNER, etc)',
    `action_type` ENUM('CREATE', 'UPDATE', 'DELETE', 'CONVERT_TO_OPPORTUNITY', 'APPROVAL_CHANGE', 'STATUS_CHANGE', 'FILE_UPLOAD', 'SEND_EMAIL') NOT NULL COMMENT 'Type of action performed',
    `field_name` VARCHAR(100) COMMENT 'Field name being updated (null for CREATE)',
    `old_value` LONGTEXT COMMENT 'Previous value of the field',
    `new_value` LONGTEXT COMMENT 'New value of the field',
    `description` VARCHAR(500) NOT NULL COMMENT 'Single line description of the action',
    `ip_address` VARCHAR(45) COMMENT 'IP address of the user',
    `user_agent` VARCHAR(500) COMMENT 'Browser/Device information',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When the action was performed',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX `idx_lead_id` (`lead_id`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_action_type` (`action_type`),
    INDEX `idx_created_at` (`created_at`),
    INDEX `idx_lead_user_date` (`lead_id`, `user_id`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Audit log for tracking all lead and opportunity activities';


-- ============================================================
-- ALTERNATIVE: If you're using the 'orders' table instead of 'tbl_mst_orders'
-- Uncomment the version below and comment out the one above
-- ============================================================
/*
CREATE TABLE IF NOT EXISTS `tbl_audit_log` (
    `log_id` BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'Unique log ID',
    `lead_id` INT NOT NULL COMMENT 'Lead/Opportunity ID from orders table',
    `user_id` INT NOT NULL COMMENT 'User who performed the action',
    `user_name` VARCHAR(100) NOT NULL COMMENT 'Username for quick reference',
    `user_type` VARCHAR(50) COMMENT 'User type (ADMIN, SUPERADMIN, PARTNER, etc)',
    `action_type` ENUM('CREATE', 'UPDATE', 'DELETE', 'CONVERT_TO_OPPORTUNITY', 'APPROVAL_CHANGE', 'STATUS_CHANGE', 'FILE_UPLOAD', 'SEND_EMAIL') NOT NULL COMMENT 'Type of action performed',
    `field_name` VARCHAR(100) COMMENT 'Field name being updated (null for CREATE)',
    `old_value` LONGTEXT COMMENT 'Previous value of the field',
    `new_value` LONGTEXT COMMENT 'New value of the field',
    `description` VARCHAR(500) NOT NULL COMMENT 'Single line description of the action',
    `ip_address` VARCHAR(45) COMMENT 'IP address of the user',
    `user_agent` VARCHAR(500) COMMENT 'Browser/Device information',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When the action was performed',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX `idx_lead_id` (`lead_id`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_action_type` (`action_type`),
    INDEX `idx_created_at` (`created_at`),
    INDEX `idx_lead_user_date` (`lead_id`, `user_id`, `created_at`),
    
    FOREIGN KEY (`lead_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Audit log for tracking all lead and opportunity activities';
*/


-- Insert sample audit logs (for reference)
INSERT INTO `tbl_audit_log` 
(`lead_id`, `user_id`, `user_name`, `user_type`, `action_type`, `field_name`, `old_value`, `new_value`, `description`, `ip_address`) 
VALUES 
(1, 5, 'Pooja Chauhan', 'ADMIN', 'CREATE', NULL, NULL, NULL, 'Lead created with company name KLASY INNOVATIONS', '192.168.1.105'),
(1, 5, 'Pooja Chauhan', 'ADMIN', 'UPDATE', 'company_name', 'KLASY INNOVATIONS', 'KLASY INNOVATIONS PVT', 'Updated company name', '192.168.1.105'),
(1, 5, 'Pooja Chauhan', 'ADMIN', 'UPDATE', 'stage_id', '2', '3', 'Changed stage from Proposal to POC', '192.168.1.105'),
(1, 5, 'Pooja Chauhan', 'ADMIN', 'FILE_UPLOAD', 'upload_file', NULL, 'uploads/leads/lead_abc123.jpg', 'Uploaded company logo image', '192.168.1.105'),
(1, 5, 'Pooja Chauhan', 'ADMIN', 'CONVERT_TO_OPPORTUNITY', 'is_opportunity', '0', '1', 'Lead converted to Opportunity', '192.168.1.105'),
(1, 3, 'Admin User', 'SUPERADMIN', 'APPROVAL_CHANGE', 'is_approved', '0', '1', 'Opportunity approved for processing', '192.168.1.100'),
(1, 5, 'Pooja Chauhan', 'ADMIN', 'UPDATE', 'expected_close_date', '2026-02-28', '2026-03-15', 'Extended expected closure date', '192.168.1.105'),
(1, 5, 'Pooja Chauhan', 'ADMIN', 'STATUS_CHANGE', 'status', 'Pending', 'In Progress', 'Status updated to In Progress', '192.168.1.105');


-- Query to view all activities for a specific lead
SELECT 
    log_id,
    lead_id,
    user_name,
    user_type,
    action_type,
    field_name,
    old_value,
    new_value,
    description,
    DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') as timestamp
FROM tbl_audit_log
WHERE lead_id = 1
ORDER BY created_at DESC;


-- Query to view all user activities
SELECT 
    log_id,
    lead_id,
    user_name,
    action_type,
    description,
    DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') as timestamp
FROM tbl_audit_log
WHERE user_id = 5
ORDER BY created_at DESC
LIMIT 50;


-- Query to view all approval changes
SELECT 
    log_id,
    lead_id,
    user_name,
    field_name,
    old_value,
    new_value,
    description,
    DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') as timestamp
FROM tbl_audit_log
WHERE action_type = 'APPROVAL_CHANGE'
ORDER BY created_at DESC;


-- Query to view conversion to opportunity logs
SELECT 
    log_id,
    lead_id,
    user_name,
    user_type,
    description,
    DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') as timestamp
FROM tbl_audit_log
WHERE action_type = 'CONVERT_TO_OPPORTUNITY'
ORDER BY created_at DESC;
