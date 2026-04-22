<?php include('includes/include.php');
/* Database connection end */

$stagesA = [
    [
        "name" => "Demo",
        "display" => "KMS Demo",
        "value" => "20"
    ],
    [
        "name" => "Proposal Submission",
        "display" => "Proposal Submission",
        "value" => "25"
    ],
    [
        "name" => "Commit",
        "display" => "Commit by decision maker",
        "value" => "50"
    ],
    [
        "name" => "PO/CIF Issued",
        "display" => "End User PO",
        "value" => "90"
    ],
    [
        "name" => "Advance Payment",
        "display" => "Payment Collection",
        "value" => "95"
    ],
    [
        "name" => "Billing",
        "display" => "Billing",
        "value" => "100"
    ]
];
// print_r($stagesA);die;

$requestData= $_REQUEST;
if($requestData['d_from'] && $requestData['d_to'])
{
	if($requestData['d_type']== 'close'){
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(expected_close_date)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(expected_close_date)>='".$requestData['d_from']."' and DATE(expected_close_date)<='".$requestData['d_to']."'";	
		}
	}elseif($requestData['d_type']== 'actioned_date'){
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(approval_time)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(approval_time)>='".$requestData['d_from']."' and DATE(approval_time)<='".$requestData['d_to']."'";	
		}
	}else{
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(created_date)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(created_date)>='".$requestData['d_from']."' and DATE(created_date)<='".$requestData['d_to']."'";	
		}
	}
}
 
$tagFtr = json_decode($_REQUEST['tag']);
if($tagFtr)
{
	$dat.=" and tag in ('".implode("','",$tagFtr)."')";
}

$totalData = 6;
$totalFiltered = $totalData;


$results = array();
$i=1;

foreach ($stagesA as $stage) {  // preparing an array
    $grandT = getSingleResult("SELECT SUM(grand_total_price) AS total_grand_total FROM orders WHERE stage = '".$stage['name']."' and agreement_type='Renewal' and is_opportunity = 1 ".$dat);
    $value = ($grandT*$stage['value'])/100; 
	$nestedData=array(); 
	$nestedData['id']=$i;
	
	$nestedData['total_amount'] = $grandT ? round($grandT) : 0;
	$nestedData['stage'] = $stage['display'];
	$nestedData['percentage'] = $stage['value'].'%';
	
    $nestedData['value'] = round($value);
    $nestedData['valuewithtax'] = '';			 
    $results[] = $nestedData;

		$i++;
}
//print_r($results); die;
 

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $results   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>

