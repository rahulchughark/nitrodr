<?php include("includes/include.php"); ?>
<?php
admin_protect();

include_once('helpers/DataController.php');
$modify_log = new DataController();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Nitro DR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Nitro DR" name="description" />
    <meta content="Nitro DR" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="images/nitro-logo.png">
    <link href="css/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
    <!-- DataTables -->

    <link href="css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap Css -->
    <link href="css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <!-- Custom Css-->
    <link href="css/custom.css" id="app-style" rel="stylesheet" type="text/css" />
    <!-- <link href="css/colors/antigravity.css" id="theme" rel="stylesheet" type="text/css" /> -->
    <!-- ION Slider -->
    <link href="css/ion.rangeSlider.min.css" rel="stylesheet" type="text/css" />
    <link href="css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css" />

    <link href="css/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<style>
.animated-modal {
  border-radius: 15px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  animation: slideInFade 0.6s ease-out;
}

/* body {background: whitesmoke;text-align: center;}*/
        button{background-color: darkslategrey;color: white;border: 0;font-size: 18px;font-weight: 500;border-radius: 7px;padding: 10px 10px;cursor: pointer;white-space: nowrap;}
        #success{background: green;}
        #error{background: red;}
        #warning{background: coral;}
        #info{background: cornflowerblue;}
        #question{background: grey;}

