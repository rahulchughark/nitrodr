<?php
include('includes/header.php');
include_once('helpers/DataController.php');
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
                
                // ownership_changed = 1
                $sqlc=db_query("update orders set created_by='".$_POST['new_user']."',r_user='".$name_new."',r_email='".$email_new."' where id=".$_REQUEST['id']);
        
                redir("view_order.php?id=" . $_POST['id'], true);
            // }else{
            //     echo "<script>alert('Daily Quota Reached.');</script>";
            // }
        }
        
        if($sql->num_rows == 0)
        {
            redir("manage_orders.php?m=nodata", true);
        }

        function struuid($entropy,$campaign,$lead_id)
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
        
        if ($_POST['caller']) {
            if ($row_data['caller'] != $_POST['caller']) {
                $modify_name = getSingleresult("select name from callers where id='" . $_POST['caller'] . "' ");
                $caller_prev = getSingleresult("select name from callers where id='" . $row_data['caller'] . "'");
        
                $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_REQUEST['id']."','Caller','". $caller_prev."','". $modify_name."',now(),'".$_SESSION['user_id']."')");
                $sqlc=db_query("update orders set  caller='".$_POST['caller']."'  where id=".$_REQUEST['id']);
        
            }
        }
                
        if (!empty($_FILES["po_re_attachment"]["name"][0])) {
            $target_dir = "uploads/";
            $f = 1;
            foreach ($_FILES['po_re_attachment']['name'] as $key => $value) {
            $file_tmp_path = $_FILES['po_re_attachment']['tmp_name'][$key];
            $mime_type     = mime_content_type($file_tmp_path); // detects real MIME type
                if (in_array($mime_type, $allowed_mime_types)) {
                    $file_extension = pathinfo(basename($_FILES['po_re_attachment']['name'][$key]), PATHINFO_EXTENSION);
                    $target_file = $target_dir .$f.'_'. time().".".$file_extension;        

                    if (move_uploaded_file($_FILES['po_re_attachment']['tmp_name'][$key], $target_file)) {
                        $uploaded_files[] = $target_file;
                    } else {
                        echo 'error';
                        exit;
                    }
                    $f++;
                } else {
                    echo "<script>alert('Sorry, wrong file type!')</script>";
                }
            }
            $file_names = implode(',', $uploaded_files);
            
            $reattach=db_query("update orders set po_attachment='".$file_names."',po_attachment_updated_by=".$_SESSION['user_id']."  where id=".$_REQUEST['id']);

        }
        if ($_POST['product_code']) {
            // print_r($_POST);die;
            if (!empty($_FILES["user_attachment"]["name"])) {
                $file_tmp_path = $_FILES['user_attachment']['tmp_name'];
                $file_ext = strtolower(pathinfo($_FILES['user_attachment']['name'], PATHINFO_EXTENSION));
                if (in_array($file_ext, $allowed_extensions)) {
                $target_dir = "uploads/";
                $target_file = $target_dir . time() . basename($_FILES["user_attachment"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
                if ($_FILES["user_attachment"]["size"] > 4000000) {
                    echo "<script>alert('Sorry, your file is too large!')</script>";
                    redir("manage_orders.php", true);
                } else {
                    move_uploaded_file($_FILES["user_attachment"]["tmp_name"], $target_file);
                }
                } else {
                    echo "<script>alert('Sorry, wrong file type!')</script>";
                }
            }else{
                $target_file = '';
            }

            $number = count($_POST["product_code"]);
            for ($i = 0; $i < $number; $i++) {
                $res =  db_query("INSERT INTO tbl_lead_product_opportunity(`lead_id`, `product`,`unit_price`,`quantity`,`total_price`) VALUES ('" . $_GET['id'] . "','" . $_POST['product_code'][$i] . "','" . $_POST['sales_price'][$i] . "','" . $_POST['quantity'][$i] . "','" . $_POST['total_price'][$i] . "')");
            }

            // $created_att = date("Y-m-d H:i:s");
            // $form_id = getSingleresult("select form_id from tbl_product_pivot where id=".$_POST['product_type']);
            // $lead_id = $_GET['id'];

            $inss = db_query("UPDATE orders SET is_opportunity = 1, stage = '".$_POST['opportunity_stage']."', expected_close_date = '".$_POST['close_date']."', user_attachement = '".$target_file."', grand_total_price = '".$_POST['grand_total']."' , opportunity_by = '".$_SESSION['user_id']."' WHERE id =".$_POST['pid']);
            if ($inss) {
                redir("manage_opportunity.php?update=success", true);
            }
        }
        
        if ($_POST['status']) {      
            if ($_POST['status'] == 'Approved') {
                if ($_POST['dr_code']) {
                    $code = $_POST['dr_code'];
                } else {
                    $code = struuid(true,$row_data['tag'],$row_data['id']);
                }
                $_POST['reason'] = '';
    
            } else {
                $code = '';
                if ($_POST['status'] == 'Undervalidation') {
                    $_POST['reason'] = $_POST['reason_ud'];
                }
            }
    
            if ($row_data['status'] != $_POST['status']) {
                $modify_name = $_POST['status'];
                $prevN = $row_data['status'] ? $row_data['status'] : 'N/A';
                $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_REQUEST['id']."','Status','". $row_data['status']."','". $modify_name."',now(),'".$_SESSION['user_id']."')");
                
                if($row_data['reason'] != $_POST['reason']){
                    $modify_name = $_POST['reason'];
                    $prevN = $row_data['reason'] ? $row_data['reason'] : 'N/A';
                    
                $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_REQUEST['id']."','Reason','". $prevN."','". $modify_name."',now(),'".$_SESSION['user_id']."')");
            }
        }
    
            $addCc[] = "virendra.kumar@arkinfo.in";
    
            $manager_email=getSingleResult("select email from users where user_type='MNGR' and status='Active' and team_id='" . $row_data['team_id'] . "'");
            if($manager_email){
                $addTo[] = $manager_email;
            }
            
            $useremail =getSingleresult("select email from users where id='".$row_data['created_by']."' ");
            $addTo[] = $useremail;
            // print_r($addTo);die;
            if ($_POST['status'] == 'Cancelled') {
                $stat = '<span style="color:red">Unqualified</span>';
            } else if ($_POST['status'] == 'Approved') {
                $stat = '<span style="color:green">Qualified</span>';
            } else if ($_POST['status'] == 'Undervalidation') {
                $stat = '<span style="color:orange">Under Validation</span>';
            } else {
                $stat = '<span class="text-blue">On-Hold</span>';
            }
    
            $setSubject = "Lead status has been changed on DR Portal [" . $row_data['school_name'] . "]";
    
            $productN = getSingleresult("select tp.product_name from tbl_lead_product as tlp left join tbl_product as tp on tp.id=tlp.product_id where tlp.lead_id=" . $_REQUEST['id']);
            $subProductN = getSingleresult("select tp.product_type from tbl_lead_product as tlp left join tbl_product_pivot as tp on tp.id=tlp.product_type_id where tlp.lead_id=" . $_REQUEST['id']);
            $body = "Hi,<br><br> Below account status has been changed to " . $stat . " on DR Portal:-<br><br>
                <ul>
                <li><b>Product Name</b> : " . $productN . " </li>
                <li><b>Product Type</b> : " . $subProductN . " </li>
                <li><b>License Type</b> : " . $row_data['agreement_type'] . " </li>
                <li><b>VAR Organization Name</b> : " . $row_data['r_name'] . " </li>
                <li><b>Submitted by </b> : " . $row_data['r_user'] . " </li>
                <li><b>Account Name</b> : " . $row_data['school_name'] . " </li>
                <li><b>Quantity</b> : " . $row_data['quantity'] . " </li>
                <li><b>Address</b> : " . htmlspecialchars($row_data['address'], ENT_QUOTES);
    
            if ($_POST['status'] == 'Undervalidation' || $_POST['status'] == 'Cancelled')
            {
                $body .=    "<li><b>Reason</b> : " . $_POST['reason'] . " </li>";
            }
                $body .= "</ul></br>Thanks,<br>
                    Corel DR Portal";
    
            if (!$_POST['dr_code'])
               { 
                $addBcc[] = '';
                  $b = sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
               }
            //    die;
        }

        if (isset($_POST['submit']) && $_SESSION['user_type'] != 'REVIEWER') {        
            /*
            mail to caller in case status is cancelled
            */
            $status = $_POST['status'] ? $_POST['status'] : $row_data['status'];
            $reason = $_POST['reason'] ? $_POST['reason'] : $row_data['reason'];        
            if($row_data['admin_attachment'] == ''){
                $fileAtt = $row_data['user_attachement'];
            }else{
                $fileAtt = $row_data['admin_attachment'];
            }
            $attachement = !empty($_FILES["admin_attachment"]["name"]) ? $target_file : $fileAtt;
            $expected_close_date = $_POST['expected_close_date'] ? $_POST['expected_close_date'] : $row_data['expected_close_date'];
        
            $sql =  db_query("update orders set code='" . $code . "', status='" . $status . "', reason='" . $reason . "',admin_attachment='" . $attachement . "',expected_close_date='" . $expected_close_date . "' where id=" . $_REQUEST['id']);
            //print_r($sql);die;
        
            if ($sql) {
                if ($row_data['status'] != $_POST['status']) {
                    echo "<script type=\"text/javascript\">history.go(-2);</script>";
                }else{
                    redir("manage_orders.php?update=success", true);
                }
            }
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
            $addCc[] = "pooja.chauhan@ict360.com";

            $manager_email=db_query("select email from users where user_type='MNGR' and team_id=" . $data['team_id']);

            while($me=db_fetch_array($manager_email))
            {
                $addTo[] = $me['email'];
            }
     
            // $addTo[] = ("pradeep.chahal@arkinfo.in");
            // $addCc[] = ("virendra.kumar@arkinfo.in"); 

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
            // sendMailGun($addTo, $addCc, $addBcc, $setSubject, $body);
            // sendMail($addTo, $addCc, $addBcc, $setSubject, $body ,$attachment);
            echo '<script type="text/javascript">window.location.href = "view_order.php?id='.$_GET['id'].'";</script>';

        }
        
        if ($_POST['activity_edit']) {
           
            $_POST['leadID'] = $_GET['id'];
            $_POST['last_activity_log_id'] = $_POST['pid']; # pid here as a activity log ID



            // echo "<br>";
            // echo "<br>";
            // echo "<br>";
            // echo "<br>";
            // echo "<br>";
            // echo "<br>";
            // echo "<br>";
            // echo "<br>";
            // echo "<br>";
            // echo "<br>";
            // print_r($_POST);
            // exit;
           

            $res = db_query("update activity_log set description='" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "',call_subject='" . $_POST['call_subject'] . "',action_plan='" . $_POST['action_plan'] . "' where id=" . $_POST['pid']);

            $dataObj->updateReminderLog($_POST,$_SESSION['user_id']);
        }

        $checkRequired = getSingleResult("SELECT CASE WHEN lead_status IS NULL OR lead_status = '' THEN 'False' WHEN source IS NULL OR source = '' THEN 'False' WHEN billing_reseller IS NULL OR billing_reseller = '' THEN 'False' WHEN credit_reseller IS NULL OR credit_reseller = '' THEN 'False' WHEN school_name IS NULL OR school_name = '' THEN 'False' WHEN address IS NULL OR address = '' THEN 'False' WHEN state IS NULL OR state = '' THEN 'False' WHEN city IS NULL OR city = '' THEN 'False' WHEN region IS NULL OR region = '' THEN 'False' WHEN pincode IS NULL OR pincode = '' THEN 'False' WHEN contact IS NULL OR contact = '' THEN 'False' WHEN website IS NULL OR website = '' THEN 'False' WHEN school_email IS NULL OR school_email = '' THEN 'False' WHEN annual_fees IS NULL OR annual_fees = '' THEN 'False' WHEN eu_name IS NULL OR eu_name = '' THEN 'False' WHEN eu_mobile IS NULL OR eu_mobile = '' THEN 'False' WHEN eu_email IS NULL OR eu_email = '' THEN 'False' ELSE 'True' END AS status FROM orders WHERE id=".$_GET['id']);        
