<?php
include('includes/include.php');

$today = date('Y-m-d');
$dTime = strtotime("$today 09:29:59");
$endTime = date('Y-m-d H:i:s', $dTime);

$lastMonday = strtotime("last monday");
$lastMondayMorning = strtotime("09:30:00", $lastMonday);
$startTime = date('Y-m-d H:i:s', $lastMondayMorning);

$setSubject = "ICT360 - Weekly DR Lead status progress";


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
    Please find the below summary of last week lead status progress @ DR Portal :<br>';
    $body.='<div style="margin:0px;padding:5px;background-color:#fff;">';

    $body .='<table class="TFtable" style="text-align:center;font-size:11px;" border="1">
    <thead class="first_table">
    <tr>
    <td colspan="2" class="c1">
    <p align="center">
    <strong>Weekly Summary</strong>
    </p>
    </td>
    </tr>
    </thead><tbody>'; 
    
    $total = getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o left join tbl_lead_product as p on o.id=p.lead_id where o.created_date >='" . $startTime . "' and o.created_date <='" . $endTime . "'");

    $approved = db_query("select o.r_name,o.school_name,o.status,o.quantity,o.stage,p.product_type_id from orders as o left join tbl_lead_product as p on o.id=p.lead_id where o.created_date >='" . $startTime . "' and o.created_date <='" . $endTime . "'  and o.status='Approved'");

    $rejected = getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o left join tbl_lead_product as p on o.id=p.lead_id where o.created_date >='" . $startTime . "' and o.created_date <='" . $endTime . "' and o.status='Cancelled'");

    $pending = getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o left join tbl_lead_product as p on o.id=p.lead_id where o.created_date >='" . $startTime . "' and o.created_date <='" . $endTime . "' and o.status='Pending'");

    $body .='<tr>
                <td># Approved</td>
                <td>'.$approved->num_rows.'</td>
            </tr><tr>
                <td># Rejected</td>
                <td>'.$rejected.'</td>
            </tr><tr>
                <td># Pending</td>
                <td>'.$pending.'</td>
            </tr><tr>
                <td># Leads Submitted by resellers</td>
                <td>'.$total.'</td>
            </tr></table>';

    $body .='<table class="TFtable" style="text-align:center;font-size:11px;" border="1">
    <thead class="first_table">
    <tr>
	<td colspan="8">
    <p align="center">
    <strong>Qualified Lead`s Summary</strong>
    </p>
	</td>
    </tr>
    <tr>
    <td  class="c1">
    <p align="center">
    <strong>Sr. Number</strong>
    </p>
    </td>
    <td  class="c1">
    <p align="center">
    <strong>Reseller Name</strong>
    </p>
    </td>
    <td  class="c1">
    <p align="center">
    <strong>Organization Name</strong>
    </p>
    </td>
    <td   class="c1">
    <p align="center">
    <strong>Status</strong>
    </p>
    </td>
    <td   class="c1">
    <p align="center">
    <strong>Sub-Product Name</strong>
    </p>
    </td>
    <td  class="c1">
    <p align="center">
    <strong>Student Count</strong>
    </p>	 
    </td>
    <td   class="c1">
    <p align="center">
    <strong>Stage</strong>
    </p>
    </td>
    </tr>
    </thead><tbody>';

    $i = 1;
    while ($data = db_fetch_array($approved)) {
        $body .= "<tr>
            <td>".$i."</td>
            <td>".$data['r_name']."</td>
            <td>".$data['school_name']."</td>
            <td>".$data['status']."</td>
            <td>".getSingleresult('SELECT product_type from tbl_product_pivot where id='.$data['product_type_id'])."</td>
            <td>".$data['quantity']."</td>
            <td>".$data['stage']."</td>
            </tr>";
            $i++;
    }

$addTo[] = 'naveen.kumar@ict360.com';
$addCc[] = 'binish.parikh@ict360.com';
$addCc[] = 'nikhil.panchal@arkinfo.in';
     sendMail($addTo, $addCc, $addBcc, $setSubject, $body ,$attachment);
   // echo "<pre>";
//print_r(); die($body);
