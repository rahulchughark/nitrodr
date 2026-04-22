<?php

include('includes/include.php');
include_once('helpers/DataController.php');

$attachment_id = isset($_POST['attachment_id']) ? $_POST['attachment_id'] : null;

if ($attachment_id && !empty($_FILES['attachments']['name'])) {
    $uploadDir = __DIR__ . "/uploads/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $name = $_FILES['attachments']['name'];
    $tmpName = $_FILES['attachments']['tmp_name'];
    $error = $_FILES['attachments']['error'];
    $attachmentName = $name; // Original name

    $attachmentId = (int)$_POST['attachment_id']; // From AJAX
    $userID = $_SESSION['user_id']; // Assuming session user ID

    if ($error === UPLOAD_ERR_OK) {
        $safeName = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $name);

        if (move_uploaded_file($tmpName, $uploadDir . $safeName)) {
            $filePath = "uploads/" . "/" . $safeName;

            //Update existing attachment
            $update = "UPDATE opportunity_attachments 
                       SET attachment_name = '$attachmentName',
                           attachment_path = '$filePath',
                           added_by = '$userID',
                           name = " . ($name_label ? "'$name_label'" : "NULL") . ",
                           amount = " . ($amount !== NULL ? $amount : "NULL") . "                           
                       WHERE id = $attachmentId";
            db_query($update);

            echo json_encode([
                "status" => "success",
                "message" => "Attachment updated successfully.",
                "file_name" => $attachmentName,
                "file_path" => $safeName
            ]);
            exit;
        }
    }

    echo json_encode([
        "status" => "error",
        "message" => "Failed to update attachment."
    ]);
    exit;
}

$order_id        = (int)$_POST['order_id_attach'];  
$main_product_id = (int)$_POST['main_product_id'];  
$sub_product_id  = (int)$_POST['sub_product_id'];  
$userID = $_SESSION['user_id'];

$name_label = !empty($_POST['name']) ? mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['name']) : NULL;
$amount     = !empty($_POST['amount']) ? floatval($_POST['amount']) : NULL;


if (!empty($_FILES['pi_attachments']['name'][0])) {
    $uploadDir = __DIR__ . "/uploads/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($_FILES['pi_attachments']['name'] as $key => $name) {
        $tmpName = $_FILES['pi_attachments']['tmp_name'][$key];
        $error   = $_FILES['pi_attachments']['error'][$key];
        // $attachmentName = $name;
        $attachmentName = addslashes($name);

        if ($error === UPLOAD_ERR_OK) {
            // $safeName = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $name);
             $cleanName = preg_replace("/[^a-zA-Z0-9\._-]/", "_", $name);
             $safeName  = time() . "_" . $cleanName;

            if (move_uploaded_file($tmpName, $uploadDir . $safeName)) {
                $filePath = "uploads/" . $safeName;

                // Insert record into opportunity_attachments
                $insert = "INSERT INTO opportunity_attachments 
                           (lead_id, attachment_type, attachment_name, tbl_lead_product_id, product_id, attachment_path, added_by, status, created_at, name, amount) 
                           VALUES 
                           ($order_id, 'pi_attachments', '$attachmentName', $main_product_id, $sub_product_id, '$filePath', '$userID', 1, NOW(), " . ($name_label ? "'$name_label'" : "NULL") . ", " . ($amount !== NULL ? $amount : "NULL") . ")";
                db_query($insert);
            }
        }
    }

    echo json_encode(['status' => 'success', 'message' => 'Attachment(s) uploaded and saved.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No files selected']);
}

// if (!empty($_FILES['po_attachments']['name'][0])) {
//     $uploadDir = __DIR__ . "/uploads/";

//     if (!is_dir($uploadDir)) {
//         mkdir($uploadDir, 0777, true);
//     }

//     foreach ($_FILES['po_attachments']['name'] as $key => $name) {
  

//         $tmpName = $_FILES['po_attachments']['tmp_name'][$key];
//         $error   = $_FILES['po_attachments']['error'][$key];
//         $attachmentName = $name;
          
         

//         if ($error === UPLOAD_ERR_OK) {
//             $safeName = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $name);
            
//             if (move_uploaded_file($tmpName, $uploadDir . $safeName)) {
//                 $filePath = "uploads/" ."/" . $safeName;            
                
//                 // Insert record into tbl_invoice_attachment
//                 $insert = "INSERT INTO opportunity_attachments 
//                            (lead_id,attachment_type,attachment_name,tbl_lead_product_id, product_id, attachment_path,added_by , status, created_at) 
//                            VALUES 
//                            ($order_id,'pi_attachments','$attachmentName',$main_product_id, $sub_product_id, '$filePath','$userID', 1, NOW())";
//                 db_query($insert);
//             }
//         }
//     }

//     echo json_encode(['status' => 'success', 'message' => 'Attachment(s) uploaded and saved.']);
// } else {
//     echo json_encode(['status' => 'error', 'message' => 'No files selected']);
// }