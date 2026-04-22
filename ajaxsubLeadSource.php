<?php 
include('includes/include.php');
if(!empty($_POST["lead_source"])){
     
    $query = db_query("SELECT * FROM sub_lead_source WHERE lead_source = '" . $_POST["lead_source"] . "'  ORDER BY sub_lead_source ASC");
  
     //Count total number of rows
     $rowCount = mysqli_num_rows($query);
     
     if ($rowCount > 0) {
        echo '  
        <label for="example-text-input" class="col-sm-5 col-form-label">Sub Lead Source<span class="text-danger">*</span></label>
        <div class="col-sm-7">
        <select name="sub_lead_source" class="form-control" required data-validation-required-message="This field is required" id="subleadsource">
        <option value="">Select lead source</option>';
        while ($row = db_fetch_array($query)) {
            echo '<option value="' . $row['sub_lead_source'] . '">' . $row['sub_lead_source'] . '</option>';
        }
        echo '</select></div>';
    }
 }
 ?>