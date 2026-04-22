<?php include('includes/header.php');
admin_page();
include_once('helpers/DataController.php');

$kraData = new DataController();

$month = date('n');
$year  =  date('Y');

$platinumData = db_query("select * from admin_kra where cdgs_category='Platinum' and year=" . $year);
$platinum_data = db_fetch_array($platinumData);

$goldData = db_query("select * from admin_kra where cdgs_category='Gold' and year=" . $year);
$gold_data = db_fetch_array($goldData);

$silverData = db_query("select * from admin_kra where cdgs_category='Silver' and year=" . $year);
$silver_data = db_fetch_array($silverData);

$roi_goldData = db_query("select * from admin_kra where cdgs_category='ROI Gold' and year=" . $year);
$RoiGold_data = db_fetch_array($roi_goldData);

$rol_silverData = db_query("select * from admin_kra where cdgs_category='ROI Silver' and year=" . $year);
$RolSilver_data = db_fetch_array($rol_silverData);


if (isset($_POST['save_platinum'])) {
    $delete_query = db_query("delete from admin_kra where cdgs_category='Platinum' and year=" . $year);

    $user_kra = [
        'raw_data'        => $_POST['kra1_platinum'],
        'new_account'     => $_POST['kra2_platinum'],
        'new_dr'          => $_POST['kra3_platinum'],
        'total_log'       => $_POST['kra4_platinum'],
        'monthly_account' => $_POST['kra5_platinum'],
        'total_visit'     => $_POST['kra6_platinum'],
        'sales_target'    => $_POST['kra7_platinum'],
        'cdgs_category'   => 'Platinum',
        'month'           => $month,
        'year'            => $year,
    ];

    $data_save = $kraData->insert($user_kra, "admin_kra");
    if ($data_save) {
        echo '<script>';
        echo 'setTimeout(function () { swal({html:true,
            title:"Submitted!",
            text:"KRA for Platinum Users Submitted Successfully",
            type: "success"});';
        echo '}, 100);</script>';
        redir("manageKra_admin.php", true);
    }
}

if (isset($_POST['save_gold'])) {
    $delete_query = db_query("delete from admin_kra where cdgs_category='Gold' and year=" . $year);

    $user_kra = [
        'raw_data'        => $_POST['kra1_gold'],
        'new_account'     => $_POST['kra2_gold'],
        'new_dr'          => $_POST['kra3_gold'],
        'total_log'       => $_POST['kra4_gold'],
        'monthly_account' => $_POST['kra5_gold'],
        'total_visit'     => $_POST['kra6_gold'],
        'sales_target'    => $_POST['kra7_gold'],
        'cdgs_category'   => 'Gold',
        'month'           => $month,
        'year'            => $year,
    ];

    $data_save = $kraData->insert($user_kra, "admin_kra");
    if ($data_save) {
        echo '<script>';
        echo 'setTimeout(function () { swal({html:true,
            title:"Submitted!",
            text:"KRA for Gold Users Submitted Successfully",
            type: "success"});';
        echo '}, 100);</script>';
        redir("manageKra_admin.php", true);
    }
}

if (isset($_POST['save_silver'])) {
    $delete_query = db_query("delete from admin_kra where cdgs_category='Silver' and year=" . $year);

    $user_kra = [
        'raw_data'        => $_POST['kra1_silver'],
        'new_account'     => $_POST['kra2_silver'],
        'new_dr'          => $_POST['kra3_silver'],
        'total_log'       => $_POST['kra4_silver'],
        'monthly_account' => $_POST['kra5_silver'],
        'total_visit'     => $_POST['kra6_silver'],
        'sales_target'    => $_POST['kra7_silver'],
        'cdgs_category'   => 'Silver',
        'month'           => $month,
        'year'            => $year,
    ];

    $data_save = $kraData->insert($user_kra, "admin_kra");
    if ($data_save) {
        echo '<script>';
        echo 'setTimeout(function () { swal({html:true,
            title:"Submitted!",
            text:"KRA for Silver Users Submitted Successfully",
            type: "success"});';
        echo '}, 100);
        </script>';
        redir("manageKra_admin.php", true);
    }
}

