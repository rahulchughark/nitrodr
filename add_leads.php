<?php 
include('includes/header.php');
include_once('helpers/DataController.php');
include('includes/audit_log_helper.php');

$dataObj = new DataController;

$leadId = isset($_GET['lead']) ? intval($_GET['lead']) : 0;
$typeId = isset($_GET['type']) ? intval($_GET['type']) : 0;
$productInterest = isset($_GET['product_of_interest']) ? trim((string)$_GET['product_of_interest']) : '';
$licenseType = isset($_GET['license_type']) ? trim((string)$_GET['license_type']) : '';
$renewalType = isset($_GET['renewal_type']) ? trim((string)$_GET['renewal_type']) : '';

$productName = '';
$subProductName = '';

if ($leadId > 0) {
    $productRes = db_query("SELECT product_name FROM tbl_product WHERE id='" . $leadId . "' LIMIT 1");
    if ($productRes && mysqli_num_rows($productRes) > 0) {
        $productRow = db_fetch_array($productRes);
        $productName = $productRow['product_name'];
    }
}

$isSubscription = (stripos((string)$productName, 'Subscription') !== false);

if ($typeId > 0) {
    $subProductRes = db_query("SELECT product_type FROM tbl_product_pivot WHERE id='" . $typeId . "' LIMIT 1");
    if ($subProductRes && mysqli_num_rows($subProductRes) > 0) {
        $subProductRow = db_fetch_array($subProductRes);
        $subProductName = $subProductRow['product_type'];
    }
}

?>

