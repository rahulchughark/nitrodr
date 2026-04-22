<?php include('includes/header.php');

        if (!$_GET['partner']) {
            $partner = array();
        } else {
            $partner = $_GET['partner'];
        }
        if (!$_GET['stage']) {
            $stage = array();
        } else {
            $stage = $_GET['stage'];
        }
        ?> 
<!-- Start right Content here -->
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
                                    <div class="media bredcrum-title">
                                        <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">

                                            <small class="text-muted">Home >Renewal Leads</small>
                                            <h4 class="font-size-14 m-b-14 mt-1">Renewal Leads</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="btn-group float-right">
                                        <div class="d-flex  justify-content-end">

                                            <?php if ($_SESSION['user_type'] == 'RM' || $_SESSION['user_type'] == 'RENEWAL TL' || $_SESSION['user_type'] == 'EM') { ?> <div class="">
                                                    <a href="javascript:void(0);" onclick="show_import()"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="Import Renewal Leads" class="right-side bottom-right waves-effect waves-light btn-light btn btn-circle btn-xs pull-right ml-1"><i class="ti-plus "></i></button></a>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                    <div class="btn-group float-right" role="group" >

                                        <div class="dropdown dropdown-lg">

                                            <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">
                                                <form method="get" id="search-form" name="search">
                                                <div class="row">
                                                            <div class="form-group col-md-4 col-xl-3">

                                                                <select class="form-control" name="dtype">
                                                                    <option value="">Select Date Type</option>
                                                                    <option <?= (($_GET['dtype'] == 'created') ? 'selected' : '') ?> value="created">Created Date</option>
                                                                    <option <?= (($_GET['dtype'] == 'actioned_date') ? 'selected' : '') ?> value="actioned_date">Actioned Date</option>
                                                                    <option <?= (($_GET['dtype'] == 'close') ? 'selected' : '') ?> value="close">Close Date</option>
                                                                </select>
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
                                                            }
                                                            ?>

                                                            <?php
                                                            if ($_SESSION['sales_manager'] != 1) {
                                                                $res = db_query("select * from partners where status='Active'");
                                                            } else {
                                                                $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active'");
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
                                                            <?php if (!is_array($sub_product)) {
                                                                $val = $sub_product;
                                                                $sub_product = array();
                                                                $sub_product['0'] = $val;
                                                                $sub_product_flag = 1;
                                                            }
                                                            ?>
                                                            <div class="form-group col-md-4 col-xl-3">
                                                                <select name="sub_product[]" class="multiselect_productType form-control" multiple>
                                                                    <?php $res = db_query("select * from tbl_product_pivot where status=1 and product_id='2'");
                                                                    while ($row = db_fetch_array($res)) { ?>
                                                                        <option <?= (in_array($row['id'], $sub_product) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['product_type'] ?></option>
                                                                    <?php  } ?>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">


                                                            <div class="form-group col-md-4 col-xl-3">
                                                                <?php
                                                                if (!is_array($partner)) {
                                                                    $val = $partner;
                                                                    $partner = array();
                                                                    $partner['0'] = $val;
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

                                                            <div class="form-group col-md-4 col-xl-3">
                                                                <?php if ($_GET['partner']) { ?>

                                                                    <select name="users[]" class="multiselect_user1 form-control" data-live-search="true" multiple>

                                                                        <?php $query = db_query('SELECT * FROM users WHERE team_id in ("' . implode('", "', $_GET['partner']) . '") and status="Active"  ORDER BY name ASC');
                                                                        while ($row = db_fetch_array($query)) { ?>
                                                                            <option <?= (in_array($row['id'], $users) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                                                        <?php } ?>
                                                                    </select>

                                                                <?php } else { ?>
                                                                    <div id="users">
                                                                    </div>
                                                                <?php } ?>
                                                            </div>

                                                            <div class="form-group col-md-4 col-xl-3">
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

                                                            
                                                            <div class="form-group col-md-4 col-xl-3">

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
                                                                
                                                            <div class="form-group col-md-4 col-xl-3">
                                                                <select name="state[]" class="form-control" id="multiselect_state" multiple>
                                                                    <?php $ress = db_query("select * from states");
                                                                    while ($row = db_fetch_array($ress)) { ?>
                                                                        <option <?= (in_array($row['id'], $state) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                    <?php  } ?>

                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-4 col-xl-3">
                                                        <select name="school_board[]" id="multiselect_school_board" class="form-control" multiple>
                                                        <option <?= (in_array('CBSE', $school_board) ? 'selected' : '') ?> value="CBSE">CBSE</option>
                                                        <option <?= (in_array('ICSE', $school_board) ? 'selected' : '') ?> value="ICSE">ICSE</option>
                                                        <option <?= (in_array('IB', $school_board) ? 'selected' : '') ?> value="IB">IB</option>
                                                        <option <?= (in_array('IGCSE', $school_board) ? 'selected' : '') ?> value="IGCSE">IGCSE</option>
                                                        <option <?= (in_array('STATE', $school_board) ? 'selected' : '') ?> value="STATE">STATE</option>
                                                        <option <?= (in_array('Others', $school_board) ? 'selected' : '') ?> value="Others">Others</option>
                                                        </select>
                                                            </div>

                                                            <div class="col-md-3">
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

                            

                            <?php if ($_GET['update'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Order Updated Successfully!
                                </div>
                            <?php } ?>
                           
                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Order Added Successfully!
                                </div>
                            <?php } ?>


                            

                            <div class="table-responsive">

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
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

</div>


<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
<div id="myModal1" class="modal" role="dialog">


</div>
 <?php if (count($stage) > 1) {
            $st = implode("','", $stage);
            //print_r($st);die;
        } else if (!$st_flag) {
            $st = $_GET['stage'][0];
        } else {
            $st = $_GET['stage'];
        } ?>

        <?php include('includes/footer.php') ?>
<script>


    $('#leads').DataTable({
        dom: 'Bfrtip',
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
                   <?php if($_SESSION['download_status'] == 1){ ?>
                    'copy', 'csv', 'excel',  'print', 'pageLength',
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    }
                <?php }else{ ?> 'pageLength'  <?php } ?>    
                ],
        lengthMenu: [
            [15, 25, 50, 100, 500, 1000],
            ['15', '25', '50', '100', '500', '1000']
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "get_renewal_leads_admin.php", // json datasource
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
                    d.partner = '<?= json_encode($_GET['partner']) ?>';
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
    // $('#example23').DataTable({
    //     dom: 'Bfrtip',
    //     buttons: [
    //         'copy', 'csv', 'excel', 'pdf', 'print'
    //     ]
    // });

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

    function show_import() {
        $.ajax({
            type: 'POST',
            url: 'import_renew.php',
            success: function(response) {
                $("#myModal1").html();
                $("#myModal1").html(response);

                $('#myModal1').modal('show');
                $('.preloader').hide();
            }
        });

    }

    function clear_search() {
        window.location = 'search_renewal_lead.php';
    }

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
                $('.multiselect_user1').multiselect({
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

    $(function() {
        $('#datepicker-close-date').datepicker({
            format: 'yyyy-mm-dd',
            //startDate: '-3d',
            autoclose: !0

        });

    });

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

    function convertDate(dateString) {
        var p = dateString.split(/\D/g)
        return [p[2], p[1], p[0]].join("-")
    }

</script>
<script>
    $(document).ready(function() {
        var wfheight = $(window).height();
        $('.dataTables_wrapper').height(wfheight - 350);
        $("#leads").tableHeadFixer();

    });

    $(document).ready(function() {
                $('#partner').on('change', function() {
                    //alert("hi");
                    var partnerID = $(this).val();
                    if (partnerID) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajaxusers.php',
                            data: 'partner=' + partnerID,
                            success: function(html) {
                                //alert(html);
                                $('#users').html(html);
                            }
                        });
                    }
                });
            });
</script>
<!-- <style>
	.page-content {margin-top: 0px !important;}
</style> -->