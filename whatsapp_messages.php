<?php
include('includes/include.php');  
include_once('helpers/DataController.php');

$sendByUserID = $_SESSION['user_id'];
$sendToRequest = $_REQUEST['phone'];
$leadID = $_REQUEST['leadID'];


$messages = db_query("select message,created_at,from_webhook,is_inivite from whatsapp_messages 
    where phone = '".$sendToRequest."'");


while ($msgData = db_fetch_array($messages)) {
    if($msgData['is_inivite'] == 1){
     echo   '<div class="message user">
            <div class="message-time">'.date('d/m/Y, H:i',strtotime($msgData['created_at'])).'</div>
            <div class="content">
                <p class="user-text">Invitation Sent</p>
                <div class="user-icon"></div>
            </div>
            
           
        </div>';
    }elseif(!$msgData['from_webhook']){
     echo   '<div class="message user">
            <div class="message-time">'.date('d/m/Y, H:i',strtotime($msgData['created_at'])).'</div>
            <div class="content">
                <p class="user-text">'.$msgData['message'].'</p>
                <div class="user-icon"></div>
            </div>
            
           
        </div>';
    }else{
    echo    '<div class="message bot" id="incoming-message-box">
            <div class="showTime">'.date('d/m/Y, H:i',strtotime($msgData['created_at'])).'</div>
            <div class="botMessage">
                <div class="bot-icon"></div>
                <p class="bot-text">'.$msgData['message'].'</p>
            </div>
        </div>';
    }               

}


// while ($msgData = db_fetch_array($messages)) {
//    '<div class="message-time">'.date('d/m/Y, H:s',strtotime($msgData['created_at'])).'</div>
//     <div class="content">
//         <p class="user-text">'.$msgData['message'].'</p>
//         <div class="user-icon"></div>
//     </div>';            

// }