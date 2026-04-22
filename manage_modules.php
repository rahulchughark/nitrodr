<?php
include('includes/header.php');

admin_page();
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
                            <div class="row">
                                <div class="col">
                                    <div class="media bredcrum-title">
                                        <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">

                                            <small class="text-muted">Home >Manage Modules</small>
                                            <h4 class="font-size-14 m-0 mt-1">Manage Modules</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="add_modules.php"><button title="Add Module" class="right-side bottom-right waves-effect waves-light btn-light btn btn-circle btn-xs pull-right m-l-10"><i class="ti-plus "></i></button></a>
                                </div>
                            </div>

                            <?php if ($_GET['add'] == 'success') { ?>
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Module Added Successfully!
                            </div>
                        <?php } ?>

                        <?php if ($_GET['update'] == 'success') { ?>
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Module Updated Successfully!
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
                                        <th data-sortable="true">S.No.</th>
                                        <th data-sortable="true">Module Name</th>
                                        <th data-sortable="true">User Role</th>
                                        <th data-sortable="true">URL</th>
                                        <th data-sortable="true">Action</th>
                                      

                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $i =1;
                                    $categoryList = get_tree_mainModule();
                                    foreach ($categoryList as $value) { ?>
                                                                        

                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= str_replace(' ', '', str_replace('&nbsp;', '', $value['name']));?></td>
                                            <td><?= $value['user_type'] ?></td>
                                            <td><?= (($value['url'] != '#' && $value['url'] != 'javascript:void(0);') ?
                                                '<a href=' . $value['url'] . ' class="btn btn-primary btn-xs px-2 text-nowrap" title="View URL"><i class="mdi mdi-link" style="font-size: 16px"></i> View URL</a>' : ' ') ?></td>
                                            <td> <a class="btn btn-primary btn-xs px-2" href="edit_module.php?id=<?=$value['id']?>"><i style="font-size:16px" class="mdi mdi-pencil"></i></a></td>
                                        </tr>

                                        <!-- echo '
                                            <tr>
                                                <td>' . $value['setOrder'] . '</td>
                                                <td>' . (($value['icon'] != ' ') ? '<img src="' . $value['icon'] . '" style="width:50px; height:50px;background-color:black;";>' : ' ') . '</td>
                                                <td>' . $value['name'] . '</td>
                                                <td>' . $value['user_type'] . '</td>
                                                <td>' .
                                            (($value['parentId'] != 0) ?
                                                '<a href=' . $value['url'] . '>View URL</a>' : ' ')
                                            . '</td>
                                               
                                                <td><a href="edit_module.php?id=' . $value['id'] . '"><i style="font-size:18px" class="mdi mdi-pencil"></i></a></td>
                                                

                                            </tr>'; -->
                                  <?php  $i++; } 

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
        columnDefs: [{
            orderable: false,
            targets: 0
        }],
    });
</script>
<script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 310);
                $("#example23").tableHeadFixer();

            });
</script>