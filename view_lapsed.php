<?php include('includes/header.php');

$date_check = date('Y-m-d', strtotime('-7 day', strtotime(date('Y-m-d'))));


if ($_POST['lapsed_save'] && $_POST['lid']) {

    if (getSingleresult("select id from activity_log where date(created_date)>'" . $date_check . "' and pid=" . $_POST['lid'])) {
        $lapsed_sql = db_query("select * from lapsed_orders where id=" . $_POST['lid']);
        $lapsed_data = db_fetch_array($lapsed_sql);
        if (!$_POST['lead_type']) {
            $_POST['lead_type'] = 'LC';
        }
        if (!$_POST['account_visited']) {
            $_POST['account_visited'] = 'Yes';
        }
        if (!$_POST['license_type']) {
            $_POST['license_type'] = 'Commercial';
        }

        if ($_FILES["user_attachment"]) {
            $target_dir = "uploads/";
            $target_file = $target_dir . time() . basename($_FILES["user_attachment"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
            if ($_FILES["user_attachment"]["size"] > 4000000) {
                echo "<script>alert('Sorry, your file is too large!')</script>";
                redir("add_leads.php", true);
            } else {
                move_uploaded_file($_FILES["user_attachment"]["tmp_name"], $target_file);
            }
        }

        $user_image = !empty($_FILES["user_attachment"]["name"]) ? $target_file : $user_attachement;

        $runrate_key = ($lapsed_data['quantity'] <= 8) ? 'Runrate' : 'Key';

        $res = db_query("INSERT INTO `orders`(id,`r_name`, `r_email`, `r_user`, `source`, `lead_type`, `company_name`, `parent_company`, `landline`,region, `industry`,sub_industry, `address`, `pincode`, `state`, `city`, `country`, `eu_name`, `eu_email`, `eu_landline`, `department`, `eu_mobile`, `eu_designation`, `eu_role`, `account_visited`, `visit_remarks`, `confirmation_from`, `license_type`, `quantity`, `created_by`, `team_id`, `status`,os,version,runrate_key,partner_close_date,lapsed_date,association_name,validation_type,user_attachement) VALUES ('" . $lapsed_data['id'] . "','" . $lapsed_data['r_name'] . "','" . $_SESSION['email'] . "','" . $_SESSION['name'] . "','" . $lapsed_data['source'] . "','" . $_POST['lead_type'] . "','" . $lapsed_data['company_name'] . "','" . $lapsed_data['parent_company'] . "','" . $lapsed_data['landline'] . "','" . $lapsed_data['region'] . "','" . $lapsed_data['industry'] . "','" . $lapsed_data['sub_industry'] . "','" . htmlspecialchars($lapsed_data['address'], ENT_QUOTES) . "','" . $lapsed_data['pincode'] . "','" . $lapsed_data['state'] . "','" . $lapsed_data['city'] . "','" . $lapsed_data['country'] . "','" . $lapsed_data['eu_name'] . "','" . $lapsed_data['eu_email'] . "','" . $lapsed_data['eu_landline'] . "','" . $lapsed_data['department'] . "','" . $lapsed_data['eu_mobile'] . "','" . $lapsed_data['eu_designation'] . "','" . $lapsed_data['eu_role'] . "','" . $lapsed_data['account_visited'] . "','" . htmlspecialchars($lapsed_data['visit_remarks'], ENT_QUOTES) . "','" . $lapsed_data['confirmation_from'] . "','" . $lapsed_data['license_type'] . "','" . $lapsed_data['quantity'] . "','" . $_SESSION['user_id'] . "','" . $_SESSION['team_id'] . "','Pending','" . $_POST['os'] . "','" . $lapsed_data['version'] . "','" . $runrate_key . "','" . $_POST['partner_close_date'] . "','" . $lapsed_data['lapsed_date'] . "','" . $_POST['association_name'] . "','" . $_POST['validation_type'] . "','".$user_image."')");
//print_r($res);
        if ($_POST['remarks'] && !$_POST['activity_edit']) {
            //echo "deepranshu"; die;
            $res = db_query("insert into activity_log (pid,description,activity_type,call_subject,added_by,action_plan) values ('" . $_POST['lid'] . "','" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "','Lead','" . $_POST['subject'] . "','" . $_SESSION['user_id'] . "','" . $_POST['action_plan'] . "')");
        }

        db_query("delete from lapsed_orders where id=" . $_POST['lid']);
        $addTo[] = ("prashant.dongrikar@arkinfo.in");
        $addCc[] = ("kailash.bhurke@arkinfo.in");

        $setSubject = "Lapsed Lead Resubmitted to DR Portal from " . $_SESSION['name'] . " (" . $lapsed_data['r_user'] . ")";
        $body    = "Hi,<br><br> There is a Lapsed Lead Resubmitted to DR Portal with details as below:-<br><br>
<ul>
<li><b>Partner Name</b> : " . $lapsed_data['r_name'] . " </li>
<li><b>Name</b> : " . $_SESSION['name'] . " </li>
<li><b>Email</b> : " . $lapsed_data['r_email'] . " </li>
<li><b>Source</b> : " . $lapsed_data['source'] . " </li>
<li><b>Lead Type</b> : " . $_POST['lead_type'] . " </li>
<li><b>Company Name</b> : " . $lapsed_data['company_name'] . " </li>
<li><b>Address</b> : " . htmlspecialchars($lapsed_data['address'], ENT_QUOTES) . " </li>
<li><b>Mobile</b> : " . $lapsed_data['eu_mobile'] . " </li>
<li><b>Email</b> : " . $lapsed_data['eu_email'] . " </li>
<li><b>License Type</b> : " . $lapsed_data['license_type'] . " </li>
<li><b>Quantity</b> : " . $lapsed_data['quantity'] . " </li>
<li><b>Projected Close Date</b> : " . $_POST['partner_close_date'] . " </li></ul><br>
Thanks,<br>
SketchUp DR Portal


";
    sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
        if ($res) {

            redir("lapsed_partner_leads.php?add=success", true);
        } else {
            redir("lapsed_partner_leads.php?e=err", true);
        }
    } else {
        redir("lapsed_partner_leads.php?valid=err", true);
    }
}

if ($_POST['association_name']) {
    $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Association Name','" . $association_name . "','" . $_POST['association_name'] . "',now(),'" . $_SESSION['user_id'] . "')");

    $sql = db_query("update lapsed_orders set association_name='" . $_POST['association_name'] . "' where id=" . $_GET['id']);
}

if ($_POST['remarks'] && !$_POST['activity_edit']) {
    //echo "deepranshu"; die;
    $res = db_query("insert into activity_log (pid,description,activity_type,call_subject,added_by,action_plan) values ('" . $_POST['pid'] . "','" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "','Lead','" . $_POST['call_subject'] . "','" . $_SESSION['user_id'] . "','" . $_POST['action_plan'] . "')");
}

if ($_REQUEST['id']) {
    $sql = db_query("select l.*,p.product_name,tpp.product_type,tpp.id as type_id from lapsed_orders as l left join tbl_lead_product as tp on l.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id where l.id=" . $_REQUEST['id']);
    $data = db_fetch_array($sql);
    @extract($data);
} else {
    redir("manage_lapsed_orders.php", true);
}

?>
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <div class="card-body">

                            <!-- <h5 class="card-title">Add Lead</h5>-->

                            <div class="media bredcrum-title">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home > View Lapsed Lead</small>
                                    <h4 class="font-size-14 m-0 mt-1">View Lapsed Lead</h4>
                                </div>
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
                                                <div class="card-body font-size-13">Lapsed on <strong><?= date('F j, Y, g:i a', strtotime($data['lapsed_date'])) ?></strong></div>

                                                <?php
                                            }
                                            $sql = db_query("select * from lead_modify_log where log_status='Active' AND lead_id=" . $_REQUEST['id'] . " order by id desc");


                                            if (db_num_array($sql) > 0) {

                                                while ($data = db_fetch_array($sql)) { ?>

                                                    <div class="card-body font-size-13"> <?= getSingleresult("select name from users where id=" . $data['created_by']) ?> has changed <strong> <?= $data['type'] ?> </strong> from <strong> <?= ($data['previous_name'] ? $data['previous_name'] : 'N/A') ?> </strong> to <strong> <?= $data['modify_name'] ?></strong> on <?= date('F j, Y, g:i a', strtotime($data['created_date'])) ?>.
                                                    </div>

                                            <?php
                                                    $count++;
                                                }
                                            }  ?>

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
                                                                <td width="35%">Reseller Name</td>
                                                                <td width="65%"><?= $r_name . ' (' . getSingleresult("select reseller_id from partners where id=" . $team_id) . ')' ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Reseller Email</td>
                                                                <td>
                                                                    <?= $r_email ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Submited By</td>
                                                                <td>
                                                                    <?= $r_user ?>
                                                                </td>
                                                            </tr>
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
                                                    Product Info
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne4" class="" aria-labelledby="headingOne4" data-parent="#accordionExample2">


                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <table class="table" id="user">
                                                        <tbody>
                                                            <tr>
                                                                <td width="35%">Product Name</td>
                                                                <td width="65%"><?= $product_name ?></td>

                                                                <input type="hidden" name="lapsed_id" id="lapsed_id" value="<?= $_REQUEST['id'] ?>" />
                                                            </tr>
                                                            <tr>
                                                                <td>Product Type</td>
                                                                <td>
                                                                    <?= $product_type ?>&nbsp;

                                                                    <?php
                                                                    if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN') {
                                                                        if ($product_name == 'CDGS Fresh') { ?>
                                                                            &nbsp;&nbsp;<select onchange="product_type('<?= $_GET['id'] ?>',this.value);" id="type_product">
                                                                                <option value="">Change Product Type</option>
                                                                                <option value="1">Change to CDGS Perpetual</option>
                                                                                <option value="2">Change to CDGS Annual</option>
                                                                                <option value="3">Change to CDGS Edu</option>
                                                                            </select>
                                                                        <?php }
                                                                    } else {
                                                                        if ($product_name == 'CDGS Fresh' && ($product_type == 'CDGS Perpetual' || $product_type == 'CDGS Annual')) { ?>
                                                                            <button class="btn1 btn-primary" onclick="change_product_type('<?= $_GET['id'] ?>','<?= $type_id ?>')">Change Product Type</button>
                                                                    <?php }
                                                                    } ?>
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
                                                                <td width="35%">Lead Source</td>
                                                                <td width="65%"><?= $source ?></td>
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

                                                                <form method="post" name="save_association_form">
                                                                    <?php if ($type_id == 1 || $type_id == 2) {
                                                                        if (!empty($association_name)) { ?>
                                                                            <td>Association Name</td>
                                                                            <td id="edit_association">
                                                                                <?= $association_name ?>
                                                                                <input type="hidden" name="edit_association_name" value="<?= $association_name ?>" id="edit_association_name">
                                                                                <?php if ($created_by == $_SESSION['user_id']) { ?>
                                                                                    <button class="btn1 btn-primary" onclick="change_association('<?= $association_name ?>')">Edit</button>
                                                                                <?php } ?>
                                                                            </td>
                                                                            <?php } else {
                                                                            if ($created_by == $_SESSION['user_id']) { ?>
                                                                                <td>Association Name</td>
                                                                                <td><input type="text" name="association_name" value="<?= $association_name ?>">
                                                                                    <button type="submit" class="btn1 btn-primary">Save</button>
                                                                                </td>
                                                                    <?php }
                                                                        }
                                                                    } ?>
                                                                </form>
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
                                                                <td>Department</td>
                                                                <td>
                                                                    <?= $department ?>
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


                                                                    <?= date('d-m-Y H:i:s', strtotime($created_date)) ?>: <?= $visit_remarks ?>



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

                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <td width="35%">Type of License</td>
                                                                <td width="65%"><?= $license_type ?></td>
                                                            </tr>
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
                                                            <tr>
                                                                <td>Quantity</td>
                                                                <td>
                                                                    <?= $quantity ?> User(s)
                                                                    <input type="hidden" name="quantity" value="<?= $quantity ?>" id="qty">
                                                                </td>
                                                            </tr>



                                                            <tr>
                                                                <td>Created on</td>
                                                                <td>
                                                                    <?= date('d-m-Y H:i:s a', strtotime($created_date)) ?>
                                                                </td>
                                                            </tr>
                                                            <?php if ($user_attachement) { ?>
                                                                <tr>
                                                                    <td>Attachment</td>
                                                                    <td>
                                                                        <a href="<?= $user_attachement ?>" target="_blank">View/Download</a>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                <?php } ?>
                                                                <?php if ($admin_attachment) { ?>
                                                                <tr>
                                                                    <td>Admin Attachment</td>
                                                                    <td>
                                                                        <a href="<?= $admin_attachment ?>" target="_blank">View/Download</a>
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
                                        <div class="card-header" id="headingOne8">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne8" aria-expanded="true" aria-controls="collapseOne8">
                                                    Activity History
                                                </button>
                                            </h5>
                                            <a href="" data-toggle="modal" onclick="add_activity(<?= $_GET['id'] ?>,'<?= $company_name ?>')" data-animation="bounce" data-target=".bs-example-modal-center"><button class="btn1 btn-primary ml-2 mt-1">Log a Call</button></a>
                                        </div>

                                        <div id="collapseOne8" class="" aria-labelledby="headingOne8" data-parent="#accordionExample2">





                                            <div class="row">
                                                <div class="col-md-12">


                                                    <?php
                                                    $new = db_query("select id,description,created_date,added_by,call_subject from activity_log where activity_type='Lead' and pid='" . $_GET['id'] . "' UNION SELECT id,description,created_date,added_by,id from caller_comments where pid='" . $_GET['id'] . "' union select id,comment as description,created_date,added_by,id from review_log where lead_id='" . $_GET['id'] . "' order by created_date desc");
                                                    $goal = db_query("select * from activity_log where pid='" . $_GET['id'] . "' order by created_date desc");
                                                    $count = mysqli_num_rows($new);
                                                    $i = $count;
                                                    if ($count) {
                                                        echo  ' <table class="table"><tr><th>S.No</th><th>Subject</th><th>Description</th><th>Added By</th><th>Date</th></tr>';

                                                        while ($data_n = db_fetch_array($new)) { ?>

                                                            <tr style="text-align:center;">
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


                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="button-items1">
                                        <?php if ($_SESSION['user_type'] != 'REVIEWER' && $_SESSION['user_type'] != 'CLR' && $_SESSION['user_type'] != 'ISS MNGR') { ?>
                                            <button type="button" onclick="add_asLead(<?= $id ?>)" class="btn1 btn-primary  mt-2">Add as Fresh Lead</button>
                                        <?php } ?>
                                        <button type="button" onclick="javascript:history.go(-1)" class="btn1 btn-danger mt-2">Back</button>
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

<?php
if ($_SESSION['user_type'] == 'USR' || $_SESSION['user_type'] == 'MNGR' || $_SESSION['user_type'] == 'CLR' || $_SESSION['user_type'] == 'ISS MNGR' || $_SESSION['user_type'] == 'REVIEWER') {
    if (mysqli_num_rows(db_query("select * from tbl_lead_product where lead_id=" . $_REQUEST['id'])) == 0) { ?>
        <script>
            var id = $('#lapsed_id').val();
            var company = $('#company').val();
            var qty = $('#qty').val();
            //alert(qty);
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
<?php }
} ?>

<script>
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
                        url: "update_association_name.php?id=<?= $_GET['id'] ?>&lapsed_association=" + new_assoc,
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
                        lapsed_lead_id: id,
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

    function product_type(id, type) {

        swal({
            title: "Are you sure?",
            text: "Are you sure you would like to change Product Type ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, convert it!",
            confirmButtonColor: "#ec6c62",
            closeOnConfirm: false,

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

    function status_update(r) {
        if (r == 'Cancelled') {
            $("#reason").show();
            $("#reason_dd").prop('required', true);
            $("#caller").hide();
            $("#caller_dd").prop('required', false);
            $("#reason_ud").hide();
        } else if (r == 'Approved') {
            $("#reason_ud").hide();
            $("#reason").hide();
            //alert('Yes');
            var ltype = $("#ltype").val();
            if (ltype == 'LC') {
                $("#caller").show();
                $("#caller_dd").prop('required', true);
            }
        } else if (r == 'On-Hold') {
            $("#reason_ud").hide();
            $("#reason").hide();
        } else {
            $("#reason").hide();
            $("#reason_ud").show();
            $("#reason_ud").prop('required', true);
            $("#reason_dd").prop('required', false);
            $("#caller").hide();
            $("#caller_dd").prop('required', false);
        }
    }

    function update_type(a) {
        if (a) {
            swal({
                title: "Are you sure?",
                text: "You want to change Lead Type!",
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
                        url: "update_lead.php?oid=<?= $_GET['id'] ?>&type=" + a,
                        success: function(result) {
                            if (result) {
                                swal({
                                    title: "Done!",
                                    text: "Lead converted.",
                                    type: "success"
                                }, function() {
                                    window.location = "view_order.php?id=<?= $_GET['id'] ?>";
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
        $('.datepicker').daterangepicker({
            "singleDatePicker": true,
            "showDropdowns": false,
            "opens": "right",
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
        $('.datepicker').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
        });


    });

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

    function add_asLead(id) {
        $.ajax({
            type: 'POST',
            url: 'ajax_lapsed_lead.php',
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
</script>
<script>
    $(document).ready(function() {
        var wfheight = $(window).height();
        $('.add_lead').height(wfheight - 220);
    });
</script>