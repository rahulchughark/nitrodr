<?php
include('includes/include.php');
//$exploreUrl = "https://ict360.com/explore/";
$exploreUrl = "http://stageweb.ict360.com/explore/";
$expire_time = date('Y-m-d H:i:s', strtotime('+2 minutes')); // 1 hour expire
$is_used = 0;
$paramValues = array(
    "username"      => $_SESSION['email'],
    "auth_passkey"  =>  bin2hex(random_bytes(10)) . bin2hex(random_bytes(10)),
    "user_id"         => $_SESSION['user_id'],
    "origin"        => "dr.ict360.com",
    'is_used' => $is_used,
    'expire_time'=>$expire_time,
);
function url_uncode_for_explore($param)
{
    // Step 1: JSON encode
    $json = json_encode($param);
    // Step 2: base64 encode
    $step1 = base64_encode($json);
    // Step 3: reverse the string
    $step2 = strrev($step1);
    // Step 4: second base64 encode
    $step3 = base64_encode($step2);
    // Step 5: final reverse
    $final = strrev($step3);
    return $final;
}

$urlParam =   url_uncode_for_explore($paramValues);
// $fullUrl =   $exploreUrl .'auth/dr/'. $urlParam;
$fullUrl =   $exploreUrl . '?auth=' . $urlParam;

// Database insert
$token = $paramValues['auth_passkey'];
// Check if user already exists in explore_tokens table
$checkUserQuery = "SELECT * FROM explore_tokens WHERE user_id = '" . addslashes($_SESSION['user_id']) . "'";
$checkResult = db_query($checkUserQuery);

if ($checkResult && mysqli_num_rows($checkResult) > 0) {
    // User exists, update token and expires_at
    $updateQuery = "UPDATE explore_tokens 
                    SET token = '" . addslashes($token) . "', 
                        expires_at = '" . addslashes($expire_time) . "' 
                    WHERE user_id = '" . addslashes($_SESSION['user_id']) . "'";
    db_query($updateQuery);
} else {
    // User does not exist, insert new record
    $insertQuery = "INSERT INTO explore_tokens (user_email, user_id, token, expires_at, is_used) 
                    VALUES ('" . addslashes($_SESSION['email']) . "', 
                            '" . addslashes($_SESSION['user_id']) . "', 
                            '" . addslashes($token) . "', 
                            '" . addslashes($expire_time) . "', 
                            0)";
    db_query($insertQuery);
}
// Redirect to explore
header('Location: ' .  $fullUrl);
exit;
