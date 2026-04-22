<?php include('includes/include.php');
/* Database connection end */
// print_r($_REQUEST);die;
$joinC = '';
$vir_cond = '';
$dat = '';
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
	$vir_cond = " and (o.team_id = ".$_SESSION['team_id']." OR o.allign_team_id=".$_SESSION['team_id'].")";
}else if($_SESSION['role'] == 'DA'){
	$vir_cond = " and o.stage = 'Demo'";
}else if($_SESSION['user_type'] == 'TEAM LEADER'){
	$callesIds = getSingleResult("SELECT caller from users where id=".$_SESSION['user_id']);
	$callesIdsA = explode(",", $callesIds);
	foreach ($callesIdsA as $value) {
	
	$calleruidQ = getSingleResult("select user_id from callers where id=".$value);
	$caller_userId[]=$calleruidQ;
	}
	$callesIdsForQ = implode(",", $caller_userId);
	$vir_cond = " and o.created_by in (".$callesIdsForQ.")";
}else if($_SESSION['user_type'] == 'CLR'){
	// $vir_cond = " AND ((COALESCE(o.align_to, '') = '' AND o.created_by = '".$_SESSION['user_id']."') OR (o.align_to IS NOT NULL AND o.align_to != '' AND o.align_to = '".$_SESSION['user_id']."'))";
	$vir_cond = " AND ((o.created_by = '".$_SESSION['user_id']."') OR (o.align_to = '".$_SESSION['user_id']."'))";
}else if($_SESSION['user_type'] != 'ADMIN' && $_SESSION['user_type'] != 'SUPERADMIN' && $_SESSION['role'] == 'ISS' && $_SESSION['user_type'] != 'CLR' && $_SESSION['user_type'] != 'RM'){
	$vir_cond = " AND ((o.created_by = '".$_SESSION['user_id']."') OR (o.align_to = '".$_SESSION['user_id']."'))";
	// $vir_cond = " and o.created_by = ".$_SESSION['user_id'];
}

$queryy = access_role_permission();
$fetch_query = db_fetch_array($queryy);

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columnSortOrder = 'DESC';
if (isset($requestData['order'][0]['dir'])) {
	$requestedSortDir = strtolower(trim((string)$requestData['order'][0]['dir']));
	if (in_array($requestedSortDir, ['asc', 'desc'], true)) {
		$columnSortOrder = strtoupper($requestedSortDir);
	}
}

if (
	isset($requestData['mngr_team_scope']) &&
	(int)$requestData['mngr_team_scope'] === 1 &&
	($_SESSION['user_type'] ?? '') === 'MNGR'
) {
	$loggedInUserId = (int)($_SESSION['user_id'] ?? 0);
	$mngrTeamId = 0;
	if ($loggedInUserId > 0) {
		$mngrTeamId = (int)getSingleresult("SELECT team_id FROM users WHERE id=" . $loggedInUserId . " LIMIT 1");
	}

	if ($mngrTeamId > 0) {
		$teamUsersQ = db_query("SELECT id FROM users WHERE team_id=" . $mngrTeamId);
		$teamUserIds = [];
		while ($teamUser = db_fetch_array($teamUsersQ)) {
			$teamUserIds[] = (int)$teamUser['id'];
		}

		if (!empty($teamUserIds)) {
			$vir_cond .= " AND ((o.created_by IN (" . implode(',', $teamUserIds) . ")) OR (o.created_by = " . $loggedInUserId . ") OR (o.align_to = " . $loggedInUserId . "))";
		} else {
			$vir_cond .= " AND 1=0";
		}
	} else {
		$vir_cond .= " AND 1=0";
	}
}

if (($_SESSION['user_type'] ?? '') === 'USR') {
	$usrTeamId = (int)($_SESSION['team_id'] ?? 0);
	if ($usrTeamId > 0) {
		$usrTeamUsersQ = db_query("SELECT id FROM users WHERE team_id=" . $usrTeamId);
		$usrTeamUserIds = [];
		while ($usrTeamUsersQ && ($usrTeamUser = db_fetch_array($usrTeamUsersQ))) {
			$usrTeamUserIds[] = (int)$usrTeamUser['id'];
		}

		if (!empty($usrTeamUserIds)) {
			$vir_cond .= " AND ((o.created_by IN (" . implode(',', $usrTeamUserIds) . ")) OR (o.align_to IN (" . implode(',', $usrTeamUserIds) . ")) )";
		} else {
			$vir_cond .= " AND 1=0";
		}
	} else {
		$vir_cond .= " AND 1=0";
	}
}

