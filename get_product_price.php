 
<?php
 include('includes/include.php');

if (!empty($_GET['product_id'])) {
    $list_price =   getSingleresult("SELECT list_price FROM tbl_product_opportunity WHERE id = '" . $_GET['product_id'] . "' and status = 1");
    echo $list_price;
}

if ($_POST['selectedProduct']) {
    $selectedpro = explode(',',$_POST['selectedProduct']);
    $a = 1;
    foreach ($selectedpro as $pro) {
        $checkParent = getSingleResult('SELECT products_group from tbl_product_opportunity where id='.$pro);
        if($checkParent){
            if (in_array($checkParent, $selectedpro)) {
    
            } else {
                $a = 0;
            }
        }        
    }
    echo $a;
    // print_r($checkParent);die;
}

if (isset($_POST['main_product_id'])) {
    $mainProductId = $_POST['main_product_id'];
    $productCodes = db_query("SELECT id, product_name, product_code FROM tbl_product_opportunity WHERE main_product_id = '$mainProductId'");
    
    echo '<label class="control-label">Product Code<span class="text-danger">*</span></label>
        <select name="product_code[]" class="form-control product-code" required><option value="">Select Product</option>';
    while ($row = db_fetch_array($productCodes)) {
        echo '<option value="'.$row['id'].'">'.$row['product_name'].' ('.$row['product_code'].')</option>';
    }
    echo '</select>';
}

if(isset($_POST['main_product_id'])){
?>
<script>
            $(document).ready(function() {
            $('.product-code').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Product',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn: true
            });
            });
</script>
<?php } ?>