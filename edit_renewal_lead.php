<?php include('includes/header.php');

include_once('helpers/DataController.php');

$modify_log = new DataController();

if ($_REQUEST['eid']) {
    $query = db_query("select o.*,tp.*,p.product_name,p.id as productId,tpp.product_type from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id where o.id=" . $_REQUEST['eid']);

    $row = db_fetch_array($query);
    @extract($row);
   
}

if ($_POST['quantity']) {
    if (!empty($_FILES["user_attachment"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . time() . basename($_FILES["user_attachment"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($_FILES["user_attachment"]["size"] > 4000000) {
            echo "<script>alert('Sorry, your file is too large!')</script>";
            redir("orders.php", true);
        } else {
            move_uploaded_file($_FILES["user_attachment"]["tmp_name"], $target_file);
        }
    }


    if (!$_POST['license_type']) {
        $_POST['license_type'] = 'Renewal';
    }

    $sql = db_query("select * from orders where id=" . $_REQUEST['eid'] . " limit 1");
    $previous_data = db_fetch_array($sql);


    if ($previous_data['partner_close_date'] != $_POST['partner_close_date']) {
        // $data = [
        //     'lead_id'         => $_REQUEST['eid'],
        //     'type'            => 'Close Date',
        //     'previous_name'   => $previous_data['partner_close_date'],
        //     'modify_name'     => $_POST['partner_close_date'],
        //     'created_date'    => now(),
        //     'created_by'      => $_SESSION['user_id']
        // ];

        //  $res = $data_oops->insert($data, "lead_modify_log");

        $modify_name = $_POST['partner_close_date'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Close Date','" . $previous_data['partner_close_date'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }


    if ($previous_data['license_type'] != $_POST['license_type']) {
        $modify_name = $_POST['license_type'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','License Type','" . $previous_data['license_type'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['address'] != $_POST['address']) {
        $modify_name = $_POST['address'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Address','" . $previous_data['address'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($previous_data['eu_email'] != $_POST['eu_email']) {
        $modify_name = $_POST['eu_email'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Email','" . $previous_data['eu_email'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($previous_data['eu_designation'] != $_POST['eu_designation']) {
        $modify_name = $_POST['eu_designation'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Designation','" . $previous_data['eu_designation'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($previous_data['department'] != $_POST['department']) {
        $modify_name = $_POST['department'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Department','" . $previous_data['department'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($previous_data['eu_role'] != $_POST['eu_role']) {
        $modify_name = $_POST['eu_role'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Role','" . $previous_data['eu_role'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['eu_mobile'] != $_POST['eu_mobile']) {
        $modify_name = $_POST['eu_mobile'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Mobile','" . $previous_data['eu_mobile'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }


    // if ($previous_data['os'] != $_POST['os']) {
    //     $modify_name = $_POST['os'];
    //     $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','OS','" . $previous_data['os'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    // }
    // if ($previous_data['version'] != $_POST['version']) {
    //     $modify_name = $_POST['version'];
    //     $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','OS Version','" . $previous_data['version'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    // }
    if ($previous_data['runrate_key'] != $_POST['runrate_key']) {
        $modify_name = $_POST['runrate_key'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Runrate/Key','" . $previous_data['runrate_key'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($previous_data['company_name'] != $_POST['company_name']) {
        $modify_name = $_POST['company_name'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Company Name','" . $previous_data['company_name'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['region'] != $_POST['region']) {
        $modify_name = $_POST['region'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Region','" . $previous_data['region'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['pincode'] != $_POST['pincode']) {
        $modify_name = $_POST['pincode'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Pincode','" . $previous_data['pincode'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['city'] != $_POST['city']) {
        $modify_name = $_POST['city'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','City','" . $previous_data['city'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['eu_name'] != $_POST['eu_name']) {
        $modify_name = $_POST['eu_name'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Contact Person','" . $previous_data['eu_name'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }


    if ($previous_data['parent_company'] != $_POST['parent_company']) {
        $modify_name = $_POST['parent_company'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Parent Company','" . $previous_data['parent_company'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['quantity'] != $_POST['quantity']) {
        $modify_name = $_POST['quantity'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Quantity','" . $previous_data['quantity'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    // renewal edit section 


    if ($previous_data['license_key'] != $_POST['license_key']) {
        $modify_name = $_POST['license_key'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','License Number','" . $previous_data['license_key'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['license_end_date'] != $_POST['license_end_date']) {
        $modify_name = $_POST['license_end_date'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','License End Date','" . $previous_data['license_end_date'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($previous_data['landline'] != $_POST['landline']) {
        $modify_name = $_POST['landline'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Landline Number1','" . $previous_data['landline'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($previous_data['eu_landline'] != $_POST['eu_landline']) {
        $modify_name = $_POST['eu_landline'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Landline Number2','" . $previous_data['eu_landline'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($previous_data['industry'] != $_POST['industry']) {
        $modify_name = getSingleresult("select name from industry where id=" . $_POST['industry']);

        $previous_industry = getSingleresult("select name from industry where id=" . $previous_data['industry']);
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Industry','" . $previous_industry . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }
    if ($previous_data['sub_industry'] != $_POST['sub_industry']) {
        $modify_name = getSingleresult("select name from sub_industry where id=" . $_POST['sub_industry']);
        if(!empty($previous_data['sub_industry'])){
            $previous_subIndustry = getSingleresult("select name from sub_industry where id=" . $previous_data['sub_industry']);
        }
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Sub Industry','" . $previous_subIndustry . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    if ($previous_data['caller'] != $_POST['caller']) {
        $modify_name = getSingleresult("select name from callers where id=" . $_POST['caller']);
        if(!empty($previous_data['caller'])){
            $previous_caller = getSingleresult("select name from callers where id=" . $previous_data['caller']);
        }
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $_REQUEST['eid'] . "','Caller','" . $previous_caller . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
    }

    // end 
    if ($row['status'] == 'Undervalidation') {
        $campaign_type = $_POST['campaign_type'] ? $_POST['campaign_type'] : 0;
        $user_image = !empty($_FILES["user_attachment"]["name"]) ? $target_file : $row['user_attachement'];


        $runrate_key = ($_POST['quantity'] <= 3) ? 'Runrate' : 'Key';

        $res = db_query("update `orders` set `source`='Corel Team', `company_name`='" . $_POST['company_name'] . "', `parent_company`='" . $_POST['parent_company'] . "', `landline`='" . $_POST['landline'] . "',`region`='" . $_POST['region'] . "', `industry`='" . $_POST['industry'] . "',`sub_industry`='" . $_POST['sub_industry'] . "', `address`='" . htmlspecialchars($_POST['address'], ENT_QUOTES) . "', `pincode`='" . $_POST['pincode'] . "', `state`='" . $_POST['state'] . "', `city`='" . $_POST['city'] . "', `country`='" . $_POST['country'] . "', `eu_name`='" . $_POST['eu_name'] . "', `eu_email`='" . $_POST['eu_email'] . "', `eu_landline`='" . $_POST['eu_landline'] . "', `department`='" . $_POST['department'] . "', `eu_mobile`='" . $_POST['eu_mobile'] . "', `eu_designation`='" . $_POST['eu_designation'] . "', `eu_role`='" . $_POST['eu_role'] . "', `account_visited`='" . $_POST['account_visited'] . "', `visit_remarks`='" . htmlspecialchars($_POST['visit_remarks'], ENT_QUOTES) . "', `confirmation_from`='" . $_POST['confirmation_from'] . "', `license_type`='Renewal', `quantity`='" . $_POST['quantity'] . "',user_attachement='" . $user_image . "',os='" . $_POST['os'] . "',version='" . $_POST['version'] . "',runrate_key='" . $runrate_key . "',partner_close_date='" . $_POST['partner_close_date'] . "',status='Pending',created_date=now(),campaign_type='" . $_POST['campaign_type'] . "',r_name='" . $row['r_name'] . "',r_email='" . $row['r_email'] . "',r_user='" . $row['r_user'] . "' ,license_key='" . $_POST['license_key'] . "',license_end_date='" . $_POST['license_end_date'] . "',caller='".$_POST['caller']."' where id=" . $_POST['eid']);


        if ($res) {
            if (count($_POST['e_name']) >= 0) {
                $number = count($_POST["e_name"]);

                $delete_query = db_query("delete from tbl_lead_contact where lead_id=" . $_REQUEST['eid']);

                for ($i = 0; $i < $number; $i++) {
                    $query =  insertLeadContact('tbl_lead_contact', $_REQUEST['eid'], $_POST['e_name'][$i], $_POST['e_email'][$i], $_POST['e_mobile'][$i], $_POST['e_designation'][$i]);
                }
            }
        }
    } else {
        $user_image = !empty($_FILES["user_attachment"]["name"]) ? $target_file : $row['user_attachement'];
        //print_r($user_image);die;
        //$campaign_type = !empty($_POST['campaign_type']) ? $_POST['campaign_type'] : '0';
        $runrate_key = ($_POST['quantity'] <= 3) ? 'Runrate' : 'Key';

        $data = ['r_name' => $r_name, 'r_email' => $r_email, 'r_user' => $r_user, 'source' => 'Corel Team', 'lead_type' => 'BD', 'company_name' => $_POST['company_name'], 'parent_company' => $_POST['parent_company'], 'landline' => $_POST['landline'], 'region' => $_POST['region'], 'industry' => $_POST['industry'], 'sub_industry' => $_POST['sub_industry'], 'address' => htmlspecialchars($_POST['address'], ENT_QUOTES), 'pincode' => $_POST['pincode'], 'state' => $_POST['state'], 'city' => $_POST['city'], 'country' => $_POST['country'], 'eu_name' => $_POST['eu_name'], 'eu_email' => $_POST['eu_email'], 'eu_landline' => $_POST['eu_landline'], 'department' => $_POST['department'], 'eu_mobile' => $_POST['eu_mobile'], 'eu_designation' => $_POST['eu_designation'], 'eu_role' => $_POST['eu_role'], 'account_visited' => $_POST['account_visited'], 'visit_remarks' => htmlspecialchars($_POST['visit_remarks'], ENT_QUOTES), 'confirmation_from' => $_POST['confirmation_from'], 'license_type' => 'Renewal', 'quantity' => $_POST['quantity'], 'user_attachement' => $target_file, 'os' => $_POST['os'] , 'runrate_key' => $runrate_key,'license_key'=>$_POST['license_number'],'license_end_date'=>$_POST['license_end_date'],'partner_close_date'=>$_POST['close_date'],'caller'=>$_POST['caller']];

        $where = ['id'=>$_REQUEST['eid']];
        
        $res = $modify_log->update($data, "orders",$where);

       // $res = db_query("update  `orders` set `source`='Corel Team','lead_type'='BD', `company_name`='" . $_POST['company_name'] . "', `parent_company`='" . $_POST['parent_company'] . "', `landline`='" . $_POST['landline'] . "',`region`='" . $_POST['region'] . "', `industry`='" . $_POST['industry'] . "',`sub_industry`='" . $_POST['sub_industry'] . "', `address`='" . htmlspecialchars($_POST['address'], ENT_QUOTES) . "', `pincode`='" . $_POST['pincode'] . "', `state`='" . $_POST['state'] . "', `city`='" . $_POST['city'] . "', `country`='" . $_POST['country'] . "', `eu_name`='" . $_POST['eu_name'] . "', `eu_email`='" . $_POST['eu_email'] . "', `eu_landline`='" . $_POST['eu_landline'] . "', `department`='" . $_POST['department'] . "', `eu_mobile`='" . $_POST['eu_mobile'] . "', `eu_designation`='" . $_POST['eu_designation'] . "', `eu_role`='" . $_POST['eu_role'] . "', `account_visited`='" . $_POST['account_visited'] . "', `visit_remarks`='" . htmlspecialchars($_POST['visit_remarks'], ENT_QUOTES) . "', `confirmation_from`='" . $_POST['confirmation_from'] . "', `license_type`='Renewal', `quantity`='" . $_POST['quantity'] . "',user_attachement='" . $user_image . "',os='" . $_POST['os'] . "',version='" . $_POST['version'] . "',runrate_key='" . $runrate_key . "',partner_close_date='" . $_POST['partner_close_date'] . "',campaign_type=0,r_name='" . $r_name . "',r_email='" . $r_email . "',r_user='" . $r_user . "',license_key='" . $_POST['license_number'] . "',license_end_date='" . $_POST['license_end_date'] . "' where id=" . $_REQUEST['eid']);


        if ($res) {
            if (count($_POST['e_name']) >= 0) {

                $number = count($_POST["e_name"]);

                $delete_query = db_query("delete from tbl_lead_contact where lead_id=" . $_REQUEST['eid']);

                for ($i = 0; $i < $number; $i++) {
                    $query =  insertLeadContact('tbl_lead_contact', $_REQUEST['eid'], $_POST['e_name'][$i], $_POST['e_email'][$i], $_POST['e_mobile'][$i], $_POST['e_designation'][$i]);
                }
            }
        }
    }

    redir("renewal_leads_admin.php?update=success", true);
}

?>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">
                                    <small class="text-muted">Home > Add Lead</small>
                                    <h4 class="font-size-14 m-0 mt-1">Add Lead</h4>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <form method="post" action="#" class="form-horizontal" enctype="multipart/form-data">
                                <div data-simplebar class="add_lead">
                                    <h5 class="card-subtitle">Reseller Info</h5>
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label class="control-label">Assigned to Partner<span class="text-danger">*</span></label>
                                            <?php $res = db_query("select * from partners where id <> 45"); ?>
                                            <select placeholder="" name="partner" id="partner" class="form-control" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <?php while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($r_name == $row['name']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                <?php } ?>
                                            </select>


                                        </div>
                                        <!--/span-->
                                        <div class="col-lg-4 mb-3">
                                        <?php if ($allign_to) {
                                                $query = db_query("SELECT * FROM users WHERE team_id = " . $team_id . "  and status='Active'  ORDER BY name ASC");

                                                $sub_industry = getSingleresult("select name from users where id='" . $_REQUEST['eid'] . "'");
                                                $rowCount = mysqli_num_rows($query);

                                                if ($rowCount > 0) { ?>

                                                    <label class="control-label">Submitted By<span class="text-danger">*</span></label>
                                                    <select name="users" class="form-control" id="subind">
                                                        <option value="">Select Submitted By</option>
                                                        <?php
                                                        while ($row = db_fetch_array($query)) { ?>
                                                            <option value="<?= $row['id'] ?>" <?php if ($row['id'] == $allign_to) {
                                                            echo "selected";} ?>> <?= $row['name'] ?></option>

                                                        <?php  } ?>
                                                    </select>
                                            <?php }
                                            } else {
                                                $query = db_query("SELECT * FROM users WHERE team_id = " . $team_id . " and status='Active'  ORDER BY name ASC");
                                                //Count total number of rows
                                                $rowCount = mysqli_num_rows($query);
                                                if ($rowCount > 0) {
                                                    echo '
                                                <label class="example-text-input">Align to<span class="text-danger">*</span></label>
                                                <select name="users" class="form-control" required >
                                                <option value="" >Select Submitted By</option>';
                                                    while ($row = db_fetch_array($query)) {
                                                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                                    }
                                                    echo '</select>';
                                                }
                                            } ?>
                                        </div>
                                            
                                       

                                        <div class="col-lg-4 mb-3">

                                            <label class="control-label">Caller<span class="text-danger">*</span></label>

                                            <select name="caller" id="caller" class="form-control " required data-validation-required-message="This field is required">
                                                <option value="">Select Caller</option>
                                                <?php $r_caller = db_query("select callers.* from callers left join users on users.id=callers.user_id where (users.user_type='RCLR' OR users.user_type='RENEWAL TL') and users.status='Active'");
                                                while ($row = db_fetch_array($r_caller)) { ?>
                                                    <option <?= (($caller == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                <?php } ?>
                                            </select>

                                        </div>
                                    </div>
                                    <!--/row-->

                                    <!--/row-->

                                    <h5 class="card-subtitle">Lead Information</h5>
                                    <div class="row">


                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">Company Name<span class="text-danger">*</span></label>

                                            <input type="text" name="company_name" value="<?= $company_name ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">

                                        </div>

                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">Parent Company</label>

                                            <input type="text" name="parent_company" value="<?= $parent_company ?>" class="form-control" placeholder="">

                                        </div>

                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">Industry<span class="text-danger">*</span></label>

                                            <?php $res = db_query("select * from industry order by name ASC");

                                            //print_r($res); die;

                                            ?>
                                            <select name="industry" id="industry" class="form-control" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <?php while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($industry == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                <?php } ?>
                                            </select>

                                        </div>
                                        <div class="col-lg-3 mb-3" id="sub_industry">

                                            <?php $sub_industry = getSingleresult("select sub_industry from orders where id='" . $_REQUEST['eid'] . "'");
                                             
                                            if ($sub_industry) {
                                                $query = db_query("SELECT * FROM sub_industry WHERE industry_id = " . $industry . "  ORDER BY name ASC");
                                                
                                                $rowCount = mysqli_num_rows($query);

                                                if ($rowCount > 0) { ?>

                                                    <label class="control-label">Sub Industry<span class="text-danger">*</span></label>
                                                    <select name="sub_industry" class="form-control" id="subind">
                                                        <option value="">Select Sub Industry</option>
                                                        <?php
                                                        while ($row = db_fetch_array($query)) { ?>
                                                            <option value="<?= $row['id'] ?>" <?= (($sub_industry==$row['id'])?'selected':'') ?>> <?= $row['name'] ?></option>

                                                        <?php  } ?>
                                                    </select>
                                            <?php }
                                            } else {
                                                
                                                $query = db_query("SELECT * FROM sub_industry WHERE industry_id = " . $industry . "  ORDER BY name ASC");
                                                //Count total number of rows
                                                $rowCount = mysqli_num_rows($query);
                                                if ($rowCount > 0) { ?>
                                                <label class="example-text-input">Sub Industry<span class="text-danger">*</span></label>
                                                <select name="sub_industry" class="form-control" required  id="subind">
                                                <option value="" >Sub Industry</option>
                                                   <?php while ($row = db_fetch_array($query)) { ?>
                                                    <option value="<?= $row['id'] ?>" <?= (($sub_industry==$row['id'])?'selected':'') ?> > <?= $row['name'] ?> </option>
                                                   <?php } ?>
                                                     </select>
                                               <?php }
                                            } ?>
                                        </div>

                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">Region<span class="text-danger">*</span></label>

                                            <select name="region" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <?php $res = db_query("select * from region where status=1");
                                                while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($region == $row['region']) ? 'selected' : '') ?> value="<?= $row['region'] ?>"><?= $row['region'] ?></option>
                                                <?php } ?>

                                            </select>
                                        </div>

                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">Address<span class="text-danger">*</span></label>

                                            <textarea name="address" autocomplete="of" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required"><?= $address ?></textarea>


                                        </div>

                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">State<span class="text-danger">*</span></label>

                                            <?php $res = db_query("select * from states"); ?>
                                            <select name="state" id="state" class="form-control" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <?php while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($state == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                <?php } ?>
                                            </select>

                                        </div>

                                        <!--/span-->
                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">Pin Code<span class="text-danger">*</span></label>

                                            <input type="number" min="0" autocomplete="of" name="pincode" value="<?= $pincode ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required" />

                                        </div>
                                        <!--/span-->


                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">City<span class="text-danger">*</span></label>

                                            <input type="text" name="city" value="<?= $city ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required" />

                                        </div>

                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">Country<span class="text-danger">*</span></label>

                                            <input type="text" name="country" value="India" class="form-control" placeholder="" required readonly data-validation-required-message="This field is required">

                                        </div>
                                    </div>


                                    <!--/row-->
                                    <h5 class="card-subtitle">Decision Maker/Proprietor/Director/End User Details</h5>
                                    <div class="row">
                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">Full Name<span class="text-danger">*</span></label>

                                            <input name="eu_name" type="text" value="<?= $eu_name ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                        </div>
                                        <!--/span-->
                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">Email<span class="text-danger">*</span></label>

                                            <input value="<?= $eu_email ?>" name="eu_email" type="email" required data-validation-required-message="This field is required" class="form-control" placeholder="">

                                        </div>

                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">Mobile<span class="text-danger">*</span></label>

                                            <input type="text" name="eu_mobile" value="<?= $eu_mobile ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">

                                        </div>
                                        <!--/span-->
                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">Designation</label>

                                            <input type="text" name="eu_designation" value="<?= $eu_designation ?>" class="form-control" placeholder="" />

                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 mt-3">

                                            <button type="button" name="add" id="add" class="btn btn-primary  mt-2">Add</button>

                                        </div>
                                        <!--/span-->
                                    </div>
                                    <?php $query = db_query("select * from tbl_lead_contact where lead_id=" . $_REQUEST['eid']);
                                $count = mysqli_num_rows($query);
                                if ($count > 0) {
                                    $i = 1;
                                    while ($row = db_fetch_array($query)) { ?>

                                        <div id="row<?= $i ?>">

                                            <div class="form-group row">
                                                <div class="col-md-2">

                                                    <label class="control-label">Full Name<span class="text-danger">*</span></label>

                                                    <input name="e_name[]" id="row<?= $i ?>" type="text" value="<?= $row['eu_name'] ?>" class="form-control"  required="required" />


                                                </div>
                                                <!--/span-->
                                                <div class="col-md-2">

                                                    <label class="control-label">Email<span class="text-danger">*</span></label>

                                                    <input value="<?= $row['eu_email'] ?>" id="row<?= $i ?>" name="e_email[]" type="email" class="form-control" required="required" />


                                                </div>
                                                <div class="col-md-2">

                                                    <label class="control-label">Mobile<span class="text-danger">*</span></label>

                                                    <input type="number" min="0" name="e_mobile[]" id="row<?= $i ?>" value="<?= $row['eu_mobile'] ?>" class="form-control" required="required" />

                                                </div>
                                                <div class="col-md-2">

                                                    <label class="control-label">Designation</label>

                                                    <input type="text" id="row<?= $i ?>" name="e_designation[]" value="<?= $row['eu_designation'] ?>" class="form-control" />
                                                </div>
                                                <div class="col-md-2"><button style="width:50px;margin-top:27px;" type="button" name="remove" id="<?= $i ?>" class="btn btn-danger btn_remove form-control">X</button></div>
                                            </div>
                                        </div>
                                <?php $i++;
                                    }
                                } ?>
                                <input type="hidden" class="add_btn" value="<?= $i ?>">
                                <div id="dynamic_field">
                                </div>

                                    <h5 class="card-subtitle">Lead Information</h5>
                                    <div class="row">
                                        <div class="col-lg-3 mb-3">

                                            <label for="example-color-input" class="control-label">Quantity<span class="text-danger">*</span></label><br>

                                            <input type="text" name="quantity" id="range_qty" value="<?= ($quantity ? $quantity : 1) ?>">
                                        </div>

                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">License Number<span class="text-danger">*</span></label>

                                            <input type="text" name="license_number" value="<?= $license_key ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required" />

                                        </div>

                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">License End Date<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" readonly required="required" name="license_end_date" id="datepicker-close-date" class="form-control" value="<?= $license_end_date ?>" data-validation-required-message="This field is required" />
                                                <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label class="control-label">Attachment <br>(Max: 4MB)<span class="text-danger"></span></label>

                                            <input type="file" name="user_attachment" class="form-control" />
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label class="control-label">Closed Date<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" readonly required="required" name="close_date" id="datepicker-close-date1" class="form-control" value="<?= $partner_close_date ?>" data-validation-required-message="This field is required" />
                                                <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="button-items">
                                    <button type="submit" name="lead_submit" class="btn btn-primary mt-2" style="margin-bottom:20px">Submit</button>
                                    <button type="button" onclick="javascript:history.go(-1)" class="btn btn-danger mt-2" style="margin-bottom:20px">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> <!-- end col -->

            </div> <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
</div>

</div>
</div>


<?php include('includes/footer.php') ?>
<script>
    $(document).ready(function() {
        $('#industry').on('change', function() {
            //alert("hi");
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    type: 'POST',
                    url: 'ajaxindustry.php',
                    data: 'industry_id=' + stateID,
                    success: function(html) {
                        //alert(html);
                        $('#sub_industry').html(html);
                    }
                });
            }
        });
    });
</script>


<script>
    $(document).ready(function() {
        var wfheight = $(window).height();
        $('.add_lead').height(wfheight - 280);
    });

    jQuery("#search_toogle").click(function() {
        jQuery(".search_form").toggle("fast");
    });
    var wfheight = $(window).height();
    $('.fixed-table-body').height(wfheight - 195);
    $('.fixed-table-body').slimScroll({
        color: '#00f',
        size: '10px',
        height: 'auto',
    });

    $(document).ready(function() {
        $('#partner').on('change', function() {
            //alert("hi");
            var partnerID = $(this).val();
            if (partnerID) {
                $.ajax({
                    type: 'POST',
                    url: 'ajaxusers.php',
                    data: 'partner_id=' + partnerID,
                    success: function(html) {
                        //alert(html);
                        $('#users').html(html);
                    }
                });
            }
        });
    });

    $(function() {
        $('#datepicker-close-date').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '-3d',
            autoclose: !0
        });
    });
    $(function() {
        $('#datepicker-close-date1').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '-3d',
            autoclose: !0
        });
    });
</script>

<script>
    $("#range_qty").ionRangeSlider({
        skin: "flat",
        min: 1,
    })


        $(document).ready(function() {
            //var i = 1;
            var add_btn = $('.add_btn').val();
            //alert(add_btn);
            $('#add').click(function() {

                $('#dynamic_field').append('<div id="row' + add_btn + '"><div class="form-group row"><div class="col-md-2"><label class="control-label">Full Name</label><input name="e_name[]" id="row' + add_btn + '" value="" type="text" class="form-control" placeholder="" required></div><div class="col-md-2"><label class="control-label">Email</label><input value="" name="e_email[]" type="email" id="row' + add_btn + '" class="form-control" placeholder="" required></div><div class="col-md-2"><label class="control-label">Mobile</label><input type="number" min="0" name="e_mobile[]" value="" id="row' + add_btn + '" class="form-control" required></div><div class="col-md-2"><label class="control-label">Designation</label><input type="text" value="" name="e_designation[]" id="row' + add_btn + '" class="form-control" /></div><div class="col-md-2"><button style="width:50px;margin-top:27px;" type="button" name="remove" id="' + add_btn + '" class="btn btn-danger btn_remove form-control">X</button></div></div></div>');
                add_btn++;
            });
            $(document).on('click', '.btn_remove', function() {
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });
        });
    </script>