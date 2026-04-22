<?php include('includes/header.php'); 

if ($_REQUEST['eid']) {
    $sql = db_query("select o.*,tp.*,p.product_name,tpp.product_type from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id where o.id=" . $_REQUEST['eid'] . " and o.team_id=" . $_SESSION['team_id']);
    $row = db_fetch_array($sql);
    @extract($row);
}

if ($_POST['eid']) {
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

    $user_image = !empty($_FILES["user_attachment"]["name"]) ? $target_file : $row['user_attachement'];

    if (!$_POST['lead_type']) {
        $_POST['lead_type'] = '';
    }
    if (!$_POST['account_visited']) {
        $_POST['account_visited'] = 'Yes';
    }
    if (!$_POST['license_type']) {
        $_POST['license_type'] = 'Commercial';
    }

    $res = db_query("update  `orders` set `source`='" . $_POST['source'] . "', `lead_type`='" . $_POST['lead_type'] . "', `company_name`='" . $_POST['company_name'] . "', `parent_company`='" . $_POST['parent_company'] . "', `landline`='" . $_POST['landline'] . "',`region`='" . $_POST['region'] . "', `industry`='" . $_POST['industry'] . "',`sub_industry`='" . $_POST['sub_industry'] . "', `address`='" . $_POST['address'] . "', `pincode`='" . $_POST['pincode'] . "', `state`='" . $_POST['state'] . "', `city`='" . $_POST['city'] . "', `country`='" . $_POST['country'] . "', `eu_name`='" . $_POST['eu_name'] . "', `eu_email`='" . $_POST['eu_email'] . "', `eu_landline`='" . $_POST['eu_landline'] . "', `department`='" . $_POST['department'] . "', `eu_mobile`='" . $_POST['eu_mobile'] . "', `eu_designation`='" . $_POST['eu_designation'] . "', `eu_role`='" . $_POST['eu_role'] . "', `account_visited`='" . $_POST['account_visited'] . "', `visit_remarks`='" . $_POST['visit_remarks'] . "', `confirmation_from`='" . $_POST['confirmation_from'] . "', `license_type`='" . $_POST['license_type'] . "', `quantity`='" . $_POST['quantity'] . "',user_attachement='" . $user_image . "' where id=" . $_POST['eid']);



    if ($res) {

        redir("dvr.php?update=success", true);
    }
}



?>
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home</small>
                                    <h4 class="font-size-14 m-0 mt-1">Edit Daily Visit Information</h4>
                                </div>
                            </div>
<div class="clearfix"></div>

                          
						   <div style="padding:0 15px;">
						   <h5 class="card-subtitle">Edit Daily Visit:-&nbsp;&nbsp;&nbsp;Product Name:<?= $product_name ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Product type:<?= $product_type ?></h5>
