<?php
include("includes/include.php");

// if (!empty($_POST['notification_id'])) {
//     $id = $_POST['notification_id'];

//     // Update seen status in whatsapp_notification
//     $update = db_query("
//         update whatsapp_notification 
//         SET seen = 1 
//         WHERE id = '" . $id . "' AND seen = 0
//     ");

//     echo $update ? 1 : 0;
// } else {
//     echo 'invalid request';
// }



$notification_id = $_POST['notification_id'] ?? null;
$phone_number = $_POST['phone_number'] ?? null;

if ($notification_id) {
    $update = db_query("
        UPDATE whatsapp_notification 
        SET seen = 1 
        WHERE id = '" . $notification_id . "' AND seen = 0
    ");
    echo $update ? 1 : 0;

} elseif ($phone_number) {
    $update = db_query("
        UPDATE whatsapp_notification 
        SET seen = 1 
        WHERE mobile = '" . $phone_number . "' AND seen = 0
    ");
    echo $update ? 1 : 0;

} else {
    echo 'invalid request';
}