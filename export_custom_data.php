<?php include('includes/include.php');
ob_start();
    $sql = "select o.*,states.name as state,users.name as created_by,callers.name as caller_name,tag.name as tagName,cities.city as cityName FROM orders as o $joinC LEFT JOIN states ON o.state = states.id LEFT JOIN users ON o.created_by = users.id LEFT JOIN callers ON o.caller = callers.id LEFT JOIN cities ON o.city = cities.id LEFT JOIN tag ON o.tag = tag.id where o.is_opportunity=1 and o.stage in ('PO/CIF Issued','Billing')  and o.agreement_type='Fresh' GROUP BY o.id";    
    //    print_r($query);die;
$query = db_query($sql);
$rowCount = mysqli_num_rows($query);
if($rowCount > 0){
    $filename = "Leads_" . date('Y-m-d') . ".csv";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $f = fopen('php://output', 'w');


    //set column headers
    // $fields = array('Code','Partners' ,'Partner_email','Partner Users', 'Source', 'Lead Type', 'Company name','Parent company', 'Landline','Industry','Sub Industry','Region','Address','Pincode','State','City','Country','eu_name','eu_email','eu_landline','department','eu_mobile','eu_designation','eu_role','account_visited','visit_remarks','confirmation_from','agreement_type','quantity','created_by','created_date','status','stage','Caller');

    $fields = array('S. NO','Product','Quanitity','Lead Source','School Name','School Board','Group Name','State','City','Address','ZIP/Postal Code','Region','Country','Contact No.','Website','E-mail ID','Annual Fees','ICT360 Admin - Name','ICT360 Admin - Designation','ICT360 Admin - Email ID','ICT360 Admin - Contact Number','ICT360 Admin - Alternative Contact Number','Operational Boards in School','Start Date of ICT360 Program in school','School Academic Year Start Date','School Academic Year End Date','Grades Signed Up For ICT 360','Student count for selected grades','Purchase Order No.','Date of Application','Purchase details','Purchase/ Renewal years','App/ ERP System','School IP Address','No. of Labs','Number of laptop/desktop','Operating systems used in ICT Labs','Student system ratio','Lab teacher ratio','Standalone PC','Projector','TV','Smart Board','Internet','Networking','Thin client','N Computing','Tag','Remarks','Lead Status','Closing Status','Reason','Caller','Stage','Sub Stage','Record Type','Expected Close Date');

    fputcsv($f, $fields);
    $s_no = 1;    
    //output each row of the data, format line as csv and write to file pointer
    while($data = db_fetch_array($query)){
        @extract($data);
        	$products = array();
            $quantitys = 0;
            $mainProId = array();
            $proquery = db_query("SELECT * FROM tbl_lead_product_opportunity where status=1 and lead_id =" . $data['id']);
            $count = mysqli_num_rows($proquery);
            if ($count) {
                while ($data_n = db_fetch_array($proquery)) { 
                    if (!in_array($data_n['main_product_id'], $mainProId)) {
                        $mainProId[] = $data_n['main_product_id'];
                        $ppro = getSingleresult("SELECT name FROM tbl_main_product_opportunity where id=".$data_n['main_product_id']);
                        if(!empty($ppro)){
                            $products[] = $ppro;
                        }
                    }
                    $quantitys = $quantitys+$data_n['quantity'];
                }
            }
            $productsE = $products ? (count($products) > 1 ? implode(' + ',$products) : $products[0]) : '';

        $lineData = array($s_no,$productsE,$quantitys,$source,$school_name,$school_board,$group_name,$state,$cityName,htmlspecialchars_decode($address, ENT_NOQUOTES),$pincode,$region,'India',$contact,$website,$school_email,$annual_fees,$eu_name,$eu_designation,$eu_email,$eu_mobile,'',$school_board,$program_start_date,$academic_start_date,$academic_end_date,$grade_signed_up,$quantity,$purchase_no,$application_date,$purchase_deails,$license_period,$is_app_erp,$ip_address,$labs_count,$system_count,$os,$student_system_ratio,$lab_teacher_ratio,$standalone_pc,$projector,$tv,$smart_board,$internet,$networking,$thin_client,$n_computing,$tagName,$visit_remarks,$lead_status,$status,$reason,$caller_name,$stage,$add_comm,$record_type,$expected_close_date);


 
            fputcsv($f, $lineData);
$s_no ++;            
    }
    
   fclose($f);
}
else {
header("Location: search_orders.php?m=nodata");    
}
exit();
