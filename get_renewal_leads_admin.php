<?php include('includes/include.php');
/* Database connection end */
include_once('helpers/DataController.php');


$helperData = new DataController();

$requestData= $_REQUEST;
if($requestData['fin_year']){
	$fy_year = $requestData['fin_year'];
}else{
	$currentMonth = date('n');
	$currentYear = date('Y');	
	if ($currentMonth >= 4) {
		$fy_year = $currentYear;
	} else {
		$fy_year = $currentYear - 1;
	}
}
if ($_SESSION['sales_manager'] == 1) {
	$vir_cond = " and o.team_id in (" . $_SESSION['access'] . ") ";
}

if($_SESSION['role'] == 'PARTNER'){
	$vir_cond = " and team_id = ".$_SESSION['team_id'];
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
	$vir_cond = " and created_by in (".$callesIdsForQ.")";
}else if($_SESSION['user_type'] != 'ADMIN' && $_SESSION['user_type'] != 'SUPERADMIN' && $_SESSION['role'] == 'ISS' && $_SESSION['user_type'] != 'RM'){
	// $vir_cond = " and created_by = ".$_SESSION['user_id'];
	$vir_cond = " AND ((created_by = '".$_SESSION['user_id']."') OR (allign_to = '".$_SESSION['user_id']."'))";
}

if ($_SESSION['sales_manager'] == 1) {
	$region_access=getSingleresult("select region_access from users where id='".$_SESSION['user_id']."'");
	if($region_access) { $regions=explode(',',$region_access);
	$search_region=array();
	foreach($regions as $region)
	{
		$search_region[]="'".$region."'";
	}
	$vir_cond .= " and o.region in (" . implode(",",$search_region) . ") ";
}
}

