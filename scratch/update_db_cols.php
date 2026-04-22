<?php
include('includes/include.php');
$queries = [
    "ALTER TABLE opportunity_attachments ADD COLUMN name VARCHAR(255) DEFAULT NULL",
    "ALTER TABLE opportunity_attachments ADD COLUMN amount DECIMAL(15,2) DEFAULT NULL",
    "ALTER TABLE opportunity_attachments ADD COLUMN parent_id INT DEFAULT NULL"
];

foreach ($queries as $q) {
    try {
        db_query($q);
        echo "Executed: $q\n";
    } catch (Exception $e) {
        // If columns already exist, maybe try to rename if they have the old names
        echo "Failed to execute $q: " . $e->getMessage() . "\n";
    }
}

// Check if old columns exist and rename them
$cols = [];
$r = db_query("DESCRIBE opportunity_attachments");
while($f = db_fetch_array($r)) $cols[] = $f['Field'];

if (in_array('pi_name', $cols) && !in_array('name', $cols)) {
    db_query("ALTER TABLE opportunity_attachments CHANGE pi_name name VARCHAR(255)");
    echo "Renamed pi_name to name\n";
}
if (in_array('pi_amount', $cols) && !in_array('amount', $cols)) {
    db_query("ALTER TABLE opportunity_attachments CHANGE pi_amount amount DECIMAL(15,2)");
    echo "Renamed pi_amount to amount\n";
}
?>
