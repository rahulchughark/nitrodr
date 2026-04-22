<?php include('includes/include.php');
/* Database connection end */

if ($_SESSION['user_type'] == 'CLR') {
	$vir_cond = " and activity_log.added_by=".$_SESSION['user_id']." ";
}else if($_SESSION['user_type'] == 'TEAM LEADER'){
	$callesIds = getSingleResult("SELECT caller from users where id=".$_SESSION['user_id']);
	$callesIdsA = explode(",", $callesIds);
	foreach ($callesIdsA as $value) {
	
	$calleruidQ = getSingleResult("select user_id from callers where id=".$value);
	$caller_userId[]=$calleruidQ;
	}
	$callesIdsForQ = implode(",", $caller_userId);
	$vir_cond = " and activity_log.added_by in (".$callesIdsForQ.")";
}

if ($_SESSION['sales_manager'] == 1) {
	$users_ids = db_query("select id from users where team_id in (" . $_SESSION['access'] . ") ");

	while ($uid = db_fetch_array($users_ids)) {
	    $u_ids[] = $uid['id'];
	}

	$uid = @implode("','", $u_ids);

	$vir_cond = " and activity_log.added_by in ('".$uid."') OR activity_log.added_by = ".$_SESSION['user_id']." ";
}

$requestData = $_REQUEST;

if ($requestData['d_from'] && $requestData['d_to']) {

	if ($requestData['d_from'] == $requestData['d_to']) {
		$dat = " and DATE(activity_log.created_date)='" . $requestData['d_from'] . "' ";
	} else {

		$dat = " and  (date(activity_log.created_date) BETWEEN  '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "')";
	}
}
if ($requestData['d_from'] && $requestData['d_to']) {

	if ($requestData['d_from'] == $requestData['d_to']) {
		$raw = " and DATE(activity_log.created_date)='" . $requestData['d_from'] . "' ";
	} else {

		$raw = " and  (date(activity_log.created_date) BETWEEN  '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "')";
	}
}
if ($requestData['partner']) {

	$dat .= " and o.team_id='" . $requestData['partner'] . "'";
	$raw .= " and o.team_id='" . $requestData['partner'] . "'";
	$users1 = db_query("select id,role from users where team_id='" . $requestData['partner'] . "' and status='Active' ");
	$ids = array();
	while ($uid = db_fetch_array($users1)) {
		$ids[] = $uid['id'];
	}
	$idds = implode(',', $ids);
	$id_check = " and activity_log.added_by in (" . $idds . ") ";

}

if ($requestData['call_type']) {
	$dat .= ' and activity_log.call_subject in ("' . stripslashes($requestData["call_type"]) . '")';
	$raw .= ' and activity_log.call_subject in ("' . stripslashes($requestData["call_type"]) . '")';
}

if($requestData['ark_users']){
	$dat .= " and activity_log.added_by='" . $requestData['ark_users'] . "'";
	$raw .= " and activity_log.added_by='" . $requestData['ark_users'] . "'";
}

if ($requestData['users']) {
	$dat .= " and activity_log.added_by='" . $requestData['users'] . "'";
	$raw .= " and activity_log.added_by='" . $requestData['users'] . "'";
}

if ($_SESSION['sales_manager'] == 1) {
	$dat .= " and o.team_id in (" . $_SESSION['access'] . ") ";
	$raw .= " and o.team_id in (" . $_SESSION['access'] . ") ";
}

if ($requestData['product']) {
	$dat .= " and p.product_id='" . $requestData['product'] . "' ";
	$raw .= " and o.product_id='" . $requestData['product'] . "' ";
}
if ($requestData['product_type']) {
	$dat .= " and p.product_type_id='" . $requestData['product_type'] . "' ";
	$raw .= " and o.product_type_id='" . $requestData['product_type'] . "' ";
}
if($requestData['segment']=='DTP'){
	$dat .= " and i.log_status=1";
	$raw .= " and i.log_status=1";
}
if($requestData['segment']=='Other'){
	$dat .= " and i.log_status=0";
	$raw .= " and i.log_status=1";
}

if ($requestData['industry']) {
	$dat .= ' and o.industry in ("' . stripslashes($requestData["industry"]) . '")';
	$raw .= ' and o.industry in ("' . stripslashes($requestData["industry"]) . '")';
}
if ($requestData['lead_status']) {
	$leadStt = $_REQUEST['lead_status'];
	// print_r($leadSttArr);die;
	// $leadStt = implode('","', ($leadSttArr));
	$dat .= ' and o.lead_status in ("' . $leadStt . '")';
	$raw .= ' and o.lead_status in ("' . $leadStt . '")';
}

// getting total number records without any search
$sql = "select o.id as lead,o.team_id,o.code,o.r_name,o.quantity,o.school_name,o.eu_email,o.status,o.eu_mobile,activity_log.*,o.stage FROM activity_log left join orders as o on o.id=activity_log.pid where 1 and activity_log.call_subject!='' and o.id!='' and activity_log.activity_type='Lead' and activity_log.is_intern=0 and o.agreement_type='Fresh' " . $dat . $u_cond . $vir_cond . $id_check . " order by activity_log.id desc";

// print_r($sql); 
// die;
//$sql .= " GROUP BY o.id";
$query = db_query($sql);
//$totalData = mysqli_num_rows($query);
//$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$search = " AND (o.r_name LIKE '%".$requestData['search']['value']."%' 
	 OR o.quantity LIKE '%".$requestData['search']['value']."%' 
	 OR o.school_name LIKE '%".$requestData['search']['value']."%' 
	 OR o.eu_email LIKE '%".$requestData['search']['value']."%'
	 OR activity_log.activity_type LIKE '%".$requestData['search']['value']."%' 
	 OR o.eu_mobile LIKE '%".$requestData['search']['value']."%' )";


	//$sql.=" OR eu_mobile LIKE '".$requestData['search']['value']."%' )";
$sql = "select o.id as lead,o.team_id,o.code,o.r_name,o.quantity,o.school_name,o.eu_email,o.status,o.eu_mobile,activity_log.*,o.stage FROM activity_log left join orders as o on o.id=activity_log.pid where 1 and activity_log.call_subject!='' and o.id!='' and activity_log.activity_type='Lead' and activity_log.is_intern=0 and o.agreement_type='Fresh' " . $dat . $u_cond . $vir_cond . $id_check . $search. " order by activity_log.id desc";
	
}

$query = db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData; // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";

$query = db_query($sql);

$results = array();
$i = 1;
while ($data = db_fetch_array($query)) {  // preparing an array

	$color = '#000';

	$nestedData = array();
	$nestedData[] = $i;
		// "<a style='display:block;color:" . $color . "' href='partner_dvr.php?id=" . $data['lead'] . "'>" . $data['r_name'] . '</a>';
	$nestedData[] = "<a  target='_blank' style='display:block;color:".$color."' href='view_order.php?id=".$data['lead']."'>".getSingleresult("select name from partners where id=" . $data['team_id']).'</a>';
	$nestedData[] = getSingleresult("select name from users where id=" . $data['added_by']);
	$nestedData[] = date('d-m-Y', strtotime($data['created_date']));
	$nestedData[] = $data['call_subject'];
	$nestedData[] = $data['school_name'];
	$nestedData[] = $data['stage'] ? $data['stage'] : 'N/A';
	$nestedData[] = $data['quantity'] ? $data['quantity'] : 'N/A';
	$nestedData[] = $data['description'];

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
