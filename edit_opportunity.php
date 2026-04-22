<?php include("includes/include.php");

$_POST['pid'] = intval($_POST['pid']);
// if($_SESSION['role'] == 'PARTNER'){
//     $products = db_query("SELECT * FROM tbl_product_opportunity where show_partner=1 and status=1");
// }else{
//     $products = db_query("SELECT * FROM tbl_product_opportunity where status=1");
// }
// $mainProducts = db_query("SELECT * FROM tbl_main_product_opportunity where status=1");

$query = db_query("SELECT * FROM tbl_lead_product_opportunity where status=1 and lead_id =" . $_POST['pid']);
$num_rowsQ= mysqli_num_rows($query);
if($num_rowsQ == 0){
    $query = db_query("SELECT * FROM tbl_lead_product_opportunity where status=2 and lead_id =" . $_POST['pid']);
}
$grandTotalPrice=getSingleResult("SELECT grand_total_price FROM orders where id=".$_POST['pid']);
$product_remarks=getSingleResult("SELECT product_remarks FROM orders where id=".$_POST['pid']);
$i=0;
?>

<style>
    .modal-xl {
        max-width: 1350px;
    }

    .modal-xxl {
        max-width: 100%;
    }
</style>

<div class="modal-dialog modal-dialog-centered modal-xxl modal-dialog-scrollablel">
    <div class="modal-content" >
        <div class="modal-header">
            <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Edit Opportunity</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="opportunityForm" action="#" method="post" class="form p-t-20" enctype="multipart/form-data">
                <div class="row">
                    <div class="form-group align-items-endn w-100">
                    <?php while($data = db_fetch_array($query)){ 
                        $i++;
                        $mainProducts = db_query("SELECT * FROM tbl_main_product_opportunity where status=1");
                        $mainPro = getSingleResult("SELECT t.id from tbl_main_product_opportunity as t left join tbl_product_opportunity as tt on t.id=tt.main_product_id where tt.id=".$data['product']); 
                        $products = db_query("SELECT * FROM tbl_product_opportunity where main_product_id=".$mainPro);
                        $is_fixed = getSingleResult("SELECT is_fixed FROM tbl_product_opportunity where id=".$data['product']);

                        if($i == 1){
                        ?>                      
                        <div class="form-group row d-flex align-items-end mx-1">
                        <div class="col-md-2 mb-2">
                            <label class="control-label">Main Product<span class="text-danger">*</span></label>
                            <select name="main_product[]" id="mainProduct" class="form-control main-product" required  onchange="mainProductChange(this.value,1)">
                                <option value="">Select Main Product</option>
                                <?php
                                while ($mainRow = db_fetch_array($mainProducts)) { ?>
                                    <option <?= (($mainRow['id'] == $mainPro) ? 'selected' : '') ?> value="<?= $mainRow['id'] ?>"><?= $mainRow['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="col-md-2 mb-2"  id="productCode1">
                            <label class="control-label">Product Code<span class="text-danger">*</span></label>
                            <select name="product_code[]" class="form-control product-code" required>
                                <option value="">Select Product</option>
                               <?php while ($mainRow = db_fetch_array($products)) { ?>
                                    <option <?= (($mainRow['id'] == $data['product']) ? 'selected' : '') ?> value="<?= $mainRow['id'] ?>"><?= $mainRow['product_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                            <div class="col-md-1 mb-2">
                                <label class="control-label">Quantity<span class="text-danger">*</span></label>
                                <input name="quantity[]" type="number" min="0" class="form-control quantity" value="<?= $data['quantity'] ?>" required <?= $is_fixed!=0 ? 'readonly' : '' ?> />
                            </div>
                            <div class="col-md-3 mb-2 sales-price-container">
                                <label class="control-label">Sales Price<span class="text-danger">*</span></label>
                               <div class="row">
                                <div class="col">
                                    <input name="sales_price[]" type="number" class="form-control sales-price"  value="<?= $data['unit_price'] ?>" readonly />
                                </div>
                                <div class="input-group-append col-auto">
                                    <button type="button" class="btn btn-secondary toggle-sales-price">Negotiate</button>
                                </div>
                               </div>
                            </div>
                            <div class="col-md mb-2">
                                <label class="control-label">Total Price<span class="text-danger">*</span></label>
                                <input name="total_price[]" type="number" class="form-control total-price" value="<?= $data['total_price'] ?>" readonly />
                            </div>
                            <div class="col-md mb-2">
                                <label class="control-label">Is Upsell<span class="text-danger">*</span></label>
                                <select name="upsell[]" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="0" <?= $data['upsell']=='0' ? 'selected' : '' ?>>No</option>
                                    <option value="1" <?= $data['upsell']=='1' ? 'selected' : '' ?>>Yes</option>
                                </select>
                            </div>
                            <input name="original_price[]" type="hidden" class="form-control original-sales-price" value="<?= $data['original_sales_price'] ?>">
                            <div class="col-md-auto mb-2">
                            <button type="button" name="addProduct" id="addProduct" class="btn btn-primary text-nowrap mt-2">Add Product</button>
                            </div>
                        </div>
                         <?php }else{ ?>
                            <div id="row<?= $i ?>">
                                <div class="form-group row d-flex align-items-end mx-1">
                                    <div class="col-md-2 mb-2">
                                <label class="control-label">Main Product<span class="text-danger">*</span></label>
                                <select name="main_product[]" id="mainProduct" class="form-control main-product" required  onchange="mainProductChange(this.value,<?= $i ?>)">
                                    <option value="">Select Main Product</option>
                                    <?php
                                    while ($mainRow = db_fetch_array($mainProducts)) { ?>
                                        <option <?= (($mainRow['id'] == $mainPro) ? 'selected' : '') ?> value="<?= $mainRow['id'] ?>"><?= $mainRow['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-2 mb-2"  id="productCode<?= $i ?>">
                                <label class="control-label">Product Code<span class="text-danger">*</span></label>
                                <select name="product_code[]" class="form-control product-code" required>
                                <option value="">Select Product</option>
                               <?php while ($mainRow = db_fetch_array($products)) { ?>
                                    <option <?= (($mainRow['id'] == $data['product']) ? 'selected' : '') ?> value="<?= $mainRow['id'] ?>"><?= $mainRow['product_name']; ?></option>
                                <?php } ?>
                                </select>
                            </div>
                                    <div class="col-md-1 mb-2">
                                        <label class="control-label">Quantity<span class="text-danger">*</span></label>
                                        <input name="quantity[]" type="number" min="0" class="form-control quantity" value="<?= $data['quantity'] ?>" required <?= $is_fixed!=0 ? 'readonly' : '' ?> />
                                    </div>
                                    <div class="col-md-3 mb-2 sales-price-container">
                                        <label class="control-label">Sales Price<span class="text-danger">*</span></label>
                                        <div class="row">
                                            <div class="col">
                                                <input name="sales_price[]" type="number" class="form-control sales-price"  value="<?= $data['unit_price'] ?>" readonly />
                                            </div>
                                            <div class="col-auto input-group-append">
                                                <button type="button" class="btn btn-secondary toggle-sales-price">Negotiate</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md mb-2">
                                        <label class="control-label">Total Price<span class="text-danger">*</span></label>
                                        <input name="total_price[]" type="number" class="form-control total-price" value="<?= $data['total_price'] ?>" readonly />
                                    </div>
                                    <div class="col-md mb-2">
                                        <label class="control-label">Is Upsell<span class="text-danger">*</span></label>
                                        <select name="upsell[]" class="form-control" required>
                                            <option value="">Select</option>
                                            <option value="0" <?= $data['upsell']=='0' ? 'selected' : '' ?>>No</option>
                                            <option value="1" <?= $data['upsell']=='1' ? 'selected' : '' ?>>Yes</option>
                                        </select>
                                    </div>
                                    <input name="original_price[]" type="hidden" class="form-control original-sales-price" value="<?= $data['original_sales_price'] ?>">
                                    <div class="col-md-auto mb-2">
                                        <span data-repeater-delete="" name="remove" id="<?= $i ?>" class="btn btn-danger btn-sm btn_remove"><span class="fa fa-times mr-1"></span>Delete</span>
                                    </div>
                                </div>
                            </div>
                        <?php }  ?> 
                        <input id="hidden" name="financial_year_start" type="hidden" class="form-control" value="<?= $data['financial_year_start'] ?>"/> 
                        <input id="hidden" name="financial_year_end" type="hidden" class="form-control" value="<?= $data['financial_year_end'] ?>"/> 
                        <?php } ?>
                        <div id="dynamic_products"></div>
                        <div class="col-md-12 mb-2">
                            <label class="control-label">Grand Total (Excluding Tax.)<span class="text-danger">*</span></label>
                            <input id="grand_total" name="grand_total" type="number" class="form-control" value="<?= $grandTotalPrice ?>" readonly />
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="control-label">Product Remarks</label>
                            <textarea id="product_remarks" name="product_remarks" type="text" class="form-control"><?= $product_remarks ?></textarea>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="pid" value="<?= $_POST['pid'] ?>" />

                <div class="mt-3 text-center">
                <button type="button" onclick="submitForm()" class="btn btn-primary">Save</button>
                    <!-- <button type="submit" class="btn btn-primary" name="save">Save </button> -->
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php 
if($_SESSION['role'] == 'PARTNER'){
    $productss = db_query("SELECT * FROM tbl_product_opportunity where show_partner=1 and status=1");
}else{
    $productss = db_query("SELECT * FROM tbl_product_opportunity where status=1");
}
$productsValueOneQ = db_query("SELECT id,is_fixed FROM tbl_product_opportunity where status=1 and is_fixed!=0");

$mainProducts = db_query("SELECT * FROM tbl_main_product_opportunity where status=1");
while($pa = db_fetch_array($productsValueOneQ)){
    $productsValueOne[] = $pa['id'];
    $productsValueFixed[$pa['id']] = $pa['is_fixed'];
}
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        $(document).ready(function() {
        var i = <?= $i ?>;
        function initializeMultiselect() {
            $('.product-code').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                nonSelectedText: 'Select Product',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                includeFilterClearBtn: true
            });
        }

        // Initialize multiselect for the existing dropdown (if any)
        initializeMultiselect();
        $('#addProduct').click(function() {
            i++;
            $('#dynamic_products').append('<div id="row' + i + '">\
                <div class="form-group row d-flex align-items-end mx-1">\
                    <div class="col-md-2 mb-2">\
                <label class="control-label">Main Product<span class="text-danger">*</span></label>\
                <select name="main_product[]" class="form-control main-product" required onchange="mainProductChange(this.value,'+i+')">\
                    <option value="">Select Main Product</option>\
                    <?php while ($mainRow = db_fetch_array($mainProducts)) { ?>
                        <option value="<?= $mainRow['id']; ?>"><?= $mainRow['name']; ?></option>\
                    <?php } ?>
                </select>\
            </div>\
            <div class="col-md-2 mb-2"  id="productCode'+i+'">\
                <label class="control-label">Product Code<span class="text-danger">*</span></label>\
                <select name="product_code[]" class="form-control product-code" required>\
                    <option value="">Select Product</option>\
                </select>\
            </div>\
                    <div class="col-md-1 mb-2">\
                        <label class="control-label">Quantity<span class="text-danger">*</span></label>\
                        <input name="quantity[]" type="number" min="0" class="form-control quantity" placeholder="0" required>\
                    </div>\
                    <div class="col-md-3 mb-2 sales-price-container">\
                        <label class="control-label">Sales Price<span class="text-danger">*</span></label>\
                        <div class="row">\
                            <div class="col">\
                                <input name="sales_price[]" type="number" class="form-control sales-price" placeholder="0.00" readonly>\
                            </div>\
                            <div class="col-auto input-group-append">\
                                <button type="button" class="btn btn-secondary toggle-sales-price" style="display: none;">Negotiate</button>\
                            </div>\
                        </div>\
                    </div>\
                    <div class="col-md mb-2">\
                    <label class="control-label">Total Price<span class="text-danger">*</span></label>\
                    <input name="total_price[]" type="number" class="form-control total-price" placeholder="0.00" readonly>\
                    </div>\
                    <div class="col-md mb-2">\
                        <label class="control-label">Is Upsell<span class="text-danger">*</span></label>\
                                <select name="upsell[]" class="form-control" required>\
                                    <option value="">Select</option>\
                                    <option value="0">No</option>\
                                    <option value="1">Yes</option>\
                                </select>\
                    </div>\
                    <input name="original_price[]" type="hidden" class="form-control original-sales-price">\
                    <div class="col-md-auto mb-2">\
                        <span data-repeater-delete="" name="remove" id="' + i + '" class="btn btn-danger btn-sm btn_remove"><span class="fa fa-times mr-1"></span>Delete</span>\
                    </div>\
                </div>\
            </div>');
            initializeMultiselect();
        });

        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $('#row' + button_id).remove();
            calculateGrandTotal();
        });

        $(document).on('change', '.product-code', function() {
            var productsValueOne = <?php echo json_encode(array_map('intval', $productsValueOne)); ?>;
            var productsValueFixed = <?php echo json_encode($productsValueFixed); ?>;
            // alert(productsValueFixed)
            var row = $(this).closest('.form-group.row');
            var productId = parseInt($(this).val());
            var salesPriceInput = row.find('.sales-price');
            var totalPriceInput = row.find('.total-price');
            var quantityInput = row.find('.quantity');
            var originalsalesPriceInput = row.find('.original-sales-price');
            var negotiateButton = row.find('.toggle-sales-price');
            $.ajax({
                url: 'get_product_price.php',
                method: 'GET',
                data: { product_id: productId },
                success: function(response) {
                    var salesPrice = parseFloat(response);
                    salesPriceInput.val(salesPrice);
                    originalsalesPriceInput.val(salesPrice);
                    if (productsValueOne.includes(productId)) {
                        var productValuee = productsValueFixed[productId];
                        quantityInput.val(productValuee).prop('readonly', true);
                        var priceFixed = 1;
                    } else {
                        quantityInput.prop('readonly', false);
                        var priceFixed = 0;
                    }
                    negotiateButton.show();
                    var quantity = priceFixed == 1 ? 1 : (parseInt(quantityInput.val()) || 0);
                    // var quantity = productId==3 ? 1 : (parseInt(quantityInput.val()) || 0);
                    totalPriceInput.val(salesPrice * quantity);
                    calculateGrandTotal();
                }
            });
        });

        $(document).on('input', '.quantity', function() {
            var row = $(this).closest('.form-group.row');
            var quantity = parseInt($(this).val()) || 0;
            var salesPrice = parseFloat(row.find('.sales-price').val()) || 0;
            var totalPriceInput = row.find('.total-price');
            totalPriceInput.val(salesPrice * quantity);
            calculateGrandTotal();
        });

        $(document).on('input', '.sales-price', function() {
            var row = $(this).closest('.form-group.row');
            var product = Number(row.find('.product-code').val());
            var productsValueOne = <?php echo json_encode(array_map('intval', $productsValueOne)); ?>;
            if (productsValueOne.includes(product)) {
                var quantity = 1;
            } else {
                var quantity = parseInt(row.find('.quantity').val()) || 0;
            }
            var salesPrice = parseFloat(row.find('.sales-price').val()) || 0;
            var totalPriceInput = row.find('.total-price');
            totalPriceInput.val(salesPrice * quantity);
            calculateGrandTotal();
        });

        function calculateGrandTotal() {
            var grandTotal = 0;
            $('.total-price').each(function() {
                grandTotal += Math.round($(this).val()) || 0;
            });
            $('#grand_total').val(grandTotal);
        }
    });
         function onChangeProduct(productID){
              if (productID) {
                  $.ajax({
                      type: 'POST',
                      url: 'ajaxProduct.php',
                      data: 'product=' + productID,
                      success: function(response) {
                          // $('.modal-footer').show();
                          $('#productTypee').html(response);
                      },
                      error: function() {
                          $('#productTypee').html('There was an error!');
                      }
                  });
              } else {
                  $('#productTypee').html('<option value="" style="color:red">Select product first</option>');
              }
        }

            function updateStudentSum() {
            var sum = 0;

            $('.student-input').each(function() {
                var value = parseInt($(this).val()) || 0;
                sum += value;
            });
            document.getElementById("quantity").setAttribute('value',sum);
            // alert(sum);
            }

            updateStudentSum();

            $('#dynamic_quanity').on('input', '.student-input', updateStudentSum);

            $('#dynamic_quanity').on('click', '.btn_removeQ', function() {
                var deletedValue = parseInt($(this).closest('.form-group').find('.student-input').val()) || 0;
                // var currentSum = parseInt($('.total-students').text()) || 0;
                currentSum = document.getElementById("quantity").value;
                var newSum = currentSum - deletedValue;
                document.getElementById("quantity").setAttribute('value',newSum);
            });

            function changeValue(e)
            {
                document.getElementById("quantity").setAttribute('value',e);
            }

    function submitForm() {
        let form = document.getElementById('opportunityForm');
        let requiredFields = form.querySelectorAll('[required]');
        let missingFields = [];

        requiredFields.forEach(field => {
            if (!field.value) {
                missingFields.push(field.name || field.placeholder || field.labels[0].innerText);
            }
        });

        if (missingFields.length > 0) {
            swal.fire("Please fill out all required fields.");
        }else{
                    var productCodes = [];
            $('select[name="product_code[]"]').each(function() {
                var selectedValueA = $(this).val();
                if (selectedValueA) {
                    productCodes.push(selectedValueA);
                }
            });
            $.ajax({
                type: 'POST',
                url: 'get_product_price.php',
                // data: 'selectedProduct=' + productCodes,  
                data: {
                    selectedProduct: productCodes,
                    _ajax: 1 //  CUSTOM KEY
                },                
                success: function(response) {
                    if(response == 0){
                        swal.fire('Please select platform product of desired products.')
                    }else{
                        event.preventDefault();
                        const form = document.getElementById("opportunityForm");
                        const formData = new FormData(form);
                        formData.append('_ajax', '1');
                        fetch("update_opportunity.php", {
                            method: "POST",
                            body: formData,
                        })
                        .then(response => response.json())
                        .then(data => {
                            // alert(data)
                            if (data.success) {
                                Swal.fire({
                                    title: "Saved as opportunity!",
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // window.location.href = "manage_opportunity.php";
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire("Error", "There was an error: " + data?.message, "error");
                            }
                        });
                    }
                },
                error: function (xhr) {   
                    Swal.fire("Error", "Something went wrong.", "error");                 

                    // let message = "Something went wrong.";

                    // // Try to parse JSON response
                    // if (xhr.responseJSON && xhr.responseJSON.message) {
                    //     message = xhr.responseJSON.message;
                    // } else if (xhr.responseText) {
                    //     try {
                    //         let res = JSON.parse(xhr.responseText);
                    //         message = res.message || message;
                    //     } catch (e) {}
                    // }

                    // Swal.fire("Error", message, "error");

                    // // If session expired → redirect
                    // if (xhr.status === 401) {
                    //     setTimeout(() => {
                    //         window.location.href = "index.php";
                    //     }, 1500);
                    // }
                    // if (xhr.status === 401) {

                    //     let res = {};
                    //     try {
                    //         res = JSON.parse(xhr.responseText);
                    //     } catch (e) {}

                    //     Swal.fire({
                    //         icon: "error",
                    //         title: "Session Expired",
                    //         text: res.message || "Your session has expired. Please login again."
                    //     }).then(() => {
                    //         window.location.href = "index.php"; // logout / redirect
                    //     });
                    //     // alert(res.message || "Your session has expired. Please login again.");
                    //     // window.location.href = "index.php";

                    // } else {
                    //     Swal.fire("Error", "Something went wrong.", "error");
                    // }



                }
            }); 
        }
    }


</script>
