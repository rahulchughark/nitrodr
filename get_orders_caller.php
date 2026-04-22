<?php include('includes/include.php');
$requestData = $_REQUEST;

$iss_users = db_query("select users.id,users.role,c.id as caller,c.name from users left join callers as c on users.id=c.user_id where users.user_type='CLR' and users.role='ISS' and users.status='Active' ");
$ids = array();
// print_r($requestData);die;
while ($uid = db_fetch_array($iss_users)) {
	$ids[] = $uid['caller'];
	$user_ids[] = $uid['id'];
	$caller_name[] = $uid['name'];
}
$iss_ids = @implode(',', $ids);
$caller_ids = @implode(',', $user_ids);
$iss_names = @implode("','", $caller_name);

if ($_SESSION['user_type'] == 'CLR') {

	$u_cond = " and o.caller='" . $_SESSION['caller'] . "' ";
	$caller_id = " and a.added_by='" . $_SESSION['user_id'] . "'";
	$lc_count_caller = " and lm.modify_name='" . $_SESSION['name'] . "'";

} else if ($_SESSION['user_type'] == 'ISS MNGR' || $_SESSION['user_type'] == 'ISS SBE MNGR') {

	$u_cond = " and o.caller in (" . $iss_ids . ")";
	$caller_id = " and a.added_by in (" . $caller_ids . ")";
	$lc_count_caller = " and lm.modify_name in ('" . $iss_names . "')";
}

if($requestData['d_from'] && $requestData['d_to'])
{
	if($requestData['d_type']== 'close'){
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(o.expected_close_date)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(o.expected_close_date)>='".$requestData['d_from']."' and DATE(o.expected_close_date)<='".$requestData['d_to']."'";	
		}
	}elseif($requestData['d_type']== 'actioned_date'){
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(o.approval_time)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(o.approval_time)>='".$requestData['d_from']."' and DATE(o.approval_time)<='".$requestData['d_to']."'";	
		}
	}else{
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(o.created_date)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(o.created_date)>='".$requestData['d_from']."' and DATE(o.created_date)<='".$requestData['d_to']."'";	
		}
	}
}

$partnerFtr =  json_decode($_REQUEST['partner']);
if($partnerFtr != '')
{
    $dat.=" and o.team_id in ('".implode("','",$partnerFtr)."')";
}

$stageFtr =  json_decode($_REQUEST['stage']);
if($stageFtr !='')
{
	$dat.=" and o.stage in ('".implode("','",$stageFtr)."')";
}
$statusFtr =  json_decode($_REQUEST['status']);
if($statusFtr != '')
{
    $dat.=" and o.status in ('".implode("','",$statusFtr)."')";
}

$sub_productFtr =  json_decode($_REQUEST['sub_product']);
if($sub_productFtr != '')
{
	$dat.=" and tlp.product_type_id in (".implode(",",$sub_productFtr).")";
}



if ($requestData['untouched'] == 7) {
	$dat2 .= " and a.created_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
} elseif ($requestData['untouched'] == 15) {
	$dat2 .= " and a.created_date >= DATE_SUB(CURDATE(), INTERVAL 15 DAY)";
} elseif ($requestData['untouched'] == 30) {
	$dat2 .= " and a.created_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
}


$cid = getSingleresult("select id from callers where user_id='" . $_SESSION['user_id'] . "'");

if($cid == ""){
	$cid = "not_exist";
}
// getting total number records without any search
if ($_GET['my'] == 'yes') {
	$cond = " and ( caller='" . $cid . "' OR o.created_by='" . $_SESSION['user_id'] . "')";
}

