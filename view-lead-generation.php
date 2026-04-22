<?php
include('includes/header.php');?>
<?php

        $sql=db_query("select * from lead_generation where id=".$_REQUEST['id']);
        $data=db_fetch_array($sql);
        @extract($data);
        //print_r($data);die;
        $licType = $license_type;


        $_REQUEST['id'] = intval($_REQUEST['id']);

        $sqll = db_query("select o.* from lead_generation as o where o.id='" . $_REQUEST['id'] . "'");
        
        $row_data = db_fetch_array($sqll);
        
        if($sql->num_rows == 0)
        {
            redir("manage_orders.php?m=nodata", true);
        }
        
        if ($_POST['caller']) {
            if ($row_data['caller'] != $_POST['caller']) {
                $modify_name = getSingleresult("select name from callers where id='" . $_POST['caller'] . "' ");
                $caller_prev = getSingleresult("select name from callers where id='" . $row_data['caller'] . "'");
        
                $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_REQUEST['id']."','Caller','". $caller_prev."','". $modify_name."',now(),'".$_SESSION['user_id']."')");
                $sqlc=db_query("update orders set  caller='".$_POST['caller']."'  where id=".$_REQUEST['id']);
        
            }
        }
                
        if (!empty($_FILES["po_re_attachment"]["name"][0])) {
            $target_dir = "uploads/";
            $f = 1;
            foreach ($_FILES['po_re_attachment']['name'] as $key => $value) {
                $file_extension = pathinfo(basename($_FILES['po_re_attachment']['name'][$key]), PATHINFO_EXTENSION);
                $target_file = $target_dir .$f.'_'. time().".".$file_extension;        

                if (move_uploaded_file($_FILES['po_re_attachment']['tmp_name'][$key], $target_file)) {
                    $uploaded_files[] = $target_file;
                } else {
                    echo 'error';
                    exit;
                }
                $f++;
            }
            $file_names = implode(',', $uploaded_files);
            
            $reattach=db_query("update orders set po_attachment='".$file_names."',po_attachment_updated_by=".$_SESSION['user_id']."  where id=".$_REQUEST['id']);

        }
        if ($_POST['product_code']) {
            // print_r($_POST);die;
            if (!empty($_FILES["user_attachment"]["name"])) {
                $target_dir = "uploads/";
                $target_file = $target_dir . time() . basename($_FILES["user_attachment"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
                if ($_FILES["user_attachment"]["size"] > 4000000) {
                    echo "<script>alert('Sorry, your file is too large!')</script>";
                    redir("manage_orders.php", true);
                } else {
                    move_uploaded_file($_FILES["user_attachment"]["tmp_name"], $target_file);
                }
            }else{
                $target_file = '';
            }

            $number = count($_POST["product_code"]);
            for ($i = 0; $i < $number; $i++) {
                $res =  db_query("INSERT INTO tbl_lead_product_opportunity(`lead_id`, `product`,`unit_price`,`quantity`,`total_price`) VALUES ('" . $_GET['id'] . "','" . $_POST['product_code'][$i] . "','" . $_POST['sales_price'][$i] . "','" . $_POST['quantity'][$i] . "','" . $_POST['total_price'][$i] . "')");
            }

            // $created_att = date("Y-m-d H:i:s");
            // $form_id = getSingleresult("select form_id from tbl_product_pivot where id=".$_POST['product_type']);
            // $lead_id = $_GET['id'];

            $inss = db_query("UPDATE orders SET is_opportunity = 1, stage = '".$_POST['opportunity_stage']."', expected_close_date = '".$_POST['close_date']."', user_attachement = '".$target_file."', grand_total_price = '".$_POST['grand_total']."' , opportunity_by = '".$_SESSION['user_id']."' WHERE id =".$_POST['pid']);
            if ($inss) {
                redir("manage_opportunity.php?update=success", true);
            }
        }
                
        if (isset($_POST['submit']) && $_SESSION['user_type'] != 'REVIEWER') {
        
        
            /*
            mail to caller in case status is cancelled
            */

            $add_comment = $_POST['add_comment'] ? $_POST['add_comment'] : $row_data['add_comment'];
            $caller = $_POST['caller'] ? $_POST['caller'] : $row_data['caller'];
            if($row_data['admin_attachment'] == ''){
                $fileAtt = $row_data['user_attachement'];
            }else{
                $fileAtt = $row_data['admin_attachment'];
            }
            $attachement = !empty($_FILES["admin_attachment"]["name"]) ? $target_file : $fileAtt;
            $expected_close_date = $_POST['expected_close_date'] ? $_POST['expected_close_date'] : $row_data['expected_close_date'];
        
            $sql =  db_query("update orders set caller='" . $caller . "',admin_attachment='" . $attachement . "',expected_close_date='" . $expected_close_date . "' where id=" . $_REQUEST['id']);
            //print_r($sql);die;
        
            if ($sql) {
        
                redir("manage_orders.php?update=success", true);
            }
        }
        
        
        if ($_POST['remarks'] && !$_POST['activity_edit']) {        
            $res = db_query("insert into activity_log(`pid`,description,`activity_type`,`call_subject`,`added_by`,`action_plan`,data_ref)values('".$_POST['pid']."','".htmlspecialchars($_POST['remarks'], ENT_QUOTES)."','Lead','". $_POST['call_subject']."',".$_SESSION['user_id'].",'".$_POST['action_plan']."',1)");
       
        
            $emaildata = db_query("select r_email,r_name,r_user,school_name,eu_email,eu_mobile,team_id,quantity,expected_close_date,caller from orders where id=" . intval($_POST['pid']));
            $dataAll = db_fetch_array($emaildata);

            $addCc[] = $data['r_email'];
            $addCc[] = $_SESSION['email'];

            $manager_email=db_query("select email from users where user_type='MNGR' and team_id=" . $data['team_id']);

            while($me=db_fetch_array($manager_email))
            {
                $addTo[] = $me['email'];
            }
     
            // $addTo[] = ("pradeep.chahal@arkinfo.in");
            // $addCc[] = ("virendra.kumar@arkinfo.in"); 

            $setSubject = ($dataAll['school_name'] . " - New Log a Call");
            $body    = "Hello,<br><br> There is new log a call from " . $_SESSION['name'] . " on ICT DR Portal with details as below:-<br><br>
            <ul>
            <li><b>Partner Name</b> : " . $dataAll['r_name'] . " </li>
            <li><b>Organization Name</b> : " . $dataAll['school_name'] . " </li>
            <li><b>Contact Number</b> : ". $dataAll['eu_mobile'] ." </li>
            <li><b>Email ID</b> : ". $dataAll['eu_email'] ." </li>
            <li><b>Call Subject</b> : " . htmlspecialchars($_POST['call_subject'], ENT_QUOTES) . " </li>
            <li><b>POA Subject</b> : " . htmlspecialchars($_POST['action_plan'], ENT_QUOTES) . " </li>
            <li><b>Description</b> : " . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . " </li>
            <li><b>Quantity</b> : " . $dataAll['quantity'] . " </li>
            <li><b>Expected Close Date</b> : " . $dataAll['expected_close_date'] . " </li></ul><br>
            Thanks,<br>
            ICT DR Portal
            ";
            $addBcc[] = '';
            sendMail($addTo, $addCc, $addBcc, $setSubject, $body ,$attachment);
        }
        
        if ($_POST['activity_edit']) {
            $res = db_query("update activity_log set description='" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "',call_subject='" . $_POST['call_subject'] . "',action_plan='" . $_POST['action_plan'] . "' where id=" . $_POST['pid']);
        }

        // $checkRequired = getSingleResult("SELECT CASE WHEN lead_status IS NULL OR lead_status = '' THEN 'False' WHEN source IS NULL OR source = '' THEN 'False' WHEN billing_reseller IS NULL OR billing_reseller = '' THEN 'False' WHEN credit_reseller IS NULL OR credit_reseller = '' THEN 'False' WHEN school_name IS NULL OR school_name = '' THEN 'False' WHEN address IS NULL OR address = '' THEN 'False' WHEN state IS NULL OR state = '' THEN 'False' WHEN city IS NULL OR city = '' THEN 'False' WHEN region IS NULL OR region = '' THEN 'False' WHEN pincode IS NULL OR pincode = '' THEN 'False' WHEN contact IS NULL OR contact = '' THEN 'False' WHEN website IS NULL OR website = '' THEN 'False' WHEN school_email IS NULL OR school_email = '' THEN 'False' WHEN annual_fees IS NULL OR annual_fees = '' THEN 'False' WHEN eu_name IS NULL OR eu_name = '' THEN 'False' WHEN eu_mobile IS NULL OR eu_mobile = '' THEN 'False' WHEN eu_email IS NULL OR eu_email = '' THEN 'False' ELSE 'True' END AS status FROM orders WHERE id=".$_GET['id']);        
        // ?>

<!-- Page wrapper  -->
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

                                    <small class="text-muted">Home > View Lead Gen</small>
                                    <h4 class="font-size-14 m-0 mt-1">View Lead Gen</h4>
                                </div>

                            </div>

                            <div class="clearfix"></div>

                            <div data-simplebar class="add_lead">

                                <div class="accordion" id="accordionExample2">
                                    
                                    <div class="card mb-0 pt-2 shadow-none">

                                        <div class="card-header" id="headingOne3">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne3" aria-expanded="true" aria-controls="collapseOne3">
                                                    School Detail 
                                                </button>
                                            </h5>
                                        </div>


                                        <div id="collapseOne3" class="" aria-labelledby="headingOne3" data-parent="#accordionExample2">
                                            <div class="row">

                                                <div class="col-lg-12 ">
                                                    <table class="table" id="user">
                                                        <tbody>
                                                            <tr>
                                                                <td width="35%">School Name</td>
                                                                <td width="65%"><?=$data['schoolName']?$data['schoolName']:"N/A" ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Spoc</td>
                                                                <td>
                                                                <?=$data['spoc']?$data['spoc']:"N/A" ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>School Address</td>
                                                                <td>
                                                                <?=$data['schoolAddress']?$data['schoolAddress']:"N/A" ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>State</td>
                                                                <td>
                                                                <?=$data['state']?$data['state']:"N/A" ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>City</td>
                                                                <td>
                                                                <?= $data['city']?$data['city']:"N/A" ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Country</td>
                                                                <td>
                                                                <?=$data['country']?$data['country']:"India" ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Zip/Postal Code</td>
                                                                <td>
                                                                <?=$data['postalCode']?$data['postalCode']:"N/A" ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Contact No</td>
                                                                <td>
                                                                <?=$data['contactNo']? $data['contactNo'] :"N/A" ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Website</td>
                                                                <td>
                                                                <?=$data['website'] ? $data['website']: "N/A"?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Email-ID</td>
                                                                <td>
                                                                <?=$data['E-mail-ID	']?$data['E-mail-ID']:"N/A"?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Annual Fees</td>
                                                                <td>
                                                                <?=$data['Annual_Fees']?$data['Annual_Fees']:"0"?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Source</td>
                                                                <td>
                                                                <?=$data['Source']?$data['Source']:"N/A"?>
                                                                </td>
                                                            </tr>
                                                           
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-0 pt-2 shadow-none">

                                        <div class="card-header" id="headingOne5">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne5" aria-expanded="true" aria-controls="collapseOne5">
                                                FACILITIES INFORMATION
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne5" class="" aria-labelledby="headingOne5" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <table class="table" id="user">
                                                        <tbody>
                                                       
                                                            <tr>
                                                                <td>Facilities</td>
                                                                <td width="65%"><?= $data['Facilities']?$data['Facilities']:"N/A" ?></td>
                                                            </tr>
        
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-0 pt-2 shadow-none">

                                        <div class="card-header" id="headingOne6">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne6" aria-expanded="true" aria-controls="collapseOne6">
                                                PRINCIPAL'S INFORMATION
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseOne6" class="" aria-labelledby="headingOne6" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <table class="table" id="user">
                                                        <tbody>
                                                            <tr>
                                                                <td width="35%">Name</td>
                                                                <td width="65%"> <?= $data['PrincipalName']?:"N/A" ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Principal Qualifications</td>
                                                                <td>
                                                                    <?= $data['Principalualifications']?:"N/A" ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Contact Number</td>
                                                                <td>
                                                                    <?= $data['principalContact']?:"N/A" ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>E-mail ID</td>
                                                                <td>
                                                                    <?= $data['principalEmail']?$data['principalEmail']:"N/A" ?>
                                                                </td>
                                                            </tr>                                                    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-0 pt-2 shadow-none">

                                        <div class="card-header" id="headingOne6">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne6" aria-expanded="true" aria-controls="collapseOne6">
                                                IMPORTANT PERSON'S INFORMATION
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseOne6" class="" aria-labelledby="headingOne6" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <table class="table" id="user">
                                                        <tbody>
                                                            <tr>
                                                                <td width="35%">Person 1st - Name</td>
                                                                <td width="65%"> <?= $iPersonName1 ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Designation</td>
                                                                <td>
                                                                    <?= $iPersonDesignation1 ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Contact Number</td>
                                                                <td>
                                                                    <?= $iPersonCN1 ?>
                                                                </td>
                                                            </tr>                                                    
                                                            <tr>
                                                                <td>Email ID</td>
                                                                <td>
                                                                    <?= $iPersonemail1 ?>
                                                                </td>
                                                            </tr>                                                    
                                                            <tr>
                                                                <td>Person 2nd - Name</td>
                                                                <td>
                                                                <?= htmlspecialchars($iPersonName2 ? $iPersonName2 : "N/A") ?>

                                                                </td>
                                                            </tr>  
                                                            
                                                            <tr>
                                                                <td>Designation</td>
                                                                <td>
                                                                <?= htmlspecialchars($iPersonDesignation2 ? $iPersonDesignation2 : "N/A") ?>

                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td>Contact Number</td>
                                                                <td>
                                                                <?= htmlspecialchars($iPersonCN2 ? $iPersonCN2 : "N/A") ?>

                                                                </td>
                                                            </tr>                                                    
                                                            <tr>
                                                                <td>Email ID</td>
                                                                <td>
                                                                <?= htmlspecialchars($iPersonemail2 ? $iPersonemail2 : "N/A") ?>

                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne6">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne6" aria-expanded="true" aria-controls="collapseOne6">
                                                ICT360 ADMIN INFORMATION
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseOne6" class="" aria-labelledby="headingOne6" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <table class="table" id="user">
                                                        <tbody>
                                                            <tr>
                                                                <td width="35%">Full Name of Admin - ICT360</td>
                                                                <td width="65%"> <?= htmlspecialchars($ictAdminname ? $ictAdminname : "N/A") ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Designation</td>
                                                                <td>
                                                                <?= htmlspecialchars($ictDesignation ? $ictDesignation : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Email ID</td>
                                                                <td>
                                                                <?= htmlspecialchars($ictEmail ? $ictEmail : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Contact Number</td>
                                                                <td>
                                                                <?= htmlspecialchars($ictContact ? $ictContact : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Alternative Contact Number</td>
                                                                <td>
                                                                <?= htmlspecialchars($ictACN? $ictACN : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne6">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne6" aria-expanded="true" aria-controls="collapseOne6">
                                                PROGRAM INFORMATION
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseOne6" class="" aria-labelledby="headingOne6" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <table class="table" id="user">
                                                        <tbody>
                                                            <tr>
                                                                <td width="35%">Operational Boards in School</td>
                                                                <td width="65%"> <?= htmlspecialchars($operationalBoards? $operationalBoards : "N/A") ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Start Date of ICT360 Program in school</td>
                                                                <td>
                                                                <?= htmlspecialchars($programStartDate? $programStartDate : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>School Academic Year Start Date</td>
                                                                <td>
                                                                <?= htmlspecialchars($academicYearStartDate? $academicYearStartDate : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>School Academic Year End Date</td>
                                                                <td>
                                                                <?= htmlspecialchars($academicYearEndDate? $academicYearEndDate : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Grades Signed Up For ICT 360</td>
                                                                <td>
                                                                <?= htmlspecialchars($GradesSigned? $GradesSigned : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Student count for selected grades</td>
                                                                <td>
                                                                <?= htmlspecialchars($Student_count_for_selected_grades? $Student_count_for_selected_grades : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Purchase Order No.</td>
                                                                <td>
                                                                <?= htmlspecialchars($Purchase_Order_No? $Purchase_Order_No : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Date of Application</td>
                                                                <td>
                                                                <?= htmlspecialchars($dataOfApplication? $dataOfApplication : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Purchase details</td>
                                                                <td>
                                                                <?= htmlspecialchars($Purchase_details? $Purchase_details : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Purchase/ Renewal for Number of years</td>
                                                                <td>
                                                                <?= htmlspecialchars($Purchase_Renewal_for_Number_of_years? $Purchase_Renewal_for_Number_of_years : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne6">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne6" aria-expanded="true" aria-controls="collapseOne6">
                                                LAB INFRASTRUCTURE 
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseOne6" class="" aria-labelledby="headingOne6" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <table class="table" id="user">
                                                        <tbody>

                                                            <tr>
                                                                <td width="35%">Standalone PC</td>
                                                                <td width="65%">
                                                                <?= htmlspecialchars($labStandalone_PC? $labStandalone_PC : "N/A") ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Projector</td>
                                                                <td>
                                                                <?= htmlspecialchars($labProjector? $labProjector : "N/A") ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>TV</td>
                                                                <td>
                                                                <?= htmlspecialchars($labTv? $labTv : "N/A") ?>
                                                                </td>
                                                            </tr>

                                                                <td>Smart Board</td>
                                                                <td>
                                                                <?= htmlspecialchars($Smart_Board? $Smart_Board : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                                <td>Internet</td>
                                                                <td>
                                                                <?= htmlspecialchars($Internet? $Internet : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                                <td>Networking</td>
                                                                <td>
                                                                <?= htmlspecialchars($Networking? $Networking : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                                <td>Thin client</td>
                                                                <td>
                                                                <?= htmlspecialchars($Thin_client? $Thin_client : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                                <td>N Computing</td>
                                                                <td>
                                                                <?= htmlspecialchars($NComputing? $NComputing : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    if($po_attachment){ 
                                    ?>
                                    <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne6">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne6" aria-expanded="true" aria-controls="collapseOne6">
                                                PO Attachments
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseOne6" class="" aria-labelledby="headingOne6" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <table class="table" id="user">
                                                        <tbody>
                                                            <tr>
                                                                <td width="35%">PO Attachment Updated By</td>
                                                                <td width="65%"> <?= getSingleresult("SELECT name from users where id=".$po_attachment_updated_by) ?></td>
                                                            </tr>
                                           
                                                            <?php
                                                            $atts = explode(",",$po_attachment);
                                                            $a = 1;
                                                            foreach ($atts as $att) {
                                                                if (file_exists($att)) { ?>
                                                                    <tr>
                                                                        <td>PO Attachment <?= $a ?></td>
                                                                        <td>
                                                                            <a href="<?= $att ?>" target="_blank">View/Download</a>
                                                                        </td>
                                                                    </tr>
                                                                        <?php
                                                                        $a++;
                                                                         }
                                                                    }   
                                                                    if($po_attachment_updated_by == $_SESSION['user_id']){
                                                                        ?>
                                                                <form action="#" method="post" id="poForm" name="poForm" enctype="multipart/form-data">
                                                               
                                                               <tr>
                                                                   <td>Re-upload Attachment</td>
                                                                   <td>
                                                                       <input type="file" class="form-control" name="po_re_attachment[]" multiple>
                                                                       <button type="submit" name="submit" class="btn1 btn-primary  mt-2">Save</button>
                                                                   </td>
                                                               </tr>
                                                                </form>
                                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="card mb-0 pt-2 shadow-none">

                                        <div class="card-header" id="headingOne7">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne7" aria-expanded="true" aria-controls="collapseOne7">
                                                   LEAD INFORMATION
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne7" class="" aria-labelledby="headingOne7" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <table class="table" id="user">
                                                        <tbody>
                                                            <tr>
                                                                <td width="35%">Stage</td>
                                                                <td>
                                                                <?= htmlspecialchars($leadStage? $leadStage : "N/A") ?>
                                                                </td>
                                                            </tr> 
                                                            <tr>
                                                                <td width="35%">Close Date</td>
                                                                <td>
                                                                <?= htmlspecialchars($lead_close_date? $lead_close_date : "N/A") ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Program initiation date</td>
                                                                <td>
                                                                <?= htmlspecialchars($lead_Program_initiation_date? $lead_Program_initiation_date : "N/A") ?>
                                                                </td>
                                                            </tr>
 
                                                             <tr>
                                                                    <td>Attachment</td>
                                                                    <td>
                                                                    <?= htmlspecialchars($Attachment? $Attachment : "N/A") ?>
                                                                    </td>
                                                                </tr>

                                                            <?php if ($_SESSION['user_type'] != 'REVIEWER') { ?>
                                                                <?php if (file_exists($user_attachement) && $user_attachement != '' && strpos($user_attachement, ".")) { ?>
                                                                    <tr>
                                                                        <td>Attachment</td>
                                                                        <td>
                                                                        <a class="btn1 btn-primary" style="color:white" target="_blank" href="<?= $user_attachement ?>">View</a>
                                                                        <a class="btn1 btn-primary" style="color:white" target="_blank" href="<?= $user_attachement ?>" download>Download</a>

                                                                            <!-- <a href="<?= $user_attachement ?>" target="_blank" download>View/Download</a> -->
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                    <?php } ?>
                                                                    <?php if (file_exists($admin_attachment)) { ?>
                                                                    <tr>
                                                                        <td>Admin Attachment</td>
                                                                        <td>
                                                                            <a href="<?= $admin_attachment ?>" target="_blank">View/Download</a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                <form action="#" method="post" id="saveform" name="saveform" enctype="multipart/form-data">
                                                               
                                                                <!-- <tr>
                                                                    <td>Attachment</td>
                                                                    <td>
                                                                        <input type="file" class="form-control" name="admin_attachment">
                                                                    </td>
                                                                </tr> -->

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="button-items1">
                                       
                                        <button type="button" onclick="javascript:history.go(-1);" class="btn1 btn-danger mt-2">Back</button>
                                       
                                        <br><br><br>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->


        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>
<!-- End Page-content -->
<!-- ============================================================== -->
<!-- End Container fluid  -->
<div id="myModal" class="modal fade" role="dialog">


</div>
<?php include('includes/footer.php') ?>
<script>
    function change_user(id, team_id) {
        // alert(id);
        // alert("team_id "+team_id);
        $.ajax({
            type: 'POST',
            url: 'ajax_change_user.php',
            data: {
                id: id,
                team_id: team_id
            },
            success: function(response) {
                $("#myModal").html();
                $("#myModal").html(response);
                $('#myModal').modal('show');
            }
        });
    }

    function view_activity(a) {
        var type = 'Lead';
        $.ajax({
            type: 'POST',
            url: 'view_activity.php',
            data: {
                pid: a,
                type: type
            },
            success: function(response) {
                $("#myModal").html();
                $("#myModal").html(response);
                $('#myModal').modal('show');
            }
        });
    }

    function add_activity(a, company_name) {
        $.ajax({
            type: 'POST',
            url: 'add_activity.php',
            data: {
                pid: a,
                company_name: company_name
            },
            success: function(response) {
                $("#myModal").html();
                $("#myModal").html(response);
                $('#myModal').modal('show');
            }
        });
    }

    function add_opportunity(lead_id) {
        check = '<?= $checkRequired ?>';
        if(check == 'False'){
            swal("Please edit lead and fill all mandatory fields to update!");        
        }else{
            $.ajax({
                type: 'POST',
                url: 'add_opporunity.php',
                data: {
                    pid: lead_id
                },
                success: function(response) {
                    $("#myModal").html();
                    $("#myModal").html(response);
                    $('#myModal').modal('show');
                }
            });
        }

    }

    $(document).ready(function() {
        var leadId = <?= $_REQUEST['id'] ?>;
        $('#addnewtask').click(function() {
            $.ajax({
                type: 'POST',
                url: 'addnewtask.php',
                data: {
                    leadId: leadId
                },
                success: function(res) {
                    $('#myModal').html('');
                    $('#myModal').html(res);
                    $('#myModal').modal('show');
                }
            });
        })

        $('#modify_log_div').hide();
        $('#modify_log').html('Show');

        $('#modify_log').click(function() {

            // $('#modify_log_div').toggle();
            var text = $(this).html();
            if (text == 'Show') {
                $(this).html('Hide');
                $('#modify_log_div').show();
            } else {
                $(this).html('Show');
                $('#modify_log_div').hide();
            }
        })

    });


    $(function() {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            forceParse: false,
            autoclose: !0

        });

    });

    function edit_activity(id, company_name) {
        $.ajax({
            type: 'POST',
            url: 'edit_activity.php',
            data: {
                id: id,
                company_name: company_name
            },
            success: function(response) {
                $("#myModal").html();
                $("#myModal").html(response);
                $('#myModal').modal('show');
            }
        });
    }

    function view_duplicate(id) {
        var status = $('select[name="status"]').val();
        var id = '<?= $id ?>';
        var company_name = '<?= $company_name ?>';
        var eu_name = '<?= $eu_name ?>';
        var eu_email = '<?= $eu_email ?>';
        var pincode = '<?= $pincode ?>';
        if (status == 'Approved') {
            $.ajax({
                type: 'POST',
                url: 'view_duplicate.php',
                data: {
                    id: id,
                    company_name: company_name,
                    eu_name: eu_name,
                    eu_email: eu_email,
                    pincode: pincode
                },
                success: function(response) {
                    $("#myModal").html();
                    $("#myModal").html(response);
                    $('#myModal').modal('show');
                }

            });
        } else {
            submit_form();
        }
    }

 


    function submit_form() {
        //alert('12312');

        document.getElementById("saveform").submit();


        //$( "#saveform").submit();
    }

    $(document).ready(function() {
        $(".duplicate_check").click(function() {
            var type = $(this).attr('data-url');
            var search = $(this).attr('data-value');
            var id = '<?= $_REQUEST['id'] ?>';
            $.ajax({
                type: 'POST',
                url: 'view_duplicate.php',
                data: {
                    type: type,
                    search: search,
                    id: id
                },
                success: function(response) {
                    $("#myModal").html();
                    $("#myModal").html(response);
                    $('#myModal').modal('show');
                }

            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        var wfheight = $(window).height();
        $('.add_lead').height(wfheight - 250);
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
                        $("#myModal").html();
                        $("#myModal").html(response);

                        $('#myModal').modal('show');
                        var stage = $('#dd_stage').val();
                        var stage = $.trim(stage);
                        var user_type = '<?= $_SESSION['user_type'] ?>';
                        $('.preloader').hide();
                    }
                });
            }

            function get_change_data(pid, ids) {
                var stage = $('#dd_stage :selected').text();
                var stagevalue = $('#dd_stage :selected').val();
                var substage = $('#add_comment_dd :selected').text();
                var substagevalue = $('#add_comment_dd :selected').val();
                var payment_status = $('#payment_status :selected').val();
                if (stagevalue == '') {
                    swal("Please select stage first.");
                    return false;
                }
                if (substagevalue == '') {
                    swal('Please select sub stage first');
                    return false;
                }
                if(substagevalue=='Partial/Credit'){
			
			if(payment_status == ''){
				swal("Please select payment status.");
			return false;
			}
		}
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
                                    $('#myModal').modal('hide');
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