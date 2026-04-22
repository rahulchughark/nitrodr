<?php
include("includes/include.php");
admin_protect();

$product_id = (int)($_POST['product_id'] ?? 0);
$stage      = (int)($_POST['stage'] ?? 0);
$sub_stage  = (int)($_POST['sub_stage'] ?? 0);

if (!$product_id || !$stage || !$sub_stage) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid input'
    ]);
    exit;
}

$update = db_query("
    UPDATE tbl_lead_product_opportunity
    SET 
        stage = {$stage},
        sub_stage = {$sub_stage}
    WHERE id = {$product_id}
");

if ($update) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database update failed'
    ]);
}
