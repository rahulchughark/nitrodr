<?php
include('includes/include.php');  
include_once('helpers/DataController.php');



$main_product_id = $_POST['main_product_id'];
$sub_product = $_POST['sub_product'];
$get_id = $_POST['get_id'];




if (!empty($_POST['main_product_id']) && $_POST['sub_product'] == 0) {
    $main_id = intval($_POST['main_product_id']);
    
    $subProducts = db_query("SELECT id, product_name FROM tbl_product_opportunity WHERE main_product_id = $main_id AND status = 1");

     echo '<option value="">---Select Sub Product---</option>';

    while ($row = db_fetch_array($subProducts)) {
        echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['product_name']) . '</option>';
    }
}else if(!empty($_POST['main_product_id']) && $_POST['sub_product']){

    $main_id = intval($main_product_id);
    $sub_product = intval($sub_product);
    
    $subProducts = db_query("SELECT id, product_name FROM tbl_product_opportunity 
                            WHERE main_product_id = $main_id  
                            AND id != $get_id
                            AND status = 1");

     echo '<option value="">---Select Sub Product---</option>';

    while ($row = db_fetch_array($subProducts)) {
        $selected = ($sub_product == $row['id']) ? 'selected' : '';
        echo '<option '.$selected.' value="' . $row['id'] . '">' . htmlspecialchars($row['product_name']) . '</option>';
    }

}



?>