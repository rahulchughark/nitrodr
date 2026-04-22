<?php include('includes/header.php');
partner_page();
$_GET['d_from'] = htmlspecialchars($_GET['d_from'], ENT_QUOTES, 'UTF-8');
$_GET['d_to'] = htmlspecialchars($_GET['d_to'], ENT_QUOTES, 'UTF-8');
$_GET['partner'] = intval($_GET['partner']);
$_GET['users'] = intval($_GET['users']);
?>
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

                                    <small class="text-muted">Home >Log a Calls</small>
                                    <h4 class="font-size-14 m-0 mt-1">Log a Calls</h4>
                                </div>
                            </div>

                            <div class="btn-group float-right" role="group" style="margin-top:12px;">

                            <button type="button" class="btn btn-xs btn-light ml-1  " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                <div class="dropdown dropdown-lg">



                                    <?php if ($_SESSION['user_type'] != 'USR') { ?>
                                        <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">
                                            <form method="get" name="search" id="search-form">
                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <select name="users[]" id="users" data-live-search="true" multiple class="multiselect_users form-control">
                                                            <?php if (!isset($users)) {
                                                                $users = array();
                                                            } else {
                                                                $users = $users;
                                                            }
                                                            $users_select = db_query("select * from users where status='Active' and team_id=" . $_SESSION['team_id'] . " order by name");
                                                            while ($row = db_fetch_array($users_select)) { ?>
                                                                <option <?= (in_array($row['id'], $users) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <?php if (!is_array($campaign)) {
                                                        $val = $campaign;
                                                        $campaign = array();
                                                        $campaign['0'] = $val;
                                                        $campaign_flag = 1;
                                                    }
                                                    if (!is_array($call_type)) {
                                                        $val = $call_type;
                                                        $call_type = array();
                                                        $call_type['0'] = $val;
                                                    }
                                                    ?>
                                                    <div class="form-group col-md-4">
                                                        <select name="campaign[]" id="campaign" data-live-search="true" multiple class="multiselect_campaign form-control">
                                                            <?php
                                                            $campaign_select = db_query("select * from campaign where status=1 order by id desc");
                                                            while ($row = db_fetch_array($campaign_select)) { ?>
                                                                <option <?= (in_array($row['id'], $campaign) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label class="control-label">Exclude Campaign Data: </label>
                                                        <br>
                                                        <input class="filled-in chk-col-light-blue" <?= (($_GET['campaign_check'] == 'yes') ? 'checked' : '') ?> type="checkbox" value="yes" name="campaign_check" id="campaign_check" />
                                                        <label for="campaign_check">Yes</label>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <div class="input-daterange input-group" id="datepicker-close-date">
                                                            <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                            <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <select name="call_type[]" id="call_type" data-live-search="true" multiple class="form-control">

                                                            <?php
                                                            $callSubject = db_query("select * from call_subject where subject not like '%visit%' order by subject");
                                                            while ($row = db_fetch_array($callSubject)) { ?>
                                                                <option <?= (in_array($row['subject'], $call_type) ? 'selected' : '') ?> value='<?= $row['subject'] ?>'><?= $row['subject'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php } else { ?>
                                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" id="filter-container" role="menu">
                                                        <form method="get" name="search" id="search-form">
                                                            <?php if (!is_array($campaign)) {
                                                                $val = $campaign;
                                                                $campaign = array();
                                                                $campaign['0'] = $val;
                                                                $campaign_flag = 1;
                                                            }
                                                            if (!is_array($call_type)) {
                                                                $val = $call_type;
                                                                $call_type = array();
                                                                $call_type['0'] = $val;
                                                            }
                                                            ?>
                                                            <div class="form-group">
                                                                <select name="campaign[]" id="campaign" data-live-search="true" multiple class="multiselect_campaign form-control">
                                                                    <?php
                                                                    $campaign_select = db_query("select * from campaign where status=1 order by id desc");
                                                                    while ($row = db_fetch_array($campaign_select)) { ?>
                                                                        <option <?= (in_array($row['id'], $campaign) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Exclude Campaign Data: </label>
                                                                <br>
                                                                <input class="filled-in chk-col-light-blue" <?= (($_GET['campaign_check'] == 'yes') ? 'checked' : '') ?> type="checkbox" value="yes" name="campaign_check" id="campaign_check" />
                                                                <label for="campaign_check">Yes</label>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="input-daterange input-group" id="datepicker-close-date">
                                                                    <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                                    <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <select name="call_type[]" id="call_type" data-live-search="true" multiple class="form-control">
                                                                    <?php
                                                                    $callSubject = db_query("select * from call_subject where subject not like '%visit%' order by subject");
                                                                    while ($row = db_fetch_array($callSubject)) { ?>
                                                                        <option <?= (in_array($row['subject'], $call_type) ? 'selected' : '') ?> value='<?= $row['subject'] ?>'><?= $row['subject'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        <?php } ?>
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                        </div>

                                                        </form>
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
                                                        <th>Reseller name</th>
                                                        <!-- <th>Name</th> -->
                                                        <th>Log By</th>
                                                        <th>Date of Log</th>
                                                        <th>Call Type</th>
                                                        <th>Company Name</th>
                                                        <th>Industry</th>
                                                        <th>Lead Type</th>
                                                        <th>Stage</th>
                                                        <th>Quantity</th>
                                                        <th>Panel Type</th>

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
            if (count($campaign) > 1) {
                $campaign_arr = implode('","', $campaign);
                //print_r($campaign_arr);die;
            } else if (!$campaign_flag) {
                $campaign_arr = $_GET['campaign'][0];
            } else {
                $campaign_arr = $_GET['campaign'];
            }

            if ($_SESSION['user_type'] != 'USR') {

                $users_arr = implode(',', $users);
                // print_r($users_arr);die;

            }

            include('includes/footer.php') ?>
            <script>
                $('#visit_lac').DataTable({

                    dom: 'Bfrtip',
                    "displayLength": 15,


                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                    ],
                    lengthMenu: [
                        [15, 25, 50, 100, 500, 1000, 10000, 30000, 50000],
                        ['15', '25', '50', '100', '500', '1000', '10000', '30000', '50000']
                    ],
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        url: "get_partner_calllogs.php", // json datasource
                        type: "post", // method  , by default get
                        data: function(d) {
                            d.d_from = "<?= htmlspecialchars($_GET['d_from'], ENT_QUOTES, 'UTF-8') ?>";
                            d.d_to = "<?= htmlspecialchars($_GET['d_to'], ENT_QUOTES, 'UTF-8') ?>";
                            d.campaign = '<?= $campaign_arr ?>',
                            d.users = '<?= $users_arr ?>',
                            d.campaign_check = "<?= $_GET['campaign_check'] ?>",
                            d.call_type ='<?= @implode('","', $call_type) ?>' ;
                        },
                        error: function() { // error handling
                            $(".employee-grid-error").html("");
                            $("#visit_lac").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                            $("#leads_processing").css("display", "none");

                        }
                    }
                });

                $(document).ready(function() {

                    $('.multiselect_users').multiselect({
                        buttonWidth: '100%',
                        includeSelectAllOption: true,
                        nonSelectedText: 'Select Users'
                    });
                    $('.multiselect_campaign').multiselect({
                        buttonWidth: '100%',
                        includeSelectAllOption: true,
                        nonSelectedText: 'Select Campaign'
                    });
                    $('#call_type').multiselect({
                        buttonWidth: '100%',
                        includeSelectAllOption: true,
                        nonSelectedText: 'Select Call Type'
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
                    window.location = 'partner_logcalls.php';
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
                    $('.dataTables_wrapper').height(wfheight - 310);
                    $("#visit_lac").tableHeadFixer();

                });
            </script>