<?php
include 'includes/header.php';
    admin_protect();
    include_once 'helpers/DataController.php';
    $modify_log = new DataController();


    
    if (isset($_POST['save_data'])) {
                               // echo "<br><br><br><br>";
                               // print_r($_POST);die;
        $maxsize = 5242880000; // 500MB
        if ($_FILES["attachment"]) {
            if ($_POST['type'] == 'Video') {
                $file        = $_FILES['attachment'];
                $maxsize     = 52428800000; // 500MB
                $target_dir  = "learning_centre/videos/";
                $target_file = $target_dir . time() . basename($file["name"]);
                // Validate file upload
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    switch ($file['error']) {
                        case UPLOAD_ERR_INI_SIZE:
                        case UPLOAD_ERR_FORM_SIZE:
                            throw new Exception("File too large. Maximum allowed size is 500MB.");
                        case UPLOAD_ERR_NO_FILE:
                            throw new Exception("No file uploaded.");
                        default:
                            throw new Exception("Unknown error during file upload.");
                    }
                }
                $extension        = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $valid_extensions = ["mp4", "avi", "3gp", "mov", "mpeg"];

                if (! in_array($extension, $valid_extensions)) {
                    throw new Exception("Invalid file extension. Allowed extensions are: " . implode(", ", $valid_extensions));
                }

                if ($file['size'] > $maxsize) {
                    throw new Exception("File too large. Maximum allowed size is 500MB.");
                }

                if (! move_uploaded_file($file['tmp_name'], $target_file)) {
                    throw new Exception("Failed to upload the file.");
                }

                $query    = db_query("INSERT INTO training_videos(module_id, vdo_address) VALUES(1, '" . $target_file . "')");
                $video_id = get_insert_id($query);
                
                if (! $video_id) {
                    throw new Exception("Failed to save video details in the database.");
                }

                $_SESSION['message'] = "Upload successful.";
                $admins              = db_query("SELECT id from users where user_type in ('ADMIN','SUPERADMIN')");
                while ($adm = db_fetch_array($admins)) {
                    $admm[] = $adm['id'];
                }
                $log = [
                    'title'             => $_POST['title'],
                    'document_category' => htmlspecialchars($_POST['document_catgeory'], ENT_QUOTES),
                    'video_id'          => $video_id,
                    'users_access'      => implode(",", $_POST['user_type']),
                    'partner_access'    => implode(",", $_POST['partner']),
                    'type'              => 'Video',
                ];
                $res = $modify_log->insert($log, "learning_zone");
            $mngrEmail = db_query("select email from users where user_type = 'MNGR' and team_id in(".implode(",", $_POST['partner']).")");            
            while ($row = db_fetch_array($mngrEmail)) {
                $addTo[] = $row['email'];                
            }
      
        $setSubject = "New marketing material Assigned";
        $body    = "Hi,<br><br> There is new marketing material assigned to you. Please the bellow details:-<br><br>
              <ul>
              <li><b>Title</b> : " . $_POST['title'] . " </li>
              <li><b>Document Category</b> : " . $_POST['document_catgeory'] . " </li>
              </ul><br><br>
        Please login Your portal for access the material.<br>
              Thanks,<br>
              ICT DR Portal";
                $addCc[] = '';
              $addBcc[] = '';
              sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
                if (! $res) {
                    throw new Exception("Failed to log the upload in the learning zone.");
                }
                redir("product_document.php?update=success", true);

            } else if ($_POST['type'] == 'Document') {
                $name           = $_FILES['attachment']['name'];
                $target_dir     = "learning_centre/docs/";
                $target_file    = $target_dir . basename($_FILES["attachment"]["name"]);
                $extension      = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $extensions_arr = ["jpeg", "jpg", "png", "pdf", "docx", "pptx"];

                if (in_array($extension, $extensions_arr)) {
                    if (($_FILES['attachment']['size'] >= $maxsize) || ($_FILES["attachment"]["size"] == 0)) {
                        $_SESSION['message'] = "File too large. File must be less than 5MB.";
                    } else {
                        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
                            $log = [
                                'title'             => htmlspecialchars($_POST['title'], ENT_QUOTES),
                                'document_category' => htmlspecialchars($_POST['document_catgeory'], ENT_QUOTES),
                                'document'          => $target_file,
                                'users_access'      => implode(",", $_POST['user_type']),
                                'partner_access'    => implode(",", $_POST['partner']),
                                'type'              => 'Doc',
                            ];
                            $res                 = $modify_log->insert($log, "learning_zone");
                            $mngrEmail = db_query("select email from users where user_type = 'MNGR' and team_id in(".implode(",", $_POST['partner']).")");            
                            while ($row = db_fetch_array($mngrEmail)) {
                                $addTo[] = $row['email'];                
                            }
                    
        $setSubject = "New marketing material Assigned";
        $body    = "Hi,<br><br> There is new marketing material assigned to you. Please the bellow details:-<br><br>
                            <ul>
                            <li><b>Title</b> : " . $_POST['title'] . " </li>
                            <li><b>Document Category</b> : " . $_POST['document_catgeory'] . " </li>    
                            </ul><br><br>
        Please login Your portal for access the material.<br>
                            Thanks,<br>
                            ICT DR Portal";

                            $addBcc[] = '';
                            $addCc[] = '';
                            sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
                            $_SESSION['message'] = "Upload successfully.";
                        }
                    }
                } else {
                    $_SESSION['message'] = "Invalid file extension.";
                }
            }
        } else {
            $_SESSION['message'] = "Please select a file.";
        }

        if ($res) {
            // echo "<script type=\"text/javascript\">
            //   window.location = \"product_document.php\"
            // </script>";
            redir("product_document.php?update=success", true);
        }
    }
    if (isset($_POST['edit_data'])) {


        $oldPartners = getSingleresult("SELECT partner_access FROM learning_zone_attachment where id=" . $_POST['eid']);

        // $titleQ = getSingleresult("SELECT title FROM learning_zone_attachment where id=" . $_POST['eid']);
      
        // $document_categoryQ = getSingleresult("SELECT document_category FROM learning_zone_attachment where id=" . $_POST['eid']);
        $document_categoryQ = getSingleresult("
                                            SELECT lz.document_category 
                                            FROM learning_zone_attachment lza
                                            INNER JOIN learning_zone lz 
                                                ON lza.zone_id = lz.id
                                            WHERE lza.id = " . (int)$_POST['eid']
                                        );
          
        $typeQ = getSingleresult("SELECT type FROM learning_zone_attachment where id=" . $_POST['eid']);
        
        $oldPartners_array = explode(',', $oldPartners);
      
        $b_array = $_POST['partner'];
        $diffArr = array_diff($b_array, $oldPartners_array);
        
        $sql = db_query("UPDATE learning_zone_attachment SET
            users_access = '" . implode(",", $_POST['user_type']) . "',
            partner_access = '" . implode(",", $_POST['partner']) . "'
        WHERE id=" . $_POST['eid']);

        if(!empty($diffArr)){
        $mngrEmail = db_query("select email from users where user_type = 'MNGR' and team_id in(".implode(",", $diffArr).")");            
        while ($row = db_fetch_array($mngrEmail)) {
            $addTo[] = $row['email'];                
        }

        $setSubject = "New marketing material Assigned";
        $body    = "Hi,<br><br> There is new marketing material assigned to you. Please the bellow details:-<br><br>
        <ul>
       
        <li><b>Document Category</b> : " . $document_categoryQ . " </li>
        </ul><br><br>
        Please login Your portal for access the material.<br>
        Thanks,<br>
        ICT DR Portal";
        $addCc[] = '';
        $addBcc[] = '';
        // sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
        }
        if ($sql) {
            redir("product_document.php?update=success", true);
        }
    }

if (isset($_POST['multiple_permission_update'])) {

    /* 1️⃣ NORMALIZE ATTACHMENT IDS */
    $attachmentIdsRaw = $_POST['attachmentIds'] ?? [];
    $zoneId = $_POST['zoneid'];

    $attachmentIds = [];
    if (!empty($attachmentIdsRaw[0])) {
        $attachmentIds = array_map('intval', explode(',', $attachmentIdsRaw[0]));
    }

    if (empty($attachmentIds)) {
        redir("product_document.php?error=invalid", true);
        exit;
    }    
  
    $idList = implode(',', array_map('intval', $attachmentIds));

    /* 2️⃣ FETCH OLD DATA (PER ATTACHMENT) */
    $oldDataQ = db_query("
        SELECT id, users_access, partner_access
        FROM learning_zone_attachment
        WHERE id IN ($idList)
    ");

    $oldData = [];
    while ($row = db_fetch_array($oldDataQ)) {
        $oldData[$row['id']] = $row;
    }


    /* 3️⃣ NEW DATA */
    $newUsers    = implode(",", $_POST['user_type'] ?? []);
    $newPartners = implode(",", $_POST['partner'] ?? []);

    //  $zoneUpdate = db_query("
    //     UPDATE learning_zone SET
    //         users_access   = '" . $newUsers . "',
    //         partner_access = '" . $newPartners . "'
    //     WHERE id = $zoneId
    // ");

    // echo "<pre>";
    // echo "<br><br><br><br><br><br>";
    // print_r($_POST);
    // exit; 

    

    /* 4️⃣ CREATE LOGS (BEFORE UPDATE) */
    foreach ($attachmentIds as $attachmentId) {

        if (!isset($oldData[$attachmentId])) {
            continue;
        }
       
        if ($oldData[$attachmentId]['users_access'] !== $newUsers) {
            $modify_log->logLearningZoneAttachmentChange([
                'attachment_id' => $attachmentId,
                'field_name'    => 'users_access',
                'old_value'     => $oldData[$attachmentId]['users_access'],
                'new_value'     => $newUsers,
                'action_type' => 'CHANGE_PERMISSION'
            ]);
        }

        if ($oldData[$attachmentId]['partner_access'] !== $newPartners) {
            $modify_log->logLearningZoneAttachmentChange([
                'attachment_id' => $attachmentId,
                'field_name'    => 'partner_access',
                'old_value'     => $oldData[$attachmentId]['partner_access'],
                'new_value'     => $newPartners,
                'action_type' => 'CHANGE_PERMISSION'
            ]);
        }
    }

    



    /* 5️⃣ UPDATE MAIN TABLE (BULK) */
    $sql = db_query("
        UPDATE learning_zone_attachment SET
            users_access   = '" .  $newUsers . "',
            partner_access = '" .  $newPartners . "'
        WHERE id IN ($idList)
    ");


    /* 6️⃣ EMAIL LOGIC (YOUR ORIGINAL CODE) */
    $oldPartners = getSingleresult("
        SELECT GROUP_CONCAT(DISTINCT partner_access)
        FROM learning_zone_attachment
        WHERE id IN ($idList)
    ");

    $oldPartners_array = array_filter(explode(',', $oldPartners));
    $newPartnersArray  = $_POST['partner'] ?? [];
    $diffArr           = array_diff($newPartnersArray, $oldPartners_array);

    if (!empty($diffArr)) {

        $mngrEmail = db_query("
            SELECT email 
            FROM users 
            WHERE user_type = 'MNGR'
              AND team_id IN (" . implode(',', array_map('intval', $diffArr)) . ")
        ");

        $addTo = [];
        while ($row = db_fetch_array($mngrEmail)) {
            $addTo[] = $row['email'];
        }

        /* FETCH DOCUMENT CATEGORY (FIRST ATTACHMENT ONLY FOR EMAIL) */
        $document_categoryQ = getSingleresult("
            SELECT lz.document_category 
            FROM learning_zone_attachment lza
            INNER JOIN learning_zone lz ON lza.zone_id = lz.id
            WHERE lza.id = " . (int)$attachmentIds[0]
        );

        $setSubject = "New marketing material assigned";
        $body = "
            Hi,<br><br>
            New marketing material has been assigned to you.<br><br>
            <ul>
                <li><b>Document Category</b>: {$document_categoryQ}</li>
            </ul><br>
            Please login to your portal to access the material.<br><br>
            Thanks,<br>
            ICT DR Portal
        ";

        // sendMail($addTo, [], [], $setSubject, $body);
    }

    if ($sql) {
        redir("product_document.php?update=success", true);
    } else {
        redir("product_document.php?error=failed", true);
    }
}




//     if (isset($_POST['multiple_permission_update'])) {

//     /* 1️⃣ NORMALIZE ATTACHMENT IDS (single OR multiple) */
//     $rawIds = $_POST['attachmentIds'] ?? '';

//      echo "<pre>";
//         echo "<br><br><br><br><br><br>";
//         print_r($rawIds);
//         exit;
   
//     if (empty($rawIds)) {
//         redir("product_document.php?error=invalid", true);
//         exit;
//     }     

//     // $attachmentIds = array_filter(array_map('intval', $rawIds));
//     $attachmentIds = $rawIds;

//     if (empty($attachmentIds)) {
//         redir("product_document.php?error=invalid", true);
//         exit;
//     }

//     $idList = implode(',', $attachmentIds);

//     /* 2️⃣ FETCH OLD PARTNER ACCESS (MERGED) */
//     $oldPartners = getSingleresult("
//         SELECT GROUP_CONCAT(DISTINCT partner_access)
//         FROM learning_zone_attachment
//         WHERE id IN ($idList)
//     ");

//     $oldPartners_array = array_filter(explode(',', $oldPartners));

//     /* 3️⃣ NEW PARTNER ACCESS */
//     $newPartners = $_POST['partner'] ?? [];
//     $newUsers    = $_POST['user_type'] ?? [];

//     $diffArr = array_diff($newPartners, $oldPartners_array);

//     /* 4️⃣ UPDATE ATTACHMENTS (BULK) */
//     $sql = db_query("
//         UPDATE learning_zone_attachment SET
//             users_access   = '" . mysqli_real_escape_string($GLOBALS['dbcon'], implode(",", $newUsers)) . "',
//             partner_access = '" . mysqli_real_escape_string($GLOBALS['dbcon'], implode(",", $newPartners)) . "'
//         WHERE id IN ($idList)
//     ");

//     /* 5️⃣ SEND EMAIL ONLY FOR NEW PARTNERS */
//     if (!empty($diffArr)) {

//         $mngrEmail = db_query("
//             SELECT email 
//             FROM users 
//             WHERE user_type = 'MNGR'
//               AND team_id IN (" . implode(',', array_map('intval', $diffArr)) . ")
//         ");

//         $addTo = [];
//         while ($row = db_fetch_array($mngrEmail)) {
//             $addTo[] = $row['email'];
//         }

//         /* FETCH DOCUMENT CATEGORY (FIRST ATTACHMENT ONLY FOR EMAIL) */
//         $document_categoryQ = getSingleresult("
//             SELECT lz.document_category 
//             FROM learning_zone_attachment lza
//             INNER JOIN learning_zone lz ON lza.zone_id = lz.id
//             WHERE lza.id = " . (int)$attachmentIds[0]
//         );

//         $setSubject = "New marketing material assigned";
//         $body = "
//             Hi,<br><br>
//             New marketing material has been assigned to you.<br><br>
//             <ul>
//                 <li><b>Document Category</b>: {$document_categoryQ}</li>
//             </ul><br>
//             Please login to your portal to access the material.<br><br>
//             Thanks,<br>
//             ICT DR Portal
//         ";

//         // sendMail($addTo, [], [], $setSubject, $body);
//     }

//     if ($sql) {
//         redir("product_document.php?update=success", true);
//     } else {
//         redir("product_document.php?error=failed", true);
//     }
// }
         
    // if ($_POST['eid']) {
    //     $res = db_query("update `learning_zone` set `title`='" . htmlspecialchars($_POST['title'], ENT_QUOTES) . "', `description`='" . htmlspecialchars($_POST['desc'], ENT_QUOTES) . "' where type='Doc' and id=" . $_POST['eid']);

    //     if ($res) {
    //         redir("product_document.php?update=success", true);
    //     }
    // }

?>
<style>
    .document-catgeory {
        max-width: 260px;
        height: 30px;
        padding: 5px 10px;
    }

    .dataTables_filter {
        position: absolute;
        top: 45px;
        right: 15px;
    }

    @media (max-width: 500px) {
        .document-catgeory {
            max-width: 140px;
        }

        .dataTables_filter {
            position: static;
            width: 100%
        }

        .dataTables_filter label {
            width: 100%
        }

        .search-category-DO .dropdown-menu1 {
            display: block;
            left: unset;
            right: 0;
            max-width: 300px;
        }
    }
</style>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">
                                            <small class="text-muted">Home > Product Document</small>
                                            <h4 class="font-size-14 m-0 mt-1">Product Document</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="" role="group">
                                        <!-- category_controls.php -->

                                         <!-- onclick="show_model()" -->
                                        <a href="category_controls.php" ><button title="Add Data" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-plus "></i></button></a>
                                        <div class="dropdown dropdown-lg">
                                            <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if ($_GET['add'] == 'success') {?>
                                <div class="alert alert-success d-flex pl-4 pt-4 pr-4 rounded-3 w-100" role="alert">
                                <div class="flex-grow-1">

                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3>
                                <div class="mb-3"> DAM Added Successfully!</div>
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                            </div>
                            <?php }?>
<?php if ($_GET['update'] == 'success') {?>
                                <div class="alert alert-success d-flex pl-4 pt-4 pr-4 rounded-3 w-100" role="alert">
                                <div class="flex-grow-1">

                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3>
                                <div class="mb-3"> DAM Updated Successfully!</div>

                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                            </div>
                            <?php }?>
                            <div class="clearfix"></div>

                            <div class="custom-tabs mt-3">
                                <!-- <ul class="nav nav-tabs mb-0" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="active" id="po-tab" data-toggle="tab" href="#productDoc" role="tab" aria-controls="po" aria-selected="true">Document</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a id="pi-tab" data-toggle="tab" href="#productVideo" role="tab" aria-controls="pi" aria-selected="false">Video</a>
                                    </li>
                                </ul> -->
                                <div class="row align-items-end">
                                    <div class="col">
                                     <ul class="nav nav-tabs mb-0" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="active" id="po-tab" data-toggle="tab" href="#productDoc" role="tab" aria-controls="po" aria-selected="true">Document</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a id="pi-tab" data-toggle="tab" href="#productVideo" role="tab" aria-controls="pi" aria-selected="false">Video</a>
                                    </li>
                                    <!-- <select id="document_catgeory" name="document_catgeory" class="form-control document-catgeory ml-1">
                                        <option>Select Category</option>
                                        <option>Product Pitch and Marketing Documents</option>
                                        <option>Commercial Documents</option>
                                        <option>Technical and Operational Documents</option>
                                        <option>Training and Support Resources</option>
                                        <option>Optional / Value Add Resources</option>
                                        <option>ATL</option>
                                    </select> -->
                                    <div class="position-relative search-category-DO">
                                        <button type="button" class="btn btn-xs btn-light ml-1" id="filter-box">
                                            Select Category
                                        </button>
                                        <div class="">
                                            <div class="dropdown-menu1 dropdown-md filter_wrap_2 dropdown-menu-right-xs" id="filter-container" role="menu">

                                                <div class="form-group">
                                                    <input type="text" onkeyup="return categorySearch(event)" class="form-control" placeholder="Search Category">
                                                </div>
                                                
                                                <div class="docCatTree" id="category-filter-html">
                                                 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </ul>
                            </div>
                            <!-- <div class="col-auto">
                                
                            </div> -->
                                    <!-- <div class="col-auto">
                                        <div class="form-group mb-0">
                                        <select id="document_catgeory" name="document_catgeory" class="form-control">
                                            <option>Select Category</option>
                                            <option>Product Pitch and Marketing Documents</option>
                                            <option>Commercial Documents</option>
                                            <option>Technical and Operational Documents</option>
                                            <option>Training and Support Resources</option>
                                            <option>Optional / Value Add Resources</option>
                                            <option>ATL</option>
                                        </select>
                                    </div>
                                    </div> -->
                                </div>
                                <div class="tab-content pt-2" id="myTabContent">
                                    <div class="tab-pane fade show active" id="productDoc" role="tabpanel" aria-labelledby="doc-tab">
                                        <!-- <a href="javascript:void(0)" onclick="document_view()"><i class="fa fa-folder-open" aria-hidden="true"></i></a> -->
                                        <div class="table-responsive" id="MyDiv">
                                            <table id="documents" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th data-sortable="true">S.No.</th>
                                                        <th data-sortable="true">Date of upload</th>
                                                        <!-- <th data-sortable="true">Title</th> -->
                                                        <th data-sortable="true">View</th>
                                                        <th data-sortable="true">Content Category</th>
                                                        <!-- <th data-sortable="true">Partners Access</th>
                                                        <th data-sortable="true">Users Type Access</th> -->
                                                        <!-- <th data-sortable="true">Edit Partner Access</th> -->
                                                        <!-- <th data-sortable="true">Delete</th> -->
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="productVideo" role="tabpanel" aria-labelledby="video-tab">
                                        <div class="table-responsive" id="MyDiv">
                                            <table id="videos" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th data-sortable="true">S.No.</th>
                                                        <th data-sortable="true">Date of upload</th>
                                                        <!-- <th data-sortable="true">Title</th> -->
                                                        <th data-sortable="true">View</th>
                                                        <th data-sortable="true">Content Category</th>
                                                        <!-- <th data-sortable="true">Partners Access</th>
                                                        <th data-sortable="true">Users Type Access</th> -->
                                                        <!-- <th data-sortable="true">Edit Partner Access</th> -->
                                                        <!-- <th data-sortable="true">Delete</th> -->
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- Modal -->
        <!-- <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false"></div> -->

<!-- <div id="myModal" class="modal fade" role="dialog"></div> -->
<div id="myModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="myModal2" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

        <div id="notif_dropdown_usr" class="modal fade" role="dialog"></div>
        <?php include 'includes/footer.php'?>

        <script>
            let documentTable;
            let videoTable;

            function loadDocumentTable(selectedCategory = '') {
                if ($.fn.DataTable.isDataTable('#documents')) {
                    documentTable.clear().destroy();
                }

                documentTable = $('#documents').DataTable({
                    "stateSave": true,
                    dom: 'frtip',
                    bSortCellsTop: true,
                    language: {
                        paginate: {
                            previous: '<i class="fas fa-arrow-left"></i>',
                            next: '<i class="fas fa-arrow-right"></i>'
                        }
                    },
                    // buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'],
                    lengthMenu: [
                        [15, 25, 50, 100, 500, 1000, 10000, 50000],
                        ['15', '25', '50', '100', '500', '1000', '10000', '50000']
                    ],
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "get_product_document.php",
                        type: "post",
                        data: function (d) {
                            d.d_from = "<?php echo $_GET['d_from']?>";
                            d.d_to = "<?php echo $_GET['d_to']?>";
                            d.partner = '<?php echo safe_implode('","', $_GET['partner'])?>';
                            d.date_from = '<?php echo $_GET['date_from']?>';
                            d.date_to = '<?php echo $_GET['date_to']?>';
                            d.document_category = selectedCategory;
                        },
                        error: function () {
                            $(".employee-grid-error").html("");
                            $("#documents").append('<tbody class="employee-grid-error"><tr><th colspan="14">No data found on server!</th></tr></tbody>');
                            $("#documents_processing").css("display", "none");
                        }
                    }
                });
                $(document).ready(function() {
                    var wfheight = $(window).height();
                    $('.dataTables_wrapper').height(wfheight - 355);
                    $("#documents").tableHeadFixer();
                });
            }

            function loadVideoTable(selectedCategory = '') {
                if ($.fn.DataTable.isDataTable('#videos')) {
                    videoTable.clear().destroy();
                }

                videoTable = $('#videos').DataTable({
                    "stateSave": true,
                    dom: 'frtip',
                    bSortCellsTop: true,
                    language: {
                        paginate: {
                            previous: '<i class="fas fa-arrow-left"></i>',
                            next: '<i class="fas fa-arrow-right"></i>'
                        }
                    },
                    // buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'],
                    lengthMenu: [
                        [15, 25, 50, 100, 500, 1000, 10000, 50000],
                        ['15', '25', '50', '100', '500', '1000', '10000', '50000']
                    ],
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "get_learning_zone.php",
                        type: "post",
                        data: function(d) {
                            d.d_from = "<?php echo $_GET['d_from']?>";
                            d.d_to = "<?php echo $_GET['d_to']?>";
                            d.partner = '<?php echo safe_implode('","', $_GET['partner'])?>';
                            d.date_from = '<?php echo $_GET['date_from']?>';
                            d.date_to = '<?php echo $_GET['date_to']?>';
                            d.document_category = selectedCategory;
                        },
                        error: function() {
                            $(".employee-grid-error").html("");
                            $("#videos").append('<tbody class="employee-grid-error"><tr><th colspan="14">No data found on server!</th></tr></tbody>');
                            $("#videos_processing").css("display", "none");
                        }
                    }
                });

                $(document).ready(function() {
                    var wfheight = $(window).height();
                    $('.dataTables_wrapper').height(wfheight - 355);
                    $("#videos").tableHeadFixer();
                });
            }

            $(document).ready(function() {
                // Initial load without filter
                loadDocumentTable();
                loadVideoTable();

                // On dropdown change, reload both tables
                $('#document_catgeory').on('change', function() {
                    const category = $(this).val();
                    loadDocumentTable(category);
                    loadVideoTable(category);
                });
            });

            function show_model() {
                $.ajax({
                    type: 'POST',
                    url: 'add_product_document.php',
                    success: function(response) {
                        $("#myModal").html();
                        $("#myModal").html(response);
                        $('#myModal').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function edit_product_doc(id) {
                $.ajax({
                    type: 'POST',
                    url: 'edit_product_doc.php',
                    data: {
                        edit_id: id
                    },
                    success: function(response) {
                        $("#myModal").html();
                        $("#myModal").html(response);
                        $('#myModal').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function replace_product_doc(id) {
                $.ajax({
                    type: 'POST',
                    url: 'replace_product_doc_file.php',
                    data: {
                        edit_id: id
                    },
                    success: function(response) {
                        $("#myModal").html();
                        $("#myModal").html(response);
                        $('#myModal').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function notFound() {
                swal("File not found.");
            }

            function count_views(document, id) {
                var url = '<?php define('MY_PATH', 'http://' . $HTTP_HOST . SITE_SUB_PATH)?>';
                $.ajax({
                    type: 'POST',
                    url: 'count_views.php',
                    data: {
                        document: document,
                        product_id: id,
                    },
                    success: function(data) {
                        window.location.href = url + document;
                    }
                });
            }

            // Document View
            function document_view(id) {
                var page_access = 'true';

                $.ajax({
                    type: 'POST',
                    url: 'document_view.php',
                    data: {
                        page_access: page_access,
                        id: id
                    },
                    success: function(response) {
                        $("#myModal").html(response);
                        $('#myModal').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function clear_search() {
                window.location = 'product_document.php';
            }
        </script>
        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 310);
                $("#documents").tableHeadFixer();
            });

            function delete_notification(id) {
                swal({
                    title: "Are you sure?",
                    text: "You want to remove document?",
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    confirmButtonText: "Yes!",
                    confirmButtonColor: "#ec6c62"
                }, function() {
                    $.ajax({
                        type: 'POST',
                        url: 'count_views.php',
                        data: {
                            delete_docId: id,
                        },
                        success: function(response) {
                            return false;
                        }
                    }).done(function(data) {
                        swal("Document deleted successfully!");
                        $('#documents').DataTable().ajax.reload();
                    }).error(function(data) {
                        swal("Oops", "We couldn't connect to the server!", "error");
                    });
                })
            }

            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 355);
                $("#videos").tableHeadFixer();
            });

            function notFound() {
                // swal("File not found.");
                alert("not found");
            }

            // For videos
            function videoModelV(video_id, video_address, title, view = '') {
                $.ajax({
                    type: 'POST',
                    url: 'video_learning_zone.php',
                    data: {
                        video_id: video_id,
                        video_address: video_address,
                        view: view,
                        title: title
                    },
                    success: function(response) {
                        $("#myModal").html();
                        $("#myModal").html(response);
                        $('#myModal').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            function delete_notificationV(id) {
                swal({
                    title: "Are you sure?",
                    text: "You want to remove video?",
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    confirmButtonText: "Yes!",
                    confirmButtonColor: "#ec6c62"
                }, function() {
                    $.ajax({
                        type: 'POST',
                        url: 'count_views.php',
                        data: {
                            delete_vid: id,
                        },
                        success: function(response) {
                            return false;
                        }
                    }).done(function(data) {
                        swal("Video deleted successfully!");
                        $('#videos').DataTable().ajax.reload();
                    }).error(function(data) {
                        swal("Oops", "We couldn't connect to the server!", "error");
                    });
                })
            }

            document.addEventListener('click', function(e) {
                // Folder click
                if (e.target.closest('.docCatTree .folder')) {
                    e.stopPropagation();
                    const folder = e.target.closest('.docCatTree .folder');

                    // Toggle nested <ul>
                    const ul = folder.querySelector('ul');
                    if (ul) {
                        ul.style.display = (ul.style.display === 'none' || ul.style.display === '') ? 'block' : 'none';
                    }

                    // Toggle 'active' class
                    folder.classList.toggle('active');
                }

                // Content-category click
                if (e.target.closest('.content-category')) {
                    e.stopPropagation();
                }
            });

            $(document).on("click", ".btn-next", function(e) {
                e.preventDefault();
                let form = $(this).closest(".upload-form");
                form.find(".step-upload").show();
                form.find(".manage-permissions").hide();
            });


            function showFilterMaterial(id, name) {
                $.ajax({
                    type: 'POST',
                    url: 'filter_data_documents.php',
                    data: {
                        id: id,
                        name: name
                    },
                    success: function(response) {
                        $("#myModal").html();
                        $("#myModal").html(response);
                        $('#myModal').modal('show');
                        $('.preloader').hide();
                    }
                });
            }

            $("#filter-box").on("click", function() {
                $.ajax({
                    type: 'GET',
                    url: 'ajax_category_filter_data.php',
                    success: function(response) {
                        $("#category-filter-html").html(response);
                    }
                });
            });

            function categorySearch(event) {
                val = event.target.value;

                $.ajax({
                    type: 'POST',
                    data: {
                        keyword: val
                    },
                    url: 'ajax_category_filter_data.php',
                    success: function(response) {
                        $("#category-filter-html").html(response);
                    }
                });
            }


            let typingTimer; // timer identifier
            const doneTypingInterval = 800; // time in ms (0.8s) after user stops typing

            function updateFileName(e, id, isParent = 0) {
                clearTimeout(typingTimer); // clear previous timer
                let newFileName = e.target.value;

                typingTimer = setTimeout(function() {
                    if (newFileName.trim() === "") {
                        return;
                    }

                    $.ajax({
                        url: "ajax_update.php",
                        type: "POST",
                        data: {
                            id: id,
                            file_name: newFileName,
                            type: 'zone_file_name_update',
                            is_parent: isParent
                        },
                        dataType: "json",
                        success: function(response) {
                            try {
                                let res = response;
                                if (res.status === "success") {
                                    toastr.success('Success : Filename updated successfully!');
                                } else {
                                    toastr.error('Error: Filename not updated successfully');
                                }
                            } catch (err) {
                                toastr.error('Error : Invalid Response Coming');
                            }
                        },
                        error: function(xhr, status, error) {
                            toastr.error('Error : Script not working');
                        }
                    });

                }, doneTypingInterval);
            }

            function updateEditUpdate(container_id) {
                $("." + container_id).prop("readonly", false); // remove readonly
                $("." + container_id).focus(); // optional: put cursor inside
            }

            function checkDisabled(classname) {
                $("." + classname).each(function() {
                    if ($(this).prop("readonly")) { // check readonly property
                        toastr.info("Edit button required to proceed");
                    } else {
                        // console.log("Enabled Input:", this.id, $(this).val());
                    }
                });
            }


            function toggleNextButton() {
                let partnersSelected = $('.multiselect_partner').val(); // returns array or null
                let userTypesSelected = $('.multiselect_user_type').val(); // returns array or null

                if (partnersSelected && partnersSelected.length > 0 &&
                    userTypesSelected && userTypesSelected.length > 0) {
                    $('.nextBtnFileUploader').prop('disabled', false);
                } else {
                    $('.nextBtnFileUploader').prop('disabled', true);
                }
            }

            // Run on page load in case selects are prefilled
            toggleNextButton();

            // Run every time a select changes
            $('.multiselect_partner, .multiselect_user_type').on('change', function() {
                toggleNextButton();
            });


            function change_folder() {
                if (!selectedRowIds || selectedRowIds.length === 0) {
                    toastr.info('Please select at least one document to change folder.');
                    return;
                }
                $.ajax({
                    type: 'POST',
                    url: 'change_folder.php',
                    success: function(response) {
                        $("#myModal2").html();
                        $("#myModal2").html(response);
                        $('#myModal2').modal('show');
                        $('.preloader').hide();
                    }
                });
            }
        </script>


<script>
let selectedRowIds = [];

// Individual row checkbox
$(document).on('change', '.row-checkbox', function () {

    const rowId = $(this).data('row-id');

    if (this.checked) {
        // add if not exists
        if (!selectedRowIds.includes(rowId)) {
            selectedRowIds.push(rowId);
        }
    } else {
        // remove if unchecked
        selectedRowIds = selectedRowIds.filter(id => id !== rowId);
    }

    // console.log('Selected Row IDs:', selectedRowIds);
});



$(document).on('change', '#selectAllRows', function () {
// alert("hello");
    selectedRowIds = [];

    const isChecked = this.checked;

    $('.row-checkbox').each(function () {
        this.checked = isChecked;

        if (isChecked) {
            selectedRowIds.push($(this).data('row-id'));
        }
    });

    // console.log('Selected Row IDs:', selectedRowIds);
});
</script>

<script>
$(document).on('click', '.category-checkbox', function (e) {

    e.stopPropagation();

    const $checkbox = $(this);
    const categoryId = $checkbox.data('id');
    const categoryName = $checkbox.data('name');
    const isChecked = $checkbox.is(':checked');
    const learningZoneId = $('#learning_zone_id').val();
    const zoneAttached = selectedRowIds;

    // Only confirm when checking
    if (!isChecked) {
        return;
    }

    swal({
        title: "Are you sure?",
        text: "You want to move items to a different folder?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, proceed",
        cancelButtonText: "Cancel",
        confirmButtonColor: "#3085d6",
        closeOnConfirm: false
    }, function (isConfirm) {

        if (!isConfirm) {
            // Revert checkbox if cancelled
            $checkbox.prop('checked', false);
            return;
        }

        $.ajax({
            url: 'ajax_change_folder.php',
            type: 'POST',
            dataType: 'json',
            data: {
                learning_zone_id: learningZoneId,
                category_id: categoryId,
                category_name: categoryName,
                zone_attached_id: zoneAttached
            },
            success: function (res) {
                if (res.status === 'success') {
                    swal.close();
                    toastr.success("Category updated successfully");
                    // location.reload();
                } else {
                    swal.close();
                    toastr.error(res.message || 'Update failed');
                    $checkbox.prop('checked', false);
                }
            },
            error: function () {
                swal.close();
                toastr.error("Something went wrong");
                $checkbox.prop('checked', false);
            }
        });

    });

});
</script>
<script>
function downloadFIle(filePath) {
    if (!filePath) {
        toastr.error('File not available for download.');
        return false;
    }

    // Create temporary anchor
    const link = document.createElement('a');
    link.href = filePath;
    link.setAttribute('download', '');

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    // Show success message
    toastr.success('Downloaded successfully.');

    return false;
}



function change_permission(zone_master) {

    if (!selectedRowIds || selectedRowIds.length === 0) {
    toastr.info('Please select at least one document for permission.');
    return;
   }      
     
                $.ajax({
                    type: 'POST',
                    url: 'ajax_change_permission.php',       
                    data: {
                        zone_id : zone_master,
                        edit_id: selectedRowIds.join(',')
                    },             
                    success: function(response) {
                        $("#myModal2").html();
                        $("#myModal2").html(response);
                        $('#myModal2').modal('show');
                        $('.preloader').hide();
                    }
                });
            }



</script>