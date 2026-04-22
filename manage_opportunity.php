<?php include('includes/header.php');
// admin_page(); 

?>

<style>
    .dt-buttons {
        margin-top: 30px;
        margin-left: -191px;
        margin-bottom: 10px;
    }


    .list-action-disabled {
        pointer-events: none;
        cursor: not-allowed !important;
        opacity: 0.6;
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
                                <div class="col-sm">
                                    <div class="media bredcrum-title">
                                        <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">

                                            <small class="text-muted">Home > Opportunities</small>
                                            <h4 class="font-size-14 m-0 mt-1">Manage Opportunities</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-auto pt-2 pt-sm-0">
                                    <div class="" role="group">
                                    <?php if ($_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'ADMIN') { ?>

                                            <a href="javascript:void(0);" onclick="show_import('all')"><button  title="Import Opportunities" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-plus "></i></button></a>

                                            <?php } ?>
                                    <?php if($_SESSION['download_status'] == 1){ 
                                            $stageStr = $stage ? implode("','",$stage) : '';
                                            $partnerStr = $partner ? implode(",",$partner) : '';
                                            $tagStr = $tag ? implode(",",$tag) : '';
                                            $userStr = $users ? implode(",",$users) : '';
                                            $sub_stageStr = $sub_stage ? implode("','",$sub_stage) : '';
                                            $school_boardStr = $school_board ? implode("','",$school_board) : '';
                                            $lead_statusStr = $lead_status ? implode("','",$lead_status) : '';
                                            $stateStr = $state ? implode(",",$state) : '';
                                            ?>
                                        <a href="export_admin_lead.php?d_from=<?= @$_GET['d_from'] ?>&d_to=<?= @$_GET['d_to'] ?>&d_type=<?= @$_GET['dtype'] ?>&license='Fresh'&stage=<?= $stageStr ?>&partner=<?= $partnerStr ?>&tag=<?= $tagStr ?>&user=<?= $userStr ?>&sub_stage=<?= $sub_stageStr ?>&school_board=<?= $school_boardStr ?>&lead_status=<?= $lead_statusStr ?>&state=<?= $stateStr ?>">
                                            <button title="Excel Export" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-share"></i>
                                            </button>
                                        </a>
                                        <?php } ?>
                                        <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                        <div class="dropdown dropdown-lg">

                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                                                <form method="get" id="search-form" name="search">
                                                    <div class="row">
                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select name="subscription_term[]" class="form-control" id="multiselect_subscription_term" multiple>
                                                                <option <?= (in_array('1', $_GET['subscription_term'] ?? []) ? 'selected' : '') ?> value="1">1 Year</option>
                                                                <option <?= (in_array('3', $_GET['subscription_term'] ?? []) ? 'selected' : '') ?> value="3">3 Year</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select name="stage_id[]" class="form-control" id="multiselect_stage_id" multiple>
                                                                <?php $stageRes = db_query("SELECT id, name FROM tbl_mst_stage WHERE status=1 ORDER BY name ASC");
                                                                while ($stageRow = db_fetch_array($stageRes)) { ?>
                                                                    <option <?= (in_array((string)$stageRow['id'], $_GET['stage_id'] ?? []) ? 'selected' : '') ?> value="<?= $stageRow['id'] ?>"><?= $stageRow['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select name="proof_engagement_id[]" class="form-control" id="multiselect_proof_engagement_id" multiple>
                                                                <?php $proofRes = db_query("SELECT id, name FROM tbl_mst_proof_engagement WHERE status=1 ORDER BY name ASC");
                                                                while ($proofRow = db_fetch_array($proofRes)) { ?>
                                                                    <option <?= (in_array((string)$proofRow['id'], $_GET['proof_engagement_id'] ?? []) ? 'selected' : '') ?> value="<?= $proofRow['id'] ?>"><?= $proofRow['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select name="approval_status[]" class="form-control" id="multiselect_approval_status" multiple>
                                                                <option <?= (in_array('1', $_GET['approval_status'] ?? []) ? 'selected' : '') ?> value="1">Approved</option>
                                                                <option <?= (in_array('0', $_GET['approval_status'] ?? []) ? 'selected' : '') ?> value="0">Not Approved</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select name="assigned_partner_id[]" class="form-control" id="multiselect_assigned_partner" multiple>
                                                                <?php $assignedPartners = db_query("SELECT id, name FROM partners WHERE status='Active' ORDER BY name ASC");
                                                                while ($assignedPartner = db_fetch_array($assignedPartners)) { ?>
                                                                    <option <?= (in_array((string)$assignedPartner['id'], $_GET['assigned_partner_id'] ?? []) ? 'selected' : '') ?> value="<?= $assignedPartner['id'] ?>"><?= $assignedPartner['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6 col-xl-3">
                                                            <select name="align_to[]" class="form-control" id="multiselect_align_to" multiple>
                                                                <?php
                                                                $selectedAlignTo = $_GET['align_to'] ?? [];
                                                                $selectedAssignedPartners = $_GET['assigned_partner_id'] ?? [];
                                                                $selectedAssignedPartners = array_filter(array_map('intval', (array)$selectedAssignedPartners));

                                                                if (!empty($selectedAssignedPartners)) {
                                                                    $alignUserRes = db_query("SELECT id, name FROM users WHERE status='Active' AND team_id IN (" . implode(',', $selectedAssignedPartners) . ") ORDER BY name ASC");
                                                                } else {
                                                                    $selectedAlignToIds = array_filter(array_map('intval', (array)$selectedAlignTo));
                                                                    if (!empty($selectedAlignToIds)) {
                                                                        $alignUserRes = db_query("SELECT id, name FROM users WHERE status='Active' AND id IN (" . implode(',', $selectedAlignToIds) . ") ORDER BY name ASC");
                                                                    } else {
                                                                        $alignUserRes = false;
                                                                    }
                                                                }

                                                                if ($alignUserRes) {
                                                                    while ($alignUser = db_fetch_array($alignUserRes)) {
                                                                        $isSelected = in_array((string)$alignUser['id'], (array)$selectedAlignTo) ? 'selected' : '';
                                                                        echo "<option {$isSelected} value=\"{$alignUser['id']}\">" . htmlspecialchars($alignUser['name'], ENT_QUOTES, 'UTF-8') . "</option>";
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3 col-xl-2">
                                                            <button type="submit" class="btn btn-primary font-14"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
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
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Opportunity Added Successfully!
                                </div>
                            <?php } ?>
                            <?php if ($_GET['update'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Opportunity Updated Successfully!
                                </div>
                            <?php } ?>
                            <?php if ($_GET['m'] == 'nodata') { ?>
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-danger"><i class="fa fa-check-circle"></i> Error!</h3> No data found!
                                </div>
                            <?php } ?>
                            <div class="table-responsive">
                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                        <th>S.No.</th>
                                        <th>Company Name</th>
                                        <th>Customer Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Designation</th>
                                        <th>Product</th>
                                        <th>No. of Licenses</th>
                                        <th>Subscription Term</th>
                                        <th>Stage</th>
                                        <th>Proof Engagement</th>
                                        <th>Partner Name</th>
                                        <th>Expected Closure Date</th>
                                        <th>Created By</th>
                                        <th>Created At</th>
                                        <th>Action</th>
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

        <?php include('includes/footer.php') ?>

        <script>
            var currentUserType = '<?= (string)($_SESSION['user_type'] ?? '') ?>';
            var isMngReadOnly = (currentUserType === 'MNGR' || currentUserType === 'MNG');

            $('#leads').DataTable({
                "dom": '<"top"if>Brt<"bottom"ip><"clear">',
                "displayLength": 15,
                "scrollX": false,
                "fixedHeader": true,

                language: {
                    paginate: {
                        previous: '<i class="fas fa-arrow-left"></i>',
                        next: '<i class="fas fa-arrow-right"></i>'
                    }
                },
                
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
                    [15, 25, 50, 100, 500, 1000, 5000, 10000, 15000],
                    ['15', '25', '50', '100', '500', '1000', '5000', '10000', '15000']
                ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "get_opportunity.php", // json datasource
                    type: "post", // method  , by default get
                    data: function(d) {
                    d.subscription_term = '<?= json_encode($_GET['subscription_term'] ?? []) ?>';
                    d.stage_id = '<?= json_encode($_GET['stage_id'] ?? []) ?>';
                    d.proof_engagement_id = '<?= json_encode($_GET['proof_engagement_id'] ?? []) ?>';
                    d.approval_status = '<?= json_encode($_GET['approval_status'] ?? []) ?>';
                    d.assigned_partner_id = '<?= json_encode($_GET['assigned_partner_id'] ?? []) ?>';
                    d.align_to = '<?= json_encode($_GET['align_to'] ?? []) ?>';
                    },
                    error: function() { // error handling
                        $(".employee-grid-error").html("");
                        $("#leads").append('<tbody class="employee-grid-error"><tr><th colspan="16">No data found on server!</th></tr></tbody>');
                        $("#leads_processing").css("display", "none");
                    }
                },
                "drawCallback": function() {
                    if (isMngReadOnly) {
                        $('#leads a.btn').addClass('list-action-disabled').attr('tabindex', '-1');
                    }
                },
                "columnDefs": [
                    { "data": "id", "targets": 0 },
                    { "data": "company_name", "targets": 1 },
                    { "data": "customer_name", "targets": 2 },
                    { "data": "email", "targets": 3 },
                    { "data": "phone", "targets": 4 },
                    { "data": "designation", "targets": 5 },
                    { "data": "product", "targets": 6 },
                    { "data": "licenses", "targets": 7 },
                    { "data": "subscription_term", "targets": 8 },
                    { "data": "stage_id", "targets": 9 },
                    { "data": "proof_engagement_id", "targets": 10 },
                    { "data": "partner_name", "targets": 11 },
                    { "data": "expected_closure_date", "targets": 12 },
                    { "data": "created_by", "targets": 13 },
                    { "data": "created_at", "targets": 14 },
                    { 
                        "data": "action", 
                        "targets": 15, 
                        "orderable": false,
                        "render": function(data) {
                            return data ? data : '';
                        }
                    }
                ]
            });

            function clear_search() {
                $("#search-form")[0].reset();
                window.location.href = 'manage_opportunity.php';
            }

            $(document).ready(function() {
                $('#multiselect_subscription_term').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Subscription Term',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselect_stage_id').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Stage',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselect_proof_engagement_id').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Proof Engagement',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselect_approval_status').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Approval Status',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselect_assigned_partner').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Assigned to Partner',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
                $('#multiselect_align_to').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Align To',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });

                function loadAlignToFilterUsers(selectedUsers) {
                    var partnerIds = $('#multiselect_assigned_partner').val() || [];

                    $.ajax({
                        type: 'POST',
                        url: 'ajax_update.php',
                        data: {
                            action: 'get_partner_users_by_partners',
                            partner_ids: partnerIds,
                            selected_users: selectedUsers || []
                        },
                        success: function(html) {
                            $('#multiselect_align_to').html(html);
                            $('#multiselect_align_to').multiselect('rebuild');
                        },
                        error: function() {
                            $('#multiselect_align_to').html('<option value="">---Select---</option>');
                            $('#multiselect_align_to').multiselect('rebuild');
                        }
                    });
                }

                $('#multiselect_assigned_partner').on('change', function() {
                    loadAlignToFilterUsers([]);
                });

                loadAlignToFilterUsers($('#multiselect_align_to').val() || []);
            });
        </script>
    </div>
</div>

</body>
</html>
