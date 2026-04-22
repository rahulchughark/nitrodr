<?php include('includes/include.php');
/* Database connection end */
if($_SESSION['user_type']=='USR' || $_SESSION['user_type']=='MNGR')
{
	$u_cond=" and created_by='".$_SESSION['user_id']."' ";
}
$sr_date_to= date('Y-m-d',strtotime("-2 days"));
$sr_date_from= '2019-11-01';
// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
 

// getting total number records without any search
$sql = "select * ";
$sql.=" FROM orders where 1 and id not in (select order_id from selfreview_tasks where status='Pending') and dvr_flag=0 and lead_type='LC' and status='Approved' and ((created_date<='".$sr_date_to."' and created_date>='".$sr_date_from."') or (prospecting_date<='".$sr_date_to."' and prospecting_date>='".$sr_date_from."')) and  stage not in ('Post Profiling','Prospecting','Review','Action','Customer Connect','OEM Billing','Billed To Other Re-Seller','Closed Lost','Hold License Certificate/Copy','Product Presentation','Product Presentation','Product POC (Evaluation)') and license_type='Commercial' and team_id='".$_SESSION['team_id']."' ".$u_cond;
//echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "select * ";
$sql.=" FROM orders WHERE 1=1 and id not in (select order_id from selfreview_tasks where status='Pending') and dvr_flag=0 and lead_type='LC' and status='Approved'  and ((created_date<='".$sr_date_to."' and created_date>='".$sr_date_from."') or (prospecting_date<='".$sr_date_to."' and prospecting_date>='".$sr_date_from."')) and stage not in ('Post Profiling','Prospecting','Review','Action','Customer Connect','OEM Billing','Billed To Other Re-Seller','Closed Lost','Hold License Certificate/Copy','Product Presentation','Product Presentation','Product POC (Evaluation)') and license_type='Commercial' and team_id='".$_SESSION['team_id']."' ".$u_cond;
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( code LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR r_name LIKE '%".$requestData['search']['value']."%' ";

	 
	$sql.=" OR lead_type LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR quantity LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR company_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR eu_email LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR status LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR eu_mobile LIKE '%".$requestData['search']['value']."%' )";
	
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
 
	$color='#000';
 
	$nestedData=array(); 
	$nestedData['id']=$i;
	$ncdate=strtotime(date('Y-m-d'));
	$closeDate=strtotime($data['close_time']);
	 
	$nestedData['r_user'] = $data['r_user']; 
	$nestedData['lead_type'] = $data['lead_type'];
	$nestedData['quantity'] = $data['quantity'];
	$nestedData['company_name'] = $data['company_name'];
	 
	$nestedData['created_date'] = date('d-m-Y',strtotime($data['created_date']));
	  if($data['status']=='Approved')
												{
													$ncdate=strtotime(date('Y-m-d'));
													$closeDate=strtotime($data['close_time']);
													if($ncdate>$closeDate)
													{
														$dayspassedafterExpired = ceil(abs($ncdate - $closeDate) / 86400);
														$daysLeft='<span style=color:red;">('.$dayspassedafterExpired.' Days Passed)</span>';
														 
													if($dayspassedafterExpired<=30) {
														 
													  $daysLeft.='<a href="javascript:void(0)" title="Re-Log" onclick="relog('.$data['id'].')"> <i style="font-size:18px" class="mdi mdi-refresh"></i></a>';
													  $check_valid=1;
													$nestedData['status']='<span style="color:red">Expired</span> '.$daysLeft;
													}
													else
													{
														$check_valid=1;
														$nestedData['status']='<span style="color:red">Expired</span> '.$daysLeft;
													}
													}

													else
													{
														$ncdate=strtotime(date('Y-m-d'));
													$closeDate=strtotime($data['close_time']);
														
														$remaining_days=ceil(($closeDate-$ncdate)/84600);
														$daysLeft='<span style="color:green">Days Left- '.$remaining_days.'</span>';
													$nestedData['status']='<span style="color:green">Qualified</span> '.$daysLeft;	
													}
													 
												
												}
												else if($data['status']=='Cancelled')
												{
												 $nestedData['status']='<span class="text-danger">Unqualified('.$data['reason'].')</span>';
													
												}
												else if($data['status']=='Pending')
													
													{
														$nestedData['status']= 'Pending';
													}
													else if($data['status']=='Undervalidation')
													{
													 $nestedData['status']= '<span class="text-warning">Re-Submission Required</span>';
													}else if($data['status']=='On-Hold')
													{
														$nestedData['status']= '<span class="text-blue">On-Hold</span>';
													}
													else if($data['status']=='For Validation')
													{
														$nestedData['status']= '<span class="text-themecolor">For Validation</span>';
													};
	 

													if($data['status'] == 'Approved' && !$check_valid){
														if(getSingleresult("select count(id) from  lead_review where is_review=1 and lead_id='".$data['id']."'"))
														{
															$nestedData['stage']='<span class="text-danger">Under Review</span>';
														}
														else
														{
														$ids="'but".$data['id']."'";
														$nestedData['stage'] = ($data['stage']?$data['stage']:'N/A');
														}
													} else if($data['status'] == 'Approved' && $check_valid)
													{
														$nestedData['stage'] = ($data['stage']?$data['stage']:'N/A');
													}
													
													else {
													   $nestedData['stage'] = '';
										   
												   }	
 
	/* $nestedData[] ="<a style='display:block;color:".$color."'  href='renewal_admin_view.php?id=".$data['id']."'>".(($data['stage']=='Closed Lost')?$data['stage'].'('.$data['add_comment'].')':$data['stage']).'</a>';*/




	$nestedData['caller'] = getSingleresult("select name from callers where id='".$data['caller']."'");
	$ids2="'but2".$data['id']."'";
	if(!$check_valid)
	{
	$nestedData['partner_close_date'] =($data['partner_close_date']?date('d-m-Y',strtotime($data['partner_close_date'])):'N/A');
	}
	else
	{
		$nestedData['partner_close_date'] =($data['partner_close_date']?date('d-m-Y',strtotime($data['partner_close_date'])):'N/A');

    }
    $order_id=base64_encode($data['id']);
    $nestedData['action']='<a href="partner_view.php?id='.$data['id'].'&review=on" ><img src="assets/images/review.png" height="25"/></a>';
	$results[] = $nestedData;
	$check_valid=0;
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
