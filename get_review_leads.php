<?php include('includes/include.php');

if ($_SESSION['sales_manager'] == 1) {
    $vir_cond = " and orders.team_id in (" . $_SESSION['access'] . ")  OR created_by=".$_SESSION['user_id']."";
}


$query = access_role_permission();
$fetch_query = db_fetch_array($query);



$requestData= $_REQUEST;
$pattern = "[^a-zA-Z]";
$requestData['review'] = intval($requestData['review']);
$requestData['partner'] = intval($requestData['partner']);
$requestData['search']['value']= mysqli_real_escape_string($GLOBALS['dbcon'],htmlspecialchars($requestData['search']['value']));
$requestData['length'] = intval($requestData['length']);
$requestData['start'] = intval($requestData['start']);
$requestData['d_from']= preg_replace("([^0-9/] | [^0-9-])","",$requestData['d_from']);
$requestData['d_to']= preg_replace("([^0-9/] | [^0-9-])","",$requestData['d_to']);
$requestData['d_from']=mysqli_real_escape_string($GLOBALS['dbcon'],htmlentities($requestData['d_from']));
$requestData['d_to']=mysqli_real_escape_string($GLOBALS['dbcon'],htmlentities($requestData['d_to']));
$requestData['order'][0]['dir'] = @preg_replace($pattern,$requestData['order'][0]['dir']);
$columnIndex=$requestData['order'][0]['column'] =intval($requestData['order'][0]['column']);
$requestData['columns'][$columnIndex]['data'] =htmlentities($requestData['columns'][$columnIndex]['data'],ENT_QUOTES);
$requestData['users'] = intval($requestData['users']);


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
if($requestData['users'])
{
    $dat.=" and orders.created_by='".$requestData['users']."'";
}
if($requestData['review']!='')
{
    if($requestData['review'])
    $dat.=" and lead_review.is_review='".$requestData['review']."'";
    else
    {
        $dat.=" and lead_review.is_review=0";
    }
}
// if($_SESSION['sales_manager']==1)
// {
//  $dat=" and orders.team_id in ('".$_SESSION['access']."') ";
// }
if($requestData['product'])
{
$dat.=" and p.product_id='".$requestData['product']."'";
}
if($requestData['product_type'])
{
$dat.=" and p.product_type_id='".$requestData['product_type']."'";
}


$sql = "select orders.r_name,orders.r_user,orders.lead_type,orders.quantity,orders.company_name,orders.eu_email,orders.id,orders.eu_mobile,orders.team_id,orders.created_by,orders.stage,lead_review.is_review,lead_review.added_date,orders.created_date,orders.data_ref ";
$sql.=" from lead_review left join orders on orders.id=lead_review.lead_id where 1 and orders.license_type='Commercial' ".$dat . $vir_cond;
//echo $sql; die;
// $sql .= " GROUP BY orders.id";




// $sql = "select orders.r_name,orders.r_user,orders.lead_type,orders.quantity,orders.company_name,orders.eu_email,orders.id,orders.eu_mobile,orders.team_id,orders.created_by,orders.stage,lead_review.is_review,lead_review.added_date,orders.created_date ";

// $sql.=" from orders left join lead_review on orders.id=lead_review.lead_id where 1 and orders.license_type='Commercial'";

if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
    $sql.=" AND ( orders.r_name LIKE '%".$requestData['search']['value']."%' ";    
    $sql.=" OR orders.lead_type LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR orders.quantity LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR orders.company_name LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR orders.eu_email LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR orders.eu_mobile LIKE '%".$requestData['search']['value']."%' )";

}
// $sql.=$dat . $vir_cond;
$sql .= " GROUP BY orders.id";

$query=db_query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
//echo $sql; die;

// $columnIndex = $requestData['order'][0]['column']; // Column index 
// if($columnIndex=='7')
// {
// $columnName = 'lead_review.added_date'; // Column name
// }
// else
// {
//     $requestData['columns'][$columnIndex]['data'];
// }

