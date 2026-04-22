<?php include('includes/header.php');
teamLead_page();

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
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">
                                    <small class="text-muted">Home >POA Report</small>
                                    <h4 class="font-size-14 m-0 mt-1">POA Report</h4>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                <div class="dropdown dropdown-lg">
                                    <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                                        <form method="get" name="search">
                                            <?php
                                            if (!is_array($partner)) {
                                                $val = $partner;
                                                $partner = array();
                                                $partner['0'] = $val;
                                            }
                                            if (!is_array($poa_stamped)) {
                                                $val = $poa_stamped;
                                                $poa_stamped = array();
                                                $poa_stamped['0'] = $val;
                                            }
                                            if (!is_array($lead_type)) {
                                                $val = $lead_type;
                                                $lead_type = array();
                                                $lead_type['0'] = $val;
                                            }

                                            $caller_data = db_query("select caller,team_id from users where id=".$_SESSION['user_id']);
                                            while($callers_arr = db_fetch_array($caller_data)){
                                                $caller_lead[]=$callers_arr['caller'];
                                            }

                                            if ($_SESSION['sales_manager'] != 1) {
                                                if($_SESSION['user_type']=='TEAM LEADER'){

                                                    $callers = db_query("select caller from users where id=".$_SESSION['user_id']." and status='Active'");

                                                    $caller_arr = db_fetch_array($callers);
                                                    $caller_data =implode(',',$caller_arr);
                                                    
                                                    $res = db_query("select partners.* from partners left join orders on partners.id=orders.team_id left join users on orders.team_id=users.team_id where orders.caller in (".$caller_data.") group by orders.team_id");
                                                    
                                                }else{
                                                    $res = db_query("select * from partners where status='Active'");
                                                }
                                               
                                            } else {
                                                $res = db_query("select * from partners where id in (" . $_SESSION['access'] . ") and status='Active'");
                                            }
                                            ?>

                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <select class="form-control" name="dtype">
                                                        <option value="">Select Date Type</option>
                                                        <option <?= (($_GET['dtype'] == 'qualified_date') ? 'selected' : '') ?> value="qualified_date">Qualified Date</option>
                                                        <option <?= (($_GET['dtype'] == 'poa_assigned') ? 'selected' : '') ?> value="poa_assigned">POA Assigned Date</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <div class="input-daterange input-group" id="datepicker-close-date">
                                                        <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                        <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <select name="partner[]" id="partner" class="multiselect_partner form-control" data-live-search="true" multiple>
                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (in_array($row['id'], $partner) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <select name="poa_stamped[]" class="multiselect_poa form-control" data-live-search="true" multiple>
                                                        <option value="Drop" <?= $poa_stamped == 'Drop' ? 'selected' : '' ?>>Drop</option>
                                                        <option value="Need More Validation" <?= $poa_stamped == 'Need More Validation' ? 'selected' : '' ?>>Need More Validation</option>
                                                        <option value="Turns Negative" <?= $poa_stamped == 'Turns Negative' ? 'selected' : '' ?>>Turns Negative</option>
                                                        <option value="Need Visit" <?= $poa_stamped == 'Need Visit' ? 'selected' : '' ?>>Need Visit</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <select name="lead_type[]" class="multiselect_lead_type form-control" data-live-search="true" multiple>
                                                        <option <?= ((in_array('LC', $lead_type)) ? 'selected' : '') ?> value="LC">LC</option>
                                                        <option <?= ((in_array('BD', $lead_type)) ? 'selected' : '') ?> value="BD">BD</option>
                                                        <option <?= ((in_array('Incoming', $lead_type)) ? 'selected' : '') ?> value="Incoming">Incoming</option>
                                                        <option <?= ((in_array('Internal', $lead_type)) ? 'selected' : '') ?> value="Internal">Internal</option>
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
                                                <select name="current_poa[]" class="multiselect_curr_poa form-control" data-live-search="true" multiple>
                                                        <option value="Drop" <?= ((in_array('Drop',$current_poa)) ? 'selected' : '') ?>>Drop</option>
                                                        <option value="Need More Validation" <?= ((in_array('Need More Validation',$current_poa)) ? 'selected' : '') ?>>Need More Validation</option>
                                                        <option value="Turns Negative" <?= ((in_array('Turns Negative',$current_poa)) ? 'selected' : '') ?>>Turns Negative</option>
                                                        <option value="Need Visit" <?= ((in_array('Need Visit',$current_poa)) ? 'selected' : '') ?>>Need Visit</option>
                                                        <option value="Completed" <?= ((in_array('Completed',$current_poa)) ? 'selected' : '') ?>>Completed</option>
                                                    </select>
                                                    </div>

                                                    <?php if($_SESSION['user_type']=='TEAM LEADER'){
                                                        if (!is_array($caller)) {
                                                            $val = $caller;
                                                            $caller = array();
                                                            $caller['0'] = $val;
                                                        } ?>
                                                    <div class="form-group col-md-4">
                                                    <select name="assigned_by[]" class="multiselect_assigned_by form-control" data-live-search="true" multiple>
                                                    <?php $caller_list = db_query("select caller from users where id=".$_SESSION['user_id']);

                                                    while($caller_arr = db_fetch_array($caller_list)){
                                                    $caller_lead[]=$caller_arr['caller'];
                                                    }

                                                    $res2 = db_query("select * from callers where id in (".implode(',',$caller_lead).")");

                                                    while ($row2 = db_fetch_array($res2)) { ?>

                                                    <option <?= (in_array($row2['id'], $assigned_by) ? 'selected' : '') ?> value='<?= $row2['id'] ?>'><?= $row2['name'] ?></option>
                                                    <?php } ?>
                                                    </select>
                                                    </div>
                                                    <?php } ?>


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
                                        <th>Reseller Name</th>
                                        <th>Submitted By</th>
                                        <th>Lead Type</th>
                                        <th>Quantity</th>
                                        <th>Caller Name</th>
                                        <th>Account Name</th>
                                        <th>Date of Qualified (Lead)</th>
                                        <th>POA assigned date</th>
                                        <th>POA (Stamped)</th>
                                        <th>Current POA</th>
                                        <th>POA Assigned By</th>
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
    <!-- ==
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
            url: "get_poa_caller.php", // json datasource
            type: "post", // method  , by default get
            data: function(d) {
                d.dtype = "<?= $_GET['dtype'] ?>";
                d.d_from = "<?= htmlspecialchars($_GET['d_from'], ENT_QUOTES, 'UTF-8') ?>";
                d.d_to = "<?= htmlspecialchars($_GET['d_to'], ENT_QUOTES, 'UTF-8') ?>";
                d.partner = '<?= implode('","', $_GET['partner']) ?>';
                d.poa_stamped = '<?= implode('","', $_GET['poa_stamped']) ?>';
                d.segment = "<?= $_GET['segment'] ?>",
                d.lead_type = '<?= implode('","', $_GET['lead_type']) ?>';
                d.current_poa = '<?= implode('","', $_GET['current_poa']) ?>';
                d.assigned_by = '<?= implode('","', $_GET['assigned_by']) ?>';
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
        window.location = 'poa_report_caller.php';
    }

    $(function() {
        $('#datepicker-close-date').datepicker({
            format: 'yyyy-mm-dd',
            //startDate: '-3d',
            autoclose: !0
        });
    });

    $(document).ready(function() {
        $('.multiselect_partner').multiselect({
            buttonWidth: '100%',
            includeSelectAllOption: true,
            nonSelectedText: 'Select Partner'
        });
        $('.multiselect_poa').multiselect({
            buttonWidth: '100%',
            includeSelectAllOption: true,
            nonSelectedText: 'Select POA Stamped'
        });
        $('.multiselect_lead_type').multiselect({
            buttonWidth: '100%',
            includeSelectAllOption: true,
            nonSelectedText: 'Select Lead Type'
        });
        $('.multiselect_curr_poa').multiselect({
            buttonWidth: '100%',
            includeSelectAllOption: true,
            nonSelectedText: 'Select Current POA'
        });
        $('.multiselect_assigned_by').multiselect({
            buttonWidth: '100%',
            includeSelectAllOption: true,
            nonSelectedText: 'Select Assigned By'
        });
        
    });

    $(document).ready(function() {
        var wfheight = $(window).height();
        $('.dataTables_wrapper').height(wfheight - 300);
        $("#leads").tableHeadFixer();

    });
</script>