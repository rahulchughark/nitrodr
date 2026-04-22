<style>
    .module-view-table td {
        height: 50px;
        padding-left: 20px;
        padding-right: 20px;
    }

   .switch {
    position: relative;
    display: inline-block;
    width: 42px;
    height: 22px;
    margin-left: 10px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    background-color: #ccc;
    top: 0; left: 0;
    right: 0; bottom: 0;
    transition: .3s;
    border-radius: 20px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    border-radius: 50%;
    transition: .3s;
}

input:checked + .slider {
    background-color: #4CAF50;
}

input:checked + .slider:before {
    transform: translateX(20px);
}

.input-rupee-wrapper {
    position: relative;
    width: 180px;
}

.rupee-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    color: #555;
}

.rupee-input {
    padding-left: 28px !important;  /* space for ₹ */
}

.update-btn {
    min-width: 90px !important; /* Button stays same size */
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

/* Uploading state color */
.update-btn.uploading {
    background: #a3d5dd !important;
    border-color: #a3d5dd !important;
    cursor: not-allowed;
}

/* Small loader */
.loader {
    width: 12px;
    height: 12px;
    border: 2px solid #fff;
    border-top-color: transparent;
    border-radius: 50%;
    display: inline-block;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

</style>



<?php
include "includes/include.php";
include_once "helpers/DataController.php";

$dataObj = new DataController();

$type = $_POST["type"]; #1 : Inc GST, 2: Exc GST
$lead_id = $_POST["lead_id"];
$is_group = $_POST["is_group"];
$group_name = $_POST["group_name"];
$isModel3 = $_POST["isModel3"];

$result = $dataObj->fetchOrderProducts($lead_id, $is_group, $group_name);
$customAmountData = $dataObj->fetchLeadCustomAmount($lead_id);
$customAmount = $customAmountData["amount"] ?? 0;

$gstFieldDisabled = $type == 1 ? true : false;
$headerTitle = $gstFieldDisabled
    ? "Grand Total (Including GST)"
    : "Grand Total (Excluding GST)";
$totalTitle = $gstFieldDisabled
    ? "Total Amount Inc. GST : "
    : "Total Amount Exc. GST : ";
?>


<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">

            <h5><?= $headerTitle ?> </h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
		</div>
		<div class="modal-body mb-4">            
           <table class="table table-bordered">
    <thead>
        <tr>
            <th>Product  <span class="text-danger">*</span></th>
            <th style="width: 28%;">Sub Product <span class="text-danger">*</span></th>
            <th style="width: 100px;">Quantity <span class="text-danger">*</span></th>
            <?php if ($gstFieldDisabled) { ?>                        
            <th style="width: 100px;">GST <span class="text-danger">*</span></th>
            <?php } ?>

            <th style="width: 35%;">Price(₹) <span class="text-danger">*</span></th>
        </tr>
    </thead>
    <tbody>

        <?php
        $grandTotal = 0;
        $grandTotalTax = 0;
        $total3RatioAmount = 0;

       
        while ($data = db_fetch_array($result)) {

            // $totalPrice = $isModel3
            //     ? ($data["total_price"] / 7) * 4
            //     : $data["total_price"];

            // $total3RatioAmount += $isModel3
            //     ? ($data["total_price"] / 7) * 3
            //     : 0;

            // $grandTotal += $totalPrice;
            // if (!$gstFieldDisabled || (float) $data["gst_tax"] == 0) {
            //     $gstPriceCount = number_format($totalPrice);
            // } else {
            //     $gstAmt = ($totalPrice * $data["gst_tax"]) / 100;
            //     $gstPriceCount =
            //         "₹" .
            //         number_format($totalPrice + $total3RatioAmount) .
            //         " + ₹" .
            //         number_format($gstAmt) .
            //         " = ₹" .
            //         number_format($totalPrice + $total3RatioAmount + $gstAmt);
            //     $grandTotalTax += $gstAmt;
            // }

             // Check Model 3 + flag
             $isModel3Product = ($data["product_mst_name"] === "Model 3" && $isModel3 == 1);

              // Apply formula only for Model 3
                if ($isModel3Product) {
                    // 4/7 portion on which GST applies
                    $totalPrice = ($data["total_price"] / 7) * 4;
                    // 3/7 portion (GST exempt)
                    $ratio3 = ($data["total_price"] / 7) * 3;
                } else {
                    // Normal products – full amount
                    $totalPrice = $data["total_price"];
                    $ratio3 = 0;
                }
                
                // Add 3/7 amount
                    $total3RatioAmount += $ratio3;

                    // Add to grand total
                    $grandTotal += $totalPrice;

                    // GST calculation
                    if (!$gstFieldDisabled || (float) $data["gst_tax"] == 0) {
                        // If GST disabled or zero
                        $gstPriceCount = number_format($totalPrice);
                    } else {
                        // GST on 4/7 amount only
                        $gstAmt = ($totalPrice * $data["gst_tax"]) / 100;

                        $gstPriceCount =
                            "₹" . number_format($totalPrice + $total3RatioAmount) .
                            " + ₹" . number_format($gstAmt) .
                            " = ₹" . number_format($totalPrice + $total3RatioAmount + $gstAmt);

                        $grandTotalTax += $gstAmt;
                    }


            ?>
        <tr>
            <td>
                <input type="text" value="<?= $data[
                    "product_mst_name"
                ] ?>" disabled class="form-control">
            </td>
            <td>
                 <input type="text" disabled value="<?= $data[
                     "sub_product"
                 ] ?>" class="form-control">
            </td>
            <td>
                <input type="text" disabled value="<?= $data[
                    "quantity"
                ] ?>" class="form-control">
            </td>
             <?php if ($gstFieldDisabled) { ?> 
            <td>
                <input type="text" disabled value="<?= $data[
                    "gst_tax"
                ] ?>%" class="form-control">
            </td>
            <?php } ?>
            <td>
                <input type="text" disabled name="price" value="<?= $gstPriceCount ?>" class="form-control">
            </td>
        </tr>

         <?php
        }
        ?>
         <tr>
        <td colspan="<?= $gstFieldDisabled
            ? 4
            : 3 ?>" class="text-end fw-bold"> <?= $totalTitle ?> </td>
        <td>
            
            <!-- $customAmount -->
            <?php /* <?php if ($customAmount > 0): ?>          
            <input type="text" readonly value=" ₹<?= number_format($customAmount) ?>" class="form-control fw-bold">
            */ ?>
            <?php 
                
            if ($gstFieldDisabled): ?>
            
            <input type="text" readonly value=" ₹<?= number_format($grandTotal + $total3RatioAmount)   ?> + ₹<?= number_format($grandTotalTax) ?>" class="form-control fw-bold">
            <?php else: ?>

            <input type="text" readonly value="₹<?= number_format($grandTotal) ?>" class="form-control fw-bold">
            <?php endif; ?>
        </td>
         
    </tr>


