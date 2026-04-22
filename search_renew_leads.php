<?php include('includes/header.php');
if ($_SESSION['user_type'] != 'EM') admin_page(); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
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
                    <li class="breadcrumb-item active">Search Leads</li>
                </ol>
            </div>
            <div class="col-md-7 col-4 align-self-center">
                <div class="d-flex m-t-10 justify-content-end">


                    <div class="">
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
                        <div class="row">
                            <div class="col-md-10">
                                <!--<h4 class="card-title">Search</h4>-->
                            </div>
                            <div class="col-md-2"><a class="btn btn-primary text-white pull-right btn-xs" id="search_toogle">Search Show/Hide</a></div>
                        </div>
                        <div class="col-12 search_form">
                            <form method="get" name="search" class="form-mate rial">
                                <div class="row">
                                    <div class="col-2 form-group">
                                        <label class="control-label text-right">Date Type:</label><select class="form-control" name="dtype">

                                            <option <?= (($_GET['dtype'] == 'created') ? 'selected' : '') ?> value="created">Created Date</option>
                                            <option <?= (($_GET['dtype'] == 'close') ? 'selected' : '') ?> value="close">Close Date</option>
                                            <option <?= (($_GET['dtype'] == 'prospecting') ? 'selected' : '') ?> value="prospecting">Prospecting Date</option>

                                        </select>
                                    </div>
                                    <div class="col-2 form-group"><label class="control-label text-right" for="d_from">Date From: </label><input type="text" autocomplete="off" value="<?php echo @$_GET['d_from'] ?>" class="datepicker form-control" id="d_from" name="d_from" placeholder="Date From" />
                                    </div>
                                    <div class="col-2 form-group"><label class="control-label text-right" for="d_to">Date to: </label> <input type="text" autocomplete="off" value="<?php echo @$_GET['d_to'] ?>" class="datepicker form-control" id="d_to" name="d_to" placeholder="Date To" />
                                    </div>
                                    <?php if (!is_array($lead_type)) {
                                        $val = $lead_type;
                                        $lead_type = array();
                                        $lead_type['0'] = $val;
                                        $lt_flag = 1;
                                    } ?>
                                    <div class="col-3 form-group"> <label class="control-label text-right">Lead Type:</label><select data-live-search="true" multiple class="selectpicker form-control " name="lead_type[]">
                                            <option value="">---Select---</option>
                                            <option <?= ((in_array('LC', $lead_type)) ? 'selected' : '') ?> value="LC">LC</option>
                                            <option <?= ((in_array('BD', $lead_type)) ? 'selected' : '') ?> value="BD">BD</option>
                                            <option <?= ((in_array('Incoming', $lead_type)) ? 'selected' : '') ?> value="Incoming">Incoming</option>
                                            <option <?= ((in_array('Internal', $lead_type)) ? 'selected' : '') ?> value="Internal">Internal</option>
                                        </select>
                                    </div>
                                    <div class="col-3 form-group">
                                        <label class="control-label text-right">Status:</label>
                                        <select class="form-control" name="status">
                                            <option value="">---Select---</option>
                                            <option <?= (($_GET['status'] == 'Approved') ? 'selected' : '') ?> value="Approved">Qualified</option>
                                            <option <?= (($_GET['status'] == 'Cancelled') ? 'selected' : '') ?> value="Cancelled">Unqualified</option>
                                            <option <?= (($_GET['status'] == 'Undervalidation') ? 'selected' : '') ?> value="Undervalidation">Under Validation</option>
                                            <option <?= (($_GET['status'] == 'Pending') ? 'selected' : '') ?> value="Pending">Pending</option>
                                            <option <?= (($_GET['status'] == 'On-Hold') ? 'selected' : '') ?> value="On-Hold">On-Hold</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3 form-group">
                                        <label class="control-label text-right">License Type:</label><select class="form-control" name="ltype">
                                            <option value="">---Select---</option>
                                            <option <?= (($_GET['ltype'] == 'Commercial') ? 'selected' : '') ?> value="Commercial">Commercial</option>
                                            <option <?= (($_GET['ltype'] == 'Education') ? 'selected' : '') ?> value="Education">Education</option>
                                            <option <?= (($_GET['ltype'] == 'Upgrade') ? 'selected' : '') ?> value="Upgrade">Upgrade</option>

                                        </select>
                                    </div>
                                    <div class="col-3 form-group">
                                        <?php
                                        if ($_SESSION['sales_manager'] != 1) {
                                            $res = db_query("select * from partners");
                                        } else {
                                            $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ")");
                                        }
                                        //print_r($res); die;

                                        ?>
                                        <label class="control-label text-right">Partners:&nbsp;</label> <select name="partner" id="partner" class="form-control">
                                            <option value="">---Select---</option>
                                            <?php while ($row = db_fetch_array($res)) { ?>
                                                <option <?= (($_GET['partner'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-3 form-group">

                                        <label class="control-label text-right">Submitted By:&nbsp;</label> <select name="users" id="users" class="form-control ">
                                            <option value="">---Select---</option>




                                        </select>
                                    </div>
                                    <div class="col-3 form-group">
                                        <?php $res2 = db_query("select * from callers");  ?>
                                        <label class="control-label text-right">Caller:&nbsp;</label> <select name="caller" id="caller" class="form-control ">
                                            <option value="">---Select---</option>
                                            <?php while ($row2 = db_fetch_array($res2)) { ?>
                                                <option <?= (($_GET['caller'] == $row2['id']) ? 'selected' : '') ?> value='<?= $row2['id'] ?>'><?= $row2['name'] ?></option>
                                            <?php } ?>
                                        </select></div>

                                </div>
                                <div class="row form-group">
                                    <div class="col-3 form-group">
                                        <?php
                                        $sqlStage = "select * from stages where 1";
                                        $stageList = db_query($sqlStage);
                                        ?>

                                        <?php if (!is_array($stage)) {
                                            $val = $stage;
                                            $stage = array();
                                            $stage['0'] = $val;
                                            $st_flag = 1;
                                        } ?>
                                        <label class="control-label text-right">Stage: </label><select name="stage[]" data-live-search="true" multiple class="selectpicker form-control ">
                                            <option value="">--Select--</option>
                                            <?php while ($stag = db_fetch_array($stageList)) { ?>
                                                <option value="<?= $stag['stage_name'] ?>" <?= (($stag['stage_name'] == $stage) ? 'selected' : '') ?>><?= $stag['stage_name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-3 form-group"><label class="control-label text-right" for="quantity">Quantity: </label>
                                        <select class="form-control" id="quantity" name="quantity">
                                            <option value="">---Select---</option>
                                            <?php for ($i = 1; $i <= 100; $i++) { ?>
                                                <option <?= (($_REQUEST['quantity'] == $i) ? 'selected' : '') ?> value="<?= $i ?>"><?= $i ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-3 form-group"><label class="control-label text-right" for="quantity">Industry: </label>
                                        <?php $res = db_query("select * from industry order by name ASC");

                                        //print_r($res); die;

                                        ?>
                                        <select name="industry" id="industry" class="form-control">
                                            <option value="">---Select---</option>
                                            <?php while ($row = db_fetch_array($res)) { ?>
                                                <option <?= (($_REQUEST['industry'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                            <?php } ?>
                                        </select>


                                    </div>

                                    <div class="col-3 form-group" id="sub_industry">
                                        <label class="control-label text-right" for="sub_industry">Sub Industry: </label>
                                        <select class="form-control" disabled>
                                            <option value="">---Select---</option>
                                        </select>
                                    </div>


                                </div>
                                <div class="row form-group">
                                    <div class="col-3 form-group"><label class="control-label text-right" for="runrate_key">Runrate/Key: </label>
                                        <select class="form-control" id="runrate_key" name="runrate_key">
                                            <option value="">---Select---</option>
                                            <option <?= (($_REQUEST['runrate_key'] == 'Runrate') ? 'selected' : '') ?> value="Runrate">Runrate</option>
                                            <option <?= (($_REQUEST['runrate_key'] == 'Key') ? 'selected' : '') ?> value="Key">Key</option>
                                        </select>
                                    </div>
                                    <div class="col-3 form-group">
                                        <label class="control-label text-right" for="os">OS: </label>
                                        <select class="form-control" id="os" name="os">
                                            <option value="">---Select---</option>
                                            <option <?= (($_REQUEST['os'] == 'MAC') ? 'selected' : '') ?> value="MAC">MAC</option>
                                            <option <?= (($_REQUEST['os'] == 'Windows') ? 'selected' : '') ?> value="Windows">Windows</option>
                                        </select>
                                    </div>
                                    <div class="col-3 form-group">
                                        <label class="control-label text-right" for="expired">Expired: </label>
                                        <select class="form-control" id="expired" name="expired">
                                            <option value="">---Select---</option>
                                            <option <?= (($_REQUEST['expired'] == 'Yes') ? 'selected' : '') ?> value="Yes">Yes</option>
                                            <option <?= (($_REQUEST['expired'] == 'No') ? 'selected' : '') ?> value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-3 form-group">
                                        <label class="control-label text-right">Include Prospecting Data:</label>
                                        <div><input class="filled-in chk-col-light-blue" <?= (($_GET['p_check'] == 'yes') ? 'checked' : '') ?> type="checkbox" value="yes" name="p_check" id="p_check" />
                                            <label for="p_check">Yes</label>&nbsp;<i class="fa fa-question-circle-o" title="Gets all the leads with stage changed within the above date range." data-toggle="tooltip"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    
                                    <div class="col-lg-2 form-group">
                                        <label class="control-label text-right" for="expired">License End Date: </label>
                                    </div>
                                    <div class="col-2 form-group">
                                        <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="datepicker" id="license_from" name="license_from" placeholder="Date From" />
                                    </div>
                                    <!--co-md-3-->

                                    <div class="col-2 form-group">
                                        <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="datepicker" id="license_to" name="license_to" placeholder="Date To" />
                                    </div>
                                    <!--co-md-3-->
                                  

                                    <div class="col-3 form-group">
                                        <input class="btn btn-primary font-14" type="submit" value="Search" />
                                        <input class="btn btn-danger font-14" type="button" value="Clear" onclick="clear_search()" />
                                    </div>
                                </div>



                            </form>
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
        <div class="right-sidebar">
            <div class="slimscrollright">
                <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                <div class="r-panel-body">
                    <ul id="themecolors" class="m-t-20">
                        <li><b>With Light sidebar</b></li>
                        <li><a href="javascript:void(0)" data-theme="default" class="default-theme">1</a></li>
                        <li><a href="javascript:void(0)" data-theme="green" class="green-theme">2</a></li>
                        <li><a href="javascript:void(0)" data-theme="red" class="red-theme">3</a></li>
                        <li><a href="javascript:void(0)" data-theme="blue" class="blue-theme working">4</a></li>
                        <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme">5</a></li>
                        <li><a href="javascript:void(0)" data-theme="megna" class="megna-theme">6</a></li>
                        <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                        <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme">7</a></li>
                        <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme">8</a></li>
                        <li><a href="javascript:void(0)" data-theme="red-dark" class="red-dark-theme">9</a></li>
                        <li><a href="javascript:void(0)" data-theme="blue-dark" class="blue-dark-theme">10</a></li>
                        <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme">11</a></li>
                        <li><a href="javascript:void(0)" data-theme="megna-dark" class="megna-dark-theme ">12</a></li>
                    </ul>
                    <ul class="m-t-20 chatonline">
                        <li><b>Chat option</b></li>
                        <li>
                            <a href="javascript:void(0)"><img src="../assets/images/users/1.jpg" alt="user-img" class="img-circle"> <span>Varun Dhavan <small class="text-success">online</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="../assets/images/users/2.jpg" alt="user-img" class="img-circle"> <span>Genelia Deshmukh <small class="text-warning">Away</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="../assets/images/users/3.jpg" alt="user-img" class="img-circle"> <span>Ritesh Deshmukh <small class="text-danger">Busy</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="../assets/images/users/4.jpg" alt="user-img" class="img-circle"> <span>Arijit Sinh <small class="text-muted">Offline</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="../assets/images/users/5.jpg" alt="user-img" class="img-circle"> <span>Govinda Star <small class="text-success">online</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="../assets/images/users/6.jpg" alt="user-img" class="img-circle"> <span>John Abraham<small class="text-success">online</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="../assets/images/users/7.jpg" alt="user-img" class="img-circle"> <span>Hritik Roshan<small class="text-success">online</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="../assets/images/users/8.jpg" alt="user-img" class="img-circle"> <span>Pwandeep rajan <small class="text-success">online</small></span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Right sidebar -->
        <!-- ============================================================== -->
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
    <?php if (count($lead_type) > 1) {
        $lt = implode("','", $lead_type);
    } else if (!$lt_flag) {
        $lt = $_GET['lead_type'][0];
    } else {
        $lt = $_GET['lead_type'];
    } ?>
    <?php include('includes/footer.php') ?>
    <script>
        $(document).ready(function() {
            $.fn.DataTable.ext.pager.numbers_length = 15;
            $('#myTable').DataTable();
            $(document).ready(function() {
                var dataTable = $('#leads').DataTable({
                    dom: 'Bfrtip',
                    "displayLength": 15,
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                    ],
                    lengthMenu: [
                        [15, 25, 50, 100, 500, 1000],
                        ['15', '25', '50', '100', '500', '1000']
                    ],

                    "searching": false,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        url: "get_renewal_leads.php", // json datasource
                        type: "post", // method  , by default get
                        data: function(d) {
                            d.d_from = "<?= $_GET['d_from'] ?>";
                            d.d_to = "<?= $_GET['d_to'] ?>";
                            d.lead_type = "<?= $lt ?>";
                            d.partner = "<?= $_GET['partner'] ?>";
                            d.caller = "<?= $_GET['caller'] ?>";
                            d.users = "<?= $_GET['users'] ?>";
                            d.status = "<?= $_GET['status'] ?>";
                            d.ltype = "<?= $_GET['ltype'] ?>";
                            d.dtype = "<?= $_GET['dtype'] ?>";
                            d.stage = "<?= $st ?>";
                            d.dash = "<?= $_GET['dash'] ?>";
                            d.caller = "<?= $_GET['caller'] ?>";
                            d.quantity = "<?= $_GET['quantity'] ?>";
                            d.industry = "<?= $_GET['industry'] ?>";
                            d.sub_industry = "<?= $_GET['sub_industry'] ?>";
                            d.runrate_key = "<?= $_GET['runrate_key'] ?>";
                            d.os = "<?= $_GET['os'] ?>";
                            d.expired = "<?= $_GET['expired'] ?>";
                            d.p_check = "<?= $_GET['p_check'] ?>";
                            d.license_to = "<?= $_GET['license_to'] ?>";
                            d.license_from = "<?= $_GET['license_from'] ?>";
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
                // Order by the grouping

            });
        });
        $('#example23').DataTable({
            dom: 'BLfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        $('#industry').on('change',function(){
		//alert("hi");
        var stateID = $(this).val();
        //alert(stateID);
        if(stateID){
            $.ajax({
                type:'POST',
                url:'ajaxindustry_search.php',
                data:'industry_id='+stateID,
                success:function(html){
					//alert(html);
                    $('#sub_industry').html(html);
                }
            }); 
        } 
    });
    
        function clear_search() {
            window.location = 'search_renew_leads.php';
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
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <style>
        table td {
            cursor: pointer;
            word-wrap: break-word;
            max-width: 120px !important;
        }
    </style>
    <script>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
    <style>
        .dropdown-menu .inner {
            height: 150px !important;
        }
    </style>