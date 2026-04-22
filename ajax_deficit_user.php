<?php 
include('includes/include.php');
if(!empty($_POST["team_id"])){

    $query = db_query("select * from partners where status='Active' and id=" . $_POST['team_id']);
    $row = db_fetch_array($query);
    
    switch ($row['category']) {
        case "Platinum":
            $sales_team = 3;
            $iss_team = 4;
            $ae_team = 1;
            break;
        case "Gold":
            $sales_team = 2;
            $iss_team = 2;
            $ae_team = 1;
            break;
        default:
            $sales_team = 1;
            $iss_team = 1;
            $ae_team = 1;
    }

$actual_sal_count = getSingleresult("select count(id) from users where team_id='" . $_POST['team_id'] . "' and role ='SAL' and status='Active' order by role");

$actual_iss_count = getSingleresult("select count(id) from users where team_id='" . $_POST['team_id'] . "' and role ='TC' and status='Active' order by role");


    //$rowCount = mysqli_num_rows($query);
    
    //City option list
    if($actual_sal_count > 0 || $actual_iss_count>0){
        echo '
        <div class="form-group row">
        <label class="control-label text-right col-md-3">Deficit User<span class="text-danger">*</span></label>
        <div class="col-md-9 controls">
        <select name="deficit_user" class="form-control" >
        <option value="" >Select deficit</option>';
        //while($row = db_fetch_array($query)){ 
            for ($i = 1; $i <= $sales_team - $actual_sal_count; $i++) { 
                echo '<option value="deficit_sal_'. $i .'">Deficit Sales'. $i .'</option>';
              }
            for ($i = 1; $i <= $iss_team - $actual_iss_count; $i++) { 
                echo '<option value="deficit_iss_'. $i .'">Deficit ISS'. $i .'</option>';

           } 
        }
		echo '</select></div></div>';
  //  } 
}
