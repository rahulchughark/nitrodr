<?php include('includes/header.php'); ?>

<?php



if ($_POST['stage']) { 


    $res = db_query("update orders set stage='" . $_POST['stage'] . "',prospecting_date='" . date('Y-m-d') . "',payment_status='" . $_POST['payment_status'] . "',add_comm='" . $_POST['add_comm'] . "' where id='" . $_GET['id'] . "'");

    if ($_POST['payment_status'] == 'Payment in Installments') {
        $ps = db_query("insert into installment_details (`pid`, `type`, `date1`, `instalment1`, `date2`, `instalment2`, `date3`, `instalment3`, `date4`, `instalment4`, `added_by`) values ('" . $_GET['id'] . "','Lead','" . $_POST['date1'] . "','" . $_POST['instalment1'] . "','" . $_POST['date2'] . "','" . $_POST['instalment2'] . "','" . $_POST['date3'] . "','" . $_POST['instalment3'] . "','" . $_POST['date4'] . "','" . $_POST['instalment4'] . "','" . $_SESSION['user_id'] . "')");
    } else {
        $ps = db_query("update orders set op_this_month='" . $_POST['op'] . "' where id='" . $_GET['id'] . "'");
    }


    redir("iss_leads.php?update=success", true);
}
if ($_POST['submit_dvr']) {
    $sql = db_query("update orders set dvr_flag=0,is_dr=1,convert_date='" . date('Y-m-d H:i:s') . "' where id=" . $_GET['id']);
    redir("iss_leads.php?update=success", true);
}
if ($_POST['remarks']) {
    $res = db_query("insert into activity_log (pid,description,activity_type,added_by) values ('" . $_POST['pid'] . "','" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "','Lead','" . $_SESSION['user_id'] . "')");
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

            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <?php if ($_REQUEST['id']) {
            $sql = db_query("select o.*,p.product_name,tpp.product_type,tpp.id as type_id from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id where o.created_by=" . $_SESSION['user_id'] . " and o.id=" . $_REQUEST['id']);
            $data = db_fetch_array($sql);
            @extract($data);
        } else {
            redir("iss_leads.php", true);
        }
        ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Lead Modify Log</h4>
                        <button id="modify_log" class="btn btn-primary pull-right" style="margin-top: -28px;"> Show </button>
                        <h6 class="card-subtitle"></h6>
                        <div id="modify_log_div">

                            <ul class="font_weight list-style-none">
                                <?php

                                $sql = db_query("select * from lead_modify_log where log_status='Active' AND lead_id=" . $_REQUEST['id'] . " order by id desc");


                                if (db_num_array($sql) > 0) {

                                    while ($data = db_fetch_array($sql)) { ?>

                                        <li> <?= getSingleresult("select name from users where id=" . $data['created_by']) ?> has changed <strong> <?= $data['type'] ?> </strong> from <strong> <?= $data['previous_name'] ?> </strong> to <strong> <?= $data['modify_name'] ?></strong> on <?= date('F j, Y, g:i a', strtotime($data['created_date'])) ?>.
                                        </li>

                                <?php


                                        $count++;
                                    }
                                }  ?>
                                <li>Created by <strong><?= getSingleresult("select name from users where id=" . $created_by) ?></strong> on <strong><?= date('F j, Y, g:i a', strtotime($created_date)) ?></strong></li>


                            </ul>

                        </div>

                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">User Info</h4>
                        <h6 class="card-subtitle"></h6>
                        <table style="clear: both" class="table table-bordered table-striped" id="user">
                            <tbody>
                                <tr>
                                    <td width="35%">Partner Name</td>
                                    <td width="65%"><?= $r_name ?></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>
                                        <?= $r_email ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Submitted By</td>
                                    <td>
                                        <?= $r_user ?>
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
                                    <input type="hidden" name="edit_id" id="edit_id" value="<?= $_REQUEST['id'] ?>" />
                                </tr>
                                <tr>
                                    <td>Product Type</td>
                                    <td>
                                        <?= $product_type ?>&nbsp;
                                       
                                        <?php if(!empty($product_type)){?>
                                        <button class="btn btn-primary" onclick="change_product_type('<?= $_GET['id'] ?>','<?=$type_id?>')">Change Product Type</button>
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


                                        <?php
                                        $new = db_query("select id,description,created_date,added_by from activity_log where pid='" . $_GET['id'] . "' UNION SELECT id,description,created_date,added_by from caller_comments where pid='" . $_GET['id'] . "' order by created_date desc");
                                        $goal = db_query("select * from activity_log where pid='" . $_GET['id'] . "' order by created_date desc");
                                        $count = mysqli_num_rows($new);
                                        $i = $count;
                                        if ($count) {
                                            echo  ' <br/>';
                                            while ($data_n = db_fetch_array($new)) { ?>
                                                <?= $i . '. [' . ($data_n['added_by'] ? getSingleresult("select name from users where id='" . $data_n['added_by'] . "'") : 'N/A') . ' on ' . date('d-m-Y H:i:s', strtotime($data_n['created_date'])) . ']: <b>' . $data_n['description'] . '</b><br/>' ?>
                                        <?php $i--;
                                            }
                                        } ?>

                                        <?= date('d-m-Y H:i:s', strtotime($created_date)) ?>: <?= $visit_remarks ?>

                                        <button onclick="add_activity(<?= $_GET['id'] ?>)" class="btn btn-primary">Log a Call</button>&nbsp;
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
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Lead Information</h4>
                        <h6 class="card-subtitle"></h6>
                        <table style="clear: both" class="table table-bordered table-striped" id="user">
                            <tbody>
                                <tr>
                                    <td width="35%">Type of License</td>
                                    <td width="65%"><?= $license_type ?></td>
                                </tr>
                                <tr>
                                    <td>Quantity</td>
                                    <td id="quant">
                                        <?= $quantity ?> User(s) <button class="btn btn-primary" onclick="change_quantity('<?= $quantity ?>')">Edit</button>
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


                                    <?php if ($data['status'] == 'Approved') {
                                        $stage = $data['stage'] ?>
                                        <form action="#" method="post" name="form_view">
                                            <tr>
                                                <td>Stage</td>
                                                <td>
                                                    <select name="stage" onchange="chage_stage(this.value)" class="form-control">
                                                        <option value="">--Select--</option>
                                                        <option <?= (($stage == 'Prospecting') ? 'selected' : '') ?> value="Prospecting">Prospecting</option>
                                                        <option <?= (($stage == 'Follow-up') ? 'selected' : '') ?> value="Follow-up">Follow-up</option>
                                                        <option <?= (($stage == 'Quote') ? 'selected' : '') ?> value="Quote">Quote</option>
                                                        <option <?= (($stage == 'Negotiation') ? 'selected' : '') ?> value="Negotiation">Negotiation</option>
                                                        <option <?= (($stage == 'Commit') ? 'selected' : '') ?> value="Commit">Commit</option>
                                                        <option <?= (($stage == 'EU PO Issued') ? 'selected' : '') ?> value="EU PO Issued">EU PO Issued</option>
                                                        <option <?= (($stage == 'Booking') ? 'selected' : '') ?> value="Booking">Booking</option>
                                                        <option <?= (($stage == 'OEM Billing') ? 'selected' : '') ?> value="OEM Billing">OEM Billing</option>
                                                        <option <?= (($stage == 'Closed Lost') ? 'selected' : '') ?> value="Closed Lost">Closed Lost</option>

                                                    </select>
                                                </td>
                                            </tr>
                                            <?php /* <tr>
										<td>Prospecting Date</td>
										<td><input type="text" name="prospecting_date" value='<?=date('Y-m-d')?>' class="datepicker" /></td>
										</tr> */ ?>
                                            <tr id="add_comment" <?php if ($stage != "Closed Lost") {
                                                                        $add_comm = $data['add_comm'] ?> style="display:none" <?php } ?>>
                                                <td>Sub Stage</td>
                                                <td>
                                                    <select id="add_comment_dd" name="add_comm" class="form-control">
                                                        <option value="">--Select--</option>
                                                        <option <?= (($add_comm == 'Budget Issues') ? 'selected' : '') ?> value="Budget Issues">Budget Issues</option>
                                                        <option <?= (($add_comm == 'Future Requirement') ? 'selected' : '') ?> value="Future Requirement">Future Requirement</option>
                                                        <option <?= (($add_comm == 'Lack of Product Support') ? 'selected' : '') ?> value="Lack of Product Support">Lack of Product Support</option>
                                                        <option <?= (($add_comm == 'Other') ? 'selected' : '') ?> value="Other">Other</option>
                                                        <option <?= (($add_comm == 'Price Services') ? 'selected' : '') ?> value="Price Services">Price Services</option>
                                                        <option <?= (($add_comm == 'Price Software') ? 'selected' : '') ?> value="Price Software">Price Software</option>
                                                        <option <?= (($add_comm == 'Received Declaration') ? 'selected' : '') ?> value="Received Declaration">Received Declaration</option>
                                                        <option <?= (($add_comm == 'Selling Process') ? 'selected' : '') ?> value="Selling Process">Selling Process</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr id="payment" <?php if ($stage != "EU PO Issued") { ?> style="display:none" <?php } ?>>
                                                <?php $payment_status = $data['payment_status']; ?>
                                                <td>Payment Status</td>
                                                <td>
                                                    <select <?php if ($data['payment_status']) {
                                                                echo 'disabled';
                                                            } ?> onchange="payment_option(this.value)" id="payment_dd" name="payment_status" class="form-control">
                                                        <option value="">--Select--</option>
                                                        <option <?= (($payment_status == '100% Payment Received') ? 'selected' : '') ?> value="100% Payment Received">100% Payment Received</option>
                                                        <option <?= (($payment_status == 'Payment After Software Delivery') ? 'selected' : '') ?> value="Payment After Software Delivery">Payment After Software Delivery</option>
                                                        <option <?= (($payment_status == 'Payment in Installments') ? 'selected' : '') ?> value="Payment in Installments">Payment in Installments</option>
                                                        <option <?= (($payment_status == 'Payment Not Clear') ? 'selected' : '') ?> value="Payment Not Clear">Payment Not Clear</option>

                                                    </select>
                                                </td>
                                            </tr>
                                            <tr id="op" <?php if (!$op_this_month) { ?> style="display:none" <?php } ?>>
                                                <td>Order Processing for this month</td>
                                                <td><input type="radio" name="op" value='Yes' <?= (($op_this_month == 'Yes') ? 'checked' : 'checked') ?> class="radio" id="opy" /><label for="opy">Yes</label><input <?= (($op_this_month == 'No') ? 'checked' : '') ?> type="radio" name="op" class="radio-col-red" value='No' id="opn" /><label for="opn">No</label></td>
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
                                                                    <input type="number" autocomplete="off" value=' <?= $inst_data['instalment3'] ?>' class="form-control" name="instalment3" min="0" />
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
                                            <td colspan='2'>

                                                <button type="button" onclick="javascript:history.go(-1)" class="btn btn-inverse">Back</button></td>

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
    <?php include('includes/footer.php') ?>

    <?php if (mysqli_num_rows(db_query("select * from tbl_lead_product where lead_id=" . $_REQUEST['id'])) == 0) { ?>
        <script>
            var id = $('#edit_id').val();
            //alert(id);
            $(function() {
                $.ajax({
                    type: 'POST',
                    url: 'lead_product.php',
                    data: {
                        id: id
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
        function chage_stage(a) {
            if (a == 'Closed Lost') {
                $("#add_comment").show();
                $("#add_comment_dd").attr("required", "required");
                $("#payment").hide();
                $("#payment_dd").removeAttr("required", "required");
                $("#op").hide();
                $("#pay_tab").hide();
            } else if (a == 'EU PO Issued') {
                $("#add_comment").hide();
                $("#add_comment_dd").removeAttr("required", "required");
                $("#payment").show();
                $("#payment_dd").attr("required", "required");
            } else {
                $("#add_comment").hide();
                $('#add_comment_dd').removeAttr("required", "required");
                $("#payment").hide();
                $("#payment_dd").removeAttr("required", "required");
                $("#op").hide();
                $("#pay_tab").hide();
            }
        }

        function payment_option(val) {
            //alert(val);
            if (val == '100% Payment Received' || val == 'Payment After Software Delivery') {
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
                                if (result) {
                                    swal({
                                        title: "Done!",
                                        text: "Lead Re-Loged.",
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

        function change_product_type(id,type) {
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
                            type:type,

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
    </script>