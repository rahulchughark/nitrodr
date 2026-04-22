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
                                <div class="col-sm">
                                    <div class="media ">
                                        <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                        <div class="media-body">

                                            <small class="text-muted">Home >Manage Product Type</small>
                                            <h4 class="font-size-14 m-0 mt-1">Manage Product Type</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto d-flex" role="group">
                                    <select class="form-control mr-3" onchange="location=this.value;">
                                        <option value="">Manage Forms</option>
                                        <option value="manage_products.php">Manage Products</option>
                                        <option value="manage_products_type.php">Manage Product Type</option>
                                    </select>
    
                                    <select class="form-control" onchange="location = this.value;">
                                        <option value="">Select Form</option>
                                        <option value="add_products.php">Add Product</option>
                                        <option value="add_product_type.php">Add Product Type</option>
    
                                    </select>
                                </div>
                            </div>


                            <?php if ($_GET['add'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> Product Type Added Successfully!
                                </div>
                            <?php } ?>

                            <?php if ($_GET['update'] == 'success') { ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Updated</h3> Product Type Updated Successfully!
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
                                            <th data-sortable="true">Product Name</th>
                                            <th data-sortable="true">Product Code</th>
                                            <th data-sortable="true">Product Type</th>
                                            <th data-sortable="true">License Type</th>
                                            <th data-sortable="true">Status</th>
                                            <th data-sortable="true">Action</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $productList = manageProductTypes('tbl_product_pivot');
                                        while ($value = db_fetch_array($productList)) {
                                            // print_r($value); 
                                        ?>

                                            <tr id="tr-id-1" class="tr-class-1">
                                                <td><?= $i ?></td>
                                                <td><?= ucwords($value['product_name']) ?></td>
                                                <td><?= ucwords($value['product_code']) ?></td>
                                                <td><?= ucwords($value['product_type']) ?></td>
                                                <td><?= ucwords($value['license_type']) ?></td>
                                                <td><?= (($value['status'] == 1) ? 'Active' : 'Inactive') ?></td>
                                                <td><a href="edit_product_type.php?id=<?= $value['id'] ?>"><i style="font-size:18px" class="mdi mdi-pencil"></i></a></td>
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
                $('.dataTables_wrapper').height(wfheight - 320);
                $("#example23").tableHeadFixer();

            });
        </script>