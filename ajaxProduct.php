<?php
 include('includes/include.php');



if (!empty($_POST['poi_id'])) {
    $poi_id = intval($_POST['poi_id']);
    $query = db_query("SELECT id, product_name FROM tbl_product WHERE poi_id = $poi_id AND status = 1 ORDER BY product_name ASC");
    echo '<option value="">---Select---</option>';
    while ($row = db_fetch_array($query)) {
        echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['product_name']) . '</option>';
    }

    exit;
}

if (!empty($_POST['product'])) {
    $query =   db_query("SELECT * FROM tbl_product_pivot
     WHERE product_id = '" . $_POST['product'] . "' and status = 1");

    //Count total number of rows
    $rowCount = mysqli_num_rows($query);

    //Product Type option list
    if ($rowCount > 0) {
        echo '
        <div class="col-md-12">
		<label for="product_type" class="control-label">Sub Product</label>
        <select name="product_type" id="product_type" class="form-control" required>
        <option value="" >Select Sub Product</option>';
        while ($row = db_fetch_array($query)) {
            echo '<option value="' . $row['id'] . '">' . $row['product_type'] . '</option>';
        }
        echo '</select></div>';
    }
        exit;
}

if (!empty($_POST['productIss'])) {
    $query =   db_query("SELECT * FROM tbl_product_pivot
     WHERE product_id = '" . $_POST['productIss'] . "' and status = 1");

    //Count total number of rows
    $rowCount = mysqli_num_rows($query);

    //Product Type option list
    if ($rowCount > 0) {
        echo '
        
		<label for="product_type" class="control-label">Sub Product<span class="text-danger">*</span></label>
        <select name="product_typeIss" id="product_type" class="form-control" required>
        <option value="" disabled>Select Sub Product</option>';
        while ($row = db_fetch_array($query)) {
            echo '<option value="' . $row['id'] . '">' . $row['product_type'] . '</option>';
        }
        echo '</select>';
    }
        exit;
}