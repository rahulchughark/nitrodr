<?php include('includes/include.php');
/* Database connection end */
$urlCond = '';
$requestData= $_REQUEST;
$requestData['upsell'] = '1';
if($requestData['fin_year']){
	$fy_year = $requestData['fin_year'];
	$startDate = $fy_year . "-04-01";
}else{
	$currentMonth = date('n'); // 1 to 12
	$currentYear = date('Y');
	if ($currentMonth >= 4) {
		$fy_year = $currentYear;
	} else {
		$fy_year = $currentYear - 1;
	}
	$startDate = $fy_year . "-04-01";
}
$endDate = ($fy_year + 1). "-03-31";
if($requestData['d_from'] && $requestData['d_to'])
{
    if($requestData['d_from'] == $requestData['d_to'])
    {
        $dat=" and DATE(o.created_date)='".$requestData['d_from']."'";	
    } else {
        $dat=" and DATE(o.created_date)>='".$requestData['d_from']."' and DATE(o.created_date)<='".$requestData['d_to']."'";	
    }
	$urlCond.= "&d_from=".$requestData['d_from']."&d_to=".$requestData['d_to'];
}
$urlCond.= "&fin_year=".$fy_year;

if($_SESSION['user_type'] == 'CLR'){
	$dat.=" AND ((o.created_by=".$_SESSION['user_id'].") OR (o.allign_to=".$_SESSION['user_id']."))";
}

$partnerFtr = json_decode($_REQUEST['partner']);
$usersFtr = json_decode($_REQUEST['users']);
if($partnerFtr && !$usersFtr)
{
	$teamUsers = db_query("SELECT id from users where team_id in (".implode(',',$partnerFtr).")");
	$usrrArr = [];
	while ($usr = db_fetch_array($teamUsers)){
		$usrrArr[] = $usr['id'];
	}
	$dat.=" AND ((o.created_by IN (" . implode(",", $usrrArr) . ")) OR (o.allign_to IN (" . implode(",", $usrrArr) . ")))";
	foreach ($partnerFtr as $ptr) {
		$urlCond.= "&partner[]=$ptr";
	}
}

if($requestData['just_partner'] == 'Yes'){
	$dat.=" AND o.team_id in (".implode(',',$partnerFtr).")	";
	$urlCond.= "&just_partner=Yes";
}

if($usersFtr)
{
    $dat.= " AND ((o.created_by IN (" . implode(",", $usersFtr) . ")) OR (o.allign_to IN (" . implode(",", $usersFtr) . ")))";
	// $dat.=" and (o.created_by in ('".implode("','",$usersFtr)."') || o.allign_to in ('".implode("','",$usersFtr)."'))";
	foreach ($usersFtr as $usr) {
		$urlCond.= "&users[]=$usr";
	}
	foreach ($partnerFtr as $ptr) {
		$urlCond.= "&partner[]=$ptr";
	}
}
// print_r($dat);die;
$productFtr = json_decode($_REQUEST['products']);
if($productFtr){
    $joinO = "left join tbl_lead_product_opportunity as t on t.lead_id=o.id";
    $dat.= " AND t.product in(" . implode(",", $productFtr) . ") and t.status=1";
	foreach ($productFtr as $pr) {
		$urlCond.= "&product[]=$pr";
	}
}

if($requestData['upsell'] == '1' || $requestData['upsell'] == '0')
{
	$dat.= " and t.upsell='".$requestData['upsell']."'";
	$urlCond.= "&upsell=".$requestData['upsell']."&upsellreport=true";
}

 

// print_r($dat);die;
$stagesAS = ("SELECT s.stage_name as stage, s.probability as valuee, s.id stage_id from stages as s where s.forecasting_flag=1 AND 1=1 ");
// echo ($stagesAS);die;
$stagesA = db_query($stagesAS);
// print_r();die;
$totalData = $stagesA->num_rows;
$totalFiltered = $totalData;