$stageFtr = json_decode($_REQUEST['stage']);
$lead_statusFtr =  json_decode($_REQUEST['lead_status']);
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
	}elseif($requestData['d_type']== 'stage' && $stageFtr){
		$joinC = " left join lead_modify_log as l on o.id=l.lead_id ";
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(l.created_date)='".$requestData['d_from']."' and l.modify_name in ('".implode("','",$stageFtr)."') and o.stage in ('".implode("','",$stageFtr)."')";	
		} else {
			$dat=" and DATE(l.created_date)>='".$requestData['d_from']."' and DATE(l.created_date)<='".$requestData['d_to']."' and l.modify_name in ('".implode("','",$stageFtr)."') and o.stage in ('".implode("','",$stageFtr)."')";	
		}
	}elseif($requestData['d_type']== 'lead_status' && $lead_statusFtr){
		$joinC = " left join lead_modify_log as l on o.id=l.lead_id ";
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and ((DATE(l.created_date)='".$requestData['d_from']."' and l.type='Lead Status' and l.modify_name in ('".implode("','",$lead_statusFtr)."')) || (DATE(o.created_date)='".$requestData['d_from']."')) and o.lead_status in ('".implode("','",$lead_statusFtr)."')";		
		} else {
			$dat=" and ((DATE(l.created_date)>='".$requestData['d_from']."' and DATE(l.created_date)<='".$requestData['d_to']."' and l.type='Lead Status' and l.modify_name in ('".implode("','",$lead_statusFtr)."')) || ( DATE(o.created_date)>='".$requestData['d_from']."' and DATE(o.created_date)<='".$requestData['d_to']."')) and o.lead_status in ('".implode("','",$lead_statusFtr)."')";	
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

// $ownership = trim($requestData['ownership'], '"');

// if ($ownership === 'assigned_to_me') {
//     $dat .= " AND o.ownership_changed = 1";
// } elseif ($ownership === 'my_leads') {
//     $dat .= " AND o.ownership_changed = 0";
// } elseif ($ownership === 'all') {
//     // no condition
// }

$ownership = trim($requestData['ownership'], '"');

if ($ownership === 'assigned_to_me') {
    // Show only orders that have a related active Ownership log
    $dat .= " AND EXISTS (
        SELECT 1 
        FROM lead_modify_log l
        WHERE l.lead_id = o.id
          AND l.type = 'Ownership'
          AND l.log_status = 'Active'
    )";
} elseif ($ownership === 'my_leads') {
    // Show only orders that do NOT have a related active Ownership log
    $dat .= " AND NOT EXISTS (
        SELECT 1 
        FROM lead_modify_log l
        WHERE l.lead_id = o.id
          AND l.type = 'Ownership'
          AND l.log_status = 'Active'
    )";
} elseif ($ownership === 'all') {
    // no additional condition
}

 
// print_r();
// print_r(($stageFtr));
// print_r($requestData['school_board']);
// die;

if($stageFtr)
{
	if($stageFtr[0] == 'Blank'){
		$dat.=" and (o.stage='' || o.stage is null)";
	}else{
		$dat.=" and o.stage in ('".implode("','",$stageFtr)."')";
	}
}
$cityFtr =  json_decode($_REQUEST['city']);
if($cityFtr != '')
{
	$dat.= " and o.city in (".implode(",",$cityFtr).")";
}
$substageFtr = json_decode($_REQUEST['sub_stage']);
if($substageFtr)
{
	$dat.=" and o.add_comm in ('".implode("','",$substageFtr)."')";
}
$tagFtr = json_decode($_REQUEST['tag']);
if($tagFtr)
{
	$dat.=" and o.tag in ('".implode("','",$tagFtr)."')";
}
$statusFtr =  json_decode($_REQUEST['status']);
if($statusFtr != '')
{
    $dat.=" and o.status in ('".implode("','",$statusFtr)."')";
}
// $partnerFtr =  json_decode($_REQUEST['partner']);
// if($partnerFtr != '')
// {
//     $dat.=" and o.team_id in ('".implode("','",$partnerFtr)."')";
// }
// $usersFtr =  json_decode($_REQUEST['users']);
// if($usersFtr != '')
// {
// 	// $dat.= " and o.created_by in (".implode(",",$usersFtr).")";
// 	$dat.= " AND ((o.created_by = '".$_SESSION['user_id']."') OR (o.allign_to = '".$_SESSION['user_id']."'))";
// 	// $dat.= " AND ((COALESCE(o.allign_to, '') = '' AND o.created_by in (".implode(",",$usersFtr).") OR (o.allign_to IS NOT NULL AND o.allign_to != '' AND o.allign_to in (".implode(",",$usersFtr)."))))";
// }

