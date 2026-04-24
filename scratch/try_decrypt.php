<?php
define('LOCAL_MODE', true);
$HTTP_HOST = 'localhost';
include 'includes/config.php';
include 'includes/functions.php';

function decryptData($data, $secret_key = "YourSecretKey123", $secret_iv = "YourSecretIV123") {
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv  = substr(hash('sha256', $secret_iv), 0, 16);
    return openssl_decrypt($data, $encrypt_method, $key, 0, $iv);
}

$res = db_query("SELECT * FROM mst_confidentials limit 1");
$row = mysqli_fetch_assoc($res);

echo "Encrypted Username: " . bin2hex($row['email_username']) . "\n";
echo "Decrypted Username: " . decryptData($row['email_username']) . "\n";

echo "Encrypted Password: " . bin2hex($row['email_password']) . "\n";
echo "Decrypted Password: " . decryptData($row['email_password']) . "\n";
?>
