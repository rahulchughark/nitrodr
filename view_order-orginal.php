<?php include('includes/header.php');if($_SESSION['user_type'] != 'REVIEWER' && $_SESSION['user_type']!='EM')admin_page();?>
<?php 
$sql=db_query("select * from orders where id='".mysqli_real_escape_string($GLOBALS['dbcon'],$_REQUEST['id'])."'");
$row_data=db_fetch_array($sql);

if($_POST['caller'] && $_SESSION['user_type'] == 'REVIEWER')
{
    if($row_data['caller'] != $_POST['caller']){      
        $modify_name = getSingleresult("select name from callers where id='".$_POST['caller']."' ");
        $caller_prev = getSingleresult("select name from callers where id='".$row_data['caller']."'");
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_REQUEST['id']."','Caller','". $caller_prev."','". $modify_name."',now(),'".$_SESSION['user_id']."')");
        $sql=db_query("update orders set caller='".$_POST['caller']."' where id=".$_REQUEST['id']);
        $userid=getSingleresult("select user_id from callers where id='".$_POST['caller']."'");
            if($userid)
            {
                $caller_email=getSingleresult("select email from users where id='".$userid."'");
                $caller_name=getSingleresult("select name from users where id='".$userid."'");
                
                $mail->AddAddress($caller_email, $caller_name);   
                $mail->AddCC("bhagyashree@corelindia.co.in");
                $mail->AddCC("kailash.bhurke@arkinfo.in");  
                $mail->AddCC("prashant.dongrikar@arkinfo.in");   
                $mail->AddBCC("deepranshu.srivastava@arkinfo.in", "Deepranshu Srivastava");   
                
                $mail->Subject = "[LC Calling] Lead changed to you on DR Portal";
                $mail->Body    = "Hi,<br><br> Below account has been changed for your LC working:-<br><br>
             <ul>
                <li><b>Account Name</b> : ".$row_data['company_name']." </li>
                <li><b>City</b> : ".$row_data['city']." </li>
                <li><b>Address</b> : ".htmlspecialchars($row_data['address'], ENT_QUOTES)." </li>
                <li><b>Mobile</b> : ".$row_data['eu_mobile']." </li>
                <li><b>Reseller Name</b> : ".$row_data['r_name']." </li>
                <li><b>License Type</b> : ".$row_data['license_type']." </li>
                <li><b>Quantity</b> : ".$row_data['quantity']." </li></ul><br>
             
                Thanks,<br>
                SketchUp DR Portal
                ";
                //$mail->AddAttachment("$target_file"); 
                //if(!$_POST['dr_code'])
                $mail->Send();
            }
        
    }
   
}

