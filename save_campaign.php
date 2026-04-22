<?php
include_once("helpers/DataController.php");
$dataObj = new DataController();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => false,
        "message" => "Invalid request method."
    ]);
    exit;
}

$projectId      = "68abf5ca7d30730c67382ce8";
$campaignName   = $_POST['campaign_name'] ?? "";
$templateId     = $_POST['template_id'] ?? "";
$isParent     = $_POST['is_parent'] ?? "";
$parentCampaign     = $_POST['parent_campaign'] ?? "";


// Validate required fields
if (empty($projectId) || empty($campaignName) || empty($templateId)) {
    echo json_encode([
        "status" => false,
        "message" => "All fields are required."
    ]);
    exit;
}

$templates = $dataObj->getAISensyTemplates($projectId, "a850dc5d98af7292567f1");

// Find template name from ID
$templateName = "";
foreach ($templates as $tpl) {
    if ($tpl["id"] == $templateId) {
        $templateName = $tpl["name"];
        break;
    }
}

if (empty($templateName)) {
    echo json_encode([
        "status" => false,
        "message" => "Template not found."
    ]);
    exit;
}


// echo "<pre>";
// print_r($_POST);
// exit;



// -------------------------------------------------
// 3. Create Campaign in AI Sensy
// -------------------------------------------------
// $campaignName = $campaignName.rand(1000,9999);
$result = $dataObj->createAiSensyManually($projectId, $templateName, $campaignName,$isParent,$parentCampaign);

// echo "<pre>";
// print_r($result);
// exit;

// API Error?
if (!$result['status']) {
    echo json_encode([
        "status"  => false,
        "message" => "API Error: " . $result['error']
    ]);
    exit;
}

// API Success Response
$response = $result['response'];

// -------------------------------------------------
// 4. Return JSON Success
// -------------------------------------------------
echo json_encode([
    "status"   => true,
    "message"  => "Campaign created successfully!",
    "api_data" => $response
]);

exit;
?>
