<?php include('includes/header.php');

if (isset($_POST['save_notification'])) {
    $id           = $_POST['pid'];
    $title        = $_POST['title'];
    $company_name = $_POST['company_name'];
    $submitted_by = $_POST['submitted_by'];
    $sender_type  = $_POST['sender_type'];
    $partner_name = $_POST['partner_name'];
    $sender_id    = $_POST['sender_id'];
    $receiver_id  = implode(',', $_POST['receiver_id']);
    $initiate_reason  = $_POST['initiate_reason'];
    $visit_done = $_POST['visit_done'];
    $usage_confirmed  = implode(',', $_POST['usage_confirmed']);
    $confirmation_received = $_POST['confirmation_received'];
    $role = $_POST['role'];
    $designation = $_POST['designation'];
    $validation_type = $_POST['validation_type'];
//$attachment = $_POST["user_attachment"];
//print_r($attachment);

if ($_FILES["user_attachment"]) {
    $target_dir = "uploads/";
    $target_file = $target_dir . time() . basename($_FILES["user_attachment"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($_FILES["user_attachment"]["size"] > 4000000) {
        echo "<script>alert('Sorry, your file is too large!')</script>";
        redir("add_leads.php", true);
    } else {
        move_uploaded_file($_FILES["user_attachment"]["tmp_name"], $target_file);
    }
}

// print_r($_FILES["user_attachment"]["name"]);
//     print_r($_FILES["user_attachment"]);die;

    if ($usage_confirmed || $role || $designation || $validation_type || $_FILES["user_attachment"]) {
        $lead_update = db_query("update orders set confirmation_from='" . $confirmation_received . "',eu_role='" . $role . "',eu_designation='" . $designation . "',validation_type='". $validation_type ."',user_attachement='".$target_file."' where id=" . $id);
    }

    $activityLog_insert = db_query("insert into activity_log(`pid`,description,`activity_type`,`call_subject`,`added_by`,`is_intern`,`action_plan`,data_ref)values('" . $id . "','". $initiate_reason ."','Lead','Profiling Call','" . $_SESSION['user_id'] . "',0,'".$_POST['action_plan']."',1)");


    $insert = saveNotification('lead_notification', $id, $title, $company_name, $submitted_by, $sender_type, $partner_name, $sender_id, $receiver_id, $initiate_reason, $visit_done, $usage_confirmed);
    // print_r($insert);die;
    if ($insert) {
        echo '* save new notification success';
    }

    $sql = db_query("select * from orders where id='" . $id . "'");
    $row_data = db_fetch_array($sql);

    $select_query = db_query("select * from lead_notification where type_id='" . $id . "' and sender_id=" . $_SESSION['user_id']);


    if (mysqli_num_rows($select_query) > 0) {

        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $id . "','Request Status','" . $row_data['lead_type'] . "','LC',now(),'" . $_SESSION['user_id'] . "')");

        if ($row_data['eu_role'] != $_POST['eu_role']) {
            $modify_name = $_POST['eu_role'];
            $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $id . "','Role','" . $row_data['eu_role'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
        }
        if ($row_data['confirmation_from'] != $_POST['confirmation_from']) {
            $modify_name = $_POST['confirmation_from'];
            $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $id . "','Usage Confirmation Received from','" . $row_data['confirmation_from'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
        }
        if ($row_data['eu_designation'] != $_POST['eu_designation']) {
            $modify_name = $_POST['eu_designation'];
            $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('" . $id . "','Designation','" . $row_data['eu_designation'] . "','" . $modify_name . "',now(),'" . $_SESSION['user_id'] . "')");
        }
    }
}

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
                                    <h4 class="font-size-14 m-b-14 mt-1">Leads</h4>
                                </div>
                            </div>


                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Order Added Successfully!
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
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Order Updated Successfully!
                                </div>
                            <?php } ?>

                           

                            <div class="table-responsive">

                                <div class="btn-group float-right" role="group" style="margin-top:12px;">

                                    <a href="export_partner_lead.php?d_from=<?= @$_GET['d_from'] ?>&d_to=<?= @$_GET['d_to'] ?>">
                                        <button data-toggle="tooltip" data-placement="left" title="" data-original-title="Excel Export" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-share "></i></button></a>
                                    <button type="button" class="btn btn-xs btn-light ml-1  " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                    <div class="dropdown dropdown-lg">

                                        <div class="dropdown-menu1 dropdown-menu-right filter_wrap filter_wrap_2" id="filter-container" role="menu">
                                            <form method="get" name="search" class="form-horizontal" role="form">

                                                <input type="hidden" name="untouched" value="<?= $_GET['untouched'] ?>">
                                                <input type="hidden" name="score" value="<?= $_GET['score'] ?>">
                                                <input type="hidden" name="stages" value="<?= $_GET['stages'] ?>">
                                                <input type="hidden" name="month" value="<?= $_GET['month'] ?>">
                                                <input type="hidden" name="year" value="<?= $_GET['year'] ?>">
                                                <input type="hidden" name="meter" value="<?= $_GET['meter'] ?>">
                                               
                                                
                                                <div class="form-group">
                                                    <div class="input-daterange input-group" id="datepicker-close-date">
                                                        <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                        <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                    </div>
                                                </div>

                                                <?php
                                                if (!is_array($poa)) {
                                                    $val = $poa;
                                                    $poa = array();
                                                    $poa['0'] = $val;
                                                    $poa_flag = 1;
                                                } ?>
                                               
                                
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
                                            <!-- <th data-sortable="true">Product Name</th>
                                            <th data-sortable="true">Product Type</th> -->
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
        <div id="myModal1" class="modal fade" role="dialog">


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
                "processing": true,
                "serverSide": true,
                stateSave: true,
                "ajax": {
                    url: "get_student_orders_partner.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                        d.d_from = "<?= $_GET['d_from'] ?>";
                        d.d_to = "<?= $_GET['d_to'] ?>";
                        d.product = '<?= intval($_GET['product']) ?>';
                        d.product_type = '<?= intval($_GET['product_type']) ?>';
                        d.untouched = '<?= intval($_GET['untouched']) ?>';
                        d.score = '<?= intval($_GET['score']) ?>';
                        d.stages = '<?= $_GET['stages'] ?>';
                        d.month = '<?= $_GET['month'] ?>';
                        d.year = '<?= $_GET['year'] ?>';
                        d.meter = '<?= $_GET['meter'] ?>'
                        d.poa = "<?= @implode("','", $_GET['poa']) ?>";
                        d.validation_type = '<?= $_GET['validation_type'] ?>';
                        // etc
                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="13">No data found on server!</th></tr></tbody>');
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
                $.ajax({
                    type: 'POST',
                    url: 'add_notification.php',
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
                        $("#myModal1").html();
                        $("#myModal1").html(response);
                        $('#myModal1').modal('show');
                    }
                });
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

            // function send_message() {
            //     swal({
            //         title: "Oops,Unable to convert this account for LC Calling!!",
            //         text: "For better progression on LC, Visit is required.",
            //         type: "warning",
            //         closeOnConfirm: false,
            //         confirmButtonText: "Ok",
            //         confirmButtonColor: "#ec6c62"
            //     });
            // }

            $(document).ready(function() {
                $('#plan_of_action').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Plan of Action'
                });
            });

            function clear_search() {
                window.location = 'student_orders.php';
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
                        page_access: page_access
                    },
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        var stage = $('#dd_stage').val();
                        var stage = $.trim(stage);
                        var user_type = '<?= $_SESSION['user_type'] ?>';
                        if ((stage == 'EU PO Issued') && (user_type == 'USR' || user_type == 'PUSR')) {
                            $("#save_button").prop('disabled', true);
                        }
                        $('.preloader').hide();
                    }
                });
            }

            function get_change_data(pid, ids) {
                var stage = $('#dd_stage :selected').text();
                var stagevalue = $('#dd_stage :selected').val();
                var substage = $('#add_comment_dd :selected').text();
                var substagevalue = $('#add_comment_dd :selected').val();
                if (stagevalue == '') {
                    swal("Please select stage first.");
                    return false;
                }
                if (substagevalue == '') {
                    swal('Please select sub stage first');
                    return false;
                }

                if (substage == 'Lost to competition') {
                    var Psubstage = $('#add_Pcomment_dd :selected').text();
                } else {
                    $('#add_Pcomment_dd option:selected').remove()
                }

                if (substage == '100% Advance Received' || substage == 'Payment Against Delivery') {
                    var op = $("input[name='op']:checked").val();

                } else if (substage == 'Payment in Installments') {
                    var order_price = $("input[name=order_price]").val();
                    var date1 = $("input[name=date1]").val();
                    var instalment1 = $("input[name=instalment1]").val();
                    var date2 = $("input[name=date2]").val();
                    var instalment2 = $("input[name=instalment2]").val();
                    var date3 = $("input[name=date3]").val();
                    var instalment3 = $("input[name=instalment3]").val();
                    var date4 = $("input[name=date4]").val();
                    var instalment4 = $("input[name=instalment4]").val();
                    var date5 = $("input[name=date5]").val();
                    var instalment5 = $("input[name=instalment5]").val();
                    var date6 = $("input[name=date6]").val();
                    var instalment6 = $("input[name=instalment6]").val();
                    //chage_stage(stage,pid,ids,substage,op,date1,instalment1,date2,instalment2,date3,instalment3,date4,instalment4);
                }



            }

            function chage_stage(stage, id, ids, substage, op, order_price, date1, instalment1, date2, instalment2, date3, instalment3, date4, instalment4, date5, instalment5, date6, instalment6, Psubstage) {

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
                            order_price: order_price,
                            date1: date1,
                            instalment1: instalment1,
                            date2: date2,
                            instalment2: instalment2,
                            date3: date3,
                            instalment3: instalment3,
                            date4: date4,
                            instalment4: instalment4,
                            date5: date5,
                            instalment5: instalment5,
                            date6: date6,
                            instalment6: instalment6,
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
        </script>
        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 300);
                $("#leads").tableHeadFixer();

            });
        </script>