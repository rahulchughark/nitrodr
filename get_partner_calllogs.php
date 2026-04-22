<?php include('includes/include.php');
/* Database connection end */

// if ($_SESSION['user_id'] == 117) {
// 	$vir_cond = " and o.lead_type='LC'";
// }

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;

if ($requestData['d_from'] && $requestData['d_to']) {

	if ($requestData['d_from'] == $requestData['d_to']) {
		$dat = " and DATE(activity_log.created_date)='" . $requestData['d_from'] . "' ";
	} else {

		$dat = " and  (date(activity_log.created_date) BETWEEN  '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "')";
	}
}

if ($requestData['d_from'] && $requestData['d_to']) {

	if ($requestData['d_from'] == $requestData['d_to']) {
		$raw = " and DATE(activity_log.created_date)='" . $requestData['d_from'] . "' ";
	} else {

		$raw = " and  (date(activity_log.created_date) BETWEEN  '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "')";
	}
}
if ($_SESSION['user_type'] == 'MNGR') {

	$dat .= " and o.team_id='" . $_SESSION['team_id'] . "'";
	$raw .= " and o.team_id='" . $_SESSION['team_id'] . "'";
	$users1 = db_query("select id,role from users where team_id='" . $_SESSION['team_id'] . "' and status='Active' ");
	$ids = array();
	while ($uid = db_fetch_array($users1)) {
		$ids[] = $uid['id'];
	}
	$idds = implode(',', $ids);
	$id_check = " and activity_log.added_by in (" . $idds . ") ";

}

if ($requestData['campaign']) {
	$dat .= ' and o.campaign_type in ("' . stripslashes($requestData["campaign"]) . '")';
	$raw .= " and activity_log.activity_type!='Raw'";
}

if ($requestData['users']) {
	// print_r($requestData['users']); die;
	$dat .= ' and activity_log.added_by in (' . $requestData["users"] . ')';
	$raw .= ' and activity_log.added_by in (' . $requestData["users"] . ')';
}

if ($requestData['call_type']) {
	$dat .= ' and activity_log.call_subject in ("' . stripslashes($requestData["call_type"]) . '")';
	$raw .= ' and activity_log.call_subject in ("' . stripslashes($requestData["call_type"]) . '")';
}

if($_SESSION['user_type'] == 'USR' && $_SESSION['role'] != 'TC')
{
	$dat .= " and activity_log.added_by = '" . $_SESSION['user_id'] ."'";
	$raw .= " and activity_log.added_by = '" . $_SESSION['user_id'] ."'";
}

if ($_SESSION['sales_manager'] == 1) {
	$dat .= " and o.team_id in (" . $_SESSION['access'] . ") ";
	$raw .= " and o.team_id in (" . $_SESSION['access'] . ") ";
}

if($requestData['campaign_check']=='yes'){
	$dat .= " and (o.campaign_type = 0 || o.campaign_type IS NULL)";
	// $raw .= " and activity_log.activity_type!='Raw'";
}

if(($_SESSION['user_type'] == 'USR') && ($_SESSION['role'] == 'TC')){
	$dat .= " and activity_log.added_by = '" . $_SESSION['user_id'] ."'";
	$raw .= " and activity_log.added_by = '" . $_SESSION['user_id'] ."'";
}

// getting total number records without any search
$sql = "select * from ((select o.id as lead,o.team_id,o.code,o.r_name,o.lead_type,o.quantity,o.company_name,o.eu_email,o.status,o.parent_company,o.landline,o.eu_mobile,activity_log.*,o.stage,i.name as industry,o.campaign_type FROM activity_log left join orders as o on o.id=activity_log.pid  left join industry as i on o.industry=i.id where 1 and activity_log.call_subject!=''  and activity_log.call_subject not like '%visit%' and o.id!='' and activity_log.activity_type='Lead' and activity_log.is_intern=0 and o.license_type='Commercial' " . $dat . $u_cond . $vir_cond . $id_check . " ) UNION ALL (select o.id as lead,o.team_id,o.code,o.r_name,o.lead_type,o.quantity,o.company_name,o.eu_email,o.status,o.parent_company,o.landline,o.eu_mobile,activity_log.*,o.stage,i.name as industry,o.campaign_type FROM activity_log left join lapsed_orders as o on o.id=activity_log.pid left join industry as i on o.industry=i.id where 1 and activity_log.call_subject!='' and activity_log.call_subject not like '%visit%' and o.id!='' and activity_log.activity_type='Lead'  and activity_log.is_intern=0  and o.license_type='Commercial' " . $dat . $u_cond . $vir_cond . $id_check . " ) UNION ALL (select o.id as lead,o.team_id,o.r_user as code,o.r_name,IF(o.eu_role IS NOT NULL,'',o.eu_role) AS lead_type,o.quantity,o.company_name,o.eu_email,o.city as status,o.parent_company,o.landline,o.eu_mobile,activity_log.*,IF(o.department IS NOT NULL,'',o.department) AS stage,i.name as industry,IF(o.eu_designation IS NOT NULL,'',o.eu_designation) AS campaign_type FROM activity_log left join raw_leads as o on o.id=activity_log.pid left join industry as i on o.industry=i.id where activity_log.call_subject!='' and o.id!='' and activity_log.call_subject not like '%visit%' and activity_log.activity_type='Raw' and activity_log.is_intern=0 " . $raw . $u_cond . $vir_cond . $id_check . " )) as i order by i.id desc";

