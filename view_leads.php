<?php
include('includes/header.php');
include_once('helpers/DataController.php');

$row = [];
$leadId = isset($_REQUEST['eid']) ? (int)$_REQUEST['eid'] : 0;

if ($leadId > 0) {
    $query = db_query("SELECT * FROM orders WHERE id='".$leadId."'");
    $row = db_fetch_array($query);
}

if (!$row) {
    redir('admin_leads.php?m=nodata', true);
    exit;
}

$callLogStatus = '';
if (isset($_POST['submit_call_log'])) {
    $callLogId = (int)($_POST['call_log_id'] ?? 0);
    $callSubject = trim((string)($_POST['call_subject'] ?? ''));
    $callDescription = trim((string)($_POST['call_description'] ?? ''));

    if ($leadId > 0 && $callSubject !== '' && $callDescription !== '') {
        $safeSubject = mysqli_real_escape_string($GLOBALS['dbcon'], $callSubject);
        $safeDescription = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($callDescription, ENT_QUOTES));
        $addedBy = (int)($_SESSION['user_id'] ?? 0);

        if ($callLogId > 0) {
            $updateCallLog = db_query("UPDATE activity_log SET call_subject='".$safeSubject."', description='".$safeDescription."' WHERE id='".$callLogId."' AND pid='".$leadId."' AND activity_type='Lead' LIMIT 1");
            $callLogStatus = $updateCallLog ? 'updated' : 'error';
        } else {
            $insertCallLog = db_query("INSERT INTO activity_log (pid, description, activity_type, call_subject, added_by, created_date) VALUES ('".$leadId."', '".$safeDescription."', 'Lead', '".$safeSubject."', '".$addedBy."', NOW())");
            $callLogStatus = $insertCallLog ? 'success' : 'error';
        }
    } else {
        $callLogStatus = 'invalid';
    }

    redir('view_leads.php?eid='.$leadId.'&calllog='.$callLogStatus, true);
    exit;
}

function text_or_na($value) {
    $value = trim((string)$value);
    return $value !== '' ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : 'N/A';
}

function master_name_by_id($table, $id, $columns = ['name']) {
    $id = (int)$id;
    if ($id <= 0) {
        return '';
    }

    $result = db_query("SELECT * FROM " . $table . " WHERE id='".$id."' LIMIT 1");
    $data = $result ? db_fetch_array($result) : [];
    if (!$data) {
        return '';
    }

    foreach ($columns as $column) {
        if (isset($data[$column]) && trim((string)$data[$column]) !== '') {
            return (string)$data[$column];
        }
    }

    return '';
}

$isApproved = (int)($row['is_approved'] ?? 0);
$isApprovalLocked = ($isApproved === 1);
$isOpportunity = (int)($row['is_opportunity'] ?? 0);
$currentRoleType = trim((string)($_SESSION['user_type'] ?? ''));
$isUsrRole = ($currentRoleType === 'USR');
$rolesNotAllowedForAction = ['CLR','SALES MNGR'];
$canPerformActions = !in_array($currentRoleType, $rolesNotAllowedForAction, true);
$rolesAllowedToEditLead = ['CLR'];
$canEditLead = $canPerformActions || in_array($currentRoleType, $rolesAllowedToEditLead, true);
$statusText = ((int)($row['status'] ?? 0) === 1) ? 'Active' : 'Inactive';
$showExpiryInfo = false;
$expiryDateText = '';
$expireInText = '';
if ($isApproved === 1 && !empty($row['close_date'])) {
    $closeDateTs = strtotime($row['close_date']);
    if ($closeDateTs) {
        $showExpiryInfo = true;
        $expiryDateText = date('d-m-Y', $closeDateTs);

        $todayTs = strtotime(date('Y-m-d'));
        $expiryDayTs = strtotime(date('Y-m-d', $closeDateTs));
        $daysLeft = (int)(($expiryDayTs - $todayTs) / 86400);

        if ($daysLeft > 1) {
            $expireInText = $daysLeft . ' Days';
        } elseif ($daysLeft === 1) {
            $expireInText = '1 Day';
        } elseif ($daysLeft === 0) {
            $expireInText = 'Today';
        } else {
            $expireInText = 'Expired';
        }
    }
}

$subscriptionTermValue = trim((string)($row['subscription_term'] ?? ''));
$subscriptionTermText = 'N/A';
if ($subscriptionTermValue === '1') {
    $subscriptionTermText = '1 Year';
} elseif ($subscriptionTermValue === '3') {
    $subscriptionTermText = '3 Years';
}

$stateName = master_name_by_id('states', $row['state_id'] ?? 0, ['name']);
$cityName = master_name_by_id('city', $row['city_id'] ?? 0, ['name', 'city']);
$industryName = master_name_by_id('tbl_mst_industry', $row['industry_id'] ?? 0, ['name']);
$leadSourceName = master_name_by_id('lead_source', $row['lead_source_id'] ?? 0, ['lead_source']);
$productInterestValue = '';
if (isset($row['product_interest']) && trim((string)$row['product_interest']) !== '') {
    $productInterestValue = (string)$row['product_interest'];
} elseif (isset($row['product_of_interest']) && trim((string)$row['product_of_interest']) !== '') {
    $productInterestValue = (string)$row['product_of_interest'];
}

$productNameMap = master_name_by_id('tbl_product', $row['product'] ?? 0, ['product_name']);
$productInterestNameMap = master_name_by_id('tbl_product_poi', $productInterestValue, ['name']);
$subProductNameMap = master_name_by_id('tbl_product_pivot', $row['sub_product'] ?? 0, ['product_type']);
$productDescriptionNameMap = master_name_by_id('tbl_product_description', $row['description'] ?? 0, ['description']);
$leadCommentValue = '';
if (isset($row['add_comment']) && trim((string)$row['add_comment']) !== '') {
    $leadCommentValue = (string)$row['add_comment'];
} elseif (isset($row['comment']) && trim((string)$row['comment']) !== '') {
    $leadCommentValue = (string)$row['comment'];
}
$stageName = master_name_by_id('tbl_mst_stage', $row['stage_id'] ?? 0, ['name']);
$proofEngagementName = master_name_by_id('tbl_mst_proof_engagement', $row['proof_engagement_id'] ?? 0, ['name']);

