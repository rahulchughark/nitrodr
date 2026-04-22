<?php 
include('includes/header.php');
include_once('helpers/DataController.php');
admin_page();

$projectID = "68abf5ca7d30730c67382ce8";
$pswdKey = "a850dc5d98af7292567f1";

$dataObj = new DataController;

$campaigns = $dataObj->getAISensyCampaignList($projectID, $pswdKey);
$templates = $dataObj->getAISensyTemplates($projectID, $pswdKey);

$parentCampaigns = $dataObj->getParentCampaignList(1);
$campaignTags       = $dataObj->getAllCampaignTags();
$campaignCategories = $dataObj->getAllCampaignCategories();

?>

<style>
    .mdi {
        font-size: 16px;
    }
    .table thead th {
        vertical-align: middle;
    }

    /* .table tbody td:first-child {
        text-align: center;
    } */

    .inner-items {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .inner-items p {
        white-space: nowrap;
        margin-bottom: 0;
    }

    .inner-items p .mdi {
        font-size: 16px;
        line-height: 1;
    }

    .inner-items .form-fields {
        display: flex;
        gap: 5px;
    }
    
    .inner-items .form-control {
        width: 140px;
    }

    .inner-items p, .inner-items .form-control:not(textarea), .inner-items .form-fields .btn {
        height: 24px;
    }

    .modal-body {
        min-height: 300px;
    }

    #uploadModal {
        background: rgba(0, 0, 0, .32);
        backdrop-filter: blur(5px);
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

                        <div class="col-sm text-right">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addCampaignModal">
                                <i class="fa fa-plus"></i> Add Campaign
                            </button>
                        </div>

                            <div class="row">
                                <div class="col-sm">
                                    <div class="media bredcrum-title">
                                        <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">
                                            <small class="text-muted">Home >Campaign Report  </small>
                                            <h4 class="font-size-14 m-0 mt-1">Campaign Report</h4>
                                        </div>
                                    </div>
                                </div>
                                 
                            </div>
                            <div class="table-responsive">
                               <table id="leads" 
                                       class="table display nowrap table-striped" 
                                       data-height="wfheight" 
                                       data-mobile-responsive="true" 
                                       cellspacing="0" 
                                       width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.no</th>                                                                          
                                            <th>Campaign Name</th>
                                            <th>Total Audience</th>
                                            <th>Sent</th>
                                            <th>Failed</th>
                                            <th>Tempate Text</th>
                                            <th>Created At</th>
                                            <th>Contacts</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                
                                    </tbody>
                                </table>
                            </div>

                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

<div class="modal fade" id="addCampaignModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg" style="border-radius:12px;">

            <div class="modal-header border-0 pb-0">
                <h4 class="modal-title w-100 text-center font-weight-bold">Add Campaign</h4>
                <button type="button" class="close" data-dismiss="modal" style="position:absolute; right:20px; top:20px;">
                    <span style="font-size:28px;">&times;</span>
                </button>
            </div>

            <hr style="width:85%; margin:auto; margin-top:10px;">

            <form id="campaignForm">
                <div class="modal-body pt-4 pb-1" style="padding-left:40px; padding-right:40px;">
                
                <div class="form-group mb-4">
                    <label class="font-weight-bold" style="color:#555;">Select Parent</label>
                    <select name="parent_campaign" id="parent_campaign" class="form-control">
                            <option value="">Select Parent Campaign</option>
                            <?php foreach ($parentCampaigns as $pc) { ?>
                                <option value="<?= $pc['campaign_id']; ?>">
                                    <?= htmlspecialchars($pc['campaign_name']); ?>
                                </option>
                            <?php } ?>
                    </select>
                   <small class="text-danger font-weight-bold">
                        Only executed campaign masters are displayed
                    </small>
                </div>
                

                    <!-- Campaign Name -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold" style="color:#555;">Campaign Name</label>
                        <input type="text" class="form-control" name="campaign_name" placeholder="Enter campaign name" required>
                    </div>

                    <!-- Template Dropdown -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold" style="color:#555;">Template</label>
                        <select class="form-control" name="template_id" required>
                            <option value="">Select Template</option>

                            <?php if (!empty($templates)) { ?>
                                <?php foreach ($templates as $tpl) { ?>
                                    <option value="<?= $tpl['id']; ?>">
                                        <?= htmlspecialchars($tpl['name']); ?>
                                    </option>
                                <?php } ?>
                            <?php } else { ?>
                                <option value="">No templates found</option>
                            <?php } ?>
                        </select>
                    </div>

                   <div class="form-group mb-4">
                        <!-- <label class="font-weight-bold d-block mb-2" style="color:#555;">
                            Campaign Type
                        </label> -->

                        <div class="custom-control custom-switch d-flex align-items-center">
                            <input type="checkbox"
                                class="custom-control-input"
                                id="is_parent"
                                name="is_parent"
                                value="1"
                                checked>
                            <label class="custom-control-label font-weight-semibold" for="is_parent">
                                Is Parent Campaign
                            </label>
                        </div>

                        <small class="text-muted mt-1 d-block">
                            Turn OFF to select an existing parent campaign
                        </small>
                    </div>

                    
                    
                </div>

                <div class="modal-footer border-0 justify-content-end pr-4 pb-4">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>

                    <button type="submit" id="saveBtn" class="btn btn-success px-4" style="border-radius:8px;">
                        <span class="btn-text">Save</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


<div class="modal fade" id="importCampaignModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg" style="border-radius:12px;">

            <div class="modal-header border-0 pb-0">
                <h4 class="modal-title w-100 text-center font-weight-bold">
                    Import Campaign Data
                </h4>
                <button type="button" class="close" data-dismiss="modal"
                        style="position:absolute; right:20px; top:20px;">
                    <span style="font-size:28px;">&times;</span>
                </button>
            </div>

            <hr style="width:85%; margin:auto; margin-top:10px;">

           
            <form id="importCampaignForm"
                  action="import_phone.php"
                  method="POST"
                  enctype="multipart/form-data">

                <div class="modal-body pt-4 pb-1"
                     style="padding-left:40px; padding-right:40px;">

                    <!-- Campaign ID -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Campaign ID</label>
                        <input type="text"
                               class="form-control"
                               name="campaign_id"
                               id="import_campaign_id"
                               readonly>
                    </div>

                    <!-- Campaign Name -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Campaign Name</label>
                        <input type="text"
                               class="form-control"
                               id="import_campaign_name"
                               readonly>
                    </div>

                    <div class="form-group mb-4">
                    <label class="font-weight-bold" style="color:#555;">Campaign Category</label>
                    <select name="phone_category" class="form-control" required>
                        <option value="">Select Category</option>

                        <?php if (!empty($campaignCategories)) { ?>
                            <?php foreach ($campaignCategories as $cat) { ?>
                                <option value="<?= $cat['id']; ?>">
                                    <?= htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php } ?>
                        <?php } else { ?>
                            <option value="">No categories found</option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold" style="color:#555;">Campaign Tags</label>
                    <select name="phone_tag" class="form-control" required>
                        <option value="">Select Tag</option>
                        <?php if (!empty($campaignTags)) { ?>
                            <?php foreach ($campaignTags as $tag) { ?>
                                <option value="<?= $tag['id']; ?>">
                                    <?= htmlspecialchars($tag['name']); ?>
                                </option>
                            <?php } ?>
                        <?php } else { ?>
                            <option value="">No tags found</option>
                        <?php } ?>
                    </select>                    
                </div>

                    <!-- Excel File -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Upload Excel File</label>
                        <input type="file"
                               class="form-control"
                               name="import_file"
                               accept=".xls,.xlsx"
                               required>
                    </div>

                    <div class="form-group mb-3">
                    <a href="sample.xlsx"
                    target="_blank"
                    class="text-primary font-weight-semibold d-inline-flex align-items-center">
                        <i class="mdi mdi-file-excel mr-1"></i>
                        Download Sample Excel Format
                    </a>

                    <small class="text-muted d-block mt-1">
                        Use this file to upload campaign contact data correctly
                    </small>
                </div>

                </div>

                <div class="modal-footer border-0 justify-content-end pr-4 pb-4">
                    <button type="button" class="btn btn-light" data-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-success px-4"
                            style="border-radius:8px;">
                        Import
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>



<?php include('includes/footer.php') ?>
<script>

var table = $('#leads').DataTable({
    dom: 'Bfrtip',
    "displayLength": 15,
    processing: true,
    serverSide: true,

    language: {
        paginate: {
            previous: '<i class="fas fa-arrow-left"></i>',
            next: '<i class="fas fa-arrow-right"></i>'
        }
    },

    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength',
    ],

    ajax: { 
        url: 'ajax_campaign.php', 
        type: 'post'
    },

    columns: [
        { data: 'sno' },                 
        { data: 'campaign_name' },    
        { data: 'audience_size' },    
        { data: 'total_sent' },    
        { data: 'latest_fail' },    
        { data: 'template_text' },    
        { data: 'created_at' },       
        { data: 'contacts' },       
        { data: 'action_btn' }
    ],
    columnDefs: [
        { orderable: false, targets: '_all' }
    ]
});


// Fix previous button going back to last page
$('#example23_previous').on('click', function () {
    if (table.page() === 0) {
        table.page('last').draw('page');
    }
});


// Reload table (optional function)
function refreshDatatable() {
    $('#example23').DataTable().ajax.reload(null, false);
}

</script>


<script>
$(document).ready(function () {
    var wfheight = $(window).height();
    $('.dataTables_wrapper').height(wfheight - 310);
    $("#example23").tableHeadFixer();
});

$('#leads').on('click', '.toggle-text', function () {
    let row = $(this).closest('td');

    row.find('.short-text').toggleClass('d-none');
    row.find('.full-text').toggleClass('d-none');

    $(this).text($(this).text() === 'Show more' ? 'Show less' : 'Show more');
});
</script>


<script>
$("#campaignForm").submit(function (e) {
    e.preventDefault();

    // Get field values
    let campaignName = $("#campaign_name").val();
    let templateId = $("#template_id").val();

    // Validation: both fields required
    if (campaignName === "" || templateId === "") {
        toastr.error("Both fields are required!");
        return; // Stop execution
    }

    let btn = $("#saveBtn");

    btn.prop("disabled", true);
    btn.find(".btn-text").text("Saving...");
    btn.find(".spinner-border").removeClass("d-none");

    $.ajax({
        url: "save_campaign.php",
        type: "POST",
        data: $(this).serialize(),
        success: function (response) {

            toastr.success("Campaign Created Successfully");
            $("#addCampaignModal").modal("hide");
            location.reload();

            // $('#leads').DataTable().ajax.reload();
        },
        error: function () {
            toastr.error("Something went wrong!");
        },
        complete: function () {
            btn.prop("disabled", false);
            btn.find(".btn-text").text("Save");
            btn.find(".spinner-border").addClass("d-none");
        }
    });
});
</script>

<script>
function openImportModal(campaignId, campaignName) {
    $('#import_campaign_id').val(campaignId);
    $('#import_campaign_name').val(campaignName);
    $('input[name="import_file"]').val('');
    $('#importCampaignModal').modal('show');
}
</script>
<script>
$('#importCampaignForm').on('submit', function (e) {
    e.preventDefault();

    let form = this;
    let formData = new FormData(form);

    $.ajax({
        url: form.action, // import_phone.php
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',

        beforeSend: function () {
            $(form).find('button[type="submit"]').prop('disabled', true)
                .text('Importing...');
        },

        success: function (res) {
            if (res.status) {
                // alert(res.message);
                toastr.success(res.message || 'Import completed successfully');
                $('#importCampaignModal').modal('hide');
            } else {
                // alert(res.message);
                toastr.error(res.message || 'Import failed');
            }

            if (res.errors && res.errors.length > 0) {
                res.errors.forEach(function (err) {
                    toastr.warning(err);
                });
            }

        },

        error: function () {
            // alert('Something went wrong while importing.');
              toastr.error('Something went wrong while importing.');
        },

        complete: function () {
            $(form).find('button[type="submit"]').prop('disabled', false)
                .text('Import');
        }
    });
});
</script>
<script>
function runCampaign(campaignId, campaignName) {

    // Replace confirm() with Toastr + JS confirm alternative
    if (!window.confirm(`Are you sure you want to run campaign "${campaignName}"?`)) {
        return;
    }

    $.ajax({
        url: 'run_campaign.php',
        type: 'POST',
        dataType: 'json',
        data: {
            campaign_id: campaignId,
            campaign_name: campaignName
        },

        beforeSend: function () {
            toastr.info('Campaign is starting...');
        },

        success: function (res) {
            if (res.status) {
                toastr.success(res.message || 'Campaign started successfully');
                setTimeout(() => location.reload(), 1200);
            } else {
                toastr.error(res.message || 'Failed to start campaign');
            }
        },

        error: function () {
            toastr.error('Failed to start campaign');
        }
    });
}
</script>
<script>
    
$(document).ready(function () {

    function toggleParentCampaign() {
        if ($('#is_parent').is(':checked')) {
            // Parent campaign → hide dropdown
            $('#parent_campaign').closest('.form-group').hide();
            $('#parent_campaign').val('');
        } else {
            // Child campaign → show dropdown
            $('#parent_campaign').closest('.form-group').show();
        }
    }

    // Initial state (default checked)
    toggleParentCampaign();

    // On checkbox toggle
    $('#is_parent').on('change', toggleParentCampaign);

    // Reset when modal opens
    $('#addCampaignModal').on('shown.bs.modal', function () {
        toggleParentCampaign();
    });

});

</script>