$partnerFtr =  json_decode($_REQUEST['partner']);
$usersFtr =  json_decode($_REQUEST['users']);
if($partnerFtr != '' && !$usersFtr)
{
	$teamUsers = db_query("SELECT id from users where team_id in (".implode(',',$partnerFtr).")");
	$usrrArr = [];
	while ($usr = db_fetch_array($teamUsers)){
		$usrrArr[] = $usr['id'];
	}
	$dat.=" AND ((o.created_by IN (" . implode(",", $usrrArr) . ")) OR (o.align_to IN (" . implode(",", $usrrArr) . "))))";
}
if($usersFtr != '')
{
	// $dat.= " and o.created_by in (".implode(",",$usersFtr).")";
	// $dat.= " AND ((COALESCE(o.align_to, '') = '' AND o.created_by in (".implode(",",$usersFtr).") OR (o.align_to IS NOT NULL AND o.align_to != '' AND o.align_to in (".implode(",",$usersFtr)."))))";
	$dat.=" AND ((o.created_by IN (" . implode(",", $usersFtr) . ")) OR (o.align_to IN (" . implode(",", $usersFtr) . "))))";
}

$school_boardFtr =  json_decode($_REQUEST['school_board']);
if($school_boardFtr != '')
{
    $dat.=" and o.school_board in ('".implode("','",$school_boardFtr)."')";
}

$product =  json_decode($_REQUEST['productDS']);
if($product != '')
{
	$joinC.=" left join tbl_lead_product as tlp on o.id=tlp.pid ";
    $dat.=" and tlp.product_id in ('".implode("','",$product)."')";
}

$product_type =  json_decode($_REQUEST['product_typeDS']);
if($product_type != '')
{
    $dat.=" and tlp.product_type_id in ('".implode("','",$product_type)."')";
}


if($lead_statusFtr != '')
{
    $dat.=" and o.lead_status in ('".implode("','",$lead_statusFtr)."')";
}
$statesFtr =  json_decode($_REQUEST['state']);
if($statesFtr != '')
{
	$dat.= " and o.state in (".implode(",",$statesFtr).")";
}
$sourceFtr =  json_decode($_REQUEST['source']);
if($sourceFtr != '')
{
	$dat.= " and o.source in ('".implode("','",$sourceFtr)."')";
}
$subscriptionTermFtr = json_decode($_REQUEST['subscription_term'], true);
if (is_array($subscriptionTermFtr) && !empty($subscriptionTermFtr))
{
	$subscriptionConditions = array();
	foreach ($subscriptionTermFtr as $term) {
		$term = trim((string)$term);
		if ($term === '1') {
			$subscriptionConditions[] = "(o.subscription_term = '1' OR LOWER(TRIM(o.subscription_term)) IN ('1 year','1 years'))";
		} elseif ($term === '3') {
			$subscriptionConditions[] = "(o.subscription_term = '3' OR LOWER(TRIM(o.subscription_term)) IN ('3 year','3 years'))";
		}
	}

	if (!empty($subscriptionConditions)) {
		$dat .= " and (" . implode(" OR ", $subscriptionConditions) . ")";
	}
}
$stageIdFtr = json_decode($_REQUEST['stage_id'], true);
if (is_array($stageIdFtr) && !empty($stageIdFtr))
{
	$stageIds = array_map('intval', $stageIdFtr);
	$stageIds = array_filter($stageIds, function($value) {
		return $value > 0;
	});
	if (!empty($stageIds)) {
		$dat .= " and o.stage_id in (" . implode(',', $stageIds) . ")";
	}
}

$proofEngagementIdFtr = json_decode($_REQUEST['proof_engagement_id'], true);
if (is_array($proofEngagementIdFtr) && !empty($proofEngagementIdFtr))
{
	$proofEngagementIds = array_map('intval', $proofEngagementIdFtr);
	$proofEngagementIds = array_filter($proofEngagementIds, function($value) {
		return $value > 0;
	});
	if (!empty($proofEngagementIds)) {
		$dat .= " and o.proof_engagement_id in (" . implode(',', $proofEngagementIds) . ")";
	}
}

$approvalStatusFtr = json_decode($_REQUEST['approval_status'], true);
if (is_array($approvalStatusFtr) && !empty($approvalStatusFtr))
{
	$approvalValues = array();
	foreach ($approvalStatusFtr as $approvalStatus) {
		if ($approvalStatus === '0' || $approvalStatus === 0 || $approvalStatus === '1' || $approvalStatus === 1) {
			$approvalValues[] = (int)$approvalStatus;
		}
	}
	$approvalValues = array_values(array_unique($approvalValues));
	if (!empty($approvalValues)) {
		$dat .= " and o.is_approved in (" . implode(',', $approvalValues) . ")";
	}
}

