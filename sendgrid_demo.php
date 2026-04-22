<?php include('includes/include.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$email->setSubject('demo');
$body = 'sadsadasdas';
            
$email->addTo('pradeep.chahal@arkinfo.in');

$email->addContent("text/html", $body);
// if($sendgrid->send($email))
// {
//     echo '1';
// }else{
//     echo 'no';
// }


try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n"; // Twilio SendGrid specific errors are found here
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


$mail->AddCC   -   $email->addCC
$mail->Subject - $email->setSubject
$mail->Body  - $body
$mail->AddAddress - $email->addTo
$mail->Send();  -   

$email->addContent("text/html", $body);
$sendgrid->send($email);