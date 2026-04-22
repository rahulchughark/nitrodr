<?php
 include('includes/include.php');

  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Cache-Control: no-cache");
  header("Pragma: no-cache");
  header("Content-Type: application/json");
  
if(!empty($_POST['product']) && !empty($_POST['product_type'])){

    $query = db_query("SELECT * FROM tbl_product
     WHERE id = " . $_POST['product'] . " and status = 1");

    $row = db_fetch_array($query);
    
    if($row['form_id'] == 1){
       echo json_encode(['status'=>true]);
        
    }else{
        echo json_encode(['status'=>false]);
    }

}


