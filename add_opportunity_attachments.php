<?php include("includes/include.php");

$_POST['pid'] = intval($_POST['pid']);

?>
<div class="modal-dialog modal-lg modal-dialog-centered">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title align-self-center mt-0">
                Add</b>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <div class="modal-body">
                <form action="#" method="post" class="form p-t-20" enctype="multipart/form-data">
                <input type="hidden" name="lead_id" id="lead_id" value="<?= $_POST['pid'] ?>">
                <input type="hidden" name="product_id" id="product_id" value="<?= $_POST['product'] ?>">
                <input type="hidden" name="tbl_lead_opportunity_id" id="tbl_lead_opportunity_id" value="<?= $_POST['tbl_lead_opportunity_id'] ?>">

                    <div class="custom-tabs">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="active" id="po-tab" data-toggle="tab" href="#po" role="tab" aria-controls="po" aria-selected="true">PO</a>
                            </li>
                            <!-- <li class="nav-item" role="presentation">
                                <a id="pi-tab" data-toggle="tab" href="#pi" role="tab" aria-controls="pi" aria-selected="false">PI</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a id="invoice-tab" data-toggle="tab" href="#invoice" role="tab" aria-controls="invoice" aria-selected="false">Invoice</a>
                            </li> -->
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="po" role="tabpanel" aria-labelledby="po-tab">                                
                                <div class="upload-container">
                                    <div class="drop-area">
                                        <div class="preview"></div>
                                        <p>Drag & Drop files here or <strong>click</strong> to upload</p>
                                        <input type="file" name="po_attachments[]"  id="fileElm" class="fileElem" multiple accept=".jpg,.png,.pdf,.doc,.docx,.xls,.xlsx" hidden>
                                        <label class="add-file-btn" for="fileElm">Add Files</label>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pi" role="tabpanel" aria-labelledby="pi-tab">
                                <div class="upload-container">
                                    <div class="drop-area">
                                        <div class="preview"></div>
                                        <p>Drag & Drop files here or <strong>click</strong> to upload</p>
                                        <input name="pi_attachments[]" type="file" id="fileElm2" class="fileElem" multiple accept=".jpg,.png,.pdf,.doc,.docx,.xls,.xlsx" hidden>
                                        <label class="add-file-btn" for="fileElm2">Add Files</label>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="invoice" role="tabpanel" aria-labelledby="invoice-tab">
                                <div class="upload-container">
                                    <div class="drop-area">
                                        <div class="preview"></div>
                                        <p>Drag & Drop files here or <strong>click</strong> to upload</p>
                                        <input type="file" name="invoice_attachments[]"  id="fileElm3" class="fileElem" multiple accept=".jpg,.png,.pdf,.doc,.docx,.xls,.xlsx" hidden>
                                        <label class="add-file-btn" for="fileElm3">Add Files</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 text-center">
                        <button type="submit" id="saveAttachments" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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