$assignedPartnerFtr = json_decode($_REQUEST['assigned_partner_id'], true);
if (is_array($assignedPartnerFtr) && !empty($assignedPartnerFtr))
{
	$assignedPartnerIds = array_map('intval', $assignedPartnerFtr);
	$assignedPartnerIds = array_filter($assignedPartnerIds, function($value) {
		return $value > 0;
	});
	if (!empty($assignedPartnerIds)) {
		$dat .= " and o.partner_id in (" . implode(',', $assignedPartnerIds) . ")";
	}
}

$alignToFtr = json_decode($_REQUEST['align_to']);
if(is_array($alignToFtr) && !empty($alignToFtr))
{
	$dat.= " and o.align_to in ('".implode("','",$alignToFtr)."')";
}
 
$sql = "SELECT o.* 
	FROM orders as o
        WHERE o.is_deleted = 0 AND o.is_opportunity = 0";

$query = db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;


$sql = "SELECT o.*, s.name as stage_name, pe.name as proof_engagement_name, creator.name as created_by_name ";
$sql .= "FROM orders as o 
		 LEFT JOIN tbl_mst_stage as s ON s.id = o.stage_id
		 LEFT JOIN tbl_mst_proof_engagement as pe ON pe.id = o.proof_engagement_id
		 LEFT JOIN users as creator ON creator.id = o.created_by
		 $joinC
		 WHERE o.is_deleted = 0 AND o.is_opportunity = 0";
	 

if (!empty($requestData['search']['value'])) {

    $search = $requestData['search']['value'];

    $sql .= " AND (
        o.customer_company_name LIKE '%$search%' OR
        o.customer_name LIKE '%$search%' OR
        o.email LIKE '%$search%' OR
        o.phone LIKE '%$search%' OR
        o.product LIKE '%$search%'
    )";
}

$sql .= $dat.$vir_cond;
$sql .= " GROUP BY o.id";

$query = db_query($sql);
$totalFiltered = mysqli_num_rows($query);

$sql .= " ORDER BY o.id ".$columnSortOrder." 
          LIMIT ".$requestData['start']." ,".$requestData['length'];

$query = db_query($sql);

$results = array();
$i = $requestData['start'] + 1;

