<?php
include('includes/include.php');

$userID = $_SESSION['user_id'];

$lead_id = $_POST['lead_id'];
$new_amount = $_POST['amount'];
$currentAmount = isset($_POST['currentAmount']) ? $_POST['currentAmount'] : null;
$currentAmount = str_replace(',', '', $currentAmount); 



if (!$lead_id || !$new_amount || !$userID) {
    echo "invalid";
    exit;
}

// fetch last non-deleted row
$query = "SELECT amount FROM tbl_lead_custom_amount 
          WHERE lead_id='$lead_id' AND is_deleted=0 
          ORDER BY id DESC LIMIT 1";

$res = db_fetch_array(db_query($query));
$previous_amount_db = $res ? $res['amount'] : 0;

// final previous_amount logic
if (!empty($currentAmount)) {
    $previous_amount = $currentAmount;   // use currentAmount
} else {
    $previous_amount = $previous_amount_db; // fallback to DB value
}


// Insert new history record
$insertQuery = "INSERT INTO tbl_lead_custom_amount 
                (user_id,lead_id, previous_amount, amount, is_deleted, created_at, updated_at)
                VALUES ('$userID','$lead_id', '$previous_amount', '$new_amount', 0, NOW(), NOW())";

db_query($insertQuery);

// get last inserted ID
$new_id = get_insert_id();

echo $new_id;


// 4 UPDATE all *older* records to deleted=1 (Exclude new one)
$updateQuery = "UPDATE tbl_lead_custom_amount 
                SET is_deleted = 1 , updated_at = NOW()
                WHERE lead_id = '$lead_id' 
                AND id != '$new_id' 
                AND is_deleted = 0";

db_query($updateQuery);

echo "success";
