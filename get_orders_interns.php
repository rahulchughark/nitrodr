<?php include('includes/include.php');
/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;
$pattern = "[^a-zA-Z]";
$requestData['search']['value'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($requestData['search']['value']));
$requestData['length'] = intval($requestData['length']);
$requestData['start'] = intval($requestData['start']);
$requestData['lead_type'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($requestData['lead_type']));
$requestData['partner'] = intval($requestData['partner']);
$requestData['caller'] = intval($requestData['caller']);

$requestData['status'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($requestData['status']));
$requestData['ltype'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($requestData['ltype']));
$requestData['stage'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($requestData['stage']));

// $requestData['industry'] = intval($requestData['industry']);
// $requestData['sub_industry'] = intval($requestData['sub_industry']);
$requestData['runrate_key'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($requestData['runrate_key']));
$requestData['os'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($requestData['os']));
$requestData['order'][0]['dir'] = preg_replace($pattern, '', $requestData['order'][0]['dir']);
$columnIndex = $requestData['order'][0]['column'] = intval($requestData['order'][0]['column']);

$requestData['d_from'] = preg_replace("([^0-9/] | [^0-9-])", "", $requestData['d_from']);
$requestData['d_to'] = preg_replace("([^0-9/] | [^0-9-])", "", $requestData['d_to']);
$requestData['d_from'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlentities($requestData['d_from']));
$requestData['d_to'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlentities($requestData['d_to']));
$requestData['columns'][$columnIndex]['data'] = htmlentities($requestData['columns'][$columnIndex]['data'], ENT_QUOTES);
//$requestData['campaign'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($requestData['campaign']));


$query = access_role_permission();
$fetch_query = db_fetch_array($query);


// getting total number records without any search
$sql = "select o.*,industry.name as industry,states.name as state ";
$sql .= " FROM orders as o left join tbl_lead_product as p on o.id=p.lead_id left join industry on o.industry=industry.id left join states on o.state=states.id where o.license_type='Commercial' and o.dvr_flag!=1 " . $dat . $vir_cond;
$sql .= " GROUP BY o.id";


$query = db_query($sql);
// when there is no search parameter then total number rows = total number filtered rows.
//echo $sql; die;


$sql = "select o.*,industry.name as industry,states.name as state ";
$sql .= " FROM orders as o left join tbl_lead_product as p on o.id=p.lead_id left join industry on o.industry=industry.id left join states on o.state=states.id WHERE o.license_type='Commercial' and o.dvr_flag!=1 ";


if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql .= " AND ( o.quantity LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.company_name LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.eu_email LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.parent_company LIKE '%" . $requestData['search']['value'] . "%' ";
	$sql .= " OR o.eu_mobile LIKE '%" . $requestData['search']['value'] . "%' )";
}

$sql .= $dat . $vir_cond;
$sql .= " GROUP BY o.id";
//echo $sql; die;

$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc



$query = db_query($sql);

$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql .= " ORDER BY o.id desc LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
//$sql.=" ORDER BY ".$columnName." ".$columnSortOrder."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
//echo $sql; die;
$query = db_query($sql);

$results = array();
$i = $requestData['start'] + 1;
while ($data = db_fetch_array($query)) {  // preparing an array

	$nestedData = array();
	$nestedData['id'] = $i;

	$nestedData['company_name']   =  $data['company_name'];
    $nestedData['parent_company'] =  $data['parent_company'] ;
    $nestedData['cust_name']      =  $data['eu_name'] ;
    $nestedData['industry']       =  $data['industry'] ;
    $nestedData['cust_number']    =  $data['eu_mobile'] ;
    $nestedData['email']          =  $data['eu_email'] ;
    $nestedData['city']           =  $data['city'] ;
    $nestedData['state']          =  $data['state'] ;
	$nestedData['quantity']       =  $data['quantity'];

	$results[] = $nestedData;
	$check = '';
	$i++;
}
//print_r($results); die;


$json_data = array(
	"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
	"recordsTotal"    => intval($totalData),  // total number of records
	"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
	"data"            => $results   // total data array
);

//print_r($json_data); die;
echo json_encode($json_data);  // send data as json format
