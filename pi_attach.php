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


?>


<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
			    <h5 class="modal-title align-self-center mt-0" id="uploadModalLabel">PI Attach  </h5>
			    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
		    </div>


    <div class="modal-body">
      <form id="billingForm">
        <input type="hidden" name="order_id" value="<?= $orderID ?>">
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
      
      <?php if($countData > 0): ?>
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
                      <input type="date" onkeydown="return false;" onkeydown="return false;" name="date[]" value="<?= $value['date'] ?>" class="form-control">
                    </div>
                  </div>
                  <div class="col-auto" style="padding-right: 12px">
                    <label class="invisible d-block">...</label>
                    <?php if ($i == 0): ?>
                        <button type="button" class="btn btn-primary btn-xs add-row px-2">
                            <span class="mdi mdi-plus"></span>
                        </button>
                    <?php else: ?>
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
                  <div class="col-auto" style="padding-right: 12px">
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
<?php endif; ?>
    
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

            <!--  <button onclick="return showAttachedFiles(<?= $orderID ?>,<?= $data['main_product_id'] ?>,<?= $data['product']?>)" title="View Attachment" type="button" class="btn btn-primary btn-xs px-2"
            data-toggle="modal" data-target="#viewAttachmentsModal" >
              <span class="mdi mdi-eye"></span>
            </button> -->


            <button type="button" onclick="return manageClickedItemIDs(<?= $data['lead_product_id'] ?>,<?= $data['product']?>)" class="btn btn-primary btn-xs px-2"
                    data-toggle="modal" data-target="#uploadModal">
              <span class="mdi mdi-plus"></span>
            </button>
          </div>
        </div>

        <?php } ?>

        <!-- More repeated sections (can keep as-is) -->

        <div class="row">
          <div class="col">
            <div class="form-group">
              <label>Remarks</label>
              <textarea name="remarks" rows="5" class="form-control"><?= htmlspecialchars($remark) ?></textarea>
            </div>
          </div>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-primary pi-submit-btn">Submit</button>
        </div>
        
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

                <div class="modal-body py-4">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-white">PI Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter PI Name">
                        </div>
                        <div class="col-md-6">
                            <label class="text-white">PI Amount</label>
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="Enter PI Amount">
                        </div>
                    </div>
                    <div class="upload-container">
                        <div class="drop-area">
                            <div class="preview"></div>
                            <p>Drag & Drop files here or <strong>click</strong> to upload</p>
                            <input name="pi_attachments[]" type="file" id="fileElm2" class="fileElem" multiple accept=".jpg,.png,.pdf,.doc,.docx,.xls,.xlsx" hidden>
                            <label class="add-file-btn" for="fileElm2">Add Files</label>
                        </div>
                      </div>
                </div>

                <div class="text-center mt-3 mb-3">
                    <button type="submit" class="btn btn-primary file-upload-btn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewAttachmentsModal" tabindex="-1" role="dialog" aria-labelledby="viewAttachmentsLabel" aria-hidden="true">
  
<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">View</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <!-- Single PI Tab -->
        <ul class="nav nav-tabs mb-3">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#piTab">PI</a>
          </li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane fade show active" id="piTab">
            <div class="table-responsive">
              <table class="table table-bordered table-striped text-center">
                <thead style="background-color:#f5d3bc;">
                    <tr>
                        <th>S.No</th>
                        <th>File</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="piAttachmentList">
                  
                
                  
                </tbody>
              </table>
            </div>
          </div>
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
    // $(document).on('click', '.add-row', function () {
    //     var newRow = $('.amount-row:first').clone(); // Clone first row
    //     newRow.find('input').val(''); // Clear input values

    //     // Replace "add" button with "remove" in the cloned row
    //     newRow.find('.add-row')
    //         .removeClass('add-row btn-primary')
    //         .addClass('remove-row btn-danger')
    //         .html('<span class="mdi mdi-minus"></span>');

    //     $('#amountRows').append(newRow); // Append to container
    // });

    // $(document).on('click', '.remove-row', function () {
    //     $(this).closest('.amount-row').remove(); // Remove that row
    // });

  $(document).off('click', '.add-row').on('click', '.add-row', function () {
    var newRow = $('#rowTemplate').html();
    $('#amountRows').append(newRow);
  });

  $(document).off('click', '.remove-row').on('click', '.remove-row', function () {
       $(this).closest('.amount-row').remove();
   });

</script>



<script>
  $(document).ready(function () {
    $('#billingForm').on('submit', function (e) {
      e.preventDefault(); // prevent default form submit

      $(".pi-submit-btn")
        .html('<i class="fa fa-spinner fa-spin"></i> Submitting...')
        .prop("disabled", true)
        .css("cursor", "not-allowed"); 
       
     $.ajax({
        url: 'pi_attach_submit.php', // change this to your endpoint
        method: 'POST',
        data: $(this).serialize(), // serializes form data
        dataType: 'json',
        success: function (response) {
           $(".pi-submit-btn")
          .html('Submit')
          .prop("disabled", false).css("cursor", "pointer"); 

          if (response.status === 'success') {
            // alert(response.message); // or show success message on UI
            // $('#billingForm')[0].reset(); // reset form
            toastr.success(response.message);
            $('#leads').DataTable().ajax.reload(null, false);
            $('#myModal1').modal('hide');
            // alert("called");
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


  $('#uploadModal').on('show.bs.modal', function () {
    $('#billingModal').modal('hide'); // close the first one before showing second
  });


$(document).ready(function () {
    $("#uploadForm").on("submit", function (e) {
      

        $(".file-upload-btn")
        .html('<i class="fa fa-spinner fa-spin"></i> Submitting...')
        .prop("disabled", true)
        .css("cursor", "not-allowed"); 


        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "pi_attach_file_upload.php", // PHP file to handle upload
            type: "POST",
            data: formData,
            dataType: 'json',
            processData: false, // Important!
            contentType: false, // Important!
            success: function (response) {
          $(".file-upload-btn")
            .html('Submit')
            .prop("disabled", false).css("cursor", "pointer");

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