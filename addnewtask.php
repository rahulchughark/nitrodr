           <?php include("includes/include.php");
            $leadId = intval($_REQUEST['leadId']);
            ?>

           <div class="modal-dialog modal-dialog-centered modal-lg">
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">New Task</h5>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                       </button>
                   </div>




                   <div class="modal-body">
                       <form name="addnewtaskform" id="taskForm" method="post">
                           <input type="hidden" name="lead" id="lead" value="<?= $leadId ?>">
                           <div class="row">
                               <div class="col-md-12">
                                   <div class="form-group">
                                       <label for="example-text-input">Assignee To</label>
                                       <select name="assigneto" class="form-control" id="assigneto" required>

                                           <option value=''> Select User </option>

                                           <?php
                                            $sql = db_query("select users.id,name from users INNER JOIN orders ON users.team_id = orders.team_id where users.status='Active' and orders.id ='" . $leadId . "' UNION ALL select id,name from callers");
                                            while ($row = db_fetch_array($sql)) { ?>

                                               <option value=<?= $row['id'] ?>> <?= $row['name'] ?> </option>

                                           <?php } ?>
                                       </select>
                                   </div>
                               </div>

                               <div class="col-md-12">
                                   <div class="form-group">
                                       <label for="example-text-input">Subject</label>


                                       <select name="subject" class="form-control" id="subject" required>

                                           <option value=''> Select Subject </option>

                                           <?php

                                            $sql = db_query("select * from status_list as subject where subject.type ='subject'");
                                            while ($row = db_fetch_array($sql)) { ?>

                                               <option value=<?= $row['id'] ?>> <?= $row['name'] ?> </option>

                                           <?php
                                            }
                                            ?>
                                       </select>
                                   </div>
                               </div>
                               <div class="col-md-12">
                                   <div class="form-group">
                                       <label for="example-text-input">Comment</label>

                                       <textarea class="form-control" name="comment" id="comment" rows="5" required></textarea>
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

                                               <option value=<?= $row['id'] ?>> <?= $row['name'] ?> </option>

                                           <?php } ?>
                                       </select>
                                   </div>
                               </div>
                           </div>

                           <div class="modal-footer">
                               <button type="submit" name="tasknewbutton" class="btn btn-primary" id="tasknewbutton" value="Save">Save</button>

                               <button type="button" class="btn btn-light" onclick="javascript:history.go(-1)" data-dismiss="modal">Close</button>

                           </div>

                       </form>

                   </div>
               </div>

           </div>


           <script type="text/javascript">
               $('document').ready(function() {

                   $('#taskForm').submit(function() {
                      
                       //var formdata = $("form#taskForm").serialize();
                       var lead = $('#lead').val();
                       var assigneto = $('#assigneto').val();
                       var subject = $('#subject').val();
                       var comment = $('#comment').val();
                       var status_id = $('#status_id').val();
                       //alert(status_id);
                       $(".preloader").show();
                       $.ajax({
                           type: 'post',
                           url: 'newtask_add.php',
                           data: {
                               leadId: lead,
                               assigneto: assigneto,
                               subject: subject,
                               comment: comment,
                               status_id: status_id
                           },
                           success: function(res) {
                               $(".preloader").hide();
                               // alert(res);
                               // return;
                               if (res) {
                                   swal({
                                       title: "Done!",
                                       text: "Task added successfully.",
                                       type: "success"
                                   }, function() {
                                       window.location = "task_list.php";
                                   });
                               } else {
                                   swal({
                                       title: "Error!",
                                       text: "Unable to insert..",
                                       type: "error"
                                   }, function() {
                                       window.location = "task_list.php";
                                   });

                               }
                           }

                       });

                   });


               });
           </script>