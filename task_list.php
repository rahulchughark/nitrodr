<?php include('includes/header.php'); 
if($_POST['status_id']){
    //print_r($_POST);die;
    $user_id=$_SESSION['user_id'];
    $comment = str_replace("'", "\'",$_POST['comment']);
    $leadId = $_POST['leadId'];

    $taskId = intval($_POST['taskId']);
    $type = mysqli_real_escape_string($GLOBALS['dbcon'],$_POST['type']);


    //if($type =='update'){
     if($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'OPERATIONS' ||$_SESSION['user_type'] == 'SALES MNGR'||$_SESSION['user_type'] == 'OPERATIONS EXECUTIVE')
     {

         $updated = db_query("update task_list set comment='". $comment."', status=".$_POST['status_id'].",updated_by=".$_SESSION['user_id'].",updated_at='".date('Y-m-d')."' where id=".$taskId."");

         //print_r($updated);die;
      }else{

         $updated = db_query("update task_list set status=".$_POST['status_id'].", comment='". $comment."',updated_by=".$_SESSION['user_id'].",updated_at='".date('Y-m-d')."' where id=".$taskId."");
         //print_r($updated);die;
       // echo "update task_list set status='".$status."', comment=concat(comment,' ','". $comment."'),updated_by='".$_SESSION['user_id']."',updated_at='".date('Y-m-d')."' where id='".$taskId."'"; 
        }
        if($updated){
            echo "<script type=\"text/javascript\">
          window.location = \"task_list.php\"
        </script>";
                         
         }else{
            echo '<script>';
            echo 'setTimeout(function () { swal({html:true,
                title:"Oopss!",
                text:"Unable to update.",
                type: "warning"});';
            echo '}, 100);</script>';
        }


   // }

}
?>
<!-- Page wrapper  -->
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">


            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >New Task</small>
                                    <h4 class="font-size-14 m-0 mt-1">New Task</h4>
                                </div>
                            </div>


                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> User Added Successfully!
                                </div>
                            <?php } ?>
                            <?php if ($_GET['email'] == 'fail') { ?>
                                <div class="alert alert-warning">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    <h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i> Warning!</h3> User with this email already exists!
                                </div>
                            <?php } ?>


                            <div class="table-responsive">
                                <table id="example23" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">S.No</th>
                                            <th data-sortable="true">Account Name</th>
                                            <th data-sortable="true">Subject</th>
                                            <th data-sortable="true">Status</th>
                                            <th data-sortable="true">Assigned By</th>
                                            <th data-sortable="true">Assigned To</th>
                                            <th data-sortable="true">Comment</th>
                                            <th data-sortable="true" style="width:130px;">Created Date</th>
                                            <th data-sortable="true" style="width:130px;">Updated Date</th>
                                            <th data-sortable="true">Action</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                        // $sql = db_query("select task.*,creat_user.name as created_by,status.name as type_name from task_list as task LEFT JOIN users as creat_user ON task.created_by = creat_user.id LEFT JOIN status_list as status ON task.status = status.id AND status.type='task' where 1 order by task.id desc");

                                        $userType = $_SESSION['user_type'];
                                        $subQuery = '';
                                        $subJoin = '';
                                        if ($userType == "CLR") {
                                            $subQuery = " AND task.created_by = '" . $_SESSION['user_id'] . "'";
                                        } else if ($userType == "ADMIN" || $userType == "SUPERADMIN" || $userType == "REVIEWER") {
                                            $subQuery = " ";
                                        } else if ($user_type == "MNGR") {
                                            $subQuery = " AND orders.team_id = '" . $_SESSION['team_id'] . "'";
                                        } else if ($user_type == "CQM") {
                                            $subQuery = " ";
                                        } else {

                                            $subQuery = " AND assigned.assigne_to = '" . $_SESSION['user_id'] . "'";
                                        }



                                        $sql = db_query("select task.*,as_user.name as assigned_to, creat_user.name as created_by,status.name as type_name,r_name,company_name from task_list as task INNER JOIN task_assigne as assigned ON task.id = assigned.task_id INNER JOIN  orders ON task.lead_id = orders.id LEFT JOIN users as as_user ON assigned.assigne_to = as_user.id LEFT JOIN users as creat_user ON task.created_by = creat_user.id LEFT JOIN status_list as status ON task.status = status.id AND status.type='task' where 1 $subQuery order by task.id desc");
                                        // echo "select task.*,as_user.name as assigned_to, creat_user.name as created_by,status.name as type_name,r_name from task_list as task INNER JOIN task_assigne as assigned ON task.id = assigned.task_id INNER JOIN  orders ON task.lead_id = orders.id LEFT JOIN users as as_user ON assigned.assigne_to = as_user.id LEFT JOIN users as creat_user ON task.created_by = creat_user.id LEFT JOIN status_list as status ON task.status = status.id AND status.type='task' where 1 $subQuery order by task.id desc"; die;
                                        $i = 1;
                                        while ($data = db_fetch_array($sql)) {
                                            $subject = getSingleresult("select status.name from task_list as task LEFT JOIN status_list as status ON task.subject_id = status.id AND status.type='subject' where task.subject_id = '" . $data['subject_id'] . "'");
                                        ?>
                                            <tr id="tr-id-1" class="tr-class-1">
                                                <td><?= $i ?></td>
                                                <td><?= $data['company_name'] ?></td>
                                                <td><?= $subject ?></td>
                                                <td><?= $data['type_name'] ?></td>
                                                <td><?= $data['created_by'] ?></td>
                                                <td> <?= $data['assigned_to'] ?>
                                                <td> <?= $data['comment'] ?>
                                                <td style="width:130px;"><?= $data['created_at'] ?></td>
                                                <td style="width:130px;"><?= $data['updated_at'] ?></td>

                                                <? //=getSingleresult("select u.name from task_assigne as assigned LEFT JOIN users as u ON assigned.assigne_to = u.id where assigned.task_id = '".$data['id']."' order by assigned.id desc")?>
                                                </td>

                                                <td> <a href="#" onClick="changeStatus(<?= $data["id"] ?>,<?= $data['lead_id'] ?>);" title="Edit Task" data-toggle="tooltip" class="edit_status"><i class="fa fa-edit"></i> </a>

                                                    <?php if ($_SESSION['user_type'] == 'ADMIN' ||$_SESSION['user_type'] == 'SUPERADMIN' ||$_SESSION['user_type'] == 'OPERATIONS'||$_SESSION['user_type'] == 'SALES MNGR') { ?>
                                                        <a href="view_order.php?id=<?= $data['lead_id'] ?>" title="View Lead" data-toggle="tooltip" class="edit_status"><i class="fa fa-eye"></i> </a>
                                                    <?php } else if($_SESSION['user_type'] == 'CLR') { ?>
                                                        <a href="caller_view.php?id=<?= $data['lead_id'] ?>" title="View Lead" data-toggle="tooltip" class="edit_status"><i class="fa fa-eye"></i> </a>
                                                        <?php }else{ ?>
                                                        <a href="partner_view.php?id=<?= $data['lead_id'] ?>" title="View Lead" data-toggle="tooltip" class="edit_status"><i class="fa fa-eye"></i> </a>
                                                    <?php } ?>

                                                </td>
                                            </tr>
                                        <?php $i++;
                                        } ?>

                                    </tbody>
                                </table>
                            </div>
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

            <!-- ============================================================== -->
            <!-- End Right sidebar -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->


        <div id="myModal" class="modal fade" role="dialog">


        </div>


        <?php include('includes/footer.php') ?>
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable();
                $(document).ready(function() {
                    var table = $('#example').DataTable({
                        "columnDefs": [{
                            "visible": false,
                            "targets": 2
                        }],
                        "order": [
                            [2, 'asc']
                        ],
                        "displayLength": 25,
                        "drawCallback": function(settings) {
                            var api = this.api();
                            var rows = api.rows({
                                page: 'current'
                            }).nodes();
                            var last = null;
                            api.column(2, {
                                page: 'current'
                            }).data().each(function(group, i) {
                                if (last !== group) {
                                    $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                                    last = group;
                                }
                            });
                        }
                    });
                    // Order by the grouping
                    $('#example23 tbody').on('click', 'tr.group', function() {
                        var currentOrder = table.order()[0];
                        if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                            table.order([4, 'desc']).draw();
                        } else {
                            table.order([4, 'asc']).draw();
                        }
                    });
                });
            });
            $('#example23').DataTable({
                dom: 'Bfrtip',
                "displayLength": 15,
                buttons: [
                    <?php if($_SESSION['download_status'] == 1){ ?>
                    'copy', 'csv', 'excel',  'print', 'pageLength',
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    }
                <?php }else{ ?> 'pageLength'  <?php } ?>
                ],
                lengthMenu: [
                    [15, 25, 50, 100, 500, 1000],
                    ['15', '25', '50', '100', '500', '1000']
                ],
            });






            function changeStatus(taskid, lead_id) {
                 //alert(lead_id);

                $.ajax({
                    type: 'post',
                    url: 'editTask.php',
                    data: {
                        taskId: taskid,
                        leadId: lead_id
                    },
                    success: function(res) {
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
                $('.dataTables_wrapper').height(wfheight - 300);				
				$("#example23").tableHeadFixer(); 

            });
        </script>