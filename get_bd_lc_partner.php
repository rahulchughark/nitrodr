<?php include('includes/include.php');

/* Database connection end */
$iss_users = db_query("select c.name from users left join callers as c on users.id=c.user_id where users.user_type='CLR' and users.role='ISS' and users.status='Active' ");

while ($uid = db_fetch_array($iss_users)) {
	$caller_name[] = $uid['name'];
}

$iss_names = @implode("','", $caller_name);

if ($_SESSION['user_type'] == 'USR') {
	$u_cond = " and o.created_by='" . $_SESSION['user_id'] . "' ";
}

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;

//$requestData['partner'] = intval($requestData['partner']);
$requestData['length'] = intval($requestData['length']);
$requestData['start'] = intval($requestData['start']);
$requestData['search']['value'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($requestData['search']['value']));


if ($requestData['users']) {
	$dat .= ' and o.created_by in ("' . stripslashes($requestData["users"]) . '")';
}

if ($requestData['caller']) {
	$dat .= ' and o.caller in ("' . stripslashes($requestData["caller"]) . '")';
}

if ($requestData['industry']) {
	$dat .= ' and o.industry in ("' . stripslashes($requestData["industry"]) . '")';
}

// if ($requestData['converted_by']) {
// 	$dat .= ' and lm.created_by in ("' . stripslashes($requestData["converted_by"]) . '")';
// }

$quant_arr = @explode(',', $requestData['quantity']);

if (in_array('9', $quant_arr)) {
	$dat .= ' and (o.quantity in (' . stripslashes($requestData["quantity"]) . ') or o.quantity >=9)';
} else if (!in_array('9', $quant_arr) && $requestData["quantity"] != '') {
	$dat .= ' and o.quantity in (' . stripslashes($requestData["quantity"]) . ') ';
}

if ($requestData['segment'] == 'DTP') {
	$dat .= " and i.log_status=1";
}
if ($requestData['segment'] == 'Other') {
	$dat .= " and i.log_status=0";
}

if ($_SESSION['sales_manager'] == 1) {
	$dat .= " and o.team_id in (" . $_SESSION['access'] . ") ";
}


