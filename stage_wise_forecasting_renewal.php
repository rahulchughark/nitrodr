<?php include('includes/header.php');
if ($_SESSION['user_type'] != 'REVIEWER') admin_page();
ini_set('max_execution_time', 0);
?>

<style>
    .dataTable td {
        text-align: center;
        padding: 12px 20px 12px 20px;
    }

    .dataTables_wrapper .dt-buttons {
        margin-bottom: 0.8rem;
    }

    b,
    strong {
        font-weight: 600;
    }

    .table>thead>tr>th,
    .table tbody td {
        border: 1px solid #bdbdbd;
    }

    .table>thead>tr>th {
        border-top: 0;
    }

    thead th:first-child,
    thead th:last-child {
        border-radius: 0;
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

                                            <small class="text-muted">Home >Stage Wise Forecasting Existing</small>
                                            <h4 class="font-size-14 m-0 mt-1">Stage Wise Forecasting Existing</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div role="group">
                                        <?php if ($_SESSION['user_type'] == 'ADMIN') { ?>
                                            <!-- <a href="javascript:void(0);" onclick="show_import()"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="Import Opportunity" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-plus "></i></button></a> -->
                                        <?php } ?>
                                        <?php if ($_SESSION['download_status'] == 1) {
                                            $stageStr = $stage ? implode("','", $stage) : [];
                                            $substageStr = $substage ? implode("','", $substage) : [];
                                            $partnerStr = $partner ? implode(",", $partner) : [];
                                            $usersStr = $users ? implode(",", $users) : [];
                                            $stateStr = $state ? implode(",", $state) : [];
                                            $tagStr = $tag ? implode(",", $tag) : [];
                                            $statusStr = $status ? implode(",", $status) : [];
                                            $lead_status_str = $lead_status ? implode(",", $lead_status) : [];
                                            $source_str = $source ? implode(",", $source) : [];

                                        ?>
                                        <?php } ?>
                                        <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                        <div class="dropdown dropdown-lg">
                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">
                                                <form method="get" name="search" class="form-horizontal" role="form">

                                                    <?php if (!is_array($partner)) {
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

                                                    <div class="row">
                                                        <div class="form-group col-md-4 col-xl-3">
                                                            <select name="fin_year" class="form-control" id="fin_year">
                                                                <?php 
                                                                    $currentYear = date('Y');
                                                                    $currentMonth = date('n');
                                                                    if ($currentMonth >= 4) {
                                                                        $startYear = $currentYear;
                                                                    } else {
                                                                        $startYear = $currentYear - 1;
                                                                    }
                                                                    for ($year = $startYear; $year >= 2023; $year--) {
                                                                        $nextYearShort = substr($year + 1, -2); // last two digits
                                                                 ?>       
                                                                        <option <?= (($_GET['fin_year'] == $year) ? 'selected' : '') ?> value="<?= $year ?>"><?= $year . "-" . $nextYearShort ?></option>
                                                            <?php    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <select name="dtype" class="form-control" id="date_type">
                                                                <option value="">Select Date Type</option>
                                                                <option <?= (($_GET['dtype'] == 'created') ? 'selected' : '') ?> value="created">Created Date</option>
                                                                <option <?= (($_GET['dtype'] == 'actioned_date') ? 'selected' : '') ?> value="actioned_date">Actioned Date</option>
                                                                <option <?= (($_GET['dtype'] == 'close') ? 'selected' : '') ?> value="close">Close Date</option>
                                                                <option <?= (($_GET['dtype'] == 'stage') ? 'selected' : '') ?> value="stage">Stage Date</option>
                                                                <option <?= (($_GET['dtype'] == 'lead_status') ? 'selected' : '') ?> value="lead_status">Lead Status Change</option>
                                                                <option <?= (($_GET['dtype'] == 'sub_stage') ? 'selected' : '') ?> value="sub_stage">Sub Stage</option>
                                                                <option <?= (($_GET['dtype'] == 'opportunity_converted') ? 'selected' : '') ?> value="opportunity_converted">Opportunity Converted</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <div class="input-daterange input-group" id="datepicker-close-date">
                                                                <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                                <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                            </div>
                                                        </div>
                                                        <?php if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN') { ?>
                                                            <div class="form-group col-md-3">
                                                                <select name="just_partner" class=" form-control" data-live-search="true">
                                                                        <option value=''>Select Just Partners</option>
                                                                        <option value='Yes' <?= $_GET['just_partner'] == 'Yes' ? 'selected' : '' ?>>Yes</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <select name="partner[]" id="partner" class="multiselect_partner form-control" data-live-search="true" multiple>
                                                                    <?php while ($row = db_fetch_array($res)) { ?>
                                                                        <option <?= (in_array($row['id'], $partner ?? []) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>

                                                        <?php } ?>


                                                        <div class="form-group col-md-3">
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

                                                        <?php
                                                        $sqlStage = "select * from stages where stage_name not in ('PO/CIF Issued','Billing')";
                                                        $stageList = db_query($sqlStage);
                                                        ?>

                                                        <?php if (!is_array($stage)) {
                                                            $val = $stage;
                                                            $stage = array();
                                                            $stage['0'] = $val;
                                                            $st_flag = 1;
                                                        } ?>
                                                        <div class="form-group col-md-3">
                                                            <select name="stage[]" data-live-search="true" multiple class="form-control" id="multiselect">
                                                                <?php while ($stag = db_fetch_array($stageList)) { ?>
                                                                    <option value="<?= $stag['stage_name'] ?>" <?= (in_array($stag['stage_name'], $stage ?? []) ? 'selected' : '') ?>><?= $stag['stage_name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3" id="sub_stageD">
                                                            <?php if ($_GET['stage']) { ?>

                                                                <select name="sub_stage[]" class="multiselect_sub_stage form-control" data-live-search="true" multiple>

                                                                    <?php $query = db_query('select * from sub_stage where stage_name IN ("' . implode('", "', $stage) . '")');
                                                                    while ($row = db_fetch_array($query)) { ?>
                                                                        <option <?= (@in_array($row['name'], $sub_stage ?? []) ? 'selected' : '') ?> value="<?= $row['name'] ?>"><?= ucwords($row['name']) ?></option>
                                                                    <?php } ?>
                                                                </select>

                                                            <?php } else { ?>
                                                                <div id="sub_stageD">
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <select name="lead_status[]" data-live-search="true" multiple class="form-control" id="multiselectleadstatus">
                                                                <option <?= (@in_array('Raw Data', $lead_status ?? []) ? 'selected' : '') ?> value="Raw Data">Raw Data</option>
                                                                <option <?= (@in_array('Validation', $lead_status ?? []) ? 'selected' : '') ?> value="Validation">Validation</option>
                                                                <option <?= (@in_array('Contacted', $lead_status ?? []) ? 'selected' : '') ?> value="Contacted">Contacted</option>
                                                                <option <?= (@in_array('Qualified', $lead_status ?? []) ? 'selected' : '') ?> value="Qualified">Qualified</option>
                                                                <option <?= (@in_array('Unqualified', $lead_status ?? []) ? 'selected' : '') ?> value="Unqualified">Unqualified</option>
                                                                <option <?= (@in_array('Duplicate', $lead_status ?? []) ? 'selected' : '') ?> value="Duplicate">Duplicate</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-md-3">
                                                            <select name="status[]" data-live-search="true" multiple class="form-control" id="multiselectleadqualifiedstatus">
                                                                <!-- <option value="">---Select---</option> -->
                                                                <option <?= (@in_array('Undervalidation', $status ?? []) ? 'selected' : '') ?> value="Undervalidation">Re-Submission Required</option>
                                                                <option <?= (@in_array('Approved', $status ?? []) ? 'selected' : '') ?> value="Approved">Qualified</option>
                                                                <option <?= (@in_array('Cancelled', $status ?? []) ? 'selected' : '') ?> value="Cancelled">Unqualified</option>
                                                                <option <?= (@in_array('On-Hold', $status ?? []) ? 'selected' : '') ?> value="On-Hold">On-Hold</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-md-3">
                                                            <select name="school_board[]" id="multiselect_school_board" class="form-control" multiple>
                                                                <option <?= (@in_array('CBSE', $school_board ?? []) ? 'selected' : '') ?> value="CBSE">CBSE</option>
                                                                <option <?= (@in_array('ICSE', $school_board ?? []) ? 'selected' : '') ?> value="ICSE">ICSE</option>
                                                                <option <?= (@in_array('IB', $school_board ?? []) ? 'selected' : '') ?> value="IB">IB</option>
                                                                <option <?= (@in_array('IGCSE', $school_board ?? []) ? 'selected' : '') ?> value="IGCSE">IGCSE</option>
                                                                <option <?= (@in_array('STATE', $school_board ?? []) ? 'selected' : '') ?> value="STATE">STATE</option>
                                                                <option <?= (@in_array('Others', $school_board ?? []) ? 'selected' : '') ?> value="Others">Others</option>
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
                                                                    <option value="<?= $tags['id'] ?>" <?= (@in_array($tags['id'], $tag ?? []) ? 'selected' : '') ?>><?= $tags['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-md-3">
                                                            <select name="state[]" class="form-control" id="multiselect_state" multiple>
                                                                <?php $ress = db_query("select * from states");
                                                                while ($row = db_fetch_array($ress)) { ?>
                                                                    <option <?= (@in_array($row['id'], $state ?? []) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                <?php  } ?>

                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3" id="cityD">
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
                                                        <div class="form-group col-md-3">
                                                            <select name="upsell" class="form-control" data-live-search="true">
                                                                <option <?= $_GET['upsell'] == '0' ? 'selected' : '' ?> value="0">Excluding Upsell Data</option>
                                                                <option <?= $_GET['upsell'] == '2' ? 'selected' : '' ?> value="2">Including Upsell Data</option>
                                                                <option <?= $_GET['upsell'] == '1' ? 'selected' : '' ?> value="1">Upsell Data</option>
                                                            </select>
                                                        </div>
                                                                                                            <!-- <div class="row">
                                                        <div class="form-group col">
                                                            <?php
                                                            if (!is_array($main_product)) {
                                                                $val = $main_product;
                                                                $main_product = array();
                                                                $main_product['0'] = $val;
                                                            }
                                                            $resPro = db_query("select * from tbl_main_product_opportunity where status=1");
                                                            ?>

                                                            <select name="main_product[]" id="main_product" class="multiselect_product form-control" data-live-search="true" multiple>
                                                            <?php while ($rowP = db_fetch_array($resPro)) { ?>
                                                            <option <?= (in_array($rowP['id'], $main_product ?? []) ? 'selected' : '') ?> value='<?= $rowP['id'] ?>'><?= $rowP['name'] ?></option>
                                                        <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div> -->
                                                    <!-- <div class="row">
                                                        <div class="form-group col">
                                                            <?php if ($_GET['main_product']) { ?>
                                                                
                                                                <select name="products[]" class="multiselect_products1 form-control" data-live-search="true" multiple>

                                                                    <?php $query = db_query('SELECT * FROM tbl_product_opportunity WHERE main_product_id in ("' . implode('", "', $_GET['main_product']) . '") and status=1');
                                                                    while ($row = db_fetch_array($query)) { ?>
                                                                        <option <?= (@in_array($row['id'], $products ?? []) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['product_name']) ?></option>
                                                                    <?php } ?>
                                                                </select>

                                                            <?php } else { ?>
                                                                <div id="products">
                                                                </div>
                                                            <?php } ?>
                                                        </div>
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

                            <div class="table-responsive" id="MyDiV">
                                <table id="leads" class="table display nowrap table-striped text-center" width="100%">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="align-middle">S.No.</th>
                                            <th rowspan="2" class="align-middle">Stage</th>
                                            <th rowspan="2" class="align-middle">Forecasting Percentage</th>
                                            <th colspan="13">Existing</th>
                                        </tr>
                                        <tr>
                                            <th>Opportunities</th>
                                            <th>Student Count</th>
                                            <th>Model</th>
                                            <th>Stop Motion</th>
                                            <th>Cubo</th>
                                            <th>Electronics Foundation</th>
                                            <th>Electronics Advanced</th>
                                            <th>IOT / Sensor</th>
                                            <th>Robotics Kit</th>
                                            <th>Creality K1 Max</th>
                                            <th>Maker Lab</th>
                                            <th>Billing Value</th>
                                            <th>Forecast Value</th>
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

        <?php include('includes/footer.php');
        $_GET['upsell'] = $_GET['upsell'] != '' ? $_GET['upsell'] : '0';
        ?>
        <script>
            $('#leads').DataTable({
                dom: 'Bfrtip',
                "displayLength": 15,
                buttons: [
                    <?php if ($_SESSION['download_status'] == 1) { ?> 'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength',

                    <?php } else { ?> 'pageLength'
                    <?php } ?>

                ],
                lengthMenu: [
                    [5, 15, 25, 50, 100, 500, 1000],
                    ['5', '15', '25', '50', '100', '500', '1000']
                ],
                "stateSave": true,
                "processing": true,
                "serverSide": true,
                "searching": false,
                "ajax": {
                    url: "get_stage_wise_forecasting_renewal.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                        d.d_from = "<?= $_GET['d_from'] ?>";
                        d.d_to = "<?= $_GET['d_to'] ?>";
                        d.upsell = "<?= $_GET['upsell'] ?>";
                        d.fin_year = "<?= $_GET['fin_year'] ?>";
                        d.just_partner = "<?= $_GET['just_partner'] ?>";
                        d.main_product = '<?= json_encode($_GET['main_product']) ?>';
                        d.products = '<?= json_encode($_GET['products']) ?>';
                        d.partner = '<?= json_encode($_GET['partner']) ?>';
                        d.users = '<?= json_encode($_GET['users']) ?>';
                        d.d_type = "<?= $_GET['dtype'] ?>";
                        d.source = '<?= json_encode($_GET['source']) ?>';
                        d.stage = '<?= json_encode($_GET['stage']) ?>';
                        d.sub_stage = '<?= json_encode($_GET['sub_stage']) ?>';
                        d.state = '<?= json_encode($_GET['state']) ?>';
                        d.city = '<?= json_encode($_GET['city']) ?>';
                        d.school_board = '<?= json_encode($_GET['school_board']) ?>';
                        d.tag = '<?= json_encode($_GET['tag']) ?>';
                        d.lead_status = '<?= json_encode($_GET['lead_status']) ?>';
                        d.status = '<?= json_encode($_GET['status']) ?>';
                    },

                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="14">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");

                    }
                },
                language: {
                    paginate: {
                        previous: '<i class="fas fa-arrow-left"></i>',
                        next: '<i class="fas fa-arrow-right"></i>'
                    }
                },
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],

                'columns': [{
                        data: 'id'
                    },
                    {
                        data: 'stage'
                    },
                    {
                        data: 'percentage'
                    },
                    {
                        data: 'opp_new'
                    },
                    {
                        data: 'quantity_new'
                    },
                    {
                        data: 'model'
                    },
                    {
                        data: 'stop_motion'
                    },
                    {
                        data: 'cubo'
                    },
                    {
                        data: 'foundation'
                    },
                    {
                        data: 'advanced'
                    },
                    {
                        data: 'iot'
                    },
                    {
                        data: 'robotics'
                    },
                    {
                        data: 'creality'
                    },
                    {
                        data: 'maker_lab'
                    },
                    {
                        data: 'billing_new'
                    },
                    {
                        data: 'value_new'
                    },
                ]
            });

            $(document).ready(function() {
                $('.multiselect_partner').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Partner',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });
                $('.multiselect_product').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Main Product',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });
                $('.multiselect_user1').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select User',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });
                $('.multiselect_products1').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Product',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });
            });

            function clear_search() {
                window.location = 'stage_wise_forecasting_renewal.php';
            }

            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });

            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 320);
                $("#leads").tableHeadFixer();

            });


            function convertDate(dateString) {
                var p = dateString.split(/\D/g)
                return [p[2], p[1], p[0]].join("-")
            }

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

            $(document).ready(function() {
                $('#main_product').on('change', function() {
                    //alert("hi");
                    var main_product = $(this).val();
                    if (main_product) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajaxusers.php',
                            data: 'main_product=' + main_product,
                            success: function(html) {
                                //alert(html);
                                $('#products').html(html);
                            }
                        });
                    }
                });
            });
                        $(document).ready(function() {
                $('#multiselect').on('change', function() {

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
            $(document).ready(function() {
                $('.multiselect_partner').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Partner',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    maxHeight: 150
                });
                $('.multiselect_status').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Status',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });
                $('.multiselect_user1').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select User',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });
                $('.multiselect_sub_stage').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Sub Stage',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });
                $('.multiselect_productType').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Product Type',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });
                $('#multiselect').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Stage',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });
                $('#multiselectleadstatus').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Lead Status',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });
                $('#multiselectleadqualifiedstatus').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Qualified Status',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });

                $('#multiselect_state').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select State',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });
                $('.multiselect_city').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select City',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });
                $('#multiselect_school_board').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select School Board',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });
                $('#lead_source').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Lead Source',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });
                $('#multiselecttag').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Tag',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn: true
                });
            });
        </script>