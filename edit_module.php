<?php
include('includes/header.php');
admin_page();


$_GET['id'] = intval($_GET['id']);

if ($_GET['id'] != '') {
    $data = db_query("select * from tbl_module where id='" . $_GET['id'] . "'");
    $user_data = db_fetch_array($data);

    $user_data['user_type'] = explode(',', $user_data['user_type']);
}

if ($_POST['user_id'] != '') {

    $_POST['name'] = mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['name']);
    $_POST['url'] = mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['url']);
    $_POST['status'] = intval($_POST['status']);
    $_POST['p_cat'] = intval($_POST['p_cat']);
    $_POST['order'] = intval($_POST['order']);
    $_POST['user_id'] = intval($_POST['user_id']);
    //$_POST['user_role']= mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['user_role']);
    $user_role = implode(',', $_POST['user_role']);
    //print_r($user_role);

    $_POST['url'] = $_POST['url'] ? $_POST['url'] : '#';
    //$_POST['user_role']   = $_POST['user_role'] ? $_POST['user_role'] : ' ';
    $_POST['is_function']   = $_POST['is_function'] ? $_POST['is_function'] : ' ';

    if (!empty($_FILES["icon"]["name"])) {
        $target_dir = "uploads/sidebar_icon/";
        $target_file = $target_dir . time() . basename($_FILES["icon"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($_FILES["icon"]["size"] > 4000000) {
            echo "<script>alert('Sorry, your file is too large!')</script>";
            redir("edit_modules.php", true);
        } else {
            move_uploaded_file($_FILES["icon"]["tmp_name"], $target_file);
        }

        $res = update_module($_POST['name'], $_POST['url'], $_POST['status'], $_POST['p_cat'], $_POST['order'], $target_file, $_POST['user_id'], $user_role, $_POST['is_function']);

        redir("manage_modules.php?update=success", true);
    } else {

        $res = update_module($_POST['name'], $_POST['url'], $_POST['status'], $_POST['p_cat'], $_POST['order'], $user_data['icon'], $_POST['user_id'], $user_role, $_POST['is_function']);
        //print_r($res);die;
        redir("manage_modules.php?update=success", true);
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
                            <div class="media ">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home > Edit Module</small>
                                    <h4 class="font-size-14 m-0 mt-1">Edit Module</h4>
                                </div>
                            </div>


                            <form method="post" action="#" class="form-horizontal" novalidate enctype="multipart/form-data">

                                <input type="hidden" name="user_id" value="<?= $user_data['id']; ?>">

                                <div data-simplebar class="add_lead">

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Name<span class="text-danger">*</span></label>
                                                <div class="controls">
                                                    <input name="name" value="<?= $user_data['name'] ?>" required data-validation-required-message="This field is required" type="text" class="form-control" id="nameA" oninput="validateInputs()">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Level</label>
                                                <div class="">
                                                    <select name="p_cat" id="p_cat" class="form-control" oninput="validateInputs()">
                                                        <option value=''>---Select---</option>
                                                        <?php echo module_listing($user_data['parentId']) ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">URL</label>
                                                <div>
                                                    <input type="text" value="<?= $user_data['url'] ?>" name="url" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Order<span class="text-danger">*</span></label>
                                                <div>
                                                    <input type="number" min="0" name="order" id="order" value="<?= $user_data['setOrder'] ?>" class="form-control" oninput="validateInputs()">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Status<span class="text-danger">*</span></label>
                                                <div>
                                                    <select name="status" required class="form-control" oninput="validateInputs()" id="statusA">
                                                        <option value="">---Select---</option>
                                                        <option <?= (($user_data['status'] == '1') ? 'selected' : '') ?> value=1>Active</option>
                                                        <option <?= (($user_data['status'] == '0') ? 'selected' : '') ?> value=0>InActive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">User Role</label>
                                                <div>
                                                    <select name="user_role[]" class="multiselect form-control" multiple>
                                                       
                                                        <?php $roles_query = getRoleData();
                                                        foreach ($roles_query as $role) { ?>
                                                            <option <?= ((in_array($role['role_code'], $user_data['user_type'])) ? 'selected' : '') ?> value="<?= $role['role_code'] ?>"><?= $role['role_type'] ?></option>
                                                        <?php } ?>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">

                                        <!-- <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-right col-md-3">Icon</label>
                                                <div class="col-md-9">
                                                    <input type="file" name="icon" class="form-control">
                                                    <img src="<?= $user_data['icon'] ?>" style="width:50px; height:50px" ; />
                                                    <input type="hidden" name="old_icon" value="<?= $user_data['icon'] ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div> -->

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group">
                                                <label class="control-label">Function</label>
                                                <div>
                                                    <input type="text" name="is_function" class="form-control" value="<?= $user_data['is_function'] ?>" />
                                                </div>
                                            </div>
                                        </div>
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
 
        <?php include('includes/footer.php') ?>
        <script>

        function validateInputs() {
                const nameA = document.getElementById('nameA');
                // alert(nameA)
                // const p_cat = document.getElementById('p_cat');
                const order = document.getElementById('order');
                const statusA = document.getElementById('statusA');
                const submitBtn = document.getElementById('submitBtn');
                if (nameA.value.trim() !== '' && statusA.value.trim() !== '' && order.value.trim() !== '') {
                    submitBtn.disabled = false;
                } else {
                    submitBtn.disabled = true;
                }
            }

            $(document).ready(function() {

                var wfheight = $(window).height();
                $('.add_lead').height(wfheight - 280);

            });

            $(document).ready(function() {
                $('.multiselect').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });
            });

            
        </script>