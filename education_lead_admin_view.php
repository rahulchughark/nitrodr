<?php include('includes/header.php'); 
//include('includes/helpers.php');
// echo $_POST['dr_code']; die;
if ($_SESSION['user_type'] != 'EM' && $_SESSION['user_type'] != 'REVIEWER') admin_page(); ?>
<?php
$_REQUEST['id'] = intval($_REQUEST['id']);



$sql = db_query("select o.*,p.product_name,tpp.product_type,tpp.id as type_id from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id  where o.id='" . $_REQUEST['id'] . "'");
$row_data = db_fetch_array($sql);

if ($_POST['caller'] && $_SESSION['user_type'] == 'REVIEWER') {
    if ($row_data['caller'] != $_POST['caller']) {
        $modify_name = getSingleresult("select name from callers where id='" . $_POST['caller'] . "' ");
        $caller_prev = getSingleresult("select name from callers where id='" . $row_data['caller'] . "'");
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Caller','" . $caller_prev . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
        $sql = db_query("update orders set caller='" . $_POST['caller'] . "' where id=" . $_REQUEST['id']);
        $userid = getSingleresult("select user_id from callers where id='" . $_POST['caller'] . "'");
        if ($userid) {
            $caller_email = getSingleresult("select email from users where id='" . $userid . "'");
            $caller_name = getSingleresult("select name from users where id='" . $userid . "'");

            $addTo[] = ($caller_email);
            $addCc[] = ("bhagyashree@corelindia.co.in");
            $addCc[] = ("kailash.bhurke@arkinfo.in");
            $addCc[] = ("prashant.dongrikar@arkinfo.in");
            $addBcc[] = ("coreldrsupport@arkinfo.in");
            $setSubject = "[LC Calling] Lead changed to you on DR Portal";
            $body    = "Hi,<br><br> Below account has been changed for your LC working:-<br><br>
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
            if (!$_POST['dr_code'])
            sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
        }

        echo "<script type=\"text/javascript\">
          window.location = \"education_leads_admin.php\"
        </script>";
    }
}

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
    redir("education_lead_admin_view.php?id=" . $_POST['id'], true);
}

