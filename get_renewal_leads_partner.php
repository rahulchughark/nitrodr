<?php include('includes/include.php');

include_once('helpers/DataController.php');

$helperData = new DataController();

/* Database connection end */
if($_SESSION['user_type']=='USR')
{
	$u_cond=" and o.created_by='".$_SESSION['user_id']."' and o.team_id='".$_SESSION['team_id']."' ";
}
if($_SESSION['user_type']=='MNGR')
{
	$u_cond=" and o.team_id='".$_SESSION['team_id']."' ";
}
 admin_protect();
 
// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
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
 
$sourceFtr =  json_decode($_REQUEST['source']);
if($sourceFtr != '')
{
	$dat.= " and o.source in ('".implode("','",$sourceFtr)."')";
}
$stageFtr = json_decode($_REQUEST['stage']);
if($stageFtr)
{
	$dat.=" and o.stage in ('".implode("','",$stageFtr)."')";
}
$statusFtr =  json_decode($_REQUEST['status']);
if($statusFtr != '')
{
    $dat.=" and o.status in ('".implode("','",$statusFtr)."')";
}
$partnerFtr =  json_decode($_REQUEST['partner']);
if($partnerFtr != '')
{
    $dat.=" and o.team_id in ('".implode("','",$partnerFtr)."')";
}
$school_boardFtr =  json_decode($_REQUEST['school_board']);
if($school_boardFtr != '')
{
    $dat.=" and o.school_board in ('".implode("','",$school_boardFtr)."')";
}
$sub_productFtr =  json_decode($_REQUEST['sub_product']);
if($sub_productFtr != '')
{
	$dat.=" and tlp.product_type_id in (".implode(",",$sub_productFtr).")";
}
$usersFtr =  json_decode($_REQUEST['users']);
if($usersFtr != '')
{
	$dat.= " and o.created_by in (".implode(",",$usersFtr).")";
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
// && $requestData['d_type'] != "sub_stage"
if($substageFtr)
{
	$dat.=" and o.add_comm in ('".implode("','",$substageFtr)."')";
}

$tagFtr = json_decode($_REQUEST['tag']);
if($tagFtr)
{
	$dat.=" and o.tag in ('".implode("','",$tagFtr)."')";
}

$quantityFtr =  json_decode($_REQUEST['quantity']);
if($quantityFtr != ''){
	if (in_array('9', $quantityFtr)) {
		$dat .= ' and (o.quantity in (' . implode(",",$quantityFtr) . ') or o.quantity >=9)';
	} else if (!in_array('9', $quantityFtr) && $quantityFtr != '') {
		$dat .= ' and o.quantity in (' . implode(",",$quantityFtr) . ') ';
	}
}

 
// getting total number records without any search
$sql = "select * ";
$sql.=" FROM orders as o ".$joinC." left join tbl_lead_product as tlp on o.id=tlp.lead_id where o.agreement_type='Renewal' and 1 " .$dat.$u_cond.$inhouse_user;
//echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "select * ";
$sql.=" FROM orders as o ".$joinC." left join tbl_lead_product as tlp on o.id=tlp.lead_id WHERE o.agreement_type='Renewal' and 1=1 " .$u_cond.$inhouse_user;
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
$sql.=" ORDER BY o.created_date desc LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
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
	
	$nestedData['code'] = "<a style='display:block;color:".$color."' href='view_opportunity.php?id=".$data['id']."'>".($data['code']?$data['code']:'N/A').'</a>';
	
	
	$nestedData['r_name'] = "<a style='display:block;color:".$color."' href='view_opportunity.php?id=".$data['id']."'>".$data['r_name'].'</a>';
	
	$nestedData['quantity'] = "<a style='display:block;color:".$color."' href='view_opportunity.php?id=".$data['id']."'>".$data['quantity'].'</a>';

	$nestedData['school_board'] = "<a style='display:block;color:".$color."' href='view_opportunity.php?id=".$data['id']."'>".$data['school_board'].'</a>';

	$nestedData['school_name'] = "<a style='display:block;color:".$color."' href='view_opportunity.php?id=".$data['id']."'>".$data['school_name'].'</a>';
	 
	$nestedData['created_date'] = date('d-m-Y',strtotime($data['created_date']));

	if($data['status']=='Approved')
	{
		$nestedData['status']='<span style="color:green">Qualified</span> ';	
	}
	else if($data['status']=='Cancelled')
	{
	 $nestedData['status']='<span class="text-danger">Unqualified('.$data['reason'].')</span>';
		
	}
	else if($data['status']=='Pending')
		
		{
			$nestedData['status']= 'Pending';
		}
		else if($data['status']=='Undervalidation')
		{
		 $nestedData['status']= '<span class="text-warning">Re-Submission Required</span>';
		}else if($data['status']=='On-Hold')
		{
			$nestedData['status']= '<span class="text-blue">On-Hold</span>';
		}
		else if($data['status']=='For Validation')
		{
			$nestedData['status']= '<span class="text-themecolor">For Validation</span>';
		}
		else 
		{
			$nestedData['status']= '<span class="text-warning">'.$data['status'].'</span>';
		}
		$nestedData['sub_product'] = getSingleresult("select tp.product_type from tbl_lead_product as tlp left join tbl_product_pivot as tp on tp.id=tlp.product_type_id where tlp.lead_id=" . $data['id']);


		if($data['status'] == 'Approved'){
			
			$ids="'but".$data['id']."'";
			$nestedData['stage'] = ($data['stage']?$data['stage']:'N/A').'<a href="javascript:void(0)" title="Change Stage" id=but'.$data['id'].' onclick="stage_change('.$ids.','.$data['id'].')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>';
		}else{
		   $nestedData['stage'] = '';
	   }	
       $nestedData['qualified_status'] = ($data['status']?$data['status']:'N/A');
	   $nestedData['sub_stage'] = isset($data['add_comm']) ? $data['add_comm'] : 'NA';
       $sstage_sql = db_query("select name from sub_stage where stage_name='demo'");
            while ($subStageHeader = db_fetch_array($sstage_sql)) {
                $nestedData[strtolower(str_replace(' ','_',$subStageHeader['name']))] = $helperData->getDataValues($data['id'],$subStageHeader['name']);
            }

	   $nestedData['close_date'] = $data['expected_close_date'];
	/* $nestedData[] ="<a style='display:block;color:".$color."'  href='renewal_admin_view.php?id=".$data['id']."'>".(($data['stage']=='Closed Lost')?$data['stage'].'('.$data['add_comment'].')':$data['stage']).'</a>';*/

	$results[] = $nestedData;
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
