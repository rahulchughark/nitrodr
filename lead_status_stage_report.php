<?php include('includes/header.php');
if ($_SESSION['user_type'] != 'REVIEWER') admin_page();
ini_set('max_execution_time', 0);
?>

<style>
    .dataTables_wrapper .dt-buttons {
        margin-top: 5px;
        margin-bottom: 5px;
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

                                            <small class="text-muted">Home >Lead Status - Stage Report</small>
                                            <h4 class="font-size-14 m-0 mt-1">Lead Status - Stage Report</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="" role="group">

                                        <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                        <div class="dropdown dropdown-lg">

                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

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
                                                        ?>

                                                        <?php
                                                        if ($_SESSION['sales_manager'] != 1) {
                                                            $res = db_query("select * from partners where status='Active'");
                                                        } else {
                                                            $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active'");
                                                        }
                                                        ?>

                                                    <!-- </div>
                                                    <div class="row"> -->


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
                                                            <option <?= (in_array($row['id'], $partner ?? []) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                            </select>
                                                        </div>

                                                        <!-- <div class="form-group col-md-3">
                                                            <?php if ($_GET['partner']) { ?>

                                                                <select name="users[]" class="multiselect_user1 form-control" data-live-search="true" multiple>

                                                                    <?php $query = db_query('SELECT * FROM users WHERE team_id in ("' . implode('", "', $_GET['partner']) . '") and status="Active"  ORDER BY name ASC');
                                                                    while ($row = db_fetch_array($query)) { ?>
                                                                        <option <?= (@in_array($row['id'], $users ?? []) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                                                    <?php } ?>
                                                                </select>

                                                            <?php } else { ?>
                                                                <div id="users">
                                                                </div>
                                                            <?php } ?>
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
                                                        
                                                        <div class="form-group col-md-3">
                                                            <select name="status[]" data-live-search="true" multiple class="form-control" id="multiselectleadqualifiedstatus">
                                                            <option <?= (@in_array('Undervalidation', $status) ? 'selected' : '') ?> value="Undervalidation">Re-Submission Required</option>
                                                            <option <?= (@in_array('Approved', $status) ? 'selected' : '') ?> value="Approved">Qualified</option>
                                                            <option <?= (@in_array('Cancelled', $status) ? 'selected' : '') ?> value="Cancelled">Unqualified</option>
                                                            <option <?= (@in_array('On-Hold', $status) ? 'selected' : '') ?> value="On-Hold">On-Hold</option>
                                                            </select>
                                                        </div>

                                                        
                                                        <div class="form-group col-md-3">
                                                        <select name="source[]" class="form-control" id="lead_source" placeholder="" multiple>
                                                                <?php $res = db_query("select * from lead_source where status=1");
                                                            while ($row = db_fetch_array($res)) { ?>
                                                                <option <?= (@in_array($row['lead_source'], $source ?? []) ? 'selected' : '') ?> value="<?= $row['lead_source'] ?>"><?= $row['lead_source'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        </div>
                                                        <?php
                                                                $sqlTag = "select * from tag where 1";
                                                                $tagList = db_query($sqlTag);
                                                                ?>

                                                                <?php if (!is_array($tag)) {
                                                                    $val = $tag;
                                                                    $tag = array();
                                                                    $tag['0'] = $val;
                                                                } ?>
                                                        <div class="form-group col-md-3">
                                                        <select name="tag[]" data-live-search="true" multiple class="form-control" id="multiselecttag">
                                                            <?php while ($tags = db_fetch_array($tagList)) { ?>
                                                                <option value="<?= $tags['id'] ?>" <?= (in_array($tags['id'], $tag) ? 'selected' : '') ?>><?= $tags['name'] ?></option>
                                                            <?php } ?>
                                                            </select>
                                                            </div> -->
                                                        <div class="form-group col-md-2">
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

                            <div class="custom-tabs mt-3">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="active" id="po-tab" data-toggle="tab" data-url="get_lead_status_stage_report.php" role="tab" aria-controls="po" aria-selected="true">Total</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a id="pi-tab" data-toggle="tab" role="tab" data-url="get_lead_status_stage_report.php?type=leads" aria-controls="pi" aria-selected="false">Lead</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a id="invoice-tab" data-toggle="tab"  data-url="get_lead_status_stage_report.php?type=opportunity" role="tab" aria-controls="invoice" aria-selected="false">Opportunity</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a id="invoice-tab" data-toggle="tab" data-url="get_lead_status_stage_report.php?type=renewal" role="tab" aria-controls="invoice" aria-selected="false">Renewal Opportunity</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="po" role="tabpanel"  aria-labelledby="po-tab">                 
                                        <div class="table-responsive" id="MyDiv">
                                            <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                                <thead>
                                                        <tr>
                                                            <th>S.No.</th>
                                                            <th>Status</th>
                                                            <th>Grand Total</th>
                                                            <th>Close Lost</th>
                                                            <th>Contacted</th>
                                                            <th>Validated</th>
                                                            <th>Demo Arranged</th>
                                                            <th>Demo Completed</th>
                                                            <th>Demo Login</th>
                                                            <th>Demo login+proposal shared</th>
                                                            <th>Proposal Shared</th>
                                                            <th>Follow-up</th>
                                                            <th>Potential</th>
                                                            <th>Quote</th>
                                                            <th>Raw Data</th>
                                                            <th>Validated Data</th>
                                                            <th>(Blank)</th>
                                                        </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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
var table;

$(document).ready(function() {
    // Initialize DataTable with default data
    loadTable("get_lead_status_stage_report.php");

    // Tab Click Event
    $('.nav-tabs a').on('click', function() {
        var newUrl = $(this).data('url'); // Get URL from data attribute
        loadTable(newUrl);
    });

    function loadTable(ajaxUrl) {
        if ($.fn.DataTable.isDataTable("#leads")) {
            table.ajax.url(ajaxUrl).load(); // Just reload data without destroying
        } else {
            table = $('#leads').DataTable({
                dom: 'Bfrtip',
                "searching": false,
                "displayLength": 15,
                language: {
                    paginate: {
                        previous: '<i class="fas fa-arrow-left"></i>',
                        next: '<i class="fas fa-arrow-right"></i>'
                    }
                },
                buttons: [
                    <?php if($_SESSION['download_status'] == 1){ ?>
                    'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength',
                    <?php } else { ?> 'pageLength' <?php } ?>
                ],
                lengthMenu: [
                    [5, 15, 25, 50, 100, 500, 1000],
                    ['5', '15', '25', '50', '100', '500', '1000']
                ],
                "stateSave": true,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: ajaxUrl, // Dynamic URL
                    type: "post",
                    data: function(d) {
                        d.d_from = "<?= $_GET['d_from'] ?>";
                        d.d_to = "<?= $_GET['d_to'] ?>";
                        d.d_type = "<?= $_GET['dtype'] ?>";
                        d.users = '<?= json_encode($_GET['users']) ?>';
                        d.state = '<?= json_encode($_GET['state']) ?>';
                        d.school_board = '<?= json_encode($_GET['school_board']) ?>';
                        d.partner = '<?= json_encode($_GET['partner']) ?>';
                        d.tag = '<?= json_encode($_GET['tag']) ?>';
                        d.source = '<?= json_encode($_GET['source']) ?>';
                        d.status = '<?= json_encode($_GET['status']) ?>';
                        d.product_typeDS = '<?= json_encode($_GET['product_typeDS']) ?>';
                        d.productDS = '<?= json_encode($_GET['productDS']) ?>';
                    },
                    error: function() {
                        $("#leads tbody").html('<tr><th colspan="17">No data found on server!</th></tr>');
                        $("#leads_processing").css("display", "none");
                    }
                },
                "order": [
                    [1, "desc"]
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],
                'columns': [
                    { data: 'id' },
                    { data: 'status' },
                    { data: 'grand_total' },
                    { data: 'close_lost' },
                    { data: 'contacted' },
                    { data: 'validated' },
                    { data: 'demo_arranged' },
                    { data: 'demo_completed' },
                    { data: 'demo_login' },
                    { data: 'login_proposal' },
                    { data: 'proposal_shared' },
                    { data: 'follow_up' },
                    { data: 'potential' },
                    { data: 'quote' },
                    { data: 'raw_data' },
                    { data: 'validated_data' },
                    { data: 'blank' },
                ]
            });
        }
    }
});




            $(document).ready(function() {
                $('.multiselect_user1').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select User',
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
                $('#multiselectleadqualifiedstatus').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Qualified Status',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
            });

            function clear_search() {
                window.location = 'lead_status_stage_report.php';
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
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 370);
                $("#leads").tableHeadFixer();

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
                            data: 'product_id=' + productID,
                            success: function(html) {
                                $('#product_type_data').html(html);

                            },
                        });
                    }
                });
            });

            function convertDate(dateString) {
                var p = dateString.split(/\D/g)
                return [p[2], p[1], p[0]].join("-")
            }

            function stageModel(e,status){
                $.ajax({
                type: 'POST',
                url: 'viewStatusStageReportModel.php',
                data: {
                    lead_status: status,
                    type: e,
                    d_from:"<?= $_GET['d_from'] ?>",
                    d_to:"<?= $_GET['d_to'] ?>",
                    d_type:"<?= $_GET['dtype'] ?>",
                    users:'<?= json_encode($_GET['users']) ?>',
                    state:'<?= json_encode($_GET['state']) ?>',
                    school_board:'<?= json_encode($_GET['school_board']) ?>',
                    partner:'<?= json_encode($_GET['partner']) ?>',
                    tag:'<?= json_encode($_GET['tag']) ?>',
                    source:'<?= json_encode($_GET['source']) ?>',
                    status:'<?= json_encode($_GET['status']) ?>',
                },
                success: function(response) {
                    $("#myModal1").html();
                    $("#myModal1").html(response);
                    $('#myModal1').modal('show');
                }

            });
            }

            $(document).ready(function() {
                $('#stage').on('change', function() {
                var e = $(this).val();
                    if (e) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajaxusers.php',
                            data: 'stage=' + e,
                            success: function(html) {
                                $('#sub_stageD').html(html);
                            }
                        });
                    }
                });
            });

            $(document).ready(function() {
                $('#partner').on('change', function() {
                    var partnerID = $(this).val();
                    if (partnerID) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajaxusers.php',
                            data: 'partner=' + partnerID,
                            success: function(html) {
                                $('#users').html(html);
                            }
                        });
                    }
                });
            });
        </script>