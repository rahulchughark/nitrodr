<?php

include('includes/include.php');
include_once('helpers/DataController.php');



// echo "<pre>";
// print_r($_POST);
// exit;

$id     = (int)$_POST['emi_id'];  
$amount = trim($_POST['amount']);
$date = $_POST['date'];


// Check if data exists
$sql = "SELECT COUNT(id) as total 
        FROM tbl_invoice_emi 
        WHERE id = $id AND status = 1 AND is_deleted = 0";

$result = db_query($sql);
$row = db_fetch_array($result);

if ($row['total'] > 0) {
    // Update
    $update = "UPDATE tbl_invoice_emi 
               SET received_amount = '$amount',
                   received_date = '$date',                    
                   updated_at = NOW() 
               WHERE id = $id AND status = 1 AND is_deleted = 0";
    db_query($update);
    $saveType = 1;# Success Update
    
} else { 
    $saveType = 0;# Error
}


if($saveType == 0){
    echo json_encode(['status' => 'error', 'message' => 'No Data Found For Receiving.']);
}else{
    echo json_encode(['status' => 'success', 'message' => 'Successfully Updated']);
}