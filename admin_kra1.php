<?php include('includes/header.php');
if (!$_GET['date_from']) {
    $_GET['date_from'] = date('Y-m-01');
}
if (!$_GET['date_to']) {
    $_GET['date_to'] = date('Y-m-t');
}


if ($_REQUEST['partner']) {
    $var = getSingleresult("select cdgs_target from partners where id=" . $_REQUEST['partner']);
    $category = getSingleresult("select category from partners where id=" . $_REQUEST['partner']);
}
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
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body" style="min-height: 500px;">
                            <div class="media ">
                                <img class="d-flex mr-3 rounded-circle avatar-xs" src="images/title-icon.png" alt=" ">
                                <div class="media-body">

                                    <small class="text-muted">Home >KRA</small>
                                    <h4 class="font-size-14 m-0 mt-1">VAR Organization KRA</h4>
                                </div>
                            </div>

                            <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>


                                <div class="dropdown dropdown-lg">

                                    <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">


                                        <form method="get" name="search">
                                            <?php $res = db_query("select * from partners where status='Active'");  ?>
                                            <div class="form-group">
                                                <select name="partner" id="partner" class="form-control">
                                                    <option value="">---Select Partner---</option>
                                                    <?php while ($row = db_fetch_array($res)) { ?>
                                                        <option <?= (($_GET['partner'] == $row['id']) ? 'selected' : '') ?> value='<?= $row['id'] ?>'><?= $row['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-daterange input-group" id="datepicker_close_date">
                                                    <input type="text" value="<?php echo @$_GET['date_from'] ?>" class="form-control" id="date_from" name="date_from" placeholder="Date From" />

                                                    <input type="text" value="<?php echo @$_GET['date_to'] ?>" class="form-control" id="date_to" name="date_to" placeholder="Date To" />
                                                </div>
                                            </div>


                                            <button type="submit" class="btn btn-primary"><span class="mdi mdi-magnify" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" onclick="clear_search()"><span class="mdi mdi-close" aria-hidden="true"></span></button>


                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div id="testTable">
                                <?php if ($_REQUEST['partner']) { ?>
                                    <div class="table-responsive col-md-12">
                                        <h5 class="card-subtitle">VAR Organization KRA (Business Owners)</h5>
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

                                                $users1 = db_query("select id,role from users where team_id='" . $_REQUEST['partner'] . "' and status='Active' ");
                                                $ids = array();

                                                while ($uid = db_fetch_array($users1)) {
                                                    $ids[] = $uid['id'];
                                                }
                                                $user_ids = implode(',', $ids);

                                                $achieved = newDR_KRA_BO($_REQUEST['partner'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                                $kra1_var = $var * 4;
                                                $achieve_percent_kra1 = $achieved / $kra1_var * 100;
                                                $achieve_percent_kra1 = round($achieve_percent_kra1);
                                                //number_format($achive_percent_kra1, 2, '.', '');

                                                $log_call = logCall_lead_BO($_REQUEST['partner'], $_GET['date_from'], $_GET['date_to'], $user_ids) + logCall_lapsed_BO($_REQUEST['partner'], $_GET['date_from'], $_GET['date_to'], $user_ids) + logCall_raw_BO($_REQUEST['partner'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                                $dvr = dvr_BO_KRA($_REQUEST['partner'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                                $converted_dvr = convertedDRV_BO($_REQUEST['partner'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                                $achieved2 = $log_call + $dvr + $converted_dvr;

                                                $kra2_var = $sales_team * 40;
                                                $achieve_percent_kra2 = $achieved2 / $kra2_var * 100;
                                                $achieve_percent_kra2 = round($achieve_percent_kra2);
                                                //number_format($achive_percent_kra2, 2, '.', '');

                                                $achieved3 =  rawLeads_BO($_REQUEST['partner'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                                $kra3_var = $var * 10;
                                                $achieve_percent_kra3 = $achieved3 / $kra3_var * 100;
                                                $achieve_percent_kra3 = round($achieve_percent_kra3);
                                                //number_format($achieve_percent_kra3, 2, '.', '');


                                                $achieved4 = logISS_raw_BO($_REQUEST['partner'], $_GET['date_from'], $_GET['date_to'], $user_ids);

                                                $kra4_var = $var * 8;
                                                $achieve_percent_kra4 = $achieved4 / $kra4_var * 100;
                                                $achieve_percent_kra4 = round($achieve_percent_kra4);
                                                //number_format($achieve_percent_kra4, 2, '.', '');

                                                $achieved5 = sales_target_BO($_REQUEST['partner'], $_GET['date_from'], $_GET['date_to'], $user_ids);
                                                $achieve_percent_kra5 = $achieved5 / $var * 100;
                                                $achieve_percent_kra5 = round($achieve_percent_kra5);

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
                                        <tr>
                                            <td>5</td>
                                            <td>Sales Target Achievement <i class="fa fa-info-circle" title="(CDGS Target)" data-toggle="tooltip"></i></td>
                                            <td><?= $var ?></td>
                                            <td><?= $achieved5 ?></td>
                                            <td><?= $achieve_percent_kra5 ?>%</td>
                                        </tr>

                                            </tbody>
                                        </table>

                                    </div>
                                    </div>
<br>
<div class="card-body">
                                    <div class="btn-group float-right" role="group" style="margin-top:12px;">
                                        <button type="button" class="btn btn-xs btn-light ml-1  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-filter-menu-outline"></i></button>


                                        <div class="dropdown dropdown-lg">

                                            <div class="dropdown-menu dropdown-menu-right filter_wrap" role="menu">

                                                <form method="get" name="search">

                                                    <div class="form-group">
                                                        <select name="user_t" class="form-control">
                                                            <option value="">---User Type---</option>

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


                                    <div class="row">
                                        <?php

                                        if ($_GET['user_t']) {
                                            $cond = " and role='" . $_GET['user_t'] . "'";
                                        }
                                        $sql = db_query("select id,name,user_type,role from users where team_id='" . $_REQUEST['partner'] . "' and role not in ('BO','AE') and status='Active'" . $cond);

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
                                                <h5 class="text-blue"> <?= $i ?>. <?= $data['name'] . ' (' . $profile . ')' ?></h5>
                                                <table id="" class="display nowrap table table-hover table-striped table-bordered kra_box"   data-height="wfheight1"  data-mobile-responsive="true" cellspacing="0" width="100%">
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
                                                            $sales_kra1 = sales_target($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']);


                                                            $sales_kra2 = logCall_lead_SALES($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                                logCall_lapsed_SALES($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                                logCall_raw_SALES($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                                dvr_SALES_KRA($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                                convertedDRV_SALES($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                                            $sales_kra3 = logDVR_lead_SALES($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                                logDVR_lapsed_SALES($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                                logDVR_raw_SALES($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                                dvr_SALES_KRA($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                                convertedDRV_SALES($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']);


                                                            $sales_kra4 = rawLeads_SALES($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']);


                                                            $total_sales_kra = $sales_kra1 + $sales_kra2 + $sales_kra3 + $sales_kra4
                                                        ?>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>Sales Target Achievement</td>
                                                                <td> <?php
                                                                        $total_target = 0;
                                                                        $user_target = getSingleresult("select kra from user_kra where kra_name='2' and user_id='" . $data['id'] . "' and   team_id=" . $_REQUEST['partner']);
                                                                        $total_target += $user_target;
                                                                        ?>
                                                                    <?= $user_target ?>

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
                                                                <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='3' and user_id='" . $data['id'] . "' and   team_id=" . $_REQUEST['partner']);
                                                                        $total_target += $user_target;
                                                                        ?>
                                                                    <?= $user_target ?>
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
                                                                <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='4' and user_id='" . $data['id'] . "' and   team_id=" . $_REQUEST['partner']);
                                                                        $total_target += $user_target;
                                                                        ?>
                                                                    <?= $user_target ?>
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
                                                                <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='5' and user_id='" . $data['id'] . "' and   team_id=" . $_REQUEST['partner']);
                                                                        $total_target += $user_target;
                                                                        ?>
                                                                    <?= $user_target ?>
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
                                                            $tc_kra1 = sales_target($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']);


                                                            $tc_kra2 = tc_approved($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']);


                                                            $tc_kra3 = rawLeads_SALES($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']);


                                                            $tc_kra4 = logCall_lead_TC($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                                logCall_lapsed_TC($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']) +
                                                                logCall_raw_TC($_REQUEST['partner'], $data['id'], $_GET['date_from'], $_GET['date_to']);

                                                            $total_tc_achived = $tc_kra1 + $tc_kra2 + $tc_kra3 + $tc_kra4
                                                        ?>

                                                            <tr>
                                                                <td>1</td>
                                                                <td>Sales Target Achievement</td>
                                                                <td> <?php
                                                                        $total_target = 0;
                                                                        $user_target = getSingleresult("select kra from user_kra where kra_name='2' and user_id='" . $data['id'] . "' and   team_id=" . $_REQUEST['partner']);
                                                                        $total_target += $user_target;
                                                                        ?>
                                                                    <?= $user_target ?>
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
                                                                <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='1' and user_id='" . $data['id'] . "' and   team_id=" . $_REQUEST['partner']);
                                                                        $total_target += $user_target;
                                                                        ?>
                                                                    <?= $user_target ?>
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
                                                                <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='5' and user_id='" . $data['id'] . "' and   team_id=" . $_REQUEST['partner']);
                                                                        $total_target += $user_target;
                                                                        ?>
                                                                    <?= $user_target ?>
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
                                                                <td> <?php $user_target = getSingleresult("select kra from user_kra where kra_name='6' and user_id='" . $data['id'] . "' and   team_id=" . $_REQUEST['partner']);
                                                                        $total_target += $user_target;
                                                                        ?>
                                                                    <?= $user_target ?>
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
                                                <br>
                                            </div>
                                        <?php $i++;
                                            unset($sales_kra1, $sales_kra2, $sales_kra3, $sales_kra4, $kra1_percent, $kra2_percent, $kra3_percent, $kra4_percent, $tc_kra1, $tc_kra2, $tc_kra3, $tc_kra4);
                                        } ?>
                                    </div>
                                <?php } ?>
                            </div>
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

            // function change_goal(a, b) {
            //     $.ajax({
            //         type: 'POST',
            //         url: 'get_dv_data.php',
            //         data: {
            //             uid: a,
            //             date: b
            //         },
            //         success: function(response) {
            //             $("#myModal").html();
            //             $("#myModal").html(response);
            //             $('#myModal').modal('show');
            //         }
            //     });
            // }

            let table = document.querySelector('#testTable');
            let button = document.querySelector('#cpy_button');

            function selectNode(node) {
                let range = document.createRange();
                range.selectNodeContents(node)
                let select = window.getSelection()
                select.removeAllRanges()
                select.addRange(range)
            }
            button.addEventListener('click', function() {
                selectNode(table);
                document.execCommand('copy')

            })

            $(function() {
                $('#datepicker_close_date').datepicker({
                    format: 'yyyy-mm-dd',
                    //startDate: '-3d',
                    autoclose: !0

                });

            });

            function clear_search() {
                window.location = 'admin_kra.php';
            }
        </script>