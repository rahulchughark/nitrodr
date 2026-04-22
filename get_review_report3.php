<?php include('includes/include.php');


$requestData= $_REQUEST;
if($requestData['d_from'] && $requestData['d_to'])
{

    if($requestData['d_from'] == $requestData['d_to'])
    {
    $dat=" and DATE(review_log.created_date)='".$requestData['d_from']."'";	
    } else {
    $dat=" and DATE(review_log.created_date)>='".$requestData['d_from']."' and DATE(review_log.created_date)<='".$requestData['d_to']."'";	
    }   
}
else
{
    $dat.=" and DATE(review_log.created_date)='".date('Y-m-d')."'";	
}


if($requestData['reviewer'])
{
    $dat.=" and review_log.added_by='".$requestData['reviewer']."'";
}

if ($requestData['product']) {
	$dat .= ' and p.product_id in ("' . stripslashes($requestData["product"]) . '")';
}
if ($requestData['product_type']) {
	$dat .= ' and p.product_type_id in ("' . stripslashes($requestData["product_type"]) . '")';
}

if($requestData['partner'])
{
    $dat.=' and orders.team_id in ("' . stripslashes($requestData["partner"]) . '")';
}

$sql = "select review_log.created_date as c_date,review_log.added_by,count(distinct(review_log.lead_id)) as cnt,p.product_id,p.product_type_id,orders.team_id as partner_id,partners.name as partner_name from review_log join orders on orders.id=review_log.lead_id join partners on orders.team_id=partners.id left join tbl_lead_product as p on review_log.lead_id=p.lead_id where 1 ".$dat;
//print_r($sql);
$sql2=$sql. " group by date(review_log.created_date),review_log.added_by,partners.id";
$query2=db_query($sql2);
$totalData = mysqli_num_rows($query2);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( review_log.old_stage LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR review_log.new_stage LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR review_log.comment LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR review_log.added_by LIKE '%".$requestData['search']['value']."%' ";

}
//echo $sql; die;
$columnIndex = $requestData['order'][0]['column']; // Column index 

    $requestData['columns'][$columnIndex]['data'];

$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc



$query=db_query($sql);
 // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" group by date(review_log.created_date),review_log.added_by,partners.id ORDER BY review_log.created_date desc  LIMIT ".$requestData['start']." ,".$requestData['length']."  ";
//echo $sql; die;
$query=db_query($sql);

$results = array();
$i=$requestData['start']+1;

while($data=db_fetch_array($query)) {

    $nestedData['id']=$i;
    $nestedData['date']=date('d-m-Y',strtotime($data['c_date']));
    $nestedData['reviewer']=$data['added_by'];
    $nestedData['var_organization']=$data['partner_name'];
    $nestedData['lc']=getSingleresult("select count(distinct(review_log.id)) as cnt from review_log join orders on review_log.lead_id=orders.id join partners on orders.team_id=partners.id where 1 and orders.lead_type='LC' and partners.id='".$data['partner_id']."' and review_log.added_by='".$data['added_by']."' and date( review_log.created_date)='".date('Y-m-d',strtotime($data['c_date']))."' ");
    $nestedData['bd']=getSingleresult("select count(distinct(review_log.id)) as cnt from review_log join orders  on  review_log.lead_id=orders.id join partners on orders.team_id=partners.id where 1 and orders.lead_type='BD' and partners.id='".$data['partner_id']."' and review_log.added_by='".$data['added_by']."' and date( review_log.created_date)='".date('Y-m-d',strtotime($data['c_date']))."'");
    $nestedData['incoming']= getSingleresult("select count(distinct(review_log.id)) as cnt from review_log join orders  on  review_log.lead_id=orders.id join partners on orders.team_id=partners.id where 1 and orders.lead_type='Incoming' and partners.id='".$data['partner_id']."' and review_log.added_by='".$data['added_by']."' and date( review_log.created_date)='".date('Y-m-d',strtotime($data['c_date']))."' ");

    $nestedData['accounts']=$nestedData['lc']+$nestedData['bd']+$nestedData['incoming'];
   
    
    $results[] = $nestedData;
$i++;
}
// echo "<pre>";
// print_r($results); die;
 

$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $results   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>

