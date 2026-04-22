<?php
include('includes/include.php');
include_once('helpers/DataController.php');

$category_name = trim($_POST['category_name'] ?? '');
$type          = trim($_POST['type'] ?? 'Add');
$parent_id     = $_POST['parent_id'] ? $_POST['parent_id'] : 0;



// $helper = new DataController;
// $parent_id = $helper->fetchCategoryIdByName($parentName);


// Escape input
$category_name = addslashes($category_name);
$type          = addslashes($type);

if (strtolower($type) === 'add') {
    // Always insert new category
    $insert = "INSERT INTO categories 
               (name, parent_id, status, deleted, created_at) 
               VALUES 
               ('$category_name', $parent_id, 1, 0, NOW())";
    db_query($insert);

    $new_id = get_insert_id(); // get inserted ID

    echo json_encode([
        'status'  => 'success',
        'message' => 'Category added successfully.',
        'id'      => $new_id
    ]);
    // echo json_encode(['status' => 'success', 'message' => 'Category added successfully.']);
    
} elseif (strtolower($type) === 'update') {
    $id = (int)($_POST['id'] ?? 0); // category id to update

    if ($id > 0) {
        $update = "UPDATE categories 
                   SET category_name = '$category_name',
                       type = '$type',
                       parent_id = $parent_id,
                       updated_at = NOW()
                   WHERE id = $id AND is_deleted = 0";
        db_query($update);

        echo json_encode(['status' => 'success', 'message' => 'Category updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid category ID for update.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request type.']);
}
