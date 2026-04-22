<?php include('includes/header.php');

$_GET['d_from']  = htmlspecialchars($_GET['d_from'], ENT_QUOTES, 'UTF-8');
$_GET['d_to']    = htmlspecialchars($_GET['d_to'], ENT_QUOTES, 'UTF-8');

// users
   if(isset($_GET['users'])){            
     $users = $_GET['users'];
       }else{
        $users = [];
       }  

// industry
   if(isset($_GET['industry'])){            
     $industry = $_GET['industry'];
       }else{
        $industry = [];
       } 

// lead_type
   if(isset($_GET['lead_type'])){            
     $lead_type = $_GET['lead_type'];
       }else{
        $lead_type = [];
       }        

// submission_type
   if(isset($_GET['submission_type'])){            
     $submission_type = $_GET['submission_type'];
       }else{
        $submission_type = [];
       } 

// quantity
   if(isset($_GET['quantity'])){            
     $quantity = $_GET['quantity'];
       }else{
        $quantity = [];
       }                                 

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
                                    <small class="text-muted">Home >Qualified Accounts report</small>
                                    <h4 class="font-size-14 m-0 mt-1">Qualified Accounts report</h4>
                                </div>
                            </div>

                             <?php if ($_GET['m'] == 'nodata') { ?>
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-danger"><i class="fa fa-check-circle"></i> Error!</h3> No data found!
                                </div>
                            <?php } ?>

                            <div class="clearfix"></div>
                            <div class="btn-group float-right" role="group" style="margin-top:12px;">

                                <a href="export_qualified_accounts_partner.php?d_from=<?= @$_GET['d_from'] ?>&d_to=<?= @$_GET['d_to'] ?>&users=<?= implode(',',@$users) ?>&lead_type=<?= implode("','",@$lead_type) ?>&submission_type=<?= implode(',',@$submission_type) ?>&quantity=<?= implode(',',@$quantity) ?>&segment=<?=@$_GET['segment']?>">
                                    <button data-toggle="tooltip" data-placement="left" title="" data-original-title="Excel Export" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-download"></i></button></a>

                                <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                <div class="dropdown dropdown-lg">
                                    <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                                        <form method="get" name="search">
                                           
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <div class="input-daterange input-group" id="datepicker-close-date">
                                                        <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                        <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                    </div>
                                                </div>

                                                    <div class="form-group col-md-4">
                                                            <select name="users[]" class="multiselect_user1 form-control" data-live-search="true" multiple>

                                                                <?php $query = db_query('SELECT * FROM users WHERE team_id ='.$_SESSION['team_id'].' and status="Active"  ORDER BY name ASC');
                                                                while ($row = db_fetch_array($query)) { ?>
                                                                    <option <?= (in_array($row['id'], $users) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                                                <?php } ?>
                                                            </select>

                                                    </div>

                                                    <div class="form-group col-md-4">
                                                    <select name="segment" id="segment" class="form-control">
                                                        <option value="">Select Industry</option>
                                                        <option value="DTP" <?= (($_GET['segment'] == 'DTP') ? 'selected' : '') ?>>Bucket of DTP</option>
                                                        <option value="Other" <?= (($_GET['segment'] == 'Other') ? 'selected' : '') ?>>Others</option>
                                                    </select>
                                                 </div> 

                                            </div>
                                                
                                             <div class="row">
                                              
                                                <div class="form-group col-md-4">
                                                   
                                                        <select data-live-search="true" multiple class="form-control " name="lead_type[]" id="lead_type" >

                                                            <option <?= ((in_array('LC', $lead_type)) ? 'selected' : '') ?> value="LC">LC</option>
                                                            <option <?= ((in_array('BD', $lead_type)) ? 'selected' : '') ?> value="BD">BD</option>
                                                            <option <?= ((in_array('Incoming', $lead_type)) ? 'selected' : '') ?> value="Incoming">Incoming</option>
                                                            <option <?= ((in_array('Internal', $lead_type)) ? 'selected' : '') ?> value="Internal">Internal</option>
                                                        </select>
                                                    </div>
                                                <div class="form-group col-md-4">

                                                    <select class="form-control" name="submission_type[]" id="submission_type" data-live-search="true" multiple>

                                                        <option value="1" <?= (in_array('1', $submission_type) ? 'selected' : '') ?>>Fresh</option>
                                                        <option value="2" <?= (in_array('2', $submission_type) ? 'selected' : '') ?>>Converted to LC</option>

                                                    </select>
                                                </div>

                                                 <div class="form-group col-md-4">

                                                    <select class="form-group" id="quantity" name="quantity[]" id="quantity" data-live-search="true" multiple>

                                                        <option value="1,2" <?= (in_array('1,2', $quantity) ? 'selected' : '') ?>>1 User & 2 User</option>
                                                        <option value="3" <?= (in_array('3', $quantity) ? 'selected' : '') ?>>3 Users</option>
                                                        <option value="4,5" <?= (in_array('4,5', $quantity) ? 'selected' : '') ?>>4 Users & 5 Users</option>
                                                        <option value="6" <?= (in_array('6', $quantity) ? 'selected' : '') ?>>6 Users</option>
                                                        <option value="7,8" <?= (in_array('7,8', $quantity) ? 'selected' : '') ?>>7 Users & 8 Users</option>
                                                        <option value="9" <?= (in_array('9', $quantity) ? 'selected' : '') ?>>9 Users & Above</option>

                                                    </select>
                                                </div>

                                                <!-- <div class="form-group col-md-4">
                                                    <select name="segment" id="segment" class="form-control">
                                                        <option value="">Select Segment</option>
                                                        <option value="DTP" <?= (($_GET['segment'] == 'DTP') ? 'selected' : '') ?>>DTP/Printing</option>
                                                        <option value="Other" <?= (($_GET['segment'] == 'Other') ? 'selected' : '') ?>>Other Segment</option>
                                                    </select>
                                                </div> -->
 </div>
                                                <div class="row">

                                                   

                                                <!-- <div class="form-group col-md-4">
                                                    <select name="status" class="form-control">
                                                        <option value="">Select Status</option>
                                                        <option value="Pending" <?= (($_GET['status'] == 'Pending') ? 'selected' : '') ?>>Pending</option>
                                                        <option value="Completed" <?= (($_GET['status'] == 'Completed') ? 'selected' : '') ?>>Completed</option>
                                                    </select>
                                                </div> -->

                                                <div class="form-group col-md-2">
                                                    <button type="submit" class="btn btn-primary" id="search"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                    <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                </div>
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
                                            <th>Account Name</th>
                                            <th>Quantity</th>
                                            <th>Industry</th>
                                            <th>Submission Date</th>
                                            <th>Actioned Date</th>
                                            <th>Submission Type</th>
                                            <th>Status</th>
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
                    url: "get_qualified_accounts_partner.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                         d.d_from = "<?= $_GET['d_from'] ?>";
                         d.d_to = "<?= $_GET['d_to'] ?>";
                         d.users = "<?= implode(',',$users) ?>";
                         d.segment = "<?= $_GET['segment'] ?>";
                         d.lead_type = "<?= implode("','",$lead_type) ?>";
                         d.submission_type = "<?= implode(',',$submission_type) ?>";
                         d.quantity = "<?= implode(',',$quantity) ?>";
                       
                        // d.custom = $('#myInput').val();
                        // etc
                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="13">No data found on server!</th></tr></tbody>');
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
                        data: 'r_name'
                    },
                    {
                        data: 'lead_type'
                    },
                    {
                        data: 'company_name'
                    },
                    {
                        data: 'quantity'
                    },
                    {
                        data: 'industry'
                    },
                    {
                        data: 'created_date'
                    },
                    {
                        data: 'actioned_date'
                    },
                    {
                        data: 'submission_type'
                    },
                    {
                        data: 'status'
                    },
                ]

              
            });

        function clear_search() {
            window.location = 'qualified_accounts_report_partner.php';
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
                nonSelectedText: 'Select Partner',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn:true
            });
            $('.multiselect_user1').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Users',
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
            $('.multiselect_converted_by').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Converted By',
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
            $('#industry').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Industry',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
            $('#lead_type').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Lead Type',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });

            $('#submission_type').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Submission Type',
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