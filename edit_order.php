<?php 
include('includes/header.php');
include_once('helpers/DataController.php');

$helperData = new DataController();


$query = selectEditDetails($_REQUEST['eid'],' ');
//$sql = db_query($query);
$row = db_fetch_array($query);

@extract($row);
$selectedBoard = $school_board; // Replace with the actual value from the database.
$predefinedBoards = ["CBSE", "ICSE", "IB", "IGCSE", "STATE"];
$isOtherBoard = !in_array($selectedBoard, $predefinedBoards);

$sql_order_important = db_query("select eu_person_name,eu_designation,eu_mobile,eu_email from order_important_person where order_id='" . $_REQUEST['eid'] . "'");

if($created_by != $_SESSION['user_id'] && $_SESSION['user_type'] != 'ADMIN' && $_SESSION['user_type'] != 'SUPERADMIN' && $_SESSION['user_type'] != 'TEAM LEADER' && $_SESSION['user_type'] != 'CLR' && $_SESSION['user_type'] != 'RM')
{
  
    if($_SESSION['role'] == 'ISS'){
        redir("manage_orders.php");
    }else{
        redir("orders.php");
    }
}

$gradesSigned = $grade_signed_up;

if(isset($_POST['lead_status']) && $_POST['lead_status'] != '')
{

    $current_date = date('Y-m-d H:i:s');
    
    function log_change($lead_id, $type, $previous_name, $modify_name, $user_id) {
        db_query("INSERT INTO lead_modify_log(`lead_id`, `type`, `previous_name`, `modify_name`, `created_date`, `created_by`)
                  VALUES('$lead_id', '$type', '$previous_name', '$modify_name', NOW(), '$user_id')");
    }
    
    // Array of fields to monitor and their corresponding types
    $fields_to_monitor = [
        'lead_status' => 'Lead Status',
        'source' => 'Lead Source',
        'sub_lead_source' => 'Sub Lead Source',
        'billing_reseller' => 'Billing Reseller',
        'credit_reseller' => 'Credit Reseller',
        'school_name' => 'Organization Name',
        'is_group' => 'Is Group',
        'group_name' => 'Group Name',
        'spoc' => 'SPOC',
        'address' => 'Address',
        'state' => 'State',
        'city' => 'City',
        'region' => 'Region',
        'country' => 'Country',
        'pincode' => 'Pincode',
        'contact' => 'Contact No',
        'website' => 'Website',
        'school_email' => 'School Email',
        'annual_fees' => 'Annual Fees',
        'eu_name' => 'EU Name',
        'eu_mobile' => 'EU Mobile',
        'eu_email' => 'EU Email',
        'eu_designation' => 'EU Designation',
        'eu_person_name1' => 'EU Person Name 1',
        'eu_designation1' => 'EU Designation 1',
        'eu_mobile1' => 'EU Mobile 1',
        'eu_email1' => 'EU Email 1',
        'eu_person_name2' => 'EU Person Name 2',
        'eu_mobile2' => 'EU Mobile 2',
        'eu_email2' => 'EU Email 2',
        'adm_name' => 'Admin Name',
        'adm_designation' => 'Admin Designation',
        'adm_email' => 'Admin Email',
        'adm_mobile' => 'Admin Mobile',
        'adm_alt_mobile' => 'Admin Alt Mobile',
        'school_board' => 'School Board',
        'program_start_date' => 'Program Start Date',
        'academic_start_date' => 'Academic Start Date',
        'academic_end_date' => 'Academic End Date',
        'grade_signed_up' => 'Grade Signed Up',
        'quantity' => 'Student Count',
        'purchase_no' => 'Purchase No',
        'application_date' => 'Application Date',
        'purchase_deails' => 'Purchase Details',
        'license_period' => 'License Period',
        'is_app_erp' => 'Is App ERP',
        'ip_address' => 'IP Address',
        'labs_count' => 'Labs Count',
        'system_count' => 'System Count',
        'os' => 'OS',
        'student_system_ratio' => 'Student System Ratio',
        'lab_teacher_ratio' => 'Lab Teacher Ratio',
        'standalone_pc' => 'Standalone PC',
        'projector' => 'Projector',
        'tv' => 'TV',
        'smart_board' => 'Smart Board',
        'internet' => 'Internet',
        'networking' => 'Networking',
        'thin_client' => 'Thin Client',
        'n_computing' => 'N Computing',
        'tag'=>'Tag',
        'visit_remarks'=>'Remarks',
        'current_being_used_school'=>'Current Being Used School',
        'kits_related_hardware_school'=>'Kits Related Hardware School',
        'service_provider'=>'Service Provider',
    ];
    
    // Check each field and log changes
    foreach ($fields_to_monitor as $field => $type) {
        if($field == 'quantity'){
            if($_POST['student_count']){
                if ($row['quantity'] != $_POST['student_count']) {
                    log_change($_REQUEST['eid'], $type, $row['quantity'], $_POST['student_count'], $_SESSION['user_id']);
                }
                $quantityI = $_POST['student_count'];
            }else{
                $quantityI = $row['quantity'];
            }
        }else if($field == 'grade_signed_up'){
            if($_POST['grade_signed_up']){
                $gradesSigned = implode(", ", $_POST['grade_signed_up']);
                if ($row['grade_signed_up'] != $gradesSigned) {
                    log_change($_REQUEST['eid'], $type, $row['grade_signed_up'], $gradesSigned, $_SESSION['user_id']);
                }
                $grade_signed_upI = $gradesSigned;
            }else{
                $grade_signed_upI = $row['grade_signed_up'];
            }
        }else if($field == 'billing_reseller'){
            if($_POST['billing_reseller']){
                if ($row['billing_reseller'] != $_POST['billing_reseller']) {
                    $prev_name = $row['billing_reseller'] > 0 ? getSingleresult("select name from partners where id=".$row['billing_reseller']) : 'N/A';
                    $modify_name = getSingleresult("select name from partners where id=".$_POST['billing_reseller']);
                    log_change($_REQUEST['eid'], $type, $prev_name, $modify_name, $_SESSION['user_id']);
                }
                $billing_resellerI = $_POST['billing_reseller'];
            }else{
                $billing_resellerI = $row['billing_reseller'];
            }
        }else if($field == 'credit_reseller'){
            if($_POST['credit_reseller']){
                if ($row['credit_reseller'] != $_POST['credit_reseller']) {
                    $prev_name = $row['credit_reseller'] > 0 ? getSingleresult("select name from partners where id=".$row['credit_reseller']) : 'N/A';
                    $modify_name = getSingleresult("select name from partners where id=".$_POST['credit_reseller']);
                    log_change($_REQUEST['eid'], $type, $prev_name, $modify_name, $_SESSION['user_id']);
                }
                $credit_resellerI = $_POST['credit_reseller'];
            }else{
                $credit_resellerI = $row['credit_reseller'];
            }
            
        }else if($field == 'spoc'){
            if($_POST['spoc']){
                if ($row['spoc'] != $_POST['spoc']) {
                    $prev_name = $row['spoc'] ? getSingleresult("select name from users where id=".$row['spoc']) : '';
                    $modify_name = getSingleresult("select name from users where id=".$_POST['spoc']);
                    log_change($_REQUEST['eid'], $type, $prev_name, $modify_name, $_SESSION['user_id']);
                }
                $spocI = $_POST['spoc'];
            }else{
                $spocI = $row['spoc'];
            }
            
        }else if($field == 'state'){
            if($_POST['state']){
                if ($row['state'] != $_POST['state']) {
                    $prev_name = $row['state'] ? getSingleresult("select name from states where id=".$row['state']) : 'N/A';
                    $modify_name = getSingleresult("select name from states where id=".$_POST['state']);
                    log_change($_REQUEST['eid'], $type, $prev_name, $modify_name, $_SESSION['user_id']);
                }
                $stateI = $_POST['state'];
            }else{
                $stateI = $row['state'];
            }
            
        }else if($field == 'city'){
            if($_POST['city']){
                if ($row['city'] != $_POST['city']) {
                    $prev_name = $row['city'] ? getSingleresult("select city from cities where id=".$row['city']) : 'N/A';
                    $modify_name = getSingleresult("select city from cities where id=".$_POST['city']);
                    log_change($_REQUEST['eid'], $type, $prev_name, $modify_name, $_SESSION['user_id']);
                }
                $cityI = $_POST['city'];
            }else{
                $cityI = $row['city'];
            }
            
        }else if($field == 'tag'){
            if($_POST['tag']){
                if ($row['tag'] != $_POST['tag']) {
                    $prev_name = $row['tag'] ? getSingleresult("select name from tag where id=".$row['tag']) : 'N/A';
                    $modify_name = $_POST['tag'] ? getSingleresult("select name from tag where id=".$_POST['tag']) : 'N/A';
                    log_change($_REQUEST['eid'], $type, $prev_name, $modify_name, $_SESSION['user_id']);
                }
                $tagI = $_POST['tag'];
            }else{
                $tagI = $row['tag'];
            }
            
        }else if($field == 'sub_lead_source'){
            if($_POST['sub_lead_source']){
                if ($row['sub_lead_source'] != $_POST['sub_lead_source']) {
                    $prev_name = $row['sub_lead_source'];
                    $modify_name = $_POST['sub_lead_source'];
                    log_change($_REQUEST['eid'], $type, $prev_name, $modify_name, $_SESSION['user_id']);
                }
                $sub_lead_sourceI = $_POST['sub_lead_source'];
            }else{
                $sub_lead_sourceI = '';
            }
            
        }else if($field == 'school_board'){
            if($_POST['school_board']){
                $schoolboard = $_POST['school_board'] == 'Others' ? $_POST['other_board'] : $_POST['school_board'];
                if ($row['school_board'] != $schoolboard) {
                    $prev_name = $row['school_board'];
                    $modify_name = $schoolboard;
                    log_change($_REQUEST['eid'], $type, $prev_name, $modify_name, $_SESSION['user_id']);
                }
                $sub_lead_sourceI = $_POST['sub_lead_source'];
            }else{
                $sub_lead_sourceI = '';
            }   
        }else if ($row[$field] != $_POST[$field] ) {
            if(isset($_POST[$field])){
                $modify_name = $_POST[$field] != '' ?  $_POST[$field] : 'N/A';
                $prev_name =  $row[$field] != '' ?   $row[$field] : 'N/A';
                log_change($_REQUEST['eid'], $type, $prev_name, $modify_name, $_SESSION['user_id']);
                $insertData[$field] = $_POST[$field];
            }else{
                $insertData[$field] = $row[$field];
            }
        }else{
            $insertData[$field] = $row[$field];
        }
    }
    // echo "<br><br><br><br><br><br>";
    // echo "<pre>";
    // print_r($insertData);die;
    // echo "<br><br><br><br><br><br>";
    // print_r($_POST);die;

    $schoolboard = $_POST['school_board'] == 'Others' ? $_POST['other_board'] : $_POST['school_board'];
    
    $res=db_query("UPDATE orders SET lead_status = '".$insertData['lead_status']."',source = '".$insertData['source']."',sub_lead_source = '".$sub_lead_sourceI."', billing_reseller = '".$billing_resellerI."',credit_reseller = '".$credit_resellerI."',school_name = '".$insertData['school_name']."',is_group = '".$insertData['is_group']."',group_name = '".$insertData['group_name']."',spoc = '".$spocI."',address = '".$insertData['address']."',state = '".$stateI."',city = '".$cityI."',region = '".$insertData['region']."',country = '".$insertData['country']."',pincode = '".$insertData['pincode']."',contact = '".$insertData['contact']."',website = '".$insertData['website']."',school_email = '".$insertData['school_email']."',annual_fees = '".$insertData['annual_fees']."',eu_name = '".$insertData['eu_name']."',eu_designation = '".$insertData['eu_designation']."',eu_mobile = '".$insertData['eu_mobile']."',eu_email = '".$insertData['eu_email']."',eu_person_name1 = '".$insertData['eu_person_name1']."',eu_designation1 = '".$insertData['eu_designation1']."',eu_mobile1 = '".$insertData['eu_mobile1']."',eu_email1 = '".$insertData['eu_email1']."',eu_person_name2 = '".$insertData['eu_person_name2']."',eu_mobile2 = '".$insertData['eu_mobile2']."',eu_email2 = '".$insertData['eu_email2']."',adm_name = '".$insertData['adm_name']."',adm_designation = '".$insertData['adm_designation']."',adm_email = '".$insertData['adm_email']."',adm_mobile = '".$insertData['adm_mobile']."',adm_alt_mobile = '".$insertData['adm_alt_mobile']."',school_board = '".$schoolboard."',program_start_date = '".$insertData['program_start_date']."',academic_start_date = '".$insertData['academic_start_date']."',academic_end_date = '".$insertData['academic_end_date']."',grade_signed_up = '".$grade_signed_upI."',quantity = '".$quantityI."',purchase_no = '".$insertData['purchase_no']."',application_date = '".$insertData['application_date']."',purchase_deails = '".$insertData['purchase_deails']."',license_period = '".$insertData['license_period']."',is_app_erp = '".$insertData['is_app_erp']."',ip_address = '".$insertData['ip_address']."',labs_count = '".$insertData['labs_count']."',system_count = '".$insertData['system_count']."',os = '".$insertData['os']."',student_system_ratio = '".$insertData['student_system_ratio']."',lab_teacher_ratio = '".$insertData['lab_teacher_ratio']."',standalone_pc = '".$insertData['standalone_pc']."',projector = '".$insertData['projector']."',tv = '".$insertData['tv']."',smart_board = '".$insertData['smart_board']."',internet = '".$insertData['internet']."',networking = '".$insertData['networking']."',thin_client = '".$insertData['thin_client']."',n_computing = '".$insertData['n_computing']."'
    ,tag = '".$tagI."',visit_remarks = '".$insertData['visit_remarks']."',current_being_used_school = '".$insertData['current_being_used_school']."',kits_related_hardware_school = '".$insertData['kits_related_hardware_school']."',service_provider = '".$insertData['service_provider']."' WHERE id =".$_REQUEST['eid']);

    $ArrPersonNames = $_POST['imp_person_name']; 
    
    if(count($ArrPersonNames)){
        $delete_raw = db_query("delete from order_important_person where order_id=" . $_REQUEST['eid']);
        
        foreach($ArrPersonNames as $key => $name){
            $euDesignationArr = isset($_POST['imp_designation'][$key]) ? $_POST['imp_designation'][$key] : '';
            $euMobileArr = isset($_POST['imp_mobile'][$key]) ? $_POST['imp_mobile'][$key] : '';
            $euEmailArr = isset($_POST['imp_email'][$key]) ? $_POST['imp_email'][$key] : '';
            $eid = $_REQUEST["eid"];
    
    
            $res=db_query("INSERT INTO order_important_person (order_id,eu_person_name,eu_designation,eu_mobile,eu_email) 
                       VALUES ($eid,'".$name."','".$euDesignationArr."','".$euMobileArr."','".$euEmailArr."')");
        }

    }
    
    if ($res && $_SESSION['user_type'] != 'ADMIN' && $_SESSION['user_type'] != 'SUPERADMIN' && $_SESSION['role'] != 'ISS'){
        if($is_opportunity == 1){
                    // redir("manage_opportunity.php?update=success", true);
                }else if($agreement_type == 'Renewal'){
                    redir("renewal_leads_partner.php?update=success", true);
                }else{
                    redir("orders.php?update=success", true);
                }
    }else {
        if($is_opportunity == 1){
            redir("manage_opportunity.php?update=success", true);
        }else if($agreement_type == 'Renewal'){
            redir("renewal_leads_admin.php?update=success", true);
        }else{
            redir("manage_orders.php?update=success", true);
        }
    }
}?>
<style>
    .card-subtitle {
    margin: 2px 0;
    padding:6px 5px; 
    }
    .form-group {
    margin-bottom: 0;
}
label {
    display: inline-block;
    margin-bottom: 0.2rem;
}
.add_lead {
    padding: 7px 15px;
}
#form_activity .form-control{height: 24px;
    padding: 0 5px; font-size:11px;}
#form_activity .col-form-label {
    padding-top: 2px; font-size:11px; color:#1B274D;
    padding-bottom: 9px; text-align: right;}
#form_activity .card-subtitle {
    margin: 2px 0;
    padding: 2px 5px;
} 	
#form_activity .card-subtitle{font-size:13px; color:#0E1426; background:#f4f4f4;}

