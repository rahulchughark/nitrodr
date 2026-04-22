<?php include('includes/include.php');
/* Database connection end */
 
 // echo $requestData['p_check']; die;
// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST; 


$query = access_role_permission();
$fetch_query = db_fetch_array($query);

if ($_SESSION['sales_manager'] == 1) {
	$vir_cond = " and o.team_id in (" . $_SESSION['access'] . ") OR o.created_by = ".$_SESSION['user_id']." ";
}

$license_from = !empty($requestData['license_from']) ? date('Y-m-d', strtotime($requestData['license_from'])) : '';

$license_to = !empty($requestData['license_to']) ? date('Y-m-d', strtotime($requestData['license_to'])) : '';


if($requestData['license_to'] && $requestData['license_from']){
if ($requestData['license_to'] == $requestData['license_from']) {
	$dat = " and o.license_end_date='" . $license_from . "'";
} else {
	$dat = " and (date(o.license_end_date) between '" . $license_from . "' and '" . $license_to . "')";
}
}



if($requestData['d_from'] && $requestData['d_to'] && $_REQUEST['dash']!='yes' && $_REQUEST['p_check']!='yes')
{

	if($requestData['dtype']=='created')
	{
			if($requestData['d_from'] == $requestData['d_to'])
			{
			$dat=" and DATE(o.created_date)='".$requestData['d_from']."'";	
			} else {
			$dat=" and DATE(o.created_date)>='".$requestData['d_from']."' and DATE(o.created_date)<='".$requestData['d_to']."'";	
			}
    }
	else if($requestData['dtype']=='close')
	{
		if($requestData['d_from'] == $requestData['d_to'])
		{
		$dat=" and DATE(o.partner_close_date)='".$requestData['d_from']."'";	
		} else {
		$dat=" and DATE(o.partner_close_date)>='".$requestData['d_from']."' and DATE(o.partner_close_date)<='".$requestData['d_to']."'";	
		}
				
	}
	else
	{
		if($requestData['d_from'] == $requestData['d_to'])
		{
		$dat=" and DATE(o.prospecting_date)='".$requestData['d_from']."'";	
		} else {
		$dat=" and DATE(o.prospecting_date)>='".$requestData['d_from']."' and DATE(o.prospecting_date)<='".$requestData['d_to']."'";	
		}
	}
}

else if($requestData['d_from'] && $requestData['d_to'] && ($requestData['dash']=='yes' || $requestData['p_check']=='yes' ) )
{
	$dat=" and  ((date(o.created_date) BETWEEN  '".$requestData['d_from']."' and '".$requestData['d_to']."') or (date(o.prospecting_date) BETWEEN  '".$requestData['d_from']."' and '".$requestData['d_to']."') )";	
}
if($requestData['lead_type'])
{
	if($requestData['lead_type']=='Internal')
	{
		$dat.=" and o.iss='1' ";
	}
	else if($requestData['lead_type']=='LC')
	{
		$dat.=" and o.lead_type = 'LC' and o.iss is NULL ";
	}
	else
	{
	if(strpos($requestData['lead_type'], 'Internal'))
	$dat.=" and o.lead_type in ('".$requestData['lead_type']."') and o.iss='1' ";
	else
	$dat.=" and o.lead_type in ('".$requestData['lead_type']."')";
	}
}
 
if($requestData['status'])
{
    $dat.=" and o.status='".$requestData['status']."'";
}
if($requestData['ltype'])
{
    $dat.=" and o.license_type='".$requestData['ltype']."'";
}
if($requestData['stage'])
{
    $dat.=" and o.stage in ('".$requestData['stage']."')";
}
if($requestData['partner'])
{
    $dat.=" and o.team_id='".$requestData['partner']."'";
}
if($requestData['caller'])
{
    $dat.=" and o.caller='".$requestData['caller']."'";
}
if($requestData['users'])
{
    $dat.=" and o.created_by='".$requestData['users']."'";
}
if($requestData['caller'])
{
$dat.=" and o.caller='".$requestData['caller']."'";
}
$quant_arr = explode(',', $requestData['quantity']);

