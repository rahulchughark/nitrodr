<?php include('includes/include.php');
/* Database connection end */
$yesterdayDate = (new DateTime('yesterday'))->format('Y-m-d');
// $yesterdayDate = '2023-07-20';
// die;
$partners = db_query("SELECT id FROM partners where status='Active'");

while($ptr=db_fetch_array($partners))
{
    $sql = db_query("SELECT o.* FROM orders AS o WHERE o.license_type = 'Commercial' AND o.status = 'Approved' AND o.team_id=".$ptr['id']." AND o.dvr_flag != 1 AND close_time = '".$yesterdayDate."'");
    if($sql->num_rows > 0)
    {
        $ptrEmail = getSingleresult("SELECT email FROM users where status='Active' AND user_type='MNGR' AND team_id=".$ptr['id']);

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
    $body.='Hi,<br><br>
    Stages and Log Calls for below DRs are not updated. : <br>
    Kindly obliged to update the Stages and Log A Calls.<br><br>';
    $body.='<div style="margin:0px;padding:5px;background-color:#fff;">';
     
    $body.='<table class="TFtable" style="text-align:center;font-size:11px;" border="1">
    <thead class="first_table">
    <tr>    <td  class="c1">
    <p align="center">
    <strong>Sr. Number</strong>
    </p>
    </td>
    <td   class="c1">
    <p align="center">
    <strong>Reseller Name</strong>
    </p>
    </td>
    <td   class="c1">
    <p align="center">
    <strong>Lead Type</strong>
    </p>
    </td>
    <td   class="c1">
    <p align="center">
    <strong>Quantity</strong>
    </p>
    </td>
    <td  class="c1">
    <p align="center">
    <strong>Company Name</strong>
    </p>	 
    </td>
    <td   class="c1">
    <p align="center">
    <strong>Date Of Submission</strong>
    </p>
    </td>
    <td   class="c1">
    <p align="center">
    <strong>Stage</strong>
    </p>
    </td>
            </tr>
            </thead><tbody>';
    $i=1;
    $addCc[] = 'gurjeet.jafar@arkinfo.in';
    $addCc[] = 'kailash.bhurke@arkinfo.in';
    $addCc[] = 'prashant.dongrikar@arkinfo.in';

    while($data=db_fetch_array($sql))
    {

        $activityCheck = db_query("SELECT * from activity_log where activity_type = 'Lead' and pid=".$data['id']);
        if($data['stage'] == '' OR $data['stage'] == 'NULL')
        {
            if($activityCheck->num_rows < 1)
            {
                $body .='<tr>
                <td>'.$i.'</td>
                <td>'.$data['r_name'].'</td>		
                <td>'.$data['lead_type'].'</td>		
                <td>'.$data['quantity'].'</td>		
                <td>'.$data['company_name'].'</td>		
                <td>'.$data['created_date'].'</td>		
                <td>'.$data['stage'].'</td>		
                </tr>';
                $i++;
            }
        }
    }
    // $body .="<p>Regards,</p>
    // <p>Sketchup Team.</p><br><br>"; 
    $setSubject = 'Stages and Log Calls for below DRs are not updated.';
	
    // $addTo[] = 'pradeep.chahal@arkinfo.in';
    // $addCc[] = 'virendra.kumar@arkinfo.in';
    $addTo[] = $ptrEmail;
    // print_r($body);
    sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
    unset($addTo);
    unset($addCc);
    $body = ''; 
    $i = 1; 
    }
}

