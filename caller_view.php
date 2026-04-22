<?php include('includes/header.php'); 

//if(isset($_POST['save']) && $_POST['call_subject'] != ''){
if ($_POST['remarks'] && !$_POST['activity_edit']) {
    //echo "deepranshu"; die;
    $licenseType = getSingleresult("select agreement_type from orders where id=" . $_POST['pid']);
    if ($licenseType == 'Renewal') {
        $activityType = 'Renewal';
    } else {
        $activityType = 'Lead';
    }
    // print_r($_POST);die;
    $res = db_query("insert into activity_log (pid,description,activity_type,call_subject,added_by,action_plan) values ('" . $_POST['pid'] . "','" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "','" . $activityType . "','" . $_POST['call_subject'] . "','" . $_SESSION['user_id'] . "','" . $_POST['action_plan'] . "')");

    if($res){
    
        $sql = db_query("select * from orders where id='" . $_POST['pid'] . "'");
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
//}

if ($_POST['activity_edit']) {
    $res = db_query("update activity_log set description='" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "',call_subject='" . $_POST['call_subject'] . "' where id=" . $_POST['pid']);
}

if ($_POST['save_new_user']) {

    $name_new = getSingleresult("select name from callers where id='" . $_POST['new_user'] . "'");
    $old_name = $_SESSION['name'];

    $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Caller Ownership','" . $old_name . "','" . $name_new . "',now(),'" . $_SESSION['user_id'] . "')");


    $ins = db_query("update orders set caller='" . $_POST['new_user'] . "' where id='" . $_POST['id'] . "'");
    redir("caller_view.php?id=" . $_POST['id'] . "&update=success", true);
}


if ($_REQUEST['id']) {

    $sql = db_query("select o.*,tp.*,p.product_name,tpp.product_type,tpp.id as type_id from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id where 1=1  and o.id=" . $_REQUEST['id']);
    $data = db_fetch_array($sql);
    // print_r($data); die;
    @extract($data);
} else {
    redir("caller_view.php", true);
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


                                <!-- <a href="#" id="addnewtask"><button type="button" class="btn btn-xs btn-primary ml-1 waves-effect waves-light" data-toggle="modal" data-original-title="Add New Task" style="float: right;"><i class="ti-plus text-white"></i></button></a> -->
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
                                            <ul class="p-4">
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
                                                                <input type="hidden" name="edit_id" id="edit_id" value="<?= $_REQUEST['id'] ?>" />
                                                            </tr>
                                                            <tr>
                                                                <td>Product Type</td>
                                                                <td>
                                                                    <?= $product_type ?>&nbsp;

                                                                    <?php if (!empty($product_type) && ($product_type == 'CDGS Perpetual' || $product_type == 'CDGS Annual')) { ?>
                                                                        <?php if ($product_name == 'CDGS Renewal') { ?>

                                                                            <button class="btn1 btn-primary" onclick="change_renewal_product_type('<?= $_GET['id'] ?>','<?= $type_id ?>')">Change Product Type</button>
                                                                        <?php } else {  ?>
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
                                        <div class="card-header" id="headingOne7">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne7" aria-expanded="true" aria-controls="collapseOne7">
                                                    Other Contacts
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne7" class="" aria-labelledby="headingOne7" data-parent="#accordionExample2">



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

                                        <div id="collapseOne8" class="" aria-labelledby="headingOne8" data-parent="#accordionExample2">



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
                                                                <td>Expected Close Date</td>
                                                                <td><?= $expected_close_date ?></td>
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
                                    <?php if ($data['status'] == 'Approved') {
                                        // $stage = $data['stage'] ?>
                                            <tr>
                                                <td>Stage</td>
                                                <td><?= $data['stage'] ?></td>
                                                
                                            </tr>

                                            <tr>
                                                <td>Sub Stage</td>
                                                <td><?= $data['add_comm'] ?></td>
                                                
                                            </tr>

<?php } ?>
                   
                                    
                                                        </tbody>
                                                    </table>
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
                                                            $new = db_query("select id,description,created_date,added_by,call_subject,action_plan from activity_log where (activity_type='Lead' or activity_type='DVR') and is_intern=0 and pid='" . $_GET['id'] . "' UNION SELECT id,description,created_date,added_by,id,id from caller_comments where pid='" . $_GET['id'] . "' union select id,comment as description,created_date,added_by,id,old_stage from review_log where lead_id='" . $_GET['id'] . "' order by created_date desc");
                                                            $goal = db_query("select * from activity_log where pid='" . $_GET['id'] . "' order by created_date desc");
                                                            $count = mysqli_num_rows($new);
                                                            $i = $count;
                                                            if ($count) {
                                                                echo  ' <table class="table"><tr><th>S.No</th><th>Subject</th><th>Description</th><th>Added By</th><th>Date</th></tr>';

                                                                while ($data_n = db_fetch_array($new)) { ?>

                                                                    <tr style="text-align:center">
                                                                        <td><?= $i ?></td>
                                                                        <td><?= ($data_n['call_subject'] ? $data_n['call_subject'] : 'N/A') ?></td>
                                                                        <td><?= $data_n['description'] ?></td>
                                                                        <td><?= (is_numeric($data_n['added_by']) ? getSingleresult("select concat(name,'-',CASE  WHEN user_type='USR' THEN 'Partner User' WHEN user_type='ADMIN' and sales_manager=0 THEN 'ADMIN' WHEN user_type='ADMIN' and sales_manager=1 THEN 'Sales Manager' WHEN user_type='MNGR' THEN 'Partner Manager'  ELSE 'Caller' END) as nme from users where id='" . $data_n['added_by'] . "'") : '<span style="color:red">(Lead Review!)</span> ' . $data_n['added_by']) ?></td>
                                                                        <td><?= date('d-m-Y H:i:s', strtotime($data_n['created_date'])) ?></td>
                                                                        <!-- <td><a href="javascript:void(0)" title="Edit" id=but<?= $data['id'] ?> onclick="edit_activity('<?= $data_n['id'] ?>')"> <i style="font-size:18px" class="mdi mdi-pencil"></i></a></td> -->
                                                                    </tr>
                                                            <?php $i--;
                                                                }
                                                                echo "</table>";
                                                            } ?>


                                                        </div>

                                                    </div>

                                                    <div class="card mb-0 pt-2 shadow-none">
                                                        <div class="card-header" id="headingOne9">
                                                            <h5 class="my-0">
                                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne9" aria-expanded="true" aria-controls="collapseOne9">
                                                                    BD to LC History
                                                                </button>
                                                            </h5>

                                                        </div>

                                                        <div id="collapseOne9" class="" aria-labelledby="headingOne9" data-parent="#accordionExample2">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <?php
                                                                    $new = db_query("select * from lead_notification where title in ('Request BD to LC','Request Incoming to LC') and type_id='" . $_GET['id'] . "'  order by created_at desc");

                                                                    $count = mysqli_num_rows($new);
                                                                    $i = $count;
                                                                    if ($count) {
                                                                        echo  ' <table class="table"><thead class="thead-default"><tr><th>S.No</th><th>Reason to initiate LC Call</th><th>Usage Confirmed</th><th>Visit Done</th><th>Added By</th><th>Date</th></tr></thead>';

                                                                        while ($data_n = db_fetch_array($new)) { ?>
                                                                            <tbody>
                                                                                <tr style="text-align:center;">
                                                                                    <td><?= $i ?></td>
                                                                                    <td><?= ($data_n['initiate_reason'] ? $data_n['initiate_reason'] : 'N/A') ?></td>
                                                                                    <td><?= $data_n['usage_confirmed'] ?></td>
                                                                                    <td><?= $data_n['visit_done'] ? $data_n['visit_done'] : 'N/A' ?></td>
                                                                                    <td><?= (is_numeric($data_n['sender_id']) ? getSingleresult("select concat(name,'-',CASE  WHEN user_type='USR' THEN 'Partner User' WHEN user_type='ADMIN' and sales_manager=0 THEN 'ADMIN' WHEN user_type='ADMIN' and sales_manager=1 THEN 'Sales Manager' WHEN user_type='MNGR' THEN 'Partner Manager' WHEN user_type='OPERATIONS' THEN 'OPERATIONS' WHEN user_type='INTERN' THEN 'COREL INTERN' ELSE 'Caller' END) as nme from users where id='" . $data_n['sender_id'] . "'") : '<span style="color:red">(Lead Review!)</span> ' . $data_n['sender_id']) ?></td>
                                                                                    <td><?= date('d-m-Y H:i:s', strtotime($data_n['created_at'])) ?></td>

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

                                                    <div class="button-items1 mt-2">

                                                        <?php $cid = getSingleresult("select id from callers where user_id='" . $_SESSION['user_id'] . "'");
                                                        if ($cid == $caller) { ?>
                                                            <a href="edit_caller.php?eid=<?= $id ?>"><button type="button" class="btn1 btn-primary">Edit</button></a>
                                                        <?php } ?>
                                                        <button type="button" onclick="javascript:history.go(-1)" class="btn1 btn-primary">Back</button>

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
                    function chage_stage(a) {
                        if (a == 'Closed Lost') {
                            $("#add_comment").show();
                            $("#add_comment_dd").attr("required", "required");
                        } else {
                            $("#add_comment").hide();
                            $('#add_comment_dd').removeAttr("required", "required");
                        }
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



                    });
                    $(document).ready(function() {
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

                    function change_caller(id) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajax_change_caller.php',
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

                    function change_renewal_product_type(rl_id, type) {
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

                    function change_product_type(id, type) {
                        //alert(id);
                        //alert(type);
                        if (type == 1) {
                            var type = 2;
                        } else {
                            var type = 1;
                        }
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
                </script>
                <script>
                    $(document).ready(function() {
                        var wfheight = $(window).height();
                        $('.add_lead').height(wfheight - 220);
                    });
                </script>