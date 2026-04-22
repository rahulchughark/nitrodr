<?php include('includes/header.php');
if ($_SESSION['user_type'] != 'REVIEWER') admin_page(); ?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Review Activity</small>
                                    <h4 class="font-size-14 m-0 mt-1">Review Activity</h4>
                                </div>
                            </div>

                            <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                <button type="button" class="btn btn-xs btn-light ml-1  " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu1 dropdown-menu-right filter_wrap filter_wrap_2" id="filter-container" role="menu">


                                        <form method="get" name="search" class="form-horizontal" role="form">

                                            <div class="form-group">
                                                <?php
                                                if (!is_array($partner)) {
                                                    $val = $partner;
                                                    $partner = array();
                                                    $partner['0'] = $val;
                                                }
                                                
                                                if (!is_array($product)) {
                                                    $val = $product;
                                                    $product = array();
                                                    $product['0'] = $val;
                                                }
                                                if (!is_array($reviewer)) {
                                                    $val = $reviewer;
                                                    $reviewer = array();
                                                    $reviewer['0'] = $val;
                                                }

                                                if ($_SESSION['sales_manager'] != 1) {
                                                    $res = db_query("select * from partners where status='Active'");
                                                } else {
                                                    $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active'");
                                                }
                                                ?>
                                                <select name="partner[]" id="partner" class="multiselect_partner form-control" data-live-search="true" multiple>

                                                    <?php while ($row = db_fetch_array($res)) { ?>
                                                        <option <?= (in_array($row['id'], $partner) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <select name="product[]" class="product_data form-control" data-live-search="true" multiple>
                                                    <?php $query = selectProduct('tbl_product');
                                                    while ($row = db_fetch_array($query)) { ?>
                                                        <option <?= (in_array($row['id'],$product) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_name']) ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <?php if ($_GET['product']) { ?>
                                                    <select name="product_type[]" id="product_type" class="product_type_id form-control" data-live-search="true" multiple>
                                                       <?php $query = productTypeMultiselect($_GET['product']);
                                                        while ($row = db_fetch_array($query)) { ?>
                                                            <option <?= (in_array($row['id'], $product_type) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_type']) ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } else { ?>
                                                    <div id="product_type">
                                                    </div>
                                                <?php } ?>

                                            </div>

                                            <div class="form-group">
                                                <div class="input-daterange input-group" id="datepicker-close-date">
                                                    <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                    <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                </div>
                                            </div>
                                            <?php if ($_SESSION['user_type'] != 'SALES MNGR') { ?>
                                                <div class="form-group">
                                                    <?php $res = db_query("select id,name from users where user_type in ('SUPERADMIN','OPERATIONS','REVIEWER','SALES MNGR')");
                                                    ?>
                                                    <select name="reviewer" id="reviewer" class="form-control">
                                                        <option value="">Select Reviewer</option>
                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (($_GET['reviewer'] == $row['name']) ? 'selected' : '') ?> value='<?= $row['name'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            <?php } ?>



                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>

                                        </form>
                                    </div>
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

                            <div class="table-responsive">

                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sr. Number</th>
                                            <th>Review Date</th>
                                            <th>Reviewer Name</th>
                                            <th>Var Organization</th>
                                            <th>LC</th>
                                            <th>BD</th>
                                            <th>Incoming</th>
                                            <th># Accounts Reviewed</th>



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


        <?php include('includes/footer.php');
         if (count($product_type) > 1) {
            $product_type_arr = implode('","', $product_type);
            //print_r($campaign_arr);die;
        } else if (!$product_type_flag) {
            $product_type_arr = $_GET['product_type'][0];
        } else {
            $product_type_arr = $_GET['product_type'];
        }
         ?>
        <script>
            $('#leads').DataTable({
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
                    url: "get_review_report3.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                        d.d_from = "<?= $_GET['d_from'] ?>";
                        d.d_to = "<?= $_GET['d_to'] ?>";
                        d.reviewer = '<?= implode('","', $reviewer) ?>';
                        d.product = "<?= implode("','", $_GET['product']) ?>";
                        d.product_type = '<?= $product_type_arr ?>';
                        d.partner = '<?= implode('","', $_GET['partner']) ?>';
                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");

                    }
                },
                "order": [
                    [1, "desc"]
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],
                'columns': [{
                        data: 'id'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'reviewer'
                    },
                    {
                        data: 'var_organization'
                    },
                    {
                        data: 'lc'
                    },
                    {
                        data: 'bd'
                    },
                    {
                        data: 'incoming'
                    },
                    {
                        data: 'accounts'
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

            $(document).ready(function() {
                $('.product_data').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Product'
                });
                $('.multiselect_type').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Product Type'
                });
                $('#reviewer').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Reviewer'
                });
                $('.multiselect_partner').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Partner'
                });

            });

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
                window.location = 'review_report3.php';
            }

            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });

            function chage_stage(stage, id) {

                //alert(stage + '' +id);


                if (stage != '') {
                    $.ajax({
                        type: 'post',
                        url: 'change_stage.php',
                        data: {
                            stage: stage,
                            lead_id: id
                        },
                        success: function(res) {
                            if (res == 'success') {
                                swal({
                                    title: "Done!",
                                    text: "Stage changed Successfully.",
                                    type: "success"
                                }, function() {
                                    //window.location = "manage_orders.php";
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

            function relog(id) {
                if (id) {
                    swal({
                        title: "Are you sure?",
                        text: "You want to relog the same lead!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, Re-Log it!",
                        cancelButtonText: "No, cancel modification!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }, function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "relog_lead.php?id=" + id,
                                success: function(result) {
                                    if (result) {
                                        swal({
                                            title: "Done!",
                                            text: "Lead Re-Loged.",
                                            type: "success"
                                        }, function() {
                                            //location.reload();
                                            $('#leads').DataTable().ajax.reload();

                                        });
                                    }
                                }
                            });

                        } else {
                            swal("Cancelled", "Lead unchanged!", "error");
                        }
                    });
                }
            }

            function edit_review(id) {
                //$('.preloader').show();
                $.ajax({
                    type: 'POST',
                    url: 'review_edit.php',
                    data: {
                        pid: id
                    },
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function view_log(id) {
                //$('.preloader').show();
                $.ajax({
                    type: 'POST',
                    url: 'view_review_log.php',
                    data: {
                        pid: id
                    },
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }
        </script>
        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 320);
                $("#leads").tableHeadFixer();

            });
        </script>