function struuid($entropy)
{
    $s=uniqid("",'');
    $num= hexdec(str_replace(".","",(string)$s));
    $index = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $base= strlen($index);
    $out = '';
        for($t = floor(log10($num) / log10($base)); $t >= 0; $t--) {
            $a = floor($num / pow($base,$t));
            $out = $out.substr($index,$a,1);
            $num = $num-($a*pow($base,$t));
        }
    return strtolower($out);
}
if($_POST['save_new_user'])
{
    $email_new=getSingleresult("select email from users where id=".$_POST['new_user']);
    $name_new=getSingleresult("select name from users where id=".$_POST['new_user']);
    $old_name=getSingleresult("select name from users where id=".$row_data['created_by']);
    // $modify_name=getSingleresult("select name from users where id=".$_POST['new_user']);
       $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_REQUEST['id']."','Ownership','".$old_name."','". $name_new."',now(),'".$_SESSION['user_id']."')");
  

    $ins=db_query("update orders set created_by='".$_POST['new_user']."',r_user='".$name_new."',r_email='".$email_new."' where id='".$_POST['id']."'");
    redir("view_order.php?id=".$_POST['id'],true);
     

}
if($_POST['status'])
{
	

if($_FILES["admin_attachment"])
{	
$target_dir = "uploads/";
$target_file = $target_dir .time(). basename($_FILES["admin_attachment"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	
if ($_FILES["admin_attachment"]["size"] > 4000000) {
    echo "<script>alert('Sorry, your file is too large!')</script>";
	redir("manage_orders.php",true);
    
}
else {
	move_uploaded_file($_FILES["admin_attachment"]["tmp_name"], $target_file);
	
}
}	
	
	
	if($_POST['status']=='Approved')
	{
		if($_POST['dr_code'])
		{
		$code=$_POST['dr_code'];
		}
		else
		{
		$code=struuid(true);
		}
	$_POST['reason']='';
	}
	else
	{
	$code='';
	  if($_POST['status']=='Undervalidation')
	  {
		$_POST['reason']=$_POST['reason_ud'];  
	  }
	}
	
	
	
   
    if($row_data['partner_close_date'] != $_POST['partner_close_date']){      
        $modify_name = $_POST['partner_close_date'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_REQUEST['id']."','Close Date','".$row_data['partner_close_date']."','". $modify_name."',now(),'".$_SESSION['user_id']."')");
    }
    if($row_data['status'] != $_POST['status']){      
        $modify_name = $_POST['status'];
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_REQUEST['id']."','Status','".$row_data['status']."','". $modify_name."',now(),'".$_SESSION['user_id']."')");
    }
    if($row_data['caller'] != $_POST['caller']){      
        $modify_name = getSingleresult("select name from callers where id='".$_POST['caller']."' ");
        $caller_prev = getSingleresult("select name from callers where id='".$row_data['caller']."' ");
        $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_REQUEST['id']."','Caller','". $caller_prev."','". $modify_name."',now(),'".$_SESSION['user_id']."')");
    }
    $sql=db_query("update orders set code='".$code."', status='".$_POST['status']."', reason='".$_POST['reason']."',add_comment='".htmlspecialchars($_POST['add_comment'])."',caller='".$_POST['caller']."',approval_time='".date('Y-m-d h:i:s')."',close_time='".date('Y-m-d',strtotime('+29 days',strtotime(date('Y-m-d h:i:s'))))."',admin_attachment='".$target_file."',partner_close_date='".$_POST['partner_close_date']."',sfdc_check='".$_POST['sfdc_check']."' where id=".$_REQUEST['id']);
    if($_POST['status']=='Approved')
    {
       
        if($row_data['allign_to'])
        {
           $point_user_id=$row_data['allign_to'];
        } 
        else
        {
            $point_user_id=$row_data['created_by'];
        }
    $points_date=week_range(date('Y-m-d'));
   $add_point=db_query("insert into user_points (stage_id,stage_name,point,week_number,date_from,date_to,no_of_seats,user_id,lead_id) values (1001,'Apporved',10,'".date('W')."','$points_date[0]','$points_date[1]','".$_POST['quant']."','".$point_user_id."','".$_REQUEST['id']."') ");
    }
	if($_POST['caller'])
	{
	$userid=getSingleresult("select user_id from callers where id='".$_POST['caller']."'");
		if($userid)
		{
			$caller_email=getSingleresult("select email from users where id='".$userid."'");
			$caller_name=getSingleresult("select name from users where id='".$userid."'");
			
			$mail->AddAddress($caller_email, $caller_name);   
			$mail->AddBCC("deepranshu.srivastava@arkinfo.in", "Deepranshu Srivastava");   
			
			$mail->Subject = "[LC Calling] New Lead assigned to you on DR Portal";
			$mail->Body    = "Hi,<br><br> Below account has been qualified for your LC working:-<br><br>
		 <ul>
			<li><b>Account Name</b> : ".$row_data['company_name']." </li>
			<li><b>City</b> : ".$row_data['city']." </li>
			<li><b>Address</b> : ".htmlspecialchars($row_data['address'], ENT_QUOTES)." </li>
			<li><b>Mobile</b> : ".$row_data['eu_mobile']." </li>
            <li><b>Reseller Name</b> : ".$row_data['r_name']." </li>
            <li><b>License Type</b> : ".$row_data['license_type']." </li>
			<li><b>Quantity</b> : ".$row_data['quantity']." </li></ul><br>
		 
			Thanks,<br>
			SketchUp DR Portal
            ";
            $mail->AddAttachment("$target_file"); 
			if(!$_POST['dr_code'])
			$mail->Send();
		}
	}
			$manager_email=getSingleresult("select email from users where user_type='MNGR' and team_id='".$row_data['team_id']."'");
			
	        $mail->AddAddress($row_data['r_email'],$row_data['r_name']);
			
			$mail->AddCC("$manager_email");
		 
			$mail->AddBCC("deepranshu.srivastava@arkinfo.in", "Deepranshu Srivastava");   
			if($_POST['status']=='Cancelled')
				{
					$stat= '<span style="color:red">Unqualified</span>';
				}
			else if($_POST['status']=='Approved')
				{
					$stat= '<span style="color:green">Qualified</span>';
				}
			else if($_POST['status']=='Undervalidation') { 
			$stat= '<span style="color:orange">Under Validation</span>';
            }
            else
            {
                $stat= '<span class="text-blue">On-Hold</span>';
            }
			//$userid=getSingleresult("select user_id from callers where id='".$row_data['caller']."'");
			
			$caller_name=getSingleresult("select name from callers where id='".$row_data['caller']."'");
			
			$mail->Subject = "Lead status has been changed on DR Portal [".$row_data['company_name']."]";
			$body= "Hi,<br><br> Below account status has been changed to ".$stat." on DR Portal:-<br><br>
			<ul>
			<li><b>Account Name</b> : ".$row_data['company_name']." </li>
			<li><b>Lead Type</b> : ".$row_data['lead_type']." </li>
			<li><b>City</b> : ".$row_data['city']." </li>
			<li><b>Address</b> : ".htmlspecialchars($row_data['address'], ENT_QUOTES)." <br>
			<li><b>Mobile</b> : ".$row_data['eu_mobile']." </li>
            <li><b>Reseller Name</b> : ".$row_data['r_name']." </li>
            <li><b>License Type</b> : ".$row_data['license_type']." </li>
			<li><b>Quantity</b> : ".$row_data['quantity']." </li>
			<li><b>Assigned To</b> : ".$caller_name." </li>";
			if($data['status']=='Undervalidation' || $_POST['status']=='Cancelled')
			$body.=	"<li><b>Reason</b> : ".$_POST['reason']." </li>";
			$body.=	"<li><b>Admin Comment</b> : ".htmlspecialchars($_POST['add_comment'])." </li>";
			$body.="</ul><br>Thanks,<br>
			SketchUp DR Portal";
            $mail->Body =$body; 
            $mail->AddAttachment("$target_file"); 
			if(!$_POST['dr_code'])
			$mail->Send();
	if($row_data['quantity']>=9)
	{
		$mail->AddAddress("jayesh.patel@arkinfo.in","Jayesh Patel");
		$mail->AddAddress("maneesh.kumar@arkinfo.in","Maneesh Kumar");
		$mail->AddAddress("shivram@corelindia.co.in","Satish Baniya");
		$mail->AddAddress("sathish.venugopal@corel.com");
		 $mail->AddBCC("deepranshu.srivastava@arkinfo.in", "Deepranshu Srivastava");  
		 $mail->Subject = "Lead status has been changed on DR Portal [".$row_data['company_name']."]";
			$body= "Hi,<br><br> Below account status has been changed to ".$stat." on DR Portal:-<br><br>
			<ul>
			<li><b>Account Name</b> : ".$row_data['company_name']." </li>
			<li><b>Lead Type</b> : ".$row_data['lead_type']." </li>
			<li><b>City</b> : ".$row_data['city']." </li>
			<li><b>Address</b> : ".htmlspecialchars($row_data['address'], ENT_QUOTES)." <br>
			<li><b>Mobile</b> : ".$row_data['eu_mobile']." </li>
            <li><b>Reseller Name</b> : ".$row_data['r_name']." </li>
            <li><b>License Type</b> : ".$row_data['license_type']." </li>
            <li><b>Quantity</b> : ".$row_data['quantity']." </li>
			<li><b>Assigned To</b> : ".$caller_name." </li>";
			if($data['status']=='Undervalidation' || $_POST['status']=='Cancelled')
			$body.=	"<li><b>Reason</b> : ".$_POST['reason']." </li>";
		    $body.=	"<li><b>Admin Comment</b> : ".htmlspecialchars($_POST['add_comment'])." </li>";
			$body.="</ul></br>Thanks,<br>
            SketchUp DR Portal";
            
            $mail->Body =$body; 
            $mail->AddAttachment("$target_file"); 
			if(!$_POST['dr_code'])
			$mail->Send();
		
	}
	if($sql)
		{
		 	
			redir("manage_orders.php?update=success",true);
		}
	
}
if($_POST['remarks'] && !$_POST['activity_edit'])
{
    //echo "deepranshu"; die;
	$res=db_query("insert into activity_log (pid,description,activity_type,call_subject,added_by,data_ref) values ('".$_POST['pid']."','".htmlspecialchars($_POST['remarks'], ENT_QUOTES)."','Lead','".$_POST['call_subject']."','".$_SESSION['user_id']."',1)");
    $email=db_query("select r_email,r_name,r_user,lead_type,company_name,eu_email,eu_mobile,team_id,quantity,partner_close_date,caller from orders where id=".$_POST['pid']);
    $data=db_fetch_array($email); 
    $mail->AddCC("prashant.dongrikar@arkinfo.in", "Prashant");   
    $mail->AddCC("kailash.bhurke@arkinfo.in");  
    $mail->AddBCC("deepranshu.srivastava@arkinfo.in", "Deepranshu Srivastava");  
    if($data['lead_type']=='LC')
    {
        if($data['caller']!='')
        {
        $caller_email1=db_query("select users.email as call_email from users join callers on users.id=callers.user_id where callers.id=".$data['caller']);
        $caller_email=db_fetch_array($caller_email1); 
        $mail->AddAddress($caller_email['call_email']);     
        }
         
        $manager_email=getSingleresult("select email from users where user_type='MNGR' and team_id=".$data['team_id']);
        $mail->AddCC($_SESSION['email']);
        $mail->AddCC($manager_email);
        $mail->AddCC("virendra@corelindia.co.in");
        //$mail->AddCC("maneesh.kumar@arkinfo.in");
$mail->Subject = $data['company_name']." - New Log a Call" ;
$mail->Body    = "Hello,<br><br> There is new log a call from ".$_SESSION['name']." on SketchUp DR Portal with details as below:-<br><br>
<ul>
<li><b>Partner Name</b> : ".$data['r_name']." </li>
<li><b>Account Name</b> : ".$data['company_name']." </li>
<li><b>Lead Type</b> : ".$data['lead_type']." </li>
<li><b>Call Subject</b> : ".htmlspecialchars($_POST['call_subject'], ENT_QUOTES)." </li>
<li><b>Description</b> : ".htmlspecialchars($_POST['remarks'], ENT_QUOTES)." </li>
<li><b>Quantity</b> : ".$data['quantity']." </li>
<li><b>Projected Close Date</b> : ".$data['partner_close_date']." </li></ul><br>
Thanks,<br>
SketchUp DR Portal
";
    }
    else{
        $manager_email=getSingleresult("select email from users where user_type='MNGR' and team_id=".$data['team_id']);
      
        $mail->AddAddress($manager_email);
        $mail->AddCC($data['r_email']);
        $mail->AddCC($_SESSION['email']);
        //$mail->AddCC("maneesh.kumar@arkinfo.in");
        $mail->Subject = $data['company_name']." - New Log a Call" ;
        $mail->Body    = "Hello,<br><br> There is new log a call from ".$_SESSION['name']." on SketchUp DR Portal with details as below:-<br><br>
        <ul>
        <li><b>Partner Name</b> : ".$data['r_name']." </li>
        <li><b>Account Name</b> : ".$data['company_name']." </li>
        <li><b>Lead Type</b> : ".$data['lead_type']." </li>
        <li><b>Call Subject</b> : ".htmlspecialchars($_POST['call_subject'], ENT_QUOTES)." </li>
        <li><b>Description</b> : ".htmlspecialchars($_POST['remarks'], ENT_QUOTES)." </li>
        <li><b>Quantity</b> : ".$data['quantity']." </li>
        <li><b>Projected Close Date</b> : ".$data['partner_close_date']." </li></ul><br>
        Thanks,<br>
        SketchUp DR Portal
        ";
    }
$mail->Send();  
$mail->ClearAllRecipients();
}
if($_POST['activity_edit'])
{
    $res=db_query("update activity_log set description='".htmlspecialchars($_POST['remarks'], ENT_QUOTES)."',call_subject='".$_POST['call_subject']."' where id=".$_POST['pid']);
}
?>
<!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">View Lead</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">View Lead</li>
                        </ol>
                    </div>
                    <div class="col-md-7 col-4 align-self-center">

                          <div class="d-flex m-t-10 justify-content-end">
                            
                             
                            <div class="">
                                <a href="#" id="addnewtask"><button  data-toggle="tooltip" data-placement="left" title="" data-original-title="Add New Task" class="right-side bottom-right waves-effect waves-light btn-primary btn btn-circle btn-md pull-right m-l-10"><i class="ti-plus text-white"></i></button></a>
                                 
                            </div>
                        </div>
                        
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
				<?php  if($_REQUEST['id']) { $sql=db_query("select * from orders where id=".$_REQUEST['id']);
				$data=db_fetch_array($sql);
				@extract($data);
				}
				else
				{
					redir("manage_orders.php",true);
				}
				?>
                <div class="row">
                    <div class="col-12">
                    <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Lead Modify Log</h4> 
                                <button id="modify_log" class="btn btn-primary pull-right" style="margin-top: -28px;"> Show </button>
                                <h6 class="card-subtitle"></h6>
                                <div id="modify_log_div">

                                <ul class="font_weight list-style-none">
                                <?php if($data['lapsed_date'] && $data['lapsed_date']!='0000-00-00 00:00:00')
                                 {?>
                                <li>Lapsed on <strong><?=date('F j, Y, g:i a',strtotime($data['lapsed_date']))?></strong></li>
                                    
                                       <?php
                                 }

                                        $sql=db_query("select * from lead_modify_log where log_status='Active' AND lead_id=".$_REQUEST['id']." order by id desc");                                     
                                      

                                        if(db_num_array($sql)>0){                                           
                                          
                                         while ( $data_lml=db_fetch_array($sql)) {?>
                                            
                                        <li> <?= getSingleresult("select name from users where id=".$data_lml['created_by'])?> has changed <strong> <?=$data_lml['type']?> </strong> from <strong> <?=($data_lml['previous_name']?$data_lml['previous_name']:'N/A')?> </strong> to <strong> <?=$data_lml['modify_name']?></strong> on <?=date('F j, Y, g:i a',strtotime($data_lml['created_date']))?>.
                                            </li>
                                     
                                       <?php

                                       
                                         $count++;}

                                        }
                                        if(strtotime(getSingleresult("select created_date from lead_modify_log where lead_id=".$_REQUEST['id']." order by id desc limit 1"))>strtotime(getSingleresult("select created_date from activity_log where pid=".$_REQUEST['id']." order by id desc limit 1"))) 
                                        $lmb=db_query("select created_date, created_by from lead_modify_log where log_status='Active' AND lead_id=".$_REQUEST['id']." order by id desc limit 1");                                     
                                        else
                                        $lmb=db_query("select created_date as created_date, added_by as created_by  from activity_log where  pid=".$_REQUEST['id']." order by id desc limit 1");                                     
                                        $lmb_row=(db_fetch_array($lmb));
                                        ?>
                                        
                                    <li>Created by <strong><?=getSingleresult("select name from users where id=".$created_by)?></strong> on <strong><?=date('F j, Y, g:i a',strtotime($created_date))?></strong> <?php if($lmb_row['created_by']) { ?> - Last Modified by <strong><?=getSingleresult("select name from users where id=".$lmb_row['created_by'])?></strong> on <strong><?=date('F j, Y, g:i a',strtotime($lmb_row['created_date']))?></strong><?php } ?></li>
                                 

                                </ul>

                            </div>

                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Reseller Info <?php if ($code) { ?> - DR Code: (<?=$code?>)<?php } ?> - License Type:(<?=$license_type?>)</h4>
                                <h6 class="card-subtitle"></h6>
                                <table style="clear: both" class="table table-bordered table-striped" id="user">
                                    <tbody>
                                        <tr>
                                            <td width="35%">Reseller Name</td>
                                            <td width="65%"><?=$r_name.' ('.getSingleresult("select reseller_id from partners where id=".$team_id).')'?></td>
                                        </tr>
                                        <tr>
                                            <td>Reseller Email</td>
                                            <td>
                                               <?=$r_email?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Submitted By</td>
                                            <td>
                                              <?=$r_user?> &nbsp;<button  class="btn btn-primary" onclick="change_user('<?=$_GET['id']?>','<?=$_SESSION['team_id']?>')" >Change</button> 
                                            </td>
                                        </tr>
                                        <?php if($allign_to) { ?>
                                        <tr>
                                            <td>Aligned To</td>
                                            <td>
                                              <?=getSingleresult("select name from users where id=".$allign_to)?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
						<div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Customer Information</h4>
                               
                                <h6 class="card-subtitle">  <button class="btn btn-primary m-l-20" onclick="view_duplicate('<?=$id?>')">View Duplicates</button></h6>
                                <table style="clear: both" class="table table-bordered table-striped" id="user">
                                    <tbody>
                                        <tr>
                                            <td width="35%">Lead Source</td>
                                            <td width="65%"><?=$source?></td>
                                        </tr>
                                        <tr>
                                            <td>Lead Type</td>
                                            <td>
                                                <?=$lead_type?>
                                                <input type="hidden" value="<?=$lead_type?>" id="ltype"/>
                                                <?php 
                                                
                                                if($_SESSION['sales_manager']!=1)
                                                {?>
                                                &nbsp;&nbsp;<select onchange="update_type(this.value);" id="type_lead"><option value="">Change Lead Type</option>
                                                    <option value="LC">Change to LC</option>
                                                    <option value="BD">Change to BD</option>
                                                    <option value="Incoming">Change to Incoming</option>
                                                </select>
                                                <?php }
                                                
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Company Name</td>
                                            <td>
                                               <?=$company_name?> 
                                               
                                               
                                              
                                               </td>
                                        </tr>
										<tr>
                                            <td>Parent Company</td>
                                            <td>
                                               <?=$parent_company?>
                                            </td>
                                        </tr>
										<tr>
                                            <td>Landline Number</td>
                                            <td>
                                               <?=$landline?>
                                            </td>
                                        </tr>
										<tr>
                                            <td>Industry</td>
                                            <td>
                                               <?=getSingleresult("select name from industry where id='".$industry."'")?>
                                            </td>
                                        </tr>
										<?php if($sub_industry) { ?><tr>
                                            <td>Sub Industry</td>
                                            <td>
                                               <?=getSingleresult("select name from sub_industry where id='".$sub_industry."'")?>
                                            </td>
                                        </tr>
										<?php } ?>
										<tr>
                                            <td>Region</td>
                                            <td>
												<?=$region?>
                                            </td>
                                        </tr>
										<tr>
                                            <td>Address</td>
                                            <td>
												<?=$address?>
                                            </td>
                                        </tr>
										<tr>
                                            <td>Pin Code</td>
                                            <td>
                                                <?=$pincode?>
                                            </td>
                                        </tr>
										<tr>
                                            <td>State</td>
                                            <td>
                                                <?=getSingleresult("select name from states where id='".$state."'")?>
                                            </td>
                                        </tr>
										<tr>
                                            <td>City</td>
                                            <td>
                                                <?=$city?>
                                            </td>
                                        </tr>
										<tr>
                                            <td>Country</td>
                                            <td>
                                                <?=$country?>
                                            </td>
                                        </tr>
										
                                    </tbody>
                                </table>
                            </div>
                        </div>
						<div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Decision Maker/Proprietor/Director/End User Details</h4>
                                <h6 class="card-subtitle"></h6>
                                <table style="clear: both" class="table table-bordered table-striped" id="user">
                                    <tbody>
                                        <tr>
                                            <td width="35%">Full Name</td>
                                            <td width="65%"> <?=$eu_name?></td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td>
                                              <?=$eu_email?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Landline Number</td>
                                            <td>
                                                 <?=$eu_landline?>
                                            </td>
                                        </tr>
										<tr>
                                            <td>Department</td>
                                            <td>
                                                 <?=$department?>
                                            </td>
                                        </tr>
										<tr>
                                            <td>Mobile</td>
                                            <td>
                                                <?=$eu_mobile?>
                                            </td>
                                        </tr>
										<tr>
                                            <td>Designation</td>
                                            <td>
                                                <?=$eu_designation?>
                                            </td>
                                        </tr>
										<tr>
                                            <td>Role</td>
                                            <td>
                                                <?=$eu_role?>
                                            </td>
                                        </tr>
										<tr>
                                            <td>Account Visited</td>
                                            <td>
                                                 <?=$account_visited?>
                                            </td>
                                        </tr>
										<tr>
                                            <td>Visit/Profiling Remarks</td>
                                            <td>
                                                 
												
                                            <?php	
												$new=db_query("select id,description,created_date,added_by,call_subject from activity_log where pid='".$_GET['id']."' UNION SELECT id,description,created_date,added_by,id from caller_comments where pid='".$_GET['id']."' union select id,comment as description,created_date,added_by,id from review_log where lead_id='".$_GET['id']."' order by created_date desc");
												 $goal=db_query("select * from activity_log where pid='".$_GET['id']."' order by created_date desc");
											$count=mysqli_num_rows($new);
                                            $i=$count; if($count){ echo  ' <table class="col-12"><tr><th>S.No</th><th>Subject</th><th>Description</th><th>Added By</th><th>Date</th><th>Action</th></tr>';
                                             
                                             while($data_n=db_fetch_array($new)) { ?>
                                            
                                            <tr><td><?=$i?></td>
                                            <td><?=($data_n['call_subject']?$data_n['call_subject']:'N/A')?></td>
                                            <td><?=$data_n['description']?></td>
                                            <td><?=(is_numeric($data_n['added_by'])?getSingleresult("select concat(name,'-',CASE  WHEN user_type='USR' THEN 'Partner User'    WHEN user_type='ADMIN' and sales_manager=0 THEN 'ADMIN' WHEN user_type='ADMIN' and sales_manager=1 THEN 'Sales Manager'   WHEN user_type='MNGR' THEN 'Partner Manager'  ELSE 'Caller' END) as nme from users where id='".$data_n['added_by']."'"):'<span style="color:red">(Lead Review!)</span> '.$data_n['added_by'])?></td>
                                            <td><?=date('d-m-Y H:i:s',strtotime($data_n['created_date']))?></td>
                                            <td><a href="javascript:void(0)" title="Edit" id=but<?=$data['id']?> onclick="edit_activity('<?=$data_n['id']?>')"> <i style="font-size:18px" class="mdi mdi-pencil"></i></a></td>
                                            </tr>
											<?php $i--; } echo "</table>"; } ?>
                                            
											
											<?=date('d-m-Y H:i:s',strtotime($created_date))?>: <?=$visit_remarks?> 
                                      
                                                <button onclick="add_activity(<?=$_GET['id']?>)" class="btn btn-primary">Log a Call</button>&nbsp; 
                                               
                                            
											  
												 
                                            </td>
                                        </tr>
										<tr>
                                            <td>Usage Confirmation Received from</td>
                                            <td>
                                               <?=$confirmation_from?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
						<div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Lead Information</h4>
                                <h6 class="card-subtitle"></h6>
                                <table style="clear: both" class="table table-bordered table-striped" id="user">
                                    <tbody>
                                        <tr>
                                            <td width="35%">Type of License</td>
                                            <td width="65%"><?=$license_type?></td>
                                        </tr>
                                        <tr>
                                            <td width="35%">OS</td>
                                            <td width="65%"><?=$os?></td>
                                        </tr>
                                        <tr>
                                            <td width="35%">Version</td>
                                            <td width="65%"><?=$version?></td>
                                        </tr>
                                        <tr>
                                            <td width="35%">Runrate/Key</td>
                                            <td width="65%"><?=$runrate_key?></td>
                                        </tr>
                                        <tr>
                                            <td>Quantity</td>
                                            <td>
                                              <?=$quantity?> User(s)
                                            </td>
                                        </tr>
										
										 <tr>
                                            <td>Status</td>
                                            <td>
                                             <?php if($data['status']=='Cancelled')
												{
													echo '<span class="text-danger">Unqualified</span>';
													
												}
											else if($data['status']=='Approved')
												{
													echo '<span class="text-success">Qualified</span>';
													
												}
											else if($data['status']=='Undervalidation') { echo '<span class="text-warning">Under Validation</span>';
											}
											else
											{
												echo '<span class="text">Pending</span>';
											}
												?>
                                            </td>
                                        </tr>
										
																				 <tr>
                                            <td>Closing Status</td>
                                            <td>
                                           <?php if($data['status']=='Approved')
												{
													$ncdate=strtotime(date('Y-m-d'));
													$closeDate=strtotime($data['close_time']);
													if($ncdate>$closeDate)
													{
														$dayspassedafterExpired = ceil(abs($ncdate - $closeDate) / 86400);
														$daysLeft='<span style=color:red;">Expired ('.$dayspassedafterExpired.' Days Passed)</span>';
													}

													else
													{
														
														$remaining_days=ceil(($closeDate-$ncdate)/84600);
														$daysLeft='<span style="color:green">Days Left- '.$remaining_days.'</span>';
													}
													 
												echo '<span style="color:green">Qualified</span> '.$daysLeft;
												}
												else if($data['status']=='Cancelled')
												{
													echo '<span class="text-danger">Unqualified</span>';
													
												}
												else if($data['status']=='Pending')
													
													{
														echo 'Pending';
													}

															
															?>
                                            </td>
                                        </tr>
										
										<tr>
                                            <td>Created on</td>
                                            <td>
                                              <?=date('d-m-Y H:i:s',strtotime($created_date))?>
                                            </td>
                                        </tr>
                                        <form action="#" method="post" enctype="multipart/form-data">
                                        <?php if($_SESSION['user_type'] != 'REVIEWER')
                                        { ?>
										<?php if($user_attachement && $user_attachement!='') { ?>
										<tr>
                                            <td>Attachment</td>
                                            <td>
                                              <a href="<?=$user_attachement?>" target="_blank" >View/Download</a>
                                            </td>
                                        </tr>
										<tr>
										<?php } ?>
                                        <?php if($admin_attachment) { ?>
										<tr>
                                            <td>Admin Attachment</td>
                                            <td>
                                              <a href="<?=$admin_attachment?>" target="_blank" >View/Download</a>
                                            </td>
                                        </tr>
										<tr>
										<?php } ?>
										
										<form action="#" method="post" enctype="multipart/form-data">
                                            <td>Status</td>
                                            <td>
                                              <select <?=(($_SESSION['sales_manager']==1)?'disabled':'')?> onchange="status_update(this.value)" class="form-control" required name="status">
											  <option value="">---Select---</option>
											  <option <?=(($status=='Undervalidation')?'Selected':'')?> value="Undervalidation">Re-Submission Required</option>
											   <option <?=(($status=='Approved')?'Selected':'')?> value="Approved">Qualified</option>
											  <option <?=(($status=='Cancelled')?'Selected':'')?> value="Cancelled">Unqualified</option>
                                              <option <?=(($status=='On-Hold')?'Selected':'')?> value="On-Hold">On-Hold</option>
											  </select>
                                            </td>
                                        </tr>
			<tr id="reason" <?php if($status!='Cancelled') { ?> style="display:none" <?php } ?>>
                                            <td>Reason</td>
                                            <td>


                                                
                                             <select <?=(($_SESSION['sales_manager']==1)?'disabled':'')?> class="form-control" id="reason_dd" name="reason">
											 <option value="">---Select---</option>
		<option <?=(($reason=='Already having licenses')?'Selected':'')?> value="Already having licenses">Already having licenses</option>
<option <?=(($reason=='Already logged account')?'Selected':'')?> value="Already logged account">Already logged account</option>
<option <?=(($reason=='Out Of Territory Criteria')?'Selected':'')?> value="Out Of Territory Criteria">Out Of Territory Criteria</option>
<option <?=(($reason=='BD Efforts are missing')?'Selected':'')?> value="BD Efforts are missing">BD Efforts are missing</option>
<option <?=(($reason=='Duplicate Record Found')?'Selected':'')?> value="Duplicate Record Found">Duplicate Record Found</option>
<option <?=(($reason=='Others')?'Selected':'')?> value="Others">Others</option>
											 </select>
                                            </td>
                                        </tr>
                                      <tr id="reason_ud" <?php if($status!='Undervalidation') { ?> style="display:none" <?php } ?>>
                                            <td>Reason</td>
                                            <td>
                                             <select class="form-control" id="reason_ud" name="reason_ud">
											 <option value="">---Select---</option>
											<option <?=(($reason=='Unclear Remarks')?'Selected':'')?> value="Unclear Remarks">Unclear Remarks</option>
<option <?=(($reason=='Re-Visit Required')?'Selected':'')?> value="Re-Visit Required">Re-Visit Required</option>
<option <?=(($reason=='Need more clarity on usage')?'Selected':'')?> value="Need more clarity on usage">Need more clarity on usage</option>
<option <?=(($reason=='Incorrect Email Id')?'Selected':'')?> value="Incorrect Email Id">Incorrect Email Id</option>
<option <?=(($reason=='Incorrect contact number')?'Selected':'')?> value="Incorrect contact number">Incorrect contact number</option>
<option <?=(($reason=='Incorrect Decision Maker details')?'Selected':'')?> value="Incorrect Decision Maker details">Incorrect Decision Maker details</option>
											 </select>
                                            </td>
                                        </tr>  
                                        <?php } ?>
                                        <tr id="caller" <?php if($status!='Approved' && $lead_type!="LC") { ?> style="display:none" <?php } ?>>
                                            <td>Caller</td>
                                            <td>
											<?php if(is_numeric($caller) || $caller=='')
											{
												 $res=db_query("select * from callers order by name ASC"); 
												 ?>
												 <select name="caller" id="caller" class="form-control" data-validation-required-message="This field is required">
													 <option value="">---Select---</option>
													 <?php while($row=db_fetch_array($res))
													 { ?>
												 <option <?=(($caller==$row['id'])?'selected':'')?> value='<?=$row['id']?>'><?=$row['name'].' ('.$row['caller_id'].')'?></option>
													 <?php } ?>
													 </select>
											<?php	
											} else { ?>
											
                                             <select class="form-control" id="caller_dd" name="caller">
											<option value="">---Select---</option>
											<option <?=(($caller=='Bhagyashree Shetty')?'selected':'')?> value="Bhagyashree Shetty">Bhagyashree Shetty</option>
                                                                                        <option <?=(($caller=='Fayyaz Ahmed')?'selected':'')?>  value="Fayyaz Ahmed">Fayyaz Ahmed</option>
                                                                                        <option <?=(($caller=='Manisha Shinde')?'selected':'')?>  value="Manisha Shinde">Manisha Shinde</option>
                                                                                        <option <?=(($caller=='Prathamesh Kargutkar')?'selected':'')?>  value="Prathamesh Kargutkar">Prathamesh Kargutkar</option>
                                                                                        <option <?=(($caller=='Shweta Makwana')?'selected':'')?>  value="Shweta Makwana">Shweta Makwana</option>
                                                                                        <option <?=(($caller=='Seyed Mavujeen')?'selected':'')?>  value="Seyed Mavujeen">Seyed Mavujeen</option>
                                                                                        <option <?=(($caller=='Nitish Shetty')?'selected':'')?>  value="Nitish Shetty">Nitish Shetty</option>
                                                                                        <option <?=(($caller=='Rukaiya Shaikh')?'selected':'')?>  value="Rukaiya Shaikh">Rukaiya Shaikh</option>
                                                                                        <option  <?=(($caller=='Azhar Nirban')?'selected':'')?> value="Azhar Nirban">Azhar Nirban</option>
                                                                                        <option  <?=(($caller=='Kiran Sharma')?'selected':'')?> value="Kiran Sharma">Kiran Sharma</option>
                                                                                        <option  <?=(($caller=='Fiza Shaikh')?'selected':'')?> value="Fiza Shaikh">Fiza Shaikh</option>
                                                                                        <option  <?=(($caller=='Vijay Singh')?'selected':'')?> value="Vijay Singh">Vijay Singh</option>
                                                                                        <option <?=(($caller=='Nithesh Anchan')?'selected':'')?>  value="Nithesh Anchan">Nithesh Anchan</option>
                                                                                        <option <?=(($caller=='Omkar Mhaske')?'selected':'')?>  value="Omkar Mhaske">Omkar Mhaske</option>

											 </select>
											<?php } ?>
                                            </td>
                                        </tr>
                                        <tr id="sfdc_check" <?php if($status!='Approved') { ?> style="display:none" <?php } ?>>
                                        <td>Exclude from SFDC Export</td>
                                       <td><input type="checkbox" class="checkbox" name="sfdc_check" <?=($sfdc_check?'checked':'')?> value ="1" id="sfdc_checkbox"><label for="sfdc_checkbox"></label></td>
                                        </tr>
										<tr>
                                            <td>Additional Comment</td>
                                            <td>
                                             <textarea <?=(($_SESSION['sales_manager']==1)?'disabled':'')?> class="form-control" name="add_comment"><?=$add_comment?></textarea>
                                            </td>
                                        </tr>
										<tr>
                                            <td>Attachment</td>
                                            <td>
                                             <input type="file" <?=(($_SESSION['sales_manager']==1)?'disabled':'')?> class="form-control" name="admin_attachment">
                                            </td>
                                        </tr>
                                            
										<tr>
                                            <td>Stage</td>
                                            <td>
                                             <?=$stage?> 
                                            </td>
                                        </tr>
										<tr id="payment" <?php if($stage!="EU PO Issued") { ?> style="display:none" <?php } ?>>
										<?php $payment_status=$add_comm; ?>
                                            <td>Payment Status</td>
                                            <td>
												<?=$add_comm?>
                                            </td>
                                        </tr>
                                        <?php if($stage=="EU PO Issued")
                                        {?>
									    <tr id="op" <?php if($add_comm=='Payment in Installments') { ?> style="display:none" <?php } ?>>
										<td>Order Processing for this month</td>
										<td><?=$data['op_this_month']?></td>
                                        </tr>
                                        <?php } ?>
										  <tr id="pay_tab" <?php if($add_comm!='Payment in Installments') { ?> style="display:none" <?php } ?>>
										<td>Installment Details</td>
										<?php
											$inst_query=db_query("select * from installment_details where type='Lead' and pid='".$_GET['id']."'");
											$inst_data=db_fetch_array($inst_query);

										?>
										<td><table  style="clear: both; border:1px solid black !important" class="table table-bordered table-striped" width="100%" >
										<tbody>
										<tr>
										<td  >
										<p><strong>1<sup>st</sup> Installment Date</strong></p>
										</td>
										<td>
										 <?=$inst_data['date1']?> 
										</td>
										<td  >
										<p><strong>2<sup>nd</sup> Installment Date</strong></p>
										</td>
										<td  >
										<?=$inst_data['date2']?> 
										</td>
										</tr>
										<tr>
										<td  >
										<p><strong>Installment Amount</strong></p>
										</td>
										<td  >
										 <?=$inst_data['instalment1']?>
										</td>
										<td >
										<p><strong>Installment Amount</strong></p>
										</td>
										<td  >
										<?=$inst_data['instalment2']?>
										</td>
										</tr>
										<tr>
										<td >
										<p><strong>3<sup>rd</sup> Installment Date</strong></p>
										</td>
										<td  >
										 <?=$inst_data['date3']?> 
										</td>
										<td  >
										<p><strong>4<sup>th</sup> Installment Date</strong></p>
										</td>
										<td  >
										 <?=$inst_data['date4']?> 
										</td>
										</tr>
										<tr>
										<td >
										<p><strong>Installment Amount</strong></p>
										</td>
										<td >
									  <?=$inst_data['instalment3']?> 
										</td>
										<td  >
										<p><strong>Installment Amount</strong></p>
										</td>
										<td >
										<?=$inst_data['instalment4']?>
										</td>
										</tr>
										</tbody>
										</table>
										</td>
										</tr>
                                        <tr><td>Projected Close Date</td>
                                        <td><input type="text" value="<?=$partner_close_date?>" class="form-control col-md-2 datepicker" id="cl_date" name="partner_close_date" /></td>
                                        </tr>
                                        <tr>
										<input type="hidden" name="dr_code" value="<?=$code?>" />
                                         <td>
                                         <?php if($_SESSION['sales_manager']!=1) { ?>
                                         <button type="submit" onclick="" class="btn btn-primary">Save</button>
											<input type="hidden" value="<?=$created_by?>" name="lead_by" />
                                            <input type="hidden" value="<?=$quantity?>" name="quant" />
                                            </form>
                                            <?php if($iss)
                                            {?>
                                             <a href="edit_caller.php?eid=<?=$id?>"><button type="button" class="btn btn-primary">Edit</button></a>
                                       
                                            <?php } else { ?>
                                            <a href="edit_order.php?eid=<?=$id?>"><button type="button" class="btn btn-primary">Edit</button></a>
                                          <?php } } ?>
                                            <button type="button" onclick="javascript:history.go(-1)" class="btn btn-inverse">Back</button>
											
											</td>
											<td></td>
                                             
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" data-theme="default" class="default-theme">1</a></li>
                                <li><a href="javascript:void(0)" data-theme="green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" data-theme="red" class="red-theme">3</a></li>
                                <li><a href="javascript:void(0)" data-theme="blue" class="blue-theme working">4</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" data-theme="megna" class="megna-theme">6</a></li>
                                <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                                <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme">7</a></li>
                                <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" data-theme="red-dark" class="red-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" data-theme="blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" data-theme="megna-dark" class="megna-dark-theme ">12</a></li>
                            </ul>
                            <ul class="m-t-20 chatonline">
                                <li><b>Chat option</b></li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/1.jpg" alt="user-img" class="img-circle"> <span>Varun Dhavan <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/2.jpg" alt="user-img" class="img-circle"> <span>Genelia Deshmukh <small class="text-warning">Away</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/3.jpg" alt="user-img" class="img-circle"> <span>Ritesh Deshmukh <small class="text-danger">Busy</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/4.jpg" alt="user-img" class="img-circle"> <span>Arijit Sinh <small class="text-muted">Offline</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/5.jpg" alt="user-img" class="img-circle"> <span>Govinda Star <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/6.jpg" alt="user-img" class="img-circle"> <span>John Abraham<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/7.jpg" alt="user-img" class="img-circle"> <span>Hritik Roshan<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/8.jpg" alt="user-img" class="img-circle"> <span>Pwandeep rajan <small class="text-success">online</small></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
<div id="myModal" class="modal fade" role="dialog">
  

</div>
<?php include('includes/footer.php') ?>
<script>

function status_update(r)
{
	if(r=='Cancelled')
	{
	 $( "#reason" ).show();
	 $("#reason_dd").prop('required',true);
     $( "#caller" ).hide();
	 $("#caller_dd").prop('required',false);
	 $( "#reason_ud" ).hide();
     $("#sfdc_check").hide();
	}
	else if(r=='Approved')
	{
		$( "#reason_ud" ).hide();
		$( "#reason" ).hide();
        $("#sfdc_check").show();
        //alert('Yes');
		var ltype=$("#ltype").val();
               if(ltype=='LC')
           {
            $( "#caller" ).show();
          $("#caller_dd").prop('required',true);
             }
    } 
    else if(r=='On-Hold')
	{
		$( "#reason_ud" ).hide();
		$( "#reason" ).hide();
        $("#sfdc_check").hide();
    }
    else
        {
	 $( "#reason" ).hide();
	 $( "#reason_ud" ).show();
	 $("#reason_ud").prop('required',true);
	 $("#reason_dd").prop('required',false);
     $( "#caller" ).hide();
     $("#sfdc_check").hide();
	 $("#caller_dd").prop('required',false);
	}
}
function change_user(id,team_id)
{
	 $.ajax({  
    type: 'POST',  
    url: 'ajax_change_user.php',
	data:{id:id,team_id:team_id},
    success: function(response) { 
	$("#myModal").html();
          $("#myModal").html(response);
        $('#myModal').modal('show');
 }
     });
}
function update_type(a)
{
  if(a) {
      swal({   
            title: "Are you sure?",   
            text: "You want to change Lead Type!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Yes, convert it!",   
            cancelButtonText: "No, cancel modification!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) {
                $.ajax({url: "update_lead.php?oid=<?=$_GET['id']?>&type="+a, success: function(result){
            if(result)
            {
            swal({title:"Done!",  text:"Lead converted.",  type:"success"}, function() {
            window.location = "view_order.php?id=<?=$_GET['id']?>";
                });
            }
               }});
                  
            } else {     
                swal("Cancelled", "Lead unchanged!", "error");   
            } 
        });
    }
}
function view_activity(a)
{
	var type='Lead';
	 $.ajax({  
    type: 'POST',  
    url: 'view_activity.php',
	data:{pid:a,type:type},
    success: function(response) { 
	$("#myModal").html();
          $("#myModal").html(response);
        $('#myModal').modal('show');
 }
     });
}	
function add_activity(a)
{
	 $.ajax({  
    type: 'POST',  
    url: 'add_activity.php',
	data:{pid:a},
    success: function(response) { 
	$("#myModal").html();
          $("#myModal").html(response);
        $('#myModal').modal('show');
 }
     });
}

