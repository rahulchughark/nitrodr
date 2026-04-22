<?php
include("includes/include.php");

$category_id    = $_POST['category_id'] ?? null;
$category_name  = $_POST['category_name'] ?? null; // optional, kept if UI sends it
$attachment_ids = $_POST['zone_attached_id'] ?? [];  // array: [32,31]

if (!$category_id || empty($attachment_ids) || !is_array($attachment_ids)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid parameters'
    ]);
    exit;
}

/* ---------------- STEP 1: FIND LEARNING ZONE BY CATEGORY ---------------- */

$zoneRes = db_query("
    SELECT id
    FROM learning_zone
    WHERE category_id = '".intval($category_id)."'
    LIMIT 1
");

if (mysqli_num_rows($zoneRes) == 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Learning zone not found for selected category'
    ]);
    exit;
}

$zoneRow = db_fetch_array($zoneRes);
$zone_id = $zoneRow['id'];

/* ---------------- STEP 2: UPDATE ATTACHMENTS ---------------- */

$attachment_ids = array_map('intval', $attachment_ids);
$idList = implode(',', $attachment_ids);

$update = db_query("
    UPDATE learning_zone_attachment
    SET zone_id = '".intval($zone_id)."'
    WHERE id IN ($idList)
");

/* ---------------- RESPONSE ---------------- */

if ($update) {
    echo json_encode([
        'status' => 'success',
        'zone_id' => $zone_id
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Attachment update failed'
    ]);
}
