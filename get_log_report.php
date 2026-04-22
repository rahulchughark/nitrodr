<?php include('includes/include.php');

$requestData = $_REQUEST;

if ($requestData['d_from'] && $requestData['d_to']) {

	if ($requestData['d_from'] == $requestData['d_to']) {
		$dat .= " and DATE(o.approval_time)='" . $requestData['d_from'] . "'";

		$activity_date = " and DATE(activity_log.created_date)='" . $requestData['d_from'] . "'";
	} else {
		$dat .= " and DATE(o.approval_time)>='" . $requestData['d_from'] . "' and DATE(o.approval_time)<='" . $requestData['d_to'] . "'";

		$activity_date = " and DATE(activity_log.created_date)>='" . $requestData['d_from'] . "' and DATE(activity_log.created_date)<='" . $requestData['d_to'] . "'";
	}
}

//print_r($requestData['caller']);die;
if ($requestData['caller']) {
	$dat .= ' and o.caller in ("' . stripslashes($requestData["caller"]) . '")';
}

if ($requestData['campaign']) {
	$dat .= " and o.campaign_type='" . $requestData['campaign'] . "'";
}

if ($requestData['lead_type']) {
	if ($requestData['lead_type'] == 'Internal') {
		$dat .= " and o.iss='1' ";
	} else if ($requestData['lead_type'] == 'LC') {
		$dat .= " and o.lead_type = 'LC' and o.iss is NULL ";
	} else {
		if (strpos($requestData['lead_type'], 'Internal'))
			$dat .= " and o.lead_type in ('" . $requestData['lead_type'] . "') and o.iss='1' ";
		else
			$dat .= " and o.lead_type in ('" . $requestData['lead_type'] . "')";
	}
}

//getting total number records without any search
if ($_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'REVIEWER' || $_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SALES MNGR'|| $_SESSION['user_type']=='CLR'|| $_SESSION['user_type']=='TEAM LEADER') {
	
	$cond .= " and o.status='Approved' ";
} else {
	$cond .= " and o.lead_type='LC' and o.status='Approved' and o.caller!=''";
}

if ($_SESSION['sales_manager'] == 1) {
	$dat .= " and o.team_id in (" . $_SESSION['access'] . ") ";
	//$raw .= " and o.team_id in (" . $_SESSION['access'] . ") ";
}

if ($requestData['partner']) {
	$cond .= " and o.team_id  in ('" . $requestData['partner'] . "')";
}

// if ($requestData['user']) {
// 	$cond .= " and o.created_by='" . $requestData['user'] . "'";
// }

if($requestData['ark_users']){
	$activity .= " and activity_log.added_by='" . $requestData['ark_users'] . "'";
	//$raw .= " and activity_log.added_by='" . $requestData['ark_users'] . "'";
}

if ($requestData['industry'] == 'DTP') {
	$dat .= " and i.log_status=1";
}
if ($requestData['industry'] == 'Other') {
	$dat .= " and i.log_status=0";
}

if ($requestData['lead_source'] == 'Internal') {
	$dat .= " and o.source = 'Internal' and o.iss='1' ";
}
if ($requestData['lead_source'] == 'Reseller') {
	$dat .= " and o.lead_type in ('LC','BD','Incoming') and o.iss is NULL";
}

$quant_arr = explode(',', $requestData['quantity']);

if (in_array('9', $quant_arr)) {
    $dat .= ' and (o.quantity in (' . stripslashes($requestData["quantity"]) . ') or o.quantity >=9)';
} else if (!in_array('9', $quant_arr) && $requestData["quantity"] != '') {
    $dat .= ' and o.quantity in (' . stripslashes($requestData["quantity"]) . ') ';
}

$sql = "select o.r_user,o.r_name,o.code,o.company_name,o.eu_email,o.eu_mobile,o.id,o.status,o.approval_time,o.campaign_type,o.iss,o.lead_type,o.license_type,o.stage,o.team_id,o.caller,o.created_by,o.quantity FROM orders as o left join activity_log as activity_log on o.id = activity_log.pid left join industry as i on o.industry=i.id where o.license_type='Commercial' " . $cond . $dat . $activity." GROUP BY o.id ";

//echo $sql; die;
// $query = db_query($sql);
// $totalData = mysqli_num_rows($query);
// $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.



