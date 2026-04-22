<?php include('includes/include.php');
// if(date('l')!='Sunday')
// {

ini_set('max_execution_time', 0);
    
  			
// $resellers=db_query("select id,name from partners where id=45 and status='Active'");
// $data=db_fetch_array($resellers);	

$setSubject = "Renewal Weekly Stage update report -".date('F');	


// $dat1=date('Y-m-01');
// $dat2=date('Y-m-t');
   
 
	$body='<style type="text/css"> 
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
		$body.='<p>Hello,</p>
		<p>Greetings for the day !!</p>

		<p>Please find below summary for Renewal Weekly Stage update report:</p>
		';
		$body.='<div style="margin:0px;padding:5px;background-color:#fff;">';
		
			$body .='<table class ="TFtable" style="width:60%;font-size:12px" border="1" cellspacing="0" cellpadding="0">
			<tbody>
			
			<tr>
				<td  class="c1">
					<p align="center">
						<strong>Stages</strong>
					</p>
				</td>

				<td  class="c1">
					<p align="center">
						<strong>Total</strong>
					</p>
				</td>

				<td  class="c1">
					<p align="center">
						<strong>Quote</strong>
					</p>
				</td>
                <td   class="c1">
                <p align="center">
					<strong>Follow-Up</strong>
                    </p>
				</td>
				<td   class="c1">
                <p align="center">
						<strong>Commit</strong>
                        </p>
				</td>
				<td   class="c1">
                <p align="center">
						<strong>EUPO</strong>
                        </p>
				</td>
				<td  class="c1">
                <p align="center">
						<strong>Booking</strong>
                        </p>	 
				</td>
				<td   class="c1">
                <p align="center">
						<strong>OEM Billing</strong>
                        </p>
				</td>
			</tr>';

function monthToWeeks($y, $m)
{
    $weeks = [];
    $month = $m;
    $current_date = date('Y-m-d');
    $first_date = date("{$y}-{$m}-01");

    do {
        $last_date = date("Y-m-d", strtotime($first_date. " +6 days"));
        // echo $last_date; die();
        $month = date("m", strtotime($last_date));

        if ($month != $m) {
            $last_date = date("Y-m-t", mktime(0, 0, 0, $m, 1, $y)); 
            // $last_date = date("Y-m-t")

            if ($first_date > $last_date) {
                break;
            }
         }  

         $weeks[] = [$first_date, $last_date];

         $first_date = date("Y-m-d", strtotime($last_date. " +1 days"));

    } while($last_date < $current_date);

    return $weeks;    
}

$curret_year = date('Y');
$curret_month = date('m');

$weeks = monthToWeeks($curret_year, $curret_month);
	// print_r($weeks);

		foreach ($weeks as $key => $week) {
			$dat1 =  $week[0];
			$dat2 =  $week[1];
			$week_no = $key+1;

$query = db_query("select lead_modify_log.* from lead_modify_log left join tbl_lead_product on lead_modify_log.lead_id =tbl_lead_product.lead_id left join orders on lead_modify_log.lead_id =orders.id where orders.license_type='Renewal' and orders.status='Approved' and lead_modify_log.type='Stage' and lead_modify_log.modify_name in('Quote','Follow-Up','Commit','EU PO Issued','Booking','OEM Billing') and (tbl_lead_product.product_type_id in (6,7) or tbl_lead_product.product_id is NULL) and date(lead_modify_log.created_date) BETWEEN '".$dat1."' and '".$dat2."' and lead_modify_log.id IN(SELECT MAX(lead_modify_log.id) FROM lead_modify_log GROUP BY lead_modify_log.lead_id) order by lead_modify_log.id DESC");

//  leads quantity
$quote = 0;
$follow_up = 0;
$commit = 0;
$eupo = 0;
$booking = 0;
$oem = 0;

// license quantity
$quote_quantity = 0;
$follow_up_quantity = 0;
$commit_quantity = 0;
$eupo_quantity = 0;
$booking_quantity = 0;
$oem_quantity = 0;

foreach ($query as $value) {
 	if($value['modify_name'] == 'Quote')
 	{
 	  $quote++;
 	  $quote_quantity += getSingleresult("select quantity from orders where license_type='Renewal' and status='Approved' and id='".$value['lead_id']."'");
 	}
 	else if($value['modify_name'] == 'Follow-Up')
 	{
 	  $follow_up++;
 	  $follow_up_quantity += getSingleresult("select quantity from orders where license_type='Renewal' and status='Approved' and id='".$value['lead_id']."'");
 	}
 	else if($value['modify_name'] == 'Commit')
 	{
 		$commit++;
 		$commit_quantity += getSingleresult("select quantity from orders where license_type='Renewal' and status='Approved' and id='".$value['lead_id']."'");
 	}
 	else if($value['modify_name'] == 'EU PO Issued')
 	{
 		$eupo++;
 		$eupo_quantity += getSingleresult("select quantity from orders where license_type='Renewal' and status='Approved' and id='".$value['lead_id']."'");
 	}
 	else if($value['modify_name'] == 'Booking')
 	{
 		$booking++;
 		$booking_quantity += getSingleresult("select quantity from orders where license_type='Renewal' and status='Approved' and id='".$value['lead_id']."'");
 	}
 	else if($value['modify_name'] == 'OEM Billing')
 	{
 		$oem++;
 		$oem_quantity += getSingleresult("select quantity from orders where license_type='Renewal' and status='Approved' and id='".$value['lead_id']."'");
 	}

 } 
 					
  $body .='<tr>
			    <td rowspan="2">
                <p align="center">
						<strong>Week '.$week_no.'</strong>
                        </p>
				</td>

				<td>
                <p align="center">
						<strong>No of Accounts</strong>
                        </p>
				</td>
				<td allign="center">	<p align="center">'.$quote.'</p></td>
				<td  >	<p align="center">'.$follow_up.'</p>
				</td>
				<td  >	<p align="center">'.$commit.'</p>
				</td>
				<td  >	<p align="center">'.$eupo.'</p>
				</td>
				<td >	<p align="center">'.$booking.'</p>
				</td>
				<td  >	<p align="center">'.$oem.'</p>
				</td>
			</tr>
			<tr>
				<td >
                <p align="center"> 
						<strong>No of Licenses</strong>
                        </p>
				</td>
				<td ><p align="center">'.$quote_quantity.'</p>
				</td>
				<td ><p align="center">'.$follow_up_quantity.'</p>
				</td>
				<td  ><p align="center">'.$commit_quantity.'</p>
				</td>
				<td ><p align="center">'.$eupo_quantity.'</p>
				</td>
				<td  ><p align="center">'.$booking_quantity.'</p>
				</td>
				<td  ><p align="center">'.$oem_quantity.'</p>
				</td>
			</tr>';
			}
  $body .='</tbody>';


$body.='</table>
<p>&nbsp;</p>
<p>Thanks &amp; Regards,</p>
<p>Core DR Support Team.</p><br><br></div>';
	
	 $addTo[] = ("rajeshri.Shriyan@arkinfo.in");
	 $addTo[] = ("amjad.Pathan@arkinfo.in");
	 $addBcc[] = ("prashant.dongrikar@arkinfo.in");

    $addCc[] = ("niket@corelindia.co.in");
    $addCc[] = ("binish.parikh@arkinfo.in");
    $addCc[] = ("maneesh.kumar@arkinfo.in");
    $addBcc[] = ("virendra.kumar@arkinfo.in"); 

	sendMail($addTo, $addCc, $addBcc, $setSubject, $body);


// }
?>
 