<?php
$c = mysqli_connect('localhost', 'root', '', 'nitro-dr');
$r = mysqli_query($c, 'DESCRIBE opportunity_attachments');
while($f = mysqli_fetch_assoc($r)) {
    echo $f['Field'] . "\n";
}
?>
