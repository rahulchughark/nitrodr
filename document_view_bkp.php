<?php include('includes/include.php'); 
$zone_id = $_POST['id'];


$query = "
    -- New flow: from attachments table
    SELECT 
        lza.file_name, 
        lza.path, 
        lz.document_category, 
        lza.type
    FROM learning_zone_attachment lza
    INNER JOIN learning_zone lz 
        ON lza.zone_id = lz.id
    WHERE lza.zone_id = '".$zone_id."'
    AND deleted = 0

    UNION

    -- Old flow: from master table
    SELECT 
        '' as file_name, 
        lz.document as path, 
        lz.document_category, 
        lz.type
    FROM learning_zone lz
    WHERE lz.id = '".$zone_id."' 
      AND lz.document IS NOT NULL 
      AND lz.document != ''
";

$attachments = db_query($query);


?>


<style>

    #viewDoc .modal-dialog {
        max-width: 900px;
        height: calc(100vh - 40px);
        padding: 0;
    }

    .responsive-embed.pdf {
        padding-bottom: 141.42%; /* approx A4 ratio */
    }

    iframe {
        width: 100%;
        height: 500px;
    }

    /* Video default 16:9 */
    .responsive-media.video {
    padding-bottom: 56.25%; /* 16:9 */
    height: 0;
    }
    .responsive-media.video video {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    }

    .responsive-media.image img {
        max-width: 100%;
    }

    .back-btn {
        position: absolute;
        left: 35px;
        top: 25px;
    }

    .back-btn svg {
        height: 26px;
        color: #000;
    }
    
</style>

<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <span class="back-btn cursor-pointer" id="backToTable" style="display: none;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7.82843 10.9999H20V12.9999H7.82843L13.1924 18.3638L11.7782 19.778L4 11.9999L11.7782 4.22168L13.1924 5.63589L7.82843 10.9999Z"></path></svg>
            </span>
			<h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Document View</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
		</div>
		<div class="modal-body">
            <div id="docTable">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Document Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                                    <?php 
                                        if (mysqli_num_rows($attachments) > 0) {
                                            while ($row = mysqli_fetch_assoc($attachments)) {
                                                echo "<tr>
                                                        <td>".htmlspecialchars($row['document_category'])."</td>
                                                        <td>".htmlspecialchars($row['type'])."</td>
                                                        <td>".(!empty($row['file_name']) ? htmlspecialchars($row['file_name']) : basename($row['path']))."</td>
                                                        <td>
                                                            <a href='#' 
                                                                class='btn btn-primary px-2 py-1 viewDoc' 
                                                                data-path='".$row['path']."'>
                                                                <span class='mdi mdi-eye'></span>
                                                            </a>
                                                        </td>
                                                    </tr>";
                                            }
                                        } else {
                                            echo "<tr>
                                                    <td colspan='4' class='text-center text-muted'>No Record Found</td>
                                                </tr>";
                                        }
                                    ?>                      
                                </tbody>
                    </table>
                </div>
            </div>
            <!-- Viewer Area -->
            <div id="docViewer" class="mt-3" style="display:none;">
                <div id="docContent" class="text-center"></div>
            </div>
        </div>
    </div>
</div>

<script>
const btnViewCls = ".viewDoc";
const btnBackSel = "#backToTable";

document.addEventListener("click", function(e) {
    // Always fetch elements fresh (in case modal reloaded)
    const docTable   = document.getElementById("docTable");
    const docViewer  = document.getElementById("docViewer");
    const docContent = document.getElementById("docContent");
    const btnBack    = document.querySelector(btnBackSel);

    // Open document in viewer
    if (e.target.closest(btnViewCls)) {
        e.preventDefault();
        let path = e.target.closest(btnViewCls).dataset.path;
        let ext  = path.split('.').pop().toLowerCase();
        let content = "";

        if (ext === "pdf") {
            content = `<iframe src="${path}" width="100%" height="500"></iframe>`;
        } 
        else if (["jpg","jpeg","png","gif","webp"].includes(ext)) {
            content = `<img src="${path}" class="img-fluid" alt="Document">`;
        } 
        else if (["mp4","webm","ogg"].includes(ext)) {
            content = `<video controls width="100%" height="500"><source src="${path}" type="video/${ext}"></video>`;
        } 
        else {
            content = `<iframe src="https://docs.google.com/viewer?url=${encodeURIComponent(path)}&embedded=true" width="100%" height="500"></iframe>`;
        }

        docContent.innerHTML   = content;
        docTable.style.display = "none";
        docViewer.style.display = "block";
        if (btnBack) btnBack.style.display = "inline-block"; // show back button
    }

    // Back button
    if (e.target.closest(btnBackSel)) {
        e.preventDefault();
        docViewer.style.display  = "none";
        docTable.style.display   = "block";
        docContent.innerHTML     = "";
        if (btnBack) btnBack.style.display = "none"; // hide again
    }
});

// Reset modal each time it closes
$('#viewDoc').on('hidden.bs.modal', function () {
    const docTable   = document.getElementById("docTable");
    const docViewer  = document.getElementById("docViewer");
    const docContent = document.getElementById("docContent");
    const btnBack    = document.querySelector("#backToTable");

    if (docTable && docViewer && docContent) {
        docViewer.style.display  = "none";
        docTable.style.display   = "block";
        docContent.innerHTML     = "";
        if (btnBack) btnBack.style.display = "none";
    }
});
</script>