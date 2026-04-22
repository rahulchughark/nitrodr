<?php include('includes/include.php');

if ($_GET['d_from'] && $_GET['d_to']) {
    if ($_GET['dtype'] == 'created') {
        if ($_GET['d_from'] == $_GET['d_to']) {
            $cond .= " and DATE(o.created_date)='" . $_GET['d_from'] . "'";
        } else {
            $cond .= " and DATE(o.created_date)>='" . $_GET['d_from'] . "' and DATE(o.created_date)<='" . $_GET['d_to'] . "'";
        }
    } else if ($_GET['dtype'] == 'approved_date') {
        if ($_GET['d_from'] == $_GET['d_to']) {
            $cond .= " and DATE(o.approval_time)='" . $_GET['d_from'] . "'";
        } else {
            $cond .= " and DATE(o.approval_time)>='" . $_GET['d_from'] . "' and DATE(o.approval_time)<='" . $_GET['d_to'] . "'";
        }
    }
}

$partner_arr = $_GET['partner'] ? explode("','", $_GET['partner']) : '';
$partner_arr1 = implode('","', $partner_arr);

if ($_GET['partner']) {
    $cond .= ' and orders.team_id in (' . $partner_arr1 . ')';
}

// if ($_GET['partner']) {
//     $cond .= " and orders.team_id='" . $_GET['partner'] . "'";
// }

// if ($_GET['my'] == 'yes') {
// 	$cond1 = " and orders.caller='" . $caller_id . "'";
// }

$query = db_query("SELECT orders.*, industry.name as industry,sub_ind.name as sub_industry,states.name as state,users.name as created_by,callers.name as caller_name FROM orders LEFT JOIN industry ON orders.industry = industry.id LEFT JOIN sub_industry as sub_ind ON orders.sub_industry = sub_ind.id LEFT JOIN states ON orders.state = states.id LEFT JOIN users ON orders.created_by = users.id LEFT JOIN callers ON orders.caller = callers.id where orders.license_type='Commercial' and orders.dvr_flag=0  $cond ORDER BY orders.id DESC");


$rowCount = mysqli_num_rows($query);
if ($rowCount > 0) {
    $delimiter = ",";
    $filename = "Leads_" . date('Y-m-d') . ".csv";
    ob_start();
    //create a file pointer
    $f = fopen('php://output', 'w');

    //set column headers



    $fields = array('Code', 'Partners', 'Partner_email', 'Partner Users', 'Source', 'Lead Type', 'Company name', 'Parent company', 'Landline', 'Industry', '	Sub Industry', 'Region', 'Address', 'Pincode', 'State', 'City', 'Country', 'eu_name', 'eu_email', 'eu_landline', 'department', 'eu_mobile', 'eu_designation', 'eu_role', 'account_visited', 'visit_remarks', 'confirmation_from', 'license_type', 'quantity', 'created_by', 'created_date', 'status', 'stage', 'Caller Name');


    fputcsv($f, $fields, $delimiter);

    //output each row of the data, format line as csv and write to file pointer
    while ($row = db_fetch_array($query)) {

        @extract($row);
        if ($status == 'Approved') {
            $status = 'Qualified';
        } elseif ($status == 'Cancelled') {
            $status = 'Unqualified';
        } elseif ($status == 'Undervalidation
        ') {
            $status = 'Under Validation';
        }

        $lineData = array($code, $r_name, $r_email, $r_user, $source, $lead_type, htmlspecialchars_decode($company_name, ENT_NOQUOTES), htmlspecialchars_decode($parent_company, ENT_NOQUOTES), $landline, $industry, $sub_industry, $region, htmlspecialchars_decode($address, ENT_NOQUOTES), $pincode, $state, $city, $country, $eu_name, $eu_email, $eu_landline, $department, $eu_mobile, $eu_designation, $eu_role, $account_visited, htmlspecialchars_decode($visit_remarks, ENT_NOQUOTES), $confirmation_from, $license_type, $quantity, $created_by, $created_date, $status, $stage, $caller_name);


        fputcsv($f, $lineData, $delimiter);
        $sub_industry_name = '';
        $sub_industry = '';
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    fpassthru($f);
    ob_end_flush();

} else {

    header("Location: orders_caller.php?m=nodata");
}

exit();
