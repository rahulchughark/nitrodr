<?php
include('includes/include.php');  


$user_id = $_REQUEST['user_id'];
$targetValue = $_REQUEST['value'];
$key_id = $_REQUEST['key_id'];
$key_subject = $_REQUEST['key_subject'];
$type = $_REQUEST['type'];
$messageRequest = $_REQUEST['message'];
$date = date('Y-m-d');
$onlyStatusUpdate =  isset($_REQUEST['onlyStatusUpdate']) ? $_REQUEST['onlyStatusUpdate'] : false;

if($onlyStatusUpdate){

$status = $_REQUEST['status'];

    $update = db_query("UPDATE `kra_users` 
                        SET status = $status 
                        WHERE key_id = '$id' 
                        AND key_subject = '$key_subject' 
                        AND type = '$type' 
                        AND user_id = '$user_id'
                        AND MONTH(date) = MONTH(CURDATE())
                        AND YEAR(date) = YEAR(CURDATE())");

     if ($update) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}


$checkQuery = db_query("SELECT id FROM `kra_users` 
                        WHERE user_id = '$user_id' 
                        AND type = '$type' 
                        AND key_id = '$key_id' 
                        AND key_subject = '$key_subject'
                        AND MONTH(date) = MONTH(CURDATE())
                        AND YEAR(date) = YEAR(CURDATE())");

if (mysqli_num_rows($checkQuery) > 0) {
    // Update if exists
    $updateQuery = db_query("UPDATE `kra_users` 
                             SET target = '$targetValue', date = '$date' 
                             WHERE user_id = '$user_id' 
                             AND type = '$type' 
                             AND key_id = '$key_id' 
                             AND key_subject = '$key_subject'");
    
  
} else {
    // Insert if not exists
    $insertQuery = db_query("INSERT INTO `kra_users`
                             (`user_id`, `type`, `key_id`, `key_subject`, `target`, `date`) 
                             VALUES 
                             ('$user_id', '$type', '$key_id', '$key_subject', '$targetValue', '$date')");
    
   
}



// gushup
    

if($insertQuery){
    echo json_encode(['status'=>200,'is_invite'=>0]);
}else{
    echo json_encode(['status'=>500,'is_invite'=>0]);
}


