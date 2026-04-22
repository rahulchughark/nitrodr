<?php include('includes/header.php');
@extract($_GET);

$_GET['lead'] = intval($_GET['lead']);
$_GET['type'] = intval($_GET['type']);

if ($_GET['lead']) {
    $select_query = selectLeadPartner('tbl_product_pivot', $_GET['type']);
    $row = db_fetch_array($select_query);
}

if ($_REQUEST['rid']) {
    $sql = copyRawNew('raw_leads', $_REQUEST['rid']);
    $copy_data = db_fetch_array($sql);

    @extract($copy_data);
}

if ($_POST['r_name']) {
    cross_login_protect($_POST['user_id']);

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

    if (!$_POST['lead_type']) {
        $_POST['lead_type'] = 'LC';
    }
    if (!$_POST['account_visited']) {
        $_POST['account_visited'] = 'Yes';
    }
    if (!$_POST['license_type']) {
        $_POST['license_type'] = 'Commercial';
    }

    $user_image = !empty($_FILES["user_attachment"]["name"]) ? $target_file : $user_attachement;

    $count_today = getSingleresult("select count(*) from orders where date(created_date)='" . date('Y-m-d') . "' and created_by='" . $_SESSION['user_id'] . "' ");
    $cont_final = 10 - $count_today;
    if ($cont_final > 0) {
        $campaign_type = $_POST['campaign_type'] ? $_POST['campaign_type'] : 0;
        $runrate_key = ($_POST['quantity']<=8)?'Runrate':'Key';

        $res = insertLeadData('orders', $_POST['source'], $_POST['lead_type'], $_POST['company_name'], $_POST['parent_company'], $_POST['landline'], $_POST['region'], $_POST['industry'], $_POST['sub_industry'], $_POST['address'], $_POST['pincode'], $_POST['state'], $_POST['city'], $_POST['country'], $_POST['eu_name'], $_POST['eu_email'], $_POST['eu_landline'], $_POST['department'], $_POST['eu_mobile'], $_POST['eu_designation'], $_POST['eu_role'], $_POST['visit_remarks'], $_POST['quantity'], $_SESSION['user_id'], $_SESSION['team_id'], $user_image, $_POST['partner_close_date'], $campaign_type, $_POST['account_visited'], $_POST['confirmation_from'], $_POST['license_type'], $_POST['os'], $_POST['version'], $runrate_key, getSingleresult("select name from partners where id='" . $_SESSION['team_id'] . "'"), $_SESSION['email'], $_SESSION['name'], $_POST['association_name'],$_POST['validation_type']);

        $lead_id = get_insert_id();

        if ($res) {
            $activity_log = db_query("insert into activity_log (pid,description,activity_type,call_subject,added_by,data_ref) values ('" . $lead_id . "','" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "','Lead','" . $_POST['call_subject'] . "','" . $_SESSION['user_id'] . "',1)");

            $activity_type_update = db_query("update activity_log set pid='" . $lead_id . "',activity_type='Lead' where pid='" . $_REQUEST['rid'] . "' and activity_type='Raw'");

            $delete_raw = db_query("delete from raw_leads where id=" . $_REQUEST['rid']);

            if ($_POST['e_name']) {
                $number = count($_POST["e_name"]);

                for ($i = 0; $i < $number; $i++) {
                    $query =  insertLeadContact('tbl_lead_contact', $lead_id, $_POST['e_name'][$i], $_POST['e_email'][$i], $_POST['e_mobile'][$i], $_POST['e_designation'][$i]);
                }
            }

            if ($row['product_name']) {

                $select_query = db_query("select * from tbl_lead_product where lead_id=" . $lead_id);

                if (mysqli_num_rows($select_query) == 0) {

                    $query =  db_query("INSERT INTO `tbl_lead_product`(`lead_id`, `product_id`,`product_type_id`,`form_id`,`created_at`) VALUES ('" . $lead_id . "' ,'" . $_GET['lead'] . "' ,'" . $_GET['type'] . "','" . $row['form_id'] . "',now())");
                }
            } elseif ($product_name) {

                $select_query = db_query("select * from tbl_lead_product where lead_id=" . $lead_id);

                if (mysqli_num_rows($select_query) == 0) {
                    $query =  db_query("INSERT INTO `tbl_lead_product`(`lead_id`, `product_id`,`product_type_id`,`form_id`,`created_at`) VALUES ('" . $lead_id . "' ,'" . $product_id . "' ,'" . $product_type_id . "','" . $form_id . "',now())");
                }
            }

            $points_date = week_range(date('Y-m-d'));

            $add_point = db_query("insert into user_points (stage_id,stage_name,point,week_number,date_from,date_to,no_of_seats,user_id,lead_id) values (1000,'New',1,'" . date('W') . "','$points_date[0]','$points_date[1]','" . $_POST['quantity'] . "','" . $_SESSION['user_id'] . "',$lead_id) ");

            $sm_email = getSingleresult("select users.email as email from users join partners on partners.sm_user=users.id where partners.id='" . $_SESSION['team_id'] . "'");

           // $mail->AddAddress("prashant.dongrikar@arkinfo.in", "Prashant");
            $mail->AddAddress("kailash.bhurke@arkinfo.in");
            $mail->AddCC("pradnya.chaukekar@arkinfo.in"); 

            $mail->Subject = "New Lead Added to DR Portal from " . $_POST['r_name'] . " (" . $_POST['r_user'] . ")";
            $mail->Body    = "Hi,<br><br> There is new lead added to SketchUp DR Portal with details as below:-<br><br>
        <ul>
        <li><b>Partner Name</b> : " . $_POST['r_name'] . " </li>
        <li><b>Name</b> : " . $_POST['r_name'] . " </li>
        <li><b>Email</b> : " . $_POST['r_email'] . " </li>
        <li><b>Source</b> : " . $_POST['source'] . " </li>
        <li><b>Lead Type</b> : " . $_POST['lead_type'] . " </li>
        <li><b>Company Name</b> : " . $_POST['company_name'] . " </li>
        <li><b>Product Name</b> : " . ($product_name ? $product_name : $row['product_name']) . " </li>
        <li><b>Product Type</b> : " . ($product_type ? $product_type : $row['product_type']) . " </li>
        <li><b>Address</b> : " . htmlspecialchars($_POST['address'], ENT_QUOTES) . " </li>
        <li><b>Mobile</b> : " . $_POST['eu_mobile'] . " </li>
        <li><b>Email</b> : " . $_POST['eu_email'] . " </li>
        <li><b>License Type</b> : " . $_POST['license_type'] . " </li>
        <li><b>Quantity</b> : " . $_POST['quantity'] . " </li>
        <li><b>Projected Close Date</b> : " . $_POST['partner_close_date'] . " </li>
        <li><b>Call Subject</b> : " . $_POST['call_subject'] . " </li>
        <li><b>Remarks</b> : " . $_POST['remarks'] . " </li>
        </ul><br>
        Thanks,<br>
        SketchUp DR Portal

        ";
            $mail->AddAttachment("$target_file");
            $mail->Send();

            if($product_type_id==3){
                redir("education_leads_partner.php?add=success&cnt=" . $cont_final . "&lt=" . $_POST['lead_type'], true);
            }else{
                redir("orders.php?add=success&cnt=" . $cont_final . "&lt=" . $_POST['lead_type'], true);
            }
            
        }
    } else {

        if($product_type_id==3){
        redir("education_leads_partner.php?fail=ext", true);
        }else{
            redir("orders.php?fail=ext", true);

        }
    }
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
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/ict-logo.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home > Add Lead</small>
                                    <h4 class="font-size-14 m-0 mt-1">Add Lead</h4>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <form method="post" action="#" class="form-horizontal" enctype="multipart/form-data" id="form_activity">
                                <input type="hidden" value="<?= $_SESSION['user_id'] ?>" name="user_id">
                                <div class="add_lead">

                                    <h5 class="card-subtitle">Add Lead:- &nbsp;&nbsp;&nbsp;Product Name:<?= ($product_name ? $product_name : $row['product_name']) ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Product type:<?= ($product_type ? $product_type : $row['product_type']) ?></h5>


                                    <div style="display:none;">
                                        <div class="col-md-4">
                                            <input name="r_name" type="text" readonly value="<?= getSingleresult("select name from partners where id='" . $_SESSION['team_id'] . "'") ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">

                                            <input readonly value="<?= $_SESSION['email'] ?>" name="r_email" type="email" required data-validation-required-message="This field is required" class="form-control form-control" placeholder="">

                                            <input name="r_user" readonly value="<?= $_SESSION['name'] ?>" type="text" class="form-control" placeholder="" required data-validation-required-message="This field is required">

                                        </div>

                                    </div>



                                    <h5 class="card-subtitle">Customer Information</h5>

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Lead Source</label>

                                            <select name="source" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <?php $res = db_query("select * from lead_source where status=1");
                                                while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($source == $row['lead_source']) ? 'selected' : '') ?> value="<?= $row['lead_source'] ?>"><?= $row['lead_source'] ?></option>
                                                <?php } ?>


                                            </select>

                                        </div>
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Lead Type</label>

                                            <div class="">
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">

                                                        <input name="lead_type" value="LC" <?php echo ($lead_type == 'LC') ?  "checked" : "";  ?> type="radio" required id="customRadio7" class="lead custom-control-input">
                                                        <label for="customRadio7" class="custom-control-label">LC</label>
                                                    </div>
                                                </div>
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">
                                                        <input name="lead_type" value="BD" <?php echo ($lead_type == 'BD') ?  "checked" : "";  ?> type="radio" required id="customRadio8" class="lead custom-control-input">
                                                        <label for="customRadio8" class="custom-control-label">BD</label>
                                                    </div>
                                                </div>
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">
                                                        <input name="lead_type" value="Incoming" <?php echo ($lead_type == 'Incoming') ?  "checked" : "";  ?> type="radio" required id="customRadio9" class="lead custom-control-input">
                                                        <label class="custom-control-label" for="customRadio9">Incoming</label>


                                                    </div>
                                                </div>


                                            </div>


                                        </div>

                                        <div class="col-lg-4 mb-3" id="validation_type">
                                            <?php if ($validation_type) {                                               
                                                echo '  
                                                <label class="control-label">Type of validation<span class="text-danger">*</span></label>
                                                <select name="validation_type" class="form-control" id="profiling_type" required data-validation-required-message="This field is required">
                                                <option value="">Type of validation</option>'; ?>
                                                <option value="profiling_validation">Validation through call (Profiling)</option>
                                                <option value="emailer_validation">Validation through emailer</option>
                                            <?php echo '</select>'; } ?>
                                            
                                        </div>

                                        <div id="attachment_user" style="display:none">
                                        <div class="col-lg-4 mb-3">
                                            <label for="example-text-input">Attachment<span class="text-danger">*</span><br>(Max: 4MB)</label>
                                        
                                        <input type="file" name="emailer_attachment" class="btn btn-default" value="<?= $user_attachement ?>" required aria-invalid="false" title="Emailer response should be attached in word file" />
                                            <?php if ($user_attachement) { ?>
                                                <img src="<?= $user_attachement ?>" style="width:50px; height:50px" />
                                        <?php } ?>
