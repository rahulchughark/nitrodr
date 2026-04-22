<?php include('includes/header.php'); ?>

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Intern Leads</small>
                                    <h4 class="font-size-14 m-0 mt-1">Intern Leads</h4>
                                </div>
                            </div>

                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success text-center">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Lead Added Successfully!</h3>
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

                                <?php if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN') { ?>

                                    <a href="export_intern_leads.php?d_from=<?= @$_GET['d_from'] ?>&d_to=<?= @$_GET['d_to'] ?>&dtype=<?= @$_GET['date_type'] ?>">
                                        <button data-toggle="tooltip" data-placement="left" title="" data-original-title="Excel Export" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-download"></i>
                                        </button>
                                    </a>
                                <?php } ?>
                                <button type="button" class="btn btn-xs btn-light ml-1  " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu1 dropdown-menu-right filter_wrap filter_wrap_2" id="filter-container" role="menu">

                                        <form method="get" name="search" class="form-horizontal" role="form">
                                            <?php if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN') {

                                                if (!is_array($users)) {
                                                    $val = $users;
                                                    $users = array();
                                                    $users['0'] = $val;
                                                }
                                                if (!is_array($region)) {
                                                    $val = $region;
                                                    $region = array();
                                                    $region['0'] = $val;
                                                }
                                                if (!is_array($city)) {
                                                    $val = $city;
                                                    $city = array();
                                                    $city['0'] = $val;
                                                }
                                            ?>
                                                <div class="form-group">
                                                    <div class="input-daterange input-group" id="datepicker-close-date">
                                                        <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />
                                                        <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <select name="users[]" class="multiselect_user1 form-control" data-live-search="true" multiple>

                                                        <?php
                                                        $sqlStage = db_query('SELECT * FROM users WHERE user_type="INTERN" and status="Active"  ORDER BY name ASC');

                                                        while ($user_row = db_fetch_array($sqlStage)) { ?>
                                                            <option value="<?= $user_row['id'] ?>" <?= (in_array($user_row['id'], $users) ? 'selected' : '') ?>><?= ucwords($user_row['name']) ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <select name="region[]" id="region" data-live-search="true" multiple class="multiselect_region form-control ">
                                                        <?php
                                                        $query = intern_stateSelect('region');
                                                        while ($row = db_fetch_array($query)) { ?>
                                                            <option value="<?= $row['region'] ?>" <?= (in_array($row['region'], $region) ? 'selected' : '') ?>><?= ucwords($row['region']) ?></option>
                                                        <?php  } ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <select name="city[]" id="city" data-live-search="true" multiple class="multiselect_city form-control ">

                                                        <?php
                                                        $query = intern_citySelect('raw_leads');
                                                        while ($row = db_fetch_array($query)) {
                                                            if (!empty($row['city'])) { ?>
                                                                <option value="<?= $row['city'] ?>" <?= (in_array($row['city'], $city) ? 'selected' : '') ?>><?= ucwords($row['city']) ?></option>
                                                        <?php  }
                                                        } ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <select name="industry" id="industry" class="form-control">
                                                        <option value="">Select Industry</option>
                                                        <option value="DTP" <?= (($_GET['industry'] == 'DTP') ? 'selected' : '') ?>>DTP/Printing</option>
                                                        <option value="Other" <?= (($_GET['industry'] == 'Other') ? 'selected' : '') ?>>Other Segment</option>

                                                    </select>
                                                </div>

                                            <?php } else { ?>
                                                <div class="form-group">
                                                    <label class="control-label">My Leads: </label>
                                                    <br>
                                                    <input class="filled-in chk-col-light-blue" <?= (($_GET['my_leads'] == 'yes') ? 'checked' : '') ?> type="checkbox" value="yes" name="my_leads" id="my_leads" />
                                                    <label for="my_leads">Yes</label>
                                                </div>

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
                                            <th>Submitted By</th>
                                            <th>Quantity</th>
                                            <th>Company Name</th>
                                            <th>Customer Name</th>
                                            <th>Customer Email</th>
                                            <th>Mobile</th>
                                            <th>Industry</th>
                                            <th>Region</th>
                                            <th>State</th>
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


        <?php include('includes/footer.php') ?>

        <script>
            $('#leads').DataTable({
                dom: 'Bfrtip',
                "displayLength": 15,
                buttons: ['pageLength'],
                lengthMenu: [
                    [25, 50, 100, 500, 1000, 10000, 50000, 100000],
                    ['25', '50', '100', '500', '1000', '10000', '50000', '100000']
                ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "get_intern_leads.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                        d.d_from = "<?= $_GET['d_from'] ?>";
                        d.d_to = "<?= $_GET['d_to'] ?>";
                        d.my_leads = "<?= $_GET['my_leads'] ?>";
                        d.users = "<?= implode("','", $users) ?>";
                        d.region = "<?= implode("','", $region) ?>";
                        d.city = "<?= implode("','", $city) ?>";
                        d.industry = "<?= $_GET['industry'] ?>"
                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");

                    }
                }
            });

            $(document).ready(function() {
                $('.multiselect_user1').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Users',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                });
                $('.multiselect_region').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Region',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                });
                $('.multiselect_city').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select City',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                });
            });

            function clear_search() {
                window.location = 'intern_leads.php';
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


            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });
        </script>

        <script>
            jQuery("#search_toogle").click(function() {
                jQuery(".search_form").toggle("fast");
            });

            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 325);
                $("#leads").tableHeadFixer();

            });
        </script>