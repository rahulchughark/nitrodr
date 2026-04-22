<?php include('includes/include.php');
// print_r($_GET);die;
if ($_SESSION['sales_manager'] == 1) {
	$vir_cond = " and o.team_id in (" . $_SESSION['access'] . ") ";
}
if($_SESSION['role'] == 'PARTNER'){
	$vir_cond = " and o.team_id = ".$_SESSION['team_id'];
}else if($_SESSION['user_type'] == 'TEAM LEADER'){
	$callesIds = getSingleResult("SELECT caller from users where id=".$_SESSION['user_id']);
	$callesIdsA = explode(",", $callesIds);
	foreach ($callesIdsA as $value) {
	
	$calleruidQ = getSingleResult("select user_id from callers where id=".$value);
	$caller_userId[]=$calleruidQ;
	}
	$callesIdsForQ = implode(",", $caller_userId);
	$vir_cond = " and o.created_by in (".$callesIdsForQ.")";
}else if($_SESSION['user_type'] != 'ADMIN' && $_SESSION['user_type'] != 'SUPERADMIN' && $_SESSION['role'] == 'ISS'){
	$vir_cond = " and o.created_by = ".$_SESSION['user_id'];
}

if($_GET['d_from'] && $_GET['d_to'])
{
   
	if($_GET['d_type']== 'close'){
		if($_GET['d_from'] == $_GET['d_to'])
		{
			$dat=" and DATE(o.expected_close_date)='".$_GET['d_from']."'";	
		} else {
			$dat=" and DATE(o.expected_close_date)>='".$_GET['d_from']."' and DATE(o.expected_close_date)<='".$_GET['d_to']."'";	
		}
	}elseif($_GET['d_type']== 'actioned_date'){
		if($_GET['d_from'] == $_GET['d_to'])
		{
			$dat=" and DATE(o.approval_time)='".$_GET['d_from']."'";	
		} else {
			$dat=" and DATE(o.approval_time)>='".$_GET['d_from']."' and DATE(o.approval_time)<='".$_GET['d_to']."'";	
		}
	}else if($_GET['d_type']== 'stage' && $_GET['stage']){
		$joinC = " left join lead_modify_log as l on o.id=l.lead_id ";
		if($_GET['d_from'] == $_GET['d_to'])
		{
			$dat=" and DATE(l.created_date)='".$_GET['d_from']."' and l.modify_name in ('".$_GET['stage']."')";	
		} else {
			$dat=" and DATE(l.created_date)>='".$_GET['d_from']."' and DATE(l.created_date)<='".$_GET['d_to']."' and l.modify_name in ('".$_GET['stage']."')";	
		}
	}else if($_GET['d_type']== 'lead_status' && $_GET['lead_status']){
		$joinC = " left join lead_modify_log as l on o.id=l.lead_id ";
		if($_GET['d_from'] == $_GET['d_to'])
		{
			$dat=" and DATE(l.created_date)='".$_GET['d_from']."' and l.type='Status' and l.modify_name in ('".$_GET['lead_status']."')";	
		} else {
			$dat=" and DATE(l.created_date)>='".$_GET['d_from']."' and DATE(l.created_date)<='".$_GET['d_to']."' and l.type='Status' and l.modify_name in ('".$_GET['lead_status']."')";	
		}
	}elseif($_GET['d_type']== 'opportunity_converted'){
      
		$joinC = " left join lead_modify_log as l on o.id=l.lead_id ";
		if($_GET['d_from'] == $_GET['d_to'])
		{
			$dat=" and ((DATE(l.created_date)='".$_GET['d_from']."' and l.type='Opportunity'))";
		} else {
			$dat=" and ((DATE(l.created_date)>='".$_GET['d_from']."' and DATE(l.created_date)<='".$_GET['d_to']."' and l.type='Opportunity'))";
		}
	}else{
		if($_GET['d_from'] == $_GET['d_to'])
		{
			$dat=" and DATE(o.created_date)='".$_GET['d_from']."'";	
		} else {
			$dat=" and DATE(o.created_date)>='".$_GET['d_from']."' and DATE(o.created_date)<='".$_GET['d_to']."'";	
		}
	}
}
if($_GET['stage'])
{
	$dat.=" and o.stage in ('".$_GET['stage']."')";
}
if($_GET['substage'])
{
	$dat.=" and o.add_comm in ('".$_GET['substage']."')";
}
if($_GET['tag'])
{
	$dat.=" and o.tag in ('".$_GET['tag']."')";
}
if($_GET['state'])
{
    $dat.=" and o.state in (".$_GET['state'].")";
}

$partnerFtr = $_REQUEST['partner'] ? explode(",",$_REQUEST['partner']) : '';
$usersFtr =  $_REQUEST['users'] ? explode(",",$_REQUEST['users']) : '';
if(!empty($partnerFtr) && empty($usersFtr))
{
	$teamUsers = db_query("SELECT id from users where team_id in (".implode(',',$partnerFtr).")");
	$usrrArr = [];
	while ($usr = db_fetch_array($teamUsers)){
		$usrrArr[] = $usr['id'];
	}
	$dat.=" AND ((o.created_by IN (" . implode(",", $usrrArr) . ")) OR (o.allign_to IN (" . implode(",", $usrrArr) . ")))";
}
if(!empty($usersFtr))
{
	$dat.=" AND ((o.created_by IN (" . implode(",", $usersFtr) . ")) OR (o.allign_to IN (" . implode(",", $usersFtr) . ")))";
}

if($_GET['sub_stage'])
{
	$dat.=" and o.add_comm in ('".$_GET['sub_stage']."')";
}
if($_GET['lead_status'])
{
	$dat.=" and o.lead_status in ('".$_GET['lead_status']."')";
}

