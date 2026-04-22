<?php include('includes/header.php');//admin_page();?>
<!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
    <div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Lead Action List</small>
                                    <h4 class="font-size-14 m-0 mt-1">Lead Action List</h4>
                                </div>
                            </div>

                                <?php if($_GET['add']=='success') { ?>
                                        <div class="alert alert-success">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> User Added Sucessfully!
                                        </div>
                                    <?php } ?>
                                   


                                <div class="table-responsive" style="margin-top:12px; float: left;">
                                    <table id="example23"  class="table display nowrap table-striped"   data-height="wfheight"  data-mobile-responsive="true" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                
                                                <th data-sortable="true">Action</th>
                                                <th data-sortable="true" style="width:300px">Comment</th>
                                                 <th data-sortable="true">Status</th>         
                                                 <th data-sortable="true">Created By</th>
                                                <th data-sortable="true">Created Date</th>
                                                  <th data-sortable="true">Caller Name</th>
                                                 <th data-sortable="true">Action</th>

                                            </tr>
                                        </thead>
                                     
                                        <tbody>
                                        <?php 

                                        if($_SESSION['user_type'] == "MNGR"){
                                           $subQuery  = " AND  action.created_by ='".$_SESSION['user_id']."'"; 
                                        }
                                        else if($_SESSION['user_type'] == "CLR"){

                                        
                                            $subQuery  = " AND  action.caller ='".$_SESSION['user_id']."'"; 
                                         }
                                           // echo "select action.*, creat_user.name as created_by,status.name as type_name,user_type from lead_action_list as action LEFT JOIN users as creat_user ON action.created_by = creat_user.id LEFT JOIN status_list as status ON action.status = status.id AND status.type='action_status' where 1 AND action.parent_id = '0' $subQuery order by action.id,action.parent_id desc"; die;

                                        $sql = db_query("select action.*, creat_user.name as created_by,status.name as type_name,user_type from lead_action_list as action LEFT JOIN users as creat_user ON action.created_by = creat_user.id LEFT JOIN status_list as status ON action.status = status.id AND status.type='action_status' where 1 AND action.parent_id = '0' $subQuery order by action.id desc");
                                        
                                         while($data=db_fetch_array($sql)){ 
                                            $action = getSingleresult("select status.name from lead_action_list as action LEFT JOIN status_list as status ON action.action = status.id AND status.type='action' where action.action = '".$data['action']."'");
                                            ?>
                                             <tr id="tr-id-1" class="tr-class-1">
                                            
                                            <td align="center"><?=$action?></td>
                                            <td align="center" style="width:300px; text-align:left"><?=$data['comment']?></td>
                                            <td align="center"><?=getSingleresult("select name from status_list where id=".$data['status'])?></td>
                                            <td align="center"><?=$data['created_by']?></td>
                                            <td align="center"><?=$data['created_at']?></td>
                                             <td align="center"><?=getSingleresult("select name from users where id=".$data['caller'])?></td>
                                                 
                                             </td>

                                            <td align="center">   <a href="#" onClick="viewStatus(<?=$data["id"]?>,<?=$data['lead_id']?>);" class="view_status" title="View Log" data-toggle="tooltip"><i class="fa fa-history"></i> </a> 

                                                  <?php if($_SESSION['user_type']=='ADMIN'||$_SESSION['user_type']=='SUPERADMIN'||$_SESSION['user_type']=='OPERATIONS'||$_SESSION['user_type']=='RADMIN')
                                             {?>
                                                <a href="#" onClick="changeStatus(<?=$data["id"]?>,<?=$data['lead_id']?>);" class="edit_status" title="Add Action" data-toggle="tooltip"><i class="fa fa-edit"></i> </a> 
                                                <a href="view_order.php?id=<?=$data['lead_id']?>" title="View Lead" data-toggle="tooltip" class="edit_status"><i class="fa fa-eye" ></i> </a>
                                             <?php } else if($_SESSION['user_type']=='CLR')
                                             {?>
                                                <a href="#" onClick="changeStatus(<?=$data["id"]?>,<?=$data['lead_id']?>);" class="edit_status" title="Add Action" data-toggle="tooltip"><i class="fa fa-edit"></i> </a> 
                                                <a href="caller_view.php?id=<?=$data['lead_id']?>" title="View Lead" data-toggle="tooltip" class="edit_status"><i class="fa fa-eye" ></i> </a>
                                             <?php } else
                                             { ?>
                                            <a href="partner_view.php?id=<?=$data['lead_id']?>" title="View Lead" data-toggle="tooltip" class="edit_status"><i class="fa fa-eye"></i> </a>
                                             <?php } ?>

                                            </td>
                                         </tr>
                                     <?php } ?>
                                                
                                        </tbody>
                                    </table>
                                    </div>

</div> <!-- end col -->
</div> <!-- end row -->
</div>
</div> <!-- container-fluid -->
</div>
<!-- End Page-content -->


<div id="myModal" class="modal fade" role="dialog">
  

</div>

 
<?php include('includes/footer.php') ?>
<script>

    $('#example23').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'excel', 'pdf'
        ] 
    });



      function changeStatus(actionid,lead_id){       

       $.ajax({
        type: 'post',
        url: 'addAction.php',
        data: {actionid:actionid,leadId:lead_id,user_id:'<?=$_SESSION['user_id']?>',name:'<?=$_SESSION['name']?>'},
        success: function(res){
          $('#myModal').html('');
               $('#myModal').html(res);
              $('#myModal').modal('show');

        }

       });


      }

      function viewStatus(actionid,lead_id){
       // alert('<?=$_SESSION['user_id']?>');
        $.ajax({
            type: 'post',
            url: 'actionLog.php',
            data: {actionid:actionid,leadId:lead_id},
            success: function(res){
              $('#myModal').html('');
                   $('#myModal').html(res);
                  $('#myModal').modal('show');

            }

       });

      }

      

    </script>

   <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 320);				
				$("#example23").tableHeadFixer(); 

            });
</script>