if (isset($_POST['save_roi_gold'])) {
    $delete_query = db_query("delete from admin_kra where cdgs_category='ROI Gold' and year=" . $year);

    $user_kra = [
        'raw_data'        => $_POST['kra1_roiGold'],
        'new_account'     => $_POST['kra2_roiGold'],
        'new_dr'          => $_POST['kra3_roiGold'],
        'total_log'       => $_POST['kra4_roiGold'],
        'monthly_account' => $_POST['kra5_roiGold'],
        'total_visit'     => $_POST['kra6_roiGold'],
        'sales_target'    => $_POST['kra7_roiGold'],
        'cdgs_category'   => 'ROI Gold',
        'month'           => $month,
        'year'            => $year,
    ];

    $data_save = $kraData->insert($user_kra, "admin_kra");
    if ($data_save) {
        echo '<script>';
        echo 'setTimeout(function () { swal({html:true,
            title:"Submitted!",
            text:"KRA for ROI Gold Users Submitted Successfully",
            type: "success"});';
        echo '}, 100);</script>';
        redir("manageKra_admin.php", true);
    }
}

if (isset($_POST['save_roi_silver'])) {
    $delete_query = db_query("delete from admin_kra where cdgs_category='ROI Silver' and year=" . $year);

    $user_kra = [
        'raw_data'        => $_POST['kra1_roiSilver'],
        'new_account'     => $_POST['kra2_roiSilver'],
        'new_dr'          => $_POST['kra3_roiSilver'],
        'total_log'       => $_POST['kra4_roiSilver'],
        'monthly_account' => $_POST['kra5_roiSilver'],
        'total_visit'     => $_POST['kra6_roiSilver'],
        'sales_target'    => $_POST['kra7_roiSilver'],
        'cdgs_category'   => 'ROI Silver',
        'month'           => $month,
        'year'            => $year,
    ];

    $data_save = $kraData->insert($user_kra, "admin_kra");
    if ($data_save) {
        echo '<script>';
        echo 'setTimeout(function () { swal({html:true,
            title:"Submitted!",
            text:"KRA for ROI Silver Users Submitted Successfully",
            type: "success"});';
        echo '}, 100);</script>';
        redir("manageKra_admin.php", true);
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
                                    <small class="text-muted">Home >Manage KRA</small>
                                    <h4 class="font-size-14 m-0 mt-1">Manage KRA</h4>
                                </div>
                            </div>
                            <div class="clearfix "></div>
