       <?php

             
              include('includes/include.php');            

                if(isset($_POST['title'])){

                	$insertdata = db_query("INSERT INTO what_new(`title`,`description`,`created_by`,`created_at`)values('".mysqli_real_escape_string($GLOBALS['dbcon'],$_POST['title'])."','".mysqli_real_escape_string($GLOBALS['dbcon'],$_POST['description'])."','".$_SESSION['user_id']."','".date("Y-m-d H:i:s")."')");
   
                    if($insertdata){

                      echo "true";die;
                      
                	}else{
                        echo "false";die;
                    }

                }

            ?>
