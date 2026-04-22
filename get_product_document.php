<?php
include('includes/include.php');
include_once('helpers/DataController.php');
$dataObj = new DataController;

$requestData = $_REQUEST;

/* ---------------------------
   INIT VARIABLES (FIX)
----------------------------*/
$where = '';

$category = isset($_REQUEST['document_category']) ? $_REQUEST['document_category'] : '';
if (!empty($category)) {
    $where .= " AND lz.document_category = '" . $category . "'";
}

/* ---------------------------
   BASE QUERY (NO DISTINCT)
----------------------------*/
$baseSql = "
    FROM learning_zone lz
    LEFT JOIN learning_zone_attachment lza 
        ON lza.zone_id = lz.id 
    LEFT JOIN categories ct 
        ON ct.id = lz.category_id 
        AND lza.type = 'DOC'
        AND lza.status = 1
        AND lza.deleted = 0
    WHERE lz.status = 1 AND ct.deleted = 0 
      AND (lz.type = 'DOC' OR lza.id IS NOT NULL)
      $where
";

/* ---------------------------
   SEARCH CONDITION
----------------------------*/
$searchSql = '';
if (!empty($requestData['search']['value'])) {
    $searchVal = $requestData['search']['value'];
    $searchSql = " AND (lz.document_category LIKE '%$searchVal%' OR lz.description LIKE '%$searchVal%')";
}

/* ---------------------------
   TOTAL RECORDS (FAST COUNT)
----------------------------*/
$countSql = "SELECT COUNT(DISTINCT lz.id) AS total $baseSql $searchSql";
$countQuery = db_query($countSql);
$countRow = db_fetch_array($countQuery);

$totalData     = (int)$countRow['total'];
$totalFiltered = $totalData;

/* ---------------------------
   DATA QUERY WITH LIMIT
----------------------------*/
$dataSql = "
    SELECT lz.*
    $baseSql
    $searchSql
    GROUP BY lz.id
    ORDER BY lz.id DESC
    LIMIT " . intval($requestData['start']) . ", " . intval($requestData['length']);

$query = db_query($dataSql);

/* ---------------------------
   BUILD RESPONSE DATA
----------------------------*/
$results = [];
$i = $requestData['start'] + 1;



while ($data = db_fetch_array($query)) {

    $nestedData = [];
    $nestedData[] = $i;

    $nestedData[] = $data['updated_at']
        ? date('d-m-Y', strtotime($data['updated_at']))
        : date('d-m-Y', strtotime($data['created_at']));

    $nestedData[] = '<a title="View File"
        onclick="return document_view(' . $data["id"] . ')"
        href="javascript:void(0)"
        class="btn btn-primary px-2 py-2">
        <i class="fa fa-folder-open"></i></a>';

    $nestedData[] = !$data['category_id']
        ? $data['document_category']
        : $dataObj->getCategoryPath($data['category_id']);
	

    $results[] = $nestedData;
    $i++;
}

/* ---------------------------
   JSON RESPONSE
----------------------------*/
echo json_encode([
    "draw"            => intval($requestData['draw']),
    "recordsTotal"    => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data"            => $results
]);
