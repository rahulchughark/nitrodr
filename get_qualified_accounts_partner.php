<?php include('includes/include.php');

$teamId = $_SESSION['team_id'];
// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;

//$requestData['partner'] = intval($requestData['partner']);
$requestData['length'] = intval($requestData['length']);
$requestData['start'] = intval($requestData['start']);
$requestData['search']['value'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($requestData['search']['value']));
$con = '';
$con2 = '';

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
// 	$con .= ' and o.industry in (' . stripslashes($requestData["industry"]) . ')';
// 	$con2 .= ' and ol.industry in (' . stripslashes($requestData["industry"]) . ')';
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

$stCon1 = " AND ml.type='Status' AND ml.modify_name='Approved'";
$stCon2 = " AND ml.type='Lead Type' AND ml.modify_name='LC'";

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

// if ($requestData['segment'] == 'DTP') {
// 	$dat .= " and i.log_status=1";
// }
// if ($requestData['segment'] == 'Other') {
// 	$dat .= " and i.log_status=0";
// }

// if ($_SESSION['sales_manager'] == 1) {
// 	$dat .= " and o.team_id in (" . $_SESSION['access'] . ") ";
// }



// getting total number records without any search

// UNION ALL 
// 	     SELECT ol.id,ol.r_name,ol.r_user,ol.lead_type,ol.company_name,ol.quantity,ol.created_date,ol.created_date,ol.status,i.name,ml.created_date as actioned_date FROM  lapsed_orders as ol left join industry as i on ol.industry=i.id left left join lead_modify_log as ml on ol.id=ml.lead_id where ol.status='Approved' AND ml.type='Status' AND ml.modify_name='Approved' order by id DESC

$sql = "SELECT * FROM (SELECT o.id,o.r_name,o.r_user,o.lead_type,o.company_name,o.quantity,o.created_date,o.status,o.close_time,i.name as industry,ml.created_date as actioned_date,ml.type  FROM orders as o left join industry as i on o.industry=i.id join lead_modify_log as ml on o.id=ml.lead_id where o.status='Approved' AND o.team_id=$teamId $stCon1 $con GROUP by o.id
UNION SELECT o.id,o.r_name,o.r_user,o.lead_type,o.company_name,o.quantity,o.created_date,o.status,o.close_time,i.name as industry,ml.created_date as actioned_date,ml.type FROM orders as o left join industry as i on o.industry=i.id join lead_modify_log as ml on o.id=ml.lead_id where o.status='Approved' AND o.team_id=$teamId $stCon2 $con GROUP by o.id
UNION SELECT ol.id,ol.r_name,ol.r_user,ol.lead_type,ol.company_name,ol.quantity,ol.created_date,ol.status,ol.close_time,i.name as industry,ml.created_date as actioned_date,ml.type  FROM lapsed_orders as ol left join industry as i on ol.industry=i.id join lead_modify_log as ml on ol.id=ml.lead_id where ol.status='Approved' AND ol.team_id=$teamId $stCon1 $con2 GROUP by ol.id 
UNION SELECT ol.id,ol.r_name,ol.r_user,ol.lead_type,ol.company_name,ol.quantity,ol.created_date,ol.status,ol.close_time,i.name as industry,ml.created_date as actioned_date,ml.type FROM lapsed_orders as ol left join industry as i on ol.industry=i.id join lead_modify_log as ml on ol.id=ml.lead_id where ol.status='Approved' AND ol.team_id=$teamId $stCon2 $con2 GROUP by ol.id) as i order by i.id DESC";

// echo $sql; die;

//$sql .= " GROUP BY ol.id";
$query = db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
//echo $totalFiltered; die;
	$search = "";
	$search2 = "";