$(document).ready(function() {
        var leadId = <?=$_REQUEST['id']?>;
        $('#addnewtask').click(function(){
           $.ajax({
            type : 'POST',
            url : 'addnewtask.php',
            data:{leadId:leadId},
            success : function(res){               
              $('#myModal').html('');
               $('#myModal').html(res);
              $('#myModal').modal('show');
            }

           });
        })

        $('#modify_log_div').hide();       
        $('#modify_log').html('Show');     
  
        $('#modify_log').click(function(){

           // $('#modify_log_div').toggle();
            var text = $(this).html();
            if(text =='Show'){
                $(this).html('Hide');
                $('#modify_log_div').show();
            }else{
                  $(this).html('Show');
                $('#modify_log_div').hide();
            }



        })

     });
     $(function() {
    $('.datepicker').daterangepicker({
        
      "singleDatePicker": true,
    "showDropdowns": false,
    "opens":"right",
    autoUpdateInput: false,
     locale: {
      format: 'YYYY-MM-DD'
    }
});
$('.datepicker').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD'));
  });
 
        
    });

    function edit_activity(id)
{
	 $.ajax({  
    type: 'POST',  
    url: 'edit_activity.php',
	data:{id:id},
    success: function(response) { 
	$("#myModal").html();
          $("#myModal").html(response);
        $('#myModal').modal('show');
 }
     });
}

function view_duplicate(id)
{
    $.ajax({  
    type: 'POST',  
    url: 'view_duplicate.php',
	data:{id:id},
    success: function(response) { 
	$("#myModal").html();
          $("#myModal").html(response);
        $('#myModal').modal('show');
 }
     });
}
</script>