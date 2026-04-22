<?php include('includes/include.php');
$requestData = $_REQUEST;

$sql = "select users.name as user,p.name as partner,r.role_name,l.title,l.type,l.created_at,l.duration FROM learning_centre_user l left join users on l.user_id=users.id left join partners p on l.team_id=p.id left join role r on l.role=r.role_code ORDER BY l.id desc";

//$sql .= " GROUP BY o.id";
//echo $sql; die;
$query = db_query($sql);

if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$search = " AND (p.name LIKE '%" . $requestData['search']['value'] . "%' 
	 OR users.name LIKE '%" . $requestData['search']['value'] . "%' 
	 OR r.role_name LIKE '%" . $requestData['search']['value'] . "%' 
	 OR l.type LIKE '%" . $requestData['search']['value'] . "%'
	 OR l.title LIKE '%" . $requestData['search']['value'] . "%' )";

	$sql = "select users.name as user,p.name as partner,r.role_name,l.title,l.type,l.created_at,l.duration FROM learning_centre_user l left join users on l.user_id=users.id left join partners p on l.team_id=p.id left join role r on l.role=r.role_code where 1 ".$search." ORDER BY l.id desc";
}
// print_r($search);die;
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
	$vdo_address = "'" . $data['vdo_address'] . "'";
    $title = "'" . $data['title'] . "'";

	$nestedData = array();
	$nestedData[] = $i;
	$nestedData[] = $data['user'];
	$nestedData[] = $data['partner'];
	$nestedData[] = $data['role_name'];
	$nestedData[] = $data['title'];
	$nestedData[] = $data['type'];
    $nestedData[] = date('d-m-Y', strtotime($data['created_at']));
    $nestedData[] = date('H:i:s', strtotime($data['created_at']));
    $seconds = $data['duration'] %60;
	$minutes = (floor($data['duration']/60)) % 60;
	$hours = floor($data['duration']/3600);
	$duration =  $hours . ':' . $minutes . ':' . $seconds;
    // $nestedData[] = $duration;
    // $nestedData[] = '<a onClick="duration_views()" href="javascript:void(0)">View</a>';
    
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
