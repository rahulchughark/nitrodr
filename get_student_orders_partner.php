<?php include('includes/include.php');
admin_protect();

@extract($_GET);

/* Database connection end */
if ($_SESSION['user_type'] == 'USR') {
	$u_cond = " and o.created_by='" . $_SESSION['user_id'] . "' ";
}

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;
if ($requestData['d_from'] && $requestData['d_to']) {

	if ($requestData['dtype'] == 'created') {
		if ($requestData['d_from'] == $requestData['d_to']) {
			$dat = " and DATE(o.created_date)='" . $requestData['d_from'] . "'";
		} else {
			$dat = " and DATE(o.created_date)>='" . $requestData['d_from'] . "' and DATE(o.created_date)<='" . $requestData['d_to'] . "'";
		}
	} else if ($requestData['dtype'] == 'close') {
		if ($requestData['d_from'] == $requestData['d_to']) {
			$dat = " and DATE(o.partner_close_date)='" . $requestData['d_from'] . "'";
		} else {
			$dat = " and DATE(o.partner_close_date)>='" . $requestData['d_from'] . "' and DATE(o.partner_close_date)<='" . $requestData['d_to'] . "'";
		}
	} else {
		if ($requestData['d_from'] == $requestData['d_to']) {
			$dat = " and DATE(o.created_date)='" . $requestData['d_from'] . "'";
		} else {
			$dat = " and DATE(o.created_date)>='" . $requestData['d_from'] . "' and DATE(o.created_date)<='" . $requestData['d_to'] . "'";
		}
	}
}

$requestData['lead_type'] = stripslashes($requestData['lead_type']);

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
if ($requestData['ltype']) {
	$dat .= " and o.license_type='" . $requestData['ltype'] . "'";
}
if ($requestData['stage']) {
	$dat .= " and o.stage in ('" . stripslashes($requestData['stage']) . "')";
}

if ($requestData['status']) {
	$dat .= ' and o.status in ("' . stripslashes($requestData["status"]) . '")';
}

if ($requestData['validation_type']) {
	$dat .= " and o.validation_type='" . $requestData['validation_type'] . "'";
}
if ($requestData['association_name']) {
	$dat .= ' and o.association_name in ("' . stripslashes($requestData["association_name"]) . '")';
}

if ($requestData['users']) {
	$dat .= ' and o.created_by in ("' . stripslashes($requestData["users"]) . '")';
}
if ($requestData['caller']){
	$dat .= " and o.caller in (" . $requestData['caller'] . ")";
}

$quant_arr = explode(',', $requestData['quantity']);

if (in_array('9', $quant_arr)) {
	$dat .= ' and (o.quantity in (' . stripslashes($requestData["quantity"]) . ') or o.quantity >=9)';
} else if (!in_array('9', $quant_arr) && $requestData["quantity"] != '') {
	$dat .= ' and o.quantity in (' . stripslashes($requestData["quantity"]) . ') ';
}

if ($requestData['industry'])
	$dat .= ' and o.industry in ("' . stripslashes($requestData["industry"]) . '")';
if ($requestData['sub_industry'])
	$dat .= ' and o.sub_industry in ("' . stripslashes($requestData["sub_industry"]) . '")';
if ($requestData['runrate_key'])
	$dat .= ' and o.runrate_key in ("' . stripslashes($requestData["runrate_key"]) . '")';
if ($requestData['os'])
	$dat .= " and o.os='" . $requestData['os'] . "'";
if ($requestData['expired'] == 'Yes') {
	$date = date('Y-m-d');
	//$check_date=date('Y-m-d',strtotime('-29 days',$date));
	$dat .= " and o.status='Approved' and o.close_time < '" . $date . "'";
} else if ($requestData['expired'] == 'No') {
	$date = date('Y-m-d');
	//$check_date=date('Y-m-d',strtotime('-29 days',$date));
	$dat .= " and o.status='Approved' and o.close_time > '" . $date . "'";
}

