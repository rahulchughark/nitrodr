<?php include('includes/include.php');



if($_GET['d_from'] && $_GET['d_to'])
{
        if ($_GET['d_from'] == $_GET['d_to']) {
            $dat = " and DATE(r.created_date)='" . $_GET['d_from'] . "'";
        } else {
            $dat = " and DATE(r.created_date)>='" . $_GET['d_from'] . "' and DATE(r.created_date)<='" . $_GET['d_to'] . "'";
        } 	
}

if ($_GET['users']) {	
	$dat = " and r.created_by in ('" . stripslashes($_GET['users']) . "')";
}
if ($_GET['region']) {	
	$dat = " and r.region in ('" . stripslashes($_GET['region']) . "')";
}
if ($_GET['city']) {	
	$dat = " and r.city in ('" . stripslashes($_GET['city']) . "')";
}
if($_GET['industry']=='DTP'){
	$dat = " and industry.log_status=1";
}
if($_GET['industry']=='Other'){
	$dat = " and industry.log_status=0";
}

       $query = db_query("select tp.product_name,tpp.product_type,r.r_email,r.created_date,r.team_id,r.id,r.quantity,r.company_name,r.parent_company,r.eu_name,r.eu_email,r.eu_mobile,r.r_user,r.source,r.created_by,r.landline,industry.name as industry,sub_industry.name as sub_industry,states.name as state,r.region,r.city,r.pincode FROM raw_leads as r left join industry on r.industry=industry.id left join states on r.state=states.id left join tbl_product as tp on r.product_id=tp.id left join tbl_product_pivot as tpp on r.product_type_id=tpp.id left join sub_industry on r.sub_industry=sub_industry.id where 1 and is_intern=1 ". $dat ." ORDER BY r.id desc");
       
   // print_r($query);die;

$rowCount = mysqli_num_rows($query);
if($rowCount > 0){
    $delimiter = ",";
    $filename = "Intern Leads_" . date('Y-m-d') . ".csv";
     ob_start();
    //create a file pointer
    $f = fopen('php://output', 'w');
    
    //set column headers



    $fields = array('Product Name','Product Type','Submitted By', 'Company name','Parent Company','Address','Landline Number','Industry','Sub Industry','PinCode','State','City','Customer Name','Customer Email','Customer Landline','Mobile','Designation','Quantity','Created Date','Activity History');


    fputcsv($f, $fields, $delimiter);
    
    //output each row of the data, format line as csv and write to file pointer
    while($row = db_fetch_array($query)){
		
		@extract($row);	

        
        $new=db_query("select id,description,created_date,added_by,call_subject from activity_log where activity_type like '%raw%' and is_intern=1 and pid='".$id."' order by created_date desc");
        
        $count=mysqli_num_rows($new);
        $remarks='';
        $i = $count;
        if ($count) {
            while ($data_n = db_fetch_array($new)) {
                $remarks .= "\n" . $i . ' [' . ($data_n['added_by'] ? getSingleresult("select concat(name,'-',CASE  WHEN user_type='USR' THEN 'Partner User'  WHEN user_type='ADMIN' and sales_manager=0 THEN 'ADMIN' WHEN user_type='ADMIN' and sales_manager=1 THEN 'Sales Manager'   WHEN user_type='MNGR' THEN 'Partner Manager' WHEN user_type='INTERN' THEN 'Corel Intern' ELSE 'Caller' END) as nme from users where id='" . $data_n['added_by'] . "'") : 'N/A') . ' on ' . date('d-m-Y H:i:s', strtotime($data_n['created_date'])) . ']:' . $data_n['description'] . "\n";
                $i--;
            }
        }

        $remarks .= date('d-m-Y H:i:s', strtotime($created_date)) . ':' . $visit_remarks;

   
		$lineData = array($product_name,$product_type ,$r_user ,htmlspecialchars_decode($company_name, ENT_NOQUOTES) ,htmlspecialchars_decode($parent_company, ENT_NOQUOTES),htmlspecialchars_decode($address, ENT_NOQUOTES),$landline,$industry,$sub_industry,$pincode,$state,$city,$eu_name ,$eu_email,$eu_landline,$eu_mobile ,$eu_designation ,$quantity ,$created_date,htmlspecialchars_decode($remarks, ENT_NOQUOTES));

 
			fputcsv($f, $lineData, $delimiter);
			$sub_industry_name='';
			$sub_industry='';
    }
	
	header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    fpassthru($f);
	ob_end_flush();
}
else {
header("Location: intern_leads.php?m=nodata");    
}
exit();
?>