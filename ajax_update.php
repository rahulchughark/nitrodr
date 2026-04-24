<?php
include('includes/include.php');

header('Content-Type: application/json');

include_once('helpers/DataController.php');
$dataObj = new DataController;


if (isset($_POST['action']) && $_POST['action'] == 'get_partner_users') {
header('Content-Type: text/html; charset=UTF-8');

$partnerId = isset($_POST['partner_id']) ? (int)$_POST['partner_id'] : 0;
$selectedUser = isset($_POST['selected_user']) ? trim((string)$_POST['selected_user']) : '';

echo '<option value="">---Select---</option>';

if ($partnerId <= 0) {
    exit;
}

$users = db_query("SELECT id, name FROM users WHERE team_id='" . $partnerId . "' AND status='Active' ORDER BY name ASC");
while ($user = db_fetch_array($users)) {
    $userId = (string)$user['id'];
    $selected = ($selectedUser !== '' && $selectedUser === $userId) ? ' selected="selected"' : '';
    echo '<option value="' . $userId . '"' . $selected . '>' . htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . '</option>';
}

exit;
}

if (isset($_POST['action']) && $_POST['action'] == 'get_partner_users_by_partners') {
header('Content-Type: text/html; charset=UTF-8');

$partnerIds = [];
if (isset($_POST['partner_ids'])) {
    if (is_array($_POST['partner_ids'])) {
        foreach ($_POST['partner_ids'] as $partnerId) {
            $partnerId = (int)$partnerId;
            if ($partnerId > 0) {
                $partnerIds[] = $partnerId;
            }
        }
    } else {
        $rawPartnerIds = explode(',', (string)$_POST['partner_ids']);
        foreach ($rawPartnerIds as $partnerId) {
            $partnerId = (int)trim($partnerId);
            if ($partnerId > 0) {
                $partnerIds[] = $partnerId;
            }
        }
    }
}

$selectedUsers = [];
if (isset($_POST['selected_users'])) {
    if (is_array($_POST['selected_users'])) {
        foreach ($_POST['selected_users'] as $selectedUser) {
            $selectedUser = (string)(int)$selectedUser;
            if ($selectedUser !== '0') {
                $selectedUsers[] = $selectedUser;
            }
        }
    } else {
        $rawSelectedUsers = explode(',', (string)$_POST['selected_users']);
        foreach ($rawSelectedUsers as $selectedUser) {
            $selectedUser = (string)(int)trim($selectedUser);
            if ($selectedUser !== '0') {
                $selectedUsers[] = $selectedUser;
            }
        }
    }
}

$partnerIds = array_values(array_unique($partnerIds));
$selectedUsers = array_values(array_unique($selectedUsers));

echo '<option value="">---Select---</option>';

if (empty($partnerIds)) {
    exit;
}

$users = db_query("SELECT id, name FROM users WHERE status='Active' AND team_id IN (" . implode(',', $partnerIds) . ") ORDER BY name ASC");
while ($user = db_fetch_array($users)) {
    $userId = (string)$user['id'];
    $selected = in_array($userId, $selectedUsers, true) ? ' selected="selected"' : '';
    echo '<option value="' . $userId . '"' . $selected . '>' . htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . '</option>';
}

exit;
}

if (isset($_POST['action']) && $_POST['action'] == 'update_user_status') {
    if (!in_array($_SESSION['user_type'], array('ADMIN', 'SUPERADMIN'))) {
        echo json_encode(array('success' => false, 'message' => 'Unauthorized request.'));
        exit;
    }

    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $new_status_input = isset($_POST['status']) ? trim($_POST['status']) : '';
    $new_status = ($new_status_input == 'Active') ? 'Active' : 'Inactive';

    if ($user_id <= 0) {
        echo json_encode(array('success' => false, 'message' => 'Invalid user id.'));
        exit;
    }

    $updated = db_query("update users set status='" . $new_status . "' where id=" . $user_id);

    if ($updated) {
        echo json_encode(array('success' => true, 'status' => $new_status));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Unable to update status.'));
    }
    exit;
}


