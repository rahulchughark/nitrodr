<?php
include("includes/include.php");
admin_protect();

header('Content-Type: application/json');


if (!empty($_FILES['attachments'])) {

    // $uploadDir = "uploads/temp_uploads/";  // temporary folder
    $uploadDir = "uploads/learning_zone/";  // temporary folder
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $errors = [];
    $saved  = [];


    foreach ($_FILES['attachments']['tmp_name'] as $i => $tmpName) {

        if (!empty($tmpName) && is_uploaded_file($tmpName)) {
            $originalName = basename($_FILES['attachments']['name'][$i]);
            $ext          = pathinfo($originalName, PATHINFO_EXTENSION);
            // sanitize + unique name
            $fileName = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $originalName);
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $filePath)) {
                $saved[] = $filePath;
            } else {
                $errors[] = "Upload failed for " . $originalName;
            }
        }
         
    }


    if (empty($errors)) {
        echo json_encode([
            "status"  => "success",
            "message" => "Files uploaded successfully",
            "files"   => $saved
        ]);
    } else {
        echo json_encode([
            "status"  => "error",
            "message" => implode(", ", $errors),
            "files"   => $saved
        ]);
    }

    exit;
}

// fallback
echo json_encode(["status" => "error", "message" => "No files received"]);
exit;