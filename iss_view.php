<?php include('includes/header.php'); 


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
    $res = db_query("insert into activity_log (pid,description,call_subject,activity_type,added_by,action_plan,data_ref) values ('" . $_POST['pid'] . "','" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "','".$_POST['call_subject']."','Lead','" . $_SESSION['user_id'] . "','" . htmlspecialchars($_POST['action_plan'], ENT_QUOTES) . "',1)");

    if($res){
        $sql = db_query("select * from orders where id='" . $_GET['id'] . "'");
        $row_data = db_fetch_array($sql);


        $addTo[] = ("pradeep.chahal@arkinfo.in");
        $addCc[] = ("virendra.kumar@arkinfo.in"); 

        $addBcc[] = '';
        $setSubject = $row_data['school_name'] . " - New Log a Call";

        // $manager_email = getSingleresult("select email from users where user_type='MNGR' and status = 'Active' and team_id=" . $row_data['team_id']);

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
}

 if ($_REQUEST['id']) {
            $sql = db_query("select o.*,p.product_name,tpp.product_type,tpp.id as type_id from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id where  o.id=" . $_REQUEST['id']);
            $data = db_fetch_array($sql);
            @extract($data);
        } else {
            redir("iss_leads.php", true);
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
										
										<ul>
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


</ul>
                                            <div class="card-body font-size-13">Created by <strong><?= getSingleresult("select name from users where id=" . $created_by) ?></strong> on <strong><?= date('F j, Y, g:i a', strtotime($created_date)) ?></strong></div>


                                        </div>

                                    </div>
                             
								
								
                                <div class="card mb-0 pt-2 shadow-none">
                                    
                                   <div class="card-header" id="headingOne4">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne4" aria-expanded="true" aria-controls="collapseOne4">
                                                   User Info
                                                </button>
                                            </h5>
                                        </div>
                    <div id="collapseOne4" class=" " aria-labelledby="headingOne4" data-parent="#accordionExample2">               

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <table class="table ">
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


</div></div>
<div class="card mb-0 pt-2 shadow-none">
<div class="card-header" id="headingOne5">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne5" aria-expanded="true" aria-controls="collapseOne5">
                                                   Product Info
                                                </button>
                                            </h5>
                                        </div>
										
				  <div id="collapseOne5" class=" " aria-labelledby="headingOne5" data-parent="#accordionExample2">						
                            
                            <div class="row">

                                <div class="col-lg-12">
                                    <table class="table" id="user">
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
                                       
                                        <!-- <?php if(!empty($product_type)){?>
                                        <button class="btn1 btn-primary" onclick="change_product_type('<?= $_GET['id'] ?>','<?=$type_id?>')">Change Product Type</button>
                                        <?php } ?> -->
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
                                                   Customer Information
                                                </button>
                                            </h5>
                                        </div>
										
                 <div id="collapseOne6" class=" " aria-labelledby="headingOne6" data-parent="#accordionExample2">                    

                                    <div class="row">

                                        <div class="col-lg-12">

                                        <table class="table ">
                            <tbody>
                                                            <tr>
                                        <td>Date of Visit</td>
                                        <td>
                                            <?= date('d-m-Y H:i:s', strtotime($created_date)) ?>
                                        </td>
                                    </tr>
                                                            
                                                            <tr>
                                                                <td>School Board</td>
                                                                <td>
                                                                    <?= $school_board ?>
                                                                    
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td>Organization Name</td>
                                                                <td>
                                                                    <?= $school_name ?>
                                                                    
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td>Is Group</td>
                                                                <td>
                                                                    <?= $is_group ?>
                                                                    <!-- <input type="hidden" name="is_group" value="<?= $is_group ?>" id="company"> -->
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td>Group Name</td>
                                                                <td>
                                                                    <?= $group_name ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="35%">Lead Source</td>
                                                                <td width="65%"><?= $source ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="35%">Sub Lead Source</td>
                                                                <td width="65%"><?= $sub_lead_source ?></td>
                                                            </tr>

                                                            <!-- <tr>
                                                                <td>Board-line Number</td>
                                                                <td>
                                                                    <?= $contact ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>School Email ID</td>
                                                                <td>
                                                                    <?= $school_email ?>
                                                                </td>
                                                            </tr> -->
                                                            <tr>
                                                                <td>State</td>
                                                                <td>
                                                                    <?= getSingleresult("select name from states where id='" . $state."' ") ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>City</td>
                                                                <td>
                                                                    <?= getSingleresult("select city from cities where id='" . $city."' ") ?>
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
                                                   Decision Maker/Proprietor/Director/End User Details
                                                </button>
                                            </h5>
                                        </div>
										
 <div id="collapseOne7" class=" " aria-labelledby="headingOne7" data-parent="#accordionExample2">

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
                                                   Lead Information
                                                </button>
                                            </h5>
                                        </div>
										
                           <div id="collapseOne8" class=" " aria-labelledby="headingOne8" data-parent="#accordionExample2">            

                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table">
                             <tbody>
                                <tr>
                                    <td width="35%">Agreement Type</td>
                                    <td width="65%"><?= $agreement_type ?></td>
                                </tr>
                                <tr>
                                    <td>Quantity</td>
                                    <td id="quant">
                                        <?= $quantity ?> User(s) <button class="btn1 btn-primary" onclick="change_quantity('<?= $quantity ?>')">Edit</button>
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
                                    <!-- <tr>
                                        <td>Caller Assigned</td>
                                        <td>
                                            <?php if (is_numeric($caller)) { ?>
                                                <?= getSingleresult("select name from callers where id='" . $caller . "'") ?>
                                            <?php } else { ?>
                                                <?= $caller ?>
                                            <?php } ?>
                                        </td>
                                    </tr> -->
                                    <?php if ($data['status'] == 'Approved') {
                                        // $stage = $data['stage'] ?>
                                            <tr>
                                                <td>Stage</td>
                                                <td>
                                                    <?=$data['stage'] ?>    <a href="javascript:void(0)" title="Change Stage" id=but<?=$data['id']?> onclick="stage_change('but<?=$data['id']?>',<?=$data['id']?>)"> <i style="font-size:18px" class="mdi mdi-update"></i></a>
                                                </td>
                                                
                                            </tr>
                         
<?php  } ?>
<tr>
                                                                <td>Expected Close Date</td>
                                                                <td><?= $expected_close_date ?></td>
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

                                            <div id="collapseOne9" class=" " aria-labelledby="headingOne9" data-parent="#accordionExample2">

                                        <div class="row">
                                            <div class="col-md-12">


                                       <?php
                                        $new = db_query("select id,description,created_date,added_by,call_subject from activity_log where pid='" . $_GET['id'] . "' and (activity_type='Lead' or activity_type='DVR') and is_intern=0 UNION SELECT id,description,created_date,added_by,id from caller_comments where pid='" . $_GET['id'] . "' union select id,comment as description,created_date,added_by,id from review_log where lead_id='" . $_GET['id'] . "' order by created_date desc");
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
										
								 
								
                                        <div class="button-items1 mt-2">
                                            <button type="button" onclick="javascript:history.go(-1)" class="btn1 btn-primary">Back</button>

                                        </div>

                                    </div>
                                </div>
                            </div> <!-- end col -->


                        </div> <!-- end row -->

                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
</div></div>
                <div id="myModal" class="modal fade" role="dialog" >

                </div>

                <!-- No Stage Self Review -->

               
                <!----end Quote stage-->
               
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
                            iss_lead_id: id,
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
    <script>
        $(document).ready(function() {
            var wfheight = $(window).height();
            $('.add_lead').height(wfheight - 240);
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