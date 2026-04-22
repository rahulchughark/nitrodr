<?php include('includes/include.php');
ini_set('max_execution_time', 300);
$date=date('Y-m-d');
$date_check=date('Y-m-d', strtotime('-60 day', strtotime($date)));
//echo $date_check;
$query=db_query("select distinct pid,created_date from activity_log where date(created_date)>'".$date_check."' order by created_date desc");
$pid_al=array();
while($data=db_fetch_array($query))
{
    $pid_al[]=$data['pid'];
    //echo $data['created_date'].'<br>';
}
//echo "select distinct pid,created_date from activity_log where date(created_date)>'".$date_check."' order by created_date desc";
///echo count($pid_al);
$query_call=db_query("select distinct pid,created_date from caller_comments where date(created_date)>'".$date_check."' order by created_date desc");
$pid_call=array();
while($data_call=db_fetch_array($query_call))
{
    $pid_call[]=$data_call['pid'];
    //echo $data['created_date'].'<br>';
} 
//echo "select distinct pid,created_date from caller_comments where date(created_date)>'".$date_check."' order by created_date desc";
//echo count($pid_call); die;


$query2=db_query("select distinct lead_id,created_date from lead_modify_log where date(created_date)>'".$date_check."' order by created_date desc");
$pid_lml=array();
while($data2=db_fetch_array($query2))
{
    $pid_lml[]=$data2['lead_id'];
    //echo $data2['created_date'].'<br>';
}
//echo "select distinct lead_id,created_date from lead_modify_log where date(created_date)>'".$date_check."' order by created_date desc";
//echo count($pid_lml); die;
//echo '<pre>';
//echo count($pid_lml).'<br>';
//print_r($pid_lml); die;
//$arr_intsect=array_intersect($pid_lml,$pid_al);
//echo count($arr_intsect);
//print_r($arr_intsect); die;


 $leads=db_query("select id,prospecting_date from orders where (date(prospecting_date)>'".$date_check."' or stage in ('OEM Billing','EU PO Issued','Booking') ) and dvr_flag=0");
 while($leads_data=db_fetch_array($leads))
 {
     $leads_id[]=$leads_data['id'];
 }
 //echo count($leads_id); die;
  $final_arr=array_merge($pid_al,$pid_lml,$leads_id, $pid_call);
  $final_array=array_unique($final_arr);
  //echo count($final_array); die;
  $final_array_string=implode(',',$final_array);
  //echo '<pre>';
//echo count($final_array).'<br>';
//print_r($final_array); die;
//echo "select id from orders where id not in (".$final_array_string.")"; die;
if(count($final_array))
{
 $leads=db_query("select id from orders where id not in (".$final_array_string.") and date(created_date)<'".$date_check."'");
 while($data_leads=db_fetch_array($leads))
{
    $leads_lapsed[]=$data_leads['id'];
    
    if(!getSingleresult("select id from lapsed_orders where id=".$data_leads['id']))
    {
       
        $dd = db_query("update orders set lapsed_date='".date('Y-m-d H:i:s')."' where id=".$data_leads['id']);
    $leads_copy=db_query("insert into lapsed_orders select * from orders where id=".$data_leads['id']);
    
    $orders_del=db_query("delete from orders where id=".$data_leads['id']);
    }

}

$leads_update=db_query("update lapsed_orders set lapsed_date='".date('Y-m-d H:i:s')."' where id in (".implode(',',$leads_lapsed).")");

//echo count($leads_lapsed);
echo "<pre>";
print_r($leads_lapsed);
}