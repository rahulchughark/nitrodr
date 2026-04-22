<?php
include("includes/include.php");
require 'vendor/autoload.php';
include_once('helpers/DataController.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
$dataObj = new DataController;


$phoneNumberStatus = $_POST['isPhoneStatus'] ?? 0;
$projectID = "68abf5ca7d30730c67382ce8";

if($phoneNumberStatus){
$campaignId = $_POST['campaign_id'] ?? '';
$mstId = $_POST['mst_id'] ?? '';
    

$d = $dataObj->syncAisensyFailedMessagesStatus(
    $projectID, // project_id
    $campaignId,        // campaign_id
    $mstId               // mst_id
    );

    if ($mstId) {
        db_query("
            UPDATE tbl_master_campaign
            SET is_failed_processed = 1
            WHERE id = '{$mstId}'
        ");
    }

     // Return JSON success message
    echo json_encode([
        'status'  => true,
        'message' => 'Failed messages synced successfully',
        'mst_id'  => $mstId,
        'campaign_id' => $campaignId,
        'isPhoneStatus' => $phoneNumberStatus
    ]);
    exit;

}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => false, 'message' => 'Invalid request']);
    exit;
}

/* ---------------- BASIC VALIDATION ---------------- */

$campaignName = trim($_POST['campaign_name'] ?? '');
$categoryId   = $_POST['category_id'] ?? '';
$tagId        = $_POST['tag_id'] ?? '';
$templateId = $_POST['template_id'] ?? '';
$projectID = "68abf5ca7d30730c67382ce8";
$pswdKey = "a850dc5d98af7292567f1";

if ($campaignName === '') {
    echo json_encode(['status' => false, 'message' => 'Campaign name is required']);
    exit;
}

if ($templateId === '') {
    echo json_encode(['status' => false, 'message' => 'Template is required']);
    exit;
}

if (!isset($_FILES['contacts_file']) || $_FILES['contacts_file']['error'] !== 0) {
    echo json_encode(['status' => false, 'message' => 'Contacts file is required']);
    exit;
}

$fileTmp  = $_FILES['contacts_file']['tmp_name'];
$fileName = $_FILES['contacts_file']['name'];
$fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

if (!in_array($fileExt, ['xls', 'xlsx'])) {
    echo json_encode(['status' => false, 'message' => 'Invalid file format']);
    exit;
}

try {

    /* ---------------- READ EXCEL ---------------- */

    $spreadsheet = IOFactory::load($fileTmp);
    $sheet       = $spreadsheet->getActiveSheet();
    $rows        = $sheet->toArray();

    if (count($rows) < 2) {
        throw new Exception('Excel file is empty');
    }

$header = array_map(function ($h) {
    $h = strtolower(trim($h));
    $h = preg_replace('/\x{00A0}/u', '', $h); // remove hidden excel spaces
    return $h;
}, $rows[0]);


$expectedHeader = ['name', 'code', 'phone'];

if ($header !== $expectedHeader) {
    throw new Exception('Excel format must be exactly: name, code, phone');
}


    // Header normalization
    $header = array_map(fn($h) => strtolower(trim($h)), $rows[0]);

    $nameIndex = array_search('name', $header, true);
    $phoneIndex = array_search('phone', $header);
    $codeIndex  = array_search('code', $header);

  
    if ($nameIndex === false) {
      throw new Exception('Excel must contain name column');
        }

    if ($phoneIndex === false) {
        throw new Exception('Excel must contain phone column');
    }

    if ($codeIndex === false) {
            throw new Exception('Excel must contain code column');
        }

    $dataRows = array_slice($rows, 1);

    if (count($dataRows) > 500) {
        throw new Exception('Maximum 500 contacts allowed per import');
    }


   
    /* ---------------- INSERT CAMPAIGN ---------------- */

    // $aisensyCampaignId = $dataObj->createAISensyCampaign("68abf5ca7d30730c67382ce8", $templateId, $campaignName);
    // $aisensyCampaignId = $aisensyCampaignId['response']['id'] ?? '';

    // if(!$aisensyCampaignId){
    //    echo json_encode(['status' => false, 'message' => 'Campaign Creation Failed']);
    //    exit; 
    // }

    $sqlCampaign = "
    INSERT INTO tbl_master_campaign (name, template, is_parent, created_at)
    VALUES ('{$campaignName}', '{$templateId}', 1,  NOW())
        ";
        db_query($sqlCampaign);

        $campaignId = get_insert_id();

        if (!$campaignId) {
            throw new Exception('Local campaign creation failed');
        }

    /* ---------------- INSERT CONTACTS ---------------- */

    $inserted = 0;

    foreach ($dataRows as $row) {

        $name  = trim($row[$nameIndex] ?? '');
        $phone = trim($row[$phoneIndex] ?? '');
        $code  = trim($row[$codeIndex] ?? '');


        if ($phone === '') {
            continue;
        }

        // Normalize phone
        $phone = preg_replace('/\D/', '', $phone);
        $code = preg_replace('/\D/', '', $code);

        if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
            continue;
        }

        $sql = "
            INSERT INTO tbl_campaign_contact_attempts
            (mst_id, category_id, tag_id,name, contacts,phone_code, status, created_at)
            VALUES
            ('{$campaignId}', '{$categoryId}', '{$tagId}', '{$name}', '{$phone}', '{$code}', 0, NOW())
        ";
        db_query($sql);
        $inserted++;
    }

 

    // This Create campaign on AI sensy
    $aisensyCampaignId = $dataObj->syncCampaignToAISensy(
        $campaignId,
        $templateId,
        $campaignName   
        );

    if (!$aisensyCampaignId) {
        echo json_encode([
            'status'  => true,
            'message' => "Campaign created locally. {$inserted} contacts imported, but AI Sensy campaign creation failed."
        ]);
        exit;
    }

    $dataObj->updateCampaignIdInContactAttempts(
                                                $campaignId,
                                                $aisensyCampaignId,
                                                $campaignName
                                                        );


    echo json_encode([
    'status'      => true,
    'message'     => "Campaign created. {$inserted} contacts imported.",
    'mst_id'      => $campaignId,
    'campaign_id' => $aisensyCampaignId
    ]);

        } catch (Exception $e) {

            echo json_encode([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
