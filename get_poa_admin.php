<?php include('includes/include.php');

/* Database connection end */

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;

//$requestData['partner'] = intval($requestData['partner']);
$requestData['length'] = intval($requestData['length']);
$requestData['start'] = intval($requestData['start']);
$requestData['search']['value'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($requestData['search']['value']));

if ($requestData['d_from'] && $requestData['d_to']) {
    if ($requestData['dtype'] == 'qualified_date') {
        if ($requestData['d_from'] == $requestData['d_to']) {
            $dat = " and DATE(o.approval_time)='" . $requestData['d_from'] . "' ";
        } else {
            $dat = " and  (date(o.approval_time) BETWEEN  '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "')";
        }
    } else if ($requestData['dtype'] == 'poa_assigned') {
        if ($requestData['d_from'] == $requestData['d_to']) {
            $dat = " and DATE(a.created_date)='" . $requestData['d_from'] . "' ";
        } else {
            $dat = " and  (date(a.created_date) BETWEEN  '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "')";
        }
    }
}

if ($requestData['partner']) {
    $dat .= ' and o.team_id in ("' . stripslashes($requestData["partner"]) . '")';
}
if ($requestData['poa_stamped']) {
    $dat .= ' and a.action_plan in ("' . stripslashes($requestData["poa_stamped"]) . '")';
}
if ($requestData['lead_type']) {
    if ($requestData['lead_type'] == 'Internal') {
        $dat .= " and o.source = 'Internal' and o.iss='1' ";
    } else if ($requestData['lead_type'] == 'LC') {
        $dat .= " and o.lead_type = 'LC' and o.iss is NULL ";
    } else {
        if (strpos($requestData['lead_type'], 'Internal'))
            $dat .= " and o.lead_type in ('" . $requestData['lead_type'] . "') and iss='1' ";
        else
            $dat .= " and o.lead_type in ('" . $requestData['lead_type'] . "')";
    }
}
if ($requestData['segment'] == 'DTP') {
    $dat .= " and i.log_status=1";
}
if ($requestData['segment'] == 'Other') {
    $dat .= " and i.log_status=0";
}

if ($_SESSION['sales_manager'] == 1) {
    $dat .= " and o.team_id in (" . $_SESSION['access'] . ") ";
}


// getting total number records without any search

$sql = "select o.*,a.action_plan,a.created_date as activity_date,a.added_by as assigned_by,c.name as caller from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log a on o.id=a.pid left join callers c on c.id=o.caller left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.status='Approved' and a.is_intern=0 and a.action_plan!='' $dat  group by a.pid having (count(a.pid)>0) order by a.created_date desc";

//echo $sql; die;

//$sql .= " GROUP BY o.id";
$query = db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
//echo $totalFiltered; die;
if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
    $search = " AND (o.r_name LIKE '%" . $requestData['search']['value'] . "%' 
	 OR o.quantity LIKE '%" . $requestData['search']['value'] . "%' 
	 OR o.company_name LIKE '%" . $requestData['search']['value'] . "%' 
	 OR o.eu_email LIKE '%" . $requestData['search']['value'] . "%'
	 OR o.parent_company LIKE '%" . $requestData['search']['value'] . "%'
	 OR o.landline LIKE '%" . $requestData['search']['value'] . "%'
	 OR o.eu_mobile LIKE '%" . $requestData['search']['value'] . "%' )";

    $sql = "select o.*,a.action_plan,a.created_date as activity_date,a.added_by as assigned_by,c.name as caller from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join activity_log a on o.id=a.pid left join callers c on c.id=o.caller left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.status='Approved' and a.is_intern=0 and a.action_plan!='' $dat $search group by a.pid having (count(a.pid)>0) order by a.created_date desc";
}


//echo $sql; die;

$query = db_query($sql);

$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

$sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
//echo $sql; die;

$query = db_query($sql);

$results = array();
$i = 1;
while ($data = db_fetch_array($query)) {  // preparing an array
    //	print_r($data['is_read']); 
    $color = '#000';

    $nestedData = array();
    $nestedData[] = $i;

    //$status = getSingleresult("select is_read from lead_notification where title='Request BD to LC' and type_id=" . $data['id']);

    $nestedData[] =  "<a target='_blank' style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['r_name'] . '</a>';
    $nestedData[] =  "<a target='_blank' style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['r_user'] . '</a>';
    $nestedData[] = "<a target='_blank' style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['lead_type'] . '</a>';
    $nestedData[] = "<a target='_blank' style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['quantity'] . '</a>';
    $nestedData[] = "<a target='_blank' style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['caller'] . '</a>';
    $nestedData[] =  "<a target='_blank' style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['company_name'] . '</a>';
    $nestedData[] = "<a target='_blank' style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . date('d-m-Y', strtotime($data['approval_time'])) . '</a>';

    $nestedData[] = date('d-m-Y', strtotime($data['activity_date']));
    $nestedData[] = $data['action_plan'];

    $nestedData[] = getSingleresult("select action_plan from activity_log where is_intern=0 and pid='" . $data['id'] . "'  ORDER BY id DESC limit 1");

    $nestedData[] = getSingleresult("select name from users where id='" . $data['assigned_by'] . "'");

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