if ($_POST['status']) {

    if (!empty($_FILES["admin_attachment"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . time() . basename($_FILES["admin_attachment"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
 
        if ($_FILES["admin_attachment"]["size"] > 4000000) {
            echo "<script>alert('Sorry, your file is too large!')</script>";
            redir("education_leads_admin.php", true);
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
}
if ($_POST['status'] == 'Approved') {
    $select_query = db_query("select * from orders where id=" . $_REQUEST['id'] . "");
    foreach ($select_query as $value) {
        $select_arr = $value['iss'];
    }
    if ($select_arr == 1) {
        $update_query = db_query("update orders set is_iss_lead = 0 where id=" . $_REQUEST['id']);
    }

    if ($row_data['allign_to'] != '') {
        $point_user_id = $row_data['allign_to'];
    } else {
        $point_user_id = $row_data['created_by'];
    }
    $points_date = week_range(date('Y-m-d'));
    $add_point = db_query("insert into user_points (stage_id,stage_name,point,week_number,date_from,date_to,no_of_seats,user_id,lead_id) values (1001,'Approved',10,'" . date('W') . "','$points_date[0]','$points_date[1]','" . $_POST['quant'] . "','" . $point_user_id . "','" . $_REQUEST['id'] . "') ");
}

if ($_POST['caller'] && $row_data['caller'] != $_POST['caller']) {
    $userid = getSingleresult("select user_id from callers where id='" . $_POST['caller'] . "'");
    if ($userid) {
        $caller_email = getSingleresult("select email from users where id='" . $userid . "'");
        $caller_name = getSingleresult("select name from users where id='" . $userid . "'");

        $addTo[] = ($caller_email);
        $addBcc[] = ("deepranshu.srivastava@arkinfo.in");

        $setSubject = "[LC Calling] New Lead assigned to you on DR Portal";
        $body    = "Hi,<br><br> Below account has been qualified for your LC working:-<br><br>
         <ul>
            <li><b>Account Name</b> : " . $row_data['company_name'] . " </li>
            <li><b>City</b> : " . $row_data['city'] . " </li>
            <li><b>Address</b> : " . htmlspecialchars($row_data['address'], ENT_QUOTES) . " </li>
            <li><b>Mobile</b> : " . $row_data['eu_mobile'] . " </li>
            <li><b>Reseller Name</b> : " . $row_data['r_name'] . " </li>
            <li><b>License Type</b> : " . $row_data['license_type'] . " </li>
            <li><b>Quantity</b> : " . $row_data['quantity'] . " </li>
        </ul><br>

            Thanks,<br>
            SketchUp DR Portal
            ";
        if (!$_POST['dr_code'])
        sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
    }
}

/*
    mail to partner for status change 
    */

//if ($_POST['status'] != 'Cancelled') {
if ($_POST['status'] && $row_data['status'] != $_POST['status']) {
    // $sm_email = getSingleresult("select email from users as u left join partners as p on u.id=p.sm_user where p.id='" . $row_data['team_id'] . "'");
    $sub_by_email = getSingleresult("select email from users as u left join orders as o on u.id=o.created_by where o.id='" . $row_data['id'] . "'");
    // print_r($sub_by_email);die;
    $manager_email = getSingleresult("select email from users where user_type='MNGR' and team_id='" . $row_data['team_id'] . "'");

    if($row_data['caller'] != $_POST['caller'])
    {
       $caller_name = getSingleresult("select name from callers where id='" . $_POST['caller'] . "'");

       $caller_email = getSingleresult("select email from users as u left join callers as c on u.id=c.user_id where c.id='" . $_POST['caller'] . "'"); 
    }
    else
    {
        $caller_name = getSingleresult("select name from callers where id='" . $row_data['caller'] . "'");

        $caller_email = getSingleresult("select email from users as u left join callers as c on u.id=c.user_id where c.id='" . $row_data['caller'] . "'");
    }
    
    // print_r($caller_email);die;
    $addTo[] = ($sub_by_email);
    $addTo[] = ($manager_email);
    $addTo[] = ($caller_email);


    $addCc[] = ("amjad.pathan@arkinfo.in");
    $addCc[] = ("niket@corelindia.co.in");
    $addCc[] = ("prashant.dongrikar@arkinfo.in");


    $addBcc[] = ("virendra.kumar@arkinfo.in");
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
    $setSubject = "Education Lead status has been changed [" . $row_data['company_name'] . "]";
    $body = "Hi,<br><br> Below account status has been changed to " . $stat . " on DR Portal:-<br><br>
            <ul>
            <li><b>Product Name</b> : " . $row_data['product_name'] . " </li>
            <li><b>Product Type</b> : " . $row_data['product_type'] . " </li>
            <li><b>Company Name</b> : " . $row_data['company_name'] . " </li>
            <li><b>Address & City</b> : " . htmlspecialchars($row_data['address'], ENT_QUOTES) . " (" . $row_data['city'] . ") </li>
            <li><b>Quantity</b> : " . $row_data['quantity'] . " </li>
            <li><b>Caller Assigned</b> : " . $caller_name . " </li>";


    if ($_POST['status'] == 'Undervalidation' || $_POST['status'] == 'Cancelled')
        $body .=    "<li><b>Reason</b> : " . $_POST['reason'] . " </li>";
    // $body .=    "<li><b>Admin Comment</b> : " . htmlspecialchars($_POST['add_comment']) . " </li>";
    $body .= "</ul><br>Thanks,<br>
            SketchUp DR Portal";
    $body = $body;
    if (!$_POST['dr_code'])
    sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
       
    if ($row_data['quantity'] >= 9) {

        $addTo[] = ("jayesh.patel@arkinfo.in");
        $addTo[] = ("maneesh.kumar@arkinfo.in");
        $addTo[] = ("shivram@corelindia.co.in");
        $addTo[] = ("sathish.venugopal@corel.com");
        $addBcc[] = ("virendra.kumar@arkinfo.in");
        $setSubject = "Lead status has been changed on DR Portal [" . $row_data['company_name'] . "]";
        $body = "Hi,<br><br> Below account status has been changed to " . $stat . " on DR Portal:-<br><br>
            <ul>
            <li><b>Product Name</b> : " . $row_data['product_name'] . " </li>
            <li><b>Product Type</b> : " . $row_data['product_type'] . " </li>
            <li><b>License Type</b> : " . $row_data['license_type'] . " </li>
            <li><b>VAR Organization Name</b> : " . $row_data['r_name'] . " </li>
            <li><b>Submitted by </b> : " . $row_data['r_user'] . " </li>
            <li><b>Account Name</b> : " . $row_data['company_name'] . " </li>
            <li><b>Lead Type</b> : " . $row_data['lead_type'] . " </li>
            <li><b>Quantity</b> : " . $row_data['quantity'] . " </li>
            <li><b>Address</b> : " . htmlspecialchars($row_data['address'], ENT_QUOTES) . " <br>          
            <li><b>Assigned To</b> : " . $caller_name . " </li>";

        if ($_POST['status'] == 'Undervalidation' || $_POST['status'] == 'Cancelled')
            $body .=    "<li><b>Reason</b> : " . $_POST['reason'] . " </li>";
        $body .=    "<li><b>Admin Comment</b> : " . htmlspecialchars($_POST['add_comment']) . " </li>";
        $body .= "</ul></br>Thanks,<br>
            SketchUp DR Portal";

        $body = $body;
        if (!$_POST['dr_code'])
        sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
    }
}

/*
    mail to caller in case status is cancelled
    */

// if ($_POST['status'] == 'Cancelled' && $row_data['is_iss_lead'] == 1) {
//     $userid = getSingleresult("select user_id from callers where id='" . $row_data['caller'] . "'");

//     $caller_email = getSingleresult("select email from users where id='" . $userid . "'");
//     $caller_name = getSingleresult("select name from users where id='" . $userid . "'");

//     $addTo[] = ($caller_email);

//     $addCc[] = ("prashant.dongrikar@arkinfo.in");
//     $addCc[] = ("kailash.bhurke@arkinfo.in");
//     $addCc[] = ("virendra@corelindia.co.in");
//     //$addBcc[] = ("coreldrsupport@arkinfo.in");
//     if ($_POST['status'] == 'Cancelled') {
//         $stat = '<span style="color:red">Unqualified</span>';
//     }


//     $setSubject = "Lead status has been changed on DR Portal [" . $row_data['company_name'] . "]";
//     $body = "Hi,<br><br> Below account status has been changed to " . $stat . " on DR Portal:-<br><br>
//             <ul>
//             <li><b>Product Name</b> : " . $row_data['product_name'] . " </li>
//             <li><b>Product Type</b> : " . $row_data['product_type'] . " </li>
//             <li><b>License Type</b> : " . $row_data['license_type'] . " </li>
//             <li><b>Account Name</b> : " . $row_data['company_name'] . " </li>
//             <li><b>Lead Type</b> : " . $row_data['lead_type'] . " </li>
//             <li><b>Quantity</b> : " . $row_data['quantity'] . " </li>           
//             <li><b>Address</b> : " . htmlspecialchars($row_data['address'], ENT_QUOTES) . " <br>
//             <li><b>Mobile</b> : " . $row_data['eu_mobile'] . " </li>
//             <li><b>Reseller Name</b> : " . $row_data['r_name'] . " </li>
//             <li><b>City</b> : " . $row_data['city'] . " </li>           
//             <li><b>Assigned To</b> : " . $caller_name . " </li>";

//     if ($data['status'] == 'Undervalidation' || $_POST['status'] == 'Cancelled')
//         $body .=    "<li><b>Reason</b> : " . $_POST['reason'] . " </li>";
//     $body .=    "<li><b>Admin Comment</b> : " . htmlspecialchars($_POST['add_comment']) . " </li>";
//     $body .= "</ul><br>Thanks,<br>
//             SketchUp DR Portal";
//     $body = $body;

//     if (!$_POST['dr_code'])
//         sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
// }

$sfdc_check = ($_POST['sfdc_check']) ? $_POST['sfdc_check'] : '0';

if ($_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'EM' || $_SESSION['user_type'] == 'OPERATIONS') {
    //if($_POST['status']=='Approved' && $row_data['status']!='Approved'){
    $ncdate = strtotime(date('Y-m-d'));
    $closeDate = strtotime($row_data['close_time']);
    if ($ncdate > $closeDate) {
        $close_time = date('Y-m-d', strtotime('+29 days', strtotime(date('Y-m-d h:i:s'))));
    } else {
        $close_time = $row_data['close_time'];
    }
} else {
    $close_time = $row_data['close_time'];
}


// print_r($_POST['status']);

if (isset($_POST['submit']) && $_SESSION['user_type'] != 'REVIEWER') {
    //print_r($_POST);die;
    if ($row_data['status'] == 'Approved') {

        $ncdate = strtotime(date('Y-m-d'));
        $closeDate = strtotime($row_data['close_time']);

        if ($ncdate > $closeDate) {
            $modify_name = ($_POST['status'] == 'Approved') ? 'Qualified' : 'N/A';

            $res = db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['id'] . "','Re-log Status','Expired','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
        }
        //print_r($res);die;
    }

    $status = $_POST['status'] ? $_POST['status'] : $row_data['status'];
    $reason = $_POST['reason'] ? $_POST['reason'] : $row_data['reason'];
    $add_comment = $_POST['add_comment'] ? $_POST['add_comment'] : $row_data['add_comment'];
    $caller = $_POST['caller'] ? $_POST['caller'] : $row_data['caller'];
    $attachement = !empty($_FILES["admin_attachment"]["name"]) ? $target_file : $row_data['user_attachement'];
    $partner_close_date = $_POST['partner_close_date'] ? $_POST['partner_close_date'] : $row_data['partner_close_date'];
    //  $approval_time = ($_POST['status']=='Approved' && $row_data['status']!='Approved')?date('Y-m-d h:i:s'):$row_data['approval_time'];
    //print_r($row_data['status']);die;
    $approval_time = ($_POST['status']) ? date('Y-m-d H:i:s') : $row_data['approval_time'];


    $sql =  db_query("update orders set code='" . $code . "', status='" . $status . "', reason='" . $reason . "',add_comment='" . htmlspecialchars($add_comment) . "',caller='" . $caller . "',approval_time='" . $approval_time . "',close_time='" . $close_time . "',admin_attachment='" . $attachement . "',partner_close_date='" . $partner_close_date . "',sfdc_check='" . $sfdc_check . "' where id=" . $_REQUEST['id']);
    //print_r($sql);die;

    if ($sql) {

        redir("education_leads_admin.php?update=success", true);
    }
}


//}

if ($_POST['remarks'] && !$_POST['activity_edit']) {
    //echo "deepranshu"; die;
    $res = db_query("insert into activity_log (pid,description,activity_type,call_subject,added_by,data_ref) values ('" . intval($_POST['pid']) . "','" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "','Lead','" . $_POST['call_subject'] . "','" . $_SESSION['user_id'] . "',1)");

    $email = db_query("select r_email,r_name,r_user,lead_type,company_name,eu_email,eu_mobile,team_id,quantity,partner_close_date,caller from orders where id=" . intval($_POST['pid']));

    if ($sm_email)
        $sm_email = getSingleresult("select email from users as u left join partners as p on u.id=p.sm_user where p.id='" . $row_data['team_id'] . "'");
    //print_r($sm_email);die;
    $data = db_fetch_array($email);
    $addCc[] = ("prashant.dongrikar@arkinfo.in");
    $addCc[] = ("kailash.bhurke@arkinfo.in");
    $addBcc[] = ("virendra.kumar@arkinfo.in");

    $addCc[] = ($sm_email); // sales manager email

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

if ($_REQUEST['id']) {

    $sql = db_query("select o.*,c.name as campaign ,tp.*,p.product_name,tpp.product_type,tpp.id as type_id from orders as o left join campaign as c on o.campaign_type = c.id left join tbl_lead_product as tp on o.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id  where o.id=" . $_REQUEST['id']);

    $data = db_fetch_array($sql);
    @extract($data);
} else {
    redir("education_leads_admin.php", true);
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

                                    <small class="text-muted">Home > View Education Lead</small>
                                    <h4 class="font-size-14 m-0 mt-1">View Education Lead</h4>
                                </div>

                                <!-- <a href="#" id="addnewtask"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="Add New Task" class="right-side bottom-right waves-effect waves-light btn-primary btn btn-circle btn-md pull-right m-l-10"><i class="ti-plus text-white"></i></button></a> -->
                               
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
                                                        <?= $r_user ?> &nbsp;
                                                        <!-- <?php $query = access_role_permission();
                                                        $fetch_query = db_fetch_array($query);
                                                        if ($fetch_query['edit_ownership'] == 1) { ?>
                                                            <button class="btn btn-primary" onclick="change_user('<?= $_GET['id'] ?>','<?= $_SESSION['team_id'] ?>')">Change</button>
                                                        <?php } ?> -->
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
                                                </tr>
                                                <tr>
                                                <td>Product Type</td>
                                                <td>
                                                    <?= $product_type ?>&nbsp;
                                                   <!--  <?php if ($product_name == 'CDGS Fresh') { ?>
                                                        &nbsp;&nbsp;<select onchange="change_product_type('<?= $_GET['id'] ?>',this.value);" id="type_product">
                                                            <option value="">Change Product Type</option>
                                                            <option value="1">Change to CDGS Perpetual</option>
                                                            <option value="2">Change to CDGS Annual</option>
                                                            <option value="3">Change to CDGS Edu</option>
                                                        </select>
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
                                                    <td width="35%">Lead Source</td>
                                                    <td width="65%"><?= $source ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Lead Type</td>
                                                    <td>
                                                        <?= $lead_type ?>
                                                        <input type="hidden" value="<?= $lead_type ?>" id="ltype" />
                                                        <?php

                                                        if ($_SESSION['sales_manager'] != 1) { ?>
                                                            &nbsp;&nbsp;<select onchange="update_type(this.value);" id="type_lead">
                                                                <option value="">Change Lead Type</option>
                                                                <option value="LC">Change to LC</option>
                                                                <option value="BD">Change to BD</option>
                                                                <option value="Incoming">Change to Incoming</option>
                                                            </select>
                                                        <?php }

                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Company Name</td>
                                                    <td>

                                                        <?php $search = trim($company_name);
                                                        $keys = explode(" ", $search);
                                                        if ($keys[0]) {
                                                            $query = "select id,r_name,company_name,eu_email,parent_company,eu_name,created_date,stage,status,close_time from orders where company_name like '%" . $keys[0] . "%'  and id!='" . $id . "'";
                                                        }
                                                        if ($keys[1]) {
                                                            $query .= " UNION select id,r_name,company_name,eu_email,parent_company,eu_name,created_date,stage,status,close_time from orders where company_name like '" . $keys[1] . "%' and id!='" . $id . "'";
                                                        }
                                                        $query .= " order by id asc ";
                                                        $cnt = getSingleresult("select count(*) as cnt from (" . $query . ") as tb_cnt"); ?>
                                                        <?= $company_name ?> <a class="duplicate_check" data-value="<?= $company_name ?>" href="javascript:void(0)" data-url="company_name" style="float:right"><?= $cnt ?> possible duplicate with this Company Name</a>



                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Parent Company</td>
                                                    <td>
                                                        <?= $parent_company ?> <?php if ($parent_company) { ?><a class="duplicate_check" data-value="<?= $parent_company ?>" href="javascript:void(0)" data-url="parent_company" style="float:right"><?= getSingleresult("select count(id) from orders where parent_company like '%" . $parent_company . "%' and id!='" . $id . "' ") ?> possible duplicate with this Parent Company Name</a><?php } ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Landline Number</td>
                                                    <td>
                                                        <?= $landline ?><?php if ($landline) { ?><a class="duplicate_check" data-value="<?= $landline ?>" href="javascript:void(0)" data-url="landline" style="float:right"><?= getSingleresult("select count(id) from orders where landline like '%" . $landline . "%' and id!='" . $id . "' ") ?> possible duplicate with this Landline No.</a><?php } ?>

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
                                                        <!-- <a class="duplicate_check" data-value="<?= $address ?>" href="javascript:void(0)" data-url="address" style="float:right"><?= getSingleresult("select count(id) from orders where address like '%" . $address . "%' and id!='" . $id . "'"); ?> possible duplicate with this address</a>
                                    -->
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
                                                        <?php
                                                        $search = trim($eu_email);
                                                        $keys = explode("@", $search);

                                                        if ($keys[0]) {
                                                            $query = "select id,r_name,company_name,eu_email,parent_company,eu_name,created_date,stage,status,close_time from orders where eu_email like '" . $keys[0] . "%' and id!='" . $id . "'";
                                                        }
                                                        if ($keys[1]) {
                                                            $query .= " UNION select id,r_name,company_name,eu_email,parent_company,eu_name,created_date,stage,status,close_time from orders where eu_email like '" . $keys[1] . "%' and id!='" . $id . "'";
                                                        }

                                                        $email_cnt = getSingleresult("select count(*) as cnt from (" . $query . ") as email_cnt");
                                                        ?>
                                                        <?= $eu_email ?><a class="duplicate_check" href="javascript:void(0)" data-value="<?= $eu_email ?>" data-url="eu_email" style="float:right"><?= $email_cnt ?> possible duplicate with this email</a>
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
                                                        <?php
                                                        $value = trim($eu_mobile);
                                                        $check1 = '0' . $value;
                                                        $check2 = '+91' . $value;
                                                        $check3 = '91' . $value;
                                                        $duplicate_name = 'Mobile';
                                                        $query = "select id from orders where (eu_mobile like '%" . $value . "%' or  eu_mobile like '%" . $check1 . "%'  or eu_mobile like '%" . $check2 . "%' or eu_mobile like '%" . $check3 . "%') and id!='" . $id . "'";
                                                        $query .= " UNION select id from orders where (landline like '%" . $value . "%' or  landline like '%" . $check1 . "%'  or landline like '%" . $check2 . "%' or landline like '%" . $check3 . "%') and id!='" . $id . "'";
                                                        $query .= " UNION select id from orders where (eu_landline like '%" . $value . "%' or  eu_landline like '%" . $check1 . "%'  or eu_landline like '%" . $check2 . "%' or eu_landline like '%" . $check3 . "%') and id!='" . $id . "'";
                                                        $mobile_cnt = db_num_array(db_query($query));
                                                        ?>

                                                        <?= $eu_mobile ?><a class="duplicate_check" href="javascript:void(0)" data-value="<?= $eu_mobile ?>" data-url="eu_mobile" style="float:right"><?= $mobile_cnt ?> possible duplicate with this mobile number</a>
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
                                                <!-- <tr>
                                                    <td width="35%">Campaign</td>
                                                    <td width="65%"><?= $campaign ?></td>
                                                </tr> -->
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
                                                    <?= date('d-m-Y H:i:s', strtotime($created_date)) ?>
                                                </td>
                                                </tr>

                                                <?php if ($_SESSION['user_type'] != 'REVIEWER') { ?>
                                                    <?php if ($user_attachement && $user_attachement != '' && strpos($user_attachement, ".")) { ?>
                                                        <tr>
                                                        <td>Attachment</td>
                                                        <td>
                                                        <a href="<?= $user_attachement ?>" target="_blank">View/Download</a>
                                                        </td>
                                                        </tr>
                                                        <tr>
                                                        <?php } ?>
                                                        <?php if ($admin_attachment  && strpos($admin_attachment, ".")) { ?>
                                                        <tr>
                                                            <td>Admin Attachment</td>
                                                            <td>
                                                            <a href="<?= $admin_attachment ?>" target="_blank">View/Download</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                        <?php } ?>
                                                    <?php } ?>
                                             <form action="#" method="post" id="saveform" name="saveform" enctype="multipart/form-data">
                                            <?php if ($_SESSION['user_type'] != 'REVIEWER') {
                                             $query = access_role_permission();
                                                $fetch_query = db_fetch_array($query); 
                                                ?>
                                            <td>Status</td>

                                            <td>
                                                <select <?= (($_SESSION['sales_manager'] == 1) ? 'disabled' : '') ?> onchange="status_update(this.value)" class="form-control" required name="status">
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
                                <?php } ?>
                                    <tr id="caller" <?php if ($status != 'Approved' && $lead_type != "LC") { ?> style="display:none" <?php } ?>>
                                        <td>Caller</td>
                                        <td>
                                            <?php if (is_numeric($caller) || $caller == '') {
                                                $res = db_query("select * from callers order by name ASC");
                                            ?>
                                                <select name="caller" id="caller" class="form-control" data-validation-required-message="This field is required">
                                                    <option value="">---Select---</option>
                                                    <?php while ($row = db_fetch_array($res)) { ?>
                                                        <option <?= (($caller == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] . ' (' . $row['caller_id'] . ')' ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php
                                            } else { ?>

                                                <select class="form-control" id="caller_dd" name="caller">
                                                    <option value="">---Select---</option>
                                                    <option <?= (($caller == 'Bhagyashree Shetty') ? 'selected' : '') ?> value="Bhagyashree Shetty">Bhagyashree Shetty</option>
                                                    <option <?= (($caller == 'Fayyaz Ahmed') ? 'selected' : '') ?> value="Fayyaz Ahmed">Fayyaz Ahmed</option>
                                                    <option <?= (($caller == 'Manisha Shinde') ? 'selected' : '') ?> value="Manisha Shinde">Manisha Shinde</option>
                                                    <option <?= (($caller == 'Prathamesh Kargutkar') ? 'selected' : '') ?> value="Prathamesh Kargutkar">Prathamesh Kargutkar</option>
                                                    <option <?= (($caller == 'Shweta Makwana') ? 'selected' : '') ?> value="Shweta Makwana">Shweta Makwana</option>
                                                    <option <?= (($caller == 'Seyed Mavujeen') ? 'selected' : '') ?> value="Seyed Mavujeen">Seyed Mavujeen</option>
                                                    <option <?= (($caller == 'Nitish Shetty') ? 'selected' : '') ?> value="Nitish Shetty">Nitish Shetty</option>
                                                    <option <?= (($caller == 'Rukaiya Shaikh') ? 'selected' : '') ?> value="Rukaiya Shaikh">Rukaiya Shaikh</option>
                                                    <option <?= (($caller == 'Azhar Nirban') ? 'selected' : '') ?> value="Azhar Nirban">Azhar Nirban</option>
                                                    <option <?= (($caller == 'Kiran Sharma') ? 'selected' : '') ?> value="Kiran Sharma">Kiran Sharma</option>
                                                    <option <?= (($caller == 'Fiza Shaikh') ? 'selected' : '') ?> value="Fiza Shaikh">Fiza Shaikh</option>
                                                    <option <?= (($caller == 'Vijay Singh') ? 'selected' : '') ?> value="Vijay Singh">Vijay Singh</option>
                                                    <option <?= (($caller == 'Nithesh Anchan') ? 'selected' : '') ?> value="Nithesh Anchan">Nithesh Anchan</option>
                                                    <option <?= (($caller == 'Omkar Mhaske') ? 'selected' : '') ?> value="Omkar Mhaske">Omkar Mhaske</option>

                                                </select>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr id="sfdc_check" <?php if ($status != 'Approved') { ?> style="display:none" <?php } ?>>
                                        <td>Exclude from SFDC Export</td>
                                        <td><input type="checkbox" class="checkbox" name="sfdc_check" <?= ($sfdc_check ? 'checked' : '') ?> value="1" id="sfdc_checkbox"><label for="sfdc_checkbox"></label></td>
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
                                            <?= $stage ?>
                                        </td>
                                    </tr>
                                    <tr id="payment" <?php if ($stage != "EU PO Issued") { ?> style="display:none" <?php } ?>>
                                        <?php $payment_status = $add_comm; ?>
                                        <td>Payment Status</td>
                                        <td>
                                            <?= $add_comm ?>
                                        </td>
                                    </tr>
                                    <?php if ($stage == "EU PO Issued") { ?>
                                        <tr id="op" <?php if ($add_comm == 'Payment in Installments') { ?> style="display:none" <?php } ?>>
                                            <td>Order Processing for this month</td>
                                            <td><?= $data['op_this_month'] ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr id="pay_tab" <?php if ($add_comm != 'Payment in Installments') { ?> style="display:none" <?php } ?>>
                                        <td>Installment Details</td>
                                        <?php
                                        $inst_query = db_query("select * from installment_details where type='Lead' and pid='" . intval($_GET['id']) . "'");
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
                                        <td><input type="text" value="<?= $partner_close_date ?>" class="form-control col-md-2 datepicker" readonly id="cl_date" name="partner_close_date" /></td>
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
                                        </div>

                                        <div id="collapseOne8" class="collapse" aria-labelledby="headingOne8" data-parent="#accordionExample2"> 
                                            <a data-toggle="modal" onclick="add_activity(<?= $_GET['id'] ?>,'<?=$company_name?>')" data-animation="bounce" data-target=".bs-example-modal-center"><button class="btn1 btn-primary mt-2">Log a Call</button></a></h5>



                                        <div class="row">
                                            <div class="col-md-12">

                                                    <?php
                                                        $query = access_role_permission();
                                                        $fetch_query = db_fetch_array($query);

                                                        $new = db_query("select id,description,created_date,added_by,call_subject from activity_log where pid='" . intval($_GET['id']) . "' UNION SELECT id,description,created_date,added_by,id from caller_comments where pid='" . intval($_GET['id']) . "' union select id,comment as description,created_date,added_by,id from review_log where lead_id='" . intval($_GET['id']) . "' order by created_date desc");
                                                        $goal = db_query("select * from activity_log where pid='" . intval($_GET['id']) . "' order by created_date desc");
                                                        $count = mysqli_num_rows($new);
                                                        $i = $count;
                                                        if ($count) {
                                                            echo  ' <table class="col-12"><tr><th>S.No</th><th>Subject</th><th>Description</th><th>Added By</th><th>Date</th>';
                                                            // if ($fetch_query['edit_log'] == 1) {
                                                            //     '<th>Action</th>';
                                                            // }
                                                            '</tr>';

                                                            while ($data_n = db_fetch_array($new)) { ?>

                                                <tr>
                                                    <td><?= $i ?></td>
                                                    <td><?= ($data_n['call_subject'] ? $data_n['call_subject'] : 'N/A') ?></td>
                                                    <td><?= $data_n['description'] ?></td>
                                                    <td><?= (is_numeric($data_n['added_by']) ? getSingleresult("select concat(name,'-',CASE  WHEN user_type='USR' THEN 'Partner User'    WHEN user_type='ADMIN' and sales_manager=0 THEN 'ADMIN' WHEN user_type='ADMIN' and sales_manager=1 THEN 'Sales Manager'   WHEN user_type='MNGR' THEN 'Partner Manager'  ELSE 'Caller' END) as nme from users where id='" . $data_n['added_by'] . "'") : '<span style="color:red">(Lead Review!)</span> ' . $data_n['added_by']) ?></td>
                                                    <td><?= date('d-m-Y H:i:s', strtotime($data_n['created_date'])) ?></td>
                                                    <?php
                                                            // if ($fetch_query['edit_log'] == 1) { 
                                                      if ($_SESSION['user_type'] == 'SUPERADMIN'){
                                                    ?>
                                                        <td><a href="javascript:void(0)" title="Edit" id=but<?= $data['id'] ?> onclick="edit_activity('<?= $data_n['id'] ?>','<?=$company_name?>')"> <i style="font-size:18px" class="mdi mdi-pencil"></i></a></td>
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
                                        <?php $query = access_role_permission();
                                        $fetch_query = db_fetch_array($query); ?>
                                            <?php if ($_SESSION['sales_manager'] != 1) { ?>
                                                <button type="submit" name="submit" class="btn btn-success">Save</button>
                                                <input type="hidden" value="<?= $created_by ?>" name="lead_by" />
                                                <input type="hidden" value="<?= $quantity ?>" name="quant" />
                                                </form>

                                                <?php
                                                if ($iss) { ?>
                                                    <a href="edit_order.php?eid=<?= $id ?>"><button type="button" class="btn btn-primary">Edit</button></a>

                                                    <?php } else {

                                                    if ($status == 'Undervalidation' && $fetch_query['edit_lead'] == 1) { ?>
                                                        <a href="edit_order.php?eid=<?= $id ?>"><button type="button" class="btn btn-primary">Edit</button></a>
                                                    <?php } elseif ($status != 'Undervalidation' && $_SESSION['user_type'] != 'OPERATIONS EXECUTIVE') { ?>
                                                        <a href="edit_order.php?eid=<?= $id ?>"><button type="button" class="btn btn-primary">Edit</button></a>
                                            <?php    }
                                                }
                                            } ?>
                                            <button type="button" onclick="javascript:history.go(-1);" class="btn btn-danger">Back</button>

                                        </div>

                                    </div>
                                </div>
                            </div> <!-- end col -->


                        </div> <!-- end row -->

                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <div id="myModal" class="modal fade" role="dialog" >

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
                        // var ltype = $("#ltype").val();
                        // if (ltype == 'LC') {
                        //     $("#caller").show();
                        //     $("#caller_dd").prop('required', true);
                        // }
                    } else if (r == 'On-Hold') {
                        $("#reason_ud").hide();
                        $("#reason").hide();
                        $("#sfdc_check").hide();
                    } else if(r == 'Pending'){
                        $("#reason").hide();
                        $("#reason_ud").hide();
                        $("#reason_ud").prop('required', false);
                        $("#reason_dd").prop('required', false);
                        $("#caller").hide();
                        $("#sfdc_check").hide();
                        $("#caller_dd").prop('required', false);
                    } else {
                        $("#reason").hide();
                        $("#reason_ud").show();
                        $("#reason_ud").prop('required', true);
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
                                                        window.location = "education_lead_admin_view.php?id=<?= $_GET['id'] ?>";
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

                        function change_product_type(ed_id, type) { 

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
                                            ed_id: ed_id,
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

                        function edit_activity(id,company_name) {
                            $.ajax({
                                type: 'POST',
                                url: 'edit_activity.php',
                                data: {
                                    id: id,
                                    company_name:company_name
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
                        $('.add_lead').height(wfheight - 220);
                    });
                </script>