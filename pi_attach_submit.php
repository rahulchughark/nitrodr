<?php

include('includes/include.php');
include_once('helpers/DataController.php');

$helperData = new DataController();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$order_id     = (int)$_POST['order_id'];  
$billing_name = trim($_POST['billing_name']);
$sub_category = (int)$_POST['sub_category'];
$remarks      = trim($_POST['remarks']);
$amount  = $_POST['amount']; // Array
$date    = $_POST['date'];


// Escape input to prevent SQL issues
$billing_name = addslashes($billing_name);
$remarks      = addslashes($remarks);

$groupResult = db_query("
    SELECT group_name 
    FROM orders 
    WHERE id = $order_id
");


$groupRow = db_fetch_array($groupResult);
$groupName = $groupRow ? $groupRow['group_name'] : null;

if ($groupName) {
    // $groupOrdersResult = db_query("
    //     SELECT id 
    //     FROM orders 
    //     WHERE group_name = '" . $groupName . "'
    // ");

    // $groupOrderIds = [];
    // while ($orderRow = db_fetch_array($groupOrdersResult)) {
    //     $groupOrderIds[] = $orderRow['id'];
    // }

    // $groupOrderIds = $helperData->getGroupOrderIdsStr($groupName);

    // Convert to a comma-separated string
    $groupOrderIdsStr = $helperData->getGroupOrderIdsStr($groupName);
    $isGrouped = 1;
    $group_id = $groupName;

} else {
    $groupOrderIdsStr = "";
    $isGrouped = 0;
    $group_id = 0;

}

// Check if data exists
$sql = "SELECT COUNT(id) as total 
        FROM tbl_mst_invoice 
        WHERE order_id = $order_id AND status = 1 AND is_deleted = 0";

$result = db_query($sql);
$row = db_fetch_array($result);

if ($row['total'] > 0) {
    // Update
    $update = "UPDATE tbl_mst_invoice 
               SET billing_detail = '$billing_name', 
                   sub_category = $sub_category, 
                   remark = '$remarks',
                   updated_at = NOW() 
               WHERE order_id = $order_id AND status = 1 AND is_deleted = 0";
    db_query($update);
    $saveType = 1;# Update
    
} else {
    // Insert
    $insert = "INSERT INTO tbl_mst_invoice 
               (order_id, sub_category, billing_detail, remark, status, is_deleted, created_at, all_orders_id,is_grouped, group_id) 
               VALUES 
               ($order_id, $sub_category, '$billing_name', '$remarks', 1, 0, NOW(), '$groupOrderIdsStr', $isGrouped, $group_id)";
    db_query($insert);
    $saveType = 0;# Insert
}



if($amount){
    
$update = "UPDATE tbl_invoice_emi 
           SET is_deleted = 1 
           WHERE order_id = $order_id";
db_query($update);

foreach ($amount as $key => $amt) {
    if (!empty($amt) && !empty($date[$key])) {
        $insert = "INSERT INTO tbl_invoice_emi
                   (order_id, amount, date, status, is_deleted, created_at) 
                   VALUES 
                   ($order_id, $amt, '{$date[$key]}', 1, 0, NOW())";
        db_query($insert);
    }
}

}



if($saveType == 0){
    echo json_encode(['status' => 'success', 'message' => 'Remark updated successfully.']);
}else{
    echo json_encode(['status' => 'success', 'message' => 'Remark added successfully.']);
}