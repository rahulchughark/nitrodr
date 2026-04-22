<?php
require_once "includes/include.php";
require_once "helpers/DataController.php";

$dataObj = new DataController();

$PROJECT_ID = "68abf5ca7d30730c67382ce8";
$FAILED_STATUS = 2;
$SENT_STATUS   = 1;

/**
 * STEP 1: Get all parent campaigns
 */
$parentSql = "
    SELECT id, name, template, campaign AS master_ai_campaign_id
    FROM tbl_master_campaign
    WHERE is_parent = 1
";
$parentRes = db_query($parentSql);

while ($parent = mysqli_fetch_assoc($parentRes)) {

    $parentId           = $parent['id'];
    $parentName         = $parent['name'];
    $templateId         = $parent['template'];
    $masterAiCampaignId = $parent['master_ai_campaign_id'];

    /**
     * STEP 2: Get FAILED contacts of parent campaign
     */
    $failedSql = "
        SELECT id,name, contacts, phone_code
        FROM tbl_campaign_contact_attempts
        WHERE mst_id = '{$parentId}'
        AND status = '{$FAILED_STATUS}'
        AND campaign_id = (
            SELECT MAX(campaign_id)
            FROM tbl_campaign_contact_attempts
            WHERE mst_id = '{$parentId}'
        )
    ";
    $failedRes = db_query($failedSql);

    
    if (!$failedRes || mysqli_num_rows($failedRes) === 0) {
        continue;
    }



    // echo "<pre>";
    // echo $parentName;
    // print_r(mysqli_fetch_assoc($failedRes));
    // echo "</pre>";

    // exit;

      /**
     * STEP 3: Create retry campaign
     */
    $retryCampaignName = "retry-".$parentName."-".date("dmYHis");

    $aiCampaign = $dataObj->createAISensyCampaign(
        $PROJECT_ID,
        $templateId,
        $retryCampaignName
    );

    $newAiCampaignId = $aiCampaign['response']['id'] ?? null;
    if (!$newAiCampaignId) {
        continue;
    }

    /**
     * STEP 4: Insert new campaign (child)
     */
    db_query("
        INSERT INTO tbl_master_campaign
        (
            name,
            template,
            campaign,
            mst_campaign,
            is_parent,
            created_at
        )
        VALUES
        (
            '{$retryCampaignName}',
            '{$templateId}',
            '{$newAiCampaignId}',
            '{$masterAiCampaignId}',
            0,
            NOW()
        )
    ");

    $newLocalCampaignId = get_insert_id();
    if (!$newLocalCampaignId) {
        continue;
    }
       

    /**
     * STEP 5: Send message + insert new contact attempts
     */
    while ($row = mysqli_fetch_assoc($failedRes)) {

        $phone     = $row['contacts'];
        $phoneCode = $row['phone_code'];
        $name      = $row['name'];

        $send = $dataObj->sendAISensyMessage(
            $PROJECT_ID,
            $phone,
            "rahul",
            $retryCampaignName
        );


        // echo "<pre>";
        // print_r($send);
        // echo "</pre>";
        // exit;

     
        if (!empty($send['status'])) {

            db_query("
                INSERT INTO tbl_campaign_contact_attempts
                (
                    mst_id,
                    mst_campaign,
                    campaign_id,
                    contacts,
                    phone_code,
                    status,
                    created_at
                )
                VALUES
                (
                    '{$parentId}',
                    '{$masterAiCampaignId}',
                    '{$newAiCampaignId}',
                    '{$phone}',
                    '{$phoneCode}',
                    '{$SENT_STATUS}',
                    NOW()
                )
            ");
        }
    }
  


}




echo "Retry campaign cron executed successfully\n"; 