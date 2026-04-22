<?php 

include("includes/include.php"); 
include_once('helpers/DataController.php');
$dataObj = new DataController;

$attachmentId = (int)($_POST['attachment_id'] ?? 0);
$partnerId    = (int)($_POST['partner_id'] ?? 0);
$action       = $_POST['action'] ?? 'add';

if (!$attachmentId || !$partnerId) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid input'
    ]);
    exit;
}


$current = getSingleresult("
    SELECT partner_access 
    FROM learning_zone_attachment 
    WHERE id = {$attachmentId}
");

$partners = array_filter(array_map('intval', explode(',', $current)));

/* 2️⃣ ADD / REMOVE LOGIC */
if ($action === 'add') {

    if (in_array($partnerId, $partners)) {
        echo json_encode([
            'status' => 'info',
            'message' => 'Partner already assigned'
        ]);
        exit;
    }

    $partners[] = $partnerId;

} else {

    $partners = array_diff($partners, [$partnerId]);
}

/* 3️⃣ Save updated list */
$newValue = implode(',', $partners);

$update = db_query("
    UPDATE learning_zone_attachment
    SET partner_access = '" . mysqli_real_escape_string($GLOBALS['dbcon'], $newValue) . "'
    WHERE id = {$attachmentId}
");

if ($update) {
    echo json_encode([
        'status' => 'success',
        'message' => ($action === 'add')
            ? 'Partner access added'
            : 'Partner access removed'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Update failed'
    ]);
}