if ($requestData['campaign']) {
	$dat .= ' and o.campaign_type in ("' . stripslashes($requestData["campaign"]) . '")';
}

if ($requestData['product']) {
	$dat .= ' and p.product_id in ("' . stripslashes($requestData["product"]) . '")';
}
if ($requestData['product_type']) {
	$dat .= ' and p.product_type_id in ("' . stripslashes($requestData["product_type"]) . '")';
}

//dashboard
if ($requestData['untouched'] == 1) {
	$unCon .= "and a.created_date >= DATE_SUB(CURDATE(), INTERVAL 10 DAY)";
} elseif ($requestData['untouched'] == 2) {
	$unCon .= "and a.created_date >= DATE_SUB(CURDATE(), INTERVAL 15 DAY)";
} elseif ($requestData['untouched'] == 3) {
	$unCon .= "and a.created_date >= DATE_SUB(CURDATE(), INTERVAL 20 DAY)";
} elseif ($requestData['untouched'] == 4) {
	$unCon .= "and a.created_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
}

if ($requestData['stages'] == 'quote') {
	$dat .= " and o.stage='Quote'";
} elseif ($requestData['stages'] == 'follow') {
	$dat .= " and o.stage='Follow-Up' ";
} elseif ($requestData['stages'] == 'commit') {
	$dat .= " and o.stage='Commit' ";
} elseif ($requestData['stages'] == 'eupo') {
	$dat .= " and o.stage='EU PO Issued' ";
} elseif ($requestData['stages'] == 'booking') {
	$dat .= " and o.stage='Booking' ";
} elseif ($requestData['stages'] == 'billing') {
	$dat .= " and o.stage='OEM Billing' ";
}

if ($requestData['meter'] == 1) {
	$dat .= "and o.status='Approved'";
} elseif ($requestData['meter'] == 2) {
	$dat .= "and o.status='Cancelled'";
} elseif ($requestData['meter'] == 3) {
	$dat .= "and o.status='Undervalidation'";
}

if ($requestData['poa']) {
	$dat .= "and activity_log.action_plan in ('" . stripslashes($requestData['poa']) . "')";
}

if(($_SESSION['user_type'] == 'USR') && ($_SESSION['role'] == 'TC')){
	$dat .= " and o.created_by='" . $_SESSION['user_id'] . "' ";
}

$date = date('Y-m-d');
$month = date("n", strtotime('F'));
$dat1 = $requestData['month'];
$dat2 = $requestData['year'];