$stageOptions = [];
$stageResult = db_query("SELECT id, name FROM tbl_mst_stage ORDER BY name ASC");
while ($stageRow = db_fetch_array($stageResult)) {
    $stageOptions[] = $stageRow;
}

$callSubjectOptions = [];
$callSubjectResult = db_query("SELECT subject FROM call_subject ORDER BY subject ASC");
while ($callSubjectRow = db_fetch_array($callSubjectResult)) {
    if (!empty($callSubjectRow['subject'])) {
        $callSubjectOptions[] = $callSubjectRow['subject'];
    }
}

$partnerId = (int)($row['partner_id'] ?? 0);
$alignToUserId = (int)($row['align_to'] ?? 0);

$partnerName = master_name_by_id('partners', $partnerId, ['name']);
$alignToUserName = master_name_by_id('users', $alignToUserId, ['name']);
$createdByUserName = master_name_by_id('users', $row['created_by'] ?? 0, ['name']);

$uploadExtension = !empty($row['upload_file']) ? strtoupper(pathinfo($row['upload_file'], PATHINFO_EXTENSION)) : '';

$auditLogs = [];
$auditLogQuery = db_query("SELECT lm.id, lm.type, lm.previous_name, lm.modify_name, lm.created_date, lm.created_by, lm.created_by_clm, u.name as user_name, cu.name as clm_user_name FROM lead_modify_log as lm LEFT JOIN users as u ON u.id=lm.created_by LEFT JOIN clm_users as cu ON cu.id=lm.created_by_clm WHERE lm.lead_id='".$leadId."' AND (lm.log_status='Active' OR lm.log_status IS NULL OR lm.log_status='') ORDER BY lm.created_date DESC, lm.id DESC");
while ($auditLogRow = db_fetch_array($auditLogQuery)) {
    $auditLogs[] = $auditLogRow;
}

