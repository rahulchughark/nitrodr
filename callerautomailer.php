<?php include('includes/include.php');
ini_set('max_execution_time', 0);
$setSubject = "Daily Activity Report - ".date('jS F Y',strtotime("-1 days")).".";
$dat=date('Y-m-d',strtotime("-1 days"));
$dat1=date('Y-m-01');
$dat2=date('Y-m-t');
         
//$dat = '2018-12-31';		  
			

	
	$body ='<style type="text/css"> 
.TFtable{ width:100%; border-collapse:collapse; font-family:verdana; } 
.TFtable td{ padding:7px; border:#4e95f4 1px solid; } 
.TFtable th{ padding:7px; border:#4e95f4 1px solid;background: #acdc9c; } 
.TFtable tr{ background: #b8d1f3; } 
.odd{ background: #b8d1f3; } 
.even{ background: #dae5f4; }
.first_table td{background: #061621;color:#FFFDFC;background: -moz-linear-gradient(top, #445058 0%, #1f2d37 66%, #061621 100%);
  
  border: 2px solid #444444;} 
 .second_table td{ 
   background: #371044;
  background: -moz-linear-gradient(top, #a6554b 0%, #943327 66%, #891D0F 100%);
  background: -webkit-linear-gradient(top, #a6554b 0%, #943327 66%, #891D0F 100%);
  background: linear-gradient(to bottom, #a6554b 0%, #943327 66%, #891D0F 100%);
  border: 2px solid #444444;color:#fff;
}
  </style>';		
		$body.='Dear Sir,<br><br>
					 Please find the below DR Portal activity report for team:<br><br>';
		$body.='<div style="margin:0px;padding:5px;background-color:#fff;">';
		
		$body .='<table class="TFtable" style="text-align:center;font-size:11px;" border="1">
		<thead class="first_table">
		<tr>
			<td colspan="5"><strong>Daily Report</strong></td>
		</tr>

	<tr>
		<td ><strong>Sr. Number</strong></td>
		<td ><strong>Name</strong></td>
		<td><strong>Call</strong></td>
		<td><strong>Lead</strong></td>
		<td><strong>License</strong></td>	
		

	</tr></thead><tbody>';

	$user =db_query("select u.id as user_id,ca.id as caller_id, u.name,u.email from users as u INNER JOIN callers as ca ON u.id = ca.user_id order by u.id DESC");

	
	$addTo[] = ("rakesh.kumar@arkinfo.in");



	$total_lead = 0;
		$total_license = 0;
		$i=1;
	while($d=db_fetch_array($user))
	{

		$data=db_query("select u.id as user_id,ca.id as caller_id, u.name,count(orders.id) as lead,sum(orders.quantity) as license from users as u LEFT JOIN callers as ca ON u.id = ca.user_id INNER JOIN orders ON orders.caller = ca.id where u.id = '".$d['user_id']."' and date(orders.created_date)='".$dat."' group by u.id ");

		$lead = 0;
		$license=0;
		
		while($users=db_fetch_array($data))
		{
			$total_lead = $total_lead + $users['lead'];
			$total_license = $total_license + $users['license'];
			if($i%2)
			{
				$class_new='class="even"';
			}
			else
			{
				$class_new='class="odd"';
			}	

			$lead = 	$users['lead'];
			$license = $users['license'];
			
		}


		$caller_log = getSingleresult("select count(cm.id) as caller_log from caller_comments as cm where cm.added_by = '".$d['user_id']."' and date(cm.created_date)='".$dat."' group by cm.added_by");

		 $body.='<tr>
			<td '.$class_new.'>'.$i.'</td>
			<td '.$class_new.'>'.$d['name'].'</td>
			<td '.$class_new.' >'.(($caller_log>0)?$caller_log:'0').'</td>
			<td '.$class_new.'>'.(($lead>0)?$lead:'0').'</td>
					<td '.$class_new.'>'.(($license>0)?$license:'0').'</td>			
			</tr>';

		$i++;
		$total_log = $total_log + $caller_log;

	}

	$body.='<tr>
	<th colspan="2">Total</th> 
	<th >'.$total_log.'</th>
	<th >'.$total_lead.'</th>
	<th>'.$total_license.'</th>
	</tr>';

	$body.='</tbody>
</table><br/><br/>';

	$body .="<div style='width:100%'>Regards,<br>
			DR Portal Support Team.</div><br><br>";
			
			$body .="<hr><i><b>Please note:-</b> This is an auto-generated email.</i><hr>";
			$body .='</div>';
			echo $body;
			$addBcc[] = ("rakesh.kumar1@arkinfo.in"); 	  
		    sendMail($addTo, $addCc, $addBcc, $setSubject, $body);


$dat1=date('Y-m-01');
$dat2=date('Y-m-t');

$body ='<style type="text/css"> 
.TFtable{ width:100%; border-collapse:collapse; font-family:verdana; } 
.TFtable td{ padding:7px; border:#4e95f4 1px solid; } 
.TFtable th{ padding:7px; border:#4e95f4 1px solid;background: #acdc9c; } 
.TFtable tr{ background: #b8d1f3; } 
.odd{ background: #b8d1f3; } 
.even{ background: #dae5f4; }
.first_table td{background: #061621;color:#FFFDFC;background: -moz-linear-gradient(top, #445058 0%, #1f2d37 66%, #061621 100%);
  
  border: 2px solid #444444;} 
 .second_table td{ 
   background: #371044;
  background: -moz-linear-gradient(top, #a6554b 0%, #943327 66%, #891D0F 100%);
  background: -webkit-linear-gradient(top, #a6554b 0%, #943327 66%, #891D0F 100%);
  background: linear-gradient(to bottom, #a6554b 0%, #943327 66%, #891D0F 100%);
  border: 2px solid #444444;color:#fff;
}
  </style>';		
		$body.='Dear Sir,<br><br>
					 Please find the below DR Portal activity report for team:<br><br>';
		$body.='<div style="margin:0px;padding:5px;background-color:#fff;">';
		
		$body .='<table class="TFtable" style="text-align:center;font-size:11px;" border="1">
		<thead class="first_table">
		<tr>
			<td colspan="5"><strong>Daily Report</strong></td>
		</tr>

	<tr>
		<td ><strong>Sr. Number</strong></td>
		<td ><strong>Name</strong></td>
		<td><strong>Call</strong></td>
		<td><strong>Lead</strong></td>
		<td><strong>License</strong></td>	
		

	</tr></thead><tbody>';

	$user =db_query("select u.id as user_id,ca.id as caller_id, u.name,u.email from users as u INNER JOIN callers as ca ON u.id = ca.user_id order by u.id DESC");

	
	$addTo[] = ("rakesh.kumar@arkinfo.in");



	$total_lead = 0;
		$total_license = 0;
		$i=1;
	while($d=db_fetch_array($user))
	{
		//$addTo[] = ($d['email']);	

		$data=db_query("select u.id as user_id,ca.id as caller_id, u.name,count(orders.id) as lead,sum(orders.quantity) as license from users as u LEFT JOIN callers as ca ON u.id = ca.user_id INNER JOIN orders ON orders.caller = ca.id where u.id = '".$d['user_id']."' and (date(orders.created_date)  BETWEEN  '".$dat1."' and '".$dat2."') group by u.id ");

		$lead = 0;
		$license=0;
		
		while($users=db_fetch_array($data))
		{
			$total_lead = $total_lead + $users['lead'];
			$total_license = $total_license + $users['license'];
			if($i%2)
			{
				$class_new='class="even"';
			}
			else
			{
				$class_new='class="odd"';
			}	

			$lead = 	$users['lead'];
			$license = $users['license'];
			
		}


		$caller_log = getSingleresult("select count(cm.id) as caller_log from caller_comments as cm where cm.added_by = '".$d['user_id']."' and  (date(cm.created_date)  BETWEEN  '".$dat1."' and '".$dat2."') group by cm.added_by");

		 $body.='<tr>
			<td '.$class_new.'>'.$i.'</td>
			<td '.$class_new.'>'.$d['name'].'</td>
			<td '.$class_new.' >'.(($caller_log>0)?$caller_log:'0').'</td>
			<td '.$class_new.'>'.(($lead>0)?$lead:'0').'</td>
					<td '.$class_new.'>'.(($license>0)?$license:'0').'</td>			
			</tr>';

		$i++;
		$total_log = $total_log + $caller_log;

	}

	$body.='<tr>
	<th colspan="2">Total</th> 
	<th >'.$total_log.'</th>
	<th >'.$total_lead.'</th>
	<th>'.$total_license.'</th>
	</tr>';

	$body.='</tbody>
</table><br/><br/>';

	$body .="<div style='width:100%'>Regards,<br>
			DR Portal Support Team.</div><br><br>";
			
			$body .="<hr><i><b>Please note:-</b> This is an auto-generated email.</i><hr>";
			$body .='</div>';
			echo $body;
			$addBcc[] = ("rakesh.kumar1@arkinfo.in"); 	  
			sendMail($addTo, $addCc, $addBcc, $setSubject, $body);

		die;







?>
 