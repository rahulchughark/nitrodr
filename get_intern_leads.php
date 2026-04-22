<?php include('includes/include.php');
/* Database connection end */
// if($_SESSION['user_type']=='INTERN'){
// 	$u_cond=" and r.team_id='".$_SESSION['team_id']."' ";
// }
	

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
$pattern = "[^a-zA-Z]";
$requestData['search']['value']= mysqli_real_escape_string($GLOBALS['dbcon'],htmlspecialchars($requestData['search']['value']));
$requestData['length'] = intval($requestData['length']);
$requestData['start'] = intval($requestData['start']);

$requestData['order'][0]['dir'] =preg_replace($pattern,'',$requestData['order'][0]['dir']);
$columnIndex=$requestData['order'][0]['column'] =intval($requestData['order'][0]['column']);
$requestData['columns'][$columnIndex]['data'] =htmlentities($requestData['columns'][$columnIndex]['data'],ENT_QUOTES);

$requestData['d_from']= preg_replace("([^0-9/] | [^0-9-])","",$requestData['d_from']);
$requestData['d_to']= preg_replace("([^0-9/] | [^0-9-])","",$requestData['d_to']);
$requestData['d_from']=mysqli_real_escape_string($GLOBALS['dbcon'],htmlentities($requestData['d_from']));
$requestData['d_to']=mysqli_real_escape_string($GLOBALS['dbcon'],htmlentities($requestData['d_to']));


if($requestData['d_from'] && $requestData['d_to'])
{

    if($requestData['d_from'] == $requestData['d_to'])
    {
    $dat=" and DATE(r.created_date)='".$requestData['d_from']."'";	
    } else {
    $dat=" and DATE(r.created_date)>='".$requestData['d_from']."' and DATE(r.created_date)<='".$requestData['d_to']."'";	
    }   
}


if($requestData['my_leads']=='yes'){
	$dat .= " and r.created_by = ".$_SESSION['user_id'];
}

if ($requestData['users']) {
	
	$dat .= " and r.created_by in ('" . stripslashes($requestData['users']) . "')";
}
if ($requestData['region']) {
	
	$dat .= " and r.region in ('" . stripslashes($requestData['region']) . "')";
}
if ($requestData['city']) {
	
	$dat .= " and r.city in ('" . stripslashes($requestData['city']) . "')";
}
if($requestData['industry']=='DTP'){
	$dat .= " and industry.log_status=1";
}
if($requestData['industry']=='Other'){
	$dat .= " and industry.log_status=0";
}

// getting total number records without any search
$sql = "select r.r_email,r.created_date,r.team_id,r.id,r.quantity,r.company_name,r.eu_name,r.eu_email,r.eu_mobile,r.r_user,r.source,r.created_by,industry.name as industry,states.name as state,r.region,r.city ";

$sql.=" FROM raw_leads as r left join industry on r.industry=industry.id left join states on r.state=states.id where 1 and is_intern=1 ".$dat.$u_cond ;
//echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "select r.r_email,r.created_date,r.team_id,r.id,r.quantity,r.company_name,r.eu_name,r.eu_email,r.eu_mobile,r.r_user,r.source,r.created_by,industry.name as industry,states.name as state,r.region,r.city ";

$sql.=" FROM raw_leads as r left join industry on r.industry=industry.id left join states on r.state=states.id WHERE 1=1 and is_intern=1 ".$u_cond ;

if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	// $sql.=" AND ( r_name LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" AND (r.quantity LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR r.company_name LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR r.eu_email LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR r.eu_mobile LIKE '".$requestData['search']['value']."%' )";

}
$sql.=$dat;
$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY r.id desc  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
//echo $sql; die;
$query=db_query($sql);

$results = array();
$i=$requestData['start']+1;
while($data=db_fetch_array($query)) {  // preparing an array
 
	$color='#000';
 
	$nestedData=array(); 
	$nestedData[]=$i;
	 
	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='intern_view.php?id=".$data['id']."'>".($data['source']?$data['source']:'N/A').'</a>';
	
	$nestedData[] =($data['r_user']?$data['r_user']:'N/A');

	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='intern_view.php?id=".$data['id']."'>".$data['quantity'].'</a>';

	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='intern_view.php?id=".$data['id']."'>".$data['company_name'].'</a>';
	
	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='intern_view.php?id=".$data['id']."'>".$data['eu_name'].'</a>';

	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='intern_view.php?id=".$data['id']."'>".$data['eu_email'].'</a>';

	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='intern_view.php?id=".$data['id']."'>".$data['eu_mobile'].'</a>';

	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='intern_view.php?id=".$data['id']."'>".$data['industry'].'</a>';

	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='intern_view.php?id=".$data['id']."'>".$data['region'].'</a>';

	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='intern_view.php?id=".$data['id']."'>".$data['state'].'</a>';
	$nestedData[] = date('d-m-Y',strtotime($data['created_date']));
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