if($_GET['status'])
{
	$dat.=" and o.status in ('".$_GET['status']."')";
}
if($_GET['source'])
{
	$dat.=" and o.source in ('".$_GET['source']."')";
}
    // $license = $_GET['license'];

       $query = db_query("SELECT o.*,states.name as state,users.name as created_by,callers.name as caller_name,tag.name as tagName,cities.city as cityName FROM orders as o $joinC LEFT JOIN states ON o.state = states.id LEFT JOIN users ON o.created_by = users.id LEFT JOIN callers ON o.caller = callers.id LEFT JOIN cities ON o.city = cities.id LEFT JOIN tag ON o.tag = tag.id where o.is_opportunity=1 AND o.agreement_type = ".$_GET['license']. $dat .$vir_cond." GROUP BY o.id ORDER BY o.id DESC");
       
//    print_r($query);die;

$rowCount = mysqli_num_rows($query);
if($rowCount > 0){
    $filename = "Opportunity_" . date('Y-m-d') . ".csv";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $f = fopen('php://output', 'w');


    //set column headers
    // $fields = array('Code','Partners' ,'Partner_email','Partner Users', 'Source', 'Lead Type', 'Company name','Parent company', 'Landline','Industry','Sub Industry','Region','Address','Pincode','State','City','Country','eu_name','eu_email','eu_landline','department','eu_mobile','eu_designation','eu_role','account_visited','visit_remarks','confirmation_from','agreement_type','quantity','created_by','created_date','status','stage','Caller');

    $fields = array('S. NO','Reseller Name','Reseller Email','Submitted By','Aligned To','Product','Quanitity','Date of Visit','Lead Source','Sub Lead Source','School Name','School Board','Billing Reseller','Credit Reseller','Group Name','State','City','Address','ZIP/ Postal Code','Region','Country','Contact No.','Website','E-mail ID','Annual Fees','Decision Maker - Full Name','Decision Maker - Email','Decision Maker - Contact Number','Person 1st - Name','Person 1st - Designation','Person 1st - Contact Number','Person 1st - Email ID','Person 2nd - Name','Person 2nd - Contact Number','Person 2nd - Email ID','ICT360 Admin - Name','ICT360 Admin - Designation','ICT360 Admin - Email ID','ICT360 Admin - Contact Number','ICT360 Admin - Alternative Contact Number','Operational Boards in School','Start Date of ICT360 Program in school','School Academic Year Start Date','School Academic Year End Date','Grades Signed Up For ICT 360','Student count for selected grades','Purchase Order No.','Date of Application','Purchase details','Purchase/ Renewal years','App/ ERP System','School IP Address','No. of Labs','Number of laptop/desktop','Operating systems used in ICT Labs','Student system ratio','Lab teacher ratio','Standalone PC','Projector','TV','Smart Board','Internet','Networking','Thin client','N Computing','Tag','Remarks','Lead Status','Closing Status','Reason','Caller','Stage','Sub Stage','Record Type','Expected Close Date');


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

        // $lineData = array($s_no,$r_name.' ('.$r_user.')',$tagName,$source,$quantity,htmlspecialchars_decode($school_name, ENT_NOQUOTES),htmlspecialchars_decode($address, ENT_NOQUOTES),$state,$cityName,$pincode,$eu_name,$eu_mobile,$eu_email,$agreement_type,$created_date,$status,$stage,$caller_name,$visit_remarks);

        $allign_too = $allign_to ? getSingleresult("select name from users where id=" . $allign_to) : '';

		$products = array();
		$quantitys = 0;
		$mainProId = array();
		$proquery = db_query("SELECT * FROM tbl_lead_product_opportunity where status=1 and lead_id =" . $id);
		$count = mysqli_num_rows($proquery);
		if ($count) {
			while ($data_n = db_fetch_array($proquery)) { 
				if (!in_array($data_n['main_product_id'], $mainProId)) {
					$mainProId[] = $data_n['main_product_id'];
					$products[] = getSingleresult("SELECT name FROM tbl_main_product_opportunity where id=".$data_n['main_product_id']);
				}
				$quantitys = $quantitys+$data_n['quantity'];
			}
		}
		$products = $products ? implode(' + ',$products) : '';
        $billR = $billing_reseller ? getSingleresult("SELECT name from partners where id=".$billing_reseller) : '';
        $credR = $credit_reseller ? getSingleresult("SELECT name from partners where id=".$credit_reseller) : '';

        $lineData = array($s_no,$r_name,$r_email,$r_user,$allign_too,$products,$quantitys,$created_date,$source,$sub_lead_source,$school_name,$school_board,$billR,$credR,$group_name,$state,$cityName,htmlspecialchars_decode($address, ENT_NOQUOTES),$pincode,$region,'India',$contact,$website,$school_email,$annual_fees,$eu_name,$eu_email,$eu_mobile,$eu_person_name1,$eu_designation1,$eu_mobile1,$eu_email1,$eu_person_name2,$eu_mobile2,$eu_email2,$adm_name,$adm_designation,$adm_email,$adm_mobile,$adm_alt_mobile,$school_board,$program_start_date,$academic_start_date,$academic_end_date,$grade_signed_up,$quantity,$purchase_no,$application_date,$purchase_deails,$license_period,$is_app_erp,$ip_address,$labs_count,$system_count,$os,$student_system_ratio,$lab_teacher_ratio,$standalone_pc,$projector,$tv,$smart_board,$internet,$networking,$thin_client,$n_computing,$tagName,$visit_remarks,$lead_status,$status,$reason,$caller_name,$stage,$add_comm,$record_type,$expected_close_date);
 
            fputcsv($f, $lineData);
$s_no ++;            
    }
    
   fclose($f);
}
else {
header("Location: manage_opportunity.php?m=nodata");    
}
exit();
