<?php
ob_start(); // Prevent header issues

include("includes/include.php");
include_once('helpers/logincontroller.php');

if (!empty($_SESSION['track_id'])) {
    $track_id = intval($_SESSION['track_id']);
    db_query("UPDATE user_tracking 
              SET logout_time='".time()."', 
                  total_time=logout_time-login_time 
              WHERE id='$track_id'");
}

$_SESSION = [];
session_unset();
session_destroy();

header("Location: index.php");
exit;