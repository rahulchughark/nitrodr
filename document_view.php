<?php include('includes/include.php'); 
include_once('helpers/DataController.php');
$dataObj = new DataController;

$zone_id = $_POST['id'];

// $query = "SELECT * FROM (
//     -- New flow: from attachments table
//     SELECT 
//         lza.file_name, 
//         lza.path, 
//         lz.document_category, 
//         lza.type,
//         lza.created_at,
//         lza.partner_access,
//         lza.users_access
//     FROM learning_zone_attachment lza
//     INNER JOIN learning_zone lz 
//         ON lza.zone_id = lz.id
//     WHERE lza.zone_id = '".$zone_id."'
//       AND deleted = 0

//     UNION

//     -- Old flow: from master table
//     SELECT 
//         '' AS file_name, 
//         lz.document AS path, 
//         lz.document_category, 
//         lz.type,
//         lz.created_at,
//         lz.partner_access,
//         lz.users_access
//     FROM learning_zone lz
//     WHERE lz.id = '".$zone_id."' 
//       AND lz.document IS NOT NULL 
//       AND lz.document != ''
// ) AS all_docs
// ORDER BY all_docs.created_at DESC";

// $attachments = db_query($query);

$accessConditionAttachment = "";

// If not ADMIN, then apply access conditions ONLY on attachments
if ($_SESSION['user_type'] !== "ADMIN" AND $_SESSION['user_type'] !== "AE") {
    $accessConditionAttachment = "
      AND FIND_IN_SET('".$_SESSION['user_type']."', lza.users_access)
      AND FIND_IN_SET(".$_SESSION['team_id'].", lza.partner_access)
    ";
}

$query = "SELECT * FROM (
    -- New flow: from attachments table
    SELECT 
        lza.file_name, 
        lza.path, 
        lz.document_category, 
        lza.type,
        lza.created_at,
        lza.updated_at,
        lza.partner_access,
        lza.users_access,
        lza.id,
         lz.id AS learning_zone_id,
        'learning_zone_attachment' AS source_table
    FROM learning_zone_attachment lza
    INNER JOIN learning_zone lz 
        ON lza.zone_id = lz.id
    WHERE lza.zone_id = '".$zone_id."'
      AND lza.deleted = 0
      $accessConditionAttachment

    UNION

    -- Old flow: from master table (NO ACCESS CHECK HERE)
    SELECT 
        lz.file_name AS file_name, 
        lz.document AS path, 
        lz.document_category, 
        lz.type,
        lz.created_at,
        lz.updated_at,
        lz.partner_access,
        lz.users_access,
        lz.id,
        lz.id AS learning_zone_id,
        'learning_zone' AS source_table
    FROM learning_zone lz
    WHERE lz.id = '".$zone_id."' 
      AND lz.document IS NOT NULL 
      AND lz.document != ''
) AS all_docs
ORDER BY all_docs.updated_at DESC";

$attachments = db_query($query);

?>

