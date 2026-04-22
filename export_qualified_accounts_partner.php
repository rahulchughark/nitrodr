<?php include('includes/include.php');

$teamId = $_SESSION['team_id'];
$requestData = $_REQUEST;
$con = '';
$con2 = '';

$stCon1 = " AND ml.type='Status' AND ml.modify_name='Approved'";
$stCon2 = " AND ml.type='Lead Type' AND ml.modify_name='LC'";

if($requestData['d_from'] && $requestData['d_to']){
if ($requestData['d_from'] == $requestData['d_to']) {
    $con = " and (date(ml.created_date)='" . $requestData['d_to'] . "')";
    $con2 = " and (date(ml.created_date)='" . $requestData['d_to'] . "')";
} else {
    $con = " and (date(ml.created_date) between '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "')";
    $con2 = " and (date(ml.created_date) between '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "')";
}

}

if ($requestData['partner']) {
    $con .= ' and o.team_id in (' . stripslashes($requestData["partner"]) . ')';
    $con2 .= ' and ol.team_id in (' . stripslashes($requestData["partner"]) . ')';
}

// if ($requestData['industry']) {
//  $con .= ' and o.industry in (' . stripslashes($requestData["industry"]) . ')';
//  $con2 .= ' and ol.industry in (' . stripslashes($requestData["industry"]) . ')';
// }

if ($requestData['segment'] == 'DTP') {
    $con .= " and i.log_status=1";
}
if ($requestData['segment'] == 'Other') {
    $con .= " and i.log_status=0";
}

if ($requestData['users']) {
    $con .= ' and o.created_by in (' . stripslashes($requestData["users"]) . ')';
    $con2 .= ' and ol.created_by in (' . stripslashes($requestData["users"]) . ')';
}

if($requestData['quantity']){
    $quant_arr = explode(',', $requestData['quantity']);
    

    if (in_array('9', $quant_arr)) {
        $con .= ' and (o.quantity in (' . stripslashes($requestData["quantity"]) . ') or o.quantity >=9)';
        $con2 .= ' and (ol.quantity in (' . stripslashes($requestData["quantity"]) . ') or ol.quantity >=9)';
    } else if (!in_array('9', $quant_arr) && $requestData["quantity"] != '') {
        $con .= ' and o.quantity in (' . stripslashes($requestData["quantity"]) . ') ';
        $con2 .= ' and ol.quantity in (' . stripslashes($requestData["quantity"]) . ') ';
    }

}


if ($requestData['lead_type']) {
    
    $con .= " and o.lead_type in ('" . stripslashes($requestData["lead_type"]) . "')";
    $con2 .= " and ol.lead_type in ('" . stripslashes($requestData["lead_type"]) . "')";
}

if ($requestData['submission_type']) { 

     if($requestData['submission_type'] == '1,2')
     {
        $stCon1 = " AND ml.type='Status' AND ml.modify_name='Approved'";
        $stCon2 = " AND ml.type='Lead Type' AND ml.modify_name='LC'";
     }
     else if($requestData['submission_type'] == '1')
     {
        $stCon1 = " AND ml.type='Status' AND ml.modify_name='Approved'";
        $stCon2 = " AND ml.type='Status' AND ml.modify_name='Approved'";
     }
     else if($requestData['submission_type'] == '2')
     {
        $stCon1 = " AND ml.type='Lead Type' AND ml.modify_name='LC'";
        $stCon2 = " AND ml.type='Lead Type' AND ml.modify_name='LC'";
     }
    
}

// $partner_arr = $_GET['partner'] ? explode("','",$_GET['partner']):'';
// $partner_arr1 = implode('","',$partner_arr);
// //print_r($partner_arr);
// if($_GET['partner'])
// {
//     $dat.=' and l.team_id in (' . $partner_arr1 . ')';
// }

