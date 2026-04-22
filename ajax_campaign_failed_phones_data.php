<?php
include_once('helpers/DataController.php');
$dataObj = new DataController();

// Safely read POST values
$draw        = intval($_POST['draw'] ?? 0);
$start       = intval($_POST['start'] ?? 0);
$length      = intval($_POST['length'] ?? 10);
$campaign_id = $_POST['campaign_id'] ?? null;
$type        = $_POST['type'] ?? 'overview';

// Static project ID
$projectID = "68abf5ca7d30730c67382ce8";

$masterCampaign = $dataObj->getNationalCampaignBroadcast();

// Get latest campaign failed records
// $latest     = $dataObj->getLatestCampaignData();
// $latestID   = $latest['id'] ?? null;
// $latestFail = $dataObj->getFailedCategoryPhones($projectID, $latestID, "FAILED");

$sqlLatestCampaign = $dataObj->getLatestCronCampaign('campaign_id');
$latestData  = $dataObj->getCampaignDetail($projectID,$sqlLatestCampaign);
$latestID   = $latestData['id'] ?? null;
$latestFail = $dataObj->getFailedCategoryPhones($projectID, $latestID, "FAILED");

// echo "<pre>";
// print_r($latestFail);   
// exit;

// Fetch full category data
$allOverview = $dataObj->getFailedCategoryPhones($projectID, $campaign_id, "ALL");
$allRecords  = $dataObj->getFailedCategoryPhones($projectID, $campaign_id);
$sentRecords = $dataObj->getFailedCategoryPhones($projectID, $campaign_id, "SENT");

// Decide dataset based on user selection
$records = ($type === "failed") ? $latestFail : $allOverview;
$campaignNameLabel = ($type === "failed")
                        ? ($latestData['name'] ?? '')
                        : ($masterCampaign['name'] ?? '');



$totalRecords = $records['total'] ?? 0;
$rows = $records['data'] ?? [];

// Pagination slice
$pagedRows = array_slice($rows, $start, $length);

// Prepare final rows
$final = [];
$sno = $start + 1;

foreach ($pagedRows as $i => $row) {

    // Format ISO time → readable IST format
    $failedAt = null;
    if (!empty($row['failedAt'])) {
        $dt = new DateTime($row['failedAt']);
        $dt->setTimezone(new DateTimeZone('Asia/Kolkata'));
        $failedAt = $dt->format("Y-m-d H:i:s");
    }

    
    $final[] = [
        "sno"           => ++$i,
        "id"            => $row['id'] ?? null,
        "campaign_id"   => $row['campaignId'] ?? null,
        "user_number"   => $row['userNumber'] ?? null,
        "user_name"     => $row['userName'] ?? null,
        "failed_reason" => $row['failurePayload']['error_data']['details'] ?? null,
        "failedAt"      => $failedAt,
        
    ];
}

// Final JSON response
echo json_encode([
    "draw"               => $draw,
    "recordsTotal"       => $totalRecords,
    "recordsFiltered"    => $totalRecords,
    "data"               => $final,
    "totalCount"         => $allRecords['total'] ?? 0,
    "sentCount"          => $sentRecords['total'] ?? 0,
    "latestFailedCount"  => $latestFail['total'] ?? 0,
    "latestFailedData"   => $latestFail['data'] ?? [],
    "page_label"         => $campaignNameLabel
]);

exit;
?>
