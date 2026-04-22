<?php include('includes/include.php');
/* Database connection end */
//if($_SESSION['user_type']!='ADMIN' && $_SESSION['user_type']!= 'SUPERADMIN' && $_SESSION['user_type']!= 'OPERATIONS' && $_SESSION['user_type']!= 'RADMIN')
if($_SESSION['user_type']=='USR' || $_SESSION['user_type']== 'MNGR' || $_SESSION['user_type']== 'PUSR')
{
	$u_cond=" and r.team_id='".$_SESSION['team_id']."' ";
}

if ($_SESSION['sales_manager'] == 1) {
	$vir_cond = " and r.team_id in (" . $_SESSION['access'] . ") ";
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

$requestData['d_from']= preg_replace("([^0-9/] | [^0-9-])","",$requestData['d_from']);
$requestData['d_to']= preg_replace("([^0-9/] | [^0-9-])","",$requestData['d_to']);
$requestData['d_from']=mysqli_real_escape_string($GLOBALS['dbcon'],htmlentities($requestData['d_from']));
$requestData['d_to']=mysqli_real_escape_string($GLOBALS['dbcon'],htmlentities($requestData['d_to']));

if ($requestData['d_from'] && $requestData['d_to']) {
	if ($requestData['dtype'] == 'created') {
		if ($requestData['d_from'] == $requestData['d_to']) {
			$dat .= " and DATE(r.created_date)='" . $requestData['d_from'] . "'";
		} else {
			$dat .= " and DATE(r.created_date)>='" . $requestData['d_from'] . "' and DATE(r.created_date)<='" . $requestData['d_to'] . "'";
		}
	} else if ($requestData['dtype'] == 'approved_date') {
		if ($requestData['d_from'] == $requestData['d_to']) {
			$dat .= " and DATE(r.approval_time)='" . $requestData['d_from'] . "'";
		} else {
			$dat .= " and DATE(r.approval_time)>='" . $requestData['d_from'] . "' and DATE(r.approval_time)<='" . $requestData['d_to'] . "'";
		}
	}else{
		if($requestData['d_from'] == $requestData['d_to'])
		{
		$dat=" and DATE(r.created_date)='".$requestData['d_from']."'";	
		} else {
		$dat=" and DATE(r.created_date)>='".$requestData['d_from']."' and DATE(r.created_date)<='".$requestData['d_to']."'";	
		}  
	} 
}


if($requestData['partner'])
{
    $dat.=' and r.team_id in ("' . stripslashes($requestData["partner"]) . '")';
}

if ($requestData['product']) {
	$dat .= " and tpp.product_id='" . $requestData['product'] . "'";
}
if ($requestData['product_type']) {
	$dat .= ' and tpp.id in ("' . stripslashes($requestData["product_type"]) . '")';
}

if ($requestData['users']) {
	$dat .= ' and r.created_by in ("' . stripslashes($requestData["users"]) . '")';
	//print_r($dat);
}

// getting total number records without any search
$sql = "select r.r_email,r.created_date,r.team_id,r.id,r.product_type_id,r.product_id,r.quantity,r.company_name,r.eu_name,r.eu_email,r.eu_mobile,r.r_user,r.source,r.created_by,tp.product_name,tpp.product_type ";

$sql.=" FROM raw_leads as r left join tbl_product as tp on r.product_id=tp.id left join tbl_product_pivot as tpp on r.product_type_id=tpp.id where 1 and is_intern=0 ".$dat.$u_cond . $vir_cond;
//echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "select r.r_email,r.created_date,r.team_id,r.id,r.product_type_id,r.product_id,r.quantity,r.company_name,r.eu_name,r.eu_email,r.eu_mobile,r.r_user,r.source,r.created_by,tp.product_name,tpp.product_type ";

$sql.=" FROM raw_leads as r left join tbl_product as tp on r.product_id=tp.id left join tbl_product_pivot as tpp on r.product_type_id=tpp.id WHERE 1=1 and is_intern=0 ".$u_cond . $vir_cond;

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
	 
	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='raw_view.php?id=".$data['id']."'>".($data['source']?$data['source']:'N/A').'</a>';
	
	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='raw_view.php?id=".$data['id']."'>".getSingleresult("select name from partners where id=".$data['team_id']).'</a>';

	$nestedData[] =($data['r_user']?$data['r_user']:'N/A');

	$nestedData[] =($data['r_email']?$data['r_email']:'N/A');

	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='raw_view.php?id=".$data['id']."'>".$data['quantity'].'</a>';

	$nestedData[] = "<a  target='_blank' style='display:block;color:".$color."' href='raw_view.php?id=".$data['id']."'>".($data['product_name']?$data['product_name']:'N/A').'</a>';

	$nestedData[] = "<a target='_blank'  style='display:block;color:".$color."' href='raw_view.php?id=".$data['id']."'>".($data['product_type']?$data['product_type']:'N/A').'</a>';

	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='raw_view.php?id=".$data['id']."'>".$data['company_name'].'</a>';
	
	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='raw_view.php?id=".$data['id']."'>".$data['eu_name'].'</a>';

	$nestedData[] = "<a target='_blank' style='display:block;color:".$color."' href='raw_view.php?id=".$data['id']."'>".$data['eu_mobile'].'</a>';
	
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
