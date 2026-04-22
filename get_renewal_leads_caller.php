<?php include('includes/include.php');
/* Database connection end */
// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;

$license_from = !empty($requestData['license_from']) ? date('Y-m-d', strtotime($requestData['license_from'])) : '';

$license_to = !empty($requestData['license_to']) ? date('Y-m-d', strtotime($requestData['license_to'])) : '';

$lic_from =  !empty($requestData['license_from']) ? date('d-m-Y', strtotime($requestData['license_from'])) : '';
//print_r($lic_from);
$lic_to = !empty($requestData['license_to']) ? date('d-m-Y', strtotime($requestData['license_to'])) : '';

if (!empty($requestData['license_from'])) {

	if ($license_from == $license_to) {
		$dat = " and (license_end_date='" . $license_from . "' or license_end_date='" . $lic_from . "')";
	} else {

		// $dat = " and ( STR_TO_DATE(LEFT(license_end_date,LOCATE(' ',license_end_date)),'%Y-%m-%d') BETWEEN '". $license_from ."'  AND '". $license_to ."' or
		// STR_TO_DATE(LEFT(license_end_date,LOCATE(' ',license_end_date)),'%d-%m-%Y') between '" . $lic_from . "' and '" . $lic_to . "')";
		$dat = " and (date(license_end_date) between '" . $license_from . "' and '" . $license_to . "' or
		date(license_end_date) between '" . $lic_from . "' and '" . $lic_to . "')";
	}
}

if ($requestData['lead_type']) {
	if ($requestData['lead_type'] == 'Internal') {
		$dat .= " and iss='1' ";
	} else if ($requestData['lead_type'] == 'LC') {
		$dat .= " and lead_type = 'LC' and iss is NULL ";
	} else {
		if (strpos($requestData['lead_type'], 'Internal'))
			$dat .= " and lead_type in ('" . $requestData['lead_type'] . "') and iss='1' ";
		else
			$dat .= " and lead_type in ('" . $requestData['lead_type'] . "')";
	}
}

if ($requestData['stage']) {
	$dat .= " and stage in ('" . $requestData['stage'] . "')";
}
if ($requestData['partner']) {
	$dat .= " and team_id='" . $requestData['partner'] . "'";
}

if ($requestData['users']) {
	$dat .= " and created_by='" . $requestData['users'] . "'";
}

if ($requestData['industry']) {
	$dat .= " and industry='" . $requestData['industry'] . "'";
}
if ($requestData['sub_industry']) {
	$dat .= " and sub_industry='" . $requestData['sub_industry'] . "'";
}

if ($requestData['expired'] == 'Yes') {
	$date = date('Y-m-d');
	//$check_date=date('Y-m-d',strtotime('-29 days',$date));
	$dat .= " and status='Approved' and close_time < '" . $date . "'";
} else if ($requestData['expired'] == 'No') {
	$date = date('Y-m-d');
	//$check_date=date('Y-m-d',strtotime('-29 days',$date));
	$dat .= " and status='Approved' and close_time > '" . $date . "'";
}

// getting total number records without any search
$select_query = db_query("select id from callers where user_id = " . $_SESSION['user_id']);
foreach ($select_query as $value) {
	$caller_arr = $value['id'];
}
//print_r($caller_arr);
$sql = "select * ";
$sql .= " FROM orders where caller = " . $caller_arr . " and license_type='Renewal' " . $dat;
// $sql = "select * ";
// $sql .= " FROM orders where caller = " . $caller_arr . " and license_type='Renewal' ";
//echo $sql; die;
$query = db_query($sql);
// foreach($query as $value){
// 	$license_date = $value['license_end_date'];
// }
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "select * ";
$sql .= " FROM orders WHERE 1=1 and caller=" . $caller_arr . " and license_type='Renewal'";
//$sql = "update orders SET license_end_date = STR_TO_DATE($license_date, '%Y-%m-%d') where caller = " . $caller_arr . " and license_type='Renewal'";
if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql .= " AND ( code LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR r_name LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR lead_type LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR quantity LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR company_name LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR eu_email LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR status LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR parent_company LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR landline LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR eu_mobile LIKE '%" . $requestData['search']['value'] . "%' )";
	//$sql.=" OR eu_landline LIKE '".$requestData['search']['value']."%' )";

	//$sql.=" OR eu_mobile LIKE '".$requestData['search']['value']."%' )";
}
$sql .= $dat . $vir_cond;
//echo $sql; die;

$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc



$query = db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql .= " ORDER BY " . $columnName . " " . $columnSortOrder . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
//echo $sql; die;
$query = db_query($sql);

