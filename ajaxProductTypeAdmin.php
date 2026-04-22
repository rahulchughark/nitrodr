 <?php
    include('includes/include.php');

    if (!empty($_POST['product'])) {
        $query =   db_query("SELECT * FROM tbl_product_pivot
     WHERE product_id = " . $_POST['product'] . " and status = 1 ORDER BY id Desc");

        //Count total number of rows
        $rowCount = mysqli_num_rows($query);

        //Product Type option list
        if ($rowCount > 0) {
            echo '
        <select name="product_type" id="product_type" class="form-control">
        <option value="">Select Product Type</option>';
            while ($row = db_fetch_array($query)) {
                echo '<option value="' . $row['id'] . '">' . $row['product_type'] . '</option>';
            }
            '</select>';
        }
    }

    if (!empty($_POST['product_id'])) {
        $query = db_query('SELECT * FROM tbl_product_pivot
     WHERE product_id in (' . $_POST['product_id'] . ')  and status = 1 ORDER BY id Desc');

        //Count total number of rows
        $rowCount = mysqli_num_rows($query);

        //Product Type option list
        if ($rowCount > 0) {
            echo '
        <select name="product_type[]" data-live-search="true" multiple class="multiselect_type form-control">';
            while ($row = db_fetch_array($query)) {
                echo '<option value="' . $row['id'] . '">' . $row['product_type'] . '</option>';
            }
            '</select>';
        }
    }
    ?>
 <script>
     $(document).ready(function() {
         $('.multiselect_type').multiselect({
             buttonWidth: '100%',
             includeSelectAllOption: true,
             nonSelectedText: 'Select Product Type'
         });
     });
 </script>