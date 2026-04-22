<?php include 'includes/include.php';
/* Database connection end */
$urlCond     = '';
$requestData = $_REQUEST;
// print_r($requestData);die;
$statesFtr      = json_decode($_REQUEST['state']);
$lead_statusFtr = json_decode($_REQUEST['lead_status']);
$joinO          = " left join lead_modify_log as l on o.id=l.lead_id ";

if ($requestData['fin_year']) {
    $startYear = $requestData['fin_year'];
    $startDate = $startYear . "-04-01";
} else {
    $currentYear  = date('Y');
    $currentMonth = date('n');
    if ($currentMonth >= 4) {
        $startYear = $currentYear;
    } else {
        $startYear = $currentYear - 1;
    }
    $startDate = $startYear . "-04-01";
}
$endDate = ($startYear + 1) . "-03-31";

if ($requestData['d_from'] && $requestData['d_to']) {
    if ($requestData['d_type'] == 'close') {

        if ($requestData['d_from'] == $requestData['d_to']) {
            $dat = " and DATE(o.expected_close_date)='" . $requestData['d_from'] . "'";
        } else {
            $dat = " and DATE(o.expected_close_date)>='" . $requestData['d_from'] . "' and DATE(o.expected_close_date)<='" . $requestData['d_to'] . "'";
        }
    } elseif ($requestData['d_type'] == 'actioned_date') {
        if ($requestData['d_from'] == $requestData['d_to']) {
            $dat = " and DATE(o.approval_time)='" . $requestData['d_from'] . "'";
        } else {
            $dat = " and DATE(o.approval_time)>='" . $requestData['d_from'] . "' and DATE(o.approval_time)<='" . $requestData['d_to'] . "'";
        }
    } elseif ($requestData['d_type'] == 'lead_status' && $lead_statusFtr) {
        if ($requestData['d_from'] == $requestData['d_to']) {
            $dat = " and ((DATE(l.created_date)='" . $requestData['d_from'] . "' and l.type='Lead Status' and l.modify_name in ('" . implode("','", $lead_statusFtr) . "')) || (DATE(o.created_date)='" . $requestData['d_from'] . "')) and o.lead_status in ('" . implode("','", $lead_statusFtr) . "')";
        } else {
            $dat = " and ((DATE(l.created_date)>='" . $requestData['d_from'] . "' and DATE(l.created_date)<='" . $requestData['d_to'] . "' and l.type='Lead Status' and l.modify_name in ('" . implode("','", $lead_statusFtr) . "')) || ( DATE(o.created_date)>='" . $requestData['d_from'] . "' and DATE(o.created_date)<='" . $requestData['d_to'] . "')) and o.lead_status in ('" . implode("','", $lead_statusFtr) . "')";
        }
    } elseif ($requestData['d_type'] == 'opportunity_converted') {
        if ($requestData['d_from'] == $requestData['d_to']) {
            $dat = " and ((DATE(l.created_date)='" . $requestData['d_from'] . "' and l.type='Opportunity'))";
        } else {
            $dat = " and ((DATE(l.created_date)>='" . $requestData['d_from'] . "' and DATE(l.created_date)<='" . $requestData['d_to'] . "' and l.type='Opportunity'))";
        }
    } else {
        if ($requestData['d_from'] == $requestData['d_to']) {
            $dat = " and DATE(o.created_date)='" . $requestData['d_from'] . "'";
        } else {
            $dat = " and DATE(o.created_date)>='" . $requestData['d_from'] . "' and DATE(o.created_date)<='" . $requestData['d_to'] . "'";
        }
    }
    $urlCond .= "&dtype=" . $requestData['d_type'] . "&d_from=" . $requestData['d_from'] . "&d_to=" . $requestData['d_to'];
}

