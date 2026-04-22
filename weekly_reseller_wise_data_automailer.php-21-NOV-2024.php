<?php
include('includes/include.php');

$today = date('Y-m-d');
$dTime = strtotime("$today 09:59:59");
$endTime = date('Y-m-d H:i:s', $dTime);

$lastMonday = strtotime("last monday");
$lastMondayMorning = strtotime("10:00:00", $lastMonday);
$startTime = date('Y-m-d H:i:s', $lastMondayMorning);


$setSubject = "ICT360 - Reseller Wise Weekly DR Submission";

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
$body.='Hi,<br><br>Please find the below summary of last week lead statistics @ DR Portal :<br>';
$body.='<div style="margin:0px;padding:5px;background-color:#fff;">';

$body .='<table class="TFtable" style="text-align:center;font-size:11px;" border="1">
<thead class="first_table">
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
<td colspan="2" class="c1">
<p align="center">
<strong># Data Submitted</strong>
</p>
</td>
<td colspan="2"  class="c1">
<p align="center">
<strong># Qualified</strong>
</p>
</td>
<td colspan="2"  class="c1">
<p align="center">
<strong># Unqualified</strong>
</p>
</td>
<td colspan="2" class="c1">
<p align="center">
<strong># Pending</strong>
</p>	 
</td>
<td  colspan="2" class="c1">
<p align="center">
<strong># Resubmission Required</strong>
</p>
</td>
</tr>


<tr>

<td  class="c1"></td>
<td  class="c1"></td>

<td  class="c1">
<p align="center">
<strong># Total</strong>
</p>
</td>
<td   class="c1">
<p align="center">
<strong># Weekly</strong>
</p>
</td>

<td   class="c1">
<p align="center">
<strong># Total</strong>
</p>
</td>
<td  class="c1">
<p align="center">
<strong># Weekly</strong>
</p>	 
</td>


<td   class="c1">
<p align="center">
<strong># Total</strong>
</p>
</td>
<td   class="c1">
<p align="center">
<strong># Weekly</strong>
</p>
</td>


<td   class="c1">
<p align="center">
<strong># Total</strong>
</p>
</td>
<td   class="c1">
<p align="center">
<strong># Weekly</strong>
</p>
</td>


<td   class="c1">
<p align="center">
<strong># Total</strong>
</p>
</td>
<td   class="c1">
<p align="center">
<strong># Weekly</strong>
</p>
</td>


</tr>

</thead><tbody>';

$partners = db_query("select id,name from partners where status='Active'");
$i=1;
while ($data = db_fetch_array($partners)) {

    

    $total = getSingleresult("select count(DISTINCT(o.id)) from orders as o where o.team_id='" . $data['id'] . "'");
    $weekly = getSingleresult("select count(DISTINCT(o.id)) from orders as o where o.team_id='" . $data['id'] . "' and o.created_date>='" . $startTime . "' and o.created_date<='" . $endTime . "'");

    $total_approved = getSingleresult("select count(DISTINCT(o.id)) from orders as o where o.team_id='" . $data['id'] . "' and o.status='Approved'");
    $weekly_approved = getSingleresult("select count(DISTINCT(o.id)) from orders as o where o.team_id='" . $data['id'] . "' and  o.created_date>='" . $startTime . "' and o.created_date<='" . $endTime . "'  and o.status='Approved'");

    $total_rejected = getSingleresult("select count(DISTINCT(o.id)) from orders as o where o.team_id='" . $data['id'] . "' and o.status='Cancelled'");
    $weekly_rejected = getSingleresult("select count(DISTINCT(o.id)) from orders as o where o.team_id='" . $data['id'] . "' and  o.created_date>='" . $startTime . "' and o.created_date<='" . $endTime . "' and o.status='Cancelled'");

    $total_pending = getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o where o.team_id='" . $data['id'] . "' and o.status='Pending'");
    $weekly_pending = getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o where o.team_id='" . $data['id'] . "' and  o.created_date>='" . $startTime . "' and o.created_date<='" . $endTime . "' and o.status='Pending'");

    $total_resubmission = getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o where o.team_id='" . $data['id'] . "' and o.status='Undervalidation'");
    $weekly_resubmission = getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o where o.team_id='" . $data['id'] . "' and  o.created_date>='" . $startTime . "' and o.created_date<='" . $endTime . "' and o.status='Undervalidation'");

    $body .='<tr>
        <td >'.$i.'</td>
        <td>'.$data['name'].'</td>
        
        <td>'.$total.'</td>
        <td>'.$weekly.'</td>


        <td>'.$total_approved.'</td>
        <td>'.$weekly_approved.'</td>


        <td>'.$total_rejected.'</td>
        <td>'.$weekly_rejected.'</td>


        <td>'.$total_pending.'</td>
        <td>'.$weekly_pending.'</td>


        <td>'.$total_resubmission.'</td>
        <td>'.$weekly_resubmission.'</td>

        </tr>';
        $i++;
}


$addTo[] = 'naveen.kumar@ict360.com';
$addCc[] = 'binish.parikh@ict360.com';
$addCc[] = 'nikhil.panchal@arkinfo.in';
// sendMail($addTo, $addCc, $addBcc, $setSubject, $body ,$attachment);
echo "<pre>";
print_r(); die($body);