if ($requestData['counter']) {
	  if($requestData['type'] && $requestData['type'] == 'iss_sbe'){
	  	$sql = "select * from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2 or tp.product_type_id=3) and o.dvr_flag=0 and o.is_iss_lead = 0 and o.status='Approved' and o.quantity >=3 and o.license_type='Commercial' and date(o.approval_time)>='" . $requestData['d_from'] . "' and o.caller!='' and date(o.approval_time)<='" . $requestData['d_to'] . "' and o.team_id=" . $requestData['counter'] . " and o.caller in (".$iss_ids.") GROUP BY o.id ORDER BY o.id desc";
	  }else{
	$sql = "select * from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.dvr_flag=0 and o.is_iss_lead = 0 and o.status='Approved' and o.license_type='Commercial' and date(o.approval_time)>='" . $requestData['d_from'] . "' and o.caller!='' and date(o.approval_time)<='" . $requestData['d_to'] . "' and o.team_id=" . $requestData['counter'] . $cond . " GROUP BY o.id ORDER BY o.id desc";
     }
} else if ($requestData['untouched']) {
	if($requestData['type'] && $requestData['type'] == 'iss_sbe'){
		// echo "testing"; die;
	  	$sql = "select o.* from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2 or tp.product_type_id=3) and o.license_type='Commercial' and  o.stage NOT IN('Booking','OEM Billing','Closed Lost','Billed To Other Re-Seller','Hold License Certificate/Copy') and o.status='Approved' and o.quantity >= 3 ".$u_cond." AND NOT EXISTS( SELECT a.pid,a.created_date FROM activity_log a WHERE a.pid=o.id $caller_id $dat2) GROUP BY o.id ORDER BY o.id desc";
	  }else{
	$sql = "select o.* from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.license_type='Commercial' and  o.stage NOT IN('Booking','OEM Billing','Closed Lost','Billed To Other Re-Seller','Hold License Certificate/Copy') and o.status='Approved' ".$u_cond." AND NOT EXISTS( SELECT a.pid,a.created_date FROM activity_log a WHERE a.pid=o.id $caller_id $dat2) GROUP BY o.id ORDER BY o.id desc";
     }
} else if ($requestData['lc_count']) {
	if($requestData['type'] && $requestData['type'] == 'iss_sbe'){
	  	$sql = "select * from (select o.* from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id where tp.product_type_id in (1,2,3) and o.license_type='Commercial' and o.quantity >=3 and date(lm.created_date)>='" . $requestData['d_from'] . "' and date(lm.created_date)<='" . $requestData['d_to'] . "' and lm.type='Lead Type' and lm.previous_name in ('BD','Incoming') and lm.modify_name='LC' and o.r_name='" . $requestData['lc_count'] . "' GROUP BY o.id) t1 INNER JOIN (select o.* from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id where tp.product_type_id in (1,2,3) and o.license_type='Commercial' and o.quantity >=3 and date(lm.created_date)>='" . $requestData['d_from'] . "' and date(lm.created_date)<='" . $requestData['d_to'] . "' and lm.type='Caller' and lm.previous_name =''" . $lc_count_caller . " and o.r_name='" . $requestData['lc_count'] . "' GROUP BY o.id) t2 on t1.r_name=t2.r_name GROUP BY t2.id";
	  }else{
	$sql = "select * from (select o.* from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id where tp.product_type_id in (1,2) and o.license_type='Commercial' and date(lm.created_date)>='" . $requestData['d_from'] . "' and date(lm.created_date)<='" . $requestData['d_to'] . "' and lm.type='Lead Type' and lm.previous_name in ('BD','Incoming') and lm.modify_name='LC' and o.r_name='" . $requestData['lc_count'] . "' GROUP BY o.id) t1 INNER JOIN (select o.* from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id where tp.product_type_id in (1,2) and o.license_type='Commercial' and date(lm.created_date)>='" . $requestData['d_from'] . "' and date(lm.created_date)<='" . $requestData['d_to'] . "' and lm.type='Caller' and lm.previous_name =''" . $lc_count_caller . " and o.r_name='" . $requestData['lc_count'] . "' GROUP BY o.id) t2 on t1.r_name=t2.r_name GROUP BY t2.id";
     }
} else {
	$sql = "select o.* FROM orders as o left join activity_log on o.id=activity_log.pid left join tbl_lead_product as tlp on o.id=tlp.lead_id where o.agreement_type not in ('Renewal') and o.dvr_flag=0 " . $dat . $cond . " GROUP BY o.id ORDER BY o.id desc";
}

//$sql .= " GROUP BY o.id";
// echo $sql; die;
$query = db_query($sql);


if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$search = " AND (o.r_name LIKE '%" . $requestData['search']['value'] . "%' 
	 OR o.quantity LIKE '%" . $requestData['search']['value'] . "%' 
	 OR o.company_name LIKE '%" . $requestData['search']['value'] . "%' 
	 OR lead_type LIKE '%" . $requestData['search']['value'] . "%'
	 OR o.eu_email LIKE '%" . $requestData['search']['value'] . "%'
	 OR o.status LIKE '%" . $requestData['search']['value'] . "%'
	 OR o.eu_mobile LIKE '%" . $requestData['search']['value'] . "%' )";


