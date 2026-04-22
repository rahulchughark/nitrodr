<?php
include('includes/include.php');
include_once('helpers/DataController.php');

$orderID = (int)$_POST['order_id'];
$type = (int)$_POST['type'];
$is_group = $_POST['is_group'];
$group_name = $_POST['group_name'];


$sql = "SELECT sub_category, billing_detail, remark 
        FROM tbl_mst_invoice 
        WHERE order_id = $orderID AND status = 1 AND is_deleted = 0";
$result = db_query($sql);
$row = db_fetch_array($result);

// Default values if no data found
$sub_category   = $row['sub_category'] ?? '';
$billing_detail = $row['billing_detail'] ?? '';
$remark         = $row['remark'] ?? '';


$dataObj = new DataController();
$product_result = $dataObj->fetchOrderAttachments($orderID,'pi_attachments',$is_group,$group_name);

$countData = db_num_array($product_result);

$getEMIs = $dataObj->getInvoiceEmiByOrderId($orderID);
$pageLabel = $type == 1 ? "PI Edit" :  "PI View"; 

?>

<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
			<h5 class="modal-title align-self-center mt-0" id="uploadModalLabel"><?= $pageLabel ?></h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
		</div>
		<div class="modal-body">
            <div class="custom-tabs">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="active" id="inf-tab" data-toggle="tab" href="#inf" role="tab" aria-controls="inf" aria-selected="true">Info</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a id="attach-tab" data-toggle="tab" href="#attach" role="tab" aria-controls="attach" aria-selected="false">Attach</a>
                    </li>
                </ul>
                <form id="billingForm">
                    <div class="tab-content" id="myTabContent">
                    <input type="hidden" name="order_id" value="<?= $orderID ?>">
                    <div class="tab-pane fade show active" id="inf" role="tabpanel" aria-labelledby="inf-tab">

                     <div class="row">
            
                            <div class="col-sm">
                                <div class="form-group">
                                <label>Billing Name </label>
                                <input type="text" placeholder="Enter Billing Name" name="billing_name" value="<?= htmlspecialchars($billing_detail) ?>" class="form-control">
                                </div>
                            </div>

                            <div class="col-sm">
                                <div class="form-group">
                                <label>Sub Category</label>
                                <select class="form-control" name="sub_category">
                                    <option value="">Select Option</option>
                                    <option value="1" <?= $sub_category == '1' ? 'selected' : '' ?> >Direct</option>
                                    <option value="2" <?= $sub_category == '2' ? 'selected' : '' ?>>Reseller Combo</option>
                                </select>
                                </div>
                            </div>

                            </div>



                            <div id="amountRows">
                                <?php if (count($getEMIs) > 0): ?>
                                    <?php foreach ($getEMIs as $i => $value): ?>
                                    <div class="row amount-row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Amount</label>
                                                <input type="text" name="amount[]" value="<?= $value['amount'] ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>Date</label>
                                                        <input type="date" onkeydown="return false;" name="date[]" value="<?= $value['date'] ?>" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-auto">
                                                    <label class="invisible d-block">...</label>
                                                    <?php if ($type == 1 && $i == 0): ?>
                                                        <button type="button" class="btn btn-primary btn-xs add-row px-2">
                                                            <span class="mdi mdi-plus"></span>
                                                        </button>
                                                    <?php elseif($type == 1): ?>
                                                        <button type="button" class="btn btn-danger btn-xs remove-row px-2">
                                                            <span class="mdi mdi-minus"></span>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- When there are no EMIs but countData > 0 -->
                                    <div class="row amount-row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Amount</label>
                                                <input type="text" name="amount[]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>Date</label>
                                                        <input type="date" onkeydown="return false;" name="date[]" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <label class="invisible d-block">...</label>
                                                    <button type="button" class="btn btn-primary btn-xs add-row px-2">
                                                        <span class="mdi mdi-plus"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                           

                        <div class="form-group">
                            <label>Remarks</label>
                             <textarea name="remarks" rows="5" class="form-control"><?= htmlspecialchars($remark) ?></textarea>
                        </div>

                        <?php if($type ==1): ?>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        <?php endif; ?>
                    </div>
               


                    <div class="tab-pane fade" id="attach" role="tabpanel" aria-labelledby="attach-tab">
                        <table class="table">
                            <thead>
                                <tr>
                                    <!-- <th>Product</th> -->
                                    <th>Product Code</th>
                                    <th>Document Name</th>
                                </tr>
                            </thead>
                            <tbody>
                               <?php
                                    if (db_num_array($product_result) > 0) {
                                        while ($data = db_fetch_array($product_result)) {
                                    ?>
                                            <tr>
                                                <!-- <td><?= $data['product_name'] ?></td> -->
                                                <td><?=  $data['product_name'] ?></td>
                                                <td>
                                                    <div class="row upload-row align-items-center">
                                                        <div class="col-md">
                                                            <?= $data['attachment_name'] ?>
                                                        </div>
                                                        <div class="col-md-auto">
                                                            <div class="upload-btn">
                                                                <?php if($type == 1): ?>
                                                                <input type="file" 
                                                                    class="upload-file" 
                                                                    data-rowid="<?= $data['attachment_id']; ?>" 
                                                                    name="attachments" 
                                                                    id="Upload_<?= $data['attachment_id']; ?>" 
                                                                    accept=".jpg,.png,.pdf,.doc,.docx,.xls,.xlsx" 
                                                                    onchange="uploadAttachment(<?= $data['attachment_id']; ?>,this,<?= $data['master_lead_id']; ?>)">
                                                                 
                                                                <label class="btn btn-primary mb-0 py-1 upload-label-<?= $data['attachment_id'] ?>" 
                                                                    for="Upload_<?= $data['attachment_id']; ?>">
                                                                    <i class="mdi mdi-upload" style="font-size: 18px"></i> Upload
                                                                </label>

                                                                <?php endif; ?>
                                                                
                                                                <label class="btn btn-primary mb-0 py-1" 
                                                                        for="Upload"
                                                                        onclick="window.open('<?= $data['attachment_path'] ?>','_blank')">
                                                                        <i class="mdi mdi-eye" style="font-size: 18px"></i>
                                                                </label>

                                                                <!-- <a href="<?= $data['attachment_path'] ?>" target="_blank">
                                                                    <img class="preview-image_<?= $data['attachment_id']?>" 
                                                                        src="<?= $data['attachment_path'] ?>" 
                                                                        alt=""
                                                                        style="cursor: pointer;">
                                                                </a> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                    ?>
                                        <tr>
                                            <td colspan="2" class="text-center">No data found</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>

                            </tbody>
                        </table>
                    </div>
                    
        </div>
                                </form>
            </div>
           
        </div>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title align-self-center mt-0">Upload File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </div>
            <div class="modal-body py-4">
                <div class="upload-container">
                    <div class="drop-area">
                        <div class="preview"></div>
                        <p>Drag & Drop files here or <strong>click</strong> to upload</p>
                        <input type="file" name="po_attachments[]"  id="fileElm" class="fileElem" multiple accept=".jpg,.png,.pdf,.doc,.docx,.xls,.xlsx" hidden>
                        <label class="add-file-btn" for="fileElm">Add Files</label>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

</div>



<div id="rowTemplate" style="display:none;">
    <div class="row amount-row">
        <div class="col">
            <div class="form-group">
                <label>Amount</label>
                <input type="text" name="amount[]" class="form-control">
            </div>
        </div>
        <div class="col">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" onkeydown="return false;" name="date[]" class="form-control">
                    </div>
                </div>
                <div class="col-auto">
                    <label class="invisible d-block">...</label>
                    <button type="button" class="btn btn-danger btn-xs remove-row px-2">
                        <span class="mdi mdi-minus"></span>
                    </button>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>


//   $(document).on('click', '.add-row', function () {
//     var newRow = $('#rowTemplate').html();
//     $('#amountRows').append(newRow);
// });

// $(document).on('click', '.remove-row', function () {
//     $(this).closest('.amount-row').remove();
// });

$(document).off('click', '.add-row').on('click', '.add-row', function () {
    var newRow = $('#rowTemplate').html();
    $('#amountRows').append(newRow);
});

$(document).off('click', '.remove-row').on('click', '.remove-row', function () {
    $(this).closest('.amount-row').remove();
});


  $(document).ready(function () {
    $('#billingForm').on('submit', function (e) {
       
      e.preventDefault(); // prevent default form submit

      $.ajax({
        url: 'pi_attach_submit.php', // change this to your endpoint
        method: 'POST',
        data: $(this).serialize(), // serializes form data
        dataType: 'json',
        success: function (response) {
          if (response.status === 'success') {
            // alert(response.message); // or show success message on UI
            // $('#billingForm')[0].reset(); // reset form
            toastr.success(response.message);
            $('#leads').DataTable().ajax.reload(null, false);
          } else {
            // alert('Error: ' + response.message);
            toastr.error(response.message || "Something went wrong.");
          }
        },
        error: function (xhr, status, error) {
          // alert('AJAX Error: ' + error);
            console.error("AJAX error:", error);
            toastr.error("Failed to save remark. Try again.");
        }
      });
    });
  });

        // Select all file inputs
document.querySelectorAll(".upload-file").forEach((input) => {
    input.addEventListener("change", function(event) {
        let file = event.target.files[0];
        var rowID = $(this).data('rowid');

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
                    row.querySelector(".preview-image_"+rowID).src = e.target.result;
                };
                reader.readAsDataURL(file);
                return;  // Skip the preview image change for document types
            }

            // Update the preview image for document files
            row.querySelector(".preview-image_"+rowID).src = previewImageSrc;
        }
    });
});


