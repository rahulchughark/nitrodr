<?php
require_once "includes/include.php";
include_once('helpers/DataController.php');
$dataObj = new DataController;


// DataTables params
$draw   = $_POST['draw'] ?? 1;
$start  = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;

// Total records
$totalSql = "
    SELECT COUNT(*) AS total
    FROM tbl_master_campaign
    WHERE is_parent = 1
";
$totalRes = db_query($totalSql);
$totalRow = mysqli_fetch_assoc($totalRes);
$totalRecords = $totalRow['total'] ?? 0;

// Fetch paginated data
// $sql = "
//     SELECT
//         id,
//         mst_campaign,
//         campaign,
//         name,
//         template,
//         project_id,
//         total_contacts,
//         sent_total,
//         invalid_contacts,
//         failed_send,
//         status,
//         is_parent,
//         is_failed_processed,
//         created_at
//     FROM tbl_master_campaign
//     WHERE is_parent = 1
//     ORDER BY id DESC
//     LIMIT {$start}, {$length}
// ";
$sql = "
    SELECT
        mc.id,
        mc.mst_campaign,
        mc.campaign,
        mc.name,
        mc.template,
        mc.project_id,
        IFNULL(cs.total, 0)          AS total_contacts,
        IFNULL(cs.success, 0)        AS sent_total,
        IFNULL(cs.invalid_cnt, 0)    AS invalid_contacts,
        IFNULL(cs.failed, 0)         AS failed_send,
        mc.status,
        mc.is_parent,
        mc.is_failed_processed,
        mc.created_at
    FROM tbl_master_campaign mc
    LEFT JOIN (
        SELECT
            t.mst_id,
            COUNT(*) AS total,
            SUM(t.status = 1) AS success,
            SUM(t.status = 2) AS failed,
            SUM(t.status = 3) AS invalid_cnt
        FROM tbl_campaign_contact_attempts t
        INNER JOIN (
            SELECT mst_id, contacts, MAX(id) AS latest_id
            FROM tbl_campaign_contact_attempts
            GROUP BY mst_id, contacts
        ) latest
            ON t.id = latest.latest_id
        GROUP BY t.mst_id
    ) cs ON cs.mst_id = mc.id
    WHERE mc.is_parent = 1
    ORDER BY mc.id DESC
    LIMIT {$start}, {$length}
";
$res = db_query($sql);

$data = [];
$sno  = $start + 1;

while ($row = mysqli_fetch_assoc($res)) {

    $data[] = [
        "sno"                 => $sno++,
        "campaign_name"       => $row['name'],
        "template"            => $row['template'],
        "total_contacts"      => $row['total_contacts'],
        "sent_total"          => $row['sent_total'],
        "invalid_contacts"    => $row['invalid_contacts'],
        "failed_send"         => $row['failed_send'],
        "status"              => $row['status'] == 1 ? 'Active' : 'Inactive',
        "campaign_type"       => $row['is_parent'] == 1 ? 'Parent' : 'Child',
        "failed_processed"    => $row['is_failed_processed'] == 1 ? 'Yes' : 'No',
        "created_at"          => date('Y-m-d H:i:s', strtotime($row['created_at'])),
        "action"              => "
            <a href='view-campaign2.php?id={$row['id']}'
               class='btn btn-sm btn-primary'>
               View
            </a>
        "
    ];
}

// DataTables response
echo json_encode([
    "draw"            => intval($draw),
    "recordsTotal"    => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data"            => $data
]);
exit;