if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql .= " AND ( o.code LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.r_name LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.lead_type LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.quantity LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.company_name LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.eu_email LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.status LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.eu_mobile LIKE '%" . $requestData['search']['value'] . "%' )";

	//$sql.=" OR eu_mobile LIKE '".$requestData['search']['value']."%' )";
}
//$sql.=$dat;
//$sql .= " GROUP BY o.id";
$query = db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData; // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql .= " ORDER BY o.id DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
//echo $sql; die;
$query = db_query($sql);

$results = array();
$i = $requestData['start'] + 1;
while ($data = db_fetch_array($query)) {  // preparing an array

	$color = '#000';

	$nestedData = array();
	$nestedData[] = $i;
	$cid = getSingleresult("select user_id from callers where  id='" . $data['caller'] . "'");
	$nestedData[] = "<a style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['r_name'] . '</a>';
	$nestedData[] = "<a style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['r_user'] . '</a>';
	$nestedData[] = "<a style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['company_name'] . '</a>';

	if ($_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'REVIEWER' || $_SESSION['user_type'] == 'ADMIN') {
		$nestedData[] = "<a style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['lead_type'] . '</a>';
	}
	$nestedData[] = "<a style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . getSingleresult("select name from callers where id='" . $data['caller'] . "'") . '</a>';
	$nestedData[] = date('d-m-Y', strtotime($data['approval_time']));

	if ($_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'REVIEWER' || $_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SALES MNGR') {

		$date_mod = date('d-m-Y', strtotime(getSingleresult("select activity_log.created_date from activity_log left join users as u on activity_log.added_by=u.id where activity_log.call_subject not like '%visit%' and activity_log.pid='" . $data['id'] . "' and u.user_type in ('USR','MNGR') order by activity_log.created_date desc limit 1")));

	} else {
		$date_mod = date('d-m-Y', strtotime(getSingleresult("select activity_log.created_date from activity_log left join users as u on activity_log.added_by=u.id where activity_log.call_subject not like '%visit%' and activity_log.pid='" . $data['id'] . "' and activity_log.added_by ='" . $cid . "' and u.user_type in ('USR','MNGR') order by activity_log.created_date desc limit 1")));
	}

	if ($_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'REVIEWER' || $_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SALES MNGR') {

		$date_mod_clr = date('d-m-Y', strtotime(getSingleresult("select activity_log.created_date from activity_log left join users as u on activity_log.added_by=u.id where activity_log.call_subject not like '%visit%' and activity_log.pid='" . $data['id'] . "' and u.user_type='CLR' order by activity_log.created_date desc limit 1")));

	} else {
		$date_mod_clr = date('d-m-Y', strtotime(getSingleresult("select activity_log.created_date from activity_log left join users as u on activity_log.added_by=u.id where activity_log.call_subject not like '%visit%' and activity_log.pid='" . $data['id'] . "' and u.user_type='CLR' and activity_log.added_by ='" . $cid . "' order by activity_log.created_date desc limit 1")));
	}

	$nestedData[] = (($date_mod != '01-01-1970') ? $date_mod : 'N/A');
	$nestedData[] = (($date_mod_clr != '01-01-1970') ? $date_mod_clr : 'N/A');

	if ($data['status'] == 'Approved') {
		if (getSingleresult("select count(id) from  lead_review where is_review=1 and lead_id='" . $data['id'] . "'")) {
			$nestedData[] = '<span class="text-danger">Under Review</span>';
		} else {
			$ids = "'but" . $data['id'] . "'";
			$nestedData[] = ($data['stage'] ? $data['stage'] : 'N/A');
		}
	} else {
		$nestedData[] = '';
	}

	$nestedData[] = 
	getSingleresult("select count(activity_log.id) from activity_log left join users as u on activity_log.added_by=u.id where activity_log.call_subject not like '%visit%' and u.user_type in ('USR','MNGR') and activity_log.pid=".$data['id'] .$activity. $activity_date );

	$nestedData[] = 
	getSingleresult("select count(activity_log.id) from activity_log left join users as u on activity_log.added_by=u.id where activity_log.call_subject not like '%visit%' and u.user_type='CLR' and activity_log.pid=".$data['id'] .$activity. $activity_date );
	
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
