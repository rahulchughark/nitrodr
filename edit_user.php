<?php include('includes/header.php');
admin_page(); ?>

<style>
    .status-toggle-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 38px;
    }

    .status-toggle {
        position: relative;
        width: 52px;
        height: 28px;
        display: inline-block;
        cursor: pointer;
    }

    .status-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .status-slider {
        position: absolute;
        inset: 0;
        border-radius: 34px;
        background-color: #f46a6a;
        transition: .25s;
    }

    .status-slider:before {
        content: '';
        position: absolute;
        height: 22px;
        width: 22px;
        left: 3px;
        top: 3px;
        border-radius: 50%;
        background: #fff;
        transition: .25s;
    }

    .status-toggle input:checked + .status-slider {
        background-color: #34c38f;
    }

    .status-toggle input:checked + .status-slider:before {
        transform: translateX(24px);
    }

    .status-text {
        font-weight: 600;
    }
</style>

<?php

if ($_POST['access']) {
    $access = implode(",", $_POST['access']);
} else {
    $access = '';
}

if ($_GET['id'] != '') {
    $data = db_query("select * from users where id='" . $_GET['id'] . "'");
    $user_data = db_fetch_array($data);
}
if($_POST['user_type'] == 'CLR' || $_POST['user_type'] == 'RCLR' || $_POST['user_type'] == 'TEAM LEADER' || $_POST['user_type'] == 'RENEWAL TL'){
    $checkCaller = getSingleresult("SELECT user_type FROM users where id=".$_POST['user_id']);
    if(!$checkCaller != $_POST['user_type']){
        $checkInCaller = db_query("SELECT id from callers where user_id=".$_POST['user_id']);
        if(mysqli_num_rows($checkInCaller) == 0){
            $add_caller = db_query("INSERT INTO `callers`(`name`, `user_id`, `created_date`) VALUES ('" . $_POST['name'] . "','" . $_POST['user_id'] . "','" . date('Y-m-d H:i:s') . "')");
        }
    }
}
if ($_POST['user_id'] != '') {

    $pass = $_POST['password']?md5($_POST['password']):$user_data['password'];
    $status = ($_POST['status'] == 'Active') ? 'Active' : 'InActive';

    $sql = db_query("update users set email='" . $_POST['email'] . "', name='" . $_POST['name'] . "', mobile='" . $_POST['mobile'] . "',user_type='" . $_POST['user_type'] . "',team_id='" . $_POST['partner'] . "',role='" . $_POST['role'] . "',status='" . $status . "',access='" . $access . "',deficit_user='" . $_POST['deficit_user'] . "',password='".$pass."' where id=" . $_REQUEST['user_id']);

    if ($sql) {
        if($_POST['access'])
        {
            $update_access = db_query("update partners set sm_user=".$_REQUEST['user_id']." where id in ($access)");
        }

        $select_query = db_query("select * from users where id =" . $_REQUEST['user_id']);
        $fetch_query = db_fetch_array($select_query);

        if (!empty($fetch_query['deficit_user'])) {
            $update_deficit_user = db_query("update user_kra set user_id=" . $_REQUEST['user_id'] . ",deficit_user='Yes' where team_id=" . $fetch_query['team_id'] . " and user_id='" . $fetch_query['deficit_user'] . "'");
        }

        redir("manage_users_admin.php?update=success", true);
    }
}


$query = db_query("select * from partners where status='Active' and id=" . $user_data['team_id']);
$row = db_fetch_array($query);

switch ($row['category']) {
    case "Platinum":
        $sales_team = 2;
        $iss_team = 3;
        $ae_team = 1;
        $installation = 1;
        break;
    case "Gold":
        $sales_team = 2;
        $iss_team = 2;
        $ae_team = 1;
        $installation = 1;
        break;
    case "Silver":
        $sales_team = 1;
        $iss_team = 1;
        $ae_team = 1;
        $installation = 1;
        break;
    case "ROI Gold":
        $sales_team = 1;
        $iss_team = 1;
        $ae_team = 1;
        break;
    case "ROI Silver":
        $sales_team = 1;
        $iss_team = 1;
        break;
    default:
        $sales_team = 1;
        $iss_team = 1;
        $ae_team = 1;
}

$actual_sal_count = getSingleresult("select count(id) from users where team_id='" . $user_data['team_id'] . "' and role ='SAL' and status='Active' order by role");

$actual_iss_count = getSingleresult("select count(id) from users where team_id='" . $user_data['team_id'] . "' and role ='TC' and status='Active' order by role");

