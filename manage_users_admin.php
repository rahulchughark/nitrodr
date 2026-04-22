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

<style>
    .manage-users-main {
        max-height: calc(100vh - 207px);
        overflow: auto;
    }

    .status-toggle-wrap {
        display: inline-flex;
        align-items: center;
        gap: 0;
    }

    .status-toggle {
        position: relative;
        width: 44px;
        height: 24px;
        display: inline-block;
        cursor: pointer;
        margin: 0;
    }

    .status-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .status-slider {
        position: absolute;
        inset: 0;
        border-radius: 20px;
        background-color: #f46a6a;
        transition: .25s;
    }

    .status-slider:before {
        content: '';
        position: absolute;
        height: 18px;
        width: 18px;
        left: 3px;
        top: 3px;
        border-radius: 50%;
        background: #fff;
        transition: .25s;
    }

    .status-toggle input:checked + .status-slider {
        background-color: #34c38f;
    }

    .status-toggle input:checked + .status-slider:before {
        transform: translateX(20px);
    }

    #example23 tbody tr.inactive-user-row td {
        background-color: #ffeaea !important;
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
                                            <small class="text-muted">Home >Manage Users</small>
                                            <h4 class="font-size-14 m-0 mt-1">Manage Users</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">                                    
                                    <div role="group"> 
                                <?php if($_SESSION['user_type']!='ISS MNGR' && $_SESSION['user_type']!='OPERATIONS' && $_SESSION['user_type']!='AE'){?> 
         
                                    <?php } ?>
         
                                    <!-- filter start -->
                                <?php
                                if($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN'){ ?>

                                    <a href="add_user.php"><button title="Add User" class="btn btn-xs btn-light ml-1"><i class="ti-plus"></i></button></a>
         
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
         
         
                                                $res = db_query("select id,name from partners where status='Active' order by name asc"); ?>
         
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
                                                <th data-sortable="true">ID</th>
                                                <th data-sortable="true">Partner (Var Organization)</th>
                                                <th data-sortable="true">User Name</th>
                                                <th data-sortable="true">Email</th>
                                                <th data-sortable="true">Contact</th>
                                                <th data-sortable="true">Role</th>
                                                <th data-sortable="true">User Type</th>
                                                <th data-sortable="true">Status</th>
                                                <th data-sortable="true">Last Login</th>
                                                <th data-sortable="true">Created User</th>
                                                <th data-sortable="true">Action</th>

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
                                            $sql=db_query("select * from users where 1 ".$con." order by id desc");
                                        }
                                        
                                        while($data=db_fetch_array($sql)){
                                        //print_r($data); die;

                                        $role_name = 'N/A';
                                        if (!empty($data['role'])) {
                                            if ($data['role'] == 'Internal' || $data['role'] == 'Partner') {
                                                $role_name = $data['role'];
                                            } else {
                                                $role_name = getSingleresult("select role_name from role where role_code='".$data['role']."'");
                                                if (empty($role_name)) {
                                                    $role_name = $data['role'];
                                                }
                                            }
                                        }

                                        $user_type_name = 'N/A';
                                        if (!empty($data['user_type'])) {
                                            $userTypeMap = array(
                                                'ADMIN' => 'Administrator',
                                                'OPERATIONS' => 'Operation',
                                                'CLR' => 'Caller',
                                                'SALES MNGR' => 'Sales Manager',
                                                'MNGR' => 'Manager',
                                                'USR' => 'User'
                                            );
                                            if (isset($userTypeMap[$data['user_type']])) {
                                                $user_type_name = $userTypeMap[$data['user_type']];
                                            } else {
                                                $user_type_name = getSingleresult("select role_type from user_type_role where role_code='".$data['user_type']."'");
                                                if (empty($user_type_name)) {
                                                    $user_type_name = $data['user_type'];
                                                }
                                            }
                                        }

                                        $created_user = 'N/A';
                                        if (!empty($data['date_created'])) {
                                            $created_user = date('Y-m-d H:i:s', strtotime($data['date_created']));
                                        } else if (!empty($data['created_date'])) {
                                            $created_user = date('Y-m-d H:i:s', strtotime($data['created_date']));
                                        }

                                        $status_label = $data['status'];
                                        if ($status_label == 'InActive') {
                                            $status_label = 'Inactive';
                                        }

                                        $teamId = (int)($data['team_id'] ?? 0);
                                        $partnerName = 'N/A';
                                        if ($teamId > 0) {
                                            $partnerName = getSingleresult("select name from partners where id=".$teamId);
                                            if (empty($partnerName)) {
                                                $partnerName = 'N/A';
                                            }
                                        }
                                        ?>
                                        
                                        <tr id="tr-id-1" class="tr-class-1 <?= ($status_label == 'Active' ? '' : 'inactive-user-row') ?>">
                                        <td><?=$data['id']?></td>
                                                <td><?= $partnerName ?></td>
                                                <td id="td-id-1" class="td-class-1">

                                                <?php if ($_SESSION['user_type'] === 'AE'): ?>
                                                    <span style="color:#000;">
                                                        <?= htmlspecialchars($data['name']) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <a style="color:#000;" href="edit_user.php?id=<?= $data['id'] ?>">
                                                        <?= htmlspecialchars($data['name']) ?>
                                                    </a>
                                                <?php endif; ?>
                                                </td>
                                                <td><?=$data['email']?></td>
                                                <td><?=$data['mobile']?></td>

                                                <td><?=$role_name?></td>
                                                <td><?=$user_type_name?></td>

                                                <td>
                                                    <div class="status-toggle-wrap">
                                                        <label class="status-toggle" for="user_status_<?= $data['id'] ?>">
                                                            <input type="checkbox" id="user_status_<?= $data['id'] ?>" class="user-status-toggle" data-user-id="<?= $data['id'] ?>" data-current-status="<?= $status_label ?>" <?= ($status_label == 'Active' ? 'checked' : '') ?>>
                                                            <span class="status-slider"></span>
                                                        </label>
                                                        
                                                    </div>
                                                </td>

                                                <?php $login_time = getSingleresult("select login_time from user_tracking where user_id=".$data['id']." ORDER BY id DESC LIMIT 1");?>
                                                <td><?= (!empty($login_time))?date("Y-m-d H:i:s",$login_time):'NA'?></td>
                                                <td><?=$created_user?></td>

                                                <?php $logout_time = getSingleresult("select logout_time from user_tracking where user_id=".$data['id']." ORDER BY id DESC LIMIT 1,1");?>
                                                <!-- <td><?= (!empty($logout_time))?date("Y-m-d H:i:s",$logout_time):'NA'?></td> -->
                                                 <td>
                                                    <a title="Edit User" href="edit_user.php?id=<?= $data['id'] ?>" class="btn btn-primary px-2 py-1"><span class="mdi mdi-pencil"></span></a>
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

            $(document).on('change', '.user-status-toggle', function() {
                var toggle = $(this);
                var userId = toggle.data('user-id');
                var isChecked = toggle.is(':checked');
                var newStatus = isChecked ? 'Active' : 'Inactive';
                var previousStatus = toggle.attr('data-current-status') || (isChecked ? 'Inactive' : 'Active');
                var previousChecked = !isChecked;

                swal({
                    title: "Are you sure?",
                    text: "Do you want to change this user status to " + newStatus + "?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#34c38f",
                    confirmButtonText: "Yes, change it!",
                    cancelButtonText: "Cancel",
                    closeOnConfirm: false
                }, function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajax_update.php',
                            dataType: 'json',
                            data: {
                                action: 'update_user_status',
                                user_id: userId,
                                status: newStatus
                            },
                            success: function(response) {
                                if (response && response.success) {
                                    var updatedStatus = response.status || newStatus;
                                    toggle.attr('data-current-status', updatedStatus);
                                    var userRow = toggle.closest('tr');
                                    if (updatedStatus === 'Active') {
                                        toggle.prop('checked', true);
                                        userRow.removeClass('inactive-user-row');
                                    } else {
                                        toggle.prop('checked', false);
                                        userRow.addClass('inactive-user-row');
                                    }
                                    swal("Updated!", "User status changed to " + updatedStatus + ".", "success");
                                } else {
                                    toggle.prop('checked', previousChecked);
                                    swal("Error!", (response && response.message) ? response.message : "Unable to update user status.", "error");
                                }
                            },
                            error: function() {
                                toggle.prop('checked', previousChecked);
                                swal("Error!", "Server error occurred.", "error");
                            }
                        });
                    } else {
                        toggle.prop('checked', previousChecked);
                    }
                });
            });

</script>