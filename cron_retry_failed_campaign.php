<?php
include_once('helpers/DataController.php');
$dataObj = new DataController();

include("includes/include.php");
require 'vendor/autoload.php';



$projectId    = "68abf5ca7d30730c67382ce8";
$templateName = "broadcast1"; // SAME template as parent campaign
$apiKey       = "a850dc5d98af7292567f1";

/* ---------------- STEP 1: FETCH PARENT CAMPAIGNS ---------------- */

// $parentCampaigns = db_query("
//     SELECT id, campaign_id, campaign_name
//     FROM tbl_mst_campaign
//     WHERE is_parent = 1
// ");

$parentCampaigns = db_query("
    SELECT 
        id,
        parent_campaign,
        campaign_id,
        campaign_name,
        parent_campaign_name
    FROM retry_campaign_master
    WHERE is_run = 0
");


/* ---------------- LOOP PARENT CAMPAIGNS ---------------- */

while ($parent = db_fetch_array($parentCampaigns)) {

   
    $parentCampaignId   = $parent['campaign_id'];
    $parentCampaignName = $parent['campaign_name'];
    $masterParentName = $parent['parent_campaign_name'] ?? $parent['campaign_name'];
    $masterCampaignId = $parent['parent_campaign'] ?? $parent['campaign_id'];


    /* STEP 2: FETCH FAILED NUMBERS FROM AiSensy */
    $failedNumbers = getFailedNumbersAISensy($projectId, $parentCampaignId, $apiKey);


    if (empty($failedNumbers)) {
        continue; // No failed numbers → skip
    }

   
    /* STEP 3: CREATE RETRY CAMPAIGN */
    // $retryCampaignName = $parentCampaignName . "-retry-" . date("Ymd-His");
    $retryCampaignName = $masterParentName . "-retry-" . date("Ymd-His");


    $createCampaign = createAISensyCampaign(
            $projectId,
            $templateName,
            $retryCampaignName
        );
     

    if (!$createCampaign['status']) {
        continue;
    }


    $newCampaignId = $createCampaign['response']['id'] ?? '';

    if (!$newCampaignId) {
        continue;
    }

    /* STEP 4: SAVE RETRY CAMPAIGN IN DB */
    db_query("
        INSERT INTO tbl_mst_campaign
        (campaign_id, campaign_name, parent_campaign, is_parent, is_run, created_at)
        VALUES
        ('{$newCampaignId}', '{$retryCampaignName}', '{$parentCampaignId}', 0, 0, NOW())
    ");
  

    foreach ($failedNumbers as $row) {

        $phone  = addslashes($row['userNumber']);
        $name   = addslashes($row['userName']);
        $reason = addslashes($row['reason']);

        /* INSERT INTO tbl_campaign_numbers */
        db_query("
            INSERT INTO tbl_campaign_numbers
            (campaign_id, phone_number, contact_name, retry_count, created_at)
            VALUES
            ('{$newCampaignId}', '{$phone}', '{$name}', 1, NOW())
        ");

        $lastId = get_insert_id();

        /* SEND MESSAGE */
        $send = sendAISensyMessage($projectId, $phone, $name, $retryCampaignName);

        if ($send['status']) {
            db_query("
                UPDATE tbl_campaign_numbers
                SET sent_at = NOW()
                   
                WHERE id = {$lastId}
            ");
        } else {
            db_query("
                UPDATE tbl_campaign_numbers
                SET retry_count = retry_count + 1
                  
                WHERE id = {$lastId}
            ");
        }
    }


     db_query("
        UPDATE tbl_mst_campaign
        SET is_run = 1
        WHERE campaign_id = '{$newCampaignId}'
    ");


       db_query("
        INSERT INTO retry_campaign_master
        (parent_campaign, campaign_id, campaign_name,parent_campaign_name, is_run, created_at)
        VALUES
        ('{$masterCampaignId}', '{$newCampaignId}', '{$retryCampaignName}','{$masterParentName}', 0, NOW())
      ");

       db_query("
        UPDATE retry_campaign_master
        SET is_run = 1, updated_at = NOW()
        WHERE campaign_id = '{$parentCampaignId}'
    ");

   echo "Retry campaign '{$retryCampaignName}' created with ID: {$newCampaignId}\n";


}



function createAISensyCampaign($projectId, $templateName, $campaignName)
{
    $apiUrl = "https://apis.aisensy.com/project-apis/v1/project/$projectId/campaign/api";
    $apiKey = "a850dc5d98af7292567f1";

    $payload = [
        'template_name' => $templateName,
        'campaign_name' => $campaignName
    ];

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Content-Type: application/json",
            "X-AiSensy-Project-API-Pwd: $apiKey"
        ],
    ]);

    $response = curl_exec($curl);
    $error    = curl_error($curl);

    curl_close($curl);

    if ($error) {
        return ['status' => false, 'error' => $error];
    }

    return [
        'status'   => true,
        'response' => json_decode($response, true)
    ];
}

/**
 * Fetch FAILED numbers from AiSensy
 */
function getFailedNumbersAISensy($projectId, $campaignId, $apiKey)
{
    $url = "https://apis.aisensy.com/project-apis/v1/project/$projectId/campaign/audience/$campaignId?category=FAILED&limit=1000";

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "X-AiSensy-Project-API-Pwd: $apiKey",
            "Accept-Charset: application/json"
        ]
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (empty($data['data'])) {
        return [];
    }

    $failed = [];

    foreach ($data['data'] as $item) {

        $reason = $item['failurePayload']['reason'] ?? '';

        // Retry only allowed reason
        if ($reason !== 'This message was not delivered to maintain healthy ecosystem engagement.') {
            continue;
        }

        $failed[] = [
            'userNumber' => $item['userNumber'] ?? '',
            'userName'   => $item['userName'] ?? '',
            'reason'     => $reason
        ];
    }

    return $failed;
}


function sendAISensyMessage($project_id, $phone, $name, $campaignName)
{
    $url = "https://apis.aisensy.com/project-apis/v1/project/$project_id/campaign/api/send";

    $postData = [
        'template_params' => [],
        'name' => $name,
        'phone_number' => $phone,
        'media' => [],
        'campaign_name' => $campaignName,
        'source' => 'organic',
        'attributes' => ['country' => 'India'],
        'default_country_code' => '91',
        'tags' => []
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($postData),
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Content-Type: application/json",
            "X-AiSensy-Project-API-Pwd: a850dc5d98af7292567f1"
        ]
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return ["status" => false, "error" => $error];
    }

    curl_close($ch);
    return ["status" => true, "response" => json_decode($response, true)];
}