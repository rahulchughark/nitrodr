<?php include('includes/include.php');
/* Database connection end */

// if($_SESSION['user_id']==117)
// {
// 	$vir_cond=" and l.lead_type='LC' and l.status='Approved' ";
// }
if($_SESSION['sales_manager']==1)
{
	$vir_cond=" and l.team_id in (".$_SESSION['access'].") ";
}



// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
if($requestData['d_from'] && $requestData['d_to'] && $_REQUEST['dash']!='yes' && $_REQUEST['p_check']!='yes')
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
	else if($requestData['dtype']=='close')
	{
		if($requestData['d_from'] == $requestData['d_to'])
		{
		$dat=" and DATE(l.partner_close_date)='".$requestData['d_from']."'";	
		} else {
		$dat=" and DATE(l.partner_close_date)>='".$requestData['d_from']."' and DATE(l.partner_close_date)<='".$requestData['d_to']."'";	
		}
				
	}
	else
	{
		if($requestData['d_from'] == $requestData['d_to'])
		{
		$dat=" and DATE(l.prospecting_date)='".$requestData['d_from']."'";	
		} else {
		$dat=" and DATE(l.prospecting_date)>='".$requestData['d_from']."' and DATE(l.prospecting_date)<='".$requestData['d_to']."'";	
		}
	}
}
else if($requestData['d_from'] && $requestData['d_to'] && ($requestData['dash']=='yes' || $requestData['p_check']=='yes' ) )
{
	$dat=" and  ((date(l.created_date) BETWEEN  '".$requestData['d_from']."' and '".$requestData['d_to']."') or (date(l.prospecting_date) BETWEEN  '".$requestData['d_from']."' and '".$requestData['d_to']."') )";	
}

if($requestData['expired']=='Yes')
{
$date=date('Y-m-d');
//$check_date=date('Y-m-d',strtotime('-29 days',$date));
$dat.=" and l.status='Approved' and l.close_time < '".$date."'";
}
else if($requestData['expired']=='No')
{
	$date=date('Y-m-d');
	//$check_date=date('Y-m-d',strtotime('-29 days',$date));
	$dat.=" and l.status='Approved' and l.close_time > '".$date."'";	
}
 
if ($requestData['product']) {
	$dat .= " and p.product_id='" . $requestData['product'] . "'";
}
if ($requestData['product_type']) {
	$dat .= " and p.product_type_id='" . $requestData['product_type'] . "'";
}

// getting total number records without any search
$sql = "select l.id,l.r_user,l.status,l.code,l.r_name,l.close_time,l.lead_type,l.quantity,l.company_name,l.eu_email,l.eu_mobile,l.parent_company,l.landline,l.industry,l.sub_industry,l.runrate_key,l.os,l.caller,l.created_by,l.team_id,l.stage,l.license_type,l.partner_close_date,l.created_date,l.prospecting_date,l.sfdc_exp,p.product_id,p.product_type_id,l.lapsed_date ";
$sql.=" FROM lapsed_orders as l left join tbl_lead_product as p on l.id=p.lead_id where 1 and l.license_type='Commercial' and l.team_id='".$_SESSION['team_id']."' ".$dat.$vir_cond;
$sql .= " GROUP BY l.id";
//echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "select l.id,l.r_user,l.status,l.code,l.r_name,l.close_time,l.lead_type,l.quantity,l.company_name,l.eu_email,l.eu_mobile,l.parent_company,l.landline,l.industry,l.sub_industry,l.runrate_key,l.os,l.caller,l.created_by,l.team_id,l.stage,l.license_type,l.partner_close_date,l.created_date,l.prospecting_date,l.sfdc_exp,p.product_id,p.product_type_id,l.lapsed_date ";
$sql.=" FROM lapsed_orders as l left join tbl_lead_product as p on l.id=p.lead_id WHERE 1=1 and l.license_type='Commercial' and l.team_id='".$_SESSION['team_id']."' ";

if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( l.code LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR l.r_name LIKE '%".$requestData['search']['value']."%' ";

	 
	$sql.=" OR l.lead_type LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR l.quantity LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR l.company_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR l.eu_email LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR l.status LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR l.parent_company LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR l.landline LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR l.eu_mobile LIKE '%".$requestData['search']['value']."%' )";

}
$sql.=$dat.$vir_cond;
$sql .= " GROUP BY l.id";
//echo $sql; die;

$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc



$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY l.id desc  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
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
	$ncdate=strtotime(date('Y-m-d'));
	$closeDate=strtotime($data['close_time']);
	 
	$nestedData['r_name'] = "<a style='display:block;color:".$color."' href='view_lapsed.php?id=".$data['id']."'>".$data['r_name'].'('.$data['r_user'].')</a>';
	//$nestedData['lead_type'] = "<a  style='display:block;color:".$color."' href='view_lapsed.php?id=".$data['id']."'>".$data['lead_type'].'</a>';
	$nestedData['quantity'] = "<a style='display:block;color:".$color."' href='view_lapsed.php?id=".$data['id']."'>".$data['quantity'].'</a>';

	$nestedData['product_name'] = "<a style='display:block;color:".$color."' href='view_lapsed.php?id=".$data['id']."'>" . (getSingleresult("select p.product_name from tbl_lead_product as l left join tbl_product as p on l.product_id=p.id where l.lead_id=" . $data['id']) ?(getSingleresult("select p.product_name from tbl_lead_product as l left join tbl_product as p on l.product_id=p.id where l.lead_id=" . $data['id'])):'N/A'). '</a>';
	
	$nestedData['product_type'] = "<a style='display:block;color:".$color."' href='view_lapsed.php?id=".$data['id']."'>" . (getSingleresult("select p.product_type from tbl_lead_product as l left join tbl_product_pivot as p on l.product_type_id=p.id where l.lead_id=" . $data['id'])?(getSingleresult("select p.product_type from tbl_lead_product as l left join tbl_product_pivot as p on l.product_type_id=p.id where l.lead_id=" . $data['id'])):'N/A') . '</a>';

	$nestedData['company_name'] = "<a style='display:block;color:".$color."' href='view_lapsed.php?id=".$data['id']."'>".$data['company_name'].'</a>';
	$nestedData['created_date'] = date('d-m-Y',strtotime($data['created_date']));
    $nestedData['stage'] = ($data['stage']?$data['stage']:'N/A');
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

