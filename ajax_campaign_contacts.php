<?php
include_once('helpers/DataController.php');
$dataObj = new DataController();

/* ---------------- DATATABLE INPUT ---------------- */
$draw   = $_POST['draw'] ?? 0;
$start  = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;
$category = $_POST['category'] ?? '';
$tag = $_POST['tag'] ?? '';

/* ---------------- FETCH DATA ---------------- */
// Optional: filter by campaign_id if passed
$campaignId = $_POST['campaign_id'] ?? '';

$where = "WHERE status = 1";

if (!empty($category)) {
    $where .= " AND category_id = '" . $category . "'";
}

if(!empty($tag)) {
    $where .= " AND tag_id = '" . $tag . "'";
}

/* Total records */
$countSql = db_query("
    SELECT COUNT(*) AS total 
    FROM tbl_campaign_numbers
    $where
");
$countRow = db_fetch_array($countSql);
$totalRecords = $countRow['total'] ?? 0;

/* Fetch paginated data */
$sql = db_query("
    SELECT id, contact_name, code, phone_number
    FROM tbl_campaign_numbers
    $where
    ORDER BY id DESC
    LIMIT $start, $length
");

/* ---------------- PREPARE RESPONSE ---------------- */
$final = [];
$sno = $start + 1;

while ($row = db_fetch_array($sql)) {

    $checkbox = "
        <input type='checkbox'
               class='phone-checkbox'
               value='{$row['id']}'
               onclick='onPhoneSelect({$row['id']}, this)'
        >
    ";


    $final[] = [
        "checkbox"     => $checkbox,
        "sno"          => $sno++,
        "contact_name" => htmlspecialchars($row['contact_name']),
        "code"         => htmlspecialchars($row['code']),
        "phone_number" => htmlspecialchars($row['phone_number'])
    ];
}

/* ---------------- OUTPUT ---------------- */
echo json_encode([
    "draw"            => intval($draw),
    "recordsTotal"    => intval($totalRecords),
    "recordsFiltered" => intval($totalRecords),
    "data"            => $final
]);
exit;
