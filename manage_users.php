<?php include('includes/header.php');?>
<!-- ============================================================== -->
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

                                    <small class="text-muted">Home >Manage Users</small>
                                    <h4 class="font-size-14 m-0 mt-1">Manage Users</h4>
                                </div>
                            </div>

                            <div class="btn-group float-right" role="group" style="margin-top:12px;"> 
                        <?php if($_SESSION['user_type']!='ISS MNGR' && $_SESSION['user_type']!='OPERATIONS'){?> 
                            <!-- <a href="add_user.php"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="Add User" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="fa fa-plus mr-1"></i></button></a> -->

                            <?php } ?>
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
                                            <th data-sortable="true">VAR Organization</th>
                                                <th data-sortable="true">User Name</th>
                                                <th data-sortable="true">Email</th>
                                                <th data-sortable="true">Mobile</th>
                                                <th data-sortable="true">Role</th>
                                                
                                            </tr>
                                        </thead>

                                        <tbody>
                                        <?php 
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
                                            $sql=db_query("select * from users where 1 and status='Active' $active_users order by id desc");
                                        }
                                       
										$i = 1;
										while($data=db_fetch_array($sql)){
										//print_r($data); die;
										?>
										
										<tr id="tr-id-1" class="tr-class-1">
                                        <td><?=$i?></td>
                                        <td><?=(($data['team_id'])?getSingleresult("select name from partners where id=".$data['team_id']):'N/A')?></td>
                                        <td id="td-id-1" class="td-class-1"> <?=$data['name']?></td>
                                        <td><?=$data['email']?></td>
                                        <td><?=$data['mobile']?></td>
                                       <td><?=(($data['role'])?getSingleresult("select role_name from role where role_code='".$data['role']."'"):'N/A')?></td>
                                       
                                               
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
        "displayLength": 15,
        buttons: [
            'copy', 'csv', 'excel', 
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            },  'print', 'pageLength', 'print','pageLength'
        ],
		lengthMenu: [
        [ 15, 25, 50, 100,500,1000 ],
        [ '15', '25', '50','100','500', '1000' ]
    ],
    });
    </script>
    <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 320);				
				$("#example23").tableHeadFixer(); 

            });
</script>