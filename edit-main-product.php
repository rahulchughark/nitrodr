<?php
include('includes/header.php');
admin_page();

$_GET['id'] = intval($_GET['id']);

if ($_GET['id'] != '') {
    $data = db_query("select * from tbl_main_product_opportunity where id='" . $_GET['id'] . "'");
    $user_data = db_fetch_array($data);
}


$_POST['name'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($_POST['name']));
$_POST['status'] = intval($_POST['status']);


if (isset($_POST['update_btn'])) {

    $_POST['desc']   = $_POST['desc'] ? $_POST['desc'] : 'NULL';

    $res = db_query("UPDATE `tbl_main_product_opportunity` set `name`='" . $_POST['name'] . "', `status`='" . $_POST['status'] . "' where id=" . $_GET['id']);

    //addProducts('tbl_main_product_opportunity',$_POST['name'],$_POST['desc'],$_POST['status'],now());

    if ($res) {        
         redir("main-product.php?update=success", true);
    }
}



?>
<style>
    .add_lead {
        height: calc(100vh - 270px);
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
                                    <small class="text-muted">Home > Edit Product</small>
                                    <h4 class="font-size-14 m-0 mt-1">Edit Product</h4>
                                </div>
                            </div>

                            <div class="add_lead_form">
                                <form method="post" action="#" class="form-horizontal" novalidate enctype="multipart/form-data">
                                    <div data-simplebar class="add_lead">
                                        <div class="row">
                                            <div class="col-lg-6 mb-3">
                                                <div class="form-group row">
                                                    <label class="control-label text-md-right col-md-3">Product Name<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                        <input type="text" name="name" required data-validation-required-message="This field is required" class="form-control" placeholder="Product Name" data-validation-required-message="This field is required" value="<?= $user_data['name'] ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 mb-3">
                                                <div class="form-group row">
                                                    <label class="control-label text-md-right col-md-3">Status<span class="text-danger">*</span></label>
                                                    <div class="col-md-9">
                                                        <select name="status" required class="form-control" data-validation-required-message="This field is required">
                                                            <option value="" disabled>---Select---</option>
                                                            <option <?= (($user_data['status'] == '1') ? 'selected' : '') ?> value=1>Active</option>
                                                            <option <?= (($user_data['status'] == '0') ? 'selected' : '') ?> value=0>InActive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!--/span-->
                                        </div>
                                        <!--/row-->

                                    </div>
                                    <div class="button-items text-center">
                                        <button type="submit" class="btn btn-primary  mt-2" name="update_btn">Update</button>
                                        <button type="button" onclick="javascript:history.go(-1)" class="btn btn-danger mt-2">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('includes/footer.php') ?>
        <script>
            $(document).ready(function(){
                var wfheight = $(window).height();
                $('.add_lead_form').height(wfheight-207);
            })
        </script>