<?php include('includes/include.php');
/* Database connection end */

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;
if ($requestData['d_from'] && $requestData['d_to']) {

    if ($requestData['d_from'] == $requestData['d_to']) {
        $dat = " and DATE(activity_log.created_date)='" . $requestData['d_from'] . "' ";
    } else {

        $dat = " and  (date(activity_log.created_date) BETWEEN  '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "')";
    }
}

if ($requestData['partner']) {

    $dat .= " and o.team_id='" . $requestData['partner'] . "'";

    $users1 = db_query("select id,role from users where team_id='" . $requestData['partner'] . "' and status='Active' ");
    $ids = array();
    while ($uid = db_fetch_array($users1)) {
        $ids[] = $uid['id'];
    }
    $idds = implode(',', $ids);
    $id_check = " and activity_log.added_by in (" . $idds . ") ";
}

if ($requestData['campaign']) {
    $dat .= ' and o.campaign_type in ("' . stripslashes($requestData["campaign"]) . '")';
}

if ($requestData['call_type']) {
    $dat .= ' and activity_log.call_subject in ("' . stripslashes($requestData["call_type"]) . '")';
}

if ($requestData['ark_users']) {
    $dat .= " and activity_log.added_by='" . $requestData['ark_users'] . "'";
}

if ($requestData['users']) {
    $dat .= " and activity_log.added_by='" . $requestData['users'] . "'";
}


if ($requestData['campaign_check'] == 'yes') {
    $dat .= " and (o.campaign_type = 0 || o.campaign_type IS NULL)";
}

if ($requestData['product']) {
    $dat .= " and p.product_id='" . $requestData['product'] . "' ";
}
if ($requestData['product_type']) {
    $dat .= " and p.product_type_id='" . $requestData['product_type'] . "' ";
}


// getting total number records without any search
$sql = "select * from ((select o.id as lead,o.license_type,o.team_id,o.code,o.r_name,o.lead_type,o.quantity,o.company_name,o.eu_email,o.status,o.parent_company,o.landline,o.eu_mobile,activity_log.*,o.stage,o.campaign_type FROM activity_log left join orders as o on o.id=activity_log.pid where 1 and activity_log.call_subject!=''  and activity_log.call_subject not like '%visit%' and o.id!='' and o.license_type='Renewal' " . $dat . $u_cond  . $id_check . " ) UNION ALL (select o.id as lead,o.license_type,o.team_id,o.code,o.r_name,o.lead_type,o.quantity,o.company_name,o.eu_email,o.status,o.parent_company,o.landline,o.eu_mobile,activity_log.*,o.stage,o.campaign_type FROM activity_log left join lapsed_orders as o on o.id=activity_log.pid where 1 and activity_log.call_subject!='' and activity_log.call_subject not like '%visit%' and o.id!='' and o.license_type='Renewal' " . $dat . $u_cond . $id_check . " ) ) as i order by i.id desc";

// echo $sql; die;

//$sql .= " GROUP BY o.id";
$query = db_query($sql);
//$totalData = mysqli_num_rows($query);
//$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
    $search = " AND (o.r_name LIKE '%" . $requestData['search']['value'] . "%' 
	 OR o.quantity LIKE '%" . $requestData['search']['value'] . "%' 
	 OR o.company_name LIKE '%" . $requestData['search']['value'] . "%' 
	 OR o.eu_email LIKE '%" . $requestData['search']['value'] . "%'
	 OR o.parent_company LIKE '%" . $requestData['search']['value'] . "%'
	 OR o.landline LIKE '%" . $requestData['search']['value'] . "%'
	 OR activity_log.activity_type LIKE '%" . $requestData['search']['value'] . "%' 
	 OR o.eu_mobile LIKE '%" . $requestData['search']['value'] . "%' )";

    $sql = "select * from ((select o.id as lead,o.team_id,o.code,o.r_name,o.lead_type,o.quantity,o.company_name,o.eu_email,o.status,o.parent_company,o.landline,o.eu_mobile,activity_log.*,o.campaign_type FROM activity_log left join orders as o on o.id=activity_log.pid  where 1 and activity_log.call_subject!=''  and activity_log.call_subject not like '%visit%' and o.id!='' and o.license_type='Renewal'  " . $dat . $u_cond . $id_check . $search . ") UNION ALL (select o.id as lead,o.team_id,o.code,o.r_name,o.lead_type,o.quantity,o.company_name,o.eu_email,o.status,o.parent_company,o.landline,o.eu_mobile,activity_log.*,o.campaign_type FROM activity_log left join lapsed_orders as o on o.id=activity_log.pid where 1 and activity_log.call_subject!=''  and activity_log.call_subject not like '%visit%' and o.id!='' and o.license_type='Renewal' " . $dat . $u_cond . $id_check . $search . ") ) as i order by i.id desc";
}


//echo $sql; die;
$query = db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData; // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
//echo $sql; die;
$query = db_query($sql);

$results = array();
$i = 1;
while ($data = db_fetch_array($query)) {  // preparing an array

    $color = '#000';

    $nestedData = array();
    $nestedData[] = $i;
    if ($data['dvr_by']) {
        $dvr_by = '(' . getSingleresult("select name from users where id=" . $data['dvr_by']) . ')';
    }
    $nestedData[] = $data['r_name'];

    $nestedData[] = getSingleresult("select name from users where id=" . $data['added_by']);
    $nestedData[] = date('d-m-Y', strtotime($data['created_date']));
    $nestedData[] = $data['call_subject'];
    $nestedData[] = $data['company_name'];
    $nestedData[] = $data['quantity'];
    $nestedData[] = $data['stage'];

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