<tr>
    <td colspan="<?= $gstFieldDisabled ? 4 : 3 ?>" class="text-end fw-bold">
        Custom Marks :

        <label class="switch">
            <input type="checkbox"
                   class="custom-check"
                   <?= $customAmount > 0 ? 'checked' : '' ?>
                   data-id="<?= $data['id'] ?>">
            <span class="slider"></span>
        </label>
    </td>

    <td>
        <div style="display:flex; align-items:center; gap:10px;">

            <div class="custom-box"
                style="display:<?= $customAmount > 0 ? 'flex' : 'none' ?>;
                        align-items:center; gap:10px;">

                <div class="input-rupee-wrapper">
                    <span class="rupee-icon">₹</span>
                    <input type="number"
                        value="<?= $customAmount ?>"
                        class="form-control updated-amount rupee-input"
                        onclick="selectAmount(this)"
                        placeholder="Enter Amount">
                </div>

                <button type="button"
                        class="btn btn-primary btn-sm update-btn"
                        data-leadid="<?= $lead_id ?>"
                        onclick="updateAmount(this)">
                    <span class="btn-text">Update</span>
                    <span class="loader spinner-border spinner-border-sm" style="display:none;"></span>
                </button>
            </div>

        </div>
    </td>
</tr>




    </tbody>
</table>
       
            
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('.main-product').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            buttonWidth: '100%'
        });
    });
</script>
<script>
document.addEventListener("change", function (e) {
    if (e.target.classList.contains("custom-check")) {

        let row = e.target.closest("tr");
        let box = row.querySelector(".custom-box");

        if (e.target.checked) {
            box.style.display = "flex";
        } else {
            box.style.display = "none";
        }
    }
});


// function updateAmount(btn) {

//     const leadId = btn.getAttribute("data-leadid");
//     const box = btn.closest(".custom-box");
//     const amount = box.querySelector(".updated-amount").value;

//     if (amount === "") {
//         // alert("Please enter amount");
//         toastr.info("Validation: Please enter amount");
//         return;
//     }

//     // Elements
//     const btnText = btn.querySelector(".btn-text");
//     const loader = btn.querySelector(".loader");

//     // Disable button + show loader + change text
//     btn.disabled = true;
//     btnText.textContent = "Uploading...";
//     loader.style.display = "inline-block";

//     $.ajax({
//         url: "update_custom_amount.php",
//         type: "POST",
//         data: {
//             lead_id: leadId,
//             amount: amount,
//             currentAmount: <?= $grandTotal ?>
//         },
//         success: function (res) {
//             // console.log("res", res);
//             toastr.success("Success: Amount Updated Successfully");

//             // OPTIONAL: restore button
//             btn.disabled = false;
//             btnText.textContent = "Update";
//             loader.style.display = "none";
//         },
//         error: function () {
//             // alert("Something went wrong!");
//             toastr.error("Error: Something Went Wrong");

//             btn.disabled = false;
//             btnText.textContent = "Update";
//             loader.style.display = "none";
//         }
//     });
// }

function updateAmount(btn) {

    let leadId = btn.getAttribute("data-leadid");
    let wrapper = btn.closest(".custom-box");
    let amount = wrapper.querySelector(".updated-amount").value;

    if (amount === "") {
        // alert("Please enter amount");
          toastr.info("Validation: Please enter amount");
        return;
    }

    // DISABLE BUTTON + SHOW LOADER TEXT
    btn.disabled = true;
    btn.classList.add("uploading");
    btn.innerHTML = `<span class="loader"></span> Uploading...`;

    $.ajax({
        url: "update_custom_amount.php",
        type: "POST",
        data: {
            lead_id: leadId,
            amount: amount,
            currentAmount : <?= $grandTotal ?>
        },
        success: function (res) {
            // console.log("res", res);
              toastr.success("Success: Amount Updated Successfully");

            btn.disabled = false;
            btn.classList.remove("uploading");
            btn.innerHTML = `Update`;

            setTimeout(function() {
                location.reload();
            }, 2000);

            
        },
        error: function () {
             toastr.error("Error: Something Went Wrong");
            btn.disabled = false;
            btn.classList.remove("uploading");
            btn.innerHTML = `Update`;
        }
    });
}


</script>

<script>
function selectAmount(input) {
    input.select();
}
</script>