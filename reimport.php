<?php
$conn = mysqli_connect('localhost', 'root', '', 'nitro-dr-prod');
mysqli_query($conn, 'TRUNCATE TABLE funnel_data');
echo "Truncated.\n";
require 'import_now.php';
?>
