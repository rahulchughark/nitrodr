<?php 
include('includes/include.php');
if(!empty($_POST["industry_id"])){
     
    $query = db_query("SELECT * FROM sub_industry WHERE industry_id = ".$_POST['industry_id']."  ORDER BY name ASC");
    
    //Count total number of rows
    $rowCount = mysqli_num_rows($query);
    
    //City option list
    if($rowCount > 0){

        echo '
        <label class="control-label text-right">Sub Industry</label>
        <select name="sub_industry" class="form-control" id="subind">';
        while($row = db_fetch_array($query)){  if($_REQUEST['sub_industry']==$row['id']) $select='selected';
            echo '<option'.$select.' value="'.$row['id'].'">'.$row['name'].'</option>';
        }
		echo '</select>';
    } 
    else
    {
        echo '
        <label class="control-label text-right">Sub Industry</label>
        <select class="form-control" disabled >
        <option value="">---Select---</option>
        </select> ';

    }
}
