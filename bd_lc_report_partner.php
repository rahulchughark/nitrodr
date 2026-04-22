<?php include('includes/header.php');

$_GET['d_from']  = htmlspecialchars($_GET['d_from'], ENT_QUOTES, 'UTF-8');
$_GET['d_to']    = htmlspecialchars($_GET['d_to'], ENT_QUOTES, 'UTF-8');

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
                                    <small class="text-muted">Home >BD/Incoming to LC Converted report</small>
                                    <h4 class="font-size-14 m-0 mt-1">BD/Incoming to LC Converted report</h4>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                <div class="dropdown dropdown-lg">
                                    <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                                        <form method="get" name="search">
                                            <?php
                                            if (!is_array($users)) {
                                                $val = $users;
                                                $users = array();
                                                $users['0'] = $val;
                                            }
                                            if (!is_array($converted_by)) {
                                                $val = $converted_by;
                                                $converted_by = array();
                                                $converted_by['0'] = $val;
                                            }

                                        ?>

                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <div class="input-daterange input-group" id="datepicker-close-date">
                                                        <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                        <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                    </div>
                                                </div>


                                                <?php if($_SESSION['user_type']=='MNGR'){?>
                                                <div class="form-group col-md-4">
                                                    <select name="users[]" id="users" class="multiselect_users form-control" data-live-search="true" multiple>
                                                        <?php $res = db_query("select * from users where team_id=".$_SESSION['team_id']." and status='Active'");
                                                        while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (in_array($row['id'], $users) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <?php
                                                }
                                                if (!is_array($caller)) {
                                                    $val = $caller;
                                                    $caller = array();
                                                    $caller['0'] = $val;
                                                }
                                                if (!is_array($quantity)) {
                                                    $val = $quantity;
                                                    $quantity = array();
                                                    $quantity['0'] = $val;
                                                }
                                                ?>

                                                <div class="form-group col-md-4">
                                                    <select name="caller[]" id="caller" class="multiselect_caller form-control" data-live-search="true" multiple>
                                                        <?php
                                                        $res2 = db_query("select * from callers");
                                                        while ($row = db_fetch_array($res2)) { ?>
                                                            <option <?= (in_array($row['id'], $caller) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-4">

                                                    <select class="form-group" id="quantity" name="quantity[]" data-live-search="true" multiple>

                                                        <option value="1,2" <?= (in_array('1,2', $quantity) ? 'selected' : '') ?>>1 User & 2 User</option>
                                                        <option value="3" <?= (in_array('3', $quantity) ? 'selected' : '') ?>>3 Users</option>
                                                        <option value="4,5" <?= (in_array('4,5', $quantity) ? 'selected' : '') ?>>4 Users & 5 Users</option>
                                                        <option value="6" <?= (in_array('6', $quantity) ? 'selected' : '') ?>>6 Users</option>
                                                        <option value="7,8" <?= (in_array('7,8', $quantity) ? 'selected' : '') ?>>7 Users & 8 Users</option>
                                                        <option value="9" <?= (in_array('9', $quantity) ? 'selected' : '') ?>>9 Users & Above</option>

                                                    </select>
                                                </div>

                                                <div class="form-group col-md-4">

                                                    <select name="segment" id="segment" class="form-control">
                                                        <option value="">Select Segment</option>
                                                        <option value="DTP" <?= (($_GET['segment'] == 'DTP') ? 'selected' : '') ?>>DTP/Printing</option>
                                                        <option value="Other" <?= (($_GET['segment'] == 'Other') ? 'selected' : '') ?>>Other Segment</option>

                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <select name="status" class="form-control">
                                                        <option value="">Select Status</option>
                                                        <option value="Pending" <?= (($_GET['status'] == 'Pending') ? 'selected' : '') ?>>Pending</option>
                                                        <option value="Completed" <?= (($_GET['status'] == 'Completed') ? 'selected' : '') ?>>Completed</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <button type="submit" class="btn btn-primary" id="search"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                    <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                            <div class="table-responsive">
                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Submitted By</th>
                                            <th>Lead Type</th>
                                            <th>Quantity</th>
                                            <th>Account Name</th>
                                            <th>Date of Submission</th>
                                            <th>Converted to LC Date</th>
                                            <th>Status</th>
                                            <th>Caller Name</th>
                                            <th>Converted By</th>
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

    <?php include('includes/footer.php') ?>

    <script>
        $('#leads').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
            ],
            lengthMenu: [
                [15, 25, 50, 100, 500, 1000],
                ['15', '25', '50', '100', '500', '1000']
            ],

            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "get_bd_lc_partner.php", // json datasource
                type: "post", // method  , by default get
                data: function(d) {
                    d.d_from = "<?= htmlspecialchars($_GET['d_from'], ENT_QUOTES, 'UTF-8') ?>";
                    d.d_to = "<?= htmlspecialchars($_GET['d_to'], ENT_QUOTES, 'UTF-8') ?>";
                    d.users = '<?= @implode('","', $_GET['users']) ?>';
                    d.caller = '<?= @implode('","', $_GET['caller']) ?>';
                    d.segment = "<?= $_GET['segment'] ?>",
                    d.converted_by = '<?= @implode('","', $_GET['converted_by']) ?>';
                    d.quantity = '<?= @implode(',', $quantity) ?>';
                    d.status ='<?= $_GET['status'] ?>'
                    // etc
                },

                error: function() { // error handling
                    $(".employee-grid-error").html("");
                    $("#visit_lac").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                    $("#leads_processing").css("display", "none");

                }
            }
        });

        function clear_search() {
            window.location = 'bd_lc_report_partner.php';
        }

        $(function() {
            $('#datepicker-close-date').datepicker({
                format: 'yyyy-mm-dd',
                //startDate: '-3d',
                autoclose: !0
            });
        });

        $(document).ready(function() {
            $('.multiselect_users').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Submitted By',
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
            $('#quantity').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Quantity',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn:true
            });
        });

        $(document).ready(function() {
            var wfheight = $(window).height();
            $('.dataTables_wrapper').height(wfheight - 300);
            $("#leads").tableHeadFixer();

        });
    </script>