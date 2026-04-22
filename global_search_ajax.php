<?php include('includes/include.php');
/* Database connection end */
$subString = '';
$userType = $_SESSION['user_type'];
$partner = $_SESSION['team_id'];
$user_id = $_SESSION['user_id'];

$leftjoin = '';
// print_r($_GET);die;
if ($_SESSION['sales_manager'] == 1) {
	$vir_cond = " and o.team_id in (" . $_SESSION['access'] . ") ";
}
if ($_SESSION['sales_manager'] == 1) {
	$region_access=getSingleresult("select region_access from users where id='".$_SESSION['user_id']."'");
	if($region_access) { $regions=explode(',',$region_access);
	$search_region=array();
	foreach($regions as $region)
	{
		$search_region[]="'".$region."'";
	}
	// $vir_cond .= " and region in (" . implode(",",$search_region) . ") ";
}
}
// print_r($_SESSION);die;
if($_SESSION['role'] == 'PARTNER'){
	$sql_lead = " and (o.team_id = ".$_SESSION['team_id']." OR o.allign_team_id=".$_SESSION['team_id'].")";
}else if($_SESSION['user_type'] == 'TEAM LEADER'){
	$callesIds = getSingleResult("SELECT caller from users where id=".$_SESSION['user_id']);
	$callesIdsA = explode(",", $callesIds);
	foreach ($callesIdsA as $value) {
	
	$calleruidQ = getSingleResult("select user_id from callers where id=".$value);
	$caller_userId[]=$calleruidQ;
	}
	$callesIdsForQ = implode(",", $caller_userId);
	$sql_lead = " and o.created_by in (".$callesIdsForQ.")";
}else if($_SESSION['user_type'] == 'CLR'){
	// $sql_lead = " AND ((COALESCE(o.allign_to, '') = '' AND o.created_by = '".$_SESSION['user_id']."') OR (o.allign_to IS NOT NULL AND o.allign_to != '' AND o.allign_to = '".$_SESSION['user_id']."'))";
	$sql_lead = " AND ((o.created_by = '".$_SESSION['user_id']."') OR (o.allign_to = '".$_SESSION['user_id']."'))";
}else if($_SESSION['user_type'] != 'ADMIN' && $_SESSION['user_type'] != 'SUPERADMIN' && $_SESSION['role'] == 'ISS' && $_SESSION['user_type'] != 'CLR'){
	$sql_lead = " and o.created_by = ".$_SESSION['user_id'];
}

if($_GET['type'] == 'leads')
{
	$Url = $userType == 'MNGR' ? 'view_order.php' : 'view_order.php';
}else{
	$Url = 'view_opportunity.php';
}

$requestData = $_REQUEST;

if (!empty($requestData['search'])) {
	$sql_lead .= " and ( o.code LIKE '%" . $requestData['search'] . "%' ";
	$sql_lead .= " OR o.r_name LIKE '%" . $requestData['search'] . "%' ";
	$sql_lead .= " OR o.quantity LIKE '%" . $requestData['search'] . "%' ";
	$sql_lead .= " OR o.school_name LIKE '%" . $requestData['search'] . "%' ";
	$sql_lead .= " OR o.eu_email LIKE '%" . $requestData['search'] . "%' ";
	$sql_lead .= " OR o.status LIKE '%" . $requestData['search'] . "%' ";
	$sql_lead .= " OR o.eu_mobile LIKE '%" . $requestData['search'] . "%' ";
	$sql_lead .= " OR o.eu_landline LIKE '%" . $requestData['search'] . "%' ";
	$sql_lead .= " OR o.website LIKE '%" . $requestData['search'] . "%' ";
	$sql_lead .= " OR o.eu_name LIKE '%" . $requestData['search'] . "%' )";
}


if ($_GET['type'] == 'leads'){
	$sql_l = "select o.* FROM orders as o where o.is_opportunity=0 AND o.agreement_type='Fresh' AND 1=1 ".$vir_cond.$sql_lead;
} else if ($_GET['type'] == 'renewal') {
	$sql_l = "select * FROM orders as o where o.agreement_type='Renewal' and o.is_opportunity=1 and 1 ".$sql_lead.$vir_cond;
} else if ($_GET['type'] == 'opportunity') { 
	$sql_l = "select o.* FROM orders as o where o.is_opportunity=1 AND o.agreement_type='Fresh' AND 1=1 ".$vir_cond.$sql_lead;
}

