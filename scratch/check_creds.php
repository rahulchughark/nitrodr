<?php
define('LOCAL_MODE', true);
$HTTP_HOST = 'localhost';
include 'includes/config.php';
include 'includes/functions.php';

$res = db_query("DESCRIBE orders");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}
?>
