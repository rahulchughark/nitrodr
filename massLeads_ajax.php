<?php include('includes/include.php');


// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;
// print_r($requestData);die;	
$dat ='';
if($_SESSION['user_type'] == 'TEAM LEADER'){
	$callesIds = getSingleResult("SELECT caller from users where id=".$_SESSION['user_id']);
	$callesIdsA = explode(",", $callesIds);
	foreach ($callesIdsA as $value) {
	
	$calleruidQ = getSingleResult("select user_id from callers where id=".$value);
	$caller_userId[]=$calleruidQ;
	}
	$callesIdsForQ = implode(",", $caller_userId);
	$dat = " and (o.created_by in (".$callesIdsForQ.") OR o.team_id NOT IN(116,127)) ";
}

$results = [];
if ($requestData['d_from'] && $requestData['d_to']) {
	if ($requestData['d_from'] == $requestData['d_to']) {
		$dat .= " and 1";
	} else {
		$dat .= " and  ((DATE(o.created_date) BETWEEN  '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "'))";
	}
}

if ($requestData['partner']) {
	$dat .= " and o.team_id in (" . $requestData['partner'] . ")";
}
if ($requestData['school_board']) {
	$dat .= " and o.school_board in ('" . $requestData['school_board'] . "')";
}
if ($requestData['sub_source']) {
	$dat .= " and o.sub_lead_source in ('" . $requestData['sub_source'] . "')";
}
// $school_boardFtr =  json_decode($_REQUEST['school_board']);
// if($school_boardFtr != '')
// {
//     $dat.=" and o.school_board in ('".implode("','",$school_boardFtr)."')";
// }
// $sub_source = json_decode($_REQUEST['sub_source']);
// if ($sub_source != '') {
// 	$dat .= " and o.sub_lead_source in ('" .implode("','",$sub_source) . "')";
// }
if ($requestData['state']) {
	$dat .= " and o.state in (" . $requestData['state'] . ")";
}
if ($requestData['city']) {
	$dat .= " and o.city in (" . $requestData['city'] . ")";
}
if ($requestData['iss']) {
	$dat .= " and o.allign_to in (" . $requestData['iss'] . ")";
}
if ($requestData['tag']) {
	$dat .= " and o.tag=" . $requestData['tag'];
}
if ($requestData['license_type'] == 'Fresh') {
	$dat .= " and o.is_opportunity=0";
}else if ($requestData['license_type'] == 'Renewal') {
	$dat .= " and o.is_opportunity=1 and agreement_type='Renewal'";
}else if ($requestData['license_type'] == 'Opportunity') {
	$dat .= " and o.is_opportunity=1 and agreement_type='Fresh'";
}

if($requestData['userF'])
{
$dat.=" and o.created_by='".$requestData['userF']."'";
}


if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$dat .= " AND ( o.r_name LIKE '%" . $requestData['search']['value'] . "%' ";
	$dat .= " OR o.quantity LIKE '%" . $requestData['search']['value'] . "%' ";
	$dat .= " OR o.school_name LIKE '%" . $requestData['search']['value'] . "%' ";
	$dat .= " OR o.eu_email LIKE '%" . $requestData['search']['value'] . "%' ";
	$dat .= " OR o.status LIKE '%" . $requestData['search']['value'] . "%' ";
	$dat .= " OR o.eu_mobile LIKE '%" . $requestData['search']['value'] . "%' )";
}

// if (empty($requestData['partner']) && empty($requestData['license_type']) &&empty($requestData['product']) &&empty($requestData['product_type'])&&empty($requestData['d_from'])&&empty($requestData['d_to'])&&empty($requestData['userF'])) {

// 	$query = massLead_TableData('orders', $requestData['start'], $requestData['length']);
// } else {
// 	// getting total number records without any search

// 	$query = massLead_DataWithConditions('orders', $dat, $requestData['start'], $requestData['length']);
// }
// //print_r($query);

//for pagination
$sql = ("select o.r_user,o.created_date,o.r_name,o.agreement_type as license_type,o.id,o.lead_status,o.quantity,o.created_by,u.name as user_name,o.school_name,o.eu_email,o.eu_mobile,o.close_time,o.stage,o.tag,o.sub_lead_source,o.is_opportunity from orders as o LEFT JOIN users as u ON o.allign_to = u.id where 1 " . $dat." GROUP BY o.id");
$query = db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

$sql .=" ORDER By o.id Desc LIMIT " . $requestData['start'] . " ," . $requestData['length'];
// print_r($sql);die;
$query = db_query($sql);
$i = $requestData['start'] + 1;
$nestedData = [];

$j = 1;

while ($data = db_fetch_array($query)) {  // preparing an array
	if($data['is_opportunity'] == 1){
		$url = 'view_opportunity.php';
		if($data['license_type'] == 'Renewal'){
			$ltype = 'Renewal Opportunity';
		}else{
			$ltype = 'Fresh Opportunity';
		}
	}else{
		$ltype = 'Fresh Lead';
		$url = 'view_order.php';
	}
	$color='#000';
	$nestedData['serial']= $i;
	$nestedData['id'] = '<div class="custom-checkbox"><input type="checkbox" class="datatable-checkbox" multiple name="check[]" value ="' . $data['id'] . '" id="check_' . $data['id'] . '">
	<label for="check_' . $data['id'] . '"></label></div>';
	$nestedData['user_name'] = ($data['user_name']!='' || $data['user_name']!=null) ? $data['user_name'] : ( $data['created_by'] ? getSingleResult("select name from users where id=".$data['created_by']) : '');	$nestedData['partner_name'] = "<a style='display:block;color:".$color."' href='".$url."?id=".$data['id']."'>".$data['r_name'].'('.$data['r_user'].')</a>';
	$nestedData['end_user'] = $data['eu_mobile'];
	$nestedData['license_type'] = $ltype;
	$nestedData['quantity'] = "<a style='display:block;color:".$color."' href='".$url."?id=".$data['id']."'>".$data['quantity'].'</a>';
	$nestedData['company_name'] = "<a style='display:block;color:".$color."' href='".$url."?id=".$data['id']."'>".$data['school_name'].'</a>';
	$nestedData['created_date'] = date('d-m-Y',strtotime($data['created_date']));
	$nestedData['status']= $data['lead_status'];
	$nestedData['tag']= $data['tag'] ? getSingleResult("select name from tag where id=".$data['tag']) : '';
	// $nestedData['tag']= $data['sub_lead_source'];
	$nestedData['stage'] = $data['stage'];

	$results[] = $nestedData;
	$i++;
	$j++;
}
//print_r($results); die;


$json_data = array(
	"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
	"recordsTotal"    => intval($totalData),  // total number of records
	"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
	"data"            => $results   // total data array
);

//print_r($json_data); die;
echo json_encode($json_data);  // send data as json format
