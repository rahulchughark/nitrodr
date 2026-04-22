<?php include('includes/header.php'); ?>

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

                                    <small class="text-muted">Home >Activity Report</small>
                                    <h4 class="font-size-14 m-0 mt-1">Activity Report</h4>
                                </div>
                            </div>

                            
                            <div class="btn-group float-right" role="group" style="margin-top:12px;">
                            <button type="button" class="btn btn-xs btn-light ml-1" id="filter-box" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>
                            <div class="dropdown dropdown-lg">

                                <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">
                                            <form method="get" name="search" class="form-material">

                                      <?php if (!is_array($lead_type)) {
                                                    $val = $lead_type;
                                                    $lead_type = array();
                                                    $lead_type['0'] = $val;
                                                    $lt_flag = 1;
                                                } ?>
                                              
                                      <div class="row">
                                                <div class="form-group col-md-3">
                                                
                                                        <div class="input-daterange input-group" id="datepicker-close-date">

                                                            <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                            <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <select data-live-search="true" multiple id="multiselect" class="form-control" name="lead_type[]">

                                                            <option <?= ((in_array('LC', $lead_type)) ? 'selected' : '') ?> value="LC">LC</option>
                                                            <option <?= ((in_array('BD', $lead_type)) ? 'selected' : '') ?> value="BD">BD</option>
                                                            <option <?= ((in_array('Incoming', $lead_type)) ? 'selected' : '') ?> value="Incoming">Incoming</option>
                                                            <option <?= ((in_array('Internal', $lead_type)) ? 'selected' : '') ?> value="Internal">Internal</option>
                                                        </select>
                                                    </div>
                                                    <?php 
                                                   // industry
                                               if(isset($_GET['caller'])){            
                                                 $caller = $_GET['caller'];
                                                   }else{
                                                    $caller = [];
                                                   } 
                                                  
                                                    if (!is_array($quantity)) {
                                                        $val = $quantity;
                                                        $quantity = array();
                                                        $quantity['0'] = $val;
                                                        $quantity_flag = 1;
                                                    }
                                                    ?>
                                                   
                                                    <div class="form-group col-md-3">
                                                        <select name="campaign" id="campaign" class="form-control">
                                                            <option value="">Select Campaign</option>
                                                            <?php
                                                            $campaign_select = db_query("select * from campaign where status=1 order by id desc");
                                                            while ($row = db_fetch_array($campaign_select)) { ?>
                                                                <option <?= (($_GET['campaign'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    
                                        <?php
                                            $res = db_query("select * from callers where 1"); ?>            
                                                    
                                        <div class="form-group col-md-3">
                                                   <select name="caller[]" id="caller" class="form-control" data-live-search="true" multiple>
                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (in_array($row['id'], $caller) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                            </div>

                                        
                                            <div class="row">
                                                
                                              
                                     <?php if($_SESSION['user_type']=='MNGR'){
                                          if(isset($_GET['users'])){            
                                                 $users = $_GET['users'];
                                                   }else{
                                                    $users = [];
                                                   } 
                                     ?>
                                                <div class="form-group col-md-4">
                                                    <select name="users[]" id="users" class="multiselect_users form-control" data-live-search="true" multiple>
                                        <?php $res = db_query("select * from users where team_id=".$_SESSION['team_id']." and status='Active'");
                                                        while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (in_array($row['name'], $users) ? 'selected' : '') ?> value='<?= $row['name'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <?php
                                                } ?>
                                                 
                                                <div class="form-group col-md-2">
                                                <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                </div> 
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Order Added Successfully!
                                </div>
                            <?php } ?>
                            <?php if ($_GET['update'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Order Updated Successfully!
                                </div>
                            <?php } ?>


                            <div class="table-responsive">
                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                       <tr>
                                            <th>S.No.</th>
                                            <th>Submitted By</th>
                                            <th>Account Name</th>
                                            <th>Lead Type</th>
                                            <th>Caller Name</th>
                                            <th>Approval Date</th>
                                            <th colspan="2">Last Activity</th>
                                            <th>Stage</th>
                                            <th colspan="2">Log a Calls</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>VAR Team</th>
                                            <th>Caller</th>
                                            <th></th>
                                            <th>VAR Team</th>
                                            <th>Caller</th>
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


    <?php if (count($lead_type) > 1) {
        $lt = implode("','", $lead_type);
    } else if (!$lt_flag) {
        $lt = $_GET['lead_type'][0];
    } else {
        $lt = $_GET['lead_type'];
    }
    ?>

    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <div id="myModal1" class="modal" role="dialog">


    </div>
    <?php include('includes/footer.php') ?>
    <script>
        $('#leads').DataTable({
            "stateSave": true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
            ],
            lengthMenu: [
                [15, 25, 50, 100, 500, 1000,10000,50000],
                ['15', '25', '50', '100', '500', '1000', '10000', '50000']
            ],
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "partner_get_log_report.php", // json datasource
                type: "post", // method  , by default get
                data: function(d) {
                    d.caller = '<?= @implode('","', $_GET['caller']) ?>';
                    d.d_from = "<?= $_GET['d_from'] ?>";
                    d.d_to = "<?= $_GET['d_to'] ?>";
                    d.lead_type = "<?= $lt ?>";
                    d.partner = "<?= $pt ?>";
                    d.users = "<?= @implode("','", $_GET['users']) ?>";
                    d.campaign = "<?= $_GET['campaign'] ?>";
                    d.product = '<?= intval($_GET['product']) ?>';
                    d.product_type = '<?= intval($_GET['product_type']) ?>';
                    d.ark_users = '<?= intval($_GET['ark_users']) ?>';
                    d.quantity = '<?= @implode(',', $quantity) ?>';
                    d.industry = "<?= $_GET['industry'] ?>";
                    d.lead_source = "<?= $_GET['lead_source'] ?>";
                },
                error: function() { // error handling
                    $(".employee-grid-error").html("");
                    $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="11">No data found on server!</th></tr></tbody>');
                    $("#leads_processing").css("display", "none");

                }
            }
        });



        $(document).ready(function() {
            $('#multiselect').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Lead Type',
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
            $('#caller').multiselect({
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
            $('.multiselect_users').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Submitted By',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn:true
            });
        });

        function clear_search() {
            window.location = 'partner_log_report.php';
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
        $('.dataTables_wrapper').height(wfheight - 325);
        $("#leads").tableHeadFixer();
    });
</script>