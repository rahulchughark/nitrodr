<?php include('includes/include.php');

if($_GET['association'])
{
    $old_association = getSingleresult("select association_name from orders where id=".$_GET['id']);
     
      
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_REQUEST['id']."','Association Name','".$old_association."','". $_GET['association']."',now(),'".$_SESSION['user_id']."')");

    $sql=db_query("update orders set association_name='".$_GET['association']."' where id=".$_GET['id']);
	 
}

if($_GET['raw_association'])
{
    $old_association = getSingleresult("select association_name from raw_leads where id=".$_GET['id']);
     
      
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_REQUEST['id']."','Association Name','".$old_association."','". $_GET['raw_association']."',now(),'".$_SESSION['user_id']."')");

    $sql=db_query("update raw_leads set association_name='".$_GET['raw_association']."' where id=".$_GET['id']);
	 
}

if($_GET['lapsed_association'])
{
    $old_association = getSingleresult("select association_name from lapsed_orders where id=".$_GET['id']);
     
      
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_REQUEST['id']."','Association Name','".$old_association."','". $_GET['lapsed_association']."',now(),'".$_SESSION['user_id']."')");

    $sql=db_query("update lapsed_orders set association_name='".$_GET['lapsed_association']."' where id=".$_GET['id']);
	 
}


if($sql)
{
    echo 1;    
    exit();
}


?>