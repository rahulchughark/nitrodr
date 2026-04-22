<?php include('includes/header.php'); ?>

<?php if ($_POST['review_edit']) {
    $query = db_query("update lead_review set is_review=0,removed_by='" . $_SESSION['user_id'] . "' where lead_id='" . $_POST['pid'] . "'");
    $o_stage = getSingleresult("select stage from orders where id='" . $_POST['pid'] . "'");
    $log_query = db_query("insert into review_log (lead_id,old_stage,new_stage,comment,added_by) values ('" . $_POST['pid'] . "','" . $o_stage . "','" . $_POST['stage'] . "','" . $_POST['comment'] . "','" . $_SESSION['name'] . "')");
    if ($_POST['stage'] != 'EU PO Issued' &&  $_POST['stage'] != 'Booking' && $_POST['stage'] != 'OEM Billing') {
        $query = db_query("update orders set prospecting_date='" . date('Y-m-d') . "', stage='" . $_POST['stage'] . "' where id='" . $_POST['pid'] . "'");
    } else {
        $query = db_query("update orders set stage='" . $_POST['stage'] . "' where id='" . $_POST['pid'] . "'");
    }
}
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
                                    <small class="text-muted">Home >Review Leads</small>
                                    <h4 class="font-size-14 m-b-14 mt-1">Leads</h4>
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

                                <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                    <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>
                                    <div class="dropdown dropdown-lg">

                                        <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">
                                            <form method="get" name="search" class="form-horizontal" role="form">
                                                <div class="form-group">

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
                                                        <div id="product_type">
                                                        <select name="product_type" class="form-control">
                                                            <option value="">Select Product Type</option>
                                                        </select>
                                                        </div>
                                                    <?php } ?>

                                                </div>
                                                <div class="form-group">
                                                    <div class="input-daterange input-group" id="datepicker-close-date">
                                                        <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_form" name="d_from" placeholder="Date From" />

                                                        <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <select class="form-control" id="is_review" name="is_review">
                                                        <option value="">Review Status</option>
                                                        <option <?= (($_REQUEST['is_review'] == '1') ? 'selected' : '') ?> value="1">Pending</option>
                                                        <option <?= (($_REQUEST['is_review'] == '0') ? 'selected' : '') ?> value="0">Done</option>
                                                        <option <?= (($_REQUEST['is_review'] == '2') ? 'selected' : '') ?> value="2">In-Complete</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true" ></span></button>
                                            </form>
                                        </div>


                                    </div>

                                </div>


                                <table id="leads" class="table display nowrap table-striped"   data-height="wfheight"  data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Reseller name(Submitted by)</th>
                                            <th>Lead Type</th>
                                            <th>Quantity</th>
                                            <!-- <th>Product Name</th>
                                            <th>Product Type</th> -->
                                            <th>Company Name</th>
                                            <th>Date of Submission</th>
                                            <th>Last Stage</th>
                                            <th>Added On</th>
                                            <th>Review Status</th>
                                            <th>Log</th>
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
                            url: "get_review_partner.php", // json datasource
                            type: "post", // method  , by default get
                            data: function(d) {
                                d.d_from = "<?= $_GET['d_from'] ?>";
                                d.d_to = "<?= $_GET['d_to'] ?>";
                                d.partner = "<?= $_GET['partner'] ?>";
                                d.review = "<?= $_GET['is_review'] ?>";
                                // d.product = '<?= intval($_GET['product']) ?>';
                                // d.product_type = '<?= intval($_GET['product_type']) ?>';
                            },
                            error: function() { // error handling
                                $(".employee-grid-error").html("");
                                $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="12">No data found on server!</th></tr></tbody>');
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
                                data: 'r_name'
                            },
                            {
                                data: 'lead_type'
                            },
                            {
                                data: 'quantity'
                            },
                            // {
                            //     data: 'product_name'
                            // },
                            // {
                            //     data: 'product_type'
                            // },
                            {
                                data: 'company_name'
                            },
                            {
                                data: 'created_date'
                            },
                            {
                                data: 'stage'
                            },
                            {
                                data: 'added_date'
                            },
                            {
                                data: 'is_review'
                            },
                            {
                                data: 'action'
                            },

                        ]

                    });
                    // Order by the grouping
                    // $('#leads tbody').on('click', 'tr.group', function() {
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
                window.location = 'review_partner_lead.php';
            }

            // $(function() {
            //     $('.datepicker').daterangepicker({

            //         "singleDatePicker": true,
            //         "showDropdowns": true,
            //         locale: {
            //             format: 'YYYY-MM-DD'
            //         },
            //         //startDate: '2017-01-01',
            //         //autoUpdateInput: false,

            //     });
            // });

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

            $(function() {
            $('#datepicker-close-date').datepicker({
               format: 'yyyy-mm-dd',
			   //startDate: '-3d',
			   autoclose:!0

            });
			
        });
        </script>
        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 310);				
				$("#leads").tableHeadFixer(); 

            });
        </script>