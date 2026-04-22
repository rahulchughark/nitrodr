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

                                    <small class="text-muted">Home >Lapsed Leads</small>
                                    <h4 class="font-size-14 m-b-14 mt-1">Lapsed Leads</h4>
                                </div>
                            </div>
                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Lead Added Successfully!
                                </div>
                            <?php } if ($_GET['update'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Lead Updated Successfully!
                                </div>
                            <?php } if($_GET['valid'] == 'err'){?>
                                <div class="alert alert-danger">                             
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                <h3 class="text-danger"><i class="fa fa-check-circle"></i> Error!</h3> Lapsed Leads can not be converted unless recent Log a Call is added.
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

                                <table id="leads" class="table display nowrap table-striped"   data-height="wfheight"  data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">S.No.</th>

                                            <th data-sortable="true">Reseller name(Submitted by)</th>

                                            <th data-sortable="true">Quantity</th>
                                            <th data-sortable="true">Product Name</th>
                                            <th data-sortable="true">Product Type</th>
                                            <th data-sortable="true">Company Name</th>
                                            <th data-sortable="true">Date of Submission</th>
                                            <th data-sortable="true">Lapsed Stage</th>

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

        </div>
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
                            url: "get_lapsed_partner.php", // json datasource
                            type: "post", // method  , by default get
                            data: function(d) {
                                d.d_from = "<?= $_GET['d_from'] ?>";
                                d.d_to = "<?= $_GET['d_to'] ?>";
                                d.dtype = 'created';
                                d.product = '<?= intval($_GET['product']) ?>';
                                d.product_type = '<?= intval($_GET['product_type']) ?>';
                                //d.start = "<?= $_GET['start'] ?>";
                                // d.custom = $('#myInput').val();
                                // etc
                            },
                            error: function() { // error handling
                                $(".employee-grid-error").html("");
                                $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                                $("#leads_processing").css("display", "none");

                            }
                        },
                        "order": [1, "desc"],
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
                                data: 'quantity'
                            },
                            {
                                data: 'product_name'
                            },
                            {
                                data: 'product_type'
                            },
                            {
                                data: 'company_name'
                            },
                            {
                                data: 'created_date'
                            },
                            {
                                data: 'stage'
                            },




                        ]
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
                window.location = 'lapsed_partner_leads.php';
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

            function chage_stage(stage, id, ids, substage) {

                //alert(stage + '' +id);


                if (stage != '') {
                    $('#myModal1').modal('hide');
                    $.ajax({
                        type: 'post',
                        url: 'change_stage.php',
                        data: {
                            stage: stage,
                            substage: substage,
                            lead_id: id
                        },
                        success: function(res) {
                            if (res == 'success') {
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
        </script>
        <style>
            table td {
                cursor: pointer;
                word-wrap: break-word;
                max-width: 120px !important;
            }
        </style>
        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 300);				
				$("#leads").tableHeadFixer(); 

            });

            function cd_change(ids, id) {
                //$('.preloader').show();
                $.ajax({
                    type: 'POST',
                    url: 'cd_change.php',
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

            function change_cdDate(cd_date, id, ids) {
                if (cd_date != '') {
                    $('#myModal1').modal('hide');
                    $.ajax({
                        type: 'post',
                        url: 'change_cdDate.php',
                        data: {
                            cd_date: cd_date,
                            lead_id: id
                        },
                        success: function(res) {
                            if (res == 'success') {
                                swal({
                                    title: "Done!",
                                    text: "Close Date changed Successfully.",
                                    type: "success"
                                }, function() {
                                    $('#myModal1').modal('hide');
                                    var ids2 = "'but2" + id + "'";
                                    //alert(ids2);
                                    var newDate = convertDate(cd_date);
                                    var link = newDate + '<a href="javascript:void(0)" title="Change Close Date" id=but2' + id + ' onclick="cd_change(' + ids2 + ',' + id + ')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>'
                                    $("#" + ids).parent().html(link);
                                    //$('#leads').DataTable().ajax.reload();
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

            function convertDate(dateString) {
                var p = dateString.split(/\D/g)
                return [p[2], p[1], p[0]].join("-")
            }
        </script>