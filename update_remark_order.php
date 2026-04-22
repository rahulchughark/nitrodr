<?php
include('includes/include.php');
include_once('helpers/DataController.php');

$order_id = (int)$_POST['order_id'];  // Always cast to int for safety
$remark = trim($_POST['remark']);

// Check if data exists
$sql = "SELECT COUNT(id) as total FROM tbl_mst_invoice WHERE order_id = $order_id AND status = 1 AND is_deleted = 0";
$result = db_query($sql);
$row = db_fetch_array($result);

if ($row['total'] > 0) {
    // Update
    $update = "UPDATE tbl_mst_invoice 
               SET remark = '" . $remark . "', updated_at = NOW() 
               WHERE order_id = $order_id AND status = 1 AND is_deleted = 0";
    db_query($update);
    echo json_encode(['status' => 'success', 'message' => 'Remark updated successfully.']);
} else {
    // Insert
    $insert = "INSERT INTO tbl_mst_invoice (order_id, remark, status, is_deleted, created_at) 
               VALUES ($order_id, '" . $remark . "', 1, 0, NOW())";
    db_query($insert);
    echo json_encode(['status' => 'success', 'message' => 'Remark added successfully.']);
}

