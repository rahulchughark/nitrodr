           <?php include("includes/include.php"); ?>

           <div class="modal-dialog modal-dialog-centered">
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">New Action</h5>

                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                       </button>
                   </div>


                   <div class="modal-body">

                       <form name="addnewtaskform" id="taskForm">
                           <input type="hidden" name="action_id" id="action_id" value="<?= $_REQUEST['actionid'] ?>">
                           <input type="hidden" name="lead" id="lead" value="<?= $_REQUEST['leadId'] ?>">
                           <div class="row">
                               <div class="col-md-12">
                                   <div class="form-group">
                                       <label for="action">Action</label>

                                       <select name="action" class="form-control" id="action">

                                           <option value=''> Select Action </option>

                                           <?php

                                            $action = getSingleresult("select action from lead_action_list where id = '" . $_REQUEST['actionid'] . "'");

                                            $sql = db_query("select * from status_list as action where action.type ='action'");
                                            while ($row = db_fetch_array($sql)) { ?>

                                               <option value=<?= $row['id'] ?> <?= (($action == $row['id']) ? 'selected' : '') ?>> <?= $row['name'] ?> </option>

                                           <?php
                                            }
                                            ?>
                                       </select>
                                   </div>
                               </div>
                           </div>
                           <div class="row">
                               <div class="col-md-12">
                                   <div class="form-group">
                                       <label for="status">Status</label>

                                       <select <?php if ($_REQUEST['user_type'] == 'MNGR') {
                                                    echo "disabled";
                                                } ?> name="action_status" class="form-control" id="action_status"  >

                                           <?php

                                            $status = getSingleresult("select status from lead_action_list where id = '" . $_REQUEST['actionid'] . "'");

                                            $sql = db_query("select * from status_list as status where status.type ='action_status'");
                                            while ($row = db_fetch_array($sql)) { ?>

                                               <option value=<?= $row['id'] ?> <?= (($status == $row['id']) ? 'selected' : '') ?>> <?= $row['name'] ?> </option>

                                           <?php } ?>
                                       </select>
                                   </div>
                               </div>
                           </div>
                           <div class="row">
                               <div class="col-md-12">
                                   <div class="form-group">
                                       <label for="comment">Comment</label>
                                       <?php
                                       $action_coment = getSingleresult("select comment from lead_action_list where id = '" . $_REQUEST['actionid'] . "'");
                                       ?>
                                       <textarea class="form-control" name="comment" id="comment" rows="10"><?=@$action_coment?></textarea>
                                   </div>
                               </div>
                           </div>
                   <div class="modal-footer">
                       <button type="submit" id="actionnewbutton" class="btn btn-primary" name="submit">Save</button>
                       <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                   </div>

                   </form>

                   </div> 
               </div>
           </div>
         </div>



           <script type="text/javascript">
               $('document').ready(function() {

                   $('#actionnewbutton').click(function(event) {
                      event.preventDefault();
                    // alert('ok');

                       //var formdata = $("form#taskForm").serialize();
                       var action_id = $('#action_id').val();
                       var lead = $('#lead').val();
                       var action = $('#action').val();
                       var status = $('#action_status').val();
                       var comment = $('#comment').val();
                       //var caller = $('#caller').val();
                       $(".preloader").show();
                       $('#actionnewbutton').html('Please wait..');
                       $('#actionnewbutton').prop('disabled', true);
                       $.ajax({
                           type: 'post',
                           url: 'saveAction.php', 
                           data: {
                               pid: action_id,
                               leadId: lead,
                               action: action,
                               comment: comment,
                               status: status,
                               user_id: '<?= $_REQUEST['user_id'] ?>',
                               name: '<?= $_REQUEST['name'] ?>'
                           },
                           success: function(res) {
                               // res = JSON.parse(res);

                               if (res) {
                                   $(".preloader").hide();
                                   
                                   swal({
                                       title: "Done!",
                                       text: "Action added successfully.",
                                       type: "success"
                                   }, function() {
                                       window.location = "lead_action_list.php";
                                   });
                               } else {
                                   swal({
                                       title: "Error!",
                                       text: "Unable to insert..",
                                       type: "error"
                                   }, function() {
                                       // window.location = "task_list.php";
                                   });

                               }
                           }

                       });

                   });


               });
           </script>