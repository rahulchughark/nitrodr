<?php
include('includes/header.php');
admin_page();
// ini_set('display_errors', 1);
// error_reporting(E_ALL);


if ($_POST['role_edit']) {
    $_POST['pid'] = intval($_POST['pid']);
    $_POST['chk'] = filter_var_array($_POST['chk'], FILTER_SANITIZE_STRING);
    if ($_POST['chk']) {
        $delete_query = delete_roles($_POST['pid']);

        foreach ($_POST['chk'] as $chk) {
            $query =  insert_roles($chk, $_POST['pid']);
        }
        redir("manage_role_access.php?update=success", true);
    }
}

if ($_POST['permission_edit']) {
    // echo "test"; die;
    $_POST['pid'] = intval($_POST['pid']);
    $_POST['edit_log'] = intval($_POST['edit_log']);
    $_POST['edit_lead'] = intval($_POST['edit_lead']);
    $_POST['edit_stage'] = intval($_POST['edit_stage']);
    $_POST['edit_date'] = intval($_POST['edit_date']);
    $_POST['edit_ownership'] = intval($_POST['edit_ownership']);
    $_POST['edit_status'] = intval($_POST['edit_status']);
    $_POST['edit_review_log'] = intval($_POST['edit_review_log']);
    $_POST['edit_product'] = intval($_POST['edit_product']);


    $select = getSingleresult("select role_id from tbl_role_permission where role_id=" . $_POST['pid']);
    if (!empty($select)) {
        $update_query = update_roles_permission($_POST['pid'], $_POST['edit_log'], $_POST['edit_lead'], $_POST['edit_stage'], $_POST['edit_date'], $_POST['edit_ownership'],$_POST['edit_status'],$_POST['edit_review_log'],$_POST['edit_product']);

        redir("manage_role_access.php?update=success", true);
    } else {
        $insert_query =insert_roles_permission($_POST['pid'], $_POST['edit_log'], $_POST['edit_lead'], $_POST['edit_stage'], $_POST['edit_date'], $_POST['edit_ownership'],$_POST['edit_status'],$_POST['edit_review_log'],$_POST['edit_product']);
        //"insert into tbl_role_permission(role_id,edit_log,edit_lead,edit_stage,edit_date,edit_ownership,edit_status,edit_review_log) values('".$_POST['pid']."','".$_POST['edit_log']."','".$_POST['edit_lead']."','".$_POST['edit_stage']."','".$_POST['edit_date']."','".$_POST['edit_ownership']."','".$_POST['edit_status']."','".$_POST['edit_review_log']."')";
     
       //print_r($insert_query);die;
        redir("manage_role_access.php?update=success", true);
    }
}
?>
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
                            <div class="media bredcrum-title">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Role Access</small>
                                    <h4 class="font-size-14 m-b-14 mt-1">Role Access</h4>
                                </div>
                            </div>
                        <?php if ($_GET['add'] == 'success') { ?>
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> User Added Successfully!
                            </div>
                        <?php } ?>

                        <?php if ($_GET['update'] == 'success') { ?>
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Role Updated Successfully!
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
                                        <th data-sortable="true" style="width: 220px">User Role</th>
                                        <th data-sortable="true">Module Access</th>
                                        <th data-sortable="true">Change Access</th>
                                        <th data-sortable="true">Change Permission</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $roleList = get_access_details();
                                    foreach ($roleList as $value) {
                                        // print_r($value);                                   

                                        echo '
                                            <tr id="tr-id-1" class="tr-class-1">
                                                <td>' . $value['role'] . '
                                                <input type="hidden" value=' . $value['id'] . ' name="roleId"/></td>
                                                <td>' . ($value['module_name']) . '</td>
                                                <td><a href="javascript:void(0)" class="btn btn-primary btn-xs px-2 text-nowrap" title="Change Access" onclick="change_access(' . $value['id'] . ')"><i style="font-size:16px" class="mdi mdi-pencil"></i> Change Access</a></td>
                                                <td><a href="javascript:void(0)" class="btn btn-primary btn-xs px-2 text-nowrap" title="Change Permission" onclick="change_permission(' . $value['id'] . ')"><i style="font-size:16px" class="mdi mdi-pencil"></i> Change Permission</a></td>
                                            </tr>';
                                    }

                                    ?>


                                </tbody>
                            </table>
                            </div>

                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

    <div id="myModal1" class="modal custom-modal" role="dialog">


    </div>

    <?php include('includes/footer.php') ?>
    <script>

        $('#example23').DataTable({
            dom: 'Bfrtip',
            language: {
                paginate: {
                    previous: '<i class="fas fa-arrow-left"></i>',
                    next: '<i class="fas fa-arrow-right"></i>'
                }
            },
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
            ],
            lengthMenu: [
                [15, 25, 50, 100, 500, 1000],
                ['15', '25', '50', '100', '500', '1000']
            ],
            "displayLength": 15,
        });

        function change_access(id) {
            //$('.preloader').show();
            //alert('abc');
            $.ajax({
                type: 'POST',
                url: 'change_role_access.php',
                data: {
                    pid: id
                },
                success: function(response) {
                    $("#myModal1").html();
                    $("#myModal1").html(response);

                    $('#myModal1').modal('show');
                    $('.preloader').hide();
                }
            });
        }

        function change_permission(id) {
            //alert('abc');
            $.ajax({
                type: 'POST',
                url: 'change_role_permission.php',
                data: {
                    pid: id
                },
                success: function(response) {
                    $("#myModal1").html();
                    $("#myModal1").html(response);

                    $('#myModal1').modal('show');
                    $('.preloader').hide();
                }
            });
        }
    </script>
    <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 325);
                $("#example23").tableHeadFixer();

            });
    </script>