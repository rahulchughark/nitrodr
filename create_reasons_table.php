<?php
include('includes/include.php');

// 1. Create tbl_approval_reasons table
$createTableSql = "CREATE TABLE IF NOT EXISTS tbl_approval_reasons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reason VARCHAR(255) NOT NULL,
    status INT DEFAULT 1
)";
$res1 = db_query($createTableSql);

if ($res1) {
    echo "Table tbl_approval_reasons created successfully.<br>";
} else {
    echo "Failed to create tbl_approval_reasons table.<br>";
}

// 2. Insert dummy records
$checkEmpty = db_query("SELECT COUNT(*) as cnt FROM tbl_approval_reasons");
$cntRow = db_fetch_array($checkEmpty);
if ($cntRow['cnt'] == 0) {
    $dummyReasons = [
        "Incorrect Pricing",
        "Client Unreachable",
        "Insufficient Documentation",
        "Budget Constraints",
        "Competitor Selected",
        "Other"
    ];
    foreach ($dummyReasons as $reason) {
        $safeReason = mysqli_real_escape_string($GLOBALS['dbcon'], $reason);
        db_query("INSERT INTO tbl_approval_reasons (reason) VALUES ('$safeReason')");
    }
    echo "Dummy records inserted successfully.<br>";
} else {
    echo "Dummy records already exist.<br>";
}

// 3. Add columns to orders table if they don't exist
$checkCol1 = getSingleresult("SHOW COLUMNS FROM orders LIKE 'approval_reason_id'");
if (empty($checkCol1)) {
    db_query("ALTER TABLE orders ADD COLUMN approval_reason_id INT DEFAULT 0");
    echo "Column approval_reason_id added to orders.<br>";
} else {
    echo "Column approval_reason_id already exists.<br>";
}

$checkCol2 = getSingleresult("SHOW COLUMNS FROM orders LIKE 'approval_reason_custom'");
if (empty($checkCol2)) {
    db_query("ALTER TABLE orders ADD COLUMN approval_reason_custom TEXT");
    echo "Column approval_reason_custom added to orders.<br>";
} else {
    echo "Column approval_reason_custom already exists.<br>";
}
?>
