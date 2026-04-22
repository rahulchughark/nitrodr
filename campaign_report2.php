<?php 
include('includes/header.php');
include_once('helpers/DataController.php');
admin_page();

$projectID = getSensyAiCredentials('projectId');
$pswdKey = getSensyAiCredentials('pswdKey');
$dataObj = new DataController;

$templates = $dataObj->getAISensyTemplates($projectID, $pswdKey);
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

    .sync-status {
    display: flex;
    align-items: center;
    gap: 16px;
    background: #f4f8ff;
    border: 1px solid #dbe7ff;
    border-radius: 10px;
    padding: 14px 16px;
    margin-bottom: 24px;
}

.sync-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e7f0ff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sync-text {
    display: flex;
    flex-direction: column;
}

.sync-title {
    font-weight: 600;
    font-size: 15px;
    color: #1f3a8a;
}

.sync-subtitle {
    font-size: 13px;
    color: #475569;
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
                                            <th>Total Contacts</th>
                                            <th>Sent</th>
                                            <th>Failed</th>
                                            <th>Invalid Contacts</th>
                                            <th>Template</th>
                                            <th>Created At</th>
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


<div class="modal fade"
     id="addCampaignModal"
     tabindex="-1"
     role="dialog"
     data-backdrop="static"
     data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg" style="border-radius:12px;">

            <div class="modal-header border-0 pb-0">
                <h4 class="modal-title w-100 text-center font-weight-bold">Add Campaign</h4>
                <button type="button" class="close" data-dismiss="modal" style="position:absolute; right:20px; top:20px;">
                    <span style="font-size:28px;">&times;</span>
                </button>
            </div>

            <hr style="width:85%; margin:auto; margin-top:10px;">

         <form id="campaignForm" enctype="multipart/form-data">
                <div class="modal-body pt-4 pb-1" style="padding-left:40px; padding-right:40px;">

                    <!-- <div id="syncLoader" class="d-none">
                        <div class="spinner-border text-primary"></div>
                        <span>Syncing failed messages, please wait...</span>
                    </div> -->
                    <div id="syncLoader" class="d-none sync-status">
                        <div class="sync-icon">
                            <div class="spinner-border text-primary"></div>
                        </div>

                        <div class="sync-text">
                        <div class="sync-title">
                            Sync in progress
                        </div>
                        <div class="sync-subtitle">
                            Fetching response from AISensy. Please wait <strong><span id="syncCounter">120</span>s</strong>.
                        </div>
                    </div>
                    </div>

                    <!-- Campaign Name -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold" style="color:#555;">Campaign Name</label>
                        <input type="text" class="form-control" name="campaign_name" placeholder="Enter campaign name" required>
                    </div>

                    <!-- Category Dropdown (Single Select) -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold" style="color:#555;">Category</label>
                       <select class="form-control" name="category_id" id="categorySelect">
                        <option value="">Select Category</option>
                        <?php foreach ($campaignCategories as $cat) { ?>
                            <option value="<?= $cat['id']; ?>">
                                <?= htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php } ?>
                        <option value="__add_new__">+ Add New Category</option>
                    </select>
                    </div>

                    <div class="form-group mb-4">
                    <label class="font-weight-bold" style="color:#555;">Tag</label>
                    <!-- Tags Dropdown (Single Select) -->
                    <select class="form-control" name="tag_id" id="tagSelect">
                        <option value="">Select Tag</option>
                        <?php if (!empty($campaignTags)) { ?>
                            <?php foreach ($campaignTags as $tag) { ?>
                                <option value="<?= $tag['id']; ?>">
                                    <?= htmlspecialchars($tag['name']); ?>
                                </option>
                            <?php } ?>
                        <?php } else { ?>
                            <!-- <option value="">No tags found</option> -->
                        <?php } ?>
                        <option value="__add_new__">+ Add New Tag</option>
                    </select>
                    </div>

                    <!-- Template Dropdown -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold" style="color:#555;">Template</label>
                        <select class="form-control" name="template_id" required>
                            <option value="">Select Template</option>
                            <?php if (!empty($templates)) { ?>
                                <?php foreach ($templates as $tpl) { ?>
                                    <option value="<?= $tpl['name']; ?>">
                                        <?= htmlspecialchars($tpl['name']); ?>
                                    </option>
                                <?php } ?>
                            <?php } else { ?>
                                <option value="">No templates found</option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Excel File Upload -->
                    <!-- <div class="form-group mb-4">
                        <label class="font-weight-bold" style="color:#555;">Upload Contacts (Excel)</label>
                        <input 
                            type="file" 
                            class="form-control-file"
                            name="contacts_file"
                            accept=".xls,.xlsx"
                            required
                        >
                        <small class="text-muted">Only .xls or .xlsx files allowed</small>
                    </div> -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold" style="color:#555;">
                            Upload Contacts (Excel)
                            <a href="uploads/sample.xlsx"
                            class="ml-2 text-primary"
                            download>
                                <small>(Download sample)</small>
                            </a>
                        </label>

                        <input 
                            type="file" 
                            class="form-control-file"
                            name="contacts_file"
                            accept=".xls,.xlsx"
                            required
                        >

                        <small class="text-muted">
                            Only .xls or .xlsx files allowed
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


        <!-- Modal -->
        <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false"></div>

<?php include('includes/footer.php') ?>
<script>

var table = $('#leads').DataTable({
    dom: 'Bfrtip',
    displayLength: 15,
    processing: true,
    serverSide: true,

    language: {
        paginate: {
            previous: '<i class="fas fa-arrow-left"></i>',
            next: '<i class="fas fa-arrow-right"></i>'
        }
    },

    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
    ],

    ajax: {
        url: 'ajax_campaign_data2.php',
        type: 'post'
    },

    columns: [
        { data: 'sno' },
        { data: 'campaign_name' },
        { data: 'total_contacts' },
        { data: 'sent_total' },
        { data: 'failed_send' },
        { data: 'invalid_contacts' },
        { data: 'template' },
        { data: 'created_at' },
        { data: 'action' }
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
$('#campaignForm').on('submit', function (e) {
    
    e.preventDefault();

    let formData = new FormData(this);
    let btn = $('#saveBtn');

    btn.prop('disabled', true);
    btn.find('.btn-text').text('Saving...');
    btn.find('.spinner-border').removeClass('d-none');

    $.ajax({
        url: 'ajax_save_campaign.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (res) {

            if (res.status) {
                toastr.success(res.message);
                // location.reload();
                // if (res.mst_id && res.campaign_id) {
                //     // Show 10-second loader
                //     let loader = $('#syncLoader'); // Make sure you have a div or spinner with this ID
                //     loader.removeClass('d-none');  // Show loader

                //     setTimeout(function () {
                //         console.log("calliongnn");
                //         loader.addClass('d-none'); // Hide loader after 10 sec
                //         syncFailedMessages(res.mst_id, res.campaign_id);
                //     }, 10000); // 10,000 ms = 10 seconds
                // }


                if (res.mst_id && res.campaign_id) {
                    let loader = $('#syncLoader');
                    let counterEl = $('#syncCounter');
                    let seconds = 120;
                   

                    // $("#saveBtn").prop("disabled",true);
                    btn.find('.btn-text').text('Updating...');

                    loader.removeClass('d-none');
                    // btn.prop('disabled', true);

                    // block reload
                    let beforeUnloadHandler = function (e) {
                        e.preventDefault();
                        e.returnValue = '';
                    };
                    window.addEventListener('beforeunload', beforeUnloadHandler);

                    counterEl.text(seconds);

                    let interval = setInterval(function () {
                        seconds--;
                        counterEl.text(seconds);

                        if (seconds <= 0) {
                            clearInterval(interval);

                            loader.addClass('d-none');
                            window.removeEventListener('beforeunload', beforeUnloadHandler);

                            btn.prop('disabled', false);
                            syncFailedMessages(res.mst_id, res.campaign_id);
                        }
                    }, 1000);
                }

            } else {
                toastr.error(res.message);
            }
        },
        error: function () {
            toastr.error('Something went wrong!');
        },
        // complete: function () {
        //     btn.prop('disabled', false);
        //     btn.find('.btn-text').text('Save');
        //     btn.find('.spinner-border').addClass('d-none');
        // }
    });
});


function syncFailedMessages(mstId, campaignId) {
 
    $.ajax({
        url: 'ajax_save_campaign.php',
        type: 'POST',
        data: {
            isPhoneStatus: 1,
            mst_id: mstId,
            campaign_id: campaignId
        },
        dataType: 'json',
        success: function (res) {
            if (res.status == true) {
                toastr.success('Failed messages synced successfully.');
                location.reload();
            } else {
                toastr.error(res.message || 'Failed to sync messages.');
            }
        },
        error: function () {
                location.reload();

            toastr.error('Something went wrong while syncing failed messages!');
        }
    });
}

$('#categorySelect').on('change', function () {
    if (this.value === '__add_new__') {

        let name = prompt("Enter new category name");
        if (!name) {
            $(this).val(''); // reset dropdown
            return;
        }

        $.post('ajax_data_update.php', {
            category_name: name
        }, function (res) {
            if (res.status) {
                $('#categorySelect')
                    .append(
                        `<option value="${res.id}" selected>${res.category_name}</option>`
                    );
            } else {
                alert('Unable to add category');
            }
        }, 'json');
    }
});

$('#tagSelect').on('change', function () {
    if (this.value === '__add_new__') {

        let name = prompt("Enter new tag name");
        if (!name) {
            $(this).val('');
            return;
        }

        $.post('ajax_data_update.php', {
            tag_name: name
        }, function (res) {
            if (res.status) {
                $('#tagSelect')
                    .append(
                        `<option value="${res.id}" selected>${res.tag_name}</option>`
                    );
            } else {
                alert('Unable to add tag');
            }
        }, 'json');
    }
});
</script>

