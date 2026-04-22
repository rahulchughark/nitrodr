<?php
include("includes/include.php");

$categoryId = intval($_POST['category_id'] ?? 0);

$response = [
    'is_parent' => false
];

if ($categoryId > 0) {
    $sql = "SELECT parent_id FROM categories WHERE id = $categoryId AND deleted = 0 LIMIT 1";
    $query = db_query($sql);

    if ($row = db_fetch_array($query)) {
        if ((int)$row['parent_id'] === 0) {
            $response['is_parent'] = true;
        }
    }
}

echo json_encode($response);
exit;
