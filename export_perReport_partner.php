<?php

include('includes/include.php');
$requestData = $_GET;
$check_list = $_GET['check_list']?implode("','", $_GET['check_list']):'';
$check_label = $_GET['check_list']?implode(',', $_GET['check_list']):'';
$col_data =  explode(",", $check_label);  //checkbox data
$d_type = $requestData['date_type']= mysqli_real_escape_string($GLOBALS['dbcon'],htmlspecialchars($requestData['date_type']));
$industry_arr = $requestData['industry'] ? implode("','",$requestData['industry']):'';
$region_arr = $requestData['region'] ? implode("','",$requestData['region']):'';
$city_arr = $requestData['city'] ? implode("','",$requestData['city']):'';
$association_arr = $requestData['association_name'] ? implode("','",$requestData['association_name']):'';
$campaign = intval($requestData['campaign']);

$d_type = str_replace("created_date", "o.created_date", $d_type); //replacing created_date
$d_type = str_replace("closed_date", "o.partner_close_date", $d_type); //replacing partner_close_date


$requestData['d_from']= preg_replace("([^0-9/] | [^0-9-])","",$requestData['d_from']);
$requestData['d_to']= preg_replace("([^0-9/] | [^0-9-])","",$requestData['d_to']);
$requestData['d_from']=mysqli_real_escape_string($GLOBALS['dbcon'],htmlentities($requestData['d_from']));
$requestData['d_to']=mysqli_real_escape_string($GLOBALS['dbcon'],htmlentities($requestData['d_to']));


if ($requestData['d_from'] && $requestData['d_to'] && $d_type) {
    if ($requestData['d_from'] == $requestData['d_to']) {
        $data = " and DATE('" . $d_type . "')='" . $requestData['d_from'] . "'";
    } else {
        $data = " and  (" . $d_type . " BETWEEN  '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "')";
    }
}

if ($requestData['industry']) {
    $data .= " and industry in ('" . $industry_arr . "')";
}

if ($requestData['region']) {
    $data .= " and state in ('" . $region_arr . "')";
}
if ($requestData['city']) {
    $data .= " and city in ('" . $city_arr . "')";
}
if ($requestData['campaign']) {
    $data .= " and campaign_type = '" . $requestData['campaign'] . "' ";
}
if ($requestData['association_name']) {
    $data .= " and o.association_name in ('" . $association_arr . "')";
}

if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$data .= " AND ( o.code LIKE '%" . $requestData['search']['value'] . "%' ";
	$data .= " OR o.r_name LIKE '%" . $requestData['search']['value'] . "%' ";
    $data .= " OR o.state LIKE '%" . $requestData['search']['value'] . "%' ";
    $data .= " OR o.city LIKE '%" . $requestData['search']['value'] . "%' ";
    $data .= " OR o.industry LIKE '%" . $requestData['search']['value'] . "%' ";
    $data .= " OR o.sub_industry LIKE '%" . $requestData['search']['value'] . "%' ";
	$data .= " OR o.lead_type LIKE '%" . $requestData['search']['value'] . "%' ";
	$data .= " OR o.quantity LIKE '%" . $requestData['search']['value'] . "%' ";
	$data .= " OR o.company_name LIKE '%" . $requestData['search']['value'] . "%' ";
	$data .= " OR o.eu_email LIKE '%" . $requestData['search']['value'] . "%' ";
	$data .= " OR o.status LIKE '%" . $requestData['search']['value'] . "%' ";
	$data .= " OR o.eu_mobile LIKE '%" . $requestData['search']['value'] . "%' )";

	//$sql.=" OR eu_mobile LIKE '".$requestData['search']['value']."%' )";
}

if (empty($d_type) && empty($_GET['industry']) && empty($_GET['region']) && empty($_GET['city'])&& !empty($requestData['search']['value']) && empty($_GET['campaign'])&& empty($_GET['association_name']) ) {

    $select_query = db_query("SELECT $check_label,o.association_name,s.name as state,si.name as sub_industry,i.name as industry,u.name as created_by  FROM orders as o
    LEFT JOIN states as s ON o.state = s.id
    LEFT JOIN sub_industry as si ON o.sub_industry = si.id
    LEFT JOIN industry as i ON o.industry = i.id
    LEFT JOIN users as u ON o.created_by = u.id
    WHERE 1 and o.team_id = ".$_SESSION['team_id']." $data 
    ORDER By o.id Desc");

}else if(empty($d_type) && empty($_GET['industry']) && empty($_GET['region']) && empty($_GET['city']) && empty($_GET['campaign'])&& empty($_GET['association_name'])){
    $select_query = db_query("SELECT $check_label,o.association_name,s.name as state,si.name as sub_industry,i.name as industry,u.name as created_by FROM orders as o
    LEFT JOIN states as s ON o.state = s.id
    LEFT JOIN sub_industry as si ON o.sub_industry = si.id
    LEFT JOIN industry as i ON o.industry = i.id
    LEFT JOIN users as u ON o.created_by = u.id
    WHERE 1=1 and o.team_id =".$_SESSION['team_id']." ORDER By o.id Desc");
} else {

    $select_query = db_query("SELECT $check_label,o.association_name,s.name as state,si.name as sub_industry,i.name as industry,u.name as created_by  FROM orders as o
    LEFT JOIN states as s ON o.state = s.id
    LEFT JOIN sub_industry as si ON o.sub_industry = si.id
    LEFT JOIN industry as i ON o.industry = i.id
    LEFT JOIN users as u ON o.created_by = u.id
    WHERE 1 and o.team_id = ".$_SESSION['team_id']." $data 
    ORDER By o.id Desc");
    
}

$col_data = str_replace("o.status", "status", $col_data); //replacing status
$col_data = str_replace("o.created_date", "created_date", $col_data); //replacing created_date
$query_data = [];
$rowCount = mysqli_num_rows($select_query);

if ($rowCount > 0) {
    $delimiter = ",";
    $filename = "PartnerPersonalReport" . date('Y-m-d') . ".csv";
    ob_start();
    //create a file pointer
    $f = fopen('php://output', 'w');

    //set column headers
    $sql_select = personalReport_tableLabels('orderPartner_pivot', $check_list);
    foreach ($sql_select as $row) {
        $fields[] = $row['field_label'];
    }

    fputcsv($f, $fields, $delimiter);
    //output each row of the data, format line as csv and write to file pointer
    while ($row = db_fetch_array($select_query)) {

        @extract($row);
        for ($k = 0; $k < count($col_data); $k++) {
            if (array_search($col_data[$k], array_keys($row), true)) {
                $query_data[] = $row[$col_data[$k]];                        
            }            
        }     
        fputcsv($f, $query_data, $delimiter); 
        unset($query_data);
    }
     //print_r($query_data);die;
    //fputcsv($f, $query_data, $delimiter);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    fpassthru($f);
    ob_end_flush();
}
// else {
// header("Location: orders.php?m=nodata");    
// }
// exit();