// echo $sql; die;

//$sql .= " GROUP BY o.id";
$query = db_query($sql);
//$totalData = mysqli_num_rows($query);
//$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$search = " AND (o.r_name LIKE '%".$requestData['search']['value']."%' 
	 OR o.quantity LIKE '%".$requestData['search']['value']."%' 
	 OR o.company_name LIKE '%".$requestData['search']['value']."%' 
	 OR o.eu_email LIKE '%".$requestData['search']['value']."%'
	 OR o.parent_company LIKE '%".$requestData['search']['value']."%'
	 OR o.landline LIKE '%".$requestData['search']['value']."%'
	 OR activity_log.activity_type LIKE '%".$requestData['search']['value']."%' 
	 OR o.eu_mobile LIKE '%".$requestData['search']['value']."%' )";


	//$sql.=" OR eu_mobile LIKE '".$requestData['search']['value']."%' )";
	$sql = "select * from ((select o.id as lead,o.team_id,o.code,o.r_name,o.lead_type,o.quantity,o.company_name,o.eu_email,o.status,o.parent_company,o.landline,o.eu_mobile,activity_log.*,o.stage,i.name as industry,o.campaign_type FROM activity_log left join orders as o on o.id=activity_log.pid left join industry as i on o.industry=i.id where 1 and activity_log.call_subject!=''  and activity_log.call_subject not like '%visit%' and o.id!='' and activity_log.activity_type='Lead' and o.license_type='Commercial'  " . $dat . $u_cond . $vir_cond . $id_check . $search.") UNION ALL (select o.id as lead,o.team_id,o.code,o.r_name,o.lead_type,o.quantity,o.company_name,o.eu_email,o.status,o.parent_company,o.landline,o.eu_mobile,activity_log.*,o.stage,i.name as industry,o.campaign_type FROM activity_log left join lapsed_orders as o on o.id=activity_log.pid left join industry as i on o.industry=i.id where 1 and activity_log.call_subject!=''  and activity_log.call_subject not like '%visit%' and o.id!='' and activity_log.activity_type='Lead' and o.license_type='Commercial' " . $dat . $u_cond . $vir_cond . $id_check . $search.") UNION ALL (select o.id as lead,o.team_id,o.r_user as code,o.r_name,IF(o.eu_role IS NOT NULL,'',o.eu_role) AS lead_type,o.quantity,o.company_name,o.eu_email,o.city as status,o.parent_company,o.landline,o.eu_mobile,activity_log.*,IF(o.department IS NOT NULL,'',o.department) AS stage,i.name as industry,IF(o.eu_designation IS NOT NULL,'',o.eu_designation) AS campaign_type FROM activity_log left join raw_leads as o on o.id=activity_log.pid left join industry as i on o.industry=i.id where activity_log.call_subject!='' and o.id!='' and activity_log.call_subject not like '%visit%'  and activity_log.activity_type='Raw' " . $raw . $u_cond . $vir_cond . $id_check . $search.")) as i order by i.id desc";
	
}
//$sql.=$dat.$vir_cond;


//$sql .= " ORDER BY DATE(activity_log.created_date) desc";

// echo $sql; die;
$query = db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData; // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
//echo $sql; die;
$query = db_query($sql);

$results = array();
$i = 1;
while ($data = db_fetch_array($query)) {  // preparing an array

	$color = '#000';

	$nestedData = array();
	$nestedData[] = $i;
	if ($data['dvr_by']) {
		$dvr_by = '(' . getSingleresult("select name from users where id=" . $data['dvr_by']) . ')';
	}
	$nestedData[] =$data['r_name'];
		// "<a style='display:block;color:" . $color . "' href='partner_dvr.php?id=" . $data['lead'] . "'>" . $data['r_name'] . '</a>';
	// $nestedData[] = "<a style='display:block;color:".$color."' href='partner_dvr.php?id=".$data['lead']."'>".$data['r_user'].$dvr_by.'</a>';
	$nestedData[] = getSingleresult("select name from users where id=" . $data['added_by']);
	$nestedData[] = date('d-m-Y', strtotime($data['created_date']));
	$nestedData[] = $data['call_subject'];
	//"<a style='display:block;color:".$color."'  href='partner_dvr.php?id=".$data['pid']."'>".$data['call_subject'].'</a>';
	$nestedData[] = $data['company_name'];
	$nestedData[] = $data['industry'];
	$nestedData[] = $data['lead_type'];
	
	$nestedData[] = $data['stage'];
	$nestedData[] = $data['quantity'];
	$nestedData[] = $data['activity_type'];

	// $nestedData[] = "<a style='display:block;color:".$color."'  href='partner_dvr.php?id=".$data['lead']."'>".$data['eu_email'].'</a>';
	// $nestedData[] = "<a style='display:block;color:".$color."'  href='partner_dvr.php?id=".$data['lead']."'>".$data['eu_mobile'].'</a>';
	$nestedData[] = date('d-m-Y', strtotime($data['created_date']));


	$results[] = $nestedData;
	$i++;
}
//print_r($results); die;


$json_data = array(
	"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
	"recordsTotal"    => intval($totalData),  // total number of records
	"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
	"data"            => $results   // total data array
);

echo json_encode($json_data);  // send data as json format
