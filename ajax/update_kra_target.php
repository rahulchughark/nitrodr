<?php
include('../includes/include.php');  


$user_id = $_SESSION['user_id'];
$targetValue = $_REQUEST['value'];
$key_id = $_REQUEST['key_id'];
$key_subject = $_REQUEST['key_subject'];
$type = $_REQUEST['type'];
$messageRequest = $_REQUEST['message'];



exit;
$dataObj = new DataController();
$isInvited = $dataObj->checkInvitationSent($sendByUserID,$leadIDRequest,$sendToRequest);

if(!$isInvited){
    $dataObj->whatsAppInviteTemplate($sendByUserID,$sendToRequest,$leadIDRequest);
    echo json_encode(['status'=>200,'is_invite'=>1]);
    exit;
}

$query =  db_query("INSERT INTO `whatsapp_messages`(`user_id`,`phone`,`message`,`lead_id`) 
                   VALUES ('" . $sendByUserID . "','" . $sendToRequest . "','" . $messageRequest . "','" . $leadIDRequest . "')");

// gushup
    

if($query){
    echo json_encode(['status'=>200,'is_invite'=>0]);
}else{
    echo json_encode(['status'=>500,'is_invite'=>0]);
}


