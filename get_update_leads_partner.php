<?php include('includes/include.php');
/* Database connection end */

if($_SESSION['user_type']=='USR')
{
	//$u_cond=" and assigned_to='".$_SESSION['user_id']."' ";
}

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
if($requestData['users'])
{
    $dat.=" and created_by='".$requestData['users']."'";
}
 
$sql = "select * ";
$sql.=" FROM upgrade_leads where reseller='".$_SESSION['team_id']."'".$u_cond;
//echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "select * ";
$sql.=" FROM upgrade_leads where reseller='".$_SESSION['team_id']."'".$u_cond;
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
$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 

$sql.=" ORDER BY id desc  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
//echo $sql; die;
$query=db_query($sql);

$results = array();
$i=1;
while($data=db_fetch_array($query)) {  // preparing an array
 
	$color='#000';
 
	$nestedData=array(); 
	$nestedData[]=$i;
	$nestedData[] = "<a style='display:block;color:".$color."' href='partner_update_view.php?id=".$data['id']."'>".getSingleresult("select name from users where id ='".$data['assigned_to']."'").'</a>';
	$nestedData[] = "<a style='display:block;color:".$color."' href='partner_update_view.php?id=".$data['id']."'>".$data['eu_name'].'</a>';
	$nestedData[] = "<a  style='display:block;color:".$color."' href='partner_update_view.php?id=".$data['id']."'>".$data['eu_address'].'</a>';
	$nestedData[] = "<a style='display:block;color:".$color."' href='partner_update_view.php?id=".$data['id']."'>".$data['eu_contact'].'</a>';
	$nestedData[] = "<a style='display:block;color:".$color."' href='partner_update_view.php?id=".$data['id']."'>".$data['contact_email'].'</a>';
	$nestedData[] = "<a style='display:block;color:".$color."'  href='partner_update_view.php?id=".$data['id']."'>".$data['mobile_number'].'</a>';
	$nestedData[] = "<a style='display:block;color:".$color."'  href='partner_update_view.php?id=".$data['id']."'>".$data['quantity'].'</a>';
	$nestedData[] ="<a style='display:block;color:".$color."'  href='partner_update_view.php?id=".$data['id']."'>".(($data['stage']=='Closed Lost')?$data['stage'].'('.$data['add_comment'].')':$data['stage']).'</a>';
	  if($data['status']=='Approved')
												{
													$ncdate=strtotime(date('Y-m-d'));
													$closeDate=strtotime($data['close_time']);
													if($ncdate>$closeDate)
													{
														$dayspassedafterExpired = ceil(abs($ncdate - $closeDate) / 86400);
														$daysLeft='<span style=color:red;">Expired ('.$dayspassedafterExpired.' Days Passed)</span>';
													}

													else
													{
														
														$remaining_days=ceil(($closeDate-$ncdate)/84600);
														$daysLeft='<span style="color:green">Days Left- '.$remaining_days.'</span>';
													}
													 
												$nestedData[]='<span style="color:green">Qualified</span> '.$daysLeft;
												}
												else if($data['status']=='Cancelled')
												{
												 $nestedData[]='<span class="text-danger">Unqualified</span>';
													
												}
												else if($data['status']=='Pending')
													
													{
														$nestedData[]= 'Pending';
													}
													else if($data['status']=='Undervalidation')
													{
													 $nestedData[]= '<span class="text-warning">Re-Submission Required</span>';
													};
	 
 
	$nestedData[] = $data['closing_status'];
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
