<?php
include("includes/include.php");
require 'vendor/autoload.php';
include_once('helpers/DataController.php');

$dataObj = new DataController;


// This section helps to add new category   
if (isset($_POST['category_name'])) {
    $name = trim($_POST['category_name']);

    if ($name === '') {
        echo json_encode(['status' => false]);
        exit;
    }

    $sql = "
        INSERT INTO categories_campaign (category_name)
        VALUES ('{$name}')
    ";
    db_query($sql);

    echo json_encode([
        'status'        => true,
        'id'            => get_insert_id(),
        'category_name' => $name
    ]);
    exit;
}


// This section helps to add new tag
if (isset($_POST['tag_name'])) {
    $name = trim($_POST['tag_name']);

    if ($name === '') {
        echo json_encode(['status' => false]);
        exit;
    }

    db_query("
        INSERT INTO campaign_mst_tags (tags)
        VALUES ('{$name}')
    ");

    echo json_encode([
        'status'   => true,
        'id'       => get_insert_id(),
        'tag_name' => $name
    ]);
    exit;
}


// 
if(isset($_POST['create_template'])){

// Prepare payload
$payload = [
    'label'       => $_POST['label'] ?? '',
    'name'        => $_POST['name'] ?? '',
    'category'    => $_POST['category'] ?? '',
    'language'    => $_POST['language'] ?? '',
    'type'        => $_POST['type'] ?? 'TEXT',
    'text'        => $_POST['text'] ?? '',
    'sample_text' => $_POST['sample_text'] ?? '',
];

$ch = curl_init();
$projectId = $_POST['projectId'] ?? '';
$projectApiPwd = $_POST['projectApiPwd'] ?? '';

curl_setopt_array($ch, [
    CURLOPT_URL            => "https://apis.aisensy.com/project-apis/v1/project/{$projectId}/wa_template/",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => [
        "Accept: application/json",
        "Content-Type: application/json",
        "X-AiSensy-Project-API-Pwd: {$projectApiPwd}"
    ],
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_TIMEOUT        => 30
]);

$response = curl_exec($ch);
$error    = curl_error($ch);
curl_close($ch);

if ($error) {
    echo json_encode([
        'status' => false,
        'message' => $error
    ]);
    exit;
}

$result = json_decode($response, true);

// Handle duplicate template name error
if (!empty($result['message'])) {
    echo json_encode([
        'status' => false,
        'message' => $result['message']
    ]);
    exit;
}

echo json_encode([
    'status' => true,
    'data'   => $result
]);

exit;
}

if (isset($_POST['delete_template'])) {
    $templateId = $_POST['template_id'] ?? '';
    $projectId = $_POST['projectId'] ?? '';
    $projectApiPwd = $_POST['projectApiPwd'] ?? '';

    if (empty($templateId)) {
        echo json_encode(['status' => false, 'message' => 'Template ID is required']);
        exit;
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => "https://apis.aisensy.com/project-apis/v1/project/{$projectId}/wa_template/{$templateId}/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => "DELETE",
        CURLOPT_HTTPHEADER     => [
            "Accept: application/json",
            "X-AiSensy-Project-API-Pwd: {$projectApiPwd}"
        ],
        CURLOPT_TIMEOUT        => 30
    ]);

    $response = curl_exec($ch);
    $error    = curl_error($ch);
    curl_close($ch);

    if ($error) {
        echo json_encode(['status' => false, 'message' => $error]);
        exit;
    }

    $result = json_decode($response, true);

    echo json_encode([
        'status' => true,
        'message' => $result['message'] ?? 'Deleted',
        'data' => $result
    ]);
    exit;
}
?>