<?php include('includes/include.php');
/* Database connection end */

 
// storing  request (ie, get/post) global array to a variable  
$requestData= $_GET;


if($requestData['d_from'] && $requestData['d_to'] )
{
    if ($requestData['d_from'] == $requestData['d_to']) {
        $dat = " and DATE(o.created_date)='" . $requestData['d_from'] . "'";
    } else {
        $dat = " and DATE(o.created_date)>='" . $requestData['d_from'] . "' and DATE(o.created_date)<='" . $requestData['d_to'] . "'";
    }
				
}
	

// getting total number records without any search
$sql = "select o.*,cl.name as caller,p.product_name,tpp.product_type,tpp.id as type_id ";
$sql.=" FROM orders as o
        LEFT JOIN callers as cl ON o.caller = cl.id 
        left join tbl_lead_product as tp on o.id=tp.lead_id left join tbl_product as p on tp.product_id=p.id left join tbl_product_pivot as tpp on tp.product_type_id=tpp.id
        where 1 and  o.license_type = 'Education' and o.dvr_flag!=1 order by o.id desc".$dat;
//echo $sql; die;
$query=db_query($sql);

$lineData = [];
$rowCount = mysqli_num_rows($query);

if ($rowCount > 0) {
    $delimiter = ",";
    $filename = "EducationLeads" . date('Y-m-d') . ".csv";
    ob_start();
    //create a file pointer
    $f = fopen('php://output', 'w');

    //set column headers
    $fields = array('S.No.','DR Code','Submitted by','Lead Type','Quantity','Product Name','Product Type','Company Name', 'Date of Submission', 'Status','Stage','Caller','Close Date');
    
    fputcsv($f, $fields, $delimiter);
    //output each row of the data, format line as csv and write to file pointer
    $i=1;
    while ($row = db_fetch_array($query)) {

        @extract($row);
        //print_r($row);die;
        
        foreach($row as $value){
            $lineData = array($i,$code,$r_user,$lead_type,$quantity ,$product_name,$product_type,htmlspecialchars_decode($company_name, ENT_NOQUOTES) ,$created_date,$status,$stage,$caller,$partner_close_date);  
               
        }

        
        
        fputcsv($f, $lineData, $delimiter); 
        //unset($query_data);
         $i++;
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    fpassthru($f);
    ob_end_flush();
}

?>

