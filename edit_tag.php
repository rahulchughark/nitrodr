<?php
include('includes/header.php');
admin_page();


$_GET['id'] = intval($_GET['id']);
$_POST['name'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($_POST['name']));
$_POST['desc'] = mysqli_real_escape_string($GLOBALS['dbcon'], htmlspecialchars($_POST['desc']));
$_POST['status'] = intval($_POST['status']);

if ($_GET['id'] != '') {
    $data = db_query("select * from tag where id='" . $_GET['id'] . "'");
    $user_data = db_fetch_array($data);
}

if ($_POST['user_id'] != '') {
    $_POST['desc']   = $_POST['desc'] ? $_POST['desc'] : NULL;

    $res = update_tag($_POST['name'], $_POST['desc'], $_POST['status'], null, null, $_SESSION['user_id'], $_POST['user_id'],$_POST['product']);
    
    //print_r($res);die;
    redir("manage_tag.php?update=success", true);
}


?>

<style>
    .add_lead {
        height: calc(100vh - 280px);
    }
</style>

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
                            <div class="media ">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home > Edit Tag</small>
                                    <h4 class="font-size-14 m-0 mt-1">Edit Tag</h4>
                                </div>
                            </div>

                            <div class="inner-content mt-3">
                                <form method="post" action="#" class="form-horizontal" novalidate enctype="multipart/form-data">
                                    <input type="hidden" name="user_id" value="<?= $user_data['id']; ?>">
                                    <div data-simplebar class="add_lead">

                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Tag Name<span class="text-danger">*</span></label>
                                                    <div class="controls">
                                                        <input name="name" value="<?= $user_data['name'] ?>" required data-validation-required-message="This field is required" type="text" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                <label class="control-label">Product Name<span class="text-danger">*</span></label>
                                            <div>
                                            <?php $res=db_query("select * from tbl_product where status=1"); ?>
                                                <select name="product" id="product" required class="form-control">
                                                <option value=''>Select Product</option>
                                                <?php while($row=db_fetch_array($res))
                                                { ?>
                                            <option <?=(($user_data['product_id']==$row['id'])?'selected':'')?> value='<?=$row['id']?>'><?=$row['product_name']?></option>
                                                <?php } ?>
                                                </select>
                                            </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Is Visible</label>
                                                    <div>
                                                        <select name="status" required class="form-control">
                                                            <option value="">---Select---</option>
                                                            <option <?= (($user_data['status'] == '1') ? 'selected' : '') ?> value=1>Yes</option>
                                                            <option <?= (($user_data['status'] == '0') ? 'selected' : '') ?> value=0>No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Tag Description</label>
                                                    <div>
                                                        <textarea name="desc" class="form-control" placeholder="Tag Description"><?= $user_data['description'] ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="button-items text-center">
                                        <button type="submit" class="btn btn-primary  mt-2">Update</button>
                                        <button type="button" onclick="javascript:history.go(-1)" class="btn btn-danger mt-2">Cancel</button>
                                    </div>
                                </form>
                            </div>
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
        </script>