</div>
                            <form method="post" action="#" class="form-horizontal" novalidate enctype="multipart/form-data">

                                <div data-simplebar class="add_lead">

                                    <h5 class="card-subtitle">Customer Information</h5>

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input">Lead Source<span class="text-danger">*</span></label>

                                            <select name="source" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <?php $res = db_query("select * from lead_source where status=1");
                                                while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($source == $row['lead_source']) ? 'selected' : '') ?> value="<?= $row['lead_source'] ?>"><?= $row['lead_source'] ?></option>
                                                <?php } ?>

                                            </select>

                                        </div>
                                        <div class="col-lg-4 mb-3" style="display:none">

                                            <label class="control-label">Lead Type<span class="text-danger">*</span></label>

                                            LC <input type="checkbox" name="lead_type" <?= (($lead_type == 'BD') ? 'checked' : '') ?> value="BD" class="js-switch" data-color="#26c6da" data-secondary-color="#f62d51" /> BD


                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Company Name<span class="text-danger">*</span></label>

                                            <input type="text" name="company_name" value="<?= $company_name ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">


                                        </div>
                                        <!--/span-->
                                        <div class="col-lg-4 mb-3">

                                            <label class="control-label">Parent Company<span class="text-danger"></span></label>

                                            <input type="text" name="parent_company" value="<?= $parent_company ?>" class="form-control" placeholder="">


                                        </div>
                                        <!--/span-->

                                        <?php if ($product_type_id != 4) { ?>
                                            <div class="col-lg-4 mb-3">

                                                <label class="control-label">Association Name<span class="text-danger"></span></label>

                                                <input type="text" name="association_name" value="" class="form-control" placeholder="Association Name">

                                            </div>
                                        <?php } ?>


                                        <div class="col-lg-4 mb-3">

                                            <label class="control-label">Landline Number<span class="text-danger"></span></label>

                                            <input type="number" min="0" name="landline" value="<?= $landline ?>" class="form-control" placeholder="">
                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label class="control-label">Region<span class="text-danger">*</span></label>

                                            <select name="region" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <?php $res = db_query("select * from region where status=1");
                                                while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($region == $row['region']) ? 'selected' : '') ?> value="<?= $row['region'] ?>"><?= $row['region'] ?></option>
                                                <?php } ?>


                                            </select>

                                        </div>
                                        <!--/span-->


                                        <div class="col-lg-4 mb-3">
                                            <label class="control-label">Industry<span class="text-danger">*</span></label>

                                            <?php $res = db_query("select * from industry order by name ASC");

                                            //print_r($res); die;

                                            ?>
                                            <select name="industry" id="industry" class="form-control" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <?php while ($ind = db_fetch_array($res)) { ?>
                                                    <option <?= (($ind['id'] == $industry) ? 'selected' : '') ?> value='<?= $ind['id'] ?>'><?= $ind['name'] ?></option>
                                                <?php } ?>
                                            </select>

                                        </div>
                                        <div class="col-lg-4 mb-3" id="sub_industry">
                                            <?php if ($sub_industry) {
                                                $query = db_query("SELECT * FROM sub_industry WHERE industry_id = " . $industry . "  ORDER BY name ASC");
                                                $rowCount = mysqli_num_rows($query);
                                                if ($rowCount > 0) {
                                                    echo ' 
                                                    <label class="control-label">Sub Industry<span class="text-danger">*</span></label>
                                                    <select name="sub_industry" class="form-control" required data-validation-required-message="This field is required" id="subind">';
                                                    while ($row = db_fetch_array($query)) {
                                                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                                    }
                                                    echo '</select>';
                                                }
                                            }else{ 
                                                $query = db_query("SELECT * FROM sub_industry WHERE industry_id = ".$industry."  ORDER BY name ASC");
                                                //Count total number of rows
                                                $rowCount = mysqli_num_rows($query);
                                                if($rowCount > 0){ 
                                                    echo '
                                                    <label class="example-text-input">Sub Industry<span class="text-danger">*</span></label>
                                                    <select name="sub_industry" class="form-control" required  id="subind">
                                                    <option value="" >Sub Industry</option>';
                                                while ($row = db_fetch_array($query)) {
                                                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                                }
                                                echo '</select>';
                                                 } } ?>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <label class="control-label">Pin Code<span class="text-danger">*</span></label>

                                            <input type="number" min="0" name="pincode" value="<?= $pincode ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required" />
                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label class="control-label">State<span class="text-danger">*</span></label>

                                            <?php $res = db_query("select * from states");

                                            //print_r($res); die;

                                            ?>
                                            <select name="state" id="state" class="form-control" required data-validation-required-message="This field is required">
                                                <option>---Select---</option>
                                                <?php while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($state == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <!--/span-->
                                        <!--/row-->
                                        <div class="col-lg-4 mb-3">
                                            <label class="control-label">City<span class="text-danger">*</span></label>

                                            <input type="text" name="city" value="<?= $city ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <label class="control-label">Country<span class="text-danger">*</span></label>

                                            <input type="text" name="country" value="<?= $country ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">

                                        </div>
                                        <!--/span-->

                                        <div class="col-lg-4 mb-3">

                                            <label class="control-label">Address<span class="text-danger">*</span></label>

                                            <textarea name="address" class="form-control" placeholder="" required data-validation-required-message="This field is required"><?= $address ?></textarea>

                                        </div>
                                        <!--/span-->

                                        <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <h5 class="card-subtitle">Decision Maker/Proprietor/Director/End User Details</h5>
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <label class="control-label">Full Name<span class="text-danger">*</span></label>

                                            <input name="eu_name" type="text" value="<?= $eu_name ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                        </div>
                                        <!--/span-->
                                        <div class="col-lg-4 mb-3">

                                            <label class="control-label">Email</label>

                                            <input value="<?= $eu_email ?>" name="eu_email" type="email" class="form-control form-control" placeholder="">
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <label class="control-label">Landline Number<span class="text-danger"></span></label>

                                            <input type="number" min="0" name="eu_landline" value="<?= $eu_landline ?>" class="form-control" placeholder="">

                                        </div>
                                        <!--/span-->
                                        <div class="col-lg-4 mb-3">
                                            <label class="control-label">Department<span class="text-danger">*</span></label>

                                            <input type="text" name="department" value="<?= $department ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                        </div>
                                        <!--/span-->
                                        <div class="col-lg-4 mb-3">
                                            <label class="control-label">Mobile<span class="text-danger">*</span></label>
                                            <input type="number" min="0" name="eu_mobile" value="<?= $eu_mobile ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">

                                        </div>
                                        <!--/span-->
                                        <div class="col-lg-4 mb-3">
                                            <label class="control-label">Designation<span class="text-danger">*</span></label>
                                            <input type="text" name="eu_designation" value="<?= $eu_designation ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required" />
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <label class="control-label">Role<span class="text-danger">*</span></label>

                                            <select name="eu_role" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <option <?= (($eu_role == 'User') ? 'selected' : '') ?> value="User">User</option>
                                                <option <?= (($eu_role == 'Economy Buyer') ? 'selected' : '') ?> value="Economy Buyer">Economy Buyer</option>
                                                <option <?= (($eu_role == 'Tech. Buyer') ? 'selected' : '') ?> value="Tech. Buyer">Tech. Buyer</option>
                                                <option <?= (($eu_role == 'Decision Maker') ? 'selected' : '') ?> value="Decision Maker">Decision Maker</option>
                                            </select>

                                        </div>
                                        <!--/span-->
                                        <div class="col-lg-4 mb-3">
                                            <label class="control-label">Usage Confirmation Received from<span class="text-danger">*</span></label>

                                            <select name="confirmation_from" class="form-control" placeholder="" required data-validation-required-message="This field is required">
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
                                        <!--/span-->
                                    

                                    <div class="col-lg-4 mb-3" style="display:none">
                                        <div class="form-group row">
                                            <label class="control-label text-right col-md-3">Account Visited<span class="text-danger">*</span></label>
                                            <div class="col-md-9 controls">
                                                Yes <input type="checkbox" <?= (($account_visited == 'No') ? 'checked' : '') ?> name="account_visited" value="NO" class="js-switch" data-color="#f62d51" data-secondary-color="#26c6da" /> NO
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 mb-3">
                                        <label class="control-label">Visit Remarks<span class="text-danger">*</span></label>
                                        <textarea name="visit_remarks" class="form-control" placeholder="" required data-validation-required-message="This field is required"><?= $visit_remarks ?></textarea>
                                    </div>
                                
                                    </div>
                                <h5 class="card-subtitle">Order Information</h5>

                                <div class="row">
                                    <div class="col-lg-4 mb-3">
                                    <label for="example-text-input" class="">Type of License<span class="text-danger">*</span></label>
                                    <div class="">
                                                <div class="form-check-inline my-1">
                                                    <div class="custom-control custom-radio">
                                        <input checked name="license_type" value="<?= $license_type ?>" type="radio" required id="md_checkbox_21" class="filled-in radio-col-red">
                                        <label for="md_checkbox_21"><?= $license_type ?></label>

                                        </div>
                                                </div>
                                            </div>

                                        </div>
                                    <?php if ($product_name == 'Parallel') { ?>
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-search-input" class=" ">Quantity<span class="text-danger">*</span></label>
                                            <div class="clearfix"> </div>

                                            <input type="text" id="range_qty" name="quantity" value="<?= $quantity ?>">


                                        </div>
                                        <!-- <div class="col-lg-4 mb-3">

                                        <label for="example-color-input" class="control-label text-right">Quantity<span class="text-danger">*</span></label><br>


                                        <span id="ex6CurrentSliderValLabel">Users: <span id="ex6SliderVal"><?= $quantity ?></span></span> &nbsp;<input id="ex6" name="quantity" type="text" data-slider-min="15" data-slider-max="300" data-slider-step="15" data-slider-value="<?= $quantity ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required" />


                                    </div> -->
                                    <?php } else { ?>
                                        <div class="col-lg-4 mb-3">
                                            <label for="example-search-input" class=" ">Quantity<span class="text-danger">*</span></label>
                                            <div class="clearfix"> </div>

                                            <input type="text" id="range_qty" name="quantity" value="<?= $quantity ?>">


                                        </div>

                                        <!-- <div class="col-md-4">

                                        <label for="example-color-input" class="control-label text-left">Quantity<span class="text-danger">*</span></label><br>

                                        <span id="ex6CurrentSliderValLabel">Users: <span id="ex6SliderVal"><?= $quantity ?></span></span>&nbsp;&nbsp;&nbsp;&nbsp;<input id="ex6" name="quantity" type="text" data-slider-min="1" data-slider-max="50" data-slider-step="1" data-slider-value="<?= $quantity ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required" />

                                    </div> -->
                                    <?php } ?>

                                    <div class="col-lg-4 mb-3">
                                        <label class="control-label">Attachment <br>(Max: 4MB)<span class="text-danger"></span></label>

                                        <input type="file" name="user_attachment" class="form-control" />

                                        <img src="<?= $user_attachement ?>" style="width:50px; height:50px" />

                                        <input type="hidden" name="old_user_attachment" value="<?= $user_attachement ?>" class="form-control">

                                    </div>
                                </div>
                        </div>

                        <div class="button-items">
                            <input type="hidden" name="eid" value=<?= $_REQUEST['eid'] ?> />
                            <button type="submit" class="btn btn-primary  mt-2">Save</button>
                            <button type="button" onclick="javascript:history.go(-1)" class="btn btn-danger mt-2">Cancel</button>

                        </div>

                        </form>
                    </div>
                </div>
            </div> <!-- end col -->


        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>
<!-- End Page-content -->

<?php include('includes/footer.php') ?>
<script src="js/validation.js"></script>

<script>
    $(document).ready(function() {
        $('#state').on('change', function() {
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    type: 'POST',
                    url: 'ajaxcity.php',
                    data: 'state_id=' + stateID,
                    success: function(html) {
                        $('#city').html(html);
                    }
                });
            } else {
                $('#city').html('<option value="">Select state first</option>');
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#state').on('change', function() {
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    type: 'POST',
                    url: 'ajaxcity.php',
                    data: 'state_id=' + stateID,
                    success: function(html) {
                        $('#city').html(html);
                    }
                });
            } else {
                $('#city').html('<option value="">Select state first</option>');
            }
        });

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
        $('#check').click(function() {
            //alert($(this).is(':checked'));
            $(this).is(':checked') ? $('#pwd').attr('type', 'text') : $('#pwd').attr('type', 'password');
        });
    });
    $(document).ready(function() {
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
        });
        // For select 2


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
</script>