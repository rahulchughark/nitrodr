<?php include('includes/header.php');
if ($_SESSION['user_type'] != 'EM') {
    admin_page();
}  ?>

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
                <h3 class="text-themecolor m-b-0 m-t-0">Leads</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item active">Leads</li>
                </ol>
            </div>
            <div class="col-md-7 col-4 align-self-center">
                <div class="d-flex m-t-10 justify-content-end">


                    <div class="">
                        <a href="javascript:void(0);" id="sdfexport"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="SFDC Export" class="right-side bottom-right waves-effect waves-light btn-success btn btn-circle btn-lg pull-right m-l-10"><i class="ti-share text-white"></i></button></a>
                    </div>
                    <div class="">

                        <a href="export_renewalLeads.php?d_from=<?= @$_GET['d_from'] ?>&d_to=<?= @$_GET['d_to'] ?>">
                            <button data-toggle="tooltip" data-placement="left" title="" data-original-title="Excel Export" class="right-side bottom-right waves-effect waves-light btn-success btn btn-circle btn-lg pull-right m-l-1"><i class="ti-download text-white"></i></button></a>

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

                    <div class="card-body fixed-table-body">
                        <!-- <h4 class="card-title">Data Export</h4>-->
                        <div style="float:right;margin-right:20px" class="m-b-10">

                        </div>
                        <!-- <h6 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h6>-->
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
                        <form method="get" name="search">
                            <div class="row  pull-right">
                                <div class="col-md-4">
                                    <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="datepicker form-control" id="d_from" name="d_from" placeholder="Date From" /> </div>
                                <div class="col-md-4"><input type="text" value="<?php echo @$_GET['d_to'] ?>" class="datepicker form-control" id="d_to" name="d_to" placeholder="Date To" /></div>
                                <div class="col-md-4"> <input type="submit" class="btn btn-primary" value="Search" />
                                    <input type="button" value="Clear" class="btn btn-danger" onclick="clear_search()" />
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive" id="MyDiv">

                            <table id="leads" class="display nowrap table table-hover table-striped table-bordered font-14" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Submitted by</th>
                                        <th>License Number</th>
                                        <th>License End Date</th>
                                        <th>Quantity</th>
                                        <th>Company Name</th>
                                        <th>Date of Submission</th>
                                        <th>Status</th>
                                        <th>Stage</th>
                                        <th>Caller</th>
                                        <th>Close Date</th>
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

        <!-- ============================================================== -->
        <!-- End Right sidebar -->
        <!-- ============================================================== -->
    </div>
    <div id="myModal1" class="modal" role="dialog">


    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->

    <?php include('includes/footer.php') ?>
    <script>
        $(document).ready(function() {
            $.fn.DataTable.ext.pager.numbers_length = 15;
            $(document).ready(function() {
                var dataTable = $('#leads').DataTable({

                    "stateSave": true,
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
                        url: "get_renewal_leads.php", // json datasource
                        type: "post", // method  , by default get
                        data: function(d) {
                            d.d_from = "<?= $_GET['d_from'] ?>";
                            d.d_to = "<?= $_GET['d_to'] ?>";
                            d.dtype = 'created';
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
                            data: 'r_user'
                        },
                        {
                            data: 'license_number'
                        },
                        {
                            data: 'license_end_date'
                        },
                        {
                            data: 'quantity'
                        },
                        {
                            data: 'company_name'
                        },
                        {
                            data: 'created_date'
                        },
                        {
                            data: 'status'
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


                    ]
                });

            });


            /* $('.stagelist').on('change',function(){

                 alert(this.val());


             });*/







        });
        /*$('#example23').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });*/
        $(document).ready(function() {

            $('#sdfexport').click(function() {
                var dfrom = $('#d_from').val();
                var dto = $('#d_to').val();

                var val = [];
                $(':checkbox:checked').each(function(i) {
                    val[i] = $(this).val();
                });

                val = val.join("_");
                val = val.toString();

                //console.log(val);
                //document.location.href = 'export_orders.php?lead='+val;
                document.location.href = 'export_orders.php?lead=' + val + '&d_from=' + dfrom + '&d_to=' + dto;
                //console.log(val);
                //{ lead: val,d_from:d_from,d_to:d_to }, // data to be submit


            });


        });

        function clear_search() {
            window.location = 'renewal_leads.php';
        }

        $(function() {
        $('.datepicker').daterangepicker({
            //autoUpdateInput: false, //disable default date
            "singleDatePicker": true,
            "showDropdowns": false,
            locale: {
                format: 'YYYY-MM-DD'
            },
        });
        $('.datepicker').val("");
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

            $('.fixed-table-body').height(wfheight - 205);



            $('.fixed-table-body').slimScroll({
                color: '#00f',
                size: '10px',
                height: 'auto',


            });

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