<?php include('includes/header.php'); ?>

<?php

$_GET['lead'] = intval($_GET['lead']);
$_GET['type'] = intval($_GET['type']);

if ($_GET['lead']) {
    $select_query = selectLeadPartner('tbl_product_pivot', $_GET['type']);
    $row = db_fetch_array($select_query);
    extract($row);
}

@extract($_GET);

//print_r($_POST['r_email']);
if ($_POST['r_email']) {

    cross_login_protect($_SESSION['user_id']);

    $res = db_query("INSERT INTO `raw_leads`(`r_name`,`r_email`, `r_user`, `source`, `company_name`, `parent_company`, `landline`, `industry`, `sub_industry`, `region`, `address`, `pincode`, `state`, `city`, `country`, `eu_name`, `eu_email`, `eu_landline`, `department`, `eu_mobile`, `eu_designation`, `eu_role`, `quantity`, `created_by`, `team_id`, `product_id`, `product_type_id`,association_name) VALUES ('" . htmlspecialchars($_POST['r_name'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['r_email'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['r_user'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['source'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['company_name'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['parent_company'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['landline'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['industry'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['sub_industry'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['region'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['address'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['pincode'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['state'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['city'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['country'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['eu_name'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['eu_email'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['eu_landline'], ENT_QUOTES)  . "','" . htmlspecialchars($_POST['department'], ENT_QUOTES)  . "','" . htmlspecialchars($_POST['eu_mobile'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['eu_designation'], ENT_QUOTES)  . "','" . htmlspecialchars($_POST['eu_role'], ENT_QUOTES) . "','" . htmlspecialchars($_POST['quantity'], ENT_QUOTES)  . "','" . htmlspecialchars($_SESSION['user_id'], ENT_QUOTES)  . "','" . htmlspecialchars($_SESSION['team_id'], ENT_QUOTES)  . "','" . $_GET['lead'] . "','" . $_GET['type'] . "','" . $_POST['association_name'] . "')");

    $lead_id = get_insert_id();

    $activity_log = db_query("insert into activity_log (pid,description,activity_type,call_subject,added_by) values ('" . $lead_id . "','" . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . "','Raw','" . $_POST['call_subject'] . "','" . $_SESSION['user_id'] . "')");

    $partner_name = getSingleresult("select name from partners where id='" . $_SESSION['team_id'] . "'");
    $addTo[] = "kailash.bhurke@arkinfo.in";
    $addCc[] = "pradnya.chaukekar@arkinfo.in"; 

    $setSubject = "[RAW] - New Raw Lead Added to DR Portal from " . $partner_name . " (" . $_POST['r_user'] . ")";
    $body    = "Hi,<br><br> There is new raw lead added to SketchUp DR Portal with details as below:-<br><br>
    <ul>
    <li><b>Partner Name</b> : " . $partner_name . " </li>
    <li><b>Name</b> : " . $_POST['r_user'] . " </li>
    <li><b>Email</b> : " . $_POST['r_email'] . " </li>
    <li><b>Source</b> : " . $_POST['source'] . " </li>
    <li><b>Company Name</b> : " . $_POST['company_name'] . " </li>
    <li><b>Product Name</b> : " . $product_name . " </li>
    <li><b>Product Type</b> : " . $product_type . " </li>
    <li><b>Address</b> : " . htmlspecialchars($_POST['address'], ENT_QUOTES) . " </li>
    <li><b>Mobile</b> : " . $_POST['eu_mobile'] . " </li>
    <li><b>Email</b> : " . $_POST['eu_email'] . " </li>
    <li><b>Quantity</b> : " . $_POST['quantity'] . " </li>
    <li><b>Call Subject</b> : " . $_POST['call_subject'] . " </li>
    <li><b>Remarks</b> : " . $_POST['remarks'] . " </li>
    </ul><br>
    Thanks,<br>
    SketchUp DR Portal

    ";
    sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
    if ($res) {
        redir("raw_leads.php?add=success", true);
    } else {
        redir("raw_leads.php?fail=1", true);
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

                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home</small>
                                    <h4 class="font-size-14 m-0 mt-1">Add Raw Leads</h4>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <form method="post" action="#" class="form-horizontal" enctype="multipart/form-data" id="form_activity">
                                <div data-simplebar class="add_lead">
                                    <h5 class="card-subtitle">Add Raw Lead:-&nbsp;&nbsp;&nbsp;Product Name:<?= $row['product_name'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Product type:<?= $row['product_type'] ?></h5>
                                    <div class=" form-group row" style="display:none">

                                        <input name="r_name" type="text" readonly value="<?= getSingleresult("select name from partners where id='" . $_SESSION['team_id'] . "'") ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">

                                        <input readonly value="<?= $_SESSION['email'] ?>" name="r_email" type="email" required data-validation-required-message="This field is required" class="form-control form-control" placeholder="">

                                        <input name="r_user" readonly value="<?= $_SESSION['name'] ?>" type="text" class="form-control" placeholder="" required data-validation-required-message="This field is required">



                                    </div>


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

                                            <label for="example-text-input" class="">Company Name<span class="text-danger">*</span></label>

                                            <input type="text" value="<?= $company_name ?>" name="company_name" value="" class="form-control" placeholder="" required pattern="[A-Za-z0-9 &'\s]+" data-validation-required-message="This field is required">

                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Parent Company<span class="text-danger"></span></label>

                                            <input type="text" value="<?= $parent_company ?>" name="parent_company" value="" class="form-control" placeholder="" pattern="[A-Za-z0-9 &'\s]+">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <?php if ($_GET['type'] == 1 || $_GET['type'] == 2) { ?>
                                            <div class="col-lg-4 mb-3">

                                                <label class="control-label ">Association Name<span class="text-danger"></span></label>

                                                <input type="text" value="<?= $association_name ?>" name="association_name" value="" class="form-control" placeholder="">

                                            </div>
                                        <?php } ?>

                                        <div class="col-lg-4 mb-3">
                                            <label for="example-text-input" class="">Landline Number<span class="text-danger"></span></label>

                                            <input type="number" <?= $landline ?> min="0" name="landline" value="" class="form-control" placeholder="">

                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Country<span class="text-danger">*</span></label>

                                            <input type="text" name="country" value="India" class="form-control" placeholder="" required data-validation-required-message="This field is required">

                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Region<span class="text-danger">*</span></label>

                                            <select name="region" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <?php $res = db_query("select * from region where status=1");
                                                while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($region == $row['region']) ? 'selected' : '') ?> value="<?= $row['region'] ?>"><?= $row['region'] ?></option>
                                                <?php } ?>



                                            </select>

                                            <!--/span-->
                                        </div>


                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Industry<span class="text-danger">*</span></label>

                                            <?php $res = db_query("select * from industry order by name ASC");

                                            //print_r($res); die;

                                            ?>
                                            <select name="industry" id="industry" class="form-control" required data-validation-required-message="This field is required">
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
                                                    <label class="control-label">Sub Industry<span class="text-danger">*</span></label>
                                                    <select name="sub_industry" class="form-control" required data-validation-required-message="This field is required" id="subind">';
                                                    while ($row = db_fetch_array($query)) {
                                                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                                    }
                                                    echo '</select>';
                                                }
                                            } ?>
                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">State<span class="text-danger">*</span></label>

                                            <?php $res = db_query("select * from states");

                                            //print_r($res); die;

                                            ?>
                                            <select name="state" id="state" class="form-control" required data-validation-required-message="This field is required">
                                                <option value="">---Select---</option>
                                                <?php while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($state == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                <?php } ?>
                                            </select>

                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">City<span class="text-danger">*</span></label>

                                            <input type="text" value="<?= $city ?>" name="city" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required" pattern="[A-Za-z\s]+" />

                                        </div>
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Pin Code<span class="text-danger">*</span></label>

                                            <input type="number" value="<?= $pincode ?>" min="0" name="pincode" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required" />

                                        </div>
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Address<span class="text-danger">*</span></label>

                                            <textarea name="address" value="" rows="5" class="form-control" placeholder="" required data-validation-required-message="This field is required"><?= $address ?></textarea>

                                        </div>

                                    </div>


                                    <h5 class="card-subtitle">Decision Maker/Proprietor/Director/End User Details</h5>
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Full Name</label>

                                            <input name="eu_name" value="<?= $eu_name ?>" type="text" value="" class="form-control" placeholder="">


                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Email</label>

                                            <input value="<?= $eu_email ?>" name="eu_email" type="email" class="form-control" placeholder="">


                                        </div>
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Mobile</label>

                                            <input type="number" min="0" name="eu_mobile" value="<?= $eu_mobile ?>" class="form-control" placeholder="">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Landline Number<span class="text-danger"></span></label>

                                            <input type="number" value="<?= $eu_landline ?>" min="0" name="eu_landline" autocomplete="of" class="form-control" placeholder="">

                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Department<span class="text-danger"></span></label>

                                            <input type="text" name="department" value="<?= $department ?>" class="form-control" placeholder="">

                                        </div>
                                        <!--/span-->
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Designation</label>

                                            <input type="text" value="<?= $eu_designation ?>" name="eu_designation" class="form-control" placeholder="" />

                                            <!--/span-->
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Role<span class="text-danger"></span></label>

                                            <select name="eu_role" class="form-control" placeholder="">
                                                <option value="">---Select---</option>
                                                <option <?= (($eu_role == 'User') ? 'selected' : '') ?> value="User">User</option>
                                                <option <?= (($eu_role == 'Economy Buyer') ? 'selected' : '') ?> value="Economy Buyer">Economy Buyer</option>
                                                <option <?= (($eu_role == 'Tech. Buyer') ? 'selected' : '') ?> value="Tech. Buyer">Tech. Buyer</option>
                                                <option <?= (($eu_role == 'Decision Maker') ? 'selected' : '') ?> value="Decision Maker">Decision Maker</option>


                                            </select>

                                        </div>
                                    </div>
                                    <h5 class="card-subtitle">Lead Information</h5>

                                    <div class="frow">


                                        <?php
                                        if ($product_name == 'Parallel') { ?>
                                            <div class="col-lg-4 mb-3">

                                                <label for="example-text-input" class="">Quantity<span class="text-danger">*</span></label>


                                                <div class="clearfix"> </div>

                                                <input type="text" id="range_quantity" name="quantity">

                                            </div>
                                        <?php } else { ?>
                                            <div class="col-lg-4 mb-3">

                                                <label for="example-text-input" class="">Quantity<span class="text-danger">*</span></label><br>

                                                <input type="text" name="quantity" id="range_qty">


                                            </div>
                                        <?php } ?>

                                    </div>

                                </div>

                                <div class="button-items">
                                    <button type="submit" class="btn btn-primary  mt-2">Submit</button>
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

    <!-- <div id="myModal" class="modal fade" role="dialog">
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
       <form action="#" method="post" class="form p-t-20" > 
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="example-text-input">Call Subject</label>
              <select required id="call_subject" name="call_subject" class="form-control">
                <option value="">---Select---</option>
                <?php
                if ($row_data['role'] == 'TC') {

                    $call_query = db_query("select * from call_subject where subject not like '%visit%' order by id asc");
                } else {
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
    </div> -->

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

        $(document).ready(function() {
            $('#check').click(function() {
                //alert($(this).is(':checked'));
                $(this).is(':checked') ? $('#pwd').attr('type', 'text') : $('#pwd').attr('type', 'password');
            });

            $("#range_quantity").ionRangeSlider({
                skin: "flat",
                min: 15,
                max: 300,
                step: 15
            })

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
    <script>
        $(document).ready(function() {

            var wfheight = $(window).height();
            $('.add_lead').height(wfheight - 260);

        });
    </script>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<!-- <script>

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
</script> -->