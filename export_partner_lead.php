<?php include('includes/include.php');


$subString = '';
$userType = $_SESSION['user_type'];
$partner = $_SESSION['team_id'];

if ($userType != 'ADMIN') {
    $subString = " orders.team_id = '" . $partner . "'";
}





if ($_GET['d_from'] && $_GET['d_to']) {
    if ($_GET['d_from'] == $_GET['d_to']) {

        $query = db_query("SELECT orders.*,p.*,tp.product_name,tpp.product_type,states.name as state,users.name as created_by,callers.name as caller_name FROM orders LEFT JOIN industry ON orders.industry = industry.id LEFT JOIN states ON orders.state = states.id LEFT JOIN users ON orders.created_by = users.id left join tbl_lead_product as p on orders.id=p.lead_id left join tbl_product as tp on p.product_id=tp.id left join tbl_product_pivot as tpp on p.product_type_id=tpp.id LEFT JOIN callers ON orders.caller = callers.id where $subString and  DATE(orders.created_date)='" . $_GET['d_from'] . "' and orders.is_iss_lead = 0 and orders.dvr_flag=0  and orders.agreement_type!='Renewal' GROUP BY p.lead_id ORDER BY orders.id DESC ");
    } else {

        $query = db_query("SELECT orders.*,p.*,tp.product_name,tpp.product_type,states.name as state,users.name as created_by,callers.name as caller_name FROM orders LEFT JOIN industry ON orders.industry = industry.id LEFT JOIN states ON orders.state = states.id LEFT JOIN users ON orders.created_by = users.id left join tbl_lead_product as p on orders.id=p.lead_id left join tbl_product as tp on p.product_id=tp.id left join tbl_product_pivot as tpp on p.product_type_id=tpp.id LEFT JOIN callers ON orders.caller = callers.id where $subString and DATE(orders.created_date)>='" . $_GET['d_from'] . "' and DATE(orders.created_date)<='" . $_GET['d_to'] . "' and orders.is_iss_lead = 0 and orders.dvr_flag=0  and orders.agreement_type!='Renewal' GROUP BY p.lead_id ORDER BY orders.id DESC");
    }
    //print_r($query);
} else {

    $query = db_query("SELECT orders.*,p.*,tp.product_name,tpp.product_type,states.name as state,users.name as created_by,callers.name as caller_name FROM orders LEFT JOIN industry ON orders.industry = industry.id LEFT JOIN states ON orders.state = states.id LEFT JOIN users ON orders.created_by = users.id left join tbl_lead_product as p on orders.id=p.lead_id left join tbl_product as tp on p.product_id=tp.id left join tbl_product_pivot as tpp on p.product_type_id=tpp.id LEFT JOIN callers ON orders.caller = callers.id where $subString and orders.is_iss_lead = 0 and orders.dvr_flag=0  and orders.agreement_type!='Renewal' GROUP BY p.lead_id ORDER BY orders.id DESC");
}





$rowCount = mysqli_num_rows($query);
if ($rowCount > 0) {
    $delimiter = ",";
    $filename = "Leads_" . date('Y-m-d') . ".csv";
    ob_start();
    //create a file pointer
    $f = fopen('php://output', 'w');

    //set column headers



    $fields = array('Code', 'Partners', 'Partner_email', 'Partner Users', 'Product Name', 'Source', 'Lead Type', 'Company name', 'Parent company', 'Landline', 'Address', 'Pincode', 'State', 'City', 'Country', 'eu_name', 'eu_email', 'eu_landline', 'department', 'eu_mobile', 'eu_designation', 'eu_role', 'account_visited', 'visit_remarks', 'confirmation_from', 'agreement_type', 'quantity', 'created_by', 'created_date', 'status', 'stage', 'Caller');


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

        $new = db_query("select id,description,created_date,added_by from activity_log where pid='" . $id . "' and is_intern=0 UNION SELECT id,description,created_date,added_by from caller_comments where pid='" . $id . "' order by created_date desc");

        $count = mysqli_num_rows($new);
        $remarks = '';
        $i = $count;
        if ($count) {
            while ($data_n = db_fetch_array($new)) {
                $remarks .= "\n" . $i . ' [' . ($data_n['added_by'] ? getSingleresult("select concat(name,'-',CASE  WHEN user_type='USR' THEN 'Partner User'    WHEN user_type='ADMIN' and sales_manager=0 THEN 'ADMIN' WHEN user_type='ADMIN' and sales_manager=1 THEN 'Sales Manager'   WHEN user_type='MNGR' THEN 'Partner Manager'  ELSE 'Caller' END) as nme from users where id='" . $data_n['added_by'] . "'") : 'N/A') . ' on ' . date('d-m-Y H:i:s', strtotime($data_n['created_date'])) . ']:' . $data_n['description'] . "\n";
                $i--;
            }
        }

        $remarks .= date('d-m-Y H:i:s', strtotime($created_date)) . ':' . $visit_remarks;


        $lineData = array($code, $r_name, $r_email, $r_user, $product_name,$source, $lead_type, htmlspecialchars_decode($company_name, ENT_NOQUOTES), htmlspecialchars_decode($parent_company, ENT_NOQUOTES), $landline,htmlspecialchars_decode($address, ENT_NOQUOTES), $pincode, $state, $city, $country, $eu_name, $eu_email, $eu_landline, $department, $eu_mobile, $eu_designation, $eu_role, $account_visited, htmlspecialchars_decode($remarks, ENT_NOQUOTES), $confirmation_from, $agreement_type, $quantity, $created_by, $created_date, $status, $stage, $caller_name);


        fputcsv($f, $lineData, $delimiter);
       
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    fpassthru($f);
    ob_end_flush();
} else {
    header("Location: orders.php?m=nodata");
}
exit();
