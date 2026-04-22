/**
 * AUDIT LOG IMPLEMENTATION GUIDE
 * =============================
 * 
 * This document explains how to integrate the audit logging system
 * into your existing lead/opportunity management system.
 */

// ============================================================
// 1. SETUP (Run once in your database)
// ============================================================
// Execute the SQL file: audit_log_table.sql
// This creates the tbl_audit_log table with proper structure and indexes


// ============================================================
// 2. INCLUDE THE HELPER FILE
// ============================================================
// Add this to your header.php or include.php
include('includes/audit_log_helper.php');


// ============================================================
// 3. IMPLEMENTATION EXAMPLES
// ============================================================

// ============================================================
// EXAMPLE 1: Log in edit_leads.php (Field Update)
// ============================================================

// When user updates a field in edit_leads.php, log it:

if ($_POST['form_submission']) {
    $lead_id = $_POST['lead_id'];
    $old_company_name = $result['company_name'];
    $new_company_name = $_POST['company_name'];
    
    // Only log if value changed
    if ($old_company_name != $new_company_name) {
        log_field_update(
            $lead_id, 
            'company_name', 
            $old_company_name, 
            $new_company_name, 
            "Changed company name from '$old_company_name' to '$new_company_name'"
        );
    }
    
    // Update the field
    $sql = "UPDATE tbl_mst_orders SET company_name = '" . db_escape_string($new_company_name) . "' WHERE id = " . intval($lead_id);
    db_query($sql);
}


// ============================================================
// EXAMPLE 2: Log in add_leads.php (Create New Lead)
// ============================================================

// When creating a new lead, log it:

if ($_POST['form_submission']) {
    $company_name = $_POST['company_name'];
    
    // Insert the lead
    $sql = "INSERT INTO tbl_mst_orders (company_name, ...) VALUES ('" . db_escape_string($company_name) . "', ...)";
    $result = db_query($sql);
    $lead_id = get_insert_id();
    
    // Log the creation
    if ($lead_id > 0) {
        log_lead_creation($lead_id, $company_name);
    }
}


// ============================================================
// EXAMPLE 3: Log File Upload (Image Upload)
// ============================================================

// In add_leads.php or edit_leads.php when handling file upload:

if ($_FILES['upload_file']) {
    $lead_id = $_POST['lead_id'];
    $file = $_FILES['upload_file'];
    
    // Your existing upload logic...
    $file_path = 'uploads/leads/lead_' . uniqid('', true) . '.' . $extension;
    move_uploaded_file($file['tmp_name'], $file_path);
    
    // Log the file upload
    log_file_upload($lead_id, $file_path, 'company image');
}


// ============================================================
// EXAMPLE 4: Log Convert to Opportunity (ajax_update.php)
// ============================================================

// When handling convert_to_opportunity action in ajax_update.php:

if ($_POST['action'] == 'convert_to_opportunity') {
    $lead_id = $_POST['lead_id'];
    
    // Log the conversion BEFORE updating
    log_convert_to_opportunity($lead_id);
    
    // Update the database
    $sql = "UPDATE tbl_mst_orders SET is_opportunity = 1 WHERE id = " . intval($lead_id);
    $result = db_query($sql);
    
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Lead converted to Opportunity']);
    }
}


// ============================================================
// EXAMPLE 5: Log Approval Status Change (ajax_common.php)
// ============================================================

// When handling approval status change:

if ($_POST['leads_approval']) {
    $lead_id = $_POST['id'];
    $new_status = $_POST['status'];
    
    // Get old status
    $old_result = getSingleResult("SELECT is_approved FROM tbl_mst_orders WHERE id = " . intval($lead_id));
    $old_status = $old_result['is_approved'];
    
    // Log the approval change
    log_approval_change($lead_id, $old_status, $new_status);
    
    // Update the database
    $sql = "UPDATE tbl_mst_orders SET is_approved = " . intval($new_status) . " WHERE id = " . intval($lead_id);
    $result = db_query($sql);
    
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Approval status updated']);
    }
}


// ============================================================
// EXAMPLE 6: Log Stage/Status Change
// ============================================================

// When updating stage or lead status:

$lead_id = $_POST['lead_id'];
$old_stage = $result['stage_id'];
$new_stage = $_POST['stage_id'];

if ($old_stage != $new_stage) {
    log_field_update(
        $lead_id,
        'stage_id',
        $old_stage,
        $new_stage,
        "Updated stage in lead management"
    );
    
    // Update database
    db_query("UPDATE tbl_mst_orders SET stage_id = " . intval($new_stage) . " WHERE id = " . intval($lead_id));
}


// ============================================================
// 4. VIEWING AUDIT LOGS
// ============================================================

// Get all logs for a specific lead:
$logs = get_lead_audit_log(1, 100); // lead_id = 1, limit 100 records

foreach ($logs as $log) {
    echo "User: " . $log['user_name'];
    echo "Action: " . $log['action_type'];
    echo "Description: " . $log['description'];
    echo "Time: " . $log['timestamp'];
    echo "---";
}

// Get user's activities:
$activities = get_user_activities(5, 50); // user_id = 5

// Get all conversions to opportunity:
$conversions = get_conversion_logs(100);

// Get all approval changes:
$approvals = get_approval_logs(100);


// ============================================================
// 5. DATABASE TABLE STRUCTURE
// ============================================================

/*
CREATE TABLE tbl_audit_log (
    log_id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    lead_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    user_name VARCHAR(100) NOT NULL,
    user_type VARCHAR(50),
    action_type ENUM('CREATE', 'UPDATE', 'DELETE', 'CONVERT_TO_OPPORTUNITY', 
                     'APPROVAL_CHANGE', 'STATUS_CHANGE', 'FILE_UPLOAD', 'SEND_EMAIL'),
    field_name VARCHAR(100),
    old_value LONGTEXT,
    new_value LONGTEXT,
    description VARCHAR(500) NOT NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_lead_id (lead_id),
    INDEX idx_user_id (user_id),
    INDEX idx_action_type (action_type),
    INDEX idx_created_at (created_at),
    
    FOREIGN KEY (lead_id) REFERENCES tbl_mst_orders(id) ON DELETE CASCADE
)
*/


// ============================================================
// 6. QUICK INTEGRATION CHECKLIST
// ============================================================

[✓] Create table using audit_log_table.sql
[  ] Include audit_log_helper.php in header.php
[  ] Add logging to add_leads.php (on create)
[  ] Add logging to edit_leads.php (on edit)
[  ] Add logging to ajax_update.php (convert_to_opportunity)
[  ] Add logging to ajax_common.php (approval change)
[  ] Add logging for file uploads
[  ] Add logging for stage changes
[  ] Test logging functionality
[  ] Create audit log viewer page (optional)


// ============================================================
// 7. OPTIONAL: Create Audit Log Viewer Page
// ============================================================

// You can create view_audit_log.php to display logs:

<?php
include('includes/header.php');

if (isset($_GET['lead_id'])) {
    $logs = get_lead_audit_log($_GET['lead_id'], 100);
    
    // Display in DataTable or HTML format
    ?>
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Field</th>
                <th>Old Value</th>
                <th>New Value</th>
                <th>Description</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= $log['user_name'] ?></td>
                    <td><?= $log['action_type'] ?></td>
                    <td><?= $log['field_name'] ?></td>
                    <td><?= $log['old_value'] ?></td>
                    <td><?= $log['new_value'] ?></td>
                    <td><?= $log['description'] ?></td>
                    <td><?= $log['timestamp'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}
?>

*/
