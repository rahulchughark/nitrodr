<?php
$conn = mysqli_connect('localhost', 'root', '', 'nitro-dr-prod');
$res = mysqli_query($conn, 'DESCRIBE funnel_data');
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
?>
