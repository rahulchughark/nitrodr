<?php include("includes/include.php");

include_once('helpers/DataController.php');
$dataObj = new DataController;

$id = $_POST['id'] ?? 0;
$name = $_POST['name'] ?? "";

?>

<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Admin Controls</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
            <form
                action="ajax_learning_zone_upload.php"
                method="post"
                class="upload-form"
                enctype="multipart/form-data"
            >
                <!-- Hidden Fields -->
                <input type="hidden" name="category_id" value="<?= $id ?>">
                <input type="hidden" name="category_name" value="<?= $name ?>">

                <!-- ======================
                     STEP 1: Partner Access
                ======================= -->
                <div id="permission-container">
                    <h5 class="font-weight-bold mb-3">Manage Partner Access</h5>

                    <div class="form-group">
                        <label>Partner Name</label>
                        <select
                            name="partner[]"
                            class="multiselect_partner form-control"
                            multiple
                            required
                        >
                        <?=
                        $dataObj->getActivePartners();
                        ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>User Type</label>
                        <select
                            name="user_type[]"
                            class="multiselect_user_type form-control"
                            multiple
                            required
                        >
                            <option value="USR">User</option>
                            <option value="MNGR">Manager</option>
                        </select>
                    </div>

                    <div class="text-center mt-3">
                        <button
                            type="button"
                            class="btn btn-primary"
                            id="nextStepBtn"
                        >
                            Next
                        </button>
                    </div>
                </div>

                <!-- ======================
                     STEP 2: Upload Section
                ======================= -->
                <div
                    id="document-container"
                    style="display:none;"
                >
                    <h5 class="font-weight-bold mb-3">
                        Upload Documents
                        <small class="text-muted">— Add up to 15 files</small>
                    </h5>

                    <!-- File Type -->
                    <div class="form-group">
                        <label>Type</label><br>

                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="file_type"
                                value="DOC"
                                checked
                            >
                            <label class="form-check-label">DOC</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="file_type"
                                value="VIDEO"
                            >
                            <label class="form-check-label">VIDEO</label>
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="form-group">
                        <label>Attach Document</label>

                        <div class="upload-container">
                            <div class="drop-area">
                                <div class="preview"></div>
                                <p>
                                    Drag & Drop files here or
                                    <strong>click</strong> to upload
                                </p>

                                <input
                                    type="file"
                                    name="attachments[]"
                                    id="fileElm"
                                    class="fileElem"
                                    multiple
                                    hidden
                                >

                                <label class="add-file-btn" for="fileElm">
                                    Add Files
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            Upload
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- ======================
     SCRIPT
====================== -->
<script>
$(document).ready(function () {

    /* Multiselect Init */
    $('.multiselect_partner').multiselect({
        buttonWidth: '100%',
        includeSelectAllOption: true,
        nonSelectedText: 'Select Partner',
        enableFiltering: true
    });

    $('.multiselect_user_type').multiselect({
        buttonWidth: '100%',
        includeSelectAllOption: true,
        nonSelectedText: 'Select User Type'
    });

    /* Next Button Click */
    $('#nextStepBtn').on('click', function () {
        $('#permission-container').hide();
        $('#document-container').show();
    });

});
</script>
