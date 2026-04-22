<?php
include('includes/include.php');  
include_once('helpers/DataController.php');

$sendByUserID = $_SESSION['user_id'];
$sendToRequest = $_REQUEST['sendTo'];
$leadIDRequest = $_REQUEST['leadID'];
$messageRequest = $_REQUEST['message'];

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


