<?php include('includes/include.php');
/* Database connection end */
if($_SESSION['user_type']=='USR')
{
	//$u_cond=" and created_by='".$_SESSION['user_id']."' ";
}

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
if($requestData['d_from'] && $requestData['d_to'] && $_REQUEST['dash']!='yes' && $_REQUEST['p_check']!='yes')
{

	if($requestData['dtype']=='created')
	{
			if($requestData['d_from'] == $requestData['d_to'])
			{
			$dat=" and DATE(created_date)='".$requestData['d_from']."'";	
			} else {
			$dat=" and DATE(created_date)>='".$requestData['d_from']."' and DATE(created_date)<='".$requestData['d_to']."'";	
			}
    }
	else if($requestData['dtype']=='close')
	{
		if($requestData['d_from'] == $requestData['d_to'])
		{
		$dat=" and DATE(partner_close_date)='".$requestData['d_from']."'";	
		} else {
		$dat=" and DATE(partner_close_date)>='".$requestData['d_from']."' and DATE(partner_close_date)<='".$requestData['d_to']."'";	
		}
				
	}
	else
	{
		if($requestData['d_from'] == $requestData['d_to'])
		{
		$dat=" and DATE(prospecting_date)='".$requestData['d_from']."'";	
		} else {
		$dat=" and DATE(prospecting_date)>='".$requestData['d_from']."' and DATE(prospecting_date)<='".$requestData['d_to']."'";	
		}
	}
}
else if($requestData['d_from'] && $requestData['d_to'] && ($requestData['dash']=='yes' || $requestData['p_check']=='yes' ) )
{
	$dat=" and  ((date(created_date) BETWEEN  '".$requestData['d_from']."' and '".$requestData['d_to']."') or (date(prospecting_date) BETWEEN  '".$requestData['d_from']."' and '".$requestData['d_to']."') )";	
}

if($_SESSION['user_type']=='ADMIN')
{
	$dat.=" and team_id='".$requestData['partner']."'";
}
else
{
	$dat.=" and team_id='".$_SESSION['team_id']."'";
}
 
// getting total number records without any search
$sql = "select * ";
$sql.=" FROM orders where 1 and dvr_flag=0 and status='Approved' and stage in ('Quote','Follow-up') ".$dat.$u_cond;
//echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

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
$sql.=" ORDER BY created_date desc   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
//echo $sql; die;
$query=db_query($sql);

$results = array();
$i=$requestData['start']+1;
while($data=db_fetch_array($query)) {  // preparing an array
 
	$color='#000';
 
	$nestedData=array(); 
	$nestedData['id']=$i;
	$total_seats+=$data['quantity'];
	$ncdate=strtotime(date('Y-m-d'));
	$closeDate=strtotime($data['close_time']);
	//$nestedData['r_user'] = "<a style='display:block;color:".$color."' href='partner_view.php?id=".$data['id']."'>".$data['r_user'].($data['allign_to']?'('.getSingleresult("select name from users where id=".$data['allign_to']).')':'').'</a>';
	$nestedData['lead_type'] = "<a  style='display:block;color:".$color."' href='partner_view.php?id=".$data['id']."'>".$data['lead_type'].'</a>';
	$nestedData['quantity'] = "<a style='display:block;color:".$color."' href='partner_view.php?id=".$data['id']."'>".$data['quantity'].'</a>';
	$nestedData['company_name'] = "<a style='display:block;color:".$color."' href='partner_view.php?id=".$data['id']."'>".$data['company_name'].'</a>';
	 
	 
	   
	 

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
	$results[] = $nestedData;
	$check_valid=0;
$i++;
}
//print_r($results); die;
 

$json_data = array(
			"total"	=> intval($total_seats),
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $results   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>
