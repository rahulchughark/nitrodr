<?php include('includes/header.php');
include('includes/audit_log_helper.php');
admin_page(); 


// sendMail($addTo, $addCc, $addBcc, $setSubject, $body)

// sendMail(["rahul.chugh@arkinfo.in"], [], [], "Test Subject", "This is a test email body.");


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
        background-color: #d5d5d5;
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
    .approval-wrapper { display: inline-block; position: relative; vertical-align: middle; }
    .approval-wrapper .approval-indicator { position: absolute; left: 0; top: 0; bottom: 0; width: 8px; border-radius: 6px 0 0 6px; }
    .approval-select { width: 140px; padding: 6px 12px 6px 18px; font-size: 13px; border-radius: 6px; border: 1px solid #ced4da; background: #fff; color: #212529; appearance: none; -webkit-appearance: none; -moz-appearance: none; }
    .approval-wrapper.pending .approval-indicator { background: #f0ad4e; }
    .approval-wrapper.approved .approval-indicator { background: #28a745; }
    .approval-wrapper.rejected .approval-indicator { background: #dc3545; }
    .approval-wrapper.onboard .approval-indicator { background: #007bff; }
    .approval-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.2;
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
    .sweet-alert .approval-note-red {
        color: #dc3545;
        font-weight: 600;
    }
    /* ensure the built-in dropdown arrow remains visible */
    .approval-wrapper::after { content: '\25BC'; position: absolute; right: 8px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #6c757d; font-size: 11px; }
    .global-ajax-loader {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.35);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .global-ajax-loader .loader-card {
        background: #fff;
        padding: 12px 16px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: #1B274D;
    }
    .global-ajax-loader .loader-spinner {
        width: 18px;
        height: 18px;
        border: 2px solid #dce3ea;
        border-top-color: #007bff;
        border-radius: 50%;
        animation: globalAjaxSpin .8s linear infinite;
    }
    @keyframes globalAjaxSpin {
        to { transform: rotate(360deg); }
    }
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
                                        <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                        <div class="dropdown dropdown-lg">

                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                                                <form method="get" id="search-form" name="search">
                                                    <div class="row">
                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select name="subscription_term[]" class="form-control" id="multiselect_subscription_term" multiple>
                                                                <option <?= (in_array('1', $_GET['subscription_term'] ?? []) ? 'selected' : '') ?> value="1">1 Year</option>
                                                                <option <?= (in_array('3', $_GET['subscription_term'] ?? []) ? 'selected' : '') ?> value="3">3 Year</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select name="stage_id[]" class="form-control" id="multiselect_stage_id" multiple>
                                                                <?php $stageRes = db_query("SELECT id, name FROM tbl_mst_stage WHERE status=1 ORDER BY name ASC");
                                                                while ($stageRow = db_fetch_array($stageRes)) { ?>
                                                                    <option <?= (in_array((string)$stageRow['id'], $_GET['stage_id'] ?? []) ? 'selected' : '') ?> value="<?= $stageRow['id'] ?>"><?= $stageRow['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select name="proof_engagement_id[]" class="form-control" id="multiselect_proof_engagement_id" multiple>
                                                                <?php $proofRes = db_query("SELECT id, name FROM tbl_mst_proof_engagement WHERE status=1 ORDER BY name ASC");
                                                                while ($proofRow = db_fetch_array($proofRes)) { ?>
                                                                    <option <?= (in_array((string)$proofRow['id'], $_GET['proof_engagement_id'] ?? []) ? 'selected' : '') ?> value="<?= $proofRow['id'] ?>"><?= $proofRow['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select name="approval_status[]" class="form-control" id="multiselect_approval_status" multiple>
                                                                <option <?= (in_array('1', $_GET['approval_status'] ?? []) ? 'selected' : '') ?> value="1">Approved</option>
                                                                <option <?= (in_array('0', $_GET['approval_status'] ?? []) ? 'selected' : '') ?> value="0">Not Approved</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select name="assigned_partner_id[]" class="form-control" id="multiselect_assigned_partner" multiple>
                                                                <?php $assignedPartners = db_query("SELECT id, name FROM partners WHERE status='Active' ORDER BY name ASC");
                                                                while ($assignedPartner = db_fetch_array($assignedPartners)) { ?>
                                                                    <option <?= (in_array((string)$assignedPartner['id'], $_GET['assigned_partner_id'] ?? []) ? 'selected' : '') ?> value="<?= $assignedPartner['id'] ?>"><?= $assignedPartner['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select name="align_to[]" class="form-control" id="multiselect_align_to" multiple>
                                                                <?php
                                                                $selectedAlignTo = $_GET['align_to'] ?? [];
                                                                $selectedAssignedPartners = $_GET['assigned_partner_id'] ?? [];
                                                                $selectedAssignedPartners = array_filter(array_map('intval', (array)$selectedAssignedPartners));

                                                                if (!empty($selectedAssignedPartners)) {
                                                                    $alignUserRes = db_query("SELECT id, name FROM users WHERE status='Active' AND team_id IN (" . implode(',', $selectedAssignedPartners) . ") ORDER BY name ASC");
                                                                } else {
                                                                    $selectedAlignToIds = array_filter(array_map('intval', (array)$selectedAlignTo));
                                                                    if (!empty($selectedAlignToIds)) {
                                                                        $alignUserRes = db_query("SELECT id, name FROM users WHERE status='Active' AND id IN (" . implode(',', $selectedAlignToIds) . ") ORDER BY name ASC");
                                                                    } else {
                                                                        $alignUserRes = false;
                                                                    }
                                                                }

                                                                if ($alignUserRes) {
                                                                    while ($alignUser = db_fetch_array($alignUserRes)) {
                                                                        $isSelected = in_array((string)$alignUser['id'], (array)$selectedAlignTo) ? 'selected' : '';
                                                                        echo "<option {$isSelected} value=\"{$alignUser['id']}\">" . htmlspecialchars($alignUser['name'], ENT_QUOTES, 'UTF-8') . "</option>";
                                                                    }
                                                                }
                                                                ?>
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

        <div id="globalAjaxLoader" class="global-ajax-loader" aria-hidden="true">
            <div class="loader-card">
                <span class="loader-spinner"></span>
                <span id="globalAjaxLoaderText">Please wait...</span>
            </div>
        </div>

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
                    d.subscription_term = '<?= json_encode($_GET['subscription_term'] ?? []) ?>';
                    d.stage_id = '<?= json_encode($_GET['stage_id'] ?? []) ?>';
                    d.proof_engagement_id = '<?= json_encode($_GET['proof_engagement_id'] ?? []) ?>';
                    d.approval_status = '<?= json_encode($_GET['approval_status'] ?? []) ?>';
                    d.assigned_partner_id = '<?= json_encode($_GET['assigned_partner_id'] ?? []) ?>';
                    d.align_to = '<?= json_encode($_GET['align_to'] ?? []) ?>';
                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="16">No data found on server!</th></tr></tbody>');
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
                            { data: 'approval' },
                            { data: 'created_at', className: 'text-nowrap' },
                            { data: 'action', className: 'text-nowrap' }
                        ]
            });


            function clear_search() {
                window.location = 'admin_leads.php';
            }

            $(document).ready(function() {
                $('#multiselect_subscription_term').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Subscription Term',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselect_stage_id').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Stage',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselect_proof_engagement_id').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Proof Engagement',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselect_approval_status').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Approval Status',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselect_assigned_partner').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Assigned to Partner',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselect_align_to').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Align To',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });

                function loadAlignToFilterUsers(selectedUsers) {
                    var partnerIds = $('#multiselect_assigned_partner').val() || [];

                    $.ajax({
                        type: 'POST',
                        url: 'ajax_update.php',
                        data: {
                            action: 'get_partner_users_by_partners',
                            partner_ids: partnerIds,
                            selected_users: selectedUsers || []
                        },
                        success: function(html) {
                            $('#multiselect_align_to').html(html);
                            $('#multiselect_align_to').multiselect('rebuild');
                        },
                        error: function() {
                            $('#multiselect_align_to').html('<option value="">---Select---</option>');
                            $('#multiselect_align_to').multiselect('rebuild');
                        }
                    });                    
                }

                $('#multiselect_assigned_partner').on('change', function() {
                    loadAlignToFilterUsers([]);
                });

                loadAlignToFilterUsers($('#multiselect_align_to').val() || []);
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


function showAjaxLoader(message) {
    $('#globalAjaxLoaderText').text(message || 'Please wait...');
    $('#globalAjaxLoader').css('display', 'flex');
}

function hideAjaxLoader() {
    $('#globalAjaxLoader').hide();
}

function finishAfterMinLoader(startTime, done) {
    var minLoaderDurationMs = 2500;
    var elapsed = Date.now() - startTime;
    var wait = Math.max(0, minLoaderDurationMs - elapsed);
    setTimeout(done, wait);
}

function updateApproval(element) {

    var id = element.getAttribute('data-id');
    var status = element.checked ? 1 : 0;

    var previousState = !element.checked;

    swal({
        title: "Are you sure?",
        text: "Do you want to change the approval status?",
        type: "warning",
        cancelButtonText: "No",
        showCancelButton: true,
        confirmButtonText: "Yes, Update",
        confirmButtonColor: "#28a745",
        closeOnConfirm: true
    }, function (isConfirm) {

        if (!isConfirm) {
            element.checked = previousState;
            return;
        }

        if (typeof swal.close === 'function') {
            swal.close();
        }

        var loaderStart = Date.now();
        showAjaxLoader('Updating approval status, please wait...');

        $.ajax({
            url: "ajax_common.php",
            type: "POST",
            dataType: "json",
            data: {
                leads_approval: '1',
                id: id,
                status: status
            },
            success: function (response) {
                finishAfterMinLoader(loaderStart, function() {
                    hideAjaxLoader();
                    if (response.status === "success") {
                        swal("Success!", response.message, "success");
                    } else {
                        swal("Error!", response.message || "Update failed", "error");
                        element.checked = previousState; // revert on failure
                    }
                    $('#leads').DataTable().ajax.reload(null, false);
                });
            },
            error: function () {
                finishAfterMinLoader(loaderStart, function() {
                    hideAjaxLoader();
                    swal("Error!", "Server error occurred.", "error");
                    element.checked = previousState; // revert on error
                });
            }
        });
    });
}


$(document).on('change', '.approval-select', function() {
    var $sel = $(this);
    var id = $sel.data('id');
    var status = $sel.val();
    var previousState = $sel.data('prev');
    var isApproveSelection = (status === '1' || status === 1);
    var confirmText = "Do you want to change the approval status?";
    var isHtmlText = false;
    if (isApproveSelection) {
        confirmText = 'Do you want to change the approval status?<br><br><span class="approval-note-red">Note: After approve status you cannot change it again.</span>';
        isHtmlText = true;
    }

    function applyStyle($el, val) {
        // Ensure select is wrapped
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
        text: confirmText,
        html: isHtmlText,
        type: "warning",
        cancelButtonText: "No",
        showCancelButton: true,
        confirmButtonText: "Yes, Update",
        confirmButtonColor: "#28a745",
        closeOnConfirm: true
    }, function(isConfirm) {
        if (!isConfirm) {
            $sel.val(previousState);
            applyStyle($sel, previousState);
            return;
        }

        if (typeof swal.close === 'function') {
            swal.close();
        }

        var loaderStart = Date.now();
        showAjaxLoader('Updating approval status, please wait...');

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
                finishAfterMinLoader(loaderStart, function() {
                    hideAjaxLoader();
                    if (response.status === "success") {
                        swal("Success!", response.message, "success");
                        $sel.data('prev', status);
                    } else {
                        swal("Error!", response.message || "Update failed", "error");
                        $sel.val(previousState);
                    }
                    applyStyle($sel, $sel.val());
                    $('#leads').DataTable().ajax.reload(null, false);
                });
            },
            error: function() {
                finishAfterMinLoader(loaderStart, function() {
                    hideAjaxLoader();
                    swal("Error!", "Server error occurred.", "error");
                    $sel.val(previousState);
                    applyStyle($sel, previousState);
                });
            }
        });
    });
});

// Keep track of previous value when user focuses the select
$(document).on('focusin', '.approval-select', function() {
    var $s = $(this);
    $s.data('prev', $s.val());
});

// When table draws, normalize selects: add classes, styles and set initial state
    $('#leads').on('draw.dt', function() {
        $('#leads').find('select').each(function() {
            var $s = $(this);
            if (!$s.hasClass('approval-select')) $s.addClass('approval-select');
            $s.addClass('form-control form-control-sm');
            // wrap with indicator if not already
            if ($s.closest('.approval-wrapper').length === 0) {
                $s.wrap('<span class="approval-wrapper"></span>');
                $s.closest('.approval-wrapper').prepend('<i class="approval-indicator"></i>');
            }
            // set prev data attribute
            $s.data('prev', $s.val());
            // apply wrapper classes for current value
            var val = $s.val();
            var $wrap = $s.closest('.approval-wrapper');
            $wrap.removeClass('pending approved rejected onboard');
            if (val === '1' || val === 1) $wrap.addClass('approved');
            else if (val === '2' || val === 2) $wrap.addClass('rejected');
            else if (val === '3' || val === 3) $wrap.addClass('onboard');
            else $wrap.addClass('pending');
        });
    });


        </script>