<?php include('includes/include.php');

if ($_SESSION['sales_manager'] == 1) {
	$vir_cond = " and o.team_id in (" . $_SESSION['access'] . ") ";
} else if ($_SESSION['user_type'] == 'MNGR') {
	$vir_cond .= " and o.team_id='" . $_SESSION['team_id'] . "' ";
}

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;

$results = [];
if ($requestData['d_from'] && $requestData['d_to']) {
	if ($requestData['d_from'] == $requestData['d_to']) {
		$dat .= " and DATE(o.approval_time)='" . $requestData['d_from'] . "'";
	} else {
		$dat .= " and  ((DATE(o.approval_time) BETWEEN  '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "'))";
	}
}

if ($requestData['partner']) {
	$dat .= " and o.team_id='" . $requestData['partner'] . "'";
}

if ($requestData['license_type']) {
	$dat .= " and o.license_type='" . $requestData['license_type'] . "'";
}

if ($requestData['users']) {
	$dat .= " and o.created_by='" . $requestData['users'] . "'";
}

if ($requestData['contains'] == 'not_updated' && $requestData['cat_type'] == 'Stage') {
	$dat .= " AND NOT EXISTS (SELECT a.lead_id FROM lead_modify_log a WHERE a.lead_id=o.id and a.raw_id=0 and a.type='Stage')";
} else if ($requestData['contains'] == 'not_updated' && $requestData['cat_type'] == 'Close Date') {
	$dat .= " AND NOT EXISTS (SELECT a.lead_id FROM lead_modify_log a WHERE a.lead_id=o.id and a.raw_id=0 and a.type='Close Date')";
} else if ($requestData['contains'] == 'updated' && $requestData['cat_type'] == 'Stage') {
	$dat .= " AND EXISTS (SELECT a.lead_id FROM lead_modify_log a WHERE a.lead_id=o.id and a.raw_id=0 and a.type='Stage')";
} else if ($requestData['contains'] == 'updated' && $requestData['cat_type'] == 'Close Date') {
	$dat .= " AND EXISTS (SELECT a.lead_id FROM lead_modify_log a WHERE a.lead_id=o.id and a.raw_id=0 and a.type='Close Date')";
} else if ($requestData['contains'] == 'not_updated' && $requestData['cat_type'] == 'logCall') {
	$dat .= " AND NOT EXISTS (SELECT a.pid FROM activity_log a WHERE a.pid=o.id and a.activity_type='Lead')";
} else if ($requestData['contains'] == 'updated' && $requestData['cat_type'] == 'logCall') {
	$dat .= " AND EXISTS (SELECT a.pid FROM activity_log a WHERE a.pid=o.id and a.activity_type='Lead')";
}

if ($requestData['duration'] == '3days') {
	$dat .= " and DATE(o.approval_time) >= DATE_SUB(CURDATE(), INTERVAL 3 DAY)";
}

if ($requestData['duration'] == '7days') {
	$dat .= " and DATE(o.approval_time) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
}
if ($requestData['duration'] == '10days') {
	$dat .= " and DATE(o.approval_time) >= DATE_SUB(CURDATE(), INTERVAL 10 DAY)";
}


if ($requestData['cat_type'] == 'Stage') {
	$sql = "SELECT o.created_by,o.created_date,o.lead_type,o.id,o.status,o.quantity,o.company_name,o.stage,o.partner_close_date FROM orders as o LEFT JOIN tbl_lead_product as p on o.id=p.lead_id WHERE p.product_type_id in (1,2) and o.status='Approved' " . $dat . $vir_cond . "  ORDER By o.id Desc";
} else if ($requestData['cat_type'] == 'Close Date') {
	$sql = "SELECT o.r_user,o.created_by,o.created_date,o.lead_type,o.id,o.status,o.quantity,o.company_name,o.stage,o.partner_close_date FROM orders as o LEFT JOIN tbl_lead_product as p on o.id=p.lead_id WHERE p.product_type_id in (1,2) and o.status='Approved' " . $dat . $vir_cond . "  ORDER By o.id Desc";
} else if ($requestData['cat_type'] == 'logCall') {
	$sql ="SELECT o.r_user,o.created_by,o.created_date,o.lead_type,o.id,o.status,o.quantity,o.company_name,o.stage,o.partner_close_date FROM orders as o LEFT JOIN tbl_lead_product as p on o.id=p.lead_id WHERE p.product_type_id in (1,2) and o.status='Approved'  " . $dat . $vir_cond . " ORDER By o.id Desc";
}else{
	$sql = "SELECT o.r_user,o.created_by,o.created_date,o.lead_type,o.id,o.status,o.quantity,o.company_name,o.stage,o.partner_close_date FROM orders as o LEFT JOIN tbl_lead_product as p on o.id=p.lead_id WHERE p.product_type_id in (1,2) and o.status='Approved'  " . $dat . $vir_cond . " ORDER By o.id Desc";
}
//echo $sql;
$query = db_query($sql);
//print_r($query);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;


//for pagination
 $sql = "SELECT o.r_user,o.created_by,o.created_date,o.lead_type,o.id,o.status,o.quantity,o.company_name,o.stage,o.partner_close_date from orders as o left join tbl_lead_product as p on o.id=p.lead_id where p.product_type_id in (1,2) and o.status='Approved'";

 if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql .= " AND ( o.r_name LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.lead_type LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.quantity LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.company_name LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.eu_email LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.status LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.eu_mobile LIKE '%" . $requestData['search']['value'] . "%' )";
}

$sql.=$dat.$vir_cond;

$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql .= " ORDER BY o.id desc LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";

$query = db_query($sql);

$results = array();
$i = $requestData['start'] + 1;
$color = '#000';
$nestedData = [];
$j = 1;

while ($data = db_fetch_array($query)) {  // preparing an array

	$nestedData['serial'] = $j;
	$nestedData['submitted_by'] = "<a target='_blank' style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . getSingleresult("select u.name from users as u left join orders as o on o.created_by=u.id where o.created_by=" . $data['created_by']) . '</a>';

	$nestedData['company_name'] = "<a target='_blank' style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['company_name'] . '</a>';
	$nestedData['lead_type'] = "<a target='_blank' style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['lead_type'] . '</a>';
	$nestedData['quantity'] = "<a target='_blank' style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['quantity'] . '</a>';
	$nestedData['stage'] = "<a target='_blank' style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['stage'] . '</a>';
	$nestedData['closed_date'] = date('d-m-Y', strtotime($data['partner_close_date']));
	$nestedData['created_date'] = date('d-m-Y', strtotime($data['created_date']));

	if ($requestData['cat_type'] == 'Close Date' || $requestData['cat_type'] == 'Stage') {
		$updated_on = getSingleresult("select created_date from lead_modify_log where lead_id=" . $data['id'] . " order by id desc limit 1");
		$nestedData['updated_date'] = date('d-m-Y', strtotime($updated_on));
	} else {
		$updated_on = getSingleresult("select created_date from activity_log where pid=" . $data['id'] . " order by id desc limit 1");
		$nestedData['updated_date'] = date('d-m-Y', strtotime($updated_on));
	}

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
