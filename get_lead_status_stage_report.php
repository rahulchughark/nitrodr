<?php include('includes/include.php');
/* Database connection end */
$urlCond.='';
$requestData= $_REQUEST;
if($requestData['d_from'] && $requestData['d_to'])
{
	if($requestData['d_type']== 'close'){
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(o.expected_close_date)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(o.expected_close_date)>='".$requestData['d_from']."' and DATE(o.expected_close_date)<='".$requestData['d_to']."'";	
		}
	}elseif($requestData['d_type']== 'actioned_date'){
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(o.approval_time)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(o.approval_time)>='".$requestData['d_from']."' and DATE(o.approval_time)<='".$requestData['d_to']."'";	
		}
	}else{
		if($requestData['d_from'] == $requestData['d_to'])
		{
			$dat=" and DATE(o.created_date)='".$requestData['d_from']."'";	
		} else {
			$dat=" and DATE(o.created_date)>='".$requestData['d_from']."' and DATE(o.created_date)<='".$requestData['d_to']."'";	
		}
	}
    $urlCond.="&dtype=".$requestData['d_type']."&d_from=".$requestData['d_from']."&d_to=".$requestData['d_to'];
}
$tagFtr = json_decode($_REQUEST['tag']);
if($tagFtr)
{
	$dat.=" and o.tag in ('".implode("','",$tagFtr)."')";
    foreach ($tagFtr as $pr) {
		$urlCond.= "&tag[]=$pr";
	}
}
$statusFtr =  json_decode($_REQUEST['status']);
if($statusFtr != '')
{
    $dat.=" and o.status in ('".implode("','",$statusFtr)."')";
    foreach ($statusFtr as $pr) {
		$urlCond.= "&status[]=$pr";
	}
}
$partnerFtr =  json_decode($_REQUEST['partner']);
$usersFtr =  json_decode($_REQUEST['users']);
if($partnerFtr != '' && !$usersFtr)
{
	$teamUsers = db_query("SELECT id from users where team_id in (".implode(',',$partnerFtr).")");
	$usrrArr = [];
	while ($usr = db_fetch_array($teamUsers)){
		$usrrArr[] = $usr['id'];
        $urlCond.= "&users[]=".$usr['id'];
	}
	$dat.=" AND ((o.created_by IN (" . implode(",", $usrrArr) . ")) OR (o.allign_to IN (" . implode(",", $usrrArr) . ")))";
}
if($usersFtr != '')
{
	$dat.=" AND ((o.created_by IN (" . implode(",", $usersFtr) . ")) OR (o.allign_to IN (" . implode(",", $usersFtr) . ")))";
    foreach ($usersFtr as $pr) {
		$urlCond.= "&users[]=$pr";
	}
}
$school_boardFtr =  json_decode($_REQUEST['school_board']);
if($school_boardFtr != '')
{
    $dat.=" and o.school_board in ('".implode("','",$school_boardFtr)."')";
    foreach ($school_boardFtr as $pr) {
		$urlCond.= "&school_board[]=$pr";
	}
}
$statesFtr =  json_decode($_REQUEST['state']);
if($statesFtr != '')
{
	$dat.= " and o.state in (".implode(",",$statesFtr).")";
    foreach ($statesFtr as $pr) {
		$urlCond.= "&state[]=$pr";
	}
}
$sourceFtr =  json_decode($_REQUEST['source']);
if($sourceFtr != '')
{
	$dat.= " and o.source in ('".implode("','",$sourceFtr)."')";
    foreach ($sourceFtr as $pr) {
		$urlCond.= "&source[]=$pr";
	}
}

$statusArray = [
    'Raw Data',
    'Validation',
    'Contacted',
    'Qualified',
    'Unqualified',
    'Blank'
];
 
if($_REQUEST['type'] == 'leads'){
	$dat.= ' and o.is_opportunity=0';
	$url = 'search_orders.php';
}else if($_REQUEST['type'] == 'opportunity'){
	$dat.= " and o.is_opportunity=1 and o.agreement_type='Fresh'";
	$url = 'manage_opportunity.php';
}else if($_REQUEST['type'] == 'renewal'){
	$dat.= " and o.agreement_type='Renewal' and is_opportunity=1";
	$url = 'renewal_leads_admin.php';
}
$totalData = 6;
$totalFiltered = 6;

