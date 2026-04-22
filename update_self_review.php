<?php include('includes/include.php');

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$lead = db_fetch_array(db_query("select lead_type,company_name,eu_name,quantity,created_by from orders where orders.id='".$_GET['rid']."'"));
$caller=getSingleresult("select caller from orders where id='".$_GET['rid']."'");
$caller_user_id=getSingleresult("select user_id from callers where id='".$caller."'");
$caller_name=db_fetch_array(db_query("select users.name as caller_name,users.email as caller_email from callers join users on callers.user_id=users.id where callers.id='".$caller."'"));
 if($_GET['rid'] && $_GET['type']=='lc')
{
    $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_GET['rid']."','Self Review-Stage','N/A','No LC Remarks',now(),'".$_SESSION['user_id']."')");

    $add_tak=db_query("INSERT INTO `selfreview_tasks`(`title`, `order_id`, `assigned_to`, `assigned_from`) VALUES ('LC Call Pending','".$_GET['rid']."','".$caller_user_id."','".$_SESSION['user_id']."')");
    ///////////////////Email to caller/////////////////////////////
 

    $addCc[] = ("prashant.dongrikar@arkinfo.in"); 
    $addBcc[] = ("deepranshu.srivastava@arkinfo.in");   
    $setSubject = "Self Review - New Task Pending for your action (LC Remarks Not added) ";
    $body    ="<h3 style='color:blue'>New Task!</h3><br>Dear ".$caller_name['caller_name'].",<br><br> ".$_SESSION['name']." has assigned you the following new task after Review:-<br><br>
                 
    <b>Subject </b> : Self Review - LC Remarks Not added<br> 
    <b>Contact</b> : ".$lead['eu_name']."<br>
    <b>Opportunity</b> : ".$lead['company_name']. " (<b>" .$lead['quantity']. " user(s)</b>) <br> 
    <b>Priority</b>: <span style='color:red'>High!</span><br>
    <br>

    Thanks,<br>
    SketchUp DR Portal";             
  
    sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
}

if($_GET['rid'] && $_GET['type']=='quote')
{

    $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_GET['rid']."','Self Review-Stage','N/A','Quote Not Shared - ".$_GET['action']."',now(),'".$_SESSION['user_id']."')");
    
if($_GET['action']=='Customer still not positive')
{
    $add_tak=db_query("INSERT INTO `selfreview_tasks`(`title`, `order_id`, `assigned_to`, `assigned_from`) VALUES ('Quote Pending-".$_GET['action']."','".$_GET['rid']."','".$caller_user_id."','".$_SESSION['user_id']."')");
    
    
    $addCc[] = ("prashant.dongrikar@arkinfo.in"); 
    $addBcc[] = ("deepranshu.srivastava@arkinfo.in");   
    $setSubject = "Self Review - New Task Pending for your action (Quote Pending ".$_GET['action'].") ";
    $body    ="<h3 style='color:blue'>New Task!</h3><br>Dear ".$caller_name['caller_name'].",<br><br> ".$_SESSION['name']." has assigned you the following new task after Review:-<br><br>
                 
    <b>Subject </b> : Self Review - Quote Pending ".$_GET['action']."<br> 
    <b>Contact</b> : ".$lead['eu_name']."<br>
    <b>Opportunity</b> : ".$lead['company_name']. " (<b>" .$lead['quantity']. " user(s)</b>) <br> 
    <b>Priority</b>: <span style='color:red'>High!</span><br>
    <br>

    Thanks,<br>
    SketchUp DR Portal";             
  
    sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
}
if($_GET['action']=='Quote pending from our end')
{
    $add_tak=db_query("INSERT INTO `selfreview_tasks`(`title`, `order_id`, `assigned_to`, `assigned_from`) VALUES ('Quote Pending-".$_GET['action']."','".$_GET['rid']."','".$lead['created_by']."','".$_SESSION['user_id']."')");
    
    
    $addCc[] = ("prashant.dongrikar@arkinfo.in"); 
    $addBcc[] = ("deepranshu.srivastava@arkinfo.in");   
    $setSubject = "Self Review - New Task Pending for your action (Quote Pending ".$_GET['action'].") ";
    $body    ="<h3 style='color:blue'>New Task!</h3><br>Dear ".$caller_name['caller_name'].",<br><br> ".$_SESSION['name']." has assigned you the following new task after Review:-<br><br>
                 
    <b>Subject </b> : Self Review - Quote Pending ".$_GET['action']."<br> 
    <b>Contact</b> : ".$lead['eu_name']."<br>
    <b>Opportunity</b> : ".$lead['company_name']. " (<b>" .$lead['quantity']. " user(s)</b>) <br> 
    <b>Priority</b>: <span style='color:red'>High!</span><br>
    <br>

    Thanks,<br>
    SketchUp DR Portal";             
  
    sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
}
if($_GET['action']=='Customer denied')
{
    $add_tak=db_query("INSERT INTO `selfreview_tasks`(`title`, `order_id`, `assigned_to`, `assigned_from`) VALUES ('Quote Pending-".$_GET['action']."','".$_GET['rid']."','".$caller_user_id."','".$_SESSION['user_id']."')");
    
    
    $addCc[] = ("prashant.dongrikar@arkinfo.in"); 
    $addBcc[] = ("deepranshu.srivastava@arkinfo.in");   
    $setSubject = "Self Review - New Task Pending for your action (Quote Pending ".$_GET['action'].") ";
    $body    ="<h3 style='color:blue'>New Task!</h3><br>Dear ".$caller_name['caller_name'].",<br><br> ".$_SESSION['name']." has assigned you the following new task after Review:-<br><br>
                 
    <b>Subject </b> : Self Review - Quote Pending ".$_GET['action']."<br> 
    <b>Contact</b> : ".$lead['eu_name']."<br>
    <b>Opportunity</b> : ".$lead['company_name']. " (<b>" .$lead['quantity']. " user(s)</b>) <br> 
    <b>Priority</b>: <span style='color:red'>High!</span><br>
    <br>

    Thanks,<br>
    SketchUp DR Portal";             
  
    sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
}

}