#form_activity .btn{padding: 2px 10px;}

.col-md-6 > .col-md-6 {
    flex: 0 0 100%;
    max-width: 100%;
    padding: 0;
}

@media (max-width: 767px) {
    #form_activity .col-form-label {
        text-align: left;
    }
}
</style>
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home > Edit Lead</small>
                                    <h4 class="font-size-14 m-0 mt-1">Edit Lead</h4>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <form method="post" action="#" class="form-horizontal" enctype="multipart/form-data" id="form_activity">
                                <input type="hidden" value="<?= $_SESSION['user_id'] ?>" name="user_id">
                                <div  class="add_lead">
                                    	<div class="row justify-content-between">	  

                                        <div class="col-md-6">
									 <h5 class="card-subtitle">School Details</h5>
                                     <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Lead Status<span class="text-danger">*</span></label>
                                               <div class="col-sm-7">   
                                           <!--  <select name="lead_status" class="form-control" required id="lead_status" placeholder="" data-validation-required-message="This field is required">
                                                 <option value="">---Select---</option>
                                                 <option <?= (($lead_status == 'Raw Data') ? 'selected' : '') ?> value="Raw Data">Raw Data</option>
                                                 <option <?= (($lead_status == 'Validation') ? 'selected' : '') ?> value="Validation">Validation</option>
                                                 <option <?= (($lead_status == 'Contacted') ? 'selected' : '') ?> value="Contacted">Contacted</option>
                                                 <option <?= (($lead_status == 'Qualified') ? 'selected' : '') ?> value="Qualified">Qualified</option>
                                                 <option <?= (($lead_status == 'Unqualified') ? 'selected' : '') ?> value="Unqualified">Unqualified</option>
                                                 <option <?= (($lead_status == 'Duplicate') ? 'selected' : '') ?> value="Duplicate">Duplicate</option>
                                         </select> -->
                                         <select name="lead_status" class="form-control" required id="lead_status" 
                                                    placeholder="" data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <?php
                                                    // Assuming $lead_status is already defined (e.g., from DB or $_POST)
                                                    $statuses = $modify_log->getAllLeadStatusNames(); // Function defined in another file

                                                    foreach ($statuses as $status) {
                                                        $selected = ($lead_status == $status) ? 'selected' : '';
                                                        echo "<option value=\"$status\" $selected>$status</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                       </div>

                                     <div class="form-group row">
                                         <label for="example-text-input" class="col-sm-5 col-form-label">Lead Source<span class="text-danger">*</span></label>
                                         <div class="col-sm-7">
                                         <select name="source" class="form-control" id="lead_source" placeholder="" required data-validation-required-message="This field is required">
                                             <option value="">---Select---</option>
                                                 <?php $res = db_query("select * from lead_source where status=1");
                                             while ($row = db_fetch_array($res)) { ?>
                                                 <option <?= (($source == $row['lead_source']) ? 'selected' : '') ?> value="<?= $row['lead_source'] ?>"><?= $row['lead_source'] ?></option>
                                             <?php } ?>
                                         </select>
                                         </div>
                                     </div>

                                     <div class="form-group row" id="sub_lead_source">
                                     <?php if ($sub_lead_source) {
                                         $query = db_query("SELECT * FROM sub_lead_source WHERE lead_source = '" . $source . "'  ORDER BY sub_lead_source ASC");
                                         $rowCount = mysqli_num_rows($query);
                                         if ($rowCount > 0) {
                                             echo '  
                                             <label class="col-sm-5 col-form-label">Sub Lead Source<span class="text-danger">*</span></label>
                                             <div class="col-sm-7"><select name="sub_lead_source" class="form-control" required data-validation-required-message="This field is required" id="subleadsource">';
                                             while ($row = db_fetch_array($query)) {
                                                 echo '<option '.(($sub_lead_source == $row['sub_lead_source']) ? 'selected' : '') .' value="' . $row['sub_lead_source'] . '">' . $row['sub_lead_source'] . '</option>';
                                             }
                                             echo '</select></div>';
                                         }
                                     } ?>
                                 </div>
                                 <?php
                                       $res=db_query("select * from partners where status='Active'");
                                       ?>
                                     <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Billing Reseller<span class="text-danger">*</span></label>
                                               <div class="col-sm-7">   
                                            <select name="billing_reseller" id="billing_reseller" class="form-control" required >
                                                     <option value="" >---Select---</option>
                                                     <?php
                                                         while($row=db_fetch_array($res))
                                                     { ?>
                                                         <option <?= (($billing_reseller == $row['id']) ? 'selected' : '') ?> value='<?=$row['id']?>'><?=$row['name']?></option>
                                                         <?php }?>

                                                </select>
                                        </div>
                                       </div>
                                       <?php
                                       $res=db_query("select * from partners where status='Active'");
                                       ?>

									 <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Credit Reseller<span class="text-danger">*</span></label>
                                               <div class="col-sm-7">   
                                            <select name="credit_reseller" id="credit_reseller" class="form-control" required >
                                                <option value="">---Select---</option>
                                                <?php
                                                         while($row=db_fetch_array($res))
                                                     { ?>
                                                         <option <?= (($credit_reseller == $row['id']) ? 'selected' : '') ?> value='<?=$row['id']?>'><?=$row['name']?></option>
                                                         <?php }?>
                                                         
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php
                                                        if($_SESSION['role'] == 'INTERNAL' || $_SESSION['user_type'] == 'ADMIN'){
                                                    $res=db_query("select * from clm_users where user_type='FACULTY'");
                                                    if($_SESSION['role'] == 'INTERNAL'){
                                                        $requiredSpoc = 'required';
                                                        }else{
                                                        $requiredSpoc = '';
                                                    }
                                                    ?>
                                                <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Spoc<?= $_SESSION['role'] == 'INTERNAL' ? '<span class="text-danger">*</span>' : '' ?></label>
                                                   <div class="col-sm-7">   
                                                  <select name="spoc" id="spoc" class="form-control" <?= $requiredSpoc ?> >
                                                         <option value="" >---Select---</option>
                                                         <?php
                                                             while($row=db_fetch_array($res))
                                                         { ?>
                                                             <option <?= (($spoc == $row['id']) ? 'selected' : '') ?> value='<?=$row['id']?>'><?=$row['name']?></option>
                                                             <?php }?>
         
                                                    </select>
                                            </div>
                                           </div>
                                           <?php } ?>
                                           <div class="form-group row">
                                               <label class="col-sm-5 col-form-label">Is Group</label>
                                                    <div class="col-sm-7">
                                                        <div class="custom-checkbox">
                                                            <input type="checkbox" name="is_group" id="is_group" value="yes" <?= $is_group == 'yes' ? 'checked' : '' ?> class="form-control" placeholder="">
                                                            <label for="is_group"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- <div class="form-group row" id="group_name_div" style="<?= $group_name != '' ? '' : 'display: none' ?>">
                                                    <label class="col-sm-5 col-form-label">Group Name<span class="text-danger">*</span></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" name="group_name" id="group_name" value="<?= $group_name ?>" pattern="[a-zA-Z0-9'-'\s]*" class="form-control" placeholder="">
                                                        
                                                    </div>
                                                </div> -->
                                                <?php
                                                  
                                                     // Check if there are any active EMI records for the given group.
                                                    // If EMI records exist for this group, the group cannot be changed.                                                   

                                                    $totalCountGrouping = $helperData->getActiveInvoiceCountByGroup($group_name);
                                                    $isDisabled = $totalCountGrouping > 0 ? true : false;
                                                    $isDisabled = false; # Info : as requirement we disabed force forcefully for the ectries in future we will disable this condition - 14-11-2025

                                                    $disabledAttr = $isDisabled ? 'disabled' : '';
                                                    $nameAttr = !$isDisabled ? 'name="group_name"' : '';

                                                ?>

                                             <div class="form-group row" id="group_name_div" 
                                             style="<?= $group_name != '' ? '' : 'display: none' ?>">
                                             
                                                <label class="col-sm-5 col-form-label">Group Name
                                                     <span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    
                                                <select <?= $disabledAttr ?>  <?= $nameAttr ?> id="group_name" class="form-control select2" style="width:100%;">
                                                        <?php
                                                        $groups = db_query("SELECT id, name FROM tbl_mst_group where status = 1 ORDER BY name ASC");
                                                        if (mysqli_num_rows($groups) > 0) {
                                                            echo '<option value="">Select Group</option>';
                                                            while ($row = db_fetch_array($groups)) {
                                                                $selected = ($group_name == $row['id']) ? 'selected' : '';
                                                                echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                                                            }
                                                            echo '<option value="__add_new__">➕ Add New Group...</option>';
                                                        } else {
                                                           echo '<option value="">Select Group</option>';
                                                           echo '<option value="__add_new__">➕ Add New Group...</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                
                                                    <?php if($isDisabled): ?>
                                                   <small class="text-danger font-weight-bold">(Note: Group changes are not allowed as an invoice has already been generated.)</small>
                                                   <?php endif; ?>
                                                    
                                                </div>
                                            </div>

                                                <?php
                                                    if($_SESSION['role'] == 'PARTNER' || $_SESSION['role'] == 'ISS'  || $_SESSION['user_type'] == 'ADMIN'){
                                                ?>                                                
                                                <div class="form-group row">
                                                       <label class="col-sm-5 col-form-label">School Name<span class="text-danger">*</span></label>
                                                          <div class="col-sm-7">   
                                                       <input name="school_name" type="text"  value="<?= $school_name ?>" class="form-control" placeholder="" required >
                                                   </div>
                                                  </div>
									   
									       <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">School Address<span class="text-danger">*</span></label>
                                               <div class="col-sm-7">   
                                            <input name="address" type="text" value="<?= $address ?>" class="form-control" placeholder="" required >
                                        </div>
                                       </div>

                                    <?php
                                       $res=db_query("select * from region where status=1");
                                       ?>
                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Region<span class="text-danger">*</span></label>
                                               <div class="col-sm-7">   
                                              <select name="region" id="region" class="form-control" required >
                                                     <option value="" >---Select---</option>
                                                     <?php
                                                         while($row=db_fetch_array($res))
                                                     { ?>
                                                         <option <?= (($region == $row['region']) ? 'selected' : '') ?> value='<?=$row['region']?>'><?=$row['region']?></option>
                                                         <?php }?>

                                                </select>
                                        </div>
                                       </div>

                                            <?php 
                                                $regionId = getSingleresult("select id from region where region='".$region."'");
                                                ?>
									    <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">State<span class="text-danger">*</span></label>
                                            <div class="col-sm-7"  id="stateDiv">
                                                <?php if($state && $regionId) { 
                                                    $res=db_query("select * from states WHERE region_id = ".$regionId);
                                                    ?>
                                                    <select name="state" id="state" class="form-control" required >
                                                        <option value="" >---Select---</option>
                                                     <?php
                                                         while($row=db_fetch_array($res))
                                                     {     
                                                        ?>
                                                         <option <?= (($state == $row['id']) ? 'selected' : '') ?> value='<?=$row['id']?>'><?=$row['name']?></option>
                                                         <?php }?>
                                                </select>
                                                <?php } else { ?>
                                                    <select name="state" id="state" class="form-control" required >
                                                           <option value="" >---Select---</option>
                                                      </select>                                                    
                                                    <?php } ?>
                                        </div>
                                       </div>
                                       <div class="form-group row"  >
                                           <label class="col-sm-5 col-form-label">City<span class="text-danger">*</span></label>
                                           <div class="col-sm-7" id="city">   
                                            <?php if($state){ 
                                                $res = db_query("SELECT * FROM cities where state_id=".$state);
                                                ?>
                                              <select name="city" id="city" class="form-control" required >
                                                     <option value="" >---Select---</option>
                                                     <?php
                                                         while($row=db_fetch_array($res))
                                                     { ?>
                                                         <option <?= (($city == $row['id']) ? 'selected' : '') ?> value='<?=$row['id']?>'><?=$row['city']?></option>
                                                         <?php }?>
                                                </select>
                                                <?php }else{?>
                                                    <select name="city" id="city" class="form-control" required >
                                                           <option value="" >---Select---</option>
                                                      </select>                                                
                                                    <?php }?>

                                        </div>
                                       </div>
									   
									       <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">Country<span class="text-danger">*</span></label>
                                               <div class="col-sm-7">   
                                            <input name="country" type="text" pattern="[a-zA-Z'-'\s]*"  value="India" class="form-control" placeholder="" readonly >
                                        </div>
                                       </div>
									   
									       <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">ZIP/ Postal Code<span class="text-danger">*</span></label>
                                               <div class="col-sm-7">   
                                            <input name="pincode" type="number" value="<?= $pincode ?>" class="form-control" placeholder="" required >
                                        </div>
                                       </div>
									   
									       <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Contact No.<span class="text-danger">*</span></label>
                                               <div class="col-sm-7">   
                                            <input name="contact" type="text" value="<?= $contact ?>" class="form-control" placeholder="" minlength="10" maxlength="10" required onkeypress="return isNumberKey(event,this.id);" onkeyup="return mobZeroValidation(this.value,this.id);">
                                        </div>
                                       </div>
									   
									       <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">Website<span class="text-danger">*</span></label>
                                               <div class="col-sm-7">   
                                            <input name="website" type="text" value="<?= $website ?>" class="form-control" placeholder="" required >
                                        </div>
                                       </div>
									   
									       <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">E-mail ID<span class="text-danger">*</span></label>
                                               <div class="col-sm-7">   
                                            <input name="school_email" type="email" value="<?= $school_email ?>" class="form-control" placeholder="" required >
                                        </div>
                                       </div>
									   
									       <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">Annual Fees<span class="text-danger">*</span></label>
                                               <div class="col-sm-7">   
                                            <input name="annual_fees" type="number" pattern="[a-zA-Z0-9'-'\s]*"  value="<?= $annual_fees ?>" class="form-control" placeholder="" required >
                                        </div>
                                       </div>
                                       <?php } ?>
                                       <?php if($_SESSION['role'] == 'ISS'  || $_SESSION['user_type'] == 'ADMIN'){
                                       $res=db_query("select * from tag where status=1");
                                       ?>
                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Tag</label>
                                               <div class="col-sm-7">   
                                              <select name="tag" id="tag" class="form-control" >
                                                     <option value="" >---Select---</option>
                                                     <?php
                                                         while($row=db_fetch_array($res))
                                                     { ?>
                                                         <option <?= (($row['id'] == $tag) ? 'selected' : '') ?> value='<?=$row['id']?>'><?=$row['name']?></option>
                                                         <?php }?>

                                                </select>
                                        </div>
                                       </div>
                                       <?php } ?>
                                       <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Remarks<span class="text-danger">*</span></label>
                                               <div class="col-sm-7">   
                                            <input name="visit_remarks" type="text" value="<?= $visit_remarks ?>" class="form-control" placeholder="" required >
                                        </div>
                                       </div>
									</div>
                                    <div class="col-md-6">
                                    <?php
                                        if($_SESSION['role'] == 'PARTNER' || $_SESSION['role'] == 'ISS' || $_SESSION['user_type'] == 'ADMIN'){
                                    ?>



                                  <h5 class="card-subtitle">Decision Maker Information</h5>
                                       <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Full Name<span class="text-danger">*</span></label>
                                               <div class="col-sm-7">   
                                            <input name="eu_name" type="text" pattern="[a-zA-Z'-'\s]*"  value="<?= $eu_name ?>" class="form-control" placeholder="" required >
                                        </div>
                                       </div>

                                       <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Designation<span class="text-danger">*</span></label>
                                               <div class="col-sm-7">   
                                            <input name="eu_designation" type="text"   value="<?= $eu_designation ?>"  class="form-control" placeholder="" required >
                                        </div>
                                       </div>

                                       <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Contact Number<span class="text-danger">*</span></label>
                                                  <div class="col-sm-7"> 
                                           <input type="text" minlength="10" maxlength="10" name="eu_mobile" id="example-text-phone-input" value="<?= $eu_mobile ?>" class="form-control mob-validate" placeholder="" required onkeypress="return isNumberKey(event,this.id);" onkeyup="return mobZeroValidation(this.value,this.id);">
                                              </div>
                                       </div>
                                        <div class="form-group row">
                                            <label for="example-search-input" class="col-sm-5 col-form-label ">Email ID<span class="text-danger">*</span></label>
													<div class="col-sm-7">
													 <input  value="<?= $eu_email ?>" name="eu_email" type="email" required  class="form-control form-control" placeholder="">
												</div>
                                        </div>




                                        <h6 class="card-subtitle">
                                            <div class="row align-items-center">
                                                <div class="col-md">
                                                    <!-- Important person Information -->
                                                    Champions
                                                </div>
                                                <div class="col-md-auto">
                                                    <button type="button" id="add-more" class="btn btn-primary btn-smal py-1"><span class="mdi mdi-plus" style="font-size: 16px"></span>Add</button>
                                                </div>
                                            </div>
                                        </h6>  
                                        <div class="mb-3">
                                        <!-- Main Form Container -->

                                        <?php if(!mysqli_num_rows($sql_order_important)){ ?>

                                        <div id="clone-form-container" class="form-clone">
                                                                    <div class="form-item">
                                                   <hr/>

                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Person Name</label>
                                                        <div class="col-sm-7">
                                                            <input 
                                                                name="imp_person_name[]" 
                                                                type="text" 
                                                                pattern="[a-zA-Z'-'\s]*" 
                                                                class="form-control" 
                                                                placeholder="">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Designation</label>
                                                        <div class="col-sm-7">
                                                            <select name="imp_designation[]" class="form-control">
                                                                <option value="">---Select---</option>
                                                                <option value="Management" >Management</option>
                                                                <option  value="Vice Chancellor" >Vice Chancellor</option>
                                                                <option  value="Registrar" >Registrar</option>
                                                                <option  value="Grade 4" >Grade 4</option>
                                                                <option  value="Director" >Director</option>
                                                                <option  value="DEAN" >DEAN</option>
                                                                <option  value="Principal" >Principal</option>
                                                                <option  value="ICT Head" >ICT Head</option>
                                                                <option  value="Professor" >Professor</option>
                                                                <option  value="HOD" >HOD</option>
                                                                <option  value="Faculty" >Faculty</option>
                                                                <option  value="Others" >Others</option>
                                                                <option  value="Head - Centre of Excellence">Head - Centre of Excellence</option>
                                                                <option  value="Vice principal" >Vice principal</option>
                                                                <option value="Founder" >Founder</option>
                                                                <!-- Add other options here -->
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Contact Number</label>
                                                        <div class="col-sm-7">
                                                            <input 
                                                                type="text" 
                                                                minlength="10" 
                                                                maxlength="10" 
                                                                name="imp_mobile[]" 
                                                                class="form-control mob-validate" 
                                                                placeholder="" 
                                                                onkeypress="return isNumberKey(event,this.id);" 
                                                                onkeyup="return mobZeroValidation(this.value,this.id);"
                                                                 >
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Email ID</label>
                                                        <div class="col-sm-7">
                                                            <input 
                                                                name="imp_email[]" 
                                                                type="email" 
                                                                class="form-control" 
                                                                placeholder=""
                                                                 >
                                                        </div>
                                                    </div>
                                                  
                                                    <div class="text-right">
                                                        <button type="button" class="btn btn-danger remove-clone py-1 my-1" style="display: none;"><span class="mdi mdi-delete" style="font-size: 16px"></span>Remove</button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                                    <?php $i = 0; while($euData=db_fetch_array($sql_order_important)) {
                                                                    
                                                                    ?>
                                                                    <div id="clone-form-container" class="form-clone">
                                                                    <div class="form-item">
                                                   <hr/>

                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Person Name</label>
                                                        <div class="col-sm-7">
                                                            <input 
                                                                name="imp_person_name[]" 
                                                                type="text" 
                                                                pattern="[a-zA-Z'-'\s]*" 
                                                                class="form-control" 
                                                                placeholder="" value="<?= $euData['eu_person_name']  ?>">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Designation</label>
                                                        <div class="col-sm-7">
                                                            <select name="imp_designation[]" class="form-control">
                                                            <option value="">---Select---</option>
<option <?= (($euData['eu_designation'] == 'Management') ? 'selected' : '') ?> value="Management" >Management</option>
<option <?= (($euData['eu_designation'] == 'Vice Chancellor') ? 'selected' : '') ?> value="Vice Chancellor" >Vice Chancellor</option>
<option <?= (($euData['eu_designation'] == 'Registrar') ? 'selected' : '') ?> value="Registrar" >Registrar</option>
<option <?= (($euData['eu_designation'] == 'Grade 4') ? 'selected' : '') ?> value="Grade 4" >Grade 4</option>
<option <?= (($euData['eu_designation'] == 'Director') ? 'selected' : '') ?> value="Director" >Director</option>
<option <?= (($euData['eu_designation'] == 'DEAN') ? 'selected' : '') ?> value="DEAN" >DEAN</option>
<option <?= (($euData['eu_designation'] == 'Principal') ? 'selected' : '') ?> value="Principal" >Principal</option>
<option <?= (($euData['eu_designation'] == 'ICT Head') ? 'selected' : '') ?> value="ICT Head" >ICT Head</option>
<option <?= (($euData['eu_designation'] == 'Professor') ? 'selected' : '') ?> value="Professor" >Professor</option>
<option <?= (($euData['eu_designation'] == 'HOD') ? 'selected' : '') ?> value="HOD" >HOD</option>
<option <?= (($euData['eu_designation'] == 'Faculty') ? 'selected' : '') ?> value="Faculty" >Faculty</option>
<option <?= (($euData['eu_designation'] == 'Others') ? 'selected' : '') ?> value="Others" >Others</option>
<option <?= (($euData['eu_designation'] == 'Head - Centre of Excellence') ? 'selected' : '') ?> value="Head - Centre of Excellence">Head - Centre of Excellence</option>
<option <?= (($euData['eu_designation'] == 'Vice principal') ? 'selected' : '') ?> value="Vice principal" >Vice principal</option>
<option <?= (($euData['eu_designation'] == 'Founder') ? 'selected' : '') ?> value="Founder" >Founder</option>
                                                                <!-- Add other options here -->
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Contact Number</label>
                                                        <div class="col-sm-7">
                                                            <input 
                                                                type="text" 
                                                                minlength="10" 
                                                                maxlength="10" 
                                                                name="imp_mobile[]" 
                                                                class="form-control mob-validate" 
                                                                placeholder="" 
                                                                onkeypress="return isNumberKey(event,this.id);" 
                                                                onkeyup="return mobZeroValidation(this.value,this.id);"
                                                                value="<?= $euData['eu_mobile']  ?>" >
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Email ID</label>
                                                        <div class="col-sm-7">
                                                            <input 
                                                                name="imp_email[]" 
                                                                type="email" 
                                                                class="form-control" 
                                                                placeholder=""
                                                                value="<?= $euData['eu_email']  ?>" >
                                                        </div>
                                                    </div>
                                                    <?php if($i != 0){ ?>
                                                    <div class="text-right">
                                                        <button type="button" class="btn btn-danger remove-clone py-1"><span class="mdi mdi-delete" style="font-size: 16px"></span>Remove</button>
                                                    </div>
                                                    <?php } ?>
                                                    <div class="text-right">
                                                        <button type="button" class="btn btn-danger remove-clone py-1" style="display: none;"><span class="mdi mdi-delete" style="font-size: 16px"></span>Remove</button>
                                                    </div>
                                                </div>
                                            </div>


                                                    <?php  $i++; } ?>

                                                    <!-- Remove button -->
                                                    
                                        </div>    
                                        
                                        
                                        <!-- Cloned forms container -->
                                        <div id="clone-wrapper"></div>

                                        
                                        <!-- <h6 class="card-subtitle">Important person Information</h6>   -->
                                        <!-- <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Person 1st - Name</label>
                                            <div class="col-sm-7">   
                                            <input name="eu_person_name1" type="text" pattern="[a-zA-Z'-'\s]*"  value="<?= $eu_person_name1 ?>" class="form-control" placeholder=""  >
                                        </div>
                                       </div>     -->
                                       <!-- <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Designation</label>
                                            <div class="col-sm-7">
                                                <select name="eu_designation1" id="eu_designation1" class="form-control"  >
                                                       <option value="" >---Select---</option>
                                                       <option <?= (($eu_designation1 == 'Management') ? 'selected' : '') ?> value="Management" >Management</option>
                                                       <option <?= (($eu_designation1 == 'Vice Chancellor') ? 'selected' : '') ?> value="Vice Chancellor" >Vice Chancellor</option>
                                                       <option <?= (($eu_designation1 == 'Registrar') ? 'selected' : '') ?> value="Registrar" >Registrar</option>
                                                       <option <?= (($eu_designation1 == 'Grade 4') ? 'selected' : '') ?> value="Grade 4" >Grade 4</option>
                                                       <option <?= (($eu_designation1 == 'Director') ? 'selected' : '') ?> value="Director" >Director</option>
                                                       <option <?= (($eu_designation1 == 'DEAN') ? 'selected' : '') ?> value="DEAN" >DEAN</option>
                                                       <option <?= (($eu_designation1 == 'Principal') ? 'selected' : '') ?> value="Principal" >Principal</option>
                                                       <option <?= (($eu_designation1 == 'Professor') ? 'selected' : '') ?> value="Professor" >Professor</option>
                                                       <option <?= (($eu_designation1 == 'HOD') ? 'selected' : '') ?> value="HOD" >HOD</option>
                                                       <option <?= (($eu_designation1 == 'Faculty') ? 'selected' : '') ?> value="Faculty" >Faculty</option>
                                                       <option <?= (($eu_designation1 == 'Others') ? 'selected' : '') ?> value="Others" >Others</option>
                                                       <option <?= (($eu_designation1 == 'Head - Centre of Excellence') ? 'selected' : '') ?> value="Head - Centre of Excellence">Head - Centre of Excellence</option>
                                                       <option <?= (($eu_designation1 == 'Vice principal') ? 'selected' : '') ?> value="Vice principal" >Vice principal</option>
                                                       <option <?= (($eu_designation1 == 'Founder') ? 'selected' : '') ?> value="Founder" >Founder</option>
                                                       
                                                    </select>
                                                </div>
                                            </div>                           -->
                                            <!-- <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Contact Number</label>
                                                      <div class="col-sm-7"> 
                                               <input type="text" minlength="10" maxlength="10" name="eu_mobile1" id="example-text-phone-input" value="<?= $eu_mobile1 ?>" class="form-control mob-validate" placeholder=""  onkeypress="return isNumberKey(event,this.id);" onkeyup="return mobZeroValidation(this.value,this.id);">
                                                  </div>
                                           </div> -->
                                            <!-- <div class="form-group row">
                                                <label for="example-search-input" class="col-sm-5 col-form-label ">Email ID</label>
                                                        <div class="col-sm-7">
                                                            <input  value="<?= $eu_email1 ?>" name="eu_email1" type="email"   class="form-control form-control" placeholder="">
                                                    </div>
                                            </div> -->
                                        <!-- <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Person 2nd - Name</label>
                                            <div class="col-sm-7">   
                                            <input name="eu_person_name2" type="text" pattern="[a-zA-Z'-'\s]*"  value="<?= $eu_person_name2 ?>" class="form-control" placeholder="">
                                        </div>
                                       </div>     -->
                                            <!-- <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Contact Number</label>
                                                      <div class="col-sm-7"> 
                                               <input type="text" minlength="10" maxlength="10" name="eu_mobile2" id="example-text-phone-input" value="<?= $eu_mobile2 ?>" class="form-control mob-validate" placeholder="" onkeypress="return isNumberKey(event,this.id);" onkeyup="return mobZeroValidation(this.value,this.id);">
                                                  </div>
                                           </div> -->
                                           <!-- <div class="form-group row">
                                                <label for="example-search-input" class="col-sm-5 col-form-label ">Email ID</label>
                                                        <div class="col-sm-7">
                                                         <input  value="<?= $eu_email2 ?>" name="eu_email2" type="email" class="form-control form-control" placeholder="">
                                                    </div>
                                            </div> -->
                                      </div>




                                            <?php } ?>




                                    <?php if($_SESSION['role'] == 'INTERNAL' || $_SESSION['user_type'] == 'ADMIN'){ ?>
                                        <div class="col-md-6">
									 <h5 class="card-subtitle">ICT360 Admin Information</h5>
									 
									     <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">Full Name of Admin - ICT360</label>
                                                    <div class="col-sm-7">
                                            <input type="text" name="adm_name" pattern="[a-zA-Z'-'\s]*" value="<?= $adm_name ?>" class="form-control" placeholder=""  /></div>
                                        </div>
										
										 <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">Designation</label>
                                                    <div class="col-sm-7">
                                            <input type="text" name="adm_designation" pattern="[a-zA-Z0-9'-'\s]*" value="<?= $adm_designation ?>" class="form-control" placeholder=""  /></div>
                                        </div>
										
										 <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">E-mail ID</label>
                                                    <div class="col-sm-7">
                                            <input type="email" name="adm_email" value="<?= $adm_email ?>" class="form-control" placeholder=""  /></div>
                                        </div>
										 <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">Contact Number</label>
                                                    <div class="col-sm-7">
                                            <input type="text" name="adm_mobile" value="<?= $adm_mobile ?>" class="form-control" placeholder=""  minlength="10" maxlength="10" onkeypress="return isNumberKey(event,this.id);" onkeyup="return mobZeroValidation(this.value,this.id);"/></div>
                                        </div>
											 <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Alternative Contact Number</label>
                                                    <div class="col-sm-7">
                                            <input type="text" name="adm_alt_mobile" value="<?= $adm_alt_mobile ?>" class="form-control" placeholder=""  minlength="10" maxlength="10" onkeypress="return isNumberKey(event,this.id);" onkeyup="return mobZeroValidation(this.value,this.id);"/></div>
                                        </div>
									 
									 </div>
									 <?php } ?>
									 	
									 <div class="col-md-6">
									 <h5 class="card-subtitle">Program Information</h5>
                                     <div class="form-group row">
                                         
                                         <label class="col-sm-5 col-form-label">School Board</label>
                                         <div class="col-sm-7">
                                         <select name="school_board" id="school_board" class="form-control" placeholder="">
                                                <option value="">---Select---</option>
                                                <option <?= $school_board == 'CBSE' ? 'selected' : '' ?> value="CBSE">CBSE</option>
                                                <option <?= $school_board == 'ICSE' ? 'selected' : '' ?> value="ICSE">ICSE</option>
                                                <option <?= $school_board == 'IB' ? 'selected' : '' ?> value="IB">IB</option>
                                                <option <?= $school_board == 'IGCSE' ? 'selected' : '' ?> value="IGCSE">IGCSE</option>
                                                <option <?= $school_board == 'STATE' ? 'selected' : '' ?> value="STATE">STATE</option>
                                                <option <?= $isOtherBoard == 1 ? 'selected' : '' ?> value="Others">Others</option>
                                                </select>
                                            </div>
                                            </div>
                                            <div class="form-group row" id="other_board_div" <?= $isOtherBoard ==1 ? '' : 'style="display: none;"' ?>>
                                                <label class="col-sm-5 col-form-label">Specify Other Board</label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="other_board" id="other_board" class="form-control" placeholder="Enter board name" value="<?= $school_board ?>">
                                                </div>
                                            </div>
                                            <?php if($_SESSION['role'] == 'Internal') {?>
										 <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">Start Date of ICT360 Program in school<span class="text-danger">*</span></label>
                                                    <div class="col-sm-7">
                                            <input type="date" name="program_start_date" value="<?= $program_start_date ?>" class="form-control" placeholder="" required  /></div>
                                        </div>
										 <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">School Academic Year Start Date<span class="text-danger">*</span></label>
                                                    <div class="col-sm-7">
                                            <input type="date" name="academic_start_date" value="<?= $academic_start_date ?>" class="form-control" placeholder="" required  /></div>
                                        </div>
										
										 <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">School Academic Year End Date<span class="text-danger">*</span></label>
                                                    <div class="col-sm-7">
                                            <input type="date" name="academic_end_date" value="<?= $academic_end_date ?>" class="form-control" placeholder="" required  /></div>
                                        </div>
										<?php 
                                            $grade_signed_upArr = explode(',',$grade_signed_up);
                                            // print_r($grade_signed_upArr);die;
                                        ?>
										 <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Grades Signed Up For ICT 360<span class="text-danger">*</span></label>
                                                    <div class="col-sm-7">
                                                    <select name="grade_signed_up[]" id="grade_signed_up" class="form-control multiselect_grade" multiple required >
                                                         <option <?= in_array('1',$grade_signed_upArr) ? 'selected' : '' ?> value='1'>Grade 1</option>
                                                         <option <?= in_array('2',$grade_signed_upArr) ? 'selected' : '' ?> value='2'>Grade 2</option>
                                                         <option <?= in_array('3',$grade_signed_upArr) ? 'selected' : '' ?> value='3'>Grade 3</option>
                                                         <option <?= in_array('4',$grade_signed_upArr) ? 'selected' : '' ?> value='4'>Grade 4</option>
                                                         <option <?= in_array('5',$grade_signed_upArr) ? 'selected' : '' ?> value='5'>Grade 5</option>
                                                         <option <?= in_array('6',$grade_signed_upArr) ? 'selected' : '' ?> value='6'>Grade 6</option>
                                                         <option <?= in_array('7',$grade_signed_upArr) ? 'selected' : '' ?> value='7'>Grade 7</option>
                                                         <option <?= in_array('8',$grade_signed_upArr) ? 'selected' : '' ?> value='8'>Grade 8</option>
                                                         <option <?= in_array('9',$grade_signed_upArr) ? 'selected' : '' ?> value='9'>Grade 9</option>
                                                         <option <?= in_array('10',$grade_signed_upArr) ? 'selected' : '' ?> value='10'>Grade 10</option>
                                                </select>
                                                </div>
                                        </div>
                                        <?php } ?>

										 <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Student count</label>
                                        <div class="col-sm-7">
                                            <input type="number" min="0" name="student_count" value="<?= $quantity ?>" class="form-control" placeholder=""  />
                                        </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Current ICT Being Used by the School</label>
                                                    <div class="col-sm-7">
                                            <input type="text" name="current_being_used_school"  value="<?= isset($current_being_used_school) && $current_being_used_school ? $current_being_used_school : '' ?>"  class="form-control" placeholder="" /></div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Kits Related Hardware Being Used By
                                            the School</label>
                                                    <div class="col-sm-7">
                                            <input type="text" name="kits_related_hardware_school"  value="<?= isset($kits_related_hardware_school) && $kits_related_hardware_school ? $kits_related_hardware_school : '' ?>"  class="form-control" placeholder="" /></div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Name of the Service Provider</label>
                                                    <div class="col-sm-7">
                                            <input type="text" name="service_provider"  value="<?= isset($service_provider) && $service_provider ? $service_provider : '' ?>"  class="form-control" placeholder="" /></div>
                                        </div>
										
										 <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">Purchase Order No.</label>
                                                    <div class="col-sm-7">
                                            <input type="text" name="purchase_no" pattern="[a-zA-Z0-9'-'\s]*" value="<?= $purchase_no ?>" class="form-control" placeholder=""  /></div>
                                        </div>
										
										 <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Date of Application</label>
                                                    <div class="col-sm-7">
                                            <input type="date" name="application_date" value="<?= $application_date ?>" class="form-control" placeholder=""  /></div>
                                        </div>
										
										 <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Purchase details</label>
                                                    <div class="col-sm-7">
                                            <input type="text" name="purchase_deails" value="<?= $purchase_deails ?>" class="form-control" placeholder=""  pattern="[a-zA-Z0-9'-'\s]*" /></div>
                                        </div>
										
										 <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">Purchase/ Renewal for Number of years</label>
                                                    <div class="col-sm-7">
                                            <input type="number" name="license_period" value="<?= $license_period ?>" class="form-control" placeholder=""  /></div>
                                        </div>
									 
									 </div>
									
									  <div class="col-md-6">
									 <h5 class="card-subtitle">Lab Details</h5>
									  <div class="form-group row">
                                                
                                                    <label class="col-sm-5 col-form-label">Does your School have any School App/ ERP System?</label>
                                                  <div class="col-sm-7">
                                                  <select name="is_app_erp" id="is_app_erp" class="form-control" >
                                                     <option value="" >---Select---</option>
                                                     <option <?= $is_app_erp == 'Yes' ? 'selected' : '' ?> value="Yes">Yes</option>
                                                     <option <?= $is_app_erp == 'No' ? 'selected' : '' ?> value="No">No</option>
                                                </select>
                                                  </div>
                                            </div>
											
											 <div class="form-group row">
                                                
                                                    <label class="col-sm-5 col-form-label">School IP Address</label>
                                                  <div class="col-sm-7">
                                                        <input type="number" min="0" name="ip_address" value="<?= $ip_address ?>" class="form-control" pattern="[0-9]*" placeholder=""/>
                                                  </div>
                                            </div>
											
											
											 <div class="form-group row">
                                                
                                                    <label class="col-sm-5 col-form-label">No. of Labs</label>
                                                  <div class="col-sm-7">
                                                        <input type="number" min="0" name="labs_count" value="<?= $labs_count ?>" class="form-control" pattern="[0-9]*" placeholder=""/>
                                                  </div>
                                            </div>
											
											
											 <div class="form-group row">
                                                
                                                    <label class="col-sm-5 col-form-label">Number of System (laptop/desktop) for ICT program.</label>
                                                  <div class="col-sm-7">
                                                        <input type="number" min="0" name="system_count" value="<?= $system_count ?>" class="form-control" placeholder=""/>
                                                  </div>
                                            </div>
											
											 <div class="form-group row">
                                                
                                                    <label class="col-sm-5 col-form-label">Operating systems used in ICT Labs</label>
                                                  <div class="col-sm-7">
                                                        <input type="text" name="os" value="<?= $os ?>" class="form-control" placeholder=""/>
                                                  </div>
                                            </div>
												 <div class="form-group row">
                                                
                                                    <label class="col-sm-5 col-form-label">Student system ratio</label>
                                                        <div class="col-sm-7">
                                                                <input type="text" min="0" name="student_system_ratio" value="<?= $student_system_ratio ?>" class="form-control"  pattern="^\d+:\d+$" placeholder="" title="Enter ratio in format like 30:1"/>
                                                        </div>
                                                    </div>
												 <div class="form-group row">
                                                
                                                    <label class="col-sm-5 col-form-label">Lab teacher ratio</label>
                                                        <div class="col-sm-7">
                                                                <input type="text" min="0" name="lab_teacher_ratio" value="<?= $lab_teacher_ratio ?>" class="form-control" pattern="^\d+:\d+$" placeholder=""   title="Enter ratio in format like 30:1"/>
                                                        </div>
                                                    </div>
									 </div>
									 
									  <div class="col-md-6">
                                      <?php 
                                            // print_r($_SESSION['role']);die;
                                          if($_SESSION['role'] == 'PARTNER' || $_SESSION['role'] == 'ISS' || $_SESSION['user_type'] == 'ADMIN'){ ?>
                                            <h5 class="card-subtitle">Lab INFRASTRUCTURE</h5>
									 
									    <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">Standalone PC</label>
                                               <div class="col-sm-7">   
                                              <select name="standalone_pc" id="standalone_pc" class="form-control" >
                                              <option value="" >---Select---</option>
                                                     <option <?= $standalone_pc == 'Yes' ? 'selected' : ''?> value="Yes">Yes</option>
                                                     <option <?= $standalone_pc == 'No' ? 'selected' : ''?> value="No">No</option>
                                                     
                                                </select>
                                        </div>
                                       </div>
									   
									      <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">Projector</label>
                                               <div class="col-sm-7">   
                                              <select name="projector" id="projector" class="form-control" >
                                              <option value="" >---Select---</option>
                                                     <option <?= $projector == 'Yes' ? 'selected' : ''?> value="Yes">Yes</option>
                                                     <option <?= $projector == 'No' ? 'selected' : ''?> value="No">No</option>
                                                     
                                                </select>
                                        </div>
                                       </div>
									   
									      <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">TV</label>
                                               <div class="col-sm-7">   
                                              <select name="tv" id="tv" class="form-control" >
                                              <option value="" >---Select---</option>
                                                     <option <?= $tv == 'Yes' ? 'selected' : ''?> value="Yes">Yes</option>
                                                     <option <?= $tv == 'No' ? 'selected' : ''?> value="No">No</option>
                                                     
                                                </select>
                                        </div>
                                       </div>
									   
									      <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">Smart Board</label>
                                               <div class="col-sm-7">   
                                              <select name="smart_board" id="smart_board" class="form-control" >
                                              <option value="" >---Select---</option>
                                                     <option <?= $smart_board == 'Yes' ? 'selected' : ''?> value="Yes">Yes</option>
                                                     <option <?= $smart_board == 'No' ? 'selected' : ''?> value="No">No</option>
                                                     
                                                </select>
                                        </div>
                                       </div>
									   
									      <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Internet</label>
                                               <div class="col-sm-7">   
                                              <select name="internet" id="internet" class="form-control" >
                                              <option value="" >---Select---</option>
                                                     <option <?= $internet == 'Yes' ? 'selected' : ''?> value="Yes">Yes</option>
                                                     <option <?= $internet == 'No' ? 'selected' : ''?> value="No">No</option>
                                                     
                                                </select>
                                        </div>
                                       </div>
									   
									      <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">Networking</label>
                                               <div class="col-sm-7">   
                                              <select name="networking" id="networking" class="form-control" >
                                              <option value="" >---Select---</option>
                                                     <option <?= $networking == 'Yes' ? 'selected' : ''?> value="Yes">Yes</option>
                                                     <option <?= $networking == 'No' ? 'selected' : ''?> value="No">No</option>
                                                     
                                                </select>
                                        </div>
                                       </div>
									   
									        <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">Thin client</label>
                                               <div class="col-sm-7">   
                                              <select name="thin_client" id="thin_client" class="form-control" >
                                              <option value="" >---Select---</option>
                                                     <option <?= $thin_client == 'Yes' ? 'selected' : ''?> value="Yes">Yes</option>
                                                     <option <?= $thin_client == 'No' ? 'selected' : ''?> value="No">No</option>
                                                     
                                                </select>
                                        </div>
                                       </div>
									   
									        <div class="form-group row">

                                            <label class="col-sm-5 col-form-label">N Computing</label>
                                               <div class="col-sm-7">   
                                              <select name="n_computing" id="n_computing" class="form-control" >
                                              <option value="" >---Select---</option>
                                                     <option <?= $n_computing == 'Yes' ? 'selected' : ''?> value="Yes">Yes</option>
                                                     <option <?= $n_computing == 'No' ? 'selected' : ''?> value="No">No</option>
                                                     
                                                </select>
                                        </div>
                                       </div>
                                       <?php } ?>
                                  </div>
                                </div>
                                <div class="col-md-12 text-center my-2">
                                    <button type="submit" class="btn btn-primary  mt-2">Submit</button>
                                  <!-- <button type="submit" data-toggle="modal" data-target="#myModal" id="form_data" class="btn btn-primary  mt-2">Submit</button> -->
                                  <button type="button" onclick="javascript:history.go(-1)" class="btn btn-danger mt-2">Cancel</button>
                                </div>

                   </div>
                </div> <!-- end col -->


            </div> <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Add Activity Call
      </h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <!-- <form action="#" method="post" class="form p-t-20" > -->
        <!-- <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Call Type<span class="text-danger">*</span></label>
              <?php $res=db_query("select * from call_type order by name asc"); ?>
        <select name="call_type" id="call_type" class="form-control" required data-validation-required-message="This field is required">
        <option value="" >---Select---</option>
        <?php while($row=db_fetch_array($res))
        { ?>
    <option value='<?=$row['name']?>'><?=$row['name']?></option>
        <?php } ?>
        </select>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Visit Remarks<span class="text-danger">*</span></label>
              
              <textarea id="remarks" pattern=".{50,}" value="<?= $goal_data['daily'] ?>" name="remarks" class="form-control" placeholder=""  required data-validation-required-message="This field is required"></textarea>
              
            </div>
          </div> -->

        </div>

    <div class="modal-footer mb-4">
    <input type="submit" id="save_btn" name="save" value="Save" class="btn btn-primary" />
     
      <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

    </div>
    </form>
 
    <?php include('includes/footer.php') ?>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="js/validation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/bootstrap-slider.min.js"></script>

        <script>
        $(document).ready(function(){
     
     
    $('#state').on('change',function(){
        // alert("hi");
        var stateID = $(this).val();
        if(stateID){
            $.ajax({
                type:'POST',
                url:'ajaxindustry.php',
                data:'state_idd='+stateID,
                success:function(html){
                    //alert(html);
                    $('#city').html(html);
                }
            }); 
        } 
    }); 
    });

    $('#add-more').click(function () {
        // Clone the container
        var newClone = $('#clone-form-container').clone();

        // Reset all input fields in the clone
        newClone.find('input').val('');
        newClone.find('select').val('');

        // Show the "Remove" button in the cloned form
        newClone.find('.remove-clone').show();

        // Append the clone to the wrapper
        $('#clone-wrapper').append(newClone);
    });

    $(document).on('click', '.remove-clone', function () {
        // Remove the closest .form-clone container
        $(this).closest('.form-clone').remove();
    });
    
    </script>

    <script>
        $(document).ready(function() {
            var i = 1;
            var add_btn = $('.add_btn').val();
            $('#add').click(function() {
                i++;
                $('#dynamic_field').append('<div id="row' + add_btn + '"><div class="form-group row d-flex align-items-end"><div class="col-lg-2 mb-2"><label class="control-label">Full Name</label><input name="e_name[]" value="" type="text" value="" class="form-control" placeholder=""></div><div class="col-lg-2 mb-2"><label class="control-label">Email</label><input value="" name="e_email[]" type="email" class="form-control" placeholder=""></div><div class="col-lg-2 mb-2"><label class="control-label">Mobile</label><input type="text" minlength="10" maxlength="10" name="e_mobile[]" value="" class="form-control mob-validate" id="mobile-append'+i+'" onkeypress="return isNumberKey(event,this.id);" onkeyup="return mobZeroValidation(this.value,this.id);"></div><div class="col-lg-2 mb-2"><label class="control-label">Designation</label><input type="text" value="" name="e_designation[]" class="form-control" /></div><div class="col-sm-1 mb-2"><span data-repeater-delete="" name="remove" id="' + add_btn + '" class="btn btn-danger btn-sm btn_remove"><span class="fa fa-times mr-1"></span>Delete</span></div></div></div>');
                add_btn++;
            });
            $(document).on('click', '.btn_remove', function() {
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });
        });
        $(document).ready(function() {
            var i = 1;
            var add_btnQ = $('.add_btnQ').val();
            $('#addGradesQ').click(function() {
                i++;
                $('#dynamic_quanity').append('<div id="row' + add_btnQ + '"><div class="form-group row d-flex align-items-end mb-2"><label class="col-sm-2 col-form-label">Grade<span class="text-danger">*</span></label><div class="col-sm-3"><input name="grade[]" value="" type="number" required value="" class="form-control" placeholder=""></div><label class="col-sm-2 col-form-label">Students<span class="text-danger">*</span></label><div class="col-sm-3"><input value="" name="students[]" type="number" required class="form-control student-input" placeholder=""></div><div class="col-sm-2 "><span data-repeater-delete="" name="remove" id="' + add_btnQ + '" class="btn btn-danger btn-sm btn_removeQ"><span class="fa fa-times mr-1"></span>Delete</span></div></div></div>')
                add_btnQ++;
            });
            $(document).on('click', '.btn_removeQ', function() {
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });
        });

        function updateStudentSum() {
            var sum = 0;

            $('.student-input').each(function() {
                var value = parseInt($(this).val()) || 0;
                sum += value;
            });
            document.getElementById("quantity").setAttribute('value',sum);
            // alert(sum);
        }

        updateStudentSum();

        $('#dynamic_quanity').on('input', '.student-input', updateStudentSum);

        $('#dynamic_quanity').on('click', '.btn_removeQ', function() {
            var deletedValue = parseInt($(this).closest('.form-group').find('.student-input').val()) || 0;
            // var currentSum = parseInt($('.total-students').text()) || 0;
            currentSum = document.getElementById("quantity").value;
            var newSum = currentSum - deletedValue;
            document.getElementById("quantity").setAttribute('value',newSum);
        });

        function changeValue(e)
        {
            document.getElementById("quantity").setAttribute('value',e);
        }
    </script>


      <script>
         
     $(document).ready(function(){
            $('#check').click(function(){
             //alert($(this).is(':checked'));
                $(this).is(':checked') ? $('#pwd').attr('type', 'text') : $('#pwd').attr('type', 'password');
            });
        });
        $(document).ready(function() {
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
        });
        // For select 2

         
    });
    $("#ex6").slider();
    $("#ex6").on("slide", function(slideEvt) {
        $("#ex6SliderVal").text(slideEvt.value);
    });

    </script>
    <script>
$(document).ready(function(){
    
     var wfheight = $(window).height();
                  
                  $('.add_lead_form').height(wfheight-310);
                  


      $('.add_lead_form').slimScroll({
        color: '#00f',
        size: '10px',
       height: 'auto',
       
      
    });
    
});
$(function() {
    $('.datepicker').daterangepicker({
        
      "singleDatePicker": true,
    "showDropdowns": true,
     locale: {
      format: 'YYYY-MM-DD'
    },
        
    });
});


        function validateInput(inputField) {
            var inputValue = inputField.value;
            var containsNumbers = /\d/.test(inputValue); // Regular expression to check for numbers
            

        }

        $(document).ready(function() {
            var wfheight = $(window).height();
            $('.add_lead').height(wfheight - 200);
        });

        $(function() {

            $('#datepicker-close-date').datepicker({
                format: 'yyyy-mm-dd',
                startDate: '-3d',
                autoclose: !0
            });
        }); 


      $(function() {

            $('#datetime').datepicker({
                format: 'yyyy-mm-dd',
                startDate: '-7d',
                // endDate: '0d',
                autoclose: !0
            });
			
			    $('.date_time').datepicker({
                format: 'yyyy-mm-dd',
                startDate: '-7d',
                // endDate: '0d',
                autoclose: !0
            });
        }); 

    
    $("#is_group").change(function() {
        if(this.checked) {
            $("#group_name").prop('required',true);
            $("#group_name_div").css('display','flex'); 
        }
        else
        {
            $("#group_name").prop('required',false);
            $("#group_name_div").css('display','none');
        
        }
    });

</script>

<script>
        $('#example-text-input1').on("keyup",function (evt) {
           if (this.value.length == 1 && this.value !=0)
           {
              swal("please enter 0 first");
              $('#example-text-input1').val('');

           }
        });

        $('#example-text-input1').on("blur",function (evt) {
           if (this.value.length < 11 && this.value.length > 1)
           {
              swal("Landline no should be minimum 11 digits");
              $('#example-text-input1').trigger('focus');
           }
        });

        $('#example-text-input2').on("keyup",function (evt) {
           if (this.value.length == 1 && this.value !=0)
           {
              swal("please enter 0 first");
              $('#example-text-input2').val('');

           }
        });

        $('#example-text-input2').on("blur",function (evt) {
           if (this.value.length < 11 && this.value.length > 1)
           {
              swal("Landline no should be minimum 11 digits");
              $('#example-text-input2').trigger('focus');
           }
        });

        $('.mob-validate').on("blur",function (evt) {
           if (this.value.length < 10 && this.value.length > 1)
           {
              swal("Please enter valid mobile no");
              $('.mob-validate').trigger('focus');
           }
        });
        
       function mobZeroValidation(value,id)
       {
        //   alert(id);
           if(value[0] == 0){
                  swal("0 not allowed as first digit in mobile no");
                  $('#'+id).val('');
                  $('#'+id).trigger('focus');
               }
       }
       

        function isNumberKey(evt,id)
         {
            try{
                var charCode = (evt.which) ? evt.which : event.keyCode;
          
                if(charCode==46){
                    var txt=document.getElementById(id).value;
                    if(!(txt.indexOf(".") > -1)){
            
                        return false;
                    }
                }
                if (charCode > 31 && (charCode < 48 || charCode > 57) )
                    return false;

                return true;
            }catch(w){
                //alert(w);
            }
         }

         $('#user_attachment').on('change', function() {
            if(this.files[0].size / 1024 / 1024 > 4)
            {
                swal('Please Upload File Less Than 4MB!!')
                $('#user_attachment').val('');
                return false;
            }
            });

            $('#emailer_attachment').on('change', function() {
            if(this.files[0].size / 1024 / 1024 > 4)
            {
                swal('Please Upload File Less Than 4MB!!')
                $('#emailer_attachment').val('');
                return false;
            }
            });

            $(document).ready(function() {
            $('#lead_source').on('change', function() {
                //alert("hi");
                var leadsource = $(this).val();
                if (leadsource) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxsubLeadSource.php',
                        data: 'lead_source=' + leadsource,
                        success: function(html) {
                            //alert(html);
                            $('#sub_lead_source').html(html);
                        }
                    });
                }
            });
        });

        $(document).ready(function() {
                $('.multiselect_grade').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Grade',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
            });

            $(document).ready(function(){
    $('#region').on('change',function(){
        //alert("hi");
        var regionName = $(this).val();
        if(regionName){
            $.ajax({
                type:'POST',
                url:'ajaxindustry.php',
                data:'regionName='+regionName,
                success:function(html){
                    //alert(html);
                    $('#stateDiv').html(html);
                }
            }); 
        } 
    }); 
    });

    function stateChange(e){
        var stateID = e;
        if(stateID){
            $.ajax({
                type:'POST',
                url:'ajaxindustry.php',
                data:'state_idd='+stateID,
                success:function(html){
                    $('#city').html(html);
                }
            }); 
        } 
    }
    $('#school_board').on('change',function(){
        
        var board = $(this).val();
        if(board=='Others')
        {
            $("#other_board").prop('required',true);
            $("#other_board_div").css('display','flex'); 
        }
        else
        {
            $("#other_board").prop('required',false);
            $("#other_board_div").css('display','none'); 
            $("#other_board").val(''); 
        }
       
    });     
       
   
    </script>