@keyframes slideInFade {
  from {
    transform: translateY(-30px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.pulse {
  animation: pulseAnimation 1.5s infinite;
}

@keyframes pulseAnimation {
  0% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.1);
    opacity: 0.8;
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

select.form-control:disabled {
    cursor: not-allowed !important;
    background-color: #e9ecef !important;
    border: 1px dotted #a8abb1 !important;
    opacity: 0.6 !important;
    color: #6c757d !important;
}

#productType .col-md-12, #productDescription .col-md-12 {
    padding: 0 !important;
    margin-top: 0 !important; /* overrides mt-2 from ajax_common.php */
}
</style>


</head>

<body data-topbar="dark" data-layout="horizontal">

    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner-chase">
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <?php if ($_SESSION['user_type'] == 'CLR' || $_SESSION['user_type']=='TEAM LEADER' || $_SESSION['user_type']=='DA') { ?>
                            <a href="dashboard_new.php" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="images/nitro-logo.png" alt="" height="35">
                                </span>
                                <span class="logo-lg">
                                    <img src="images/nitro-logo.png" alt="" height="35">
                                </span>
                            </a>
                        <?php } else if($_SESSION['user_type'] == 'MNGR' || $_SESSION['user_type'] == 'USR'){ ?>
                            <a href="welcome.php" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="images/nitro-logo.png" alt="" height="35">
                                </span>
                                <span class="logo-lg">
                                    <img src="images/nitro-logo.png" alt="" height="35">
                                </span>
                            </a>
                            <?php }else if($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN'){ ?>
                            <a href="dashboard_new.php" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="images/nitro-logo.png" alt="" height="35">
                                </span>
                                <span class="logo-lg">
                                    <img src="images/nitro-logo.png" alt="" height="35">
                                </span>
                            </a>
                            <?php }else { ?>
                            <a href="welcome.php" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="images/nitro-logo.png" alt="" height="35">
                                </span>
                                <span class="logo-lg">
                                    <img src="images/nitro-logo.png" alt="" height="35">
                                </span>
                            </a>
                        <?php } ?>

                    </div>
                    <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light" data-toggle="collapse" data-target="#topnav-menu-content">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->



<div class="d-flex col-sm-3 col-md-5 global-search">
  <form action="global_search.php" method="GET" class="col-md-12" autocomplete="off">
    <div class="input-group">
      <input 
        name="search"
        id="globalSearchInput"
        type="text"
        class="form-control"
        placeholder="Search"
        aria-label="Search for..."
        value="<?= htmlspecialchars($_REQUEST['search'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
      >
      <span class="input-group-append">
        <button class="btn btn-light" type="submit"><i class="fas fa-search"></i></button>
      </span>

      <!-- Dynamic Suggestion Dropdown -->
      <div id="searchDropdown" class="search-dropdown" style="display: none;"></div>
    </div>
  </form>
</div>

                <?php
                if (isset($_POST['save_activity_markup'])) {
                    $res = activityLogs($_POST['pid'], $_POST['remarks'], $_POST['call_subject'], $_SESSION['user_id']);
                    $update_status = db_query("Update follow_up_notification set status='Completed' where lead_id=" . $_POST['pid'] . " and follow_up_date='" . $_POST['follow_up_date'] . "' and follow_up_time='" . $_POST['follow_up_time'] . "'");
                }

                if (isset($_POST['reschedule_reminder'])) {
                    if (!getSingleresult("select id from follow_up_notification where status='Not Started' and lead_id=" . $_POST['lead_id'] . " and company_name='" . $_POST['company_name'] . "'")) {

                        $log = [
                            'lead_id'          => intval($_POST['lead_id']),
                            'follow_type'      => htmlspecialchars($_POST['follow_type'], ENT_QUOTES),
                            'follow_up_date'   => $_POST['follow_up_date'],
                            'follow_up_time'   => $_POST['follow_up_time'],
                            'comments'         => htmlspecialchars($_POST['comments'], ENT_QUOTES),
                            'reminder_time'    => $_POST['reminder'],
                            'user_id'          => $_SESSION['user_id'],
                            'status'           => 'Not Started',
                            'company_name'     => $_POST['company_name']
                        ];
                        //print_r($log);
                        $res = $modify_log->insert($log, "follow_up_notification");
                    } else {
                        $delete_query = db_query("delete from follow_up_notification where status='Not Started' and lead_id=" . $_POST['lead_id'] . " and follow_up_date='" . $_POST['follow_up_date'] . "' and follow_up_time='" . $_POST['follow_up_time'] . "' and company_name='" . $_POST['company_name'] . "' and follow_type='" . $_POST['follow_type'] . "'");

                        $log = [
                            'lead_id'          => intval($_POST['lead_id']),
                            'follow_type'      => htmlspecialchars($_POST['follow_type'], ENT_QUOTES),
                            'follow_up_date'   => $_POST['follow_up_date'],
                            'follow_up_time'   => $_POST['follow_up_time'],
                            'comments'         => htmlspecialchars($_POST['comments'], ENT_QUOTES),
                            'reminder_time'    => $_POST['reminder'],
                            'user_id'          => $_SESSION['user_id'],
                            'status'           => 'Not Started',
                            'company_name'     => $_POST['company_name']
                        ];
                        //print_r($log);
                        $res = $modify_log->insert($log, "follow_up_notification");
                    }
                }

                if (isset($_POST['overdue_status'])) {
                    $update_status = db_query("Update follow_up_notification set status='Overdue' where lead_id=" . $_POST['lead_id'] . " and follow_up_date='" . $_POST['follow_up_date'] . "' and follow_up_time='" . $_POST['follow_up_time'] . "' and follow_type='" . $_POST['follow_type'] . "' and status='Not Started'");
                }


            // $whatsappNotication = db_query("select description,id from whatsapp_notification where mobile = ".'4521632541'." AND seen = 0");

            ?>
                <div class="d-flex">
                  <div class="whatsapp-notification" onclick="return showNotificationBar()" id="whatsapp-notification">
                  </div>
                    <?php if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'REVIEWER' || $_SESSION['user_type'] == 'SUPERADMIN' || $_SESSION['user_type'] == 'RADMIN' || $_SESSION['user_type'] == 'OPERATIONS') {

                        $notification_admin = db_query("select id from lead_notification where sender_type='Partner' and is_read=0"); ?>
                        <!-- <div class="dropdown d-inline-block">

                            <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="lead_notify">
                                <i class="mdi mdi-bell-outline"></i>

                                <span class="badge badge-danger badge-pill notify"><?= mysqli_num_rows($notification_admin) ?></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0" aria-labelledby="page-header-notifications-dropdown">
                                <div class="p-3">
                                    <h6 class="m-0">Notifications (<?= mysqli_num_rows($notification_admin) ?>) </h6>
                                </div>

                                <div data-simplebar style="max-height: 230px;">
                                    <div id="notif_dropdown" class="notif_dropdown"></div>

                                </div>


                            </div>
                        </div> -->
                    <?php } else if ($_SESSION['user_type'] == 'USR') {
                        $notification_count = db_query("select id from lead_notification where sender_type='Admin' and receiver_id =" . $_SESSION['user_id'] . " and is_read=0");
                    ?>
                        <!-- <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="lead_notify_usr">
                                <i class="mdi mdi-bell-outline"></i>
                                <span class="badge badge-danger badge-pill notify"><?= mysqli_num_rows($notification_count) ?> </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0" aria-labelledby="page-header-notifications-dropdown">
                                <div class="p-3">
                                    <h6 class="m-0">Notifications (<?= mysqli_num_rows($notification_count) ?>) </h6>
                                </div>

                                <div data-simplebar style="max-height: 230px;">
                                    <div id="notif_dropdown_usr"></div>


                                </div>


                            </div>
                        </div> -->
                    <?php } ?>


                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="images/user.png" alt="User">
                            <span class=" d-xl-inline-block ml-1"><?= $_SESSION['name'] ?></span>
                           
                            <!--<i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>-->
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <!-- item-->
                            <?php $agreement = getSingleresult("select agreement from partners where id='" . $_SESSION['team_id'] . "'");
                            if ($_SESSION['role'] == 'BO' && $agreement) {
                            ?>

                                <a class="dropdown-item" href="uploads/agreements/<?= $agreement ?>" target="_blank"><i class="ti-clip" target="_blank"></i>View Agreement</a>
                            <?php } ?>
                            
                            <a class="dropdown-item text-center" href="#">
                                <img class="rounded-circle header-profile-user" src="images/user.png" alt="User"><br>
                                <?= $_SESSION['email'] ?><br>
                                (<?= $modify_log->getFullRoleName($_SESSION['user_type']) ?>)
                               
                            </a>

                            <a class="dropdown-item" href="change_password.php"><i class="dripicons-lock d-inline-block text-muted mr-2"></i> Change Password</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php"><i class="dripicons-exit d-inline-block text-muted mr-2"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <?php $agreement = getSingleresult("select agreement from partners where id='" . $_SESSION['team_id'] . "'");
        if ($_SESSION['role'] == 'BO' && $agreement) {
        ?>
            <li role="separator" class="divider"></li>
            <li><a href="uploads/agreements/<?= $agreement ?>" target="_blank"><i class="ti-clip" target="_blank"></i>View Agreement</a></li>
        <?php } ?>

        <div class="topnav">
            <div class="container-fluid">
                <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
                    <div class="collapse navbar-collapse" id="topnav-menu-content">
                        <ul class="navbar-nav">
                            <?php

                            $expAccess = explode(',', user_access($_SESSION['user_id'], $_SESSION['role_id']));

                            $moduleLevel1 = get_modules_by_level();


                           foreach ($moduleLevel1 as $moduleLevel1) {
                            //  print_r($moduleLevel1);
                            // exit;
                                if (in_array($moduleLevel1['id'], $expAccess)) {
                                    echo '<li class="nav-item dropdown"> <a id="topnav-dashboard"  href="' . $moduleLevel1['url'] . '"  ' . (($moduleLevel1['url'] == '#') ? 'class="nav-link dropdown-toggle arrow-none" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : 'class="nav-link dropdown-toggle arrow-none" title="' . $moduleLevel1['name'] . '"') . ' >' .
                                        $moduleLevel1['name'] .
                                        (($moduleLevel1['url'] == '#') ? '<i class="mdi mdi-chevron-down  d-xl-inline-block"> </i></a>' : '<i class=" mdi d-none"> </i></a>');
                                    if ($moduleLevel1['url'] == '#') {
                                        echo '<div class="dropdown-menu dropdown-menu-left" aria-labelledby="topnav-dashboard">';

                                        $moduleLevel2 = get_modules_by_level($moduleLevel1['id']);

                                        foreach ($moduleLevel2 as $moduleLevel2) {
                                            if (in_array($moduleLevel2['id'], $expAccess)) {
                                                echo ' <a href="' . $moduleLevel2['url'] . '" onClick="' . $moduleLevel2['is_function'] . '" class="dropdown-item" title="' . $moduleLevel2['name'] . '">' . '
                               ' . $moduleLevel2['name'] . '
                               </a>';
                                            }
                                            //print_r($moduleLevel2['name']);
                                        }
                                        echo '</div>';
                                    }
                                    echo '</li>';
                                }
                            }


                            ?>
                    </div>
                    </li>
                    </ul>
            </div>
            </nav>
        </div>
    </div>
    <!-- End Sidebar scroll-->
    <!-- Bottom points-->
    <div class="sidebar-footer">
        <!-- item--><a href="change_password.php" class="link" id="change_password" data-toggle="tooltip" title="Change Password"><i class="ti-lock"></i></a>

        <!-- item--><a href="logout.php" class="link" data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a> </div>
    <!-- End Bottom points-->
    </aside>
    <?php if ($_SESSION['user_type'] != 'ADMIN' || $_SESSION['user_type'] != 'SUPERADMIN') { ?>
        <div id="product_data" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title">Select Product </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <?php $isPartnerRole = (strtoupper((string) ($_SESSION['role'] ?? '')) === 'PARTNER'); ?>
                    <form class="product_data" id="product_select_form">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="license_type" class="control-label">License Type</label>
                                    <select name="license_type" class="form-control" id="license_type">
                                        <option value="">---Select---</option>
                                        <option value="Fresh">Fresh</option>
                                        <option value="Renewal">Renewal</option>
                                        <option value="Expansion">Expansion</option>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group" id="renewal_type_section" style="display:none;">
                                    <label for="renewal_type" class="control-label">Type of renewal</label>
                                    <select name="renewal_type" class="form-control" id="renewal_type">
                                        <option value="">---Select---</option>
                                        <option value="FTR">FTR</option>
                                        <option value="RR">RR</option>
                                        <option value="Expansion">Expansion</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="product_of_interest" class="control-label">Product of Interest</label>
                                    <select name="product_of_interest" class="form-control" id="product_of_interest">
                                        <option value="">---Select---</option>
                                        <?php
                                        $res_poi = db_query("SELECT id, name FROM tbl_product_poi WHERE status=1 ORDER BY id ASC");
                                        while ($row_poi = db_fetch_array($res_poi)) { ?>
                                            <option value="<?= $row_poi['id']; ?>"><?= $row_poi['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="product" class="control-label">Product</label>
                                    <select name="product" class="form-control" id="product" disabled>
                                        <option value="">---Select---</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group" id="productType">
                                    <div class="col-md-12" style="padding:0">
                                        <label for="product_type" class="control-label">Sub Product</label>
                                        <select name="sub_product" class="form-control" disabled>
                                            <option value="">---Select---</option>
                                        </select>
                                    </div>
                                    <!-- Sub Product options are loaded dynamically from tbl_product_pivot by product_id -->
                                    <script>
                                    // Mutation observer to rename select name if loaded as 'product_type'
                                    new MutationObserver(function() {
                                        var sel = document.querySelector('#productType select[name="product_type"]');
                                        if(sel) sel.setAttribute('name', 'sub_product');
                                    }).observe(document.getElementById('productType'), { childList: true, subtree: true });
                                    </script>
                                </div>

                                <div class="col-md-6 form-group" id="productDescription">
                                    <label for="product_description" class="control-label">Description</label>
                                    <select name="description" class="form-control" disabled>
                                        <option value="">---Select---</option>
                                    </select>
                                </div>
                            </div>

                            <div id="terms_and_conditions_section" style="display:none;">
                                <div class="form-group mt-3">
                                    <label>
                                        <strong>Terms & Conditions</strong>
                                    </label>
                                    <div style="font-size: 13px; line-height: 1.6;">
                                        <p>• A perpetual license is non-reactivatable and cannot be reissued or restored in the event of a system failure, crash, or loss of the original installation.</p>
                                        <p>• Customer must choose either a perpetual or a subscription license. A mixed environment is not supported within the same account.</p>
                                        <p>• Subscription customer is not eligible for Perpetual.</p>
                                    </div>
                                    <div class="mt-2">
                                        <input type="checkbox" id="accept_terms">
                                        <label for="accept_terms">I agree to the above terms and conditions</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                    <div class="modal-footer justify-content-center">
                        <button type="button" name="submit" id="submit_btn" class="btn btn-primary" disabled>Submit</button>
                    </div>
                    <script>
                    // Enable submit button only if all selects have value and checkbox is checked
                    function checkProductFormValidity() {
                        var isPartnerRole = <?= $isPartnerRole ? 'true' : 'false' ?>;
                        var productOfInterest = document.getElementById('product_of_interest').value;
                        var productSelect = document.getElementById('product');
                        var product = productSelect.value;
                        var productName = productSelect.options[productSelect.selectedIndex] ? productSelect.options[productSelect.selectedIndex].text : '';
                        
                        var isSubscription = productName.toLowerCase().includes('subscription');
                        
                        var productTypeDiv = document.getElementById('productType');
                        var productDescriptionDiv = document.getElementById('productDescription');
                        
                        if (isSubscription) {
                            if (productTypeDiv) productTypeDiv.style.display = 'none';
                            if (productDescriptionDiv) productDescriptionDiv.style.display = 'none';
                        } else {
                            if (productTypeDiv) productTypeDiv.style.display = 'block';
                            if (productDescriptionDiv) productDescriptionDiv.style.display = 'block';
                        }
                        
                        var licenseType = document.getElementById('license_type').value;
                        var renewalTypeSection = document.getElementById('renewal_type_section');
                        var renewalTypeInput = document.getElementById('renewal_type');
                        
                        if (licenseType === 'Renewal') {
                            renewalTypeSection.style.display = 'block';
                        } else {
                            renewalTypeSection.style.display = 'none';
                            if (renewalTypeInput) renewalTypeInput.value = '';
                        }
                        
                        var renewalType = renewalTypeInput ? renewalTypeInput.value : '';
                        
                        var termsSection = document.getElementById('terms_and_conditions_section');
                        var acceptTermsCheckbox = document.getElementById('accept_terms');
                        
                        // Show T&C only for Partner role + Perpetual product (value 1)
                        var shouldShowTerms = (isPartnerRole && product === '1');
                        
                        if (termsSection) {
                            if (shouldShowTerms) {
                                termsSection.style.display = 'block';
                            } else {
                                termsSection.style.display = 'none';
                                if (acceptTermsCheckbox) acceptTermsCheckbox.checked = false; // Reset if hidden
                            }
                        }

                        var acceptTermsValid = shouldShowTerms ? (acceptTermsCheckbox && acceptTermsCheckbox.checked) : true;

                        // Check for sub product and description if present
                        var subProduct = document.querySelector('#productType select');
                        var subProductValid = (subProduct && !isSubscription) ? subProduct.value !== '' : true;
                        var description = document.querySelector('#productDescription select');
                        var descriptionValid = (description && !isSubscription) ? description.value !== '' : true;
                        
                        var licenseTypeValid = licenseType !== '';
                        var renewalTypeValid = (licenseType === 'Renewal') ? (renewalType !== '') : true;
                        
                        var enable = productOfInterest !== '' && product !== '' && subProductValid && descriptionValid && acceptTermsValid && licenseTypeValid && renewalTypeValid;
                        document.getElementById('submit_btn').disabled = !enable;
                    }
                    document.getElementById('product_of_interest').addEventListener('change', checkProductFormValidity);
                    document.getElementById('product').addEventListener('change', checkProductFormValidity);
                    document.getElementById('accept_terms').addEventListener('change', checkProductFormValidity);
                    document.getElementById('license_type').addEventListener('change', checkProductFormValidity);
                    document.getElementById('renewal_type').addEventListener('change', checkProductFormValidity);
                    // Listen for dynamically loaded selects
                    var observer = new MutationObserver(function() {
                        var subProduct = document.querySelector('#productType select');
                        var description = document.querySelector('#productDescription select');
                        if (subProduct) subProduct.addEventListener('change', checkProductFormValidity);
                        if (description) description.addEventListener('change', checkProductFormValidity);
                    });
                    observer.observe(document.getElementById('productType'), { childList: true, subtree: true });
                    observer.observe(document.getElementById('productDescription'), { childList: true, subtree: true });
                    </script>
                </div>

            </div>

        </div>

    <?php } ?>

<!-- Animated Reminder Modal -->
<div class="modal fade" id="iphoneModal" tabindex="-1" role="dialog" aria-labelledby="iphoneModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content text-center p-4 animated-modal">

      <div class="modal-body">
        <div class="icon-wrapper mb-3">
          <i class="fas fa-bell text-warning fa-3x pulse"></i>
        </div>

        <h5 class="mb-3 text-dark font-weight-bold">📅 Schedule</h5>
        <p><strong>Date:</strong> <span id="reminderDate"></span> <span id="reminderTime"></span></p>

        <p><strong>School:</strong> <span id="reminderSchoolName"></span></p>
        <p><strong>Subject:</strong> <span id="reminderSubject"></span></p>
        <p><strong>Remark:</strong> <span id="reminderRemark"></span></p>

        <button type="button" class="btn btn-info px-5 mt-3 shadow" data-dismiss="modal">OK</button>
      </div>

    </div>
  </div>
</div>




    <div id="selfReview" class="modal modal_review" role="dialog" data-backdrop="static" data-keyboard="false">

    </div>
    <div id="follow_up" class="modal modal_review" role="dialog" data-backdrop="static" data-keyboard="false">

    </div>
    <div id="mark_complete" class="modal modal_review" role="dialog" data-backdrop="static" data-keyboard="false">

    </div>
    <!-- ============================================================== -->
    <!-- End Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.querySelector("input[name='search']");
            const dropdown   = document.getElementById("searchDropdown");

            searchInput.addEventListener("input", function () {
                if (this.value.trim() !== "") {
                    dropdown.classList.add("active");
                } else {
                    dropdown.classList.remove("active");
                }
            });

            // Optional: hide dropdown when clicking outside
            document.addEventListener("click", function (e) {
                if (!dropdown.contains(e.target) && e.target !== searchInput) {
                    dropdown.classList.remove("active");
                }
            });
        });
    </script>
    <script>
        //for admin
        $(document).ready(function() {
            $('#lead_notify').on('click', function() {
                $('.count').html('');
                load_unseen_notification('yes');
            });

            function load_unseen_notification(view = '') {
                $.ajax({
                    url: "notify_lead.php",
                    method: "POST",
                    data: {
                        view: view
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#notif_dropdown').html(data.notification);
                        if (data.unseen_notification > 0) {
                            $('.count').html(data.unseen_notification);
                        }
                    }
                });
            }
            load_unseen_notification();
        });

        //for partner
        $(document).ready(function() {
            $('#lead_notify_usr').on('click', function() {
                $('.count_usr').html('');
                load_notification_usr('yes');
            });

            function load_notification_usr(view = '') {
                $.ajax({
                    url: "notifyLead_partner.php",
                    method: "POST",
                    data: {
                        view: view
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#notif_dropdown_usr').html(data.notification);
                        if (data.unseen_notification > 0) {
                            $('.count_usr').html(data.unseen_notification);
                        }
                    }
                });
            }
            load_notification_usr();
        });

        function updateNotificationPartner(id, user_id, type_id) {
            //alert(id);
            $.ajax({
                type: 'POST',
                url: 'notifyLead_partner.php',
                data: {
                    id: id,
                    user_id: user_id,
                    type_id: type_id
                },
                success: function(data) {
                    window.location.href = 'partner_view.php?id=' + type_id
                },
            });
        }
    </script>

    <script>
        $('.whatsapp-notification > a').on('click', function() {
            $('.whatspp-dropdown-main').addClass('active');
        });
        $('.close-notif').on('click', function() {
            $('.whatspp-dropdown-main').removeClass('active');
        });

        
    </script>
    <script>
$(document).ready(function() {
  $('#globalSearchInput').on('keyup', function() {
    const query = $(this).val().trim();
    const dropdown = $('#searchDropdown');

    if (query.length < 2) {
      dropdown.hide().empty();
      return;
    }
    // alert(query);
    $.ajax({
      url: 'ajax_global_search_suggestions.php',
      type: 'GET',
      data: { search: query },
      success: function(response) {
        dropdown.html(response).show();
      }
    });
  });

  // Hide dropdown when clicking outside
  $(document).click(function(e) {
    if (!$(e.target).closest('.global-search').length) {
      $('#searchDropdown').hide();
    }
  });
});
</script>
