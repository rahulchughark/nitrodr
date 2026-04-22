<?php include('includes/header.php');
if (!$_GET['license_type']) {
    $_GET['license_type'] = 'Commercial';
} ?>

<?php @extract($_GET);

$_GET['lead'] = intval($_GET['lead']);
$_GET['type'] = intval($_GET['type']);

if ($_GET['lead']) {
    $select_query = selectLeadPartner('tbl_product_pivot', $_GET['type']);
    $row = db_fetch_array($select_query);
}

if ($_REQUEST['cid']) {
    $sql = copyLeadNew('orders', $_REQUEST['cid']);
    $copy_data = db_fetch_array($sql);
    foreach ($sql as $value) {
        $exist_IT[] = $value['existing_IT'];
        $appUsage[] = $value['app_usage'];
    }
    //print_r($existing_IT); 
    @extract($copy_data);
}

if ($_REQUEST['rid']) {
    $sql = copyRawNew('raw_leads', $_REQUEST['rid']);
    $copy_data = db_fetch_array($sql);
    foreach ($sql as $value) {
        $exist_IT[] = $value['existing_IT'];
        $appUsage[] = $value['app_usage'];
    }
    @extract($copy_data);
}


if ($_POST['r_name']) {

    cross_login_protect($_SESSION['user_id']);

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

    if (!$_POST['license_type']) {
        $_POST['license_type'] = 'Commercial';
    }


    $count_today = getSingleresult("select count(*) from orders where date(created_date)='" . date('Y-m-d') . "' and created_by='" . $_SESSION['user_id'] . "' ");
    $cont_final = 10 - $count_today;
    //print_r($cont_final);die;
    if ($cont_final > 0) {
        $_POST['campaign_type'] = $_POST['campaign_type'] ? $_POST['campaign_type'] : NULL;
        
        $user_image = !empty($_FILES["user_attachment"]["name"]) ? $target_file : $user_attachement;


        $res = insertLeadDataParallel('orders',getSingleresult("select name from partners where id='" . $_SESSION['team_id'] . "'"), $_SESSION['email'], $_SESSION['name'], $_POST['source'], $_POST['lead_type'], $_POST['company_name'], $_POST['parent_company'], $_POST['landline'], $_POST['region'], $_POST['industry'], $_POST['sub_industry'], $_POST['address'], $_POST['pincode'], $_POST['state'], $_POST['city'], $_POST['country'], $_POST['eu_name'], $_POST['eu_email'], $_POST['eu_landline'], $_POST['department'], $_POST['eu_mobile'], $_POST['eu_designation'], $_POST['eu_role'], $_POST['visit_remarks'], $_POST['quantity'], $_SESSION['user_id'], $_SESSION['team_id'], $user_image, $_POST['partner_close_date'], $_POST['campaign_type']);

        $lead_id = get_insert_id();

        if ($res) {
            $activity_log = db_query("insert into activity_log (pid,description,activity_type,call_subject,added_by) values ('" . $lead_id . "','" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "','Lead','" . $_POST['call_subject'] . "','" . $_SESSION['user_id'] . "')");

            if ($_POST['e_name']) {
                $number = count($_POST["e_name"]);

                for ($i = 0; $i < $number; $i++) {
                    $query =  insertLeadContact('tbl_lead_contact', $lead_id, $_POST['e_name'][$i], $_POST['e_email'][$i], $_POST['e_mobile'][$i], $_POST['e_designation'][$i]);
                }
            }
            if ($row['product_name']) {
                $number = count($row['product_name']);
                $number1 = count($_POST['existing_IT']);
                $number2 = count($_POST['app_usage']);
                if ($number1 > $number2) {
                    for ($i = 0; $i < $number1; $i++) {
                        $query =  db_query("INSERT INTO `tbl_lead_product`(`lead_id`, `product_id`,`product_type_id`,`existing_IT`, `app_usage`,`form_id`,`created_at`) VALUES ('" . $lead_id . "' ,'" . $_GET['lead'] . "' ,'" . $_GET['type'] . "' ,'" . $_POST['existing_IT'][$i] . "','" . $_POST['app_usage'][$i] . "','" . $row['form_id'] . "',now())");
                    }
                } elseif ($number1 < $number2) {
                    for ($i = 0; $i < $number2; $i++) {
                        $query =  db_query("INSERT INTO `tbl_lead_product`(`lead_id`, `product_id`,`product_type_id`,`existing_IT`, `app_usage`,`form_id`,`created_at`) VALUES ('" . $lead_id . "' ,'" . $_GET['lead'] . "' ,'" . $_GET['type'] . "' ,'" . $_POST['existing_IT'][$i] . "','" . $_POST['app_usage'][$i] . "','" . $row['form_id'] . "',now())");
                    }
                } else {
                    for ($i = 0; $i < $number; $i++) {
                        $query =   db_query("INSERT INTO `tbl_lead_product`(`lead_id`, `product_id`,`product_type_id`,`existing_IT`, `app_usage`,`form_id`,`created_at`) VALUES ('" . $lead_id . "' ,'" . $_GET['lead'] . "' ,'" . $_GET['type'] . "','" . $_POST['existing_IT'][$i] . "','" . $_POST['app_usage'][$i] . "','" . $row['form_id'] . "',now())");
                       //print_r($query);die;
                    }
                }
            } elseif ($product_name) {
                // print_r($product_name);die;
                $number = count($product_name);
                $number1 = count($_POST['existing_IT']);
                $number2 = count($_POST['app_usage']);
                if ($number1 > $number2) {
                    for ($i = 0; $i < $number1; $i++) {
                        $query =  db_query("INSERT INTO `tbl_lead_product`(`lead_id`, `product_id`,`product_type_id`,`existing_IT`, `app_usage`,`form_id`,`created_at`) VALUES ('" . $lead_id . "' ,'" . $product_id . "' ,'" . $product_type_id . "' ,'" . $_POST['existing_IT'][$i] . "','" . $_POST['app_usage'][$i] . "','" . $form_id . "',now())");
                    }
                } elseif ($number1 < $number2) {
                    for ($i = 0; $i < $number2; $i++) {
                        $query =  db_query("INSERT INTO `tbl_lead_product`(`lead_id`, `product_id`,`product_type_id`,`existing_IT`, `app_usage`,`form_id`,`created_at`) VALUES ('" . $lead_id . "' ,'" . $product_id . "' ,'" . $product_type_id . "' ,'" . $_POST['existing_IT'][$i] . "','" . $_POST['app_usage'][$i] . "','" . $form_id . "',now())");
                    }
                } else {
                    for ($i = 0; $i < $number; $i++) {
                        $query =  db_query("INSERT INTO `tbl_lead_product`(`lead_id`, `product_id`,`product_type_id`,`existing_IT`, `app_usage`,`form_id`,`created_at`) VALUES ('" . $lead_id . "' ,'" . $product_id . "' ,'" . $product_type_id . "','" . $_POST['existing_IT'][$i] . "','" . $_POST['app_usage'][$i] . "','" . $form_id . "',now())");
                    }
                }
            }
        }
        $points_date = week_range(date('Y-m-d'));

        $add_point = db_query("insert into user_points (stage_id,stage_name,point,week_number,date_from,date_to,no_of_seats,user_id,lead_id) values (1000,'New',1,'" . date('W') . "','$points_date[0]','$points_date[1]','" . $_POST['quantity'] . "','" . $_SESSION['user_id'] . "',$lead_id) ");

        $sm_email = getSingleresult("select users.email as email from users join partners on partners.sm_user=users.id where partners.id='" . $_SESSION['team_id'] . "'");


        $addTo[] = "kailash.bhurke@arkinfo.in";
        $addCc[] = "pradnya.chaukekar@arkinfo.in"; 

        $setSubject = "New Lead Added to DR Portal from " . $_POST['r_name'] . " (" . $_POST['r_user'] . ")";
        $body    = "Hi,<br><br> There is new lead added to SketchUp DR Portal with details as below:-<br><br>
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
        sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
        //$res=db_query("insert into notifications (`description`,`parent_id`, `type`, `user_id`, `role`, `is_read`, `status`) VALUES('".$mail->Subject."','".$insert_id."','New-Lead','NULL','ADMIN',0,1) ") ;
        if ($res){
        //if ($res && $mail->Send()) {
            redir("orders.php?add=success&cnt=" . $cont_final . "&lt=" . $_POST['lead_type'], true);
        }
    } else {
        redir("orders.php?fail=ext", true);
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

                            <!-- <h5 class="card-title">Add Lead</h5>-->

                            <div class="media ">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/ict-logo.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home > Add Lead</small>
                                    <h4 class="font-size-14 m-0 mt-1">Add Lead</h4>
                                </div>
                            </div>
                            <form method="post" action="#" class="form-horizontal" enctype="multipart/form-data" id="form_activity">

                                <div data-simplebar class="add_lead">

                                    <h5 class="card-subtitle">Add Lead:- &nbsp;&nbsp;&nbsp;Product Name:<?= ($product_name ? $product_name : $row['product_name']) ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Product type:<?= ($product_type ? $product_type : $row['product_type']) ?></h5>

                                    <div style="display:none;">

                                        <input name="r_name" type="text" readonly value="<?= getSingleresult("select name from partners where id='" . $_SESSION['team_id'] . "'") ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">

                                        <input readonly value="<?= $_SESSION['email'] ?>" name="r_email" type="email" required data-validation-required-message="This field is required" class="form-control form-control" placeholder="">

                                        <input name="r_user" readonly value="<?= $_SESSION['name'] ?>" type="text" class="form-control" placeholder="" required data-validation-required-message="This field is required">

                                    </div>
                                    <!--/row-->

                                    <h5 class="card-subtitle">Customer Information</h5>

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Lead Source<span class="text-danger">*</span></label>

                                            <select name="source" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <?php $res = db_query("select * from lead_source where status=1");
                                                while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($source == $row['lead_source']) ? 'selected' : '') ?> value="<?= $row['lead_source'] ?>"><?= $row['lead_source'] ?></option>
                                                <?php } ?>



                                            </select>

                                        </div>
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Lead Type<span class="text-danger">*</span></label><br>
                                            <div class="">
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">

                                                        <input name="lead_type" value="LC" <?php echo ($lead_type == 'LC') ?  "checked" : "";  ?> type="radio" required id="customRadio7" class="custom-control-input " disabled style="float: left;margin: 1px;    position: relative;">
                                                        <label for="customRadio7" class="custom-control-label" disabled>LC</label>
                                                    </div>
                                                </div>
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">
                                                        <input name="lead_type" value="BD" <?php echo ($lead_type == 'BD') ?  "checked" : "";  ?> type="radio" required id="customRadio8" class="custom-control-input error">
                                                        <label for="customRadio8" class="custom-control-label">BD</label>
                                                    </div>
                                                </div>
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">
                                                        <input name="lead_type" value="Incoming" <?php echo ($lead_type == 'Incoming') ?  "checked" : "";  ?> type="radio" required id="customRadio9" class="custom-control-input ">
                                                        <label class="custom-control-label" for="customRadio9">Incoming</label>
                                                    </div>
                                                </div>


                                            </div>


                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input">Company Name<span class="text-danger">*</span></label>

                                            <input type="text" value="<?= $company_name ?>" name="company_name" class="form-control" placeholder="Company Name" required id="example-text-input" data-validation-required-message="This field is required">

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label class="example-text-input">Parent Company<span class="text-danger"></span></label>

                                            <input type="text" value="<?= $parent_company ?>" name="parent_company" class="form-control" placeholder="Parent Company" id="example-text-input">

                                        </div>


                                        <div class="col-lg-4 mb-3">
                                            <label for="example-text-input">Landline Number<span class="text-danger"></span></label>

                                            <input type="number" min="0" name="landline" value="<?= $landline ?>" class="form-control" placeholder="Landline Number" id="example-text-input">

                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input">Region<span class="text-danger">*</span></label>

                                            <select name="region" class="form-control" placeholder="" required data-validation-required-message="This field is required" id="example-text-input" aria-invalid="false">
                                                <option value="">---Select---</option>
                                                <?php $res = db_query("select * from region where status=1");
                                                while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($region == $row['region']) ? 'selected' : '') ?> value="<?= $row['region'] ?>"><?= $row['region'] ?></option>
                                                <?php } ?>


                                            </select>

                                            <!--/span-->
                                        </div>
                                    </div>

                                    <div class="row">
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

                                            <input type="text" name="country" value="India" class="form-control" placeholder="Country" required readonly data-validation-required-message="This field is required" id="example-text-input">

                                        </div>
                                    </div>


                                    <div class="row">
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

                                            <input type="text" value="<?= $city ?>" name="city" class="form-control" placeholder="City" required data-validation-required-message="This field is required" pattern="[A-Za-z\s]+" id="example-text-input" />

                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input">Pin Code<span class="text-danger">*</span></label>

                                            <input type="number" value="<?= $pincode ?>" min="0" name="pincode" class="form-control" placeholder="PinCode" required id="example-text-input" data-validation-required-message="This field is required" />

                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-4">
                                            <label for="validationTooltip05">Address<span class="text-danger">*</span></label>

                                            <textarea name="address" value="" rows="5" class="form-control" placeholder="" id="exampleFormControlTextarea1" required data-validation-required-message="This field is required"><?= $address ?></textarea>

                                        </div>

                                    </div>

                                    <h5 class="card-subtitle">Decision Maker/Proprietor/Director/End User Details
                                    </h5>
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Full Name<span class="text-danger">*</span></label>

                                            <input name="eu_name" value="<?= $eu_name ?>" type="text" class="form-control" placeholder="Full Name" required data-validation-required-message="This field is required" id="example-text-input">


                                        </div>


                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Email<span class="text-danger">*</span></label>

                                            <input value="<?= $eu_email ?>" name="eu_email" type="email" id="example-text-input" class="form-control" placeholder="Email" required data-validation-required-message="This field is required">


                                        </div>


                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Mobile<span class="text-danger">*</span></label>

                                            <input type="number" min="0" name="eu_mobile" value="<?= $eu_mobile ?>" class="form-control" id="example-text-input" placeholder="Mobile" required data-validation-required-message="This field is required">

                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Landline Number<span class="text-danger"></span></label>

                                            <input type="number" value="<?= $eu_landline ?>" min="0" name="eu_landline" autocomplete="of" id="example-text-input" class="form-control" placeholder="Landline Number">

                                        </div>


                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Designation<span class="text-danger">*</span></label>

                                            <input type="text" value="<?= $eu_designation ?>" name="eu_designation" class="form-control" placeholder="Designation" required id="example-text-input" data-validation-required-message="This field is required" />

                                        </div>


                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Role<span class="text-danger"></span></label>

                                            <select name="eu_role" class="form-control" placeholder="">
                                                <option value="">---Select---</option>
                                                <option <?= (($eu_role == 'User') ? 'selected' : '') ?> value="User">User</option>
                                                <option <?= (($eu_role == 'Economy Buyer') ? 'selected' : '') ?> value="Economy Buyer">Economy Buyer</option>
                                                <option <?= (($eu_role == 'Tech. Buyer') ? 'selected' : '') ?> value="Tech. Buyer">Tech. Buyer</option>
                                                <option <?= (($eu_role == 'Decision Maker') ? 'selected' : '') ?> value="Decision Maker">Decision Maker</option>


                                            </select>

                                        </div>



                                    </div>



                                    <div class="row">
                                        <!-- <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Visit/Profiling Remarks<span class="text-danger">*</span></label>

                                            <textarea name="visit_remarks" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required"><?= $visit_remarks ?></textarea>


                                        </div> -->
                                        <div class="col-lg-4 mb-3">
                                            <button style="width:13dx;margin-top:20px;" type="button" name="add" id="add" class="btn btn-success">Add</button>

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
                                                    <div class="form-group row">
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
                                                        <div class="col-md-2"><button style="width:50px;margin-top:18px;" type="button" name="remove" id=<?= $i ?> class="btn btn-danger btn_remove form-control">X</button></div>
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

                                            <label class="mt-2">Existing IT / Infrastructure</label>
                                            <div class="clearfix"> </div>
                                            <select id="multiselect1" class="form-control" multiple name="existing_IT[]">
                                              
                                                <option value="Server/Client" <?= (in_array('Server/Client', $exist_IT) ? 'selected' : '') ?>>Server/Client</option>
                                                <option value="Private Cloud" <?= (in_array('Private Cloud', $exist_IT) ? 'selected' : '') ?>>Private Cloud</option>
                                                <option value="Public Cloud" <?= (in_array('Public Cloud', $exist_IT) ? 'selected' : '') ?>>Public Cloud</option>
                                            </select>

                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label class="mt-2">Application Usage</label>
                                            <div class="clearfix"> </div>
                                            <select id="multiselect" class="form-control" multiple name="app_usage[]">
                                                <option value="CAD CAM" <?= (in_array('CAD CAM', $appUsage) ? 'selected' : '') ?>>CAD CAM</option>
                                                <option value="Animation Software" <?= (in_array('Animation Software', $appUsage) ? 'selected' : '') ?>>Animation Software</option>
                                                <option value="Graphic Designing" <?= (in_array('Graphic Designing', $appUsage) ? 'selected' : '') ?>>Graphic Designing</option>
                                                <option value="ERP/ CRM" <?= (in_array('ERP/ CRM', $appUsage) ? 'selected' : '') ?>>ERP/ CRM</option>
                                                <option value="Others" <?= (in_array('Others', $appUsage) ? 'selected' : '') ?>>Others</option>
                                            </select>
                                        </div>


                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Quantity<span class="text-danger">*</span></label><br>
                                            <div class="clearfix"> </div>

                                            <input type="text" id="range_quantity" name="quantity" value="<?= ($quantity ? $quantity : 15) ?>">


                                        </div>
                                    </div>
                                    <!-- <span id="ex6CurrentSliderValLabel">Users: <span id="ex6SliderVal"><?= ($quantity ? $quantity : '15') ?></span></span> &nbsp;<input id="ex6" name="quantity" type="text" data-slider-min="15" data-slider-step="15" data-slider-value="<?= ($quantity ? $quantity : '15') ?>" data-slider-max="300" class="form-control" placeholder="" required data-validation-required-message="This field is required" /> -->
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input">Attachment <br>(Max: 4MB)<span class="text-danger"></span></label>

                                            <input type="file" name="user_attachment" class="btn btn-default" value="<?= $user_attachement ?>" aria-invalid="false" />
                                            <?php if ($user_attachement) { ?>
                                                <img src="<?= $user_attachement ?>" style="width:50px; height:50px" />
                                            <?php } ?>
                                            <input type="hidden" name="old_user_attachment" value="<?= $user_attachement ?>" class="form-control">

                                        </div>
                                        <?php if ($_GET['license_type'] == 'Commercial') { ?>
                                            <div class="col-lg-4 mb-3" id="campaign_show">
                                                <label class="control-label">Campaign<span class="text-danger"></span></label>
                                                <select name="campaign_type" class="form-control">
                                                    <option value=" ">--Select--</option>
                                                    <?php
                                                    $campaign_select = campaign_data($_GET['lead']);
                                                    while ($row = db_fetch_array($campaign_select)) { ?>
                                                        <option value="<?= $row['id'] ?>" <?= (($campaign_type == $row['id']) ? 'selected' : '') ?>><?= $row['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-lg-4 mb-3" id="campaign_show" style="display: none">
                                                <label class="control-label">Campaign<span class="text-danger"></span></label>
                                                <select name="campaign_type" class="form-control">
                                                    <option value=" ">--Select--</option>
                                                    <?php
                                                    $campaign_select = campaign_data($_GET['lead']);
                                                    while ($row = db_fetch_array($campaign_select)) { ?>
                                                        <option value="<?= $row['id'] ?>" <?= (($campaign_type == $row['id']) ? 'selected' : '') ?>><?= $row['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        <?php } ?>
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input">Close Date<span class="text-danger"></span></label>
                                            <div class="input-group">
                                            <input type="text" value="<?= date('Y-m-t') ?>" readonly required="required" name="partner_close_date" class="form-control" id="datepicker-close-date" />
                                            <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
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
              if ($row_data['role'] == 'TC') {
               
                  $call_query = db_query("select * from call_subject where subject not like '%visit%' order by id asc");
              }else{
                $call_query = db_query("select * from call_subject where 1 order by id asc"); 
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

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    <script>

   $('#form_data').on('click', function(e) {
    e.preventDefault();
   
     });

$('#save_btn').on('click', function(e){
    e.preventDefault();
    if($('#form_activity').validate().form()) {
        e.preventDefault();
        if ($('#call_subject').val() != "" && $('#remarks').val() != "") {
            console.log("validates");
        //alert('submitting');
     $('#form_activity').submit();
        }else{
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

  
    </script>

    <script>
  $(document).ready(function () {
  $('#multiselect').multiselect({
    buttonWidth: '100%',
    includeSelectAllOption: true,
    nonSelectedText: 'Select an Option' });

    $('#multiselect1').multiselect({
    buttonWidth: '100%',
    includeSelectAllOption: true,
    nonSelectedText: 'Select an Option' });

});

        $(document).ready(function() {

            var wfheight = $(window).height();
            $('.add_lead').height(wfheight - 230);

        });

        $(document).ready(function() {
            $("#range_quantity").ionRangeSlider({
                skin: "flat",
               // type: "double",
                min: 15,
                max: 300,
               // from: 0,
                //to: 15,
                step: 15
            })
        });
    </script>

    <script>
        $(document).ready(function() {
            // var i = 1;
            var add_btn = $('.add_btn').val();
            $('#add').click(function() {
                //i++;
                $('#dynamic_field').append('<div id="row' + add_btn + '"><div class="form-group row"><div class="col-md-2"><label class="control-label">Full Name</label><input name="e_name[]" value="" type="text" value="" class="form-control" placeholder=""></div><div class="col-md-2"><label class="control-label">Email</label><input value="" name="e_email[]" type="email" class="form-control" placeholder=""></div><div class="col-md-2"><label class="control-label">Mobile</label><input type="number" min="0" name="e_mobile[]" value="" class="form-control"></div><div class="col-md-2"><label class="control-label">Designation</label><input type="text" value="" name="e_designation[]" class="form-control" /></div><div class="col-md-2"><button style="width:50px;margin-top:18px;" type="button" name="remove" id="' + add_btn + '" class="btn btn-danger btn_remove form-control">X</button></div></div></div>');
                add_btn++;
            });
            $(document).on('click', '.btn_remove', function() {
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
    <style>
        .dropdown-menu .inner {
            height: 150px !important;
        }

        .data_export_box .dropdown-menu.show {
            width: 300px !important;
            min-width: 300px !important;
        }
    </style>
	<script>
	   $(function() {
            $('#datepicker-close-date').datepicker({
               format: 'yyyy-mm-dd',
			   startDate: '-3d',
			   autoclose:!0
                //startDate: '2017-01-01',
                //autoUpdateInput: false,

            });
			
        });

 </script>