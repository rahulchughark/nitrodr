<?php include('includes/header.php');
$_GET['d_from']  = htmlspecialchars($_GET['d_from'], ENT_QUOTES, 'UTF-8');
$_GET['d_to']    = htmlspecialchars($_GET['d_to'], ENT_QUOTES, 'UTF-8');
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

                                    <small class="text-muted">Home >Daily Visits</small>
                                    <h4 class="font-size-14 m-0 mt-1">Daily Visits</h4>
                                </div>
                            </div>


                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Daily Visit Added Successfully!
                                </div>
                            <?php } ?>
                            <?php if ($_GET['fail'] == 'ext') { ?>
                                <div class="alert alert-warning">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-warning"><i class="fa fa-check-circle"></i> Error!</h3>Daily quota for conversion exhausted.
                                </div>
                            <?php } ?>
                            <?php if ($_GET['update'] == 'success') { ?>

                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Daily Visit Updated Successfully! 
                                </div>
                            <?php } ?>

                            <div class="table-responsive">

                                <div class="btn-group float-right" role="group" style="margin-top:12px;">

                                    <button type="button" class="btn btn-xs btn-light ml-1  " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                    <div class="dropdown dropdown-lg">

                                        <div class="dropdown-menu1 dropdown-menu-right filter_wrap filter_wrap_2" id="filter-container" role="menu">

                                            <form method="get" id="search-form" name="search" class="form-horizontal" role="form">

                                                <div class="form-group">
                                                    <div class="input-daterange input-group" id="datepicker-close-date">
                                                        <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                        <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                    </div>
                                                </div>

                                                <?php
                                                if (!is_array($call_type)) {
                                                    $val = $call_type;
                                                    $call_type = array();
                                                    $call_type['0'] = $val;
                                                }
                                                if (!is_array($industry)) {
                                                    $val = $industry;
                                                    $industry = array();
                                                    $industry['0'] = $val;
                                                }

                                                ?>
                                                <div class="form-group">
                                                    <select name="call_type[]" id="call_type" class="form-control" data-live-search="true" multiple>

                                                        <?php $query = db_query("select * from call_type");
                                                        while ($row = db_fetch_array($query)) { ?>
                                                            <option <?= (in_array($row['id'], $call_type) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <?php $res = db_query("select * from industry order by name ASC"); ?>
                                                    <select name="industry[]" id="industry" class="form-control" data-live-search="true" multiple>
                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (in_array($row['id'], $industry) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <select name="users" class="form-control">
                                                        <option value="">Select User</option>
                                                        <?php $query = db_query("SELECT * FROM users WHERE team_id =" . $_SESSION['team_id'] . " and status='Active' and role in ('SAL','BO') ORDER BY name ASC");

                                                        while ($row = db_fetch_array($query)) { ?>

                                                            <option <?= (($_GET['user'] == $row['id']) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <!-- <div class="form-group">
                                                    <select name="product" class="product_data form-control">
                                                        <option value="">Select Product</option>
                                                        <?php $query = selectProductPartner($_SESSION['team_id']);
                                                        while ($row = db_fetch_array($query)) { ?>
                                                            <option <?= (($_GET['product'] == $row['id']) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_name']) ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">

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

                                                <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Created By</th>
                                            <th>Lead Type</th>
                                            <th>Company Name</th>
                                            <th>Industry</th>
                                            <th>Date of Creation</th>
                                            <th>Call Subject</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                            <!-- <div class="table-responsive m-t-40">
                                <h5>Converted Daily Visits</h5>
                                <table id="visit_lac" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Converted By</th>
                                            <th>Lead Type</th>
                                            <th>Company Name</th>
                                            <th>Industry</th>
                                            <th>Date of Conversion</th>
                                            <th>Call Type</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div> -->
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <?php include('includes/footer.php') ?>
        <script>
            $('#leads').DataTable({
                dom: 'Bfrtip',
                "displayLength": 15,
                buttons: [
                    <?php if($_SESSION['download_status'] == 1){ ?>
                    'copy', 'csv', 'excel',  'print', 'pageLength',
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    }
                <?php }else{ ?> 'pageLength'  <?php } ?>
                ],
                lengthMenu: [
                    [15, 25, 50, 100, 500, 1000],
                    ['15', '25', '50', '100', '500', '1000']
                ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "get_dvr_partner.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                        d.d_from = "<?= $_GET['d_from'] ?>";
                        d.d_to = "<?= $_GET['d_to'] ?>";
                        d.product = '<?= $_GET['product'] ?>';
                        d.product_type = '<?= $_GET['product_type'] ?>';
                        d.industry = '<?= @implode('","', $_GET['industry']) ?>';
                        d.call_type = '<?= @implode('","', $_GET['call_type']) ?>';
                        d.users = '<?= $_GET['users'] ?>';
                        // etc
                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");

                    }
                }
            });


            $('#visit_lac').DataTable({
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
                url: "get_converted_visit_partner.php", // json datasource
                type: "post", // method  , by default get
                data: function(d) {
                    d.d_from = "<?= htmlspecialchars($_GET['d_from'], ENT_QUOTES, 'UTF-8') ?>";
                    d.d_to = "<?= htmlspecialchars($_GET['d_to'], ENT_QUOTES, 'UTF-8') ?>";
                  //  d.partner = '<?= @implode('","', $_GET['partner']) ?>';
                    d.users = '<?= $users_arr ?>';
                    d.industry = '<?= @implode('","', $_GET['industry']) ?>';
                    d.call_type = '<?= @implode('","', $_GET['call_type']) ?>';
                    //  d.product = '<?= intval($_GET['product']) ?>';
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

            function clear_search() {
                window.location = 'dvr.php';
            }
        </script>
        
        <script>
            $(document).ready(function() {
                //alert("hi");
                $('.product_data').on('change', function() {

                    var productID = $(this).val();

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

            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });

            $(document).ready(function() {
                $('#call_type').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Type of Visit'
                });
                $('#industry').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Industry'
                });
            });

            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 300);
                $("#leads").tableHeadFixer();

            });
        </script>