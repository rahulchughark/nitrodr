<?php include('includes/include.php');
$requestData = $_REQUEST;

if($requestData['type'])
{
	$dat=" and type = '".$requestData['type']."'";
}

$sql = "select * FROM learning_zone where status=1 $dat ORDER BY title";

//$sql .= " GROUP BY o.id";
$query = db_query($sql);


if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$search = " AND (title LIKE '%" . $requestData['search']['value'] . "%' 
	 OR description LIKE '%" . $requestData['search']['value'] . "%' )";


	$sql = "select * FROM learning_zone where status=1 $dat $search ORDER BY title";
}


$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc

//$sql .= " GROUP BY o.id";
$query = db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
// echo $sql; die;
$query = db_query($sql);

$results = array();
$i = $requestData['start'] + 1;
while ($data = db_fetch_array($query)) {  // preparing an array
	$color = '#000';
	$bold = '0';

	$document = $data['document'];
	if($data['users_access'] != null && $data['users_access'] != '0'){
		$usrs = $data['users_access'];
	}else{
		$usrs = 'All Users';
	}
	$nestedData = array();
	$nestedData[] = $i;
	$nestedData[] = $data['title']; 
	$nestedData[] = $data['type']; 
	$nestedData[] = $usrs; 
	$nestedData[] = '<a href="javascript:void(0)" class="btn btn-primary btn-xs px-2 text-nowrap" title="Change Access" onclick="change_access(' . $data['id'] . ')"><i style="font-size:16px" class="mdi mdi-pencil"></i> Change Access</a>';

	$results[] = $nestedData;
	$i++;
}

$json_data = array(
	"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
	"recordsTotal"    => intval($totalData),  // total number of records
	"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
	"data"            => $results   // total data array
);

echo json_encode($json_data);  // send data as json format
