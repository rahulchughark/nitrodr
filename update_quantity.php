<?php include('includes/include.php');

if($_GET['id'])
{
    $old_quantity=getSingleresult("select quantity from orders where id=".$_GET['id']);
     
      
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_REQUEST['id']."','Quantity','".$old_quantity."','". $_GET['quantity']."',now(),'".$_SESSION['user_id']."')");
    $sql=db_query("update orders set quantity='".$_GET['quantity']."' where id=".$_GET['id']);
	 
}

if($_GET['campaign_id'])
{
    $sql=db_query("update orders set campaign_type=0 where id=".$_GET['campaign_id']);
}

if($_GET['oid']){
    $sql=db_query("update orders set campaign_type='".$_GET['type']."' where id=".$_GET['oid']);
}

if($sql)
{
    echo 1;    
    exit();
}


?>