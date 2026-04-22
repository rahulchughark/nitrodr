<?php include('includes/header.php');
admin_page(); ?>
<style>
    .dt-buttons {
        margin-top: 30px;
        margin-left: -191px;
        margin-bottom: 10px;
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
                            <div class="media bredcrum-title">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Leads</small>
                                    <h4 class="font-size-14 m-0 mt-1">Search Leads</h4>
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

                                            <form method="get" id="search-form" name="search">
                                                <div class="row">
                                                    <div class="form-group col-md-3">

                                                        <select class="form-control" name="dtype">
                                                            <option value="">Select Date Type</option>
                                                            <option <?= (($_GET['dtype'] == 'created') ? 'selected' : '') ?> value="created">Created Date</option>
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
                                                    if (!is_array($product)) {
                                                        $val = $product;
                                                        $product = array();
                                                        $product['0'] = $val;
                                                    }
                                                    if (!is_array($product_type)) {
                                                        $val = $product_type;
                                                        $product_type = array();
                                                        $product_type['0'] = $val;
                                                    } ?>

                                                    <?php if (!is_array($lead_type)) {
                                                        $val = $lead_type;
                                                        $lead_type = array();
                                                        $lead_type['0'] = $val;
                                                        $lt_flag = 1;
                                                    }
                                                    if (!is_array($caller)) {
                                                        $val = $caller;
                                                        $caller = array();
                                                        $caller['0'] = $val;
                                                    }
                                                     ?>

                                                   <div class="form-group col-md-3">

                                                        <select data-live-search="true" multiple class="multiselect form-control " name="lead_type[]">

                                                            <option <?= ((in_array('LC', $lead_type)) ? 'selected' : '') ?> value="LC">LC</option>
                                                            <option <?= ((in_array('BD', $lead_type)) ? 'selected' : '') ?> value="BD">BD</option>
                                                            <option <?= ((in_array('Incoming', $lead_type)) ? 'selected' : '') ?> value="Incoming">Incoming</option>
                                                            <option <?= ((in_array('Internal', $lead_type)) ? 'selected' : '') ?> value="Internal">Internal</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-3">

                                                        <select class="multiselect_status form-control" name="status[]" data-live-search="true" multiple>

                                                            <option <?= (in_array('Approved', $status) ? 'selected' : '') ?> value="Approved">Qualified</option>
                                                            <option <?= (in_array('Cancelled', $status) ? 'selected' : '') ?> value="Cancelled">Unqualified</option>
                                                            <option <?= (in_array('Undervalidation', $status) ? 'selected' : '') ?> value="Undervalidation">Under Validation</option>
                                                            <option <?= (in_array('Pending', $status) ? 'selected' : '') ?> value="Pending">Pending</option>
                                                            <option <?= (in_array('On-Hold', $status) ? 'selected' : '') ?> value="On-Hold">On-Hold</option>
                                                        </select>
                                                    </div>
                                                  
                                                </div>
                                                <div class="row">
                                                    

                                                    
                                                    <!-- <div class="form-group col-md-3">

                                                        <?php $res2 = db_query("select * from callers"); ?>

                                                        <select name="caller[]" class="multiselect_caller form-control" data-live-search="true" multiple>

                                                            <?php while ($row2 = db_fetch_array($res2)) { ?>
                                                                <option <?= (in_array($row2['id'], $caller) ? 'selected' : '') ?> value='<?= $row2['id'] ?>'><?= $row2['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div> -->

                                                    <div class="form-group col-md-3">
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
                                                        if (!is_array($industry)) {
                                                            $val = $industry;
                                                            $industry = array();
                                                            $industry['0'] = $val;
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

                                                    <div class="form-group col-md-3">
                                                        <?php $res = db_query("select * from industry order by name ASC"); ?>
                                                        <select name="industry[]" id="industry" class="form-control" data-live-search="true" multiple>
                                                            <?php while ($row = db_fetch_array($res)) { ?>
                                                                <option <?= (in_array($row['id'], $industry) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                            <?php } ?>
                                                        </select>

                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <!-- <div class="form-group col-md-3">
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
                                                    </div> -->


                                                    <div class="form-group col-md-3">
                                                        <?php if ($_GET['industry']) { ?>

                                                            <select class="form-control" id="subind" name="sub_industry[]" data-live-search="true" multiple>

                                                                <?php $query = db_query('SELECT * FROM sub_industry WHERE industry_id in ("' . implode('", "', $_GET['industry']) . '")  ORDER BY name ASC');
                                                                while ($row = db_fetch_array($query)) { ?>
                                                                    <option <?= (in_array($row['id'], $sub_industry) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                                                <?php } ?>
                                                            </select>

                                                        <?php } else { ?>
                                                            <div id="sub_industry">
                                                                <select class="form-control" name="sub_industry">
                                                                    <option value="">Select Sub Industry</option>
                                                                </select>
                                                            </div>
                                                        <?php } ?>
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
                                            <th style="width:10%">Reseller name(Submitted by)</th>
                                            <th>Lead Type</th>
                                            <th>Quantity</th>
                                            <th>Product Name</th>
                                            <th>Company Name</th>
                                            <th>Date of Submission</th>
                                            <th>Status</th>
                                            <th>Stage</th>
                                            <th>Caller Name</th>
                                            <th>Close Date</th>
                                            <th>Data Reference</th>
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

        <?php if (count($stage) > 1) {
            $st = implode("','", $stage);
            //print_r($st);die;
        } else if (!$st_flag) {
            $st = $_GET['stage'][0];
        } else {
            $st = $_GET['stage'];
        } ?>

        <?php if (count($lead_type) > 1) {
            $lt = implode("','", $lead_type);
        } else if (!$lt_flag) {
            $lt = $_GET['lead_type'][0];
        } else {
            $lt = $_GET['lead_type'];
        }

        // if (count($association_name) > 1) {
        //     $assoc_arr = implode('","', $association_name);
        // } else if (!$assoc_flag) {
        //     $assoc_arr = $_GET['association_name'][0];
        // } else {
        //     $assoc_arr = $_GET['association_name'];
        // }

        // if (count($campaign) > 1) {
        //     $campaign_arr = implode('","', $campaign);
        //     //print_r($campaign_arr);die;
        // } else if (!$campaign_flag) {
        //     $campaign_arr = $_GET['campaign'][0];
        // } else {
        //     $campaign_arr = $_GET['campaign'];
        // }
        // if (@count($users) > 1) {
        //     $users_arr = implode('","', $users);
        //     //print_r($campaign_arr);die;
        // } else if (!$users_flag) {
        //     $users_arr = $_GET['users'][0];
        // } else {
        //     $users_arr = $_GET['users'];
        // }
        ?>

        <?php include('includes/footer.php') ?>

        <script>
            $('#leads').DataTable({
                "dom": '<"top"if>Brt<"bottom"ip><"clear">',
                //dom: 'Bfrtip',
                "displayLength": 15,
                "scrollX": false,
                "fixedHeader": true,
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
                    [15, 25, 50, 100, 500, 1000, 5000, 10000, 15000],
                    ['15', '25', '50', '100', '500', '1000', '5000', '10000', '15000']
                ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "get_student_leads_admin.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                        d.d_from = "<?= $_GET['d_from'] ?>";
                        d.d_to = "<?= $_GET['d_to'] ?>";
                        d.lead_type = "<?= $lt ?>";
                        d.partner = '<?= @implode('","', $_GET['partner']) ?>';
                        d.caller = "<?= @implode("','", $_GET['caller']) ?>";
                        d.users = '<?= $users_arr ?>';
                        d.status = "<?= @implode("','", $status) ?>";
                        d.ltype = "<?= $_GET['ltype'] ?>";
                        d.dtype = "<?= $_GET['dtype'] ?>";
                        d.stage = "<?= $st ?>";
                        d.dash = "<?= $_GET['dash'] ?>";
                        d.quantity = '<?= @implode(',', $quantity) ?>';
                        d.industry = '<?= @implode('","', $industry) ?>';
                        d.sub_industry = '<?= @implode('","', $sub_industry) ?>';
                        d.runrate_key = "<?= @implode("','", $runrate_key) ?>";
                        d.os = "<?= $_GET['os'] ?>";
                        d.expired = "<?= $_GET['expired'] ?>";
                        d.product = '<?= @implode('","', $product) ?>';
                        d.product_type = '<?= @implode('","', $product_type) ?>';
                        d.association_name = '<?= $assoc_arr ?>';
                        d.campaign = '<?= $campaign_arr ?>';
                        d.validation_type = '<?= $_GET['validation_type'] ?>';
                        d.sub_pro_type = '<?= $_GET['sub_pro_type'] ?>';
                        // etc
                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");

                    }
                },
                //"initComplete": function () {
                // },
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
                        data: 'r_name'
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
                    {
                        data: 'data_ref'
                    }

                ]
            });


            function clear_search() {
                window.location = 'search_student_leads_admin.php';
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
                $('.multiselect_assoc').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Association Name',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_campaign').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Campaign',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.product_data').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Product',
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
                $('#runrate_key').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Runrate/Key',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#industry').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Industry',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#subind').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Sub Industry',
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
                $('.multiselect_caller').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Caller',
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
            });

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
                            data: 'partner=' + partnerID,
                            success: function(html) {
                                //alert(html);
                                $('#users').html(html);
                            }
                        });
                    }
                });
            });

            function stage_change(ids, id) {

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

            function chage_stage(stage, id, ids, substage, op, date1, instalment1, date2, instalment2, date3, instalment3, date4, instalment4, Psubstage) {


                if (stage != '') {
                    //$('#myModal1').modal('hide');
                    $.ajax({
                        type: 'post',
                        url: 'change_stage.php',
                        data: {
                            Psubstage: Psubstage,
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
                            instalment4
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

            $(document).ready(function() {
                //alert("hi");
                $('.product_data').on('change', function() {

                    var productID = $(this).val();

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
        </script>

        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 325);
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