<?php 
include('includes/include.php');
if(!empty($_POST["state_id"])){
    $stateId = (int)$_POST['state_id'];
    $selectedCity = isset($_POST['selected_city']) ? $_POST['selected_city'] : '';
    $selectedCityId = isset($_POST['selected_city_id']) ? (int)$_POST['selected_city_id'] : 0;
    $selectName = isset($_POST['select_name']) ? $_POST['select_name'] : 'city_id';
    $selectId = isset($_POST['select_id']) ? $_POST['select_id'] : 'city';
    
    $query = db_query("SELECT * FROM city WHERE state_id = ".$stateId."  ORDER BY name ASC");
    
    //Count total number of rows
    $rowCount = mysqli_num_rows($query);
    
    //City option list
    echo '<select name="'.$selectName.'" class="form-control" id="'.$selectId.'" required>';
    echo '<option value="">---Select---</option>';
    if($rowCount > 0){
        while($row = db_fetch_array($query)){ 
            // Support both name-based and ID-based selection
            $selected = '';
            if ($selectedCity && $row['name'] == $selectedCity) {
                $selected = ' selected="selected"';
            } elseif ($selectedCityId && (int)$row['id'] === $selectedCityId) {
                $selected = ' selected="selected"';
            }
            
            // For add_partner.php, the value should be the city name
            $value = ($selectName == 'city') ? $row['name'] : $row['id'];
            
            echo '<option value="'.$value.'"'.$selected.'>'.$row['name'].'</option>';
        }
    }
    echo '</select>';
}
?>