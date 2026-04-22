<?php include('includes/header.php'); 

$sql = db_query("select * from orders where id='" . $_REQUEST['id'] . "'");
$row_data = db_fetch_array($sql);

if ($row_data['status'] == 'Undervalidation') {
    if ($_POST['remarks'] || (!$_POST['activity_edit'] && $_POST['call_subject'])) {
        $res = db_query("update  `orders` set status='Pending',created_date=now() where id='" . $_REQUEST['id'] . "'");

        // $activity_select = db_query("select * from activity log where pid=".$_POST['pid']);
        // $activity_data = db_fetch_array($activity_select);

        // $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Log a Call','" . $row_data['status'] . "','Pending',now(),'" . $_SESSION['user_id'] . "')");

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
    redir("partner_view.php?id=" . $_POST['id'], true);
}


if ($_POST['partner_close_date'] && !$_POST['stage']) {
    if ($row_data['partner_close_date'] != $_POST['partner_close_date']) {
        $modify_name = $_POST['partner_close_date'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Close Date','" . $row_data['partner_close_date'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    $res = db_query("update orders set partner_close_date='" . $_POST['partner_close_date'] . "' where id='" . $_GET['id'] . "'");

    redir("orders.php?update=success", true);
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
   
    $res = "update orders set stage='" . $_POST['stage'] . "',prospecting_date='" . date('Y-m-d') . "',payment_status='" . $_POST['payment_status'] . "',add_comm='" . $_POST['add_comm'] . "',add_Parallelcomm='" . $_POST['add_Pcomm'] . "',partner_close_date='" . $_POST['partner_close_date'] . "' where id='" . $_GET['id'] . "'";
    // db_query("update orders set stage='" . $_POST['stage'] . "',prospecting_date='" . date('Y-m-d') . "',payment_status='" . $_POST['payment_status'] . "',add_comm='" . $_POST['add_comm'] . "',add_Parallelcomm='" . $_POST['add_Pcomm'] . "',partner_close_date='" . $_POST['partner_close_date'] . "' where id='" . $_GET['id'] . "'");
    print_r($res);
    die;
    if ($_POST['payment_status'] == 'Payment in Installments') {
        $ps = db_query("insert into installment_details (`pid`, `type`, `date1`, `instalment1`, `date2`, `instalment2`, `date3`, `instalment3`, `date4`, `instalment4`, `added_by`) values ('" . $_GET['id'] . "','Lead','" . $_POST['date1'] . "','" . $_POST['instalment1'] . "','" . $_POST['date2'] . "','" . $_POST['instalment2'] . "','" . $_POST['date3'] . "','" . $_POST['instalment3'] . "','" . $_POST['date4'] . "','" . $_POST['instalment4'] . "','" . $_SESSION['user_id'] . "')");
    } else if ($_POST['stage'] == 'EU PO Issued') {
        $ps = db_query("update orders set op_this_month='" . $_POST['op'] . "' where id='" . $_GET['id'] . "'");
    }


    redir("orders.php?update=success", true);
}
if ($_POST['submit_dvr']) {
    $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Lead','Lead','DVR',now(),'" . $_SESSION['user_id'] . "')");
    $sql = db_query("update orders set dvr_flag=0,is_dr=1,convert_date='" . date('Y-m-d H:i:s') . "',dvr_by='" . $_SESSION['user_id'] . "',date_dvr='" . date('Y-m-d H:i:s') . "' where id=" . $_GET['id']);
    redir("orders.php?update=success", true);
}
if ($_POST['remarks'] && !$_POST['activity_edit']) {
    $res = db_query("insert into activity_log (pid,description,activity_type,call_subject,added_by) values ('" . $_POST['pid'] . "','" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "','Lead','" . $_POST['call_subject'] . "','" . $_SESSION['user_id'] . "')");
    $email = db_query("select r_email,r_name,r_user,lead_type,company_name,eu_email,eu_mobile,team_id,quantity,partner_close_date,caller from orders where id=" . $_POST['pid']);
    $data = db_fetch_array($email);
    $mail->AddCC("prashant.dongrikar@arkinfo.in", "Prashant");
    $mail->AddCC("kailash.bhurke@arkinfo.in");
    $mail->AddBCC("deepranshu.srivastava@arkinfo.in", "Deepranshu Srivastava");
    if ($data['lead_type'] == 'LC') {
        if ($data['caller'] != '') {
            $caller_email1 = db_query("select users.email as call_email from users join callers on users.id=callers.user_id where callers.id=" . $data['caller']);
            $caller_email = db_fetch_array($caller_email1);
            $mail->AddAddress($caller_email['call_email']);
        }

        $manager_email = getSingleresult("select email from users where user_type='MNGR' and team_id=" . $data['team_id']);
        $mail->AddCC($_SESSION['email']);
        $mail->AddCC($manager_email);
        $mail->AddCC("virendra@corelindia.co.in");
        //$mail->AddCC("maneesh.kumar@arkinfo.in");
        $mail->Subject = $data['company_name'] . " - New Log a Call";
        $mail->Body    = "Hello,<br><br> There is new log a call from " . $_SESSION['name'] . " on SketchUp DR Portal with details as below:-<br><br>
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
    } else {
        $manager_email = getSingleresult("select email from users where user_type='MNGR' and team_id=" . $data['team_id']);

        $mail->AddAddress($manager_email);
        $mail->AddCC($data['r_email']);
        $mail->AddCC($_SESSION['email']);
        //$mail->AddCC("maneesh.kumar@arkinfo.in");
        $mail->Subject = $data['company_name'] . " - New Log a Call";
        $mail->Body    = "Hello,<br><br> There is new log a call from " . $_SESSION['name'] . " on SketchUp DR Portal with details as below:-<br><br>
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
    }
    // $mail->Send();
    $mail->ClearAllRecipients();
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
    redir("manage_orders.php", true);
}
?>
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">View Lead</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">View Lead</li>
                </ol>
            </div>
            <div class="col-md-7 col-4 align-self-center">
                <?php if ($_SESSION['user_type'] == 'MNGR' && $lead_type == 'LC' && $caller) { ?>
                    <div class="">
                        <a href="#" id="addAction"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="Add New Action" class="right-side bottom-right waves-effect waves-light btn-primary btn btn-circle btn-md pull-right m-l-20"><i class="ti-plus text-white"></i></button></a>

                    </div>
                <?php } ?>
                <div class="">
                    <a href="#" id="addCopy"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="Copy Lead as New" class="right-side bottom-right waves-effect waves-light btn-primary btn btn-circle btn-md pull-right m-l-10"><i class="ti-reload text-white"></i></button></a>

                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->

        <div class="row partner_lead_log">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Lead Modify Log</h4>
                        <button id="modify_log" class="btn btn-primary pull-right" style="margin-top: -28px;"> Show </button>
                        <h6 class="card-subtitle"></h6>
                        <div id="modify_log_div">

                            <ul class="font_weight list-style-none">
                                <?php if ($data['lapsed_date'] && $data['lapsed_date'] != '0000-00-00 00:00:00') { ?>
                                    <li>Lapsed on <strong><?= date('F j, Y, g:i a', strtotime($data['lapsed_date'])) ?></strong></li>

                                    <?php
                                }
                                $sql = db_query("select * from lead_modify_log where log_status='Active' AND lead_id=" . $_REQUEST['id'] . " order by id desc");


                                if (db_num_array($sql) > 0) {

                                    while ($data1 = db_fetch_array($sql)) { ?>

                                        <li> <?= getSingleresult("select name from users where id=" . $data1['created_by']) . (($data1['type'] != 'Request Status' && $data1['type'] != 'Request Delete Status') ? (' has changed <strong>' . $data1['type'] . ' </strong>') : (($data1['type'] == 'Request Status') ? ' <strong>has requested Status Change</strong>' : ' <strong>has deleted Status Change Request</strong>')) ?> from <strong> <?= ($data1['previous_name'] ? $data1['previous_name'] : 'N/A') ?> </strong> to <strong> <?= $data1['modify_name'] ?></strong> on <?= date('F j, Y, g:i a', strtotime($data1['created_date'])) ?>.
                                        </li>


                                <?php $count++;
                                    }
                                } ?>



                                <li>Created by <strong><?= getSingleresult("select name from users where id=" . $created_by) ?></strong> on <strong><?= date('F j, Y, g:i a', strtotime($created_date)) ?></strong></li>


                            </ul>

                        </div>

                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Partner Info</h4>
                        <h6 class="card-subtitle"></h6>
                        <table style="clear: both" class="table table-bordered table-striped" id="user">
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
                                        <?= $r_user ?> <?php if ($created_by == $_SESSION['user_id'] || $_SESSION['user_type'] == 'MNGR') { ?> <button class="btn btn-primary" onclick="change_user('<?= $_GET['id'] ?>','<?= $_SESSION['team_id'] ?>')">Change</button> <?php } ?>

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
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Product Info</h4>
                        <h6 class="card-subtitle"></h6>
                        <table style="clear: both" class="table table-bordered table-striped" id="user">
                            <tbody>
                                <tr>
                                    <td width="35%">Product Name</td>
                                    <td width="65%"><?= $product_name ?></td>
                                    <input type="hidden" name="product_id" id="product_id" value="<?= $product_name ?>" />
                                </tr>
                                <tr>
                                    <td>Product Type</td>
                                    <td>
                                        <?= $product_type ?>&nbsp;

                                        <?php if ($product_name == 'CDGS Fresh' && ($product_type == 'CDGS Perpetual' || $product_type == 'CDGS Annual')) { ?>
                                            <button class="btn btn-primary" onclick="change_product_type('<?= $_GET['id'] ?>','<?= $type_id ?>')">Change Product Type</button>
                                        <?php } ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Customer Information</h4>
                        <h6 class="card-subtitle"></h6>
                        <table style="clear: both" class="table table-bordered table-striped" id="user">
                            <tbody>
                                <tr>
                                    <td><strong>Close Date</strong></td>
                                    <td><strong><?= $partner_close_date ?></strong></td>
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
                                        <?= $company_name ?> </td>
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
                                        <?= getSingleresult("select name from industry where id=" . $industry) ?>
                                    </td>
                                </tr>
                                <?php if ($sub_industry) { ?><tr>
                                        <td>Sub Industry</td>
                                        <td>
                                            <?= getSingleresult("select name from sub_industry where id=" . $sub_industry) ?>
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
                                        <?= getSingleresult("select name from states where id=" . $state) ?>
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
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Decision Maker/Proprietor/Director/End User Details</h4>
                        <h6 class="card-subtitle"></h6>
                        <table style="clear: both" class="table table-bordered table-striped" id="user">
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
                                <?php if ($form_id == 0) { ?>
                                    <tr>
                                        <td>Department</td>
                                        <td>
                                            <?= $department ?>
                                        </td>
                                    </tr>
                                <?php } ?>
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
                                    <td>Role</td>
                                    <td>
                                        <?= $eu_role ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Account Visited</td>
                                    <td>
                                        <?= $account_visited ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Visit/Profiling Remarks</td>
                                    <td>


                                        <?php
                                        $new = db_query("select id,description,created_date,added_by,call_subject from activity_log where pid='" . $_GET['id'] . "' UNION SELECT id,description,created_date,added_by,id from caller_comments where pid='" . $_GET['id'] . "' union select id,comment as description,created_date,added_by,id from review_log where lead_id='" . $_GET['id'] . "' order by created_date desc");
                                        $goal = db_query("select * from activity_log where pid='" . $_GET['id'] . "' order by created_date desc");
                                        $count = mysqli_num_rows($new);
                                        $i = $count;
                                        if ($count) {
                                            echo  ' <table class="col-12"><tr><th>S.No</th><th>Subject</th><th>Description</th><th>Added By</th><th>Date</th></tr>';

                                            while ($data_n = db_fetch_array($new)) { ?>

                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= ($data_n['call_subject'] ? $data_n['call_subject'] : 'N/A') ?></td>
                                    <td><?= $data_n['description'] ?></td>
                                    <td><?= (is_numeric($data_n['added_by']) ? getSingleresult("select concat(name,'-',CASE  WHEN user_type='USR' THEN 'Partner User'    WHEN user_type='ADMIN' and sales_manager=0 THEN 'ADMIN' WHEN user_type='ADMIN' and sales_manager=1 THEN 'Sales Manager'   WHEN user_type='MNGR' THEN 'Partner Manager'  ELSE 'Caller' END) as nme from users where id='" . $data_n['added_by'] . "'") : '<span style="color:red">(Lead Review!)</span> ' . $data_n['added_by']) ?></td>
                                    <td><?= date('d-m-Y H:i:s', strtotime($data_n['created_date'])) ?></td>
                                    <!-- <td><a href="javascript:void(0)" title="Edit" id=but<?= $data['id'] ?> onclick="edit_activity('<?= $data_n['id'] ?>')"> <i style="font-size:18px" class="mdi mdi-pencil"></i></a></td> -->
                                </tr>
                        <?php $i--;
                                            }
                                            echo "</table>";
                                        } ?>

                        <?= date('d-m-Y H:i:s', strtotime($created_date)) ?>: <?= $visit_remarks ?>

                        <button onclick="add_activity(<?= $_GET['id'] ?>)" class="btn btn-primary">Log a Call</button>&nbsp;
                        </td>
                        </tr>
                        <?php if ($form_id == 0) { ?>
                            <tr>
                                <td>Usage Confirmation Received from</td>
                                <td>
                                    <?= $confirmation_from ?>
                                </td>
                            </tr>
                        <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Other Contacts</h4>
                        <h6 class="card-subtitle"></h6>
                        <?php $query = leadContactData('tbl_lead_contact', $_REQUEST['id']);
                        $count = mysqli_num_rows($query);
                        $i = 1;
                        if ($count) {
                            echo  ' <table class="col-12"><tr><th>S.No</th><th>Name</th><th>Email</th><th>Mobile</th><th>Designation</th></tr>';
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
                            echo "</table>";
                        } ?>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Lead Information</h4>
                        <h6 class="card-subtitle"></h6>
                        <table style="clear: both" class="table table-bordered table-striped" id="user">
                            <tbody>
                                <tr>
                                    <td width="35%">Campaign</td>
                                    <td width="65%"><?= $campaign ?></td>
                                </tr>
                                <tr>
                                    <td width="35%">Type of License</td>
                                    <td width="65%"><?= $license_type ?></td>
                                </tr>
                                <?php $query = db_query("SELECT lead_id,GROUP_CONCAT(existing_IT),GROUP_CONCAT(app_usage) FROM tbl_lead_product where lead_id=" . $_REQUEST['id'] . " GROUP BY lead_id");
                                $row = db_fetch_array($query);
                                if ($form_id == 1) { ?>
                                    <tr>
                                        <td width="35%">Existing IT / Infrastructure</td>
                                        <td width="65%"><?= rtrim($row['GROUP_CONCAT(existing_IT)'], ',') ?></td>
                                    </tr>
                                    <tr>
                                        <td width="35%">Application Usage</td>
                                        <td width="65%"><?= rtrim($row['GROUP_CONCAT(app_usage)'], ',') ?></td>
                                    </tr>
                                <?php } else { ?>
                                    <tr>
                                        <td width="35%">OS</td>
                                        <td width="65%"><?= $os ?></td>
                                    </tr>
                                    <tr>
                                        <td width="35%">Version</td>
                                        <td width="65%"><?= $version ?></td>
                                    </tr>
                                    <tr>
                                        <td width="35%">Runrate/Key</td>
                                        <td width="65%"><?= $runrate_key ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td>Quantity</td>
                                    <td id="quant">
                                        <?= $quantity ?> User(s)
                                        <?php if ($created_by == $_SESSION['user_id']) { ?>
                                            <button class="btn btn-primary" onclick="change_quantity('<?= $quantity ?>')">Edit</button>
                                        <?php } ?>
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
                                                <button onclick="relog(<?= $_GET['id'] ?>)" class="btn btn-warning">Re-Log</button>

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
                                <?php if ($admin_attachment) { ?>
                                    <tr>
                                        <td>Attachment</td>
                                        <td>
                                            <a href="<?= $admin_attachment ?>" download>View/Download</a>
                                        </td>
                                    </tr>
                                    <tr>
                                    <?php } ?>
                                    <tr>
                                        <td>Reason</td>
                                        <td>
                                            <?= ($reason ? $reason : 'N/A') ?>
                                        </td>
                                    </tr>
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
                                    <?php if ($lead_type == 'LC') { ?>
                                        <tr>
                                            <td>Caller Assigned</td>
                                            <td>
                                                <?php if (is_numeric($caller)) { ?>
                                                    <?= getSingleresult("select name from callers where id='" . $caller . "'") ?>
                                                <?php } else { ?>
                                                    <?= $caller ?>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
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
                                    <tr>
                                        <?php if ($_SESSION['user_type'] != 'ADMIN' && $_SESSION['user_type'] != 'SUPERADMIN' && $_SESSION['user_type'] != 'OPERATIONS' && $_SESSION['role'] != 'TC') { ?>
                                    <tr>
                                        <td>Add as DVR</td>
                                        <td>
                                            <form name="update_dvr" action="#" method='post'>
                                                <input name="submit_dvr" value="1" type="checkbox" required id="md_checkbox_21" class="filled-in check-col-pink">
                                                <label for="md_checkbox_21"></label>
                                                <button id="sub_btn" style="display:none; position: relative; top:-20px" type="submit" name="save" onclick="" class="btn btn-primary">Submit as DVR</button>
                                            </form>

                                        </td>
                                    </tr>
                                <?php } ?>
                                <form action="#" method="post" name="form_view">
                                    <?php if ($data['status'] == 'Approved' || $data['status'] == 'Cancelled') {
                                        $stage = $data['stage'] ?>

                                        <tr>
                                            <?php $product_stage = db_query("select distinct(form_id) from tbl_lead_product where lead_id=" . $data['id']);
                                        //print_r($product_stage);

                                        $p_stage = db_fetch_array($product_stage); ?>

                                            <td>Stage</td>
                                            <?php if (getSingleresult("select count(id) from  lead_review where is_review IN (1,2) and lead_id='" . $data['id'] . "'")) { ?>
                                                <td><span class="text-danger">Under Review</span></td>
                                            <?php } else { ?>
                                                <td>
                                                    <select name="stage" onchange="chage_stage(this.value,<?= $data['id'] ?>)" class="form-control">
                                                        <option value="">--Select--</option>
                                                        <?php if ($p_stage['form_id'] == 1) { $stage_sql = db_query("select * from stages where 1");
                                                        }else {
                                                            $stage_sql = db_query("select * from stages where 1 and is_parallel = 0");
                                                        }
                                                        while ($stage_data = db_fetch_array($stage_sql)) {
                                                        ?>
                                                            <option <?= (($stage == $stage_data['stage_name']) ? 'selected' : '') ?> value="<?= $stage_data['stage_name'] ?>"><?= $stage_data['stage_name'] ?></option>
                                                        <?php } ?>

                                                    </select>
                                                </td>
                                            <?php } ?>
                                        </tr>

                                        <?php 
                                        if ($p_stage['form_id'] == 1) {
                                            $sstage_sql = db_query("select * from sub_stage where stage_name='" . $stage . "'");
                                        } else {
                                            $sstage_sql = db_query("select * from sub_stage where is_parallel = 0 and stage_name='" . $stage . "'");
                                        }
                                        ?>
                                        <tr id="add_comment" <?php if (!getSingleresult("select count(id) from sub_stage where stage_name='" . $stage . "'")) { ?> style="display:none" <?php } ?>>
                                            <td>Sub Stage</td>
                                            <td>
                                            
                                            <select id="add_comment_dd" name="add_comm" onchange="payment_option(this.value,<?= $data['id'] ?>)" class="form-control">
                                            
                                                    <option value="">--Select--</option>
                                                    <?php 
                                                    while ($sstage_data = db_fetch_array($sstage_sql)) {
                                                    ?>
                                                        <option <?= (($add_comm == $sstage_data['name']) ? 'selected' : '') ?> value="<?= $sstage_data['name'] ?>"><?= $sstage_data['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr id="add_Pcomment">
                                            <?php if ($add_comm == 'Lost to competition') { ?>
                                                <td>List of Products</td>
                                                <td><select id="add_Pcomment_dd" name="add_Pcomm" class="form-control">
                                                        <option value="">--Select--</option>
                                                        <option value="Citrix" <?= $add_Parallelcomm == 'Citrix' ? 'selected' : '' ?>>Citrix</option>
                                                        <option value="Vmware" <?= $add_Parallelcomm == 'Vmware' ? 'selected' : '' ?>>Vmware</option>
                                                        <option value="Microsoft" <?= $add_Parallelcomm == 'Microsoft' ? 'selected' : '' ?>>Microsoft</option>
                                                        <option value="Terminal Services Plus" <?= $add_Parallelcomm == 'Terminal Services Plus' ? 'selected' : '' ?>>Terminal Services Plus</option>
                                                        <option value="Accops" <?= $add_Parallelcomm == 'Accops' ? 'selected' : '' ?>>Accops</option>

                                                    </select>
                                                </td>
                                            <?php } ?>

                                        </tr>
                                        <tr id="op" <?php if (!$op_this_month) { ?> style="display:none" <?php } ?>>
                                            <td>Order Processing for this month</td>
                                            <td><input type="radio" name="op" value='Yes' <?= (($op_this_month == 'Yes') ? 'checked' : 'checked') ?> class="radio" id="opy" /><label for="opy">Yes</label><input <?= (($op_this_month == 'No') ? 'checked' : '') ?> type="radio" name="op" class="radio-col-red" value='No' id="opn" /><label for="opn">No</label></td>
                                        </tr>
                                        <tr id="pay_tab" <?php if ($add_comm != 'Payment in Installments') { ?> style="display:none" <?php } ?>>
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
                                                                <p><strong>1<sup>st</sup> Installment Date</strong></p>
                                                            </td>
                                                            <td>
                                                                <input type="text" autocomplete="off" value="<?= $inst_data['date1'] ?>" class="form-control datepicker1" name="date1" id='date1' />
                                                            </td>
                                                            <td>
                                                                <p><strong>2<sup>nd</sup> Installment Date</strong></p>
                                                            </td>
                                                            <td>
                                                                <input type="text" autocomplete="off" value="<?= $inst_data['date2'] ?>" class="form-control datepicker1" name="date2" id='date2' />
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
                                                                <input type="text" autocomplete="off" value='<?= $inst_data['date3'] ?>' class="form-control datepicker1" name="date3" id='date3' />
                                                            </td>
                                                            <td>
                                                                <p><strong>4<sup>th</sup> Installment Date</strong></p>
                                                            </td>
                                                            <td>
                                                                <input type="text" autocomplete="off" value='<?= $inst_data['date4'] ?>' class="form-control datepicker1" name="date4" id='date4' />
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
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>

                                    <?php } ?>
                                    <tr>
                                        <td>Projected Close Date</td>
                                        <td><input type="text" value="<?= $partner_close_date ?>" class="form-control datepicker" id="cl_date" readonly name="partner_close_date" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan='2'>

                                            <button type="submit" name="save_btn" id="save_button"  class="btn btn-primary">Save</button>

                                </form>
                                <?php if ($data['status'] != 'Approved' || strstr($company_name, 'One Version Downgrade')) {
                                    if ($data['status'] == 'Undervalidation' && $created_by == $_SESSION['user_id']) { ?>
                                        <a href="edit_order.php?eid=<?= $id ?>"><button type="button" class="btn btn-primary">Edit</button></a>
                                <?php }
                                } ?>
                                <button type="button" onclick="window.location.replace(document.referrer)" class="btn btn-inverse">Back</button></td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right sidebar -->
        <!-- ============================================================== -->
        <!-- .right-sidebar -->

        <!-- ============================================================== -->
        <!-- End Right sidebar -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <div id="myModal" class="modal fade" role="dialog">


    </div>

    <!-- No Stage Self Review -->

    <div id="no_stage_review" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title ">Self Review</h4>
                    <button class="close modalMinimize"> <i class='fa fa-minus'></i> </button>

                </div>
                <div class="modal-body">
                    <h3>Can you see a remarks from LC Calling team? </h3>
                    <button class="btn btn-primary" onclick="lc_yes()">Yes</button>
                    <button class="btn btn-danger" onclick="lc_no()">No</button>
                    <div id="review_yes" class="row col-10 m-t-10" style="display:none">
                        <select name="stage" id="lc_stage" class="form-control">
                            <option value="License Compliance">License Compliance</option>
                        </select>

                        <button type="submit" onclick="save_lc_yes()" class="btn btn-success m-t-10 float-right">Save</button>
                    </div>
                </div>


            </div>

        </div>

    </div>

    <!-- end no stage sr deepranshu -->
    <!-- LC Stage----------->
    <div id="lc_stage_review" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title ">Self Review</h4>
                    <button class="close modalMinimize"> <i class='fa fa-minus'></i> </button>

                </div>
                <div class="modal-body">
                    <h3>Is Quote Shared? </h3>
                    <button class="btn btn-primary" onclick="yes_lc_stage()">Yes</button>
                    <button class="btn btn-danger" onclick="no_lc_stage()">No</button>
                    <div id="lc_stage_yes" class="row col-10 m-t-10" style="display:none">
                        <select name="stage" id="quote_stage" class="form-control">
                            <option value="Quote">Quote</option>
                        </select>

                        <button type="submit" onclick="save_lc_yes()" class="btn btn-success m-t-10 float-right">Save</button>
                    </div>
                    <div id="lc_stage_no" class="row col-10 m-t-10" style="display:none">
                        <select name="action" id="action_quote" class="form-control">
                            <option value="Customer still not positive">Customer still not positive</option>
                            <option value="Quote pending from our end">Quote pending from our end</option>
                            <option value="Customer denied">Customer denied</option>

                        </select>

                        <button type="submit" onclick="no_lc_stage_action()" class="btn btn-success m-t-10 float-right">Save</button>
                    </div>


                </div>


            </div>

        </div>

    </div>
    <!----end lc stage-->
    <!-- Quote Stage----------->
    <div id="quote_stage_review" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title ">Self Review</h4>
                    <button class="close modalMinimize"> <i class='fa fa-minus'></i> </button>

                </div>
                <div class="modal-body">
                    <h3>What&apos;s the feedback after quotation? </h3>
                    <button class="btn btn-primary" onclick="yes_quote_stage()">Yes</button>
                    <button class="btn btn-danger" onclick="no_quote_stage()">No</button>
                    <div id="quote_stage_yes" class="row col-10 m-t-10" style="display:none">
                        <select name="stage" id="fup_stage" class="form-control">
                            <option value="Customer asked to call back">Customer asked to call back</option>
                            <option value="Customer is not responding, we will try again">Customer is not responding, we will try again</option>
                            <option value="Customer is not responding, need help from LC member">Customer is not responding, need help from LC member</option>
                            <option value="Customer is positive">Customer is positive</option>
                            <option value="Looking for best price">Looking for best price</option>
                            <option value="Customer denied">Customer denied</option>

                        </select>
                        <div class="row col-12 m-t-10" id="follow_date" style="display:none">
                            <label for="">Select Follow-Up Date</label>
                            <input type="text" name="followup" id="followup" class="datepicker2 form-control" />
                        </div>
                        <div class="col-2 m-l-10">
                            <button type="submit" onclick="save_follow_yes()" class="btn btn-success m-t-10 float-right">Save</button>
                        </div>
                    </div>
                    <div id="quote_stage_no" class="row col-10 m-t-10" style="display:none">
                        <label for="">Would you like to</label>
                        <select name="action" id="action_follow" class="form-control">

                            <option value="Drop">Drop</option>
                            <option value="LC Follow-up">LC Follow-up</option>

                        </select>

                        <button type="submit" onclick="no_follow_stage_action()" class="btn btn-success m-t-10 float-right">Save</button>
                    </div>


                </div>


            </div>

        </div>

    </div>
    <!----end Quote stage-->
    <!-- follow-up Stage----------->
    <div id="follow_stage_review" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title ">Self Review</h4>
                    <button class="close modalMinimize"> <i class='fa fa-minus'></i> </button>

                </div>
                <div class="modal-body">
                    <h3>How&apos;s the case progressing?</h3>
                    <button class="btn btn-primary" onclick="yes_follow_stage()">Yes</button>
                    <button class="btn btn-danger" onclick="no_follow_stage()">No</button>
                    <div id="follow_stage_yes" class="row col-10 m-t-10" style="display:none">
                        <select name="stage" id="followup_stage" class="form-control">
                            <option value="Yes, Positive. Expecting Closure">Yes, Positive. Expecting Closure</option>
                            <option value="Yes, Positive but unsure on closure date">Yes, Positive but unsure on closure date</option>
                            <option value="Yes but still negotiating on Price">Yes but still negotiating on Price</option>
                            <option value="Yes but still negotiating on Quantity">Yes but still negotiating on Quantity</option>
                            <option value="No, Customer doesnt seems intrested">No, Customer doesn&apos;t seems intrested</option>

                        </select>
                        <div class="row col-12 m-t-10" id="flup_date" style="display:none">
                            <label for="">Select Follow-Up Date</label>
                            <input type="text" name="followup_dat" id="followup_dat" class="datepicker2 form-control" />
                        </div>
                        <div class="col-2 m-l-10">
                            <button type="submit" onclick="save_follow_yes()" class="btn btn-success m-t-10 float-right">Save</button>
                        </div>
                    </div>



                </div>


            </div>

        </div>

    </div>
    <!----end Quote stage-->
    <!-- Commit Stage----------->
    <div id="commit_stage_review" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title ">Self Review</h4>
                    <button class="close modalMinimize"> <i class='fa fa-minus'></i> </button>

                </div>
                <div class="modal-body">
                    <h3>What&apos;s the status on Purchase Order?</h3>
                    <button class="btn btn-primary" onclick="yes_commit_stage()">Yes</button>
                    <<!-- button class="btn btn-danger" onclick="no_commit_stage()">No</button> -->
                        <div id="commit_stage_yes" class="row col-10 m-t-10" style="display:none">
                            <select name="stage" id="commit_stage" class="form-control">
                                <option value="Yes, Purchase Order received">Yes, Purchase Order received</option>
                                <option value="Expected to Close">Expected to Close</option>
                                <option value="Yes, Positive but unsure on closure date">Yes, Positive but unsure on closure date</option>
                                <option value="Customer become negative, Need LC Support">Customer become negative, Need LC Support</option>
                                <option value="Customer become negative, Need Manager Support">Customer become negative, Need Manager Support</option>

                            </select>
                            <div class="row col-12 m-t-10" id="commit_date" style="display:none">
                                <label for="">Select Follow-Up Date</label>
                                <input type="text" name="commit_dat" id="commit_dat" class="datepicker2 form-control" />
                            </div>
                            <div class="col-2 m-l-10">
                                <button type="submit" onclick="save_commit_yes()" class="btn btn-success m-t-10 float-right">Save</button>
                            </div>
                        </div>



                </div>


            </div>

        </div>

    </div>
    <!----end Quote stage-->
    <!-- EUPO Stage----------->
    <div id="eupo_stage_review" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title ">Self Review</h4>
                    <button class="close modalMinimize"> <i class='fa fa-minus'></i> </button>

                </div>
                <div class="modal-body">
                    <h3>What&apos;s the status on Order Processing?</h3>
                    <button class="btn btn-primary" onclick="yes_eupo_stage()">Yes</button>
                    <!-- <button class="btn btn-danger" onclick="no_eupo_stage()" >No</button> -->
                    <div id="eupo_stage_yes" class="row col-10 m-t-10" style="display:none">
                        <select name="stage" id="eupo_stage" class="form-control">
                            <option value="Yes, Order is Processed">Yes, Order is Processed</option>
                            <option value="Payment is not clear, but we will process this order">Payment is not clear, but we will process this order</option>
                            <option value="Payment is not clear, order can not process in this month">Payment is not clear, order can not process in this month</option>


                        </select>
                        <div class="row col-12 m-t-10" id="eupo_date" style="display:none">
                            <label for="">Select Follow-Up Date</label>
                            <input type="text" name="eupo_dat" id="eupo_dat" class="datepicker2 form-control" />
                        </div>
                        <div class="col-2 m-l-10">
                            <button type="submit" onclick="save_eupo_yes()" class="btn btn-success m-t-10 float-right">Save</button>
                        </div>
                    </div>



                </div>


            </div>

        </div>

    </div>
    <!----end Quote stage-->
    <!-- booking Stage----------->
    <div id="booking_stage_review" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title ">Self Review</h4>
                    <button class="close modalMinimize"> <i class='fa fa-minus'></i> </button>

                </div>
                <div class="modal-body">
                    <h3>Do you received the invoice?</h3>
                    <button class="btn btn-primary" onclick="yes_booking_stage()">Yes</button>

                    <div id="booking_stage_yes" class="row col-10 m-t-10" style="display:none">
                        <select name="stage" id="booking_stage" class="form-control">
                            <option value="Yes, we received">Yes, we received</option>


                        </select>

                        <div class="col-2 m-l-10">
                            <button type="submit" onclick="save_booking_yes()" class="btn btn-success m-t-10 float-right">Save</button>
                        </div>
                    </div>



                </div>


            </div>

        </div>

    </div>
    <!----end Quote stage-->
    <?php include('includes/footer.php') ?>
    <script>
        function chage_stage(a, id) {
           
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
                        url: 'get_sub_stage.php',
                        data: {
                            stage: a,
                            id: id
                        },
                        success: function(response) {
                            //alert(html);
                            if (response != 'html') {
                                $('#add_comment').html(response);
                                $('#add_comment').show();
                            } else {
                                $('#add_comment').hide();
                            }
                        }
                    });
                }
            }


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
                            id: id,
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

        function payment_option(val, id) {
            //alert(val);
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
            } else if (val == 'Payment in Installments') {
                $("#pay_tab").show();
                $("#op").hide();
            } else if (val == 'Payment Not Clear' || val == '') {
                //alert(12);
                $("#pay_tab").hide();
                ("#op").hide();
            }
        }


        function add_activity(a) {
            $.ajax({
                type: 'POST',
                url: 'add_activity.php',
                data: {
                    pid: a
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
            $('.datepicker2').daterangepicker({

                "singleDatePicker": true,
                "showDropdowns": true,
                minDate: new Date(),
                locale: {
                    format: 'YYYY-MM-DD'
                },
            });
        });
        $(function() {
            $('.datepicker').daterangepicker({
                drops: "up",
                "singleDatePicker": true,
                "showDropdowns": true,
                minDate: new Date(),
                locale: {
                    format: 'YYYY-MM-DD'
                },
                autoUpdateInput: false,


            }, function(start, end) {
                $(this.element).val(start.format('YYYY-MM-DD'));
            });



        });

        function change_quantity(a) {
            document.getElementById("quant").innerHTML = '<input type="text" value="' + a + '" id="new_quantity"/> <button onclick="save_newqty()" class="btn btn-warning">Save</button>'

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
            $('.datepicker1').daterangepicker({

                "singleDatePicker": true,
                "showDropdowns": false,
                "opens": "right",
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
            $('.datepicker1').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });


        });
    </script>

    <script>
        $(document).ready(function() {

            var wfheight = $(window).height();
            $('.partner_lead_log').height(wfheight - 170);

            $('.partner_lead_log').perfectScrollbar()
        });
    </script>

    <!-- No Stage Self Review -->
    <?php require_once("self_review_script.php"); ?>

    <!-- End Self Review for quote Stage -->