<?php
include('includes/header.php');
include_once('helpers/DataController.php');

// admin_page();
?>
<?php

        $sql=db_query("select * from orders where id=".$_REQUEST['id']);
        $data=db_fetch_array($sql);
        $dataObj = new DataController;

        @extract($data);
        
        $licType = $license_type;

        $_REQUEST['id'] = intval($_REQUEST['id']);

        $sqll = db_query("select o.* from orders as o where o.id='" . $_REQUEST['id'] . "'");
        $sql_order_important = db_query("select eu_person_name,eu_designation,eu_mobile,eu_email from order_important_person where order_id='" . $_REQUEST['id'] . "'");
        
        $row_data = db_fetch_array($sqll);
        
        if($sql->num_rows == 0)
        {
            redir("manage_orders.php?m=nodata", true);
        }
        $allowed_extensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'csv', 'txt', 'rtf', 'odt', 'jpg', 'jpeg', 'png', 'gif', 'webp','mp4', 'mov', 'avi', 'mkv', 'webm', 'flv'];
        $allowed_mime_types = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
            'text/csv',
            'application/rtf',
            'application/vnd.oasis.opendocument.text',
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'video/mp4',
            'video/quicktime',
            'video/x-msvideo',
            'video/x-matroska',
            'video/webm',
            'video/x-flv',
        ];        
        if (isset($_POST['new_user'])) {
            if($_SESSION['team_id'] == '155' || $_SESSION['team_id'] == '45')
            {
                $cont_final = 1;
            }else{
                // $count_today = getSingleresult("select count(*) from orders where date(created_date)='" . date('Y-m-d') . "' and created_by='" . $_POST['new_user'] . "' and agreement_type='Fresh' ");
                // $cont_final = 10 - $count_today;
                $cont_final = 1;
            }
        
            // if ($cont_final > 0) {
                $email_new = getSingleresult("select email from users where id=" . $_POST['new_user']);
                $name_new = getSingleresult("select name from users where id=" . $_POST['new_user']);
                $old_name = getSingleresult("select name from users where id=" . $row_data['created_by']);
                // $modify_name=getSingleresult("select name from users where id=".$_POST['new_user']);

                $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_REQUEST['id']."','Ownership','". $old_name."','". $name_new."',now(),'".$_SESSION['user_id']."')");
        
                $sqlc=db_query("update orders set created_by='".$_POST['new_user']."',r_user='".$name_new."',r_email='".$email_new."' where id=".$_REQUEST['id']);
        
                redir("view_opportunity.php?id=" . $_POST['id'], true);
            // }else{
            //     echo "<script>alert('Daily Quota Reached.');</script>";
            // }
        }
        
        
        if ($_POST['remarks'] && !$_POST['activity_edit']) {   

            $res = db_query("insert into activity_log(`pid`,description,`activity_type`,`call_subject`,`added_by`,`action_plan`,data_ref)values('".$_POST['pid']."','".htmlspecialchars($_POST['remarks'], ENT_QUOTES)."','Lead','". $_POST['call_subject']."',".$_SESSION['user_id'].",'".$_POST['action_plan']."',1)");

            if($_POST['reminder'] == 1){
            $_POST['last_activity_log_id'] = mysqli_insert_id($GLOBALS['dbcon']);                     
            $dataObj->insertReminderLog($_POST,$_SESSION['user_id']);                
            }  
       
        
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
            $addCc[] = ("pooja.chauhan@ict360.com"); 

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

            echo '<script type="text/javascript">window.location.href = "view_opportunity.php?id='.$_GET['id'].'";</script>';
        }
        
        if ($_POST['activity_edit']) {
            $res = db_query("update activity_log set description='" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "',call_subject='" . $_POST['call_subject'] . "',action_plan='" . $_POST['action_plan'] . "' where id=" . $_POST['pid']);
        }
        if($_POST['lead_id'] && $_POST['product_id'] && $_POST['form_type'] != 'update')
        {

            $attachment_types = ['po_attachments', 'pi_attachments', 'invoice_attachments'];
            $saved_files = [];
            $upload_dir = "uploads/";
            foreach ($attachment_types as $attachment_type) {
                if (!empty($_FILES[$attachment_type]['name'][0])) {
                    foreach ($_FILES[$attachment_type]['name'] as $key => $filename) {
                        $tmp_name = $_FILES[$attachment_type]['tmp_name'][$key];
                        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
                        if (in_array($file_ext, $allowed_extensions)) {
                        $new_filename = time() . "_" . uniqid() . "." . $file_ext;
                        $filepath = $upload_dir . $new_filename;
                        $filename = preg_replace('/[^a-zA-Z0-9\s]/', '', $filename);
                        // Move uploaded file to server directory
                        if (move_uploaded_file($tmp_name, $filepath)) {
                            $query = "INSERT INTO opportunity_attachments (lead_id, product_id, attachment_name, attachment_path, attachment_type, added_by,tbl_lead_product_id) VALUES (".$_POST['lead_id'].",".$_POST['product_id'].", '".$filename."', '".$filepath."', '".$attachment_type."',".$_SESSION['user_id'].",'".$_POST['tbl_lead_opportunity_id']."')";
                            db_query($query);
            
                            $saved_files[] = $filename;
                        }
                        }else{
                            echo "<script>alert('Sorry, wrong file type!')</script>";
                        }
                    }
                }
            }
            if(count($saved_files) > 0){
                $filess = implode("  ,  ",$saved_files);
                $msg = 'Files saved : '.$filess;
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: '".$msg."',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'view_renewal_opportunity.php?id=".$_POST['lead_id']."';
                        }
                    });
                </script>";
                    }
            // redir("view_renewal_opportunity.php?id=" . $_POST['lead_id'], true);
        }else if($_POST['lead_id'] && $_POST['product_id'] && $_POST['form_type'] == 'update'){
            $attachment_ids = $_POST['attachments_ids'];
            $uploaded_files = $_FILES['attachments'];
            
            $upload_dir = "uploads/";
            
            // echo "<br><br><br><br><pre>";
            // print_r($attachment_ids);
            // print_r($_FILES);die;
            foreach ($attachment_ids as $key => $attachment_id) {

                if ($uploaded_files['size'][$key] > 0) {
                    $tmp_name = $uploaded_files['tmp_name'][$key];
                    $fname = $uploaded_files['name'][$key];
                    $file_ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
                    if (in_array($file_ext, $allowed_extensions)) {
                        $file_name = time() . "_" . basename($uploaded_files['name'][$key]); // Rename file
                        $target_file = $upload_dir . $file_name;
                        $fname = preg_replace('/[^a-zA-Z0-9\s]/', '', $fname);
                        if (move_uploaded_file($tmp_name, $target_file)) {
                            $attachmentType = getSingleResult("SELECT attachment_type FROM opportunity_attachments where id=".$attachment_id);
                            db_query("UPDATE opportunity_attachments SET status='0' WHERE id='$attachment_id'");
                            $query = "INSERT INTO opportunity_attachments (lead_id, product_id, attachment_name, attachment_path, attachment_type, added_by,tbl_lead_product_id) VALUES (".$_POST['lead_id'].",".$_POST['product_id'].", '".$fname."', '".$target_file."', '".$attachmentType."',".$_SESSION['user_id'].",".$_POST['tbl_lead_opportunity_id'].")";
                            db_query($query);
                            $saved_files[] = $fname;
                        }
                        }else{
                        echo "<script>alert('Sorry, wrong file type!')</script>";
                    }
                }
            }
            if(count($saved_files) > 0){
                $filess = implode("  ,  ",$saved_files);
                $msg = 'Files saved : '.$filess;
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                echo "<script>
                        Swal.fire({
                            title: 'Success!',
                            text: '".$msg."',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'view_renewal_opportunity.php?id=".$_POST['lead_id']."';
                            }
                        });
                    </script>";
            }
            // redir("view_renewal_opportunity.php?id=" . $_POST['lead_id'], true);
        }        
        ?>

