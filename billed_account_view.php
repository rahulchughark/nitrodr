<?php include('includes/header.php');

$sql = db_query("select * from orders where id='" . $_REQUEST['id'] . "'");
$row_data = db_fetch_array($sql);

if ($row_data['status'] == 'Undervalidation') {
    if ($_POST['remarks'] || $_POST['call_subject']) {
        $res = db_query("update  `orders` set status='Pending',created_date=now() where id='" . $_REQUEST['id'] . "'");


        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Status','" . $row_data['status'] . "','Pending',now(),'" . $_SESSION['user_id'] . "')");
    }
}
 
//print_r($_POST['new_user']);die;
if ($_POST['new_user']) {
    $email_new = getSingleresult("select email from users where id=" . $_POST['new_user']);
   // print_r($email_new);die;
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
    if ($row_data['status'] == 'Approved') {
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

            }
            
            else if($st_data['stage_name'] != 'Product Demo' && $st_data['stage_name'] != 'Product POC (Evaluation)') {
                $add_point = db_query("insert into user_points (stage_id,stage_name,point,week_number,date_from,date_to,no_of_seats,user_id,lead_id) values ('" . $st_data['id'] . "','" . $st_data['stage_name'] . "','" . $st_data['stage_point'] . "','" . date('W') . "','$points_date[0]','$points_date[1]','" . $order_detail['quantity'] . "','" . $order_detail['created_by'] . "','" . $_REQUEST['id'] . "') ");
            }
        }
    }

    if (!getSingleresult("select id from user_points where stage_name='" . $_POST['stage'] . "' and lead_id=" . $_REQUEST['id'])) {
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

        $points_date = week_range(date('Y-m-d'));

        $add_point = db_query("insert into user_points (stage_id,stage_name,point,week_number,date_from,date_to,no_of_seats,user_id,lead_id) values ('" . $row_data['team_id'] . "','OEM Billing',50,'" . date('W') . "','$points_date[0]','$points_date[1]','" . $row_data['quantity'] . "','" . $row_data['created_by'] . "','" . $_REQUEST['id'] . "') ");

    }else if($_POST['stage'] =='Product Demo' && $row_data['stage']!='Product Demo'){
        $points_date = week_range(date('Y-m-d'));

        $add_point = db_query("insert into user_points (stage_id,stage_name,point,week_number,date_from,date_to,no_of_seats,user_id,lead_id) values ('" . $row_data['team_id'] . "','Product Demo',10,'" . date('W') . "','$points_date[0]','$points_date[1]','" . $row_data['quantity'] . "','" . $row_data['created_by'] . "','" . $_REQUEST['id'] . "') ");

    }else if($_POST['stage'] =='Product POC (Evaluation)' && $row_data['stage']!='Product POC (Evaluation)'){
        $points_date = week_range(date('Y-m-d'));
        
        $add_point = db_query("insert into user_points (stage_id,stage_name,point,week_number,date_from,date_to,no_of_seats,user_id,lead_id) values ('" . $row_data['team_id'] . "','Product POC (Evaluation)',25,'" . date('W') . "','$points_date[0]','$points_date[1]','" . $row_data['quantity'] . "','" . $row_data['created_by'] . "','" . $_REQUEST['id'] . "') ");
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

            $ps = db_query("update installment_details set pid='" . $_GET['id'] . "',type='Lead',date1='" . $_POST['date1'] . "',instalment1='" . $_POST['instalment1'] . "',date2='" . $_POST['date2'] . "',instalment2='" . $_POST['instalment2'] . "',date3='" . $_POST['date3'] . "',instalment3='" . $_POST['instalment3'] . "',date4='" . $_POST['date4'] . "',instalment4='" . $_POST['instalment4'] . "',added_by='" . $_SESSION['user_id'] . "' where pid='" . $_GET['id'] . "'");
        } else {

            $ps = db_query("insert into installment_details (`pid`, `type`, `date1`, `instalment1`, `date2`, `instalment2`, `date3`, `instalment3`, `date4`, `instalment4`, `added_by`) values ('" . $_GET['id'] . "','Lead','" . $_POST['date1'] . "','" . $_POST['instalment1'] . "','" . $_POST['date2'] . "','" . $_POST['instalment2'] . "','" . $_POST['date3'] . "','" . $_POST['instalment3'] . "','" . $_POST['date4'] . "','" . $_POST['instalment4'] . "','" . $_SESSION['user_id'] . "')");
        }
    } else if ($_POST['stage'] == 'EU PO Issued' || $row_data['add_comm'] == 'EU PO Issued') {
        $ps = db_query("update orders set op_this_month='" . $_POST['op'] . "' where id='" . $_GET['id'] . "'");
    }


    redir("orders.php?update=success", true);
}
if ($_POST['submit_dvr']) {
    $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Lead','Lead','DVR',now(),'" . $_SESSION['user_id'] . "')");

    $sql = db_query("update orders set is_dr=1,convert_date='" . date('Y-m-d H:i:s') . "',dvr_by='" . $_SESSION['user_id'] . "',date_dvr='" . date('Y-m-d H:i:s') . "' where id=" . $_GET['id']);

    redir("orders.php?update=success", true);
}