$results = array();
$i = $requestData['start'] + 1;
while ($data = db_fetch_array($query)) {  // preparing an array
	$color = '#000';
	if (strtotime($data['license_end_date']) > strtotime(date('Y-m-d'))) {
		$ed = '<span style="color:green">' . date('d-M-Y', strtotime($data['license_end_date'])) . '</span>';
	} else {
		$ed = '<span style="color:red">' . date('d-M-Y', strtotime($data['license_end_date'])) . '</span>';
	}
	$nestedData = array();
	$nestedData['id'] = $i;
	$ncdate = strtotime(date('Y-m-d'));
	$closeDate = strtotime($data['close_time']);
	$nestedData['r_user'] = "<a style='display:block;color:" . $color . "' href='renewal_caller_view.php?id=" . $data['id'] . "'>" . $data['r_user'] . ($data['allign_to'] ? '(' . getSingleresult("select name from users where id=" . $data['allign_to']) . ')' : '') . '</a>';
	$nestedData['license_number'] = "<a  style='display:block;color:" . $color . "' href='renewal_caller_view.php?id=" . $data['id'] . "'>" . $data['license_key'] . '</a>';
	$nestedData['license_end_date'] = "<a  style='display:block;color:" . $color . "' href='renewal_caller_view.php?id=" . $data['id'] . "'>" . $ed . '</a>';
	$nestedData['quantity'] = "<a style='display:block;color:" . $color . "' href='renewal_caller_view.php?id=" . $data['id'] . "'>" . $data['quantity'] . '</a>';
	$nestedData['company_name'] = "<a style='display:block;color:" . $color . "' href='renewal_caller_view.php?id=" . $data['id'] . "'>" . $data['company_name'] . '</a>';

	$nestedData['created_date'] = date('d-m-Y', strtotime($data['created_date']));
	if ($data['status'] == 'Approved') {
		$ncdate = strtotime(date('Y-m-d'));
		$closeDate = strtotime($data['close_time']);
		if ($ncdate > $closeDate) {
			$dayspassedafterExpired = ceil(abs($ncdate - $closeDate) / 86400);
			$daysLeft = '<span style=color:red;">(' . $dayspassedafterExpired . ' Days Passed)</span>';

			if ($dayspassedafterExpired <= 30) {

				$daysLeft .= '<a href="javascript:void(0)" title="Re-Log" onclick="relog(' . $data['id'] . ')"> <i style="font-size:18px" class="mdi mdi-refresh"></i></a>';

				$nestedData['status'] = '<span style="color:red">Expired</span> ' . $daysLeft;
			} else {
				$nestedData['status'] = '<span style="color:red">Expired</span> ' . $daysLeft;
			}
		} else {
			$ncdate = strtotime(date('Y-m-d'));
			$closeDate = strtotime($data['close_time']);

			$remaining_days = ceil(($closeDate - $ncdate) / 84600);
			$daysLeft = '<span style="color:green">Days Left- ' . $remaining_days . '</span>';
			$nestedData['status'] = '<span style="color:green">Qualified</span> ' . $daysLeft;
		}
	} else if ($data['status'] == 'Cancelled') {
		$nestedData['status'] = '<span class="text-danger">Unqualified(' . $data['reason'] . ')</span>';
	} else if ($data['status'] == 'Pending') {
		$nestedData['status'] = 'Pending';
	} else if ($data['status'] == 'Undervalidation') {
		$nestedData['status'] = '<span class="text-warning">Re-Submission Required</span>';
	} else if ($data['status'] == 'On-Hold') {
		$nestedData['status'] = '<span class="text-blue">On-Hold</span>';
	} else if ($data['status'] == 'For Validation') {
		$nestedData['status'] = '<span class="text-themecolor">For Validation</span>';
	};


	if ($data['status'] == 'Approved') {
		if (getSingleresult("select count(id) from  lead_review where is_review=1 and lead_id='" . $data['id'] . "'")) {
			$nestedData['stage'] = '<span class="text-danger">Under Review</span>';
		} else {
			$ids = "'but" . $data['id'] . "'";
			$nestedData['stage'] = ($data['stage'] ? $data['stage'] : 'N/A');
		}
	} else {
		$nestedData['stage'] = '';
	}

	/* $nestedData[] ="<a style='display:block;color:".$color."'  href='renewal_admin_view.php?id=".$data['id']."'>".(($data['stage']=='Closed Lost')?$data['stage'].'('.$data['add_comment'].')':$data['stage']).'</a>';*/
	$nestedData['caller'] = getSingleresult("select name from callers where id='" . $data['caller'] . "'");
	$ids2 = "'but2" . $data['id'] . "'";
	$nestedData['partner_close_date'] = ($data['partner_close_date'] ? date('d-m-Y', strtotime($data['partner_close_date'])) : 'N/A') . '<a href="javascript:void(0)" title="Change Stage" id=but2' . $data['id'] . ' onclick="cd_change(' . $ids2 . ',' . $data['id'] . ')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>';
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