$callLogs = [];
$callLogsQuery = db_query("SELECT al.id, al.call_subject, al.description, al.created_date, u.name as added_by_name FROM activity_log as al LEFT JOIN users as u ON u.id=al.added_by WHERE al.pid='".$leadId."' AND al.activity_type='Lead' ORDER BY al.created_date DESC, al.id DESC");
while ($callLogRow = db_fetch_array($callLogsQuery)) {
    $callLogs[] = $callLogRow;
}
?>
<style>
    .lead-view-card .card-subtitle { margin: 0; padding: 8px 10px; background: #f4f6f9; font-size: 13px; color: #1B274D; }
    .lead-view-card .view-row { display: flex; padding: 8px 0; border-bottom: 1px dashed #ececec; }
    .lead-view-card .view-row:last-child { border-bottom: none; }
    .lead-view-card .view-label { width: 42%; font-weight: 600; color: #495057; }
    .lead-view-card .view-value { width: 58%; color: #212529; word-break: break-word; }
    .status-badge { font-size: 12px; padding: 6px 10px; border-radius: 20px; }
    .status-approved { background: #e6f7ee; color: #1f8f4e; }
    .status-pending { background: #fff4e6; color: #c57600; }
    .status-rejected { background: #fdecea; color: #b02a37; }
    .status-onboard { background: #e7f1ff; color: #1155cc; }
    .opportunity-yes { background: #e7f1ff; color: #1155cc; }
    .opportunity-no { background: #f3f3f3; color: #6c757d; }
    .stage-edit-icon { font-size: 14px; }
    .switch-toggle { position: relative; display: inline-block; width: 46px; height: 24px; margin: 0; }
    .switch-toggle input { opacity: 0; width: 0; height: 0; }
    .switch-toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #c9c9c9; transition: .2s; border-radius: 24px; }
    .switch-toggle-slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: #fff; transition: .2s; border-radius: 50%; }
    .switch-toggle input:checked + .switch-toggle-slider { background-color: #28a745; }
    .switch-toggle input:checked + .switch-toggle-slider:before { transform: translateX(22px); }
    .switch-toggle.toggle-not-allowed,
    .switch-toggle.toggle-not-allowed .switch-toggle-slider,
    .switch-toggle.toggle-not-allowed input { cursor: not-allowed !important; }
    /* Segmented radio control for approval */
    .approval-segment { display: inline-block; vertical-align: middle; }
    .approval-segment .segmented { display: inline-flex; border: 1px solid #ced4da; border-radius: 6px; overflow: hidden; }
    /* .approval-segment .segmented label { margin: 0; padding: 6px 12px; cursor: pointer; font-size: 13px; background: #fff; color: #212529; border-right: 1px solid rgba(0,0,0,0.04); } */
    /* .approval-segment .segmented label:last-child { border-right: none; } */
    /* .approval-segment .segmented input { display: none; } */
    /* .approval-segment .segmented label.active { background: #007bff; color: #fff; } */
    /* .approval-segment .segmented label.pending { background: #fff; color: #212529; }
    .approval-segment .segmented label.approved { background: #28a745; color: #fff; }
    .approval-segment .segmented label.rejected { background: #dc3545; color: #fff; }
    .approval-segment .segmented label.onboard { background: #007bff; color: #fff; } */

    .approval-segment .segmented label { margin: 0; padding: 6px 12px; cursor: pointer; font-size: 13px; background: #fff; color: #212529; border-right: 1px solid rgba(0,0,0,0.1); transition: background 0.15s, color 0.15s; }
.approval-segment .segmented label:last-child { border-right: none; }
.approval-segment .segmented input { display: none; }

/* Default (unselected) — ghost/outline style */
.approval-segment .segmented label.pending  { background: #fff; color: #6c757d; }
.approval-segment .segmented label.approved { background: #fff; color: #28a745; }
.approval-segment .segmented label.rejected { background: #fff; color: #dc3545; }
.approval-segment .segmented label.onboard  { background: #fff; color: #007bff; }

/* Active/selected — solid filled */
.approval-segment .segmented label.pending.active  { background: #6c757d; color: #fff; }
.approval-segment .segmented label.approved.active { background: #28a745; color: #fff; }
.approval-segment .segmented label.rejected.active { background: #dc3545; color: #fff; }
.approval-segment .segmented label.onboard.active  { background: #007bff; color: #fff; }
    
    /* container highlight when a selection is active */
    .approval-segment.active-container { border-radius: 8px; padding: 4px; display: inline-block; }
    .approval-segment.active-container .segmented { box-shadow: 0 12px 40px rgba(0,123,255,0.08); border-color: rgba(0,123,255,0.18); }
    .approval-segment.disabled { opacity: 0.6; cursor: not-allowed; }
    .approval-segment.disabled .segmented label { cursor: not-allowed; }
    .sweet-alert .approval-note-red { color: #dc3545; font-weight: 600; }
    #leadLogsHeading,
    #leadLogsHeading a { cursor: pointer; }
    #leadLogsUsrHint { color: #dc3545 !important; font-weight: 600; }
    .blink-hint { animation: blinkHint 1s linear infinite; }
    .call-log-desc-full { display: none; }
    @keyframes blinkHint {
        0%, 50%, 100% { opacity: 1; }
        25%, 75% { opacity: 0.2; }
    }
    .toggle-text { font-weight: 600; margin-left: 10px; vertical-align: middle; }
    .logs-single-line { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 520px; }
    .upload-preview-thumb { width: 90px; height: 90px; object-fit: cover; cursor: pointer; border: 1px solid #ddd; padding: 2px; }
    .global-ajax-loader {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.35);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .global-ajax-loader .loader-card {
        background: #fff;
        padding: 12px 16px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: #1B274D;
    }
    .global-ajax-loader .loader-spinner {
        width: 18px;
        height: 18px;
        border: 2px solid #dce3ea;
        border-top-color: #007bff;
        border-radius: 50%;
        animation: approvalSpin .8s linear infinite;
    }
    @keyframes approvalSpin {
        to { transform: rotate(360deg); }
    }
    @media (max-width: 767px) {
        .lead-view-card .view-row { flex-direction: column; }
        .lead-view-card .view-label, .lead-view-card .view-value { width: 100%; }
    }
</style>


<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="media bredcrum-title">
                        <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                        <div class="media-body">
                            <small class="text-muted">Home > View Lead</small>
                            <h4 class="font-size-14 m-0 mt-1">View Lead #<?= (int)$row['id'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12 d-flex flex-wrap align-items-center">
                    <span id="approvalBadge" class="status-badge <?= $isApproved ? 'status-approved' : 'status-pending' ?> mr-2 mb-2">
                        <?= $isApproved ? 'Approved' : 'Not Approved' ?>
                    </span>
                    <?php if ($canPerformActions) { ?>
                    <span id="opportunityBadge" class="status-badge <?= $isOpportunity ? 'opportunity-yes' : 'opportunity-no' ?> mr-2 mb-2">
                        <?= $isOpportunity ? 'Opportunity: Yes' : 'Opportunity: No' ?>
                    </span>
                    <?php } ?>
                </div>
            </div>

            <div class="card lead-view-card">
                <div class="card-body">
                    <h5 class="card-subtitle mt-3">Activity Logs</h5>
                    <div id="leadLogsAccordion" class="accordion mt-2">
                        <div class="card">
                            <div class="card-header p-2" id="leadLogsHeading">
                                <h6 class="mb-0 d-flex align-items-center justify-content-between">
                                    <div>
                                       
                                        <a href="javascript:void(0);" data-toggle="collapse" data-target="#leadLogsCollapse" aria-expanded="false" aria-controls="leadLogsCollapse" class="text-dark">
                                        Lead Action History
                                        </a>
                                         <small id="leadLogsUsrHint" class="text-muted mr-2 blink-hint">This section is hidden by default. Click the container to expand.</small>
                                    </div>
                                    <span class="badge badge-info"><?= count($auditLogs) ?></span>
                                </h6>
                            </div>
                            <div id="leadLogsCollapse" class="collapse" aria-labelledby="leadLogsHeading" data-parent="#leadLogsAccordion">
                                <div class="card-body p-2">
                                    <?php if (empty($auditLogs)) { ?>
                                        <div class="text-muted">No logs found for this lead.</div>
                                    <?php } else { ?>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Activity</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($auditLogs as $log) { ?>
                                                        <tr>
                                                            <?php
                                                                $userName = trim((string)($log['user_name'] ?? ''));
                                                                $clmUserName = trim((string)($log['clm_user_name'] ?? ''));
                                                                $logUser = $userName !== '' ? $userName : $clmUserName;
                                                                if ($logUser === '') {
                                                                    $logUser = 'Unknown User';
                                                                }

                                                                $logType = trim((string)($log['type'] ?? 'Update'));
                                                                if (strcasecmp($logType, 'Approval') === 0) {
                                                                    $logType = 'Approval Status';
                                                                } elseif (strcasecmp($logType, 'Opportunity') === 0) {
                                                                    $logType = 'Opportunity Status';
                                                                }
                                                                $previousName = trim((string)($log['previous_name'] ?? ''));
                                                                $modifyName = trim((string)($log['modify_name'] ?? ''));
                                                                $previousName = $previousName !== '' ? $previousName : 'N/A';
                                                                $modifyName = $modifyName !== '' ? $modifyName : 'N/A';
                                                                $logTime = !empty($log['created_date']) ? date('F j, Y, g:i a', strtotime($log['created_date'])) : '';

                                                                $isLeadCreateLog = (strcasecmp($log['type'] ?? '', 'Lead') === 0)
                                                                    && (strcasecmp($previousName, 'N/A') === 0)
                                                                    && (strcasecmp($modifyName, 'Lead') === 0);

                                                                if ($isLeadCreateLog) {
                                                                    $logLine = $logUser . ' has created this lead';
                                                                } else {
                                                                    $logLine = $logUser . ' has changed ' . $logType . ' from ' . $previousName . ' to ' . $modifyName;
                                                                }
                                                                if ($logTime !== '') {
                                                                    $logLine .= ' on ' . $logTime . '.';
                                                                } else {
                                                                    $logLine .= '.';
                                                                }
                                                            ?>
                                                            <td class="logs-single-line" title="<?= htmlspecialchars($logLine, ENT_QUOTES, 'UTF-8') ?>\"><?= text_or_na($logLine) ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="card-subtitle mt-3 d-flex align-items-center justify-content-between">
                        <span>Call Log</span>
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#callLogModal"><i class="fa fa-plus mr-1"></i>Call Log</button>
                    </h5>
                    <?php if (!empty($callLogs)) { ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Call Subject</th>
                                    <th>Description</th>
                                    <th>Added By</th>
                                    <th>Created At</th>
                                    <th>Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $callLogSno = 1; foreach ($callLogs as $callLog) { ?>
                                    <tr>
                                        <td><?= $callLogSno++ ?></td>
                                        <td><?= text_or_na($callLog['call_subject'] ?? '') ?></td>
                                        <td>
                                            <?php
                                            $fullDescription = trim((string)($callLog['description'] ?? ''));
                                            $shortDescription = mb_substr($fullDescription, 0, 80);
                                            $hasMoreDescription = mb_strlen($fullDescription) > 80;
                                            ?>
                                            <span class="call-log-desc-short"><?= text_or_na($hasMoreDescription ? ($shortDescription . '...') : $fullDescription) ?></span>
                                            <?php if ($hasMoreDescription) { ?>
                                                <span class="call-log-desc-full"><?= text_or_na($fullDescription) ?></span>
                                                <a href="javascript:void(0);" class="call-log-read-more ml-1">Read more</a>
                                            <?php } ?>
                                        </td>
                                        <td><?= text_or_na($callLog['added_by_name'] ?? '') ?></td>
                                        <td><?= !empty($callLog['created_date']) ? date('d-m-Y h:i:s A', strtotime($callLog['created_date'])) : 'N/A' ?></td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-primary edit-call-log-btn"
                                                data-id="<?= (int)($callLog['id'] ?? 0) ?>"
                                                data-subject="<?= htmlspecialchars((string)($callLog['call_subject'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                                data-description="<?= htmlspecialchars((string)($callLog['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                            >
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } else { ?>
                    <div class="p-2 border text-muted text-center">No Call Log Found</div>
                    <?php } ?>

                    <h5 class="card-subtitle">Customer Details</h5>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="view-row"><div class="view-label">Customer Company Name</div><div class="view-value"><?= text_or_na($row['customer_company_name'] ?? '') ?></div></div>
                            <div class="view-row"><div class="view-label">Customer Name</div><div class="view-value"><?= text_or_na($row['customer_name'] ?? '') ?></div></div>
                            <div class="view-row"><div class="view-label">Email</div><div class="view-value"><?= text_or_na($row['email'] ?? '') ?></div></div>
                            <div class="view-row"><div class="view-label">Phone</div><div class="view-value"><?= text_or_na($row['phone'] ?? '') ?></div></div>
                            <div class="view-row"><div class="view-label">Designation</div><div class="view-value"><?= text_or_na($row['designation'] ?? '') ?></div></div>
                        </div>
                        <div class="col-md-6">
                            <div class="view-row"><div class="view-label">State</div><div class="view-value"><?= text_or_na($stateName !== '' ? $stateName : ($row['state_id'] ?? '')) ?></div></div>
                            <div class="view-row"><div class="view-label">City</div><div class="view-value"><?= text_or_na($cityName !== '' ? $cityName : ($row['city_id'] ?? '')) ?></div></div>
                            <div class="view-row"><div class="view-label">Address</div><div class="view-value"><?= text_or_na($row['address'] ?? '') ?></div></div>
                            <div class="view-row"><div class="view-label">Industry</div><div class="view-value"><?= text_or_na($industryName !== '' ? $industryName : ($row['industry_id'] ?? '')) ?></div></div>
                            <div class="view-row"><div class="view-label">Existing Nitro customer?</div><div class="view-value"><?= text_or_na($row['existing_nitro_customer'] ?? '') ?></div></div>
                        </div>
                    </div>

                    <h5 class="card-subtitle mt-3">Deal Details</h5>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="view-row"><div class="view-label">Product Interest</div><div class="view-value"><?= text_or_na($productInterestNameMap !== '' ? $productInterestNameMap : $productInterestValue) ?></div></div>
                            <div class="view-row"><div class="view-label">Sub Product</div><div class="view-value"><?= text_or_na($subProductNameMap !== '' ? $subProductNameMap : ($row['sub_product'] ?? '')) ?></div></div>
                            <div class="view-row"><div class="view-label">Number of licenses</div><div class="view-value"><?= text_or_na($row['number_of_licenses'] ?? '') ?></div></div>
                            <div class="view-row"><div class="view-label">License Type</div><div class="view-value"><?= text_or_na($row['license_type'] ?? '') ?></div></div>
                        </div>
                        <div class="col-md-6">
                            <div class="view-row"><div class="view-label">Product</div><div class="view-value"><?= text_or_na($productNameMap !== '' ? $productNameMap : ($row['product'] ?? '')) ?></div></div>
                            <div class="view-row"><div class="view-label">Product Description</div><div class="view-value"><?= text_or_na($productDescriptionNameMap !== '' ? $productDescriptionNameMap : ($row['description'] ?? '')) ?></div></div>
                            <div class="view-row"><div class="view-label">Subscription term</div><div class="view-value"><?= $subscriptionTermText ?></div></div>
                            <div class="view-row"><div class="view-label">Renewal Type</div><div class="view-value"><?= text_or_na($row['renewal_type'] ?? '') ?></div></div>
                            <div class="view-row"><div class="view-label">Expected closure date</div><div class="view-value"><?= !empty($row['expected_closure_date']) ? date('d-m-Y', strtotime($row['expected_closure_date'])) : 'N/A' ?></div></div>
                        </div>
                    </div>

                    <h5 class="card-subtitle mt-3">Opportunity Details</h5>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="view-row"><div class="view-label">Competition involved</div><div class="view-value"><?= text_or_na($row['competition_involved'] ?? '') ?></div></div>
                            <div class="view-row"><div class="view-label">Comment</div><div class="view-value">
                                <?php
                                $commentText = trim((string)$leadCommentValue);
                                $commentLimit = 120;
                                if ($commentText === '') {
                                    echo 'N/A';
                                } else {
                                    $isLongComment = strlen($commentText) > $commentLimit;
                                    $shortComment = $isLongComment ? substr($commentText, 0, $commentLimit) . '...' : $commentText;
                                    echo '<span class="lead-comment-short">' . htmlspecialchars($shortComment, ENT_QUOTES, 'UTF-8') . '</span>';
                                    if ($isLongComment) {
                                        echo '<span class="lead-comment-full" style="display:none;">' . htmlspecialchars($commentText, ENT_QUOTES, 'UTF-8') . '</span>';
                                        echo ' <a href="javascript:void(0);" class="lead-comment-toggle">Read more</a>';
                                    }
                                }
                                ?>
                            </div></div>
                            <div class="view-row"><div class="view-label">Lead Source</div><div class="view-value"><?= text_or_na($leadSourceName !== '' ? $leadSourceName : ($row['lead_source_id'] ?? '')) ?></div></div>
                            <div class="view-row"><div class="view-label">Stage</div><div class="view-value">
                                <span id="stageNameText"><?= text_or_na($stageName !== '' ? $stageName : ($row['stage_id'] ?? '')) ?></span>
                                <a href="javascript:void(0);" id="openStageModalBtn" class="ml-2 text-primary stage-edit-icon" title="Change Stage"><i class="fa fa-edit"></i></a>
                            </div></div>
                            <div class="view-row"><div class="view-label">Proof Engagement</div><div class="view-value"><?= text_or_na($proofEngagementName !== '' ? $proofEngagementName : ($row['proof_engagement_id'] ?? '')) ?></div></div>
                        </div>
                        <div class="col-md-6">
                            <div class="view-row"><div class="view-label">Partner</div><div class="view-value">
                                <?php if ($partnerId > 0) { ?>
                                    <?= text_or_na($partnerName !== '' ? $partnerName : $partnerId) ?>
                                    <!-- $canPerformActions && $currentRoleType !== 'MNGR' && $currentRoleType !== 'USR' -->
                                    <?php if ($canPerformActions && $currentRoleType === 'ADMIN') { ?>
                                    <a href="add_partner.php?eid=<?= $partnerId ?>" target="_blank" rel="noopener" title="View Partner" class="ml-2 text-primary"><i class="fa fa-eye"></i></a>
                                    <?php } ?>
                                <?php } else { ?>
                                    N/A
                                <?php } ?>
                            </div></div>
                            <div class="view-row"><div class="view-label">Align To</div><div class="view-value">
                                <?php if ($alignToUserId > 0) { ?>
                                    <?= text_or_na($alignToUserName !== '' ? $alignToUserName : $alignToUserId) ?>
                                    <!-- $canPerformActions && $currentRoleType !== 'MNGR' && $currentRoleType !== 'USR' -->
                                    <?php if ($canPerformActions && $currentRoleType === 'ADMIN') { ?>
                                    <a href="edit_user.php?id=<?= $alignToUserId ?>" target="_blank" rel="noopener" title="View User" class="ml-2 text-primary"><i class="fa fa-eye"></i></a>
                                    <?php } ?>
                                <?php } else { ?>
                                    N/A
                                <?php } ?>
                            </div></div>
                            <div class="view-row"><div class="view-label">Upload File</div><div class="view-value">
                                <?php if (!empty($row['upload_file'])) {
                                    $fileExt = strtolower(pathinfo($row['upload_file'], PATHINFO_EXTENSION));
                                    $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
                                    if ($isImage) { ?>
                                        <div class="mt-1">
                                            <a href="<?= htmlspecialchars($row['upload_file'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">View uploaded file<?= $uploadExtension ? ' (' . htmlspecialchars($uploadExtension, ENT_QUOTES, 'UTF-8') . ')' : '' ?></a>
                                        </div>
                                        <img class="upload-preview-thumb mt-1" src="<?= htmlspecialchars($row['upload_file'], ENT_QUOTES, 'UTF-8') ?>" alt="Uploaded image">
                                    <?php } else { ?>
                                        <a href="<?= htmlspecialchars($row['upload_file'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">View uploaded file<?= $uploadExtension ? ' (' . htmlspecialchars($uploadExtension, ENT_QUOTES, 'UTF-8') . ')' : '' ?></a>
                                    <?php }
                                } else {
                                    echo 'No file uploaded';
                                } ?>
                            </div></div>
                        </div>
                    </div>

                    <?php if ($currentRoleType === 'ADMIN') { ?>
                    <h5 class="card-subtitle mt-3">Lead Actions</h5>
                    <div class="row mt-2">
                        
                        <div class="col-md-6">
                            <div class="view-row">
                                <div class="view-label">Approval</div>
                                <div class="view-value">
                                    <div class="approval-segment <?= ($isUsrRole || $isApprovalLocked) ? 'disabled' : '' ?>" id="approvalSegment" title="<?= $isApprovalLocked ? 'Now you cannot change the approval status because it is Approved now' : '' ?>">
                                        <div class="segmented" role="tablist" aria-label="Approval Status">
                                            <label class="pending <?= ($isApproved === 0 ? 'active' : '') ?>" data-value="0">
                                                <input type="radio" name="approvalRadio" value="0" autocomplete="off" <?= ($isApproved === 0 ? 'checked' : '') ?> <?= ($isUsrRole || $isApprovalLocked) ? 'disabled' : '' ?>>Pending
                                            </label>
                                            <label class="approved <?= ($isApproved === 1 ? 'active' : '') ?>" data-value="1">
                                                <input type="radio" name="approvalRadio" value="1" autocomplete="off" <?= ($isApproved === 1 ? 'checked' : '') ?> <?= ($isUsrRole || $isApprovalLocked) ? 'disabled' : '' ?>>Approve
                                            </label>
                                            <label class="rejected <?= ($isApproved === 2 ? 'active' : '') ?>" data-value="2">
                                                <input type="radio" name="approvalRadio" value="2" autocomplete="off" <?= ($isApproved === 2 ? 'checked' : '') ?> <?= ($isUsrRole || $isApprovalLocked) ? 'disabled' : '' ?>>Reject
                                            </label>
                                            <label class="onboard <?= ($isApproved === 3 ? 'active' : '') ?>" data-value="3">
                                                <input type="radio" name="approvalRadio" value="3" autocomplete="off" <?= ($isApproved === 3 ? 'checked' : '') ?> <?= ($isUsrRole || $isApprovalLocked) ? 'disabled' : '' ?>>Onhold
                                            </label>
                                        </div>
                                    </div>
                                    <!-- approvalActionBadge hidden for ADMIN users -->
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="view-row">
                                <div class="view-label">Opportunity</div>
                                <div class="view-value">
                                    <label class="switch-toggle <?= $isUsrRole ? 'toggle-not-allowed' : '' ?>">
                                        <input type="checkbox" id="toggleOpportunitySwitch" <?= $isOpportunity ? 'checked' : '' ?> <?= $isUsrRole ? 'disabled' : '' ?>>
                                        <span class="switch-toggle-slider"></span>
                                    </label>
                                    <!-- <span id="opportunityToggleText" class="toggle-text"><?= $isOpportunity ? 'Yes' : 'No' ?></span> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } else { ?>
                    <h5 class="card-subtitle mt-3">Lead Actions</h5>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="view-row"><div class="view-label">Approval</div><div class="view-value"><?php
                                $apText = ($isApproved === 1) ? 'Approved' : (($isApproved === 2) ? 'Rejected' : (($isApproved === 3) ? 'Onhold' : 'Pending'));
                                $apClass = ($isApproved === 1) ? 'status-approved' : (($isApproved === 2) ? 'status-rejected' : (($isApproved === 3) ? 'status-onboard' : 'status-pending'));
                                echo '<span id="approvalActionBadge" class="status-badge ' . $apClass . '">' . htmlspecialchars($apText, ENT_QUOTES, 'UTF-8') . '</span>';
                            ?></div></div>
                        </div>
                        <div class="col-md-6">
                            <div class="view-row"><div class="view-label">Opportunity</div><div class="view-value"><?= $isOpportunity ? 'Yes' : 'No' ?></div></div>
                        </div>
                    </div>
                    <?php } ?>

                    <h5 class="card-subtitle mt-3">System Details</h5>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="view-row"><div class="view-label">Lead ID</div><div class="view-value"><?= text_or_na($row['id'] ?? '') ?></div></div>

                            <div class="view-row"><div class="view-label">Created By  </div><div class="view-value">
                                <?= text_or_na($createdByUserName !== '' ? ucwords($createdByUserName) : ($row['created_by'] ?? '')) ?>
                                <!-- $canPerformActions && $currentRoleType === 'ADMIN' && !empty($row['created_by']) -->
                                <?php if ($canPerformActions && $currentRoleType === 'ADMIN' && !empty($row['created_by'])) { ?>
                                    <a href="edit_user.php?id=<?= (int)$row['created_by'] ?>" target="_blank" rel="noopener" title="View User" class="ml-2 text-primary"><i class="fa fa-eye"></i></a>
                                <?php } ?>
                            </div></div>
                            
                            <div class="view-row"><div class="view-label">Status</div><div class="view-value"><?= $statusText ?></div></div>
                            <div class="view-row"><div class="view-label">Approved</div><div class="view-value"><?= $isApproved ? 'Yes' : 'No' ?></div></div>
                            <?php if ($showExpiryInfo) { ?>
                            <div class="view-row"><div class="view-label">Expiry Date</div><div class="view-value"><?= $expiryDateText ?></div></div>
                            <div class="view-row"><div class="view-label">Expire In</div><div class="view-value"><?= $expireInText ?></div></div>
                            <?php } ?>
                        </div>
                        <div class="col-md-6">
                            <?php if ($canPerformActions) { ?>
                            <div class="view-row"><div class="view-label">Opportunity</div><div class="view-value"><?= $isOpportunity ? 'Yes' : 'No' ?></div></div>
                            <?php } ?>
                            <div class="view-row"><div class="view-label">Created At</div><div class="view-value"><?= !empty($row['created_at']) ? date('d-m-Y h:i:s A', strtotime($row['created_at'])) : 'N/A' ?></div></div>
                            <div class="view-row"><div class="view-label">Updated At</div><div class="view-value"><?= !empty($row['updated_at']) ? date('d-m-Y h:i:s A', strtotime($row['updated_at'])) : 'N/A' ?></div></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="text-center mt-3">
                <?php if ($canEditLead) { ?>
                <a href="add_order.php?eid=<?= (int)$row['id'] ?>" class="btn btn-primary" style="margin-bottom:20px">Edit</a>
                <?php } ?>
                <button type="button" onclick="javascript:history.go(-1)" class="btn btn-danger" style="margin-bottom:20px">Back</button>
            </div>
        </div>
    </div>
</div>

<div id="globalAjaxLoader" class="global-ajax-loader" aria-hidden="true">
    <div class="loader-card">
        <span class="loader-spinner"></span>
        <span id="globalAjaxLoaderText">Please wait...</span>
    </div>
</div>

<div class="modal fade" id="uploadPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="uploadPreviewImage" src="" alt="Preview" style="max-width: 100%; height: auto;">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="stageUpdateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Stage</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="stageSelectModal" class="mb-1">Stage</label>
                <select id="stageSelectModal" class="form-control">
                    <option value="">---Select---</option>
                    <?php foreach ($stageOptions as $stageOption) {
                        $selected = ((int)$stageOption['id'] === (int)($row['stage_id'] ?? 0)) ? 'selected="selected"' : '';
                    ?>
                        <option value="<?= (int)$stageOption['id'] ?>" <?= $selected ?>><?= htmlspecialchars($stageOption['name'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="saveStageBtn" class="btn btn-primary">Update Stage</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="callLogModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="">
                <input type="hidden" id="call_log_id" name="call_log_id" value="0">
                <div class="modal-header">
                    <h5 class="modal-title" id="callLogModalTitle">Add Call Log</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="call_subject">Call Subject <span class="text-danger">*</span></label>
                        <select id="call_subject" name="call_subject" class="form-control" required>
                            <option value="">---Select---</option>
                            <?php foreach ($callSubjectOptions as $subjectOption) { ?>
                                <option value="<?= htmlspecialchars($subjectOption, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($subjectOption, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label for="call_description">Description <span class="text-danger">*</span></label>
                        <textarea id="call_description" name="call_description" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit_call_log" value="1" id="callLogSubmitBtn" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php') ?>

<script>
    $(document).ready(function(){
        var leadId = <?= (int)$row['id'] ?>;
        var isApproved = <?= (int)$isApproved ?>;
        var isOpportunity = <?= (int)$isOpportunity ?>;
        var canPerformActions = <?= $canPerformActions ? 'true' : 'false' ?>;
        var isUsrRole = <?= $isUsrRole ? 'true' : 'false' ?>;
        var isAdmin = <?= ($currentRoleType === 'ADMIN') ? 'true' : 'false' ?>;
        var currentStageId = <?= (int)($row['stage_id'] ?? 0) ?>;
        var isApprovalUpdating = false;
        var minLoaderDurationMs = 2500;

        function setGlobalAjaxLoading(isLoading, message) {
            if (isLoading) {
                $('#globalAjaxLoaderText').text(message || 'Please wait...');
                $('#globalAjaxLoader').css('display', 'flex');
            } else {
                $('#globalAjaxLoader').hide();
            }
        }

        // Reusable global helpers for any AJAX operation in this page.
        window.showAjaxLoader = function(message) {
            setGlobalAjaxLoading(true, message);
        };
        window.hideAjaxLoader = function() {
            setGlobalAjaxLoading(false);
        };

        function finishAfterMinLoader(startTime, done) {
            var elapsed = Date.now() - startTime;
            var wait = Math.max(0, minLoaderDurationMs - elapsed);
            setTimeout(done, wait);
        }

        function setApprovalLoading(isLoading) {
            isApprovalUpdating = isLoading;
            if (isLoading) {
                showAjaxLoader('Updating approval, please wait...');
            } else {
                hideAjaxLoader();
            }
            $('input[name="approvalRadio"]').prop('disabled', isLoading || (parseInt(isApproved, 10) === 1));
        }

        setTimeout(function() {
            $('#leadLogsUsrHint').fadeOut();
        }, 10000);

        var callLogStatus = '<?= htmlspecialchars($_GET['calllog'] ?? '', ENT_QUOTES, 'UTF-8') ?>';
        if (callLogStatus === 'success') {
            swal('Success', 'Call log added successfully.', 'success');
        } else if (callLogStatus === 'updated') {
            swal('Success', 'Call log updated successfully.', 'success');
        } else if (callLogStatus === 'error') {
            swal('Error', 'Failed to add call log.', 'error');
        } else if (callLogStatus === 'invalid') {
            swal('Warning', 'Please fill required fields.', 'warning');
        }

        if (callLogStatus !== '') {
            var cleanUrl = new URL(window.location.href);
            cleanUrl.searchParams.delete('calllog');
            window.history.replaceState({}, document.title, cleanUrl.toString());
        }

        $('.call-log-read-more').on('click', function() {
            var $link = $(this);
            var $container = $link.closest('td');
            var $shortText = $container.find('.call-log-desc-short');
            var $fullText = $container.find('.call-log-desc-full');
            var isExpanded = $fullText.is(':visible');

            if (isExpanded) {
                $fullText.hide();
                $shortText.show();
                $link.text('Read more');
            } else {
                $shortText.hide();
                $fullText.show();
                $link.text('Read less');
            }
        });

        $('.lead-comment-toggle').on('click', function() {
            var $link = $(this);
            var $container = $link.closest('.view-value');
            var $shortText = $container.find('.lead-comment-short');
            var $fullText = $container.find('.lead-comment-full');
            var isExpanded = $fullText.is(':visible');

            if (isExpanded) {
                $fullText.hide();
                $shortText.show();
                $link.text('Read more');
            } else {
                $shortText.hide();
                $fullText.show();
                $link.text('Read less');
            }
        });

        $('.edit-call-log-btn').on('click', function() {
            var callLogId = $(this).data('id') || 0;
            var callSubject = $(this).data('subject') || '';
            var callDescription = $(this).data('description') || '';

            $('#call_log_id').val(callLogId);
            $('#call_subject').val(callSubject);
            $('#call_description').val(callDescription);
            $('#callLogModalTitle').text('Edit Call Log');
            $('#callLogSubmitBtn').text('Update');
            $('#callLogModal').modal('show');
        });

        $('#callLogModal').on('hidden.bs.modal', function() {
            $('#call_log_id').val(0);
            $('#call_subject').val('');
            $('#call_description').val('');
            $('#callLogModalTitle').text('Add Call Log');
            $('#callLogSubmitBtn').text('Submit');
        });

        function setApprovalUI() {
            var isLocked = (parseInt(isApproved, 10) === 1);
            // Map numeric states to labels and badge classes
            var state = parseInt(isApproved, 10) || 0;
            var label = 'Pending';
            var badgeClass = 'status-pending';
            if (state === 1) { label = 'Approved'; badgeClass = 'status-approved'; }
            else if (state === 2) { label = 'Rejected'; badgeClass = 'status-rejected'; }
            else if (state === 3) { label = 'Onhold'; badgeClass = 'status-onboard'; }
            else { label = 'Pending'; badgeClass = 'status-pending'; }

            // top badge
            $('#approvalBadge').removeClass('status-pending status-approved status-rejected status-onboard').addClass(badgeClass).text(label);

            // inline action badge (near control) if present
            var $actionBadge = $('#approvalActionBadge');
            if ($actionBadge.length) {
                $actionBadge.removeClass('status-pending status-approved status-rejected status-onboard').addClass(badgeClass).text(label);
            }

            // update segmented control active state and accessibility
            var $labels = $('#approvalSegment .segmented label');
            $labels.removeClass('active').attr('aria-pressed', 'false');
            $labels.each(function(){
                var $lab = $(this);
                var val = parseInt($lab.data('value'), 10);
                if (val === state) {
                    $lab.addClass('active').attr('aria-pressed', 'true');
                    $lab.find('input[name="approvalRadio"]').prop('checked', true).prop('disabled', isLocked);
                } else {
                    $lab.find('input[name="approvalRadio"]').prop('checked', false).prop('disabled', isLocked);
                }
            });

            // toggle container highlight
            var $approvalWrap = $('#approvalSegment');
            if (isLocked) {
                $approvalWrap.addClass('disabled');
                $approvalWrap.attr('title', 'Now you cannot change the approval status because it is Approved now');
            } else {
                $approvalWrap.removeClass('disabled');
                $approvalWrap.attr('title', '');
            }
            if ($approvalWrap.find('.segmented label.active').length) {
                $approvalWrap.addClass('active-container');
            } else {
                $approvalWrap.removeClass('active-container');
            }
        }

        function setOpportunityUI() {
            var opportunitySwitch = $('#toggleOpportunitySwitch');
            if (isOpportunity) {
                opportunitySwitch.prop('checked', true);
                $('#opportunityToggleText').text('Yes');
                $('#opportunityBadge').removeClass('opportunity-no').addClass('opportunity-yes').text('Opportunity: Yes');
            } else {
                opportunitySwitch.prop('checked', false);
                $('#opportunityToggleText').text('No');
                $('#opportunityBadge').removeClass('opportunity-yes').addClass('opportunity-no').text('Opportunity: No');
            }
        }

        setApprovalUI();
        if (canPerformActions && isAdmin) {
            setOpportunityUI();
        }

        $('.upload-preview-thumb').on('click', function(){
            var src = $(this).attr('src');
            if (src) {
                $('#uploadPreviewImage').attr('src', src);
                $('#uploadPreviewModal').modal('show');
            }
        });

        $('#openStageModalBtn').on('click', function(){
            $('#stageSelectModal').val(String(currentStageId));
            $('#stageUpdateModal').modal('show');
        });

        if (canPerformActions && isAdmin) {
            // Handle segmented radio change for approval (values 0..3)
            $(document).on('change', 'input[name="approvalRadio"]', function(){
                if (isApprovalUpdating) {
                    return;
                }

                var $input = $(this);
                var newStatus = parseInt($input.val(), 10);
                var previousState = parseInt(isApproved, 10) || 0;
                var stateLabel = (newStatus === 1) ? 'Approve' : (newStatus === 2) ? 'Reject' : (newStatus === 3) ? 'Onhold' : 'Pending';
                var confirmText = 'Are you sure you want to set this lead to "' + stateLabel + '"?';
                var isHtmlText = false;

                if (newStatus === 1) {
                    confirmText = 'Are you sure you want to set this lead to "' + stateLabel + '"?<br><br><span class="approval-note-red">Note: After approve status you cannot change it again.</span>';
                    isHtmlText = true;
                }

                swal({
                    title: 'Change Approval Status?',
                    text: confirmText,
                    html: isHtmlText,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonClass: 'btn-warning',
                    confirmButtonText: 'Yes, Continue',
                    cancelButtonText: 'Cancel',
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function(isConfirm){
                    if(isConfirm){
                        if (typeof swal.close === 'function') {
                            swal.close();
                        }
                        setApprovalLoading(true);
                        var approvalLoaderStart = Date.now();
                        $.ajax({
                            type: 'POST',
                            url: 'ajax_update.php',
                            data: {
                                action: 'update_approval',
                                lead_id: leadId,
                                is_approved: newStatus
                            },
                            dataType: 'json',
                            success: function(response){
                                finishAfterMinLoader(approvalLoaderStart, function() {
                                    setApprovalLoading(false);
                                    if(response.status === 'success'){
                                        isApproved = newStatus;
                                        setApprovalUI();
                                        swal('Success', response.message, 'success');
                                        setTimeout(function(){ window.location.reload(); }, 1500);
                                    } else {
                                        // revert selection
                                        setTimeout(function(){
                                            isApproved = previousState;
                                            setApprovalUI();
                                        }, 10);
                                        swal('Error', response.message, 'error');
                                    }
                                });
                            },
                            error: function(){
                                finishAfterMinLoader(approvalLoaderStart, function() {
                                    setApprovalLoading(false);
                                    isApproved = previousState;
                                    setApprovalUI();
                                    swal('Error', 'Something went wrong. Please try again.', 'error');
                                });
                            }
                        });
                    } else {
                        // revert selection
                        setTimeout(function(){
                            isApproved = previousState;
                            setApprovalUI();
                        }, 10);
                    }
                });
            });
        }

        if (canPerformActions && isAdmin) {
        $('#toggleOpportunitySwitch').on('change', function(){
            var newStatus = $(this).is(':checked') ? 1 : 0;

            // Prevent changing opportunity unless lead is Approved (1)
            if (parseInt(isApproved, 10) !== 1) {
                swal('Warning', 'Only approved leads can be marked as opportunity.', 'warning');
                // revert to previous state
                $(this).prop('checked', !!isOpportunity);
                return;
            }

            var actionText = newStatus ? 'save as opportunity' : 'remove from opportunity';

            swal({
                title: 'Change Opportunity Status?',
                text: 'Are you sure you want to ' + actionText + ' this lead?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'btn-success',
                confirmButtonText: 'Yes, Continue',
                cancelButtonText: 'Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if(isConfirm){
                    showAjaxLoader('Updating opportunity, please wait...');
                    var opportunityLoaderStart = Date.now();
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_update.php',
                        data: {
                            lead_id: leadId,
                            action: 'update_opportunity',
                            is_opportunity: newStatus
                        },
                        dataType: 'json',
                        success: function(response){
                            finishAfterMinLoader(opportunityLoaderStart, function() {
                                hideAjaxLoader();
                                if(response.status === 'success'){
                                    isOpportunity = newStatus;
                                    setOpportunityUI();
                                    swal('Success', response.message, 'success');
                                    setTimeout(function(){ window.location.reload(); }, 1500);
                                } else {
                                    swal('Error', response.message, 'error');
                                }
                            });
                        },
                        error: function(){
                            finishAfterMinLoader(opportunityLoaderStart, function() {
                                hideAjaxLoader();
                                $('#toggleOpportunitySwitch').prop('checked', !!isOpportunity);
                                swal('Error', 'Something went wrong. Please try again.', 'error');
                            });
                        }
                    });
                } else {
                    $('#toggleOpportunitySwitch').prop('checked', !!isOpportunity);
                }
            });
        });
        }

        $('#saveStageBtn').on('click', function(){
            var stageId = parseInt($('#stageSelectModal').val(), 10) || 0;

            if (stageId <= 0) {
                swal('Warning', 'Please select a stage.', 'warning');
                return;
            }

            if (stageId === currentStageId) {
                swal('Info', 'Selected stage is already set.', 'info');
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'ajax_update.php',
                data: {
                    action: 'update_stage',
                    lead_id: leadId,
                    stage_id: stageId
                },
                dataType: 'json',
                beforeSend: function() {
                    showAjaxLoader('Updating stage, please wait...');
                    $('#saveStageBtn').data('loader-start', Date.now());
                },
                success: function(response){
                    var stageLoaderStart = parseInt($('#saveStageBtn').data('loader-start'), 10) || Date.now();
                    finishAfterMinLoader(stageLoaderStart, function() {
                        hideAjaxLoader();
                        if(response.status === 'success'){
                            currentStageId = stageId;
                            $('#stageNameText').text($('#stageSelectModal option:selected').text());
                            $('#stageUpdateModal').modal('hide');
                            swal('Success', response.message, 'success');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        } else {
                            swal('Error', response.message, 'error');
                        }
                    });
                },
                error: function(){
                    var stageLoaderStart = parseInt($('#saveStageBtn').data('loader-start'), 10) || Date.now();
                    finishAfterMinLoader(stageLoaderStart, function() {
                        hideAjaxLoader();
                        swal('Error', 'Something went wrong. Please try again.', 'error');
                    });
                }
            });
        });
    });
</script>
