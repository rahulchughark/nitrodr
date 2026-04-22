<?php include('includes/include.php');
/* Database connection end */
// print_r($_REQUEST);die;
if ($_SESSION['sales_manager'] == 1) {
	$vir_cond = " and team_id in (" . $_SESSION['access'] . ") ";
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
	$vir_cond = " and team_id = ".$_SESSION['team_id'];
}else if($_SESSION['user_type'] == 'TEAM LEADER'){
	$callesIds = getSingleResult("SELECT caller from users where id=".$_SESSION['user_id']);
	$callesIdsA = explode(",", $callesIds);
	foreach ($callesIdsA as $value) {
	
	$calleruidQ = getSingleResult("select user_id from callers where id=".$value);
	$caller_userId[]=$calleruidQ;
	}
	$callesIdsForQ = implode(",", $caller_userId);
	$vir_cond = " and created_by in (".$callesIdsForQ.")";
}else if($_SESSION['user_type'] != 'ADMIN' && $_SESSION['user_type'] != 'SUPERADMIN' && $_SESSION['role'] == 'ISS'){
	$vir_cond = " and created_by = ".$_SESSION['user_id'];
}

$queryy = access_role_permission();
$fetch_query = db_fetch_array($queryy);

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
if($requestData['d_from'] && $requestData['d_to'])
{
	if($requestData['d_type']== 'close'){
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(expected_close_date)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(expected_close_date)>='".$requestData['d_from']."' and DATE(expected_close_date)<='".$requestData['d_to']."'";	
		} 
	}elseif($requestData['d_type']== 'actioned_date'){
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(approval_time)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(approval_time)>='".$requestData['d_from']."' and DATE(approval_time)<='".$requestData['d_to']."'";	
		}
	}else{
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(created_date)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(created_date)>='".$requestData['d_from']."' and DATE(created_date)<='".$requestData['d_to']."'";	
		}
	}
}
 
// print_r();
// print_r(($stageFtr));
// print_r($requestData['school_board']);
// die;
$stageFtr = json_decode($_REQUEST['stage']);
if($stageFtr)
{
	$dat.=" and stage in ('".implode("','",$stageFtr)."')";
}
$statusFtr =  json_decode($_REQUEST['status']);
if($statusFtr != '')
{
    $dat.=" and status in ('".implode("','",$statusFtr)."')";
}
$partnerFtr =  json_decode($_REQUEST['partner']);
if($partnerFtr != '')
{
    $dat.=" and team_id in ('".implode("','",$partnerFtr)."')";
}
$school_boardFtr =  json_decode($_REQUEST['school_board']);
if($school_boardFtr != '')
{
    $dat.=" and school_board in ('".implode("','",$school_boardFtr)."')";
}
$usersFtr =  json_decode($_REQUEST['users']);
if($usersFtr != '')
{
	$dat.= " and created_by in (".implode(",",$usersFtr).")";
}
$statesFtr =  json_decode($_REQUEST['state']);
if($statesFtr != '')
{
	$dat.= " and state in (".implode(",",$statesFtr).")";
}
 
// getting total number records without any search
$sql = "SELECT * ";
$sql.=" FROM lead_generation";
// $sql.=" FROM orders where is_opportunity=0 AND 1=1 ".$dat.$vir_cond;
// echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$sql = "SELECT * ";
$sql.=" FROM lead_generation";
// $sql.=" FROM orders WHERE is_opportunity=0 AND 1=1 ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" WHERE (schoolName LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR schoolAddress LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR operationalBoards LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR city LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR postalCode LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR contactNo LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR source LIKE '%".$requestData['search']['value']."%' )";
}
$sql.=$dat.$vir_cond;
//echo $sql; die;

$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc



$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY id ".$columnSortOrder."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
// echo $sql; die;
$query=db_query($sql);

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
	
	$nestedData['schoolName'] = "<a style='display:block;color:".$color."' href='view-lead-generation.php?id=".$data['id']."'>".$data['schoolName'].'</a>';
	
	$nestedData['operationalBoards'] = "<a style='display:block;color:".$color."' href='view-lead-generation.php?id=".$data['id']."'>".$data['operationalBoards'].'</a>';

	$nestedData['schoolAddress'] = "<a style='display:block;color:".$color."' href='view-lead-generation.php?id=".$data['id']."'>".$data['schoolAddress'].'</a>';

    $nestedData['city'] = "<a style='display:block;color:".$color."' href='view-lead-generation.php?id=".$data['id']."'>".$data['city'].'</a>';

    $nestedData['postalCode'] = "<a style='display:block;color:".$color."' href='view-lead-generation.php?id=".$data['id']."'>".$data['postalCode'].'</a>';

    $nestedData['contactNo'] = "<a style='display:block;color:".$color."' href='view-lead-generation.php?id=".$data['id']."'>".$data['contactNo'].'</a>';

    $nestedData['email'] = "<a style='display:block;color:".$color."' href='view-lead-generation.php?id=".$data['id']."'>".$data['E-mail-ID'].'</a>';

    $nestedData['annualFees'] = "<a style='display:block;color:".$color."' href='view-lead-generation.php?id=".$data['id']."'>".$data['Annual_Fees'].'</a>';

    $nestedData['source'] = "<a style='display:block;color:".$color."' href='view-lead-generation.php?id=".$data['id']."'>".$data['Source'].'</a>';
		 
			$results[] = $nestedData;
			$check='';
		$i++; 
}
$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $results   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>