// zone_file_name_update
if(isset($_POST['type']) && $_POST['type'] == "add_group"){

$name = trim($_POST['name']);
if ($name != '') {
    db_query("INSERT INTO tbl_mst_group (name, created_by) VALUES ('$name', '".$_SESSION['user_id']."')");
    $id = get_insert_id();
    echo json_encode(['id' => $id]);
}

exit;

}elseif($_POST['type']){
   
if (!empty($_POST['id']) && !empty($_POST['file_name'])) {
    $id = intval($_POST['id']);
    $fileName = trim($_POST['file_name']);
    $is_parent = trim($_POST['is_parent']);

    if($is_parent){
    // Update query
    $update = db_query("UPDATE learning_zone 
                        SET file_name = '" .  $fileName . "' 
                        WHERE id = " . $id);

    }else{
    // Update query
    $update = db_query("UPDATE learning_zone_attachment 
                        SET file_name = '" .  $fileName . "' 
                        WHERE id = " . $id);

    }
    

    if ($update) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database update failed"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
}
}elseif(isset($_POST['action']) && $_POST['action'] == 'convert_to_opportunity' && isset($_POST['lead_id'])){
    // Handle convert lead to opportunity request
    $lead_id = intval($_POST['lead_id']);
    
    if($lead_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid lead ID"]);
        exit;
    }
    
    $oldOpportunity = (int)getSingleresult("SELECT is_opportunity FROM orders WHERE id='".$lead_id."'");
    
    // Update the is_opportunity field from 0 to 1
    $update_query = db_query("UPDATE orders SET is_opportunity = 1 WHERE id = '".$lead_id."'");
    
    if($update_query) {
        if ($oldOpportunity !== 1) {
            $createdBy = (int)($_SESSION['user_id'] ?? 0);
            db_query("INSERT INTO lead_modify_log (`lead_id`, `type`, `stage`, `previous_name`, `modify_name`, `created_date`, `created_by`, `log_status`, `timestamp`, `created_by_clm`) VALUES ('".$lead_id."', 'Opportunity', NULL, 'Lead', 'Opportunity', NOW(), '".$createdBy."', 'Active', NOW(), '0')");
        }

        echo json_encode([
            "status" => "success", 
            "message" => "Lead successfully converted to opportunity!"
        ]);
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => "Failed to convert lead. Please try again."
        ]);
    }
} elseif(isset($_POST['action']) && $_POST['action'] == 'update_approval' && isset($_POST['lead_id']) && isset($_POST['is_approved'])){
    // Only allowed roles can update approval
    if (in_array(($_SESSION['user_type'] ?? ''), ['USR', 'CLR', 'MNGR', 'MNG'], true)) {
        echo json_encode([
            "status" => "error",
            "message" => "You do not have permission to approve leads"
        ]);
        exit;
    }

    $lead_id = intval($_POST['lead_id']);
    $raw_status = isset($_POST['is_approved']) ? intval($_POST['is_approved']) : -1;

    // Accept only 0,1,2,3
    $allowed = [0,1,2,3];
    if (!in_array($raw_status, $allowed, true)) {
        echo json_encode(["status" => "error", "message" => "Invalid approval status"]);
        exit;
    }

    if($lead_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid lead ID"]);
        exit;
    }

    $old_status = (int)getSingleresult("SELECT is_approved FROM orders WHERE id='".$lead_id."'");
    $is_approved = $raw_status;

    if ($old_status === 1 && $is_approved !== 1) {
        echo json_encode([
            "status" => "error",
            "message" => "Approval status cannot be changed after Approved"
        ]);
        exit;
    }

    if ($is_approved === 1) {
        $update_query = db_query("UPDATE orders SET is_approved='".$is_approved."', close_date = DATE_ADD(CURDATE(), INTERVAL 30 DAY) WHERE id='".$lead_id."'");
    } else {
        $update_query = db_query("UPDATE orders SET is_approved='".$is_approved."' WHERE id='".$lead_id."'");
    }

    if($update_query) {
        if($old_status !== $is_approved) {
            $map = [0 => 'Pending', 1 => 'Approved', 2 => 'Rejected', 3 => 'Onhold'];
            $previousName = isset($map[$old_status]) ? $map[$old_status] : (string)$old_status;
            $modifyName = isset($map[$is_approved]) ? $map[$is_approved] : (string)$is_approved;
            $createdBy = (int)($_SESSION['user_id'] ?? 0);

            db_query("INSERT INTO lead_modify_log (`lead_id`, `type`, `stage`, `previous_name`, `modify_name`, `created_date`, `created_by`, `log_status`, `timestamp`, `created_by_clm`) VALUES ('".$lead_id."', 'Approval', NULL, '".$previousName."', '".$modifyName."', NOW(), '".$createdBy."', 'Active', NOW(), '0')");

            // Email notification to the lead creator for approval status change.
            $leadMailQ = db_query("SELECT o.id, o.customer_company_name, o.customer_name, o.product, o.number_of_licenses, o.created_by, u.email AS creator_email, u.name AS creator_name FROM orders o LEFT JOIN users u ON u.id=o.created_by WHERE o.id='".$lead_id."' LIMIT 1");
            $leadMailData = $leadMailQ ? db_fetch_array($leadMailQ) : null;

            $creatorEmail = trim((string)($leadMailData['creator_email'] ?? ''));
            if (filter_var($creatorEmail, FILTER_VALIDATE_EMAIL)) {
                $updatedByName = trim((string)($_SESSION['name'] ?? ''));
                if ($updatedByName === '' && $createdBy > 0) {
                    $updatedByName = trim((string)getSingleresult("SELECT name FROM users WHERE id='".$createdBy."' LIMIT 1"));
                }

                $mailPayload = [
                    'lead_id' => (int)($leadMailData['id'] ?? $lead_id),
                    'creator_name' => (string)($leadMailData['creator_name'] ?? 'User'),
                    'company_name' => (string)($leadMailData['customer_company_name'] ?? 'N/A'),
                    'customer_name' => (string)($leadMailData['customer_name'] ?? 'N/A'),
                    'product_name' => (string)($leadMailData['product'] ?? 'N/A'),
                    'licenses' => (string)($leadMailData['number_of_licenses'] ?? 'N/A'),
                    'previous_status' => $previousName,
                    'current_status' => $modifyName,
                    'updated_by' => ($updatedByName !== '' ? $updatedByName : 'System'),
                    'updated_at' => date('d-m-Y h:i A')
                ];

                $setSubject = "Lead Approval Status Updated [#".$lead_id."]";
                $mailBody = $dataObj->buildLeadApprovalStatusEmailTemplate($mailPayload);
                
                // Keep AJAX response JSON clean even if mailer writes warnings/notices.
                ob_start();
                sendMailReminder($creatorEmail, $setSubject, $mailBody);
                ob_end_clean();
            }
        }

        echo json_encode([
            "status" => "success",
            "message" => "Approval status updated successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to update approval status"
        ]);
    }
} elseif(isset($_POST['action']) && $_POST['action'] == 'update_stage' && isset($_POST['lead_id']) && isset($_POST['stage_id'])){
    $lead_id = intval($_POST['lead_id']);
    $stage_id = intval($_POST['stage_id']);

    if($lead_id <= 0 || $stage_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid lead or stage"]);
        exit;
    }

    $stage_exists = (int)getSingleresult("SELECT COUNT(*) FROM tbl_mst_stage WHERE id='".$stage_id."'");
    if($stage_exists <= 0) {
        echo json_encode(["status" => "error", "message" => "Selected stage not found"]);
        exit;
    }

    $oldStageId = (int)getSingleresult("SELECT stage_id FROM orders WHERE id='".$lead_id."'");

    $update_query = db_query("UPDATE orders SET stage_id='".$stage_id."' WHERE id='".$lead_id."'");

    if($update_query) {
        if ($oldStageId !== $stage_id) {
            $previousName = trim((string)getSingleresult("SELECT name FROM tbl_mst_stage WHERE id='".$oldStageId."'"));
            $modifyName = trim((string)getSingleresult("SELECT name FROM tbl_mst_stage WHERE id='".$stage_id."'"));
            $previousName = $previousName !== '' ? $previousName : 'N/A';
            $modifyName = $modifyName !== '' ? $modifyName : (string)$stage_id;
            $createdBy = (int)($_SESSION['user_id'] ?? 0);

            db_query("INSERT INTO lead_modify_log (`lead_id`, `type`, `stage`, `previous_name`, `modify_name`, `created_date`, `created_by`, `log_status`, `timestamp`, `created_by_clm`) VALUES ('".$lead_id."', 'Stage', NULL, '".$previousName."', '".$modifyName."', NOW(), '".$createdBy."', 'Active', NOW(), '0')");
        }

        echo json_encode([
            "status" => "success",
            "message" => "Stage updated successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to update stage"
        ]);
    }
} elseif(isset($_POST['action']) && $_POST['action'] == 'update_opportunity' && isset($_POST['lead_id']) && isset($_POST['is_opportunity'])){
    if (($_SESSION['user_type'] ?? '') === 'USR') {
        echo json_encode([
            "status" => "error",
            "message" => "You do not have permission to update opportunity"
        ]);
        exit;
    }

    $lead_id = intval($_POST['lead_id']);
    $is_opportunity = intval($_POST['is_opportunity']) === 1 ? 1 : 0;

    if($lead_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid lead ID"]);
        exit;
    }

    $oldOpportunity = (int)getSingleresult("SELECT is_opportunity FROM orders WHERE id='".$lead_id."'");

    $update_query = db_query("UPDATE orders SET is_opportunity='".$is_opportunity."' WHERE id='".$lead_id."'");

    if($update_query) {
        if($oldOpportunity !== $is_opportunity) {
            $previousName = ($oldOpportunity === 1) ? 'Opportunity' : 'Lead';
            $modifyName = ($is_opportunity === 1) ? 'Opportunity' : 'Lead';
            $createdBy = (int)($_SESSION['user_id'] ?? 0);

            db_query("INSERT INTO lead_modify_log (`lead_id`, `type`, `stage`, `previous_name`, `modify_name`, `created_date`, `created_by`, `log_status`, `timestamp`, `created_by_clm`) VALUES ('".$lead_id."', 'Opportunity', NULL, '".$previousName."', '".$modifyName."', NOW(), '".$createdBy."', 'Active', NOW(), '0')");
        }

        echo json_encode([
            "status" => "success",
            "message" => "Opportunity status updated successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to update opportunity status"
        ]);
    }
}

?>
