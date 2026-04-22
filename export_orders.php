<?php include('includes/include.php');

if(count(explode('_',$_REQUEST['lead']))>1 && $_REQUEST['lead']!='' )
{
$leadId = str_replace("_",",", $_REQUEST['lead']);
$list=" and id not in (".$leadId.") ";
}
else if($_REQUEST['lead']!='')
{
    $list=" and id !=".$_REQUEST['lead'];
}

if($_GET['d_from'] && $_GET['d_to'])
{
    if($_GET['dtype']=='approved_date')
    {
        if($_REQUEST['d_from'] == $_REQUEST['d_to'])
        {
        $dat=" and DATE(approval_time)='".$_REQUEST['d_from']."'";	
        } else {
        $dat=" and DATE(approval_time)>='".$_REQUEST['d_from']."' and DATE(approval_time)<='".$_REQUEST['d_to']."'";	
        }
    }
    else
    {
        if($_REQUEST['d_from'] == $_REQUEST['d_to'])
        {
        $dat=" and DATE(created_date)='".$_REQUEST['d_from']."'";	
        } else {
        $dat=" and DATE(created_date)>='".$_REQUEST['d_from']."' and DATE(created_date)<='".$_REQUEST['d_to']."'";	
        }
    }

}

$sql="SELECT * FROM orders where status='Approved' and license_type='Commercial' and sfdc_check!='1' ".$list.$dat;
//print_r($sql);die;
$query = db_query($sql);

$rowCount = mysqli_num_rows($query);
if($rowCount > 0){
    $delimiter = ",";
    $filename = "Leads_" . date('Y-m-d') . ".csv";
     ob_start();
    //create a file pointer
    $f = fopen('php://output', 'w');
    
    //set column headers
    $fields = array('Salutation', 'First Name', 'Last Name', 'Company', 'Title', 'Division','Region','Website','Phone','Mobile','Email','Country','Street','City','Zip/Postal Code','State','Industry','Sub Industry','Lead Source','Reseller','Description','No. of Employees','Caller','Rating ','Runrate/Key','Lead Type','OS');
    fputcsv($f, $fields, $delimiter);
    
    //output each row of the data, format line as csv and write to file pointer
    while($row = db_fetch_array($query)){
		
		
		@extract($row);
		$update = db_query("update orders set sfdc_exp='1' where id=".$row['id']);
        $name_ex=explode(" ",$eu_name);
		$email_ex=explode("@",$eu_email);
		 if($name_ex[1]=='')
		{
		$name_ex[1]=$name_ex[0];
		$name_ex[0]='';
        }
        if($state)
        {
        $state_name=getSingleresult("select name from states where id=".$state);
        }
		$industry_name=getSingleresult("select name from industry where id=".$industry);
    if($sub_industry!='' && is_numeric($sub_industry))
    { 
        $sub_industry_name=getSingleresult("select name from sub_industry where id=".$sub_industry);
    }
        $reseller_id=getSingleresult("select reseller_id from partners where id=".$team_id);
        
        if(is_numeric($caller)) 
        { 
            $caller_id=getSingleresult("select caller_id from callers where id=".$caller);
	} else
        { $caller_id=$caller; }
        
		 $lineData = array('Mr.', $name_ex[0], $name_ex[1], htmlspecialchars_decode($company_name, ENT_NOQUOTES), $eu_designation, 'Corel',$region,$email_ex[1],$landline,$eu_mobile,$eu_email,$country,htmlspecialchars_decode($address, ENT_NOQUOTES),$city,$pincode,$state_name,$industry_name,$sub_industry_name,'Reseller',$reseller_id,htmlspecialchars_decode($visit_remarks, ENT_NOQUOTES),$quantity,$caller_id,'Hot',$runrate_key,$lead_type,$os);
 
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
header("Location: manage_orders.php?m=nodata");    
}
exit();
