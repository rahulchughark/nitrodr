<?php include('includes/include.php');
/* Database connection end */

	$u_cond=" and assigned_to='".$_SESSION['user_id']."' ";
$requestData= $_REQUEST;
$sql = "select * ";
$sql.=" FROM selfreview_tasks where 1 ".$u_cond;
//echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
$sql.=" AND ( title LIKE '%".$requestData['search']['value']."%' )";    
	// $sql.=" OR r_name LIKE '%".$requestData['search']['value']."%' ";
    // $sql.=" OR lead_type LIKE '%".$requestData['search']['value']."%' ";
	// $sql.=" OR quantity LIKE '%".$requestData['search']['value']."%' ";
	// $sql.=" OR company_name LIKE '%".$requestData['search']['value']."%' ";
	// $sql.=" OR eu_email LIKE '%".$requestData['search']['value']."%' ";
	// $sql.=" OR status LIKE '%".$requestData['search']['value']."%' ";
	// $sql.=" OR eu_mobile LIKE '%".$requestData['search']['value']."%'";
	
	//$sql.=" OR eu_mobile LIKE '".$requestData['search']['value']."%' )";
}
$sql.=$dat;
$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query);
$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ".$columnName." ".$columnSortOrder."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
//echo $sql; die;
$query=db_query($sql);

$results = array();
$i=$requestData['start']+1;
while($data=db_fetch_array($query)) {  // preparing an array
 
    $nestedData['id']=$i;   
    $nestedData['r_user'] = getSingleresult("select name from users where id='".$data['assigned_from']."'");
    $nestedData['user'] = getSingleresult("select name from users where id='".$data['assigned_to']."'");
	$nestedData['lead_type'] ='LC';
	$nestedData['company_name'] = getSingleresult("select company_name from orders where id='".$data['order_id']."'");
    $nestedData['title'] = $data['title'];
    $nestedData['created_date'] = date('d-m-Y',strtotime($data['created_date']));
    $nestedData['status']=(($data['status']=='Pending')?'<span class="text-warning">Pending</span>':'<span class="text-success">Done</span>');
    if($data['status']=='Pending')
    $nestedData['action']='<a href="javascript:void(0);" onclick="complete_srt('.$data['id'].')" ><i style="font-size:30px" class="text-warning mdi mdi-comment-alert"></i></a> &nbsp;<a href="'.(($_SESSION['user_type']=='CLR')?'caller_view.php':'partner_view.php').'?id='.$data['order_id'].'" ><i style="font-size:30px" class="text-warning mdi mdi-eye"></i></a>';
    else
    {
        $nestedData['action']='<span class="text-success" style="font-size:25px;font-weight:900"><i class="mdi mdi-check"></i></span>  &nbsp;<a href="'.(($_SESSION['user_type']=='CLR')?'caller_view.php':'partner_view.php').'?id='.$data['order_id'].'" ><i style="font-size:30px" class="text-success mdi mdi-eye"></i></a>';  
    }
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
