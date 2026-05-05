<?php
    include 'includes/header.php';
    include_once 'helpers/DataController.php';
    include 'includes/audit_log_helper.php';

    $dataObj = new DataController;

// echo "<br>";
// echo "<br>";
// echo "<br>";
// echo "<br>";
// echo "<br>";
// echo "<br>";

// ini_set('display_startup_errors', 1);
// ini_set('display_errors', 1);
// error_reporting(-1);


    function normalize_log_value($field, $value)
    {
    $value = trim((string) $value);
    if ($value === '') {
        return 'N/A';
    }

    if (in_array($field, ['state_id', 'city_id', 'industry_id', 'lead_source_id', 'stage_id', 'proof_engagement_id', 'partner_id', 'align_to', 'created_by'], true)) {
        $id = (int) $value;
        if ($id <= 0) {
            return 'N/A';
        }

        if ($field === 'state_id') {
            $name = getSingleresult("SELECT name FROM states WHERE id='" . $id . "' LIMIT 1");
            return trim((string) $name) !== '' ? trim((string) $name) : (string) $id;
        }
        if ($field === 'city_id') {
            $name = getSingleresult("SELECT name FROM city WHERE id='" . $id . "' LIMIT 1");
            return trim((string) $name) !== '' ? trim((string) $name) : (string) $id;
        }
        if ($field === 'industry_id') {
            $name = getSingleresult("SELECT name FROM tbl_mst_industry WHERE id='" . $id . "' LIMIT 1");
            return trim((string) $name) !== '' ? trim((string) $name) : (string) $id;
        }
        if ($field === 'lead_source_id') {
            $name = getSingleresult("SELECT lead_source FROM lead_source WHERE id='" . $id . "' LIMIT 1");
            return trim((string) $name) !== '' ? trim((string) $name) : (string) $id;
        }
        if ($field === 'stage_id') {
            $name = getSingleresult("SELECT name FROM tbl_mst_stage WHERE id='" . $id . "' LIMIT 1");
            return trim((string) $name) !== '' ? trim((string) $name) : (string) $id;
        }
        if ($field === 'proof_engagement_id') {
            $name = getSingleresult("SELECT name FROM tbl_mst_proof_engagement WHERE id='" . $id . "' LIMIT 1");
            return trim((string) $name) !== '' ? trim((string) $name) : (string) $id;
        }
        if ($field === 'partner_id') {
            $name = getSingleresult("SELECT name FROM partners WHERE id='" . $id . "' LIMIT 1");
            return trim((string) $name) !== '' ? trim((string) $name) : (string) $id;
        }
        if ($field === 'align_to' || $field === 'created_by') {
            $name = getSingleresult("SELECT name FROM users WHERE id='" . $id . "' LIMIT 1");
            return trim((string) $name) !== '' ? trim((string) $name) : (string) $id;
        }
    }

    if ($field === 'subscription_term') {
        if ($value === '1') {
            return '1 Year';
        }
        if ($value === '3') {
            return '3 Year';
        }
    }

    if ($field === 'expected_closure_date') {
        $dateValue = strtotime($value);
        if ($dateValue) {
            return date('Y-m-d', $dateValue);
        }
    }

    return $value;
    }

    function add_lead_modify_log_entry($leadId, $type, $previousName, $modifyName)
    {
    $leadId = (int) $leadId;
    if ($leadId <= 0) {
        return;
    }

    $type         = trim((string) $type) !== '' ? trim((string) $type) : 'Update';
    $previousName = normalize_log_value('', $previousName);
    $modifyName   = normalize_log_value('', $modifyName);

    if ($previousName === $modifyName) {
        return;
    }

    $createdBy        = (int) ($_SESSION['user_id'] ?? 0);
    $safeType         = mysqli_real_escape_string($GLOBALS['dbcon'], $type);
    $safePreviousName = mysqli_real_escape_string($GLOBALS['dbcon'], $previousName);
    $safeModifyName   = mysqli_real_escape_string($GLOBALS['dbcon'], $modifyName);

    db_query("INSERT INTO lead_modify_log (`lead_id`, `type`, `stage`, `previous_name`, `modify_name`, `created_date`, `created_by`, `log_status`, `timestamp`, `created_by_clm`) VALUES ('" . $leadId . "', '" . $safeType . "', NULL, '" . $safePreviousName . "', '" . $safeModifyName . "', NOW(), '" . $createdBy . "', 'Active', NOW(), '0')");
    }

    function get_orders_comment_column()
    {
    static $commentColumn = null;
    if ($commentColumn !== null) {
        return $commentColumn;
    }

    $hasAddComment = getSingleresult("SHOW COLUMNS FROM orders LIKE 'add_comment'");
    if (! empty($hasAddComment)) {
        $commentColumn = 'add_comment';
        return $commentColumn;
    }

    $hasComment = getSingleresult("SHOW COLUMNS FROM orders LIKE 'comment'");
    if (! empty($hasComment)) {
        $commentColumn = 'comment';
        return $commentColumn;
    }

    $commentColumn = '';
    return $commentColumn;
    }

    function get_orders_product_interest_column()
    {
    static $productInterestColumn = null;
    if ($productInterestColumn !== null) {
        return $productInterestColumn;
    }

    $hasProductInterest = getSingleresult("SHOW COLUMNS FROM orders LIKE 'product_interest'");
    if (! empty($hasProductInterest)) {
        $productInterestColumn = 'product_interest';
        return $productInterestColumn;
    }

    $hasProductOfInterest = getSingleresult("SHOW COLUMNS FROM orders LIKE 'product_of_interest'");
    if (! empty($hasProductOfInterest)) {
        $productInterestColumn = 'product_of_interest';
        return $productInterestColumn;
    }

    $productInterestColumn = '';
    return $productInterestColumn;
    }

    $editId = isset($_REQUEST['eid']) ? (int) $_REQUEST['eid'] : 0;
    $row    = [];
    if ($editId > 0) {
    $editRes = db_query("SELECT * FROM orders WHERE id='" . $editId . "' LIMIT 1");
    if ($editRes && mysqli_num_rows($editRes) > 0) {
        $row = db_fetch_array($editRes);
    }
    }

    $leadId          = isset($_GET['lead']) ? intval($_GET['lead']) : 0;
    $typeId          = isset($_GET['type']) ? intval($_GET['type']) : 0;
    $productInterest = isset($_GET['product_of_interest']) ? trim((string) $_GET['product_of_interest']) : '';
    $licenseTypeUrl  = isset($_GET['license_type']) ? trim((string) $_GET['license_type']) : '';
    $renewalTypeUrl  = isset($_GET['renewal_type']) ? trim((string) $_GET['renewal_type']) : '';

    $productName    = '';
    $subProductName = '';

    if ($leadId > 0) {
    $productRes = db_query("SELECT product_name FROM tbl_product WHERE id='" . $leadId . "' LIMIT 1");
    if ($productRes && mysqli_num_rows($productRes) > 0) {
        $productRow  = db_fetch_array($productRes);
        $productName = $productRow['product_name'];
    }
    }

    if ($typeId > 0) {
    $subProductRes = db_query("SELECT product_type FROM tbl_product_pivot WHERE id='" . $typeId . "' LIMIT 1");
    if ($subProductRes && mysqli_num_rows($subProductRes) > 0) {
        $subProductRow  = db_fetch_array($subProductRes);
        $subProductName = $subProductRow['product_type'];
    }
    }

    if (! empty($row)) {
    $productName    = ! empty($row['product']) ? $row['product'] : $productName;
    $subProductName = ! empty($row['sub_product']) ? $row['sub_product'] : $subProductName;
    if (isset($row['product_interest']) && trim((string) $row['product_interest']) !== '') {
        $productInterest = trim((string) $row['product_interest']);
    } elseif (isset($row['product_of_interest']) && trim((string) $row['product_of_interest']) !== '') {
        $productInterest = trim((string) $row['product_of_interest']);
    }
    if ($subProductName === '') {
        $subProductName = '-';
    }
    }

    $isEditMode                  = ($editId > 0 && ! empty($row));
    $loggedInUserType            = $_SESSION['user_type'] ?? '';
    $loggedInTeamId              = (int) ($_SESSION['team_id'] ?? 0);
    $isManagerEditMode           = ($isEditMode && $loggedInUserType === 'MNGR');
    $isPartnerRole               = (strtoupper((string) ($_SESSION['role'] ?? '')) === 'PARTNER');
    $ordersCommentColumn         = get_orders_comment_column();
    $ordersProductInterestColumn = get_orders_product_interest_column();
    $commentFieldValue           = ($ordersCommentColumn !== '' && isset($row[$ordersCommentColumn])) ? (string) $row[$ordersCommentColumn] : '';

    $managerTeamUsers = [];
    if ($isManagerEditMode && $loggedInTeamId > 0) {
    $managerUsersRes = db_query("SELECT id, name FROM users WHERE team_id='" . $loggedInTeamId . "' AND user_type='USR' AND status='Active' ORDER BY name ASC");
    while ($managerUsersRes && ($managerUserRow = db_fetch_array($managerUsersRes))) {
        $managerTeamUsers[] = $managerUserRow;
    }
    }

