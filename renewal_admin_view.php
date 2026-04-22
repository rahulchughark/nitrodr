<?php include('includes/header.php');
// echo $_POST['status']; die;
admin_page(); ?>
<?php
$sql = db_query("select orders.* from orders where orders.id='" . $_REQUEST['id'] . "'");
$row_data = db_fetch_array($sql);

function struuid($entropy)
{
    $s = uniqid("", '');
    $num = hexdec(str_replace(".", "", (string) $s));
    $index = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $base = strlen($index);
    $out = '';
    for ($t = floor(log10($num) / log10($base)); $t >= 0; $t--) {
        $a = floor($num / pow($base, $t));
        $out = $out . substr($index, $a, 1);
        $num = $num - ($a * pow($base, $t));
    }
    return strtolower($out);
}

if ($_POST['save_new_user']) {
    $email_new = getSingleresult("select email from users where id=" . $_POST['new_user']);
    $name_new = getSingleresult("select name from users where id=" . $_POST['new_user']);
    $old_name = getSingleresult("select name from users where id=" . $row_data['created_by']);
    // $modify_name=getSingleresult("select name from users where id=".$_POST['new_user']);
    $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Ownership','" . $old_name . "','" . $name_new . "',now(),'" . $_SESSION['user_id'] . "')");


    $ins = db_query("update orders set created_by='" . $_POST['new_user'] . "',r_user='" . $name_new . "',r_email='" . $email_new . "' where id='" . $_POST['id'] . "'");
    redir("renewal_leads_admin.php?id=" . $_POST['id'], true);
}
if ($_POST['status']) {


    if ($_FILES["admin_attachment"]) {
        $target_dir = "uploads/";
        $target_file = $target_dir . time() . basename($_FILES["admin_attachment"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($_FILES["admin_attachment"]["size"] > 4000000) {
            echo "<script>alert('Sorry, your file is too large!')</script>";
            redir("renewal_leads_admin.php", true);
        } else {
            move_uploaded_file($_FILES["admin_attachment"]["tmp_name"], $target_file);
        }
    }

    if ($_POST['status'] == 'Approved') {
        if ($_POST['dr_code']) {
            $code = $_POST['dr_code'];
        } else {
            $code = struuid(true);
        }
        $_POST['reason'] = '';
    } else {
        $code = '';
        if ($_POST['status'] == 'Undervalidation') {
            $_POST['reason'] = $_POST['reason_ud'];
        }
    }




    if ($row_data['partner_close_date'] != $_POST['partner_close_date']) {
        $modify_name = $_POST['partner_close_date'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Close Date','" . $row_data['partner_close_date'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($row_data['status'] != $_POST['status']) {
        $modify_name = $_POST['status'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Status','" . $row_data['status'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($row_data['caller'] != $_POST['caller']) {
        $modify_name = getSingleresult("select name from callers where id='" . $_POST['caller'] . "' ");
        $caller_prev = getSingleresult("select name from callers where id='" . $row_data['caller'] . "' ");
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Caller','" . $caller_prev . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    $sql = db_query("update orders set code='" . $code . "', status='" . $_POST['status'] . "', reason='" . $_POST['reason'] . "',add_comment='" . htmlspecialchars($_POST['add_comment']) . "',caller='" . $_POST['caller'] . "',approval_time='" . date('Y-m-d h:i:s') . "',close_time='" . date('Y-m-d', strtotime('+29 days', strtotime(date('Y-m-d h:i:s')))) . "',admin_attachment='" . $target_file . "',partner_close_date='" . $_POST['partner_close_date'] . "',sfdc_check='" . $_POST['sfdc_check'] . "' where id=" . $_REQUEST['id']);
    if ($_POST['status'] == 'Approved') {

        if ($row_data['allign_to']) {
            $point_user_id = $row_data['allign_to'];
        } else {
            $point_user_id = $row_data['created_by'];
        }
        $points_date = week_range(date('Y-m-d'));
        $add_point = db_query("insert into user_points (stage_id,stage_name,point,week_number,date_from,date_to,no_of_seats,user_id,lead_id) values (1001,'Apporved',10,'" . date('W') . "','$points_date[0]','$points_date[1]','" . $_POST['quant'] . "','" . $point_user_id . "','" . $_REQUEST['id'] . "') ");
    }
    if ($_POST['caller']) {
        $userid = getSingleresult("select user_id from callers where id='" . $_POST['caller'] . "'");
        if ($userid) {
            $caller_email = getSingleresult("select email from users where id='" . $userid . "'");
            $caller_name = getSingleresult("select name from users where id='" . $userid . "'");

            $addTo[] = ($caller_email);

            $setSubject = "[LC Calling] New Lead assigned to you on DR Portal";
            $body    = "Hi,<br><br> Below account has been qualified for your LC working:-<br><br>
         <ul>
            <li><b>Account Name</b> : " . $row_data['company_name'] . " </li>
            <li><b>City</b> : " . $row_data['city'] . " </li>
            <li><b>Address</b> : " . htmlspecialchars($row_data['address'], ENT_QUOTES) . " </li>
            <li><b>Mobile</b> : " . $row_data['eu_mobile'] . " </li>
            <li><b>Reseller Name</b> : " . $row_data['r_name'] . " </li>
            <li><b>License Type</b> : " . $row_data['license_type'] . " </li>
            <li><b>Quantity</b> : " . $row_data['quantity'] . " </li></ul><br>
         
            Thanks,<br>
            SketchUp DR Portal
            ";
            if (!$_POST['dr_code']) {
                sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
            }
        }
    }
    $manager_email = getSingleresult("select email from users where user_type='MNGR' and team_id='" . $row_data['team_id'] . "'");

    $addTo[] = ($row_data['r_email']);

    $addCc[] = ("$manager_email");

    $addBcc[] = ("deepranshu.srivastava@arkinfo.in");
    if ($_POST['status'] == 'Cancelled') {
        $stat = '<span style="color:red">Unqualified</span>';
    } else if ($_POST['status'] == 'Approved') {
        $stat = '<span style="color:green">Qualified</span>';
    } else if ($_POST['status'] == 'Undervalidation') {
        $stat = '<span style="color:orange">Under Validation</span>';
    } else {
        $stat = '<span class="text-blue">On-Hold</span>';
    }
    //$userid=getSingleresult("select user_id from callers where id='".$row_data['caller']."'");

    $caller_name = getSingleresult("select name from callers where id='" . $row_data['caller'] . "'");

    $setSubject = "Lead status has been changed on DR Portal [" . $row_data['company_name'] . "]";
    $body = "Hi,<br><br> Below account status has been changed to " . $stat . " on DR Portal:-<br><br>
            <ul>
            <li><b>Account Name</b> : " . $row_data['company_name'] . " </li>
            <li><b>Lead Type</b> : " . $row_data['lead_type'] . " </li>
            <li><b>City</b> : " . $row_data['city'] . " </li>
            <li><b>Address</b> : " . htmlspecialchars($row_data['address'], ENT_QUOTES) . " <br>
            <li><b>Mobile</b> : " . $row_data['eu_mobile'] . " </li>
            <li><b>Reseller Name</b> : " . $row_data['r_name'] . " </li>
            <li><b>License Type</b> : " . $row_data['license_type'] . " </li>
            <li><b>Quantity</b> : " . $row_data['quantity'] . " </li>
            <li><b>Assigned To</b> : " . $caller_name . " </li>";
    if ($data['status'] == 'Undervalidation' || $_POST['status'] == 'Cancelled')
        $body .=    "<li><b>Reason</b> : " . $_POST['reason'] . " </li>";
    $body .=    "<li><b>Admin Comment</b> : " . htmlspecialchars($_POST['add_comment']) . " </li>";
    $body .= "</ul><br>Thanks,<br>
            SketchUp DR Portal";
    $body = $body;
    if (!$_POST['dr_code']) {
        sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
    }
    if ($row_data['quantity'] >= 9) {
        $addTo[] = ("jayesh.patel@arkinfo.in");
        $addTo[] = ("maneesh.kumar@arkinfo.in");
        $addTo[] = ("shivram@corelindia.co.in");
        $addTo[] = ("sathish.venugopal@corel.com");
        $addBcc[] = ("virendra.kumar@arkinfo.in");
        $setSubject = "Lead status has been changed on DR Portal [" . $row_data['company_name'] . "]";
        $body = "Hi,<br><br> Below account status has been changed to " . $stat . " on DR Portal:-<br><br>
            <ul>
            <li><b>Account Name</b> : " . $row_data['company_name'] . " </li>
            <li><b>Lead Type</b> : " . $row_data['lead_type'] . " </li>
            <li><b>City</b> : " . $row_data['city'] . " </li>
            <li><b>Address</b> : " . htmlspecialchars($row_data['address'], ENT_QUOTES) . " <br>
            <li><b>Mobile</b> : " . $row_data['eu_mobile'] . " </li>
            <li><b>Reseller Name</b> : " . $row_data['r_name'] . " </li>
            <li><b>License Type</b> : " . $row_data['license_type'] . " </li>
            <li><b>Quantity</b> : " . $row_data['quantity'] . " </li>
            <li><b>Assigned To</b> : " . $caller_name . " </li>";
        if ($data['status'] == 'Undervalidation' || $_POST['status'] == 'Cancelled')
            $body .=    "<li><b>Reason</b> : " . $_POST['reason'] . " </li>";
        $body .=    "<li><b>Admin Comment</b> : " . htmlspecialchars($_POST['add_comment']) . " </li>";
        $body .= "</ul></br>Thanks,<br>
            SketchUp DR Portal";

        $body = $body;
        if (!$_POST['dr_code']) {
            sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
        }
    }
    if ($sql) {

        redir("renewal_leads_admin.php?update=success", true);
    }
}
if ($_POST['remarks'] && !$_POST['activity_edit']) {
    //echo "deepranshu"; die;
    $res = db_query("insert into activity_log (pid,description,activity_type,call_subject,added_by,data_ref) values ('" . $_POST['pid'] . "','" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "','Renewal','" . $_POST['call_subject'] . "','" . $_SESSION['user_id'] . "',1)");
}
if ($_POST['activity_edit']) {
    $res = db_query("update activity_log set description='" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "',call_subject='" . $_POST['call_subject'] . "' where id=" . $_POST['pid']);
}

if ($_REQUEST['id']) {
    $sql = renewalLeadViewData('orders', $_REQUEST['id']);
    $data = db_fetch_array($sql);
    @extract($data);
} else {
    redir("renewal_leads_admin.php", true);
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

                                        <div id="collapseOne2" class="collapse" aria-labelledby="headingOne2" data-parent="#accordionExample2">
                                            <?php if ($data['lapsed_date'] && $data['lapsed_date'] != '0000-00-00 00:00:00') { ?>
                                                <li>Lapsed on <strong><?= date('F j, Y, g:i a', strtotime($data['lapsed_date'])) ?></strong></li>

                                                <?php
                                            }


                                            $sql = db_query("select * from lead_modify_log where log_status='Active' AND lead_id=" . $_REQUEST['id'] . " order by id desc");


                                            if (db_num_array($sql) > 0) {

                                                while ($data_log = db_fetch_array($sql)) { ?>

                                                    <li> <?= getSingleresult("select name from users where id=" . $data_log['created_by']) ?> has changed <strong> <?= $data_log['type'] ?> </strong> from <strong> <?= ($data_log['previous_name'] ? $data_log['previous_name'] : 'N/A') ?> </strong> to <strong> <?= $data_log['modify_name'] ?></strong> on <?= date('F j, Y, g:i a', strtotime($data_log['created_date'])) ?>.
                                                    </li>

                                            <?php


                                                    $count++;
                                                }
                                            }
                                            if (strtotime(getSingleresult("select created_date from lead_modify_log where lead_id=" . $_REQUEST['id'] . " order by id desc limit 1")) > strtotime(getSingleresult("select created_date from activity_log where pid=" . $_REQUEST['id'] . " order by id desc limit 1")))
                                                $lmb = db_query("select created_date, created_by from lead_modify_log where log_status='Active' AND lead_id=" . $_REQUEST['id'] . " order by id desc limit 1");
                                            else
                                                $lmb = db_query("select created_date as created_date, added_by as created_by  from activity_log where  pid=" . $_REQUEST['id'] . " order by id desc limit 1");
                                            $lmb_row = (db_fetch_array($lmb));
                                            ?>

                                            <div>Created by <strong><?= getSingleresult("select name from users where id=" . $created_by) ?></strong> on <strong><?= date('F j, Y, g:i a', strtotime($created_date)) ?></strong> <?php if ($lmb_row['created_by']) { ?> - Last Modified by <strong><?= getSingleresult("select name from users where id=" . $lmb_row['created_by']) ?></strong> on <strong><?= date('F j, Y, g:i a', strtotime($lmb_row['created_date'])) ?></strong><?php } ?></div>


                                        </div>

                                    </div>
                                </div>
                                <div class="card">

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

                                                            <?php if ($product_name == 'CDGS Renewal' && ($product_type == 'CDGS Perpetual' || $product_type == 'CDGS Annual')) { ?>
                                                                <button class="btn btn-primary" onclick="change_product_type('<?= $_GET['id'] ?>','<?= $type_id ?>')">Change Product Type</button>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <h5 class="card-subtitle">Reseller Info <?php if ($code) { ?> - DR Code: (<?= $code ?>)<?php } ?> - License Type:(<?= $license_type ?>)</h5>

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <table class="table ">
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
                                                        <td>Submitted By</td>
                                                        <td>
                                                            <?= getSingleresult("select name from users where id=" . $created_by) ?> &nbsp;
                                                            <?php $query = access_role_permission();
                                                            $fetch_query = db_fetch_array($query);
                                                            if ($fetch_query['edit_ownership'] == 1) { ?>
                                                                <button class="btn btn-primary" onclick="change_user('<?= $_GET['id'] ?>','<?= $_SESSION['team_id'] ?>')">Change</button>
                                                            <?php } ?>
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
                                                        <td width="35%">Company Name</td>
                                                        <td width="65%">
                                                            <?= $company_name ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Parent Company</td>
                                                        <td>
                                                            <?= $parent_company ?>
                                                        </td>
                                                    </tr>
                                                    <!-- <tr>
                                    <td>Landline Number</td>
                                    <td>
                                        <?= $landline ?>
                                    </td>
                                </tr> -->
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
                                                    <!-- <tr>
                                    <td>Landline Number</td>
                                    <td>
                                        <?= $eu_landline ?>
                                    </td>
                                </tr> -->

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
                                                    <!-- <tr>
                            <td>Usage Confirmation Received from</td>
                            <td>
                                <?= $confirmation_from ?>
                            </td>
                        </tr> -->
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
                                                        <td width="35%">Type of License</td>
                                                        <td width="65%"><?= $license_type ?></td>
                                                    </tr>

                                                    <tr>
                                                        <td>Quantity</td>
                                                        <td>
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
                                                                } else {

                                                                    $remaining_days = ceil(($closeDate - $ncdate) / 84600);
                                                                    $daysLeft = '<span style="color:green">Days Left- ' . $remaining_days . '</span>';
                                                                }

                                                                echo '<span style="color:green">Qualified</span> ' . $daysLeft;
                                                            } else if ($data['status'] == 'Cancelled') {
                                                                echo '<span class="text-danger">Unqualified</span>';
                                                            } else if ($data['status'] == 'Pending') {
                                                                echo 'Pending';
                                                            }


                                                            ?>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>Created on</td>
                                                        <td>
                                                            <?= date('d-m-Y H:i:s a', strtotime($created_date)) ?>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>Runrate/Key</td>
                                                        <td>
                                                            <?= $runrate_key ?>
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
                                                        <tr>
                                                        <?php } ?>

                                                        <form action="#" method="post" enctype="multipart/form-data">

                                                            <?php $query = access_role_permission();
                                                            $fetch_query = db_fetch_array($query); ?>
                                                            <td>Status</td>
                                                            <td>
                                                                <select <?= (($_SESSION['sales_manager'] == 1) ? 'disabled' : '') ?> onchange="status_update(this.value)" class="form-control" name="status" required />
                                                                <option value="">---Select---</option>
                                                                <option <?= (($status == 'Pending') ? 'Selected' : '') ?> value="Pending">Pending</option>
                                                                <option <?= (($status == 'Undervalidation') ? 'Selected' : '') ?> value="Undervalidation">Re-Submission Required</option>
                                                                <option <?= (($status == 'Approved') ? 'Selected' : '') ?> value="Approved">Qualified</option>
                                                                <option <?= (($status == 'Cancelled') ? 'Selected' : '') ?> value="Cancelled">Unqualified</option>
                                                                <option <?= (($status == 'On-Hold') ? 'Selected' : '') ?> value="On-Hold">On-Hold</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr id="reason" <?php if ($status != 'Cancelled') { ?> style="display:none" <?php } ?>>
                                                            <td>Reason</td>
                                                            <td>



                                                                <select <?= (($_SESSION['sales_manager'] == 1) ? 'disabled' : '') ?> class="form-control" id="reason_dd" name="reason">
                                                                    <option value="">---Select---</option>
                                                                    <option <?= (($reason == 'Already having licenses') ? 'Selected' : '') ?> value="Already having licenses">Already having licenses</option>
                                                                    <option <?= (($reason == 'Already logged account') ? 'Selected' : '') ?> value="Already logged account">Already logged account</option>
                                                                    <option <?= (($reason == 'Out Of Territory Criteria') ? 'Selected' : '') ?> value="Out Of Territory Criteria">Out Of Territory Criteria</option>
                                                                    <option <?= (($reason == 'BD Efforts are missing') ? 'Selected' : '') ?> value="BD Efforts are missing">BD Efforts are missing</option>
                                                                    <option <?= (($reason == 'Duplicate Record Found') ? 'Selected' : '') ?> value="Duplicate Record Found">Duplicate Record Found</option>
                                                                    <option <?= (($reason == 'Others') ? 'Selected' : '') ?> value="Others">Others</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr id="reason_ud" <?php if ($status != 'Undervalidation') { ?> style="display:none" <?php } ?>>
                                                            <td>Reason</td>
                                                            <td>
                                                                <select class="form-control" id="reason_ud" name="reason_ud">
                                                                    <option value="">---Select---</option>
                                                                    <option <?= (($reason == 'Unclear Remarks') ? 'Selected' : '') ?> value="Unclear Remarks">Unclear Remarks</option>
                                                                    <option <?= (($reason == 'Re-Visit Required') ? 'Selected' : '') ?> value="Re-Visit Required">Re-Visit Required</option>
                                                                    <option <?= (($reason == 'Need more clarity on usage') ? 'Selected' : '') ?> value="Need more clarity on usage">Need more clarity on usage</option>
                                                                    <option <?= (($reason == 'Incorrect Email Id') ? 'Selected' : '') ?> value="Incorrect Email Id">Incorrect Email Id</option>
                                                                    <option <?= (($reason == 'Incorrect contact number') ? 'Selected' : '') ?> value="Incorrect contact number">Incorrect contact number</option>
                                                                    <option <?= (($reason == 'Incorrect Decision Maker details') ? 'Selected' : '') ?> value="Incorrect Decision Maker details">Incorrect Decision Maker details</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr id="caller" <?php if ($status != 'Approved' && $lead_type != "LC") { ?> style="display:none" <?php } ?>>
                                                            <td>Caller</td>
                                                            <td>
                                                                <?php if (is_numeric($caller) || $caller == '') {
                                                                    $res = db_query("select callers.* from callers join users on callers.user_id=users.id where users.user_type='RCLR' OR users.user_type='RENEWAL TL' order by callers.name ASC");
                                                                ?>
                                                                    <select name="caller" id="caller" class="form-control">
                                                                        <option value="">---Select---</option>
                                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                                            <option <?= (($caller == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] . ' (' . $row['caller_id'] . ')' ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                <?php
                                                                } ?>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td>Additional Comment</td>
                                                            <td>
                                                                <textarea <?= (($_SESSION['sales_manager'] == 1) ? 'disabled' : '') ?> class="form-control" name="add_comment"><?= $add_comment ?></textarea>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Attachment</td>
                                                            <td>
                                                                <input type="file" <?= (($_SESSION['sales_manager'] == 1) ? 'disabled' : '') ?> class="form-control" name="admin_attachment">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Stage</td>
                                                            <td>
                                                                <?= $stage ?>&nbsp;<?= $add_comm ?>
                                                            </td>
                                                        </tr>
                                                        <tr id="payment" <?php if ($stage != "EU PO Issued") { ?> style="display:none" <?php } ?>>
                                                            <?php $payment_status = $data['payment_status']; ?>
                                                            <td>Payment Status</td>
                                                            <td>
                                                                <?= $data['payment_status'] ?>
                                                            </td>
                                                        </tr>
                                                        <tr id="op" <?php if (!$op_this_month) { ?> style="display:none" <?php } ?>>
                                                            <td>Order Processing for this month</td>
                                                            <td><?= $data['op_this_month'] ?></td>
                                                        </tr>
                                                        <tr id="pay_tab" <?php if ($data['payment_status'] != 'Payment in Installments') { ?> style="display:none" <?php } ?>>
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
                                                                                <?= $inst_data['date1'] ?>
                                                                            </td>
                                                                            <td>
                                                                                <p><strong>2<sup>nd</sup> Installment Date</strong></p>
                                                                            </td>
                                                                            <td>
                                                                                <?= $inst_data['date2'] ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <p><strong>Installment Amount</strong></p>
                                                                            </td>
                                                                            <td>
                                                                                <?= $inst_data['instalment1'] ?>
                                                                            </td>
                                                                            <td>
                                                                                <p><strong>Installment Amount</strong></p>
                                                                            </td>
                                                                            <td>
                                                                                <?= $inst_data['instalment2'] ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <p><strong>3<sup>rd</sup> Installment Date</strong></p>
                                                                            </td>
                                                                            <td>
                                                                                <?= $inst_data['date3'] ?>
                                                                            </td>
                                                                            <td>
                                                                                <p><strong>4<sup>th</sup> Installment Date</strong></p>
                                                                            </td>
                                                                            <td>
                                                                                <?= $inst_data['date4'] ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <p><strong>Installment Amount</strong></p>
                                                                            </td>
                                                                            <td>
                                                                                <?= $inst_data['instalment3'] ?>
                                                                            </td>
                                                                            <td>
                                                                                <p><strong>Installment Amount</strong></p>
                                                                            </td>
                                                                            <td>
                                                                                <?= $inst_data['instalment4'] ?>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Projected Close Date</td>
                                                            <td><input type="text" value="<?= $partner_close_date ?>" readonly class="form-control col-md-2 datepicker" id="cl_date" name="partner_close_date" /></td>
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
                                    <a data-toggle="modal" onclick="add_activity(<?= $_GET['id'] ?>,'<?= $company_name ?>')" data-animation="bounce" data-target=".bs-example-modal-center">
                                        <button class="btn1 btn-primary  ml-2 mt-1">Log a Call</button></a>
                                </div>
                                <div id="collapseOne9" class="" aria-labelledby="headingOne9" data-parent="#accordionExample2">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <?php
                                            $query = access_role_permission();
                                            $fetch_query = db_fetch_array($query);

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
                                                        <?php
                                                        // if ($fetch_query['edit_log'] == 1) { 
                                                        if ($_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'ADMIN') {
                                                        ?>
                                                            <td><a href="javascript:void(0)" title="Edit" id=but<?= $data_n['id'] ?> onclick="edit_activity('<?= $data_n['id'] ?>','<?= $company_name ?>')"> <i style="font-size:18px" class="mdi mdi-pencil"></i></a></td>
                                                        <?php } ?>
                                                    </tr>
                                            <?php $i--;
                                                }
                                                echo "</table>";
                                            } ?>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            </br>
                            <div class="button-items">
                                <input type="hidden" name="dr_code" value="<?= $code ?>" />
                                <?php
                                $userId = getSingleresult("select created_by from orders where id=" . $_GET['id']);
                                ?>
                                <button type="submit" onclick="" class="btn btn-success">Save</button>
                                <input type="hidden" value="<?= $created_by ?>" name="lead_by" />
                                <input type="hidden" value="<?= $quantity ?>" name="quant" />
                                </form>
                                <?php if ($status == 'Undervalidation' && $fetch_query['edit_lead'] == 1) { ?>
                                    <a href="edit_renewal_lead.php?eid=<?= $id ?>"><button type="button" class="btn1 btn-primary">Edit</button></a>
                                <?php } elseif ($status != 'Undervalidation') { ?>
                                    <a href="edit_renewal_lead.php?eid=<?= $id ?>"><button type="button" class="btn1 btn-primary">Edit</button></a>
                                <?php    }
                                ?>
                                <button type="button" onclick="javascript:history.go(-1)" class="btn1 btn-danger">Back</button>
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


    <!----end Quote stage-->

    <?php include('includes/footer.php') ?>

    <script>
        function status_update(r) {
            if (r == 'Cancelled') {
                $("#reason").show();
                $("#reason_dd").prop('required', true);
                $("#caller").hide();
                $("#caller_dd").prop('required', false);
                $("#reason_ud").hide();
                $("#sfdc_check").hide();
            } else if (r == 'Approved') {
                $("#reason_ud").hide();
                $("#reason").hide();
                $("#sfdc_check").show();
                $("#caller").show();
                //alert('Yes');
                var ltype = $("#ltype").val();
                if (ltype == 'LC') {
                    $("#caller").show();
                    $("#caller_dd").prop('required', true);
                }
            } else if (r == 'On-Hold') {
                $("#reason_ud").hide();
                $("#reason").hide();
                $("#sfdc_check").hide();
            } else {
                $("#reason").hide();
                $("#reason_ud").show();
                $("#reason_ud").prop('required', false);
                $("#reason_dd").prop('required', false);
                $("#caller").hide();
                $("#sfdc_check").hide();
                $("#caller_dd").prop('required', false);
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
            //alert(a);
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
                                        window.location = "renewal_admin_view.php?id=<?= $_GET['id'] ?>";
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
                                        window.location = "renewal_leads_admin.php?id=<?= $_GET['id'] ?>";
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