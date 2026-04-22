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

                                    <small class="text-muted">Home >Raw Leads</small>
                                    <h4 class="font-size-14 m-0 mt-1">Raw Leads</h4>
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

                                <button type="button" class="btn btn-xs btn-light ml-1  " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu1 dropdown-menu-right filter_wrap filter_wrap_2" id="filter-container" role="menu">

                                        <form method="get" name="search" class="form-horizontal" role="form">
                                            <?php if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'RADMIN' || $_SESSION['user_type'] == 'SALES MNGR') {

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


                                                    if ($_GET['partner']) {
                                                    ?>
                                                        <select name="users[]" class="multiselect_user1 form-control" data-live-search="true" multiple>

                                                            <?php
                                                            $sqlStage = db_query('SELECT * FROM users WHERE team_id in ("' . implode('", "', $_GET['partner']) . '") and status="Active"  ORDER BY name ASC');

                                                            while ($user_row = db_fetch_array($sqlStage)) { ?>
                                                                <option value="<?= $user_row['id'] ?>" <?= (in_array($user_row['id'], $users) ? 'selected' : '') ?>><?= ucwords($user_row['name']) ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } else { ?>
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
                                            if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'SALES MNGR' || $_SESSION['user_type'] == 'CLR' || $_SESSION['user_type'] == 'ISS MNGR') { ?>
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
                                            <?php } ?>
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

                                            <div class="form-group">
                                                <div class="input-daterange input-group" id="datepicker-close-date">
                                                    <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />
                                                    <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                </div>
                                            </div>

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
                                            <th data-sortable="true">Account name</th>
                                            <th data-sortable="true">Parent Company</th>
                                            <th data-sortable="true">Customer Name</th>
                                            <th data-sortable="true">Industry</th>
                                            <th data-sortable="true">Contact Number</th>
                                            <th data-sortable="true">Email Id</th>
                                            <th data-sortable="true">City</th>
                                            <th data-sortable="true">State</th>
                                            <th data-sortable="true">Quantity</th>
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
                dom: 'Bfrtip',
                "displayLength": 15,
                buttons: ['pageLength'],
                lengthMenu: [
                    [15, 25, 50, 100, 500, 1000],
                    ['15', '25', '50', '100', '500', '1000']
                ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "get_raw_leads_interns.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                        d.d_from = "<?= $_GET['d_from'] ?>";
                        d.d_to = "<?= $_GET['d_to'] ?>";
                        d.partner = '<?= implode('","', $partner) ?>';
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
                window.location = 'raw_leads_intern.php';
            }



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