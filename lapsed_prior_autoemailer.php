<?php include('includes/include.php');
ini_set('max_execution_time', 300);
$date=date('Y-m-d');
$date_check=date('Y-m-d', strtotime('-58 days', strtotime($date)));
// echo $date_check; die();
$lapsedDate = date('Y-m-d', strtotime('+2 days', strtotime($date)));
$query=db_query("select distinct activity_log.pid,activity_log.created_date from activity_log left join tbl_lead_product tp on activity_log.pid=tp.lead_id left join orders o on activity_log.pid=o.id where date(activity_log.created_date)>'".$date_check."' and activity_log.activity_type='Lead' and tp.product_type_id in (1,2) and o.license_type ='Commercial' order by activity_log.created_date desc");
$pid_al=array();
while($data=db_fetch_array($query))
{
    $pid_al[]=$data['pid'];
}

$query_call=db_query("select distinct caller_comments.pid,caller_comments.created_date from caller_comments left join tbl_lead_product tp on caller_comments.pid=tp.lead_id left join orders o on caller_comments.pid=o.id where date(caller_comments.created_date)>'".$date_check."' and tp.product_type_id in (1,2) and o.license_type ='Commercial' order by caller_comments.created_date desc");
$pid_call=array();
while($data_call=db_fetch_array($query_call))
{
    $pid_call[]=$data_call['pid'];
    //echo $data['created_date'].'<br>';
} 

$query2=db_query("select distinct lead_modify_log.lead_id,lead_modify_log.created_date from lead_modify_log left join tbl_lead_product tp on lead_modify_log.lead_id=tp.lead_id left join orders o on lead_modify_log.lead_id=o.id where date(lead_modify_log.created_date)>'".$date_check."' and tp.product_type_id in (1,2) and o.license_type ='Commercial' order by lead_modify_log.created_date desc");
$pid_lml=array();
while($data2=db_fetch_array($query2))
{
    $pid_lml[]=$data2['lead_id'];
    //echo $data2['created_date'].'<br>';
}

 $leads=db_query("select orders.id,orders.prospecting_date from orders left join tbl_lead_product tp on orders.id=tp.lead_id where (date(orders.prospecting_date)>'".$date_check."' or orders.stage in ('OEM Billing','EU PO Issued','Booking') ) and orders.dvr_flag=0 and orders.license_type ='Commercial' and tp.product_type_id in (1,2)");
 while($leads_data=db_fetch_array($leads))
 {
     $leads_id[]=$leads_data['id'];
 }
 //echo count($leads_id); die;
  $final_arr=array_merge($pid_al,$pid_lml,$leads_id, $pid_call);
  $final_array=array_unique($final_arr);
//   echo count($final_array); die;
  $final_array_string=implode(',',$final_array);

if(count($final_array))
{
 $leads=db_query("select id from orders where id not in (".$final_array_string.") and date(created_date)<'".$date_check."' and  license_type='Commercial'");
 
 while($data_leads=db_fetch_array($leads))
{
    $leads_lapsed[]=$data_leads['id'];
    
    if(!getSingleresult("select id from lapsed_orders where id=".$data_leads['id']))
    {
      // auto emailers code start 
        $email = db_query("select r_email,r_name,r_user,lead_type,company_name,eu_name,eu_email,eu_mobile,team_id,quantity,partner_close_date,caller,created_by from orders where id=".$data_leads['id']);

        
       $sm_email = getSingleresult("select email from users as u left join partners as p on u.id=p.sm_user where p.id='" . $row_data['team_id'] . "'");
        //print_r($sm_email);die;

        $data = db_fetch_array($email);
      
        $manager_email = getSingleresult("select email from users where user_type='MNGR' and team_id=" . $data['team_id']);
        $submitedby_email = getSingleresult("select email from users where id=".$data['created_by']." and team_id=".$data['team_id']);

        $mail->AddAddress($submitedby_email);
        $mail->AddCC($manager_email);
        if ($sm_email)
        {
          $mail->AddCC($sm_email); // sales manager email
        }
        $mail->AddBCC('virendra.kumar@arkinfo.in');

        $mail->Subject = "Your account is about to lapsed - ".$data['company_name'];
        $mail->Body    = "Hi,<br><br> Below account is going to get lapsed due to no activity effective on ( ".$lapsedDate." )<br><br>
                <ul>
                <li><b>Account Name</b> : " . $data['company_name'] . " </li>
                <li><b>Quantity</b> : " . $data['quantity'] . " </li>
                <li><b>Customer Name</b> : ". $data['eu_name'] ." </li>
                <li><b>Mobile Number</b> : ". $data['eu_mobile'] ." </li>
                <li><b>Email ID</b> : ". $data['eu_email'] ." </li></ul>
                <p>Please do update or else it will be lapsed</p><br>
                Thanks,<br>
                SketchUp DR Portal
                ";
        echo "<pre>";
        echo $mail->Body;
        // $mail->Send();
        // $mail->ClearAllRecipients();

       // auto emailer code end
    }

}

echo "<pre>";
echo "Cron Exicuted";

}

?>