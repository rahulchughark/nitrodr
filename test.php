<?php include('includes/include.php');  
// $addTo[] = ('virendrathakur70@gmail.com'); 
// $addCc[] = ('virendra.kumar@arkinfo.in'); 
$addTo[] = ('pradeep.chahal@arkinfo.in'); 
// $addTo[] = ('pooja.maurya@arkinfo.in'); 
// $addTo[] = 'pradeepchahal905@gmail.com'; 
// $addTo[] = 'pradeepchahal905@gmail.com'; 
// $addCc[] = 'bhuban.singh@arkinfo.in'; 
// $addCc[] = ''; 
// $addBcc[] = '';

  $setSubject = "5 Testing sendgrid";

  $body    ="Test.
<br>
    Test sendgrid
    Test sendgrid
    Test sendgrid
    Test sendgrid
    Test sendgrid
    Test sendgrid
    Test sendgrid
    Test sendgrid
    Test sendgrid
    Test sendgrid
    <br>
  <br>

  Thanks,<br>";             
   
  sendMail($addTo, $addCc, $addBcc, $setSubject, $body);