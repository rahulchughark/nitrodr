<?php
include("includes/include.php");
include_once 'helpers/DataController.php';
$modify_log = new DataController();
admin_protect();

$categoryId   = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
$partnerId    = isset($_POST['partner_id']) ? (int)$_POST['partner_id'] : 0;
$checked      = isset($_POST['checked']) ? (int)$_POST['checked'] : 0;
$categoryName = isset($_POST['category_name']) ? trim($_POST['category_name']) : '';

if ($categoryId <= 0 || $partnerId <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid data"
    ]);
    exit;
}

/* 🔹 Get learning_zone by category_id */
$check = db_query("
    SELECT id, partner_access, users_access
    FROM learning_zone
    WHERE category_id = {$categoryId}
      AND status = 1
      AND delete_date IS NULL
    LIMIT 1
");

if (mysqli_num_rows($check) === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "No Folder is created with the name '{$categoryName}'. Please create the Folder first."
    ]);
    exit;
}

$row    = db_fetch_array($check);
$zoneId = (int)$row['id'];



/* 🔹 Update partner_access array */
$partners = array_filter(explode(',', $row['partner_access']));

if ($checked) {
    if (!in_array($partnerId, $partners)) {
        $partners[] = $partnerId;
    }
} else {
    $partners = array_diff($partners, [$partnerId]);
}

$newPartnerAccess = implode(',', $partners);


/* 🔹 Update learning_zone */
$updateZone = db_query("
    UPDATE learning_zone
    SET partner_access = '{$newPartnerAccess}'
    WHERE id = {$zoneId}
");

/* 🔹 Fetch attachment data BEFORE update (for logging) */
$attachmentsRes = db_query("
    SELECT id, partner_access
    FROM learning_zone_attachment
    WHERE zone_id = {$zoneId}
      AND deleted = 0
");

$oldData = [];
while ($att = db_fetch_array($attachmentsRes)) {
    $oldData[$att['id']] = [
        'partner_access' => $att['partner_access']
    ];
}

/* 🔹 ALSO update learning_zone_attachment (multiple rows) */
$updateAttachments = db_query("
    UPDATE learning_zone_attachment
    SET partner_access = '{$newPartnerAccess}'
    WHERE zone_id = {$zoneId}
      AND deleted = 0
");


/* 🔹 Create attachment-wise logs */
if ($updateAttachments && !empty($oldData)) {
    foreach ($oldData as $attachmentId => $data) {

        // Skip log if value not changed
        if ((string)$data['partner_access'] === (string)$newPartnerAccess) {
            continue;
        }

        $modify_log->logLearningZoneAttachmentChange([
            'attachment_id' => $attachmentId,
            'field_name'    => 'partner_access',
            'old_value'     => $data['partner_access'],
            'new_value'     => $newPartnerAccess,
            'action_type'   => 'Partner_WISE_PERMISSION'
        ]);
    }
}

if ($updateZone && $updateAttachments) {
    echo json_encode([
        "status"  => "success",
        "message" => "Partner access updated successfully"
    ]);
} else {
    echo json_encode([
        "status"  => "error",
        "message" => "Failed to update access"
    ]);
}
exit;