<script>
$(document).ready(function() {
    var placeholderText = "Search or add item";

    function addButtonToDropdown() {
        var $container = $('.multiselect-container');
        var $searchInput = $container.find('.multiselect-search');

        // Set placeholder
        $searchInput.attr('placeholder', placeholderText);

        // Only wrap and add button once
        if (!$searchInput.parent().hasClass('add-btn-inline')) {
            $searchInput.wrap('<div class="d-flex add-btn-inline" style="width: calc(100% - 40px);"></div>');

            var $btn = $('<button type="button" class="btn btn-primary btn-sm mx-2">+ Add</button>');
            $searchInput.parent().append($btn);

            $btn.on('click', function(e) {
                e.stopPropagation(); // prevent dropdown from closing
                var $select = $('.multiselect_partner');
                var searchVal = $searchInput.val().trim();

                if (searchVal !== '') {
                    var exists = $select.find('option').filter(function() {
                        return $(this).text().toLowerCase() === searchVal.toLowerCase();
                    }).length > 0;

                    if (!exists) {
                        $select.append($('<option>', { value: searchVal, text: searchVal }));
                    }

                    // Rebuild multiselect
                    $select.multiselect('rebuild');

                    // Keep dropdown open after rebuild
                    $select.multiselect('show');

                    // Clear search input
                    $searchInput.val('');

                    // Re-add button after rebuild
                    addButtonToDropdown();
                }
            });
        }
    }

    $('.multiselect_partner').multiselect({
        buttonWidth: '100%',
        includeSelectAllOption: false,
        nonSelectedText: 'Select Partner',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 250,
        multiple: false,
        filterPlaceholder: placeholderText,
        onChange: function(option, checked) {
            // Single select behavior
            $('.multiselect_partner option').each(function() {
                if ($(this).val() != $(option).val()) {
                    $(this).prop('selected', false);
                }
            });
            $('.multiselect_partner').multiselect('refresh');
        },
        onDropdownShow: function() {
            addButtonToDropdown(); // Ensure button exists every time
        }
    });

});
</script>


