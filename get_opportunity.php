<?php include('includes/include.php');
/* Database connection end */
// print_r($_REQUEST);die;
$joinC = '';
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
}
}
if($_SESSION['user_type'] == 'MNGR'){
	$teamId = (int)$_SESSION['team_id'];
	$vir_cond = " AND ((o.created_by IN (SELECT id FROM users WHERE status='Active' AND team_id='".$teamId."')) OR (o.align_to IN (SELECT id FROM users WHERE status='Active' AND team_id='".$teamId."')))";
}else if($_SESSION['role'] == 'PARTNER'){
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
	$vir_cond = " AND ((o.created_by = '".$_SESSION['user_id']."') OR (o.align_to = '".$_SESSION['user_id']."'))";
}else if($_SESSION['user_type'] != 'ADMIN' && $_SESSION['user_type'] != 'SUPERADMIN' && $_SESSION['role'] == 'ISS' && $_SESSION['user_type'] != 'CLR' && $_SESSION['user_type'] != 'RM'){
	$vir_cond = " AND ((o.created_by = '".$_SESSION['user_id']."') OR (o.align_to = '".$_SESSION['user_id']."'))";
}

$queryy = access_role_permission();
$fetch_query = db_fetch_array($queryy);

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
$stageFtr = json_decode($_REQUEST['stage']);
$lead_statusFtr =  json_decode($_REQUEST['lead_status']);
if($requestData['d_from'] && $requestData['d_to'])
{
	if($requestData['d_type']== 'close'){
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(o.expected_closure_date)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(o.expected_closure_date)>='".$requestData['d_from']."' and DATE(o.expected_closure_date)<='".$requestData['d_to']."'";	
		}
	}elseif($requestData['d_type']== 'actioned_date'){
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(o.created_at)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(o.created_at)>='".$requestData['d_from']."' and DATE(o.created_at)<='".$requestData['d_to']."'";	
		}
	}else{
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(o.created_at)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(o.created_at)>='".$requestData['d_from']."' and DATE(o.created_at)<='".$requestData['d_to']."'";	
		}
	}
}

// Apply filters from admin_leads.php
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
        WHERE o.is_deleted = 0 AND o.is_opportunity = 1";

$query = db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;


$sql = "SELECT o.*, s.name as stage_name, pe.name as proof_engagement_name, u.name as created_by_name, p.name as partner_name ";
$sql .= "FROM orders as o 
		 LEFT JOIN tbl_mst_stage as s ON s.id = o.stage_id
		 LEFT JOIN tbl_mst_proof_engagement as pe ON pe.id = o.proof_engagement_id
		 LEFT JOIN users as u ON u.id = o.created_by
		 LEFT JOIN partners as p ON p.id = o.partner_id
		 WHERE o.is_deleted = 0 AND o.is_opportunity = 1";
	 

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

$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc

$sql .= " ORDER BY o.id ".$columnSortOrder." 
          LIMIT ".$requestData['start']." ,".$requestData['length'];

$query = db_query($sql);

$results = array();
$i = $requestData['start'] + 1;

while($data = db_fetch_array($query)) {

    $nestedData = array();

    $nestedData['id'] = $i;
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
	$nestedData['partner_name'] = $data['partner_name'] ? $data['partner_name'] : 'N/A';

    $nestedData['expected_closure_date'] = 
        $data['expected_closure_date'] 
        ? date('d-m-Y', strtotime($data['expected_closure_date'])) 
        : 'N/A';

    $nestedData['status'] = $data['status'] ? 'Active' : 'Inactive';
	$userType = $_SESSION['user_type'] ?? '';
	$approvalMap = [0 => 'Pending', 1 => 'Approved', 2 => 'Rejected', 3 => 'Onboard'];
	if ($userType === 'MNGR' || $userType === 'USR') {
		$val = isset($approvalMap[$data['is_approved']]) ? $approvalMap[$data['is_approved']] : $data['is_approved'];
		$nestedData['approval'] = $val;
	} else {
		$checked = $data['is_approved'] ? 'checked' : '';
		$nestedData['approval'] = '
			<label class="approval-switch">
				<input type="checkbox"
					class="approval-toggle"
					data-id="'.$data['id'].'"
					'.$checked.'
					onchange="updateApproval(this)">
				<span class="approval-slider"></span>
			</label>
		';
	}

	$nestedData['action'] = '<a href="view_leads.php?eid='.$data['id'].'" class="btn btn-sm btn-info mr-1" title="View Lead"><i class="fa fa-eye"></i></a><a href="add_order.php?eid='.$data['id'].'" class="btn btn-sm btn-primary" title="Edit Lead"><i class="fa fa-edit"></i></a>';
	$nestedData['created_by'] = $data['created_by_name'] ? $data['created_by_name'] : 'N/A';
	$nestedData['created_at'] = $data['created_at'] ? date('d-m-Y H:i:s', strtotime($data['created_at'])) : 'N/A';

	$results[] = $nestedData;
	$i++;
}

$json_data = array(
	"draw"            => intval( $requestData['draw'] ),
	"recordsTotal"    => intval( $totalData ),
	"recordsFiltered" => intval( $totalFiltered ),
	"data"            => $results
);

echo json_encode($json_data);
?>


