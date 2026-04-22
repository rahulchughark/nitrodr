<?php include('includes/header.php');
ini_set('max_execution_time', 0);

if ($_POST['save_csv']) {
    $filename = $_FILES["file"]["tmp_name"];
    if ($_FILES["file"]["size"] > 0) {
        $row = 1;
        $file = fopen($filename, "r");
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
            if ($row != 1) {

                $getData[26] = $getData[26] ? $getData[26] : '';

                $sql = "INSERT INTO `raw_leads`(r_name,r_email,r_user,created_by,product_id,product_type_id,`source`, `company_name`, `parent_company`, `industry`, `sub_industry`, `region`, `address`, `pincode`, `state`, `city`, `country`, `eu_name`, `eu_email`, `eu_landline`, `department`, `eu_mobile`, `eu_designation`, `eu_role`, `quantity`, `team_id`,association_name) VALUES ('" . $getData[0] . "','" . htmlspecialchars($getData[1], ENT_QUOTES) . "','" . htmlspecialchars($getData[2], ENT_QUOTES) . "','" . intval($getData[3]) . "','" . intval($getData[4]) . "','" . htmlspecialchars($getData[5], ENT_QUOTES) . "','" . htmlspecialchars($getData[6], ENT_QUOTES) . "','" . htmlspecialchars($getData[7], ENT_QUOTES) . "','" . htmlspecialchars($getData[8], ENT_QUOTES) . "','" . htmlspecialchars($getData[9], ENT_QUOTES) . "','" . htmlspecialchars($getData[10], ENT_QUOTES) . "','" . htmlspecialchars($getData[11], ENT_QUOTES) . "','" . htmlspecialchars($getData[12], ENT_QUOTES) . "','" . htmlspecialchars($getData[13], ENT_QUOTES) . "','" . htmlspecialchars($getData[14], ENT_QUOTES) . "','" . htmlspecialchars($getData[15], ENT_QUOTES) . "','" . htmlspecialchars($getData[16], ENT_QUOTES) . "','" . htmlspecialchars($getData[17], ENT_QUOTES) . "','" . htmlspecialchars($getData[18], ENT_QUOTES) . "','" . htmlspecialchars($getData[19], ENT_QUOTES) . "','" . htmlspecialchars($getData[20], ENT_QUOTES) . "','" . htmlspecialchars($getData[21], ENT_QUOTES) . "','" . htmlspecialchars($getData[22], ENT_QUOTES) . "','" . htmlspecialchars($getData[23], ENT_QUOTES) . "','" . intval($getData[24]) . "','" . intval($getData[25]) . "','" . htmlspecialchars($getData[26], ENT_QUOTES) . "')";
                $result = db_query($sql);
            }
            $row++;
        }
        echo "<script type=\"text/javascript\">
          alert(\"CSV File has been successfully Imported.\");
          window.location = \"raw_leads.php\"
        </script>";
        fclose($file);
    } else {
        echo "<script type=\"text/javascript\">
          alert(\"An error occured while uploading file.\");
          window.location = \"raw_leads.php\"
        </script>";
    }
}

?>
<style>
    .dt-buttons {
        margin-top: 30px;
        margin-left: -175px;
        margin-bottom: 10px;
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

                                    <small class="text-muted">Home >Raw Leads</small>
                                    <h4 class="font-size-14 m-0 mt-1">Raw Leads</h4>
                                </div>
                            </div>

                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success text-center">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i>Raw Lead Added Successfully!</h3>
                                </div>
                            <?php } ?>
                            <?php if ($_GET['fail'] == 'ext') { ?>
                                <div class="alert alert-warning text-center">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-warning"><i class="fa fa-check-circle"></i> Error! Daily quota for leads exhausted.</h3>
                                </div>
                            <?php } ?>
                            <?php if ($_GET['update'] == 'success') { ?>
                                <div class="alert alert-success text-center">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Lead Updated Successfully!</h3>
                                </div>
                            <?php } ?>



                            <div class="btn-group float-right" role="group" style="margin-top:12px;">

                                <?php if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'RADMIN') { ?>
                                    <div class="">
                                        <a href="javascript:void(0);" onclick="show_import()"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="Import Raw Leads" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-plus "></i></button></a>
                                    </div>
                                    <div class="">

                                        <a href="export_raw_leads.php?d_from=<?= @$_GET['d_from'] ?>&d_to=<?= @$_GET['d_to'] ?>">
                                            <button data-toggle="tooltip" data-placement="left" title="" data-original-title="Excel Export" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-download"></i></button></a>

                                    </div>
                                <?php } else if ($_SESSION['user_type'] == 'MNGR' || $_SESSION['user_type'] == 'USR') { ?>
                                    <button type="button" onclick="select_product_raw()" class="btn btn-xs btn-light ml-1 waves-effect waves-light" data-toggle="modal" data-animation="bounce" data-target=".bs-example-modal-center"><i class="fa fa-plus mr-1"></i></button>

                                    <!-- <a href="javascript:void(0);"><button onclick="select_product_raw()" data-toggle="tooltip" data-placement="left" title="" data-original-title="Add Raw Leads" class="right-side bottom-right waves-effect waves-light btn-success btn btn-circle btn-lg pull-right m-l-10"><i class="ti-plus text-white"></i></button></a> -->

                                <?php } ?>

                                <button type="button" class="btn btn-xs btn-light ml-1  " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu1 dropdown-menu-right filter_wrap filter_wrap_2" id="filter-container" role="menu">

                                        <form method="get" name="search" class="form-horizontal" role="form">
                                            <?php 
                                            if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'RADMIN' || $_SESSION['user_type'] == 'SALES MNGR'|| $_SESSION['user_type'] == 'CLR' || $_SESSION['user_type'] == 'ISS MNGR') {

                                                if ($_SESSION['sales_manager'] != 1) {
                                                    $res = db_query("select * from partners where status='Active'");
                                                } else {
                                                    $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active'");
                                                }
                                                if (!is_array($partner)) {
                                                    $val = $partner;
                                                    $partner = array();
                                                    $partner['0'] = $val;
                                                    $partner_flag = 1;
                                                }
                                            ?>
                                                <div class="form-group">
                                                    <select name="partner[]" id="partner" class="multiselect_partner form-control" data-live-search="true" multiple>

                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (in_array($row['id'], $partner) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <?php
                                                    if ($_GET['partner'] && ($_SESSION['user_type'] != 'CLR' && $_SESSION['user_type'] != 'ISS MNGR')) {
                                                    ?>
                                                        <select name="users[]" class="multiselect_user1 form-control" data-live-search="true" multiple>

                                                            <?php
                                                            $sqlStage = db_query('SELECT * FROM users WHERE team_id in ("' . implode('", "', $_GET['partner']) . '") and status="Active"  ORDER BY name ASC');

                                                            while ($user_row = db_fetch_array($sqlStage)) { ?>
                                                                <option value="<?= $user_row['id'] ?>" <?= (in_array($user_row['id'], $users) ? 'selected' : '') ?>><?= ucwords($user_row['name']) ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } else if($_SESSION['user_type'] != 'CLR' && $_SESSION['user_type'] != 'ISS MNGR') { ?>

                                                        <div id="users"></div>
                                                        
                                                    <?php } ?>
                                                </div>
                                            <?php }

                                            if ($_SESSION['user_type'] == 'MNGR') { ?>
                                                <div class="form-group">
                                                    <select name="users[]" class="multiselect_user1 form-control" data-live-search="true" multiple>

                                                        <?php $query = db_query("SELECT * FROM users WHERE team_id = " . $_SESSION['team_id'] . " and status='Active'  ORDER BY name ASC");

                                                        while ($row = db_fetch_array($query)) { ?>
                                                            <option <?= (in_array($row['id'], $users) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            <?php } 
                                            if($_SESSION['user_type'] != 'CLR' && $_SESSION['user_type'] != 'ISS MNGR'){

                                            if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'SALES MNGR') { ?>
                                                <div class="form-group">
                                                    <select name="product" class="product_data form-control">
                                                        <option value="">Select Product</option>
                                                        <?php $query = selectProduct('tbl_product');
                                                        while ($row = db_fetch_array($query)) { ?>
                                                            <option <?= (($_GET['product'] == $row['id']) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_name']) ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            <?php } else { ?>
                                                <div class="form-group">
                                                    <select name="product" class="product_data form-control">
                                                        <option value="">Select Product</option>
                                                        <?php $query = selectProductPartner($_SESSION['team_id']);
                                                        while ($row = db_fetch_array($query)) { ?>
                                                            <option <?= (($_GET['product'] == $row['id']) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_name']) ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            <?php } }?>
                                            <div class="form-group">
                                                <?php if ($_GET['product']) { ?>
                                                    <select name="product_type[]" data-live-search="true" multiple class="multiselect_type form-control">
                                                        <?php $query = selectProductType('tbl_product_pivot', $_GET['product']);
                                                        while ($row = db_fetch_array($query)) { ?>
                                                            <option <?= (in_array($row['id'], $product_type) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_type']) ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } else { ?>
                                                    <div id="product_type"></div>

                                                <?php } ?>
                                            </div>

<?php if($_SESSION['user_type'] == 'CLR' || $_SESSION['user_type'] == 'ISS MNGR'){ ?>
    <div class="form-group">
                                                    <select name="date_type" class="form-control" id="date_type">
                                                        <option value="">Select Date Type</option>
                                                        <option value="created" <?= (($_GET['date_type'] == 'created') ? 'selected' : '') ?>>Created Date</option>
                                                        <option <?= (($_GET['date_type'] == 'approved_date') ? 'selected' : '') ?> value="approved_date">Qualified Date</option>
                                                    </select>
                                                </div>

    <div class="form-group">
                                                <div class="input-daterange input-group" id="datepicker-close-date">
                                                    <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />
                                                    <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                </div>
                                            </div>
<?php }else{ ?>
                                            <div class="form-group">
                                                <div class="input-daterange input-group" id="datepicker-close-date">
                                                    <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />
                                                    <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                </div>
                                            </div>
<?php } ?>

                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>

                                        </form>

                                    </div>
                                </div>

                            </div>

                            <div class="table-responsive">
                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Lead Source</th>
                                            <th>Reseller Name</th>
                                            <th>Submitted By</th>
                                            <th>Submitted Email</th>
                                            <th>Quantity</th>
                                            <th>Product Name</th>
                                            <th>Product Type</th>
                                            <th>Company Name</th>
                                            <th>Customer Name</th>
                                            <th>Mobile</th>
                                            <th>Date of Submission</th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>

                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <div id="myModal1" class="modal" role="dialog">
        </div>

        <div id="product_raw" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">">&times;</button>
                    </div>
                    <form class="product_raw">
                        <div class="modal-body">
                            <div class="col-md-3">
                                <span>Product</span>
                      <button type="button" class="close" data-dismiss="modal
                                <select name="product" class="form-control" id="productRaw">
                                    <option value="">---Select---</option>
                                    <?php $res_product = selectProductPartner($_SESSION['team_id']);
                                    while ($row = db_fetch_array($res_product)) { ?>
                                        <option value=<?= $row['id']; ?>><?= $row['product_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div id="productRawType">

                            </div>

                             </div>
                            <div class="col-md-12">

                        <h4 class="modal-title">Select Product</h4>
                         </div>
                    </form>
                    <div class="modal-footer justify-content-center " style="display: none;">
                        <button type="button" name="submit" id="submit_raw" class="btn btn-primary">Submit</button>
                    </div>
                </div>

            </div>

        </div>
        <?php include('includes/footer.php') ?>
        <?php
        if (count($partner) > 1) {
            $partner_arr = implode(",", $partner);
            //print_r($campaign_arr);die;
        }
        //print_r($partner);
        if (count($product_type) > 1) {
            $product_type_arr = implode('","', $product_type);
            //print_r($campaign_arr);die;
        } else if (!$product_type_flag) {
            $product_type_arr = $_GET['product_type'][0];
        } else {
            $product_type_arr = $_GET['product_type'];
        }

        if (count($users) > 1) {
            $users_arr = implode('","', $users);
            //print_r($campaign_arr);die;
        } else if (!$users_flag) {
            $users_arr = $_GET['users'][0];
        } else {
            $users_arr = $_GET['users'];
        }
        //print_r($users_arr);
        ?>

        <script>
            $('#leads').DataTable({
                "dom": '<"top"if>Brt<"bottom"ip><"clear">',
                "displayLength": 15,
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                ],
                lengthMenu: [
                    [15, 25, 50, 100, 500, 1000],
                    ['15', '25', '50', '100', '500', '1000']
                ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "get_raw_leads.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                        d.date_type ="<?= $_GET['date_type']?>";
                        d.d_from = "<?= $_GET['d_from'] ?>";
                        d.d_to = "<?= $_GET['d_to'] ?>";
                        d.partner = '<?= @implode('","', $partner) ?>';
                        d.product = '<?= $_GET['product'] ?>';
                        d.product_type = '<?= $product_type_arr ?>';
                        d.users = '<?= $users_arr ?>';
                        // d.custom = $('#myInput').val();
                        // etc
                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");

                    }
                }
            });



            function clear_search() {
                window.location = 'raw_leads.php';
            }

            //product module start

            function select_product_raw() {
                $('#product_raw').modal('show');
                //$('.modal-footer').hide();
            }

            $('#productRaw').on('change', function() {

                var productID = $(this).val();
                //alert(productID);
                if (productID) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxProduct.php',
                        data: 'product_raw=' + productID,
                        success: function(response) {
                            $('.modal-footer').show();
                            $('#productRawType').html(response);

                        },
                        error: function() {
                            $('#productRawType').html('There was an error!');
                        }
                    });
                } else {
                    $('#productRawType').html('<option value="" style="color:red">Select product first</option>');
                }
            });

            $('#submit_raw').on('click', function() {
                //alert('clicked');
                if (($('#product_type_raw').val() == " ") || ($('#product_type_raw').val() == undefined)) {
                    swal('Select product type!!');
                    return true;
                }

                var product = $('#productRaw').val();
                var product_type = $('#product_type_raw').val();
                //alert(product_type);
                window.location = 'add_raw.php?lead=' + product + '&type=' + product_type;

            });

            //end product module

            $(document).ready(function() {
                $('.product_data').on('change', function() {
                    // alert("hi");
                    var productID = $(this).val();
                    if (productID) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajaxProductTypeAdmin.php',
                            data: 'product_id=' + productID,
                            success: function(response) {
                                $('#product_type').html(response);

                            },
                        });
                    }
                });
            });

            $(document).ready(function() {
                $('#partner').on('change', function() {
                    //alert("hi");
                    var partnerID = $(this).val();
                    if (partnerID) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajaxusers.php',
                            data: 'partner=' + partnerID,
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
                    //startDate: '-3d',
                    autoclose: !0

                });

            });
        </script>

        <script>
            $(document).ready(function() {
                $('.multiselect_type').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Product Type',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                });
                $('.multiselect_user1').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Users',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                });
                $('.multiselect_partner').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Partner',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                });
            });

            jQuery("#search_toogle").click(function() {
                jQuery(".search_form").toggle("fast");
            });

            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 325);
                $("#leads").tableHeadFixer();

            });

            function stage_change(ids, id) {
                //$('.preloader').show();
                $.ajax({
                    type: 'POST',
                    url: 'stage_change.php',
                    data: {
                        pid: id,
                        ids: ids
                    },
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function chage_stage(stage, id, ids) {

                //alert(stage + '' +id);


                if (stage != '') {
                    $('#myModal1').modal('hide');
                    $.ajax({
                        type: 'post',
                        url: 'change_stage.php',
                        data: {
                            stage: stage,
                            lead_id: id
                        },
                        success: function(res) {
                            if (res == 'success') {
                                // alert(ids);
                                swal({
                                    title: "Done!",
                                    text: "Stage changed Successfully.",
                                    type: "success"
                                }, function() {
                                    $('#myModal1').modal('hide');
                                    var idss = "'but" + id + "'";
                                    var link = stage + '<a href="javascript:void(0)" title="Change Stage" id=but' + id + ' onclick="stage_change(' + idss + ',' + id + ')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>'
                                    $("#" + ids).parent().html(link);

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

            function show_import() {
                $.ajax({
                    type: 'POST',
                    url: 'import_raw.php',
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });

            }
        </script>