$query = db_query("SELECT * FROM (SELECT o.id,o.r_name,o.r_user,o.lead_type,o.company_name,o.quantity,o.created_date,o.status,o.close_time,i.name as industry,ml.created_date as actioned_date,ml.type  FROM orders as o left join industry as i on o.industry=i.id join tbl_lead_product as tp on o.id=tp.lead_id join lead_modify_log as ml on o.id=ml.lead_id where o.status='Approved' AND tp.product_id=1 AND tp.product_type_id IN(1,2) AND o.team_id=$teamId $stCon1 $con GROUP by o.id
UNION SELECT o.id,o.r_name,o.r_user,o.lead_type,o.company_name,o.quantity,o.created_date,o.status,o.close_time,i.name as industry,ml.created_date as actioned_date,ml.type FROM orders as o left join industry as i on o.industry=i.id join tbl_lead_product as tp on o.id=tp.lead_id join lead_modify_log as ml on o.id=ml.lead_id where o.status='Approved' AND tp.product_id=1 AND tp.product_type_id IN(1,2) AND o.team_id=$teamId $stCon2 $con GROUP by o.id
UNION SELECT ol.id,ol.r_name,ol.r_user,ol.lead_type,ol.company_name,ol.quantity,ol.created_date,ol.status,ol.close_time,i.name as industry,ml.created_date as actioned_date,ml.type  FROM lapsed_orders as ol left join industry as i on ol.industry=i.id join tbl_lead_product as tp on ol.id=tp.lead_id join lead_modify_log as ml on ol.id=ml.lead_id where ol.status='Approved' AND tp.product_id=1 AND tp.product_type_id IN(1,2) AND ol.team_id=$teamId $stCon1 $con2 GROUP by ol.id 
UNION SELECT ol.id,ol.r_name,ol.r_user,ol.lead_type,ol.company_name,ol.quantity,ol.created_date,ol.status,ol.close_time,i.name as industry,ml.created_date as actioned_date,ml.type FROM lapsed_orders as ol left join industry as i on ol.industry=i.id join tbl_lead_product as tp on ol.id=tp.lead_id join lead_modify_log as ml on ol.id=ml.lead_id where ol.status='Approved' AND tp.product_id=1 AND tp.product_type_id IN(1,2) AND ol.team_id=$teamId $stCon2 $con2 GROUP by ol.id) as i order by i.id DESC");
       
    // print_r($query);die;



$rowCount = mysqli_num_rows($query);
if($rowCount > 0){
    $delimiter = ",";
    $filename = "Qualified_Acounts" . date('Y-m-d') . ".csv";
     ob_start();
    //create a file pointer
    $f = fopen('php://output', 'w');
    
    //set column headers



    $fields = array('Reseller Name','Lead Type','Account Name','Quantity','Industry', 'Submission Date', 'Action Date', 'Submission Type','Status');


    fputcsv($f, $fields, $delimiter);
    
    //output each row of the data, format line as csv and write to file pointer
    while($row = db_fetch_array($query)){
		
		@extract($row);

        $type = ($type == 'Status')?'Fresh':'Converted';

        $ncdate=strtotime(date('Y-m-d')); 
        $closeDate=strtotime($close_time);

        if ($ncdate > $closeDate) {
            $dayspassedafterExpired = ceil(abs($ncdate - $closeDate) / 86400);
            $daysLeft = '(' . $dayspassedafterExpired . ' Days Passed)';
            $status = 'Expired' . $daysLeft;
        } else {

            $remaining_days = ceil(($closeDate - $ncdate) / 84600);
            $daysLeft = 'Days Left- ' . $remaining_days;
            $status = 'Qualified ' . $daysLeft;
        }

       $created_date = date('d-m-Y',strtotime($created_date)); 
       $actioned_date = date('d-m-Y',strtotime($actioned_date)); 
       $r_name = $r_name.'('.$r_user.')';

		$lineData = array($r_name,$lead_type ,htmlspecialchars_decode($company_name, ENT_NOQUOTES) ,$quantity,$industry,$created_date,$actioned_date,$type,$status);

 
			fputcsv($f, $lineData, $delimiter);
    }
	
	header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    fpassthru($f);
	ob_end_flush();
}
else {
header("Location: qualified_accounts_report_partner.php?m=nodata");    
}
exit();
