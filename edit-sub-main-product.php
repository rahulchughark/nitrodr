<?php
include('includes/header.php');
admin_page();

$_GET['id'] = intval($_GET['id']);

if ($_GET['id'] != '') {
    $data = db_query("select * from tbl_product_opportunity where id='" . $_GET['id'] . "'");
    $user_data = db_fetch_array($data);
}


$_POST['name'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($_POST['name']));
$_POST['status'] = intval($_POST['status']);


if (isset($_POST['update_btn'])) {


   

    $_POST['desc']   = $_POST['desc'] ? $_POST['desc'] : 'NULL';

    $product_name     = $_POST['product_name'];
    $status           = $_POST['status'];
    $main_product_id  = $_POST['main_product_id'];
    $list_price       = $_POST['list_price'];
    $product_code     = $_POST['product_code'];
    $sac_code         = $_POST['sac_code'];
    $product_family   = $_POST['product_family'];
    $description      = $_POST['description'];
    $is_fixed         = $_POST['is_fixed_select'] == 1 ? $_POST['is_fixed'] : 0;
    $show_partner     = $_POST['show_partner'];
    $products_group   = $_POST['products_group'];
    $tax              = $_POST['tax'];

    // $res = db_query("UPDATE `tbl_product_opportunity` set `name`='" . $_POST['name'] . "', `status`='" . $_POST['status'] . "' where id=" . $_GET['id']);

 $query = db_query("UPDATE `tbl_product_opportunity` SET
              `product_name` = '$product_name',
              `main_product_id` = '$main_product_id',
              `list_price` = '$list_price',
              `product_code` = '$product_code',
              `sac_code` = '$sac_code',
              `product_family` = '$product_family',
              `description` = '$description',
              `status` = '$status',
              `is_fixed` = '$is_fixed',
              `show_partner` = '$show_partner',
              `products_group` = '$products_group',
              `tax` = '$tax'
               WHERE id = $id");

    //addProducts('tbl_product_opportunity',$_POST['name'],$_POST['desc'],$_POST['status'],now());

    if ($query) {        
         redir("sub-main-product.php?update=success", true);
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

            <!-- Product Name -->
            <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Sub Product Name<span class="text-danger">*</span></label>
                    <div class="col-md-9 controls">
                        <input type="text" name="product_name" required class="form-control" placeholder="Product Name" value="<?= $user_data['product_name'] ?>">
                    </div>
                </div>
            </div>

            <!-- Main Product ID -->
            <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Main Product ID</label>
                    <div class="col-md-9 controls">
                             <?php
                             $main_products = getMainProduct();                             
                             ?>
                         <select name="main_product_id" class="form-control" id="mainProductSelect" class="form-control" 
                        onchange="loadSubProducts(this.value)">
                            <option value="">---Select---</option>
                            <?php
                            foreach ($main_products as $main): ?>
                                <option 
                                 <?= ($user_data['main_product_id'] == $main['id']) ? 'selected' : '' ?>
                                value="<?= $main['id'] ?>">
                                    <?= htmlspecialchars(ucwords($main['name'])) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>


            <?php
            $classAdd = $user_data['products_group'] == null ? 'd-none' : '';
             ?>

            <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Link Product Group<span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <select name="product_group_status" id="product_group_status" class="form-control" 
                        id="isFixedSelect">
                            <!-- <option value="">---Select---</option> -->
                            <option <?php echo !$classAdd ? 'selected' : ''  ?>  value="1">Yes</option>
                            <option <?php echo $classAdd ? 'selected' : ''  ?> value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>



            <!-- Products Group -->

            

            <div class="col-lg-6 mb-3 <?= $classAdd ?>" id="product-group-container">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Products Group 
                    </label>
                    <div class="col-md-9 controls">
                        <!-- <input type="number" name="products_group" class="form-control" placeholder="Products Group" value="<?= $user_data['products_group'] ?>"> -->

                        <?php
                          // $productList = getAllSubMainProduct($_GET['id'],true);                                        
                        ?>

                        <select class="form-control" id="subProductSelect" name="products_group">
                            <option>Select Product</option>
                           <!--  <?php while ($value = db_fetch_array($productList)) { ?>
                              
                                <option <?= ($user_data['products_group'] == $value['id']) ? 'selected' : '' ?>
                                        value="<?= $value['id'] ?>"><?= $value['product_name'] ?></option>
                            <?php } ?>  -->                           
                        </select>


                    </div>
                </div>
            </div>



            <!-- List Price -->
            <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">List Price</label>
                    <div class="col-md-9 controls">
                        <input type="number" name="list_price" class="form-control" placeholder="List Price" 
                        value="<?= $user_data['list_price'] ?>">
                    </div>
                </div>
            </div>


            <div class="col-lg-6 mb-3">               
             <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Tax (%)</label>
                    <div class="col-md-9 controls">
                        <input type="number" name="tax" class="form-control" value="<?= $user_data['tax'] ?>" placeholder="List Price">
                    </div>
                </div>
            </div>

            <!-- Product Code -->
            <!-- <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Product Code</label>
                    <div class="col-md-9 controls">
                        <input type="number" name="product_code" class="form-control" placeholder="Product Code" value="<?= $user_data['product_code'] ?>">
                    </div>
                </div>
            </div> -->

            <!-- SAC Code -->
            <!-- <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">SAC Code</label>
                    <div class="col-md-9 controls">
                        <input type="number" name="sac_code" class="form-control" placeholder="SAC Code" value="<?= $user_data['sac_code'] ?>">
                    </div>
                </div>
            </div> -->

            <!-- Product Family -->
          <!--   <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Product Family</label>
                    <div class="col-md-9 controls">
                        <input type="text" name="product_family" class="form-control" placeholder="Product Family" value="<?= $user_data['product_family'] ?>">
                    </div>
                </div>
            </div> -->

            <!-- Description -->
           <!--  <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Description</label>
                    <div class="col-md-9 controls">
                        <textarea name="description" class="form-control" placeholder="Description"><?= $user_data['description'] ?></textarea>
                    </div>
                </div>
            </div> -->

            <!-- Status -->
            <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Status<span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <select name="status" required class="form-control">
                            <option value="" disabled>---Select---</option>
                            <option <?= ($user_data['status'] == '1') ? 'selected' : '' ?> value="1">Active</option>
                            <option <?= ($user_data['status'] == '0') ? 'selected' : '' ?> value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Is Fixed -->
           <!--  <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Is Fixed</label>
                    <div class="col-md-9">
                        <select name="is_fixed" class="form-control">
                            <option value="" disabled>---Select---</option>
                            <option <?= ($user_data['is_fixed'] == '1') ? 'selected' : '' ?> value="1">Yes</option>
                            <option <?= ($user_data['is_fixed'] == '0') ? 'selected' : '' ?> value="0">No</option>
                        </select>
                    </div>
                </div>
            </div> -->

             <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Is Quantity Fixed</label>
                    <div class="col-md-9">
                        <select name="is_fixed_select" class="form-control" 
                        id="isFixedSelect" onchange="toggleFixedQuantity()">
                            <option value="">---Select---</option>
                            <option <?= ($user_data['is_fixed'] > 0) ? 'selected' : '' ?> value="1">Yes</option>
                            <option <?= (!$user_data['is_fixed']) ? 'selected' : '' ?> value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>


          <div class="col-lg-6 mb-3" id="fixedQuantityField">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Quantity</label>
                    <div class="col-md-9 controls">
                        <input type="number" name="is_fixed" class="form-control" value="<?= $user_data['is_fixed'] ?>" placeholder="Quantity..">
                    </div>
                </div>
          </div>

            <!-- Show Partner -->
            <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Show Partner</label>
                    <div class="col-md-9">
                        <select name="show_partner" class="form-control">
                            <option value="" disabled>---Select---</option>
                            <option <?= ($user_data['show_partner'] == '1') ? 'selected' : '' ?> value="1">Yes</option>
                            <option <?= ($user_data['show_partner'] == '0') ? 'selected' : '' ?> value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>

            

        </div>
    </div>

    <div class="button-items text-center">
        <button type="submit" class="btn btn-primary mt-2" name="update_btn">Update</button>
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



       loadSubProducts(<?= $user_data['main_product_id'] ?>,<?= $user_data['products_group'] ?>,<?= $_GET['id'] ?>);

        </script>


