<?php include('includes/include.php');
if(date('l')!='Sunday')
{
    
ini_set('max_execution_time', 0);

/**for monthly report */
if(date('d')=='01')
{
    $dat1=date("Y-m-01", strtotime("-1 months"));
    $dat2=date('Y-m-t', strtotime("-1 months"));
}
else
{
$dat1=date('m');
$dat2=date('Y');
} 
 
/**for daily report date */
 
         $dat=date('Y-m-d'); 
         //$dat=date('Y-m-d',strtotime("-1 days"));
          if($_REQUEST['d_check']) 
          {
          $dat=$_REQUEST['d_check'];
          }
          $mail->Subject = "VAR Customer Reach Report- ".date('d M Y',strtotime($dat))." @ DR Portal";
        

	 
$mail->Body='<style type="text/css"> 
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


<p>Which are updated @ SketchUp DR Portal dated '.date('d-M-Y',strtotime($dat)).'.  Below is summary for your reference  :</p>';

/**Todays top header */
$partner_list=db_query("select id,name from partners where id not in (45,25,37,53) and product_id!=4 and status='Active' order by name asc");
while($row=db_fetch_array($partner_list))
{            
    $users1 = db_query("select id,role from users where team_id='" . $row['id'] . "' and status='Active' ");
        $ids = array();

        while ($uid = db_fetch_array($users1)) 
        {
            $ids[] = $uid['id'];
        }
        $user_ids = implode(',', $ids);

        $selectLead =db_query("select activity_log.id from activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and date(activity_log.created_date) = '".$dat."' and orders.license_type='Commercial' and activity_log.activity_type='Lead' and tbl_lead_product.product_type_id in (1,2) and call_subject in ('Fresh Call','Follow-up Call') group by activity_log.pid order by activity_log.created_date asc");
        $sub_id = array();
        
        while ($call_id = db_fetch_array($selectLead)) 
        {
            $sub_id[] = $call_id['id'];
        }
        //print_r($sub_id);
        $CallId = implode(',', $sub_id);

        $selectLapsed =db_query("select activity_log.id from activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id where lapsed_orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and date(activity_log.created_date) = '".$dat."' and lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and activity_log.activity_type='Lead' and call_subject in ('Fresh Call','Follow-up Call') group by activity_log.pid order by activity_log.created_date asc");
        $lapsed_id = array();
        
        while ($callLapsedid = db_fetch_array($selectLapsed)) 
        {
            $lapsed_id[] = $callLapsedid['id'];
        }
        $CallidLapsed = implode(',', $lapsed_id);

    
        $selectRaw = db_query("select activity_log.id from activity_log left join raw_leads on activity_log.pid=raw_leads.id where raw_leads.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and date(activity_log.created_date) = '".$dat."' and raw_leads.product_type_id in (1,2) and activity_log.activity_type='Raw' and call_subject in ('Fresh Call','Follow-up Call') group by activity_log.pid order by activity_log.created_date asc");
        $raw_id = array();
        
        while ($callRawid = db_fetch_array($selectRaw)) 
        {
            $raw_id[] = $callRawid['id'];
        }
        $CallidRaw = implode(',', $raw_id);

$freshCallDTP = (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where  orders.team_id='".$row['id']."' and orders.license_type='Commercial' and activity_log.activity_type='Lead' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.added_by in (".$user_ids.") and activity_log.id in (".(!empty($CallId)?$CallId:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.team_id='".$row['id']."' and lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.activity_type='Lead' and activity_log.added_by in (".$user_ids.") and activity_log.id in (".(!empty($CallidLapsed)?$CallidLapsed:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src")) 
+ (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where raw_leads.team_id='".$row['id']."' and ( raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.activity_type='Raw' and activity_log.added_by in (".$user_ids.") and activity_log.id in (".(!empty($CallidRaw)?$CallidRaw:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"));
$totalFreshCallDTP+=$freshCallDTP;

$freshCallOthers = (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.team_id='".$row['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and activity_log.activity_type='Lead' and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.added_by in (".$user_ids.") and activity_log.id in (".(!empty($CallId)?$CallId:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.team_id='".$row['id']."' and lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2)  and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.added_by in (".$user_ids.") and activity_log.id in (".(!empty($CallidLapsed)?$CallidLapsed:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where raw_leads.team_id='".$row['id']."' and ( raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.activity_type='Raw' and activity_log.added_by in (".$user_ids.") and activity_log.id in (".(!empty($CallidRaw)?$CallidRaw:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"));
$totalFreshCallOthers+=$freshCallOthers;

$followCallDTP = (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.id)) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.license_type='Commercial' and activity_log.activity_type='Lead' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and activity_log.call_subject='Follow-up Call' and activity_log.id in (".(!empty($CallId)?$CallId:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.id)) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2)  and industry.log_status=1 and activity_log.call_subject='Follow-up Call' and activity_log.id in (".(!empty($CallidLapsed)?$CallidLapsed:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.id)) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where ( raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=1 and activity_log.call_subject='Follow-up Call' and activity_log.activity_type='Raw' and activity_log.id in (".(!empty($CallidRaw)?$CallidRaw:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"));
$totalFollow_callDTP+=$followCallDTP;

$followCallOthers = (getSingleresult("select sum(TotalByOrder) TotalOrders from (select count(distinct(activity_log.id)) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=0 and activity_log.activity_type='Lead' and activity_log.call_subject='Follow-up Call' and activity_log.id in (".(!empty($CallId)?$CallId:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalByOrder) TotalOrders from (select count(distinct(activity_log.id)) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=0 and activity_log.call_subject='Follow-up Call' and activity_log.id in (".(!empty($CallidLapsed)?$CallidLapsed:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalByOrder) TotalOrders from (select count(distinct(activity_log.id)) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where ( raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=0 and activity_log.call_subject='Follow-up Call' and activity_log.activity_type='Raw' and activity_log.id in (".(!empty($CallidRaw)?$CallidRaw:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"));

$totalFollow_callOthers+=$followCallOthers;

$Total_freshCalls = $freshCallDTP + $freshCallOthers;
$TotalFresh_calls+=$Total_freshCalls;

$totalFollowCalls = $followCallDTP + $followCallOthers;
$totalFollowUP_calls+=$totalFollowCalls;

$select_stage = db_query("select * from lead_modify_log left join orders on lead_modify_log.lead_id=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$row['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and date(lead_modify_log.created_date) = '".$dat."' and (orders.created_by in (".$user_ids.") or orders.allign_to in (".$user_ids.")) and lead_modify_log.modify_name='EU PO Issued'");
$lead_ids = array();

while ($lid = db_fetch_array($select_stage)) 
{
$lead_ids[] = $lid['lead_id'];
}
$lead_id = implode(',', $lead_ids);
if(!empty($lead_id)){
$contd .= " and lead_modify_log.lead_id not in (".$lead_id.")";
}

$leadGen = getSingleresult("select count(distinct(orders.id)) FROM lead_modify_log left join orders on lead_modify_log.lead_id=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$row['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and lead_modify_log.modify_name ='Quote' and lead_modify_log.previous_name!='Quote' $contd and date(lead_modify_log.created_date) = '".$dat."' and (orders.created_by in (".$user_ids.") or orders.allign_to in (".$user_ids."))");
$totalLeadGen+=$leadGen;

$Closure = getSingleresult("select count(distinct(orders.id)) FROM  lead_modify_log join orders on lead_modify_log.lead_id=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$row['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and lead_modify_log.modify_name ='EU PO Issued' and lead_modify_log.previous_name!='EU PO Issued' and date(lead_modify_log.created_date) = '".$dat."' and (orders.created_by in (".$user_ids.") or orders.allign_to in (".$user_ids."))");
$totalClosure+=$Closure;

};

$mail->Body.='<table width="100%">

<tr>
<td valign="top">
<table  class ="TFtable" style="width:100%;font-size:12px" border="1" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td width="664" colspan="10"  class="c2">
    <p align="center">
        <strong>Todays Report</strong>
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
                    <strong>VAR Organization Name</strong>
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
            <td width="155" rowspan="2" class="c3" >
                <p align="center">
                    <strong>Lead Gen</strong>
                </p>
            </td>
            <td width="155" rowspan="2" class="c3" >
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
        </tr>
        
        <tr>
            <td width="283" colspan="2" class="c2" >
                <p align="center">
                    <strong>Total</strong>
                </p>
            </td>
            
            <td width="70" class="c2">
                <p align="center">
                    <strong>'.$totalFreshCallDTP.'</strong>
                </p>
            </td>
            <td width="70" class="c2">
                <p align="center">
                    <strong>'.$totalFreshCallOthers.'</strong>
                </p>
            </td>
            <td width="72" class="c2">
                <p align="center">
                    <strong>'.$totalFollow_callDTP.'</strong>
                    
                </p>
            </td>
            <td width="72" class="c2">
                <p align="center">
                    <strong>'.$totalFollow_callOthers.'</strong>
                    
                </p>
            </td>
            <td width="48.5" class="c2">
                <p align="center">
                    <strong>'.$TotalFresh_calls.'</strong>
                </p>
            </td>
            <td width="48.5" class="c2">
            <p align="center">
                <strong>'.$totalFollowUP_calls.'</strong>
            </p>
        </td>
        <td width="140" class="c2">
            <p align="center">
                <strong>'.$totalLeadGen.'</strong>
            </p>
        </td>
        <td width="140" class="c2">
            <p align="center">
                <strong>'.$totalClosure.'</strong>
            </p>
        </td>
        </tr>';

        $i=1;

        $partners=db_query("select id,name from partners where id not in (45,25,37,53) and product_id!=4 and status='Active' order by name asc");
        while($row=db_fetch_array($partners))
        {            
            $users1 = db_query("select id,role from users where team_id='" . $row['id'] . "' and status='Active' ");
                $ids = array();

                while ($uid = db_fetch_array($users1)) 
                {
                    $ids[] = $uid['id'];
                }
                $user_ids = implode(',', $ids);

                $select_lead =db_query("select activity_log.id from activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and date(activity_log.created_date) = '".$dat."' and orders.license_type='Commercial' and activity_log.activity_type='Lead' and tbl_lead_product.product_type_id in (1,2) and call_subject in ('Fresh Call','Follow-up Call') group by activity_log.pid order by activity_log.created_date asc");
                $sub_id = array();
                
                while ($call_id = db_fetch_array($select_lead)) 
                {
                    $sub_id[] = $call_id['id'];
                }
                //print_r($sub_id);
                $Callid = implode(',', $sub_id);
        
                $select_lapsed =db_query("select activity_log.id from activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id where lapsed_orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and date(activity_log.created_date) = '".$dat."' and lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and activity_log.activity_type='Lead' and call_subject in ('Fresh Call','Follow-up Call') group by activity_log.pid order by activity_log.created_date asc");
                $lapsed_id = array();
                
                while ($callLapsedid = db_fetch_array($select_lapsed)) 
                {
                    $lapsed_id[] = $callLapsedid['id'];
                }
                $Callid_lapsed = implode(',', $lapsed_id);
        
            
                $select_raw = db_query("select activity_log.id from activity_log left join raw_leads on activity_log.pid=raw_leads.id where raw_leads.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and date(activity_log.created_date) = '".$dat."' and raw_leads.product_type_id in (1,2) and activity_log.activity_type='Raw' and call_subject in ('Fresh Call','Follow-up Call') group by activity_log.pid order by activity_log.created_date asc");
                $raw_id = array();
                
                while ($callRawid = db_fetch_array($select_raw)) 
                {
                    $raw_id[] = $callRawid['id'];
                }
                $Callid_raw = implode(',', $raw_id);

    $fresh_callDTP = (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where  orders.team_id='".$row['id']."' and orders.license_type='Commercial' and activity_log.activity_type='Lead' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.added_by in (".$user_ids.") and activity_log.id in (".(!empty($Callid)?$Callid:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
    + (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.team_id='".$row['id']."' and lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.activity_type='Lead' and activity_log.added_by in (".$user_ids.") and activity_log.id in (".(!empty($Callid_lapsed)?$Callid_lapsed:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src")) 
    + (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where raw_leads.team_id='".$row['id']."' and ( raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.activity_type='Raw' and activity_log.added_by in (".$user_ids.") and activity_log.id in (".(!empty($Callid_raw)?$Callid_raw:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"));
    $total_fresh_callDTP+=$fresh_callDTP;
      
    $fresh_callOthers = (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.team_id='".$row['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and activity_log.activity_type='Lead' and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.added_by in (".$user_ids.") and activity_log.id in (".(!empty($Callid)?$Callid:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
    + (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.team_id='".$row['id']."' and lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2)  and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.added_by in (".$user_ids.") and activity_log.id in (".(!empty($Callid_lapsed)?$Callid_lapsed:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
    + (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where raw_leads.team_id='".$row['id']."' and ( raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.activity_type='Raw' and activity_log.added_by in (".$user_ids.") and activity_log.id in (".(!empty($Callid_raw)?$Callid_raw:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"));
    $total_fresh_callOthers+=$fresh_callOthers;
    
    $follow_callDTP = (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.id)) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.license_type='Commercial' and activity_log.activity_type='Lead' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and activity_log.call_subject='Follow-up Call' and activity_log.id in (".(!empty($Callid)?$Callid:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
    + (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.id)) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2)  and industry.log_status=1 and activity_log.call_subject='Follow-up Call' and activity_log.id in (".(!empty($Callid_lapsed)?$Callid_lapsed:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
    + (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.id)) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where ( raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=1 and activity_log.call_subject='Follow-up Call' and activity_log.activity_type='Raw' and activity_log.id in (".(!empty($Callid_raw)?$Callid_raw:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"));
    $total_follow_callDTP+=$follow_callDTP;
    
    $follow_callOthers = (getSingleresult("select sum(TotalByOrder) TotalOrders from (select count(distinct(activity_log.id)) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=0 and activity_log.activity_type='Lead' and activity_log.call_subject='Follow-up Call' and activity_log.id in (".(!empty($Callid)?$Callid:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
    + (getSingleresult("select sum(TotalByOrder) TotalOrders from (select count(distinct(activity_log.id)) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=0 and activity_log.call_subject='Follow-up Call' and activity_log.id in (".(!empty($Callid_lapsed)?$Callid_lapsed:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"))
    + (getSingleresult("select sum(TotalByOrder) TotalOrders from (select count(distinct(activity_log.id)) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where ( raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=0 and activity_log.call_subject='Follow-up Call' and activity_log.activity_type='Raw' and activity_log.id in (".(!empty($Callid_raw)?$Callid_raw:'NULL').") group by activity_log.pid order by activity_log.created_date asc) src"));

    $total_follow_callOthers+=$follow_callOthers;
    
    $total_freshCalls = $fresh_callDTP + $fresh_callOthers;
    $totalFreshCalls+=$total_freshCalls;
    
    $total_followCalls = $follow_callDTP + $follow_callOthers;
    $totalFollowUPCalls+=$total_followCalls;
    
    $select_stage = db_query("select * from lead_modify_log left join orders on lead_modify_log.lead_id=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$row['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and date(lead_modify_log.created_date) = '".$dat."' and (orders.created_by in (".$user_ids.") or orders.allign_to in (".$user_ids.")) and lead_modify_log.modify_name='EU PO Issued'");
    $lead_ids = array();

    while ($lid = db_fetch_array($select_stage)) 
    {
        $lead_ids[] = $lid['lead_id'];
    }
    $lead_id = implode(',', $lead_ids);
    if(!empty($lead_id)){
        $contd .= " and lead_modify_log.lead_id not in (".$lead_id.")";
    }
    
    $lead_gen = getSingleresult("select count(distinct(orders.id)) FROM lead_modify_log left join orders on lead_modify_log.lead_id=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$row['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and lead_modify_log.modify_name ='Quote' and lead_modify_log.previous_name!='Quote' $contd and date(lead_modify_log.created_date) = '".$dat."' and (orders.created_by in (".$user_ids.") or orders.allign_to in (".$user_ids."))");

    // $lead_gen = getSingleresult("select count(distinct(lead_modify_log.lead_id)) FROM  lead_modify_log left join orders on lead_modify_log.lead_id=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where  orders.team_id='".$row['id']."' and orders.license_type='Commercial' and ( tbl_lead_product.product_type_id in (1,2) or tbl_lead_product.product_id is NULL) and lead_modify_log.modify_name ='Quote' and orders.stage!='EU PO Issued' and date(lead_modify_log.created_date) = '".$dat."' and (orders.created_by in (".$user_ids.") or orders.allign_to in (".$user_ids."))");
    $total_leadGen+=$lead_gen;
    
    $closure = getSingleresult("select count(distinct(orders.id)) FROM  lead_modify_log join orders on lead_modify_log.lead_id=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$row['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and lead_modify_log.modify_name ='EU PO Issued' and lead_modify_log.previous_name!='EU PO Issued' and date(lead_modify_log.created_date) = '".$dat."' and (orders.created_by in (".$user_ids.") or orders.allign_to in (".$user_ids."))");

    $total_closure+=$closure;
 
        $mail->Body.='<tr>
            <td width="128" >
                <p align="center">
                    '.$i.'
                </p>
            </td>
            <td width="155" >
                <p align="center">
                    '.$row['name'].'
                </p>
            </td>
            <td width="70" >
                <p align="center">
                   '.($fresh_callDTP?$fresh_callDTP:0).'
                </p>
                </td>
                <td width="70" >
                <p align="center">
                '.($fresh_callOthers?$fresh_callOthers:0).'
             </p>
            </td>
            <td width="72" >
                <p align="center">
                
                   '.($follow_callDTP?$follow_callDTP:0).'
                </p>
            </td>
            <td width="72" >
            <p align="center">
            
               '.($follow_callOthers?$follow_callOthers:0).'
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
      // }
       
       
       
 }     

        $mail->Body.='<tr>
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

</td>';

/**Total top header monthly report */
$partners_data=db_query("select id,name from partners where id not in (45,25,37,53) and status='Active' order by name asc");
while($row=db_fetch_array($partners_data))
{
    $users1 = db_query("select id,role from users where team_id='" . $row['id'] . "' and status='Active' ");
    $ids = array();

    while ($uid = db_fetch_array($users1)) 
    {
        $ids[] = $uid['id'];
    }
    $user_ids = implode(',', $ids);

$monthlyFreshCallDTP = (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and activity_log.activity_type='Lead' and activity_log.call_subject='Fresh Call' and orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and activity_log.activity_type='Lead' and activity_log.call_subject='Fresh Call' and lapsed_orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src ")) 
+ (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where (raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=1 and activity_log.activity_type='Raw' and activity_log.call_subject='Fresh Call' and raw_leads.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"));
$MonthlyFreshCallDTP+=$monthlyFreshCallDTP;

$monthlyFreshCallOthers = (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=0 and activity_log.call_subject='Fresh Call' and activity_log.activity_type='Lead' and orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=0 and activity_log.activity_type='Lead' and activity_log.call_subject='Fresh Call' and lapsed_orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src")) 
+ (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where (raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=0 and activity_log.activity_type='Raw' and activity_log.call_subject='Fresh Call' and raw_leads.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"));
$MonthlyFreshCallOthers+=$monthlyFreshCallOthers;

$monthlyFollowCallDTP = (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and activity_log.call_subject='Follow-up Call' and activity_log.activity_type='Lead' and orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and activity_log.activity_type='Lead' and activity_log.call_subject='Follow-up Call' and lapsed_orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where (raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=1 and activity_log.activity_type='Raw' and activity_log.call_subject='Follow-up Call' and raw_leads.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"));
$MonthlyFollowCallDTP+=$monthlyFollowCallDTP;

$monthlyFollowCallOthers = (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=0 and activity_log.call_subject='Follow-up Call' and activity_log.activity_type='Lead' and orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=0 and activity_log.activity_type='Lead' and activity_log.call_subject='Follow-up Call' and lapsed_orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src")) 
+ (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where (raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=0 and activity_log.activity_type='Raw' and activity_log.call_subject='Follow-up Call' and raw_leads.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"));
$MonthlyFollowCallOthers+=$monthlyFollowCallOthers;

$MonthlyTotal_freshCalls = $monthlyFreshCallDTP + $monthlyFreshCallOthers;
$MonthlyTotalFreshCalls+=$MonthlyTotal_freshCalls;

$MonthlyTotalFollowCalls = $monthlyFollowCallDTP + $monthlyFollowCallOthers;
$MonthlyTotalFollowUPCalls+=$MonthlyTotalFollowCalls;


$monthlyLeadGen = getSingleresult("select count(distinct(lead_modify_log.lead_id)) FROM  lead_modify_log left join orders on lead_modify_log.lead_id=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where  orders.team_id='".$row['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and lead_modify_log.modify_name ='Quote' and lead_modify_log.previous_name!='Quote' and MONTH(lead_modify_log.created_date) = ".$dat1." and YEAR(lead_modify_log.created_date) = ".$dat2." and (orders.created_by in (".$user_ids.") or orders.allign_to in (".$user_ids."))");
$monthlyTotalLeadGen+=$monthlyLeadGen;

$monthlyClosure =getSingleresult("select count(distinct(lead_modify_log.lead_id)) FROM  lead_modify_log left join orders on lead_modify_log.lead_id=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where  orders.team_id='".$row['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2)  and lead_modify_log.modify_name ='EU PO Issued' and lead_modify_log.previous_name!='EU PO Issued' and MONTH(lead_modify_log.created_date) = ".$dat1." and YEAR(lead_modify_log.created_date) = ".$dat2."  and (orders.created_by in (".$user_ids.") or orders.allign_to in (".$user_ids."))");
$monthlyTotalClosure+=$monthlyClosure;
    }

    $mail->Body.='<td valign="top">
    <table  class ="TFtable" style="width:100%;font-size:12px" border="1" cellspacing="0" cellpadding="0">
    <tbody>
<tr>
<td width="664" colspan="10"  class="c2">
    <p align="center">
        <strong>Monthly Report</strong>
    </p>
</td>
</tr>
       <tr>
        <td width="155" rowspan="2" class="c3" >
        <p align="center">
            <strong>VAR Organization Name</strong>
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
            <td width="155" rowspan="2" class="c3" >
                <p align="center">
                    <strong>Lead Gen</strong>
                </p>
            </td>
            <td width="155" rowspan="2" class="c3" >
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
    </tr>
    <tr>

    <td width="283" colspan="1" class="c2" >
    <p align="center">
        <strong>Total</strong>
    </p>
</td>
    <td width="70" class="c2">
        <p align="center">
            <strong>'.$MonthlyFreshCallDTP.'</strong>
        </p>
    </td>
    <td width="70" class="c2">
        <p align="center">
            <strong>'.$MonthlyFreshCallOthers.'</strong>
        </p>
    </td>
    <td width="72" class="c2">
        <p align="center">
            <strong>'.$MonthlyFollowCallDTP.'</strong>
            
        </p>
    </td>
    <td width="72" class="c2">
        <p align="center">
            <strong>'.$MonthlyFollowCallOthers.'</strong>
            
        </p>
    </td>
    <td width="48.5" class="c2">
        <p align="center">
            <strong>'.$MonthlyTotalFreshCalls.'</strong>
        </p>
    </td>
    <td width="48.5" class="c2">
    <p align="center">
        <strong>'.$MonthlyTotalFollowUPCalls.'</strong>
    </p>
</td>
<td width="140" class="c2">
    <p align="center">
        <strong>'.$monthlyTotalLeadGen.'</strong>
    </p>
</td>
<td width="140" class="c2">
    <p align="center">
        <strong>'.$monthlyTotalClosure.'</strong>
    </p>
</td>
</tr>'; 
     
/**Monthly report logic */

$partners=db_query("select id,name from partners where id not in (45,25,37,53) and status='Active' order by name asc");
while($row=db_fetch_array($partners))
{
    $users1 = db_query("select id,role from users where team_id='" . $row['id'] . "' and status='Active' ");
    $ids = array();

    while ($uid = db_fetch_array($users1)) 
    {
        $ids[] = $uid['id'];
    }
    $user_ids = implode(',', $ids);

    // $select_lead = db_query("select activity_log.id from activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and call_subject in ('Fresh Call','Follow-up Call') order by activity_log.created_date asc");
    // $sub_id = array();
    
    // while ($call_id = db_fetch_array($select_lead)) 
    // {
    //     $sub_id[] = $call_id['id'];
    // }
    // //print_r($sub_id);
    // $Callid = implode(',', $sub_id);

    // $select_lapsed = db_query("select activity_log.id from activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id where lapsed_orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." and lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and activity_log.activity_type='Lead' and call_subject in ('Fresh Call','Follow-up Call') order by activity_log.created_date asc");
    // $lapsed_id = array();
    
    // while ($callLapsedid = db_fetch_array($select_lapsed)) 
    // {
    //     $lapsed_id[] = $callLapsedid['id'];
    // }
    // $Callid_lapsed = implode(',', $lapsed_id);


    // $select_raw = db_query("select activity_log.id from activity_log left join raw_leads on activity_log.pid=raw_leads.id where raw_leads.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." and raw_leads.product_type_id in (1,2) and activity_log.activity_type='Raw' and call_subject in ('Fresh Call','Follow-up Call') order by activity_log.created_date asc");
    // $raw_id = array();
    
    // while ($callRawid = db_fetch_array($select_raw)) 
    // {
    //     $raw_id[] = $callRawid['id'];
    // }
    // $Callid_raw = implode(',', $raw_id);



$monthly_fresh_callDTP = (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and activity_log.activity_type='Lead' and activity_log.call_subject='Fresh Call' and orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and activity_log.activity_type='Lead' and activity_log.call_subject='Fresh Call' and lapsed_orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src ")) 
+ (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where (raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=1 and activity_log.activity_type='Raw' and activity_log.call_subject='Fresh Call' and raw_leads.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"));
$Monthlyfresh_callDTP+=$monthly_fresh_callDTP;

$monthly_fresh_callOthers = (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=0 and activity_log.call_subject='Fresh Call' and activity_log.activity_type='Lead' and orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=0 and activity_log.activity_type='Lead' and activity_log.call_subject='Fresh Call' and lapsed_orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src")) 
+ (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where (raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=0 and activity_log.activity_type='Raw' and activity_log.call_subject='Fresh Call' and raw_leads.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"));
$Monthlyfresh_callOthers+=$monthly_fresh_callOthers;

// SELECT DATE(activity_log.created_date) Date,COUNT(DISTINCT activity_log.pid) FROM activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where orders.team_id='23' and activity_log.added_by in (14,15,16,70,96,183,184) and MONTH(activity_log.created_date) = 12 and YEAR(activity_log.created_date) = 2020 and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and call_subject ='Follow-up Call'
// GROUP BY DATE(created_date) order by activity_log.created_date asc
$monthly_follow_callDTP = (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and activity_log.call_subject='Follow-up Call' and activity_log.activity_type='Lead' and orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and activity_log.activity_type='Lead' and activity_log.call_subject='Follow-up Call' and lapsed_orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where (raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=1 and activity_log.activity_type='Raw' and activity_log.call_subject='Follow-up Call' and raw_leads.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"));
$Monthlyfollow_callDTP+=$monthly_follow_callDTP;

$monthly_follow_callOthers = (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=0 and activity_log.call_subject='Follow-up Call' and activity_log.activity_type='Lead' and orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"))
+ (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=0 and activity_log.activity_type='Lead' and activity_log.call_subject='Follow-up Call' and lapsed_orders.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src")) 
+ (getSingleresult("select sum(TotalByOrder) from (select COUNT(DISTINCT activity_log.pid) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where (raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=0 and activity_log.activity_type='Raw' and activity_log.call_subject='Follow-up Call' and raw_leads.team_id='".$row['id']."' and activity_log.added_by in (".$user_ids.") and MONTH(activity_log.created_date) = ".$dat1." and YEAR(activity_log.created_date) = ".$dat2." GROUP BY DATE(activity_log.created_date) order by activity_log.created_date asc) src"));
$Monthlyfollow_callOthers+=$monthly_follow_callOthers;

$Monthlytotal_freshCalls = $monthly_fresh_callDTP + $monthly_fresh_callOthers;
$MonthlytotalFreshCalls+=$Monthlytotal_freshCalls;

$Monthlytotal_followCalls = $monthly_follow_callDTP + $monthly_follow_callOthers;
$MonthlytotalFollowUPCalls+=$Monthlytotal_followCalls;


$monthly_lead_gen = getSingleresult("select count(distinct(lead_modify_log.lead_id)) FROM  lead_modify_log left join orders on lead_modify_log.lead_id=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where  orders.team_id='".$row['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and lead_modify_log.modify_name ='Quote' and lead_modify_log.previous_name!='Quote' and MONTH(lead_modify_log.created_date) = ".$dat1." and YEAR(lead_modify_log.created_date) = ".$dat2." and (orders.created_by in (".$user_ids.") or orders.allign_to in (".$user_ids."))");
$monthly_total_leadGen+=$monthly_lead_gen;

$monthly_closure =getSingleresult("select count(distinct(lead_modify_log.lead_id)) FROM  lead_modify_log left join orders on lead_modify_log.lead_id=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id where  orders.team_id='".$row['id']."' and orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2)  and lead_modify_log.modify_name ='EU PO Issued' and lead_modify_log.previous_name!='EU PO Issued' and MONTH(lead_modify_log.created_date) = ".$dat1." and YEAR(lead_modify_log.created_date) = ".$dat2."  and (orders.created_by in (".$user_ids.") or orders.allign_to in (".$user_ids."))");
$monthly_total_closure+=$monthly_closure;
   
        $mail->Body.='<tr>
        <td width="155" >
        <p align="center">
            '.$row['name'].'
        </p>
    </td>
            <td width="70" >
                <p align="center">
                  '.($monthly_fresh_callDTP?$monthly_fresh_callDTP:0).'
                </p>
            </td>
             <td width="70" >
                <p align="center">
                    '.($monthly_fresh_callOthers?$monthly_fresh_callOthers:0).'
                </p>
            </td>
             <td width="72" >
                <p align="center">
                '.($monthly_follow_callDTP?$monthly_follow_callDTP:0).'
                </p>
            </td>
            
             <td width="72" >
                <p align="center">
                '.($monthly_follow_callOthers?$monthly_follow_callOthers:0).'
                </p>
            </td>
            <td width="48.5" >
                <p align="center">
                  '.$Monthlytotal_freshCalls.'
                </p>
            </td>
            <td width="48.5" >
            <p align="center">
              '.$Monthlytotal_followCalls.'
            </p>
        </td>
        <td width="155" >
        <p align="center">
          '.$monthly_lead_gen.'
        </p>
        </td>
        <td width="155" >
        <p align="center">
          '.$monthly_closure.'
        </p>
        </td>
        </tr>';
    }
    $mail->Body.='<tr>

    <td width="283" colspan="1" class="c2" >
    <p align="center">
        <strong>Total</strong>
    </p>
</td>
    <td width="70" class="c2">
        <p align="center">
            <strong>'.$Monthlyfresh_callDTP.'</strong>
        </p>
    </td>
    <td width="70" class="c2">
        <p align="center">
            <strong>'.$Monthlyfresh_callOthers.'</strong>
        </p>
    </td>
    <td width="72" class="c2">
        <p align="center">
            <strong>'.$Monthlyfollow_callDTP.'</strong>
            
        </p>
    </td>
    <td width="72" class="c2">
        <p align="center">
            <strong>'.$Monthlyfollow_callOthers.'</strong>
            
        </p>
    </td>
    <td width="48.5" class="c2">
        <p align="center">
            <strong>'.$MonthlytotalFreshCalls.'</strong>
        </p>
    </td>
    <td width="48.5" class="c2">
    <p align="center">
        <strong>'.$MonthlytotalFollowUPCalls.'</strong>
    </p>
</td>
<td width="140" class="c2">
    <p align="center">
        <strong>'.$monthly_total_leadGen.'</strong>
    </p>
</td>
<td width="140" class="c2">
    <p align="center">
        <strong>'.$monthly_total_closure.'</strong>
    </p>
</td>
</tr>';
        
     $mail->Body.='</tbody>
</table>

</td>
<tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Thanks &amp; Regards,</p>
<p>SketchUp DR Support Team.</p>';
		 
		 
		$mail->Body .='</div>';

        $mail->AddAddress('binish.parikh@arkinfo.in');
        $mail->AddCC("sathish.venugopal@corel.com");
        $mail->AddCC("jayesh.patel@arkinfo.in");
        $mail->AddCC("maneesh.kumar@arkinfo.in");
        $mail->AddCC("virendra@corelindia.co.in");
        $mail->AddCC("manish.pandey@arkinfo.in");
        $mail->AddCC("sagar.parikh@arkinfo.in");
        $mail->AddCC("roshan.j@arkinfo.in");
        $mail->AddCC("vijay.sagar@arkinfo.in");
        $mail->AddCC("fayyaz@corelindia.co.in");
        $mail->AddCC("prashant.dongrikar@arkinfo.in");
        $mail->AddBCC("isha.mittal@arkinfo.in"); 	  
           
           echo $mail->Body;
            //$mail->Send();
            $mail->ClearAllRecipients();
            //die;
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
?>
 