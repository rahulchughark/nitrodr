<?php
include("includes/include.php");

require 'vendor/autoload.php';

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
        'attributes' => [
            'country' => 'India'
        ],
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

/* ---------------- MAIN ---------------- */

$campaignId   = $_POST['campaign_id'] ?? '';
$campaignName = $_POST['campaign_name'] ?? '';

if (!$campaignId || !$campaignName) {
    echo json_encode(['status' => false, 'message' => 'Campaign data missing']);
    exit;
}

$project_id = "68abf5ca7d30730c67382ce8";

/* Fetch pending users */
$sql = "
    SELECT id, phone_number, contact_name
    FROM tbl_campaign_numbers
    WHERE campaign_id = '".$campaignId."'
      AND sent_at IS NULL
";

$result = db_query($sql);

$sent = 0;
$failed = 0;

while ($row = db_fetch_array($result)) {

    $phone = $row['phone_number'];
    $name  = $row['contact_name'];

    $api = sendAISensyMessage($project_id, $phone, $name, $campaignName);

    if ($api['status']) {
            db_query("
                UPDATE tbl_campaign_numbers
                SET sent_at = NOW()
                 
                WHERE id = {$row['id']}
            ");
            $sent++;
        } else {
            db_query("
                UPDATE tbl_campaign_numbers
                SET retry_count = retry_count + 1
                 
                WHERE id = {$row['id']}
            ");
            $failed++;
        }
}

/* If all messages attempted, update campaign as run */
db_query("
    UPDATE tbl_mst_campaign
    SET is_run = 1
    WHERE campaign_id = '".$campaignId."'
");

echo json_encode([
    'status'  => true,
    'message' => "Campaign completed. Sent: {$sent}, Failed: {$failed}"
]);


