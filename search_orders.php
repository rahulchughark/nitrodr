<?php include('includes/header.php');
// admin_page(); 


if ($_POST['save_csv']) {
    $filename = $_FILES["file"]["tmp_name"];
    if ($_FILES["file"]["size"] > 0) {
        $row = 1;
        $file = fopen($filename, "r");
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
            if ($row != 1) {                
                $r_nameI = htmlspecialchars($getData[0], ENT_QUOTES);
                $r_emailI = htmlspecialchars($getData[1], ENT_QUOTES);
                $r_userI = htmlspecialchars($getData[2], ENT_QUOTES);
                $lead_statusI = htmlspecialchars($getData[3], ENT_QUOTES);
                $sourceI = htmlspecialchars($getData[4], ENT_QUOTES);
                $sub_lead_sourceI = htmlspecialchars($getData[5], ENT_QUOTES);
                $billing_resellerI = htmlspecialchars($getData[6], ENT_QUOTES);
                $credit_resellerI = htmlspecialchars($getData[7], ENT_QUOTES);
                $school_nameI = htmlspecialchars($getData[8], ENT_QUOTES);
                $is_groupI = htmlspecialchars($getData[9], ENT_QUOTES);
                $group_nameI = htmlspecialchars($getData[10], ENT_QUOTES);
                $spocI = htmlspecialchars($getData[11], ENT_QUOTES);
                $addressI = htmlspecialchars($getData[12], ENT_QUOTES);

                
                $stateI = getSingleresult("select id from states where name='".trim($getData[13])."'");
                $cityI = getSingleresult("select id from cities where city='".trim($getData[14])."'");

                $regionI = $getData[15];
                $countryI = 'India';
                $pincodeI = $getData[17];
                $contactI = $getData[18];
                $websiteI = $getData[19];
                $school_emailI = $getData[20];
                $annual_feesI = $getData[21];
                $eu_nameI = $getData[22];
                $eu_mobileI = $getData[23];
                $eu_emailI = $getData[24];
                $eu_person_name1I = $getData[25];
                $eu_designation1I = $getData[26];
                $eu_mobile1I = $getData[27];
                $eu_email1I = $getData[28];
                $eu_person_name2I = $getData[29];
                $eu_mobile2I = $getData[30];
                $eu_email2I = $getData[31];
                $adm_nameI = $getData[32];
                $adm_designationI = $getData[33];
                $adm_emailI = $getData[34];
                $adm_mobileI = $getData[35];
                $adm_alt_mobileI = $getData[36];
                $school_boardI = $getData[37];
                $grade_signed_upI = $getData[41];
                $quantityI = $getData[42];
                $purchase_noI = $getData[43];
                $purchase_deailsI = $getData[45];
                $license_periodI = $getData[46];
                $is_app_erpI = $getData[47];
                $ip_addressI = $getData[48];
                $labs_countI = $getData[49];
                $system_countI = $getData[50];
                $osI = $getData[51];
                $student_system_ratioI = $getData[52];
                $lab_teacher_ratioI = $getData[53];
                $standalone_pcI = $getData[54];
                $projectorI = $getData[55];
                $tvI = $getData[56];
                $smart_boardI = $getData[57];
                $internetI = $getData[58];
                $networkingI = $getData[59];
                $thin_clientI = $getData[60];
                $n_computingI = $getData[61];
                $created_byI = $getData[62];
                $agreement_typeI = 'Fresh';
                $team_idI = $getData[64];
                
                $productI = $getData[65];
                $productTypeI = $getData[66];
                
                $programStartDate = $getData[38] ? date("Y-m-d", strtotime($getData[38])) : '';
                $academicStartDate = $getData[39] ? date("Y-m-d", strtotime($getData[39])) : '';
                $academicEndDate = $getData[40] ? date("Y-m-d", strtotime($getData[40])) : '';
                $applicationDate = $getData[44] ? date("Y-m-d", strtotime($getData[44])) : '';
                $expectedCloseDate = $getData[70] ? date("Y-m-d", strtotime($getData[70])) : '';
                
                $stageI = $getData[69];
                $programInitationDate = $getData[71] ? date("Y-m-d", strtotime($getData[71])) : '';
                

                $recordType = $getData[63];
                $poReceive = $getData[72];
                
                $tagI = $getData[74];
                
                $sql = "INSERT INTO orders (r_name, r_email, r_user, lead_status, status, source, sub_lead_source, billing_reseller,credit_reseller, school_name, is_group, group_name, spoc, address, state, city, region,country, pincode, contact, website, school_email, annual_fees, eu_name, eu_mobile, eu_email,eu_person_name1, eu_designation1, eu_mobile1, eu_email1, eu_person_name2, eu_mobile2, eu_email2,adm_name, adm_designation, adm_email, adm_mobile, adm_alt_mobile, school_board, program_start_date,academic_start_date, academic_end_date, grade_signed_up, quantity, purchase_no, application_date,purchase_deails, license_period, is_app_erp, ip_address, labs_count, system_count, os, student_system_ratio,lab_teacher_ratio, standalone_pc, projector, tv, smart_board, internet, networking, thin_client, n_computing,created_by,created_date,agreement_type,team_id,data_ref,record_type,po_receive,stage,expected_close_date,tag) VALUES ('".$r_nameI."','".$r_emailI."','".$r_userI."','".$lead_statusI."','Pending','".$sourceI."','".$sub_lead_sourceI."','".$billing_resellerI."','".$credit_resellerI."','".$school_nameI."','".$is_groupI."','".$group_nameI."','".$spocI."','".$addressI."','".$stateI."','".$cityI."','".$regionI."','".$countryI."','".$pincodeI."','".$contactI."','".$websiteI."','".$school_emailI."','".$annual_feesI."','".$eu_nameI."','".$eu_mobileI."','".$eu_emailI."','".$eu_person_name1I."','".$eu_designation1I."','".$eu_mobile1I."','".$eu_email1I."','".$eu_person_name2I."','".$eu_mobile2I."','".$eu_email2I."','".$adm_nameI."','".$adm_designationI."','".$adm_emailI."','".$adm_mobileI."','".$adm_alt_mobileI."','".$school_boardI."','".$programStartDate."','".$academicStartDate."','".$academicEndDate."','".$grade_signed_upI."','".$quantityI."','".$purchase_noI."','".$applicationDate."','".$purchase_deailsI."','".$license_periodI."','".$is_app_erpI."','".$ip_addressI."','".$labs_countI."','".$system_countI."','".$osI."','".$student_system_ratioI."','".$lab_teacher_ratioI."','".$standalone_pcI."','".$projectorI."','".$tvI."','".$smart_boardI."','".$internetI."','".$networkingI."','".$thin_clientI."','".$n_computingI."','".$created_byI."',now(),'".$agreement_typeI."','".$team_idI."','Excel','".$recordType."','".$poReceive."','".$stageI."','".$expectedCloseDate."','".$tagI."')";
                
                $result = db_query($sql);
                $lead_id = get_insert_id();
                
                if ($lead_id > 0 && $productI > 0) {
                    $producQuery =  db_query("INSERT INTO `tbl_lead_product`(`lead_id`, `product_id`,`product_type_id`,`form_id`,`created_at`) VALUES ('" . $lead_id . "' ,'".$productI."' ,'".$productTypeI."','0',now())");
                }
            }
            $row++;
        }
        echo "<script type=\"text/javascript\">
          alert(\"CSV File has been successfully Imported.\");
          window.location = \"search_orders.php\"
        </script>";
        fclose($file);
    } else {
        echo "<script type=\"text/javascript\">
          alert(\"An error occured while uploading file.\");
          window.location = \"search_orders.php\"
        </script>";
    }
}

if($_POST['save_partner_csv']){
    $filename = $_FILES["file"]["tmp_name"];
    if ($_FILES["file"]["size"] > 0) {
        $row = 1;
        $file = fopen($filename, "r");
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
            if ($row != 1) {                
                $r_nameI = getSingleresult("SELECT name from partners where id=".$_POST['reseller']);
                $r_emailI = getSingleresult("SELECT email from users where team_id=".$_POST['reseller']." and user_type='MNGR' limit 1");
                $r_userI = getSingleresult("SELECT name from users where team_id=".$_POST['reseller']." and user_type='MNGR' limit 1");
                $lead_statusI = htmlspecialchars($getData[0], ENT_QUOTES);
                $sourceI = htmlspecialchars($getData[1], ENT_QUOTES);
                $sub_lead_sourceI = htmlspecialchars($getData[2], ENT_QUOTES);
                $billing_resellerI = $_POST['reseller'];
                $credit_resellerI = $_POST['reseller'];
                $school_nameI = htmlspecialchars($getData[3], ENT_QUOTES);
                $is_groupI = htmlspecialchars($getData[4], ENT_QUOTES);
                $group_nameI = htmlspecialchars($getData[5], ENT_QUOTES);
                $spocI = htmlspecialchars($getData[6], ENT_QUOTES);
                $addressI = htmlspecialchars($getData[7], ENT_QUOTES);

                
                $stateI = getSingleresult("select id from states where name='".trim($getData[8])."'");
                $cityI = getSingleresult("select id from cities where city='".trim($getData[9])."'");

                $regionI = $getData[10];
                $countryI = 'India';
                $pincodeI = $getData[12];
                $contactI = $getData[13];
                $websiteI = $getData[14];
                $school_emailI = $getData[15];
                $annual_feesI = $getData[16];
                $eu_nameI = $getData[17];
                $eu_mobileI = $getData[18];
                $eu_emailI = $getData[19];
                $eu_person_name1I = $getData[20];
                $eu_designation1I = $getData[21];
                $eu_mobile1I = $getData[22];
                $eu_email1I = $getData[23];
                $eu_person_name2I = $getData[24];
                $eu_mobile2I = $getData[25];
                $eu_email2I = $getData[26];
                $adm_nameI = $getData[27];
                $adm_designationI = $getData[28];
                $adm_emailI = $getData[29];
                $adm_mobileI = $getData[30];
                $adm_alt_mobileI = $getData[31];
                $school_boardI = $getData[32];
                $grade_signed_upI = $getData[36];
                $quantityI = $getData[37];
                $purchase_noI = $getData[38];
                $purchase_deailsI = $getData[40];
                $license_periodI = $getData[41];
                $is_app_erpI = $getData[42];
                $ip_addressI = $getData[43];
                $labs_countI = $getData[44];
                $system_countI = $getData[45];
                $osI = $getData[46];
                $student_system_ratioI = $getData[47];
                $lab_teacher_ratioI = $getData[48];
                $standalone_pcI = $getData[49];
                $projectorI = $getData[50];
                $tvI = $getData[51];
                $smart_boardI = $getData[52];
                $internetI = $getData[53];
                $networkingI = $getData[54];
                $thin_clientI = $getData[55];
                $n_computingI = $getData[56];
                $created_byI = getSingleresult("SELECT id from users where team_id=".$_POST['reseller']." and user_type='MNGR' limit 1");
                $agreement_typeI = 'Fresh';
                $team_idI = $_POST['reseller'];
                
                $productI = $getData[59];
                $productTypeI = $getData[60];
                
                $programStartDate = $getData[33] ? date("Y-m-d", strtotime($getData[33])) : '';
                $academicStartDate = $getData[34] ? date("Y-m-d", strtotime($getData[34])) : '';
                $academicEndDate = $getData[35] ? date("Y-m-d", strtotime($getData[35])) : '';
                $applicationDate = $getData[39] ? date("Y-m-d", strtotime($getData[39])) : '';
                $expectedCloseDate = $getData[61] ? date("Y-m-d", strtotime($getData[61])) : '';
                
                $stageI = $getData[69];
                $programInitationDate = $getData[62] ? date("Y-m-d", strtotime($getData[62])) : '';
                

                $recordType = $getData[58];
                $poReceive = $getData[63];
                
                $tagI = $getData[64];
                
                $sql = "INSERT INTO orders (r_name, r_email, r_user, lead_status, status, source, sub_lead_source, billing_reseller,credit_reseller, school_name, is_group, group_name, spoc, address, state, city, region,country, pincode, contact, website, school_email, annual_fees, eu_name, eu_mobile, eu_email,eu_person_name1, eu_designation1, eu_mobile1, eu_email1, eu_person_name2, eu_mobile2, eu_email2,adm_name, adm_designation, adm_email, adm_mobile, adm_alt_mobile, school_board, program_start_date,academic_start_date, academic_end_date, grade_signed_up, quantity, purchase_no, application_date,purchase_deails, license_period, is_app_erp, ip_address, labs_count, system_count, os, student_system_ratio,lab_teacher_ratio, standalone_pc, projector, tv, smart_board, internet, networking, thin_client, n_computing,created_by,created_date,agreement_type,team_id,data_ref,record_type,po_receive,stage,expected_close_date,tag) VALUES ('".$r_nameI."','".$r_emailI."','".$r_userI."','".$lead_statusI."','Pending','".$sourceI."','".$sub_lead_sourceI."','".$billing_resellerI."','".$credit_resellerI."','".$school_nameI."','".$is_groupI."','".$group_nameI."','".$spocI."','".$addressI."','".$stateI."','".$cityI."','".$regionI."','".$countryI."','".$pincodeI."','".$contactI."','".$websiteI."','".$school_emailI."','".$annual_feesI."','".$eu_nameI."','".$eu_mobileI."','".$eu_emailI."','".$eu_person_name1I."','".$eu_designation1I."','".$eu_mobile1I."','".$eu_email1I."','".$eu_person_name2I."','".$eu_mobile2I."','".$eu_email2I."','".$adm_nameI."','".$adm_designationI."','".$adm_emailI."','".$adm_mobileI."','".$adm_alt_mobileI."','".$school_boardI."','".$programStartDate."','".$academicStartDate."','".$academicEndDate."','".$grade_signed_upI."','".$quantityI."','".$purchase_noI."','".$applicationDate."','".$purchase_deailsI."','".$license_periodI."','".$is_app_erpI."','".$ip_addressI."','".$labs_countI."','".$system_countI."','".$osI."','".$student_system_ratioI."','".$lab_teacher_ratioI."','".$standalone_pcI."','".$projectorI."','".$tvI."','".$smart_boardI."','".$internetI."','".$networkingI."','".$thin_clientI."','".$n_computingI."','".$created_byI."',now(),'".$agreement_typeI."','".$team_idI."','Excel','".$recordType."','".$poReceive."','".$stageI."','".$expectedCloseDate."','".$tagI."')";
                
                $result = db_query($sql);
                $lead_id = get_insert_id();
                
                if ($lead_id > 0 && $productI > 0) {
                    $producQuery =  db_query("INSERT INTO `tbl_lead_product`(`lead_id`, `product_id`,`product_type_id`,`form_id`,`created_at`) VALUES ('" . $lead_id . "' ,'".$productI."' ,'".$productTypeI."','0',now())");
                }
            }
            $row++;
        }
        echo "<script type=\"text/javascript\">
          alert(\"CSV File has been successfully Imported.\");
          window.location = \"search_orders.php\"
        </script>";
        fclose($file);
    } else {
        echo "<script type=\"text/javascript\">
          alert(\"An error occured while uploading file.\");
          window.location = \"search_orders.php\"
        </script>";
    }
}
?>
<style>
    .dt-buttons {
        margin-top: 30px;
        margin-left: -191px;
        margin-bottom: 10px;
    }

    .approval-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        margin-bottom: 0;
    }

    .approval-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .approval-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #d33;
        transition: .3s;
        border-radius: 24px;
    }

    .approval-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: #fff;
        transition: .3s;
        border-radius: 50%;
    }

    .approval-switch input:checked + .approval-slider {
        background-color: #28a745;
    }

    .approval-switch input:checked + .approval-slider:before {
        transform: translateX(20px);
    }
    /* Approval select styles: white select with a colored left indicator */
    .approval-wrapper { display: inline-block; position: relative; vertical-align: middle; overflow: hidden; border-radius: 6px; }
    .approval-wrapper .approval-indicator { position: absolute; left: 0; top: 0; bottom: 0; width: 24px; border-radius: 6px 0 0 6px; pointer-events: none; }
    .approval-select { width: 140px; padding: 6px 12px 6px 30px; font-size: 13px; border-radius: 6px; border: 1px solid #ced4da; background: #fff; color: #212529; appearance: none; -webkit-appearance: none; -moz-appearance: none; }
    .approval-wrapper.pending .approval-indicator { background: linear-gradient(135deg, #ffb347 0%, #f0ad4e 100%); }
    .approval-wrapper.approved .approval-indicator { background: linear-gradient(135deg, #5cb85c 0%, #28a745 100%); }
    .approval-wrapper.rejected .approval-indicator { background: linear-gradient(135deg, #d9534f 0%, #dc3545 100%); }
    .approval-wrapper.onboard .approval-indicator { background: linear-gradient(135deg, #0275d8 0%, #007bff 100%); }
    .approval-wrapper .view-approval-reason { position: absolute; left: 0; top: 0; bottom: 0; width: 24px; display: flex; align-items: center; justify-content: center; z-index: 10; color: rgba(255, 255, 255, 0.95) !important; font-size: 13px; cursor: pointer; pointer-events: auto; transition: all 0.25s ease; }
    .approval-wrapper .view-approval-reason:hover { color: #ffffff !important; transform: scale(1.25); text-shadow: 0 0 8px rgba(255,255,255,0.8); }
    .approval-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
    }
    .approval-badge.pending { background: #fff4e6; color: #c57600; }
    .approval-badge.approved { background: #e6f7ee; color: #1f8f4e; }
    .approval-badge.rejected { background: #fdecea; color: #b02a37; }
    .approval-badge.onboard { background: #e7f1ff; color: #1155cc; }
    /* subtle focus */
    .approval-select:focus { outline: none; box-shadow: 0 0 0 2px rgba(0,123,255,0.12); border-color: #80bdff; }
    .approval-select:disabled {
        background: #f8f9fa;
        color: #6c757d;
        border-color: #e0e0e0;
        cursor: not-allowed;
        opacity: 1;
    }
    .approval-wrapper:has(.approval-select:disabled) {
        opacity: 0.95;
    }
    .approval-wrapper:has(.approval-select:disabled)::after {
        color: #adb5bd;
    }
    .approval-wrapper::after { content: '\25BC'; position: absolute; right: 8px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #6c757d; font-size: 11px; }
</style>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm">
                                    <div class="media bredcrum-title">
                                        <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">

                                            <small class="text-muted">Home >Leads</small>
                                            <h4 class="font-size-14 m-0 mt-1">Search Leads</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-auto pt-2 pt-sm-0">
                                    <div class="" role="group">
                                    <?php if ($_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'ADMIN') { ?>

                                            <a href="javascript:void(0);" onclick="show_import('all')"><button  title="Import Leads" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-plus "></i></button></a>
                                            <a href="javascript:void(0);" onclick="show_import('partner')"><button title="Import Partner Leads" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-plus "></i></button></a>

                                            <?php } ?>
                                    <?php if($_SESSION['download_status'] == 1){ 
                                            $stageStr = $stage ? implode("','",$stage) : '';
                                            $partnerStr = $partner ? implode(",",$partner) : '';
                                            $tagStr = $tag ? implode(",",$tag) : '';
                                            $userStr = $users ? implode(",",$users) : '';
                                            $sub_stageStr = $sub_stage ? implode("','",$sub_stage) : '';
                                            $school_boardStr = $school_board ? implode("','",$school_board) : '';
                                            $lead_statusStr = $lead_status ? implode("','",$lead_status) : '';
                                            $stateStr = $state ? implode(",",$state) : '';
                                            ?>
                                        <a href="export_admin_lead.php?d_from=<?= @$_GET['d_from'] ?>&d_to=<?= @$_GET['d_to'] ?>&d_type=<?= @$_GET['dtype'] ?>&license='Fresh'&stage=<?= $stageStr ?>&partner=<?= $partnerStr ?>&tag=<?= $tagStr ?>&user=<?= $userStr ?>&sub_stage=<?= $sub_stageStr ?>&school_board=<?= $school_boardStr ?>&lead_status=<?= $lead_statusStr ?>&state=<?= $stateStr ?>">
                                            <button title="Excel Export" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-share"></i>
                                            </button>
                                        </a>
                                        <?php } ?>
                                        <!-- <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button> -->

                                        <div class="dropdown dropdown-lg">

                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                                                <form method="get" id="search-form" name="search">
                                                    <div class="row">
                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select class="form-control" name="dtype">
                                                                <option value="">Select Date Type</option>
                                                                <option <?= (($_GET['dtype'] == 'created') ? 'selected' : '') ?> value="created">Created Date</option>
                                                                <option <?= (($_GET['dtype'] == 'actioned_date') ? 'selected' : '') ?> value="actioned_date">Actioned Date</option>
                                                                <option <?= (($_GET['dtype'] == 'close') ? 'selected' : '') ?> value="close">Close Date</option>
                                                                <option <?= (($_GET['dtype'] == 'stage') ? 'selected' : '') ?> value="stage">Stage Change</option>
                                                                <option <?= (($_GET['dtype'] == 'lead_status') ? 'selected' : '') ?> value="lead_status">Lead Status Change</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select class="form-control" name="ownership">
                                                                <option value="">Lead Ownership</option>
                                                                <option <?= (($_GET['ownership'] == 'all') ? 'selected' : '') ?> value="all">All</option>
                                                                <option <?= (($_GET['ownership'] == 'my_leads') ? 'selected' : '') ?> value="my_leads">My Leads</option>
                                                                <option <?= (($_GET['ownership'] == 'assigned_to_me') ? 'selected' : '') ?> value="assigned_to_me">Assigned To Me</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <div class="input-daterange input-group" id="datepicker-close-date">

                                                                <input type="text" autocomplete="off" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                                <input type="text" autocomplete="off" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                            </div>
                                                        </div>
                                                        <?php if (!is_array($status)) {
                                                            $val = $status;
                                                            $status = array();
                                                            $status['0'] = $val;
                                                        }
                                                        ?>

                                                        <?php
                                                        if ($_SESSION['sales_manager'] != 1) {
                                                            $res = db_query("select * from partners where status='Active'");
                                                        } else {
                                                            $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active'");
                                                        }
                                                        ?>

                                                    <!-- </div>
                                                    <div class="row"> -->


                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <?php
                                                            if (!is_array($partner)) {
                                                                $val = $partner;
                                                                $partner = array();
                                                                $partner['0'] = $val;
                                                            }

                                                            if ($_SESSION['user_type'] != 'SALES MNGR') {
                                                            $res = db_query("select * from partners where status='Active'");
                                                            } else {
                                                            $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active'");
                                                            }
                                                            ?>

                                                            <select name="partner[]" id="partner" class="multiselect_partner form-control" data-live-search="true" multiple>
                                                            <?php while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (in_array($row['id'], $partner ?? []) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <?php if ($_GET['partner']) { ?>

                                                                <select name="users[]" class="multiselect_user1 form-control" data-live-search="true" multiple>

                                                                    <?php $query = db_query('SELECT * FROM users WHERE team_id in ("' . implode('", "', $_GET['partner']) . '") and status="Active"  ORDER BY name ASC');
                                                                    while ($row = db_fetch_array($query)) { ?>
                                                                        <option <?= (@in_array($row['id'], $users ?? []) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                                                    <?php } ?>
                                                                </select>

                                                            <?php } else { ?>
                                                                <div id="users">
                                                                </div>
                                                            <?php } ?>
                                                        </div>

                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <?php
                                                            $sqlStage = "select * from stages where 1";
                                                            $stageList = db_query($sqlStage);

                                                        if (!is_array($stage)) {
                                                                $val = $stage;
                                                                $stage = array();
                                                                $stage['0'] = $val;
                                                                $st_flag = 1;
                                                            }
                                                            if ($_GET['stage']) {
                                                                $get_stage = $_GET['stage'];
                                                                $query = db_query('select * from stages where stage_name IN ("' . implode('", "', $get_stage) . '")');
                                                                while ($row = db_fetch_array($query)) {
                                                                    $stage_arr[] = $row['stage_name'];
                                                                }
                                                            ?>

                                                                <select name="stage[]" id="stage" data-live-search="true" multiple class="multiselect_stage form-control ">

                                                                    <?php
                                                                    while ($stag = db_fetch_array($stageList)) {
                                                                    ?>
                                                                        <option value="<?= $stag['stage_name'] ?>" <?= (in_array($stag['stage_name'], $stage_arr ?? []) ? 'selected' : '') ?>><?= $stag['stage_name'] ?></option>
                                                                    <?php  } ?>
                                                                </select>
                                                            <?php } else { ?>

                                                                <select name="stage[]" id="stage" data-live-search="true" multiple class="multiselect_stage form-control ">

                                                                    <?php while ($stag = db_fetch_array($stageList)) { ?>
                                                                        <option value="<?= $stag['stage_name'] ?>" <?= (($stag['stage_name'] == $stage) ? 'selected' : '') ?>><?= $stag['stage_name'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            <?php } ?>

                                                        </div>

                                                        <div class="form-group col-md-6 col-xl-3" id="sub_stageD">
                                                            <?php if ($_GET['stage']) { ?>

                                                                <select name="sub_stage[]" class="multiselect_sub_stage form-control" data-live-search="true" multiple>

                                                                    <?php $query = db_query('select * from sub_stage where stage_name IN ("' . implode('", "', $get_stage) . '")');
                                                                    while ($row = db_fetch_array($query)) { ?>
                                                                        <option <?= (@in_array($row['name'], $sub_stage ?? []) ? 'selected' : '') ?> value="<?= $row['name'] ?>"><?= ucwords($row['name']) ?></option>
                                                                    <?php } ?>
                                                                </select>

                                                            <?php } else { ?>
                                                                <div id="sub_stageD">
                                                                </div>
                                                            <?php } ?>
                                                        </div>

                                                        <?php if (!is_array($state)) {
                                                            $val = $state;
                                                            $state = array();
                                                            $state['0'] = $val;
                                                            $state_flag = 1;
                                                        }
                                                        ?>
                                                        <?php if (!is_array($school_board)) {
                                                            $val = $school_board;
                                                            $school_board = array();
                                                            $school_board['0'] = $val;
                                                            $school_board_flag = 1;
                                                        }
                                                        ?>
                                                            
                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select name="state[]" class="form-control" id="multiselect_state" multiple>
                                                                <?php $ress = db_query("select * from states");
                                                                while ($row = db_fetch_array($ress)) { ?>
                                                                    <option <?= (in_array($row['id'], $_GET['state'] ?? []) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                <?php  } ?>

                                                            </select>
                                                        </div>
                                                                                                                <div class="form-group col-md-6 col-xl-3" id="cityD">
                                                            <?php if ($_GET['state']) { ?>

                                                                <select name="city[]" class="multiselect_city form-control" data-live-search="true" multiple>

                                                                    <?php 
                                                                    // print_r($state);die;
                                                                    $query = db_query("SELECT * FROM cities where state_id  IN (" . implode(",", $_GET['state']) . ")");
                                                                    while ($row = db_fetch_array($query)) { ?>
                                                                        <option <?= (@in_array($row['id'], $city ?? []) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= $row['city'] ?></option>
                                                                    <?php } ?>
                                                                </select>

                                                            <?php } else { ?>
                                                                <div id="cityD">
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="form-group col-md-6 col-xl-3">
                                                    <select name="school_board[]" id="multiselect_school_board" class="form-control" multiple>
                                                    <option <?= (in_array('CBSE', $school_board ?? []) ? 'selected' : '') ?> value="CBSE">CBSE</option>
                                                    <option <?= (in_array('ICSE', $school_board ?? []) ? 'selected' : '') ?> value="ICSE">ICSE</option>
                                                    <option <?= (in_array('IB', $school_board ?? []) ? 'selected' : '') ?> value="IB">IB</option>
                                                    <option <?= (in_array('IGCSE', $school_board ?? []) ? 'selected' : '') ?> value="IGCSE">IGCSE</option>
                                                    <option <?= (in_array('STATE', $school_board ?? []) ? 'selected' : '') ?> value="STATE">STATE</option>
                                                    <option <?= (in_array('Others', $school_board ?? []) ? 'selected' : '') ?> value="Others">Others</option>
                                                    </select>
                                                        </div>
                                                        
                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select name="lead_status[]" data-live-search="true" multiple class="form-control" id="multiselectleadstatus">
                                                                <option <?= (@in_array('Raw Data', $lead_status ?? []) ? 'selected' : '') ?> value="Raw Data">Raw Data</option>
                                                                <option <?= (@in_array('Validation', $lead_status ?? []) ? 'selected' : '') ?> value="Validation">Validation</option>
                                                                <option <?= (@in_array('Contacted', $lead_status ?? []) ? 'selected' : '') ?> value="Contacted">Contacted</option>
                                                                <option <?= (@in_array('Qualified', $lead_status ?? []) ? 'selected' : '') ?> value="Qualified">Qualified</option>
                                                                <option <?= (@in_array('Unqualified', $lead_status ?? []) ? 'selected' : '') ?> value="Unqualified">Unqualified</option>
                                                                <option <?= (@in_array('Duplicate', $lead_status ?? []) ? 'selected' : '') ?> value="Duplicate">Duplicate</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select name="status[]" data-live-search="true" multiple class="form-control" id="multiselectleadqualifiedstatus">
                                                            <!-- <option value="">---Select---</option> -->
                                                            <option <?= (@in_array('Undervalidation', $status ?? []) ? 'selected' : '') ?> value="Undervalidation">Re-Submission Required</option>
                                                            <option <?= (@in_array('Approved', $status ?? []) ? 'selected' : '') ?> value="Approved">Qualified</option>
                                                            <option <?= (@in_array('Cancelled', $status ?? []) ? 'selected' : '') ?> value="Cancelled">Unqualified</option>
                                                            <option <?= (@in_array('On-Hold', $status ?? []) ? 'selected' : '') ?> value="On-Hold">On-Hold</option>
                                                            </select>
                                                        </div>

                                                        
                                                        <div class="form-group col-md-6 col-xl-3">
                                                        <select name="source[]" class="form-control" id="lead_source" placeholder="" multiple>
                                                                <?php $res = db_query("select * from lead_source where status=1");
                                                            while ($row = db_fetch_array($res)) { ?>
                                                                <option <?= (@in_array($row['lead_source'], $source ?? []) ? 'selected' : '') ?> value="<?= $row['lead_source'] ?>"><?= $row['lead_source'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        </div>
                                                        <?php
                                                                $sqlTag = "select * from tag where 1";
                                                                $tagList = db_query($sqlTag);
                                                                ?>

                                                                <?php if (!is_array($tag)) {
                                                                    $val = $tag;
                                                                    $tag = array();
                                                                    $tag['0'] = $val;
                                                                } ?>
                                                        <div class="form-group col-md-6 col-xl-3">
                                                        <select name="tag[]" data-live-search="true" multiple class="form-control" id="multiselecttag">
                                                            <?php while ($tags = db_fetch_array($tagList)) { ?>
                                                                <option value="<?= $tags['id'] ?>" <?= (in_array($tags['id'], $tag ?? []) ? 'selected' : '') ?>><?= $tags['name'] ?></option>
                                                            <?php } ?>
                                                            </select>
                                                            </div>
                                                        <div class="col-md-3 col-xl-2">
                                                            <button type="submit" class="btn btn-primary font-14"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                        </div>

                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            


                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Lead Added Successfully!
                                </div>
                            <?php } ?>
                            <?php if ($_GET['update'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Lead Updated Successfully!
                                </div>
                            <?php } ?>
                            <?php if ($_GET['m'] == 'nodata') { ?>
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-danger"><i class="fa fa-check-circle"></i> Error!</h3> No data found!
                                </div>
                            <?php } ?>
                            <div class="table-responsive">
                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                        <th>S.No.</th>
                                        <th>Company Name</th>
                                        <th>Customer Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Designation</th>
                                        <th>Product</th>
                                        <th>No. of Licenses</th>
                                        <th>Subscription Term</th>
                                        <th>Stage</th>
                                        <th>Proof Engagement</th>
                                        <th>Expiry Date</th>
                                        <th>Expire In</th>
                                        <?php if (($_SESSION['user_type'] ?? '') === 'MNGR') { ?>
                                        <th>Created By</th>
                                        <?php } ?>
                                        <!-- <th>Status</th> -->
                                        <th>Approval</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>


                                </table>
                            </div>

                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <div id="myModal1" class="modal" role="dialog">

        </div>

        <?php include('includes/footer.php') ?>

        <script>
            $('#leads').DataTable({
                "dom": '<"top"if>Brt<"bottom"ip><"clear">',
                //dom: 'Bfrtip',
                "displayLength": 15,
                "scrollX": false,
                "fixedHeader": true,

                language: {
                    infoFiltered: "",
                    paginate: {
                        previous: '<i class="fas fa-arrow-left"></i>',
                        next: '<i class="fas fa-arrow-right"></i>'
                    }
                },
                
                 buttons: [
                 <?php if($_SESSION['download_status'] == 1){ ?>
                    'copy', 'csv', 'excel',  'print', 'pageLength',
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    }
                <?php }else{ ?> 'pageLength'  <?php } ?>    
                ],
                lengthMenu: [
                    [15, 25, 50, 100, 500, 1000, 5000, 10000, 15000],
                    ['15', '25', '50', '100', '500', '1000', '5000', '10000', '15000']
                ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "get_orders.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                    d.d_from = "<?= $_GET['d_from'] ?>";
                    d.d_to = "<?= $_GET['d_to'] ?>";
                    d.d_type = "<?= $_GET['dtype'] ?>";
                    d.stage = '<?= json_encode($_GET['stage']) ?>';
                    d.users = '<?= json_encode($_GET['users']) ?>';
                    d.state = '<?= json_encode($_GET['state']) ?>';
                    d.city = '<?= json_encode($_GET['city']) ?>';
                    d.sub_stage = '<?= json_encode($_GET['sub_stage']) ?>';
                    d.school_board = '<?= json_encode($_GET['school_board']) ?>';
                    d.lead_status = '<?= json_encode($_GET['lead_status']) ?>';
                    d.partner = '<?= json_encode($_GET['partner']) ?>';
                    d.tag = '<?= json_encode($_GET['tag']) ?>';
                    d.source = '<?= json_encode($_GET['source']) ?>';
                    d.status = '<?= json_encode($_GET['status']) ?>';
                    d.product_typeDS = '<?= json_encode($_GET['product_typeDS']) ?>';
                    d.productDS = '<?= json_encode($_GET['productDS']) ?>';
                    d.ownership = '<?= json_encode($_GET['ownership']) ?>';
                    d.mngr_team_scope = <?= ($_SESSION['user_type'] === 'MNGR' ? 1 : 0) ?>;
                    d.approval_badge = '1';
                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="17">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");

                    }
                },
                //"initComplete": function () {
                // },
                "order": [
                    [0, "desc"]
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],

                'columns': [
                            { data: 'id' },
                            { data: 'company_name' },
                            { data: 'customer_name' },
                            { data: 'email' },
                            { data: 'phone' },
                            { data: 'designation' },
                            { data: 'product' },
                            { data: 'licenses' },
                            { data: 'subscription_term' },
                            { data: 'stage_id' },
                            { data: 'proof_engagement_id' },
                            { data: 'expiry_date' },
                            { data: 'expire_in' },
                            <?php if (($_SESSION['user_type'] ?? '') === 'MNGR') { ?>
                            { data: 'created_by_name' },
                            <?php } ?>
                            // Status column removed
                            {
                                data: 'approval',
                                render: function(data, type, row) {
                                        var userType = '<?= $_SESSION['user_type'] ?>';
                                        // If users see only numeric approval codes, show readable badges
                                        if (userType === 'USR' || userType === 'MNGR') {
                                            if (data === 0 || data === '0') return '<span class="badge badge-warning">Pending</span>';
                                            if (data === 1 || data === '1') return '<span class="badge badge-success">Approve</span>';
                                            if (data === 2 || data === '2') return '<span class="badge badge-danger">Reject</span>';
                                            if (data === 3 || data === '3') return '<span class="badge badge-primary">Onboard</span>';
                                            return data;
                                        }

                                        // For other roles, return HTML if provided (select or toggle) or raw value
                                        return data;
                                    }
                            },
                            { data: 'created_at', className: 'text-nowrap' },
                            { data: 'action', className: 'text-nowrap' }
                        ]
            });


            function clear_search() {
                window.location = 'search_orders.php';
            }

            $(document).ready(function() {
                $('.multiselect').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Lead Type',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_stage').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Stage',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_productType').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Product Type',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_status').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Status',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_city').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select City',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_user1').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select User',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#quantity').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Quantity',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_partner').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Partner',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_status').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Status',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_productType').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Product Type',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselect').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Stage',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselect_school_board').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select School Board',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselectleadstatus').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Lead Status',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselect_state').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select State',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_sub_stage').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Sub Stage',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselecttag').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Tag',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#lead_source').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Lead Source',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselectleadqualifiedstatus').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Qualified Status',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
            });

            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });
        </script>

        <script>
            $(document).ready(function() {
                $('#partner').on('change', function() {
                    //alert("hi");
                    var partnerID = $(this).val();
                    if (partnerID) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajaxusers.php',
                            data: 'partner=' + partnerID,
                            success: function(html) {
                                //alert(html);
                                $('#users').html(html);
                            }
                        });
                    }
                });
            });

            $(document).ready(function() {
                $('#stage').on('change', function() {

                var e = $(this).val();
                    if (e) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajaxusers.php',
                            data: 'stage=' + e,
                            success: function(html) {
                                //alert(html);
                                $('#sub_stageD').html(html);
                            }
                        });
                    }
                });
            });

            function stage_change(ids, id) {
                //$('.preloader').show();
                var page_access = 'true';
                $.ajax({
                    type: 'POST',
                    url: 'stage_change.php',
                    data: {
                        pid: id,
                        ids: ids,
                        page_access: page_access
                    },
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function chage_stage(stage, id, ids, substage, payment_status, attachments,demo_datetime) {
            
if (stage != '') {
    var formData = new FormData();
    formData.append('stage', stage);
    formData.append('substage', substage);
    formData.append('lead_id', id);
    formData.append('payment_status', payment_status);
    formData.append('demo_datetime', demo_datetime);

    // Append files to FormData
    for (var i = 0; i < attachments.length; i++) {
        formData.append('attachments[]', attachments[i]);
    }

    $('#myModal1').modal('hide');
    $.ajax({
        type: 'post',
        url: 'change_stage.php',
        data: formData,
        processData: false,
        contentType: false,
                        success: function(res) {
                            var res = $.trim(res);
                            if (res == 'success') {
                                swal({
                                    title: "Done!",
                                    text: "Stage changed Successfully.",
                                    type: "success"
                                }, function() {
                                    $('#myModal1').modal('hide');
                                    var idss = "'but" + id + "'";
                                    var link = stage + '<a href="javascript:void(0)" title="Change Stage" id=but' + id + ' onclick="stage_change(' + idss + ',' + id + ')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>'
                                    $("#" + ids).parent().html(link);
                                    $('#leads').DataTable().ajax.reload();
                                });

                            } else {
                                swal({
                                    title: "Error!",
                                    text: res,
                                    type: "error"
                                }, function() {

                                });
                            }
                        }
                    });
                }
            }

            $(document).ready(function() {
                //alert("hi");
                $('.product_data').on('change', function() {

                    var productID = $(this).val();

                    if (productID) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajaxProductTypeAdmin.php',
                            data: 'product_id=' + productID,
                            success: function(html) {
                                $('#product_type_data').html(html);

                            },
                        });
                    }
                });
            });

            $('#industry').on('change', function() {

                var industry = $(this).val();
                //alert(stateID);
                if (industry) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxindustry.php',
                        data: 'industry=' + industry,
                        success: function(response) {
                            $('#sub_industry').html(response);
                        },
                        error: function() {
                            $('#sub_industry').html('There was an error!');
                        }
                    });
                } else {
                    $('#sub_industry').html('<option value="">Select industry first</option>');
                }
            });

            function relog(id) {
                if (id) {
                    swal({
                        title: "Are you sure?",
                        text: "You want to relog the same lead!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, Re-Log it!",
                        cancelButtonText: "No, cancel modification!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }, function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "relog_lead.php?id=" + id,
                                success: function(result) {
                                    if (result) {
                                        swal({
                                            title: "Done!",
                                            text: "Lead Re-Loged.",
                                            type: "success"
                                        }, function() {
                                            //location.reload();
                                            $('#leads').DataTable().ajax.reload();

                                        });
                                    }
                                }
                            });

                        } else {
                            swal("Cancelled", "Lead unchanged!", "error");
                        }
                    });
                }
            }
        </script>

        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 311);
                $("#leads").tableHeadFixer();

            });

            function cd_change(ids, id) {
                //$('.preloader').show();
                $.ajax({
                    type: 'POST',
                    url: 'cd_change.php',
                    data: {
                        pid: id,
                        ids: ids
                    },
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function change_cdDate(cd_date, id, ids) {
                if (cd_date != '') {
                    $('#myModal1').modal('hide');
                    $.ajax({
                        type: 'post',
                        url: 'change_cdDate.php',
                        data: {
                            cd_date: cd_date,
                            lead_id: id
                        },
                        success: function(res) {
                            res = $.trim(res);
                            if (res == 'success') {
                                swal({
                                    title: "Done!",
                                    text: "Close Date changed Successfully.",
                                    type: "success"
                                }, function() {
                                    $('#myModal1').modal('hide');
                                    var ids2 = "'but2" + id + "'";
                                    //alert(ids2);
                                    var newDate = convertDate(cd_date);
                                    var link = newDate + '<a href="javascript:void(0)" title="Change Close Date" id=but2' + id + ' onclick="cd_change(' + ids2 + ',' + id + ')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>'
                                    $("#" + ids).parent().html(link);
                                    $('#leads').DataTable().ajax.reload();
                                });

                            } else {
                                swal({
                                    title: "Error!",
                                    text: res,
                                    type: "error"
                                }, function() {

                                });
                            }
                        }
                    });
                }
            }

            function convertDate(dateString) {
                var p = dateString.split(/\D/g)
                return [p[2], p[1], p[0]].join("-")
            }
            function status_change(ids, id) {
                var page_access = 'true';
                $.ajax({
                    type: 'POST',
                    url: 'status_change.php',
                    data: {
                        pid: id,
                        ids: ids
                    },
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function show_import(e) {
                $.ajax({
                    type: 'POST',
                    url: 'import_leads.php',
                    data: {
                            type: 'Lead',
                            importFor : e,
                        },
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function updateApproval(element) {
                var leadId = element.getAttribute('data-id');
                var approvalStatus = element.checked ? 1 : 0;
                var previousState = !element.checked;

                swal({
                    title: "Are you sure?",
                    text: "Do you want to change the approval status?",
                    type: "warning",
                    cancelButtonText: "No",
                    showCancelButton: true,
                    confirmButtonText: "Yes, Update",
                    confirmButtonColor: "#28a745",
                    closeOnConfirm: false
                }, function(isConfirm) {
                    if (!isConfirm) {
                        element.checked = previousState;
                        return;
                    }

                    $.ajax({
                        url: "ajax_update.php",
                        type: "POST",
                        dataType: "json",
                        data: {
                            action: 'update_approval',
                            lead_id: leadId,
                            is_approved: approvalStatus
                        },
                        success: function(response) {
                            if (response.status === "success") {
                                swal("Success!", response.message, "success");
                            } else {
                                swal("Error!", response.message || "Update failed", "error");
                                element.checked = previousState;
                            }
                            $('#leads').DataTable().ajax.reload(null, false);
                        },
                        error: function() {
                            swal("Error!", "Server error occurred.", "error");
                            element.checked = previousState;
                        }
                    });
                });
            }


            // Handle select-based approval changes (Admin selects)
            $(document).on('change', '.approval-select', function() {
                var $sel = $(this);
                var id = $sel.data('id');
                var status = $sel.val();
                var previousState = $sel.data('prev');

                function applyStyle($el, val) {
                    var $wrap = $el.closest('.approval-wrapper');
                    if ($wrap.length === 0) {
                        $el.wrap('<span class="approval-wrapper"></span>');
                        $wrap = $el.closest('.approval-wrapper');
                        if ($wrap.find('.approval-indicator').length === 0) $wrap.prepend('<i class="approval-indicator"></i>');
                    }
                    $wrap.removeClass('pending approved rejected onboard');
                    if (val === '1' || val === 1) $wrap.addClass('approved');
                    else if (val === '2' || val === 2) $wrap.addClass('rejected');
                    else if (val === '3' || val === 3) $wrap.addClass('onboard');
                    else $wrap.addClass('pending');
                }

                swal({
                    title: "Are you sure?",
                    text: "Do you want to change the approval status?",
                    type: "warning",
                    cancelButtonText: "No",
                    showCancelButton: true,
                    confirmButtonText: "Yes, Update",
                    confirmButtonColor: "#28a745",
                    closeOnConfirm: false
                }, function(isConfirm) {
                    if (!isConfirm) {
                        $sel.val(previousState);
                        applyStyle($sel, previousState);
                        return;
                    }
                    $.ajax({
                        url: "ajax_update.php",
                        type: "POST",
                        dataType: "json",
                        data: {
                            action: 'update_approval',
                            lead_id: id,
                            is_approved: status
                        },
                        success: function(response) {
                            if (response.status === "success") {
                                swal("Success!", response.message, "success");
                                $sel.data('prev', status);
                            } else {
                                swal("Error!", response.message || "Update failed", "error");
                                $sel.val(previousState);
                            }
                            applyStyle($sel, $sel.val());
                            $('#leads').DataTable().ajax.reload(null, false);
                        },
                        error: function() {
                            swal("Error!", "Server error occurred.", "error");
                            $sel.val(previousState);
                            applyStyle($sel, previousState);
                        }
                    });
                });
            });

            // Store previous value on focus
            $(document).on('focusin', '.approval-select', function() {
                var $s = $(this);
                $s.data('prev', $s.val());
            });

            // Wrap and style selects after table draw
            $('#leads').on('draw.dt', function() {
                $('#leads').find('select').each(function() {
                    var $s = $(this);
                    if (!$s.hasClass('approval-select')) $s.addClass('approval-select');
                    $s.addClass('form-control form-control-sm');
                    if ($s.closest('.approval-wrapper').length === 0) {
                        $s.wrap('<span class="approval-wrapper"></span>');
                        $s.closest('.approval-wrapper').prepend('<i class="approval-indicator"></i>');
                    }
                    $s.data('prev', $s.val());
                    var val = $s.val();
                    var $wrap = $s.closest('.approval-wrapper');
                    $wrap.removeClass('pending approved rejected onboard');
                    if (val === '1' || val === 1) $wrap.addClass('approved');
                    else if (val === '2' || val === 2) $wrap.addClass('rejected');
                    else if (val === '3' || val === 3) $wrap.addClass('onboard');
                    else $wrap.addClass('pending');
                });
            });

                            $(document).ready(function() {
                $('#multiselect_state').on('change', function() {

                var e = $(this).val();
                    if (e) {
                        $.ajax({
                            type: 'POST',
                            url: 'general_changes.php',
                            data: 'state=' + e,
                            success: function(html) {
                                //alert(html);
                                $('#cityD').html(html);
                            }
                        });
                    }
                });
            });
        </script>