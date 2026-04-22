<?php include('includes/include.php');
  if(date('l')=='Monday'){			
    $dat=date('Y-m-d',strtotime("-2 days"));
    $setSubject = "DR Action Report - ".$data['name'].' '.date('jS F Y',strtotime("-2 days")).".";
    //$dat='2020-02-26';
    }
    else
    {
      $dat=date('Y-m-d',strtotime("-1 days"));  
      $setSubject = "DR Action Report - ".$data['name'].' '.date('jS F Y',strtotime("-1 days")).".";
     // $dat='2020-02-26';
    }
if(date('l')!='Sunday')
{
    
ini_set('max_execution_time', 0);
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
.c1{ background-color:#333;color:#fff}
.c2{ background-color:#370F44;color:#fff}
.c3{ background-color:#051620;color:#fff}
.c_yellow td{ background-color:#FFFF00;color:#000}
  </style>';			
  $body.='<p>
  Dear Sir,
</p>
<p>
  Greetings for the day!!
</p>
<p>
  Please find the below DR Actioned yesterday:
</p>
<table  class ="TFtable" style="width:60%;font-size:12px" border="1" cellspacing="0" cellpadding="0">
  <tbody>
      <tr>
          <td width="90" nowrap="" rowspan="2" class="c1">
              <p align="center">
                  <strong>Sr. Number</strong>
              </p>
          </td>
          <td class="c1" width="154" nowrap="" rowspan="2">
              <p align="center">
                  <strong>Team Member Name</strong>
              </p>
          </td>
          <td class="c1" width="97" nowrap="" rowspan="2">
              <p align="center">
                  <strong># Qualified</strong>
              </p>
          </td>
          <td class="c1" width="119" nowrap="" rowspan="2">
              <p align="center">
                  <strong># Un-qualified</strong>
              </p>
          </td>
          <td class="c1" width="126" nowrap="" rowspan="2">
              <p align="center">
                  <strong># Re-submission</strong>
              </p>
          </td>
          <td class="c1" width="90" nowrap="" rowspan="2">
              <p align="center">
                  <strong># On Hold</strong>
              </p>
          </td>
          <td  class="c1" width="151" nowrap="" rowspan="2">
              <p align="center">
                  <strong># Accounts Actioned</strong>
              </p>
          </td>
          
      </tr>
      <tr>
        
      </tr>';
      $sql=db_query("select id,name from users where user_type in ('SUPERADMIN','OPERATIONS')");
      $i=1;
      while($data=db_fetch_array($sql))
      {
        $qualified=getSingleresult("select count(id) from lead_modify_log where type='Status' and modify_name='Approved' and created_by='".$data['id']."' and date(created_date)='".$dat."'");
        $total_qualified+=$qualified;
        $unqualified= getSingleresult("select count(id) from lead_modify_log where type='Status' and modify_name='Cancelled' and created_by='".$data['id']."' and date(created_date)='".$dat."'");
        $total_unqualified+=$unqualified;
        $undervalidation= getSingleresult("select count(id) from lead_modify_log where type='Status' and modify_name='Undervalidation' and created_by='".$data['id']."' and date(created_date)='".$dat."'");
        $total_undervalidation+=$undervalidation;
        $onhold= getSingleresult("select count(id) from lead_modify_log where type='Status' and modify_name='On-Hold' and created_by='".$data['id']."' and date(created_date)='".$dat."'");
        $total_onhold+=$onhold;
        $total=$qualified+$unqualified+$undervalidation+$onhold;
        $grand_total+=$total;
        $body.='<tr>
          <td width="90">
              <p align="center">
                  '.$i.'
              </p>
          </td>
          <td width="154">
              <p align="center">
                  '.$data['name'].'
              </p>
          </td>
          <td width="97">
              <p align="center">
                '.$qualified.'
              </p>
          </td>
          <td width="119">
              <p align="center">
              '.$unqualified.'
              </p>
          </td>
          <td width="126">
              <p align="center">
              '.$undervalidation.'
              </p>
          </td>
          <td width="90">
              <p align="center">
              '.$onhold.'
              </p>
          </td>
          <td width="151">
              <p align="center">
                '.$total.'
              </p>
          </td>
         
      </tr>';
      $qualified=0;
      $unqualified=0;
      $undervalidation=0;
      $onhold=0;
      $total=0;
      $i++;
      }
      
      $body.=' <tr>
          <td class="c1" width="244" nowrap="" colspan="2" rowspan="2">
              <p align="center">
                  <strong>Total</strong>
              </p>
          </td>
          <td class="c1" width="97" nowrap="" rowspan="2">
              <p align="center">
                  <strong>'.$total_qualified.'</strong>
              </p>
          </td>
          <td class="c1" width="119" nowrap="" rowspan="2">
              <p align="center">
                  <strong>'.$total_unqualified.'</strong>
              </p>
          </td>
          <td class="c1"  width="126" nowrap="" rowspan="2">
              <p align="center">
                  <strong>'.$total_undervalidation.'</strong>
              </p>
          </td>
          <td class="c1" width="90" nowrap="" rowspan="2">
              <p align="center">
                  <strong>'.$total_onhold.'</strong>
              </p>
          </td>
          <td class="c1" width="151" nowrap="" rowspan="2">
              <p align="center">
                  <strong>'.$grand_total.'</strong>
              </p>
          </td>
          
      </tr>
      
  </tbody>
</table>
<p>
  Thanks &amp; Regards,
</p>
<p>
  Core DR Support Team.
</p>';

$addTo[] = ("jayesh.patel@arkinfo.in");
$addTo[] = ("maneesh.kumar@arkinfo.in");
$addCc[] = ("binish.parikh@arkinfo.in"); 
$addCc[] = ("prashant.dongrikar@arkinfo.in");
$addCc[] = ("kailash.bhurke@arkinfo.in");    
$addCc[] = ("virendra@corelindia.co.in");  	
$addBcc[] = ("deepranshu.srivastava@arkinfo.in"); 
$addBcc[] = ("ankit.aggarwal@arkinfo.in"); 
    echo $body;
    sendMail($addTo, $addCc, $addBcc, $setSubject, $body);

}
?>