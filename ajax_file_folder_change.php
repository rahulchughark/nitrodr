<?php
include("includes/include.php");
include_once 'helpers/DataController.php';
$dataObj = new DataController();
admin_protect();



$fromVar = isset($_POST['fromVar']) ? intval($_POST['fromVar']) : 0;
$toVar   = isset($_POST['toVar']) ? intval($_POST['toVar']) : 0;
$name   = isset($_POST['name']) ? $_POST['name'] : '';


if ($fromVar <= 0 || $toVar <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid category values"
    ]);
    exit;
}

/* 🔹 NEW: Check if fromVar category exists */
$checkData = db_query("
    SELECT id 
    FROM learning_zone
    WHERE category_id = {$fromVar}
      AND status = 1
      AND delete_date IS NULL
    LIMIT 1
");

if (mysqli_num_rows($checkData) === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "No data found for move"
    ]);
    exit;
}

$previousFolder = getSingleresult("
    SELECT name
    FROM categories
    WHERE id = {$fromVar}
");

if (!empty($previousFolder)) {
    $description = "You have moved files from the '{$previousFolder}' folder to the '{$name}' folder.";
} else {
    $description = '';
}

// 🔹 Update learning_zone
$updateZone = db_query("
    UPDATE learning_zone 
    SET category_id = {$toVar}, document_category = '{$name}'
    WHERE category_id = {$fromVar}
      AND status = 1
      AND delete_date IS NULL
");

// Creating Log
while ($row = db_fetch_array($checkData)) {
    $dataObj->logCategoryStructureChange([
        'entity_type' => 'learning_zone',
        'record_id'   => $row['id'],
        'field_name'  => 'category_id',
        'old_value'   => $fromVar,
        'new_value'   => $toVar,
        'action_type' => 'MOVE_FILE',
        "description" => $description
    ]);
}


if ($updateZone) {
    echo json_encode([
        "status" => "success",
        "message" => "Files and folders moved successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to move some records"
    ]);
}
exit;
