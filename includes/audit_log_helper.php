<?php
/**
 * Audit Log Helper Functions
 * Used for tracking all lead and opportunity activities
 */

/**
 * Add audit log entry
 * @param int $lead_id - Lead/Opportunity ID
 * @param string $action_type - Type of action (CREATE, UPDATE, CONVERT_TO_OPPORTUNITY, APPROVAL_CHANGE, etc)
 * @param string $description - Description of the action
 * @param string|null $field_name - Field being updated (optional)
 * @param mixed $old_value - Previous value (optional)
 * @param mixed $new_value - New value (optional)
 * @return bool - True if logged successfully
 */
function add_audit_log($lead_id, $action_type, $description, $field_name = null, $old_value = null, $new_value = null) {
    try {
        // Get current user info from session
        $user_id = $_SESSION['user_id'] ?? 0;
        $user_name = $_SESSION['name'] ?? 'Unknown User';
        $user_type = $_SESSION['user_type'] ?? 'Unknown';
        
        // Get IP address
        $ip_address = get_client_ip();
        
        // Get user agent
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        // Prepare values
        $old_value = is_array($old_value) ? json_encode($old_value) : $old_value;
        $new_value = is_array($new_value) ? json_encode($new_value) : $new_value;
        
        // Insert into audit log
        $sql = "INSERT INTO tbl_audit_log 
                (lead_id, user_id, user_name, user_type, action_type, field_name, old_value, new_value, description, ip_address, user_agent) 
                VALUES 
                ('" . intval($lead_id) . "', 
                 '" . intval($user_id) . "', 
                 '" . addslashes($user_name) . "', 
                 '" . addslashes($user_type) . "', 
                 '" . addslashes($action_type) . "', 
                 " . ($field_name ? "'" . addslashes($field_name) . "'" : "NULL") . ", 
                 " . ($old_value ? "'" . addslashes(substr($old_value, 0, 10000)) . "'" : "NULL") . ", 
                 " . ($new_value ? "'" . addslashes(substr($new_value, 0, 10000)) . "'" : "NULL") . ", 
                 '" . addslashes($description) . "', 
                 '" . addslashes($ip_address) . "', 
                 '" . addslashes($user_agent) . "')";
        
        $result = db_query($sql);
        return $result ? true : false;
    } catch (Exception $e) {
        // Log errors silently to avoid breaking main application
        error_log("Audit Log Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get client IP address
 * @return string - IP address
 */
function get_client_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_FORWARDED'])) {
        $ip = $_SERVER['HTTP_FORWARDED'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else {
        $ip = 'UNKNOWN';
    }
    return $ip;
}

/**
 * Log lead creation
 * @param int $lead_id - Newly created lead ID
 * @param string $company_name - Company name
 */
function log_lead_creation($lead_id, $company_name) {
    add_audit_log(
        $lead_id, 
        'CREATE', 
        "Lead created with company name: " . $company_name
    );
}

/**
 * Log field update
 * @param int $lead_id - Lead ID
 * @param string $field_name - Field being updated
 * @param mixed $old_value - Previous value
 * @param mixed $new_value - New value
 * @param string|null $custom_description - Custom description (optional)
 */
function log_field_update($lead_id, $field_name, $old_value, $new_value, $custom_description = null) {
    $description = $custom_description ?? "Updated " . str_replace('_', ' ', $field_name) . " from '" . substr($old_value, 0, 50) . "' to '" . substr($new_value, 0, 50) . "'";
    
    add_audit_log(
        $lead_id, 
        'UPDATE', 
        $description,
        $field_name,
        $old_value,
        $new_value
    );
}

/**
 * Log conversion to opportunity
 * @param int $lead_id - Lead ID being converted
 */
function log_convert_to_opportunity($lead_id) {
    add_audit_log(
        $lead_id, 
        'CONVERT_TO_OPPORTUNITY', 
        'Lead converted to Opportunity',
        'is_opportunity',
        '0',
        '1'
    );
}

/**
 * Log approval status change
 * @param int $lead_id - Lead/Opportunity ID
 * @param int $old_status - Previous approval status (0 or 1)
 * @param int $new_status - New approval status (0 or 1)
 */
function log_approval_change($lead_id, $old_status, $new_status) {
    $status_text_old = $old_status == 1 ? 'Approved' : 'Not Approved';
    $status_text_new = $new_status == 1 ? 'Approved' : 'Not Approved';
    
    add_audit_log(
        $lead_id, 
        'APPROVAL_CHANGE', 
        'Approval status changed from ' . $status_text_old . ' to ' . $status_text_new,
        'is_approved',
        $old_status,
        $new_status
    );
}

/**
 * Log status change
 * @param int $lead_id - Lead ID
 * @param string $old_status - Previous status
 * @param string $new_status - New status
 */
function log_status_change($lead_id, $old_status, $new_status) {
    add_audit_log(
        $lead_id, 
        'STATUS_CHANGE', 
        'Status changed from ' . $old_status . ' to ' . $new_status,
        'status',
        $old_status,
        $new_status
    );
}

/**
 * Log file upload
 * @param int $lead_id - Lead ID
 * @param string $file_path - Path to uploaded file
 * @param string $file_type - Type of file (image, document, etc)
 */
function log_file_upload($lead_id, $file_path, $file_type = 'file') {
    add_audit_log(
        $lead_id, 
        'FILE_UPLOAD', 
        'Uploaded ' . $file_type . ': ' . basename($file_path),
        'upload_file',
        null,
        $file_path
    );
}

/**
 * Log email sent
 * @param int $lead_id - Lead ID
 * @param string $recipient - Email recipient
 * @param string $subject - Email subject
 */
function log_send_email($lead_id, $recipient, $subject) {
    add_audit_log(
        $lead_id, 
        'SEND_EMAIL', 
        'Email sent to ' . $recipient . ': ' . $subject
    );
}

/**
 * Get audit log for a specific lead
 * @param int $lead_id - Lead ID
 * @param int $limit - Number of records to fetch
 * @return array - Array of audit logs
 */
function get_lead_audit_log($lead_id, $limit = 100) {
    $sql = "SELECT 
                log_id,
                lead_id,
                user_name,
                user_type,
                action_type,
                field_name,
                old_value,
                new_value,
                description,
                ip_address,
                DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') as timestamp
            FROM tbl_audit_log
            WHERE lead_id = " . intval($lead_id) . "
            ORDER BY created_at DESC
            LIMIT " . intval($limit);
    
    $result = db_query($sql);
    $logs = array();
    
    while ($row = db_fetch_array($result)) {
        $logs[] = $row;
    }
    
    return $logs;
}

/**
 * Get user activities
 * @param int $user_id - User ID
 * @param int $limit - Number of records to fetch
 * @return array - Array of user activities
 */
function get_user_activities($user_id, $limit = 100) {
    $sql = "SELECT 
                log_id,
                lead_id,
                user_name,
                action_type,
                description,
                DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') as timestamp
            FROM tbl_audit_log
            WHERE user_id = " . intval($user_id) . "
            ORDER BY created_at DESC
            LIMIT " . intval($limit);
    
    $result = db_query($sql);
    $activities = array();
    
    while ($row = db_fetch_array($result)) {
        $activities[] = $row;
    }
    
    return $activities;
}

/**
 * Get all conversion logs
 * @param int $limit - Number of records to fetch
 * @return array - Array of conversion logs
 */
function get_conversion_logs($limit = 100) {
    $sql = "SELECT 
                log_id,
                lead_id,
                user_name,
                user_type,
                description,
                DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') as timestamp
            FROM tbl_audit_log
            WHERE action_type = 'CONVERT_TO_OPPORTUNITY'
            ORDER BY created_at DESC
            LIMIT " . intval($limit);
    
    $result = db_query($sql);
    $logs = array();
    
    while ($row = db_fetch_array($result)) {
        $logs[] = $row;
    }
    
    return $logs;
}

/**
 * Get all approval change logs
 * @param int $limit - Number of records to fetch
 * @return array - Array of approval logs
 */
function get_approval_logs($limit = 100) {
    $sql = "SELECT 
                log_id,
                lead_id,
                user_name,
                user_type,
                old_value,
                new_value,
                description,
                DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') as timestamp
            FROM tbl_audit_log
            WHERE action_type = 'APPROVAL_CHANGE'
            ORDER BY created_at DESC
            LIMIT " . intval($limit);
    
    $result = db_query($sql);
    $logs = array();
    
    while ($row = db_fetch_array($result)) {
        $logs[] = $row;
    }
    
    return $logs;
}
