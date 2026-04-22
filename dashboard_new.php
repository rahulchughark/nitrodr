<?php
session_start();

if ($_SESSION['user_type'] == 'ADMIN') {
	header("Location: admin_dashboard.php");
	exit();
} elseif ($_SESSION['user_type'] == 'user') {
	header("Location: user_dashboard.php");
	exit();
} else {
	header("Location: login.php");
	exit();
}

