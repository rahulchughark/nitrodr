<?php

include('includes/include.php');
include_once('helpers/DataController.php');

$helper = new DataController;

$order_id     = (int)$_POST['order_id']; 
$tds = $_POST['tds'];


$updating = $helper->updateTdsAmount($order_id,$tds);


if($updating){
    echo json_encode(['status' => 'success', 'message' => 'Successfully Updated']);
}else{
    echo json_encode(['status' => 'error', 'message' => 'No Data Found For Receiving.']);
}