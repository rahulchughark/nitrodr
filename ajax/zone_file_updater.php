<?php
include('../includes/include.php'); 

header('Content-Type: application/json');

if (!empty($_POST['id']) && !empty($_POST['file_name'])) {
    $id = intval($_POST['id']);
    $fileName = trim($_POST['file_name']);

    // Update query
    $update = db_query("UPDATE learning_zone_attachment 
                        SET file_name = '" .  $fileName . "' 
                        WHERE id = " . $id);

    if ($update) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database update failed"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
}
?>
