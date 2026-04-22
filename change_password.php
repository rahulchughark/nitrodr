<?php include('includes/header.php'); ?>
<?php if ($_POST['opassword'] && ($_POST['npassword'] == $_POST['cpassword'])) {

    if (getSingleresult("select id from users where id='" . $_SESSION['user_id'] . "' and password='" . md5($_POST['opassword']) . "'")) {

        $res = db_query("update users set password='" . md5($_POST['npassword']) . "' where id=" . $_SESSION['user_id']);

        echo '<script>';
        echo 'setTimeout(function () { swal({html:true,
            title:"Success!",
            text:"Password updated successfully!.",
            type: "success"});';
        echo '}, 100);</script>';
        //echo "<script>alert('Password updated successfully!');</script>";
    } else {
        echo '<script>';
        echo 'setTimeout(function () { swal({html:true,
            title:"Oopss!",
            text:"Old password did not match. Please try again!",
            type: "warning"});';
        echo '}, 100);</script>';
       // echo "<script>alert('Old password did not match. Please try again!');</script>";
    }
} else if ($_POST['npassword'] != $_POST['cpassword']) { ?>

    <script>
        
       setTimeout(function () { swal({html:true,
            title:"Oopss!",
            text:"Password and Confirm password did not match. Please try again!",
            type: "warning"});
       }, 100);
        //alert('Password and Confirm password did not match. Please try again!');
    </script>

<?php }
?>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->

<style>
    .simplebar-content {
        height: 100%;
    }
    
    .custom-checkbox label:before, .multiselect-container.dropdown-menu li a label:before {
        margin-right: 10px;
    }

    .custom-checkbox:before, .multiselect-container.dropdown-menu li:before {
        background-color: #f9f9f9;
    }
</style>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- <div class="media bredcrum-title"><img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home</small>
                                    <h4 class="font-size-14 m-0 mt-1">Change Password</h4>
                                </div>
                            </div> -->


                            <form method="post" action="#" class="form-horizontal">
                                <div data-simplebar class="add_lead change-password-card">
                                    <div class="change-password-card">
                                        <div class="cp-inner">
                                            <h3>Change Password</h3>
                                            <div class="form-group">
                                                <label class="control-label">Current Password<span class="text-danger">*</span></label>
                                                <div class="controls row align-items-center">
                                                    <div class="col pr-0">
                                                        <input name="opassword" id="opassword" type="password" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                                    </div>
                                                    <div class="col-auto text-nowrap">
                                                        <div class="custom-checkbox">
                                                        <input type="checkbox" value="1" id="checkC" class="filled-in chk-col-blue">
                                                        <label for="checkC">Show</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">New Password<span class="text-danger">*</span></label>
                                                <div class="controls row align-items-center">
                                                    <div class="col pr-0">
                                                        <input name="npassword" id="npassword" type="password" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                                    </div>
                                                    <div class="col-auto text-nowrap">
                                                        <div class="custom-checkbox">
                                                            <input type="checkbox" value="1" id="checkN" class="filled-in chk-col-blue">
                                                            <label for="checkN">Show</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Confirm Password<span class="text-danger">*</span></label>
                                                <div class="controls">
                                                    <input name="cpassword" id="cpassword" type="text" class="form-control" placeholder="" required data-validation-required-message="This field is required">
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <button type="button" onclick="javascript:history.go(-1)" class="btn btn-danger ml-2">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
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

                var wfheight = $(window).height();
                $('.add_lead').height(wfheight - 210);

            });

            $(document).ready(function() {
                $('#checkC').click(function() {
                    //alert($(this).is(':checked'));
                    $(this).is(':checked') ? $('#opassword').attr('type', 'text') : $('#opassword').attr('type', 'password');
                });
                $('#checkN').click(function() {
                    //alert($(this).is(':checked'));
                    $(this).is(':checked') ? $('#npassword').attr('type', 'text') : $('#npassword').attr('type', 'password');
                });
            });
        </script>