<style>
    #viewDoc {
        background: rgba(0, 0, 0, .32);
        backdrop-filter: blur(5px);
    }

    #viewDoc .modal-dialog {
        max-width: 900px;
        height: calc(100vh - 40px);
        padding: 0;
    }

    #viewDoc .modal-content {
        min-height: 300px;
    }

    iframe {
        width: 100%;
        height: 500px;
    }

    .responsive-embed.pdf {
        padding-bottom: 56.25%; /* approx A4 ratio */
        position: relative;
    }

    .responsive-embed.pdf iframe {
        position: absolute;
        inset: 0;
        height: 100%;
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

    .loader-overlay {
        position: absolute;
        inset-inline: 40px;
        inset-block: 20px;
        background: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }
    
    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    input[readonly] {
        border: none;
        background-color: grey;
        cursor: not-allowed;
        }


    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .change-folder-btn {
    font-weight: 500;
    padding: 5px 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}
</style>

<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header d-flex align-items-center">

                <!-- LEFT SPACER -->
                <div class="flex-grow-1"></div>

                <!-- CENTER TITLE -->
                <h5 class="modal-title mb-0 text-center" id="exampleModalLabel">
                    Document View
                </h5>

                <!-- RIGHT ACTIONS -->
                <div class="d-flex align-items-center ml-auto">

                    <button 
                        type="button" 
                        class="btn btn-sm btn-primary mr-2 change-folder-btn"
                        onclick="change_folder()">
                        <span class="mdi mdi-folder-swap mr-1"></span>
                        Change Folder
                    </button>

                    <button 
                        type="button" 
                        class="btn btn-sm btn-primary mr-2 change-folder-btn"
                        onclick="change_permission(<?= $zone_id ?>)">
                        <span class="mdi mdi-book-lock mr-1"></span>
                        Change Permission
                    </button>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            </div>
		<div class="modal-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAllRows">
                            </th>
                            <th>Category  </th>
                            <th>Type</th>
                            <th>Extension</th>
                            <th>Document Name</th>
                            <?php if($_SESSION['user_type'] == "ADMIN" || $_SESSION['user_type'] == "AE"): ?>
                            <th>Partners Access</th>
                            <th>User Type</th>
                            <?php endif;  ?>
                            <th>Upload On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php 
                            if (mysqli_num_rows($attachments) > 0) {
                                while ($row = mysqli_fetch_assoc($attachments)) {
                                //    if($row['source_table'] == 'learning_zone_attachment') {
                                //         $fileName = preg_replace('/^[^_]+_/', '', $row['file_name']);
                                //         $isParent = 0;
                                //     } else {
                                //         $fileName = preg_replace('/^[^_]+_/', '', $row['file_name']);// or handle master files differently
                                //         $isParent = 1; // it define need to update file name in parent tbl

                                //     }
                                //     $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                                // Keep your existing logic exactly as-is
                                if ($row['source_table'] == 'learning_zone_attachment') {
                                    $fileName = preg_replace('/^[^_]+_/', '', $row['file_name']);
                                    $isParent = 0;
                                } else {
                                    $fileName = preg_replace('/^[^_]+_/', '', $row['file_name']);
                                    $isParent = 1;
                                }
                            
                                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                    if (empty($fileExtension) && !empty($row['path'])) {
                                        $fileExtension = strtolower(pathinfo($row['path'], PATHINFO_EXTENSION));
                                    }
                                    $fileNameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME); 
                                
                                    $inputID = "file-name-input".$row['id'];
                                    echo "<tr>
                                    <td>
                                            <input 
                                                type='checkbox'
                                                class='row-checkbox'
                                                data-row-id='".$row['id']."'
                                            >
                                            <td>" . htmlspecialchars($row['document_category']) . "</td>
                                            <td>" . htmlspecialchars($row['type']) . "</td>
                                            <td>" . strtoupper($fileExtension) . "</td>
                                            <td>
                                                <div class='input-with-icon' style='position:relative; display:flex; align-items:center;'>
                                                    <input 
                                                        type='text' 
                                                        readonly
                                                        onclick=\"checkDisabled('".$inputID."')\"
                                                        onkeyup='updateFileName(event, ".$row['id'].", ".$isParent.")' 
                                                        id='".$inputID."' 
                                                        class='form-control editable-input $inputID' 
                                                        value='".htmlspecialchars(trim($fileNameWithoutExt))."' 
                                                        style='padding-right: 2.2em; background: #f9f9f9; border: 1px solid #ddd; border-radius: 4px;'>
                                                    <span class='mdi mdi-pencil pencil-icon' 
                                                        onclick=\"updateEditUpdate('".$inputID."')\" 
                                                        style='position:absolute; right: 12px; cursor:pointer; color:#888; font-size: 1.2em;' 
                                                        title='Edit'>
                                                    </span>
                                                </div>
                                            </td>";

                                    // show these only for ADMIN
                                    if ($_SESSION['user_type'] == "ADMIN" || $_SESSION['user_type'] == "AE") {
                                        echo "<td>" . $dataObj->getPartnerNames($row['partner_access']) . "</td>
                                            <td>" . htmlspecialchars($row['users_access']) . "</td>";
                                    }

                                    echo "<td>" . (!empty($row['created_at']) ? date('d M Y', strtotime($row['created_at'])) : "-") . "</td>
                                               <td class='text-nowrap'>
                                                        <a title='View File' href='javascript:void(0)' onclick=\"return iframeDocumentView('" . $row['path'] . "','".$row['type']."')\"
                                                        class='btn btn-primary btn-xs px-2'
                                                        data-toggle='modal' data-target='#viewDoc'>
                                                            <span class='mdi mdi-eye'></span>
                                                        </a>";

                                            if ($_SESSION['user_type'] == "ADMIN" || $_SESSION['user_type'] == "AE") {
                                                echo "  <a title='Edit File' href='javascript:void(0)' onclick=\"edit_product_doc(" . $row['id'] . ")\"
                                                            class='btn btn-primary btn-xs px-2'
                                                            data-toggle='modal' data-target='#editDoc'>
                                                            <span class='mdi mdi-pencil'></span>
                                                        </a>";

                                                echo "  <a title='Replace File' href='javascript:void(0)' onclick=\"replace_product_doc(" . $row['id'] . ")\"
                                                            class='btn btn-primary btn-xs px-2'
                                                            data-toggle='modal' data-target='#editDoc'>
                                                             <span class='mdi mdi-file-replace'></span>
                                                        </a>";

                                                // echo "<a title='Change Folder' href='javascript:void(0)'
                                                //                                  onclick=\"change_folder(" . $row['learning_zone_id'] . ")\"
                                                //                                 class='btn btn-primary ml-1 btn-xs px-2'
                                                //                                 data-toggle='modal' data-target='#editDoc'>
                                                //                                 <span class='mdi mdi-swap-horizontal'></span>
                                                //                                 </a>";

                                            }
                                            echo "                                              
                                                        <a title='Download File' href='javascript:void(0)' onclick=\"return downloadFIle('" . $row['path'] . "')\"
                                                        class='btn btn-primary btn-xs px-2'
                                                        >
                                                            <span class='mdi mdi-download'></span>
                                                        </a>";


                                            echo "    </td>
                                        </tr>";
                                }
                            } else {
                                echo "<tr>
                                        <td colspan='8' class='text-center text-muted'>No Record Found</td>
                                    </tr>";
                            }
                            ?>                      
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="position-relative" style="z-index: 9999999">
    <!-- <div class="modal fade" id="viewDoc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> -->
        <div class="modal fade" id="viewDoc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form id="uploadForm" enctype="">
                    
                    <div class="modal-header">
                        <h5 class="modal-title align-self-center mt-0" id="modal-header-title">View</h5>
                        <!-- <button type="button" class="close" aria-label="Close" onclick="$(this).closest('.modal').modal('hide')">
                            <span aria-hidden="true">&times;</span>
                        </button> -->
                         <div class="ml-auto d-flex align-items-center gap-2">
                            <!-- Download Button -->
                            <button type="button"
                                    id="modal-download-btn"
                                    class="btn btn-sm btn-primary mr-2"
                                    onclick="downloadFIle(window.currentPreviewFile)">
                                <i class="mdi mdi-download"></i> Download
                            </button>

                            <!-- Close Button -->
                            <button type="button" class="close" aria-label="Close"
                                    onclick="$(this).closest('.modal').modal('hide')">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>

                    <div class="modal-body py-4" style="min-height: auto">
                        
                        <div id="iframe-loader" class="loader-overlay">
                            <div class="spinner"></div>
                        </div>

                        <!-- Documents -->
                        <div class="responsive-embed pdf">
                            <!-- <iframe id="iframe-document" style="width:600px; height:500px;"  ></iframe>
                              -->
                            <iframe id="iframe-document" style="width:100%; height:500px;" frameborder="0"></iframe>
                        </div>

                        <!-- Videos -->
                        <!-- <div class="responsive-media video">
                            <video controls>
                                <source id="iframe-document" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div> -->

                        <!-- Responsive Image -->
                        <!-- <div class="responsive-media image">
                            <img src="images/login-bg.png" alt="Responsive Image">
                        </div> -->
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>






