<?php include('includes/include.php');
/* Database connection end */



// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;
$pattern = "[^a-zA-Z]";
$requestData['search']['value'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($requestData['search']['value']));
$requestData['length'] = intval($requestData['length']);
$requestData['start'] = intval($requestData['start']);


$requestData['order'][0]['dir'] = preg_replace($pattern, '', $requestData['order'][0]['dir']);
$columnIndex = $requestData['order'][0]['column'] = intval($requestData['order'][0]['column']);

$requestData['columns'][$columnIndex]['data'] = htmlentities($requestData['columns'][$columnIndex]['data'], ENT_QUOTES);



// getting total number records without any search
$sql = "select * ";
$sql .= " FROM follow_up_notification where status!='Completed' and user_id=".$_SESSION['user_id'];


$query = db_query($sql);
// when there is no search parameter then total number rows = total number filtered rows.
//echo $sql; die;



$sql = "select * ";
$sql .= " FROM follow_up_notification where status!='Completed' and user_id=".$_SESSION['user_id'];


if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
    $sql .= " AND ( o.code LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR o.r_name LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR o.lead_type LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR o.quantity LIKE '%" . $requestData['search']['value'] . "%' ";

}



$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc



$query = db_query($sql);

$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql .= " ORDER BY id desc LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
//$sql.=" ORDER BY ".$columnName." ".$columnSortOrder."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
//echo $sql; die;
$query = db_query($sql);

$results = array();
$i = $requestData['start'] + 1;
while ($data = db_fetch_array($query)) {  // preparing an array

    $nestedData = array();
    $nestedData['id'] = $i;

    $nestedData['task'] = $data['follow_type'];

    $nestedData['account_name'] = "<a target='_blank' style='display:block;color:" . $color . "' href='partner_view.php?id=" . $data['id'] . "'>" . $data['company_name'] . '</a>';

    $nestedData['date'] =  date('d-M-Y',strtotime($data['follow_up_date']));

    $nestedData['time'] = $data['follow_up_time'] ;

    $nestedData['status'] = $data['status'];


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