if ($requestData['counter']) {
	$sql = "select * from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.dvr_flag=0 and o.is_iss_lead = 0 and o.status='Approved' and o.license_type='Commercial' and date(o.approval_time)>='" . $requestData['d_from'] . "' and o.caller!='' and date(o.approval_time)<='" . $requestData['d_to'] . "' and o.team_id=" . $requestData['counter'] . $cond . $search." GROUP BY o.id ORDER BY o.id desc";
} else if ($requestData['untouched']) {
	$sql = "select * from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.dvr_flag=0 and o.is_iss_lead = 0 and o.status='Approved' and o.license_type='Commercial' and o.caller!='' AND NOT EXISTS( SELECT a.pid FROM activity_log a WHERE a.pid=o.id $caller_id)" . $u_cond . $dat . $search." GROUP BY o.id ORDER BY o.id desc";
} else if ($requestData['lc_count']) {
	$sql = "select * from (select o.* from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id where tp.product_type_id in (1,2) and o.license_type='Commercial' and date(lm.created_date)>='" . $requestData['d_from'] . "' and date(lm.created_date)<='" . $requestData['d_to'] . "' and lm.type='Lead Type' and lm.previous_name in ('BD','Incoming') and lm.modify_name='LC' and o.r_name='" . $requestData['lc_count'] . "' $search GROUP BY o.id) t1 INNER JOIN (select o.* from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id where tp.product_type_id in (1,2) and o.license_type='Commercial' and date(lm.created_date)>='" . $requestData['d_from'] . "' and date(lm.created_date)<='" . $requestData['d_to'] . "' and lm.type='Caller' and lm.previous_name ='' " . $lc_count_caller . " and o.r_name='" . $requestData['lc_count'] . "' $search GROUP BY o.id) t2 on t1.r_name=t2.r_name GROUP BY t2.id ";
} else {
	$sql = "select o.* FROM orders as o left join activity_log on o.id=activity_log.pid left join tbl_lead_product as tlp on o.id=tlp.lead_id WHERE o.dvr_flag=0 ";
	$sql .= $cond . $dat . $search. " GROUP BY o.id ORDER BY o.id desc";
}

}


$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc

//$sql .= " GROUP BY o.id";
$query = db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
//echo $sql; die;
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

	$nestedData['code'] = "<a style='display:block;color:".$color."' href='caller_view.php?id=".$data['id']."'>".($data['code']?$data['code']:'N/A').'</a>';

	$nestedData['r_name'] = "<a style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='caller_view.php?id=" . $data['id'] . "'>" . $data['r_name'] . '</a>';

	
	$nestedData['school_board'] = "<a style='display:block;color:".$color."' href='caller_view.php?id=".$data['id']."'>".$data['school_board'].'</a>';
	
	$nestedData['school_name'] = "<a style='display:block;color:".$color."' href='caller_view.php?id=".$data['id']."'>".$data['school_name'].'</a>';
	
	$nestedData['quantity'] = "<a style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='caller_view.php?id=" . $data['id'] . "'>" . $data['quantity'] . '</a>';
	$nestedData['sub_product'] = getSingleresult("select tp.product_type from tbl_lead_product as tlp left join tbl_product_pivot as tp on tp.id=tlp.product_type_id where tlp.lead_id=" . $data['id']);

	$nestedData['created_date'] = "<span style='color:" . $color . ";font-weight:" . $bold . "'>" . date('d-m-Y', strtotime($data['created_date'])) . "</span>";

	if ($data['status'] == 'Approved') {
		$ncdate = strtotime(date('Y-m-d'));
		$closeDate = strtotime($data['close_time']);
		if ($ncdate > $closeDate) {
			$dayspassedafterExpired = ceil(abs($ncdate - $closeDate) / 86400);
			$daysLeft = '<span style=color:red;">(' . $dayspassedafterExpired . ' Days Passed)</span>';
			$nestedData['status'] = '<span style="color:red">Expired</span> ' . $daysLeft;
		} else {

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
			$nestedData['stage'] = "<span style='color:" . $color . ";font-weight:" . $bold . "'>" . ($data['stage'] ? $data['stage'] : 'N/A') . "</span>" . '<a href="javascript:void(0)" title="Change Stage" id=but' . $data['id'] . ' onclick="stage_change(' . $ids . ',' . $data['id'] . ')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>';
		}
	} else {
		$nestedData['stage'] = '';
	}

	$nestedData['close_date'] = $data['expected_close_date'];
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
