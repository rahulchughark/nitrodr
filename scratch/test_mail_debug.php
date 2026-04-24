<?php
define('LOCAL_MODE', true);
$HTTP_HOST = 'localhost';
include 'includes/config.php';
require_once 'class.phpmailer.php';
require_once 'class.smtp.php';
include 'includes/functions.php';

$to = "rahul.chugh@arkinfo.in";
$subject = "Test Mail Debug";
$body = "<h1>Test Email</h1><p>This is a test email with SMTP debug enabled.</p>";

echo "Sending test mail to $to...\n";
$result = sendMailReminder($to, $subject, $body);

if ($result) {
    echo "\n\nMail sent successfully!\n";
} else {
    echo "\n\nMail failed to send.\n";
}
?>
