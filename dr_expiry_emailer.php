<?php include('includes/include.php');
ini_set('max_execution_time', 300);
$date=date('Y-m-d',strtotime("-1 days")); 


$query = db_query("select r_email,r_name,r_user,lead_type,company_name,eu_name,eu_email,eu_mobile,team_id,quantity,partner_close_date,caller,created_by,pid from orders left join relog on orders.id=relog.pid where license_type='Commercial' and relog.pid IS NULL and status='Approved' and close_time='".$date."'");

 while($data=db_fetch_array($query))
{
      // auto emailers code start 
     
            $sm_email = getSingleresult("select email from users as u left join partners as p on u.id=p.sm_user where p.id='" . $data['team_id'] . "'");
        
      
        $manager_email = getSingleresult("select email from users where user_type='MNGR' and team_id=" . $data['team_id']);
        $submitedby_email = getSingleresult("select email from users where id=".$data['created_by']." and team_id=".$data['team_id']);
        
        $mail->AddAddress($submitedby_email);
        if($sm_email)
        {
           $mail->AddCC($sm_email); // sales manager email
        }
        $mail->AddCC($manager_email);
       
        $mail->AddCC('virendra.kumar@arkinfo.in');

        $mail->Subject = "Your DR is about to expire - ".$data['company_name'];
        $mail->Body    = "Hi,<br><br> Below account’s DR is expired , kindly get it relogged from your end<br><br>
                <ul>
                <li><b>Account Name</b> : " . $data['company_name'] . " </li>
                <li><b>Quantity</b> : " . $data['quantity'] . " </li>
                <li><b>Customer Name</b> : ". $data['eu_name'] ." </li>
                <li><b>Mobile Number</b> : ". $data['eu_mobile'] ." </li>
                <li><b>Email ID</b> : ". $data['eu_email'] ." </li></ul>
                <br>
Thanks,<br>
SketchUp DR Portal
";
        echo "<pre>";
        echo $mail->Body;
        // die();         
        // $mail->Send();
        // $mail->ClearAllRecipients();

     
    $i++;

}

// echo "<pre>";
// echo "Cron Exicuted";


?>