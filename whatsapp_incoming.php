<?php


include('includes/include.php');  

$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod == "GET") {
    die("The GET method is not supported for this page. Supported methods: POST");
}

// Get webhook payload
$webhookData = file_get_contents("php://input");

// Decode JSON
$data = json_decode($webhookData, true);

if ($data === null) {
    die("Invalid JSON received");
}

// Extract fields safely
$fromMessage    = $data['data']['message']['phone_number'] ?? '';
$messageRequest = mysqli_real_escape_string($GLOBALS['dbcon'], $data['data']['message']['message_content']['text'] ?? '');
$messageType    = $data['data']['message']['message_type'] ?? '';
$wpRespJson = json_encode($data);

// Save full JSON payload
// $wpRespJson = mysqli_real_escape_string($GLOBALS['dbcon'], json_encode($data));

// Insert into DB
$query = db_query("INSERT INTO `whatsapp_messages`
    (`phone`, `message`, `from_webhook`, `gupshup_response`, `reply_type`)
    VALUES ('" . $fromMessage . "', '" . $messageRequest . "', 1, '" . $wpRespJson . "', '".$messageType."')");

// Response to AiSensy
if ($query) {
    echo json_encode(["status" => "success", "message" => "Saved"]);
} else {
    echo json_encode(["status" => "error", "message" => "DB insert failed"]);
}


exit;
include('includes/include.php');  
require __DIR__ . '/vendor/autoload.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];

if($requestMethod == "GET"){
	die("The GET method is not supported for this page.Supported methods:POST");
}

$webhookData = file_get_contents("php://input");

$data = json_decode($webhookData, true);


if ($data === null) {
    echo "Invalid JSON received";
}

$fromMessage = $data['mobile'];

$messageRequest = mysqli_real_escape_string($GLOBALS['dbcon'],$data['text']);
$messageType = $data['type'];
$wpRespJson = json_encode($data);

$query =  db_query("INSERT INTO `whatsapp_messages`(`phone`,`message`,`from_webhook`,`gupshup_response`,`reply_type`)
                   VALUES ('" . $fromMessage . "','" . $messageRequest . "',1,'" . $wpRespJson . "','".$messageType."')");
                   
if($query){

// This is for Sending Mail notification
        // $addTo[] = "rahul.chugh@arkinfo.in";   
        // $setSubject = "New Whatsapp Received or DR Portal";
        // $mobile = htmlspecialchars($data['mobile'], ENT_QUOTES, 'UTF-8');

        // $body = <<<HTML
        // <h1>Hi</h1>
        // <p>You have received a WhatsApp reply on DR Portal.</p>
        // <p>Message from <strong>{$mobile}</strong></p>
        // HTML;
        // $addCc = [];

        // sendMail($addTo, $addCc, $addBcc, $setSubject, $body);


// this is for live chatting show (pusher)
$options = array(
    'cluster' => 'ap2',
    'useTLS' => true
);
// $pusher = new Pusher\Pusher(
//     'b2125d64edf5e1a092e2',
//     '55824a6f034b1aac03a5',
//     '1972669',
//     $options
// );


$pusher = new Pusher\Pusher(
                            getPusherCredentials('key'),
                            getPusherCredentials('secret'),
                            getPusherCredentials('app_id'),
                            $options
                            );

// Data to send
$data = ['message' => 'Hello from Core PHP!','mobile'=>$fromMessage];
$pusher->trigger('my-channel', 'my-event', $data);


$notificationMsg = "Notication from ".$fromMessage;
// This is for inserting notification
$noticationQuery =  db_query("INSERT INTO `whatsapp_notification`(`mobile`,`description`)
                   VALUES ('" . $fromMessage . "','" . $notificationMsg . "')");

echo json_encode(['status'=>200,'is_invite'=>0]);
}else{
    echo json_encode(['status'=>500,'is_invite'=>0]);
}