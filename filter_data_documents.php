<?php

include 'includes/include.php';


$category_id = $_POST['id']; 

$res = db_query("
    SELECT 
        lz.id AS learning_zone_id,
        lz.document_category,
        lz.category_id,
        lz.title,
        att.type,
        lz.users_access,
        lz.partner_access,
        att.id AS attachment_id,
        att.path,
        att.file_name,
        att.type attachment_type,
        att.created_at,
        att.updated_at
    FROM learning_zone lz
    LEFT JOIN learning_zone_attachment att 
        ON att.zone_id = lz.id
    WHERE lz.category_id = '$category_id'
    ORDER BY att.updated_at DESC" );

$data = [];
while ($row = mysqli_fetch_assoc($res)) {

    $lzId = $row['learning_zone_id'];

    if (!isset($data[$lzId])) {
        $data[$lzId] = [
            'id' => $lzId,
            'document_category' => $row['document_category'],
            'category_id' => $row['category_id'],
            'title' => $row['title'],
            'type' => $row['type'],
            'users_access' => $row['users_access'],
            'partner_access' => $row['partner_access'],
            'attachments' => []
        ];
    }

    if (!empty($row['attachment_id'])) {
        $data[$lzId]['attachments'][] = [
            'id' => $row['attachment_id'],
            'path' => $row['path'],
            'file_name' => $row['file_name'],
            'type' => $row['attachment_type'],
            'created_at' => $row['created_at'],
        ];
    }
    
}

// reset numeric indexes
$data = array_values($data);

// echo "<pre>";
// print_r($data);
// exit;

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
    .loader-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
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


    
</style>

<div class="modal-dialog modal-dialog-centered modal-xl">
   <!-- Modal content-->
   <div class="modal-content">
      <div class="modal-header">
         <h4 class="modal-title">Displaying records for "<?= isset($data[0]["document_category"]) ? $data[0]["document_category"] : '' ?>"</h4>
         <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
         <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Extension</th>
                        <th>Document Name</th>
                        <th>Upload On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- href='".$att['path']."' target='_blank' -->
                      <!-- <td>".(!empty($att['file_name']) ? htmlspecialchars($att['file_name']) : basename($att['path']))."</td> -->
                    <?php 
                        if (!empty($data)) {
                            foreach ($data as $row) {
                                if (!empty($row['attachments'])) {
                                    foreach ($row['attachments'] as $att) {
                                        $inputID = "search-file-name-input".$att['id'];
                                        $fileName = preg_replace('/^[^_]+_/', '', $att['file_name']);
                                        $fileNameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME); 
                                        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                        if (empty($fileExtension) && !empty($row['path'])) {
                                            $fileExtension = strtolower(pathinfo($row['path'], PATHINFO_EXTENSION));
                                        }
                                        
                                       echo "<tr>
                                        <td>" . htmlspecialchars($row['document_category']) . "</td>
                                        <td>" . htmlspecialchars($att['type']) . "</td>
                                        <td>" . strtoupper($fileExtension) . "</td>

                                        <td>
                                            <div class='input-icon-wrapper' style='display: flex; align-items: center; position: relative;'>
                                                <input
                                                    readonly
                                                    type='text'
                                                    onclick=\"checkDisabled('" . $inputID . "')\"
                                                    onkeyup='updateFileName(event," . $att['id'] . ")'
                                                    id='" . $inputID . "'
                                                    class='form-control editable-file-input $inputID'
                                                    value='" . htmlspecialchars(trim($fileNameWithoutExt)) . "'
                                                    style='padding-right: 2.5em; background: #f9f9f9;'
                                                >
                                                <span class='mdi mdi-pencil input-edit-icon'
                                                    onclick=\"updateEditUpdate('" . $inputID . "')\"
                                                    style='position: absolute; right: 10px; cursor: pointer; color: #888; font-size: 1.25em;'
                                                    title='Edit file name'
                                                ></span>
                                            </div>
                                        </td>

                                        <td>" . (!empty($att['created_at']) ? date('d M Y', strtotime($att['created_at'])) : "-") . "</td>

                                        <td>
                                            <a title='View File' href='javascript:void(0)' onclick=\"return iframeDocumentView('" . $att['path'] . "')\"  
                                            data-toggle='modal' data-target='#viewDoc' 
                                            class='btn btn-primary px-2 py-1'>
                                                <span class='mdi mdi-eye'></span>
                                            </a>";
                                            
                                          
                                            if (in_array($_SESSION['user_type'], ["ADMIN", "AE"])) {
                                                echo "
                                                <a title='Edit File' href='javascript:void(0)' 
                                                onclick=\"edit_product_doc(" . $att['id'] . ")\"
                                                class='btn btn-primary btn-xs px-2'
                                                data-toggle='modal' data-target='#editDoc'>
                                                    <span class='mdi mdi-pencil'></span>
                                                </a>
                                                <a title='Replace File' href='javascript:void(0)' 
                                                onclick=\"replace_product_doc(" . $att['id'] . ")\"
                                                class='btn btn-primary btn-xs px-2'
                                                data-toggle='modal' data-target='#editDoc'>
                                                    <span class='mdi mdi-file-replace'></span>
                                                </a>";
                                            }

                                            

                                        echo " <a title='Download File' href='javascript:void(0)' onclick=\"return downloadFIle('" . $att['path'] . "')\"
                                                        class='btn btn-primary btn-xs px-2'
                                                        >
                                                            <span class='mdi mdi-download'></span>
                                                        </a></td>
                                    </tr>";

                                    }
                                } else {
                                    // Parent exists but no attachments
                                    echo "<tr>
                                            <td>".htmlspecialchars($row['document_category'])."</td>
                                            <td>".htmlspecialchars($row['type'])."</td>
                                            <td class='text-muted'>No Attachments</td>
                                            <td>-</td>
                                            <td>-</td>
                                        </tr>";
                                }
                            }
                        } else {
                            echo "<tr>
                                    <td colspan='5' class='text-center text-muted'>No Record Found</td>
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
            <div class="modal-content h-100">
                <form id="uploadForm" enctype="">
                    <div class="modal-header">
                        <h5 class="modal-title align-self-center mt-0">View Document</h5>
                        <button type="button" class="close" aria-label="Close" onclick="$(this).closest('.modal').modal('hide')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body py-4" style="min-height: auto">
                        <div id="iframe-loader" class="loader-overlay">
                            <div class="spinner"></div>
                        </div>
                        <!-- Documents -->
                        <div class="responsive-embed pdf">
                            <iframe id="iframe-document" ></iframe>
                        </div>

                        <!-- Videos -->
                        <!-- <div class="responsive-media video">
                            <video controls>
                                <source src="images/sample-video.mp4" type="video/mp4">
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
    function iframeDocumentView(path){
        // console.log(path);
        document.getElementById('iframe-document').src = path;
    }

    function iframeDocumentView(path) {
    // show loader
    $('#iframe-loader').show();

    // set src
    $('#iframe-document').attr('src', path);
        }

        // When iframe fully loads → hide loader
        $('#iframe-document').on('load', function () {
            $('#iframe-loader').hide();
        });


     $('#viewDoc').on('hidden.bs.modal', function () {
        $('#iframe-document').attr('src', '');
    });
</script>