while($data = db_fetch_array($query)) {

    $nestedData = array();

	$nestedData['id'] = '#'.$data['id'];
    $nestedData['company_name'] = $data['customer_company_name'];
    $nestedData['customer_name'] = $data['customer_name'];
    $nestedData['email'] = $data['email'];
    $nestedData['phone'] = $data['phone'];
    $nestedData['designation'] = $data['designation'];
    $nestedData['product'] = $data['product'];
    $nestedData['licenses'] = $data['number_of_licenses'];
	$subscriptionTermRaw = strtolower(trim((string)$data['subscription_term']));
	$nestedData['subscription_term'] = ($data['subscription_term'] == 1 || in_array($subscriptionTermRaw, array('1 year', '1 years'))) ? "1 Year" : (($data['subscription_term'] == 3 || in_array($subscriptionTermRaw, array('3 year', '3 years'))) ? "3 Year" : "");
	$nestedData['stage_id'] = $data['stage_name'] ? $data['stage_name'] : '';
	$nestedData['proof_engagement_id'] = $data['proof_engagement_name'] ? $data['proof_engagement_name'] : '';
	$nestedData['created_by_name'] = $data['created_by_name'] ? $data['created_by_name'] : '-';

	$nestedData['expiry_date'] = 'N/A';
	$nestedData['expire_in'] = 'N/A';
	$expectedClosureRaw = '';
	if (!empty($data['expected_closure_date'])) {
		$expectedClosureRaw = $data['expected_closure_date'];
	} elseif (!empty($data['expected_close_date'])) {
		$expectedClosureRaw = $data['expected_close_date'];
	}
	$nestedData['expected_closure_date'] = $expectedClosureRaw ? date('d-m-Y', strtotime($expectedClosureRaw)) : 'N/A';
	if ((int)$data['is_approved'] === 1 && !empty($data['close_date'])) {
		$expiryDateTs = strtotime($data['close_date']);
		if ($expiryDateTs) {
			$nestedData['expiry_date'] = date('d-m-Y', $expiryDateTs);

			$todayTs = strtotime(date('Y-m-d'));
			$expiryDayTs = strtotime(date('Y-m-d', $expiryDateTs));
			$daysLeft = (int)(($expiryDayTs - $todayTs) / 86400);

			if ($daysLeft > 1) {
				$nestedData['expire_in'] = $daysLeft . ' Days';
			} elseif ($daysLeft === 1) {
				$nestedData['expire_in'] = '1 Day';
			} elseif ($daysLeft === 0) {
				$nestedData['expire_in'] = 'Today';
			} else {
				$nestedData['expire_in'] = 'Expired';
			}
		}
	}

    $nestedData['status'] = $data['status'] ? 'Active' : 'Inactive';

	// For Admin/SuperAdmin, show select; for USR/MNGR, show value; others keep toggle
	$userType = $_SESSION['user_type'] ?? '';
	$approvalBadgeMode = (isset($_REQUEST['approval_badge']) && (string)$_REQUEST['approval_badge'] === '1');
	if ($approvalBadgeMode) {
		$approvalState = (int)$data['is_approved'];
		$approvalMap = [
			0 => ['label' => 'Pending', 'class' => 'pending'],
			1 => ['label' => 'Approve', 'class' => 'approved'],
			2 => ['label' => 'Reject', 'class' => 'rejected'],
			3 => ['label' => 'Onhold', 'class' => 'onboard']
		];
		$approvalData = isset($approvalMap[$approvalState]) ? $approvalMap[$approvalState] : $approvalMap[0];
		$nestedData['approval'] = '<span class="approval-badge '.$approvalData['class'].'">'.$approvalData['label'].'</span>';
	} elseif ($userType === 'ADMIN' || $userType === "OPERATIONS" || $userType === 'SUPERADMIN') {
		$approvalOptions = [
			['value' => 0, 'label' => 'Pending'],
			['value' => 1, 'label' => 'Approve'],
			['value' => 2, 'label' => 'Reject'],
			['value' => 3, 'label' => 'Onhold']
		];
		$isApprovalLocked = ((int)$data['is_approved'] === 1);
		$disabledAttr = $isApprovalLocked ? ' disabled' : '';
		$select = '<select class="approval-select" data-id="'.$data['id'].'"'.$disabledAttr.'>';
		foreach ($approvalOptions as $opt) {
			$selected = ((string)$data['is_approved'] === (string)$opt['value']) ? 'selected' : '';
			$select .= '<option value="'.$opt['value'].'" '.$selected.'>'.$opt['label'].'</option>';
		}
		$select .= '</select>';
		$nestedData['approval'] = $select;
	} else {
		$approvalState = (int)$data['is_approved'];
		$approvalMap = [
			0 => ['label' => 'Pending', 'class' => 'pending'],
			1 => ['label' => 'Approve', 'class' => 'approved'],
			2 => ['label' => 'Reject', 'class' => 'rejected'],
			3 => ['label' => 'Onhold', 'class' => 'onboard']
		];
		$approvalData = isset($approvalMap[$approvalState]) ? $approvalMap[$approvalState] : $approvalMap[0];
		$nestedData['approval'] = '<span class="approval-badge '.$approvalData['class'].'">'.$approvalData['label'].'</span>';
	}  
	// else if ($userType === 'USR' || $userType === 'MNGR') {
	// 	$nestedData['approval'] = $data['is_approved'];
	// } 
	// else {
	// 	$checked = $data['is_approved'] ? 'checked' : '';
	// 	$approvalDisabled = '';
	// 	$approvalOnChange = 'onchange="updateApproval(this)"';
	// 	$approvalCursorStyle = '';
	// 	$nestedData['approval'] = '
	// 		<label class="approval-switch" '.$approvalCursorStyle.'>
	// 			<input type="checkbox"
	// 				class="approval-toggle"
	// 				data-id="'.$data['id'].'"
	// 				'.$checked.'
	// 				'.$approvalDisabled.'
	// 				'.$approvalOnChange.'>
	// 			<span class="approval-slider" '.$approvalCursorStyle.'></span>
	// 		</label>
	// 	';
	// }

	$nestedData['action'] = '<a href="view_leads.php?eid='.$data['id'].'" class="btn btn-sm btn-info mr-1" title="View Lead"><i class="fa fa-eye"></i></a><a href="add_order.php?eid='.$data['id'].'" class="btn btn-sm btn-primary" title="Edit Lead"><i class="fa fa-edit"></i></a>';


    $nestedData['created_at'] = 
        $data['created_at'] 
        ? date('d-m-Y h:i:s', strtotime($data['created_at'])) 
        : '';

    $results[] = $nestedData;

    $i++;
}

$json_data = array(
    "draw" => intval($requestData['draw']),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $results
);

echo json_encode($json_data);



