           
           <?php include("includes/include.php");
           $_REQUEST['actionid'] = intval($_REQUEST['actionid']);
           
           ?>  <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->

        <div class="modal-dialog modal-lg" >

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header ">
                      <h5 class="modal-title align-self-center mt-0" id="exampleModalLongTitle">Action List</h5>
                     
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>


                  <div class="modal-body">
                   
                    <div class="row">
                    <div class="col-12">
                      
                       
                            <!--

                           
                                <h6 class="card-subtitle"></h6>-->

                                <div class="table-responsive">
                                    <table id="example23"  class="table-striped table-bordered dataTable no-footer table table-hover" data-toggle="table" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                
                                                <th data-sortable="true">Action</th>
                                                <th data-sortable="true">Comment</th>
                                                 <th data-sortable="true">Status</th>         
                                                 <th data-sortable="true">Created By</th>
                                                <th data-sortable="true">Created Date</th>
                                                 
                                                 

                                            </tr>
                                        </thead>
                                     
                                        <tbody>
                                        <?php 

                                        // $sql = db_query("select task.*,creat_user.name as created_by,status.name as type_name from task_list as task LEFT JOIN users as creat_user ON task.created_by = creat_user.id LEFT JOIN status_list as status ON task.status = status.id AND status.type='task' where 1 order by task.id desc");

                                       

                                        //print_r($_SESSION);

                                        $sql = db_query("select action.*, creat_user.name as created_by,status.name as type_name,user_type from lead_action_list as action LEFT JOIN users as creat_user ON action.created_by = creat_user.id LEFT JOIN status_list as status ON action.status = status.id AND status.type='action_status' where 1 AND action.parent_id = '".mysqli_real_escape_string($GLOBALS['dbcon'],$_REQUEST['actionid'])."' AND action.lead_id = '".mysqli_real_escape_string($GLOBALS['dbcon'],$_REQUEST['leadId'])."'  order by action.id,action.parent_id desc");
                                         $num_rows=mysqli_num_rows($sql);
                                        if($num_rows > 0 )
                                        {
                                         while($data=db_fetch_array($sql)){ 
                                            $action = getSingleresult("select status.name from lead_action_list as action LEFT JOIN status_list as status ON action.action = status.id AND status.type='action' where action.action = '".$data['action']."'");
                                            ?>
                                             <tr id="tr-id-1" class="tr-class-1">
                                            
                                            <td><?=$action?></td>
                                            <td><?=$data['comment']?></td>
                                            <td><?=$data['type_name']?></td>
                                            <td><?=$data['created_by']?></td>
                                            <td><?=$data['created_at']?></td>
                                             
                                                  <? //=getSingleresult("select u.name from task_assigne as assigned LEFT JOIN users as u ON assigned.assigne_to = u.id where assigned.task_id = '".$data['id']."' order by assigned.id desc")?>
                                             </td>

                                           
                                         </tr>
                                     <?php } } else { ?>
                                              <tr id="tr-id-1" class="tr-class-1">
                                              <td colspan="5">No History found for this action!</td>
                                              </tr>  
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>


                              
                       
                        
                    </div>
                </div>

                    
                                                
            <div class="modal-footer">
                
             <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            
          </div>  


          </div>
         
           
        </div>
     
      </div>

 


   
