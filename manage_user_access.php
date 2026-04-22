<?php
include('includes/header.php');
admin_page();

if ($_POST['role_edit']) {
    $_POST['pid'] = intval($_POST['pid']);
    $_POST['chk'] = @filter_var_array($_POST['chk'],FILTER_SANITIZE_STRING);
    // print_r($_POST);die;
    $delete_query = delete_user_roles($_POST['pid']);
    if($_POST['chk']){  

        foreach($_POST['chk'] as $chk){       
            $query =  insert_user_roles($chk,$_POST['pid']);

        }
        redir("manage_user_access.php?update=success", true);             
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

                                    <small class="text-muted">Home >User Access</small>
                                    <h4 class="font-size-14 m-b-14 mt-1">User Access</h4>
                                </div>
                            </div>

                        <?php if ($_GET['add'] == 'success') { ?>
                            <div class="alert alert-success text-center">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> User Access Added Successfully!</h3> 
                            </div>
                        <?php } ?>

                        <?php if ($_GET['update'] == 'success') { ?>
                            <div class="alert alert-success text-center">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> User Access Updated Successfully!</h3> 
                            </div>
                        <?php } ?>
                        <?php if ($_GET['email'] == 'fail') { ?>
                            <div class="alert alert-warning text-center">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                <h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i> Warning! User with this email already exists!</h3> 
                            </div>
                        <?php } ?>

                        <div class="table-responsive">
                            <table id="example23" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th data-sortable="true">User Id</th>
                                        <th data-sortable="true">Name</th>
                                        <th data-sortable="true">Profile</th>
                                        <th data-sortable="true">User Role</th>
                                        <th data-sortable="true">User Type</th>
                                        <th data-sortable="true">Current Access</th>
                                        <th data-sortable="true">Manage Access</th>


                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $userList = get_user_access_details();
                                    foreach ($userList as $value) {
                                        // print_r($value);                                   

                                        echo '
                                            <tr id="tr-id-1" class="tr-class-1">
                                                <td>' . $value['id'] . '
                                                <input type="hidden" value=' . $value['id'] . ' name="userId"/></td>
                                                <td>' . $value['name'] . '</td>
                                                <td>' . $value['profile_path'] . '</td>
                                                <td>' . $value['role_type'] . '</td>
                                                <td>' . $value['role'] . '</td>
                                                <td>' . $value['module_name'] . '</td>
                                                <td><a class="btn btn-primary btn-xs" href="javascript:void(0)" title="Change Access" onclick="change_access(' . $value['id'] . ')">Change Access</a></td>
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

    <div id="myModal1" class="modal align-items-center" role="dialog">


    </div>
    <?php include('includes/footer.php') ?>
    <script>

        $('#example23').DataTable({
            dom: 'Bfrtip',

            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
            ],
            lengthMenu: [
                [15, 25, 50, 100, 500, 1000],
                ['15', '25', '50', '100', '500', '1000']
            ],
            language: {
                paginate: {
                    previous: '<i class="fas fa-arrow-left"></i>',
                    next: '<i class="fas fa-arrow-right"></i>'
                }
            },
            "displayLength": 15,
        });

        function change_access(id) {
            //$('.preloader').show();
            //alert('abc');
            $.ajax({
                type: 'POST',
                url: 'change_user_access.php',
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