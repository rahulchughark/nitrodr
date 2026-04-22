           <?php include("includes/include.php"); ?>

           <?php

            $task_id = intval($_REQUEST['taskId']);
            if ($task_id != '') {

              $query = db_query("select task.*,assigned.* from task_list as task LEFT JOIN task_assigne as assigned ON task.id = assigned.task_id LEFT JOIN status_list as status ON task.status = status.id AND status.type='task' where task.id ='" . $task_id . "'");
              $result = db_fetch_array($query);

              //print_r($result);
            }

            $_REQUEST['leadId'] = intval($_REQUEST['leadId']);
            ?>


           <div class="modal-dialog modal-dialog-centered modal-lg">
             <div class="modal-content">
               <div class="modal-header">
                 <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Edit Task</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                 </button>
               </div>


               <div class="modal-body">

                 <form name="addnewtaskform" id="taskForm" method="post">
                   <input type="hidden" name="lead" id="lead" value="<?= $_REQUEST['leadId'] ?>">
                   <input type="hidden" name="taskId" id="taskId" value="<?= $task_id ?>">

                   <div class="row">
                     <div class="col-md-12">
                       <div class="form-group">
                         <label for="example-text-input">Assignee To</label>


                         <?php if ($_SESSION['user_type'] != 'ADMIN' || $_SESSION['user_type'] != 'SUPERADMIN' || $_SESSION['user_type'] != 'OPERATIONS' || $_SESSION['user_type'] != 'SALES MNGR') {
                            $disable = 'disabled';
                          }
                          ?>

                         <select name="assigneto" class="form-control" id="assigneto" disabled="">

                           <option value=''> Select User </option>

                           <?php

                            $sql = db_query("select users.id,name from users INNER JOIN orders ON users.team_id = orders.team_id where users.status='Active' and orders.id ='" . $leadId . "'");
                            while ($row = db_fetch_array($sql)) { ?>

                             <option value=<?= $row['id'] ?> <?= (($row['id'] == $result['assigne_to']) ? "selected" : '') ?>> <?= $row['name'] ?> </option>

                           <?php } ?>
                         </select>


                       </div>
                     </div>


                     <div class="col-md-12">
                       <div class="form-group">
                         <label for="example-text-input">Subject</label>
                         <select name="subject" class="form-control" id="subject" <?= $disable ?>>

                           <option value=''> Select Subject </option>

                           <?php

                            $sql = db_query("select * from status_list as subject where subject.type ='subject'");
                            while ($row = db_fetch_array($sql)) { ?>

                             <option value=<?= $row['id'] ?> <?= (($row['id'] == $result['subject_id']) ? "selected" : '') ?>> <?= $row['name'] ?> </option>

                           <?php } ?>
                         </select>
                       </div>
                     </div>
                     <div class="col-md-12">
                       <div class="form-group">
                         <?php if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'OPERATIONS' || $_SESSION['user_type'] == 'SALES MNGR') { ?>

                           <label for="example-text-input">Comment</label>

                           <textarea class="form-control" name="comment" id="comment" rows="10"><?= $result['comment'] ?></textarea>


                         <?php } else { ?>
                           <label for="example-text-input">Comment</label>


                           <textarea class="form-control" name="comment" id="comment" rows="10"><?= $result['comment'] ?></textarea>



                         <?php } ?>
                       </div>
                     </div>

                     <div class="col-md-12">
                       <div class="form-group">
                         <label for="example-text-input">Status</label>

                         <select name="status_id" class="form-control" id="status_id">

                           <?php

                            $sql = db_query("select * from status_list as status where status.type ='task'");
                            while ($row = db_fetch_array($sql)) {
                            ?>

                             <option value=<?= $row['id'] ?> <?= (($row['id'] == $result['status']) ? "selected" : '')
                                                            ?>> <?= $row['name'] ?> </option>

                           <?php } ?>
                         </select>
                       </div>
                     </div>
                   </div>
                   <div class="modal-footer">
                     <button type="submit" name="updatebutton" class="btn btn-primary" id="taskupdatebutton">Update</button>

                     <button type="button" class="btn btn-light" onclick="javascript:history.go(-1)" data-dismiss="modal">Back</button>

                   </div>

                   <!-- <td colspan=2 ><button type="submit" name="submit" value="submit" id="taskupdatebutton" class="btn btn-primary">Update</button>
                                            <button type="button" onclick="javascript:history.go(-1)" class="btn btn-success">Back</button> -->


                 </form>

               </div>
             </div>

           </div>


           <!--  
<script type="text/javascript">
    
    $('document').ready(function(){

         $('#taskupdatebutton').click(function(){       
            
             //var formdata = $("form#taskForm").serialize();
            
              var taskId = <?= $task_id ?>;
              var lead = $('#lead').val();
              var assigneto = $('#assigneto').val();
              var subject = $('#subject').val();
              var comment = $('#comment').val();
              var status = $('#status_id').val();   

               $.ajax({
                    type :'post',
                    url: 'newtask_add.php',
                    data :{taskId:taskId,leadId:lead,assigneto:assigneto,subject:subject,comment:comment,status_id:status_id,type:'update'},
                    success : function(res){ 

                       if(res){   
                          swal({title:"Done!",  text:"Status changed successfully.",  type:"success"}, function() {
                                    window.location = "task_list.php";
                              });
                      }else{
                           swal({title:"Error!",  text:"Unable to change status..",  type:"error"}, function() {
                                 
                              });

                      }
                    
                       
                    }

               });  

            });


    });


</script> -->