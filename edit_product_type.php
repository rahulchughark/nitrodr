<?php
include('includes/header.php');
admin_page();

$_GET['id'] = intval($_GET['id']);

if ($_GET['id'] != '') {
    $data = db_query("select tp.*,p.product_name,p.id as pid from tbl_product_pivot as tp left join tbl_product as p on tp.product_id=p.id where tp.id='" . $_GET['id'] . "'");
    $user_data = db_fetch_array($data);
}


$_POST['product_name'] = intval($_POST['product_name']);

$select_query = db_query("select * from tbl_product where id=" . $_POST['product_name']);
$select_res = db_fetch_array($select_query);

if (isset($_POST['update_btn'])) {

    $res =
        //updateProductType('tbl_product_pivot',$_POST['product_name'],$_POST['product_type'],$_POST['product_code'],$_POST['license_type'],$_POST['status'],now(),$_GET['id']);
        db_query("UPDATE `tbl_product_pivot` set `product_id`='" . $_POST['product_name'] . "', `product_type`='" . $_POST['product_type'] . "',`product_code`='" . $_POST['product_code'] . "',`license_type`='" . $_POST['license_type'] . "', `status`='" . $_POST['status'] . "', `updated_at`=now(),`form_id`='" . $select_res['form_id'] . "' where id=" . $_GET['id']);



    if ($res) {
        redir("manage_products_type.php?update=success", true);
    }
}


?>
<style>
    .add_lead {
        height: calc(100vh - 250px);
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
                            <div class="media ">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home > Edit Product Type</small>
                                    <h4 class="font-size-14 m-0 mt-1">Edit Product Type</h4>
                                </div>
                            </div>



                            <form method="post" action="#" class="form-horizontal" novalidate enctype="multipart/form-data">

                                <div data-simplebar class="add_lead">

                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-right col-md-3">Product Name<span class="text-danger">*</span></label>
                                                <div class="col-md-9 controls">
                                                    <select name="product_name" required class="form-control" placeholder="Product Name">
                                                        <option value="" disabled>---Select---</option>
                                                        <?php $res_product = db_query("select * from tbl_product where status=1");
                                                        while ($row = db_fetch_array($res_product)) { ?>
                                                            <option <?= (($user_data['pid'] == $row['id'] ? 'selected' : '')) ?> value=<?= $row['id']; ?>><?= $row['product_name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!--/span-->
                                    </div>
                                    <!--/row-->
                                    <div id="dynamic_field">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="start_date" name="product_type" placeholder="Product Type" value="<?= $user_data['product_type'] ?>" />
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <!--/row-->

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="end_date" name="product_code" placeholder="Product Code" value="<?= $user_data['product_code'] ?>" />
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select name="license_type" class="form-control" placeholder="License Type">
                                                        <option value=" ">Agreement Type</option>
                                                        <option <?= (($user_data['license_type']) == 'Fresh') ? 'selected' : '' ?> value='Fresh'>Fresh</option>
                                                        <option <?= (($user_data['license_type']) == 'Renewal') ? 'selected' : '' ?> value='Renewal'>Renewal</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select name="status" required class="form-control" placeholder="Status">
                                                        <option value="" disabled>Status</option>
                                                        <option <?= (($user_data['status']) == '1') ? 'selected' : '' ?> value='1'>Active</option>
                                                        <option <?= (($user_data['status']) == '0') ? 'selected' : '' ?> value='0'>InActive</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!--/span-->

                                    <!--/span-->
                                </div>
                                <div class="button-items text-center">
                                    <button type="submit" name="update_btn" class="btn btn-primary  mt-2">Update</button>
                                    <button type="button" onclick="javascript:history.go(-1)" class="btn btn-danger mt-2">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include('includes/footer.php') ?>