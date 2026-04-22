<?php include('includes/include.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(date('l')!='Sunday')
{
ini_set('max_execution_time', 0);
            
$resellers=db_query("select id,name from partners where id != '116' and status='Active'");

while($data=db_fetch_array($resellers))
{
    
    if(date('l')=='Monday'){            
        $dat=date('Y-m-d',strtotime("-2 days"));
        $setSubject = "Daily Activity Report - ".$data['name'].' '.date('jS F Y',strtotime("-2 days")).".";
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
          $setSubject = "Daily Activity Report - ".$data['name'].' '.date('jS F Y',strtotime("-1 days")).".";
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
//echo $tcids; die;
$varqualified_data=getSingleresult("select count(orders.id) as leads from orders left join tbl_lead_product on orders.id=tbl_lead_product.lead_id  where  orders.team_id='".$data['id']."' and orders.license_type='Commercial' and date(orders.approval_time) = '".$dat."' and orders.status='Approved' and orders.created_by in (".$idds.") and ( tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL)");
//end////
$varvisit_data=getSingleresult("SELECT count(activity_log.id) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id =tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.license_type='Commercial' and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject in ('Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech. Support Visit','Validation Visit','BD Visit') and activity_log.added_by in (".$tcids.") and ( tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL)");
$vardvr_data=getSingleresult("SELECT count(id) FROM orders left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.license_type='Commercial' and ((date(orders.created_date) = '".$dat."') or (date(orders.date_dvr) = '".$dat."') ) and orders.is_dr=1 and orders.created_by in (".$tcids.") and ( tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL) ");
$dvr_data_raw=getSingleresult("SELECT count(*) FROM `activity_log` left JOIN raw_leads on raw_leads.id=activity_log.pid where raw_leads.team_id='".$data['id']."'  and (date(activity_log.created_date) = '".$dat."') and activity_log.call_subject in ('Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech. Support Visit','Validation Visit','BD Visit') and activity_log.activity_type like '%raw%'   and activity_log.added_by in (".$idds.") and (raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) ");
$var_dvr_lapsed=getSingleresult("SELECT count(*) FROM `activity_log` left JOIN lapsed_orders on lapsed_orders.id=activity_log.pid where lapsed_orders.team_id='".$data['id']."' and lapsed_orders.license_type='Commercial' and (date(activity_log.created_date) = '".$dat."') and activity_log.call_subject in ('Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech. Support Visit','Validation Visit','BD Visit')  and activity_log.added_by in (".$idds.")");
$vardvr_data+=$varvisit_data+$dvr_data_raw+$var_dvr_lapsed;
$vartotal_dvr+=$vardvr_data;
//
$varlog_call=getSingleresult("SELECT count(*) FROM `activity_log` left JOIN orders on orders.id=activity_log.pid left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.license_type='Commercial' and (date(activity_log.created_date) = '".$dat."') and activity_log.call_subject not like '%visit%' and activity_log.added_by in (".$idds.") and ( tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL)");

$varlog_call_lapsed=getSingleresult("SELECT count(*) FROM `activity_log` left JOIN lapsed_orders on lapsed_orders.id=activity_log.pid where lapsed_orders.team_id='".$data['id']."' and lapsed_orders.license_type='Commercial' and (date(activity_log.created_date) = '".$dat."') and activity_log.call_subject not like '%visit%' and activity_log.added_by in (".$idds.")");
$varlog_call_raw=getSingleresult("SELECT count(*) FROM `activity_log` left JOIN raw_leads on raw_leads.id=activity_log.pid where raw_leads.team_id='".$data['id']."'  and (date(activity_log.created_date) = '".$dat."') and activity_log.call_subject not like '%visit%' and activity_log.activity_type like '%raw%'   and activity_log.added_by in (".$idds.") and (raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) ");
$varlog_call+=$varlog_call_raw+$varlog_call_lapsed;
     
// $mail->Body='<style type="text/css"> 
$body='<style type="text/css"> 
.TFtable{ width:100%; border-collapse:collapse; font-family:Arial; } 
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
  </style><p>Dear Sir,</p>
<p>Greetings for the day.!</p>
<p>'.$data['name'].' yesterday&rsquo;s report suggest we had total </p>
<ul>

<li>'.$varqualified_data.' Qualified Account(s)</li>
<li>'.$vartotal_dvr.' DVR Visits Updated</li>
<li>'.$varlog_call.' Log a calls</li>
</ul>
<p>Which are updated @ SketchUp DR Portal dated '.date('d-M-Y',strtotime($dat)).'. Below is user wise summary for your reference :</p>

<table width="100%">
<tr>
<td>
<table  class ="TFtable" style="width:80%;font-size:12px" border="1" cellspacing="0" cellpadding="0">
<tbody>
        <tr>
            <td width="664" colspan="5"  class="c2">
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
            <td width="140" class="c3" >
                <p align="center">
                    <strong>Data Received</strong>
                </p>
            </td>
            <td width="144" class="c3" >
                <p align="center" >
                    <strong>DVR Added</strong>
                </p>
            </td>
           
            <td width="97" class="c3">
                <p align="center">
                    <strong>Calls</strong>
                </p>
            </td>
        </tr>
        <tr>
            <td width="140" class="c3">
                <p align="center">
                    <strong>Qualified</strong>
                </p>
            </td>
            <td width="144" class="c3">
                <p align="center">
                    <strong>Daily Visit Updated</strong>
                </p>
            </td>
           
            <td width="97" class="c3" >
                <p align="center">
                    <strong>(Log A Calls)</strong>
                </p>
            </td>
        </tr>';
        $users2=db_query("select id,name,email from users where team_id='".$data['id']."' and status='Active' ");
        $i=1;
    while($users=db_fetch_array($users2))
{
    $qualified_data=getSingleresult("select count(orders.id) as leads from orders left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where  orders.team_id='".$data['id']."' and orders.license_type='Commercial' and  date(orders.approval_time) = '".$dat."' and orders.status='Approved' and orders.created_by='".$users['id']."' and ( tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL)");   
$total_qualified+=$qualified_data;
if(getSingleresult("select role from users where id='".$users['id']."'")!='TC'){
$visit_data=getSingleresult("SELECT count(activity_log.id) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.license_type='Commercial' and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject in ('Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech. Support Visit','Validation Visit','BD Visit') and activity_log.added_by='".$users['id']."' and ( tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL) ");
$dvr_raw=getSingleresult("SELECT count(*) FROM `activity_log` JOIN raw_leads on raw_leads.id=activity_log.pid where raw_leads.team_id='".$data['id']."' and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject in ('Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech. Support Visit','Validation Visit','BD Visit') and activity_log.activity_type like '%raw%' and activity_log.added_by='".$users['id']."' and (raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL)");
$log_dvr_lapsed=getSingleresult("SELECT count(*) FROM `activity_log` JOIN lapsed_orders on lapsed_orders.id=activity_log.pid where lapsed_orders.team_id='".$data['id']."' and lapsed_orders.license_type='Commercial' and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject in ('Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech. Support Visit','Validation Visit','BD Visit') and activity_log.added_by='".$users['id']."'");
$dvr_data=getSingleresult("SELECT count(id) FROM orders left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.license_type='Commercial' and ((date(orders.created_date) = '".$dat."') or (date(orders.date_dvr) = '".$dat."') ) and orders.is_dr=1 and orders.created_by='".$users['id']."' and ( tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL)");
$dvr_data+=$visit_data+$dvr_raw+$log_dvr_lapsed;
$total_dvr+=$dvr_data;
//
}
else
{
    $dvr_data=0;

}
$log_call=getSingleresult("SELECT count(*) FROM `activity_log` JOIN orders on orders.id=activity_log.pid left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.license_type='Commercial' and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject not like '%visit%' and activity_log.added_by='".$users['id']."' and ( tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL)");

$log_call_lapsed=getSingleresult("SELECT count(*) FROM `activity_log` JOIN lapsed_orders on lapsed_orders.id=activity_log.pid where lapsed_orders.team_id='".$data['id']."' and lapsed_orders.license_type='Commercial' and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject not like '%visit%' and activity_log.added_by='".$users['id']."'");

$log_call_raw=getSingleresult("SELECT count(*) FROM `activity_log` JOIN raw_leads on raw_leads.id=activity_log.pid where raw_leads.team_id='".$data['id']."' and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject not like '%visit%' and activity_log.activity_type like '%raw%' and activity_log.added_by='".$users['id']."' and (raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL)");
$final_log_call=$log_call+$log_call_raw+$log_call_lapsed;
$total_lac+=$final_log_call;



 //$mail->AddCC($users['email']);

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
            <td width="140" >
                <p align="center">
                   '.$qualified_data.'
                </p>
            </td>
            <td width="144" >
                <p align="center">
                
                   '.((getSingleresult("select role from users where id='".$users['id']."'")=='TC')?'N/A':$dvr_data).'
                </p>
            </td>
            
            <td width="97" >
                <p align="center">
                    '.$final_log_call.'
                </p>
            </td>
        </tr>';
        $i++;
}
        $body.='<tr>
            <td width="283" colspan="2" class="c2" >
                <p align="center">
                    <strong>Total</strong>
                </p>
            </td>
            <td width="140" class="c2">
                <p align="center">
                    <strong>'.$total_qualified.'</strong>
                </p>
            </td>
            <td width="144" class="c2">
                <p align="center">
                    <strong>'.$total_dvr.'</strong>
                    
                </p>
            </td>
            
            <td width="97" class="c2">
                <p align="center">
                    <strong>'.$total_lac.'</strong>
                </p>
            </td>
        </tr>
    </tbody>
</table>
</td>

<td valign="top">
<table  class ="TFtable" style="width:80%;font-size:12px" border="1" cellspacing="0" cellpadding="0">
<tbody>
        <tr>
            <td width="664" colspan="10"  class="c2">
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
            <td width="140" colspan="2" class="c3" >
                <p align="center">
                    <strong>Fresh Call</strong>
                </p>
            </td>
            <td width="144" colspan="2" class="c3" >
                <p align="center" >
                    <strong>Follow-up call</strong>
                </p>
            </td>
           
            <td width="97" colspan="2" class="c3">
                <p align="center">
                    <strong>Total Calls</strong>
                </p>
            </td>
            <td width="140" rowspan="2" class="c3" >
                <p align="center">
                    <strong>Lead Gen</strong>
                </p>
            </td>
            <td width="140" rowspan="2" class="c3" >
                <p align="center">
                    <strong>Closure</strong>
                </p>
            </td>
        </tr>
        <tr>
            <td width="70" class="c3">
                <p align="center">
                    <strong>DTP/Printing</strong>
                </p>
            </td>
            <td width="70" class="c3">
            <p align="center">
                <strong>Other</strong>
            </p>
        </td>
            <td width="72" class="c3">
                <p align="center">
                    <strong>DTP/Printing</strong>
                </p>
            </td>
            <td width="72" class="c3">
            <p align="center">
                <strong>Other</strong>
            </p>
        </td>
            <td width="48.5" class="c3" >
                <p align="center">
                    <strong>Fresh Call</strong>
                </p>
            </td>
            <td width="48.5" class="c3" >
                <p align="center">
                    <strong>Follow-up call</strong>
                </p>
            </td>
        </tr>';
       
        $users2=db_query("select id,name,email from users where team_id='".$data['id']."' and status='Active' ");
        $i=1;
    while($users=db_fetch_array($users2))
{
        $select_lead = db_query("select activity_log.id from activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and date(activity_log.created_date) = '".$dat."' and activity_log.added_by='".$users['id']."' and call_subject in ('Fresh Call','Follow-up Call') group by activity_log.pid order by activity_log.created_date asc");
        $sub_id = array();
        
        while ($call_id = db_fetch_array($select_lead)) 
        {
            $sub_id[] = $call_id['id'];
        }
        //print_r($sub_id);
        $Callid = implode(',', $sub_id);

        $select_lapsed = db_query("select activity_log.id from activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id where lapsed_orders.team_id='".$data['id']."' and lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and activity_log.activity_type='Lead' and date(activity_log.created_date) = '".$dat."' and activity_log.added_by='".$users['id']."' and call_subject in ('Fresh Call','Follow-up Call') group by activity_log.pid order by activity_log.created_date asc");
        $lapsed_id = array();
        
        while ($callLapsedid = db_fetch_array($select_lapsed)) 
        {
            $lapsed_id[] = $callLapsedid['id'];
        }
        $Callid_lapsed = implode(',', $lapsed_id);

    
        $select_raw = db_query("select activity_log.id from activity_log left join raw_leads on activity_log.pid=raw_leads.id where raw_leads.team_id='".$data['id']."' and raw_leads.product_type_id in (1,2) and activity_log.activity_type='Raw' and date(activity_log.created_date) = '".$dat."' and activity_log.added_by='".$users['id']."' and call_subject in ('Fresh Call','Follow-up Call') group by activity_log.pid order by activity_log.created_date asc");
        $raw_id = array();
        
        while ($callRawid = db_fetch_array($select_raw)) 
        {
            $raw_id[] = $callRawid['id'];
        }
        $Callid_raw = implode(',', $raw_id);
       
        // if(!empty($Callid_raw)){
        //     $raw_sub .= " and activity_log.id in (".$Callid_raw.")";
        // }
        


$fresh_callDTP =  (getSingleresult("select sum(TotalFresh) from (select count(activity_log.pid) TotalFresh FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.team_id='".$data['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.added_by='".$users['id']."' and activity_log.id in (".(!empty($Callid)?$Callid:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src")) 
 + (getSingleresult("select sum(TotalFresh) from (select count(distinct(activity_log.pid)) TotalFresh FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.team_id='".$data['id']."' and lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.activity_type='Lead' and activity_log.added_by='".$users['id']."' and activity_log.id in (".(!empty($Callid_lapsed)?$Callid_lapsed:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src")) 
 + (getSingleresult("select sum(TotalFresh) from (select count(activity_log.pid) TotalFresh FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where raw_leads.team_id='".$data['id']."' and raw_leads.product_type_id in (1,2) and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.activity_type='Raw' and activity_log.added_by='".$users['id']."' and activity_log.id in (".(!empty($Callid_raw)?$Callid_raw:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"));
$total_fresh_callDTP+=$fresh_callDTP;

$fresh_callOthers = (getSingleresult("select sum(TotalFresh) from (select count(activity_log.pid) TotalFresh FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where  orders.team_id='".$data['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2)  and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.added_by='".$users['id']."' and activity_log.id in (".(!empty($Callid)?$Callid:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalFresh) from (select count(distinct(activity_log.pid)) TotalFresh FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.team_id='".$data['id']."' and lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.added_by='".$users['id']."' and activity_log.id in (".(!empty($Callid_lapsed)?$Callid_lapsed:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalFresh) from (select count(distinct(activity_log.pid)) TotalFresh FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where raw_leads.team_id='".$data['id']."' and ( raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.activity_type='Raw' and activity_log.added_by='".$users['id']."' and activity_log.id in (".(!empty($Callid_raw)?$Callid_raw:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"));
$total_fresh_callOthers+=$fresh_callOthers;

$follow_callDTP =(getSingleresult("select sum(TotalFollow) from (select count(distinct(activity_log.pid)) TotalFollow FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where  orders.team_id='".$data['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2)  and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Follow-up Call' and activity_log.added_by='".$users['id']."' and activity_log.id in (".(!empty($Callid)?$Callid:'NULL').") group by activity_log.pid order by activity_log.created_date asc ) src"))
+ (getSingleresult("select sum(TotalFollow) from (select count(distinct(activity_log.pid)) TotalFollow FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.team_id='".$data['id']."' and lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2)  and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Follow-up Call' and activity_log.added_by='".$users['id']."' and activity_log.id in (".(!empty($Callid_lapsed)?$Callid_lapsed:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalFollow) from (select count(distinct(activity_log.pid)) TotalFollow FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where raw_leads.team_id='".$data['id']."' and ( raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Follow-up Call' and activity_log.activity_type='Raw' and activity_log.added_by='".$users['id']."' and activity_log.id in (".(!empty($Callid_raw)?$Callid_raw:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"));
$total_follow_callDTP+=$follow_callDTP;

$follow_callOthers = (getSingleresult("select sum(TotalFollow) from (select count(distinct(activity_log.pid)) TotalFollow FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where  orders.team_id='".$data['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2)  and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Follow-up Call' and activity_log.added_by='".$users['id']."' and activity_log.id in (".(!empty($Callid)?$Callid:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalFollow) from (select count(distinct(activity_log.pid)) TotalFollow FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.team_id='".$data['id']."' and lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2)  and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Follow-up Call' and activity_log.added_by='".$users['id']."' and activity_log.id in (".(!empty($Callid_lapsed)?$Callid_lapsed:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalFollow) from (select count(distinct(activity_log.pid)) TotalFollow FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where raw_leads.team_id='".$data['id']."' and ( raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Follow-up Call' and activity_log.activity_type='Raw' and activity_log.added_by='".$users['id']."' and activity_log.id in (".(!empty($Callid_raw)?$Callid_raw:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"));
$total_follow_callOthers+=$follow_callOthers;

$total_freshCalls = $fresh_callDTP + $fresh_callOthers;

$totalFreshCalls+=$total_freshCalls;

$total_followCalls= $follow_callDTP + $follow_callOthers;

$totalFollowUPCalls+=$total_followCalls;

$select_stage = db_query("select * from lead_modify_log left join orders on lead_modify_log.lead_id=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and date(lead_modify_log.created_date) = '".$dat."' and (orders.created_by= '".$users['id']."' or orders.allign_to = '".$users['id']."') and lead_modify_log.modify_name='EU PO Issued'");
$lead_ids = array();

while ($lid = db_fetch_array($select_stage)) 
{
    $lead_ids[] = $lid['lead_id'];
}
$lead_id = implode(',', $lead_ids);
if(!empty($lead_id)){
    $contd .= " and lead_modify_log.lead_id not in (".$lead_id.")";
}


$lead_gen = getSingleresult("select count(distinct(orders.id)) FROM lead_modify_log left join orders on lead_modify_log.lead_id=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and lead_modify_log.modify_name ='Quote' and lead_modify_log.previous_name!='Quote' $contd and date(lead_modify_log.created_date) = '".$dat."' and (orders.created_by='".$users['id']."' or orders.allign_to='".$users['id']."') ");

$total_leadGen+=$lead_gen;

$closure = getSingleresult("select count(distinct(orders.id)) FROM  orders left join lead_modify_log on orders.id=lead_modify_log.lead_id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$data['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and lead_modify_log.modify_name ='EU PO Issued' and lead_modify_log.previous_name!='EU PO Issued' and date(lead_modify_log.created_date) = '".$dat."' and (orders.created_by='".$users['id']."' or orders.allign_to='".$users['id']."')");
$total_closure+=$closure;
 //$mail->AddCC($users['email']);
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
            <td width="70" >
                <p align="center">
                   '.$fresh_callDTP.'
                </p>
                </td>
                <td width="70" >
                <p align="center">
                '.$fresh_callOthers.'
             </p>
            </td>
            <td width="72" >
                <p align="center">
                
                   '.$follow_callDTP.'
                </p>
            </td>
            <td width="72" >
            <p align="center">
            
               '.$follow_callOthers.'
            </p>
        </td>
            <td width="48.5" >
                <p align="center">
                    '.$total_freshCalls.'
                </p>
            </td>
            <td width="48.5" >
                <p align="center">
                    '.$total_followCalls.'
                </p>
            </td>
            <td width="140">
            <p align="center">
                '.$lead_gen.'
            </p>
        </td>
        <td width="140">
            <p align="center">
                '.$closure.'
            </p>
        </td>
        </tr>';
        $i++;
}
        $body.='<tr>
            <td width="283" colspan="2" class="c2" >
                <p align="center">
                    <strong>Total</strong>
                </p>
            </td>
            <td width="70" class="c2">
                <p align="center">
                    <strong>'.$total_fresh_callDTP.'</strong>
                </p>
            </td>
            <td width="70" class="c2">
                <p align="center">
                    <strong>'.$total_fresh_callOthers.'</strong>
                </p>
            </td>
            <td width="72" class="c2">
                <p align="center">
                    <strong>'.$total_follow_callDTP.'</strong>
                    
                </p>
            </td>
            <td width="72" class="c2">
                <p align="center">
                    <strong>'.$total_follow_callOthers.'</strong>
                    
                </p>
            </td>
            <td width="48.5" class="c2">
                <p align="center">
                    <strong>'.$totalFreshCalls.'</strong>
                </p>
            </td>
            <td width="48.5" class="c2">
            <p align="center">
                <strong>'.$totalFollowUPCalls.'</strong>
            </p>
        </td>
        <td width="140" class="c2">
            <p align="center">
                <strong>'.$total_leadGen.'</strong>
            </p>
        </td>
        <td width="140" class="c2">
            <p align="center">
                <strong>'.$total_closure.'</strong>
            </p>
        </td>
        
        </tr>
    </tbody>
</table>

</td>


<tr>
</table>
<p>&nbsp;</p>
<p>Note- This report is based on the updates done in DR Portal till 11:59 AM</p>
<p>&nbsp;</p>
<p>Thanks &amp; Regards,</p>
<p>SketchUp DR Support Team.</p>';
         
         
            $body .='</div>';
            // $users1=db_query("select email from users where team_id='".$data['id']."' and user_type!='MNGR' and status='Active'");
            // while($user_email=db_fetch_array($users1)){
            //     $mail->AddAddress($user_email['email']);
            // }
            $manager_email=getSingleresult("select email from users where user_type='MNGR' and team_id='".$data['id']."' and status='Active'");
            // $mail->AddAddress($manager_email);
            $addTo[] = $manager_email;
            $sm_email=getSingleresult("select users.email as email from users join partners on partners.sm_user=users.id where partners.id='".$data['id']."'");
            if($sm_email){
            $addCc[] = $sm_email;
            $addCc[] = "prashant.dongrikar@arkinfo.in";
            $addCc[] = "kailash.bhurke@arkinfo.in"; 
            }
            
            sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
           
$qualified_data=0;
$dvr_data =0;

$log_call =0;
$total_qualified=0;
$total_dvr=0;
$total_lac=0;
$vartotal_dvr=0;
$vardvr_data=0;
$varlog_call=0;
$varqualified_data=0;
$varvisit_data=0;
$varlog_call_raw=0;
$log_call_raw=0;
$dvr_data_raw=0;
$dvr_raw=0;
$var_dvr_lapsed=0;
$log_dvr_lapsed=0;
$fresh_callDTP=0;
$fresh_callOthers =0;
$follow_callDTP =0;
$follow_callOthers =0;
$total_freshCalls=0;
$total_followCalls=0;
$lead_gen=0;
$closure=0;
$total_fresh_callDTP=0;
$total_fresh_callOthers=0;
$total_follow_callDTP=0;
$total_follow_callOthers=0;
$totalFreshCalls=0;
$totalFollowUPCalls=0;
$total_leadGen=0;
$total_closure=0;
}

}
?>
 