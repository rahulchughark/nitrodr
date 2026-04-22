<?php
// include('includes/include.php');  
// include_once('helpers/DataController.php');


echo "hi";
exit;

$main_product_id = $_POST['main_product_id'];


echo "hi";
exit;


if (!empty($_POST['main_product_id'])) {
    $main_id = intval($_POST['main_product_id']);
    
    $subProducts = db_query("SELECT id, product_name FROM tbl_product_opportunity WHERE main_product_id = $main_id AND status = 1");

    while ($row = db_fetch_array($subProducts)) {
        echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['product_name']) . '</option>';
    }
}
?>