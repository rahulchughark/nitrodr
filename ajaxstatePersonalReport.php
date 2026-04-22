<?php
 include('includes/include.php');
 
 $region = implode("','", $_POST['region']);

if (!empty($_POST['region'])) {
    $query =   db_query("SELECT Distinct(city) FROM orders
      WHERE city!=' ' and state IN (" . $_POST['region'] . ") and team_id ='".$_SESSION['team_id']."' ORDER BY city ASC");

//print_r($query);die;
    //Count total number of rows
    $rowCount = mysqli_num_rows($query);

    //City option list
    if ($rowCount > 0) {
        echo '
        <div class="col-md-3">  
        <span>City</span>
        </div>
        <div class="col-md-9">
        <select name="city[]" id="city" data-live-search="true" multiple class="form-control">
        <option value=" " disabled>Select City</option>';
        while ($row = db_fetch_array($query)) {
            echo '<option value="' . $row['city'] . '">' . $row['city'] . '</option>';
        }
        echo '</select></div>';
    }
}
?>
<script>
      $(document).ready(function() {
    $('#city').multiselect({
                                    buttonWidth: '100%',
                                    includeSelectAllOption: true,
                                    nonSelectedText: 'Select an Option'
                                });
      });
</script>