?>

<?php if (isset($_POST['r_name']) && $_POST['r_name'] != '') {

    $current_date = date('Y-m-d H:i:s');
    $gradesSigned = $_POST['grade_signed_up'] ? implode(", ", $_POST['grade_signed_up']) : '';

    $schoolboard = $_POST['school_board'] == 'Others' ? $_POST['other_board'] : $_POST['school_board'];

    // Handle file upload for upload_file
    $uploadFileName = '';
    if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] === UPLOAD_ERR_OK) {
        $tmpPath         = $_FILES['upload_file']['tmp_name'];
        $ext             = strtolower(pathinfo($_FILES['upload_file']['name'], PATHINFO_EXTENSION));
        $allowedImageExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowedDocExt   = ['doc', 'docx', 'pdf'];
        $allowed         = array_merge($allowedImageExt, $allowedDocExt);
        $isImageFile     = in_array($ext, $allowedImageExt, true);

        if (in_array($ext, $allowed, true) && (! $isImageFile || @getimagesize($tmpPath))) {
            $uploadDir = __DIR__ . '/uploads/leads/';
            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = uniqid('lead_', true) . '.' . $ext;
            if (move_uploaded_file($tmpPath, $uploadDir . $fileName)) {
                $uploadFileName = 'uploads/leads/' . $fileName;
            }
        }
    }

    if ($isEditMode) {
        $updatedUserId = (int) ($row['created_by'] ?? 0);
        if ($isManagerEditMode) {
            $requestedUserId = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;
            if ($requestedUserId > 0 && $loggedInTeamId > 0) {
                $validUserRes = db_query("SELECT id FROM users WHERE id='" . $requestedUserId . "' AND team_id='" . $loggedInTeamId . "' AND user_type='USR' LIMIT 1");
                if ($validUserRes && mysqli_num_rows($validUserRes) > 0) {
                    $updatedUserId = $requestedUserId;
                }
            }
        }

        // Determine created_by_category from session (no DB query)
        $sessionRole              = $_SESSION['role'] ?? '';
        $updatedCreatedByCategory = ($sessionRole === 'Partner') ? 'Partner' : 'Internal';

        $updatedAlignTo = $_POST['align_to'] ?? '';
        if ($loggedInUserType === 'USR' && $loggedInTeamId !== 127) {
            $updatedAlignTo = (string) (int) ($_SESSION['user_id'] ?? 0);
        }
        $leadComment              = mysqli_real_escape_string($GLOBALS['dbcon'], trim((string) ($_POST['comment'] ?? '')));
        $leadProductInterest      = mysqli_real_escape_string($GLOBALS['dbcon'], trim((string) ($_POST['product_interest'] ?? '')));
        $commentUpdateSql         = ($ordersCommentColumn !== '') ? $ordersCommentColumn . "='" . $leadComment . "',\n            " : '';
        $productInterestUpdateSql = ($ordersProductInterestColumn !== '') ? $ordersProductInterestColumn . "='" . $leadProductInterest . "',\n            " : '';

        $uploadSql     = $uploadFileName !== '' ? ", upload_file='" . $uploadFileName . "'" : '';
        $oldUploadFile = trim((string) ($row['upload_file'] ?? ''));

        $res = db_query("UPDATE orders SET
            customer_company_name='" . $_POST['customer_company_name'] . "',
            customer_name='" . $_POST['customer_name'] . "',
            email='" . $_POST['email'] . "',
            phone='" . $_POST['phone'] . "',
            designation='" . $_POST['designation'] . "',
            state_id='" . $_POST['state_id'] . "',
            city_id='" . $_POST['city_id'] . "',
            address='" . $_POST['address'] . "',
            industry_id='" . $_POST['industry_id'] . "',
            existing_nitro_customer='" . $_POST['existing_nitro_customer'] . "',
            product='" . $_POST['product'] . "',
            sub_product='" . $_POST['sub_product'] . "',
            number_of_licenses='" . $_POST['number_of_licenses'] . "',
            subscription_term='" . $_POST['subscription_term'] . "',
            expected_closure_date='" . $_POST['expected_closure_date'] . "',
            competition_involved='" . $_POST['competition_involved'] . "',
            " . $commentUpdateSql . "
            " . $productInterestUpdateSql . "
            lead_source_id='" . $_POST['lead_source_id'] . "',
            stage_id='" . $_POST['stage_id'] . "',
            proof_engagement_id='" . $_POST['proof_engagement_id'] . "',
            partner_id='" . $_POST['partner_id'] . "',
            created_by='" . $updatedUserId . "',
            description='" . $_POST['description'] . "',
            created_by_category='" . $updatedCreatedByCategory . "',
            align_to='" . $updatedAlignTo . "'"
            . $uploadSql .
            " WHERE id='" . $editId . "'");

        if ($res) {
            $fields_to_log = [
                'customer_company_name'   => 'Company Name',
                'customer_name'           => 'Customer Name',
                'email'                   => 'Email',
                'phone'                   => 'Phone',
                'designation'             => 'Designation',
                'state_id'                => 'State',
                'city_id'                 => 'City',
                'address'                 => 'Address',
                'industry_id'             => 'Industry',
                'existing_nitro_customer' => 'Existing Nitro Customer',
                'product'                 => 'Product',
                'sub_product'             => 'Sub Product',
                'number_of_licenses'      => 'Number of Licenses',
                'subscription_term'       => 'Subscription Term',
                'expected_closure_date'   => 'Expected Closure Date',
                'competition_involved'    => 'Competition Involved',
                'lead_source_id'          => 'Lead Source',
                'stage_id'                => 'Stage',
                'proof_engagement_id'     => 'Proof Engagement',
                'partner_id'              => 'Partner',
                'created_by'              => 'Created By',
                'align_to'                => 'Align To',
            ];
            if ($ordersCommentColumn !== '') {
                $fields_to_log[$ordersCommentColumn] = 'Comment';
            }
            if ($ordersProductInterestColumn !== '') {
                $fields_to_log[$ordersProductInterestColumn] = 'Product Interest';
            }

            foreach ($fields_to_log as $field => $display_name) {
                $old_value = $row[$field] ?? '';
                $new_value = $_POST[$field] ?? '';
                if ($field === 'created_by') {
                    $new_value = $updatedUserId;
                }
                if ($field === 'align_to' && $loggedInUserType === 'USR') {
                    $new_value = $updatedAlignTo;
                }
                if ($field === 'add_comment' || $field === 'comment') {
                    $new_value = $_POST['comment'] ?? '';
                }
                if ($field === 'product_interest' || $field === 'product_of_interest') {
                    $new_value = $_POST['product_interest'] ?? '';
                }
                $normalizedOldValue = normalize_log_value($field, $old_value);
                $normalizedNewValue = normalize_log_value($field, $new_value);
                if ($normalizedOldValue !== $normalizedNewValue) {
                    add_lead_modify_log_entry($editId, $display_name, $normalizedOldValue, $normalizedNewValue);
                }
            }

            if ($uploadFileName !== '') {
                $normalizedOldUpload = normalize_log_value('upload_file', $oldUploadFile);
                $normalizedNewUpload = normalize_log_value('upload_file', $uploadFileName);
                if ($normalizedOldUpload !== $normalizedNewUpload) {
                    add_lead_modify_log_entry($editId, 'Upload File', $normalizedOldUpload, $normalizedNewUpload);
                }
            }

            $redirectBase = (strtoupper((string) ($_SESSION['user_type'] ?? '')) === 'ADMIN') ? 'admin_leads.php' : 'search_orders.php';
            redir($redirectBase . "?edit=success", true);
        }
    } else {

        // INSERT query for add mode
        $insertAlignTo = $_POST['align_to'] ?? '';
        if ($loggedInUserType === 'USR' && $loggedInTeamId !== 127) {
            $insertAlignTo = (string) (int) ($_SESSION['user_id'] ?? 0);
        }

        // Determine created_by_category from session (no DB query)
        $sessionRole                  = $_SESSION['role'] ?? '';
        $insertCreatedByCategory      = ($sessionRole === 'Partner') ? 'Partner' : 'Internal';
        $leadComment                  = mysqli_real_escape_string($GLOBALS['dbcon'], trim((string) ($_POST['comment'] ?? '')));
        $leadProductInterest          = mysqli_real_escape_string($GLOBALS['dbcon'], trim((string) ($_POST['product_interest'] ?? '')));
        $leadDescription              = mysqli_real_escape_string($GLOBALS['dbcon'], trim((string) ($_POST['description'] ?? '')));
        $commentInsertColumns         = ($ordersCommentColumn !== '') ? ", " . $ordersCommentColumn : '';
        $commentInsertValues          = ($ordersCommentColumn !== '') ? ", '" . $leadComment . "'" : '';
        $productInterestInsertColumns = ($ordersProductInterestColumn !== '') ? ", " . $ordersProductInterestColumn : '';
        $productInterestInsertValues  = ($ordersProductInterestColumn !== '') ? ", '" . $leadProductInterest . "'" : '';

        $res = db_query("INSERT INTO orders (
            customer_company_name, customer_name, email, phone, designation,
            state_id, city_id, address, industry_id, existing_nitro_customer,
            product, sub_product, number_of_licenses, subscription_term, expected_closure_date,
            competition_involved" . $commentInsertColumns . $productInterestInsertColumns . ", lead_source_id, stage_id, proof_engagement_id, partner_id, align_to,
            upload_file, status, created_by_category, created_by, created_at, description, license_type, renewal_type
        ) VALUES (
            '" . $_POST['customer_company_name'] . "',
            '" . $_POST['customer_name'] . "',
            '" . $_POST['email'] . "',
            '" . $_POST['phone'] . "',
            '" . $_POST['designation'] . "',
            '" . $_POST['state_id'] . "',
            '" . $_POST['city_id'] . "',
            '" . $_POST['address'] . "',
            '" . $_POST['industry_id'] . "',
            '" . $_POST['existing_nitro_customer'] . "',
            '" . $_POST['product'] . "',
            '" . $_POST['sub_product'] . "',
            '" . $_POST['number_of_licenses'] . "',
            '" . $_POST['subscription_term'] . "',
            '" . $_POST['expected_closure_date'] . "',
            '" . $_POST['competition_involved'] . "'" . $commentInsertValues . $productInterestInsertValues . ",
            '" . $_POST['lead_source_id'] . "',
            '" . $_POST['stage_id'] . "',
            '" . $_POST['proof_engagement_id'] . "',
            '" . $_POST['partner_id'] . "',
            '" . $insertAlignTo . "',
            '" . $uploadFileName . "',
            '1',
            '" . $insertCreatedByCategory . "',
            '" . $_SESSION['user_id'] . "',
            '" . $current_date . "',
            '" . $_POST['description'] . "',
            '" . ($_POST['license_type'] ?? '') . "',
            '" . ($_POST['renewal_type'] ?? '') . "'
        )");

        $lead_id = get_insert_id();

        if ($res) {
            $last_inserted_id = $lead_id;

            $createdBy = (int) ($_SESSION['user_id'] ?? 0);
            db_query("INSERT INTO lead_modify_log (`lead_id`, `type`, `stage`, `previous_name`, `modify_name`, `created_date`, `created_by`, `log_status`, `timestamp`, `created_by_clm`) VALUES ('" . $lead_id . "', 'Lead', NULL, 'N/A', 'Lead', NOW(), '" . $createdBy . "', 'Active', NOW(), '0')");

            // Log new lead creation
            log_lead_creation($lead_id, $_POST['customer_company_name']);

            // Log file upload if occurred
            if ($uploadFileName !== '') {
                log_file_upload($lead_id, $uploadFileName, 'company image');
            }
            // $kmsSendRecords = $dataObj->saveOnboardingSchoolDetailsWithJson($_POST,$last_inserted_id);

            // $kmsResponseJson = json_encode($kmsSendRecords, JSON_UNESCAPED_UNICODE);

            // db_query("
            // UPDATE orders
            // SET kms_school_response = '{$kmsResponseJson}'
            // WHERE id = {$last_inserted_id}
            // ");

            $adminsEmail = db_query("select email from users where user_type IN ('SUPERADMIN','ADMIN')");
            while ($rowAd = db_fetch_array($adminsEmail)) {
                $addTo[] = $rowAd['email'];
            }
            $operEmail = db_query("select email from users where user_type = 'OPERATIONS'");
            while ($rowOp = db_fetch_array($operEmail)) {
                $addCc[] = $rowOp['email'];
            }

            $setSubject = "New Lead Added to DR Portal from " . $_POST['r_name'] . " (" . $_POST['r_user'] . ")";
            $body       = "Hi,<br><br> There is new lead added to ICT DR Portal with details as below:-<br><br>
              <ul>
              <li><b>Customer Company</b> : " . $_POST['customer_company_name'] . " </li>
              <li><b>Customer Name</b> : " . $_POST['customer_name'] . " </li>
              <li><b>Email</b> : " . $_POST['email'] . " </li>
              <li><b>Phone</b> : " . $_POST['phone'] . " </li>
              <li><b>Product</b> : " . $_POST['product'] . " </li>
              <li><b>Licenses</b> : " . $_POST['number_of_licenses'] . " </li>
              <li><b>Expected Close Date</b> : " . $_POST['expected_closure_date'] . " </li>
              </ul><br>
              Thanks,<br>
              ICT DR Portal";

            $addBcc[] = '';
            //   sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
            $redirectBase = (strtoupper((string) ($_SESSION['user_type'] ?? '')) === 'ADMIN') ? 'admin_leads.php' : 'search_orders.php';
            redir($redirectBase . "?add=success", true);
        }
    }
}?>
<style>
    .card-subtitle {
    margin: 2px 0;
    padding:6px 5px;
    }
    .form-group {
    margin-bottom: 0;
}
label {
    display: inline-block;
    margin-bottom: 0.2rem;
}
.add_lead {
    padding: 7px 15px;
}
#form_activity .form-control{height: 24px;
    padding: 0 5px; font-size:11px;}
