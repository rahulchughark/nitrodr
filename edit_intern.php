<?php include('includes/header.php'); 
include_once('helpers/DataController.php');

$data_oops = new DataController();

if ($_REQUEST['eid']) {

    if ($_SESSION['user_type'] == 'USR' && $_SESSION['user_type'] = 'MNGR') {
        $query = editRawDetails($_REQUEST['eid'],$_SESSION['team_id']);
        //$query .= " and o.team_id=" . $_SESSION['team_id'];
    }else{
        $query = editRawDetails($_REQUEST['eid'],' ');
    }
    //$sql = db_query($query);
    $row = db_fetch_array($query);
    @extract($row);

}

if ($_POST['eid']){
   
    $sql = db_query("select * from raw_leads where id=" . $_REQUEST['eid'] . " limit 1");
    
    $previous_data = db_fetch_array($sql);

    if ($previous_data['source'] != $_POST['source']) {
        $data = [
            'raw_id'         => $_REQUEST['eid'],
            'type'            => 'Lead Source',
            'previous_name'   => $previous_data['source'],
            'modify_name'     => $_POST['source'],
            'created_date'    => date("Y-m-d H:i:s"),
            'created_by'      => $_SESSION['user_id'],
            'lead_id'         => 0,
        ];
        
         $res = $data_oops->insert($data, "lead_modify_log");

    }

    if ($previous_data['address'] != $_POST['address']) {
        
        $data = ['raw_id'=> $_REQUEST['eid'],'type'=> 'Address','previous_name'=> $previous_data['address'],'modify_name'=> $_POST['address'],'created_date'=> date("Y-m-d H:i:s"),'created_by' => $_SESSION['user_id'],'lead_id'=> 0];
        
         $res = $data_oops->insert($data, "lead_modify_log");
        
    }
    if ($previous_data['eu_email'] != $_POST['eu_email']) {
        $data = ['raw_id'=> $_REQUEST['eid'],'type'=> 'Email','previous_name'=> $previous_data['eu_email'],'modify_name'=> $_POST['eu_email'],'created_date'=> date("Y-m-d H:i:s"),'created_by' => $_SESSION['user_id'],'lead_id'=> 0];
        
        $res = $data_oops->insert($data, "lead_modify_log");
       
    }

    if ($previous_data['eu_designation'] != $_POST['eu_designation']) {
        $data = ['raw_id'=> $_REQUEST['eid'],'type'=> 'Designation','previous_name'=> $previous_data['eu_designation'],'modify_name'=> $_POST['eu_designation'],'created_date'=> date("Y-m-d H:i:s"),'created_by' => $_SESSION['user_id'],'lead_id'=> 0];
        
        $res = $data_oops->insert($data, "lead_modify_log");
       
    }

    if ($previous_data['department'] != $_POST['department']) {
        $data = ['raw_id'=> $_REQUEST['eid'],'type'=> 'Department','previous_name'=> $previous_data['department'],'modify_name'=> $_POST['department'],'created_date'=> date("Y-m-d H:i:s"),'created_by' => $_SESSION['user_id'],'lead_id'=> 0];
        
        $res = $data_oops->insert($data, "lead_modify_log");

    }

    if ($previous_data['eu_role'] != $_POST['eu_role']) {
        $data = ['raw_id'=> $_REQUEST['eid'],'type'=> 'Role','previous_name'=> $previous_data['eu_role'],'modify_name'=> $_POST['eu_role'],'created_date'=> date("Y-m-d H:i:s"),'created_by' => $_SESSION['user_id'],'lead_id'=> 0];
        
        $res = $data_oops->insert($data, "lead_modify_log");
    }

    if ($previous_data['eu_mobile'] != $_POST['eu_mobile']) {
        $data = ['raw_id'=> $_REQUEST['eid'],'type'=> 'Mobile','previous_name'=> $previous_data['eu_mobile'],'modify_name'=> $_POST['eu_mobile'],'created_date'=> date("Y-m-d H:i:s"),'created_by' => $_SESSION['user_id'],'lead_id'=> 0];
        
        $res = $data_oops->insert($data, "lead_modify_log");
    }


    if ($previous_data['company_name'] != $_POST['company_name']) {
        $data = ['raw_id'=> $_REQUEST['eid'],'type'=> 'Company Name','previous_name'=> $previous_data['company_name'],'modify_name'=> $_POST['company_name'],'created_date'=> date("Y-m-d H:i:s"),'created_by' => $_SESSION['user_id'],'lead_id'=> 0];
        
        $res = $data_oops->insert($data, "lead_modify_log");
    }

    if ($previous_data['region'] != $_POST['region']) {
        $data = ['raw_id'=> $_REQUEST['eid'],'type'=> 'Region','previous_name'=> $previous_data['region'],'modify_name'=> $_POST['region'],'created_date'=> date("Y-m-d H:i:s"),'created_by' => $_SESSION['user_id'],'lead_id'=> 0];
        
        $res = $data_oops->insert($data, "lead_modify_log");
    }

    if ($previous_data['pincode'] != $_POST['pincode']) {
        $data = ['raw_id'=> $_REQUEST['eid'],'type'=> 'Pincode','previous_name'=> $previous_data['pincode'],'modify_name'=> $_POST['pincode'],'created_date'=> date("Y-m-d H:i:s"),'created_by' => $_SESSION['user_id'],'lead_id'=> 0];
        
        $res = $data_oops->insert($data, "lead_modify_log");
    }

    if ($previous_data['city'] != $_POST['city']) {
        $data = ['raw_id'=> $_REQUEST['eid'],'type'=> 'City','previous_name'=> $previous_data['city'],'modify_name'=> $_POST['city'],'created_date'=> date("Y-m-d H:i:s"),'created_by' => $_SESSION['user_id'],'lead_id'=> 0];
        
        $res = $data_oops->insert($data, "lead_modify_log");
    }

    if ($previous_data['eu_name'] != $_POST['eu_name']) {
        $data = ['raw_id'=> $_REQUEST['eid'],'type'=> 'Contact Person','previous_name'=> $previous_data['eu_name'],'modify_name'=> $_POST['eu_name'],'created_date'=> date("Y-m-d H:i:s"),'created_by' => $_SESSION['user_id'],'lead_id'=> 0];
        
        $res = $data_oops->insert($data, "lead_modify_log");
    }

    if ($previous_data['parent_company'] != $_POST['parent_company']) {
        $data = ['raw_id'=> $_REQUEST['eid'],'type'=> 'Parent Company','previous_name'=> $previous_data['parent_company'],'modify_name'=> $_POST['parent_company'],'created_date'=> date("Y-m-d H:i:s"),'created_by' => $_SESSION['user_id'],'lead_id'=> 0];
        
        $res = $data_oops->insert($data, "lead_modify_log");
    }

    if ($previous_data['quantity'] != $_POST['quantity']) {
        $data = ['raw_id'=> $_REQUEST['eid'],'type'=> 'Quantity','previous_name'=> $previous_data['quantity'],'modify_name'=> $_POST['quantity'],'created_date'=> date("Y-m-d H:i:s"),'created_by' => $_SESSION['user_id'],'lead_id'=> 0];
        
        $res = $data_oops->insert($data, "lead_modify_log");
    }
    if ($previous_data['association_name'] != $_POST['association_name']) {
        $data = ['raw_id'=> $_REQUEST['eid'],'type'=> 'Association Name','previous_name'=> $previous_data['association_name'],'modify_name'=> $_POST['association_name'],'created_date'=> date("Y-m-d H:i:s"),'created_by' => $_SESSION['user_id'],'lead_id'=> 0];
        
        $res = $data_oops->insert($data, "lead_modify_log");
    }
$update_data = [
    'source' => $_POST['source'],
    'company_name' => $_POST['company_name'],
    'parent_company' => $_POST['parent_company'],
    'landline' => $_POST['landline'],
    'region' => $_POST['region'],
    'industry' => $_POST['industry'],
    'sub_industry' => $_POST['sub_industry'],
    'address' => htmlspecialchars($_POST['address'], ENT_QUOTES),
    'pincode' => $_POST['pincode'],
    'state' => $_POST['state'],
    'city' => $_POST['city'],
    'country' => $_POST['country'],
    'eu_name' => $_POST['eu_name'],
    'eu_email' => $_POST['eu_email'],
    'eu_landline' => $_POST['eu_landline'],
    'department' => $_POST['department'],
    'eu_designation' => $_POST['eu_designation'],
    'eu_mobile' => $_POST['eu_mobile'],
    'eu_role' => $_POST['eu_role'],
    'quantity' => $_POST['quantity'],
    'association_name'=>$_POST['association_name'],
    'is_intern' => 1
];
$where = ['id'=>$_POST['eid']];
$update_query = $data_oops->update($update_data,'raw_leads',$where);

    if ($update_query){

        redir("intern_view.php?update=success", true);
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

                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home > Edit Raw Lead</small>
                                    <h4 class="font-size-14 m-0 mt-1">Edit Raw Lead</h4>
                                </div>
                            </div>
							<div class="clearfix"></div>
                        <form method="post" action="#" class="form-horizontal" enctype="multipart/form-data">                     
                        
                        <div data-simplebar class="add_lead">

                                    <h5 class="card-subtitle">Edit Raw Lead:-&nbsp;&nbsp;&nbsp;Product Name:<?= $row['product_name'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Product type:<?= $row['product_type'] ?></h5>
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
                                        <?php if ($product_type_id == 1 || $product_type_id == 2) { ?>
                                            <div class="col-lg-4 mb-3">

                                                <label class="control-label ">Association Name<span class="text-danger"></span></label>

                                                <input type="text" value="<?= $association_name ?>" name="association_name" value="" class="form-control" placeholder="">

                                            </div>
                                        <?php } ?>

                                        <div class="col-lg-4 mb-3">
                                            <label for="example-text-input" class="">Landline Number<span class="text-danger"></span></label>

                                            <input type="number" min="0" name="landline" value="<?= $landline ?>" class="form-control" placeholder="">

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

                                            <label for="example-text-input" class="">Full Name<span class="text-danger">*</span></label>

                                            <input name="eu_name" value="<?= $eu_name ?>" type="text" value="" class="form-control" placeholder="" required data-validation-required-message="This field is required">


                                        </div>

                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Email<span class="text-danger">*</span></label>

                                            <input value="<?= $eu_email ?>" name="eu_email" type="email" class="form-control" placeholder="" required data-validation-required-message="This field is required">


                                        </div>
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Mobile<span class="text-danger">*</span></label>

                                            <input type="number" min="0" name="eu_mobile" value="<?= $eu_mobile ?>" class="form-control" placeholder="" required data-validation-required-message="This field is required">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Landline Number<span class="text-danger"></span></label>

                                            <input type="number" value="<?= $eu_landline ?>" min="0" name="eu_landline" autocomplete="of" class="form-control" placeholder="">

                                        </div>

                                        
                                        <div class="col-lg-4 mb-3">

                                            <label for="example-text-input" class="">Designation<span class="text-danger">*</span></label>

                                            <input type="text" value="<?= $eu_designation ?>" name="eu_designation" class="form-control" placeholder="" required data-validation-required-message="This field is required" />

                                            <!--/span-->
                                        </div>
                                    </div>

                                    
                                    <h5 class="card-subtitle">Lead Information</h5>
                                    <div class="frow">

                                        <?php
                                        if ($product_name == 'Parallel') { ?>
                                            <div class="col-lg-4 mb-3">

                                                <label for="example-text-input" class="">Quantity<span class="text-danger">*</span></label>


                                                <div class="clearfix"> </div>

                                                <input type="text" id="range_quantity" name="quantity" value="<?= ($quantity ? $quantity : 15) ?>">

                                            </div>
                                        <?php } else { ?>
                                            <div class="col-lg-4 mb-3">

                                                <label for="example-text-input" class="">Quantity<span class="text-danger">*</span></label><br>

                                                <input type="text" name="quantity" id="range_qty" value="<?= ($quantity ? $quantity : 1) ?>">


                                            </div>
                                        <?php } ?>

                                    </div>

                                </div>

                                <div class="button-items">
                                <input type="hidden" name="eid" value=<?= $_REQUEST['eid'] ?> />
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

    <?php include('includes/footer.php') ?>
    
    <script>
        $(document).ready(function() {
           // $('.multiselect-feald').multiselect();
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

        $(document).ready(function () {
  $('#multiselect').multiselect({
    buttonWidth: '100%',
    includeSelectAllOption: true,
    nonSelectedText: 'Select an Option' });

    $('#multiselect1').multiselect({
    buttonWidth: '100%',
    includeSelectAllOption: true,
    nonSelectedText: 'Select an Option' });

    $("#range_quantity").ionRangeSlider({
                skin: "flat",
               // type: "double",
                min: 15,
                max: 300,
               // from: 0,
                //to: 15,
                step: 15
            })

            $("#range_qty").ionRangeSlider({
                skin: "flat",
              
                min: 1,
               
            })
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

            function goBack() {
                window.history.go(-1);
            }

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



        $(function() {
            $('.datepicker').datepicker({
               format: 'yyyy-mm-dd',
               forceParse: false,
               startDate: '-3d',
               autoclose:!0
                //startDate: '2017-01-01',
                //autoUpdateInput: false,

            });
            
        });
    </script>

    <script>
    $(document).ready(function() {

            var wfheight = $(window).height();
            $('.add_lead').height(wfheight - 260);

        });
    </script>
