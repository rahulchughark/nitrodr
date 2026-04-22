<?php include('includes/include.php');
include_once('helpers/DataController.php');

$update_payment = new DataController();

$id           = $_POST['id'];
$lead_id      = $_POST['lead_id'];
$var          = $_POST['var'];
$installment1 = $_POST['installment1'];
$revoke_status= $_POST['revoke_status'];
$ark          = $_POST['ark'];

$select_query = db_query("select * from payment_status where installment_id='" . $id . "'");
//$arr = db_fetch_array($select_query);

if($var=='payment_var1'){ 
    if(mysqli_num_rows($select_query)==0){
        $insert_status= db_query("insert into payment_status(`installment_id`,lead_id,`payment_var1`,`payment_ark1`,`var_status`)values(" . $id . "," . $lead_id . ",".$installment1.",0,1)");
    }else{
        $insert_status= db_query("update payment_status set `installment_id`=" . $id . ",lead_id=" . $lead_id . ",`payment_ark1`=0,`payment_var1`=".$installment1.",`var_status`=1 where installment_id=".$id);
    }
        
    
}
if($ark=='payment_ark1'){ 
    if(mysqli_num_rows($select_query)==0){
    $insert_status= db_query("insert into payment_status(`installment_id`,lead_id,`payment_var1`,`payment_ark1`,`ark_status`)values(" . $id . "," . $lead_id . ",0,".$installment1.",1)");
    }else{
        $insert_status= db_query("update payment_status set `installment_id`=" . $id . ",lead_id=" . $lead_id . ",`payment_var1`=0,`payment_ark1`=".$installment1.",`ark_status`=1 where installment_id=".$id);
    }
    
}
if($var=='payment_var2'){ 
    $insert_status= db_query("update payment_status set `installment_id`=" . $id . ",lead_id=" . $lead_id . ",`payment_var2`=".$installment1.",`payment_ark2`=0,`var_status`=1,`ark_status`=0 where installment_id=".$id);

}
if($ark=='payment_ark2'){ 
    $insert_status= db_query("update payment_status set `installment_id`=" . $id . ",lead_id=" . $lead_id . ",`payment_ark2`=".$installment1.",`payment_var2`=0,`ark_status`=1,`var_status`=0 where installment_id=".$id);

}
if($var=='payment_var3'){ 
    $insert_status= db_query("update payment_status set `installment_id`=" . $id . ",lead_id=" . $lead_id . ",`payment_var3`=".$installment1.",`payment_ark3`=0,`var_status`=1,`ark_status`=0 where installment_id=".$id);

}
if($ark=='payment_ark3'){ 
    $insert_status= db_query("update payment_status set `installment_id`=" . $id . ",lead_id=" . $lead_id . ",`payment_ark3`=".$installment1.",`payment_var3`=0,`ark_status`=1,`var_status`=0 where installment_id=".$id);

}
if($var=='payment_var4'){ 
    $insert_status= db_query("update payment_status set `installment_id`=" . $id . ",lead_id=" . $lead_id . ",`payment_var4`=".$installment1.",`payment_ark4`=0,`var_status`=1,`ark_status`=0 where installment_id=".$id);

}
if($ark=='payment_ark4'){ 
    $insert_status= db_query("update payment_status set `installment_id`=" . $id . ",lead_id=" . $lead_id . ",`payment_ark4`=".$installment1.",`payment_var4`=0,`ark_status`=1,`var_status`=0 where installment_id=".$id);

}
if($var=='payment_var5'){ 
    $insert_status= db_query("update payment_status set `installment_id`=" . $id . ",lead_id=" . $lead_id . ",`payment_var5`=".$installment1.",`payment_ark5`=0,`var_status`=1,`ark_status`=0 where installment_id=".$id);

}
if($ark=='payment_ark5'){ 
    $insert_status= db_query("update payment_status set `installment_id`=" . $id . ",lead_id=" . $lead_id . ",`payment_ark5`=".$installment1.",`payment_var5`=0,`ark_status`=1,`var_status`=0 where installment_id=".$id);

}
if($var=='payment_var6'){ 
    $insert_status= db_query("update payment_status set `installment_id`=" . $id . ",lead_id=" . $lead_id . ",`payment_var6`=".$installment1.",`payment_ark6`=0,`var_status`=1,`ark_status`=0 where installment_id=".$id);

}
if($ark=='payment_ark6'){ 
    $insert_status= db_query("update payment_status set `installment_id`=" . $id . ",lead_id=" . $lead_id . ",`payment_ark6`=".$installment1.",`payment_var6`=0,`ark_status`=1,`var_status`=0 where installment_id=".$id);

}

if($revoke_status=='revoke1'){
    $data = ['payment_var1' => 0,'payment_ark1' => 0,'var_status' => 0,'ark_status' =>0];
    $where = ['installment_id'=>$id];
    $update_status = $update_payment->update($data,'payment_status',$where);
}
if($revoke_status=='revoke2'){
    $data = ['payment_var2' => 0,'payment_ark2' => 0,'var_status' => 0,'ark_status' =>0];
    $where = ['installment_id'=>$id];
    $update_status = $update_payment->update($data,'payment_status',$where);
}
if($revoke_status=='revoke3'){
    $data = ['payment_var3' => 0,'payment_ark3' => 0,'var_status' => 0,'ark_status' =>0];
    $where = ['installment_id'=>$id];
    $update_status = $update_payment->update($data,'payment_status',$where);
}
if($revoke_status=='revoke4'){
    $data = ['payment_var4' => 0,'payment_ark4' => 0,'var_status' => 0,'ark_status' =>0];
    $where = ['installment_id'=>$id];
    $update_status = $update_payment->update($data,'payment_status',$where);
}
if($revoke_status=='revoke5'){
    $data = ['payment_var5' => 0,'payment_ark5' => 0,'var_status' => 0,'ark_status' =>0];
    $where = ['installment_id'=>$id];
    $update_status = $update_payment->update($data,'payment_status',$where);
}
if($revoke_status=='revoke6'){
    $data = ['payment_var6' => 0,'payment_ark6' => 0,'var_status' => 0,'ark_status' =>0];
    $where = ['installment_id'=>$id];
    $update_status = $update_payment->update($data,'payment_status',$where);
}