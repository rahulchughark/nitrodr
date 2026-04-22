<?php
include("includes/include.php");
include_once('helpers/DataController.php');
$dataObj = new DataController();

admin_protect();


$is_deleted = $_POST['is_deleted'];
$is_update = $_POST['is_update'];

if($is_deleted){


  $id = $_POST['id'] ?? 0;

if ($id > 0) {
    // Fetch name for logging
    $catName = getSingleresult("SELECT name FROM categories WHERE id = " . intval($id));

    // 🔹 Update status instead of hard delete
    $delete = db_query("UPDATE categories SET deleted = 1 WHERE id = " . intval($id));

    if ($delete) {
        $dataObj->logCategoryStructureChange([
            'entity_type' => 'categories',
            'record_id'   => $id,
            'field_name'  => 'deleted',
            'old_value'   => 0,
            'new_value'   => 1,
            'action_type' => 'DELETE_FOLDER',
            'description' => "Folder '{$catName}' was deleted."
        ]);

        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid ID"]);
}
exit;

}else if($is_update){

    $id   = $_POST['id'] ?? 0;
    $name = $_POST['category_name'] ?? '';

    if ($id > 0 && !empty($name)) {
        // 🔹 Update category name
        $update = db_query("
            UPDATE categories 
            SET name = '" . $name . "' 
            WHERE id = " . intval($id) . " AND deleted = 0
        ");

        if ($update) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid ID or Name"]);
    }
    exit;


}else{


// fetch all categories
$sql = db_query("SELECT id, name, parent_id FROM categories WHERE deleted = 0 ORDER BY id ASC");

$categories = [];
while ($row = db_fetch_array($sql)) {
    // $row['isData'] = !$row['parent_id'] ? true : false;
    $row['isData'] = $dataObj->isCategoryDataExists($row['id']);
    $categories[] = $row;
}

echo json_encode(["status" => "success", "data" => $categories]);
exit;

}