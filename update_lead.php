<?php include('includes/include.php');

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$_GET['oid']=intval($_GET['oid']);
$_GET['type']=mysqli_real_escape_string($GLOBALS['dbcon'],htmlspecialchars($_GET['type']));


if ($_GET['oid']) {
    $leads_type = getSingleresult("select lead_type from orders where id=" . $_GET['oid'] . " limit 1");
    
    //modification log
    $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_GET['oid'] . "','Lead Type','" . $leads_type . "','" . $_GET['type'] . "',now(),'" . $_SESSION['user_id'] . "')");

    $sql = db_query("update orders set lead_type='" . $_GET['type'] . "',bd2lc=1 where id=" . $_GET['oid']);

    if ($sql && $_GET['type'] == 'LC') {

        $update_notify = db_query("update lead_notification set is_read=1 where type_id=" . $_GET['oid']);

        if ($update_notify) {

            $select = db_query("select * from lead_notification where type_id=" . $_GET['oid']);
            foreach ($select as $value) {
                $company_name = mysqli_real_escape_string($GLOBALS['dbcon'],htmlspecialchars($value['company_name']));
                $title        = mysqli_real_escape_string($GLOBALS['dbcon'],htmlspecialchars($value['title']));
                //$submitted_by = $value['submitted_by'];
                $receiver_id  = intval($value['sender_id']);
                $sender_id    = $_SESSION['user_id'];
                $initiate_reason= mysqli_real_escape_string($GLOBALS['dbcon'],htmlspecialchars($value['initiate_reason']));
                $visit_done  = $value['visit_done'];
                $usage_confirmed  = $value['usage_confirmed'];
            }
            // foreach($sender_id as $row){
            //     if($row == $_SESSION['user_id']){
            //         $sender_id = $row;
            //     }
            //     //print_r($sender_id);
            // }

            $title = 'Approved ' . $title;
            $sender_type = ($_SESSION['name'] == 'Administrator') ? 'Admin' : 'Reviewer';
            $id = $_GET['oid'];

            $insert = saveNotification('lead_notification', $id, $title, $company_name, $_SESSION['name'], $sender_type, $partner_name, $sender_id, $receiver_id,$initiate_reason,$visit_done,$usage_confirmed);
            //print_r($insert);die;
        }
    }
}

if ($sql) {
    echo 1;
    exit();
}
