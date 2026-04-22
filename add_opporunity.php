<?php include("includes/include.php");

$_POST['pid'] = intval($_POST['pid']);
if($_SESSION['role'] == 'PARTNER'){
    $products = db_query("SELECT * FROM tbl_product_opportunity where show_partner=1 and status=1");
}else{
    $products = db_query("SELECT * FROM tbl_product_opportunity where status=1");
}
$mainProducts = db_query("SELECT * FROM tbl_main_product_opportunity where status=1");
// $program_initiation_date = getSingleresult("SELECT program_initiation_date from orders where id=".$_POST['pid']);
?>
<style>
    .modal-xl {
        max-width: 1350px;
    }

    .multiselect-container {
        min-width: 450px;
    }
</style>
<div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollablel">
    <div class="modal-content" >
        <div class="modal-header">
            <h5 class="modal-title align-self-center mt-0" id="exampleModalLabel">Save as Opportunity</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="opportunityForm" action="#" method="post" class="form p-t-20" enctype="multipart/form-data">
                <div class="row">
                    <div class="form-group align-items-endn w-100">
                      
                        <div class="form-group row d-flex align-items-end mx-1">
                        <div class="col-md-2 mb-2">
                            <label class="control-label">Main Product<span class="text-danger">*</span></label>
                            <select name="main_product[]" id="mainProduct" class="form-control main-product" required  onchange="mainProductChange(this.value,1)">
                                <option value="">Select Main Product</option>
                                <?php while ($mainRow = db_fetch_array($mainProducts)) { ?>
                                    <option value="<?= $mainRow['id']; ?>"><?= $mainRow['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2"  id="productCode1">
                            <label class="control-label">Product Code<span class="text-danger">*</span></label>
                            <select name="product_code[]" class="form-control product-code" required>
                                <option value="">Select Product</option>
                            </select>
                        </div>
                            <div class="col-md-1 mb-2">
                                <label class="control-label">Quantity<span class="text-danger">*</span></label>
                                <input name="quantity[]" type="number" min="0" class="form-control quantity" placeholder="0" required />
                            </div>
                            <div class="col-md-3 mb-2 sales-price-container">
                                <label class="control-label">Sales Price<span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col">
                                        <input name="sales_price[]" type="number" class="form-control sales-price" placeholder="0.00" readonly />
                                    </div>
                                    <div class="col-auto input-group-append">
                                        <button type="button" class="btn btn-secondary toggle-sales-price" style="display: none;">Negotiate</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="control-label">Total Price<span class="text-danger">*</span></label>
                                <input name="total_price[]" type="number" class="form-control total-price" placeholder="0.00" readonly />
                            </div>
                            <input name="original_price[]" type="hidden" class="form-control original-sales-price">
                            <div class="col-md-2 mb-2">
                            <button type="button" name="addProduct" id="addProduct" class="btn btn-primary mt-2">Add Product</button>
                            </div>
                        </div>
                        
                        <div id="dynamic_products"></div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label class="control-label">Grand Total (Excluding Tax.)<span class="text-danger">*</span></label>
                            <input id="grand_total" name="grand_total" type="number" class="form-control" placeholder="0.00" readonly />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="example-color-input" class="control-label text-left">Stage</label><br />
                            <input value="Quote" name="opportunity_stage" type="text" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="example-color-input" class="control-label text-left">Close Date<span class="text-danger">*</span></label><br />
                            <input id="close-date" value="" min="<?= date('Y-m-d') ?>"  name="close_date" type="date" class="form-control" required />
                        </div>
                    </div>
                    <!-- <div class="col-md-4">
                        <div class="form-group">
                            <label for="example-color-input" class="control-label text-left">Program initiation date<span class="text-danger">*</span></label><br />
                            <input min="<?= $program_initiation_date!= '' ? 'readonly' : date('Y-m-d') ?>" id="program-initiation-date" value="<?= $program_initiation_date ?>" required name="program_initiation_date" type="date" class="form-control"
                            <?= $program_initiation_date!= '' ? 'readonly' : '' ?>>
                        </div>
                    </div> -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="example-color-input" class="control-label text-left">Attachment</label><br />
                            <input type="file" class="form-control" name="user_attachment" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                        <label class="control-label">Product Remarks</label>
                        <textarea id="product_remarks" name="product_remarks" type="text" class="form-control"><?= $product_remarks ?></textarea>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="pid" value="<?= $_POST['pid'] ?>" />

                <div class="text-center mt-3">
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
        var i = 1;
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
                    <div class="col-md-2 mb-2">\
                        <label class="control-label">Total Price<span class="text-danger">*</span></label>\
                        <input name="total_price[]" type="number" class="form-control total-price" placeholder="0.00" readonly>\
                    </div>\
                    <input name="original_price[]" type="hidden" class="form-control original-sales-price">\
                    <div class="col-md-1 mb-2">\
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
                        quantityInput.val(quantity);
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

        $(document).ready(function() {
                var i = 1;
                var add_btnQ = $('.add_btnQ').val();
                $('#addGradesQ').click(function() {
                    i++;
                    $('#dynamic_quanity').append('<div id="row' + add_btnQ + '"><div class="form-group row d-flex align-items-end"><div class="col-lg-2 mb-2"><label class="control-label">Grade<span class="text-danger">*</span></label><input name="grade[]" value="" type="number" required value="" class="form-control" placeholder=""></div><div class="col-lg-2 mb-2"><label class="control-label">Students<span class="text-danger">*</span></label><input value="" name="students[]" type="number" required class="form-control student-input" placeholder=""></div><div class="col-sm-1 mb-2"><span data-repeater-delete="" name="remove" id="' + add_btnQ + '" class="btn btn-danger btn-sm btn_removeQ"><span class="fa fa-times mr-1"></span>Delete</span></div></div></div>')
                    add_btnQ++;
                });
                $(document).on('click', '.btn_removeQ', function() {
                    var button_id = $(this).attr("id");
                    $('#row' + button_id + '').remove();
                });

                let add_btn = 1; // Starting index
        $('#add_button').click(function() {
            add_btn++;
            $('#dynamic_field').append(`
                <div id="row${add_btn}">
                    <div class="form-group row d-flex align-items-end">
                        <div class="col-md-6 mb-2">
                            <label class="control-label">Full Name<span class="text-danger">*</span></label>
                            <input name="e_name[]" type="text" class="form-control" placeholder="" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="control-label">Email<span class="text-danger">*</span></label>
                            <input name="e_email[]" type="email" class="form-control student-input" placeholder="" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="control-label">Mobile<span class="text-danger">*</span></label>
                            <input name="e_mobile[]" type="number" class="form-control student-input" placeholder="" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="control-label">Designation<span class="text-danger">*</span></label>
                            <input name="e_designation[]" type="text" class="form-control student-input" placeholder="" required>
                        </div>
                        <div class="col-md-1 mb-2">
                            <span data-repeater-delete="" name="remove" id="${add_btn}" class="btn btn-danger btn-sm btn_remove">
                                <span class="fa fa-times mr-1"></span>Delete
                            </span>
                        </div>
                    </div>
                </div>
            `);
        });

        $(document).on('click', '.btn_remove', function() {
            let button_id = $(this).attr("id");
            $('#row' + button_id).remove();
        });
            });




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
                data: 'selectedProduct=' + productCodes,
                success: function(response) {
                    if(response == 0){
                        swal.fire('Please select platform product of desired products.')
                    }else{
                        event.preventDefault();
                        const form = document.getElementById("opportunityForm");
                        const formData = new FormData(form);
                        fetch("save_opportunity.php", {
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
                                        window.location.href = "manage_opportunity.php";
                                    }
                                });
                            } else {
                                // Swal.fire("Error", "There was an error: " + data.message, "error");
                                Swal.fire("Error", "There was an error");
                            }
                        });
                    }
                },
                error: function() {
                    alert('error')
                }
            }); 
        }
    }


</script>
