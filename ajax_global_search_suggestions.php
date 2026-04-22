<?php include('includes/include.php');
$search = trim($_GET['search'] ?? '');
$output = '';

if ($_SESSION['sales_manager'] == 1) {
	$vir_cond = " and o.team_id in (" . $_SESSION['access'] . ") ";
}
if ($_SESSION['sales_manager'] == 1) {
	$region_access=getSingleresult("select region_access from users where id='".$_SESSION['user_id']."'");
	if($region_access) { $regions=explode(',',$region_access);
	$search_region=array();
	foreach($regions as $region)
	{
		$search_region[]="'".$region."'";
	}
	// $vir_cond .= " and region in (" . implode(",",$search_region) . ") ";
}
}
// print_r($_SESSION);die;
if($_SESSION['role'] == 'PARTNER'){
	$sql_lead = " and (o.team_id = ".$_SESSION['team_id']." OR o.allign_team_id=".$_SESSION['team_id'].")";
}else if($_SESSION['user_type'] == 'TEAM LEADER'){
	$callesIds = getSingleResult("SELECT caller from users where id=".$_SESSION['user_id']);
	$callesIdsA = explode(",", $callesIds);
	foreach ($callesIdsA as $value) {
	
	$calleruidQ = getSingleResult("select user_id from callers where id=".$value);
	$caller_userId[]=$calleruidQ;
	}
	$callesIdsForQ = implode(",", $caller_userId);
	$sql_lead = " and o.created_by in (".$callesIdsForQ.")";
}else if($_SESSION['user_type'] == 'CLR'){
	// $sql_lead = " AND ((COALESCE(o.allign_to, '') = '' AND o.created_by = '".$_SESSION['user_id']."') OR (o.allign_to IS NOT NULL AND o.allign_to != '' AND o.allign_to = '".$_SESSION['user_id']."'))";
	$sql_lead = " AND ((o.created_by = '".$_SESSION['user_id']."') OR (o.allign_to = '".$_SESSION['user_id']."'))";
}else if($_SESSION['user_type'] != 'ADMIN' && $_SESSION['user_type'] != 'SUPERADMIN' && $_SESSION['role'] == 'ISS' && $_SESSION['user_type'] != 'CLR'){
	$sql_lead = " and o.created_by = ".$_SESSION['user_id'];
}

if ($search != '') {
	$sql_lead .= " and ( o.code LIKE '%" . $search . "%' ";
	$sql_lead .= " OR o.r_name LIKE '%" . $search . "%' ";
	$sql_lead .= " OR o.school_name LIKE '%" . $search . "%' ";
	$sql_lead .= " OR o.eu_email LIKE '%" . $search . "%' ";
	$sql_lead .= " OR o.eu_mobile LIKE '%" . $search . "%') ";


	$sql_l = "select o.* FROM orders as o where 1=1 ".$vir_cond.$sql_lead;
    // print_r($sql_l);die;
    $query = db_query($sql_l);
    $totalData = mysqli_num_rows($query);

    if ($totalData > 0) {
        while ($row = db_fetch_array($query)) {
            if($row['agreement_type']=='Renewal' && $row['is_opportunity']==1){
                $type = 'Renewal Opportunity';
                $url = "view_opportunity.php";
            }else if($row['agreement_type']=='Fresh' && $row['is_opportunity']==1){
                $type = 'Opportunity';
                $url = "view_opportunity.php";
            }else if($row['agreement_type']=='Fresh' && $row['is_opportunity']==0){
                $type = 'Leads';
                $url = "view_order.php";
            }
            $label = htmlspecialchars($row['school_name'] ?? $row['r_name'] ?? $row['code']);
            $category = '';
            if (!empty($row['school_name'])) $category = 'School';
            elseif (!empty($row['eu_email'])) $category = 'Email';
            elseif (!empty($row['eu_mobile'])) $category = 'Mobile';
            elseif (!empty($row['r_name'])) $category = 'Reseller';
            elseif (!empty($row['code'])) $category = 'Order Code';

            $output .= '
<a href="'.$url.'?id=' . $row['id'] . '" class="search-list">
    <span>'. $label .' ('.$type.')</span>
    <span class="category">'. $category .'</span>
  </a>';
        }
    }
     else {
        $output = '<div class="search-list"><span>No results found</span></div>';
    }
}
echo $output;