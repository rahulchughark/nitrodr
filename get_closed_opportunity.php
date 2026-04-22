<?php include('includes/include.php');
include_once('helpers/DataController.php');

$helperData = new DataController();

// print_r($_POST);die;
/* Database connection end */
// admin_page();
$queryy = access_role_permission();
$fetch_query = db_fetch_array($queryy);
if($_SESSION['role'] == 'PARTNER'){
	$vir_cond = " and (o.team_id='".$_SESSION['team_id']."' OR o.allign_team_id='".$_SESSION['team_id']."')";
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
	// $vir_cond = " AND ((COALESCE(o.allign_to, '') = '' AND o.created_by = '".$_SESSION['user_id']."') OR (o.allign_to IS NOT NULL AND o.allign_to != '' AND o.allign_to = '".$_SESSION['user_id']."'))";
	$vir_cond = " AND ((o.created_by = '".$_SESSION['user_id']."') OR (o.allign_to = '".$_SESSION['user_id']."'))";
}else if($_SESSION['user_type'] != 'ADMIN' && $_SESSION['user_type'] != 'SUPERADMIN' && $_SESSION['role'] == 'ISS' && $_SESSION['user_type'] != 'CLR' && $_SESSION['user_type'] != 'RM'){
	$vir_cond = " AND ((o.created_by = '".$_SESSION['user_id']."') OR (o.allign_to = '".$_SESSION['user_id']."'))";
	// $vir_cond = " and o.created_by = ".$_SESSION['user_id'];
}

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
$statesFtr =  json_decode($_REQUEST['state']);
$lead_statusFtr =  json_decode($_REQUEST['lead_status']);
$stageFtr = json_decode($_REQUEST['stage']);
$substageFtr = json_decode($_REQUEST['sub_stage']);
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
	}elseif($requestData['d_type']== 'stage' && $stageFtr && !$substageFtr){
		$joinC = " left join lead_modify_log as l on o.id=l.lead_id ";
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(l.created_date)='".$requestData['d_from']."' and l.modify_name in ('".implode("','",$stageFtr)."') ";	
		} else {
			$dat=" and DATE(l.created_date)>='".$requestData['d_from']."' and DATE(l.created_date)<='".$requestData['d_to']."' and l.modify_name in ('".implode("','",$stageFtr)."') ";	
		}
	}elseif($requestData['d_type']== 'lead_status' && $lead_statusFtr){
		$joinC = " left join lead_modify_log as l on o.id=l.lead_id ";
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and ((DATE(l.created_date)='".$requestData['d_from']."' and l.type='Lead Status' and l.modify_name in ('".implode("','",$lead_statusFtr)."')) || (DATE(o.created_date)='".$requestData['d_from']."')) and o.lead_status in ('".implode("','",$lead_statusFtr)."')";	
		} else {
			$dat=" and ((DATE(l.created_date)>='".$requestData['d_from']."' and DATE(l.created_date)<='".$requestData['d_to']."' and l.type='Lead Status' and l.modify_name in ('".implode("','",$lead_statusFtr)."')) || ( DATE(o.created_date)>='".$requestData['d_from']."' and DATE(o.created_date)<='".$requestData['d_to']."')) and o.lead_status in ('".implode("','",$lead_statusFtr)."')";
		}
	}elseif($requestData['d_type']== 'sub_stage' && $substageFtr){
		$joinC = " left join lead_modify_log as l on o.id=l.lead_id ";
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and ((DATE(l.created_date)='".$requestData['d_from']."' and l.type='Sub Stage' and l.modify_name in ('".$substageFtr."')))";
		} else {
			$dat=" and ((DATE(l.created_date)>='".$requestData['d_from']."' and DATE(l.created_date)<='".$requestData['d_to']."' and l.type='Sub Stage' and l.modify_name in ('".$substageFtr."')))";
		}
	}elseif($requestData['d_type']== 'opportunity_converted'){
      
		$joinC = " left join lead_modify_log as l on o.id=l.lead_id ";
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and ((DATE(l.created_date)='".$requestData['d_from']."' and l.type='Opportunity'))";
		} else {
			$dat=" and ((DATE(l.created_date)>='".$requestData['d_from']."' and DATE(l.created_date)<='".$requestData['d_to']."' and l.type='Opportunity'))";
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

$sourceFtr =  json_decode($_REQUEST['source']);
if($sourceFtr != '')
{
	$dat.= " and o.source in ('".implode("','",$sourceFtr)."')";
}
if($stageFtr && !$requestData['d_type']== 'sub_stage' && !$requestData['d_type']== 'stage')
{
	if($stageFtr[0] == 'Blank'){
		$dat.=" and (o.stage='' || o.stage is null)";
	}else{
		$dat.=" and o.stage in ('".implode("','",$stageFtr)."')";
	}
}

// && $requestData['d_type'] != "sub_stage"
if($substageFtr && !$requestData['d_type']== 'sub_stage' && !$requestData['d_type']== 'stage')
{
	$dat.=" and o.add_comm in ('".$substageFtr."')";
}
$partnerFtr =  json_decode($_REQUEST['partner']);
$usersFtr =  json_decode($_REQUEST['users']);
if($partnerFtr != '' && !$usersFtr)
{
	$teamUsers = db_query("SELECT id from users where team_id in (".implode(',',$partnerFtr).")");
	$usrrArr = [];
	while ($usr = db_fetch_array($teamUsers)){
		$usrrArr[] = $usr['id'];
	}
	$dat.=" AND ((o.created_by IN (" . implode(",", $usrrArr) . ")) OR (o.allign_to IN (" . implode(",", $usrrArr) . ")))";
}
if($requestData['just_partner'] == 'Yes'){
	$dat.=" AND o.team_id in (".implode(',',$partnerFtr).")	";
}
if($usersFtr != '')
{
	// $dat.= " and o.created_by in (".implode(",",$usersFtr).")";
	// $dat.= " AND ((COALESCE(o.allign_to, '') = '' AND o.created_by in (".implode(",",$usersFtr).") OR (o.allign_to IS NOT NULL AND o.allign_to != '' AND o.allign_to in (".implode(",",$usersFtr)."))))";
	$dat.=" AND ((o.created_by IN (" . implode(",", $usersFtr) . ")) OR (o.allign_to IN (" . implode(",", $usersFtr) . ")))";
}
if($statesFtr != '')
{
	$dat.= " and o.state in (".implode(",",$statesFtr).")";
}
$cityFtr =  json_decode($_REQUEST['city']);
if($cityFtr != '')
{
	$dat.= " and o.city in (".implode(",",$cityFtr).")";
}
if($lead_statusFtr != '')
{
    $dat.=" and o.lead_status in ('".implode("','",$lead_statusFtr)."')";
}
$school_boardFtr =  json_decode($_REQUEST['school_board']);
if($school_boardFtr != '')
{
    $dat.=" and o.school_board in ('".implode("','",$school_boardFtr)."')";
}

$status_FTR =  json_decode($_REQUEST['status']);
if($status_FTR != '')
{
    $dat.=" and o.status in ('".implode("','",$status_FTR)."')";
}

$tagFtr = json_decode($_REQUEST['tag']);
if($tagFtr)
{
	$dat.=" and o.tag in ('".implode("','",$tagFtr)."')";
}
$statesFtr =  json_decode($_REQUEST['state']);
if($statesFtr != '')
{
	$dat.= " and o.state in (".implode(",",$statesFtr).")";
}
if($requestData['type'] == 'Renewal')
{
	$Type=" and o.agreement_type='Renewal'";	
}else if($requestData['type'] == 'total')
{

}else {
	$Type=" and o.agreement_type='Fresh'";	
}
$productFtr =  json_decode($_REQUEST['product']);
if($productFtr)
{
	$joinC.= " left join tbl_lead_product_opportunity as t on t.lead_id=o.id";
	$dat.= " and t.product in (".implode(",",$productFtr).") and t.status=1 ";
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

// getting total number records without any search
// print_r($_REQUEST); die;
$sql = "select o.* ";
$sql.=" FROM orders as o $joinC where o.is_opportunity=1 and o.stage in ('PO/CIF Issued') ".$vir_cond.$Type;

$sql.=" GROUP BY o.id";

$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$sql = "select o.* ";
$sql.=" FROM orders as o $joinC WHERE o.is_opportunity=1 and o.stage in ('PO/CIF Issued') ";

if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( o.code LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR o.r_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.quantity LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.school_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.eu_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.eu_email LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.group_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.contact LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.eu_mobile LIKE '%".$requestData['search']['value']."%' )";
	//$sql.=" OR eu_landline LIKE '".$requestData['search']['value']."%' )";	
	//$sql.=" OR eu_mobile LIKE '".$requestData['search']['value']."%' )";
}

$sql.=$dat.$Type.$vir_cond;
$sql.=" GROUP BY o.id";
// print_r($sql);die;

$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc

$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY o.id ".$columnSortOrder."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
// echo $sql; die;
$query=db_query($sql);

$results = array();
$i=$requestData['start']+1;
while($data=db_fetch_array($query)) {  // preparing an array
	$color='#000';
	$nestedData=array(); 
	$nestedData['id']=$i;
	$ncdate=strtotime(date('Y-m-d'));
	$closeDate=strtotime($data['close_time']);
	
	$nestedData['r_name'] = "<a style='display:block;color:".$color."' href='view_opportunity.php?id=".$data['id']."'>".preg_replace('/[^A-Za-z0-9\-]/', ' ',$data['r_name']).'</a>';
	$products = array();
	$quantitys = 0;
	$mainProId = array();
	$proquery = db_query("SELECT * FROM tbl_lead_product_opportunity where status=1 and lead_id =" . $data['id']);
	$count = mysqli_num_rows($proquery);
	if ($count) {
		while ($data_n = db_fetch_array($proquery)) { 
			if (!in_array($data_n['main_product_id'], $mainProId)) {
				$mainProId[] = $data_n['main_product_id'];
				$ppro = getSingleresult("SELECT name FROM tbl_main_product_opportunity where id=".$data_n['main_product_id']);
				if(!empty($ppro)){
					$products[] = $ppro;
				}
			}
			$quantitys = $quantitys+$data_n['quantity'];
		}
	}
    $nestedData['product'] = $products ? (count($products) > 1 ? implode(' + ',$products) : $products[0]) : '';
	// print_r($nestedData['product']);die;
	$nestedData['quantity'] = $quantitys;
	$nestedData['grand_total'] = $data['grand_total_price'];
	$nestedData['school_board'] = "<a style='display:block;color:".$color."' href='view_opportunity.php?id=".$data['id']."'>".preg_replace('/[^A-Za-z0-9\-]/', ' ',$data['school_board']).'</a>';

	$nestedData['school_name'] = "<a style='display:block;color:".$color."' href='view_opportunity.php?id=".$data['id']."'>".preg_replace('/[^A-Za-z0-9\-]/', ' ',$data['school_name']).'</a>';

	$nestedData['created_date'] = date('d-m-Y h:i:s',strtotime($data['created_date']));
			
	$ids = "'but" . $data['id'] . "'";
    $nestedData['stage'] = ($data['stage']?$data['stage']:'N/A').(($fetch_query['edit_stage'] == 1) ? '<a href="javascript:void(0)" title="Change Stage" id=but'.$data['id'].' onclick="stage_change('.$ids.','.$data['id'].')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>' : '');

	$nestedData['status'] = ($data['lead_status']?$data['lead_status']:'N/A').(($fetch_query['edit_status'] == 1) ? '<a href="javascript:void(0)" title="Change Status" id=but'.$data['id'].' onclick="status_change('.$ids.','.$data['id'].')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>' : '');
	$nestedData['qualified_status'] = ($data['status']?$data['status']:'N/A');
	$nestedData['sub_stage'] = isset($data['add_comm']) ? $data['add_comm'] : 'NA';
	$nestedData['city'] = isset($data['city']) && $data['city'] ? getSingleresult("SELECT city FROM cities where id=".$data['city']) : '';
	$nestedData['state'] = isset($data['state']) && $data['state'] ? getSingleresult("SELECT name FROM states where id=".$data['state']) : '';
    // $nestedData['close_date'] = $data['expected_close_date'] ? date('d-m-Y', strtotime($data['expected_close_date'])) : 'N/A';
	$ids2 = "'but2" . $data['id'] . "'";

		   $nestedData['close_date'] = ($data['expected_close_date'] ? date('d-m-Y', strtotime($data['expected_close_date'])) : 'N/A') . (($fetch_query['edit_date'] == 1) ? '<a href="javascript:void(0)" title="Change Close Date" id=but2' . $data['id'] . ' onclick="cd_change(' . $ids2 . ',' . $data['id'] . ')"> <i style="font-size:18px;color:"' . $color . ';font-weight:" . $bold . "" class="mdi mdi-update"></i></a>' : '');
            $sstage_sql = db_query("select name,id from sub_stage where stage_name='demo'");
            while ($subStageHeader = db_fetch_array($sstage_sql)) {
                $nestedData[strtolower(str_replace(' ','_',$subStageHeader['name']))] = $helperData->getDataValues($data['id'],$subStageHeader['name']).(($fetch_query['edit_date'] == 1) ? '<a  href="javascript:void(0)" title="Change Close Date" id=demo'.$data['id'].$subStageHeader['id'].' onclick="demo_sub_stage_date(' . $data['id'] . ','.$subStageHeader['id'].')"> <i style="font-size:18px;color:"' . $color . ';font-weight:" . $bold . "" class="mdi mdi-update"></i></a>' : '');
            }
            // $nestedData['demo_login'] =  db_fetch_array($sstage_sql));
			$results[] = $nestedData;
			unset($products);
			unset($quantitys);
			$check='';
		$i++;
}
//print_r($results); die;
 

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $results   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>