<?php if(isset($_POST['r_name']) && $_POST['r_name'] != '')
{




     
    $current_date = date('Y-m-d H:i:s');
    $gradesSigned = $_POST['grade_signed_up'] ? implode(", ", $_POST['grade_signed_up']) : '';
    
    $schoolboard = $_POST['school_board'] == 'Others' ? $_POST['other_board'] : $_POST['school_board'];

    // Handle file upload for upload_file
    $uploadFileName = '';
    if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] === UPLOAD_ERR_OK) {
        $tmpPath = $_FILES['upload_file']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['upload_file']['name'], PATHINFO_EXTENSION));
        $allowedImageExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowedDocExt = ['doc', 'docx', 'pdf'];
        $allowed = array_merge($allowedImageExt, $allowedDocExt);
        $isImageFile = in_array($ext, $allowedImageExt, true);

        if (in_array($ext, $allowed, true) && (!$isImageFile || @getimagesize($tmpPath))) {
            $uploadDir = __DIR__ . '/uploads/leads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = uniqid('lead_', true) . '.' . $ext;
            if (move_uploaded_file($tmpPath, $uploadDir . $fileName)) {
                $uploadFileName = 'uploads/leads/' . $fileName;
            }
        }
    }

    // INSERT query for add mode
    $postedCityId = $_POST['city_id'] ?? ($_POST['city'] ?? '');

    // Determine created_by_category from session (no DB query)
    $sessionRole = $_SESSION['role'] ?? '';
    $insertCreatedByCategory = ($sessionRole === 'Partner') ? 'Partner' : 'Internal';
    $leadComment = mysqli_real_escape_string($GLOBALS['dbcon'], trim((string)($_POST['comment'] ?? '')));
    $leadProductInterest = mysqli_real_escape_string($GLOBALS['dbcon'], trim((string)($_POST['product_interest'] ?? '')));
    $ordersCommentColumn = '';
    if (!empty(getSingleresult("SHOW COLUMNS FROM orders LIKE 'add_comment'"))) {
        $ordersCommentColumn = 'add_comment';
    } elseif (!empty(getSingleresult("SHOW COLUMNS FROM orders LIKE 'comment'"))) {
        $ordersCommentColumn = 'comment';
    }
    $ordersProductInterestColumn = '';
    if (!empty(getSingleresult("SHOW COLUMNS FROM orders LIKE 'product_interest'"))) {
        $ordersProductInterestColumn = 'product_interest';
    } elseif (!empty(getSingleresult("SHOW COLUMNS FROM orders LIKE 'product_of_interest'"))) {
        $ordersProductInterestColumn = 'product_of_interest';
    }
    $commentInsertColumns = ($ordersCommentColumn !== '') ? ", " . $ordersCommentColumn : '';
    $commentInsertValues = ($ordersCommentColumn !== '') ? ", '" . $leadComment . "'" : '';
    $productInterestInsertColumns = ($ordersProductInterestColumn !== '') ? ", " . $ordersProductInterestColumn : '';
    $productInterestInsertValues = ($ordersProductInterestColumn !== '') ? ", '" . $leadProductInterest . "'" : '';

    $res = db_query("INSERT INTO orders (
        customer_company_name, customer_name, email, phone, designation, 
        state_id, city_id, address, industry_id, existing_nitro_customer, 
        product, sub_product, number_of_licenses, subscription_term, expected_closure_date, 
        competition_involved".$commentInsertColumns.$productInterestInsertColumns.", lead_source_id, stage_id, proof_engagement_id, partner_id, align_to,
        upload_file, status, created_by_category,created_by, created_at, description, license_type, renewal_type
    ) VALUES (
        '".$_POST['customer_company_name']."', 
        '".$_POST['customer_name']."', 
        '".$_POST['email']."', 
        '".$_POST['phone']."', 
        '".$_POST['designation']."',
        '".$_POST['state_id']."', 
        '".$postedCityId."', 
        '".$_POST['address']."',
        '".$_POST['industry_id']."', 
        '".$_POST['existing_nitro_customer']."', 
        '".$_POST['product']."',
        '".$_POST['sub_product']."',
        '".$_POST['number_of_licenses']."', 
        '".$_POST['subscription_term']."', 
        '".$_POST['expected_closure_date']."',
        '".$_POST['competition_involved']."'".$commentInsertValues.$productInterestInsertValues.",
        '".$_POST['lead_source_id']."',
        '".$_POST['stage_id']."', 
        '".$_POST['proof_engagement_id']."', 
        '".$_POST['partner_id']."', 
        '".$_POST['align_to']."',
        '".$uploadFileName."',
        '1', 
        '".$insertCreatedByCategory."',
        '".$_SESSION['user_id']."',
        '".$current_date."',
        '".$_POST['description']."',
        '".($_POST['license_type'] ?? '')."',
        '".($_POST['renewal_type'] ?? '')."'
    )");
    
    $lead_id = get_insert_id();       

        if($res)
        {
            $last_inserted_id = $lead_id;

            $createdBy = (int)($_SESSION['user_id'] ?? 0);
            db_query("INSERT INTO lead_modify_log (`lead_id`, `type`, `stage`, `previous_name`, `modify_name`, `created_date`, `created_by`, `log_status`, `timestamp`, `created_by_clm`) VALUES ('".$lead_id."', 'Lead', NULL, 'N/A', 'Lead', NOW(), '".$createdBy."', 'Active', NOW(), '0')");
            
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

            // Email notification to the lead creator for new lead.
            $creatorEmail = trim((string)($_SESSION['email'] ?? ''));
            if (filter_var($creatorEmail, FILTER_VALIDATE_EMAIL)) {
                $creatorName = trim((string)($_SESSION['name'] ?? 'User'));
                
                // Get product name for email
                $productId = intval($_POST['product']);
                $pName = trim((string)getSingleresult("SELECT product_name FROM tbl_product WHERE id='".$productId."' LIMIT 1"));
                $pName = ($pName !== '') ? $pName : 'N/A';

                $mailPayload = [
                    'lead_id' => $lead_id,
                    'creator_name' => $creatorName,
                    'company_name' => (string)($_POST['customer_company_name'] ?? 'N/A'),
                    'customer_name' => (string)($_POST['customer_name'] ?? 'N/A'),
                    'product_name' => $pName,
                    'licenses' => (string)($_POST['number_of_licenses'] ?? 'N/A'),
                    'expected_closure_date' => (string)($_POST['expected_closure_date'] ?? 'N/A'),
                    'created_at' => date('d-m-Y h:i A')
                ];

                $setSubject = "New Lead Created [#".$lead_id."]";
                $mailBody = $dataObj->buildLeadCreationEmailTemplate($mailPayload);

                ob_start();
                sendMailReminder($creatorEmail, $setSubject, $mailBody);
                ob_end_clean();
            }

            redir("admin_leads.php?add=success",true);
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

                                    <small class="text-muted">Home > Add Lead</small>
                                    <h4 class="font-size-14 m-0 mt-1">Add Lead</h4>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <!-- <form method="post" action="#" class="form-horizontal" enctype="multipart/form-data" id="form_activity">
                            
                            </form> -->

                           
                            <form method="post" action="#" class="form-horizontal" id="form_activity" enctype="multipart/form-data">
                                <input type="hidden" value="<?= $_SESSION['user_id'] ?>" name="user_id">
                                <input type="hidden" value="<?= $_SESSION['team_id'] ?>" name="team_id">
                                <input type="hidden" value="<?= $_SESSION['name'] ?>" name="r_user">
                                <input type="hidden" value="<?= $_SESSION['email'] ?>" name="r_email">
                                <input type="hidden" value="<?= getSingleresult("select name from partners where id='".$_SESSION['team_id']."'") ?>" name="r_name">
                                
                                <div class="add_lead">
                                    <h5 class="card-subtitle">Customer Details</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Customer Company Name<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="customer_company_name" class="form-control" placeholder="" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Customer Name<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="customer_name" class="form-control" placeholder="" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Email<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="email" name="email" class="form-control" placeholder="" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Phone Number<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="phone" maxlength="10" minlength="10" class="form-control" placeholder="" required onkeypress="return isNumberKey(event,this.id);">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Designation<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="designation" class="form-control" placeholder="" required>
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
                                                            echo "<option value='{$st['id']}'>{$st['name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">City<span class="text-danger">*</span></label>
                                                <div class="col-sm-7" id="city">
                                                    <select name="city_id" class="form-control" required>
                                                        <option value="">---Select---</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Address<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <textarea name="address" class="form-control" rows="2" required></textarea>
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
                                                            echo "<option value='{$ind['id']}'>{$ind['name']}</option>";
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
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                        <option value="Not Sure">Not Sure</option>
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
                                                    <select <?php echo (!empty($_GET['product_of_interest'])) ? 'disabled' : ''; ?> name="product_interest" id="product_interest_select" class="form-control" >
                                                        <option value="">---Select---</option>
                                                        <?php
                                                        $selectedPoiId = isset($_GET['product_of_interest']) ? intval($_GET['product_of_interest']) : ($row['product_interest'] ?? '');
                                                        $poiRes = db_query("SELECT id, name FROM tbl_product_poi WHERE status=1 ORDER BY name ASC");
                                                        while ($poi = db_fetch_array($poiRes)) {
                                                            $selected = ((string)$selectedPoiId === (string)$poi['id']) ? 'selected' : '';
                                                            echo "<option value='{$poi['id']}' {$selected}>{$poi['name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php if (!empty($_GET['product_of_interest'])) echo '<input type="hidden" name="product_interest" value="' . htmlspecialchars($selectedPoiId, ENT_QUOTES, 'UTF-8') . '">'; ?>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Sub Product<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <select <?php echo (!empty($_GET['type']) || $isSubscription) ? 'disabled' : ''; ?> name="sub_product" id="sub_product_select" class="form-control" required >
                                                        <option value="">---Select---</option>
                                                        <?php
                                                        $selectedProductId = isset($_GET['lead']) ? intval($_GET['lead']) : ($row['product'] ?? '');
                                                        $selectedSubProductId = isset($_GET['type']) ? intval($_GET['type']) : ($row['sub_product'] ?? '');
                                                        if ($selectedProductId) {
                                                            $subProducts = db_query("SELECT id, product_type FROM tbl_product_pivot WHERE product_id='".$selectedProductId."' AND status=1 ORDER BY product_type ASC");
                                                            while ($sub = db_fetch_array($subProducts)) {
                                                                $selected = ((string)$selectedSubProductId === (string)$sub['id']) ? 'selected' : '';
                                                                echo "<option value='{$sub['id']}' {$selected}>{$sub['product_type']}</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php if (!empty($_GET['type']) || $isSubscription) echo '<input type="hidden" name="sub_product" value="' . htmlspecialchars($selectedSubProductId, ENT_QUOTES, 'UTF-8') . '">'; ?>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Number of licenses<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="number" name="number_of_licenses" min="1" class="form-control" placeholder="" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Product<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                   <select <?php echo (!empty($_GET['lead'])) ? 'disabled' : ''; ?> name="product" id="product_select" class="form-control" required >
                                                        <option value="">---Select---</option>
                                                        <?php
                                                        $products = db_query("SELECT id, product_name FROM tbl_product WHERE status=1 ORDER BY product_name ASC");
                                                        $selectedProductId = isset($_GET['lead']) ? intval($_GET['lead']) : ($row['product'] ?? '');
                                                        while ($prod = db_fetch_array($products)) {
                                                            $selected = ((string)$selectedProductId === (string)$prod['id']) ? 'selected' : '';
                                                            echo "<option value='{$prod['id']}' {$selected}>{$prod['product_name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php if (!empty($_GET['lead'])) echo '<input type="hidden" name="product" value="' . htmlspecialchars($selectedProductId, ENT_QUOTES, 'UTF-8') . '">'; ?>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Product Description</label>
                                                <div class="col-sm-7">
                                                    <select <?php echo (!empty($_GET['description']) || $isSubscription) ? 'disabled' : ''; ?> name="description" id="description_select" class="form-control">
                                                    <option value="">---Select---</option>
                                                    <?php
                                                        $selectedDescriptionId = isset($_GET['description']) ? intval($_GET['description']) : '';
                                                        $descRes               = db_query("SELECT id, description FROM tbl_product_description WHERE status=1 ORDER BY description ASC");
                                                        while ($desc = db_fetch_array($descRes)) {
                                                        $selected = ((string) $selectedDescriptionId === (string) $desc['id']) ? 'selected' : '';
                                                        echo "<option value='{$desc['id']}' {$selected}>{$desc['description']}</option>";
                                                        }
                                                    ?>
                                                    </select>
                                                    <?php if (!empty($_GET['description']) || $isSubscription) echo '<input type="hidden" name="description" value="' . htmlspecialchars($selectedDescriptionId, ENT_QUOTES, 'UTF-8') . '">'; ?>
                                             </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">License Type</label>
                                                <div class="col-sm-7">
                                                    <select <?php echo (!empty($licenseType)) ? 'disabled' : ''; ?> name="license_type" class="form-control">
                                                        <option value="">---Select---</option>
                                                        <option value="Fresh" <?php echo ($licenseType == 'Fresh') ? 'selected' : ''; ?>>Fresh</option>
                                                        <option value="Renewal" <?php echo ($licenseType == 'Renewal') ? 'selected' : ''; ?>>Renewal</option>
                                                        <option value="Expansion" <?php echo ($licenseType == 'Expansion') ? 'selected' : ''; ?>>Expansion</option>
                                                    </select>
                                                    <?php if (!empty($licenseType)) echo '<input type="hidden" name="license_type" value="' . htmlspecialchars($licenseType, ENT_QUOTES, 'UTF-8') . '">'; ?>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="renewal_type_row" style="<?php echo ($licenseType == 'Renewal') ? '' : 'display:none;'; ?>">
                                                <label class="col-sm-5 col-form-label">Type of renewal</label>
                                                <div class="col-sm-7">
                                                    <select <?php echo (!empty($licenseType)) ? 'disabled' : ''; ?> name="renewal_type" class="form-control">
                                                        <option value="">---Select---</option>
                                                        <option value="FTR" <?php echo ($renewalType == 'FTR') ? 'selected' : ''; ?>>FTR</option>
                                                        <option value="RR" <?php echo ($renewalType == 'RR') ? 'selected' : ''; ?>>RR</option>
                                                        <option value="Expansion" <?php echo ($renewalType == 'Expansion') ? 'selected' : ''; ?>>Expansion</option>
                                                    </select>
                                                    <?php if (!empty($licenseType)) echo '<input type="hidden" name="renewal_type" value="' . htmlspecialchars($renewalType, ENT_QUOTES, 'UTF-8') . '">'; ?>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="subscription_term_container">
                                                <label class="col-sm-5 col-form-label">Subscription term<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <select name="subscription_term" id="subscription_term" class="form-control" required>
                                                        <option value="">---Select---</option>
                                                        <option value="1">1 year</option>
                                                        <option value="3">3 years</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <script>
                                            document.addEventListener("DOMContentLoaded", function() {
                                                function toggleSubTerm() {
                                                    var prod = document.getElementById('product_select');
                                                    var termContainer = document.getElementById('subscription_term_container');
                                                    var termSelect = document.getElementById('subscription_term');
                                                    
                                                    var subProdSelect = document.getElementById('sub_product_select');
                                                    var descSelect = document.getElementById('description_select');
                                                    var productName = prod.options[prod.selectedIndex] ? prod.options[prod.selectedIndex].text.toLowerCase() : '';
                                                    var isSubscription = productName.includes('subscription');

                                                    if(isSubscription) {
                                                        if(subProdSelect) subProdSelect.disabled = true;
                                                        if(descSelect) descSelect.disabled = true;
                                                    } else {
                                                        // Only enable if they are not forced disabled by URL params
                                                        var urlHasType = "<?php echo !empty($_GET['type']) ? '1' : '0'; ?>";
                                                        var urlHasDesc = "<?php echo !empty($_GET['description']) ? '1' : '0'; ?>";
                                                        if(subProdSelect && urlHasType !== '1') subProdSelect.disabled = false;
                                                        if(descSelect && urlHasDesc !== '1') descSelect.disabled = false;
                                                    }

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
                                                // Handle potential ajax resets
                                                if (window.jQuery) {
                                                    $('body').on('change', '#product_select', toggleSubTerm);
                                                }
                                            });
                                            </script>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Expected closure date<span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="date" name="expected_closure_date" id="datepicker-close-date" class="form-control" required autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="card-subtitle mt-3">Opportunity Details</h5>
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Competition involved?</label>
                                                <div class="col-sm-7">
                                                    <input type="text" name="competition_involved" class="form-control" placeholder="Adobe, Foxit, etc." >
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Comment</label>
                                                <div class="col-sm-7">
                                                    <textarea name="comment" class="form-control" rows="2" placeholder=""></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Lead Source</label>
                                                <div class="col-sm-7">
                                                    <select name="lead_source_id" id="lead_source_id" class="form-control">
                                                        <option value="">---Select---</option>
                                                        <?php
                                                        $leadSources = db_query("SELECT id, lead_source FROM lead_source WHERE status=1 ORDER BY lead_source ASC");
                                                        while ($sourceRow = db_fetch_array($leadSources)) {
                                                            echo "<option value='{$sourceRow['id']}'>{$sourceRow['lead_source']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Deal stage</label>
                                                <div class="col-sm-7">
                                                    <select name="stage_id" class="form-control" >
                                                        <option value="">---Select---</option>
                                                        <?php
                                                        $stages = db_query("SELECT * FROM tbl_mst_stage WHERE status=1 ORDER BY name ASC");
                                                        while ($stage = db_fetch_array($stages)) {
                                                            echo "<option value='{$stage['id']}'>{$stage['name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Proof of Engagement</label>
                                                <div class="col-sm-7">
                                                    <select name="proof_engagement_id" class="form-control" >
                                                        <option value="">---Select---</option>
                                                        <?php
                                                        $proofs = db_query("SELECT * FROM tbl_mst_proof_engagement WHERE status=1 ORDER BY name ASC");
                                                        while ($proof = db_fetch_array($proofs)) {
                                                            echo "<option value='{$proof['id']}'>{$proof['name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Assigned to Partner</label>
                                                <div class="col-sm-7">
                                                    <select name="partner_id" id="assigned_partner_id" class="form-control select2">
                                                        <option value="">---Select---</option>
                                                        <?php
                                                        $partners = db_query("SELECT id, name FROM partners WHERE status='Active' ORDER BY name ASC");
                                                        while ($p = db_fetch_array($partners)) {
                                                            echo "<option value='{$p['id']}'>{$p['name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Align To</label>
                                                <div class="col-sm-7">
                                                    <select name="align_to" id="align_to" class="form-control">
                                                        <option value="">---Select---</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Upload Image</label>
                                                <div class="col-sm-7">
                                                    <input type="file" name="upload_file" class="form-control" accept="image/*,application/pdf,.pdf,application/msword,.doc,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.docx">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-items text-center mt-3">
                                    <button type="submit" data-toggle="modal" data-target="#myModal" id="form_data" class="btn btn-primary" style="margin-bottom:20px">Submit</button>
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
    <?php include('includes/footer.php') ?>
    
    <?php //include('includes/footer.php') ?>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="js/validation.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/bootstrap-slider.min.js"></script>

        <script>
                    
    function stateChange(e){
        var stateID = e;
        if(stateID){
            $.ajax({
                type:'POST',
                url:'ajaxindustry.php',
                data:'state_idd='+stateID,
                success:function(html){
                    console.log(html);
                    $('#city').html(html);
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

        function updateStudentSum() {
            var sum = 0;

            $('.student-input').each(function() {
                var value = parseInt($(this).val()) || 0;
                sum += value;
            });
            document.getElementById("quantity").setAttribute('value',sum);
            // alert(sum);
        }

        updateStudentSum();

        $('#dynamic_quanity').on('input', '.student-input', updateStudentSum);

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

        function stateChange(state_id) {
            if (state_id) {
                $.ajax({
                    type: 'POST',
                    url: 'ajaxcity.php',
                    data: 'state_id=' + state_id,
                    success: function(html) {
                        $('#city').html(html);
                    }
                });
            }
        }

        });


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
        loadAlignUsers($(this).val(), '');
    });

    if ($('#assigned_partner_id').val()) {
        loadAlignUsers($('#assigned_partner_id').val(), $('#align_to').val());
    }

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
});
</script>