if($_GET['rid'] && $_GET['type']=='fup')
{
    
    if($_GET['action']=='Customer is not responding, we will try again')
    {
        $add_tak=db_query("INSERT INTO `selfreview_tasks`(`title`, `order_id`, `assigned_to`, `assigned_from`,created_date) VALUES ('Follow-Up Pending-".$_GET['action']."','".$_GET['rid']."','".$_SESSION['user_id']."','".$_SESSION['user_id']."','".$_GET['fupdate'].' '.date('H:i:s')."')");
    
        
        $addCc[] = ("prashant.dongrikar@arkinfo.in"); 
        $addBcc[] = ("deepranshu.srivastava@arkinfo.in");   
        $setSubject = "Self Review - New Task Pending for your action (Follow-Up Pending ".$_GET['action'].") ";
        $body    ="<h3 style='color:blue'>New Task!</h3><br>Dear ".$_SESSION['name'].",<br><br> Reminder has been  set for the following task after Review:-<br><br>
                     
        <b>Subject </b> : Self Review - Follow-Up Pending ".$_GET['action']."<br> 
        <b>Contact</b> : ".$lead['eu_name']."<br>
        <b>Opportunity</b> : ".$lead['company_name']. " (<b>" .$lead['quantity']. " user(s)</b>) <br> 
        <b>Priority</b>: <span style='color:red'>High!</span><br>
        <br>
    
        Thanks,<br>
        SketchUp DR Portal";             
      
        sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
    }
    if($_GET['action']=='Customer is not responding, need help from LC member')
    {
        $add_tak=db_query("INSERT INTO `selfreview_tasks`(`title`, `order_id`, `assigned_to`, `assigned_from`) VALUES ('Follow-Up-".$_GET['action']."','".$_GET['rid']."','".$caller_user_id."','".$_SESSION['user_id']."')");
    
        
        $addCc[] = ("prashant.dongrikar@arkinfo.in"); 
        $addBcc[] = ("deepranshu.srivastava@arkinfo.in");   
        $setSubject = "Self Review - New Task Pending for your action (Follow-Up Pending ".$_GET['action'].") ";
        $body    ="<h3 style='color:blue'>New Task!</h3><br>Dear ".$caller_name['caller_name'].",<br><br> ".$_SESSION['name']." has assigned you the following new task after Review:-<br><br>
                     
        <b>Subject </b> : Self Review - Follow-Up ".$_GET['action']."<br> 
        <b>Contact</b> : ".$lead['eu_name']."<br>
        <b>Opportunity</b> : ".$lead['company_name']. " (<b>" .$lead['quantity']. " user(s)</b>) <br> 
        <b>Priority</b>: <span style='color:red'>High!</span><br>
        <br>
    
        Thanks,<br>
        SketchUp DR Portal";             
      
        sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
    }
    
      if($_GET['action']=='Looking for best price')
    {
        $add_tak=db_query("INSERT INTO `selfreview_tasks`(`title`, `order_id`, `assigned_to`, `assigned_from`) VALUES ('Follow-Up-".$_GET['action']."','".$_GET['rid']."','".$lead['created_by']."','".$_SESSION['user_id']."')");
    
        
        $addCc[] = ("prashant.dongrikar@arkinfo.in"); 
        $addBcc[] = ("deepranshu.srivastava@arkinfo.in");   
        $setSubject = "Self Review - New Task Pending for your action (Follow-Up Pending ".$_GET['action'].") ";
        $body    ="<h3 style='color:blue'>New Task!</h3><br>Dear ".$caller_name['caller_name'].",<br><br> ".$_SESSION['name']." has assigned you the following new task after Review:-<br><br>
                     
        <b>Subject </b> : Self Review - Follow-Up ".$_GET['action']."<br> 
        <b>Contact</b> : ".$lead['eu_name']."<br>
        <b>Opportunity</b> : ".$lead['company_name']. " (<b>" .$lead['quantity']. " user(s)</b>) <br> 
        <b>Priority</b>: <span style='color:red'>High!</span><br>
        <br>
    
        Thanks,<br>
        SketchUp DR Portal";             
      
        sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
    }
    if($_GET['action']=='Customer denied')
    {
        $add_tak=db_query("INSERT INTO `selfreview_tasks`(`title`, `order_id`, `assigned_to`, `assigned_from`) VALUES ('Follow-Up-".$_GET['action']."','".$_GET['rid']."','".$caller_user_id."','".$_SESSION['user_id']."')");
    
        
        $addCc[] = ("prashant.dongrikar@arkinfo.in"); 
        $addBcc[] = ("deepranshu.srivastava@arkinfo.in");   
        $setSubject = "Self Review - New Task Pending for your action (Follow-Up Pending ".$_GET['action'].") ";
        $body    ="<h3 style='color:blue'>New Task!</h3><br>Dear ".$caller_name['caller_name'].",<br><br> ".$_SESSION['name']." has assigned you the following new task after Review:-<br><br>
                     
        <b>Subject </b> : Self Review - Follow-Up ".$_GET['action']."<br> 
        <b>Contact</b> : ".$lead['eu_name']."<br>
        <b>Opportunity</b> : ".$lead['company_name']. " (<b>" .$lead['quantity']. " user(s)</b>) <br> 
        <b>Priority</b>: <span style='color:red'>High!</span><br>
        <br>
    
        Thanks,<br>
        SketchUp DR Portal";             
      
        sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
    }  
    if($_GET['action']=='LC Follow-up')
    {
        $add_tak=db_query("INSERT INTO `selfreview_tasks`(`title`, `order_id`, `assigned_to`, `assigned_from`) VALUES ('Follow-Up-".$_GET['action']."','".$_GET['rid']."','".$caller_user_id."','".$_SESSION['user_id']."')");
    
        
        $addCc[] = ("prashant.dongrikar@arkinfo.in"); 
        $addBcc[] = ("deepranshu.srivastava@arkinfo.in");   
        $setSubject = "Self Review - New Task Pending for your action (Follow-Up Pending ".$_GET['action'].") ";
        $body    ="<h3 style='color:blue'>New Task!</h3><br>Dear ".$caller_name['caller_name'].",<br><br> ".$_SESSION['name']." has assigned you the following new task after Review:-<br><br>
                     
        <b>Subject </b> : Self Review - Follow-Up ".$_GET['action']."<br> 
        <b>Contact</b> : ".$lead['eu_name']."<br>
        <b>Opportunity</b> : ".$lead['company_name']. " (<b>" .$lead['quantity']. " user(s)</b>) <br> 
        <b>Priority</b>: <span style='color:red'>High!</span><br>
        <br>
    
        Thanks,<br>
        SketchUp DR Portal";             
      
        sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
    }  


}