function uploadAttachment(attachmentId, input, master_id) {

         $(".upload-label-"+attachmentId)
            .html('<i class="fa fa-spinner fa-spin"></i> Uploading...')
            .prop("disabled", true)
            .css("cursor", "not-allowed");
  
    if (input.files.length === 0) {
        toastr.warning("Please select a file to upload.");
        return;
    }

    let fileData = input.files[0];
    let formData = new FormData();
    formData.append('attachments', fileData);
    formData.append('attachment_id', attachmentId);
    formData.append('order_id', $("#order_id").val()); // Optional hidden input

    let orderID = <?= isset($orderID) ? $orderID : 0 ?>;

  
    $.ajax({
        url: 'pi_attach_file_upload.php', // PHP file
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function () {
            toastr.info("Uploading...");
        },
        success: function (response) {
            try {
                // pi_edit(master_id);              
                $(".upload-label-"+attachmentId)
                .html('<i class="mdi mdi-upload" style="font-size: 18px"></i> Upload')
                .prop("disabled", false)
                .css("cursor", "pointer");

                // alert(".preview-image_"+attachmentId);
                let res = JSON.parse(response);
                if (res.status === 'success') {
                    toastr.success(res.message || "File uploaded.");
                    
                    // Preview image if image file
                    if (fileData.type.startsWith("image/")) {
                        $(input).siblings(".preview-image_"+attachmentId).attr("src", URL.createObjectURL(fileData));
                    }

                pi_edit(orderID, 1, 0, "", "attach");                    
                
                } else {
                    toastr.error(res.message || "Upload failed.");
                }
            } catch (e) {
                toastr.error("Invalid server response.");
            }
        },
        error: function () {
            toastr.error("Error uploading file.");
        }
    });
}
    </script>