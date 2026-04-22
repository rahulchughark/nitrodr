<?php include('includes/include.php');

if(date('l')!='Sunday')
{

if($_GET['date'])
{
	ini_set('max_execution_time', 0);
	$setSubject = "ISS Activity Report - ".date('jS F Y',strtotime($_GET['date'])).".";
	$dat=$_GET['date'];  
	$dat1=date('Y-m-01');	
	$dat2=$_GET['date'];
}
else
{
		ini_set('max_execution_time', 0);
 	
			$dat=date('Y-m-d');
			$setSubject = "ISS Activity Report - ".date('jS F Y').".";

	 
		$dat1=date('Y-m-01');
		$dat2=date('Y-m-d');
	 
}       
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
		$body.='Dear All,<br><br>
					 Please find the below DR Portal activity report for ISS team:<br><br>';
		$body.='<div style="margin:0px;padding:5px;background-color:#fff;">';
		
		$body .='<table class="TFtable" style="text-align:center;font-size:11px;" border="1">
		<thead class="first_table">
		<tr>
			<td colspan="5"><strong>Daily Report</strong></td>
		</tr>

		<tr>
		<td rowspan="2" width="20">S.No.</td>
		<td rowspan="2">ISS Name</td>
		<td rowspan="2">Log A Calls</td>
		<td colspan="2">Lead Submission</td>
		</tr>
		<tr>
		<td>No. Of Leads</td>
		<td>No. of License</td>
		</tr></thead><tbody>';

	$user =db_query("select callers.*,users.email from callers join users on callers.user_id=users.id where users.id not in (139,138,124,119,135,117,222,208,209,160) order by callers.name ASC");


	$total_lead = 0;
		$total_license = 0;
		$i=1;	
	while($d=db_fetch_array($user))
	{

		$data=db_query("select count(orders.id) as lead,sum(orders.quantity) as license from orders where orders.created_by = '".$d['user_id']."' and date(orders.created_date)='".$dat."'");

		$lead = 0;
		$license=0;
		
		if($i%2)
			{
				$class_new='class="even"';
			}
			else
			{
				$class_new='class="odd"';
			}	
		while($users=db_fetch_array($data))
		{
			$total_lead = $total_lead + $users['lead'];
			$total_license = $total_license + $users['license'];
			

			$lead = 	$users['lead'];
			$license = $users['license'];
			
			
		}


		$caller_log = getSingleresult("select count(cm.id) as caller_log from activity_log as cm where cm.added_by = '".$d['user_id']."' and date(cm.created_date)='".$dat."' group by cm.added_by");

		 $body.='<tr>
			<td '.$class_new.'>'.$i.'</td>
			<td '.$class_new.'>'.$d['name'].'</td>
			<td '.$class_new.' >'.(($caller_log>0)?$caller_log:'0').'</td>
			<td '.$class_new.'>'.(($lead>0)?$lead:'0').'</td>
					<td '.$class_new.'>'.(($license>0)?$license:'0').'</td>			
			</tr>';

		 
		$total_log = $total_log + $caller_log;
		$i++;
	}

	$body.='<tr>
	<th colspan="2">Total</th> 
	<th >'.$total_log.'</th>
	<th >'.$total_lead.'</th>
	<th>'.$total_license.'</th>
	</tr>';

	$body.='</tbody>
</table><br/><br/>';
$dat1=date('Y-m-01');
$dat2=date('Y-m-t');

 
	 
		
		$body .='<table class="TFtable" style="text-align:center;font-size:11px;" border="1">
		<thead class="second_table">
		<tr>
			<td colspan="5"><strong>Monthly Report</strong></td>
		</tr>

		<tr>
		<td rowspan="2" width="20">S.No.</td>
		<td rowspan="2">ISS Name</td>
		<td rowspan="2">Log A Calls</td>
		<td colspan="2">Lead Submission</td>
		</tr>
		<tr>
		<td>No. Of Leads</td>
		<td>No. of License</td>
		</tr></thead><tbody>';

	$user =db_query("select callers.*,users.email from callers join users on callers.user_id=users.id where users.id not in (139,138,124,119,135,117,222,208,209,160) order by callers.name ASC");

	



	$total_lead = 0;
		$total_license = 0;
		$i=1;
	while($d=db_fetch_array($user))
	{

		$data=db_query("select count(orders.id) as lead,sum(orders.quantity) as license from orders where created_by = '".$d['user_id']."' and (date(orders.created_date)  BETWEEN  '".$dat1."' and '".$dat2."')");

		$lead = 0;
		$license=0;
		if($i%2)
			{
				$class_new='class="even"';
			}
			else
			{
				$class_new='class="odd"';
			}	
		while($users=db_fetch_array($data))
		{
			$total_lead = $total_lead + $users['lead'];
			$total_license = $total_license + $users['license'];
			

			$lead = 	$users['lead'];
			$license = $users['license'];
			
		}


		$caller_log = getSingleresult("select count(cm.id) as caller_log from activity_log as cm where cm.added_by = '".$d['user_id']."' and  (date(cm.created_date)  BETWEEN  '".$dat1."' and '".$dat2."') group by cm.added_by");

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
			$addTo[] = ("virendra@corelindia.co.in");
		 	$addTo[] = ("nithesh@corelindia.co.in");
			
			 $addCc[] = ("prashant.dongrikar@arkinfo.in");
			 $addCc[] = ("fayyaz@corelindia.co.in");
			 $addCc[] = ("vijay@corelindia.co.in");
			 $addCc[] = ("bhagyashree@corelindia.co.in");
			$addBcc[] = ("deepranshu.srivastava@arkinfo.in");
			 
	     
			sendMail($addTo, $addCc, $addBcc, $setSubject, $body);




}


?>
 