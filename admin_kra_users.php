<?php include('includes/header.php');
admin_page();

if ($_REQUEST['partner']) {
    $_REQUEST['partner'] =  implode(',', $_REQUEST['partner']);

    $contd = " and team_id in (" . $_REQUEST['partner'] . ")";
}
if ($_GET['users']) {
    $_GET['users'] =  implode(',', $_GET['users']);

    $user_ids = " and id in (" . $_GET['users'] . ")";
}

if (!$_GET['date_from']) {
    $_GET['date_from'] = date('Y-m-01');
}
if (!$_GET['date_to']) {
    $_GET['date_to'] = date('Y-m-t');
}
$year  =  date('Y');
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

                                    <small class="text-muted">Home >KRA</small>
                                    <h4 class="font-size-14 m-0 mt-1">VAR User's KRA</h4>
                                </div>
                            </div>
                            <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                <button type="button" class="btn btn-xs btn-light ml-1 " id="filter-box"><i class="mdi mdi-filter-menu-outline"></i></button>

                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu1 dropdown-menu-right filter_wrap_2" id="filter-container" role="menu">

                                        <form method="get" name="search">
                                            <div class="row">
                                            <?php 
                                             if ($_SESSION['sales_manager'] != 1) {
                                                $res = db_query("select * from partners where status='Active'");
                                                } else {
                                                    $res = db_query("select * from partners where status='Active' and id in (" . $_SESSION['access'] . ")");                                               
                                                }
                                            ?>
                                                <div class="form-group col-md-4">
                                                    <label class="control-label">VAR Name</label>
                                                    <select name="partner[]" id="partner" class="multiselect_partner1 form-control" multiple data-live-search="true">
                                                        <?php 
                                                        while ($row = db_fetch_array($res)) { ?>
                                                            <option <?= (in_array($row['id'], $_GET['partner']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <?php
                                                    if ($_GET['partner']) { ?>
                                                        <label class="control-label">VAR User</label>
                                                        <select name="users[]" id="users" class="multiselect_user form-control" data-live-search="true" multiple>

                                                            <?php
                                                            $_GET['partner'] =  implode(',', $_GET['partner']);
                                                            $query = db_query("SELECT * FROM users WHERE team_id in (" . $_GET['partner'] . ") and status='Active'  ORDER BY name ASC");
                                                            while ($row = db_fetch_array($query)) { ?>
                                                                <option <?= (in_array($row['id'], $_GET['users']) ? 'selected' : '') ?> value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                                            <?php } ?>
                                                        </select>

                                                    <?php } else { ?>

                                                        <label class="control-label">VAR User</label>
                                                        <div id="users">
                                                            <select name="users[]" class="multiselect_user2 form-control" data-live-search="true" multiple>

                                                            </select>
                                                        </div>

                                                    <?php } ?>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label class="control-label">KRA Parameters In</label>
                                                    <select name="kra_parameter" class="form-control">
                                                        <option value="">Select</option>
                                                        <option <?= (($_GET['kra_parameter'] == 'percent_achieved') ? 'selected' : '') ?> value="percent_achieved">%Achieved</option>
                                                        <option <?= (($_GET['kra_parameter'] == 'achieved') ? 'selected' : '') ?> value="achieved">#Achieved</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label class="control-label">Date Range:</label>
                                                    <div class="input-daterange input-group" id="datepicker_close_date">

                                                        <input type="text" value="<?php echo @$_GET['date_from'] ?>" class="form-control" id="date_from" name="date_from" placeholder="Date From" />

                                                        <input type="text" value="<?php echo @$_GET['date_to'] ?>" class="form-control" id="date_to" name="date_to" placeholder="Date To" />
                                                    </div>
                                                </div>

                                                <div class="form-group" style="margin-top: 25px;">
                                                    <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                                    <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="example23" class="table display nowrap table-striped" data-height="wfheight" data-mobile-responsive="true" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S No.</th>
                                            <th>User Name</th>
                                            <th>VAR Organization Name</th>
                                            <th>Designation</th>
                                            <th colspan="7">KRA Parameters</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>Seats Closed</th>
                                            <th>Account for Lc calling<br>with mailer validation</th>
                                            <th>New Visit</th>
                                            <th>Total Visit</th>
                                            <th>Total Log a calls</th>
                                            <th>Account for Lc calling<br>with profile remark<br>and Usage confirmation</th>
                                            <th>New Account Call</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($_GET['users']) {
                                            $sql = db_query("select * from users where status='Active' and role in ('BO','SAL','TC','AE') and team_id not in (20,45,25,37,53)  $user_ids order by team_id ");

                                        } else if($_SESSION['sales_manager'] == 1){
                                            $sql = db_query("select * from users where status='Active' and role in ('BO','SAL','TC','AE') and team_id in (" . $_SESSION['access'] . ") $contd  order by team_id");
                                        }else {
                                            $sql = db_query("select * from users where status='Active' and role in ('BO','SAL','TC','AE') and team_id not in (20,45,25,37,53) $contd  order by team_id ");
                                        }

                                        $i = 1;
                                        while ($data = db_fetch_array($sql)) {
                                           
                                            $partner = getSingleresult("select name from partners where status='Active' and id=" . $data['team_id']);
                                            
                                            //$var = getSingleresult("select cdgs_target from partners where id=" . $data['team_id']);

                                            $category = getSingleresult("select category from partners where id=" . $data['team_id']);

                                            switch ($category) {
                                                case "Platinum":
                                                    $sales_team = 2;
                                                    break;
                                                case "Gold":
                                                    $sales_team = 2;
                                                    break;
                                                case "Silver":
                                                    $sales_team = 1;
                                                    break;
                                                default:
                                                    $sales_team = 1;
                                            }

                                            $cat_target = db_query("select * from admin_kra where cdgs_category='" . $category . "' and year=" . $year);
                                            $cat_arr = db_fetch_array($cat_target);

                                            $users1 = db_query("select id,role from users where team_id='" . $data['team_id'] . "' and status='Active' ");
                                            $ids = array();

                                            while ($uid = db_fetch_array($users1)) {
                                                $ids[] = $uid['id'];
                                            }
                                            $user_ids = implode(',', $ids);

                                            /**
                                             * Sales Target  Admin KRA Tracking
                                             */

                                            /**Sales */
                                            $seatClosed_sales = sales_target($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) + iss_sales_target($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);
                                            $user_target = userTarget_Kra(2, $data['id'], $data['team_id'], $_GET['date_from'], $_GET['date_from']);

                                            $seatClosed_percent = ($seatClosed_sales / $user_target) * 100;
                                            // $seatClosed_percent = round($seatClosed_percent) . '%';
                                            $seatClosed_percent = (is_nan($seatClosed_percent) || is_infinite($seatClosed_percent)) ? 0 : round($seatClosed_percent) . '%';

                                            /**TC */
                                            $seatClosed_tc = sales_target($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) + iss_sales_target($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                            $seatClosed_target = userTarget_Kra(2, $data['id'], $data['team_id'], $_GET['date_from'], $_GET['date_from']);
                                            $seatClosed_percentTC = ($seatClosed_tc / $seatClosed_target) * 100;
                                            //$seatClosed_percentTC = round($seatClosed_percentTC) . '%';
                                            $seatClosed_percentTC = (is_nan($seatClosed_percentTC) || is_infinite($seatClosed_percentTC)) ? 0 : round($seatClosed_percentTC) . '%';

                                            /**Business Owner */
                                            $seatClosed_BO = sales_target($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) + iss_sales_target($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);
                                            $seatClosed_BO_percent = $seatClosed_BO / $cat_arr['sales_target'] * 100;

                                            $seatClosed_BO_percent = $seatClosed_BO_percent ? round($seatClosed_BO_percent) . '%' : '0%';


                                            /**
                                             * New DR Code(Leads Qualified TC) Admin KRA Tracking
                                             */

                                            /**TC */
                                            $newDR_tc = LCcalling_emailer_admin($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                            $newDR_tc_target = userTarget_Kra(1, $data['id'], $data['team_id'], $_GET['date_from'], $_GET['date_from']);
                                            $newDR_percent = ($newDR_tc / $newDR_tc_target) * 100;
                                            //$newDR_percent = round($newDR_percent) . '%';
                                            $newDR_percent = (is_nan($newDR_percent) || is_infinite($newDR_percent)) ? 0 : round($newDR_percent) . '%';

                                            /**BO */
                                            $newDR_BO = LCcalling_emailer_admin($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);
                                            //LCcalling_profiling_TCadmin($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);
                                           // tc_approved($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) + iss_approved($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                            $kra1_var = $cat_arr['new_dr'] * 4;
                                            $newDR_BO_percent = $newDR_BO / $kra1_var * 100;

                                            $newDR_BO_percent = ($newDR_BO_percent != NAN) ? round($newDR_BO_percent) . '%' : '0%';

                                            /**
                                             * New Visit Sales Admin KRA Tracking
                                             */

                                            /**Sales */
                                            $newVisit = logCall_lead_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                            logCall_lapsed_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                            logCall_raw_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                            dvr_SALES_KRA($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                            convertedDRV_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);
                                            $newVisit_target = userTarget_Kra(3, $data['id'], $data['team_id'], $_GET['date_from'], $_GET['date_from']);
                                            $newVisit_percent = ($newVisit / $newVisit_target) * 100;
                                            //$newVisit_percent = round($newVisit_percent) . '%';
                                            $newVisit_percent = (is_nan($newVisit_percent) || is_infinite($newVisit_percent)) ? 0 : round($newVisit_percent) . '%';


                                            /**BO */

                                            // $log_call = logCall_lead_BO($data['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids) + logCall_lapsed_BO($data['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids) + logCall_raw_BO($data['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                            // $dvr = dvr_BO_KRA($data['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                            // $converted_dvr = convertedDRV_BO($data['team_id'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                            $newVisit_BO = logCall_lead_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                logCall_lapsed_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                logCall_raw_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                dvr_SALES_KRA($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                convertedDRV_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                            $kra2_var = $cat_arr['monthly_account'];
                                            $newVisit_BO_percent = $newVisit_BO / $kra2_var * 100;
                                            //$newVisit_BO_percent = $newVisit_BO_percent ? round($newVisit_BO_percent) . '%' : '0%';
                                            $newVisit_BO_percent = (is_nan($newVisit_BO_percent) || is_infinite($newVisit_BO_percent)) ? 0 : round($newVisit_BO_percent) . '%';

                                            /**
                                             * Total Visit Logs Sales Admin KRA Tracking
                                             */

                                            /**Sales */
                                            $totalVisit = logDVR_lead_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                logDVR_lapsed_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                logDVR_raw_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                dvr_SALES_KRA($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                convertedDRV_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                            $totalVisit_target = userTarget_Kra(4, $data['id'], $data['team_id'], $_GET['date_from'], $_GET['date_from']);
                                            $totalVisit_percent = ($totalVisit / $totalVisit_target) * 100;
                                            //  $totalVisit_percent = round($totalVisit_percent) . '%';
                                            $totalVisit_percent = (is_nan($totalVisit_percent) || is_infinite($totalVisit_percent)) ? 0 : round($totalVisit_percent) . '%';

                                            /**BO */
                                            $total_visit_BO = logDVR_lead_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                logDVR_lapsed_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                logDVR_raw_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                dvr_SALES_KRA($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                convertedDRV_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);


                                            $totalVisit_percentBO = $total_visit_BO / 300 * 100;
                                            $totalVisit_percentBO = $totalVisit_percentBO ? round($totalVisit_percentBO) . '%' : '0%';

                                            /**
                                             * Log a calls TC Admin KRA Tracking
                                             */

                                            $logCalls = logCall_lead_TC($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                            logCall_lapsed_TC($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                            logCall_raw_TC($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                            $logCalls_target = userTarget_Kra(6, $data['id'], $data['team_id'], $_GET['date_from'], $_GET['date_from']);

                                            $logCalls_percent = ($logCalls / $logCalls_target) * 100;
                                            $logCalls_percent = (is_nan($logCalls_percent) || is_infinite($logCalls_percent)) ? 0 : round($logCalls_percent) . '%';
                                            //$logCalls_percent = round($logCalls_percent) . '%';

                                            /**BO */
                                            $logCalls_BO = logCall_lead_TC($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                            logCall_lapsed_TC($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                            logCall_raw_TC($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                            /**
                                             * Raw Leads Admin KRA Tracking
                                             */

                                            /**Sales */
                                            $rawSales = LCcalling_profiling_TCadmin($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                            $rawSales_target = userTarget_Kra(5, $data['id'], $data['team_id'], $_GET['date_from'], $_GET['date_from']);
                                            $rawSales_percent = ($rawSales / $rawSales_target) * 100;
                                            // $rawSales_percent = round($rawSales_percent) . '%';
                                            $rawSales_percent = (is_nan($rawSales_percent) || is_infinite($rawSales_percent)) ? 0 : round($rawSales_percent) . '%';

                                            /**TC */
                                            $rawTC = LCcalling_profiling_TCadmin($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);
                                            //rawLeads_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                            $rawTC_target = userTarget_Kra(5, $data['id'], $data['team_id'], $_GET['date_from'], $_GET['date_from']);

                                            $rawTC_percent = ($rawTC / $rawTC_target) * 100;
                                            //$rawTC_percent = round($rawTC_percent) . '%';
                                            $rawTC_percent = (is_nan($rawTC_percent) || is_infinite($rawTC_percent)) ? 0 : round($rawTC_percent) . '%';

                                            /**BO */

                                            $rawBO =  LCcalling_profiling_TCadmin($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);
                                            
                                            //rawLeads_SALES($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                            /**New account call per day 15 by each team */

                                            $newAccount = freshCall_lead($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                freshCall_DVR($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                freshCall_lapsed($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                freshCall_raw($data['team_id'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                            $newAccount_target = userTarget_Kra(7, $data['id'], $data['team_id'], $_GET['date_from'], $_GET['date_from']);

                                            $newAccount_percent = ($newAccount / $newAccount_target) * 100;
                                            $newAccount_percent = (is_nan($newAccount_percent) || is_infinite($newAccount_percent)) ? 0 : round($newAccount_percent) . '%';
                                        ?>
                                            <tr>
                                                <td><?= $i ?></td>
                                                <td><?= $data['name'] ?></td>
                                                <td><?= $partner ?></td>
                                                <td><?= (($data['role']) ? getSingleresult("select role_name from role where role_code='" . $data['role'] . "'") : 'N/A') ?></td>

                                                <!-- /**Seats Closed */ -->
                                                <?php if ($data['role'] == 'SAL') { ?>
                                                    <td><?= (($_GET['kra_parameter'] == 'percent_achieved') ? $seatClosed_percent : $seatClosed_sales) ?></td>
                                                <?php } else if ($data['role'] == 'TC') { ?>
                                                    <td><?= (($_GET['kra_parameter'] == 'percent_achieved') ? $seatClosed_percentTC : $seatClosed_tc) ?></td>
                                                <?php } else if ($data['role'] == 'BO') { ?>
                                                    <td><?= $seatClosed_BO ?></td>
                                                <?php } else { ?>
                                                    <td>N/A</td>
                                                <?php } ?>

                                                <!-- /**New DR Code */ -->
                                                <?php if ($data['role'] == 'SAL') { ?>
                                                    <td><?= (($_GET['kra_parameter'] == 'percent_achieved') ? $newDR_percent : $newDR_tc) ?></td>
                                                <?php  } else if ($data['role'] == 'TC') { ?>
                                                    <td> <?= (($_GET['kra_parameter'] == 'percent_achieved') ? $newDR_percent : $newDR_tc) ?></td>
                                                <?php  } else if ($data['role'] == 'BO') { ?>
                                                    <td> <?= $newDR_BO ?></td>
                                                <?php } else { ?>
                                                    <td>N/A</td>
                                                <?php } ?>

                                                <!-- /**New Visit */ -->

                                                <?php if ($data['role'] == 'TC' || $data['role'] == 'AE') { ?>
                                                    <td>N/A</td>
                                                <?php  } else if ($data['role'] == 'SAL') { ?>
                                                    <td> <?= (($_GET['kra_parameter'] == 'percent_achieved') ? $newVisit_percent : $newVisit) ?></td>
                                                <?php  } else if ($data['role'] == 'BO') { ?>
                                                    <td> <?= $newVisit_BO ?></td>
                                                <?php } ?>

                                                <!-- /**Total Visit */ -->
                                                <?php if ($data['role'] == 'TC' || $data['role'] == 'AE') { ?>
                                                    <td>N/A</td>
                                                <?php  } else if ($data['role'] == 'SAL') { ?>
                                                    <td> <?= (($_GET['kra_parameter'] == 'percent_achieved') ? $totalVisit_percent : $totalVisit) ?></td>
                                                <?php  } else if ($data['role'] == 'BO') { ?>
                                                    <td><?= $total_visit_BO ?></td>
                                                <?php } ?>

                                                <!-- /**Log a Calls */ -->
                                                <?php if ($data['role'] == 'SAL') { ?>
                                                    <td><?= (($_GET['kra_parameter'] == 'percent_achieved') ? $logCalls_percent : $logCalls) ?></td>
                                                <?php  } else if ($data['role'] == 'TC') { ?>
                                                    <td> <?= (($_GET['kra_parameter'] == 'percent_achieved') ? $logCalls_percent : $logCalls) ?></td>
                                                <?php  } else if ($data['role'] == 'BO') { ?>
                                                    <td><?= $logCalls_BO ?></td>
                                                <?php }else{ ?>
                                                    <td>N/A</td>
                                                <?php } ?>

                                                <!-- /**Raw Leads */ -->

                                                <?php if ($data['role'] == 'SAL') { ?>
                                                    <td><?= (($_GET['kra_parameter'] == 'percent_achieved') ? $rawSales_percent : $rawSales) ?></td>
                                                <?php  } else if ($data['role'] == 'TC') { ?>
                                                    <td> <?= (($_GET['kra_parameter'] == 'percent_achieved') ? $rawTC_percent : $rawTC) ?></td>
                                                <?php  } else if ($data['role'] == 'BO') { ?>
                                                    <td><?= $rawBO ?></td>
                                                <?php } else { ?>
                                                    <td>N/A</td>
                                                <?php } ?>

                                                <!-- /**New Account */ -->
                                                <?php if ($data['role'] == 'SAL') { ?>
                                                    <td><?= (($_GET['kra_parameter'] == 'percent_achieved') ? $newAccount_percent : $newAccount) ?></td>
                                                <?php  } else if ($data['role'] == 'TC') { ?>
                                                    <td><?= (($_GET['kra_parameter'] == 'percent_achieved') ? $newAccount_percent : $newAccount) ?></td>
                                                <?php  } else if ($data['role'] == 'BO') { ?>
                                                    <td><?= (($_GET['kra_parameter'] == 'percent_achieved') ? $newAccount_percent : $newAccount) ?></td>
                                                <?php } else { ?>
                                                    <td>N/A</td>
                                                <?php } ?>
                                            </tr>
                                        <?php $i++;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('includes/footer.php') ?>

        <script>
            $('#example23').DataTable({
                "displayLength": 15,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
                ],
                lengthMenu: [
                    [15, 25, 50, 100, 500, 1000],
                    ['15', '25', '50', '100', '500', '1000']
                ],
                // "processing": true,
                // "serverSide": true,
                // columnDefs: [{
                //     orderable: false,
                //     targets: 0
                // }],
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#partner').on('change', function() {
                    //alert('abc');
                    var partnerID = $(this).val();
                    //alert(partnerID);
                    if (partnerID) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajaxusers.php',
                            data: 'partner=' + partnerID,
                            success: function(html) {
                                $('#users').html(html);
                                $('.multiselect_user1').multiselect({
                                    buttonWidth: '100%',
                                    includeSelectAllOption: true,
                                    nonSelectedText: 'Select an Option'
                                });

                            },
                        });
                    }
                });
            });

            $(document).ready(function() {
                $('.multiselect_partner1').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });
                $('.multiselect_user').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });
                $('.multiselect_user2').multiselect({
                    buttonWidth: '100%',
                    includeSelectAllOption: true,
                    nonSelectedText: 'Select an Option'
                });
            });

            $(function() {
                $('#datepicker_close_date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });

            function clear_search() {
                window.location = 'admin_kra_users.php';
            }
        </script>
        <script>
            $(document).ready(function() {
                var wfheight = $(window).height();
                $('.dataTables_wrapper').height(wfheight - 320);
                $("#example23").tableHeadFixer();

            });
        </script>