<?php include('includes/include.php');  
include_once('helpers/DataController.php');

if (!empty($_POST["product"])) {
        
    $query = db_query("SELECT * FROM tbl_product_opportunity where main_product_id  IN (" . $_POST['product'] . ")");

    $rowCount = mysqli_num_rows($query);

    //City option list
    if ($rowCount > 0) {
        echo ' <select name="sub_product_data[]" class="multiselect_sub_product form-control" data-live-search="true" multiple>';

        while ($row = db_fetch_array($query)) {
            echo '<option value="' . $row['id'] . '">' . $row['product_name'] . '</option>';
        }
        echo '</select>';
    } else {
        echo '<option value="">No Data Found</option>';
    }
}



if (!empty($_POST["product"])) {
    ?>
<script>
        $(document).ready(function() {
                $('.multiselect_sub_product').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Sub Product',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
            });
</script>
<?php } ?>