$closeLostT = 0;
$contactedT = 0;
$validatedT = 0;
$DemoArrangedT = 0;
$DemoCompletedT = 0;
$DemoLoginT = 0;
$login_proposalT = 0;
$ProposalSharedT = 0;
$FollowupT = 0;
$potentialT = 0;
$QuoteT = 0;
$RawDataT = 0;
$validated_dataT = 0;
$blank_dataT = 0;
$GraTotT = 0;

$results = array();
$i=1;
foreach($statusArray as $data) {  // preparing an array

    $nestedData=array(); 
	$nestedData['id']=$i;
	$nameSS = $data;
	if($data == 'Blank'){
		$data = '';
	}
	$nestedData['status']=$nameSS;	
	
	$closeLost = getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.lead_status='".$data."' and o.stage in('Close Lost') $dat");
	$contacted = getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.lead_status='".$data."' and o.add_comm in('Contacted') $dat");
	$validated = getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.lead_status='".$data."' and o.add_comm in('Validated') $dat");
	$DemoArranged = getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.lead_status='".$data."' and o.add_comm in('Demo Arranged') $dat");
	$DemoCompleted = getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.lead_status='".$data."' and o.add_comm in('Demo Completed') $dat");
	$DemoLogin = getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.lead_status='".$data."' and o.add_comm in('Demo Login') $dat");
	$login_proposal = getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.lead_status='".$data."' and o.add_comm in('Demo login+proposal shared') $dat");
	$ProposalShared = getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.lead_status='".$data."' and o.add_comm in('Proposal Shared') $dat");
	$Followup = getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.lead_status='".$data."' and o.stage in('Follow-up') $dat");
	$potential = getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.lead_status='".$data."' and o.stage in('Potential') $dat");
	$Quote = getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.lead_status='".$data."' and o.stage in('Quote') $dat");
	$RawData = getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.lead_status='".$data."' and o.stage in('Raw Data') $dat");
	$validated_data = getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.lead_status='".$data."' and o.stage in('Validated Data') $dat");
	$blank_data = getSingleResult("SELECT COUNT(o.id) from orders as o $joinC where o.lead_status='".$data."' and (o.stage is null or o.stage='') $dat");
	$received = $closeLost + $contacted + $validated + $DemoArranged + $DemoCompleted + $DemoLogin + $login_proposal + $ProposalShared + $Followup + $potential + $Quote + $RawData + $validated_data + $blank_data;

	$closeLostT+=$closeLost;
	$contactedT+=$contacted;
	$validatedT+=$validated;
	$DemoArrangedT+=$DemoArranged;
	$DemoCompletedT+=$DemoCompleted;
	$DemoLoginT+=$DemoLogin;
	$login_proposalT+=$login_proposal;
	$ProposalSharedT+=$ProposalShared;
	$FollowupT+=$Followup;
	$potentialT+=$potential;
	$QuoteT+=$Quote;
	$RawDataT+=$RawData;
	$validated_dataT+=$validated_data;
	$blank_dataT+=$blank_data;
	$GraTot+=$received;

	$d_from = $requestData['d_from'];
	$d_to = $requestData['d_to'];
	
	// $nestedData['grand_total'] = $_REQUEST['type'] ? ($received ? "<a target='_blank' href='".$url."?lead_status[]=$data&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$received.'</a>' : 0) : $received;
	$nestedData['grand_total'] = $received;
	$nestedData['close_lost'] = $_REQUEST['type'] ? ($closeLost ? "<a target='_blank' href='".$url."?lead_status[]=".$data."&stage[]=Close Lost&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$closeLost.'</a>' : 0) : $closeLost;
	$nestedData['contacted'] = $_REQUEST['type'] ? ($contacted ? "<a target='_blank' href='".$url."?lead_status[]=".$data."&sub_stage[]=Contacted&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$contacted.'</a>' : 0) : $contacted;
	$nestedData['validated'] = $_REQUEST['type'] ? ($validated ? "<a target='_blank' href='".$url."?lead_status[]=".$data."&sub_stage[]=Validated&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$validated.'</a>' : 0) : $validated;
	$nestedData['demo_arranged'] = $_REQUEST['type'] ? ($DemoArranged ? "<a target='_blank' href='".$url."?lead_status[]=".$data."&sub_stage[]=Demo Arranged&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$DemoArranged.'</a>' : 0) : $DemoArranged;
	$nestedData['demo_completed'] = $_REQUEST['type'] ? ($DemoCompleted ? "<a target='_blank' href='".$url."?lead_status[]=".$data."&sub_stage[]=Demo Completed&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$DemoCompleted.'</a>' : 0) : $DemoCompleted;
	$nestedData['demo_login'] = $_REQUEST['type'] ? ($DemoLogin ? "<a target='_blank' href='".$url."?lead_status[]=".$data."&sub_stage[]=Demo Login&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$DemoLogin.'</a>' : 0) : $DemoLogin;
	$nestedData['login_proposal'] = $_REQUEST['type'] ? ($login_proposal ? "<a target='_blank' href='".$url."?lead_status[]=".$data."&sub_stage[]=Demo login+proposal shared&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$login_proposal.'</a>' : 0) : $login_proposal;
	$nestedData['proposal_shared'] = $_REQUEST['type'] ? ($ProposalShared ? "<a target='_blank' href='".$url."?lead_status[]=".$data."&sub_stage[]=Proposal Shared&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$ProposalShared.'</a>' : 0) : $ProposalShared;
	$nestedData['follow_up'] = $_REQUEST['type'] ? ($Followup ? "<a target='_blank' href='".$url."?lead_status[]=".$data."&stage[]=Follow-up&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$Followup.'</a>' : 0) : $Followup;
	$nestedData['potential'] = $_REQUEST['type'] ? ($potential ? "<a target='_blank' href='".$url."?lead_status[]=".$data."&stage[]=Potential&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$potential.'</a>' : 0) : $potential;
	$nestedData['quote'] = $_REQUEST['type'] ? ($Quote ? "<a target='_blank' href='".$url."?lead_status[]=".$data."&stage[]=Quote&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$Quote.'</a>' : 0) : $Quote;
	$nestedData['raw_data'] = $_REQUEST['type'] ? ($RawData ? "<a target='_blank' href='".$url."?lead_status[]=".$data."&stage[]=Raw Data&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$RawData.'</a>' : 0) : $RawData;
	$nestedData['validated_data'] = $_REQUEST['type'] ? ($validated_data ? "<a target='_blank' href='".$url."?lead_status[]=".$data."&stage[]=Validated Data&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$validated_data.'</a>' : 0) : $validated_data;
	$nestedData['blank'] = $_REQUEST['type'] ? ($blank_data ? "<a target='_blank' href='".$url."?lead_status[]=".$data."&stage[]=Blank&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$blank_data.'</a>' : 0) : $blank_data;
    $results[] = $nestedData;
    $check='';
    $i++;
}
//print_r($results); die;
$nestedData=array(); 
$nestedData['id']='';
$nestedData['status']='Grand Total';	

$nestedData['close_lost'] =$closeLostT;
$nestedData['contacted'] =$contactedT;
$nestedData['validated'] =$validatedT;
$nestedData['demo_arranged'] =$DemoArrangedT;
$nestedData['demo_completed'] =$DemoCompletedT;
$nestedData['demo_login'] =$DemoLoginT;
$nestedData['login_proposal'] =$login_proposalT;
$nestedData['proposal_shared'] =$ProposalSharedT;
$nestedData['follow_up'] =$FollowupT;
$nestedData['potential'] =$potentialT;
$nestedData['quote'] =$QuoteT;
$nestedData['raw_data'] =$RawDataT;
$nestedData['validated_data'] =$validated_dataT;
$nestedData['blank'] =$blank_dataT;
$nestedData['grand_total'] =$GraTot;
$results[] = $nestedData; 

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),  
			"recordsTotal"    => intval( $totalData ), 
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => $results  
			);

echo json_encode($json_data);  // send data as json format

?>

