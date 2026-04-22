<?php include('includes/include.php');
if(date('l')!='Sunday')
{
    
ini_set('max_execution_time', 0);
   			
$resellers=db_query("select id,name from partners where id not in (45,25,37,53) and status='Active'");
while($data=db_fetch_array($resellers))
{

    if(date('l')=='Monday'){			
        $dat=date('Y-m-d',strtotime("-2 days"));
        $setSubject = "Review Report - ".$data['name'].' '.date('jS F Y',strtotime("-2 days")).".";
        }
        else
        {
         $dat1=date("Y-m-d", strtotime("-5 week"));
         $dat2=date('Y-m-d');
         $setSubject = "Under Review Account Summary";
        }

        $body = '<style type="text/css"> 
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
    .c1{ background-color:#370F44;color:#fff}
    .c2{ background-color:#370F44;color:#fff}
    .c3{ background-color:#051620;color:#fff}
    .c_yellow td{ background-color:#FFFF00;color:#000}
    </style><p>Hi Sir,</p>
    <p>Please find below summary of accounts we have reviewed with respective team member:</p>
    <table  class ="TFtable" style="width:85%;font-size:12px" border="1" cellspacing="0" cellpadding="0">
<tbody>
        <tr>
            <td  class="c3">
                <p align="center">
                    <strong>Sr. Number</strong>
                </p>
            </td>

            <td  class="c3">
            <p align="center">
                <strong>Account Name</strong>
            </p>
            </td>

            <td  class="c3">
            <p align="center">
                <strong>Lead Type</strong>
            </p>
            </td>

            <td  class="c3">
            <p align="center">
                <strong>Quantity</strong>
            </p>
            </td>

            <td  class="c3">
            <p align="center">
                <strong>Last Update Stage</strong>
            </p>
            </td>

            <td  class="c3">
            <p align="center">
                <strong>"Stage"- Post review</strong>
            </p>
            </td>

            <td  class="c3">
            <p align="center">
                <strong>Review Remarks</strong>
            </p>
            </td>
        </tr>';
        $i=1; 
        if(date('l')=='Monday'){
            $sql=  db_query("select review_log.*,orders.company_name,orders.created_date,orders.quantity,orders.lead_type,orders.r_user from review_log left join orders on review_log.lead_id=orders.id where date(review_log.created_date) ='".$dat."' and orders.team_id='".$data['id']."' and review_log.id In(Select Max(review_log.id) From review_log Group By review_log.lead_id)");
        }else{
            $sql=  db_query("select review_log.*,orders.company_name,orders.created_date,orders.quantity,orders.lead_type,orders.r_user from review_log left join orders on review_log.lead_id=orders.id where date(review_log.created_date)>='".$dat1."' and date(review_log.created_date)<='".$dat2."' and orders.team_id='".$data['id']."' and review_log.id In(Select Max(review_log.id) From review_log Group By review_log.lead_id)");
        }

     
   // print_r($sql);
   $counter = db_num_array($sql);

    while($row=db_fetch_array($sql))
    { 
        $body.='<tr>
            <td width="128" >
                <p align="center">
                    '.$i.'
                </p>
            </td>
            <td width="144" >
                <p align="center">
                
                   '.$row['company_name'].'
                   '.$row['lead_id'].'
                </p>
            </td>
            <td width="140" >
                <p align="center">
                   '.$row['lead_type'].'
                </p>
            </td>

            <td width="97" >
                <p align="center">
                    '.$row['quantity'].'
                </p>
            </td>
            <td width="97" >
                <p align="center">
                    '.$row['old_stage'].'
                </p>
            </td>
            <td width="150" >
                <p align="center">
                    '.$row['new_stage'].'
                </p>
            </td>
            <td width="97" >
                <p align="center">
                    '.$row['comment'].'
                </p>
            </td>
        </tr>';
        $i++;
    }
        $body.='</tbody>
        </table>
        <p>Requesting your kind intervene to this accounts for next POA based on remarks shared.</p>
        <p>&nbsp;</p>
        <p>This is auto generated email based on review done by team on "Under Review" accounts.</p>
        <p>Thanks &amp; Regards,</p>
        <p>Core DR Support Team.</p>';
		 
		 
			$body .='</div>';
        
            $manager_email=getSingleresult("select email from users where user_type='MNGR' and team_id='".$data['id']."' and status='Active'");
            $sm_email=getSingleresult("select users.email as email from users join partners on partners.sm_user=users.id where partners.id='".$data['id']."'");
            $submittedBy_email=getSingleresult("select r_email from orders where team_id='".$data['id']."'");
            if($sm_email)
            $addTo[] = ($sm_email);
			$addCc[] = ("prashant.dongrikar@arkinfo.in");
			$addCc[] = ("kailash.bhurke@arkinfo.in");  
            $addBcc[] = ("ankit.aggarwal@arkinfo.in"); 	  
            $addBcc[] = ("deepranshu.srivastava@arkinfo.in"); 
            if(date('l')=='Monday'){
                if(getSingleresult("select count(review_log.id) from review_log left join orders on review_log.lead_id=orders.id where date(review_log.created_date)='".$dat."' and orders.team_id='".$data['id']."'") )

                if($counter>0){
                    sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
                }
            }else{
                if(getSingleresult("select count(review_log.id) from review_log left join orders on review_log.lead_id=orders.id where date(review_log.created_date)>='".$dat1."' and date(review_log.created_date)<='".$dat2."' and orders.team_id='".$data['id']."'"))
            
            if($counter>0){
               // echo $body;
               sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
            }
            }

}

}
