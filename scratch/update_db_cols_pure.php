<?php
$c = mysqli_connect('localhost', 'root', '', 'nitro-dr');
if (!$c) die("Connect failed: " . mysqli_connect_error());

function run($c, $q) {
    if (mysqli_query($c, $q)) echo "Success: $q\n";
    else echo "Error: " . mysqli_error($c) . "\n";
}

$res = mysqli_query($c, "DESCRIBE opportunity_attachments");
$cols = [];
while($f = mysqli_fetch_assoc($res)) $cols[] = $f['Field'];

if (!in_array('name', $cols)) {
    if (in_array('pi_name', $cols)) {
        run($c, "ALTER TABLE opportunity_attachments CHANGE pi_name name VARCHAR(255)");
    } else {
        run($c, "ALTER TABLE opportunity_attachments ADD COLUMN name VARCHAR(255)");
    }
}

if (!in_array('amount', $cols)) {
    if (in_array('pi_amount', $cols)) {
        run($c, "ALTER TABLE opportunity_attachments CHANGE pi_amount amount DECIMAL(15,2)");
    } else {
        run($c, "ALTER TABLE opportunity_attachments ADD COLUMN amount DECIMAL(15,2)");
    }
}

if (!in_array('parent_id', $cols)) {
    run($c, "ALTER TABLE opportunity_attachments ADD COLUMN parent_id INT");
}

$res = mysqli_query($c, "DESCRIBE opportunity_attachments");
while($f = mysqli_fetch_assoc($res)) echo $f['Field'] . "\n";
?>