$selectedRole = $user_data['role'];
if ($selectedRole != 'Internal' && $selectedRole != 'Partner') {
    if (in_array($user_data['user_type'], array('ADMIN', 'OPERATIONS', 'CLR', 'SALES MNGR'))) {
        $selectedRole = 'Internal';
    } else if (in_array($user_data['user_type'], array('MNGR', 'USR'))) {
        $selectedRole = 'Partner';
    } else {
        $selectedRole = '';
    }
}
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Edit User</small>
                                    <h4 class="font-size-14 m-0 mt-1">Edit User</h4>
                                </div>
                            </div>

                            <form method="post" action="#" id="userForm" class="form-horizontal" onsubmit="return handleFormSubmit()">
                                <input type="hidden" name="user_id" value="<?= $user_data['id']; ?>">
                                <div data-simplebar class="add_lead">

                                    <h5 class="card-subtitle">Personal Info</h5>
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">Name<span class="text-danger">*</span></label>
                                                <div class="col-md-9 controls">
                                                    <input name="name" type="text" class="form-control" placeholder="" value="<?= $user_data['name'] ?>" required data-validation-required-message="This field is required">
                                                    <small class="form-control-feedback">Full Name</small>

                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">Mobile<span class="text-danger">*</span></label>
                                                <div class="col-md-9 controls">
                                                    <input name="mobile" minlength="10" maxlength="10" type="text" autocomplete="off" value="<?= $user_data['mobile'] ?>" required data-validation-required-message="This field is required" class="form-control form-control" placeholder="" onkeypress="return isNumberKey(event,this.id);">
                                                    <small class="form-control-feedback">Valid Mobile Number</small> </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">Email<span class="text-danger">*</span></label>
                                                <div class="col-md-9 controls">
                                                    <input name="email" autocomplete="off" type="email" class="form-control" value="<?= $user_data['email'] ?>" placeholder="" required data-validation-required-message="Valid email is required">

                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">Password<span class="text-danger"></span></label>
                                                <div class="col-md-9 controls">
                                                    <div class="input-group">
                                                        <input name="password" autocomplete="off" type="password" id="pwd" data-validation-required-message="This field is required" class="form-control" />
                                                        <div class="input-group-append">
                                                            <button class="btn btn-light" type="button" id="togglePassword" aria-label="Show password">
                                                                <i class="mdi mdi-eye-outline" id="togglePasswordIcon"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!--/span-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--/row-->

                                    <h5 class="card-subtitle">Permission</h5>

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">Partner<span class="text-danger">*</span></label>
                                                <div class="col-md-9 controls">
                                                    <?php $res = db_query("select * from partners where status='Active' order by name asc");
                                                    ?>
                                                    <select name="partner" id="partner" required class="form-control">
                                                        <option value=" ">---Select---</option>
                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                            <option value='<?= $row['id'] ?>' <?= (($row['id'] == $user_data['team_id']) ? 'selected' : ''); ?>><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">Role<span class="text-danger">*</span></label>
                                                <div class="col-md-9 controls">
                                                    <select name="role" required id="role" class="form-control">
                                                        <option value="">---Select---</option>
                                                        <option value="Internal" <?= ($selectedRole == 'Internal' ? 'selected' : '') ?>>Internal</option>
                                                        <option value="Partner" <?= ($selectedRole == 'Partner' ? 'selected' : '') ?>>Partner</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">User Type<span class="text-danger">*</span></label>

                                                <div class="col-md-9 controls">
                                                    <select name="user_type" id="user_type" required class="form-control">
                                                        <option value=""> Please Select </option>
                                                        <option data-role-group="Internal" value="ADMIN" <?= (($user_data['user_type'] ?? '') == 'ADMIN' ? 'selected' : '') ?>>Administrator</option>
                                                        <option data-role-group="Internal" value="OPERATIONS" <?= (($user_data['user_type'] ?? '') == 'OPERATIONS' ? 'selected' : '') ?>>Operation</option>
                                                        <option data-role-group="Internal" value="CLR" <?= (($user_data['user_type'] ?? '') == 'CLR' ? 'selected' : '') ?>>Caller</option>
                                                        <option data-role-group="Internal" value="SALES MNGR" <?= (($user_data['user_type'] ?? '') == 'SALES MNGR' ? 'selected' : '') ?>>Sales Manager</option>
                                                        <option data-role-group="Partner" value="MNGR" <?= (($user_data['user_type'] ?? '') == 'MNGR' ? 'selected' : '') ?>>Manager</option>
                                                        <option data-role-group="Partner" value="USR" <?= (($user_data['user_type'] ?? '') == 'USR' ? 'selected' : '') ?>>User</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">Status<span class="text-danger">*</span></label>

                                                <div class="col-md-9 controls">
                                                    <input type="hidden" name="status" value="InActive">
                                                    <div class="status-toggle-wrap">
                                                        <label class="status-toggle mb-0" for="statusToggle">
                                                            <input type="checkbox" id="statusToggle" name="status" value="Active" <?= (($user_data['status'] == 'Active') ? 'checked' : '') ?>>
                                                            <span class="status-slider"></span>
                                                        </label>
                                                        <span id="statusText" class="status-text <?= (($user_data['status'] == 'Active') ? 'text-success' : 'text-danger') ?>"><?= (($user_data['status'] == 'Active') ? 'Active' : 'Inactive') ?></span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <?php if ($user_data['user_type'] == 'SALES MNGR') { ?>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-md-right col-md-3">Partner Access<span class="text-danger">*</span></label>
                                                    <div class="col-md-9 controls">
                                                        <?php $part_acc = db_query("select id,name from partners where status='Active'");
                                                        $current_access = getSingleresult("select access from users where id='" . $id . "'");
                                                        $access_array = explode(",", $current_access);
                                                        //print_r($access_array); die;
                                                        ?>

                                                        <select data-live-search="true" multiple class="multiselect form-control " name="access[]" required id="access" required class="form-control">
                                                            
                                                            <?php while ($par_row = db_fetch_array($part_acc)) { ?>
                                                                <option <?= (in_array($par_row['id'], $access_array) ? 'selected' : '') ?> value="<?= $par_row['id'] ?>"><?= $par_row['name'] ?></option>
                                                            <?php } ?>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php } ?>
                                        <!--/row-->
                                    </div>
                                </div>
                                <div class="button-items text-center">
                                    <button type="submit" class="btn btn-primary  mt-2" id="submitBtn">Update</button>
                                    <button type="button" onclick="javascript:history.go(-1)" class="btn btn-danger mt-2">Cancel</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->

        <?php include('includes/footer.php') ?>

        <script>
            $(document).ready(function() {
                $('#togglePassword').on('click', function() {
                    var passwordField = $('#pwd');
                    var passwordIcon = $('#togglePasswordIcon');
                    if (passwordField.attr('type') === 'password') {
                        passwordField.attr('type', 'text');
                        passwordIcon.removeClass('mdi-eye-outline').addClass('mdi-eye-off-outline');
                        $(this).attr('aria-label', 'Hide password');
                    } else {
                        passwordField.attr('type', 'password');
                        passwordIcon.removeClass('mdi-eye-off-outline').addClass('mdi-eye-outline');
                        $(this).attr('aria-label', 'Show password');
                    }
                });
            });

            $(document).ready(function() {
                $('#statusToggle').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#statusText').text('Active').removeClass('text-danger').addClass('text-success');
                    } else {
                        $('#statusText').text('Inactive').removeClass('text-success').addClass('text-danger');
                    }
                });
            });

            $(document).ready(function() {
                $('.multiselect').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });
                
            });

            $(document).ready(function() {
                var userTypeOptionsByRole = {
                    Internal: [],
                    Partner: []
                };

                $('#user_type option[data-role-group]').each(function() {
                    var roleGroup = $(this).data('role-group');
                    var optionValue = $(this).attr('value');
                    var optionText = $(this).text();

                    if (userTypeOptionsByRole[roleGroup]) {
                        userTypeOptionsByRole[roleGroup].push({
                            value: optionValue,
                            text: optionText
                        });
                    }
                });

                function updateUserTypeOptionsByRole() {
                    var selectedRole = $('#role').val();
                    var selectedUserType = $('#user_type').val();
                    var roleOptions = userTypeOptionsByRole[selectedRole] || [];
                    var userTypeSelect = $('#user_type');
                    var selectedStillExists = false;

                    userTypeSelect.find('option:not(:first)').remove();

                    $.each(roleOptions, function(index, optionItem) {
                        var selectedAttr = '';
                        if (optionItem.value === selectedUserType) {
                            selectedAttr = ' selected="selected"';
                            selectedStillExists = true;
                        }
                        userTypeSelect.append('<option value="' + optionItem.value + '"' + selectedAttr + '>' + optionItem.text + '</option>');
                    });

                    if (!selectedStillExists) {
                        userTypeSelect.val('');
                    }
                }

                $('#role').on('change', function() {
                    updateUserTypeOptionsByRole();
                });

                updateUserTypeOptionsByRole();
            });

            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.add_lead').height(wfheight - 295);
            });

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

         function handleFormSubmit() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerText = 'Updating...';
            return true;
         }
        </script>