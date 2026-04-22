<?php include('includes/include.php');
/* Database connection end */

//  if($_SESSION['user_id']==117)
// {
// 	$vir_cond=" and o.lead_type='LC'";
// }

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;
//$requestData['partner'] = intval($requestData['partner']);
$requestData['length'] = intval($requestData['length']);
$requestData['start'] = intval($requestData['start']);
$requestData['search']['value'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($requestData['search']['value']));
//$requestData['users'] = intval($requestData['users']);	


if ($requestData['d_from'] && $requestData['d_to']) {

    if ($requestData['d_from'] == $requestData['d_to']) {
        $dat = " and (DATE(o.convert_date)='" . $requestData['d_from'] . "')";
    } else {

        $dat = " and  (date(o.convert_date) BETWEEN  '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "' )";
    }
}



// if($requestData['lead_type'])
// {
//     $dat.=" and o.lead_type='".$requestData['lead_type']."'";
// }

// if($requestData['status'])
// {
//     $dat.=" and o.status='".$requestData['status']."'";
// }

// if ($requestData['partner']) {
//     $dat.=' and o.team_id in ("' . stripslashes($requestData["partner"]) . '")';
// }

if ($requestData['users']) {
    $dat .= " and or o.dvr_by='" . $requestData['users'] . "'";
}

if ($requestData['industry']) {
	$dat .= ' and o.industry in ("' . stripslashes($requestData["industry"]) . '")';
}

if ($requestData['call_type']) {
	$dat .= ' and o.call_type in ("' . stripslashes($requestData["call_type"]) . '")';
}

// if ($requestData['product']) {
//     $dat .= " and p.product_id='" . $requestData['product'] . "'";
// }
// if ($requestData['product_type']) {
//     $dat .= " and p.product_type_id='" . $requestData['product_type'] . "'";
// }



// getting total number records without any search
$sql = "select o.r_user,o.r_name,o.code,o.company_name,o.eu_email,o.parent_company,o.eu_mobile,o.id,o.created_date,o.status,o.lead_type,o.team_id,o.created_by,o.quantity,o.date_dvr,o.call_type,i.name as industry,o.dvr_by ";
$sql .= " FROM orders as o left join industry as i on o.industry=i.id where o.team_id='".$_SESSION['team_id']."' and o.dvr_by!=0 and o.is_dr=1 " . $dat;
// echo $sql; die;
$sql .= " GROUP BY o.id";
$query = db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "select o.r_user,o.r_name,o.code,o.company_name,o.eu_email,o.parent_company,o.eu_mobile,o.id,o.created_date,o.status,o.lead_type,o.team_id,o.created_by,o.quantity,o.date_dvr,o.call_type,i.name as industry,o.dvr_by ";
$sql .= " FROM orders as o left join industry as i on o.industry=i.id where o.team_id='".$_SESSION['team_id']."' and o.dvr_by!=0 and o.is_dr=1 " . $dat;

if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
    $sql .= " AND ( o.code LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR o.r_name LIKE '%" . htmlspecialchars($requestData['search']['value']) . "%' ";
    $sql .= " OR o.lead_type LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR o.quantity LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR o.company_name LIKE '%" . $requestData['search']['value'] . "%' ";
    $sql .= " OR o.eu_email LIKE '%" . $requestData['search']['value'] . "%' ";
    //$sql.=" OR o.status LIKE '%".$requestData['search']['value']."%' ";
    //$sql.=" OR o.parent_company LIKE '%".$requestData['search']['value']."%' ";
    //$sql.=" OR o.landline LIKE '%".$requestData['search']['value']."%' ";
    //$sql.=" OR o.eu_mobile LIKE '%".$requestData['search']['value']."%' )";

    //$sql.=" OR eu_mobile LIKE '".$requestData['search']['value']."%' )";
}


$sql .= $dat;
$sql .= " GROUP BY o.id";
$query = db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql .= " ORDER BY o.date_dvr,o.created_date desc  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
//echo $sql; die;
$query = db_query($sql);

$results = array();
$i = 1;
while ($data = db_fetch_array($query)) {  // preparing an array

    $color = '#000';

    $nestedData = array();
    $nestedData[] = $i;
    // if ($data['dvr_by']) {
    //     $dvr_by = '(' . getSingleresult("select name from users where id=" . $data['dvr_by']) . ')';
    // }
   // $nestedData[] = $data['r_name'];
    $nestedData[] = getSingleresult("select name from users where id=" . $data['dvr_by']);
    $nestedData[] = $data['lead_type'];
    //$nestedData[] = $data['quantity'];
    $nestedData[] = $data['company_name'];
    $nestedData[] = $data['industry'];
    $nestedData[] = ($data['convert_date']!=''?date('d-m-Y', strtotime($data['convert_date'])):'');

    if (is_numeric($data['call_type'])) {
        $nestedData[] = getSingleresult("select name from call_type where id=" . $data['call_type']);
    } else {
        $nestedData[] =     $data['call_type'];
    }
    //$nestedData[] = getSingleresult("select count(id) from activity_log where activity_type='DVR' and pid=".$data['id']);
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
