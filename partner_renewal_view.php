<?php include('includes/header.php');

$sql = db_query("select * from orders where id='" . $_REQUEST['id'] . "'");
$row_data = db_fetch_array($sql);

if ($row_data['status'] == 'Undervalidation') {
    if ($_POST['remarks'] || $_POST['call_subject']) {
        $res = db_query("update  `orders` set status='Pending',created_date=now() where id='" . $_REQUEST['id'] . "'");


        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Status','" . $row_data['status'] . "','Pending',now(),'" . $_SESSION['user_id'] . "')");
    }
}


if ($_POST['save_new_user']) {
    $email_new = getSingleresult("select email from users where id=" . $_POST['new_user']);
    $name_new = getSingleresult("select name from users where id=" . $_POST['new_user']);
    $old_name = getSingleresult("select name from users where id=" . $row_data['created_by']);
    // $modify_name=getSingleresult("select name from users where id=".$_POST['new_user']);
    $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Ownership','" . $old_name . "','" . $name_new . "',now(),'" . $_SESSION['user_id'] . "')");


    $ins = db_query("update orders set created_by='" . $_POST['new_user'] . "',r_user='" . $name_new . "',r_email='" . $email_new . "' where id='" . $_POST['id'] . "'");
    redir("partner_renewal_view.php?id=" . $_POST['id'], true);
}


