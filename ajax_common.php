<?php 

include('includes/include.php');

if (!empty($_POST['pivot_id'])) {
    $pivot_id = intval($_POST['pivot_id']);
    $query = db_query("SELECT id, description FROM tbl_product_description
                       WHERE product_pivot_id = $pivot_id AND status = 1
                       ORDER BY id ASC");
    if (mysqli_num_rows($query) > 0) {
        echo '<label for="product_description" class="control-label">Description</label>';
        echo '<select name="description" id="product_description" class="form-control">';
        echo '<option value="">---Select---</option>';
        while ($row = db_fetch_array($query)) {
            echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['description']) . '</option>';
        }
        echo '</select>';
    }
    exit;
}

if(!empty($_POST["state_id"])){
    $stateId = (int)$_POST['state_id'];
    $selectedCity = isset($_POST['selected_city']) ? $_POST['selected_city'] : '';
    $selectedCityId = isset($_POST['selected_city_id']) ? (int)$_POST['selected_city_id'] : 0;
    $selectName = isset($_POST['select_name']) ? $_POST['select_name'] : 'city_id';
    $selectId = isset($_POST['select_id']) ? $_POST['select_id'] : 'city';
    
    // Using 'city' table and 'name' column as per user instruction
    $query = db_query("SELECT * FROM city WHERE state_id = ".$stateId."  ORDER BY name ASC");
    
    $rowCount = mysqli_num_rows($query);
    
    echo '<select name="'.$selectName.'" class="form-control" id="'.$selectId.'" required data-validation-required-message="This field is required">';
    echo '<option value="">---Select---</option>';
    if($rowCount > 0){
        while($row = db_fetch_array($query)){ 
            $selected = '';
            if ($selectedCity && $row['name'] == $selectedCity) {
                $selected = ' selected="selected"';
            } elseif ($selectedCityId && (int)$row['id'] === $selectedCityId) {
                $selected = ' selected="selected"';
            }
            
            // Value is Name for 'city' name, else ID
            $value = ($selectName == 'city') ? $row['name'] : $row['id'];
            echo '<option value="'.$value.'"'.$selected.'>'.$row['name'].'</option>';
        }
    }
    echo '</select>';
    exit;
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
    exit;
}

if(!empty($_POST["region_id"])){
    $regionId = (int)$_POST['region_id'];
    $selectedStateId = isset($_POST['selected_state_id']) ? (int)$_POST['selected_state_id'] : 0;
    
    $query = db_query("SELECT * FROM states WHERE region_id = $regionId ORDER BY name ASC");
    $rowCount = mysqli_num_rows($query);
    
    echo '<select name="state" id="state" class="form-control" required >';
    echo '<option value="">---Select---</option>';
    if($rowCount > 0){
        while($row = db_fetch_array($query)){ 
            $selected = ($selectedStateId === (int)$row['id']) ? ' selected="selected"' : '';
            echo '<option value="'.$row['id'].'"'.$selected.'>'.$row['name'].'</option>';
        }
    }
    echo '</select>';
    exit;
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
    exit;
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
    <?php
    exit;
}


if(!empty($_POST["leads_approval"])){

    $response = array();

    if (in_array(($_SESSION['user_type'] ?? ''), ['USR', 'CLR'], true)) {
        $response['status'] = "error";
        $response['message'] = "You do not have permission to approve leads.";
        echo json_encode($response);
        exit;
    }

    if(!empty($_POST["id"]) && isset($_POST["status"])){

        $id = intval($_POST["id"]);
        $status = intval($_POST["status"]);

        // Fetch old status before updating
        $old_query = db_query("SELECT is_approved FROM orders WHERE id = '".$id."'");
        $old_data = db_fetch_array($old_query);
        $old_status = $old_data['is_approved'] ?? 0;

        $update = db_query("UPDATE orders 
                            SET is_approved = '".$status."' 
                            WHERE id = '".$id."'");

        if($update){
            if ((int)$old_status !== (int)$status) {
                $previousName = ((int)$old_status === 1) ? 'Approved' : 'Not Approved';
                $modifyName = ((int)$status === 1) ? 'Approved' : 'Not Approved';
                $createdBy = (int)($_SESSION['user_id'] ?? 0);

                db_query("INSERT INTO lead_modify_log (`lead_id`, `type`, `stage`, `previous_name`, `modify_name`, `created_date`, `created_by`, `log_status`, `timestamp`, `created_by_clm`) VALUES ('".$id."', 'Approval', NULL, '".$previousName."', '".$modifyName."', NOW(), '".$createdBy."', 'Active', NOW(), '0')");

                // Email notification to the lead creator for approval status change.
                $leadMailQ = db_query("SELECT o.id, o.customer_company_name, o.customer_name, o.product, o.number_of_licenses, o.created_by, u.email AS creator_email, u.name AS creator_name FROM orders o LEFT JOIN users u ON u.id=o.created_by WHERE o.id='".$id."' LIMIT 1");
                $leadMailData = $leadMailQ ? db_fetch_array($leadMailQ) : null;

                $creatorEmail = trim((string)($leadMailData['creator_email'] ?? ''));
                if (filter_var($creatorEmail, FILTER_VALIDATE_EMAIL)) {
                    $updatedByName = trim((string)($_SESSION['name'] ?? ''));
                    if ($updatedByName === '' && $createdBy > 0) {
                        $updatedByName = trim((string)getSingleresult("SELECT name FROM users WHERE id='".$createdBy."' LIMIT 1"));
                    }

                    include_once('helpers/DataController.php');
                    $dataObj = new DataController;

                    $mailPayload = [
                        'lead_id' => (int)($leadMailData['id'] ?? $id),
                        'creator_name' => (string)($leadMailData['creator_name'] ?? 'User'),
                        'company_name' => (string)($leadMailData['customer_company_name'] ?? 'N/A'),
                        'customer_name' => (string)($leadMailData['customer_name'] ?? 'N/A'),
                        'product_name' => (string)($leadMailData['product'] ?? 'N/A'),
                        'licenses' => (string)($leadMailData['number_of_licenses'] ?? 'N/A'),
                        'previous_status' => $previousName,
                        'current_status' => $modifyName,
                        'updated_by' => ($updatedByName !== '' ? $updatedByName : 'System'),
                        'updated_at' => date('d-m-Y h:i A')
                    ];

                    $setSubject = "Lead Approval Status Updated [#".$id."]";
                    $mailBody = $dataObj->buildLeadApprovalStatusEmailTemplate($mailPayload);


                    // Keep AJAX response JSON clean even if mailer writes warnings/notices.
                    ob_start();
                    sendMailReminder($creatorEmail, $setSubject, $mailBody);
                    ob_end_clean();
                }
            }
            
            $response['status'] = "success";
            $response['message'] = "Approval status updated successfully.";
        }else{
            $response['status'] = "error";
            $response['message'] = "Database update failed.";
        }

    } else {
        $response['status'] = "error";
        $response['message'] = "Invalid request.";
    }

    echo json_encode($response);
    exit;
}
?>