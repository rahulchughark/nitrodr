<?php include('includes/include.php');

if (date('l') == 'Monday') {
    $dat=date('Y-m-d', strtotime("-2 days"));
}else{
    $dat=date('Y-m-d', strtotime("-1 days"));
}

if(date('l')!='Sunday')
{
    
ini_set('max_execution_time', 0);

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
  </style><p>Hi Team,</p>
<p>Greetings for the day.!</p>


<p>Please find yesterday’s log a call report of DR Portal  :</p>';

/**Todays top header */


$mail->Body.='<table width="100%">

<tr>
<td valign="top">
<table  class ="TFtable" style="width:100%;font-size:12px" border="1" cellspacing="0" cellpadding="0">
<tbody>

        <tr>
            <td width="128" rowspan="2"  class="c3">
                <p align="center">
                    <strong>Sr. Number</strong>
                </p>
            </td>
            <td width="155" rowspan="2" class="c3" >
                <p align="center">
                    <strong>ISS Name</strong>
                </p>
            </td>
            <td width="140" colspan="2" class="c3" >
                <p align="center">
                    <strong>DTP/Printing</strong>
                </p>
            </td>
            <td width="155" rowspan="2" class="c3" >
                <p align="center">
                    <strong>DTP/Printing Total</strong>
                </p>
            </td>
            <td width="144" colspan="2" class="c3" >
                <p align="center" >
                    <strong>Other</strong>
                </p>
            </td>
            <td width="155" rowspan="2" class="c3" >
                <p align="center">
                    <strong>Other Total</strong>
                </p>
            </td>
            <td width="155" rowspan="2" class="c3" >
                <p align="center">
                    <strong>Grand Total</strong>
                </p>
            </td>

        </tr>
        <tr>
            <td width="70" class="c3">
                <p align="center">
                    <strong>Fresh Call</strong>
                </p>
            </td>
            <td width="70" class="c3">
            <p align="center">
                <strong>Follow-up call</strong>
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
        


        $i=1;

        $callers=db_query("select callers.id as caller_id,users.id,callers.name from callers left join users on users.id=callers.user_id where users.status='Active' and users.role='ISS' and users.user_type='CLR' order by callers.name asc");

        while($row=db_fetch_array($callers))
        {            
             
            $fresh_callDTP = (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.license_type='Commercial' and activity_log.activity_type='Lead' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.added_by ='".$row['id']."' group by activity_log.pid order by activity_log.created_date asc) src"))
            + (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.activity_type='Lead' and activity_log.added_by ='".$row['id']."' group by activity_log.pid order by activity_log.created_date asc) src")) 
            + (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where ( raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.activity_type='Raw' and activity_log.added_by ='".$row['id']."' group by activity_log.pid order by activity_log.created_date asc) src"));
            $total_fresh_callDTP+=$fresh_callDTP;

            $follow_callDTP = (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.license_type='Commercial' and activity_log.activity_type='Lead' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Follow-up Call' and activity_log.added_by ='".$row['id']."' group by activity_log.pid order by activity_log.created_date asc) src"))
            + (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Follow-up Call' and activity_log.activity_type='Lead' and activity_log.added_by ='".$row['id']."' group by activity_log.pid order by activity_log.created_date asc) src")) 
            + (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where ( raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=1 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Follow-up Call' and activity_log.activity_type='Raw' and activity_log.added_by ='".$row['id']."' group by activity_log.pid order by activity_log.created_date asc) src"));
            $total_follow_callDTP+=$follow_callDTP;

            $fresh_callOthers = (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.license_type='Commercial' and activity_log.activity_type='Lead' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.added_by ='".$row['id']."' group by activity_log.pid order by activity_log.created_date asc) src"))
            + (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.activity_type='Lead' and activity_log.added_by ='".$row['id']."' group by activity_log.pid order by activity_log.created_date asc) src")) 
            + (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where ( raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Fresh Call' and activity_log.activity_type='Raw' and activity_log.added_by ='".$row['id']."' group by activity_log.pid order by activity_log.created_date asc) src"));
            $total_fresh_callOthers+=$fresh_callOthers;

            $follow_callOthers = (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product on orders.id=tbl_lead_product.lead_id left join industry on orders.industry=industry.id where orders.license_type='Commercial' and activity_log.activity_type='Lead' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Follow-up Call' and activity_log.added_by ='".$row['id']."' group by activity_log.pid order by activity_log.created_date asc) src"))
            + (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join lapsed_orders on activity_log.pid=lapsed_orders.id left join tbl_lead_product on lapsed_orders.id=tbl_lead_product.lead_id left join industry on lapsed_orders.industry=industry.id where lapsed_orders.license_type='Commercial' and tbl_lead_product.product_type_id in (1,2) and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Follow-up Call' and activity_log.activity_type='Lead' and activity_log.added_by ='".$row['id']."' group by activity_log.pid order by activity_log.created_date asc) src")) 
            + (getSingleresult("select sum(TotalByOrder) from (select count(distinct(activity_log.added_by)) TotalByOrder FROM  activity_log left join raw_leads on activity_log.pid=raw_leads.id left join industry on raw_leads.industry=industry.id where ( raw_leads.product_type_id in (1,2) or raw_leads.product_id is NULL) and industry.log_status=0 and date(activity_log.created_date) = '".$dat."' and activity_log.call_subject='Follow-up Call' and activity_log.activity_type='Raw' and activity_log.added_by ='".$row['id']."' group by activity_log.pid order by activity_log.created_date asc) src"));
            $total_follow_callOthers+=$follow_callOthers;

            $total_DTPCalls = $fresh_callDTP + $follow_callDTP ;
            $TotalDTPCalls+=$total_DTPCalls;
            
            $total_otherCalls = $fresh_callOthers + $follow_callOthers;
            $TotalOtherCalls+=$total_otherCalls;

            $grandTotal = $total_DTPCalls + $total_otherCalls;
            $GrandTotalCalls+= $grandTotal;

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
                   '.$fresh_callDTP.'
                </p>
                </td>
                <td width="70" >
                <p align="center">
                '.$follow_callDTP.'
             </p>
            </td>
            <td width="72" >
                <p align="center">
                '.$total_DTPCalls.'
                </p>
            </td>
            <td width="72" >
            <p align="center">
            '.$fresh_callOthers.'
            </p>
        </td>
            <td width="48.5" >
                <p align="center">
                   '.$follow_callOthers.'
                </p>
            </td>
            <td width="48.5" >
                <p align="center">
                   '.$total_otherCalls.'
                </p>
            </td>
            <td width="140">
            <p align="center">
               '.$grandTotal.'
            </p>
        </td>

        </tr>';
         $i++;      
       
 }     

        $mail->Body.='<tr>
            <td width="283" colspan="2" class="c3" >
                <p align="center">
                    <strong>Total</strong>
                </p>
            </td>
            
            <td width="70" class="c3">
                <p align="center">
                    <strong>'.$total_fresh_callDTP.'</strong>
                </p>
            </td>
            <td width="70" class="c3">
                <p align="center">
                    <strong>'.$total_follow_callDTP.'</strong>
                </p>
            </td>
            <td width="72" class="c3">
                <p align="center">
                    <strong>'.$TotalDTPCalls.'</strong>
                    
                </p>
            </td>
            <td width="70" class="c3">
            <p align="center">
                <strong>'.$total_fresh_callOthers.'</strong>
            </p>
        </td>
            <td width="72" class="c3">
                <p align="center">
                    <strong>'.$total_follow_callOthers.'</strong>
                    
                </p>
            </td>
            <td width="48.5" class="c3">
                <p align="center">
                    <strong>'.$TotalOtherCalls.'</strong>
                </p>
            </td>
            <td width="48.5" class="c3">
            <p align="center">
                <strong>'.$GrandTotalCalls.'</strong>
            </p>
        </td>

        </tr>
    </tbody>
</table>

</td>';
  
$mail->Body.='</tbody>

</table>
<p>&nbsp;</p>
<p>Thanks &amp; Regards,</p>
<p>SketchUp DR Support Team.</p>';		 
		 
		$mail->Body .='</div>';
        $mail->AddAddress('Coreliss@corelindia.co.in');
        $mail->AddCC("virendra@corelindia.co.in");
        $mail->AddCC("shivram@corelindia.co.in");
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
            $total_fresh_callDTP=0;
            $total_fresh_callOthers=0;
            $total_follow_callDTP=0;
            $total_follow_callOthers=0;


}
