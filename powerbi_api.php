<?php

$tenantId = "1c087d74-971b-4892-a328-e1c9a57d3780";
$clientId = "64b49731-caaa-4011-9a25-152932c5ee10";
$clientSecret = "YOUR_CLIENT_SECRET";
$workspaceId = "8ec31f95-4162-44ba-a13a-c2652df86380";
$reportId = "f8d31976-f3da-40ee-aa1a-bbc52ce0a80f";


// ==========================
// STEP 1: Get Access Token
// ==========================
$tokenUrl = "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token";

$postData = http_build_query([
    'grant_type' => 'client_credentials',
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'scope' => 'https://analysis.windows.net/powerbi/api/.default'
]);

$ch = curl_init($tokenUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (!isset($data['access_token'])) {
    echo $response;
    exit;
}

$accessToken = $data['access_token'];


// ==========================
// STEP 2: Get Report Details
// ==========================
$reportDetailsUrl = "https://api.powerbi.com/v1.0/myorg/groups/$workspaceId/reports/$reportId";

$ch = curl_init($reportDetailsUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken"
]);

$response = curl_exec($ch);
curl_close($ch);

$reportData = json_decode($response, true);

if (!isset($reportData['embedUrl'])) {
    echo "REPORT API ERROR:\n";
    print_r($reportData);
    exit;
}

$embedUrl = $reportData['embedUrl']; // ✅ Correct embed URL


// ==========================
// STEP 3: Generate Embed Token
// ==========================
$generateTokenUrl = "https://api.powerbi.com/v1.0/myorg/groups/$workspaceId/reports/$reportId/GenerateToken";

$payload = json_encode([
    "accessLevel" => "View"
]);

$ch = curl_init($generateTokenUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

$response = curl_exec($ch);
curl_close($ch);

$embedData = json_decode($response, true);

if (!isset($embedData['token'])) {
    echo "TOKEN API ERROR:\n";
    print_r($embedData);
    exit;
}


// ==========================
// FINAL OUTPUT
// ==========================
echo json_encode([
    "embedToken" => $embedData['token'],
    "embedUrl" => $embedUrl,
    "reportId" => $reportId
]);

?>