if ($_POST['partner_close_date'] && !$_POST['stage']) {
    if ($row_data['partner_close_date'] != $_POST['partner_close_date']) {
        $modify_name = $_POST['partner_close_date'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Close Date','" . $row_data['partner_close_date'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    $res = db_query("update orders set partner_close_date='" . $_POST['partner_close_date'] . "' where id='" . $_GET['id'] . "'");

    redir("renewal_leads_partner.php?update=success", true);
}

if ($_POST['stage']) {
    $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Stage','" . $row_data['stage'] . "','" . $_POST['stage'] . "',now(),'" . $_SESSION['user_id'] . "')");

    if ($row_data['partner_close_date'] != $_POST['partner_close_date']) {
        $modify_name = $_POST['partner_close_date'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Close Date','" . $row_data['partner_close_date'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    ////////////////////////////Points Calc///////////////////////////////
    if ($data['status'] == 'Approved') {
        $stage = db_query("select stage,quantity,created_by,iss,allign_to from orders where id=" . $_REQUEST['id'] . " limit 1");
        $order_detail = db_fetch_array($stage);
        if ($order_detail['iss']) {
            $order_detail['created_by'] = $order_detail['allign_to'];
        }
        $stage_details = db_query("select * from stages where stage_name='" . $_POST['stage'] . "'");
        $st_data = db_fetch_array($stage_details);
        if (!getSingleresult("select id from user_points where stage_name='" . $_POST['stage'] . "' and lead_id=" . $_REQUEST['id'])) {
            $points_date = week_range(date('Y-m-d'));
            if ($st_data['id'] == '12' && $st_data['stage_name'] == 'OEM Billing') {
                $points = $order_detail['quantity'] * $st_data['stage_point'];
                $add_point = db_query("insert into user_points (stage_id,stage_name,point,week_number,date_from,date_to,no_of_seats,user_id,lead_id) values ('" . $st_data['id'] . "','" . $st_data['stage_name'] . "','" . $points . "','" . date('W') . "','$points_date[0]','$points_date[1]','" . $order_detail['quantity'] . "','" . $order_detail['created_by'] . "','" . $_REQUEST['id'] . "') ");
            } else {
                $add_point = db_query("insert into user_points (stage_id,stage_name,point,week_number,date_from,date_to,no_of_seats,user_id,lead_id) values ('" . $st_data['id'] . "','" . $st_data['stage_name'] . "','" . $st_data['stage_point'] . "','" . date('W') . "','$points_date[0]','$points_date[1]','" . $order_detail['quantity'] . "','" . $order_detail['created_by'] . "','" . $_REQUEST['id'] . "') ");
            }
        }
    }
    if ($_POST['stage'] == 'OEM Billing') {
        if (date('m', strtotime($row_data['partner_close_date'])) == '01') {
            $point_approved = 2;
            $point_rejected = -1.5;
        } else if (date('m', strtotime($row_data['partner_close_date'])) == '02') {
            $point_approved = 1.5;
            $point_rejected = -1;
        } else {
            $point_approved = 0;
            $point_rejected = 0;
        }
        if ($row_data['status'] == 'Approved') {
            $var_points = $row_data['quantity'] * $point_approved;
        } else {
            $var_points = $row_data['quantity'] * $point_rejected;
        }

        if (!getSingleresult("select id from var_promo where lead_id='" . $_REQUEST['id'] . "'")) {
            $add_var = db_query("INSERT INTO `var_promo`(`point`, `user_id`, `team_id`, `month`, `lead_id`) VALUES ('" . $var_points . "','" . $_SESSION['user_id'] . "','" . $_SESSION['team_id'] . "','" . date('m', strtotime($order_detail['partner_close_date'])) . "','" . $_REQUEST['id'] . "')");
        }
    }


    ///////////////////////////////////////End Points Calc

    if ($_POST['add_comm'] || $_POST['add_Pcomm']) {
        $res =
            //"update orders set stage='" . $_POST['stage'] . "',prospecting_date='" . date('Y-m-d') . "',payment_status='" . $_POST['payment_status'] . "',add_comm='" . $_POST['add_comm'] . "',add_Parallelcomm='" . $_POST['parallel_sub_stage'] . "',partner_close_date='" . $_POST['partner_close_date'] . "' where id='" . $_GET['id'] . "'";
            db_query("update orders set stage='" . $_POST['stage'] . "',prospecting_date='" . date('Y-m-d') . "',payment_status='" . $_POST['payment_status'] . "',add_comm='" . $_POST['add_comm'] . "',add_Parallelcomm='" . $_POST['add_Pcomm'] . "',partner_close_date='" . $_POST['partner_close_date'] . "' where id='" . $_GET['id'] . "'");
    } else {

        $res =
            // "update orders set stage='" . $_POST['stage'] . "',prospecting_date='" . date('Y-m-d') . "',payment_status='" . $_POST['payment_status'] . "',add_comm='" . $_POST['sub_stage'] . "',add_Parallelcomm='" . $_POST['parallel_sub_stage'] . "',partner_close_date='" . $_POST['partner_close_date'] . "' where id='" . $_GET['id'] . "'";
            db_query("update orders set stage='" . $_POST['stage'] . "',prospecting_date='" . date('Y-m-d') . "',payment_status='" . $_POST['payment_status'] . "',add_comm='" . $_POST['sub_stage'] . "',add_Parallelcomm='" . $_POST['parallel_sub_stage'] . "',partner_close_date='" . $_POST['partner_close_date'] . "' where id='" . $_GET['id'] . "'");
    }
    //print_r($res);die;
    if ($_POST['sub_stage'] == 'Payment in Installments' || $row_data['add_comm'] == 'Payment in Installments') {

        $query = db_query("select * from installment_details where pid=" . $_GET['id']);
        //print_r($query);die;
        if (mysqli_num_rows($query) > 0) {

            $ps = db_query("update installment_details set pid='" . $_GET['id'] . "',type='Lead',order_price='" . $_POST['order_price'] . "',date1='" . $_POST['date1'] . "',instalment1='" . $_POST['instalment1'] . "',date2='" . $_POST['date2'] . "',instalment2='" . $_POST['instalment2'] . "',date3='" . $_POST['date3'] . "',instalment3='" . $_POST['instalment3'] . "',date4='" . $_POST['date4'] . "',instalment4='" . $_POST['instalment4'] . "',date5='" . $_POST['date5'] . "',installment5='" . $_POST['instalment5'] . "',date6='" . $_POST['date6'] . "',installment6='" . $_POST['instalment6'] . "',added_by='" . $_SESSION['user_id'] . "' where pid='" . $_GET['id'] . "'");
        } else {

            $ps = db_query("insert into installment_details (`pid`, `type`,`order_price`, `date1`, `instalment1`, `date2`, `instalment2`, `date3`, `instalment3`, `date4`, `instalment4`,`date5`, `installment5`, `date6`, `installment6`, `added_by`) values ('" . $_GET['id'] . "','Lead','" . $_POST['order_price'] . "','" . $_POST['date1'] . "','" . $_POST['instalment1'] . "','" . $_POST['date2'] . "','" . $_POST['instalment2'] . "','" . $_POST['date3'] . "','" . $_POST['instalment3'] . "','" . $_POST['date4'] . "','" . $_POST['instalment4'] . "','" . $_POST['date5'] . "','" . $_POST['instalment5'] . "','" . $_POST['date6'] . "','" . $_POST['instalment6'] . "','" . $_SESSION['user_id'] . "')");
        }
    } else if ($_POST['stage'] == 'EU PO Issued' || $row_data['add_comm'] == 'EU PO Issued') {
        $ps = db_query("update orders set op_this_month='" . $_POST['op'] . "' where id='" . $_GET['id'] . "'");
    }


    redir("renewal_leads_partner.php?update=success", true);
}
if ($_POST['submit_dvr']) {
    $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Lead','Lead','DVR',now(),'" . $_SESSION['user_id'] . "')");

    $sql = db_query("update orders set is_dr=1,convert_date='" . date('Y-m-d H:i:s') . "',dvr_by='" . $_SESSION['user_id'] . "',date_dvr='" . date('Y-m-d H:i:s') . "' where id=" . $_GET['id']);

    redir("renewal_leads_partner.php?update=success", true);
}

if ($_POST['remarks'] || $_POST['call_subject']) {

    $res = db_query("insert into activity_log (pid,description,activity_type,call_subject,added_by,data_ref) values ('" . $_POST['pid'] . "','" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "','Renewal','" . $_POST['call_subject'] . "','" . $_SESSION['user_id'] . "',1)");

    $email = db_query("select r_email,r_name,r_user,lead_type,company_name,eu_email,eu_mobile,team_id,quantity,partner_close_date,caller from orders where id=" . $_POST['pid']);
    $data = db_fetch_array($email);

    $addcc[] = ("prashant.dongrikar@arkinfo.in");

    $addBcc[] = ("virendra.kumar@arkinfo.in");
    // if ($data['lead_type'] == 'LC') {
    if ($data['caller'] != '') {
        $caller_email1 = db_query("select users.email as call_email from users join callers on users.id=callers.user_id where callers.id=" . $data['caller']);
        $caller_email = db_fetch_array($caller_email1);
        $addTo[] = ($caller_email['call_email']);
    }

    $manager_email = getSingleresult("select email from users where user_type='MNGR' and team_id=" . $data['team_id']);
    $addcc[] = ($_SESSION['email']);
    $addcc[] = ('rajeshri.shriyan@arkinfo.in');
    $addcc[] = ('amjad.pathan@arkinfo.in');
    $addcc[] = ($manager_email);
    $setSubject = $data['company_name'] . " - New Log a Call";
    $body    = "Hello,<br><br> There is new log a call from " . $_SESSION['name'] . " on SketchUp DR Portal with details as below:-<br><br>
        <ul>
        <li><b>Partner Name</b> : " . $data['r_name'] . " </li>
        <li><b>Account Name</b> : " . $data['company_name'] . " </li>
        <li><b>Lead Type</b> : " . $data['lead_type'] . " </li>
        <li><b>Call Subject</b> : " . htmlspecialchars($_POST['call_subject'], ENT_QUOTES) . " </li>
        <li><b>Description</b> : " . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . " </li>
        <li><b>Quantity</b> : " . $data['quantity'] . " </li>
        <li><b>Projected Close Date</b> : " . $data['partner_close_date'] . " </li></ul><br>
        Thanks,<br>
        SketchUp DR Portal
        ";
    //   } 
    // else {
    //     $manager_email = getSingleresult("select email from users where user_type='MNGR' and team_id=" . $data['team_id']);

    //     $mail->AddAddress($manager_email);
    //     $addcc[] = ($data['r_email']);
    //     $addcc[] = ($_SESSION['email']);
    //     //$addcc[] = ("maneesh.kumar@arkinfo.in");
    //     $mail->Subject = $data['company_name'] . " - New Log a Call";
    //     $mail->Body    = "Hello,<br><br> There is new log a call from " . $_SESSION['name'] . " on SketchUp DR Portal with details as below:-<br><br>
    //     <ul>
    //     <li><b>Partner Name</b> : " . $data['r_name'] . " </li>
    //     <li><b>Account Name</b> : " . $data['company_name'] . " </li>
    //     <li><b>Lead Type</b> : " . $data['lead_type'] . " </li>
    //     <li><b>Call Subject</b> : " . htmlspecialchars($_POST['call_subject'], ENT_QUOTES) . " </li>
    //     <li><b>Description</b> : " . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . " </li>
    //     <li><b>Quantity</b> : " . $data['quantity'] . " </li>
    //     <li><b>Projected Close Date</b> : " . $data['partner_close_date'] . " </li></ul><br>
    //     Thanks,<br>
    //     SketchUp DR Portal
    //     ";
    // }
    sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
}

if ($_POST['activity_edit']) {
    $res = db_query("update activity_log set description='" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "',call_subject='" . $_POST['call_subject'] . "' where id=" . $_POST['pid']);
}

?>


<?php
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
    redir("renewal_leads_partner.php", true);
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

                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home > View Renewal Lead</small>
                                    <h4 class="font-size-14 m-0 mt-1">View Renewal Lead</h4>
                                </div>
                                <!--  <?php
                                        if ($_SESSION['user_type'] == 'MNGR' && $lead_type == 'LC' && $caller) { ?>
                                    <a href="#" id="addAction"><button type="button" class="btn btn-xs btn-primary ml-1 waves-effect waves-light" data-toggle="modal" data-original-title="Add New Action" data-animation="bounce" data-target=".bs-example-modal-task" style="float: right;"><i class="fa fa-plus mr-1"></i></button></a>
                                <?php } ?> -->
                                <!-- <a href="#" id="addCopy">
                                    <button type="button" class="btn btn-xs  ml-1 waves-effect btn-primary waves-light" data-toggle="modal" data-original-title="Copy Lead as New" data-animation="bounce" data-target=".bs-example-modal-task" style="float: right;"><i class="ti-reload"></i></button></a> -->
                            </div>
                            <div class="clearfix"></div>

                            <div data-simplebar class="add_lead">

                                <div class="accordion" id="accordionExample2">
                                    <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne1">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne1" aria-expanded="true" aria-controls="collapseOne1">
                                                    Lead Modify Log
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne1" class="" aria-labelledby="headingOne1" data-parent="#accordionExample2">
                                            <?php if ($data['lapsed_date'] && $data['lapsed_date'] != '0000-00-00 00:00:00') { ?>
                                                <li>Lapsed on <strong><?= date('F j, Y, g:i a', strtotime($data['lapsed_date'])) ?></strong></li>

                                                <?php
                                            }


                                            $sql = db_query("select * from lead_modify_log where log_status='Active' AND lead_id=" . $_REQUEST['id'] . " order by id desc");


                                            if (db_num_array($sql) > 0) {

                                                while ($data1 = db_fetch_array($sql)) { ?>

                                                    <li> <?= getSingleresult("select name from users where id=" . $data1['created_by']) ?> has changed <strong> <?= $data1['type'] ?> </strong> from <strong> <?= ($data1['previous_name'] ? $data1['previous_name'] : 'N/A') ?> </strong> to <strong> <?= $data1['modify_name'] ?></strong> on <?= date('F j, Y, g:i a', strtotime($data1['created_date'])) ?>.
                                                    </li>

                                            <?php


                                                    $count++;
                                                }
                                            }  ?>



                                            <div class="card-body font-size-13">Created by <strong><?= getSingleresult("select name from users where id='" . $created_by . "'") ?></strong> on <strong><?= date('F j, Y, g:i a', strtotime($created_date)) ?></strong></div>


                                        </div>

                                    </div>

                                    <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne2">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne2" aria-expanded="true" aria-controls="collapseOne2">
                                                    Product Info
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne2" class="" aria-labelledby="headingOne2" data-parent="#accordionExample2">
                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <table class="table" id="user">
                                                        <tbody>
                                                            <tr>
                                                                <td width="35%">Product Name</td>
                                                                <td width="65%"><?= $product_name ?>
                                                                <input type="hidden" name="edit_id" id="edit_id" value="<?= $_REQUEST['id'] ?>" />
                                                            </td>
                                                                <input type="hidden" name="product_id" id="product_id" value="<?= $product_name ?>" />
                                                                <input type="hidden" name="edit_id" id="edit_id" value="<?= $_REQUEST['id'] ?>" />
                                                            </tr>
                                                            <tr>
                                                                <td>Product Type</td>
                                                                <td>
                                                                    <?= $product_type ?>&nbsp;

                                                                    <?php if ($product_name == 'CDGS Renewal' && ($product_type == 'CDGS Perpetual' || $product_type == 'CDGS Annual')) { ?>
                                                                        <button class="btn1 btn-primary" onclick="change_product_type('<?= $_GET['id'] ?>','<?= $type_id ?>')">Change Product Type</button>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne3">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne3" aria-expanded="true" aria-controls="collapseOne3">
                                                    Partner Info
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne3" class="" aria-labelledby="headingOne3" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">

                                                    <table class="table ">
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
                                                                <td>Submited By</td>
                                                                <td>
                                                                    <!-- <?= $r_user ?>  -->
                                                                    <?= getSingleresult("select name from users where id='" . $created_by . "'") ?>
                                                                    <!-- <?php if ($created_by == $_SESSION['user_id']) { ?> <button class="btn btn-primary" onclick="change_user('<?= $_GET['id'] ?>','<?= $_SESSION['team_id'] ?>')">Change</button> <?php } ?> -->

                                                                </td>
                                                                <!-- <td>
                                        <?= $r_user ?> <?php if ($created_by == $_SESSION['user_id']) { ?> <button class="btn btn-primary" onclick="change_user('<?= $_GET['id'] ?>','<?= $_SESSION['team_id'] ?>')">Change</button> <?php } ?>

                                    </td> -->
                                                            </tr>
                                                            <?php if ($allign_to) { ?>
                                                                <tr>
                                                                    <td>Aligned To</td>
                                                                    <td>
                                                                        <?= getSingleresult("select name from users where id='" . $allign_to . "'") ?>
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
                                        <div class="card-header" id="headingOne4">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne4" aria-expanded="true" aria-controls="collapseOne4">
                                                    Customer Information
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseOne4" class="" aria-labelledby="headingOne4" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">

                                                    <table class="table ">
                                                        <tbody>
                                                            <tr>
                                                                <td width="35%"><strong>Close Date</strong></td>
                                                                <td width="65%"><strong><?= $partner_close_date ?></strong></td>
                                                            </tr>

                                                            <tr>
                                                                <td width="35%">Lead Source</td>
                                                                <td width="65%"><?= $source ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Lead Type</td>
                                                                <td>
                                                                    <?= $lead_type ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Company Name</td>
                                                                <td>
                                                                    <?= $company_name ?> 
                                                                    <input type="hidden" name="company_name" value="<?= $company_name ?>" id="company">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Parent Company</td>
                                                                <td>
                                                                    <?= $parent_company ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Landline Number</td>
                                                                <td>
                                                                    <?= $landline ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Industry</td>
                                                                <td>
                                                                    <?= getSingleresult("select name from industry where id='" . $industry . "'") ?>
                                                                </td>
                                                            </tr>
                                                            <?php if ($sub_industry) { ?><tr>
                                                                    <td>Sub Industry</td>
                                                                    <td>
                                                                        <?= getSingleresult("select name from sub_industry where id='" . $sub_industry . "'") ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            <tr>
                                                                <td>Region</td>
                                                                <td>
                                                                    <?= $region ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Address</td>
                                                                <td>
                                                                    <?= $address ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Pin Code</td>
                                                                <td>
                                                                    <?= $pincode ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>State</td>
                                                                <td>
                                                                    <?= getSingleresult("select name from states where id='" . $state . "'") ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>City</td>
                                                                <td>
                                                                    <?= $city ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Country</td>
                                                                <td>
                                                                    <?= $country ?>
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
                                                    Decision Maker/Proprietor/Director/End User Details
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseOne5" class="" aria-labelledby="headingOne5" data-parent="#accordionExample2">

                                            <div class="row">
                                                <div class="col-lg-12">

                                                    <table class="table">
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
                                                                <td>Landline Number</td>
                                                                <td>
                                                                    <?= $eu_landline ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Mobile</td>
                                                                <td>
                                                                    <?= $eu_mobile ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Designation</td>
                                                                <td>
                                                                    <?= $eu_designation ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Usage Confirmation Received from</td>
                                                                <td>
                                                                    <?= $confirmation_from ?>
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
                                                    Other Contacts
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne6" class="" aria-labelledby="headingOne6" data-parent="#accordionExample2">

                                            <div class="row">
                                                <div class="col-md-12">
                                                   
                                                        <?php $query = leadContactData('tbl_lead_contact', $_REQUEST['id']);
                                                        $count = mysqli_num_rows($query);
                                                        $i = 1;
                                                        if ($count) {
                                                            echo  ' <table class="table"><tbody><tr><th>S.No</th><th>Name</th><th >Email</th><th>Mobile</th><th>Designation</th></tr>';
                                                            while ($data_n = db_fetch_array($query)) { ?>

                                                                <tr>
                                                                    <td><?= $i ?></td>
                                                                    <td><?= ($data_n['eu_name'] ? $data_n['eu_name'] : 'N/A') ?></td>
                                                                    <td><?= ($data_n['eu_email'] ? $data_n['eu_email'] : 'N/A') ?></td>
                                                                    <td><?= ($data_n['eu_mobile'] ? $data_n['eu_mobile'] : 'N/A') ?></td>
                                                                    <td><?= ($data_n['eu_designation'] ? $data_n['eu_designation'] : 'N/A') ?></a></td>
                                                                </tr>
                                                        <?php $i++;
                                                            }
                                                            echo "</tbody></table>";
                                                        } ?>
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
                                                <div class="col-md-12">
                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <td width="35%">Campaign Type</td>
                                                                <?php if (!empty($campaign)) { ?>
                                                                    <td><?= $campaign ?>
                                                                        <input type="hidden" name="delete_campaign" value="<?= $id ?>" id="delete_campaign">

                                                                        <button class="btn1 btn-primary" onclick="delete_campaign('<?= $campaign_type ?>','<?= $id ?>')">Delete</button>
                                                                    </td>

                                                                <?php  } else { ?>

                                                                    <td>
                                                                        <select name="campaign" class="form-control" onchange="update_campaign_type(this.value);" style="width: 25%;">
                                                                            <option value="">--Select--</option>
                                                                            <?php $date = date('Y-m-d');
                                                                            $query = db_query("select * from campaign where status=1 and start_date<='" . $date . "'and end_date>='" . $date . " ' and product_id=2");
                                                                            while ($row = db_fetch_array($query)) { ?>
                                                                                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                                                            <?php } ?>
                                                                        </select>

                                                                    </td>
                                                                <?php } ?>


                                                            </tr>
                                                            <tr>
                                                                <td width="35%">Type of License</td>
                                                                <td width="65%"><?= $license_type ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="35%">License Key</td>
                                                                <td width="65%"><?= $license_key ?></td>
                                                            </tr>
                                                            <?php
                                                            if (strtotime($data['license_end_date']) > strtotime(date('Y-m-d'))) {
                                                                $ed = '<span style="color:green">' . date('d-M-Y', strtotime($data['license_end_date'])) . '</span>';
                                                            } else {
                                                                $ed = '<span style="color:red">' . date('d-M-Y', strtotime($data['license_end_date'])) . '</span>';
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td width="35%">License End Date</td>
                                                                <td width="65%"><?= $ed ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Quantity</td>
                                                                <td id="quant">
                                                                    <?= $quantity ?> User(s) <button class="btn1 btn-primary" onclick="change_quantity('<?= $quantity ?>')">Edit</button>
                                                                    <input type="hidden" name="quantity" value="<?= $quantity ?>" id="qty">
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>Status</td>
                                                                <td>
                                                                    <?php if ($data['status'] == 'Cancelled') {
                                                                        echo '<span class="text-danger">Unqualified</span>';
                                                                    } else if ($data['status'] == 'Approved') {
                                                                        echo '<span class="text-success">Qualified</span>';
                                                                    } else if ($data['status'] == 'Undervalidation') {
                                                                        echo '<span class="text-warning">Under Validation</span>';
                                                                    } else if ($data['status'] == 'On-Hold') {
                                                                        echo '<span class="text-blue">On-Hold</span>';
                                                                    } else {
                                                                        echo '<span class="text">Pending</span>';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Closing Status</td>
                                                                <td>
                                                                    <?php if ($data['status'] == 'Approved') {
                                                                        $ncdate = strtotime(date('Y-m-d'));
                                                                        $closeDate = strtotime($data['close_time']);
                                                                        if ($ncdate > $closeDate) {
                                                                            $dayspassedafterExpired = ceil(abs($ncdate - $closeDate) / 86400);
                                                                            $daysLeft = '<span style=color:red;">Expired (' . $dayspassedafterExpired . ' Days Passed)</span>';
                                                                            if ($dayspassedafterExpired <= 30) $exp = 1;
                                                                        } else {

                                                                            $remaining_days = ceil(($closeDate - $ncdate) / 84600);
                                                                            $daysLeft = '<span style="color:green">Days Left- ' . $remaining_days . '</span>';
                                                                        }

                                                                        echo '<span style="color:green">Approved</span> ' . $daysLeft;
                                                                        if ($exp) {
                                                                    ?>
                                                                            <button onclick="relog(<?= $_GET['id'] ?>)" class="btn1 btn-warning">Re-Log</button>

                                                                    <?php
                                                                        }
                                                                    } else if ($data['status'] == 'Cancelled') {
                                                                        echo 'N/A';
                                                                    } else if ($data['status'] == 'Pending') {
                                                                        echo 'N/A';
                                                                    } else
                                                                        echo 'N/A';


                                                                    ?>

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Reason</td>
                                                                <td>
                                                                    <?= ($reason ? $reason : 'N/A') ?>
                                                                </td>
                                                            </tr>
                                                            <?php if ($user_attachement && $user_attachement != '' && strpos($user_attachement, ".")) { ?>
                                                                <tr>
                                                                    <td>Attachment</td>
                                                                    <td>
                                                                        <a href="<?= $admin_attachment ?>" download>View/Download</a>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                <?php } ?>

                                                                <tr>
                                                                    <td>Admin Comment</td>
                                                                    <td>
                                                                        <?= ($add_comment ? $add_comment : 'N/A') ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Created on</td>
                                                                    <td>
                                                                        <?= date('d-m-Y H:i:s', strtotime($created_date)) ?>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Runrate/Key</td>
                                                                    <td>
                                                                        <?= $runrate_key ?>
                                                                    </td>
                                                                </tr>

                                                                <?php /* if($lead_type=='LC') { ?>
                                        <tr>
                                         <td>Caller Comments</td>
                                            <td>
                                                                                         <?php      
                                                 $goal=db_query("select * from caller_comments where pid='".$_GET['id']."' order by created_date desc");
                                            $count=mysqli_num_rows($goal);
                                            $i=$count; if($count){ echo  ' <br/>'; while($data1=db_fetch_array($goal)) { ?>
                                                <?=$i.'. ['.getSingleresult("select name from users where id='".$data1['added_by']."'").' on '.date('d-m-Y H:i:s',strtotime($data1['created_date'])).']: <b>'.$data1['description'].'</b><br/>'?>
                                            <?php $i--; }  } ?>
                                        </td> <?php 
                                        </tr>?>
                                        <?php  }*/ ?>

                                                                <form action="#" method="post" name="form_view">
                                                                    <input type="hidden" id="hidden_sub_stage" name="sub_stage" value="">
                                                                    <?php if ($data['status'] == 'Approved') {
                                                                        $stage = $data['stage'] ?>

                                                                        <tr>
                                                                            <td>Stage</td>
                                                                            <?php if (getSingleresult("select count(id) from  lead_review where is_review IN (1,2) and lead_id='" . $data['id'] . "'")) { ?>
                                                                                <td><span class="text-danger">Under Review</span></td>
                                                                            <?php } else { ?>
                                                                                <td>
                                                                                    <select name="stage" onchange="chage_stage(this.value,'<?= $license_type ?>')" class="form-control">
                                                                                        <option value="">--Select--</option>
                                                                                        <?php $stage_sql = db_query("select * from stages where is_parallel=0");
                                                                                        while ($stage_data = db_fetch_array($stage_sql)) {
                                                                                        ?>
                                                                                            <option <?= (($stage == $stage_data['stage_name']) ? 'selected' : '') ?> value="<?= $stage_data['stage_name'] ?>"><?= $stage_data['stage_name'] ?></option>
                                                                                        <?php } ?>

                                                                                    </select>
                                                                                </td>
                                                                            <?php } ?>
                                                                        </tr>


                                                                        <tr id="add_comment" <?php if (!getSingleresult("select count(id) from sub_stage where stage_name='" . $stage . "'")) { ?> style="display:none" <?php } ?>>
                                                                            <td>Sub Stage</td>
                                                                            <td><select id="add_comm" name="sub_stage" onchange="payment_option(this.value)" class="form-control" />
                                                                                <option value="">--Select--</option>
                                                                                <?php $sstage_sql = db_query("select * from sub_stage where stage_name='" . $stage . "'");
                                                                                while ($sstage_data = db_fetch_array($sstage_sql)) {
                                                                                ?>
                                                                                    <option <?= (($add_comm == $sstage_data['name']) ? 'selected' : '') ?> value="<?= $sstage_data['name'] ?>"><?= $sstage_data['name'] ?></option>
                                                                                <?php } ?>
                                                                                </select>
                                                                            </td>
                                                                        </tr>

                                                                        <tr id="op" <?php if (($stage != 'EU PO Issued') || (($add_comm != '100% Advance Received')) && ($add_comm != 'Payment Against Delivery')) { ?> style="display:none" <?php } ?>>
                                                                            <td>Order Processing for this month</td>
                                                                            <td><input type="radio" name="op" value='Yes' <?= (($op_this_month == 'Yes') ? 'checked' : 'checked') ?> class="radio" id="opy" /><label for="opy">Yes</label><input <?= (($op_this_month == 'No') ? 'checked' : '') ?> type="radio" name="op" class="radio-col-red" value='No' id="opn" /><label for="opn">No</label></td>
                                                                        </tr>
                                                                        <tr id="pay_tab" <?php if ($data['add_comm'] != 'Payment in Installments') { ?> style="display:none" <?php } ?>>
                                                                            <td>Installment Details</td>
                                                                            <?php
                                                                            $inst_query = db_query("select * from installment_details where type='Lead' and pid='" . $_GET['id'] . "'");
                                                                            $inst_data = db_fetch_array($inst_query);

                                                                            ?>
                                                                            <td>
            <table style="clear: both; border:1px solid black !important" class="table table-bordered table-striped" width="100%">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td>
            <p><strong>Order Price:</strong></p>
                                                                                            </td>
                                                                                            <td>
            <input type="number" autocomplete="off" value="<?= $inst_data['order_price'] ?>" class="form-control" min="0" name="order_price" id='order_price' />
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
            <p><strong>1<sup>st</sup> Installment Date</strong></p>
                                                                                            </td>
                                                                                            <td>
            <input type="text" autocomplete="off" value="<?= $inst_data['date1'] ?>" class="form-control datepicker" name="date1" id='date1' />
                                                                                            </td>
                                                                                            <td>
            <p><strong>2<sup>nd</sup> Installment Date</strong></p>
                                                                                            </td>
                                                                                            <td>
            <input type="text" autocomplete="off" value="<?= $inst_data['date2'] ?>" class="form-control datepicker" name="date2" id='date2' />
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
            <p><strong>Installment Amount</strong></p>
                                                                                            </td>
                                                                                            <td>
            <input type="number" autocomplete="off" value='<?= $inst_data['instalment1'] ?>' class="form-control" name="instalment1" min="0" />
                                                                                            </td>
                                                                                            <td>
            <p><strong>Installment Amount</strong></p>
                                                                                            </td>
                                                                                            <td>
            <input type="number" autocomplete="off" value='<?= $inst_data['instalment2'] ?>' class="form-control" name="instalment2" min="0" />
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
            <p><strong>3<sup>rd</sup> Installment Date</strong></p>
                                                                                            </td>
                                                                                            <td>
                                                                                                <input type="text" autocomplete="off" value='<?= $inst_data['date3'] ?>' class="form-control datepicker" name="date3" id='date3' />
                                                                                            </td>
                                                                                            <td>
                                                                                                <p><strong>4<sup>th</sup> Installment Date</strong></p>
                                                                                            </td>
                                                                                            <td>
                                                                                                <input type="text" autocomplete="off" value='<?= $inst_data['date4'] ?>' class="form-control datepicker" name="date4" id='date4' />
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <p><strong>Installment Amount</strong></p>
                                                                                            </td>
                                                                                            <td>
                                                                                                <input type="number" autocomplete="off" value='<?= $inst_data['instalment3'] ?>' class="form-control" name="instalment3" min="0" />
                                                                                            </td>
                                                                                            <td>
                                                                                                <p><strong>Installment Amount</strong></p>
                                                                                            </td>
                                                                                            <td>
                                                                                                <input type="number" autocomplete="off" value='<?= $inst_data['instalment4'] ?>' class="form-control" name="instalment4" min="0" />
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td>
                                                                                                <p><strong>5<sup>th</sup> Installment Date</strong></p>
                                                                                            </td>
                                                                                            <td>
                                                                                                <input type="text" autocomplete="off" value='<?= $inst_data['date5'] ?>' class="form-control datepicker" name="date5" id='date5' />
                                                                                            </td>
                                                                                            <td>
                                                                                                <p><strong>6<sup>th</sup> Installment Date</strong></p>
                                                                                            </td>
                                                                                            <td>
                                                                                                <input type="text" autocomplete="off" value='<?= $inst_data['date6'] ?>' class="form-control datepicker" name="date6" id='date6' />
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <p><strong>Installment Amount</strong></p>
                                                                                            </td>
                                                                                            <td>
                                                                                                <input type="number" autocomplete="off" value='<?= $inst_data['installment5'] ?>' class="form-control" name="instalment5" min="0" />
                                                                                            </td>
                                                                                            <td>
                                                                                                <p><strong>Installment Amount</strong></p>
                                                                                            </td>
                                                                                            <td>
                                                                                                <input type="number" autocomplete="off" value='<?= $inst_data['installment6'] ?>' class="form-control" name="instalment6" min="0" />
                                                                                            </td>
                                                                                        </tr>

                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>

                                                                    <?php } ?>
                                                                    <tr>
                                                                        <td>Projected Close Date</td>
                                                                        <td><input type="text" value="<?= $partner_close_date ?>" class="form-control datepicker" id="cl_date" name="partner_close_date" /></td>
                                                                    </tr>



                                                        </tbody>
														
														
														
														
                                                    </table>
													
													
													
													
                                                </div>

                                            </div>


                                        </div>

                                    
  </div>
                                  
                                        <div class="card mb-0 pt-2 shadow-none">
                                            <div class="card-header" id="headingOne8">
                                                <h5 class="my-0">
                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne8" aria-expanded="true" aria-controls="collapseOne8">
                                                        Activity History
                                                    </button>
                                                </h5>
												<a data-toggle="modal" onclick="add_activity(<?= $_GET['id'] ?>,'<?= $company_name ?>')" data-animation="bounce" data-target=".bs-example-modal-center"><button class="btn1 btn-primary mt-2">Log a Call</button></a>
                                            </div>

                                            <div id="collapseOne8" class="" aria-labelledby="headingOne8" data-parent="#accordionExample2">

                                                



                                                <div class="row">
                                                    <div class="col-md-12">


                                                        <?php
                                                        $new = db_query("select id,description,created_date,added_by,call_subject from activity_log where pid='" . $_GET['id'] . "' order by created_date desc");
                                                        $goal = db_query("select * from activity_log where pid='" . $_GET['id'] . "' order by created_date desc");
                                                        $count = mysqli_num_rows($new);
                                                        $i = $count;
                                                        if ($count) {
                                                            echo  ' <table class="table"><thead class="thead-default"><tr><th>S.No</th><th>Subject</th><th>Description</th><th>Added By</th><th>Date</th></tr></thead>';

                                                            while ($data_n = db_fetch_array($new)) { ?>
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="center"><?= $i ?></td>
                                                                        <td align="center"><?= ($data_n['call_subject'] ? $data_n['call_subject'] : 'N/A') ?></td>
                                                                        <td align="center"><?= $data_n['description'] ?></td>
                                                                        <td><?= (is_numeric($data_n['added_by']) ? getSingleresult("select concat(name,'-',CASE  WHEN user_type='USR' THEN 'Partner User' WHEN user_type='RENEWAL TL' OR user_type='EM' OR user_type='RM'THEN 'Renewal Manager' WHEN user_type='ADMIN' and sales_manager=0 THEN 'ADMIN' WHEN user_type='ADMIN' and sales_manager=1 THEN 'Sales Manager'   WHEN user_type='MNGR' THEN 'Partner Manager'  ELSE 'Caller' END) as nme from users where id='" . $data_n['added_by'] . "'") : '<span style="color:red">(Lead Review!)</span> ' . $data_n['added_by']) ?></td>
                                                                        <td align="center"><?= date('d-m-Y H:i:s', strtotime($data_n['created_date'])) ?></td>
                                                                        <!-- <td><a href="javascript:void(0)" title="Edit" id=but<?= $data['id'] ?> onclick="edit_activity('<?= $data_n['id'] ?>')"> <i style="font-size:18px" class="mdi mdi-pencil"></i></a></td> -->
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

                                        <button type="submit" id="save_button" onclick="return checkSubstage()" class="btn1 btn-primary">Save</button>
                                        <?php
                                        $userId = getSingleresult("select created_by from orders where id=" . $_GET['id']);
                                        ?>

                                        </form>
                                        <?php if ($data['status'] != 'Approved' && ($userId == $_SESSION['user_id'] || $_SESSION['user_type']=='MNGR')) { ?>
                                            <a href="edit_order.php?eid=<?= $id ?>"><button type="button" class="btn1 btn-primary">Edit</button></a>
                                        <?php } ?>
                                        <button type="button" onclick="javascript:history.go(-1)" class="btn1 btn-danger">Back</button></td>

                                    </div>

                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div>

                </div> <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
</div>
        <div id="myModal" class="modal fade" role="dialog">

        </div>

        <!-- No Stage Self Review -->


        <!----end Quote stage-->

        <?php include('includes/footer.php') ?>

        <?php if (mysqli_num_rows(db_query("select * from tbl_lead_product where lead_id=" . $_REQUEST['id'])) == 0) { ?>
            <script>
                var id = $('#edit_id').val();
                var company = $('#company').val();
                var qty = $('#qty').val();
                //alert(id);
                $(function() {
                    $.ajax({
                        type: 'POST',
                        url: 'lead_product_renewal.php',
                        data: {
                            renewal_id: id,
                            company: company,
                            qty: qty
                        },
                        success: function(response) {
                            $("#selfReview").html();
                            $("#selfReview").html(response);
                            $('#selfReview').modal('show');

                        }
                    });
                });
            </script>
        <?php } ?>

        <script>
            function chage_stage(a, license) {
                //alert('abc');
                if (a == 'Commit') {
                    swal("Commit stage can be updated through Manager Access only!", "", "warning");
                    $("#save_button").prop('disabled', true);
                } else {
                    $("#save_button").prop('disabled', false);
                    $("#op").hide();
                    $("#pay_tab").hide();
                    if (a) {
                        $.ajax({
                            type: 'POST',
                            // url: 'get_sub_stage_for_renwal.php',
                            url: 'get_sub_stage.php',
                            data: {
                                stage: a,
                                license: license
                            },
                            success: function(html) {
                                // alert(html);
                                console.log(html);
                                if (html) {
                                    $('#add_comment').html(html);
                                    $('#add_comment').show();
                                } else {
                                    $('#add_comment').hide();
                                }
                            }
                        });
                    }
                }



            }

            function payment_option(val) {

                $('#hidden_sub_stage').val(val);

                if (val == '100% Advance Received' || val == 'Payment Against Delivery') {
                    $("#op").show();
                    $("#pay_tab").hide();
                } else if (val == 'Payment in Installments') {
                    $("#pay_tab").show();
                    $("#op").hide();
                } else if (val == 'Not Clear' || val == '') {
                    //alert(12);
                    ("#op").hide();
                    $("#pay_tab").hide();

                }
            }


            function delete_campaign(campaign_type, id) {
                //alert(id);
                swal({
                    title: "Are you sure?",
                    text: "You want to delete campaign for this lead!",
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
                            url: "update_quantity.php?campaign_id=<?= $_GET['id'] ?>",
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

            function update_campaign_type(a) {
                if (a) {
                    swal({
                        title: "Are you sure?",
                        text: "You want to update Campaign Type!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, convert it!",
                        cancelButtonText: "No, cancel modification!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }, function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "update_quantity.php?oid=<?= $_GET['id'] ?>&type=" + a,
                                success: function(result) {
                                    if (result) {
                                        swal({
                                            title: "Done!",
                                            text: "Lead converted.",
                                            type: "success"
                                        }, function() {
                                            window.location = "partner_renewal_view.php?id=<?= $_GET['id'] ?>";
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


            function add_activity(a, company_name) {
                $.ajax({
                    type: 'POST',
                    url: 'add_activity_renewal.php',
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
                        $("#ltype_dd").prop('required', true);
                    } else {
                        $("#sub_btn").hide();
                        $("#ltype").hide();
                        $("#ltype_dd").prop('required', false);
                    }
                });


            });

            $(function() {
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    forceParse: false,
                    autoclose: !0

                });

            });

            function change_quantity(a) {
                document.getElementById("quant").innerHTML = '<input type="text" value="' + a + '" id="new_quantity"/> <button onclick="save_newqty()" class="btn1 btn-primary">Save</button>'

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


            function change_product_type(rl_id, type) {
                //alert(id);
                //alert(type);
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
                                rl_id: rl_id,
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
        </script>

        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.add_lead').height(wfheight - 220);
            });
        </script>

        <script>
            function checkSubstage() {
                var substage = $('#add_comment_dd :selected').val();
                if (substage == '') {
                    swal('Please select sub stage first');
                    return false;
                } else {
                    return true;
                }

            }
        </script>