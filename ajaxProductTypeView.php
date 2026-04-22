 <?php
 include('includes/include.php');

 $product_type = "'" . $_POST['product_type'] . "'";

if (!empty($_POST['product'])) {
    $query =   db_query("SELECT * FROM tbl_product_pivot
     WHERE product_id = " . $_POST['product'] . " and status = 1 ORDER BY id Desc");

    //Count total number of rows
    $rowCount = mysqli_num_rows($query);

    //Product Type option list
    if ($rowCount > 0) {
        echo '
        <select name="product_type" id="product_type" onchange="product_type('.$_POST['raw_id'].','.$_POST['product'].',this.value,'.$product_type.');">
        <option value="">Select Product Type</option>';
        while ($row = db_fetch_array($query)) {
            echo '<option value="' . $row['id'] . '">' . $row['product_type'] . '</option>';
        }
       '</select>';
    }
}
?>

<script>
function product_type(raw_id,id, type,product_type) {

swal({
    title: "Are you sure?",
    text: "Are you sure you would like to change Product Type ?",
    type: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, convert it!",
    confirmButtonColor: "#ec6c62",
    closeOnConfirm: false,

}, function() {
    $.ajax({
            type: 'POST',
            url: 'iss_product_change.php',
            data: {
                raw_lead_id:raw_id,
                product_id: id,
                type: type,
                previous_type:product_type
            },
            success: function(response) {
                return false;
            }
        }).done(function(data) {
            swal("Product Type changed successfully!");
            setTimeout(function() {
                location.reload();
            }, 1000)
        })
        .error(function(data) {
            swal("Oops", "We couldn't connect to the server!", "error");
        });
})
}
</script>