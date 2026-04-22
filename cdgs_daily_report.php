<?php include('includes/include.php');
if(date('l')!='Sunday')
{
    
ini_set('max_execution_time', 0);
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
$mail->Subject = "CDGS Monthly Visibility for ".date('F');  			
$resellers=db_query("select id,name from partners where id not in (45,25,37,53) and product_id!=4 and status='Active'");
while($data=db_fetch_array($resellers))	
{
   
 $quote=getSingleresult("select count(orders.id) as leads from orders left join tbl_lead_product on orders.id =tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.stage='Quote' and license_type='Commercial' and orders.status='Approved' and (tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL) and date(orders.partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' ");
//
$verification=getSingleresult("select count(orders.id) as leads from orders left join tbl_lead_product on orders.id =tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and license_type='Commercial' and orders.status='Approved' and (tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL) and (orders.stage='Verification' or orders.stage='Follow-Up') and date(orders.partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' ");
//
$commit=getSingleresult("select count(orders.id) as leads from orders left join tbl_lead_product on orders.id =tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and license_type='Commercial' and orders.status='Approved' and (tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL) and orders.stage='Commit' and date(orders.partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' ");
//
$booking=getSingleresult("select count(orders.id) as leads from orders left join tbl_lead_product on orders.id =tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and license_type='Commercial' and orders.status='Approved' and (tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL) and orders.stage='Booking' and date(orders.partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' ");
//
$eupo=getSingleresult("select count(orders.id) as leads from orders left join tbl_lead_product on orders.id =tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and license_type='Commercial' and orders.status='Approved' and (tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL) and orders.stage='EU PO Issued' and date(orders.partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' ");
//
$oem=getSingleresult("select count(orders.id) as leads from orders left join tbl_lead_product on orders.id =tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.stage='OEM Billing' and license_type='Commercial' and orders.status='Approved' and (tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL) and date(orders.partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' ");
//
$bor=getSingleresult("select count(orders.id) as leads from orders left join tbl_lead_product on orders.id =tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.stage='Billed To Other Re-Seller' and license_type='Commercial' and orders.status='Approved' and (tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL) and date(orders.partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."'");
$total_acc=$commit+$booking+$oem+$eupo+$bor;


$quote_quantity=getSingleresult("select COALESCE(SUM(orders.quantity),0) as leads from orders left join tbl_lead_product on orders.id =tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and license_type='Commercial' and orders.status='Approved' and (tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL) and orders.stage='Quote' and orders.license_type='Commercial' and date(orders.partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' ");
//
$verification_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders left join tbl_lead_product on orders.id =tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and license_type='Commercial' and orders.status='Approved' and (tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL) and (orders.stage='Verification' or orders.stage='Follow-Up') and date(orders.partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' ");
//
$commit_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders left join tbl_lead_product on orders.id =tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and license_type='Commercial' and orders.status='Approved' and (tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL) and orders.stage='Commit' and  date(orders.partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' ");
//
$booking_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders left join tbl_lead_product on orders.id =tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and license_type='Commercial' and orders.status='Approved' and (tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL) and orders.stage='Booking' and date(orders.partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' ");
//
$eupo_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders left join tbl_lead_product on orders.id =tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and license_type='Commercial' and orders.status='Approved' and (tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL) and  orders.stage='EU PO Issued' and date(orders.partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' ");
//
$oem_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders left join tbl_lead_product on orders.id =tbl_lead_product.lead_id where orders.team_id='".$data['id']."'  and license_type='Commercial' and orders.status='Approved' and (tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL) and orders.stage='OEM Billing'  and date(orders.partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."' ");
$bor_quantity=getSingleresult("select COALESCE(SUM(quantity),0) as leads from orders left join tbl_lead_product on orders.id =tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.stage='Billed To Other Re-Seller' and license_type='Commercial' and orders.status='Approved' and (tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL) and date(orders.partner_close_date) BETWEEN  '".$dat1."' and '".$dat2."'");
//
$total_quantity=$commit_quantity+$booking_quantity+$oem_quantity+$eupo_quantity+$bor_quantity;

	$mail->Body='<style type="text/css"> 
.TFtable{ width:100%; border-collapse:collapse; font-family:Arial; } 
.TFtable td{ padding:7px; border:#000 1px solid; } 
.TFtable th{ padding:7px; border:#000 1px solid;background: #fff; } 
.TFtable tr{ background: #fff; } 
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
.c1{ background-color:#370F44;color:#fff}
.c2{ background-color:#370F44;color:#fff}
.c3{ background-color:#051620;color:#fff}
.c_yellow td{ background-color:#FFFF00;color:#000}
  </style>';		
		$mail->Body.='<p>Hello,</p>
		<p>Greetings for the day !!</p>
		<p>'.$data['name'].' your monthly visibility is <strong><u>'.$total_quantity.' Seats Vs. '.getSingleresult("select cdgs_target from partners where id='".$data['id']."'").' Seats </u></strong></p>
	 
		<p>Please find below summary :</p>
		';
		$mail->Body.='<div style="margin:0px;padding:5px;background-color:#fff;">';
		
			$mail->Body .='<table class ="TFtable" style="width:60%;font-size:12px" border="1" cellspacing="0" cellpadding="0">
			<tbody>
			<tr>
				<td colspan="7"  class="c1">
					<p align="center">
						<strong>'.$data['name'].'</strong>
					</p>
				</td>
			</tr>
			<tr>
				<td  class="c1">
					<p align="center">
						<strong>Stages</strong>
					</p>
				</td>
				<td  class="c1">
					<p align="center">
						<strong>Commit</strong>
					</p>
				</td>
                <td   class="c1">
                <p align="center">
					<strong>EU PO Issued</strong>
                    </p>
				</td>
				<td   class="c1">
                <p align="center">
						<strong>Booking</strong>
                        </p>
				</td>
				<td   class="c1">
                <p align="center">
						<strong>OEM</strong>
				 
						<strong>Billing</strong>
                        </p>
				</td>
				<td  class="c1">
                <p align="center">
						<strong>Billed to Other Re-seller</strong>
                        </p>	 
				</td>
				<td   class="c1">
                <p align="center">
						<strong>Visibility for Month</strong>
                        </p>
				</td>
			</tr>
			<tr>
				<td  >
                <p align="center">
						<strong>No of Accounts</strong>
                        </p>
				</td>
				<td allign="center">	<p align="center">'.$commit.'</p></td>
				<td  >	<p align="center">'.$eupo.'</p>
				</td>
				<td  >	<p align="center">'.$booking.'</p>
				</td>
				<td  >	<p align="center">'.$oem.'</p>
				</td>
				<td >	<p align="center">'.$bor.'</p>
				</td>
				<td  >	<p align="center">'.$total_acc.'</p>
				</td>
			</tr>
			<tr>
				<td >
                <p align="center"> 
						<strong>No of Licenses</strong>
                        </p>
				</td>
				<td ><p align="center">'.$commit_quantity.'</p>
				</td>
				<td ><p align="center">'.$eupo_quantity.'</p>
				</td>
				<td  ><p align="center">'.$booking_quantity.'</p>
				</td>
				<td ><p align="center">'.$oem_quantity.'</p>
				</td>
				<td  ><p align="center">'.$bor_quantity.'</p>
				</td>
				<td  ><p align="center">'.$total_quantity.'</p>
				</td>
			</tr>
			 
		</tbody>';


$mail->Body.='</table>
<p>Note- <strong>This report is based on the <u>Stages, Quantity & Closed Date</u> updated in DR Portal for <u>'.date('F').' Month</u></strong></p>
<p>&nbsp;</p>
<p>Thanks &amp; Regards,</p>
<p>Core DR Support Team.</p><br><br></div>';
$users1=db_query("select email from users where team_id='".$data['id']."' and user_type!='MNGR' and status='Active'");
    while($user_email=db_fetch_array($users1)){
        $mail->AddAddress($user_email['email']);
    }
    $sm_email=getSingleresult("select users.email as email from users join partners on partners.sm_user=users.id where partners.id='".$data['id']."'");
    if($sm_email)
    $mail->AddCC($sm_email);
    $manager_email=getSingleresult("select email from users where user_type='MNGR' and team_id='".$data['id']."' and status='Active'");
    $mail->AddCC($manager_email);
    $mail->AddCC("jayesh.patel@arkinfo.in");
    $mail->AddCC("pradnya.chaukekar@arkinfo.in");
    $mail->AddCC("kailash.bhurke@arkinfo.in");    	
    $mail->AddCC("maneesh.kumar@arkinfo.in"); 
    $mail->AddBCC("deepranshu.srivastava@arkinfo.in"); 
    $mail->AddBCC("ankit.aggarwal@arkinfo.in");
  //  echo "<pre>";
    echo  $mail->Body;
    //$mail->Send();
    $mail->ClearAllRecipients();
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
 