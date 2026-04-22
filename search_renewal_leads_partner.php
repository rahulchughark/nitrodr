<?php include('includes/header.php');
partner_page(); ?>
<style>
    .dt-buttons {
        margin-top: 30px;
        margin-left: -185px;
        margin-bottom: 10px;
    }

    body[data-layout=horizontal] .page-content {
        min-height: calc(100vh - 290px) !important;
        padding-bottom: 0;
    }

    @media (min-width: 1200px) {
        .scroll_div {
            overflow-x: hidden !important;
        }
    }
</style>
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
                                    <h4 class="font-size-14 m-0 mt-1">Search Renewal Leads</h4>
                                </div>
                            </div>


                            <div class="col-md-7 col-4 align-self-right">
                                <div class="d-flex m-t-10 justify-content-end">

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

                                    <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                    <div class="dropdown dropdown-lg">

                                        <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">
                                            <!-- <div class="col-12 search_form"> -->
                                            <form method="get" name="search" class="form-horizontal" role="form">

                                            <div class="row">
                                                    <div class="form-group col-md-3">

                                                        <select class="form-control" name="dtype">
                                                            <option value="">Select Date Type</option>
                                                            <option <?= (($_GET['dtype'] == 'created') ? 'selected' : '') ?> value="created">Created Date</option>
                                                            <option <?= (($_GET['dtype'] == 'actioned_date') ? 'selected' : '') ?> value="actioned_date">Actioned Date</option>
                                                            <option <?= (($_GET['dtype'] == 'close') ? 'selected' : '') ?> value="close">Close Date</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <div class="input-daterange input-group" id="datepicker-close-date">

                                                            <input type="text" autocomplete="off" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                            <input type="text" autocomplete="off" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                        </div>
                                                    </div>
                                                    <?php if (!is_array($status)) {
                                                        $val = $status;
                                                        $status = array();
                                                        $status['0'] = $val;
                                                    }
                                                     if (!is_array($users)) {
                                                        $val = $users;
                                                        $users = array();
                                                        $users['0'] = $val;
                                                    }
                                                    ?>

                                                    <div class="form-group col-md-3">

                                                        <select class="multiselect_status form-control" name="status[]" data-live-search="true" multiple>

                                                        <option <?= (in_array('Approved', $status) ? 'selected' : '') ?> value="Approved">Qualified</option>
                                                            <option <?= (in_array('Cancelled', $status) ? 'selected' : '') ?> value="Cancelled">Unqualified</option>
                                                            <option <?= (in_array('Undervalidation', $status) ? 'selected' : '') ?> value="Undervalidation">Under Validation</option>
                                                            <option <?= (in_array('Pending', $status) ? 'selected' : '') ?> value="Pending">Pending</option>
                                                            <option <?= (in_array('Already locked', $status) ? 'selected' : '') ?> value="Already locked">Already locked</option>
                                                            <option <?= (in_array('Insufficient Information', $status) ? 'selected' : '') ?> value="Insufficient Information">Insufficient Information</option>
                                                            <option <?= (in_array('Incorrect Information', $status) ? 'selected' : '') ?> value="Incorrect Information">Incorrect Information</option>
                                                            <option <?= (in_array('Out Of Territory', $status) ? 'selected' : '') ?> value="Out Of Territory">Out Of Territory</option>
                                                            <option <?= (in_array('Duplicate Record Found', $status) ? 'selected' : '') ?> value="Duplicate Record Found">Duplicate Record Found</option>
                                                        </select>
                                                    </div>
                                                    <?php if (!is_array($sub_product)) {
                                                        $val = $sub_product;
                                                        $sub_product = array();
                                                        $sub_product['0'] = $val;
                                                        $sub_product_flag = 1;
                                                    }
                                                     ?>
                                                    <div class="form-group col-md-3">
                                                        <select name="sub_product[]" class="multiselect_productType form-control" multiple>
                                                            <?php $res = db_query("select * from tbl_product_pivot where status=1 and product_id='2'");
                                                            while ($row = db_fetch_array($res)) { ?>
                                                                <option <?= (in_array($row['id'], $sub_product) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['product_type'] ?></option>
                                                            <?php  } ?>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">


                                                <div class="form-group col-md-3">
                                                        <select name="users[]" id="users" class="form-control" data-live-search="true" multiple>
                                                            <?php $res = db_query("select * from users where status='Active' and team_id=" . $_SESSION['team_id']);
                                                            while ($row = db_fetch_array($res)) { ?>
                                                                <option <?= (in_array($row['id'], $users) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                            <?php  } ?>

                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <?php
                                                        $sqlStage = "select * from stages where 1";
                                                        $stageList = db_query($sqlStage);

                                                     if (!is_array($stage)) {
                                                            $val = $stage;
                                                            $stage = array();
                                                            $stage['0'] = $val;
                                                            $st_flag = 1;
                                                        }
                                                        if (!is_array($quantity)) {
                                                            $val = $quantity;
                                                            $quantity = array();
                                                            $quantity['0'] = $val;
                                                            $quantity_flag = 1;
                                                        }
                                                        if ($_GET['stage']) {
                                                            $get_stage = $_GET['stage'];
                                                            $query = db_query('select * from stages where stage_name IN ("' . implode('", "', $get_stage) . '")');
                                                            while ($row = db_fetch_array($query)) {
                                                                $stage_arr[] = $row['stage_name'];
                                                            }
                                                        ?>

                                                            <select name="stage[]" data-live-search="true" multiple class="multiselect_stage form-control ">

                                                                <?php
                                                                while ($stag = db_fetch_array($stageList)) {
                                                                ?>
                                                                    <option value="<?= $stag['stage_name'] ?>" <?= (in_array($stag['stage_name'], $stage_arr) ? 'selected' : '') ?>><?= $stag['stage_name'] ?></option>
                                                                <?php  } ?>
                                                            </select>
                                                        <?php } else { ?>

                                                            <select name="stage[]" data-live-search="true" multiple class="multiselect_stage form-control ">

                                                                <?php while ($stag = db_fetch_array($stageList)) { ?>
                                                                    <option value="<?= $stag['stage_name'] ?>" <?= (($stag['stage_name'] == $stage) ? 'selected' : '') ?>><?= $stag['stage_name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        <?php } ?>

                                                    </div>

                                                    
                                                    <div class="form-group col-md-3">

                                                        <select class="form-control" id="quantity" name="quantity[]" data-live-search="true" multiple>

                                                            <option value="1,2" <?= (in_array('1,2', $quantity) ? 'selected' : '') ?>>1 User & 2 User</option>
                                                            <option value="3" <?= (in_array('3', $quantity) ? 'selected' : '') ?>>3 Users</option>
                                                            <option value="4,5" <?= (in_array('4,5', $quantity) ? 'selected' : '') ?>>4 Users & 5 Users</option>
                                                            <option value="6" <?= (in_array('6', $quantity) ? 'selected' : '') ?>>6 Users</option>
                                                            <option value="7,8" <?= (in_array('7,8', $quantity) ? 'selected' : '') ?>>7 Users & 8 Users</option>
                                                            <option value="9" <?= (in_array('9', $quantity) ? 'selected' : '') ?>>9 Users & Above</option>

                                                        </select>
                                                    </div>
                                                    <?php if (!is_array($state)) {
                                                        $val = $state;
                                                        $state = array();
                                                        $state['0'] = $val;
                                                        $state_flag = 1;
                                                    }
                                                     ?>
                                                    <?php if (!is_array($school_board)) {
                                                        $val = $school_board;
                                                        $school_board = array();
                                                        $school_board['0'] = $val;
                                                        $school_board_flag = 1;
                                                    }
                                                     ?>
                                                          
                                                    <div class="form-group col-md-3">
                                                        <select name="state[]" class="form-control" id="multiselect_state" multiple>
                                                            <?php $ress = db_query("select * from states");
                                                            while ($row = db_fetch_array($ress)) { ?>
                                                                <option <?= (in_array($row['id'], $state) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                            <?php  } ?>

                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                <select name="school_board[]" id="multiselect_school_board" class="form-control" multiple>
                                                <option <?= (in_array('CBSE', $school_board) ? 'selected' : '') ?> value="CBSE">CBSE</option>
                                                <option <?= (in_array('ICSE', $school_board) ? 'selected' : '') ?> value="ICSE">ICSE</option>
                                                <option <?= (in_array('IB', $school_board) ? 'selected' : '') ?> value="IB">IB</option>
                                                <option <?= (in_array('IGCSE', $school_board) ? 'selected' : '') ?> value="IGCSE">IGCSE</option>
                                                <option <?= (in_array('STATE', $school_board) ? 'selected' : '') ?> value="STATE">STATE</option>
                                                <option <?= (in_array('Others', $school_board) ? 'selected' : '') ?> value="Others">Others</option>
                                                </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <button type="submit" class="btn btn-primary font-14"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                        <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                    </div>

                                                </div>

                                            </form>

                                        </div>
                                    </div>

                                </div>

                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                            <tr>
                                            <th>S.No.</th>
												<th>DR Code</th>
                                                <th>Reseller Name</th>
                                                <th>School Board</th>
                                                <th>School Name</th>
                                                <th>Number of Students</th>
                                                <th>Sub Product</th>
                                                <th>Date of Submission</th>
											    <th>Status</th>
											    <th>Stage</th>
											    <th>Closed Date</th>
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
    </div>
    <div id="myModal1" class="modal" role="dialog">


    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <?php if (count($stage) > 1) {
        $st = implode("','", $stage);
    } else if (!$st_flag) {
        $st = $_GET['stage'][0];
    } else {
        $st = $_GET['stage'];
    } ?>
    <?php if (count($caller) > 1) {
        $clr = implode(",", $caller);
    } else if (!$clr_flag) {
        $clr = $_GET['caller'][0];
    } else {
        $clr = $_GET['caller'];
    }
    if (count($status) > 1) {
        $status_arr = implode('","', $status);
    } else if (!$status_flag) {
        $status_arr = $_GET['status'][0];
    } else {
        $status_arr = $_GET['status'];
    }
    // print_r($clr); die;
    ?>
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
            "dom": '<"top"if>Brt<"bottom"ip><"clear">',
            //dom: 'Bfrtip',
            "displayLength": 15,
            "scrollX": false,
            "fixedHeader": true,
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
            ],
            language: {
                paginate: {
                    previous: '<i class="fas fa-arrow-left"></i>',
                    next: '<i class="fas fa-arrow-right"></i>'
                }
            },
            lengthMenu: [
                [15, 25, 50, 100, 500, 1000, 5000, 10000, 15000],
                ['15', '25', '50', '100', '500', '1000', '5000', '10000', '15000']
            ],
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "get_renewal_leads_partner.php", // json datasource
                type: "post", // method  , by default get
                data: function(d) {
                    d.d_from = "<?= $_GET['d_from'] ?>";
                    d.d_to = "<?= $_GET['d_to'] ?>";
                    d.d_type = "<?= $_GET['dtype'] ?>";
                    d.sub_product = '<?= json_encode($_GET['sub_product']) ?>';
                    d.stage = '<?= json_encode($_GET['stage']) ?>';
                    d.status = '<?= json_encode($_GET['status']) ?>';
                    d.users = '<?= json_encode($_GET['users']) ?>';
                    d.quantity = '<?= json_encode($_GET['quantity']) ?>';
                    d.state = '<?= json_encode($_GET['state']) ?>';
                    d.school_board = '<?= json_encode($_GET['school_board']) ?>';                  
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

            'columns': [
                { data: 'id' },
                 { data: 'code' },
                 { data: 'r_name' },
                 {data:'school_board'},
                 { data: 'school_name' },
                 { data: 'quantity' },
                 { data: 'sub_product' },
                   {data:'created_date'},
                   {data:'status'},
                  {data:'stage'},
                  {data:'close_date'},
                   
                
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

        // $('#example23').DataTable({
        //     dom: 'BLfrtip',
        //     buttons: [
        //         'copy', 'csv', 'excel', 'pdf', 'print'
        //     ]
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
                        data: 'product_id=' + productID,
                        success: function(html) {
                            $('#product_type_data').html(html);

                        },
                    });
                }
            });
        });

        function clear_search() {
            window.location = 'search_renewal_leads_partner.php';
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


            $('#partner').on('change', function() {
                //alert("hi");
                var partnerID = $(this).val();
                if (partnerID) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxusers.php',
                        data: 'partner_id=' + partnerID,
                        success: function(html) {
                            //alert(html);
                            $('#users').html(html);
                        }
                    });
                }
            });
        });

 

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
            
            function chage_stage(stage, id, ids, substage, payment_status, attachments,demo_datetime) {

if (stage != '') {
    var formData = new FormData();
    formData.append('stage', stage);
    formData.append('substage', substage);
    formData.append('lead_id', id);
    formData.append('payment_status', payment_status);
    formData.append('demo_datetime', demo_datetime);
    // Append files to FormData
    for (var i = 0; i < attachments.length; i++) {
        formData.append('attachments[]', attachments[i]);
    }

    $('#myModal1').modal('hide');
    $.ajax({
        type: 'post',
        url: 'change_stage.php',
        data: formData,
        processData: false,
        contentType: false,
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

        $('#industry').on('change', function() {

            var industry = $(this).val();
            //alert(stateID);
            if (industry) {
                $.ajax({
                    type: 'POST',
                    url: 'ajaxindustry.php',
                    data: 'industry=' + industry,
                    success: function(response) {
                        $('#sub_industry').html(response);
                    },
                    error: function() {
                        $('#sub_industry').html('There was an error!');
                    }
                });
            } else {
                $('#sub_industry').html('<option value="">Select industry first</option>');
            }
        });
    </script>


    <script>
        $(document).ready(function() {
            var wfheight = $(window).height();
            $('.dataTables_wrapper').height(wfheight - 330);
            $("#leads").tableHeadFixer();

        });

        $(document).ready(function() {
                $('.multiselect').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Lead Type',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_stage').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Stage',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_productType').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Product Type',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_status').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Status',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#users').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Submitted By',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#quantity').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Quantity',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_partner').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Partner',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_status').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Status',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_productType').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Product Type',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselect').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Stage',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselect_school_board').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select School Board',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselect_state').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select State',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
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
    <script>
        $('.form-control').on('click', function() {
            $('.multiselect-container').removeClass('show');
            $(this).addClass('hide');
        });
    </script>