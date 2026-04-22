<?php
include('includes/include.php');
$r = db_query('DESCRIBE opportunity_attachments');
while($f = db_fetch_array($r)) {
    echo $f['Field'] . "\n";
}
?>
