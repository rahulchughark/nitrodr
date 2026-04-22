<?php include('includes/header.php');
include_once('helpers/DataController.php');
// echo $_SESSION['name']; die;
$modify_log = new DataController();

$sql = db_query("select * from orders where id='" . $_REQUEST['id'] . "'");
$row_data = db_fetch_array($sql);

if (!empty($_FILES["user_attachment"]["name"])) {

    $target_dir = "uploads/";
    $target_file = $target_dir . time() . basename($_FILES["user_attachment"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($_FILES["user_attachment"]["size"] > 4000000) {
        echo "<script>alert('Sorry, your file is too large!')</script>";
        redir("orders.php", true);
    } else {
        move_uploaded_file($_FILES["user_attachment"]["tmp_name"], $target_file);
    $attachement = $_FILES["user_attachment"]["name"];

        $res = db_query("update `orders` set user_attachement='$target_file' where id='" . $_REQUEST['id'] . "'");
        if ($res) {

            redir("orders.php?update=success", true);
        }
    }
}

if ($_POST['new_user']) {
    $email_new = getSingleresult("select email from users where id=" . $_POST['new_user']);
    $name_new = getSingleresult("select name from users where id=" . $_POST['new_user']);

    $old_name = getSingleresult("select name from users where id=" . $row_data['created_by']);

    $log = [
        'lead_id'          => $_REQUEST['id'],
        'type'             => 'Ownership',
        'previous_name'    => $old_name,
        'modify_name'      => $name_new,
        'created_date'     => date("Y-m-d H:i:s"),
        'created_by'       => $_SESSION['user_id'],
    ];
    //print_r($log);die;
    $res = $modify_log->insert($log, 'lead_modify_log');

    $update_data = ['created_by' => $_POST['new_user'], 'r_user' => $name_new, 'r_email' => $email_new,];
    $where = ['id' => $_POST['id']];
    $ins = $modify_log->update($update_data, 'orders', $where);


    redir("partner_view.php?id=" . $_POST['id'], true);
}

if (isset($_POST['save_activity'])) {

    $res = activityLogs($_POST['pid'], preg_replace('/[^A-Za-z0-9\-]/', ' ',$_POST['remarks']), $_POST['call_subject'], $_SESSION['user_id'],$_POST['action_plan']);

    // $sm_email = getSingleresult("select users.email as email from users left join partners on partners.sm_user=users.id where partners.id='" . $row_data['team_id'] . "'");
    // if ($sm_email){
    //     $addCc[] = $sm_email;
    // }
    $adminsEmail = db_query("select email from users where user_type IN ('SUPERADMIN','ADMIN')");
    while ($rowAd = db_fetch_array($adminsEmail)) {
        $addCc[] = $rowAd['email'];                
    }
    $operEmail = db_query("select email from users where user_type = 'OPERATIONS'");            
    while ($rowOp = db_fetch_array($operEmail)) {
        $addCc[] = $rowOp['email'];                
    }
    // $addTo[] = ("pradeep.chahal@arkinfo.in");
    $addCc[] = $_SESSION['email']; 

    $addBcc[] = '';
    $setSubject = $row_data['school_name'] . " - New Log a Call";

    $manager_email = getSingleresult("select email from users where user_type='MNGR' and status = 'Active' and team_id=" . $row_data['team_id']);
    $addTo[] = $manager_email;

    $body    = "Hello,<br><br> There is new log a call from " . $_SESSION['name'] . " on ICT DR Portal with details as below:-<br><br>
    <ul>
    <li><b>Partner Name</b> : " . $row_data['r_name'] . " </li>
    <li><b>Organization Name</b> : " . $row_data['school_name'] . " </li>
    <li><b>Contact Number</b> : ". $row_data['eu_mobile'] ." </li>
    <li><b>Email ID</b> : ". $row_data['eu_email'] ." </li>
    <li><b>Call Subject</b> : " . htmlspecialchars($_POST['call_subject'], ENT_QUOTES) . " </li>
    <li><b>POA Subject</b> : " . htmlspecialchars($_POST['action_plan'], ENT_QUOTES) . " </li>
    <li><b>Description</b> : " . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . " </li>
    <li><b>Quantity</b> : " . $row_data['quantity'] . " </li>
    <li><b>Expected Close Date</b> : " . $row_data['expected_close_date'] . " </li></ul><br>
    Thanks,<br>
    ICT DR Portal
    ";
    
    sendMail($addTo, $addCc, $addBcc, $setSubject, $body);

}


$userType = $_SESSION['user_type'];
$subQuery = '';

if ($userType == "ADMIN" || $userType == "SUPERADMIN"  || $userType == "OPERATIONS" || $userType == "REVIEWER") {
    $subQuery = " ";
} else {
    $subQuery = " AND o.team_id = '" . $_SESSION['team_id'] . "'";
}

if ($_REQUEST['id']) {
    $sql = leadViewData('orders', $subQuery, $_REQUEST['id']);
    $data = db_fetch_array($sql);
    @extract($data);
} else {
    redir("manage_orders.php", true);
}

?>
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
                            <!-- <h5 class="card-title">Add Lead</h5>-->
                            <div class="media ">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">
                                    <small class="text-muted">Home > View Lead</small>
                                    <h4 class="font-size-14 m-0 mt-1">View Lead</h4>
                                </div>
                               
                                <!-- <a href="#" id="addCopy">
                                    <button type="button" class="btn btn-xs  ml-1 waves-effect btn-primary waves-light" data-toggle="modal" data-original-title="Copy Lead as New" data-animation="bounce" data-target=".bs-example-modal-task" style="float: right;"><i class="ti-reload" data-toggle="tooltip" data-placement="left" title="" data-original-title="Copy Lead as New"></i></button></a> -->
                            </div>
                            <div class="clearfix"></div>
                            <div data-simplebar class="add_lead">
                                <div class="accordion" id="accordionExample2">
                                    <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne2">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne2" aria-expanded="true" aria-controls="collapseOne2">
                                                    Lead Modify Log
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne2" class="" aria-labelledby="headingOne2" data-parent="#accordionExample2">

                                            <?php if ($data['lapsed_date'] && $data['lapsed_date'] != '0000-00-00 00:00:00') { ?>
                                                <!-- <div class="card-body font-size-13">Lapsed on <strong><?= date('F j, Y, g:i a', strtotime($data['lapsed_date'])) ?></strong></div> -->

                                                <?php
                                            }
                                            $sql = db_query("select * from lead_modify_log where log_status='Active' AND lead_id=" . $_REQUEST['id'] . " order by id desc");


                                            if (db_num_array($sql) > 0) {

                                                while ($data1 = db_fetch_array($sql)) { ?>

                                                    <div class="card-body font-size-13"> <?= getSingleresult("select name from users where id=" . $data1['created_by']) . (($data1['type'] != 'Request Status' && $data1['type'] != 'Request Delete Status') ? (' has changed <strong>' . $data1['type'] . ' </strong>') : (($data1['type'] == 'Request Status') ? ' <strong>has requested Status Change</strong>' : ' <strong>has deleted Status Change Request</strong>')) ?> from <strong> <?= ($data1['previous_name'] ? $data1['previous_name'] : 'N/A') ?> </strong> to <strong> <?= $data1['modify_name'] ?></strong> on <?= date('F j, Y, g:i a', strtotime($data1['created_date'])) ?>.
                                                    </div>


                                            <?php $count++;
                                                }
                                            } ?>



                                            <div class="card-body font-size-13">Created by <strong><?= getSingleresult("select name from users where id=" . $created_by) ?></strong> on <strong><?= date('F j, Y, g:i a', strtotime($created_date)) ?></strong></div>


                                        </div>

                                    </div>



                                    <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne3">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne3" aria-expanded="true" aria-controls="collapseOne3">
                                                    Reseller Info
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne3" class="" aria-labelledby="headingOne3" data-parent="#accordionExample2">
                                            <div class="row">

                                                <div class="col-lg-12 ">
                                                    <table class="table" id="user">

                                                        <tbody>
                                                            <tr>
                                                                <td width="35%">Partner Name</td>
                                                                <td width="65%"><?= $r_name ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Partner Email</td>
                                                                <td>
                                                                    <?= $r_email ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Submitted By</td>
                                                                <td>
                                                                    <?= $r_user ?> 
                                                                    <!-- <?php if (($created_by == $_SESSION['user_id']) || $_SESSION['user_type'] == 'MNGR') { ?> <button class="btn1 btn-primary" onclick="change_user('<?= $_GET['id'] ?>','<?= $_SESSION['team_id'] ?>')">Change</button> <?php } ?> -->

                                                                </td>
                                                            </tr>
                                                            <?php if ($allign_to) { ?>
                                                                <tr>
                                                                    <td>Aligned To</td>
                                                                    <td>
                                                                        <?= getSingleresult("select name from users where id=" . $allign_to) ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
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
                                                    Customer Information
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne5" class="" aria-labelledby="headingOne5" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <table class="table" id="user">
                                                        <tbody>
                                                        <tr>
                                                                <td>Product Name</td>
                                                                <td><?= getSingleresult("select tp.product_name from tbl_lead_product as tlp left join tbl_product as tp on tp.id=tlp.product_id where tlp.lead_id=" . $_REQUEST['id']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Sub-Product Name</td>
                                                                <td><?= getSingleresult("select tp.product_type from tbl_lead_product as tlp left join tbl_product_pivot as tp on tp.id=tlp.product_type_id where tlp.lead_id=" . $_REQUEST['id']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Date of Visit</td>
                                                                <td>
                                                                    <?= date('d-m-Y H:i:s', strtotime($created_date)) ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Lead Source</td>
                                                                <td width="65%"><?= $source ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Sub Lead Source</td>
                                                                <td width="65%"><?= $sub_lead_source ?></td>
                                                            </tr>                                                         
                                                            <tr>
                                                                <td>School Board</td>
                                                                <td>
                                                                    <?= $school_board ?>                                                                    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Billing Reseller</td>
                                                                <td>
                                                                    <?= $billing_reseller ? getSingleresult("SELECT name from partners where id=".$billing_reseller) : '' ?>                                                                   
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Credit Reseller</td>
                                                                <td>
                                                                    <?= $credit_reseller ? getSingleresult("SELECT name from partners where id=".$credit_reseller) : '' ?>                                                                   
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Is Group</td>
                                                                <td>
                                                                    <?= $is_group ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Group Name</td>
                                                                <td>
                                                                    <?= $group_name ?>
                                                                </td>
                                                            </tr>                                        
                                                            
                                                            <tr>
                                                                <td>State</td>
                                                                <td>
                                                                    <?= $state ? getSingleresult("select name from states where id='" . $state."' ") : '' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>City</td>
                                                                <td>
                                                                    <?= $city ? getSingleresult("select city from cities where id='" . $city."' ") : '' ?>
                                                                </td>
                                                             </tr>
                                                           
                                                           
                                                            <tr>
                                                                <td>Address</td>
                                                                <td>
                                                                    <?= $address ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>ZIP/ Postal Code</td>
                                                                <td>
                                                                    <?= $pincode ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Region</td>
                                                                <td>
                                                                    <?= $region ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Country</td>
                                                                <td>India</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Contact No.</td>
                                                                <td>
                                                                    <?= $contact ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Website</td>
                                                                <td>
                                                                    <?= $website ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>E-mail ID</td>
                                                                <td>
                                                                    <?= $school_email ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Annual Fees</td>
                                                                <td>
                                                                    <?= $annual_fees ?>
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
                                                    Decision Maker/Proprietor/Director/End User Details
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseOne6" class="" aria-labelledby="headingOne6" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <table class="table" id="user">
                                                        <tbody>
                                                            <tr>
                                                                <td width="35%">Full Name</td>
                                                                <td width="65%"> <?= $eu_name ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Email</td>
                                                                <td>
                                                                    <?= $eu_email ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Contact Number</td>
                                                                <td>
                                                                    <?= $eu_mobile ?>
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
                                                Important person Information
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
                                                                <td width="65%"> <?= $eu_person_name1 ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Designation</td>
                                                                <td>
                                                                    <?= $eu_designation1 ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Contact Number</td>
                                                                <td>
                                                                    <?= $eu_mobile1 ?>
                                                                </td>
                                                            </tr>                                                    
                                                            <tr>
                                                                <td>Email ID</td>
                                                                <td>
                                                                    <?= $eu_email1 ?>
                                                                </td>
                                                            </tr>                                                    
                                                            <tr>
                                                                <td>Person 2nd - Name</td>
                                                                <td>
                                                                    <?= $eu_person_name2 ?>
                                                                </td>
                                                            </tr>                                                    
                                                            <tr>
                                                                <td>Contact Number</td>
                                                                <td>
                                                                    <?= $eu_mobile2 ?>
                                                                </td>
                                                            </tr>                                                    
                                                            <tr>
                                                                <td>Email ID</td>
                                                                <td>
                                                                    <?= $eu_email2 ?>
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
                                                ICT360 Admin Information
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
                                                                <td width="65%"> <?= $adm_name ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Designation</td>
                                                                <td>
                                                                    <?= $adm_designation ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Email ID</td>
                                                                <td>
                                                                    <?= $adm_email ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Contact Number</td>
                                                                <td>
                                                                    <?= $adm_mobile ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Alternative Contact Number</td>
                                                                <td>
                                                                    <?= $adm_alt_mobile ?>
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
                                                Program Information
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
                                                                <td width="65%"> <?= $school_board ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Start Date of ICT360 Program in school</td>
                                                                <td>
                                                                    <?= $program_start_date ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>School Academic Year Start Date</td>
                                                                <td>
                                                                    <?= $academic_start_date ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>School Academic Year End Date</td>
                                                                <td>
                                                                    <?= $academic_end_date ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Grades Signed Up For ICT 360</td>
                                                                <td>
                                                                    <?= $grade_signed_up ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Student count for selected grades</td>
                                                                <td>
                                                                    <?= $quantity ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Purchase Order No.</td>
                                                                <td>
                                                                    <?= $purchase_no ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Date of Application</td>
                                                                <td>
                                                                    <?= $application_date ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Purchase details</td>
                                                                <td>
                                                                    <?= $purchase_deails ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Purchase/ Renewal for Number of years</td>
                                                                <td>
                                                                    <?= $license_period ?>
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
                                                Lab Details
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseOne6" class="" aria-labelledby="headingOne6" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <table class="table" id="user">
                                                        <tbody>
                                                            <tr>
                                                                <td width="35%">Does your School have any School App/ ERP System?</td>
                                                                <td width="65%"> <?= $is_app_erp ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>School IP Address</td>
                                                                <td>
                                                                    <?= $ip_address ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>No. of Labs</td>
                                                                <td>
                                                                    <?= $labs_count ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Number of System (laptop/desktop) for ICT program.</td>
                                                                <td>
                                                                    <?= $system_count ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Operating systems used in ICT Labs</td>
                                                                <td>
                                                                    <?= $os ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Student system ratio</td>
                                                                <td>
                                                                    <?= $student_system_ratio ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Lab teacher ratio</td>
                                                                <td>
                                                                    <?= $lab_teacher_ratio ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Standalone PC</td>
                                                                <td>
                                                                    <?= $standalone_pc ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Projector</td>
                                                                <td>
                                                                    <?= $projector ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>TV</td>
                                                                <td>
                                                                    <?= $tv ?>
                                                                </td>
                                                            </tr>
                                                                <td>Smart Board</td>
                                                                <td>
                                                                    <?= $smart_board ?>
                                                                </td>
                                                            </tr>
                                                                <td>Internet</td>
                                                                <td>
                                                                    <?= $internet ?>
                                                                </td>
                                                            </tr>
                                                                <td>Networking</td>
                                                                <td>
                                                                    <?= $networking ?>
                                                                </td>
                                                            </tr>
                                                                <td>Thin client</td>
                                                                <td>
                                                                    <?= $thin_client ?>
                                                                </td>
                                                            </tr>
                                                                <td>N Computing</td>
                                                                <td>
                                                                    <?= $n_computing ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-0 pt-2 shadow-none">

                                        <div class="card-header" id="headingOne7">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne7" aria-expanded="true" aria-controls="collapseOne7">
                                                    Lead Information
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne7" class="" aria-labelledby="headingOne7" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <table class="table" id="user">
                                                        <tbody>
                                                        <tr>
                                                                <td width="35%">Tag</td>
                                                                <td>
                                                                    <?= $tag ? getSingleResult("SELECT name FROM tag where id=".$tag) : '' ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="35%">Remarks</td>
                                                                <td>
                                                                    <?= $visit_remarks ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Created on</td>
                                                                <td>
                                                                    <?= date('d-m-Y H:i:s', strtotime($created_date)) ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Lead Status</td>
                                                                <td>
                                                                    <?= $lead_status ?>
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
                                                                    <td>Additional Comment</td>
                                                                    <td>
                                                                        <textarea <?= (($_SESSION['sales_manager'] == 1) ? 'disabled' : '') ?> class="form-control" name="add_comment"><?= $add_comment ?></textarea>
                                                                    </td>
                                                                </tr> -->
                                                                <!-- <tr>
                                                                    <td>Attachment</td>
                                                                    <td>
                                                                        <input type="file" class="form-control" name="admin_attachment">
                                                                    </td>
                                                                </tr> -->

                                                                <tr>
                                                                    <td>Stage</td>
                                                                    <td>
                                                                    <?=$stage ?>    <a href="javascript:void(0)" title="Change Stage" id=but<?=$row_data['id']?> onclick="stage_change('but<?=$row_data['id']?>',<?=$row_data['id']?>)"> <i style="font-size:18px" class="mdi mdi-update"></i></a> 
                                                                    </td>
                                                                </tr>
                                                       
                                                                
                                                                <tr>
                                                                    <td>Expected Close Date</td>
                                                                    <td><?= $expected_close_date ?></td>
<!-- <td><input type="text" value="<?= $expected_close_date ?>" class="form-control col-md-2 datepicker" readonly id="cl_date" name="expected_close_date" /></td> -->
                                                                </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                        <div class="card mb-0 pt-2 shadow-none">
                                            <div class="card-header" id="headingOne9">
                                                <h5 class="my-0">
                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne9" aria-expanded="true" aria-controls="collapseOne9">
                                                        Activity History
                                                    </button>
                                                </h5>
                                                <?php if (getSingleresult("select count(id) from  lead_review where is_review IN (1,2) and lead_id='" . $data['id'] . "'")) { ?>
                                                   <span class="text-danger">Under Review</span>
                                                <?php } else { ?>    
                                                <a data-toggle="modal" onclick="add_activity(<?= $_GET['id'] ?>,'<?= $company_name ?>')" data-animation="bounce" data-target=".bs-example-modal-center">
                                                    <button class="btn1 btn-primary  ml-2 mt-1">Log a Call</button></a>
                                                 <?php } ?>     
                                            </div>

    <div id="collapseOne9" class="" aria-labelledby="headingOne9" data-parent="#accordionExample2">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            $new = db_query("select id,description,created_date,added_by,call_subject,action_plan from activity_log where is_intern=0 and (activity_type='Lead' or activity_type='DVR') and pid='" . $_GET['id'] . "' UNION SELECT id,description,created_date,added_by,id,id from caller_comments where pid='" . $_GET['id'] . "' union select id,comment as description,created_date,added_by,id,old_stage from review_log where lead_id='" . $_GET['id'] . "' order by created_date desc");
                            $goal = db_query("select * from activity_log where pid='" . $_GET['id'] . "' order by created_date desc");
                            $count = mysqli_num_rows($new);
                            $i = $count;
                            if ($count) {
                                echo  ' <table class="table"><thead class="thead-default"><tr><th>S.No</th><th>Subject</th><th>Description</th><th>Added By</th><th>Date</th></tr></thead>';

                                while ($data_n = db_fetch_array($new)) { ?>
                            <tbody>
                                <tr style="text-align:center;">
                                    <td><?= $i ?></td>
                                    <td><?= ($data_n['call_subject'] ? $data_n['call_subject'] : 'N/A') ?></td>
                                    <td><?= $data_n['description'] ?></td>
                                   
                                    <td><?= (is_numeric($data_n['added_by']) ? getSingleresult("select concat(name,'-',CASE  WHEN user_type='USR' THEN 'Partner User' WHEN user_type='ADMIN' and sales_manager=0 THEN 'ADMIN' WHEN user_type='ADMIN' and sales_manager=1 THEN 'Sales Manager' WHEN user_type='MNGR' THEN 'Partner Manager' WHEN user_type='OPERATIONS' THEN 'OPERATIONS' WHEN user_type='INTERN' THEN 'COREL INTERN' ELSE 'Caller' END) as nme from users where id='" . $data_n['added_by'] . "'") : '<span style="color:red">(Lead Review!)</span> ' . $data_n['added_by']) ?></td>
                                    <td><?= date('d-m-Y H:i:s', strtotime($data_n['created_date'])) ?></td>

                                </tr>
                            </tbody>
                                            <?php $i--;
                                                }
                                                echo "</table>";
                                            } ?>

                                        </div>

                                    </div>
                                </div>
                            </div>


                                        <div class="button-items1">

                                            <button type="submit" name="save_btn" id="save_button" class="btn1 btn-primary  mt-2">Save</button>

                                            </form>
                                            <?php if ($created_by == $_SESSION['user_id']) { ?>
                                                    <a href="edit_order.php?eid=<?= $id ?>"><button type="button" class="btn1 btn-primary  mt-2">Edit</button></a>
                                                <?php } ?>
                                            <button type="button" onclick="javascript:history.go(-1);" class="btn1 btn-danger mt-2">Back</button>

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

        <div id="myModal" class="modal fade" role="dialog">

        </div>

   
        <?php include('includes/footer.php') ?>

        <script>
            function stage_change(ids, id) {
                // alert(ids)
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
                        if ((stage == 'EU PO Issued') && (user_type == 'USR' || user_type == 'PUSR')) {
                            $("#save_button").prop('disabled', true);
                        }
                        $('.preloader').hide();
                    }
                });
            }

            function change_product_type(id, type) {
                swal({
                    title: "Are you sure?",
                    text: "Are you sure you would like to change Product Type ?",
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    confirmButtonText: "Yes!",
                    confirmButtonColor: "#ec6c62"
                }, function() {
                    $.ajax({
                            type: 'POST',
                            url: 'iss_product_change.php',
                            data: {
                                lead_id: id,
                                type: type,
                            },
                            success: function(response) {
                                return false;
                            }
                        }).done(function(data) {
                            swal("Product Type changed successfully!");
                            setTimeout(function() {
                                location.reload();
                            }, 1000)
                        })
                        .error(function(data) {
                            swal("Oops", "We couldn't connect to the server!", "error");
                        });
                })
            }

            function change_association(a) {
                document.getElementById("edit_association").innerHTML = '<input type="text" value="' + a + '" id="new_association"/> <button onclick="save_association()" class="btn1 btn-warning">Save</button>'

            }

            function save_association() {

                var new_assoc = document.getElementById("new_association").value;

                if (new_assoc) {
                    swal({
                        title: "Are you sure?",
                        text: "You want to change the association name for this lead!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, Change!",
                        cancelButtonText: "No, cancel modification!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }, function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "update_association_name.php?id=<?= $_GET['id'] ?>&association=" + new_assoc,
                                success: function(result) {
                                    //var result = $.trim(result);
                                    if (result) {
                                        swal({
                                            title: "Done!",
                                            text: "Lead Modified.",
                                            type: "success"
                                        }, function() {
                                            location.reload();

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

            function payment_option(val, id) {

                $('#hidden_sub_stage').val(val);

                if (val == 'Lost to competition') {
                    $.ajax({
                        type: 'POST',
                        url: 'getParallel_subStage.php',
                        data: {
                            pstage: val,
                            id: id
                        },
                        success: function(html) {
                            //alert(html);
                            if (html != 'html') {
                                $('#add_Pcomment').html(html);
                                $('#add_Pcomment').show();
                                //this.reset();
                            } else {
                                $('#add_Pcomment').hide();
                            }
                        }
                    });
                } else if (val == '100% Advance Received' || val == 'Payment Against Delivery') {
                    $("#op").show();
                    $("#pay_tab").hide();
                    $('#add_Pcomment').hide();
                } else if (val == 'Payment in Installments') {
                    $("#pay_tab").show();
                    $("#op").hide();
                    $('#add_Pcomment').hide();
                } else if (val == 'Payment Not Clear' || val == '') {
                    //alert(12);
                    $("#pay_tab").hide();
                    ("#op").hide();
                    $('#add_Pcomment').hide();
                } else if (val != 'Lost to competition') {
                    $('#add_Pcomment').hide();
                    $('#hidden_parallel_stage option:selected').remove();
                    $('#add_Pcomment_dd option:selected').remove();

                }
            }

            function selectParallel(val) {
                $('#hidden_parallel_stage').val(val);

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
                                url: "relog_lead.php?id=<?= $_GET['id'] ?>",
                                success: function(result) {
                                    if (result == 1) {
                                        swal({
                                            title: "Done!",
                                            text: "Lead Re-Loged.",
                                            type: "success"
                                        }, function() {
                                            //location.reload();
                                            $('#leads').DataTable().ajax.reload();

                                        });
                                    } else {
                                        swal("Can't Relog Lead!", "Lead already logged once in the past!", "error");
                                    }
                                }
                            });

                        } else {
                            swal("Cancelled", "Lead unchanged!", "error");
                        }
                    });
                }
            }


            $(document).ready(function() {
                $('#md_checkbox_21').click(function() {

                    if ($(this).is(':checked')) {
                        $("#ltype").show();
                        $("#sub_btn").show();
                        $("#call_type").show();
                        $("#ltype_dd").prop('required', true);
                    } else {
                        $("#sub_btn").hide();
                        $("#ltype").hide();
                        $("#call_type").hide();
                        $("#ltype_dd").prop('required', false);
                    }
                });


            });
            $(function() {
                $('.datepicker2').datepicker({

                    "singleDatePicker": true,
                    "showDropdowns": true,
                    minDate: new Date(),
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                });
            });

            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    startDate: '-3d',
                    autoclose: !0

                });

            });

            function change_quantity(a) {
                document.getElementById("quant").innerHTML = '<input type="text" value="' + a + '" id="new_quantity"/> <button onclick="save_newqty()" class="btn1 btn-warning">Save</button>'

            }

            function save_newqty() {
                var newquant = document.getElementById("new_quantity").value;
                if (newquant) {
                    swal({
                        title: "Are you sure?",
                        text: "You want to change the quantity for this lead!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, Change!",
                        cancelButtonText: "No, cancel modification!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }, function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "update_quantity.php?id=<?= $_GET['id'] ?>&quantity=" + newquant,
                                success: function(result) {
                                    if (result) {
                                        swal({
                                            title: "Done!",
                                            text: "Lead Modified.",
                                            type: "success"
                                        }, function() {
                                            location.reload();

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

            function change_user(id, team_id) {
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

            function edit_activity(id) {
                $.ajax({
                    type: 'POST',
                    url: 'edit_activity.php',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        $("#myModal").html();
                        $("#myModal").html(response);
                        $('#myModal').modal('show');
                    }
                });
            }

            $(document).ready(function() {
                var leadId = <?= $_REQUEST['id'] ?>;
                $('#addAction').click(function() {
                    //alert('<?= $_SESSION['user_id'] ?>');  
                    $.ajax({
                        type: 'POST',
                        url: 'addAction.php',
                        data: {
                            leadId: leadId,
                            user_id: '<?= $_SESSION['user_id'] ?>',
                            name: '<?= $_SESSION['name'] ?>',
                            user_type: '<?= $_SESSION['user_type'] ?>'
                        },
                        success: function(res) {
                            $('#myModal').html('');
                            $('#myModal').html(res);
                            $('#myModal').modal('show');
                        }

                    });
                });
                $('#addCopy').click(function() {
                    var p_name = $('#product_id').val();
                    if (p_name == 'Parallel') {
                        window.location = 'add_order_parallel.php?cid=' + leadId;
                    } else {
                        window.location = 'add_leads.php?cid=' + leadId;
                    }

                });
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
                $('#datepicker1').datepicker({
                    format: 'yyyy-mm-dd',
                    startDate: '-3d',
                    autoclose: !0

                });

            });
            $(function() {
                $('#datepicker3').datepicker({
                    format: 'yyyy-mm-dd',
                    startDate: '-3d',
                    autoclose: !0

                });

            });
            $(function() {
                $('#datepicker4').datepicker({
                    format: 'yyyy-mm-dd',
                    startDate: '-3d',
                    autoclose: !0

                });

            });
            $(function() {
                $('#datepicker5').datepicker({
                    format: 'yyyy-mm-dd',
                    startDate: '-3d',
                    autoclose: !0

                });

            });
            $(function() {
                $('#datepicker6').datepicker({
                    format: 'yyyy-mm-dd',
                    startDate: '-3d',
                    autoclose: !0

                });

            });
            $(function() {
                $('#datepicker7').datepicker({
                    format: 'yyyy-mm-dd',
                    startDate: '-3d',
                    autoclose: !0

                });

            });
       

            $(document).ready(function(){
    $('.timepicker').timepicker({
        timeFormat: 'HH:mm',
        // year, month, day and seconds are not important
        minTime: new Date(0, 0, 0, 8, 0, 0),
        maxTime: new Date(0, 0, 0, 15, 0, 0),
        // time entries start being generated at 6AM but the plugin 
        // shows only those within the [minTime, maxTime] interval
        startHour: 6,
        // the value of the first item in the dropdown, when the input
        // field is empty. This overrides the startHour and startMinute 
        // options
        startTime: new Date(0, 0, 0, 8, 20, 0),
        // items in the dropdown are separated by at interval minutes
        interval: 1
    });
});


function change_user_attachment(event) {
                // document.getElementById("change_user_att").innerHTML = '<input type="file" class="form-control" name="user_attachment" id="user_attachment">'
                event.preventDefault();
                $('#user_attachment').css('display','block');
                $('#files_buttons').css('display','none');

            
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

        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.add_lead').height(wfheight - 220);
            });
        </script>

        <!-- No Stage Self Review -->
        <?php require_once("self_review_script.php"); ?>

        <!-- End Self Review for quote Stage -->