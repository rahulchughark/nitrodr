<?php
include('includes/header.php');
admin_page();




if ($_POST['product_name']) {


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


            $res = db_query("INSERT INTO `tbl_product_opportunity` (
                `product_name`,
                `main_product_id`,
                `list_price`,
                `product_code`,
                `sac_code`,
                `product_family`,
                `description`,
                `status`,
                `is_fixed`,
                `show_partner`,
                `products_group`,
                `tax`
            ) VALUES (
                '$product_name',
                '$main_product_id',
                '$list_price',
                '$product_code',
                '$sac_code',
                '$product_family',
                '$description',
                '$status',
                '$is_fixed',
                '$show_partner',
                '$products_group',
                '$tax'
            )");



    

    if ($res) {
        redir("sub-main-product.php?add=success", true);
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
                                <form method="post" class="form-horizontal" novalidate enctype="multipart/form-data">
    <div data-simplebar class="add_lead">
        <div class="row">

            <!-- Product Name -->
            <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Sub Product Name<span class="text-danger">*</span></label>
                    <div class="col-md-9 controls">
                        <input type="text" name="product_name" required class="form-control" placeholder="Product Name">
                    </div>
                </div>
            </div>

            <!-- Main Product ID -->
            <!-- <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Main Product ID</label>
                    <div class="col-md-9 controls">
                        <?php $main_products = getMainProduct(); ?>
                        <select name="main_product_id" class="form-control">
                            <option value="">---Select---</option>
                            <?php foreach ($main_products as $main): ?>
                                <option value="<?= $main['id'] ?>"><?= htmlspecialchars(ucwords($main['name'])) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div> -->

            <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Main Product ID<span class="text-danger">*</span></label>
                    <div class="col-md-9 controls">
                        <?php $main_products = getMainProduct(); ?>
                        <select name="main_product_id" id="mainProductSelect" class="form-control" 
                        onchange="loadSubProducts(this.value)">
                            <option value="">---Select---</option>
                            <?php foreach ($main_products as $main): ?>
                                <option value="<?= $main['id'] ?>"><?= htmlspecialchars(ucwords($main['name'])) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

             <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Link Product Group<span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <select name="product_group_status" id="product_group_status" class="form-control" 
                        id="isFixedSelect">
                            <!-- <option value="">---Select---</option> -->
                            <option value="1">Yes</option>
                            <option selected value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Products Group -->
            <div class="col-lg-6 mb-3 d-none" id="product-group-container">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Products Group</label>
                    <div class="col-md-9 controls">
                        <!-- <input type="number" name="products_group" class="form-control" placeholder="Products Group"> -->

                        <?php
                          $productList = getAllSubMainProduct(0,true);                                        
                        ?>

                        <select class="form-control" id="subProductSelect" name="products_group">
                            <option>Select Product</option>
<!-- 
                            <?php while ($value = db_fetch_array($productList)) { ?>
                                <option value="<?= $value['id'] ?>"><?= $value['product_name'] ?></option>
                            <?php } ?>   -->                          
                        </select>
                    </div>
                </div>
            </div>

            <!-- List Price -->
            <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">List Price<span class="text-danger">*</span></label>
                    <div class="col-md-9 controls">
                        <input type="number" name="list_price" class="form-control" placeholder="List Price">
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-3">
               
             <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Tax (%)<span class="text-danger">*</span></label>
                    <div class="col-md-9 controls">
                        <input type="number" name="tax" class="form-control" placeholder="List Price">
                    </div>
                </div>
                </div>

            <!-- Product Code -->
           <!--  <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Product Code</label>
                    <div class="col-md-9 controls">
                        <input type="number" name="product_code" class="form-control" placeholder="Product Code">
                    </div>
                </div>
            </div> -->

            <!-- SAC Code -->
           <!--  <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">SAC Code</label>
                    <div class="col-md-9 controls">
                        <input type="number" name="sac_code" class="form-control" placeholder="SAC Code">
                    </div>
                </div>
            </div> -->

            <!-- Product Family -->
            <!-- <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Product Family</label>
                    <div class="col-md-9 controls">
                        <input type="text" name="product_family" class="form-control" placeholder="Product Family">
                    </div>
                </div>
            </div> -->

            <!-- Description -->
           <!--  <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Description</label>
                    <div class="col-md-9 controls">
                        <textarea name="description" class="form-control" placeholder="Description"></textarea>
                    </div>
                </div>
            </div> -->

            <!-- Status -->
            <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Status</label>
                    <div class="col-md-9">
                        <select name="status" required class="form-control">
                            <!-- <option value="">---Select---</option> -->
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Is Fixed -->
            <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Is Quantity Fixed<span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <select name="is_fixed_select" class="form-control" 
                        id="isFixedSelect" onchange="toggleFixedQuantity()">
                            <option value="">---Select---</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>


          <div class="col-lg-6 mb-3" id="fixedQuantityField">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Quantity</label>
                    <div class="col-md-9 controls">
                        <input type="number" name="is_fixed" class="form-control" placeholder="Quantity..">
                    </div>
                </div>
          </div>

            <!-- Show Partner -->
            <div class="col-lg-6 mb-3">
                <div class="form-group row">
                    <label class="control-label text-md-right col-md-3">Show Partner</label>
                    <div class="col-md-9">
                        <select name="show_partner" class="form-control">
                            <!-- <option value="">---Select---</option> -->
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>

            

        </div>
    </div>

    <div class="button-items text-center">
        <button type="submit" class="btn btn-success mt-2" name="add_btn">Add</button>
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


