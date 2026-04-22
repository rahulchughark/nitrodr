<?php include('includes/include.php');
$requestData = $_REQUEST;


	$sql = "select * FROM learning_zone where status=0 ORDER BY id desc";


//$sql .= " GROUP BY o.id";
//echo $sql; die;
$query = db_query($sql);


if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$search = " AND (title LIKE '%" . $requestData['search']['value'] . "%' 
	 OR description LIKE '%" . $requestData['search']['value'] . "%' )";

	$sql = "select * FROM learning_zone where status=0 $search ORDER BY id desc";

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

	$nestedData = array();
	$nestedData[] = $i;
    $nestedData[] = date('d-m-Y',strtotime($data['created_at']));
    $nestedData[] = $data['title'];
    $nestedData[] = '<a onClick="restore(' . $data['id'] . ')" href="javascript:void(0)"><i style="font-size:20px" class="mdi mdi-recycle" aria-hidden="true"></i></a>';
    $nestedData[] = $data['description'];

	
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
