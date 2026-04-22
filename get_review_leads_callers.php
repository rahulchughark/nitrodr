<?php include('includes/include.php');


$requestData= $_REQUEST;
if($requestData['d_from'] && $requestData['d_to'])
{

    if($requestData['d_from'] == $requestData['d_to'])
    {
    $dat=" and DATE(lead_review.added_date)='".$requestData['d_from']."'";	
    } else {
    $dat=" and DATE(lead_review.added_date)>='".$requestData['d_from']."' and DATE(lead_review.added_date)<='".$requestData['d_to']."'";	
    }   
}
if($requestData['partner'])
{
    $dat.=" and orders.team_id='".$requestData['partner']."'";
}
if($requestData['review']!='')
{
    if($requestData['review'])
    $dat.=" and lead_review.is_review='".$requestData['review']."'";
    else
    {
        $dat.=" and lead_review.is_review<>1";
    }
}

if($_SESSION['user_type']=='CLR')
{
    $cid=getSingleresult("select id from callers where user_id='".$_SESSION['user_id']."'");
	$dat=" and caller = '".$cid."'";
}

$sql = "select orders.*,lead_review.is_review,lead_review.added_date ";
$sql.=" from orders join lead_review on orders.id=lead_review.lead_id where 1 ".$dat;
//echo $sql; die;
$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "select orders.*,lead_review.is_review,lead_review.added_date ";
$sql.=" from orders join lead_review on orders.id=lead_review.lead_id where 1 ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( orders.r_name LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR orders.lead_type LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR orders.quantity LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR orders.company_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR orders.eu_email LIKE '%".$requestData['search']['value']."%' ";
	//$sql.=" OR lead_review.is_review '%".$requestData['search']['value']."%' ";
	$sql.=" OR orders.eu_mobile LIKE '%".$requestData['search']['value']."%' )";
	//$sql.=" OR eu_landline LIKE '".$requestData['search']['value']."%' )";
	
	//$sql.=" OR eu_mobile LIKE '".$requestData['search']['value']."%' )";
}
$sql.=$dat;
//echo $sql; die;

$columnIndex = $requestData['order'][0]['column']; // Column index 
if($columnIndex=='7')
{
$columnName = 'lead_review.added_date'; // Column name
}
else
{
    $requestData['columns'][$columnIndex]['data'];
}

$columnSortOrder = $requestData['order'][0]['dir']; // asc or desc



$query=db_query($sql);
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ".$columnName." ".$columnSortOrder."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
//echo $sql; die;
$query=db_query($sql);

$results = array();
$i=$requestData['start']+1;

while($data=db_fetch_array($query)) {

    $nestedData['id']=$i;
    $nestedData['r_name']='<a style="color:#000" href="view_order.php?id='.$data['id'].'">'.$data['r_name'].'('.$data['r_user'].')</a>';
    $nestedData['lead_type']='<a style="color:#000" href="view_order.php?id='.$data['id'].'">'.$data['lead_type'].'</a>';
    $nestedData['quantity']='<a style="color:#000" href="view_order.php?id='.$data['id'].'">'.$data['quantity'].'</a>';
    $nestedData['company_name']='<a style="color:#000" href="view_order.php?id='.$data['id'].'">'.$data['company_name'].'</a>';
    $nestedData['created_date']='<a style="color:#000" href="view_order.php?id='.$data['id'].'">'.date('d-m-Y',strtotime($data['created_date'])).'</a>';
   // $ids="but".$data['id'];
    $nestedData['stage']=$data['stage'];
    $nestedData['added_date']='<a style="color:#000" href="view_order.php?id='.$data['id'].'">'.date('d-m-Y',strtotime($data['added_date'])).'</a>';
    $nestedData['is_review']=(($data['is_review']==1)?'<span class="text-danger">Pending</span>':'<span class="text-success">Done</span>');
    
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

?>