<script>

// When iframe fully loads → hide loader
$('#iframe-document').on('load', function () {
    $('#iframe-loader').hide();
});

// When modal closes → clear iframe
$('#viewDoc').on('hidden.bs.modal', function () {
    $('#iframe-document').attr('src', '');
});


</script>

<script>
function iframeDocumentView(path, type) {
    // console.log("path",path);
    // console.log("type",type);
    
    window.currentPreviewFile = path;
    // Show modal
    $('#viewDoc').modal('show');

    // Show loader
    $('#iframe-loader').show();

    // --- Get file extension from path ---
    let extension = '';
    try {
        extension = path.split('?')[0].split('#')[0].split('.').pop().toLowerCase();
    } catch (e) {
        extension = '';
    }

    let iframeSrc = '';
    
    // console.log("extension",extension);

    // --- PPT / PPTX ---
    if (extension === 'ppt' || extension === 'pptx') {
        // iframeSrc = 'https://docs.google.com/gview?url=' +  encodeURIComponent(path) + '&embedded=true';
    //   iframeSrc = 'https://docs.google.com/gview?url=' +
    //                 encodeURIComponent('https://stagedr.ict360.com/uploads/ppt-test.ppt') +
    //                 '&embedded=true';
    
    iframeSrc =
      'https://view.officeapps.live.com/op/embed.aspx?src=' + 'stagedr.ict360.com/'+
      encodeURIComponent(path);
      
    //   console.log(iframeSrc)
    } else {
        // PDF, images, etc.
        iframeSrc = path;
    }

    // Remove previous load event
    $('#iframe-document').off('load').on('load', function () {
        $('#iframe-loader').fadeOut();
    });

    // Clear src first (important)
    $('#iframe-document').attr('src', '');

    // Set src
    setTimeout(() => {
        $('#iframe-document').attr('src', iframeSrc);
    }, 100);

    $("#modal-header-title").text("View " + extension.toUpperCase());
}
</script>
