<?php
define('LOCAL_MODE', true);
$HTTP_HOST = 'localhost';
include 'includes/config.php';
include 'includes/functions.php';

$to = "rahul.chugh@arkinfo.in";
$subject = "CLI Test Mail";
$body = "Testing from CLI";

echo "Attempting to send mail...\n";
$res = sendMailReminder($to, $subject, $body);

if ($res) {
    echo "\nSuccess!\n";
} else {
    echo "\nFailed.\n";
}
?>
