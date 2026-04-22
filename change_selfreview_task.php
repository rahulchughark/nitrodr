<?php include('includes/include.php');    
if($_REQUEST['rev_id']) {
$review=db_query("update selfreview_tasks set status='Done' where id=".$_REQUEST['rev_id']);
//echo "update selfreview_tasks set status='Done' where id=".$_REQUEST['rev_id']; die;
if($review){
   echo 'success';

}else{

    echo 'Error :'. mysql_info();
}
}
else
{
echo 0;
}

?>