<?php

include("includes/include.php");

// $d_from = $_POST['d_from'];
// $d_to = $_POST['d_to'];
$d_type = $_POST['d_type']= mysqli_real_escape_string($GLOBALS['dbcon'],htmlspecialchars($_POST['d_type']));
$d_from =$_POST['d_from']= preg_replace("([^0-9/] | [^0-9-])","",$_POST['d_from']);
$d_to =$_POST['d_to']= preg_replace("([^0-9/] | [^0-9-])","",$_POST['d_to']);
$d_from=mysqli_real_escape_string($GLOBALS['dbcon'],htmlentities($d_from));
$d_to=mysqli_real_escape_string($GLOBALS['dbcon'],htmlentities($d_to));

$col_data =  explode(",", $_POST['check_label']);  //checkbox data
$check_label =  $_POST['check_label'];
$campaign = intval($_POST['campaign']);
$validation_type = $_POST['validation_type'];

$results = [];

$requestData = $_REQUEST;

$d_type = str_replace("created_date", "o.created_date", $d_type); //replacing created_date
$d_type = str_replace("closed_date", "o.partner_close_date", $d_type); //replacing partner_close_date

if (!empty($d_type) && !empty($d_from) && !empty($d_to)) {
    if ($requestData['d_from'] == $requestData['d_to']) {
        $data = " and DATE(" . $d_type . ")='" . $requestData['d_from'] . "'";
    } else {
        $data = " and  (" . $d_type . " BETWEEN  '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "')";
    }
}

//print_r($industry_arr);
if ($requestData['industry']) {
    $data .= " and industry in ('" . $requestData['industry'] . "')";
}

if ($requestData['region']) {
    $data .= " and state in ('" . $requestData['region'] . "')";
}
if ($requestData['city']) {
    $data .= " and city in ('" . $requestData['city'] . "')";
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
    $data .= " and o.association_name in ('" . $requestData['association_name'] . "')";
}
if ($requestData['validation_type']) {
	$data .= " and o.validation_type='" . $requestData['validation_type'] . "'";
}

if(($_SESSION['user_type'] == 'USR') && ($_SESSION['role'] == 'TC')){
    $dat .= " and o.created_by='" . $_SESSION['user_id'] . "' ";
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

if (empty($d_type) && empty($_POST['industry']) && empty($_POST['region']) && empty($_POST['city'])&& !empty($requestData['search']['value']) && empty($_POST['campaign']) && empty($_POST['product']) && empty($_POST['product_type']) && empty($_POST['association_name'])) {

    $select_query =  personalRep_PartnerTableWithDate('orders', $check_label, $requestData['start'], $requestData['length'],$data,$_SESSION['team_id']);
   
} else if(empty($d_type) && empty($_POST['industry']) && empty($_POST['region']) && empty($_POST['city']) && empty($_POST['campaign']) && empty($_POST['product']) && empty($_POST['product_type'])&& empty($_POST['association_name'])){

    $select_query =   personalRep_PartnerData('orders', $check_label, $requestData['start'], $requestData['length'],$_SESSION['team_id']);

}else{

     $select_query = 
     personalRep_PartnerTableWithDate('orders', $check_label, $requestData['start'], $requestData['length'], $data,$_SESSION['team_id']);
    // "SELECT o.id,p.product_id,p.product_type_id,$check_label,s.name as state,si.name as sub_industry,i.name as industry,u.name as created_by FROM orders as o LEFT JOIN states as s ON o.state = s.id LEFT JOIN sub_industry as si ON o.sub_industry = si.id LEFT JOIN industry as i ON o.industry = i.id LEFT JOIN users as u ON o.created_by = u.id LEFT JOIN tbl_lead_product as p ON o.id=p.lead_id WHERE 1 $data and o.team_id =". $_SESSION['team_id'] ." GROUP by o.id ORDER By o.id Desc ";

    
}

// print_r($select_query);
$col_data = str_replace("o.status", "status", $col_data); //replacing status
$col_data = str_replace("o.created_date", "created_date", $col_data); //replacing created_date

$totalFiltered =mysqli_num_rows($select_query);// when there is no search parameter then total number rows = total number filtered rows.

$sql= db_query("select o.* from orders as o where 1 and o.team_id=".$_SESSION['team_id'] .$data ." group by o.id");
//print_r($sql);
$totalData = mysqli_num_rows($sql);
$totalFiltered = $totalData;

$j=$requestData['start']+1;

$i = 0;
$query_data = []; 
while ($query = db_fetch_array($select_query)) {
    $id = $query['id'];
    //print_r($query);die;
    for ($k = 0; $k < count($col_data); $k++) {
        if (array_search($col_data[$k], array_keys($query), true)) {
            $query_data[] = "<a target='_blank' style='color:#000' href='partner_view.php?id=".$id."'>".$query[$col_data[$k]];
            //print_r($query_data);                   
        }
    }
    $results[$i] = $query_data;
    unset($query_data);
    //$query_data = array();
    $i++;
   // print_r($query_data);
}

$json_data = array(
    "draw"            => intval($requestData['draw']),
    "recordsTotal"    => intval($totalData),  // total number of records
    "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
    "data"            => $results   // total data array
);

//print_r($json_data); die;
echo json_encode($json_data);  // send data as json format
