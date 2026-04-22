<?php
include('includes/include.php');
$_POST["partner_id"] = intval($_POST["partner_id"]);

if (!empty($_POST["partner_id"])) {

    $query = db_query("SELECT * FROM users WHERE team_id = " . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['partner_id']) . " and status='Active'  ORDER BY name ASC");
    //print_r($query);
    //Count total number of rows
    $rowCount = mysqli_num_rows($query);

    //City option list
    if ($rowCount > 0) {
        echo '<option value="">Select Submitted By</option>';
        while ($row = db_fetch_array($query)) {
            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
        }
    } else {
        echo '<option value="">Users not available</option>';
    }
}

if (!empty($_POST["partner"])) {

    $query = db_query("SELECT * FROM users WHERE team_id in (" . $_POST['partner'] . ") and status='Active'  ORDER BY name ASC");
    //print_r($query);
    //Count total number of rows
    $rowCount = mysqli_num_rows($query);

    //City option list
    if ($rowCount > 0) {
        echo ' <select name="users[]" id="users" class="multiselect_user1 form-control" data-live-search="true" multiple>';

        while ($row = db_fetch_array($query)) {
            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
        }
        echo '</select>';
    } else {
        echo '<option value="">Users not available</option>';
    }
}

if (!empty($_POST["main_product"])) {

    $query = db_query("SELECT * FROM tbl_product_opportunity WHERE main_product_id in (" . $_POST['main_product'] . ") and status=1");
    //print_r($query);
    //Count total number of rows
    $rowCount = mysqli_num_rows($query);

    //City option list
    if ($rowCount > 0) {
        echo ' <select name="products[]" class="multiselect_product form-control" data-live-search="true" multiple>';

        while ($row = db_fetch_array($query)) {
            echo '<option value="' . $row['id'] . '">' . $row['product_name'] . '</option>';
        }
        echo '</select>';
    } else {
        echo '<option value="">Users not available</option>';
    }
}

if (!empty($_POST["partnerF"])) {

    $query = db_query("SELECT * FROM users WHERE team_id in (" . $_POST['partnerF'] . ") and status='Active'  ORDER BY name ASC");
    //print_r($query);
    //Count total number of rows
    $rowCount = mysqli_num_rows($query);

    //City option list
    if ($rowCount > 0) {
        echo ' <select name="userF" id="userF" class="form-control" data-live-search="true" ><option value="">Select User</option>';

        while ($row = db_fetch_array($query)) {
            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
        }
        echo '</select>';
    } else {
        echo '<option value="">Users not available</option>';
    }
}


if (!empty($_POST["partner_visit_id"])) {

    $query = db_query("SELECT * FROM users WHERE team_id in (" . $_POST['partner_visit_id'] . ") and status='Active' and role in ('BO','SAL') ORDER BY name ASC");
    //print_r($query);
    //Count total number of rows
    $rowCount = mysqli_num_rows($query);

    //City option list
    if ($rowCount > 0) {
        echo ' <select name="users[]" id="users" class="multiselect_user2 form-control" data-live-search="true" multiple>';

        while ($row = db_fetch_array($query)) {
            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
        }
        echo '</select>';
    } else {
        echo '<option value="">Users not available</option>';
    }
}

if (!empty($_POST["stage"])) {
    $st = str_replace(",", "','", $_POST['stage']);
    // print_r($st);die;
    $query = db_query("select * from sub_stage where stage_name IN ('".$st. "')");
    // print_r($query);die;
    //Count total number of rows
    $rowCount = mysqli_num_rows($query);

    //City option list
    if ($rowCount > 0) {
        echo ' <select name="sub_stage[]" class="multiselect_sub_stage form-control" data-live-search="true" multiple>';

        while ($row = db_fetch_array($query)) {
            echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
        }
        echo '</select>';
    } else {
        echo '<option value="">Sub stage not available</option>';
    }
}

?>

<script>
    $(document).ready(function() {
        $('.multiselect_user1').multiselect({
            buttonWidth: '100%',
            includeSelectAllOption: true,
            nonSelectedText: 'Select Submitted By',
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            includeFilterClearBtn:true
        });
    });

    $(document).ready(function() {
        $('.multiselect_user2').multiselect({
            buttonWidth: '100%',
            includeSelectAllOption: true,
            nonSelectedText: 'Select Submitted By',
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            includeFilterClearBtn:true
        });
    });

    $(document).ready(function() {
        $('.multiselect_sub_stage').multiselect({
            buttonWidth: '100%',
            includeSelectAllOption: true,
            nonSelectedText: 'Select Sub Stage',
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            includeFilterClearBtn:true
        });
        $('.multiselect_product').multiselect({
            buttonWidth: '100%',
            includeSelectAllOption: true,
            nonSelectedText: 'Select Product',
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            includeFilterClearBtn:true
        });
    });
</script>