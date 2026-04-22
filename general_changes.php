<?php include('includes/include.php');  
include_once('helpers/DataController.php');

if($_POST['type'] == 'status'){

    $oldstatus = getSingleResult("SELECT lead_status from orders where id=".$_POST['pid']);
    $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_POST['pid']."','Lead Status','".$oldstatus."','".$_POST['leadStatus']."',now(),'".$_SESSION['user_id']."')");

    $status=db_query("update orders set lead_status = '".$_POST['leadStatus']."' where id=".$_REQUEST['pid']);
    if($status){
        echo 'success';
     }else{
         echo 'Error :'. mysqli_info();
     }

}

if($_POST['programStartDate']){

    $olds = getSingleResult("SELECT program_start_date from orders where id=".$_POST['pid']);
    $res =  db_query("insert into lead_modify_log(`lead_id`,type,`previous_name`,`modify_name`,`created_date`,`created_by`)values('".$_POST['pid']."','Program Start Date','".$olds."','".$_POST['programStartDate']."',now(),'".$_SESSION['user_id']."')");

    $status=db_query("update orders set program_start_date = '".$_POST['programStartDate']."' where id=".$_REQUEST['pid']);
    if($status){
        echo 'success';
     }else{
         echo 'Error :'. mysqli_info();
     }

}

if (!empty($_POST["state"])) {
    // print_r($_POST["state"]);die;
    $query = db_query("SELECT * FROM cities where state_id  IN (" . $_POST['state'] . ")");

    $rowCount = mysqli_num_rows($query);

    //City option list
    if ($rowCount > 0) {
        echo ' <select name="city[]" class="multiselect_city form-control" data-live-search="true" multiple>';

        while ($row = db_fetch_array($query)) {
            echo '<option value="' . $row['id'] . '">' . $row['city'] . '</option>';
        }
        echo '</select>';
    } else {
        echo '<option value="">City not available</option>';
    }
}



if (!empty($_POST["state"])) {
    ?>
<script>
        $(document).ready(function() {
                $('.multiselect_city').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select City',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
            });
</script>
<?php } ?>