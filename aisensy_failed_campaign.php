<?php
include_once ('helpers/DataController.php');
$projectID = "68abf5ca7d30730c67382ce8";

$dataObj = new DataController();

$campaigns = $dataObj->getAISensyCampaignDetail($projectID);

// echo "<pre>";
// print_r($campaigns);
// return ;

// Check if any campaigns were returned
if (empty($campaigns)) {
    echo "⚠️ No campaigns found for project ID: $projectID<br>";
    exit;
}

// $abcd = $dataObj->sendAISensyMessage($projectID, "9319422021", "Test Name", "manual parent campaign");
// echo "<pre>";
// print_r($abcd);
// exit;


$templateCampaign = [];

foreach ($campaigns as $i => $data) {    
    
    $campaign = $data['id']??null;
    $campaignName = $data['name']??null;
    $campaign_type = $data['campaign_type']??null;
    $campaign_status = $data['campaign_status']??null;
    $message_type = $data['message_type']??null;
    $audience_size = $data['audience_size']??null;
    $template = $data['template']??null;

    $arr = ($arr == $template) ? true : false;
    $campaignID = $campaign??null;

    if (empty($campaignID)) {
        echo "Invalid campaign data detected, skipping...<br>";
        continue;
    }

    $checkQuery = "
                SELECT id
                FROM processed_campaigns 
                WHERE project_id = '$projectID' 
                AND campaign_id = '$campaignID'
                LIMIT 1";

    $checkResult = db_query($checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        echo "Campaign ID $campaignID already processed. Skipping...<br>";
        continue;
    }
    // // Process failed numbers
    $dataObj->saveFailedNumbersAISensy($projectID, $campaignID);

    // // Insert record so next time it's not processed again
    $insertQuery = "
        INSERT INTO processed_campaigns (project_id, campaign_id, campaign_name,template,campaign_status,message_type,campaign_type,audience_size, created_at)
        VALUES ('$projectID', '$campaignID','$campaignName','$template','$campaign_status','$message_type','$campaign_type','$audience_size', NOW())
    ";
    db_query($insertQuery);
    echo "Campaign ID/Name $campaignID($campaignName) processed and marked as completed.<br>";
    // $templateCampaign[$template] = $campaignName;  
    $templateCampaign[$template] = "Retry Campaign";  
}


// print_r($templateCampaign);
// die("done");
foreach ($templateCampaign as $template => $campaign) {
    $send = $dataObj->sendBulkFailedUsersMessage($projectID, $template, $campaign);
}