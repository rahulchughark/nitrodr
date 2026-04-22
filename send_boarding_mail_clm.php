<?php include('includes/include.php');
$lead = db_fetch_array(db_query("SELECT * FROM orders WHERE id=".$_POST['lead_id']));

$addTo[] = 'vaishnavi.n@ict360.com';
$addCc[] = "naveen.kumar@ict360.com	";
$addCc[] = "imran.desai@ict360.com";
$addBcc[] = '';
$created_by = getSingleResult("SELECT name FROM users where id=".$lead['created_by']);
$champ = getSingleResult("SELECT eu_person_name FROM order_important_person where id=".$_POST['lead_id']);
$attachments = db_query("SELECT * FROM opportunity_attachments where attachment_type='po_attachments' and status=1 and lead_id=".$_POST['lead_id']);
while($att = db_fetch_array($attachments)){
    $attach[] = $att['attachment_path'];
}
// print_r($attach);die;
$setSubject = "Onboarding mail : " . $_POST['school_name'];
$body    = "Dear Team,<br><br>Congratulations!<br><br>We are pleased to inform you that the school is now ready for onboarding. Kindly proceed with the next steps, as we have received the purchase order (PO) and the advance payment.<br><br>Below are the school details for your reference:<br>
<ul>
<li><b>School Name</b> : " . $_POST['school_name'] . " </li>
<li><b>Champion Name</b> : ".$champ."</li>
<li><b>Phone Number</b> : ".$lead['contact']."</li>
<li><b>Email ID</b> : ".$lead['school_email']."</li>
<li><b>PO Order</b> : </li>
<li><b>Submitted By</b> : ".$created_by."</li>
</ul><br>
Best regards,<br>
Team ISS"; 
$a = sendMailAttachment($addTo, $addCc, $addBcc, $setSubject, $body, $attach);

$q = db_query("update orders set onboard_mail_sent=1 where id=".$_POST['lead_id']);
