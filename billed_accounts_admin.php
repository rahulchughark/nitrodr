<?php include('includes/header.php'); 
if ($_SESSION['user_type'] != 'REVIEWER') admin_page();
?>
<!-- ============================================================== -->
<!-- Start right Content here -->
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
<div class="clearfix"></div>
                            <div class="row" style="padding-top: 0px; padding-bottom: 10px;">
                            <div class="col-md-6 offset-3">
                            <?php
                            $dat = " ";
                            $requestData = $_REQUEST;

                            if($requestData['d_from']){
                                if ($requestData['d_from'] == $requestData['d_to']) {
                                    $dat = " and DATE(o.partner_close_date)='" . $requestData['d_from'] . "'";
                                } else {
                                    $dat = " and DATE(o.partner_close_date)>='" . $requestData['d_from'] . "' and DATE(o.partner_close_date)<='" . $requestData['d_to'] . "'";
                                }
                            }   
                            
                            if($requestData['partner'])
                            {
                                $dat.=" and o.team_id='".$requestData['partner']."'";
                            }

                            $prepetual_query = billedAccountsPrepetualAdmin($dat);

                            $prep_res = db_query($prepetual_query);
                            $prep_data = mysqli_fetch_assoc($prep_res);

                            if($requestData['product_type'] && $requestData['product_type'] == 2)
                            {
                              $prep_count = 0;
                              $prep_license_count = 0;
                            }
                            else{
                                $prep_count = $prep_data['prep_count_no'];
                                 $prep_license_count = $prep_data['prep_license_count'];
                            }

                            $annual_query = billedAccountsAnnualAdmin($dat);

                            $ann_res = db_query($annual_query);
                            $ann_data = mysqli_fetch_assoc($ann_res);
                            
                            if($requestData['product_type'] && $requestData['product_type'] == 1)
                            {
                              $ann_count = 0;
                              $ann_license_count = 0;
                            }
                            else{
                                $ann_count = $ann_data['ann_count_no'];
                                 $ann_license_count = $ann_data['ann_license_count'];
                            }

                            $total_no_account = $prep_count+$ann_count;
                            $total_license_count = $prep_license_count+$ann_license_count;

                            ?>   
                            <table class="table display nowrap table-striped"  cellspacing="0" width="100%" >
                                    <thead>
                                        <tr>

                                            <th data-sortable="true">Product Type</th>
                                            <th data-sortable="true">No. of Accounts</th>
                                            <th data-sortable="true">Number of Licenses</th>
                                        </tr>

                                        <tr>
                                            <th align="center">Perpetual</td>
                                            <td align="center"><?=$prep_count?></td>
                                            <td align="center"><?=$prep_license_count?></td>
                                        </tr>
                                        <tr>
                                            <th align="center">Annual</td>
                                            <td align="center"><?=$ann_count?></td>
                                            <td align="center"><?=$ann_license_count?></td>
                                        </tr>
                                        <tr>
                                            <th align="center">Total</td>
                                            <td align="center"><?=$total_no_account?></td>
                                            <td align="center"><?=$total_license_count?></td>
                                        </tr>
                                    </thead>


                                </table>
                                </div>
                                </div>


                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Order Added Sucessfully! Daily quota for leads is now <?= $_GET['cnt'] ?>. <?php if ($_GET['lt'] == 'LC') {                                                                                  echo " Status for this account will change within approx 24 Hours. ";                                                                                          } else {                                                                                                    echo " Status for this account will change within approx 72 Hours.";                                                                                } ?>
                                </div>
                            <?php } ?>
                            <?php if ($_GET['fail'] == 'ext') { ?>
                                <div class="alert alert-warning">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-warning"><i class="fa fa-check-circle"></i> Error!</h3>Daily quota for leads exhausted.
                                </div>
                            <?php } ?>
                            <?php if ($_GET['update'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Order Updated Sucessfully!
                                </div>
                            <?php } ?>

                            <div class="table-responsive">

                                <div class="btn-group float-right" role="group" style="margin-top:10px;">

                                    <!-- <a href="export_partner_lead.php?d_from=<?= @$_GET['d_from'] ?>&d_to=<?= @$_GET['d_to'] ?>">
                                        <button data-toggle="tooltip" data-placement="left" title="" data-original-title="Excel Export" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-share "></i></button></a> -->
                                    <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>

                                    <div class="dropdown dropdown-lg">

                                        <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">
                                            <form method="get" name="search" class="form-horizontal" role="form">
                                                
                                                <div class="form-group">
                                                        <select name="product_type" id="product_type" class="form-control">
                                                    <option value="">Select Product Type</option>
                                                            
                                                  <option <?= (($_GET['product_type'] == 1) ? 'selected' : '') ?> value="1">CDGS Perpetual</option>
                                                  <option <?= (($_GET['product_type'] == 2) ? 'selected' : '') ?> value="2">CDGS Annual</option>
                                                </select>
                                                </div>

                                                <div class="form-group">
                                                    <div class="input-daterange input-group" id="datepicker-close-date">
                                                        <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                        <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                    </div>
                                                </div> 

                                                 <?php
                                                  $res = db_query("select * from partners where status='Active'");
                                                    
                                                    ?>  
                                                <div class="form-group">
                                                 <select name="partner" id="partner" class="form-control">
                                                <option value="">Select Partner</option>
                                                <?php while ($row = db_fetch_array($res)) { ?>
                                                    <option <?= (($_GET['partner'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                                </div>

                                                <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                            </form>
                                        </div>
                                    </div>

                                </div>

                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>

                                            <th data-sortable="true">S.No.</th>
                                            <th data-sortable="true">DR Code</th>
                                            <th data-sortable="true">Submitted by</th>
                                            <th data-sortable="true">Lead Type</th>
                                            <th data-sortable="true">Quantity</th>
                                            <th data-sortable="true">Product Name</th>
                                            <th data-sortable="true">Product Type</th>
                                            <th data-sortable="true">Company Name</th>
                                            <th data-sortable="true">Date of Submission</th>
                                            <th data-sortable="true">Status</th>
                                            <th data-sortable="true">Stage</th>
                                            <th data-sortable="true">Caller Name</th>
                                            <th data-sortable="true">Close Date</th>
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
            $(document).ready(function() {
                $('#myTable').DataTable();
                $(document).ready(function() {
                    var table = $('#example').DataTable({
                        "columnDefs": [{
                            "visible": false,
                            "targets": 2
                        }],
                        "order": [
                            [2, 'asc']
                        ],
                        "displayLength": 25,

                    });
                });
            });

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
                "processing": false,
                "serverSide": true, 
                "ajax": {
                    url: "get_billed_accounts_admin.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                        d.d_from = "<?= $_GET['d_from'] ?>";
                        d.d_to = "<?= $_GET['d_to'] ?>";
                        d.product_type = '<?= intval($_GET['product_type']) ?>';
                        d.partner = "<?= $_GET['partner'] ?>";
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
                    [6, "desc"]
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],

                'columns': [{
                        data: 'id'
                    },
                    {
                        data: 'code'
                    },
                    {
                        data: 'r_user'
                    },
                    {
                        data: 'lead_type'
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

            function send_notification(title, company_name, submitted_by, id, sender_type, partner_name, sender_id, receiver_id) {
                swal({
                    title: "Are you sure?",
                    text: "Are you sure you would like to align this account for LC ?",
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    confirmButtonText: "Yes!",
                    confirmButtonColor: "#ec6c62"
                }, function() {
                    $.ajax({
                            type: 'POST',
                            url: 'notify_lead.php',
                            data: {
                                id: id,
                                title: title,
                                company_name: company_name,
                                submitted_by: submitted_by,
                                sender_type: sender_type,
                                partner_name: partner_name,
                                sender_id: sender_id,
                                receiver_id: receiver_id
                            },
                            success: function(response) {
                                return false;
                            }
                        }).done(function(data) {
                            swal("Notification to change lead type sent successfully!");
                            setTimeout(function() {
                                location.reload();
                            }, 1000)
                        })
                        .error(function(data) {
                            swal("Oops", "We couldn't connect to the server!", "error");
                        });
                })
            }

            function delete_notification(id) {
                swal({
                    title: "Are you sure?",
                    text: "Are you sure you want to delete request ?",
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    confirmButtonText: "Yes!",
                    confirmButtonColor: "#ec6c62"
                }, function() {
                    $.ajax({
                            type: 'POST',
                            url: 'notifyLead_partner.php',
                            data: {
                                id: id,
                            },
                            success: function(response) {
                                return false;
                            }
                        }).done(function(data) {
                            swal("Request deleted successfully!");
                            setTimeout(function() {
                                location.reload();
                            }, 1000)
                        })
                        .error(function(data) {
                            swal("Oops", "We couldn't connect to the server!", "error");
                        });
                })
            }

            function send_message() {
                swal({
                    title: "Oops,Unable to convert this account for LC Calling!!",
                    text: "For better progression on LC, Visit is required.",
                    type: "warning",
                    closeOnConfirm: false,
                    confirmButtonText: "Ok",
                    confirmButtonColor: "#ec6c62"
                });
            }


            function clear_search() {
                window.location = 'billed_accounts_admin.php';
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

            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });

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
                                    if (result == 1) {
                                        swal({
                                            title: "Done!",
                                            text: "Lead Re-Loged.",
                                            type: "success"
                                        }, function() {
                                            //location.reload();
                                            $('#leads').DataTable().ajax.reload();

                                        });
                                    } else {
                                        swal("Can't Relog Lead!", "Lead already logged once in the past!", "error");
                                    }
                                }
                            });

                        } else {
                            swal("Cancelled", "Lead unchanged!", "error");
                        }
                    });
                }
            }

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
                            var res = $.trim(res);
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
                                    $('#leads').DataTable().ajax.reload();
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

            function stage_change(ids, id) {
                //$('.preloader').show();
                var page_access = 'true';
                $.ajax({
                    type: 'POST',
                    url: 'stage_change.php',
                    data: {
                        pid: id,
                        ids: ids,
                        page_access:page_access
                    },
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function chage_stage(stage, id, ids, substage, op, date1, instalment1, date2, instalment2, date3, instalment3, date4, instalment4, Psubstage) {

                //alert(stage + '' +id);
                //alert(substage);

                if (stage != '') {
                    $('#myModal1').modal('hide');
                    $.ajax({
                        type: 'post',
                        url: 'change_stage.php',
                        data: {
                            stage: stage,
                            substage: substage,
                            lead_id: id,
                            op: op,
                            date1: date1,
                            instalment1: instalment1,
                            date2: date2,
                            instalment2: instalment2,
                            date3: date3,
                            instalment3: instalment3,
                            date4: date4,
                            instalment4,
                            instalment4,
                            Psubstage: Psubstage
                        },
                        success: function(res) {
                            var res = $.trim(res);
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
                                    location.reload();
                                    // $('#leads').DataTable().ajax.reload();
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
        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 410);
                $("#leads").tableHeadFixer();

            });
        </script>