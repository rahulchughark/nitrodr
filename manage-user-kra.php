<?php include('includes/header.php');admin_page();

$helper = new DataController;


// filter results
 // salse manager
 if(isset($_GET['role'])){            
 $role = $_GET['role'];
   }else{
    $role = [];
   } 

// partners
        if(isset($_GET['partnersId'])){            
        $partnersId = $_GET['partnersId'];
        }else{
        $partnersId = [];
        } 

// status

    if(isset($_GET['status'])){            
    $status = $_GET['status'];
    }else{
    $status = '';
    }

?>

<style>
    .manage-users-main {
        max-height: calc(100vh - 207px);
        overflow: auto;
    }
</style>

<!-- ============================================================== -->
<!-- Page wrapper  -->
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                           <div class="row">
                                <div class="col">
                                    <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">
                                            <small class="text-muted">Home > Manage User KRA</small>
                                            <h4 class="font-size-14 m-0 mt-1">Manage User KRA</h4>
                                        </div>
                                    </div>
                                </div>
                               
                        </div>



                        <?php if($_GET['add']=='success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> User Added Successfully!
                                </div>
                                <?php } ?>

                                <?php if($_GET['update']=='success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> User Updated Successfully!
                                </div>
                                <?php } ?>
                                <?php if($_GET['email']=='fail') { ?>
                                <div class="alert alert-warning">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    <h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i> Warning!</h3> User with this email already exists!
                                </div>
                            <?php } ?>
                                <div class="table-responsive">
                                    <table id="example23" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                            <th data-sortable="true">S.No.</th>
                                            <th data-sortable="true">DR ID</th>
                                                <th data-sortable="true">User Name</th>
                                                <th data-sortable="true">VAR Organization</th>
                                                <th data-sortable="true">User Type</th>
                                                <th data-sortable="true">Email</th>
                                                <th data-sortable="true">Contact Number</th>
                                                <th data-sortable="true">Role</th>
                                                <th data-sortable="true">Status</th>
                                                <th data-sortable="true">Last Login On</th>
                                                <th data-sortable="true">KRA Updated</th>
                                                <th data-sortable="true">Assign KRA</th>
                                                <!-- <th data-sortable="true">Last Logout On</th> -->
                                            </tr>
                                        </thead>

                                        <tbody>
                                        <?php 


                                        $con = "";
                                            if(!empty($partnersId))
                                            {
                                            $PartnersIds = implode(",",$partnersId);
                                            $con .= " and team_id in (".$PartnersIds.")"; 
                                            }

                                            if(!empty($role))
                                            {
                                            $role = implode("','",$role);
                                            $con .= " and role in ('".$role."')"; 
                                            }

                                            if($status != '')
                                            {
                                            $status = $status;
                                            $con .= " and status = '".$status."'"; 
                                            }


                                        if ($_SESSION['sales_manager'] == 1) {
                                            $active_users = " and team_id in (" . $_SESSION['access'] . ") ";
                                        }
                                        if($_GET['iss']==1){
                                            $sql=db_query("select * from users where status='Active' and role='TC' $active_users order by id desc");
                                        }else if($_GET['sal']==1){
                                            $sql=db_query("select * from users where status='Active' and role='SAL' $active_users order by id desc");
                                        }else if($_GET['ae']==1){
                                            $sql=db_query("select * from users where status='Active' and role='AE' $active_users order by id desc");
                                        }
                                        else{
                                            $sql=db_query("select * from users where 1 ".$con." and user_type IN ('CLR', 'DA') and role != 'PARTNER' order by id desc");
                                        }
                                        
                                        $i = 1;
                                        while($data=db_fetch_array($sql)){
                                        //print_r($data); die;
                                        ?>
                                        
                                        <tr id="tr-id-1" class="tr-class-1">
                                        <td><?=$i?></td>
                                        <td><?=$data['id']?></td>
                                        <td id="td-id-1" class="td-class-1">
                                        <a style="color:#000;" href="edit_user.php?id=<?=$data['id']?>">
                                        <?=$data['name']?></a>
                                        </td>
                                        <td><?=(($data['team_id'])?getSingleresult("select name from partners where id=".$data['team_id']):'N/A')?></td>

                                        <td><?=(($data['user_type'])?getSingleresult("select role_type from user_type_role where role_code='".$data['user_type']."'"):'N/A')?>
                                                </td>

                                        <td><?=$data['email']?></td>   
                                                                 
                                        <td><?=$data['mobile']?></td>
                                                                                            
                                                <td><?=(($data['role'])?getSingleresult("select role_name from role where role_code='".$data['role']."'"):'N/A')?>
                                                </td>

                                                <td><?=$data['status']?></td>

                                                <?php $login_time = getSingleresult("select login_time from user_tracking where user_id=".$data['id']." ORDER BY id DESC LIMIT 1");?>
                                                <td><?= (!empty($login_time))?date("Y-m-d H:i:s",$login_time):'NA'?></td>

                                                <?php $logout_time = getSingleresult("select logout_time from user_tracking where user_id=".$data['id']." ORDER BY id DESC LIMIT 1,1");?>
                                                <!-- <td><?= (!empty($logout_time))?date("Y-m-d H:i:s",$logout_time):'NA'?></td> -->
                                                <?php if($helper->checkKRATargetExistsByUser($data['id'])): ?>
                                                <td><span  style="font-size: 25px;color: green;" class="mdi mdi-check"></span></td>
                                                <?php else: ?>
                                                <td><span style="font-size: 25px;color: red;" class="mdi mdi-close"></span></td>
                                                <?php endif; ?>
                                                <td class="text-center">
                                                    <a href="kra_new.php?id=<?=$data['id']?>" class="btn btn-primary btn-xs" title="KRA"><span class="mdi mdi-account-plus" style="font-size: 16px"></span>
                                                    </a>
                                                 </td>
                                            </tr>
                                        <?php $i++; } ?>
                                        </tbody>
                                    </table>
                                </div>

                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
            </div> <!-- container-fluid -->
</div>
<!-- End Page-content -->
 
<?php include('includes/footer.php') ?>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
        $(document).ready(function() {
            var table = $('#example').DataTable({
                "stateSave": true,
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
            $('#example tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
        });
    });
    $('#example23').DataTable({
        dom: 'Bfrtip',
        language: {
            paginate: {
                previous: '<i class="fas fa-arrow-left"></i>',
                next: '<i class="fas fa-arrow-right"></i>'
            }
        },
        buttons: [
                    'copy', 'csv', 'excel',
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
                    },  'print', 'pageLength',
                    
                ],
		lengthMenu: [
        [ 15, 25, 50, 100,500,1000 ],
        [ '15', '25', '50','100','500', '1000' ]
    ],
        "displayLength": 15,
    });
    </script>
    <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 310);				
				$("#example23").tableHeadFixer(); 

            });

            $(document).ready(function() {
                $('.multiselect_partners').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Partners'
                });
            });

            $(document).ready(function() {
                $('.multiselect_role').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Role Type'
                });
            });

            function clear_search() {
                window.location = 'manage_users_admin.php';
            }
</script>