<script>
$(document).ready(function() {
    $('#group_name').select2({
        placeholder: "Select Group",
        allowClear: true,
        language: {
            noResults: function() {
                return `
                    <div style="text-align:center; padding:6px;">
                        <p>No Group Found</p>
                        <button type="button" class="btn btn-sm btn-primary" id="addNewGroupBtn">
                            + Add New Group
                        </button>
                    </div>
                `;
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    // 🔹 Case 1: Handle "Add New Group" option already present in dropdown
    $('#group_name').on('select2:select', function(e) {
        var data = e.params.data;
        if (data.id === "__add_new__") {
            $('#group_name').val(null).trigger('change');
            openAddGroupPrompt();
        }
    });

    // 🔹 Case 2: Handle "Add New Group" button when no results found
    $(document).on('mousedown', '#addNewGroupBtn', function(e) {
        e.stopPropagation(); // Prevent Select2 from closing immediately
    });

    $(document).on('click', '#addNewGroupBtn', function(e) {
        e.preventDefault();
        $('#group_name').select2('close');
        setTimeout(() => openAddGroupPrompt(), 200); // Delay before prompt
    });

    // 🔹 Shared function for both cases
    function openAddGroupPrompt() {
        var newGroup = prompt("Enter new group name:");
        if (newGroup) {
            var exists = false;
            $('#group_name option').each(function() {
                if ($(this).text().toLowerCase() === newGroup.toLowerCase()) {
                    exists = true;
                    return false;
                }
            });

            if (exists) {
                toastr.info("Duplicate record: Group name already exists.");
                $('#group_name').val(null).trigger('change');
                return;
            }

            $.post('ajax_update.php', { name: newGroup, type: 'add_group' }, function(response) {
                if (response && response.id) {
                    var newOption = new Option(newGroup, response.id, true, true);
                    $('#group_name').append(newOption).trigger('change');
                    toastr.success("Group added successfully!");
                } else {
                    toastr.error("Error: Unable to add group. Please try again.");
                }
            }, 'json');
        } else {
            $('#group_name').val(null).trigger('change');
        }
    }
});




</script>