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
$campaign = intval($requestData['campaign']);
$association_arr = $requestData['association_name'] ? implode("','",$requestData['association_name']):'';

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
    $data .= " and o.campaign_type = '" . $requestData['campaign'] . "' ";
}
if ($requestData['product']) {
    $data .= " and p.product_id='" . $requestData['product'] . "'";
}
if ($requestData['product_type']) {
    $data .= " and p.product_type_id='" . $requestData['product_type'] . "'";
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

if (empty($d_type) && empty($_POST['industry']) && empty($_POST['region']) && empty($_POST['city'])
&& empty($_POST['campaign']) && empty($_POST['product']) && empty($_POST['product_type']) && !empty($requestData['search']['value'])&& empty($_GET['association_name'])) {
    
    $select_query = db_query("SELECT $check_label,o.association_name,s.name as state,si.name as sub_industry,i.name as industry,u.name as created_by  FROM orders as o
    LEFT JOIN states as s ON o.state = s.id
    LEFT JOIN sub_industry as si ON o.sub_industry = si.id
    LEFT JOIN industry as i ON o.industry = i.id
    LEFT JOIN users as u ON o.created_by = u.id
    WHERE 1 $data GROUP by o.id
    ORDER By o.id Desc");

}else if(empty($d_type) && empty($_POST['industry']) && empty($_POST['region']) && empty($_POST['city']) && empty($_POST['campaign'])&& empty($_POST['product']) && empty($_POST['product_type'])  && empty($_GET['association_name'])){

    $select_query = db_query("SELECT $check_label,o.association_name,s.name as state,si.name as sub_industry,i.name as industry,u.name as created_by FROM orders as o
    LEFT JOIN states as s ON o.state = s.id
    LEFT JOIN sub_industry as si ON o.sub_industry = si.id
    LEFT JOIN industry as i ON o.industry = i.id
    LEFT JOIN users as u ON o.created_by = u.id
    WHERE 1=1 GROUP by o.id ORDER By o.id Desc");
} else {

    $select_query = db_query("SELECT $check_label,o.association_name,s.name as state,si.name as sub_industry,i.name as industry,u.name as created_by  FROM orders as o
    LEFT JOIN states as s ON o.state = s.id
    LEFT JOIN sub_industry as si ON o.sub_industry = si.id
    LEFT JOIN industry as i ON o.industry = i.id
    LEFT JOIN users as u ON o.created_by = u.id
    WHERE 1 $data GROUP by o.id
    ORDER By o.id Desc");
    // "SELECT $check_label,o.association_name,s.name as state,si.name as sub_industry,i.name as industry,u.name as created_by  FROM orders as o
    // LEFT JOIN states as s ON o.state = s.id
    // LEFT JOIN sub_industry as si ON o.sub_industry = si.id
    // LEFT JOIN industry as i ON o.industry = i.id
    // LEFT JOIN users as u ON o.created_by = u.id
    // LEFT JOIN tbl_lead_product as p ON o.id=p.lead_id
    // WHERE 1 $data GROUP by o.id
    // ORDER By o.id Desc";

    //$select_query = personalReport_TableDataWithDate('orders', $check_label, $requestData['start'], $requestData['length'], $data);
}
//print_r($select_query);die;
$col_data = str_replace("o.status", "status", $col_data); //replacing status
$col_data = str_replace("o.created_date", "created_date", $col_data); //replacing created_date
$query_data = [];
$rowCount = mysqli_num_rows($select_query);

if ($rowCount > 0) {
    $delimiter = ",";
    $filename = "PersonalReport" . date('Y-m-d') . ".csv";

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    //create a file pointer
    $f = fopen('php://output', 'w');

    //set column headers
    $sql_select = personalReport_tableLabels('order_pivot', $check_list);
    foreach ($sql_select as $row) {
        $fields[] = $row['field_label'];
    }
    //$fields = array('Code','Partners' ,'Partner_email','Partner Users', 'Source', 'Lead Type', 'Company name','Parent company', 'Landline','Industry','	Sub Industry','Region','Address','Pincode','State','City','Country','eu_name','eu_email','eu_landline','department','eu_mobile','eu_designation','eu_role','account_visited','visit_remarks','confirmation_from','license_type','quantity','created_by','created_date','status','stage');
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
    
    fclose($f);
}
// else {
// header("Location: orders.php?m=nodata");    
// }
// exit();
