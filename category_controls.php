<?php

include('includes/header.php');
include_once('helpers/DataController.php');
$dataObj = new DataController;


?>

<style>

    .admin-control-main {
        height: calc(100vh - 230px);
        overflow-x: auto;
    }
    .file-tree li.folder .fi {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .file-tree li.folder .fi:before {
        top: 13px;
    }
    .file-tree li.folder .fi:after {
        top: 14px;
        font-size: 20px;
    }
    
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="media bredcrum-title">
                                        <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">
                                            <small class="text-muted">Home >Admin Controls</small>
                                            <h4 class="font-size-14 m-0 mt-1">Admin Controls</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="admin-control-main">
                                       <?=
                                       $dataObj->getAllCategoriesTree2();
                                       ?>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<?php include 'includes/footer.php'?>
<script>
document.addEventListener("click", function (e) {

    const uploadBtn = e.target.closest(".uploadDocBtn");
    if (uploadBtn) {
        const id   = uploadBtn.dataset.categoryId || 0;
        const name = uploadBtn.dataset.categoryName || "";

        e.preventDefault();
        e.stopPropagation(); // stop folder toggle
        uploadDocument(uploadBtn, id, name);
        return;
    }

    /* =========================
       Folder Open / Close
    ========================== */
    const folder = e.target.closest(".file-tree .folder");
    if (!folder) return;

    const subList = folder.querySelector(":scope > ul");
    if (!subList) return;

    // Close siblings
    const parentList = folder.parentElement;
    parentList.querySelectorAll(":scope > .folder").forEach(f => {
        if (f === folder) return;
        f.classList.remove("active");
        const ul = f.querySelector(":scope > ul");
        if (ul) ul.style.display = "none";
    });

    // Toggle current
    const isOpen = subList.style.display === "block";
    subList.style.display = isOpen ? "none" : "block";
    folder.classList.toggle("active", !isOpen);
});

/* =========================
   AJAX Modal Function
========================= */
function uploadDocument(btn, id, name) {
    $.ajax({
        type: "POST",
        data: {
            id: id,
            name: name
        },
        url: "upload_cat_document.php",
        success: function (response) {
            $("#myModal").html(response);
            $("#myModal").modal("show");
        }
    });
}
</script>

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
 $('#next-btn').click(() => {
    $('#upload_documents').hide();
    $('#manage_partner_access, #footer-buttons').show();
  });

  $('#back-btn').click(() => {
    $('#manage_partner_access, #footer-buttons').hide();
    $('#upload_documents').show();
  });

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
</script>
<script>
    $(document).on("change", "#myModal .fileElem", function () {
        // console.log("hey hello");
        
        $(".upload-container").each(function () {
            // console.log("hello");
            
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
<script>
//   document.getElementById('type').addEventListener('change', function () {
//     const fileInput = document.getElementById('attachment');
//     const selectedType = this.value;
//     // alert(selectedType)

//     if (selectedType === 'Video') {
//       fileInput.setAttribute('accept', 'video/*');
//     } else {
//       fileInput.setAttribute('accept', '.xlsx,.xls,image/*,.doc,.docx,.ppt,.pptx,.txt,.pdf');
//     }
//   });


// document.addEventListener("click", function (e) {
//     const folder = e.target.closest(".file-tree .folder");
//     if (!folder) return;

//     // Check if the click is inside the last nested <ul>
//     const lastSubUl = folder.querySelector("ul:last-child");
//     if (lastSubUl && lastSubUl.contains(e.target)) {
//         // Click inside last sub <ul>, do nothing
//         return;
//     }

//     // Close sibling folders at the same level
//     const parentList = folder.parentElement;
//     parentList.querySelectorAll(":scope > .folder").forEach(f => {
//         if (f !== folder) {
//             f.classList.remove("active");
//             const sub = f.querySelector(":scope > ul");
//             if (sub) sub.style.display = "none";
//         }
//     });

//     // Toggle the clicked folder
//     const subList = folder.querySelector(":scope > ul");
//     if (subList) {
//         const isOpen = subList.style.display === "block";
//         subList.style.display = isOpen ? "none" : "block";
//         folder.classList.toggle("active", !isOpen);
//     }
// });


$(document).off("submit", ".upload-form").on("submit", ".upload-form", function(e) {
    e.preventDefault();

    let form = this;
    let formData = new FormData(form);
  
    $.ajax({
        url: $(form).attr("action"),
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        beforeSend: function() {
            $(form).find("button[type=submit]").prop("disabled", true).text("Uploading...");
        },
        success: function(response) {
            if (response.status === "success") {
                toastr.success(response.message || "File uploaded successfully!");
                form.reset(); // clear form
                // $(".user_type_permissions_btn").prop("disabled",true);
                // location.reload();
                setTimeout(function () {
                    window.location.href = "product_document.php";
                }, 2000); // 2 seconds
              

            } else {
                toastr.error(response.message || "Upload failed");
            }
            $('#documents').DataTable().ajax.reload(null, false);
            // location.reload();
            $(".user_type_permissions_btn").prop("disabled", true);
            $('#myModal').modal('hide');
        },
        error: function() {
            toastr.error("Error: Something went wrong");
        },
        complete: function() {
            $(form).find("button[type=submit]").prop("disabled", false).text("Upload");
        }
    });
});



// $(document).off("change", ".fileElem").on("change", ".fileElem", function (e) {
//     console.log("uploader 22");
//     uploadFiles(this.files, this);
// });
$(document).on("change", ".fileElem", function () {
    // console.log("fileElem change fired");
    uploadFiles(this.files, this);
});

// Drag over
$(document).off("dragover", ".drop-area").on("dragover", ".drop-area", function (e) {
    e.preventDefault();
    $(this).addClass("highlight");
});

// Drag leave
$(document).off("dragleave", ".drop-area").on("dragleave", ".drop-area", function (e) {
    e.preventDefault();
    $(this).removeClass("highlight");
});

// Drop
$(document).off("drop", ".drop-area").on("drop", ".drop-area", function (e) {
    e.preventDefault();
    $(this).removeClass("highlight");

    let files = e.originalEvent.dataTransfer.files;
    let input = $(this).find(".fileElem")[0]; // hidden file input
    
    uploadFiles(files, input);
});


let selectedFileType = $("input[name='file_type']:checked").val() || 'DOC';

// On change event for radio buttons
$(document).on("change", "input[name='file_type']", function () {
    selectedFileType = $(this).val();
    // console.log("Selected File Type:", selectedFileType);
});


function uploadFiles(files, input) {

    
    $(".tracker-uploader").removeClass("d-none");
    let formData = new FormData();
    let invalidFiles = [];
    let oversizedFiles = [];

    let selectedType = selectedFileType;
    // let docExts = ["jpg", "jpeg", "png", "pdf", "doc", "docx", "xls", "xlsx"];
    let docExts = ["jpg", "jpeg", "png", "pdf", "doc", "docx", "xls", "xlsx", "ppt", "pptx"];
    let videoExts = ["mp4", "avi", "mov", "mkv"];

    // Maximum file count validation - 15 files limit
    const MAX_FILES_COUNT = 15;
    
    // Maximum file size validation - 1 GB
    const MAX_FILE_SIZE = 1 * 1024 * 1024 * 1024; // 1 GB in bytes
    const MAX_TOTAL_SIZE = 1 * 1024 * 1024 * 1024; // 1 GB total
    let totalSize = 0;
    let validFilesCount = 0;


    // Check if number of files exceeds limit
    if (files.length > MAX_FILES_COUNT) {
        toastr.error("You can upload a maximum of " + MAX_FILES_COUNT + " files at once. You selected " + files.length + " files.");
        $(input).val("");
        $(input).closest(".drop-area").find(".preview").empty();
        $(".tracker-uploader").addClass("d-none");
        setTimeout(function () {
        window.location.reload();
        }, 2000);
        return;
    }
    

    $.each(files, function (i, file) {
        let ext = file.name.split(".").pop().toLowerCase();
        
        // Check individual file size - 1 GB limit
        if (file.size > MAX_FILE_SIZE) {
            oversizedFiles.push(file.name + ' (' + formatFileSize(file.size) + ')');
            return true; // skip this file
        }
        
        totalSize += file.size;

        if (selectedType == "DOC" && !docExts.includes(ext)) {
            invalidFiles.push(file.name);
        } else if (selectedType == "VIDEO" && !videoExts.includes(ext)) {
            invalidFiles.push(file.name);
        } else {
            formData.append("attachments[]", file);
            validFilesCount++;
        }
    });
    
    // Check for oversized individual files
    if (oversizedFiles.length > 0) {
        toastr.error("Files exceed 1 GB limit: " + oversizedFiles.join(", ") + ". Max size per file: 1 GB");
        $(input).val("");
        $(input).closest(".drop-area").find(".preview").empty();
        $(".tracker-uploader").addClass("d-none");
        return;
    }
    
    // Check total upload size
    if (totalSize > MAX_TOTAL_SIZE) {
        toastr.error("Total file size (" + formatFileSize(totalSize) + ") exceeds 1 GB limit. Please upload smaller files or fewer files at once.");
        $(input).val("");
        $(input).closest(".drop-area").find(".preview").empty();
        $(".tracker-uploader").addClass("d-none");
        return;
    }

    if(invalidFiles.length > 0) {
        toastr.error("Please upload valid " + selectedType + " files only");
        $(input).val("");
        $(input).closest(".drop-area").find(".preview").empty();
        $(".tracker-uploader").addClass("d-none");
        return;
    }

    if (formData.has("attachments[]")) {

        $(".final-uploader").prop("disabled", true).text("Uploading...");
        $(".nextBtnFileUploader").prop("disabled", true);
        let uploadStartTime = Date.now();
        let processingMessageShown = false;
        
        $.ajax({
            url: "learning_zone_temp_upload.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            timeout: 600000, // 10 minutes for large files
            
            xhr: function () {
                let xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        let percentComplete = Math.round((evt.loaded / evt.total) * 100);
                        let uploaded = formatFileSize(evt.loaded);
                        let total = formatFileSize(evt.total);
                        
                        // Show upload progress with file count
                        if (validFilesCount > 1) {
                            $(".upload-progress").text(percentComplete + "% - Uploading " + validFilesCount + " files (" + uploaded + " / " + total + ")");
                        } else {
                            $(".upload-progress").text(percentComplete + "% (" + uploaded + " / " + total + ")");
                        }
                        $(".upload-progress-bar").css("width", percentComplete + "%");
                        
                        // Show processing message when upload completes
                        if (percentComplete === 100 && !processingMessageShown) {
                            processingMessageShown = true;
                            if (validFilesCount > 1) {
                                $(".upload-progress").text("Processing " + validFilesCount + " files on server... Please wait.");
                            } else {
                                $(".upload-progress").text("Processing file on server... Please wait.");
                            }
                            
                            // Start animated dots for processing
                            let dots = 0;
                            let processingInterval = setInterval(function() {
                                dots = (dots + 1) % 4;
                                let dotText = '.'.repeat(dots);
                                if (validFilesCount > 1) {
                                    $(".upload-progress").text("Processing " + validFilesCount + " files on server" + dotText);
                                } else {
                                    $(".upload-progress").text("Processing file on server" + dotText);
                                }
                            }, 500);
                            
                            // Store interval ID to clear it later
                            xhr.processingInterval = processingInterval;
                        }
                    }
                }, false);
                return xhr;
            },
            
            beforeSend: function() {
                if (validFilesCount > 1) {
                    // console.log("Starting upload... " + validFilesCount + " files, Total size: " + formatFileSize(totalSize));
                    $(".upload-progress").text("0% - Preparing to upload " + validFilesCount + " files...");
                } else {
                    // console.log("Starting upload... Total size: " + formatFileSize(totalSize));
                    $(".upload-progress").text("0% Uploading...");
                }
                $(".upload-progress-bar").css("width", "0%");
            },
            
            success: function (response, textStatus, xhr) {
                // Clear processing animation
                if (xhr.processingInterval) {
                    clearInterval(xhr.processingInterval);
                }
                
                $(".tracker-uploader").addClass("d-none");
                
                if (response.status === "success") {
                    
                    let uploadDuration = ((Date.now() - uploadStartTime) / 1000).toFixed(1);
                    
                    response.files.forEach(function (path) {
                        let preview = `
                            <div class="uploaded-file">
                                <span>${path.split('/').pop()}</span>
                                <input type="hidden" name="uploaded_files[]" value="${path}">
                            </div>`;
                        $(input).closest(".drop-area").find(".preview").append(preview);
                    });
                    $(".nextBtnFileUploader").prop("disabled", false);
                    $(input).closest("form.upload-form").submit();
                    
                    
                    if (validFilesCount > 1) {
                        toastr.success(validFilesCount + " files uploaded successfully in " + uploadDuration + "s!");
                    } else {
                        toastr.success("File uploaded successfully in " + uploadDuration + "s!");
                    }
                    // $(".upload-form").submit();
                } else {
                    toastr.error(response.message || "Upload failed");
                }

                $(".upload-progress").text("");
                $(".upload-progress-bar").css("width", "0%");
            },
            
            error: function(xhr, status, error) {
                $(".final-uploader").prop("disabled", false).text("Upload");
                // Clear processing animation
                if (xhr.processingInterval) {
                    clearInterval(xhr.processingInterval);
                }
                
                $(".tracker-uploader").addClass("d-none");
                
                // console.error("Upload Error:", {
                //     status: status, 
                //     error: error, 
                //     responseText: xhr.responseText ? xhr.responseText.substring(0, 500) : null,
                //     httpStatus: xhr.status
                // });
                
                // Check if response is HTML error (PHP limit exceeded)
                if (xhr.responseText && xhr.responseText.includes("POST Content-Length")) {
                    let match = xhr.responseText.match(/(\d+) bytes exceeds the limit of (\d+) bytes/);
                    if (match) {
                        let fileSize = formatFileSize(parseInt(match[1]));
                        let limit = formatFileSize(parseInt(match[2]));
                        toastr.error(`File size (${fileSize}) exceeds server limit (${limit}). Max allowed: 1 GB. Please increase PHP limits or upload smaller files.`);
                    } else {
                        toastr.error("File size exceeds server limit. Maximum: 1 GB");
                    }
                } else if (status === "timeout") {
                    if (validFilesCount > 1) {
                        toastr.error("Upload timeout. " + validFilesCount + " files may be too large. Try uploading fewer files.");
                    } else {
                        toastr.error("Upload timeout. File may be too large. Please try again.");
                    }
                } else if (xhr.status === 413) {
                    toastr.error("Files too large. Maximum size: 1 GB");
                } else if (xhr.status === 500) {
                    toastr.error("Server error while processing files. Please contact support.");
                } else if (status === "parsererror") {
                    toastr.error("Server returned invalid response. Files may exceed 1 GB limit or server configuration issue.");
                } else {
                    toastr.error("Upload failed: " + (error || "Unknown error"));
                }
                
                $(input).val("");
                $(input).closest(".drop-area").find(".preview").empty();
                $(".upload-progress").text("");
                $(".upload-progress-bar").css("width", "0%");
            },
            
            complete: function(xhr) {
                // Clear processing animation if still running
                if (xhr && xhr.processingInterval) {
                    clearInterval(xhr.processingInterval);
                }
                
                // ALWAYS hide loader when request completes
                $(".tracker-uploader").addClass("d-none");
                // console.log("Upload request completed");
            }
        });
    } else {
        $(".tracker-uploader").addClass("d-none");
        toastr.info("No valid files to upload");
    }
}

// Helper function to format file sizes
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}


 

$(document).ready(function() {
    
    function validatePartnerAccess() {
        // Get the actual select element values
        var partnerSelected = $('select[name="partner[]"] option:selected').length;
        var userTypeSelected = $('select[name="user_type[]"] option:selected').length;
        
        
        // console.log('partnerSelected count:', partnerSelected);
        // console.log('userTypeSelected count:', userTypeSelected);
        
        // Enable button only if both have at least one selection
        if (partnerSelected > 0 && userTypeSelected > 0) {
            $('.user_type_permissions_btn').prop('disabled', false);
        } else {
            $('.user_type_permissions_btn').prop('disabled', true);
        }
    }
    
    // Initially disable the button
    $('.user_type_permissions_btn').prop('disabled', true);
    
    // Listen for change events on both selects
    $('select[name="partner[]"]').on('change', function() {
        validatePartnerAccess();
    });
    
    $('select[name="user_type[]"]').on('change', function() {
        validatePartnerAccess();
    });
    
    // Also try listening to the Bootstrap Select wrapper if it exists
    $('.multiselect_partner').on('change', function() {
        validatePartnerAccess();
    });
    
    $('.multiselect_user_type').on('change', function() {
        validatePartnerAccess();
    });
});
</script>