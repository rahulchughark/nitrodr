<?php include('includes/include.php');

if($_GET['d_from'] && $_GET['d_to'])
{
    if($_GET['d_from'] == $_GET['d_to'])
    {
    $dat=" and DATE(l.lapsed_date)='".$_GET['d_from']."'";	
    } else {
    $dat=" and DATE(l.lapsed_date)>='".$_GET['d_from']."' and DATE(l.lapsed_date)<='".$_GET['d_to']."'";	
    } 
}

$partner_arr = $_GET['partner'] ? explode("','",$_GET['partner']):'';
$partner_arr1 = implode('","',$partner_arr);
//print_r($partner_arr);
if($_GET['partner'])
{
    $dat.=' and l.team_id in (' . $partner_arr1 . ')';
}

       $query = db_query("SELECT l.*,p.*,tp.product_name,tpp.product_type, industry.name as industry,sub_ind.name as sub_industry,states.name as state,users.name as created_by,callers.name as caller_name FROM lapsed_orders as l LEFT JOIN industry ON l.industry = industry.id LEFT JOIN sub_industry as sub_ind ON l.sub_industry = sub_ind.id LEFT JOIN states ON l.state = states.id LEFT JOIN users ON l.created_by = users.id left join tbl_lead_product as p on l.id=p.lead_id left join tbl_product as tp on p.product_id=tp.id left join tbl_product_pivot as tpp on p.product_type_id=tpp.id LEFT JOIN callers ON l.caller = callers.id where  l.license_type='Commercial' and l.dvr_flag!=1 ". $dat ." GROUP BY l.id ORDER BY l.id DESC");
       
    //print_r($query);die;


$rowCount = mysqli_num_rows($query);
if($rowCount > 0){
    $delimiter = ",";
    $filename = "Lapsed_leads_" . date('Y-m-d') . ".csv";
     ob_start();
    //create a file pointer
    $f = fopen('php://output', 'w');
    
    //set column headers

    $fields = array('Code','Partners' ,'Partner_email','Partner Users','Product Name','Product Type', 'Source', 'Lead Type', 'Company name','Parent company', 'Landline','Industry','	Sub Industry','Region','Address','Pincode','State','City','Country','eu_name','eu_email','eu_landline','department','eu_mobile','eu_designation','eu_role','account_visited','visit_remarks','confirmation_from','license_type','quantity','created_by','created_date','status','stage','Lapsed Date','Caller');


    fputcsv($f, $fields, $delimiter);
    
    //output each row of the data, format line as csv and write to file pointer
    while($row = db_fetch_array($query)){
		
		@extract($row);	
        if($status=='Approved'){
            $status='Qualified';
        }
        elseif($status=='Cancelled')
        {
            $status='Unqualified';
        }
        elseif($status=='Undervalidation
        ')
        {
            $status='Under Validation';
        }
        
        $new=db_query("select id,description,created_date,added_by from activity_log where pid='".$id."' and is_intern=0 UNION SELECT id,description,created_date,added_by from caller_comments where pid='".$id."' order by created_date desc");
        
        $count=mysqli_num_rows($new);
        $remarks='';
        $i = $count;
        if ($count) {
            while ($data_n = db_fetch_array($new)) {
                $remarks .= "\n" . $i . ' [' . ($data_n['added_by'] ? getSingleresult("select concat(name,'-',CASE  WHEN user_type='USR' THEN 'Partner User' WHEN (user_type='ADMIN' || user_type='SUPERADMIN') and sales_manager=0 THEN 'ADMIN' WHEN (user_type='ADMIN' || user_type='SUPERADMIN')and sales_manager=1 THEN 'Sales Manager'   WHEN user_type='MNGR' THEN 'Partner Manager'  ELSE 'Caller' END) as nme from users where id='" . $data_n['added_by'] . "'") : 'N/A') . ' on ' . date('d-m-Y H:i:s', strtotime($data_n['created_date'])) . ']:' . $data_n['description'] . "\n";
                $i--;
            }
        }

        $remarks .= date('d-m-Y H:i:s', strtotime($created_date)) . ':' . $visit_remarks;

   
		$lineData = array($code,$r_name,$r_email ,$r_user ,$product_name,$product_type,$source ,$lead_type ,htmlspecialchars_decode($company_name, ENT_NOQUOTES) ,htmlspecialchars_decode($parent_company, ENT_NOQUOTES) ,$landline ,$industry ,$sub_industry ,$region ,htmlspecialchars_decode($address, ENT_NOQUOTES) ,$pincode ,$state ,$city ,$country ,$eu_name ,$eu_email ,$eu_landline ,$department ,$eu_mobile ,$eu_designation ,$eu_role ,$account_visited , htmlspecialchars_decode($remarks, ENT_NOQUOTES) ,$confirmation_from ,$license_type ,$quantity ,$created_by ,$created_date ,$status ,$stage,$lapsed_date,$caller_name);

 
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
header("Location: lapsed_orders.php?m=nodata");    
}
exit();
