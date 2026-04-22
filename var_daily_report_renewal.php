<?php include('includes/include.php');
if(date('l')!='Sunday')
{
    
ini_set('max_execution_time', 0);
   			
$resellers=db_query("select id,name from partners where id not in (45,25,37,53) and status='Active'");
while($data=db_fetch_array($resellers))
{
           
    if(date('l')=='Monday'){			
        $dat=date('Y-m-d',strtotime("-2 days"));
        $setSubject = "Renewal Daily Activity Report - ".$data['name'].' '.date('jS F Y',strtotime("-2 days")).".";
        if($_REQUEST['d_check'])
        {
        $dat=$_REQUEST['d_check'];
        }
        }
        else
        {
          $dat=date('Y-m-d',strtotime("-1 days")); 
          if($_REQUEST['d_check']) 
          {
          $dat=$_REQUEST['d_check'];
          }
          $setSubject = "Renewal daily activity report - ".$data['name'].' '.date('jS F Y',strtotime("-1 days")).".";
        }
$users1=db_query("select id,role from users where team_id='".$data['id']."' and status='Active' ");
$ids=array();
$tcid=array();
while($uid=db_fetch_array($users1))
{
$ids[]=$uid['id'];
if($uid['role']=='TC')
{
    $tcid[]=$uid['id'];
}
}
$salesid=array_diff($ids,$tcid);
$idds=implode(',',$ids);
$tcids=implode(',',$salesid);
if(!$idds)
{
    $idds=0; 
}
if(!$tcids)
{
    $tcids=0;
}
$varlog_call=getSingleresult("SELECT count(*) FROM `activity_log` left JOIN orders on orders.id=activity_log.pid left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.license_type='Renewal' and (date(activity_log.created_date) = '".$dat."') and activity_log.call_subject not like '%visit%'  and activity_log.added_by in (".$idds.") and ( tbl_lead_product.product_type_id in (6,7) or tbl_lead_product.product_id is NULL)");

$varstage_update_count=getSingleresult("SELECT count(DISTINCT(lead_modify_log.lead_id)) as stage_update_count FROM `lead_modify_log` JOIN orders on orders.id=lead_modify_log.lead_id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.license_type='Renewal' and orders.status='Approved' and date(lead_modify_log.created_date) = '".$dat."' and lead_modify_log.type = 'Stage' and lead_modify_log.created_by in (".$idds.") and ( tbl_lead_product.product_type_id in (6,7) or tbl_lead_product.product_id is NULL)");


	 
$body='<style type="text/css"> 
.TFtable{ border-collapse:collapse; font-family:Arial; } 
.TFtable td{ padding:7px; border:#000 1px solid; } 
.TFtable th{ padding:7px; border:#000 1px solid;background: #fff; } 
.TFtable tr{ background: #fff; } 
.odd{ background: #b8d1f3; } 
.even{ background: #dae5f4; }
.first_table td{background: #061621;color:#FFFDFC;background: -moz-linear-gradient(top, #445058 0%, #1f2d37 66%, #061621 100%);
  background: -webkit-linear-gradient(top, #445058 0%, #1f2d37 66%, #061621 100%);
  background: linear-gradient(to bottom, #445058 0%, #1f2d37 66%, #061621 100%);
  border: 2px solid #444444;} 
 .second_table td{ 
   background: #371044;
  background: -moz-linear-gradient(top, #a6554b 0%, #943327 66%, #891D0F 100%);
  background: -webkit-linear-gradient(top, #a6554b 0%, #943327 66%, #891D0F 100%);
  background: linear-gradient(to bottom, #a6554b 0%, #943327 66%, #891D0F 100%);
  border: 2px solid #444444;color:#fff;

}
ul {
    list-style: none;
  }
  
ul li:before { content:"\2714\0020"; }
.c1{ background-color:#370F44;color:#fff}
.c2{ background-color:#370F44;color:#fff}
.c3{ background-color:#051620;color:#fff}
.c_yellow td{ background-color:#FFFF00;color:#000}
  </style><p>Hi,</p>
<p>Greetings for the day.!</p>
<p>'.$data['name'].' yesterday&rsquo;s  renewal activity report suggest we had total</p>
<ul>

<li>'.$varlog_call.' Log a calls</li>
<li>'.$varstage_update_count.' Of Stage Update</li>

</ul>
<p>Which are updated @ SketchUp DR Portal dated '.date('d-M-Y',strtotime($dat)).'.  Below is summary for your reference  :</p>
<table width="100%">

<tr>
<td valign="top">
<table  class ="TFtable" style="width:100%;font-size:12px" border="1" cellspacing="0" cellpadding="0">
<tbody>
        <tr>
            <td width="664" colspan="4"  class="c2">
                <p align="center">
                    <strong>'.$data['name'].'</strong>
                </p>
            </td>
        </tr>
        <tr>
            <td width="128" rowspan="2"  class="c3">
                <p align="center">
                    <strong>Sr. Number</strong>
                </p>
            </td>
            <td width="155" rowspan="2" class="c3" >
                <p align="center">
                    <strong>Team Member Name</strong>
                </p>
            </td>

            <td width="97" class="c3">
                <p align="center">
                    <strong>Calls</strong>
                </p>
            </td>

            <td width="97" class="c3">
                <p align="center">
                    <strong>Stage Updates</strong>
                </p>
            </td>

        </tr>
        <tr>
            
            <td width="97" class="c3" >
                <p align="center">
                    <strong>(Log A Calls)</strong>
                </p>
            </td>

            <td width="97" class="c3" >
                <p align="center">
                    <strong>(Number Of Accounts)</strong>
                </p>
            </td>

        </tr>';
        $users2=db_query("select id,name,email from users where team_id='".$data['id']."' and status='Active' ");
        $i=1;
        $data_avl = 0;
    while($users=db_fetch_array($users2))
{

$log_call=getSingleresult("SELECT count(*) FROM `activity_log` JOIN orders on orders.id=activity_log.pid left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.license_type='Renewal' and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject not like '%visit%' and activity_log.added_by='".$users['id']."' and ( tbl_lead_product.product_type_id in (6,7) or tbl_lead_product.product_id is NULL)");

$final_log_call=$log_call;
$total_lac+=$final_log_call;

// stage updates count by virendra
$stage_update_count=getSingleresult("SELECT count(DISTINCT(lead_modify_log.lead_id)) as stage_update_count FROM `lead_modify_log` JOIN orders on orders.id=lead_modify_log.lead_id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.license_type='Renewal' and orders.status='Approved' and date(lead_modify_log.created_date) = '".$dat."' and lead_modify_log.type = 'Stage' and lead_modify_log.created_by='".$users['id']."' and ( tbl_lead_product.product_type_id in (6,7) or tbl_lead_product.product_id is NULL)");

$final_stage_update_count=$stage_update_count;
$total_stage_update_count+=$final_stage_update_count;
// end
 
     if($final_log_call > 0 || $final_stage_update_count > 0)
       { 
        $data_avl = 1;
        $body.='<tr>
            <td width="128" >
                <p align="center">
                    '.$i.'
                </p>
            </td>
            <td width="155" >
                <p align="center">
                    '.$users['name'].'
                </p>
            </td>
            
            <td width="97" >
                <p align="center">
                    '.$final_log_call.'
                </p>
            </td>

            <td width="97" >
                <p align="center">
                    '.$stage_update_count.'
                </p>
            </td>
        </tr>';
         $i++;
       }
       
       
       
}     
       if($data_avl == 0)
       {
         $body.='<tr>
            <td colspan="4" >
                <p align="center">
                    Data Not Found
                </p>
            </td>
           
        </tr>';
       }
        $body.='<tr>
            <td width="283" colspan="2" class="c2" >
                <p align="center">
                    <strong>Total</strong>
                </p>
            </td>
            
            <td width="97" class="c2">
                <p align="center">
                    <strong>'.$total_lac.'</strong>
                </p>
            </td>

            <td width="97" class="c2">
                <p align="center">
                    <strong>'.$total_stage_update_count.'</strong>
                </p>
            </td>
        </tr>
    </tbody>
</table>

</td>

<td valign="top">
<table  class ="TFtable" style="width:100%;font-size:12px" border="1" cellspacing="0" cellpadding="0">
<tbody>

        <tr>
            <td width="128" class="c3">
                <p align="center">
                    <strong>Sr. Number</strong>
                </p>
            </td>
            <td width="128" class="c3">
                <p align="center">
                    <strong>Account Name</strong>
                </p>
            </td>
            <td width="128" class="c3">
                <p align="center">
                    <strong>Stage From</strong>
                </p>
            </td>
            <td width="128" class="c3">
                <p align="center">
                    <strong>Stage To</strong>
                </p>
            </td>
            <td width="128" class="c3">
                <p align="center">
                    <strong>Updated By</strong>
                </p>
            </td>
            <td width="128" class="c3">
                <p align="center">
                    <strong>License End Month</strong>
                </p>
            </td>
            
        </tr>';
        $stage_data=db_query("select lml.*,o.company_name as company_name,MONTHNAME(o.license_end_date) as license_end_month,users.name as user_name from lead_modify_log as lml JOIN orders as o on o.id=lml.lead_id left join users on users.id=lml.created_by where o.status = 'Approved' and o.license_type='Renewal' and o.team_id='".$data['id']."' and lml.created_by in (".$idds.") and lml.type='Stage' and date(lml.created_date) = '".$dat."'");
        $i=1;
    while($loop_data=db_fetch_array($stage_data))
{
   

        $body.='<tr>
            <td width="128" >
                <p align="center">
                    '.$i.'
                </p>
            </td>
            <td width="155" >
                <p align="center">
                    '.$loop_data['company_name'].'
                </p>
            </td>
             <td width="155" >
                <p align="center">
                    '.$loop_data['previous_name'].'
                </p>
            </td>
             <td width="155" >
                <p align="center">
                    '.$loop_data['modify_name'].'
                </p>
            </td>
            
             <td width="155" >
                <p align="center">
                    '.$loop_data['user_name'].'
                </p>
            </td>
            <td width="155" >
                <p align="center">
                    '.$loop_data['license_end_month'].'
                </p>
            </td>
        </tr>';
        $i++;
}
 
 if($i <= 1)
 {
    $body.='<tr>
            <td colspan="6" width="128" >
                <p align="center">
                    Data not found
                </p>
            </td>
            
        </tr>';
 }
        
     $body.='</tbody>
</table>

</td>
<tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Thanks &amp; Regards,</p>
<p>SketchUp DR Support Team.</p>';
		 
		 
			$body .='</div>';

             $manager_email=getSingleresult("select email from users where user_type='MNGR' and team_id='".$data['id']."' and status='Active'");
            
             $addTo[] = $manager_email;
           
            $addCc[] = "prashant.dongrikar@arkinfo.in"; 
			$addCc[] = "rajeshri.shriyan@arkinfo.in";
			$addCc[] = "amjad.pathan@arkinfo.in";	  

            sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
 
$log_call =0;
$total_lac=0;
$total_stage_update_count=0;
$stage_update_count=0;
$varlog_call=0;
$varstage_update_count=0;
}

}
?>
 