#form_activity .col-form-label {
    padding-top: 2px; font-size:11px; color:#1B274D;
    padding-bottom: 9px; text-align: right;}
#form_activity .card-subtitle {
    margin: 2px 0;
    padding: 2px 5px;
}
#form_activity .card-subtitle{font-size:13px; color:#0E1426; background:#f4f4f4;}

#form_activity .btn{padding: 8px 10px;}

#clone-form-container hr {
    display: none;
}

#clone-wrapper #clone-form-container hr {
    display: block;
}

.select2-container .select2-selection--single {
    height: 24px;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 24px;
    font-size: 12px;
}

@media (max-width: 767px) {
        .add_lead {
            height: auto!important;
        }
        .add_lead {
            padding: 7px 0;
        }
    }
    #form_activity .col-form-label {
        text-align: left;
    }
</style>

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home > <?php echo $isEditMode ? 'Edit Lead' : 'Add Lead' ?></small>
                                    <h4 class="font-size-14 m-0 mt-1"><?php echo $isEditMode ? 'Edit Lead' : 'Add Lead' ?></h4>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <!-- <form method="post" action="#" class="form-horizontal" enctype="multipart/form-data" id="form_activity">

                            </form> -->


                            <form method="post" action="#" class="form-horizontal" id="form_activity" enctype="multipart/form-data">
                                <?php if (! $isManagerEditMode) {?>
                                <input type="hidden" value="<?php echo $_SESSION['user_id'] ?>" name="user_id">
                                <?php }?>
                                <input type="hidden" value="<?php echo $_SESSION['team_id'] ?>" name="team_id">
                                <input type="hidden" value="<?php echo $_SESSION['name'] ?>" name="r_user">
                                <input type="hidden" value="<?php echo $_SESSION['email'] ?>" name="r_email">
                                <input type="hidden" value="<?php echo getSingleresult("select name from partners where id='".$_SESSION['team_id']."'") ?>" name="r_name">
                                <input type="hidden" id="selected_city_id" value="<?php echo (int)($row['city_id'] ?? 0) ?>">

                                <div class="add_lead">
                                    <h5 class="card-subtitle">Customer Details</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Customer Company Name<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="customer_company_name" class="form-control" placeholder="" value="<?php echo htmlspecialchars($row['customer_company_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Customer Name<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="customer_name" class="form-control" placeholder="" value="<?php echo htmlspecialchars($row['customer_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Email<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="email" name="email" class="form-control" placeholder="" value="<?php echo htmlspecialchars($row['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Phone Number<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="phone" maxlength="10" minlength="10" class="form-control" placeholder="" value="<?php echo htmlspecialchars($row['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required onkeypress="return isNumberKey(event,this.id);">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Designation<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="designation" class="form-control" placeholder="" value="<?php echo htmlspecialchars($row['designation'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">State<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <select name="state_id" id="state" class="form-control" required onchange="stateChange(this.value)">
                                                        <option value="">---Select---</option>
                                                        <?php
                                                            $states = db_query("SELECT * FROM states ORDER BY name ASC");
                                                            while ($st = db_fetch_array($states)) {
                                                                $selectedState = ((string) ($row['state_id'] ?? '') === (string) $st['id']) ? 'selected' : '';
                                                                echo "<option value='{$st['id']}' {$selectedState}>{$st['name']}</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">City<span class="text-danger">*</span></label>
                                                <div class="col-sm-7" id="city_container">
                                                    <select name="city_id" class="form-control" required>
                                                        <option value="">---Select---</option>
                                                        <?php if (! empty($row['city_id'])) {?>
                                                            <option value="<?php echo (int)$row['city_id'] ?>" selected><?php echo htmlspecialchars(getSingleresult("SELECT name FROM city WHERE id='".(int)$row['city_id']."' LIMIT 1"), ENT_QUOTES, 'UTF-8') ?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Address<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <textarea name="address" class="form-control" rows="2" required><?php echo htmlspecialchars($row['address'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Industry<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <select name="industry_id" class="form-control" required>
                                                        <option value="">---Select---</option>
                                                        <?php
                                                            $industries = db_query("SELECT * FROM tbl_mst_industry WHERE status=1 ORDER BY name ASC");
                                                            while ($ind = db_fetch_array($industries)) {
                                                                $selectedIndustry = ((string) ($row['industry_id'] ?? '') === (string) $ind['id']) ? 'selected' : '';
                                                                echo "<option value='{$ind['id']}' {$selectedIndustry}>{$ind['name']}</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Existing Nitro customer?<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <select name="existing_nitro_customer" class="form-control" required>
                                                        <option value="">---Select---</option>
                                                        <option value="Yes" <?php echo (($row['existing_nitro_customer'] ?? '') === 'Yes') ? 'selected' : '' ?>>Yes</option>
                                                        <option value="No" <?php echo (($row['existing_nitro_customer'] ?? '') === 'No') ? 'selected' : '' ?>>No</option>
                                                        <option value="Not Sure" <?php echo (($row['existing_nitro_customer'] ?? '') === 'Not Sure') ? 'selected' : '' ?>>Not Sure</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="card-subtitle mt-3">Deal Details</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Product Interest</label>
                                                <div class="col-sm-7">
                                                    <select <?php echo ($isEditMode || !empty($_GET['product_of_interest'])) ? 'disabled' : ''; ?> name="product_interest" id="product_interest_select" class="form-control" >
                                                        <option value="">---Select---</option>
                                                        <?php
                                                            $selectedPoiId = isset($_GET['product_of_interest']) ? intval($_GET['product_of_interest']) : ($row['product_interest'] ?? '');
                                                            $poiRes        = db_query("SELECT id, name FROM tbl_product_poi WHERE status=1 ORDER BY name ASC");
                                                            while ($poi = db_fetch_array($poiRes)) {
                                                                $selected = ((string) $selectedPoiId === (string) $poi['id']) ? 'selected' : '';
                                                                echo "<option value='{$poi['id']}' {$selected}>{$poi['name']}</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Sub Product<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <select <?php echo ($isEditMode || !empty($_GET['type'])) ? 'disabled' : ''; ?> name="sub_product" id="sub_product_select" class="form-control" required >
                                                        <option value="">---Select---</option>
                                                        <?php
                                                            $selectedProductId    = isset($_GET['lead']) ? intval($_GET['lead']) : ($row['product'] ?? '');
                                                            $selectedSubProductId = isset($_GET['type']) ? intval($_GET['type']) : ($row['sub_product'] ?? '');
                                                            if ($selectedProductId) {
                                                                $subProducts = db_query("SELECT id, product_type FROM tbl_product_pivot WHERE product_id='" . $selectedProductId . "' AND status=1 ORDER BY product_type ASC");
                                                                while ($sub = db_fetch_array($subProducts)) {
                                                                    $selected = ((string) $selectedSubProductId === (string) $sub['id']) ? 'selected' : '';
                                                                    echo "<option value='{$sub['id']}' {$selected}>{$sub['product_type']}</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Number of licenses<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="number" name="number_of_licenses" min="1" class="form-control" placeholder="" value="<?php echo htmlspecialchars($row['number_of_licenses'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">License Type</label>
                                                <div class="col-sm-7">
                                                    <select disabled name="license_type" id="license_type" class="form-control">
                                                        <option value="">---Select---</option>
                                                        <?php
                                                            $selectedLicenseType = $isEditMode ? ($row['license_type'] ?? '') : $licenseTypeUrl;
                                                        ?>
                                                        <option value="Fresh" <?php echo ($selectedLicenseType === 'Fresh') ? 'selected' : ''; ?>>Fresh</option>
                                                        <option value="Renewal" <?php echo ($selectedLicenseType === 'Renewal') ? 'selected' : ''; ?>>Renewal</option>
                                                        <option value="Expansion" <?php echo ($selectedLicenseType === 'Expansion') ? 'selected' : ''; ?>>Expansion</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Product<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                     <select <?php echo ($isEditMode || !empty($_GET['lead'])) ? 'disabled' : ''; ?> name="product" id="product_select" class="form-control" required >
                                                        <option value="">---Select---</option>
                                                        <?php
                                                            $selectedProductId = isset($_GET['lead']) ? intval($_GET['lead']) : ($row['product'] ?? '');
                                                            $selectedPoiId = isset($_GET['product_of_interest']) ? intval($_GET['product_of_interest']) : ($row['product_interest'] ?? '');
                                                            
                                                            $sql = "SELECT id, product_name FROM tbl_product WHERE status=1 ";
                                                            if ($selectedPoiId) {
                                                                $sql .= " AND poi_id = '$selectedPoiId' ";
                                                            }
                                                            $sql .= " ORDER BY product_name ASC";
                                                            
                                                            $products = db_query($sql);
                                                            while ($prod = db_fetch_array($products)) {
                                                                $selected = ((string) $selectedProductId === (string) $prod['id']) ? 'selected' : '';
                                                                echo "<option value='{$prod['id']}' {$selected}>{$prod['product_name']}</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                             <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Product Description</label>
                                                <div class="col-sm-7">
                                                    <select <?php echo ($isEditMode || !empty($_GET['description'])) ? 'disabled' : ''; ?> name="description" id="description_select" class="form-control">
                                                    <option value="">---Select---</option>
                                                    <?php
                                                        $selectedDescriptionId = isset($_GET['description']) ? intval($_GET['description']) : ($row['description'] ?? '');
                                                        $selectedSubProductId = isset($_GET['type']) ? intval($_GET['type']) : ($row['sub_product'] ?? '');
                                                        
                                                        $sql = "SELECT id, description FROM tbl_product_description WHERE status=1 ";
                                                        if ($selectedSubProductId) {
                                                            $sql .= " AND product_pivot_id = '$selectedSubProductId' ";
                                                        }
                                                        $sql .= " ORDER BY description ASC";
                                                        
                                                        $descRes = db_query($sql);
                                                        while ($desc = db_fetch_array($descRes)) {
                                                        $selected = ((string) $selectedDescriptionId === (string) $desc['id']) ? 'selected' : '';
                                                        echo "<option value='{$desc['id']}' {$selected}>{$desc['description']}</option>";
                                                        }
                                                    ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="subscription_term_container">
                                                <label class="col-sm-5 col-form-label">Subscription term<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <select name="subscription_term" id="subscription_term" class="form-control" required>
                                                        <option value="">---Select---</option>
                                                        <option value="1" <?php echo ((string)($row['subscription_term'] ?? '') === '1' || (string)($row['subscription_term'] ?? '') === '1 Year' || (string)($row['subscription_term'] ?? '') === '1 year') ? 'selected' : '' ?>>1 year</option>
                                                        <option value="3" <?php echo ((string)($row['subscription_term'] ?? '') === '3' || (string)($row['subscription_term'] ?? '') === '3 Year' || (string)($row['subscription_term'] ?? '') === '3 year') ? 'selected' : '' ?>>3 years</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <script>
                                            document.addEventListener("DOMContentLoaded", function() {
                                                function toggleSubTerm() {
                                                    var prod = document.getElementById('product_select');
                                                    var termContainer = document.getElementById('subscription_term_container');
                                                    var termSelect = document.getElementById('subscription_term');
                                                    if(prod && prod.value === '1') {
                                                        termContainer.style.display = 'none';
                                                        termSelect.required = false;
                                                    } else {
                                                        termContainer.style.display = 'flex';
                                                        termSelect.required = true;
                                                    }
                                                }
                                                var pSelect = document.getElementById('product_select');
                                                if(pSelect) {
                                                    pSelect.addEventListener('change', toggleSubTerm);
                                                    setTimeout(toggleSubTerm, 100);
                                                }

                                                function toggleRenewalType() {
                                                    var licenseType = document.getElementById('license_type');
                                                    var renewalRow = document.getElementById('renewal_type_row');
                                                    if (licenseType && renewalRow) {
                                                        if (licenseType.value === 'Renewal') {
                                                            renewalRow.style.display = 'flex';
                                                        } else {
                                                            renewalRow.style.display = 'none';
                                                        }
                                                    }
                                                }
                                                var lSelect = document.getElementById('license_type');
                                                if(lSelect) {
                                                    lSelect.addEventListener('change', toggleRenewalType);
                                                }

                                                // Handle potential ajax resets
                                                if (window.jQuery) {
                                                    $('body').on('change', '#product_select', toggleSubTerm);
                                                    $('body').on('change', '#license_type', toggleRenewalType);
                                                }
                                            });
                                            </script>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Expected closure date<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="date" name="expected_closure_date" id="datepicker-close-date" class="form-control" value="<?php echo htmlspecialchars($row['expected_closure_date'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="form-group row" id="renewal_type_row" style="<?php echo (($isEditMode ? ($row['license_type'] ?? '') : $licenseTypeUrl) === 'Renewal') ? '' : 'display:none;'; ?>">
                                                <label class="col-sm-5 col-form-label">Type of renewal</label>
                                                <div class="col-sm-7">
                                                    <select disabled name="renewal_type" id="renewal_type" class="form-control">
                                                        <option value="">---Select---</option>
                                                        <?php
                                                            $selectedRenewalType = $isEditMode ? ($row['renewal_type'] ?? '') : $renewalTypeUrl;
                                                        ?>
                                                        <option value="FTR" <?php echo ($selectedRenewalType === 'FTR') ? 'selected' : ''; ?>>FTR</option>
                                                        <option value="RR" <?php echo ($selectedRenewalType === 'RR') ? 'selected' : ''; ?>>RR</option>
                                                        <option value="Expansion" <?php echo ($selectedRenewalType === 'Expansion') ? 'selected' : ''; ?>>Expansion</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="card-subtitle mt-3">Opportunity Details</h5>
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Competition involved?<?php echo $isPartnerRole ? '<span class="text-danger">*</span>' : '' ?></label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="competition_involved" class="form-control" placeholder="Adobe, Foxit, etc." value="<?php echo htmlspecialchars($row['competition_involved'] ?? '', ENT_QUOTES, 'UTF-8') ?>" <?php echo $isPartnerRole ? 'required' : '' ?>>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Comment</label>
                                                <div class="col-sm-7">
                                                    <textarea name="comment" class="form-control" rows="2" placeholder=""><?php echo htmlspecialchars($commentFieldValue, ENT_QUOTES, 'UTF-8') ?></textarea>
                                                </div>
                                            </div>

                                            <?php if (!$isPartnerRole) { ?>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Lead Source</label>
                                                <div class="col-sm-7">
                                                    <select name="lead_source_id" class="form-control" >
                                                        <option value="">---Select---</option>
                                                        <?php
                                                            $leadSources = db_query("SELECT id, lead_source FROM lead_source WHERE status=1 ORDER BY lead_source ASC");
                                                            while ($sourceRow = db_fetch_array($leadSources)) {
                                                                $selectedSource = ((string) ($row['lead_source_id'] ?? '') === (string) $sourceRow['id']) ? 'selected' : '';
                                                                echo "<option value='{$sourceRow['id']}' {$selectedSource}>{$sourceRow['lead_source']}</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php } else { ?>
                                                <input type="hidden" name="lead_source_id" value="4">
                                            <?php } ?>

                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Deal stage<?php echo $isPartnerRole ? '<span class="text-danger">*</span>' : '' ?></label>
                                                <div class="col-sm-7">
                                                    <select name="stage_id" class="form-control" <?php echo $isPartnerRole ? 'required' : '' ?>>
                                                        <option value="">---Select---</option>
                                                        <?php
                                                            $stages = db_query("SELECT * FROM tbl_mst_stage WHERE status=1 ORDER BY name ASC");
                                                            while ($stage = db_fetch_array($stages)) {
                                                                $selectedStage = ((string) ($row['stage_id'] ?? '') === (string) $stage['id']) ? 'selected' : '';
                                                                echo "<option value='{$stage['id']}' {$selectedStage}>{$stage['name']}</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Proof of Engagement</label>
                                                <div class="col-sm-7">
                                                    <select name="proof_engagement_id" class="form-control">
                                                        <option value="">---Select---</option>
                                                        <?php
                                                            $proofs = db_query("SELECT * FROM tbl_mst_proof_engagement WHERE status=1 ORDER BY name ASC");
                                                            while ($proof = db_fetch_array($proofs)) {
                                                                $selectedProof = ((string) ($row['proof_engagement_id'] ?? '') === (string) $proof['id']) ? 'selected' : '';
                                                                echo "<option value='{$proof['id']}' {$selectedProof}>{$proof['name']}</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <?php
                                                $loggedInTeamId = (int) ($_SESSION['team_id'] ?? 0);
                                                if ($loggedInTeamId === 127) {
                                                    ?>
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Assigned to Partner</label>
                                                        <div class="col-sm-7">
                                                            <select name="partner_id" id="assigned_partner_id" class="form-control select2">
                                                                <option value="">---Select---</option>
                                                                <?php
                                                                $partners = db_query("SELECT id, name FROM partners WHERE status='Active' ORDER BY name ASC");
                                                                while ($p = db_fetch_array($partners)) {
                                                                    $selectedPartner = ((string)($row['partner_id'] ?? '') === (string)$p['id']) ? 'selected' : '';
                                                                    echo "<option value='{$p['id']}' {$selectedPartner}>{$p['name']}</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Align To</label>
                                                        <div class="col-sm-7">
                                                            <select name="align_to" id="align_to" class="form-control" data-selected-user="<?php echo htmlspecialchars($row['align_to'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                                                <option value="">---Select---</option>
                                                                <?php
                                                                if ($isEditMode && !empty($row['partner_id'])) {
                                                                    $partnerId = (int)$row['partner_id'];
                                                                    $alignUsers = db_query("SELECT u.id, u.name FROM users as u JOIN partners as p ON p.id = u.team_id WHERE p.id = $partnerId ORDER BY u.name ASC");
                                                                    while ($au = db_fetch_array($alignUsers)) {
                                                                        $selectedAlign = ((string)($row['align_to'] ?? '') === (string)$au['id']) ? 'selected' : '';
                                                                        echo "<option value='{$au['id']}' {$selectedAlign}>{$au['name']}</option>";
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php
                                                } else {
                                                    // Always post partner_id from session and keep it hidden per requirement
                                                    echo "<input type=\"hidden\" name=\"partner_id\" value=\"" . ($loggedInTeamId > 0 ? $loggedInTeamId : '') . "\">";
                                                    // Keep align_to hidden and null per requirement
                                                    echo "<input type=\"hidden\" name=\"align_to\" value=\"" . htmlspecialchars($row['align_to'] ?? '', ENT_QUOTES, 'UTF-8') . "\">";
                                                }
                                            ?>
                                            <?php if ($isManagerEditMode) {?>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Created By</label>
                                                <div class="col-sm-7">
                                                    <select name="user_id" id="created_by_user_id" class="form-control select2">
                                                        <option value="">---Select---</option>
                                                        <?php
                                                            foreach ($managerTeamUsers as $managerTeamUser) {
                                                                $createdBySelected = ((string) ($row['created_by'] ?? '') === (string) $managerTeamUser['id']) ? 'selected' : '';
                                                                echo "<option value='" . $managerTeamUser['id'] . "' " . $createdBySelected . ">" . $managerTeamUser['name'] . "</option>";
                                                            }
                                                            ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php }?>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Upload Image</label>
                                                <div class="col-sm-7">
                                                    <input type="file" name="upload_file" class="form-control" accept="image/*,application/pdf,.pdf,application/msword,.doc,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.docx">
                                                    <?php if (! empty($row['upload_file'])) {
                                                            $existingUpload    = $row['upload_file'];
                                                            $existingUploadExt = strtolower(pathinfo($existingUpload, PATHINFO_EXTENSION));
                                                            $isExistingImage   = in_array($existingUploadExt, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
                                                        ?>
                                                        <div class="mt-2">
                                                            <small class="text-muted d-block">Current File:</small>
                                                            <a href="<?php echo htmlspecialchars($existingUpload, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                                                                <?php echo htmlspecialchars(basename($existingUpload), ENT_QUOTES, 'UTF-8') ?>
                                                            </a>
                                                            <?php if ($isExistingImage) {?>
                                                                <div class="mt-1">
                                                                    <img src="<?php echo htmlspecialchars($existingUpload, ENT_QUOTES, 'UTF-8') ?>" alt="Uploaded file" style="max-width: 120px; height: auto; border: 1px solid #ddd; padding: 2px;">
                                                                </div>
                                                            <?php }?>
                                                        </div>
                                                    <?php }?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-items text-center mt-3">
                                    <button type="submit" data-toggle="modal" data-target="#myModal" id="form_data" class="btn btn-primary" style="margin-bottom:20px"><?php echo $isEditMode ? 'Update' : 'Submit' ?></button>
                                    <button type="button" onclick="javascript:history.go(-1)" class="btn btn-danger" style="margin-bottom:20px">Cancel</button>
                                </div>
                            </form>


                        </div>
                    </div>
                    </div>
                    </div>

                   </div>
                </div> <!-- end col -->


            </div> <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <?php include 'includes/footer.php'?>

    <?php //include('includes/footer.php') ?>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="js/validation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/bootstrap-slider.min.js"></script>

        <script>

    function stateChange(e, selectedCityId){
        var stateID = e;
        if(stateID){
            $.ajax({
                type:'POST',
                url:'ajaxcity.php',
                data:{state_id: stateID, selected_city_id: selectedCityId || ''},
                success:function(html){
                    $('#city_container').html(html);
                }
            });
        }
    }


    $(document).ready(function(){
    $('#region').on('change',function(){
        //alert("hi");
        var regionName = $(this).val();
        if(regionName){
            $.ajax({
                type:'POST',
                url:'ajaxindustry.php',
                data:'regionName='+regionName,
                success:function(html){
                    //alert(html);
                    $('#stateDiv').html(html);
                }
            });
        }
    });
    });

    </script>

    <script>
    $(document).ready(function() {
        var existingStateId = $('#state').val();
        var selectedCityId = $('#selected_city_id').val();
        if (existingStateId) {
            stateChange(existingStateId, selectedCityId);
        }

        var $createdByUser = $('#created_by_user_id');
        if ($createdByUser.length) {
            $createdByUser.data('lastValue', $createdByUser.val());
            $createdByUser.data('skipConfirm', false);

            $createdByUser.on('focus', function() {
                $(this).data('lastValue', $(this).val());
            });

            $createdByUser.on('change', function() {
                if ($(this).data('skipConfirm')) {
                    $(this).data('skipConfirm', false);
                    return;
                }

                var previousValue = $(this).data('lastValue');
                var currentValue = $(this).val();
                var $currentSelect = $(this);

                if (currentValue === previousValue) {
                    return;
                }

                swal({
                    title: 'Are you sure?',
                    text: 'Are you sure you want to change the user?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, change',
                    cancelButtonText: 'No'
                }, function(isConfirm) {
                    if (isConfirm) {
                        $currentSelect.data('lastValue', currentValue);
                        return;
                    }

                    $currentSelect.data('skipConfirm', true);
                    $currentSelect.val(previousValue).trigger('change.select2');
                });
            });
        }
    });
    </script>


    <script>
        $(document).ready(function() {
            var i = 1;
            var add_btn = $('.add_btn').val();
            $('#add').click(function() {
                i++;
                $('#dynamic_field').append('<div id="row' + add_btn + '"><div class="form-group row d-flex align-items-end"><div class="col-lg-2 mb-2"><label class="control-label">Full Name</label><input name="e_name[]" value="" type="text" value="" class="form-control" placeholder=""></div><div class="col-lg-2 mb-2"><label class="control-label">Email</label><input value="" name="e_email[]" type="email" class="form-control" placeholder=""></div><div class="col-lg-2 mb-2"><label class="control-label">Mobile</label><input type="text" minlength="10" maxlength="10" name="e_mobile[]" value="" class="form-control mob-validate" id="mobile-append'+i+'" onkeypress="return isNumberKey(event,this.id);" onkeyup="return mobZeroValidation(this.value,this.id);"></div><div class="col-lg-2 mb-2"><label class="control-label">Designation</label><input type="text" value="" name="e_designation[]" class="form-control" /></div><div class="col-sm-1 mb-2"><span data-repeater-delete="" name="remove" id="' + add_btn + '" class="btn btn-danger btn-sm btn_remove"><span class="fa fa-times mr-1"></span>Delete</span></div></div></div>');
                add_btn++;
            });
            $(document).on('click', '.btn_remove', function() {
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });
        });
        $(document).ready(function() {
            var i = 1;
            var add_btnQ = $('.add_btnQ').val();
            $('#addGradesQ').click(function() {
                i++;
                $('#dynamic_quanity').append('<div id="row' + add_btnQ + '"><div class="form-group row d-flex align-items-end mb-2"><label class="col-sm-2 col-form-label">Grade<span class="text-danger">*</span></label><div class="col-sm-3"><input name="grade[]" value="" type="number" required value="" class="form-control" placeholder=""></div><label class="col-sm-2 col-form-label">Students<span class="text-danger">*</span></label><div class="col-sm-3"><input value="" name="students[]" type="number" required class="form-control student-input" placeholder=""></div><div class="col-sm-2 "><span data-repeater-delete="" name="remove" id="' + add_btnQ + '" class="btn btn-danger btn-sm btn_removeQ"><span class="fa fa-times mr-1"></span>Delete</span></div></div></div>')
                add_btnQ++;
            });
            $(document).on('click', '.btn_removeQ', function() {
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });
        });

        // function updateStudentSum() {
        //     var sum = 0;

        //     $('.student-input').each(function() {
        //         var value = parseInt($(this).val()) || 0;
        //         sum += value;
        //     });
        //     document.getElementById("quantity").setAttribute('value',sum);
        //     // alert(sum);
        // }

        // updateStudentSum();

        // $('#dynamic_quanity').on('input', '.student-input', updateStudentSum);

        $('#dynamic_quanity').on('click', '.btn_removeQ', function() {
            var deletedValue = parseInt($(this).closest('.form-group').find('.student-input').val()) || 0;
            // var currentSum = parseInt($('.total-students').text()) || 0;
            currentSum = document.getElementById("quantity").value;
            var newSum = currentSum - deletedValue;
            document.getElementById("quantity").setAttribute('value',newSum);
        });

        function changeValue(e)
        {
            document.getElementById("quantity").setAttribute('value',e);
        }
    </script>


      <script>

     $(document).ready(function(){
            $('#check').click(function(){
             //alert($(this).is(':checked'));
                $(this).is(':checked') ? $('#pwd').attr('type', 'text') : $('#pwd').attr('type', 'password');
            });
        });
        $(document).ready(function() {
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
        });
        // For select 2


    });
    $("#ex6").slider();
    $("#ex6").on("slide", function(slideEvt) {
        $("#ex6SliderVal").text(slideEvt.value);
    });

    </script>
    <script>
$(document).ready(function(){

     var wfheight = $(window).height();

                  $('.add_lead_form').height(wfheight-380);



      $('.add_lead_form').slimScroll({
        color: '#00f',
        size: '10px',
       height: 'auto',


    });

});
$(function() {
    $('.datepicker').daterangepicker({

      "singleDatePicker": true,
    "showDropdowns": true,
     locale: {
      format: 'YYYY-MM-DD'
    },

    });
});


        function validateInput(inputField) {
            var inputValue = inputField.value;
            var containsNumbers = /\d/.test(inputValue); // Regular expression to check for numbers


        }

        $(document).ready(function() {
            // Height restriction removed to show all form fields
        });




      $(function() {

            $('#datetime').datepicker({
                format: 'yyyy-mm-dd',
                startDate: '-7d',
                // endDate: '0d',
                autoclose: !0
            });

			    $('.date_time').datepicker({
                format: 'yyyy-mm-dd',
                startDate: '-7d',
                // endDate: '0d',
                autoclose: !0
            });
        });

        function stateChange(state_id, selectedCityId) {
            if (state_id) {
                $.ajax({
                    type: 'POST',
                    url: 'ajaxcity.php',
                    data: {state_id: state_id, selected_city_id: selectedCityId || ''},
                    success: function(html) {
                        $('#city_container').html(html);
                    }
                });
            }
        }


    $('#school_board').on('change',function(){

        var board = $(this).val();
        if(board=='Others')
        {
            $("#other_board").prop('required',true);
            $("#other_board_div").css('display','flex');
        }
        else
        {
            $("#other_board").prop('required',false);
            $("#other_board_div").css('display','none');
            $("#other_board").val('');
        }

    });

    $("#is_group").change(function() {
        if(this.checked) {
            $("#group_name").prop('required',true);
            $("#group_name_div").css('display','flex');
        }
        else
        {
            $("#group_name").prop('required',false);
            $("#group_name_div").css('display','none');

        }
    });

</script>

<script>

        $('.mob-validate').on("blur",function (evt) {
           if (this.value.length < 10 && this.value.length > 1)
           {
              swal("Please enter valid mobile no");
              $('.mob-validate').trigger('focus');
           }
        });

       function mobZeroValidation(value,id)
       {
        //   alert(id);
           if(value[0] == 0){
                  swal("0 not allowed as first digit in mobile no");
                  $('#'+id).val('');
                  $('#'+id).trigger('focus');
               }
       }


        function isNumberKey(evt,id)
         {
            try{
                var charCode = (evt.which) ? evt.which : event.keyCode;

                if(charCode==46){
                    var txt=document.getElementById(id).value;
                    if(!(txt.indexOf(".") > -1)){

                        return false;
                    }
                }
                if (charCode > 31 && (charCode < 48 || charCode > 57) )
                    return false;

                return true;
            }catch(w){
                //alert(w);
            }
         }

         $('#user_attachment').on('change', function() {
            if(this.files[0].size / 1024 / 1024 > 4)
            {
                swal('Please Upload File Less Than 4MB!!')
                $('#user_attachment').val('');
                return false;
            }
            });

            $('#emailer_attachment').on('change', function() {
            if(this.files[0].size / 1024 / 1024 > 4)
            {
                swal('Please Upload File Less Than 4MB!!')
                $('#emailer_attachment').val('');
                return false;
            }
            });

            $(document).ready(function() {
            $('#lead_source').on('change', function() {
                //alert("hi");
                var leadsource = $(this).val();
                if (leadsource) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajaxsubLeadSource.php',
                        data: 'lead_source=' + leadsource,
                        success: function(html) {
                            //alert(html);
                            $('#sub_lead_source').html(html);
                        }
                    });
                }
            });
        });

        $(document).ready(function() {
                $('.multiselect_grade').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select Grade',
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeFilterClearBtn:true
                });
            });
    </script>

    <script>
       $(document).ready(function () {
    // Add a new clone
    $('#add-more').click(function () {
        // Clone the container
        var newClone = $('#clone-form-container').clone();

        // Reset all input fields in the clone
        newClone.find('input').val('');
        newClone.find('select').val('');

        // Show the "Remove" button in the cloned form
        newClone.find('.remove-clone').show();

        // Append the clone to the wrapper
        $('#clone-wrapper').append(newClone);
    });

    // Remove a clone on "Remove" button click
    $(document).on('click', '.remove-clone', function () {
        // Remove the closest .form-clone container
        $(this).closest('.form-clone').remove();
    });
});
    </script>



<script>

$(document).ready(function() {
    $('#group_name').select2({
        placeholder: "Select Group",
        allowClear: true,
        language: {
            noResults: function() {
                return `
                    <div style="text-align:center; padding:6px;">
                        <p>No Group Found</p>
                        <button type="button" class="btn btn-sm btn-primary" id="addNewGroupBtn">
                            + Add New Group
                        </button>
                    </div>
                `;
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    // 🔹 Case 1: Handle "Add New Group" option already present in dropdown
    $('#group_name').on('select2:select', function(e) {
        var data = e.params.data;
        if (data.id === "__add_new__") {
            $('#group_name').val(null).trigger('change');
            openAddGroupPrompt();
        }
    });

    // 🔹 Case 2: Handle "Add New Group" button when no results found
    $(document).on('mousedown', '#addNewGroupBtn', function(e) {
        e.stopPropagation(); // Prevent Select2 from closing immediately
    });

    $(document).on('click', '#addNewGroupBtn', function(e) {
        e.preventDefault();
        $('#group_name').select2('close');
        setTimeout(() => openAddGroupPrompt(), 200); // Delay before prompt
    });

    // 🔹 Shared function for both cases
    function openAddGroupPrompt() {
        var newGroup = prompt("Enter new group name:");
        if (newGroup) {
            var exists = false;
            $('#group_name option').each(function() {
                if ($(this).text().toLowerCase() === newGroup.toLowerCase()) {
                    exists = true;
                    return false;
                }
            });

            if (exists) {
                toastr.info("Duplicate record: Group name already exists.");
                $('#group_name').val(null).trigger('change');
                return;
            }

            $.post('ajax_update.php', { name: newGroup, type: 'add_group' }, function(response) {
                if (response && response.id) {
                    var newOption = new Option(newGroup, response.id, true, true);
                    $('#group_name').append(newOption).trigger('change');
                    toastr.success("Group added successfully!");
                } else {
                    toastr.error("Error: Unable to add group. Please try again.");
                }
            }, 'json');
        } else {
            $('#group_name').val(null).trigger('change');
        }
    }

    // 🔹 Cascading Dropdowns for Product Fields
    $('#product_interest_select').on('change', function() {
        var poiId = $(this).val();
        var $productSelect = $('#product_select');
        var $subProductSelect = $('#sub_product_select');
        var $descriptionSelect = $('#description_select');

        // Clear children
        $productSelect.html('<option value="">Loading...</option>');
        $subProductSelect.html('<option value="">---Select---</option>');
        $descriptionSelect.html('<option value="">---Select---</option>');

        if (poiId) {
            $.post('ajaxProduct.php', { poi_id: poiId }, function(html) {
                $productSelect.html(html);
                $productSelect.trigger('change');
            });
        } else {
            $productSelect.html('<option value="">---Select---</option>');
        }
    });

    $('#product_select').on('change', function() {
        var productId = $(this).val();
        var $subProductSelect = $('#sub_product_select');
        var $descriptionSelect = $('#description_select');

        $subProductSelect.html('<option value="">Loading...</option>');
        $descriptionSelect.html('<option value="">---Select---</option>');

        if (productId) {
            $.post('ajaxProduct.php', { product: productId }, function(html) {
                var $newSelect = $(html).find('select');
                if ($newSelect.length) {
                    $subProductSelect.html($newSelect.html());
                } else {
                    $subProductSelect.html('<option value="">---Select---</option>');
                }
                $subProductSelect.trigger('change');
            });
        } else {
            $subProductSelect.html('<option value="">---Select---</option>');
        }
    });

    $('#sub_product_select').on('change', function() {
        var subProductId = $(this).val();
        var $descriptionSelect = $('#description_select');

        $descriptionSelect.html('<option value="">Loading...</option>');

        if (subProductId) {
            $.post('ajax_common.php', { pivot_id: subProductId }, function(html) {
                var $newSelect = $(html).find('select');
                if ($newSelect.length) {
                    $descriptionSelect.html($newSelect.html());
                } else {
                    $descriptionSelect.html('<option value="">---Select---</option>');
                }
            });
        } else {
            $descriptionSelect.html('<option value="">---Select---</option>');
        }
    });

    // 🔹 For disabled selects with hidden inputs (ensure hidden inputs update if user enables via console or if logic changes)
    $('select[disabled]').each(function() {
        var $sel = $(this);
        var name = $sel.attr('name');
        var val = $sel.val();
        if (name && val && !$('input[type="hidden"][name="' + name + '"]').length) {
            $sel.after('<input type="hidden" name="' + name + '" value="' + val + '">');
        }
    });
    function loadAlignUsers(partnerId, selectedUser) {
        var $alignSelect = $('#align_to');
        if (!partnerId) {
            $alignSelect.html('<option value="">---Select---</option>');
            return;
        }
        $.ajax({
            type: 'POST',
            url: 'ajax_update.php',
            data: {
                action: 'get_partner_users',
                partner_id: partnerId,
                selected_user: selectedUser || ''
            },
            success: function(html) {
                $alignSelect.html(html);
            },
            error: function() {
                $alignSelect.html('<option value="">---Select---</option>');
            }
        });
    }

    $('#assigned_partner_id').on('change', function() {
        loadAlignUsers($(this).val());
    });
    if ($('#assigned_partner_id').length && $('#assigned_partner_id').val()) {
        loadAlignUsers($('#assigned_partner_id').val(), $('#align_to').data('selected-user') || $('#align_to').val());
    }
});
</script>