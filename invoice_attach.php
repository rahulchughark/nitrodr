<?php
include('includes/include.php');
include_once('helpers/DataController.php');

$orderID = (int)$_POST['order_id'];
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
$product_result = $dataObj->fetchOrderProducts($orderID,$is_group,$group_name);

$countData = db_num_array($product_result);

$getEMIs = $dataObj->getInvoiceEmiByOrderId($orderID);

// Fetch PI attachments for the current order/lead
$pi_sql = "SELECT id, name, amount, attachment_name 
           FROM opportunity_attachments 
           WHERE lead_id = $orderID 
           AND attachment_type = 'pi_attachments' 
           AND status = 1";
$pi_result = db_query($pi_sql);
?>


<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
			    <h5 class="modal-title align-self-center mt-0" id="uploadModalLabel">Invoice Attach</h5>
			    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
		    </div>


    <div class="modal-body">
      <form id="billingForm">
        <input type="hidden" name="order_id" value="<?= $orderID ?>">
        
        <!-- Repeated static sections -->
        <?php
        while ($data = db_fetch_array($product_result)) {
        ?>

        <div class="row align-items-center mb-2">
          <div class="col">
            <p class="mb-0">
              <?= $data['product_mst_name'] ?> (<?= $data['sub_product'] ?>)</p>
          </div>
          <div class="col-auto">
          
            <button type="button" onclick="return manageClickedItemIDs(<?= $data['lead_product_id'] ?>,<?= $data['product']?>)" class="btn btn-primary btn-xs px-2"
                    data-toggle="modal" data-target="#uploadModal">
              <span class="mdi mdi-plus"></span>
            </button>
            
          </div>
        </div>

        <?php } ?>


        
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background:#7e7e7e">
            <form id="uploadForm" enctype="multipart/form-data">
              <input type="hidden" id="order_id_attach" name="order_id_attach">
              <input type="hidden" id="main_product_id" name="main_product_id">
              <input type="hidden" id="sub_product_id" name="sub_product_id">
                <div class="modal-header">
                    <h5 class="modal-title align-self-center mt-0">Upload File</h5>
                    <button type="button" class="close" aria-label="Close" onclick="$(this).closest('.modal').modal('hide')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body py-4" style="min-height: auto">
                    <div class="row mb-3">
                        <div class="col-md-12 mb-3">
                            <label class="text-white">PI (Link To)</label>
                            <select name="parent_pi_id" class="form-control">
                                <option value="">---Select---</option>
                                <?php 
                                mysqli_data_seek($pi_result, 0); // Reset result pointer
                                while($pi_row = db_fetch_array($pi_result)) { 
                                    $pi_label = (!empty($pi_row['name']) && !empty($pi_row['amount'])) 
                                                ? $pi_row['name'] . " (₹" . $pi_row['amount'] . ")" 
                                                : $pi_row['attachment_name'];
                                ?>
                                    <option value="<?= $pi_row['id'] ?>"><?= htmlspecialchars($pi_label) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="text-white">Invoice Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Name">
                        </div>
                        <div class="col-md-6">
                            <label class="text-white">Invoice Amount</label>
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="Amount">
                        </div>
                    </div>

                    <div class="upload-container">
                        <div class="drop-area">
                            <div class="preview"></div>
                            <p>Drag & Drop files here or <strong>click</strong> to upload</p>
                            <input type="file" name="po_attachments[]" id="fileElm" class="fileElem" multiple accept=".jpg,.png,.pdf,.doc,.docx,.xls,.xlsx" hidden>
                            <label class="add-file-btn" for="fileElm">Add Files</label>
                        </div>
                    </div>
                    <div class="text-center mt-3 mb-3">
                        <button type="submit" class="btn btn-primary upload-file-submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>






<script>


  $(document).on('click', '.add-row', function () {
    var newRow = $('#rowTemplate').html();
    $('#amountRows').append(newRow);
});

$(document).on('click', '.remove-row', function () {
    $(this).closest('.amount-row').remove();
});
</script>



<script>

    $('#uploadModal').on('show.bs.modal', function () {
    $('#billingModal').modal('hide'); // close the first one before showing second
  });

$(document).ready(function () {
    $("#uploadForm").on("submit", function (e) {
        e.preventDefault();
        $(".upload-file-submit").html("uploading...").prop("disabled", true).css("cursor", "not-allowed");

        let formData = new FormData(this);
     

        $.ajax({
            url: "invoice_attach_submit.php", // PHP file to handle upload
            type: "POST",
            data: formData,
            dataType: 'json',
            processData: false, // Important!
            contentType: false, // Important!
            success: function (response) {
            $(".upload-file-submit").html("Save").prop("disabled", false).css("cursor", "pointer");
            $("#myModal1").modal('hide');

              if (response.status === 'success') {           
                toastr.success(response.message);
                $('#leads').DataTable().ajax.reload(null, false);
              } else {
                toastr.error(response.message || "Something went wrong.");
              }
                $("#uploadModal").modal("hide"); // Close modal
                $("#uploadForm")[0].reset(); // Reset form
            },
            error: function () {
              console.error("AJAX error:", error);
              toastr.error("Failed to save remark. Try again.");
                // alert("File upload failed. Please try again.");
            }
        });
        
    });
});


function manageClickedItemIDs(p_id,s_p_id){

  $("#order_id_attach").val(<?= $orderID ?>);
  $("#main_product_id").val(p_id);
  $("#sub_product_id").val(s_p_id);
}


function showAttachedFiles(orderID, p_id, s_p_id) {
    $.ajax({
        url: "pi_attached_files_view.php",
        type: "POST",
        data: {
            order_id: orderID,
            main_product: p_id,
            sub_product_id: s_p_id
        },
       dataType: 'json',
        success: function (response) {
            // Example: Populate modal content dynamically
            
              $("#piAttachmentList").html(response.html);
              console.log("response",response.html)
                // $('#viewAttachmentsModal .modal-body').html(response.html);
                // $('#viewAttachmentsModal').modal('show'); // Show attachment modal
            
        },
        error: function (xhr, status, error) {
            console.error("AJAX error:", error);
            toastr.error("Failed to load attachments. Try again.");
        }
    });
}

</script>

<script>
    $(document).ready(function () {
        $(".upload-container").each(function () {
            let container = $(this);
            let dropArea = container.find(".drop-area");
            let fileInput = container.find(".fileElem");
            let preview = container.find(".preview");

            dropArea.on("dragover", function (e) {
                e.preventDefault();
                dropArea.addClass("highlight");
            });

            dropArea.on("dragleave", function () {
                dropArea.removeClass("highlight");
            });

            dropArea.on("drop", function (e) {
                e.preventDefault();
                dropArea.removeClass("highlight");
                handleFiles(e.originalEvent.dataTransfer.files, preview);
            });

            dropArea.on("click", function () {
                fileInput.trigger("click");
            });

            // dropArea.on("click", function (e) {
            //     if (e.target !== fileInput[0]) {
            //         fileInput.trigger("click");
            //     }
            // });

            fileInput.on("change", function () {
                handleFiles(this.files, preview);
            });

            function handleFiles(files, preview) {
                for (let file of files) {
                    let fileType = file.type;
                    let fileName = file.name;

                    if (fileType.startsWith("image/")) {
                        let reader = new FileReader();
                        reader.onload = function (e) {
                            let img = $("<img>").addClass("preview-img").attr("src", e.target.result);
                            let tooltip = $("<div>").addClass("tooltip").text(fileName);
                            let fileItem = $("<div>").addClass("file-item").append(img).append(tooltip);
                            let deleteBtn = $("<button>").addClass("delete-btn").text("x");
                            fileItem.append(deleteBtn);

                            deleteBtn.on("click", function () {
                                fileItem.remove();
                            });

                            preview.append(fileItem);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        let fileIcon = getFileIcon(fileName);
                        let tooltip = $("<div>").addClass("tooltip").text(fileName);
                        let fileItem = $("<div>").addClass("file-item").html(fileIcon).append(tooltip);
                        let deleteBtn = $("<button>").addClass("delete-btn").text("x");
                        fileItem.append(deleteBtn);

                        deleteBtn.on("click", function () {
                            fileItem.remove();
                        });

                        preview.append(fileItem);
                    }
                }
            }

            function getFileIcon(fileName) {
                let ext = fileName.split('.').pop().toLowerCase();
                let iconSrc = "default-file.png"; // Default icon for unknown types

                if (ext === "pdf") {
                    iconSrc = "pdf-icon.png";
                } else if (ext === "doc" || ext === "docx") {
                    iconSrc = "word-icon.png";
                } else if (ext === "xls" || ext === "xlsx") {
                    iconSrc = "excel-icon.png";
                }

                return `<img src="images/${iconSrc}" class="file-icon" alt="${ext} file">`;
            }
        });
    });
</script>