<?php 
include('includes/include.php');
if(!empty($_POST["state_id"])){
     
    $query = db_query("SELECT * FROM cities WHERE state_id = ".$_POST['state_id']."  ORDER BY city ASC");
    
    //Count total number of rows
    $rowCount = mysqli_num_rows($query);
    
    //City option list
    if($rowCount > 0){
        echo '
        <label class="control-label">City<span class="text-danger">*</span></label>
        <select name="city" class="form-control" id="city" required data-validation-required-message="This field is required">
        <option value="" >City</option>';
        while($row = db_fetch_array($query)){ 
            echo '<option value="'.$row['id'].'">'.$row['city'].'</option>';
        }
		echo '</select>';
    } 
}

if(!empty($_POST["state_idd"])){
     
    $query = db_query("SELECT * FROM city WHERE state_id = ".$_POST['state_idd']."  ORDER BY name ASC");
    
    //Count total number of rows
    $rowCount = mysqli_num_rows($query);
    
    //City option list
    if($rowCount > 0){
        echo '
        <select name="city" class="form-control" id="city" required data-validation-required-message="This field is required">
        <option value="" >---Select---</option>';
        while($row = db_fetch_array($query)){ 
            echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }
		echo '</select>';
    } 
    
}

if(!empty($_POST["regionName"])){
    $regionId = getSingleresult("select id from region where region='".$_POST["regionName"]."'");
    $statesForLeads = getSingleresult("select states_access from partners where id=".$_SESSION['team_id']);
    if($statesForLeads){
        $query=db_query("select * from states where region_id=".$regionId." and id in (".$statesForLeads.")");
    }else{
        $query=db_query("select * from states WHERE region_id = ".$regionId);
    }                                                    
    
    //Count total number of rows
    $rowCount = mysqli_num_rows($query);
    
    //City option list
    // if($rowCount > 0){
        echo '
        <select name="state" id="state" class="form-control" required onchange="stateChange(this.value)">
        <option value="" >---Select---</option>';
        while($row = db_fetch_array($query)){ 
            echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }
		echo '</select>';
    // } 
}

if(!empty($_POST["industry"])){
     
    $query = db_query('SELECT * FROM sub_industry WHERE industry_id in (' . $_POST['industry'] . ')  ORDER BY name ASC');
    
    //Count total number of rows
    $rowCount = mysqli_num_rows($query);
    
    //City option list
    if($rowCount > 0){
        echo '
        <select name="sub_industry[]" class="multiselect_type form-control" data-live-search="true" multiple id="subind">';
        while($row = db_fetch_array($query)){ 
            echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }
		echo '</select>';
    } 
}
if(!empty($_POST["industry"])){
    ?>
 <script>
     $(document).ready(function() {
         $('.multiselect_type').multiselect({
             buttonWidth: '100%',
             includeSelectAllOption: true,
             nonSelectedText: 'Select Sub Industry'
         });
     });
 </script>
 <?php } ?>