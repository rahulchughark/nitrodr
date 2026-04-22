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
                                <div class="col-12 col-md">
                                    <div class="media bredcrum-title">
                                        <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">
                                            <small class="text-muted">Home >Sub Main Products</small>
                                            <h4 class="font-size-14 m-0 mt-1">Sub Main Products</h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <a href="add-sub-main-product.php"><button title="Add Tag" class="btn btn-xs btn-light ml-1 waves-effect waves-light"><i class="ti-plus"></i></button></a>
                                </div>
                                
                            </div>

                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success mt-2">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; right: 20px;"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Product Added Successfully!
                                </div>
                            <?php } ?>

                            <?php if ($_GET['update'] == 'success') { ?>
                                <div class="alert alert-success mt-2">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; right: 20px;"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Product Updated Successfully!
                                </div>
                            <?php } ?>
                            <?php if ($_GET['email'] == 'fail') { ?>
                                <div class="alert alert-warning mt-2">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; right: 20px;"> <span aria-hidden="true">&times;</span> </button>
                                    <h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i> Warning!</h3> User with this email already exists!
                                </div>
                            <?php } ?>

                            <div class="table-responsive">
                                <table id="example23" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th data-sortable="true">S.No.</th>
                                            <th data-sortable="true">Product</th>
                                            <th data-sortable="true">Sub Product</th>
                                            <th data-sortable="true">Price</th>
                                            <!-- <th data-sortable="true">Code</th> -->
                                            <!-- <th data-sortable="true">SAC Code</th> -->
                                            <th data-sortable="true">Status</th>
                                            <th data-sortable="true">Action</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $productList = getAllSubMainProduct();

                                        while ($value = db_fetch_array($productList)) {
                                            // echo "<pre>";
                                            // print_r($value); 
                                            // exit;
                                        ?>
                                              <tr id="tr-id-1" class="tr-class-1">
                                                <td><?= $i ?></td>
                                                <td><?= ucwords($value['main_product_name']) ?></td>
                                                <td><?= ucwords($value['product_name']) ?></td>
                                                <td><?= ucwords($value['list_price']) ?></td>
                                                <!-- <td><?= ucwords($value['product_code']) ?></td> -->
                                                <!-- <td><?= ucwords($value['sac_code']) ?></td> -->
                                                <td><?= (($value['status'] == 1) ? 'Active' : 'Inactive') ?></td>
                                                <td><a class="btn btn-primary btn-xs px-2" href="edit-sub-main-product.php?id=<?= $value['id'] ?>"><i style="font-size:16px" class="mdi mdi-pencil"></i></a></td>
                                              </tr>
                                        <?php $i++;
                                        } ?>


                                    </tbody>
                                </table>
                            </div>

                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
            </div> <!-- container-fluid -->
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
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],
            });
        </script>
        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 317);
                $("#example23").tableHeadFixer();

            });
        </script>