if (in_array('9', $quant_arr)) {
	$dat .= ' and (o.quantity in (' . stripslashes($requestData["quantity"]) . ') or o.quantity >=9)';
} else if (!in_array('9', $quant_arr) && $requestData["quantity"] != '') {
	$dat .= ' and o.quantity in (' . stripslashes($requestData["quantity"]) . ') ';
}
if($requestData['industry'])
{
$dat.=" and o.industry='".$requestData['industry']."'";
}
if($requestData['sub_industry'])
{
$dat.=" and o.sub_industry='".$requestData['sub_industry']."'";
}
if($requestData['runrate_key'])
{
$dat.=" and o.runrate_key='".$requestData['runrate_key']."'";
}
if($requestData['os'])
{
$dat.=" and o.os='".$requestData['os']."'";
}
if($requestData['expired']=='Yes')
{
$date=date('Y-m-d');
//$check_date=date('Y-m-d',strtotime('-29 days',$date));
$dat.=" and o.status='Approved' and o.close_time < '".$date."'";
}
else if($requestData['expired']=='No') 
{
	$date=date('Y-m-d');
	//$check_date=date('Y-m-d',strtotime('-29 days',$date));
	$dat.=" and o.status='Approved' and o.close_time > '".$date."'";	
}
 
// getting total number records without any search

$condition1 = " and o.license_type = 'Education' and dvr_flag!=1 " .$dat .$vir_cond;
$sql = educationLeadQuery($condition1);
// print_r($sql);
// die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
// echo $sql; die;

$condition2 = " and o.license_type = 'Education' and dvr_flag!=1";
$sql = educationLeadQuery($condition2);

if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( o.code LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR o.r_name LIKE '%".$requestData['search']['value']."%' ";

	 
	$sql.=" OR o.lead_type LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.quantity LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.company_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.eu_email LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.status LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.parent_company LIKE '%".$requestData['search']['value']."%' ";
	$sql .= " OR o.website LIKE '%".$requestData['search']['value']."%' ";
	$sql .= " OR o.eu_name LIKE '%".$requestData['search']['value']."%' ";
	$sql .= " OR o.eu_landline LIKE '".$requestData['search']['value']."%' ";
	$sql .= " OR o.landline LIKE '%".$requestData['search']['value']."%' ";
	$sql .= " OR o.eu_mobile LIKE '%" . $requestData['search']['value'] . "%' )";
	//$sql.=" OR eu_landline LIKE '".$requestData['search']['value']."%' )";
	
	//$sql.=" OR eu_mobile LIKE '".$requestData['search']['value']."%' )";
}
$sql.=$dat .$vir_cond;
// echo $sql; die;

$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc



$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY o.id desc LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
//echo $sql; die;
$query=db_query($sql);

