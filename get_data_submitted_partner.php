<?php include('includes/include.php');
/* Database connection end */

$requestData= $_REQUEST;
// print_r($requestData);die;
    $dat = "";
	$ptr = "";
if($requestData['d_from'] && $requestData['d_to'])
{
    if($requestData['d_from'] == $requestData['d_to'])
    {
        $dat=" and DATE(o.created_date)='".$requestData['d_from']."'";	
    } else {
        $dat=" and DATE(o.created_date)>='".$requestData['d_from']."' and DATE(o.created_date)<='".$requestData['d_to']."'";	
    }
}

$partnerFtr =  json_decode($_REQUEST['partner']);
if($partnerFtr != '')
{
    $ptr=" and id in ('".implode("','",$partnerFtr)."')";
}

$product =  json_decode($_REQUEST['product']);
if($product != '')
{
	$joinC = " left join tbl_lead_product as tlp on o.id=tlp.pid ";
    $dat.=" and tlp.product_id in ('".implode("','",$product)."')";
	foreach ($product as $pr) {
		$urlCond.= "&productDS[]=$pr";
	}
}

$product_type =  json_decode($_REQUEST['product_type']);
if($product_type != '')
{
    $dat.=" and tlp.product_type_id in ('".implode("','",$product_type)."')";
	foreach ($product_type as $pr) {
		$urlCond.= "&product_typeDS[]=$pr";
	}
}

 
// getting total number records without any search
$sql ="select id,name from partners where status='Active' AND 1=1 ";

$query=db_query($sql);
$totalData = mysqli_num_rows($query);
// $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$sql ="select id,name from partners where status='Active' AND 1=1 ".$ptr;
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( name LIKE '%".$requestData['search']['value']."%' )";
}
// $sql.=$dat;
// echo $sql; die;

$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY partners.name LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
// echo $sql; die;
$query=db_query($sql);

