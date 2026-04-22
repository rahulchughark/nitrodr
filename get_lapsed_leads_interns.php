<?php include('includes/include.php');
/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
$pattern = "[^a-zA-Z]";
$requestData['search']['value']= mysqli_real_escape_string($GLOBALS['dbcon'],htmlspecialchars($requestData['search']['value']));
$requestData['length'] = intval($requestData['length']);
$requestData['start'] = intval($requestData['start']);
$requestData['order'][0]['dir'] =preg_replace($pattern,'',$requestData['order'][0]['dir']);
$columnIndex=$requestData['order'][0]['column'] =intval($requestData['order'][0]['column']);

$requestData['columns'][$columnIndex]['data'] =preg_replace($pattern,'',$requestData['columns'][$columnIndex]['data']);


	if($requestData['d_from'] && $requestData['d_to'] )
	{
	if($requestData['dtype']=='created')
	{
			if($requestData['d_from'] == $requestData['d_to'])
			{
			$dat=" and DATE(l.created_date)='".$requestData['d_from']."'";	
			} else {
			$dat=" and DATE(l.created_date)>='".$requestData['d_from']."' and DATE(l.created_date)<='".$requestData['d_to']."'";	
			}
	}
}


// getting total number records without any search
$sql = "select l.id,l.quantity,l.company_name,l.eu_email,l.eu_mobile,l.parent_company,l.landline,industry.name as industry,states.name as state,l.city,l.eu_name ";

$sql.=" FROM lapsed_orders as l left join industry on l.industry=industry.id left join states on l.state=states.id where 1 and l.license_type='Commercial'".$dat.$vir_cond;
//echo $sql; die;
$sql .= " GROUP BY l.id";
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "select l.id,l.quantity,l.company_name,l.eu_email,l.eu_mobile,l.parent_company,l.landline,industry.name as industry,states.name as state,l.city,l.eu_name ";
$sql.=" FROM lapsed_orders as l left join industry on l.industry=industry.id left join states on l.state=states.id WHERE l.license_type='Commercial'";

if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( l.quantity LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR l.company_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR l.eu_email LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR l.parent_company LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR l.eu_mobile LIKE '%".$requestData['search']['value']."%' )";

}
$sql.=$dat.$vir_cond;
//echo $sql; die;
$sql .= " GROUP BY l.id";

$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc



$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER by l.id desc   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
//echo $sql; die;
$query=db_query($sql);

$results = array();
$i=$requestData['start']+1;
while($data=db_fetch_array($query)) {  // preparing an array
if($data['sfdc_exp']==1 && $_SESSION['sales_manager']!=1)
{
	$color='#000';
}
else
{
	$color='#000';
}
	$nestedData=array(); 
	$nestedData['id']=$i;
	$nestedData['company_name']   =  $data['company_name'];
    $nestedData['parent_company'] =  $data['parent_company'] ;
    $nestedData['cust_name']      =  $data['eu_name'] ;
    $nestedData['industry']       =  $data['industry'] ;
    $nestedData['cust_number']    =  $data['eu_mobile'] ;
    $nestedData['email']          =  $data['eu_email'] ;
    $nestedData['city']           =  $data['city'] ;
    $nestedData['state']          =  $data['state'] ;
	$nestedData['quantity']       =  $data['quantity'];
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
