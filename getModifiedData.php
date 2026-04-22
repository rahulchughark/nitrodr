<?php include('includes/include.php');
/* Database connection end */


if ($_SESSION['sales_manager'] == 1) {
	$vir_cond = " and o.team_id in (" . $_SESSION['access'] . ") ";
}
// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
$pattern = "[^a-zA-Z]";
$requestData['search']['value']= mysqli_real_escape_string($GLOBALS['dbcon'],htmlspecialchars($requestData['search']['value']));
$requestData['length'] = intval($requestData['length']);
$requestData['start'] = intval($requestData['start']);
$requestData['order'][0]['dir'] =preg_replace($pattern,'',$requestData['order'][0]['dir']);
$columnIndex=$requestData['order'][0]['column'] =intval($requestData['order'][0]['column']);
$requestData['columns'][$columnIndex]['data'] =htmlentities($requestData['columns'][$columnIndex]['data'],ENT_QUOTES);


if($requestData['d_from'] && $requestData['d_to'])
{
    if($requestData['d_from'] == $requestData['d_to'])
    {
    $dat .=" and DATE(lm.created_date)='".$requestData['d_from']."'";	
    } else {
    $dat .=" and DATE(lm.created_date)>='".$requestData['d_from']."' and DATE(lm.created_date)<='".$requestData['d_to']."'";	
    }   
}

if($requestData['dtype'])
    {
    $dat .=" and lm.type='".$requestData['dtype']."'";	
    }

// getting total number records without any search
$sql = "select lm.*,o.r_user,o.company_name,o.team_id,u.name as user FROM lead_modify_log as lm left join orders as o on lm.lead_id=o.id left join users as u on lm.created_by=u.id where lm.raw_id=0 ".$dat . $vir_cond;
//echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$sql = "select lm.*,o.r_user,o.company_name,o.team_id,u.name as user FROM lead_modify_log as lm left join orders as o on lm.lead_id=o.id left join users as u on lm.created_by=u.id WHERE lm.raw_id=0 " . $vir_cond;

if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	// $sql.=" AND ( r_name LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" AND ( o.company_name LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR r.eu_email LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR r.eu_mobile LIKE '".$requestData['search']['value']."%' )";

}
$sql.=$dat;
$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY lm.id desc  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
//echo $sql; die;
$query=db_query($sql);

$results = array();
$i=$requestData['start']+1;
while($data=db_fetch_array($query)) {  // preparing an array
 
	$color='#000';
 
	$nestedData=array(); 
	$nestedData[]=$i;
	 
	$nestedData[] = ($data['r_user']?$data['r_user']:'N/A');
	
	$nestedData[] = ($data['user']?$data['user']:'N/A');

	$nestedData[] =($data['company_name']?$data['company_name']:'N/A');
    $nestedData[] =($data['type']?$data['type']:'N/A');
	$nestedData[] =($data['previous_name']?$data['previous_name']:'N/A');

	$nestedData[] = $data['modify_name'];
	
    $nestedData[] = date('d-m-Y',strtotime($data['created_date']));
    $nestedData[] = date('H:i:s',strtotime($data['created_date']));
	$results[] = $nestedData;
$i++;
}
//print_r($results); die;
 

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $results   // total data array
			);

echo json_encode($json_data);  // send data as json format