</div>
                                        </div> 

                                            <div class="col-lg-4 mb-3" id="attachment">
                                            <label for="example-text-input">Attachment(Max: 4MB)</label>

                                            <input type="file" name="user_attachment" class="btn btn-default" value="<?= $user_attachement ?>" aria-invalid="false" />
                                            <?php if ($user_attachement) { ?>
                                                <img src="<?= $user_attachement ?>" style="width:50px; height:50px" />
                                        <?php } ?>
                                        </div> 


                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input">Company Name<span class="text-danger">*</span></label>

                                            <input type="text" value="<?= $company_name ?>" name="company_name" class="form-control" placeholder="Company Name" required id="example-text-input" data-validation-required-message="This field is required">

                                        </div>
                                  
                                        <div class="col-lg-4 mb-3">

                                            <label class="example-text-input">Parent Company<span class="text-danger"></span></label>

                                            <input type="text" value="<?= $parent_company ?>" name="parent_company" value="" class="form-control" placeholder="Parent Company" id="example-text-input">

                                        </div>
                                        <?php if ($product_type_id == 1 || $product_type_id == 2) { ?>
                                            <div class="col-lg-4 mb-3">

                                                <label class="example-text-input">Association Name<span class="text-danger"></span></label>

                                                <input type="text" value="<?= $association_name ?>" name="association_name" value="" class="form-control" placeholder="Association Name" id="example-text-input">

                                            </div>
                                        <?php } ?>
                                        <div class="col-lg-4 mb-3">
                                            <label for="example-text-input">Landline Number</label>

                                            <input type="number" min="0" name="landline" value="<?= $landline ?>" id="example-text-input" class="form-control" placeholder="Landline Number">

                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input">Region<span class="text-danger">*</span></label>

                                            <select name="region" class="form-control" placeholder="" required id="example-text-input" data-validation-required-message="This field is required" aria-invalid="false">
                                                <option value="">---Select---</option>
                                                <?php $res = db_query("select * from region where status=1");
                                                while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($region == $row['region']) ? 'selected' : '') ?> value="<?= $row['region'] ?>"><?= $row['region'] ?></option>
                                                <?php } ?>

                                            </select>

                                            <!--/span-->
                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input">Industry<span class="text-danger">*</span></label>

                                            <?php $res = db_query("select * from industry order by name ASC");
                                            ?>
                                            <select name="industry" id="industry" class="form-control" required data-validation-required-message="This field is required" aria-invalid="false">
                                                <option value="">---Select---</option>
                                                <?php while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($row['id'] == $industry) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                <?php } ?>
                                            </select>

                                        </div>



                                        <div class="col-lg-4 mb-3" id="sub_industry">
                                            <?php if ($sub_industry) {
                                                $query = db_query("SELECT * FROM sub_industry WHERE industry_id = " . $industry . "  ORDER BY name ASC");
                                                $rowCount = mysqli_num_rows($query);
                                                if ($rowCount > 0) {
                                                    echo '  
                                                    <label for="example-text-input">Sub Industry<span class="text-danger">*</span></label>
                                                    <select name="sub_industry" class="form-control" required data-validation-required-message="This field is required" id="subind">';
                                                    while ($row = db_fetch_array($query)) {
                                                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                                    }
                                                    echo '</select>';
                                                }
                                            } ?>
                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input">Country<span class="text-danger">*</span></label>

                                            <input type="text" name="country" value="India" class="form-control" placeholder="Country" required id="example-text-input" data-validation-required-message="This field is required">

                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">State<span class="text-danger">*</span></label>

                                            <?php $res = db_query("select * from states");
                                            ?>
                                            <select name="state" id="state" class="form-control" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <?php while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($state == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                <?php } ?>
                                            </select>

                                        </div>


                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">City<span class="text-danger">*</span></label>

                                            <input type="text" value="<?= $city ?>" name="city" class="form-control" placeholder="City" required id="example-text-input" data-validation-required-message="This field is required" pattern="[A-Za-z\s]+" />

                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input">Pin Code<span class="text-danger">*</span></label>

                                            <input type="number" value="<?= $pincode ?>" min="0" name="pincode" id="example-text-input" class="form-control" placeholder="Pin Code" required data-validation-required-message="This field is required" />

                                        </div>


                                        <div class="col-lg-4 mb-3">
                                            <label for="validationTooltip05">Address<span class="text-danger">*</span></label>

                                            <textarea name="address" rows="5" class="form-control" id="exampleFormControlTextarea1" placeholder="" required data-validation-required-message="This field is required"><?= $address ?></textarea>

                                        </div>

                                    </div>

                                    <h5 class="card-subtitle">Decision Maker/Proprietor/Director/End User Details
                                    </h5>
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Full Name<span class="text-danger">*</span></label>

                                            <input name="eu_name" value="<?= $eu_name ?>" type="text" class="form-control" placeholder="Full Name" required id="example-text-input" data-validation-required-message="This field is required">


                                        </div>


                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Email<span class="text-danger">*</span></label>

                                            <input value="<?= $eu_email ?>" name="eu_email" type="email" id="example-text-input" class="form-control" placeholder="Email" required data-validation-required-message="This field is required">


                                        </div>


                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Mobile<span class="text-danger">*</span></label>

                                            <input type="number" min="0" id="example-text-input" name="eu_mobile" value="<?= $eu_mobile ?>" class="form-control" placeholder="Mobile" required data-validation-required-message="This field is required">

                                        </div>



                                    </div>



                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Landline Number<span class="text-danger"></span></label>

                                            <input type="number" value="<?= $eu_landline ?>" min="0" name="eu_landline" autocomplete="of" class="form-control" id="example-text-input" placeholder="Landline Number">

                                        </div>


                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Department<span class="text-danger"></span></label>

                                            <input type="text" name="department" value="<?= $department ?>" class="form-control" id="example-text-input" placeholder="Department">

                                        </div>


                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Designation<span class="text-danger">*</span></label>

                                            <input type="text" value="<?= $eu_designation ?>" name="eu_designation" class="form-control" id="example-text-input" placeholder="Designation" required data-validation-required-message="This field is required" />

                                            <!--/span-->
                                        </div>



                                    </div>



                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Role<span class="text-danger"></span></label>

                                            <select name="eu_role" class="form-control" placeholder="" aria-invalid="false">
                                                <option value="">---Select---</option>
                                                <option <?= (($eu_role == 'User') ? 'selected' : '') ?> value="User">User</option>
                                                <option <?= (($eu_role == 'Economy Buyer') ? 'selected' : '') ?> value="Economy Buyer">Economy Buyer</option>
                                                <option <?= (($eu_role == 'Tech. Buyer') ? 'selected' : '') ?> value="Tech. Buyer">Tech. Buyer</option>
                                                <option <?= (($eu_role == 'Decision Maker') ? 'selected' : '') ?> value="Decision Maker">Decision Maker</option>


                                            </select>

                                        </div>


                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Account Visited<span class="text-danger">*</span></label><br>
                                            <div class="">
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">

                                                        <input name="account_visited" value="Yes" <?php echo ($account_visited == 'Yes') ?  "checked" : "";  ?> type="radio" required id="customRadio3" class="custom-control-input">
                                                        <label class="custom-control-label" for="customRadio3">Yes</label>
                                                    </div>
                                                </div>
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">
                                                        <input name="account_visited" value="No" <?php echo ($account_visited == 'No') ?  "checked" : "";  ?> type="radio" required id="customRadio2" class="custom-control-input">
                                                        <label class="custom-control-label" for="customRadio2">No</label>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Visit/Profiling Remarks<span class="text-danger">*</span></label>

                                            <textarea name="visit_remarks" value="" class="form-control" id="example-text-input" placeholder="Visit/Profiling Remarks" required data-validation-required-message="This field is required"><?= $visit_remarks ?></textarea>


                                        </div> -->

                                        <!--/span-->
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Usage Confirmation Received from<span class="text-danger"></span></label>

                                            <select name="confirmation_from" class="form-control" id="example-text-input" placeholder="">
                                                <option value="">---Select---</option>
                                                <option <?= (($confirmation_from == 'Graphic Designer') ? 'selected' : '') ?> value="Graphic Designer">Graphic Designer</option>
                                                <option <?= (($confirmation_from == 'IT Manager') ? 'selected' : '') ?> value="IT Manager">IT Manager</option>
                                                <option <?= (($confirmation_from == 'IT Executive') ? 'selected' : '') ?> value="IT Executive">IT Executive</option>
                                                <option <?= (($confirmation_from == 'Reception') ? 'selected' : '') ?> value="Reception">Reception</option>
                                                <option <?= (($confirmation_from == 'Employee') ? 'selected' : '') ?> value="Employee">Employee</option>
                                                <option <?= (($confirmation_from == 'Customer Reference') ? 'selected' : '') ?> value="Customer Reference">Customer Reference</option>
                                                <option <?= (($confirmation_from == 'Other') ? 'selected' : '') ?> value="Other">Other</option>



                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 mt-3">

                                            <button type="button" name="add" id="add" class="btn btn-primary  mt-2">Add</button>

                                        </div>
                                        <!--/span-->
                                    </div>
                                    <?php if ($_REQUEST['cid']) {
                                        $query = db_query("select * from tbl_lead_contact where lead_id=" . $_REQUEST['cid']);
                                        $count = mysqli_num_rows($query);
                                        if ($count > 0) {
                                            $i = 1;
                                            while ($row = db_fetch_array($query)) { ?>

                                                <div id="row<?= $i ?>">
                                                    <div class="form-group row d-flex align-items-end">
                                                        <div class="col-md-2">

                                                            <label class="control-label">Full Name<span class="text-danger">*</span></label>

                                                            <input name="e_name[]" type="text" value="<?= $row['eu_name'] ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">


                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-2">

                                                            <label class="control-label">Email<span class="text-danger">*</span></label>

                                                            <input value="<?= $row['eu_email'] ?>" name="e_email[]" type="email" required data-validation-required-message="This field is required" class="form-control form-control" placeholder="">


                                                        </div>
                                                        <div class="col-md-2">

                                                            <label class="control-label">Mobile<span class="text-danger">*</span></label>

                                                            <input type="number" min="0" name="e_mobile[]" value="<?= $row['eu_mobile'] ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">

                                                        </div>
                                                        <div class="col-md-2">

                                                            <label class="control-label">Designation<span class="text-danger">*</span></label>

                                                            <input type="text" name="e_designation[]" value="<?= $row['eu_designation'] ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <span data-repeater-delete="" id=<?= $i ?> class="btn btn-danger btn-sm btn_remove">
                                                                <span class="fa fa-times mr-1"></span> Delete
                                                            </span>

                                                            <!--<button style="width:50px;margin-top:18px;" type="button" name="remove" id=<//?= $i ?> class="btn btn-danger btn_remove form-control">X</button>-->

                                                        </div>
                                                    </div>
                                                </div>
                                        <?php $i++;
                                            }
                                        } ?>
                                        <input type="hidden" class="add_btn" value="<?= $i ?>">
                                    <?php } ?>
                                    <div id="dynamic_field">
                                    </div>
                                    <h5 class="card-subtitle">Lead Information</h5>

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Type of License<span class="text-danger">*</span></label>
                                            <div class="">
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">
                                                        <?php $select_query = selectLeadPartner('tbl_product_pivot', $_GET['type']);
                                                        $row = db_fetch_array($select_query); ?>


                                                        <?php //if ($_REQUEST['rid']) { 
                                                        ?>
                                                        <!-- 
                                                            <input <?= (($license_type == 'Commercial') ? 'checked' : '') ?> name="license_type" value="Commercial" type="radio" required id="customRadio4" onchange="valueChanged()">
                                                            <label for="customRadio4">Commercial</label>
                                                            <input name="license_type" <?= (($license_type == 'Upgrade') ? 'checked' : '') ?> value="Upgrade" type="radio" required id="customRadio5" onchange="valueChanged()">
                                                            <label for="customRadio5">Upgrade</label>
                                                            <input name="license_type" <?= (($license_type == 'Education') ? 'checked' : '') ?> value="Education" type="radio" required id="customRadio6" onchange="valueChanged()">
                                                            <label for="customRadio6">Education</label> -->
                                                        <?php  //} else { 
                                                        ?>

                                                        <input checked name="license_type" value="<?= ($license_type ? $license_type : $row['license_type']) ?>" type="radio" required id="customRadio4">
                                                        <label for="customRadio"><?= ($license_type ? $license_type : $row['license_type']) ?></label>
                                                        <?php // } 
                                                        ?>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">OS<span class="text-danger">*</span></label><br>
                                            <div class="">
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">

                                                        <input name="os" value="Windows" <?php echo ($os == 'Windows') ?  "checked" : "";  ?> type="radio" required id="customRadio5" class="custom-control-input">
                                                        <label class="custom-control-label" for="customRadio5">Windows</label>
                                                    </div>
                                                </div>
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">
                                                        <input name="os" value="Mac" <?php echo ($os == 'Mac') ?  "checked" : "";  ?> type="radio" required id="customRadio6" class="custom-control-input">
                                                        <label class="custom-control-label" for="customRadio6">Mac</label>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input">OS Version<span class="text-danger"></span></label>

                                            <input type="text" name="version" value="<?= $version ?>" class="form-control" id="example-text-input" placeholder="OS Version" />

                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Runrate/Key<span class="text-danger">*</span></label>
                                            <div class="">
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">

                                                        <input name="runrate_key" value="Key" <?php echo ($runrate_key == 'Key') ?  "checked" : "";  ?> type="radio" required id="customRadio1" class="custom-control-input">
                                                        <label class="custom-control-label" for="customRadio1">Key</label>
                                                    </div>
                                                </div>
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">
                                                        <input name="runrate_key" value="Runrate" <?php echo ($runrate_key == 'Runrate') ?  "checked" : "";  ?> type="radio" required id="customRadio10" class="custom-control-input">
                                                        <label class="custom-control-label" for="customRadio10">Runrate</label>

                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Quantity<span class="text-danger">*</span></label><br>
                                            <div class="clearfix"> </div>

                                            <input type="text" name="quantity" id="range_qty" value="<?= ($quantity ? $quantity : 1) ?>">


                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <!-- <span id="ex6CurrentSliderValLabel">Users: <span id="ex6SliderVal"><?= ($quantity ? $quantity : '1') ?></span></span> &nbsp;<input id="ex6" name="quantity" type="text" data-slider-min="1" data-slider-max="50" data-slider-step="1" data-slider-value="<?= ($quantity ? $quantity : '1') ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required" /> -->


                                            <!-- <label for="example-text-input">Attachment <br>(Max: 4MB)<span class="text-danger"></span></label>

                                            <input type="file" name="user_attachment" class="btn btn-default" value="<?= $user_attachement ?>" aria-invalid="false" />
                                            <?php if ($user_attachement) { ?>
                                                <img src="<?= $user_attachement ?>" style="width:50px; height:50px" />
                                            <?php } ?> -->

                                        </div>
                                   
                                   
                                            <div class="col-lg-4 mb-3" id="campaign_show" style="display: none">
                                                <label for="example-text-input">Campaign<span class="text-danger"></span></label>
                                                <select name="campaign_type" class="form-control" id="example-text-input">
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    $campaign_select = campaign_data($product_id);
                                                    while ($row = db_fetch_array($campaign_select)) { ?>
                                                        <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                       
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input">Close Date<span class="text-danger"></span></label>
                                            <div class="input-group">
                                                <input type="text" readonly required="required" name="partner_close_date" id="datepicker-close-date" class="form-control" value="<?= date('Y-m-t') ?>" />
                                                <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <div class="button-items">

                                    <button type="submit" data-toggle="modal" data-target="#myModal" id="form_data" class="btn btn-primary  mt-2">Submit</button>

                                    <button type="button" onclick="javascript:history.go(-1)" class="btn btn-danger mt-2">Cancel</button>

                                </div>
                                <!-- </form> -->
                        </div>


                    </div>
                </div> <!-- end col -->


            </div> <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Add Activity Call
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php $query = db_query("select * from users where id=" . $_SESSION['user_id']);
                $row_data = db_fetch_array($query);
                ?>
                <div class="modal-body">
                    <!-- <form action="#" method="post" class="form p-t-20" > -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input">Call Subject</label>
                                <select required id="call_subject" name="call_subject" class="form-control">
                                    <option value="">---Select---</option>
                                    <?php
                                    $log_query = db_query("select * from activity_log where pid=" . $_REQUEST['rid'] . " and activity_type='Raw' and call_subject='Fresh Call'");

                                    if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'SALES MNGR' || $_SESSION['user_type'] == 'ISS MNGR' || $_SESSION['user_type'] == 'REVIEWER') {

                                        $call_query = db_query("select * from call_subject where 1 order by subject");
                                    } else {
                                        if ($row_data['role'] == 'TC') {
                                            if (mysqli_num_rows($log_query) > 0) {
                                                $call_query = db_query("select * from call_subject where subject not like '%visit%' and subject!='Fresh Call' order by subject");
                                            } else {
                                                $call_query = db_query("select * from call_subject where subject not like '%visit%' order by id asc");
                                            }
                                        } else {
                                            if (mysqli_num_rows($log_query) > 0) {
                                                $call_query = db_query("select * from call_subject where subject!='Fresh Call' order by id asc");
                                            } else {
                                                $call_query = db_query("select * from call_subject where 1 order by id asc");
                                            }
                                        }
                                    }
                                    while ($call_subject = db_fetch_array($call_query)) {  ?>
                                        <option value="<?= $call_subject['subject'] ?>"><?= $call_subject['subject'] ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input">Visit/Profiling Remarks</label>
                                <textarea required id="remarks" value="<?= $goal_data['daily'] ?>" name="remarks" class="form-control" placeholder=""></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <input type="submit" id="save_btn" name="save" value="Save" class="btn btn-primary" />

                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

                    </div>
                    </form>
                </div>


            </div>
        </div>
    </div>

    <?php include('includes/footer.php') ?>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="js/validation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/bootstrap-slider.min.js"></script>

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

        $(document).ready(function() {
        $('.lead').on('change', function() {
                var leadID = $(this).val();
               // alert(leadID);
                if (leadID=='LC') {
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxlead_type.php',
                        data: 'lead_type_id=' + leadID,
                        success: function(html) {
                           // alert(html);
                            $('#validation_type').html(html);     
                            $('#validation_type').show();                       
                        }
                    });
                }else{
                    $('#validation_type').hide();  
                    $("#attachment_user").hide(); 
                    $("#attachment").show();
                    // return false; 
                }
            });
        });
    </script>

    <script>
        $('#form_data').on('click', function(e) {
            e.preventDefault();

        });

        $('#save_btn').on('click', function(e) {
            e.preventDefault();
            var wasSubmitted = false;
            if ($('#form_activity').validate().form()) {
                e.preventDefault();
                if ($('#call_subject').val() != "" && $('#remarks').val() != "") {
                    console.log("validates");
                    if (!wasSubmitted) {
                        wasSubmitted = true;
                        $('#form_activity').submit();
                    }
                    return false;

                } else {
                    swal('Fields cant be empty!!');
                    return false;
                }

            } else {
                swal('Fields cant be empty!!');
                return false;
                console.log("does not validate");
            }

            $('form#form_activity').validate();
        });




        $(document).ready(function() {
            $("#range_qty").ionRangeSlider({
                skin: "flat",
                //type: "double",
                min: 1,
                //max: 100,
                // from:0
            })
        });

        function valueChanged() {

            if ($('#customRadio4').val("Commercial").is(":checked"))
                $("#campaign_show").show();
            else
                $("#campaign_show").hide();
        }

        $(document).ready(function() {

            var wfheight = $(window).height();
            $('.add_lead').height(wfheight - 265);

        });

        $(function() {
            $('#datepicker-close-date').datepicker({
                format: 'yyyy-mm-dd',
                startDate: '-3d',
                autoclose: !0

            });

        });
    </script>

    <script>
        $(document).ready(function() {
            //var i = 1;
            var add_btn = $('.add_btn').val();
            $('#add').click(function() {
                // i++;
                $('#dynamic_field').append('<div id="row' + add_btn + '"><div class="form-group row d-flex align-items-end"><div class="col-lg-2 mb-3"><label class="control-label">Full Name</label><input name="e_name[]" value="" type="text" value="" class="form-control" placeholder=""></div><div class="col-lg-2 mb-3"><label class="control-label">Email</label><input value="" name="e_email[]" type="email" class="form-control" placeholder=""></div><div class="col-lg-2 mb-3"><label class="control-label">Mobile</label><input type="number" min="0" name="e_mobile[]" value="" class="form-control"></div><div class="col-lg-2 mb-3"><label class="control-label">Designation</label><input type="text" value="" name="e_designation[]" class="form-control" /></div><div class="col-sm-1 mb-3"><span data-repeater-delete="" name="remove" id="' + add_btn + '" class="btn btn-danger btn-sm btn_remove"><span class="fa fa-times mr-1"></span>Delete</span></div></div></div>');
                add_btn++;
            });
            $(document).on('click', '.btn_remove', function() {
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });
        });
    </script>