// getting total number records without any search
if ($requestData['status'] == 'Pending') {
	$sql = "select o.* from orders as o left join lead_notification lm on o.id=lm.type_id where lm.title='Request BD to LC' and lm.is_read=0 and lm.sender_type='Partner' and o.team_id='" . $_SESSION['team_id'] . "' $dat $u_cond group by lm.type_id order by o.id desc";

} else if($requestData['status'] == 'Completed'){
	$sql = "select * from ((select o.*,lm.created_date as converted_date,lm.created_by as converted_by from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and lm.type='Lead Type' and lm.previous_name in ('BD','Incoming') and lm.modify_name='LC' and o.lead_type='LC' and o.team_id='" . $_SESSION['team_id'] . "' and EXISTS (select o.*,lm.created_date as converted_date,lm.created_by as converted_by from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and lm.type='Caller' and lm.previous_name ='' and lm.modify_name in ('" . $iss_names . "') and o.team_id='" . $_SESSION['team_id'] . "') $dat $u_cond GROUP BY o.id) UNION ALL (select o.*,lm.created_at as converted_date,lm.sender_id as converted_by from orders as o left join lead_notification lm on o.id=lm.type_id where lm.title='Request BD to LC' and lm.is_read=1 and lm.sender_type='Partner' and o.team_id='" . $_SESSION['team_id'] . "' $dat $u_cond group by lm.type_id)) as i group by i.id order by i.id desc";
}else if ($requestData['d_from'] && $requestData['d_to']) {

	if ($requestData['d_from'] == $requestData['d_to']) {
		$sql = "select o.*,lm.created_date as converted_date,lm.created_by as converted_by from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and lm.type='Lead Type' and lm.previous_name in ('BD','Incoming') and lm.modify_name='LC' and o.lead_type='LC' and DATE(lm.created_date)='" . $requestData['d_from'] . "' and EXISTS( select o.*,lm.created_date as converted_date,lm.created_by as converted_by from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and lm.type='Caller' and lm.previous_name ='' and lm.modify_name in ('" . $iss_names . "')) and o.team_id='" . $_SESSION['team_id'] . "' $dat $contd GROUP BY o.id";
		
	} else {
		$sql = "select o.*,lm.created_date as converted_date,lm.created_by as converted_by from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and lm.type='Lead Type' and lm.previous_name in ('BD','Incoming') and lm.modify_name='LC' and o.lead_type='LC' and  (date(lm.created_date) BETWEEN  '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "') and EXISTS( select o.*,lm.created_date as converted_date,lm.created_by as converted_by from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and lm.type='Caller' and lm.previous_name ='' and lm.modify_name in ('" . $iss_names . "')) and o.team_id='" . $_SESSION['team_id'] . "' $dat $contd GROUP BY o.id";
	}
}else {
	$sql = "select * from ((select o.*,lm.created_date as converted_date,lm.created_by as converted_by from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and lm.type='Lead Type' and lm.previous_name in ('BD','Incoming') and lm.modify_name='LC' and o.lead_type='LC' and o.team_id='" . $_SESSION['team_id'] . "' and EXISTS (select o.*,lm.created_date as converted_date,lm.created_by as converted_by from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and lm.type='Caller' and lm.previous_name ='' and lm.modify_name in ('" . $iss_names . "') and o.team_id='" . $_SESSION['team_id'] . "') $dat $u_cond GROUP BY o.id) UNION ALL (select o.*,lm.created_at as converted_date,lm.sender_id as converted_by from orders as o left join lead_notification lm on o.id=lm.type_id where lm.title='Request BD to LC' and lm.is_read=0 and lm.sender_type='Partner' and o.team_id='" . $_SESSION['team_id'] . "' $dat $u_cond group by lm.type_id)) as i group by i.id order by i.id desc";
}
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
	 OR o.eu_mobile LIKE '%" . $requestData['search']['value'] . "%' )";

	if ($requestData['status'] == 'Pending') {
		$sql = "select o.* from orders as o left join lead_notification lm on o.id=lm.type_id where lm.title='Request BD to LC' and lm.is_read=0 and lm.sender_type='Partner' and o.team_id='" . $_SESSION['team_id'] . "' $dat $u_cond $search group by lm.type_id order by o.id desc";

	} else if($requestData['status'] == 'Completed'){
		$sql = "select * from ((select o.*,lm.created_date as converted_date,lm.created_by as converted_by from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and lm.type='Lead Type' and lm.previous_name in ('BD','Incoming') and lm.modify_name='LC' and o.lead_type='LC' and o.team_id='" . $_SESSION['team_id'] . "' and EXISTS(select o.*,lm.created_date as converted_date,lm.created_by as converted_by from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and lm.type='Caller' and lm.previous_name ='' and lm.modify_name in ('" . $iss_names . "') and o.team_id='" . $_SESSION['team_id'] . "' ) $dat $u_cond $search GROUP BY o.id) UNION ALL (select o.*,lm.created_at as converted_date,lm.sender_id as converted_by from orders as o left join lead_notification lm on o.id=lm.type_id where lm.title='Request BD to LC' and lm.is_read=1 and lm.sender_type='Partner' and o.team_id='" . $_SESSION['team_id'] . "' $dat $u_cond $search group by lm.type_id)) as i group by i.id order by i.id desc";

	}else if ($requestData['d_from'] && $requestData['d_to']) {

		if ($requestData['d_from'] == $requestData['d_to']) {
			$sql = "select o.*,lm.created_date as converted_date,lm.created_by as converted_by from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and lm.type='Lead Type' and lm.previous_name in ('BD','Incoming') and lm.modify_name='LC' and o.lead_type='LC' and DATE(lm.created_date)='" . $requestData['d_from'] . "' and EXISTS( select o.*,lm.created_date as converted_date,lm.created_by as converted_by from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and lm.type='Caller' and lm.previous_name ='' and lm.modify_name in ('" . $iss_names . "')) and o.team_id='" . $_SESSION['team_id'] . "' $dat $contd $search GROUP BY o.id";
			
		} else {
			$sql = "select o.*,lm.created_date as converted_date,lm.created_by as converted_by from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and lm.type='Lead Type' and lm.previous_name in ('BD','Incoming') and lm.modify_name='LC' and o.lead_type='LC' and  (date(lm.created_date) BETWEEN  '" . $requestData['d_from'] . "' and '" . $requestData['d_to'] . "') and EXISTS( select o.*,lm.created_date as converted_date,lm.created_by as converted_by from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and lm.type='Caller' and lm.previous_name ='' and lm.modify_name in ('" . $iss_names . "')) and o.team_id='" . $_SESSION['team_id'] . "' $dat $contd $search GROUP BY o.id";
		}
	}else {
		$sql = "select * from ((select o.*,lm.created_date as converted_date,lm.created_by as converted_by from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and lm.type='Lead Type' and lm.previous_name in ('BD','Incoming') and lm.modify_name='LC' and o.lead_type='LC' and o.team_id='" . $_SESSION['team_id'] . "' and EXISTS(select o.*,lm.created_date as converted_date,lm.created_by as converted_by from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join lead_modify_log lm on o.id=lm.lead_id left join industry as i on o.industry=i.id where tp.product_type_id in (1,2) and o.license_type='Commercial' and lm.type='Caller' and lm.previous_name ='' and lm.modify_name in ('" . $iss_names . "') and o.team_id='" . $_SESSION['team_id'] . "' ) $dat $u_cond $search GROUP BY o.id) UNION ALL (select o.*,lm.created_at as converted_date,lm.sender_id as converted_by from orders as o left join lead_notification lm on o.id=lm.type_id where lm.title='Request BD to LC' and lm.is_read=0 and lm.sender_type='Partner' and o.team_id='" . $_SESSION['team_id'] . "' $dat $u_cond $search group by lm.type_id)) as i group by i.id order by i.id desc";
	}
}


