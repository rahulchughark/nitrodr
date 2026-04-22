<?php include('includes/header.php');

include_once('helpers/DataController.php');

$modify_log = new DataController();


if (isset($_POST['lead_submit'])) {

    if (!empty($_FILES["user_attachment"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . time() . basename($_FILES["user_attachment"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($_FILES["user_attachment"]["size"] > 4000000) {
            echo "<script>alert('Sorry, your file is too large!')</script>";
            redir("add_iss_leads.php", true);
        } else {
            move_uploaded_file($_FILES["user_attachment"]["tmp_name"], $target_file);
        }
    }

    if (!$_POST['account_visited']) {
        $_POST['account_visited'] = 'No';
    }


    $runrate_key = ($_POST['quantity'] <= 3) ? 'Runrate' : 'Key';

   // $caller = getSingleresult("select id from callers where user_id='" . $_SESSION['user_id'] . "'");
   $reseller_email = getSingleresult("select email from users where id='" . $_POST['allign_to'] . "'");
   $reseller_name = getSingleresult("select name from users where id='" . $_POST['allign_to'] . "'");
    $partner = getSingleresult("select name from partners where id='" . $_POST['partner'] . "'");
    $close_time = date('Y-m-d', strtotime('+29 days', strtotime(date('Y-m-d h:i:s'))));


    $data = ['r_name' => $partner, 'r_email' => $reseller_email, 'r_user' => $reseller_name, 'source' => 'Corel Team', 'lead_type' => 'BD', 'company_name' => $_POST['company_name'], 'parent_company' => $_POST['parent_company'], 'landline' => $_POST['landline'], 'region' => $_POST['region'], 'industry' => $_POST['industry'], 'sub_industry' => $_POST['sub_industry'], 'address' => htmlspecialchars($_POST['address'], ENT_QUOTES), 'pincode' => $_POST['pincode'], 'state' => $_POST['state'], 'city' => $_POST['city'], 'country' => $_POST['country'], 'eu_name' => $_POST['eu_name'], 'eu_email' => $_POST['eu_email'], 'eu_landline' => $_POST['eu_landline'], 'department' => $_POST['department'], 'eu_mobile' => $_POST['eu_mobile'], 'eu_designation' => $_POST['eu_designation'], 'eu_role' => $_POST['eu_role'], 'account_visited' => $_POST['account_visited'], 'visit_remarks' => htmlspecialchars($_POST['visit_remarks'], ENT_QUOTES), 'confirmation_from' => $_POST['confirmation_from'], 'license_type' => 'Renewal', 'quantity' => $_POST['quantity'], 'created_by' => $_POST['allign_to'], 'team_id' => $_POST['partner'], 'status' => 'Approved', 'user_attachement' => $target_file, 'allign_to' => $_POST['allign_to'], 'os' => 'Windows', 'runrate_key' => $runrate_key,'license_key'=>$_POST['license_number'],'license_end_date'=>$_POST['license_end_date'],'approval_time'=>date('Y-m-d H:i:s'),'close_time'=>$close_time,'partner_close_date'=>$_POST['close_date'],'caller'=>$_POST['caller']];

    $res = $modify_log->insert($data, "orders");

    $lead_id = get_insert_id();

    $query =  db_query("INSERT INTO `tbl_lead_product`(`lead_id`, `product_id`,`product_type_id`,`form_id`,`created_at`) VALUES ('" . $lead_id . "' ,2 ,6,0,now())");

    if ($_POST['e_name']) {
        $number = count($_POST["e_name"]);

        for ($i = 0; $i < $number; $i++) {
            $query =  insertLeadContact('tbl_lead_contact', $lead_id, $_POST['e_name'][$i], $_POST['e_email'][$i], $_POST['e_mobile'][$i], $_POST['e_designation'][$i]);
        }
    }

    if($res){
        redir("renewal_leads_admin.php?add=success", true);
    }else{
        redir("renewal_leads_admin.php?fail=ext", true);
    }
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
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/ict-logo.png" alt=" ">
                                <div class="media-body">
                                    <small class="text-muted">Home > Add Lead</small>
                                    <h4 class="font-size-14 m-0 mt-1">Add Lead</h4>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <form method="post" action="#" class="form-horizontal" enctype="multipart/form-data" >
                                <div data-simplebar class="add_lead">
                                    <h5 class="card-subtitle">Reseller Info</h5>
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label class="control-label">Assigned to Partner<span class="text-danger">*</span></label>
                                            <?php $res = db_query("select * from partners where id <> 45"); ?>
                                            <select placeholder="" name="partner" id="partner" class="form-control" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <?php while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($_GET['partner'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                <?php } ?>
                                            </select>


                                        </div>
                                        <!--/span-->
                                        <div class="col-lg-4 mb-3">

                                            <label class="control-label">Align To<span class="text-danger">*</span></label>

                                            <select name="allign_to" id="users" class="form-control " required data-validation-required-message="This field is required">
                                                <option value="">Select User</option>

                                            </select>

                                        </div>

                                        <div class="col-lg-4 mb-3">

<label class="control-label">Caller<span class="text-danger">*</span></label>

<select name="caller" id="caller" class="form-control " required data-validation-required-message="This field is required">
    <option value="">Select Caller</option>
<?php $r_caller = db_query("select callers.* from callers left join users on users.id=callers.user_id where (users.user_type='RCLR' OR users.user_type='RENEWAL TL') and users.status='Active'");
while($row = db_fetch_array($r_caller)){ ?>
<option <?= (($_GET['caller'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
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

                                            <input type="text" name="company_name" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required">

                                        </div>

                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">Parent Company</label>

                                            <input type="text" name="parent_company" value="" class="form-control" placeholder="">

                                        </div>

                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">Industry<span class="text-danger">*</span></label>

                                            <?php $res = db_query("select * from industry order by name ASC");

                                            //print_r($res); die;

                                            ?>
                                            <select name="industry" id="industry" class="form-control" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <?php while ($row = db_fetch_array($res)) { ?>
                                                    <option value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                <?php } ?>
                                            </select>

                                        </div>
                                        <div class="col-lg-3 mb-3" id="sub_industry">

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

                                            <textarea name="address" autocomplete="of" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required"></textarea>


                                        </div>

                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">State<span class="text-danger">*</span></label>

                                            <?php $res = db_query("select * from states"); ?>
                                            <select name="state" id="state" class="form-control" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <?php while ($row = db_fetch_array($res)) { ?>
                                                    <option value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                <?php } ?>
                                            </select>

                                        </div>

                                        <!--/span-->
                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">Pin Code<span class="text-danger">*</span></label>

                                            <input type="number" min="0" autocomplete="of" name="pincode" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required" />

                                        </div>
                                        <!--/span-->


                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">City<span class="text-danger">*</span></label>

                                            <input type="text" name="city" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required" />

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

                                            <input name="eu_name" type="text" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                        </div>
                                        <!--/span-->
                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">Email<span class="text-danger">*</span></label>

                                            <input value="" name="eu_email" type="email" required data-validation-required-message="This field is required" class="form-control" placeholder="">

                                        </div>

                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">Mobile<span class="text-danger">*</span></label>

                                            <input type="text" name="eu_mobile" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required">

                                        </div>
                                        <!--/span-->
                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">Designation</label>

                                            <input type="text" name="eu_designation" value="" class="form-control" placeholder="" />

                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 mt-3">

                                            <button type="button" name="add" id="add" class="btn btn-primary  mt-2">Add</button>

                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div id="dynamic_field">
                                    </div>
                                    <h5 class="card-subtitle">Lead Information</h5>
                                    <div class="row">
                                        <div class="col-lg-3 mb-3">

                                            <label for="example-color-input" class="control-label">Quantity<span class="text-danger">*</span></label><br>

                                            <input id="range_qty" name="quantity" type="text" data-slider-min="1" data-slider-max="50" data-slider-step="1" data-slider-value="1" class="form-control" placeholder="" required data-validation-required-message="This field is required" />
                                        </div>

                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">License Number<span class="text-danger">*</span></label>

                                            <input type="text" name="license_number" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required"/>

                                        </div>

                                        <div class="col-lg-3 mb-3">

                                            <label class="control-label">License End Date<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" readonly required="required" name="license_end_date" id="datepicker-close-date" class="form-control" value="<?= date('Y-m-t') ?>" data-validation-required-message="This field is required"/>
                                                <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar" ></i></span></div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label class="control-label">Attachment <br>(Max: 4MB)<span class="text-danger"></span></label>

                                            <input type="file" name="user_attachment" class="form-control" />
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label class="control-label">Close Date<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" readonly required="required" name="close_date" id="datepicker-close-date1" class="form-control" value="<?= date('Y-m-t') ?>" data-validation-required-message="This field is required"/>
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
            forceParse: false,
            autoclose: !0
        });
    });

    $(function() {
        $('#datepicker-close-date1').datepicker({
            format: 'yyyy-mm-dd',
            forceParse: false,
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