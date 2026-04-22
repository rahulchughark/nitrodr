<?php
include_once('helpers/DataController.php');
$dataObj = new DataController();
$projectID = "68abf5ca7d30730c67382ce8";
// Read DataTables post input
$draw   = $_POST['draw'];
$start  = $_POST['start'];
$length = $_POST['length'];

// Fetch ONLY the National Campaign Broadcast campaign
$campaign = $dataObj->getNationalCampaignBroadcast();

// echo "<pre>";
// print_r($campaign);
// exit;

// Convert to list so DataTables works
$allData = $campaign ? [$campaign] : [];

$totalRecords = count($allData);

// Slice (unnecessary for 1 record but required for DataTables)
$data = array_slice($allData, $start, $length);

// Prepare rows
$final = [];
$sno = $start + 1;


$latest     = $dataObj->getLatestCampaignData();
$latestID   = $latest['id'] ?? null;
$latestFail = $dataObj->getFailedCategoryPhones($projectID, $latestID, "FAILED");

foreach ($data as $row) {

  

    $viewButton = "<a href='view-campaign.php?project_id={$row['project_id']}&campaign_id={$row['id']}'
        class='btn btn-sm btn-primary'>
        View
    </a>";
    $templateText = $row['message_payload']['template']['text'] ?? '';
    $words = explode(' ', $templateText);

    $shortText = implode(' ', array_slice($words, 0, 20));
    $fullText  = $templateText;

    $final[] = [
        "sno"             => $sno++,
        // "project_id"      => $row['project_id'] ?? '',
        // "campaign_id"     => $row['id'] ?? '',
        "campaign_name"   => $row['name'] ?? '',                 // Updated key
        // "template"        => $campaign['message_payload']['template']['name'] ?? null,
        // "campaign_status" => $row['status'] ?? '',
        // "message_type"    => $row['message_type'] ?? '',
        // "campaign_type"   => $row['type'] ?? '',
        "audience_size"   => $row['audience_size'] ?? '',
        "latest_fail"     =>  $latestFail['total'] ?? 0,
        "total_sent"     =>  ($row['audience_size'] ?? 0) - ($latestFail['total'] ?? 0),
        "created_at" => isset($row['created_at'])
                        ? date("Y-m-d H:i:s", $row['created_at'] / 1000)
                        : '',
        "template_text" => "
            <span class='short-text'>{$shortText}...</span>
            <span class='full-text d-none'>{$fullText}</span>
            <a href='javascript:void(0)' class='toggle-text text-primary'>Show more</a>
        ",
        "action_btn"      => $viewButton
    ];
}

// Output JSON for DataTables
echo json_encode([
    "draw"            => intval($draw),
    "recordsTotal"    => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data"            => $final
]);
exit;
?>
