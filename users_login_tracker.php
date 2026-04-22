<?php include('includes/header.php');admin_page();

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
                            <a href="add_user.php"><button data-toggle="tooltip" data-placement="left" title="" data-original-title="Add User" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="fa fa-plus mr-1"></i></button></a>

                            <?php } ?>

                            <!-- filter start -->
                        <?php
                        if($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN'){ ?>

                            <button type="button" class="btn btn-xs btn-light ml-1  " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>
                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu1 dropdown-menu-right filter_wrap filter_wrap_2" id="filter-container" role="menu">

                                        <form method="get" name="search">
                                        <?php if (!is_array($partner)) {
                                                    // $val = $partner;
                                                    // $partner = array();
                                                    // $partner['0'] = $val;
                                                    // $partner_flag = 1;
                                                } 


                                        $res = db_query("select * from partners where status='Active'"); ?>

                                                <!-- <div class="form-group">
                                                  <select name="role[]" id="role" class="multiselect_role form-control" data-live-search="true" multiple>
                                                     <option <?=(in_array('BO', $role) ? 'selected' : '')?> value="BO">Business Owner</option>
                                                     <option <?=(in_array('BDM', $role) ? 'selected' : '')?> value="BDM">Business Development Manager</option>
                                                     <option <?=(in_array('SAL', $role) ? 'selected' : '')?> value="SAL">Sales Executive</option>
                                                     <option <?=(in_array('TC', $role) ? 'selected' : '')?> value="TC">Tele-Caller</option>
                                                     <option <?=(in_array('AE', $role) ? 'selected' : '')?> value="AE">Application Engineer</option>
                                                     <option <?=(in_array('ISS', $role) ? 'selected' : '')?> value="ISS">Corel ISS</option>
                                                     <option <?= (in_array('Renewal ISS', $role) ? 'selected' : '') ?> value="Renewal ISS">Renewal ISS</option>
                                                     <option <?= (in_array('Installation', $role) ? 'selected' : '') ?> value="Installation">Installation</option>
                                                  </select>
                                                </div> -->

                                                <div class="form-group">
                                                    <select name="status" class="form-control" id="partner_status">
                                                     <option value="">Select Status</option>
                                                         <option <?= (($_GET['status'] == 'Active') ? 'selected' : '') ?> value="Active">Active</option>
                                                         <option <?= (($_GET['status'] == 'Inactive') ? 'selected' : '') ?> value="Inactive">Inactive</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <select name="partnersId[]" id="partnersId" class="multiselect_partners form-control" data-live-search="true" multiple>

                                                        <?php 
                                                        while ($row = db_fetch_array($res)) { 
                                                            
                                                            ?>
                                                            <option <?= (in_array($row['id'], $partnersId) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                  
                                                                                                               
                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>

                                        </form>
                                    </div>
                                </div>
                                    <?php } ?>  
                        <!-- filter end -->    

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
                                                <th data-sortable="true">Last Action On</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        <?php 


                                        $con = "";
                                         if(!empty($partnersId))
                                         {
                                            $PartnersIds = implode(",",$partnersId);
                                           $con .= " and u.team_id in (".$PartnersIds.")"; 
                                         }

                                         if(!empty($role))
                                         {
                                            $role = implode("','",$role);
                                           $con .= " and u.role in ('".$role."')"; 
                                         }

                                         if($status != '')
                                         {
                                           $status = $status;
                                           $con .= " and u.status = '".$status."'"; 
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
                                            $sql=db_query("select ut.*,u.* from user_tracking as ut LEFT JOIN users AS u ON u.id=ut.user_id where 1 ".$con." order by ut.id desc");
                                        }
                                       
										$i = 1;
										while($data=db_fetch_array($sql)){
										//print_r($data); die;
										?>
										
										<tr id="tr-id-1" class="tr-class-1">
                                        <td><?=$i?></td>
                                        <td><?=$data['id']?></td>
                                                <td id="td-id-1" class="td-class-1">

                                                <a style="color:#000;font-weight:bold;" href="edit_user.php?id=<?=$data['id']?>">
                                                    <?=$data['name']?></a>
                                                </td>
                                                <td><?=(($data['team_id'])?getSingleresult("select name from partners where id=".$data['team_id']):'N/A')?></td>
                                                <td><?=(($data['user_type'])?getSingleresult("select role_type from user_type_role where role_code='".$data['user_type']."'"):'N/A')?></td>
                                                <td><?=$data['email']?></td>
                                                <td><?=$data['mobile']?></td>
                                                                                          
                                                 <td><?=(($data['role'])?getSingleresult("select role_name from role where role_code='".$data['role']."'"):'N/A')?></td>

                                                <td><?=$data['status']?></td>

                                                <?php $login_time = getSingleresult("select login_time from user_tracking where user_id=".$data['id']." ORDER BY id DESC LIMIT 1");?>
                                               <td><?= date("Y-m-d H:i:s",$login_time)?></td>
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
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print','pageLength'
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
                $('.dataTables_wrapper').height(wfheight - 320);				
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