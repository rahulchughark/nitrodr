<?php 
include('includes/header.php');
include_once('helpers/DataController.php');
admin_page();

$projectID = "68abf5ca7d30730c67382ce8";
$pswdKey = "a850dc5d98af7292567f1";

$dataObj = new DataController;
$campaignID = $_GET['campaign_id'] ?? '';
$campaignName = $dataObj->getCampaignByCampaignId($campaignID, 'campaign_name');
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

    /* .modal-body {
        min-height: 300px;
    } */

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
                            <button class="btn btn-primary" onclick="return openImportModal('<?=$campaignID?>','<?= $campaignName?>')">
                                <i class="fa fa-plus"></i> Import
                            </button>

                            <button class="btn btn-primary" data-toggle="modal" data-target="#addCampaignModal">
                                <i class="fa fa-plus"></i> Send Campaign
                            </button>
                        </div>

                            <div class="row mt-3">
                                <div class="col-sm">
                                    <div class="media bredcrum-title">
                                        <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">
                                            <small class="text-muted">Home >Campaign Contacts  </small>
                                            <h4 class="font-size-14 m-0 mt-1">Campaign Contacts</h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-auto pt-2 pt-sm-0">
                                    <div class="" role="group">
                                   
                                  
                                        <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                        <div class="dropdown dropdown-lg">

                                            <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                                                <form method="get" id="search-form" name="search">
                                                    <div class="row">
                                                        <input type="hidden" name="campaign_id" value="<?= htmlspecialchars($campaignID); ?>">
                                                            
                                                         <div class="form-group col-md-6 col-xl-3">
                                                            <select name="category" class="form-control" id="category">
                                                                <option value="">Select Category</option>
                                                                    <?php if (!empty($campaignCategories)) { ?>
                                                                        <?php foreach ($campaignCategories as $cat) { ?>
                                                                            <option <?= $_GET['category'] == $cat['id'] ? 'selected' : '' ?> value="<?= $cat['id']; ?>">
                                                                                <?= htmlspecialchars($cat['name']); ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <option value="">No categories found</option>
                                                                    <?php } ?>
                                                            </select>
                                                        </div>

                                                      
                                                             <!-- Financial Year -->
                                                            <div class="form-group col-md-6 col-xl-3">
                                                                <select name="tag" class="form-control" id="tag">
                                                                    <option value="">Select Tag</option>
                                                                     <?php if (!empty($campaignTags)) { ?>
                                                                        <?php foreach ($campaignTags as $tag) { ?>
                                                                            <option <?= $_GET['tag'] == $tag['id'] ? 'selected' : '' ?> value="<?= $tag['id']; ?>">
                                                                                <?= htmlspecialchars($tag['name']); ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <option value="">No tags found</option>
                                                                    <?php } ?>
                                                                   
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
                            <div class="table-responsive">
                               <table id="leads" 
                                       class="table display nowrap table-striped" 
                                       data-height="wfheight" 
                                       data-mobile-responsive="true" 
                                       cellspacing="0" 
                                       width="100%">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="selectAllPhones">
                                            </th>
                                            <th>S.no</th>                                                                          
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Phone</th>                                    
                                         
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
                <h4 class="modal-title w-100 text-center font-weight-bold">Send Campaign</h4>
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
                              <option value="<?= htmlspecialchars($pc['campaign_name']); ?>">
                                    <?= htmlspecialchars($pc['campaign_name']); ?>
                                </option>
                            <?php } ?>
                    </select>
                </div>
   
                    
                </div>

                <div class="modal-footer border-0 justify-content-end pr-4 pb-4">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>

                    <button type="submit" id="saveBtn" class="btn btn-success px-4" style="border-radius:8px;">
                        <span class="btn-text">Send</span>
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
    
 function getUrlParameter(name) {
                    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                    var results = regex.exec(location.search);
                    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
                }


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
        url: 'ajax_campaign_contacts.php', 
        type: 'post',
        data: function (d) {
                                    d.category = getUrlParameter('category'),
                                    d.tag = getUrlParameter('tag')
                                }
    },

    columns: [
        { data: 'checkbox', orderable: false, searchable: false },
        { data: 'sno' },
        { data: 'contact_name' },
        { data: 'code' },
        { data: 'phone_number' }
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
let selectedPhoneIds = [];

function onPhoneSelect(phoneId, checkbox) {


    if (checkbox.checked) {
        if (!selectedPhoneIds.includes(phoneId)) {
            selectedPhoneIds.push(phoneId);
        }
    } else {
        selectedPhoneIds = selectedPhoneIds.filter(id => id !== phoneId);
    }
    // console.log("selectedPhoneIds", selectedPhoneIds);
}


/* Header select all */
$('#selectAllPhones').on('change', function () {

    const isChecked = $(this).is(':checked');
    selectedPhoneIds = [];

    $('.phone-checkbox').each(function () {
        $(this).prop('checked', isChecked);

        if (isChecked) {
            selectedPhoneIds.push(parseInt($(this).val()));
        }
    });

    // console.log("selectedPhoneIds:", selectedPhoneIds);
});
</script>

<script>
$('#campaignForm').on('submit', function (e) {
    e.preventDefault();

    if (selectedPhoneIds.length === 0) {
        // alert('Please select at least one contact.');
        toastr.info('Please select at least one contact.');
        return;
    }


    // const campaignName = $('#parent_campaign option:selected').text();
    const campaignName = $('#parent_campaign').val();
    const campaignId   = $('#parent_campaign').val();

    if (!campaignId) {
        // alert('Please select a campaign.');
        toastr.info('Please select a campaign.');
        return;
    }

      // ✅ Confirmation
    const confirmSend = confirm(
        `Are you sure you want to send campaign message to ${selectedPhoneIds.length} number(s)?`
    );

    if (!confirmSend) {
        return; // stop submission
    }

    $.ajax({
        url: 'send-selected-messages.php',
        type: 'POST',
        data: {
            phone_ids: selectedPhoneIds,
            campaign_name: campaignName
        },
        beforeSend: function () {
            $('#saveBtn').prop('disabled', true);
            $('#saveBtn .btn-text').addClass('d-none');
            $('#saveBtn .spinner-border').removeClass('d-none');
        },
        success: function (res) {
            // alert(res.message || 'Messages sent successfully');
              toastr.success('Messages sent successfully');
              $('#campaignForm')[0].reset();
              $("#addCampaignModal").modal('hide');
              
        },
        complete: function () {
            $('#saveBtn').prop('disabled', false);
            $('#saveBtn .btn-text').removeClass('d-none');
            $('#saveBtn .spinner-border').addClass('d-none');
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
                location.reload();
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

function clear_search() {
                window.location = 'campaign-contacts.php?campaign_id=' + getUrlParameter('campaign_id');
            }

</script>