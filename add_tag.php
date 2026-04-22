<?php
include('includes/header.php');
admin_page();

$_POST['name'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($_POST['name']));
$_POST['desc'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($_POST['desc']));
$_POST['status'] = intval($_POST['status']);
//$_POST['start_date'] = filter_var("([^0-9/] | [^0-9-])","",htmlentities($_POST['start_date']));
//$_POST['end_date'] = filter_var("([^0-9/] | [^0-9-])","",htmlentities($_POST['end_date']));
//print_r($_POST['start_date']);
if ($_POST['name']) {

    $_POST['desc']   = $_POST['desc'] ? $_POST['desc'] : 'NULL';

    $res = db_query("INSERT INTO `tag`(`name`, `description`, `status`, `created_by`,`created_at`,product_id) VALUES ('" . $_POST['name'] . "' ,'" . $_POST['desc'] . "' ,'" . $_POST['status'] . "' ,'" . $_SESSION['user_id'] . "',now(),'" . $_POST['product'] . "')");
    
    if ($res) {
        redir("manage_tag.php?add=success", true);
    }
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
                            <div class="media bredcrum-title">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home > Add Tag</small>
                                    <h4 class="font-size-14 m-0 mt-1">Add Tag</h4>
                                </div>
                            </div>


                            <form method="post" action="#" class="form-horizontal" novalidate enctype="multipart/form-data">
                                <div data-simplebar class="add_lead">

                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">Tag Name<span class="text-danger">*</span></label>
                                                <div class="col-md-9 controls">
                                                    <input type="text" name="name" required data-validation-required-message="This field is required" class="form-control" placeholder="Tag Name" data-validation-required-message="This field is required">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">Tag Description</label>
                                                <div class="col-md-9">
                                                    <textarea name="desc" class="form-control" placeholder="Tag Description"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                            <label class="control-label text-md-right col-md-3">Product Name<span class="text-danger">*</span></label>
                                            <?php $res=db_query("select * from tbl_product where status=1"); ?>
                                            <div class="col-md-9">
                                            <select name="product" id="product" required class="form-control">
                                            <option value=''>Select Product</option>
                                            <?php while($row=db_fetch_array($res))
                                            { ?>
                                        <option <?=(($product_id==$row['id'])?'selected':'')?> value='<?=$row['id']?>'><?=$row['product_name']?></option>
                                            <?php } ?>
                                            </select>
                                            </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <div class="form-group row">
                                                <label class="control-label text-md-right col-md-3">Is Visible<span class="text-danger">*</span></label>
                                                <div class="col-md-9">
                                                    <select name="status" required class="form-control" data-validation-required-message="This field is required">
                                                        <option value="" disabled>---Select---</option>
                                                        <option <?= (($status == '1') ? 'selected' : '') ?> value=1>Yes</option>
                                                        <option <?= (($status == '0') ? 'selected' : '') ?> value=0>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="button-items text-center">
                                    <button type="submit" class="btn btn-primary  mt-2">Submit</button>
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
            $(function() {
                $('#datepicker-start-date').datepicker({

                    format: 'yyyy-mm-dd',
                    startDate: '-3d',
                    autoclose: !0

                });
            });

            $(function() {
                $('#datepicker-end-date').datepicker({
                    format: 'yyyy-mm-dd',
                    startDate: '-3d',
                    autoclose: !0

                });
            });

            // $(function() {
            //     $('.datepicker').daterangepicker({
            //         //autoUpdateInput: false, //disable default date
            //         "singleDatePicker": true,
            //         "showDropdowns": false,
            //         locale: {
            //             format: 'YYYY-MM-DD'
            //         },
            //         //startDate: '2017-01-01',
            //         //autoUpdateInput: false,
            //     });
            //     $('.datepicker').val("");
            // });
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.add_lead').height(wfheight - 280);
            });
        </script>