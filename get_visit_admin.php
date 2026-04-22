<?php include('includes/include.php');
/* Database connection end */

//  if($_SESSION['user_type']=='RADMIN')
// {
// 	$vir_cond=" and o.lead_type='LC'";
// }

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;

//$requestData['partner'] = intval($requestData['partner']);
$requestData['length'] = intval($requestData['length']);
$requestData['start'] = intval($requestData['start']);
$requestData['search']['value'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($requestData['search']['value']));

if ($requestData['d_from'] && $requestData['d_to']) {

	if ($requestData['d_from'] == $requestData['d_to']) {
		$dat = " and DATE(activity_log.created_date)='" . $requestData['d_from'] . "' ";
		$raw = " and DATE(activity_log.created_date)='" . $requestData['d_from'] . "' ";
	} else {

		$dat = " and  (date(activity_log.created_date) BETWEEN  '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "')";

		$raw = " and  (date(activity_log.created_date) BETWEEN  '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "')";
	}
}

// if ($requestData['product']) {
// 	$dat .= " and p.product_id='" . $requestData['product'] . "'";
// }
// if ($requestData['product_type']) {
// 	$dat .= " and p.product_type_id='" . $requestData['product_type'] . "'";
// }

if ($requestData['partner']) {
	$dat .= ' and o.team_id in ("' . stripslashes($requestData["partner"]) . '")';
	$raw .= ' and o.team_id in ("' . stripslashes($requestData["partner"]) . '")';
}

if ($requestData['users']) {
	$dat .= ' and activity_log.added_by in ("' . stripslashes($requestData["users"]) . '")';
	$raw .= ' and activity_log.added_by in ("' . stripslashes($requestData["users"]) . '")';
	//print_r($dat);
}

if ($requestData['industry']) {
	$dat .= ' and o.industry in ("' . stripslashes($requestData["industry"]) . '")';
	$raw .= ' and o.industry in ("' . stripslashes($requestData["industry"]) . '")';
}
if ($requestData['call_subject']) {
	$dat .= ' and activity_log.call_subject in ("' . stripslashes($requestData["call_subject"]) . '")';
	$raw .= ' and activity_log.call_subject in ("' . stripslashes($requestData["call_subject"]) . '")';
}

// if ($requestData['partner']) {
// 	$dat .= " and o.team_id='" . $requestData['partner'] . "'";
// 	$raw .= " and o.team_id='" . $requestData['partner'] . "'";
// }
// if ($requestData['users']) {
// 	$dat .= " and activity_log.added_by='" . $requestData['users'] . "'";
// 	$raw .= " and activity_log.added_by='" . $requestData['users'] . "'";
// }
if ($_SESSION['sales_manager'] == 1) {
	$dat .= " and o.team_id in (" . $_SESSION['access'] . ") ";
	$raw .= " and o.team_id in (" . $_SESSION['access'] . ") ";
}

// if ($requestData['product']) {
// 	$raw .= " and o.product_id='" . $requestData['product'] . "'";
// }
// if ($requestData['product_type']) {
// 	$raw .= " and o.product_type_id='" . $requestData['product_type'] . "'";
// }

// if($requestData['ark_users']){
// 	$dat .= " and activity_log.added_by='" . $requestData['ark_users'] . "'";
// 	$raw .= " and activity_log.added_by='" . $requestData['ark_users'] . "'";
// }

// getting total number records without any search

$sql = "select * from ((select o.id as lead,o.r_user,o.team_id,o.r_name,o.lead_type,o.quantity,o.company_name,o.eu_email,o.status,o.parent_company,o.landline,o.eu_mobile,activity_log.*,p.product_id,p.product_type_id,i.name as industry FROM activity_log left join orders as o on activity_log.pid= o.id left join tbl_lead_product as p on o.id=p.lead_id left join industry as i on o.industry=i.id  where 1 and activity_log.call_subject!='' and activity_log.call_subject in ('BD Visit','Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech Support Visit','Validation Visit') and o.id!='' and activity_log.activity_type='Lead' and o.license_type='Commercial' and p.product_type_id in (1,2) " . $dat   . ")  UNION ALL (select o.id as lead,o.r_user,o.team_id,o.r_name,o.lead_type,o.quantity,o.company_name,o.eu_email,o.status,o.parent_company,o.landline,o.eu_mobile,activity_log.*,p.product_id,p.product_type_id,i.name as industry FROM activity_log left join lapsed_orders as o on o.id=activity_log.pid left join tbl_lead_product as p on o.id=p.lead_id left join industry as i on o.industry=i.id  where 1 and activity_log.call_subject!='' and activity_log.call_subject in ('BD Visit','Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech Support Visit','Validation Visit') and o.id!='' and activity_log.activity_type='Lead' and o.license_type='Commercial' and p.product_type_id in (1,2) " . $dat . $id_check . " ) UNION ALL (select o.id as lead,IFNULL(o.r_user,'') as r_user,o.team_id,IFNULL(o.r_name,'') as r_name,IF(o.eu_role IS NOT NULL,'',o.eu_role) AS lead_type,o.quantity,o.company_name,o.eu_email,o.city as status,o.parent_company,o.landline,o.eu_mobile,activity_log.*,o.product_id ,o.product_type_id,i.name as industry FROM activity_log left join raw_leads as o on o.id=activity_log.pid left join industry as i on o.industry=i.id  where activity_log.call_subject!='' and o.id!='' and activity_log.call_subject in ('BD Visit','Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech Support Visit','Validation Visit') and activity_log.activity_type='Raw' and o.product_type_id in (1,2) " . $raw  . $id_check . " )) as i ORDER BY id desc";
//echo $sql; die;

