<?php include('includes/include.php');
/* Database connection end */

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;

$columnSortOrder = 'DESC';
if (isset($requestData['order'][0]['dir'])) {
    $requestedSortDir = strtolower(trim((string)$requestData['order'][0]['dir']));
    if (in_array($requestedSortDir, ['asc', 'desc'], true)) {
        $columnSortOrder = strtoupper($requestedSortDir);
    }
}

$isPartner = (strtoupper((string) ($_SESSION['role'] ?? '')) === 'PARTNER');

if ($isPartner) {
    $columns = array(
        0 => 'fd.id',
        1 => 'ls.lead_source',
        2 => 'fd.type',
        3 => 'fd.month',
        4 => 'COALESCE(p.name, fd.reseller)',
        5 => 'fd.end_customer',
        6 => 'fd.brand',
        7 => 'fd.quote',
        8 => 'fd.qty',
        9 => 'fd.closure_date',
        10 => 'fd.closure_month',
        11 => 'fd.created_at'
    );
} else {
    $columns = array(
        0 => 'fd.id',
        1 => 'ls.lead_source',
        2 => 'fd.type',
        3 => 'fd.month',
        4 => 'COALESCE(p.name, fd.reseller)',
        5 => 'fd.end_customer',
        6 => 'fd.brand',
        7 => 'fd.quote',
        8 => 'fd.price',
        9 => 'fd.qty',
        10 => 'fd.total',
        11 => 'fd.closure_date',
        12 => 'fd.closure_month',
        13 => 'fd.created_at'
    );
}

// getting total number records without any search
$sqlTotal = "SELECT count(*) as total FROM funnel_data WHERE 1=1";
if ($isPartner && isset($_SESSION['team_id'])) {
    $teamId = db_escape($_SESSION['team_id']);
    $sqlTotal .= " AND reseller_code = '$teamId'";
}
$queryTotal = db_query($sqlTotal);
$rowTotal = db_fetch_array($queryTotal);
$totalData = $rowTotal['total'] ?? 0;
$totalFiltered = $totalData;

$sql = "SELECT fd.*, ls.lead_source as source_name, p.name as partner_name 
        FROM funnel_data fd 
        LEFT JOIN lead_source ls ON fd.source = ls.id 
        LEFT JOIN partners p ON fd.reseller_code = p.id 
        WHERE 1=1";

if ($isPartner && isset($_SESSION['team_id'])) {
    $teamId = db_escape($_SESSION['team_id']);
    $sql .= " AND fd.reseller_code = '$teamId'";
}

if (!empty($requestData['search']['value'])) {
    $search = db_escape($requestData['search']['value']);
    $sql .= " AND (
        ls.lead_source LIKE '%$search%' OR
        fd.type LIKE '%$search%' OR
        p.name LIKE '%$search%' OR
        fd.reseller LIKE '%$search%' OR
        fd.end_customer LIKE '%$search%' OR
        fd.brand LIKE '%$search%' OR
        fd.quote LIKE '%$search%'
    )";
}

// Custom filter for lead_source_id
if (!empty($requestData['lead_source_id'])) {
    $lsIds = json_decode($requestData['lead_source_id'], true);
    if (!empty($lsIds)) {
        $escapedLsIds = array_map(function($id) { return "'" . db_escape($id) . "'"; }, (array)$lsIds);
        $sql .= " AND fd.source IN (" . implode(',', $escapedLsIds) . ")";
    }
}

// Custom filter for reseller_id
if (!$isPartner && !empty($requestData['reseller_id'])) {
    $resellerIds = json_decode($requestData['reseller_id'], true);
    if (!empty($resellerIds)) {
        $escapedIds = array_map(function($id) { return "'" . db_escape($id) . "'"; }, (array)$resellerIds);
        $sql .= " AND (fd.reseller_code IN (" . implode(',', $escapedIds) . ") OR fd.reseller IN (" . implode(',', $escapedIds) . "))";
    }
}

// Custom filter for closure date range
if (!empty($requestData['closure_from'])) {
    $closure_from = db_escape($requestData['closure_from']);
    $sql .= " AND fd.closure_date >= '$closure_from'";
}
if (!empty($requestData['closure_to'])) {
    $closure_to = db_escape($requestData['closure_to']);
    $sql .= " AND fd.closure_date <= '$closure_to'";
}

$queryCount = db_query($sql);
$totalFiltered = mysqli_num_rows($queryCount);

$orderColumnIndex = isset($requestData['order'][0]['column']) ? intval($requestData['order'][0]['column']) : 0;
$orderBy = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'fd.id';

$sql .= " ORDER BY $orderBy $columnSortOrder";
if (isset($requestData['length']) && $requestData['length'] != -1) {
    $start = isset($requestData['start']) ? intval($requestData['start']) : 0;
    $length = intval($requestData['length']);
    $sql .= " LIMIT $start, $length";
}

$query = db_query($sql);

$results = array();
$start = isset($requestData['start']) ? intval($requestData['start']) : 0;
$i = $start + 1;

while($data = db_fetch_array($query)) {
    $nestedData = array();
    $nestedData['id'] = $i;
    $nestedData['source'] = $data['source_name'] ?: $data['source'];
    $nestedData['type'] = $data['type'];
    $nestedData['month'] = $data['month'];
    $nestedData['reseller'] = !empty($data['partner_name']) ? $data['partner_name'] : $data['reseller'];
    $nestedData['end_customer'] = $data['end_customer'];
    $nestedData['brand'] = $data['brand'];
    $nestedData['quote'] = $data['quote'];
    if (!$isPartner) {
        $nestedData['price'] = $data['price'];
    }
    $nestedData['qty'] = $data['qty'];
    if (!$isPartner) {
        $nestedData['total'] = $data['total'];
    }
    $nestedData['closure_date'] = ($data['closure_date'] && $data['closure_date'] != '0000-00-00') ? date('d-m-Y', strtotime($data['closure_date'])) : '';
    $nestedData['closure_month'] = $data['closure_month'];
    $nestedData['created_at'] = date('d-m-Y h:i:s', strtotime($data['created_at']));
    
    $results[] = $nestedData;
    $i++;
}

$json_data = array(
    "draw"            => intval($requestData['draw']),
    "recordsTotal"    => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data"            => $results
);

ob_clean();
echo json_encode($json_data);
exit;
?>
