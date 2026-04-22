<?php 
include('includes/include.php');
/* Database connection end */


$u_cond = '';
$onlyMyCreated = (isset($_REQUEST['only_my_created']) && $_REQUEST['only_my_created'] == '1');
if($onlyMyCreated)
{
	if(($_SESSION['user_type'] ?? '') == 'SALES MNGR') {
		$userId = (int)($_SESSION['user_id'] ?? 0);
		$sessionTeamId = $userId > 0 ? (int)getSingleresult("select team_id from users where id='".$userId."'") : 0;
		if($sessionTeamId > 0) {
			$u_cond = " and o.created_by in (select id from users where team_id='".$sessionTeamId."') ";
		} else {
			$u_cond = " and o.created_by='".$_SESSION['user_id']."' ";
		}
	} else {
		$u_cond=" and o.created_by='".$_SESSION['user_id']."' ";
	}
}
elseif($_SESSION['user_type']=='USR')
{
	$u_cond=" and o.created_by='".$_SESSION['user_id']."' ";
}


 admin_protect();

$queryy = access_role_permission();
$fetch_query = db_fetch_array($queryy);

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
$listFormat = isset($requestData['list_format']) ? $requestData['list_format'] : '';
$stageFtr = json_decode($_REQUEST['stage']);
$lead_statusFtr =  json_decode($_REQUEST['lead_status']);