?>

<!-- Page wrapper  -->
<!-- ============================================================== -->
 <style>
    .add_lead {
        padding: 15px 0;
    }

    @media (min-width: 800px) {
        .multiselect-container {
            width: 700px;
            max-height: 300px;
        }
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
                                                    <div class="card-body font-size-13">
    <?php 
        $created_by = $data_lml['created_by'];
        $created_by_clm = $data_lml['created_by_clm'];
        if ($created_by == 0 && $created_by_clm != 0) {
            $query = "SELECT name FROM clm_users WHERE id = $created_by_clm";
            $user_name = getSingleresult($query);
        } else {
            $query = "SELECT name FROM users WHERE id = $created_by";
            $user_name = getSingleresult($query);
        }
    ?>
    <?= $user_name ?> has changed 
    <strong><?= $data_lml['type'] ?></strong> 
    from <strong><?= ($data_lml['previous_name'] ? $data_lml['previous_name'] : 'N/A') ?></strong> 
    to <strong><?= $data_lml['modify_name'] ?></strong> 
    on <?= date('F j, Y, g:i a', strtotime($data_lml['created_date'])) ?>.
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
                                            <a data-toggle="modal" onclick="add_activity(<?= $_GET['id'] ?>,'<?= $school_name ?>')" data-animation="bounce" data-target=".bs-example-modal-center"><button class="btn1 btn-primary mt-1">Log a Call</button></a>
                                        </div>

                                        <div id="collapseOne8" class="" aria-labelledby="headingOne8" data-parent="#accordionExample2">


                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <?php
                                                        $query = access_role_permission();
                                                        $fetch_query = db_fetch_array($query);

                                                        $new = db_query("select id,description,created_date,added_by,call_subject,action_plan,location,latitude,longitude from activity_log where is_intern=0 and (activity_type='Lead' or activity_type='DVR') and pid='" . intval($_GET['id']) . "' UNION SELECT id,description,created_date,added_by,id,id,id,id,id from caller_comments where pid='" . intval($_GET['id']) . "' union select id,comment as description,created_date,added_by,id,old_stage,old_caller,old_caller,old_caller from review_log where lead_id='" . intval($_GET['id']) . "' order by created_date desc");

                                                        $goal = db_query("select * from activity_log where pid='" . intval($_GET['id']) . "' order by created_date desc");

                                                        $count = mysqli_num_rows($new);
                                                        $i = $count;
                                                        if ($count) {
                                                            echo  ' <table class="table"><thead class="thead-default"><tr><th>S.No</th><th>Subject</th><th style="min-width: 200px">Description</th><th>Added By</th><th>Location</th><th>Date</th><th>Data Reference</th>';

                                                            if ($_SESSION['user_type'] != "RM" && $_SESSION['user_type'] != "RCLR" && $_SESSION['user_type'] != "CLR" && $fetch_query['edit_log'] == 1) {
                                                                echo  '<th>Action</th>';
                                                            }
                                                            echo '</tr></thead> <tbody>';

                                                            while ($data_n = db_fetch_array($new)) {  ?>
                                                            
                                                                
                                                                    <tr>
                                                                        <td><?= $i ?></td>
                                                                        <td><?= ($data_n['call_subject'] ? $data_n['call_subject'] : 'N/A') ?></td>
                                                                        <td><?= $data_n['description'] ?></td>
                                                                        <td><?= (is_numeric($data_n['added_by']) ? getSingleresult("select concat(name,'-',CASE  WHEN user_type='USR' THEN 'Partner User'    WHEN (user_type='ADMIN' ||user_type='SUPERADMIN'||user_type='OPERATIONS') and sales_manager=0 THEN 'ADMIN' WHEN user_type='SALES MNGR' and sales_manager=1 THEN 'Sales Manager'   WHEN user_type='MNGR' THEN 'Partner Manager'  ELSE 'Caller' END) as nme from users where id='" . $data_n['added_by'] . "'") : '<span style="color:red">(Lead Review!)</span> ' . $data_n['added_by']) ?></td>
                                                                        <td><?php if($data_n['latitude'] && $data_n['longitude']) {?><a target='_blank' href='location.php?latitude=<?= $data_n['latitude'] ?>&longitude=<?= $data_n['longitude'] ?>'><img src="images/locationicon.png" alt="Location" style="width: 20px;height: 20px;"></a><?=$data_n['location']?><?php  } ?> </td>
                                                                        <td class="text-nowrap"><?= date('d-m-Y H:i:s', strtotime($data_n['created_date'])) ?></td>
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
                                                                        if ($_SESSION['user_type'] != "RM" && $_SESSION['user_type'] != "RCLR" && $_SESSION['user_type'] != "CLR" && $fetch_query['edit_log'] == 1) { ?>
                                                                            <td><a href="javascript:void(0)" title="Edit" id=but<?= $data['id'] ?> onclick="edit_activity('<?= $data_n['id'] ?>','<?= $school_name ?>')"> <i style="font-size:18px" class="mdi mdi-pencil"></i></a></td>

                                                                        <?php } ?>
                                                                    </tr>
                                                        <?php $i--;
                                                            }
                                                            echo "</tbody></table>";
                                                        } ?>
                                                    </div>
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
            <div class="table-responsive">
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
                                <?= $eu_email ?> <?php if ($eu_email) { ?><a class="duplicate_check" data-value="<?= $eu_email ?>" href="javascript:void(0)" data-url="eu_email" style="float:right"><?= getSingleresult("select count(id) from orders where is_opportunity=0 and eu_email ='" . $eu_email . "' and id!='" . $id . "' ") ?> possible duplicate with this Email</a><?php } ?> 
                            </td>
                        </tr>
                        <tr>
                            <td>Contact Number </td>
                            <td>

                                <a href="#" class="text-default whatsapp-btn-icon"
                               data-leadid="<?= $_REQUEST['id'] ?>" 
                               data-phone="<?= $eu_mobile ?>" 
                               data-userid="<?= $_SESSION['user_id'] ?>" 
                               onclick="openWhatsappModal(this)">
                               <?= $eu_mobile ?>
                               <?php if ($eu_mobile) { ?>
                                   <img src="images/whatsapp.png" class="ml-2" alt="" height="20">
                               <?php } ?>
                            

                            <span class="msg-count not-count-<?= $eu_mobile ?>">
                                <?php echo $dataObj->getWhatsappNotificationCount($eu_mobile); ?>
                            </span></a>
                            <a class="duplicate_check" data-value="<?= $eu_mobile ?>" href="javascript:void(0)" data-url="eu_mobile" style="float:right"><?= getSingleresult("select count(id) from orders where is_opportunity=0 and eu_mobile ='" . $eu_mobile . "' and id!='" . $id . "' ") ?> <?php if ($eu_mobile) { ?> possible duplicate with this Contact Number</a><?php } ?>
                            </td>
                        </tr>                                                    
                    </tbody>
                </table>
            </div>
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
                                                    <div class="table-responsive">
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
                                                                    <td>
                                                                        <?= $r_user?> &nbsp;
                                                                        <?php $query = access_role_permission();
                                                                        $fetch_query = db_fetch_array($query);
                                                                        if ($fetch_query['edit_ownership'] == 1) { ?>
                                                                            <button class="btn1 btn-primary" onclick="change_user('<?= $_GET['id'] ?>','<?= $_SESSION['team_id'] ?>')">Change</button>
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
                                                    <div class="table-responsive">
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
                                                                        <?= $contact ?><?php if ($contact) { ?><a class="duplicate_check" data-value="<?= $contact ?>" href="javascript:void(0)" data-url="contact" style="float:right"><?= getSingleresult("select count(id) from orders where is_opportunity=0 and contact ='" . $contact . "' and id!='" . $id . "' ") ?> possible duplicate with this Contact</a><?php } ?>
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
                                                                        <?= $school_email ?><?php if ($school_email) { ?><a class="duplicate_check" data-value="<?= $school_email ?>" href="javascript:void(0)" data-url="school_email" style="float:right"><?= getSingleresult("select count(id) from orders where is_opportunity=0 and school_email ='" . $school_email . "' and id!='" . $id . "' ") ?> possible duplicate with this School Email</a><?php } ?>
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
                                                    <div class="table-responsive">
                                                        <table class="table" id="user">
                                                            <tbody>
                                                               
                                                                <?php
                                                                $counter = 1; // Initialize the counter
                                                                
                                                                while($euData=db_fetch_array($sql_order_important)) {
                                                                    
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
                                                                        <a   href="#" class="text-default whatsapp-btn-icon"
                                                                        data-leadid="<?= $_REQUEST['id'] ?>" 
                                                                        data-phone="<?=$euData['eu_mobile'] ?>" 
                                                                        data-userid="<?= $_SESSION['user_id'] ?>"
                                                                        onclick="openWhatsappModal(this)"
                                                                        >
                                                                        <?= $euData['eu_mobile'] ?>
                                                                        <img src="images/whatsapp.png" class="ml-2 cursor-pointer" alt="" height="20" >


                                                                        <span class="msg-count not-count-<?= $euData['eu_mobile'] ?>"><?php echo $dataObj->getWhatsappNotificationCount($euData['eu_mobile']); ?></span>
                                                                        <!-- <?= $eu_mobile1 ?> <?php if ($eu_mobile1) { ?> <a class="duplicate_check" data-value="<?= $eu_mobile1 ?>" href="javascript:void(0)" data-url="eu_mobile1" style="float:right"><?= getSingleresult("select count(id) from orders where is_opportunity=0 and eu_mobile1 ='" . $eu_mobile1 . "' and id!='" . $id . "' ") ?> possible duplicate with this Contact Number</a><?php } ?> -->
                                                                        </a>
                                                                    </td>
                                                                </tr>                                                    
                                                                <tr>
                                                                    <td>Email ID</td>
                                                                    <td>
                                                                    <?= $euData['eu_email'] ?>
                                                                        <!-- <?= $eu_email1 ?> <?php if ($eu_email1) { ?><a class="duplicate_check" data-value="<?= $eu_email1 ?>" href="javascript:void(0)" data-url="eu_email1" style="float:right"><?= getSingleresult("select count(id) from orders where is_opportunity=0 and eu_email1 ='" . $eu_email1 . "' and id!='" . $id . "' ") ?> possible duplicate with this Email ID</a><?php } ?> -->
                                                                    </td>
                                                                </tr>     

                                                                                                               
                                                                       <?php
                                                                    
                                                                    include(__DIR__ . '/ajax/whatsapp_box_loop.php');
                                                                    $counter++; // Increment the counter
                                                                    } ?>                                     
                                                              
                                                            </tbody>
                                                        </table>
                                                    </div>
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
                                                    <div class="table-responsive">
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
                                                    <div class="table-responsive">
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
                                                    <div class="table-responsive">
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
                                                    <div class="table-responsive">
                                                        <table class="table" id="user">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="35%">Standalone PC</td>
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
                                                    <div class="table-responsive">
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
                                                                    <td width="35%">Created on</td>
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
                                                                    <tr>
                                                                    <td>Closing Status</td>
                                                                    <td>
                                                                        <?php if ($status == 'Approved') {
                                                                            echo '<span style="color:green">Qualified</span> ';
                                                                        } else if ($status == 'Cancelled') {
                                                                            echo '<span class="text-danger">Unqualified</span>';
                                                                        } else if ($status == 'Pending') {
                                                                            echo 'Pending';
                                                                        } else if ($status == 'Undervalidation') {
                                                                            echo '<span class="text-warning">Under Validation</span>';
                                                                        } else {
                                                                            echo $status;
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                    <form action="#" method="post" id="saveform" name="saveform" enctype="multipart/form-data">
                                                                
                                                                <tr>
                                                                    
                                                                <?php if (($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN') && $team_id != '127' && $team_id != '116') {
                                                                    $query = access_role_permission();
                                                                    $fetch_query = db_fetch_array($query);
                                                                    if ($fetch_query['edit_status'] == 1) { ?>
                                                                        <td>Status<span class="text-danger">*</span></td>

                                                                        <td>
                                                                            <select <?= (($_SESSION['sales_manager'] == 1) ? 'disabled' : '') ?> onchange="status_update(this.value)" class="form-control" required name="status">
                                                                                <option value="">---Select---</option>
                                                                                <option <?= (($status == 'Undervalidation') ? 'Selected' : '') ?> value="Undervalidation">Re-Submission Required</option>
                                                                                <option <?= (($status == 'Approved') ? 'Selected' : '') ?> value="Approved">Qualified</option>
                                                                                <option <?= (($status == 'Cancelled') ? 'Selected' : '') ?> value="Cancelled">Unqualified</option>
                                                                                <option <?= (($status == 'On-Hold') ? 'Selected' : '') ?> value="On-Hold">On-Hold</option>
                                                                            </select>
                                                                        </td>
                                                                    <?php } ?>
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
                                                                            <option <?= (($reason == 'Incomplete Details') ? 'Selected' : '') ?> value="Incomplete Details">Incomplete Details</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                <?php } ?>
                                                                <tr id="caller" <?php if ($status != 'Approved') { ?> style="display:none" <?php } ?>>
                                                                <?php if (($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN') && $team_id != '127' && $team_id != '116'){ ?>
                                                                <td>Caller</td>
                                                                <td>
                                                                    <?php if (is_numeric($caller) || $caller == '') {
                                                                        $res = db_query("select callers.* from callers left join users on callers.user_id=users.id where users.status='Active' and users.user_type in ('CLR','TEAM LEADER') order by callers.name ASC");
                                                                    ?>
                                                                        <select name="caller" id="caller" class="form-control" data-validation-required-message="This field is required">
                                                                            <option value="">---Select---</option>
                                                                            <?php while ($row = db_fetch_array($res)) { ?>
                                                                                <option <?= (($caller == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] . ' (' . $row['caller_id'] . ')' ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    <?php
                                                                    } ?>
                                                                </td>
                                                                <?php } ?>
                                                                </tr>
                                                                    <!-- <tr>
                                                                        <td>Attachment</td>
                                                                        <td>
                                                                            <input type="file" class="form-control" name="admin_attachment">
                                                                        </td>
                                                                    </tr> -->

                                                                    <tr>
                                                                        <td>Stage</td>
                                                                        <td>
                                                                        <?=$stage ?> <?php if($fetch_query['edit_stage'] == 1){ ?> <a href="javascript:void(0)" title="Change Stage" id=but<?=$row_data['id']?> onclick="stage_change('but<?=$row_data['id']?>',<?=$row_data['id']?>)"> <i style="font-size:18px" class="mdi mdi-update"></i></a> <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Record Type</td>
                                                                        <td>
                                                                            <?= $record_type ?>
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
                                    </div>

                                    

                                    <div class="button-items1 text-center">
                                        <input type="hidden" name="dr_code" value="<?= $code ?>" />
                                        <?php $query = access_role_permission();
                                        $fetch_query = db_fetch_array($query); ?>

                                        <?php if (($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN')  && $team_id != '127' && $team_id != '116') { 
                                        ?>
                                        <button type="submit" name="submit" class="btn1 btn-primary  mt-2">Save</button>
                                        <?php } ?>
                                        <input type="hidden" value="<?= $row_data['created_by'] ?>" name="lead_by" />
                                        <input type="hidden" value="<?= $quantity ?>" name="quant" />
                                        </form>

                                        <?php 
                                        $editStatus = 0;
                                        $url = 'edit_order.php';
                                        $queryY = access_role_permission();
                                        $fetch_queryY = db_fetch_array($queryY);

                                        $userType = $_SESSION['user_type'];
                                        if($userType != "RCLR") {
                                            if (($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $row_data['created_by'] == $_SESSION['user_id'] || $allign_to==$_SESSION['user_id']) && $fetch_queryY['edit_lead'] == 1 && $stage != 'PO/CIF Issued' && $stage !='Billing') { ?>
                                                <a href="<?=$url?>?eid=<?= $id ?>"><button type="button" class="btn1 btn-primary  mt-2">Edit</button></a>

                                        <?php }
                                            }
                                         ?>
                                        <button type="button" onclick="javascript:history.go(-1);" class="btn1 btn-danger mt-2">Back</button>
                                        <?php if($is_opportunity == 0 && $_SESSION['role'] != 'DA'){ ?>
                                        <a data-toggle="modal" onclick="add_opportunity(<?= $_GET['id'] ?>)" data-animation="bounce" data-target=".bs-example-modal-center"><button class="btn1 btn-primary mt-2">Save as Opportunity</button></a>
                                        <?php }else if ($_SESSION['role'] != 'DA' ){ ?>
                                            <a><button class="btn1 btn-primary mt-2" disabled>Opportunity Saved</button></a>
                                            <?php } ?>
                                        
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
<div id="myModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">


</div>


<?php include('includes/footer.php') ?>


<script>
     
</script>

<script>
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

    function add_opportunity(lead_id) {
        check = '<?= $checkRequired ?>';
        if(check == 'False'){
            swal("Please edit lead and fill all mandatory fields to update!");        
        }else{
            $.ajax({
                type: 'POST',
                url: 'add_opporunity.php',
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

    function view_duplicate(id) {
        var status = $('select[name="status"]').val();
        var id = '<?= $id ?>';
        var company_name = '<?= $school_name ?>';
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
    });

    $(document).ready(function () {
                var wfheight = $(window).height();

                if (window.innerWidth <= 767) {
                    // Mobile devices (width <= 767px)
                    $('.add_lead').height(wfheight - 200); // Adjust as needed for mobile
                } else {
                    // Desktop or larger tablets
                    $('.add_lead').height(wfheight - 240);
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
            $("#sfdc_check").show();
            $("#caller").show();
            $("#caller_dd").prop('required', true);
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

</script>