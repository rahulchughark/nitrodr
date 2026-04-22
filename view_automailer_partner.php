<?php include('includes/include.php');
if(date('l')!='Sunday')
{
    
ini_set('max_execution_time', 0);

if(date('l')=='Monday'){			
$dat=date('Y-m-d',strtotime("-2 days"));
$mail->Subject = "Daily Activity Report - ".date('jS F Y',strtotime("-2 days")).".";
}
else
{
  $dat=date('Y-m-d',strtotime("-1 days"));  
  $mail->Subject = "Daily Activity Report - ".date('jS F Y',strtotime("-1 days")).".";
}
      if(date('d')=='01')
        {
            $dat1=date("Y-m-01", strtotime("-1 months"));
            $dat2=date('Y-m-t', strtotime("-1 months"));
        }
        else
        {
        $dat1=date('Y-m-01');
        $dat2=date('Y-m-t');
        }        
		  
			
$resellers=db_query("select id,name from partners where id not in (45,25,37,53)");
while($data=db_fetch_array($resellers))
{
	$manager_email=getSingleresult("select email from users where user_type='MNGR' and team_id='".$data['id']."' and status='Active'");
	$mail->AddAddress($manager_email);
	$mail->Body='<style type="text/css"> 
.TFtable{ width:100%; border-collapse:collapse; font-family:verdana; } 
.TFtable td{ padding:7px; border:#4e95f4 1px solid; } 
.TFtable th{ padding:7px; border:#4e95f4 1px solid;background: #acdc9c; } 
.TFtable tr{ background: #b8d1f3; } 
.odd{ background: #b8d1f3; } 
.even{ background: #dae5f4; }
.first_table td{background: #061621;color:#FFFDFC;background: -moz-linear-gradient(top, #445058 0%, #1f2d37 66%, #061621 100%);
  background: -webkit-linear-gradient(top, #445058 0%, #1f2d37 66%, #061621 100%);
  background: linear-gradient(to bottom, #445058 0%, #1f2d37 66%, #061621 100%);
  border: 2px solid #444444;} 
 .second_table td{ 
   background: #371044;
  background: -moz-linear-gradient(top, #a6554b 0%, #943327 66%, #891D0F 100%);
  background: -webkit-linear-gradient(top, #a6554b 0%, #943327 66%, #891D0F 100%);
  background: linear-gradient(to bottom, #a6554b 0%, #943327 66%, #891D0F 100%);
  border: 2px solid #444444;color:#fff;
}
  </style>';		
		$mail->Body.='Dear Sir,<br><br>
					 Please find the below DR Portal activity report for team:<br><br>';
		$mail->Body.='<div style="margin:0px;padding:5px;background-color:#fff;">';
		
			$mail->Body .='<table class="TFtable" style="text-align:center;font-size:11px;" border="1">
<thead class="first_table">
<tr>
<td colspan="16"><strong>Daily Report</strong></td>
</tr>
<tr>
<td colspan="12"><strong>Stage</strong></td>
<td rowspan="3"><strong>Visibility for day</strong></td>
</tr>
<tr>
<td rowspan="2"><strong>Sr. Number</strong></td>
<td rowspan="2"><strong>'.$data['name'].'</strong></td>
<td><strong>Data Received</strong></td>
<td><strong>DVR</strong></td>
<td><strong>Calls</strong></td>
<td rowspan="2">&nbsp;</td>
<td rowspan="2"><strong>Quote</strong></td>
<td rowspan="2"><strong>Follow-Up</strong></td>
<td rowspan="2"><strong>Commit</strong></td>
<td rowspan="2"><strong>Booking</strong></td>
<td rowspan="2"><strong>EU PO Issued</strong></td>
 
<td rowspan="2"><strong>OEM Billing</strong></td>
</tr>
<tr>
<td><strong>Qualified</strong></td>
<td><strong>Daily Visit Updated</strong></td>
<td><strong>(Log A Calls)</strong></td>
</tr></thead><tbody>';
$users1=db_query("select * from users where team_id='".$data['id']."' and status='Active'");
$i=1;
  
while($users=db_fetch_array($users1))
{
//approved
$qualified_data=getSingleresult("select count(id) as leads from orders where  team_id='".$data['id']."' and license_type='Commercial' and  date(created_date)='".$dat."' and created_by='".$users['id']."' and status='Approved'");	
$total_qualified+=$qualified_data;
//end//
$visit_data=getSingleresult("SELECT count(activity_log.id) FROM  activity_log left join orders on activity_log.pid=orders.id where orders.team_id='".$data['id']."' and orders.license_type='Commercial' and date(activity_log.created_date)='".$dat."' and activity_log.call_subject in ('Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech. Support Visit','Validation Visit') and activity_log.added_by='".$users['id']."'");
$dvr_data=getSingleresult("SELECT count(id) FROM orders where orders.team_id='".$data['id']."' and orders.license_type='Commercial' and ((date(orders.created_date) ='".$dat."') or (date(orders.date_dvr)='".$dat."') ) and is_dr=1 and (orders.created_by='".$users['id']."' or dvr_by='".$users['id']."')");
$dvr_data+=$visit_data;
$total_dvr+=$dvr_data;
//
$log_call=getSingleresult("SELECT count(*) FROM `activity_log` JOIN orders on orders.id=activity_log.pid where orders.team_id='".$data['id']."' and orders.license_type='Commercial' and date(activity_log.created_date) ='".$dat."' and activity_log.call_subject in ('Call','LC Calling','Send Quote','Follow up Call') and activity_log.added_by='".$users['id']."'");
$total_lac+=$log_call;
//
$quote=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and stage='Quote' and license_type='Commercial' and date(partner_close_date)='".$dat."' and orders.created_by='".$users['id']."'");
$total_quote+=$quote;
//
$verification=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and license_type='Commercial' and (stage='Verification' or stage='Follow-Up') and date(partner_close_date)='".$dat."' and created_by='".$users['id']."'");
$total_verification+=$verification;
//
$commit=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and stage='Commit' and license_type='Commercial'  and date(partner_close_date)='".$dat."' and created_by='".$users['id']."'");
$total_commit+=$commit;
//
$booking=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and stage='Booking' and license_type='Commercial'  and date(partner_close_date)='".$dat."'  and created_by='".$users['id']."'");
$total_booking+=$booking;
$eupo=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and stage='EU PO Issued' and license_type='Commercial' and op_this_month='Yes'   and date(partner_close_date)='".$dat."' and created_by='".$users['id']."'");
$total_eupo+=$eupo;
//
$oem=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and stage='OEM Billing' and license_type='Commercial' and date(partner_close_date)='".$dat."' and created_by='".$users['id']."'");
$total_oem+=$oem;
//

$total_acc=$commit+$booking+$oem+$eupo;

$grand_total_acc+=$total_acc;

$quote_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where  team_id='".$data['id']."' and license_type='Commercial' and  date(created_date)='".$dat."' and status='Approved' and created_by='".$users['id']."' and stage='Quote' and orders.license_type='Commercial'  and date(partner_close_date)='".$dat."'");
$total_quote_quantity+=$quote_quantity;
//
$verification_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and license_type='Commercial' and (stage='Verification' or stage='Follow-Up') and date(partner_close_date)='".$dat."'  and created_by='".$users['id']."'");
$total_verification_quantity+=$verification_quantity;
//
$commit_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and license_type='Commercial' and stage='Commit' and date(partner_close_date)='".$dat."'  and created_by='".$users['id']."'");
$total_commit_quantity+=$commit_quantity;
//
$booking_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and license_type='Commercial' and stage='Booking'and date(partner_close_date)='".$dat."'  and created_by='".$users['id']."'");
$total_booking_quantity+=$booking_quantity;
//
$eupo_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and license_type='Commercial' and  stage='EU PO Issued' and op_this_month='Yes'   and date(partner_close_date)='".$dat."'  and created_by='".$users['id']."'");
$total_eupo_quantity+=$eupo_quantity;
//
$oem_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and license_type='Commercial' and stage='OEM Billing' and date(partner_close_date)='".$dat."'  and created_by='".$users['id']."'");
$total_oem_quantity+=$oem_quantity;
//
$total_quantity=$commit_quantity+$booking_quantity+$oem_quantity+$eupo_quantity;
$grand_total_quantity+=$total_quantity;

if($i%2)
{
	$class_new='class="even"';
}
else
	{
		$class_new='class="odd"';
	}
	
 $mail->Body.='<tr>
<td '.$class_new.' rowspan="2">'.$i.'</td>
<td '.$class_new.' rowspan="2">'.$users['name'].'</td>
<td '.$class_new.' rowspan="2">'.$qualified_data.'</td>
<td  '.$class_new.' rowspan="2">'.$dvr_data.'</td>
<td '.$class_new.'  rowspan="2">'.$log_call.'</td>
<td '.$class_new.' >No. of Accounts</td>
<td '.$class_new.' >'.$quote.'</td>
<td '.$class_new.' >'.$verification.'</td>
<td '.$class_new.' >'.$commit.'</td>
<td '.$class_new.' >'.$booking.'</td>
<td '.$class_new.' >'.$eupo.'</td>
<td '.$class_new.' >'.$oem.'</td>
<td '.$class_new.' >'.$total_acc.'</td>
</tr>
<tr>
<td '.$class_new.' >No. of Licenses</td>
<td '.$class_new.' >'.$quote_quantity.'</td>
<td '.$class_new.' >'.$verification_quantity.'</td>
<td '.$class_new.' >'.$commit_quantity.'</td>
<td '.$class_new.' >'.$booking_quantity.'</td>
<td '.$class_new.' >'.$eupo_quantity.'</td>
<td '.$class_new.' >'.$oem_quantity.'</td>
<td '.$class_new.' >'.$total_quantity.'</td>
</tr>';
$i++;}
$mail->Body.='<tr>
<th rowspan="2" colspan="2">Total</th> 
<th rowspan="2">'.$total_qualified.'</th>
<th rowspan="2">'.$total_dvr.'</th>
<th rowspan="2">'.$total_lac.'</th>
<th>No. of Accounts</th>
<th>'.$total_quote.'</th>
<th>'.$total_verification.'</th>
<th>'.$total_commit.'</th>
<th>'.$total_booking.'</th>
<th>'.$total_eupo.'</th>
<th>'.$total_oem.'</th>
<th>'.$grand_total_acc.'</th>
</tr>
<tr>
<th>No. of Licenses</th>
<th>'.$total_quote_quantity.'</th>
<th>'.$total_verification_quantity.'</th>
<th>'.$total_commit_quantity.'</th>
<th>'.$total_booking_quantity.'</th>
<th>'.$total_eupo_quantity.'</th>
 
<th>'.$total_oem_quantity.'</th>
<th>'.$grand_total_quantity.'</th>
</tr>';


$mail->Body.='</tbody>
</table><br/><br/>';

$qualified_data=0;
$dvr_data =0;
$log_call =0;
$quote=0;
$verification=0;
$commit=0;
$booking=0;
$oem=0;
$total_acc=0;
$quote_quantity=0;
$verification_quantity=0;
$commit_quantity=0;
$booking_quantity=0;
$oem_quantity=0;
$total_quantity=0;
$total_qualified=0;
$total_dvr=0;
$total_lac=0;
$total_quote=0;
$total_verification=0;
$total_commit=0;
$total_booking=0;
$total_oem=0;
$grand_total_acc=0;
$total_quote_quantity=0;
$total_verification_quantity=0;
$total_commit_quantity=0;
$total_booking_quantity=0;
$total_oem_quantity=0;
$grand_total_quantity=0;
$total_eupo_quantity=0;
$total_process_this_month_quantity=0;
$total_eupo=0;


$dat1=date('Y-m-01');
$dat2=date('Y-m-t');
	 
$mail->Body.='<table class="TFtable" style="text-align:center;font-size:11px;" border="1">
<thead class="second_table">
<tr>
<td colspan="13"><strong>Monthly Report</strong></td>
</tr>
<tr>
<td colspan="12"><strong>Stage</strong></td>
<td rowspan="3"><strong>Visibility for Month</strong></td>
</tr>
<tr>
<td rowspan="2"><strong>Sr. Number</strong></td>
<td rowspan="2"><strong>'.$data['name'].'</strong></td>
<td><strong>Data Received</strong></td>
<td><strong>DVR</strong></td>
<td><strong>Calls</strong></td>
<td rowspan="2">&nbsp;</td>
<td rowspan="2"><strong>Quote</strong></td>
<td rowspan="2"><strong>Follow-Up</strong></td>
<td rowspan="2"><strong>Commit</strong></td>
<td rowspan="2"><strong>Booking</strong></td>
<td rowspan="2"><strong>EU PO Issued</strong></td>
<td rowspan="2"><strong>OEM Billing</strong></td>
</tr>
<tr>
<td><strong>Qualified</strong></td>
<td><strong>Daily Visit Updated</strong></td>
<td><strong>(Log A Calls)</strong></td>
</tr></thead><tbody>';
$users2=db_query("select * from users where team_id='".$data['id']."' and status='Active' ");
$sm_email=getSingleresult("select users.email as email from users join partners on partners.sm_user=users.id where partners.id='".$data['id']."'");
if($sm_email)
$mail->AddCC($sm_email);
$i=1;
while($users=db_fetch_array($users2))
{
 $mail->AddCC($users['email']);

$qualified_data=getSingleresult("select count(id) as leads from orders where  team_id='".$data['id']."' and license_type='Commercial' and ( date(created_date) BETWEEN  '".$dat1."' and '".$dat2."')  and status='Approved' and created_by='".$users['id']."'");	
$total_qualified+=$qualified_data;
//end//
$visit_data=getSingleresult("SELECT count(activity_log.id) FROM  activity_log left join orders on activity_log.pid=orders.id where orders.team_id='".$data['id']."' and orders.license_type='Commercial' and (date(activity_log.created_date) BETWEEN  '".$dat1."' and '".$dat2."') and activity_log.call_subject in ('Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech. Support Visit','Validation Visit') and activity_log.added_by='".$users['id']."'");
$dvr_data=getSingleresult("SELECT count(id) FROM orders where orders.team_id='".$data['id']."' and orders.license_type='Commercial' and ((date(orders.created_date) between '".$dat1."' and '".$dat2."') or (date(orders.date_dvr) between '".$dat1."' and '".$dat2."') ) and orders.is_dr=1 and orders.created_by='".$users['id']."'");
$dvr_data+=$visit_data;
$total_dvr+=$dvr_data;
//
$log_call=getSingleresult("SELECT count(*) FROM `activity_log` JOIN orders on orders.id=activity_log.pid where orders.team_id='".$data['id']."' and orders.license_type='Commercial' and (date(activity_log.created_date) BETWEEN  '".$dat1."' and '".$dat2."') and activity_log.call_subject in ('Call','LC Calling','Send Quote','Follow up Call') and activity_log.added_by='".$users['id']."'");
$total_lac+=$log_call;
//
$quote=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and stage='Quote' and license_type='Commercial' and date(partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' and created_by='".$users['id']."'");
$total_quote+=$quote;
//
$verification=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and license_type='Commercial' and (stage='Verification' or stage='Follow-Up') and date(partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' and created_by='".$users['id']."'");
$total_verification+=$verification;
//
$commit=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and license_type='Commercial' and stage='Commit' and date(partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' and created_by='".$users['id']."'");
$total_commit+=$commit;
//
$booking=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and license_type='Commercial' and stage='Booking' and date(partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' and created_by='".$users['id']."'");
$total_booking+=$booking;
//
$eupo=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and license_type='Commercial' and stage='EU PO Issued' and date(partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' and created_by='".$users['id']."'");
$total_eupo+=$eupo;
//
$oem=getSingleresult("select count(id) as leads from orders where team_id='".$data['id']."' and stage='OEM Billing' and license_type='Commercial' and date(partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' and created_by='".$users['id']."'");
$total_oem+=$oem;
//

$total_acc=$commit+$booking+$oem+$eupo;

$grand_total_acc+=$total_acc;

$quote_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and license_type='Commercial' and stage='Quote' and license_type='Commercial' and date(partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' and created_by='".$users['id']."'");
$total_quote_quantity+=$quote_quantity;
//
$verification_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and license_type='Commercial' and (stage='Verification' or stage='Follow-Up') and date(partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' and created_by='".$users['id']."'");
$total_verification_quantity+=$verification_quantity;
//
$commit_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and license_type='Commercial' and stage='Commit' and  date(partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' and created_by='".$users['id']."'");
$total_commit_quantity+=$commit_quantity;
//
$booking_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and license_type='Commercial' and stage='Booking' and date(partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' and created_by='".$users['id']."'");
$total_booking_quantity+=$booking_quantity;
//
$eupo_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."' and license_type='Commercial' and  stage='EU PO Issued' and date(partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' and created_by='".$users['id']."'");
$total_eupo_quantity+=$eupo_quantity;
//
$oem_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders where team_id='".$data['id']."'  and license_type='Commercial' and stage='OEM Billing'  and date(partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' and created_by='".$users['id']."'");
$total_oem_quantity+=$oem_quantity;
//
$total_quantity=$commit_quantity+$booking_quantity+$oem_quantity+$eupo_quantity;
$grand_total_quantity+=$total_quantity;
	if($i%2)
{
	$class_new='class="even"';
}
else
	{
		$class_new='class="odd"';
	}
	
 $mail->Body.='<tr>
<td  '.$class_new.' rowspan="2">'.$i.'</td>
<td '.$class_new.'  rowspan="2">'.$users['name'].'</td>
<td  '.$class_new.' rowspan="2">'.$qualified_data.'</td>
<td '.$class_new.'  rowspan="2">'.$dvr_data.'</td>
<td '.$class_new.'  rowspan="2">'.$log_call.'</td>
<td '.$class_new.' >No. of Accounts</td>
<td '.$class_new.' >'.$quote.'</td>
<td '.$class_new.' >'.$verification.'</td>
<td '.$class_new.' >'.$commit.'</td>
<td '.$class_new.' >'.$booking.'</td>
<td '.$class_new.' >'.$eupo.'</td>
<td '.$class_new.' >'.$oem.'</td>
<td '.$class_new.' >'.$total_acc.'</td>
</tr>
<tr>
<td '.$class_new.' >No. of Licenses</td>
<td '.$class_new.' >'.$quote_quantity.'</td>
<td '.$class_new.' >'.$verification_quantity.'</td>
<td '.$class_new.' >'.$commit_quantity.'</td>
<td '.$class_new.' >'.$booking_quantity.'</td>
<td '.$class_new.' >'.$eupo_quantity.'</td>
<td '.$class_new.' >'.$oem_quantity.'</td>
<td '.$class_new.' >'.$total_quantity.'</td>
</tr>';
$i++;
$total_acc=0;
$total_quantity=0;
    
}
$mail->Body.='<tr>
<th rowspan="2" colspan="2">Total</th> 
<th rowspan="2">'.$total_qualified.'</th>
<th rowspan="2">'.$total_dvr.'</th>
<th rowspan="2">'.$total_lac.'</th>
<th>No. of Accounts</th>
<th>'.$total_quote.'</th>
<th>'.$total_verification.'</th>
<th>'.$total_commit.'</th>
<th>'.$total_booking.'</th>
<th>'.$total_eupo.'</th>
<th>'.$total_oem.'</th>
<th>'.$grand_total_acc.'</th>
</tr>
<tr>
<th>No. of Licenses</th>
<th>'.$total_quote_quantity.'</th>
<th>'.$total_verification_quantity.'</th>
<th>'.$total_commit_quantity.'</th>
<th>'.$total_booking_quantity.'</th>
<th>'.$total_eupo_quantity.'</th>
<th>'.$total_oem_quantity.'</th>
<th>'.$grand_total_quantity.'</th>
</tr>';

$mail->Body.='</tbody>
</table><br/><br/>';
$mail->Body.='<table class="TFtable" style="border-collapse: collapse; border: 1px solid black; ;font-size:11px;width:500px">
<tbody>
<tr>
<td style="background-color: #99cc00; text-align:center" colspan="2">
<p><strong>Monthly Report Stages- Description</strong></p>
</td>
</tr>
<tr>
<td style="background-color: #ff9900;">
<p><strong>Quote</strong></p>
</td>
<td>
<p style="text-align: left;">Till date Quote stage calculation</p>
</td>
</tr>
<tr>
<td style="background-color: #ff9900;">
<p><strong>Daily/Monthly Visibility</strong></p>
</td>
<td>
<p>= EUPO Issued + Commit + Booking + Billing</p>
</td>
</tr>
</tbody>
</table><br><br>';
		 
		$mail->Body .="<div style='width:100%'>Regards,<br>
			DR Portal Support Team.</div><br><br>";
			


			$mail->Body .="<hr><i><b>Please note:-</b> This is an auto-generated email.</i><hr>";
			$mail->Body .='</div>';
			echo $mail->Body;
			$mail->AddBCC("ankit.aggarwal@arkinfo.in"); 	  
			$mail->AddBCC("deepranshu.srivastava@arkinfo.in", "Deepranshu Srivastava"); 
			$mail->AddCC("prashant.dongrikar@arkinfo.in");
			$mail->AddCC("kailash.bhurke@arkinfo.in");  
	     	
		   // $mail->Send();
			$mail->ClearAllRecipients();
			//usleep(10);
$qualified_data=0;
$dvr_data =0;
$tt_dvr =0;
$log_call =0;
$quote=0;
$verification=0;
$commit=0;
$booking=0;
$oem=0;
$total_acc=0;
$quote_quantity=0;
$verification_quantity=0;
$commit_quantity=0;
$booking_quantity=0;
$oem_quantity=0;
$total_quantity=0;
$total_qualified=0;
$total_dvr=0;
$total_lac=0;
$total_quote=0;
$total_verification=0;
$total_commit=0;
$total_booking=0;
$total_oem=0;
$grand_total_acc=0;
$total_quote_quantity=0;
$total_verification_quantity=0;
$total_commit_quantity=0;
$total_booking_quantity=0;
$total_oem_quantity=0;
$grand_total_quantity=0;
	
}

}
?>
 