$dat = "";
if($requestData['d_from'] && $requestData['d_to'])
{
	if($requestData['d_type']== 'close'){
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(o.expected_closure_date)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(o.expected_closure_date)>='".$requestData['d_from']."' and DATE(o.expected_closure_date)<='".$requestData['d_to']."'";	
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

if($stageFtr)
{
	$dat.=" and o.stage_id in ('".implode("','",$stageFtr)."')";
}

$statusFtr =  json_decode($_REQUEST['status']);
if($statusFtr != '')
{
    $dat.=" and o.status in ('".implode("','",$statusFtr)."')";
}

$usersFtr =  json_decode($_REQUEST['users']);
if($usersFtr != '')
{
	$dat.= " and o.created_by in ('".implode(",",$usersFtr)."')";
}
$statesFtr =  json_decode($_REQUEST['state']);
if($statesFtr != '')
{
	$dat.= " and o.state_id in (".implode(",",$statesFtr).")";
}
$cityFtr =  json_decode($_REQUEST['city']);
if($cityFtr != '')
{
	$dat.= " and o.city_id in (".implode(",",$cityFtr).")";
}
$quantityFtr =  json_decode($_REQUEST['quantity']);
if($quantityFtr != ''){
	if (in_array('9', $quantityFtr)) {
		$dat .= ' and (o.number_of_licenses in (' . implode(",",$quantityFtr) . ') or o.number_of_licenses >=9)';
	} else if (!in_array('9', $quantityFtr) && $quantityFtr != '') {
		$dat .= ' and o.number_of_licenses in (' . implode(",",$quantityFtr) . ') ';
	}
}

// getting total number records without any search
$sql = "select o.* FROM orders as o where o.is_deleted=0 " .$dat.$u_cond.$inhouse_user;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData; 

$sql = "select o.* FROM orders as o WHERE o.is_deleted=0 " .$u_cond.$inhouse_user;
if( !empty($requestData['search']['value']) ) {   
	$sql.=" AND ( o.customer_company_name LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR o.customer_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.email LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.phone LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.product LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.designation LIKE '%".$requestData['search']['value']."%' )";
}
$sql.=$dat;
$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query);
$columnIndex = $requestData['order'][0]['column']; 
$columnName = $requestData['columns'][$columnIndex]['data']; 
$columnSortOrder = $requestData['order'][0]['dir']; 

$sql.=" ORDER BY o.id desc LIMIT ".$requestData['start']." ,".$requestData['length']." ";
$query=db_query($sql);

$results = array();
$i=$requestData['start']+1;
while($data=db_fetch_array($query)) {  
 
	$nestedData=array(); 
	$nestedData['id']='#'.$data['id'];

	if($listFormat === 'admin') {
		$nestedData['company_name'] = $data['customer_company_name'];
		$nestedData['customer_name'] = $data['customer_name'];
		$nestedData['email'] = $data['email'];
		$nestedData['phone'] = $data['phone'];
		$nestedData['designation'] = $data['designation'];
		$nestedData['product'] = $data['product'];
		$nestedData['licenses'] = $data['number_of_licenses'];

		$subscriptionTermRaw = strtolower(trim((string)$data['subscription_term']));
		$nestedData['subscription_term'] = ($data['subscription_term'] == 1 || in_array($subscriptionTermRaw, array('1 year', '1 years'))) ? '1 Year' : (($data['subscription_term'] == 3 || in_array($subscriptionTermRaw, array('3 year', '3 years'))) ? '3 Year' : '');

		$stage_name = getSingleresult("SELECT name FROM tbl_mst_stage WHERE id='".$data['stage_id']."'");
		$nestedData['stage_id'] = $stage_name ? $stage_name : '';

		$proof_name = getSingleresult("SELECT name FROM tbl_mst_proof_engagement WHERE id='".$data['proof_engagement_id']."'");
		$nestedData['proof_engagement_id'] = $proof_name ? $proof_name : '';

		$nestedData['expiry_date'] = 'N/A';
		$nestedData['expire_in'] = 'N/A';
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
		$approvalState = (int)$data['is_approved'];
		$approvalMap = [
			0 => ['label' => 'Pending', 'class' => 'pending'],
			1 => ['label' => 'Approve', 'class' => 'approved'],
			2 => ['label' => 'Reject', 'class' => 'rejected'],
			3 => ['label' => 'Onboard', 'class' => 'onboard']
		];
		$approvalData = isset($approvalMap[$approvalState]) ? $approvalMap[$approvalState] : $approvalMap[0];
		$nestedData['approval'] = '<span class="approval-badge '.$approvalData['class'].'">'.$approvalData['label'].'</span>';
		$nestedData['created_at'] = $data['created_at'] ? date('d-m-Y h:i:s', strtotime($data['created_at'])) : '';
		if(($_SESSION['user_type'] ?? '') === 'SALES MNGR') {
			$nestedData['action'] = '<a href="view_leads.php?eid='.$data['id'].'" class="btn btn-sm btn-info mr-1" title="View Lead"><i class="fa fa-eye"></i></a>';
		} else {
			$nestedData['action'] = '<a href="view_leads.php?eid='.$data['id'].'" class="btn btn-sm btn-info mr-1" title="View Lead"><i class="fa fa-eye"></i></a><a href="edit_order.php?eid='.$data['id'].'" class="btn btn-sm btn-primary" title="Edit Lead"><i class="fa fa-edit"></i></a>';
		}
	} else {
		$nestedData['r_name'] = "<a target='_blank' style='display:block;color:#000' href='view_order.php?id=".$data['id']."'>".preg_replace('/[^A-Za-z0-9\-]/', ' ',$data['customer_name']).'</a>';

		$nestedData['quantity'] = "<a target='_blank' style='display:block;color:#000' href='view_order.php?id=".$data['id']."'>".$data['number_of_licenses'].'</a>';

		$nestedData['school_name'] = "<a target='_blank' style='display:block;color:#000' href='view_order.php?id=".$data['id']."'>".preg_replace('/[^A-Za-z0-9\-]/', ' ',$data['customer_company_name']).'</a>';

		$nestedData['created_date'] = $data['created_at'] ? date('d-m-Y',strtotime($data['created_at'])) : '';

		$nestedData['status'] = $data['status'] ? 'Active' : 'Pending';
		$ids = "'but" . $data['id'] . "'";

		$nestedData['approval'] = $data['is_approved'] ? 'Approved' : 'Pending';

		$stage_name = getSingleresult("SELECT name FROM tbl_mst_stage WHERE id='".$data['stage_id']."'");
		$nestedData['stage'] = ($stage_name?$stage_name:'N/A').'<a href="javascript:void(0)" title="Change Stage" id=but'.$data['id'].' onclick="stage_change('.$ids.','.$data['id'].')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>';

		$ids2 = "'but2" . $data['id'] . "'";
		$nestedData['close_date'] = ($data['expected_closure_date'] ? date('d-m-Y', strtotime($data['expected_closure_date'])) : 'N/A') . '<a href="javascript:void(0)" title="Change Close Date" id=but2' . $data['id'] . ' onclick="cd_change(' . $ids2 . ',' . $data['id'] . ')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>';
	}

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
