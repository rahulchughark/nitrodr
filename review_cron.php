<?php include('includes/include.php');
 $date=date('Y-m-d');
$data_check=date('Y-m-d', strtotime('-7 day', strtotime($date)));
 //echo "select orders.id from orders join activity_log on orders.id=activity_log.pid where orders.stage in (NULL,'Prospecting','Follow-up','Quote','Negotiation','Commit','EU PO Issued') and date(orders.created_date)>'2019-03-15' and orders.prospecting_date<'".$data_check."' and orders.dvr_flag='0' and orders.status='Approved' and activity_log.created_date not between '".$data_check."' and '".date('Y-m-d')."'"; die;
 
//  echo $data_check; die;
$query=db_query("select DISTINCT(activity_log.pid) as id from activity_log join orders on orders.id=activity_log.pid where orders.stage != 'OEM Billing' and date(orders.created_date)>'2019-03-15' and orders.dvr_flag='0' and orders.status='Approved' and orders.license_type='Commercial' and activity_log.created_date not between '".$data_check."' and '".date('Y-m-d')."'");

$query2=db_query("select DISTINCT(lead_modify_log.lead_id) as id from lead_modify_log join orders on orders.id=lead_modify_log.lead_id where orders.stage != 'OEM Billing' and date(orders.created_date)>'2019-03-15' and orders.dvr_flag='0' and orders.status='Approved' and orders.license_type='Commercial' and lead_modify_log.created_date not between '".$data_check."' and '".date('Y-m-d')."' and lead_modify_log.type='Stage' ");


if(mysqli_num_rows($query2))
{
    while($data2=db_fetch_array($query2))
     {
        $pid_lml[]=$data2['id'];
     }
}

if(mysqli_num_rows($query))
{
    while($data=db_fetch_array($query))
    {
        $pid_al[]=$data['id'];

    }
}
 
  $final_arr=array_merge($pid_al,$pid_lml);
  $final_array=array_unique($final_arr);
  
 echo count($final_array); die;
 if(count($final_array))
 {
  foreach($final_array as $leadId)
  {
    if(!getSingleresult("select id from lead_review where lead_id='".$leadId."'"))
    {
    $inser=db_query("insert into lead_review (lead_id,is_review) values ('".$leadId."',1)");
    // echo 'Lead added --ID:'.$leadId;
    // echo '<br>';
    }
    else
    {
      $update=db_query("update lead_review set is_review=1 where lead_id='".$leadId."'");
   // echo 'Lead Updated --ID:'.$data['id'];
   //  echo '<br>';
    }
  }
}

 // print_r($i);die;
// else
// {
//     echo '<h1>No Data Found</h1>'; 
// } 

// $data='';
// $data_check='';
// $date=date('Y-m-d');
// $data_check=date('Y-m-d', strtotime('-13 day', strtotime($date)));
 
// $query2=db_query("select DISTINCT(orders.id) from orders join activity_log on orders.id=activity_log.pid left join tbl_lead_product tp on orders.id=tp.lead_id where orders.stage in (NULL,'Prospecting','Follow-up','Quote','Negotiation','Commit','EU PO Issued') and date(orders.created_date)>'2019-03-15' and orders.prospecting_date<'".$data_check."' and orders.dvr_flag='0' and orders.status='Approved' and orders.license_type='Commercial' and tp.product_type_id in (1,2) and activity_log.created_date not between '".$data_check."' and '".date('Y-m-d')."'");
// while($data2=db_fetch_array($query2))
// {
//     if(!getSingleresult("select id from lead_review where lead_id='".$data2['id']."'"))
//     {
//         $email=db_query("select r_email,r_name,r_user,lead_type,company_name,eu_email,eu_mobile,team_id,quantity,stage,caller from orders where id=".$data2['id']);
//         $data3=db_fetch_array($email); 
//         //$mail->AddCC("prashant.dongrikar@arkinfo.in", "Prashant"); 
//         $mail->AddCC("pradnya.chaukekar@arkinfo.in"); 
//         $mail->AddCC("kailash.bhurke@arkinfo.in");    
//         //$mail->AddBCC("isha.mittal@arkinfo.in", "Isha Mittal"); 
         
//         $sm_email=getSingleresult("select users.email as email from users join partners on partners.sm_user=users.id where partners.id='".$data3['team_id']."'");
//         if($sm_email)
//         $mail->AddCC($sm_email);

//             $manager_email=getSingleresult("select email from users where user_type='MNGR' and team_id=".$data3['team_id']);

//            $mail->AddAddress($data3['r_email']);
//            $mail->AddCC($manager_email);
//             if($data3['caller']!='')
//             {
//             $caller_email1=db_query("select users.email as call_email,users.name as caller_name from users join callers on users.id=callers.user_id where callers.id=".$data3['caller']);
//             $caller_email=db_fetch_array($caller_email1); 
//             $mail->AddCC($caller_email['call_email']);     
//             }
//     $mail->Subject = "Review Warning for ".$data3['company_name'] ;
//     $mail->Body    = "Hello,<br><br><p style='color:red;font-size:18px;font-weight:900'>Alert...!!!</p> <br><br>Below account will move to <b>&quot;Under Review&quot;</b> effective by ".date('d-m-Y',strtotime('+2 day', strtotime($date)))."<br><br> Kindly take next POA and update Log A Call & Stage on immediate basis.
//     <ul>
//     <li><b>Submitted  By</b> : ".$data3['r_user']." </li>
//     <li><b>Account Name</b> : ".$data3['company_name']." </li>
//     <li><b>Lead Type</b> : ".$data3['lead_type']." </li>
//     <li><b>Quantity</b> : ".$data3['quantity']." </li>
//     <li><b>Caller Assigned</b> : ".($caller_email['caller_name']?$caller_email['caller_name']:'N/A')." </li>
//     <li><b>Stage</b> : ".$data3['stage']." </li></ul><br>
//     Thanks,<br>
//     SketchUp DR Portal
//     ";
//     $mail->Send();  
//     $mail->ClearAllRecipients();
//     $data3['caller']='';
//     $caller_email['caller_name']='';
//     }
// }

?>