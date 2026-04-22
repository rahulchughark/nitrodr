<?php
include("includes/include.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
    exit;
}

$sql = "
    SELECT 
        al.id,
        al.pid,
        al.description,
        al.call_subject,  
        o.is_opportunity,
        o.lead_status
    FROM activity_log al
    LEFT JOIN orders o ON o.id = al.pid
    ORDER BY al.created_date DESC
    LIMIT 20000
";

$result = db_query($sql);

$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode([
    'status' => 'success',
    'count'  => count($data),
    'data'   => $data
]);
?>
