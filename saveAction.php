 <?php include('includes/include.php');  
        $parent_id = mysqli_real_escape_string($GLOBALS['dbcon'],$_REQUEST['pid']);
        $leadId = intval($_REQUEST['leadId']);
        $subject = mysqli_real_escape_string($GLOBALS['dbcon'],$_REQUEST['subject']); 
        $comment = mysqli_real_escape_string($GLOBALS['dbcon'],$_REQUEST['comment']); 
        $status = mysqli_real_escape_string($GLOBALS['dbcon'],$_REQUEST['status']); 
        $user_id=intval($_REQUEST['user_id']);
        $user_name=mysqli_real_escape_string($GLOBALS['dbcon'],$_REQUEST['name']);
        $action=intval($_REQUEST['action']);
        /* update data */

        $parent_id = $parent_id?$parent_id:0;

        $taskId = mysqli_real_escape_string($GLOBALS['dbcon'],$_REQUEST['taskId']);
        $type = mysqli_real_escape_string($GLOBALS['dbcon'],$_REQUEST['type']);

        $lead = db_query("select lead_type,company_name,eu_name,quantity,caller from orders where orders.id='".$leadId."'");

        $lead_caller=db_fetch_array($lead);

        if(isset($action) && isset($comment) && isset($status)) {

            $to = db_fetch_array(db_query("select users.name,users.email,users.id from users join callers on callers.user_id=users.id where callers.id='".$lead_caller['caller']."' limit 1"));   
            //print_r($to);
          $insertdata = db_query("insert into lead_action_list (`parent_id`,`lead_id`,`action`,`comment`,`status`,`created_by`,caller,`created_at`)values('".$parent_id."','".$leadId."','".$action."','".$comment."','".$status."','".$user_id."','".$to['id']."','".date("Y-m-d h:m:s")."')");

            $lastId = get_insert_id();

            if($parent_id!=0){                               
               
                $update = db_query("update lead_action_list set  status = '".$status."' where id ='".$parent_id."' ");
            }
               
//echo "select users.name,users.email from users join callers on callers.user_id=users.id where callers.id='".$lead['caller']."' limit 1"; die;
                 

                $action = db_fetch_array(db_query("select name from status_list where id='".$action."'"));       
                if($parent_id=0)
                {               
                    
                $addTo[] = ($to['email']); 
                $addCc[] = ("prashant.dongrikar@arkinfo.in"); 
                $addCc[] = ("kailash.bhurke@arkinfo.in");   
                  $addBcc[] = ("deepranshu.srivastava@arkinfo.in");

                  $setSubject = "New Action Added - ".$action['name'];

                  $body    ="<h3 style='color:magenta'>New Action...!!</h3><br>Dear ".$to['name'].",<br><br> ".$user_name." has added a new action on DR Portal:-<br><br>
                 <ul>
                 <li><b>Action Required </b> : ".$action['name']."</li> 
                 <li><b>Account Name</b> : ".$lead['company_name']."</li> 
                 <li><b>Contact Person</b> : ".$lead['eu_name']."</li> 
                 <li><b>Quantity </b> : ".$lead['quantity']."</li> 
                  <li><b>Description</b> : ".str_replace("'", "\'",htmlspecialchars($comment, ENT_QUOTES))."</li> 
                 <li><b>Status</b> : ".getSingleresult("select name from status_list where id=".$status)."</li> 
                 </ul>
                  <br>
                  Kindly take decision and update status soon on DR Portal.
                <br>
                    Please view this actions in &quot;Action List&quot; panel of your DR Portal.
                    <br>
                  <br>

                  Thanks,<br>
                  SketchUp DR Portal";             
                   
                  sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
                }
                else
                {
                    $created_by=getSingleresult("select created_by from lead_action_list where lead_id='".$leadId."' and parent_id='0' ");
                  //print_r($created_by); die;
                    $to = db_fetch_array(db_query("select name, email from users where id='". $created_by."' limit 1"));  
                     $addTo[] = ($to['email']); 
                $addCc[] = ("prashant.dongrikar@arkinfo.in"); 
                $addCc[] = ("kailash.bhurke@arkinfo.in");  
                  $addBcc[] = ("deepranshu.srivastava@arkinfo.in");   

                  $setSubject = "Action Updated - ".$action['name'];

                  $body    ="<h3 style='color:blue'>Action Updated...!!</h3><br>Dear ".$to['name'].",<br><br> ".$user_name." has updated action on DR Portal:-<br><br>
                 <ul>
                 <li><b>Action Required </b> : ".$action['name']."</li> 
                 <li><b>Account Name</b> : ".$lead['company_name']."</li> 
                 <li><b>Contact Person</b> : ".$lead['eu_name']."</li> 
                 <li><b>Quantity </b> : ".$lead['quantity']."</li> 
                  <li><b>Description</b> : ".str_replace("'", "\'",htmlspecialchars($comment, ENT_QUOTES))."</li> 
                 <li><b>Status</b> : ".getSingleresult("select name from status_list where id=".$status)."</li> 
                 </ul>
                  
                  <br>

                  Thanks,<br>
                  SketchUp DR Portal";             
                   
                  sendMail($addTo, $addCc, $addBcc, $setSubject, $body);

                }

                if($insertdata || $update){

              echo json_encode(array('success'=>'true','error'=>'false'));
              
        	   }else{
                echo json_encode(array('success'=>'false','error'=>'true'));
            }

        }

    ?>
