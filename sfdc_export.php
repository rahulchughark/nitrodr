<?php include('includes/include.php');

    if($_GET['d_from'] && $_GET['d_to'])
    {
        if ($_GET['dtype'] == 'created') {
            if ($_GET['d_from'] == $_GET['d_to']) {
                $dat = " and DATE(orders.created_date)='" . $_GET['d_from'] . "'";
            } else {
                $dat = " and DATE(orders.created_date)>='" . $_GET['d_from'] . "' and DATE(orders.created_date)<='" . $_GET['d_to'] . "'";
            } 
        }   
        else if($_GET['dtype']=='approved_date')
        {
            if($_GET['d_from'] == $_GET['d_to'])
            {
            $dat=" and DATE(orders.approval_time)='".$_GET['d_from']."'";   
            } else {
            $dat=" and DATE(orders.approval_time)>='".$_GET['d_from']."' and DATE(orders.approval_time)<='".$_GET['d_to']."'";  
            }
        } 

    }

    // $license = $_GET['license'];

       $query = db_query("SELECT orders.*,partners.reseller_id,industry.name as industry,sub_ind.name as sub_industry,states.name as state,users.name as created_by,callers.name as caller_name FROM orders LEFT JOIN industry ON orders.industry = industry.id LEFT JOIN sub_industry as sub_ind ON orders.sub_industry = sub_ind.id LEFT JOIN states ON orders.state = states.id LEFT JOIN users ON orders.created_by = users.id LEFT JOIN callers ON orders.caller = callers.id LEFT JOIN partners ON orders.team_id = partners.id where  orders.license_type = '".$_GET['license']."' and orders.dvr_flag!=1 and sfdc_check=0 ". $dat ." GROUP BY orders.id ORDER BY orders.id DESC");
       
   // print_r($query);die;

$rowCount = mysqli_num_rows($query);
if($rowCount > 0){
    $filename = "Leads_" . date('Y-m-d') . ".csv";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $f = fopen('php://output', 'w');


    //set column headers
    // $fields = array('Code','Partners' ,'Partner_email','Partner Users', 'Source', 'Lead Type', 'Company name','Parent company', 'Landline','Industry','Sub Industry','Region','Address','Pincode','State','City','Country','eu_name','eu_email','eu_landline','department','eu_mobile','eu_designation','eu_role','account_visited','visit_remarks','confirmation_from','license_type','quantity','created_by','created_date','status','stage','Caller','Website');

    // $fields = array('Company name','eu_name','eu_landline','eu_mobile','eu_email','Region','Lead Type','Runrate/Key','license_type','quantity','Address','City','Pincode','State','Website','Industry','Sub Industry','Source','Partners');

    $fields = array('S. NO','DR Code','Reseller Name(Submitted By)','Lead Source','Lead Type','Quantity','Company name','Website','Industry','Sub Industry','Address','State','City','Pincode','Contact Person Name','Contact Number','Email Id','Type Of License','Type Of Software','Runrate/Key','Date Of Submission','Status','Stage','Caller Name','Close Date');


    fputcsv($f, $fields);
    $s_no = 1;
    //output each row of the data, format line as csv and write to file pointer
    while($row = db_fetch_array($query)){
        
        @extract($row); 
        if($status=='Approved') {
            $status='Qualified';
        }
        else if($status=='Cancelled') {
            $status='Unqualified';
        }
         else if ($status == 'Pending') {
             $status = 'Pending';
        } else if ($status == 'Undervalidation') {
            $status = 'Re-Submission Required';
        } else if ($status == 'On-Hold') {
            $status = 'On-Hold';
        } else if ($status == 'Already locked') {
            $status = 'Already locked';
        } else if ($status == 'Insufficient Information') {
            $status = 'Insufficient Information';
        } else if ($status == 'Incorrect Information') {
            $status = 'Incorrect Information';
        } else if ($status == 'Out Of Territory') {
            $status = 'Out Of Territory';
        } else if ($status == 'Duplicate Record Found') {
            $status = 'Duplicate Record Found';
        }
        
        $new=db_query("select id,description,created_date,added_by from activity_log where pid='".$id."' and is_intern=0 UNION SELECT id,description,created_date,added_by from caller_comments where pid='".$id."'  order by created_date desc");
        
        $count=mysqli_num_rows($new);
        $remarks='';
        $i = $count;
        if ($count) {
            while ($data_n = db_fetch_array($new)) {
                $remarks .= "\n" . $i . ' [' . ($data_n['added_by'] ? getSingleresult("select concat(name,'-',CASE  WHEN user_type='USR' THEN 'Partner User'    WHEN user_type='ADMIN' and sales_manager=0 THEN 'ADMIN' WHEN user_type='ADMIN' and sales_manager=1 THEN 'Sales Manager'   WHEN user_type='MNGR' THEN 'Partner Manager'  ELSE 'Caller' END) as nme from users where id='" . $data_n['added_by'] . "'") : 'N/A') . ' on ' . date('d-m-Y H:i:s', strtotime($data_n['created_date'])) . ']:' . $data_n['description'] . "\n";
                $i--;
            }
        }
        

        $remarks .= date('d-m-Y H:i:s', strtotime($created_date)) . ':' . $visit_remarks;

   
        // $lineData = array($code,$r_name,$r_email ,$r_user ,'Reseller' ,$lead_type ,htmlspecialchars_decode($company_name, ENT_NOQUOTES) ,htmlspecialchars_decode($parent_company, ENT_NOQUOTES) ,$landline ,$industry ,$sub_industry ,$region ,htmlspecialchars_decode($address, ENT_NOQUOTES) ,$pincode ,$state ,$city ,$country ,$eu_name ,$eu_email ,$eu_landline ,$department ,$eu_mobile ,$eu_designation ,$eu_role ,$account_visited , htmlspecialchars_decode($remarks, ENT_NOQUOTES) ,$confirmation_from ,$license_type ,$quantity ,$created_by ,$created_date ,$status ,$stage,$caller_name,$website);

        // $lineData = array(htmlspecialchars_decode($company_name, ENT_NOQUOTES),$eu_name,$eu_landline,$eu_mobile,$eu_email,$region,$lead_type,$runrate_key,$license_type,$quantity,htmlspecialchars_decode($address, ENT_NOQUOTES),$city,$pincode,$state,$website,$industry ,$sub_industry,'Reseller',$reseller_id);

        $lineData = array($s_no,$code,$r_name.' ('.$r_user.')',$source,$lead_type,$quantity,htmlspecialchars_decode($company_name, ENT_NOQUOTES),$website,$industry,$sub_industry,htmlspecialchars_decode($address, ENT_NOQUOTES),$state,$city,$pincode,$eu_name,$eu_mobile,$eu_email,$license_type,$software_type,$runrate_key,$created_date,$status,$stage,$caller_name,$partner_close_date);
 
            fputcsv($f, $lineData);
        $s_no ++;            
    }
    
   fclose($f);
}
else {
header("Location: orders.php?m=nodata");    
}
exit();
