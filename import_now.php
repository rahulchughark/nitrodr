<?php
require_once __DIR__ . '/vendor/autoload.php';

$conn = mysqli_connect('localhost', 'root', '', 'nitro-dr-prod');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function db_escape($str) {
    global $conn;
    return mysqli_real_escape_string($conn, $str);
}

function getSingleresult($sql) {
    global $conn;
    $res = mysqli_query($conn, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_row($res);
        return $row[0];
    }
    return null;
}

function db_query($sql) {
    global $conn;
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        echo "Error: " . mysqli_error($conn) . " in query: " . $sql . "\n";
    }
    return $res;
}

$filePath = __DIR__ . '/funnel-data.xlsx';
if (!file_exists($filePath)) {
    die("File not found: " . $filePath);
}

echo "Loading $filePath...\n";
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
$worksheet = $spreadsheet->getActiveSheet();
$rows = $worksheet->toArray(null, true, true, true);

$header = array_shift($rows);
$headerMap = [];
foreach ($header as $col => $title) {
    $key = strtolower(str_replace(' ', '_', trim($title)));
    $headerMap[$col] = $key;
}

echo "Starting import...\n";
$inserted = 0;
foreach ($rows as $index => $row) {
    $data = [];
    foreach ($row as $col => $value) {
        $key = $headerMap[$col] ?? null;
        if ($key) {
            $data[$key] = $value;
        }
    }
    
    // Skip empty rows (where all fields are empty)
    $isEmpty = true;
    foreach ($data as $v) {
        if (trim($v) !== '') { $isEmpty = false; break; }
    }
    if ($isEmpty) continue;

    $fields = [];
    $values = [];
    foreach (['source','type','month','reseller_code','reseller','end_customer','brand','quote','price','qty','total','closure_date','closure_month'] as $field) {
        if (isset($data[$field])) {
            $value = (string)$data[$field];
            
            if ($field === 'source' && !empty($value)) {
                $sourceName = trim($value);
                $sourceId = getSingleresult("SELECT id FROM lead_source WHERE lead_source = '" . db_escape($sourceName) . "' LIMIT 1");
                if ($sourceId) {
                    $value = $sourceId;
                }
            }

            // Robust date parsing for closure_date
            if ($field === 'closure_date' && !empty($value)) {
                $value = trim($value);
                if (is_numeric($value)) {
                    // Numeric excel serial date
                    $timestamp = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($value);
                    $value = date('Y-m-d', $timestamp);
                } else {
                    // String date formats
                    $timestamp = strtotime($value);
                    if ($timestamp !== false) {
                        $value = date('Y-m-d', $timestamp);
                    } else {
                        // Fallback: try replacing - with / or other formats if needed
                        $timestamp2 = strtotime(str_replace('-', '/', $value));
                        if ($timestamp2 !== false) {
                            $value = date('Y-m-d', $timestamp2);
                        } else {
                            $value = '0000-00-00';
                        }
                    }
                }
            }
            
            // Fix formatting of numeric fields
            if (in_array($field, ['price', 'total']) && !empty($value)) {
                $value = str_replace([',', '$', ' '], '', $value); // Remove commas/currency
            }
            if ($field === 'qty' && !empty($value)) {
                $value = (int)str_replace(',', '', $value);
            }

            $fields[] = $field;
            $values[] = "'" . db_escape($value) . "'";
        }
    }
    
    if (empty($fields)) continue;

    $now = date('Y-m-d H:i:s');
    $fields[] = 'created_at';
    $values[] = "'{$now}'";
    $fields[] = 'updated_at';
    $values[] = "'{$now}'";
    
    $sql = "INSERT INTO funnel_data (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $values) . ")";
    if (db_query($sql)) {
        $inserted++;
    }
}
echo "Import completed. Successfully imported {$inserted} records.\n";
?>
