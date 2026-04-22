<?php

include("includes/include.php");
include_once('helpers/DataController.php');
$dataObj = new DataController;

admin_protect();


if (isset($_POST['type']) && $_POST['type'] == "replace_learning_zone") {

 if (!empty($_FILES['attachments']['tmp_name'][0])) {

    foreach ($_FILES['attachments']['tmp_name'] as $key => $tmpName) {

        if (!is_uploaded_file($tmpName)) {
            continue;
        }

        $originalName = basename($_FILES['attachments']['name'][$key]);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        $fileName = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $originalName);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $filePath)) {

            $fileType = ($ext === 'mp4') ? "VIDEO" : "DOC";
            $id = (int) $_POST['eid'];

            $updateZone = db_query("
                UPDATE learning_zone_attachment
                SET 
                    file_name = '" . addslashes($fileName) . "',
                    type = '" . addslashes($fileType) . "',
                    path = '" . addslashes($filePath) . "',
                    updated_at = NOW()
                WHERE id = '" . $id . "'
            ");

            if (!$updateZone) {
                echo json_encode(['status' => 'error', 'message' => 'DB update failed']);
                exit;
            }

        } else {
            echo json_encode(['status' => 'error', 'message' => 'File move failed']);
            exit;
        }
    }

    echo json_encode(['status' => 'success', 'message' => 'Files uploaded successfully']);

} else {
    echo json_encode(['status' => 'error', 'message' => 'No files uploaded']);
}   


exit;

}

   

if (isset($_POST['category_id']) && !empty($_POST['uploaded_files'])) {
   
    $category_id   = $_POST['category_id'];
   
    $category_name = $_POST['category_name'] ?? '';
    $title         = $_POST['title'] ?? '';
    $type          = $_POST['file_type'] ?? 'DOC';
    $user_type = implode(',', $_POST['user_type'] ?? []);
    $partner = implode(',', $_POST['partner'] ?? []);

    $existingZone = db_query("SELECT id FROM learning_zone WHERE category_id = '" . intval($category_id) . "' LIMIT 1");
    $learning_zone_id = null;
    

    $uploadDir = "uploads/learning_zone/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $errors = [];
    $saved  = [];

    if ($row = db_fetch_array($existingZone)) {
         $learning_zone_id = $row['id'];
         db_query("
                UPDATE learning_zone 
                SET users_access = '" . $user_type . "', 
                    partner_access = '" . $partner . "',
                    updated_at = NOW()
                WHERE id = '" . intval($learning_zone_id) . "'
            ");

            // die($learning_zone_id);
       
        }else {
            // Insert new zone since not found
            $insertZone = db_query("
                INSERT INTO learning_zone (document_category, category_id, title, type, users_access, partner_access) 
                VALUES (
                    '" . $category_name . "',
                    '" . $category_id . "',
                    '" . $title . "',
                    '" . $type . "',
                    '" . $user_type . "',
                    '" . $partner . "'
                )
            ");

            if ($insertZone) {
                $learning_zone_id = get_insert_id();
            } else {
                $errors[] = "Master record insert failed.";
            }
        }



    // Step 1: Insert master record
    // $insertZone = db_query("
    //     INSERT INTO learning_zone (document_category,category_id, title, type,users_access,partner_access) 
    //     VALUES (
    //         '" . $category_name . "',
    //         '" . $category_id . "',
    //         '" . $title . "',
    //         '" . $type . "',
    //         '" . $user_type . "',
    //         '" . $partner . "'
    //     )
    // ");

    if ($learning_zone_id) {
        //$learning_zone_id = get_insert_id(); // get new master ID

        // Step 2: Process uploaded_files
        foreach ($_POST['uploaded_files'] as $tempPath) {
            $originalName = basename($tempPath);
            $fileName     = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $originalName);
            $filePath     = $uploadDir . $fileName;

            if (file_exists($tempPath) && rename($tempPath, $filePath)) {
                // Insert attachment row
                $insertAttach = db_query("
                    INSERT INTO learning_zone_attachment (zone_id, path, file_name,type,users_access,partner_access) 
                    VALUES (
                        '" . intval($learning_zone_id) . "',
                        '" . $filePath . "',
                        '" . $originalName . "',
                        '" . $type . "',
                        '" . $user_type . "',
                        '" . $partner . "'
                    )
                ");

                if ($insertAttach) {
                    $saved[] = $fileName;
                } else {
                    $errors[] = "DB insert failed for " . $originalName;
                }
            } else {
                $errors[] = "Move failed for " . $originalName;
            }
        }
    } else {
        $errors[] = "Master record insert failed.";
    }

    // Response
    if (empty($errors)) {
         $category_name = $_POST['category_name'] ?? '';
         $type          = $_POST['file_type'] ?? 'DOC';
         $dataObj->sendAdminFolderPermissionMail('rahul.chugh@arkinfo.in',$category_name,$type);
         // $dataObj->sendAdminFolderPermissionMail('rahul.chugh@arkinfo.in',$category_name,$type);
        
        echo json_encode([
            "status" => "success", 
            "message" => "Document(s) uploaded successfully", 
            "files" => $saved
        ]);
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => implode(", ", $errors)
        ]);
    }

    exit;
}

echo json_encode(["status" => "error", "message" => "Invalid request"]);
exit;
