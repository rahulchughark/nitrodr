<?php
require_once "includes/include.php";

// ===============================
// Datatables inputs
// ===============================
$draw   = intval($_POST['draw'] ?? 0);
$start  = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);

// ===============================
// URL params
// ===============================
$mstId  = intval($_POST['mst_id'] ?? 0);
$filter = $_POST['filter'] ?? 'all'; // all | failed | sent


// ===============================
// Page label (master campaign name)
// ===============================
$page_label = '';
$labelSql = "
    SELECT name
    FROM tbl_master_campaign
    WHERE id = '{$mstId}'
";
$labelRes = db_query($labelSql);

if ($labelRes && mysqli_num_rows($labelRes) > 0) {
    $labelRow  = mysqli_fetch_assoc($labelRes);
    $page_label = $labelRow['name'];
}

// ===============================
// Filter condition
// ===============================
$where = " WHERE mst_id = '{$mstId}' ";

if ($filter === 'failed') {
    $where .= " AND status = 2 ";
} elseif ($filter === 'sent') {
    $where .= " AND status = 1 ";
} elseif ($filter === 'invalid') {
    $where .= " AND status = 3 ";
}

// ===============================
// Total records count
// ===============================
$countSql = "
    SELECT COUNT(*) AS total
    FROM tbl_campaign_contact_attempts
    $where
";
$countRes = db_query($countSql);
$countRow = mysqli_fetch_assoc($countRes);
$totalRecords = $countRow['total'] ?? 0;

// $countStatusSql = "
//     SELECT
//         COUNT(*) AS total,
//         SUM(status = 1) AS success,
//         SUM(status = 2) AS failed,
//         SUM(status = 3) AS invalid_cnt
//     FROM tbl_campaign_contact_attempts
//     WHERE mst_id = '{$mstId}'
// ";
$countStatusSql = "
    SELECT
        COUNT(*) AS total,
        SUM(status = 1) AS success,
        SUM(status = 2) AS failed,
        SUM(status = 3) AS invalid_cnt
    FROM tbl_campaign_contact_attempts t
    INNER JOIN (
        SELECT contacts, MAX(id) AS latest_id
        FROM tbl_campaign_contact_attempts
        WHERE mst_id = '{$mstId}'
        GROUP BY contacts
    ) latest
    ON t.id = latest.latest_id
";

$countStatusRes = db_query($countStatusSql);
$countStatusRow = mysqli_fetch_assoc($countStatusRes);

$totalCount   = $countStatusRow['total'] ?? 0;
$successCount = $countStatusRow['success'] ?? 0;
$failedCount  = $countStatusRow['failed'] ?? 0;
$invalidCount = $countStatusRow['invalid_cnt'] ?? 0;

$countRes = db_query($countSql);
$countRow = mysqli_fetch_assoc($countRes);


// ===============================
// Fetch paginated records
// ===============================
// $dataSql = "
//     SELECT
//         id,
//         campaign_id,
//         contacts,
//         phone_code,
//         status,
//         remark,
//         created_at
//     FROM tbl_campaign_contact_attempts
//     $where
//     ORDER BY id DESC
//     LIMIT {$start}, {$length}
// ";

// $dataSql = "
//     SELECT
//         t.id,
//         t.campaign_id,
//         t.contacts,
//         t.phone_code,
//         t.status,
//         t.remark,
//         t.created_at
//     FROM tbl_campaign_contact_attempts t
//     INNER JOIN (
//         SELECT contacts, MAX(id) AS latest_id
//         FROM tbl_campaign_contact_attempts
//         WHERE mst_id = '{$mstId}'
//         GROUP BY contacts
//     ) latest
//     ON t.id = latest.latest_id
//     $where
//     ORDER BY t.id DESC
//     LIMIT {$start}, {$length}
// ";

$dataSql = "
    SELECT
        t.id,
        t.campaign_id,
        t.contacts,
        t.phone_code,
        t.status,
        t.remark,
        t.created_at,
        COALESCE(attempts.attempt_count, 0) AS attempt
    FROM tbl_campaign_contact_attempts t
    INNER JOIN (
        SELECT contacts, MAX(id) AS latest_id
        FROM tbl_campaign_contact_attempts
        WHERE mst_id = '{$mstId}'
        GROUP BY contacts
    ) latest
    ON t.id = latest.latest_id
    LEFT JOIN (
        SELECT contacts, COUNT(*) AS attempt_count
        FROM tbl_campaign_contact_attempts
        WHERE mst_id = '{$mstId}'
        GROUP BY contacts
    ) attempts
    ON t.contacts = attempts.contacts
    $where
    ORDER BY t.id DESC
    LIMIT {$start}, {$length}
";

$dataRes = db_query($dataSql);

// ===============================
// Prepare response rows
// ===============================
$data = [];
$sno  = $start + 1;

while ($row = mysqli_fetch_assoc($dataRes)) {
    $data[] = [
        'sno'           => $sno++,
        'id'            => $row['id'],
        'campaign_id'   => $row['campaign_id'],
        'user_number'   => $row['contacts'],
        'code'          => $row['phone_code'],
        'status'        => $row['status'],
        'failed_reason' => $row['remark'],
        'created_at'    => $row['created_at'],
        'attempt'       => $row['attempt']
    ];
}

// ===============================
// Final JSON response
// ===============================
echo json_encode([
    'draw'            => $draw,
    'recordsTotal'    => $totalRecords,
    'recordsFiltered' => $totalRecords,
    'data'            => $data,
    'page_label'      => $page_label,
    'totalCount'      => $totalCount,
    'successCount'    => $successCount,
    'failedCount'     => $failedCount,
    'invalidCount'    => $invalidCount
]);

exit;