if ($_SESSION['user_type'] == 'CLR') {
    $dat .= " AND ((o.created_by=" . $_SESSION['user_id'] . ") OR (o.allign_to=" . $_SESSION['user_id'] . "))";
}
if ($lead_statusFtr != '') {
    $dat .= " and o.lead_status in ('" . implode("','", $lead_statusFtr) . "')";
    foreach ($lead_statusFtr as $ls) {
        $urlCond .= "&lead_status[]=$ls";
    }
}
$status_FTR = json_decode($_REQUEST['status']);
if ($status_FTR != '') {
    $dat .= " and o.status in ('" . implode("','", $status_FTR) . "')";
    foreach ($status_FTR as $ls) {
        $urlCond .= "&status[]=$ls";
    }
}
$school_boardFtr = json_decode($_REQUEST['school_board']);
if ($school_boardFtr != '') {
    $dat .= " and o.school_board in ('" . implode("','", $school_boardFtr) . "')";
    foreach ($school_boardFtr as $ls) {
        $urlCond .= "&school_board[]=$ls";
    }
}
$sourceFtr = json_decode($_REQUEST['source']);
if ($sourceFtr != '') {
    $dat .= " and o.source in ('" . implode("','", $sourceFtr) . "')";
    foreach ($sourceFtr as $ls) {
        $urlCond .= "&source[]=$ls";
    }
}
$tagFtr = json_decode($_REQUEST['tag']);
if ($tagFtr) {
    $dat .= " and o.tag in ('" . implode("','", $tagFtr) . "')";
    foreach ($tagFtr as $ls) {
        $urlCond .= "&tag[]=$ls";
    }
}
$statesFtr = json_decode($_REQUEST['state']);
if ($statesFtr != '') {
    $dat .= " and o.state in (" . implode(",", $statesFtr) . ")";
    foreach ($statesFtr as $ls) {
        $urlCond .= "&state[]=$ls";
    }
}
$cityFtr = json_decode($_REQUEST['city']);
if ($cityFtr != '') {
    $dat .= " and o.city in (" . implode(",", $cityFtr) . ")";
    foreach ($cityFtr as $ls) {
        $urlCond .= "&city[]=$ls";
    }
}
$partnerFtr = json_decode($_REQUEST['partner']);
$usersFtr   = json_decode($_REQUEST['users']);
if ($partnerFtr && ! $usersFtr) {
    $teamUsers = db_query("SELECT id from users where team_id in (" . implode(',', $partnerFtr) . ")");
    $usrrArr   = [];
    while ($usr = db_fetch_array($teamUsers)) {
        $usrrArr[] = $usr['id'];
    }
    $dat .= " AND ((o.created_by IN (" . implode(",", $usrrArr) . ")) OR (o.allign_to IN (" . implode(",", $usrrArr) . ")))";
    foreach ($partnerFtr as $ptr) {
        $urlCond .= "&partner[]=$ptr";
    }
}
if ($requestData['just_partner'] == 'Yes') {
    $dat .= " AND o.team_id in (" . implode(',', $partnerFtr) . ")	";
    $urlCond .= "&just_partner=Yes";
}
if ($usersFtr) {
    $dat .= " AND ((o.created_by IN (" . implode(",", $usersFtr) . ")) OR (o.allign_to IN (" . implode(",", $usersFtr) . ")))";
    foreach ($usersFtr as $usr) {
        $urlCond .= "&users[]=$usr";
    }
    foreach ($partnerFtr as $ptr) {
        $urlCond .= "&partner[]=$ptr";
    }
}

$productFtr = json_decode($_REQUEST['products']);
if ($productFtr) {
    $joinO .= " left join tbl_lead_product_opportunity as t on t.lead_id=o.id";
    $dat .= " AND t.product in(" . implode(",", $productFtr) . ") and t.status=1";
    foreach ($productFtr as $pr) {
        $urlCond .= "&product[]=$pr";
    }
}
// print_r($dat);
// print_r($urlCond);
// die;
$stagesAS = ("SELECT s.stage_name as stage,s.probability as valuee from stages as s where s.forecasting_flag=1 AND 1=1 ");
// echo ($stagesAS);die;
$stagesA = db_query($stagesAS);
// print_r();die;
$totalData     = $stagesA->num_rows;
$totalFiltered = $totalData;

