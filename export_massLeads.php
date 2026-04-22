<?php

include('includes/include.php');

$requestData = $_GET;

if ($requestData['d_from'] && $requestData['d_to']) {
	if ($requestData['d_from'] == $requestData['d_to']) {
		$dat = " and 1";
	} else {
		$dat = " and  ((DATE(o.created_date) BETWEEN  '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "'))";
	}
}

if ($requestData['lead_type']) {
	$dat .= " and lead_type='" . $requestData['lead_type'] . "'";
}

if ($requestData['partner']) {
	$dat .= " and r_name='" . $requestData['partner'] . "'";
}
if ($requestData['license_type']) {
	$dat .= " and license_type='" . $requestData['license_type'] . "'";
}

if ($requestData['industry']) {
	$dat .= " and o.industry='" . $requestData['industry'] . "'";
}


if (empty($requestData['industry']) &&empty($requestData['lead_type']) && empty($requestData['partner']) && empty($requestData['license_type'])) {

    $select_query = db_query("SELECT o.id,o.r_name,o.quantity,o.eu_mobile,o.lead_type,o.license_type,o.created_date,cl.name as caller,i.name as industry 
    FROM orders as o
    LEFT JOIN callers as cl ON o.caller = cl.id
    LEFT JOIN industry as i ON o.industry = i.id
    WHERE 1=1 and o.status!='Cancelled' and o.status!='Undervalidation' ORDER By o.id Desc");
} else {

    $select_query = db_query("SELECT o.id,o.r_name,o.quantity,o.eu_mobile,o.lead_type,o.license_type,o.created_date,cl.name as caller,i.name as industry 
    FROM orders as o
    LEFT JOIN callers as cl ON o.caller = cl.id
    LEFT JOIN industry as i ON o.industry = i.id
    WHERE 1 $dat and o.status!='Cancelled' and o.status!='Undervalidation'
    ORDER By o.id Desc");

}
//print_r($select_query);die;

$lineData = [];
$rowCount = mysqli_num_rows($select_query);

if ($rowCount > 0) {
    $delimiter = ",";
    $filename = "MassLeadAlignment" . date('Y-m-d') . ".csv";
    ob_start();
    //create a file pointer
    $f = fopen('php://output', 'w');

    //set column headers
    $fields = array('Caller Name','Industry','Partner Name' ,'End User Contact','Lead Type', 'License Type', 'Quantity');
    
    fputcsv($f, $fields, $delimiter);
    //output each row of the data, format line as csv and write to file pointer
    while ($row = db_fetch_array($select_query)) {

        @extract($row);
        //print_r($row);die;
        foreach($row as $value){
            $lineData = array($caller,$industry,$r_name,$eu_mobile ,$lead_type ,$license_type ,$quantity);      
        }

        
        
        fputcsv($f, $lineData, $delimiter); 
        //unset($query_data);
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    fpassthru($f);
    ob_end_flush();
}
// else {
// header("Location: orders.php?m=nodata");    
// }
// exit();