// print_r($_POST);die;
// storing  request (ie, get/post) global array to a variable

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
	}elseif($requestData['d_type']== 'sub_stage' && $substageFtr){
		$joinC = " left join lead_modify_log as l on o.id=l.lead_id ";
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and ((DATE(l.created_date)='".$requestData['d_from']."' and l.type='Sub Stage' and l.modify_name in ('".implode("','",$substageFtr)."')))";
		} else {
			$dat=" and ((DATE(l.created_date)>='".$requestData['d_from']."' and DATE(l.created_date)<='".$requestData['d_to']."' and l.type='Sub Stage' and l.modify_name in ('".implode("','",$substageFtr)."')))";
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

// if($requestData['upsellreport'] == '1' || $requestData['upsellreport'] == '0')
// {
// 	$dat.= " and t.upsell='".$requestData['upsellreport']."'";
// }

$isUpsellReport = $_POST['upsellreport'] != 'null' ? (bool)$_POST['upsellreport'] : false;


$stageFtr = json_decode($_REQUEST['stage']);
if($stageFtr && !$isUpsellReport)
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

$statusFtr =  json_decode($_REQUEST['status']);
if($statusFtr != '')
{
    $dat.=" and o.status in ('".implode("','",$statusFtr)."')";
}
// && $requestData['d_type'] != "sub_stage"
if($substageFtr)
{
	$dat.=" and o.add_comm in ('".implode("','",$substageFtr)."')";
}
$lead_statusFtr =  json_decode($_REQUEST['lead_status']);
if($lead_statusFtr != '')
{
    $dat.=" and o.lead_status in ('".implode("','",$lead_statusFtr)."')";
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
$school_boardFtr =  json_decode($_REQUEST['school_board']);
if($school_boardFtr != '')
{
    $dat.=" and o.school_board in ('".implode("','",$school_boardFtr)."')";
}

if($requestData['just_partner'] == 'Yes'){
	$dat.=" AND o.team_id in (".implode(',',$partnerFtr).")	";
}
$tagFtr = json_decode($_REQUEST['tag']);
if($tagFtr)
{
	$dat.=" and o.tag in ('".implode("','",$tagFtr)."')";
}

$sourceFtr =  json_decode($_REQUEST['source']);
if($sourceFtr != '')
{
	$dat.= " and o.source in ('".implode("','",$sourceFtr)."')";
}


$sub_productFtr =  json_decode($_REQUEST['sub_product']);
if($sub_productFtr != '')
{
	$dat.=" and tlp.product_type_id in (".implode(",",$sub_productFtr).")";
}
if($usersFtr != '')
{
	$dat.=" AND ((o.created_by IN (" . implode(",", $usersFtr) . ")) OR (o.allign_to IN (" . implode(",", $usersFtr) . ")))";
}
$productFtr =  json_decode($_REQUEST['product']);
if($productFtr)
{
	$dat.= " and t.product in (".implode(",",$productFtr).") and t.status!=0 ";
}
$product =  json_decode($_REQUEST['productDS']);
if($product != '')
{
    $dat.=" and tlp.product_id in ('".implode("','",$product)."')";
}
$product_type =  json_decode($_REQUEST['product_typeDS']);
if($product_type != '')
{
    $dat.=" and tlp.product_type_id in ('".implode("','",$product_type)."')";
} 
$product_opp =  json_decode($_REQUEST['product_opp']);
if($product_opp != '' && $product_opp != null && $product_opp != 1)
{
    $dat.=" and t.main_product_id in (8) ";
}elseif ($product_opp == 1) {
    $dat.=" and t.main_product_id in (1,2,3) ";
}

$product_opp_type =  json_decode($_REQUEST['product_opp_type']);
$type =  json_decode($_REQUEST['type']);
if($product_opp_type != '')
{
    $dat.=" and t.product=".$product_opp_type;
}
if($productFtr || $product_opp || $product_opp_type || $type == 'Renewal' || $requestData['upsell'])
{
	$joinC.= " left join tbl_lead_product_opportunity as t on t.lead_id=o.id";
}
if($productFtr || $product_opp || $product_opp_type || $type == 'Renewal')
{
	$dat.=" and t.status!=0  AND t.financial_year_start = '$fy_year'";
}

if (!empty($requestData['upsell'])) {	
    $stageFtr = $stageFtr[0] ?? null;

    if ($stageFtr) {
        $stageId = $helperData->getStageIdByName($stageFtr);	
        $dat .= " AND t.upsell = '".$requestData['upsell']."' AND t.stage = '".$stageId."'";
    } else {
        $dat .= " AND t.upsell = '".$requestData['upsell']."'";
    }
}

if (isset($requestData['isupsell']) && $requestData['isupsell'] != '') {
    $joinC .= " LEFT JOIN tbl_lead_product_opportunity t ON t.lead_id = o.id";

    if ($requestData['isupsell'] === 'Yes') {
        $dat .= " AND t.upsell = 1";
    } elseif ($requestData['isupsell'] === 'No') {
        $dat .= " AND t.upsell = 0";
    }
}


$mainProduct = json_decode($_REQUEST['main_product']);
$subProductMain = json_decode($_REQUEST['sub_product_data']);

if ($mainProduct) {
    $pID = implode(',', $mainProduct);
	$subProductMain = $subProductMain ? implode(',',$subProductMain) : '';

    if ($pID) {
        $dat .= " AND EXISTS (
            SELECT 1 
            FROM tbl_lead_product_opportunity lpo
            WHERE lpo.lead_id = o.id 
              AND lpo.main_product_id IN ($pID)
        )";
    }


	if ($subProductMain) {
        $dat .= " AND EXISTS (
            SELECT 1 
            FROM tbl_lead_product_opportunity lpo
            WHERE lpo.lead_id = o.id 
              AND lpo.product IN ($subProductMain)
        )";
    }
    
}




// getting total number records without any search
$sql = "select o.* ";
$sql.=" FROM orders as o ".$joinC." left join tbl_lead_product as tlp on o.id=tlp.lead_id where o.agreement_type='Renewal' and o.is_opportunity=1 ".$dat.$vir_cond;

if($productFtr || $product_opp || $product_opp_type || $type == 'Renewal'){
	$sql.=" GROUP BY o.id";
}
// echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$sql = "select o.* ";
$sql.=" FROM orders as o ".$joinC." left join tbl_lead_product as tlp on o.id=tlp.lead_id WHERE o.agreement_type='Renewal' and o.is_opportunity=1 ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( o.code LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR o.r_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.quantity LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.school_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.eu_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.eu_email LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.status LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.group_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.contact LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.eu_mobile LIKE '%".$requestData['search']['value']."%' )";
	//$sql.=" OR eu_landline LIKE '".$requestData['search']['value']."%' )";
	
	//$sql.=" OR eu_mobile LIKE '".$requestData['search']['value']."%' )";
}
$sql.=$dat.$vir_cond;
// echo $sql; die;

$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc


if($productFtr || $product_opp || $product_opp_type || $type == 'Renewal'){
	$sql.=" GROUP BY o.id";
}
$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
if($productFtr || $product_opp || $product_opp_type || $type == 'Renewal'){
	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
}else{
	$sql.=" GROUP BY o.id  ORDER BY o.created_date desc LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
}
// echo $sql; die;
$query=db_query($sql);
$queryy = access_role_permission();
$fetch_query = db_fetch_array($queryy);
$results = array();
$i=$requestData['start']+1;
while($data=db_fetch_array($query)) {  // preparing an array
if($data['sfdc_exp']==1 && $_SESSION['sales_manager']!=1)
{
	$color='#225da8';
}
else
{
	$color='#000';
}
	$nestedData=array(); 
	$nestedData['id']=$i;
	$ncdate=strtotime(date('Y-m-d'));
	$closeDate=strtotime($data['close_time']);
	
	$products = array();
	$quantitys = 0;
	$mainProId = array();
	$proquery = db_query("SELECT * FROM tbl_lead_product_opportunity where status=1 and lead_id =" . $data['id']);
	$count = mysqli_num_rows($proquery);
	if ($count) {
		while ($data_n = db_fetch_array($proquery)) { 
			if (!in_array($data_n['main_product_id'], $mainProId)) {
				$mainProId[] = $data_n['main_product_id'];
				$ppro = $data_n['main_product_id'] ? getSingleresult("SELECT name FROM tbl_main_product_opportunity where id=".$data_n['main_product_id']) : '';
				if(!empty($ppro)){
					$products[] = $ppro;
				}
			}
			$quantitys = $quantitys+$data_n['quantity'];
		}
	}
	// print_r($mainProId);die;
    $nestedData['product'] = $products ? implode(' + ',$products) : '';
	$nestedData['quantity'] = $quantitys;
	$nestedData['grand_total'] = $data['grand_total_price'];	
	$nestedData['r_name'] = "<a style='display:block;color:".$color."' href='view_renewal_opportunity.php?id=".$data['id']."'>".$data['r_name'].'</a>';
	$nestedData['school_board'] = "<a style='display:block;color:".$color."' href='view_renewal_opportunity.php?id=".$data['id']."'>".$data['school_board'].'</a>';
	$nestedData['school_name'] = "<a style='display:block;color:".$color."' href='view_renewal_opportunity.php?id=".$data['id']."'>".$data['school_name'].'</a>';
	$nestedData['created_date'] = date('d-m-Y h:i:s',strtotime($data['created_date']));

    if($data['status']=='Approved')
		{	
			$nestedData['qualified_status']='<span style="color:green">Qualified</span> ';
		
		}
		else if($data['status']=='Cancelled')
		{
			$nestedData['qualified_status']='<span class="text-danger">Unqualified('.$data['reason'].')</span>';
			
		}
		else if($data['status']=='Pending')
			
			{
				$nestedData['qualified_status']= 'Pending';
			}
			else if($data['status']=='Undervalidation')
			{
				$nestedData['qualified_status']= '<span class="text-warning">Re-Submission Required</span>';
			}
			else if($data['status']=='On-Hold')
			{
				$nestedData['qualified_status']= '<span class="text-blue">On-Hold</span>';
			}
			else if($data['status']=='For Validation')
			{
				$nestedData['qualified_status']= '<span class="text-themecolor">For Validation</span>';
			}
			else 
			{
				$nestedData['qualified_status']= '<span class="text-warning">'.$data['status'].'</span>';
			}
		

			// if($data['status'] == 'Approved'){
				$ids="'but".$data['id']."'";
				$nestedData['stage'] = ($data['stage']?$data['stage']:'N/A').(($fetch_query['edit_stage'] == 1) ? '<a href="javascript:void(0)" title="Change Stage" id=but'.$data['id'].' onclick="stage_change('.$ids.','.$data['id'].')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>' : '');
		//    }else{
		// 	   $nestedData['stage'] = '';
   
		//    }
           $nestedData['status'] = ($data['lead_status']?$data['lead_status']:'N/A');
           $nestedData['sub_stage'] = isset($data['add_comm']) ? $data['add_comm'] : 'NA';
           $sstage_sql = db_query("select name from sub_stage where stage_name='demo'");
           while ($subStageHeader = db_fetch_array($sstage_sql)) {
            $nestedData[strtolower(str_replace(' ','_',$subStageHeader['name']))] = $helperData->getDataValues($data['id'],$subStageHeader['name']);
           }


		   $nestedData['close_date'] = $data['expected_close_date'];
			 
			$results[] = $nestedData;
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

