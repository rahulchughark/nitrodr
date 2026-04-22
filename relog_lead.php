<?php include('includes/include.php');
if(!getSingleresult("select id from relog where pid=".$_GET['id']))
{
if($_GET['id'])
{
	
	$date=getSingleresult("select close_time from orders where id=".$_GET['id']);
	$newdate=date('Y-m-d H:i:s',strtotime('+30 days',strtotime($date)));
    $sql=db_query("update orders set close_time='".$newdate."' where id=".$_GET['id']);
	//echo "insert into relog (pid,closer_date,new_date) values ('".$_GET['id']."','".$date."','".$newdate."')";
	$sqlInsert=db_query("insert into relog (pid,closer_date,new_date) values ('".$_GET['id']."','".$date."','".$newdate."')"); 
}

if($sql)
{
    echo 1;    
    exit();
}
}
else
{
	echo 2;    
    exit();
}


?>