//$sql .= " GROUP BY o.id";
$query = db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
//echo $totalFiltered; die;
if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$search = " AND (o.r_name LIKE '%" . $requestData['search']['value'] . "%' 
	 OR o.quantity LIKE '%" . $requestData['search']['value'] . "%' 
	 OR o.company_name LIKE '%" . $requestData['search']['value'] . "%' 
	 OR o.eu_email LIKE '%" . $requestData['search']['value'] . "%'
	 OR o.parent_company LIKE '%" . $requestData['search']['value'] . "%'
	 OR o.landline LIKE '%" . $requestData['search']['value'] . "%'
	 OR activity_log.activity_type LIKE '%" . $requestData['search']['value'] . "%' 
	 OR o.eu_mobile LIKE '%" . $requestData['search']['value'] . "%' )";

	$sql = "select * from ((select o.id as lead,o.r_user,o.team_id,o.r_name,o.lead_type,o.quantity,o.company_name,o.eu_email,o.status,o.parent_company,o.landline,o.eu_mobile,activity_log.*,p.product_id,p.product_type_id,i.name as industry FROM activity_log left join orders as o on activity_log.pid= o.id left join tbl_lead_product as p on o.id=p.lead_id left join industry as i on o.industry=i.id  where 1 and activity_log.call_subject!=''  and activity_log.call_subject in ('BD Visit','Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech Support Visit','Validation Visit') and o.license_type='Commercial' and p.product_type_id in (1,2) and o.id!='' and activity_log.activity_type='Lead' " . $dat  . $search . " ) UNION ALL (select o.id as lead,o.r_user,o.team_id,o.r_name,o.lead_type,o.quantity,o.company_name,o.eu_email,o.status,o.parent_company,o.landline,o.eu_mobile,activity_log.*,p.product_id,p.product_type_id,i.name as industry FROM activity_log left join lapsed_orders as o on o.id=activity_log.pid left join tbl_lead_product as p on o.id=p.lead_id left join industry as i on o.industry=i.id  where 1 and activity_log.call_subject!=''  and activity_log.call_subject in ('BD Visit','Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech Support Visit','Validation Visit') and o.id!='' and activity_log.activity_type='Lead' and o.license_type='Commercial' and p.product_type_id in (1,2)  " . $dat . $id_check .  $search . " ) UNION ALL (select o.id as lead,IFNULL(o.r_user,'') as r_user,o.team_id,IFNULL(o.r_name,'') as r_name,IF(o.eu_role IS NOT NULL,'',o.eu_role) AS lead_type,o.quantity,o.company_name,o.eu_email,o.city as status,o.parent_company,o.landline,o.eu_mobile,activity_log.*,o.product_id ,o.product_type_id,i.name as industry FROM activity_log left join raw_leads as o on o.id=activity_log.pid left join industry as i on o.industry=i.id where activity_log.call_subject!='' and o.id!='' and activity_log.call_subject in ('BD Visit','Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech Support Visit','Validation Visit') and activity_log.activity_type='Raw' and o.product_type_id in (1,2) " . $raw  . $id_check . $search . " )) as i order by id desc";
}
//$sql.=$dat.$vir_cond;

//echo $sql; die;

$query = db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
//$totalFiltered = $totalData; 
$sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
//echo $sql; die;
//echo $totalFiltered; die;
$query = db_query($sql);

$results = array();
$i = 1;
while ($data = db_fetch_array($query)) {  // preparing an array
	// print_r($data);
	$color = '#000';

	$nestedData = array();
	$nestedData[] = $i;
	if ($data['dvr_by']) {
		$dvr_by = '(' . getSingleresult("select name from users where id=" . $data['dvr_by']) . ')';
	}
	$nestedData[] = $data['r_name'];
	$nestedData[] =  $data['r_user'] . $dvr_by;
	$nestedData[] = $data['lead_type'];
	$nestedData[] =  $data['company_name'];
	$nestedData[] =  $data['industry'];
	$nestedData[] = date('d-m-Y', strtotime($data['created_date']));


	$nestedData[] = $data['call_subject'];

	// $nestedData[] = getSingleresult("select count(id) from activity_log where activity_type='DVR' and pid=" . $data['id']);
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
