<?php include('includes/include.php');   
        // $leadId = mysqli_real_escape_string($GLOBALS['dbcon'],$_REQUEST['leadId']);
        // $assigneto = mysqli_real_escape_string($GLOBALS['dbcon'],$_REQUEST['assigneto']);   
        // $subject = mysqli_real_escape_string($GLOBALS['dbcon'],$_REQUEST['subject']); 
        // $comment = mysqli_real_escape_string($GLOBALS['dbcon'],$_REQUEST['comment']); 
        // $status = mysqli_real_escape_string($GLOBALS['dbcon'],$_REQUEST['status']); 

        $leadId = $_REQUEST['leadId'];
        $assigneto = $_REQUEST['assigneto'];   
        $subject = $_REQUEST['subject']; 
        $comment = $_REQUEST['comment']; 
        $status = $_REQUEST['status_id']; 

        /* update data */

        $taskId = intval($_REQUEST['taskId']);
        $type = mysqli_real_escape_string($GLOBALS['dbcon'],$_REQUEST['type']);


        if($type =='update'){
         if($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'OPERATIONS' ||$_SESSION['user_type'] == 'SALES MNGR'||$_SESSION['user_type'] == 'OPERATIONS EXECUTIVE')
         {
           
         $updated = db_query("update task_list set subject_id=".$_POST['subject'].",comment='". $comment."', status=".$_POST['status_id'].",updated_by=".$_SESSION['user_id'].",updated_at='".date('Y-m-d')."' where id=".$taskId."");
         
          }else{

             $updated = db_query("update task_list set status=".$status.", comment=concat(comment,' ','". $comment."'),updated_by=".$_SESSION['user_id'].",updated_at='".date('Y-m-d')."' where id=".$taskId."");
           // echo "update task_list set status='".$status."', comment=concat(comment,' ','". $comment."'),updated_by='".$_SESSION['user_id']."',updated_at='".date('Y-m-d')."' where id='".$taskId."'"; 
            }
            if($updated){
              echo "true";die;                      
             }else{
                echo "false";die;
            }


        }


        if(isset($assigneto) && isset($subject) && isset($comment) && isset($status)){
          $user_id=$_SESSION['user_id'];
          $comment = str_replace("'", "\'",$comment);
        
          $insertdata = db_query("INSERT INTO task_list(`lead_id`,`subject_id`,`comment`,`status`,`created_by`,`created_at`)values(".$leadId.",".$subject.",'".$comment."',".$status.",".$user_id.",'".date("Y-m-d")."')");

            $lastId = get_insert_id();

            if($lastId){

                $assigndata = db_query("INSERT INTO task_assigne(`task_id`,`assigne_to`,`assigne_by`,`assigne_date`)values(".$lastId.",".$assigneto.",".$user_id.",'".date("Y-m-d")."')");
                
                if($assigndata){


                 $lead = db_fetch_array(db_query("select lead_type,company_name,eu_name,quantity from orders where orders.id='".$leadId."'"));

                 $to = db_fetch_array(db_query("select name,email from users where id='".$assigneto."' limit 1"));    

                 $assigned = db_fetch_array(db_query("select name from users where id='".$_SESSION['user_id']."' limit 1"));         
     

                 //print_r($result);die;

                 $subject = db_fetch_array(db_query("select st.name as subject,comment from task_list as task INNER JOIN status_list as st ON task.subject_id = st.id where task.id='".$lastId."'"));

                  $status = db_fetch_array(db_query("select st.name as status from task_list as task INNER JOIN status_list as st ON task.subject_id = st.id where task.id='".$lastId."'"));
                  //$result['email'],
                  $addTo[] = ($to['email']);  
                  $addCc[] = ("prashant.dongrikar@arkinfo.in"); 
                  
                  $setSubject = "New Task Assigned-".$subject['subject'];

                  $body    ="<h3 style='color:blue'>New Task!</h3><br>Dear ".$to['name'].",<br><br> ".$assigned['name']." has assigned you the following new task:-<br><br>
                 
                  <b>Subject </b> : ".$subject['subject']."<br> 
                  <b>Contact</b> : ".$lead['eu_name']."<br>
                  <b>Opportunity</b> : ".$lead['company_name']. " (<b>" .$lead['quantity']. " user(s)</b>) <br> 
                  <b>Priority</b>: <span style='color:red'>High!</span><br>
                  <b>Remarks</b> : ".str_replace("'", "\'",htmlspecialchars($subject['comment'], ENT_QUOTES))." <br>
                  <br>

                  Thanks,<br>
                  SketchUp DR Portal";             
                
                  sendMail($addTo, $addCc, $addBcc, $setSubject, $body);

                  echo "true";die;
                }else{
                  echo "false";die;
                }
              
              
        	}else{
                echo "false";die;
            }

        }
