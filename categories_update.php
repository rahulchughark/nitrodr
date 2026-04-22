<?php
include("includes/include.php");
include_once 'helpers/DataController.php';

$dataController = new DataController();
admin_protect();

// 1. Inputs & Sanitization
$categoryId    = isset($_POST['childFolder']) ? intval($_POST['childFolder']) : 0;
$newParentId   = isset($_POST['parentFolder']) ? intval($_POST['parentFolder']) : 0;
$isMasterMove  = isset($_POST['isMaster']) && $_POST['isMaster'] == 1;

// If master move, parent is forced to 0
$targetParentId = $isMasterMove ? 0 : $newParentId;

// 2. Validation
if ($categoryId <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid category selection."]);
    exit;
}

// Check if moving to same folder (skip if it's already master and requested to be master)
if (!$isMasterMove && $targetParentId === $categoryId) {
    echo json_encode(["status" => "error", "message" => "A category cannot be its own parent."]);
    exit;
}

// 3. Fetch Category and Potential New Parent Info
$categoryData = db_fetch_array(db_query("SELECT name, parent_id FROM categories WHERE id = {$categoryId} LIMIT 1"));

if (!$categoryData) {
    echo json_encode(["status" => "error", "message" => "Category not found."]);
    exit;
}

$categoryName = $categoryData['name'];
$oldParentId  = intval($categoryData['parent_id']);

// 4. Recursive Move Check (Basic - 1 level)
if (!$isMasterMove && $targetParentId > 0) {
    $newParentData = db_fetch_array(db_query("SELECT name, parent_id FROM categories WHERE id = {$targetParentId} LIMIT 1"));
    
    if ($newParentData && intval($newParentData['parent_id']) === $categoryId) {
        echo json_encode(["status" => "error", "message" => "Recursive move detected: Cannot move a parent under its own child."]);
        exit;
    }
    $newParentName = $newParentData['name'] ?? 'Unknown Parent';
}

// 5. Generate Description for Logs
if ($isMasterMove) {
    if ($oldParentId > 0) {
        $oldParentName = getSingleresult("SELECT name FROM categories WHERE id = {$oldParentId}");
        $description = "Folder '{$categoryName}' was removed from '{$oldParentName}' and set as a master folder.";
    } else {
        $description = "Folder '{$categoryName}' is already a master folder.";
    }
} else {
    $description = "Folder '{$categoryName}' was moved under parent folder '{$newParentName}'.";
}

// 6. Perform Database Update
$updateSuccess = db_query("UPDATE categories SET parent_id = {$targetParentId} WHERE id = {$categoryId}");

// 7. Success/Failure Handling
if ($updateSuccess) {
    // Log the change
    $dataController->logCategoryStructureChange([
        'entity_type' => 'categories',
        'record_id'   => $categoryId,
        'field_name'  => 'parent_id',
        'old_value'   => $oldParentId,
        'new_value'   => $targetParentId,
        'action_type' => $isMasterMove ? 'MAKE_MASTER' : 'MOVE_FOLDER',
        'description' => $description
    ]);

    echo json_encode(["status" => "success", "message" => "Category moved successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update category in database."]);
}

