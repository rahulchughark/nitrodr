<?php
ob_start();
include 'includes/include.php';

////// Export CLM tasks Data

// $cond = "";

// /* ================= USER TYPE CONDITIONS ================= */

// if (in_array($_SESSION['user_type'], ['FACULTY', 'SALES', 'HELPDESK'])) {

//     $cond .= " AND gt.task_owner = " . $_SESSION['user_id'];

//     if (! empty($_GET['CheckID'])) {
//         $cond .= " AND gt.id = " . intval($_GET['CheckID']);
//     }
// }

// /* ================= STATUS FILTER ================= */

// if (! empty($_GET['statusValue'])) {
//     $cond .= " AND gt.task_status = '" . $_GET['statusValue'] . "'";
// } else {
//     $cond .= " AND gt.task_status NOT IN ('Completed','Cancelled')";
// }

// /* ================= CREATED DATE FILTER ================= */

// if (! empty($_GET['created_f_date']) && ! empty($_GET['created_t_date'])) {
//     $cond .= " AND DATE(gt.task_generate_date) BETWEEN '" . $_GET['created_f_date'] . "' AND '" . $_GET['created_t_date'] . "'";
// }

// /* ================= DUE DATE FILTER ================= */

// if (! empty($_GET['f_due_date']) && ! empty($_GET['t_due_date'])) {
//     $cond .= " AND DATE(gt.task_due_date) BETWEEN '" . $_GET['f_due_date'] . "' AND '" . $_GET['t_due_date'] . "'";
// }

// /* ================= OWNER FILTER ================= */

// if (! empty($_GET['owner'])) {
//     $cond .= " AND gt.task_owner IN (" . $_GET['owner'] . ")";
// }

// /* ================= SUBJECT FILTER ================= */

// if (! empty($_GET['subject'])) {
//     $cond .= " AND gt.mst_task_id IN (" . $_GET['subject'] . ")";
// }

// /* ================= TASK TYPE ================= */

// // if ($_GET['task_type'] === 'Renewal') {
// //     $cond .= " AND o.agreement_type = 'Renewal'";
// // } else {
// $cond .= " AND o.agreement_type in ('Fresh','Renewal')";
// // }

// /* ================= FINAL QUERY ================= */

// $sql = "
// SELECT
//     gt.id,
//     gt.task_subject,
//     o.school_name,
//     gt.task_owner,
//     gt.task_status,
//     gt.task_generate_date,
//     gt.task_due_date,
//     cu.name AS updated_by,
//     o.id as orderId
// FROM generated_task gt
// LEFT JOIN orders o ON o.id = gt.lead_id
// LEFT JOIN clm_users cu ON cu.id = gt.updated_by
// WHERE gt.task_owner IS NOT NULL
// $cond
// ORDER BY gt.id DESC
// ";

// $query = db_query($sql);

// if (mysqli_num_rows($query) == 0) {
//     header("Location: task_report.php?msg=nodata");
//     exit;
// }

// /* ================= CSV EXPORT ================= */

// $filename = "Task_Report_" . date('Y-m-d') . ".csv";
// header('Content-Type: text/csv; charset=utf-8');
// header('Content-Disposition: attachment; filename="' . $filename . '"');

// $output = fopen('php://output', 'w');

// /* CSV HEADERS */
// fputcsv($output, [
//     'S.No',
//     'Order Id',
//     'Task Id',
//     'Task Subject',
//     'School Name',
//     'Task Owner',
//     'Status',
//     'Generated Date',
//     'Due Date',
//     'Updated By',
// ]);

// $sno = 1;

// while ($row = db_fetch_array($query)) {

//     $ownerName = getSingleresult("SELECT name FROM clm_users WHERE id=" . $row['task_owner']);

//     fputcsv($output, [
//         $sno++,
//         $row['orderId'],
//         $row['id'],
//         $row['task_subject'],
//         $row['school_name'],
//         $ownerName,
//         $row['task_status'],
//         date('d-m-Y', strtotime($row['task_generate_date'])),
//         date('d-m-Y', strtotime($row['task_due_date'])),
//         $row['updated_by'],
//     ]);
// }

// fclose($output);
// exit;



$sql = "
SELECT
    a.*,o.school_name,cu.name AS created_by,
FROM clm_activity a
LEFT JOIN orders o ON o.id = a.lead_id
LEFT JOIN clm_users cu ON cu.id = a.created_by
ORDER BY a.id DESC
";

$query = db_query($sql);

if (mysqli_num_rows($query) == 0) {
    header("Location: task_report.php?msg=nodata");
    exit;
}

/* ================= CSV EXPORT ================= */

$filename = "Activity_Report_" . date('Y-m-d') . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

/* CSV HEADERS */
fputcsv($output, [
    'S.No',
    'Order Id',
    'Activity Id',
    'School Name',
    'Subject',
    'POC Name',
    'POC Designation',
    'Provided Solution',
    'Solution Provided Date',
    'Follow Up Status',
    'Follow Up Date',
    'Follow Up Reason',
    'Follow Up Solution',
    'Remark',
    'Status',
    'Created At',
    'Updated At',
]);

$sno = 1;

while ($row = db_fetch_array($query)) {

    $ownerName = getSingleresult("SELECT name FROM clm_users WHERE id=" . $row['task_owner']);
$follow_up_status = $row['follow_up_status'] == 1 ? 'Yes' : 'No';
$status = $row['status'] == 1 ? 'Open' : 'Closed';
    fputcsv($output, [
        $sno++,
        $row['lead_id'],
        $row['id'],
        $row['school_name'],
        $row['subject'],
        $row['poc_name'],
        $row['poc_designation'],
        $row['solution_provided'],
        $row['solution_rovided_date'],
        $follow_up_status,
        date('d-m-Y', strtotime($row['follow_up_date'])),
        $row['follow_up_reason'],
        $row['follow_up_solution'],
        $row['remark'],
        $status,
        $row['created_at'],
        $row['updated_at'],
    ]);
}

fclose($output);
exit;