<!-- Page wrapper  -->
<!-- ============================================================== -->
 <style>
    .add_lead {
        padding: 15px 0;
    }
 </style>
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home > View Lead</small>
                                    <h4 class="font-size-14 m-0 mt-1">View Lead</h4>
                                </div>
                                <div class="col-auto">
                                    <a href="helpdesk.php?id=<?=$data['id']?>&school_id=<?=$data['kms_school_id']?>&type=renewal" 
                                    class="btn1 btn-primary mt-1"
                                   
                                    data-animation="bounce" 
                                    data-target=".bs-example-modal-center">
                                        View Tickets
                                    </a>

                                    <a href="training.php?id=<?=$data['id']?>&school_id=<?=$data['kms_school_id']?>&type=opportunity" 
                                    class="btn1 btn-primary mt-1"
                                   
                                    data-animation="bounce" 
                                    data-target=".bs-example-modal-center">
                                        View Training
                                    </a>

                                    <!-- <a data-toggle="modal" onclick="viewTicket(<?=$data['kms_school_id'] ? $data['kms_school_id'] : 0?>, 
                                    '<?=$data['school_name']?>')" data-animation="bounce" data-target=".bs-example-modal-center">
                                        <button class="btn1 btn-primary mt-1">View Tickets </button></a> -->
                                </div>
                                <a href="#" id="addCopy">
                                    <button type="button" class="btn btn-xs  ml-1 waves-effect btn-primary waves-light clonner-page" data-toggle="modal" data-original-title="Copy Lead as New" data-animation="bounce" data-target=".bs-example-modal-task" style="float: right;"><i class="ti-reload" data-toggle="tooltip" data-placement="left" title="" data-original-title="Copy Lead as New"></i></button>
                                </a>

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
                                            <?php if ($data['lapsed_date'] && $data['lapsed_date'] != '0000-00-00 00:00:00' && $data['lapsed_date'] != '' ) { ?>
                                                <!-- <div class="card-body font-size-13">Lapsed on <strong><?= date('F j, Y, g:i a', strtotime($data['lapsed_date'])) ?></strong></div> -->
                                                <?php
                                            }

                                            $sql = db_query("select * from lead_modify_log where log_status='Active' AND lead_id=" . $_REQUEST['id'] . " order by id desc");
                                            if (db_num_array($sql) > 0) {
                                                while ($data_lml = db_fetch_array($sql)) { ?>
                                                    <div class="card-body font-size-13"> <?= $data_lml['created_by'] == 0 ? 'CLM User' : getSingleresult("select name from users where id=" . $data_lml['created_by']) ?> has changed <strong> <?= $data_lml['type'] ?> </strong> from <strong> <?= ($data_lml['previous_name'] ? $data_lml['previous_name'] : 'N/A') ?> </strong> to <strong> <?= $data_lml['modify_name'] ?></strong> on <?= date('F j, Y, g:i a', strtotime($data_lml['created_date'])) ?>.
                                                    </div>
                                            <?php
                                                    $count++;
                                                }
                                            }
                                            if (strtotime(getSingleresult("select created_date from lead_modify_log where lead_id=" . $_REQUEST['id'] . " order by id desc limit 1")) > strtotime(getSingleresult("select created_date from activity_log where pid=" . $_REQUEST['id'] . " order by id desc limit 1"))) {
                                                $lmb = db_query("select created_date, created_by from lead_modify_log where log_status='Active' AND lead_id=" . $_REQUEST['id'] . " order by id desc limit 1");
                                            } else {
                                                $lmb = db_query("select created_date as created_date, added_by as created_by  from activity_log where  pid=" . $_REQUEST['id'] . " order by id desc limit 1");
                                            }
                                            $lmb_row = (db_fetch_array($lmb));
                                            ?>

                                            <div class="card-body font-size-13">Created by <strong><?= $created_by == 0 ? 'CLM User' : getSingleresult("select name from users where id=" . $created_by) ?></strong> on <strong><?= date('F j, Y, g:i a', strtotime($created_date)) ?></strong> <?php if ($lmb_row['created_by']) { ?> - Last Modified by <strong><?= getSingleresult("select name from users where id=" . $lmb_row['created_by']) ?></strong> on <strong><?= date('F j, Y, g:i a', strtotime($lmb_row['created_date'])) ?></strong><?php } ?></div>


                                        </div>

                                    </div>

                                    <div class="card mb-0 pt-2 shadow-none">
                                        <div class="card-header" id="headingOne8">
                                            <h5 class="my-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne8" aria-expanded="true" aria-controls="collapseOne8">
                                                    Activity History
                                                </button>
                                            </h5>
                                            <a data-toggle="modal" onclick="add_activity(<?= $_GET['id'] ?>,'<?= $company_name ?>')" data-animation="bounce" data-target=".bs-example-modal-center"><button class="btn1 btn-primary mt-1">Log a Call</button></a>
                                        </div>

                                        <div id="collapseOne8" class="" aria-labelledby="headingOne8" data-parent="#accordionExample2">


                                            <div class="row">
                                                <div class="col-md-12">

                                                    <?php
                                                    $query = access_role_permission();
                                                    $fetch_query = db_fetch_array($query);

                                                    $new = db_query("select id,description,created_date,added_by,call_subject,action_plan,location,latitude,longitude from activity_log where is_intern=0 and (activity_type='Lead' or activity_type='DVR') and pid='" . intval($_GET['id']) . "' UNION SELECT id,description,created_date,added_by,id,id,id,id,id from caller_comments where pid='" . intval($_GET['id']) . "' union select id,comment as description,created_date,added_by,id,old_stage,old_caller,old_caller,old_caller from review_log where lead_id='" . intval($_GET['id']) . "' order by created_date desc");

                                                    $goal = db_query("select * from activity_log where pid='" . intval($_GET['id']) . "' order by created_date desc");

                                                    $count = mysqli_num_rows($new);
                                                    $i = $count;
                                                    if ($count) {
                                                        echo  ' <div class="table-responsive"><table class="table"><thead class="thead-default"><tr><th>S.No</th><th>Subject</th><th style="min-width: 200px">Description</th><th>Added By</th><th>Location</th><th>Date</th><th>Data Reference</th> <th>Actions</th> <tbody>';

                                                        if ($fetch_query['edit_log'] == 1) {
                                                            '<th>Action</th>';
                                                        }
                                                        '</tr></thead>';

                                                        while ($data_n = db_fetch_array($new)) {  ?>
                                                        
                                                            
                                                                <tr>
                                                                    <td><?= $i ?></td>
                                                                    <td><?= ($data_n['call_subject'] ? $data_n['call_subject'] : 'N/A') ?></td>
                                                                    <td><?= $data_n['description'] ?></td>
                                                                    <td><?= (is_numeric($data_n['added_by']) ? getSingleresult("select concat(name,'-',CASE  WHEN user_type='USR' THEN 'Partner User'    WHEN (user_type='ADMIN' ||user_type='SUPERADMIN'||user_type='OPERATIONS') and sales_manager=0 THEN 'ADMIN' WHEN user_type='SALES MNGR' and sales_manager=1 THEN 'Sales Manager'   WHEN user_type='MNGR' THEN 'Partner Manager'  ELSE 'Caller' END) as nme from users where id='" . $data_n['added_by'] . "'") : '<span style="color:red">(Lead Review!)</span> ' . $data_n['added_by']) ?></td>
                                                                    <td><?php if($data_n['latitude'] && $data_n['longitude']) {?><a target='_blank' href='location.php?latitude=<?= $data_n['latitude'] ?>&longitude=<?= $data_n['longitude'] ?>'><img src="images/locationicon.png" alt="Location" style="width: 20px;height: 20px;"></a><?=$data_n['location']?><?php  } ?> </td>
                                                                    <td><?= date('d-m-Y H:i:s', strtotime($data_n['created_date'])) ?></td>
                                                                    <td><?php 
                                                                    $data_ref = getSingleresult("select data_ref from activity_log where id=".$data_n['id']);
                                                                        if($data_ref == 2){
                                                                            echo 'APP';
                                                                        }elseif($data_ref == 1){
                                                                            echo 'WEB';
                                                                        }else{
                                                                            echo '';
                                                                        }
                                                                        ?></td>
                                                                    <?php
                                                                    if ($fetch_query['edit_log'] == 1) { ?>
                                                                        <td><a href="javascript:void(0)" title="Edit" id=but<?= $data['id'] ?> onclick="edit_activity('<?= $data_n['id'] ?>','<?= $company_name ?>')"> <i style="font-size:18px" class="mdi mdi-pencil"></i></a></td>

                                                                    <?php } ?>
                                                                </tr>
                                                            
                                                    <?php $i--;
                                                        }
                                                        echo "</tbody></table></div>";
                                                    } ?>
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
                        <td>School Name</td>
                                                                    <td>
                                                                        <?= $school_name ?> <?php if ($school_name) { ?><a class="duplicate_check" data-value="<?= $school_name ?>" href="javascript:void(0)" data-url="school_name" style="float:right"><?= getSingleresult("select count(id) from orders where is_opportunity=0 and school_name ='" . $school_name . "' and id!='" . $id . "' ") ?> possible duplicate with this School Name</a><?php } ?>                                                 
                                                                    </td>
                                                                </tr>                                                      
                                                            <tr>
                                                                <td>School Board</td>
                                                                <td>
                                                                    <?= $school_board ?>                                                                    
                                                                </td>
                                                            </tr>
                    <tr>
                        <td width="35%">Full Name</td>
                        <td width="65%"> <?= $eu_name ?></td>
                    </tr>
                    <tr>
                            <td width="35%">Designation</td>
                            <td width="65%"> <?= $eu_designation ?></td>
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
                                                                <td>Submitted By</td>
                                                                <td><?= $r_user?>
                                                                <?php $query = access_role_permission();
                                                                        $fetch_query = db_fetch_array($query);
                                                                        if ($fetch_query['edit_ownership'] == 1) { ?>
                                                                            <button class="btn1 btn-primary" onclick="change_user('<?= $_GET['id'] ?>','<?= $_SESSION['team_id'] ?>')">Change</button>
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
                                                                    <td>School Name</td>
                                                                    <td>
                                                                        <?= $school_name ?> <?php if ($school_name) { ?><a class="duplicate_check" data-value="<?= $school_name ?>" href="javascript:void(0)" data-url="school_name" style="float:right"><?= getSingleresult("select count(id) from orders where is_opportunity=0 and school_name ='" . $school_name . "' and id!='" . $id . "' ") ?> possible duplicate with this School Name</a><?php } ?>                                                 
                                                                    </td>
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
                                                <!-- Important person Information -->
                                                Champions
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseOne6" class="" aria-labelledby="headingOne6" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <table class="table" id="user">
                                                    <tbody>
                                                               
                                                               <?php while($euData=db_fetch_array($sql_order_important)) {
                                                                   
                                                                ?>
                                                            
                                                               <tr>
                                                                   <td width="35%">Person Name</td>
                                                                   <td width="65%"> <?= $euData['eu_person_name'] ?></td>
                                                               </tr>
                                                               <tr>
                                                                   <td>Designation</td>
                                                                   <td>
                                                                       <?= $euData['eu_designation'] ?>
                                                                   </td>
                                                               </tr>
                                                               <tr>
                                                                   <td>Contact Number</td>
                                                                   <td>
                                                                       <?= $euData['eu_mobile'] ?>
                                                                       <!-- <?= $eu_mobile1 ?> <?php if ($eu_mobile1) { ?><a class="duplicate_check" data-value="<?= $eu_mobile1 ?>" href="javascript:void(0)" data-url="eu_mobile1" style="float:right"><?= getSingleresult("select count(id) from orders where is_opportunity=0 and eu_mobile1 ='" . $eu_mobile1 . "' and id!='" . $id . "' ") ?> possible duplicate with this Contact Number</a><?php } ?> -->
                                                                   </td>
                                                               </tr>                                                    
                                                               <tr>
                                                                   <td>Email ID</td>
                                                                   <td>
                                                                   <?= $euData['eu_email'] ?>
                                                                       <!-- <?= $eu_email1 ?> <?php if ($eu_email1) { ?><a class="duplicate_check" data-value="<?= $eu_email1 ?>" href="javascript:void(0)" data-url="eu_email1" style="float:right"><?= getSingleresult("select count(id) from orders where is_opportunity=0 and eu_email1 ='" . $eu_email1 . "' and id!='" . $id . "' ") ?> possible duplicate with this Email ID</a><?php } ?> -->
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
                                                                <td>Student count</td>
                                                                <td>
                                                                    <?= $quantity ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                    <td>Current ICT Being Used by the School</td>
                                                                    <td>
                                                                        <?= $current_being_used_school ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Kits Related Hardware Being Used By the School</td>
                                                                    <td>
                                                                        <?= $kits_related_hardware_school ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Name of the Service Provider</td>
                                                                    <td>
                                                                        <?= $service_provider ?>
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
                                    <?php 
                                            $financial_yearQuery = db_query("SELECT DISTINCT(financial_year_start) AS financial_year,financial_year_end FROM tbl_lead_product_opportunity WHERE lead_id = ".$_REQUEST['id']." and `financial_year_start` IS NOT NULL and `financial_year_start` != '' and status !=0 ORDER BY financial_year ASC");
                                            $checkFin = getSingleresult("SELECT DISTINCT(financial_year_start) AS financial_year,financial_year_end FROM tbl_lead_product_opportunity WHERE lead_id = ".$_REQUEST['id']." and `financial_year_start` IS NOT NULL and `financial_year_start` != '' and status !=0 ORDER BY financial_year ASC");
                                            $countFin = mysqli_num_rows($financial_yearQuery);
                                            
                                            $finC = 0;
                                            ?>
                                    <div class="card mb-0 pt-2 shadow-none">
                                        
                                        <div class="card-header d-flex justify-content-between" id="headingOne6">
                                            <h5 class="my-0 flex-1">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne6" aria-expanded="true" aria-controls="collapseOne6">
                                                    Product Details 
                                                </button>
                                            </h5>
                                        </div>
                                        <?php
                                        if($checkFin){
                                                                                        ?>
                                                                                        <div id="collapseOne6" class="" aria-labelledby="headingOne6" data-parent="#accordionExample2">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="table-responsive">
                                                        <table class="table"><tr><td colspan="8">Products During Fresh To Renewal</td></tr>
                                                            <tbody>
                                                                <tr>
                                                                    <th>S.No</th>
                                                                    <th>Product Code</th>
                                                                    <th>Quantity</th>
                                                                    <th>Original Price</th>
                                                                    <th>Negotiate/Sales Price</th>
                                                                    <th>Total Price</th>
                                                                </tr>
                                                                <?php 
                                                                $parentID = getSingleresult("SELECT parent_id FROM orders where id=" . $_REQUEST['id']);
                                                                $query = db_query("SELECT * FROM tbl_lead_product_opportunity where status=1 and lead_id =" . $parentID);
                                                                $count = mysqli_num_rows($query);
                                                                $i = 1;
                                                                if ($count) {
                                                                    while ($data_n = db_fetch_array($query)) {
                                                                ?>
                                                                        <tr style="text-align:left;">
                                                                            <td><?= $i ?></td>
                                                                            <td><?= getSingleresult("SELECT product_name FROM tbl_product_opportunity where id=" . $data_n['product']) ?></td>
                                                                            <td><?= $data_n['quantity'] ?></td>
                                                                            <td><?= $data_n['original_sales_price'] ?></td>
                                                                            <td><?= $data_n['unit_price'] ?></td>
                                                                            <td><?= $data_n['total_price'] ?></td>
                                                                        </tr>
                                                                    <?php $i++;
                                                                    }
                                                                } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            <?php  
                                        while ($dataF = db_fetch_array($financial_yearQuery)) { 
                                            if($dataF['financial_year'] != ''){
                                            $finC++;
                                            
                                        ?>
                                        <div id="collapseOne6" class="" aria-labelledby="headingOne6" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <div class="table-responsive">
                                                        <table class="table"><tr><td colspan="11">Financial Year(<?= $dataF['financial_year'] ?>-<?= $dataF['financial_year_end'] ?>)</td></tr>
                                                        <tbody>
                                                            <tr><th>S.No</th>
                                                            <th>Product Code</th>
                                                            <th>Quantity</th>
                                                            <th>Original Price</th>
                                                            <th>Negotiate/Sales Price</th>
                                                            <th>Total Price</th>
                                                            <th>Stage</th>
                                                            <th class="text-nowrap">Sub Stage</th>
                                                            <th>Is Upsell</th>                                                           
                                                            <th class="text-nowrap" style="width: 60px">Stage Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    <?php 
                                                    // $query = db_query("SELECT DATE_FORMAT(created_at, '%Y-%m-%d') AS purchase_day,financial_year_start AS financial_year,tbl_lead_product_opportunity.* FROM tbl_lead_product_opportunity WHERE status !=0 and lead_id = ".$_REQUEST['id']." AND financial_year_start = '".$dataF['financial_year']."' ORDER BY purchase_day ASC");
                                                    $query = db_query("
                                                                    SELECT 
                                                                        DATE_FORMAT(tlpo.created_at, '%Y-%m-%d') AS purchase_day,
                                                                        tlpo.financial_year_start AS financial_year,
                                                                        tlpo.*,
                                                                        s.stage_name,
                                                                        ss.name as sub_stage_name
                                                                    FROM tbl_lead_product_opportunity tlpo
                                                                    LEFT JOIN stages s 
                                                                        ON s.id = tlpo.stage
                                                                    LEFT JOIN sub_stage ss
                                                                        ON ss.id = tlpo.sub_stage
                                                                    WHERE tlpo.status != 0
                                                                        AND tlpo.lead_id = " . (int)$_REQUEST['id'] . "
                                                                        AND tlpo.financial_year_start = '" . $dataF['financial_year'] . "'
                                                                    ORDER BY purchase_day ASC
                                                                ");

                                                            $count = mysqli_num_rows($query);
                                                            $i = 1;
                                                            $currentYear = date('Y');
                                                            $currentMonth = date('m');
                                                            if ($currentMonth >= 4) {
                                                                $financial_year_start = $currentYear;
                                                                $financial_year_end = $currentYear + 1;
                                                            } else {
                                                                $financial_year_start = $currentYear - 1;
                                                                $financial_year_end = $currentYear;
                                                            }
                                                            $renew_year = date('Y', strtotime($renew_opportunity_date));
                                                            $renew_month = date('m', strtotime($renew_opportunity_date));
                                                            if ($renew_month >= 4) {
                                                                $renew_financial_year_start = $renew_year;
                                                                $renew_financial_year_end = $renew_year + 1;
                                                            } else {
                                                                $renew_financial_year_start = $renew_year - 1;
                                                                $renew_financial_year_end = $renew_year;
                                                            }
                                                            if ($count) {
                                                                $grandTotal = 0;
                                                                while ($data_n = db_fetch_array($query)) { 
                                                                    $countProduct = getSingleResult("SELECT COUNT(id) from opportunity_attachments where lead_id='".$_REQUEST['id']."' and product_id='".$data_n['product']."' and status=1");
                                                                    $grandTotal+=$data_n['total_price'];
                                                                    ?>
                                                                    <tr style="text-align:left;">
                                                                        <td><?= $i ?></td>
                                                                        <td><?= getSingleresult("SELECT product_name FROM tbl_product_opportunity where id=".$data_n['product']) ?></td>
                                                                        <td><?= $data_n['quantity'] ?></td>
                                                                        <td><?= $data_n['original_sales_price'] ?></td>
                                                                        <td><?= $data_n['unit_price'] ?></td>
                                                                        <td><?= $data_n['total_price'] ?></td>
                                                                        <td><?= $data_n['stage_name'] ?? 'NA' ?></td>
                                                                        <td><?= $data_n['sub_stage_name'] ?? 'NA' ?></td>
                                                                        
                                                                        <td><?= $data_n['upsell'] ? 'Yes' : 'No' ?></td>
                                                                        <td>
                                                                            <div class="d-inline-flex text-center">
                                                                                <?php if($data_n['upsell']){ ?>
                                                                                <button class="btn btn-primary px-2 py-1" onclick="changeStageModal(<?= $data_n['id'] ?>)"><i class="mdi mdi-update"></i></button>

                                                                                <?php } ?>
                                                                            </div>
                                                                        </td>

                                                                        <td>
                                                                            <div class="d-inline-flex text-center">
                                                                           <?php if($finC == $countFin) { ?>
                                                                                <button class="btn btn-primary px-2 py-1 mr-1 add-product-btn" onclick="AddProductModal(<?= $_GET['id'] ?>,<?= $data_n['product'] ?>,<?= $data_n['id'] ?>)"><i style="font-size:16px" class="mdi mdi-plus"></i></button>
                                                                               <?php if($countProduct) { ?>
                                                                                <button class="btn btn-primary px-2 py-1 mr-1" onclick="EditProductModal(<?= $_GET['id'] ?>,<?= $data_n['product'] ?>,<?= $data_n['id'] ?>)"><i style="font-size:16px" class="mdi mdi-pencil"></i></button>
                                                                                <button class="btn btn-primary px-2 py-1" onclick="viewProductModal(<?= $_GET['id'] ?>,<?= $data_n['product'] ?>,<?= $data_n['id'] ?>)"><i style="font-size:16px" class="mdi mdi-eye"></i></button>
                                                                                <?php } ?>
                                                                                <?php } else { ?>
                                                                                    <?php if($countProduct) { ?>
                                                                                    <button class="btn btn-primary px-2 py-1" onclick="viewProductModal(<?= $_GET['id'] ?>,<?= $data_n['product'] ?>,<?= $data_n['id'] ?>)"><i style="font-size:16px" class="mdi mdi-eye"></i></button>
                                                                               <?php }} ?>
                                                                            </div>
                                                                        </td>

                                                                    </tr>
                                                                    <?php $i++;
                                                                }
                                                            } ?>
                                                        <tr>
                                                        <?php 
                                                        $currentYear = date('Y');
                                                        $currentMonth = date('m');
                                                        
                                                        if ($currentMonth >= 4) {
                                                            $financialYear = $currentYear . '-' . ($currentYear + 1);
                                                        } else {
                                                            $financialYear = ($currentYear - 1) . '-' . $currentYear;
                                                        }
                                                        ?>                                                            
                                                                    <td colspan="3">Grand Total</td>
                                                                    <td colspan="<?= (($finC == $countFin) && ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN')) ? 5 : 4 ?>">
                                                                        <?= $grandTotal ?>
                                                                    </td>

                                                                    <?php if($finC == $countFin && $fetch_query['edit_product']==1){?>
                                                                    <td colspan="3">
                                            <?php if ($renew_financial_year_start == $financial_year_start && $renew_financial_year_end == $financial_year_end) { ?> 
                                                        <a class="flex-shrink-0 ml-5" data-toggle="modal" onclick="edit_opportunity(<?= $_GET['id'] ?>)" data-animation="bounce" data-target=".bs-example-modal-center"><button class="btn1 btn-primary mt-1 pull">Edit Opportunity</button></a>
                                                    <?php } else { ?>
                                                        <a class="flex-shrink-0 ml-5" data-toggle="modal" onclick="renew_opportunity(<?= $_GET['id'] ?>)" data-animation="bounce" data-target=".bs-example-modal-center"><button class="btn1 btn-primary mt-1 pull">Renew Opportunity</button></a>
                                                <?php } ?>
                                            </td>
                                                            <?php } ?>
                                                                </tr>
                                                                <?php if($finC == $countFin){ ?>
                                                                    <tr>
                                                                    <td colspan="2">Product Remarks</td>
                                                                    <td colspan="9">
                                                                        <?= $product_remarks ?>
                                                                    </td>
                                                                </tr>
                                                                <?php    } ?>
                                                            </tbody></table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } }}else{?>
                                            <div id="collapseOne6" class="" aria-labelledby="headingOne6" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <tbody>
                                                                <tr>
                                                                    <th>S.No</th>
                                                                    <th>Product Code</th>
                                                                    <th>Quantity</th>
                                                                    <th>Original Price</th>
                                                                    <th>Negotiate/Sales Price</th>
                                                                    <th>Total Price</th>
                                                                </tr>
                                                                <?php 
                                                                $parentID = getSingleresult("SELECT parent_id FROM orders where id=" . $_REQUEST['id']);
                                                                $query = db_query("SELECT * FROM tbl_lead_product_opportunity where status=1 and lead_id =" . $parentID);
                                                                $count = mysqli_num_rows($query);
                                                                $i = 1;
                                                                if ($count) {
                                                                    while ($data_n = db_fetch_array($query)) {
                                                                ?>
                                                                        <tr style="text-align:left;">
                                                                            <td><?= $i ?></td>
                                                                            <td><?= getSingleresult("SELECT product_name FROM tbl_product_opportunity where id=" . $data_n['product']) ?></td>
                                                                            <td><?= $data_n['quantity'] ?></td>
                                                                            <td><?= $data_n['original_sales_price'] ?></td>
                                                                            <td><?= $data_n['unit_price'] ?></td>
                                                                            <td><?= $data_n['total_price'] ?></td>
                                                                        </tr>
                                                                    <?php $i++;
                                                                    }
                                                                } ?>
                                                                <tr>
                                                                    <td colspan="2">Grand Total</td>
                                                                    <td colspan="3"><?= $grand_total_price ?></td>
                                            <?php if($fetch_query['edit_product']==1){?>
                                                                    <td>
                                                                        <a class="flex-shrink-0 mr-5" data-toggle="modal" onclick="renew_opportunity(<?= $_GET['id'] ?>)" data-animation="bounce" data-target=".bs-example-modal-center"><button class="btn1 btn-primary mt-1 pull">Renew Opportunity</button></a>
                                                                    </td>
                                                            <?php } ?>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            <?php }?>
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
                                                Lab INFRASTRUCTURE
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseOne6" class="" aria-labelledby="headingOne6" data-parent="#accordionExample2">

                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <table class="table" id="user">
                                                        <tbody>
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
                                                            <tr>
                                                                <td>Record Type</td>
                                                                <td>
                                                                    <?= $record_type ?>
                                                                </td>
                                                            </tr>
                                                             <tr>
                                                                <td>Quantity</td>
                                                                <td>
                                                                    <?= $quantity ?>
                                                                </td>
                                                            </tr>
                                                             <tr>
                                                                <td>PO Received</td>
                                                                <td>
                                                                    <?= $po_receive ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Agreement Type</td>
                                                                <td>
                                                                    <?= $agreement_type ?>
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
                                                                <form action="#" method="post" id="poForm" name="saveform" enctype="multipart/form-data">
                                                               
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
                                                                    <?=$stage ?>  <?php  if($fetch_query['edit_stage']==1){ ?>  <a href="javascript:void(0)" title="Change Stage" id=but<?=$row_data['id']?> onclick="stage_change('but<?=$row_data['id']?>',<?=$row_data['id']?>)"> <i style="font-size:18px" class="mdi mdi-update"></i></a> 
                                                                        <?php } ?>
                                                                    <?php 
                                                                    $checkOnBoarding = getSingleResult("select onboard_mail_sent from orders where id=".$_GET['id']);
                                                                    if($stage == 'PO/CIF Issued' && $add_comm == 'Advance Payment Received' && ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN') && ($program_initiation_date=='' || $program_initiation_date==null) && $checkOnBoarding==0) { ?>
                                                                    <a href="#" onclick="event.preventDefault(); sendOnboardingMailCLM('<?= $school_name ?>','<?=$row_data['id']?>');"><button class="btn1 btn-primary mt-1">Send Onboarding mail to CLM team.</button></a>
                                                                    <?php } ?>
                                                                    </td>
                                                                </tr>
                                                                    <tr>
                                                                        <td>Close Date</td>
                                                                        <td>
                                                                            <?= $expected_close_date ?>
                                                                            <?php 
                                                                            if($fetch_query['edit_date'] == 1 && $stage != 'PO/CIF Issued' && $stage !='Billing') {  
                                                                                $ids2 = "'but2" . $row_data['id'] . "'";
                                                                                ?>
                                                                            <a href="javascript:void(0)" title="Change Close Date" id="but2<?= $row_data['id'] ?>" onclick="cd_change(<?= $ids2 ?>,'<?= $row_data['id'] ?>')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                            <tr>
                                                                <td>Program initiation date</td>
                                                                <td>
                                                                    <?= $program_initiation_date ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    

                                    <div class="button-items1">
                                        <input type="hidden" name="dr_code" value="<?= $code ?>" />
                                        <?php $query = access_role_permission();
                                        $fetch_query = db_fetch_array($query); ?>
                                        <input type="hidden" value="<?= $created_by ?>" name="lead_by" />
                                        <input type="hidden" value="<?= $quantity ?>" name="quant" />
                                        </form>

                                        <?php 
                                        $queryY = access_role_permission();
                                        $fetch_queryY = db_fetch_array($queryY);
                                        $userType = $_SESSION['user_type'];
                                        if($userType != "RM" && $userType != "RCLR") {
                                            if (($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $created_by == $_SESSION['user_id'] || $allign_to==$_SESSION['user_id']) && $fetch_queryY['edit_lead'] == 1 && $stage != 'PO/CIF Issued' && $stage !='Billing') { ?>
                                                <a href="edit_order.php?eid=<?= $id ?>"><button type="button" class="btn1 btn-primary  mt-2">Edit</button></a>

                                        <?php }
                                            }
                                         ?>
                                        <button type="button" onclick="javascript:history.go(-1);" class="btn1 btn-danger mt-2">Back</button>
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

        if (window.innerWidth <= 768) {
            $('.add_lead').height(wfheight - 200);
        }
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
                // formData.append('academic_year', academic_year);
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
                            console.log("succ");
                            swal({
                                title: "Done!",
                                text: "Stage changed Successfully.",
                                type: "success"
                            }, function() {
                                location.reload();
                            });

                        } else {
                            console.log("err");
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

    function renew_opportunity(lead_id) {
        $.ajax({
            type: 'POST',
            url: 'renew_opportunity.php',
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

    function mainProductChange(e,i) {
        var mainProductId = e;
        // alert(mainProductId);
        if (mainProductId) {
            $.ajax({
                url: 'get_product_price.php', 
                type: 'POST',
                data: { 
                    main_product_id: mainProductId,
                    i:i
                    },
                success: function(data) {
                    // console.log(data); 
                    $('#productCode'+i).html(data);
                }
            });
        }
    }

    $(document).on('click', '.toggle-sales-price', function () {
            var container = $(this).closest('.sales-price-container');
            var currentValue = container.find('.sales-price').val();
            container.html(`<label class="control-label">Negotiate Price<span class="text-danger">*</span></label><input name="sales_price[]" type="number" class="form-control sales-price" placeholder="0.00" value="${currentValue}" />`);
        });

        $(".clonner-page").click(function(){
            var host = '<?= $_SERVER['HTTP_HOST'] ?>';
            var id = '<?= $_REQUEST['id'] ?>';
        
            if(host == "localhost"){
                var baseURL = "http://localhost/DR/ict-dr/";
                location.replace(baseURL+"add_leads.php?eid="+id);
            }else if(host == "13.127.41.55"){
                var baseURL = "http://13.127.41.55/~mblmbxd2h/";
                location.replace(baseURL+"add_leads.php?eid="+id);
            }else if(host == "dr.ict360.com"){
                var baseURL = "dr.ict360.com";
                location.replace("add_leads.php?eid="+id);
            }   
        })

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

    // function edit_opportunity(lead_id) {
    //     $.ajax({
    //         type: 'POST',
    //         url: 'edit_opportunity.php',
    //         data: {
    //             pid: lead_id
    //         },
    //         success: function(response) {
    //             $("#myModal").html();
    //             $("#myModal").html(response);
    //             $('#myModal').modal('show');
    //         }
    //     });
    // }
    function edit_opportunity(lead_id) {
        
    $.ajax({
        type: 'POST',
        url: 'edit_opportunity.php',
        data: {
            pid: lead_id,
            _ajax: 1   // IMPORTANT → tell backend it's ajax
        },
        success: function(response) {
            $("#myModal").html(response);
            $('#myModal').modal('show');
        },
        error: function(xhr) {
             Swal.fire("Error", "Something went wrong.", "error");

            // Check if session expired
            // if (xhr.status === 401) {

            //     let res = {};
            //     try {
            //         res = JSON.parse(xhr.responseText);
            //     } catch (e) {}

            //     Swal.fire({
            //         icon: "error",
            //         title: "Session Expired",
            //         text: res.message || "Your session has expired. Please login again."
            //     }).then(() => {
            //         window.location.href = "index.php"; // logout / redirect
            //     });
            //     // alert(res.message || "Your session has expired. Please login again.");
            //     // window.location.href = "index.php";

            // } else {
            //     Swal.fire("Error", "Something went wrong.", "error");
            // }
        }
    });
}


    function EditProductModal(a,product,tbl_lead_opportunity_id) {
        $.ajax({
            type: 'POST',
            url: 'edit_opportunity_attachments.php',
            data: {
                pid: a,
                product:product,
                tbl_lead_opportunity_id:tbl_lead_opportunity_id
            },
            success: function(response) {
                $("#myModal").html();
                $("#myModal").html(response);
                $('#myModal').modal('show');
            }
        });
    }

    function changeStageModal(product_id) {
        //alert(product_id);
        $.ajax({
            type: 'POST',
            url: 'opportunity_change_stage.php',
            data: {
                product_id:product_id
            },
            success: function(response) {
                $("#myModal").html(response);
                $('#myModal').modal('show');
            }
        });

    }

    function viewProductModal(a,product,tbl_lead_opportunity_id) {
        $.ajax({
            type: 'POST',
            url: 'opportunity_attachments.php',
            data: {
                pid: a,
                product:product,
                tbl_lead_opportunity_id:tbl_lead_opportunity_id
            },
            success: function(response) {
                $("#myModal").html();
                $("#myModal").html(response);
                $('#myModal').modal('show');
            }
        });
    }

    function AddProductModal(a,product,tbl_lead_opportunity_id) {
        $.ajax({
            type: 'POST',
            url: 'add_opportunity_attachments.php',
            data: {
                pid: a,
                product:product,
                tbl_lead_opportunity_id:tbl_lead_opportunity_id
            },
            success: function(response) {
                $("#myModal").html();
                $("#myModal").html(response);
                $('#myModal').modal('show');
            }
        });
    }
    function sendOnboardingMailCLM(school_name,lead_id){
        swal({
            title: "Are you sure?",
            text: "You want to send onboarding mail to CLM team?",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Yes!",
            confirmButtonColor: "#ec6c62"
        }, function() {
            $.ajax({
                    type: 'POST',
                    url: 'send_boarding_mail_clm.php',
                    data: {
                        school_name: school_name,
                        lead_id:lead_id
                    },
                    success: function(response) {
                        return false;
                    }
                }).done(function(data) {
                    swal("Mail sent successfully!");
                    // setTimeout(function() {
                    //     location.reload();
                    // }, 1000)
                }) 
                .error(function(data) {
                    swal("Oops", "We couldn't connect to the server!", "error");
                });
        })
    }


    function viewTicket(school_id,school_name) {
        var page_access = 'true';

        $.ajax({
            type: 'POST',
            url: 'view-ticket.php',
            data:{ 
                school_id: school_id, 
                page_access: page_access,
                school_name:school_name
            },
            success: function(response) {
                $("#myModal").html(response);
                $('#myModal').modal('show');
                $('.preloader').hide();
            }
        });
    }

                function cd_change(ids, id) {
                        //$('.preloader').show();
                        // alert('hii')
                        $.ajax({
                            type: 'POST',
                            url: 'cd_change.php',
                            data: {
                                pid: id,
                                ids: ids
                            },
                            success: function(response) {
                                $("#myModal").html();
                                $("#myModal").html(response);
                                $('#myModal').modal('show');
                                $('.preloader').hide();
                            }
                        });
        }


        function change_cdDate(cd_date, id, ids) {
                if (cd_date != '') {
                    $('#myModal').modal('hide');
                    $.ajax({
                        type: 'post',
                        url: 'change_cdDate.php',
                        data: {
                            cd_date: cd_date,
                            lead_id: id
                        },
                        success: function(res) {
                            var res = $.trim(res);
                            if (res == 'success') {
                                swal({
                                    title: "Done!",
                                    text: "Close Date changed Successfully.",
                                    type: "success"
                                }, function() {
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000)
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
// function loadSubStages(stageName) {
//     // alert("hello stage changed");

//     var $subStage = $('#sub_stage');
//     $subStage.html('<option value="">Loading...</option>');

//     if (stageName === '') {
//         $subStage.html('<option value="">Select Sub Stage</option>');
//         return;
//     }

//     $.ajax({
//         url: 'ajax_get_sub_stages.php',
//         type: 'POST',
//         data: { stage: stageName },
//         dataType: 'json',
//         success: function (response) {
//             var options = '<option value="">Select Sub Stage</option>';

//             $.each(response, function (index, item) {
//                 options += '<option value="' + item.id + '">' + item.name + '</option>';
//             });

//             $subStage.html(options);
//         },
//         error: function () {
//             $subStage.html('<option value="">Error loading sub stages</option>');
//         }
//     });
// }

function loadSubStages(selectEl) {

    var $subStage = $('#sub_stage');
    $subStage.html('<option value="">Loading...</option>');

    // Selected stage ID
    var stageId = selectEl.value;

    // Selected stage NAME (label)
    var stageName = selectEl.options[selectEl.selectedIndex]
                            .getAttribute('data-name');

                            // alert(stageName);
                            // return ;

    if (stageId === '') {
        $subStage.html('<option value="">Select Sub Stage</option>');
        return;
    }

    $.ajax({
        url: 'ajax_get_sub_stages.php',
        type: 'POST',
        data: {
            stage_id: stageId,     // ID
            stage_name: stageName  // LABEL
        },
        dataType: 'json',
        success: function (response) {
            var $subStage = $('#sub_stage');
            var $container = $('#sub_stage_container');
            if (response.length > 0) {
                $container.removeClass('d-none');
                var options = '<option value="">Select Sub Stage</option>';

                $.each(response, function (index, item) {
                    options += '<option value="' + item.id + '">' 
                             + item.name + 
                             '</option>';
                });

                $subStage.html(options);
            } else {
                $container.addClass('d-none');
                $subStage.html('<option value="">Select Sub Stage</option>');
            }
        },
        error: function () {
            $subStage.html('<option value="">Error loading sub stages</option>');
        }
    });
}

</script>

<script>
function submitStageForm(btn) {

    const form = document.getElementById('stageForm');

    if (!form) {
        toastr.error('Form not found');
        return;
    }

    const stage    = form.querySelector('[name="stage"]').value;
    const product_id    = form.querySelector('[name="product_id"]').value;
    const subStage = form.querySelector('[name="sub_stage"]').value;

    // ---------- VALIDATION ----------
    if (!stage) {
        toastr.error('Please select a Stage');
        return;
    }

    const subStageSelect = form.querySelector('[name="sub_stage"]');
    const hasSubStages = subStageSelect && subStageSelect.options.length > 1;

    if (hasSubStages && !subStage) {
        toastr.error('Please select a Sub Stage');
        return;
    }

    // ---------- BUTTON LOADER ----------
    btn.disabled = true;
    btn.querySelector('.btn-text').classList.add('d-none');
    btn.querySelector('.btn-loader').classList.remove('d-none');

    // ---------- AJAX ----------
    $.ajax({
        url: 'ajax_update_stage_substage.php',
        type: 'POST',
        data: $(form).serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                toastr.success('Stage updated successfully');
                setTimeout(function () {
                        location.reload();
                    }, 2000);
            } else {
                toastr.error(response.message || 'Something went wrong');
            }
        },
        error: function () {
            toastr.error('Server error. Please try again.');
        },
        complete: function () {
            // ---------- RESET BUTTON ----------
            btn.disabled = false;
            btn.querySelector('.btn-text').classList.remove('d-none');
            btn.querySelector('.btn-loader').classList.add('d-none');
        }
    });
}
</script>