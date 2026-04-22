<?php include('includes/include.php');
$requestData= $_REQUEST;
$dat = " ";
// print_r($_REQUEST); die;
if($requestData['license_from'] && $requestData['license_to']) 
{

    if($requestData['license_from'] == $requestData['license_to'])
    {
    $dat=" and DATE(license_end_date)='".$requestData['license_from']."'";	
    } else {
    $dat=" and DATE(license_end_date)>='".$requestData['license_from']."' and DATE(license_end_date)<='".$requestData['license_to']."'";	
    }   
}
 if($requestData['partner'])
 {
	 $dat .=" and team_id='".$requestData['partner']."'";   
 }

 if ($requestData['license_end_month']) {
	$dat .= " and  MONTH(license_end_date) ='".$requestData['license_end_month']."' AND YEAR(license_end_date) = YEAR(CURDATE()) ";
}

if ($requestData['lead_type']) {
	if ($requestData['lead_type'] == 'Internal') {
		$dat .= " and iss='1' ";
	} else if ($requestData['lead_type'] == 'LC') {
		$dat .= " and lead_type = 'LC' and iss is NULL ";
	} else {
		if (strpos($requestData['lead_type'], 'Internal'))
			$dat .= " and lead_type in ('" . $requestData['lead_type'] . "') and iss='1' ";
		else
			$dat .= " and lead_type in ('" . $requestData['lead_type'] . "')";
	}
}


if ($requestData['stage']) {
	$dat .= " and stage in ('" . $requestData['stage'] . "')";
}
if ($requestData['partner']) {
	$dat .= " and team_id='" . $requestData['partner'] . "'";
}

if ($requestData['users']) {
	$dat .= " and created_by='" . $requestData['users'] . "'";
}

if ($requestData['quantity']) {
	$dat .= " and quantity='" . $requestData['quantity'] . "'";
}
if ($requestData['industry']) {
	$dat .= " and industry='" . $requestData['industry'] . "'";
}
if ($requestData['sub_industry']) {
	$dat .= " and sub_industry='" . $requestData['sub_industry'] . "'";
}

if ($requestData['expired'] == 'Yes') {
	$date = date('Y-m-d');
	//$check_date=date('Y-m-d',strtotime('-29 days',$date));
	$dat .= " and status='Approved' and close_time < '" . $date . "'";
} else if ($requestData['expired'] == 'No') {
	$date = date('Y-m-d');
	//$check_date=date('Y-m-d',strtotime('-29 days',$date));
	$dat .= " and status='Approved' and close_time > '" . $date . "'";
}

$cid = reviewLeadCallerId($_SESSION['user_id']); 

 // $cid = reviewLeadCallerId($_SESSION['user_id']);
 // $cid=getSingleresult("select id from callers where user_id='".$_SESSION['user_id']."'");
// getting total number records without any search
$sql = callerRenewalLeads($cid,$dat);	
// $sql = "select * ";
// $sql.=" FROM orders where 1 and dvr_flag=0  and  license_type='Renewal'  and caller='".$cid."'".$dat;
// echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$sql = callerRenewalLeads($cid,$dat);	
// $sql = "select * ";
// $sql.=" FROM orders WHERE 1=1 and dvr_flag=0 and  license_type='Renewal'  and caller='".$cid."'".$dat;

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
	$nestedData['id']=$i;

	$user_name = getSingleresult("select name from users where id='" . $data['created_by'] . "'");

	$reseller = getSingleresult("select name from partners where id='" . $data['team_id'] . "'");
 
	$nestedData['r_user'] = "<a style='display:block;color:".$color."' href='caller_view.php?id=".$data['id']."'>".$reseller.'('.$user_name.')</a>';

	$nestedData['lead_type'] = "<a  style='display:block;color:".$color."' href='caller_view.php?id=".$data['id']."'>".$data['lead_type'].'</a>';

	$nestedData['quantity'] = "<a style='display:block;color:".$color."' href='caller_view.php?id=".$data['id']."'>".$data['quantity'].'</a>';

	$nestedData['company_name'] = "<a style='display:block;color:".$color."' href='caller_view.php?id=".$data['id']."'>".$data['company_name'].'</a>';
	 
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
			$check_valid = 1;
			$nestedData['status']='<span style="color:red">Expired</span> '.$daysLeft;
			}
			else
			{
				$check_valid = 1;
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
		}
		else if($data['status']=='For Validation')
		{
			$nestedData['status']= '<span class="text-themecolor">For Validation</span>';
		}
		else if($data['status']=='On-Hold')
		{
			$nestedData['status']= '<span class="text-themecolor">On-Hold</span>';
		}
	;


	if($data['status'] == 'Approved'){
		if(getSingleresult("select count(id) from  lead_review where is_review=1 and lead_id='".$data['id']."'"))
		{
			$nestedData['stage']='<span class="text-danger">Under Review</span>';
		}
		else
		{
		$ids="'but".$data['id']."'";
		$nestedData['stage'] = ($data['stage']?$data['stage']:'N/A').'<a href="javascript:void(0)" title="Change Stage" id=but'.$data['id'].' onclick="stage_change('.$ids.','.$data['id'].')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>';
		}
	}else{
		 $nestedData['stage'] = '';

	 }	
	 
	 $nestedData['caller'] = getSingleresult("select name from callers where id='".$data['caller']."'");
 $ids2="'but2".$data['id']."'";
	 if (!$check_valid) { 
	$nestedData['partner_close_date'] =($data['partner_close_date']?date('d-m-Y',strtotime($data['partner_close_date'])):'N/A').'<a href="javascript:void(0)" title="Change Close Date" id=but2'.$data['id'].' onclick="cd_change('.$ids2.','.$data['id'].')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>';
	} else if (getSingleresult("select count(id) from  lead_review where is_review=1 and lead_id='" . $data['id'] . "'")) {
		$nestedData['partner_close_date'] = 'N/A';
	} else {
		$nestedData['partner_close_date'] = ($data['partner_close_date'] ? date('d-m-Y', strtotime($data['partner_close_date'])) : 'N/A');
	}


	$results[] = $nestedData;
	$check_valid = 0;
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