if($_GET['rid'] && $_GET['type']=='comm')
{
    if($_GET['action']=='Yes, Positive but unsure on closure date')
    {
        $add_tak=db_query("INSERT INTO `selfreview_tasks`(`title`, `order_id`, `assigned_to`, `assigned_from`,created_date) VALUES ('Follow-Up-".$_GET['action']."','".$_GET['rid']."','".$_SESSION['user_id']."','".$_SESSION['user_id']."','".$_GET['fupdate'].' '.date('H:i:s')."')");
    }
    $addCc[] = ("prashant.dongrikar@arkinfo.in"); 
    $addBcc[] = ("deepranshu.srivastava@arkinfo.in");   
    $setSubject = "Self Review - New Task Pending for your action (Follow-Up Pending ".$_GET['action'].") ";
    $body    ="<h3 style='color:blue'>New Task!</h3><br>Dear ".$caller_name['caller_name'].",<br><br> ".$_SESSION['name']." has assigned you the following new task after Review:-<br><br>
                 
    <b>Subject </b> : Self Review - Follow-Up ".$_GET['action']."<br> 
    <b>Contact</b> : ".$lead['eu_name']."<br>
    <b>Opportunity</b> : ".$lead['company_name']. " (<b>" .$lead['quantity']. " user(s)</b>) <br> 
    <b>Priority</b>: <span style='color:red'>High!</span><br>
    <br>

    Thanks,<br>
    SketchUp DR Portal";             
  
    sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
    $res=1;
}
if($_GET['rid'] && $_GET['type']=='eupo')
{
    IF($_GET['action']=='Payment is not clear, but we will process this order' || $_GET['action']=='Payment is not clear, order can not process in this month')
{
    $add_tak=db_query("INSERT INTO `selfreview_tasks`(`title`, `order_id`, `assigned_to`, `assigned_from`) VALUES ('Follow-Up-".$_GET['action']."','".$_GET['rid']."','".$lead['created_by']."','".$_SESSION['user_id']."')");
    $update_closedate=db_query("update orders set partner_close_date='".$_GET['fupdate']."' and id='".$_GET['rid']."'");

}
    $addCc[] = ("prashant.dongrikar@arkinfo.in"); 
    $addBcc[] = ("deepranshu.srivastava@arkinfo.in");   
    $setSubject = "Congratulations..!!! Your customer issued PO ";
    $body    ="Hi, <br/><br/>Congratulations! PO has been issued for the below account:-<br><br>
                 
    <b>Subject </b> : Self Review - EU PO Issued".$_GET['action']."<br> 
    <b>Contact</b> : ".$lead['eu_name']."<br>
    <b>Opportunity</b> : ".$lead['company_name']. " (<b>" .$lead['quantity']. " user(s)</b>) <br> 
     <br>

    Thanks,<br>
    SketchUp DR Portal";             
  
    sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
    $res=1;
}
if($_GET['rid'] && $_GET['action']=='Customer asked to call back')
{
    $add_tak=db_query("INSERT INTO `selfreview_tasks`(`title`, `order_id`, `assigned_to`, `assigned_from`) VALUES ('Follow-Up-".$_GET['action']."','".$_GET['rid']."','".$lead['created_by']."','".$_SESSION['user_id']."')");

}
if($_GET['rid'] && $_GET['type']=='booking')
{
    $addCc[] = ("prashant.dongrikar@arkinfo.in"); 
    $addBcc[] = ("deepranshu.srivastava@arkinfo.in");   
    $setSubject = "Congratulations..!!! Your order is processed ";
    $body    ="Hi, <br/><br/>Congratulations! Booking has been done for the below account:-<br><br>
                 
    <b>Subject </b> : Self Review - Booking ".$_GET['action']."<br> 
    <b>Contact</b> : ".$lead['eu_name']."<br>
    <b>Opportunity</b> : ".$lead['company_name']. " (<b>" .$lead['quantity']. " user(s)</b>) <br> 
     <br>

    Thanks,<br>
    SketchUp DR Portal";             
  
    sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
    $res=1;
}
if($res || $add_tak )
{
    echo 1;    
    exit();
}
?>
