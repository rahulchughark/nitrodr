<?php include('includes/include.php');
/* Database connection end */
if($_SESSION['user_type']=='USR')
{
	//$u_cond=" and created_by='".$_SESSION['user_id']."' ";
}

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
if($requestData['d_from'] && $requestData['d_to'])
{

    if($requestData['d_from'] == $requestData['d_to'])
    {
    $dat=" and DATE(created_date)='".$requestData['d_from']."'";	
    } else {
    $dat=" and DATE(created_date)>='".$requestData['d_from']."' and DATE(created_date)<='".$requestData['d_to']."'";	
    }   
}
$sql = "select * ";
$sql.=" FROM call_quality where 1 ".$dat;
//echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "select * ";
$sql.=" FROM call_quality WHERE 1=1 ".$dat;
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( company_name LIKE '".$requestData['search']['value']."%' ";    
	//$sql.=" OR r_name LIKE '".$requestData['search']['value']."%' ";

	 
	//$sql.=" OR lead_type LIKE '".$requestData['search']['value']."%' ";
	//$sql.=" OR quantity LIKE '".$requestData['search']['value']."%' ";
	//$sql.=" OR company_name LIKE '".$requestData['search']['value']."%' ";
	//$sql.=" OR eu_email LIKE '".$requestData['search']['value']."%' ";
	//$sql.=" OR status LIKE '".$requestData['search']['value']."%' ";
	//$sql.=" OR eu_mobile LIKE '".$requestData['search']['value']."%' )";
	
	$sql.=" OR customer_phone LIKE '".$requestData['search']['value']."%' )";
}
$sql.=$dat;
$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY id desc  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
//echo $sql; die;
$query=db_query($sql);

$results = array();
$i=$requestData['start']+1;
while($data=db_fetch_array($query)) {  // preparing an array
 
	$color='#000';
 
	$nestedData=array(); 
	$nestedData[]=$i;
	 
	$nestedData[] = "<a style='display:block;color:".$color."' href='call_view.php?id=".$data['id']."'>".getSingleresult("select name from callers where id =".$data['caller']).'</a>';
	$nestedData[] = "<a style='display:block;color:".$color."' href='call_view.php?id=".$data['id']."'>".$data['extension'].'</a>';
	if($data['profiling_call_type'])
	{
		$add_profile="(".$data['profiling_call_type'].")";
	}
	$nestedData[] = "<a style='display:block;color:".$color."' href='call_view.php?id=".$data['id']."'>".$data['call_type'].	$add_profile.'</a>';
		//$nestedData[] = "<a style='display:block;color:".$color."' href='call_view.php?id=".$data['id']."'>"..'</a>';

    $nestedData[] = "<a style='display:block;color:".$color."' href='call_view.php?id=".$data['id']."'>".$data['company_name'].'</a>';
    
    $nestedData[] = "<a style='display:block;color:".$color."' href='call_view.php?id=".$data['id']."'><div class='progress'>
    <div class='progress-bar bg-dark active progress-bar-striped' role='progressbar' style='width: ".$data['total_score']."%;height:15px;' role='progressbar'> <span style='font-weight:900'>".$data['total_score']."% </span></div>
    </div></a>";
    $nestedData[] = '<audio style="width:150px; height: 20px;" controls>
    <source src='.SITE_PATH.$data['call_attachment'].' type="audio/wav">
    Your browser does not support the audio tag.
  </audio>';
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

?>
