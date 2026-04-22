<?php 
include('includes/include.php');
/* Database connection end */
if($_SESSION['user_type']=='USR')
{
	$u_cond=" and o.created_by='".$_SESSION['user_id']."' and o.team_id='".$_SESSION['team_id']."' ";
}
if($_SESSION['user_type']=='MNGR')
{
	$u_cond=" and (o.team_id='".$_SESSION['team_id']."' OR o.allign_team_id='".$_SESSION['team_id']."') ";
}

if($_SESSION['email']=='nitesh.sharma@arkinfo.in')
{
	$inhouse_user=" and (o.team_id in (87,120,119,107,129,128) or o.created_by='".$_SESSION['user_id']."' ) ";
}
 admin_protect();
//  print_r(json_decode($_REQUEST['status']));die;

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

// $stageFtr = json_decode($_REQUEST['school_board']);
// print_r(implode("','",$stageFtr));
// print_r(($stateFtr));
// print_r($requestData['school_board']);
// die;
if($stageFtr)
{
	$dat.=" and o.stage in ('".implode("','",$stageFtr)."')";
}
$substageFtr = json_decode($_REQUEST['sub_stage']);
if($substageFtr)
{
	$dat.=" and o.add_comm in ('".implode("','",$substageFtr)."')";
}
$statusFtr =  json_decode($_REQUEST['status']);
if($statusFtr != '')
{
    $dat.=" and o.status in ('".implode("','",$statusFtr)."')";
}
$school_boardFtr =  json_decode($_REQUEST['school_board']);
if($school_boardFtr != '')
{
    $dat.=" and o.school_board in ('".implode("','",$school_boardFtr)."')";
}
$sub_productFtr =  json_decode($_REQUEST['sub_product']);
if($sub_productFtr != '')
{
	$dat.=" and tlp.product_type_id in ('".implode(",",$sub_productFtr)."')";
}
$usersFtr =  json_decode($_REQUEST['users']);
if($usersFtr != '')
{
	$dat.= " and o.created_by in ('".implode(",",$usersFtr)."')";
}
$statesFtr =  json_decode($_REQUEST['state']);
if($statesFtr != '')
{
	$dat.= " and o.state in (".implode(",",$statesFtr).")";
}
$cityFtr =  json_decode($_REQUEST['city']);
if($cityFtr != '')
{
	$dat.= " and o.city in (".implode(",",$cityFtr).")";
}
$quantityFtr =  json_decode($_REQUEST['quantity']);
if($quantityFtr != ''){
	if (in_array('9', $quantityFtr)) {
		$dat .= ' and (o.quantity in (' . implode(",",$quantityFtr) . ') or o.quantity >=9)';
	} else if (!in_array('9', $quantityFtr) && $quantityFtr != '') {
		$dat .= ' and o.quantity in (' . implode(",",$quantityFtr) . ') ';
	}
}
if($lead_statusFtr != '')
{
    $dat.=" and o.lead_status in ('".implode("','",$lead_statusFtr)."')";
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
$cityFtr =  json_decode($_REQUEST['city']);
if($cityFtr != '')
{
	$dat.= " and o.city in (".implode(",",$cityFtr).")";
}
// getting total number records without any search
$sql = "select o.* ";
$sql.=" FROM orders as o $joinC left join tbl_lead_product as tlp on o.id=tlp.lead_id where o.is_opportunity=0 AND o.agreement_type='Fresh' AND 1=1 " .$dat.$u_cond.$inhouse_user;
// echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "select o.* ";
$sql.=" FROM orders as o $joinC left join tbl_lead_product as tlp on o.id=tlp.lead_id WHERE o.is_opportunity=0 AND o.agreement_type='Fresh' AND 1=1 " .$u_cond.$inhouse_user;
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( o.code LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR o.r_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.quantity LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.school_board LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.school_email LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.contact LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.school_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.eu_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.eu_email LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.status LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.eu_mobile LIKE '%".$requestData['search']['value']."%' )";
	
	//$sql.=" OR eu_mobile LIKE '".$requestData['search']['value']."%' )";
}
$sql.=$dat;
$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query);
$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc // when there is a search parameter then we have to modify total number filtered rows as per search result. 
// print_r($columnName);die;
$sql.=" ORDER BY o.id desc   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
//echo $sql; die;
$query=db_query($sql);