//echo $sql_l; die;

$sql_f = $sql_l; //.' UNION '.$sql_lap; //' UNION '.$sql_r.' UNION '.$sql_u;
$query = db_query($sql_f);
$totalData = mysqli_num_rows($query);
// print_r($totalData);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

// $columnIndex = $requestData['order'][0]['column']; // Column index 
// $columnName = $requestData['columns'][$columnIndex]['data']; // Column name
// $columnSortOrder = $requestData['order'][0]['dir']; // asc or desc


$sql_f .= " GROUP BY o.id ORDER BY o.id desc LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
//print_r($totalFiltered);
//echo $sql_f; die;
$query = db_query($sql_f);

$results = array();
$i = $requestData['start'] + 1;

while ($data = db_fetch_array($query)) {  // preparing an array
	$url = $Url . "?id=" . $data['id'];
	$nestedData = array();
	$nestedData['id'] = $i;
	$style = 'style="display:block;color:#000"';


	$nestedData['r_name'] = "<a $style href='" . $url . "'>" . $data['r_name'] . '(' . $data['r_user'] . ')</a>';

	$nestedData['school_board'] = "<a $style href='" . $url . "'>" . $data['school_board'] . '</a>';
	$nestedData['school_name'] = "<a $style href='" . $url . "'>" . $data['school_name'] . '</a>';
	// print_r($_GET);die;
	if($_GET['type'] == 'renewal' || $_GET['type'] == 'opportunity'){
		$products = array();
		$quantitys = array();
		$proquery = db_query("SELECT * FROM tbl_lead_product_opportunity where status=1 and lead_id =" . $data['id']);
		$count = mysqli_num_rows($proquery);
		if ($count) {
			while ($data_n = db_fetch_array($proquery)) { 
				$products[] = getSingleresult("SELECT product_name FROM tbl_product_opportunity where id=".$data_n['product']);
				$quantitys[] = $data_n['quantity'];
			}
		}
		
		$nestedData['product'] = $products ? implode(' | ',$products) : '';
		$nestedData['quantity'] = $quantitys ? implode(' | ',$quantitys) : '';
		$nestedData['grand_total'] = $data['grand_total_price'];
	}else {
		$nestedData['tag'] = $data['tag'] ? getSingleResult("SELECT name from tag where id=".$data['tag']) : 'N/A';
		$nestedData['allign_to'] = $data['allign_to'] ? getSingleResult("SELECT name from users where id=".$data['allign_to']) : 'N/A';
	}
	
	$ids = "'but" . $data['id'] . "'";
	$nestedData['status'] = ($data['lead_status']?$data['lead_status']:'N/A').'<a href="javascript:void(0)" title="Change Status" id=but'.$data['id'].' onclick="status_change('.$ids.','.$data['id'].')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>';
	$nestedData['qualified_status'] = "<a $style href='" . $url . "'>" . $data['status'] . '</a>';

	$nestedData['sub_stage'] = $data['add_comm']?$data['add_comm']:'N/A';
	$nestedData['created_date'] = date('d-m-Y', strtotime($data['created_date']));
	$nestedData['close_date'] = "<span style='color:" . $color . ";font-weight:" . $bold . "'>" . ($data['expected_close_date'] ? date('d-m-Y', strtotime($data['expected_close_date'])) : 'N/A') . "</span>" . (($fetch_query['edit_date'] == 1) ? ('<a href="javascript:void(0)" title="Change Stage" id=but2' . $data['id'] . ' onclick="cd_change(' . $ids . ',' . $data['id'] . ')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>') : '');

	$nestedData['stage'] = ($data['stage']?$data['stage']:'N/A').'<a href="javascript:void(0)" title="Change Stage" id=but'.$data['id'].' onclick="stage_change('.$ids.','.$data['id'].')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>';

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
