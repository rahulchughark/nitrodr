<?php
include("includes/include.php");
require 'vendor/autoload.php';

$response = null;

/*
CSV Columns expected:
A: Name
B: Mobile Number
C: Sent At
D: Failure Reason
E: mst_id
F: campaign_id
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {

    if ($_FILES['csv_file']['error'] !== 0) {
        $response = ['status' => false, 'message' => 'CSV file upload failed'];
        echo json_encode($response);
        exit;
    } else {

        $handle = fopen($_FILES['csv_file']['tmp_name'], 'r');
        if (!$handle) {
            $response = ['status' => false, 'message' => 'Unable to read CSV'];     
            echo json_encode($response);
            exit;
        } else {

            $header = fgetcsv($handle);
            $header = array_map(fn($h) => strtolower(trim($h)), $header);


            

            $nameIndex    = array_search('name', $header);
            $phoneIndex    = array_search('mobile number', $header);
            $reasonIndex   = array_search('failure reason', $header);
            $mstIndex      = array_search('mst_id', $header);
            $campaignIndex = array_search('campaign_id', $header);
  

           
            if ($phoneIndex === false || $reasonIndex === false || $mstIndex === false || $campaignIndex === false) {
                $response = ['status' => false, 'message' => 'Invalid CSV format'];
                echo json_encode($response);
                exit;
            } else {

                

                $inserted = 0;


                while (($row = fgetcsv($handle)) !== false) {

                                                          
                    $rawPhone   = preg_replace('/\D/', '', $row[$phoneIndex] ?? '');
                    $name     = trim($row[$nameIndex] ?? '');
                    $reason     = trim($row[$reasonIndex] ?? '');
                    $mstId      = intval($row[$mstIndex] ?? 0);
                    $campaignId = trim($row[$campaignIndex] ?? '');

                    if (!$rawPhone || strlen($rawPhone) < 12 || !$mstId || !$campaignId) {
                        continue;
                    }

                    $phoneCode = substr($rawPhone, 0, 2);
                    $phone     = substr($rawPhone, 2);

                    if (!preg_match('/^[0-9]{10}$/', $phone)) {
                        continue;
                    }


                    // Status mapping
                    if (
                        $reason === "User's number is part of an experiment" ||
                        $reason === "Message undeliverable"
                    ) {
                        $status = 3;
                    } elseif (
                        $reason === "This message was not delivered to maintain healthy ecosystem engagement."
                    ) {
                        $status = 2;
                    }elseif (
                        $reason === "sent"
                    ) {
                        $status = 1;
                    } else {
                        $status = 0;
                    }

               

                    db_query("
                        INSERT INTO tbl_campaign_contact_attempts
                        (mst_id, campaign_id,name, contacts, phone_code, status, remark, created_at)
                        VALUES
                        (
                            '{$mstId}',
                            '{$campaignId}',
                            '{$name}',
                            '{$phone}',
                            '{$phoneCode}',
                            '{$status}',
                            '".db_escape($reason)."',
                            NOW()
                        )
                    ");

                    $inserted++;
                    // die("done");
                   
                }

                fclose($handle);

                $response = [
                    'status'  => true,
                    'message' => "{$inserted} records imported successfully"
                ];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Import Failed Contacts</title>
    
</head>
<body>

<div class="box">
    <h3>Import Failed Contacts (CSV)</h3>

    <?php if ($response): ?>
        <div class="msg <?= $response['status'] ? 'success' : 'error' ?>">
            <?= htmlspecialchars($response['message']) ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="file" name="csv_file" accept=".csv" required>
        <button type="submit">Import CSV</button>
    </form>
</div>

</body>
</html>