if ($_POST['association_name']) {
    $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Association Name','" . $row_data['association_name'] . "','" . $_POST['association_name'] . "',now(),'" . $_SESSION['user_id'] . "')");

    $sql = db_query("update orders set association_name='" . $_POST['association_name'] . "' where id=" . $_GET['id']);
}

if ($_POST['remarks'] || $_POST['call_subject']) {

    $res = db_query("insert into activity_log (pid,description,activity_type,call_subject,added_by,data_ref) values ('" . $_POST['pid'] . "','" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "','Lead','" . $_POST['call_subject'] . "','" . $_SESSION['user_id'] . "',1)");

    $email = db_query("select r_email,r_name,r_user,lead_type,company_name,eu_email,eu_mobile,team_id,quantity,partner_close_date,caller from orders where id=" . $_POST['pid']);
    $data = db_fetch_array($email);

    $sm_email = getSingleresult("select users.email as email from users join partners on partners.sm_user=users.id where partners.id='" . $data['team_id'] . "'");
    if ($sm_email)
        $addCc[] = ($sm_email);

    $addCc[] = ("prashant.dongrikar@arkinfo.in");
    $addCc[] = ("kailash.bhurke@arkinfo.in");
    $addBcc[] = ("deepranshu.srivastava@arkinfo.in");
    if ($data['lead_type'] == 'LC') {
        if ($data['caller'] != '') {
            $caller_email1 = db_query("select users.email as call_email from users join callers on users.id=callers.user_id where callers.id=" . $data['caller']);
            $caller_email = db_fetch_array($caller_email1);
            $addTo[] = ($caller_email['call_email']);
        }

        $manager_email = getSingleresult("select email from users where user_type='MNGR' and team_id=" . $data['team_id']);
        $addCc[] = ($_SESSION['email']);
        $addCc[] = ($manager_email);
        $addCc[] = ("virendra@corelindia.co.in");
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
    } else {
        $manager_email = getSingleresult("select email from users where user_type='MNGR' and team_id=" . $data['team_id']);

        $addTo[] = ($manager_email);
        $addCc[] = ($data['r_email']);
        $addCc[] = ($_SESSION['email']);
        //$addCc[] = ("maneesh.kumar@arkinfo.in");
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
    }
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

                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home > View Lead</small>
                                    <h4 class="font-size-14 m-0 mt-1">View Lead</h4>
                                </div>
                                <?php
                                if ($_SESSION['user_type'] == 'MNGR' && $lead_type == 'LC' && $caller) { ?>
                                    <a href="#" id="addAction"><button type="button" class="btn btn-xs btn-primary ml-1 waves-effect waves-light" data-toggle="modal" data-original-title="Add New Action" data-animation="bounce" data-target=".bs-example-modal-task" style="float: right;"><i class="fa fa-plus mr-1"></i></button></a>
                                <?php } ?>
                                <a href="#" id="addCopy">
                                    <button type="button" class="btn btn-xs  ml-1 waves-effect btn-primary waves-light" data-toggle="modal" data-original-title="Copy Lead as New" data-animation="bounce" data-target=".bs-example-modal-task" style="float: right;"><i class="ti-reload"></i></button></a>
                            </div>

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

                                        <div id="collapseOne2" class="collapse" aria-labelledby="headingOne2" data-parent="#accordionExample2">

                                            <?php if ($data['lapsed_date'] && $data['lapsed_date'] != '0000-00-00 00:00:00') { ?>
                                                <div class="card-body font-size-13">Lapsed on <strong><?= date('F j, Y, g:i a', strtotime($data['lapsed_date'])) ?></strong></div>

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
                                </div>
                                <div class="card">
                                    <h5 class="card-subtitle"> Reseller Info - License Type:(<?= $license_type ?>)</h5>
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
                                                            <?php if ($_SESSION['user_id'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN') { ?> <button class="btn btn-primary" onclick="change_user('<?= $_GET['id'] ?>','<?= $_SESSION['team_id'] ?>')">Change</button> <?php } ?>

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
                                    <h5 class="card-subtitle"> Product Info</h5>
                                    <div class="row">

                                        <div class="col-lg-12">
                                            <table class="table" id="user">
                                                <tbody>
                                                    <tr>
                                                        <td width="35%">Product Name</td>
                                                        <td width="65%"><?= $product_name ?></td>
                                                        <input type="hidden" name="product_id" id="product_id" value="<?= $product_name ?>" />
                                                        <input type="hidden" name="edit_id" id="edit_id" value="<?= $_REQUEST['id'] ?>" />
                                                    </tr>
                                                    <tr>
                                                        <td>Product Type</td>
                                                        <td>
                                                            <?= $product_type ?>&nbsp;

                                                            <!-- <?php if ($product_name == 'CDGS Fresh' && ($product_type == 'CDGS Perpetual' || $product_type == 'CDGS Annual')) { ?>
                                                                <button class="btn btn-primary" onclick="change_product_type('<?= $_GET['id'] ?>','<?= $type_id ?>')">Change Product Type</button>
                                                            <?php } ?> -->
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <h5 class="card-subtitle">Customer Information</h5>

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <table class="table ">
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
                                                        <td>Aassociation Name</td>
                                                        <td><?= $association_name ?></td>
                                                    </tr>   
                                                    <!-- <tr>
                                                   
                                                    <form method="post" name="save_association_form">
                                                            <?php if($type_id==1 || $type_id==2){
                                                            if (!empty($association_name)) { ?>
                                                                <td>Association Name</td>
                                                                <td id="edit_association">
                                                                    <?= $association_name ?>
                                                                    <input type="hidden" name="edit_association_name" value="<?= $association_name ?>" id="edit_association_name">
                                                                    <?php if ($created_by == $_SESSION['user_id']) { ?>
                                                                        <button class="btn btn-primary" onclick="change_association('<?= $association_name ?>')">Edit</button>
                                                                    <?php } ?>
                                                                </td>
                                                                <?php } else {
                                                                if ($created_by == $_SESSION['user_id']) { ?>
                                                                <td>Association Name</td>
                                                                    <td><input type="text" name="association_name" value="<?= $association_name ?>">
                                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                                    </td>
                                                            <?php }
                                                            } } ?>
                                                        </form>
                                                    </tr> -->

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

                                    <h5 class="card-subtitle">Decision Maker/Proprietor/Director/End User Details
                                    </h5>

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

                                    <h5 class="card-subtitle">Other Contacts</h5>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table">
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

                                    <h5 class="card-subtitle">Lead Information</h5>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table">
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

                                                        <tr>
                                                            <td>Stage</td>
                                                            <td><?=$stage ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Sub Stage</td>
                                                            <td><?=$add_comm ?></td>
                                                        </tr>
                                                        
                                                            <tr>
                                                                <td>Projected Close Date</td>
                                                                <td><?= $partner_close_date ?></td>
                                                                <!-- <td><input type="text" value="<?= $partner_close_date ?>" class="form-control" id="datepicker-close-date" readonly name="partner_close_date" /></td> -->
                                                            </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <h5 class="card-subtitle">Activity Call <a data-toggle="modal" onclick="add_activity(<?= $_GET['id'] ?>,'<?=$company_name?>')" data-animation="bounce" data-target=".bs-example-modal-center" class="float-lg-right"><i class="fa fa-plus mr-1"></i></a></h5>



                                    <div class="row">
                                        <div class="col-md-12">


                                            <?php
                                            $new = db_query("select id,description,created_date,added_by,call_subject from activity_log where activity_type='Lead' and pid='" . $_GET['id'] . "' UNION SELECT id,description,created_date,added_by,id from caller_comments where pid='" . $_GET['id'] . "' union select id,comment as description,created_date,added_by,id from review_log where lead_id='" . $_GET['id'] . "' order by created_date desc");
                                            $goal = db_query("select * from activity_log where pid='" . $_GET['id'] . "' order by created_date desc");
                                            $count = mysqli_num_rows($new);
                                            $i = $count;
                                            if ($count) {
                                                echo  ' <table class="table"><thead class="thead-default"><tr><th>S.No</th><th>Subject</th><th>Description</th><th>Added By</th><th>Date</th></tr></thead>';

                                                while ($data_n = db_fetch_array($new)) { ?>
                                                    <tbody>
                                                        <tr>
                                                            <td><?= $i ?></td>
                                                            <td><?= ($data_n['call_subject'] ? $data_n['call_subject'] : 'N/A') ?></td>
                                                            <td><?= $data_n['description'] ?></td>
                                                            <td><?= (is_numeric($data_n['added_by']) ? getSingleresult("select concat(name,'-',CASE  WHEN user_type='USR' THEN 'Partner User'    WHEN user_type='ADMIN' and sales_manager=0 THEN 'ADMIN' WHEN user_type='ADMIN' and sales_manager=1 THEN 'Sales Manager'   WHEN user_type='MNGR' THEN 'Partner Manager'  ELSE 'Caller' END) as nme from users where id='" . $data_n['added_by'] . "'") : '<span style="color:red">(Lead Review!)</span> ' . $data_n['added_by']) ?></td>
                                                            <td><?= date('d-m-Y H:i:s', strtotime($data_n['created_date'])) ?></td>

                                                        </tr>
                                                    </tbody>
                                            <?php $i--;
                                                }
                                                echo "</table>";
                                            } ?>

                                        </div>

                                    </div>
                                    <div class="button-items">

                                        <?php if ($data['status'] != 'Approved' || strstr($company_name, 'One Version Downgrade')) {
                                            if ($data['status'] == 'Undervalidation' && $_SESSION['user_type'] == 'MNGR') { ?>
                                                <a href="edit_order.php?eid=<?= $id ?>"><button type="button" class="btn btn-primary  mt-2">Edit</button></a>
                                            <?php } elseif ($data['status'] == 'Undervalidation' && $created_by == $_SESSION['user_id']) { ?>
                                                <a href="edit_order.php?eid=<?= $id ?>"><button type="button" class="btn btn-primary  mt-2">Edit</button></a>
                                            <?php } elseif ($data['status'] == 'Pending' && $created_by == $_SESSION['user_id']) { ?>
                                                <a href="edit_order.php?eid=<?= $id ?>"><button type="button" class="btn btn-primary  mt-2">Edit</button></a>
                                        <?php    }
                                        } ?>
                                        <button type="button" onclick="javascript:history.go(-1);" class="btn btn-danger mt-2">Back</button>

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

            <!-- No Stage Self Review -->

            <div id="no_stage_review" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">

                            <h4 class="modal-title">Self Review - <?= $company_name ?></h4>
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
                                <select name="substage" id="lc_substage" class="form-control">
                                    <?php $sub_query = db_query("select name from sub_stage where stage_name='License Compliance'");
                                    while ($sb_stage = db_fetch_array($sub_query)) { ?>
                                        <option value="<?= $sb_stage['name'] ?>"><?= $sb_stage['name'] ?></option>
                                    <?php } ?>
                                </select>
                                <button type="submit" onclick="save_lc_yes()" class="btn btn-primary m-t-10 float-right">Save</button>
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

                            <h4 class="modal-title">Self Review - <?= $company_name ?></h4>
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

                                <button type="submit" onclick="no_lc_stage_action()" class="btn btn-primary m-t-10 float-right">Save</button>
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

                            <h4 class="modal-title ">Self Review - <?= $company_name ?></h4>
                            <button class="close modalMinimize"> <i class='fa fa-minus'></i> </button>

                        </div>
                        <div class="modal-body">
                            <h3>What&apos;s the feedback after quotation? </h3>
                            <button class="btn btn-primary" onclick="yes_quote_stage()">Yes</button>
                            <button class="btn btn-danger" onclick="no_quote_stage()">No</button>
                            <div id="quote_stage_yes" class="row col-10 m-t-10" style="display:none">
                                <select name="stage" id="fup_stage" class="form-control" onchange="show_sub(this.value)">
                                    <option value="">---Select---</option>
                                    <option value="Customer asked to call back">Customer asked to call back</option>
                                    <option value="Customer is not responding, we will try again">Customer is not responding, we will try again</option>
                                    <option value="Customer is not responding, need help from LC member">Customer is not responding, need help from LC member</option>
                                    <option value="Customer is positive">Customer is positive</option>
                                    <option value="Looking for best price">Looking for best price</option>
                                    <option value="Customer denied">Customer denied</option>

                                </select>
                                <select name="substage" id="lc_substagefup" class="form-control" style="display:none">
                                    <?php $sub_query = db_query("select name from sub_stage where stage_name='Follow-Up'");
                                    while ($sb_stage = db_fetch_array($sub_query)) { ?>
                                        <option value="<?= $sb_stage['name'] ?>"><?= $sb_stage['name'] ?></option>
                                    <?php } ?>
                                </select>
                                <select name="substage" id="lc_substageCommit" class="form-control" style="display:none">
                                    <?php $sub_query = db_query("select name from sub_stage where stage_name='Commit'");
                                    while ($sb_stage = db_fetch_array($sub_query)) { ?>
                                        <option value="<?= $sb_stage['name'] ?>"><?= $sb_stage['name'] ?></option>
                                    <?php } ?>
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

                                <button type="submit" onclick="no_follow_stage_action()" class="btn btn-primary m-t-10 float-right">Save</button>
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

                            <h4 class="modal-title ">Self Review - <?= $company_name ?></h4>
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
                                    <input type="text" name="followup" id="followup" class="datepicker2 form-control" />
                                </div>
                                <div class="col-2 m-l-10">
                                    <button type="submit" onclick="save_follow_yes2()" class="btn btn-primary m-t-10 float-right">Save</button>
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

                            <h4 class="modal-title ">Self Review - <?= $company_name ?></h4>
                            <button class="close modalMinimize"> <i class='fa fa-minus'></i> </button>

                        </div>
                        <div class="modal-body">
                            <h3>What&apos;s the status on Purchase Order?</h3>
                            <button class="btn btn-primary" onclick="yes_commit_stage()">Yes</button>
                            <!-- button class="btn btn-danger" onclick="no_commit_stage()">No</button> -->
                            <div id="commit_stage_yes" class="row col-10 m-t-10" style="display:none">
                                <select name="stage" id="commit_stage" class="form-control" onchange="change_subfup(this.value)">
                                    <option value="">---Select---</option>
                                    <option value="Yes, Purchase Order received">Yes, Purchase Order received</option>
                                    <option value="Expected to Close">Expected to Close</option>
                                    <option value="Yes, Positive but unsure on closure date">Yes, Positive but unsure on closure date</option>
                                    <option value="Customer become negative, Need LC Support">Customer become negative, Need LC Support</option>
                                    <option value="Customer become negative, Need Manager Support">Customer become negative, Need Manager Support</option>

                                </select>
                                <select name="substage" id="fp_substagefup" class="form-control" style="display:none">
                                    <?php $sub_query = db_query("select name from sub_stage where stage_name='Follow-Up'");
                                    while ($sb_stage = db_fetch_array($sub_query)) { ?>
                                        <option value="<?= $sb_stage['name'] ?>"><?= $sb_stage['name'] ?></option>
                                    <?php } ?>
                                </select>
                                <select name="substage" id="fp_substageCommit" class="form-control" style="display:none">
                                    <?php $sub_query = db_query("select name from sub_stage where stage_name='EU PO Issued'");
                                    while ($sb_stage = db_fetch_array($sub_query)) { ?>
                                        <option value="<?= $sb_stage['name'] ?>"><?= $sb_stage['name'] ?></option>
                                    <?php } ?>
                                </select>
                                <div class="row col-12 m-t-10" id="commit_date" style="display:none">
                                    <label for="">Select Follow-Up Date</label>
                                    <input type="text" name="commit_dat" id="commit_dat" class="datepicker2 form-control" />
                                </div>
                                <div class="col-2 m-l-10">
                                    <button type="submit" onclick="save_commit_yes()" class="btn btn-primary m-t-10 float-right">Save</button>
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

                            <h4 class="modal-title ">Self Review - <?= $company_name ?></h4>
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
                                    <input type="text" name="followup" id="followup" class="datepicker2 form-control" />
                                </div>
                                <div class="col-2 m-l-10">
                                    <button type="submit" onclick="save_eupo_yes()" class="btn btn-primary m-t-10 float-right">Save</button>
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

                            <h4 class="modal-title ">Self Review - <?= $company_name ?></h4>
                            <button class="close modalMinimize"> <i class='fa fa-minus'></i> </button>

                        </div>
                        <div class="modal-body">
                            <h3>Do you received the invoice?</h3>
                            <button class="btn btn-primary" onclick="yes_booking_stage()">Yes</button>

                            <div id="booking_stage_yes" class="row col-10 m-t-10" style="display:none">
                                <select name="stage" id="booking_stage" class="form-control">
                                    <option value="Yes, we received">Yes, we received</option>


                                </select>
                                <select name="substage" id="oemstage" class="form-control">
                                    <?php $sub_query = db_query("select name from sub_stage where stage_name='OEM Billing'");
                                    while ($sb_stage = db_fetch_array($sub_query)) { ?>
                                        <option value="<?= $sb_stage['name'] ?>"><?= $sb_stage['name'] ?></option>
                                    <?php } ?>
                                </select>
                                <div class="col-2 m-l-10">
                                    <button type="submit" onclick="save_booking_yes()" class="btn btn-primary m-t-10 float-right">Save</button>
                                </div>
                            </div>



                        </div>


                    </div>

                </div>

            </div>

            <!----end Quote stage-->
            <?php include('includes/footer.php') ?>

            <?php if (mysqli_num_rows(db_query("select * from tbl_lead_product where lead_id=" . $_REQUEST['id'])) == 0 && !$_GET['review']) { ?>
                <script>
                    var id = $('#edit_id').val();
                    var company = $('#company').val();
                    var qty = $('#qty').val();
                    //alert(id);
                    $(function() {
                        $.ajax({
                            type: 'POST',
                            url: 'lead_product.php',
                            data: {
                                id: id,
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
            <?php if ($_SESSION['user_type'] == 'USR' || $_SESSION['user_type'] == 'PUSR') { ?>
                function chage_stage(a, id) {

                    if (a == 'Commit') {
                        swal("Commit stage can be updated through Manager Access only!", "", "warning");
                        $("#save_button").prop('disabled', true);
                        $('#add_Pcomment').hide();
                        $('#hidden_parallel_stage option:selected').remove();
                        $('#add_Pcomment_dd option:selected').remove();
                    }else if(a == 'EU PO Issued'){
                            swal("EU PO Issued stage can be updated through Manager Access only!", "", "warning");
                        $("#save_button").prop('disabled', true);
                        $('#add_Pcomment').hide();
                        $('#hidden_parallel_stage option:selected').remove();
                        $('#add_Pcomment_dd option:selected').remove();
                    }else if(a == 'Booking'){
                            swal("Booking stage can be updated through Manager Access only!", "", "warning");
                        $("#save_button").prop('disabled', true);
                        $('#add_Pcomment').hide();
                        $('#hidden_parallel_stage option:selected').remove();
                        $('#add_Pcomment_dd option:selected').remove();
                    }else if(a == 'OEM Billing'){
                            swal("OEM Billing stage can be updated through Manager Access only!", "", "warning");
                        $("#save_button").prop('disabled', true);
                        $('#add_Pcomment').hide();
                        $('#hidden_parallel_stage option:selected').remove();
                        $('#add_Pcomment_dd option:selected').remove();
                    }else if(a == 'Billed To Other Re-Seller'){
                            swal("Billed To Other Re-Seller stage can be updated through Manager Access only!", "", "warning");
                        $("#save_button").prop('disabled', true);
                        $('#add_Pcomment').hide();
                        $('#hidden_parallel_stage option:selected').remove();
                        $('#add_Pcomment_dd option:selected').remove();
                    }else if(a == 'Hold License Certificate/Copy'){
                            swal("Hold License Certificate/Copy can be updated through Manager Access only!", "", "warning");
                        $("#save_button").prop('disabled', true);
                        $('#add_Pcomment').hide();
                        $('#hidden_parallel_stage option:selected').remove();
                        $('#add_Pcomment_dd option:selected').remove();
                    }
                     else {
                        $("#save_button").prop('disabled', false);
                        $("#op").hide();
                        $("#pay_tab").hide();
                        $('#add_Pcomment').hide();
                        if (a) {
                            $.ajax({
                                type: 'POST',
                                url: 'get_sub_stage.php',
                                data: {
                                    stage: a,
                                    id: id
                                },
                                success: function(html) {
                                    //alert(html);
                                    if (html != 'html') {
                                        $('#hidden_parallel_stage option:selected').remove();
                                        $('#add_Pcomment_dd option:selected').remove();
                                        $('#add_comment').html(html);
                                        $('#add_comment').show();
                                    } else {
                                        $('#add_comment').hide();
                                        $('#hidden_parallel_stage option:selected').remove();
                                        $('#add_Pcomment_dd option:selected').remove();
                                    }
                                }
                            });
                        }
                    }
                }
            <?php } else { ?> 
            
                function chage_stage(a, id) {

                        $("#save_button").prop('disabled', false);
                        $("#op").hide();
                        $("#pay_tab").hide();
                        $('#add_Pcomment').hide();
                        if (a) {
                            $.ajax({
                                type: 'POST',
                                url: 'get_sub_stage.php',
                                data: {
                                    stage: a,
                                    id: id
                                },
                                success: function(html) {
                                    //alert(html);
                                    if (html != 'html') {
                                        $('#hidden_parallel_stage option:selected').remove();
                                        $('#add_Pcomment_dd option:selected').remove();
                                        $('#add_comment').html(html);
                                        $('#add_comment').show();
                                    } else {
                                        $('#add_comment').hide();
                                        $('#hidden_parallel_stage option:selected').remove();
                                        $('#add_Pcomment_dd option:selected').remove();
                                    }
                                }
                            });
                        }
                    
                }

               <?php } ?>    

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
                    document.getElementById("edit_association").innerHTML = '<input type="text" value="' + a + '" id="new_association"/> <button onclick="save_association()" class="btn btn-warning">Save</button>'

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

                function add_activity(a,company_name) {
                    $.ajax({
                        type: 'POST',
                        url: 'add_activity.php',
                        data: {
                            pid: a,
                            company_name:company_name
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
            </script>

            <script>
                $(document).ready(function() {
                    var wfheight = $(window).height();
                    $('.add_lead').height(wfheight - 280);
                });
            </script>

            <!-- No Stage Self Review -->
            <?php require_once("self_review_script.php"); ?>

            <!-- End Self Review for quote Stage -->