<form action="" method="post">
                            <div class="table-responsive mt-2 admin_kra1">

                                <table id="leads"  class="table display nowrap table-striped admin_kra"  cellspacing="0" width="100%">

                                    <thead>

                                        <tr>
                                            <th>S.No.</th>
                                            <th>KRA</th>
                                            <th>Platinum</th>
                                            <th>Gold</th>
                                            <th>Silver</th>
                                            <th>ROI Gold</th>
                                            <th>ROI Silver</th>

                                        </tr>
                                    </thead>

                                    
                                        <tbody>

                                            <tr style="text-align:center;">
                                                <td style="width:50px;">1</td>
                                                <td style="width:300px;">Account for LC calling with mailer validation</td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra1_platinum" value="<?= $platinum_data['raw_data'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra1_gold" value="<?= $gold_data['raw_data'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra1_silver" value="<?= $silver_data['raw_data'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra1_roiGold" value="<?= $RoiGold_data['raw_data'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra1_roiSilver" value="<?= $RolSilver_data['raw_data'] ?>" /></td>

                                            </tr>

                                            <tr style="text-align:center;">
                                                <td>2</td>
                                                <td style="width:300px;">New account call per day 15 by each team (Sales + ISS)</td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra2_platinum" value="<?= $platinum_data['new_account'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra2_gold" value="<?= $gold_data['new_account'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra2_silver" value="<?= $silver_data['new_account'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra2_roiGold" value="<?= $RoiGold_data['new_account'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra2_roiSilver" value="<?= $RolSilver_data['new_account'] ?>" /></td>
                                            </tr>

                                            <tr style="text-align:center;">
                                                <td>3</td>
                                                <td style="width:300px;">Account for LC calling with profile remark and Uses confirmation</td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra3_platinum" value="<?= $platinum_data['new_dr'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra3_gold" value="<?= $gold_data['new_dr'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra3_silver" value="<?= $silver_data['new_dr'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra3_roiGold" value="<?= $RoiGold_data['new_dr'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra3_roiSilver" value="<?= $RolSilver_data['new_dr'] ?>" /></td>
                                            </tr>

                                            <tr style="text-align:center;">
                                                <td>4</td>
                                                <td style="width:300px;">Total Log-call @ DR Portal = 2 x new customer calls</td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra4_platinum" value="<?= $platinum_data['total_log'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra4_gold" value="<?= $gold_data['total_log'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra4_silver" value="<?= $silver_data['total_log'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra4_roiGold" value="<?= $RoiGold_data['total_log'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra4_roiSilver" value="<?= $RolSilver_data['total_log'] ?>" /></td>
                                            </tr>

                                            <tr style="text-align:center;">
                                                <td>5</td>
                                                <td style="width:300px;">Monthly 40 New Account to be visited per dedicated sales resource x number of dedicated sales required as per authorization</td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra5_platinum" value="<?= $platinum_data['monthly_account'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra5_gold" value="<?= $gold_data['monthly_account'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra5_silver" value="<?= $silver_data['monthly_account'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra5_roiGold" value="<?= $RoiGold_data['monthly_account'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra5_roiSilver" value="<?= $RolSilver_data['monthly_account'] ?>" /></td>
                                            </tr>

                                            <tr style="text-align:center;">
                                                <td>6</td>
                                                <td style="width:300px;">Total Account Visit to be done by Sales</td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra6_platinum" value="<?= $platinum_data['total_visit'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra6_gold" value="<?= $gold_data['total_visit'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra6_silver" value="<?= $silver_data['total_visit'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra6_roiGold" value="<?= $RoiGold_data['total_visit'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra6_roiSilver" value="<?= $RolSilver_data['total_visit'] ?>" /></td>
                                            </tr>

                                            <tr style="text-align:center;">
                                                <td>7</td>
                                                <td style="width:300px;">Sales Target Achievement</td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra7_platinum" value="<?= $platinum_data['sales_target'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra7_gold" value="<?= $gold_data['sales_target'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra7_silver" value="<?= $silver_data['sales_target'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra7_roiGold" value="<?= $RoiGold_data['sales_target'] ?>" /></td>
                                                <td><input class="form-control col-md-12" type="number" min="0" name="kra7_roiSilver" value="<?= $RolSilver_data['sales_target'] ?>" /></td>
                                            </tr>

                                            <tr style="text-align:center;">

                                                <td colspan="2">Save Data</td>

                                                <td><input type="submit" name="save_platinum" value="Save" class="btn btn-primary btn-xs col-md-10" /></td>
                                                <td><input type="submit" name="save_gold" value="Save" class="btn btn-primary btn-xs col-md-10" /></td>
                                                <td><input type="submit" name="save_silver" value="Save" class="btn btn-primary btn-xs col-md-10" /></td>
                                                <td><input type="submit" name="save_roi_gold" value="Save" class="btn btn-primary btn-xs col-md-10" /></td>
                                                <td><input type="submit" name="save_roi_silver" value="Save" class="btn btn-primary btn-xs col-md-10" /></td>
                                            </tr>

                                        </tbody>
                                    
                                </table>
                            </div>
</form>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <?php include('includes/footer.php') ?>

        <script>
            $('#leads').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                ]
            });


            $(function() {
                $('#datepicker-close-date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });

            function clear_search() {
                window.location = 'manageKra_admin.php';
            }
        </script>

        <script>
            jQuery("#search_toogle").click(function() {
                jQuery(".search_form").toggle("slow");
            });

            $(document).ready(function() {
                var wfheight = $(window).height();
                //$('.dataTables_wrapper').height(wfheight - 370);
				$('.admin_kra1').height(wfheight - 215);
				
                $("#leads").tableHeadFixer();

            });
        </script>