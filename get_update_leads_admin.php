<?php include('includes/include.php');
/* Database connection end */

//print_r($_REQUEST['stage']); die;

$requestData= $_REQUEST;
$pattern = "[^a-zA-Z]";
$requestData['search']['value']= mysqli_real_escape_string($GLOBALS['dbcon'],htmlspecialchars($requestData['search']['value']));
$requestData['length'] = intval($requestData['length']);
$requestData['start'] = intval($requestData['start']);
$requestData['partners'] = intval($requestData['partners']);
$requestData['stage']=mysqli_real_escape_string($GLOBALS['dbcon'],htmlspecialchars($requestData['stage']));
$columnIndex=$requestData['order'][0]['column'] =intval($requestData['order'][0]['column']);
$requestData['order'][0]['dir'] =preg_replace($pattern,'',$requestData['order'][0]['dir']);



if($requestData['d_from'] && $requestData['d_to'])
{

    if($requestData['d_from'] == $requestData['d_to'])
    {
    $dat=" and DATE(created_date)='".$requestData['d_from']."'";	
    } else {
    $dat=" and DATE(created_date)>='".$requestData['d_from']."' and DATE(created_date)<='".$requestData['d_to']."'";	
    }   
}
if($requestData['lead_type'])
{
    $dat.=" and lead_type='".$requestData['lead_type']."'";
}
 
if($requestData['status'])
{
    $dat.=" and status='".$requestData['status']."'";
}
if($requestData['partner'])
{
    $dat.=" and team_id='".$requestData['partner']."'";
}
if($requestData['partners'])
{
    $q.=" and reseller in ('".$requestData['partners']."')";
}
 if($requestData['stage']!=' ')
{
    $q.=" and stage in ('".$requestData['stage']."')";
}
 
if($_SESSION['sales_manager']==1)
{
	$q.=" and reseller in (".$_SESSION['access'].") ";
}
$sql = "select * ";
$sql.=" FROM upgrade_leads where 1".$q;
//echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "select * ";
$sql.=" FROM upgrade_leads where 1".$q;
$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 

/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
//echo $sql; die;

if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( eu_name LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR eu_address LIKE '".$requestData['search']['value']."%' ";

	 
	$sql.=" OR eu_contact LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR eu_designation LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR contact_email LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR landline_number LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR mobile_number LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR quantity LIKE '%".$requestData['search']['value']."%' )";


}

$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc

if(!$requestData['order'][0]['column'])
{
	$sql.=" ORDER BY id desc  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
}
else{
$sql.=" ORDER BY ".$columnName." ".$columnSortOrder."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
}
$query=db_query($sql);

$results = array();
$i=1;
while($data=db_fetch_array($query)) {  // preparing an array
 
	$color='#000';
 
	$nestedData=array(); 
	$nestedData['id']=$i;
	$nestedData['name'] = "<a style='display:block;color:".$color."' href='upgrade_admin_view.php?id=".$data['id']."'>".getSingleresult("select name from partners where id =".$data['reseller']).'</a>';
	$nestedData['eu_name'] = "<a style='display:block;color:".$color."' href='upgrade_admin_view.php?id=".$data['id']."'>".$data['eu_name'].'</a>';
	$nestedData['eu_address'] = "<a  style='display:block;color:".$color."' href='upgrade_admin_view.php?id=".$data['id']."'>".$data['eu_address'].'</a>';
	$nestedData['eu_contact'] = "<a style='display:block;color:".$color."' href='upgrade_admin_view.php?id=".$data['id']."'>".$data['eu_contact'].'</a>';
	$nestedData['contact_email'] = "<a style='display:block;color:".$color."' href='upgrade_admin_view.php?id=".$data['id']."'>".$data['contact_email'].'</a>';
	$nestedData['mobile_number'] = "<a style='display:block;color:".$color."'  href='upgrade_admin_view.php?id=".$data['id']."'>".$data['mobile_number'].'</a>';
	$nestedData['quantity'] = "<a style='display:block;color:".$color."'  href='upgrade_admin_view.php?id=".$data['id']."'>".$data['quantity'].'</a>';
	$nestedData['stage'] ="<a style='display:block;color:".$color."'  href='upgrade_admin_view.php?id=".$data['id']."'>".(($data['stage']=='Closed Lost')?$data['stage'].'('.$data['add_comment'].')':$data['stage']).'</a>';
	   
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

?>