//echo $sql; die;

$query = db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

$sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
//echo $sql; die;

$query = db_query($sql);

$results = array();
$i = 1;
while ($data = db_fetch_array($query)) {  // preparing an array
	// print_r($data);
	$color = '#000';

	$status = getSingleresult("select is_read from lead_notification where title='Request BD to LC' and type_id=" . $data['id']); 

	$lead_status = getSingleresult("select * from lead_modify_log where previous_name in ('BD','Incoming') and modify_name='LC' and type='Lead Type' and lead_id=".$data['id']);

	$nestedData = array();
	$nestedData[] = $i;

	$nestedData[] =  "<a target='_blank' style='display:block;color:" . $color . "' href='partner_view.php?id=" . $data['id'] . "'>" .  $data['r_user'] . '</a>';
	$nestedData[] = "<a target='_blank' style='display:block;color:" . $color . "' href='partner_view.php?id=" . $data['id'] . "'>" . $data['lead_type'] . '</a>';
	$nestedData[] = "<a target='_blank' style='display:block;color:" . $color . "' href='view_order.php?id=" . $data['id'] . "'>" . $data['quantity'] . '</a>';
	$nestedData[] =  "<a target='_blank' style='display:block;color:" . $color . "' href='partner_view.php?id=" . $data['id'] . "'>" . $data['company_name'] . '</a>';
	$nestedData[] = "<a target='_blank' style='display:block;color:" . $color . "' href='partner_view.php?id=" . $data['id'] . "'>" . date('d-m-Y', strtotime($data['created_date'])) . '</a>';

	$nestedData[] =   ($status == 0 && $lead_status == 0) ? '' :date('d-m-Y', strtotime($data['converted_date']));
	$nestedData[] = ($status == 0 && $lead_status == 0) ?'Pending' : 'Completed';
	$nestedData[] =  getSingleresult("select name from callers where id='" . $data['caller'] . "'");

	$nestedData[] = ($status == 0 && $lead_status == 0) ? '' :getSingleresult("select name from users where id='" . $data['converted_by'] . "'");

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
