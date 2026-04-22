<?php include('includes/include.php');
admin_protect();

@extract($_GET);

/* Database connection end */

$condition = " ";
// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;

		if($requestData['d_from']){
		if ($requestData['d_from'] == $requestData['d_to']) {
			$dat = " and DATE(o.partner_close_date)='" . $requestData['d_from'] . "'";
		} else {
			$dat = " and DATE(o.partner_close_date)>='" . $requestData['d_from'] . "' and DATE(o.partner_close_date)<='" . $requestData['d_to'] . "'";
		}
	}	
	
if ($requestData['users']) {
	$dat .= " and o.created_by='" . $requestData['users'] . "'";
}

if ($requestData['product_type']) {
	$dat .= " and p.product_id=1 and p.product_type_id='" . $requestData['product_type'] . "'";
}else{
	$condition .= " and p.product_id=1 and p.product_type_id in(1,2)";
}


// getting total number records without any search

$sql = billedAccountsSearch($_SESSION['team_id'],$dat,$condition);

//echo $sql; die;
$query = db_query($sql);
// when there is no search parameter then total number rows = total number filtered rows.

$sql = billedAccountsLead($_SESSION['team_id'],$condition);

if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql .= " AND ( o.code LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.r_name LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.lead_type LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.quantity LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.company_name LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.eu_email LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.status LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.eu_mobile LIKE '%" . $requestData['search']['value'] . "%' )";
}

$sql .= $dat;
$query = db_query($sql);

$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

$totalFiltered = mysqli_num_rows($query);
$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql .= " ORDER BY o.id desc LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
//echo $sql; die;
//$sql .= " ORDER BY " . $columnName . " " . $columnSortOrder . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";


$query = db_query($sql);

$results = array();
$i = $requestData['start'] + 1;
while ($data = db_fetch_array($query)) {  // preparing an array

	$color = '#000';

	$nestedData = array();
	$nestedData['id'] = $i;
	$ncdate = strtotime(date('Y-m-d'));
	$closeDate = strtotime($data['close_time']);
	if ($ncdate > $closeDate || $data['stage'] == 'OEM Billing' || $data['stage'] == 'Booking' || (getSingleresult("select count(id) from  lead_review where is_review=1 and lead_id='" . $data['id'] . "'"))) {
		$nestedData['code'] = 'N/A';
	} else {
		$nestedData['code'] = "<a style='display:block;color:" . $color . "' href='billed_account_view.php?id=" . $data['id'] . "'>" . ($data['code'] ? $data['code'] : 'N/A') . '</a>';
	}

	// $rec_id = db_query("select id from users where user_type IN ('SUPERADMIN','REVIEWER','OPERATIONS')");
	// $rec_id_arr  = db_fetch_array($rec_id);
	//for notification
	$title = "'" . 'Request ' . $data['lead_type'] . ' to LC' . "'";
	$company_name = "'" . $data['company_name'] . "'";
	$submitted_by = "'" . $_SESSION['name'] . "'";
	$sender_type  = "'" . 'Partner' . "'";
	$partner_name = "'" . $data['r_name'] . "'";
	$sender_id    = $_SESSION['user_id'];
	$rec_id  = [1, 12, 218];
	$receiver_id = json_encode($rec_id);



	$nestedData['r_user'] = "<a style='display:block;color:" . $color . "' href='billed_account_view.php?id=" . $data['id'] . "'>" . $data['r_user'] . ($data['allign_to'] ? '(' . getSingleresult("select name from users where id=" . $data['allign_to']) . ')' : '') . '</a>';

	$role_query = db_query("select * from users where id=" . $_SESSION['user_id']);
	$row_data = db_fetch_array($role_query);

	// if ($row_data['role'] == 'TC') {
	$call_query = db_query("select * from activity_log where call_subject like '%visit%' and pid=" . $data['id']);


	$nestedData['lead_type'] = "<a  style='display:block;color:" . $color . "' href='billed_account_view.php?id=" . $data['id'] . "'>" . $data['lead_type'] . '</a>';

	$nestedData['quantity'] = "<a style='display:block;color:" . $color . "' href='billed_account_view.php?id=" . $data['id'] . "'>" . $data['quantity'] . '</a>';

	$nestedData['product_name'] = "<a style='display:block;color:" . $color . "' href='billed_account_view.php?id=" . $data['id'] . "'>" . (getSingleresult("select p.product_name from tbl_lead_product as l left join tbl_product as p on l.product_id=p.id where l.lead_id=" . $data['id']) ? (getSingleresult("select p.product_name from tbl_lead_product as l left join tbl_product as p on l.product_id=p.id where l.lead_id=" . $data['id'])) : 'N/A') . '</a>';

	$nestedData['product_type'] = "<a style='display:block;color:" . $color . "' href='billed_account_view.php?id=" . $data['id'] . "'>" . (getSingleresult("select p.product_type from tbl_lead_product as l left join tbl_product_pivot as p on l.product_type_id=p.id where l.lead_id=" . $data['id']) ? (getSingleresult("select p.product_type from tbl_lead_product as l left join tbl_product_pivot as p on l.product_type_id=p.id where l.lead_id=" . $data['id'])) : 'N/A') . '</a>';

	$nestedData['company_name'] = "<a style='display:block;color:" . $color . "' href='billed_account_view.php?id=" . $data['id'] . "'>" . $data['company_name'] . '</a>';


	$nestedData['created_date'] = date('d-m-Y', strtotime($data['created_date']));
	if ($data['status'] == 'Approved') {
		$ncdate = strtotime(date('Y-m-d'));
		$closeDate = strtotime($data['close_time']);
		if ($ncdate > $closeDate) {
			$dayspassedafterExpired = ceil(abs($ncdate - $closeDate) / 86400);
			$daysLeft = '<span style=color:red;">(' . $dayspassedafterExpired . ' Days Passed)</span>';

			if ($dayspassedafterExpired <= 30) {

				// $daysLeft .= '<a href="javascript:void(0)" title="Re-Log" onclick="relog(' . $data['id'] . ')"> <i style="font-size:18px" class="mdi mdi-refresh"></i></a>';
				$check_valid = 1;
				$nestedData['status'] = '<span style="color:red">Expired</span> ' . $daysLeft;
			} else {
				$check_valid = 1;
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
		$nestedData['status'] = '<span class="text-warning">Re-Submission Required(' . $data['reason'] . ')</span>';
	} else if ($data['status'] == 'On-Hold') {
		$nestedData['status'] = '<span class="text-blue">On-Hold</span>';
	} else if ($data['status'] == 'For Validation') {
		$nestedData['status'] = '<span class="text-themecolor">For Validation</span>';
	};

   $nestedData['stage'] = $data['stage'];



	$nestedData['caller'] = getSingleresult("select name from callers where id='" . $data['caller'] . "'");
	$ids2 = "'but2" . $data['id'] . "'";
	if (!$check_valid) {
		
			$nestedData['partner_close_date'] = ($data['partner_close_date'] ? date('d-m-Y', strtotime($data['partner_close_date'])) : 'N/A');
		
		
	} else if (getSingleresult("select count(id) from  lead_review where is_review=1 and lead_id='" . $data['id'] . "'")) {
		$nestedData['partner_close_date'] = 'N/A';
	} else {
		$nestedData['partner_close_date'] = ($data['partner_close_date'] ? date('d-m-Y', strtotime($data['partner_close_date'])) : 'N/A');
	}
	$results[] = $nestedData;
	$check_valid = 0;
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
