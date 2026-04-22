<?php
include('includes/header.php');

admin_page(); ?>
<?php

$_POST['eid'] = intval($_POST['eid']);
$eid = $_GET['eid'] ?? 0;

if ($_POST['name']) {
//     echo "<br><br><br><br>";
//     error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
    // print_r($_POST);die;                    
    // if ($_FILES["agreement"]) {
    //     //print_r($_FILES); die;
    //     if (!file_exists('uploads/agreements')) {
    //         mkdir('uploads/agreements', 0777, true);
    //     }
    //     $target_dir = "uploads/agreements/";
    //     $file_name = basename($_FILES["agreement"]["name"]);
    //     $target_file = $target_dir . $file_name;
    //     $uploadOk = 1;
    //     $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    //     if ($_FILES["agreement"]["size"] > 10000000) {
    //         echo "<script>alert('Sorry, your file is too large!')</script>";
    //         redir("add_partner.php", true);
    //     } else {
    //         move_uploaded_file($_FILES["agreement"]["tmp_name"], $target_file);
    //     }
    // }
    $product = isset($_POST['product_id']) && is_array($_POST['product_id']) ? implode(',', $_POST['product_id']) : '';
    $states = isset($_POST['states']) && is_array($_POST['states']) ? implode(',', $_POST['states']) : '';
   
    if ($_POST['eid']) {
        $logoUpdateSql = ''; // will be appended only if logo uploaded

        /*  LOGO UPLOAD (OPTIONAL ON UPDATE) */
        if (!empty($_FILES['logo']['name']) && $_FILES['logo']['error'] == 0) {

            $allowedExt = ['png', 'jpg', 'jpeg', 'webp'];
            $maxSize    = 2 * 1024 * 1024; // 2MB

            $fileName = $_FILES['logo']['name'];
            $tmpName  = $_FILES['logo']['tmp_name'];
            $fileSize = $_FILES['logo']['size'];
            $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExt)) {
                die("Invalid logo format. Only PNG/JPG/WebP allowed.");
            }

            if ($fileSize > $maxSize) {
                die("Logo size must be less than 2MB.");
            }

            $uploadDir = "uploads/partner_logo/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            /* 🔹 DELETE OLD LOGO (OPTIONAL BUT RECOMMENDED) */
            $oldLogoRes = db_query("SELECT logo FROM partners WHERE id = {$eid}");
            if ($old = db_fetch_array($oldLogoRes)) {
                if (!empty($old['logo']) && file_exists($old['logo'])) {
                    unlink($old['logo']);
                }
            }

            $newName  = time() . '_' . uniqid() . '.' . $ext;
            $logoPath = $uploadDir . $newName;

            if (!move_uploaded_file($tmpName, $logoPath)) {
                die("Failed to upload logo.");
            }

            // append logo update only when uploaded
            $logoUpdateSql = ", `logo`='" . mysqli_real_escape_string($GLOBALS['dbcon'], $logoPath) . "'";
        }

        $res = db_query("UPDATE `partners` SET 
        `reseller_id`='" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['reseller_id']) . "',`name`='" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['name']) . "',
        `shortname`='" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['shortname']) . "',
        `product_id`='" . mysqli_real_escape_string($GLOBALS['dbcon'], $product) . "',
        `states_access`='" . mysqli_real_escape_string($GLOBALS['dbcon'], $states) . "',
        `address`='" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['address']) . "',
        `region`='" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['region']) . "',
        `state`='" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['state']) . "',
        `city`='" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['city']) . "',
        `pin`='" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['pin']) . "',
        `country`='" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['country']) . "',
        `status`='" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['status']) . "'
        {$logoUpdateSql}
        WHERE id='" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['eid']) . "'");
        if ($res) {

            redir("manage_partners.php?update=success", true);
        }
    } else {

        $logoPath = '';

        /*  LOGO UPLOAD (OPTIONAL) */
        if (!empty($_FILES['logo']['name']) && $_FILES['logo']['error'] == 0) {

            $allowedExt = ['png', 'jpg', 'jpeg', 'webp'];
            $maxSize    = 2 * 1024 * 1024; // 2MB

            $fileName = $_FILES['logo']['name'];
            $tmpName  = $_FILES['logo']['tmp_name'];
            $fileSize = $_FILES['logo']['size'];
            $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExt)) {
                die("Invalid logo format. Only PNG/JPG/WebP allowed.");
            }

            if ($fileSize > $maxSize) {
                die("Logo size must be less than 2MB.");
            }

            $uploadDir = "uploads/partner_logo/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newName  = time() . '_' . uniqid() . '.' . $ext;
            $logoPath = $uploadDir . $newName;

            if (!move_uploaded_file($tmpName, $logoPath)) {
                die("Failed to upload logo.");
            }
        }

        $res = db_query("insert into partners (`name`, `shortname`,`product_id`,reseller_id, `address`, `region`, `state`, `city`, `pin`, `country`,`createdby`,status,states_access,logo) VALUES ('" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['name']) . "','" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['shortname']) . "','" . mysqli_real_escape_string($GLOBALS['dbcon'], $product) . "','" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['reseller_id']) . "','" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['address']) . "','" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['region']) . "','" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['state']) . "','" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['city']) . "','" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['pin']) . "','" . mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['country']) . "','" . $_SESSION['user_id'] . "','".$_POST['status']."','".$states."','".$logoPath."')");



        if ($res) {

            redir("manage_partners.php?add=success", true);
        }
    }
}

