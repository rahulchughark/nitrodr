<?php
include('includes/header.php');
admin_page();


if ($_POST['name']) {

    $_POST['name'] = mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['name']);
    $_POST['url'] = mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['url']);
    $_POST['status'] = intval($_POST['status']);
    $_POST['p_cat'] = intval($_POST['p_cat']);
    $_POST['order'] = intval($_POST['order']);
    //$_POST['user_role']= mysqli_real_escape_string($GLOBALS['dbcon'], $_POST['user_role']);

    $user_role = implode(',', $_POST['user_role']);

    if ($_FILES["icon"]["name"]) {
        $target_dir = "uploads/sidebar_icon/";
        $target_file = $target_dir . time() . basename($_FILES["icon"]["name"]);

        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($_FILES["icon"]["size"] > 4000000) {
            echo "<script>alert('Sorry, your file is too large!')</script>";
            redir("add_modules.php", true);
        } else {
            move_uploaded_file($_FILES["icon"]["tmp_name"], $target_file);
        }
    }
    //print_r($target_file);die;
    $_POST['user_role']   = $_POST['user_role'] ? $_POST['user_role'] : ' ';
    $_POST['is_function']   = $_POST['is_function'] ? $_POST['is_function'] : ' ';
    $_POST['url']   = $_POST['url'] ? $_POST['url'] : '#';
    $_POST['p_cat'] = $_POST['p_cat'] ? $_POST['p_cat'] : '0';
    //$target_file    = $target_file ? $target_file : ' ';

    $res = add_module($_POST['name'], $_POST['url'], $_POST['status'], $_POST['p_cat'], $_POST['order'], $target_file, $user_role, $_POST['is_function']);


    if ($res) {
        redir("manage_modules.php?add=success", true);
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
                            <div class="media bredcrum-title">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">
                                    <small class="text-muted">Home > Add Module</small>
                                    <h4 class="font-size-14 m-0 mt-1">Add Module</h4>
                                </div>
                            </div>

                            <form method="post" action="#" class="form-horizontal" novalidate enctype="multipart/form-data">

                                <div data-simplebar class="add_lead">

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-lg-right col-md-3">Name<span class="text-danger">*</span></label>
                                                <div class="col-md-9 controls">
                                                    <input name="name" value="<?= $sub_category ?>" required data-validation-required-message="This field is required" type="text" class="form-control" id="nameA" oninput="validateInputs()">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-lg-right col-md-3">Level</label>
                                                <div class="col-md-9 controls">
                                                    <select name="p_cat" id="p_cat" class="form-control" required data-validation-required-message="This field is required">
                                                        <option value=' '>---Select---</option>
                                                        <?php echo module_listing('parentId') ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-lg-right col-md-3">URL</label>
                                                <div class="col-md-9">
                                                    <input type="text" value="<?= $url ?>" name="url" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-lg-right col-md-3">Order<span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <input type="number" required min="0" name="order" id="order" value="<?= $order ?>" class="form-control" data-validation-required-message="This field is required" oninput="validateInputs()">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-lg-right col-md-3">Status<span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <select name="status" id="statusA" required class="form-control" data-validation-required-message="This field is required" oninput="validateInputs()">
                                                        <option value="" disabled>---Select---</option>
                                                        <option <?= (($status == 'Active') ? 'selected' : '') ?> value=1>Active</option>
                                                        <option <?= (($status == 'InActive') ? 'selected' : '') ?> value=0>InActive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-lg-right col-md-3">User Role</label>
                                                <div class="col-md-9">
                                                    <select name="user_role[]" class="multiselect form-control" multiple>

                                                        <?php $roles_query = getRoleData();
                                                        foreach ($roles_query as $role) { ?>
                                                            <option value="<?= $role['role_code'] ?>"><?= $role['role_type'] ?></option>
                                                        <?php } ?>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">


                                        <!-- <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-lg-right col-md-3">Icon</label>
                                                <div class="col-md-9">
                                                    <input type="file" name="icon" class="form-control" />
                                                </div>
                                            </div>
                                        </div> -->

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-lg-right col-md-3">Function</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="is_function" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="button-items text-center">
                                    <button type="submit" class="btn btn-primary  mt-2" id="submitBtn" disabled>Submit</button>
                                    <button type="button" onclick="javascript:history.go(-1)" class="btn btn-danger mt-2">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('includes/footer.php') ?>
        <script type="text/javascript">
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

            function validateInputs() {
                const nameA = document.getElementById('nameA');
                // const p_cat = document.getElementById('p_cat');
                const order = document.getElementById('order');
                const statusA = document.getElementById('statusA');
                const submitBtn = document.getElementById('submitBtn');
                // alert(nameA)
                if (nameA.value.trim() !== '' && statusA.value.trim() !== '' && order.value.trim() !== '') {
                    submitBtn.disabled = false;
                } else {
                    submitBtn.disabled = true;
                }
            }
        </script>