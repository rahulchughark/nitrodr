<?php 

$getIDs = $_GET['ids'];
if (empty($getIDs)) {
    // header("Location: kra_daily_report.php");
    header("Location: 404.php"); // Adjust path if your 404 file is elsewhere
    
    exit;
}

include('includes/header.php');
//print_r($_SESSION['user_id']);die;
// if ($_SESSION['user_type'] != 'REVIEWER') admin_page();
ini_set('max_execution_time', 0);
// echo "<br><br><br><br>";
if ($_POST['save_csv']) {
    $filename = $_FILES["file"]["tmp_name"];
    if ($_FILES["file"]["size"] > 0) {
        $row = 1;
        $file = fopen($filename, "r");
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
            if ($row != 1) {                
                $r_nameI = $getData[0];
                $r_emailI = $getData[1];
                $r_userI = $getData[2];
                $lead_statusI = $getData[3];
                $sourceI = $getData[4];
                $sub_lead_sourceI = $getData[5];
                $billing_resellerI = getSingleresult("select id from partners where name='".trim($getData[6])."'");
                $credit_resellerI = getSingleresult("select id from partners where name='".trim($getData[7])."'");
                $school_nameI = $getData[8];
                $is_groupI = $getData[9];
                $group_nameI = $getData[10];
                $spocI = $getData[11];
                $addressI = str_replace("'", "", $getData[12]);
                
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
                // $productTypeI = $getData[65];
                
                $programStartDate = $getData[38] ? date("Y-m-d", strtotime($getData[38])) : '';
                $academicStartDate = $getData[39] ? date("Y-m-d", strtotime($getData[39])) : '';
                $academicEndDate = $getData[40] ? date("Y-m-d", strtotime($getData[40])) : '';
                $applicationDate = $getData[44] ? date("Y-m-d", strtotime($getData[44])) : '';
                $expectedCloseDate = $getData[69] ? date("Y-m-d", strtotime($getData[69])) : '';
                
                $program_date = new DateTime($getData[70]);
                $comparison_date = new DateTime('2024-04-01');

                // if ($program_date < $comparison_date) {
                //     $programInitationDate = '2024-04-01';
                // } else {
                //     $programInitationDate = date("Y-m-d", strtotime($getData[70]));
                // }                
                
                $productCodes = explode("|", $getData[66]);
                $productQuantities = explode("|", $getData[67]);
                $productRates = explode("|", $getData[72]);

                $recordType = $getData[63];
                $poReceive = $getData[71];

                if ((count($productCodes) == count($productQuantities))) {
                    
                    $l = 0;
                    foreach ($productCodes as $arrayvalue) {
                        $check = getSingleresult("select COUNT(*) AS count from tbl_product_opportunity where product_code='".trim($arrayvalue)."'");
                        if($check == 0){
                            echo "<script type=\"text/javascript\">
                            alert(\"Please check products code at row no " . $row . " and try again with same and next rows data.\");
                            window.location = \"manage_opportunity.php\"
                            </script>";
                            $check = 0;
                            exit;
                        }else{
                            $productArray[$l] = array($productCodes[$l], $productQuantities[$l],$productRates[$l]);
                            $l++;
                        }
                    }
                } else {                    
                    echo "<script type=\"text/javascript\">
                    alert(\"Please check products count at row no " . $row . " and try again with same and next rows data.\");
                    window.location = \"manage_opportunity.php\"
                    </script>";
                }
                
                
                $sql = "INSERT INTO orders (r_name, r_email, r_user, lead_status, status, source, sub_lead_source, billing_reseller,credit_reseller, school_name, is_group, group_name, spoc, address, state, city, region,country, pincode, contact, website, school_email, annual_fees, eu_name, eu_mobile, eu_email,eu_person_name1, eu_designation1, eu_mobile1, eu_email1, eu_person_name2, eu_mobile2, eu_email2,adm_name, adm_designation, adm_email, adm_mobile, adm_alt_mobile, school_board, program_start_date,academic_start_date, academic_end_date, grade_signed_up, quantity, purchase_no, application_date,purchase_deails, license_period, is_app_erp, ip_address, labs_count, system_count, os, student_system_ratio,lab_teacher_ratio, standalone_pc, projector, tv, smart_board, internet, networking, thin_client, n_computing,created_by,created_date,agreement_type,team_id,data_ref,record_type,po_receive) VALUES ('".$r_nameI."','".$r_emailI."','".$r_userI."','".$lead_statusI."','Pending','".$sourceI."','".$sub_lead_sourceI."','".$billing_resellerI."','".$credit_resellerI."','".$school_nameI."','".$is_groupI."','".$group_nameI."','".$spocI."','".$addressI."','".$stateI."','".$cityI."','".$regionI."','".$countryI."','".$pincodeI."','".$contactI."','".$websiteI."','".$school_emailI."','".$annual_feesI."','".$eu_nameI."','".$eu_mobileI."','".$eu_emailI."','".$eu_person_name1I."','".$eu_designation1I."','".$eu_mobile1I."','".$eu_email1I."','".$eu_person_name2I."','".$eu_mobile2I."','".$eu_email2I."','".$adm_nameI."','".$adm_designationI."','".$adm_emailI."','".$adm_mobileI."','".$adm_alt_mobileI."','".$school_boardI."','".$programStartDate."','".$academicStartDate."','".$academicEndDate."','".$grade_signed_upI."','".$quantityI."','".$purchase_noI."','".$applicationDate."','".$purchase_deailsI."','".$license_periodI."','".$is_app_erpI."','".$ip_addressI."','".$labs_countI."','".$system_countI."','".$osI."','".$student_system_ratioI."','".$lab_teacher_ratioI."','".$standalone_pcI."','".$projectorI."','".$tvI."','".$smart_boardI."','".$internetI."','".$networkingI."','".$thin_clientI."','".$n_computingI."','".$created_byI."',now(),'".$agreement_typeI."','".$team_idI."','Excel','".$recordType."','".$poReceive."')";
                
                $result = db_query($sql);
                $lead_id = get_insert_id();
                
                if ($lead_id > 0) {

                    $producQuery =  db_query("INSERT INTO `tbl_lead_product`(`lead_id`, `product_id`,`product_type_id`,`form_id`,`created_at`) VALUES ('" . $lead_id . "' ,'2' ,'5','0',now())");

                    $grandTotal = 0;
                    foreach ($productArray as $productValue) {
                        $totalPrice = $productValue[2]*$productValue[1];
                        $grandTotal = $grandTotal+$totalPrice;
                        $productId = getSingleresult("select id from tbl_product_opportunity where product_code='".trim($productValue[0])."'");
                            $sql3 = "INSERT INTO tbl_lead_product_opportunity(`lead_id`, `product`,`unit_price`,`quantity`,`total_price`) VALUES ('" . $lead_id . "','" . $productId . "','" . $productValue[2] . "','" . $productValue[1] . "','" . $totalPrice . "')";
                            $result3 = db_query($sql3);
                    }
                    $inss = db_query("UPDATE orders SET is_opportunity = 1, stage = 'PO/CIF Issued', add_comm = 'Advance Payment Received', expected_close_date = '".$expectedCloseDate."', grand_total_price = '".$grandTotal."' , opportunity_by = '".$created_byI."' WHERE id =".$lead_id);

                    $resL =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$lead_id."','Stage','N/A','PO/CIF Issued',now(),'".$_SESSION['user_id']."')");

                    unset($productArray);
                }
            }
            $row++;
        }
        echo "<script type=\"text/javascript\">
          alert(\"CSV File has been successfully Imported.\");
          window.location = \"manage_opportunity.php\"
        </script>";
        fclose($file);
    } else {
        echo "<script type=\"text/javascript\">
          alert(\"An error occured while uploading file.\");
          window.location = \"manage_opportunity.php\"
        </script>";
    }
}
?>

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
                                <div class="col">
                                    <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">

                                            <small class="text-muted">Home > Opportunity </small>
                                            <h4 class="font-size-14 m-0 mt-1">Opportunity</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div role="group">
                                    <?php if ($_SESSION['user_type'] == 'ADMIN') { ?>
                                    <!-- <a href="javascript:void(0);" onclick="show_import()"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="Import Opportunity" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-plus "></i></button></a> -->
                                    <?php } ?>
                                    <?php if($_SESSION['download_status'] == 1){ 
                                            $stageStr = $stage ? implode("','",$stage) : '';
                                            $substageStr = $substage ? implode("','",$substage) : '';
                                            $partnerStr = $partner ? implode(",",$partner) : '';
                                            $usersStr = $users ? implode(",",$users) : '';
                                            $stateStr = $state ? implode(",",$state) : '';
                                            $tagStr = $tag ? implode(",",$tag) : '';
                                            $statusStr = $status ? implode(",",$status) : '';
                                            $lead_status_str = $lead_status ? implode(",",$lead_status) : '';
                                            $source_str = $source ? implode(",",$source) : '';
                                            
                                            ?>
                                        <a href="export_admin_opportunity.php?d_from=<?= @$_GET['d_from'] ?>&d_to=<?= @$_GET['d_to'] ?>&d_type=<?= @$_GET['dtype'] ?>&license='Fresh'&stage=<?= $stageStr ?>&substage=<?= $substageStr ?>&partner=<?= $partnerStr ?>&users=<?= $usersStr ?>&tag=<?= $tagStr ?>&status=<?= $statusStr ?>&lead_status=<?= $lead_status_str ?>&state=<?= $stateStr ?>&source=<?= $source_str ?>">
                                            <button title="Excel Export" class="btn btn-xs btn-light ml-1"><i class="ti-share"></i>
                                            </button>
                                        </a>
                                        <?php } ?>
                                        <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                        <div class="dropdown dropdown-lg">
                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">
                                                <form method="get" name="search" class="form-horizontal" role="form">

                                                    <?php if (!is_array($partner)) {
                                                        $val = $partner;
                                                        $partner = array();
                                                        $partner['0'] = $val;
                                                    }
                                                    if ($_SESSION['sales_manager'] != 1) {
                                                        $res = db_query("select * from partners where status='Active'");
                                                    } else {
                                                        $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active'");
                                                    }
                                                    ?>
                                                
                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <select name="dtype" class="form-control" id="date_type">
                                                            <option value="">Select Date Type</option>
                                                            <option <?= (($_GET['dtype'] == 'created') ? 'selected' : '') ?> value="created">Created Date</option>
                                                                    <option <?= (($_GET['dtype'] == 'actioned_date') ? 'selected' : '') ?> value="actioned_date">Actioned Date</option>
                                                                    <option <?= (($_GET['dtype'] == 'close') ? 'selected' : '') ?> value="close">Close Date</option>
                                                                    <option <?= (($_GET['dtype'] == 'stage') ? 'selected' : '') ?> value="stage">Stage Date</option>
                                                                    <option <?= (($_GET['dtype'] == 'lead_status') ? 'selected' : '') ?> value="lead_status">Lead Status Change</option>
                                                                    <option <?= (($_GET['dtype'] == 'sub_stage') ? 'selected' : '') ?> value="sub_stage">Sub Stage</option>
                                                                    <option <?= (($_GET['dtype'] == 'opportunity_converted') ? 'selected' : '') ?> value="opportunity_converted">Opportunity Converted</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <div class="input-daterange input-group" id="datepicker-close-date">
                                                                <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                                <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                            </div>
                                                        </div>
                                                    <?php if($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN') { ?>
                                                        <div class="form-group col-md-3">
                                                            <select name="partner[]" id="partner" class="multiselect_partner form-control" data-live-search="true" multiple>
                                                                <?php while ($row = db_fetch_array($res)) { ?>
                                                                    <option <?= (in_array($row['id'], $partner) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>

                                                    <?php } ?>


                                                    <div class="form-group col-md-3">
                                                            <?php if ($_GET['partner']) { ?>

                                                                <select name="users[]" class="multiselect_user1 form-control" data-live-search="true" multiple>

                                                                    <?php $query = db_query('SELECT * FROM users WHERE team_id in ("' . implode('", "', $_GET['partner']) . '") and status="Active"  ORDER BY name ASC');
                                                                    while ($row = db_fetch_array($query)) { ?>
                                                                        <option <?= (@in_array($row['id'], $users) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                                                    <?php } ?>
                                                                </select>

                                                            <?php } else { ?>
                                                                <div id="users">
                                                                </div>
                                                            <?php } ?>
                                                        </div>

                                                        <?php
                                                                $sqlStage = "select * from stages where stage_name not in ('PO/CIF Issued','Billing')";
                                                                $stageList = db_query($sqlStage);
                                                                ?>

                                                                <?php if (!is_array($stage)) {
                                                                    $val = $stage;
                                                                    $stage = array();
                                                                    $stage['0'] = $val;
                                                                    $st_flag = 1;
                                                                } ?>
                                                        <div class="form-group col-md-3">
                                                        <select name="stage[]" data-live-search="true" multiple class="form-control" id="multiselect">
                                                            <?php while ($stag = db_fetch_array($stageList)) { ?>
                                                                <option value="<?= $stag['stage_name'] ?>" <?= (in_array($stag['stage_name'], $stage) ? 'selected' : '') ?>><?= $stag['stage_name'] ?></option>
                                                            <?php } ?>
                                                            </select>
                                                            </div>
                                                        <div class="form-group col-md-3" id="sub_stageD">
                                                            <?php if ($_GET['stage']) { ?>

                                                                <select name="sub_stage[]" class="multiselect_sub_stage form-control" data-live-search="true" multiple>

                                                                    <?php $query = db_query('select * from sub_stage where stage_name IN ("' . implode('", "', $stage) . '")');
                                                                    while ($row = db_fetch_array($query)) { ?>
                                                                        <option <?= (@in_array($row['name'], $sub_stage) ? 'selected' : '') ?> value="<?= $row['name'] ?>"><?= ucwords($row['name']) ?></option>
                                                                    <?php } ?>
                                                                </select>

                                                            <?php } else { ?>
                                                                <div id="sub_stageD">
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <select name="lead_status[]" data-live-search="true" multiple class="form-control" id="multiselectleadstatus">
                                                                <option <?= (@in_array('Raw Data', $lead_status ?? []) ? 'selected' : '') ?> value="Raw Data">Raw Data</option>
                                                                <option <?= (@in_array('Validation', $lead_status ?? []) ? 'selected' : '') ?> value="Validation">Validation</option>
                                                                <option <?= (@in_array('Contacted', $lead_status ?? []) ? 'selected' : '') ?> value="Contacted">Contacted</option>
                                                                <option <?= (@in_array('Qualified', $lead_status ?? []) ? 'selected' : '') ?> value="Qualified">Qualified</option>
                                                                <option <?= (@in_array('Unqualified', $lead_status ?? []) ? 'selected' : '') ?> value="Unqualified">Unqualified</option>
                                                                <option <?= (@in_array('Duplicate', $lead_status ?? []) ? 'selected' : '') ?> value="Duplicate">Duplicate</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-md-3">
                                                            <select name="status[]" data-live-search="true" multiple class="form-control" id="multiselectleadqualifiedstatus">
                                                            <!-- <option value="">---Select---</option> -->
                                                            <option <?= (@in_array('Undervalidation', $status ?? []) ? 'selected' : '') ?> value="Undervalidation">Re-Submission Required</option>
                                                            <option <?= (@in_array('Approved', $status ?? []) ? 'selected' : '') ?> value="Approved">Qualified</option>
                                                            <option <?= (@in_array('Cancelled', $status ?? []) ? 'selected' : '') ?> value="Cancelled">Unqualified</option>
                                                            <option <?= (@in_array('On-Hold', $status ?? []) ? 'selected' : '') ?> value="On-Hold">On-Hold</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-md-3">
                                                    <select name="school_board[]" id="multiselect_school_board" class="form-control" multiple>
                                                    <option <?= (@in_array('CBSE', $school_board ?? []) ? 'selected' : '') ?> value="CBSE">CBSE</option>
                                                    <option <?= (@in_array('ICSE', $school_board ?? []) ? 'selected' : '') ?> value="ICSE">ICSE</option>
                                                    <option <?= (@in_array('IB', $school_board ?? []) ? 'selected' : '') ?> value="IB">IB</option>
                                                    <option <?= (@in_array('IGCSE', $school_board ?? []) ? 'selected' : '') ?> value="IGCSE">IGCSE</option>
                                                    <option <?= (@in_array('STATE', $school_board ?? []) ? 'selected' : '') ?> value="STATE">STATE</option>
                                                    <option <?= (@in_array('Others', $school_board ?? []) ? 'selected' : '') ?> value="Others">Others</option>
                                                    </select>
                                                        </div>

                                                        <div class="form-group col-md-3">
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
                                                        <div class="form-group col-md-3">
                                                        <select name="tag[]" data-live-search="true" multiple class="form-control" id="multiselecttag">
                                                            <?php while ($tags = db_fetch_array($tagList)) { ?>
                                                                <option value="<?= $tags['id'] ?>" <?= (@in_array($tags['id'], $tag) ? 'selected' : '') ?>><?= $tags['name'] ?></option>
                                                            <?php } ?>
                                                            </select>
                                                            </div>

                                                            <div class="form-group col-md-3">
                                                            <select name="state[]" class="form-control" id="multiselect_state" multiple>
                                                                <?php $ress = db_query("select * from states");
                                                                while ($row = db_fetch_array($ress)) { ?>
                                                                    <option <?= (@in_array($row['id'], $state ?? []) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                <?php  } ?>

                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3" id="cityD">
                                                            <?php if ($_GET['state']) { ?>

                                                                <select name="city[]" class="multiselect_city form-control" data-live-search="true" multiple>

                                                                    <?php 
                                                                    // print_r($state);die;
                                                                    $query = db_query("SELECT * FROM cities where state_id  IN (" . implode(",", $state) . ")");
                                                                    while ($row = db_fetch_array($query)) { ?>
                                                                        <option <?= (@in_array($row['id'], $city) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= $row['city'] ?></option>
                                                                    <?php } ?>
                                                                </select>

                                                            <?php } else { ?>
                                                                <div id="cityD">
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
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

                            
                            <div class="table-responsive" id="MyDiv">

                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                            <tr>
                                            <th>S.No.</th>
                                                <th>Reseller Name</th>
                                                <th>School Board</th>
                                                <th>School Name</th>
                                                <th>City</th>
                                                <th>State</th>
                                                <th style="min-width: 300px">Product</th>
                                                <th>Quantity</th>
                                                <th>Grand Total</th>
                                                <th>Date of Submission</th>
											    <th>Status</th>
											    <th>Qualified Status</th>
											    <th>Stage</th>
                                                <th>Sub Stage</th>
											    <th>Demo Arranged</th>
											    <th>Demo Completed</th>
											    <th>Proposal Shared</th>
											    <th>Demo Login</th>
											    <th>DL + PS</th>
											    <th>Closed Date</th>
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
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->

        <?php include('includes/footer.php') ?>
        <script>
            $('#leads').DataTable({
                dom: 'Bfrtip',
                "displayLength": 15,
                language: {
                    paginate: {
                        previous: '<i class="fas fa-arrow-left"></i>',
                        next: '<i class="fas fa-arrow-right"></i>'
                    }
                },
                language: {
                    paginate: {
                        previous: '<i class="fas fa-arrow-left"></i>',
                        next: '<i class="fas fa-arrow-right"></i>'
                    }
                },
                buttons: [
                    <?php if($_SESSION['download_status'] == 1){ ?>
                    'copy', 'csv', 'excel', 'pdf',  'print', 'pageLength',
                    
                <?php }else{ ?> 'pageLength'  <?php } ?>
                    
                ],
                lengthMenu: [
                    [5, 15, 25, 50, 100, 500, 1000],
                    ['5', '15', '25', '50', '100', '500', '1000']
                ],
                "stateSave": true,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "filtered_get_opportunity.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                    d.request_lead_id= "<?= $getIDs?>";                       
                    d.d_from = "<?= $_GET['d_from'] ?>";
                    d.d_to = "<?= $_GET['d_to'] ?>";
                    d.d_type = "<?= $_GET['dtype'] ?>";
                    d.type = "<?= $_GET['type'] ?>";
                    d.sub_product = '<?= json_encode($_GET['sub_product']) ?>';
                    d.stage = '<?= json_encode($_GET['stage']) ?>';
                    d.sub_stage = '<?= json_encode($_GET['sub_stage']) ?>';
                    d.users = '<?= json_encode($_GET['users']) ?>';
                    d.quantity = '<?= json_encode($_GET['quantity']) ?>';
                    d.state = '<?= json_encode($_GET['state']) ?>';
                    d.city = '<?= json_encode($_GET['city']) ?>';
                    d.school_board = '<?= json_encode($_GET['school_board']) ?>';
                    d.tag = '<?= json_encode($_GET['tag']) ?>';
                    d.lead_status = '<?= json_encode($_GET['lead_status']) ?>';
                    d.status = '<?= json_encode($_GET['status']) ?>';
                    d.partner = '<?= json_encode($_GET['partner']) ?>';
                    d.source = '<?= json_encode($_GET['source']) ?>';
                    d.users = '<?= json_encode($_GET['users']) ?>';
                    d.product_typeDS = '<?= json_encode($_GET['product_typeDS']) ?>';
                    d.productDS = '<?= json_encode($_GET['productDS']) ?>';
                    d.product_opp = '<?= json_encode($_GET['product_opp']) ?>';
                    d.product_opp_type = '<?= json_encode($_GET['product_opp_type']) ?>';
                    d.type = '<?= json_encode($_GET['type']) ?>';
                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="14">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");
                    }
                },
                "order": [
                    [7, "desc"]
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],

                'columns': [
                    { data: 'id' },
                 { data: 'r_name' },
                 {data:'school_board'},
                 { data: 'school_name' },
                 { data: 'city' },
                 { data: 'state'},
                 { data: 'product' },
                 { data: 'quantity' },
                 { data: 'grand_total' },
                   {data:'created_date'},
                  {data:'status', className: 'text-nowrap'},
                  {data:'qualified_status'},
                  {data:'stage', className: 'text-nowrap'},
                  {data:'sub_stage'},
                  {data:'demo_arranged'},
                  {data:'demo_completed'},
                  {data:'proposal_shared'},
                  {data:'demo_login'},
                  {data:'demo_login+proposal_shared'},                  
                  {data:'close_date'},
                   
                
              ]
            });

            $(document).ready(function() {
                $('.multiselect_partner').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Partner',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    maxHeight: 150
                });
                $('.multiselect_status').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Status',
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
                $('.multiselect_sub_stage').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Sub Stage',
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
                $('#multiselectleadstatus').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Lead Status',
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
                
                $('#multiselect_state').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select State',
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
                $('#multiselect_school_board').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select School Board',
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
                $('#multiselecttag').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Tag',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
            });


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


            function clear_search() {
                window.location = 'manage_opportunity.php';
            }

            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });

            function chage_stage(stage, id) {
                if (stage != '') {
                    $.ajax({
                        type: 'post',
                        url: 'change_stage.php',
                        data: {
                            stage: stage,
                            lead_id: id
                        },
                        success: function(res) {
                            var res = $.trim(res);
                            if (res == 'success') {
                                swal({
                                    title: "Done!",
                                    text: "Stage changed Successfully.",
                                    type: "success"
                                }, function() {
                                    //window.location = "manage_orders.php";
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
                                    location.reload();
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
        </script>

        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 310);
                $("#leads").tableHeadFixer();

            });

            function convertDate(dateString) {
                var p = dateString.split(/\D/g)
                return [p[2], p[1], p[0]].join("-")
            }

            function show_import() {
                $.ajax({
                    type: 'POST',
                    url: 'import_leads.php',
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });

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
            $(document).ready(function() {
                $('#multiselect').on('change', function() {

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


        function demo_sub_stage_date(id,subStageID){
            $.ajax({
                            type: 'POST',
                            url: 'demo_sub_stage_date.php',
                            data: {
                                id: id,
                                subStageID:subStageID
                            },
                            success: function(response) {
                                $("#myModal1").html();
                                $("#myModal1").html(response);

                                $('#myModal1').modal('show');
                                $('.preloader').hide();
                            }
                        });            
        }


        function cd_change(ids, id) {
                        //$('.preloader').show();
                        // alert('hii')
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
                            var res = $.trim(res);
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

            function update_sub_stage_timestamps(logID,currentSubStage,previousSubStage,logDate) {
               
                if (logID != '') {
                    $('#myModal1').modal('hide');
                    $.ajax({
                        type: 'post',
                        url: 'demo_sub_stage_date.php',
                        data: {
                            logID: logID,
                            currentSubStage: currentSubStage,
                            previousSubStage: previousSubStage,
                            logDate: logDate
                        },
                        success: function(res) {
                            var res = $.trim(res);
                            if (res == 'success') {
                                swal({
                                    title: "Done!",
                                    text: "Close Date changed Successfully.",
                                    type: "success"
                                }, function() {
                                    $('#myModal1').modal('hide');
                                    // var ids2 = "'but2" + logID + "'";                                    
                                    // var newDate = convertDate(cd_date);
                                    // var link = newDate + '<a href="javascript:void(0)" title="Change Close Date" id=but2' + id + ' onclick="cd_change(' + ids2 + ',' + logID + ')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>'
                                    // $("#" + ids).parent().html(link);
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



        </script>