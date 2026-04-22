<?php include("includes/include.php"); 
include_once('helpers/DataController.php');
$dataObj = new DataController;

if ($_REQUEST['edit_id']) {
    $sql = db_query("select * FROM learning_zone_attachment where status=1 and id=".$_REQUEST['edit_id']);
    $row = db_fetch_array($sql);
    @extract($row);
} 

// print_r($partners);die;
?>



<div id="replaceFileModal" class="modal-dialog modal-dialog-centered modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Replace File:</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">

      <form method="post" class="form p-t-20" enctype="multipart/form-data">
        <input type="hidden" name="eid" class="form-control" value="<?= $_REQUEST['edit_id'] ?>">

        <div class="form-group">
            <label for="existing_file" class="d-block mb-2">View Existing File</label>
            <div class="border rounded p-3 bg-light">
                <div class="mb-2"><strong>Type:</strong> <?= htmlspecialchars($type) ?></div>
                <div class="mb-2"><strong>File Name:</strong> <?= htmlspecialchars($file_name) ?></div>
                <div><strong>View Path:</strong> <a target="_blank" href="<?= htmlspecialchars($path) ?>">View File</a></div>
                <div><strong>Last Updated:</strong> <?= date('d F Y h:i:s A', strtotime($updated_at)) ?></div>
            </div>
        </div>

        <div class="form-group">
          <label>Select File Type</label>
          <div class="mb-3">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="file_type" id="doc_type" value="doc" <?=  $type == "DOC" ? "checked" : '' ?> onchange="changeFileAccept()">
              <label class="form-check-label" for="doc_type">DOC</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="file_type" id="video_type" value="video" <?=  $type == "VIDEO" ? "checked" : '' ?> onchange="changeFileAccept()">
              <label class="form-check-label" for="video_type">Video</label>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="upload_file">Upload New File</label>
          <div class="upload-container">
            <div class="drop-area">
                <div class="preview"></div>
                  <p>Drag &amp; Drop files here or <strong>click</strong> to upload</p>
                  <input name="attachments[]" type="file" id="upload_file" class="fileElem" multiple="" hidden="">
                  <label class="add-file-btn" for="upload_file">Add Files</label>
              </div>
          </div>
        </div>

        <div id="footer-buttons">
          <div class="modal-footer justify-content-center border-0 pb-0">
            <input type="submit" disabled name="edit_data" value="Save" class="btn btn-primary replace-submit-btn" />
          </div>
        </div>
      </form>

    </div>
  </div>
</div>



<script>
  var regex = new RegExp("(.*?)\.(csv)$");

  function triggerValidation(el) {
    if (!(regex.test(el.value.toLowerCase()))) {
      el.value = '';
      alert('Please select correct file format');
    }
  }
</script>

<script>

    $(document).ready(function() {
        $('.multiselect_partner').multiselect({
            buttonWidth: '100%',
            includeSelectAllOption: true,
            nonSelectedText: 'Select Partner',
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            includeFilterClearBtn:true
        });

    $('.multiselect_user_type').multiselect({
        buttonWidth: '100%',
        includeSelectAllOption: true,
        nonSelectedText: 'Select User Type',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        includeFilterClearBtn:true
    });
  });

  $(document).ready(function() {
    $('#upload_file').on('change', function() {
        if ($(this).val()) {
            $('.replace-submit-btn').prop('disabled', false);
        } else {
            $('.replace-submit-btn').prop('disabled', true);
        }
    });
});


  $(document).ready(function() {
    $('.form').on('submit', function(e) {
        $('.replace-submit-btn').prop('disabled', true).text("replacing...").css('cursor','not-allowed');
        e.preventDefault(); // Prevent default form submission

        var formData = new FormData(this); // Create form data with file included
        formData.append('type', 'replace_learning_zone');

        $.ajax({
            url: 'ajax_learning_zone_upload.php', // Replace with your actual PHP handler URL
            type: 'POST',
            data: formData,
            contentType: false,       // Important to include for file upload
            processData: false,       // Important to include for file upload
            success: function(response) {
              $('.replace-submit-btn').prop('disabled', false).text("Save");
                // Handle the response from PHP (e.g., success message, errors)
                // alert('Response: ' + response);
                $('#replaceFileModal').closest('.modal').modal('hide');
                toastr.success("Success","File replaced successfully!");
                location.reload();
            },
            error: function(xhr, status, error) {
                // alert('An error occurred: ' + error);

                 toastr.error("Error","File uploaded successfully!");
            }
        });
    });
});
</script>

<script>
  changeFileAccept();
  function changeFileAccept() {   
    const docType = document.getElementById('doc_type');
    const videoType = document.getElementById('video_type');
    const uploadFile = document.getElementById('upload_file');

    if(docType.checked) {
      // uploadFile.setAttribute('accept', '.doc,.docx,.jpg,.jpeg,.png,.gif,.bmp,.webp,image/*');
      uploadFile.setAttribute(
      'accept',
      '.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.bmp,.webp,image/*'
    );
    } else if(videoType.checked) {
      uploadFile.setAttribute('accept', '.mp4,video/mp4');
    }
    
    // Clear the selected file when switching types
    uploadFile.value = '';
  }


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
