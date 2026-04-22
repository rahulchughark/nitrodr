<?php include('includes/include.php');
include_once('helpers/DataController.php');
$dataObj = new DataController;

$requestData = $_REQUEST;

$category = isset($_REQUEST['document_category']) ? $_REQUEST['document_category'] : '';
if (!empty($category)) {
    $where= " AND document_category = '" . $category . "'";
}

// $sql = "select * FROM learning_zone where status=1 and type='Doc' AND FIND_IN_SET('".$_SESSION['user_type']."', users_access) AND FIND_IN_SET(".$_SESSION['team_id'].", partner_access) ";

$sql = "
    SELECT DISTINCT lz.*
    FROM learning_zone lz
    INNER JOIN learning_zone_attachment lza 
        ON lza.zone_id = lz.id 
        AND lza.status = 1
        AND lza.deleted = 0
        AND FIND_IN_SET('".$_SESSION['user_type']."', lza.users_access)
        AND FIND_IN_SET(".$_SESSION['team_id'].", lza.partner_access)
    WHERE lz.status = 1
      AND (lz.type = 'DOC' OR lza.type = 'DOC')
";

$query = db_query($sql);
$sql.=$where;
$sql.= " ORDER BY id desc";
// echo $sql; die;

if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$search = " AND (learning_zone.title LIKE '%" . $requestData['search']['value'] . "%' 
	 OR learning_zone.description LIKE '%" . $requestData['search']['value'] . "%' )";


    $sql = "select * FROM learning_zone where status=1 and type='Doc' AND FIND_IN_SET('".$_SESSION['user_type']."', users_access) AND FIND_IN_SET(".$_SESSION['team_id'].", partner_access) $where $search ORDER BY id desc";
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

    $nestedData = array();
    $nestedData[] = $i;
    $nestedData[] = date('d-m-Y', strtotime($data['created_at']));
    // $nestedData[] = $data['title'];
    // if (file_exists($document)) {
    //     $nestedData[] = '<a onClick="count_views(`' . $document . '`,' . $data['id'] . ')" href="javascript:void(0)"><i class="fa fa-folder-open" aria-hidden="true"></i></a>';
    // }else{
    //     $nestedData[] = '<a onClick="notFound()" href="javascript:void(0)"><i class="fa fa-folder-open" aria-hidden="true"></i></a>';
    // }
    $nestedData[] = '<a onclick="return document_view('.$data["id"].')"  href="javascript:void(0)"><i class="fa fa-folder-open" aria-hidden="true"></i></a>';
    // $nestedData[] = $data['document_category'];
    $nestedData[] = !$data['category_id'] ? $data['document_category'] : $dataObj->getCategoryPath($data['category_id']);

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
