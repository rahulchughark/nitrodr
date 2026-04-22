<?php include("includes/include.php"); ?>

<style>
    .vc-modal.modal-lg {
        max-width: 1000px;
    }
    .vc-download {
    padding: 6px 18px;
    border-radius: 20px;
}
</style>

<?php

$path = $_POST['pdf_path'];

?>

<div class="modal-dialog modal-lg vc-modal" role="document">
    <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header d-flex align-items-center">

            <!-- Title (LEFT) -->
            <h4 class="modal-title mb-0">Visiting Card</h4>

            <!-- Right Actions -->
            <div class="ml-auto d-flex align-items-center gap-1">

                <!-- DOWNLOAD -->
                <button
                    type="button"
                    class="btn btn-success btn-sm vc-download"
                    onclick="downloadVisitingCard('<?= htmlspecialchars($path, ENT_QUOTES) ?>')">
                    Download
                </button>

                <!-- CLOSE -->
                <button type="button" class="close ml-2" data-dismiss="modal">
                    <span>&times;</span>
                </button>

            </div>
        </div>

        <!-- Modal Body -->
        <div class="modal-body p-0" style="height:80vh;">
            <!-- <iframe
                id="visitingCardPdf"
                src=""
                width="100%"
                height="100%"
                style="border:none;">
            </iframe> -->
            <embed 
                id="visitingCardPdf"
                src="<?= $path ?>"
                type="application/pdf"
                width="100%"
                height="100%">
        </div>

    </div>
</div>
<!-- <script>
    $('#visitingCardPdf').attr('src', pdfPath + '#toolbar=0');
</script> -->