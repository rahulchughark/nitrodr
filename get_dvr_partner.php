<?php include('includes/include.php');
/* Database connection end */

// if($_SESSION['user_type']=='USR')
// {
// 	$u_cond=" and o.created_by='".$_SESSION['user_id']."' ";
// }

if ($_SESSION['sales_manager'] == 1) {
	$u_cond = " and o.team_id in(" . $_SESSION['access'] . ") OR created_by=".$_SESSION['user_id']." ";
}else{
  $u_cond  = " and o.team_id='".$_SESSION['team_id']."' ";
}
// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
if($requestData['d_from'] && $requestData['d_to'])
{

    if($requestData['d_from'] == $requestData['d_to'])
    {
    $dat=" and DATE(o.created_date)='".$requestData['d_from']."'";	
    } else {
    $dat=" and DATE(o.created_date)>='".$requestData['d_from']."' and DATE(o.created_date)<='".$requestData['d_to']."'";	
    }   
}
// if($requestData['lead_type'])
// {
//     $dat.=" and o.lead_type='".$requestData['lead_type']."'";
// }
 
// if($requestData['status'])
// {
//     $dat.=" and o.status='".$requestData['status']."'";
// }
// if($requestData['partner'])
// {
//     $dat.=" and o.team_id='".$requestData['partner']."'";
// }
if($requestData['users'])
{
    $dat.=" and o.created_by='".$requestData['users']."'";
}
 
// if ($requestData['product']) {
// 	$dat .= " and p.product_id='" . $requestData['product'] . "'";
// }
// if ($requestData['product_type']) {
// 	$dat .= " and p.product_type_id='" . $requestData['product_type'] . "'";
// }
if ($requestData['industry']) {
	$dat .= ' and o.industry in ("' . stripslashes($requestData["industry"]) . '")';
}
if ($requestData['call_type']) {
	$dat .= ' and o.call_type in ("' . stripslashes($requestData["call_type"]) . '")';
}

// getting total number records without any search
$sql = "select o.*,i.name as industry,o.call_type ";
$sql.=" FROM orders as o left join industry as i on o.industry=i.id where 1 and o.is_dr=1 ".$dat.$u_cond;
//echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "select o.*,i.name as industry,o.call_type ";
$sql.=" FROM orders as o left join industry as i on o.industry=i.id WHERE 1=1 and o.is_dr=1 ".$u_cond;

if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( o.code LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR o.r_name LIKE '%".$requestData['search']['value']."%' ";	 
	$sql.=" OR o.lead_type LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.quantity LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.company_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.eu_email LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.status LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR o.eu_mobile LIKE '%".$requestData['search']['value']."%' )";
	
	//$sql.=" OR eu_mobile LIKE '".$requestData['search']['value']."%' )";
}

$sql.=$dat;
//$sql .= " GROUP BY o.id";
$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" GROUP BY o.id ORDER BY o.id desc  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
//echo $sql; die;
$query=db_query($sql);

$results = array();
$i=1;
while($data=db_fetch_array($query)) {  // preparing an array
 
	$color='#000';
 
	$nestedData=array(); 
	$nestedData[]=$i;
	if($data['dvr_by'])
	{
		$dvr_by='('.getSingleresult("select name from users where id=".$data['dvr_by']).')';
	}
	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='partner_dvr.php?id=".$data['id']."'>".$data['r_user'].'</a>';
	$nestedData[] = "<a target='_blank'  style='display:block;color:".$color."' href='partner_dvr.php?id=".$data['id']."'>".$data['lead_type'].'</a>';
	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='partner_dvr.php?id=".$data['id']."'>".$data['company_name'].'</a>';
	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='partner_dvr.php?id=".$data['id']."'>".$data['industry'].'</a>';
	$nestedData[] = date('d-m-Y',strtotime($data['created_date']));
	  
	$dvr_by='';
	$call_type=getSingleresult("select name from call_type where id='".$data['call_type']."'");

	$nestedData[] =($call_type?$call_type:'N/A');
	$results[] = $nestedData;
$i++;
}
// echo "<pre>";
// print_r($results); die;
 

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $results   // total data array
			);

echo json_encode($json_data);  // send data as json format
