<?php include('includes/header.php');

?>

<!-- ============================================================== -->
<!-- Page wrapper  -->
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/ict-logo.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Target Vs Achievement Report</small>
                                    <h4 class="font-size-14 m-0 mt-1">Target Vs Achievement Report</h4>
                                </div>
                            </div>
                         <div class="clearfix"></div>  
                                <h5 class="card-subtitle">CDGS Visibility</h5>
                                <div class="datenotes-box">
                                    <p>Closed Date updated in "Selected Date Range"<br>
                                        Stage "Commit + EUPO Issued + Booking + OEM Billing + Billed to other reseller"</p>
                                </div>

                                <div class="btn-group float-right" role="group" style="margin-top:0px;">
                                    <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>


                                    <div class="dropdown dropdown-lg">

                                        <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">

                                            <form method="get" name="search" class="form-horizontal" role="form">
                                                <?php $res = db_query("select * from partners where status='Active'");  ?>
                                                <div class="form-group">
                                                    <select name="partner" id="partner" class="form-control">
                                                        <option value="">---Select Partner---</option>
                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (($_GET['partner'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <div class="input-daterange input-group" id="datepicker-close-date">
                                                        <input type="text" value="<?php echo @$_GET['date_cdgs1'] ?>" class="form-control" id="date_cdgs1" name="date_cdgs1" placeholder="Date From" />

                                                        <input type="text" value="<?php echo @$_GET['date_cdgs2'] ?>" class="form-control" id="date_cdgs2" name="date_cdgs2" placeholder="Date To" />
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>

                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="cdgs" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>Account Name</th>
                                                <th>Lead Type</th>
                                                <th>Stage</th>
                                                <th>Caller Name</th>
                                                <th>Close Date</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>Total</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>

                                    </table>
                                </div>

<div class="clearfix"></div> 

                                <h5 class="card-subtitle">Funnel</h5>
                                <div class="datenotes-box">
                                    <p>Closed Date updated in "Selected Date Range"<br>Stage "Quote + Follow-up"</p>
                                </div>

                                <div class="btn-group float-right" role="group" style="margin-top:0px;">
                                    <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>


                                    <div class="dropdown dropdown-lg">

                                        <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">

                                    <form method="get" name="search">
                                        <?php $res = db_query("select * from partners where status='Active'");  ?>
                                        <div class="form-group">
                                        <select name="partner" id="partner" class="form-control">
                                            <option value="">---Select Partner---</option>
                                            <?php while ($row = db_fetch_array($res)) { ?>
                                                <option <?= (($_GET['partner'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                        </div>

                                        <div class="form-group">
                                                <div class="input-daterange input-group" id="datepicker-close-date1">
                                                    <input type="text" value="<?php echo @$_GET['date_funnel1'] ?>" class="form-control" id="date_funnel1" name="date_cdgs1" placeholder="Date From" />

                                                    <input type="text" value="<?php echo @$_GET['date_funnel2'] ?>" class="form-control" id="date_funnel2" name="date_cdgs2" placeholder="Date To" />
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>

                                    </form>
                                </div>
                                    </div>
                                </div>
                            

                                <div class="table-responsive">
                                    <table id="funnel" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>Account Name</th>
                                                <th>Lead Type</th>
                                                <th>Stage</th>
                                                <th>Caller Name</th>
                                                <th>Close Date</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>Total</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>

                                    </table>
                                </div>

<div class="clearfix"></div> 
                               <h5 class="card-subtitle">Billing Report</h5>
                                <div  style="margin:20px;">
                                    <form method="get" name="search">
                                        <?php $res = db_query("select * from partners where status='Active'");  ?>
<div class="row">                                       
									   <select name="partner" id="partner" class="form-control col-md-3">
                                            <option value="">---Select Partner---</option>
                                            <?php while ($row = db_fetch_array($res)) { ?>
                                                <option <?= (($_GET['partner'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="text" value="<?php echo @$_GET['date_qs1'] ?>" class="datepicker form-control col-md-3" id="date_qs1" name="date_qs1" placeholder="Date" />
                                        <input type="text" value="<?php echo @$_GET['date_qs2'] ?>" class="datepicker form-control col-md-3" id="date_qs2" name="date_qs2" placeholder="Date" />

                                        <input type="submit" class="btn btn-primary" value="Search" />
                                        <input type="button" class="btn btn-warning ml-1" value="Clear" onclick="clear_search()" />
										</div>
                                    </form>
                                </div>

                                <div class="table-responsive m-t-20">
                                    <table id="qtr_status" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>Account Name</th>
                                                <th>Lead Type</th>
                                                <th>Stage</th>
                                                <th>Caller Name</th>
                                                <th>Close Date</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>

                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>Total</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="myModal" class="modal fade" role="dialog">


        </div>
        <?php include('includes/footer.php') ?>
        <script>
           $('#cdgs').DataTable({
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
                        url: "ta_report_cdgs.php", // json datasource
                        type: "post", // method  , by default get
                        data: function(d) {
                            d.partner = '<?= $_GET['partner'] ?>';
                            d.d_from = "<?= $_GET['date_cdgs1'] ?>";
                            d.d_to = "<?= $_GET['date_cdgs2'] ?>";
                            // d.custom = $('#myInput').val();
                            // etc
                        },
                        error: function() { // error handling
                            $(".employee-grid-error").html("");
                            $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                            $("#leads_processing").css("display", "none");

                        }
                    },
                    "footerCallback": function(row, data, start, end, display) {
                        var api = this.api(),
                            data;

                        // Remove the formatting to get integer data for summation
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };

                        // Total over all pages
                        total = api
                            .column(6)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Total over this page
                        pageTotal = api
                            .column(6, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        var json = api.ajax.json();
                        // Update footer
                        $(api.column(6).footer()).html(json.total);
                    },
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
                            data: 'lead_type'
                        },
                        {
                            data: 'stage'
                        },
                        {
                            data: 'caller'
                        },
                        {
                            data: 'partner_close_date'
                        },
                        {
                            data: 'quantity'
                        },



                    ]
                });
                // Order by the grouping
                $('#cdgs tbody').on('click', 'tr.group', function() {
                    var currentOrder = table.order()[0];
                    if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                        table.order([2, 'desc']).draw();
                    } else {
                        table.order([2, 'asc']).draw();
                    }
                });


               $('#funnel').DataTable({
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
                        url: "ta_report_funnel.php", // json datasource
                        type: "post", // method  , by default get
                        data: function(d) {
                            d.partner = '<?= $_GET['partner'] ?>';
                            d.d_from = "<?= $_GET['date_funnel1'] ?>";
                            d.d_to = "<?= $_GET['date_funnel2'] ?>";
                            // d.custom = $('#myInput').val();
                            // etc
                        },
                        error: function() { // error handling
                            $(".employee-grid-error").html("");
                            $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                            $("#leads_processing").css("display", "none");

                        }
                    },
                    "footerCallback": function(row, data, start, end, display) {
                        var api = this.api(),
                            data;

                        // Remove the formatting to get integer data for summation
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };

                        // Total over all pages
                        total = api
                            .column(6)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Total over this page
                        pageTotal = api
                            .column(6, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        var json = api.ajax.json();
                        // Update footer
                        $(api.column(6).footer()).html(json.total);
                    },
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
                            data: 'lead_type'
                        },
                        {
                            data: 'stage'
                        },
                        {
                            data: 'caller'
                        },
                        {
                            data: 'partner_close_date'
                        },
                        {
                            data: 'quantity'
                        },



                    ]
                });
                // Order by the grouping
                $('#funnel tbody').on('click', 'tr.group', function() {
                    var currentOrder = table.order()[0];
                    if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                        table.order([2, 'desc']).draw();
                    } else {
                        table.order([2, 'asc']).draw();
                    }
                });
                var dataTable = $('#qtr_status').DataTable({
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
                        url: "ta_report_qtrstatus.php", // json datasource
                        type: "post", // method  , by default get
                        data: function(d) {
                            d.partner = '<?= $_GET['partner'] ?>';
                            d.d_from = "<?= $_GET['date_qs1'] ?>";
                            d.d_to = "<?= $_GET['date_qs2'] ?>";
                            // d.custom = $('#myInput').val();
                            // etc
                        },
                        error: function() { // error handling
                            $(".employee-grid-error").html("");
                            $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                            $("#leads_processing").css("display", "none");

                        }
                    },
                    "footerCallback": function(row, data, start, end, display) {
                        var api = this.api(),
                            data;

                        // Remove the formatting to get integer data for summation
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };

                        // Total over all pages
                        total = api
                            .column(6)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Total over this page
                        pageTotal = api
                            .column(6, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        var json = api.ajax.json();
                        // Update footer
                        $(api.column(6).footer()).html(json.total);
                    },
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
                            data: 'lead_type'
                        },
                        {
                            data: 'stage'
                        },
                        {
                            data: 'caller'
                        },
                        {
                            data: 'partner_close_date'
                        },
                        {
                            data: 'quantity'
                        },



                    ]
                });
                // Order by the grouping
                $('#qtr_status tbody').on('click', 'tr.group', function() {
                    var currentOrder = table.order()[0];
                    if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                        table.order([2, 'desc']).draw();
                    } else {
                        table.order([2, 'asc']).draw();
                    }
                });
           
            function change_goal(a, b) {
                $.ajax({
                    type: 'POST',
                    url: 'get_dv_data.php',
                    data: {
                        uid: a,
                        date: b
                    },
                    success: function(response) {
                        $("#myModal").html();
                        $("#myModal").html(response);
                        $('#myModal').modal('show');
                    }
                });
            }
            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });
            $(function() {
                $('#datepicker-close-date1').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 320);
                $("#cdgs").tableHeadFixer();
                $("#funnel").tableHeadFixer();

            });

            function clear_search() {
                window.location = 'daily_report.php';
            }
        </script>