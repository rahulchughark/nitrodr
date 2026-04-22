<?php
include('includes/include.php');
$res = db_query("DESCRIBE orders");
$columns = [];
while($row = db_fetch_array($res)) {
    $columns[] = $row['Field'];
}
echo "COLUMNS: " . implode(', ', $columns) . "\n";

$tables = ['tbl_mst_industry', 'tbl_mst_stage', 'tbl_mst_proof_engagement'];
foreach($tables as $table) {
    echo "TABLE: $table\n";
    $res = db_query("SHOW TABLES LIKE '$table'");
    if(mysqli_num_rows($res) > 0) {
        $data = db_query("SELECT * FROM $table LIMIT 5");
        while($row = db_fetch_array($data)) {
            print_r($row);
        }
    } else {
        echo "Table $table does not exist.\n";
    }
}
?>
