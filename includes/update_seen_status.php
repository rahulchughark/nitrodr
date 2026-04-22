<?php
include("includes/include.php");

if (!empty($_POST['notification_id'])) {
    $id = $_POST['notification_id'];

    // Update seen status in whatsapp_notification
    $update = db_query("
        UPDATE whatsapp_notification 
        SET seen = 1 
        WHERE id = '" . $id . "' AND seen = 0
    ");

    echo $update ? 'success' : 'error';
} else {
    echo 'invalid request';
}
