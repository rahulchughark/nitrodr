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



if ($_POST['name']) {
    ;
    //print_r($_POST);
    $status = ($_POST['status'] == 'Inactive') ? 'Inactive' : 'Active';

    if ($_POST['access']) {
        $access = implode(",", $_POST['access']);
    } else {
        $access = '';
    }

    if ($_POST['caller']) {
        $caller = implode(",", $_POST['caller']);
    } else {
        $caller = '';
    }
    
    $email_check = getSingleresult("select id from users where email='" . $_POST['email'] . "'");

    if (!$email_check) {
        $utype = $_POST['user_type'];
        $res = db_query("insert into users (`name`, `email`, `password`, `mobile`, `team_id`, `user_type`,access,deficit_user,role,caller,status) VALUES ('" . $_POST['name'] . "','" . $_POST['email'] . "','" . md5($_POST['password']) . "','" . $_POST['mobile'] . "','" . $_POST['partner'] . "','" . $utype . "','" . $access . "','".$_POST['deficit_user']."','".$_POST['role']."','" . $caller . "','" . $status . "')");

        $uid = get_insert_id();

        if ($utype == 'CLR' || $utype == 'RCLR' || $utype == 'TEAM LEADER') {
            $add_caller = db_query("INSERT INTO `callers`(`name`, `user_id`, `caller_id`, `created_date`) VALUES ('" . $_POST['name'] . "','" . $uid . "','" . $_POST['sfdc'] . "','" . date('Y-m-d H:i:s') . "')");
        }

        $select_query = db_query("select * from users where id =" . $uid);
        $fetch_query = db_fetch_array($select_query);
        if ($fetch_query['user_type'] == 'SALES MNGR') {
            $update_query = db_query("update users set sales_manager=1 where id=" . $uid);
        }
        if(!empty($fetch_query['deficit_user'])){
            $update_deficit_user = db_query("update user_kra set user_id=".$uid.",deficit_user='Yes' where team_id=".$fetch_query['team_id']." and user_id='".$fetch_query['deficit_user']."'");
        }

        if ($res) {
            $addTo[] = $_POST['email'];
            $addCc[] = $_SESSION['email'];
            $addBcc[] = 'virendra.kumar@arkinfo.in';
            $setSubject = "Credentials for " . $_POST['name'];
              $body    = "Hi,<br><br> There are credentials for ICT DR Portal with details as below:-<br><br>
              <ul>
              <li><b>Login Id</b> : " . $_POST['email'] . " </li>
              <li><b>Password</b> : " . $_POST['password'] . " </li>
              <li><b>Url</b> : https://dr.ict360.com/ </li>
              </ul><br>
              Thanks,<br>
              ICT DR Portal";

              $addBcc[] = '';
              //sendMail($addTo, $addCc, $addBcc, $setSubject, $body);
            redir("manage_users_admin.php?add=success", true);
        }
    } else
        redir("manage_users_admin.php?email=fail", true);
}
 ?>

