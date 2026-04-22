<?php include('includes/include.php');

if ($_SESSION['sales_manager'] == 1) {
	$dat .= " and o.team_id in (" . $_SESSION['access'] . ") ";
}

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

if($requestData['partner'])
{
    $dat.=' and o.team_id in ("' . stripslashes($requestData["partner"]) . '")';
}

if($requestData['reviewer'])
{
    $dat .= ' and review_log.added_by in ("' . stripslashes($requestData["reviewer"]) . '")';
    //$dat.=" and review_log.added_by='".$requestData['reviewer']."'";
}

if ($requestData['product']) {
	$dat .= " and p.product_id in ('" . stripslashes($requestData["product"]) . "')";
}

if ($requestData['product_type']) {
	$dat .= ' and p.product_type_id in ("' . stripslashes($requestData["product_type"]) . '")';
}


$sql = "select review_log.*,p.product_id,p.product_type_id from review_log left join tbl_lead_product as p on review_log.lead_id=p.lead_id left join orders as o on review_log.lead_id=o.id where 1 ".$dat;
//echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( review_log.old_stage LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR review_log.new_stage LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR review_log.comment LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR review_log.added_by LIKE '%".$requestData['search']['value']."%' ";

}
$sql.=$dat;
$sql .= " GROUP BY o.id";

$columnIndex = $requestData['order'][0]['column']; // Column index 

    $requestData['columns'][$columnIndex]['data'];

$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc


$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY review_log.created_date desc  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
//echo $sql; die;
$query=db_query($sql);

$results = array();
$i=$requestData['start']+1;

while($data=db_fetch_array($query)) {

    $nestedData['id']=$i;
    $nestedData['partner_name']='<a style="color:#000" href="view_order.php?id='.$data['lead_id'].'">'.getSingleresult("select partners.name as pname from partners join orders on orders.team_id=partners.id where orders.id='".$data['lead_id']."'").'</a>';
    $nestedData['review_date']='<a style="color:#000" href="view_order.php?id='.$data['lead_id'].'">'.date('d-m-Y H:i:s',strtotime($data['created_date'])).'</a>';
    $nestedData['review_status']='<a style="color:#000" href="view_order.php?id='.$data['lead_id'].'"><span  style="color:green;font-weight:900">Done</span></a>';
    $nestedData['company_name']='<a style="color:#000" href="view_order.php?id='.$data['lead_id'].'">'.getSingleresult("select company_name from orders where id='".$data['lead_id']."'").'</a>';
    $nestedData['last_stage']='<a style="color:#000" href="view_order.php?id='.$data['lead_id'].'">'.$data['old_stage'].'</a>';
   // $ids="but".$data['id'];
    $nestedData['updated_stage']='<a style="color:#000" href="view_order.php?id='.$data['lead_id'].'">'.$data['new_stage'].'</a>';;
    $nestedData['comment']='<a style="color:#000" href="view_order.php?id='.$data['lead_id'].'">'.$data['comment'].'</a>';
    $nestedData['reviewer']='<a style="color:#000" href="view_order.php?id='.$data['lead_id'].'">'.$data['added_by'].'</a>';
    $results[] = $nestedData;
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
