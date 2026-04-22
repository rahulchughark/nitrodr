<?php
require_once "includes/include.php";
require_once "helpers/DataController.php";

$dataObj = new DataController();

$PROJECT_ID = "68abf5ca7d30730c67382ce8";
$FAILED_STATUS = 2;
$SENT_STATUS   = 1;


/**
 * STEP 6: Sync phone-number-wise FAILED status & mark processed
 */
$syncSql = "
    SELECT id, campaign
    FROM tbl_master_campaign
    WHERE is_failed_processed = 0
";
$syncRes = db_query($syncSql);

// print_r($syncRes);
// exit;

while ($row = mysqli_fetch_assoc($syncRes)) {

    $mstId      = $row['id'];
    $campaignId = $row['campaign'];

   $d = $dataObj->syncAisensyFailedMessagesStatus(
        $PROJECT_ID,
        $campaignId,
        $mstId
    );


    db_query("
        UPDATE tbl_master_campaign
        SET is_failed_processed = 1
        WHERE id = '{$mstId}'
    ");
}



echo "status updated successfully";