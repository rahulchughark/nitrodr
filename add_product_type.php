<?php
include('includes/header.php');
admin_page();

//$_POST['status'] = intval($_POST['status']);
$_POST['product_name'] = intval($_POST['product_name']);

$select_query = db_query("select * from tbl_product where id=" . $_POST['product_name']);
$select_res = db_fetch_array($select_query);

if ($_POST['product_name']) {
    $number = count($_POST["product_type"]);

    for ($i = 0; $i < $number; $i++) {
        $res =  db_query("INSERT INTO `tbl_product_pivot`(`product_id`, `product_type`,`license_type`,`product_code`, `status`, `created_at`, `form_id`) VALUES ('" . $_POST['product_name'] . "' ,'" . $_POST['product_type'][$i] . "' ,'" . $_POST['license_type'][$i] . "' ,'" . $_POST['product_code'][$i] . "','" . $_POST['status'][$i] . "',now(),'" . $select_res['form_id'] . "')");

        // addProductType($_POST['product_name'],$_POST['product_type'][$i],$_POST['license_type'][$i],$_POST['product_code'][$i],$_POST['status'][$i],now());

    }

    if ($res) {
        redir("manage_products_type.php?add=success", true);
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
                            <div class="media bredcrum-title">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home > Add Product Type</small>
                                    <h4 class="font-size-14 m-0 mt-1">Add Product Type</h4>
                                </div>
                            </div>


                            <form method="post" action="#" class="form-horizontal" novalidate enctype="multipart/form-data">
                                <div data-simplebar class="add_lead">

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row align-items-center">
                                                <label class="control-label text-md-right col-md-4">Product Name<span class="text-danger">*</span></label>
                                                <div class="col-md-8 controls">
                                                    <select name="product_name" required class="form-control" placeholder="Product Name">
                                                        <option value=" " disabled>---Select---</option>
                                                        <?php $res_product = selectProduct('tbl_product');
                                                        while ($row = db_fetch_array($res_product)) { ?>
                                                            <option value=<?= $row['id']; ?>><?= $row['product_name']; ?></option>
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
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="start_date" name="product_type[]" placeholder="Product Type" required onkeydown="preventFirstLetterSpace(event)" oninput="validateInputs()"/>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <!--/row-->

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="end_date" name="product_code[]" placeholder="Product Code" required onkeydown="preventFirstLetterSpace(event)" oninput="validateInputs()"/>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <select name="license_type[]" class="form-control" placeholder="License Type" required>
                                                        <option value="" disabled>License Type</option>
                                                        <option value='Fresh'>Fresh</option>
                                                        <option value='Renewal'>Renewal</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                <select name="status[]" required class="form-control" placeholder="Status">
                                                    <option value="" disabled>Status</option>
                                                    <option value='1'>Active</option>
                                                    <option value='0'>InActive</option>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group row">
                                                    <button style="width:13dx;" type="button" name="add" id="add" class="btn btn-primary">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/span-->

                                    <!--/span-->
                                </div>
                                <div class="button-items text-center">
                                    <button type="submit" class="btn btn-primary  mt-2" id="submitBtn" disabled>Submit</button>
                                    <button type="button" onclick="javascript:history.go(-1)" class="btn btn-danger mt-2">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php include('includes/footer.php') ?>
        <script>
            $(document).ready(function() {
                var i = 1;
                $('#add').click(function() {
                    i++;
                    $('#dynamic_field').append('<div id="row' + i + '"><div class="row"><div class="col-md-2"><div class="form-group"><div class=""><input type="text" class="form-control" id="start_date" name="product_type[]" placeholder="Product Type" required onkeydown="preventFirstLetterSpace(event)" /></div></div></div><div class="col-md-2"><div class="form-group"><div class=""><input type="text" class="form-control" id="end_date" name="product_code[]" placeholder="Product Code" required onkeydown="preventFirstLetterSpace(event)"/></div></div></div><div class="col-md-2"><div class="form-group"><div class=""><select name="license_type[]" class="form-control" required><option value="" disabled>License Type</option><option value="Fresh">Fresh</option><option value="Renewal">Renewal</option></select></div></div></div><div class="col-md-2"><div class="form-group"><div class=""><select name="status[]" required class="form-control"><option value="" disabled>---Select---</option><option value="1">Active</option><option value="0">InActive</option></select></div></div></div><div class="col-md-2"><div class="form-group row"><button style="width:50px;" type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove">X</button></div></div></div></div>');

                });
                $(document).on('click', '.btn_remove', function() {
                    var button_id = $(this).attr("id");
                    $('#row' + button_id + '').remove();
                });
            });
        </script>
<script>
function preventFirstLetterSpace(event) {
    if (event.key === " " && event.target.selectionStart === 0) {
        event.preventDefault();
    }
}

function validateInputs() {
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');
    const submitBtn = document.getElementById('submitBtn');
    
    if (startInput.value.trim() !== '' && endInput.value.trim() !== '') {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

</script>