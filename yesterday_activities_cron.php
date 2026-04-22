<?php
include('helpers/DataController.php');
include('helpers/dashboard_helper.php');

// Set yesterday's date
$FyesterDay = date("Y-m-d", strtotime("-1 day"));
$TyesterDay = date("Y-m-d", strtotime("-1 day"));

// For testing, you can override dates
// $FyesterDay = "2025-08-01";
// $TyesterDay = "2025-09-30";

$formatedDate = date('d F-Y', strtotime($FyesterDay));

// -------------------- ADMIN --------------------
$userCheck = "ADMIN";
ob_start();
include('kra_daily_report_cron.php');  // Big HTML+PHP template
$body = ob_get_clean();




$setSubject = "Daily Report - " . $formatedDate;
$addTo = ["pooja.chauhan@ict360.com"];
sendMail($addTo, ['binish.parikh@ict360.com'], [], $setSubject, $body);

// -------------------- USER --------------------
$userCheck = "USER";
$query = "SELECT * FROM users WHERE 1 {$con} AND user_type IN ('CLR') AND role != 'PARTNER' AND status = 'Active' ORDER BY name ASC";
$sql = db_query($query);

if (mysqli_num_rows($sql) == 0) {
    die("No users found");
}

$usersArray = [];

// Collect all users
while ($data = db_fetch_array($sql)) {
    $usersArray[] = [
        'id'    => $data['id'],
        'email' => $data['email']
    ];
}

// Loop over all users to send emails
foreach ($usersArray as $user) {
    $userID    = $user['id'];
    $userEmail = $user['email'];

    if (empty($userEmail)) continue;

    $setSubject = "Daily Report - " . $formatedDate;

    $userData = $user;  // Pass data explicitly to template
    ob_start();
    include('kra_daily_report_cron.php');  
    $body = ob_get_clean();

    // Send email
    $addTo = [$userEmail];           // Send to actual user
    // $addTo = ["binish.parikh@ict360.com","rahul.chugh@arkinfo.in"]; // For testing only
    sendMail($addTo, ['binish.parikh@ict360.com'], [], $setSubject, $body);
}

die("Done");