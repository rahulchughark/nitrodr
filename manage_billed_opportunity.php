<?php include('includes/header.php');

ini_set('max_execution_time', 0);

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
                            <div class="row">
                                <div class="col">
                                    <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">

                                            <small class="text-muted">Home >Billed Opportunity </small>
                                            <h4 class="font-size-14 m-0 mt-1">Billed Opportunity</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div role="group">
                                    <?php if($_SESSION['download_status'] == 1){ 
                                            $stageStr = $stage ? implode("','",$stage) : '';
                                            $substageStr = $substage ? implode("','",$substage) : '';
                                            $partnerStr = $partner ? implode(",",$partner) : '';
                                            $usersStr = $users ? implode(",",$users) : '';
                                            $stateStr = $state ? implode(",",$state) : '';
                                            $tagStr = $tag ? implode(",",$tag) : '';
                                            $statusStr = $status ? implode(",",$status) : '';
                                            $lead_status_str = $lead_status ? implode(",",$lead_status) : '';
                                            $source_str = $source ? implode(",",$source) : '';
                                            
                                            ?>
                                        <!-- <a href="export_admin_opportunity.php?d_from=<?= @$_GET['d_from'] ?>&d_to=<?= @$_GET['d_to'] ?>&d_type=<?= @$_GET['dtype'] ?>&license='Fresh'&stage=<?= $stageStr ?>&substage=<?= $substageStr ?>&partner=<?= $partnerStr ?>&users=<?= $usersStr ?>&tag=<?= $tagStr ?>&status=<?= $statusStr ?>&lead_status=<?= $lead_status_str ?>&state=<?= $stateStr ?>&source=<?= $source_str ?>">
                                            <button title="Excel Export" class="btn btn-xs btn-light ml-1"><i class="ti-share"></i>
                                            </button>
                                        </a> -->
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
                                                        <div class="form-group col-md-4 col-xl-4">
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
                                                        <div class="form-group col-md-4 col-xl-4">
                                                            <div class="input-daterange input-group" id="datepicker-close-date">
                                                                <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="form-control" id="d_from" name="d_from" placeholder="Date From" />

                                                                <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="form-control" id="d_to" name="d_to" placeholder="Date To" />
                                                            </div>
                                                        </div>
                                                    <?php if($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN') { ?>
                                                        <div class="form-group col-md-4 col-xl-3">
                                                            <select name="just_partner" class=" form-control" data-live-search="true">
                                                                    <option value=''>Select Just Partners</option>
                                                                    <option value='Yes' <?= $_GET['just_partner'] == 'Yes' ? 'selected' : '' ?>>Yes</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4 col-xl-4">
                                                            <select name="partner[]" id="partner" class="multiselect_partner form-control" data-live-search="true" multiple>
                                                                <?php while ($row = db_fetch_array($res)) { ?>
                                                                    <option <?= (in_array($row['id'], $partner) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>

                                                    <?php } ?>


                                                    <div class="form-group col-md-4 col-xl-4">
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

                                                        <div class="form-group col-md-4 col-xl-4">
                                                            <select name="lead_status[]" data-live-search="true" multiple class="form-control" id="multiselectleadstatus">
                                                                <option <?= (@in_array('Raw Data', $lead_status ?? []) ? 'selected' : '') ?> value="Raw Data">Raw Data</option>
                                                                <option <?= (@in_array('Validation', $lead_status ?? []) ? 'selected' : '') ?> value="Validation">Validation</option>
                                                                <option <?= (@in_array('Contacted', $lead_status ?? []) ? 'selected' : '') ?> value="Contacted">Contacted</option>
                                                                <option <?= (@in_array('Qualified', $lead_status ?? []) ? 'selected' : '') ?> value="Qualified">Qualified</option>
                                                                <option <?= (@in_array('Unqualified', $lead_status ?? []) ? 'selected' : '') ?> value="Unqualified">Unqualified</option>
                                                                <option <?= (@in_array('Duplicate', $lead_status ?? []) ? 'selected' : '') ?> value="Duplicate">Duplicate</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-md-4 col-xl-4">
                                                            <select name="status[]" data-live-search="true" multiple class="form-control" id="multiselectleadqualifiedstatus">
                                                            <!-- <option value="">---Select---</option> -->
                                                            <option <?= (@in_array('Undervalidation', $status ?? []) ? 'selected' : '') ?> value="Undervalidation">Re-Submission Required</option>
                                                            <option <?= (@in_array('Approved', $status ?? []) ? 'selected' : '') ?> value="Approved">Qualified</option>
                                                            <option <?= (@in_array('Cancelled', $status ?? []) ? 'selected' : '') ?> value="Cancelled">Unqualified</option>
                                                            <option <?= (@in_array('On-Hold', $status ?? []) ? 'selected' : '') ?> value="On-Hold">On-Hold</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-md-4 col-xl-4">
                                                    <select name="school_board[]" id="multiselect_school_board" class="form-control" multiple>
                                                    <option <?= (@in_array('CBSE', $school_board ?? []) ? 'selected' : '') ?> value="CBSE">CBSE</option>
                                                    <option <?= (@in_array('ICSE', $school_board ?? []) ? 'selected' : '') ?> value="ICSE">ICSE</option>
                                                    <option <?= (@in_array('IB', $school_board ?? []) ? 'selected' : '') ?> value="IB">IB</option>
                                                    <option <?= (@in_array('IGCSE', $school_board ?? []) ? 'selected' : '') ?> value="IGCSE">IGCSE</option>
                                                    <option <?= (@in_array('STATE', $school_board ?? []) ? 'selected' : '') ?> value="STATE">STATE</option>
                                                    <option <?= (@in_array('Others', $school_board ?? []) ? 'selected' : '') ?> value="Others">Others</option>
                                                    </select>
                                                        </div>

                                                        <div class="form-group col-md-4 col-xl-4">
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
                                                        <div class="form-group col-md-4 col-xl-4">
                                                        <select name="tag[]" data-live-search="true" multiple class="form-control" id="multiselecttag">
                                                            <?php while ($tags = db_fetch_array($tagList)) { ?>
                                                                <option value="<?= $tags['id'] ?>" <?= (@in_array($tags['id'], $tag ?? []) ? 'selected' : '') ?>><?= $tags['name'] ?></option>
                                                            <?php } ?>
                                                            </select>
                                                            </div>

                                                            <div class="form-group col-md-4 col-xl-4">
                                                            <select name="state[]" class="form-control" id="multiselect_state" multiple>
                                                                <?php $ress = db_query("select * from states");
                                                                while ($row = db_fetch_array($ress)) { ?>
                                                                    <option <?= (@in_array($row['id'], $state ?? []) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                                <?php  } ?>

                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4 col-xl-4" id="cityD">
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
                                                        <div class="form-group col-md-4 col-xl-4">
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

                            
                            <div class="table-responsive" id="MyDiv">

                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                            <tr>
                                            <th>S.No.</th>
                                                <th style="min-width: 220px">Reseller Name</th>
                                                <th>School Board</th>
                                                <th style="min-width: 220px">School Name</th>
                                                <th>City</th>
                                                <th>State</th>
                                                <th style="min-width: 300px">Product</th>
                                                <th>Quantity</th>
                                                <th>Grand Total</th>
                                                <th>Date of Submission</th>
											    <th>Status</th>
											    <th>Qualified Status</th>
											    <th>Stage</th>
                                                <th>Sub Stage</th>
											    <th>Demo Arranged</th>
											    <th>Demo Completed</th>
											    <th>Proposal Shared</th>
											    <th>Demo Login</th>
											    <th>DL + PS</th>
											    <th>Closed Date</th>
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

        <?php include('includes/footer.php') ?>
        <script>
            $('#leads').DataTable({
                dom: 'Bfrtip',
                "displayLength": 15,
                language: {
                    paginate: {
                        previous: '<i class="fas fa-arrow-left"></i>',
                        next: '<i class="fas fa-arrow-right"></i>'
                    }
                },
                language: {
                    paginate: {
                        previous: '<i class="fas fa-arrow-left"></i>',
                        next: '<i class="fas fa-arrow-right"></i>'
                    }
                },
                buttons: [
                    <?php if($_SESSION['download_status'] == 1){ ?>
                    'copy', 'csv', 'excel', 'pdf',  'print', 'pageLength',
                    
                <?php }else{ ?> 'pageLength'  <?php } ?>
                    
                ],
                lengthMenu: [
                    [5, 15, 25, 50, 100, 500, 1000],
                    ['5', '15', '25', '50', '100', '500', '1000']
                ],
                "stateSave": true,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "get_billed_opportunity.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                       
                    d.d_from = "<?= $_GET['d_from'] ?>";
                    d.d_to = "<?= $_GET['d_to'] ?>";
                    d.d_type = "<?= $_GET['dtype'] ?>";
                    d.type = "<?= $_GET['type'] ?>";
                    d.just_partner = "<?= $_GET['just_partner'] ?>";
                    d.sub_product = '<?= json_encode($_GET['sub_product']) ?>';
                    d.stage = '<?= json_encode($_GET['stage']) ?>';
                    d.sub_stage = '<?= json_encode($_GET['sub_stage']) ?>';
                    d.users = '<?= json_encode($_GET['users']) ?>';
                    d.quantity = '<?= json_encode($_GET['quantity']) ?>';
                    d.state = '<?= json_encode($_GET['state']) ?>';
                    d.city = '<?= json_encode($_GET['city']) ?>';
                    d.school_board = '<?= json_encode($_GET['school_board']) ?>';
                    d.tag = '<?= json_encode($_GET['tag']) ?>';
                    d.lead_status = '<?= json_encode($_GET['lead_status']) ?>';
                    d.status = '<?= json_encode($_GET['status']) ?>';
                    d.partner = '<?= json_encode($_GET['partner']) ?>';
                    d.source = '<?= json_encode($_GET['source']) ?>';
                    d.users = '<?= json_encode($_GET['users']) ?>';
                    d.product_typeDS = '<?= json_encode($_GET['product_typeDS']) ?>';
                    d.productDS = '<?= json_encode($_GET['productDS']) ?>';
                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="14">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");
                    }
                },
                "order": [
                    [7, "desc"]
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],

                'columns': [
                    { data: 'id' },
                 { data: 'r_name' },
                 {data:'school_board'},
                 { data: 'school_name' },
                 { data: 'city' },
                 { data: 'state'},
                 { data: 'product' },
                 { data: 'quantity' },
                 { data: 'grand_total' },
                   {data:'created_date'},
                  {data:'status', className: 'text-nowrap'},
                  {data:'qualified_status'},
                  {data:'stage', className: 'text-nowrap'},
                  {data:'sub_stage', className: 'text-nowrap'},
                  {data:'demo_arranged'},
                  {data:'demo_completed'},
                  {data:'proposal_shared'},
                  {data:'demo_login', className: 'text-nowrap'},
                  {data:'demo_login+proposal_shared', className: 'text-nowrap'},                  
                  {data:'close_date', className: 'text-nowrap'},
                   
                
              ]
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
                    includeFilterClearBtn:true
                });
                $('.multiselect_user1').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select User',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('.multiselect_sub_stage').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Sub Stage',
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
                $('#multiselect').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Stage',
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
                $('#multiselectleadqualifiedstatus').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Qualified Status',
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
                $('#multiselect_school_board').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select School Board',
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
                $('#multiselecttag').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Tag',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                    $('.multiselect_city').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select City',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
            });


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


            function clear_search() {
                window.location = 'manage_billed_opportunity.php';
            }

            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });

            function chage_stage(stage, id) {
                if (stage != '') {
                    $.ajax({
                        type: 'post',
                        url: 'change_stage.php',
                        data: {
                            stage: stage,
                            lead_id: id
                        },
                        success: function(res) {
                            var res = $.trim(res);
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


            function stage_change(ids, id) {                
                //$('.preloader').show();
                var page_access = 'true';
                $.ajax({
                    type: 'POST',
                    url: 'stage_change.php',
                    data: {
                        pid: id,
                        ids: ids,
                        page_access: page_access
                    },
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function chage_stage(stage, id, ids, substage, payment_status, attachments,demo_datetime) {


                if (stage != '') {
                    var formData = new FormData();
                    formData.append('stage', stage);
                    formData.append('substage', substage);
                    formData.append('lead_id', id);
                    formData.append('payment_status', payment_status);
                    formData.append('demo_datetime', demo_datetime);
                    // Append files to FormData
                    for (var i = 0; i < attachments.length; i++) {
                        formData.append('attachments[]', attachments[i]);
                    }

                    $('#myModal1').modal('hide');
                    $.ajax({
                        type: 'post',
                        url: 'change_stage.php',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            var res = $.trim(res);
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
                                    location.reload();
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

        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 310);
                $("#leads").tableHeadFixer();

            });

            function convertDate(dateString) {
                var p = dateString.split(/\D/g)
                return [p[2], p[1], p[0]].join("-")
            }

            function show_import() {
                $.ajax({
                    type: 'POST',
                    url: 'import_leads.php',
                    success: function(response) {
                        $("#myModal1").html();
                        $("#myModal1").html(response);

                        $('#myModal1').modal('show');
                        $('.preloader').hide();
                    }
                });

            }

            

            

            function status_change(ids, id) {
                var page_access = 'true';
                $.ajax({
                    type: 'POST',
                    url: 'status_change.php',
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

        function demo_sub_stage_date(id,subStageID){
            $.ajax({
                            type: 'POST',
                            url: 'demo_sub_stage_date.php',
                            data: {
                                id: id,
                                subStageID:subStageID
                            },
                            success: function(response) {
                                $("#myModal1").html();
                                $("#myModal1").html(response);

                                $('#myModal1').modal('show');
                                $('.preloader').hide();
                            }
                        });            
        }


        function cd_change(ids, id) {
                        //$('.preloader').show();
                        // alert('hii')
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
                            var res = $.trim(res);
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
                                    $('#leads').DataTable().ajax.reload();
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

            function update_sub_stage_timestamps(logID,currentSubStage,previousSubStage,logDate) {
               
                if (logID != '') {
                    $('#myModal1').modal('hide');
                    $.ajax({
                        type: 'post',
                        url: 'demo_sub_stage_date.php',
                        data: {
                            logID: logID,
                            currentSubStage: currentSubStage,
                            previousSubStage: previousSubStage,
                            logDate: logDate
                        },
                        success: function(res) {
                            var res = $.trim(res);
                            if (res == 'success') {
                                swal({
                                    title: "Done!",
                                    text: "Close Date changed Successfully.",
                                    type: "success"
                                }, function() {
                                    $('#myModal1').modal('hide');
                                    // var ids2 = "'but2" + logID + "'";                                    
                                    // var newDate = convertDate(cd_date);
                                    // var link = newDate + '<a href="javascript:void(0)" title="Change Close Date" id=but2' + id + ' onclick="cd_change(' + ids2 + ',' + logID + ')"> <i style="font-size:18px" class="mdi mdi-update"></i></a>'
                                    // $("#" + ids).parent().html(link);
                                    $('#leads').DataTable().ajax.reload();
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

        </script>