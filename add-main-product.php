<?php
include('includes/header.php');
admin_page();

$_POST['name'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($_POST['name']));
$_POST['status'] = intval($_POST['status']);


if ($_POST['name']) {

    $res = db_query("INSERT INTO `tbl_main_product_opportunity`(`name`, `status`, `created_at`) 
        VALUES ('" . $_POST['name'] . "' ,'" . $_POST['status'] . "' ,now())");

    //addProducts('tbl_product',$_POST['name'],$_POST['desc'],$_POST['status'],now());

    // $last_insert_id = mysqli_insert_id($GLOBALS['dbcon']);
    // $select_query = db_query("select * from tbl_main_product_opportunity where id=" . $last_insert_id);
    // $result = db_fetch_array($select_query);
    // if ($result['product_name'] == 'Parallel') {
    //     $res = db_query("update tbl_main_product_opportunity set form_id = 1 where id=" . $last_insert_id);
    // }

    if ($res) {
        redir("main-product.php?add=success", true);
    }

    
}



?>
<style>
    .add_lead {
        height: calc(100vh - 280px);
    }
</style>
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

                                    <small class="text-muted">Home > Add Main Product</small>
                                    <h4 class="font-size-14 m-0 mt-1">Add Main Product</h4>
                                </div>
                            </div>


                            <form method="post" action="#" class="form-horizontal"  enctype="multipart/form-data">
                                <div data-simplebar class="add_lead">

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-right col-md-3">Product Main Name<span class="text-danger">*</span></label>
                                                <div class="col-md-9 controls">
                                                    <input type="text" name="name" required data-validation-required-message="This field is required" class="form-control" placeholder="Product Main Name" data-validation-required-message="This field is required">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-right col-md-3">Status<span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <select name="status" required class="form-control" data-validation-required-message="This field is required">
                                                        <option value="" disabled>---Select---</option>
                                                        <option <?= (($status == 'Active') ? 'selected' : '') ?> value=1>Active</option>
                                                        <option <?= (($status == 'InActive') ? 'selected' : '') ?> value=0>InActive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/row-->

                                </div>

                                <div class="button-items text-center">
                                    <button type="submit" class="btn btn-primary  mt-2">Submit</button>
                                    <button type="button" onclick="javascript:history.go(-1)" class="btn btn-danger mt-2">Cancel</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('includes/footer.php') ?>