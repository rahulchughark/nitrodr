<?php include('includes/header.php');

if (!$_GET['date_from']) {
    $_GET['date_from'] = date('Y-m-01');
}
if (!$_GET['date_to']) {
    $_GET['date_to'] = date('Y-m-t');
}
if ($_POST['kra1']) {
    if (!getSingleresult("select id from user_kra where user_id='" . $_POST['user_id'] . "' and kra_name=1"))
        $data_save = db_query("INSERT INTO `user_kra`(kra_name,`kra`, `team_id`,user_id,created_by) VALUES (1,'" . $_POST['kra1'] . "','" . $_SESSION['team_id'] . "','" . $_POST['user_id'] . "','" . $_SESSION['user_id'] . "') ON DUPLICATE KEY UPDATE kra='" . $_POST['kra1'] . "'");
    else
        $data_save = db_query("update `user_kra` set kra='" . $_POST['kra1'] . "' where user_id='" . $_POST['user_id'] . "' and kra_name=1");
}
if ($_POST['kra2']) {
    if (!getSingleresult("select id from user_kra where user_id='" . $_POST['user_id'] . "' and kra_name=2"))
        $data_save = db_query("INSERT INTO `user_kra`(kra_name,`kra`, `team_id`,user_id,created_by) VALUES (2,'" . $_POST['kra2'] . "','" . $_SESSION['team_id'] . "','" . $_POST['user_id'] . "','" . $_SESSION['user_id'] . "')");
    else
        $data_save = db_query("update `user_kra` set kra='" . $_POST['kra2'] . "' where user_id='" . $_POST['user_id'] . "' and kra_name=2");
}
if ($_POST['kra3']) {
    if (!getSingleresult("select id from user_kra where user_id='" . $_POST['user_id'] . "' and kra_name=3"))
        $data_save = db_query("INSERT INTO `user_kra`(kra_name,`kra`, `team_id`,user_id,created_by) VALUES (3,'" . $_POST['kra3'] . "','" . $_SESSION['team_id'] . "','" . $_POST['user_id'] . "','" . $_SESSION['user_id'] . "')");
    else
        $data_save = db_query("update `user_kra` set kra='" . $_POST['kra3'] . "' where user_id='" . $_POST['user_id'] . "' and kra_name=3");
}

if ($_POST['kra4']) {
    if (!getSingleresult("select id from user_kra where user_id='" . $_POST['user_id'] . "' and kra_name=4"))
        $data_save = db_query("INSERT INTO `user_kra`(kra_name,`kra`, `team_id`,user_id,created_by) VALUES (4,'" . $_POST['kra4'] . "','" . $_SESSION['team_id'] . "','" . $_POST['user_id'] . "','" . $_SESSION['user_id'] . "')");
    else
        $data_save = db_query("update `user_kra` set kra='" . $_POST['kra4'] . "' where user_id='" . $_POST['user_id'] . "' and kra_name=4");
}

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


?>