if ($eid) {
    $data = db_fetch_array(db_query("select * from partners where id='" . $eid . "'"));  
    @extract($data);
}


?>
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <div class="media bredcrum-title">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home > Add Partner</small>
                                    <h4 class="font-size-14 m-0 mt-1">Add Partner</h4>
                                </div>
                            </div>


                            <form method="post" action="#" class="form-horizontal" enctype="multipart/form-data" onsubmit="return handleFormSubmit()">

                                <div  class="add_lead">
                                    <h5 class="card-subtitle">Company Info</h5>


                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-2">Name<span class="text-danger">*</span></label>
                                                <div class="col-md-10 controls">
                                                    <input name="name" type="text" value="<?= $name ?>" id="nameP" class="form-control" placeholder="" required data-validation-required-message="This field is required" oninput="validateInputs()">
                                                    <small class="form-control-feedback">Full partner company name</small>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-lg-6 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-2">Short Name<span class="text-danger">*</span></label>
                                                <div class="col-md-10 controls">
                                                    <input name="shortname" value="<?= $shortname ?>" id="sNameP" type="text" required data-validation-required-message="This field is required" class="form-control form-control" placeholder="" oninput="validateInputs()">
                                                    <small class="form-control-feedback">Company Short Name</small> </div>
                                            </div>
                                        </div>

                                        <!--/span-->
                                        <div class="col-lg-4 mb-3 d-none">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">Partner ID</label>
                                                <div class="col-md-9 controls">
                                                    <input name="reseller_id" value="<?= $reseller_id ?>" id="pId" type="text" class="form-control form-control" placeholder="" oninput="validateInputs()">
                                                    <small class="form-control-feedback">SFDC ID</small> </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row d-none">
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">Products</label>
                                                <div class="col-md-9 controls">
                                                    <select name="product_id[]" class=" form-control" multiple id="multiselect" onchange="validateInputs()">         
                                                        <?php $query = db_query("select * from tbl_product where status=1");
                                                        while ($row = db_fetch_array($query)) { ?>
                                                            <option <?= (in_array($row['id'], explode(',', $product_id)) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= $row['product_name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                                <div class="form-group row">
                                                    <label class="control-label text-md-right col-md-3">States Access</label>
                                                    <div class="col-md-9 controls">
                                                        <select name="states[]" class=" form-control" multiple id="multiselectStates" onchange="validateInputs()">    
                                                            <?php $query = db_query("select * from states order by name");
                                                            while ($row = db_fetch_array($query)) { ?>
                                                                <option <?= (in_array($row['id'], explode(',', $states_access)) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                                <!-- Logo Upload (Optional) -->
                                            <div class="col-lg-4 mb-3">
                                                <div class="form-group row">
                                                    <label class="control-label text-md-right col-md-3">
                                                        Logo
                                                        
                                                    </label>
                                                    <div class="col-md-9 controls">       
                                                        <small class="text-muted">
                                                            Optional · Allowed: JPG, PNG, WEBP<br>
                                                            Max size: <b>200 × 200 px</b> (Recommended)
                                                        </small>                                                 
                                                        <input type="file" 
                                                            name="logo" 
                                                            class="form-control"
                                                            accept="image/png,image/jpg,image/jpeg,image/webp">

                                                            <?php if (!empty($logo) && file_exists($logo)) { ?>
                                                                <div class="mb-2">
                                                                    <img 
                                                                        src="<?= htmlspecialchars($logo) ?>" 
                                                                        alt="Partner Logo"
                                                                        onclick="openLogoModal('<?= htmlspecialchars($logo) ?>')"
                                                                        style="
                                                                            max-width: 120px;
                                                                            max-height: 120px;
                                                                            border: 1px solid #ddd;
                                                                            padding: 4px;
                                                                            border-radius: 6px;
                                                                            background: #fff;
                                                                            cursor: pointer;
                                                                        ">
                                                                </div>
                                                            <?php } ?>

                                                        
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <!--/row-->

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">Country</label>
                                                <div class="col-md-9">
                                                    <input type="text" value="India" name="country" class="form-control" readonly style="cursor: not-allowed;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">Region<span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <select name="region" id="region" required class="form-control" onchange="validateInputs()">
                                                        <option value=''>---Select---</option>
                                                        <?php 
                                                        $regionRes = db_query("select * from region where status=1 order by region");
                                                        while ($reg = db_fetch_array($regionRes)) { ?>
                                                            <option <?= (($region == $reg['id']) ? 'selected' : '') ?> value='<?= $reg['id'] ?>'><?= $reg['region'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">State<span class="text-danger">*</span></label>
                                                <div class="col-md-9" id="state_container">
                                                    <select name="state" id="state" required class="form-control">
                                                        <option value=''>---Select---</option>
                                                        <?php 
                                                        $stateRes = db_query("select * from states order by name");
                                                        while ($st = db_fetch_array($stateRes)) { ?>
                                                            <option <?= (($state == $st['id']) ? 'selected' : '') ?> value='<?= $st['id'] ?>'><?= $st['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">City<span class="text-danger">*</span></label>
                                                <div class="col-md-9" id="city_container">
                                                   <select name="city" class="form-control" id="city" onchange="validateInputs()">
                                                       <option value="">Select City</option>
                                                       <?php if($city) { ?>
                                                           <option value="<?= $city ?>" selected><?= $city ?></option>
                                                       <?php } ?>
                                                   </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">Pin Code</label>
                                                <div class="col-md-9">
                                                    <input name="pin" type="number" value="<?= $pin ?>" class="form-control" onkeyup="validateInputs()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">Status<span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <select name="status" required class="form-control" onchange="validateInputs()">
                                                        <option value="">---Select---</option>
                                                        <option <?= (($status == 'Active') ? 'selected' : '') ?> value="Active">Active</option>
                                                        <option <?= (($status == 'InActive') ? 'selected' : '') ?> value="InActive">InActive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-1">Address<span class="text-danger">*</span></label>
                                                <div class="col-md-11 controls">
                                                    <input name="address" value="<?= $address ?>" required data-validation-required-message="This field is required" type="text" class="form-control" id="addressP" oninput="validateInputs()">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                       <!--  <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">CDGS Seats Target Per Month</label>
                                                <div class="col-md-9">
                                                    <input type="number" min="0" name="cdgs_target" id="cdgs_target" value="<?= $cdgs_target ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div> -->
                                        
                                        <!--/span-->
                                        <input type="hidden" value="<?= $eid ?>" name="eid" />
                                        <!--/span-->
                                        <!-- <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">Agreement</label>
                                                <div class="col-md-9">
                                                    <input type="file" accept=".pdf" name="agreement" value="<?= $agreement ?>" class="form-control">
                                                    <?php if ($agreement) { ?>
                                                        <a href="uploads/agreements/<?= $agreement ?>" target="_blank">View</a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                        <div class="row">
                                            <!-- <div class="col-lg-4 mb-3">
                                                <div class="form-group row">
                                                    <label class="control-label text-md-right col-md-3">Sales Manager</label>
                                                    <div class="col-md-9">
                                                        <select name="sm_user" class="form-control">
                                                            <option value="0">---Select---</option>
                                                            <?php $sm_query = db_query("select id,name from users where status='Active' and sales_manager='1' ");
                                                            while ($sm_data = db_fetch_array($sm_query)) { ?>
                                                                <option <?= (($sm_data['id'] == $sm_user) ? 'selected' : '') ?> value="<?= $sm_data['id'] ?>"><?= $sm_data['name'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div> -->

                                        </div>
                                </div>
                                <div class="button-items text-center">
                                    <button type="submit" class="btn btn-primary  mt-2" disabled id="submitBtn">Submit</button>
                                    <button type="button" onclick="location.href = 'manage_partners.php'" class="btn btn-danger mt-2">Cancel</button>
                                </div>


                            </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>


<!-- Logo Preview Modal -->
<div class="modal fade" id="logoPreviewModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Logo Preview</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <img id="logoPreviewImage"
                     src=""
                     alt="Logo Preview"
                     style="
                        max-width: 100%;
                        max-height: 70vh;
                        border-radius: 8px;
                        border: 1px solid #ddd;
                     ">
            </div>

        </div>
    </div>
</div>


        <?php include('includes/footer.php') ?>
        <script src="js/validation.js"></script>
        <script>
            $(document).ready(function() {
                function loadCities(stateID, selectedCity = '') {
                    if (stateID) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajax_common.php',
                            data: { 
                                state_id: stateID, 
                                selected_city: selectedCity,
                                select_name: 'city',
                                select_id: 'city'
                            },
                            success: function(html) {
                                $('#city_container').html(html);
                                validateInputs(); // Re-validate after loading
                            }
                        });
                    } else {
                        $('#city_container').html('<select name="city" class="form-control" id="city"><option value="">Select state first</option></select>');
                    }
                }

                $('#region').on('change', function() {
                    validateInputs();
                });

                $(document).on('change', '#state', function() {
                    loadCities($(this).val());
                    validateInputs();
                });

                $(document).on('change', '#city', function() {
                    validateInputs();
                });

                // Initial load for edit mode
                var initialState = $('#state').val();
                var initialCity = "<?= $city ?? '' ?>";

                if (initialState) {
                    loadCities(initialState, initialCity);
                }
            });

            $(document).ready(function() {
                $('#multiselect').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });
            });
            $(document).ready(function() {
                $('#multiselectStates').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });
            });

            function fill_seats(val) {
                if (val == 'Platinum') {
                    $('#cdgs_target').val(80);
                } else if (val == 'Gold') {
                    $('#cdgs_target').val(50);
                } else if (val == 'Silver') {
                    $('#cdgs_target').val(25);
                } else if (val == 'Bronze') {
                    $('#cdgs_target').val(10);
                }


            }
        </script>
		    <script>
                $(document).ready(function () {
                    var wfheight = $(window).height();
                    var windowWidth = $(window).width();

                    if (windowWidth <= 768) {
                        // Mobile devices
                        $('.add_lead').height(wfheight - 240); // Adjust as needed
                    } else {
                        // Desktop or tablet
                        $('.add_lead').height(wfheight - 280);
                    }
                });

            function validateInputs() {
                const submitBtn = document.getElementById('submitBtn');
                const nameP = document.getElementById('nameP');
                const sNameP = document.getElementById('sNameP');
                const addressP = document.getElementById('addressP');
                const cityP = document.getElementById('city');
                const stateAddress = document.getElementById('state');
                const regionP = document.getElementById('region');
                
                if (nameP.value.trim() !== '' && 
                    sNameP.value.trim() !== '' && 
                    addressP.value.trim() !== '' && 
                    regionP.value.trim() !== '' && 
                    stateAddress.value.trim() !== '' && 
                    cityP.value.trim() !== '') {
                    submitBtn.disabled = false;
                } else {
                    submitBtn.disabled = true;
                }
            }

            function handleFormSubmit() {
                const btn = document.getElementById('submitBtn');
                btn.disabled = true;
                btn.innerText = 'Submitting...';
                return true;
            }

            // function stagechange()
            // {
            //     $id = <?= $_GET['eid'] ?>;
            //     if($id){
            //         submitBtn.disabled = false;
            //     }
            // }
    </script>
    <script>
function openLogoModal(src) {
    document.getElementById('logoPreviewImage').src = src;
    $('#logoPreviewModal').modal('show');
}
</script>