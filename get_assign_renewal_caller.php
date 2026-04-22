<?php include('includes/include.php'); 

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;
$requestData['search']['value'];
$pattern = "[^a-zA-Z]";
$requestData['length'] = intval($requestData['length']);
$requestData['start'] = intval($requestData['start']);
$requestData['partner'] = intval($requestData['partner']);
$requestData['search']['value'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($requestData['search']['value']));
$columnIndex = $requestData['order'][0]['column'] = intval($requestData['order'][0]['column']);
$requestData['order'][0]['dir'] = preg_replace($pattern, '', $requestData['order'][0]['dir']);
$requestData['license_end_month'] = intval($requestData['license_end_month']);

$requestData['license_end_year'] = intval($requestData['license_end_year']);

if ($requestData['license_end_month']) {
	$dat .= " and  MONTH(license_end_date) ='".$requestData['license_end_month']."'";
}

if ($requestData['license_end_year']) {
	$dat .= " and  YEAR(license_end_date) ='".$requestData['license_end_year']."'"; 
}

if ($requestData['partner']) {
	$dat .= " and team_id='" . $requestData['partner'] . "'";
}

// getting total number records without any search
$condition = " license_type='Renewal'"; 

// $sql = "select * from orders WHERE 1=1 and license_type='Renewal'";
$sql = assignRenewalQuery('orders',$condition);
// echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


// $sql = "select * from orders WHERE 1=1 and license_type='Renewal'";
$sql = assignRenewalQuery('orders',$condition);
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter   
	$sql.=" AND company_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR license_key LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR quantity LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR license_end_date LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR stage LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR partner_close_date LIKE '%".$requestData['search']['value']."%' ";
	
	//$sql.=" OR eu_mobile LIKE '".$requestData['search']['value']."%' )";
}
$sql.=$dat;
$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query);
$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY id desc   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
// echo $sql; die;
$query=db_query($sql);

$results = array();
$i=$requestData['start']+1;
while($data=db_fetch_array($query)) {  // preparing an array
 
	$color='#000';
	if(strtotime($data['license_end_date'])>strtotime(date('Y-m-d')))
	{
		$ed='<span style="color:green">'.date('d-m-Y',strtotime($data['license_end_date'])).'</span>';
	}
	else 
	{
		$ed='<span style="color:red">'.date('d-m-Y',strtotime($data['license_end_date'])).'</span>';
	}
	$nestedData=array(); 
	$nestedData['id']=$i;
	$ncdate=strtotime(date('Y-m-d'));
	$closeDate=strtotime($data['close_time']);
	$nestedData['checkboxinput'] = "<input type='checkbox' name='ids[]'  value='".$data['id']."' id='check_".$data['id']."'/><label for='check_".$data['id']."'></label>";
	$nestedData['license_number'] = $data['license_key']; 
	$nestedData['license_end_date'] = $ed;
	$nestedData['quantity'] = $data['quantity'];
	$nestedData['company_name'] = $data['company_name'];
	// $nestedData['status'] = $data['status'];
	////
	if ($data['status'] == 'Approved') {
		$ncdate = strtotime(date('Y-m-d'));
		$closeDate = strtotime($data['close_time']);
		if ($ncdate > $closeDate) {
			$dayspassedafterExpired = ceil(abs($ncdate - $closeDate) / 86400);
			$daysLeft = '<span style=color:red;">(' . $dayspassedafterExpired . ' Days Passed)</span>';

			if ($dayspassedafterExpired <= 30) {

				// $daysLeft .= '<a href="javascript:void(0)" title="Re-Log" onclick="relog(' . $data['id'] . ')"> <i style="font-size:18px" class="mdi mdi-refresh"></i></a>';

				$nestedData['status'] = '<span style="color:red">Expired</span> ' . $daysLeft;
			} else {
				$nestedData['status'] = '<span style="color:red">Expired</span> ' . $daysLeft;
			}
		} else {
			$ncdate = strtotime(date('Y-m-d'));
			$closeDate = strtotime($data['close_time']);

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
	} else if ($data['status'] == 'For Validation') {
		$nestedData['status'] = '<span class="text-themecolor">For Validation</span>';
	}

	////

	// $nestedData['stage'] = $data['stage'];
	if ($data['status'] == 'Approved') {
		// if (getSingleresult("select count(id) from  lead_review where is_review=1 and lead_id='" . $data['id'] . "'")) {
		// 	$nestedData['stage'] = '<span class="text-danger">Under Review</span>';
		// } else {
			$ids = "'but" . $data['id'] . "'";
			$nestedData['stage'] = ($data['stage'] ? $data['stage'] : 'N/A');
		//}
	} else {
		$nestedData['stage'] = '';
	}
	
	$nestedData['caller'] = getSingleresult("select name from callers where id='".$data['caller']."'");
	// $nestedData['caller'] = " ";
	$nestedData['partner_close_date'] = $data['partner_close_date'];
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
