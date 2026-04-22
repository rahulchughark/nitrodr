<?php include('includes/include.php');
/* Database connection end */

if($_SESSION['user_id']==117)
{
	$vir_cond=" and lead_type='LC' and status='Approved' ";
}
if ($_SESSION['sales_manager'] == 1) {
	$vir_cond = " and team_id in (" . $_SESSION['access'] . ") ";
}
if ($_SESSION['sales_manager'] == 1) {
	$region_access=getSingleresult("select region_access from users where id='".$_SESSION['user_id']."'");
	if($region_access) { $regions=explode(',',$region_access);
	$search_region=array();
	foreach($regions as $region)
	{
		$search_region[]="'".$region."'";
	}
	$vir_cond .= " and region in (" . implode(",",$search_region) . ") ";
}
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
		$dat=" and DATE(created_date)='".$requestData['d_from']."'";	
		} else {
		$dat=" and DATE(created_date)>='".$requestData['d_from']."' and DATE(created_date)<='".$requestData['d_to']."'";	
		}
	}
}
else if($requestData['d_from'] && $requestData['d_to'] && ($requestData['dash']=='yes' || $requestData['p_check']=='yes' ) )
{
	$dat=" and  ((date(created_date) BETWEEN  '".$requestData['d_from']."' and '".$requestData['d_to']."') or (date(prospecting_date) BETWEEN  '".$requestData['d_from']."' and '".$requestData['d_to']."') )";	
}
if($requestData['lead_type'])
{
	if($requestData['lead_type']=='Internal')
	{
		$dat.=" and iss='1' ";
	}
	else if($requestData['lead_type']=='LC')
	{
		$dat.=" and lead_type = 'LC' and iss is NULL ";
	}
	else
	{
	if(strpos($requestData['lead_type'], 'Internal'))
	$dat.=" and lead_type in ('".$requestData['lead_type']."') and iss='1' ";
	else
	$dat.=" and lead_type in ('".$requestData['lead_type']."')";
	}
}
 
if($requestData['status'])
{
    $dat.=" and status='".$requestData['status']."'";
}
if($requestData['ltype'])
{
    $dat.=" and agreement_type='".$requestData['ltype']."'";
}
if($requestData['stage'])
{
    $dat.=" and stage in ('".$requestData['stage']."')";
}
if($requestData['partner'])
{
    $dat.=" and team_id='".$requestData['partner']."'";
}
if($requestData['caller'])
{
    $dat.=" and caller='".$requestData['caller']."'";
}
if($requestData['users'])
{
    $dat.=" and created_by='".$requestData['users']."'";
}
if($requestData['caller'])
{
$dat.=" and caller='".$requestData['caller']."'";
}
if($requestData['quantity'])
{
$dat.=" and quantity='".$requestData['quantity']."'";
}
if($requestData['industry'])
{
$dat.=" and industry='".$requestData['industry']."'";
}
if($requestData['sub_industry'])
{
$dat.=" and sub_industry='".$requestData['sub_industry']."'";
}
if($requestData['runrate_key'])
{
$dat.=" and runrate_key='".$requestData['runrate_key']."'";
}
if($requestData['os'])
{
$dat.=" and os='".$requestData['os']."'";
}
if($requestData['expired']=='Yes')
{
$date=date('Y-m-d');
//$check_date=date('Y-m-d',strtotime('-29 days',$date));
$dat.=" and status='Approved' and close_time < '".$date."'";
}
else if($requestData['expired']=='No')
{
	$date=date('Y-m-d');
	//$check_date=date('Y-m-d',strtotime('-29 days',$date));
	$dat.=" and status='Approved' and close_time > '".$date."'";	
}
 
// getting total number records without any search
$sql = "select * ";
$sql.=" FROM orders where 1 ".$dat.$vir_cond;
// echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "select * ";
$sql.=" FROM orders WHERE 1=1 ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( code LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR r_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR quantity LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR school_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR eu_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR eu_email LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR status LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR group_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR contact LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR eu_mobile LIKE '%".$requestData['search']['value']."%' )";
	//$sql.=" OR eu_landline LIKE '".$requestData['search']['value']."%' )";
	
	//$sql.=" OR eu_mobile LIKE '".$requestData['search']['value']."%' )";
}
$sql.=$dat.$vir_cond;
// echo $sql; die;

$columnIndex = $requestData['order'][0]['column']; // Column index 
$columnName = $requestData['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc



$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ".$columnName." ".$columnSortOrder."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
//echo $sql; die;
$query=db_query($sql);

$results = array();
$i=$requestData['start']+1;
while($data=db_fetch_array($query)) {  // preparing an array
if($data['sfdc_exp']==1 && $_SESSION['sales_manager']!=1)
{
	$color='#225da8';
}
else
{
	$color='#000';
}
	$nestedData=array(); 
	$nestedData['id']=$i;
	$ncdate=strtotime(date('Y-m-d'));
	$closeDate=strtotime($data['close_time']);
	
	
   $nestedData['code'] = "<a style='display:block;color:".$color."' href='view_order.php?id=".$data['id']."'>".($data['code']?$data['code']:'N/A').'</a>';
	
	$nestedData['r_name'] = "<a style='display:block;color:".$color."' href='view_order.php?id=".$data['id']."'>".$data['r_name'].'('.$data['r_user'].')</a>';
	
	
	$nestedData['quantity'] = "<a style='display:block;color:".$color."' href='view_order.php?id=".$data['id']."'>".$data['quantity'].'</a>';

	$nestedData['school_board'] = "<a style='display:block;color:".$color."' href='view_order.php?id=".$data['id']."'>".$data['school_board'].'</a>';

	$nestedData['school_name'] = "<a style='display:block;color:".$color."' href='view_order.php?id=".$data['id']."'>".$data['school_name'].'</a>';

	$nestedData['created_date'] = date('d-m-Y h:i:s',strtotime($data['created_date']));
	  if($data['status']=='Approved')
		{	
			$nestedData['status']='<span style="color:green">Qualified</span> ';
		
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
			}
			else if($data['status']=='On-Hold')
			{
				$nestedData['status']= '<span class="text-blue">On-Hold</span>';
			}
			else if($data['status']=='For Validation')
			{
				$nestedData['status']= '<span class="text-themecolor">For Validation</span>';
			}
			else 
			{
				$nestedData['status']= '<span class="text-warning">'.$data['status'].'</span>';
			}
		

			if($data['status'] == 'Approved'){
				$ids="'but".$data['id']."'";
				$nestedData['stage'] = ($data['stage']?$data['stage']:'N/A').'<a href="javascript:void(0)" title="Change Stage" id=but'.$data['id'].' onclick="stage_change('.$ids.','.$data['id'].')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>';
		   }else{
			   $nestedData['stage'] = '';
   
		   }	
			 
			$results[] = $nestedData;
			$check='';
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