// getting total number records without any search
if ($requestData['untouched']) {

	// select o.* from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2 or tp.product_type_id=3) and o.license_type='Student' and  o.stage NOT IN('Booking','OEM Billing','Closed Lost','Billed To Other Re-Seller','Hold License Certificate/Copy') and o.status='Approved' and o.quantity >= 3 ".$u_cond." AND NOT EXISTS( SELECT a.pid,a.created_date FROM activity_log a WHERE a.pid=o.id $caller_id $dat2) GROUP BY o.id ORDER BY o.id desc

	$sql = "select o.r_user,o.r_name,o.code,o.company_name,o.eu_email,o.eu_mobile,o.id,o.created_date,o.status,o.partner_close_date,o.prospecting_date,o.campaign_type,o.iss,o.lead_type,o.license_type,o.stage,o.team_id,o.caller,o.created_by,o.quantity,o.industry,o.sub_industry,o.runrate_key,o.os,o.close_time,o.dvr_flag,o.reason,o.association_name,p.product_id,p.product_type_id ";
	$sql .= " FROM orders as o left join tbl_lead_product as p on o.id=p.lead_id where p.product_id = 1 and (p.product_type_id=1 or p.product_type_id=2) and o.is_iss_lead = 0 and o.dvr_flag=0 and o.status='Approved' AND o.stage NOT IN('Booking','OEM Billing','Closed Lost','Billed To Other Re-Seller','Hold License Certificate/Copy') $u_cond and o.license_type='Student' and p.product_id!=' ' and o.team_id='" . $_SESSION['team_id'] . "' AND NOT EXISTS( SELECT a.pid,a.created_date FROM activity_log a WHERE a.pid=o.id $unCon) ";
} elseif ($requestData['score'] == 1) {

	$sql = "select o.r_user,o.r_name,o.code,o.company_name,o.eu_email,o.eu_mobile,o.id,o.created_date,o.status,o.partner_close_date,o.prospecting_date,o.campaign_type,o.iss,o.lead_type,o.license_type,o.stage,o.team_id,o.caller,o.created_by,o.quantity,o.industry,o.sub_industry,o.runrate_key,o.os,o.close_time,o.dvr_flag,o.reason,o.association_name,p.product_id,p.product_type_id ";
	$sql .= " FROM orders as o left join tbl_lead_product as p on o.id=p.lead_id where p.product_id = 1 and (p.product_type_id=1 or p.product_type_id=2) and o.is_iss_lead = 0 and o.dvr_flag=0 and o.license_type='Student' and o.team_id='" . $_SESSION['team_id'] . "' and o.status='Approved' and date(o.approval_time)='" . $date . "'" . $dat . $u_cond;
} elseif ($requestData['meter']) {
	$sql = "select o.r_user,o.r_name,o.code,o.company_name,o.eu_email,o.eu_mobile,o.id,o.created_date,o.status,o.partner_close_date,o.prospecting_date,o.campaign_type,o.iss,o.lead_type,o.license_type,o.stage,o.team_id,o.caller,o.created_by,o.quantity,o.industry,o.sub_industry,o.runrate_key,o.os,o.close_time,o.dvr_flag,o.reason,o.association_name,tp.product_id,tp.product_type_id ";
	$sql .= " from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_id = 1 and (tp.product_type_id=1 or tp.product_type_id=2) and o.is_iss_lead = 0 and o.dvr_flag=0 and o.license_type='Student' and o.team_id='" . $_SESSION['team_id'] . "' and MONTH(o.approval_time)='" . $dat1 . "' && YEAR(o.approval_time)='" . $dat2 . "'" . $dat . $u_cond;
} elseif ($requestData['stages']) {
	$sql = "select o.r_user,o.r_name,o.code,o.company_name,o.eu_email,o.eu_mobile,o.id,o.created_date,o.status,o.partner_close_date,o.prospecting_date,o.campaign_type,o.iss,o.lead_type,o.license_type,o.stage,o.team_id,o.caller,o.created_by,o.quantity,o.industry,o.sub_industry,o.runrate_key,o.os,o.close_time,o.dvr_flag,o.reason,o.association_name,p.product_id,p.product_type_id ";
	$sql .= " FROM activity_log as a left join orders as o on a.pid=o.id left join tbl_lead_product as p on o.id=p.lead_id where p.product_id = 1 and (p.product_type_id=1 or p.product_type_id=2) and o.is_iss_lead = 0 and o.dvr_flag=0 and o.license_type='Student'  and o.team_id='" . $_SESSION['team_id'] . "' and MONTH(o.partner_close_date)=" . $dat1 . " and YEAR(o.partner_close_date)=" . $dat2 . "" . $dat . $u_cond;
} else {

	$sql = "select o.r_user,o.r_name,o.code,o.company_name,o.eu_email,o.eu_mobile,o.id,o.created_date,o.status,o.partner_close_date,o.prospecting_date,o.campaign_type,o.iss,o.lead_type,o.license_type,o.stage,o.team_id,o.caller,o.created_by,o.quantity,o.industry,o.sub_industry,o.runrate_key,o.os,o.close_time,o.dvr_flag,o.reason,o.association_name,p.product_id,p.product_type_id ";
	$sql .= " FROM orders as o left join tbl_lead_product as p on o.id=p.lead_id left join activity_log on o.id=activity_log.pid where o.is_iss_lead = 0 and o.dvr_flag=0 and o.license_type='Student' and p.product_id!=' ' and o.team_id='" . $_SESSION['team_id'] . "' " . $dat;
}
$sql .= " GROUP BY o.id";
//echo $sql; die;
$query = db_query($sql);
// when there is no search parameter then total number rows = total number filtered rows.

