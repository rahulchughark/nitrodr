<?php

include("includes/include.php");
// print_r($_POST);die;
$checkbox_id = @array_filter($_POST['ids']);
if($checkbox_id[0] == 'multiselect-all'){
    $checkbox_id = @array_slice($checkbox_id,1);
}
if ($_POST['caller']) {

    if (count($checkbox_id)) {
        foreach ($checkbox_id as $id) {

            $prev_caller = getSingleResult("select allign_to from orders where id=".$id); 
            
            $prev_caller = $prev_caller ? getSingleResult("select name from users where id=".$prev_caller): 'N/A';
            $allignToUserId = getSingleResult("select user_id from callers where id=".$_POST['caller']);
            // print_r($_SESSION);die;
                $sql = db_query("update orders set allign_to='" . $allignToUserId . "',caller='" . $_POST['caller'] . "' where id=" . $id);
            
            $modify_caller = getSingleResult("select allign_to from orders where id=".$id); 
            $modify_caller = getSingleResult("select name from users where id=".$modify_caller);
            // print_r($modify_caller);die;
            $created_by = $_SESSION['user_id'];
            $insertLead_log = db_query("INSERT INTO lead_modify_log (lead_id,type,previous_name,modify_name,created_date,created_by,log_status,timestamp)VALUES('$id','Alligned To','$prev_caller','$modify_caller',now(),'$created_by','Active',now())");
            // print_r($sql); die;

                // if($sql){
                //     return true;
                // }else{
                //     return false;
                // }
        }
    }
}
if ($_POST['sales_mngr']) {
    $i = 1;
    if (count($checkbox_id)) { 
        foreach ($checkbox_id as $id) {
            $select_query = massLead_modifyLog('orders',$id);
            foreach($select_query as $row){
                $prev_sm =$row['allign_to'] ? getSingleresult("SELECT name from users where id =".$row['allign_to'])  : 'N/A'; 
            }
            $prev_sm = $prev_sm ? $prev_sm: 'NA';
            $sql = db_query("update orders set allign_to='" . $_POST['sales_mngr'] . "' where id=" . $id);
            
            $select_query = massLead_modifyLog('orders',$id);
            foreach($select_query as $row){
                $modify_sm = getSingleresult("SELECT name from users where id =".$row['allign_to']); 
            }
            $created_by = $_SESSION['user_id'];
            $insertLead_log = db_query("INSERT INTO lead_modify_log (lead_id,type,previous_name,modify_name,created_date,created_by,log_status,timestamp,data_ref)VALUES('$id','Alligned To','$prev_sm','$modify_sm',now(),'$created_by','Active',now(),1)");
            // print_r($sql); die;

                // if($sql){
                //     return true;
                // }else{
                //     return false;
                // }
        }
    }
}

if($_POST['partnerF']){
    if (count($checkbox_id)) {
        foreach ($checkbox_id as $id) {

            $prev_allignTo = getSingleResult("select allign_to from orders where id=".$id); 
            $prev_team = getSingleResult("select team_id from orders where id=".$id); 
            
            $prev_allignTo = $prev_allignTo ? getSingleResult("select name from users where id=".$prev_allignTo): 'N/A';
            $prev_team = $prev_team ? getSingleResult("select name from partners where id=".$prev_team): 'N/A';

            $sql = db_query("update orders set allign_to='" . $_POST['userF'] . "',allign_team_id='" . $_POST['partnerF'] . "' where id=" . $id);
            
            $modify_allign = getSingleResult("select name from users where id=".$_POST['userF']);

            $modify_team = getSingleResult("select name from partners where id=".$_POST['partnerF']);

            $created_by = $_SESSION['user_id'];
            $insertLead_log = db_query("INSERT INTO lead_modify_log (lead_id,type,previous_name,modify_name,created_date,created_by,log_status,timestamp)VALUES('$id','Alligned To','$prev_allignTo','$modify_allign',now(),'$created_by','Active',now())");
            $insertLead_log = db_query("INSERT INTO lead_modify_log (lead_id,type,previous_name,modify_name,created_date,created_by,log_status,timestamp)VALUES('$id','Partner','$prev_team','$modify_team',now(),'$created_by','Active',now())");

        }
    }
}

?>
