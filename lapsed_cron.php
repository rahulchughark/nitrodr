<?php include('includes/include.php');
ini_set('max_execution_time', 300);
$date=date('Y-m-d');
$date_check=date('Y-m-d', strtotime('-60 day', strtotime($date)));
//echo $date_check;
$query=db_query("select distinct activity_log.pid,activity_log.created_date from activity_log left join tbl_lead_product tp on activity_log.pid=tp.lead_id left join orders o on activity_log.pid=o.id where date(activity_log.created_date)>'".$date_check."' and activity_log.activity_type='Lead' and tp.product_type_id in (1,2) and o.license_type ='Commercial' order by activity_log.created_date desc");
$pid_al=array();
while($data=db_fetch_array($query))
{
    $pid_al[]=$data['pid'];
    //echo $data['created_date'].'<br>';
}
//echo "select distinct pid,created_date from activity_log where date(created_date)>'".$date_check."' order by created_date desc";
///echo count($pid_al);
$query_call=db_query("select distinct caller_comments.pid,caller_comments.created_date from caller_comments left join tbl_lead_product tp on caller_comments.pid=tp.lead_id left join orders o on caller_comments.pid=o.id where date(caller_comments.created_date)>'".$date_check."' and tp.product_type_id in (1,2) and o.license_type ='Commercial' order by caller_comments.created_date desc");
$pid_call=array();
while($data_call=db_fetch_array($query_call))
{
    $pid_call[]=$data_call['pid'];
    //echo $data['created_date'].'<br>';
} 
//echo "select distinct pid,created_date from caller_comments where date(created_date)>'".$date_check."' order by created_date desc";
//echo count($pid_call); die;


$query2=db_query("select distinct lead_modify_log.lead_id,lead_modify_log.created_date from lead_modify_log left join tbl_lead_product tp on lead_modify_log.lead_id=tp.lead_id left join orders o on lead_modify_log.lead_id=o.id where date(lead_modify_log.created_date)>'".$date_check."' and tp.product_type_id in (1,2) and o.license_type ='Commercial' order by lead_modify_log.created_date desc");
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


 $leads=db_query("select orders.id,orders.prospecting_date from orders left join tbl_lead_product tp on orders.id=tp.lead_id where (date(orders.prospecting_date)>'".$date_check."' or orders.stage in ('OEM Billing','EU PO Issued','Booking') ) and orders.dvr_flag=0 and orders.license_type ='Commercial' and tp.product_type_id in (1,2)");
 while($leads_data=db_fetch_array($leads))
 {
     $leads_id[]=$leads_data['id'];
 }
 //echo count($leads_id); die;
  $final_arr=array_merge($pid_al,$pid_lml,$leads_id, $pid_call);
  $final_array=array_unique($final_arr);
  //echo count($final_array); die;
  $final_array_string=implode(',',$final_array);

if(count($final_array))
{
 $leads=db_query("select id from orders where id not in (".$final_array_string.") and date(created_date)<'".$date_check."' and license_type='Commercial'");

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
//print_r($leads_update);
//echo count($leads_lapsed);
echo "<pre>";
echo "added";
//print_r($leads_lapsed);
}