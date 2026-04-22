<?php include("includes/include.php");

$_POST['pid'] = intval($_POST['pid']);
$product = intval($_POST['product']);
$oppAttachPO = db_query("SELECT * from opportunity_attachments where lead_id='".$pid."' and product_id='".$product."' and attachment_type='po_attachments' and status=1 ");
$oppAttachPI = db_query("SELECT * from opportunity_attachments where lead_id='".$pid."' and product_id='".$product."' and attachment_type='pi_attachments' and status=1 ");
$oppAttachInv = db_query("SELECT * from opportunity_attachments where lead_id='".$pid."' and product_id='".$product."' and attachment_type='invoice_attachments' and status=1 ");
?>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title align-self-center mt-0">
                Edit</b>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <form action="#" method="post" class="form p-t-20" enctype="multipart/form-data">
            <input type="hidden" name="lead_id" id="lead_id" value="<?= $_POST['pid'] ?>">
                <input type="hidden" name="product_id" id="product_id" value="<?= $_POST['product'] ?>">
                <input type="hidden" name="form_type" id="form_type" value="update">
                <input type="hidden" name="tbl_lead_opportunity_id" id="tbl_lead_opportunity_id" value="<?= $_POST['tbl_lead_opportunity_id'] ?>">

                    <div class="custom-tabs">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="active" id="po-tab" data-toggle="tab" href="#po2" role="tab" aria-controls="po" aria-selected="true">PO</a>
                            </li>
                            <!-- <li class="nav-item" role="presentation">
                                <a id="pi-tab" data-toggle="tab" href="#pi2" role="tab" aria-controls="pi" aria-selected="false">PI</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a id="invoice-tab" data-toggle="tab" href="#invoice2" role="tab" aria-controls="invoice" aria-selected="false">Invoice</a>
                            </li> -->
                        </ul>
                        <div class="tab-content" id="myTabContent">
<!-- po  -->
                            <div class="tab-pane fade show active" id="po2" role="tabpanel" aria-labelledby="po-tab">
                                
                            <?php
                                if(mysqli_num_rows($oppAttachPO) > 0){
                                $i = 1;
                                while($po = db_fetch_array($oppAttachPO))
                                {  
                                ?>
                                <input type="hidden" name="attachments_ids[]" value="<?= $po['id'] ?>">
                                <div class="row upload-row">
                                    <div class="col-md">
                                        <div class="form-group">
                                            <label for="">Document Name <span class="text-danger">*</span></label>
                                            <input type="text" name="attachment_names[]" class="form-control document-name" placeholder="Document Name" value="<?= $po['attachment_name'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-auto">
                                        <div class="form-group">
                                            <label>Document Icon <span class="text-danger">*</span></label>
                                            <div class="upload-btn">
                                                <input type="file" class="upload-file" name="attachments[]" id="Upload<?= $i ?>" accept=".jpg,.png,.pdf,.doc,.docx,.xls,.xlsx" onchange="enableSaveButton()">
                                                <label class="btn btn-primary mb-0 py-1" for="Upload<?= $i ?>"><i class="mdi mdi-upload" style="font-size: 18px"></i> Upload</label>
                                                <img class="preview-image" src="images/default-attach.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $i++; 
                            }}else{ ?>
                                    <p>No attachment available.</p>
                                    <?php } ?>
                            </div>
