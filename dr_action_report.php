<?php include('includes/include.php');
if (date('l') == 'Monday') {

    $dat1 = date('Y-m-d 20:00:00', strtotime("-2 days"));
    $dat2 = date('Y-m-d 19:59:00');
    // $mail->Subject = "DR Action Report - ".date('jS F Y',strtotime("-2 days")).".";

} else {
    $dat1 = date('Y-m-d 20:00:00', strtotime("-1 days"));
    $dat2 = date('Y-m-d 19:59:00');
    // $mail->Subject = "DR Action Report - ".date('jS F Y',strtotime("-1 days"));
    // $dat='2020-02-26';
}

//$dat = date('Y-m-d');

$mail->Subject = "DR Action Report - " . date('jS F Y');

if (date('l') != 'Sunday') {

    ini_set('max_execution_time', 0);
    $mail->Body = '<style type="text/css"> 
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
    $mail->Body .= '<p>
  Dear Sir,
</p>
<p>
  Greetings for the day!!
</p>
<p>
Please find the below DR Action report for today:
</p>

<table width="100%">


<table  class ="TFtable" style="width:60%;font-size:12px" border="1" cellspacing="0" cellpadding="0">
  <tbody>
      <tr>
      <td class="c1" width="40" nowrap="" rowspan="2">
      <p align="center">
          <strong>S.No.</strong>
      </p>
  </td>
  <td class="c1" width="97" nowrap="" rowspan="2">
              <p align="center">
                  <strong>Actioned By</strong>
              </p>
          </td>
          <td class="c1" width="97" nowrap="" rowspan="2">
          <p align="center">
              <strong># DR Actioned</strong>
          </p>
      </td>
          <td class="c1" width="97" nowrap="" rowspan="2">
              <p align="center">
                  <strong># Qualified</strong>
              </p>
          </td>
          <td class="c1" width="90" nowrap="" rowspan="2">
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
          <td  class="c1" width="90" nowrap="" rowspan="2">
              <p align="center">
                  <strong># Re-logged</strong>
              </p>
          </td>
          
      </tr>
      <tr>
        
      </tr>';
    $i = 1;

    $actioned_by = db_query("select * from users where user_type='OPERATIONS' and status='Active'");

    while ($user_arr = db_fetch_array($actioned_by)) {

        $dr_actioned = getSingleresult("select count(id) from lead_modify_log where type='Status' and modify_name in ('Approved','Cancelled','Undervalidation','On-Hold') and created_date between '" . $dat1 . "' and '" . $dat2 . "' and created_by='" . $user_arr['id'] . "'");
        $total_dr_actioned += $dr_actioned;

        $qualified = getSingleresult("select count(id) from lead_modify_log where type='Status' and modify_name='Approved' and created_date between '" . $dat1 . "' and '" . $dat2 . "' and created_by='" . $user_arr['id'] . "'");
        $total_qualified += $qualified;

        $unqualified = getSingleresult("select count(id) from lead_modify_log where type='Status' and modify_name='Cancelled' and created_date between '" . $dat1 . "' and '" . $dat2 . "' and created_by='" . $user_arr['id'] . "'");
        $total_unqualified += $unqualified;

        $undervalidation = getSingleresult("select count(id) from lead_modify_log where type='Status' and modify_name='Undervalidation' and created_date between '" . $dat1 . "' and '" . $dat2 . "' and created_by='" . $user_arr['id'] . "'");
        $total_undervalidation += $undervalidation;

        $onhold = getSingleresult("select count(id) from lead_modify_log where type='Status' and modify_name='On-Hold' and created_date between '" . $dat1 . "' and '" . $dat2 . "' and created_by='" . $user_arr['id'] . "'");
        $total_onhold += $onhold;

        $reLogged = getSingleresult("select count(id) from lead_modify_log where type='Re-log Status' and modify_name='Qualified' and created_date between '" . $dat1 . "' and '" . $dat2 . "' and created_by='" . $user_arr['id'] . "'");
        $total_reLogged += $reLogged;

        $total = $qualified + $unqualified + $undervalidation + $onhold;
        $grand_total += $total;
        $mail->Body .= '<tr>
          <td width="97">
          <p align="center">
            ' . $i . '
          </p>
          </td>
          <td width="97">
          <p align="center">
            ' . $user_arr['name'] . '
          </p>
          </td>
          <td width="97">
          <p align="center">
            ' . $dr_actioned . '
          </p>
          </td>
          <td width="97">
              <p align="center">
                ' . $qualified . '
              </p>
          </td>
          <td width="119">
              <p align="center">
              ' . $unqualified . '
              </p>
          </td>
          <td width="126">
              <p align="center">
              ' . $undervalidation . '
              </p>
          </td>
          <td width="90">
              <p align="center">
              ' . $onhold . '
              </p>
          </td>
          <td width="151">
              <p align="center">
                ' . $reLogged . '
              </p>
          </td>
         
      </tr>';
        $i++;
    }

    $mail->Body .= '<tr>
            <td width="283" colspan="2" class="c1" >
                <p align="center">
                    <strong>Total</strong>
                </p>
            </td>
            <td width="70" class="c1">
                <p align="center">
                    <strong>' . $total_dr_actioned . '</strong>
                </p>
            </td>
            <td width="70" class="c1">
                <p align="center">
                    <strong>' . $total_qualified . '</strong>
                </p>
            </td>
            <td width="72" class="c1">
                <p align="center">
                    <strong>' . $total_unqualified . '</strong>
                    
                </p>
            </td>
            <td width="72" class="c1">
                <p align="center">
                    <strong>' . $total_undervalidation . '</strong>
                    
                </p>
            </td>
            <td width="48.5" class="c1">
                <p align="center">
                    <strong>' . $total_onhold . '</strong>
                </p>
            </td>
            <td width="48.5" class="c1">
            <p align="center">
                <strong>' . $total_reLogged . '</strong>
            </p>
        </td>

        
        </tr>

</tbody>
</table>
<br>
<table  class ="TFtable" style="width:80%;font-size:12px" border="1" cellspacing="0" cellpadding="0">
<tbody>
<tr>
            <td width="200" colspan="10"  class="c2">
                <p align="center">
                    <strong>Todays Tracker</strong>
                </p>
            </td>
        </tr>
<tr>
<td class="c1" width="50" nowrap="" rowspan="2">
<p align="center">
    <strong>Total Data Received</strong>
</p>
</td>
<td class="c1" width="50" nowrap="" rowspan="2">
<p align="center">
    <strong># LC Received</strong>
</p>
</td>
<td class="c1" width="50" nowrap="" rowspan="2">
<p align="center" >
    <strong># BD Received</strong>
</p>
</td>

<td class="c1" width="50" nowrap="" rowspan="2">
<p align="center">
    <strong># Incoming Received</strong>
</p>
</td>
</tr>
<tr>
        
</tr>';

    $data_received = getSingleresult("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.dvr_flag!=1 and o.created_date between '" . $dat1 . "' and '" . $dat2 . "' ");

    $lc_data = getSingleresult("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.dvr_flag!=1 and o.lead_type='LC' and o.created_date between '" . $dat1 . "' and '" . $dat2 . "'");
    $bd_data = getSingleresult("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.dvr_flag!=1 and o.lead_type='BD'  and o.created_date between '" . $dat1 . "' and '" . $dat2 . "'");
    $incoming_data = getSingleresult("select count(DISTINCT(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where tp.product_type_id in (1,2) and o.license_type='Commercial' and o.dvr_flag!=1 and o.lead_type='Incoming'  and o.created_date between '" . $dat1 . "' and '" . $dat2 . "'");

    $mail->Body .= '<tr>
          <td width="97">
          <p align="center">
            ' . $data_received . '
          </p>
          </td>
          <td width="97">
          <p align="center">
            ' . $lc_data . '
          </p>
          </td>
          <td width="97">
          <p align="center">
            ' . $bd_data . '
          </p>
          </td>
          <td width="97">
            <p align="center">
              ' . $incoming_data . '
            </p>
          </td>

</tr>

</tbody>
</table>

</table>
<p>Actioned activity to be considered from 8.00 PM to 7.59 PM cycle</p>
<p>
  Thanks &amp; Regards,
</p>
<p>
  Core DR Support Team.
</p>';

    $email_cc = [];
    $emails = db_query("select email,name from users where user_type='OPERATIONS' and status='Active'");
    while ($data = db_fetch_array($emails)) {
        $email_cc[] = array('email' => $data['email'], 'name' => $data['name']);
    }
    //print_r($email_cc);
    foreach ($email_cc as $recipient) {
        $mail->AddCC($recipient['email'], $recipient['name']);
    }
    //print_r($mail->AddCC($recipient['email'],$recipient['name']));
    //$mail_data = implode(",",$email_cc);


    $mail->AddCC("binish.parikh@arkinfo.in");
    $mail->AddCC("prashant.dongrikar@arkinfo.in");
    $mail->AddCC("virendra@corelindia.co.in");
    $mail->AddAddress("maneesh.kumar@arkinfo.in");
    $mail->AddBCC("isha.mittal@arkinfo.in");


    //$mail->AddAddress("jayesh.patel@arkinfo.in");
    //$mail->AddCC("kailash.bhurke@arkinfo.in");
    //$mail->AddCC("prashant.dongrikar@arkinfo.in");
    //$mail->AddBCC("deepranshu.srivastava@arkinfo.in"); 
    //print_r($mail->AddCC("$mail_data"));

    print_r($mail->Body);
    //$mail->Send();

}