// $columnSortOrder = $requestData['order'][0]['dir']; // asc or desc

$sql.=" ORDER BY lead_review.id desc LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
//echo $sql; die;
$query=db_query($sql);

$results = array();
$i=$requestData['start']+1;




while($data=db_fetch_array($query)) {

    $nestedData['id']=$i;
    $nestedData['r_name']='<a style="color:#000" href="view_order.php?id='.$data['id'].'">'.$data['r_name'].'('.$data['r_user'].')</a>';

    $nestedData['lead_type']='<a style="color:#000" href="view_order.php?id='.$data['id'].'">'.$data['lead_type'].'</a>';

    $nestedData['quantity']='<a style="color:#000" href="view_order.php?id='.$data['id'].'">'.$data['quantity'].'</a>';

 //    $nestedData['product_name'] = '<a style="color:#000" href="view_order.php?id='.$data['id'].'">' . (getSingleresult("select p.product_name from tbl_lead_product as l left join tbl_product as p on l.product_id=p.id where l.lead_id=" . $data['id'])?(getSingleresult("select p.product_name from tbl_lead_product as l left join tbl_product as p on l.product_id=p.id where l.lead_id=" . $data['id'])):'N/A') . '</a>';

    // $nestedData['product_type'] = '<a style="color:#000" href="view_order.php?id='.$data['id'].'">' . (getSingleresult("select p.product_type from tbl_lead_product as l left join tbl_product_pivot as p on l.product_type_id=p.id where l.lead_id=" . $data['id'])?(getSingleresult("select p.product_type from tbl_lead_product as l left join tbl_product_pivot as p on l.product_type_id=p.id where l.lead_id=" . $data['id'])):'N/A') . '</a>';

    $nestedData['company_name']='<a style="color:#000" href="view_order.php?id='.$data['id'].'">'.$data['company_name'].'</a>';

    $nestedData['created_date']='<a style="color:#000" href="view_order.php?id='.$data['id'].'">'.date('d-m-Y',strtotime($data['created_date'])).'</a>';
   // $ids="but".$data['id'];
    $nestedData['stage']=$data['stage'];

    $nestedData['added_date']='<a style="color:#000" href="view_order.php?id='.$data['id'].'">'.date('d-m-Y',strtotime($data['added_date'])).'</a>';

    if($data['is_review']==1) 
    { $rev='<span class="text-danger">Pending</span>'; } 
    elseif($data['is_review']==2) { $rev='<span class="text-purple">In-Complete</span>'; } else { $rev='<span class="text-success">Done</span>'; }
    $nestedData['is_review']=$rev;
    if($data['data_ref'] == 2){
        $nestedData['data_ref'] = 'APP';
    }elseif($data['data_ref'] == 1){
        $nestedData['data_ref'] = 'WEB';
    }else{
        $nestedData['data_ref'] = '';
    }
    
    // if($_SESSION['sales_manager']!=1)
    // {
        $data['id']="'".$data['id']."'";

    $nestedData['action']=(($fetch_query['edit_review_log'] == 1)?('<a href="javascript:void(0)" title="Edit" onclick="edit_review('.$data['id'].')"> <i style="font-size:18px" class="mdi mdi-pencil"></i></a>'):''). '&nbsp;<a href="javascript:void(0)" title="View Log" onclick="view_log('.$data['id'].')"> <i style="font-size:18px" class="mdi mdi-eye"></i></a>';
    // }
    // else
    // {
    //     $nestedData['action']='<a href="javascript:void(0)" title="View Log" onclick="view_log('.$data['id'].')"> <i style="font-size:18px" class="mdi mdi-eye"></i></a>';
    
    // }

    $results[] = $nestedData;
$i++;
}
// print_r($results); die;
 

$json_data = array(
            "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal"    => intval( $totalData ),  // total number of records
            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $results   // total data array
            );

echo json_encode($json_data);  // send data as json format

?>