<!-- pi -->
                            <div class="tab-pane fade" id="pi2" role="tabpanel" aria-labelledby="pi-tab">
                            <?php
                            if(mysqli_num_rows($oppAttachPI) > 0){
                                $i = 1;
                                while($pi = db_fetch_array($oppAttachPI))
                                { 
                                ?>
                                <input type="hidden" name="attachments_ids[]" value="<?= $pi['id'] ?>">
                                <div class="row upload-row">
                                    <div class="col-md">
                                        <div class="form-group">
                                            <label for="">Document Name <span class="text-danger">*</span></label>
                                            <input type="text" name="attachment_names[]" class="form-control document-name" placeholder="Document Name" value="<?= $pi['attachment_name'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-auto">
                                        <div class="form-group">
                                            <label>Document Icon <span class="text-danger">*</span></label>
                                            <div class="upload-btn">
                                                <input type="file" name="attachments[]" class="upload-file" id="piUpload<?= $i ?>" accept=".jpg,.png,.pdf,.doc,.docx,.xls,.xlsx" readonly>
                                                <label class="btn btn-primary mb-0 py-1" for="piUpload<?= $i ?>"><i class="mdi mdi-upload" style="font-size: 18px"></i> Upload</label>
                                                <img class="preview-image" src="images/default-attach.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                        $i++;
                                }}else{ ?>
                                <p>No attachment available.</p>
                                <?php } ?>
                            </div>
<!-- invoice -->
                            <div class="tab-pane fade" id="invoice2" role="tabpanel" aria-labelledby="invoice-tab">
                            <?php
                            if(mysqli_num_rows($oppAttachInv) > 0){
                                $i = 1;
                                while($inv = db_fetch_array($oppAttachInv))
                                { 
                                ?>    
                                <input type="hidden" name="attachments_ids[]" value="<?= $inv['id'] ?>">
                                <div class="row upload-row">
                                    <div class="col-md">
                                        <div class="form-group">
                                            <label for="">Document Name <span class="text-danger">*</span></label>
                                            <input type="text" name="attachment_names[]" class="form-control document-name" placeholder="Document Name" value="<?= $inv['attachment_name'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-auto">
                                        <div class="form-group">
                                            <label>Document Icon <span class="text-danger">*</span></label>
                                            <div class="upload-btn">
                                                <input type="file" id="iUpload<?= $i ?>" class="upload-file" accept=".jpg,.png,.pdf,.doc,.docx,.xls,.xlsx" name="attachments[]">
                                                <label class="btn btn-primary mb-0 py-1" for="iUpload<?= $i ?>"><i class="mdi mdi-upload" style="font-size: 18px"></i> Upload</label>
                                                <img class="preview-image" src="images/default-attach.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                $i++;
                                }}else{ ?>
                                <p>No attachment available.</p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <button type="submit" id="save_attachment" class="btn btn-primary" disabled>Save</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    </div> 
                </form>
            </div>
        </div>
    </div>

    <script>
        // Select all file inputs
document.querySelectorAll(".upload-file").forEach((input) => {
    input.addEventListener("change", function(event) {
        let file = event.target.files[0];

        if (file) {
            // Get the closest row container
            let row = event.target.closest(".upload-row");

            // Set document name field value in the same row
            row.querySelector(".document-name").value = file.name;

            // Preview based on file type
            let fileType = file.name.split('.').pop().toLowerCase();
            let previewImageSrc = "images/default-file.png";  // Default image if no match

            if (fileType === "xlsx" || fileType === "xls") {
                previewImageSrc = "images/excel-icon.png"; // Excel thumbnail
            } else if (fileType === "docx" || fileType === "doc") {
                previewImageSrc = "images/word-icon.png"; // Word thumbnail
            } else if (fileType === "pdf") {
                previewImageSrc = "images/pdf-icon.png"; // PDF thumbnail
            } else if (["jpg", "jpeg", "png", "gif"].includes(fileType)) {
                // Handle image files as usual
                let reader = new FileReader();
                reader.onload = function(e) {
                    // row.querySelector(".preview-image").style.display = 'block';
                    row.querySelector(".preview-image").src = e.target.result;
                };
                reader.readAsDataURL(file);
                return;  // Skip the preview image change for document types
            }

            // Update the preview image for document files
            row.querySelector(".preview-image").src = previewImageSrc;
        }
    });
});

function enableSaveButton(){
    document.getElementById('save_attachment').disabled = false;
}
    </script>