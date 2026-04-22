<?php include("includes/include.php"); ?>

<div class="modal-dialog modal-lg review_dr"></div>
<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">

        <h4 class="modal-title">Please add product for "<?= $_POST['company'] ?>-<?= $_POST['qty'] ?> User(s)"</h4>
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        </div>
        <form class="product_data">
            <div class="modal-body">
                <div class="col-md-12">
                   <label for="product_id" class="control-label">Product</label>
                </div>
                <div class="col-md-12">
                    <input type="hidden" id="edit_id" value="<?= $_POST['renewal_id'] ?>">
                 
                    <select name="product" class="form-control" id="product_id">
                        <option value="">---Select---</option>
                        <?php $res_product = db_query("select * from tbl_product where id =2");

                        while ($row = db_fetch_array($res_product)) { ?>
                            <option value=<?= $row['id']; ?>><?= $row['product_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div id="productType_id" class="mt-2">

                </div>

            </div>
        </form>
        <div class="modal-footer" style="display: none;">
            <button type="button" name="submit" id="submitBtn" class="btn btn-primary">Submit</button>
        </div>
    </div>

</div>

</div>

<script>
    $('#product_id').on('change', function() {

        var productID = $(this).val();
        //alert(productID);
        if (productID) {
            $.ajax({
                type: 'POST',
                url: 'ajaxProduct.php',
                data: 'product=' + productID,
                success: function(response) {
                    $('.modal-footer').show();
                    $('#productType_id').html(response);
                },
                error: function() {
                    $('#productType').html('There was an error!');
                }
            });
        } else {
            $('#productType').html('<option value="" style="color:red">Select product first</option>');
        }
    });

    $('#submitBtn').on('click', function() {
        //alert('clicked');
        if (($('#product_type').val() == " ") || ($('#product_type').val() == undefined)) {
            swal('Select product type!!');
            return true;
        }
        // var data = $('.product_data').serialize();
        var product = $('#product_id').val();
        var product_type = $('#product_type').val();
        var lead_id = $('#edit_id').val();

        $.ajax({
            type: 'post',
            url: 'ajaxProduct.php',
            data: {
                product_id: product,
                product_type_id: product_type,
                lead_id: lead_id
            },
            success: function(result) {
                if (result == 1) {
                    swal("Product Added successfully!");
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    swal("Can't Add Product!", "error");
                }
            }
        });

    });
</script>