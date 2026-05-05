<?php 
include('includes/header.php');

partner_page(); ?>
<style>
    .dt-buttons {
        margin-top: 30px;
        margin-left: -185px;
        margin-bottom: 10px;
    }

    .approval-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(0,0,0,0.03);
    }
    .approval-badge.pending {
        background: #fff4e6;
        color: #c57600;
    }
    .approval-badge.approved {
        background: #e6f7ee;
        color: #1f8f4e;
    }
    .approval-badge.rejected {
        background: #fdecea;
        color: #b02a37;
    }
    .approval-badge.onboard {
        background: #e7f1ff;
        color: #1155cc;
    }

    @media (min-width: 1200px) {
        .scroll_div {
            /* overflow-x: hidden !important; */
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
                            <div class="row">
                                <div class="col">
                                    <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">
                                            <small class="text-muted">Home >Leads</small>
                                            <h4 class="font-size-14 m-0 mt-1">Leads Report</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div>

                                        <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                        <div class="dropdown dropdown-lg">

                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">
                                                <!-- <div class="col-12 search_form"> -->
                                                <form method="get" name="search" class="form-horizontal" role="form">

                                                    <div class="row">
                                                        <div class="form-group col-md-4 col-xl-3">

                                                            <select class="form-control" name="dtype">
                                                                <option value="">Select Date Type</option>
                                                                <option <?= (($_GET['dtype'] == 'created') ? 'selected' : '') ?> value="created">Created Date</option>
                                                                <option <?= (($_GET['dtype'] == 'actioned_date') ? 'selected' : '') ?> value="actioned_date">Actioned Date</option>
                                                                <option <?= (($_GET['dtype'] == 'close') ? 'selected' : '') ?> value="close">Close Date</option>
                                                                <option <?= (($_GET['dtype'] == 'stage') ? 'selected' : '') ?> value="stage">Stage Change</option>
                                                                <option <?= (($_GET['dtype'] == 'lead_status') ? 'selected' : '') ?> value="lead_status">Lead Status Change</option>                                                        </select>
                                                        </div>
                                                        <div class="form-group col-md-4 col-xl-3">
                                                            <div class="input-daterange input-group" id="datepicker-close-date">

                                                                <input type="text" autocomplete="off" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                                <input type="text" autocomplete="off" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                            </div>
                                                        </div>
                                                        <?php if (!is_array($status)) {
                                                            $val = $status;
                                                            $status = array();
                                                            $status['0'] = $val;
                                                            $status_flag = 1;
                                                        }
                                                        ?>
                                                        <div class="form-group col-md-4 col-xl-3">
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
                                                        <?php if (!is_array($lead_type)) {
                                                            $val = $lead_type;
                                                            $lead_type = array();
                                                            $lead_type['0'] = $val;
                                                            $lt_flag = 1;
                                                        }
                                                        ?>
                                                        
                                                        <?php
                                                        if (!is_array($caller)) {
                                                            $val = $caller;
                                                            $caller = array();
                                                            $caller['0'] = $val;
                                                            $clr_flag = 1;
                                                        }
                                                        ?>

                                                        <!-- </div>
                                                <div class="row"> -->
                                                        <!-- <div class="form-group col-md-3">
                                                            <?php $res2 = db_query("select * from callers");  ?>
                                                            <select name="caller[]" data-live-search="true" multiple class="form-control" id="multiselect_caller">

                                                                <?php while ($row2 = db_fetch_array($res2)) { ?>
                                                                    <option <?= (in_array($row2['id'], $caller) ? 'selected' : '') ?> value='<?= $row2['id'] ?>'><?= $row2['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div> -->
                                                            <?php 
                                                            if (!is_array($quantity)) {
                                                                $val = $quantity;
                                                                $quantity = array();
                                                                $quantity['0'] = $val;
                                                                $quantity_flag = 1;
                                                            }
                                                            //print_r($quantity);
                                                            ?>

                                                        <div class="form-group col-md-4 col-xl-3">
                                                                 <?php
                                                                 $sqlStage = "select * from tbl_mst_stage where 1";
                                                                 $stageList = db_query($sqlStage);

                                                                 if (!is_array($stage)) {
                                                                     $val = $stage;
                                                                     $stage = array();
                                                                     $stage['0'] = $val;
                                                                     $st_flag = 1;
                                                                 }
                                                                 ?>
                                                                     <select name="stage[]" id="stage" data-live-search="true" multiple class="multiselect_stage form-control ">
                                                                         <?php
                                                                         while ($stag = db_fetch_array($stageList)) {
                                                                         ?>
                                                                             <option value="<?= $stag['id'] ?>" <?= (in_array($stag['id'], $stage ?? []) ? 'selected' : '') ?>><?= $stag['name'] ?></option>
                                                                         <?php  } ?>
                                                                     </select>
                                                             </div>

                                                         <div class="form-group col-md-4 col-xl-3">

                                                             <select class="form-control" id="quantity" name="quantity[]" data-live-search="true" multiple>

                                                                 <option value="1,2" <?= (in_array('1,2', $quantity ?? []) ? 'selected' : '') ?>>1 Student & 2 Student</option>
                                                                 <option value="3" <?= (in_array('3', $quantity ?? []) ? 'selected' : '') ?>>3 Students</option>
                                                                 <option value="4,5" <?= (in_array('4,5', $quantity ?? []) ? 'selected' : '') ?>>4 Students & 5 Students</option>
                                                                 <option value="6" <?= (in_array('6', $quantity ?? []) ? 'selected' : '') ?>>6 Students</option>
                                                                 <option value="7,8" <?= (in_array('7,8', $quantity ?? []) ? 'selected' : '') ?>>7 Students & 8 Students</option>
                                                                 <option value="9" <?= (in_array('9', $quantity ?? []) ? 'selected' : '') ?>>9 Students & Above</option>
                                                             
                                                             </select>
                                                         </div>
                                                        
                                                        <div class="form-group col-md-4 col-xl-3">
                                                            <select name="state[]" class="form-control" id="multiselect_state" multiple>
                                                                <?php $ress = db_query("select * from states");
                                                                while ($row = db_fetch_array($ress)) { ?>
                                                                    <option <?= (in_array($row['id'], $state ?? []) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                <?php  } ?>

                                                            </select>
                                                        </div>
                                                                                                                <div class="form-group col-md-4 col-xl-3" id="cityD">
                                                            <?php if ($_GET['state']) { ?>

                                                                <select name="city[]" class="multiselect_city form-control" data-live-search="true" multiple>

                                                                    <?php 
                                                                    // print_r($state);die;
                                                                    $query = db_query("SELECT * FROM cities where state_id  IN (" . implode(",", $state) . ")");
                                                                    while ($row = db_fetch_array($query)) { ?>
                                                                        <option <?= (@in_array($row['id'], $city ?? []) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= $row['city'] ?></option>
                                                                    <?php } ?>
                                                                </select>

                                                            <?php } else { ?>
                                                                <div id="cityD">
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="col-md-4 col-xl-3" style="margin-top:5px">
                                                            <button type="submit" class="btn btn-primary font-14"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                        </div>
                                                    </div>

                                                </form>

                                            </div>
                                        </div>

                                        </div>
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

                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Company Name</th>
                                            <th>Customer Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Designation</th>
                                            <th>Product</th>
                                            <th>No. of Licenses</th>
                                            <th>Subscription Term</th>
                                            <th>Stage</th>
                                            <th>Proof Engagement</th>
                                            <th>Expiry Date</th>
                                            <th>Expire In</th>
                                            <th>Approval</th>
                                            <th>Created At</th>
                                            <th>Action</th>
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
    <?php if (safe_count($stage) > 1) {
        $st = safe_implode("','", $stage);
    } else if (!$st_flag) {
        $st = $_GET['stage'][0];
    } else {
        $st = $_GET['stage'];
    } ?>
    
    <?php
    if (safe_count($status) > 1) {
        $status_arr = safe_implode('","', $status);
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
            language: {
                paginate: {
                    previous: '<i class="fas fa-arrow-left"></i>',
                    next: '<i class="fas fa-arrow-right"></i>'
                }
            },
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
            ],
            lengthMenu: [
                [15, 25, 50, 100, 500, 1000, 5000, 10000, 15000],
                ['15', '25', '50', '100', '500', '1000', '5000', '10000', '15000']
            ],
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "get_orders_partner.php", // json datasource
                type: "post", // method  , by default get
                data: function(d) {
                    d.d_from = "<?= $_GET['d_from'] ?>";
                    d.d_to = "<?= $_GET['d_to'] ?>";
                    d.d_type = "<?= $_GET['dtype'] ?>";
                    d.stage = '<?= json_encode($_GET['stage']) ?>';
                    d.status = '<?= json_encode($_GET['status']) ?>';
                    d.only_my_created = '1';
                    d.list_format = 'admin';
                    d.quantity = '<?= json_encode($_GET['quantity']) ?>';
                    d.state = '<?= json_encode($_GET['state']) ?>';
                    d.city = '<?= json_encode($_GET['city']) ?>';
                },
                error: function() { // error handling
                    $(".employee-grid-error").html("");
                    $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="16">No data found on server!</th></tr></tbody>');
                    $("#leads_processing").css("display", "none");

                }
            },
            "order": [
                [14, "desc"]
            ],
            columnDefs: [{
                orderable: false,
                targets: 0
            }],

            'columns': [
                { data: 'id' },
                { data: 'company_name' },
                { data: 'customer_name' },
                { data: 'email' },
                { data: 'phone' },
                { data: 'designation' },
                { data: 'product' },
                { data: 'licenses' },
                { data: 'subscription_term' },
                { data: 'stage_id' },
                { data: 'proof_engagement_id' },
                                { data: 'expiry_date' },
                                { data: 'expire_in' },
                                { data: 'approval' },
                { data: 'created_at', className: 'text-nowrap' },
                { data: 'action', className: 'text-nowrap' }
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
            window.location = 'search_partner_lead.php';
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

        $(document).ready(function() {
            $('#multiselect').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Stage',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn:true
            });

            $('#multiselect_lead').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Lead Type',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn:true
            });

            $('#multiselect_caller').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Caller',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn:true
            });

            $('#multiselect_association').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Association Name',
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
            $('#multiselect_state').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select State',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn:true
            });
                            $('.multiselect_city').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select City',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
            $('#campaign').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Campaign',
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
            $('#quantity').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Quantity',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn:true
            });
            $('#plan_of_action').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Plan of Action',
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
            $('.multiselect_stage').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Stage',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
            });
            $('.multiselect_sub_stage').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Sub Stage',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn:true
            });
            $('#multiselectleadstatus').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Lead Status',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
            });
            $('#multiselecttag').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Tag',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#lead_source').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Lead Source',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
        });

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
            $('.dataTables_wrapper').height(wfheight - 325);
            $("#leads").tableHeadFixer();

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
    </script>
    <script>
        $('.form-control').on('click', function() {
            $('.multiselect-container').removeClass('show');
            $(this).addClass('hide');
        });

        $(document).ready(function() {
                $('#stage').on('change', function() {

                var e = $(this).val();
                    if (e) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajaxusers.php',
                            data: 'stage=' + e,
                            success: function(html) {
                                //alert(html);
                                $('#sub_stageD').html(html);
                            }
                        });
                    }
                });
            });

                            $(document).ready(function() {
                $('#multiselect_state').on('change', function() {

                var e = $(this).val();
                    if (e) {
                        $.ajax({
                            type: 'POST',
                            url: 'general_changes.php',
                            data: 'state=' + e,
                            success: function(html) {
                                //alert(html);
                                $('#cityD').html(html);
                            }
                        });
                    }
                });
            });
    </script>