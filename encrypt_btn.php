<?php

function encryptDataForUrl($data, $secret_key = "YourSecretKey123", $secret_iv = "YourSecretIV123") {
    
    // Convert array to JSON
    $json = json_encode($data);

    // Encryption settings
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv  = substr(hash('sha256', $secret_iv), 0, 16);

    // Encrypt JSON
    $encrypted = openssl_encrypt($json, $encrypt_method, $key, 0, $iv);

    // Base64 encode + URL safe
    return urlencode(base64_encode($encrypted));
}

// -----------------------------
// Sample Data
// -----------------------------
$dataToSend = [
    "email_id"         => "test@gmail.com",
    "user_id"          => 25,
    "partner_team_id"  => 18,
    "key"             => bin2hex(random_bytes(10)).bin2hex(random_bytes(10)) // random string
];

// die(bin2hex(random_bytes(10)).bin2hex(random_bytes(10)));

// Encrypt
$encryptedString = encryptDataForUrl($dataToSend);

// Final URL
$url = "https://ict360.com/explore/?auth=" . $encryptedString;



echo "<pre>";
echo $url;


// validate.php?token="ec4e67ad8cf5d599addf202c35480de6398ac482"