if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$search = " AND (o.r_name LIKE '%" . $requestData['search']['value'] . "%' 
	 OR o.quantity LIKE '%" . $requestData['search']['value'] . "%' 
	 OR o.company_name LIKE '%" . $requestData['search']['value'] . "%' )";

	 $search2 = " AND (ol.r_name LIKE '%" . $requestData['search']['value'] . "%' 
	 OR ol.quantity LIKE '%" . $requestData['search']['value'] . "%' 
	 OR ol.company_name LIKE '%" . $requestData['search']['value'] . "%' )";

	$sql = "SELECT * FROM (SELECT o.id,o.r_name,o.r_user,o.lead_type,o.company_name,o.quantity,o.created_date,o.status,o.close_time,i.name as industry,ml.created_date as actioned_date,ml.type  FROM orders as o left join industry as i on o.industry=i.id join lead_modify_log as ml on o.id=ml.lead_id where o.status='Approved' AND o.team_id=$teamId $search $stCon1 $con GROUP by o.id
	UNION SELECT o.id,o.r_name,o.r_user,o.lead_type,o.company_name,o.quantity,o.created_date,o.status,o.close_time,i.name as industry,ml.created_date as actioned_date,ml.type FROM orders as o left join industry as i on o.industry=i.id join lead_modify_log as ml on o.id=ml.lead_id where o.status='Approved' AND o.team_id=$teamId $search $stCon2 $con GROUP by o.id
	UNION SELECT ol.id,ol.r_name,ol.r_user,ol.lead_type,ol.company_name,ol.quantity,ol.created_date,ol.status,ol.close_time,i.name as industry,ml.created_date as actioned_date,ml.type  FROM lapsed_orders as ol left join industry as i on ol.industry=i.id join lead_modify_log as ml on ol.id=ml.lead_id where ol.status='Approved' AND ol.team_id=$teamId $search2 $stCon1 $con2 GROUP by ol.id 
	UNION SELECT ol.id,ol.r_name,ol.r_user,ol.lead_type,ol.company_name,ol.quantity,ol.created_date,ol.status,ol.close_time,i.name as industry,ml.created_date as actioned_date,ml.type FROM lapsed_orders as ol left join industry as i on ol.industry=i.id join lead_modify_log as ml on ol.id=ml.lead_id where ol.status='Approved' AND ol.team_id=$teamId $search2 $stCon2 $con2 GROUP by ol.id) as i order by i.id DESC";

}


//echo $sql; die;

$query = db_query($sql);

$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

$sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
//echo $sql; die;

$query = db_query($sql);

$results = array();
$i = $requestData['start'] + 1;
while ($data = db_fetch_array($query)) {  // preparing an array
	//	print_r($data['is_read']); 
	$color = '#000';
	$nestedData = array();
	$ncdate=strtotime(date('Y-m-d')); 
	$closeDate=strtotime($data['close_time']);

   $submission_type = ($data['type'] == 'Status')?'Fresh':'Converted';

	$nestedData['id'] = $i;
	$nestedData['r_name'] =  "<a target='_blank' style='display:block;color:" . $color . "' href='partner_view.php?id=" . $data['id'] . "'>" .  $data['r_user'] . '</a>';
	$nestedData['lead_type'] = "<a target='_blank' style='display:block;color:" . $color . "' href='partner_view.php?id=" . $data['id'] . "'>" . $data['lead_type'] . '</a>';
	$nestedData['company_name'] =  "<a target='_blank' style='display:block;color:" . $color . "' href='partner_view.php?id=" . $data['id'] . "'>" . $data['company_name'] . '</a>';
	$nestedData['quantity'] = "<a target='_blank' style='display:block;color:" . $color . "' href='partner_view.php?id=" . $data['id'] . "'>" . $data['quantity'] . '</a>';
	$nestedData['industry'] = "<a target='_blank' style='display:block;color:" . $color . "' href='partner_view.php?id=" . $data['id'] . "'>" . $data['industry'] . '</a>';
	$nestedData['created_date'] = "<a target='_blank' style='display:block;color:" . $color . "' href='partner_view.php?id=" . $data['id'] . "'>" . date('d-m-Y',strtotime($data['created_date'])) . '</a>';
	$nestedData['actioned_date'] = "<a target='_blank' style='display:block;color:" . $color . "' href='partner_view.php?id=" . $data['id'] . "'>" . date('d-m-Y',strtotime($data['actioned_date'])) . '</a>';
	$nestedData['submission_type'] = "<a target='_blank' style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . $submission_type . '</a>';

		if ($ncdate > $closeDate) {
			$dayspassedafterExpired = ceil(abs($ncdate - $closeDate) / 86400);
			$daysLeft = '<span style=color:red;">(' . $dayspassedafterExpired . ' Days Passed)</span>';
			$nestedData['status'] = '<span style="color:red">Expired</span> ' . $daysLeft;
		} else {

			$remaining_days = ceil(($closeDate - $ncdate) / 84600);
			$daysLeft = '<span style="color:green">Days Left- ' . $remaining_days . '</span>';
			$nestedData['status'] = '<span style="color:green">Qualified</span> ' . $daysLeft;
		}


	$results[] = $nestedData;
	$i++;
}
//print_r($results); die;


$json_data = array(
	"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
	"recordsTotal"    => intval($totalData),  // total number of records
	"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
	"data"            => $results   // total data array
);

echo json_encode($json_data);  // send data as json format
