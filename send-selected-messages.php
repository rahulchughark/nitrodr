<?php
include_once('helpers/DataController.php');
$dataObj = new DataController();

$projectId = "68abf5ca7d30730c67382ce8";

$phoneIds     = $_POST['phone_ids'] ?? [];
$campaignName = $_POST['campaign_name'] ?? '';
$campaignID = $dataObj->getCampaignByCampaignName($campaignName,'campaign_id');


if (empty($phoneIds) || empty($campaignName) || empty($campaignID)) {
    echo json_encode([
        'status' => false,
        'message' => 'Invalid input'
    ]);
    exit;
}

/* ---------------- FETCH CONTACTS ---------------- */
$ids = array_map('intval', $phoneIds);
$idList = implode(',', $ids);

$sql = db_query("
    SELECT id, contact_name, code, phone_number
    FROM tbl_campaign_numbers
    WHERE id IN ($idList)
");

$success = 0;
$failed  = 0;

while ($row = db_fetch_array($sql)) {

    // $phone = $row['code'] . $row['phone_number'];
    $phone =  $row['phone_number'];
    $name  = $row['contact_name'];

    $response = $dataObj->sendAISensyMessage(
        $projectId,
        $phone,
        $name,
        $campaignName
    );


    db_query("
            INSERT INTO tbl_campaign_numbers
            (campaign_id, phone_number, contact_name, retry_count, created_at,sent_at, sent_from)
            VALUES
            ('{$campaignID}', '{$phone}', '{$name}', 1, NOW(), NOW(), 'campaign-content-page')
        ");

    
    if ($response['status']) {
        $success++;
    } else {
        $failed++;
    }
}

echo json_encode([
    'status'  => true,
    'message' => "Messages sent. Success: $success, Failed: $failed"
]);
exit;
