<?php
include_once('helpers/DataController.php');
$dataObj = new DataController();
$projectID = "68abf5ca7d30730c67382ce8";
$pswdKey = "a850dc5d98af7292567f1";

// Read DataTables post input
$draw   = $_POST['draw'];
$start  = $_POST['start'];
$length = $_POST['length'];

// Fetch ONLY the National Campaign Broadcast campaign
$campaigns = $dataObj->getAISensyCampaignList($projectID, $pswdKey);



// if (!empty($campaigns['status']) && !empty($campaigns['data'])) {

//     foreach ($campaigns['data'] as $camp) {

//         $campaignID   = $camp['id'];
//         $campaignName = addslashes($camp['name']);

//         // Map API status → local status
//         // $status = ($camp['status'] === 'LIVE') ? 'created' : 'failed';

//         // 🔍 Check if campaign already exists
//         $checkSql = "
//             SELECT id 
//             FROM tbl_mst_campaign 
//             WHERE campaign_id = '$campaignID'
//             LIMIT 1
//         ";
//         $checkRes = db_query($checkSql);

//         if (mysqli_num_rows($checkRes) > 0) {
//             continue; // skip duplicates
//         }

//         // ✅ Insert campaign
//         $insertSql = "
//             INSERT INTO tbl_mst_campaign
//             (parent_campaign, campaign_name, campaign_id, is_parent)
//             VALUES
//             (NULL, '$campaignName', '$campaignID', 0)
//         ";

//         db_query($insertSql);
//     }
// }


$campaignsData = $campaigns['data'] ?? [];

// echo "<pre>";
// print_r($campaignsData);
// exit;
// Convert to list so DataTables works
$allData = $campaignsData ? $campaignsData : [];

// echo "<pre>";
// print_r($allData);
// exit;

$totalRecords = count($allData);

// Slice (unnecessary for 1 record but required for DataTables)
$data = array_slice($allData, $start, $length);

// Prepare rows
$final = [];
$sno = $start + 1;


foreach ($data as $row) {  
     $isParent = $dataObj->getCampaignByCampaignId($row['id'],'is_parent');
     $isRun = $dataObj->getCampaignByCampaignId($row['id'],'is_run');

    if($isParent){
    $viewButton = "
            <button 
                type='button'
                class='btn btn-sm btn-primary'
                onclick=\"openImportModal(
                    '{$row['id']}',
                    '" . htmlspecialchars($row['name'], ENT_QUOTES) . "'
                )\"
            >
                Import
            </button>
        ";

    

    
    }else{
    $viewButton = "";
    
    }


    if($isParent && $isRun){
    $contactButton = "
            <a href='campaign-contacts.php?campaign_id={$row['id']}'
            class='text-primary font-weight-bold'            
            >
                View
            </a>
        ";
    }else{
    $contactButton = "
            <a class='text-primary font-weight-bold text-muted'
            style='cursor: not-allowed; '>
                View
            </a>
        ";
    }



     if(!$isRun){
        $runButton = "
                <button 
                    type='button'
                    class='btn btn-sm btn-primary'
                    onclick=\"runCampaign(
                        '{$row['id']}',
                        '" . htmlspecialchars($row['name'], ENT_QUOTES) . "'
                    )\"
                >
                    Run
                </button>
            ";
        }else{
        $runButton =  "
                <button 
                    type='button'
                    class='btn btn-sm btn-primary'
                >
                    Already
                </button>
            ";;
        }
    
    $templateText = $row['message_payload']['template']['text'] ?? '';
    $words = explode(' ', $templateText);

    $shortText = implode(' ', array_slice($words, 0, 20));
    $fullText  = $templateText;

    $final[] = [
        "sno"             => $sno++,       
        "campaign_name"   => $row['name'] ?? '',    
        "audience_size"   => $row['audience_size'] ?? '',
        "latest_fail"     => 0,
        "total_sent"     =>  0,
        "created_at" => isset($row['created_at'])
                        ? date("Y-m-d H:i:s", $row['created_at'] / 1000)
                        : '',
        "contacts"        => $contactButton,
        "template_text" => "
            <span class='short-text'>{$shortText}...</span>
            <span class='full-text d-none'>{$fullText}</span>
            <a href='javascript:void(0)' class='toggle-text text-primary'>Show more</a>
        ",
        "action_btn"      => $runButton.$viewButton
    ];
}

// Output JSON for DataTables
echo json_encode([
    "draw"            => intval($draw),
    "recordsTotal"    => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data"            => $final
]);
exit;
?>
