<?php include('includes/include.php');
/* Database connection end */

if ($_SESSION['user_id'] != '118' && $_SESSION['user_id'] != '122' && $_SESSION['user_id'] != '123' && $_SESSION['user_id'] != '124') {

	$u_cond .= " and o.created_by='" . $_SESSION['user_id'] . "' ";
}

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;

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

$caller_data = db_query("select caller from users where id=".$_SESSION['user_id']);
while($callers_arr = db_fetch_array($caller_data)){
	$caller_lead[]=$callers_arr['caller'];
}

if($_SESSION['user_type']=='TEAM LEADER'){
	$dat .= " and o.caller in (".implode(',',$caller_lead).")";
}


// getting total number records without any search
$sql = "select o.* ";
$sql .= " FROM orders as o left join tbl_lead_product as tlp on o.id=tlp.lead_id left join users on users.caller=o.caller left join activity_log on o.id=activity_log.pid where o.dvr_flag=0  " . $dat . $u_cond ." GROUP BY o.id";

$query = db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.



if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$search = " AND (o.code LIKE '" . $requestData['search']['value'] . "%' 
	OR o.r_name LIKE '%" . $requestData['search']['value'] . "%' 
	OR o.quantity LIKE '%" . $requestData['search']['value'] . "%' 
	OR o.company_name LIKE '%" . $requestData['search']['value'] . "%' 
	OR o.eu_email LIKE '%" . $requestData['search']['value'] . "%'
	OR o.lead_type LIKE '%" . $requestData['search']['value'] . "%'
	OR o.eu_mobile LIKE '%" . $requestData['search']['value'] . "%' )";

$sql = "select o.* ";
$sql .= " FROM orders as o left join tbl_lead_product as tlp on o.id=tlp.lead_id left join users on users.caller=o.caller left join activity_log on o.id=activity_log.pid WHERE o.dvr_flag=0 " . $dat . $search . $u_cond ." GROUP BY o.id";

}

$query = db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 

//echo $totalFiltered; die;
$sql .= " ORDER BY o.id desc  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
//echo $sql; die;
$query = db_query($sql);


$results = array();
$i = $requestData['start'] + 1;
while ($data = db_fetch_array($query)) {  // preparing an array

	$color = '#000';

	$nestedData = array();
	$nestedData[] = $i;
	$ncdate = strtotime(date('Y-m-d'));
	$closeDate = strtotime($data['close_time']);
	$nestedData[] = "<a style='display:block;color:" . $color . "' href='iss_view.php?id=" . $data['id'] . "'>" . ($data['code']?$data['code']:'N/A') . '</a>';
	$nestedData[] = "<a  style='display:block;color:" . $color . "' href='iss_view.php?id=" . $data['id'] . "'>" . $data['r_name'] . '</a>';
	$nestedData[] = "<a  style='display:block;color:" . $color . "' href='iss_view.php?id=" . $data['id'] . "'>" . $data['school_board'] . '</a>';
	
	$nestedData[] = "<a style='display:block;color:" . $color . "' href='iss_view.php?id=" . $data['id'] . "'>" . $data['school_name'] . '</a>';
	$nestedData[] = "<a style='display:block;color:" . $color . "' href='iss_view.php?id=" . $data['id'] . "'>" . $data['quantity'] . '</a>';
	
	$nestedData[] = "<a style='display:block;color:" . $color . "' href='iss_view.php?id=" . $data['id'] . "'>" .  getSingleresult("select tp.product_type from tbl_lead_product as tlp left join tbl_product_pivot as tp on tp.id=tlp.product_type_id where tlp.lead_id=" . $data['id']) . '</a>';
	$nestedData[] = date('d-m-Y h:i:s',strtotime($data['created_date']));
	if ($data['status'] == 'Approved') {
		$ncdate = strtotime(date('Y-m-d'));
		$closeDate = strtotime($data['close_time']);
		if ($ncdate > $closeDate) {
			$dayspassedafterExpired = ceil(abs($ncdate - $closeDate) / 86400);
			$daysLeft = '<span style=color:red;">Expired (' . $dayspassedafterExpired . ' Days Passed)</span>';
			if ($dayspassedafterExpired <= 30) {

				$daysLeft .= '<a href="javascript:void(0)" title="Re-Log" onclick="relog(' . $data['id'] . ')"> <i style="font-size:18px" class="mdi mdi-refresh"></i></a>';
			}
		} else {

			$remaining_days = ceil(($closeDate - $ncdate) / 84600);
			$daysLeft = '<span style="color:green">Days Left- ' . $remaining_days . '</span>';
		}

		$nestedData[] = '<span style="color:green">Qualified</span> ' . $daysLeft;
	} else if ($data['status'] == 'Cancelled') {
		$nestedData[] = '<span class="text-danger">Unqualified(' . $data['reason'] . ')</span>';
	} else if ($data['status'] == 'Pending') {
		$nestedData[] = 'Pending';
	} else if ($data['status'] == 'Undervalidation') {
		$nestedData[] = '<span class="text-warning">Re-Submission Required</span>';
	} else if ($data['status'] == 'For Validation') {
		$nestedData[] = '<span class="text-themecolor">For Validation</span>';
	};

	if ($data['status'] == 'Approved') {
		if (getSingleresult("select count(id) from  lead_review where is_review=1 and lead_id='" . $data['id'] . "'")) {
			$nestedData[] = '<span class="text-danger">Under Review</span>';
		} else {
			$ids = "'but" . $data['id'] . "'";
			$nestedData[] = ($data['stage'] ? $data['stage'] : 'N/A') . '<a href="javascript:void(0)" title="Change Stage" id=but' . $data['id'] . ' onclick="stage_change(' . $ids . ',' . $data['id'] . ')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>';
		}
	} else {
		$nestedData[] = '';
	}

	
	$nestedData[] = $data['expected_close_date'];
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