<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >Add User</small>
                                    <h4 class="font-size-14 m-0 mt-1">Add User</h4>
                                </div>
                            </div>

                            <form method="post" action="#" id="userForm" class="form-horizontal" onsubmit="return handleFormSubmit()">
                                <div data-simplebar class="add_lead">

                                    <h5 class="card-subtitle">Personal Info</h5>
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-4 col-xl-3">Name<span class="text-danger">*</span></label>
                                                <div class="col-md-8 col-xl-9 controls">
                                                    <input name="name" type="text" id="nameU" class="form-control" placeholder="" required data-validation-required-message="This field is required" oninput="validateInputs()">
                                                    <small class="form-control-feedback">Full Name</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-4 col-xl-3">Mobile<span class="text-danger">*</span></label>
                                                <div class="col-md-8 col-xl-9 controls">
                                                    <input name="mobile" id="mobileU" maxlength="10" type="text" autocomplete="off" required data-validation-required-message="This field is required" class="form-control form-control" placeholder="" onkeypress="return isNumberKey(event,this.id);" oninput="validateInputs()">
                                                    <small class="form-control-feedback">Valid Mobile Number</small> </div>
                                            </div>
                                        </div>
                                        <!--/span-->

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-4 col-xl-3">Email<span class="text-danger">*</span></label>
                                                <div class="col-md-8 col-xl-9 controls">
                                                    <input name="email" id="email" autocomplete="off" type="email" class="form-control" placeholder="" required data-validation-required-message="Valid email is required" onchange="checkEmail(this.value)" oninput="validateInputs()">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-4 col-xl-3">Password<span class="text-danger">*</span></label>
                                                <div class="col-md-8 col-xl-9 controls">
                                                    <div class="input-group">
                                                        <input name="password" autocomplete="off" type="password" id="pwd" required data-validation-required-message="This field is required" class="form-control" placeholder="">
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
                                        <div class="col-lg-4 mb-3">
                                            <div id="sfdc_row" style="display:none">

                                                <div class="form-group row">
                                                    <label class="control-label text-md-right col-md-4 col-xl-3">SFDC ID<span class="text-danger">*</span></label>
                                                    <div class="col-md-8 col-xl-9 controls">
                                                        <input name="sfdc" id="sfdc" type="text" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="card-subtitle">Permission</h5>

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-4 col-xl-3">Partner<span class="text-danger">*</span></label>
                                                <div class="col-md-8 col-xl-9 controls">
                                                    <?php $res = db_query("select * from partners where status='Active' order by name asc");
                                                    ?>
                                                    <select name="partner" id="partner" required class="form-control" onchange="check_deficit_user(this.value)">
                                                        <option value="">---Select---</option>
                                                        <?php while ($row = db_fetch_array($res)) { ?>
                                                            <option value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-4 col-xl-3">Role<span class="text-danger">*</span></label>
                                                <div class="col-md-8 col-xl-9 controls">
                                                    <select name="role" required id="role" required class="form-control">
                                                        <option value="">---Select---</option>
                                                        <option value="Internal" <?= (($user_data['role'] ?? '') == 'Internal' ? 'selected' : '') ?>>Internal</option>
                                                        <option value="Partner" <?= (($user_data['role'] ?? '') == 'Partner' ? 'selected' : '') ?>>Partner</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-4 col-xl-3">User Type<span class="text-danger">*</span></label>
                                                <div class="col-md-8 col-xl-9">
                                                    <select name="user_type" id="user_type" required class="form-control" onchange="check_caller(this.value)">
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
                                                <label class="control-label text-md-right col-md-4 col-xl-3">Status</label>
                                                <div class="col-md-8 col-xl-9 controls">
                                                    <input type="hidden" name="status" value="Inactive">
                                                    <div class="status-toggle-wrap">
                                                        <label class="status-toggle mb-0" for="statusToggle">
                                                            <input type="checkbox" id="statusToggle" name="status" value="Active" checked>
                                                            <span class="status-slider"></span>
                                                        </label>
                                                        <span id="statusText" class="status-text text-success">Active</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-items text-center">
                                    <button type="submit" class="btn btn-primary  mt-2" disabled id="submitBtn">Submit</button>
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
            function check_caller(caller) {

                if (caller == 'CLR' || caller == 'RCLR') {
                    $('#sfdc_row').show();
                    $('#sfdc').prop('required', true);
                    $('#partner_access').hide();
                    $('#access').prop('required', false);
                    $('#caller_data').hide();
                } else if (caller == 'SALES MNGR') {
                    $('#partner_access').show();
                    $('#access').prop('required', true);
                    $('#caller_data').hide();
                } else if(caller=='TEAM LEADERS'){
                    $('#caller_data').show();
                } else if(caller=='TEAM LEADER' || caller == 'RENEWAL TL'){
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_get_callers.php',
                        data: {tl_type:caller,edit:false},
                        success: function(html) {
                            //alert(html);
                            $('#caller_data').show();
                            $('#caller_selection').html(html);
                        }
                    });
                    // $('#caller_data').show();
                }else {
                    $('#sfdc_row').hide();
                    $('#sfdc').prop('required', false);
                    $('#partner_access').hide();
                    $('#access').prop('required', false);
                    $('#caller_data').hide();
                }
            }

            function check_deficit_user(partner){
               
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_deficit_user.php',
                        data: 'team_id=' + partner,
                        success: function(html) {
                            //alert(html);
                            $('#deficit_user').html(html);
                        }
                    });
                
            }

            $(document).ready(function() {
                $('.multiselect').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });
                $('.multiselect_caller').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });

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
                        check_caller('');
                    }
                }

                $('#role').on('change', function() {
                    updateUserTypeOptionsByRole();
                });

                updateUserTypeOptionsByRole();
            });
            
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

            $(document).ready(function () {
                var wfheight = $(window).height();

                if (window.innerWidth <= 767) {
                    // Mobile devices (width <= 767px)
                    $('.add_lead').height(wfheight - 240); // Adjust as needed for mobile
                } else {
                    // Desktop or larger tablets
                    $('.add_lead').height(wfheight - 280);
                }
            });

            function checkEmail(email)
            {
                $.ajax({
                    type: 'POST',
                    url: 'ajax_email_check.php',
                    data: {
                        email: email
                    },
                    success: function(response) {
                        var response = $.trim(response);
                        if(response == "exist"){
                            toastr.error("This email id is already exist");
                            $("#email").val("");
                            // $('#submitBtn').prop('disabled', true);
                            $("#email").focus();
                        } else {
                            validateInputs();
                    }
                }
              });
           }

           $(document).ready(function() {
                setTimeout(function(){
                  $('#userForm')[0].reset();
                }, 1000);
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

          function validateInputs() {
                const submitBtn = document.getElementById('submitBtn');
                const nameU = document.getElementById('nameU');
                const mobileU = document.getElementById('mobileU');
                const emailU = document.getElementById('email');
                
                // alert(nameP.value)
                if (nameU.value.trim() !== '' && mobileU.value.trim() !== '' && emailU.value.trim() !== '') {
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
        </script>

        