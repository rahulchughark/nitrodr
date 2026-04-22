<?php include('includes/header.php');
admin_page();

$_GET['d_from'] = htmlspecialchars($_GET['d_from'], ENT_QUOTES, 'UTF-8');
$_GET['d_to'] = htmlspecialchars($_GET['d_to'], ENT_QUOTES, 'UTF-8');
$_GET['partner'] = intval($_GET['partner']);
$_GET['users'] = intval($_GET['users']);
?>
<style>
    body[data-layout=horizontal] .page-content {
        min-height: calc(100vh - 149px) !important;
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
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="media bredcrum-title">
                                        <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">
        
                                            <small class="text-muted">Home >Log a Calls</small>
                                            <h4 class="font-size-14 m-0 mt-1">Log a Calls</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="btn-group float-right" role="group">


                                        <div class="dropdown dropdown-lg">
                                            <button type="button" class="btn btn-xs btn-light ml-1  " aria-expanded="false" id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                                                <form method="get" name="search" id="search-form">


                                                    <?php if ($_SESSION['sales_manager'] != 1) {
                                                        $res = db_query("select * from partners where status='Active' and id!=45");
                                                    } else {
                                                        $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active'");
                                                    }
                                                    //print_r($res); die;

                                                    ?>

                                                    <div class="row">
                                                        <?php 
                                                            if($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN'){
                                                        ?>
                                                        <div class="form-group col-md-4">
                                                            <select name="partner" id="partner" class="form-control">
                                                                <option value="">Select Partner</option>
                                                                <?php while ($row = db_fetch_array($res)) { ?>
                                                                    <option <?= (($_GET['partner'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php } ?>
                                                        <div class="form-group col-md-4">
                                                            <?php if ($_GET['partner'] || $_SESSION['user_type'] == 'TEAM LEADER') { ?>
                                                                <select name="users" id="users" class="form-control">
                                                                    <option value="">Select User</option>
                                                                    <?php
                                                                    if($_SESSION['user_type'] == 'TEAM LEADER'){
                                                                        $callerTM = getSingleResult("SELECT caller from users where id=".$_SESSION['user_id']);
                                                                        $res = db_query("select user_id as id,name from callers where id in (" . $callerTM . ")");
                                                                        // $res = db_query("select * from users where team_id=" . $_GET['partner'] . " and status='Active'");
                                                                    }else{
                                                                        $res = db_query("select * from users where team_id=" . $_GET['partner'] . " and status='Active'");
                                                                    }
                                                                    while ($row = db_fetch_array($res)) { ?>
                                                                        <option <?= (($_GET['users'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            <?php } else { ?>
                                                                <select name="users" id="users" class="form-control">
                                                                    <option value="">Select User</option>

                                                                </select>
                                                            <?php } ?>

                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <div class="input-daterange input-group" id="datepicker-close-date">
                                                                <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                                <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                            </div>
                                                        </div>

                                                        <?php if (!is_array($call_type)) {
                                                            $val = $call_type;
                                                            $call_type = array();
                                                            $call_type['0'] = $val;
                                                            $call_type_flag = 1;
                                                        }
                                                        ?>

                                                        <div class="form-group col-md-4">
                                                            <select name="call_type[]" id="call_type" data-live-search="true" multiple class="multiselect_call form-control">

                                                                <?php
                                                                $callSubject = db_query("select * from call_subject order by subject");
                                                                while ($row = db_fetch_array($callSubject)) { ?>
                                                                    <option <?= (in_array($row['subject'], $call_type) ? 'selected' : '') ?> value='<?= $row['subject'] ?>'><?= $row['subject'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                       <div class="form-group col-md-4">
                                                            <select name="lead_status[]" data-live-search="true" multiple class="form-control" id="multiselectleadstatus">
                                                                <option <?= (@in_array('Raw Data', $lead_status ?? []) ? 'selected' : '') ?> value="Raw Data">Raw Data</option>
                                                                <option <?= (@in_array('Validation', $lead_status ?? []) ? 'selected' : '') ?> value="Validation">Validation</option>
                                                                <option <?= (@in_array('Contacted', $lead_status ?? []) ? 'selected' : '') ?> value="Contacted">Contacted</option>
                                                                <option <?= (@in_array('Qualified', $lead_status ?? []) ? 'selected' : '') ?> value="Qualified">Qualified</option>
                                                                <option <?= (@in_array('Unqualified', $lead_status ?? []) ? 'selected' : '') ?> value="Unqualified">Unqualified</option>
                                                                <option <?= (@in_array('Duplicate', $lead_status ?? []) ? 'selected' : '') ?> value="Duplicate">Duplicate</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        </div>
                                </div>
                            </div>

                            
                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Daily Visit Added Successfully!
                                </div>
                            <?php } ?>
                            <?php if ($_GET['update'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Daily Visit Updated Successfully!
                                </div>
                            <?php } ?>


                            <div class="table-responsive">

                                <table id="visit_lac" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th style="min-width: 200px">Reseller name</th>
                                            <th style="min-width: 150px">Log By</th>
                                            <th>Date of Log</th>
                                            <th>Call Type</th>
                                            <th>School Name</th>
                                            <th style="min-width: 100px">Stage</th>
                                            <th>Quantity</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>


                                </table>
                            </div>
                        </div>

                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->

    <?php
    if (safe_count($call_type) > 1) {
        $call_subject_arr = safe_implode('","', $call_type);
        //print_r($campaign_arr);die;
    } else if (!$call_type_flag) {
        $call_subject_arr = $_GET['call_type'][0];
    } else {
        $call_subject_arr = $_GET['call_type'];
    }
    if(safe_count($lead_status) > 0){
        $leadstr = safe_implode('","', ($lead_status));
    }else{
        $leadstr = '';
    }

    include('includes/footer.php') ?>
    <script>
        $('#visit_lac').DataTable({
            dom: 'Bfrtip',
            "displayLength": 15,
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
                [15, 25, 50, 100, 500, 1000, 10000, 30000, 50000],
                ['15', '25', '50', '100', '500', '1000', '10000', '30000', '50000']
            ],
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "get_calllogs.php", // json datasource
                type: "post", // method  , by default get
                data: function(d) {
                    d.d_from = "<?= htmlspecialchars($_GET['d_from'], ENT_QUOTES, 'UTF-8') ?>";
                    d.d_to = "<?= htmlspecialchars($_GET['d_to'], ENT_QUOTES, 'UTF-8') ?>";
                    d.partner = "<?= intval($_GET['partner']) ?>";
                    d.users = "<?= intval($_GET['users']) ?>";
                    d.product = '<?= intval($_GET['product']) ?>';
                    d.product_type = '<?= intval($_GET['product_type']) ?>',
                        d.ark_users = '<?= intval($_GET['ark_users']) ?>'
                        d.call_type = '<?= $call_subject_arr ?>';
                        d.lead_status = '<?= $leadstr ?>';
                },
                error: function() { // error handling
                    $(".employee-grid-error").html("");
                    $("#visit_lac").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                    $("#leads_processing").css("display", "none");

                }
            },
            language: {
                paginate: {
                    previous: '<i class="fas fa-arrow-left"></i>',
                    next: '<i class="fas fa-arrow-right"></i>'
                }
            }
        });

        $(document).ready(function() {
            $('.multiselect_call').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Call Type',
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
            $('#industry').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Industry',
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
            window.location = 'logcalls.php';
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
    </script>

    <script>
        $(document).ready(function() {
            var wfheight = $(window).height();
            $('.dataTables_wrapper').height(wfheight - 315);
            $("#visit_lac").tableHeadFixer();

        });
    </script>