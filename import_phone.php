<?php
include("includes/include.php");
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => false, 'message' => 'Invalid request']);
    exit;
}

$campaignId = $_POST['campaign_id'] ?? '';
$category = $_POST['phone_category'] ?? '';
$tag = $_POST['phone_tag'] ?? '';


if (empty($campaignId)) {
    echo json_encode(['status' => false, 'message' => 'Campaign ID missing']);
    exit;
}

if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== 0) {
    echo json_encode(['status' => false, 'message' => 'File upload failed']);
    exit;
}

$fileTmp  = $_FILES['import_file']['tmp_name'];
$fileName = $_FILES['import_file']['name'];
$fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

if (!in_array($fileExt, ['xls', 'xlsx'])) {
    echo json_encode(['status' => false, 'message' => 'Invalid file format']);
    exit;
}

try {

    $spreadsheet = IOFactory::load($fileTmp);
    $sheet       = $spreadsheet->getActiveSheet();
    $rows        = $sheet->toArray();

    if (count($rows) < 2) {
        throw new Exception('Excel file is empty');
    }

    // Normalize header
    $header = array_map(fn($h) => strtolower(trim($h)), $rows[0]);

    $phoneIndex    = array_search('phone', $header);
    $codeIndex     = array_search('code', $header);
    $usernameIndex = array_search('username', $header);

    if ($phoneIndex === false || $codeIndex === false || $usernameIndex === false) {
        throw new Exception(
            'Invalid Excel format. Required columns: phone, code, username'
        );
    }

    $inserted = 0;
    $errors   = [];

    foreach (array_slice($rows, 1) as $rowNumber => $row) {

        $excelRow = $rowNumber + 2;

        $phone    = trim($row[$phoneIndex] ?? '');
        $code     = strtoupper(trim($row[$codeIndex] ?? ''));
        $username = trim($row[$usernameIndex] ?? '');

        // Skip completely empty rows
        if ($phone === '' && $code === '' && $username === '') {
            continue;
        }

        /* ---------------- VALIDATIONS ---------------- */

        // Phone validation
        $phone = preg_replace('/\D/', '', $phone);
        if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
            $errors[] = "Row {$excelRow}: Invalid phone number";
            continue;
        }

        // Code validation
       $code = trim($row[$codeIndex] ?? '');

        if (!preg_match('/^[0-9]{1,4}$/', $code)) {
            $errors[] = "Row {$excelRow}: Invalid phone code (example: 91, 1, 971)";
            continue;
        }

        // Username validation
        if (strlen($username) < 2) {
            $errors[] = "Row {$excelRow}: Username is required";
            continue;
        }

        /* ---------------- INSERT ---------------- */

        $sql = "
            INSERT INTO tbl_campaign_numbers
            (category_id, tag_id, campaign_id, contact_name, phone_number, code, retry_count, created_at)
            VALUES
            ('{$category}', '{$tag}', '{$campaignId}', '{$username}', '{$phone}', '{$code}', 0, NOW())
        ";

        db_query($sql);
        $inserted++;
    }

    echo json_encode([
        'status'  => true,
        'message' => "Import completed. {$inserted} records inserted.",
        'errors'  => $errors
    ]);

} catch (Exception $e) {

    echo json_encode([
        'status'  => false,
        'message' => $e->getMessage()
    ]);
}