$results = array();
$i=$requestData['start']+1;
while($data=db_fetch_array($query)) {  // preparing an array

    $nestedData=array(); 
	$nestedData['id']=$i;	
	
	$receivedF = getSingleresult("select count(DISTINCT(o.id)) from orders as o ".$joinC." where o.agreement_type='Fresh' and o.is_opportunity=0 and o.team_id='".$data['id']."'".$dat); 
	$receivedO = getSingleresult("select count(DISTINCT(o.id)) from orders as o ".$joinC." where o.agreement_type='Fresh' and o.is_opportunity=1 and o.team_id='".$data['id']."'".$dat); 
	$receivedR = getSingleresult("select count(DISTINCT(o.id)) from orders as o ".$joinC." where o.agreement_type='Renewal' and o.is_opportunity=1 and o.team_id='".$data['id']."'".$dat); 
	$qualifiedF = getSingleresult("select count(DISTINCT(o.id)) from orders as o ".$joinC." where o.agreement_type='Fresh' and o.is_opportunity=0 and o.team_id='" . $data['id'] . "' and o.status='Approved'".$dat); 
	$qualifiedO = getSingleresult("select count(DISTINCT(o.id)) from orders as o ".$joinC." where o.agreement_type='Fresh' and o.is_opportunity=1 and o.team_id='" . $data['id'] . "' and o.status='Approved'".$dat); 
	$qualifiedR = getSingleresult("select count(DISTINCT(o.id)) from orders as o ".$joinC." where o.agreement_type='Renewal' and o.is_opportunity=1 and o.team_id='" . $data['id'] . "' and o.status='Approved'".$dat); 
	$re_submissionF = getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o ".$joinC." where o.agreement_type='Fresh' and o.is_opportunity=0 and o.team_id='" . $data['id'] . "' and o.status='Undervalidation'".$dat); 
	$re_submissionO = getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o ".$joinC." where o.agreement_type='Fresh' and o.is_opportunity=1 and o.team_id='" . $data['id'] . "' and o.status='Undervalidation'".$dat); 
	$re_submissionR = getSingleresult("select count(DISTINCT(o.id)) as leads from orders as o ".$joinC." where o.agreement_type='Renewal' and o.is_opportunity=1 and o.team_id='" . $data['id'] . "' and o.status='Undervalidation'".$dat); 
	$unqualifiedF = getSingleresult("select count(DISTINCT(o.id)) from orders as o ".$joinC." where o.agreement_type='Fresh' and o.is_opportunity=0 and o.team_id='" . $data['id'] . "' and o.status='Cancelled'".$dat); 
	$unqualifiedO = getSingleresult("select count(DISTINCT(o.id)) from orders as o ".$joinC." where o.agreement_type='Fresh' and o.is_opportunity=1 and o.team_id='" . $data['id'] . "' and o.status='Cancelled'".$dat); 
	$unqualifiedR = getSingleresult("select count(DISTINCT(o.id)) from orders as o ".$joinC." where o.agreement_type='Renewal' and o.is_opportunity=1 and o.team_id='" . $data['id'] . "' and o.status='Cancelled'".$dat); 
	$pendingF = getSingleresult("select count(DISTINCT(o.id)) from orders as o ".$joinC." where o.agreement_type='Fresh' and o.is_opportunity=0 and o.team_id='" . $data['id'] . "' and o.status='Pending'".$dat); 
	$pendingO = getSingleresult("select count(DISTINCT(o.id)) from orders as o ".$joinC." where o.agreement_type='Fresh' and o.is_opportunity=1 and o.team_id='" . $data['id'] . "' and o.status='Pending'".$dat); 
	$pendingR = getSingleresult("select count(DISTINCT(o.id)) from orders as o ".$joinC." where o.agreement_type='Renewal' and o.is_opportunity=1 and o.team_id='" . $data['id'] . "' and o.status='Pending'".$dat); 
	$on_holdF = getSingleresult("select count(DISTINCT(o.id)) from orders as o ".$joinC." where o.agreement_type='Fresh' and o.is_opportunity=0 and o.team_id='" . $data['id'] . "' and o.status='On-Hold'".$dat); 
	$on_holdO = getSingleresult("select count(DISTINCT(o.id)) from orders as o ".$joinC." where o.agreement_type='Fresh' and o.is_opportunity=1 and o.team_id='" . $data['id'] . "' and o.status='On-Hold'".$dat); 
	$on_holdR = getSingleresult("select count(DISTINCT(o.id)) from orders as o ".$joinC." where o.agreement_type='Renewal' and o.is_opportunity=1 and o.team_id='" . $data['id'] . "' and o.status='On-Hold'".$dat); 

	$d_from = $requestData['d_from'];
	$d_to = $requestData['d_to'];

	$nestedData['r_name'] = $data['name'];
	$nestedData['receivedF'] = $receivedF ? "<a target='_blank' href='search_orders.php?partner[]=".$data['id']."&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$receivedF.'</a>' : 0;
	$nestedData['receivedO'] = $receivedO ? "<a target='_blank' href='manage_opportunity.php?partner[]=".$data['id']."&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$receivedO.'</a>' : 0;
	$nestedData['receivedR'] = $receivedR ? "<a target='_blank' href='renewal_leads_admin.php?partner[]=".$data['id']."&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$receivedR.'</a>' : 0;
	$nestedData['qualifiedF'] = $qualifiedF ? "<a target='_blank' href='search_orders.php?partner[]=".$data['id']."&status[]=Approved&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$qualifiedF.'</a>' : 0;
	$nestedData['qualifiedO'] = $qualifiedO ? "<a target='_blank' href='manage_opportunity.php?partner[]=".$data['id']."&status[]=Approved&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$qualifiedO.'</a>' : 0;
	$nestedData['qualifiedR'] = $qualifiedR ? "<a target='_blank' href='renewal_leads_admin.php?partner[]=".$data['id']."&status[]=Approved&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$qualifiedR.'</a>' : 0;
	$nestedData['re_submissionF'] = $re_submissionF ? "<a target='_blank' href='search_orders.php?partner[]=".$data['id']."&status[]=Undervalidation&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$re_submissionF.'</a>' : 0;
	$nestedData['re_submissionO'] = $re_submissionO ? "<a target='_blank' href='manage_opportunity.php?partner[]=".$data['id']."&status[]=Undervalidation&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$re_submissionO.'</a>' : 0;
	$nestedData['re_submissionR'] = $re_submissionR ? "<a target='_blank' href='renewal_leads_admin.php?partner[]=".$data['id']."&status[]=Undervalidation&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$re_submissionR.'</a>' : 0;
	$nestedData['unqualifiedF'] = $unqualifiedF ? "<a target='_blank' href='search_orders.php?partner[]=".$data['id']."&status[]=Cancelled&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$unqualifiedF.'</a>' : 0;
	$nestedData['unqualifiedO'] = $unqualifiedO ? "<a target='_blank' href='manage_opportunity.php?partner[]=".$data['id']."&status[]=Cancelled&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$unqualifiedO.'</a>' : 0;
	$nestedData['unqualifiedR'] = $unqualifiedR ? "<a target='_blank' href='renewal_leads_admin.php?partner[]=".$data['id']."&status[]=Cancelled&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$unqualifiedR.'</a>' : 0;
	$nestedData['pendingF'] = $pendingF ? "<a target='_blank' href='search_orders.php?partner[]=".$data['id']."&status[]=Pending&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$pendingF.'</a>' : 0;
	$nestedData['pendingO'] = $pendingO ? "<a target='_blank' href='manage_opportunity.php?partner[]=".$data['id']."&status[]=Pending&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$pendingO.'</a>' : 0;
	$nestedData['pendingR'] = $pendingR ? "<a target='_blank' href='renewal_leads_admin.php?partner[]=".$data['id']."&status[]=Pending&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$pendingR.'</a>' : 0;
	$nestedData['on_holdF'] = $on_holdF ? "<a target='_blank' href='search_orders.php?partner[]=".$data['id']."&status[]=On-Hold&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$on_holdF.'</a>' : 0;
	$nestedData['on_holdO'] = $on_holdO ? "<a target='_blank' href='manage_opportunity.php?partner[]=".$data['id']."&status[]=On-Hold&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$on_holdO.'</a>' : 0;
	$nestedData['on_holdR'] = $on_holdR ? "<a target='_blank' href='renewal_leads_admin.php?partner[]=".$data['id']."&status[]=On-Hold&d_from=".$d_from."&d_to=".$d_to.$urlCond."'>".$on_holdR.'</a>' : 0;
	
    $results[] = $nestedData;
    $check='';
    $i++;
}
//print_r($results); die;
 

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $results   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>

