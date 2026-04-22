<?php include('includes/header.php');
admin_page(); ?>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Upgrade Leads</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item active">Upgrade Leads</li>
                </ol>
            </div>
            <div class="col-md-7 col-4 align-self-center">
                <div class="d-flex m-t-10 justify-content-end">


                    <div class="">

                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body  fixed-table-body">
                        <h4 class="card-title">Search</h4>
                        <div class="col-12" style="float:left;">
                            <form method="get" name="search">
                                <div class="row form-group">

                                    <label>Date From:</label><input type="text" autocomplete="off" value="<?php echo @$_GET['d_from'] ?>" class="datepicker form-control col-4" id="d_from" name="d_from" placeholder="Date From" />
                                    &nbsp;<label>Date to:</label> <input type="text" autocomplete="off" value="<?php echo @$_GET['d_to'] ?>" class="datepicker form-control col-4" id="d_to" name="d_to" placeholder="Date To" />
                                </div>

                                <div class="row form-group">
                                    <?php if ($_SESSION['sales_manager'] != 1) {
                                        $res = db_query("select * from partners");
                                    } else {
                                        $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ")");
                                    }
                                    //print_r($res); die;

                                    ?>
                                    <label class="control-label text-right">Partners:&nbsp;</label> <select name="partner" id="partner" class="form-control col-4">
                                        <option value="">---Select---</option>
                                        <?php while ($row = db_fetch_array($res)) { ?>
                                            <option <?= (($_GET['partner'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php $stage = $_GET['stage']; ?> &nbsp;<label>Stage:</label><select name="stage" class="form-control col-4">
                                        <option value="">--Select--</option>
                                        <option <?= (($stage == 'Prospecting') ? 'selected' : '') ?> value="Prospecting">Prospecting</option>
                                        <option <?= (($stage == 'Verification') ? 'selected' : '') ?> value="Verification">Verification</option>
                                        <option <?= (($stage == 'Quote') ? 'selected' : '') ?> value="Quote">Quote</option>
                                        <option <?= (($stage == 'Negotiation') ? 'selected' : '') ?> value="Negotiation">Negotiation</option>
                                        <option <?= (($stage == 'Commit') ? 'selected' : '') ?> value="Commit">Commit</option>
                                        <option <?= (($stage == 'EU PO Issued') ? 'selected' : '') ?> value="EU PO Issued">EU PO Issued</option>
                                        <option <?= (($stage == 'Booking') ? 'selected' : '') ?> value="Booking">Booking</option>
                                        <option <?= (($stage == 'OEM Billing') ? 'selected' : '') ?> value="OEM Billing">OEM Billing</option>
                                        <option <?= (($stage == 'Closed Lost') ? 'selected' : '') ?> value="Closed Lost">Closed Lost</option>

                                    </select>

                                </div>

                                <br>
                                <input class="btn btn-primary" type="submit" value="Search" />
                                <input class="btn btn-danger" type="button" value="Clear" onclick="clear_search()" />
                                <hr>

                            </form>
                        </div>
                        <?php if ($_GET['add'] == 'success') { ?>
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Order Added Sucessfully!
                            </div>
                        <?php } ?>
                        <?php if ($_GET['update'] == 'success') { ?>
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Upadated</h3> Order Updated Sucessfully!
                            </div>
                        <?php } ?>
                        <div class="table-responsive m-t-40">
                            <table id="leads" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Reseller</th>
                                        <th>End User</th>
                                        <th>End User Address</th>
                                        <th>End User Contact</th>
                                        <th>Contact License Email</th>
                                        <th>Phone</th>
                                        <th>Quantity</th>
                                        <th>Stage</th>
                                    </tr>
                                </thead>


                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right sidebar -->
        <!-- ============================================================== -->
        <!-- .right-sidebar -->
        <div class="right-sidebar">
            <div class="slimscrollright">
                <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                <div class="r-panel-body">
                    <ul id="themecolors" class="m-t-20">
                        <li><b>With Light sidebar</b></li>
                        <li><a href="javascript:void(0)" data-theme="default" class="default-theme">1</a></li>
                        <li><a href="javascript:void(0)" data-theme="green" class="green-theme">2</a></li>
                        <li><a href="javascript:void(0)" data-theme="red" class="red-theme">3</a></li>
                        <li><a href="javascript:void(0)" data-theme="blue" class="blue-theme working">4</a></li>
                        <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme">5</a></li>
                        <li><a href="javascript:void(0)" data-theme="megna" class="megna-theme">6</a></li>
                        <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                        <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme">7</a></li>
                        <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme">8</a></li>
                        <li><a href="javascript:void(0)" data-theme="red-dark" class="red-dark-theme">9</a></li>
                        <li><a href="javascript:void(0)" data-theme="blue-dark" class="blue-dark-theme">10</a></li>
                        <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme">11</a></li>
                        <li><a href="javascript:void(0)" data-theme="megna-dark" class="megna-dark-theme ">12</a></li>
                    </ul>
                    <ul class="m-t-20 chatonline">
                        <li><b>Chat option</b></li>
                        <li>
                            <a href="javascript:void(0)"><img src="../assets/images/users/1.jpg" alt="user-img" class="img-circle"> <span>Varun Dhavan <small class="text-success">online</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="../assets/images/users/2.jpg" alt="user-img" class="img-circle"> <span>Genelia Deshmukh <small class="text-warning">Away</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="../assets/images/users/3.jpg" alt="user-img" class="img-circle"> <span>Ritesh Deshmukh <small class="text-danger">Busy</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="../assets/images/users/4.jpg" alt="user-img" class="img-circle"> <span>Arijit Sinh <small class="text-muted">Offline</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="../assets/images/users/5.jpg" alt="user-img" class="img-circle"> <span>Govinda Star <small class="text-success">online</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="../assets/images/users/6.jpg" alt="user-img" class="img-circle"> <span>John Abraham<small class="text-success">online</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="../assets/images/users/7.jpg" alt="user-img" class="img-circle"> <span>Hritik Roshan<small class="text-success">online</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="../assets/images/users/8.jpg" alt="user-img" class="img-circle"> <span>Pwandeep rajan <small class="text-success">online</small></span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Right sidebar -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->

    <?php include('includes/footer.php') ?>
    <script>
        $(document).ready(function() {

            $(document).ready(function() {
                var dataTable = $('#leads').DataTable({
                    "stateSave": true,
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
                        url: "get_update_leads_admin.php", // json datasource
                        type: "post", // method  , by default get
                        data: function(d) {
                            d.d_from = "<?= $_GET['d_from'] ?>";
                            d.d_to = "<?= $_GET['d_to'] ?>";
                            d.reseller = "<?= $_GET['partner'] ?>";
                            d.stage = "<?= $_GET['stage'] ?>";
                            // d.custom = $('#myInput').val();
                            // etc
                        },
                        error: function() { // error handling
                            $(".employee-grid-error").html("");
                            $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                            $("#leads_processing").css("display", "none");

                        }
                    },
                    'columns': [{
                            data: 'id'
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'eu_name'
                        },
                        {
                            data: 'eu_address'
                        },
                        {
                            data: 'eu_contact'
                        },
                        {
                            data: 'contact_email'
                        },
                        {
                            data: 'mobile_number'
                        },
                        {
                            data: 'quantity'
                        },
                        {
                            data: 'stage'
                        },


                    ]
                });
                // Order by the grouping
                $('#leads tbody').on('click', 'tr.group', function() {
                    var currentOrder = table.order()[0];
                    if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                        table.order([2, 'desc']).draw();
                    } else {
                        table.order([2, 'asc']).draw();
                    }
                });
            });
        });
        $('#example23').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        $(function() {
            $('.datepicker').daterangepicker({

                "singleDatePicker": true,
                "showDropdowns": true,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                //startDate: '2017-01-01',
                //autoUpdateInput: false,

            });
        });
    </script>
    <script>
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
        function clear_search() {
            window.location = 'search_upgrade_lead.php';
        }
    </script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>