<?php
include("includes/include.php");


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
    exit;
}

header('Content-Type: application/json');

define('API_MAPPING_TOKEN', 'mQ8F2Z9LJYH7P3VKR4T6E5CXNDBW');

$token = $_POST['api_token'] ?? '';
$lead_id = $_POST['lead_id'] ?? null;
$school_kms_id = $_POST['school_kms_id'] ?? null;

if ($token !== API_MAPPING_TOKEN) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized: Invalid API Token'
    ]);
    exit;
}

if (!$lead_id || !$school_kms_id) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing lead_id or school_kms_id'
    ]);
    exit;
}

$sql = "UPDATE orders SET kms_school_id = ? WHERE id = ?";
$res = db_query_param($sql, [$school_kms_id, (int)$lead_id], "si");

if ($res) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Lead mapping updated successfully'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to update database'
    ]);
}
?>