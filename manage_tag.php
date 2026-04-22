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
                                    <div class="media ">
                                        <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">

                                            <small class="text-muted">Home > Manage Tags</small>
                                            <h4 class="font-size-14 m-0 mt-1">Manage Tags</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="add_tag.php"><button title="Add Tag" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-plus"></i></button></a>
                                </div>
                            </div>
<div class="clearfix"></div>
                            <?php if ($_GET['add'] == 'success') { ?>
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Tag Added Successfully!
                            </div>
                        <?php } ?>

                        <?php if ($_GET['update'] == 'success') { ?>
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Tag Updated Successfully!
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
                                        <th data-sortable="true">S No.</th>
                                        <th data-sortable="true">Tag Name</th>
                                        <th data-sortable="true">Description</th>
                                        <th data-sortable="true">Product Name</th>
                                        <th data-sortable="true">Created By</th>
                                        <th data-sortable="true">Created Date</th>
                                        <th data-sortable="true">Is Visible</th>
                                        <th data-sortable="true">Action</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $i = 1;
                                    $tagList = get_tag_data();
                                    foreach ($tagList as $value) {
                                        // print_r($value);                                   

                                        echo '
                                            <tr>
                                                <td>' . $i . '</td>
                                                <td>' . $value['name'] . '</td>
                                                <td>' . $value['description'] . '</td>
                                                <td>' . $value['product_name'] . '</td>
                                                <td>' . $value['user'] . '</td>
                                                <td>' . date('d F Y',strtotime($value['created_at'])) . '</td>
                                                <td>'. (($value['status']==1)?'Yes':'No').'</td>
                                                <td><a class="btn btn-primary btn-xs px-2" href="edit_tag.php?id=' . $value['id'] . '"><i style="font-size:16px" class="mdi mdi-pencil"></i></a></td>
                                                

                                            </tr>';
                                            $i++;
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
            [15, 25, 50, 100, 500, 1000, 10000, 30000],
            ['15', '25', '50', '100', '500', '1000', '10000', '30000']
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