$results = [];
$i       = 1;
$urlCond .= "&fin_start=" . $startDate . "&fin_end=" . $endDate;
while ($stage = db_fetch_array($stagesA)) {
    // $datS='';
    if ($stage['stage'] == 'Billing') {
        $datS = " and DATE(l.created_date)>='" . $startDate . "' and DATE(l.created_date)<='" . $endDate . "' and l.type='Stage' and l.modify_name='PO/CIF Issued' AND o.id NOT IN ( SELECT lead_id FROM lead_modify_log WHERE type = 'Stage' AND modify_name = 'PO/CIF Issued' AND DATE(created_date) > '" . $endDate . "')";
    } else {
        $datS = " and DATE(l.created_date)>='" . $startDate . "' and DATE(l.created_date)<='" . $endDate . "' and l.type='Stage' and l.modify_name='" . $stage['stage'] . "' AND o.id NOT IN ( SELECT lead_id FROM lead_modify_log WHERE type = 'Stage' AND modify_name = '" . $stage['stage'] . "' AND DATE(created_date) > '" . $endDate . "')";
    }
    if ($productFtr) {
        $grandTN = getSingleResult("SELECT SUM(t.total_price) AS total_grand_total FROM orders as o " . $joinO . " WHERE o.stage = '" . $stage['stage'] . "' and o.agreement_type='Fresh' and t.status=1 and o.is_opportunity = 1 " . $dat . $datS);
    } else {
        $grandTN = getSingleResult("SELECT SUM(o.grand_total_price) AS total_grand_total FROM orders as o " . $joinO . " WHERE o.stage = '" . $stage['stage'] . "' and o.agreement_type='Fresh' and o.is_opportunity = 1 " . $dat . $datS);
    }
    $CountNQ  = db_query("SELECT DISTINCT(o.id) order_id FROM orders as o " . $joinO . " WHERE o.stage = '" . $stage['stage'] . "' and o.agreement_type='Fresh' and o.is_opportunity = 1 " . $dat . $datS);
    $orderIds = [];
    if ($CountNQ->num_rows > 0) {
        while ($cnt = db_fetch_array($CountNQ)) {
            $orderIds[] = $cnt['order_id'];
        }
        $CountN        = count($orderIds);
        $orderCommaSep = implode(',', $orderIds);
    } else {
        $CountN        = 0;
        $orderCommaSep = '';
    }

    $QuanitityCountN = $CountN > 0 ? getSingleResult("SELECT SUM(t.quantity) AS totalId FROM tbl_lead_product_opportunity as t WHERE t.lead_id in (" . $orderCommaSep . ") and t.status=1") : 0;

    $model = $CountN > 0 ? getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM tbl_lead_product_opportunity as t WHERE t.main_product_id IN (1, 2, 3) AND t.lead_id in (" . $orderCommaSep . ") AND t.status=1") : 0;
    // print_r($model);die;
    $maker_lab = $CountN > 0 ? getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM tbl_lead_product_opportunity t WHERE t.main_product_id IN (8) AND t.status = 1 AND t.lead_id in (" . $orderCommaSep . ")") : 0;

    $stop_motion = $CountN > 0 ? getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM tbl_lead_product_opportunity t WHERE t.product=34 AND t.status = 1 AND t.lead_id in (" . $orderCommaSep . ")") : 0;

    $cubo = $CountN > 0 ? getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM tbl_lead_product_opportunity t WHERE t.product=35 AND t.status = 1 AND t.lead_id in (" . $orderCommaSep . ")") : 0;

    $foundation = $CountN > 0 ? getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM tbl_lead_product_opportunity t WHERE t.product=36 AND t.status = 1 AND t.lead_id in (" . $orderCommaSep . ")") : 0;

    $advanced = $CountN > 0 ? getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM tbl_lead_product_opportunity t WHERE t.product=37 AND t.status = 1 AND t.lead_id in (" . $orderCommaSep . ")") : 0;

    $iot = $CountN > 0 ? getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM tbl_lead_product_opportunity t WHERE t.product=38 AND t.status = 1 AND t.lead_id in (" . $orderCommaSep . ")") : 0;

    $robotics = $CountN > 0 ? getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM tbl_lead_product_opportunity t WHERE t.product=39 AND t.status = 1 AND t.lead_id in (" . $orderCommaSep . ")") : 0;

    $creality = $CountN > 0 ? getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM tbl_lead_product_opportunity t WHERE t.product=40 AND t.status = 1 AND t.lead_id in (" . $orderCommaSep . ")") : 0;

    // print_r($grandTN);die;
    $nestedData       = [];
    $nestedData['id'] = $i;

    $CountN                     = $CountN ? $CountN : 0;
    $QuanitityCountN            = $QuanitityCountN ? $QuanitityCountN : 0;
    $grandTN                    = $grandTN ? $grandTN : 0;
    $value                      = ($grandTN * $stage['valuee']) / 100;
    $model                      = $model ?? 0;
    $stop_motion                = $stop_motion ?? 0;
    $cubo                       = $cubo ?? 0;
    $maker_lab                  = $maker_lab ?? 0;
    $nestedData['stage']        = $stage['stage'];
    $nestedData['percentage']   = $stage['valuee'] . '%';
    $nestedData['opp_new']      = $CountN != 0 ? "<a target='_blank' href='manage_opportunity.php?stage[]=" . $stage['stage'] . "&type=Fresh" . $urlCond . "'>" . $CountN . '</a>' : 0;
    $nestedData['quantity_new'] = $QuanitityCountN;
    $nestedData['model']        = $model != 0 ? "<a target='_blank' href='manage_opportunity.php?stage[]=" . $stage['stage'] . "&type=Fresh" . $urlCond . "&product_opp=1'>" . $model . '</a>' : 0;
    $nestedData['stop_motion']  = $stop_motion != 0 ? "<a target='_blank' href='manage_opportunity.php?stage[]=" . $stage['stage'] . "&type=Fresh" . $urlCond . "&product_opp_type=34'>" . $stop_motion . '</a>' : 0;
    $nestedData['cubo']         = $cubo != 0 ? "<a target='_blank' href='manage_opportunity.php?stage[]=" . $stage['stage'] . "&type=Fresh" . $urlCond . "&product_opp_type=35'>" . $cubo . '</a>' : 0;
    $nestedData['foundation']   = $foundation != 0 ? "<a target='_blank' href='manage_opportunity.php?stage[]=" . $stage['stage'] . "&type=Fresh" . $urlCond . "&product_opp_type=36'>" . $foundation . '</a>' : 0;
    $nestedData['advanced']     = $advanced != 0 ? "<a target='_blank' href='manage_opportunity.php?stage[]=" . $stage['stage'] . "&type=Fresh" . $urlCond . "&product_opp_type=37'>" . $advanced . '</a>' : 0;
    $nestedData['iot']          = $iot != 0 ? "<a target='_blank' href='manage_opportunity.php?stage[]=" . $stage['stage'] . "&type=Fresh" . $urlCond . "&product_opp_type=38'>" . $iot . '</a>' : 0;
    $nestedData['robotics']     = $robotics != 0 ? "<a target='_blank' href='manage_opportunity.php?stage[]=" . $stage['stage'] . "&type=Fresh" . $urlCond . "&product_opp_type=39'>" . $robotics . '</a>' : 0;
    $nestedData['creality']     = $creality != 0 ? "<a target='_blank' href='manage_opportunity.php?stage[]=" . $stage['stage'] . "&type=Fresh" . $urlCond . "&product_opp_type=40'>" . $creality . '</a>' : 0;
    $nestedData['maker_lab']    = $maker_lab != 0 ? "<a target='_blank' href='manage_opportunity.php?stage[]=" . $stage['stage'] . "&type=Fresh" . $urlCond . "&product_opp=8'>" . $maker_lab . '</a>' : 0;
    $nestedData['billing_new']  = $grandTN;
    $nestedData['value_new']    = round($value);

    if ($stage['stage'] != 'Billing') {
        $CountNTotal += $CountN;
        $QuanitityCountNTotal += $QuanitityCountN;
        $modelTotal += $model;
        $stop_motionTotal += $stop_motion;
        $cuboTotal += $cubo;
        $foundationTotal += $foundation;
        $advancedTotal += $advanced;
        $iotTotal += $iot;
        $roboticsTotal += $robotics;
        $crealityTotal += $creality;
        $maker_labTotal += $maker_lab;
        $grandTNTotal += $grandTN;
        $valueTotal += $value;
    }

    $results[] = $nestedData;

    $i++;
}
$nestedData['id']           = '';
$nestedData['stage']        = 'Total';
$nestedData['percentage']   = '(Excluding Billing For New)';
$nestedData['opp_new']      = $CountNTotal;
$nestedData['quantity_new'] = $QuanitityCountNTotal;
$nestedData['model']        = $modelTotal;
$nestedData['stop_motion']  = $stop_motionTotal;
$nestedData['cubo']         = $cuboTotal;
$nestedData['foundation']   = $foundationTotal;
$nestedData['advanced']     = $advancedTotal;
$nestedData['iot']          = $iotTotal;
$nestedData['robotics']     = $roboticsTotal;
$nestedData['creality']     = $crealityTotal;
$nestedData['maker_lab']    = $maker_labTotal;
$nestedData['billing_new']  = $grandTNTotal;
$nestedData['value_new']    = round($valueTotal);
$results[]                  = $nestedData;

$json_data = [
    "draw"            => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
    "recordsTotal"    => intval($totalData),           // total number of records
    "recordsFiltered" => intval($totalFiltered),       // total number of records after searching, if there is no searching then totalFiltered = totalData
    "data"            => $results,                     // total data array
];

echo json_encode($json_data); // send data as json format
