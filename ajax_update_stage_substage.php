<?php
include("includes/include.php");
include_once('helpers/DataController.php');
$dataObj = new DataController();
admin_protect();

$product_id = (int)($_POST['product_id'] ?? 0);
$stage      = (int)($_POST['stage'] ?? 0);
$sub_stage  = (int)($_POST['sub_stage'] ?? 0);

if (!$product_id || !$stage) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

// Get stage name to check for sub-stages
$stage_name = getSingleresult("SELECT stage_name FROM stages WHERE id = {$stage}");
$subStagesCount = (int)getSingleresult("SELECT COUNT(*) FROM sub_stage WHERE stage_name = '{$stage_name}'");

if ($subStagesCount > 0 && !$sub_stage) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please select a Sub Stage'
    ]);
    exit;
}

$oldData = db_fetch_array(db_query("
    SELECT stage, sub_stage
    FROM tbl_lead_product_opportunity
    WHERE id = {$product_id}
"));

$update = db_query("
    UPDATE tbl_lead_product_opportunity
    SET 
        stage = {$stage},
        sub_stage = {$sub_stage}
    WHERE id = {$product_id}
");

if ($update) {

    $dataObj->logLeadStageSubStageChange([
        'lead_product_opportunity_id' => $product_id,
        'old_stage'     => $oldData['stage'] ?? null,
        'new_stage'     => $stage,
        'old_sub_stage' => $oldData['sub_stage'] ?? null,
        'new_sub_stage' => $sub_stage,
        'remarks'       => 'Stage/Sub-stage updated'
    ]);

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database update failed'
    ]);
}
