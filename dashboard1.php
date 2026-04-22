<?php include('includes/header.php');

if (!$_GET['date_from']) {
    $_GET['date_from'] = date('Y-m-01');
}
if (!$_GET['date_to']) {
    $_GET['date_to'] = date('Y-m-t');
}
?>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper bg-dark">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid col-12">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h5 class="text-themecolor">Dashboard</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>


            </div>

            <?php if ($_SESSION['user_type'] != 'CQM') { ?>
			<div class="col-lg-7 search_form_design">
			<div style="float:right;margin-top:15px">
                    <form method="get" name="search">
                        <input type="text" value="<?php echo @$_GET['d_from'] ?>" class="datepicker" id="d_from" name="d_from" placeholder="Date From" />
                        <input type="text" value="<?php echo @$_GET['d_to'] ?>" class="datepicker" id="d_to" name="d_to" placeholder="Date To" />
                        <input type="submit" class="btn btn-primary" value="View" />

                    </form>
            </div>
			</div><!--col-lg-7-->


        </div>
    <?php } ?>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <!-- Row -->
    <?php if ($_SESSION['user_type'] == 'USR' || $_SESSION['user_type'] == 'MNGR') { ?>
	<div class="dashboard-main-div">
        <div class="row">
            <div class="col-lg-12 col-md-4">
                <div class="card">
                    <img class="" src="assets/images/background/user-info.jpg" alt="Card image cap">
                    <div class="card-img-overlay text-center" style="height:110px;">
                        <h1 class="card-title text-white m-b-0 dl">Corel Rewards Program</h1>
                        <br>
                        <h4 class="card-text text-white font-light">Rank across all participants</h4>
                    </div>
                    <div class="card-body weather-small">
                        <div class="row">
                            <div class="col-6 b-r align-self-center">
                                <div class="d-flex">
                                    <div class="display-6 text-info"><i class="fa fa-star"></i></div>
                                    <div class="m-l-20">

                                        <?php $sql_z = db_query("select users.*,COALESCE(sum(user_points.point),0) as total from users left join user_points on users.id=user_points.user_id where (users.team_id!='' and users.team_id!=45) and (users.user_type='USR' or users.user_type='MNGR' ) and user_points.week_number='" . date('W') . "' GROUP by users.id order by total Desc");
                                        $i = 1;
                                        $rank = 1;
                                        $final_rank = 'N/A';
                                        while ($data_z = db_fetch_array($sql_z)) {
                                            if ($data_z['id'] == $_SESSION['user_id']) {
                                                $final_rank = $rank;
                                                $new = getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='" . $data_z['id'] . "' and stage_id=1000 and week_number=" . date('W'));
                                                $approved = getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='" . $data_z['id'] . "' and stage_id=1001 and week_number=" . date('W'));
                                                $lc = getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='" . $data_z['id'] . "' and stage_id=5 and week_number=" . date('W'));
                                                $quote = getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='" . $data_z['id'] . "' and stage_id=6 and week_number=" . date('W'));
                                                $follow = getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='" . $data_z['id'] . "' and stage_id=7 and week_number=" . date('W'));
                                                $commit = getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='" . $data_z['id'] . "' and stage_id=9 and week_number=" . date('W'));
                                                $eupo = getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='" . $data_z['id'] . "' and stage_id=10 and week_number=" . date('W'));
                                                $booking = getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='" . $data_z['id'] . "' and stage_id=11 and week_number=" . date('W'));
                                                $billing = getSingleresult("select COALESCE(sum(point),0) from user_points where user_id='" . $data_z['id'] . "' and stage_id=12 and week_number=" . date('W'));
                                                $net = $new + $approved + $lc + $quote + $follow + $commit + $eupo + $booking + $billing;
                                                $insentive = 0;
                                                $grand_total = $net + $insentive;
                                            }
                                            $rank++;
                                        }
                                        ?>

                                        <h1 class="font-light text-info m-b-0"><?= ($grand_total ? $grand_total : 'N/A') ?></h1>
                                        <small>Your Points Till Date</small>
                                    </div>
                                </div>

                            </div>
                            <div class="col-6 text-center">

                                <h1 class="font-light m-b-0"><?= $final_rank ?></h1>
                                <small>Rank</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="m-t-0 m-b-10">
    <?php } ?>

    <?php if ($_SESSION['user_type'] == 'MNGR') { ?>
        <div class="row">
            <!-- Column -->
            <div class="col-lg-12 col-md-7">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex flex">
                                    <div>
                                        <h5 class="card-title">VAR KAR</h3>
                                            <h6 class="card-subtitle"></h6>
                                    </div>

                                </div>
                            </div>
                            <div class="col-12">
                                <table id="" class="display nowrap table table-hover table-striped table-bordered " cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>KRA</th>
                                            <th>Weightage</th>
                                            <th>Achieved</th>
                                            <th>Achieved %</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php
                                        $var = getSingleresult("select cdgs_target from partners where id=" . $_SESSION['team_id']);
                                        $category = getSingleresult("select category from partners where id=" . $_SESSION['team_id']);
                                        switch ($category) {
                                            case "Platinum":
                                                $sales_team = 3;
                                                break;
                                            case "Gold":
                                                $sales_team = 2;
                                                break;
                                            default:
                                                $sales_team = 1;
                                        }

                                        $achived = getSingleresult("select IFNULL(sum(quantity),0) from orders where status='Approved' and team_id='" . $_SESSION['team_id'] . "' and date(created_date)>='" . $_GET['date_from'] . "' and date(created_date)<='" . $_GET['date_to'] . "'");
                                        $kra1_var = $var * 4;
                                        $achive_percent_kra1 = $achived / $kra1_var * 100;
                                        $achive_percent_kra1 = number_format($achive_percent_kra1, 2, '.', '');

                                        $achived2 = getSingleresult("SELECT count(activity_log.id) FROM  activity_log left join orders on activity_log.pid=orders.id where orders.team_id='" . $_SESSION['team_id'] . "' and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $_GET['date_from'] . "' and date(activity_log.created_date)<='" . $_GET['date_to'] . "'  and activity_log.call_subject in ('Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech. Support Visit','Validation Visit')");
                                        $kra2_var = $sales_team * 40;
                                        $achive_percent_kra2 = $achived2 / $kra2_var * 100;
                                        $achive_percent_kra2 = number_format($achive_percent_kra2, 2, '.', '');

                                        $achived3 = getSingleresult("select count(id) from raw_leads where team_id='" . $_SESSION['team_id'] . "' and date(created_date)>='" . $_GET['date_from'] . "' and date(created_date)<='" . $_GET['date_to'] . "'");
                                        $kra3_var = $var * 10;
                                        $achive_percent_kra3 = $achived3 / $kra3_var * 100;
                                        $achive_percent_kra3 = number_format($achive_percent_kra3, 2, '.', '');


                                        $achived4 = getSingleresult("SELECT count(activity_log.id) FROM  activity_log left join orders on activity_log.pid=orders.id where orders.team_id='" . $_SESSION['team_id'] . "' and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $_GET['date_from'] . "' and date(activity_log.created_date)<='" . $_GET['date_to'] . "'  and activity_log.call_subject in ('Call','LC Calling','Send Quote','Follow up Call')");
                                        $kra4_var = $var * 8;
                                        $achive_percent_kra4 = $achived4 / $kra4_var * 100;
                                        $achive_percent_kra4 = number_format($achive_percent_kra4, 2, '.', '');


                                        ?>
                                        <tr>
                                            <td>1</td>
                                            <td>NEW DR Code Per Month <i class="fa fa-info-circle" title="(4 x Authorization level Target)" data-toggle="tooltip"></i></td>
                                            <td><?= $kra1_var ?></td>
                                            <td><?= $achived ?></td>
                                            <td><?= $achive_percent_kra1 ?>%</td>
                                        </tr>

                                        <tr>
                                            <td>2</td>
                                            <td>Monthly 40 New Account to be visited <i class="fa fa-info-circle" title="(40 * by per dedicated sales person)" data-toggle="tooltip"></i></td>
                                            <td><?= $kra2_var ?></td>
                                            <td><?= $achived2 ?></td>
                                            <td><?= $achive_percent_kra2 ?>%</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>RAW Data to be added into the system <i class="fa fa-info-circle" title="(10 x Authorization CDGS Target)" data-toggle="tooltip"></i></td>
                                            <td> <?= $kra3_var ?> </td>
                                            <td><?= $achived3 ?></td>
                                            <td><?= $achive_percent_kra3 ?>%</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>ISS Log a call in DR Portal in new Accounts <i class="fa fa-info-circle" title="(Log a calls updated on accounts)" data-toggle="tooltip"></i></td>
                                            <td><?= $kra4_var ?></td>
                                            <td><?= $achived4 ?></td>
                                            <td><?= $achive_percent_kra4 ?>%</td>
                                        </tr>

                                    </tbody>
                                </table>

                            </div>
                            <div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="m-t-0 m-b-10">
    <?php }

    if ($_SESSION['user_type'] == 'USR' && ($_SESSION['role'] == 'SAL' || $_SESSION['role'] == 'TC')) {

        switch ($_SESSION['role']) {
            case 'SAL':
                $profile = 'Sales';
                break;
            case 'TC':
                $profile = 'ISS Caller';
                break;
            default:
                $profile = 'N/A';
        }

    ?>
        <div class="row">
            <!-- Column -->
            <div class="col-lg-12 col-md-7">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex flex">
                                    <div>
                                        <h5 class="card-title">User KRA</h3>
                                            <h6 class="card-subtitle"></h6>
                                    </div>

                                </div>
                            </div>
                            <div class="col-12">
                                <h3 class="text-blue"><?= $_SESSION['name'] . ' (' . $profile . ')' ?></h3>
                                <table id="" class="display nowrap table table-hover table-striped table-bordered " cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>KRA</th>
                                            <th>Target</th>
                                            <th>Achieved</th>
                                            <th>Achieved %</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php if ($_SESSION['role'] == 'SAL') {
                                            $sales_kra1 = getSingleresult("select IFNULL(sum(quantity),0) from orders where team_id='" . $_SESSION['team_id'] . "' and created_by='" . $_SESSION['user_id'] . "' and date(partner_close_date)>='" . $_GET['date_from'] . "' and date(partner_close_date)<='" . $_GET['date_to'] . "' and stage='OEM Billing'");

                                            $sales_kra2 = getSingleresult("select IFNULL(sum(quantity),0) from orders where team_id='" . $_SESSION['team_id'] . "' and created_by='" . $_SESSION['user_id'] . "' and date(partner_close_date)>='" . $_GET['date_from'] . "' and date(partner_close_date)<='" . $_GET['date_to'] . "' and is_dr=1");

                                            $sales_kra3 = getSingleresult("SELECT count(activity_log.id) FROM  activity_log left join orders on activity_log.pid=orders.id where orders.team_id='" . $_SESSION['team_id'] . "' and  orders.created_by='" . $_SESSION['user_id'] . "' and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $_GET['date_from'] . "' and date(activity_log.created_date)<='" . $_GET['date_to'] . "'  and activity_log.call_subject in ('Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech. Support Visit','Validation Visit')");

                                            $sales_kra4 = getSingleresult("select count(id) from raw_leads where team_id='" . $_SESSION['team_id'] . "' and created_by='" . $_SESSION['user_id'] . "' and date(created_date) between '" . $_GET['date_from'] . "' and '" . $_GET['date_to'] . "'");

                                            $total_sales_kra = $sales_kra1 + $sales_kra2 + $sales_kra3 + $sales_kra4
                                        ?>
                                            <tr>
                                                <td>1</td>
                                                <td>Sales Target Achievement</td>
                                                <td> <?php
                                                        $total_target = 0;
                                                        $user_target = getSingleresult("select kra from user_kra where kra_name='1' and user_id='" . $_SESSION['user_id'] . "' and   team_id=" . $_SESSION['team_id']);
                                                        $total_target += $user_target;
                                                        ?>
                                                    <?= ($user_target ? $user_target : 'N/A') ?>
                                                </td>
                                                <td><?= $sales_kra1 ?></td>
                                                <?php
                                                if ($user_target)
                                                    $kra1_percent = ($sales_kra1 / $user_target) * 100;
                                                $kra1_percent = number_format($kra1_percent, 2, '.', '');
                                                ?>
                                                <td><?= $kra1_percent ?>%</td>
                                            </tr>

                                            <tr>
                                                <td>2</td>
                                                <td>Monthly 40 New Account to be visited by Each sales team</td>
                                                <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='2' and user_id='" . $_SESSION['user_id'] . "' and   team_id=" . $_SESSION['team_id']);
                                                        $total_target += $user_target;
                                                        ?>
                                                    <?= ($user_target ? $user_target : 'N/A') ?>
                                                </td>
                                                <td><?= $sales_kra2 ?></td>
                                                <?php
                                                if ($user_target)
                                                    $kra2_percent = ($sales_kra2 / $user_target) * 100;
                                                $kra2_percent = number_format($kra2_percent, 2, '.', '');
                                                ?>
                                                <td><?= $kra2_percent ?>%</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Total #300 DVR Visits to be updated in DVR</td>
                                                <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='3' and user_id='" . $_SESSION['user_id'] . "' and   team_id=" . $_SESSION['team_id']);
                                                        $total_target += $user_target;
                                                        ?>
                                                    <?= ($user_target ? $user_target : 'N/A') ?>
                                                </td>
                                                <td><?= $sales_kra3 ?></td>
                                                <?php
                                                if ($user_target)
                                                    $kra3_percent = ($sales_kra3 / $user_target) * 100;
                                                $kra3_percent = number_format($kra3_percent, 2, '.', '');
                                                ?>
                                                <td><?= $kra3_percent ?>%</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>RAW Data to be added in DR 200 Per Month</td>
                                                <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='4' and user_id='" . $_SESSION['user_id'] . "' and   team_id=" . $_SESSION['team_id']);
                                                        $total_target += $user_target;
                                                        ?>
                                                    <?= ($user_target ? $user_target : 'N/A') ?>
                                                </td>
                                                <td><?= $sales_kra4 ?></td>
                                                <?php
                                                if ($user_target)
                                                    $kra4_percent = ($sales_kra4 / $user_target) * 100;
                                                $kra4_percent = number_format($kra4_percent, 2, '.', '');
                                                ?>
                                                <td><?= $kra4_percent ?>%</td>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>Total</th>
                                                <th><?= $total_target ?></th>
                                                <th><?= $total_sales_kra ?></th>
                                                <?php $total_kra = ($kra1_percent + $kra2_percent + $kra3_percent + $kra4_percent) / 4 ?>
                                                <th><?= $total_kra ?>%</th>
                                            </tr>
                                        <?php } else if ($_SESSION['role'] == 'TC') {
                                            $tc_kra1 = getSingleresult("select IFNULL(sum(quantity),0) from orders where team_id='" . $_SESSION['team_id'] . "' and created_by='" . $_SESSION['user_id'] . "' and date(partner_close_date)>='" . $_GET['date_from'] . "' and date(partner_close_date)<='" . $_GET['date_to'] . "' and stage='OEM Billing'");

                                            $tc_kra2 = getSingleresult("select IFNULL(sum(quantity),0) from orders where team_id='" . $_SESSION['team_id'] . "' and created_by='" . $_SESSION['user_id'] . "' and status='Approved' and date(partner_close_date)>='" . $_GET['date_from'] . "' and  date(partner_close_date)<='" . $_GET['date_to'] . "' ");

                                            $tc_kra3 = getSingleresult("SELECT count(activity_log.id) FROM  activity_log left join orders on activity_log.pid=orders.id where orders.team_id='" . $_SESSION['team_id'] . "' and  orders.created_by='" . $_SESSION['user_id'] . "' and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $_GET['date_from'] . "'  and date(activity_log.created_date)<='" . $_GET['date_to'] . "'  and activity_log.call_subject in ('Call','LC Calling','Send Quote','Follow up Call')");

                                            $tc_kra4 = getSingleresult("SELECT count(activity_log.id) FROM  activity_log left join orders on activity_log.pid=orders.id where orders.team_id='" . $_SESSION['team_id'] . "' and  orders.created_by='" . $_SESSION['user_id'] . "' and orders.license_type='Commercial'  and date(activity_log.created_date)>='" . $_GET['date_from'] . "'  and date(activity_log.created_date)<='" . $_GET['date_to'] . "'   and activity_log.call_subject in ('Call','LC Calling','Send Quote','Follow up Call')");
                                            $total_tc_achived = $tc_kra1 + $tc_kra2 + $tc_kra3 + $tc_kra4
                                        ?>

                                            <tr>
                                                <td>1</td>
                                                <td>Sales Target Achievement</td>
                                                <td> <?php
                                                        $total_target = 0;
                                                        $user_target = getSingleresult("select kra from user_kra where kra_name='1' and user_id='" . $_SESSION['user_id'] . "' and   team_id=" . $_SESSION['team_id']);
                                                        $total_target += $user_target;
                                                        ?>

                                                    <?= ($user_target ? $user_target : 'N/A') ?>

                                                </td>
                                                <td><?= $tc_kra1 ?></td>
                                                <?php
                                                if ($user_target)
                                                    $kra1_percent = ($tc_kra1 / $user_target) * 100;
                                                $kra1_percent = number_format($kra1_percent, 2, '.', '');
                                                ?>
                                                <td><?= $kra1_percent ?>%</td>
                                            </tr>

                                            <tr>
                                                <td>2</td>
                                                <td>NEW DR Code Per Month</td>
                                                <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='2' and user_id='" . $_SESSION['user_id'] . "' and   team_id=" . $_SESSION['team_id']);
                                                        $total_target += $user_target;
                                                        ?>
                                                    <?= ($user_target ? $user_target : 'N/A') ?>
                                                </td>
                                                <td><?= $tc_kra2 ?></td>
                                                <?php
                                                if ($user_target)
                                                    $kra2_percent = ($tc_kra2 / $user_target) * 100;
                                                $kra2_percent = number_format($kra2_percent, 2, '.', '');
                                                ?>
                                                <td><?= $kra2_percent ?>%</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>RAW Data to be added in DR 200 Per Month</td>
                                                <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='3' and user_id='" . $_SESSION['user_id'] . "' and   team_id=" . $_SESSION['team_id']);
                                                        $total_target += $user_target;
                                                        ?>
                                                    <?= ($user_target ? $user_target : 'N/A') ?>
                                                </td>
                                                <td><?= $tc_kra3 ?></td>
                                                <?php
                                                if ($user_target)
                                                    $kra3_percent = ($tc_kra3 / $user_target) * 100;
                                                $kra3_percent = number_format($kra3_percent, 2, '.', '');
                                                ?>
                                                <td><?= $kra3_percent ?>%</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>ISS Log a call in DR Portal in new Accounts</td>
                                                <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='4' and user_id='" . $_SESSION['user_id'] . "' and   team_id=" . $_SESSION['team_id']);
                                                        $total_target += $user_target;
                                                        ?>
                                                    <?= ($user_target ? $user_target : 'N/A') ?>
                                                </td>
                                                <td><?= $tc_kra4 ?></td>
                                                <?php
                                                if ($user_target)
                                                    $kra4_percent = ($tc_kra4 / $user_target) * 100;
                                                $kra4_percent = number_format($kra4_percent, 2, '.', '');
                                                ?>
                                                <td><?= $kra4_percent ?>%</td>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>Total</th>
                                                <th><?= $total_target ?></th>
                                                <th><?= $total_tc_achived ?></th>
                                                <?php $total_kra = ($kra1_percent + $kra2_percent + $kra3_percent + $kra4_percent) / 4 ?>
                                                <th><?= $total_kra ?>%</th>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="m-t-10 m-b-10">
    <?php }
    if ($_SESSION['user_type'] != 'CQM') { ?>
        <div class="row mt-t-10">
            <!-- Column -->
            <div class="col-lg-5 col-md-7">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex flex">
                                    <div>
                                        <?php if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN'|| $_SESSION['user_type'] == 'RADMIN'|| $_SESSION['user_type'] == 'OPERATIONS'||$_SESSION['user_type'] == 'RM') { ?>
                                            <h5 class="card-title">VAR Data Submisison(No. of Seats)</h3>
                                            <?php  } else {
                                            if ($_SESSION['user_type'] == 'MNGR') {
                                                echo "<h5 class='card-title'>" . getSingleresult("select name from partners where id=" . $_SESSION['team_id']) . " Data Submisison(No. of Seats)</h3>";
                                            } else {
                                                echo "<h5 class='card-title'>" . $_SESSION['name'] . " Data Submisison(No. of Seats)</h3>";
                                            }
                                        } ?>
                                            <h6 class="card-subtitle"></h6>
                                    </div>

                                </div>
                            </div>
                            <div class="col-12">
                                <div class="amp-pxl" style="height: 360px;"></div>
                            </div>
                            <div>
                                <hr class="m-t-0 m-b-0">
                            </div>
                            <div class="card-body text-center ">
                                <ul class="list-inline m-b-0">
                                    <li>
                                        <span class="text-muted text-success font-10"><i class="fa fa-circle font-8 m-r-10 "></i>Data Received</span> </li>
                                    <li>
                                        <span class="text-muted  text-info font-10"><i class="fa fa-circle font-8 m-r-10"></i>Data Qualified</span> </li>
                                    <li>
                                        <span class="text-muted  text-warning font-10"><i class="fa fa-circle font-8 m-r-10"></i>Data Unqualified</span> </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-5">
                <div class="card">
                    <div class="card-body">

                        <?php if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN'|| $_SESSION['user_type'] == 'RADMIN'|| $_SESSION['user_type'] == 'OPERATIONS'||$_SESSION['user_type'] == 'RM') { ?>
                            <h5 class="card-title">VAR Stage Wise Report(No. of Seats)</h3>
                            <?php  } else {
                            if ($_SESSION['user_type'] == 'MNGR') {
                                echo "<h5 class='card-title'>" . getSingleresult("select name from partners where id=" . $_SESSION['team_id']) . " Stage Wise Report(No. of Seats)</h3>";
                            } else {
                                echo "<h5 class='card-title'>" . $_SESSION['name'] . " Stage Wise Report(No. of Seats)</h3>";
                            }
                        } ?>
                            <h6 class="card-subtitle"> </h6>
                            <div class="campaign2" style="height:405px; width:100%;"></div>
                    </div>

                </div>
            </div>
        </div>
		<hr class="m-t-10 m-b-10">
        <!-- Row -->
        <?php if ($_SESSION['user_type'] == 'MNGR') { ?>
            <div class="row">
                <!-- Column -->
                <div class="col-lg-12 col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex flex">
                                        <div>
                                            <h5 class="card-title">User Wise Data Submisison</h3>
                                                <h6 class="card-subtitle"></h6>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="amp-pxl1" style="height: 360px;"></div>
                                </div>
                                <div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
    } else { ?>
    </div>
	</div><!--dashboard-main-div-->
    <div class="row">
        <!-- Column -->
        <div class="col-lg-12 col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex flex">
                                <div>
                                    <h5 class="card-title">Team Average Quality Score</h3>
                                        <h6 class="card-subtitle"></h6>
                                </div>

                            </div>
                        </div>
                        <div class="col-12">
                            <div class="amp-pxl2" style="height:1000px;"></div>
                        </div>
                        <div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    <?php } ?>

    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Right sidebar -->
    <!-- ============================================================== -->
    <!-- .right-sidebar -->

    <!-- ============================================================== -->
    <!-- End Right sidebar -->
    <!-- ============================================================== -->
    </div>

    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <?php

    if (!$_GET['d_from']) {
        $dat1 = date('Y-m-01');
        $dat2 = date('Y-m-t');
    } else {
        $dat1 = $_GET['d_from'];
        $dat2 = $_GET['d_to'];
    }
    ?>

    <?php include('includes/footer.php') ?>
    <script src="js/custom.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!-- chartist chart -->
    <script src="assets/plugins/chartist-js/dist/chartist.min.js"></script>
    <script src="assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
    <!--c3 JavaScript -->
	
	<script>
        //jQuery("#search_toogle").click(function() {
          //  jQuery(".search_form").toggle("slow");
       // });

        var wfheight = $(window).height();

        $('.dashboard-main-div').height(wfheight - 250);



        $('.dashboard-main-div').slimScroll({
            color: '#00f',
            size: '10px',
            height: 'auto',


        });
    </script>

    <script>
        $(function() {
            $('.datepicker').daterangepicker({

                "singleDatePicker": true,
                "showDropdowns": true,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                //startDate: '2017-01-01',
                //autoUpdateInput: false,

            });


        });
    </script>
    <?php
    if ($_SESSION['user_type'] == 'MNGR' || $_SESSION['user_type'] == 'USR') {
    ?>
        <script>
            $(function() {
                $.ajax({
                    type: 'POST',
                    url: 'self_review.php',
                    success: function(response) {
                        $("#selfReview").html();
                        $("#selfReview").html(response);
                        $('#selfReview').modal('show');
                    }
                });
            });
        </script>
    <?php
    }
    ?>



    <!-- Chart JS -->
    <?php if ($_SESSION['user_type'] == 'ADMIN' || $_SESSION['user_type'] == 'SUPERADMIN'|| $_SESSION['user_type'] == 'RADMIN'|| $_SESSION['user_type'] == 'OPERATIONS'||$_SESSION['user_type'] == 'RM') {
        if ($_SESSION['sales_manager'] == 1) {
            $contd = " and orders.team_id in (" . $_SESSION['access'] . ") ";
        }
        //echo "select COALESCE(sum(orders.quantity),0) from orders join lead_review on orders.id=lead_review.lead_id where lead_review.is_review=1 and dvr_flag=0 and  ((date(created_date) BETWEEN  '".$dat1."' and '".$dat2."') or (date(prospecting_date) BETWEEN  '".$dat1."' and '".$dat2."') ) ".$contd;
    ?>
        <script>
            $(function() {
                "use strict";
                // ============================================================== 
                // Sales overview
                // ============================================================== 
                var chart2 = new Chartist.Bar('.amp-pxl', {
                    labels: ['Data Received', 'Data Qualified', 'Data Unqualified'],
                    series: [<?= getSingleresult("select COALESCE(sum(quantity),0) from orders where 1 and dvr_flag=0 and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) " . $contd) ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where 1 and dvr_flag=0 and status='Approved' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) " . $contd) ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where 1 and dvr_flag=0 and dvr_flag=0 and status='Cancelled' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') )" . $contd) ?>]
                }, {
                    distributeSeries: true,
                    plugins: [
                        Chartist.plugins.tooltip()
                    ]
                });

                var chart3 = new Chartist.Bar('.campaign2', {
                    labels: ['<a href=review_leads.php?d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>Review</a>', '<a href=search_orders.php?dash=yes&stage=OEM%20Billing&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>OEM Billing</a>', '<a href=search_orders.php?dash=yes&stage=Booking&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>Booking</a>', '<a href=search_orders.php?dash=yes&stage=EU%20PO%20Issued&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>EU PO Issued</a>', '<a href=search_orders.php?dash=yes&stage=Commit&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>Commit</a>', '<a href=search_orders.php?dash=yes&stage=Follow-Up&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>Follow-Up</a>', '<a href=search_orders.php?dash=yes&stage=Quote&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>Quote<a>'],
                    series: [
                        [<?= getSingleresult("select COALESCE(sum(orders.quantity),0) from orders join lead_review on orders.id=lead_review.lead_id where lead_review.is_review=1 and dvr_flag=0 and  date(lead_review.added_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "' " . $contd) ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where stage='OEM Billing' and dvr_flag=0 and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) " . $contd) ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where stage='Booking' and dvr_flag=0 and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') )" . $contd) ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where dvr_flag=0 and stage='EU PO Issued' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) " . $contd) ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where stage='Commit' and dvr_flag=0 and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) " . $contd) ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where stage='Follow-up' and dvr_flag=0 and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) " . $contd) ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where stage='Quote' and dvr_flag=0 and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) " . $contd) ?>]
                    ]
                }, {
                    //distributeSeries: true,
                    horizontalBars: true,
                    plugins: [
                        Chartist.plugins.tooltip()
                    ],
                    axisY: {
                        offset: 70
                    }
                });



            });
        </script>
    <?php }
    if ($_SESSION['user_type'] == 'USR') { ?>

        <script>
            $(function() {
                "use strict";
                // ============================================================== 
                // Sales overview
                // ============================================================== 
                var chart2 = new Chartist.Bar('.amp-pxl', {
                    labels: ['Data Received', 'Data Qualified', 'Data Unqualified'],
                    series: [<?= getSingleresult("select COALESCE(sum(quantity),0) from orders where 1 and dvr_flag=0 and created_by='" . $_SESSION['user_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where 1 and status='Approved' and dvr_flag=0 and created_by='" . $_SESSION['user_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where 1 and status='Cancelled' and dvr_flag=0 and created_by='" . $_SESSION['user_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>]
                }, {
                    distributeSeries: true,
                    plugins: [
                        Chartist.plugins.tooltip()
                    ]
                });

                var chart3 = new Chartist.Bar('.campaign2', {
                    labels: ['Review', '<a href=search_partner_lead.php?stage=OEM%20Billing&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>OEM Billing</a>', '<a href=search_partner_lead.php?stage=Booking&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>Booking</a>', '<a href=search_partner_lead.php?stage=EU%20PO%20Issued&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>EU PO Issued</a>', '<a href=search_partner_lead.php?stage=Commit&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>Commit</a>', '<a href=search_partner_lead.php?stage=Follow-Up&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>Follow-Up</a>', '<a href=search_partner_lead.php?stage=Quote&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>Quote<a>'],
                    series: [
                        [<?= getSingleresult("select COALESCE(sum(orders.quantity),0) from orders join lead_review on orders.id=lead_review.lead_id where lead_review.is_review=1 and dvr_flag=0 and  created_by='" . $_SESSION['user_id'] . "' and  date(lead_review.added_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "' ") ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where stage='OEM Billing' and dvr_flag=0 and created_by='" . $_SESSION['user_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where stage='Booking' and dvr_flag=0 and created_by='" . $_SESSION['user_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where stage='EU PO Issued' and dvr_flag=0 and created_by='" . $_SESSION['user_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where stage='Commit' and dvr_flag=0 and created_by='" . $_SESSION['user_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where stage='Follow-up' and dvr_flag=0 and created_by='" . $_SESSION['user_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where stage='Quote' and dvr_flag=0 and created_by='" . $_SESSION['user_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>]
                    ]
                }, {
                    //distributeSeries: true,
                    horizontalBars: true,
                    plugins: [
                        Chartist.plugins.tooltip()
                    ],
                    axisY: {
                        offset: 70
                    }
                });



            });
        </script>


    <?php }
    if ($_SESSION['user_type'] == 'MNGR') {

        $users_list = db_query("select id,name from users where user_type='USR' and team_id='" . $_SESSION['team_id'] . "' and status='Active'");
        while ($row = db_fetch_array($users_list)) {
            $user_array[] = $row['name'];
            $approved[] = getSingleresult("select COALESCE(sum(quantity),0) from orders where 1 and dvr_flag=0 and status='Approved' and created_by='" . $row['id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ");
            $unqalified[] = getSingleresult("select COALESCE(sum(quantity),0) from orders where 1 and dvr_flag=0 and status='Cancelled' and created_by='" . $row['id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ");
        }
        $string_users = implode("','", $user_array);
        $string_approved = implode("','", $approved);
        $string_unqualified = implode("','", $unqalified);
    ?>
        <script>
            $(function() {
                "use strict";
                // ============================================================== 
                // Sales overview
                // ============================================================== 
                var chart2 = new Chartist.Bar('.amp-pxl', {
                    labels: ['Data Received', 'Data Qualified', 'Data Unqualified'],
                    series: [<?= getSingleresult("select COALESCE(sum(quantity),0) from orders where 1 and dvr_flag=0 and team_id='" . $_SESSION['team_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where 1 and dvr_flag=0 and status='Approved' and team_id='" . $_SESSION['team_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where 1 and dvr_flag=0 and status='Cancelled' and team_id='" . $_SESSION['team_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>]
                }, {
                    distributeSeries: true,
                    plugins: [
                        Chartist.plugins.tooltip()
                    ]
                });

                var chart3 = new Chartist.Bar('.campaign2', {
                    labels: ['Review', '<a href=search_partner_lead.php?stage=OEM%20Billing&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>OEM Billing</a>', '<a href=search_partner_lead.php?stage=Booking&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>Booking</a>', '<a href=search_partner_lead.php?stage=EU%20PO%20Issued&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>EU PO Issued</a>', '<a href=search_partner_lead.php?stage=Commit&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>Commit</a>', '<a href=search_partner_lead.php?stage=Follow-Up&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>Follow-Up</a>', '<a href=search_partner_lead.php?stage=Quote&d_from=<?= $dat1 ?>&d_to=<?= $dat2 ?>>Quote<a>'],
                    series: [
                        [<?= getSingleresult("select COALESCE(sum(orders.quantity),0) from orders join lead_review on orders.id=lead_review.lead_id where lead_review.is_review=1 and dvr_flag=0 and team_id='" . $_SESSION['team_id'] . "' and  date(lead_review.added_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "'") ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where stage='OEM Billing' and dvr_flag=0 and team_id='" . $_SESSION['team_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where stage='Booking' and dvr_flag=0 and team_id='" . $_SESSION['team_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where stage='EU PO Issued' and dvr_flag=0 and team_id='" . $_SESSION['team_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where stage='Commit' and dvr_flag=0 and team_id='" . $_SESSION['team_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where stage='Follow-up' and dvr_flag=0 and team_id='" . $_SESSION['team_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>, <?= getSingleresult("select COALESCE(sum(quantity),0) from orders where stage='Quote' and dvr_flag=0 and team_id='" . $_SESSION['team_id'] . "' and  ((date(created_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') or (date(prospecting_date) BETWEEN  '" . $dat1 . "' and '" . $dat2 . "') ) ") ?>]
                    ]
                }, {
                    //distributeSeries: true,
                    horizontalBars: true,
                    plugins: [
                        Chartist.plugins.tooltip()
                    ],
                    axisY: {
                        offset: 70
                    }
                });

                var data = {
                    labels: ['<?= $string_users ?>'],
                    series: [
                        ['<?= $string_approved ?>'],
                        ['<?= $string_unqualified ?>']
                    ]
                };

                var options = {
                    seriesBarDistance: 10,
                    plugins: [
                        Chartist.plugins.tooltip()
                    ],
                };

                var responsiveOptions = [
                    ['screen and (max-width: 640px)', {
                        seriesBarDistance: 5,
                        axisX: {
                            labelInterpolationFnc: function(value) {
                                return value[0];
                            }
                        }
                    }]
                ];

                new Chartist.Bar('.amp-pxl1', data, options, responsiveOptions);



            });
        </script>

    <?php } ?>

    <?php if ($_SESSION['user_type'] == 'CQM') {

        $users_list = db_query("select id,name from callers where  1 ");
        while ($row = db_fetch_array($users_list)) {
            $user_array[] = $row['name'];
            $approved[] = getSingleresult("select COALESCE(AVG(total_score),0) from call_quality where 1 and caller='" . $row['id'] . "'");
            // $unqalified[]=getSingleresult("select COALESCE(sum(quantity),0) from orders where 1 and dvr_flag=0 and status='Cancelled' and created_by='".$row['id']."' and  ((date(created_date) BETWEEN  '".$dat1."' and '".$dat2."') or (date(prospecting_date) BETWEEN  '".$dat1."' and '".$dat2."') ) ");
        }
        $string_users = implode("','", $user_array);
        $string_approved = implode("','", $approved);
        //$string_unqualified=implode("','",$unqalified);
    ?>
        <script>
            $(function() {
                "use strict";
                // ============================================================== 
                // Sales overview
                // ============================================================== 


                var data = {
                    labels: ['<?= $string_users ?>'],
                    series: [
                        ['<?= $string_approved ?>']
                    ]
                };

                var options = {
                    seriesBarDistance: 10,
                    plugins: [
                        Chartist.plugins.tooltip()
                    ],
                    horizontalBars: true,
                    axisY: {
                        offset: 120
                    },

                };

                var responsiveOptions = [
                    ['screen and (max-width: 640px)', {
                        seriesBarDistance: 5,
                        axisX: {
                            labelInterpolationFnc: function(value) {
                                return value[0];
                            }
                        }
                    }]
                ];

                new Chartist.Bar('.amp-pxl2', data, options, responsiveOptions);



            });
        </script>
		
		

    <?php } ?>