$results = array();
$i=$requestData['start']+1;
while($data=db_fetch_array($query)) {  // preparing an array
 
	$color='#000';
 
	$nestedData=array(); 
	$nestedData['id']=$i;
	$ncdate=strtotime(date('Y-m-d'));
	$closeDate=strtotime($data['close_time']);
	
	// $nestedData['code'] = "<a target='_blank' style='display:block;color:".$color."' href='view_order.php?id=".$data['id']."'>".($data['code']?$data['code']:'N/A').'</a>';
	
	
    $nestedData['r_name'] = "<a target='_blank' style='display:block;color:".$color."' href='view_order.php?id=".$data['id']."'>".preg_replace('/[^A-Za-z0-9\-]/', ' ',$data['r_name']).'</a>';
	
	$nestedData['quantity'] = "<a target='_blank' style='display:block;color:".$color."' href='view_order.php?id=".$data['id']."'>".$data['quantity'].'</a>';

	$nestedData['school_board'] = "<a target='_blank' style='display:block;color:".$color."' href='view_order.php?id=".$data['id']."'>".preg_replace('/[^A-Za-z0-9\-]/', ' ',$data['school_board']).'</a>';

	$nestedData['school_name'] = "<a target='_blank' style='display:block;color:".$color."' href='view_order.php?id=".$data['id']."'>".preg_replace('/[^A-Za-z0-9\-]/', ' ',$data['school_name']).'</a>';
	$nestedData['sub_product'] = getSingleresult("select tp.product_type from tbl_lead_product as tlp left join tbl_product_pivot as tp on tp.id=tlp.product_type_id where tlp.lead_id=" . $data['id']);
	$nestedData['created_date'] = date('d-m-Y',strtotime($data['created_date']));

	$nestedData['lead_status'] = $data['lead_status'] ? $data['lead_status'] : '';
	$nestedData['status'] = $data['status'] ? $data['status'] : 'Pending';
	$ids = "'but" . $data['id'] . "'";
	$nestedData['sub_stage'] = $data['add_comm']?$data['add_comm']:' ';
	$nestedData['stage'] = ($data['stage']?$data['stage']:'N/A').'<a href="javascript:void(0)" title="Change Stage" id=but'.$data['id'].' onclick="stage_change('.$ids.','.$data['id'].')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>';

	$ids2 = "'but2" . $data['id'] . "'";

		   $nestedData['close_date'] = ($data['expected_close_date'] ? date('d-m-Y', strtotime($data['expected_close_date'])) : 'N/A') . '<a href="javascript:void(0)" title="Change Close Date" id=but2' . $data['id'] . ' onclick="cd_change(' . $ids2 . ',' . $data['id'] . ')"> <i style="font-size:18px;color:"' . $color . ';font-weight:" . $bold . "" class="mdi mdi-update"></i></a>';
		//    $nestedData['close_date'] = "<span style='color:" . $color . ";font-weight:" . $bold . "'>" . ($data['expected_close_date'] ? date('d-m-Y', strtotime($data['expected_close_date'])) : 'N/A') . "</span>";

		$nestedData['tag'] = $data['tag'] ? getSingleResult("SELECT name from tag where id=".$data['tag']) : '';
	/* $nestedData[] ="<a style='display:block;color:".$color."'  href='renewal_admin_view.php?id=".$data['id']."'>".(($data['stage']=='Closed Lost')?$data['stage'].'('.$data['add_comment'].')':$data['stage']).'</a>';*/

	$results[] = $nestedData;
	$check_valid = 0;

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
