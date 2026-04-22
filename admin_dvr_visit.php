<?php include('includes/header.php');
admin_page();

$_GET['d_from']  = htmlspecialchars($_GET['d_from'], ENT_QUOTES, 'UTF-8');
$_GET['d_to']    = htmlspecialchars($_GET['d_to'], ENT_QUOTES, 'UTF-8');
//$_GET['partner'] = intval($_GET['partner']);
//$_GET['users']   = intval($_GET['users']);
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
                            <div class="media bredcrum-title">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/ict-logo.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Daily Visits</small>
                                    <h4 class="font-size-14 m-0 mt-1">Daily Visits Logs</h4>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                                        <form method="get" name="search">
                                            <?php
                                            if (!is_array($partner)) {
                                                $val = $partner;
                                                $partner = array();
                                                $partner['0'] = $val;
                                            }
                                            if ($_SESSION['sales_manager'] != 1) {
                                                $res = db_query("select * from partners where status='Active'");
                                            } else {
                                                $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active'");
                                            }
                                            //print_r($res); die;

                                            ?>
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <div class="input-daterange input-group" id="datepicker-close-date">
                                                        <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                        <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <select name="partner[]" id="partner" class="multiselect_partner form-control" data-live-search="true" multiple>
                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (in_array($row['id'], $partner) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <?php if ($_GET['partner']) { ?>
                                                        <select name="users[]" class="multiselect_user1 form-control" data-live-search="true" multiple>

                                                            <?php $query = db_query('SELECT * FROM users WHERE team_id in ("' . implode('", "', $_GET['partner']) . '") and status="Active" and role in ("BO","SAL") ORDER BY name ASC');
                                                            while ($row = db_fetch_array($query)) { ?>
                                                                <option <?= (in_array($row['id'], $users) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } else { ?>
                                                        <div id="users">
                                                        </div>
                                                    <?php } ?>

                                                </div>

                                                <?php
                                                if (!is_array($call_subject)) {
                                                    $val = $call_subject;
                                                    $call_subject = array();
                                                    $call_subject['0'] = $val;
                                                }
                                                if (!is_array($industry)) {
                                                    $val = $industry;
                                                    $industry = array();
                                                    $industry['0'] = $val;
                                                }

                                                ?>
                                                <div class="form-group col-md-4">
                                                    <select name="call_subject[]" id="call_subject" class="form-control" data-live-search="true" multiple>

                                                        <?php $query = db_query("select * from call_subject where subject like '%visit%'");
                                                        while ($row = db_fetch_array($query)) { ?>
                                                            <option <?= (in_array($row['subject'], $call_subject) ? 'selected' : '') ?> value="<?= $row['subject'] ?>"><?= ucwords($row['subject']) ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <?php $res = db_query("select * from industry order by name ASC"); ?>
                                                    <select name="industry[]" id="industry" class="form-control" data-live-search="true" multiple>
                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (in_array($row['id'], $industry) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <!-- <div class="form-group col-md-4">
                                                    <select name="ark_users" id="ark_users" class="form-control">
                                                        <option value="">Select ARK Users</option>
                                                        <?php $res = db_query("select * from users where team_id=45 and status='Active'");
                                                        while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (($_GET['ark_users'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div> -->




                                                <!-- <div class="form-group col-md-4">
                                                    <select name="product" class="product_data form-control">
                                                        <option value="">Select Product</option>
                                                        <?php $query = selectProduct('tbl_product');
                                                        while ($row = db_fetch_array($query)) { ?>
                                                            <option <?= (($_GET['product'] == $row['id']) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_name']) ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <?php if ($_GET['product']) { ?>
                                                        <select name="product_type" id="product_type" class="form-control">
                                                            <option value="">Select Product Type</option>
                                                            <?php $query = selectProductType('tbl_product_pivot', $_GET['product']);
                                                            while ($row = db_fetch_array($query)) { ?>
                                                                <option <?= (($_GET['product_type'] == $row['id']) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_type']) ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } else { ?>
                                                        <select name="product_type" id="product_type" class="form-control">
                                                            <option value="">Select Product Type</option>
                                                        </select>
                                                    <?php } ?>
                                                </div> -->

                                                <div class="form-group col-md-2">
                                                    <button type="submit" class="btn btn-primary" id="search"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                    <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                            <div class="table-responsive">
                            <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>VAR Name</th>
                                            <th>Logged By</th>
                                            <th>Lead Type</th>
                                            <th>Company Name</th>
                                            <th>Industry</th>
                                            <th>Date of Logging</th>
                                            <th>Call Subject</th>
                                        </tr>
                                    </thead>


                                </table>
                            </div>

                        </div>

                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->

    <?php include('includes/footer.php');

    if (count($users) > 1) {
        $users_arr = implode('","', $users);
        //print_r($campaign_arr);die;
    } else if (!$users_flag) {
        $users_arr = $_GET['users'][0];
    } else {
        $users_arr = $_GET['users'];
    }
    ?>
    <script>
        $('#leads').DataTable({

            dom: 'Bfrtip',
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
                url: "get_visit_admin.php", // json datasource
                type: "post", // method  , by default get
                data: function(d) {
                    d.d_from = "<?= htmlspecialchars($_GET['d_from'], ENT_QUOTES, 'UTF-8') ?>";
                    d.d_to = "<?= htmlspecialchars($_GET['d_to'], ENT_QUOTES, 'UTF-8') ?>";
                    d.partner = '<?= implode('","', $_GET['partner']) ?>';
                    d.users = '<?= $users_arr ?>';
                    d.industry = '<?= implode('","', $_GET['industry']) ?>';
                    d.call_subject = '<?= implode('","', $_GET['call_subject']) ?>';
                    // d.product = '<?= intval($_GET['product']) ?>';
                    // d.product_type = '<?= intval($_GET['product_type']) ?>';
                    // d.ark_users = '<?= intval($_GET['ark_users']) ?>'
                    // d.custom = $('#myInput').val();
                    // etc
                },

                error: function() { // error handling
                    $(".employee-grid-error").html("");
                    $("#visit_lac").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                    $("#leads_processing").css("display", "none");

                }
            }
        });
        // if (session.getAttribrute("user_type") == "REVIEWER") {

        //     button.visible(false);
        // }
        // if ($_SESSION['user_type'] == "REVIEWER") {
        //     dataTable.buttons(0).disable(true);
        // }

        // // Order by the grouping
        // $('#visit_lac tbody').on('click', 'tr.group', function() {
        //     var currentOrder = table.order()[0];
        //     if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
        //         table.order([2, 'desc']).draw();
        //     } else {
        //         table.order([2, 'asc']).draw();
        //     }
        // });



        $(document).ready(function() {
            $('.product_data').on('change', function() {
                //alert('abc');
                var productID = $(this).val();
                //alert(productID);
                if (productID) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxProductTypeAdmin.php',
                        data: 'product=' + productID,
                        success: function(html) {
                            $('#product_type').html(html);

                        },
                    });
                }
            });
        });

        function clear_search() {
            window.location = 'admin_dvr_visit.php';
        }

        $(function() {
            $('#datepicker-close-date').datepicker({
                format: 'yyyy-mm-dd',
                //startDate: '-3d',
                autoclose: !0

            });

        });

        $(document).ready(function() {
            $('.multiselect_partner').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Partner',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn:true
            });
            $('.multiselect_user1').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Submitted By',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn:true
            });
            $('#call_subject').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Subject of Visit',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn:true
            });
            $('#industry').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Industry',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn:true
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#partner').on('change', function() {
                //alert("hi");
                var partnerID = $(this).val();
                if (partnerID) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxusers.php',
                        data: 'partner_visit_id=' + partnerID,
                        success: function(html) {
                            //alert(html);
                            $('#users').html(html);
                        }
                    });
                }
            });
        });
    </script>


    <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 300);
                $("#leads").tableHeadFixer();

            });
    </script>