if ($requestData['untouched']) {

	$sql = "select o.r_user,o.r_name,o.code,o.company_name,o.eu_email,o.eu_mobile,o.id,o.created_date,o.status,o.partner_close_date,o.prospecting_date,o.campaign_type,o.iss,o.lead_type,o.license_type,o.stage,o.team_id,o.caller,o.created_by,o.quantity,o.industry,o.sub_industry,o.runrate_key,o.os,o.close_time,o.dvr_flag,o.reason,o.association_name,p.product_id,p.product_type_id ";
	$sql .= " FROM orders as o left join tbl_lead_product as p on o.id=p.lead_id where p.product_id = 1 and (p.product_type_id=1 or p.product_type_id=2) and o.is_iss_lead = 0 and o.dvr_flag=0 and o.status='Approved' AND o.stage NOT IN('Booking','OEM Billing','Closed Lost','Billed To Other Re-Seller','Hold License Certificate/Copy') $u_cond and o.license_type='Student' and p.product_id!=' ' and o.team_id='" . $_SESSION['team_id'] . "' AND NOT EXISTS( SELECT a.pid,a.created_date FROM activity_log a WHERE a.pid=o.id $unCon) ";
} elseif ($requestData['score'] == 1) {

	$sql = "select o.r_user,o.r_name,o.code,o.company_name,o.eu_email,o.eu_mobile,o.id,o.created_date,o.status,o.partner_close_date,o.prospecting_date,o.campaign_type,o.iss,o.lead_type,o.license_type,o.stage,o.team_id,o.caller,o.created_by,o.quantity,o.industry,o.sub_industry,o.runrate_key,o.os,o.close_time,o.dvr_flag,o.reason,o.association_name,p.product_id,p.product_type_id ";
	$sql .= " FROM orders as o left join tbl_lead_product as p on o.id=p.lead_id where p.product_id = 1 and (p.product_type_id=1 or p.product_type_id=2) and o.is_iss_lead = 0 and o.dvr_flag=0 and o.license_type='Student' and o.team_id='" . $_SESSION['team_id'] . "' and o.status='Approved' and date(o.approval_time)='" . $date . "'" . $dat . $u_cond;
} elseif ($requestData['meter']) {
	$sql = "select o.r_user,o.r_name,o.code,o.company_name,o.eu_email,o.eu_mobile,o.id,o.created_date,o.status,o.partner_close_date,o.prospecting_date,o.campaign_type,o.iss,o.lead_type,o.license_type,o.stage,o.team_id,o.caller,o.created_by,o.quantity,o.industry,o.sub_industry,o.runrate_key,o.os,o.close_time,o.dvr_flag,o.reason,o.association_name,tp.product_id,tp.product_type_id ";
	$sql .= " from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_id = 1 and (tp.product_type_id=1 or tp.product_type_id=2) and o.is_iss_lead = 0 and o.dvr_flag=0 and o.license_type='Student' and o.team_id='" . $_SESSION['team_id'] . "' and MONTH(o.approval_time)='" . $dat1 . "' && YEAR(o.approval_time)='" . $dat2 . "'" . $dat . $u_cond;
} elseif ($requestData['stages']) {
	$sql = "select o.r_user,o.r_name,o.code,o.company_name,o.eu_email,o.eu_mobile,o.id,o.created_date,o.status,o.partner_close_date,o.prospecting_date,o.campaign_type,o.iss,o.lead_type,o.license_type,o.stage,o.team_id,o.caller,o.created_by,o.quantity,o.industry,o.sub_industry,o.runrate_key,o.os,o.close_time,o.dvr_flag,o.reason,o.association_name,p.product_id,p.product_type_id ";
	$sql .= " FROM orders as o left join tbl_lead_product as p on o.id=p.lead_id where p.product_id = 1 and (p.product_type_id=1 or p.product_type_id=2) and o.is_iss_lead = 0 and o.dvr_flag=0 and o.license_type='Student' and o.team_id='" . $_SESSION['team_id'] . "' and MONTH(o.partner_close_date)=" . $dat1 . " and YEAR(o.partner_close_date)=" . $dat2 . "" . $dat . $u_cond;
} else {
	$sql = "select o.r_user,o.r_name,o.code,o.company_name,o.eu_email,o.eu_mobile,o.id,o.created_date,o.status,o.partner_close_date,o.prospecting_date,o.campaign_type,o.iss,o.lead_type,o.license_type,o.stage,o.team_id,o.caller,o.created_by,o.quantity,o.industry,o.sub_industry,o.runrate_key,o.os,o.close_time,o.dvr_flag,o.reason,o.association_name,p.product_id,p.product_type_id ";
	$sql .= " FROM orders as o left join tbl_lead_product as p on o.id=p.lead_id left join activity_log on o.id=activity_log.pid WHERE o.is_iss_lead = 0 and o.dvr_flag=0  and o.license_type='Student' and o.team_id='" . $_SESSION['team_id'] . "' ";
}

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
$sql .= " GROUP BY o.id";
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

	$action_plan = db_query("select action_plan from activity_log where pid='" . $data['id'] . "' order by id desc limit 1");
	$action_plan_arr = db_fetch_array($action_plan);

	if ($action_plan_arr['action_plan'] == 'Need More Validation') {
		$color = '#006400';
		$bold = 'bold';
	} else if ($action_plan_arr['action_plan'] == 'Drop' || $action_plan_arr['action_plan'] == 'Turns Negative') {
		$color = '#8B0000';
		$bold = 'bold';
	} else {
		$color = '#000';
		$bold = '0';
	}



	$nestedData = array();
	$nestedData['id'] = $i;
	$ncdate = strtotime(date('Y-m-d'));
	$closeDate = strtotime($data['close_time']);

	if ($ncdate > $closeDate || $data['stage'] == 'OEM Billing' || $data['stage'] == 'Booking' || (getSingleresult("select count(id) from  lead_review where is_review=1 and lead_id='" . $data['id'] . "'"))) {
		$nestedData['code'] = "<span style='color:" . $color . ";font-weight:" . $bold . "'>N/A</span>";
	} else {
		$nestedData['code'] = "<a target='_blank' style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='partner_view.php?id=" . $data['id'] . "'>" . ($data['code'] ? $data['code'] : 'N/A') . '</a>';
	}


	//for notification
	$title = "'" . 'Request ' . $data['lead_type'] . ' to LC' . "'";
	$company_name = "'" . $data['company_name'] . "'";
	$submitted_by = "'" . $_SESSION['name'] . "'";
	$sender_type  = "'" . 'Partner' . "'";
	$partner_name = "'" . $data['r_name'] . "'";
	$sender_id    = $_SESSION['user_id'];
	$rec_id  = [1, 12, 218];
	$receiver_id = json_encode($rec_id);


	$nestedData['r_user'] = "<a target='_blank' style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='partner_view.php?id=" . $data['id'] . "'>" . $data['r_user'] . ($data['allign_to'] ? '(' . getSingleresult("select name from users where id=" . $data['allign_to']) . ')' : '') . '</a>';

	$role_query = db_query("select * from users where id=" . $_SESSION['user_id']);
	$row_data = db_fetch_array($role_query);

	// if ($row_data['role'] == 'TC') {
	//$call_query = db_query("select * from activity_log where call_subject like '%visit%' and pid=" . $data['id']);


	if ($data['lead_type'] != "LC" && $data['status'] == 'Approved' && $_SESSION['user_type'] != 'PUSR') {

		if (getSingleresult("select count(id) from lead_notification 
		where sender_type='Partner' and type_id =" . $data['id'] . " and sender_id =" . $_SESSION['user_id'] . " and is_read=0")) {

			$nestedData['lead_type'] = "<a target='_blank' style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='partner_view.php?id=" . $data['id'] . "'>" . $data['lead_type'] . '</a>';
		} else{
			//else if ($row_data['role'] != 'TC' && (mysqli_num_rows($call_query) > 0)) {
			$nestedData['lead_type'] = "<a target='_blank' style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='partner_view.php?id=" . $data['id'] . "'>" . $data['lead_type'] . '</a>';
		}
		// else {
		// 	$nestedData['lead_type'] = "<a target='_blank' style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='partner_view.php?id=" . $data['id'] . "'>" . $data['lead_type'] . '</a>';
		//  }
	} else {
		$nestedData['lead_type'] = "<a target='_blank' style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='partner_view.php?id=" . $data['id'] . "'>" . $data['lead_type'] . '</a>';
	}

	$nestedData['quantity'] = "<a target='_blank' style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='partner_view.php?id=" . $data['id'] . "'>" . $data['quantity'] . '</a>';

	$nestedData['product_name'] = "<a target='_blank' style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='partner_view.php?id=" . $data['id'] . "'>" . (getSingleresult("select p.product_name from tbl_lead_product as l left join tbl_product as p on l.product_id=p.id where l.lead_id=" . $data['id']) ? (getSingleresult("select p.product_name from tbl_lead_product as l left join tbl_product as p on l.product_id=p.id where l.lead_id=" . $data['id'])) : 'N/A') . '</a>';

	$nestedData['product_type'] = "<a target='_blank' style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='partner_view.php?id=" . $data['id'] . "'>" . (getSingleresult("select p.product_type from tbl_lead_product as l left join tbl_product_pivot as p on l.product_type_id=p.id where l.lead_id=" . $data['id']) ? (getSingleresult("select p.product_type from tbl_lead_product as l left join tbl_product_pivot as p on l.product_type_id=p.id where l.lead_id=" . $data['id'])) : 'N/A') . '</a>';

	$nestedData['company_name'] = "<a target='_blank' style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='partner_view.php?id=" . $data['id'] . "'>" . $data['company_name'] . '</a>';


	$nestedData['created_date'] = "<span style='color:" . $color . ";font-weight:" . $bold . "'>" . date('d-m-Y', strtotime($data['created_date'])) . "</span>";

	if ($data['status'] == 'Approved') {
		$ncdate = strtotime(date('Y-m-d'));
		$closeDate = strtotime($data['close_time']);
		if ($ncdate > $closeDate) {
			$dayspassedafterExpired = ceil(abs($ncdate - $closeDate) / 86400);
			$daysLeft = '<span style=color:red;">(' . $dayspassedafterExpired . ' Days Passed)</span>';

			if ($dayspassedafterExpired <= 30) {

				$daysLeft .= '<a href="javascript:void(0)" title="Re-Log" onclick="relog(' . $data['id'] . ')"> <i style="font-size:18px" class="mdi mdi-refresh"></i></a>';
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
	} else if ($data['status'] == 'Already locked') {
		$nestedData['status'] = '<span class="text-themecolor">Already locked</span>';
	} else if ($data['status'] == 'Undervalidation') {
		$nestedData['status'] = '<span class="text-themecolor">Re-Submission Required</span>';
	} else if ($data['status'] == 'Insufficient Information') {
		$nestedData['status'] = '<span class="text-themecolor">Insufficient Information</span>';
	} else if ($data['status'] == 'Incorrect Information') {
		$nestedData['status'] = '<span class="text-themecolor">Incorrect Information</span>';
	} else if ($data['status'] == 'Out Of Territory') {
		$nestedData['status'] = '<span class="text-themecolor">Out Of Territory</span>';
	} else if ($data['status'] == 'Duplicate Record Found') {
		$nestedData['status'] = '<span class="text-themecolor">Duplicate Record Found</span>';
	};



	if ($data['status'] == 'Approved') {
		if (getSingleresult("select count(id) from  lead_review where is_review=1 and lead_id='" . $data['id'] . "'")) {
			$nestedData['stage'] = '<span class="text-danger">Under Review</span>';
		} else {
			$ids = "'but" . $data['id'] . "'";
			$nestedData['stage'] = "<span style='color:" . $color . ";font-weight:" . $bold . "'>" . ($data['stage'] ? $data['stage'] : 'N/A') . "</span>" . '<a href="javascript:void(0)" title="Change Stage" id=but' . $data['id'] . ' onclick="stage_change(' . $ids . ',' . $data['id'] . ')"> <i style="font-size:18px;color:"' . $color . ';font-weight:' . $bold . '" class="mdi mdi-update"></i></a>';
		}
	} else if ($data['status'] == 'Approved' && $check_valid) {

		$nestedData['stage'] = "<span style='color:" . $color . ";font-weight:" . $bold . "'>" . ($data['stage'] ? $data['stage'] : 'N/A') . "</span>";
	} else if ($data['status'] == 'Cancelled' && !$check_valid) {

		if (getSingleresult("select count(id) from  lead_review where is_review=1 and lead_id='" . $data['id'] . "'")) {
			$nestedData['stage'] = '<span class="text-danger">Under Review</span>';
		} else {
			$nestedData['stage'] = "<span style='color:" . $color . ";font-weight:" . $bold . "'>N/A</span>";
		}
		// else {
		// 	$ids = "'but" . $data['id'] . "'";
		// 	$nestedData['stage'] = ($data['stage'] ? $data['stage'] : 'N/A') . '<a href="javascript:void(0)" title="Change Stage" id=but' . $data['id'] . ' onclick="stage_change(' . $ids . ',' . $data['id'] . ')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>';
		// }
	} else {
		$nestedData['stage'] = '';
	}

	/* $nestedData[] ="<a style='display:block;color:".$color."'  href='renewal_admin_view.php?id=".$data['id']."'>".(($data['stage']=='Closed Lost')?$data['stage'].'('.$data['add_comment'].')':$data['stage']).'</a>';*/




	$nestedData['caller'] = "<span style='color:" . $color . ";font-weight:" . $bold . "'>" . getSingleresult("select name from callers where id='" . $data['caller'] . "'") . "</span>";

	$ids2 = "'but2" . $data['id'] . "'";

	if (!$check_valid) {
		$nestedData['partner_close_date'] = ($data['partner_close_date'] ? date('d-m-Y', strtotime($data['partner_close_date'])) : 'N/A') . '<a href="javascript:void(0)" title="Change Stage" id=but2' . $data['id'] . ' onclick="cd_change(' . $ids2 . ',' . $data['id'] . ')"> <i style="font-size:18px;color:"' . $color . ';font-weight:" . $bold . "" class="mdi mdi-update"></i></a>';
	} else if (getSingleresult("select count(id) from  lead_review where is_review=1 and lead_id='" . $data['id'] . "'")) {
		$nestedData['partner_close_date'] = "<span style='color:" . $color . ";font-weight:" . $bold . "'>N/A</span>";
	} else {
		$nestedData['partner_close_date'] = "<span style='color:" . $color . ";font-weight:" . $bold . "'>" . ($data['partner_close_date'] ? date('d-m-Y', strtotime($data['partner_close_date'])) : 'N/A') . "</span>";
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
