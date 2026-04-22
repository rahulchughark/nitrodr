<?php include('includes/header.php');
business_owner_page();

if (!$_GET['date_from']) {
    $_GET['date_from'] = date('Y-m-01');
}
if (!$_GET['date_to']) {
    $_GET['date_to'] = date('Y-m-t');
}

$month = date('n');
$year  =  date('Y');

$var = getSingleresult("select cdgs_target from partners where id=" . $_SESSION['team_id']);

$category = getSingleresult("select category from partners where id=" . $_SESSION['team_id']);

?>

<!-- ============================================================== -->
<!-- Page wrapper  -->
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

                                    <small class="text-muted">Home >KRA</small>
                                    <h4 class="font-size-14 m-0 mt-1">KRA</h4>
                                </div>
                            </div>


                            <div class="btn-group float-right" role="group" style="margin-top:12px;">

                                <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>
                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">
                                        <form method="get" name="search" class="form-horizontal" role="form">
                                            <div class="form-group">
                                                <div class="input-daterange input-group" id="datepicker-close-date">
                                                    <input type="text" value="<?php echo @$_GET['date_from'] ?>" class="form-control" id="date_from" name="date_from" placeholder="Date" />
                                                    <input type="text" value="<?php echo @$_GET['date_to'] ?>" class="form-control" id="date_to" name="date_to" placeholder="Date" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <select name="user_t" class="form-control">
                                                    <option value="">User Type</option>

                                                    <option <?= (($user_t == 'SAL') ? 'selected' : '') ?> value="SAL">Sales Manager</option>
                                                    <option <?= (($user_t == 'TC') ? 'selected' : '') ?> value="TC">Tele Caller</option>
                                                    <!-- <option  <?= (($user_t == 'AE') ? 'selected' : '') ?> value="AE">Application Engineer</option> -->
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>

                                        </form>

                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <h5 class="text-blue"><?= getSingleresult("select name from partners where id=" . $_SESSION['team_id']) ?> KRA</h5>
                                <table id="leads" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>KRA</th>
                                            <th>Weightage</th>
                                            <th>Achieved</th>
                                            <th>Achieved %</th>
                                        </tr>
                                    </thead>

                                    <tbody style="text-align:center;">

                                        <?php
                                        $cat_target = db_query("select * from admin_kra where cdgs_category='" . $category . "' and year=" . $year);
                                        $cat_arr = db_fetch_array($cat_target);

                                        $users1 = db_query("select id,role from users where team_id='" . $_SESSION['team_id'] . "' and status='Active' ");
                                        $ids = array();

                                        while ($uid = db_fetch_array($users1)) {
                                            $ids[] = $uid['id'];
                                        }
                                        $user_ids = implode(',', $ids);

                                        $achieved = LCcalling_emailer_BO($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                        $kra1_var = $cat_arr['new_dr'];
                                        $achieve_percent_kra1 = $achieved / $kra1_var * 100;
                                        $achieve_percent_kra1 = round($achieve_percent_kra1);
                                        //number_format($achieved_percent_kra1, 2, '.', '');

                                        $log_call = logCall_lead_BO($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids) + logCall_lapsed_BO($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids) + logCall_raw_BO($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                        $dvr = dvr_BO_KRA($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                        $converted_dvr = convertedDRV_BO($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                        $achieved2 = $log_call + $dvr + $converted_dvr;

                                        $kra2_var = $cat_arr['monthly_account'];
                                        $achieve_percent_kra2 = $achieved2 / $kra2_var * 100;
                                        $achieve_percent_kra2 = round($achieve_percent_kra2);
                                        //number_format($achieve_percent_kra2, 2, '.', '');

                                        $achieved3 = LCcalling_profiling_BO($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids) ;
                                        

                                        $kra3_var = $cat_arr['raw_data'];
                                        $achieve_percent_kra3 = $achieved3 / $kra3_var * 100;
                                        $achieve_percent_kra3 = round($achieve_percent_kra3);
                                        //number_format($achieve_percent_kra3, 2, '.', '');


                                        $achieved4 = totalLog_lead_BO($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids) + totalLog_lapsed_BO($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids) + totalLog_raw_BO($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                        $kra4_var = $cat_arr['total_log'];
                                        $achieve_percent_kra4 = $achieved4 / $kra4_var * 100;
                                        $achieve_percent_kra4 = round($achieve_percent_kra4);
                                        //number_format($achieve_percent_kra4, 2, '.', '');

                                        $achieved5 = sales_target_BO($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids) +iss_sales_target_BO($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids);
                                        $achieve_percent_kra5 = $achieved5 / $cat_arr['sales_target'] * 100;
                                        $achieve_percent_kra5 = round($achieve_percent_kra5);


                                        $achieved6 = freshCall_lead_BO($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids) + freshCall_DVR_BO($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids) + freshCall_lapsed_BO($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids) + freshCall_raw_BO($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                        $achieve_percent_kra6 = $achieved6 / $cat_arr['new_account'] * 100;
                                        $achieve_percent_kra6 = round($achieve_percent_kra6);

                                        $achieved7 = logDVR_lead_BO($_SESSION['team_id'],$user_ids, $_GET['date_from'], $_GET['date_to']) + logDVR_lapsed_BO($_SESSION['team_id'],$user_ids, $_GET['date_from'], $_GET['date_to']) + logDVR_raw_BO($_SESSION['team_id'],$user_ids, $_GET['date_from'], $_GET['date_to']) + dvr_BO_KRA($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'],$user_ids) + convertedDRV_BO($_SESSION['team_id'], $_GET['date_from'], $_GET['date_to'],$user_ids);
//print_r($achieved7);
                                        $achieve_percent_kra7 = $achieved7 / $cat_arr['total_visit'] * 100;
                                        $achieve_percent_kra7 = round($achieve_percent_kra7);

                                        ?>
<tr>
                                            <td>1</td>
                                            <td>Account for Lc calling with mailer validation</td>
                                            <td><?= $kra1_var ?></td>
                                            <td><?= $achieved ?></td>
                                            <td><?= $achieve_percent_kra1 ?>%</td>
                                        </tr>
                                       <tr>
                                            <td>2</td>
                                            <td>Account for Lc calling with profile remark and Usage confirmation</td>
                                            <td><?= $kra2_var ?></td>
                                            <td><?= $achieved3 ?></td>
                                            <td><?= $achieve_percent_kra3 ?>%</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Monthly 40 New Account to be visited <i class="fa fa-info-circle" title="(40 * by per dedicated sales person)" data-toggle="tooltip"></i></td>
                                            <td><?= $kra2_var ?></td>
                                            <td><?= $achieved2 ?></td>
                                            <td><?= $achieve_percent_kra2 ?>%</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Total Log-call @ DR Portal <i class="fa fa-info-circle" title="(Log a calls updated on accounts)" data-toggle="tooltip"></i></td>
                                            <td><?= $kra4_var ?></td>
                                            <td><?= $achieved4 ?></td>
                                            <td><?= $achieve_percent_kra4 ?>%</td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Sales Target Achievement <i class="fa fa-info-circle" title="(CDGS Target)" data-toggle="tooltip"></i></td>
                                            <td><?= $cat_arr['sales_target'] ?></td>
                                            <td><?= $achieved5 ?></td>
                                            <td><?= $achieve_percent_kra5 ?>%</td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>New account call per day 15 by each team <i class="fa fa-info-circle" title="(Sales + ISS)" data-toggle="tooltip"></i></td>
                                            <td><?= $cat_arr['new_account'] ?></td>
                                            <td><?= $achieved6 ?></td>
                                            <td><?= $achieve_percent_kra6 ?>%</td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>Total Account Visit to be done <i class="fa fa-info-circle" title="(Sales)" data-toggle="tooltip"></i></td>
                                            <td><?= $cat_arr['total_visit'] ?></td>
                                            <td><?= $achieved7 ?></td>
                                            <td><?= $achieve_percent_kra7 ?>%</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>

                        <div class="card-body">                          
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
                                        case 'Installation':
                                            $profile = 'Installation';
                                            break;
                                        default:
                                            $profile = 'N/A';
                                    }

                                ?>
                                    <div class="table-responsive m-t-40 col-md-6">
                                        <h5 class="text-blue"> <?= $i ?>. <?= $data['name'] . ' (' . $profile . ')' ?></h5><br>
                                        <table id="leads1" class="display nowrap table table-hover table-striped table-bordered kra_box" data-height="wfheight1" data-mobile-responsive="true" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>S.No.</th>
                                                    <th>KRA</th>
                                                    <th>Target</th>
                                                    <th>Achieved</th>
                                                    <th>Achieved %</th>

                                                </tr>
                                            </thead>

                                            <tbody style="text-align:center;">

                                                <?php if ($data['role'] == 'SAL') {
                                                    $sales_kra1 = sales_target($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) + iss_sales_target($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);
                                                    //getSingleresult("select IFNULL(sum(quantity),0) from orders where team_id='" . $_SESSION['team_id'] . "' and created_by='" . $data['id'] . "' and date(partner_close_date)>='" . $_GET['date_from'] . "' and date(partner_close_date)<='" . $_GET['date_to'] . "' and stage='OEM Billing'");

                                                    $sales_kra2 = logCall_lead_SALES($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) + logCall_lapsed_SALES($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) + logCall_raw_SALES($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) + dvr_SALES_KRA($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                    convertedDRV_SALES($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);
                                                    //getSingleresult("select IFNULL(sum(quantity),0) from orders where team_id='" . $_SESSION['team_id'] . "' and created_by='" . $data['id'] . "' and date(partner_close_date)>='" . $_GET['date_from'] . "' and date(partner_close_date)<='" . $_GET['date_to'] . "' and is_dr=1");

                                                    $sales_kra3 = logDVR_lead_SALES($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) + logDVR_lapsed_SALES($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                    logDVR_raw_SALES($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                    dvr_SALES_KRA($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                    convertedDRV_SALES($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);
                                   
                                                    $sales_kra4 = LCcalling_emailer($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);
                                                   
                                                    $sales_kra5 = freshCall_lead($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) + freshCall_DVR($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) + freshCall_lapsed($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) + freshCall_raw($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                                    $sales_kra6 = logCall_lead_TC($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                    logCall_lapsed_TC($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                    logCall_raw_TC($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                                    $sales_kra7 = LCcalling_profiling_SALES($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                                    $total_sales_kra = $sales_kra1 + $sales_kra2 + $sales_kra3 + $sales_kra4 + $sales_kra5 + $sales_kra6 + $sales_kra7
                                                ?>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Sales Target Achievement</td>
                                                        <td> <?php
                                                                $total_target = 0;
                                                                $user_target = userTarget_Kra(2, $data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);
                                                                //getSingleresult("select kra from user_kra where kra_name='2' and user_id='" . $data['id'] . "' and   team_id=" . $_SESSION['team_id']);
                                                                $total_target += $user_target;
                                                                ?>
                                                            <?= $user_target ? $user_target : 'N/A' ?>
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
                                                        <td> <?php $user_target = userTarget_Kra(3, $data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                                $total_target += $user_target;
                                                                ?>
                                                            <?= $user_target ? $user_target : 'N/A' ?>
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
                                                        <td>Total Account Visit to be done</td>
                                                        <td> <?php $user_target = userTarget_Kra(4, $data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);
                                                                $total_target += $user_target;
                                                                ?>
                                                            <?= $user_target ? $user_target : 'N/A' ?>
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
                                                        <td>Account for LC calling with mailer validation</td>
                                                        <td> <?php $user_target = userTarget_Kra(5, $data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);
                                                                $total_target += $user_target;
                                                                ?>
                                                            <?= $user_target ? $user_target : 'N/A' ?>
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
                                                        <td>5</td>
                                                        <td>New account call per day 15 by each team</td>
                                                        <td> <?php $user_target = userTarget_Kra(7, $data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                                $total_target += $user_target;
                                                                ?>
                                                            <?= $user_target ? $user_target : 'N/A' ?>
                                                        </td>
                                                        <td><?= $sales_kra5 ?></td>
                                                        <?php
                                                        if ($user_target)
                                                            $kra5_percent = ($sales_kra5 / $user_target) * 100;
                                                        $kra5_percent = round($kra5_percent);
                                                        //number_format($kra4_percent, 2, '.', '');
                                                        ?>
                                                        <td><?= $kra5_percent ?>%</td>
                                                    </tr>
                                                    <tr>
                                                        <td>6</td>
                                                        <td>Total Log-call @ DR Portal</td>
                                                        <td> <?php $user_target = userTarget_Kra(6, $data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                        $total_target += $user_target;
                                                        ?>
                                                        <?= $user_target ? $user_target : 'N/A' ?>
                                                        </td>
                                                        <td><?= $sales_kra6 ?></td>
                                                        <?php
                                                        if ($user_target)
                                                        $kra6_percent = ($sales_kra6 / $user_target) * 100;
                                                        $kra6_percent = round($kra6_percent);
                                                        //number_format($kra4_percent, 2, '.', '');
                                                        ?>
                                                        <td><?= $kra6_percent ?>%</td>
                                                    </tr>

                                                    <tr>
                                                        <td>7</td>
                                                        <td>Account for LC calling with profile remark and Usage confirmation</td>
                                                        <td> <?php $user_target = userTarget_Kra(1, $data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                        $total_target += $user_target;
                                                        ?>
                                                        <?= $user_target ? $user_target : 'N/A' ?>
                                                        </td>
                                                        <td><?= $sales_kra7 ?></td>
                                                        <?php
                                                        if ($user_target)
                                                        $kra7_percent = ($sales_kra7 / $user_target) * 100;
                                                        $kra7_percent = round($kra7_percent);
                                                        
                                                        ?>
                                                        <td><?= $kra7_percent ?>%</td>
                                                    </tr>

                                                    <tr>
                                                        <th></th>
                                                        <th>Total</th>
                                                        <th><?= $total_target ?></th>
                                                        <th><?= $total_sales_kra ?></th>
                                                        <?php $total_kra = $total_sales_kra / $total_target * 100 ?>
                                                        <th><?= (is_nan($total_kra) || is_infinite($total_kra)) ? 0 : round($total_kra)
                                                            ?>%</th>
                                                    </tr>
                                                <?php } else if ($data['role'] == 'TC') {

                                                    $tc_kra1 = sales_target($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) + iss_sales_target($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);
                                              

                                                    $tc_kra2 = LCcalling_profiling_SALES($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);
                                                    
                                                    $tc_kra3 = LCcalling_emailer($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                                    
                                                    $tc_kra4 = logCall_lead_TC($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                        logCall_lapsed_TC($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                        logCall_raw_TC($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                                    $tc_kra5 = freshCall_lead($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) + freshCall_DVR($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) + freshCall_lapsed($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) + freshCall_raw($_SESSION['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);


                                                    $total_tc_achived = $tc_kra1 + $tc_kra2 + $tc_kra3 + $tc_kra4 + $tc_kra5;
                                                ?>

                                                    <tr>
                                                        <td>1</td>
                                                        <td>Sales Target Achievement</td>
                                                        <td> <?php
                                                                $total_target = 0;
                                                                $user_target = userTarget_Kra(2, $data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);
                                                                $total_target += $user_target;
                                                                ?>
                                                            <?= $user_target ? $user_target : 'N/A' ?>
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
                                                        <td>Account for LC calling with profile remark and Usage confirmation</td>
                                                        <td> <?php $user_target = userTarget_Kra(1, $data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                                $total_target += $user_target;
                                                                ?>
                                                            <?= $user_target ? $user_target : 'N/A' ?>
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
                                                        <td>Account for LC calling with mailer validation</td>
                                                        <td> <?php $user_target = userTarget_Kra(5, $data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                                $total_target += $user_target;
                                                                ?>
                                                            <?= $user_target ? $user_target : 'N/A' ?>
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
                                                        <td>Total Log-call @ DR Portal</td>
                                                        <td> <?php $user_target = userTarget_Kra(6, $data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                                $total_target += $user_target;
                                                                ?>
                                                            <?= $user_target ? $user_target : 'N/A' ?>
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
                                                        <td>5</td>
                                                        <td>New account call per day 15 by each team</td>
                                                        <td> <?php $user_target = userTarget_Kra(7, $data['id'], $_SESSION['team_id'], $_GET['date_from'], $_GET['date_from']);

                                                                $total_target += $user_target;
                                                                ?>
                                                            <?= $user_target ? $user_target : 'N/A' ?>
                                                        </td>
                                                        <td><?= $tc_kra5 ?></td>
                                                        <?php
                                                        if ($user_target)
                                                            $kra5_percent = ($tc_kra5 / $user_target) * 100;
                                                        $kra5_percent = round($kra5_percent);
                                                        //number_format($kra4_percent, 2, '.', '');
                                                        ?>
                                                        <td><?= $kra5_percent ?>%</td>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th>Total</th>
                                                        <th><?= $total_target ?></th>
                                                        <th><?= $total_tc_achived ?></th>
                                                        <?php $total_kra = $total_tc_achived / $total_target * 100 ?>
                                                        <th><?= (is_nan($total_kra) || is_infinite($total_kra)) ? 0 : round($total_kra) ?>%</th>
                                                    </tr>

                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <br>
                                    </div>

                                <?php $i++;
                                    unset($sales_kra1, $sales_kra2, $sales_kra3, $sales_kra4, $kra1_percent, $kra2_percent, $kra3_percent, $kra4_percent, $tc_kra1, $tc_kra2, $tc_kra3, $tc_kra4, $tc_kra5);
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

    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <div id="myModal" class="modal fade" role="dialog">


    </div>
    <?php include('includes/footer.php') ?>
    <script>
        $('#myTable').DataTable({
            dom: 'Bfrtip',
            "displayLength": 15,
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
            ],
            lengthMenu: [
                [15, 25, 50, 100, 500, 1000],
                ['15', '25', '50', '100', '500', '1000']
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
        // $('#example tbody').on('click', 'tr.group', function() {
        //     var currentOrder = table.order()[0];
        //     if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
        //         table.order([2, 'desc']).draw();
        //     } else {
        //         table.order([2, 'asc']).draw();
        //     }
        // });

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
            $('#datepicker-close-date').datepicker({
                format: 'yyyy-mm-dd',
                //startDate: '-3d',
                autoclose: !0

            });

        });

        function clear_search() {
            window.location = 'kra.php';
        }
    </script>

    <script>
        $(document).ready(function() {
            var wfheight = $(window).height();
            $('.dataTables_wrapper').height(wfheight - 320);
            $("#leads").tableHeadFixer();

        });

    </script>