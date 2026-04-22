<?php
echo "Starting check...\n";
include('includes/include.php');
$res = db_query("SELECT source, COUNT(*) as count FROM funnel_data GROUP BY source LIMIT 10");
while($row = db_fetch_array($res)) {
    echo "Source: " . $row['source'] . " - Count: " . $row['count'] . "\n";
}
?>
