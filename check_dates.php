<?php
require_once __DIR__ . '/vendor/autoload.php';

$filePath = __DIR__ . '/funnel-data.xlsx';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
$worksheet = $spreadsheet->getActiveSheet();
$rows = $worksheet->toArray(null, true, true, true);

$header = array_shift($rows);
$headerMap = [];
foreach ($header as $col => $title) {
    $key = strtolower(str_replace(' ', '_', trim($title)));
    $headerMap[$col] = $key;
}

$unparsed = [];
foreach ($rows as $index => $row) {
    $data = [];
    foreach ($row as $col => $value) {
        $key = $headerMap[$col] ?? null;
        if ($key) {
            $data[$key] = $value;
        }
    }
    
    $value = $data['closure_date'] ?? '';
    if (!empty($value)) {
        $value = trim($value);
        if (!is_numeric($value)) {
            $formats = ['d-m-Y', 'd/m/Y', 'Y-m-d', 'm/d/Y', 'd-M-Y', 'Y/m/d', 'm-d-Y', 'd-m-y', 'd/m/y'];
            $parsed = false;
            foreach ($formats as $fmt) {
                $dt = date_create_from_format($fmt, $value);
                if ($dt !== false && $dt->format($fmt) === $value) {
                    $parsed = true;
                    break;
                }
            }
            if (!$parsed) {
                $timestamp = strtotime(str_replace('/', '-', $value));
                if (!$timestamp) {
                    $unparsed[] = $value;
                }
            }
        }
    }
}
$unparsed = array_unique($unparsed);
print_r($unparsed);
?>