$results = array();
$i=$requestData['start']+1;
while($data=db_fetch_array($query)) {  // preparing an array
	if ($data['sfdc_exp'] == 1 && $_SESSION['sales_manager'] != 1) {
		$color = '#225da8';
	} else {
		$color = '#000';
	}
	if(strtotime($data['license_end_date'])>strtotime(date('Y-m-d')))
	{
		$ed='<span style="color:green">'.($data['license_end_date']?date('d-M-Y',strtotime($data['license_end_date'])):'N/A').'</span>';
	}
	else
	{
		$ed='<span style="color:red">'.($data['license_end_date']?date('d-M-Y',strtotime($data['license_end_date'])):'N/A').'</span>';
	}
	$nestedData=array(); 
	$nestedData['id']=$i;
	$ncdate=strtotime(date('Y-m-d')); 
	$closeDate=strtotime($data['close_time']);

	$data['r_name'] = explode(' ', trim($data['r_name']));
	$data['r_user'] = explode(' ', $data['r_user']);

	if ($data['sfdc_check'] == 1) {
		$check = ' checked="Checked" ';
	}

	$nestedData['check'] = '<input type="checkbox" ' . $check . ' class="checkbox" name="check[]" value ="' . $data['id'] . '" id="check_' . $data['id'] . '"><label for="check_' . $data['id'] . '"></label>';

	if ($ncdate > $closeDate) {
		$nestedData['code'] = "<span style='color:" . $color . ";font-weight:" . $bold . "'>N/A</span>";
	} else {
		$nestedData['code'] = "<a target='_blank' style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='view_order.php?id=" . $data['id'] . "'>" . ($data['code'] ? $data['code'] : 'N/A') . '</a>';
	}

	$nestedData['r_name'] = "<a target='_blank' style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['r_name'][0] . '(' . $data['r_user'][0] . ')</a>';

	$nestedData['lead_type'] = "<a target='_blank' style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['lead_type'] . '</a>';

	$nestedData['quantity'] = "<a target='_blank' style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['quantity'] . '</a>';

	$nestedData['product_name'] = "<a target='_blank' style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='view_order.php?id=" . $data['id'] . "'>" .  $data['product_type'] . '</a>';


	$nestedData['company_name'] = "<a target='_blank' style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['company_name'] . '</a>';

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
	} else if ($data['status'] == 'Already locked') {
		$nestedData['status'] = '<span class="text-themecolor">Already locked</span>';
	} else if ($data['status'] == 'Insufficient Information') {
		$nestedData['status'] = '<span class="text-themecolor">Insufficient Information</span>';
	} else if ($data['status'] == 'Incorrect Information') {
		$nestedData['status'] = '<span class="text-themecolor">Incorrect Information</span>';
	} else if ($data['status'] == 'Out Of Territory') {
		$nestedData['status'] = '<span class="text-themecolor">Out Of Territory</span>';
	} else if ($data['status'] == 'Duplicate Record Found') {
		$nestedData['status'] = '<span class="text-themecolor">Duplicate Record Found</span>';
	} 


	if ($data['status'] == 'Approved') {
		$ids = "'but" . $data['id'] . "'";

		$nestedData['stage'] = "<span style='color:" . $color . ";font-weight:" . $bold . "'>" . ($data['stage'] ? $data['stage'] : 'N/A') . "</span>" . (($fetch_query['edit_stage'] == 1) ? ('<a href="javascript:void(0)" title="Change Stage" id=but' . $data['id'] . ' onclick="stage_change(' . $ids . ',' . $data['id'] . ')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>') : '');
	} else {
		$nestedData['stage'] = '';
	}


	$nestedData['caller'] = "<span style='color:" . $color . ";font-weight:" . $bold . "'>" . getSingleresult("select name from callers where id='" . $data['caller'] . "'") . ($data['allign_to'] ? '<br><span style="font-size:8px">(' . getSingleresult("select name from users where id=" . $data['allign_to']) . ')</span>' : '') . "</span>";
	$ids2 = "'but2" . $data['id'] . "'";

	$nestedData['partner_close_date'] = "<span style='color:" . $color . ";font-weight:" . $bold . "'>" . ($data['partner_close_date'] ? date('d-m-Y', strtotime($data['partner_close_date'])) : 'N/A') . "</span>" . (($fetch_query['edit_date'] == 1) ? ('<a href="javascript:void(0)" title="Change Stage" id=but2' . $data['id'] . ' onclick="cd_change(' . $ids2 . ',' . $data['id'] . ')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>') : '');

	$nestedData['actioned_by'] = "<a target='_blank' style='display:block;color:" . $color . ";font-weight:" . $bold . "' href='view_order.php?id=" . $data['id'] . "'>" . getSingleresult("select u.name from users as u left join lead_modify_log as l on l.created_by=u.id where l.type='Status' and l.lead_id='" . $data['id'] . "' and l.previous_name='Pending' order by created_date desc ") . '</a>';

	if($data['data_ref'] == 2){
		$nestedData['data_ref'] = 'APP';
	}elseif($data['data_ref'] == 1){
		$nestedData['data_ref'] = 'WEB';
	}else{
		$nestedData['data_ref'] = '';
	}
	$results[] = $nestedData;
	$check = '';
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
