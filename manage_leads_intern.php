<?php include('includes/header.php'); ?>

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

                                    <small class="text-muted">Home >Leads</small>
                                    <h4 class="font-size-14 m-0 mt-1">Leads</h4>
                                </div>
                            </div>

                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Lead Added Successfully!
                                </div>
                            <?php } ?>
                            <?php if ($_GET['update'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Lead Updated Successfully!
                                </div>
                            <?php } ?>
                            <?php if ($_GET['m'] == 'nodata') { ?>
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-danger"><i class="fa fa-check-circle"></i> Error!</h3> No data found!
                                </div>
                            <?php } ?>

                            <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>


                                <!-- <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">

                                        <form method="get" name="search" class="form-horizontal" role="form">
                                            <input type="hidden" name="counter" value="<?= $_GET['counter'] ?>">
                                            <input type="hidden" name="d_from" value="<?= $_GET['d_from'] ?>">
                                            <input type="hidden" name="d_to" value="<?= $_GET['d_to'] ?>">
                                            <input type="hidden" name="progress" value="<?= $_GET['progress'] ?>">
                                            <div class="form-group ">
                                                <select name="product" class="product_data form-control">
                                                    <option value="">Select Product</option>
                                                    <?php $query = selectProduct('tbl_product');
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
                                                    <div id="product_type">
                                                    <select name="product_type" class="form-control">
                                                        <option value="">Select Product Type</option>
                                                    </select>
                                                    </div>
                                                <?php } ?>

                                            </div>

                                            <div class="form-group">
                                                <select name="date_type" class="form-control" id="date_type">
                                                    <option value="created">Created Date</option>
                                                    <option <?= (($_GET['date_type'] == 'approved_date') ? 'selected' : '') ?> value="approved_date">Qualified Date</option>
                                                </select>
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
                                </div> -->

                            </div>
                            <div class="table-responsive" id="MyDiv">

                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">S.No.</th>

                                            <th style="width:10%" data-sortable="true">Account name</th>
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
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->

        <?php include('includes/footer.php') ?>
        <script>
            $('#leads').DataTable({
                dom: 'Bfrtip',
                "displayLength": 15,
                buttons: ['pageLength'],
                lengthMenu: [
                    [5, 15, 25, 50, 100, 500, 1000],
                    ['5', '15', '25', '50', '100', '500', '1000']
                ],

                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "get_orders_interns.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                        d.d_from = "<?= $_GET['d_from'] ?>";
                        d.d_to = "<?= $_GET['d_to'] ?>";
                        // = 'created';
                        d.dtype = '<?= $_GET['date_type'] ?>';
                        d.product = '<?= intval($_GET['product']) ?>';
                        d.product_type = '<?= intval($_GET['product_type']) ?>';
                        d.counter = '<?= $_GET['counter'] ?>';
                        d.date_from = '<?= $_GET['date_from'] ?>';
                        d.date_to = '<?= $_GET['date_to'] ?>';
                        d.progress = '<?= $_GET['progress'] ?>';
                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="14">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");

                    }
                },
                "order": [
                    [7, "desc"]
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],

                'columns': [{
                        data: 'id'
                    },
                    {
                        data: 'company_name'
                    },
                    {
                        data: 'parent_company'
                    },
                    {
                        data: 'cust_name'
                    },
                    {
                        data: 'industry'
                    },
                    {
                        data: 'cust_number'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'city'
                    },
                    {
                        data: 'state'
                    },
                    {
                        data: 'quantity'
                    },



                ]
            });


            function clear_search() {
                window.location = 'manage_leads_intern.php';
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
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 315);
                $("#leads").tableHeadFixer();

            });


        </script>