$results = array();
$i=1;
while ($stage=db_fetch_array($stagesA)) {  
	// $grandTN = getSingleResult("SELECT SUM(o.grand_total_price) AS total_grand_total FROM orders as o ".$joinO." WHERE o.stage = '".$stage['stage']."' and o.agreement_type='Renewal' and o.is_opportunity = 1 ".$dat);

	// $grandTN = getSingleResult("SELECT SUM(o.grand_total_price) AS total_grand_total FROM orders AS o JOIN tbl_lead_product_opportunity AS t ON t.lead_id = o.id WHERE o.stage = '" . $stage['stage'] . "' AND o.agreement_type = 'Renewal' AND o.is_opportunity = 1 AND t.financial_year_start = '$fy_year' $dat ");
	
	$grandTN = getSingleResult("SELECT SUM(grand_total_price) AS total_grand_total FROM (SELECT o.id, o.grand_total_price FROM orders AS o JOIN tbl_lead_product_opportunity AS t ON t.lead_id = o.id WHERE o.stage = '" . $stage['stage'] . "' AND o.agreement_type = 'Renewal' AND o.is_opportunity = 1 AND t.status!=0 AND t.financial_year_start = '$fy_year' $dat GROUP BY o.id ) AS unique_orders ");

	// $CountN = getSingleResult("SELECT COUNT(DISTINCT o.id) AS totalId FROM orders AS o JOIN tbl_lead_product_opportunity AS t ON t.lead_id = o.id WHERE o.stage = '" . $stage['stage'] . "' AND o.agreement_type = 'Renewal' AND o.is_opportunity = 1 AND t.status!=0 AND t.financial_year_start = '$fy_year' $dat ");

	$CountN = getSingleResult("SELECT COUNT(DISTINCT o.id) AS totalId FROM orders AS o JOIN tbl_lead_product_opportunity AS t ON t.lead_id = o.id WHERE t.upsell = 1 AND t.stage = '" . $stage['stage_id'] . "' AND o.agreement_type = 'Renewal' AND o.is_opportunity = 1 AND t.status!=0 AND t.financial_year_start = '$fy_year' ");
	
	// $QuanitityCountN = getSingleResult("SELECT SUM(t.quantity) AS totalId FROM orders as o join tbl_lead_product_opportunity as t on t.lead_id=o.id WHERE o.stage = '".$stage['stage']."' and o.agreement_type='Renewal' and t.status!=0 and o.is_opportunity = 1 ".$dat." AND t.financial_year_start = '$fy_year' $dat ");

	$QuanitityCountN = getSingleResult("SELECT SUM(t.quantity) AS totalId FROM orders as o join tbl_lead_product_opportunity as t on t.lead_id=o.id WHERE t.upsell = 1 AND t.stage = '" . $stage['stage_id'] . "' and o.agreement_type='Renewal' and t.status!=0 and o.is_opportunity = 1 AND t.financial_year_start = '$fy_year' ");


	// $model = getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM orders o JOIN tbl_lead_product_opportunity t ON o.id = t.lead_id WHERE t.main_product_id IN (1, 2, 3) AND o.stage = '" . $stage['stage'] . "' AND o.agreement_type = 'Renewal' AND t.status!=0 AND o.is_opportunity = 1 AND t.financial_year_start = '$fy_year' $dat ");


	$model = getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM orders o JOIN tbl_lead_product_opportunity t ON o.id = t.lead_id WHERE t.main_product_id IN (1, 2, 3) AND t.upsell = 1 AND t.stage = '" . $stage['stage_id'] . "' AND o.agreement_type = 'Renewal' AND t.status!=0 AND o.is_opportunity = 1 AND t.financial_year_start = '$fy_year' ");
	
	
	$maker_lab = getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM orders o JOIN tbl_lead_product_opportunity t ON o.id = t.lead_id WHERE t.main_product_id IN (8) AND t.upsell = 1 AND t.stage = '" . $stage['stage_id'] . "' AND o.agreement_type = 'Renewal' AND t.status!=0 AND o.is_opportunity = 1 AND t.financial_year_start = '$fy_year' ");

	$stop_motion = getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM orders o JOIN tbl_lead_product_opportunity t ON o.id = t.lead_id WHERE t.product=34 AND t.upsell = 1 AND t.stage = '" . $stage['stage_id'] . "' AND o.agreement_type='Renewal' AND t.status!=0 AND o.is_opportunity = 1 and t.financial_year_start = '$fy_year' ");

	$cubo = getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM orders o JOIN tbl_lead_product_opportunity t ON o.id = t.lead_id WHERE t.product=35 AND t.upsell = 1 AND t.stage = '" . $stage['stage_id'] . "' AND o.agreement_type='Renewal' AND t.status!=0 AND o.is_opportunity = 1 and t.financial_year_start = '$fy_year' ");

	$foundation = getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM orders o JOIN tbl_lead_product_opportunity t ON o.id = t.lead_id WHERE t.product=36 AND t.upsell = 1 AND t.stage = '" . $stage['stage_id'] . "' AND o.agreement_type='Renewal' AND t.status!=0 AND o.is_opportunity = 1 and t.financial_year_start = '$fy_year' ");
	
	$advanced = getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM orders o JOIN tbl_lead_product_opportunity t ON o.id = t.lead_id WHERE t.product=37 AND t.upsell = 1 AND t.stage = '" . $stage['stage_id'] . "' AND o.agreement_type='Renewal' AND t.status!=0 AND o.is_opportunity = 1 and t.financial_year_start = '$fy_year' ");
	
	$iot = getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM orders o JOIN tbl_lead_product_opportunity t ON o.id = t.lead_id WHERE t.product=38 AND t.upsell = 1 AND t.stage = '" . $stage['stage_id'] . "' AND o.agreement_type='Renewal' AND t.status!=0 AND o.is_opportunity = 1 and t.financial_year_start = '$fy_year' ");
	
	$robotics = getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM orders o JOIN tbl_lead_product_opportunity t ON o.id = t.lead_id WHERE t.product=39 AND t.upsell = 1 AND t.stage = '" . $stage['stage_id'] . "' AND o.agreement_type='Renewal' AND t.status!=0 AND o.is_opportunity = 1 and t.financial_year_start = '$fy_year' ");
	
	$creality = getSingleResult("SELECT SUM(t.total_price) AS total_sum FROM orders o JOIN tbl_lead_product_opportunity t ON o.id = t.lead_id WHERE t.product=40 AND t.upsell = 1 AND t.stage = '" . $stage['stage_id'] . "' AND o.agreement_type='Renewal' AND t.status!=0 AND o.is_opportunity = 1 and t.financial_year_start = '$fy_year' ");
    
	$nestedData=array(); 
	$nestedData['id']=$i;
	
	$CountN = $CountN ? $CountN : 0;
	$QuanitityCountN = $QuanitityCountN ? $QuanitityCountN : 0;
	$grandTN = $grandTN ? $grandTN : 0;
	$value = ($grandTN*$stage['valuee'])/100;
	$model = $model ?? 0;
	$stop_motion = $stop_motion ?? 0;
	$cubo = $cubo ?? 0;
	$maker_lab = $maker_lab ?? 0;
	$nestedData['stage'] = $stage['stage'];
	$nestedData['percentage'] = $stage['valuee'].'%';
	
	$nestedData['opp_new'] = $CountN != 0 ? "<a target='_blank' href='renewal_leads_admin.php?stage[]=".$stage['stage']."&type=Renewal".$urlCond."'>".$CountN.'</a>' : 0;
	
	$nestedData['quantity_new'] = $QuanitityCountN;
	$nestedData['model'] = $model != 0 ? "<a target='_blank' href='renewal_leads_admin.php?stage[]=".$stage['stage']."&type=Renewal".$urlCond."&product_opp=1'>".$model.'</a>' : 0;
	$nestedData['stop_motion'] = $stop_motion != 0 ? "<a target='_blank' href='renewal_leads_admin.php?stage[]=".$stage['stage']."&type=Renewal".$urlCond."&product_opp_type=34'>".$stop_motion.'</a>' : 0;
	$nestedData['cubo'] = $cubo != 0 ? "<a target='_blank' href='renewal_leads_admin.php?stage[]=".$stage['stage']."&type=Renewal".$urlCond."&product_opp_type=35'>".$cubo.'</a>' : 0;
	$nestedData['foundation'] = $foundation != 0 ? "<a target='_blank' href='renewal_leads_admin.php?stage[]=".$stage['stage']."&type=Renewal".$urlCond."&product_opp_type=36'>".$foundation.'</a>' : 0;
	$nestedData['advanced'] = $advanced != 0 ? "<a target='_blank' href='renewal_leads_admin.php?stage[]=".$stage['stage']."&type=Renewal".$urlCond."&product_opp_type=37'>".$advanced.'</a>' : 0;
	$nestedData['iot'] = $iot != 0 ? "<a target='_blank' href='renewal_leads_admin.php?stage[]=".$stage['stage']."&type=Renewal".$urlCond."&product_opp_type=38'>".$iot.'</a>' : 0;
	$nestedData['robotics'] = $robotics != 0 ? "<a target='_blank' href='renewal_leads_admin.php?stage[]=".$stage['stage']."&type=Renewal".$urlCond."&product_opp_type=39'>".$robotics.'</a>' : 0;
	$nestedData['creality'] = $creality != 0 ? "<a target='_blank' href='renewal_leads_admin.php?stage[]=".$stage['stage']."&type=Renewal".$urlCond."&product_opp_type=40'>".$creality.'</a>' : 0;
	$nestedData['maker_lab'] = $maker_lab != 0 ? "<a target='_blank' href='renewal_leads_admin.php?stage[]=".$stage['stage']."&type=Renewal".$urlCond."&product_opp=8'>".$maker_lab.'</a>' : 0;
	$nestedData['billing_new'] = $grandTN;
	$nestedData['value_new'] = round($value);

	if($stage['stage'] != 'Billing')
	{
		$CountNTotal+=$CountN;
		$QuanitityCountNTotal+=$QuanitityCountN;
		$modelTotal+=$model;
		$stop_motionTotal+=$stop_motion;
		$cuboTotal+=$cubo;
		$foundationTotal+=$foundation;
		$advancedTotal+=$advanced;
		$iotTotal+=$iot;
		$roboticsTotal+=$robotics;
		$crealityTotal+=$creality;
		$maker_labTotal+=$maker_lab;
		$grandTNTotal+=$grandTN;
		$valueTotal+=$value;
	}
	
    $results[] = $nestedData;

		$i++;
}
$nestedData['id']= '';
$nestedData['stage'] = 'Total';
$nestedData['percentage'] = '(Excluding Billing For New)';
$nestedData['opp_new'] = $CountNTotal;
$nestedData['quantity_new'] = $QuanitityCountNTotal;
$nestedData['model'] = $modelTotal;
$nestedData['stop_motion'] = $stop_motionTotal;
$nestedData['cubo'] = $cuboTotal;
$nestedData['foundation'] = $foundationTotal;
$nestedData['advanced'] = $advancedTotal;
$nestedData['iot'] = $iotTotal;
$nestedData['robotics'] = $roboticsTotal;
$nestedData['creality'] = $crealityTotal;
$nestedData['maker_lab'] = $maker_labTotal;
$nestedData['billing_new'] = $grandTNTotal;
$nestedData['value_new'] =  round($valueTotal);
$results[] = $nestedData;

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $results   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>