<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">KRA</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item active">KRA</li>
                </ol>
            </div>
            <div class="col-md-7 col-4 align-self-center">
                <div class="d-flex m-t-10 justify-content-end">


                    <div class="">

                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row search_form_design">
                            <div class="col-lg-4">
                                <h4 class="card-title">KRA</h4>
                            </div>
                            <!--col-lg-4-->
                            <div class="col-lg-8">
                                <div style="float:right;margin-bottom:10px">

                                    <form method="get" name="search">
                                        <input type="text" value="<?php echo @$_GET['date_from'] ?>" class="datepicker" id="date_from" name="date_from" placeholder="Date" />
                                        <input type="text" value="<?php echo @$_GET['date_to'] ?>" class="datepicker" id="date_to" name="date_to" placeholder="Date" />
                                        <input type="submit" class="btn btn-primary" value="Search" />
                                        <input type="button" class="btn btn-warning" value="Clear" onclick="clear_search()" />
                                    </form>

                                </div>
                            </div>
                            <!--col-lg-8-->
                        </div>
                        <!--row-->
                        <div class="table-responsive">
                            <h3 class="text-blue">VAR Organization KRA (Business Owners)</h3>
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
                                    $users1 = db_query("select id,role from users where team_id='" . $_SESSION['team_id'] . "' and status='Active' ");
                                    $ids = array();
                                  
                                    while ($uid = db_fetch_array($users1)) {
                                        $ids[] = $uid['id'];
                                    }
                                    $user_ids = implode(',', $ids);
                                    
                                    $achieved = 
                                    // "select count(distinct(o.id)) from orders as o left join tbl_lead_product as tp on o.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and o.license_type='Commercial' and o.status='Approved' and o.team_id='" . $_SESSION['team_id'] . "' and date(o.approval_time)>='" . $_GET['date_from'] . "' and o.created_by in (" . $user_ids . ") and date(o.approval_time)<='" . $_GET['date_to'] . "'";
                                    // print_r($achieved);
                                    newDR_KRA_BO($_SESSION['team_id'],$_GET['date_from'],$_GET['date_to'],$user_ids);
                                    
                                    $kra1_var = $var * 4;
                                    $achieved_percent_kra1 = $achieved / $kra1_var * 100;
                                    $achieved_percent_kra1 = round($achieved_percent_kra1);
                                    //number_format($achieved_percent_kra1, 2, '.', '');

                                    $log_call = logCall_lead_BO($_SESSION['team_id'],$_GET['date_from'],$_GET['date_to'],$user_ids) + logCall_lapsed_BO($_SESSION['team_id'],$_GET['date_from'],$_GET['date_to'],$user_ids) + logCall_raw_BO($_SESSION['team_id'],$_GET['date_from'],$_GET['date_to'],$user_ids);


                                    $dvr = dvr_BO_KRA($_SESSION['team_id'],$_GET['date_from'],$_GET['date_to'],$user_ids);
                                   // getSingleresult("select count(o.id) from orders as o where o.is_dr=1 and o.license_type='Commercial' and date(o.created_date)>='" . $_GET['date_from'] . "' and date(o.created_date)<='" . $_GET['date_to'] . "' and o.team_id='" . $_SESSION['team_id'] . "'");

                                    $converted_dvr = convertedDRV_BO($_SESSION['team_id'],$_GET['date_from'],$_GET['date_to'],$user_ids);
                                    //getSingleresult("select count(o.id) from orders as o where o.is_dr=1 and o.license_type='Commercial' and date(o.convert_date)>='" . $_GET['date_from'] . "' and date(o.convert_date)<='" . $_GET['date_to'] . "' and o.team_id='" . $_SESSION['team_id'] . "'");

                                    $achieved2 = $log_call + $dvr + $converted_dvr;

                                    $kra2_var = $sales_team * 40;
                                    $achieve_percent_kra2 = $achieved2 / $kra2_var * 100;
                                    $achieve_percent_kra2 = round($achieve_percent_kra2);
                                    //number_format($achieve_percent_kra2, 2, '.', '');

                                    $achieved3 = rawLeads_BO($_SESSION['team_id'],$_GET['date_from'],$_GET['date_to'],$user_ids);
                                    //getSingleresult("select count(id) from raw_leads where team_id='" . $_SESSION['team_id'] . "' and date(created_date)>='" . $_GET['date_from'] . "' and date(created_date)<='" . $_GET['date_to'] . "'");
                                    $kra3_var = $var * 10;
                                    $achieve_percent_kra3 = $achieved3 / $kra3_var * 100;
                                    $achieve_percent_kra3 = round($achieve_percent_kra3);
                                    //number_format($achieve_percent_kra3, 2, '.', '');


                                    $achieved4 = 
                                     // "SELECT count(distinct(activity_log.id)) FROM  activity_log left join raw_leads as o on activity_log.pid=o.id where (o.product_type_id=1 or o.product_type_id=2) and o.team_id='" . $_SESSION['team_id'] . "' and date(activity_log.created_date)>='" . $_GET['date_from'] . "' and date(activity_log.created_date)<='" . $_GET['date_to'] . "' and activity_log.added_by in (" . $user_ids . ") and activity_log.call_subject in ('Call','LC Calling','Send Quote','Follow up Call') group by activity_log.pid ";
                                     // print_r($achieved4);
                                    logISS_raw_BO($_SESSION['team_id'],$_GET['date_from'],$_GET['date_to'],$user_ids);
                                    //logISS_lead_BO($_SESSION['team_id'],$_GET['date_from'],$_GET['date_to'],$user_ids) + logISS_lapsed_BO($_SESSION['team_id'],$_GET['date_from'],$_GET['date_to'],$user_ids) + logISS_raw_BO($_SESSION['team_id'],$_GET['date_from'],$_GET['date_to'],$user_ids);

                                    $kra4_var = $var * 8;
                                    $achieve_percent_kra4 = $achieved4 / $kra4_var * 100;
                                    $achieve_percent_kra4 = round($achieve_percent_kra4);
                                    //number_format($achieve_percent_kra4, 2, '.', '');


                                    ?>
                                    <tr>
                                        <td>1</td>
                                        <td>NEW DR Code Per Month <i class="fa fa-info-circle" title="(4 x Authorization level Target)" data-toggle="tooltip"></i></td>
                                        <td><?= $kra1_var ?></td>
                                        <td><?= $achieved ?></td>
                                        <td><?= $achieved_percent_kra1 ?>%</td>
                                    </tr>

                                    <tr>
                                        <td>2</td>
                                        <td>Monthly 40 New Account to be visited <i class="fa fa-info-circle" title="(40 * by per dedicated sales person)" data-toggle="tooltip"></i></td>
                                        <td><?= $kra2_var ?></td>
                                        <td><?= $achieved2 ?></td>
                                        <td><?= $achieve_percent_kra2 ?>%</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>RAW Data to be added into the system <i class="fa fa-info-circle" title="(10 x Authorization CDGS Target)" data-toggle="tooltip"></i></td>
                                        <td> <?= $kra3_var ?> </td>
                                        <td><?= $achieved3 ?></td>
                                        <td><?= $achieve_percent_kra3 ?>%</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>ISS Log a call in DR Portal in new Accounts <i class="fa fa-info-circle" title="(Log a calls updated on accounts)" data-toggle="tooltip"></i></td>
                                        <td><?= $kra4_var ?></td>
                                        <td><?= $achieved4 ?></td>
                                        <td><?= $achieve_percent_kra4 ?>%</td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>
                        <div class="row">
                            <div class="search_form_design-2">
                                <form method="get" name="search">
                                    <div class="row">
                                        <div class="col-lg-8">&nbsp;</div>
                                        <!--col-lg-8-->
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <select name="user_t" class="form-control">
                                                    <option value="">---User Type---</option>

                                                    <option <?= (($user_t == 'SAL') ? 'selected' : '') ?> value="SAL">Sales Manager</option>
                                                    <option <?= (($user_t == 'TC') ? 'selected' : '') ?> value="TC">Tele Caller</option>
                                                    <!-- <option  <?= (($user_t == 'AE') ? 'selected' : '') ?> value="AE">Application Engineer</option> -->
                                                </select>


                                                <input type="submit" class="btn btn-primary" value="Search" />
                                                <input type="button" class="btn btn-warning" value="Clear" onclick="clear_search()" />
                                            </div>
                                        </div>
                                        <!--col-md-4-->
                                    </div>
                                </form>



                                <div class="row">
                                    <?php

                                    if ($_GET['user_t']) {
                                        $cond = " and role='" . $_GET['user_t'] . "'";
                                    }
                                    $sql = db_query("select id,name,user_type,role from users where team_id='" . $_SESSION['team_id'] . "' and role not in ('BO','AE') and status='Active'" . $cond);

                                    $i = 1;
                                    while ($data = db_fetch_array($sql)) {

                                        switch ($data['role']) {
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
                                        <div class="table-responsive m-t-40 col-md-6">
                                            <h3 class="text-blue"> <?= $i ?>. <?= $data['name'] . ' (' . $profile . ')' ?></h3>
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

                                                    <?php if ($data['role'] == 'SAL') {
                                                        $sales_kra1 = sales_target($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']);
                                                        //getSingleresult("select IFNULL(sum(quantity),0) from orders where team_id='" . $_SESSION['team_id'] . "' and created_by='" . $data['id'] . "' and date(partner_close_date)>='" . $_GET['date_from'] . "' and date(partner_close_date)<='" . $_GET['date_to'] . "' and stage='OEM Billing'");

                                                        $sales_kra2 = logCall_lead_SALES($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']) +
                                                        logCall_lapsed_SALES($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']) +
                                                        logCall_raw_SALES($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']) +
                                                        dvr_SALES_KRA($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']) +
                                                        convertedDRV_SALES($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']);
                                                        //getSingleresult("select IFNULL(sum(quantity),0) from orders where team_id='" . $_SESSION['team_id'] . "' and created_by='" . $data['id'] . "' and date(partner_close_date)>='" . $_GET['date_from'] . "' and date(partner_close_date)<='" . $_GET['date_to'] . "' and is_dr=1");

                                                        $sales_kra3 = logDVR_lead_SALES($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']) +
                                                        logDVR_lapsed_SALES($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']) +
                                                        logDVR_raw_SALES($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']) +
                                                        dvr_SALES_KRA($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']) +
                                                        convertedDRV_SALES($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']);
                                                        //getSingleresult("SELECT count(activity_log.id) FROM  activity_log left join orders on activity_log.pid=orders.id where orders.team_id='" . $_SESSION['team_id'] . "' and  orders.created_by='" . $data['id'] . "' and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $_GET['date_from'] . "' and date(activity_log.created_date)<='" . $_GET['date_to'] . "'  and activity_log.call_subject in ('Client Demo Visit','Closure/Negotiation Visit','Cold Call/Visit','Demo Visit','Payment Collection Visit','Profiling Call Visit','Tech. Support Visit','Validation Visit')");

                                                        $sales_kra4 = rawLeads_SALES($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']);
                                                        //getSingleresult("select count(id) from raw_leads where team_id='" . $_SESSION['team_id'] . "' and created_by='" . $data['id'] . "' and date(created_date) between '" . $_GET['date_from'] . "' and '" . $_GET['date_to'] . "'");

                                                        $total_sales_kra = $sales_kra1 + $sales_kra2 + $sales_kra3 + $sales_kra4
                                                    ?>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Sales Target Achievement</td>
                                                            <td> <?php
                                                                    $total_target = 0;
                                                                    $user_target = getSingleresult("select kra from user_kra where kra_name='1' and user_id='" . $data['id'] . "' and   team_id=" . $_SESSION['team_id']);
                                                                    $total_target += $user_target;
                                                                    ?>
                                                                <form action="" method="post">
                                                                    <input class="form-control col-md-8" type="number" required min="0" name="kra1" value="<?= $user_target ?>" />&nbsp;<input type="submit" value="Save" class="btn btn-primary btn-xs" />
                                                                    <input type="hidden" name="user_id" value="<?= $data['id'] ?>" />
                                                                </form>
                                                            </td>
                                                            <td><?= $sales_kra1 ?></td>
                                                            <?php
                                                            if ($user_target)
                                                                $kra1_percent = ($sales_kra1 / $user_target) * 100;
                                                            $kra1_percent = round($kra1_percent);
                                                            //number_format($kra1_percent, 2, '.', '');
                                                            ?>
                                                            <td><?= $kra1_percent ?>%</td>
                                                        </tr>

                                                        <tr>
                                                            <td>2</td>
                                                            <td>Monthly 40 New Account to be visited by Each sales team</td>
                                                            <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='2' and user_id='" . $data['id'] . "' and   team_id=" . $_SESSION['team_id']);
                                                                    $total_target += $user_target;
                                                                    ?>
                                                                <form action="" method="post">
                                                                    <input class="form-control col-md-8" type="number" required min="0" name="kra2" value="<?= $user_target ?>" />&nbsp;<input type="submit" value="Save" class="btn btn-primary btn-xs" />
                                                                    <input type="hidden" name="user_id" value="<?= $data['id'] ?>" />
                                                                </form>
                                                            </td>
                                                            <td><?= $sales_kra2 ?></td>
                                                            <?php
                                                            if ($user_target)
                                                                $kra2_percent = ($sales_kra2 / $user_target) * 100;
                                                            $kra2_percent = round($kra2_percent);
                                                            //number_format($kra2_percent, 2, '.', '');
                                                            ?>
                                                            <td><?= $kra2_percent ?>%</td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>Total #300 DVR Visits to be updated in DVR</td>
                                                            <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='3' and user_id='" . $data['id'] . "' and   team_id=" . $_SESSION['team_id']);
                                                                    $total_target += $user_target;
                                                                    ?>
                                                                <form action="" method="post">
                                                                    <input class="form-control col-md-8" type="number" required min="0" name="kra3" value="<?= $user_target ?>" />&nbsp;<input type="submit" value="Save" class="btn btn-primary btn-xs" />
                                                                    <input type="hidden" name="user_id" value="<?= $data['id'] ?>" />
                                                                </form>
                                                            </td>
                                                            <td><?= $sales_kra3 ?></td>
                                                            <?php
                                                            if ($user_target)
                                                                $kra3_percent = ($sales_kra3 / $user_target) * 100;
                                                            $kra3_percent = round($kra3_percent);
                                                            //number_format($kra3_percent, 2, '.', '');
                                                            ?>
                                                            <td><?= $kra3_percent ?>%</td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>RAW Data to be added in DR 200 Per Month</td>
                                                            <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='4' and user_id='" . $data['id'] . "' and   team_id=" . $_SESSION['team_id']);
                                                                    $total_target += $user_target;
                                                                    ?>
                                                                <form action="" method="post">
                                                                    <input class="form-control col-md-8" type="number" required min="0" name="kra4" value="<?= $user_target ?>" />&nbsp;<input type="submit" value="Save" class="btn btn-primary btn-xs" />
                                                                    <input type="hidden" name="user_id" value="<?= $data['id'] ?>" />
                                                                </form>
                                                            </td>
                                                            <td><?= $sales_kra4 ?></td>
                                                            <?php
                                                            if ($user_target)
                                                                $kra4_percent = ($sales_kra4 / $user_target) * 100;
                                                            $kra4_percent = round($kra4_percent);
                                                            //number_format($kra4_percent, 2, '.', '');
                                                            ?>
                                                            <td><?= $kra4_percent ?>%</td>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th>Total</th>
                                                            <th><?= $total_target ?></th>
                                                            <th><?= $total_sales_kra ?></th>
                                                            <?php $total_kra = ($kra1_percent + $kra2_percent + $kra3_percent + $kra4_percent) / 4 ?>
                                                            <th><?= round($total_kra) ?>%</th>
                                                        </tr>
                                                    <?php } else if ($data['role'] == 'TC') {

                                                        $tc_kra1 = sales_target($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']);
                                                        //getSingleresult("select IFNULL(sum(quantity),0) from orders where team_id='" . $_SESSION['team_id'] . "' and created_by='" . $data['id'] . "' and date(partner_close_date)>='" . $_GET['date_from'] . "' and date(partner_close_date)<='" . $_GET['date_to'] . "' and stage='OEM Billing'");

                                                        $tc_kra2 = tc_approved($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']);
                                                        //getSingleresult("select IFNULL(sum(quantity),0) from orders where team_id='" . $_SESSION['team_id'] . "' and created_by='" . $data['id'] . "' and status='Approved' and date(partner_close_date)>='" . $_GET['date_from'] . "' and  date(partner_close_date)<='" . $_GET['date_to'] . "' ");

                                                        $tc_kra3 = rawLeads_SALES($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']);
                                                        //getSingleresult("SELECT count(activity_log.id) FROM  activity_log left join orders on activity_log.pid=orders.id where orders.team_id='" . $_SESSION['team_id'] . "' and  orders.created_by='" . $data['id'] . "' and orders.license_type='Commercial' and date(activity_log.created_date)>='" . $_GET['date_from'] . "'  and date(activity_log.created_date)<='" . $_GET['date_to'] . "'  and activity_log.call_subject in ('Call','LC Calling','Send Quote','Follow up Call')");

                                                        $tc_kra4 = 
                                                        // "SELECT count(distinct(activity_log.id)) FROM  activity_log left join orders on activity_log.pid=orders.id left join tbl_lead_product as tp on orders.id=tp.lead_id where (tp.product_type_id=1 or tp.product_type_id=2) and orders.team_id='" . $_SESSION['team_id'] . "' and  activity_log.added_by='" . $data['id'] . "' and orders.license_type='Commercial' and MONTH(activity_log.created_date)>=" . $_GET['date_from'] . " and YEAR(activity_log.created_date)<=" . $_GET['date_to'] . " and activity_log.call_subject in ('Call','LC Calling','Send Quote','Follow up Call')";
                                                        // print_r($tc_kra4 );
                                                        logCall_lead_TC($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']) +
                                                        logCall_lapsed_TC($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']) +
                                                        logCall_raw_TC($_SESSION['team_id'],$data['id'],$_GET['date_from'],$_GET['date_to']);

                                                        $total_tc_achived = $tc_kra1 + $tc_kra2 + $tc_kra3 + $tc_kra4
                                                    ?>

                                                        <tr>
                                                            <td>1</td>
                                                            <td>Sales Target Achievement</td>
                                                            <td> <?php
                                                                    $total_target = 0;
                                                                    $user_target = getSingleresult("select kra from user_kra where kra_name='1' and user_id='" . $data['id'] . "' and   team_id=" . $_SESSION['team_id']);
                                                                    $total_target += $user_target;
                                                                    ?>
                                                                <form action="" method="post">
                                                                    <input class="form-control col-md-8" type="number" required min="0" name="kra1" value="<?= $user_target ?>" />&nbsp;<input type="submit" value="Save" class="btn btn-primary btn-xs" />
                                                                    <input type="hidden" name="user_id" value="<?= $data['id'] ?>" />
                                                                </form>
                                                            </td>
                                                            <td><?= $tc_kra1 ?></td>
                                                            <?php
                                                            if ($user_target)
                                                                $kra1_percent = ($tc_kra1 / $user_target) * 100;
                                                            $kra1_percent = round($kra1_percent);
                                                            //number_format($kra1_percent, 2, '.', '');
                                                            ?>
                                                            <td><?= $kra1_percent ?>%</td>
                                                        </tr>

                                                        <tr>
                                                            <td>2</td>
                                                            <td>NEW DR Code Per Month</td>
                                                            <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='2' and user_id='" . $data['id'] . "' and   team_id=" . $_SESSION['team_id']);
                                                                    $total_target += $user_target;
                                                                    ?>
                                                                <form action="" method="post">
                                                                    <input class="form-control col-md-8" type="number" required min="0" name="kra2" value="<?= $user_target ?>" />&nbsp;<input type="submit" value="Save" class="btn btn-primary btn-xs" />
                                                                    <input type="hidden" name="user_id" value="<?= $data['id'] ?>" />
                                                                </form>
                                                            </td>
                                                            <td><?= $tc_kra2 ?></td>
                                                            <?php
                                                            if ($user_target)
                                                                $kra2_percent = ($tc_kra2 / $user_target) * 100;
                                                            $kra2_percent = round($kra2_percent);
                                                            //number_format($kra2_percent, 2, '.', '');
                                                            ?>
                                                            <td><?= $kra2_percent ?>%</td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>RAW Data to be added in DR 200 Per Month</td>
                                                            <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='3' and user_id='" . $data['id'] . "' and   team_id=" . $_SESSION['team_id']);
                                                                    $total_target += $user_target;
                                                                    ?>
                                                                <form action="" method="post">
                                                                    <input class="form-control col-md-8" type="number" required min="0" name="kra3" value="<?= $user_target ?>" />&nbsp;<input type="submit" value="Save" class="btn btn-primary btn-xs" />
                                                                    <input type="hidden" name="user_id" value="<?= $data['id'] ?>" />
                                                                </form>
                                                            </td>
                                                            <td><?= $tc_kra3 ?></td>
                                                            <?php
                                                            if ($user_target)
                                                                $kra3_percent = ($tc_kra3 / $user_target) * 100;
                                                            $kra3_percent = round($kra3_percent);
                                                            //number_format($kra3_percent, 2, '.', '');
                                                            ?>
                                                            <td><?= $kra3_percent ?>%</td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>ISS Log a call in DR Portal in new Accounts</td>
                                                            <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='4' and user_id='" . $data['id'] . "' and   team_id=" . $_SESSION['team_id']);
                                                                    $total_target += $user_target;
                                                                    ?>
                                                                <form action="" method="post">
                                                                    <input class="form-control col-md-8" type="number" required min="0" name="kra4" value="<?= $user_target ?>" />&nbsp;<input type="submit" value="Save" class="btn btn-primary btn-xs" />
                                                                    <input type="hidden" name="user_id" value="<?= $data['id'] ?>" />
                                                                </form>
                                                            </td>
                                                            <td><?= $tc_kra4 ?></td>
                                                            <?php
                                                            if ($user_target)
                                                                $kra4_percent = ($tc_kra4 / $user_target) * 100;
                                                            $kra4_percent = round($kra4_percent);
                                                            //number_format($kra4_percent, 2, '.', '');
                                                            ?>
                                                            <td><?= $kra4_percent ?>%</td>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th>Total</th>
                                                            <th><?= $total_target ?></th>
                                                            <th><?= $total_tc_achived ?></th>
                                                            <?php $total_kra = ($kra1_percent + $kra2_percent + $kra3_percent + $kra4_percent) / 4 ?>
                                                            <th><?= round($total_kra) ?>%</th>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php $i++;
                                        unset($sales_kra1, $sales_kra2, $sales_kra3, $sales_kra4, $kra1_percent, $kra2_percent, $kra3_percent, $kra4_percent, $tc_kra1, $tc_kra2, $tc_kra3, $tc_kra4);
                                    } ?>
                                </div>


                            </div>
                            <!--search_form_design-2-->

                        </div>
                    </div>
                </div>
            </div>
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
        <div id="myModal" class="modal fade" role="dialog">


        </div>
        <?php include('includes/footer.php') ?>
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable();
                $(document).ready(function() {
                    var table = $('#example').DataTable({
                        "columnDefs": [{
                            "visible": false,
                            "targets": 2
                        }],
                        "order": [
                            [2, 'desc']
                        ],
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                        ],
                        lengthMenu: [
                            [10, 25, 50, 100, 500, 1000],
                            ['10', '25', '50', '100', '500', '1000']
                        ],
                        "displayLength": 25,
                        "drawCallback": function(settings) {
                            var api = this.api();
                            var rows = api.rows({
                                page: 'current'
                            }).nodes();
                            var last = null;
                            api.column(2, {
                                page: 'current'
                            }).data().each(function(group, i) {
                                if (last !== group) {
                                    $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                                    last = group;
                                }
                            });
                        }
                    });
                    // Order by the grouping
                    $('#example tbody').on('click', 'tr.group', function() {
                        var currentOrder = table.order()[0];
                        if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                            table.order([2, 'desc']).draw();
                        } else {
                            table.order([2, 'asc']).draw();
                        }
                    });
                });
            });
            $('#example23').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                ]
            });

            function change_goal(a, b) {
                $.ajax({
                    type: 'POST',
                    url: 'get_dv_data.php',
                    data: {
                        uid: a,
                        date: b
                    },
                    success: function(response) {
                        $("#myModal").html();
                        $("#myModal").html(response);
                        $('#myModal').modal('show');
                    }
                });
            }
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

            function clear_search() {
                window.location = 'kra.php';
            }
        </script>

        <script>
            jQuery("#search_toogle").click(function() {
                jQuery(".search_form").toggle("slow");
            });

            var wfheight = $(window).height();

            $('.card').height(wfheight - 180);



            $('.card').slimScroll({
                color: '#00f',
                size: '10px',
                height: 'auto',


            });
        </script>